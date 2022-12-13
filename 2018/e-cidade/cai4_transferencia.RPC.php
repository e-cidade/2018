<?php
require_once("libs/db_stdlib.php");
require_once("std/db_stdClass.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");
require_once("libs/db_libcontabilidade.php");
require_once("dbforms/db_funcoes.php");
require_once("model/slip.model.php");
require_once("interfaces/ILancamentoAuxiliar.interface.php");
require_once("interfaces/IRegraLancamentoContabil.interface.php");
require_once("model/caixa/slip/Transferencia.model.php");
require_once("model/configuracao/Instituicao.model.php");
require_once("model/CgmFactory.model.php");
require_once("model/agendaPagamento.model.php");
require_once "model/contabilidade/planoconta/ContaPlano.model.php";

db_app::import("MaterialCompras");
db_app::import("caixa.*");
db_app::import("caixa.slip.*");
db_app::import("exceptions.*");
db_app::import("contabilidade.*");
db_app::import("financeiro.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("contabilidade.contacorrente.*");
db_app::import("contabilidade.planoconta.*");
db_app::import("orcamento.*");
db_app::import("configuracao.*");

$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","", $_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

$iAnoSessao         = db_getsession("DB_anousu");
$iInstituicaoSessao = db_getsession("DB_instit");


switch ($oParam->exec) {

	case "getDadosSlip" :

		try {

			$oSlip                            = TransferenciaFactory::getInstance(null, $oParam->iCodigoSlip);

			$oRetorno->iDocumentoContabil     = $oSlip->getDocumentoPorTipoInclusao();
			$oRetorno->iTipoOperacao          = (int)$oSlip->getTipoOperacaoPorInclusao();
			$oRetorno->iCodigoSlip            = $oSlip->getCodigoSlip();
			$oRetorno->iContaDebito           = $oSlip->getContaDebito();
			$oRetorno->sDescricaoContaDebito  = urlencode(ContaPlano::getDescricaoContaPorReduzido($oSlip->getContaDebito()));
			$oRetorno->iContaCredito          = $oSlip->getContaCredito();
			$oRetorno->sDescricaoContaCredito = urlencode(ContaPlano::getDescricaoContaPorReduzido($oSlip->getContaCredito()));
			$oRetorno->nValor                 = $oSlip->getValor();
			$oRetorno->iHistorico             = $oSlip->getHistorico();
			$oRetorno->sHistorico             = "";
			$oRetorno->sObservacao            = urlencode($oSlip->getObservacao());
			$oRetorno->iTipoPagamento         = $oSlip->getTipoPagamento();
			$oRetorno->iSituacao              = $oSlip->getSituacao();
			$oRetorno->dtData                 = $oSlip->getData();

			$oRetorno->sCaracteristicaDebito  = $oSlip->getCaracteristicaPeculiarDebito();
			$oRetorno->sCaracteristicaCredito = $oSlip->getCaracteristicaPeculiarCredito();

			$oRetorno->iInstituicaoDestino    = "null";
			$oRetorno->sInstituicaoDestino    = "null";

			if ($oSlip instanceof TransferenciaFinanceira) {

				$oInstituicaoDestino           = new Instituicao($oSlip->getInstituicaoDestino());
				$oRetorno->iInstituicaoDestino = $oSlip->getInstituicaoDestino();
				$oRetorno->sInstituicaoDestino = urlencode($oInstituicaoDestino->getDescricao());
			}

			$oRetorno->iInstituicaoOrigem = $oSlip->getInstituicao();

			$iInstituicaoOrigem = $iInstituicaoSessao;

			if (isset($oParam->lRecebimento) && $oParam->lRecebimento) {
				$iInstituicaoOrigem = $oSlip->getInstituicao();
			}

			$oInstituicao                          = new Instituicao($iInstituicaoOrigem);
			$oRetorno->sDescricaoInstituicaoOrigem = urlencode($oInstituicao->getDescricao());

			/**
			 * Busca os dados do CGM favorecido/concessor
			 */
			$oCgm                 = CgmFactory::getInstanceByCgm($oSlip->getCodigoCgm());
			$oRetorno->iCodigoCgm = $oSlip->getCodigoCgm();
			$oRetorno->sNomeCgm   = urlencode($oCgm->getNome());
			if (method_exists($oCgm, "getCnpj")) {
				$oRetorno->sCNPJ = db_formatar($oCgm->getCnpj(), "cnpj");
			} else {
				$oRetorno->sCNPJ = db_formatar($oCgm->getCpf(), "cpf");
			}


			// buscamos o processo
			$oRetorno->k145_numeroprocesso = null;
			$oDaoSlipProcesso = new cl_slipprocesso();
			$sSqlSlipProcesso = $oDaoSlipProcesso->sql_query_file(null, "*", null, "k145_slip = {$oParam->iCodigoSlip}");
			$rsSlipProcesso   = $oDaoSlipProcesso->sql_record($sSqlSlipProcesso);
			if ($oDaoSlipProcesso->numrows > 0) {

			  $oDadosSlipProcesso = db_utils::fieldsMemory($rsSlipProcesso, 0);
			  $oRetorno->k145_numeroprocesso = urlencode($oDadosSlipProcesso->k145_numeroprocesso);
			}



			/*
			 * descrição dos demais campos:
			 *
			 * conta debito
			 * caracteristica debito
			 * conta credito
			 * caracteristica credito
			 * descricao do historico
			 */
			$oCaracteristicaCredito = new CaracteristicaPeculiar($oRetorno->sCaracteristicaCredito);
			$oCaracteristicaDebito  = new CaracteristicaPeculiar($oRetorno->sCaracteristicaDebito);
			$oRetorno->sCaracteristicaPeculiarCredito = urlencode($oCaracteristicaCredito->getDescricao());
			$oRetorno->sCaracteristicaPeculiarDebito  = urlencode($oCaracteristicaDebito->getDescricao());

			$oDaoConHist = db_utils::getDao('conhist');
			$sSqlConHist = $oDaoConHist->sql_query($oRetorno->iHistorico);
			$rsConHist   = $oDaoConHist->sql_record($sSqlConHist);
			if ($oDaoConHist->numrows > 0) {
				$oRetorno->sHistorico = urlencode(db_utils::fieldsMemory($rsConHist, 0)->c50_descr);
			}

		} catch (Exception $eErro) {

			$oRetorno->message = urlencode(str_replace("\n", "\\n", $eErro->getMessage()));
			$oRetorno->status  = 2;
			db_fim_transacao(true);
		}


	break;

  /**
   * Salva os dados do slip
   */
  case "salvarSlip":

    db_inicio_transacao();
    try {

      $iCodigoSlip = null;
      if (isset($oParam->k17_codigo) && !empty($oParam->k17_codigo)) {
        $iCodigoSlip = $oParam->k17_codigo;
      }

      $oTransferencia = TransferenciaFactory::getInstance($oParam->iCodigoTipoOperacao, $iCodigoSlip);
      $oTransferencia->setContaDebito($oParam->k17_debito);
      $oTransferencia->setContaCredito($oParam->k17_credito);
      $oTransferencia->setValor(str_replace(",", ".", $oParam->k17_valor));
      $oTransferencia->setHistorico($oParam->k17_hist);
      $oTransferencia->setObservacao(addslashes(db_stdClass::normalizeStringJsonEscapeString($oParam->k17_texto)));
      $oTransferencia->setTipoPagamento(0);
      $oTransferencia->setSituacao(1);
      $oTransferencia->setCodigoCgm($oParam->iCGM);
      $oTransferencia->setCaracteristicaPeculiarDebito($oParam->sCaracteristicaPeculiarDebito);
      $oTransferencia->setCaracteristicaPeculiarCredito($oParam->sCaracteristicaPeculiarCredito);
      $oTransferencia->setData(date("Y-m-d",db_getsession("DB_datausu")));
      $oTransferencia->setProcessoAdministrativo(db_stdClass::normalizeStringJsonEscapeString($oParam->k145_numeroprocesso));

      if ($oTransferencia instanceof TransferenciaFinanceira) {
        $oTransferencia->setInstituicaoDestino($oParam->iCodigoInstituicaoDestino);
      }

      $oTransferencia->salvar();

      $iRecursoContaCredito = $oTransferencia->getContaPlanoCredito()->getRecurso();
      $iParametroFundeb     = ParametroCaixa::getCodigoRecursoFUNDEB($iInstituicaoSessao);

      if (empty($oParam->sCodigoFinalidadeFundeb) && $iRecursoContaCredito === $iParametroFundeb) {
        throw new BusinessException("Não foi informado o código da finalidade do FUNDEB para a conta crédito.");
      } else if ( !empty($oParam->sCodigoFinalidadeFundeb) && $iRecursoContaCredito === $iParametroFundeb) {

        $oFinalidadePagamento = FinalidadePagamentoFundeb::getInstanciaPorCodigo($oParam->sCodigoFinalidadeFundeb);
        $oTransferencia->setFinalidadePagamentoFundebCredito($oFinalidadePagamento);
        $oTransferencia->salvarFinalidadePagamentoFundeb();
      }

      if (isset($oParam->iInscricao)) {

        $iCodigoSlip       = $oTransferencia->getCodigoSlip();
        LancamentoAuxiliarSlip::vinculaSlipInscricao($iCodigoSlip, $oParam->iInscricao);
      }

      $oRetorno->message     = urlencode("Transferência {$oTransferencia->getCodigoSlip()} salva com sucesso.");
      $oRetorno->iCodigoSlip = $oTransferencia->getCodigoSlip();
      db_fim_transacao(false);

    } catch (Exception $eErro) {

      $oRetorno->message = str_replace("\n", "\\n", urlencode($eErro->getMessage()));
      $oRetorno->status  = 2;
      db_fim_transacao(true);
    }

  break;


  case "excluirSlip":

    try {

      db_inicio_transacao();
      $oTransferencia = TransferenciaFactory::getInstance(null, $oParam->iCodigoSlip);
      $oTransferencia->excluir();
      $oRetorno->message = urlencode("Slip excluído com sucesso.");

      db_fim_transacao(false);

    } catch (Exception $eErro) {

      $oRetorno->message = str_replace("\n", "\\n", urlencode($eErro->getMessage()));
      $oRetorno->status  = 2;
      db_fim_transacao(true);
    }

    break;

  /**
   * Recebe um slip originado em outro departamento e executa os lançamentos contabeis
   */
  case "receberSlip":

    db_inicio_transacao();
    try {

      $oTransferencia = new TransferenciaFinanceira($oParam->iCodigoSlipRecebido);
      $oTransferencia->setTipoPagamento(0);
      $oTransferencia->setInstituicao($oParam->iCodigoInstituicaoOrigem);
      $oTransferencia->setContaDebito($oParam->k17_debito);
      $oTransferencia->setCaracteristicaPeculiarDebito($oParam->sCaracteristicaPeculiarDebito);
      $oTransferencia->setContaCredito($oParam->k17_credito);
      $oTransferencia->setCaracteristicaPeculiarCredito($oParam->sCaracteristicaPeculiarCredito);
      $oTransferencia->setHistorico($oParam->k17_hist);
      $oTransferencia->setValor(str_replace(",", ".", trim($oParam->k17_valor)));
      $oTransferencia->setObservacao(db_stdClass::normalizeStringJsonEscapeString($oParam->k17_texto));
      $oTransferencia->setData(date("Y-m-d",db_getsession("DB_datausu")));
      $oTransferencia->setProcessoAdministrativo(db_stdClass::normalizeStringJsonEscapeString($oParam->k145_numeroprocesso));

      /**
       * Verifica qual transferência financeira o slip é originário
       * Usa essa informação para que a transferência seja marcada como recebida, na tabela transferenciafinanceirarecebimento
       */
      $oDaoTransferenciaFinanceira = db_utils::getDao('transferenciafinanceira');
      $sSqlTransferenciaFinanceira = $oDaoTransferenciaFinanceira->sql_query_file(null, "*", null, "k150_slip = {$oParam->iCodigoSlipRecebido}");
      $rsTransferenciaFinanceira   = $oDaoTransferenciaFinanceira->sql_record($sSqlTransferenciaFinanceira);

      if ($oDaoTransferenciaFinanceira->erro_status == "0") {
        throw new Exception("Não foi possível receber a transação.\n\nErro Técnico 1: {$oDaoConPlano->erro_msg}");
      }

      $iCodigoTransferencia = db_utils::fieldsMemory($rsTransferenciaFinanceira,0)->k150_sequencial;

      if ($oTransferencia instanceof TransferenciaFinanceira) {
        $oTransferencia->setInstituicaoDestino($iInstituicaoSessao);
      }

      $oTransferencia->receberTransferencia($iCodigoTransferencia);
      $oRetorno->message     = urlencode("Transferência {$iCodigoTransferencia} recebida com sucesso.");
      $oRetorno->iCodigoSlip = $oTransferencia->getCodigoSlip();

      db_fim_transacao(false);


    } catch (Exception $eException) {

      $oRetorno->message = str_replace("\\n", "\n", urlencode($eException->getMessage()));
      $oRetorno->status  = 2;
      db_fim_transacao(true);

    }
  break;

  /**
   * Anula um slip e executa os lancamentos contábeis, caso haja necessidade
   */
  case "anularSlip" :

    db_inicio_transacao();

    try {

      $oTransferencia = TransferenciaFactory::getInstance($oParam->iCodigoTipoOperacao, $oParam->k17_codigo);
      $oTransferencia->anular($oParam->sMotivo);

      /**
       * Verifica existência de lançamento contábil
       */
      $oDaoLancamentoSlip  = db_utils::getDao('conlancamslip');
      $sSqlLancamento      = $oDaoLancamentoSlip->sql_query_file(null, "*", null, "c84_slip = {$oParam->k17_codigo}");
      $rsLancamento        = $oDaoLancamentoSlip->sql_record($sSqlLancamento);

      if ($oDaoLancamentoSlip->numrows > 0) {
        $oTransferencia->executarLancamentoContabil(null, true);
      }

      $oRetorno->message = urlencode("Procedimento executado com sucesso.");

      db_fim_transacao(false);

    } catch (Exception $eErro) {

      $oRetorno->message = urlencode(str_replace("\n", "\\n", $eErro->getMessage()));
      $oRetorno->status  = 2;
      db_fim_transacao(true);
    }
  break;

  /**
   * Retorna os dados da transferência que serão apresentados no front-end
   */
  case "getDadosTransferencia" :

    try {

      $oTransferencia                   = TransferenciaFactory::getInstance($oParam->iCodigoTipoOperacao, $oParam->k17_codigo);
      $oRetorno->iCodigoSlip            = $oTransferencia->getCodigoSlip();
      $oRetorno->iContaDebito           = $oTransferencia->getContaDebito();
      $oRetorno->iContaCredito          = $oTransferencia->getContaCredito();
      $oRetorno->nValor                 = $oTransferencia->getValor();
      $oRetorno->iHistorico             = $oTransferencia->getHistorico();
      $oRetorno->sObservacao            = urlencode($oTransferencia->getObservacao());
      $oRetorno->iTipoPagamento         = $oTransferencia->getTipoPagamento();
      $oRetorno->iSituacao              = $oTransferencia->getSituacao();
      $oRetorno->dtData                 = $oTransferencia->getData();

      $oRetorno->sCaracteristicaDebito  = $oTransferencia->getCaracteristicaPeculiarDebito();
      $oRetorno->sCaracteristicaCredito = $oTransferencia->getCaracteristicaPeculiarCredito();

      if ($oTransferencia instanceof TransferenciaFinanceira) {
        $oRetorno->iInstituicaoDestino = $oTransferencia->getInstituicaoDestino();
      }

      $oRetorno->iInstituicaoOrigem = $oTransferencia->getInstituicao();

      $iInstituicaoOrigem = $iInstituicaoSessao;
      if (isset($oParam->lRecebimento) && $oParam->lRecebimento) {
        $iInstituicaoOrigem = $oTransferencia->getInstituicao();
      }

      $oInstituicao                          = new Instituicao($iInstituicaoOrigem);
      $oRetorno->sDescricaoInstituicaoOrigem = urlencode($oInstituicao->getDescricao());

      // buscamos o processo
      $oRetorno->k145_numeroprocesso = null;
      $oDaoSlipProcesso = new cl_slipprocesso();
      $sSqlSlipProcesso = $oDaoSlipProcesso->sql_query_file(null, "*", null, "k145_slip = {$oParam->k17_codigo}");
      $rsSlipProcesso   = $oDaoSlipProcesso->sql_record($sSqlSlipProcesso);
      if ($oDaoSlipProcesso->numrows > 0) {

        $oDadosSlipProcesso = db_utils::fieldsMemory($rsSlipProcesso, 0);
        $oRetorno->k145_numeroprocesso = urlencode($oDadosSlipProcesso->k145_numeroprocesso);
      }

      /**
       * Busca os dados do CGM favorecido/concessor
       */
      $oCgm                 = CgmFactory::getInstanceByCgm($oTransferencia->getCodigoCgm());
      $oRetorno->iCodigoCgm = $oTransferencia->getCodigoCgm();
      $oRetorno->sNomeCgm   = urlencode($oCgm->getNome());
      if (method_exists($oCgm, "getCnpj")) {
        $oRetorno->sCNPJ = db_formatar($oCgm->getCnpj(), "cnpj");
      } else {
        $oRetorno->sCNPJ = db_formatar($oCgm->getCpf(), "cpf");
      }


    } catch (Exception $eErro) {

      $oRetorno->message = urlencode(str_replace("\n", "\\n", $eErro->getMessage()));
      $oRetorno->status  = 2;
      db_fim_transacao(true);
    }
  break;

  /**
   * Busca as contas caixa/banco da tabela saltes
   */
  case "getContasSaltes":

    try{

      $aContasCredito = array();
      $oDaoSaltes     = db_utils::getDao("saltes");
      $sCamposSaltes  = "k13_reduz as reduzido, k13_descr as descricao";
      $sSqlSaltes     = $oDaoSaltes->sql_query(null, $sCamposSaltes, "k13_reduz");
      $rsDadosSaltes  = $oDaoSaltes->sql_record($sSqlSaltes);

      if ($oDaoSaltes->erro_status == "0") {
        throw new Exception("Não foi possível localizar as contas crédito.");
      }

      if ($oDaoSaltes->numrows > 0) {
        $aContasCredito = db_utils::getCollectionByRecord($rsDadosSaltes, false, false, true);
      }
      $oRetorno->aContas = $aContasCredito;

    } catch (BusinessException $eBEErro) {

      $oRetorno->message = urlencode(str_replace("\n", "\\n", $eBEErro->getMessage()));
      $oRetorno->status  = 2;
      db_fim_transacao(true);

    } catch (Exception $eErro) {

      $oRetorno->message = urlencode(str_replace("\n", "\\n", $eErro->getMessage()));
      $oRetorno->status  = 2;
      db_fim_transacao(true);
    }
  break;

  /**
   * Busca os dados da instituicao da sessao
   */
  case 'getDadosInstituicaoOrigem':

    $oInstituicao                       = new Instituicao($iInstituicaoSessao);
    $oRetorno->sInstituicaoOrigem       =  urlencode($oInstituicao->getDescricao());
    $oRetorno->iCodigoInstituicaoOrigem =  $oInstituicao->getSequencial();
    $oRetorno->iCodigoCgm               = $oInstituicao->getCgm()->getCodigo();
    $oRetorno->sNomeCgm                 = urlencode($oInstituicao->getCgm()->getNome());
    $oRetorno->sCNPJ                    = db_formatar($oInstituicao->getCgm()->getCnpj(), "cnpj");
  break;


  /**
   * Transferencias disponiveis para recebimento
   */
  case 'pesquisaTranferenciasRecebimento':

    try{

      $oDaoTransferenciaFinanceira       = db_utils::getDao('transferenciafinanceira');
      $sCamposTransferencia              = "k17_codigo, k17_valor, k17_data, k17_instit, k17_hist, k150_instituicao";
      $sWhereTransferencia               = " (k151_sequencial is null or k151_estornado is true) and k150_instituicao = {$iInstituicaoSessao}";
      $sWhereTransferencia              .= " and k17_dtaut is not null ";
      $sWhereTransferencia              .= " and not exists(select 1 ";
      $sWhereTransferencia              .= "             from transferenciafinanceirarecebimento rec";
      $sWhereTransferencia              .= "            where rec.k151_transferenciafinanceira = k150_sequencial";
      $sWhereTransferencia              .= "              and rec.k151_estornado is false)";
      $sWhereTransferencia              .= " group by {$sCamposTransferencia} ";
      $sSqlBuscaTransferenciasPendentes  = $oDaoTransferenciaFinanceira->sql_query_recebimento(null, $sCamposTransferencia, "k17_codigo", $sWhereTransferencia);

      $rsBuscaTransferencia              = $oDaoTransferenciaFinanceira->sql_record($sSqlBuscaTransferenciasPendentes);

      if ($oDaoTransferenciaFinanceira->numrows == 0) {
        throw new Exception("Nenhuma transferência para a instituição.");
      }

      $aTransferenciasRecebimento = array();
      for ($iRowTransferencia = 0; $iRowTransferencia < $oDaoTransferenciaFinanceira->numrows; $iRowTransferencia++) {

        $oDadoTransferencia          = db_utils::fieldsMemory($rsBuscaTransferencia, $iRowTransferencia);
        $oDaoHistorico               = db_utils::getDao('conhist');
        $sSqlBuscaDescricaoHistorico = $oDaoHistorico->sql_query_file($oDadoTransferencia->k17_hist);
        $rsBuscaHistorico            = $oDaoHistorico->sql_record($sSqlBuscaDescricaoHistorico);
        if ($oDaoHistorico->numrows == 0) {
          throw new Exception("Não foi possível localizar o histórico.");
        }

        $oDadoTransferencia->c50_compl = urlencode(db_utils::fieldsMemory($rsBuscaHistorico, 0)->c50_descr);

        $oInstituicaoOrigem = new Instituicao($oDadoTransferencia->k17_instit);
        $oDadoTransferencia->sInstituicaoOrigem = urlencode($oInstituicaoOrigem->getDescricao());
        $oDadoTransferencia->k17_data = db_formatar($oDadoTransferencia->k17_data, "d");
        $oDadoTransferencia->nValor   = $oDadoTransferencia->k17_valor;
        $aTransferenciasRecebimento[] = $oDadoTransferencia;
      }
      $oRetorno->aTransferenciasRecebimento = $aTransferenciasRecebimento;

    } catch (BusinessException $eBEErro) {

      $oRetorno->message = urlencode(str_replace("\n", "\\n", $eBEErro->getMessage()));
      $oRetorno->status  = 2;
      db_fim_transacao(true);

    } catch (Exception $eErro) {

      $oRetorno->message = urlencode(str_replace("\n", "\\n", $eErro->getMessage()));
      $oRetorno->status  = 2;
      db_fim_transacao(true);
    }
  break;

  case 'pesquisaEstornoRecebimento':

    try{

      $oDaoRecebimento = db_utils::getDao('transferenciafinanceirarecebimento');
      /**
       * Busca todas transferências com Situação igual a 2
       * Slip Autenticado
       */
      $sWhere          = "slip.k17_instit = {$iInstituicaoSessao} and slip.k17_situacao = 2";
      $sSqlRecebimento = $oDaoRecebimento->sql_query(null, "*", null, $sWhere);
      $rsRecebimento   = $oDaoRecebimento->sql_record($sSqlRecebimento);

      if ($oDaoRecebimento->erro_status == "0") {
        throw new Exception("Não há transferências para efetuar recebimento.");
      }


      $oRetorno->aTransferenciasRecebimento = array();

      for ($i = 0; $i < $oDaoRecebimento->numrows; $i++) {

        $oConsulta      = db_utils::fieldsMemory($rsRecebimento, $i);

        $oTransferenciaFinanceira = new TransferenciaFinanceira($oConsulta->k17_codigo);

        /**
         *  Se não possuir recebimento, ela deve ser listada na Grid
         */
        $oTransferencia = new stdClass();

        //Seta Propriedade Código do slip e valor da transferência
        $oTransferencia->k17_codigo = $oConsulta->k17_codigo;
        $oTransferencia->nValor     = db_formatar($oConsulta->k17_valor,"f");
        $oTransferencia->k17_data   = $oTransferenciaFinanceira->getData();



        //Busca e seta Instituição Origem
        $iInstitOrigem       = $oConsulta->k17_instit;
        $oDaoInstituicao     = db_utils::getDao('db_config');
        $sSqlInstituicao     = $oDaoInstituicao->sql_query(null, "nomeinst" , null, "codigo = {$iInstitOrigem}");
        $rsInstit            = $oDaoInstituicao->sql_record($sSqlInstituicao);
        $sInstituicaoOrigem  = urlencode(db_utils::fieldsMemory($rsInstit, 0)->nomeinst);
        unset($oDaoInstituicao);

        $oTransferencia->sInstituicaoOrigem = $sInstituicaoOrigem;

        //Busca e seta descrição Histórico
        $iHistorico     = $oConsulta->k17_hist;
        $oDaoHistorico  = db_utils::getDao('conhist');
        $sSQLHistorico  = $oDaoHistorico->sql_query($iHistorico,"c50_descr");
        $rsHistorico    = $oDaoHistorico->sql_record($sSQLHistorico);
        $sHistorico     = db_utils::fieldsMemory($rsHistorico, 0)->c50_descr;
        unset($oDaoHistorico);
        $oTransferencia->c50_compl = $sHistorico;

        $oRetorno->aTransferenciasRecebimento[] = $oTransferencia;

        unset($oTransferencia);
        unset($oConsulta);
      }


    } catch (Exception $eErro) {

      $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
      $oRetorno->status  = 2;
    }
  break;

  /**
   * Retornas as contas de um evento contabil de ordem 1
   *
   * Valido se o parametro lContaCredito esta setado e é 'true', caso seja, alteramos o método que será utilizado para
   * buscar as contas
   */
  case "getContaEventoContabil";

    try {

      /*
       * Verifico que método utilizar para buscar as contas na conplano
       */
      $sMetodoConta = 'getContaDebito';
      if (isset($oParam->lContaCredito) && $oParam->lContaCredito == true) {
        $sMetodoConta = 'getContaCredito';
      }

      $aContasEvento   = array();
      $oEventoContabil = new EventoContabil(getDocumentoPorTipoInclusao($oParam->iTipoTransferencia), $iAnoSessao);
      $aLancamentos    = $oEventoContabil->getEventoContabilLancamento();
      foreach ($aLancamentos as $oLancamento) {

        if ($oLancamento->getOrdem() == 1) {

          $aRegrasLancamento = $oLancamento->getRegrasLancamento();
          foreach ($aRegrasLancamento as $oContaRegraLancamento) {


            $oDaoConPlano   = db_utils::getDao("conplano");
            $sCamposPlano   = " c61_reduz as reduzido, c60_descr  as descricao";
            $sWherePlano    = " conplanoreduz.c61_reduz = {$oContaRegraLancamento->$sMetodoConta()} ";
            $sSqlDadosConta = $oDaoConPlano->sql_query(null, null,$sCamposPlano, null, $sWherePlano);
            $rsDadosConta   = $oDaoConPlano->sql_record($sSqlDadosConta);

            if ($oDaoConPlano->erro_status == "0") {
              throw new Exception("Não foi possível localizar as contas para débito.");
            }

            if ($oDaoConPlano->numrows > 0) {

              $oDadoConta      = db_utils::fieldsMemory($rsDadosConta, 0);
              $aContasEvento[] = $oDadoConta;
            }
          }
          break;
        }
        break;
      }
      $oRetorno->aContas = $aContasEvento;

    } catch (Exception $eException) {

      $oRetorno->message = urlencode(str_replace("\\n", "\n", $eException->getMessage()));
      $oRetorno->status  = 2;
    }

  break;

  /**
   * Busca conta do lançamento da inscrição
   * quando for do tipo baixa de pagamento
   */
  case "getContaFavorecido":

    $oDAOInscricaoPassivo     = db_utils::getDao("inscricaopassivo");
    $sCampos                  = "c69_credito as conta_credito_reduzido, ";
    $sCampos                 .= "c36_cgm     as cgm_favorecido,  ";
    $sCampos                 .= "c60_descr   as descricao  ";
    $sWhere                   = "     conlancaminscricaopassivo.c37_instit = ".db_getsession("DB_instit");
    $sWhere                  .= " and conlancaminscricaopassivo.c37_inscricaopassivo = {$oParam->iInscricao}";
    $sWhere                  .= " and c70_anousu =".db_getsession("DB_anousu");

    $sSqlLancamentoInscricao  = $oDAOInscricaoPassivo->sql_lancamento_inscricao(null, $sCampos, null, $sWhere);
    $rsLancamentoInscricao   = $oDAOInscricaoPassivo->sql_record($sSqlLancamentoInscricao);

    if ($oDAOInscricaoPassivo->numrows != 1) {
      throw new Exception("Não foi possível localizar a conta vinculada à inscrição");
    }

    $oDadosLancamentoInscricao = db_utils::fieldsMemory( $rsLancamentoInscricao, 0);
    $oConta                    = new stdClass();
    $oConta->reduzido          = $oDadosLancamentoInscricao->conta_credito_reduzido;
    $oConta->descricao         = $oDadosLancamentoInscricao->descricao;
    $oRetorno->iFavorecido     = $oDadosLancamentoInscricao->cgm_favorecido;
    $oRetorno->aContas         = array();
    $oRetorno->aContas[]       = $oConta;

    /**
     * Busca os dados do CGM favorecido/concessor
     */
    $oCgm                  = CgmFactory::getInstanceByCgm($oRetorno->iFavorecido);
    $oRetorno->sFavorecido = urlencode($oCgm->getNome());

    /**
     * Resgata o valor total da inscrição
     */
    $oInscricaoPassivo              = new InscricaoPassivoOrcamento($oParam->iInscricao);
    $aItensInscricao                = $oInscricaoPassivo->getItens();
    $nTotalInscricao                = $oInscricaoPassivo->getValorTotalInscricao();
    $oRetorno->nValorTotalInscricao = $nTotalInscricao;

    if (method_exists($oCgm, "getCnpj")) {
      $oRetorno->sCNPJ                       = db_formatar($oCgm->getCnpj(), "cnpj");
    } else {
      $oRetorno->sCNPJ                       = db_formatar($oCgm->getCpf(), "cpf");
    }
    break;

  case "getDadosInscricao":

    $oInscricao      = new InscricaoPassivoOrcamento($oParam->iCodigoInscricao);

    $oRetorno->nValorTotalInscricao = $oInscricao->getValorTotalInscricao();;

    $oRetorno->iCgmFavorecido       = $oInscricao->getFavorecido()->getCodigo();
    $oRetorno->sNomeFavorecido      = urlencode($oInscricao->getFavorecido()->getNomeCompleto());

    $oDAOInscricaoPassivo     = db_utils::getDao("inscricaopassivo");
    $sCampos                  = "c69_credito as conta_debito, ";
    $sCampos                 .= "c36_cgm     ,  ";
    $sCampos                 .= "c60_descr    ";
    $sWhere                   = "     conlancaminscricaopassivo.c37_instit = ".db_getsession("DB_instit");
    $sWhere                  .= " and conlancaminscricaopassivo.c37_inscricaopassivo = {$oParam->iCodigoInscricao}";
    $sWhere                  .= " and c70_anousu =".db_getsession("DB_anousu");
    $sSqlLancamentoInscricao  = $oDAOInscricaoPassivo->sql_lancamento_inscricao(null, $sCampos, null, $sWhere);
    $rsLancamentoInscricao    = $oDAOInscricaoPassivo->sql_record($sSqlLancamentoInscricao);
    $sDescricaoContaDebito    = '';
    $iContaContaDebito        = '';
    if ( $rsLancamentoInscricao && $oDAOInscricaoPassivo->numrows > 0) {

      $oLancamentoInscricao   = db_utils::fieldsMemory($rsLancamentoInscricao, 0);
      $sDescricaoContaDebito  = urlencode($oLancamentoInscricao->c60_descr);
      $iContaContaDebito      = $oLancamentoInscricao->conta_debito;
    }

    $oRetorno->iContaDebito         = $iContaContaDebito;
    $oRetorno->sDescrContaDebito    = $sDescricaoContaDebito;

    break;

  case "getAutenticacoesSlip":

    $sWhere  = "       k12_codigo = {$oParam->iCodigoSlip}";
    $sWhere .= " order by corlanc.k12_data,";
    $sWhere .= "          corrente.k12_autent";

    $sCampos  = "corrente.k12_id,";
    $sCampos .= "corrente.k12_data,";
    $sCampos .= "corrente.k12_hora,";
    $sCampos .= "corrente.k12_autent,";
    $sCampos .= "corrente.k12_valor,";
    $sCampos .= "e91_cheque,";
    $sCampos .= "e96_descr as descricao,";
    $sCampos .= "(select coalesce(c86_conlancam, 0)
                    from conlancamcorrente
                   where c86_id     = corrente.k12_id
                     and c86_data   = corrente.k12_data
                     and c86_autent = corrente.k12_autent) as codigo_lancamento";

    $oDaoCorLanc = db_utils::getDao('corlanc');
    $sSqlBuscaAutenticacao = $oDaoCorLanc->sql_query_slip(null, null, null, $sCampos, null, $sWhere);
    $rsBuscaAutenticacao   = $oDaoCorLanc->sql_record($sSqlBuscaAutenticacao);
    $aDadosRetorno         = array();
    if ($oDaoCorLanc->numrows > 0) {
      $aDadosRetorno = db_utils::getCollectionByRecord($rsBuscaAutenticacao);
    }

    $oRetorno->aAutenticacoes = $aDadosRetorno;
    break;

  case "getFinalidadePagamentoTransferencia":

    $oTransferencia       = TransferenciaFactory::getInstance(null, $oParam->iCodigoSlip);
    $oFinalidadePagamento = $oTransferencia->getFinalidadePagamentoFundebCredito();

    $oRetorno->lPossuiFinalidadePagamento = false;
    if (!empty($oFinalidadePagamento)) {

      $oRetorno->oFinalidadePagamentoFundeb                 = new stdClass();
      $oRetorno->oFinalidadePagamentoFundeb->e151_codigo    = $oFinalidadePagamento->getCodigo();
      $oRetorno->oFinalidadePagamentoFundeb->e151_descricao = urlencode($oFinalidadePagamento->getDescricao());
      $oRetorno->lPossuiFinalidadePagamento = true;
    }

    break;
}
echo $oJson->encode($oRetorno);

/**
 * Retorna o código do documento para executar na inclusão de um slip
 * @param integer $iTipoOperacao
 * @return integer
 */
function getDocumentoPorTipoInclusao($iTipoOperacao) {

  $iCodigoDocumento = 0;
  switch ($iTipoOperacao) {

    /**
     * Transferencia Financeira
     */
    case 1:
  	case 2:
  	  $iCodigoDocumento = 120;
	    break;
  	case 3:
  	case 4:
  	  $iCodigoDocumento = 130;
	  break;

		/**
		 * Transferencia Bancaria
		 */
  	case 5:
  	case 6:
  	  $iCodigoDocumento = 140;
		break;

    /**
     * Caução
     */
  	case 7:
  	case 8:
  	  $iCodigoDocumento = 150;
	  break;
  	case 9:
  	case 10:
  	  $iCodigoDocumento = 151;
	  break;

	  /**
	   * Depósito de Diversas Origens
	   */
  	case 11:
  	case 12:
  	  $iCodigoDocumento = 160;
  	break;

  	case 13:
  	case 14:
  	  $iCodigoDocumento = 161;
	  break;
  }

  return $iCodigoDocumento;
}
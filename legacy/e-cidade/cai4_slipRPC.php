<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once('model/contabilidade/SingletonDocumentoContabil.model.php');
require_once("model/slip.model.php");
require_once("interfaces/ILancamentoAuxiliar.interface.php");
require_once("interfaces/IRegraLancamentoContabil.interface.php");
require_once("model/caixa/slip/Transferencia.model.php");
require_once("model/configuracao/Instituicao.model.php");
require_once("model/CgmFactory.model.php");
require_once("model/agendaPagamento.model.php");
require_once "model/contabilidade/planoconta/ContaPlano.model.php";

$oGet              = db_utils::postMemory($_GET);
$oJson             = new services_json();
$sStringToParse    = str_replace("<aspa>",'\"',str_replace("\\","",$_POST["json"]));
$oParam            = $oJson->decode($sStringToParse);

$oRetorno          = new stdClass;
$oRetorno->status  = 1;
$oRetorno->message = "";
$oRetorno->itens   = array();

if ($oParam->exec == "isExtra") {

  $oRetorno->lExtra = false;
  $oDaoTabPlan       = db_utils::getDao("tabplan");
  $sWhere            = "k02_reduz = {$oParam->conta} and k02_anousu = ".db_getsession("DB_anousu");
  $sSqlTabplan       = $oDaoTabPlan->sql_query_file(null, null,"*", null, $sWhere);
  $rsTabplan         = $oDaoTabPlan->sql_record($sSqlTabplan);
  if ($oDaoTabPlan->numrows > 0 ) {
    $oRetorno->lExtra = true;
  }

} else if ($oParam->exec == "autenticarSlip") {  // case para autenticação de slip

	 $iCodigoSlip = $oParam->iCodigoSlip;
	 $iAcao       = $oParam->iAcao;

  try {

	  db_inicio_transacao();

	  $oTransferencia = TransferenciaFactory::getInstance(null, $iCodigoSlip);

	  switch ($iAcao) {

	  	case 1 : // Autenticar

	  		/**
	  		 *  Operações que devem realizar lançamento contábil na inclusão
	  		 */
	  		$aTipoOperacao = array(3, 4, 7, 8, 11, 12);

	  		/**
	  		 * Faz lançamento contábil para os tipos de operação descritos acima
	  		 * ou quando for um recebimento de pagamento de uma transferencia
	  		*/

	  		if (in_array($oTransferencia->getTipoOperacao(), $aTipoOperacao)) {

	  			$oTransferencia->executaAutenticacao();
	  			$oTransferencia->executarLancamentoContabil();
	  		}

	  	break;

	  	case 2 : // Anular


	  		$sMotivo  = addslashes(db_stdClass::normalizeStringJson($oParam->sMotivo));
	  		$oDaoLancamentoSlip  = db_utils::getDao('conlancamslip');
	  		$sSqlLancamento      = $oDaoLancamentoSlip->sql_query_file(null, "*", null, "c84_slip = {$iCodigoSlip}");
	  		$rsLancamento        = $oDaoLancamentoSlip->sql_record($sSqlLancamento);

	  		$oTransferencia->anular($sMotivo);

	  		if ($oDaoLancamentoSlip->numrows > 0) {
	  			$oTransferencia->executarLancamentoContabil(null, true);
	  		}
	  	break;
	  }

	  db_fim_transacao(false);
	  $oRetorno->message = "Procedimento realizado com sucesso.";

  } catch (Exception $eException) {

		db_fim_transacao(true);
		$oRetorno->status  = 2;
		$oRetorno->message = urlencode($eException->getMessage());
	}

} else if ($oParam->exec == "isSaldoContaRecurso") {

  $iAnoUsu          = db_getsession("DB_anousu");
  $sSqlTabPlan      = "  select o15_codigo,                                                                           ";
  $sSqlTabPlan     .= "         o15_descr,                                                                            ";
  $sSqlTabPlan     .= "         ( select rnsaldofinal                                                                 ";
  $sSqlTabPlan     .= "             from fc_saltessaldoextrainicial(k02_codigo, current_date)) as   saldoini          ";
  $sSqlTabPlan     .= "    from tabplan                                                                               ";
  $sSqlTabPlan     .= "         inner join tabplansaldorecurso  on k02_codigo   = k111_tabplan                        ";
  $sSqlTabPlan     .= "                                        and k02_anousu   = k111_anousu                         ";
  $sSqlTabPlan     .= "         inner join orctiporec           on k111_recurso = o15_codigo                          ";
  $sSqlTabPlan     .= "   where k02_reduz  = {$oParam->conta}                                                         ";
  $sSqlTabPlan     .= "     and k02_anousu = {$iAnoUsu}                                                               ";
  $rsSqlTabPlan     = db_query($sSqlTabPlan);
  $iNumRows         = pg_num_rows($rsSqlTabPlan);
  if ($iNumRows == 0) {
    $oRetorno->status = 2;
  } else {
    $oRetorno->itens  = db_utils::getCollectionByRecord($rsSqlTabPlan,false,false,true);
  }

} else if ($oParam->exec == "getPagamentosEmAberto") {

  /**
   * Verificamos a data do inicio do controle do saldo das extras
   */
  $dtData  = null;
  $aParam = db_stdClass::getParametro("caiparametro", array(db_getsession("DB_instit")));
  if (count($aParam > 0)) {
    $dtData  = $aParam[0]->k29_datasaldocontasextra;
  }

  if ($dtData != null) {

    $sWhereAlt  = "";
    $sWhere     = "";
    if ($oParam->k17_codigo != null) {
      $sWhereAlt  = " or k112_slip ={$oParam->k17_codigo}";
    }
    if ($oParam->lApenasSlip) {
      $sWhere = "and k112_slip ={$oParam->k17_codigo}";
    }
    $oDaoTabPlan     = db_utils::getDao("tabplan");
    $sWhereRec       = "k02_reduz = {$oParam->conta} and k02_anousu = ".db_getsession("DB_anousu");
    $sSqlReceita     = $oDaoTabPlan->sql_query_file(null, null,"*", null, $sWhereRec);
    $rsReceita       = $oDaoTabPlan->sql_record($sSqlReceita);
    if ($oDaoTabPlan->numrows > 0) {

      $oReceita        = db_utils::fieldsMemory($rsReceita, 0);
      $sSqlRegistros   = "select cornump.k12_numpre, ";
      $sSqlRegistros  .= "       k00_recurso,";
      $sSqlRegistros  .= "       (select max(k12_data) from cornump  a where a.k12_numpre = cornump.k12_numpre) as data,";
      $sSqlRegistros  .= "       k00_recurso,";
      $sSqlRegistros  .= "       coalesce(sum(corrente.k12_valor), 0) as valor";
      $sSqlRegistros  .= "  from corrente  ";
      $sSqlRegistros  .= "       inner join cornump on corrente.k12_data   = cornump.k12_data ";
      $sSqlRegistros  .= "                         and corrente.k12_id     = cornump.k12_id ";
      $sSqlRegistros  .= "                         and corrente.k12_autent = cornump.k12_autent ";
      $sSqlRegistros  .= "       inner join reciborecurso on k00_numpre = cornump.k12_numpre  ";
      $sSqlRegistros  .= " where corrente.k12_data >= '{$dtData}'";
      $sSqlRegistros  .= "   and cornump.k12_receit = {$oReceita->k02_codigo}";
      $sSqlRegistros  .= "   and not exists( select 1
                                               from slipcorrente ";
      $sSqlRegistros  .= "                    where k112_data   = corrente.k12_data
                                                and k112_autent = corrente.k12_autent
                                                and k112_id     = corrente.k12_id
                                                and ( k112_ativo is true {$sWhereAlt}))";
      $sSqlRegistros  .= $sWhere;
      $sSqlRegistros  .= " group by cornump.k12_numpre,";
      $sSqlRegistros  .= "       k00_recurso ";
      $sSqlRegistros  .= "having sum(corrente.k12_valor) > 0";
      $sSqlRegistros  .= " order by cornump.k12_numpre";
      $rsRegistos      = db_query($sSqlRegistros);
      $oRetorno->itens = db_utils::getCollectionByRecord($rsRegistos);

    }
  }
} else if ($oParam->exec == "incluirSlip") {

  $numslip = null;
  $sMsgErro = "";
  $lErro    = false;
  if (trim($oParam->k17_debito) == "") {

    $sMsgErro = "Conta a Debitar(Receber) não Informada";
    $lErro    = true;

  }

  if (trim($oParam->k17_credito) == "" && $lErro == false) {

    $sMsgErro = "Conta a Creditar(Pagar) não Informada";
    $lErro    = true;

  }
  if (empty($oParam->k17_valor) || $oParam->k17_valor < 0) {

    $sMsgErro = "Informe o valor do slip!";
    $lErro    = true;

  }
  db_inicio_transacao();
  if ($oParam->k17_codigo != "" ) {

    $numslip = $oParam->k17_codigo;
    $clsliprecurso = db_utils::getDao("sliprecurso");
    $clsliprecurso->excluir(null," k29_slip = $numslip ");

    if ($clsliprecurso->erro_status == 0) {

      $sMsgErro = $clsliprecurso->erro_msg;
      $lErro = true;
    }

    $clslipnum = db_utils::getDao("slipnum");
    $clslipnum->excluir($numslip);
    if ($clslipnum->erro_status == 0) {

       $sMsgErro = $clslipnum->erro_msg;
       $lErro = true;
    }

    $clempageslip = db_utils::getDao("empageslip");
    $sSqlMov = $clempageslip->sql_query_file(null,$numslip);
    $rsMovSlip = $clempageslip->sql_record($sSqlMov);

    if ($clempageslip->numrows > 0) {

      $oMovimentoSlip = db_utils::fieldsMemory($rsMovSlip, 0);
      $clempageslip->excluir($oMovimentoSlip->e89_codmov);

      if ($clempageslip->erro_status == 0){

        $lErro = true;
        $sMsgErro = $clempageslip->erro_msg;
      }

      $oDaoEmpPag = db_utils::getDao("empagepag");
      $oDaoEmpPag->excluir($oMovimentoSlip->e89_codmov);

      if ($oDaoEmpPag->erro_status == 0){

        $lErro = true;
        $sMsgErro = $oDaoEmpPag->erro_msg;

      }

      $oDaoNotasOrdem = db_utils::getDao("empagenotasordem");
      $oDaoNotasOrdem->excluir(null,"e43_empagemov={$oMovimentoSlip->e89_codmov}");
      if ($oDaoNotasOrdem->erro_status == 0){

         $lErro = true;
         $sMsgErro = $oDaoNotasOrdem->erro_msg;
      }

      $oDaoEmpageConfChe = db_utils::getDao("empageconfche");
      $oDaoEmpageConfChe->excluir(""," e91_codmov = {$oMovimentoSlip->e89_codmov}");

      if ($oDaoEmpageConfChe->erro_status == 0){

        $lErro = true;
        $sMsgErro = $oDaoEmpageConfChe->erro_msg."\nExclusão de configuração de Cheque não efetuada";
      }

      $oDaoEmpageMov = db_utils::getDao("empagemov");
      $oDaoEmpageMov->excluir($oMovimentoSlip->e89_codmov);
      if ($oDaoEmpageMov->erro_status == 0){

        $lErro = true;
        $sMsgErro = $oDaoEmpageMov->erro_msg;
      }
    }

    $oDaoSlipCorrente = db_utils::getDao("slipcorrente");
    $oDaoSlipCorrente->excluir(null,"k112_slip = {$numslip}");
    if ($oDaoSlipCorrente->erro_status == 0) {
        $lErro = true;
        $sMsgErro = $oDaoSlipCorrente->erro_msg;
    }

  }

  if (!$lErro) {

    $clslip                    =  db_utils::getDao("slip");
    $clslip->k17_data          = date("Y-m-d",db_getsession("DB_datausu"));
    $clslip->k17_debito        = $oParam->k17_debito;
    $clslip->k17_credito       = "{$oParam->k17_credito}";
    $clslip->k17_valor         = "$oParam->k17_valor";
    $clslip->k17_hist          = $oParam->k17_hist;
    $clslip->k17_texto         = utf8_decode(urldecode(str_replace("/n", "\n", $oParam->k17_obs)));
    $clslip->k17_instit        = db_getsession("DB_instit");
    $clslip->k17_dtanu         = "";
    $clslip->k17_dtestorno     = "";
    $clslip->k17_tipopagamento = "{$oParam->k17_tipopagamento}";
    $clslip->k17_situacao      = 1;

    if ($numslip == null) {
      $clslip->incluir(null);
    } else {

      $clslip->k17_codigo      = $numslip;
      $clslip->alterar($numslip);
    }

    $numsliprel           = $clslip->k17_codigo;

    if ($clslip->erro_status == "0") {

      $lErro    = true;
      $sMsgErro = $clslip->erro_msg;
    }
  }

  /**
   * Manutenção de dados em SLIPCONCARPECULIAR
   */
  if (!$lErro) {

    $oDaoSlipConCar = db_utils::getDao('slipconcarpeculiar');

    /**
     * Exclui os dados em slipconcarpeculiar para inserir novamente ou não caso o usuário não tenha preenchido
     * os parâmetros no formulário
     */
    $oDaoSlipConCar->excluir(null, "k131_slip = {$clslip->k17_codigo} and (k131_tipo = 1 or k131_tipo = 2)");

    if ($oDaoSlipConCar->erro_status == "0") {

      $lErro    = true;
      $sMsgErro = $oDaoSlipConCar->erro_msg;
    }

    /**
     * Inclui caso o usuário tenha preenchido a característica peculiar do débito
     */
    if ($oParam->iCPCADebito != "") {

      $oDaoSlipConCar->k131_slip           = $clslip->k17_codigo;
      $oDaoSlipConCar->k131_tipo           = 1;
      $oDaoSlipConCar->k131_concarpeculiar = $oParam->iCPCADebito;
      $oDaoSlipConCar->incluir(null);

      if ($oDaoSlipConCar->erro_status == "0") {

        $lErro    = true;
        $sMsgErro = $oDaoSlipConCar->erro_msg;
      }
    }

    /**
     * Inclui caso o usuário tenha preenchido a característica peculiar do crédito
     */
    if ($oParam->iCPCACredito != "") {

      $oDaoSlipConCar->k131_slip           = $clslip->k17_codigo;
      $oDaoSlipConCar->k131_tipo           = 2;
      $oDaoSlipConCar->k131_concarpeculiar = $oParam->iCPCACredito;
      $oDaoSlipConCar->incluir(null);

      if ($oDaoSlipConCar->erro_status == "0") {

        $lErro    = true;
        $sMsgErro = $oDaoSlipConCar->erro_msg;
      }
    }

    /**
     * Verifica se o parâmetro de caracteristica peculiar para Credito veio preenchido.
     * Caso sim, será excluido o registro já existente e então incluido novamente com os novos valores
     */
    if ($oParam->iCPCACredito != "") {

      /**
       * Exclui a caracteristica da tabela slipconcarpeculiar caso exista e insere novamente
       */
      $oDaoSlipConCar->excluir(null, "k131_slip = {$clslip->k17_codigo} and k131_tipo = 2");
      if ($oDaoSlipConCar->erro_status == "0") {

        $lErro    = true;
        $sMsgErro = $oDaoSlipConCar->erro_msg;
      }

      /**
       * Seta as propriedades em slipconcarpeculiar para efetuar a inclusão
       */
      $oDaoSlipConCar->k131_slip           = $clslip->k17_codigo;
      $oDaoSlipConCar->k131_tipo           = 2;
      $oDaoSlipConCar->k131_concarpeculiar = $oParam->iCPCACredito;
      $oDaoSlipConCar->incluir(null);

      if ($oDaoSlipConCar->erro_status == "0") {

        $lErro    = true;
        $sMsgErro = $oDaoSlipConCar->erro_msg;
      }
    }
  }


  if (!$lErro) {

    /**
     * Agendamos o slip caso o parametro emparametro.e30_agendaautomatico = true.
     */
    require_once("model/agendaPagamento.model.php");
    $oInstit = db_stdClass::getDadosInstit();
    if ($oParam->k17_numcgm == "") {
      $oParam->k17_numcgm = $oInstit->numcgm;
    }
    $oAgendaPagamento = new agendaPagamento();
    $oSlipAgenda = new stdClass();
    $oSlipAgenda->iCodigoSlip = $clslip->k17_codigo;
    $oSlipAgenda->nValor      = "$oParam->k17_valor";

    /**
     * Procuramos se a conta credito do slip é uma conta pagadora no caixa.
     * caso for. setamos essa conta como conta pagadora na agenda.
     */
    $oParametroAgenda = (db_stdClass::getParametro("empparametro",array(db_getsession('DB_anousu')),
                                                   "e30_agendaautomatico"));
    if ($oParametroAgenda[0]->e30_agendaautomatico == "t" ) {
      if ($oParam->k17_credito != 0 ) {

        $oDaoEmpAgeTipo = db_utils::getDao("empagetipo");
        $sSqlConta      = $oDaoEmpAgeTipo->sql_query_file(null,"e83_codtipo", null,"e83_conta = {$oParam->k17_credito}");
        $rsConta        = $oDaoEmpAgeTipo->sql_record($sSqlConta);
        if ($oDaoEmpAgeTipo->numrows > 0 ) {
          $oSlipAgenda->iCodTipo = db_utils::fieldsMemory($rsConta,0)->e83_codtipo;
        }
      }

      try {
        $oAgendaPagamento->addMovimentoAgenda(2, $oSlipAgenda);
      }

      catch(Exception $eErro) {

        $lErro    = true;
        $sMsgErro = $eErro->getMessage();

      }
    }
  }
  if (!$lErro) {

   if ($oParam->k17_numcgm != "") {

      $clslipnum = db_utils::getDao("slipnum");
      $clslipnum->k17_numcgm = $oParam->k17_numcgm;
      $clslipnum->incluir($numsliprel);
      if ($clslipnum->erro_status == 0) {

        $sMsgErro = $clslipnum->erro_msg;
        $lErro    = true;

      }
    }
  }
  if (!$lErro) {

    $clsliprecurso = db_utils::getDao("sliprecurso");
    /**
     * Incluimos os recursos
     */
    foreach ($oParam->aRecursos as $oRecurso) {

      $clsliprecurso->k29_slip     = $numsliprel;
      $clsliprecurso->k29_recurso  = $oRecurso->o15_codigo;
      $clsliprecurso->k29_valor    = "$oRecurso->o15_valor";
      $clsliprecurso->incluir(null);
      if ($clsliprecurso->erro_status == 0) {

        $sMsgErro = $clsliprecurso->erro_msg;
        $lErro    = true;
        break;

      }
    }
  }
  /**
   * vinculamos as arrecacoes com o slip
   */
  if (!$lErro) {

    if ($oParam->k17_tipopagamento == 2) {

      foreach ($oParam->aArrecadacoes as $iArrecadacao) {

        $sSqlCornump       = "select cornump.k12_data,cornump.k12_id,cornump.k12_autent";
        $sSqlCornump      .= "  from cornump ";
        $sSqlCornump      .= "  inner join corrente on cornump.k12_data   = corrente.k12_data ";
        $sSqlCornump      .= "                     and cornump.k12_autent = corrente.k12_autent ";
        $sSqlCornump      .= "                     and cornump.k12_id     = corrente.k12_id ";
        $sSqlCornump      .= " where k12_numpre = {$iArrecadacao}";
        $sSqlCornump      .= "   and k12_estorn is false ";
        $sSqlCornump      .= "  order by corrente.k12_data desc, corrente.k12_id desc limit 1";
        $rsCorrente       = db_query($sSqlCornump);
        if (pg_num_rows($rsCorrente) == 1) {

          $oCorrente        = db_utils::fieldsMemory($rsCorrente, 0);
          $oDaoSlipCorrente = db_utils::getDao("slipcorrente");
          $oDaoSlipCorrente->k112_ativo  = "true";
          $oDaoSlipCorrente->k112_data   = $oCorrente->k12_data;
          $oDaoSlipCorrente->k112_id     = $oCorrente->k12_id;
          $oDaoSlipCorrente->k112_autent = $oCorrente->k12_autent;
          $oDaoSlipCorrente->k112_slip   = $numsliprel;
          $oDaoSlipCorrente->incluir(null);
          if ($oDaoSlipCorrente->erro_status == 0) {

            $lErro      = true;
            $sMsgErro   = "Não foi possível vincular slip com a arrecadação {$iArrecadacao}.\n";
            $sMsgErro   = "{$oDaoSlipCorrente->erro_msg}";

          }

        } else {

          $lErro      = true;
          $sMsgErro   = "Foi encontrado mais de uma autentição para a arrecadação {$iArrecadacao}.\n";
          $sMsgErro  .= "Processamento cancelado.\nVerifique Suporte.";

        }
      }
    }
  }



  if (!$lErro) {

    try {

      $oTransferenciaBancaria  = new TransferenciaBancaria($clslip->k17_codigo);
      $iCodigoRecursoCredito   = $oTransferenciaBancaria->getContaPlanoCredito()->getRecurso();
      $iCodigoRecursoParametro = ParametroCaixa::getCodigoRecursoFUNDEB(db_getsession('DB_instit'));

      if ($iCodigoRecursoCredito === $iCodigoRecursoParametro) {

        $oFinalidadePagamento = FinalidadePagamentoFundeb::getInstanciaPorCodigo($oParam->iCodigoFinalidadeFundeb);
        $oTransferenciaBancaria->setFinalidadePagamentoFundebCredito($oFinalidadePagamento);
        $oTransferenciaBancaria->salvarFinalidadePagamentoFundeb();
      }

    } catch (Exception $eErro) {

      $lErro = true;
      $sMsgErro = $eErro->getMessage();
    }
  }

  if ($lErro) {

    $oRetorno->status  = 2;
    $oRetorno->message = urlencode(str_replace("\\n", "\n",$sMsgErro));

  } else {
    $oRetorno->k17_codigo = $numsliprel;
  }

  db_fim_transacao($lErro);

} else if ($oParam->exec == "getSaldoInicialRecurso") {

	$oRetorno->lExtra = false;
	$iAnoUsu          = db_getsession("DB_anousu");
  $sSqlSaldoConta   = " select o15_codigo,                                                                            ";
  $sSqlSaldoConta  .= "        o15_descr,                                                                             ";

  if (isset($oParam->k17_codigo) && !empty($oParam->k17_codigo)) {

    $sSqlSaldoConta  .= "      k29_recurso,                                                                           ";
    $sSqlSaldoConta  .= "      k29_valor,                                                                             ";
  } else {

  	$sSqlSaldoConta  .= "      null as k29_recurso,                                                                   ";
    $sSqlSaldoConta  .= "      null as k29_valor,                                                                     ";
  }

  $sSqlSaldoConta  .= "        ( select rnsaldofinal                                                                  ";
  $sSqlSaldoConta  .= "            from fc_saltessaldoextrainicial(k02_codigo,current_date, o15_codigo)               ";
  $sSqlSaldoConta  .= "        )  as saldorecurso                                                                     ";
  $sSqlSaldoConta  .= "   from tabplan                                                                                ";
  $sSqlSaldoConta  .= "        inner join tabplansaldorecurso on k111_tabplan = k02_codigo                            ";
  $sSqlSaldoConta  .= "                                      and k111_anousu  = k02_anousu                            ";
  $sSqlSaldoConta  .= "        inner join orctiporec          on k111_recurso = o15_codigo                            ";

  if (isset($oParam->k17_codigo) && !empty($oParam->k17_codigo)) {
    $sSqlSaldoConta  .= "      left  join sliprecurso         on k29_recurso  = o15_codigo                            ";
    $sSqlSaldoConta  .= "                                    and k29_slip     = {$oParam->k17_codigo}                 ";
  }

  $sSqlSaldoConta  .= "  where k02_reduz  = {$oParam->conta}                                                          ";
  $sSqlSaldoConta  .= "    and k02_anousu = {$iAnoUsu}                                                                ";

  $rsSqlSaldoConta  = db_query($sSqlSaldoConta);
  $iNumRows         = pg_num_rows($rsSqlSaldoConta);
  if ($iNumRows == 0) {
  	$oRetorno->status = 2;
  } else {
  	$oRetorno->itens  = db_utils::getCollectionByRecord($rsSqlSaldoConta,false,false,true);
  }

}

echo $oJson->encode($oRetorno);
?>
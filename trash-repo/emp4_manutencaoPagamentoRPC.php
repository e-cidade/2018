<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require_once("libs/db_sessoes.php");
require_once("libs/db_libcontabilidade.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("libs/db_app.utils.php");
require_once("model/CgmFactory.model.php");
require_once("model/CgmBase.model.php");
require_once("model/CgmJuridico.model.php");
require_once("model/CgmFisico.model.php");
require_once("model/Dotacao.model.php");
require_once("model/agendaPagamento.model.php");
require_once("model/impressaoCheque.model.php");
require_once('model/empenho/EmpenhoFinanceiro.model.php');
require_once('model/empenho/EmpenhoFinanceiroItem.model.php');
require_once('model/MaterialCompras.model.php');
require_once("classes/ordemPagamento.model.php");
//require_once "model/caixa/ArrecadacaoReceitaOrcamentaria.model.php";
require_once "model/caixa/AutenticacaoArrecadacao.model.php";
require_once("model/contabilidade/contacorrente/ContaCorrenteFactory.model.php");
require_once("model/contabilidade/contacorrente/ContaCorrenteBase.model.php");
require_once("model/financeiro/ContaBancaria.model.php");
require_once("model/contabilidade/planoconta/ContaPlano.model.php");
require_once("model/contabilidade/planoconta/ClassificacaoConta.model.php");
require_once("model/contabilidade/planoconta/ContaCorrente.model.php");
require_once("model/contabilidade/planoconta/ContaOrcamento.model.php");
require_once("model/contabilidade/planoconta/ContaPlanoPCASP.model.php");
//db_app::import("exceptions.*");
require_once("libs/exceptions/BusinessException.php");
require_once("libs/exceptions/DBException.php");
require_once("libs/exceptions/FileException.php");
require_once("libs/exceptions/ParameterException.php");
//db_app::import("configuracao.*");
require_once("model/configuracao/Agenda.model.php");
require_once("model/configuracao/DBDepartamento.model.php");
require_once("model/configuracao/DBDivisaoDepartamento.model.php");
require_once("model/configuracao/DBEstrutura.model.php");
require_once("model/configuracao/DBEstruturaValor.model.php");
require_once("model/configuracao/DBFormCache.model.php");
require_once("model/configuracao/DBLogJSON.model.php");
require_once("model/configuracao/DBLog.model.php");
require_once("model/configuracao/DBLogTXT.model.php");
require_once("model/configuracao/DBLogXML.model.php");
require_once("model/configuracao/Instituicao.model.php");
require_once("model/configuracao/Job.model.php");
require_once("model/configuracao/RemessaWebService.model.php");
require_once("model/configuracao/TaskManager.model.php");
require_once("model/configuracao/Task.model.php");
require_once("model/configuracao/UsuarioSistema.model.php");
//db_app::import("caixa.*");
require_once("model/caixa/ArrecadacaoReceitaOrcamentaria.model.php");
require_once("model/caixa/AutenticacaoArrecadacao.model.php");
require_once("model/caixa/AutenticacaoBaixaBanco.model.php");
require_once("model/caixa/AutenticacaoPlanilha.model.php");
require_once("model/caixa/LancamentoContabilAjusteBaixaBanco.model.php");
require_once("model/caixa/PlanilhaArrecadacao.model.php");
require_once("model/caixa/ReceitaPlanilha.model.php");
//b_app::import("contabilidade.*");
require_once("model/contabilidade/DocumentoContabilConjuntoRegra.model.php");
require_once("model/contabilidade/DocumentoContabil.model.php");
require_once("model/contabilidade/DocumentoContabilRegra.model.php");
require_once("model/contabilidade/EventoContabilLancamento.model.php");
require_once("model/contabilidade/EventoContabil.model.php");
require_once("model/contabilidade/GrupoContaOrcamento.model.php");
require_once("model/contabilidade/InscricaoPassivoOrcamentoItem.model.php");
require_once("model/contabilidade/InscricaoPassivoOrcamento.model.php");
require_once("model/contabilidade/RegraLancamentoContabil.model.php");
require_once("model/contabilidade/SingletonDocumentoContabil.model.php");
//db_app::import("contabilidade.contacorrente.*");
require_once("model/contabilidade/contacorrente/AdiantamentoConcessao.model.php");
require_once("model/contabilidade/contacorrente/AdiantamentoConcessaoRepository.model.php");
require_once("model/contabilidade/contacorrente/ContaCorrenteBase.model.php");
require_once("model/contabilidade/contacorrente/ContaCorrenteContrato.model.php");
require_once("model/contabilidade/contacorrente/ContaCorrenteContratoRepository.model.php");
require_once("model/contabilidade/contacorrente/ContaCorrenteFactory.model.php");
require_once("model/contabilidade/contacorrente/ContaCorrenteRepositoryBase.model.php");
require_once("model/contabilidade/contacorrente/ContaCorrenteRepositoryFactory.model.php");
require_once("model/contabilidade/contacorrente/CredorFornecedorDevedor.model.php");
require_once("model/contabilidade/contacorrente/CredorFornecedorDevedorRepository.model.php");
require_once("model/contabilidade/contacorrente/DisponibilidadeFinanceira.model.php");
require_once("model/contabilidade/contacorrente/DisponibilidadeFinanceiraRepository.model.php");
require_once("model/contabilidade/contacorrente/DomicilioBancario.model.php");
require_once("model/contabilidade/contacorrente/DomicilioBancarioRepository.model.php");
//db_app::import("contabilidade.lancamento.*");
require_once("model/contabilidade/lancamento/EscrituracaoRestosAPagarNaoProcessados.model.php");
require_once("model/contabilidade/lancamento/LancamentoAuxiliarAberturaExercicioOrcamento.model.php");
require_once("model/contabilidade/lancamento/LancamentoAuxiliarArrecadacaoReceitaExtraOrcamentaria.model.php");
require_once("model/contabilidade/lancamento/LancamentoAuxiliarArrecadacaoReceita.model.php");
require_once("model/contabilidade/lancamento/LancamentoAuxiliarBase.model.php");
require_once("model/contabilidade/lancamento/LancamentoAuxiliarContaCorrente.model.php");
require_once("model/contabilidade/lancamento/LancamentoAuxiliarDepreciacao.model.php");
require_once("model/contabilidade/lancamento/LancamentoAuxiliarEmLiquidacaoMaterialPermanente.model.php");
require_once("model/contabilidade/lancamento/LancamentoAuxiliarEmLiquidacao.model.php");
require_once("model/contabilidade/lancamento/LancamentoAuxiliarEmpenhoEmLiquidacaoMaterialAlmoxarifado.model.php");
require_once("model/contabilidade/lancamento/LancamentoAuxiliarEmpenhoLiquidacao.model.php");
require_once("model/contabilidade/lancamento/LancamentoAuxiliarEmpenho.model.php");
require_once("model/contabilidade/lancamento/LancamentoAuxiliarEmpenhoPassivo.model.php");
require_once("model/contabilidade/lancamento/LancamentoAuxiliarInscricao.model.php");
require_once("model/contabilidade/lancamento/LancamentoAuxiliarInscricaoRestosAPagarNaoProcessados.model.php");
require_once("model/contabilidade/lancamento/LancamentoAuxiliarInventario.model.php");
require_once("model/contabilidade/lancamento/LancamentoAuxiliarMovimentacaoEstoque.model.php");
require_once("model/contabilidade/lancamento/LancamentoAuxiliarProvisaoDecimoTerceiro.model.php");
require_once("model/contabilidade/lancamento/LancamentoAuxiliarProvisaoFerias.model.php");
require_once("model/contabilidade/lancamento/LancamentoAuxiliarReconhecimentoReceitaFatoGerador.model.php");
require_once("model/contabilidade/lancamento/LancamentoAuxiliarSlip.model.php");
require_once("model/contabilidade/lancamento/LancamentoEmpenhoEmLiquidacao.model.php");
require_once("model/contabilidade/lancamento/ReceitaFatoGerador.model.php");
require_once("model/contabilidade/lancamento/RegraAnulacaoSlip.model.php");
require_once("model/contabilidade/lancamento/RegraArrecadacaoReceita.model.php");
require_once("model/contabilidade/lancamento/RegraBaixaInscricaoPassivoSemSuporteOrcamentario.model.php");
require_once("model/contabilidade/lancamento/RegraEmLiquidacao.model.php");
require_once("model/contabilidade/lancamento/RegraEmpenhoPassivoSemSuporteOrcamentario.model.php");
require_once("model/contabilidade/lancamento/RegraInscricaoPassivoSemSuporteOrcamentario.model.php");
require_once("model/contabilidade/lancamento/RegraLancamentoAberturaExercicio.model.php");
require_once("model/contabilidade/lancamento/RegraLancamentoContabilFactory.model.php");
require_once("model/contabilidade/lancamento/RegraLancamentoContaDepreciacao.model.php");
require_once("model/contabilidade/lancamento/RegraLancamentoDevolucaoAdiantamento.model.php");
require_once("model/contabilidade/lancamento/RegraLancamentoEmLiquidacaoMaterialConsumo.model.php");
require_once("model/contabilidade/lancamento/RegraLancamentoEmLiquidacaoMaterialPermanente.model.php");
require_once("model/contabilidade/lancamento/RegraLancamentoEmpenhoPrestacaoConta.model.php");
require_once("model/contabilidade/lancamento/RegraLancamentoEntradaEstoque.model.php");
require_once("model/contabilidade/lancamento/RegraLancamentoLiquidacaoEmpenho.model.php");
require_once("model/contabilidade/lancamento/RegraLancamentoProvisaoDecimoTerceiro.model.php");
require_once("model/contabilidade/lancamento/RegraLancamentoProvisaoFerias.model.php");
require_once("model/contabilidade/lancamento/RegraLancamentoReavaliacaoBem.model.php");
require_once("model/contabilidade/lancamento/RegraLancamentoRestosAPagar.model.php");
require_once("model/contabilidade/lancamento/RegraLiquidacaoEmpenhoPassivoSemSuporteOrcamentario.model.php");
require_once("model/contabilidade/lancamento/RegraMovimentacaoEstoque.model.php");
require_once("model/contabilidade/lancamento/RegraPagamentoSlip.model.php");
require_once("model/contabilidade/lancamento/RegraReconhecimentoReceitaFatoGerador.model.php");
//db_app::import("orcamento.*");
require_once("model/orcamento/CaracteristicaPeculiar.model.php");
require_once("model/orcamento/Orgao.model.php");
require_once("model/orcamento/ReceitaContabil.model.php");
require_once("model/orcamento/ReceitaExtraOrcamentaria.model.php");
require_once("model/orcamento/ReceitaOrcamentaria.model.php");
require_once("model/orcamento/Recurso.model.php");
require_once("model/orcamento/TribunalEstrutura.model.php");
require_once("model/orcamento/Unidade.model.php");


require_once 'model/impressaoAutenticacao.php';
		
$oGet     = db_utils::postMemory($_GET);
$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));

switch($oParam->exec) {

  case "getMovimentos" :

    $oAgenda = new agendaPagamento();
    $oAgenda->setUrlEncode(true);
    $sJoin   = '';
    $sWhereIni  = " ((round(e53_valor,2)-round(e53_vlranu,2)-round(e53_vlrpag,2)) > 0 ";
    $sWhereIni .= " and (round(e60_vlremp,2)-round(e60_vlranu,2)-round(e60_vlrpag,2)) > 0) ";
    $sWhereIni .= " and corempagemov.k12_codmov is null and e81_cancelado is null";
    $sWhereIni .= " and e80_data  <= '".date("Y-m-d",db_getsession("DB_datausu"))."'";
    $sWhereIni .= " and e60_instit = ".db_getsession("DB_instit");
    $sWhere     = $sWhereIni;
    $oAgenda->setOrdemConsultas("e82_codord, e81_codmov");
    if ($oParam->params[0]->iOrdemIni != '' && $oParam->params[0]->iOrdemFim == "") {
      $sWhere .= " and e50_codord = {$oParam->params[0]->iOrdemIni}";
    } else if ($oParam->params[0]->iOrdemIni != '' && $oParam->params[0]->iOrdemFim != "") {
      $sWhere .= " and e50_codord between  {$oParam->params[0]->iOrdemIni} and {$oParam->params[0]->iOrdemFim}";
    }

    if ($oParam->params[0]->dtDataIni != "" && $oParam->params[0]->dtDataFim == "") {
      $sWhere .= " and e50_data = '".implode("-",array_reverse(explode("/",$oParam->params[0]->dtDataIni)))."'";
    } else if ($oParam->params[0]->dtDataIni != "" && $oParam->params[0]->dtDataFim != "") {

      $dtDataIni = implode("-",array_reverse(explode("/",$oParam->params[0]->dtDataIni)));
      $dtDataFim = implode("-",array_reverse(explode("/",$oParam->params[0]->dtDataFim)));
      $sWhere .= " and e50_data between '{$dtDataIni}' and '{$dtDataFim}'";

    } else if ($oParam->params[0]->dtDataIni == "" && $oParam->params[0]->dtDataFim != "") {

       $dtDataFim  = implode("-",array_reverse(explode("/",$oParam->params[0]->dtDataFim)));
       $sWhere    .= " and e50_data <= '{$dtDataFim}'";
    }

    //Filtro para Empenho
    if ($oParam->params[0]->iCodEmp!= '') {

      if (strpos($oParam->params[0]->iCodEmp,"/")) {

        $aEmpenho = explode("/",$oParam->params[0]->iCodEmp);
        $sWhere .= " and e60_codemp = '{$aEmpenho[0]}' and e60_anousu={$aEmpenho[1]}";

      } else {
        $sWhere .= " and e60_codemp = '{$oParam->params[0]->iCodEmp}' and e60_anousu=".db_getsession("DB_anousu");
      }

    }
    
    $sCredorCgm = '';

    //filtro para filtrar por credor
    if ($oParam->params[0]->iNumCgm != '') {
      
      $sWhere    .= " and (e60_numcgm = {$oParam->params[0]->iNumCgm})";
      $sCredorCgm = $oParam->params[0]->iNumCgm;
    }
  if ($oParam->params[0]->iAutorizadas == 2) {

    $lAutorizadas      = true;
    if ($oParam->params[0]->sDtAut != "") {

      $sDtAut   = implode("-", array_reverse(explode("/", $oParam->params[0]->sDtAut)));
      $sWhere .= " and e42_dtpagamento = '{$sDtAut}'";

    }


    $sWhere .= " and e43_autorizado is true ";

  } else if ($oParam->params[0]->iAutorizadas == 3) {

    $sWhere .= " and e43_empagemov is null";
  }

  if ($oParam->params[0]->iOPauxiliar != '') {

    $sWhere .= " and e42_sequencial = {$oParam->params[0]->iOPauxiliar}";
  }
  if ($oParam->params[0]->iRecurso != '') {

    $sWhere .= " and o15_codigo = {$oParam->params[0]->iRecurso}";
  }
  if ($oParam->params[0]->iOPManutencao != '') {

    $sWhere .= " or ( e42_sequencial = {$oParam->params[0]->iOPManutencao}  and $sWhereIni)";
    $oAgenda->setOrdemConsultas("e42_sequencial,e43_sequencial, e81_codmov,e50_codord");

  }

  $sJoin   .= " left join empagenotasordem on e81_codmov         = e43_empagemov  ";
  $sJoin   .= " left join empageordem      on e43_ordempagamento = e42_sequencial ";
  $aOrdensAgenda = $oAgenda->getMovimentosAgenda($sWhere,$sJoin,true,true,'',$oParam->params[0]->lVinculadas, $sCredorCgm);

  if (count($aOrdensAgenda) > 0) {

    $oRetono->status           = 1;
    $oRetono->mensagem         = 1;
    $oRetono->totais           = $oAgenda->getTotaisAgenda($sWhere);
    $oRetono->aNotasLiquidacao = $aOrdensAgenda;
    echo $oJson->encode($oRetono);

  } else {

    $oRetono->status           = 2;
    $oRetono->mensagem         = "";
    echo $oJson->encode($oRetono);

  }
  break;
  
  
  
  case 'efetuarPagamentoSlip':
  
 		$oAgenda                        = new agendaPagamento();
 		$oRetorno                       = new stdClass();
 		$oRetorno->status               = '1';
 		$oRetorno->iCodigoOrdemAuxiliar = null;
 		$oRetorno->aAutenticacoes       = array();
  	 
  	try {
  
  		db_inicio_transacao();
  		

  		foreach ($oParam->aMovimentos as $oMovimento) {
  			$oAgenda->configurarPagamentos($oParam->dtPagamento, $oMovimento);
  		}
  		 
  		/*
  		 * Se o usuario marcou a opcao para "Efetuar pagamento" o sistema gera pagamento sequingo a mesma logica
  		*   da rotina de pagamento de empenho por agenda (Caixa > Procedimentos > Agenda > Pgtos Empenho p/ Agenda )
  		*/
  		
  		if ($oParam->lEfetuarPagamento) {
  			
  			foreach ($oParam->aMovimentos as $oMovimento) {
  
  				$oTransferencia = TransferenciaFactory::getInstance(null, $oMovimento->iCodNota);
  				
 					$oTransferencia->executaAutenticacao();
 					
  				if (USE_PCASP) {
  					$oTransferencia->executarLancamentoContabil();
  				}
  				
  				$oAutentica                 = new stdClass();
  				$oAutentica->iNota          = $oMovimento->iCodNota;
  				$oAutentica->sAutentica     = $oTransferencia->getStringAutenticacao();
  				$oRetorno->aAutenticacoes[] = $oAutentica;
  			}
  		}
  		db_fim_transacao(false);
  		
  	} catch (Exception $eErro) {
  
  		db_fim_transacao(true);
  		$oRetorno->status  = 2;
  		$oRetorno->message = urlencode($eErro->getMessage());
  
  	}
  	echo $oJson->encode($oRetorno);
  
  	break;

  case "configurarPagamento" :

     $oAgenda                       = new agendaPagamento();
     $oRetorno                       = new stdClass();
     $oRetorno->status               = '1';
     $oRetorno->iCodigoOrdemAuxiliar = null;
     $oRetorno->aAutenticacoes      = array();
     try {

       db_inicio_transacao();
       $iCodigoOrdemAuxiliar = null;
       if ($oParam->lEmitirOrdeAuxiliar) {
         $iCodigoOrdemAuxiliar =  $oAgenda->autorizarPagamento($oParam->dtPagamento);
       }
       /*
        * Adiciona o Movimento na ordem auxiliar escolhida pelo usu�rio
        */
       if (isset($oParam->iOPAuxiliarManutencao) && $oParam->iOPAuxiliarManutencao != "") {
         $iCodigoOrdemAuxiliar = $oParam->iOPAuxiliarManutencao;
         $oParam->lEmitirOrdeAuxiliar = true;
       }

       foreach ($oParam->aMovimentos as $oMovimento) {
         $oAgenda->configurarPagamentos($oParam->dtPagamento, $oMovimento, $iCodigoOrdemAuxiliar, $oParam->lEmitirOrdeAuxiliar);
       }

       /*
        * Se o usuario marcou a opcao para "Efetuar pagamento" o sistema gera pagamento sequingo a mesma logica
        *   da rotina de pagamento de empenho por agenda (Caixa > Procedimentos > Agenda > Pgtos Empenho p/ Agenda )
        */
       if ($oParam->lEfetuarPagamento) {
         foreach ($oParam->aMovimentos as $oMovimento) {

           $oOrdemPagamento = new ordemPagamento($oMovimento->iCodNota);
           $oOrdemPagamento->setCheque(null);
//           $oOrdemPagamento->setChequeAgenda($oMovimento->iCodCheque);

           $oOrdemPagamento->setConta($oMovimento->iContaSaltes); // temos que verificar esses parametros
           $oOrdemPagamento->setValorPago($oMovimento->nValor);
           $oOrdemPagamento->setMovimentoAgenda($oMovimento->iCodMov);
           $oOrdemPagamento->setHistorico('');
           $oOrdemPagamento->pagarOrdem();

           $oRetorno->iItipoAutent     = $oOrdemPagamento->oAutentica->k11_tipautent;
           $c70_codlan                 = $oOrdemPagamento->iCodLanc;
           $oAutentica                 = new stdClass();
           $oAutentica->iNota          = $oMovimento->iCodNota;
           $oAutentica->sAutentica     = $oOrdemPagamento->getRetornoautenticacao();
           $oRetorno->aAutenticacoes[] = $oAutentica;
         }
       }

       $oRetorno->iCodigoOrdemAuxiliar = $iCodigoOrdemAuxiliar;
       db_fim_transacao(false);

     }
     catch (Exception $eErro) {

       db_fim_transacao(true);
       $oRetorno->status  = 2;
       $oRetorno->message = urlencode($eErro->getMessage());

     }
     echo $oJson->encode($oRetorno);
     break;

  case "getMovimentosSlip":

    $oAgenda = new agendaPagamento();
    $oAgenda->setUrlEncode(true);
    $sWhere  = " s.k17_instit = ".db_getsession("DB_instit");
    $sWhere .= " and e91_codmov is null    ";
    $sWhere .= " and e81_cancelado is null ";
    //$sWhere .= " and e90_codmov is null    ";
    // alterada condicao do where vara ver o campo e90_cancelado = true, pois agora os registros da empageconfgera
    // ao cancelar um arquivo, nao ser�o mais deletados
    $sWhere .= " and (e90_cancelado is true or e90_cancelado is null)";
    $sWhere .= "and k17_situacao in(1,3)   ";
    if ($oParam->params[0]->iOrdemIni != '' && $oParam->params[0]->iOrdemFim == "") {
      $sWhere .= " and s.k17_codigo = {$oParam->params[0]->iOrdemIni}";
    } else if ($oParam->params[0]->iOrdemIni != '' && $oParam->params[0]->iOrdemFim != "") {
      $sWhere .= " and s.k17_codigo between  {$oParam->params[0]->iOrdemIni} and {$oParam->params[0]->iOrdemFim}";
    }

    if ($oParam->params[0]->dtDataIni != "" && $oParam->params[0]->dtDataFim == "") {
      $sWhere .= " and k17_data = '".implode("-",array_reverse(explode("/",$oParam->params[0]->dtDataIni)))."'";
    } else if ($oParam->params[0]->dtDataIni != "" && $oParam->params[0]->dtDataFim != "") {

      $dtDataIni = implode("-",array_reverse(explode("/",$oParam->params[0]->dtDataIni)));
      $dtDataFim = implode("-",array_reverse(explode("/",$oParam->params[0]->dtDataFim)));
      $sWhere .= " and k17_data between '{$dtDataIni}' and '{$dtDataFim}'";

    } else if ($oParam->params[0]->dtDataIni == "" && $oParam->params[0]->dtDataFim != "") {

       $dtDataFim  = implode("-",array_reverse(explode("/",$oParam->params[0]->dtDataFim)));
       $sWhere    .= " and k17_data <= '{$dtDataFim}'";
    }

    //filtro para filtrar por credor
    if ($oParam->params[0]->iNumCgm != '') {
      $sWhere .= " and (k17_numcgm = {$oParam->params[0]->iNumCgm})";
    }

    if ($oParam->params[0]->iRecurso != '') {
      $sWhere .= " and ctapag.c61_codigo = {$oParam->params[0]->iRecurso}";
    }
    $aSlipsAgenda = $oAgenda->getSlips($sWhere, true);
    if (count($aSlipsAgenda) > 0) {

      $oRetono->status           = 1;
      $oRetono->mensagem         = 1;
      $oRetono->aSlips           = $aSlipsAgenda;
      echo $oJson->encode($oRetono);

    } else {

      $oRetono->status           = 2;
      $oRetono->mensagem         = "";
      echo $oJson->encode($oRetono);

    }
    break;

  case "cancelaMovimentoOrdemAuxiliar":

    $oAgenda                       = new agendaPagamento();
    $oRetono                       = new stdClass();
    $oRetono->status               = '1';
    $oRetono->message              = '1';
    $oRetono->iCodigoOrdemAuxiliar = null;

    try {

      db_inicio_transacao();
      $iCodigoOrdemAuxiliar = $oParam->iOPAuxiliarManutencao;
      foreach ($oParam->aMovimentos as $oMovimento) {
        $oAgenda->cancelaMovimentoOrdemAuxiliar($iCodigoOrdemAuxiliar, $oMovimento->iCodMov);
      }
      $oRetono->iCodigoOrdemAuxiliar = $iCodigoOrdemAuxiliar;
      db_fim_transacao(false);
    }
    catch (Exception $eErro) {

      db_fim_transacao(true);
      $oRetono->status  = 2;
      $oRetono->message = urlencode($eErro->getMessage());

    }
    echo $oJson->encode($oRetono);
    break;

 case "agruparMovimentos":

   $oRetorno                       = new stdClass();
   $oRetorno->status               = 1;
   $oRetorno->message              = '1';
   $oRetorno->totalagrupados       = "".count($oParam->aMovimentosAgrupar)."";
   $oAgenda                       = new agendaPagamento();
   try {

     db_inicio_transacao();
     $oAgenda->agruparMovimentos($oParam->aMovimentosAgrupar);
     db_fim_transacao(false);

   } catch (Exception $eErro) {

     db_fim_transacao(true);
     $oRetorno->status               = 2;
     $oRetorno->message              = urlencode($eErro->getMessage());

   }
   echo $oJson->encode($oRetorno);
   break;

}
?>
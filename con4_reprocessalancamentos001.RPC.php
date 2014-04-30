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
 
//con4_reprocessalancamentos001.RPC.php
require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/JSON.php");  
require_once ("libs/exceptions/BusinessException.php");
require_once ("libs/exceptions/DBException.php");
require_once ("libs/exceptions/ParameterException.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_libcontabilidade.php");
require_once ("classes/materialestoque.model.php");

$oJson                   = new services_json();
$oParam                  = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno                = new stdClass();
$oRetorno->iStatus       = 1;
$oRetorno->sMensagem     = '';
$iInstituicaoSessao      = db_getsession("DB_instit");
$aDadosRetorno           = array();
$sCaminhoMensagem   = "financeiro.contabilidade.con4_reprocessalancamentos001.";

/**
 * funcao que ira verificar a data do lancamento a ser processada e comparar
 * com o fechamento da contabilidade, não podemos processar lançamentos que estejam igual ou abaixo
 * da data de fechamento.
 * caso o lançamento esteja igual ou abaixo (invalido) retornamos exception
 * senão retorna true
 * @param date $dtLancamento
 * @return bollean
 */
function verificaPeriodoContabilidade($dtLancamento){

	$oDaoConDataConf    = db_utils::getDao("condataconf");
	$iInstituicao       = db_getsession("DB_instit");
	$iAnoUsu            = db_getsession("DB_anousu");
		$sWhereConDataConf  = "     c99_anousu = {$iAnoUsu}";
	$sWhereConDataConf .= " and c99_instit = {$iInstituicao}";
	$sWhereConDataConf .= " and c99_data >= '{$dtLancamento}'";
	$sCaminhoMensagem   = "financeiro.contabilidade.con4_reprocessalancamentos001.";
	$sSqlConDataConf = $oDaoConDataConf->sql_query_file (null, null, "*", null, $sWhereConDataConf);
	$rsConDataConf   = $oDaoConDataConf->sql_record($sSqlConDataConf);

	if ($oDaoConDataConf->numrows > 0) {
		throw new BusinessException(_M($sCaminhoMensagem."periodoEncerrado"));
	}
	return true;
}

try {
  switch ($oParam->sExec) {
    
    case "operacoesExtraOrcamentaria" :
     
      //$iLancamento      = $oParam->iLancamento;
      $iDocumento       = $oParam->iDocumento;
      $dtInicial        = $oParam->dtInicial;
      $dtFinal          = $oParam->dtFinal;
      $iSlipInicial     = $oParam->iSlipInicial;
      $iSlipFinal       = $oParam->iSlipFinal;
      $oDaoConlancam    = db_utils::getDao("conlancam");
      
      if ( empty($iSlipInicial) && !empty($iSlipFinal) ) {
        $iSlipInicial = $iSlipFinal;
      }
      if (empty($iSlipFinal) && !empty($iSlipInicial)) {
        $iSlipFinal = $iSlipInicial;
      }
      $sWhereConlancam   = " c71_coddoc = {$iDocumento} ";
      // Filtros padrao
     // if (!empty($iLancamento)) {
     //   $sWhereConlancam .= " and c70_codlan = {$iLancamento}";
     // }
      if (!empty($dtInicial) && empty($dtFinal)) {
        $sWhereConlancam .= " and c70_data between '{$dtInicial}' and '{$iAnoUsu}-01-01' ";
      }
      if (empty($dtInicial) && !empty($dtFinal)) {
        $sWhereConlancam .= " and c70_data between '{$iAnoUsu}-01-01' and '{$dtFinal}' ";
      }
      if (!empty($dtInicial) && !empty($dtFinal)) {
        $sWhereConlancam .= " and c70_data between '{$dtInicial}' and '{$dtFinal}' ";
      }
      // conlancamslip
      if (!empty($iSlipInicial) ||  !empty($iSlipFinal)) {
        $sWhereConlancam .= " and c84_slip between {$iSlipInicial} and {$iSlipFinal} ";
      }
      
      $sCamposConLancam  = " distinct conlancam.*,    ";
      $sCamposConLancam .= " extract(year from c70_data) as anolancamento ";
      
      $sSqlConlancam = $oDaoConlancam->sql_query_reprocessaExtraOrcamentario(null, $sCamposConLancam, "c70_codlan", $sWhereConlancam);
      $rsConlancam   = $oDaoConlancam->sql_record($sSqlConlancam);
      
      if ($oDaoConlancam->numrows == 0) {
      
        throw new BusinessException(_M($sCaminhoMensagem."buscaLancamentos"));
      }
      
      //percorre os lançamentos chamando os lancamentos auxiliares e fazendo o evento contabil
      db_inicio_transacao();
      $iTotalRegistrosProcessados = 0;
      
      for ($iResultLancamento = 0; $iResultLancamento < $oDaoConlancam->numrows; $iResultLancamento++) {
      
        $oDadosConlancam     = db_utils::fieldsMemory($rsConlancam, $iResultLancamento);
        // primeiro verificamos a data do lancamento está no periodo valido da contabilidade
        verificaPeriodoContabilidade($oDadosConlancam->c70_data);
        // instanciamos o lancamento auxiliar
        
        $oEventoContabil     = new EventoContabil($iDocumento, $oDadosConlancam->anolancamento);
        $aLancamentos        = $oEventoContabil->getEventoContabilLancamento();
        $iHistorico          = $aLancamentos[0]->getHistorico();
        $oLancamentoAuxiliar = LancamentoAuxiliarFactory::getInstance($iDocumento, $oDadosConlancam->c70_codlan);
        $oLancamentoAuxiliar->setHistorico($iHistorico);
        
        $oEventoContabil->reprocessaLancamentos($oDadosConlancam->c70_codlan, $oLancamentoAuxiliar, $oDadosConlancam->c70_data);
        $iTotalRegistrosProcessados++;
      }
      
      
      db_fim_transacao(false);
      
      $oStdMensagemSucesso = new stdClass();
      $oStdMensagemSucesso->total_registro = $iTotalRegistrosProcessados;
      $oRetorno->sMensagem = (_M($sCaminhoMensagem."processado", $oStdMensagemSucesso));
      
      
      
      break;
    
    case "reprocessarLancamentosMovimentacaoPatrimonial" :
      
      $iLancamento      = $oParam->iLancamento;
      $iDocumento       = $oParam->iDocumento;
      $dtInicial        = $oParam->dtInicial;
      $dtFinal          = $oParam->dtFinal;
      $iNota            = $oParam->iNota;
      $iEmpenho         = $oParam->iEmpenho;
      $oDaoConlancam    = db_utils::getDao("conlancam");
      
      $sWhereConlancam   = " c71_coddoc = {$iDocumento} ";
      // Filtros padrao
      if (!empty($iLancamento)) {
        $sWhereConlancam .= " and c70_codlan = {$iLancamento}";
      }
      if (!empty($dtInicial) && empty($dtFinal)) {
        $sWhereConlancam .= " and c70_data between '{$dtInicial}' and '{$iAnoUsu}-01-01' ";
      }
      if (empty($dtInicial) && !empty($dtFinal)) {
        $sWhereConlancam .= " and c70_data between '{$iAnoUsu}-01-01' and '{$dtFinal}' ";
      }
      if (!empty($dtInicial) && !empty($dtFinal)) {
        $sWhereConlancam .= " and c70_data between '{$dtInicial}' and '{$dtFinal}' ";
      }
      // conlancamnota
      if (!empty($iNota)) {
        $sWhereConlancam .= " and empnotaord.m72_codnota = {$iNota} ";
      }
      // conlancamemp
      if (!empty($iEmpenho)) {
        $sWhereConlancam .= " and c75_numemp = {$iEmpenho} ";
      }
      
      $sCamposConLancam  = " distinct c70_codlan,    ";
      $sCamposConLancam .= " c70_data,      ";
      $sCamposConLancam .= " c70_valor,     ";
      $sCamposConLancam .= " c75_numemp,    ";
      $sCamposConLancam .= " m72_codnota,   ";
      $sCamposConLancam .= " extract(year from c70_data) as anolancamento ";
      
      $sSqlConlancam = $oDaoConlancam->sql_query_reprocessaMovimentacaoPatrimonial(null, $sCamposConLancam, 'c70_codlan', $sWhereConlancam);
      $rsConlancam   = $oDaoConlancam->sql_record($sSqlConlancam);
      
      if ($oDaoConlancam->numrows == 0) {
        
        throw new BusinessException(_M($sCaminhoMensagem."buscaLancamentos"));
      }
      
      //percorre os lançamentos chamando os lancamentos auxiliares e fazendo o evento contabil
      db_inicio_transacao();
      $iTotalRegistrosProcessados = 0;
      
      for ($iResultLancamento = 0; $iResultLancamento < $oDaoConlancam->numrows; $iResultLancamento++) {
      
        $oDadosConlancam     = db_utils::fieldsMemory($rsConlancam, $iResultLancamento);
        // primeiro verificamos a data do lancamento está no periodo valido da contabilidade
        verificaPeriodoContabilidade($oDadosConlancam->c70_data);
      
      
        if ( !empty($oDadosConlancam->c75_numemp) ) {
      
          $oEmpenhoFinanceiro = new EmpenhoFinanceiro($oDadosConlancam->c75_numemp);
          if ( $iInstituicaoSessao !== $oEmpenhoFinanceiro->getInstituicao()->getSequencial() ) {
            continue;
          }
          
        }
        
        
        if ( !empty($oDadosConlancam->m72_codnota) ) {
            
          $oNota = new NotaLiquidacao($oDadosConlancam->m72_codnota);
        }
        
        // instanciamos o lancamento auxiliar
       $oEventoContabil = new EventoContabil($iDocumento, $oDadosConlancam->anolancamento);
       $aLancamentos    = $oEventoContabil->getEventoContabilLancamento();
       $iHistorico      = $aLancamentos[0]->getHistorico();
       
       $oLancamentoAuxiliar = LancamentoAuxiliarFactory::getInstance($iDocumento, $oDadosConlancam->c70_codlan);
       $oLancamentoAuxiliar->setHistorico($iHistorico);
       $oEventoContabil->reprocessaLancamentos($oDadosConlancam->c70_codlan, $oLancamentoAuxiliar, $oDadosConlancam->c70_data);
        
        $iTotalRegistrosProcessados++;
      }
      db_fim_transacao(false);
      
      $oStdMensagemSucesso = new stdClass();
      $oStdMensagemSucesso->total_registro = $iTotalRegistrosProcessados;
      $oRetorno->sMensagem = (_M($sCaminhoMensagem."processado", $oStdMensagemSucesso));
      
    break;  
    
    
    case "reprocessarLancamentos":
    	
    	$iLancamento      = $oParam->iLancamento;
    	$iDocumento       = $oParam->iDocumento;
    	$dtInicial        = $oParam->dtInicial;
    	$dtFinal          = $oParam->dtFinal;
    	$iAcordo          = $oParam->iAcordo;
    	$iEmpenho         = $oParam->iEmpenho;
    	$iPassivo         = $oParam->iPassivo;
    	$oDaoConlancam    = db_utils::getDao("conlancam");
    	$sCaminhoMensagem = "financeiro.contabilidade.con4_reprocessalancamentos001.";
    	
    	$sCamposConLancam  = " c70_codlan,  ";
    	$sCamposConLancam .= " c70_data,    ";
    	$sCamposConLancam .= " c70_valor,   ";
      $sCamposConLancam .= " c75_numemp,   ";
      $sCamposConLancam .= " c87_acordo,   ";
    	$sCamposConLancam .= "extract(year from c70_data) as anolancamento ";
    	
    	$sWhereConlancam   = " c71_coddoc = {$iDocumento} ";
    	// Filtros padrao
    	if (!empty($iLancamento)) {
    		$sWhereConlancam .= " and c70_codlan = {$iLancamento}";
    	}
    	if (!empty($dtInicial) && empty($dtFinal)) {
    		$sWhereConlancam .= " and c70_data >= '{$dtInicial}' and c70_data <= '{$iAnoUsu}-01-01' ";
    	}
    	if (empty($dtInicial) && !empty($dtFinal)) {
    		$sWhereConlancam .= " and c70_data >= '{$iAnoUsu}-01-01' and c70_data <= '{$dtFinal}' ";
    	}
    	if (!empty($dtInicial) && !empty($dtFinal)) {
    		$sWhereConlancam .= " and c70_data >= '{$dtInicial}' and c70_data <= '{$dtFinal}' ";
    	}
    	// filtros especificos:
    	   // conlancamacordo
    	if (!empty($iAcordo)) {
    		$sWhereConlancam .= " and c87_acordo = {$iAcordo} ";
    	}
    	   // conlancamemp
    	if (!empty($iEmpenho)) {
    		$sWhereConlancam .= " and c75_numemp = {$iEmpenho} ";
    	}
    	   // conlancaminscricaopassivo
      if (!empty($iPassivo)) {
      	$sWhereConlancam .= " and c37_inscricaopassivo = {$iPassivo} ";
      }
    	$sSqlConlancam  = $oDaoConlancam->sql_query_reprocessamento(null, $sCamposConLancam, 'c70_codlan', $sWhereConlancam);
    	$rsConlancam    = $oDaoConlancam->sql_record($sSqlConlancam);

    	// caso nao venha lançamentos informamos o usuário.
    	if ($oDaoConlancam->numrows == 0) {
    		throw new BusinessException(_M($sCaminhoMensagem."buscaLancamentos"));
    	}
    	//percorre os lançamentos chamando os lancamentos auxiliares e fazendo o evento contabil
    	db_inicio_transacao();
      $iTotalRegistrosProcessados = 0;
    	for ($iResultLancamento = 0; $iResultLancamento < $oDaoConlancam->numrows; $iResultLancamento++) {
    		
    		$oDadosConlancam     = db_utils::fieldsMemory($rsConlancam, $iResultLancamento);
    		// primeiro verificamos a data do lancamento está no periodo valido da contabilidade
    		verificaPeriodoContabilidade($oDadosConlancam->c70_data);


        if ( !empty($oDadosConlancam->c75_numemp) ) {

          $oEmpenhoFinanceiro = new EmpenhoFinanceiro($oDadosConlancam->c75_numemp);
          if ( $iInstituicaoSessao !== $oEmpenhoFinanceiro->getInstituicao()->getSequencial() ) {
            continue;
          }
        }

        if ( !empty($oDadosConlancam->c87_acordo) ) {

          $oAcordo = new Acordo($oDadosConlancam->c87_acordo);
          if ( $iInstituicaoSessao !== $oAcordo->getInstit() ) {
            continue;
          }
        }

    		// instanciamos o lancamento auxiliar
    		$oLancamentoAuxiliar = LancamentoAuxiliarFactory::getInstance($iDocumento, $oDadosConlancam->c70_codlan);
    		$oEventoContabil     = new EventoContabil($iDocumento, $oDadosConlancam->anolancamento);
    		$oEventoContabil->reprocessaLancamentos($oDadosConlancam->c70_codlan, $oLancamentoAuxiliar, $oDadosConlancam->c70_data);
        $iTotalRegistrosProcessados++;
    	}
    	db_fim_transacao(false);
    	
      $oStdMensagemSucesso = new stdClass();
      $oStdMensagemSucesso->total_registro = $iTotalRegistrosProcessados;
    	$oRetorno->sMensagem = (_M($sCaminhoMensagem."processado", $oStdMensagemSucesso));
    break;
    
    default:
      throw new ParameterException("Nenhuma Opção Definida");
    break;
    
  }
  
  $oRetorno->sMensagem = urlencode($oRetorno->sMensagem);
  echo $oJson->encode($oRetorno);
  
} catch (Exception $eErro){
  
  $oRetorno->iStatus  = 2;
  $oRetorno->sMensagem = urlencode($eErro->getMessage());
  db_fim_transacao(true);
  echo $oJson->encode($oRetorno);
  
}catch (DBException $eErro){
  
  $oRetorno->iStatus  = 2;
  $oRetorno->sMensagem = urlencode($eErro->getMessage());
  db_fim_transacao(true);
  echo $oJson->encode($oRetorno);
  
}catch (ParameterException $eErro){
  
  $oRetorno->iStatus  = 2;
  $oRetorno->sMensagem = urlencode($eErro->getMessage());
  db_fim_transacao(true);
  echo $oJson->encode($oRetorno);
  
}catch (BusinessException $eErro){
  
  $oRetorno->iStatus  = 2;
  $oRetorno->sMensagem = urlencode($eErro->getMessage());
  db_fim_transacao(true);
  echo $oJson->encode($oRetorno);
}

?>
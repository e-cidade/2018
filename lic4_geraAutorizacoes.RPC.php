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
require_once("libs/db_app.utils.php");
require_once("libs/db_conn.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_sql.php");
require_once("std/db_stdClass.php");
require_once("libs/JSON.php");
require_once("libs/db_utils.php");
require_once("classes/db_pctipocompra_classe.php");
require_once("classes/db_emptipo_classe.php");
require_once("model/licitacao.model.php");
require_once("model/itemSolicitacao.model.php");
require_once("model/Dotacao.model.php");
require_once("model/CgmFactory.model.php");
require_once("model/CgmBase.model.php");
require_once("model/CgmFisico.model.php");
require_once("model/CgmJuridico.model.php");
require_once("classes/solicitacaocompras.model.php");
require_once("model/ProcessoCompras.model.php");

db_app::import("empenho.*");
$oDaoPcTipoCompra = new cl_pctipocompra();
$oDaoEmpTipo      = new cl_emptipo();

$oJson  = new services_json();
$oParam = $oJson->decode(str_replace("\\", "", $_POST["json"]));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = "";

switch ($oParam->exec) {

  case "getTipoLicitacao":

    $oDaoCfgLiclicita          = db_utils::getDao("cflicita");
    $sSqlTipoLicitacao         = $oDaoCfgLiclicita->sql_query_file(null,"l03_tipo, l03_descr", '', "l03_codcom = {$oParam->iTipoCompra}");
    $rsTipoLicitacao           = $oDaoCfgLiclicita->sql_record($sSqlTipoLicitacao);
    $oRetorno->aTiposLicitacao = array();

    if ($oDaoCfgLiclicita->numrows > 0) {

      for ($iTipoLicitacao = 0; $iTipoLicitacao < $oDaoCfgLiclicita->numrows; $iTipoLicitacao++) {
        $oRetorno->aTiposLicitacao[] = db_utils::fieldsMemory($rsTipoLicitacao, $iTipoLicitacao);
      }
    }

  break;
  /**
   * Busca os Tipos de Compra
   */
  case "getTipoCompraEmpenho":
    
    /**
     * Buscando resumo da solicitacao de compras
     */
    $oRetorno->sResumo = "";
    $oDaoSolicitacao   = db_utils::getDao("solicita");
    
    /*
     * Valida a origem dos dados.
     * 1 - Processo de Compras
     * 2 - Solicitação de Compras
     * undefined - Licitacao 
     */
    if (isset($oParam->iOrigemDados) && $oParam->iOrigemDados == 1) {
      
      $sWhereResumo     = "pc81_codproc = {$oParam->iCodigo}";
      $oDaoBuscaDotacao = new ProcessoCompras($oParam->iCodigo);
    } else if (isset($oParam->iOrigemDados) && $oParam->iOrigemDados == 2) {
      
      $sWhereResumo     = "pc10_numero = {$oParam->iCodigo}";
      $oDaoBuscaDotacao = new solicitacaoCompra($oParam->iCodigo);
    } else {
      
      $sWhereResumo     = "l20_codigo = {$oParam->iCodigo}";
      $oDaoBuscaDotacao = new licitacao($oParam->iCodigo);
    }
    
    $aSolicitacaoComDotacaoAnoAnterior = $oDaoBuscaDotacao->getSolicitacoesDotacaoAnoAnterior();
    $oRetorno->solicitacaoComDotacaoAnoAnterior = $aSolicitacaoComDotacaoAnoAnterior;
    
    $sOrderResumo      = "pc10_numero desc limit 1";
    $sSqlBuscaResumo   = $oDaoSolicitacao->sql_query_estregistro(null, "pc10_resumo", $sOrderResumo, $sWhereResumo);
    $rsResumo          = $oDaoSolicitacao->sql_record($sSqlBuscaResumo);
    
    if ($rsResumo && pg_num_rows($rsResumo) > 0) {
      
      $oResumo           = db_utils::fieldsMemory($rsResumo,0, false, false, false);
      $oRetorno->sResumo = utf8_encode($oResumo->pc10_resumo);
    }
    

    /*
     * Busca os Tipos de Compra
     */
    $sSqlPcTipoCompra   = $oDaoPcTipoCompra->sql_query_file(null, 'pc50_codcom, pc50_descr');
    $rsExecPcTipoCompra = $oDaoPcTipoCompra->sql_record($sSqlPcTipoCompra);
    $aPcTipoCompra      = array();

    if ($oDaoPcTipoCompra->numrows > 0) {

      for ($iRow = 0; $iRow < $oDaoPcTipoCompra->numrows; $iRow++) {

        $oDadosTipoCompra = db_utils::fieldsMemory($rsExecPcTipoCompra, $iRow, false, false, true);
        $aPcTipoCompra[] = $oDadosTipoCompra;
      }

      $oRetorno->aPcTipoCompra = $aPcTipoCompra;
    }
    
    /**
     * Busca o tipo de compra do registro
     * Tipos:  1 - Processo de Compras, 2 - Solicitação de Compras
     */
    !isset($oParam->iOrigemDados) ? $oParam->iOrigemDados = "" : $oParam->iOrigemDados;
    switch ($oParam->iOrigemDados) {
    	
    	case 1:
    		
    		$oDaoPcProc         = db_utils::getDao('pcproc');
    		$sSqlProcessoCompra = $oDaoPcProc->sql_query_tipocompra($oParam->iCodigo, "pc50_codcom");
    		$rsProcessoCompra   = $oDaoPcProc->sql_record($sSqlProcessoCompra);
    		if ($oDaoPcProc->numrows > 0) {
    			
    			$iTipoCompra                  = db_utils::fieldsMemory($rsProcessoCompra, 0)->pc50_codcom;
    			$oRetorno->iTipoCompraInicial = $iTipoCompra;
    		}
    		
    		break;
    		
    	case 2:
				
    		$oDaoSolicita        = db_utils::getDao('solicita');
    		$sSqlBuscaTipoCompra = $oDaoSolicita->sql_query_tipocompra($oParam->iCodigo, "pc50_codcom");
    		$rsBuscaTipoCompra   = $oDaoSolicita->sql_record($sSqlBuscaTipoCompra);
    		if ($oDaoSolicita->numrows > 0) {
    		
    			$iTipoCompra = db_utils::fieldsMemory($rsBuscaTipoCompra, 0)->pc50_codcom;
    			$oRetorno->iTipoCompraInicial = $iTipoCompra;
    		}
    		break;
    		
    	default:
    		
    		$oDaoLicLicita       = db_utils::getDao('liclicita');
    		$sSqlBuscaTipoCompra = $oDaoLicLicita->sql_query($oParam->iCodigo, "pc50_codcom");
    		$rsBuscaTipoCompra   = $oDaoLicLicita->sql_record($sSqlBuscaTipoCompra);
    		if ($oDaoLicLicita->numrows > 0) {
    		
    			$iTipoCompra = db_utils::fieldsMemory($rsBuscaTipoCompra, 0)->pc50_codcom;
    			$oRetorno->iTipoCompraInicial = $iTipoCompra;
    		}
    }
    
    /*
     * Busca os Tipos de Empenho
     */    
    $sSqlTipoEmpenho   = $oDaoEmpTipo->sql_query_file();
    $rsExecTipoEmpenho = $oDaoEmpTipo->sql_record($sSqlTipoEmpenho);

    $aTipoEmpenho = array();

    if ($oDaoEmpTipo->numrows > 0) {

      for($iRow = 0; $iRow < $oDaoEmpTipo->numrows; $iRow++) {

        $oDadosTipoEmpenho = db_utils::fieldsMemory($rsExecTipoEmpenho, $iRow, false, false, true);
        $aTipoEmpenho[]    = $oDadosTipoEmpenho;
      }

      $oRetorno->aTipoEmpenho = $aTipoEmpenho;
    }
    
    if (count($aPcTipoCompra) > 0 && count($aTipoEmpenho) > 0) {
      $oRetorno->status = 0;
    }

  break;

  /**
   * Busca os Itens para uma Autorização
   */
  case "getItensParaAutorizacao":

    $oLicitacao       = new licitacao($oParam->iCodigo);
    $oRetorno->aItens = $oLicitacao->getItensParaAutorizacao();

  break;
  
  /**
   * Gera Autorização
   */
  case "gerarAutorizacoes":
  
  	
		if (!isset($oParam->aAutorizacoes)) {
			
			$oRetorno->status  = 2;
			$oRetorno->message = urlencode("Há muitos itens selecionados. É necessário selecionar menos itens para gerar a autorização de empenho.");
			
		} else {
  	
	    try {
	
	      /**
	       * corrigimos as strings antes de salvarmos os dados
	       */
	      foreach ($oParam->aAutorizacoes as $oAutorizacao) {
	      	 
	        $oAutorizacao->destino           = addslashes(utf8_decode(db_stdClass::db_stripTagsJson(urldecode($oAutorizacao->destino))));
          $oAutorizacao->sContato          = addslashes(utf8_decode(db_stdClass::db_stripTagsJson(urldecode($oAutorizacao->sContato))));
          $oAutorizacao->sOutrasCondicoes  = addslashes(utf8_decode(db_stdClass::db_stripTagsJson(urldecode($oAutorizacao->sOutrasCondicoes))));
	        $oAutorizacao->condicaopagamento = addslashes(utf8_decode(db_stdClass::db_stripTagsJson(urldecode($oAutorizacao->condicaopagamento))));
	        $oAutorizacao->prazoentrega      = addslashes(utf8_decode(db_stdClass::db_stripTagsJson(urldecode($oAutorizacao->prazoentrega))));
	        $oAutorizacao->resumo            = addslashes(utf8_decode(db_stdClass::db_stripTagsJson(urldecode($oAutorizacao->resumo))));
	        
	        foreach ($oAutorizacao->itens as $oItem) {
	          $oItem->observacao = addslashes(utf8_decode(db_stdClass::db_stripTagsJson(urldecode($oItem->observacao))));
	        }
	      }
	
	      db_inicio_transacao();
	      $oLicitacao = new licitacao($oParam->iCodigo);
	      $oRetorno->autorizacoes = $oLicitacao->gerarAutorizacoes($oParam->aAutorizacoes);
	      db_fim_transacao(false);
	      
	      $oRetorno->status  = 1;
	      $oRetorno->message = urlencode("Autorização efetuada com sucesso.");
	      
	    } catch (Exception $eErro) {
	
	      $oRetorno->status  = 2;
	      $oRetorno->message = urlencode($eErro->getMessage());
	      db_fim_transacao(true);
	    }
		}
    
  break;
}

echo $oJson->encode($oRetorno);
?>
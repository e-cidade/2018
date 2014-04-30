<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("std/db_stdClass.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("classes/db_liclicitem_classe.php");
require_once("model/licitacao.model.php");
require_once("model/ItemLicitacao.model.php");
require_once("model/itemSolicitacao.model.php");


$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

  switch ($oParam->exec) {
    
    /**
     * Busca os PROCESSO DE COMPRAS de uma licitacao
     */
    case "getProcessoCompras";
    
      $oRetorno->iLicitacao = $oParam->iLicitacao;
      try {
        
        $oLicitacao       = new licitacao($oParam->iLicitacao);
        $aItens           = $oLicitacao->getItens();
        $aProcessoCompras = $oLicitacao->getProcessoCompras();
        foreach ($aProcessoCompras as $oProcesso) {
          $oProcesso->iTotalItens = count($oLicitacao->getItensPorProcessoDeCompras($oProcesso->pc80_codproc));
        }
        $oRetorno->aProcCompras = $aProcessoCompras;
      } catch (Exception $eException) {
        
        $oRetorno->message = urlencode($eException->getMessage());
        $oRetorno->status = 2;
      }
    break;
    
    /**
     * Busca os ITENS associados a um processo de compras
     */
    case "getItensProcessoCompras":
      
      $oLicitacao    = new licitacao($oParam->iLicitacao);
      $aDadosItens   = $oLicitacao->getItensPorProcessoDeCompras($oParam->iProcessoCompras);
      $aRetornoItens = array();
      
      foreach ($aDadosItens as $oItemSolicitacao) {
        
        $oItem                     = $oItemSolicitacao->getItemSolicitacao();
        $oItemRetorno              = new stdClass();
        $oItemRetorno->iCodigo     = $oItem->getCodigoMaterial();
        $oItemRetorno->sDescricao  = $oItem->getDescricaoMaterial();
        $oItemRetorno->iQuantidade = $oItem->getQuantidade();
        $aRetornoItens[]           = $oItemRetorno;
      }

      $oRetorno->aItens = $aRetornoItens;
    break;
    
    /**
     * Exclui o processo de compras de uma licitacao
     */
    case "excluiProcessoCompras":
      
      db_inicio_transacao();
      try {
        
        $sProcessoCompras = ""; 
        $oLicitacao       = new licitacao($oParam->iLicitacao);
        foreach ($oParam->aProcessos as $iProcesso) {
          $oLicitacao->desvinculaProcessoDeCompras($iProcesso);          
        }
        $oRetorno->message = urlencode("Processo concluído com sucesso");
        db_fim_transacao(false);
        
      } catch (Exception $eErro) {
        
        $oRetorno->status  = 2;
        $oRetorno->message = $eErro->getMessage();
        db_fim_transacao(true);
      }
      
      
    break;
  }

echo $oJson->encode($oRetorno);
?>
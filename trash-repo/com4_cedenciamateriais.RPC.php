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
require_once("classes/db_solicita_classe.php");
require_once("classes/db_solicitavinculo_classe.php");
require_once("model/aberturaRegistroPreco.model.php");
require_once("model/estimativaRegistroPreco.model.php");
  
$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

$oDaoSolicita        = new cl_solicita();
$oDaoSolicitaVinculo = new cl_solicitavinculo(); 
  
switch ($oParam->exec) {
  
  case "getCompilacao":
    
    $sCamposSolicita        = "distinct pc10_numero, l20_codigo, pc10_resumo";
    $sSqlSolicitaCompilacao = $oDaoSolicita->sql_query_compilacao(null, $sCamposSolicita, "pc10_numero desc", "", true);
    $rsSolicitaCompilacao   = $oDaoSolicita->sql_record($sSqlSolicitaCompilacao);
    if ($oDaoSolicita->numrows > 0) {
      
      $aDadosRetorno         = db_utils::getColectionByRecord($rsSolicitaCompilacao);
      $oRetorno->aCompilacao = $aDadosRetorno;
    } else {
      
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode("Departamento n�o participa de nenhum registro de pre�o.");
    }
    
  break;
  
  case "getDepartamento":
    
    $sSolicitaWhere = "pc53_solicitafilho = {$oParam->iCompilacao}";
    $sSqlSolicitaVinculo = $oDaoSolicitaVinculo->sql_query(null, "pc53_solicitapai", null, $sSolicitaWhere);
    $rsSolicitaVinculo   = $oDaoSolicitaVinculo->sql_record($sSqlSolicitaVinculo);
    $iSolicitaVinculoPai = db_utils::fieldsMemory($rsSolicitaVinculo, 0)->pc53_solicitapai;
    
    $oAberturaRegPreco   = new aberturaRegistroPreco($iSolicitaVinculoPai);
    $aEstimativas        = $oAberturaRegPreco->getEstimativas();

    $aRetornoEstimativa  = array();
    
    /**
     * Percorre o array de estimativas e resgata os dados utilizando os m�todos do model 'estimativaRegistroPreco'
     */
    foreach ($aEstimativas as $oEstimativa) {
      
      if (!$oEstimativa->isAnulada()) {
      
        $oDadoEstimativa = new stdClass();
        $oDadoEstimativa->iDepartamento      = $oEstimativa->getCodigoDepartamento();
        if ($oDadoEstimativa->iDepartamento != db_getsession("DB_coddepto")) {
          
          $oDadoEstimativa->sDescrDepartamento = urlencode($oEstimativa->getDescricaoDepartamento());
          $oDadoEstimativa->iEstimativa        = $oEstimativa->getCodigoSolicitacao();
          $aRetornoEstimativa[]                = $oDadoEstimativa;
        } else {
          /*
           * Estimativa do DPTO em que o usu�rio est� logado
           */
          $oRetorno->iEstimativaDptoAtual = $oEstimativa->getCodigoSolicitacao();
        }
      }
    }
    
    $oRetorno->aEstimativa = $aRetornoEstimativa;
  break;
  
  case "getItens":
    
    $oEstimativaRP    = new estimativaRegistroPreco($oParam->iCodEstimativa);
    $aItensEstimativa = $oEstimativaRP->getItens();
    $aItensRetorno    = array();
    
    $oDaoSolicitem    = db_utils::getDao("solicitem");
    foreach ($aItensEstimativa as $oItem) {
      
      $oDadoItem = new stdClass();
      $oDadoItem->iOrdem         = $oItem->getOrdem();
      $oDadoItem->iCodMaterial   = $oItem->getCodigoMaterial();
      $oDadoItem->sDescrMaterial = $oItem->getDescricaoMaterial();
      $oDadoItem->sResumo        = $oItem->getResumo();
      $oDadoItem->iCodigoItem    = $oItem->getCodigoItemSolicitacao();
      /**
       * Pega as movimenta��es do item atual 
       */
      $oQtdDisponiveis         = $oItem->getMovimentacao();
      $oDadoItem->iQtdSaldo    = $oQtdDisponiveis->saldo;
      $oDadoItem->iQtdTotal    = $oQtdDisponiveis->quantidade;
      $oDadoItem->iQtdCedida   = $oQtdDisponiveis->cedidas;
      $oDadoItem->iQtdRecebida = $oQtdDisponiveis->recebidas;
      /**
       * verifica se o departamento de destino possui o item lan�ado
       */
      $sCampos                 = "estimativarecebe.pc11_codigo, pc10_numero, pc10_depto";
      $sWhere                  = "vincdoa.pc55_solicitempai = {$oDadoItem->iCodigoItem} ";
      $sWhere                 .= "and pc10_numero = {$oParam->iCodEstimativaRecebe}";
      $sSqlItemEstimativa      = $oDaoSolicitem->sql_query_item_outras_estimativas(null, $sCampos, null, $sWhere);
      $rsitemEstimativa        = $oDaoSolicitem->sql_record($sSqlItemEstimativa);
      $oDadoItem->iItemRecebe  = 0;
      if ($oDaoSolicitem->numrows > 0) {
        $oDadoItem->iItemRecebe = db_utils::fieldsMemory($rsitemEstimativa, 0)->pc11_codigo;
      }
      $aItensRetorno[] = $oDadoItem;
    }
    
    $oRetorno->aItens = $aItensRetorno;
  break;
  case "cederItens" :
    
    $oEstimativaRP    = new estimativaRegistroPreco($oParam->iEstimativa);
    try {
       
      db_inicio_transacao();
      $oEstimativaRP->cederMaterial($oParam->iDepartRecebe, $oParam->aItensCedidos);
      db_fim_transacao(false);
    } catch (Exception $eErro) {
       
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
      db_fim_transacao(true);
    }
    break;
}
echo $oJson->encode($oRetorno);
?>
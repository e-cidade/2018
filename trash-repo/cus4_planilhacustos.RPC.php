<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("dbforms/db_funcoes.php");
include("classes/db_custocriteriorateio_classe.php");
require_once("model/custoPlanilha.model.php");
require_once("model/custoPlanilhaLinha.model.php");
include("libs/JSON.php");

$oJson       = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

switch ($oParam->exec) {
  
  case "processarPlanilha":
    
    try {
    
      db_inicio_transacao();
      $oPlanilha = new custoPlanilha($oParam->iMesUsu, $oParam->iAnoUsu);
      $oPlanilha->processarPlanilha($oParam->aNiveis);
      db_fim_transacao(false);
    
    } catch (Exception $eErro) {
      
      $oRetorno->message = urlencode($eErro->getMessage());
      $oRetorno->status  = 2;
      db_fim_transacao(true);
      
    }
    break;
  
  case "getDadosPlanilha" :
    
    $sWhere = "";
    $oPlanilha = new custoPlanilha($oParam->iMesUsu, $oParam->iAnoUsu);
    if ($oParam->iNivel > 0) {
       
     $sWhere = " cc17_custoplanilhaorigem = {$oParam->iNivel}"; 
    }
    $oPlanilha->setFiltros($sWhere);
    $oRetorno->itens   = $oPlanilha->getCustosPlanilha();
    break;
    
  case "salvarPlanilha" :
    
    try {
      
      db_inicio_transacao();
      foreach ($oParam->aCustosSalvar as $oCustoSalvar) {
        
        $oPlanilha   = new custoPlanilha($oParam->iMesUsu, $oParam->iAnoUsu);
        $oCustoLinha = new custoPlanilhaLinha($oCustoSalvar->iCodigoCusto);
        $oCustoLinha->setAutomatico(false);
        $oCustoLinha->setDesdobramento($oCustoSalvar->iCodEle);
        $oCustoLinha->save($oPlanilha->getPlanilha());
        
      }
      db_fim_transacao(false);
      
    } catch (Exception $eErro) {
      
      $oRetorno->message = urlencode($eErro->getMessage());
      $oRetorno->status = 2;
      db_fim_transacao(true);
    }
}
echo $oJson->encode($oRetorno);
?>
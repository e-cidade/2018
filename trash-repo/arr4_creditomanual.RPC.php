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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_libpessoal.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");
require_once ("model/arrecadacao/Credito.model.php");
require_once ("model/arrecadacao/CreditoManual.model.php");
require_once ("model/arrecadacao/RegraCompensacao.model.php");
require_once ("model/CgmFactory.model.php");
require_once ("std/DBDate.php");
require_once ("std/db_stdClass.php");
require_once ("libs/exceptions/DBException.php");
require_once ("libs/exceptions/BusinessException.php");
require_once ("libs/exceptions/ParameterException.php");
require_once ("libs/exceptions/FileException.php");
require_once ("model/recibo.model.php");
require_once ("model/processoProtocolo.model.php");

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$lErro                  = false;

$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

switch ($oParam->sExec) {

  case "novoCredito":

    /**
     * Salva os dados no banco
     */
    
    db_inicio_transacao();
    
    try {
      
      $oCreditoManual = new CreditoManual();
      
      $oCreditoManual->adicionarRegra     (new RegraCompensacao($oParam->iCodigoRegraCompensacao));
      $oCreditoManual->setDataLancamento  (new DBDate(date('Y-m-d', db_getsession('DB_datausu'))));
      
      $oCreditoManual->setHora            (date('H:i'));
      $oCreditoManual->setUsuario         (db_getsession('DB_id_usuario'));
      $oCreditoManual->setInstituicao     (db_getsession('DB_instit'));
      $oCreditoManual->setValor           ($oParam->fValor);
      $oCreditoManual->setObservacao      (db_stdClass::normalizeStringJson($oParam->sObservacao));
      $oCreditoManual->setPercentual      (100);
      $oCreditoManual->setCgm             (CgmFactory::getInstanceByCgm($oParam->iCodigoCgm));
      
      if (!empty($oParam->lProcessoSistema)) {
        
        $oCreditoManual->setProcessoSistema ($oParam->lProcessoSistema == 'S' ? true : false);
        
        if ($oCreditoManual->isProcessoSistema()) {
        
        	$oCreditoManual->setProcessoProtocolo(new processoProtocolo($oParam->iCodigoProcessoSistema));
        	
        } else {
        
        	$oCreditoManual->setNumeroProcessoExterno     ($oParam->sNumeroProcessoExterno);
        	$oCreditoManual->setNomeTitularProcessoExterno(db_stdClass::normalizeStringJson($oParam->sNomeTitularProcessoExterno));
        	
        	if (!empty($oParam->dDataProcessoExterno)) {
        	  $oCreditoManual->setDataProcessoExterno       (new DBDate($oParam->dDataProcessoExterno));
        	}
        	
        }
        
      }
      
      if (!empty($oParam->dDataExpiracao)) {
      	
      	$oCreditoManual->setDataExpiracao (new DBDate($oParam->dDataExpiracao));
      }
      
      $oCreditoManual->salvar();
      
    } catch (Exception $oErro) {  //Exception
       
      $oRetorno->iStatus  = 2;
      
      $oRetorno->sMessage = urlencode($oErro->getMessage());
      
      $lErro              = true;
      
    }
    
    db_fim_transacao($lErro);
    
    break;
    
  case 'calculaDataExpiracao':
    
    $oRetorno->dDataExpiracao = '';
    
    $oRegraCompensacao        = new RegraCompensacao($oParam->iCodigoRegraCompensacao);
    
    if ($oParam->dDataLancamento != '') {
      $oRegraCompensacao->setDataLancamento(new DBDate($oParam->dDataLancamento));
    }
    
    
    if ($oRegraCompensacao->getDataValidade() instanceof DBDate) {
    	
      $oRetorno->dDataExpiracao = $oRegraCompensacao->getDataValidade()->getDate(DBDate::DATA_PTBR);
    }
    
    break;    

}

echo $oJson->encode($oRetorno);

?>
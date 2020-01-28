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

require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("classes/db_habitprograma_classe.php");
require_once("classes/db_habitcandidato_classe.php");
require_once("model/CgmBase.model.php");
require_once("model/CgmFisico.model.php");

$oJson             = new services_json();
$oParam            = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\","",$_POST["json"])));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = 1;
$lErro             = false;
$sMensagem         = "";
$sWhere            = '';

switch($oParam->exec) {

  case 'salva_situacao' :
    
	  db_inicio_transacao();

	  $aSituacao        = array();
   	$iCgm             = $oParam->iCgm;
    $iSituacao        = $oParam->iSituacao;
    $cl_SituacaoCpf   = new CgmFisico();
	    
	  try {	    
	    	
		  $cl_SituacaoCpf->setCodigo($iCgm);
		  $cl_SituacaoCpf->setSituacao($iSituacao);
		  $cl_SituacaoCpf->save();
		    	
	    $oDadosSalvo        = new stdClass(); 
	    $oDadosSalvo->salvo = "Operaчуo Realizada com Sucesso.";
	    $aSituacao[]        = $oDadosSalvo;	    	
		    
	    db_fim_transacao();
	    $oRetorno->dados    = $aSituacao;
	  } catch (Exception $eErro) {
	      
	    db_fim_transacao(true);
	    $oRetorno->status  = 2; 
	    $oRetorno->message = urlencode($eErro->getMessage()); 
	  }       
        
    break;   
  
  case 'consultaSituacaoCPF' :
    
    try {
    	     
      $oCgm = new CgmFisico($oParam->iCgm);
      $oRetorno->iSituacao = $oCgm->getSituacao();

    } catch (Exception $eErro) {
        
      $oRetorno->status  = 2; 
      $oRetorno->message = urlencode($eErro->getMessage()); 
    }       
        
    break;    
  
  
  
}
  
echo $oJson->encode($oRetorno);   

?>
<?
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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/JSON.php");



$oPost    = db_utils::postMemory($_POST);

$oJson    = new services_json();

$oRetorno = new stdClass();

$oRetorno->status  = 1;

$oRetorno->message = '';

$oParam = $oJson->decode(str_replace("\\","",$oPost->json));

switch ($oParam->exec) {
  
  case 'perquisarPorRota':
 
	  $oDaoAguaRotaRua = db_utils::getDao('aguarotarua');
	  
	  $sSqlAguaRotaRua = $oDaoAguaRotaRua->sql_query(null, 'x07_codrota, x06_descr', null, "x07_codrua = {$oParam->rua} and {$oParam->nro} between x07_nroini and x07_nrofim");
	  
	  $rDaoAguaRotaRua = $oDaoAguaRotaRua->sql_record($sSqlAguaRotaRua);
	  
	  if($oDaoAguaRotaRua->numrows > 0) {
	    
	    $oAguaRotaRua = db_utils::fieldsMemory($rDaoAguaRotaRua, 0);
	    
	    $oRetorn->status      = 1;
	    
	    $oRetorno->iCodRota   = $oAguaRotaRua->x07_codrota;
	    
	    $oRetorno->sDescricao = $oAguaRotaRua->x06_descr;
	  
	  } else {
	    
	    $oRetorno->status     = 0;
	    
	  }
	
	  echo $oJson->encode($oRetorno);
	  
	  break;
		
}

?>
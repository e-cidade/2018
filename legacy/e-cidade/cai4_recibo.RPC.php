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
require_once("libs/JSON.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("std/db_stdClass.php");
require_once("dbforms/db_funcoes.php");

$oPost             = db_utils::postMemory($_POST);
$oGet              = db_utils::postMemory($_GET);

$oJson             = new services_json();
$oParam            = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\", "", $oPost->json)));

$oRetorno          = new stdClass;
$oRetorno->status  = 1;
$oRetorno->message = "";

switch ($oParam->exec) {
	
  case "getConCarPeculiar":
    
  	$oDaoTabRec                = db_utils::getDao("tabrec");        
    $sSqlTabRecConCarPeculiar  = $oDaoTabRec->sql_query_concarpeculiar($oParam->iCodigoReceita, 
                                                                       "o70_concarpeculiar", null, null);
    $rsSqlTabRecConCarPeculiar    = $oDaoTabRec->sql_record($sSqlTabRecConCarPeculiar);
    $oRetorno->o70_concarpeculiar = null;
    if ($oDaoTabRec->numrows > 0) {
    	
    	$oTabRecConCarPeculiar        = db_utils::fieldsMemory($rsSqlTabRecConCarPeculiar, 0);
    	$oRetorno->o70_concarpeculiar = $oTabRecConCarPeculiar->o70_concarpeculiar;
    }
    
    break;
}

echo $oJson->encode($oRetorno);
?>
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
require_once("std/db_stdClass.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("classes/db_rhsuspensaopag_classe.php");

$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

switch ($oParam->exec) {
  
  case "getSuspensoes":
    $sCamposSuspensoes      = "rh101_sequencial, rh101_regist, rh101_dtcadastro, rh101_dtcadastro, rh101_usuario, ";
    $sCamposSuspensoes     .= "db_usuarios.nome, rh101_dtinicial, rh101_dtfinal, rh101_dtdesativacao, rh101_obs"; 
    $sOrdemSuspensoes       = "rh101_dtdesativacao DESC";
    $sWhereSuspensoes       = "rh101_regist = {$oParam->oDados->rh101_regist}";
    
    $oSuspensoes            = new cl_rhsuspensaopag();
    $sSqlSuspensoes         = $oSuspensoes->sql_query(null, $sCamposSuspensoes, $sOrdemSuspensoes, $sWhereSuspensoes);
    $rsSuspensoes           = $oSuspensoes->sql_record($sSqlSuspensoes);
    
    $aSuspensoes            = db_utils::getColectionByRecord($rsSuspensoes, false, false, true);    
    $oRetorno->aSuspensoes  =  $aSuspensoes;
  break;
}
echo $oJson->encode($oRetorno);
?>
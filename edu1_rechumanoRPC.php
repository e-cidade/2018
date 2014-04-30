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
include("libs/JSON.php");
include("libs/db_usuariosonline.php");
include("classes/db_rechumanopessoal_classe.php");
include("classes/db_rechumanocgm_classe.php");
include("dbforms/db_funcoes.php");
$clrechumanopessoal = new cl_rechumanopessoal;
$clrechumanocgm = new cl_rechumanocgm;

$oPost = db_utils::postMemory($_POST);

if($oPost->sAction == 'VerificaMatricula') {
 $result = $clrechumanopessoal->sql_record($clrechumanopessoal->sql_query("","ed284_i_rhpessoal","","  ed284_i_rhpessoal = {$oPost->matricula}"));
 if($clrechumanopessoal->numrows>0){
  $retorno = "1";
  $matricula = $oPost->matricula;
 }else{
  $retorno = "0";
  $matricula = $oPost->matricula;
 }
 $oJson = new services_json();
 echo $oJson->encode(array($retorno,$matricula));
}

if($oPost->sAction == 'VerificaCGM') {
 $result = $clrechumanocgm->sql_record($clrechumanocgm->sql_query("","ed285_i_cgm","","  ed285_i_cgm = {$oPost->cgm}"));
 if($clrechumanocgm->numrows>0){
  $retorno = "1";
  $cgm = $oPost->cgm;
 }else{
  $retorno = "0";
  $cgm = $oPost->cgm;
 }
 $oJson = new services_json();
 echo $oJson->encode(array($retorno,$cgm));
}

?>
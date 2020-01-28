<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
include("libs/db_stdlibwebseller.php");
include("libs/JSON.php");

include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");


//$oPost = db_utils::postMemory($_POST);
$objJson             = new services_json();
$objParam            = $objJson->decode(str_replace("\\","",$_POST["json"]));
$objRetorno          = new stdClass();
$objRetorno->status  = 1;
$objRetorno->message = '';
$departamento= db_getsession("DB_coddepto");
$descrdepto = db_getsession("DB_nomedepto");
$hoje=date("d/m/Y",db_getsession("DB_datausu"));
$hoje2=date("Y-m-d",db_getsession("DB_datausu"));
$login = DB_getsession("DB_id_usuario");

if($objParam->exec == 'getvalor'){
   $objRetorno->alvo=$objParam->alvo;
   //$classe = db_utils::getDao($objParam->tabela);
   require_once "classes/db_{$objParam->tabela}_classe.php"; 
   eval ("\$classe = new cl_{$objParam->tabela};");
   if(($objParam->where!="")||($objParam->where!=null)){
      $where=$objParam->nome."=".$objParam->valor." and ".$objParam->where;
   }else{
   	  $where=$objParam->nome."=".$objParam->valor;
   }
   $sql = $classe->sql_query("",$objParam->campo,"",$where);
   $result = $classe->sql_record($sql);
   if($classe->numrows>0){
   	 $objRetorno->valor = pg_result($result,0,0);
   }else{
   	 $objRetorno->valor = "Chave (".$objParam->valor.") Não encontrada.";
   }
}

echo $objJson->encode($objRetorno);
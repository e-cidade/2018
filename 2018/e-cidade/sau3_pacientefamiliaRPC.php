<?
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
include("libs/db_stdlibwebseller.php");
include("libs/JSON.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_familiamicroarea_classe.php");
include("classes/db_cgs_und_classe.php");

$clfamiliamicroarea  = new cl_familiamicroarea;
$clcgs_und  = new cl_cgs_und;

$objJson             = new services_json();
$objParam            = $objJson->decode(str_replace("\\","",$_POST["json"]));
$objRetorno          = new stdClass();
$objRetorno->status  = 1;
$objRetorno->message = "";

if($objParam->exec == 'consulta_microfamilia'){
   $sql=$clfamiliamicroarea->sql_query_cgs($objParam->cgs," sd33_i_codigo,sd33_v_descricao,sd34_i_codigo,sd34_v_descricao,z01_i_familiamicroarea ","","");
   $result=$clfamiliamicroarea->sql_record($sql);
   if($result!=false){  
       $campos = new stdclass();
       db_fieldsmemory($result,0);
       $campos->sd33_i_codigo=$sd33_i_codigo;
       $campos->sd33_v_descricao=$sd33_v_descricao;
       $campos->sd34_i_codigo=$sd34_i_codigo;
       $campos->sd34_v_descricao=$sd34_v_descricao;
       $campos->z01_i_familiamicroarea=$z01_i_familiamicroarea;
       $objRetorno->campo=$campos;
   }else{
      $objRetorno->status  = 0;
      $objRetorno->message = $clfamiliamicroarea->erro_msg;
   }
}

echo $objJson->encode($objRetorno);
?>
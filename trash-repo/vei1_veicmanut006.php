<?
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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_veicmanut_classe.php");
include("classes/db_veicmanutitem_classe.php");
include("classes/db_veicmanutoficina_classe.php");
include("classes/db_veicmanutretirada_classe.php");
include("classes/db_veiculos_classe.php");
include("classes/db_veictipoabast_classe.php");
include("classes/db_veicretirada_classe.php"); 
db_app::import("veiculos.*");
$clveicmanut = new cl_veicmanut;
$clveiculos  = new cl_veiculos;
/*$clveicmanutitem = new cl_veicmanutitem;*/
$clveicmanutoficina = new cl_veicmanutoficina;
$clveicmanutretirada = new cl_veicmanutretirada;
db_postmemory($HTTP_POST_VARS);
   $db_opcao = 33;
$db_botao = false;
if(isset($excluir)){
  $sqlerro=false;
  db_inicio_transacao();
  if ($sqlerro==false){
  	$result_oficina=$clveicmanutoficina->sql_record($clveicmanutoficina->sql_query(null,"*",null,"ve66_veicmanut=$ve62_codigo"));   
  	if($clveicmanutoficina->numrows>0){
  	 	$clveicmanutoficina->excluir(null,"ve66_veicmanut=$ve62_codigo");
  		if ($clveicmanutoficina->erro_status=="0"){
  			$erro_msg=$clveicmanutoficina->erro_msg;
  			$sqlerro=true;
  		}
  	}
  } 
  if ($sqlerro==false){
  	$result_retirada=$clveicmanutretirada->sql_record($clveicmanutretirada->sql_query(null,"*",null,"ve65_veicmanut=$ve62_codigo"));
  	if($clveicmanutretirada->numrows>0){
  		$clveicmanutretirada->excluir(null,"ve65_veicmanut=$ve62_codigo");
  		if ($clveicmanutretirada->erro_status=="0"){
  			$erro_msg=$clveicmanutretirada->erro_msg;
  			$sqlerro=true;
  		}
  	}
  }
  if ($sqlerro==false){
  	$clveicmanut->excluir($ve62_codigo);
  	if($clveicmanut->erro_status==0){
    	$sqlerro=true;
  	} 
  	$erro_msg = $clveicmanut->erro_msg;
  } 
  db_fim_transacao($sqlerro);
   $db_opcao = 3;
   $db_botao = true;
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $db_botao = true;
   $result = $clveicmanut->sql_record($clveicmanut->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $result_oficina=$clveicmanutoficina->sql_record($clveicmanutoficina->sql_query(null,"*",null,"ve66_veicmanut=$chavepesquisa"));
   if($clveicmanutoficina->numrows>0){
   	db_fieldsmemory($result_oficina,0);
   }
   $result_retirada=$clveicmanutretirada->sql_record($clveicmanutretirada->sql_query(null,"*",null,"ve65_veicmanut=$ve62_codigo"));
   if($clveicmanutretirada->numrows>0){
   	db_fieldsmemory($result_retirada,0);
   }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" style='margin-top: 25px' topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
	<?
	include("forms/db_frmveicmanut.php");
	?>
</body>
</html>
<?
if(isset($excluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clveicmanut->erro_campo!=""){
      echo "<script> document.form1.".$clveicmanut->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clveicmanut->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
 echo "
  <script>
    function js_db_tranca(){
      parent.location.href='vei1_veicmanut003.php';
    }\n
    js_db_tranca();
  </script>\n
 ";
  }
}
if(isset($chavepesquisa)){
 echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.veicmanutitem.disabled=false;
         top.corpo.iframe_veicmanutitem.location.href='vei1_veicmanutitem001.php?db_opcaoal=3&ve63_veicmanut=".@$ve62_codigo."';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('veicmanutitem');";
         }
 echo"}\n
    js_db_libera();
  </script>\n
 ";
}
 if($db_opcao==22||$db_opcao==33){
    echo "<script>document.form1.pesquisar.click();</script>";
 }
?>
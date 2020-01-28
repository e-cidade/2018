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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_db_almoxdepto_classe.php");
include("classes/db_db_almox_classe.php");
include("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$cldb_almoxdepto = new cl_db_almoxdepto;
$cldb_almox = new cl_db_almox;

$db_opcao = 22;
$db_botao = false;
if(isset($alterar) || isset($excluir) || isset($incluir)){
  $sqlerro = false;

  $cldb_almoxdepto->m92_codalmox = $m92_codalmox;
  $cldb_almoxdepto->m92_depto = $m92_depto;
 
}
if(isset($incluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $cldb_almoxdepto->incluir($m92_codalmox,$m92_depto);
    $erro_msg = $cldb_almoxdepto->erro_msg;
    if($cldb_almoxdepto->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($alterar)){
  if($sqlerro==false){
  	db_inicio_transacao();
    $cldb_almoxdepto->alterar($m92_codalmox,$m92_depto);
    $erro_msg = $cldb_almoxdepto->erro_msg;
    if($cldb_almoxdepto->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($excluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $cldb_almoxdepto->excluir($m92_codalmox,$m92_depto);
    $erro_msg = $cldb_almoxdepto->erro_msg;
    if($cldb_almoxdepto->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($opcao)){
   $result = $cldb_almoxdepto->sql_record($cldb_almoxdepto->sql_query($m92_codalmox,$m92_depto));
   if($result!=false && $cldb_almoxdepto->numrows>0){
     db_fieldsmemory($result,0);
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
<table border="0" cellspacing="0" style="padding-top:15px" cellpadding="0">
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC"> 
	<?
	  include("forms/db_frmdb_almoxdepto.php");
	?>
	</td>
  </tr>
</table>
</center>
</body>
</html>
<?
if (isset($alterar) || isset($excluir) || isset($incluir)) {
    
	db_msgbox($erro_msg);
    
    if ( $erro_msg != null) {
      echo "<script> document.form1.".$cldb_almoxdepto->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cldb_almoxdepto->erro_campo.".focus();</script>";
    }
    
    echo " <script>                               ";
    echo "   document.form1.descrdepto.value =''; ";
    echo "   document.form1.m92_depto.value  =''; ";
    echo " </script>                              ";
    
}
?>
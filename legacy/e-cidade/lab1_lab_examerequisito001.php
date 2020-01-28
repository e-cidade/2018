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
include("classes/db_lab_examerequisito_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$cllab_examerequisito = new cl_lab_examerequisito;
$db_opcao = 1;
$db_botao = true;
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
<br><br>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <fieldset style='width: 95%;'> <legend><b>Exame Requisito</b></legend>
	<?
	include("forms/db_frmlab_examerequisito.php");
	?>
	</fieldset>
    </center>
	</td>
  </tr>
</table>
</center>
</body>
</html>
<script>
js_tabulacaoforms("form1","la20_i_requisito",true,1,"la20_i_requisito",true);
</script>
<?
if(isset($incluir)){
 db_inicio_transacao();
 $cllab_examerequisito->incluir($la20_i_codigo);
 db_fim_transacao();
 if($cllab_examerequisito->erro_status=="0"){
  $cllab_examerequisito->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($cllab_examerequisito->erro_campo!=""){
   echo "<script> document.form1.".$cllab_examerequisito->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$cllab_examerequisito->erro_campo.".focus();</script>";
  };
 }else{
  $cllab_examerequisito->erro(true,false);
  db_redireciona("lab1_lab_examerequisito001.php?la20_i_exame=$la20_i_exame&la08_c_descr=$la08_c_descr");
 };
}

if(isset($alterar)){
 db_inicio_transacao();
 $cllab_examerequisito->alterar($la20_i_codigo);
 db_fim_transacao();
 if($cllab_examerequisito->erro_status=="0"){
  $cllab_examerequisito->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($cllab_examerequisito->erro_campo!=""){
   echo "<script> document.form1.".$cllab_examerequisito->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$cllab_examerequisito->erro_campo.".focus();</script>";
  };
 }else{
  $cllab_examerequisito->erro(true,false);
  db_redireciona("lab1_lab_examerequisito001.php?la20_i_exame=$la20_i_exame&la08_c_descr=$la08_c_descr");
 };
}

if(isset($excluir)){
 db_inicio_transacao();
 $cllab_examerequisito->excluir($la20_i_codigo);
 db_fim_transacao();
 if($cllab_examerequisito->erro_status=="0"){
  $cllab_examerequisito->erro(true,false);
 }else{
  $cllab_examerequisito->erro(true,false);
  db_redireciona("lab1_lab_examerequisito001.php?la20_i_exame=$la20_i_exame&la08_c_descr=$la08_c_descr");
 };
}
if(isset($cancelar)){ 
 db_redireciona("lab1_lab_examerequisito001.php?la20_i_exame=$la20_i_exame&la08_c_descr=$la08_c_descr");
}
?>
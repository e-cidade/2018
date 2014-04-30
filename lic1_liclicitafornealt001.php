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
include("classes/db_liclicitaforne_classe.php");
include("classes/db_liclicita_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clliclicitaforne = new cl_liclicitaforne;
$clliclicita = new cl_liclicita;
$db_botao = false;
$db_opcao=1;
$op=11;
if(isset($incluir)){
  $sqlerro=false;
  db_inicio_transacao();
    $clliclicitaforne->incluir(null);
    $erro_msg=$clliclicitaforne->erro_msg;
    if ($clliclicitaforne->erro_status==0){
    	$sqlerro=true;
    }
    $db_opcao=1;
    $op=1;
    $db_botao = true;
  db_fim_transacao($sqlerro);
}else if(isset($alterar)){
  $sqlerro=false;
  db_inicio_transacao();
  
  $clliclicitaforne->alterar(@$l22_codigo);
  $erro_msg=$clliclicitaforne->erro_msg;
    if ($clliclicitaforne->erro_status==0){
    	$sqlerro=true;
    }
    $db_opcao=2;
    $op=1;
    $db_botao = true;
  db_fim_transacao($sqlerro);
}else if(isset($excluir)){
  $sqlerro=false;
  db_inicio_transacao();
  $clliclicitaforne->excluir(@$l22_codigo);
  $erro_msg=$clliclicitaforne->erro_msg;
    if ($clliclicitaforne->erro_status==0){
    	$sqlerro=true;
    }
    $db_opcao=3;
    $op=1;
    $db_botao = true;
  db_fim_transacao($sqlerro);
}else if(isset($chavepesquisa)){
   $db_opcao = 1;
   $result = $clliclicita->sql_record($clliclicita->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $db_botao = true;
   $op=1;
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">

  <tr>
  <br> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmliclicitafornealt.php");
	?>
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($incluir) || isset($alterar) || isset($excluir)){
  if($sqlerro==true){
    $clliclicitaforne->erro(true,false);
    if($clliclicitaforne->erro_campo!=""){
      echo "<script> parent.document.form1.".$clliclicitaforne->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> parent.document.form1.".$clliclicitaforne->erro_campo.".focus();</script>";
    }
  }else{
    db_msgbox($erro_msg);
    echo "<script>location.href='lic1_liclicitafornealt001.php?chavepesquisa=$l22_codliclicita';</script>";
  }
}  
if($op==11){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
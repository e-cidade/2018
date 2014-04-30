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
include("classes/db_configdbprefagua_classe.php");
include("dbforms/db_funcoes.php");
include("libs/db_utils.php");
db_postmemory($HTTP_POST_VARS);
$oPost = db_utils::postMemory($_POST);

$clconfigdbprefagua = new cl_configdbprefagua;

$db_opcao = 1;
$db_botao = true;
$lErro    = true;

if(isset($oPost->alterar) || isset($oPost->excluir) || isset($oPost->incluir)){
  $sqlerro = false;
}

if(isset($oPost->incluir)){
	
	if($sqlerro==false){
		 db_inicio_transacao();
		 
		 $result = $clconfigdbprefagua->sql_record($clconfigdbprefagua->sql_query(null,"*",null,"w16_instit = {$w16_instit} and w16_aguacortesituacao = {$w16_aguacortesituacao}"));
		 if($clconfigdbprefagua->numrows > 0){
		 	$erro_msg = "Usuário: \\n\\n Registro já cadastrado com os valores informados !\\n\\n Inclusão Abortada.\\n\\nAdministrador: \\n\\n ";
		 	$sqlerro = true;
		 }else{
		 	 $clconfigdbprefagua->incluir($w16_sequencial);
			 $erro_msg = $clconfigdbprefagua->erro_msg;
			 if($clconfigdbprefagua->erro_status == 0){
			 	$sqlerro = true;
			 }
		 }
		 
		 db_fim_transacao($sqlerro);
	}
	
}else if($oPost->alterar){
	
	if($sqlerro==false){
		 db_inicio_transacao();
		
		 $clconfigdbprefagua->alterar($w16_sequencial);
		 $erro_msg = $clconfigdbprefagua->erro_msg;
		 if($clconfigdbprefagua->erro_status == 0){
		 	$sqlerro = true;
		 }
		 
		 db_fim_transacao($sqlerro);
	}
	
}else if($oPost->excluir){
	
	if($sqlerro==false){
		 db_inicio_transacao();
		
		 $clconfigdbprefagua->excluir($w16_sequencial);
		 $erro_msg = $clconfigdbprefagua->erro_msg;
		 if($clconfigdbprefagua->erro_status == 0){
		 	$sqlerro = true;
		 }
		 
		 db_fim_transacao($sqlerro);
	}
	
}
else if(isset($opcao)){
	$w16_instit = db_getsession('DB_instit');
	$result = $clconfigdbprefagua->sql_record($clconfigdbprefagua->sql_query(null,'*',null,"w16_sequencial = {$w16_sequencial} and w16_instit = {$w16_instit}"));
	if($clconfigdbprefagua->numrows > 0){
		db_fieldsmemory($result,0);
	}
}
/*
if(isset($incluir)){
  db_inicio_transacao();
  $clconfigdbprefagua->incluir($w16_sequencial);
  db_fim_transacao();
}
*/
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmconfigdbprefagua.php");
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
<script>
js_tabulacaoforms("form1","w16_instit",true,1,"w16_instit",true);
</script>
<?
if(isset($oPost->alterar) || isset($oPost->excluir) || isset($oPost->incluir)){
    if ($lErro){
	  db_msgbox($erro_msg);
	    if($clconfigdbprefagua->erro_campo!=""){
	      echo "<script> document.form1.".$clconfigdbprefagua->erro_campo.".style.backgroundColor='#99A9AE';</script>";
	      echo "<script> document.form1.".$clconfigdbprefagua->erro_campo.".focus();</script>";
	    }
    }
}
/*
if(isset($incluir)){
  if($clconfigdbprefagua->erro_status=="0"){
    $clconfigdbprefagua->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clconfigdbprefagua->erro_campo!=""){
      echo "<script> document.form1.".$clconfigdbprefagua->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clconfigdbprefagua->erro_campo.".focus();</script>";
    }
  }else{
    $clconfigdbprefagua->erro(true,true);
  }
}
*/
?>
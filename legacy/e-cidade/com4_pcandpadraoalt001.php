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
include("classes/db_pcandpadrao_classe.php");
include("classes/db_pcandpadraodepto_classe.php");
include("classes/db_pctipoandam_classe.php");
include("classes/db_db_config_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$clpcandpadrao = new cl_pcandpadrao;
$clpcandpadraodepto = new cl_pcandpadraodepto;
$clpctipoandam = new cl_pctipoandam;
$cldb_config = new cl_db_config;
if (!isset($db_opcao)){
$db_opcao = 1;
}
$db_botao = true;

if(isset($dbopcao) && $dbopcao=="Incluir") {
  db_inicio_transacao();
  $sqlerro=false;
  $clpcandpadrao->incluir(null);
  $erro_msg=$clpcandpadrao->erro_msg;
  if ($clpcandpadrao->erro_status==0){
  	$sqlerro=true;
  }
  if (isset($pc46_depart)&&$pc46_depart!=""){
    $clpcandpadraodepto->incluir($clpcandpadrao->pc45_codigo);
    if ($clpcandpadraodepto->erro_status==0){
  	  $sqlerro=true;
  	  $erro_msg=$clpcandpadraodepto->erro_msg;
    }
  }
  db_fim_transacao($sqlerro);
}else if (isset($dbopcao) && $dbopcao=="Alterar"){
	db_inicio_transacao();
  $sqlerro=false;
  $clpcandpadrao->pc45_instit=$pc45_instit;
  $clpcandpadrao->pc45_pctipoandam=$pc45_pctipoandam;
  $clpcandpadrao->pc45_dias=$pc45_dias;  
  $clpcandpadrao->pc45_ordem=$pc45_ordem;
  $clpcandpadrao->alterar($pc45_codigo);
  $erro_msg=$clpcandpadrao->erro_msg;
  if ($clpcandpadrao->erro_status==0){
  	$sqlerro=true;
  }
  if (isset($pc46_depart)&&$pc46_depart!=""){
  	$clpcandpadraodepto->pc46_pcandpadrao=$pc45_codigo;
  	$clpcandpadraodepto->pc46_depart=$pc46_depart;
    $clpcandpadraodepto->alterar($pc45_codigo);
    if ($clpcandpadraodepto->erro_status==0){
  	  $sqlerro=true;
  	  $erro_msg=$clpcandpadraodepto->erro_msg;
    }
  }
  db_fim_transacao($sqlerro);  
}else if (isset($dbopcao) && $dbopcao=="Excluir"){
  db_inicio_transacao();
  $sqlerro=false;
  
  if (isset($pc46_depart)&&$pc46_depart!=""){
    $clpcandpadraodepto->excluir($pc45_codigo);
    if ($clpcandpadraodepto->erro_status==0){
  	  $sqlerro=true;
  	  $erro_msg=$clpcandpadraodepto->erro_msg;
    }
  }
  if($sqlerro==false){
    $clpcandpadrao->excluir($pc45_codigo);
    $erro_msg=$clpcandpadrao->erro_msg;
    if ($clpcandpadrao->erro_status==0){
   	   $sqlerro=true;
    } 
  }
  db_fim_transacao($sqlerro);
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
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmpcandpadraoalt.php");
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
if(isset($dbopcao)&&$db_opcao!=""){
if($clpcandpadrao->erro_status=="0"){
  $clpcandpadrao->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clpcandpadrao->erro_campo!=""){
    echo "<script> document.form1.".$clpcandpadrao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$clpcandpadrao->erro_campo.".focus();</script>";
  }
}else{
	db_msgbox($erro_msg);
	echo "<script>location.href='com4_pcandpadraoalt001.php';</script>"; 
	
}
}
?>
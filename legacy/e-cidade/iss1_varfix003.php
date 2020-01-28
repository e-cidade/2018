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
include("classes/db_varfix_classe.php");
include("classes/db_varfixval_classe.php");
include("classes/db_varfixproc_classe.php");
include("classes/db_varfixnotifica_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clvarfix = new cl_varfix;
$clvarfixval = new cl_varfixval;
$clvarfixproc = new cl_varfixproc;
$clvarfixnotifica = new cl_varfixnotifica;

$db_opcao   = 33;
$db_botao=false;

$db_opcao02  = 33;
$db_botao02=false;
if (isset($excluir)) {
  $sqlerro=false;
  db_inicio_transacao();

  $result = $clvarfixval->sql_record($clvarfixval->sql_query_file(null,"*",null," q34_codigo = $q33_codigo "));
  if ($clvarfixval->numrows > 0) {
    $clvarfixval->q34_codigo = $q33_codigo;
    $clvarfixval->excluir(null," q34_codigo = $q33_codigo ");
    $erro_msg = $clvarfixval->erro_msg;
    if ($clvarfixval->erro_status==0) {
      $sqlerro=true;
    }
  }
  
  $result=$clvarfixnotifica->sql_record($clvarfixnotifica->sql_query_file(null,"*",null,null,"q37_varfix=$q33_codigo"));
  if ($clvarfixnotifica->numrows>0) {
    $clvarfixnotifica->excluir(null,"q37_varfix=$q33_codigo");
  }
  $erro_msg=$clvarfixproc->erro_msg;
  if ($clvarfixproc->erro_status==0) {
    $sqlerro=true;
  }
  
  $result=$clvarfixproc->sql_record($clvarfixproc->sql_query_file(null,"*",null,null,"q36_varfix=$q33_codigo"));
  if ($clvarfixproc->numrows>0) {
    $clvarfixproc->excluir(null,"q36_varfix=$q33_codigo");
  }
  $erro_msg=$clvarfixproc->erro_msg;
  if ($clvarfixproc->erro_status==0) {
    $sqlerro=true;
  }
  
  $clvarfix->excluir($q33_codigo);
  $erro_msg=$clvarfix->erro_msg;
  if ($clvarfix->erro_status==0) {
    $sqlerro=true;
  }
  
  db_fim_transacao();
  
} else if (isset($chavepesquisa)) {
  $result = $clvarfix->sql_record($clvarfix->sql_query($chavepesquisa,'q33_hora,q33_obs,q33_data,q33_codigo,q33_inscr,z01_nome,q33_tiporeg'));
  db_fieldsmemory($result,0);
  $result = $clvarfix->sql_record($clvarfix->sql_query($chavepesquisa,'q33_hora,q33_obs,q33_data,q33_codigo,q33_inscr,z01_nome,q33_tiporeg'));
  
  $result = $clvarfixproc->sql_record($clvarfixproc->sql_query(null,'q36_processo,p58_requer',null,"q36_varfix=$q33_codigo"));
  if ($clvarfixproc->numrows>0) {
    db_fieldsmemory($result,0);
  }
  
  $result = $clvarfixnotifica->sql_record($clvarfixnotifica->sql_query(null,'q37_notifica,y30_nome',null,"q37_varfix=$q33_codigo"));
  if ($clvarfixnotifica->numrows>0) {
    db_fieldsmemory($result,0);
  }
  
  $db_opcao = 3;
  $db_botao = true;
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmvarfix.php");
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
if(isset($excluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clvarfix->erro_campo!=""){
      echo "<script> document.form1.".$clvarfix->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clvarfix->erro_campo.".focus();</script>";
    }
  }else{
    db_msgbox($erro_msg);
    db_redireciona('iss1_varfix003.php');
  }
}
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
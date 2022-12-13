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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("classes/db_ouvidoriaparametro_classe.php");
require_once("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clouvidoriaparametro = new cl_ouvidoriaparametro;
$db_botao = true;

$ov06_instit	= db_getsession('DB_instit');
$ov06_anousu 	= db_getsession('DB_anousu');

//Verifico se j� existe algum par�metro se sim op��o=2 sen�o 1
$result = $clouvidoriaparametro->sql_record($clouvidoriaparametro->sql_query_file($ov06_instit,$ov06_anousu));

if ($clouvidoriaparametro->numrows > 0) {
  
	db_fieldsmemory($result,0);
	$db_opcao = 2;	 
}else{
	$db_opcao = 1;
}

if (isset($incluir)) {
  
  db_inicio_transacao();
  $clouvidoriaparametro->ov06_db_documentotemplate = $documento;
  $clouvidoriaparametro->incluir($ov06_instit,$ov06_anousu);
  db_fim_transacao();
} elseif (isset($alterar)) {
  
	db_inicio_transacao();
  $db_opcao = 2;
  $clouvidoriaparametro->ov06_anousu = $ov06_anousu;
  $clouvidoriaparametro->ov06_instit = $ov06_instit;
  $clouvidoriaparametro->ov06_db_documentotemplate = $documento;
  $clouvidoriaparametro->alterar($ov06_instit,$ov06_anousu);
  db_fim_transacao();
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
  <tr align="center"> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmouvidoriaparametro.php");
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
js_tabulacaoforms("form1","ov06_anousu",true,1,"ov06_anousu",true);
</script>
<?
if(isset($incluir) || isset($alterar)){
  if($clouvidoriaparametro->erro_status=="0"){
    $clouvidoriaparametro->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clouvidoriaparametro->erro_campo!=""){
      echo "<script> document.form1.".$clouvidoriaparametro->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clouvidoriaparametro->erro_campo.".focus();</script>";
    }
  }else{
    $clouvidoriaparametro->erro(true,true);
  }
}
?>
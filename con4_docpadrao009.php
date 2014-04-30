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
include("classes/db_db_config_classe.php");
include("classes/db_db_documentopadrao_classe.php");
include("classes/db_db_paragrafopadrao_classe.php");
include("classes/db_db_docparagpadrao_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$cldb_config    = new cl_db_config;
$cldb_documentopadrao = new cl_db_documentopadrao;
$cldb_paragrafopadrao = new cl_db_paragrafopadrao;
$cldb_docparagpadrao = new cl_db_docparagpadrao;

$db_opcao = 22;
$db_botao = false;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){
  db_inicio_transacao();
  $db_opcao = 2;
  
  $cldb_docparagpadrao->db62_coddoc=$db60_coddoc;
  $cldb_docparagpadrao->excluir($db60_coddoc);
  
  if(isset($campos)){
    $tamanho=sizeof($campos);
    for($i=0; $i < $tamanho; $i++){
      $cldb_docparagpadrao->db62_coddoc = $db60_coddoc;
      $cldb_docparagpadrao->db62_codparag = $campos[$i];
      $cldb_docparagpadrao->db62_ordem = $i+1;
      $cldb_docparagpadrao->incluir($db60_coddoc,$campos[$i]);
    }
  }


  
  db_fim_transacao();
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $result = $cldb_documentopadrao->sql_record($cldb_documentopadrao->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_trocacordeselect();" >
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
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmordparagpadrao.php");
	?>
    </center>
	</td>
  </tr>
</table>
<?
//db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){
	
if($cldb_documentopadrao->erro_status=="0"){
  //$cldb_documentopadrao->erro(true,false);
  db_msgbox($erro_msg);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($cldb_documentopadrao->erro_campo!=""){
    echo "<script> document.form1.".$cldb_documentopadrao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$cldb_documentopadrao->erro_campo.".focus();</script>";
  };
}else{
	echo "<script>
	parent.db_iframe_ordena.hide();
	parent.document.form1.submit();
</script>
";
}
  //$cldb_documentopadrao->erro(true,true);
};
?>
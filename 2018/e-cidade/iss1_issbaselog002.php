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
include("classes/db_issbaselog_classe.php");
include("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clissbaselog = new cl_issbaselog;
$db_opcao     = 22;
$db_botao     = false;
$lPesquisa    = false; 
$lErro        = false;

if (isset($alterar)) {
	
  db_inicio_transacao();
  
  $db_opcao = 2;
  $clissbaselog->alterar($q102_sequencial);
  if ($clissbaselog->erro_status == '0') {
  	$lErro = true;
  }
  
  db_fim_transacao($lErro);
  
} else if (isset($chavepesquisa)) {
	
   $result   = $clissbaselog->sql_record($clissbaselog->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   
   $db_opcao = 2;
   $db_botao = true;
}

if ($db_opcao == 22) {
  
  $db_opcao  = 2;
  $lPesquisa = true; 
}

if ($db_botao == false) {
  $db_opcao  = 3;
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
<body bgcolor="#CCCCCC" background="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
</table>
<table align="center" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td valign="top"> 
      <?
        include("forms/db_frmissbaselog.php");
      ?>
  </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if (isset($alterar)) {
  
  if ($clissbaselog->erro_status == "0") {
    
    $clissbaselog->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($clissbaselog->erro_campo != "") {
    	
      echo "<script> document.form1.".$clissbaselog->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clissbaselog->erro_campo.".focus();</script>";
    }
  } else { 
    $clissbaselog->erro(true,true);
  }
}

if ($lPesquisa == true) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","q102_inscr",true,1,"q102_inscr",true);
</script>
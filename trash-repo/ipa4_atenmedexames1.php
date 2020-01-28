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

//////////INCLUIR/////////////
if(isset($HTTP_POST_VARS["incluir"])) {
  db_postmemory($HTTP_POST_VARS);
  $result = pg_exec("select max(codexa) + 1 from exames");
  $codexa = pg_result($result,0,0);
  $codexa = $codexa==""?"1":$codexa;
  pg_exec("insert into exames values($codexa,".db_getsession("DB_id_usuario").",'$descr')") or die("Erro(24) inserindo em exames");
  db_redireciona();
  exit;		   
////////////////ALTERAR////////////////  
} else if(isset($HTTP_POST_VARS["alterar"])) {
  db_postmemory($HTTP_POST_VARS);
  pg_exec("update exames set descr = '$descr'
           where codexa = $codigo
		   and codmed = ".db_getsession("DB_id_usuario")) or die("Erro(22) atualizando exames");
  db_redireciona($HTTP_SERVER_VARS['PHP_SELF']);
  exit;		     
////////////////EXCLUIR//////////////
} else if(isset($HTTP_POST_VARS["excluir"])) {
  pg_exec("delete from exames where codexa = ".$HTTP_POST_VARS["codigo"]) or die("Erro(15) deletando tabela exames");
  db_redireciona($HTTP_SERVER_VARS['PHP_SELF']);
  exit;  
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_iniciar() {
  if(document.form1)
    document.form1.descr.focus();
}
function js_inserir() {
  var F = document.form1;
  F.descr.value = '';
  F.codigo.value = '';
  F.incluir.disabled = false;
  F.alterar.disabled = true;
  F.excluir.disabled = true;
  F.descr.focus();
}
function js_submeter() {
  if(document.form1.descr.value.length == 0) {
    alert("Campo descrição não pode ser vazio!");
	document.form1.descr.focus();
	return false;
  }
  return true;
}
</script>
<style type="text/css">
<!--
td {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}

input {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	height: 17px;
	border: 1px solid #999999;
}-->
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_iniciar()">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="middle" bgcolor="#CCCCCC"> 
	  <table border="0" cellspacing="0" cellpadding="0">
	  <tr>
	  <td>
         <form name="form1" method="post" onSubmit="return js_submeter()">
		 <input type="hidden" name="codigo">
              <table width="60%" border="0" cellspacing="0" cellpadding="5">
                <tr> 
                  <td><strong>Descri&ccedil;&atilde;o:</strong></td>
                  <td><input type="text" name="descr" size="30" maxlength="300"></td>
                </tr>
                <tr> 
                  <td colspan="2" nowrap> 
				    <input type="submit" name="incluir" value="Incluir"> <input type="submit" name="alterar" value="Alterar" disabled> 
                    <input type="submit" onClick="return confirm('Quer realmente excluir este registro?')" name="excluir" value="Excluir" disabled> 
                    <input type="button" name="procurar" onClick="consulta.js_procurar(document.form1.descr.value)" value="Procurar"> 
                    <input type="button" name="inserir" value="Limpar Campos" onClick="js_inserir()"> 
                  </td>
                </tr>
              </table>
            </form>			
	  </td>
	  </tr>
	  <tr>
      <td> 
	  <iframe name="consulta" src="ipa4_atenmedexames2.php" frameborder="0" scrolling="auto" width="400" height="300"></iframe>         
       </td>
	  </tr>
	  </table>
    </td>
  </tr>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));				   
?>
</body>
</html>
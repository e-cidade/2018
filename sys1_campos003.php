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
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
?>
<html>
<head>
<title>Procura de Campos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<!--script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script-->
<script>
function js_inserir() {
  var F = document.form1;
  var SI = F.voltacampo.selectedIndex;
  if(SI != -1) {
    parent.js_insSelect(F.voltacampo.options[SI].text,F.voltacampo.options[SI].value);
    F.voltacampo.options[SI] = null;
    if(SI <= (F.voltacampo.length - 1)) 
        F.voltacampo.options[SI].selected = true;  
    js_trocacordeselect();
    parent.db_iframe_pesquisa.hide();
  }
}
</script>
<style type="text/css">
<!--
td {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
input {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	height: 17px;
	border: 1px solid #999999;
}
-->
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_trocacordeselect()">
<center>
<form name="form1">
<table border="0" cellpadding="0" cellspacing="0">
<tr>
<td><strong>Selecione o Campo:</strong>
</td>
</tr>
<tr>
<td>
<?
$result = pg_exec("select codcam,nomecam from db_syscampo where nomecam like '$campo%'");
$numrows = pg_numrows($result);
if($numrows > 0) {
  echo "<select name=\"voltacampo\" size=\"15\" style=\"width:200px\">\n";
  for($i = 0;$i < $numrows;$i++) {
    echo "<option value=\"".pg_result($result,$i,"codcam")."\">".pg_result($result,$i,"nomecam")."</option>\n";
  }
  echo "</select>\n";
} else {
  echo "Campo não encontrado\n";
}
?>
</td>
</tr>
<tr>
<td height="30">
<input type="button" name="inserir" onClick="js_inserir()" value="Inserir">
<input type="button" name="fechar" onClick="parent.db_iframe_pesquisa.hide()" value="Fechar">
</td>
</tr>
</table>
</form>
</center>
</body>
</html>
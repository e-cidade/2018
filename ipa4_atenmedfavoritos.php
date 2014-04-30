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
include("dbforms/db_funcoes.php");

if(isset($HTTP_POST_VARS["incluir"])) {
  db_postmemory($HTTP_POST_VARS);
  $result = pg_exec("select max(codigo) + 1 from favoritos");
  $codigo = pg_result($result,0,0);
  $codigo = $codigo==""?"1":$codigo;
  pg_exec("insert into favoritos values($codigo,".db_getsession("DB_id_usuario").",'$descr')") or die("Erro(12) inserindo em favoritos");
}
if(isset($HTTP_POST_VARS["excluir"])) {
  pg_exec("delete from favoritos where codigo = ".$HTTP_POST_VARS["favoritos"]) or die("Erro(15) deletando tabela favoritos");
}
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
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
<script>
function js_inserir(texto) {
  var aonde = parent.aondeC;
  parent.document.form1.elements[aonde].value += texto + "\n";

//  parent.document.form1.elements[aonde].value = parent.document.form1.elements[aonde].value 
  for(var i = 0;i < 15;i++)
    parent.document.form1.elements[aonde].doScroll("scrollbarDown");
  parent.document.form1.elements[aonde].focus();	
}
function js_incluir() {
  if(document.form1.descr.value=='') { 
    alert('Informe algum favorito para inclusão');
	return false;
  }
  return true;
}
function js_excluir() {
  if(document.form1.favoritos.selectedIndex == -1) {
    alert('Selecione algum favorito para exclusão');
	return false;
  }
  return confirm('Quer realmente excluir este registro?');
}
</script>
</head>

<body bgcolor=#CCCCCC bgcolor="#FFFF64" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_trocacordeselect()">
	<form name="form1" method="post">
	
  <table border="0" cellpadding="3" cellspacing="0" style="	border: 1px solid #000000;">
    <tr> 
      <td align="left" valign="top"><strong>Favoritos:</strong><br> <select onDblClick="js_inserir(this.options[this.selectedIndex].text)" style="width:136px;font-size:9px;" name="favoritos" size="10" id="select">
          <?			 
			  $result = pg_exec("select codigo,descr from favoritos where codmed = ".db_getsession("DB_id_usuario")." order by upper(descr)");
			  $numrows = pg_numrows($result);
			  for($i = 0;$i < $numrows;$i++) {
			    db_fieldsmemory($result,$i);
			    echo "<option value=\"".$codigo."\">".trim($descr)."</option>\n";
			  }
		  ?>
        </select></td>
    </tr>
    <tr>
      <td align="left" valign="top"><small>nome:</small><br><input style="width:136px" name="descr" type="text" id="descr" maxlength="100"></td>
    </tr>
    <tr> 
      <td align="left" valign="top">
	    <input name="incluir" onClick="return js_incluir()" type="submit" id="incluir" value="Incluir"> 
        <input name="excluir" onClick="return js_excluir()" type="submit" id="excluir" value="Excluir"> 
      </td>
    </tr>
  </table>
      </form>
</body>
</html>
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
db_postmemory($HTTP_POST_VARS);
if(isset($txtexto)){
   if($txtexto!=""){
     $result = pg_exec("select max(idtx)+1 as max from db_carnesdados");
	 db_fieldsmemory($result,0);
     if(empty($max)) $max = 1;
     $result = pg_exec("insert into db_carnesdados values($max,'$txtexto')");
   }
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_gerar(obj) {
  window.open('con4_carnes004.php?codigo=' + obj.formulario.options[obj.formulario.selectedIndex].value);
}
function js_abririmagem(obj) {
  if(obj.options[obj.selectedIndex].value != "")
    parent.imagem.location.href = 'con4_carnes003.php?codigo=' + obj.options[obj.selectedIndex].value;
}
function js_inserircampo(obj,objtxt) {
  obj.nomecam.value = objtxt.options[objtxt.selectedIndex].text;  
}
function js_inserircampoa(obj,objtxt) {
  var x  = new String(objtxt.options[objtxt.selectedIndex].text);
  var xx = x.split("->");
  obj.nomecam.value = xx[0];  
//  obj.nomecam.value = objtxt.options[objtxt.selectedIndex].value;  
}

function js_cadastrar() {
  parent.imagem.form1.submit();
}
function js_novotexto() {
  if(document.form1.txtexto.value == ""){
    alert('Você deve preencher um texto.');
    document.form1.txtexto.focus();
	return false;
  }else{
	document.form1.submit();
	return true;
  }
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
tr {
	border: 1px solid #000000;
}
-->
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
	<form name="form1" method="post">
        
    <table width="77%" height="61" border="0" cellpadding="0" cellspacing="0">
      <tr> 
        <td width="21%" nowrap> 
		<select name="dados" size="1" onChange="js_inserircampo(this.form,this)">
            <option value="0">&nbsp;</option>
            <?
		  $result = pg_exec("select distinct c.nomecam ,c.tamanho
		                     from db_syscampo c 
							   inner join db_sysarqcamp a on a.codcam = c.codcam
							   inner join db_sysarquivo t on a.codarq = t.codarq
							 order by c.nomecam");
							 //where a.codarq = 204");
		  $numrows = pg_numrows($result);
		  for($i = 0;$i < $numrows;$i++){
		    $conteudo = pg_result($result,$i,0);
		    echo "<option value=\"".pg_result($result,$i,1)."\">".$conteudo."</option>\n";
		  }
		  ?>
          </select> &nbsp; </td>
        <td width="21%" nowrap><strong> Nome Formul&aacute;rio:&nbsp;&nbsp;</strong></td>
        <td width="20%"> <select name="formulario" id="formulario" onChange="js_abririmagem(this)">
            <option>&nbsp;</option>
            <?
			    $result = pg_exec("select codmodelo,nomemodelo from db_carnesimg");
				$numrows = pg_numrows($result);
				for($i = 0;$i < $numrows;$i++) {
				  echo "<option value=\"".pg_result($result,$i,"codmodelo")."\" ".(isset($HTTP_POST_VARS["formulario"])?($HTTP_POST_VARS["formulario"]==pg_result($result,$i,"codmodelo")?"selected":""):"").">".pg_result($result,$i,"nomemodelo")."</option>\n";
				}
			  ?>
          </select> </td>
        <td nowrap><strong>&nbsp;&nbsp;PosX:&nbsp;&nbsp;</strong></td>
        <td><input name="posx" type="text" id="posx" size="10" maxlength="15" readonly></td>
        <td><strong>&nbsp;</strong></td>
        <td>&nbsp;</td>
      </tr>
      <tr> 
        <td width="21%" nowrap>&nbsp;</td>
        <td nowrap><strong>Campo:&nbsp;</strong></td>
        <td><input name="nomecam" type="text" id="nomecam" size="20" maxlength="50" readonly></td>
        <td nowrap><strong>&nbsp;&nbsp;PoxY:</strong></td>
        <td><input name="posy" type="text" id="posy" size="10" maxlength="15" readonly></td>
        <td align="center"><input name="cadastrar" type="button" onClick="js_cadastrar()" id="cadastrar4" value="Atualizar"></td>
        <td><input name="gerar" type="button" id="gerar" value="Gerar" onClick="js_gerar(this.form)"></td>
      </tr>
      <tr> 
        <td colspan="7" nowrap><font color="#FF0000"> 
	      <input name="txtexto" type="text" id="txtexto" size="40" maxlength="100">
          <input name="novotexto" type="button" id="novotexto" value="Enviar" onclick="js_novotexto();">
		  
		  <select name="texto" size="1" onChange="js_inserircampoa(this.form,this)">
            <option value="0">&nbsp;</option>
            <?
		  $result = pg_exec("select idtx,txcampo
		                     from db_carnesdados 
							 order by txcampo");
							 //where a.codarq = 204");
		  $numrows = pg_numrows($result);
		  for($i = 0;$i < $numrows;$i++){
		    $conteudo = pg_result($result,$i,0).'->'.pg_result($result,$i,1);
		    $tamanho  = strlen(trim(pg_result($result,$i,1)));
		    echo "<option value=\"".$tamanho."\">".$conteudo."</option>\n";
		  }
		  ?>
          </select>
          Rotina desenvolvida pro Internet Explorer 6.0</font></td>
      </tr>
    </table>
        <br>
      </form> 
</center>	  
    </body>
</html>
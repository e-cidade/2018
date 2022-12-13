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

if(isset($HTTP_POST_VARS["incluir"])) {
  db_postmemory($HTTP_POST_VARS);
  $result = pg_exec("select max(m_codigo) from db_menupref");
  $codigo = pg_result($result,0,0) == ""?"1":((integer)pg_result($result,0,0) + 1);
  $ativo = @$ativo=="1"?"1":"0";
  $publico = @$publico=="f"?"f":"t";
  pg_exec("BEGIN");
  $result = pg_exec("INSERT INTO db_menupref VALUES($codigo,
                                          '$descricao',
										  '$arquivo',
										  '$imgs',
										  '$ativo',
										  '$publico')");
  if(pg_cmdtuples($result) > 0) {
    pg_exec("COMMIT");
	db_msgbox("Item Incluido");
  } else {
    pg_exec("ROLLBACK");
	echo "Erro incluindo item em db_menupref.<br><a href=\"\" onclick=\"history.back();return false\">Voltar</a>\n";
	exit;
  }
} else if(isset($HTTP_POST_VARS["atualizar"])) {
  pg_exec("BEGIN");
  if($HTTP_POST_VARS["codigo"] != "") {
      db_postmemory($HTTP_POST_VARS);
	if(isset($ativo)){
	  $ativo = "1";
	}else{
	  $ativo = "0";
	}
	if(isset($publico)){
	  $publico = "t";
	}else{
	  $publico = "f";
	}
    $result = pg_exec("UPDATE db_menupref SET
	                     m_descricao = '$descricao',
						 m_arquivo = '$arquivo',
						 m_imgs = '$imgs',
						 m_ativo = '$ativo',
						 m_publico = '$publico'
					   WHERE m_codigo = $codigo");
  }
  $tam_vetor = sizeof($HTTP_POST_VARS);
  reset($HTTP_POST_VARS);
  pg_exec("UPDATE db_menupref SET m_ativo = '0'");
  for($i = 0;$i < $tam_vetor;$i++) {
    if(db_indexOf(key($HTTP_POST_VARS),"cb") > 0) {
	  $ativo = $HTTP_POST_VARS[key($HTTP_POST_VARS)] != ""?"1":"0";
	  $cod = $HTTP_POST_VARS[key($HTTP_POST_VARS)];
	  pg_exec("UPDATE db_menupref SET m_ativo = '$ativo' WHERE m_codigo = $cod");
	}
    next($HTTP_POST_VARS);
  }  
  pg_exec("COMMIT");
} else if(isset($HTTP_POST_VARS["excluir"])) {
  pg_exec("DELETE FROM db_menupref WHERE m_codigo = ".$HTTP_POST_VARS["codigo"]);
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
    document.form1.descricao.focus();
}
function js_valores(cod) {
  var F = document.form1;
    F.incluir.disabled = true;
    F.descricao.value = document.getElementById(cod).firstChild.nodeValue;
    F.arquivo.value = document.getElementById(cod + 'Harq').value;
    F.imgs.value = document.getElementById(cod + 'Himgs').value;
    F.codigo.value = document.getElementById(cod + 'Hcod').value;
    if(document.getElementById(cod + 'Hat').value == 1)
	  F.ativo.checked = true;
	else
	  F.ativo.checked = false;
    if(document.getElementById(cod + 'Hpu').value == 't')
	  F.publico.checked = true;
	else
	  F.publico.checked = false;


}
function js_excluir() {
  if(document.form1.codigo.value == "") {
    alert('Selecione um registro primeiro');
	return false;
  }
  if(confirm("Excluir este registro?")==true)
    return true;
  else
    return false;
}
function js_incluir() {
  var F = document.form1;
  if(F.descricao.value == "" || F.arquivo.value == "" || F.imgs.value == "") {
    alert("Todos os campos tem que estar preenchidos");
	return false;
  } else
    return true;
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_iniciar()">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
	
	<center>
  <form name="form1" method="post">
    <table width="70%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr> 
        <td height="25"><strong>C&oacute;digo:</strong></td>
        <td height="25"><input name="codigo" type="text" id="codigo" size="6" readonly></td>
      </tr>
      <tr> 
        <td height="25"><strong>Descri&ccedil;&atilde;o:</strong></td>
        <td height="25"><input name="descricao" type="text" id="descricao" size="50"></td>
      </tr>
      <tr> 
        <td height="25"><strong>Arquivo:</strong></td>
        <td height="25"><input name="arquivo" type="text" id="arquivo" size="50" maxlength="100"></td>
      </tr>
      <tr> 
        <td height="25"><strong>Imagens:<br>
          </strong><font size="-2">(separadas por ponto e virgula)</font></td>
        <td height="25"><input name="imgs" type="text" id="imgs" size="50" maxlength="200"></td>
      </tr>
      <tr>
        <td height="25"><strong>Ativo:</strong></td>
        <td height="25"><input name="ativo" type="checkbox" id="ativo" value="1" </td>
      </tr>
      <tr>
        <td height="25"><strong>Público:</strong></td>
        <td height="25"><input name="publico" type="checkbox" id="publico" value="f"></td>
      </tr>
      <tr> 
        <td height="25">&nbsp;</td>
        <td height="25"><input name="incluir" type="submit" id="incluir" value="Incluir" onClick="return js_incluir()">
          <input name="atualizar" type="submit" id="atualizar" value="Atualizar">
          <input name="excluir" type="submit" id="excluir" value="Excluir" onClick="return js_excluir()"></td>
      </tr>
    </table>
  <table border="0" cellspacing="0" cellpadding="0">
  <?
  $result = pg_exec("SELECT m_codigo as codigo,m_descricao as descricao,m_arquivo as arquivo,m_imgs as imgs,m_ativo as ativo,m_publico as publico FROM db_menupref ORDER BY m_codigo");
  $numrows = pg_numrows($result);
  $cor = "#33CCFF";
  for($i = 0;$i < $numrows;$i++) {
    $cor = $cor=="#33CCFF"?"#33CCCC":"#33CCFF";
    $ativo = pg_result($result,$i,"ativo");
    echo "<tr bgcolor=\"$cor\">
	        <td><input type=\"checkbox\" name=\"cb".pg_result($result,$i,"codigo")."\" value=\"".pg_result($result,$i,"codigo")."\" ".($ativo=="1"?"checked":"")."></td>
			<td style=\"cursor: hand\" id=\"C".pg_result($result,$i,"codigo")."\" onclick=\"js_valores('C".pg_result($result,$i,"codigo")."')\">".pg_result($result,$i,"descricao")."</td>
			<td><input type=\"hidden\" id=\"C".pg_result($result,$i,"codigo")."Harq\" value=\"".pg_result($result,$i,"arquivo")."\"></td>
			<td><input type=\"hidden\" id=\"C".pg_result($result,$i,"codigo")."Himgs\" value=\"".pg_result($result,$i,"imgs")."\"></td>
			<td><input type=\"hidden\" id=\"C".pg_result($result,$i,"codigo")."Hcod\" value=\"".pg_result($result,$i,"codigo")."\"></td>
			<td><input type=\"hidden\" id=\"C".pg_result($result,$i,"codigo")."Hat\" value=\"".pg_result($result,$i,"ativo")."\"></td>			
			<td><input type=\"hidden\" id=\"C".pg_result($result,$i,"codigo")."Hpu\" value=\"".pg_result($result,$i,"publico")."\"></td>			
		  </tr>\n";
  }
  ?>
  </table>
  </form>
</center>
	
	<?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
	</td>
  </tr>
</table>
</body>
</html>
<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

if(isset($HTTP_POST_VARS["retornar"])){
  db_postmemory($HTTP_POST_VARS);
  db_redireciona("con4_orgitens002.php?mod=$modulos&modulos=$modulos");
  exit;
}
if(isset($HTTP_POST_VARS["campos"])){
  //
  db_postmemory($HTTP_POST_VARS);
  // atualiza ordem do menu
  //
  db_query("begin");
  for($i=0;$i<sizeof($campos);$i++){

     $sql = " update db_menu set menusequencia = ".($i+1)."
                where id_item = $coditem and modulo = $modulos
                  and id_item_filho = ".$campos[$i];

     $result = db_query($sql);

  }
  db_query("commit");

  /**
   * Limpa o cache dos menus
   */
  DBMenu::limpaCache('', '', $modulos);
}


?>


<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript">

function js_sobe() {
  var F = document.getElementById("campos");
  if(F.selectedIndex != -1 && F.selectedIndex > 0) {
    var SI = F.selectedIndex - 1;
    var auxText = F.options[SI].text;
	var auxValue = F.options[SI].value;
	F.options[SI] = new Option(F.options[SI + 1].text,F.options[SI + 1].value);
	F.options[SI + 1] = new Option(auxText,auxValue);
	js_trocacordeselect();
	F.options[SI].selected = true;
  }
}
function js_desce() {
  var F = document.getElementById("campos");
  if(F.selectedIndex != -1 && F.selectedIndex < (F.length - 1)) {
    var SI = F.selectedIndex + 1;
    var auxText = F.options[SI].text;
	var auxValue = F.options[SI].value;
	F.options[SI] = new Option(F.options[SI - 1].text,F.options[SI - 1].value);
	F.options[SI - 1] = new Option(auxText,auxValue);
	js_trocacordeselect();
	F.options[SI].selected = true;
  }
}

function js_selecionar() {
  var F = document.getElementById("campos").options;
  for(var i = 0;i < F.length;i++) {
    F[i].selected = true;
  }
  return true;
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_trocacordeselect()" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
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
     <form name="form1" method="post">
       <?
       $sql = "select m.descricao as dmenu, o.nome_modulo
               from db_menu d
	            inner join db_itensmenu m on m.id_item = d.id_item
	            inner join db_modulos o on d.modulo = o.id_item
	       where d.id_item = $coditem and
	             d.modulo = $modulos and
                     m.libcliente is true
	       order by d.menusequencia";
       $result = db_query($sql);
       if($result!=false && pg_numrows($result)>0){
         db_fieldsmemory($result,0);
         //
         $sql = "select m.id_item,m.descricao
               from db_menu d
	            inner join db_itensmenu m on m.id_item = d.id_item_filho
	       where d.id_item = $coditem and
	             d.modulo = $modulos and
		     m.itemativo = '$ambiente'
	       order by d.menusequencia";
         $result = db_query($sql);
       }
       if($result==false || pg_numrows($result)==0){
         ?>
         <table>
	 <tr>
	 <td align='center'>
	  <input name="coditem" type="hidden" value="<?=$coditem?>">
	  <input name="modulos" type="hidden" value="<?=$modulos?>">
	  <font size="4px">Item selecionado não contém menu.</font>
	  <input name="ambiente" type="hidden" value="<?=$ambiente?>">
	 <br>
	 <br>
	 </td></tr>
	 <tr><td align='center'>
         <input name="retornar" type="submit" value="Retornar">
	 </td>
	 </tr>
	 </table>
	 <?
       }else{
         ?>
	 <table>
	 <tr>
	 <td colspan="2"><font size="4"><b>Módulo: <?=$modulos?>-<?=$nome_modulo?></b></font>
	 <br><font size="4"><b> Ítem Menu : <?=$coditem?>-<?=$dmenu?></b></font> </td>
	 	 </tr>
	 <tr>
	 <td>
         <select name="campos[]" id="campos" size="17" style="width:250px" multiple >
         <?
         for($i = 0;$i < pg_numrows($result);$i++) {
	    echo "<option value=\"".pg_result($result,$i,"id_item")."\">".pg_result($result,$i,"descricao")."</option>\n";
	 }
         ?>
         </select>
	 </td>
	 <td>
	 <img style="cursor:hand" onClick="js_sobe();return false;" src="skins/img.php?file=Controles/seta_up.png" />
    <br/><br/>
   <img style="cursor:hand" onClick="js_desce()" src="skins/img.php?file=Controles/seta_down.png" />
    <br/><br/>
	  <input name="coditem" type="hidden" value="<?=$coditem?>">
	  <input name="modulos" type="hidden" value="<?=$modulos?>">
	  <input name="ambiente" type="hidden" value="<?=$ambiente?>">
	  <input name="atualizar" onClick="js_selecionar();" accesskey="a" type="submit" value="Atualizar">
	 <br>
	 <br>
         <input name="retornar" type="submit" value="Retornar">
	 </td>
	 </tr>
	 </table>
       <?
       }
       ?>

    </form>
  </center>
  </td></tr>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

</body>
</html>
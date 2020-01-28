<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

//MODULO: educação
include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_parecer_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clparecer = new cl_parecer;
if(isset($atualizar)){
 $tam = sizeof($pareceres);
 for($i=0;$i<$tam;$i++){
  $sql = "UPDATE parecer SET
           ed92_i_sequencial = $numparecer[$i]
          WHERE ed92_i_codigo = $pareceres[$i]
         ";
  $query = db_query($sql);
 }
 if($opcaoatual==1){
  $location = "edu1_parecer001.php";
 }elseif($opcaoatual==2){
  $location = "edu1_parecer002.php?chavepesquisa=$codigoparec";
 }elseif($opcaoatual==3){
  $location = "edu1_parecer003.php?chavepesquisa=$codigoparec";
 }elseif($opcaoatual==22){
  $location = "edu1_parecer002.php";
 }elseif($opcaoatual==33){
  $location = "edu1_parecer003.php";
 }
 ?>
 <script>
  alert("Pareceres ordenados com sucesso!");
  parent.location.href = "<?=$location?>";
  parent.db_iframe_ordenacao.hide();
 </script>
 <?
 exit;
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form2" method="post" action="">
<table border="0" cellspacing="3" bgcolor="#CCCCCC" align="center">
 <tr>
  <td valign="top" align="center">
   <b>Nova Seq.</b>
  </td>
  <td valign="top" align="center" colspan="2">
   <b>Seq. Atual - Parecer</b>
  </td>
 </tr>
 <tr>
  <td align="right" valign="top" >
   <?
   $result = $clparecer->sql_record($clparecer->sql_query("","*","ed92_i_sequencial"," ed92_i_escola = ".db_getsession("DB_coddepto").""));
   ?>
   <select name="numparecer[]" id="numparecer" size="15" style="font-size:9px;width:50px;" multiple>
    <?
    for($x=0;$x<$clparecer->numrows;$x++){
     db_fieldsmemory($result,$x);
     echo "<option value='".($x+1)."'>".($x+1)."</option>";
    }
    ?>
   </select>
  </td>
  <td align="right" valign="top">
   <select name="pareceres[]" id="pareceres" size="15" style="font-size:9px;width:600px;" multiple>
   <?
   for($x=0;$x<$clparecer->numrows;$x++){
    db_fieldsmemory($result,$x);
    echo "<option value='$ed92_i_codigo'>".($ed92_i_sequencial==""?"S/N":$ed92_i_sequencial)." - $ed92_c_descr</option>";
   }
   ?>
   </select>
  </td>
  <td valign="top">
   <img style="cursor:hand" onClick="js_sobe();return false;" src="skins/img.php?file=Controles/seta_up.png" />
   <br/>
   <img style="cursor:hand" onClick="js_desce()" src="skins/img.php?file=Controles/seta_down.png" />
   <br>
  </td>
 </tr>
 <tr>
  <td align="center" valign="top" colspan="3">
   <br>
   <input type="submit" name="atualizar" value="Confirmar" onClick="js_selecionar();">
   <input type="button" value="Cancelar" onClick="js_fechar();">
   <input type="hidden" value="<?=$opcaoatual?>" name="opcaoatual">
   <input type="hidden" value="<?=$codigoparec?>" name="codigoparec">
  </td>
 </tr>
 <tr>
  <td align="center" valign="top">
  </td>
 </tr>
</table>
</form>
</body>
</html>
<script>
function js_sobe() {
 var F = document.getElementById("pareceres");
 if(F.selectedIndex != -1 && F.selectedIndex > 0) {
  var SI = F.selectedIndex - 1;
  var auxText = F.options[SI].text;
  var auxValue = F.options[SI].value;
  F.options[SI] = new Option(F.options[SI + 1].text,F.options[SI + 1].value);
  F.options[SI + 1] = new Option(auxText,auxValue);
  F.options[SI].selected = true;
 }
}
function js_desce() {
 var F = document.getElementById("pareceres");
 if(F.selectedIndex != -1 && F.selectedIndex < (F.length - 1)) {
  var SI = F.selectedIndex + 1;
  var auxText = F.options[SI].text;
  var auxValue = F.options[SI].value;
  F.options[SI] = new Option(F.options[SI - 1].text,F.options[SI - 1].value);
  F.options[SI - 1] = new Option(auxText,auxValue);
  F.options[SI].selected = true;
 }
}
function js_selecionar() {
 var F = document.getElementById("pareceres").options;
 for(var i = 0;i < F.length;i++) {
   F[i].selected = true;
 }
 var F = document.getElementById("numparecer").options;
 for(var i = 0;i < F.length;i++) {
   F[i].selected = true;
 }
 return true;
}
function js_fechar(){
 parent.db_iframe_ordenacao.hide();
}
</script>
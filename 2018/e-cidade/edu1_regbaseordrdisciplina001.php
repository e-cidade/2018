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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_regencia_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clregencia = new cl_regencia;
$turma=  $turma;
if(isset($ordenacao)){
 $tam = sizeof($ordenar);
 for($i=0;$i<$tam;$i++){
  $sql = "UPDATE regencia SET
           ed59_i_ordenacao = ".($i+1)."
          WHERE ed59_i_codigo = $ordenar[$i]
         ";
  $query = db_query($sql);
 }
 ?>
 <script>
  parent.db_iframe_ordenar.hide();
  //parent.js_refresh();
  parent.window.location.reload();
 </script>
<?}?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name="form1" method="post" action="">
<table width="100%" border="0" cellspacing="2" cellpading="0">
 <tr>
  <td align="center">
   <table border="0" cellspacing="0" cellpading="0">
    <tr>
     <td align="center">
      <?$result3 = $clregencia->sql_record($clregencia->sql_query("","ed59_i_codigo,ed59_i_ordenacao,ed232_c_descr","ed59_i_ordenacao"," ed59_i_turma = $turma AND ed59_i_serie = $serie"));?>
      <table width="100%" cellspacing="0" cellpading="0" border="0" >
       <tr>
        <td rowspan="0">
         <select multiple="true" name="ordenar[]" id="ordenar" size="10" style="font-size:9px;width:300px"  onclick="js_selectum('ordenar')">
         <?
         if($linhabranco=="yes"){
          echo "<option value=''></option>";
         }
         for($i=0;$i<$clregencia->numrows;$i++){
          db_fieldsmemory($result3,0);
          $dados1 = pg_fetch_array($result3);
          echo "<option value=\"".$dados1["ed59_i_codigo"]."\">".trim($dados1["ed232_c_descr"])."</option>\n";
         }
         ?>
         </select>
        </td>
       </tr>
      </table>
     </td>
     <td>
      <img style="cursor:hand" onClick="js_sobe();return false;" src="skins/img.php?file=Controles/seta_up.png" />
      <br/><br/>
      <img style="cursor:hand" onClick="js_desce()" src="skins/img.php?file=Controles/seta_down.png" />
     </td>
    </tr>
    <tr>
     <td>
      <input name="ordenacao" type="submit" value="Ordenar" onclick="js_selecionar()">
     </td>
    </tr>
   </table>
  </td>
 </tr>
</table>
</form>
</body>
</html>
<script>
function js_sobe(){
 var F = document.getElementById("ordenar");
 if(F.selectedIndex != -1 && F.selectedIndex > 0 ) {
  var SI = F.selectedIndex - 1;
  var auxText = F.options[SI].text;
  var auxValue = F.options[SI].value;
  F.options[SI] = new Option(F.options[SI + 1].text,F.options[SI + 1].value);
  F.options[SI + 1] = new Option(auxText,auxValue);
  F.options[SI].selected = true;
 }
}
function js_desce(){
 var F = document.getElementById("ordenar");
 if(F.selectedIndex != -1 && F.selectedIndex < (F.length - 1) ) {
  var SI = F.selectedIndex + 1;
  var auxText = F.options[SI].text;
  var auxValue = F.options[SI].value;
  F.options[SI] = new Option(F.options[SI - 1].text,F.options[SI - 1].value);
  F.options[SI - 1] = new Option(auxText,auxValue);
  F.options[SI].selected = true;
 }
}
function js_selecionar(){
 var F = document.getElementById("ordenar").options;
 for(var i = 0;i < F.length;i++) {
   F[i].selected = true;
 }
 return true;
}
function js_selectum(nome){
 var F = document.getElementById(nome);
 for(var i = 0;i < F.options.length;i++){
  if(F.selectedIndex == i){
   F.options[i].selected = true;
  }else{
   F.options[i].selected = false;
  }
 }
}
</script>
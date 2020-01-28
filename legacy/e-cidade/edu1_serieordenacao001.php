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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_serieregimemat_classe.php");
include("classes/db_ensino_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clserieregimemat = new cl_serieregimemat;
$clensino = new cl_ensino;
$db_opcao = 1;
$db_botao = true;
if(isset($salvar)){

}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<form name="form1" method="post" action="">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Ordenação de Etapas</b></legend>
    <table width="100%">
     <tr>
      <td width="15%">
       <b>Nível de Ensino:</b>
      </td>
      <td>
       <?
       $result = $clensino->sql_record($clensino->sql_query("","ed10_i_codigo,ed10_c_descr","ed10_c_abrev",""));
       ?>
       <select name="ensino" id="ensino" onchange="js_ensino(this.value);" style="width:500px;">
        <option value=""></option>
        <?for($t=0;$t<$clensino->numrows;$t++){
          db_fieldsmemory($result,$t);
          ?>
          <option value="<?=$ed10_i_codigo?>"><?=$ed10_c_descr?></option>
        <?}?>
       </select>
      </td>
     </tr>
     <tbody id="div_regime"></tbody>
     <tbody id="div_divisao"></tbody>
     <tbody id="div_serie"></tbody>
    </table>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</form>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
function js_ensino(codensino){
 $('div_regime').innerHTML = "";
 $('div_divisao').innerHTML = "";
 $('div_serie').innerHTML = "";
 if(codensino!=""){
  js_divCarregando("Aguarde, buscando registro(s)","msgBox");
  var sAction = 'PesquisaRegime';
  var url     = 'edu1_serieordenacaoRPC.php';
  parametros  = 'sAction='+sAction+'&ensino='+codensino;
  var oAjax = new Ajax.Request(url,{method    : 'post',
                                    parameters: parametros,
                                    onComplete: js_retornaPesquisaRegime
                                   });
 }
}
function js_retornaPesquisaRegime(oAjax){
 js_removeObj("msgBox");
 var oRetorno = eval("("+oAjax.responseText+")");
 sHtml = '<tr>';
 sHtml += ' <td valign="top"><b>Regime de Matrícula:</b>';
 sHtml += ' </td>';
 sHtml += ' <td>';
 sHtml += '  <select name="regime" id="regime" style="width:500px;" onchange="js_regime(this.value)">';
 if(oRetorno.length==0){
  sHtml += '  <option value="">Nenhuma etapa vinculada ao regime de matrícula no ensino selecionado.</option>';
 }else{
  sHtml += '  <option value=""></option>';
  for (var i = 0;i < oRetorno.length; i++) {
   with (oRetorno[i]) {
    sHtml += '  <option value="'+ed218_i_codigo+'#'+ed218_c_divisao.urlDecode()+'">'+ed218_c_nome.urlDecode()+'</option>';
   }
  }
 }
 sHtml += '  </select>';
 sHtml += ' </td>';
 sHtml += '</tr>';
 $('div_regime').innerHTML = sHtml;
}
function js_regime(codregime){
 $('div_divisao').innerHTML = "";
 $('div_serie').innerHTML = "";
 if(codregime!=""){
  js_divCarregando("Aguarde, buscando registro(s)","msgBox");
  var url     = 'edu1_serieordenacaoRPC.php';
  arr_codregime = codregime.split("#");
  if(arr_codregime[1]=="S"){
   var sAction = 'PesquisaDivisao';
   parametros  = 'sAction='+sAction+'&regime='+arr_codregime[0]+'&ensino='+$('ensino').value;
   var oAjax = new Ajax.Request(url,{method    : 'post',
                                     parameters: parametros,
                                     onComplete: js_retornaPesquisaDivisao
                                    });
  }else{
   var sAction = 'PesquisaSerie';
   parametros  = 'sAction='+sAction+'&regime='+arr_codregime[0]+'&ensino='+$('ensino').value;
   var oAjax = new Ajax.Request(url,{method    : 'post',
                                     parameters: parametros,
                                     onComplete: js_retornaPesquisaSerie
                                    });
  }
 }
}
function js_retornaPesquisaDivisao(oAjax){
 js_removeObj("msgBox");
 var oRetorno = eval("("+oAjax.responseText+")");
 sHtml = '<tr>';
 sHtml += ' <td colspan="2" valign="top">';
 sHtml += '  <table border="0"><tr><td>';
 sHtml += '   <b>Ordenaçao Geral de Etapas pelo Ensino:</b><br>';
 sHtml += '   <table border="0"><tr><td>';
 sHtml += '    <select name="etapaensino" id="etapaensino" size="15" multiple style="width:300px;">';
 if(oRetorno[0].length==0){
  sHtml += '    <option value="">Nenhuma etapa vinculada ao regime de matrícula no ensino selecionado.</option>';
 }else{
  for (var i = 0;i < oRetorno[0].length; i++) {
   with (oRetorno[0][i]) {
    sHtml += '  <option value="'+ed11_i_codigo+'">'+ed11_c_descr.urlDecode()+'</option>';
   }
  }
 }
 sHtml += '    </select>';
 sHtml += '   </td><td valign="top">';
 sHtml += '    <br/>';
 sHtml += '    <img style="cursor:hand" onClick="js_sobe(1);return false;" src="skins/img.php?file=Controles/seta_up.png" />';
 sHtml += '    <br/><br/>';
 sHtml += '    <img style="cursor:hand" onClick="js_desce(1)" src="skins/img.php?file=Controles/seta_down.png" />';
 sHtml += '    <br/><br/>';
 sHtml += '    <input name="atualizar1" type="button" value="Atualizar" onclick="js_selecionar(1)" '+(oRetorno[0].length==0?"disabled":"")+'>';
 sHtml += '   </td></tr></table>';
 sHtml += '  </td>';
 sHtml += '  <td>';
 sHtml += '   <b>Ordenaçao de Etapas pelo Regime de Matricula:</b><br>';
 sHtml += '   <table border="0"><tr><td>';
 sHtml += '    <select name="etapa" id="etapa" size="15" multiple style="width:300px;">';
 if(oRetorno[1].length==0){
  sHtml += '    <option value="">Nenhuma etapa vinculada ao regime de matrícula no ensino selecionado.</option>';
 }else{
  for (var i = 0;i < oRetorno[1].length; i++) {
   with (oRetorno[1][i]) {
    sHtml += '  <option value="'+ed223_i_codigo+'">'+ed219_c_nome.urlDecode()+' - '+ed11_c_descr.urlDecode()+'</option>';
   }
  }
 }
 sHtml += '    </select>';
 sHtml += '   </td><td valign="top">';
 sHtml += '    <br>';
 sHtml += '    <img style="cursor:hand" onClick="js_sobe(2);return false" src="skins/img.php?file=Controles/seta_up.png" width="20" height="20" border="0">';
 sHtml += '    <br><br>';
 sHtml += '    <img style="cursor:hand" onClick="js_desce(2)" src="skins/img.php?file=Controles/seta_down.png" width="20" height="20" border="0">';
 sHtml += '    <br><br>';
 sHtml += '    <input name="atualizar2" type="button" value="Atualizar" onclick="js_selecionar(2)" '+(oRetorno[1].length==0?"disabled":"")+'>';
 sHtml += '   </td></tr></table>';
 sHtml += '  </td></tr></table>';
 sHtml += ' </td>';
 sHtml += '</tr>';
 $('div_divisao').innerHTML = sHtml;
}
function js_retornaPesquisaSerie(oAjax){
 js_removeObj("msgBox");
 var oRetorno = eval("("+oAjax.responseText+")");
 sHtml = '<tr>';
 sHtml += ' <td colspan="2" valign="top">';
 sHtml += '  <table border="0"><tr><td>';
 sHtml += '   <b>Ordenaçao de Etapas pelo Ensino:</b><br>';
 sHtml += '   <table border="0"><tr><td>';
 sHtml += '    <select name="etapaensino" id="etapaensino" size="15" multiple style="width:300px;">';
 if(oRetorno[0].length==0){
  sHtml += '    <option value="">Nenhuma etapa vinculada ao regime de matrícula no ensino selecionado.</option>';
 }else{
  for (var i = 0;i < oRetorno[0].length; i++) {
   with (oRetorno[0][i]) {
    sHtml += '  <option value="'+ed11_i_codigo+'">'+ed11_c_descr.urlDecode()+'</option>';
   }
  }
 }
 sHtml += '    </select>';
 sHtml += '   </td><td valign="top">';
 sHtml += '    <br>';
 sHtml += '    <img style="cursor:hand" onClick="js_sobe(1);return false" src="skins/img.php?file=Controles/seta_up.png" width="20" height="20" border="0">';
 sHtml += '    <br><br>';
 sHtml += '    <img style="cursor:hand" onClick="js_desce(1)" src="skins/img.php?file=Controles/seta_down.png" width="20" height="20" border="0">';
 sHtml += '    <br><br>';
 sHtml += '    <input name="atualizar1" type="button" value="Atualizar" onclick="js_selecionar(1)" '+(oRetorno[0].length==0?"disabled":"")+'>';
 sHtml += '   </td></tr></table>';
 sHtml += '  </td>';
 sHtml += '  <td>';
 sHtml += '   <b>Ordenaçao de Etapas pelo Regime de Matricula:</b><br>';
 sHtml += '   <table border="0"><tr><td>';
 sHtml += '    <select name="etapa" id="etapa" size="15" multiple style="width:300px;">';
 if(oRetorno[1].length==0){
  sHtml += '    <option value="">Nenhuma etapa vinculada ao regime de matrícula no ensino selecionado.</option>';
 }else{
  for (var i = 0;i < oRetorno[1].length; i++) {
   with (oRetorno[1][i]) {
    sHtml += '  <option value="'+ed223_i_codigo+'">'+ed11_c_descr.urlDecode()+'</option>';
   }
  }
 }
 sHtml += '    </select>';
 sHtml += '   </td><td valign="top">';
 sHtml += '    <br>';
 sHtml += '    <img style="cursor:hand" onClick="js_sobe(2);return false" src="skins/img.php?file=Controles/seta_up.png" width="20" height="20" border="0">';
 sHtml += '    <br><br>';
 sHtml += '    <img style="cursor:hand" onClick="js_desce(2)" src="skins/img.php?file=Controles/seta_down.png" width="20" height="20" border="0">';
 sHtml += '    <br><br>';
 sHtml += '    <input name="atualizar2" type="button" value="Atualizar" onclick="js_selecionar(2)" '+(oRetorno[1].length==0?"disabled":"")+'>';
 sHtml += '   </td></tr></table>';
 sHtml += '  </td></tr></table>';
 sHtml += ' </td>';
 sHtml += '</tr>';
 $('div_serie').innerHTML = sHtml;
}
function js_sobe(tipo) {
 var F = document.getElementById((tipo==1?"etapaensino":"etapa"));
 if(F.selectedIndex != -1 && F.selectedIndex > 0) {
  var SI = F.selectedIndex - 1;
  var auxText = F.options[SI].text;
  var auxValue = F.options[SI].value;
  F.options[SI] = new Option(F.options[SI + 1].text,F.options[SI + 1].value);
  F.options[SI + 1] = new Option(auxText,auxValue);
  F.options[SI].selected = true;
 }
}
function js_desce(tipo) {
 var F = document.getElementById((tipo==1?"etapaensino":"etapa"));
 if(F.selectedIndex != -1 && F.selectedIndex < (F.length - 1)) {
  var SI = F.selectedIndex + 1;
  var auxText = F.options[SI].text;
  var auxValue = F.options[SI].value;
  F.options[SI] = new Option(F.options[SI - 1].text,F.options[SI - 1].value);
  F.options[SI - 1] = new Option(auxText,auxValue);
  F.options[SI].selected = true;
 }
}
function js_selecionar(tipo){
 if(tipo==1){
  var F = document.getElementById("etapaensino").options;
  registros = "";
  sep = "";
  for(var i = 0;i < F.length;i++) {
   F[i].selected = true;
   registros += sep+F[i].value;
   sep = ",";
  }
  if(registros!=""){
   js_divCarregando("Aguarde, atualizando registro(s)","msgBox");
   var sAction = 'UpdateSerie';
   var url     = 'edu1_serieordenacaoRPC.php';
   parametros  = 'sAction='+sAction+'&registros='+registros;
   var oAjax = new Ajax.Request(url,{method    : 'post',
                                     parameters: parametros,
                                     onComplete: js_retornaUpdate
                                    });
  }
 }else{
  var F = document.getElementById("etapa").options;
  registros = "";
  sep = "";
  for(var i = 0;i < F.length;i++) {
   F[i].selected = true;
   registros += sep+F[i].value;
   sep = ",";
  }
  if(registros!=""){
   js_divCarregando("Aguarde, atualizando registro(s)","msgBox");
   var sAction = 'UpdateSerieRegime';
   var url     = 'edu1_serieordenacaoRPC.php';
   parametros  = 'sAction='+sAction+'&registros='+registros;
   var oAjax = new Ajax.Request(url,{method    : 'post',
                                     parameters: parametros,
                                     onComplete: js_retornaUpdate
                                    });
  }
 }
}
function js_retornaUpdate(oAjax){
 js_removeObj("msgBox");
 var oRetorno = eval("("+oAjax.responseText+")");
 alert(oRetorno.urlDecode());
 js_regime($('regime').value);
}
</script>
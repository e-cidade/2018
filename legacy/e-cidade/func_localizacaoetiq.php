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

//MODULO: educaÁ„o
include(modification("libs/db_stdlibwebseller.php"));
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_localizacao_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cllocalizacao = new cl_localizacao;
$depto = db_getsession("DB_coddepto");
$sql = "SELECT bi17_codigo FROM biblioteca WHERE bi17_coddepto = $depto";
$result = db_query($sql);
$linhas = pg_num_rows($result);
if($linhas>0){
 db_fieldsmemory($result,0);
}
if($jatem!=""){
 $explode = explode(",",$jatem);
 $codigos_not = "";
 $sep = "";
 for($c=0;$c<count($explode);$c++){
  $exp_registro = explode("|",$explode[$c]);
  $codigos_not .= $sep.$exp_registro[0];
  $sep = ",";
 }
 $where = " AND bi09_codigo not in ($codigos_not) ";
}else{
 $where = "";
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1" method="post" action="">
<table border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
 <tr>
  <td>
   <?
   $result_loc = $cllocalizacao->sql_record($cllocalizacao->sql_query_file("","bi09_codigo,bi09_nome","bi09_nome"," bi09_biblioteca = $bi17_codigo $where"));
   ?>
   <b>LocalizaÁıes:</b><br>
   <select name="localizacoes" id="localizacoes" size="10" onclick="js_desabinc()" ondblclick="js_localizacoes()" style="font-size:9px;width:450px;height:400px" multiple>
    <?
    if($cllocalizacao->numrows>0){
     for($i=0;$i<$cllocalizacao->numrows;$i++) {
      db_fieldsmemory($result_loc,$i);
      echo "<option value='$bi09_codigo'>$bi09_nome</option>\n";
     }
    }
    ?>
   </select>
  </td>
  <td align="center">
   <br>
   <table border="0">
    <tr>
     <td>
      <input name="incluirum" title="Incluir" type="button" value=">" onclick="js_localizacoes();" style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:15px;font-weight:bold;width:30px;height:20px;" disabled>
     </td>
    </tr>
    <tr><td height="1"></td></tr>
    <tr>
     <td>
      <input name="incluirtodos" title="Incluir Todos" type="button" value=">>" onclick="js_incluirtodos();" style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:15px;font-weight:bold;width:30px;height:20px;">
     </td>
    </tr>
    <tr><td height="8"></td></tr>
    <tr>
     <td>
      <hr>
     </td>
    </tr>
    <tr><td height="8"></td></tr>
    <tr>
     <td>
      <input name="excluirum" title="Excluir" type="button" value="<" onclick="js_excluir();" style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:15px;font-weight:bold;width:30px;height:20px;" disabled>
     </td>
    </tr>
    <tr><td height="1"></td></tr>
    <tr>
     <td>
      <input name="excluirtodos" title="Excluir Todos" type="button" value="<<" onclick="js_excluirtodos();" style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:15px;font-weight:bold;width:30px;height:20px;" disabled>
     </td>
    </tr>
   </table>
  </td>
  <td>
   <b>LocalizaÁıes para efetuar a busca:</b><br>
   <select name="localizacoesbusca" id="localizacoesbusca" size="10" onclick="js_desabexc()" style="font-size:9px;width:350px;height:400px" multiple>
    <?if($jatem!=""){
     $explode = explode(",",$jatem);
     for($c=0;$c<count($explode);$c++){
      $exp_registro = explode("|",$explode[$c]);
      ?>
      <option value="<?=$exp_registro[0]?>"><?=$exp_registro[1]?></option>
      <?
     }
    }?>
   </select>
  </td>
 </tr>
</table>
<center>
 <br>
 <input name="enviar" id="enviar" type="button" value="Enviar" onclick="js_enviar();" disabled>
</center>
</form>
</body>
</html>
<script>
function TiraAcento(string){
 acentos = '¡…Õ”⁄¿¬ ‘‹œ÷—√’ƒ\'';
 letras  = 'AEIOUAAEOUIONAOA ';
 new_string = '';
 for(r=0; r<string.length; r++){
  let = string.substr(r,1);
  for(d=0; d<acentos.length; d++){
   if(let==acentos.substr(d,1)){
    let=letras.substr(d,1);
    break;
   }
  }
  new_string = new_string+let;
 }
 return new_string;
}
function js_OrdenarLista(combo) {
 var lb = document.getElementById(combo);
 arrTexts = new Array();
 for(i=0; i<lb.length; i++){
  texto = TiraAcento(lb.options[i].text);
  arrTexts[i] = texto+"#"+lb.options[i].value;
 }
 arrTexts.sort();
 for(i=0; i<lb.length; i++){
  ArrayExplode  = arrTexts[i].split("#");
  lb.options[i].text = ArrayExplode[0];
  lb.options[i].value = ArrayExplode[1];
 }
}
function js_localizacoes() {
 var Tam = document.form1.localizacoes.length;
 var F = document.form1;
 for(x=0;x<Tam;x++){
  if(F.localizacoes.options[x].selected==true){
   F.elements['localizacoesbusca'].options[F.elements['localizacoesbusca'].options.length] = new Option(F.localizacoes.options[x].text,F.localizacoes.options[x].value)
   F.localizacoes.options[x] = null;
   Tam--;
   x--;
  }
 }
 if(document.form1.localizacoes.length>0){
  document.form1.localizacoes.options[0].selected = true;
 }else{
  document.form1.incluirum.disabled = true;
  document.form1.incluirtodos.disabled = true;
 }
 document.form1.enviar.disabled = false;
 document.form1.excluirtodos.disabled = false;
 js_OrdenarLista("localizacoes");
 js_OrdenarLista("localizacoesbusca");
 document.form1.localizacoes.focus();
}
function js_incluirtodos() {
 var Tam = document.form1.localizacoes.length;
 var F = document.form1;
 for(i=0;i<Tam;i++){
  F.elements['localizacoesbusca'].options[F.elements['localizacoesbusca'].options.length] = new Option(F.localizacoes.options[0].text,F.localizacoes.options[0].value);
  F.localizacoes.options[0] = null;
 }
 document.form1.incluirum.disabled = true;
 document.form1.incluirtodos.disabled = true;
 document.form1.excluirtodos.disabled = false;
 if(document.form1.localizacoesbusca.length>0){
  document.form1.enviar.disabled = false;
 }
 js_OrdenarLista("localizacoes");
 js_OrdenarLista("localizacoesbusca");
 document.form1.localizacoesbusca.focus();
}
function js_excluir() {
 var F = document.getElementById("localizacoesbusca");
 Tam = F.length;
 for(x=0;x<Tam;x++){
  if(F.options[x].selected==true){
   document.form1.localizacoes.options[document.form1.localizacoes.length] = new Option(F.options[x].text,F.options[x].value);
   F.options[x] = null;
   Tam--;
   x--;
  }
 }
 if(document.form1.localizacoesbusca.length>0){
  document.form1.localizacoesbusca.options[0].selected = true;
 }
 if(F.length == 0){
  document.form1.enviar.disabled = true;
  document.form1.excluirum.disabled = true;
  document.form1.excluirtodos.disabled = true;
 }
 document.form1.incluirtodos.disabled = false;
 js_OrdenarLista("localizacoes");
 js_OrdenarLista("localizacoesbusca");
 document.form1.localizacoesbusca.focus();
}
function js_excluirtodos() {
 var Tam = document.form1.localizacoesbusca.length;
 var F = document.getElementById("localizacoesbusca");
 for(i=0;i<Tam;i++){
  document.form1.localizacoes.options[document.form1.localizacoes.length] = new Option(F.options[0].text,F.options[0].value);
  F.options[0] = null;
 }
 if(F.length == 0){
  document.form1.enviar.disabled = true;
  document.form1.excluirum.disabled = true;
  document.form1.excluirtodos.disabled = true;
  document.form1.incluirtodos.disabled = false;
 }
 js_OrdenarLista("localizacoes");
 js_OrdenarLista("localizacoesbusca");
 document.form1.localizacoes.focus();
}
function js_desabinc(){
 for(i=0;i<document.form1.localizacoes.length;i++){
  if(document.form1.localizacoes.length>0 && document.form1.localizacoes.options[i].selected){
   if(document.form1.localizacoesbusca.length>0){
    document.form1.localizacoesbusca.options[0].selected = false;
   }
   document.form1.incluirum.disabled = false;
   document.form1.excluirum.disabled = true;
  }
 }
}
function js_desabexc(){
 for(i=0;i<document.form1.localizacoesbusca.length;i++){
  if(document.form1.localizacoesbusca.length>0 && document.form1.localizacoesbusca.options[i].selected){
   if(document.form1.localizacoes.length>0){
    document.form1.localizacoes.options[0].selected = false;
   }
   document.form1.incluirum.disabled = true;
   document.form1.excluirum.disabled = false;
  }
 }
}
function js_enviar(){
 js_divCarregando("Aguarde, enviando registros","MSG");
 parent.document.form1.cod_localizacao.length = 0;
 tam = document.form1.localizacoesbusca.length;
 for(i=0;i<tam;i++){
  parent.document.form1.cod_localizacao.options[parent.document.form1.cod_localizacao.length] = new Option(document.form1.localizacoesbusca.options[i].text,document.form1.localizacoesbusca.options[i].value);
 }
 tam = parent.document.form1.cod_localizacao.length;
 for(i=0;i<tam;i++){
  parent.document.form1.cod_localizacao.options[i].selected = true;
 }
 if(parent.document.form1.alunospossib){
  parent.document.form1.alunospossib.length = 0;
  parent.document.form1.alunos.length = 0;
 }
 js_removeObj("MSG");
 parent.db_iframe_localizacao.hide();
}
js_OrdenarLista("localizacoes");
if(document.form1.localizacoesbusca.length>0){
 document.form1.enviar.disabled = false;
 document.form1.excluirtodos.disabled = false;
}
</script>


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
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_db_config_classe.php");
include("classes/db_orcparamseq_classe.php");
include("dbforms/db_funcoes.php");
$oDaoOrcparamseq = new cl_orcparamseq;
$oGET= db_utils::postMemory($_GET);
$db_opcao = 1;
if (isset($oGET->chavepesquisa)) {

  $chavepesquisa = $oGET->chavepesquisa;
  $db_opcao      = 22;
}
$oPost = db_utils::postMemory($_POST);

if (isset($oPost->campos)) {

  db_inicio_transacao();
  for ($i = 0; $i < count($oPost->campos); $i++) {

    $oDaoOrcparamseq->o69_codparamrel = $chavepesquisa;
    $oDaoOrcparamseq->o69_codseq      = $oPost->campos[$i];
    $oDaoOrcparamseq->o69_ordem       = $i+1;
    $oDaoOrcparamseq->alterar($chavepesquisa, $oPost->campos[$i]);

  }
  db_fim_transacao();
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
  <center>
  <form action="" method="post" name="form1" onSubmit="return js_selecionar()">
  <table><tr><td>
  <fieldset><Legend><b>Ordernar as linhas do Relatório</b></legend>

      <table border="0">
        <tr>
         <td align="right" colspan="" width="80%">
         <select name="campos[]" id="campos" size="15" style="width:250px;" multiple>
              <?
              if (isset($chavepesquisa)) {

                $sSql     = $oDaoOrcparamseq->sql_query_file($chavepesquisa, null,"*", "o69_ordem" );
                $rsLinhas = $oDaoOrcparamseq->sql_record($sSql);
                $iNumRows = $oDaoOrcparamseq->numrows;
                if ($iNumRows != 0) {

                  for ($i = 0;$i < $iNumRows;$i++) {

                    $oLinha    = db_utils::fieldsmemory($rsLinhas, $i);
                    $sStyle    = "margin-left:".($oLinha->o69_nivellinha*10)."px;";
                    if ($oLinha->o69_totalizador == 't') {
                      $sStyle .= "font-weight:bold;";
                    }
                    echo "<option style='{$sStyle}' value='{$oLinha->o69_codseq}'>{$oLinha->o69_labelrel}</option>\n";
                  }
                }
              }
              ?>
             </select>
            </td>
            <td align="left" valign="middle" width="20%">
             <img style="cursor:hand" onClick="js_sobe();return false;" src="skins/img.php?file=Controles/seta_up.png" />
              <br/><br/>
             <img style="cursor:hand" onClick="js_desce()" src="skins/img.php?file=Controles/seta_down.png" />
              <br/><br/>


     </td>
         </tr>
      </table>

      </fieldset>
      </td>
      </tr>
      </table>
      <input name="db_opcao" type="submit" id="db_opcao" value="Incluir" >
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
    </center>
    </form>
  </td>
  </tr>
</table>
</body>
</html>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
function js_sobe() {
  var F = document.getElementById("campos");
  if (F.selectedIndex != -1 && F.selectedIndex > 0) {

    var SI        = F.selectedIndex - 1;
    var auxText   = F.options[SI].text;
    var auxValue  = F.options[SI].value;
    var sStyle    = F.options[SI + 1].style;
    var sStyleOther = F.options[SI].style;
    F.options[SI]                  = new Option(F.options[SI + 1].text,F.options[SI + 1].value);
    F.options[SI].style.fontWeight = sStyle.fontWeight;
    F.options[SI].style.marginLeft = sStyle.marginLeft;
    F.options[SI + 1]              = new Option(auxText,auxValue);
    F.options[SI+1].style.fontWeight = sStyleOther.fontWeight;
    F.options[SI+1].style.marginLeft = sStyleOther.marginLeft;
    F.options[SI].selected         = true;

  }
}
function js_desce() {

  var F = document.getElementById("campos");
  if (F.selectedIndex != -1 && F.selectedIndex < (F.length - 1)) {

    var SI = F.selectedIndex + 1;
    var auxText = F.options[SI].text;
    var auxValue = F.options[SI].value;
    var sStyle    = F.options[SI - 1].style;
    var sStyleOther = F.options[SI].style;
    F.options[SI] = new Option(F.options[SI - 1].text,F.options[SI - 1].value);
    F.options[SI].style.fontWeight = sStyle.fontWeight;
    F.options[SI].style.marginLeft = sStyle.marginLeft;
    F.options[SI - 1] = new Option(auxText,auxValue);
    F.options[SI-1].style.fontWeight = sStyleOther.fontWeight;
    F.options[SI-1].style.marginLeft = sStyleOther.marginLeft;
    F.options[SI].selected = true;

  }
}
function js_excluir() {
  var F = document.getElementById("campos");
  var SI = F.selectedIndex;
  if(F.selectedIndex != -1 && F.length > 0) {
    F.options[SI] = null;
    if(SI <= (F.length - 1))
      F.options[SI].selected = true;
  }
}
function js_insSelect() {
  var texto=document.form1.db61_descr.value;
  var valor=document.form1.db61_codparag.value;
  if(texto != "" && valor != ""){
    var F = document.getElementById("campos");
    var testa = false;

    for(var x = 0; x < F.length; x++){

      if(F.options[x].value == valor || F.options[x].text == texto){
        testa = true;
  break;
      }
    }
    if(testa == false){
      F.options[F.length] = new Option(texto,valor);
    }
 }
   texto=document.form1.db61_descr.value="";
   valor=document.form1.db61_codparag.value="";
 document.form1.lanca.onclick = '';
}
function js_selecionar() {
  var F = document.getElementById("campos").options;
  for(var i = 0;i < F.length;i++) {
    F[i].selected = true;
  }
  return true;
}
function js_veri(){
  if(document.form1.db60_descr.value==""){
    alert("Preencha a descrição!");
    return false;
  }
return true;
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_orcparamrel','func_orcparamrel.php?funcao_js=parent.js_preenchepesquisa|o42_codparrel','Pesquisa',true,'0','1');
}
function js_preenchepesquisa(chave){
  db_iframe_orcparamrel.hide();
  <?
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  ?>
}
<?
if ($db_opcao != 22) {
 echo "document.form1.pesquisar.click()";
}
?>
</script>
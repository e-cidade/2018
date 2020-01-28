<?php
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?
    db_app::load('scripts.js,estilos.css,prototype.js, dbmessageBoard.widget.js, windowAux.widget.js');
    db_app::load('dbtextField.widget.js, dbcomboBox.widget.js, DBViewGeracaoAutorizacao.classe.js, grid.style.css');
    db_app::load('datagrid.widget.js, strings.js, arrays.js');
  ?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" style="margin-top:30px;">
<center>
  <form>
    <fieldset style="width: 250px; padding: 10px;">
      <legend><b>Gerar Autorização de Empenho</b></legend>
      <table width="100%">
        <tr>
          <td width="55%">
            <?php
              db_ancora("<b>Processo de Compra:</b>", "js_pesquisaProcessoCompra(true);", 1);
            ?>
          </td>
          <td>
            <?php
              db_input("iProcessoCompra", 10, false, true, "text", 1, "onchange='js_pesquisaProcessoCompra(false);'");
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <?php
              db_ancora("<b>Solicitação:</b>", "js_pesquisaSolicitacao(true);", 1);
            ?>
          </td>
          <td>
            <?php
              db_input("iSolicitacao", 10, false, true, "text", 1, "onchange='js_pesquisaSolicitacao(false);'");
            ?>
          </td>
        </tr>
      </table>
    </fieldset>

    <br />
    <input type="button" name="btnEnviarDados" id="btnEnviarDados" value="Enviar Dados" />
  </form>

</center>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>


<script>


$("btnEnviarDados").observe("click", function() {


  var iProcessoCompra = $('iProcessoCompra').value;
  var iSolicitacao    = $('iSolicitacao').value;

  if (iProcessoCompra == "" && iSolicitacao == "") {

    alert("Informe o código do processo de compras ou solicitação.");
    return false;
  }

  var sStringUrl = "";
  if (iProcessoCompra.trim() != "") {
    sStringUrl  = "iCodigoCompra="+iProcessoCompra;
    sStringUrl += "&iTipoCompra=1";
  } else {
    sStringUrl  = "iCodigoCompra="+iSolicitacao;
    sStringUrl += "&iTipoCompra=2";
  }
  sStringUrl    += "&iInstituicao="+<?=db_getsession("DB_instit");?>;
  sStringUrl    += "&iAno="+<?=db_getsession("DB_anousu");?>;
  location.href  = "com1_geraautorizacao002.php?"+sStringUrl;
});

/*
 * Abre Lookup para pesquisa de um processo de compra.
 */
function js_pesquisaProcessoCompra(lMostra) {

  if (lMostra) {
    js_OpenJanelaIframe('top.corpo','db_iframe_pcproc','func_pcproc.php?lFiltroContrato=1&funcao_js=parent.js_preencheProcessoCompra|pc80_codproc','Pesquisa Processo de Compras',true);
  } else {

    if ($('iProcessoCompra').value != "") {
      var sUrlOpen = 'func_pcproc.php?lFiltroContrato=1&pesquisa_chave='+$('iProcessoCompra').value+'&funcao_js=parent.js_completaProcessoCompra';
      js_OpenJanelaIframe('top.corpo','db_iframe_pcproc',sUrlOpen,'Pesquisa Processo de Compras', false);
    }
  }
}

/**
 * Preenche o input referente ao processo de compra
 */
function js_preencheProcessoCompra(iCodigoProcessoCompra) {

  $('iProcessoCompra').value = iCodigoProcessoCompra;
  $('iSolicitacao').value    = "";
  db_iframe_pcproc.hide();
}

/**
 * Utilizado quando o iframe não é aberto para pesquisar um código do proceso de compra
 */
function js_completaProcessoCompra(iCodigoProcessoCompra, lErro) {
  if (lErro) {
    $('iProcessoCompra').value = "";
  }
  $('iSolicitacao').value = "";
}

/**
 * Funções de pesquisa de uma solicitação
 */
function js_pesquisaSolicitacao(lMostra) {

  if (lMostra) {
    var sUrlOpen = 'func_solicita_compras.php?lFiltroContrato=1&iTipoConsulta=2&passar=&proc=false&validar_liberacao_solicitacao=true&gerautori=true&param=&funcao_js=parent.js_preencheSolicitacao|pc10_numero';
    js_OpenJanelaIframe('top.corpo','db_iframe_solicita',sUrlOpen,'Pesquisa Solicitação',true);
  } else {

    $('btnEnviarDados').disabled = true;
    if ($('iSolicitacao').value != "") {
      var sUrlOpen = 'func_solicita_compras.php?lFiltroContrato=1&iTipoConsulta=2&passar=&proc=false&validar_liberacao_solicitacao=true&gerautori=true&param=&pesquisa_chave='+$('iSolicitacao').value+'&funcao_js=parent.js_completaSolicitacao';
      js_OpenJanelaIframe('top.corpo','db_iframe_solicita',sUrlOpen,'Pesquisa Solicitação', false);
    }
  }
}
function js_preencheSolicitacao(iCodigoSolicita) {

  $('iSolicitacao').value    = iCodigoSolicita;
  $('iProcessoCompra').value = "";
  db_iframe_solicita.hide();
}
function js_completaSolicitacao(iCodigoSolicita, lErro) {
  if (lErro) {
    $('iSolicitacao').value = "";
  }
  $('iProcessoCompra').value = "";
  $('btnEnviarDados').disabled = false;
}

</script>













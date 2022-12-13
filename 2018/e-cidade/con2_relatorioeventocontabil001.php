<?php
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oRotuloConHistDoc  = new rotulo('conhistdoc');
$oRotuloConHistDoc->label();
$oRotuloContranslan = new rotulo('contranslan');
$oRotuloContranslan->label();

/*
 * Buscamos os anos das transações cadastrados em contrans
 */
$oDaoContrans          = db_utils::getDao('contrans');
$sSqlBuscaAnoTransacao = $oDaoContrans->sql_query_file(null, "distinct c45_anousu", "c45_anousu desc");
$rsBuscaAnoTransacao   = $oDaoContrans->sql_record($sSqlBuscaAnoTransacao);
$aAnosConfigurados     = array();
for ($iRowAno = 0; $iRowAno < $oDaoContrans->numrows; $iRowAno++) {

  $iAnoLocalizado                     = db_utils::fieldsMemory($rsBuscaAnoTransacao, $iRowAno)->c45_anousu;
  $aAnosConfigurados[$iAnoLocalizado] = $iAnoLocalizado;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<body class="body-default">
  <div class="container">
    <form>
      <fieldset style="width: 600px;">
        <legend><b>Relatório de Eventos Contábeis</b></legend>
        <div id="lancadorDocumentos"></div>
        <table width="100%" style="margin-top: 15px;">
          <tr id="trLancamentos">
            <td>
              <?php
                db_ancora("<b>Lançamento:</b>", "js_pesquisaLancamento(true);", 1);
              ?>
            </td>
            <td>
              <?php
                db_input('c46_seqtranslan', 10, $Ic46_seqtranslan, true, 'text', 1, "onchange='js_pesquisaLancamento(false);'");
                db_input('c46_descricao', 50, $Ic46_descricao, true, 'text', 3)
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <br>
      <input type="button" name="btnEmiteRelatorio" id="btnEmiteRelatorio" value="Emitir" />
    </form>
  </div>
<?php db_menu(); ?>
<script>
  var oLancadorDocumentos;

  function getCodigosDocumentos() {

    var aCodigosDocumentos = [];
    var aDocumentos        = oLancadorDocumentos.getRegistros(false);

    for (var iDocumento = 0; iDocumento < aDocumentos.length; iDocumento++) {
      aCodigosDocumentos.push(aDocumentos[iDocumento].sCodigo);
    }

    return aCodigosDocumentos;
  }

  document.observe('dom:loaded', function () {

    oLancadorDocumentos = new DBLancador('lancadorDocumentos');
    oLancadorDocumentos.setNomeInstancia('oLancadorDocumentos');
    oLancadorDocumentos.setLabelAncora('Documento:');
    oLancadorDocumentos.setTextoFieldset('Documentos');
    oLancadorDocumentos.setParametrosPesquisa('func_conhistdoc.php', ['0','1']);
    oLancadorDocumentos.setTituloJanela("Pesquisa de Documentos");
    oLancadorDocumentos.setGridHeight(150);
    oLancadorDocumentos.show($('lancadorDocumentos'));
  });

  $('btnEmiteRelatorio').observe('click', function() {

    var aCodigosDocumentos = getCodigosDocumentos();

    if (aCodigosDocumentos.length === 0) {

      if (!confirm("A emissão do relatório pode demorar pois nenhum documento foi selecionado. Confirma esta operação?")) {
        return false;
      }
    }

    if ($F('c46_seqtranslan') == "") {

      var sMsgConfirm  = "Não foi selecionado nenhum lançamento.\nSerá emitido o relatório com todos";
      sMsgConfirm     += " os lançamentos do(s) documento(s) selecionado(s).\n\nConfirma esta operação?";
      if (!confirm(sMsgConfirm)) {
        return false;
      }
    }

    var sUrlRelatorio  = "con2_relatorioeventocontabil002.php?";
        sUrlRelatorio += "iCodigosDocumentos=" + aCodigosDocumentos.join(',');
        sUrlRelatorio += "&iCodigoLancamento=" + $F('c46_seqtranslan');
    var sOpcoes = 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ';
    var oJanela = window.open(sUrlRelatorio, '', sOpcoes);

    oJanela.moveTo(0,0);
  });

  function js_pesquisaLancamento(lMostra) {

    var aCodigosDocumentos = getCodigosDocumentos();
    var sUrlLancamento = "func_contranslan.php?iCodigoDocumento=" + aCodigosDocumentos.join(',') + "&pesquisa_chave=" + $F("c46_seqtranslan") + "&funcao_js=parent.js_completaLancamento";
    if (lMostra) {
      sUrlLancamento = "func_contranslan.php?iCodigoDocumento=" + aCodigosDocumentos.join(',') + "&funcao_js=parent.js_preencheLancamento|c46_seqtranslan|c46_descricao";
    }
    js_OpenJanelaIframe("", "db_iframe_contranslan", sUrlLancamento, "Pesquisa Lançamento", lMostra);
  }

  function js_preencheLancamento(iSequencialLancamento, sDescricaoLancamento) {

    $('c46_seqtranslan').value = iSequencialLancamento;
    $('c46_descricao').value   = sDescricaoLancamento;
    db_iframe_contranslan.hide();
  }

  function js_completaLancamento(iCodigoHistorico, lErro, sDescricaoLancamento) {

    if (sDescricaoLancamento == null) {
      $("c46_descricao").value = iCodigoHistorico;
    } else {
      $("c46_descricao").value = sDescricaoLancamento;
    }
    if (lErro) {
      $("c46_seqtranslan").value = "";
    }
  }

  function js_pesquisaDocumento(lMostra) {

    var sUrlDocumento = "";
    if (lMostra) {
      sUrlDocumento = "func_conhistdoc.php?funcao_js=parent.js_preencheDocumento|c53_coddoc|c53_descr";
    } else {
      sUrlDocumento = "func_conhistdoc.php?pesquisa_chave="+$F("c53_coddoc")+"&funcao_js=parent.js_completaDocumento";
    }
    js_OpenJanelaIframe("", "db_iframe_conhistdoc", sUrlDocumento, "Pesquisa Documento", lMostra);
  }

  function js_preencheDocumento(iCodigoDocumento, sDescricaoDocumento) {

    $("c53_coddoc").value = iCodigoDocumento;
    $("c53_descr").value  = sDescricaoDocumento;
    db_iframe_conhistdoc.hide();
  }

  function js_completaDocumento(sDescricao, lErro) {

    $("c53_descr").value = sDescricao;
    if (lErro) {
      $("c53_coddoc").value = "";
    }
  }
</script>
</body>
</html>

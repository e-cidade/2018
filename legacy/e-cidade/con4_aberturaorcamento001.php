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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));

$oGet       = db_utils::postMemory($_GET);
$iAnoSessao = db_getsession("DB_anousu");
$sLegend = "Processar Abertura do Exercício";
$lProcessamento = 'true';
if (isset($oGet->lDesprocessar) && $oGet->lDesprocessar == "true") {
	$sLegend = "Desprocessar Abertura do Exercício";
  $lProcessamento = 'false';
}

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
    db_app::load("scripts.js");
    db_app::load("strings.js, prototype.js, estilos.css, AjaxRequest.js");
  ?>
</head>
<body bgcolor="#CCCCCC" style="margin-top:30px;">
  <div class="container">
    <form name='form1'>
      <fieldset style="width: 550px">
        <legend><b><?php echo $sLegend;?></b></legend>
        <table width="100%">
          <tr>
            <td nowrap="nowrap"><b><label for="iAnoSessao">Ano:</label></b></td>
            <td nowrap="nowrap">
              <?php
                db_input("iAnoSessao", 10, null, true, 'text', 3);
              ?>
            </td>
          </tr>
          <tr>
          	<td nowrap="nowrap">
          		<b><label for="nValorReceita">Valor da Receita:</label></b>
          	</td>
          	<td nowrap="nowrap">
          		<?php
          		  db_input("nValorReceita", 15, null, true, "text", 3);
          		?>
          	</td>
          </tr>

          <tr>
          	<td nowrap="nowrap">
          		<b><label for="nValorDespesa">Valor da Despesa:</label></b>
          	</td>
          	<td nowrap="nowrap">
          		<?php
          		  db_input("nValorDespesa", 15, null, true, "text", 3);
          		?>
          	</td>
          </tr>
          <tr>
            <td nowrap="nowrap" colspan="2">
              <fieldset>
                <legend><b><label for="sObservacao">Observações</label></b></legend>
                <textarea name="sObservacao" id="sObservacao" style="width:100%; height: 100px" ></textarea>
              </fieldset>
            </td>
          </tr>
        </table>
      </fieldset>
      <input type="button" name="btnProcessar" id="btnProcessar" value="Processar" disabled="disabled"/>
    </form>
  </div>
 </body>
  <?
	  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</html>
<script type="text/javascript">

var oGet          = js_urlToObject();
var sUrlRpc       = 'con4_aberturaexercicio.RPC.php';
var lProcessamento = <?php echo $lProcessamento; ?>;

/**
 * Busca o valor dos RPs nao processados para o ano da sessao
 */
function js_buscaValorAberturaExercicio() {

  js_divCarregando("Aguarde, buscando valores...", "msgBox");
  var oObject           = {};
  oObject.exec          = "getDadosOrcamento";
  oObject.lProcessados  = oGet.lDesprocessar == 'true' ? 'false' : 'true';

  var oAjax = new Ajax.Request (sUrlRpc,{ method:'post',
                                          parameters:'json='+Object.toJSON(oObject),
                                          onComplete:js_retornoValorAberturaExercicio});
}

function js_retornoValorAberturaExercicio(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 2) {

    alert(oRetorno.message.urlDecode());
    return false;
  }

  $('nValorDespesa').value = js_formatar(oRetorno.nValorDotacao, "f");
  $('nValorReceita').value = js_formatar(oRetorno.nValorReceita, "f");

  if (oRetorno.lBloquearTela) {

    var oInputObservacao = $('sObservacao');
		oInputObservacao.disabled              = true;
		oInputObservacao.style.backgroundColor = '#DEB887';
		oInputObservacao.style.color           = '#333333';
		$('btnProcessar').disabled = true;

		var sMsgAviso = "Abertura do exercício já processada.";
		if (oGet.lDesprocessar == 'true') {
			sMsgAviso = "Abertura do exercício já desprocessada.";
		}
		sMsgAviso += "\nVocê não pode executar essa rotina novamente";

		alert(sMsgAviso);
  }


}

js_buscaValorAberturaExercicio();

/**
 * Verificamos se o campo observacao foi devidamente preenchido
 */
$('sObservacao').observe('keyup', function() {
  $('btnProcessar').disabled = $F('sObservacao').trim() == "";
});


$('btnProcessar').observe('click', function() {

  var oParametros           = {};
  oParametros.exec          = "processarAbertura";
  oParametros.lProcessar    = lProcessamento;
  oParametros.sObservacao   = encodeURIComponent(tagString($F('sObservacao')));
  js_divCarregando("Aguarde, processando...", "msgBox");
  var oAjax = new Ajax.Request (sUrlRpc,{ method:'post',
                                          parameters:'json='+Object.toJSON(oParametros),
                                          onComplete:js_retornoProcessamento});
});


function js_retornoProcessamento(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  var oInputObservacao = $('sObservacao');

	oInputObservacao.disabled              = true;
	oInputObservacao.style.backgroundColor = '#DEB887';
	oInputObservacao.style.color           = '#333333';
	$('btnProcessar').disabled = true;
  alert(oRetorno.message.urlDecode());
}
</script>
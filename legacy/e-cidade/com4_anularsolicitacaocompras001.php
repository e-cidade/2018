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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");

define('MENSAGEM', 'patrimonial.compras.com4_anularsolicitacaocompras001.');

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$Ipc10_numero = '';
$Ipc67_processoadministrativo = '';
$oRotulo = new rotulocampo();
$oRotulo->label('pc10_numero');
$oRotulo->label('pc67_processoadministrativo');

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <?php db_app::load("estilos.css, scripts.js, strings.js, prototype.js, AjaxRequest.js"); ?>
</head>
<body style="background-color: #CCCCCC; margin-top: 30px;">
<div class="container">

  <fieldset style="width: 600px">
    <legend class="bold">
      <?php echo _M(MENSAGEM.'fieldsetGlobal'); ?>
    </legend>

    <table style="width: 100%">
      <tr>
        <td style="width: 150px">
          <?php
          db_ancora('Solicitação de Compras:', 'pesquisaSolicitacao(true);', 1)
          ?>
        </td>
        <td>
          <?php
          $Spc10_numero = "Solicitação de Compras";
          db_input('pc10_numero', 10, $Ipc10_numero, true, 'text', 1, 'onchange="pesquisaSolicitacao(false)";');
          ?>
        </td>
      </tr>
      <tr>
        <td class="bold">Processo Administrativo:</td>
        <td>
          <?php
          db_input('pc67_processoadministrativo', 20, $Ipc67_processoadministrativo, true, 'text', 1);
          ?>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <fieldset style="width: 97%">
            <legend class="bold">
              <?php echo _M(MENSAGEM.'fieldsetObservacao'); ?>
            </legend>
            <label for="textareaObservacao">
              <textarea style="width: 100%; height: 150px;" id="textareaObservacao"></textarea>
            </label>
          </fieldset>
        </td>
      </tr>
    </table>
  </fieldset>
  <p>
    <input type="button" value="Confirmar" id="btnAnularSolicitacao" />
  </p>
</div>

<?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>

</body>
</html>
<script type="text/javascript">

  const MENSAGENS = 'patrimonial.compras.com4_anularsolicitacaocompras001.';
  var sUrlRpc = '',
      oCodigoSolicitacao = $('pc10_numero'),
      oProcessoAdministrativo = $('pc67_processoadministrativo');
      oMotivo = $('textareaObservacao');

  $('btnAnularSolicitacao').observe('click',
    function () {

      try {

        if (oCodigoSolicitacao.value == "") {
          throw _M(MENSAGENS+"codigo_solicitacao_vazio");
        }

        if (oMotivo.value.trim() == "") {
          throw _M(MENSAGENS+"motivo_vazio");
        }

        if (!confirm(_M(MENSAGENS+'confirma_anulacao'))) {
          return false;
        }

        var oParametro = {
          'exec' : 'anularSolicitacao',
          'iCodigoSolicitacao' : oCodigoSolicitacao.value,
          'sProcessoAdministrativo' : encodeURIComponent(tagString(oProcessoAdministrativo.value)),
          'sMotivo' : encodeURIComponent(tagString(oMotivo.value))
        };

        new AjaxRequest('com4_solicitacaoCompras.RPC.php', oParametro,
          function (oRetorno, lErro) {

            alert(oRetorno.mensagem.urlDecode());
            if ( ! lErro ) {

              oCodigoSolicitacao.value = "";
              oProcessoAdministrativo.value = "";
              oMotivo.value = "";
            }

          }
        ).setMessage(_M(MENSAGENS+'ajax_salvando_anulacao')).execute();



      } catch (eException) {
        alert(eException);
        return false;
      }

    }
  );

  function pesquisaSolicitacao(lMostra) {

    var sQuery = "tiposolicitacao=1,5&pesquisa_chave="+$F('pc10_numero')+"&funcao_js=parent.validaSolicitacao";
    sQuery += "&ativas=1";
    if (lMostra) {
      sQuery = 'lConsulta=true&tiposolicitacao=1,5&funcao_js=parent.preencheSolicitacao|pc10_numero&ativas=1';
    }
    js_OpenJanelaIframe('top.corpo',
      'db_iframe_solicita',
      "func_solicita.php?" + sQuery,
      'Pesquisa Solicitação de Compras',
      lMostra);
  }

  function preencheSolicitacao(iCodigoSolicitacao) {
    oCodigoSolicitacao.value = iCodigoSolicitacao;
    db_iframe_solicita.hide();
  }

  function validaSolicitacao(iCodigoSolicitacao, lErro) {

    oCodigoSolicitacao.value = iCodigoSolicitacao;
    if (lErro) {
      oCodigoSolicitacao.value = '';
    }

  }
</script>

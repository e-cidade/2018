<?php
require_once ("libs/db_stdlib.php");
require_once "libs/db_conecta.php";
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("dbforms/db_funcoes.php");

$oGet = db_utils::postMemory($_GET);
?>
<html xmlns="http://www.w3.org/1999/html">
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <?php
  // Includes padrão
  db_app::load("scripts.js, prototype.js, strings.js, AjaxRequest.js");
  db_app::load("estilos.css");

  ?>
  <script type="text/javascript" src="scripts/widgets/DBAbas.widget.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">

  <style>
    .headerLabel {
      font-weight: bold;
      width: 140px;
    }

  </style>
</head>
<body style='background-color: #CCCCCC; margin-top: 30px'>
<form id="form-abertura-acompanhamento">
  <div class="container">
    <div style="width: 600px;">

      <div>
        <fieldset>
          <legend class="bold">Abertura do Acompanhamento da Programação Financeira</legend>
          <table style="width: 100%">
            <tr>
              <td class="headerLabel">
                <?php
                db_ancora('Perspectiva de Origem:', 'js_pesquisa()', 1, "", "perspectiva-lookup");
                ?>
              </td>
              <td colspan="3">
                <?php
                db_input('perspectiva', 15, false, true, 'text', 3, null, null, null, 'width: 20%;');
                ?>
                <?php
                db_input('perspectiva_descr', 15, false, true, 'text', 3, null, null, null, 'width: 79%;');
                ?>
              </td>
            </tr>
            <tr>
              <td class="headerLabel"><label for="descricao">Descrição:</label></td>
              <td colspan="3">
                <?php
                db_input('descricao', 15, false, false, 'text', 3, null, null, null, 'width: 100%;');
                ?>
              </td>
            </tr>
            <tr>
              <td class="headerLabel"><label for="mes">Mês:</label></td>
              <td colspan="3">
                <?php
                $meses = array(
                    0  => 'Selecione',
                    1  => 'Janeiro',
                    2  => 'Fevereiro',
                    3  => 'Março',
                    4  => 'Abril',
                    5  => 'Maio',
                    6  => 'Junho',
                    7  => 'Julho',
                    8  => 'Agosto',
                    9  => 'Setembro',
                    10 => 'Outubro',
                    11 => 'Novembro',
                    12 => 'Dezembro',
                );
                db_select('mes', $meses, true, 1, 'style="width: 20%"');
                ?>
              </td>
            </tr>
            <tr>
              <td class="headerLabel"><label for="ano">Ano:</label></td>
              <td colspan="3">
                <?php
                $ano = db_getsession('DB_anousu');
                db_input('ano', 15, false, true, 'text', 3, null, null, null, 'width: 20%');
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
        <p class="text-center">
          <input type="button" id="btnIncluir" value="Incluir" />
        </p>
      </div>
    </div>
  </div>
</form>

<script>
  const MENSAGEM_ACOMPANHAMENTO = 'financeiro.orcamento.orc4_acompanhamentocronograma.';

  window.onload = function() {

    reiniciaJanela();
    $('btnIncluir').addEventListener('click', js_incluir);
  };

  function reiniciaJanela() {

    $('perspectiva').value       = '';
    $('perspectiva_descr').value = '';
    $('descricao').value         = '';
    $('mes').value               = 0; // marca a opção 'Selecione'

    js_pesquisa();
  }

  function js_incluir() {

    var sUrl           = "orc4_acompanhamentocronograma.RPC.php";
    var iPerspectiva   = $F('perspectiva');
    var sDescricao     = $F('descricao');
    var iMes           = $F('mes');
    var iAno           = $F('ano');

    if (iPerspectiva == '') {
      alert(_M(MENSAGEM_ACOMPANHAMENTO + 'perspectiva_obrigatorio'));
      return;
    }

    if (sDescricao == '') {
      alert(_M(MENSAGEM_ACOMPANHAMENTO + 'descricao_obrigatorio'));
      return;
    }

    if (iMes == 0) {
      alert(_M(MENSAGEM_ACOMPANHAMENTO + 'mes_obrigatorio'));
      return;
    }

    var oParametros = {
      'exec'        : 'salvar',
      'perspectiva' : iPerspectiva,
      'descricao'   : encodeURIComponent(tagString(sDescricao)),
      'mes'         : iMes,
      'ano'         : iAno
    };

    new AjaxRequest(sUrl, oParametros,
      function (oRetorno, lErro) {

        if (oRetorno.mensagem) {
          alert(oRetorno.mensagem.urlDecode());
        }

        if (!lErro) {
          reiniciaJanela();
        }
      }
    ).setMessage(_M(MENSAGEM_ACOMPANHAMENTO + 'aguarde_incluindo_acompanhamento')).execute();
  }

  function js_pesquisa() {
    js_OpenJanelaIframe('top.corpo', 'db_iframe_cronogramaperspectiva', 'func_cronogramaperspectiva.php?funcao_js=parent.js_preenchepesquisa|o124_sequencial|o124_descricao&homologado=1', 'Pesquisa', true);
  }

  function js_preenchepesquisa(iChave, sDescricao) {
    $('perspectiva').value       = iChave;
    $('perspectiva_descr').value = sDescricao;
    db_iframe_cronogramaperspectiva.hide();
  }
</script>

<?php
db_menu( db_getsession("DB_id_usuario"),
  db_getsession("DB_modulo"),
  db_getsession("DB_anousu"),
  db_getsession("DB_instit") );
?>
</body>
</html>

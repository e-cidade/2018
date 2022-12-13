<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
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
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");
?>
<html>
  <head>
    <title>DBSeller Informática Ltda</title>
    <meta http-equiv="Expires" CONTENT="0">
    <?php
    $assets = array(
      "scripts.js",
      "strings.js",
      "prototype.js",
      "estilos.css",
      "AjaxRequest.js",
      "widgets/Input/DBInput.widget.js",
      "widgets/Input/DBInputDate.widget.js",
      "DBLookUp.widget.js",
      "DBToggleList.widget.js",
      "EmissaoRelatorio.js"
    );
    db_app::load($assets);
    ?>
    <style>
      #toggleEspecialidade img {
        display: none;
      }
    </style>
  </head>
  <?php
  db_menu();
  try {
    new UnidadeProntoSocorro(db_getsession("DB_coddepto"));
  } catch(\Exception $e) {
    die(
    "<div class='container'><h2>{$e->getMessage()}</h2></div>"
    );
  }
  ?>
  <body class="body-default">
    <div class="container">
      <form>
        <fieldset>
          <legend>Relatório de Agenda Médica</legend>

          <table class="form-container">
            <tr>
              <td>
                <label for="dataInicio">Período:</label>
              </td>
              <td>
                <input id="dataInicio" type="text" value="" />
                <label for="dataFim">até</label>
                <input id="dataFim" type="text" value="" />
              </td>
            </tr>

            <tr>
              <td>
                <label for="sd03_i_codigo">
                  <a id="ancoraProfissional" href="#" func-arquivo="func_medicos.php">Profissional:</a>
                </label>
              </td>
              <td>
                <input id="sd03_i_codigo" type="text" />
                <input id="z01_nome"      type="text" readonly="readonly" />
              </td>
            </tr>

            <tr>
              <td colspan="2">
                <fieldset class="separator">
                  <legend>Especialidade</legend>
                  <div id="toggleEspecialidade"></div>
                </fieldset>
              </td>
            </tr>
          </table>

        </fieldset>

        <input id="gerarRelatorio" type="button" value="Gerar Relatório" />
      </form>
    </div>
  </body>
</html>
<script>
/******************************************************************
 * BLOCO PARA DEFINIÇÃO DA CONSTANTE E VARIÁVEIS A SEREM UTILIZADAS
 ******************************************************************/
const MENSAGENS_SAU2_AGENDAMEDICA = 'saude.ambulatorial.sau2_agendamedica001.';

var aInconsistencias    = [];
var oDataInicio         = new DBInputDate($('dataInicio'));
var oDataFim            = new DBInputDate($('dataFim'));
var oAncoraProfissional = new DBLookUp($('ancoraProfissional'), $('sd03_i_codigo'), $('z01_nome'), {
  'sLabel'      : 'Pesquisa Profissional',
  'sQueryString': '&lFiltraDptoLogado&prof_ativo'
});

var oToggleEspecialidade = new DBToggleList([{
  'sId'   : 'sEspecialidade',
  'sLabel': 'Especialidade',
  'sWidth': '250px'
}]);
oToggleEspecialidade.closeOrderButtons();
oToggleEspecialidade.show($('toggleEspecialidade'));

/**
 * Busca as especialidades do médico no departamento atual
 */
function buscaEspecialidadesMedico() {

  if(empty($F('sd03_i_codigo'))) {
    return;
  }

  var oParametros = {
    'exec'          : 'especialidadeMedico',
    'iProfissional' : $F('sd03_i_codigo'),
    'lValidaUnidade': true,
    'lSomenteAtiva' : true
  };
  AjaxRequest.create('sau4_medicos.RPC.php', oParametros, function(oRetorno, lErro) {

    if(lErro === true) {

      alert(oRetorno.message.urlDecode());
      return;
    }

    oRetorno.aEspecialidades.each(function(oEspecialidade) {

      oEspecialidade.sEspecialidade = oEspecialidade.sEspecialidade.urlDecode();
      oToggleEspecialidade.addSelect(oEspecialidade);
    });

    oToggleEspecialidade.renderRows();
  }).execute();
}

/**
 * Valida os campos antes da geração do relatório
 * @returns {boolean}
 */
function validaCampos() {

  aInconsistencias.length = 0;

  if(empty($F('dataInicio'))) {
    aInconsistencias.push(_M(MENSAGENS_SAU2_AGENDAMEDICA + 'periodo_inicial_nao_informado'));
  }

  if(empty($F('dataFim'))) {
    aInconsistencias.push(_M(MENSAGENS_SAU2_AGENDAMEDICA + 'periodo_final_nao_informado'));
  }

  if(empty($F('sd03_i_codigo'))) {
    aInconsistencias.push(_M(MENSAGENS_SAU2_AGENDAMEDICA + 'profissional_nao_selecionado'));
  }

  if(oToggleEspecialidade.getSelected().length == 0) {
    aInconsistencias.push(_M(MENSAGENS_SAU2_AGENDAMEDICA + 'especialidade_nao_selecionada'));
  }
}

/**
 * Responsável por gerar o relatório
 */
function gerarRelatorio() {

  validaCampos();

  if(aInconsistencias.length > 0) {

    var sMensagem = "Relatório não gerado. Os seguintes problemas foram encontrados:\n\n- " + aInconsistencias.join("\n- ");
    alert(sMensagem);

    return;
  }
  var sParametros = JSON.stringify({
    'aEspecialidades': oToggleEspecialidade.getSelected(),
    'dtInicio'       : $F('dataInicio'),
    'dtFim'          : $F('dataFim'),
    'sProfissional'  : $F('z01_nome'),
    'iProfissional'  : $F('sd03_i_codigo')
  });

  var parametrosImpressao = {"parametros": sParametros.urlEncode()};
  var oEmissaoRelatorio = new EmissaoRelatorio('sau2_agendamedica002.php', parametrosImpressao);
  oEmissaoRelatorio.open();
}

/***************************************************************
 * BLOCO ONDE SÃO TRATADAS AS AÇÕES DOS CAMPOS E SETADAS CLASSES
 ***************************************************************/
$('dataInicio').observe('change', function() {

  if(    !empty($F('dataInicio'))
      && !empty($F('dataFim'))
      && $F('dataInicio') > $F('dataFim')
    ) {

    alert(_M(MENSAGENS_SAU2_AGENDAMEDICA + 'periodo_inicial_maior'));
    oDataInicio.setValue('');
  }
});

$('dataFim').observe('change', function() {

  if(    !empty($F('dataInicio'))
      && !empty($F('dataFim'))
      && $F('dataFim') < $F('dataInicio')
    ) {

    alert(_M(MENSAGENS_SAU2_AGENDAMEDICA + 'periodo_inicial_maior'));
    oDataFim.setValue('');
  }
});

oAncoraProfissional.setCallBack('onClick', function() {

  oToggleEspecialidade.clearAll();
  buscaEspecialidadesMedico();
});

oAncoraProfissional.setCallBack('onChange', function() {
  buscaEspecialidadesMedico();
});

$('sd03_i_codigo').addEventListener('change', function() {
  oToggleEspecialidade.clearAll();
});

$('gerarRelatorio').observe('click', function() {
  gerarRelatorio();
});

$('sd03_i_codigo').className = 'field-size2';
$('z01_nome').className      = 'readonly field-size7';
</script>

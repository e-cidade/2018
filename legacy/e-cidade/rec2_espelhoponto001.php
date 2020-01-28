<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
  db_app::load("scripts.js");
  db_app::load("strings.js");
  db_app::load("prototype.js");
  db_app::load("estilos.css");
  db_app::load("widgets/DBLancador.widget.js");
  db_app::load("widgets/DBLookUp.widget.js");
  db_app::load("widgets/Input/DBInput.widget.js");
  db_app::load("widgets/Input/DBInputDate.widget.js");
  db_app::load("AjaxRequest.js");
  db_app::load("classes/recursoshumanos/Efetividade/PeriodoEfetividade.js");
  db_app::load("EmissaoRelatorio.js");
  ?>
</head>
<body>
  <div class="container">
    <form>
      <fieldset>
        <legend>Espelho Ponto</legend>

        <table class="form-container" cellspacing="1">

          <tr>
            <td>
              <label for="dataInicio">Período:</label>
            </td>
            <td id="linhaPeriodo" colspan="2"></td>
          </tr>
          <tr>
            <td>
              <label for="exibeMarcacoes">Exibir Todos os Eventos da Batida:</label>
            </td>
            <td colspan="2" class="field-size-max">
              <select id="exibeMarcacoes">
                <option value="1" selected>Sim</option>
                <option value="2">Não</option>
              </select>
            </td>
          </tr>
          <tr>
            <td>
              <label for="observacoesSim">Mostrar Observações:</label>
            </td>
            <td>
              <input id="observacoesSim" name="observacoes" type="radio" value="S" checked="checked" />Sim
              <input id="observacoesNao" name="observacoes" type="radio" value="N" />Não
            </td>
          </tr>

          <tr>
            <td>
              <label for="tipoPesquisa">Filtrar Por:</label>
            </td>
            <td colspan="2" class="field-size-max">
              <select id="tipoPesquisa">
                <option value="1">Seleção</option>
                <option value="2">Matrículas</option>
              </select>
            </td>
          </tr>          
          
          <tr id="linhaSelecao">
            <td>
              <label for="r44_selec">
                <a href="#" id="ancoraSelecao">Seleção:</a>
              </label>
            </td>
            <td>
              <input id="r44_selec" type="text" value="" class="field-size2" />
            </td>
            <td>
              <input id="r44_descr" type="text" value="" class="field-size8 readonly" disabled="disabled" />
            </td>
          </tr>

          <tr id="linhaMatriculas" style="display: none;">
            <td id="linhaLancadorMatriculas" colspan="3"></td>
          </tr>
        </table>

      </fieldset>

      <input id="imprimir" type="button" value="Imprimir" onclick="imprimirEspelhoPonto();" />
    </form>
  </div>
</body>
<?php db_menu(); ?>
<script>

var oPeriodo = new PeriodoEfetividade();
    oPeriodo.show($('linhaPeriodo'));

var oLookupSelecao = new DBLookUp(
  $('ancoraSelecao'),
  $('r44_selec'),
  $('r44_descr'),
  {
    'sArquivo': 'func_selecao.php',
    'sLabel'  : 'Pesquisar Seleção'
  }
);

var oLancadorMatriculas = new DBLancador('oLancadorMatriculas');
    oLancadorMatriculas.setNomeInstancia('oLancadorMatriculas');
    oLancadorMatriculas.setLabelAncora('Matrícula: ');
    oLancadorMatriculas.setParametrosPesquisa('func_rhpessoal.php', ['rh01_regist','z01_nome'], "");
    oLancadorMatriculas.show($('linhaLancadorMatriculas'));

$('tipoPesquisa').observe('change', function() {

  $('linhaSelecao').setStyle({'display': ''});
  $('linhaMatriculas').setStyle({'display': 'none'});

  if($F('tipoPesquisa') == 2) {

    $('linhaSelecao').setStyle({'display': 'none'});
    $('linhaMatriculas').setStyle({'display': ''});
  }
});

function validaCampos() {

  if(!oPeriodo.validarPreenchimentoPeriodo()) {
    alert('Informe as datas do período')
    return false;
  }

  if($F('tipoPesquisa') == 1 && empty($F('r44_selec'))) {

    alert('Selecione uma Seleção.');
    return false;
  }

  if($F('tipoPesquisa') == 2 && oLancadorMatriculas.getRegistros().length == 0) {

    alert('Selecione ao menos uma Matrícula.');
    return false;
  }

  return true;
}

function imprimirEspelhoPonto() {

  if(!validaCampos()) {
    return false;
  }

  var iCodigoSelecao     = $F('r44_selec');
  var aMatriculas        = [];
  var lMostraObservacoes = $$("input[type=radio]:checked")[0].value == 'S';

  if($F('tipoPesquisa') == 2 && oLancadorMatriculas.getRegistros().length > 0) {

    iCodigoSelecao = '';

    oLancadorMatriculas.getRegistros().each(function(oRegistros) {
      aMatriculas.push(oRegistros.sCodigo);
    });
  }

  var oRelatorio = new EmissaoRelatorio(
    'rec2_espelhoponto002.php',
    {
      'sDataInicio'             : oPeriodo.getDataFormatada(oPeriodo.getDataInicio()),
      'sDataFim'                : oPeriodo.getDataFormatada(oPeriodo.getDataFim()),
      'aMatriculas'             : aMatriculas,
      'iCodigoSelecao'          : iCodigoSelecao,
      'lMostraObservacoes'      : lMostraObservacoes,
      'iEmiteTodosAfastamentos' : $F('exibeMarcacoes')
    }
  );
  oRelatorio.open();
}
</script>
</html>
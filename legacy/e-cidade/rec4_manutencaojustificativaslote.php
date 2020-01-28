<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBSeller Servicos de Informatica
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

use ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\Repository\Justificativa as JustificativaRepository;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$aTiposAssentamento       = TipoAssentamentoRepository::getInstanciasPorNaturezaComJustificativaConfigurada();
$justificativaRepository  = new JustificativaRepository();
$aJustificativas           = array();

foreach ($aTiposAssentamento as $tipoAssentamento) {
  $justificativa = $justificativaRepository->getJustificativaPorTipoAssentamento($tipoAssentamento->getSequencial());
  $aJustificativas[$tipoAssentamento->getSequencial()] = $justificativa->getAbreviacao() .' - '. $tipoAssentamento->getDescricao();
}
?>

<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <link href="estilos/DBFormularios.css" rel="stylesheet" type="text/css">
  <script src="scripts/scripts.js" type="text/javascript"></script>
  <script src="scripts/prototype.js" type="text/javascript"></script>
</head>
<body>
<div class="container">
  <form>
    <fieldset>
      <legend>Lançamento de Justificativas em Lote</legend>

      <table class="form-container">
        <tr>
          <td>
            <label for="dataInicio">Data inicial:</label>
          </td>
          <td>
            <input id="dataInicio"/>
          </td>
        </tr>

        <tr>
          <td>
            <label for="dataFim">Data final:</label>
          </td>
          <td>
            <input id="dataFim"/>
          </td>
        </tr>
        <tr>
          <td>
            <label for="tipoassentamento">Justificativa:</label>
          </td>
          <td>
            <?php db_select('tipoassentamento', $aJustificativas, true, 0); ?>
          </td>
        </tr>
        <tr>
          <td>
            <label for="tipoFiltro">Filtrar por:</label>
          </td>
          <td colspan="2" class="field-size-max">
            <select id="tipoFiltro" style="width: 84px;">
              <option value="1" selected>Seleção</option>
              <option value="2">Matrícula</option>
            </select>
          </td>
        </tr>

        <tr id="linhaSelecao">
          <td>
            <label for="r44_selec">
              <a href="#" id="selecao">Seleção:</a>
            </label>
          </td>
          <td>
            <input id="r44_selec" type="text" value=""/>
            <input id="r44_descr" type="text" value=""/>
          </td>
        </tr>

        <tr style="display: none;" id="linhaMatricula">
          <td id="matricula" colspan="3"></td>
        </tr>

        <tr class="celulas-periodos-justificativa">
          <td>
            Períodos da Justificativa:
          </td>
          <td>
            <input type="checkbox" value="1" id="periodoJustificativa1" name="periodoJustificativa1"/>
            <label for="periodoJustificativa1">Entrada 1 - Saída 1</label>
          </td>
        </tr>
        <tr class="celulas-periodos-justificativa">
          <td></td>
          <td>
            <input type="checkbox" value="2" id="periodoJustificativa2" name="periodoJustificativa2"/>
            <label for="periodoJustificativa2">Entrada 2 - Saída 2</label>
          </td>
        </tr>
        <tr class="celulas-periodos-justificativa">
          <td></td>
          <td>
            <input type="checkbox" value="3" id="periodoJustificativa3" name="periodoJustificativa3"/>
            <label for="periodoJustificativa3">Entrada 3 - Saída 3</label>
          </td>
        </tr>

      </table>
    </fieldset>

    <input id="salvar" type="button" value="Salvar"/>
    <input id="limpar" type="button" value="Limpar"/>

  </form>
</div>
</body>

<?php db_menu(); ?>

<script type="text/javascript">

  require_once('scripts/widgets/Input/DBInput.widget.js');
  require_once("scripts/widgets/Input/DBInputDate.widget.js");
  require_once("scripts/widgets/DBInputHora.widget.js");
  require_once("scripts/widgets/DBLookUp.widget.js");
  require_once("scripts/widgets/DBLancador.widget.js");
  require_once("scripts/AjaxRequest.js");
  require_once("scripts/EmissaoRelatorio.js");

  var aMatriculasEnviar = [];
  var dataInicio        = new DBInputDate($('dataInicio'));
  var dataFim           = new DBInputDate($('dataFim'));

  /**
   * Ancora da seleção
   */
  new DBLookUp(
    $('selecao'),
    $('r44_selec'),
    $('r44_descr'),
    {
      'sArquivo': 'func_selecao.php',
      'sLabel': 'Pesquisa de Seleção'
    }
  );

  var oLancadorMatricula = new DBLancador('oLancadorMatricula');
  oLancadorMatricula.setLabelAncora('Matrícula:');
  oLancadorMatricula.setNomeInstancia('oLancadorMatricula');
  oLancadorMatricula.setTituloJanela('Pesquisa de Matrícula');
  oLancadorMatricula.setParametrosPesquisa('func_rhpessoal.php', ['rh01_regist', 'z01_nome']);
  oLancadorMatricula.setTextoFieldset('Matrículas');
  oLancadorMatricula.setGridHeight(150);
  oLancadorMatricula.show($('matricula'));

  $('tipoFiltro').observe('change', function() {

    $('linhaSelecao').setStyle({'display': 'none'});
    $('linhaMatricula').setStyle({'display': 'none'});

    /**
     * Filtrar por Seleção
     */
    if($F('tipoFiltro') == 1) {

      $('linhaSelecao').setStyle({'display': ''});
      $('linhaMatricula').setStyle({'display': 'none'});
      oLancadorMatricula.clearAll();
    }

    /**
     * Filtrar por Matrícula
     */
    if($F('tipoFiltro') == 2) {

      $('linhaSelecao').setStyle({'display': 'none'});
      $('linhaMatricula').setStyle({'display': ''});
      $('r44_selec').value = '';
      $('r44_descr').value = '';
    }
  });

  $('limpar').observe('click', function() {
    location.href = 'rec4_manutencaojustificativaslote.php';
  });

  $('salvar').observe('click', function () {
    if(!verificaCampos()){
      return false;
    }

    aMatriculasEnviar = [];
    oLancadorMatricula.getRegistros().each(function(matricula) {
      aMatriculasEnviar.push(matricula.sCodigo);
    });

    var sDataInicio = $F('dataInicio');
    var sDataFim    = $F('dataFim');

    if(sDataFim == '' || sDataFim == null) {
      sDataFim = sDataInicio;
    }

    var oAjaxRequest = new AjaxRequest(
      'rec4_pontoeletronico.RPC.php',
      {
        'exec'                  : 'criarAssentamentosJustificativas',
        'selecao'               : $F('r44_selec'),
        'matriculas'            : aMatriculasEnviar,
        'dataInicio'            : js_formatar(sDataInicio, 'd'),
        'dataFim'               : js_formatar(sDataFim, 'd'),
        'tipoassentamento'      : $F('tipoassentamento'),
        'tipoFiltro'            : $F('tipoFiltro'),
        'periodoJustificativa1' : $F('periodoJustificativa1'),
        'periodoJustificativa2' : $F('periodoJustificativa2'),
        'periodoJustificativa3' : $F('periodoJustificativa3')
      },
      function(oRetorno, lErro) {

        if(!oRetorno.lTemInconsistencias) {
          alert(oRetorno.mensagem);
        }

        /**
         * Quando foram encontradas inconsistências, apresenta a possibilidade de imprimir o relatório com os casos
         * encontrados
         */
        if(!lErro && oRetorno.lTemInconsistencias && confirm(oRetorno.mensagem)) {

          var oRelatorioInconsistencia = new EmissaoRelatorio('rec2_pontoeletronicoinconsistencias002.php', {
            'data': $F('dataInicio') +' - '+ $F('dataFim')
          });
          
          oRelatorioInconsistencia.open();
        }
      }
    ).setMessage("Aguarde... Salvando assentamentos lançados.").execute();
  });

  function verificaCampos(){

    if($F('dataInicio') == ""){

      alert('Campo Data inicial é obrigatório');
      $('dataInicio').focus();
      return false;
    }

    if($F('dataFim') != ""){

      if(new Date($F('dataInicio')) > new Date($F('dataFim'))){

        alert('Data inicial deve ser menor ou igual a data final.');
        return false;
      }
    }

    if($F('tipoFiltro') == 1 && $F('r44_selec') == '') {

      alert('Campo Seleção é de preenchimento obrigatório.');
      return false;
    }

    if($F('tipoFiltro') == 2 && oLancadorMatricula.getRegistros().length == 0) {

      alert('Informe ao menos uma Matrícula.');
      return false;
    }

    if($('periodoJustificativa1').checked === false && $('periodoJustificativa2').checked === false && $('periodoJustificativa3').checked === false){

      alert('Informe um período para a Justificativa.');
      return false;
    }

    return true;
  }
</script>
</html>

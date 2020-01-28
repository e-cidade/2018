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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
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
      <legend>Manutenção do Ponto Eletrônico em Lote</legend>

      <table class="form-container">
        <tr>
          <td>
            <label for="dataPonto">Data:</label>
          </td>
          <td>
            <input id="dataPonto"/>
          </td>
        </tr>

        <tr>
          <td>
            <label for="marcacoes">Sobrescrever Marcações Existentes:</label>
          </td>
          <td id="linhaMarcacoes">
            <select id="marcacoes" style="width: 84px;">
              <option value="f">Não</option>
              <option value="t">Sim</option>
            </select>
          </td>
        </tr>

        <tr>
          <td>
            <label for="tipoFiltro">Filtrar por:</label>
          </td>
          <td colspan="2" class="field-size-max">
            <select id="tipoFiltro" style="width: 84px;">
              <option value="1">Seleção</option>
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

        <tr>
          <td colspan="2">
            <fieldset class="separator">
              <legend>Horários</legend>

              <table>
                <tr>
                  <td>
                    <label for="entrada1">Entrada 1:</label>
                  </td>
                  <td>
                    <input id="entrada1"/>
                  </td>

                  <td>
                    <label for="saida1">Saída 1:</label>
                  </td>
                  <td>
                    <input id="saida1"/>
                  </td>
                </tr>

                <tr>
                  <td>
                    <label for="entrada2">Entrada 2:</label>
                  </td>
                  <td>
                    <input id="entrada2"/>
                  </td>

                  <td>
                    <label for="saida2">Saída 2:</label>
                  </td>
                  <td>
                    <input id="saida2"/>
                  </td>
                </tr>

                <tr>
                  <td>
                    <label for="entrada3">Entrada 3:</label>
                  </td>
                  <td>
                    <input id="entrada3"/>
                  </td>

                  <td>
                    <label for="saida3">Saída 3:</label>
                  </td>
                  <td>
                    <input id="saida3"/>
                  </td>
                </tr>
              </table>

            </fieldset>
          </td>
        </tr>

      </table>
    </fieldset>

    <input id="processar" type="button" value="Processar"/>
    <input id="limpar" type="button" value="Limpar"/>

  </form>
</div>
</body>
<?php
db_menu();
?>
<script type="text/javascript">

  require_once('scripts/widgets/Input/DBInput.widget.js');
  require_once("scripts/widgets/Input/DBInputDate.widget.js");
  require_once("scripts/widgets/DBInputHora.widget.js");
  require_once("scripts/widgets/DBLookUp.widget.js");
  require_once("scripts/widgets/DBLancador.widget.js");
  require_once("scripts/AjaxRequest.js");
  require_once("scripts/EmissaoRelatorio.js");

  var aMatriculasEnviar = [];
  var dataPonto         = new DBInputDate($('dataPonto'));

  /**
   * Elementos da hora
   */
  new DBInputHora($('entrada1'));
  new DBInputHora($('saida1'));
  new DBInputHora($('entrada2'));
  new DBInputHora($('saida2'));
  new DBInputHora($('entrada3'));
  new DBInputHora($('saida3'));

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

  /**
   * DBLancador para as matrículas
   */
  var oLancadorMatricula = new DBLancador('oLancadorMatricula');
  oLancadorMatricula.setLabelAncora('Matrícula:');
  oLancadorMatricula.setNomeInstancia('oLancadorMatricula');
  oLancadorMatricula.setTituloJanela('Pesquisa de Matrícula');
  oLancadorMatricula.setParametrosPesquisa('func_rhpessoal.php', ['rh01_regist', 'z01_nome']);
  oLancadorMatricula.setTextoFieldset('Matrículas');
  oLancadorMatricula.setGridHeight(150);
  oLancadorMatricula.show($('matricula'));

  /**
   * Estilização dos elementos
   */
  $('entrada1').addClassName('field-size1');
  $('saida1').addClassName('field-size1');
  $('entrada2').addClassName('field-size1');
  $('saida2').addClassName('field-size1');
  $('entrada3').addClassName('field-size1');
  $('saida3').addClassName('field-size1');
  $('tipoFiltro').addClassName('field-size-max');
  $('r44_selec').addClassName('field-size2');
  $('r44_descr').addClassName('field-size7');
  $('r44_descr').addClassName('readOnly');
  $('r44_descr').setAttribute('disabled', 'disabled');
  $('dataPonto').addClassName('field-size2');

  /**
   * Controla o filtro selecionado e o que deve ser apresentado
   */
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

  /**
   * Cria/altera as marcações lançadas
   */
  $('processar').observe('click', function() {

    if(!validaCampos()) {
      return false;
    }

    var mensagem  = 'Você selecionou a opção de sobrescrever marcações existentes, tem certeza que deseja';
    mensagem += ' sobrescrevê-las com os novos horários informados?';

    if($F('marcacoes') == 't' && !confirm(mensagem)) {
      return false;
    }

    var aHorarios = [
      $F('entrada1'),
      $F('saida1'),
      $F('entrada2'),
      $F('saida2'),
      $F('entrada3'),
      $F('saida3')
    ];

    aMatriculasEnviar.length = 0;

    oLancadorMatricula.getRegistros().each(function(matricula) {
      aMatriculasEnviar.push(matricula.sCodigo);
    });

    new AjaxRequest(
      'rec4_pontoeletronico.RPC.php',
      {
        'exec'                 : 'criarMarcacoesEmLote',
        'selecao'              : $F('r44_selec'),
        'matriculas'           : aMatriculasEnviar,
        'datas'                : [js_formatar($F('dataPonto'), 'd')],
        'sobrescreverMarcacao' : $F('marcacoes'),
        'horarios'             : aHorarios
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

          var oRelatorioInconsistencia = new EmissaoRelatorio('rec2_pontoeletronicoinconsistencias002.php', {'data': $F('dataPonto')});
          oRelatorioInconsistencia.open();
        }

        aMatriculasEnviar = oRetorno.matriculas;

        if(aMatriculasEnviar.length > 0) {
          buscaRegistrosPonto();
        }
      }
    ).setMessage("Aguarde... Salvando horários lançados.").execute();
  });

  $('limpar').observe('click', function() {
    location.href = 'rec4_pontoeletronicomanutencaolote001.php';
  });

  /**
   * Após criar as marcações, chama a requisição que busca os registros, para em seguida chamar o "processar", onde serão
   * criados os vínculos de assentamentos para as marcações
   */
  function buscaRegistrosPonto() {

    new AjaxRequest(
      'rec4_pontoeletronico.RPC.php',
      {
        'exec'         : 'buscaRegistrosPonto',
        'periodo'      : {
          'dataInicio' : $F('dataPonto'),
          'dataFim'    : $F('dataPonto')
        },
        'matriculas'  : aMatriculasEnviar
      },
      function(oRetorno, lErro) {

        if(lErro) {

          alert(oRetorno.mensagem);
          return false;
        }

        var matriculas = [];

        /**
         * Percorre os registros das matrículas retornadas
         */
        oRetorno.aDados.each(function(dadosPonto) {

          var dadosMatricula = {
            'matricula'   : dadosPonto.dados.matricula,
            'codigo_data' : dadosPonto.datas[0].codigo_data,
            'data'        : dadosPonto.datas[0].data.replace(new RegExp('_', 'g'), '/'),
            'aMarcacoes'  : []
          };

          /**
           * Percorre as marcações, organizando os dados a serem enviados para o salvar
           */
          dadosPonto.datas[0].aMarcacoes.each(function(marcacao) {

            var marcacaoEntrada = {
              'codigo'   : marcacao.oEntrada.codigo,
              'data'     : marcacao.oEntrada.data,
              'hora'     : marcacao.oEntrada.hora,
              'alterado' : marcacao.oEntrada.manual
            };

            dadosMatricula.aMarcacoes.push(marcacaoEntrada);

            var marcacaoSaida = {
              'codigo'   : marcacao.oSaida.codigo,
              'data'     : marcacao.oSaida.data,
              'hora'     : marcacao.oSaida.hora,
              'alterado' : marcacao.oSaida.manual
            };

            dadosMatricula.aMarcacoes.push(marcacaoSaida);
          });

          matriculas.push(dadosMatricula);
        });

        salvarRegistrosPonto(matriculas);
      }
    ).setMessage('Aguarde, vinculando os lançamentos ao ponto...')
      .execute();
  }

  /**
   * Salva as marcações criando os vínculos corretos com assentamentos, por exemplo
   */
  function salvarRegistrosPonto(matriculas) {

    new AjaxRequest(
      'rec4_pontoeletronico.RPC.php',
      {
        'exec'         : 'salvarRegistrosPonto',
        'periodo'      : {
          'dataInicio' : $F('dataPonto'),
          'dataFim'    : $F('dataPonto')
        },
        'aDados'       : matriculas
      },
      function(oRetorno, lErro) {

        if(lErro) {
          alert(oRetorno.mensagem);
        }
      }
    ).setMessage('Aguarde, vinculando os lançamentos ao ponto...')
      .execute();
  }

  /**
   * Validações de campos antes de persistir os horários
   * @returns {boolean}
   */
  function validaCampos() {

    if(dataPonto.getValue() == null) {

      alert('Campo Data é de preenchimento obrigatório.');
      return false;
    }

    if($F('tipoFiltro') == 1 && $F('r44_selec') == '') {

      alert('Campo Seleção é de preenchimento obrigatório.');
      return false;
    }

    if($F('tipoFiltro') == 2 && oLancadorMatricula.getRegistros().length == 0) {

      alert('Informe ao menos uma Matrícula.');
      return false;
    }

    if($F('entrada1') == '' && $F('saida1') == '' && $F('entrada2') == '' && $F('saida2') == '' && $F('entrada3') == '' && $F('saida3') == '') {

      alert('Informe ao menos um Horário para manutenção do ponto eletrônico.');
      return false;
    }

    return true;
  }
</script>
</html>
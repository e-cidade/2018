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

require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

$iEscola = db_getsession("DB_coddepto");

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
    db_app::load('scripts.js');
    db_app::load('prototype.js');
    db_app::load('strings.js');
    db_app::load('DBFormCache.js');
    db_app::load('DBFormSelectCache.js');
    db_app::load('classes/educacao/escola/ListaCalendario.classe.js');
    db_app::load('classes/educacao/escola/ListaTurma.classe.js');
    db_app::load('classes/educacao/escola/ListaPeriodoAvaliacao.classe.js');
    db_app::load('classes/educacao/escola/ListaDisciplinas.classe.js');
    db_app::load('widgets/DBToggleList.widget.js');
  ?>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <style>
    .DBToggleListBox .toggleListActionButons {
      margin:8% 0 10px 7%;
    }
  </style>
</head>
<body class="body-default">
  <div class="container">
    <form>
      <fieldset>
        <legend>Relatório Diário de Classe</legend>
        <table class="form-container">
          <tr>
            <td class="field-size5">
              <label>Selecione o Calendário:</label>
            </td>
            <td id="listaCalendarios">
            </td>
          </tr>
          <tr>
            <td class="field-size5">
              <label>Selecione a Turma:</label>
            </td>
            <td id="listaTurmas">
            </td>
          </tr>
          <tr>
            <td class="field-size5">
              <label>Selecione o Período de Avaliação:</label>
            </td>
            <td id="listaPeriodos" class="field-size-max">
            </td>
          </tr>
        </table>

        <fieldset class='separator'>
          <legend>Disciplinas</legend>
          <div id='listaDisciplinas' style="padding-left: 10%;"></div>
        </fieldset>

        <table class="form-container">
          <tr>
            <td colspan="2">
              <fieldset class="separator">
                <legend>Configuração do Relatório</legend>
                <table>
                  <tr>
                    <td>
                      <label>Selecione um modelo:</label>
                    </td>
                    <td class="field-size-max">
                      <select id="listaModelos" onchange="liberaFiltrosPorModelo();">
                        <option value="1">Modelo 1 - Uma disciplina por página (Área)</option>
                        <option value="2" disabled="disabled">Modelo 2 - Todas disciplinas em uma página (Currículo)</option>
                        <option value="3">Modelo 3 - Duas páginas por disciplina (Página 1 - Presenças / Página 2 - Avaliações)</option>
                        <option value="4" disabled="disabled">Modelo 4 - Turma EJA</option>
                      </select>
                    </td>
                  </tr>
                </table>
              </fieldset>
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <fieldset class="separator">
                <legend>Exibir Colunas</legend>
                <table>
                  <tr>
                    <td>
                      <label for="avaliacoes">
                        <input id="avaliacoes" type="checkbox" name="colunas" >
                        Avaliações
                      </label>
                      <label for="dataPeriodo">
                        <input id="dataPeriodo" type="checkbox" name="colunas">
                        Data do Período
                      </label>
                      <label for="totalFaltas">
                        <input id="totalFaltas" type="checkbox" name="colunas" >
                        Total de Faltas
                      </label>
                      <label for="sexo">
                        <input id="sexo" type="checkbox" name="colunas" >
                        Sexo
                      </label>
                      <label for="idade">
                        <input id="idade" type="checkbox" name="colunas" >
                        Idade
                      </label>
                      <label for="faltasAbonadas">
                        <input id="faltasAbonadas" type="checkbox" name="colunas" >
                        Faltas Abonadas
                      </label>
                      <label for="codigo">
                        <input id="codigo" type="checkbox" name="colunas" >
                        Código
                      </label>
                      <label for="nascimento">
                        <input id="nascimento" type="checkbox" name="colunas" >
                        Nascimento
                      </label>
                      <label for="resultadoAnterior">
                        <input id="resultadoAnterior" type="checkbox" name="colunas" >
                        Resultado Anterior
                      </label>
                      <label for="parecer">
                        <input id="parecer" type="checkbox" name="colunas" >
                        Parecer
                      </label>
                    </td>
                  </tr>
                </table>
              </fieldset>
            </td>
          </tr>
          <tr>
            <td>
              <label>Registro:</label>
            </td>
            <td>
              <select id="registro">
                <option value="M">Manual</option>
                <option value="F">Frequência / Conteúdo</option>
              </select>
            </td>
          </tr>
          <tr>
            <td>
              <label>Exibir Pontos</label>
            </td>
            <td>
              <select id="exibirPontos">
                <option value="S">SIM</option>
                <option value="N">NÃO</option>
              </select>
            </td>
          </tr>
          <tr>
            <td class="field-size7">
              <label>Informar Dias Letivos:</label>
            </td>
            <td>
              <select id="diasLetivos" onchange="mostraQuantidadeColunas();">
                <option value="S">SIM</option>
                <option value="N">NÃO</option>
              </select>
            </td>
          </tr>
          <tr>
            <td>
               <label>Quantidade de Colunas (Presenças):</label>
            </td>
            <td>
              <select id="quantidadeColunas" disabled="disabled">
              </select>
            </td>
          </tr>
          <tr>
            <td>
              <label>Mostrar somente alunos ativos (Matriculados):</label>
            </td>
            <td>
              <select id="alunosAtivos">
                <option value="S">SIM</option>
                <option value="N">NÃO</option>
              </select>
            </td>
          </tr>
          <tr>
            <td>
              <label>Exibir Trocas de Turma:</label>
            </td>
            <td>
             <select id="trocaTurma">
                <option value="S">SIM</option>
                <option value="N">NÃO</option>
              </select>
            </td>
          </tr>
        </table>
      </fieldset>
      <input type="hidden" value=<?php echo $iEscola ?> id="iEscola">
      <input type="button" id="imprimir" name="imprimir" value="Imprimir" onclick="validaDados();">
    </form>
  </div>
</body>
<?php
db_menu();
?>
</html>
<script>

const MENSAGENS_DIARIO_CLASSE_NOVO = 'educacao.escola.edu2_diarioclassenovo001.';

var aColunas             = document.getElementsByName('colunas');
var lTemDisciplinaGlobal = false;
var iEscola              = $F("iEscola");
var oTurma               = new DBViewFormularioEducacao.ListaTurma();
var oPeriodo             = new DBViewFormularioEducacao.ListaPeriodoAvaliacao();
    oPeriodo.somentePeriodoCalculaCargaHoraria( true );
var oDisciplina          = new DBViewFormularioEducacao.ListaDisciplinas();
var oCalendario          = new DBViewFormularioEducacao.ListaCalendario();
var sRpc                 = "edu4_turmas.RPC.php";

oTurma.show( $('listaTurmas') );
oPeriodo.show( $('listaPeriodos') );
oDisciplina.show( $('listaDisciplinas') );

for ( var oElemento in aColunas ) {

  aColunas[oElemento].checked  = false;
  aColunas[oElemento].disabled = true;

  if ( aColunas[oElemento].id == "avaliacoes" || aColunas[oElemento].id == "totalFaltas" || aColunas[oElemento].id == "dataPeriodo") {

    aColunas[oElemento].checked  = true;
    aColunas[oElemento].disabled = false;
  }
}

/**
 * Preenche o combo da quantidade de colunas a serem exibidas
 */
function preencheQuantidadeColunas() {

  for ( var iContador = 30; iContador <= 70; iContador++ ) {
    $("quantidadeColunas").add( new Option(iContador, iContador) );
  }
}

/**
 * Controla se o select de quantidade de colunas deve estar habilitado
 */
function mostraQuantidadeColunas() {

  $("quantidadeColunas").disabled = true;
  $("quantidadeColunas").value    = 30;

  if ( $("diasLetivos").value == 'N' ) {
    $("quantidadeColunas").disabled = false;
  }
}

/**
 * Controla os filtros que devem ser liberados, de acordo com o modelo selecionado
 */
function liberaFiltrosPorModelo() {

  for ( var oElemento in aColunas ) {

    aColunas[oElemento].checked  = false;
    aColunas[oElemento].disabled = true;

    if (    aColunas[oElemento].id == "avaliacoes"
         || aColunas[oElemento].id == "totalFaltas"
         || aColunas[oElemento].id == "dataPeriodo") {

      aColunas[oElemento].checked  = true;
      aColunas[oElemento].disabled = false;
    }
  }

  if ( $("listaModelos").value == 2 ) {

    for ( var oElemento in aColunas ) {

      aColunas[oElemento].checked  = false;
      aColunas[oElemento].disabled = true;
    }
  }

  if ( $("listaModelos").value == 3 ) {

    for ( var oElemento in aColunas ) {

      aColunas[oElemento].checked  = true;
      aColunas[oElemento].disabled = false;

      if ( aColunas[oElemento].id == "avaliacoes" || aColunas[oElemento].id == "dataPeriodo" ) {

        aColunas[oElemento].checked  = false;
        aColunas[oElemento].disabled = true;
      }
    }
  }

  if ($("listaModelos").value == 4) {

    for ( var oElemento in aColunas ) {

      aColunas[oElemento].checked  = false;
      aColunas[oElemento].disabled = true;

      if ( aColunas[oElemento].id == "avaliacoes" || aColunas[oElemento].id == "totalFaltas") {

        aColunas[oElemento].checked  = true;
        aColunas[oElemento].disabled = false;
      }
    }
  }

}

/**
 * Busca os calendários vinculados a escola logada
 */
function buscaCalendario() {

  oCalendario.setEscola(iEscola);
  oCalendario.getCalendarios();

  /**
   * Função realizada ao alterar o calendário
   * @return {function}
   */
  var functionChangeCalendario = function() {

    limpaElementos();

    var oCalendarioSelecionado = oCalendario.getSelecionados();

    if ( oCalendarioSelecionado.iCalendario != "" ) {
      buscaTurma(oCalendarioSelecionado);
    }
  };

  /**
   * Função chamada ao trazer os calendários
   * @return {[type]} [description]
   */
  var functionLoadCalendario = function() {

    var oCalendarioSelecionado = oCalendario.getSelecionados();

    if ( oCalendarioSelecionado.iCalendario != "" ) {
      buscaTurma(oCalendarioSelecionado);
    }
  };

  oCalendario.setOnChangeCallBack(functionChangeCalendario);
  oCalendario.setCallBackLoad(functionLoadCalendario);
  oCalendario.show($('listaCalendarios'));
}

/**
 * Busca as turmas vinculadas ao calendário selecionado
 * @param  {Object} oCalendarioSelecionado
 */
function buscaTurma( oCalendarioSelecionado ) {

  limpaElementos();
  oTurma.setEscola(iEscola);
  oTurma.setCalendario(oCalendarioSelecionado.iCalendario);

  /**
   * Função chamada ao trazer os dados da turma
   */
  var functionLoadTurma = function() {

    var oTurmaSelecionada = oTurma.getSelecionados();

    if ( oTurmaSelecionada.codigo_turma != "" ) {

      validaTurma( oTurmaSelecionada.codigo_turma, oTurmaSelecionada.codigo_etapa );
      oPeriodo.getPeriodos(oTurmaSelecionada.codigo_turma, oTurmaSelecionada.codigo_etapa, 2);
    }
  };

  /**
   * Função ao alterar a turma selecionada
   */
  var functionChangeTurma = function() {

    oPeriodo.limpaElemento();
    oDisciplina.clear();

    var oTurmaSelecionada   = oTurma.getSelecionados();
    $("listaModelos").value = 1;

    if ( oTurmaSelecionada.codigo_turma != "" ) {

      validaTurma( oTurmaSelecionada.codigo_turma, oTurmaSelecionada.codigo_etapa );
      oPeriodo.getPeriodos(oTurmaSelecionada.codigo_turma, oTurmaSelecionada.codigo_etapa, 2);
    }
  };

  oTurma.setCallBackLoad(functionLoadTurma);
  oTurma.setCallbackOnChange(functionChangeTurma);
  oTurma.getTurmas();
}

/**
 * Buscas as seguintes informações da turma:
 *   -> lTipoEja             - Verifica se a turma é do tipo EJA
 *   -> lTemDisciplinaGlobal - Verifica se o controle de frequência da turma é individual ou globalizada
 * @param  {integer} iTurma
 * @param  {integer} iEtapa
 */
function validaTurma( iTurma, iEtapa ) {

  var oParametro        = new Object();
      oParametro.exec   = 'getInformacoesTurma';
      oParametro.iTurma = iTurma;
      oParametro.iEtapa = iEtapa;

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json=' + Object.toJSON( oParametro );
      oDadosRequisicao.onComplete = retornoValidaTurma;

  js_divCarregando( _M( MENSAGENS_DIARIO_CLASSE_NOVO + "validando_turma" ), "msgBox" );
  new Ajax.Request( sRpc, oDadosRequisicao );
}

/**
 * Verifica se o tipo da turma é igual a EJA e libera modelo de relatório  "Turma EJA"
 * Verifica se a frequência da turma é individual ou globalizada e busca as disciplinas
 * @param  {Object} oResponse
 */
function retornoValidaTurma( oResponse ) {

  js_removeObj( "msgBox" );
  var oRetorno = eval( '(' + oResponse.responseText + ')' );

  $('listaModelos').options[1].disabled = true;
  $('listaModelos').options[1].selected = false;
  $('listaModelos').options[3].disabled = true;

  if ( oRetorno.lTipoEja ) {
    $('listaModelos').options[3].disabled = false;
  }

  lTemDisciplinaGlobal = false;

  if ( oRetorno.lFrequenciaGlobal ) {

    lTemDisciplinaGlobal = true;
    $('listaModelos').options[1].disabled = false;
  }

  buscaDisciplina( oRetorno.iTurma, oRetorno.iEtapa );
}

/**
 * Busca as disciplinas da turma.
 * Foi alterado, para buscar todas disciplinas da turma, não importando mais se a disciplina não controla frequência
 * @param  {integer} iTurma
 * @param  {integer} iEtapa
 */
function buscaDisciplina( iTurma, iEtapa ) {

  oDisciplina.clear();
  oDisciplina.setSomenteDisciplinasGlobais( false );
  oDisciplina.getDisciplinas( iTurma, iEtapa, false );
}

$('registro').onchange = function() {
  validaTipoRegistro();
}

/**
 * Valida se mostra ou oculta os campos
 *   -> Exibir Pontos
 *   -> Dias Letivos
 *   -> Quantidade de Colunas
 * Conforme o tipo de registro (Manual ou Frequência/Conteúdo)
 */
function validaTipoRegistro() {

  $('exibirPontos').disabled      = false;
  $('diasLetivos').disabled       = false;
  $('quantidadeColunas').disabled = true;

  if ( $F('registro') == "F") {

    $('exibirPontos').value         = "S";
    $('diasLetivos').value          = "S";
    $('quantidadeColunas').value    = 30;
    $('exibirPontos').disabled      = true;
    $('diasLetivos').disabled       = true;
    $('quantidadeColunas').disabled = true;
  }
}

/**
 * Valida se os dados obrigatórios estão setados para imprimir o relatório. Campos obrigatórios:
 *   -> Calendário
 *   -> Turma
 *   -> Disciplina
 *   -> Período
 */
function validaDados() {

  var iCalendarioSelecionado  = oCalendario.getSelecionados().iCalendario;
  var iTurmaSelecionada       = oTurma.getSelecionados().codigo_turma;
  var iPeriodoSelecionado     = oPeriodo.getSelecionado().iCodigo;
  var aDisciplinaSelecionadas = oDisciplina.getSelecionados();

  if ( iCalendarioSelecionado == "" ) {

    alert( _M( MENSAGENS_DIARIO_CLASSE_NOVO + "selecione_calendario" ) );
    return false;
  }

  if ( iTurmaSelecionada == "" ) {

    alert( _M( MENSAGENS_DIARIO_CLASSE_NOVO + "selecione_turma" ) );
    return false;
  }

  if ( iPeriodoSelecionado == "") {

    alert( _M( MENSAGENS_DIARIO_CLASSE_NOVO + "selecione_periodo" ) );
    return false;
  }

  if ( aDisciplinaSelecionadas.length == 0 ) {

    alert( _M( MENSAGENS_DIARIO_CLASSE_NOVO + "selecione_disciplinas" ) );
    return false;
  }

  imprimir();
}

/**
 * Busca todos os parâmetros selecionados na tela e os passa via GET para o relatório para que possa imprimir
 * as informações na tela
 */
function imprimir() {

  var iCalendarioSelecionado  = oCalendario.getSelecionados().iCalendario;
  var iTurmaSelecionada       = oTurma.getSelecionados().codigo_turma;
  var iEtapaSelecionada       = oTurma.getSelecionados().codigo_etapa;
  var iPeriodoSelecionado     = oPeriodo.getSelecionado().iCodigo;
  var aDisciplinaSelecionadas = oDisciplina.getSelecionados();
  var aRegencias              = [];
  var aRegenciasSemGrade      = [];

  var sUrl         = "edu2_diarioclassenovo002.php";
  var sParametros  = "?iCalendario=" + iCalendarioSelecionado;
      sParametros += "&iTurma="      + iTurmaSelecionada;
      sParametros += "&iEtapa="      + iEtapaSelecionada;
      sParametros += "&iPeriodo="    + iPeriodoSelecionado;

  aDisciplinaSelecionadas.each(function( oDisciplina ){

    if( $F('registro') == 'F' && !oDisciplina.lTemGradeHorario ) {

      aRegenciasSemGrade.push(oDisciplina.sRegencia);
      return;
    }

    aRegencias.push(oDisciplina.iRegencia);
  });

  if ( aRegenciasSemGrade.length > 0 ) {
    alert ( _M(MENSAGENS_DIARIO_CLASSE_NOVO+"regencias_sem_grade", { 'sRegencias' : aRegenciasSemGrade.implode(', ') }));
  }

  if ( aRegencias.length == 0 ) {
    return;
  }

  sParametros += "&aRegencias="         + aRegencias;
  sParametros += "&iModelo="            + $F('listaModelos');
  sParametros += "&sRegistro="          + $F('registro');
  sParametros += "&sExibirPontos="      + $F('exibirPontos');
  sParametros += "&sDiasLetivos="       + $F('diasLetivos');
  sParametros += "&iQuantidadeColunas=" + $F('quantidadeColunas');
  sParametros += "&sAlunosAtivos="      + $F('alunosAtivos');
  sParametros += "&sTrocaTurma="        + $F('trocaTurma');

  for ( var oElemento in aColunas ) {

    if ( aColunas[oElemento].id ) {
      sParametros += "&" + aColunas[oElemento].id + "=" + aColunas[oElemento].checked;
    }
  }

  oJanela = window.open(
                         sUrl + sParametros,
                         '',
                         'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '
                       );
  oJanela.moveTo(0,0);
  return false;
}

/**
 * Limpa os campos período, turma e disciplina
 */
function limpaElementos() {

  oPeriodo.limpaElemento();
  oDisciplina.clear();
  oTurma.limpar();
}

preencheQuantidadeColunas();
buscaCalendario();
</script>
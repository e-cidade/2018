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
require_once(modification("libs/db_utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));

$db_opcao = 1;

if (!isset($iAnoMatricula)) {
  $iAnoMatricula = date("Y");
}
$oRotulo = new rotulocampo;
$oRotulo->label("ed56_i_aluno");
$oRotulo->label("ed47_v_nome");
$oRotulo->label("ed56_i_escola");
$oRotulo->label("ed60_i_turma");
$oRotulo->label("ed18_i_codigo");
$oRotulo->label("ed18_c_nome");
$oRotulo->label("ed60_d_datamatricula");

$ed18_i_codigo = db_getsession("DB_coddepto");
$ed18_c_nome   = db_getsession("DB_nomedepto");

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script type="text/javascript" src="scripts/scripts.js"></script>
  <script type="text/javascript" src="scripts/strings.js"></script>
  <script type="text/javascript" src="scripts/prototype.js"></script>
  <script type="text/javascript" src="scripts/classes/educacao/escola/TurmaTurnoReferente.classe.js"></script>
  <script type="text/javascript" src="scripts/classes/DBViewConsultaAvaliacoesAluno.classe.js"></script>
  <script type="text/javascript" src="scripts/dates.js"></script>
  <script type="text/javascript" src="scripts/webseller.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">

  <?php
    db_app::load("datagrid.widget.js");
    db_app::load("widgets/windowAux.widget.js");
    db_app::load("widgets/DBToogle.widget.js");
    db_app::load("widgets/DBHint.widget.js");
    db_app::load("widgets/datagrid/plugins/DBHint.plugin.js");
    db_app::load("DBGridMultiCabecalho.widget.js");
    db_app::load("grid.style.css");
    db_app::load("dbmessageBoard.widget.js");
  ?>
</head>
<body bgcolor="#CCCCCC" >

<div class="container">

  <form name="form1">
    <fieldset>
      <legend>Matricular Alunos Transferidos (FORA)</legend>

      <fieldset class='separator'>
        <legend>Dados do Aluno</legend>
        <table class="form-container">
          <tr>
            <td colspan="2">
              <label >Ano do Calendário da matrícula:</label>
              <?db_input( 'iAnoMatricula', 4, $iAnoMatricula, true, 'text', 1, "onchange='js_atualizaAnoSelecionado()';" );?>
            </td>
          </tr>

          <tr>
            <td class="field-size3">
              <?db_ancora( $Led56_i_aluno, "js_pesquisaTransferencia();", $db_opcao );?>
            </td>
            <td>
              <?php
                db_input( 'ed56_i_aluno', 10, $Ied56_i_aluno,   true, 'text',   3, "");
                db_input( 'ed47_v_nome',  40, '', true, 'text',   3, '');
                db_input( 'sSerieTransf', 20, '', true, 'text',   3, '');
                db_input( 'iSerieTransf', 10, '', true, 'hidden', 3, '');
                db_input( 'iMatriculaTransf', 10, '', true, 'hidden', 3, '');
              ?>
            </td>
          </tr>
        </table>
        <table class="form-container dadosAluno">
          <tr>
            <td class="field-size3">
              <label>Escola:</label>
            </td>
            <td>
              <?php
                db_input('iTransEscolaFora', 10, '', true, 'hidden', 3 );
                db_input('iEscolaOrigem', 10, '', true, 'hidden', 3 );
                db_input('sEscolaTransFora', 77, '', true, 'text', 3 );
                ?>
            </td>
          </tr>
          <tr>
            <td class="field-size3">
              <label>Data Matrícula:</label>
            </td>
            <td>
              <?php db_input('dtMatriculaAntes', 10, '', true, 'text', 3 );?>
              <label>Data Saída:</label>
              <?php db_input('dtSaidaAntes', 10,'',  true, 'text', 3 );?>
            </td>
          </tr>
        </table>
      </fieldset>

      <fieldset id='informacaoMatriculaAlunoEscolaAno' class='separator'>

      <legend>Informações da última matrícula do aluno</legend>
        <!--
            -- Se o aluno possuir matrícula na escola atual no ano selecionado.
           -->
        <table  class="form-container" >
          <tr class="alunoComMatriculaNessaEscolaNesseAno">
            <td colspan="4">
              <label id='msgAluno' style="color:#D14620"></label>
            </td>
          </tr>
          <tr class="alunoComMatriculaNessaEscolaNesseAno">
            <td class="field-size3">
              <label>Matrícula:</label>
            </td>
            <td>
              <?php db_input("iMatriculaAntes", 10, '', true, 'text', 3);?>
            </td>
            <td >
              <label>Situação: </label>
            </td>
            <td>
              <?php db_input("sSituacaoMatriculaAntes", 30, '', true, 'text', 3);?>
            </td>
          </tr>
          <tr class="alunoComMatriculaNessaEscolaNesseAno">
            <td class="field-size3">
              <label>Data Matrícula:</label>
            </td>
            <td>
              <?php db_input('dtMatriculaAntes2', 10, '', true, 'text', 3 );?>
            <td>
              <label>Data Saída:</label>
            </td>
            <td>
              <?php db_input('dtSaidaAntes2', 10,'',  true, 'text', 3 );?>
            </td>
          </tr>
        </table>
      </fieldset>

      <fieldset class='separator dadosAluno'>

      <!-- -------------------------------- DADOS DA TURMA ------------------------------------ -->
      <legend>Dados do Destino</legend>
        <table class="form-container dadosAluno informacoesTurmaDestino">
          <tr >
            <td class="field-size3">
              <label>Tipo de Ingresso:</label>
            </td>
            <td>
              <?php
                $aTipoIngresso = array( 1 => "Normal", 2 => "Classificado", 3 => "Reclassificado", 4 => "Avanço" );
                db_select('ed334_tipo', $aTipoIngresso, true, 1, "onchange='js_validaTipoTurma();'");
              ?>
            </td>
          </tr>
          <tr>
            <td class="field-size3" nowrap="nowrap" >
              <?db_ancora( "Turma:", "js_pesquisaTurma();", 1 );?>
            </td>
            <td>
              <?php
                db_input( 'ed60_i_turma', 10, '',  true, 'text', 3, '' );
                db_input( 'ed57_c_descr', 65, '', true, 'text', 3, '' );
                db_input( 'lTurmaMultietapa', 10, '', true, 'hidden', 3, '' );
              ?>
            </td>
          </tr>
          <tr>
            <td class="field-size3" nowrap="nowrap">
              <label>Curso:</label>
            </td>
            <td>
              <?php
                db_input( 'ed31_i_curso', 10, '', true, 'text', 3, '' );
                db_input( 'ed29_c_descr', 65, '', true, 'text', 3, '' );
              ?>
            </td>
          </tr>
          <tr>
            <td class="field-size3" nowrap="nowrap">
              <label>Base Curricular:</label>
            </td>
            <td>
              <?
                db_input( 'ed57_i_base',  10, '',  true, 'text', 3, '' );
                db_input( 'ed31_c_descr', 65, '', true, 'text', 3, '' );
              ?>
            </td>
          </tr>
          <tr>
            <td class="field-size3" nowrap="nowrap">
              <label>Calendário:</label>
            </td>
            <td>
             <?php
               db_input( 'ed57_i_calendario', 10, '', true, 'text',   3, '' );
               db_input( 'ed52_c_descr',      52, '', true, 'text',   3, '' );
               db_input( 'ed52_i_ano',        9,  '', true, 'text',   3, '' );
               db_input( 'ed52_d_inicio',     10, '', true, 'hidden', 3, '' );
               db_input( 'ed52_d_fim',        10, '', true, 'hidden', 3, '' );
               db_input( 'ed104_i_codigo',    10, '', true, 'hidden', 3, '' );
             ?>
            </td>
          </tr>

          <tr id='turmaMultietapa' style="display:none;">
            <td class="field-size3" nowrap="nowrap">
              <label>Etapa:</label>
            </td>
            <td>
              <select id='selectMultietapa' >
              </select>
            </td>
          </tr>
          <tr id='turmaEtapaUnica' style="display:none;">
            <td class="field-size3" nowrap="nowrap">
              <label>Etapa:</label>
            </td>
            <td>
              <?
                db_input( 'ed11_i_codigo',  10, '',  true, 'text', 3);
                db_input( 'ed11_c_descr',   65, '', true, 'text', 3);
              ?>
            </td>
          </tr>

          <tr id='linhaTurno'>
            <td class="field-size3" nowrap="nowrap">
              <label>Turno:</label>
            </td>
            <td>
              <?php
                db_input( 'ed57_i_turno', 10, '', true, 'text', 3);
                db_input( 'ed15_c_nome',  65, '',  true, 'text', 3);
               ?>
            </td>
          </tr>

          <tr>
            <td class="field-size3" nowrap="nowrap">
              <label>Escola:</label>
            </td>
            <td>
              <?php
                db_input( 'ed18_i_codigo', 10, '', true, 'text', 3);
                db_input( 'ed18_c_nome',   65, '', true, 'text', 3);
              ?>
            </td>
          </tr>
          <tr>
            <td class="field-size3" nowrap="nowrap">
              <label>Data da matrícula:</label>
            </td>
            <td>
              <?php
                db_inputdata('ed60_d_datamatricula', @$ed60_d_datamatricula_dia, @$ed60_d_datamatricula_mes, @$ed60_d_datamatricula_ano, true, 'text', 1);?>
            </td>
          </tr>
        </table>
      </fieldset>
      <!-- -------------------------------- FIM DADOS DA TURMA ---------------------------------- -->

      <fieldset class='separator' id='dadosImportarAproveitamento' >
        <legend>Dados de importação do aproveitamento</legend>
        <table class="form-container">
          <tr>
            <td colspan="2">
              <label class="bold">
                Este aluno foi transferido para fora da Rede Municipal neste ano.<br>
                Caso queira importar o aproveitamento para a turma de destino, informe no campo abaixo:
              </label>
            </td>
          </tr>
          <tr>
            <td class="field-size3">
              <label>Importar Aproveitamento:</label>
            </td>
            <td >
              <select id='importarAproveitamento'>
                <option value="S" selected="selected">Sim</option>
                <option value="N">Não</option>
              </select>
            </td>
          </tr>
        </table>
        <input type="button" value="Visualizar Aproveitamento" name="gradeAproveitameno" id="gradeAproveitameno" />
      </fieldset>
    </fieldset>
    <input type="button" value="Matricular Aluno" name="matricularAluno" id="matricularAluno" />
  </form>
</div>

</body>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</html>
<script type="text/javascript">

var oViewAvaliacao = null;
var oTurmaTurno    = null;
const MESSAGE      = "educacao/escola/edu4_matricularalunotranferidofora001.";

( function() {

  $('ed56_i_aluno').value     = '';
  $('ed47_v_nome').value      = '';
  $('sSerieTransf').value     = '';
  $('iSerieTransf').value     = '';
  $('iMatriculaTransf').value = '';
  $('dadosImportarAproveitamento').style.display = 'none';
  $('matricularAluno').setAttribute('disabled', 'disabled');
  $$('.dadosAluno').each( function( oElement ) {
    oElement.style.display = 'none';
  });

  $('ed334_tipo').value = 1;
  js_ocultaInformacoesMatriculaAlunoEscola();

})();

function js_montaViewVagasTurma() {

  if (oTurmaTurno instanceof DBViewFormularioEducacao.TurmaTurnoReferente) {
    oTurmaTurno.limpaLinhasCriadas();
  }
  oTurmaTurno = new DBViewFormularioEducacao.TurmaTurnoReferente($('linhaTurno') , $F('ed60_i_turma'));
  oTurmaTurno.show();

  $('matricularAluno').removeAttribute('disabled');

  if ( !oTurmaTurno.temVagasDisponiveis() ) {

    alert( _M( MESSAGE+'aviso_turma_sem_vagas') );
    $('matricularAluno').setAttribute('disabled', 'disabled');
  }
}

/**
 * Busca os alunos transferidos
 * @return {void}
 */
function js_pesquisaTransferencia() {

  var sUrl  = 'func_transfescolaforamatr.php?funcao_js=parent.js_mostraAluno';
      sUrl += '|ed104_i_aluno|ed47_v_nome|dl_etapa|dl_codigo|ed104_d_data|ed60_d_datamatricula|ed18_c_nome|ed104_i_codigo';
      sUrl += '|ed104_i_escolaorigem|dl_sequencia_matricula';
  var sLabelJanela = 'Pesquisa de alunos transferidos para fora da rede';

  js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_transfescolafora', sUrl, sLabelJanela, true);
}


/**
 * Retorno do dados do aluno selecionado
 *  [0] - codigo do aluno
 *  [1] - nome do aluno
 *  [2] - série cursada
 *  [3] - codigo da série
 *  [4] - data da tranferencia
 *  [5] - data de matricula
 *  [6] - nome da escolka
 *  [7] - codigo da tabela transfescolafora que guarda a referncia que escola saiu e para qual foi
 *  [8] - Escola de origem
 *  [9] - Matrícula do aluno quando foi transferido
 * @return {void}
 */
function js_mostraAluno () {

  $('ed56_i_aluno').value     = arguments[0];
  $('ed47_v_nome').value      = arguments[1].trim();
  $('sSerieTransf').value     = arguments[2].trim();
  $('iSerieTransf').value     = arguments[3];
  $('dtSaidaAntes').value     = js_formatar(arguments[4], 'd');
  $('dtMatriculaAntes').value = js_formatar(arguments[5], 'd');
  $('sEscolaTransFora').value = arguments[6];
  $('iTransEscolaFora').value = arguments[7];
  $('iEscolaOrigem').value    = arguments[8];
  $('iMatriculaTransf').value = arguments[9];

  $$('.dadosAluno').each( function(oElement) {
    oElement.style.display = '';
  });

  db_iframe_transfescolafora.hide();

  js_limpaDadosAlunosMatriculadosNessaEscola();
  js_limpaDadosTurma();

  if ( $F('iEscolaOrigem') == $F('ed18_i_codigo') ) {
    js_verificaMatriculaEscola();
  } else {
    js_verificaImportacaoAvaliacao();
  }
}

/**
 * Pesquisa as turmas
 * @return {void}
 */
function js_pesquisaTurma(iTurma) {

  js_limpaDadosTurma();
  if ($F('iAnoMatricula') == '') {

    alert( _M( sCaminhoMensagens + 'ano_calendario' ) );
    $('iAnoMatricula').style.backgroundColor = '#99A9AE';
    $('iAnoMatricula').focus();
    return;
  }

  var sUrl  = "func_turmamatrtransffora.php?";
      sUrl += "codserietransf="+$F('iSerieTransf');
      sUrl += "&anocalendario="+$F('iAnoMatricula');
      sUrl += "&aluno="+$F('ed56_i_aluno');
      sUrl += "&turmasprogressao=f";
      sUrl += "&lEliminarSeriesAnteriores=true";

  if ( iTurma && iTurma != '') {
    sUrl += "&iTurma="+iTurma;
  }

  if ( $('ed334_tipo').value == 3 ) {
    sUrl += "&lReclassificacao=t";
  }
  sUrl += "&funcao_js=parent.js_mostraTurma";
  sUrl += "|ed57_i_codigo|ed57_c_descr|ed15_i_codigo|ed15_c_nome";
  sUrl += "|ed29_c_descr|ed29_i_codigo|ed31_i_codigo|ed31_c_descr";
  sUrl += "|ed52_i_ano|ed52_c_descr|ed52_i_codigo|ed52_d_inicio|ed52_d_fim";
  // sUrl += "|ed11_i_codigo|ed11_i_sequencia|ed11_c_descr";

  js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_turma', sUrl, 'Pesquisa de Turmas', true);
}

/**
 * Dados no retorno do pesquisar turma
 * [0]  - Código da turma
 * [1]  - Nome da turma
 * [2]  - Código do Turno
 * [3]  - Nome do Turno
 * [4]  - Nome do curso
 * [5]  - Código do curso
 * [6]  - Código da Base
 * [7]  - Nome da Base
 * [8]  - Ano do calendário
 * [9]  - Nome do calendário
 * [10] - Código do calendário
 * [11] - Data de inicio do calendário
 * [12] - Data de fim do calendário
 * [13] - "38"
 * [14] - "2"
 * [15] - 'pre'
 */
function js_mostraTurma() {

  $('ed60_i_turma').value      = arguments[0];
  $('ed57_c_descr').value      = arguments[1];
  $('ed31_i_curso').value      = arguments[5];
  $('ed29_c_descr').value      = arguments[4];
  $('ed31_c_descr').value      = arguments[7];
  $('ed57_i_base').value       = arguments[6];
  $('ed57_i_calendario').value = arguments[10];
  $('ed52_c_descr').value      = arguments[9];
  $('ed52_i_ano').value        = arguments[8];
  $('ed52_d_inicio').value     = arguments[11];
  $('ed52_d_fim').value        = arguments[12];
  $('ed57_i_turno').value      = arguments[2];
  $('ed15_c_nome').value       = arguments[3];

  // validaEtapasTurma(arguments[13], arguments[14], arguments[15]) ;
  js_verificaEtapaTurmaSelecionada();

  db_iframe_turma.hide();
}


/**
 * Busca a(s) etapa(s) da turma
 * @return {void}
 */
function js_verificaEtapaTurmaSelecionada() {

  var oParametros                 = {};
  oParametros.exec                = 'verificaEtapaTurma';
  oParametros.iTurma              = $F('ed60_i_turma');
  oParametros.iEtapaTurmaAnterior = $F('iSerieTransf');

  var oRequest = {};
  oRequest.method       = 'post';
  oRequest.asynchronous = false;
  oRequest.parameters   = 'json='+Object.toJSON(oParametros);
  oRequest.onComplete   = js_retornoVerificaEtapaTurmaSelecionada;

  js_divCarregando( _M( MESSAGE + 'aguarde_buscando_etapa_turma' ), "msgBoxB" );
  new Ajax.Request('edu4_transferencia.RPC.php', oRequest);
}

/**
 * Retorno da busca das etapas da turma
 * @param  {[type]} oAjax [description]
 * @return {[type]}       [description]
 */
function js_retornoVerificaEtapaTurmaSelecionada (oAjax) {

  js_removeObj('msgBoxB');
  var oRetorno = eval( "(" + oAjax.responseText + ")" );

  if (oRetorno.lMultietapa) {

    js_turmaDestinoMultietapa(oRetorno);
    return;
  }
  js_turmaDestinoEtapaUnica(oRetorno);
}

/**
 * Monta o select das etapas da turma
 * @param  {Object} oDadosEtapa informações das etapas da turma
 * @return {void}
 */
function js_turmaDestinoMultietapa (oDadosEtapa) {

  $('ed11_i_codigo').value           = '';
  $('ed11_c_descr').value            = '';
  $('turmaEtapaUnica').style.display = 'none';
  $('lTurmaMultietapa').value        = 'true';

  oDadosEtapa.aEtapasTurma.each( function (oEtapa) {

    var oOption = new Element('option', {'value':oEtapa.iCodigo }).update(oEtapa.sDecricao.urlDecode());

    if ( oDadosEtapa.lConsistirHistorico && !oEtapa.lEquivalente ) {
      oOption.setAttribute("disabled", "disabled");
    } else if ( oDadosEtapa.lConsistirHistorico && oEtapa.lEquivalente ) {
      oOption.setAttribute("selected", "selected");
    }
    $('selectMultietapa').appendChild(oOption);

  });

  $('turmaMultietapa').style.display = '';

  js_montaViewVagasTurma();
}

/**
 * Preenche os dados da etapa.
 * @param  {Object} oDadosEtapa informações das etapas da turma
 * @return {void}
 */
function js_turmaDestinoEtapaUnica (oDadosEtapa) {

  $('selectMultietapa').innerHTML    = "" ;
  $('turmaMultietapa').style.display = 'none';
  $('ed11_i_codigo').value           = oDadosEtapa.aEtapasTurma[0].iCodigo;
  $('ed11_c_descr').value            = oDadosEtapa.aEtapasTurma[0].sDecricao.urlDecode();
  $('turmaEtapaUnica').style.display = '';
  $('lTurmaMultietapa').value        = 'false';

  js_montaViewVagasTurma();
}

/**
 * Atualiza o ano selecionado
 * @return {void}
 */
function js_atualizaAnoSelecionado() {

  js_limpaDadosTurma();
  if ( $F('iAnoMatricula') != '' && $F('ed56_i_aluno') != '' ) {

    if ( $F('iEscolaOrigem') == $F('ed18_i_codigo') ) {
      js_verificaMatriculaEscola();
    } else {
      js_verificaImportacaoAvaliacao();
    }
  }
}

/**
 * Verifica se o aluno teve matricula nesta escola (escola logada) para o ano informado
 * @return {void}
 */
function js_verificaMatriculaEscola() {

  js_limpaDadosImportacaoAproveitamento();

  if ( $F('iAnoMatricula') == '') {
    return;
  }

  var oParametros    = {};
  oParametros.exec   = 'verificaMatriculaEscola';
  oParametros.iAluno = $F('ed56_i_aluno');
  oParametros.iAno   = $F('iAnoMatricula');

  var oRequest = {};
  oRequest.method       = 'post';
  oRequest.asynchronous = false;
  oRequest.parameters   = 'json='+Object.toJSON(oParametros);
  oRequest.onComplete   = js_retornoVerificaMatriculaEscola;

  js_divCarregando( _M( MESSAGE + 'aguarde_verifica_matricula_escola' ), "msgBoxA" );
  new Ajax.Request('edu4_transferencia.RPC.php', oRequest);
}

/**
 * @param  {JSON} oAjax dados de retorno
 * @return {void}
 */
function js_retornoVerificaMatriculaEscola(oAjax) {

  js_removeObj('msgBoxA');
  var oRetorno = eval( "(" + oAjax.responseText + ")" );

  if ( !oRetorno.lTemMatriculaEscolaAno ) {

    $('msgAluno').update("");
    $$('.alunoComMatriculaNessaEscolaNesseAno input').each( function( oElement ) {
      oElement.value = '';
    });
    js_ocultaInformacoesMatriculaAlunoEscola();
    return;
  }

  $('iMatriculaAntes').value         = oRetorno.oDadosMatricula.iMatricula;
  $('sSituacaoMatriculaAntes').value = oRetorno.oDadosMatricula.sSituacao.urlDecode();
  $('dtMatriculaAntes2').value       = oRetorno.oDadosMatricula.dtMatricula;
  $('dtSaidaAntes2').value           = oRetorno.oDadosMatricula.dtSaida;

  var sMsg  = "Aluno " + $F('ed56_i_aluno') + " já possui matrícula em " + $F('iAnoMatricula') + " nesta escola na turma abaixo relacionada, ";
      sMsg += "com situação <br>" + $F('sSituacaoMatriculaAntes') + " a " + oRetorno.oDadosMatricula.iDiasPassados;
      sMsg += " dia";
      sMsg += oRetorno.oDadosMatricula.iDiasPassados > 1 ? "(s).":".";

  $('msgAluno').update(sMsg);
  $('informacaoMatriculaAlunoEscolaAno').style.display = '';

  js_pesquisaTurma(oRetorno.oDadosMatricula.iTurma);

}

function js_ocultaInformacoesMatriculaAlunoEscola() {
  $('informacaoMatriculaAlunoEscolaAno').style.display = 'none';
}

function js_limpaDadosAlunosMatriculadosNessaEscola () {

  $('msgAluno').update("");
  $$('.alunoComMatriculaNessaEscolaNesseAno input').each( function( oElement ) {
    oElement.value = '';
  });
  $('informacaoMatriculaAlunoEscolaAno').style.display = 'none';
}

/**
 * Apaga os dados da turma e preenche as informações da escola e data de matrícula com os dados atuais
 * @return {void}
 */
function js_limpaDadosTurma() {

  $$('.informacoesTurmaDestino input').each( function(oElement) {

    if ( oElement.type == 'button') {
      return;
    }
    oElement.value = '';
  });


  $('ed18_i_codigo').value = '<?=db_getsession("DB_coddepto");?>';
  $('ed18_c_nome').value   = '<?=db_getsession("DB_nomedepto");?>';

  var oData = new Date();
  $('ed60_d_datamatricula_dia').value = js_strLeftPad( oData.getDate(), '2', '0');
  $('ed60_d_datamatricula_mes').value = js_strLeftPad( oData.getMonth() + 1, '2', '0');
  $('ed60_d_datamatricula_ano').value = oData.getFullYear();
  $('ed60_d_datamatricula').value     = $F('ed60_d_datamatricula_dia') + '/' + $F('ed60_d_datamatricula_mes') + '/' + $F('ed60_d_datamatricula_ano');

  $('matricularAluno').setAttribute('disabled', 'disabled');
}

/**
 * Limpa os dados da importação
 * @return {void}
 */
function js_limpaDadosImportacaoAproveitamento() {

  delete oViewAvaliacao;
  $('dadosImportarAproveitamento').style.display = 'none';

}

/**
 * Verifica se o aluno teve uma matricula em uma escola da rede (não a logada) para o ano informado
 * @return {void}
 */
function js_verificaImportacaoAvaliacao() {

  var aDataMatricula = $F('dtMatriculaAntes').split('/');

  //Só percisamos verificar se o ano da matricula for igual ao ano informado
  if ( aDataMatricula[2] != $F('iAnoMatricula') ) {

    js_limpaDadosImportacaoAproveitamento();
    return;
  }

  $('dadosImportarAproveitamento').style.display = '';

  $('gradeAproveitameno').onclick = function() {

    delete oViewAvaliacao;
    oViewAvaliacao = new DBViewConsultaAvaliacoesAluno("oViewAvaliacao", $F('iMatriculaTransf'));
    oViewAvaliacao.show();
  }

};

function js_validaTipoTurma() {

  $('gradeAproveitameno').removeAttribute('disabled');
  $('importarAproveitamento').options[0].removeAttribute('disabled');
  $('importarAproveitamento').value = 'S';
  if ( $F('ed334_tipo') == 3 ){

    $('gradeAproveitameno').setAttribute('disabled', 'disabled');
    $('importarAproveitamento').options[0].setAttribute('disabled', 'disabled');
    $('importarAproveitamento').options[0].removeAttribute('selected');
    $('importarAproveitamento').value = 'N'
  }
}


function validaDadosMatricula () {

  if ( $F('lTurmaMultietapa') == 'true' && $F('selectMultietapa') == '' ) {

    alert(_M( MESSAGE+'selecione_uma_etapa') );
    return false;
  }

  if ( $F('ed60_d_datamatricula') == '' ) {

    alert( _M( MESSAGE + 'informe_data_matricular' ) );
    return false;
  }

  var sDataInformada = $F('ed60_d_datamatricula_ano') + '-' + $F('ed60_d_datamatricula_mes') + '-' + $F('ed60_d_datamatricula_dia');
  if ( !js_validaIntervaloData(sDataInformada , $F('ed52_d_inicio'), $F('ed52_d_fim') ) ) {

    var oMsg = { "sDataInicio" : js_formatar($F('ed52_d_inicio'), 'd'), "sDataFim" : js_formatar($F('ed52_d_fim'), 'd') };
    alert( _M( MESSAGE + 'data_fora_intervalo', oMsg) );
    return false;
  }

  var aDataMatricula         = $F('dtMatriculaAntes').split('/');
  var sDataMatriculaAnterior = aDataMatricula[2] + "" + aDataMatricula[1] + "" + aDataMatricula[0];
  sDataInformada             = $F('ed60_d_datamatricula').split('/').reverse().toString().replace(/,/g, "");

  if ( parseInt( sDataMatriculaAnterior ) > parseInt( sDataInformada ) ) {

    var oMsg = { "sDataMatricula" : $F('dtMatriculaAntes') };
    alert( _M( MESSAGE + 'data_matricula_menor_data_matricula_anterior', oMsg ) ) ;
    return false;
  }

  var aDataSaida = $F('dtSaidaAntes').split('/');
  var sDataSaida = aDataSaida[2] + "" + aDataSaida[1] + "" + aDataSaida[0];

  if ( parseInt(sDataSaida) > parseInt(sDataInformada) ) {

    var oMsg = { "sDataSaida" : $F('dtSaidaAntes') };
    alert( _M( MESSAGE + 'data_matricula_menor_data_saida', oMsg ) ) ;
    return false;
  }
  return true;

}


/**
 * Realiza a matrícula do aluno
 * @return {void}
 */
$('matricularAluno').observe('click', function() {

  if ( !validaDadosMatricula() ) {
    return false;
  }

  $('matricularAluno').setAttribute('disabled', 'disabled');

  var lImportaAvaliacao = false;
  var aDataMatricula    = $F('dtMatriculaAntes').split('/');


  if ( $F('iEscolaOrigem') != $F('ed18_i_codigo') && ( aDataMatricula[2] == $F('iAnoMatricula') ) ) {
    lImportaAvaliacao = $F('importarAproveitamento') == 'S';
  }

  var iEtapa = $F('ed11_i_codigo');
  if ( $F('lTurmaMultietapa') == 'true' ) {
    iEtapa = $F('selectMultietapa');
  }

  var aTurnoReferenteSelecionado = [];
  $$('.TurmaTurnoReferente:checked').each( function(oElement) {
    aTurnoReferenteSelecionado.push(oElement.value);
  });


  var oParametros                 = {};
  oParametros.exec                = 'MatriculaFora';
  oParametros.iAluno              = $F('ed56_i_aluno');
  oParametros.iAno                = $F('iAnoMatricula');
  oParametros.iMatriculaAntiga    = $F('iMatriculaTransf');
  oParametros.iEtapaAntiga        = $F('iSerieTransf');
  oParametros.iCodigoTranferencia = $F('iTransEscolaFora');
  oParametros.iEscolaOrigem       = $F('iEscolaOrigem');
  oParametros.iTipoMatricula      = $F('ed334_tipo');
  oParametros.iTurmaDestino       = $F('ed60_i_turma');
  oParametros.aTurnosReferente    = aTurnoReferenteSelecionado;
  oParametros.iEtapa              = iEtapa;
  oParametros.dtMatricula         = $F('ed60_d_datamatricula');
  oParametros.lImportaAvaliacao   = lImportaAvaliacao;

  var oRequest          = {};
  oRequest.method       = 'post';
  oRequest.asynchronous = false;
  oRequest.parameters   = 'json='+Object.toJSON(oParametros);
  oRequest.onComplete   = js_retornoMatricula;

  js_divCarregando( _M( MESSAGE + 'aguarde_verifica_matricula_escola' ), "msgBoxC" );
  new Ajax.Request('edu4_transferencia.RPC.php', oRequest);

});

function js_retornoMatricula (oAjax) {

  js_removeObj('msgBoxC');
  var oRetorno = eval( "(" + oAjax.responseText + ")" );

  if (oRetorno.iStatus == 2) {

    alert(oRetorno.sMessage.urlDecode());
    return;
  }

  alert(oRetorno.oDados.sMensagem.urlDecode());

  /**
   * Só devemos importar os dados do aproveitamento da turma anterior, se o aluno atender os requisitos abaixo
   * - Aluno não pode ter progressão parcial
   * - A turma de orgem tem que ser diferente da turma de destino
   * - O tipo da matricula tem que ser diferente de 3 ( Reclassificado)
   */

  if (    oRetorno.iStatus == 1
       && !oRetorno.oDados.lTemProgressaoParcial
       && $F('importarAproveitamento') == 'S'
       && oRetorno.iTurmaAnterior != ''
       && $F('ed60_i_turma') != oRetorno.iTurmaAnterior
       && $F('ed334_tipo') != 3
       && oRetorno.lPermiteImportarAproveitamento
     ) {


    var sUrl  = 'edu1_matriculatransffora002.php?ed56_i_aluno=' + $F('ed56_i_aluno');
        sUrl += '&ed47_v_nome=' + $F('ed47_v_nome');
        sUrl += '&desabilita&matricula=' + $F('iMatriculaTransf');
        sUrl += '&turmaorigem='  + oRetorno.iTurmaAnterior;
        sUrl += '&turmadestino=' + $F('ed60_i_turma');


    location.href = sUrl;
    return;
  }

  location.href = 'edu4_matricularalunotranferidofora001.php';

}

</script>
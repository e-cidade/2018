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
  <?php
  db_app::load("scripts.js, prototype.js, strings.js");
  db_app::load("estilos.css");
  db_app::load("classes/educacao/escola/ListaEscola.classe.js");
  db_app::load("classes/educacao/escola/ListaCalendario.classe.js");
  db_app::load("classes/educacao/escola/ListaEtapa.classe.js");
  db_app::load("classes/educacao/escola/ListaTurma.classe.js");
  ?>
  <script type="text/javascript" >
    require_once('scripts/widgets/DBToggleList.widget.js');
  </script>
</head>
<body bgcolor="#cccccc" style='margin-top: 30px'>
  <?php
    /**
     * Validamos se estamos no módulo escola
     */
    if (db_getsession("DB_modulo") == 1100747) {
      MsgAviso(db_getsession("DB_coddepto"),"escola");
    }
  ?>
  <div class='container'>
    <form id='formPadrao' action="">
      <fieldset>
        <legend>Atestado de Frequência</legend>
        <table class="form-container">
          <tr>
            <td nowrap="nowrap" class='bold field-size3'>Escola:</td>
            <td nowrap="nowrap" id='listaEscola'></td>
          </tr>
          <tr>
            <td nowrap="nowrap" class='bold field-size3'>Calendário:</td>
            <td nowrap="nowrap" id='listaCalendario'></td>
          </tr>
          <tr>
            <td nowrap="nowrap" class='bold field-size3'>Turmas:</td>
            <td nowrap="nowrap" id='listaTurmas'></td>
          </tr>
        </table>

        <fieldset class='separator'>
          <legend>Alunos</legend>
          <div id="ctnAlunos" style="width:800px;"></div>
        </fieldset>

        <fieldset class='separator'>
          <legend>Outros Filtros</legend>
          <table class="form-container">
            <tr>
              <td nowrap="nowrap" class = 'bold field-size3'>Emissor:</td>
              <td nowrap="nowrap" >
                <select id='emissor' style="width:100%;">
                  <option value="">Selecione Emissor</option>
                </select>
              </td>
            </tr>
            <tr>
              <td nowrap="nowrap" class = 'bold field-size3'>Exibir Grade de Horário:</td>
              <td nowrap="nowrap" >
                <select id='gradeAluno' style="width:100%;">
                  <option value="S">Sim</option>
                  <option value="N">Não</option>
                </select>
              </td>
            </tr>
          </table>
        </fieldset>
        <fieldset class='separator'>
          <legend>Observação</legend>
          <textarea id='observacao' rows="5" style="width: 100%;"></textarea>
        </fieldset>
      </fieldset>

      <input type="button" disabled='disabled' id='imprimir' value='Imprimir' name='imprimir' />
    </form>
  </div>
</body>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>

/**
 * Instância dos componentes da View
 * @type {DBViewFormularioEducacao}
 */
var oEscola     = new DBViewFormularioEducacao.ListaEscola();
var oCalendario = new DBViewFormularioEducacao.ListaCalendario();
var oTurmas     = new DBViewFormularioEducacao.ListaTurma();

var oToggle = new DBToggleList([{sId:'sAluno', sLabel: 'Aluno'}]);

oToggle.closeOrderButtons();
oToggle.show($('ctnAlunos'));

/**
 * Funcão a ser executada após carregar os dados da escola
 * @return {void}
 */
var fFunctionLoadEscola = function () {

  var oEscolaSelecionada = oEscola.getSelecionados();

  if (oEscolaSelecionada.codigo_escola != '') {

    oCalendario.setEscola(oEscolaSelecionada.codigo_escola);
    oCalendario.getCalendarios();
    js_buscaEmissor();
  }
};

/**
 * Função a ser colocada no onchange do combo da escola
 * @return {void}
 */
var fFunctionChangeEscola = function () {

  var oEscolaSelecionada = oEscola.getSelecionados();

  oCalendario.limpar();
  oTurmas.limpar();
  oToggle.clearAll();

  if (oEscolaSelecionada.codigo_escola == '') {

    $('imprimir').setAttribute("disabled", "disabled");
    return false;
  }

  oCalendario.setEscola(oEscolaSelecionada.codigo_escola);
  oCalendario.getCalendarios();
  oTurmas.setEscola(oEscolaSelecionada.codigo_escola);
  js_buscaEmissor();

};


oEscola.setCallBackLoad(fFunctionLoadEscola);
oEscola.setCallbackOnChange(fFunctionChangeEscola);
oEscola.habilitarOpcaoTodas(false);
oEscola.show($('listaEscola'));


/**
 * Funcão a ser executada após carregar os dados do calendario
 * @return {void}
 */
var fFunctionLoadCalendario = function() {

  if (oCalendario.oElement.options.length == 2) {

    oCalendario.oElement.value = oCalendario.oElement.options[1].value;
    oTurmas.setCalendario(oCalendario.oElement.options[1].value);
    oTurmas.getTurmas();
  }
  $('imprimir').setAttribute("disabled", "disabled");
};

/**
 * Função a ser colocada no onchange do combo do calendario
 * @return {void}
 */
var fFunctionChangeCalendario = function() {

  var mCalendarioSelecionado = oCalendario.getSelecionados();

  oTurmas.limpar();
  oToggle.clearAll();
  if (mCalendarioSelecionado.iCalendario == '') {

    $('imprimir').setAttribute("disabled", "disabled");
    return false;
  }

  var aListaCalendarios = new Array();
  aListaCalendarios.push(mCalendarioSelecionado.iCalendario);
  oTurmas.setCalendario(aListaCalendarios.implode(", "));
  oTurmas.getTurmas();
};

oCalendario.setCallBackLoad(fFunctionLoadCalendario);
oCalendario.setOnChangeCallBack(fFunctionChangeCalendario);
oCalendario.agruparPorAno(false);
oCalendario.show($('listaCalendario'));


/**
 * Funcão a ser executada após carregar os dados da turma
 * @return {void}
 */
var fFunctionLoadTurma = function () {

  if (oTurmas.getSelecionados().codigo_turma != '') {

    js_buscaAlunosTurma();
    $('imprimir').removeAttribute("disabled");
  }
};

/**
 * Função a ser colocada no onchange do combo da turma
 * @return {void}
 */
var fFunctionChangeTurma = function () {

  var oTurmaSelecionada = oTurmas.getSelecionados();
  oToggle.clearAll();
  if (oTurmaSelecionada.codigo_turma != '') {

    js_buscaAlunosTurma();
    $('imprimir').removeAttribute("disabled");
  }
};

oTurmas.setCallBackLoad(fFunctionLoadTurma);
oTurmas.setCallbackOnChange(fFunctionChangeTurma);
oTurmas.show($("listaTurmas"));


/**
 * Busca os alunos da turma selecionada
 * @return {void}
 */
function js_buscaAlunosTurma() {

  var oParametros    = {};
  oParametros.exec   = 'pesquisaAlunosTurma';
  oParametros.iTurma = oTurmas.getSelecionados().codigo_turma;
  oParametros.iEtapa = oTurmas.getSelecionados().codigo_etapa;

  js_divCarregando(_M("educacao.escola.edu2_atestadofrequencia.pesquisando_alunos"), "msgBox");

  var oObjeto        = {};
  oObjeto.method     = 'post';
  oObjeto.parameters = 'json='+Object.toJSON(oParametros);
  oObjeto.onComplete = function(oAjax) {
                       js_retornoAlunos(oAjax);
                     };
  new Ajax.Request("edu4_turmas.RPC.php", oObjeto);
}

/**
 * Trata o retorno dos dados retornados pela função js_buscaAlunosTurma
 * @param  {Object} oAjax
 * @return {void}
 */
function js_retornoAlunos(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("(" + oAjax.responseText + ")");

  if (oRetorno.aAlunos.length == 0) {

    alert(_M("educacao.escola.edu2_atestadofrequencia.nenhum_aluno_encontrado"));
    return false;
  }

  oToggle.clearAll();
  oRetorno.aAlunos.each( function (oAluno) {
    oToggle.addSelect({"iMatricula":oAluno.iMatricula, "sAluno":oAluno.sNome.urlDecode()});
  });
  oToggle.renderRows();
}


function js_buscaEmissor() {

  var oEscolaSelecionada = oEscola.getSelecionados();
  if (oEscolaSelecionada.codigo_escola == '') {
    return false;
  }
  var oParametros     = {};
  oParametros.exec    = 'buscaEmissor';
  oParametros.iEscola = oEscolaSelecionada.codigo_escola;

  $('emissor').options.length = 0;
  $('emissor').add(new Option("Selecione Emissor", ""));

  js_divCarregando(_M("educacao.escola.edu2_atestadofrequencia.pesquisando_emissor"), "msgBox");

  var oObjeto        = {};
  oObjeto.method     = 'post';
  oObjeto.parameters = 'json='+Object.toJSON(oParametros);
  oObjeto.onComplete = function(oAjax) {
                       js_retornoEmissor(oAjax);
                     };
  new Ajax.Request("edu_educacaobase.RPC.php", oObjeto);
}

function js_retornoEmissor(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("(" + oAjax.responseText+ ")");

  if (oRetorno.status == 2) {
    alert(oRetorno.message.urlDecode());
  }

  oRetorno.dados.each( function (oEmissor) {

    var sValue  = oEmissor.funcao.urlDecode()+'|'+oEmissor.nome.urlDecode()+'|'+oEmissor.descricao.urlDecode();
    var sString = oEmissor.funcao.urlDecode()+' - '+oEmissor.nome.urlDecode();

    if (!empty(oEmissor.descricao)) {
      sString += " ("+oEmissor.descricao.urlDecode()+") ";
    }
    $('emissor').add(new Option(sString, sValue));
  });
}

$('imprimir').observe("click", function() {

  if (oToggle.getSelected().length == 0) {

    alert(_M("educacao.escola.edu2_atestadofrequencia.nenhum_aluno_selecionado"));
    return false;
  }
  var sUrl  = "edu2_atestadofrequencia002.php";
      sUrl += "?aMatriculas="+Object.toJSON(oToggle.getSelected());
      sUrl += "&iTurma="+oTurmas.getSelecionados().codigo_turma;
      sUrl += "&sDiretor="+btoa($('emissor').value);
      sUrl += "&lExibeGradeAluno="+$F('gradeAluno');
      sUrl += "&sObservacao="+btoa($F('observacao'));

  jan = window.open(sUrl,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  jan.moveTo(0,0);
});

</script>
</html>
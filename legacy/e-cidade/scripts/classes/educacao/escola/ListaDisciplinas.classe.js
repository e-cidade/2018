require_once("scripts/arrays.js");
require_once("scripts/classes/educacao/DBViewFormularioEducacao.classe.js");

const MSG_LISTA_DISCIPLINAS = "educacao.escola.ListaDisciplinas.";
DBViewFormularioEducacao.ListaDisciplinas = function () {

  /**
   * RPC para buscar as regências
   * @type {string}
   */
  this.sRPC = "edu4_turmas.RPC.php";

  /**
   * Define um ação a ser executada após o carregamento dos dados
   * @type {function}
   */
  this.fCallBackLoad = function () {
    return true;
  };

  /**
   * Controla se devem ser retornadas somente disciplinas globais
   * @type {Boolean}
   */
  this.lSomenteDisciplinasGlobais = false;

  /**
   * Instância de ToggleList para disciplina
   * @type {DBToggleList}
   */
  this.oToggleRegencia = new DBToggleList([{'sId' : "sRegencia", 'sLabel' : "Regência"}]);
  this.oToggleRegencia.closeOrderButtons();
};

/**
 * Define a função para ser executado após o carregamento dos dados
 * @param fFunction
 */
DBViewFormularioEducacao.ListaDisciplinas.prototype.setCallBackLoad = function (fFunction) {
  this.fCallBackLoad = fFunction;
};

/**
 * Busca as disciplinas da turma para etapa selecionada
 * @param iTurma           código da turma
 * @param iEtapa           código da etapa
 * @param lProfessorLogado se deve as disciplinas do professor logado
 */
DBViewFormularioEducacao.ListaDisciplinas.prototype.getDisciplinas = function ( iTurma, iEtapa, lProfessorLogado ) {

  var oParametros                            = {};
      oParametros.exec                       = "getRegencias";
      oParametros.iTurma                     = iTurma;
      oParametros.iEtapa                     = iEtapa;
      oParametros.lDisciplinasProfessor      = lProfessorLogado;
      oParametros.lSomenteDisciplinasGlobais = this.lSomenteDisciplinasGlobais;

  oSelf = this;
  var oRequest = {};
      oRequest.asynchronous = false;
      oRequest.method = 'post';
      oRequest.parameters = 'json='+Object.toJSON(oParametros);
      oRequest.onComplete = function(oAjax) {
        oSelf.retornoDisciplinas(oAjax);
      };

  js_divCarregando( _M(MSG_LISTA_DISCIPLINAS+"buscando_disciplinas"), "msgBoxA" );
  new Ajax.Request(oSelf.sRPC, oRequest);
};

/**
 * Retorno das disciplinas
 * @param oAjax
 */
DBViewFormularioEducacao.ListaDisciplinas.prototype.retornoDisciplinas = function( oAjax ) {

  js_removeObj("msgBoxA");
  var oRetorno = eval ( "(" + oAjax.responseText + ")" );
  var oSelf    = this;
  oSelf.oToggleRegencia.clearAll();
  oRetorno.aRegencias.each( function ( oRegencia ) {
    oSelf.oToggleRegencia.addSelect( {'iRegencia'        : oRegencia.iRegencia,
                                      'iDisciplina'      : oRegencia.iDisciplina,
                                      'sRegencia'        : oRegencia.sDisciplina.urlDecode(),
                                      'lTemGradeHorario' : oRegencia.lTemGradeHorario });
  });
  this.oToggleRegencia.renderRows();

  this.fCallBackLoad();
};

/**
 * Retorna um array com as regencias selecionadas
 * @returns {Array}
 */
DBViewFormularioEducacao.ListaDisciplinas.prototype.getSelecionados = function () {
  return this.oToggleRegencia.getSelected();
};

/**
 * Limpa
 */
DBViewFormularioEducacao.ListaDisciplinas.prototype.clear = function () {
  this.oToggleRegencia.clearAll();
};

/**
 * Seta se devem ser trazidas somente disciplinas globais
 * @param  {Boolean} lSomenteDisciplinasGlobais
 */
DBViewFormularioEducacao.ListaDisciplinas.prototype.setSomenteDisciplinasGlobais = function( lSomenteDisciplinasGlobais ) {
  this.lSomenteDisciplinasGlobais = lSomenteDisciplinasGlobais;
}

/**
 * Renderiza o toggle em um elemento HTML
 * @param oElement {HTMLElement}
 */
DBViewFormularioEducacao.ListaDisciplinas.prototype.show = function( oElement ) {
  this.oToggleRegencia.show(oElement );
};
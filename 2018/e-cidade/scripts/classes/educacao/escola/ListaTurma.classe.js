require_once("scripts/arrays.js");
require_once("scripts/classes/educacao/DBViewFormularioEducacao.classe.js");

/**
 * Monta um select com uma lista de escolas
 * @dependency Utiliza DBViewFormularioEducacao.classe.js
 * @autor Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.9 $
 * @package Educacao
 * @subpackage Escola
 * @example
 *         var oTurma = new DBViewFormularioEducacao.ListaTurma();
 *
 *         var fFuncaoLoad = function() {
 *           alert('Chamei após carregar');
 *         };
 *
 *         var fFuncaoChange = function () {
 *           alert(oTurma.getSelecionados().toSource());
 *         };
 *
 *         oTurma.setCallBackLoad(fFuncaoLoad);       // Opcional
 *         oTurma.setCallbackOnChange(fFuncaoChange); // Opcional
 *         oTurma.habilitarOpcaoTodas(true);          // Opcional
 *         oTurma.show($('listaTurma'));
 *
 * @returns {void}
 */
DBViewFormularioEducacao.ListaTurma = function() {

  /**
   * Nome do arquivo RPC para as requisições ajax
   * @var string
   */
  this.sUrlRPC = "edu4_turmas.RPC.php";

  /**
   * Função callback ao carregar os dados
   * @var function
   */
  this.fCallBackLoad = function () {
    return true;
  };

  /**
   * Função callback ao selecionar um option do select
   * @var function
   */
  this.fCallbackOnChange = function () {
    return true;
  };

  /**
   * Se true, adiciona no combobox o option 'Todas'
   * @var boolean
   */
  this.lHabilitaOpcaoTodas = false;

  /**
   * Código da escola que devemos buscar as etapas
   * OBS.: Se 0, busca de todas as escolas
   * @var integer
   */
  this.iEscola = 0;

  /**
   * Código do calendário que devemos buscar as etapas
   * OBS.: Se 0, busca de todos os calendários
   *       Pode ser uma lista separada por ", "
   *         exemplo : 123,124,125
   * @var integer
   */
  this.iCalendario = 0;

  /**
   * Código da etapa que devemos buscar as turmas
   * OBS.: Se 0, busca de todas as etapas
   * @var {integer}
   */
  this.iEtapa = 0;

  /**
   * Elemento select das turmas
   * @var HTMLElement
   */
  this.oCboTurma             = document.createElement("select");
  this.oCboTurma.id          = 'cboTurma';
  this.oCboTurma.style.width = "100%";
  this.oCboTurma.add(new Option("Selecione uma Turma", ""));

  this.lSomenteComCriterioAvaliacao  = false;
  this.lSomenteComAlunosMatriculados = true;
  this.lSomenteAtivas                = false;
  this.aTipoTurmaFora                = new Array();
};

/**
 * Define uma função para ser executada após o carregamento dos dados
 * @param function fFunction
 * @return {void}
 */
DBViewFormularioEducacao.ListaTurma.prototype.setCallBackLoad = function (fFunction) {
  this.fCallBackLoad = fFunction;
};

/**
 * Define uma função para ser executada ao mudar a seleção no combobox
 * @param function fFunction
 * @return {void}
 */
DBViewFormularioEducacao.ListaTurma.prototype.setCallbackOnChange = function (fFunction) {

  this.fCallbackOnChange = fFunction;
  this.oCboTurma.stopObserving('change');
  this.oCboTurma.observe('change', function() {
    fFunction();
  });
};

/**
 * Método que define se será exibido a opção 'Todas'
 * @param boolean lHabilta
 */
DBViewFormularioEducacao.ListaTurma.prototype.habilitarOpcaoTodas = function (lHabilta) {

  this.lHabilitaOpcaoTodas = lHabilta;
};

/**
 * Retorna o option selecionado no comboBox
 * @returns Object
 */
DBViewFormularioEducacao.ListaTurma.prototype.getSelecionados = function () {

  var oRetorno          = {};
  oRetorno.codigo_turma = this.oCboTurma.value;
  oRetorno.turma        = this.oCboTurma.options[this.oCboTurma.selectedIndex].innerHTML;
  oRetorno.codigo_etapa = this.oCboTurma.options[this.oCboTurma.selectedIndex].getAttribute("etapa");
  return oRetorno;
};

/**
 * Define uma escola para ser buscada as turmas
 * @param  {integer} iEscola Código da escola
 * @return {void}
 */
DBViewFormularioEducacao.ListaTurma.prototype.setEscola = function (iEscola) {
  this.iEscola = iEscola;
};

/**
 * Define o calendário que sera buscado as turmas
 * @param  {mixed} iCalendario código do calendário (pode ser uma lista separada por ", ")
 *                             exemplo : 123,124,125
 * @return {void}
 */
DBViewFormularioEducacao.ListaTurma.prototype.setCalendario = function (iCalendario) {
  this.iCalendario = iCalendario;
};

/**
 * Define a etapa que será buscado as turmas
 * @param  {integer} iEtapa Código da etapa
 * @return {void}
 */
DBViewFormularioEducacao.ListaTurma.prototype.setEtapa = function (iEtapa) {
  this.iEtapa = iEtapa;
};

/**
 * Define os tipos de turma que não devem retornar
 * @param {array} aTipoTurmaFora
 */
DBViewFormularioEducacao.ListaTurma.prototype.setTipoTurmaFora = function (aTipoTurmaFora) {
  this.aTipoTurmaFora = aTipoTurmaFora;
};

/**
 * Realiza a pesquisa das turmas cadastrada no sistema para os filtros informados
 * @returns {void}
 */
DBViewFormularioEducacao.ListaTurma.prototype.getTurmas = function () {

  var oSelf = this;

  var oParametro                           = new Object();
  oParametro.exec                          = 'buscaTurmasPorCalendarioEscola';
  oParametro.iEscola                       = this.iEscola;
  oParametro.iCalendario                   = this.iCalendario;
  oParametro.iEtapa                        = this.iEtapa;
  oParametro.lSomenteComCriterioAvaliacao  = this.lSomenteComCriterioAvaliacao;
  oParametro.lSomenteComAlunosMatriculados = this.lSomenteComAlunosMatriculados;
  oParametro.lSomenteAtivas                = this.lSomenteAtivas;
  oParametro.aTipoTurmaFora                = this.aTipoTurmaFora;

  js_divCarregando(_M("educacao.escola.ListaTurma.pesquisando_turmas"), "msgBox");

  var oObjeto        = {};
  oObjeto.method     = 'post';
  oObjeto.parameters = 'json='+Object.toJSON(oParametro);
  oObjeto.onComplete = function(oAjax) {
                       oSelf.retornoTurma(oAjax);
                     };
  oObjeto.asynchronous = false;

  new Ajax.Request(oSelf.sUrlRPC, oObjeto);
};

/**
 * Trata o retorno das turmas buscadas pelo metodo getTurmas()
 * @param  {Object} oAjax
 * @return {void}
 */
DBViewFormularioEducacao.ListaTurma.prototype.retornoTurma = function (oAjax) {

  js_removeObj("msgBox");
  var oSelf    = this;
  var oRetorno = eval("(" + oAjax.responseText + ")");

  if (oRetorno.status != 1) {

    alert(_M('educacao.escola.ListaTurma.nenhuma_turma_encontrada'));
    return false;
  }

  this.limpar();

  var iContadorTurma = oRetorno.aTurmas.length;

  if (oSelf.lHabilitaOpcaoTodas && iContadorTurma > 1) {
    this.oCboTurma.add(new Option("Todas", 0));
  }

  oRetorno.aTurmas.each( function (oTurma) {

    var sDescricao = oTurma.sTurma.urlDecode() + ' - ' + oTurma.sEtapa.urlDecode();
    var oOption = new Option( sDescricao, oTurma.iTurma );
    oOption.setAttribute("etapa", oTurma.iEtapa);
    oSelf.oCboTurma.add(oOption);
  });

  if (iContadorTurma == 1) {
    oSelf.oCboTurma.value = oRetorno.aTurmas[0].iTurma;
  }

  oSelf.fCallBackLoad();
};

/**
 * Renderiza o comboBox com a lista de turmas
 * @param  {HTMLElement} oElement Node onde será renderizado o comboBox
 * @return {void}
 */
DBViewFormularioEducacao.ListaTurma.prototype.show = function (oElement) {
  oElement.appendChild(this.oCboTurma);
};


/**
 * Limpa as informações da lista de Turmas
 * @return {void}
 */
DBViewFormularioEducacao.ListaTurma.prototype.limpar = function () {

  this.oCboTurma.options.length = 0;
  this.oCboTurma.add(new Option("Selecione uma Turma", ""));
};

DBViewFormularioEducacao.ListaTurma.prototype.somenteComCriterioAvaliacao = function( lSomenteComCriterioAvaliacao ) {
  this.lSomenteComCriterioAvaliacao = lSomenteComCriterioAvaliacao;
};

/**
 * Seta se devem ser pesquisadas turmas somente com alunos matriculados
 * @param {bool} lSomenteComAlunosMatriculados
 */
DBViewFormularioEducacao.ListaTurma.prototype.somenteComAlunosMatriculados = function( lSomenteComAlunosMatriculados ) {
  this.lSomenteComAlunosMatriculados = lSomenteComAlunosMatriculados;
};

/**
 * Seta se devem ser apresentadas somente turmas ativas
 * @param {bool} lSomenteAtivas
 */
DBViewFormularioEducacao.ListaTurma.prototype.somenteAtivas = function( lSomenteAtivas ) {
  this.lSomenteAtivas = lSomenteAtivas;
};
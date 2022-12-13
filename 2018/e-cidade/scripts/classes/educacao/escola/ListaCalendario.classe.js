require_once("scripts/classes/educacao/DBViewFormularioEducacao.classe.js");
require_once("scripts/strings.js");
/**
 *
 * Cria um ComboBox com as calendarios de uma determinada escola
 * Permite mostrar os calendarios agrupados por ano, atraves do metodo agruparPorAno
 * @author Iuri Guntchnigg - iuri@dbseller.com.br
 * @revision Andrio Costa - andrio.costa@dbseller.com.br
 * @package Educacao
 * @subpackage Escola
 * @example
 * var oCboCalendarios = new DBViewFormularioEducacao.ListaCalendario();
 * oCbCalendario.setCallBackLoad(function() {
 *    alert('Carregou todos os calendarios');
 * });
 * oCbCalendario.show(oDiv);
 *
 * @version $Revision: 1.9 $
 * @returns {DBViewFormularioEducacao.ListaCalendario}
 *
 */
DBViewFormularioEducacao.ListaCalendario = function() {

  /**
   * Controle da exibição da opção todas
   */
  this.lOpcaoTodos = false;

  /**
   * Elemento select
   * @var HTMLElement
   */
  this.oElement = document.createElement("select");

  /**
   * RPC que deve ser chamado os dados
   * @var string
   */
  this.sUrlRPC = 'edu_educacaobase.RPC.php';

  /**
   * Define se devemos trazer somente os calendários com turma vinculádas ou todos calendários da escola selecionada
   * @var Boolean
   */
  this.lSomenteCalendarioComTurmaVinculada = true;

  /**
   * Codigo da escola
   * @var integer
   */
  this.iCodigoEscola = '';

  /**
   * lista de calendarios padroes
   */
  this.aCalendarios = new Array();

  /**
   * controle de calendarios com apenas turma Encerradas
   */
  this.lComTurmasEncerradas = false;

  /**
   * Controle de calendarios ativos
   * @var boolean
   */
  this.lApenasAtivos = true;

  /**
   * Agrupar os Calendários por ano
   * @var {Boolean}
   */
  this.lAgruparPorAno = false;

  /**
   * Controla para que sejam retornados somente calendários com turmas que tenham progressão encerrada
   * @type {boolean}
   */
  this.lSomenteTurmasComProgressaoEncerrada = false;

  /**
   * Callback padrao chamado apos o load dos calendarios
   * @var function
   */
  this.fCallBackAfterLoad = function() {
    return true;
  }

  /**
  * Função callback ao selecionar um option do select
  * @var function
  */
 this.fCallbackOnChange = function () {
   return true;
 };

  this.oElement.id          = 'cboCalendario';
  this.oElement.style.width = '100%';
};

/**
 * Carrega a lista de Calendários
 * Caso foi definido  a escola retorna os dados da mesma.
 */
DBViewFormularioEducacao.ListaCalendario.prototype.getCalendarios = function() {

  var oSelf = this;
  oSelf.limpar();
  js_divCarregando('Aguarde, pesquisando calendários', 'msgBoxCalendario');

  var oParametros  = new Object();
  oParametros.exec = "pesquisaCalendario";

  if (!this.lSomenteCalendarioComTurmaVinculada) {
    oParametros.exec = "pesquisaCalendarioEscola";
  }

  oParametros.turmas_encerradas                    = oSelf.lComTurmasEncerradas;
  oParametros.apenas_ativos                        = oSelf.lApenasAtivos;
  oParametros.lSomenteTurmasComProgressaoEncerrada = oSelf.lSomenteTurmasComProgressaoEncerrada;

  if (oSelf.iCodigoEscola != '') {
    oParametros.iEscola = oSelf.iCodigoEscola;
  }

  new Ajax.Request(oSelf.sUrlRPC,
                   {
                     method:'post',
                     parameters: 'json='+Object.toJSON(oParametros),
                     onComplete: function(oResponse) {
                        oSelf.preencherCalendarios(oResponse);
                     }
                   });
};
/**
 * Preenche os dados do calendario no combobox
 * @param { XMLHttpRequest} oResponse resposta da requisicao
 * @private
 */
DBViewFormularioEducacao.ListaCalendario.prototype.preencherCalendarios = function(oResponse) {

  var oSelf = this;
  js_removeObj('msgBoxCalendario');
  var oRetorno       = eval('('+oResponse.responseText+')');
  oSelf.aCalendarios = new Array();

  oRetorno.dados.each(function(oCalendarioRetorno) {

    var oCalendario         = {};
    oCalendario.iAno        = oCalendarioRetorno.ed52_i_ano;
    oCalendario.iCalendario = oCalendarioRetorno.ed52_i_codigo;
    oCalendario.sDescricao  = oCalendarioRetorno.ed52_c_descr;
    oSelf.aCalendarios.push(oCalendario);
  });

  if (this.lAgruparPorAno) {
    oSelf.preencherCalendarioPorAno();
  } else {
    oSelf.preencherCalendario();
  }

  oSelf.fCallBackAfterLoad(oSelf);
};

/**
 * Agrupa os calendário por ano letivo
 * @return {void}
 */
DBViewFormularioEducacao.ListaCalendario.prototype.preencherCalendarioPorAno = function () {

  var oSelf = this;
  var aAnos = new Array();

  this.oElement.add(new Option('Selecione um Ano Letivo', ''));

  oSelf.aCalendarios.each(function(oCalendario, iSeq) {

    if (!aAnos.in_array(oCalendario.iAno)) {
      aAnos.push(oCalendario.iAno);
    }
  });

  aAnos.each( function (iAno) {
    oSelf.oElement.add(new Option(iAno, iAno));
  });

  if (aAnos.length == 1) {
    oSelf.oElement.value = aAnos[0];
  }
};

/**
 * Exibe os calendários letivos da escola selecionada
 * @return {}
 */
DBViewFormularioEducacao.ListaCalendario.prototype.preencherCalendario = function () {

  var oSelf = this;

  this.oElement.add(new Option('Selecione um Calendário', ''));

  oSelf.aCalendarios.each(function(oCalendario, iSeq) {

    var oOption = new Option(oCalendario.sDescricao.urlDecode(), oCalendario.iCalendario);
    oOption.setAttribute("ano", oCalendario.iAno);
    oSelf.oElement.appendChild(oOption);
  });

  if (oSelf.aCalendarios.length == 1) {
    oSelf.oElement.value = oSelf.aCalendarios[0].iCalendario;
  }

  oSelf.fCallBackAfterLoad();
};
/**
 * Habilita a opção para selecionar  todas os calendarios
 * @param {bool} lHabilitar true para habilitar a Opçao TODOS no combobox
 */
DBViewFormularioEducacao.ListaCalendario.prototype.habilitarOpcaoTodos = function(lHabilitar) {
  this.lOpcaoTodos = lHabilitar;
};

/**
 * Define o codigo da escola que os calendários deveram ser pesquisados
 * @param {integer} iEscola codigo da escola
 */
DBViewFormularioEducacao.ListaCalendario.prototype.setEscola = function(iEscola) {

  this.iCodigoEscola = iEscola;
};

/**
 * Renderiza o comboBox no elemento oNode
 * @param oNode Elemento que deverá ser renderizado o comboBox
 */
DBViewFormularioEducacao.ListaCalendario.prototype.show = function(oNode) {

  oNode.appendChild(this.oElement);
  //this.getCalendarios();
};

/**
 * Funcao de Callback que sera executada apos os dados do calendario serem carregados
 * @param {function} fFunction funcao de Callback que devem ser executada
 */
DBViewFormularioEducacao.ListaCalendario.prototype.setCallBackLoad = function(fFunction) {

  if (typeof(fFunction) != 'function') {
    throw exception('parametro fFunction deve ser uma função!');
  }

  this.fCallBackAfterLoad = fFunction;
};

/**
 * Funcao de Callback que sera executada apos os dados do calendario serem carregados
 * @param {function} fFunction funcao de Callback que devem ser executada
 */
DBViewFormularioEducacao.ListaCalendario.prototype.setOnChangeCallBack = function(fFunction) {

  var oSelf = this;
  if (typeof(fFunction) != 'function') {
    throw exception('parametro fFunction deve ser uma função!');
  }
  this.fCallbackOnChange = fFunction;
  this.oElement.stopObserving('change');
  this.oElement.observe('change', function() {
    fFunction(oSelf.getSelecionados());
  });
};
/**
 * Retorna umn Objeto com os  dados do calendario Selecionado
 * retorna {codigo, nome, ano}
 * @returns Object
 */
DBViewFormularioEducacao.ListaCalendario.prototype.getSelecionados = function() {

  var iSelectedIndex = this.oElement.selectedIndex;
  var mSelecionado   = null;
  if (this.lAgruparPorAno) {

    mSelecionado = new Array();
    var iAno         = this.oElement.options[iSelectedIndex].value;
    this.aCalendarios.each(function(oCalendario, iSeq){
      if (oCalendario.iAno == iAno) {
        mSelecionado.push(oCalendario);
      }
    });

  } else {
     mSelecionado = {
       iCalendario  : this.oElement.options[iSelectedIndex].value,
       sDescricao  : this.oElement.options[iSelectedIndex].innerHTML,
       iAno         : this.oElement.options[iSelectedIndex].getAttribute("ano")
     };
  }
  return mSelecionado;
};

/**
 * Retorna apenas calendarios ativos
 * @param {Boolean} lApenasAtivos
 */
DBViewFormularioEducacao.ListaCalendario.prototype.apenasAtivos = function(lApenasAtivos) {
  this.lApenasAtivos = lApenasAtivos;
};

/**
 * Retorna apenas os calendarios que possuam ao menos uma turma encerrada
 * @param {boolean} lEncerradas
 */
DBViewFormularioEducacao.ListaCalendario.prototype.calendariosComTurmasEncerradas = function(lEncerradas) {
  this.lComTurmasEncerradas = lEncerradas;
};

/**
 * Agrupa os calendários por ano
 * @param  {boolean} lAgruparPorAno true para agrupar por ano
 * @return  {void}
 */
DBViewFormularioEducacao.ListaCalendario.prototype.agruparPorAno = function(lAgruparPorAno) {
  this.lAgruparPorAno = lAgruparPorAno;
};

/**
 * Apaga os dados do combobox
 */
DBViewFormularioEducacao.ListaCalendario.prototype.limpar = function() {
  this.oElement.options.length = 0;
};


/**
 * Desabilita/Habilita seleção no combobox
 * @param lDesativar
 */
DBViewFormularioEducacao.ListaCalendario.prototype.permitirSelecao = function(lPermitir) {

  this.oElement.removeAttribute("disabled");
  if ( !lPermitir ) {
    this.oElement.setAttribute("disabled", "disabled");
  }
};

/**
 * Seta se devem ser carregados calendários com turmas que tenham progressão encerrada
 * @param {boolean} lSomenteTurmasComProgressaoEncerrada
 */
DBViewFormularioEducacao.ListaCalendario.prototype.somenteTurmasComProgressaoEncerrada = function( lSomenteTurmasComProgressaoEncerrada ) {
  this.lSomenteTurmasComProgressaoEncerrada = lSomenteTurmasComProgressaoEncerrada;
};
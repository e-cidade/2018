require_once("scripts/arrays.js");
require_once("scripts/classes/educacao/DBViewFormularioEducacao.classe.js");

/**
 * Monta um select com uma lista de escolas
 * @dependency Utiliza DBViewFormularioEducacao.classe.js
 * @autor Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.3 $
 * @package Educacao
 * @subpackage Escola 
 * @example 
 *         var oTurma = new DBViewFormularioEducacao.ListaTurma();
 *        
 *         var fFuncaoLoad = function() {
 *           alert('Chamei ap�s carregar');
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
   * Nome do arquivo RPC para as requisi��es ajax
   * @var string
   */
  this.sUrlRPC = "edu_educacaobase.RPC.php";
  
  /**
   * Fun��o callback ao carregar os dados
   * @var function
   */
  this.fCallBackLoad = function () {
    return true;
  };
  
  /**
   * Fun��o callback ao selecionar um option do select 
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
   * C�digo da escola que devemos buscar as etapas
   * OBS.: Se 0, busca de todas as escolas
   * @var integer
   */
  this.iEscola = 0;

  /**
   * C�digo do calend�rio que devemos buscar as etapas
   * OBS.: Se 0, busca de todos os calend�rios
   *       Pode ser uma lista separada por ", "
   *         exemplo : 123,124,125 
   * @var integer
   */
  this.iCalendario = 0;

  /**
   * C�digo da etapa que devemos buscar as turmas
   * OBS.: Se 0, busca de todas as etapas
   * @var {integer}
   */
  this.iEtapa = 0;

  /**
   * Elemento select das turmas
   * @var HTMLElement
   */
  this.oCboTurma = document.createElement("select");
  this.oCboTurma.style.width = "100%";
};


/**
 * Define uma fun��o para ser executada ap�s o carregamento dos dados
 * @param function fFunction
 * @return {void}
 */
DBViewFormularioEducacao.ListaTurma.prototype.setCallBackLoad = function (fFunction) {
  
  this.fCallBackLoad = fFunction;
}; 

/**
 * Define uma fun��o para ser executada ao mudar a sele��o no combobox
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
 * M�todo que define se ser� exibido a op��o 'Todas'
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
 * @param  {integer} iEscola C�digo da escola
 * @return {void}         
 */
DBViewFormularioEducacao.ListaTurma.prototype.setEscola = function (iEscola) {

  this.iEscola = iEscola;
};

/**
 * Define o calend�rio que sera buscado as turmas
 * @param  {mixed} iCalendario c�digo do calend�rio (pode ser uma lista separada por ", ")
 *                             exemplo : 123,124,125
 * @return {void}
 */
DBViewFormularioEducacao.ListaTurma.prototype.setCalendario = function (iCalendario) {

  this.iCalendario = iCalendario;
};

/**
 * Define a etapa que ser� buscado as turmas
 * @param  {integer} iEtapa C�digo da etapa
 * @return {void}
 */
DBViewFormularioEducacao.ListaTurma.prototype.setEtapa = function (iEtapa) {

  this.iEtapa = iEtapa;
};

/**
 * Realiza a pesquisa das turmas cadastrada no sistema para os filtros informados
 * @returns {void}
 */
DBViewFormularioEducacao.ListaTurma.prototype.getTurmas = function () {
  
  var oSelf = this;
  
  var oParametro         = new Object();
  oParametro.exec        = 'pesquisaTurmaEtapa';
  oParametro.iEscola     = this.iEscola;
  oParametro.iCalendario = this.iCalendario;
  oParametro.iEtapa      = this.iEtapa;
  
  js_divCarregando(_M("educacao.escola.ListaTurma.pesquisando_turmas"), "msgBox");

  var oObjeto        = {};
  oObjeto.method     = 'post';
  oObjeto.parameters = 'json='+Object.toJSON(oParametro);
  oObjeto.onComplete = function(oAjax) {
                       oSelf.retornoTurma(oAjax);
                     };
  
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

  var iContadorTurma = oRetorno.dados.length;

  this.oCboTurma.add(new Option("Selecione uma Turma", ""));

  if (oSelf.lHabilitaOpcaoTodas && iContadorTurma > 1) {
    this.oCboTurma.add(new Option("Todas", 0));
  }

  oRetorno.dados.each( function (oTurma) {
    
    var oOption = new Option(oTurma.ed57_c_descr.urlDecode(), oTurma.ed57_i_codigo);
    oOption.setAttribute("etapa", oTurma.codigo_etapa);
    oSelf.oCboTurma.add(oOption);
  });

  if (iContadorTurma == 1) {
    oSelf.oCboTurma.value = oRetorno.dados[0].ed57_i_codigo;
  }

  oSelf.fCallBackLoad();
};

/**
 * Renderiza o comboBox com a lista de turmas
 * @param  {HTMLElement} oElement Node onde ser� renderizado o comboBox
 * @return {void}          
 */
DBViewFormularioEducacao.ListaTurma.prototype.show = function (oElement) {

  oElement.appendChild(this.oCboTurma);
};


/**
 * Limpa as informa��es da lista de Turmas
 * @return {void} 
 */
DBViewFormularioEducacao.ListaTurma.prototype.limpar = function () {

  this.oCboTurma.options.length = 0;
};

require_once("scripts/arrays.js");
require_once("scripts/classes/educacao/DBViewFormularioEducacao.classe.js");
/**
 * Monta um select com uma lista de etapas
 * @dependency Utiliza DBViewFormularioEducacao.classe.js
 * @autor Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.4 $
 * @package Educacao
 * @subpackage Escola 
 * @example 
 *         var oEtapa = new DBViewFormularioEducacao.ListaEtapa();
 *        
 *         var fFuncaoLoad = function() {
 *           alert('Chamei ap�s carregar');
 *         };
 *        
 *         var fFuncaoChange = function () {
 *           alert(oEtapa.getSelecionados().toSource());
 *         };
 *         
 *         oEtapa.setCallBackLoad(fFuncaoLoad);       // Opcional
 *         oEtapa.setCallbackOnChange(fFuncaoChange); // Opcional 
 *         oEtapa.habilitarOpcaoTodas(true);          // Opcional 
 *         oEtapa.show($('listaEtapas'));
 * 
 * @returns {void}
 */
DBViewFormularioEducacao.ListaEtapa = function() {

  /**
   * Nome do arquivo RPC para as requisi��es ajax
   * @var string
   */
  this.sUrlRPC = "edu4_etapas.RPC.php";
  
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
   * @var integer
   */
  this.iCalendario = 0;

  /**
   * Elemento select das etapas
   * @var HTMLElement
   */
  this.oCboEtapas = document.createElement("select");
  this.oCboEtapas.style.width = "100%";
};



/**
 * Define uma fun��o para ser executada ap�s o carregamento dos dados
 * @param function fFunction
 * @return {void}
 */
DBViewFormularioEducacao.ListaEtapa.prototype.setCallBackLoad = function (fFunction) {
  
  this.fCallBackLoad = fFunction;
}; 

/**
 * Define uma fun��o para ser executada ao mudar a sele��o no combobox
 * @param function fFunction
 * @return {void}
 */
DBViewFormularioEducacao.ListaEtapa.prototype.setCallbackOnChange = function (fFunction) {
  
  this.fCallbackOnChange = fFunction;
  this.oCboEtapas.stopObserving('change');
  this.oCboEtapas.observe('change', function() {
    fFunction();
  });
};

/**
 * M�todo que define se ser� exibido a op��o 'Todas'
 * @param boolean lHabilta
 */
DBViewFormularioEducacao.ListaEtapa.prototype.habilitarOpcaoTodas = function (lHabilta) {
  
  this.lHabilitaOpcaoTodas = lHabilta;
};

/**
 * Retorna o option selecionado no comboBox
 * @returns Object
 */
DBViewFormularioEducacao.ListaEtapa.prototype.getSelecionados = function () {
  
  var oRetorno          = {};
  oRetorno.codigo_etapa = this.oCboEtapas.value;
  oRetorno.etapa        = this.oCboEtapas.options[this.oCboEtapas.selectedIndex].innerHTML;
  return oRetorno;
};

/**
 * Define uma escola para ser buscada as etapas 
 * @param  {integer} iEscola C�digo da escola
 * @return {void}         
 */
DBViewFormularioEducacao.ListaEtapa.prototype.setEscola = function (iEscola) {

  this.iEscola = iEscola;
};

/**
 * Define o calend�rio que sera buscado as etapas 
 * @param  {integer} iCalendario c�digo do calend�rio
 * @return {void}
 */
DBViewFormularioEducacao.ListaEtapa.prototype.setCalendario = function (iCalendario) {

  this.iCalendario = iCalendario;
};


/**
 * Realiza a pesquisa das etapas cadastrada no sistema para os filtros informados
 * @returns {void}
 */
DBViewFormularioEducacao.ListaEtapa.prototype.pesquisaEtapas = function () {
  
  var oSelf = this;
  
  var oParametro         = new Object();
  oParametro.exec        = 'pesquisaEtapas';
  oParametro.iEscola     = this.iEscola;
  oParametro.iCalendario = this.iCalendario;
  
  js_divCarregando("Aguarde, pesquisando etapas.", "msgBox");

  var oObjeto        = {};
  oObjeto.method     = 'post';
  oObjeto.parameters = 'json='+Object.toJSON(oParametro);
  oObjeto.onComplete = function(oAjax) {
                       oSelf.retornoEtapas(oAjax);
                     };
  
  new Ajax.Request(oSelf.sUrlRPC, oObjeto);
};

/**
 * Trata o retorno do metodo pesquisaEtapas montando o comboBox com os par�metros configurados
 * @param Object oAjax
 * @returns {void}
 */
DBViewFormularioEducacao.ListaEtapa.prototype.retornoEtapas = function (oAjax) {
  
  var oSelf = this;
  js_removeObj('msgBox');
  var oRetorno = eval ('(' +oAjax.responseText+ ')');

  if (oRetorno.status != 1) {
    
    alert(_M('educacao.escola.ListaEtapa.nenhuma_etapa_encontrada'));
    return false;
  }
  
  oSelf.limpar();

  var iContadorEtapa = oRetorno.dados.length;
  
  oSelf.oCboEtapas.add(new Option('Selecione uma Etapa', ''));
 
  if (oSelf.lHabilitaOpcaoTodas && iContadorEtapa > 1) {
    oSelf.oCboEtapas.add(new Option('Todas', '0'));
  }
  
  oRetorno.dados.each( function (oDado) {

    var sLabel = oDado.etapa.urlDecode() + " - " + oDado.ensino.urlDecode();
    oSelf.oCboEtapas.add(new Option(sLabel, oDado.codigo_etapa));    
  }); 
  
  if (iContadorEtapa == 1) {
    oSelf.oCboEtapas.value = oRetorno.dados[0].codigo_etapa;
  }
  
  oSelf.fCallBackLoad();
  
};

/**
 * Renderiza o comboBox com a lista de etapas.
 * @param oElement id onde ser� renderizado o comboBox
 * @returns {void}
 */
DBViewFormularioEducacao.ListaEtapa.prototype.show = function(oElement) {
  
  oElement.appendChild(this.oCboEtapas);
}; 

/**
 * Remove os options do comboBox das Etapas
 * @return {void}
 */
DBViewFormularioEducacao.ListaEtapa.prototype.limpar = function() {
  this.oCboEtapas.options.length = 0;
};
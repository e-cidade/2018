require_once("scripts/arrays.js");
require_once("scripts/classes/educacao/DBViewFormularioEducacao.classe.js");
/**
 * Monta um select com uma lista de etapas
 * @dependency Utiliza DBViewFormularioEducacao.classe.js
 * @autor Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.5 $
 * @package Educacao
 * @subpackage Escola 
 * @example 
 *         var oEtapa = new DBViewFormularioEducacao.ListaEtapa();
 *        
 *         var fFuncaoLoad = function() {
 *           alert('Chamei após carregar');
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
   * Nome do arquivo RPC para as requisições ajax
   * @var string
   */
  this.sUrlRPC = "edu4_etapas.RPC.php";
  
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
   * @var integer
   */
  this.iCalendario = 0;

  /**
   * Elemento select das etapas
   * @var HTMLElement
   */
  this.oCboEtapas = document.createElement("select");
  this.oCboEtapas.style.width = "100%";
  this.oCboEtapas.add( new Option( 'Selecione uma Etapa', '' ) );
};

/**
 * Define uma função para ser executada após o carregamento dos dados
 * @param function fFunction
 * @return {void}
 */
DBViewFormularioEducacao.ListaEtapa.prototype.setCallBackLoad = function (fFunction) {
  this.fCallBackLoad = fFunction;
}; 

/**
 * Define uma função para ser executada ao mudar a seleção no combobox
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
 * Método que define se será exibido a opção 'Todas'
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
 * @param  {integer} iEscola Código da escola
 * @return {void}         
 */
DBViewFormularioEducacao.ListaEtapa.prototype.setEscola = function (iEscola) {
  this.iEscola = iEscola;
};

/**
 * Define o calendário que sera buscado as etapas 
 * @param  {integer} iCalendario código do calendário
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
 * Trata o retorno do metodo pesquisaEtapas montando o comboBox com os parâmetros configurados
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
 * @param oElement id onde será renderizado o comboBox
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
  this.oCboEtapas.add( new Option( 'Selecione uma Etapa', '' ) );
};
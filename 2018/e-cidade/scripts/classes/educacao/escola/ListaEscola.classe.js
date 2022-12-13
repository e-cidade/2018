require_once("scripts/arrays.js");
require_once("scripts/classes/educacao/DBViewFormularioEducacao.classe.js");

/**
 * Monta um select com uma lista de escolas
 * @dependency Utiliza DBViewFormularioEducacao.classe.js
 * @autor Andrio Costa <andrio.costa@dbseller.com.br>
 * @autor Trucolo <trucolo@dbseller.com.br>
 * @version $Revision: 1.6 $
 * @package Educacao
 * @subpackage Escola
 * @example
 *         var oEscola = new DBViewFormularioEducacao.ListaEscola();
 *
 *         var fFuncaoLoad = function() {
 *           alert('Chamei após carregar');
 *         };
 *
 *         var fFuncaoChange = function () {
 *           alert(oEscola.getSelecionados().toSource());
 *         };
 *
 *         oEscola.setCallBackLoad(fFuncaoLoad);       // Opcional
 *         oEscola.setCallbackOnChange(fFuncaoChange); // Opcional
 *         oEscola.habilitarOpcaoTodas(true);          // Opcional
 *         oEscola.show($('listaEscola'));
 *
 * @returns {void}
 */
DBViewFormularioEducacao.ListaEscola = function () {

  /**
   * Nome do arquivo RPC para as requisições ajax
   * @var string
   */
  this.sUrlRPC = "edu_educacaobase.RPC.php";

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
	 * Elemento select das escolas
	 * @var HTMLElement
	 */
	this.oCboEscola    = document.createElement("select");
	this.oCboEscola.id = 'cboEscola';
};

/**
 * Define uma função para ser executada após o carregamento dos dados
 * @param function fFunction
 * @return {void}
 */
DBViewFormularioEducacao.ListaEscola.prototype.setCallBackLoad = function (fFunction) {

  this.fCallBackLoad = fFunction;
};

/**
 * Define uma função para ser executada ao mudar a seleção no combobox
 * @param function fFunction
 * @return {void}
 */
DBViewFormularioEducacao.ListaEscola.prototype.setCallbackOnChange = function (fFunction) {

  this.fCallbackOnChange = fFunction;
  this.oCboEscola.stopObserving('change');
  this.oCboEscola.observe('change', function() {
    fFunction();
  });
};

/**
 * Método que define se será exibido a opção 'Todas'
 * @param boolean lHabilta
 */
DBViewFormularioEducacao.ListaEscola.prototype.habilitarOpcaoTodas = function (lHabilta) {

  this.lHabilitaOpcaoTodas = lHabilta;
};

/**
 * Retorna o option selecionado no comboBox
 * @returns Object
 */
DBViewFormularioEducacao.ListaEscola.prototype.getSelecionados = function () {

  var oRetorno           = {};
  oRetorno.codigo_escola = this.oCboEscola.value;
  oRetorno.nome_escola   = this.oCboEscola.options[this.oCboEscola.selectedIndex].innerHTML;
  return oRetorno;
};

/**
 * Realiza a pesquisa das escolas cadastrada no sistema
 * @returns {{void}}
 */
DBViewFormularioEducacao.ListaEscola.prototype.pesquisaEscola = function () {

  var oSelf = this;

  var oParametro  = new Object();
  oParametro.exec = 'pesquisaEscola';

  js_divCarregando("Aguarde, pesquisando escolas.", "msgBox");

  var oObjeto        = {};
  oObjeto.method     = 'post';
  oObjeto.parameters = 'json='+Object.toJSON(oParametro);
  oObjeto.onComplete = function(oAjax) {
                       oSelf.retornoEscola(oAjax);
                     };

  new Ajax.Request(oSelf.sUrlRPC, oObjeto);
};

/**
 * Trata o retorno do metodo pesquisaEscola montando o comboBox com os parâmetros configurados
 * @param Object oAjax
 * @returns {void}
 */
DBViewFormularioEducacao.ListaEscola.prototype.retornoEscola = function (oAjax) {


  js_removeObj('msgBox');
  var oRetorno = eval ('(' +oAjax.responseText+ ')');

  if (oRetorno.status != 1) {

    alert(_M('educacao.escola.ListaEscola.nenhuma_escola_encontrada'));
    return false;
  }

  this.oCboEscola.options.length = 0;

  var iContEscolas = oRetorno.dados.length;

  this.oCboEscola.add(new Option('Selecione uma escola', ''));

  if (this.lHabilitaOpcaoTodas && iContEscolas > 1) {
    this.oCboEscola.add(new Option('Todas', '0'));
  }

  var oSelf = this;
  oRetorno.dados.each( function (oDado) {
    oSelf.oCboEscola.add(new Option(oDado.nome_escola.urlDecode(), oDado.codigo_escola));
  });

  if (iContEscolas == 1) {
    this.oCboEscola.value = oRetorno.dados[0].codigo_escola;
  }

  this.fCallBackLoad();

};

/**
 * Renderiza o o comboBox com a lista de escolas.
 * @param oElement id onde será renderizado o comboBox
 * @returns {void}
 */
DBViewFormularioEducacao.ListaEscola.prototype.show = function(oElement) {

  this.pesquisaEscola();
  oElement.appendChild(this.oCboEscola);
};
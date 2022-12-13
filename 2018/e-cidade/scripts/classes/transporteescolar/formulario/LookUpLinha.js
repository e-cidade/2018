
require_once("scripts/widgets/DBLookUp.widget.js");

/**
 * Monta uma lookUp de pesquisa para buscar as linhas de trasnporte
 *
 * @author Andrio Costa <andrio.costa@dbseller.com.br
 */
DBViewLinha.LookUpLinha = function () {

  this.aCamposAdicionais = [];

  this.sQueryString = null;

  this.lCallOnClick = function (aCampos) {
    return true;
  }
  this.lCallOnChange = function (aCampos) {
    return true;
  }
};

/**
 * Adiciona campos a mais para retorno na fun��o de pesquisa
 *
 * @example ['db_ed29_i_codigo', 'ed29_c_descr']
 * @param  {array} aCampos
 * @return {void}
 */
DBViewLinha.LookUpLinha.prototype.adicionaCampos = function (aCampos) {
  this.aCamposAdicionais     = [];
};

/**
 * Adiciona par�metros para ser informado por get a fun��o de pesquisa
 *
 * @example '&instit=1&ativo=sim'
 * @param  {string} sQueryString
 * @return {void}
 */
DBViewLinha.LookUpLinha.prototype.informarQueryString = function (sQueryString) {
  this.sQueryString = sQueryString;
};

/**
 * Ao clicar na ancora, executa no retorno
 * @param {function} fFunction
 */
DBViewLinha.LookUpLinha.prototype.setCallBackClick = function (fFunction) {
  this.lCallOnClick = fFunction;
};

/**
 * Ao digitar no campo de codigo, executa no retorno
 * @param {function} fFunction
 */
DBViewLinha.LookUpLinha.prototype.setCallBackChange = function (fFunction) {
  this.lCallOnChange = fFunction;
};

/**
 * Cria a ancora com os par�metros informados
 *
 * @param {HtmlElement} oLink      ancora
 * @param {HtmlElement} oCodigo    input para digitar o c�digo
 * @param {HtmlElement} oDescricao input para onde ser� redicionada a descri��o
 */
DBViewLinha.LookUpLinha.prototype.criarAncora = function (oLink, oCodigo, oDescricao) {

  // bloqueado digita��o de qualquer coisa que n�o valide RegExp("[^0-9]+")
  oCodigo.addEventListener('input', function( event ) {
    js_ValidaCampos(this, 1 ,'C�ddigo', 'f', 'f', event);
  });

  var oParamentros = {
    sArquivo              : 'func_linhatransporte.php',
    sLabel                : 'Pesquisa Linhas de Transporte',
    sObjetoLookUp         : 'db_iframe_linhatransporte',
    aCamposAdicionais     : this.aCamposAdicionais,
    sQueryString          : this.sQueryString
  };

  var oLookUp = new DBLookUp( oLink, oCodigo, oDescricao, oParamentros);

  oLookUp.setCallBack('onClick', this.lCallOnClick );
  oLookUp.setCallBack('onChange', this.lCallOnChange );
}
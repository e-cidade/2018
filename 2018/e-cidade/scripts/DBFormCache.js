require_once('scripts/DBFormCheckBoxCache.js');
require_once('scripts/DBFormSelectCache.js');
require_once('scripts/DBFormTextAreaCache.js');
require_once('scripts/DBFormTextCache.js');
require_once('scripts/strings.js');

/**
 * Cria um objeto de cache para um formulario
 * @class DBFormCache
 * @param {string} sNameInstance nome da instancia
 * @param {string} sUrl nome do formulario
 * @return void
 */
DBFormCache = function (sNameInstance, sUrl) {

  var me           = this;
  me.sNameInstance = sNameInstance;
  me.sRPC          = 'con4_DBCache.RPC.php';

  /**
   * Nome do formulario que esta sendo cacheado
   */
  me.sUrl    = sUrl;

  /**
   * Array de objetos com os elementos que serao cacheados
   * Cada elemento é repesentado por um objeto do seu tipo
   */
  me.aElements    = new Array();


  /**
   * Seta os elementos HTML dento da classe
   * @param aElements
   * @returns void
   */
  this.setElements = function (aElements) {

    aElements.each(function (oElement, iInd) {

      switch (oElement.type) {

        case 'text':

          me.aElements.push(new DBFormTextCache(oElement));
          break;

        case 'select-one':
        case 'combobox-one':

          me.aElements.push(new DBFormSelectCache(oElement));
          break;

        case 'select-multiple':
        case 'combobox-multiple':

          me.aElements.push(new DBFormSelectCache(oElement));
          break;

        case 'checkbox':

          me.aElements.push(new DBFormCheckBoxCache(oElement));
          break;

        case 'textarea':

          me.aElements.push(new DBFormTextAreaCache(oElement));
          break;

        default:
          break;
      }
    });
    return me;
  };

  /**
   * Limpa o array de elementos
   */
  this.clearElements = function() {
    me.aElements    = new Array();
  };

  /**
   * Salva os dados do formulario em um arquivo cache
   * @returns void
   */
  this.save = function() {

    var oObject    = new Object();
    oObject.exec   = 'saveCache';
    oObject.url    = me.sUrl;
    oObject.fields = new Array();

    me.aElements.each(function (oElement, iInd) {
      oObject.fields.push(oElement.toObject());
    });

    var oAjax   = new Ajax.Request (me.sRPC,
                                       {
                                        method:       'post',
                                        parameters:   'json='+Object.toJSON(oObject),
                                        onComplete:   me.js_retornoSalvar,
                                        asynchronous: false
                                       }
                                     );
  };

  this.js_retornoSalvar = function(oResponse) {

    var oRetorno = eval('('+oResponse.responseText+')');
  };

  /**
   * Deleta o arquivo de cache para o usuario e o formulario
   */
  this.remove = function() {

    var oObject    = new Object();
    oObject.exec   = 'delete';
    oObject.url    = me.sUrl;

    var oAjax   = new Ajax.Request (me.sRPC,
                                     {
                                      method:       'post',
                                      parameters:   'json='+Object.toJSON(oObject),
                                      onComplete:   me.js_retornoDelete,
                                      asynchronous: false
                                     }
                                   );
  };

  this.js_retornoDelete = function(oResponse) {

    var oRetorno = eval('('+oResponse.responseText+')');
  };
  /**
   * Carrega os dados do Cache no formulario
   * @returns void
   */
  this.load = function() {

    var oObject    = new Object();
    oObject.exec   = 'load';
    oObject.url    = me.sUrl;

    var oAjax   = new Ajax.Request (me.sRPC,
                                     {
                                      method:       'post',
                                      parameters:   'json='+Object.toJSON(oObject),
                                      onComplete:   me.js_retornoLoad,
                                      asynchronous: false
                                     }
                                   );
  };

  this.js_retornoLoad = function (oResponse) {

    var oRetorno = eval('('+oResponse.responseText+')');

    if( !oRetorno.dados ) {
      return;
    }

    var aElementsCache = me.aElements;

    me.aElements = new Array();
    me.setElements(oRetorno.dados.fields);
    me.aElements.each(function (oElement, iInd) {
      oElement.toElement();
    });
    me.aElements = new Array();
    me.aElements = aElementsCache;
  };
};
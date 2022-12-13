/**
 * Cria um objeto de cache para um elemento 'textarea' do formulario 
 * @class DBFormCheckBoxCache
 * @param {Element/Object} oElement Elemento HTML ou um Objeto com as propriedades  
 * @return void  
 */
DBFormCheckBoxCache = function (oElement) {
  
  var me      = this;
  me.oElement = oElement;

  /**
   * Converte um Elemento HTML em um objeto javascript 
   * @returns Object
   */
  this.toObject = function () {
    
    var oObject = new Object();
    
    oObject.id       = me.oElement.id;
    oObject.type     = me.oElement.type;
    oObject.value    = new Array();
    
    /**
     * Validamos se estamos tratando de um elemento HTML ou de um Objeto JS
     */
    if (me.oElement.checked) {
      oObject.value = true;
    } else {
      oObject.value = me.oElement.value;
    }
    
    return oObject;
  };
  
  /**
   * Atribui no DOM o Elemento HTML setando suas propriedades
   * @returns void
   */
  this.toElement = function() {
    
    if ($(me.oElement.id)) {
      
      $(me.oElement.id).checked  = me.oElement.value;
      $(me.oElement.id).disabled = me.oElement.disabled;
      $(me.oElement.id).readOnly = me.oElement.readOnly;
    }
  };
};
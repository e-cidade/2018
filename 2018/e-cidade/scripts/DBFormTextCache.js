/**
 * Cria um objeto de cache para um elemento 'text' do formulario 
 * @class DBFormTextCache
 * @param {Element/Object} oElement Elemento HTML ou um Objeto com as propriedades  
 * @return void  
 */
DBFormTextCache = function (oElement) {

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
    oObject.value    = encodeURIComponent(me.oElement.value);
    oObject.disabled = me.oElement.disabled;   
    oObject.readOnly = me.oElement.readOnly;
    
    return oObject;
  };
  
  /**
   * Atribui no DOM o Elemento HTML setando suas propriedades
   * @returns void
   */
  this.toElement = function() {
    
     if ($(me.oElement.id)) {
       
       $(me.oElement.id).value    = me.oElement.value.urlDecode();
       $(me.oElement.id).disabled = me.oElement.disabled
       $(me.oElement.id).readOnly = me.oElement.readOnly;
     }
  }
};
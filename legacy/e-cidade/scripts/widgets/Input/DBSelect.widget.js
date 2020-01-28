/**
 * Representa um campo de digitação do sistema
 * 
 * @author Rafael Nery <rafael.nery@dbseller.com.br>
 * @version  $Revision: 1.1 $
 *
 */
(function(exports) {

  
  /**
   * Construtor da Classe
   *
   * @constructor
   * @module DBInput
   *
   * @param inputElement {HTMLinputElement} elemento que será modificado
   */
  var DBSelect = function(inputElement) {
    
    if ( !!inputElement.getAttribute("dbinput-infected") ) {
      return inputElement.DBSelect;
    }
    
    this.itens         = [];
    this.type          = 'select'
    this.inputElement = inputElement;
    this.writable      = true;
    this.valid         = true;
    this.callbackError = function (sMensagem) {
      
      alert(sMensagem); 
      return false;
    };
    this.__infect();
  };
  
  DBSelect.create = function(inputElement) {
    return new this(inputElement);
  };
  
  DBSelect.prototype = {

    '__infect' : function() {

      this.inputElement.type = this.type;      
      this.inputElement.setAttribute('dbinput-infected', 'true');
      this.inputElement.DBSelect = this;
      return this;
    },
      
    'isWritable' : function(writable) {
      this.writable = !!writable;
    }, 

    'isValid' : function(event) {
      return this.valid;
    },

    'success' : function(event) {
      return true;
    },

    'getValue' : function() {
      return this.inputElement.value;
    },

    'setValue' : function(sValue) {
      this.inputElement.value = sValue;
    },

    'getElement' : function() {
      return this.inputElement;
    },

    'addOption' : function(id, value, attributes) {    
      
      var item = {'id': id, 'value': value};
      this.itens.push(item);
      var option = new Option(value, id); 
      
      
      if (attributes != null) {
        
        item.attributes = attributes;
        for (atribute of attributes) {          
          option.setAttribute(atribute.name, atribute.value);
        }
        option.setAttribute('item', item);
      }
      this.inputElement.add(option);
        
    },
    get value() {
      return this.getValue();
    },

    set value(valor) {
      return this.setValue(valor);
    }

  };
  
  exports.DBSelect = DBSelect;
  return DBSelect;
})(this);

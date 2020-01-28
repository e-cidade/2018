/**
 * Representa um campo de digitação do sistema
 * 
 * @author Rafael Nery <rafael.nery@dbseller.com.br>
 * @version  $Revision: 1.8 $
 *
 */
(function(exports) {

  require('ext/javascript/prototype.maskedinput.js');

  /**
   * Construtor da Classe
   *
   * @constructor
   * @module DBInput
   *
   * @param inputElement {HTMLInputElement} elemento que será modificado
   */
  var DBInput = function(inputElement) {

    if (!inputElement || !(inputElement instanceof HTMLInputElement)) {
      throw new TypeError("Elemento input deve ser especificado.");
    }

    if ( !!inputElement.getAttribute("dbinput-infected") ) {
      return inputElement.DBInput;
    }


    this.type         = this.type || 'text'; //Com ou(||) pois na herança já vem valor
    this.inputElement = inputElement;
    this.writable     = true;
    this.valid        = true;
    this.callbackError = function (sMensagem) { 
      alert(sMensagem); 
      return false;
    };
    this.__infect();
  };

  DBInput.create = function(inputElement) {
    return new this(inputElement);
  };

  DBInput.extend = function(callback) {
    return {
      value : callback
    };
  };

  DBInput.prototype = {

    '__infect' : function() {

      this.inputElement.type = this.type;
      this.inputElement.setAttribute('dbinput-infected', 'true');
      this.inputElement.DBInput = this;
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

    get value() {
      return this.getValue();
    },

    set value(valor) {
      return this.setValue(valor);
    }

  };

  exports.DBInput = DBInput;
  return DBInput;
})(this);

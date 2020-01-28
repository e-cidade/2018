/**
 * Cria um objeto de cache para um elemento 'select' do formulario
 * @class DBFormSelectCache
 * @param {Element/Object} oElement Elemento HTML ou um Objeto com as propriedades
 * @return void
 */

DBFormSelectCache = function (oElement) {

  var me      = this;
  me.oElement = oElement;

  /**
   * Converte um Elemento HTML em um objeto javascript
   * @returns Object
   */
  this.toObject = function () {

    var oObject = new Object();

    oObject.id       = me.oElement.id;
    oObject.type     = me.oElement.type == "select-one" ? "combobox-one" : "combobox-multiple";
    oObject.value    = new Array();

    /**
     * Validamos se estamos tratando de um elemento HTML ou de um Objeto JS
     */
    if (me.oElement.options) {

      for (var i = 0; i < me.oElement.options.length; i++) {

        if (me.oElement.options[i].selected) {
          oObject.value.push(encodeURIComponent(me.oElement.options[i].value));
        }
      }
    } else {
      oObject.value = encodeURIComponent(me.oElement.value);
    }

    oObject.disabled = me.oElement.disabled;
    oObject.readOnly = me.oElement.readOnly;

    return oObject;

  };

  /**
   * Atribui no DOM o Elemento HTML setando suas propriedades
   * @returns void
   */
  this.toElement = function() {

    // Verificamos se o elemento existe no formulario pelo id
    if ($(me.oElement.id)) {

      // Percorremos as opcoes do elemento
      for (var i = 0; i < $(me.oElement.id).options.length; i++) {

        /**
         *  Verificamos se os valores do select possue um valor em comum com nosso array de valores "me.oElement.value"
         *  Se sim setamos ele como selecionado
         */
        if (js_search_in_array(me.oElement.value, $(me.oElement.id).options[i].value)) {

          //$(me.oElement.id).options[i].value    =  me.oElement.value.urlDecode();
          $(me.oElement.id).options[i].selected = true;
        }
      }

      $(me.oElement.id).disabled = me.oElement.disabled;
      $(me.oElement.id).readOnly = me.oElement.readOnly;
    }
  };
};
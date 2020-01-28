
/**
 * Representa um campo de digita��o de valor monetario
 *
 * @author Rafael Nery <rafael.nery@dbseller.com.br>
 * @version  $Revision: 1.6 $
 *
 */
(function(exports, DBInput) {

	var DBInputValor = function () {

		this.type = 'valor';
		return DBInput.apply(this, arguments);
  };

	/**
   * Registrando Heran�a
   */
  DBInputValor.prototype = Object.create(DBInput.prototype, {

  	'__infect' : DBInput.extend(function() {

  		this.inputElement.placeholder = '00,00';
  		this.inputElement.size        = 10;
  		this.inputElement.maxLength   = 10;
      this.inputElement.observe('input', function(event) {

        this.value = this.value.replace('.', ',');
        this.value = this.value.replace(/[^0-9\,\.]/g, '').replace(/^(\,|\.)(\d*)$|(?:\,|\.)?(\d*)(\,|\.)?(\d{0,2})(\d*)(?:\,|\.)?/, '$1$2$3$4$5');
      });

      DBInput.prototype.__infect.apply(this, arguments);
    }),

    /**
     * Retorna o valor no formato decimal.
     *
     * @param  {string} Valor no formato monet�rio.
     * @return {float}  float
     */
    'getValue' : DBInput.extend(function() {
      return this.inputElement.value.getNumber();
    }),

    /**
     * Converte um valor decimal para a representa��o monet�ria.
     *
     * @param  {float}
     */
    'setValue' : DBInput.extend(function(value) {

      value = js_formatar(value, 'f');
      DBInput.prototype.setValue.apply(this, arguments);
    })

  });

  DBInputValor.prototype.constructor = DBInputValor;

  /**
   * Registrando m�dulo na memoria para execu��o
   */
  exports.DBInputValor = DBInputValor;
  return DBInputValor;

})(this, DBInput);

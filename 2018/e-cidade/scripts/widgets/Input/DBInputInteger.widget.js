/**
 * Representa um campo de digita��o de n�meros inteiros no sistema
 * 
 * @author Renan Silva <renan.silva@dbseller.com.br>
 * @version  $Revision: 1.1 $
 *
 */
(function(exports, DBInput) {

	function aplicarMascaraInteiro (event) {
		this.value = this.value.replace(/[^\d]*/g, '');
	}

	var DBInputInteger = function () {

		this.type = 'integer';
		return DBInput.apply(this, arguments);
	}

	/**
   * Registrando Heran�a
   */
  DBInputInteger.prototype = Object.create(DBInput.prototype, {

  	'__infect' : DBInput.extend(function() {

  		this.inputElement.observe('input', aplicarMascaraInteiro);

  		DBInput.prototype.__infect.apply(this, arguments)
  	}),

  	'getValue' : DBInput.extend(function() {
      return this.inputElement.value;
    }),
  });
		
  DBInputInteger.prototype.constructor = DBInputInteger;


  /**
   * Registrando m�dulo na memoria para execu��o
   */
  exports.DBInputInteger = DBInputInteger;
  return DBInputInteger;

})(this, DBInput);
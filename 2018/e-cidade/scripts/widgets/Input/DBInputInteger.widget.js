/**
 * Representa um campo de digitação de números inteiros no sistema
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
   * Registrando Herança
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
   * Registrando módulo na memoria para execução
   */
  exports.DBInputInteger = DBInputInteger;
  return DBInputInteger;

})(this, DBInput);
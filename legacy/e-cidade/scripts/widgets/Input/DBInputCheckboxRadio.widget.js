/**
 * Representa um campo de digitação de data do sistema
 * 
 * @author Renan Silva <renan.silva@dbseller.com.br>
 * @version  $Revision: 1.1 $
 *
 */
(function(exports, DBInput) {

	var DBInputCheckboxRadio = function () {
    return DBInput.apply(this, arguments);
	}

	/**
   * Registrando Herança
   */
  DBInputCheckboxRadio.prototype = Object.create(DBInput.prototype);

  DBInputCheckboxRadio.prototype.isChecked = function() {
    return this.inputElement.checked ? 1 : 0;
  }
		
  DBInputCheckboxRadio.prototype.constructor = DBInputCheckboxRadio;


  /**
   * Registrando módulo na memoria para execução
   */
  exports.DBInputCheckboxRadio = DBInputCheckboxRadio;
  return DBInputCheckboxRadio;

})(this, DBInput);
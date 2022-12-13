/**
 * Representa um campo de digita��o de data do sistema
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
   * Registrando Heran�a
   */
  DBInputCheckboxRadio.prototype = Object.create(DBInput.prototype);

  DBInputCheckboxRadio.prototype.isChecked = function() {
    return this.inputElement.checked ? 1 : 0;
  }
		
  DBInputCheckboxRadio.prototype.constructor = DBInputCheckboxRadio;


  /**
   * Registrando m�dulo na memoria para execu��o
   */
  exports.DBInputCheckboxRadio = DBInputCheckboxRadio;
  return DBInputCheckboxRadio;

})(this, DBInput);
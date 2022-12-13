/**
 * Representa um campo de digita��o de data do sistema
 * 
 * @author Renan Silva <renan.silva@dbseller.com.br>
 * @version  $Revision: 1.2 $
 *
 */
(function(exports, DBInputCheckboxRadio) {

	var DBCheckBox = function () {

		this.type = 'checkbox';
		return DBInputCheckboxRadio.apply(this, arguments);
	}

  DBCheckBox.prototype = Object.create(DBInputCheckboxRadio.prototype);
		
  DBCheckBox.prototype.constructor = DBCheckBox;


  /**
   * Registrando m�dulo na memoria para execu��o
   */
  exports.DBCheckBox = DBCheckBox;
  return DBCheckBox;

})(this, DBInputCheckboxRadio);
/**
 * Representa um campo de digitação de data do sistema
 * 
 * @author Renan Silva <renan.silva@dbseller.com.br>
 * @version  $Revision: 1.2 $
 *
 */
(function(exports, DBInputCheckboxRadio) {

	var DBRadio = function () {

		this.type = 'radio';
		return DBInputCheckboxRadio.apply(this, arguments);
	}

  DBRadio.prototype = Object.create(DBInputCheckboxRadio.prototype);
	
  DBRadio.prototype.constructor = DBRadio;


  /**
   * Registrando módulo na memoria para execução
   */
  exports.DBRadio = DBRadio;
  return DBRadio;

})(this, DBInputCheckboxRadio);
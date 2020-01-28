
/**
 * Representa um campo de digita��o de umn email
 * 
 * @author Rafael Nery <rafael.nery@dbseller.com.br>
 * @version  $Revision: 1.2 $
 *
 */
(function(exports, DBInput) {

	var DBInputEmail = function () {

		this.type = 'email';
		return DBInput.apply(this, arguments);
  };

	/**
   * Registrando Heran�a
   */
  DBInputEmail.prototype = Object.create(DBInput.prototype, {

  	'__infect' : DBInput.extend(function() {

  		this.inputElement.placeholder = 'destinatario@teste.com';
      DBInput.prototype.__infect.apply(this, arguments);
    }),

    'isValid' : DBInput.extend(function(){
      
      var regex = /^[\w\._-]+@([\w\._-]+\.)[\w]{2,3}/; 
      return regex.test(this.getValue());
    })
  });
		
  DBInputEmail.prototype.constructor = DBInputEmail;

  /**
   * Registrando m�dulo na memoria para execu��o
   */
  exports.DBInputEmail = DBInputEmail;
  return DBInputEmail;

})(this, DBInput);


/**
 * Representa um campo de digitação de umn email
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
   * Registrando Herança
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
   * Registrando módulo na memoria para execução
   */
  exports.DBInputEmail = DBInputEmail;
  return DBInputEmail;

})(this, DBInput);

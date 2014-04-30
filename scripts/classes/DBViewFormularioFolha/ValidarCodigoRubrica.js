require_once('scripts/classes/DBViewFormularioFolha/DBViewFormularioFolha.classe.js');

/**
 * Função responsável por validar se a Rubrica informada possui 4 digitos,
 * ou se inicia com a Letra R seguida de 3 digitos.
 *
 * @param object oCodigo 
 * 
 * @access public
 * @return bollean
 */
DBViewFormularioFolha.ValidarCodigoRubrica = function(oCodigo){

	var sValorRubrica 		= oCodigo.value;
	var oExpressaoRegular = new RegExp(/^([0-9]|R)[0-9]{3}$/g);

	if ( !oExpressaoRegular.test(sValorRubrica) ) {
		return false;
	} 

  /**
   * Valida se não foi passada a Rubrica R000 ou 0000
   */
  var iNumeroRubrica = new Number( sValorRubrica.replace(/R/g, 0) );

  if ( iNumeroRubrica == 0 ) { 
    return false;
  }

	return true;	
}

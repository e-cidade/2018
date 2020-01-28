/**
 * Fun��es para formata��o e controle de numeros
 * @package numbers.js
 */

/**
 * Fun��o para validar numero inteiro ou float, negativo ou positivo
 * @deprecated
 * @param  iValor valor a ser verificado float ou integer
 * @author Tales Baz
 * @return boolean
 */
function js_validaSomenteNumeros(iValor) {
  return isNumeric(iValor);
}

/**
 * Fun��o para validar numero inteiro ou float, negativo ou positivo
 * @param  iValor valor a ser verificado float ou integer
 * @author Tales Baz
 * @return boolean
 */
function isNumeric( iValor, lApenasPositivos ) {

  lApenasPositivos = lApenasPositivos || false;
  
  var sRegex  = /^-?(?:\d+|\d*\.\d+)$/;
  
  if ( lApenasPositivos ) {
    sRegex =/^(?:\d+|\d*\.\d+)$/;
  }
  return sRegex.test(iValor);
}



;
/**
 * http://kevin.vanzonneveld.net
 * original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
 * improved by: Legaev Andrey                                      
 * improved by: Michael White (http://getsprink.com)
 * @exemple 
 *   example 1: is_object('23');                                   
 *   returns 1: false                                              
 *   example 2: is_object({foo: 'bar'});                           
 *   returns 2: true                                               
 *   example 3: is_object(null);                                   
 *   returns 3: false                                              
 * @param oObject
 * @returns {Boolean}
 */
function isObject (oObject) {
  
  if (Object.prototype.toString.call(oObject) === '[object Array]') {
    return false;
  };
  return oObject !== null && typeof oObject === 'object';
};


/**
 * Mescla os atributos do objeto passado por parametro nos de origem
 * Caso o atributo passado não exista ele cria.
 *
 * @author  Rafael Nery - <rafael.nery@dbseller.com.br>
 * @example 
 *   var oElemento = {
 *     "sId"    : "Testes",
 *     "sValor" : "Valor de Teste"
 *   }
 *
 *   oElemento.merge( {"sValor" : "Modificicacao", "atributo" : "Adicional"} );
 *   
 *   //returns { "sId" : "Testes", "sValor": "Modificicacao", "atributo" : "Adicional"}
 */
mergeObject = function( oTarget, oObject ) {

  
  for (var sChave in oObject ) {
    oTarget[sChave] = oObject[sChave];
  };
  return oTarget;
}


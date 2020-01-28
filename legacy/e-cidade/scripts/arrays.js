
/**
 * Retorna uma string contendo os elementos da matriz na mesma ordem com uma ligação entre cada elemento.
 * @param {string} sGlue string a ser concatenada
 * @type array;
 *
 */
Array.prototype.implode = function (sGlue) {

   if (sGlue == null) {
     sGlue = " ";
   }
   var iLenght = this.length;
   var sReturn =  "";
   var sGlued  = "";
   for (var i = 0; i < iLenght; i++) {

      sReturn += sGlued+this[i];
      sGlued  = sGlue;

   }
   return sReturn;
}

/**
 * Função para verificar existencia de um valor em um array
 * @param valor any   valor procurado
 * @param vetor array vetor a ser percorrido
 * @returns
 */
Array.prototype.in_array = function( sValorRecebido ) {

  for (var i in this) {

    var sValor = this[i];

    if ( sValorRecebido == sValor ) {
      return true;
    }
  }

  return false;
};

/**
 * Remove todas as ocorrencias de um valor no array
 * @param  element
 * @return array
 */
Array.prototype.remove = function( element ) {

  var iIndex = -1;

  while ((iIndex = this.indexOf(element)) != -1) {
    this.splice(iIndex, 1);
  }

  return this;
}
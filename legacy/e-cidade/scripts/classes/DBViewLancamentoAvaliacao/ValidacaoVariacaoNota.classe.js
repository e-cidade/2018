require_once("scripts/classes/DBViewLancamentoAvaliacao/DBViewLancamentoAvaliacao.classe.js");

/**
 * Funçao para validacao da variacao da nota.
 * a Nota deve estar dentro da variacao especificada dentro da forma de avaliacao
 * @param {Number} nNota Nota a ser Verificada
 * @param {Number} nVariacao variacao que a nota deve estar .
 * @return boolean
 */
DBViewAvaliacao.ValidacaoVariacaoNota = function (nNota, nVariacaoParametro, sMascaFormatacao) {
  
  var iTamanhoCasas  = 0;
  var aPartesMascara = sMascaFormatacao.split('.');
  var nVariacao      = new Number(nVariacaoParametro);
  if (aPartesMascara[1]) {
    nVariacao = new Number(nVariacaoParametro).toFixed(aPartesMascara[1].length);
  }
  
  if (new String(nVariacao).indexOf('.') >= 0) {
    iTamanhoCasas = new String(nVariacao).split(".")[1].length;
  } 
  if (nVariacao != 0) {  
  
    var nModulo = new Number(nNota) % new Number(nVariacao);
    if (new String(nModulo).indexOf('e-') > 0) {
      nModulo = nModulo.toFixed(0);
    }
    
    if (iTamanhoCasas == 0 && new String(nNota).indexOf('.') >= 0) {
       return false;
    }
    if ((nModulo)  == new Number(0).toFixed(iTamanhoCasas) || 
       new Number(nModulo).toFixed(iTamanhoCasas)  == new Number(nVariacao).toFixed(iTamanhoCasas)) {
          return true
    }
  } else if (nVariacao == 0) {
    return true;
  }  
  return false;
}
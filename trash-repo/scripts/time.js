/**
 * Conjunto de funções para tratamento de data e tempo
 */


/**
 * Compara dois períodos verificando se há conflito entre eles
 * @return boolean
 */
function js_comparaPeriodo(sDataInicial1, sDataFinal1, sDataInicial2, sDataFinal2) {

  if ((js_comparadata(sDataInicial1, sDataInicial2, ">=") && js_comparadata(sDataInicial1, sDataFinal2, "<")) ||
      (js_comparadata(sDataFinal1, sDataInicial2, ">") && js_comparadata(sDataFinal1, sDataFinal2, "<"))) {
    return true;
  } else {
    return false;
  }
} 
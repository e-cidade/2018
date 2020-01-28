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


function js_validaHora24Horas(oElement, oEvent) {

  var iHora    = oElement.value.substr(0, 2);
  var iMinutos = oElement.value.substr(3, 2);

  if (iHora > 23 || iMinutos > 59) {

    alert('Atenção, hora informada é inválida.\nPadrão aceito: 24 horas');
    oElement.value = "00:00";

    oEvent.preventDefault();
    oEvent.stopPropagation();
    setTimeout(function(){
      oElement.focus();
    }, 10);
    return false;
  }

  return true;
}

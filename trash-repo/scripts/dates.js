/**
 * Função que calcula data adicionando dias, meses e anos
 * @param dData
 * @param iDiaSomar
 * @param iMesSomar
 * @param iAnoSomar
 * @returns {Date}
 */
function somaDataDiaMesAno(dData, iDiaSomar, iMesSomar, iAnoSomar) {

  iTimestamp = dData.getTime();
  iTimestamp = parseInt(iTimestamp) + (parseInt(iAnoSomar) * 31557600000); //31536000
  iTimestamp = parseInt(iTimestamp) + (parseInt(iMesSomar) * 2629800000);  //2628000
  iTimestamp = parseInt(iTimestamp) + (parseInt(iDiaSomar) * 86400000);
  return new Date(iTimestamp);
}

/**
 * Função que transforma a data no formoato do banco de dados e nativo do js 
 * @param sDateIn
 * @returns {String}
 */
function getDateInDatabaseFormat(sDateIn) {

  if (sDateIn != undefined && sDateIn != "") {
    return sDateIn.split('/')[2] + "-" + sDateIn.split('/')[1] + "-" + sDateIn.split('/')[0];
  } else if (this.iYear > 0) {
    return this.iYear + "-" + this.iMonth + "-" + this.iDay;
  }
}

const DATA_PTBR = "d/m/Y";
const DATA_EN   = "Y-m-d";

Date.prototype.getFormatedDate = function( sFormato ){
  
  if ( sFormato === null ) {
    sFormato = DATA_EN;
  }

  var sDia = js_strLeftPad( this.getDate(), 2, "0");
  var sMes = js_strLeftPad( this.getMonth() + 1, 2, "0");
  var sAno = this.getFullYear();
  
  if ( sFormato == DATA_EN ) {
    return sAno + "-" + sMes + "-" + sDia;
  };
  return sDia + "/" + sMes + "/" + sAno; 
};


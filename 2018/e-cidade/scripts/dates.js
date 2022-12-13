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

/*
 * Verifica se sData se encontra entre sDataInicio e @sDataFim
 *
 * @param  {string} sData       Formato de entrada YYYY-MM-DD
 * @param  {string} sDataInicio Formato de entrada YYYY-MM-DD
 * @param  {string} sDataFim    Formato de entrada YYYY-MM-DD
 * @return {Boolean}            true se verdadeiro
 */
function js_validaIntervaloData(sData, sDataInicio, sDataFim) {

 var data1 = sDataInicio.substr(0,4)+''+sDataInicio.substr(5,2)+''+sDataInicio.substr(8,2);
 var data2 = sDataFim.substr(0,4)+''+sDataFim.substr(5,2)+''+sDataFim.substr(8,2);
 var sData = sData.substr(0,4)+''+sData.substr(5,2)+''+sData.substr(8,2);

 if ( parseInt(sData) >= parseInt(data1) && parseInt(sData) <= parseInt(data2) ) {
  return true;
 }
 return false;
}

/**
 * Retorna a quantidade de dias no mes
 *
 * @param  {Integer} iAno
 * @param  {Integer} iMes
 * @return {Integer}
 */
function diasNoMes(iAno, iMes) {

  var iDiasNoMes = 28;

  switch(iMes) {

    // Meses com 30 dias
    case 4:    // Abril
    case 6:    // Junho
    case 9:    // Setembro
    case 11:   // Novembro
      iDiasNoMes += 2;
    break;

    case 2:   // Fevereiro
      if(iAno%4 == 0) { //Ano bisexto então Fevereiro 29 dias
        iDiasNoMes += 1;
      }
    break;

    default:   // Meses com 31 dias
      iDiasNoMes += 3;
    break;
  }

  return iDiasNoMes;
}

const DATA_PTBR = "d/m/Y";
const DATA_EN   = "Y-m-d";
const COMPARACAO_MENOR = '<';
const COMPARACAO_MAIOR = '>';
const COMPARACAO_IGUAL = '=';
const DIFERENCA_ENTRE_DATAS_ANO = 'A';
const DIFERENCA_ENTRE_DATAS_MES = 'M';
const DIFERENCA_ENTRE_DATAS_DIA = 'D';

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

/**
 * Converte uma data String em um objeto Date
 *
 * ATENÇÃO: Se for comparar com o objeto DBInputDate.widget.js utilizar método Date.convertFromUTC.
 *
 * @param  {String} sData
 * @param  {String} sFormato
 * @return {Date}
 */
Date.convertFrom = function(sData, sFormato) {

  if (!sData) {
    throw new Error("Você precisa informar um data.")
  }

  var iDia, iMes, iAno, dRetorno = new Date();

  switch (sFormato) {
    case DATA_PTBR:
      var aData = sData.split('/');

      iDia = aData[0];
      iMes = aData[1];
      iAno = aData[2];
    break;

    case DATA_EN:
    default:
      var aData = sData.split('-');

      iDia = aData[2];
      iMes = aData[1];
      iAno = aData[0];

  }

  dRetorno.setYear(iAno);
  dRetorno.setMonth(iMes-1);
  dRetorno.setDate(iDia);

  return dRetorno;
}

/**
 * Converte uma data String em um objeto Date UTC
 *
 * @param  {String} sData
 * @param  {String} sFormato
 * @return {Date}
 */
Date.convertFromUTC = function(sData, sFormato) {

  if (!sData) {
    throw new Error("Você precisa informar um data.")
  }

  var iDia, iMes, iAno;

  switch (sFormato) {
    case DATA_PTBR:
      var aData = sData.split('/');

      iDia = aData[0];
      iMes = aData[1];
      iAno = aData[2];
    break;

    case DATA_EN:
    default:
      var aData = sData.split('-');

      iDia = aData[2];
      iMes = aData[1];
      iAno = aData[0];

  }

  return new Date(Date.UTC(iAno, iMes-1, iDia));
}

/**
 * Compara uma data se maior, menor ou igual
 *
 * @param  {Date}    oDataComparar
 * @param  {String}  sComparacao
 * @return {boolean}
 */
Date.prototype.compararData = function( oDataComparar, sComparacao ) {

  if(!sComparacao || typeof sComparacao == 'undefined' || sComparacao == '' || sComparacao == null) {
    sComparacao = COMPARACAO_IGUAL;
  }

  var iDataReferencia  = this.getFullYear().toString();
      iDataReferencia += js_strLeftPad((this.getMonth() + 1).toString(), 2, '0');
      iDataReferencia += js_strLeftPad(this.getDate().toString(), 2, '0');

  var iDataComparar   = oDataComparar.getFullYear().toString();
      iDataComparar  += js_strLeftPad((oDataComparar.getMonth() + 1).toString(), 2, '0');
      iDataComparar  += js_strLeftPad(oDataComparar.getDate().toString(), 2, '0');

  switch(sComparacao) {

    case COMPARACAO_MAIOR:
      if(iDataReferencia > iDataComparar)
        return true;
    break;

    case COMPARACAO_MENOR:
      if(iDataReferencia < iDataComparar)
        return true;
    break;

    default: // COMPARACAO_IGUAL
      if(iDataReferencia == iDataComparar)
        return true;
    break;
  }

  return false;
}

/**
 * Calcula a diferenca entre duas datas em dias meses ou anos
 *
 * @param  {Date}    oDataDiferenca
 * @param  {String}  sTipoDiferenca
 * @return {Integer}
 */
Date.prototype.diferenca = function( oDataDiferenca, sTipoDiferenca ) {

  var iDiaReferencia = this.getDate();
  var iMesReferencia = this.getMonth()+1;
  var iAnoReferencia = this.getFullYear();

  var iDiaDiferenca  = oDataDiferenca.getDate();
  var iMesDiferenca  = oDataDiferenca.getMonth() + 1;
  var iAnoDiferenca  = oDataDiferenca.getFullYear();

  /**
   * A ordem para diferença de datas está errada passe como parâmetro a data menor
   */
  if(iAnoReferencia < iAnoDiferenca) {
    return null;
  }

  switch(sTipoDiferenca) {

    case DIFERENCA_ENTRE_DATAS_ANO:
      return iAnoReferencia - iAnoDiferenca;
    break;

    case DIFERENCA_ENTRE_DATAS_MES:

      var iDiferencaMeses = 0;

      if(iAnoReferencia == iAnoDiferenca) {
        iDiferencaMeses = iMesReferencia - iMesDiferenca;
      }

      if(iAnoReferencia > iAnoDiferenca) {
        iDiferencaMeses = iMesReferencia + (12 - iMesDiferenca);
      }

      return iDiferencaMeses;
    break;

    default: //DIFERENCA_ENTRE_DATAS_DIA:

      var iDiferencaDias = 0;

      /**
       * Se o mesmo ano, calcula-se a diferena de dias neste ano
       */
      if(iAnoReferencia == iAnoDiferenca) {

        /**
         * No caso de meses iguais subtrai apenas os dias
         */
        if(iMesReferencia == iMesDiferenca) {

          iDiferencaDias = iDiaReferencia - iDiaDiferenca;

        } else { // No caso de meses diferentes deve-se percorrer os meses

          /**
           * Soma-se os dias faltantes para o final do mes no primeiro mes mais os dias decorridos no último mês
           */
          iDiferencaDias += diasNoMes(iAnoDiferenca, iMesDiferenca) - iDiaDiferenca;
          iDiferencaDias += iDiaReferencia;

          // console.log('Antes de percorrer os meses: '+ iDiferencaDias);

          /**
           * Percorre o intervalo entre o primeiro e último mês
           */
          for (var i = iMesDiferenca; i < iMesReferencia; i++) {

            if(i > iMesDiferenca) {
              iDiferencaDias += diasNoMes(iAnoDiferenca, i);
            }
            // console.log('Percorrendo meses: '+ iDiferencaDias);
          }
        }
      } else { // Se anos diferentes

        for (var i = iAnoDiferenca; i <= iAnoReferencia; i++) { // Percorre o intervalo de anos

          /**
           * A seguir utiliza-se recursão calculando dias em um mesmo ano
           *
           * No primeiro ano faz-se a diferenca entre o último dia do ano e data inicial
           */
          if(i == iAnoDiferenca) {

            iDiferencaDias += new Date(Date.UTC(i, 11, 31, 3)).diferenca(oDataDiferenca, DIFERENCA_ENTRE_DATAS_DIA);

          } else { // Nos próximos anos faz-se a diferenca entre o primeiro e o último dia do ano e diferenca entre data final e primeiro dia do ano

            /**
             * Faz-se a diferenca entre o primeiro e o último dia do ano
             */
            if(i < iAnoReferencia) {
              iDiferencaDias += new Date(Date.UTC(i, 11, 31, 3)).diferenca(new Date(Date.UTC(i, 0, 1, 3)), DIFERENCA_ENTRE_DATAS_DIA);
            }

            /**
             * Faz-se a diferenca entre data final e primeiro dia do ano
             */
            if(i == iAnoReferencia) {
              iDiferencaDias += this.diferenca(new Date(Date.UTC(i, 0, 1, 3)), DIFERENCA_ENTRE_DATAS_DIA);
            }
          }
        };
      }

      return iDiferencaDias;
    break;
  }
}

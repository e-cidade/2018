/**
 * Funções para formatacao e controle de strings
 * @package string.js
 */


/*
 * Função para formatar strings
 * @param string sString string a ser convertida
 * @param string sType   tipo de formatacao a ser realizada f = formato númerico d = formatado data.
 * @author Iuri Guntchnigg
 * @param integer iPrecisao precisao da formatacao monetaria;
 * @return string
 */

function js_formatar(sString, sType, iPrecisao){

  sType = new String(sType);
  sString = new String(sString);
  switch (sType.toLowerCase()) {

    /*
     * Formatar a string para formato monetario;
     */
    case 'f':

      if (iPrecisao == '' || typeof(iPrecisao) == 'undefined') {
        iPrecisao = 2;
      }
      var nValor         = new Number(sString);
      var nValorOriginal = nValor;

      if (isNaN(nValor)) {
        return sString;
      } else {

        iPow   = (Math.pow(10,iPrecisao));
        nValor = nValor.toFixed(iPrecisao);
        nValor = (nValor * iPow);
        nValor = nValor.toFixed(iPrecisao);
        sValor = nValor.toString();
        var iPontoDecimal = sValor.length - iPrecisao;

        if (iPontoDecimal > 0) {
          sValor = sValor.substring(0, iPontoDecimal) + "," + sValor.substring(iPontoDecimal, sValor.length);
        } else if (nValor == 0) {
          sValor = "0," + strRepeat("0", iPrecisao);
        } else if(nValor < 10 || (sString.substring(0,3) == "0.0" && iPrecisao > 2)) {
          sValor = "0,0" + sValor.substring(iPontoDecimal, sValor.length);
        } else {
          sValor = "0," + sValor.substring(iPontoDecimal, sValor.length);
        }

          /**
           * Fix evitar que o sinal de - esteja em lugar errado com numeros negativos
           */
        if (nValor < 0) {
            sValor = "-"+sValor.replace("-", "");
        }

        var sReg = /(-?\d+)(\d{3})/;
        aValores = sValor.split(',');

        while (sReg.test(aValores[0])) {
          aValores[0] = aValores[0].replace(sReg, "$1.$2");
        }

        /**
         * Quando valor é por exemplo: -0.60, o retorno formatado era -,60. Com este if ele retornará -0,60.
         */
        if (aValores[0] == "-") {
          aValores[0] = "-0";
        }

        return aValores[0]+","+aValores[1];
      }
      break;
    //formatacao de datas.
    case 'd':

      if (sString.length == 8) {
        //data no padrao YYYYMMDD
        var iDia = sString.substring(6, 8);
        var iMes = sString.substring(4, 6);
        var iAno = sString.substring(0, 4);
        return iDia + "/" + iMes + "/" + iAno;
      } else if (sString.length == 10) {

        if (sString.indexOf("-") > 0) {

          var sDateParts = sString.split("-");
          return sDateParts[2] + "/" + sDateParts[1] + "/" + sDateParts[0];

        } else if (sString.indexOf("/") > 0) {

          var sDateParts = sString.split("/");
          return sDateParts[2] + "-" + sDateParts[1] + "-" + sDateParts[0];

        } else {
          return sString;
        }
      } else {
        return sString;
      }
      break;

    // formata CPF e CNPJ
    case 'cpfcnpj':

      var sCpfCnpj = sString;

      var vrc = new String(sCpfCnpj);
      vrc = vrc.replace(".", "");
      vrc = vrc.replace(".", "");
      vrc = vrc.replace("/", "");
      vrc = vrc.replace("-", "");

      var tamString = vrc.length;
      var nCpfCnpj  = new Number(vrc);

      if (!isNaN(nCpfCnpj)) {
        if (tamString == 11 ){
          var vr = new String(sCpfCnpj);
          vr = vr.replace(".", "");
          vr = vr.replace(".", "");
          vr = vr.replace("-", "");

          var iTam = vr.length;

          if (iTam > 3 && iTam < 7)
            sCpfCnpj = vr.substr(0, 3) + '.' +
              vr.substr(3, iTam);
          if (iTam >= 7 && iTam <10)
            sCpfCnpj = vr.substr(0,3) + '.' +
              vr.substr(3,3) + '.' +
              vr.substr(6,iTam-6);
          if (iTam >= 10 && iTam < 12)
            sCpfCnpj = vr.substr(0,3) + '.' +
              vr.substr(3,3) + '.' +
              vr.substr(6,3) + '-' +
              vr.substr(9,iTam-9);

        } else if (tamString > 11){
          var vr = new String(sCpfCnpj);
          vr = vr.replace(".", "");
          vr = vr.replace(".", "");
          vr = vr.replace("/", "");
          vr = vr.replace("-", "");

          var iTam = vr.length;
          sCpfCnpj = vr.substr(0,2) + '.' +
            vr.substr(2,3) + '.' +
            vr.substr(5,3) + '/' +
            vr.substr(8,4)+ '-' +
            vr.substr(12,iTam-12);

        }
      }

      return sCpfCnpj;

      break;

  }
}

function js_stripTags(sString) {

  var sRegExp  =  /<[^>]*>/g;
  sString      = sString.replace(sRegExp,'');
  var sRegExp  = /&[^&]*;/g;
  return        sString.replace(sRegExp,'');
}
/*
 * Converte uma String para Float;
 * @param string sString string a ser convertida
 * @author Iuri Guntchnigg
 * @return float
 */
function  js_strToFloat(sString){

  sRegExp = /\./g;
  sString = sString.replace(/ /g,"");
  sString = sString.replace(sRegExp,"");
  sString = sString.replace(",",".");
  try {
    nValor  = new Number(sString);
    if (isNaN(nValor)){
      throw "NaN";
    }
  }
  catch (e){
    if (e == "NaN"){
      nValor = 0;
    }
  }
  finally{
    return nValor;
  }
}

/*
 * Metodo trim para objeto tipo string
 * @return string
 */
String.prototype.trim = function(){
  return this.replace(/^\s*/, "").replace(/\s*$/, "");
}

/*
 * metodo urldecode para objeto tipo string
 * @return string
 */

String.prototype.urlDecode = function() {

  str = this.replace(/\+/g," ");
  str = unescape(str);
  return str;
}

/*
 * metodo countOccurs para objeto tipo string
 * @return integer
 */

String.prototype.countOccurs = function(chr) {
  var iOccurs = 0;
  var iLength = this.length;
  var indx = 0;

  for(indx = 0; indx < iLength; indx++) {
    if(this[indx] == chr) {
      iOccurs++;
    }
  }
  return iOccurs;
}

String.prototype.extenso = function(c){
  var ex = [
    ["zero", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove", "dez", "onze", "doze", "treze",
      "quatorze", "quinze", "dezesseis", "dezessete", "dezoito", "dezenove"],
    ["dez", "vinte", "trinta", "quarenta", "cinqüenta", "sessenta", "setenta", "oitenta", "noventa"],
    ["cem", "cento", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos"],
    ["mil", "milhão", "bilhão", "trilhão", "quadrilhão", "quintilhão", "sextilhão", "setilhão", "octilhão", "nonilhão",
      "decilhão", "undecilhão", "dodecilhão", "tredecilhão", "quatrodecilhão", "quindecilhão", "sedecilhão",
      "septendecilhão", "octencilhão", "nonencilhão"]
  ];
  var a, n, v, i, n = this.replace(c ? /[^,\d]/g : /\D/g, "").split(","), e = " e ", $ = "real", d = "centavo", sl;
  for(var f = n.length - 1, l, j = -1, r = [], s = [], t = ""; ++j <= f; s = []){
    j && (n[j] = (("." + n[j]) * 1).toFixed(2).slice(2));
    if(!(a = (v = n[j]).slice((l = v.length) % 3).match(/\d{3}/g), v = l % 3 ? [v.slice(0, l % 3)] : [], v = a ? v.concat(a) : v).length) continue;
    for(a = -1, l = v.length; ++a < l; t = ""){
      if(!(i = v[a] * 1)) continue;
      i % 100 < 20 && (t += ex[0][i % 100]) ||
      i % 100 + 1 && (t += ex[1][(i % 100 / 10 >> 0) - 1] + (i % 10 ? e + ex[0][i % 10] : ""));
      s.push((i < 100 ? t : !(i % 100) ? ex[2][i == 100 ? 0 : i / 100 >> 0] : (ex[2][i / 100 >> 0] + e + t)) +
        ((t = l - a - 2) > -1 ? " " + (i > 1 && t > 0 ? ex[3][t].replace("ão", "ões") : ex[3][t]) : ""));
    }
    a = ((sl = s.length) > 1 ? (a = s.pop(), s.join(" ") + e + a) : s.join("") || ((!j && (n[j + 1] * 1 > 0) || r.length) ? "" : ex[0][0]));
    a && r.push(a + (c ? (" " + (v.join("") * 1 > 1 ? j ? d + "s" : (/0{6,}$/.test(n[0]) ? "de " : "") + $.replace("l", "is") : j ? d : $)) : ""));
  }
  return r.join(e);
}

String.prototype.ucFirst = function() {

  var sFirstString = this.substring(0,1);
  var sRestString  = this.substring(1);
  sFirstString = sFirstString.toUpperCase();
  return sFirstString+sRestString;

}

String.prototype.ucWords = function() {

  var aStrings = this.split(" ");
  var sString  = "";

  var sSeparator = "";
  for (var i = 0; i < aStrings.length; i++) {

    sString   += sSeparator+aStrings[i].ucFirst();
    sSeparator = " ";
  }
  return sString;
}

String.prototype.substrReplace = function(sReplace, inicio, fim) {


  var iTamanho = fim - inicio;
  var iInicioString = '';

  if (inicio > 0) {
    iInicioString = this.substr(0, inicio);
  }

  var sFinalString = this.substr(fim);
  var sString = iInicioString+sReplace+sFinalString;
  return sString;
}
/**
 * realiza algumas modificacações na string,
 * trocando caracteres especiais por tags definidas para essas string
 */
function tagString(sString) {

  if (sString!=null) {
    var sStringNova     = sString.replace(/\"/g, "<aspa>");
    sStringNova     = sStringNova.replace(/@/g, "<arroba>");
    sStringNova     = sStringNova.replace(/\n/g,"<quebralinha>");
    sStringNova     = sStringNova.replace(/\'/g,"<aspasimples>");
    sStringNova     = sStringNova.replace(/\"/g,"<aspa>");
    sStringNova     = sStringNova.replace(/\?/g,"<interrogacao>");
    sStringNova     = sStringNova.replace(/%/g,"<percentual>");
    sStringNova     = sStringNova.replace(/\(/g,"<abreparenteses>");
    sStringNova     = sStringNova.replace(/\)/g,"<fechaparenteses>");
    sStringNova     = sStringNova.replace(/\{/g,"<abrechaves>");
    sStringNova     = sStringNova.replace(/\}/g,"<fechachaves>");
    sStringNova     = sStringNova.replace(/\[/g,"<abrecolcheltes>");
    sStringNova     = sStringNova.replace(/\]/g,"<fechacolchetes>");
    sStringNova     = sStringNova.replace(/\+/g,"<mais>");
    sStringNova     = sStringNova.replace(/\#/g,"<sustenido>");
    sStringNova     = sStringNova.replace(/\&/g,"<ecomercial>");
    sStringNova     = sStringNova.replace(/\t/g,"<tab>");
    sStringNova     = sStringNova.replace(/\//g,"<barra>");
    /**
     * trocar os hifens automaticos do word; openoffice:
     */
    var sExpressao = String.fromCharCode(8211);
    var regEx      = new RegExp(sExpressao, 'g');
    sStringNova    = (sStringNova.replace(regEx, "<hifengrande>"));

    return sStringNova;
  } else {
    return sString;
  }
}

/**
 * Desfaz as modificacoes realizadas pela funcao tagString()
 */
function undoTagString(sString) {
  if (sString!=null) {
    var sStringNova     = sString.replace(/<aspa>/g, '"');
    sStringNova     = sStringNova.replace(/<arroba>/g, "@");
    sStringNova     = sStringNova.replace(/<quebralinha>/g,"\n");
    sStringNova     = sStringNova.replace(/<aspasimples>/g,"'");
    sStringNova     = sStringNova.replace(/<interrogacao>/g,"?");
    sStringNova     = sStringNova.replace(/<percentual>/g,"%");
    sStringNova     = sStringNova.replace(/<abreparenteses>/g,"(");
    sStringNova     = sStringNova.replace(/<fechaparenteses>/g,")");
    sStringNova     = sStringNova.replace(/<abrechaves>/g,"{");
    sStringNova     = sStringNova.replace(/<fechachaves>/g,"}");
    sStringNova     = sStringNova.replace(/<abrecolcheltes>/g,"[");
    sStringNova     = sStringNova.replace(/<fechacolchetes>/g,"]");
    sStringNova     = sStringNova.replace(/<mais>/g,"+");
    sStringNova     = sStringNova.replace(/<sustenido>/g,"#");
    sStringNova     = sStringNova.replace(/<ecomercial>/g,"&");
    sStringNova     = sStringNova.replace(/<tab>/g,"\t");
    sStringNova     = sStringNova.replace(/<barra>/g,"/");
    sStringNova     = sStringNova.replace(/<hifengrande>/g,"-");

    return sStringNova;
  } else {
    return sString;
  }

}
/**
 * Transforma o parametro SEARCH do window.location ou um querystring em objeto
 *
 * Ex.: var testeUrl = "index.php?chave=1234&busca=todos";
 *      var oUrl     = js_urlToObject(testeUrl);
 *
 *  Irá retornar {chave->'1234', busca->'todos'}
 *
 *  Ex2: var oUrl     = js_urlToObject();
 *   Irá retornar a query string da pagina atual como um objeto
 *
 * @return Object
 */

function js_urlToObject(sUrl) {

  if (sUrl == "" || sUrl == null) {
    sUrl = window.location.search;
  }

  if (sUrl == ""|| sUrl == null) {
    return new Object;
  }
  sUrl = sUrl.urlDecode();
  var aURlFields    = sUrl.split('&');
  var oUrlObject    = new Object;
  aURlFields.each(function (aPart, index) {

    var aKeys = aPart.split("=");
    if (aKeys[0].substring(0,1) == '?') {
      aKeys[0] = aKeys[0].substrReplace('', 0, 1);
    }
    oUrlObject[aKeys[0]] = aKeys[1];
  });

  return oUrlObject;
}

function js_strLeftPad(sString, iTotalCaracteres, sCaracteres) {

  sString     = new String(sString);
  sCaracteres = (sCaracteres) ? sCaracteres : " ";

  if (sString.length < iTotalCaracteres) {

    while (sString.length < iTotalCaracteres) {
      sString = sCaracteres + sString;
    }
  }

  if (sString.length > iTotalCaracteres) {
    sString = sString.substring((sString.length - iTotalCaracteres), iTotalCaracteres);
  }

  return sString;
}

function js_strPadRight(sString, iTotalCaracteres, sCaracteres) {

  sString     = new String(sString);
  sCaracteres = (sCaracteres) ? sCaracteres : " ";

  if (sString.length < iTotalCaracteres) {

    while (sString.length < iTotalCaracteres) {
      sString = sString+sCaracteres;
    }
  }

  if (sString.length > iTotalCaracteres) {
    sString = sString.substring((sString.length - iTotalCaracteres), iTotalCaracteres);
  }

  return sString;
}
String.prototype.reverse = function () {

  var sValue  = this.valueOf();
  var sString = new String();
  for  (var iChar = sValue.length; iChar > -1; iChar--) {
    sString += sValue.substr(iChar, 1);
  }
  return sString;
}

/**
 * Função para codificar uma URL
 * @return Retorna String Codificada
 **/
String.prototype.urlEncode = function() {

  return encodeURIComponent(this).replace(/!/g  , '%21').
  replace(/'/g  , '%27').
  replace(/\(/g , '%28').
  replace(/\)/g , '%29').
  replace(/\*/g , '%2A').
  replace(/%20/g, '+');

};



function sprintf () {
  // http://kevin.vanzonneveld.net
  // +   original by: Ash Searle (http://hexmen.com/blog/)
  // + namespaced by: Michael White (http://getsprink.com)
  // +    tweaked by: Jack
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +      input by: Paulo Freitas
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +      input by: Brett Zamir (http://brett-zamir.me)
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   improved by: Dj
  // +   improved by: Allidylls
  // *     example 1: sprintf("%01.2f", 123.1);
  // *     returns 1: 123.10
  // *     example 2: sprintf("[%10s]", 'monkey');
  // *     returns 2: '[    monkey]'
  // *     example 3: sprintf("[%'#10s]", 'monkey');
  // *     returns 3: '[####monkey]'
  // *     example 4: sprintf("%d", 123456789012345);
  // *     returns 4: '123456789012345'
  var regex  = /%%|%(\d+\$)?([-+\'#0 ]*)(\*\d+\$|\*|\d+)?(\.(\*\d+\$|\*|\d+))?([scboxXuideEfFgG])/g;
  var a      = arguments;
  var i      = 0;
  var format = a[i++];

  if (a[1] instanceof Array) {

    a = new Array();
    a.push(arguments[0]);
    for (var i = 0; i  < arguments[1].length; i++) {
      a.push(arguments[1][i]);
    }
    var i = 1;
    format = a[0];
  }
  // pad()
  var pad = function (str, len, chr, leftJustify) {
    if (!chr) {
      chr = ' ';
    }
    var padding = (str.length >= len) ? '' : Array(1 + len - str.length >>> 0).join(chr);
    return leftJustify ? str + padding : padding + str;
  };

  // justify()
  var justify = function (value, prefix, leftJustify, minWidth, zeroPad, customPadChar) {
    var diff = minWidth - value.length;
    if (diff > 0) {
      if (leftJustify || !zeroPad) {
        value = pad(value, minWidth, customPadChar, leftJustify);
      } else {
        value = value.slice(0, prefix.length) + pad('', diff, '0', true) + value.slice(prefix.length);
      }
    }
    return value;
  };

  // formatBaseX()
  var formatBaseX = function (value, base, prefix, leftJustify, minWidth, precision, zeroPad) {
    // Note: casts negative numbers to positive ones
    var number = value >>> 0;
    prefix = prefix && number && {
        '2': '0b',
        '8': '0',
        '16': '0x'
      }[base] || '';
    value = prefix + pad(number.toString(base), precision || 0, '0', false);
    return justify(value, prefix, leftJustify, minWidth, zeroPad);
  };

  // formatString()
  var formatString = function (value, leftJustify, minWidth, precision, zeroPad, customPadChar) {
    if (precision != null) {
      value = value.slice(0, precision);
    }
    return justify(value, '', leftJustify, minWidth, zeroPad, customPadChar);
  };

  // doFormat()
  var doFormat = function (substring, valueIndex, flags, minWidth, _, precision, type) {
    var number;
    var prefix;
    var method;
    var textTransform;
    var value;

    if (substring === '%%') {
      return '%';
    }

    // parse flags
    var leftJustify = false,
      positivePrefix = '',
      zeroPad = false,
      prefixBaseX = false,
      customPadChar = ' ';
    var flagsl = flags.length;
    for (var j = 0; flags && j < flagsl; j++) {
      switch (flags.charAt(j)) {
        case ' ':
          positivePrefix = ' ';
          break;
        case '+':
          positivePrefix = '+';
          break;
        case '-':
          leftJustify = true;
          break;
        case "'":
          customPadChar = flags.charAt(j + 1);
          break;
        case '0':
          zeroPad = true;
          break;
        case '#':
          prefixBaseX = true;
          break;
      }
    }

    // parameters may be null, undefined, empty-string or real valued
    // we want to ignore null, undefined and empty-string values
    if (!minWidth) {
      minWidth = 0;
    } else if (minWidth === '*') {
      minWidth = +a[i++];
    } else if (minWidth.charAt(0) == '*') {
      minWidth = +a[minWidth.slice(1, -1)];
    } else {
      minWidth = +minWidth;
    }

    // Note: undocumented perl feature:
    if (minWidth < 0) {
      minWidth = -minWidth;
      leftJustify = true;
    }

    if (!isFinite(minWidth)) {
      throw new Error('sprintf: (minimum-)width must be finite');
    }

    if (!precision) {
      precision = 'fFeE'.indexOf(type) > -1 ? 6 : (type === 'd') ? 0 : undefined;
    } else if (precision === '*') {
      precision = +a[i++];
    } else if (precision.charAt(0) == '*') {
      precision = +a[precision.slice(1, -1)];
    } else {
      precision = +precision;
    }

    // grab value using valueIndex if required?
    value = valueIndex ? a[valueIndex.slice(0, -1)] : a[i++];

    switch (type) {
      case 's':
        return formatString(String(value), leftJustify, minWidth, precision, zeroPad, customPadChar);
      case 'c':
        return formatString(String.fromCharCode(+value), leftJustify, minWidth, precision, zeroPad);
      case 'b':
        return formatBaseX(value, 2, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
      case 'o':
        return formatBaseX(value, 8, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
      case 'x':
        return formatBaseX(value, 16, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
      case 'X':
        return formatBaseX(value, 16, prefixBaseX, leftJustify, minWidth, precision, zeroPad).toUpperCase();
      case 'u':
        return formatBaseX(value, 10, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
      case 'i':
      case 'd':
        number = +value || 0;
        number = Math.round(number - number % 1); // Plain Math.round doesn't just truncate
        prefix = number < 0 ? '-' : positivePrefix;
        value = prefix + pad(String(Math.abs(number)), precision, '0', false);
        return justify(value, prefix, leftJustify, minWidth, zeroPad);
      case 'e':
      case 'E':
      case 'f': // Should handle locales (as per setlocale)
      case 'F':
      case 'g':
      case 'G':
        number = +value;
        prefix = number < 0 ? '-' : positivePrefix;
        method = ['toExponential', 'toFixed', 'toPrecision']['efg'.indexOf(type.toLowerCase())];
        textTransform = ['toString', 'toUpperCase']['eEfFgG'.indexOf(type) % 2];
        value = prefix + Math.abs(number)[method](precision);
        return justify(value, prefix, leftJustify, minWidth, zeroPad)[textTransform]();
      default:
        return substring;
    }
  };

  return format.replace(regex, doFormat);
}

/**
 * Remove aspas simples e composta de um input html
 * @exemple exemplo de uso
 *          js_removerAspas($('ed39_c_conceito'));
 */
function js_removerAspas(field) {

  field.observe("blur", function() {

    var sExpressaoRegular = /[\'\"]/g;
    field.value = field.value.replace(sExpressaoRegular, '');
  });

  field.observe('keydown', function(event) {

    var iTecla = event.which;
    if (iTecla == 222) {

      event.preventDefault();
      event.stopPropagation();
      return false;
    }

    return true;
  });
}

String.prototype.removeAcento = function() {

  return this.replace(/[áàâã]/g,'a').replace(/[éèê]/g,'e').replace(/[íî]/g,'i').replace(/[óòôõ]/g,'o').replace(/[úùû]/g,'u').
  replace(/[ÁÀÂÃ]/g,'A').replace(/[ÉÈÊ]/g,'E').replace(/[ÍÎ]/g,'I').replace(/[ÓÒÔÕ]/g,'O').replace(/[ÚÙÛ]/g,'U');
};

/**
 * Repete a string passada iLength vezes
 * @param {string} sString Sring a ser repetida
 * @param {integer} iLength vezes que a string deve ser repetida
 * @returns {string}
 */
strRepeat = function(sString, iLength) {

  var sStringReturn = '';
  for (var iTotal = 0; iTotal < iLength; iTotal++) {
    sStringReturn += sString;
  }
  return sStringReturn;
}

/**
 * Retorna a substituição de qualquer valor que não seja número, por vazio
 * @returns {string}
 */
String.prototype.somenteNumeros = function() {
  return this.replace( /[^0-9]/, '' );
}

/**
 * Função pad
 * @param  {string} padString
 * @param  {integer} length
 * @return {string}
 */
String.prototype.lpad = function(padString, length) {

  var str = this;

  while (str.length < length)
    str = padString + str;

  return str;
}

/**
 * Retorna o valor numérico independente da formatação
 * @return {Number}|{NaN}
 */
String.prototype.getNumber = function() {

  if (this.match(/^\d*\.?\d*$/)) {
    return new Number(this);
  }

  if (this.match(/^\d*\,?\d*$/)) {
    return new Number(this.replace(',', '.'));
  }

  if (this.match(/^(\d*\.)*\d+\,\d*$/)) {
    return new Number(this.replace(/\./g, '').replace(',', '.'));
  }

  if (this.match(/^(\d*\,)*\d+\.\d*$/)) {
    return new Number(this.replace(/\,/g, ''));
  }

  if (this.match(/^\d*$/)) {
    return new Number(this);
  }

  return NaN;
}

/**
 * Retorna o numero de casas decimais
 * @return {integer}
 */
String.prototype.getDecimalsLength = function() {

  if (this.match(/\,\d+$/)) {
    return this.match(/\,\d+$/)[0].length - 1;
  }

  if (this.match(/\.\d+$/)) {
    return this.match(/\.\d+$/)[0].length - 1;
  }

  return 0;
}

/**
 * Retorna uma data a partir de uma string
 * @return {Date}|{null}
 */
String.prototype.getDate = function() {

  if (this.match(/^\d{4}\-\d{1,2}\-\d{1,2}$/)) {
    var aDate = this.split('-');
  } else if (this.match(/^\d{1,2}\/\d{1,2}\/\d{4}$/)) {
    var aDate = this.split('/').reverse();
  } else {
    return null;
  }

  return new Date(aDate[0], +aDate[1]-1, aDate[2]);
}

/**
 * Formata uma data para o padrão brasileiro
 * @return {String} "dd/mm/YYYY"
 */
Date.prototype.getDateBR = function() {

  return [(new String(this.getDate())).lpad('0', 2),
    (new String(this.getMonth()+1)).lpad('0', 2),
    this.getFullYear()].join("/");
}

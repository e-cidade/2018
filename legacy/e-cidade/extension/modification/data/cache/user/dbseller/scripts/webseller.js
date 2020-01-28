
;(function(global) {

  // script ja carregado
  if (global.CurrentWindow) {
    return false;
  }

  var

    // confirm() builtin do navegador
    _confirm = global.confirm,

    // tanta buscar a variavel CurrentWindow do parent
    CurrentWindow = global.CurrentWindow || parent.CurrentWindow || window.frameElement.CurrentWindow,

    /**
     * @param {String} message
     * @param {Function} done
     * @return {Boolean}
     */
    confirm = function(message, done) {

      if (!done) {
        return _confirm.call(global, message);
      }

      return CurrentWindow.confirm.call(global, message, done);
    }
  ;

  global.CurrentWindow = CurrentWindow;
  global.Desktop = CurrentWindow.Desktop;
  global.alert = CurrentWindow.alert;
  global.confirm = confirm;

  document.addEventListener('mousedown', CurrentWindow._eventHandler);

})(this);
/*
 * @access public 
 * @author Adriano Quilião de Oliveira <adriano.oliveira@dbseller.com.br>
 * @copyright 
 * @example db_frmsau_lote001.php
 * @param  string $sDateIn
 * @param  int    $iDayIn
 * @param  string $iMonthIn
 * @param  string $iYearIn
 * @return object $wsDate 
 */
function wsDate(sDateIn, iDayIn, iMonthIn, iYearIn) {

  /* Variables */
  this.sDate  = undefined;
  this.iDay   = new Number(0);
  this.iMonth = new Number(0);
  this.iYear  = new Number(0);
  
  /* Functions */
  this.setDate                 = setDate;
  this.getDate                 = getDate;
  this.getDay                  = getDay;
  this.getMonth                = getMonth;
  this.getNameMonth            = getNameMonth;
  this.getNameMonthEnglish     = getNameMonthEnglish;
  this.getYear                 = getYear;
  this.toString                = toString;
  this.isValidDate             = isValidDate;
  this.getDateInAmericanFormat = getDateInAmericanFormat;
  this.getDateInDatabaseFormat = getDateInDatabaseFormat; 
  this.compareTo               = compareTo;
  this.thisHigher              = thisHigher;
  this.otherHigher             = otherHigher;
  this.formatDate              = formatDate;
  this.thisInInterval          = thisInInterval;
  this.sum                     = sum;
  this.getAge                  = getAge;
  this.countDaysInInterval     = countDaysInInterval

  /* constructor this class */
  if (typeof(sDateIn) != undefined && sDateIn != null && sDateIn != ""
      || typeof(iYearIn) != undefined && iYearIn != null && iYearIn != "") { 
	
    if (typeof(iDayIn) != undefined && typeof(iMonthIn) != undefined && typeof(iYearIn) != undefined
        && isValidDate(iDayIn, iMonthIn, iYearIn)) {
      
      sDateIn = iDayIn + "/" + iMonthIn + "/" + iYearIn;
     
    }
    sDateIn = this.formatDate(sDateIn);
    if (typeof(sDateIn) != undefined 
        && this.isValidDate(sDateIn.split('/')[0], sDateIn.split('/')[1] , sDateIn.split('/')[2])) {
         
      this.iDay   = sDateIn.split('/')[0];
      this.iMonth = sDateIn.split('/')[1];
      this.iYear  = sDateIn.split('/')[2];
      this.sDate  = sDateIn;
      
    }

  }

  /* Functions Implemented */
  /*  
   * @access public
   * @param void
   * @return string $sDate representa uma da no formato dd/mm/aaaa  
   * 
   */
  function getDate() {
    return this.sDate;  
  }

  /*  
   * @access public
   * @param string $sDateIn 
   * @return int $iDay dia de uma determinada data  
   * 
   */
  function getDay(sDateIn) {
  
    if (typeof(sDateIn) != undefined) {
  
      sDateIn = this.formatDate(sDateIn);
      if (sDateIn != undefined && sDateIn.split('/').length == 3
          && this.isValidDate(sDateIn.split('/')[0], sDateIn.split('/')[1], sDateIn.split('/')[2])) {
        return sDateIn.split('/')[0];
      }
    
    }
    return this.iDay;
  
  }
  
  /*  
   * @access public
   * @param string $sDateIn 
   * @return int $iMonth mês de uma determinada data  
   * 
   */
  function getMonth(sDateIn) {
    
    if (sDateIn != undefined) {
            
      sDateIn = this.formatDate(sDateIn);
      if (sDateIn != undefined && sDateIn.split('/').length == 3
          && this.isValidDate(sDateIn.split('/')[0], sDateIn.split('/')[1], sDateIn.split('/')[2])) {
        return sDateIn.split('/')[1];
      }
            
    }
    return this.iMonth; 
    
  }
  
  /*  
   * @access public
   * @param string $sDateIn 
   * @return int $iYear ano de uma determinada data  
   * 
   */
  function getYear(sDateIn) {
   
    if (typeof(sDateIn) != undefined) {
          
      sDateIn = this.formatDate(sDateIn);
      if (sDateIn != undefined && sDateIn.split('/').length == 3 
          && this.isValidDate(sDateIn.split('/')[0], sDateIn.split('/')[1], sDateIn.split('/')[2])) {
        return sDateIn.split('/')[2];
      }
              
    }
    return this.iYear; 
  
  }
  
  /*  
   * @access public
   * @param void 
   * @return string $sDate data descrita por extenso  
   * 
   */
  function toString() {

    if (this.sDate != undefined) {
      return "Dia " + this.iDay + " de " + getNameMonth(this.iMonth)  + " de " + this.iYear;
    }
    return 'undefined';
  
  } 
  
  /*  
   * @access public
   * @param int $iIndex valor entre 1 e 12 que representa o mês
   * @param void
   * @return string $aMonth[iIndex] nome do mês correspondente ao valor informado
   * @return array $aMonth representando todos os meses   
   * 
   */
  function getNameMonth(iIndex) {
  
    var aMonth = new Array(12);
    aMonth[0]  = "Janeiro";
    aMonth[1]  = "Fevereiro";
    aMonth[2]  = "Março";
    aMonth[3]  = "Abril";
    aMonth[4]  = "Maio";
    aMonth[5]  = "Junho";
    aMonth[6]  = "Julho";
    aMonth[7]  = "Agosto";
    aMonth[8]  = "Setembro";
    aMonth[9]  = "Outubro";
    aMonth[10] = "Novembro";
    aMonth[11] = "Dezembro";
    if (typeof(iIndex) != undefined && isNumber(iIndex) && parseInt(iIndex, 10) < 13 && parseInt(iIndex, 10) > 0) {
      return aMonth[parseInt(iIndex, 10) - 1];
    } else {
      return aMonth;
    }
      
  }
  
  /*  
   * @access public
   * @param int $iIndex valor entre 1 e 12 que representa o mês
   * @param void
   * @return string $aMonth[iIndex] nome do mês correspondente ao valor informado em inglês
   * @return array $aMonth representando todos os meses em inglês   
   */
  function getNameMonthEnglish(iIndex) {
  
    var aMonth = new Array(12);
    aMonth[0]  = "January";
    aMonth[1]  = "February";
    aMonth[2]  = "March";
    aMonth[3]  = "April";
    aMonth[4]  = "May";
    aMonth[5]  = "June";
    aMonth[6]  = "July";
    aMonth[7]  = "August";
    aMonth[8]  = "September";
    aMonth[9]  = "October";
    aMonth[10] = "November";
    aMonth[11] = "December";
    if (typeof(iIndex) != undefined && isNumber(iIndex) && parseInt(iIndex, 10) < 13 && parseInt(iIndex, 10) > 0) {
      return aMonth[parseInt(iIndex, 10) - 1];
    } else {
      return aMonth;
    }
        
  }
  
  /*  
   * @access public
   * @param int $iDayIn 
   * @param int $iMonthIn
   * @param int $iYearIn 
   * @return boolean $isValid true caso valida e false caso inválida  
   */
  function isValidDate(iDayIn, iMonthIn, iYearIn) {
    
    if (!isNumber(iDayIn) || !isNumber(iMonthIn) || !isNumber(iYearIn)) {
      return false;
    }
    if (iMonthIn > 12 || iMonthIn < 1) {
      return false;  
    }
    if (iYearIn < 1800) {
      return false;
    }
    if (iDayIn < 1) {
      return false;  
    }
    if ((iDayIn > 31) && (iMonthIn == 1 || iMonthIn == 3 || iMonthIn == 5 || iMonthIn == 7 || iMonthIn == 8 
        || iMonthIn == 10 || iMonthIn == 12)) {
    	return false;
    } else if ((iDayIn > 30) && (iMonthIn == 4 || iMonthIn == 6 || iMonthIn == 9 || iMonthIn == 11)) {
    	return false;
    } else if (iMonthIn == 2
               && (((iDayIn > 29 && (parseInt(iYearIn / 4, 10) == iYearIn / 4)))
               || ((iDayIn >= 29 && (parseInt(iYearIn / 4, 10) != iYearIn / 4))))) {    
    	return false;
    }
    return true;

  }
  
  /*  
   * @access public
   * @param string $iDateIn
   * @return void  
   */
  function setDate(sDateIn) { 

    sDateIn = this.formatDate(sDateIn);
    if (sDateIn.split('/').length == 3 && sDateIn != undefined
        && this.isValidDate(sDateIn.split('/')[0], sDateIn.split('/')[1], sDateIn.split('/')[2])) {

      this.iDay   = sDateIn.split('/')[0];
      this.iMonth = sDateIn.split('/')[1];
      this.iYear  = sDateIn.split('/')[2];
      this.sDate  = sDateIn;

    } else {
      
      this.iDay   = 0;
      this.iMonth = 0;
      this.iYear  = 0;
      this.sDate  = undefined;
    
    }
                
  }
  
  /*  
   * @access public
   * @param string $iDateIn
   * @return $sDate data no formato dd/mm/aaaa  
   */
  function formatDate(sDateIn) {
  
    if (sDateIn != null && typeof(sDateIn) != undefined && sDateIn != "") {
   
      if (sDateIn.split('/').length == 3) {
        return sDateIn;
      } else if (sDateIn.split('-').length == 3) {

        var sNewdate = "";
        sNewdate  = sDateIn.split('-')[2] + "/";
        sNewdate += sDateIn.split('-')[1] + "/";
        sNewdate += sDateIn.split('-')[0];
        return sNewdate;
      
      } else {
        return undefined;  
      }
      
    }
  
  }
  
  /*  
   * @access private
   * @param string $iValue
   * @return boolean $isNumeric true se for numérico e false caso contrário   
   */
  function isNumber(iValue) {
    
    var nonNumbers = /\D/;
    if (nonNumbers.test(iValue)) {
      return false;
    } else {
      return true;
    }
      
  }
  
  /*  
   * @access public
   * @param string $iDateIn
   * @return string $sDate data no formato aaaa-mm-dd 
   */
  function getDateInDatabaseFormat(sDateIn) {

    sDateIn = this.formatDate(sDateIn);
    if (sDateIn != undefined && sDateIn != ""
        && this.isValidDate(sDateIn.split('/')[0], sDateIn.split('/')[1], sDateIn.split('/')[2])) {
      return sDateIn.split('/')[2] + "-" + sDateIn.split('/')[1] + "-" + sDateIn.split('/')[0];
    } else if (this.iYear > 0) {
      return this.iYear + "-" + this.iMonth + "-" + this.iDay;
    }
    
  }
  
  /*  
   * @access public
   * @param string $iDateIn
   * @return string $sDate data formatada como o seguinte exemplo June 10 2011  
   */
  function getDateInAmericanFormat(sDateIn) {
    
    sDateIn = this.formatDate(sDateIn);
    if (sDateIn != undefined && sDateIn != ""
        && this.isValidDate(sDateIn.split('/')[0], sDateIn.split('/')[1], sDateIn.split('/')[2])) {

      return this.getNameMonthEnglish(sDateIn.split('/')[1]) + ' ' + sDateIn.split('/')[0] + ' ' +
             sDateIn.split('/')[2];

    } else if (this.iYear > 0) {
      return this.getNameMonthEnglish(this.iMonth) + ' ' + this.iDay + ' ' + this.iYear;
    }
    return undefined;
  
  }

  /*  
   * @access public
   * @param string $iDateIn
   * @return boolean $lIsEqual true se iguais as datas ou false caso contrario  
   */
  function compareTo(sDateIn) {
    
    sDateIn = this.formatDate(sDateIn);
    if (typeof(sDateIn) != undefined
        && this.isValidDate(sDateIn.split('/')[0], sDateIn.split('/')[1], sDateIn.split('/')[2])) {
     
      return (this.iYear == sDateIn.split('/')[2] && this.iMonth == sDateIn.split('/')[1] 
              && this.iDay == sDateIn.split('/')[0])? true : false;     
        
    } 
    return false; 

  }
  
  /*  
   * @access public
   * @param string $iDateIn
   * @return boolean $lIsHigher Se a data do objeto for maior que a informada true e em caso contrário false 
   */
  function thisHigher(sDateIn) {
	
    sDateIn = this.formatDate(sDateIn);
    if (typeof(sDateIn) != undefined
        && this.isValidDate(sDateIn.split('/')[0], sDateIn.split('/')[1], sDateIn.split('/')[2])) {
            
      var dThis  = new Date(this.iYear, (this.iMonth -1), this.iDay);
      var dOther = new Date(sDateIn.split('/')[2], (sDateIn.split('/')[1] - 1), sDateIn.split('/')[0]);
      return dOther < dThis ? true : false;     
                
    }
    return false;   
  
  }
  
  /*  
   * @access public
   * @param string $iDateIn
   * @return boolean $lIsSmaller se a data do objeto for menor que a informada true caso contrario false 
   */
  function otherHigher(sDateIn) {
     
    sDateIn = this.formatDate(sDateIn);
	if (typeof(sDateIn) != undefined
	    && this.isValidDate(sDateIn.split('/')[0], sDateIn.split('/')[1], sDateIn.split('/')[2])) {
	            
	  var dThis  = new Date(this.iYear, (this.iMonth - 1), this.iDay);
	  var dOther = new Date(sDateIn.split('/')[2], (sDateIn.split('/')[1] - 1), sDateIn.split('/')[0]);
	  return dOther > dThis ? true : false;     
	                
	}
	return false;  
    
  }
  
  /*  
   * @access public
   * @param string $iDateIn
   * @return boolean $lIsInThisInterval true se a data informada estiver no intervalo da do objeto caso contrario false
   */
  function thisInInterval(sDateIn) {
    return this.thisHigher(sDateIn);
  }
  
  /*  
   * @access public
   * @param int $iDaysIns 
   * @param int $iMonthsIns
   * @param int $iiYearIns
   * @return void 
   */
  function sum(iDayIns, iMonthIns, iYearIns) {
    
    var dThis = new Date(this.iYear, (this.iMonth-1), this.iDay);
    if (isNumber(iDayIns)) {
      dThis.setDate(dThis.getDate() + iDayIns);
    }
    if (isNumber(iMonthIns)) {
      dThis.setMonth(dThis.getMonth() + iMonthIns);  
    }
    if (isNumber(iYearIns)) {
      dThis.setFullYear(dThis.getFullYear() + iYearIns);  
    }
    this.iDay   = dThis.getDate();
    this.iMonth = (dThis.getMonth() + 1); 
    this.iYear  = dThis.getFullYear();
    this.sDate  = dThis.getDate() + "/" + (dThis.getMonth() + 1) + "/" + dThis.getFullYear();
      
  }
  
  /*  
   * @access public
   * @param string $iDateIn
   * @return int $iDaysInInterval retorna a quantidade de dias do intervalo entre a data informada e a do objeto  
   */
  function countDaysInInterval(sDateIn) {
    
    sDateIn = this.formatDate(sDateIn);
    if (typeof(sDateIn) != undefined
        && this.isValidDate(sDateIn.split('/')[0], sDateIn.split('/')[1], sDateIn.split('/')[2])) {
          
      sDateIni  = this.getNameMonthEnglish(this.iMonth) + ' ' + this.iDay + ' ' + this.iYear;
      sDateEnd  = this.getNameMonthEnglish(sDateIn.split('/')[1]) + ' ';
      sDateEnd += sDateIn.split('/')[0] + ' ' + sDateIn.split('/')[2];
      iInterval = ((Date.parse(sDateEnd) - Date.parse(sDateIni)) / (24 * 60 * 60 * 1000)).toFixed(0);
      return iInterval < 0 ? (iInterval * -1) : iInterval;
      
    }
    return false;
     
  }
  
  /*  
   * @access public
   * @param void
   * @return string $iAgeThis retorna a idade que é o valor entre a data atual e a do objeto  
   */
  function getAge() {
    
    var sDateNow  = new Date().getDate() + "/" + (new Date().getMonth() + 1) + "/" + new Date().getFullYear();
    var iDaysCnt  = this.countDaysInInterval(sDateNow);
    var iYearIni  = new Date().getFullYear();
    var iMonthIni = new Date().getMonth();
    var iYearIns   = 0;
    var iMonthIns  = 0;
    if (this.thisHigher(sDateNow)) {
      return "Data maior que a data do sistema";
    }
    if (iDaysCnt != false && iDaysCnt > 0) {
        
      while (true) {
      
        if ((parseInt(iYearIni, 10) / 4) == (iYearIni / 4) && (iDaysCnt - 366) >= 0) {
            
          iDaysCnt -= 366;
          iYearIns++;
          iYearIni--;
          
        } else if ((iDaysCnt - 365) >= 0) {
          
          iDaysCnt -= 365;
          iYearIns++;
          iYearIni--;
          
        } else if ((iMonthIni == 1 || iMonthIni == 3 || iMonthIni == 5 || iMonthIni == 7 || iMonthIni == 8
                   || iMonthIni == 10 || iMonthIni == 12) && iDaysCnt >= 31) {
          
          iDaysCnt -= 31;
          iMonthIns++;
          if (iMonthIni != 1) {
            iMonthIni--;
          } else {
            iMonthIni = 12;  
          }
            
        } else if ((iMonthIni == 4 || iMonthIni == 6 || iMonthIni == 9 || iMonthIni == 11) && iDaysCnt >= 30) {
          
          iDaysCnt -= 30;
          iMonthIns++;
          iMonthIni--;
          
        } else if ((iMonthIni == 2 && parseInt(iYearIni / 4, 10) == iYearIni / 4) && iDaysCnt >= 29) {
          
          iDaysCnt -= 29;
          iMonthIns++;
          iMonthIni--;
          
        } else if (iMonthIni == 2 && parseInt(iYearIni / 4, 10) != iYearIni / 4  && iDaysCnt >= 28) {
        
          iDaysCnt -= 28;
          iMonthIns++;
          iMonthIni--;
          
        } else {
          return iYearIns + " ano(s), " + iMonthIns + " mese(s) e " + iDaysCnt + " dia(s)";
        }
    
      }
    
    } 
    return undefined;
      
  }
 
}
/*
 * =========================================================== 
 *                  END CLASS WSDATE
 * ===========================================================
 */




/**
 * Função que executa a requisição AJAX 
 * @param oParam    Objeto com os parametros
 * @param jsRetorno String com o nome da função retorno
 * @param sUrl      String com o arquivo RPC
 * @param lAsync    Define se a requisição vai ser sincronizada ou não
 * @return          [opcional]
 */
function js_webajax(oParam, jsRetorno, sUrl, lAsync, sMessage) {

  var mRetornoAjax;
  
  if (lAsync == undefined) {
    lAsync = false;
  }
  
  if (typeof(sMessage) == 'undefined') {
    sMessage = 'Aguarde, Carregando...';
  }
  
  var oAjax = new Ajax.Request(sUrl, 
                               {
                                 method: 'post', 
                                 asynchronous: lAsync,
                                 parameters: 'json='+Object.toJSON(oParam),
                                 onCreate  : function(){
		                                       js_divCarregando(sMessage, 'msgbox');
	                                         }, 
                                 onComplete: function(oAjax) {
                                               
                                               var evlJS           = jsRetorno+'(oAjax);';
                                               js_removeObj('msgbox');
                                               return mRetornoAjax = eval(evlJS);
                                               
                                           }
                               }
                              );

  return mRetornoAjax;

}


/**
 * 
 * Arruma \n vindo do ajax para apresentar no alert
 * 
 * @param strMessage
 * @return
 */
function message_ajax( strMessage ) {

  var strNew = strMessage.replace(/\\n/g, "\n");  
  alert( strNew );

}

function somaDataDiaMesAno(data, iDiaSomar, iMesSomar, iAnoSomar) {

  dTimestamp = data.getTime();
  dTimestamp = parseInt(dTimestamp, 10) + (parseInt(iAnoSomar, 10) * 31557600000); //31536000
  dTimestamp = parseInt(dTimestamp, 10) + (parseInt(iMesSomar, 10) * 2629800000);  //2628000
  dTimestamp = parseInt(dTimestamp, 10) + (parseInt(iDiaSomar, 10) * 86400000);
  return new Date(dTimestamp);

}

/*
 * Class Ajax orientado objeto Objetivo: Simplificar o uso do ajax e reduzir o
 * codigo
 * 
 * ->Atributos url = string com o nome do arquivo RPC param = Objeto que ira
 * receber os parametros
 */
var ws_ajax = Class.create();
ws_ajax.prototype = {
    initialize: function(rpc){
        this.url   = rpc;
        this.param = new Object();
    },
    // verifica se a data é valida
    add: function(campo,valor){
    var evlTmp = 'this.param.'+campo+'='+'\''+valor+'\'; \n';
        eval(evlTmp);
    },
    execute: function(acao,func){
        this.add('exec',acao);
        var objAjax = new Ajax.Request(
                              this.url,
                              {
                                method    : 'post',
                                parameters: 'json='+Object.toJSON(this.param),
                                onCreate  : function(){
                                              js_divCarregando( 'Por favor espere', 'msgbox');
                                            },
                                onFailure: function(reportError){
                                               js_removeObj('msgbox');
                                               alert(reportError.responseText);
                                             },
                                onComplete: function(objAjax){
                                              var evlJS = func+'( objAjax )';
                                              js_removeObj('msgbox');
                                              eval( evlJS );
                                            }
                               }
                          );
    },
    monta: function(obj_ajax){
         return eval("("+obj_ajax.responseText+")");
    },
    clear: function(){
        this.param = new Object();
    },
    getvalor: function(valor,campo,tabela,where,nome,alvo){
        aux = new ws_ajax('libs/websellerRPC.php');
        aux.add('valor',valor);
        aux.add('campo',campo);
        aux.add('tabela',tabela);
        aux.add('where',where);
        aux.add('nome',nome);
        aux.add('alvo',alvo);
        aux.add('exec','getvalor');
        var objAjax = new Ajax.Request(
                              aux.url,
                              {
                                method    : 'post',
                                parameters: 'json='+Object.toJSON(aux.param),
                                onCreate  : function(){
                                              js_divCarregando( 'Por favor espere', 'msgbox');
                                            },
                                onFailure: function(reportError){
                                               js_removeObj('msgbox');
                                               alert('!Erro[RPC] '+reportError.responseText);
                                             },
                                onComplete: function(objAjax){
                                              obj_retorno = aux.monta(objAjax);
                                              js_removeObj('msgbox');
                                              $(obj_retorno.alvo).value=obj_retorno.valor.urlDecode();
                                            }
                               }
                          );
    },
    test: function(){
       alert('Classe OK! WS-AJAX versão:1.2 Webseller[117]');
    },
    tostring: function(){
        str=this.param;
        // write('RPC['+this.url+'] Parametros['+str+']');
        return 'RPC['+this.url+']  Parametros['+str+']';
    }
}

/**
 * A função gera um número aleatório dentro de um intervalo de tempo
 * determinado. Exemplo: alert(rand(1, 5)); // 1 ou 2 ou 3 ou 4 ou 5
 */
function rand(min, max) {
  return Math.floor((Math.random() * (max - min + 1)) + min);
}


function show_calendarsaude(obj, shutdown_function, especmed, iUpsSolicitante, iUpsPrestadora) {

  // #01#//show_calendar
  // #10#//Funcão para mostrar o calendário do sistema
  // #20#// shutdown_function: função ao ser executada no final da execução do
  // calendário
  // #15#//show_calendar()

  if(PosMouseY >= 270) {
    PosMouseY = 270;
  }
  
  if(PosMouseX >= 600) {
    PosMouseX = 600;
  }

  js_OpenJanelaIframe(
    '',
    'iframe_data_'+obj,
    'func_calendariosaude2.php?nome_objeto_data='+obj
                            +'&shutdown_function='+shutdown_function
                            +'&sd27_i_codigo='+especmed
                            +'&upssolicitante='+iUpsSolicitante
                            +'&upsprestadora='+iUpsPrestadora
                            +'&fechar=true',
    'Calendário',
    true,
    PosMouseY,
    PosMouseX,
    600,
    270
  );

}

function show_calendariolaboratorio(nome_obj,function_retorno,SetorExame,iQuantidade) {
  
  //#01#//show_calendar
  //#10#//Funcão para mostrar o calendário do sistema
  //#20#// shutdown_function: função ao ser executada no final da execução do calendário
  //#15#//show_calendar()
  if(PosMouseY >= 270){
    PosMouseY = 270;
  }
  if(PosMouseX >= 600){
    PosMouseX = 600;
  }
  js_OpenJanelaIframe('',
                  'iframe_data_'+nome_obj,
                  'func_calendariolaboratorio.php?nome_objeto_data='+nome_obj+
                  '&shutdown_function='+function_retorno+'&la09_i_codigo='+SetorExame+
                  '&iQuantidade='+iQuantidade+'&fechar=true',
                  'Calendário',
                  true,
                  PosMouseY,
                  PosMouseX,
                  250,
                  270);

}

function show_calendarexames(obj,shutdown_function,especmed) {
// #01#//show_calendar
// #10#//Funcão para mostrar o calendário do sistema
// #20#// shutdown_function: função ao ser executada no final da execução do
// calendário
// #15#//show_calendar()

  if(PosMouseY >= 270)
    PosMouseY = 270;
  if(PosMouseX >= 600)
    PosMouseX = 600;

    js_OpenJanelaIframe('','iframe_data_'+obj,'func_calendarioexames.php?nome_objeto_data='+obj+'&shutdown_function='+shutdown_function+'&s111_i_codigo='+especmed+'&fechar=true','Calendário',true,PosMouseY,PosMouseX,250,270);

}


/*
 * Função para alinhar caracters alert('Retorno: ' + strPad('5',10,'0','L'));
 */
function strPad(palavra, casas, carac, dir) {
  // dir = 'R' => Right; dir = 'L' => Left;
  if(palavra == null || palavra == '') palavra = 0;
  var ret = '';
  var nro = casas - (palavra.length);
  for(var i = 0; i < nro; i++) ret += carac;
  if(dir == 'R')
    ret = palavra + ret;
  else if(dir == 'L')
    ret += palavra;
  return ret;
}

/*
 * Coloca mascara na hora @hora = valor atuald o campo "this.value" @x = nome do
 * campo('sd29_c_hora') ou número do index @event= evento. Ex:
 * OnKeyUp=mascara_hora(this.value,'sd29_c_hora',event)
 */
function mascara_hora(hora,x,event,verhora){
 var myhora = '';
 if(verhora == undefined){
  var verhora=true;
 }
 myhora = myhora + hora;
 if( event == undefined ){
   if( myhora.length == 2){
    myhora = myhora + ':';
    document.form1[x].value = myhora;
   }
 }else{
   // k != 8 -- backspace
   k = event.keyCode;

   if( k!=8 && myhora.length == 2){
    myhora = myhora + ':';
    document.form1[x].value = myhora;
   }else if( k==8 && myhora.length == 2){
    document.form1[x].value = document.form1[x].value.substring(0,1);
   }
 }
 if(verhora==true){
  if(myhora.length == 5){   
   verifica_hora(x);  
  }
 }
}


function verifica_hora(x){
 hrs = document.form1[x].value.slice(0,2);
 min = document.form1[x].value.slice(3,5);
 
 situacao = "";
 // verifica hora
 if ( (hrs < 00 ) || (hrs > 23) || ( min < 00) || ( min > 59) ) {
  alert("E R R O !!!\n\nHora inválida!\nPreencha corretamente o campo!");
  document.form1[x].value="";
  document.form1[x].focus();
 }
}

/*
 * verifica hora e completa com o que falta
 */

function js_verifica_hora_webseller(valor,campo){
  
  var erro = 0;
  var ms   = "";
  var hs   = "";
  
  var tam  = "";
  var pos  = "";
  var tam  = valor.length;
  var pos  = valor.indexOf(":");
  
  if (pos != -1) {
	  
    if (pos == 0 || pos > 2) {
      
      erro++;
    
    } else {
    	
      if (pos == 1) {

        hs = "0" + valor.substr(0, 1);
        ms = valor.substr(pos + 1, 2);

      } else if (pos == 2) {

        hs = valor.substr(0, 2);
        ms = valor.substr(pos + 1, 2);

      }
      if (ms == "") {
        ms = "00";
      } else if (ms.length == 1) {
        ms = ms+"0";        
      }
      
    }
    
  } else {
	  
    if (tam >= 4) {
    	
      hs = valor.substr(0, 2);
      ms = valor.substr(2, 2);
    
    } else if (tam == 3) {
    	
      hs = "0" + valor.substr(0, 1);
      ms = valor.substr(1, 2);
      
    } else if (tam == 2) {
      
      hs = valor;
      ms = "00";
    
    } else if (tam == 1) {
    	
      hs = "0" + valor;
      ms = "00";
    
    } else {
    	
      var objData = new Date();
      var hs      = "" + objData.getHours();
      var ms      = "" + objData.getMinutes();
      if (hs.length == 1) {
    	hs = '0' + hs;  
      }
      if (ms.length == 1) {
    	ms = '0' + ms;  
      }
      valor  = hs + ":" + ms;

    }
    
  }
  if (ms != "" && hs != "") {
	  
    var reDigits = /^\d+$/;
    if (!reDigits.test(ms + hs)) {
        
    	erro++;
        valor = '0';
        hora  = '';
        minu  = '';
        
    } else {
      
      if (hs > 24 || hs < 0 || ms > 60 || ms < 0) {
    	  
        erro++;
        
      } else {
    	  
        if (ms == 60) {
          ms = "59";
        }
        if (hs == 24) {
          hs = "00";
        }
        hora = hs;
        minu = ms;
      
      }
      
    }
    
  }

  if (erro > 0) {
    alert("Informe uma hora válida.");
  }
  if (valor != "") {
	  
    eval("document.form1." + campo + ".focus();");
    if (hora != '') {
      
      if (hora.length == 1) {
        hora = '0' + hora;
      }
      if (minu.length == 1) {
        minu = '0' + minu;
      }
      eval("document.form1." + campo + ".value='" + hora + ":" + minu + "';");
    
    } else {
      eval("document.form1." + campo + ".value='';");
    }
    
  }

}

/*
 * verifica hora e completa com o que falta
 */

function js_mascara_hora_webseller(sValor,campo){
  tam        = sValor.length;
  sNovoValor = sValor;
  sMsgErro   = '';
  var reDigits = /^\d+$/;
  if (tam < 6) {
    if(tam==1) {
      if(!reDigits.test(sValor)){
        sNovoValor = '';
      }
    } else if(tam==2) {
    
      if (!reDigits.test(sValor[1]) && (sValor[1] != ':')) {
        sNovoValor = sValor[0];
      }else{
        if (sValor[1] == ':') {
          sNovoValor = '0'+sValor[0]+':';
        }else{
          hh = parseInt(sValor,10);
          if (hh > 23) {
            if (hh == 24) {
              sNovoValor = '00:';
            }else{
              sNovoValor = '';
              sMsgErro   = 'Hora invalida!';
            }
          }
        }
      }
        
    } else if(tam==3) {
      hh = parseInt(sValor[0]+sValor[1],10);
      if (sValor[2]!=':') {
        if(reDigits.test(sValor[2])){
          //
        }else{
          sNovoValor = sValor[0]+sValor[1];
        }
      }
      if (hh > 23) {
        if (hh == 24) {
          sNovoValor[0] = sNovoValor[1] = '0';
        }else{
          sNovoValor = '';
          sMsgErro   = 'Hora invalida!';
        }
      }
    } else if(tam==4) {
      alert(' 4 digitos! ');
    } else if(tam==5) {
      alert(' 5 digitos! ');
    }
  }else{
    sNovoValor = sValor.substr(0,4);
  }
  if (sMsgErro != '') {
    alert(sMsgErro);
  }
  eval("document.form1."+campo+".focus();");
  eval("document.form1."+campo+".value='"+sNovoValor+"';");
}

/*
 * funcão preenche uma string com algum caracter str = string a ser preenchida
 * caracter = caracter para preencher o restante da string tamanho = tamanho a
 * string direcao = L - left, R - right
 */
function preenche( str, caracter, tamanho, direcao ){
     var iLen = String(str).length;
     direcao = direcao.toUpperCase();
     for( ; iLen < tamanho; iLen ++ ){
          if( direcao == 'L' ){
               str = caracter+str;
          }else{
               str = str+caracter;
          }
     }
     return str;
}

/*
 * verifica se @data se encontra entre @inicio e @fim @data tipo:data @inicio
 * tipo:data @fim tipo:data Formato de entrada da data= YYYY-MM-DD
 * @deprecated
 * @See date.js
 * 
 */
function js_validata(datamat, inicio, fim) {

 var data1   = inicio.substr(0,4)+''+inicio.substr(5,2)+''+inicio.substr(8,2);
 var data2   = fim.substr(0,4)+''+fim.substr(5,2)+''+fim.substr(8,2);
 var datamat = datamat.substr(0,4)+''+datamat.substr(5,2)+''+datamat.substr(8,2);
 if(parseInt(datamat)>=parseInt(data1) && parseInt(datamat)<=parseInt(data2)){
  ok = true;
 }else{
  ok = false;
 }
 return ok;
}

function RetiraInvalido(string,expres){
 tamanhostring = string.length;
 new_string = '';
 for(x=0;x<tamanhostring;x++){
  let = string.substr(x,1);
  if(let.match(expres)){
   let = "";
  }
  new_string = new_string+let;
 }
 return new_string;
}
function RetiraAcentos(string){
   tamanhostring = string.length;
   new_string = '';
   for(x=0;x<tamanhostring;x++){
    let = string.substr(x,1);
      acentos = 'ÁÉÍÓÚÀÈÌÒÙÂÊÎÔÛËÜÏÖÑÃÕÇÄã';
      letras  = 'AEIOUAEIOUAEIOUEUIONAOCAA';
    tamacentos = acentos.length;
    tamletras = letras.length;
    for(w=0;w<tamacentos;w++){
     leterro = acentos.substr(w,1);
     letcerto = letras.substr(w,1);
     if(let==leterro){
      let = letcerto;    
     }   
    }
    new_string = new_string+let;
   }
   return new_string;
  
}

function js_ValidaCamposEdu(obj, tipo, nome, aceitanulo, maiusculo, evt) {
 // #01#//js_ValidaCamposEdu
 // #10#//Funcao para validar o conteúdo do campo quando digitado no
  // formulário
 // #15#//js_ValidaCamposEdu(obj,tipo,nome,aceitanulo,maiusculo,evt);
 // #20#//objeto : Nome do objeto do formulário
 // #20#//tipo : Tipo de consistencia do objeto gerado
 // #20#// 1 - Letras e espaço = RegExp("[^A-Za-zà-úÁ-ÚüÜ ]+")
 // #20#// 2 - Números, Letras, espaço, ª, º, ° e traço =
  // RegExp("[^A-Za-z0-9à-úÁ-ÚüÜ ªº°-]+")
 // #20#// 3 - Números, Letras, espaço, ponto, virgula, barra, ª, º, ° e traço =
  // RegExp("[^A-Za-z0-9à-úÁ-ÚüÜ \.,\/ªº°-]+")
 // #20#// 4 - Números, Letras(sem acentuação e cedilha), ponto, arroba,
  // sublinha e traço = RegExp("[A-Za-z0-9\.@_-]+")
 // #20#//Nome : Descrição do campo para mensagem de erro
 // #20#//Aceitanulo : Se aceita o campo nulo ou não: true = aceita false =
  // não aceita
 // #20#//Maiusculo : Se campo deve ser maiusculo, quando digita o sistema
  // troca para maiusculo
 // #20#//evt : este parâmetro não deve ser passado para a função, pois é
  // automático do javascript
 evt = (evt)?evt:(event)?event:'';
 if(maiusculo=='t'){
  var maiusc = new String(obj.value);
  obj.value = maiusc.toUpperCase();
 }
 if(tipo==1){
  var expr = new RegExp("[^A-Zà-úÁ-ÚüÜ ]+");
  if(obj.value.match(expr)){
   alert(nome+" deve ser preenchido somente com Letras!");
   obj.value = RetiraInvalido(obj.value,expr);
   obj.focus();
  }
 }else if(tipo==2){
  var expr = new RegExp("[^A-Zà-úÁ-ÚüÜ0-9 ªº°-]+");
  if(obj.value.match(expr)){
   alert(nome+" deve ser preenchido somente com Números, Letras, espaço, ª, º, ° e traço ");
   obj.value = RetiraInvalido(obj.value,expr);
   obj.focus();
  }
 }else if(tipo==3){
  var expr = new RegExp("[^A-Zà-úÁ-ÚüÜ0-9 \.,\/ªº°-]+");
  if (obj.value.match(expr)) {
   alert(nome+" deve ser preenchido somente com Números, Letras, espaço, ponto, virgula, barra, ª, º. ° e traço!");
   obj.value = RetiraInvalido(obj.value,expr);
   obj.focus();
  }
 }else if(tipo==4){
  var expr = new RegExp("[^A-Z0-9\.@_-]+");
  if (obj.value.match(expr)) {
   alert(nome+" deve ser preenchido somente com Números, Letras(sem acentuação e cedilha), ponto, arroba, sublinha e traço!");
   obj.value = RetiraInvalido(obj.value,expr);
   obj.focus();
  }
 }
 js_putInputValue(obj.name, obj.value);
 return;
}
function jsValidaEmail(email,label){
 if(email!=""){
  var expr = /\./g;
  var expr1 = /@/g;
  if(!email.match(expr) || !email.match(expr1)){
   alert("E-mail deve possuir arroba e ponto!");
   return false;
  }
  if(email.match(expr1)){
   var expr2 = /[A-Za-z0-9]@[A-Za-z0-9]/g;
   if(!email.match(expr2)){
    alert(label+" deve possuir caracteres alfanuméricos antes e depois do arroba!");
    return false;
   }
  }
 }else{
  return true;
 }
}

function URLEncode (clearString) {
  var output = '';
  var x = 0;
  clearString = clearString.toString();
  var regex = /(^[a-zA-Z0-9_.]*)/;
  while (x < clearString.length) {
    var match = regex.exec(clearString.substr(x));
    if (match != null && match.length > 1 && match[1] != '') {
      output += match[1];
      x += match[1].length;
    } else {
      if (clearString[x] == ' ')
        output += '+';
      else {
        var charCode = clearString.charCodeAt(x);
        var hexVal = charCode.toString(16);
        output += '%' + ( hexVal.length < 2 ? '0' : '' ) + hexVal.toUpperCase();
      }
      x++;
    }
  }
  return output;
}

function URLDecode (encodedString) {
  var output = encodedString;
  var binVal, thisString;
  var myregexp = /(%[^%]{2})/;
  while ((match = myregexp.exec(output)) != null
             && match.length > 1
             && match[1] != '') {
    binVal = parseInt(match[1].substr(1),16);
    thisString = String.fromCharCode(binVal);
    output = output.replace(match[1], thisString);
  }
  return output;
}
function show_calendarmerenda(obj,shutdown_function) {
// #01#//show_calendar
// #10#//Funcão para mostrar o calendário do sistema
// #20#// shutdown_function: função ao ser executada no final da execução do
// calendário
// #15#//show_calendar()

  if(PosMouseY >= 270)
    PosMouseY = 270;
  if(PosMouseX >= 600)
    PosMouseX = 600;

    js_OpenJanelaIframe('','iframe_data_'+obj,'func_calendariomerenda.php?nome_objeto_data='+obj+'&shutdown_function='+shutdown_function,'Calendário',true,PosMouseY,PosMouseX,200,230);

}
/**
 * Função para mascaramento das avaliacoes
 */
function js_observeMascaraNota(oInput, sMascara) {
     
     oInput.setAttribute('mascara', sMascara);
     oInput.maxLength = sMascara.length;
     oInput.value  = js_mascaraNota(oInput.value, sMascara);
     oInput.observe('keydown', function (Event) {
      
      var iTeclaPressionada = document.all ? Event.keyCode : Event.which;
      oInput.setAttribute('especialEvents', '0');
      var aTeclasEventos  = new Array(8, 46, 40, 39, 38, 37, 13, 17, 9, 32);
      if (!js_search_in_array(aTeclasEventos, iTeclaPressionada)) {
        oInput.setAttribute('especialEvents', '1');
       }
     });
     
     oInput.observe('change', function (Event) {
        
        var sMascara = oInput.getAttribute('mascara');
        sValor       = oInput.value.replace(/,/g,'.');
        oInput.value = js_mascaraNota(sValor, sMascara); 
     });
     
     
     oInput.observe('keypress', function (Event) {
       
       var iTeclaPressionada = document.all ? Event.keyCode : Event.which;
       var sValorTecla       =  String.fromCharCode(iTeclaPressionada);
       if ((sValorTecla == '.' && hasPoint(oInput.value)) || sValorTecla == ' ') {
         
         Event.preventDefault();
         Event.stopPropagation();
         return false;
       }
       
       var aStringsValidas = new Array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
       if (hasPoint(oInput.getAttribute('mascara'))) {
         aStringsValidas.push('.', ',');
       } 
       
       if (!js_search_in_array(aStringsValidas, sValorTecla) && oInput.getAttribute('especialEvents') == '1') {
       
         Event.preventDefault();
         Event.stopPropagation();
         return false;
       }
       
     });
   }
   
   function hasPoint(sValor) {
     
     if (sValor.indexOf(".") > -1) {
      return true;
     }
     return false;
   }
   
   function js_mascaraNota(sNota, sMascara) {
     
     if (sNota == "") {
       return sNota;
     }
     sNota              = new String(sNota);
     var aPartesNota    = sNota.split(".");
     var aPartesMascara = sMascara.split(".");
     sParteInteira      = aPartesMascara[0];
     sParteDecimal      = '';
     if (aPartesNota[0]) {
      
       sParteInteira   = aPartesNota[0];
       if (aPartesNota[0].length < aPartesMascara[0].length) {
         sParteInteira = aPartesNota[0];
       } else {
         sParteInteira = aPartesNota[0].substr(0, aPartesMascara[0].length); 
       } 
     } 
     if (aPartesMascara[1]) {
      
       sParteInteira += ".";
       sParteDecimal  =  aPartesMascara[1];
       if (aPartesNota[1]) {
        
         sParteDecimal = aPartesNota[1];
         if (aPartesNota[1].length < aPartesMascara[1].length) {
           sParteDecimal = js_strPadRight(aPartesNota[1], aPartesMascara[1].length, '0');
         } else {
           sParteDecimal = aPartesNota[1].substr(0, aPartesMascara[1].length);
         }
       }  
     }
     sNota = sParteInteira+sParteDecimal;
     return sNota;
   }
/**
 * @fileoverview Define campo do tipo input text para datas
 *
 * @author Robson Inacio robson@dbseller.com.br
 * @version  $Revision: 1.14 $
 */

/**
 * Cria um input tipo text para manipulação de datas
 * @class dbTxtFieldData
 * @constructor
 * @param {string} sName id do Objeto
 * @param {string} sNameInstance nome da instancia do objeto, usado para referencia interna
 * @param {string} sValue valor Default do Objeto
 * @param {string} sSize tamanho
 */
DBTextFieldData = function (sName,sNameInstance, sValue, sJsClick) {

  require_once("scripts/strings.js");


  this.sIndexInstance = "DBTextFieldData" + new Number(DBTextFieldData.addInstance( this ));
  this.sNameInstance  = "DBTextFieldData.oInstances['"+this.sIndexInstance+"']";
  sNameInstance       = this.sNameInstance;

  if ( sJsClick == undefined ) {
    sJsClick = '';
  }
  if ( sValue == undefined ) {
    sValue = '';
  }

  this.PosMouseX  = 0;
  this.PosMouseY  = 0;
  this.sName      = sName;

  this.fShutdown  = function (){

    evento = new Event('change');
    this.getElement().dispatchEvent(evento);
  };

  var me         = this;
  this.sStringConteudo  = "<input type    = 'text' ";
  this.sStringConteudo += "        name    = '"+this.sName+"'";
  this.sStringConteudo += "        id      = '"+this.sName+"' ";
  this.sStringConteudo += "        value   = '"+sValue+"' ";
  this.sStringConteudo += "        size    = '10' ";
  this.sStringConteudo += "        maxlength    = '10'";
  this.sStringConteudo += "        autocomplete = 'off'";
  this.sStringConteudo += "        onblur  = \" "+sNameInstance+".mascaraData(this,event); "+sNameInstance+".validaData(this,event);\"";
  this.sStringConteudo += "        onkeydown = \"return "+sNameInstance+".mascaraData(this,event)\" ";
//  this.sStringConteudo += "        onfocus = '"+sNameInstance+".validaEntrada(this);'
  this.sStringConteudo += " > ";
  this.sStringConteudo += " <input value='D' ";
  this.sStringConteudo += "        type='button' ";
  this.sStringConteudo += "        id='dtjs_"+this.sName+"'";
  this.sStringConteudo += "        name='dtjs_"+this.sName+"'";
  this.sStringConteudo += "        onclick=\""+sJsClick+"; "+sNameInstance+".pegaPosMouse(event); "+sNameInstance+".showCalendar('"+this.sName+"','')\" >";

  /**
   *Valida a digitacao da data
   *@private
   */
  this.validaData = function(oObj,event){

    this.mascaraData(oObj, event);

    var strValor = oObj.value;
    if (strValor == '' || strValor == null){
      return false;
    }
    var Dia = strValor.substr(0,2);
    var Mes = strValor.substr(3,2);
    var Ano = strValor.substr(6,4);
    var data = new Date(Ano,(Mes-1),Dia);

    if (checkleapyear(Ano)) {
      var fev = 29;
    }else{
      var fev = 28;
    }
    //                  01  02 03 04 05 06 07 08 09 10 11 12
    var dia = new Array(31,fev,31,30,31,30,31,31,30,31,30,31);
    var diaexpr = new RegExp("[0-3][0-9]");
    if(Dia.match(diaexpr) == null || Dia > dia[Mes-1] || Dia == "00") {
      alert("Dia inválido!");
      oObj.value = '';
      oObj.select();
      return false;
    }

    var mesexpr = new RegExp("[01][0-9]");
    if(Mes.match(mesexpr) == null ||  Mes > 12 || Mes == "00") {
      alert("Mês inválido");
      oObj.value = '';
      oObj.select();
      return false;
    }

    var anoexpr = new RegExp("[12][0-9][0-9][0-9]");
    if(Ano.match(anoexpr) == null) {
      alert("Ano inválido");
      oObj.value = '';
      oObj.select();
      return false;
    }

    return true;
  }
  /**
   *pega a posição do mouse
   *@private
   */
  this.pegaPosMouse = function(evt){

    if( typeof(event) != "object" ) {

      this.PosMouseX = evt.clientX+5;
      this.PosMouseY = evt.clientY;

    } else {

      this.PosMouseX = event.x;
      this.PosMouseY = event.y;
    }

  }
  /**
   *mascara a data
   *@private
   */
  this.mascaraData = function(oCampo,evt){

    var strAux           = '';
    var tecla            = evt.keyCode;
    var valor            = oCampo.value;
    var exprLiterais     = new RegExp("[^0-9]+");

    // constante array com o codigo das teclas a serem ignoradas
    const teclasNaoFormatadas = new Array(8,13,35,36,37,38,39,40,45,46);

    valor  = valor.replace(".", ""); // tira ponto "."
    valor  = valor.replace("-", ""); // tira traco "-"
    valor  = valor.replace("/", ""); //
    valor  = valor.replace("/", "");
    valor  = valor.replace("/", "");

    if(tecla == 8 || tecla == 46 ){
      var tmpstr = this.colocaBarras(oCampo,valor,true);
      return true;
    }

    if (!js_search_in_array(teclasNaoFormatadas,tecla)){
      // tira os caracteres literais
      for(i=0; i < valor.length; i++){
        if(!valor[i].match(exprLiterais)){
          strAux += valor[i];
        }else{
          strAux  = '';
        }
      }
      oCampo.value = this.colocaBarras(oCampo,strAux,false);
      return true;
    }

  }

  /**
   *seta a data
   *@param integer dia dia
   *@param integer mes mes
   *@param integer ano ano
   */
  this.setData = function(dia,mes,ano){
    var objData   = document.getElementById(this.sName);
    objData.value = dia+"/"+mes+'/'+ano;
  }

  /**
   *Seta o valor
   */
  this.setValue = function(sValue) {

    var objData   = document.getElementById(this.sName);
    objData.value = sValue;
  }

  /**
   * Mostra o calentario
   *@param string sName - Nome do objeto
   */
  this.showCalendar = function (sName) {

	if( this.PosMouseY >= document.body.clientHeight-270) {
     this.PosMouseY = document.body.clientHeight-270;
    }
	if(this.PosMouseX >= document.body.scrollWidth-270){
      this.PosMouseX = document.body.scrollWidth-270;
    }

	var sQueryString         = 'dbTxtFieldDataCalendar.php?';
	    sQueryString        += '&nome_objeto_data='+sName;
	    sQueryString        += '&nome_instancia='+this.sIndexInstance;
    var sNameIframeCalendar  = sName.replace(/\./g, '');

    js_OpenJanelaIframe('','iframe_data_'+sNameIframeCalendar,sQueryString,'Calendário',true,this.PosMouseY,this.PosMouseX,200,230);

    $('Janiframe_data_'+sNameIframeCalendar).style.zIndex='150000';

  }

  /**
   * Coloca as barras conforme digitacao
   * @private
   */
  this.colocaBarras = function(obj,strValor,apagando){

    var strRetorno      = '';
    var strNumDigitados = strValor.length;
    var strRetorno      = '';
    var aValorAnt       =  new String(obj.value).split('/');

    if ( aValorAnt.length > 1 ) {
      if ( aValorAnt.length == 3 ) {
        if ( aValorAnt[0].length < 2 || aValorAnt[1].length < 2 || aValorAnt[2].length < 4 ) {
          return obj.value;
        }
      } else {
        if ( aValorAnt[1].length == 1 ) {
          return obj.value;
        }
      }
    }

    if(!apagando){

      if(strNumDigitados >= 2 && strNumDigitados < 4){
        strRetorno = strValor.substr(0,2)+'/'+strValor.substr(2,strNumDigitados-1);
      } else if(strNumDigitados >= 4 && strNumDigitados < 8) {
        var fev        = 29;
        var diaatual   = new Number(strValor.substr(0,2));
        var mesatual   = new Number(strValor.substr(2,2));
        mesatual--;
        //                  01  02 03 04 05 06 07 08 09 10 11 12
        var dia = new Array(31,fev,31,30,31,30,31,31,30,31,30,31);
        if(diaatual > dia[mesatual]) {
          strRetorno = dia[mesatual]+'/'+strValor.substr(2,2)+'/'+strValor.substr(4,strNumDigitados-1);
        }else {
          strRetorno = strValor.substr(0,2)+'/'+strValor.substr(2,2)+'/'+strValor.substr(4,strNumDigitados-1);
        }

      }else if(strNumDigitados == 8){

        var diaatual = new Number(strValor.substr(0,2));
        var mesatual = new Number(strValor.substr(2,2));
        mesatual--;
        if (checkleapyear(strValor.substr(4,4))) {
          var fev = 29;
        }else{
          var fev = 28;
        }

        var dia = new Array(31,fev,31,30,31,30,31,31,30,31,30,31);
        var ano = new Array(31,fev,31,30,31,30,31,31,30,31,30,31);
        if(diaatual > dia[mesatual]) {
          strRetorno = dia[mesatual]+'/'+strValor.substr(2,2)+'/'+strValor.substr(4,4);
        }else {
          strRetorno = strValor.substr(0,2)+'/'+strValor.substr(2,2)+'/'+strValor.substr(4,4);
        }

      }else{
        return strValor;
      }
      return strRetorno;
    }
  }
   /**
    * renderiza o widget no no especificado
    * @return void
    */
  this.show = function (oNo){
    oNo.innerHTML = this.sStringConteudo;
  }

  /**
    * retorna o objeto em formato html
    * @return string
    */
  this.toInnerHtml = function() {
    return this.sStringConteudo;
  }

  /**
    * retorna o valor do objeto
    * @return string
    */
  this.getValue = function () {
    return $F(this.sName);
  }

  /**
    * Define o campo como somente leitura.
    * @param bollean lReadOnly true somente leitura
    * return void
    */
   this.setReadOnly = function(lReadOnly) {

     me.lReadOnly = lReadOnly;
     if ($(me.sName)) {

       $(me.sName).readOnly = lReadOnly;
       if (lReadOnly) {

         $(me.sName).style.backgroundColor = 'rgb(222, 184, 135)';
         $('dtjs_'+me.sName).style.display = 'none';
       } else {

         $(me.sName).style.backgroundColor = 'white';
         $('dtjs_'+me.sName).style.display = 'inline';
       }
     }
   };
};

DBTextFieldData.prototype.getElement = function (){
  return $(this.sName);
};

DBTextFieldData.prototype.setShutdownFunction = function (fFunction) {

  this.fShutdown = fFunction;
  return;
};

/**
 * Repositório das Instancias
 */
DBTextFieldData.oInstances  = DBTextFieldData.oInstances || {};

DBTextFieldData.iCounter    = DBTextFieldData.iCounter   || 0;

DBTextFieldData.addInstance = function( oDBTextFieldData ) {

  if(!(oDBTextFieldData instanceof DBTextFieldData)){
    throw('Objeto Inválido');
  }
  var iNumeroInstancia= DBTextFieldData.iCounter++;
  DBTextFieldData.oInstances['DBTextFieldData'+ iNumeroInstancia] = oDBTextFieldData;
  return iNumeroInstancia;
}

DBTextFieldData.getInstance = function( sName ) {
  return DBTextFieldData.oInstances[sName];
};

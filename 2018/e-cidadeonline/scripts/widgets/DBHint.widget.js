/**
 * @fileoverview Esse arquivo Cria uma div semelhante a um Hint contendo textos etc;
 * @author       Rafael Lopes rafael.lopes@dbseller.com.br
 *               Rafael Nery  rafael.nery@dbseller.com.br
 * @revision $Author: dbalberto $
 * @version  $Revision: 1.1 $
 *
 * Classe que disponibiliza uma div como um hint
 * @param {STRING} sInstancia Nome da instancia do Objeto
*/
var DBHint = function(sInstancia) {
 
  var me             = this;
  var sNameInstance  = sInstancia;
  var sTexto         = ""; 
  var iPadding       = 5;
  var aShowEvents    = new Array("onFocus");
  var aHideEvents    = new Array("onBlur");
  var iTop           = 0;
  var iLeft          = 0;
  me.sPositionTop    = 'T';
  me.sPositionLeft   = 'L';
  me.oDivContainer   = null;
  /**
   * Escreve as funções no elemento
   */
  var setEvents      = function(oElemento) {
    /**
     * Percorre eventos de Exibição
     */
    for (var i = 0; i < aShowEvents.length; i++) {
      
      var sAttr = oElemento.getAttribute(aShowEvents[i]);
      if (sAttr == null){
        sAttr = "";
      }
      oElemento.setAttribute(aShowEvents[i], sNameInstance + ".show(this);" + sAttr);
    }
    /**
     * Percorre eventos de Ocultação
     */
    for (var i = 0; i < aHideEvents.length; i++) {
        
        var sAttr = oElemento.getAttribute(aHideEvents[i]);
        if (sAttr == null){
          sAttr = "";
        }
        oElemento.setAttribute(aHideEvents[i], sNameInstance + ".hide();" + sAttr);
    }
  };
  
  /**
   * Define o Texto de ajuda a ser mostrado
   */
  this.setText       = function(sText){
    sTexto = sText;
  };
  
  /**
   * Define lista de eventos para mostrar o hint
   */
  this.setShowEvents = function(aEvent) {
    aShowEvents = aEvent;
  };
  
  /**
   * Define lista de eventos para ocultar o hint
   */
  this.setHideEvents = function(aEvent) {
    aHideEvents = aEvent;
  };
  
  /**
   * Constrói o componente
   */
  this.make          = function(oElemento) {
    
  /**
   * 
   */
  //oElemento.style.position  = oElemento.style.position == "" ? "relative" : oElemento.style.position;
  /**
   * Define os Eventos para o elemnto mostrar/ocultar o hint
   */
  var lEventos = setEvents(oElemento);                     
  
  
    var oDivContainer                       = document.createElement("DIV");
        oDivContainer.id                    = sNameInstance + "divDBhintExterno";
        oDivContainer.style.position        = "absolute";
        oDivContainer.style.top             = (oElemento.offsetTop - oElemento.offsetHeight - (iPadding * 2) ) + 'px';
        oDivContainer.style.left            = (oElemento.offsetLeft) + 'px';
        oDivContainer.style.border          = '1px solid #FFDD00';
        oDivContainer.style.display         = 'none';
        oDivContainer.style.backgroundColor = '#FFFFCC';
        oDivContainer.style.zIndex          = '9999';

    var oDivInterna                         = document.createElement("DIV");
        oDivInterna.id                      = sNameInstance+"divDBhintInterno";
        oDivInterna.style.overflowX         = "hidden";
        oDivInterna.style.overflowY         = "auto";
        oDivInterna.style.padding           = iPadding + "px";

    oDivInterna.innerHTML                   = sTexto;
    
    oDivContainer.appendChild(oDivInterna);
    document.body.appendChild(oDivContainer);
  };
  
  /**
   * Exibe o Hint
   */
  this.show   = function(oElemento) {
    
    var oCoordinates            = me.getElementCoordinates(oElemento);
    //oElemento.style.position    = oElemento.style.position == "" ? "relative" : oElemento.style.position;
    me.oDivContainer           = document.getElementById(sNameInstance + "divDBhintExterno");
    me.oDivContainer.style.display = '';     
    //oDivContainer.style.top     = oCoordinates.y-(oDivContainer.offsetHeight+oElemento.offsetHeight);
    me.oDivContainer.style.top     = me.getCoordinatesTop(oCoordinates, oElemento);
    me.oDivContainer.style.left    = me.getCoordinatesLeft(oCoordinates, oElemento) + 'px';
  };
  
  /**
   * Esconde o hint
   */
  this.hide          = function() {
    document.getElementById(sNameInstance + "divDBhintExterno").style.display = 'none';     
  };
  
  this.getElementCoordinates = function(oElement) {
	  
	var oCoordinates = new Object();	  
	oCoordinates.x   = 0;
	oCoordinates.y   = oElement.offsetHeight;
    while (oElement.offsetParent && oElement.tagName.toUpperCase() != 'BODY') {
     
    	oCoordinates.x += oElement.offsetLeft;
        oCoordinates.y += oElement.offsetTop;
        oElement = oElement.offsetParent;
    }
    oCoordinates.x += oElement.offsetLeft;
    oCoordinates.y += oElement.offsetTop;
    return oCoordinates;
  }
  
  
  this.setPosition = function(sPositionTop, sPositionLeft) {
     
    me.sPositionLeft = sPositionLeft;
    me.sPositionTop  = sPositionTop;
  }
  
  this.getCoordinatesTop = function (oCoordinates, oElemento) {
  
    var iTop = 0;
    switch (me.sPositionTop.toUpperCase()) {
    
      case 'T':
        
        var iTop = oCoordinates.y-(me.oDivContainer.offsetHeight+oElemento.offsetHeight);
        break;
      
      case 'B':
        
        var iTop = oCoordinates.y;
       break;  
    }
    return iTop;
  }
  
  
  this.getCoordinatesLeft = function(oCoordinates, oElemento) {
  
    var iLeft = 0;
    switch (me.sPositionLeft.toUpperCase()) {
    
      case 'L':
        
        var iLeft = oCoordinates.x;
        break;
      
      case 'R':
        
        var iLeft = oCoordinates.x+oElemento.offsetWidth;
       break;  
    }
    return iLeft;
  }
};  
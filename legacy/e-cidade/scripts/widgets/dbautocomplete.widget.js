/******************************************************
dmsAutoComplete v1.3.2
This work is licensed under the Creative Commons
Attribution-Noncommercial-Share Alike 3.0 License.
To view a copy of this license, visit
http://creativecommons.org/licenses/by-nc-sa/3.0/ or
send a letter to Creative Commons, 171 Second Street,
Suite 300, San Francisco, California, 94105, USA.
*******************************************************

Author:
	Rafael Dohms (rafael at rafaeldohms dot com dot br)
	http://www.rafaeldohms.com.br
Baseado em conceito por: (concept by)
	joekepley at yahoo (dot) com
Contribuiï¿½ï¿½es/Contributions
	Simon Franz (www.tanzmusik-online.de)
	Marcus Ellend (www.uniqa.com.br)
	Jon Bernhardt (www.wobblymusic.com)

*******************************************************
en:
This is an AJAX implementation of a auto-complete/auto-
suggest script. It uses PHP to return a XML result list
and displays it in a div for selection.

pt_br:
Este script ï¿½ uma implementaï¿½ï¿½o AJAX de um script de
auto-compleï¿½ï¿½o/auto-sugestï¿½o, similar o utilizado pelo
Google. Ele usa um backend PHP que retorna um XML de
resultados mostrados em um DIV para seleï¿½ï¿½o

OBS : ALTERADO PARA UTILIZAR JSON AO INVES DE XML
****************************************/
/**
 * cria um widget para autocomplete
 * @class dmsAutoComplete
 * @constructor
 * @param {HTMLElement}  		elemento input
 * @param {HTMLDivElement} 	elemento div
 * @param {string}     			sFileNameRpc  nome do arquivo RPC
 * @param {boolean}     		lAjustaPosicao flag se ajusta posição da lista automaticamente
 */
function dmsAutoComplete(elem,divname,sUrlRpc, lAjustaPosicao) {

	var me                    = this;
	this.clearField           = true;
	this.minLength            = 4;
	this.elem                 = document.getElementById(elem);
	this.highlighted          = -1;
	this.arrItens             = new Array();
	this.sQueryStringFunction = null;
	this.ScrollBar            = false;
  this.iAdicionalWidth       = 0;
	// this.ajaxTarget        = 'dmsAC.php'; // original
	this.ajaxTarget           = sUrlRpc;
  this.lLoader              = false;
  this.oLoading             = null;
	this.lAjustaPosicao 			= lAjustaPosicao === undefined ? true : lAjustaPosicao;

  this.validateFunction	    = function () {
    return true;
  };
	this.chooseFunc           = null; //Funï¿½ï¿½o para executar com obj selecionado
	this.div                  = document.getElementById(divname);
	this.hideSelects          = false;
  var oTxtFieldId           = null;
	//Keycodes que devem ser monitorados
	var TAB                   = 9;
	var ESC                   = 27;
	var KEYUP                 = 38;
	var KEYDN                 = 40;
	var ENTER                 = 13;

	if (this.elem.style.width == "" || typeof(this.elem.style.width) == undefined){
	  //
	  this.div.style.width = ( parseFloat(this.elem.scrollWidth) - 6 + this.iAdicionalWidth );
	}else{
  	//Tamanho do DIV = Tamanho do campo
	  this.div.style.width = ( parseFloat(this.elem.scrollWidth) - 6 + this.iAdicionalWidth );
	}

	//this.div.style.width = '18px';

	//Desabilitar autocomplete IE
	me.elem.setAttribute("autocomplete","off");

	//Crate AJAX Request
	this.ajaxReq = createRequest();

	//Aï¿½ï¿½o a ser executada no KEYDOWN (funï¿½ï¿½es de navegaï¿½ï¿½o)
	me.elem.onkeypress = function(ev)
	{
		var key = me.getKeyCode(ev);

		switch(key)
		{
			case TAB:
			 if (me.highlighted.id != undefined){
          me.acChoose(me.highlighted.id);
        }
        me.hideDiv();
        return true;
			 break;
			case ENTER:
				if (me.highlighted.id != undefined){
					me.acChoose(me.highlighted.id);
				}
				me.hideDiv();
				return false;
			break;

			case ESC:
				me.hideDiv();
				return false;
			break;

			case KEYUP:
				me.changeHighlight('up', ev);
				return false;
			break;

			case KEYDN:
				me.changeHighlight('down', ev);
				return false;
			break;
		}
		//me.elem.onkeypress = me.elem.onkeydown;
	};

	this.setElemValue = function(){
		var a = me.highlighted.firstChild;
		me.elem.value = a.innerTEXT;
	}

	this.highlightThis = function(obj,yn){
		if (yn = 'y'){
			me.highlighted.className = '';
			me.highlighted = obj;
			me.highlighted.className = 'selected';

			//me.setElemValue(obj);

		}else{
			obj.className = '';
			me.highlighted = '';
		}
	}

	this.changeHighlight = function(way, event) {

		if (me.highlighted != '' && me.highlighted != null ){
			me.highlighted.className = '';
			switch(way){
				case 'up':
					if(me.highlighted.parentNode.firstChild == me.highlighted){
						me.highlighted = me.highlighted.parentNode.lastChild;
					}else{
						me.highlighted = me.highlighted.previousSibling;
					}
					me.div.scrollTop = me.getOffsetElement(me.highlighted,me.elem) - 100 ;
				  //me.highlighted.scrollIntoView();
				  event.preventDefault();
          event.stopPropagation();
				break;
				case 'down':
					if(me.highlighted.parentNode.lastChild == me.highlighted){
						me.highlighted = me.highlighted.parentNode.firstChild;
					}else{
						me.highlighted = me.highlighted.nextSibling;
					}
					me.div.scrollTop = me.getOffsetElement(me.highlighted,me.elem) - 100 ;
				break;

			}
			me.highlighted.className = 'selected';
			//me.setElemValue();

		}else{
			switch(way){
				case 'up':
					me.highlighted = me.div.firstChild.lastChild;
				break;
				case 'down':
					me.highlighted = me.div.firstChild.firstChild;
				break;

			}
			me.highlighted.className = 'selected';
			//me.setElemValue();
		}

	}

	//Rotina no KEYUP (pegar input)
	me.elem.onkeyup = function(ev)
	{
		var key = me.getKeyCode(ev);
		switch(key)
		{
		//The control keys were already handled by onkeydown, so do nothing.
		case TAB:
		  return true;
		  break;
		case ESC:
		case KEYUP:
		case KEYDN:
			return;
		case ENTER:
			return false;
			break;
		default:

      if( me.lLoader ) {
        me.oLoading.style.display = 'none';
      }

			//Cancelar requisicao antiga
			me.ajaxReq.abort();

      if( me.lLoader ) {
        me.oLoading.style.display = 'block';
      }
			//Enviar query por AJAX
			//Verificar tamanho mï¿½nimo
			if ((me.elem.value.length >= me.minLength) && me.validateFunction()) {
				if (me.ajaxReq != undefined){

					me.ajaxReq.open("POST", me.ajaxTarget, true);
					me.ajaxReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
					me.ajaxReq.onreadystatechange = me.acResult;


          if ( me.sQueryStringFunction != null ) {
					  var param = me.sQueryStringFunction();
          } else {
            var param = 'string=' + me.elem.value;
          }

					me.ajaxReq.send(param);

				}
			}else{

        if( me.lLoader ) {
          me.oLoading.style.display = 'none';
        }

        me.hideDiv();

				return;
			}

			//Remover elementos highlighted
			me.highlighted = '';
		}
	};

	//Sumir com autosuggest
	me.elem.onblur = function() {
		me.hideDiv();
	}

	//Ajax return function
	this.acResult = function(){

		if (me.ajaxReq.readyState == 4 && me.validateFunction()){

      me.showDiv();

			var oRetorno = eval(me.ajaxReq.responseText);

			//verificar conteudo
			if (oRetorno == undefined){
        return false;
      }

      if( me.lLoader ) {
        me.oLoading.style.display = 'none';
      }

			var itCnt = oRetorno.length;

			//Pegar primeiro filho
			me.div.innerHTML = '';
			var ul = document.createElement('ul');
			me.div.appendChild(ul);

			if (itCnt > 0){

				for (i=0; i<itCnt; i++){

					//Popular array global
					me.arrItens[oRetorno[i].cod]           = new Array();
			    me.arrItens[oRetorno[i].cod]['label']  = oRetorno[i].label.urlDecode();
					me.arrItens[oRetorno[i].cod]['flabel'] = oRetorno[i].label.urlDecode();
          me.arrItens[oRetorno[i].cod]['obj']    = oRetorno[i];

					//Adicionar LI
					var li = document.createElement('li');
					li.id  = oRetorno[i].cod;
					li.onmouseover = function(){ this.className = 'selected'; me.highlightThis(this,'y')}
					li.onmouseout  = function(){ this.className = '';  me.highlightThis(this,'n')}
					li.onmousedown = function() {
						me.acChoose(this.id);
						me.hideDiv();
						return false;
					}

					var a = document.createElement('a');
					a.href = '#';
					a.onclick = function() { return false; }
					a.innerHTML = unescape(oRetorno[i].label.urlDecode());
					if(oRetorno[i].label != null){
						a.innerTEXT = unescape(oRetorno[i].label.urlDecode());
					}

					li.appendChild(a);
					ul.appendChild(li);
				}

			}else{

        if( me.lLoader ) {
          me.oLoading.style.display = 'none';
        }

				me.hideDiv();
			}
		}
	}

  this.wheelScroll = function(event) {

	  var delta = 0;
	  if (!event) /* For IE. */
	    event = window.event;
	  if (event.wheelDelta) { /* IE/Opera. */
	    delta = event.wheelDelta/120;
	  } else if (event.detail) { /** Mozilla case. */
	    delta = -event.detail/3;
	  }
	  if (delta < 0) {
      me.div.scrollTop += 10;
    } else {
      me.div.scrollTop -= 10;
    }
	  if (event.preventDefault){
	    event.preventDefault();
	  }
	   event.returnValue = false;
  }
  me.div.addEventListener('DOMMouseScroll', me.wheelScroll, false);
  this.getOffsetElement = function(element, div) {

    var el = element;
    var x = 0;
    var y = el.offsetHeight;

    //Walk up the DOM and add up all of the offset positions.
    while (el.offsetParent) {
      if (el.className != "windowAux12") {

        x += el.offsetLeft;
        y += el.offsetTop;

      }
      if (el.id == div.id) {
        break;
      }
      el = el.offsetParent;
    }
    //alert(el.id);
    x += el.offsetLeft ;
    y += el.offsetTop;
   return y;
  };

	this.acChoose = function (id){

		if (id != ''){
			//Funï¿½ï¿½o de retorno (Opcional)
			if (me.chooseFunc != null) {
				me.chooseFunc(id,unescape(me.arrItens[id]['label']), me.arrItens[id]['obj']);
			} else {
		    if (this.clearField){
          me.elem.value = '';
        } else {
          me.elem.value = unescape(me.arrItens[id]['label']);
        }
			}
			me.hideDiv();
		}
	}

	this.positionDiv = function()
	{
		var el = this.elem;
		var x = 0;
		var y = el.offsetHeight;

		//Walk up the DOM and add up all of the offset positions.
		while (el.offsetParent && el.tagName.toUpperCase() != 'BODY')
		{
		  if (el.className != "windowAux12") {

			  x += el.offsetLeft;
			  y += el.offsetTop;

		  }
			el = el.offsetParent;
		}
    //alert(el.id);
		x += el.offsetLeft ;
		y += el.offsetTop;

		if ( this.lAjustaPosicao ) {
			this.div.style.left = x + 'px';
			this.div.style.top = y + 'px';
		}
	};

	this.hideDiv = function(){

		me.highlighted = '';
		me.div.style.display = 'none';
		me.div.innerHTML     = '';
		me.handleSelects('');
		//$('div_lista_'+oAutoComplete.sTxtFieldName).innerHTML = '';

	}

	this.showDiv = function(){

 if (me.elem.style.width == "" || typeof(me.elem.style.width) == undefined){
    //
    me.div.style.width = ( parseFloat(me.elem.scrollWidth) - 6 + this.iAdicionalWidth );
  }else{
    //Tamanho do DIV = Tamanho do campo
    me.div.style.width = ( parseFloat(me.elem.scrollWidth) - 6 + this.iAdicionalWidth );
  }
		me.highlighted = '';
		me.positionDiv();
		me.handleSelects('none');
		me.div.style.display = 'block';

	}

	this.handleSelects = function(state){

		if (!me.hideSelects) return false;

		var selects	= document.getElementsByTagName('SELECT');
		for (var i = 0; i < selects.length; i++)
        {
            selects[i].style.display = state;
        }
	}

	//HELPER FUNCTIONS

	/********************************************************
	Helper function to determine the keycode pressed in a
	browser-independent manner.
	********************************************************/
	this.getKeyCode = function(ev)
	{
		if(ev)			//Moz
		{
			return ev.keyCode;
		}
		if(window.event)	//IE
		{
			return window.event.keyCode;
		}
	};

	/********************************************************
	Helper function to determine the event source element in a
	browser-independent manner.
	********************************************************/
	this.getEventSource = function(ev)
	{
		if(ev)			//Moz
		{
			return ev.target;
		}

		if(window.event)	//IE
		{
			return window.event.srcElement;
		}
	};

	/********************************************************
	Helper function to cancel an event in a
	browser-independent manner.
	(Returning false helps too).
	********************************************************/
	this.cancelEvent = function(ev)
	{
		if(ev)			//Moz
		{
			ev.preventDefault();
			ev.stopPropagation();
		}
		if(window.event)	//IE
		{
			window.event.returnValue = false;
		}
	}

  this.setLoader = function(lLoader) {

    this.lLoader = lLoader;

    if (!lLoader) {
      //@apagar elemento da tela.
      this.oLoading
      return;
    }

    this.oLoading                = document.createElement('img');
    this.oLoading.src            = 'imagens/files/loading.gif';
    this.oLoading.style.width    = '18px';
    this.oLoading.style.height   = '18px';
    this.oLoading.style.cssFloat = 'left';
    this.oLoading.style.padding  = '0';
    this.oLoading.style.margin   = '0';
    this.oLoading.style.display  = 'none';

    this.elem.parentNode.appendChild(this.oLoading);

  }
}

function createRequest() {
  try {

    request = new XMLHttpRequest();
  } catch (trymicrosoft) {
    try {
      request = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (othermicrosoft) {
      try {
        request = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (failed) {
        request = false;
      }
    }
  }

  if (!request)
    alert("Error initializing XMLHttpRequest!");
  else
  	return request;
}




/**
 * cria um widget para autocomplete
 * @class dbAutoComplete
 * @constructor
 * @param HTMLInput  oTxtFieldId campo input que ira ter o autocomplete
 * @param string     sFileNameRpc  nome do arquivo RPC
 * @param {boolean}  lAjustaPosicao flag se ajusta posição da lista automaticamente
 */
function dbAutoComplete(oTxtField,sFileNameRpc, lAjustaPosicao){

  this.oTxtField     	= oTxtField;
  this.sTxtFieldName 	= oTxtField.name;
  this.sFileNameRpc  	= sFileNameRpc;
	this.sScrollBars   	= "hidden";
	this.lAjustaPosicao = lAjustaPosicao === undefined ? true : lAjustaPosicao;
	var me             	= this;
	var oTxtFieldId    	= null;

  /**
   *Cria a Definicao do CSS
   * @private
   */

  this.makeCss = function () {

    var sDivId  = 'div_lista_'+this.sTxtFieldName;
    var iHList  = '100px'
    this.oStyle = document.createElement('style');

    if (me.iHeightList != '' && me.iHeightList != undefined) {
      iHList = me.iHeightList+'px';
    }

    /* Definicao dos estilos CSS da DIV */

    var sInnerCss = "";
    sInnerCss += " #"+sDivId+"{  ";
    sInnerCss += "   border: 1px solid #9F9F9F;  ";
    sInnerCss += "   background-color:#F3F3F3;   ";
    sInnerCss += "   padding: 2px;    ";
    sInnerCss += "   font-size:10px;  ";
    sInnerCss += "   font-family:Verdana, Arial, Helvetica, sans-serif;  ";
    sInnerCss += "   color:#000000;      ";
    sInnerCss += "   display:none;       ";
    sInnerCss += "   position:absolute;  ";
    sInnerCss += "   overflow:hidden;  ";
    sInnerCss += "   height:"+iHList+";  ";
    sInnerCss += "   z-index:999;        ";
    sInnerCss += "   overflow-x: hidden; ";
    sInnerCss += "   overflow-y: "+this.sScrollBars +"; ";
    sInnerCss += " } ";
    sInnerCss += " #"+sDivId+" UL{  ";
    sInnerCss += "   list-style:none;    ";
    sInnerCss += "   margin: 0;          ";
    sInnerCss += "   padding: 0;         ";
    sInnerCss += " }                     ";
    sInnerCss += " #"+sDivId+" UL LI{    ";
    sInnerCss += "   display:block;      ";
    sInnerCss += " }                     ";
    sInnerCss += " #"+sDivId+" A{        ";
    sInnerCss += "   color:#000000;         ";
    sInnerCss += "   text-decoration:none;  ";
    sInnerCss += " }                        ";
    sInnerCss += " #"+sDivId+" A:hover{     ";
    sInnerCss += "   color:#000000;         ";
    sInnerCss += " }                        ";
    sInnerCss += " #"+sDivId+" LI.selected{     ";
    sInnerCss += "   background-color:#7d95ae;  ";
    sInnerCss += "   color:#000000;             ";
    sInnerCss += " }                            ";

//    sInnerCss += " #"+sDivId+" { ";
//    sInnerCss += "   width:300px;";
//    sInnerCss += "   height:170px; ";
//    sInnerCss += "   background-color:#F2F2F2; ";
//    sInnerCss += "   overflow:auto; ";
//    sInnerCss += " } ";


    document.getElementsByTagName('head')[0].appendChild(this.oStyle);

    this.oStyle.innerHTML = sInnerCss;

  }

  /**
   * Define o campo que deve receber o autoComplete
   * @param HTMLInput oTxt Objeto do tipo input
   */
  this.setTxtFieldId = function (oTxt) {
	  oTxtFieldId = oTxt;
  }



  /**
   * Define o tamanho da lista de resultados
   * @param integer iPx Inteiro com numero de pixels da altura da lista
   */
  this.setHeightList = function (iPx) {
	  me.iHeightList = iPx;
  }

  /**
   * Cria o autocomplete e inicia a captura dos valores digitados
   *
   */
  this.show = function () {

    this.oDivLista = document.createElement('div');

    this.oDivLista.setAttribute('id', 'div_lista_'+this.sTxtFieldName);
    this.oTxtField.parentNode.appendChild(this.oDivLista);
    this.makeCss();
    this.oObjAutoComplete = new dmsAutoComplete( this.oTxtField.id,
                                                 this.oDivLista.id,
                                                 this.sFileNameRpc,
                                                 this.lAjustaPosicao
																								);

    //Definir opcoes
    this.oObjAutoComplete.clearField = false; //Definir que texto escolhido nao deve ser removido do campo

    //Definir funcao de retorno
    //Esta funcao sera executada ao se escolher a string na lista da div
    /**
     * funcao callback padrao a selecionar um item da lista
     * @param string id Codigo identificador do item
     * @param string label label do item
     */
    this.oObjAutoComplete.chooseFunc = function(id,label) {

      if (typeof(oTxtFieldId) != "undefined"){
    	  oTxtFieldId.value = id.urlDecode();
      }
      oTxtField.value   = label.urlDecode();
      $('div_lista_'+me.sTxtFieldName).innerHTML = '';

    }
  }

  /**
   * define a funcao callback para a acao de selecionar no item
   * @param function sFunction funcao definida pelo usuario para o callback. devemos ter o cuidado de passar dois
   * parametros para a funcao, um id, e um label.
   * @example
   * oAutoComplete.setCallBackFunction(function(id, label) {
   *     alert('id:'+Id+' Label:'+label);
   * });
   */
  this.setCallBackFunction = function (sFunction) {
    this.oObjAutoComplete.chooseFunc = sFunction;
  }

  /**
   * Define uma função que retorna a querystring que será passada para o RPC
   * @param function sFunction deve retornar uma string contento a querystring que será passada para o RPC
   */
  this.setQueryStringFunction = function (sFunction) {
    this.oObjAutoComplete.sQueryStringFunction = sFunction;
  }

  /**
   * Define funcao para verificar se a busca do autocomplete deve ser mostrado
   * @param function fFunction funcao para validacao., deve retornar true ou false
   */
  this.setValidateFunction = function(fFunction) {
    this.oObjAutoComplete.validateFunction = fFunction;
  }
  /**
   * Define valor minino de caracteres para chamar a função autocomplete
   */

  this.setMinLength = function(iMinimoCaracteres){
    this.oObjAutoComplete.minLength = iMinimoCaracteres;
  }

  /**
   * Define se a DIV dos resultados aparecerá com Scroll
   *
   */
  this.setScrollBar = function(lShow){
    if(lShow == true){
      this.sScrollBars = "scroll";
    } else {
      this.sScrollBars = "hidden";
    }
  }

  this.setAdicionalWidth = function(iWidth){

    iWidth = new Number(iWidth);
    this.oObjAutoComplete.iAdicionalWidth = iWidth;
  }

  this.setLoader = function (lLoader){

    this.oObjAutoComplete.setLoader(lLoader);
  }

}

require_once('estilos/windowAux.css');
require_once('scripts/widgets/DBMask.widget.js');

/**
 * Cria uma janela interna , podendo criar aplicacoes MDI
 *
 * @version  $Revision: 1.30 $
 * @constructor
 * @author  Iuri Guntchnigg - <iuri@dbseller.com.br>
 *
 * @example
 * // Uma Janela de 400x500
 * var windowTeste = new windowAux('windowTeste', 'Testando Janela', 400, 500);
 *     windowTeste.setContent("<b>Ola Mundo</b>");
 *     windowTeste.show(10,10)
 *
 * @example
 * //Uma Janela com HTML node como conteudo
 * var oConteudo           = document.createElement('b');
 *     oConteudo.innerHTML = "Olá Mundo";
 * var windowTeste         = new windowAux('windowTeste', 'Testando Janela', 300, 300);
 *     windowTeste.setContent( oConteudo );
 *     windowTeste.show(100,200)
 *
 *
 * @param {String}  iIdWindow identificar da janela
 * @param {String}  sTitle titulo da Janela
 * @param {int} iWidth largura em pixeis da janela
 * @param {int} iHeight  largura em pixeis da janela
 */
windowAux = function (iIdWindow, sTitle, iWidth, iHeight) {

  if (!iIdWindow) {
    iIdWindow = "window" + getElementsByClass('windowAux12').length + 1;
  }

  var idWindow            = iIdWindow;
  this.idWindow           = iIdWindow;
  var me                  = this;
  this.sWindowTitle       = sTitle;
  this.iWidth             = iWidth;
  this.allowDrag          = true;
  this.iHeight            = iHeight;
  var allowCloseWithEsc   = true;
  this.parent             = null;
  this.zIndex             = 500;
  this.aChilds            = [];

  isdrag = false;

  var self        = this;
  this.iMaxBottom = document.body.clientHeight - 50;
  this.iMaxTop    = 20;
  this.iMaxLeft   = null;
  this.iMaxRight  = null;
  this.divWindow  = document.createElement("DIV");
  this.oDBMask    = null;

  if (!this.iWidth) {
     this.iWidth = document.width-12;
  }

  if (!this.iHeight) {
     this.iHeight  = document.body.scrollHeight-50;
  }

  /*
   * Criamos a div principal
   */
  this.divWindow.id                    = idWindow;
  this.divWindow.style.height          = this.iHeight+"px";
  this.divWindow.style.width           = this.iWidth+"px";
  this.divWindow.style.position        = "absolute";
  this.divWindow.style.border          = "3px outset black";
  this.divWindow.style.backgroundColor = "#CCCCCC";
  this.divWindow.style.display         = "none";
  this.divWindow.className             = "windowAux12";
  this.divWindow.tabIndex              = "0";
  var shutDown = function () {
     me.divWindow.style.display="none";
  };

  var shutDownFunction  = shutDown;
  /*
   * Criamos a div do titulo
   */
  this.divTitleWindow           = document.createElement("DIV");
  this.divTitleWindow.id        = idWindow+"TitleBar";
  this.divTitleWindow.className = "windowAuxTitle";
  this.divTitleWindow.setAttribute('divParent',idWindow);

  this.divTitleWindow.style.padding         = "0px";
  this.divTitleWindow.style.textAlign       = "right";
  this.divTitleWindow.style.borderBottom    = "2px outset white";
  this.divTitleWindow.style.backgroundColor = "#2C7AFE";
  this.divTitleWindow.style.color           = "white";

  this.divTitleWindow.innerHTML  ="<span class='dragme' style='width:90%;text-align:left;padding:1px; -moz-user-select:none;cursor:default;float:left;font-weight:bold' id='"+idWindow+"_title'>"+this.sWindowTitle+"</span>";
  this.oImagem = document.createElement("img");
  this.oImagem.setAttribute('style', 'z-index:10001;-moz-user-select:none;');
  this.oImagem.src    = "skins/img.php?file=Controles/jan_fechar_on.png";
  this.oImagem.id     = 'window'+idWindow+'_btnclose';
  this.oImagem.border = 0;

  this.oImagem.onclick = function(){
    shutDownFunction();
  };

  this.divTitleWindow.appendChild(this.oImagem);
  this.divWindow.appendChild(this.divTitleWindow);

  var divWindowContent  = document.createElement("DIV");
  divWindowContent.style.padding   = "3px";
  divWindowContent.id              = "window"+idWindow+"_content";
  divWindowContent.style.border    = "2px inset white";
  divWindowContent.style.overflow  = "auto";
  divWindowContent.style.padding   = "0px";
  divWindowContent.tabIndex        = "1";
  me.divContent                    = divWindowContent;
  document.body.appendChild(this.divWindow);

  /**
   * mostra a janela nas posicoes passadas
   * @param {int} top  altura da tela
   * @param {int} left altura da tela
   */
  this.show = function (top, left, lModal) {

    if (!top) {
      top = 25;
    }
    if (!left) {
      left = ((screen.availWidth-this.iWidth)/2)
    }

    this.divWindow.style.left = left+"px";
    this.divWindow.style.top = top+"px";
    this.divWindow.style.display = '';
    self.toFront();

    if( lModal && lModal === true ) {

      this.oDBMask = new DBMask();
      this.oDBMask.getMaskElement().appendChild( this.divWindow );
    }
  };

  /**
   * esconde a janela
   * @return {void}
   */
  this.hide = function () {
    this.divWindow.style.display='none';
  };

  /**
   * Define o Conteudo da janela, aceitando strings html.
   * @param {Mixed} Conteudo da janela
   */
  this.setContent = function (Content) {

     divWindowContent.style.height = (this.iHeight -32)+"px";
     this.divWindow.appendChild(divWindowContent);

     divWindowContent.innerHTML = '';

     if ( typeof(Content) === "string" ) {

       divWindowContent.innerHTML = Content;
       return;
     }
     divWindowContent.appendChild(Content);
  };

  this.divWindow.observe("keydown", function(event){

    if (allowCloseWithEsc) {
      if (event.which == 27) {

        shutDownFunction();
        event.preventDefault();
        event.stopPropagation();
      }
    }
  });

  /**
   * Define se Permite Fazer Drag'Drop da Janela
   *
   * @param {Boolean} lDrag Permite a janela ser arrastada
   */
  this.allowDrag = function (lDrag) {

    if (lDrag) {
      $(idWindow+"_title").className ='dragme';
    } else {
      $(idWindow+"_title").className   ='';
    }
  };

  this.getId = function() {
    return self.idWindow;
  };

  /**
   * Define como Conteudo da janela  um Objeto HTML ja existente na pagina.
   * @param {Object} oDiv Node HTML
   */
  this.setObjectForContent = function (oDiv) {

    oDiv.style.display='';
    var divWindowContent = oDiv;
    this.divWindow.appendChild(divWindowContent);
    divWindowContent.style.height = (this.iHeight-32)+"px";
    divWindowContent.style.border    = "2px inset white";
    divWindowContent.style.overflow  = "auto";
  };

  /**
   * Define o Titulo da Janela
   * @param {string} sTitle titulo da janela
   */
  this.setTitle = function (sTitle) {

    this.sWindowTitle = sTitle;
    if ( $(idWindow+"_title") ) {
      $(idWindow+"_title").innerHTML = this.sWindowTitle;
    }
  };

  /**
   * Destroi a janela
   * @returns {void}
   */
  this.destroy = function () {

    if ( !!this.oDBMask ) {
      this.oDBMask.destroy();
    }

    oWindow = this.divWindow;
    oWindow.parentNode.removeChild(oWindow);
    return;
  };

  this.setShutDownFunction = function(sFunction) {
    shutDownFunction = sFunction;
  };

  /**
   * Permite fechar a janela com a tecla esc
   */
  this.allowCloseWithEsc= function (lAllow) {
    allowCloseWithEsc = lAllow;
  };

  /**
   * Retorna o titulo da Janela
   */
   this.getTitle = function () {
     return this.sWindowTitle;
   };

   /**
    * realiza o Drag
    * @private
    */
  doDrag = function (event){

    if (isdrag) {

      var iLeft = iPosicaoX + event.clientX - iMouseX;
      var iTop  = iPosicaoY + event.clientY - iMouseY;
      if (iTop > (iMaxBottom)) {

       iTop = iMaxBottom;
      }
      if (iTop  < 0 || iTop < iMaxTop) {
        iTop = iMaxTop;
      }
      if ((iLeft < iMaxLeft) && !!iMaxLeft) {
       iLeft = iMaxLeft;
      }

      if ((iLeft > iMaxRight) && !!iMaxRight) {
       iLeft = iMaxRight;
      }
      oDivDragDrop.style.left = iLeft;
      oDivDragDrop.style.top  = iTop;
      return false;
    }
  };

  /**
   * Inicia o Drag An Drop da Janela
   * @private
   */
  this.initDrag = function (e) {

    if (e.button == 0) {

		  self.toFront();
		  var oObjetoInicio = e.target;
		  var oTopElement   = "HTML";


		  while (oObjetoInicio.tagName != oTopElement && oObjetoInicio.className != "dragme" ) {
		    oObjetoInicio = oObjetoInicio.parentNode;
		  }

		  if (oObjetoInicio.className == "dragme") {

		    objectDrag   = oObjetoInicio.parentNode.parentNode;
		    isdrag       = true;
		    oDivDragDrop = document.createElement("DIV");

		    oDivDragDrop.id                     = "drag";
		    oDivDragDrop.className              = "box_drag";
		    oDivDragDrop.style.padding          = "0px";
		    oDivDragDrop.style.textAlign        = "right";
		    oDivDragDrop.style.border           = "3px dotted #999999";
		    oDivDragDrop.style.backgroundImage  = "url(imagens/transparencia.png)";
		    oDivDragDrop.style.backgroundRepeat = "repeat";
		    oDivDragDrop.style.color            = "white";
		    oDivDragDrop.style.cursor           = "hand";
		    oDivDragDrop.style.zIndex           = "1002";


		    document.body.appendChild(oDivDragDrop);
		    document.body.style.cursor  = "hand";
		    objectDrag.style.cursor     = "hand";
		    oDivDragDrop.style.top      = parseInt(objectDrag.style.top+0,10);
		    oDivDragDrop.style.left     = parseInt(objectDrag.style.left+0,10);
		    oDivDragDrop.style.position = "absolute";
		    oDivDragDrop.style.height   = objectDrag.style.height;
		    oDivDragDrop.style.width    = objectDrag.style.width;

		    iPosicaoX = parseInt(oDivDragDrop.style.left+0,10);
		    iPosicaoY = parseInt(oDivDragDrop.style.top+0,10);
		    iMouseX   = e.clientX;
		    iMouseY   = e.clientY;

		    iMaxBottom           = self.getMaxBottom();
		    iMaxTop              = self.getMaxTop();
		    iMaxLeft             = self.getMaxLeft();
		    iMaxRight            = self.getMaxRight();
		    document.onmousemove = doDrag;
		  }
	  }
	};

  /**
   * Termina o Drag and Drop da Janela
   * @private
   */
  this.endDrag = function(){

	  document.body.style.cursor  = "default";
	  if (isdrag) {
		  objectDrag.style.top        = oDivDragDrop.style.top;
		  objectDrag.style.left       = oDivDragDrop.style.left;
		  objectDrag.style.opacity    = '';
		  var paioDivDragDrop = oDivDragDrop.parentNode;
		  if (paioDivDragDrop && paioDivDragDrop != null) {
	      paioDivDragDrop.removeChild(oDivDragDrop);
	    }
    }
	  isdrag = false;
	};

	/**
	 * seta a janela como filha de outra
	 * @param {windowAux} oWindowAuxObject instancia de windowAux
	 */
	this.setChildOf   = function (oWindowAuxObject) {

	  this.parent = oWindowAuxObject;
	  oWindowAuxObject.add(this);
	};

	/**
	 * Adiciona um Elemento a Janela
	 * @private
	 */
	this.add = function (oElement) {

	  this.aChilds.push(oElement);
	  $(idWindow).appendChild(oElement.divWindow);
  };

	this.toFront = function() {

	  if (self.aChilds.length > 0) {
	   return true;
	  }
	  /**
	   * procuramos todos os zIndex dos objetos window, e definos ele como o maior, e diminuio os outros;
	   */
	   if (!$(idWindow)) {
	    return false;
	   }
	   if ($(self.idWindow).style.zIndex == this.zIndex) {
	     return true;
	   }

	   var zIndexInicial  = 1;
	   var aWindowns      = $$('div.windowAuxTitle');
	   aWindowns.each(function(oDiv, id){

	     var parentDiv = oDiv.getAttribute('divParent');
	     if (self.parent &&  self.parent.getId() == parentDiv) {

	       oDiv.style.backgroundColor='#2C7AFE';
	     } else {

		     oDiv.style.backgroundColor = 'gray';
		     $(parentDiv).style.zIndex = zIndexInicial++;
	     }
	   });

	   self.divTitleWindow.style.backgroundColor='#2C7AFE';
	   self.divWindow.style.zIndex = this.zIndex;
	   $(idWindow).focus();
	};

  /**
   * Seta o zIndex para a windowAux
   * @param {integer} iIndex
   */
  self.setIndex = function(iIndex) {
    this.zIndex = iIndex;
  };


	 self.divTitleWindow.observe('click',function(event) {
     self.toFront();
   });

   $(idWindow).observe('click',function(event) {
     self.toFront();
   });
	/**
   * Retorna a o ponto maximo abaixo da janela
   * @private
   */
	this.getMaxBottom = function() {

	  if (!!this.parent) {
      this.iMaxBottom  = this.parent.getHeight()-(this.getHeight()+10);
    }
	  return this.iMaxBottom;
	};

	/**
   * retorna a altura maxima do viewport
   * @private
   */
	this.getMaxTop = function() {

    if (!!this.parent) {
      this.iMaxTop  = this.parent.iMaxTop+25;
    }
    return this.iMaxTop;
  };

  /**
   * retorna a altura maxima do viewport
   * @private
   */
  this.getMaxtop = function() {

    if (!!this.parent) {
      this.iMaxBottom  = this.parent.getHeight()-(this.getHeight()+10);
    }
    return this.iMaxBottom;
  };

  /**
   * retorna a maior ponto a esquerda do viewport
   * @private
   */
  this.getMaxLeft = function() {

     if (!!this.parent) {
       this.iMaxLeft = parseInt($(this.parent.idWindow).style.left+0,10) + 10;
     }
     return this.iMaxLeft;
  };

  /**
   * retorna a maior ponto a direita do viewport
   * @private
   */
  this.getMaxRight = function () {

    if (!!this.parent) {
      this.iMaxRight = parseInt($(this.parent.idWindow).style.width+0,10)- (this.iWidth+10);
    }
    return this.iMaxRight;
  };

	/**
	 * Retorna a Altura da Janela
	 *@return {integer}
	 */
	this.getHeight = function () {
	  return this.iHeight;
	};

	/**
	 * Retorna a Largura da Janela
	 * @return {integer}
	 */
	this.getWidth = function () {
	  return this.iWidth;
	};

	$(idWindow+"_title").observe("mousedown", this.initDrag);
  document.observe("mouseup",  this.endDrag);

  /**
   * Adiciona um event listener a window
   *
   * @param {string} sEvent Evento que sera 'escutado' sendo o nome do evento sem as letras 'on'
   * @param {Object} oCallBack  funcao que sera executada ao disparar o evento
   * @example
   * window1.addEvent('keydown', function(event) {
   *   this.setTitle(this.getTitle()+" - "+toCharCode(event.which));
   * });
   */
  this.addEvent = function(sEvent, oCallBack) {
    this.divWindow.observe(sEvent, oCallBack);
  };

  me.getContentContainer = function() {
    return me.divContent;
  };
};

windowAux.prototype.getElement = function() {
  return this.divWindow;
};

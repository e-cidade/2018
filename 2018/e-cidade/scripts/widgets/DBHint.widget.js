  /**
   * Esse arquivo Cria uma div semelhante a um Hint contendo textos etc;
   * 
   * @constructor
   * @author       Rafael Lopes <rafael.lopes@dbseller.com.br>
   *               Rafael Nery  <rafael.nery@dbseller.com.br>
   * @version  $Revision: 1.17 $
   * @revision $Author: dbrenan.silva $
   *
   * @param {String} sInstancia Nome da instancia do Objeto
   */
  DBHint = function(sInstancia) {

      var lUseMouse      = false;
      var zIndexHint     = null;
      var me             = this;
      var sNameInstance  = sInstancia;
      var sTexto         = "";
      var iPadding       = 5;
      var aShowEvents    = new Array("onFocus");
      var aHideEvents    = new Array("onBlur");
      var iTop           = 0;
      var iLeft          = 0;
      var oScrollElement = null;
      var iWidth         = null;
      me.sPositionTop    = 'T';
      me.sPositionLeft   = 'L';
      me.oDivContainer   = null;

      Object.defineProperty(this, "instance", {
        value: sInstancia,
        writable: false,
        configurable: false,
        enumerable: false
      });

      /**
       * Elementos do componente
       */
      var oDivContainer                       = document.createElement("DIV");
      var oDivInterna                         = document.createElement("DIV");
      /**
       * Escreve as funções no elemento
       */
      var setEvents      = function(oElemento) {
        /**
         * Percorre eventos de Exibição
         */
        for (var i = 0; i < aShowEvents.length; i++) {

          var sAttr = oElemento.getAttribute(aShowEvents[i])
            if (sAttr == null){
              sAttr = "";
            }

          oElemento.setAttribute(aShowEvents[i], sNameInstance + ".show(this, event);" + sAttr);
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

        if ( typeof(oDivInterna) == "object" ) {
          oDivInterna.innerHTML = sTexto;
        }
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
         * Define os Eventos para o elemnto mostrar/ocultar o hint
         */
        var lEventos = setEvents(oElemento);

        if ($(sNameInstance + "divDBhintExterno")) {
          $(sNameInstance + "divDBhintExterno").parentNode.removeChild($(sNameInstance + "divDBhintExterno"));
        }

        oDivContainer.id                    = sNameInstance + "divDBhintExterno";
        oDivContainer.style.position        = "absolute";
        oDivContainer.style.top             = (oElemento.offsetTop - oElemento.offsetHeight - (iPadding * 2) ) + 'px';
        oDivContainer.style.left            = (oElemento.offsetLeft) + 'px';
        oDivContainer.style.border          = '1px solid #FFDD00';
        oDivContainer.style.display         = 'none';
        oDivContainer.style.backgroundColor = '#FFFFCC';
        oDivContainer.style.zIndex          = '9999';

        if(iWidth != null) {
          oDivContainer.style.width           = iWidth + 'px';
        }

        oDivInterna.id                      = sNameInstance+"divDBhintInterno";
        oDivInterna.style.overflowX         = "hidden";
        oDivInterna.style.overflowY         = "auto";
        oDivInterna.style.padding           = iPadding + "px";

        oDivInterna.innerHTML               = sTexto;

        oDivContainer.appendChild(oDivInterna);
        document.body.appendChild(oDivContainer);
      };

      /**
       * Exibe o Hint
       */
      this.show   = function( oElemento, oEvento ) {

        var oCoordinates               = me.getElementCoordinates( oElemento );

        if ( lUseMouse ) {
          oCoordinates               = { x : oEvento.clientX, y : oEvento.clientY };
        }
        me.oDivContainer               = document.getElementById( sNameInstance + "divDBhintExterno" );
        me.oDivContainer.style.display = '';
        me.oDivContainer.style.top     = me.getCoordinatesTop ( oCoordinates, oElemento ) + "px";
        me.oDivContainer.style.left    = me.getCoordinatesLeft( oCoordinates, oElemento ) + 'px';

        if(zIndexHint != null) {
          me.oDivContainer.style.zIndex  = zIndexHint;
        }
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

            var iTop = oCoordinates.y-(me.oDivContainer.offsetHeight + oElemento.offsetHeight);
            break;

          case 'B':

            var iTop = oCoordinates.y;
            break;
        }
        if (oScrollElement != null) {

          iTop  -= oScrollElement.scrollTop;

        }
        return iTop;
      }


      this.getCoordinatesLeft = function(oCoordinates, oElemento) {

        var iLeft = 0;
        var iJanelaCliente = document.body.clientWidth;

        switch (me.sPositionLeft.toUpperCase()) {

          case 'L':

            var iLeft = oCoordinates.x;
            break;

          case 'R':

            var iLeft = oCoordinates.x + oElemento.offsetWidth;
            break;
        }

        if ((iLeft + iWidth) >= iJanelaCliente) {
          iLeft -= iWidth;
        }

        if (oScrollElement != null) {
          iLeft -= oScrollElement.scrollLeft;
        }

        return iLeft;
      }

      /**
       * Define a largura do objeto hint
       * @param iLargura - Largura em pixels
       * @return void
       */
      this.setWidth = function(iLargura) {
        iWidth = iLargura;
      }

      /**
       * Utiliza o evento do mouse para obter as coordenadas onde sera apresentado o hint
       */
      this.setUseMouse = function( lUsarMouse ) {
        lUseMouse = lUsarMouse;
      };

      this.setScrollElement = function(oElement) {
        oScrollElement = oElement;
      }
      this.setZIndexHint = function(zIx) {
        zIndexHint = zIx;
      }
    };


  /**
   * Array de instancias do DBHint
   */
  DBHint.iInstancesCounter = DBHint.iInstancesCounter || 0;

  DBHint.oInstances = DBHint.oInstances || {};

  /**
   * Construtor automático do widget dos hints.
   *
   * @param  Element oElemento Elemento do DOM que receberá o hint
   * @param  Object settings  objeto de configuração do hint
   *
   * @example
   *
   *  <!-- O atributo [db-action="hint"] é opcional -->
   *  <!-- serve para montar automaticamente o hint no elemento via DBBootstrap -->
   *  <input id="ID_DO_INPUT" db-action="hint" db-hint-text="TEXTO DO HINT" />
   *
   * @example
   *  DBHint.build($('ID_DO_INPUT'), {text: "TEXTO DO HINT"})
   *
   * @example
   *  DBHint.build($('ID_DO_INPUT')).setText("TEXTO DO HINT")
   *
   * @return DBHint retorna a instancia da classe DBHint
   */
  DBHint.build = function(oElemento, settings) {

    /**
     * Default attributes
     */
    var defaultSettings = {
      showEvents : ["onFocus"],
      hideEvents : ["onBlur"],
      text: ""
    }

    for (attr in settings)  {
      defaultSettings[attr] = settings[attr]
    }


    var sNomeInstancia = oElemento.DBHint ? oElemento.DBHint.instance : '',
        oInstancia = DBHint.oInstances[sNomeInstancia];

    if ( !oInstancia ) {

      var sNomeInstancia = 'oDBHint_' + js_strLeftPad( DBHint.iInstancesCounter++ , 3, "0");

      var oRetorno = new DBHint(sNomeInstancia);
          oRetorno.setShowEvents(defaultSettings.showEvents);
          oRetorno.setHideEvents(defaultSettings.hideEvents);
          oRetorno.make(oElemento);


      oInstancia = DBHint.oInstances[sNomeInstancia] = window[sNomeInstancia] = oRetorno
      oElemento.DBHint = oInstancia;
    }

    oInstancia.setText(defaultSettings.text || oElemento.getAttribute("db-hint-text"));

    return oInstancia;
  }

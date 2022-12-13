;(function(exports) {

  var EmissaoRelatorio = function(sURL, oParameters) {

    /**
     * Método de chamada
     * @type {String}
     */
    this.sMethod = "get";

    /**
     * Destino da chamada
     * @type {String}
     */
    this.sURL = sURL || '';

    /**
     * Parametros a serem passados
     * @type {Array}
     */
    this.aParameters = [];

    oParameters && this.setParameters(oParameters);

    /**
     * Nome da nova janela criada
     * @type {String}
     */
    this.sWindowName = "Window" + parseInt(Math.random()*1000);
  };

  EmissaoRelatorio.prototype = {

    /**
     * @param {String} sName
     * @param {String} sValue
     */
    addParameter : function(sName, sValue) {

      /**
       * Somente atualiza caso o parametro já exista
       */
      if (this.aParameters.length) {

        var aItens = this.aParameters.map(function(oParam) {
                return oParam.name;
              }),
            iIndex = aItens.indexOf(sName);

        if (iIndex != -1) {
          this.aParameters[iIndex].value = sValue;
          return this;
        }
      }

      this.aParameters.push({
          name : sName,
          value : sValue
        });

      return this;
    },

    /**
     * @param {Object} oParameters
     */
    setParameters : function(oParameters) {

      this.aParameters = [];

      if (Object.keys(oParameters).length) {
        for (var sParametro in oParameters) {
          this.addParameter(sParametro, oParameters[sParametro]);
        }
      }

      return this;
    },

    /**
     * @param {String} sMethod
     */
    setMethod : function(sMethod) {
      this.sMethod = sMethod.toLowerCase();
      return this;
    },

    /**
     * @param {String} sURL
     */
    setURL : function(sURL) {
      this.sURL = sURL;
      return this;
    },

    /**
     * @param {String} sWindowName
     */
    setWindowName : function(sWindowName) {
      this.sWindowName = sWindowName;
    },

    open : function() {

      if (this.sURL == '') {
        throw "URL não definida.";
      }

      var sURL = '';

      /**
       * Trata os parametros para enviar via GET
       */
      if (this.sMethod == "get") {

        var sParameters = this.aParameters.map(function(oParam) {
            return oParam.name + "=" + encodeURIComponent(oParam.value)
          }).join("&");

        sURL = this.sURL + (sParameters != '' ? '?' : '') + sParameters;
      }

      /**
       * Abre a Window
       */
      var oWindow = window.open(
          sURL,
          this.sWindowName,
          'width=' + (screen.availWidth-5) + ',height=' + (screen.availHeight-40) + ',scrollbars=1,location=0'
        );
      oWindow.moveTo(0,0);

      /**
       * Trata os parametros para enviar via POST
       */
      if (this.sMethod == "post") {

        var oForm = document.createElement("form");

        oForm.setAttribute("method", this.sMethod);
        oForm.setAttribute("action", this.sURL);
        oForm.setAttribute("target", this.sWindowName);
        oForm.style.display = "none";

        this.aParameters.map(function(oParam) {

          var oInput = document.createElement("input");

          oInput.name = oParam.name;
          oInput.value = oParam.value;
          oForm.appendChild(oInput);
        });

        document.body.appendChild(oForm);
        oForm.submit();
        document.body.removeChild(oForm);
      }
    }
  }

  exports.EmissaoRelatorio = EmissaoRelatorio;
})(this);
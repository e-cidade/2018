 
DBViewAlvaraDocumentos = function (sInstance, sContainer) {
  
  var me                 = this;
  var sRPC               = 'iss1_documentosAlvara.RPC.php';
  var aHeadersDocumentos = new Array('Código','Documento');
  var sIdField           = sInstance+"_fieldSet";
  var iCodigoAlvara      = null;
  this.setCodigoAlvara = function(iAlvara){
    iCodigoAlvara = iAlvara;
  }
  /**
   * Renderiza componente na tela
   */
  this.show  = function () {
      
    var oFieldSetDocumentos             = document.createElement("FIELDSET");
        oFieldSetDocumentos.id          = sIdField;
    var oLegendDocumentos               = document.createElement("LEGEND");
        oLegendDocumentos.innerHTML     = "<b>Documentos apresentados</b>";
    var oDivCtnGrid                     = document.createElement("DIV");
        oDivCtnGrid.id                  = "ctnGridDocumentos";
        
    /**
     * Escreve HTML
     */
    oFieldSetDocumentos.appendChild(oLegendDocumentos);
    oFieldSetDocumentos.appendChild(oDivCtnGrid);
    $(sContainer).appendChild(oFieldSetDocumentos);
    
    /**
     * Cria Grid
     */
    me.oGridDocumentos              = new DBGrid(sInstance+'.oGridDocumentos');
    me.oGridDocumentos.nameInstance = sInstance+'.oGridDocumentos';
    me.oGridDocumentos.sName        = 'oGridDocumentos';
    me.oGridDocumentos.setCheckbox(0);
    me.oGridDocumentos.aWidths      = new Array('10%','90%');
    me.oGridDocumentos.setCellAlign(new Array("center","left"));
    me.oGridDocumentos.setHeader(aHeadersDocumentos);
    me.oGridDocumentos.show($('ctnGridDocumentos'));
  };
  /**
   * Renderiza dados na grid
   */
  this.carregaDados  = function() {
    
    if(iCodigoAlvara != null) {
      
      var oParam                  = new Object();
      oParam.exec                 = 'getDocumentosAlvara';
      oParam.iCodigoAlvara        = iCodigoAlvara;
      me.oGridDocumentos.clearAll(true);
      
      var oAjax  = new Ajax.Request(
          sRPC,
          {method    : 'post',
          parameters : 'json='+Object.toJSON(oParam), 
          onComplete : function(oAjax) {
            
            var oRetorno = eval("("+oAjax.responseText.urlDecode()+")");
            
            if (oRetorno.iStatus == "2") {
              alert(oRetorno.sMessage);
            } else {
              for (var i = 0; i < oRetorno.aDocumentos.length; i++) {
                with (oRetorno.aDocumentos[i]) {
                  
                  var aLinha    = new Array();
                      aLinha[0] = db44_sequencial; 
                      aLinha[1] = db44_descricao; 
                      
                  var lCheck = entregue == 't' ? true : false;
                  me.oGridDocumentos.addRow(aLinha,null,false,lCheck);
                }
              }
              me.oGridDocumentos.renderRows();
            }
         }
      });
    }
  };
  /**
   * Retorna os dados selecionados da grid
   */ 
  this.getDocumentosSelecionados = function (){
    
    var aRetorno = new Array(); 
    me.oGridDocumentos.getSelection().each(function(aCampo, iIndice){
      aRetorno[iIndice] = aCampo[0];
    });
    return aRetorno;
  };
};
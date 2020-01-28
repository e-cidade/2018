function js_showVencedores(iSolicitacao, iOrcamento) {

    var oParam                    = new Object();
    oParam.exec                   = "getVencedoresRegistro";
    oParam.iSolicitacao           = iSolicitacao;
    oParam.iOrcamento             = iOrcamento;
    var oAjax   = new Ajax.Request(sUrlRPC,
                                   {
                                    method: "post",
                                    parameters:'json='+Object.toJSON(oParam),
                                    onComplete: js_retornoVencedoresRegistro
                                   });
  
  }
  
function  js_retornoVencedoresRegistro(oAjax) {
    
  var oRetorno = eval("("+oAjax.responseText+")");
  windowVencedoresRegistro = new windowAux('windowVencedoresRegistro','Vencedores', document.body.getWidth() - 100, 500);
  windowVencedoresRegistro.allowCloseWithEsc(false);
    
  var sContent  = "<fieldset>";
      sContent += "  <div id='ctnGridVencedores'>";
      sContent += "  </div>";
      sContent += "</fieldset>";
        
  windowVencedoresRegistro.setContent(sContent);
  $('windowwindowVencedoresRegistro_btnclose').onclick= function () {
    windowVencedoresRegistro.destroy();
  }
    
  /**
   * Adiciona a grid na janela
   */ 
  windowVencedoresRegistro.show(40,10);
  oGridVencedores     = new DBGrid('gridVencedores');
  oGridVencedores.nameInstance = "oGridVencedores";
  oGridVencedores.setHeight(300);
  oGridVencedores.setCellAlign(new Array("center", "Left", "Left", "left","right", "left"));
  oGridVencedores.setCellWidth(new Array("5%", "30%","25%", "30%", "20%"));
  oGridVencedores.setHeader(new Array("Trocar", "Vencedor", "Item","Compl","Vlr. Un.",'id'));
  oGridVencedores.aHeaders[5].lDisplayed = false;
  oGridVencedores.show($('ctnGridVencedores'));
  oGridVencedores.clearAll(true);
  var aItens   = oRetorno.itens;
 
  var iTotalItens = aItens.length;
  for(var i = 0; i < iTotalItens; i++) {
    
    with (aItens[i]) {
      
      var aLinha = new Array();
      var idRow  = itemorcamento+"_"+fornecedororcamento; 
      aLinha[0]  = "<a>Trocar</a>";
      aLinha[1]  = vencedor.urlDecode();
      aLinha[2]  = material.urlDecode();
      aLinha[3]  = complemento.urlDecode();
      aLinha[4]  = js_formatar(valorunitario,'f', oRetorno.iNumeroCasasDecimais);
      aLinha[5]  = idRow;
      oGridVencedores.addRow(aLinha);
    }
  }
  oGridVencedores.renderRows();
  //windowVencedoresRegistro.setChildOf(windowItensRegistro); 
}

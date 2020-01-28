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
    
  var sContent  = "<fieldset style='width:100%;'>";
      sContent += "  <div id='ctnGridVencedores' style='width:100%;'>";
      sContent += "  </div>";
      sContent += "</fieldset>";
        
  windowVencedoresRegistro.setContent(sContent);
  $('windowwindowVencedoresRegistro_btnclose').onclick= function () {
    windowVencedoresRegistro.destroy();
  }
    
  windowVencedoresRegistro.show(40,10); 
  /**
   * Adiciona a grid na janela
   */ 
  oGridVencedores     = new DBGrid('gridVencedores');
  oGridVencedores.nameInstance = "oGridVencedores";
  oGridVencedores.setHeight(300);
  oGridVencedores.setCellWidth(new Array("5%", "30%","25%", "30%", "20%"));
  oGridVencedores.setCellAlign(new Array("center", "Left", "Left", "left","right", "left"));
  oGridVencedores.setHeader(new Array("Trocar", "Vencedor", "Item","Compl","Vlr. Un.",'id'));
  oGridVencedores.aHeaders[5].lDisplayed = false;
  oGridVencedores.show($('ctnGridVencedores'));
  var aItens   = oRetorno.itens;
 
  oGridVencedores.clearAll(true);
  for(var i = 0; i < aItens.length; i++) {
    
    with (aItens[i]) {
      
      var aLinha = new Array();
      var idRow  = itemorcamento+"_"+fornecedororcamento; 
      aLinha[0]  = "<a>Trocar</a>";
      aLinha[1]  = vencedor.urlDecode();
      aLinha[2]  = material.urlDecode();
      aLinha[3]  = complemento.urlDecode();
      aLinha[4]  = js_formatar(valorunitario,'f');
      aLinha[5]  = idRow;
      oGridVencedores.addRow(aLinha);
    }
  }
  oGridVencedores.renderRows();
  //windowVencedoresRegistro.setChildOf(windowItensRegistro); 
}

 /**
   * Abre a Janela com os Movimentos
   */
  function js_showItens(iRow) {

    oGlobalRowSelected = oGridMovimentos.aRows[iRow];
    oGlobalRowSelected.setClassName("marcado");
    $(oGlobalRowSelected.sId).className = 'marcado';
    /*    
     * Criamos uma nova Janela para os itens
     */
    windowItensMovimento = new windowAux('windowItensMovimento','Itens Movimento', document.body.getWidth() - 100,500);
    windowItensMovimento.allowCloseWithEsc(false);
    var sContent  = "<fieldset style='width:100%;'>";
        sContent += "  <div id='ctnGridItens' style='width:100%;'>";
        sContent += "  </div>";
        sContent += "</fieldset>";
        sContent += "  <br><center>";
        sContent += "  </center>";
    windowItensMovimento.setContent(sContent);
    $('windowwindowItensMovimento_btnclose').onclick= function () {
      
      oGlobalRowSelected.isSelected       = false;
      $(oGlobalRowSelected.sId).className = 'normal';
      oGlobalRowSelected.setClassName("normal");
      windowItensMovimento.destroy();
      
    }
    
    var oMessageBoard = new messageBoard('msg1',
        'Itens Vinculados ao Movimento',
        '',
        $('windowwindowItensMovimento_content')
    );
    oMessageBoard.show();
    windowItensMovimento.show(40,10);
    windowItensMovimento.setChildOf(windowMovimentos);  
    /**
     * Adiciona a grid na janela
     */ 
    oGridItensMov     = new DBGrid('gridItens');
    oGridItensMov.nameInstance = "oGridItensMov";
    oGridItensMov.setHeight(300);
    oGridItensMov.setCellWidth(new Array("5%","25%",'20%',"25%","25%"));
    oGridItensMov.setCellAlign(new Array("center", "center", "Left", "left", "left", "right", "right"));
    oGridItensMov.setHeader(new Array("Codigo", "Item", "Complemento", "Fornecedor","Justificativa"));
    
    
    oGridItensMov.show($('ctnGridItens'));
    js_getItensMovimento(oGlobalRowSelected.aCells[1].getValue());  
    
  }
  
  /**
   * Consulta os itens do Movimento do Registro que o usuário selecionou
   */
  function js_getItensMovimento(iCodigoMovimentacao) {
  
    js_divCarregando('Aguarde, Carregando itens.', 'msgBox');
    var oParam          = new Object();
    oParam.exec         = "getItensMovimentosRegistro";
    oParam.iSolicitacao = iGlobalSolicitacao;
    oParam.iOrcamento   = iGlobalOrcamento;
    oParam.iCodigoMovimentacao  = iCodigoMovimentacao;
    var oAjax           = new Ajax.Request(sUrlRPC,
                                   {
                                    method: "post",
                                    parameters:'json='+Object.toJSON(oParam),
                                    onComplete: js_retornoGetItensMovimento
                                   });
  }

  /**
   * Preenche a grid com os itens que o fornecedor Cotou 
   */ 
  function js_retornoGetItensMovimento(oAjax) {
    
    js_removeObj('msgBox');
    oGridItensMov.clearAll(true);
    var oRetorno = eval("("+oAjax.responseText+")");
    var aItens   = oRetorno.itens;
    for(var i = 0; i < aItens.length; i++) {
    
      with (aItens[i]) {
      
        var aLinha = new Array();
        aLinha[0]  = pc01_codmater;
        aLinha[1]  = pc01_descrmater.urlDecode();
        aLinha[2]  = pc11_resum.urlDecode().substring(0,30).replace(/\+/g, ' ');
        aLinha[3]  = z01_nome.urlDecode();  
        aLinha[4]  = pc66_justificativa.urlDecode().substring(0,30).replace(/\+/g, ' ');
        oGridItensMov.addRow(aLinha);
        
      }
    }
    oGridItensMov.renderRows();
  }

  /**
   * Retorna todos as Desistencias Realizadas.
   *
   */
  function js_getMovimentos(iTipo, iSolicitacao, iOrcamento) {
  
    iGlobalSolicitacao = iSolicitacao; 
    iGlobalOrcamento   = iOrcamento; 
    iGlobalTipo        = iTipo; 
    js_divCarregando('Aguarde, pesquisando Movimentos', 'msgBox');
    var oParam                    = new Object();
    oParam.exec                   = "getMovimentosRegistro";
    oParam.iSolicitacao           = iGlobalSolicitacao;
    oParam.iOrcamento             = iOrcamento;
    oParam.iTipo                  = iTipo;
    var oAjax   = new Ajax.Request(sUrlRPC,
                                   {
                                    method: "post",
                                    parameters:'json='+Object.toJSON(oParam),
                                    onComplete: js_retornoGetMovimentos
                                   });
  
  }
  
  /**
   * Monta a janela com os fornecedores
   */
  function  js_retornoGetMovimentos(oAjax) {
    
    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");
    windowMovimentos = new windowAux('windowMovimentos','Movimentos', document.body.getWidth() - 50);
    windowMovimentos.allowCloseWithEsc(false);
    
    var sContent  = "<fieldset style='width:98%;'><legend><b>Movimentos</b></legend>";
        sContent += "  <div id='ctngridMovimentos' style='width:100%;'>";
        sContent += "  </div>";
        sContent += "</fieldset>";
        sContent += "  <br><center>";
        sContent += "   <input type='button' id='btnCancelarMovimento'  value='Processar'>";
        sContent += "  </center>";
        
    windowMovimentos.setContent(sContent);
    $('windowwindowMovimentos_btnclose').onclick= function () {
      windowMovimentos.destroy();
    }
    
    var sStringTipo = "Desitência";
    if (iGlobalTipo == 3) {
      sStringTipo = "Bloqueio";
    }
    var sMsg  = "Selecione os movimentos que deseja cancelar.<br>  ";
        sMsg += "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dois cliques no movimento para visualizar os itens vinculados ao movimento.";
    var oMessageBoardForne = new messageBoard('msg2',
                                         'Cancelamento de '+sStringTipo,
                                         sMsg,
                                         $('windowwindowMovimentos_content')
                                         );
    oMessageBoardForne.show();
    windowMovimentos.allowDrag(false);
    windowMovimentos.show(25,10);
    
    /**
     * Adiciona a grid na janela
     */ 
    oGridMovimentos     = new DBGrid('gridMovimentos');
    oGridMovimentos.nameInstance = "oGridMovimentos";
    oGridMovimentos.setCheckbox(1);
    oGridMovimentos.setHeight(200);
    oGridMovimentos.setCellWidth(new Array("5%","10%","75%","10%"));
    oGridMovimentos.setCellAlign(new Array("center", "Left", "Left"));
    oGridMovimentos.setHeader(new Array("Código","Data", "Usuário","Qtd. Itens"));
    oGridMovimentos.show($('ctngridMovimentos'));
    oGridMovimentos.clearAll(true);
    var aItens   = oRetorno.itens;
    for(var i = 0; i < aItens.length; i++) {
    
      with (aItens[i]) {
      
        var aLinha = new Array();
        aLinha[0]  = pc58_sequencial;
        aLinha[1]  = js_formatar(pc58_data, "d");
        aLinha[2]  = login.urlDecode();
        aLinha[3]  = qtditens;
        oGridMovimentos.addRow(aLinha);
        oGridMovimentos.aRows[i].sEvents += "onDblClick='js_showItens("+i+")'";
        
      }
    }
    
    oGridMovimentos.renderRows();
    
    $('btnCancelarMovimento').onclick= function () {js_CancelaMovimentos()};
  } 
  
  function js_CancelaMovimentos() {
  
    var aItens          = oGridMovimentos.getSelection("object");
    var oParam          = new Object();
    oParam.exec         = "CancelaMovimentos";
    oParam.iSolicitacao = iGlobalSolicitacao;
    oParam.aItens       = new Array();
  
    for (var i = 0; i < aItens.length; i++) {
  
      var oMovimento = new Object();
      oMovimento.iCodigoMovimento  = aItens[i].aCells[1].getValue()
      oParam.aItens.push(oMovimento);
      
    }
    
    js_divCarregando('Aguarde, salvando fornecedores', 'msgBox');
    var oAjax   = new Ajax.Request(sUrlRPC,
                                   {
                                    method: "post",
                                    parameters:'json='+Object.toJSON(oParam),
                                    onComplete: js_retornoCancelarMovimentos
                                   }); 
  }
  function js_retornoCancelarMovimentos(oAjax) {
  
    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    if (oRetorno.status == 1) {
      
      alert('Bloqueio efetuado com sucesso!'); 
      windowMovimentos.destroy();
      js_getMovimentos(iGlobalTipo, iGlobalSolicitacao, iGlobalOrcamento);
    } else {
      alert(oRetorno.message.urlDecode());
    }
  }

function js_setAjuda(sTexto,lShow) {

  if (lShow) {
   
    var el    =  $('gridgridItens'); 
    var x = 0;
    var y = el.offsetHeight;

    //Walk up the DOM and add up all of the offset positions.
    while (el.offsetParent && el.tagName.toUpperCase() != 'BODY')
    {
     // if (el.className != "windowAux12") { 
      
        x += el.offsetLeft;
        y += el.offsetTop;
        
     // }
      el = el.offsetParent;
    }
   x += el.offsetLeft
   y += el.offsetTop;
   $('ajudaItem').innerHTML     = sTexto;
   $('ajudaItem').style.display = '';
   $('ajudaItem').style.top     = y+"px";
   $('ajudaItem').style.left    = x+"px";
   
  } else {
   $('ajudaItem').style.display = 'none';
  }
}
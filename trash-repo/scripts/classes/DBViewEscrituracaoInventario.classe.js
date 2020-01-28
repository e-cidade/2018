/**
 * View para escrituracao de inventario
 * @param string  (nome da instancia do objeto)
 * @param integer (Codigo do Inventario)
 * @param boolean (estornar = true/false);
 * @returns {DBViewEscrituracaoInventario}
 */
DBViewEscrituracaoInventario = function (sNameInstance, iCodigoInventario, lEstornar) {
  
  var me               = this;
  me.iCodigoInventario = iCodigoInventario;
  me.sNameInstance     = sNameInstance;
  me.sRpc              = 'con4_contabilizacaoReavaliacao.RPC.php';
  me.oDBGridInventario = new DBGrid('oDBGridInventario');
  me.oWindowAux        = null;
  me.lEstorno          = lEstornar == 'true' ? true : false;
  
  me.show = function() {
    
    var sTitulo    = "Escritura��o dos Lan�amentos da Reavalia��o";
    var sAjuda     = "Demonstra��o do montante dos valores reavaliados no invent�rio, agrupados pela classifica��o cont�bil.";
    
    var sConteudo  = "<div id='ctnGridItensInventario'></div>";
        sConteudo += "<br />";
        sConteudo += "<fieldset id='ctnHistorico'>";
        sConteudo += "<legend><b>Hist�rico</b></legend>";
        sConteudo += "<textarea id='historico' rows='4' style='width:100%'></textarea>";
        sConteudo += "</fieldset>";
        sConteudo += "<br />";
        sConteudo += "<center>";
        sConteudo += "<input type='button' id='btnLancarEscrituracao' value='Escriturar' name='btnLancarEscrituracao' ";
        sConteudo += "       onclick='"+me.sNameInstance+".lancarEscrituracao();'/> ";
        sConteudo += "</center>";
        sConteudo += "<br />";
    
    me.oWindowAux = new windowAux("oWindowAux", sTitulo, 1300, 600);
    me.oWindowAux.setContent(sConteudo);
    
    me.oWindowAux.setShutDownFunction(function(){
      me.oWindowAux.destroy();
    });
    
    var oMsgBoard  = new DBMessageBoard("oMsgBoard", sTitulo, sAjuda, me.oWindowAux.getContentContainer());
    
    me.oWindowAux.show();
    oMsgBoard.show();
    me.showGrid();
  }
  
  
  this.showGrid = function () {
    
    me.oDBGridInventario.sName = 'oDBGridInventario';
    me.oDBGridInventario.setCellAlign(new Array("left", "right", "right", "right", "right", "left"));
    me.oDBGridInventario.setCellWidth(new Array("30%", "10%", "10%","10%", "10%", "30%"));
    me.oDBGridInventario.setHeader(new Array("Classifica��o", "Saldo Cont�bil", "Reavalia��o", 
                                             "Ajuste", "Valor Lan�amento", "Evento"));
    me.oDBGridInventario.aHeaders[1].lDisplayed = false;
    me.oDBGridInventario.aHeaders[3].lDisplayed = false;
    me.oDBGridInventario.setHeight(300);
    me.oDBGridInventario.show($('ctnGridItensInventario'));
    
    me.buscaDadosGrid();
  }
  
  this.buscaDadosGrid = function () {
    
    var oObject          = new Object();
    oObject.exec         = "getItensIventario";
    oObject.iCodigoInventario  = me.iCodigoInventario;
    oObject.lEstorno     = lEstornar;
    
    
    js_divCarregando('Buscando Itens do Invent�rio...','msgBox');
    var objAjax   = new Ajax.Request (me.sRpc,{
                                               method:'post',
                                               parameters:'json='+Object.toJSON(oObject), 
                                               onComplete:me.retornoDadosGrid
                                              }
                                     );
    
  }
  
  this.retornoDadosGrid = function (oJson) {
    
    js_removeObj("msgBox");  
    var oRetorno = eval("("+oJson.responseText+")");
    
    if (oRetorno.iStatus == 2) {

      alert(oRetorno.sMessage.urlDecode());
      me.oWindowAux.destroy();
      return false;
    }
    
    me.oDBGridInventario.clearAll(true);
    oRetorno.aItensInventario.each( function(oDado, id) {

      var aRow = new Array(); 
      aRow[0]  = oDado.sClassificacao.urlDecode();  
      aRow[1]  = oDado.nSaldoContabil;  
      aRow[2]  = oDado.nReavaliacao;    
      aRow[3]  = oDado.nAjuste;         
      aRow[4]  = oDado.nValorLancamento;
      aRow[5]  = oDado.sEvento.urlDecode();         
      me.oDBGridInventario.addRow(aRow);
    });
    me.oDBGridInventario.renderRows();
  };
  
  me.lancarEscrituracao = function () {
    
    if ($F('historico') == "") {
      
      alert("Preencha o campo hist�rico.");
      return false;
    }
    
    var sMensagemConfirm = "Confirma a escritura��o do invent�rio?";
    if (me.lEstorno) {
      sMensagemConfirm = "Confirma o estorno da escritura��o do invent�rio?";
    }
    
    if (!confirm(sMensagemConfirm)) {
      return false;
    }
    
    js_divCarregando('Aguarde, processando escritura��o...','msgBox');
    var oParam               = new Object();
    oParam.exec              = "processar";
    if (me.lEstorno) {
      oParam.exec            = "estornar";
    }
    
    oParam.sObservacao       = encodeURIComponent(tagString($F("historico")));
    oParam.iCodigoInventario = me.iCodigoInventario;
    
    var oAjax   = new Ajax.Request (me.sRpc,{
                                             method:'post',
                                             parameters:'json='+Object.toJSON(oParam), 
                                             onComplete: me.concluirProcessamento
                                            }
                                    );
  }
  
  me.concluirProcessamento = function(oAjax) {
    
    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");
    alert(oRetorno.sMessage.urlDecode());
    if (oRetorno.iStatus == 1) {

      me.oWindowAux.destroy();  
      $('t75_sequencial').value = "";
    }
  }
}
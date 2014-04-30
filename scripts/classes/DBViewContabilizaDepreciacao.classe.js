/**
 * View para contabilizacao da depreciacao
 * @author Matheus Felini <matheus.felini@dbseller.com.br>
 * @version $Revision: 1.7 $
 * 
 * @param string  -sNomeInstancia - Nome da Instancia do Objeto no front-end
 * @param integer - iMes - Mes da Depreciacao
 * @param integer - iAno - Ano da Depreciacao
 * @param integer - iInstituicao - Instituicao
 * @param boolean - lEstorno - caso seja estorno = true
 */
DBViewContabilizaDepreciacao = function (sNomeInstancia, iMes, iAno, iInstituicao, lEstorno) {
  
  var me                   = this;
  me.sNomeInstancia        = sNomeInstancia;
  me.oWindowAux            = null;
  me.oDataGrid             = null;
  me.sArquivoRPC           = "con4_contabilizacaodepreciacao.RPC.php";
  me.iMes                  = iMes;
  me.iAno                  = iAno;
  me.lEstorno              = lEstorno;
  me.iInstituicao          = iInstituicao;
  /**
   * Variável que controla o texto do botão
   */
  me.sLabelButton          = (lEstorno) ? "Desprocessar" : "Processar";
  
  /**
   * Metodo responsavel pela apresentacao dos dados na tela
   */
  me.show = function () {
    
    var sTituloWindow  = "Escriturar Depreciação";
    var sConteudo      = "<center>";
    sConteudo         += "  <div id='ctnGridItensDepreciacao'></div>";
    sConteudo         += "<br><input type='button' id='btnProcessarEscrituracao' value='"+me.sLabelButton+"' onclick='"+me.sNomeInstancia+".processar();'/>";
    sConteudo         += "</center>";
    me.oWindowAux = new windowAux("oWindowAux"+me.iHistoricoDepreciacao, sTituloWindow, 800, 400);
    me.oWindowAux.setContent(sConteudo);
    me.oWindowAux.show();
    
    var sTituloBoard = "Escrituração da Depreciação";
    var sAjudaBoard  = "Dados para lançamento da depreciação do período selecionado";
    var oMsgBoard = new DBMessageBoard("oMsgBoard"+me.iHistoricoDepreciacao, sTituloBoard, sAjudaBoard, me.oWindowAux.getContentContainer());
    oMsgBoard.show();
    
    if (me.oDataGrid != null) {
      me.oDataGrid = null;
    }
    
    var aHeaders   = new Array("Dados da Classificação", "Valor", "Documento");
    var aAligns    = new Array("left", "right", "left");
    var aCellWidth = new Array("45%", "10%", "42%");
    me.oDataGrid = new DBGrid("oDataGrid");
    me.oDataGrid.sName = me+".oDataGrid";
    me.oDataGrid.setHeader(aHeaders);
    me.oDataGrid.setCellAlign(aAligns);
    me.oDataGrid.setCellWidth(aCellWidth);
    me.oDataGrid.show($('ctnGridItensDepreciacao'));
    
    var oParam          = new Object();
    oParam.exec         = "getDadosSintetico";
    oParam.iMes         = me.iMes;
    oParam.iAno         = me.iAno;
    oParam.iInstituicao = me.iInstituicao;
    
    js_divCarregando("Aguarde, buscando dados...", "msgBox");
    var objAjax = new Ajax.Request (me.sArquivoRPC, {method:'post',
                                                      parameters:'json='+Object.toJSON(oParam), 
                                                      onComplete:me.preencheGrid});
    /**
     * Seta funcao de saida da windowAux
     */
    me.oWindowAux.setShutDownFunction(function() {
      me.oWindowAux.destroy();
    });
  }
  
  /**
   * Preenche a grid com os dados da depreciacao
   * @param oAjax - Retorno do Ajax
   */
  me.preencheGrid = function (oAjax) {
    
    js_removeObj("msgBox");
    var oRetorno  = eval("("+oAjax.responseText+")");
    var sMessage = oRetorno.message.urlDecode(); 
    
    if ( oRetorno.iStatus == 2 ) {
    	
    	alert(sMessage);
    	return false;
    }
    
    me.oDataGrid.clearAll(true);
    oRetorno.aDadosDepreciacao.each(function(oDado, iIndice) {
      
      var aLinha = new Array();
      aLinha[0]   = oDado.iCodigoConta + " - " + oDado.sDescricaoConta.urlDecode();
      aLinha[1]   = oDado.nValorTotal;
      aLinha[2]   = "&nbsp"+oDado.sDocumento.urlDecode();
      me.oDataGrid.addRow(aLinha);
    });
    me.oDataGrid.renderRows();
  };
  
  /**
   * Executa o processamento da rotina
   */
  me.processar = function() {
    
	var sMensagem = "Confirma a escrituração da depreciação?";
	if (lEstorno) {
      sMensagem = "Confirma o desprocessamento da escrituração?";
	}
    if (!confirm(sMensagem)) {
      return false;
    }
    
    js_divCarregando("Aguarde, escriturando depreciação...", "msgBox");
    var oParam          = new Object();
    oParam.exec         = "processar";
    if (me.lEstorno) {
      oParam.exec       = "estornar";  
    } 
    oParam.iMes         = me.iMes;
    oParam.iAno         = me.iAno;
    oParam.iInstituicao = me.iInstituicao;
    
    var objAjax   = new Ajax.Request (me.sArquivoRPC, {method:'post',
                                                      parameters:'json='+Object.toJSON(oParam), 
                                                      onComplete:me.concluirProcessamento});
      
  }
  
  /**
   * Conclui o processamento da escrituracao
   * @param oAjax
   */
  me.concluirProcessamento = function (oAjax) {
    
    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");
    
    alert(oRetorno.message.urlDecode());
    
    if (oRetorno.iStatus == 1) {
      
      me.oDataGrid = null;
      me.oWindowAux.destroy();
      
      var sQuery = '';
      if ( lEstorno ) {
        sQuery = '?estorno=true';
      }
      
      document.location.href = 'con4_contabilizacaodepreciacao001.php' + sQuery;
    }
  }
}

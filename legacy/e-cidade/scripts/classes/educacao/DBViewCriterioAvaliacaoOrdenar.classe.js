const MSG_DBVIEWCRITERIOORDENAR = "educacao.escola.DBViewCriterioAvaliacaoOrdenar."; 

DBViewCriterioAvaliacaoOrdenar = function() {

  this.oGridCriterios  = null;  
  this.oWindow         = null;
  this.aGradeCriterios = new Array();
  this.iWidth          = document.body.getWidth()/1.9;
  this.iHeight         = document.body.getHeight()/1.2;
  this.sRPCCriterio    = "edu4_criterioavaliacao.RPC.php";
  var me               = this;  
    
  this.oWindow = new windowAux("wndAlunoAvaliacao", "Ordenação - Critério de Avaliação", me.iWidth, me.iHeight );

  this.oWindow.setShutDownFunction(function () {
    me.oWindow.destroy();
  });
    
  this.oWindow.allowCloseWithEsc(true);

  var sConteudo   = "  <div id='divListaCriterios' style='text-align:center'>";
      sConteudo  += "    <fieldset style='width:95%; margin-bottom:10px;'>              \n";
      sConteudo  += "      <legend><b>Ordenação dos Critérios de Avaliação</b></legend> \n";
      sConteudo  += "    <table width='98%'>                                            \n";
      sConteudo  += "     <tr>                                                          \n";
      sConteudo  += "      <td id='cntCriterio' style='width: 100%;'>                   \n";
      sConteudo  += "      </td>                                                        \n";
      sConteudo  += "      <td>                                                         \n";
      sConteudo  += "        <input type='button' id='btnMoveUp'  value='^'>            \n";
      sConteudo  += "        <br>                                                       \n";
      sConteudo  += "        <input type='button' id='btnMoveDown' value='v'>           \n";
      sConteudo  += "      </td>                                                        \n";
      sConteudo  += "     </tr>                                                         \n";
      sConteudo  += "    </table>                                                       \n";
      sConteudo  += "    </fieldset>                                                    \n";
      sConteudo  += "  <input type='button' id='salvarReordenacao'  value='Salvar'>     \n";
      sConteudo  += "  </div>                                                           \n";
      
  this.oWindow.setContent(sConteudo);

  var sHelpMsgBox  = _M( MSG_DBVIEWCRITERIOORDENAR + "ajuste_ordenacao_com_setas" );
  
  var oMessageBoard = new DBMessageBoard('msgBoardCriterio', 
                                     'Critérios de Ordenação' ,
                                     sHelpMsgBox,
                                     this.oWindow.getContentContainer()
                                    );    
  
   /**
   * Grid dos Itinerarios
   */
  
  this.oGridCriterios              = new DBGrid("gridCriterioAvaliacao");
  this.oGridCriterios.nameInstance = 'oGridCriterios';
  this.oGridCriterios.setCellAlign(new Array("center", "left", "left", "center"));
  this.oGridCriterios.setCellWidth(new Array("5%", "60%", "20%", "5%"));
  this.oGridCriterios.setHeader(new Array("Código", "Descrição", "Abreviatura", "Ordem"));
  this.oGridCriterios.setHeight(200);
  this.oGridCriterios.aHeaders[0].lDisplayed = false;      
  this.oGridCriterios.aHeaders[3].lDisplayed = false;
  this.oGridCriterios.show($('cntCriterio'));

}

/**
 * Mostra a grid na tela e busca as avaliações
 */
DBViewCriterioAvaliacaoOrdenar.prototype.show = function () {
  var oSelf = this;
  
  this.oWindow.show();
  this.buscaCriteriosAvaliacao();
  
  $('salvarReordenacao').observe('click', function() {
    oSelf.salvarReordenacao();
  })
};

/**
 * Busca os critérios de avaliação existentes
 */
DBViewCriterioAvaliacaoOrdenar.prototype.buscaCriteriosAvaliacao = function() {

  js_divCarregando( _M( MSG_DBVIEWCRITERIOORDENAR + "buscando_criterios" ), 'msgboxA');
  var oSelf                = this;
  var oParametro           = new Object();
      oParametro.sExecucao = 'getCriteriosAvaliacao';

  var oRequest            = new Object();
      oRequest.method     = 'post';
      oRequest.parameters = 'json='+Object.toJSON(oParametro);
      oRequest.onComplete = function(oResponse) {
                                      oSelf.js_retornoCriteriosAvaliacao(oResponse, oSelf);
                                    };

  new Ajax.Request(oSelf.sRPCCriterio, oRequest);
}

/**
 * Pega os dados dos Critérios de Avaliações retornados e os adiciona a grid
 */
DBViewCriterioAvaliacaoOrdenar.prototype.js_retornoCriteriosAvaliacao = function(oResponse, oSelf) {

  js_removeObj("msgboxA");

  var oRetorno = eval('('+oResponse.responseText+')');

  var aCriterios = oRetorno.aCriterios;

  oSelf.oGridCriterios.clearAll(true);
  
  if ( aCriterios.length > 0) {
  
    aCriterios.each(function(oCriterio, iSeq) {

      var aLinha    = new Array();  
      
          aLinha[0] = oCriterio.ed338_sequencial;
          aLinha[1] = oCriterio.ed338_descricao.urlDecode();
          aLinha[2] = oCriterio.ed338_abreviatura.urlDecode();
          aLinha[3] = oCriterio.ed338_ordem;
      oSelf.oGridCriterios.addRow(aLinha);
    });

    oSelf.oGridCriterios.renderRows();
    this.oGridCriterios.enableOrderRows({btnMoveUp:$('btnMoveUp'), btnMoveDown:$('btnMoveDown')});
  }
}

/**
 * Altera a ordenação dos critérios de avaliação conforme listado na tela e os salva no banco de dados
 */
DBViewCriterioAvaliacaoOrdenar.prototype.salvarReordenacao = function() {
  
  var oSelf = this;
  
  if (!confirm( _M( MSG_DBVIEWCRITERIOORDENAR + "confirmar_nova_ordenacao" ) )) {
    return;
  }
  var aNovoCriterioAvaliacao = new Array();
  
  this.oGridCriterios.aRows.each(function(aRow, iSeq) {
    aNovoCriterioAvaliacao.push({ iCodigo : aRow.aCells[0].getValue(), iOrdem :  iSeq+1});
  })
  
  var oParametro                        = new Object();
      oParametro.sExecucao              = 'salvarReordenacaoCriterio';
      oParametro.aNovoCriterioAvaliacao = aNovoCriterioAvaliacao;

  var oRequest            = new Object();
      oRequest.method     = 'post';
      oRequest.parameters = 'json='+Object.toJSON(oParametro);
      oRequest.onComplete = function(oResponse) {
        
                                      js_removeObj("msgBoxB");
                                      var oRetorno = eval("("+oResponse.responseText+")");
                                      
                                      alert(oRetorno.sMensagem.urlDecode());
                                      oSelf.fechar();
                                    }; 
                                    
  js_divCarregando( _M( MSG_DBVIEWCRITERIOORDENAR + "reordenado_criterios"), "msgBoxB");
  new Ajax.Request(oSelf.sRPCCriterio, oRequest);
}

/**
 * Fecha a janela de Ordenação
 */
DBViewCriterioAvaliacaoOrdenar.prototype.fechar = function() {
  
  var oSelf = this;
  oSelf.oWindow.destroy();
}
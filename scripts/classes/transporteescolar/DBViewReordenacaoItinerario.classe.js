require_once('scripts/widgets/windowAux.widget.js');
require_once('scripts/widgets/dbmessageBoard.widget.js');
require_once('scripts/widgets/datagrid/plugins/DBOrderRows.plugin.js');

DBViewReordenacaoItinerario = function(sInstancia, iCodigoLinha, iTipoItinerarioLogradouro) {
  
  var me      = this;  
  var sUrlRpc = 'tre4_linhastransporte.RPC.php';
  var iWidth  = document.body.getWidth()/1.3;
  var iHeight = document.body.getHeight()/1.2;
  
  this.iTipoItinerarioLogradouro = iTipoItinerarioLogradouro;
  this.iCodigoLinha              = iCodigoLinha;
  
  this.oWindowReordenar = new windowAux("wndReordenar", "Reordenação de logradouros", iWidth, iHeight);
  
  this.oWindowReordenar.setShutDownFunction(function () {
    me.oWindowReordenar.destroy();
  });
  
  this.oCallbackSalvar = function () {
    return true;
  }
  var sConteudo  = "<div id='ctnparecer' style='text-align:center'>";
  sConteudo     += "  <fieldset id='ctnLogradouroItinerario' style='display:block'>";
  sConteudo     += "    <Legend><b>Logradouros do Itinerário</b></legend>";
  sConteudo     += "    <table width='95%'>";
  sConteudo     += "     <tr>";
  sConteudo     += "      <td id='ctnItinerarioLogradouros' style='width: 100%;'>";
  sConteudo     += "      </td>";
  sConteudo     += "      <td>";
  sConteudo     += "        <input type='button' id='btnMoveUp'  value='^'>";
  sConteudo     += "        <br>";
  sConteudo     += "        <input type='button' id='btnMoveDown' value='v'>";
  sConteudo     += "      </td>";
  sConteudo     += "     </tr>";
  sConteudo     += "    </table>";
  sConteudo     += "  </fieldset>";
  sConteudo     += "     <input type='button' id='salvarReordenacao'  value='Salvar'>";
  sConteudo     += "</div>";
  
  this.oWindowReordenar.setContent(sConteudo);
  
  this.oMessageBoard = new DBMessageBoard('msgBoardReordenacao', 
                                            'Reordenação de lougradouros do itinerário', 
                                            '',
                                             this.oWindowReordenar.getContentContainer()
                                            );
  
  /**
   * Grid dos Itinerarios
   */
  
  this.oGridItinerariosLogradouros              = new DBGrid("gridItinerariosLogradouros");
  this.oGridItinerariosLogradouros.nameInstance = 'oGridItinerariosLogradouros';
  this.oGridItinerariosLogradouros.setCellAlign(new Array("center", "left", "left", "left", "center"));
  this.oGridItinerariosLogradouros.setCellWidth(new Array("5%", "55%", "20%", "10%"));
  this.oGridItinerariosLogradouros.setHeader(new Array("Código", "Logradouro", "Bairro", "Itinerario"));
  this.oGridItinerariosLogradouros.setHeight(200);
  this.oGridItinerariosLogradouros.aHeaders[0].lDisplayed = false;      
  this.oGridItinerariosLogradouros.show($('ctnItinerarioLogradouros'));
  
};

DBViewReordenacaoItinerario.prototype.show = function () {
  var oSelf = this;
  
  this.oWindowReordenar.show();
  this.buscaLogradourosItinerarios();
  
  $('salvarReordenacao').observe('click', function() {
    oSelf.salvarReordenacao();
  })
};

DBViewReordenacaoItinerario.prototype.salvarReordenacao = function() {
  
  
  if (!confirm("Confirmar a nova ordenação da rota do itinerário?")) {
    return;
  }
  var oSelf                   = this;
  var aNovoItinerario = new Array();
  
  this.oGridItinerariosLogradouros.aRows.each(function(aRow, iSeq) {
    aNovoItinerario.push({ iCodigo : aRow.aCells[0].getValue(), iOrdem :  iSeq+1});
  })
  
  var oParametro                 = new Object();
      oParametro.sExecucao       = 'salvarReordenacaoItinerario';
      oParametro.iCodigoLinha    = oSelf.iCodigoLinha;
      oParametro.iItinerario     = oSelf.iTipoItinerarioLogradouro;
      oParametro.aNovoItinerario = aNovoItinerario;

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
      oDadosRequisicao.onComplete = function(oResponse) {
        
                                       js_removeObj("msgBox");
                                       var oRetorno = eval("("+oResponse.responseText+")");
                                       
                                       alert(oRetorno.sMensagem.urlDecode());
                                       if (oRetorno.iStatus == 1) {
                                         oSelf.oCallbackSalvar();
                                       }
                                    }; 
                                    
  js_divCarregando(_M('educacao.transporteescolar.db_frmlinhastransporte.aguardando_buscar_logradouros'), "msgBox");
  new Ajax.Request(sUrlRpc, oDadosRequisicao);
}

/**
 * Busca os logradouros vinculados ao itinerario
 */
DBViewReordenacaoItinerario.prototype.buscaLogradourosItinerarios = function() {
  
  var oSelf                   = this;
  var oParametro              = new Object();
      oParametro.sExecucao    = 'getLogradouros';
      oParametro.iCodigoLinha = oSelf.iCodigoLinha;
      oParametro.iItinerario  = oSelf.iTipoItinerarioLogradouro;

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
      oDadosRequisicao.onComplete = function(oResponse) {
                                      oSelf.retornoBuscaLogradourosItinerarios(oResponse, oSelf);
                                    }; 

  js_divCarregando(_M('educacao.transporteescolar.db_frmlinhastransporte.aguardando_buscar_logradouros'), "msgBox");
  new Ajax.Request(sUrlRpc, oDadosRequisicao);
}

/**
 * Retorno da busca pelos logradouros vinculados ao itinerario
 */
DBViewReordenacaoItinerario.prototype.retornoBuscaLogradourosItinerarios = function(oResponse, oSelf) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  oSelf.oGridItinerariosLogradouros.clearAll(true);
  
  if (oRetorno.aLogradouros.length > 0) {
  
    oRetorno.aLogradouros.each(function(oLogradouro, iSeq) {

      var aLinha    = new Array();  
      
          aLinha[0] = oLogradouro.iCodigoLinhaLogradouro;
          aLinha[1] = oLogradouro.sNomeLogradouro.urlDecode();
          aLinha[2] = oLogradouro.sBairro.urlDecode();
          aLinha[3] = "Ida";
          if (oLogradouro.iTipo == 2) {
            aLinha[3] = "Retorno";
          }
      oSelf.oGridItinerariosLogradouros.addRow(aLinha);
    });

    oSelf.oGridItinerariosLogradouros.renderRows();
    this.oGridItinerariosLogradouros.enableOrderRows({btnMoveUp:$('btnMoveUp'), btnMoveDown:$('btnMoveDown')});
  }
}

/**
 * Fecha a janela de Ordenação de Itinerários
 */
DBViewReordenacaoItinerario.prototype.fechar  = function() {
  
  var oSelf = this;
  oSelf.oWindowReordenar.destroy();
  
}

/**
 * Define uma função de callback apos os dados serem salvos com sucesso.
 * @param fFunction funcao para ser executada
 */
DBViewReordenacaoItinerario.prototype.setCallbackSalvar = function(fFunction) {
  this.oCallbackSalvar = fFunction;
}
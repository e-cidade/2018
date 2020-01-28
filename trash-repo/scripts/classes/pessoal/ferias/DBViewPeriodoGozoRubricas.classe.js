require_once('scripts/widgets/windowAux.widget.js');
require_once('scripts/widgets/dbmessageBoard.widget.js');

var DBViewPeriodoGozoRubricas = function( iCodigoPeriodoGozo, oPeriodoGozoFerias ) {
	
  var oSelf                         = this;
  this.oComponentes                 = {};
  this.iCodigoPeriodoGozo     		= iCodigoPeriodoGozo;
  this.oPeriodoGozoFerias           = oPeriodoGozoFerias;
  this.oDivConteudo                 = document.createElement("div");  
  this.oDivConteudo.className       = "container-window-aux";
  this.oDivGrid                     = document.createElement("div");
  this.sCaminhoMensagem             = 'recursoshumanos.pessoal.DBViewPeriodoGozoRubricas.';
  DBViewPeriodoGozoRubricas.sUrlRPC = 'pes2_consultaferias.RPC.php';
  this.oBotaoFechar                 = document.createElement("input");
  this.oBotaoFechar.value           = "Fechar";
  this.oBotaoFechar.type            = "button";
  this.oBotaoFechar.onclick         = function() {
    oSelf.oComponentes.oWindowAux.destroy();
  }
  return this;
}

DBViewPeriodoGozoRubricas.prototype.criarJanela = function( ) {

  if ( $('PeriodoGozoRubricas') ) {
    $('PeriodoGozoRubricas').outerHTML = ''; //Self Destroy
  }
  this.oComponentes.oWindowAux    =  new windowAux( 'PeriodoGozoRubricas', _M( this.sCaminhoMensagem+'titulo_janela_periodogozorubricas' ), 600, 400 );
  this.oComponentes.oWindowAux.setContent( this.oDivConteudo );
  this.oComponentes.oWindowAux.setChildOf(this.oPeriodoGozoFerias.getWindowAux());
  this.oComponentes.oWindowAux.show(25,25);

  this.oComponentes.oMessageBoard = new DBMessageBoard( null, 
                                                        _M( this.sCaminhoMensagem+'titulo_messageBoard' ), 
                                                        _M( this.sCaminhoMensagem+'mensagem_messageBoard' ), 
                                                        this.oDivConteudo );
  this.oComponentes.oMessageBoard.show();

  /**
   * Preparativos para a Grid
   */
  var oFieldSetGrid     = document.createElement('fieldset');
  var oLegendGrid       = document.createElement('legend');
  oLegendGrid.innerHTML = _M( this.sCaminhoMensagem+'titulo_fieldset_janela_periodogozorubricas' );
  oFieldSetGrid    .appendChild( oLegendGrid   );
  this.oDivConteudo.appendChild( oFieldSetGrid );
  oFieldSetGrid    .appendChild( this.oDivGrid );
  this.oDivConteudo.appendChild( oFieldSetGrid );
  this.oDivConteudo.appendChild( this.oBotaoFechar );
  
  this.oComponentes.oDataGrid                 = new DBGrid('GridPeriodosGozoRubricas');
  this.oComponentes.oDataGrid.nameInstance    = 'oGridPeriodosGozoRubricas'; //@TODO -
  this.oComponentes.oDataGrid.setHeight(200);
  this.oComponentes.oDataGrid.setCellAlign(["center", 
                                            "left", 
                                            "center", 
                                            "center"]);
  this.oComponentes.oDataGrid.setHeader(   ["Código", 
                                            "Rubrica", 
                                            "Quantidade", 
                                            "Valor"]);
  this.oComponentes.oDataGrid.aWidths = ['15%', 
                                         '55%', 
                                         '15%', 
                                         '15%'];
  this.oComponentes.oDataGrid.show( this.oDivGrid );
  this.oComponentes.oDataGrid.clearAll(true);
  return;
};

DBViewPeriodoGozoRubricas.prototype.show = function() {
  
  if ( this.iCodigoPeriodoGozo == null ) {
    return;
  }
  this.criarJanela();
  this.carregarDadosGrid();
  return;
};

/**
 * Carrega os Dados no DataGrid
 */
DBViewPeriodoGozoRubricas.prototype.carregarDadosGrid = function() {

  var oSelf                            = this;
  var oParametros                      = new Object();
  var oDadosRequisicao                 = new Object();
  
  oParametros.sExecucao                = 'getPeriodosGozoRubricas';
  oParametros.iCodigoPeriodoGozo 	   = this.iCodigoPeriodoGozo;

  oDadosRequisicao.method              = 'post';
  oDadosRequisicao.asynchronous        = false;
  oDadosRequisicao.parameters          = 'json='+Object.toJSON(oParametros);
  oDadosRequisicao.onComplete          = function(oAjax){
    
    var oRetorno = eval("("+oAjax.responseText+")");

    if (oRetorno.iStatus == "2") {
    
      alert(oRetorno.sMensagem.urlDecode());
      return;
    }
    
    for(var iPeriodo=0; iPeriodo < oRetorno.aRegistrosPonto.length; iPeriodo++ ){
    
      var oDadosRegistroPonto = oRetorno.aRegistrosPonto[iPeriodo];
    
      oSelf.oComponentes.oDataGrid.addRow([
        oDadosRegistroPonto.sRubrica,
        oDadosRegistroPonto.sDescricaoRubrica.urlDecode(),
        js_formatar(oDadosRegistroPonto.nQuantidade, "f"),
        js_formatar(oDadosRegistroPonto.nValor     , "f")
      ]);
    
    }
    
    oSelf.oComponentes.oDataGrid.renderRows();
  };

  var oAjax  = new Ajax.Request( DBViewPeriodoGozoRubricas.sUrlRPC, oDadosRequisicao );
};

DBViewPeriodoGozoRubricas.oInstance   = null;

DBViewPeriodoGozoRubricas.getInstance = function( iCodigoPeriodoGozo, oPeriodoGozoFerias ) {

  DBViewPeriodoGozoRubricas.oInstance = new DBViewPeriodoGozoRubricas( iCodigoPeriodoGozo, oPeriodoGozoFerias );
  return DBViewPeriodoGozoRubricas.oInstance;
}

DBViewPeriodoGozoRubricas.prototype.getWindowAux = function(  ) {
  return this.oComponentes.oWindowAux;
}

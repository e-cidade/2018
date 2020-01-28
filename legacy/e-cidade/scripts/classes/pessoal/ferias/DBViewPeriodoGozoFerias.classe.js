require_once('scripts/widgets/windowAux.widget.js');
require_once('scripts/widgets/dbmessageBoard.widget.js');

var DBViewPeriodoGozoFerias = function( iCodigoPeriodoAquisitivo ) {

  var oSelf                         = this;
  this.oComponentes                 = {};
  this.iCodigoPeriodoAquisitivo     = iCodigoPeriodoAquisitivo;
  this.oDivConteudo                 = document.createElement("div");  
  this.oDivConteudo.className       = "container-window-aux";  
  this.oDivGrid                     = document.createElement("div");  
  this.sCaminhoMensagem             = 'recursoshumanos.pessoal.DBViewPeriodoGozoFerias.';
  DBViewPeriodoGozoFerias.sUrlRPC   = 'pes2_consultaferias.RPC.php';
  this.oBotaoFechar                 = document.createElement("input");
  this.oBotaoFechar.value           = "Fechar";
  this.oBotaoFechar.type            = "button";
  this.oBotaoFechar.onclick         = function() {
    oSelf.oComponentes.oWindowAux.destroy();
  }
  return this;
}

DBViewPeriodoGozoFerias.prototype.criarJanela = function( ) {

  if ( $('PeriodoGozo') ) {
    $('PeriodoGozo').outerHTML = ''; //Self Destroy
  }
  this.oComponentes.oWindowAux    =  new windowAux( 'PeriodoGozo', _M( this.sCaminhoMensagem+'titulo_janela_periodogozo' ), 700, 450 );
  this.oComponentes.oWindowAux.setContent( this.oDivConteudo );
  this.oComponentes.oWindowAux.show(100,200);

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
  oLegendGrid.innerHTML = _M( this.sCaminhoMensagem+'titulo_fieldset_janela_periodogozo' );
  oFieldSetGrid    .appendChild( oLegendGrid   );
  this.oDivConteudo.appendChild( oFieldSetGrid );
  oFieldSetGrid    .appendChild( this.oDivGrid );
  this.oDivConteudo.appendChild( oFieldSetGrid );
  this.oDivConteudo.appendChild( this.oBotaoFechar );

  this.oComponentes.oDataGrid                 = new DBGrid('GridPeriodosGozo');
  this.oComponentes.oDataGrid.nameInstance    = 'oGridPeriodosGozo'; //@TODO - 
  this.oComponentes.oDataGrid.setHeight(200);
  this.oComponentes.oDataGrid.setCellAlign(["center", 
                                            "center", 
                                            "center", 
                                            "center", 
                                            "center", 
                                            "center", 
                                            "center"]);
  this.oComponentes.oDataGrid.setHeader(   ["Período Inicial", 
                                            "Período Final", 
                                            "Ano/Mês <br>Pagamento", 
                                            "Folha", 
                                            "Somente 1/3", 
                                            "Dias Gozados", 
                                            "Dias Abonados"]);
  this.oComponentes.oDataGrid.aWidths = ['15%', 
                                         '15%', 
                                         '13%', 
                                         '15%', 
                                         '12%', 
                                         '15%', 
                                         '15%'];
  this.oComponentes.oDataGrid.show( this.oDivGrid );
  this.oComponentes.oDataGrid.clearAll(true);
  return;
};

DBViewPeriodoGozoFerias.prototype.show = function() {
  
  if ( this.iCodigoPeriodoAquisitivo == null ) {
    return;
  }
  this.criarJanela();
  this.carregarDadosGrid();
  return;
};

/**
 * Carrega os Dados no DataGrid
 */
DBViewPeriodoGozoFerias.prototype.carregarDadosGrid = function() {

  var oSelf                            = this;
  var oParametros                      = new Object();
  var oDadosRequisicao                 = new Object();
  
  oParametros.sExecucao                = 'getPeriodosGozoServidor';
  oParametros.iCodigoPeriodoAquisitivo = this.iCodigoPeriodoAquisitivo;

  oDadosRequisicao.method              = 'post';
  oDadosRequisicao.asynchronous        = false;
  oDadosRequisicao.parameters          = 'json='+Object.toJSON(oParametros);
  oDadosRequisicao.onComplete          = function(oAjax){
    
    var oRetorno = eval("("+oAjax.responseText+")");

    if (oRetorno.iStatus == "2") {
    
      alert(oRetorno.sMensagem.urlDecode());
      return;
    }
    
    for(var iPeriodo=0; iPeriodo < oRetorno.aPeriodosGozo.length; iPeriodo++ ){
    
      var oDadosPeriodos = oRetorno.aPeriodosGozo[iPeriodo];
    
      oSelf.oComponentes.oDataGrid.addRow([
    
        oDadosPeriodos.dPeriodoInicial,
        oDadosPeriodos.dPeriodoFinal,
        oDadosPeriodos.sAnoMes,
        '',
        oDadosPeriodos.sPagaterco,
        new String(oDadosPeriodos.iDiasGozados),
        new String(oDadosPeriodos.iDiasAbono)
      ]);
    
    }
    
    oSelf.oComponentes.oDataGrid.renderRows();
    
    for(var iPeriodo=0; iPeriodo < oRetorno.aPeriodosGozo.length; iPeriodo++ ){
    	
      var oDadosPeriodos = oRetorno.aPeriodosGozo[iPeriodo];
      var oLinha         = oSelf.oComponentes.oDataGrid.aRows[iPeriodo];
      var oCelula        = $(oLinha.aCells[3].sId);
      var oLink          = document.createElement('a');
      oLink.href         = "#";
      oLink.innerHTML    = oDadosPeriodos.sFolha;
      oLink.rel          = oDadosPeriodos.iCodigoPeriodoGozo;
      oLink.onclick      = function() {
        var oListaRubricas = DBViewPeriodoGozoRubricas.getInstance(this.rel, oSelf);
        oListaRubricas.show();
      }
      oCelula.appendChild(oLink);
    }
    
  };

  var oAjax  = new Ajax.Request( DBViewPeriodoGozoFerias.sUrlRPC, oDadosRequisicao );
};

DBViewPeriodoGozoFerias.oInstance = null;

DBViewPeriodoGozoFerias.getInstance = function( iCodigoPeriodoAquisitivo ) {

DBViewPeriodoGozoFerias.oInstance = new DBViewPeriodoGozoFerias( iCodigoPeriodoAquisitivo );
  return DBViewPeriodoGozoFerias.oInstance;
}

DBViewPeriodoGozoFerias.prototype.getWindowAux = function(  ) {
  return this.oComponentes.oWindowAux;
}

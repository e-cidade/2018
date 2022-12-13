/**
 * @fileoverview Plugin para adicionar hint na celula da Grid
 * @dependency Utiliza o DBHint.widgets.js e datagrid.widgets.js
 * 
 * @autor Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.3 $
 * 
 * @param iLine       Linha da grid a ser aplicado
 * @param iCell       Numero da celula a ser aplicado
 * @param sMessage    Mensagem que aparecera dentro do Hint
 * @param oParametros Este parametro nao eh obrigatorio, nele podemos modificar as configuracoes do 
 * @example           Hint atraves de parametros pre-defidos. Por exemplo podemos definir:
 *       {iWidth:'350', aShowEvents : ['','',''], aHidenEvents : ['', ''], oPosition : {sVertical : 'B', sHorizontal : 'L'} }
 *        
 *        OBSERVACAO: 
 *        Para este plugin funcionar a grid ja deve ter as linhas renderizada (apos executar Grid.renderRows())
 *        
 *        Exemplo de implementacao:
 *        
 *        oRetorno.aRegrasLancamento.each(function (oRegra, iLinha) {
 *          var aLinha = new Array();
 *          aLinha[0]  = oRegra.c47_seqtranslr;
 *          aLinha[1]  = oRegra.sDebito; 
 *          aLinha[2]  = oRegra.sCredito;
 *          
 *          oDBGridContas.addRow(aLinha);
 *        });
 *        oDBGridContas.renderRows();
 *        
 *        oRetorno.aRegrasLancamento.each(function (oRegra, iLinha) {
 *         
 *         oParametros = {iWidth:'150', oPosition : {sVertical : 'T', sHorizontal : 'L'}};
 *         oDBGridContas.setHint(iLinha, 1, oRegra.sDebito,  oParametros);
 *         // ou sem passar parametros
 *         oDBGridContas.setHint(iLinha, 2, oRegra.sCredito.sCredito);
 *       });
 *        
 */
DBGrid.prototype.setHint = function(iLine, iCell, sMessage, oParameters) {
	
  require_once('scripts/widgets/DBHint.widget.js');
  
  var sIdCelulaDestino = this.aRows[iLine].sId;
  if( iCell !== null){
	  sIdCelulaDestino = this.aRows[iLine].aCells[iCell].sId;
  }
  var parent           = this;
  parent.oHint         = eval("oDBHint_"+sIdCelulaDestino+" = new DBHint('oDBHint_"+sIdCelulaDestino+"')");
  
  /**
   * Configuracao padrao do Hint
   */
	parent.oHint.setText(sMessage)
	parent.oHint.setWidth(350);
	parent.oHint.setShowEvents(['onmouseover']);
	parent.oHint.setHideEvents(['onmouseout']);
	parent.oHint.setPosition('B', 'L');
 
	/**
	 * Configuracao personalizada 
	 */
	if (oParameters) {
	  
    if (oParameters.iWidth ) {
      parent.oHint.setWidth(oParameters.iWidth);
    }
    
	  if (oParameters.aShowEvents && oParameters.aShowEvents.length > 0) {
	    parent.oHint.setShowEvents(oParameters.aShowEvents);
	  }
    
	  if (oParameters.aHidenEvents && oParameters.aHidenEvents.length > 0) {
      parent.oHint.setHideEvents(oParameters.aHidenEvents);
	  }
    
	  
	  if (oParameters.oPosition) {
	  
	  	var sVertical   = 'B';
	  	var sHorizontal = 'L';
	  	if (oParameters.oPosition.sVertical) {
	  	  sVertical = oParameters.oPosition.sVertical;
	  	}
	  	if (oParameters.oPosition.sHorizontal) {
	  	  sHorizontal = oParameters.oPosition.sHorizontal;
	  	}
      parent.oHint.setPosition(sVertical, sHorizontal);
	  }
	}
  parent.oHint.setScrollElement($("body-container-"+this.sName));
  parent.oHint.make($(sIdCelulaDestino));
  
}


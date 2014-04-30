/**
 * @fileoverview Plugin para reordenacao de Linhas da Grid
 * 
 * @autor Iuri Guncthnigg <iuri@dbseller.com.br>
 * @autor Maurício Costa <mauricio@dbseller.com.br>
 * @version $Revision: 1.1 $
 */ 
const DATAGRID_MOVE_ROW_UP   = 1;
const DATAGRID_MOVE_ROW_DOWN = 2;

/**
 * Habilita a ordenacao de linhas de uma datagrid
 * Deve ser chamado apos o renderRow
 * @param Object oOptions option com as propriedades para ordenacao
 * @example
 * oGrid = new DBGrid('teste');
 * oGrid.show($('teste'));
 * oGrid.enableOrderRows({
 *                        btnMoveUp:$('elemento_do_Botao_mover_cima'),
 *                        btnMoveDown:$('elemento_do_Botao_mover_baixo')
 *                       }
 */
DBGrid.prototype.enableOrderRows = function(oOptions) {
  
  var oSelf        = this;
  this.oOtions     = oOptions;
  this.btnMoveUp   = null;
  this.bntMoveDown = null;
  
  if (oOptions.btnMoveUp) {

    this.btnMoveUp = oOptions.btnMoveUp;
    this.btnMoveUp.observe('click', function() {
      oSelf.moveRow(DATAGRID_MOVE_ROW_UP);
    });
  }
  
  if (oOptions.btnMoveDown) {

    this.btnMoveDown = oOptions.btnMoveDown;
    this.btnMoveDown.observe('click', function() {
      oSelf.moveRow(DATAGRID_MOVE_ROW_DOWN);
    });
  }
  
  this.oElementSelected = null;
  this.setBehaviorsOrderRows();
};

/**
 * Define os comportamentos da ordenacao
 * @private
 */
DBGrid.prototype.setBehaviorsOrderRows = function() {
  
  var oSelf = this;
  var aRows = oSelf.aRows;
  aRows.each(function(oRow, iSeq) {
    
    oRow.getElement().onclick = function () {

      if (oSelf.oElementSelected != null) {
        
        oSelf.oElementSelected.setClassName('normal');
        oSelf.oElementSelected.getElement().removeClassName('marcado');
        /**
         * Caso for o mesmo elemento marcado, desmarcamos o mesmo e saimos da rotina 
         */
        if (oSelf.oElementSelected.sId == oRow.sId) {

          oSelf.oElementSelected = null;
          return;
        }
      }
      oSelf.oElementSelected = oRow;
      oSelf.oElementSelected.setClassName('marcado');
      
      this.removeClassName('normal');
      this.addClassName('marcado');
    }
  });
  
};

/**
 * 
 * Move uma linha da grid em direcao informada (DATAGRID_MOVE_ROW_UP,DATAGRID_MOVE_ROW_DOWN);
 * @param integer  iDirection Direção em que a linha sera Movida DATAGRID_MOVE_ROW_UP, DATAGRID_MOVE_ROW_DOWN
 */
DBGrid.prototype.moveRow = function(iDirection) {

  var oSelf       = this;
  var iLinhaAtual = null;
  
  if (oSelf.oElementSelected == null) {
    return ;
  }
  oSelf.aRows.each(function(oRow, iSeq) {
 
    if (oRow.sId == oSelf.oElementSelected.sId) {
      
      iLinhaAtual = iSeq;
      return;
    }
  });
  
  var iLinhaLimite     = 0;
  var iLinhaSubstituir = iLinhaAtual -1;
  
  if (iDirection == DATAGRID_MOVE_ROW_DOWN) {
    
    iLinhaLimite     = oSelf.aRows.length-1;
    iLinhaSubstituir = iLinhaAtual + 1;
  }
  
  if (iLinhaAtual == iLinhaLimite) {
    return;
  }
  
  oSelf.aRows.splice(iLinhaAtual, 1, oSelf.aRows[iLinhaSubstituir]);
  oSelf.aRows.splice(iLinhaSubstituir , 1, oSelf.oElementSelected);
  
  this.renderRows();
  this.setBehaviorsOrderRows();
}
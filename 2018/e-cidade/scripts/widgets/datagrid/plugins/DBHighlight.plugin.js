/**
 * @fileoverview Plugin para adicionar Highlight na linha da grid
 * @dependency Utiliza datagrid.widgets.js
 *
 * @autor Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.1 $
 *
 * @example
 *        OBSERVACAO:
 *        Para este plugin funcionar a grid ja deve ter as linhas renderizada (apos executar Grid.renderRows())
 *
 *        Exemplo de implementacao:
 *
 *        oGrid.renderRows();
 *        oGrid.setHighlight();
 */

sinalizarLinhaGrid = function (oObjeto, lPintar) {

  var oLinha = oObjeto;
  if (oObjeto.nodeName == 'TR') {
    oLinha = oObjeto;
  }

  if (oObjeto.nodeName == 'INPUT') {
    oLinha = oObjeto.parentNode.parentNode;
  }

  var sCor      = 'white';
  var sCorFonte = 'black';

  if( oObjeto.hasClassName( 'disabled' ) ) {

    sCor      = '#F7F5F1';
    sCorFonte = '#BCB1A2';
  }

  if (lPintar) {
    sCor       = 'rgb(240, 240, 240)';
  }

  oLinha.style.backgroundColor = sCor;
  oLinha.style.color           = sCorFonte;
};


DBGrid.prototype.setHighlight = function ( ) {

  var oSelf = this;
  this.aRows.each( function(oLinha, iLinha) {

    $(oSelf.aRows[iLinha].sId).addEventListener('mouseover', function () {
      sinalizarLinhaGrid(this, true);
    });

    $(oSelf.aRows[iLinha].sId).addEventListener('mouseout', function () {
      sinalizarLinhaGrid(this, false);
    });
  });

}
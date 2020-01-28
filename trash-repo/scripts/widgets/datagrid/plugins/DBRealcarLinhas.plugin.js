/**
 * @fileoverview Plugin para realçar/grifar a linha da da Grid
 * @dependency Utiliza datagrid.widgets.js
 * 
 * @autor Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.1 $
 * @example  
 *        OBSERVACAO: 
 *        Para este plugin funcionar a grid ja deve ter as linhas renderizada (apos executar Grid.renderRows())
 *        
 *        Exemplo de implementacao:
 *        oDBGrid.renderRows();
 *        oDBGrid.realcarLinhas();
 */
DBGrid.prototype.realcarLinhas = function () {

  var oSelf = this;
  
  this.aRows.each(function (oObject, iLinha) {

    $(oSelf.aRows[iLinha].sId).onmouseover = function () {
      oSelf.sinalizarLinha(this, true); 
    };
    $(oSelf.aRows[iLinha].sId).onmouseout = function () {
      oSelf.sinalizarLinha(this, false); 
    };
  });
};

DBGrid.prototype.sinalizarLinha = function (oObjeto, lPintar) {
  
  if (oObjeto.nodeName == 'TR') {
    oLinha = oObjeto;
  }
  if (oObjeto.nodeName == 'INPUT') {
    oLinha = oObjeto.parentNode.parentNode;
  }
  var sCor      = 'white';
  var sCorFonte = 'black';
  if (lPintar) {
    sCor       = 'rgb(240, 240, 240)';
  }   
  oLinha.style.backgroundColor = sCor;
  oLinha.style.color           = sCorFonte;
};


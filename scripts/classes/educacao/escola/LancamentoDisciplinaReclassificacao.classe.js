require_once("scripts/classes/educacao/DBViewFormularioEducacao.classe.js");
require_once("scripts/widgets/DBLancador.widget.js");

DBViewFormularioEducacao.LancamentoDisciplinaReclassificacao = function( sName ) {
  
  DBLancador.call(this, sName);
  DBViewFormularioEducacao.LancamentoDisciplinaReclassificacao.oCamposResultadoAvaliacao = {};
};
/**
 * Estendendo classe do DBLancador
 */
DBViewFormularioEducacao.LancamentoDisciplinaReclassificacao.prototype             = Object.create(DBLancador.prototype);
DBViewFormularioEducacao.LancamentoDisciplinaReclassificacao.prototype.constructor = DBViewFormularioEducacao.LancamentoDisciplinaReclassificacao;

DBViewFormularioEducacao.LancamentoDisciplinaReclassificacao.prototype.criaGridLancador = function() {

  this.oGridLancador              = new DBGrid(this.getNomeInstancia() +'Lancador');
  this.oGridLancador.nameInstance = this.getNomeInstancia() + '.oGridLancador';
  this.oGridLancador.setCellWidth(['10%', '35%','40%', '15%']);
  this.oGridLancador.setCellAlign(['center', 'left', 'left', 'center']);
  this.oGridLancador.setHeader(['Código','Descrição', 'Avaliação', 'Ação']);
  this.oGridLancador.setHeight(this.iGridHeight);
  this.oGridLancador.show( this.oElementos.oDivGrid );
  this.oGridLancador.clearAll(true);

  return this.oGridLancador;
};


DBViewFormularioEducacao.LancamentoDisciplinaReclassificacao.prototype.renderizarRegistros = function() {

  var me            = this;
  var aCelulasBotao = new Array();
  var oDataGrid     = this.oGridLancador;
  oDataGrid.clearAll(true);
  var oRegistros    = this.getRegistros(true);
  /**
   * percorremos os registros criando as linhas da grid
   * sem o botao de remover
   */
  var iIndiceLinhaGrid = 0;

  for ( var sIdRegistro in oRegistros ) {

    var oRegistro = oRegistros[sIdRegistro];
    

    var oBotaoRemover = document.createElement('input');
    oBotaoRemover.type  = 'button';
    oBotaoRemover.value = 'Remover';


    if (!me.lHabilitado) {
      oBotaoRemover.disabled = true;	
    } else {
      oBotaoRemover.setAttribute("onClick", this.getNomeInstancia() + ".removerRegistro( '" + oRegistro.sCodigo + "' )");
    }        

    oDataGrid.addRow([oRegistro.sCodigo, oRegistro.sDescricao, '', '']);

    var oDadosAcao           = new Object();
    oDadosAcao.sIdValor      = oDataGrid.aRows[iIndiceLinhaGrid].aCells[2].sId;
    oDadosAcao.sIdCelulaGrid = oDataGrid.aRows[iIndiceLinhaGrid].aCells[3].sId;
    oDadosAcao.sCodigo       = oRegistro.sCodigo;
    oDadosAcao.oBotaoRemover = oBotaoRemover;
    aCelulasBotao.push(oDadosAcao);
    iIndiceLinhaGrid++;
  }

  oDataGrid.renderRows();

  /*
   * com a grid renderizada , percorremos os botao remover criados
   * para que sejam adicionados
   */
  for (var iDadosAcao = 0; iDadosAcao < aCelulasBotao.length; iDadosAcao++) {

    var oCamposResultadoAvaliacao = DBViewFormularioEducacao.LancamentoDisciplinaReclassificacao.oCamposResultadoAvaliacao;
    var oDadosAcao                = aCelulasBotao[iDadosAcao];
    var sIndice                   = oDadosAcao.sCodigo;
    var oCelulaGrid               = document.getElementById(oDadosAcao.sIdCelulaGrid);
    var oCelulaValor              = document.getElementById(oDadosAcao.sIdValor);
    var oInput                    = document.createElement('input');
    oInput.className              = "field-size-max";
    oInput.type                   = "text";
    
    if ( !oCamposResultadoAvaliacao[sIndice] ) {
      oCamposResultadoAvaliacao[sIndice] = oInput;
    } else {
      oInput = oCamposResultadoAvaliacao[sIndice];
    }

    oCelulaGrid.appendChild(oDadosAcao.oBotaoRemover);
    oCelulaValor.appendChild(oInput); 
  }
  return true;
};

DBViewFormularioEducacao.LancamentoDisciplinaReclassificacao.oCamposResultadoAvaliacao = {};

/**
 * Retorna os registros lançados na grid
 * @returns {Array}
 */
DBViewFormularioEducacao.LancamentoDisciplinaReclassificacao.prototype.getDisciplinas = function () {
  
  
  var aRetorno   = new Array();
  var iRegistros = this.oGridLancador.aRows.length;
  
  for (var i = 0; i < iRegistros; i++) {
    
    var sAvaliacao = $(this.oGridLancador.aRows[i].aCells[2].sId).getElementsByTagName('input')[0].value;
    
    if (sAvaliacao == "") {
      continue;
    }
    var oRetorno               = {};
    oRetorno.iCodigoDisciplina = this.oGridLancador.aRows[i].aCells[0].content;
    oRetorno.sAvaliacao        = sAvaliacao;
    
    aRetorno.push(oRetorno);
  }
  return aRetorno;
};
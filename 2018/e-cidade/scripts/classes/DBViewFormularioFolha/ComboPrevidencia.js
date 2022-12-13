
require_once('scripts/classes/DBViewFormularioFolha/DBViewFormularioFolha.classe.js');
require_once('scripts/widgets/dbcomboBox.widget.js');

/**
 * Cria um ComboBox de Previdencia
 * Realiza a busca das previdencias dinamicamente.
 * @returns DBCombobox
 */
DBViewFormularioFolha.ComboPrevidencia = function() {

  var oDBComboBox =  new DBComboBox('Previdencia', 
                                     null, 
                                     []);
 
  
  var oParam = {
	sExecucao: 'BuscaPrevidencia'	  
  }
  
  
  var oDadosRequisicao = {
	method      : 'post', 
    parameters  : 'json='+Object.toJSON(oParam),
    asynchronous: false,
    onComplete  : function( oRespostaAjax ) {

      var oRetorno = eval("(" + oRespostaAjax.responseText + ")");
      
      if ( oRetorno.iStatus == 2 ) {
        throw oRetorno.sMensagem;
      }
      
      for ( var iPrevidencia = 0; iPrevidencia < oRetorno.aPrevidencias.length; iPrevidencia++ ) {
        
        var oDadosPrevidencia = oRetorno.aPrevidencias[iPrevidencia];
        oDBComboBox.addItem(oDadosPrevidencia.r33_codtab, oDadosPrevidencia.r33_codtab + " - " + oDadosPrevidencia.r33_nome.urlDecode());
      }
    }
  }

  var oPrevidencia = new Ajax.Request('pes4_formularioFolha.RPC.php',oDadosRequisicao);

  return oDBComboBox;
};

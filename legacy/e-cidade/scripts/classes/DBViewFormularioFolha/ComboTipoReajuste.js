require_once('scripts/widgets/dbcomboBox.widget.js');
require_once('scripts/classes/DBViewFormularioFolha/DBViewFormularioFolha.classe.js');
require_once('scripts/AjaxRequest.js');

/**
 * Cria um ComboBox de Tipo de Reajuste com 
 * as opções 'Real', 'Paridade'
 *
 * @return DBComboBox
 */
DBViewFormularioFolha.ComboTipoReajuste = function(){

  var oDBComboTipoReajuste = new DBComboBox('tipoReajuste', null, []);

  new AjaxRequest('pes4_formularioFolha.RPC.php', {'sExecucao' : 'BuscaTiposReajuste'},
    function (oRetorno, lErro) {
      oRetorno.aTiposReajuste.each(
        function (sDescricaoItem, iIndice) {
          oDBComboTipoReajuste.addItem(iIndice, sDescricaoItem.urlDecode());
        }
      );
    }
  ).setMessage('Buscando tipos de reajuste...').execute();

  return oDBComboTipoReajuste;
}
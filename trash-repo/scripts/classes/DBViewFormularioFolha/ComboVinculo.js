require_once('scripts/widgets/dbcomboBox.widget.js');
require_once('scripts/classes/DBViewFormularioFolha/DBViewFormularioFolha.classe.js');

/**
 * Cria um ComboBox de vinculos.
 * Os options do select serão: Geral, Ativos, Inativos, Pensionistas, Inativos/Pensionistas
 * @returns DBComboBox
 */
DBViewFormularioFolha.ComboVinculo = function() {
  
  var oDBComboBox =  new DBComboBox('Vinculo', null, []);
  
  oDBComboBox.addItem('g', 'Geral');
  oDBComboBox.addItem('a', 'Ativos');
  oDBComboBox.addItem('i', 'Inativos');
  oDBComboBox.addItem('p', 'Pensionistas');
  oDBComboBox.addItem('ip','Inativos / Pensionistas');
  
  return oDBComboBox;
};

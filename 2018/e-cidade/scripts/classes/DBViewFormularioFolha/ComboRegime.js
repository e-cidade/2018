
require_once('scripts/widgets/dbcomboBox.widget.js');
require_once('scripts/classes/DBViewFormularioFolha/DBViewFormularioFolha.classe.js');

/**
 * Cria um ComboBox de Regime.
 * O select possui os seguintes options: Estatutario, CLT e Extra Quadro
 * @return DBComboBox
 */
DBViewFormularioFolha.ComboRegime = function(){
  
  var oDBComboBox =  new DBComboBox('Regime', 
                                     null, 
                                     []);
  
  oDBComboBox.addItem('', 'Todos');
  oDBComboBox.addItem('1', '1 - Estatutário');
  oDBComboBox.addItem('2', '2 - CLT');
  oDBComboBox.addItem('3', '3 - Extra Quadro');
  
  return oDBComboBox;
};

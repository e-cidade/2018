/**
 * Cria um combobox com os tipos de itinerários
 *
 * @author Andrio Costa <andrio.costa@dbseller.com.br
 *
 * @param {HtmlElement} oElement elemento alvo do select
 */
DBViewLinha.ComboItinerario = function ( oElement ) {

  var oCboItinerario = document.createElement('select');
  oCboItinerario.id  = 'cboItinerario';
  oCboItinerario.add(new Option('TODOS', '0'));
  oCboItinerario.add(new Option('IDA', '1'));
  oCboItinerario.add(new Option('RETORNO', '2'));

  oElement.appendChild(oCboItinerario);
  return oElement;
};
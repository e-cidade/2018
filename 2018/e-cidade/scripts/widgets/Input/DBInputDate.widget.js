/**
 * Representa um campo de digita��o de data do sistema
 *
 * @author Rafael Nery <rafael.nery@dbseller.com.br>
 * @version  $Revision: 1.15 $
 *
 */
(function(exports, DBInput) {

  /**
   * Construtor da Classe
   *
   * @constructor
   * @module  DBInputData
   * @extends DBInput
   * @param   inputElement {HTMLInputElement} elemento que ser� modificado
   */
  var DBInputDate = function(inputElement) {

    this.ID               = Math.random().round();
    this.type             = 'data'; //Dat'A' � proposital para que o chrome n�o formate o campo com o datepicker padr�o @TODO Fazer melhor pls
    this.datepickerButton = this.__getDatePickerButton();
    return DBInput.apply(this, arguments);
  };

  /**
   * Wrapper default
   */
  DBInputDate.create = function(inputElement) {
    return new this(inputElement);
  };

  /**
   * M�todos
   */
  var DBInputDatePrototype = {

    /**
     * Adiciona ao input comportamentos e modifica��es
     * para atender o componente
     */
    '__infect' : DBInput.extend(function() {

      this.inputElement.parentNode.insertBefore(
        this.datepickerButton,
        this.inputElement.nextSibling
      );

      this.inputElement.placeholder = '__/__/____';
      this.inputElement.size        = '10';
      this.inputElement.maxLength   = '10';
      this.inputElement.observe('blur', validarValor.bind(this));
      this.setValue(this.inputElement.value);

      new MaskedInput(this.inputElement, '99/99/9999', {placeholder: ' '});

      DBInput.prototype.__infect.apply(this, arguments);
    }),

    /**
     * Fecha o calend�rio
     */
    'closeDatePicker' : DBInput.extend(function(){

      var nome             = 'InputData' + this.ID;

      if(!!window['DatePicker'+nome]) {
        window['DatePicker'+nome].hide();
      }
    }),

    /**
     * Mostra o calend�rio para selelcionar a data
     */
    'showDatePicker' : DBInput.extend(function() {

      /**
       * @TODO - Usar um datepicker melhor, pois esse n�o d� mais
       */
      var nome             = 'InputData' + this.ID;
      var queryString      = 'dbTxtFieldDataCalendar.php?nome_objeto_data=' + nome + '&nome_instancia=';
      var janelaDatePicker = js_OpenJanelaIframe('','DatePicker'+nome, queryString,'Selecionar',true,0,0,200,230);
      $('JanDatePicker'+nome).clonePosition(this.inputElement, {setHeight: false, setWidth: false, offsetTop: 20, offsetWidth: 0});
      $('JanDatePicker'+nome).style.zIndex='100000';

      /**
       * Callback que ser� chamado a cada carregamento de p�gina dentro do iframe
       */
      var callBackLoad = function(eventLoad) {

        /**
         * Sobrecarrega o evento de click na data
         */
        eventLoad.target.contentWindow.janela = function(dia, mes, ano) {

          this.inputElement.value = formatarData(new Date( Date.UTC(ano, mes - 1, dia)) );
          window['DatePicker'+nome].hide();
          this.inputElement.dispatchEvent(new Event("change"));
          this.inputElement.focus();
          return false;
        }.bind(this);

        /**
         * sobrecarrega a a��o de zerar a data
         */
        eventLoad.target.contentWindow.janela_zera = function() {

          this.inputElement.value         = '';
          window['DatePicker'+nome].hide();
        }.bind(this);

      }.bind(this);

      /**
       * Limpa e redefine o callBackLoad no carregamento do frame
       */
      $('IFDatePicker'+nome).stopObserving('load');
      $('IFDatePicker'+nome).observe('load', callBackLoad);

      return;
    }),

    /**
     * Cria o bot�o datepicker com seus comportamento e estilo
     */
    '__getDatePickerButton' : DBInput.extend(function() {

      var button = document.createElement('input');
      button.value = 'D';
      button.type  = 'button';
      button.setAttribute("tabindex", '-1');
      button.style.paddingRight = '2px';
      button.style.paddingLeft  = '2px';
      button.observe('click', function(event){
        this.showDatePicker();
      }.bind(this));

      return button;
    }),

    /**
     * Retorna os elementos que s�o utilizados no componente
     */
    'getElements' : DBInput.extend(function() {
      return {
        inputText   : this.inputElement,
        inputButton : this.datepickerButton
      };
    }),

    'setValue' : DBInput.extend(function(value) {

      if (value) {

        var oData = createDateFromValue(value);
        if (empty(oData)) {
           return;
        }

        oDate = new Date( Date.UTC(oData.ano, oData.mes, oData.dia) );
        value = formatarData(oDate);
      }

      DBInput.prototype.setValue.apply(this, arguments);
    }),

    'getValue' : DBInput.extend(function() {

      var data = this.inputElement.value.split('/');

      if (!data[0].trim() || !data[1].trim() || !data[2].trim()) {
        return null;
      }

      return new Date( Date.UTC(data[2], data[1] - 1, data[0]) );
    }),

    /**
     * Converte a data de acordo com as op��es informadas.
     * default data em pt-BR ... exemplo 02/02/2016
     *
     * @param  {object} otherOptions par�metros aceitos pela funcao toLocaleDateString
     *                               https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Date/toLocaleDateString
     *                               { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }
     *                               Retorna a data assim: "segunda-feira, 2 de fevereiro de 1976"
     * @return {string} data em pt-BR conforme op��es informadas
     */
    '__toLocaleDateString' : DBInput.extend(function(options) {

      var value = this.getValue();

      if ( typeof options == 'object' ) {
        options = mergeObject({timeZone: 'UTC'}, options);
      } else {
        options = {timeZone: 'UTC'};
      }
      return value.toLocaleDateString('pt-BR', options);
    }),

  };

  /**
   * Formata a data para inser��o no componente
   *
   * @private
   *
   * @param {String|Number}
   * @param {String|Number}
   * @param {String|Number}
   */
  function formatarData(date) {

    var ano = + date.getUTCFullYear()    + '';
    var mes = +(date.getUTCMonth() + 1)  + '';
    var dia = + date.getUTCDate()        + '';

    var padraoDia = '00';
    var padraoMes = '00';
    var padraoAno = '0000';

    ano = padraoAno.substring(0, padraoAno.length - ano.length) + ano;
    mes = padraoMes.substring(0, padraoMes.length - mes.length) + mes;
    dia = padraoDia.substring(0, padraoDia.length - dia.length) + dia;

    return dia + '/' + mes + '/' + ano;
  }

  /**
   * Valida o valor e completa campos n�o informados
   */
  function validarValor(changeEvent) {

    var campo    = this.inputElement;
    var conteudo = this.inputElement.value.replace(/\s+/g, '');
    this.valid   = true;

    if (conteudo == '//') {
      campo.value = '';
      changeEvent.stopPropagation();
      changeEvent.stopImmediatePropagation();
      return;
    }
    var tamanhoCampo   = conteudo.length;
    var posicaoCursor  = [campo.selectionStart, campo.selectionEnd];
    var dataCampo      = conteudo.split('/');

    var dataAtual      = new Date( );
    dataAtual          = new Date( Date.UTC(dataAtual.getUTCFullYear(), dataAtual.getUTCMonth() +1, dataAtual.getUTCDate()) );

    var anoAtual       = +dataCampo[2].trim() || dataAtual.getUTCFullYear();
    var mesAtual       = +dataCampo[1].trim() || dataAtual.getUTCMonth() + 1;
    var diaAtual       = +dataCampo[0].trim() || dataAtual.getUTCDate();
    var anoBissexto    = new Date( Date.UTC(anoAtual, 1, 29) ).getUTCMonth() == 1;

    if (mesAtual < 1 || mesAtual > 12) {

      alert('M�s inv�lido.');
      campo.focus();
      campo.selectionStart = 3;
      campo.selectionEnd   = 5;
      this.valid = false;
      return;
    }

    var diasFevereiro = anoBissexto ? 29 : 28;

    if (mesAtual == 2 && diaAtual > diasFevereiro) {
      alert('Dia Inv�lido, m�s de fevereiro do ano de ' + anoAtual + ' tem ' + diasFevereiro + ' dias.');
      campo.selectionStart = 0;
      campo.selectionEnd   = 2;
      this.valid = false;
      return;
    }

    var limite = 30;

    if (js_search_in_array([1, 3, 5, 7, 8, 10, 12], mesAtual) ){
      limite = 31;
    }

    if (diaAtual > limite) {
      alert("Dia inv�lido, o m�s de " + Calendar.Months[mesAtual - 1] + " tem " + limite + ' dias.');
      this.valid = false;
    }

    campo.value = formatarData(new Date(Date.UTC(anoAtual, mesAtual - 1, diaAtual)));
  }

  /**
   * Valida o valor e completa campos n�o informados
   */
  function createDateFromValue(value) {

    var oData = {
      ano : 0,
      mes : 0,
      dia : 0
    };

    if (value.indexOf('/') !== -1) {

      var aData = value.substr(0, 10).split('/');
      oData.ano = aData[2];
      oData.mes = aData[1] - 1;
      oData.dia = aData[0];
      return oData;
    }

    if (value.indexOf('-') !== -1) {

      var aData = value.substr(0, 10).split('\-');

      oData.ano = aData[0];
      oData.mes = aData[1] - 1;
      oData.dia = aData[2];
      return oData;
    }

    return '';
  }

  /**
   * Registrando Heran�a
   */
  DBInputDate.prototype = Object.create(DBInput.prototype, DBInputDatePrototype);
  DBInputDate.prototype.constructor = DBInputDate;


  /**
   * Registrando m�dulo na memoria para execu��o
   */
  exports.DBInputDate = DBInputDate;
  return DBInputDate;

})(this, DBInput);

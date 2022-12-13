
require_once('scripts/widgets/Input/DBInput.widget.js');

(function (exports, DBInput) {
  /**
   * Componente que renderiza/transforma um input para suportar variações de data alem de suas validações
   *
   * @constructor
   * 
   * @author Rafael Nery <rafael.nery@dbseller.com.br>
   * @author André Mello <andre.mello@dbseller.com.br>
   * @return DBInputData
   */
  DBInputHora = function( oElement ) {

    // this.inputElement               = DBInputHora.prepareElement( oElement || this.createElement() );
    this.iIncrementoValor     = 1;
    this.lIncrementarComSetas = true;
    this.type = 'hora';

    this.fCallBack            = function(sErro) {

      alert(sErro);
      this.setValue('');
      this.getElement().focus();
    }
    return DBInput.apply(this, arguments);
  };


  var prototypeHora = {};

  prototypeHora.__infect =  DBInput.extend(function() {

    DBInput.prototype.__infect.apply(this, arguments);

   /**
    * Executa a mudança do valor de 'this' dentro do callback com a função bind
    */
    this.inputElement.addEventListener('keypress', DBInputHora.eventFunctions.keyPress.bind(this));

    this.inputElement.addEventListener('change',   DBInputHora.eventFunctions.changeValue.bind(this));
    this.inputElement.addEventListener('paste',    DBInputHora.eventFunctions.changeValue.bind(this));
    this.inputElement.addEventListener('drop',     DBInputHora.eventFunctions.changeValue.bind(this));
  });

  prototypeHora.getValue = DBInput.extend(function() {
    return this.inputElement.value;
  });

  prototypeHora.setValue = DBInput.extend(function( sValue, lMantemPosicionamento ) {

    if (!this.getElement()) {
      return;
    }
    var iSelecaoInicio               = this.getElement().selectionStart;
    var iSelecaoFim                  = this.getElement().selectionEnd;

    this.inputElement.value                = sValue;

    if (!!lMantemPosicionamento) {

      this.getElement().selectionStart = iSelecaoInicio;
      this.getElement().selectionEnd   = iSelecaoFim;
    }
    return;
  });


  /**
   * Define o callback de erro na validação
   *
   * @param fCallBack - Callback de Erro
   *                    Muito importante salientar que o valor de 'THIS' dentro da função callback será a instancia do
   *                    componente e não a do elemento input. Portanto quando for definir/acessar valores do input
   *                    através do callback utilizar this.getValue()/this.setValue() e não this.value.
   * @returns void
   */
  prototypeHora.setErrorCallBack = DBInput.extend(function( fCallBack ) {

    this.fCallBack = fCallBack;
    return;
  });
  /**
   * Define os atributos basicos para o funcionamento do componente
   *
   * @static
   **/
  DBInputHora.prepareElement = function( oElement ) {

    oElement.type       = 'text';
    oElement.className += " DBInput DBInputHora ";
    return oElement;
  }

  /**
   * Move (via Element.append) o elemento para o nó especificado
   */
  prototypeHora.show = DBInput.extend(function( oElement ) {

    oElement.appendChild(this.inputElement);
  });

  /**
   * Funções para eventos
   */
  DBInputHora.eventFunctions          = {

    /**
     * Disparado quando um tecla é pressionada
     */
    "keyPress" : function(event) {
      /**
       * 08 - backspace
       * 58 - :
       * 46 - del
       */
      var key                    = event.keyCode || event.charCode;
      var sValorAtual            = this.getElement().getValue();
      var reNumeroValido         = /^[0-9\:]$/;
      var reTeclasNavegacao      = /^(9|35|36|37|39|8|46)$/;
      var sValorTeclaPressionada = String.fromCharCode(event.charCode);
      var nIncremento            = 1;
      var iSelecaoInicio         = this.getElement().selectionStart;
      var iSelecaoFim            = this.getElement().selectionEnd;
      var lExisteSelecao         = iSelecaoInicio != iSelecaoFim;
      var lIncrementaHorasComSeta= iSelecaoFim < 3;

      if ( (key == 38 || key == 40 || key == 33 || key == 34) && !!this.lIncrementarComSetas ) {//Up,Down,PgUp,PgDown

        if ( key == 38 || key == 33 ) {//cima
          nIncremento *= 1;
        }

        if ( key == 40 || key == 34 ) { //Baixo
          nIncremento *= -1;
        }

        this.setValue(this.getValue() || "0", true);
        this.maskValue();


        if ( (key == 38 || key == 40) && !lIncrementaHorasComSeta) {//Up,Down
          this.getElement().setValue(DBInputHora.modifyMinutes(this.getValue(), nIncremento),true);
        }

        if ( ( key == 33 || key == 34 ) || lIncrementaHorasComSeta ) {//PgUp, PgDown
          this.getElement().setValue(DBInputHora.modifyHours(this.getValue(), nIncremento),true);
        }

        event.preventDefault();
        event.stopImmediatePropagation();
        return;
      }


      /**
       * Valida se não foi pressionada nenhuma tecla de navegação
       */
      if ( !reTeclasNavegacao.test(key) ) {// != 8 && key != 46) {

        if ( !reNumeroValido.test(sValorTeclaPressionada) ) {
          event.preventDefault();
          return;
        }

        /**
         * Controla posicionamento do caractere ":"
         */
        if (key == 58 ) {

          switch ( this.getValue().length ) {
            case 0:
              this.setValue( '00' + this.getValue() );
            break;
            case 1:
              this.setValue( '0' + this.getValue() );
            break;
            case 2:
            break;
            default:
              event.preventDefault();
              return;
            break;
          }
        }

        /**
         * Caso esteja digitando os numeros coloca o caractere ":" automaticamente
         */
        if (key != 58 && this.getValue().length == 2 ) {
          this.setValue( this.getValue() + ':' );
        }

        /**
         * Não bloqueia digitação caso tenha algum valor selecionado e o tamanho for o máximo
         */
        if ( this.getValue().length > 4 && !lExisteSelecao ) {
          event.preventDefault();
          return;
        }
      }
      return;
    },

    /**
     * Evento Disparado quando ocorre alguma mudança do valor do campo(change, drop, paste)
     */
    "changeValue"   : function( oEvento ) {

      var oDataTransfer = oEvento.dataTransfer || oEvento.clipboardData || null;

      if ( oDataTransfer ) {

        var sValorAnterior = this.getValue();

        this.setValue(oDataTransfer.getData('text/plain'));

        if ( !DBInputHora.validate(this.getElement(), this.fCallBack.bind(this) ) ) {
          this.setValue(sValorAnterior);
        }
        oEvento.preventDefault();
        return;
      }

      if ( this.getValue() == '' ) {

        oEvento.preventDefault();
        return;
      }

      this.maskValue();

      if ( !DBInputHora.validate(this.getElement(), this.fCallBack.bind(this)) ) {
        oEvento.preventDefault();
        return;
      }
    }

  }

  /**
   * Mascara o valor do campo/completando com zeros e : quando necessário
   */
  prototypeHora.maskValue = DBInput.extend(function() {


      while(this.getValue().length < 5) {

        if (this.getValue().substr(0, 2).length == 1 ||
            this.getValue().substr(0, this.getValue().indexOf(':')).length == 1) {

          this.setValue( '0' + this.getValue());
        }

        if (this.getValue().length == 2) {
          this.setValue( this.getValue() + ':' );
        }

        if (this.getValue().length < 5) {
          this.setValue( this.getValue() + '0');
        }
      }
  });

  /**
   * Executa as validações do campo
   */
  DBInputHora.validate = function( elemento, fCallBack ) {

    var iHoras       = new Number(elemento.value.substr(0, 2));
    var iMinutos     = new Number(elemento.value.substr(3, 5));
    var reHoraValida = /^[0-9]{2}\:[0-9]{2}$/;
    try {

      if ( !reHoraValida.test(elemento.value) ) {
        throw 'Formato de Hora inválida';
      }

      if (elemento.value.indexOf(':') != 2) {
        throw 'Hora inválida.';
      }

      if (iHoras > 23) {
        throw 'Hora inválida.';
      }

      if (iMinutos >= 60) {
        throw 'Hora inválida.';
      }
    } catch (erro) {

      fCallBack(erro);
      return false;
    }
    return true;
  }

  /**
   * Compatibilidade com o DBBootstrap.
   */
  DBInputHora.build = DBInputHora.create = function( oElemento ) {
    return new DBInputHora( oElemento );
  }

  /**
   * Incrementa/Descrementa Minutos
   *
   * @TODO Fazer quantidade maior que 1
   */
  DBInputHora.modifyMinutes = function( sHoraAtual, iQuantidade ) {

    var iMinutos, iHoras;
    iMinutos = new Number(sHoraAtual.split(":")[1]) + 0;
    iHoras   = new Number(sHoraAtual.split(":")[0]) + 0;
    /**
     * Valida hora antes que faça algo
     */
    if ( iHoras >= 23 && iMinutos >= 59 && iQuantidade > 0 ) {
      return "00:00";
    }

    if ( iHoras <= 0 && iMinutos <= 0 && iQuantidade < 0 ) {
      return "23:59";
    }

    /**
     * Agora sim atribui o valor
     */
    iMinutos+= iQuantidade;

    if ( iMinutos >= 60 && iQuantidade > 0 ) {

      iMinutos = 0;
      iHoras  += 1;
    }

    if ( iMinutos < 0 && iQuantidade < 0 ) {

      iMinutos = 59;
      iHoras  -= 1;
    }

    var sMinutos = new String(iMinutos);
    var sHoras   = new String(iHoras);
    sMinutos     = iMinutos < 10 ? '0' + sMinutos : sMinutos;
    sHoras       = iHoras   < 10 ? '0' + sHoras   : sHoras  ;
    return sHoras + ":" + sMinutos;
  }

  /**
   * Incrementa/Descrementa Horas
   *
   * @TODO Fazer quantidade maior que 1
   */
  DBInputHora.modifyHours = function( sHoraAtual, iQuantidade ) {

    var iMinutos, iHoras;
    iMinutos = new Number(sHoraAtual.split(":")[1]) + 0;
    iHoras   = new Number(sHoraAtual.split(":")[0]) + 0;
    /**
     * Valida hora antes que faça algo
     */
    if ( iHoras >= 23 && iQuantidade > 0 ) {
      iHoras = 0;
    } else if ( iHoras <= 0  && iQuantidade < 0 ) {
      iHoras = 23;
    } else {
      iHoras += iQuantidade;
    }

    var sMinutos = new String(iMinutos);
    var sHoras   = new String(iHoras);
    sMinutos     = iMinutos < 10 ? '0' + sMinutos : sMinutos;
    sHoras       = iHoras   < 10 ? '0' + sHoras   : sHoras  ;
    return sHoras + ":" + sMinutos;
  }

  DBInputHora.prototype = Object.create(DBInput.prototype, prototypeHora);
  DBInputHora.prototype.constructor = DBInputHora;
  exports.DBInputHora = DBInputHora;
  return DBInputHora;

})(this, DBInput);
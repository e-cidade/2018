
(function (exports, DBInput) {

  var DBInputCNPJ = function(inputElement) {

    //this.ID   = Math.random().round();
    this.type = 'cnpj';

    return DBInput.apply(this, arguments);
  }

  /**
   * 11.222.333/0001-XX
   * 112223330001XX
   */
  DBInputCNPJ.prototype = Object.create(DBInput.prototype, {

    '__infect' : DBInput.extend(function() {
      
      this.inputElement.placeholder = '__.___.___/____-__';
      this.inputElement.size        = '17';
      this.inputElement.maxLength   = '17';
      this.inputElement.minLength   = '16';
      this.inputElement.observe('blur', validaCnpj.bind(this));
      
      new MaskedInput(this.inputElement,"99.999.999/9999-99", {placeholder: ' '});
      DBInput.prototype.__infect.apply(this, arguments);
    }),
    'getValue' : DBInput.extend(function() {
      return this.inputElement.value.replace(/[^\d]*/g, '');
    }),
    'setValue' : DBInput.extend(function(value) {
      value = value.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/g, "$1.$2.$3/$4-$5");
      DBInput.prototype.setValue.apply(this, arguments);
    }),
  });

  var validaCnpj = function(event) {

    var aAlgarismos     = new Array(5,4,3,2,9,8,7,6,5,4,3,2);
    var iCnpj           = event.target.value.replace(/[^\d]*/g,'');
    var iVerificador    = iCnpj.slice(12);
    var iCnpjVerificado = iCnpj.slice(0, -2);
    var sMensagem       = "Informe um cnpj válido.";
    this.valid          = true;

    /**
     * Se o cnpj digitado for menor que 14, já é um cnpj inválido
     */
    if (iCnpj.length < 14) {

      event.preventDefault();
      this.valid = false;
      return this.callbackError(sMensagem);
    }

    /**
     * Alinhamos os digitos do CNPJ sem o digito verificador com os algarismos 5,4,3,2,9,8,7,6,5,4,3,2.
     * Ex: 112223330001
     * |05|04|03|02|09|08|07|06|05|04|03|02| 
     * |01|01|02|02|02|03|03|03|00|00|00|01| Multiplicamos o algarismo pelo digito do CNPJ
     * |05|04|06|04|18|24|21|18|00|00|00|02| e então somamos o resultado, resultando no numero 102
     * O resultado do somatorio é atribuido a variável iSomatorioAlgarismos
     */

    var iSomatorioAlgarismos = aAlgarismos.reduce(function(iValorAnterior, iValor, index, array) {
      return iValorAnterior + (iValor * iCnpjVerificado[index]);
    }, 0);

    /**
     * Verificamos o primeiro digito verificador.
     * Obtemos o resto da divisao do Somatorio dos algoritmos pelo número 11. (102/11)
     * Se o resto da divisão for menor que 2 o digito verificador é 0 caso contrario subtraimos o resto de 11
     * 102 % 11 = 3 DigitoVerificador: 11 - 3 = 8;
     */
    var iRestoDivisao = iSomatorioAlgarismos % 11;
    var iPrimeiroDigitoVerificador = (iRestoDivisao < 2) ? 0 : (11 - iRestoDivisao);

    /**
     * Adicionamos o digito verificador a iCnpj
     */
    iCnpjVerificado = iCnpjVerificado.concat(iPrimeiroDigitoVerificador);

    /**
     * Realizamos novamente o mesmo somatorio do primeitro digito verificador, mas dessa 
     * vez adicionando a multiplicacao e somatorio do primeiro digito verificador.
     */
    aAlgarismos     = new Array(6,5,4,3,2,9,8,7,6,5,4,3,2);
    var iSomatorioAlgarismos = aAlgarismos.reduce(function(iValorAnterior, iValor, index, array) {
      return iValorAnterior + (iValor * iCnpjVerificado[index]);
    }, 0);

    /**
     * Realizamos a mesma verificação do primeito digito verificador.
     * Se o resto da divisão for menor que 2 o digito verificador é 0 caso contrario subtraimos o resto de 11
     */
    iRestoDivisao = iSomatorioAlgarismos % 11;
    var iSegundoDigitoVerificador = (iRestoDivisao < 2) ? 0 : (11 - iRestoDivisao);

    iCnpjVerificado = iCnpjVerificado.concat(iSegundoDigitoVerificador);
    
    /**
     * Verificamos se o cnpj é válido.
     */
    if (iCnpj != iCnpjVerificado) {
      this.valid = false;
      return this.callbackError(sMensagem);
    }

    return true;
  };

  //DBInputCNPJ.prototype = Object.create(DBInput, DBInputCNPJPrototype);
  DBInputCNPJ.prototype.constructor = DBInputCNPJ;

  exports.DBInputCNPJ = DBInputCNPJ;
  return DBInputCNPJ;

})(this, DBInput);
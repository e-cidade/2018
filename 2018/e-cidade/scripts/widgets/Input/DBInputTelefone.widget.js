
(function (exports, DBInput){

  var DBInputTelefone = function(inputElement) {

    this.ID   = Math.random().round();
    this.type = 'phone';

    return DBInput.apply(this, arguments);
  }

  DBInputTelefone.create = function(inputElement) {
    return new this(inputElement);
  }

  var DBInputTelefonePrototype = {


    '__infect' : DBInput.extend(function() {
      
      this.inputElement.placeholder = '(__) ____ - _____';
      this.inputElement.size        = '17';
      this.inputElement.maxLength   = '17';
      this.inputElement.minLength   = '16';
      
      new MaskedInput(this.inputElement,
                "(99) 9999-99999", {placeholder: ' '});

      DBInput.prototype.__infect.apply(this, arguments);
    }),

    'setValue' : DBInput.extend(function(valor) {

      DBInput.prototype.setValue.apply(this, arguments);

      this.inputElement.value = this.inputElement.value

        //Tira sujeira
        .replace(/([^\d]+)/g, '') 
        
        //Coloca caracteres especiais
        .replace(/(\d{2})(\d{4})(\d+)/g, function(valorEncontrado, ddd, prefixo, sufixo){ 
          return '(' + ddd + ') ' + prefixo + '-' + sufixo;
        });
    }),

    'getValue' : DBInput.extend(function() {
      return DBInput.prototype.getValue.apply(this, arguments).replace(/([^\d]+)/g, '');
    })
  }

  DBInputTelefone.prototype = Object.create(DBInput.prototype, DBInputTelefonePrototype);
  DBInputTelefone.prototype.constructor = DBInputTelefone;

  exports.DBInputTelefone = DBInputTelefone;
  return DBInputTelefone;

})(this, DBInput);
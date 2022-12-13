/**
 * Representa um campo de digitação de data do sistema
 * 
 * @author Renan Silva <renan.silva@dbseller.com.br>
 * @version  $Revision: 1.3 $
 *
 */
(function(exports, DBInput) {

	function validarCpf(event) {

		var iNumerosCpf = this.getValue().replace(/[^\d]*/g, '');
		var iDigitoVerificadorUm, iDigitoVerificadorDois, sMessagemCpfInvalido, aBlackList, iBaseNumerosCpf;

		sMessagemCpfInvalido = 'Informe um CPF válido';
		iBaseNumerosCpf      = iNumerosCpf.substr(0, 9);
		aBlackList           = [];

		for (var i = 0; i < 10; i++) {
			aBlackList.push(i.toString().repeat(11));
		};
		this.valid = true;

		if(iNumerosCpf.length == 11) {

			if(aBlackList.indexOf(iNumerosCpf) != -1) {
				alert(sMessagemCpfInvalido);
				event.preventDefault();
				this.valid = false;
				return;
			} 

			iDigitoVerificadorUm = calcularDigito(iBaseNumerosCpf);

			if(iDigitoVerificadorUm != parseInt(iNumerosCpf.replace(/.*(\d{1})(\d{1})$/g, "$1"))) {

				alert(sMessagemCpfInvalido);
				event.preventDefault();
				this.valid = false;
				return;
			}

			iDigitoVerificadorDois = calcularDigito(iBaseNumerosCpf.toString()+iDigitoVerificadorUm);

			if(iDigitoVerificadorDois != parseInt(iNumerosCpf.replace(/.*(\d{1})$/g, "$1"))) {

				alert(sMessagemCpfInvalido);
				event.preventDefault();
				this.valid = false;
				return; 
			}
		}
	}

	function calcularDigito (iBase) {

		var iResto, iTotalCalculado = 0;
		
		for (var i = (iBase.length + 1); i > 1; i--) {
			
			var iDigitoatual = iBase.toString().substr(((iBase.length + 1)-i), 1);
			iTotalCalculado += i * iDigitoatual;
		};

		iResto = iTotalCalculado % 11;

		return (iResto < 2) ? 0 : (11 - iResto);
	}

	var DBInputCpf = function () {

		this.type = 'cpf';
		return DBInput.apply(this, arguments);
	}

	/**
   * Registrando Herança
   */
  DBInputCpf.prototype = Object.create(DBInput.prototype, {

  	'__infect' : DBInput.extend(function() {

  		this.inputElement.placeholder = '___.___.___-__';
  		this.inputElement.size        = 14;
  		this.inputElement.maxLength   = 14;

  		var me = this;

  		this.inputElement.observe('blur', validarCpf.bind(me));

  		new MaskedInput(this.inputElement,
                			"999.999.999-99",
                			{placeholder: '_'});

  		DBInput.prototype.__infect.apply(this, arguments)
  	}),

  	'getValue' : DBInput.extend(function() {
      return this.inputElement.value.replace(/[^\d]*/g, '');
    }),
    'setValue' : DBInput.extend(function(value) {
      value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/g, "$1.$2.$3-$4");
      DBInput.prototype.setValue.apply(this, arguments);
    }),
  });
		
  DBInputCpf.prototype.constructor = DBInputCpf;


  /**
   * Registrando módulo na memoria para execução
   */
  exports.DBInputCpf = DBInputCpf;
  return DBInputCpf;

})(this, DBInput);
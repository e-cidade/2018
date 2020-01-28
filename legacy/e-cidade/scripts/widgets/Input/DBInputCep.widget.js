/**
 * Representa um campo de digitação de data do sistema
 * 
 * @author Renan Silva <renan.silva@dbseller.com.br>
 * @version  $Revision: 1.3 $
 *
 */
(function(exports, DBInput) {

  var aTeclasEspeciaisPermitidas = [
		46,   // Delete
		8     // Backspace
	];
	
	var aTeclasEspeciais = [
		8,    // Backspace
		46,   // Delete
		9,    // Tab
		16,   // Shift
		17,   // Ctl
		18,   // Alt
		225,  // AltGr
		13,   // Enter
		36,   // Home
		35,   // End
		33,   // PageUp
		34,   // PageDown
		38,   // Seta para cima
		39,   // Seta para direita
		40,   // Seta para baixo
		37,   // Seta para esquerda
		20    // CapsLock
	];

	var iPosicaoCursor;

	function guardarPosicaoCursor (event) {
		
		iPosicaoCursor = event.target.selectionStart;

		if( !parseInt(event.key) && parseInt(event.key) !== 0 && !event.ctrlKey) { //Mantém o cursos na posição atual se digitar caracteres inválidos

			if(aTeclasEspeciais.indexOf(event.keyCode) == -1) {
				event.preventDefault();
			}
		}
	}
		
	function aplicarMascara(event) {

		var iNumerosCep, sCep, oInputCep, aPartesCep = null;

		if(aTeclasEspeciais.indexOf(event.keyCode) == -1 || aTeclasEspeciaisPermitidas.indexOf(event.keyCode) != -1) {

			oInputCep   = event.target;
			sCep        = oInputCep.value;
			iNumerosCep = sCep.replace(/[^\d]*/g, '');

			var iPrimeirosNumeros, iUltimosNumeros;
			iPrimeirosNumeros    = iUltimosNumeros = null;
		  aPartesCep           = /^(\d{5})(\d{1,3}).*/g.exec(iNumerosCep);

		  if(aPartesCep) {

				iPrimeirosNumeros = aPartesCep[1];

				if(iNumerosCep.length > 5) {
					iUltimosNumeros   = aPartesCep[2];
				}

				if(iPrimeirosNumeros) {

					sCep = iPrimeirosNumeros;

					if(iUltimosNumeros) {
						sCep += '-'+iUltimosNumeros;
					}
				}
			}

			oInputCep.value = sCep;

			if(aTeclasEspeciaisPermitidas.indexOf(event.keyCode) != -1) {

				if(event.keyCode == 8) {
					iPosicaoCursor--;
				}
			  
		  	oInputCep.selectionStart = iPosicaoCursor;
		  	oInputCep.selectionEnd   = iPosicaoCursor;
			}

			return

		}
	}

	function validarTecla(event) {

		var key              = event.key;

		/**
		 * Verifica se não é número e se 
		 * é alguma tecla especial, se não
		 * retorna. Teclas especiais são
		 * (backspace, delete e setas direcionais)
		 */
		if( !parseInt(key) && parseInt(key) !== 0 && !event.ctrlKey) {

			if(aTeclasEspeciais.indexOf(event.keyCode) == -1) {

				alert('Digite apenas números.');
				return false;
			}
		}

		aplicarMascara(event);
	}

	function validarPaste(event) {

		alert('Apenas digitação.');
		event.preventDefault();
	}

	function validarDrop(event) {

		alert('Apenas digitação.');
		event.preventDefault();
	}

	var DBInputCep = function () {

		this.type = 'cep';
		return DBInput.apply(this, arguments);
	}

	/**
   * Registrando Herança
   */
  DBInputCep.prototype = Object.create(DBInput.prototype, {

  	'__infect' : DBInput.extend(function() {

  		this.inputElement.placeholder = '_____-___';
  		this.inputElement.size        = 9;
  		this.inputElement.maxLength   = 9;

  		this.inputElement.observe('paste', validarPaste);
  		this.inputElement.observe('drop', validarDrop);
  		this.inputElement.observe('keyup', validarTecla);
  		this.inputElement.observe('keydown', guardarPosicaoCursor);

  		DBInput.prototype.__infect.apply(this, arguments)
  	}),

  	'getValue' : DBInput.extend(function() {
      return this.inputElement.value.replace(/[^\d]*/g, '');
    }),

  	'setValue' : DBInput.extend(function(value) {
  		value = value.replace(/(\d{5})(\d{1,3})/g, "$1-$2");
  		DBInput.prototype.setValue.apply(this, arguments);
    }),
  });
		
  DBInputCep.prototype.constructor = DBInputCep;


  /**
   * Registrando módulo na memoria para execução
   */
  exports.DBInputCep = DBInputCep;
  return DBInputCep;

})(this, DBInput);
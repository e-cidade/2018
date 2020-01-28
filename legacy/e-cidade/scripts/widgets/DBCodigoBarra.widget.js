 /**
  * Esse arquivo cria um componente com um campo de codigo de barras
  * será possivel passar o label, limite de caracteres e a mensagem.
  *
  * @constructor
  *
  * @example
  * var oDBCodigoBarra = new DBCodigoBarra("Instancia do OBJ");
	* oDBCodigoBarra.setLabelCodigoBarra("Lable do campo:");
	* oDBCodigoBarra.setMaximoDigito(tamanhodo padrao do codigo de barra);
	* oDBCodigoBarra.setMensagemLeitura('msgm de aguardadndo leitura');
	* oDBCodigoBarra.criaComponentes();
	* oDBCodigoBarra.show('container a ser exibido');
  *
  * @author Rafael Lopes <rafael.lopes@dbseller.com.br>
  * @author Bruno silva <bruno.silva@dbseller.com.br>
  * @version $Revision: 1.8 $
  * @param {String} sNomeCampo
  * @param {String} sNameInstance
  */
DBCodigoBarra = function (sNomeCampo, sNameInstance) {

  this.sLabelCodigoDeBarra    = "Código de Barras:";
  this.sLabelLinhaDigitavel   = "Linha Digitável:";
  this.sMensagemLeitura       = "Aguardando Leitura do Código de Barras";
  this.sNameInstance          = sNameInstance;
  this.sAtributosBotao        = "";
  this.iTamanhoCampo          = 50;
  this.iMaximoDigito          = 44;
  this.sEstiloCssLeitura      = "background-color: rgb(222, 184, 135); color: black";
  this.sNomeCampo             = sNomeCampo;
  this.fCallbackAposLeitura   = function () {};
  this.fCallbackInicioLeitura = function () {};
  this.fModulo                = function () {};

  /**
   * Seta a mensagem de leitura do código de barras.
   * @param sMensagemLeitura
   */
  this.setMensagemLeitura = function(sMensagemLeitura) {
	  this.sMensagemLeitura = sMensagemLeitura;
  };

  /**
   * Seta a quantidade máxima de dígitos.
   * @param iMaximoDigito
   */
  this.setMaximoDigito = function(iMaximoDigito){
	  this.iMaximoDigito = iMaximoDigito;
  };

  /**
   * Seta o label para o campo do código de barras.
   * @param sLabel
   */
  this.setLabelCodigoBarra = function(sLabel){
	  this.sLabelCodigoDeBarra = sLabel;
  };

  /**
   * Seta o tamanho dos campos.
   * @param iTamanhoCampo
   */
  this.setTamanhoCampo = function(iTamanhoCampo){
	  this.iTamanhoCampo = iTamanhoCampo;
  };

  /**
   * Seta o atributo do botão.
   * @param sAtributos
   */
  this.setAtributosBota = function(sAtributos)  {
	  this.sAtributosBotao = sAtributos;
  };

  /**
   * Cria os elementos necessários (Linha Digitável e Código de Barras).
   */
  this.criaComponentes = function() {

    sConteudoLinhaDigitavel  = "<td><label id='ctnLabelLinhaDigitavel'><strong>" + this.sLabelLinhaDigitavel + "</strong></label></td>";
    sConteudoLinhaDigitavel += "<td><label id='ctnLinhaDigitavel' ></label>";
    sConteudoLinhaDigitavel += "</td>";

    sConteudoCodigoDeBarra  = "<td><label id='ctnLabelCodigoBarra'><strong>" + this.sLabelCodigoDeBarra + "</strong></label></td>";
	  sConteudoCodigoDeBarra += "<td><label id='ctnCodigoBarra' ></label>";
	  sConteudoCodigoDeBarra += "<input id='btnCodigoBarra' type='button' value='Cadastrar' onclick = '"+ this.sNameInstance+".liberarCodigoDeBarra();' "+ this.sAtributosBotao +" />";
	  sConteudoCodigoDeBarra += "</td>";

    this.sConteudoLinhaDigitavel = sConteudoLinhaDigitavel;
	  this.sConteudoCodigoDeBarra  = sConteudoCodigoDeBarra;
  };

  /**
   * Exibe os elementos de código de barras e linha digitável na tela.
   * @param {string} sContainerCodigoDeBarra  Id do elemento que terá o campo para o código de barras.
   * @param {string} sContainerLinhaDigitavel Id do elemento que terá o campo para a linha digitpavel.
   */
  this.show = function (sContainerCodigoDeBarra, sContainerLinhaDigitavel) {

    $(sContainerLinhaDigitavel).innerHTML = this.sConteudoLinhaDigitavel;
	  $(sContainerCodigoDeBarra).innerHTML  = this.sConteudoCodigoDeBarra;


    oTxtLinhaDigitavel = new DBTextField('txtLinhaDigitavel', 'oTxtLinhaDigitavel', null, this.iTamanhoCampo);
    oTxtLinhaDigitavel.addEvent("onChange", this.sNameInstance+".gerarCodigoBarra(event)");

	  oTxtCodigoBarra = new DBTextField(this.sNomeCampo,'oTxtCodigoBarra', null, this.iTamanhoCampo);
	  oTxtCodigoBarra.addEvent("onKeyPress", "return js_mask(event,\"0-9\")");
	  oTxtCodigoBarra.setReadOnly(true);
	  oTxtCodigoBarra.addEvent("onKeyUp", this.sNameInstance+".lerCodigo(event)");
	  oTxtCodigoBarra.addEvent("onKeyDown", this.sNameInstance+".bloquearTab(event)");

    oTxtLinhaDigitavel.show($('ctnLinhaDigitavel'));
	  oTxtCodigoBarra.show($('ctnCodigoBarra'));
  };

  /**
   * Libera o input do código de barra para que seja inserido valores
   */
  this.liberarCodigoDeBarra = function() {

    this.fCallbackInicioLeitura();
    oTxtLinhaDigitavel.setValue('');
    oTxtCodigoBarra.setValue('');
	  oTxtCodigoBarra.setReadOnly(false);
	  $(this.sNomeCampo).setAttribute("style", this.sEstiloCssLeitura);

    var sNomeCampo = this.sNomeCampo;
	  $(sNomeCampo).focus();
    js_divCarregando(this.sMensagemLeitura, 'msgBox');

    /**
     * Ao clicar na div criada pela funcao js_divCarregando retorna o focu para o campo
     */
    $('msgBoxmodal').onclick = function(event) {
      $(sNomeCampo).focus();
      return false;
    }
  };

  /**
   * Bloqueia a tecla tab para que, após o usuário clicar no botão, não permita sair do foco do campo input
   */
  this.bloquearTab = function(event) {

    if (event.which == 9) {

      event.preventDefault();
      event.stopPropagation();
      return false;
    };
  };

  /**
   * Lê o código de barra.
   * @param event
   */
  this.lerCodigo = function(event) {

    var oSelf = this;

	  if (event.keyCode == 27) {

	  	js_removeObj("msgBox");
	  	$(this.sNomeCampo).value = '';
	  	oTxtCodigoBarra.setReadOnly(true);
	  }

	  var iTotalCaractereCodigoBarra = $('txtCodigoBarra').value.length;

     if (iTotalCaractereCodigoBarra == this.iMaximoDigito) {

	    if (event.keyCode == 13) {

	    	oTxtCodigoBarra.setReadOnly(true);
	  	  js_removeObj("msgBox");
	  	  oSelf.fCallbackAposLeitura(oSelf.processarCodigoDeBarra(true));
	    }
	  }
	  if (event.keyCode == 13 && iTotalCaractereCodigoBarra > this.iMaximoDigito ) {

	  	$(this.sNomeCampo).value = '';
	  	js_removeObj("msgBox");
	  	js_divCarregando("Codigo de barra invalido, tente novamente ou pressione ESC para sair.", 'msgBox');
	  	oTxtCodigoBarra.setReadOnly(false);
	  	$(this.sNomeCampo).setAttribute("style", this.sEstiloCssLeitura);
	  }
  };
};

 /**
  * Função de callback para o componente executar após ler o código de barras com sucesso
  * @param fFunction
  */
 DBCodigoBarra.prototype.setCallBackAposLeitura = function(fFunction) {
   this.fCallbackAposLeitura = fFunction;
 };

 /**
  * Função de callback para o componente executar antes de ler o código de barras.
  * @param fFunction
  */
 DBCodigoBarra.prototype.setCallBackInicioLeitura = function(fFunction) {
   this.fCallbackInicioLeitura = fFunction;
 };

 /**
  * Processa o codigo de barras, capturando as informações contidas nele.
  * @returns {{tipo: number, data_pagamento: string, valor: number, linha: string}| boolean}
  */
 DBCodigoBarra.prototype.processarCodigoDeBarra = function(lPreencherLinha) {

   var sCodigoBarra    = oTxtCodigoBarra.getValue();
   var iTipoBarra      = sCodigoBarra.substr(0,1) == '8' ? 2 : 1;
   var sData           = '';
   var sLinhaDigitavel = '';
   var sValor          = new Number(sCodigoBarra.substr(4, 11)) / 100 ;

   // Boleto fatura
   if (iTipoBarra == 1) {

     sValor = new Number(sCodigoBarra.substr(9, 10)) / 100 ;

     /**
      * Data base para somar com os dias que vem no codigo de barras
      * - 07/10/1997, no javascript os meses comecao em 0
      */
     var oDataInicial = new Date(1997, 9, 7);
     var iNumeroDias  = new Number(sCodigoBarra.substr(5, 4));

     oDataInicial.setDate(oDataInicial.getDate() + iNumeroDias);
     var sDia = js_strLeftPad(oDataInicial.getDate(), 2, '0');
     var sMes = js_strLeftPad(oDataInicial.getMonth() + 1, 2, '0');
     sData    = oDataInicial.getFullYear()+"-"+ sMes +"-"+ sDia;

     this.fModulo = this.modulo10;
     sLinhaDigitavel = this.geraLinhaDigitavelFatura(sCodigoBarra);

   // Boleto convêio.
   } else {

     var sIdentificador = sCodigoBarra.substr(2, 1);
     this.selecionaModulo(sIdentificador);
     sLinhaDigitavel = this.geraLinhaDigitavelConvenio(sCodigoBarra);
   }

   if (!this.validarCodigoBarra(sCodigoBarra, iTipoBarra, !lPreencherLinha) || !this.validarLinhaDigitavel(sLinhaDigitavel, iTipoBarra)) {

     oTxtCodigoBarra.setValue('');
     if (lPreencherLinha) {
       oTxtLinhaDigitavel.setValue('');
     }
     return false;
   }

   var oRetorno = {
     tipo : iTipoBarra,
     data_pagamento: sData,
     valor: sValor,
     linha: sLinhaDigitavel,
     preencher_linha: lPreencherLinha
   };

   return oRetorno;
};

 /**
  * Gera o codigo de barra de acordo com a linha digitavel, identificando o tipo de fatura pelo tamanho da linha digitável.
  */
 DBCodigoBarra.prototype.gerarCodigoBarra = function() {

   var sCodigoBarra    = '';
   var sLinhaDigitavel = oTxtLinhaDigitavel.getValue();
   sLinhaDigitavel     = sLinhaDigitavel.replace(/[^0-9]/g, '');

   if (sLinhaDigitavel == '') {
     return;
   }

   var iTipoBarra = sLinhaDigitavel.substr(0,1) == '8' ? 2 : 1;

   this.fModulo = this.modulo10;
   if (iTipoBarra == 2) {

     var sIdentificador = sLinhaDigitavel.substr(2, 1);
     this.selecionaModulo(sIdentificador);
   }

   sCodigoBarra = this.geraCodigoBarra(sLinhaDigitavel, iTipoBarra);

   if(!this.validarLinhaDigitavel(sLinhaDigitavel, iTipoBarra)) {

     oTxtCodigoBarra.setValue('');
     return false;
   }

   oTxtCodigoBarra.setValue(sCodigoBarra);
   var oDados = this.processarCodigoDeBarra(false);
   this.fCallbackAposLeitura(oDados);
};

 /**
  * Gera um código de barra de acordo com a linha digitável informada e o tipo.
  * @param {string} sLinhaDigitavel Valor da linha digitável informada.
  * @param {int}    iTipoBarra      Tipo do código de barra.
  * @returns {string}
  */
 DBCodigoBarra.prototype.geraCodigoBarra = function(sLinhaDigitavel, iTipoBarra) {

   if (iTipoBarra == 1) {
     return this.geraCodigoBarraFatura(sLinhaDigitavel);
   }

   if (iTipoBarra == 2) {
     return this.geraCodigoBarraConvenio(sLinhaDigitavel);
   }

   return ('');
 };

 /**
  * Gera o código de barra para um convênio de acordo com a linha digitável.
  * @param {string} sLinhaDigitavel Linha digitável.
  * @returns {string} Código de barra.
  */
 DBCodigoBarra.prototype.geraCodigoBarraConvenio = function(sLinhaDigitavel) {

   var sCodigoBarra  = sLinhaDigitavel.substr(0, 11)
                       + sLinhaDigitavel.substr(12, 11)
                        + sLinhaDigitavel.substr(24, 11)
                          + sLinhaDigitavel.substr(36, 11);

   return (sCodigoBarra);
 };

 /**
  * Gera o código de barras para a fatura de acordo com a linha digitável.
  * @param {string} sLinhaDigitavel Linha digitável.
  * @returns {string} Código de barras.
  */
 DBCodigoBarra.prototype.geraCodigoBarraFatura = function(sLinhaDigitavel) {

   var sCodigoBarra  = sLinhaDigitavel.substr(0, 4)
                       + sLinhaDigitavel.substr(32, 15)
                       + sLinhaDigitavel.substr(4, 5)
                       + sLinhaDigitavel.substr(10, 10)
                       + sLinhaDigitavel.substr(21, 10);

   return (sCodigoBarra );
};

 /**
  * Seleciona o módulo que deve utilizar .
  * @param {string} sIdentificadorValor Identificador de valor do código de barras.
  */
 DBCodigoBarra.prototype.selecionaModulo = function(sIdentificadorValor) {

   switch (sIdentificadorValor) {
     case '6':
     case '7':
       this.fModulo = this.modulo10;
       break;
     case '8':
     case '9':
       this.fModulo = this.modulo11;
       break;
     default:
       alert("O código informado é inválido.");
       break;
   }
 };

 /**
  * Gera a linha digitável para uma fatura a partor do seu código de barras.
  * @param {string} sCodigoBarra Código de barra.
  * @returns {string}
  */
 DBCodigoBarra.prototype.geraLinhaDigitavelFatura = function(sCodigoBarra) {

   var sLinha = sCodigoBarra.replace(/[^0-9]/g, '');

   var sCampo1 = sLinha.substr(0, 4) + sLinha.substr(19, 1) + '.' + sLinha.substr(20, 4);
   var sCampo2 = sLinha.substr(24, 5) + '.' + sLinha.substr(24 + 5, 5);
   var sCampo3 = sLinha.substr(34, 5) + '.' + sLinha.substr(34 + 5, 5);
   var sCampo4 = sLinha.substr(4, 1);   // Digito verificador
   var sCampo5 = sLinha.substr(5, 14);  // Vencimento + Valor

   if (sCampo5 == 0) {
     sCampo5 = '000';
   }

   sLinha = sCampo1 + this.fModulo(sCampo1) + ' ' + sCampo2 + this.fModulo(sCampo2) + ' ' + sCampo3 + this.fModulo(sCampo3) + ' ' + sCampo4 + ' ' + sCampo5;

   return (sLinha);
 };

 /**
  * Gera a linha digitável para o convênio de acordo com o código de barras.
  * @param {string} sCodigoBarra Código de barra.
  * @returns {string} Linha digitável gerada.
  */
 DBCodigoBarra.prototype.geraLinhaDigitavelConvenio = function(sCodigoBarra) {

   var sLinhaDigitavel = "";
   var aBlocos         = [
     sCodigoBarra.substr(0,  11),
     sCodigoBarra.substr(11, 11),
     sCodigoBarra.substr(22, 11),
     sCodigoBarra.substr(33, 11)
   ];

   for (var i = 0; i < aBlocos.length; i++) {

     var sBloco  = aBlocos[i].substr(0, 6);
     sBloco     += ".";
     sBloco     += aBlocos[i].substr(6, 5);
     sBloco     += this.fModulo(aBlocos[i])
     aBlocos[i]  = sBloco;
   }

   sLinhaDigitavel = aBlocos.implode(" ");

   return (sLinhaDigitavel);
};

 /**
  * Módulo 10 para validação e geração da linha digitável.
  * @param {string} sNumero Parte do código de barras
  * @returns {number} Dígito verifivador gerado.
  */
 DBCodigoBarra.prototype.modulo10 = function (sNumero) {

   sNumero       = sNumero.replace(/[^0-9]/g, '');
   var iSoma     = 0;
   var iPeso     = 2;

   for (var iContador = (sNumero.length - 1); iContador >= 0; iContador--) {

     iMultiplicacao = (sNumero.substr(iContador, 1) * iPeso);
     if (iMultiplicacao >= 10) {
       iMultiplicacao = 1 + (iMultiplicacao - 10);
     }
     iSoma = iSoma + iMultiplicacao;

     if (iPeso == 2) {
       iPeso = 1;
     } else {
       iPeso = 2;
     }
   }
   var iDigito = 10 - (iSoma % 10);

   if (iDigito == 10) {
     iDigito = 0;
   }
   return iDigito;
 };

 /**
  * Módulo 11 para validação e geração da linha digitável.
  * @param {string} sNumero Parte do código de barras
  * @returns {number} Dígito verifivador gerado.
  */
 DBCodigoBarra.prototype.modulo11 = function(sNumero) {

   sNumero       = sNumero.replace(/[^0-9]/g, '');
   var iSoma     = 0;
   var iPeso     = 2;
   var iBase     = 9;
   var iContador = sNumero.length - 1;

   for (var iPosicao = iContador; iPosicao >= 0; iPosicao--) {

     iSoma = iSoma + (sNumero.substring(iPosicao, iPosicao + 1) * iPeso);
     if (iPeso < iBase) {
       iPeso++;
     } else {
       iPeso = 2;
     }
   }

   var nDigito = 11 - (iSoma % 11);
   if (nDigito > 9) {
     nDigito = 0;
   }

   /* Utilizar o dígito 1 sempre que o resultado do cálculo padrão for igual a 0, 1 ou 10. */
   if (nDigito == 0) {
     nDigito = 1;
   }
   return nDigito;
 };

 /**
  * Prepara os parâmetros e chama e aplica a validação para um código de barra.
  * @param {string}  sCodigoBarra Código de barra.
  * @param {int}     iTipoBarra   Tipo de boleto.
  * @param {boolean} lGerado      Informa se o código de barra foi gerado da linha digitável..
  * @returns {boolean}
  */
 DBCodigoBarra.prototype.validarCodigoBarra = function(sCodigoBarra, iTipoBarra, lGerado) {

   sCodigoBarra = sCodigoBarra.replace(/[^0-9]/g, '');

   var fFuncaoVerificadora = function(){};
   var iQuantidadeDigitos  = 44;
   var sDigitoVerificador  = "";
   var aDigitosValidar     = [];
   var aDigitosValidadores = [];
   var sDigitosValidar     = "";

   //Boleto fatura.
   if (iTipoBarra == 1) {

     sDigitoVerificador  = sCodigoBarra.substr(4, 1);
     sDigitosValidar     = sCodigoBarra.substr(0, 4) + sCodigoBarra.substr(5);
     fFuncaoVerificadora = this.modulo11;
   }

   //Boleto convênio.
   if (iTipoBarra == 2) {

     sDigitoVerificador = sCodigoBarra.substr(3, 1);
     sDigitosValidar    = sCodigoBarra.substr(0, 3) + sCodigoBarra.substr(4);
     fFuncaoVerificadora = this.fModulo;
   }

   if (sCodigoBarra.length != iQuantidadeDigitos) {

     alert("A quantidade de dígitos informada para o Código de Barra é inválida.");
     return false;
   }

   if (fFuncaoVerificadora(sDigitosValidar) != sDigitoVerificador) {

     var sMensagem = "O Código de barras informado é inválido.";
     if (lGerado) {
       sMensagem = "A Linha Digitável informada é inválida."
     }
     alert(sMensagem);
     return false;
   }

   return true;
 };

 /**
  * Prepara os parâmetros e chama e aplica a validação para uma linha digitável.
  * @param {string} sLinhaDigitavel Linha digitável para validar.
  * @param {int}    iTipoBarra      Tipo de código de barra/linha digitável
  * @returns {boolean}
  */
 DBCodigoBarra.prototype.validarLinhaDigitavel = function(sLinhaDigitavel, iTipoBarra) {

   sLinhaDigitavel = sLinhaDigitavel.replace(/[^0-9]/g, '');

   var iQuantidadeDigitos  = 0;
   var aDigitosValidar     = [];
   var aDigitosValidadores = [];

   //Boleto fatura.
   if (iTipoBarra == 1) {

     iQuantidadeDigitos = 47;

     aDigitosValidar[0] = sLinhaDigitavel.substr(0, 9);
     aDigitosValidar[1] = sLinhaDigitavel.substr(10, 10);
     aDigitosValidar[2] = sLinhaDigitavel.substr(21, 10);

     aDigitosValidadores[0] = sLinhaDigitavel.substr(9, 1);
     aDigitosValidadores[1] = sLinhaDigitavel.substr(20, 1);
     aDigitosValidadores[2] = sLinhaDigitavel.substr(31, 1);
   }

   //Boleto convênio.
   if (iTipoBarra == 2) {

     iQuantidadeDigitos = 48;

     aDigitosValidar[0] = sLinhaDigitavel.substr(0, 11);
     aDigitosValidar[1] = sLinhaDigitavel.substr(12, 11);
     aDigitosValidar[2] = sLinhaDigitavel.substr(24, 11);
     aDigitosValidar[3] = sLinhaDigitavel.substr(36, 11);

     aDigitosValidadores[0] = sLinhaDigitavel.substr(11, 1);
     aDigitosValidadores[1] = sLinhaDigitavel.substr(23, 1);
     aDigitosValidadores[2] = sLinhaDigitavel.substr(35, 1);
     aDigitosValidadores[3] = sLinhaDigitavel.substr(47, 1);
   }

   if (sLinhaDigitavel.length != iQuantidadeDigitos) {

     alert("A quantidade de dígitos informada para a Linha Digitável é inválida.");
     return false;
   }

   for (var iIndice = 0; iIndice < aDigitosValidar.length; iIndice++) {

     if (this.fModulo(aDigitosValidar[iIndice]) != aDigitosValidadores[iIndice]) {

       alert("A Linha Digitável informada é inválida.");
       return false;
     }
   }

   return true;
 };

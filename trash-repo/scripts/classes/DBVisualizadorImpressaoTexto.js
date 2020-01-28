/** 
 * @fileoverview Esse arquivo define uma classe para gerar um visualizador para impress�o de
 * arquivos texto, sendo permitido definir o n�mero de linhas e colunas para poder visualizar
 * como a impress�o ir� ficar. V�rios arquivos texto podem ser visualizados ao mesmo tempo.
 * Quando um arquivo termina, o outro come�a na pr�xima p�gina.
 *
 * @author Tony Farney Bruck Mendes Ribeiro tony.farney@dbseller.com.br
 * @version  $Revision: 1.5 $
 *
 */

/**
 * @class Classe que gera um visualizador para impress�o de arquivos texto, sendo permitido definir o 
 * n�mero de linhas e colunas para poder visualizar como a impress�o ir� ficar.
 * @constructor
 * Obs: Este objeto tem que possuir escopo global no javascript.
 * @param string sIdElementoPai ID do elemento pai (elemento onde ser� appended o visualizador)
 * @param string sAltura Altura do visualizador. Pode ser em %, px 
 * ou qualquer outra unidade aceita pelo estilo css "height".
 * @param string sLargura Largura do visualizador. Pode ser em %, px 
 * ou qualquer outra unidade aceita pelo estilo css "width".
 * @param string sNomeJanelaVisualizacao Nome e ID da div de visualiza��o.
 */
function DBVisualizadorImpressaoTexto(sIdElementoPai, sAltura, sLargura, sNomeJanelaVisualizacao, sUrlRpc) {

  /**
  * N�mero de linhas que cada p�gina suporta.
  * @type int;
  * @private
  */ 
  this.iLinhasPagina = 66;

  /**
  * N�mero de colunas que cada linha suporta.
  * @type int;
  * @private
  */ 
  this.iColunasPagina = 80;

  /**
   * Altura do visualizador (definida pelo estilo css "height").
   * @type string;
   */
  this.sAltura = sAltura == undefined ? "100%" : sAltura;

  /**
   * Largura do visualizador (definida pelo estilo css "width").
   * @type string
   */
  this.sLargura = sLargura == undefined ? "100%" : sLargura;

  /**
  * N�mero de arquivos adicionados para visualiza��o.
  * @type int;
  * @private
  */ 
  this.iNumArquivos = 0;
  
  /**
  * ID do elemento pai da div de visualiza��o.
  * @type string;
  * @private
  */ 
  this.sIdElementoPai = sIdElementoPai == undefined ? 'visualizador' : sIdElementoPai;

  /**
  * Nome da janela de visualiza��o (div) que cont�m todas as p�ginas.
  * @type string;
  * @private
  */ 
  this.sNomeJanelaVisualizacao = sNomeJanelaVisualizacao == undefined ? 'jan_'+this.sIdElementoPai : sNomeJanelaVisualizacao;

  /**
  * Vari�vel com 5050 espa�os em branco utilizada para fazer o padding no final de cada linha.
  * @type string;
  * @private
  */ 
  
  this.sPad = new Array(5050).join(" ");
  /**
   * Variavel armazena a lista de ID das impressoras disponiveis 
   * @type array;
   * @private
   */ 
  this.aImpressoraCod = new Array();
   
  /**
   * Variavel armazena a lista de impressoras disponiveis.
   * @type array;
   * @private
   */ 
  this.aImpressoraDescr = new Array();
   
  /**
   * Variavel armazena a lista de impressoras disponiveis.
   * @type array;
   * @private
   */ 
  this.iIpPadrao = 0;
    
  var me = this;
  this.sUrlRpc = 'sau4_ambulatorial.RPC.php';
  if (sUrlRpc != null) {
    this.sUrlRpc = sUrlRpc;
  }  
  /**
   * M�todo que cria a div de visualiza��o e anexa ela ao elemento pai.
   */
  this.inicializa = function () {

    oDiv = document.createElement('div');
    oDiv.setAttribute('name', this.sNomeJanelaVisualizacao);
    oDiv.setAttribute('id', this.sNomeJanelaVisualizacao);
    oDiv.className    = 'janelaVisualizacao';
    oDiv.style.height = this.sAltura;
    oDiv.style.width  = this.sLargura;
    oDiv.appendChild(document.createElement('br'));

    oPai = document.getElementById(this.sIdElementoPai);
    oPai.appendChild(oDiv);

  }

  this.inicializa();

  /**
   * M�todo que adiciona arquivo para visualizar.
   * @param string sTexto Conte�do do arquivo (string)
   */
  this.addArquivo = function (sTexto) {

    this.iNumArquivos++;
    var oTextArea = document.createElement('textarea');
    oTextArea.setAttribute('name', 'textoArquivo'+this.sIdElementoPai);
    oTextArea.setAttribute('id', 'textoArquivo'+this.iNumArquivos);
    oTextArea.className = 'textareaTextoArquivo';
    oTextArea.value     = sTexto;
    document.getElementById(this.sNomeJanelaVisualizacao).appendChild(oTextArea);

  }

  /**
   * M�todo que seta as impressoras selesionaveis.
   * @param {int} iLinhas N�mero de linhas de cada p�gina.(Intervalo v�lido: 1 - 2000)
   * @param {int} iColunas N�mero de colunas de cada linha da p�gina. (Intervalo v�lido: 1 - 5000)
   */
  this.setImpressoras = function (aId, aDescr) {

    this.aImpressoraCod   = aId;
    this.aImpressoraDescr = aDescr;

  }
  
  /**
   * M�todo que seta as dimens�es da p�gina para visualiza��o.
   * @param {int} iLinhas N�mero de linhas de cada p�gina.(Intervalo v�lido: 1 - 2000)
   * @param {int} iColunas N�mero de colunas de cada linha da p�gina. (Intervalo v�lido: 1 - 5000)
   */
  this.setDimensoes = function (iLinhas, iColunas) {

    if (this.validaDimensoesPagina(iLinhas, iColunas)) {
      
      if (iLinhas != undefined) {
        this.iLinhasPagina  = parseInt(iLinhas, 10);
      }
      if (iColunas != undefined) {
        this.iColunasPagina = parseInt(iColunas, 10);
      }

    }

  }

  /**
   * M�todo que cria e exibe uma p�gina.
   * @param {string} sTexto Texto a ser exibido na p�gina.
   */
  this.criaPagina = function (sTexto) {

    var oPre = document.createElement('pre');
    oPre.setAttribute('name', 'prePagina');
    oPre.setAttribute('class', 'prePagina');
    oPre.innerHTML = sTexto;
  
    var oDiv = document.createElement('div');
    oDiv.setAttribute('name', 'divPagina');
    oDiv.className = 'pagina';
    
    oDiv.appendChild(oPre);
    
    var oBr = document.createElement('br');
    oBr.setAttribute('name', 'brPagina');
    
    var oJanelaVisualizacao = document.getElementById(this.sNomeJanelaVisualizacao);
    oJanelaVisualizacao.appendChild(oDiv);
    oJanelaVisualizacao.appendChild(oBr);

  }

  /**
   * M�todo que deleta todas as p�ginas criadas (tira da visualiza��o apenas).
   */
  this.deletaPaginas = function () {

    var oJanelaVisualizacao = document.getElementById(this.sNomeJanelaVisualizacao);
    
    var aDivPaginas         = document.getElementsByName('divPagina');
    var aBrs                = document.getElementsByName('brPagina');
    
    /* Apago as paginas */
    while (aDivPaginas.length > 0) {
      oJanelaVisualizacao.removeChild(aDivPaginas[0]);
    }
    /* Apago os br */
    while (aBrs.length > 0) {
      oJanelaVisualizacao.removeChild(aBrs[0]);  
    }

  }
  
  /**
   * M�todo que valida as dimens�es da p�ginas. Limites: 1 - 2000 linhas e 1 - 5000 colunas.
   * @param iLinhas N�mero de linhas. Se n�o informado, ou informado como undefined, pega o valor da classe. 
   * @param iColunas N�mero de colunas. Se n�o informado, ou informado como undefined, pega o valor da classe. 
   * @return true em caso de dimens�es v�lidas, false em caso contr�rio.
   */
  this.validaDimensoesPagina = function (iLinhas, iColunas) {
    
    var iLinhas  = iLinhas == undefined ? this.iLinhasPagina : iLinhas;
    var iColunas = iColunas == undefined ? this.iColunasPagina : iColunas;

    if (iLinhas == undefined) {
    
      alert('Informe o n�mero de linhas.');
      return false;
      
    }
  
    if (iColunas == undefined) {
    
      alert('Informe o n�mero de colunas.');
      return false;
      
    }
    
    iLinhas  = parseInt(iLinhas, 10);
    iColunas = parseInt(iColunas, 10);
    
    if (iLinhas < 1) {
    
      alert('O n�mero de linhas n�o pode ser menor que 1.');
      return false;
      
    }
    
    if (iColunas < 1) {
    
      alert('O n�mero de colunas n�o pode ser menor que 1.');
      return false;
      
    }
    
    if (iLinhas > 2000) {
    
      alert('O n�mero de linhas n�o pode ser maior que 2000.');
      return false;
      
    }
    
    if (iColunas > 5000) {
    
      alert('O n�mero de colunas n�o pode ser maior que 5000.');
      return false;
      
    }

    return true;
  
  }
  
  /*
   * M�todo que exibe todas as p�ginas de todos os arquivos.
   */
  this.renderizarArquivos = function () {

    if (!this.validaDimensoesPagina()) {
      return false;
    }

    this.deletaPaginas();

    var aElementos = document.getElementsByName('textoArquivo'+this.sIdElementoPai);
    var aArquivos  = new Array();

    /* Obtenho os valores dos textareas para obter os conte�dos dos arquivos */
    for (var iCont = 0; iCont < aElementos.length; iCont++) {
      aArquivos[iCont] = aElementos[iCont].value;
    }

    for (var iCont = 0; iCont < aArquivos.length; iCont++) {

      /* Laco que trata o tamanho das linhas do arquivo (n�mero de colunas) */
      aLinhas = aArquivos[iCont].split("\n");
      for (var iCont2 = 0; iCont2 < aLinhas.length; iCont2++) {
  
        if (aLinhas[iCont2].length <= this.iColunasPagina) { // Linha n�o extrapola o limite de colunas
        
          aLinhas[iCont2] += this.sPad.substr(0, this.iColunasPagina - aLinhas[iCont2].length);
          
        } else { // Linha ultrapassa o limite de colunas
          
          var aTmp = new Array(); // Array de linhas de overflow
          var iIni = 0;
          /* Coloco em cada posicao de aTmp, o conteudo que vai ficar em cada linha, repeitando o limite */
          for (; iIni < aLinhas[iCont2].length; iIni += this.iColunasPagina) {
            
            aTmp[aTmp.length] = aLinhas[iCont2].substr(iIni, this.iColunasPagina);
  
          }
          
          /* Preencho a �ltima linha com os espacos em branco */
          aTmp[aTmp.length - 1] += this.sPad.substr(0, iIni - aLinhas[iCont2].length);
          
          /* Substituo a linha pelo seu trecho inicial de iColunas caracteres */
          aLinhas.splice(iCont2, 1, aTmp[0]);
          /* Adiciono as demais linhas geradas (overflow) para obedecer o limite de colunas */
          for (var iCont3 = 1; iCont3 < aTmp.length; iCont3++) {
          
            iCont2++; // Movo para a proxima posicao (linha de overflow)
            aLinhas.splice(iCont2, 0, aTmp[iCont3]);
            
          }
          
        } // Fim do else (linha ultrapassa o limite de colunas)
      
      }
      
      /* Exibo as p�ginas, respeitando o n�mero m�ximo de linhas em cada p�gina */
      var sTmp       = ""; // Texto de cada p�gina
      var iLinhasTmp = 0; // N�mero da linha na p�gina
      /* Obtenho o texto que ir� ficar em cada p�gina */
      for (iCont2 = 0; iCont2 < aLinhas.length; iCont2++) {
        
        sTmp += aLinhas[iCont2]+"\n";
        iLinhasTmp++;
        if (iLinhasTmp == this.iLinhasPagina) { // Atingiu o limite de linhas
        
          /* Crio / exibo uma p�gina */
          this.criaPagina(sTmp);
          /* Zero as vari�veis */
          sTmp       = "";
          iLinhasTmp = 0;
          
        }
        
      } // endfor
      
      if (iLinhasTmp != 0) { // Preencho a �ltima p�gina com linhas em branco (\n)
        
        /* La�o que insere as linhas em branco para exibir a pagina em seu numero de linhas definido */
        for (var iCont2 = iLinhasTmp; iCont2 < this.iLinhasPagina; iCont2++) {
      
          sTmp += "\n";
      
        }
        this.criaPagina(sTmp);
        
      } // fim do if que trata do n�mero de linhas da �ltima p�gina
      
    } // fim do for que renderiza todos os arquivos
  
  } // fim da funcao renderizaPaginas

  this.gerarVisualizador = function(){

    var oTxtLinhas = document.createElement('span');
    oTxtLinhas.innerHTML = "N�mero de linhas: ";
    oTxtColunas = document.createElement('span');
    oTxtColunas.innerHTML = " N�mero de colunas: ";
    var oInpLinhas = document.createElement('input');
    oInpLinhas.setAttribute('type', 'text');
    oInpLinhas.setAttribute('name', 'iLinhas');
    oInpLinhas.setAttribute('id', 'iLinhas');
    oInpLinhas.setAttribute('size', '4');
    oInpLinhas.setAttribute('maxlength', '4');
    oInpLinhas.setAttribute('value', this.iLinhasPagina);
    oInpLinhas.onchange = function () {

      oVisualizador.renderizarArquivos(oVisualizador.setDimensoes(this.value, 
                                                                  document.getElementById('iColunas').value
                                                                 )
                                      );

    }
    var oInpColunas = document.createElement('input');
    oInpColunas.setAttribute('type', 'text');
    oInpColunas.setAttribute('name', 'iColunas');
    oInpColunas.setAttribute('id', 'iColunas');
    oInpColunas.setAttribute('size', '4');
    oInpColunas.setAttribute('maxlength', '4')
    oInpColunas.setAttribute('value', this.iColunasPagina);
    oInpColunas.onchange = function () {

    oVisualizador.renderizarArquivos(oVisualizador.setDimensoes(document.getElementById('iLinhas').value, 
                                                                 this.value
                                                               )
                                    );

    }

    var oElementoPai = document.getElementById(this.sIdElementoPai);
    oElementoPai.appendChild(oTxtLinhas);
    oElementoPai.appendChild(oInpLinhas);
    oElementoPai.appendChild(oTxtColunas);
    oElementoPai.appendChild(oInpColunas);

    /* Bot�es */
    
    if(this.aImpressoraCod.length > 0){

      var oImpres = document.createElement('select');
      oImpres.setAttribute('name', 'impressora');
      oImpres.setAttribute('id', 'impressora');

    }
    
    /* Imprimir */
    var oImp = document.createElement('input');
    oImp.setAttribute('type', 'button');
    oImp.setAttribute('name', 'imprimir');
    oImp.setAttribute('id', 'imprimir');
    oImp.setAttribute('value', 'Imprimir');
    oImp.onclick = function () {

      var oParam          = new Object();
      oParam.exec         = 'imprimeArquivoTXT';
      oParam.sSessionNome = $F('sSessionNome');
      oSel = document.getElementById('impressora');
      if (oSel != null && oSel != undefined) {
        oParam.idImpressora = oSel.options[oSel.selectedIndex].value;
      }
      js_webajax(oParam, 'retornoImprimeArquivoTXT', me.sUrlRpc);

    }

    /* Download */
    var oDown = document.createElement('input');
    oDown.setAttribute('type', 'button');
    oDown.setAttribute('name', 'download');
    oDown.setAttribute('id', 'download');
    oDown.setAttribute('value', 'Download');
    oDown.onclick = function () {

      var oParam          = new Object();
      oParam.exec         = 'salvarArquivoTXT';
      oParam.sSessionNome = $F('sSessionNome');
      js_webajax(oParam, 'retornoSalvarArquivoTXT', me.sUrlRpc);

    }

    /* Fechar */
    var oFechar = document.createElement('input');
    oFechar.setAttribute('type', 'button');
    oFechar.setAttribute('name', 'fechar');
    oFechar.setAttribute('id', 'fechar');
    oFechar.setAttribute('value', 'Fechar');
    oFechar.onclick = function () {
      parent.db_iframe_visualizador.hide();
    }

    var sEspacos = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

    var oCenter = document.createElement('center');
    oCenter.appendChild(document.createElement('br'));

    if(this.aImpressoraCod.length > 0){
    
      var oTxtImpres = document.createElement('span');
      oTxtImpres.innerHTML = "Impressora:";
      oCenter.appendChild(oTxtImpres);
      oCenter.appendChild(oImpres);
      oS = document.createElement('span');
      oS.innerHTML = sEspacos;
      oCenter.appendChild(oS);
    
    }
    
    oCenter.appendChild(oImp);

    oS = document.createElement('span');
    oS.innerHTML = sEspacos;
    oCenter.appendChild(oS);

    oCenter.appendChild(oDown);

    oS = document.createElement('span');
    oS.innerHTML = sEspacos;
    oCenter.appendChild(oS);

    oCenter.appendChild(oFechar);
    oElementoPai.appendChild(oCenter);

    var oSel = document.getElementById('impressora');
    var iTam = this.aImpressoraCod.length;
    for (var iInd = 0; iInd < iTam; iInd++) {

      var oOpt   = document.createElement('option');
      oOpt.text  = this.aImpressoraDescr[iInd].urlDecode();
      oOpt.value = this.aImpressoraCod[iInd];
      if (this.iIpPadrao == this.aImpressoraCod[iInd]) {
        oOpt.defaultSelected = true;
      }
      oSel.add(oOpt, null);

    }

  }
}
function retornoImprimeArquivoTXT(oAjax) {

  oRetorno = eval("("+oAjax.responseText+")");
  message_ajax(oRetorno.sMessage.urlDecode());

}
function retornoSalvarArquivoTXT(oAjax) {

  oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.istatus == 2) {
    message_ajax(oRetorno.sMessage.urlDecode());
  } else {

    sArquivo  = 'db_download.php?arquivo='+oRetorno.sNomeArquivo;
    var WindowObjectReference;
    var strWindowFeatures = "menubar=yes,location=no,resizable=yes,scrollbars=yes,status=yes";
    WindowObjectReference = window.open(sArquivo,"CNN_WindowName", strWindowFeatures);

  }

}
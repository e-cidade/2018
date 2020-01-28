/** 
 * @fileoverview Esse arquivo define uma classe para gerar um visualizador para impressão de
 * arquivos texto, sendo permitido definir o número de linhas e colunas para poder visualizar
 * como a impressão irá ficar. Vários arquivos texto podem ser visualizados ao mesmo tempo.
 * Quando um arquivo termina, o outro começa na próxima página.
 *
 * @author Tony Farney Bruck Mendes Ribeiro tony.farney@dbseller.com.br
 * @version  $Revision: 1.1 $
 *
 * Exemplo de uso:
 *
 *  <script>
 *  
 *  </script>                                                  
 */

/**
 * @class Classe que gera um visualizador para impressão de arquivos texto, sendo permitido definir o 
 * número de linhas e colunas para poder visualizar como a impressão irá ficar.
 * @constructor
 * Obs: Este objeto tem que possuir escopo global no javascript.
 * @param string sIdElementoPai ID do elemento pai (elemento onde será appended o visualizador)
 * @param string sAltura Altura do visualizador. Pode ser em %, px 
 * ou qualquer outra unidade aceita pelo estilo css "height".
 * @param string sLargura Largura do visualizador. Pode ser em %, px 
 * ou qualquer outra unidade aceita pelo estilo css "width".
 * @param string sNomeJanelaVisualizacao Nome e ID da div de visualização.
 */
function DBVisualizadorImpressaoTexto(sIdElementoPai, sAltura, sLargura, sNomeJanelaVisualizacao) {

  /**
  * Número de linhas que cada página suporta.
  * @type int;
  * @private
  */ 
  this.iLinhasPagina = 65;

  /**
  * Número de colunas que cada linha suporta.
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
  * Número de arquivos adicionados para visualização.
  * @type int;
  * @private
  */ 
  this.iNumArquivos = 0;

  /**
  * ID do elemento pai da div de visualização.
  * @type string;
  * @private
  */ 
  this.sIdElementoPai = sIdElementoPai == undefined ? 'visualizador' : sIdElementoPai;

  /**
  * Nome da janela de visualização (div) que contém todas as páginas.
  * @type string;
  * @private
  */ 
  this.sNomeJanelaVisualizacao = sNomeJanelaVisualizacao == undefined ? 'janelaVisualicacao' : sNomeJanelaVisualizacao;

  /**
  * Variável com 5050 espaços em branco utilizada para fazer o padding no final de cada linha.
  * @type string;
  * @private
  */ 
  this.sPad = new Array(5050).join(" ");
  
  /**
   * Método que cria a div de visualização e anexa ela ao elemento pai.
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
   * Método que adiciona arquivo para visualizar.
   * @param string sTexto Conteúdo do arquivo (string)
   */
  this.addArquivo = function (sTexto) {

    this.iNumArquivos++;
    oTextArea = document.createElement('textarea');
    oTextArea.setAttribute('name', 'textoArquivo');
    oTextArea.setAttribute('id', 'textoArquivo'+this.iNumArquivos);
    oTextArea.className = 'textareaTextoArquivo';
    oTextArea.value     = sTexto;
    document.getElementById(this.sNomeJanelaVisualizacao).appendChild(oTextArea);
   
  }

  /**
   * Método que seta as dimensões da página para visualização.
   * @param {int} iLinhas Número de linhas de cada página.(Intervalo válido: 1 - 2000)
   * @param {int} iColunas Número de colunas de cada linha da página. (Intervalo válido: 1 - 5000)
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
   * Método que cria e exibe uma página.
   * @param {string} sTexto Texto a ser exibido na página.
   */
  this.criaPagina = function (sTexto) {

    oPre = document.createElement('pre');
    oPre.setAttribute('name', 'prePagina');
    oPre.innerHTML = sTexto;
  
    oDiv = document.createElement('div');
    oDiv.setAttribute('name', 'divPagina');
    oDiv.className = 'pagina';
    
    oDiv.appendChild(oPre);
    
    oBr = document.createElement('br');
    oBr.setAttribute('name', 'brPagina');
    
    oJanelaVisualizacao = document.getElementById(this.sNomeJanelaVisualizacao);
    oJanelaVisualizacao.appendChild(oDiv);
    oJanelaVisualizacao.appendChild(oBr);

  }

  /**
   * Método que deleta todas as páginas criadas (tira da visualização apenas).
   */
  this.deletaPaginas = function () {

    oJanelaVisualizacao = document.getElementById(this.sNomeJanelaVisualizacao);
    
    aDivPaginas         = document.getElementsByName('divPagina');
    aBrs                = document.getElementsByName('brPagina');
    
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
   * Método que valida as dimensões da páginas. Limites: 1 - 2000 linhas e 1 - 5000 colunas.
   * @param iLinhas Número de linhas. Se não informado, ou informado como undefined, pega o valor da classe. 
   * @param iColunas Número de colunas. Se não informado, ou informado como undefined, pega o valor da classe. 
   * @return true em caso de dimensões válidas, false em caso contrário.
   */
  this.validaDimensoesPagina = function (iLinhas, iColunas) {
    
    iLinhas  = iLinhas == undefined ? this.iLinhasPagina : iLinhas;
    iColunas = iColunas == undefined ? this.iColunasPagina : iColunas;

    if (iLinhas == undefined) {
    
      alert('Informe o número de linhas.');
      return false;
      
    }
  
    if (iColunas == undefined) {
    
      alert('Informe o número de colunas.');
      return false;
      
    }
    
    iLinhas  = parseInt(iLinhas, 10);
    iColunas = parseInt(iColunas, 10);
    
    if (iLinhas < 1) {
    
      alert('O número de linhas não pode ser menor que 1.');
      return false;
      
    }
    
    if (iColunas < 1) {
    
      alert('O número de colunas não pode ser menor que 1.');
      return false;
      
    }
    
    if (iLinhas > 2000) {
    
      alert('O número de linhas não pode ser maior que 2000.');
      return false;
      
    }
    
    if (iColunas > 5000) {
    
      alert('O número de colunas não pode ser maior que 5000.');
      return false;
      
    }

    return true;
  
  }
  
  /*
   * Método que exibe todas as páginas de todos os arquivos.
   */
  this.renderizarArquivos = function () {

    if (!this.validaDimensoesPagina()) {
      return false;
    }
    
    this.deletaPaginas();

    var aElementos = document.getElementsByName('textoArquivo');
    var aArquivos  = new Array();

    /* Obtenho os valores dos textareas para obter os conteúdos dos arquivos */
    for (var iCont = 0; iCont < aElementos.length; iCont++) {
    
      aArquivos[iCont] = aElementos[iCont].value;
      
    }
      
    for (var iCont = 0; iCont < aArquivos.length; iCont++) {
  
      /* Laco que trata o tamanho das linhas do arquivo (número de colunas) */
      aLinhas = aArquivos[iCont].split("\n");
      for (var iCont2 = 0; iCont2 < aLinhas.length; iCont2++) {
  
        if (aLinhas[iCont2].length <= this.iColunasPagina) { // Linha não extrapola o limite de colunas
        
          aLinhas[iCont2] += this.sPad.substr(0, this.iColunasPagina - aLinhas[iCont2].length);
          
        } else { // Linha ultrapassa o limite de colunas
          
          var aTmp = new Array(); // Array de linhas de overflow
          var iIni = 0;
          /* Coloco em cada posicao de aTmp, o conteudo que vai ficar em cada linha, repeitando o limite */
          for (; iIni < aLinhas[iCont2].length; iIni += this.iColunasPagina) {
            
            aTmp[aTmp.length] = aLinhas[iCont2].substr(iIni, this.iColunasPagina);
  
          }
          
          /* Preencho a última linha com os espacos em branco */
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
      
      /* Exibo as páginas, respeitando o número máximo de linhas em cada página */
      var sTmp       = ""; // Texto de cada página
      var iLinhasTmp = 0; // Número da linha na página
      /* Obtenho o texto que irá ficar em cada página */
      for (iCont2 = 0; iCont2 < aLinhas.length; iCont2++) {
        
        sTmp += aLinhas[iCont2]+"\n";
        iLinhasTmp++;
        if (iLinhasTmp == this.iLinhasPagina) { // Atingiu o limite de linhas
        
          /* Crio / exibo uma página */
          this.criaPagina(sTmp);
          /* Zero as variáveis */
          sTmp       = "";
          iLinhasTmp = 0;
          
        }
        
      } // endfor
      
      if (iLinhasTmp != 0) { // Preencho a última página com linhas em branco (\n)
        
        /* Laço que insere as linhas em branco para exibir a pagina em seu numero de linhas definido */
        for (var iCont2 = iLinhasTmp; iCont2 < this.iLinhasPagina; iCont2++) {
      
          sTmp += "\n";
      
        }
        this.criaPagina(sTmp);
        
      } // fim do if que trata do número de linhas da última página
      
    } // fim do for que renderiza todos os arquivos
  
  } // fim da funcao renderizaPaginas

}

require_once("scripts/widgets/datagrid/plugins/DBHint.plugin.js");
require_once("scripts/widgets/datagrid/plugins/DBSelecionaLinha.plugin.js");
/**
 * View que permite a sele��o de institui��o de acordo com o usu�rio
 *
 * @param {String} sNomeInstancia           Nome da Instancia
 * @param {HTMLElement} oContainerDestino  Objeto que ser� adicionado o componente
 * @constructor
 *
 * @example
 * var oViewInstituicao = new DBViewInstituicao('oViewInstituicao', $('ctnDestino'));
 * oViewInstituicao.setLegenda("Legenda do Fieldset"); (opcional)
 * oViewInstituicao.apresentarNomeAbreviado(true); (opcional)
 * oViewInstituicao.show();
 *
 */
DBViewInstituicao = function(sNomeInstancia, oContainerDestino) {

  this.sNomeInstancia    = sNomeInstancia;
  this.sUrlRPC           = "con4_instituicao.RPC.php";
  this.aInstituicoes     = [];
  this.oContainerDestino = oContainerDestino;
  this.sLegenda          = "Institui��es";
  this.iWidth            = 500;
  this.iHeight           = 200;
  this.lNomeAbreviado    = false;
  this.iTotalInstituicao = 0;
  this.aCallBackClickLinha=[];

  this.show = function () {


    var oLegend       = document.createElement('legend');
    oLegend.id        = "legend_"+this.sNomeInstancia;
    oLegend.innerHTML = "<b>"+this.sLegenda+"</b>";

    var oFieldset = document.createElement('fieldset');
    oFieldset.style.width = this.iWidth+"px";
    oFieldset.appendChild(oLegend);

    var oContainerGrid = document.createElement('div');
    oContainerGrid.id  = 'ctnDBViewGridInstituicao';

    oFieldset.appendChild(oContainerGrid);

    this.oContainerDestino.appendChild(oFieldset);

    this.montarGrid();

  };


  /**
   * Seta uma descri��o para a legenda do fieldset
   * @param sLegenda
   */
  this.setLegenda = function (sLegenda) {
    this.sLegenda = sLegenda;
  };

  /**
   * Seta o tamanho horizontal em pixels.
   * @param iWidth
   */
  this.setWidth = function (iWidth) {
    this.iWidth = iWidth;
  };

  /**
   * Seta o tamanho vertical em pixels
   * @param iHeight
   */
  this.setHeight = function (iHeight) {
    this.iHeight = iHeight;
  };

  /**
   * Apresentar nome abreviado
   * @param lNomeAbreviado
   */
  this.apresentarNomeAbreviado = function (lNomeAbreviado) {
    this.lNomeAbreviado = lNomeAbreviado;
  };

  /**
   * Retorna o total de instituicoes cadastradas
   * @returns {number}
   */
  this.getTotalInstituicoes = function() {
    return this.iTotalInstituicao;
  }

};


/**
 * Carrega as institui��es cadastradas no sistema e armazena estas no array aInstituicoes
 */
DBViewInstituicao.prototype.carregarInstituicoes = function () {

  js_divCarregando("Aguarde, carregando institui��es...", "msgBox");

  var oParam = {"exec" : "getInstituicoes"};

  var self = this;
  new Ajax.Request(this.sUrlRPC,
                  {method: 'post',
                    asynchronous: false,
                    parameters: 'json='+Object.toJSON(oParam),
                    onComplete: function(oAjax) {

                      js_removeObj("msgBox");
                      var oRetorno = eval("("+oAjax.responseText+")");
                      self.aInstituicoes = oRetorno.aInstituicoes;
                    }
                  });
};

/**
 * Constr�i a grid onde ser�o mostradas as institui��es
 */
DBViewInstituicao.prototype.montarGrid = function () {

  this.oGridInstituicao  = new DBGrid("oGridInstituicao");
  this.oGridInstituicao.nameInstance = this.sNomeInstancia+".oGridInstituicao";
  this.oGridInstituicao.setCheckbox(0);
  this.oGridInstituicao.setHeight(this.iHeight);
  this.oGridInstituicao.setCellWidth(['0%', '100%']);
  this.oGridInstituicao.setCellAlign(["center", "left"]);
  this.oGridInstituicao.setHeader(["C�digo", "Institui��o"]);
  this.oGridInstituicao.aHeaders[1].lDisplayed = false;
  this.oGridInstituicao.show($('ctnDBViewGridInstituicao'));
  this.oGridInstituicao.setStatus("Clique sobre a linha para selecionar.");
  this.carregarInstituicoes();
  this.preencherGrid();
};

/**
 * Preenche a grid com as institui��es encontradas no sistema
 */
DBViewInstituicao.prototype.preencherGrid = function () {

  var self = this;
  this.oGridInstituicao.clearAll(true);
  this.aInstituicoes.each(function (oInstituicao, iIndice) {

    var lMarcarCheckBox = false;
    if (oInstituicao.lSelecionado) {
      lMarcarCheckBox = true;
    }

    var aLinha = [];
    aLinha[0]  = oInstituicao.iCodigo;
    aLinha[1]  = oInstituicao.iCodigo + " - ";
    aLinha[1] += self.lNomeAbreviado === true ? oInstituicao.sNomeAbreviado.urlDecode() : oInstituicao.sNomeCompleto.urlDecode();
    self.oGridInstituicao.addRow(aLinha, false, false, lMarcarCheckBox);
    sObjetoInstituicao = JSON.stringify(oInstituicao);

    /*
     * Adicionado evento quando clicado na c�lula 2
     */
    self.oGridInstituicao.aRows[iIndice].aCells[2].adicionarEvento(
      "onclick", 
      self.sNomeInstancia+".clickLinha("+sObjetoInstituicao+", "+iIndice+")"
    );

    self.iTotalInstituicao++;
  });
  this.oGridInstituicao.renderRows();

  /**
   * Hints para facilitar a identifica��o quando o nome da institui��o for maior que o esperado.
   */
  this.aInstituicoes.each(function (oInstituicao, iIndice) {

    self.oGridInstituicao.setHint(iIndice, 1, oInstituicao.sNomeCompleto.urlDecode());
    self.oGridInstituicao.setHint(iIndice, 2, oInstituicao.sNomeCompleto.urlDecode());
  });
};

DBViewInstituicao.prototype.clickLinha = function (oItem, iIndice) {

  this.oGridInstituicao.selecionarLinha(oItem.iCodigo, iIndice);
  if(this.aCallBackClickLinha.length > 0) {
    this.aCallBackClickLinha.each( function(callBackClick) {
      callBackClick(iIndice, oItem.iCodigo, oItem);
    });
  }
};

DBViewInstituicao.prototype.addCallBackClickLinha = function (callBackClick) {

  if(typeof callBackClick == 'function') {
    this.aCallBackClickLinha.push(callBackClick);
  }
};

/**
 * Retorna um array de objeto contendo o c�digo e nome das institui��es selecionadas
 * 
 * @returns {Array}
 * @param {boolean} lSomenteCodigo - Indica se o retorno deve ser somente o c�digo ou o objeto com codigo e nome
 */
DBViewInstituicao.prototype.getInstituicoesSelecionadas = function (lSomenteCodigo) {

  var lRetornarCodigo = false;
  if (lSomenteCodigo != undefined) {
    lRetornarCodigo = lSomenteCodigo;
  }

  var aGridSelecionadas = this.oGridInstituicao.getSelection();
  var iTotalSelecionada = aGridSelecionadas.length;
  var aInstituicoesSelecionadas = [];
  for (var i = 0; i < iTotalSelecionada; i++) {

    if (lRetornarCodigo) {

      aInstituicoesSelecionadas.push(aGridSelecionadas[i][1]);
    } else {

      var aNomeInstituicao = aGridSelecionadas[i][2].split("-");
      var oInstituicao     = {"codigo" : aGridSelecionadas[i][1], "nome" : aNomeInstituicao[1].trim()};
      aInstituicoesSelecionadas.push(oInstituicao);
    }
  }

  return aInstituicoesSelecionadas;
};

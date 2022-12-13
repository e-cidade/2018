require_once('estilos/DBContextComplete.css');
/**
 * Cria um menu de contexto com opções
 * @param sId
 * @constructor
 */
function DBContextComplete(sId) {

  this.sId          = sId;
  /**
   * Lista de Opções que serao mostradas ao usuario
   * @type {Array}
   */
  this.aListaOpcoes = [];

  /**
   * COntrole do Grupo
   * @type {Array}
   */
  this.aGrupos = [];

  /**
   * Controle da lista de opções aberta/fechada
   * @type {boolean}
   */
  this.lListaAberta = false;

  /**
   * Elemento que dispara a invocao do contexto
   * @type {HTMLElement}
   */
  this.oElementContext = null;

  /**
   * Classe css para definir a opçcao selecionado   *
   * @type {string}
   */
  this.sClassNameItemSelecionado = "DBContext-item-selected";

  /**
   * Lista HTML copm os itens
   * @type {HTMLElement}
   */
  this.oLista = null;

  /**
   * Lista de Invocadores
   * @type {Array}
   */
  this.aInvokers = [];

  /**
   * ULtimo caractere digitado
   * @type {string}
   */
  this.sUltimoCaractereDigitado = '';

  /**
   * Elemento Principal do Context
   * @type {HTMLElement}
   */
  this.oDivLista               = document.createElement("div");
  this.oDivLista.id            = 'divVariaveis'+this.sId;
  this.oDivLista.tabIndex      = 99;
  this.oDivLista.style.display = 'none';
  this.oDivLista.className     = 'DBContext-div';

  /**
   * Adiciona uma string Antes da variavel
   * @type {string}
   */
  this.sPrependString = '';
}


/**
 * String que será Adicionada antes da variavel selecionadada
 * @param sString
 */
DBContextComplete.prototype.setPrependString = function (sString) {
  this.sPrependString = sString;
};

/**
 * Define qual elemento o Contexto poderá ser chamado
 * @param {HTMLElement} oElement elemento html
 */
DBContextComplete.prototype.setElementForContext = function(oElement) {

  this.oElementContext = oElement;
  oElement.parentNode.appendChild(this.oDivLista);
};

/**
 * INicia o componente
 */
DBContextComplete.prototype.init = function() {

  var oSelf = this;
  this.oDivLista.observe('keydown', function(event) {
    oSelf.initkeyBoardEvents(event);
  });

  this.oElementContext.observe('keydown', function(event) {

    if ((event.ctrlKey && event.which == 32)) {

      var sValue                     = oSelf.oElementContext.value;
      oSelf.sUltimoCaractereDigitado = sValue.substr(sValue.length -1, 1);
      oSelf.showList();
    }
  });
  this.closeOnEsc();
};

/**
 * @private
 */
DBContextComplete.prototype.showList = function() {

  this.oDivLista.style.position  = 'absolute';
  this.oDivLista.style.left      = getPageOffsetLeft(this.oElementContext);
  this.oDivLista.style.width     = this.oElementContext.clientWidth;
  this.oDivLista.style.top       = getPageOffsetTop(this.oElementContext) +this.oElementContext.clientHeight;
  this.oDivLista.style.height    = '300px';
  this.oDivLista.style.overflow  = 'hidden';
  this.oDivLista.style.display   = '';
  this.oDivLista.focus();
  this.createList();
};



/**
 * Inicia os eventos de Teclado no Evento
 */
DBContextComplete.prototype.initkeyBoardEvents = function(event) {

  var oSelf          = this;
  var oNoSelecionado = getElementsByClass(oSelf.sClassNameItemSelecionado)[0];
  var lMover         = false;
  var oProximoNo     = '';
  switch (event.which) {

    case KEY_ENTER:

      if (!oNoSelecionado) {
        return ;
      }
      oSelf.fillValue(oNoSelecionado.innerHTML);
      event.preventDefault();
      break;

    case KEY_DOWN:

      oProximoNo = oNoSelecionado.nextSibling;
      lMover     = true;
      break;

    case KEY_UP:

      oProximoNo = oNoSelecionado.previousSibling;
      lMover     = true;
      break;
  }

  if (lMover && oProximoNo) {

    oNoSelecionado.className = '';
    oProximoNo.className     = oSelf.sClassNameItemSelecionado;
    oProximoNo.scrollIntoView();

  }
};

/**
 * Adiciona uma opção a lista de itens
 * @param {string} sValue valor do texto
 * @param {string} sLabel label de Ajuda
 */
DBContextComplete.prototype.addOption = function(sValue, sLabel, sGrupo) {

  if (sLabel == null) {
    sLabel = sValue;
  }
  if (sGrupo != null) {

    this.aGrupos[sGrupo].itens.push({value:sValue, label: sLabel});
    return ;
  }
  this.aListaOpcoes.push({value:sValue, label: sLabel});
};

/**
 * Adiciona uma coleção de opções ao contexto
 * cada item da colecao devera ser um Objeto com as propriedades value e label
 * @param {Array} aOptions
 */
DBContextComplete.prototype.addOptions = function(aOptions, sGrupo) {

  var oSelf = this;
  aOptions.each(function(oOption, iSeq) {
    oSelf.addOption(oOption.value, oOption.label, sGrupo);
  });
};

/**
 * Cria lista de opções que podem ser Selecionadas
 * @private
 */
DBContextComplete.prototype.createList = function() {

  var oSelf    = this;
  var sInvoker = oSelf.sUltimoCaractereDigitado;
  if (oSelf.oLista != null) {
    oSelf.oDivLista.removeChild(oSelf.oLista);
  }
  this.oLista      = document.createElement("ul");
  var aItensToShow = this.aListaOpcoes;
  if (sInvoker.trim() != '') {
    aItensToShow = oSelf.getItensByInvoker(sInvoker);
  }

  if (sInvoker == oSelf.setPrependString || aItensToShow.length == 0) {
    aItensToShow = this.aListaOpcoes;
  }
  aItensToShow.each(function(oOption, iSeq) {
    oSelf.itemToElement(oOption, iSeq == 0);
  });
  this.oDivLista.appendChild(this.oLista);
};

/**
 * Converte um item para uma lista HTML
 * @private
 * @param oOption item para ser convertido
 * @param lSelect trazer como selecionado
 */
DBContextComplete.prototype.itemToElement = function(oOption, lSelect) {

  var oSelf      = this;
  var oItemLista = document.createElement("li");
  oItemLista.display   = 'block';
  oItemLista.innerHTML = oOption.value;
  oItemLista.title     = oOption.label;
  if (lSelect) {
    oItemLista.className = this.sClassNameItemSelecionado;
  }
  oItemLista.observe('click', function () {
    oSelf.fillValue(oOption.value);
  });

  oItemLista.observe('mouseover', function () {
    oItemLista.className = oSelf.sClassNameItemSelecionado;
  });

  oItemLista.observe('mouseout', function () {
    oItemLista.className = '';
  });
  oSelf.oLista.appendChild(oItemLista);
};

/**
 * fecha a lista de variaveis
 */
DBContextComplete.prototype.close = function() {

  this.oDivLista.style.display = 'none';
  this.lListaAberta            = false;
  this.oElementContext.focus();

};
/**
 * Adicionar atalho para fechar a janela com ESC
 * @private
 */
DBContextComplete.prototype.closeOnEsc = function() {

  var oSelf = this;
  this.oElementContext.observe('keydown', function(event) {
    if (event.which == ESC) {
      oSelf.close();
    }
  });
};

/**
 * Seta o valor da variavel no elemento de Contexto
 * @private
 */
DBContextComplete.prototype.fillValue = function(sValue) {

  var sValorAdicionar = this.sPrependString+sValue;
  if (this.sUltimoCaractereDigitado.trim() != '') {
    sValorAdicionar = sValue;
  }
  this.oElementContext.value += sValorAdicionar;
  this.oElementContext.focus();
  this.close();

}

/**
 * Seta o valor da variavel no elemento de Contexto
 * @private
 */
DBContextComplete.prototype.addGroup = function(sName, sKeyInvoker) {

  if (!this.aGrupos[sName]) {
    this.aGrupos[sName] = {itens:[]};
  }

  this.aGrupos[sName].sKey = sKeyInvoker;
  this.aInvokers.push(sKeyInvoker);
};

DBContextComplete.prototype.getItensByInvoker = function(sKeyInvoker) {

  for (sGrupo in this.aGrupos) {
    if (typeof(sGrupo) ==  'function') {
      continue;
    }
    if (this.aGrupos[sGrupo].sKey == sKeyInvoker) {
      return this.aGrupos[sGrupo].itens;
    }
  }
  return [];
}
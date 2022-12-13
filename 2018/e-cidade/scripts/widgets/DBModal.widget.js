var CONTEXT = this;

/**
 *  Cria uma modal
 *
 *  @constructor
 *  @return {Object} 
 */
DBModal = function() {

  this.oDBMask = {};

  this.dbMaskOptions = {};

  this.mContent = "";

  this.sTitle = "";

  /**
   * Div do cabecalho da modal
   * @type Element
   */
  this.oDivCabecalho = null;

  /**
   * Div do conteudo da modal
   * @type Element
   */
  this.oDivConteudo  = null;

  /**
   * Div do rodape da modal
   * @type Element
   */
  this.oDivRodape    = null;

  this.aButtons      = [{
    label: "Fechar",
    onclick: this.destroy.bind(this),
    disabled: false,
    type: "button"
  }];

  /**
   * Mapa de eventos implementados pela classe
   * @type {Object}
   */
  this.events = {
    beforeDestroy: function() {},
    afterDestroy: function() {}
  }

  DBModal.dependencies();

}

/**
 * Seta o conteudo da modal, caso ja esteja instanciada, somente muda o conteudo
 * @param string sContent
 */
DBModal.prototype.setContent = function(mContent) {
  this.mContent = mContent;

  if (this.oDivConteudo) {

    this.oDivConteudo.innerHTML = '';

    if (typeof this.mContent == "string") {
      this.oDivConteudo.innerHTML = this.mContent;
    } else {
      this.oDivConteudo.appendChild(this.mContent)
    }

  }
}

/**
 * Seta o titulo da modal, caso ja esteja instanciada, somente muda o titulo
 * @param string sTitle
 */
DBModal.prototype.setTitle = function(sTitle) {
  this.sTitle = sTitle;

  if (this.oDivCabecalho) {
    this.oDivCabecalho.querySelector('h1').innerHTML = sTitle;
  }
}

/**
 * Seta os botoes da modal, caso ja esteja instanciada, somente muda os botoes
 * @param Object[] aButtons
 */
DBModal.prototype.setButtons = function(aButtons) {
  this.aButtons = aButtons;

  if (this.oDivRodape) {

    var oDivContainer = this.oDivRodape.parentNode;

    var oDivRodape = this.__montaRodape();
    this.oDivRodape.remove();
    this.oDivRodape = oDivRodape;

    oDivContainer.appendChild(this.oDivRodape);

  }
}

/**
 * Método responsavel por mostrar a modal em tela
 */
DBModal.prototype.show = function() {

  this.oDBMask = new DBMask(this.dbMaskOptions);

  var oDivContainer = CONTEXT.document.createElement('div');
  oDivContainer.setAttribute('class', 'db-modal-container');


  this.oDivCabecalho = this.__montaCabecalho();

  this.oDivConteudo = CONTEXT.document.createElement('div');
  this.oDivConteudo.setAttribute('class', 'db-modal-content');

  if (typeof this.mContent == "string") {
    this.oDivConteudo.innerHTML = this.mContent;
  } else {
    this.oDivConteudo.appendChild(this.mContent);
  }


  this.oDivRodape = this.__montaRodape();

  oDivContainer.appendChild(this.oDivCabecalho)
  oDivContainer.appendChild(this.oDivConteudo)
  oDivContainer.appendChild(this.oDivRodape)

  this.oDBMask.getMaskElement().appendChild(oDivContainer);

}

/**
 * Método responsavel por montar todo o cabecalho do componente
 * @param  string sNomeUsuario Nome do usuario que irá aparecer no cabecalho
 * @return Element              Retorna o objeto da div do cabecalho
 */
DBModal.prototype.__montaCabecalho = function() {

  var _this            = this;

  var oDivCabecalho    = CONTEXT.document.createElement('div');
  oDivCabecalho.setAttribute('class', 'db-modal-header');

  var oDivTopo          = CONTEXT.document.createElement('div');
  oDivTopo.setAttribute('class', 'top');

  /**
   * Botao de fechar
   */
  var oSpanClose       = CONTEXT.document.createElement('span');
  oSpanClose.innerHTML = '&times;';
  oSpanClose.setAttribute('class', 'close');
  oSpanClose.onclick   = function() {
    _this.destroy(false);
  }
  oDivTopo.appendChild(oSpanClose)

  var oTitle             = CONTEXT.document.createElement('h1');
  oTitle.setAttribute('class', 'title');
  oTitle.innerHTML = this.sTitle;
  oDivTopo.appendChild(oTitle);

  /**
   * Junta tudo no final
   */
  oDivCabecalho.appendChild(oDivTopo);

  return oDivCabecalho;
}

/**
 * Método responsavel por montar o rodape com todo seu comportamento
 * @param  string sVersaoAnterior Versão anterior ao release-note atual
 * @param  string sProximaVersao  Proxima versão do release-note atual
 * @return Element Div pronta para ser adicionado ao container
 */
DBModal.prototype.__montaRodape = function() {

  var oDivRodape = CONTEXT.document.createElement('div');
  oDivRodape.setAttribute('class', 'db-modal-footer');

  for (var iIndexButtons = 0; iIndexButtons < this.aButtons.length; iIndexButtons++) {

    var oDadoButton = this.aButtons[iIndexButtons];

    var oButton = CONTEXT.document.createElement('a');
    oButton.setAttribute('class', oDadoButton.type || "button")
    oButton.innerHTML = oDadoButton.label;
    oButton.onclick = oDadoButton.onclick;

    if ( oDadoButton.disabled ) {
      oButton.setAttribute('disabled', true)
    }

    oDivRodape.appendChild(oButton)

  }


  return oDivRodape;
}

/**
 * Gerencia as dependencias do componente.
 */
DBModal.dependencies = function() {

  if ( !CONTEXT["require_once"] ) {
    throw "Não é possível carregar as dependências (scripts.js não carregado)";
  }

  require_once('estilos/widgets/DBModalBase.css');

  if ( !CONTEXT["DBMask"] ) {
    require_once("scripts/widgets/DBMask.widget.js");
  }

};

/**
 * Remove o componente
 */
DBModal.prototype.destroy = function() {

  if ( this.events['beforeDestroy'] ) {
    this.events['beforeDestroy'].call(this);
  }

  this.oDBMask.destroy();

  this.oDBMask = {};

  this.mContent = "";

  this.sTitle = "";

  this.oDivCabecalho = null;

  this.oDivConteudo  = null;

  this.oDivRodape    = null;

  if ( this.events['afterDestroy'] ) {
    this.events['afterDestroy'].call(this);
  }

}

DBModal.prototype.setDBMaskOptions = function(options) {
  this.dbMaskOptions = options;
}

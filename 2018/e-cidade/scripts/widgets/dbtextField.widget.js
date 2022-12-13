/**
 * Define campo do tipo input
 *
 * @constructor
 * @author Iuri Guntchnigg iuri@dbseller.com.br
 * @id  $Id: dbtextField.widget.js,v 1.23 2015/12/22 17:47:43 dbrafael.nery Exp $
 *
 * @param {String} sName         - id do Objeto
 * @param {String} sNameInstance - nome da instancia do objeto, usado para referencia interna  //Obsoleto.
 * @param {String} sValue        - valor Default do Objeto
 * @param {String} sSize         - tamanho
 */
DBTextField = function (mName, sNameInstance, sValue, sSize) {

  require_once("scripts/strings.js");


  if ( mName instanceof HTMLInputElement ) {

    this.oHTMLElement = mName;
    sName             = mName.name || mName.id || null;
  } else {
    sName = mName;
  }

  this.sIndexInstance = "DBTextField" + new Number(DBTextField.addInstance( this ));
  this.sNameInstance  = "DBTextField.oInstances['"+this.sIndexInstance+"']";
  sNameInstance       = this.sNameInstance;

  if ( sSize == undefined ) {
    sSize = 25;
  }
  if ( sValue == undefined ) {
    sValue = '';
  }

  if ( !this.oHTMLElement ) {
    this.oHTMLElement              = document.createElement("input");
  }

  this.oHTMLElementDivExpansible = document.createElement('div');
  this.oHTMLElementTextArea      = document.createElement('textarea');
  this.hasTextArea               = false;
  this.sName                     = sName;
  this.sValue                    = sValue;
  this.sSize                     = sSize;
  this.sNameInstance             = sNameInstance;
  this.lReadOnly                 = false;
  this.sStringConteudo           = "";
  this.iMaxLength                = "";
  this.sStringTextarea           = "";
  this.onBlur                    = "";
  this.onChange                  = "DBTextField.getInstance('"+this.sIndexInstance+"').setValue(this.value);";
  this.onFocus                   = "";
  this.onKeyPress                = "";
  this.onKeyUp                   = "";
  this.onKeyDown                 = "";
  this.onInput                   = "";
  this.sStyle                    = "";
  this.lExpansible               = false;
  var me                         = this;

  /**
   * Renderiza o input
   */
  this.makeInput  = function() {

    me.oHTMLElement.type  = "text";
    me.oHTMLElement.name  = me.sName;
    me.oHTMLElement.id    = me.sName;

    me.oHTMLElement.size  = me.sSize;
    me.oHTMLElement.setAttribute("value", me.sValue)
    me.oHTMLElement.setAttribute("autocomplete", "off");
    me.oHTMLElement.setAttribute("maxlength"   , me.iMaxLength);
    me.oHTMLElement.setAttribute("onBlur"      , me.onBlur);
    me.oHTMLElement.setAttribute("onFocus"     , me.onFocus);
    me.oHTMLElement.setAttribute("onKeyPress"  , me.onKeyPress);
    me.oHTMLElement.setAttribute("onKeyUp"     , me.onKeyUp);
    me.oHTMLElement.setAttribute("onKeyDown"   , me.onKeyDown);
    me.oHTMLElement.setAttribute("onChange"    , me.onChange);
    me.oHTMLElement.setAttribute("onInput"     , me.onInput);
    me.oHTMLElement.setAttribute("style"       , me.sStyle);

    me.oHTMLElement.observe("change", function() {
      me.setValue(me.oHTMLElement.value);
    });
    me.oHTMLElement.observe("focus",  function() {

      if ( me.lExpansible ) {
        me.displayTextArea();
      }
    });

    if ( me.lReadOnly ) {
      me.setReadOnly(me.lReadOnly);
    }
  }

  /**
   * Renderiza o input
   */
  this.makeTextArea = function() {

    this.oHTMLElementDivExpansible.id    = "cntTextArea" + this.sName;
    this.oHTMLElementDivExpansible.setStyle({
      position        : "absolute",
      display         : "none",
      padding         : "1px",
      paddingBottom   : "3px",
      border          : "1px solid #999999",
      backgroundColor : "#efefef"
    });
    this.oHTMLElementTextArea.id    = "textarea" + this.sName;
    this.oHTMLElementTextArea.setStyle({
      width          : "100%",
      height         : "100%",
      resize         : "none"
    });

    this.oHTMLElementTextArea.onblur = function() {
      me.hidetextArea();
    };

    this.oHTMLElementDivExpansible.appendChild(this.oHTMLElementTextArea);
  }

  /**
   * renderiza o widget no no especificado
   * @return void
   */
  this.show = function ( oNo, lAdicionaConteudo ) {

    this.makeInput();
    this.makeTextArea();

    if (this.lReadOnly) {
      this.setReadOnly(this.lReadOnly);
    }

    this.oHTMLElementDivExpansible.setStyle({
      width          : this.oHTMLElement.clientWidth - 3  + "px",
      height         : "100px"
    });

    /**
     * @TODO Trocar por validação interna da classe
     */
    if ( arguments.length == 0 ) {
      return;
    }

    if ( !lAdicionaConteudo ) {
      oNo.innerHTML  =  "";
    }
    oNo.appendChild(this.oHTMLElement);
    oNo.appendChild(this.oHTMLElementDivExpansible);


  }

  /**
   * Retorna o objeto em formato html
   * @return string
   *
   */
  this.toInnerHtml = function() {

    this.makeInput();
    this.makeTextArea();

    return this.oHTMLElement.outerHTML +
           this.oHTMLElementDivExpansible.outerHTML;
  }

  /**
   * Deixa o Input com sua altura extentida no momento que o objeto receber o foco
   * @param {Boolean} lExpansible - Habilita a altura extentida
   * @param {Integer} iHeight     - Altura do texto
   * @param {Integer} iWidth      - Largura do texto
   */
  this.setExpansible = function (lExpansible, iHeight, iWidth) {

    this.oHTMLElementDivExpansible.style.width  = iWidth  + "px";
    this.oHTMLElementDivExpansible.style.height = iHeight + "px";
    this.lExpansible                       = lExpansible;
  }

  /**
   * Mostra a textarea
   * @private
   */
  this.displayTextArea = function () {

    if ( me.lReadOnly || !me.lExpansible ) {
      return;
    }

    me.positionDiv();
    me.oHTMLElementDivExpansible.style.display = '';
    me.oHTMLElementTextArea.focus();
    return;
  }

  /**
   * Esconde a textaera
   * @private
   */
  this.hidetextArea = function () {

    me.setValue(this.oHTMLElementTextArea.value);
    me.oHTMLElementDivExpansible.style.display = 'none';
    return;
  }


  /**
   * Define um Evento do Input
   *
   * @param sEvent    {String}   - Evento a Ser adicionado
   * @param fFunction {Function} - Callback do Evento
   */
  this.setEvent = function ( sEvent, fFunction ) {

    me.oHTMLElement[sEvent] = fFunction;
    return ;
  }

  /**
   * Adiciona um evento a funcao
   *
   * @param {String} sEvent nome do evento
   * @param {String} com a funcao a ser executada
   *
   * @example dbTextField.addEvent('onclick', 'alert("ola")');
   */
  this.addEvent = function(sEvent, sFunction) {
    eval("this."+sEvent+" += sFunction");
  }


  /**
   * Retorna o Valor do Input
   */
  this.getValue = function () {

    var sValor = this.oHTMLElement.value;

    if (me.lExpansible) {
      sValor = this.oHTMLElement.value;
    }
    return sValor;
  }

  /**
   * Define o Valor do Elemento
   *
   * @param {string} sValor
   */
  this.setValue = function (sValor) {

    me.sValue             = sValor;
    me.oHTMLElement.value = me.sValue;
    return;
  }

  /**
   * Posiciona a div do texto
   * @private
   * @TODO Refatorar
   */
  this.positionDiv = function() {

    var el = this.oHTMLElement
    var x  = 0;
    var y  = new Number(el.offsetHeight);

    //Walk up the DOM and add up all of the offset positions.
    while (el.offsetParent && el.tagName.toUpperCase() != 'BODY') {

      if (el.className != "windowAux12") {

        x += el.offsetLeft;
        y += new Number(el.offsetTop);
      }
      el = el.offsetParent;
    }

    x += el.offsetLeft ;
    y += new Number(el.offsetTop) -window.scrollTop;
    this.oHTMLElementTextArea.style.top  = (y-this.oHTMLElement.offsetHeight-window.scrollTop) + 'px';
    this.oHTMLElementTextArea.style.left = x + 'px';

  };

  /**
   * Adiciona uma propriedade css ao input
   *
   * @param string sPropertie nome da propriedade css
   * @param string sValor Valor da proprieade
   */
  this.addStyle = function (sPropertie, sValor) {

    this.sStyle += sPropertie+":"+sValor+";";
    me.oHTMLElement.setAttribute("style", me.sStyle);
    return;
  }

  /**
   * Define o tamanho maximo de caracteres do campo.
   *
   * @param integer iMaxLength numero maximo de caracteres
   * return void
   */
  this.setMaxLength = function(iMaxLength) {

    me.iMaxLength = iMaxLength;
    me.oHTMLElement.setAttribute("maxLength", me.iMaxLength);
    return true;
  }

  /**
   * Define o campo como somente leitura.
   * @param bollean lReadOnly true somente leitura
   * return void
   */
  this.setReadOnly = function(lReadOnly) {

    me.oHTMLElement.readOnly = lReadOnly;
    me.lReadOnly             = lReadOnly;

    if ( lReadOnly === true ) {
      me.oHTMLElement.addClassName("readonly");
    } else {
      me.oHTMLElement.removeClassName("readonly");
    }
    return;
  }
}

/**
 * Retorna o Elemento Input do Componente
 * @returns
 */
DBTextField.prototype.getElement = function() {
  return this.oHTMLElement;
};

/**
 * Adiciona uma classe ao elemento
 * @param sClass nome da classe a ser adicionado
 */
DBTextField.prototype.setClassName = function(sClass) {

  this.oHTMLElement.addClassName(sClass);
  return;
}


/**
 * Repositório das Instancias
 */
DBTextField.oInstances  = DBTextField.oInstances || {};

DBTextField.iCounter    = DBTextField.iCounter   || 0;

DBTextField.addInstance = function( oDBTextField ) {

  if ( !( oDBTextField instanceof DBTextField ) ) {
    throw('Objeto Inválido');
  }
  var iNumeroInstancia= DBTextField.iCounter++;
  DBTextField.oInstances['DBTextField'+ iNumeroInstancia] = oDBTextField;
  return iNumeroInstancia;
}

DBTextField.getInstance = function( sName ) {
  return DBTextField.oInstances[sName];
};

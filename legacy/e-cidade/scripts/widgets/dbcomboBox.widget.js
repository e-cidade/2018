/**
 * @fileoverview Define um objeto do tipo DBComBox
 *
 * @author Iuri Guntchnigg iuri@dbseller.com.br
 * @version  $Revision: 1.18 $
 */

/**
 * Cria um input tipo do tipo ComboBox (HTMLSelectElement)
 * @class DBComboBox
 * @constructor
 * @param {string} sName nome e id do objeto
 * @param {string} sNameInstance nome da instancia do objeto --- Não Utilizado
 * @param {string} [sWidth]  Tamanho Padrao do Objeto
 * @param {integer} sSize    quantos mostra na tela (define a aparencia de lista);
 * @return void
 */
DBComboBox = function (sName, sNameInstance, aItens, sWidth, sSize) {

  if ( sSize == null ) {
    sSize = '';
  }

  if (sWidth == null ) {
    sWidth = '100%';
  }

  this.aItens = [];

  if (aItens != null) {

    for (var nome in aItens) {

      if (typeof(aItens[nome]) == "function") {
        continue;
      }

      this.addItem(nome, aItens[nome]);
    }
  }

  this.sName          = sName;
  this.sValue         = '';
  this.stringConteudo = '';
  this.sStyle         = "";
  this.aGroups        = new Array();
  this.sSize          = sSize;
  this.onChange       = "";
  this.onFocus        = "";
  this.onDblClick     = "";
  this.lMultiple      = false;
  this.lDisabled      = false;
  this.aAttributes    = [];
  this.addStyle('width', sWidth);
}

/**
 * Adiciona um item ao combobox
 * @param {string} sValue valor do item
 * @param {string} sDescription descricao do item
 * @param {string} [sDescription] grupo que o item fara parte
 * @param {Array} [oParameters] parametros adiciinais no item.
 * @return void
 */
DBComboBox.prototype.addItem  = function (sValue, sDescription, sIdGroup, aParameters) {

  if (aParameters == null) {
    aParameters = new Array();
  }

  if ($(this.sName)) {

    var oOption = new Option(sDescription, sValue);
    aParameters.each(function(oParam, iSeq) {
      oOption.setAttribute(oParam.nome, oParam.valor);
    });

    if (sIdGroup != null) {

      if ($(sIdGroup)) {

        $(sIdGroup).appendChild(oOption);
      } else {
        $(this.sName).add(oOption, null);
      }

    } else {
      $(this.sName).add(oOption, null);
    }
  }

  var oItem        = new Object();
  oItem.id         = sValue;
  oItem.descricao  = sDescription;
  oItem.grupo      = '';
  oItem.parametros = aParameters;

  if (sIdGroup != null) {
    oItem.grupo     = sIdGroup
  }

  this.aItens[sValue] = oItem;
}

/**
 * Remove um item do combobox
 * @param {string} sValue remove o item com valor = sValue
 * @return void
 */
DBComboBox.prototype.removeItem  = function (sValue) {

  if (this.aItens[sValue]) {
    delete this.aItens[sValue];
  }

  if ($(this.sName)) {

    var iOptionsLength = $(this.sName).options.length;

    for (iOPT = 0; iOPT < iOptionsLength; iOPT++) {

      if ($(this.sName).options[iOPT].value == sValue) {

        $(this.sName).options[iOPT] = null;
        break;
      }
    }
  }
}

/**
 * Remove um grupo e seus filhos do combobox
 * @param {string} sValue remove o grupo com valor = sIdGroup
 * @return void
 */
DBComboBox.prototype.removeGroup = function(sIdGroup) {

  if (this.aGroups[sIdGroup]) {
    delete this.aItens[sIdGroup];
  }

  if ($(this.sName)) {

    if ($(sIdGroup)) {
      $(this.sName).removeChild($(sIdGroup));
    }
  }
}

/**
 * Renderiza o input
 */
DBComboBox.prototype.make = function () {

  var sDisabled = "";
  if (this.lDisabled) {
    var sDisabled = ' disabled ';
  }
  var sMultiple   = '';
  if (this.lMultiple ) {
    sMultiple = ' multiple ';
  }
  this.sStringConteudo  = "<select "+sDisabled+" "+sMultiple;
  this.sStringConteudo += !this.sName     ? "" : "        name       = '" + this.sName     + "' ";
  this.sStringConteudo += !this.sName     ? "" : "        id         = '" + this.sName     + "' ";
  this.sStringConteudo += !this.sValue    ? "" : "        value      = '" + this.sValue    + "' ";
  this.sStringConteudo += !this.onFocus   ? "" : "        onFocus    = '" + this.onFocus   + "' ";
  this.sStringConteudo += !this.onChange  ? "" : "        onChange   = '" + this.onChange  + "' ";
  this.sStringConteudo += !this.onDblClick? "" : "        onDblClick = '" + this.onDblClick+ "' ";
  this.sStringConteudo += !this.sStyle    ? "" : "        style      = '" + this.sStyle    + "' ";
  this.sStringConteudo += !this.sSize     ? "" : "        size       = '" + this.sSize     + "' ";
  this.aAttributes.each(function(oAttribute, id) {
    this.sStringConteudo +=  oAttribute.name +" = '"+oAttribute.value+"'";
  });
  this.sStringConteudo += " >";

  /**
   * primeiro, adicionamos os itens que nao estáo agrupados
   */
  for (var nome in this.aItens) {

    if (typeof(this.aItens[nome]) == "function") {
      continue;
    }

    if (this.aItens[nome].grupo != '') {
      continue;
    }

    with (this.aItens[nome]) {

      var sSelect = '';

      if (!this.lMultiple) {

        if (this.sValue == id) {
          sSelect = ' selected ';
        }
      }

      var sParameters = '';
      parametros.each(function(oParametro, iSeq) {
        sParameters +=" "+oParametro.nome+"='"+oParametro.valor+"' ";
      });

      this.sStringConteudo += "<option value='"+id+"' "+sSelect+" "+sParameters+">"+descricao+"</option>";
    }
  }

  /**
   * Adicionamos os Grupos e os itens do grupo
   */
  for (var iGrupo in this.aGroups) {

    if (typeof(this.aGroups[iGrupo]) == "function") {
      continue;
    }

    with (this.aGroups[iGrupo]) {

      this.sStringConteudo += "<optgroup id='"+id+"' label='"+descricao+"'>";

      for (var nome in this.aItens) {

        if (typeof(this.aItens[nome]) == "function") {
          continue;
        }

        if (this.aItens[nome].grupo == id) {

          with (this.aItens[nome]) {
            this.sStringConteudo += "<option value='"+id+"'>"+descricao+"</option>";
          }
        }
      }

      this.sStringConteudo += "</optgroup>";
    }
  }

  this.sStringConteudo += "</select>";
}

/**
 *Adiciona um evento a funcao
 *@param string sEvent nome do evento
 *@param sFunction string com a funcao a ser executada
 *@example dbTextField.addEvent('onclick', 'alert("ola")');
 */
DBComboBox.prototype.addEvent = function(sEvent, sFunction) {
  eval("this."+sEvent+" += sFunction");
}

/**
 *Adiciona uma propriedade css ao input
 * @param string sPropertie nome da propriedade css
 * @param string sValor Valor da propriedade
 */
DBComboBox.prototype.addStyle = function (sPropertie, sValor) {
  this.sStyle += sPropertie+":"+sValor+";";
}

/**
 * renderiza o widget no no especificado
 * @param htmlNODE oNo conteiner HTML
 * @return void
 */
DBComboBox.prototype.show = function (oNo, lAdiciona) {

  this.make();
  if ( !!lAdiciona ) {
    oNo.innerHTML +=  this.sStringConteudo;
  } else {
    oNo.innerHTML =  this.sStringConteudo;
  }

  oNo.appendChild($(this.sName));
}

/**
 * Desabilita o combobox
 * return void
 */
DBComboBox.prototype.setDisable = function () {

  if ($(this.sName)) {

    this.lDisabled                      = true;
    $(this.sName).disabled              = true;
    $(this.sName).style.backgroundColor = 'rgb(222, 184, 135)';
    $(this.sName).style.color           = 'rgb(0,0,0)';

  } else {
    this.lDisabled = true;
  }
}

/**
 * Habilita o combobox
 * return void
 */
DBComboBox.prototype.setEnable = function () {

  if ($(this.sName)) {

    this.lDisabled = false;
    $(this.sName).disabled = false;

  } else {
    this.lDisabled = false;
  }

  $(this.sName).style.backgroundColor= '#FFFFFF';
}

/**
 * retorna o valor do combobox
 * return mixed  caso seje multiple, retorna um array, senao string
 */
DBComboBox.prototype.getValue = function() {

  var sValue = '';

  if ($(this.sName)) {
    sValue = $F(this.sName);
  } else {
    sValue = this.sValue;
  }

  return sValue;
}

/**
 * Retorna a descrição da opção selecionada
 * @returns {string|string=|*|string|descricao}
 */
DBComboBox.prototype.getDescricao = function() {
  return this.aItens[this.getValue()].descricao;
};

/**
 * Define se o ComboBox Usa Selecao multipla
 * @param {boolean} lMultiple
 * return void
 */
DBComboBox.prototype.setMultiple = function (lMultiple) {

  if ($(this.sName)) {

    if (lMultiple) {

      this.lMultiple         = lMultiple;
      $(this.sName).multiple = 'multiple';

    } else {

      this.lMultiple         = lMultiple;
      $(this.sName).multiple = ''
    }

  } else {
    this.lMultiple = lMultiple;
  }
}

/**
 * Define a altura do combobox
 * @param {integer} iSize tamanho do combobox
 * return void
 */
DBComboBox.prototype.setSize = function (iSize) {

  this.sSize = iSize;

  if ($(this.sName)) {
    $(this.sName).size = this.sSize;
  }
}

/**
 * retorna o objeto em formato html
 * @return string
 */
DBComboBox.prototype.toInnerHtml = function(){

  this.make();
  return this.sStringConteudo;
}

/**
 * Seta o valor do ComboBox
 *@param {mixed} mValue define o valor do combo
 *@return void
 */
DBComboBox.prototype.setValue = function (mValue) {

  this.sValue = mValue;

  if (mValue instanceof Array) {

    if (this.lMultiple) {

      this.sValue = mValue;
    } else {

      if (mValue.length >= 1) {
        this.sValue = mValue[0];
      }
    }
  }

  if ($(this.sName)) {

    var iOptionsLength = $(this.sName).options.length;

    for (iOPT = 0; iOPT < iOptionsLength; iOPT++) {
      $(this.sName).options[iOPT].selected = false;
    }

    if (this.lMultiple) {

      if (!(mValue instanceof Array)) {
        $(this.sName).value = mValue;
      } else {

        for (var iValue = 0; iValue < mValue.length; iValue++) {

          var iOptionsLength = $(this.sName).options.length;
          for (iOPT = 0; iOPT < iOptionsLength; iOPT++) {

            if (mValue[iValue] == $(this.sName).options[iOPT].value) {
              $(this.sName).options[iOPT].selected = true;
            }
          }
        }
      }

    } else {
      $(this.sName).value = this.sValue;
    }
  }
}

/**
 * Adiciona um grupo de options ao combo
 * @param {string} sIdGrupo id do grupo usando para referencia nos options
 * @param {string} sDescricao Descricao do Grupo
 * return void;
 */
DBComboBox.prototype.addGroup = function(sIdGrupo, sDescricaoGrupo) {

  var oGrupo = new Object;

  oGrupo.id        = sIdGrupo;
  oGrupo.descricao = sDescricaoGrupo;

  this.aGroups[sIdGrupo] = oGrupo;

  if ($(this.sName)) {

    var oOptGroup   = document.createElement("optgroup");
    oOptGroup.label = sDescricaoGrupo;
    oOptGroup.id    = sIdGrupo;
    $(this.sName).appendChild(oOptGroup);
  }
}

/**
 * remove todos os elementos do combo
 * return void;
 */
DBComboBox.prototype.clearItens  = function () {

  if (this.aItens) {
    this.aItens = new Array();
  }

  if ($(this.sName)) {
    $(this.sName).options.length = 0;
  }
}

/**
 * Retorna o Label do option selecionado
 *@return {string}
 */
DBComboBox.prototype.getLabel = function () {

  var sValue = '';

  if ($(this.sName)) {
    sValue = $(this.sName).options[$(this.sName).selectedIndex].innerHTML;
  }

  return sValue;
};

/**
 * Retorna o Label de acordo com o index recebido
 * @param iOption
 * @returns string
 */
DBComboBox.prototype.getLabelOption = function(iOption) {
  return $(this.sName).options[iOption].innerHTML;
};

/**
 * Retorna o Elemento Input do Componente
 * @returns HTMLSelectElement
 */
DBComboBox.prototype.getElement = function() {

  if ( !$(this.sName) ) {
    throw "Para Chamar este método, primeiro o componente deve ser renderizado via DBComboBox.show();";
  }
  return $(this.sName);
};

/**
 * Adiciona um atributo no select
 * @param {string} sName nome do atributo
 * @param {string} sValue valor do atributo
 */
DBComboBox.prototype.addAttribute = function(sName, sValue) {

  var self = this;
  var oAttribute = {name:sName, value:sValue};
  this.aAttributes.push(oAttribute);
  if ($(self.sName)) {
    $(self.sName).addAttribute(sName, sValue);
  }

};
/**
 * Retorna o valor do atributo sName
 * @param sName nome do atributo
 * @returns {string}
 */
DBComboBox.prototype.getAttribute = function(sName) {

  var sAttribute = '';
  if ($(this.sName)) {
    sAttribute = $(this.sName).getAttribute(sName);
  }
  return sAttribute;
};

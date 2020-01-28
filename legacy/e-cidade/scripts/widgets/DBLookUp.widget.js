/**
 * DBLookUp Classe responsavel por instanciar os comportamentos padrão para Ancora informada como parametro
 *
 *  @param oAncora          {HTMLAnchorElement} Ancora para lookUp
 *  @param oInputID         {HTMLInputElement}  Input com a informação do ID
 *  @param oInputDescricao  {HTMLInputElement}  Input com a informação da Descrição
 *  @param oParametros      {Object}            Parametros opcionais
 *
 *  @constructor
 */
DBLookUp = function (oAncora, oInputID, oInputDescricao, oParametros) {

  /**
   * Define valor padrão
   */
  oParametros = oParametros || {};

  this.iReferencia     = CurrentWindow.DBLookUp.repository.addInstance(this);
  this.oAncora         = oAncora;
  this.oInputID        = oInputID;
  this.oInputDescricao = oInputDescricao;
  this.oParametros     = {
    "sArquivo"              : oParametros.sArquivo               || oParametros.arquivo                || this.oAncora.getAttribute('func-arquivo') || null,
    "sLabel"                : oParametros.sLabel                 || oParametros.label                  || "Pesquisar",
    "sQueryString"          : oParametros.sQueryString           || oParametros.queryString            || "",
    "sDestinoLookUp"        : oParametros.sDestinoLookUp         || oParametros.destinoLookup          || "",
    "sObjetoLookUp"         : oParametros.sObjetoLookUp          || oParametros.objetoLookup           || this.oAncora.getAttribute('func-objeto') || "db_pesquisa",
    "aCamposAdicionais"     : oParametros.aCamposAdicionais      || oParametros.camposAdicionais       || [],
    "fCallBack"             : oParametros.fCallBack              || oParametros.callBack               || null,
    "aParametrosAdicionais" : oParametros.aParametrosAdicionais  || oParametros.parametrosAdicionais   || [],
    "zIndex"                : oParametros.zIndex                 || oParametros.index                  || null,
    "oBotaoParaDesabilitar" : oParametros.oBotaoParaDesabilitar  || oParametros.botaoParaDesabilitar   || ""
  };

  this.oCallback  = {
    onChange         : function(lErro) {},
    onClick          : function() {}
  };

  this.__init();

  var lHabilitado = true;

  this.habilitar = function() {

    if (lHabilitado) {
      return true;
    }

    this.oInputID.classList.remove("readonly");
    this.oInputID.readOnly = false;

    if ( !(this.oAncora instanceof HTMLInputElement) ){

      this.oAncora.href = "javascript:;";
      this.oAncora.classList.add("DBAncora");
    }
    this.oAncora.onclick   = this.eventFunctions.click.bind(this);
    this.oInputID.onchange = this.eventFunctions.change.bind(this);

    lHabilitado = true;
  };

  this.desabilitar = function() {

    if (!lHabilitado) {
      return true;
    }

    this.oInputID.readOnly = true;
    this.oInputID.onchange = null;
    this.oAncora.classList.remove("DBAncora");

    if ( !(this.oAncora instanceof HTMLInputElement) ){

      this.oInputID.classList.add("readonly");
      this.oAncora.removeAttribute("href");
    }
    this.oAncora.onclick = null;

    lHabilitado = false;
  };
};

/**
 * Função __init para alterar os elementos necessários
 * para o comportamento da LookUp
 */
DBLookUp.prototype.__init = function() {

  /**
   * Modifica a ancora
   */
  if ( !(this.oAncora instanceof HTMLInputElement) ){

    this.oAncora.className         += "DBAncora bold";
    this.oAncora.href               = "javascript:void(0)";
  }
  /**
   * Modifica os Inputs
   */
  var sClassID = this.oInputID.classList.toString();
  if ( sClassID.match(/field-size/) == null ) {
   this.oInputID.className        += " field-size2";
  }
  this.oInputID.oInstancia = this;

  var sClassDescricao = this.oInputDescricao.classList.toString();
  if ( sClassDescricao.match(/field-size/) == null ) { // validado se já foi adicionado alguma classe
    this.oInputDescricao.className += " field-size8";
  }

  this.oInputDescricao.className += " readonly";
  this.oInputDescricao.readOnly   = true;
  this.oInputDescricao.oInstancia = this;

  this.oAncora.onclick = this.eventFunctions.click.bind(this);
  this.oInputID.onchange = this.eventFunctions.change.bind(this);
};

/**
 * Realiza o tratamento dos eventos Click e Change
 */
DBLookUp.prototype.eventFunctions = {

  /**
   *  Abre a janela para pesquisa
   *
   *  @param  {String} click Onde será aberta a janela
   *  @return {void}
   */
  click: function(click){
    this.abrirJanela(true);
  },

  /**
   *  Callbackda digitação
   *
   *  @param  {String} change
   *  @return {void}
   */
  change: function(change){

    if ( this.oInputID.value == "" ) {
      this.oInputDescricao.value = "";
      change.preventDefault();
      return false;
    }
    this.abrirJanela(false);
  }
};

/**
 * Monta a QueryString para quando é
 * feito o click na ancora
 *
 * @return String QueryString
 */
DBLookUp.prototype.getQueryStringClick = function() {

  var sQuery  = "";
  sQuery += this.oParametros.sArquivo;
  sQuery += "?";

  if ( this.oParametros.aParametrosAdicionais.length > 0 ){
    sQuery += this.oParametros.aParametrosAdicionais.join("&");
    sQuery += "&";
  }

  sQuery += "funcao_js=parent.CurrentWindow.DBLookUp.repository.getInstance("+this.iReferencia+").callBackClick";
  sQuery += "|" + (this.oInputID.lang        || this.oInputID.getAttribute('data')        || this.oInputID.id        || this.oInputID.name);
  sQuery += "|" + (this.oInputDescricao.lang || this.oInputDescricao.getAttribute('data') || this.oInputDescricao.id || this.oInputDescricao.name);

  var sCampos = this.oParametros.aCamposAdicionais.join("|");

  if ( sCampos ) {
    sQuery += "|" + sCampos;
  }

  sQuery += this.oParametros.sQueryString ? "|" + this.oParametros.sQueryString : "";

  return sQuery;
}

/**
 * Monta a QueryString para quando é executado
 * o Change no objeto oInputID
 * @return String QueryString
 */
DBLookUp.prototype.getQueryStringChange = function() {

  var sQuery  = "";
      sQuery += this.oParametros.sArquivo;
      sQuery += "?";
      sQuery += "pesquisa_chave=" + this.oInputID.value;
      sQuery += "&";

      if ( this.oParametros.aParametrosAdicionais.length > 0 ){
        sQuery += this.oParametros.aParametrosAdicionais.join("&");
        sQuery += "&";
      }

      sQuery += "funcao_js=parent.CurrentWindow.DBLookUp.repository.getInstance("+this.iReferencia+").callBackChange";

  sQuery += this.oParametros.sQueryString;

  return sQuery;
}

/**
 * Trata o retorno do click na Ancora
 *
 * @param iCodigo {Integer}
 * @param sDescricao {String}
 */
DBLookUp.prototype.callBackClick = function(iCodigo, sDescricao) {

  this.oInputID.value        = iCodigo;
  this.oInputDescricao.value = sDescricao;
  var prefixo                = !!this.oParametros.sDestinoLookUp ? this.oParametros.sDestinoLookUp + '.' : '';
  var oObjetoLookUp          = eval(prefixo + this.oParametros.sObjetoLookUp);

  oObjetoLookUp.hide();
  this.oCallback.onClick(arguments);

  if (this.oParametros.fCallBack) {
    this.oParametros.fCallBack.apply(this.oParametros, arguments);
  }
  return;
}

/**
 * Trata o retorno do change no objeto oInputID.
 * Percorre todos os arguments recebido pela função, pois não temos um padrão
 * de retorno dos dados, verificando qual deles é responsavel por informar
 * a ocorrencia de erro e qual realmente é a string que deverá ser a descrição.
 */
DBLookUp.prototype.callBackChange  = function() {

  var aArgumentos = arguments,
      lErro       = null,
      sDescricao  = null;

  for (var iArgumento = 0; iArgumento < aArgumentos.length; iArgumento++) {

    if (typeof(aArgumentos[iArgumento]) == "boolean") {
      lErro = aArgumentos[iArgumento];
    };

    if (typeof(aArgumentos[iArgumento]) == 'string' && sDescricao == null ) {
      sDescricao = aArgumentos[iArgumento];
    };
  };

  this.oInputDescricao.value = sDescricao;
  if (lErro) {
    this.oInputID.value        = '';
  }

  if (this.oParametros.oBotaoParaDesabilitar != '') {
    this.oParametros.oBotaoParaDesabilitar.disabled = false;
  }

  this.oCallback.onChange(lErro, arguments);

  if (this.oParametros.fCallBack) {
    this.oParametros.fCallBack.apply(this.oParametros, arguments);
  }

  return;
};

/**
 * Função responsável pela abertura da janela de pesquisa.
 * @param lAbre {Boolean}
 */
DBLookUp.prototype.abrirJanela = function(lAbre){

  var sQueryString = '';

  if (typeof lAbre == 'undefined') {
    lAbre = true;
  }

  if ( !this.oParametros.sArquivo ) {
    throw "Arquivo não pode ser vazio.";
  };

  if (lAbre) {
    sQueryString = this.getQueryStringClick();
  } else {

    if (this.oParametros.oBotaoParaDesabilitar != '') {
      this.oParametros.oBotaoParaDesabilitar.disabled = true;
    }
    sQueryString = this.getQueryStringChange();
  };

  var oJanela = js_OpenJanelaIframe(

    this.oParametros.sDestinoLookUp,
    this.oParametros.sObjetoLookUp,
    sQueryString,
    this.oParametros.sLabel,
    lAbre
  );

  if ( oJanela.setAltura ) {

    oJanela.setAltura("calc(100% - 10px)");
    oJanela.setLargura("calc(100% - 10px)");
    oJanela.setPosY("0");
    oJanela.focus();
  }

  if ( this.oParametros.zIndex != null ) {
    $('Jan' + this.oParametros.sObjetoLookUp).style.zIndex = this.oParametros.zIndex;
  }

};

/**
 * Seta o arquivo responsável pela pesquisa
 * @param String sArquivo
 */
DBLookUp.prototype.setArquivo = function(sArquivo){
  this.oParametros.sArquivo = sArquivo;
};

/**
 * Seta o nome do Label que será utilizado como titulo da janela de pesquisa
 *
 * @param sLabel {String}
 */
DBLookUp.prototype.setLabel = function(sLabel){
  this.oParametros.sLabel = sLabel;
};

/**
 * Seta a query string que deverá ser utilizada na função de pesquisa.
 *
 * @param sQueryString {String}
 */
DBLookUp.prototype.setQueryString = function(sQueryString){
  this.oParametros.sQueryString = sQueryString;
};

/**
 * Seta o Destino da LookUp
 *
 * @param sDestinoLookUp {String}
 */
DBLookUp.prototype.setDestinoLookUp = function(sDestinoLookUp){
  this.oParametros.sDestinoLookUp = sDestinoLookUp;
};

/**
 * Seta o nome do objeto que será utilizado na tela de pesquisa.
 * @param sObjetoLookUp {String}
 */
DBLookUp.prototype.setObjetoLookUp = function(sObjetoLookUp){
  this.oParametros.sObjetoLookUp = sObjetoLookUp;
};

/**
 * Seta os campos adicionais
 *
 * @param CamposAdicionais {Array}
 */
DBLookUp.prototype.setCamposAdicionais = function(aCamposAdicionais){
  this.oParametros.aCamposAdicionais = aCamposAdicionais;
};

DBLookUp.prototype.setParametrosAdicionais = function (aParametrosAdicionais) {
  this.oParametros.aParametrosAdicionais = aParametrosAdicionais;
}
/**
 * Seta função de callBack.
 *
 * @param sEvento {String} tipo do evendo (onChange ou onClick)
 * @param fFuncao {String}
 */
DBLookUp.prototype.setCallBack = function(sEvento, fFuncao){
  this.oCallback[sEvento] = fFuncao;
}

window.DBLookup        = CurrentWindow.DBLookUp || DBLookUp;
window.Lookup          = CurrentWindow.DBLookUp || DBLookUp;
CurrentWindow.DBLookUp = CurrentWindow.DBLookUp || DBLookUp;

DBLookUp.repository = DBLookUp.repository || {};
DBLookUp.repository = {

  "oInstances"  : DBLookUp.repository.oInstances || {},

  "iCounter"    : DBLookUp.repository.iCounter   || 0,

  "addInstance" : function( oDBLookUp ) {

    var iNumeroInstancia = DBLookUp.repository.iCounter++;
    DBLookUp.repository.oInstances['DBLookUp'+ iNumeroInstancia] = oDBLookUp;
    return iNumeroInstancia;
  },
  "getInstance" : function( iCodigo ) {
    return DBLookUp.repository.oInstances["DBLookUp" + iCodigo];
  }
}

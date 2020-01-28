var CONTEXT = this;

DBViewHelp = function() {

  DBViewHelp.dependencies();

  /**
   * Instancia da DBModal
   */
  this.oDBModal = null

  /**
   * URL do RPC para buscar os dados.
   */
  this.URL_RPC = "con4_dbhelp.RPC.php";

  /**
   * Elemento que irá conter a versão
   */
  this.oSpanVersao = null;

  /**
   * Dados do HELP
   */
  this.oHelp = {}
}

/**
 * Método responsavel por mostrar a view em tela
 */
DBViewHelp.prototype.show = function() {

  var _this = this;

  _this.oDBModal = new DBModal();

  _this.oDBModal.setTitle("Ajuda:");

  var oDivVersao         = CONTEXT.document.createElement('div');
  oDivVersao.style.color      = "#003b85";
  oDivVersao.style.fontSize   = "18px";
  oDivVersao.style.textAlign  = "left";
  oDivVersao.style.marginTop  = "5px";
  oDivVersao.style.textIndent = "10px";

  var oSpanVersao            = CONTEXT.document.createElement('span');
  oSpanVersao.innerHTML      = '';
  oSpanVersao.style.position = "absolute";
  oSpanVersao.style.right    = "15px";

  this.oSpanVersao = oSpanVersao;

  oDivVersao.appendChild(oSpanVersao);

  oHelpContainer = this.__buildHtml();

  var oDivContainer = CONTEXT.document.createElement('div');
  oDivContainer.appendChild(oDivVersao);
  oDivContainer.appendChild(oHelpContainer);

  _this.oDBModal.setContent(oDivContainer);
  _this.oDBModal.show();

};

DBViewHelp.build = function() {

  var oDBHelp = new DBViewHelp();
  oDBHelp.show();

};

DBViewHelp.prototype.__buildHtml = function() {

  var _this = this

  var oDivContainer = CONTEXT.document.createElement('div')

  /**
   * Cria o texto do titulo
   */
  var oTitleHelp = CONTEXT.document.createElement('h2');
  oTitleHelp.innerHTML = 'Bem-vindo à Central de Ajuda do e-Cidade';

  /**
   * Cria a div principal do help
   */
  var oDivHelp = CONTEXT.document.createElement('div');
  oDivHelp.setAttribute('class', 'db-help');

  _this.oDivHelp = oDivHelp;

  /**
   * Cria a div descricao
   */
  var oDivDescricao = CONTEXT.document.createElement('div')
  oDivDescricao.innerHTML = 'Tire suas dúvidas sobre esta página:';
  oDivDescricao.setAttribute('class', 'db-help-resume')

  /**
   * Insere na div principal a descricao e o container dos grupos
   */
  oDivHelp.appendChild(oDivDescricao);

  /**
   * Percorre os helps e adiciona na div principal
   */
  _this.retrieveHelpData();

  /**
   * Insere no container o titulo e a div principal
   */
  oDivContainer.appendChild(oTitleHelp);
  oDivContainer.appendChild(oDivHelp);

  return oDivContainer
}

/**
 * Método responsavel por remontar os helps do componente.
 */
DBViewHelp.prototype.__buildHelp = function() {

  var _this = this

  /**
   * Remove todos os groups
   */
  if (!_this.oHelp || (_this.oHelp['error'] != undefined) || !_this.oHelp.helps_releases.group ) {
    var div = CONTEXT.document.createElement('div');
    div.setAttribute('class', 'db-help-group db-help-group-empty');
    div.innerHTML = 'Não foi encontrado nenhum Help para esta rotina.';
    _this.oDivHelp.appendChild(div)
    return;
  }

  /**
   * Insere todos baseado no help base
   */
  var oDivDescricao = CONTEXT.document.createElement('div')
  oDivDescricao.innerHTML = _this.oHelp.helps_releases.group.content;
  oDivDescricao.setAttribute('class', 'db-help-resume');

  _this.oDivHelp.appendChild(oDivDescricao);

  _this.oHelp.helps_releases.group.groups.each(function(group) {

    var oDivHelpGroup = _this.__buildHelpGroup(group);

    _this.oDivHelp.appendChild(oDivHelpGroup);

  });

  if (_this.oHelp.helps_releases.group.fields.length > 0) {
    _this.oDivHelp.appendChild(_this.__buildFields(_this.oHelp.helps_releases.group.fields));
  }

}

/**
 * Seta os helps e remonta a tela
 */
DBViewHelp.prototype.setHelp = function(oHelp) {

  this.oHelp = oHelp;
  this.__buildHelp();
}

/**
 * Método responsavel por montar um help-group
 * {
 *   title: "Lorem ipsum Dolore ea nostrud mollit exercitation dolor?",
 *   content: "Lorem ipsum Id cillum officia commodo deserunt ad aliqua do amet pariatur ad ad commodo eiusmod."
 * }
 */
DBViewHelp.prototype.__buildHelpGroup = function(helpData) {

  var _this = this;

  var oDivHelpGroup = CONTEXT.document.createElement('div');
  oDivHelpGroup.setAttribute('class', 'db-help-group');

  var oEventClick = (function() {
    oDivHelpGroup.classList.toggle('active')
  }).bind(this);

  /**
   * Icone do Help (sinal de "mais" ou de "menos")
   */
  var oDivGroupIcon = CONTEXT.document.createElement('div')
  oDivGroupIcon.setAttribute('class', 'db-help-group-icon');
  oDivGroupIcon.onclick = oEventClick

  /**
   * Titulo do help, no caso será um pergunta (FAQ)
   */
  var oDivGroupTitle = CONTEXT.document.createElement('div')
  oDivGroupTitle.setAttribute('class', 'db-help-group-title');
  oDivGroupTitle.innerHTML = helpData.title;
  oDivGroupTitle.onclick = oEventClick

  /**
   * Conteudo do help, no caso resposta da pergunta (FAQ)
   */
  var oDivGroupContent = CONTEXT.document.createElement('div')
  oDivGroupContent.setAttribute('class', 'db-help-group-content');

  if (helpData.content) {

    var oDivGroupResume = CONTEXT.document.createElement('div')
    oDivGroupResume.setAttribute('class', 'db-help-group-resume');
    oDivGroupResume.innerHTML = helpData.content;

    oDivGroupContent.appendChild(oDivGroupResume);
  }

  oDivHelpGroup.appendChild(oDivGroupIcon);
  oDivHelpGroup.appendChild(oDivGroupTitle);
  oDivHelpGroup.appendChild(oDivGroupContent);

  if (helpData.groups && helpData.groups.length > 0) {

    helpData.groups.each(function(group) {
      oDivGroupContent.appendChild(_this.__buildHelpGroup(group));
    })
  }

  if (helpData.fields && helpData.fields.length > 0) {
    oDivHelpGroup.appendChild(_this.__buildFields(helpData.fields));
  }

  return oDivHelpGroup;
}

/**
 * Monta o html dos campos
 * @param  Object[] aFields Array de objetos com as informacoes dos campos
 */
DBViewHelp.prototype.__buildFields = function(aFields) {

  var oOL = CONTEXT.document.createElement('ol');
  oOL.setAttribute('class', 'db-help-fields');

  aFields.each(function(field) {

    var oSpanTitle = CONTEXT.document.createElement('span');
    oSpanTitle.setAttribute('class', 'db-help-fields-title');
    oSpanTitle.innerHTML = field.label + ":";

    var oSpanContent = CONTEXT.document.createElement('span');
    oSpanContent.setAttribute('class', 'db-help-fields-content');
    oSpanContent.innerHTML = field.content;

    var oItem = CONTEXT.document.createElement('li');

    oItem.appendChild(oSpanTitle);
    oItem.appendChild(oSpanContent);
    oOL.appendChild(oItem);

  })

  return oOL;
}

/**
 * Método responsável por setar a versão passada por parametro no elemento.
 */
DBViewHelp.prototype.setVersao = function(sVersao) {

  if (!this.oSpanVersao) {
    throw "Elemento responvável por mostrar a versão ainda não criado.\nProvavelmente o método 'show' não foi chamado.";
  }
  /* @note: Removido exebicao da versão pois nao ha mais release' com a entrega continua */
  //this.oSpanVersao.innerHTML = sVersao
}

DBViewHelp.prototype.retrieveHelpData = function() {

  var _this = this;

  var oParametros = {
    exec: "getHelpData"
  }

  js_divCarregando('Aguarde...<br />Buscando os Help\'s', 'divCarregandoHelps', false)
  $('divCarregandoHelps').style.zIndex = 10001

  var oRequisicao = {
    method: "GET",
    parameters: "json=" + JSON.stringify(oParametros),
    onComplete: function(oAjax) {

      js_removeObj('divCarregandoHelps')

      var oRetorno = JSON.parse(oAjax.responseText);

      if (oRetorno.iStatus == 2) {
        alert(oRetorno.sMessage.urlDecode())
        return;
      }

      _this.setVersao(oRetorno.sVersao ? oRetorno.sVersao : '');
      _this.setHelp(oRetorno.oHelp);

    }
  }

  new Ajax.Request(this.URL_RPC, oRequisicao)

}

DBViewHelp.build = function() {

  var dbView = new DBViewHelp();
  dbView.show();

}

/**
 * Método estatico responsavel por carregar as dependencias da view
 */
DBViewHelp.dependencies = function() {

  if ( !CONTEXT["require_once"] ) {
    throw "Não é possível carregar as dependências (scripts.js não carregado)";
  }

  console.log('Carregando estilos do componente.')
  require_once('estilos/DBViewHelp.css');

  if ( !String.prototype["urlDecode"] ) {
    console.log("Carregando string.js");
    require_once("scripts/strings.js");
  }

  if ( !CONTEXT["DBModal"] ) {

    console.log("Carregando DBModal");
    require_once("scripts/widgets/DBModal.widget.js");
  }

  if ( !CONTEXT["Ajax"] ) {

    console.log("Carregando Prototype");
    require_once("scripts/prototype.js");
  }
}

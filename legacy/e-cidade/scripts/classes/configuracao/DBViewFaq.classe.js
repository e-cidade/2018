var CONTEXT = this;

DBViewFaq = function() {

  DBViewFaq.dependencies();

  /**
   * Instancia da DBModal
   */
  this.oDBModal = null

  /**
   * URL do RPC para buscar os dados.
   */
  this.URL_RPC = "con4_dbfaq.RPC.php";

  /**
   * Elemento que irá conter a versão
   */
  this.oSpanVersao = null;

  /**
   * Dados do HELP (FAQ)
   */
  this.aFaqs = []
}

/**
 * Método responsavel por mostrar a view em tela
 */
DBViewFaq.prototype.show = function() {

  var _this = this;

  _this.oDBModal = new DBModal();
  // _this.oDBModal.setDBMaskOptions({})

  _this.oDBModal.setTitle("Perguntas Frequentes:");

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

  oFaqContainer = this.__buildHtml();

  var oDivContainer = CONTEXT.document.createElement('div');
  oDivContainer.appendChild(oDivVersao);
  oDivContainer.appendChild(oFaqContainer);

  _this.oDBModal.setContent(oDivContainer);
  _this.oDBModal.show();

};

DBViewFaq.build = function() {

  var oDBFaq = new DBViewFaq();
  oDBFaq.show();

};

DBViewFaq.prototype.__buildHtml = function() {

  var _this = this

  var oDivContainer = CONTEXT.document.createElement('div')

  /**
   * Cria o texto do titulo
   */
  var oTitleFaq = CONTEXT.document.createElement('h2');
  oTitleFaq.innerHTML = 'Bem-vindo à Central de Ajuda do e-Cidade';

  /**
   * Cria a div principal do faq
   */
  var oDivFaq = CONTEXT.document.createElement('div');
  oDivFaq.setAttribute('class', 'db-faq');

  _this.oDivFaq = oDivFaq;

  /**
   * Cria a div descricao
   */
  var oDivDescricao = CONTEXT.document.createElement('div')
  oDivDescricao.innerHTML = 'Tire suas dúvidas sobre esta página:';
  oDivDescricao.setAttribute('class', 'db-faq-resume')

  /**
   * Insere na div principal a descricao e o container dos grupos
   */
  oDivFaq.appendChild(oDivDescricao);

  /**
   * Percorre os faqs e adiciona na div principal
   */
  _this.retrieveFaqData();

  /**
   * Insere no container o titulo e a div principal
   */
  oDivContainer.appendChild(oTitleFaq);
  oDivContainer.appendChild(oDivFaq);

  return oDivContainer
}

/**
 * Método responsavel por remontar os faqs do componente.
 */
DBViewFaq.prototype.__buildFaqs = function() {

  var _this = this

  /**
   * Remove todos os groups
   */
  aGroups = _this.oDivFaq.querySelectorAll('.db-faq-group')

  if (aGroups.length) {

    Array.prototype.slice.call(aGroups).each(function(obj) {
      obj.parentNode.removeChild(obj);
    })
  }

  /**
   * Insere todos baseado no array de faqs
   */
  _this.aFaqs.each(function(obj) {

    var oDivFaqGroup = _this.__buildFaqGroup(obj);
    _this.oDivFaq.appendChild(oDivFaqGroup);

  });

  if (_this.aFaqs.length == 0) {
    var div = CONTEXT.document.createElement('div');
    div.setAttribute('class', 'db-faq-group db-faq-group-empty');
    div.innerHTML = 'Não foi encontrado nenhum FAQ para esta rotina.';
    _this.oDivFaq.appendChild(div)
  }

}

/**
 * Seta os faqs e remonta a tela
 */
DBViewFaq.prototype.setFaqs = function(aFaqs) {

  this.aFaqs = aFaqs;
  this.__buildFaqs();
}

/**
 * Método responsavel por montar um faq-group
 * {
 *   title: "Lorem ipsum Dolore ea nostrud mollit exercitation dolor?",
 *   content: "Lorem ipsum Id cillum officia commodo deserunt ad aliqua do amet pariatur ad ad commodo eiusmod."
 * }
 */
DBViewFaq.prototype.__buildFaqGroup = function(faqData) {

  var oDivFaqGroup = CONTEXT.document.createElement('div');
  oDivFaqGroup.setAttribute('class', 'db-faq-group');

  var oEventClick = (function() {
    oDivFaqGroup.classList.toggle('active')
  }).bind(this);

  /**
   * Icone do Faq (sinal de "mais" ou de "menos")
   */
  var oDivGroupIcon = CONTEXT.document.createElement('div')
  oDivGroupIcon.setAttribute('class', 'db-faq-group-icon');
  oDivGroupIcon.onclick = oEventClick

  /**
   * Titulo do faq, no caso será um pergunta (FAQ)
   */
  var oDivGroupTitle = CONTEXT.document.createElement('div')
  oDivGroupTitle.setAttribute('class', 'db-faq-group-title');
  oDivGroupTitle.innerHTML = faqData.title;
  oDivGroupTitle.onclick = oEventClick

  /**
   * Conteudo do faq, no caso resposta da pergunta (FAQ)
   * @type {[type]}
   */
  var oDivGroupContent = CONTEXT.document.createElement('div')
  oDivGroupContent.setAttribute('class', 'db-faq-group-content');
  oDivGroupContent.innerHTML = faqData.content;

  oDivFaqGroup.appendChild(oDivGroupIcon);
  oDivFaqGroup.appendChild(oDivGroupTitle);
  oDivFaqGroup.appendChild(oDivGroupContent);

  return oDivFaqGroup;
}

/**
 * Método responsável por setar a versão passada por parametro no elemento.
 */
DBViewFaq.prototype.setVersao = function(sVersao) {

  if (!this.oSpanVersao) {
    throw "Elemento responvável por mostrar a versão ainda não criado.\nProvavelmente o método 'show' não foi chamado.";
  }

  this.oSpanVersao.innerHTML = sVersao
}

DBViewFaq.prototype.retrieveFaqData = function() {

  var _this = this;

  var oParametros = {
    exec: "getFaqData"
  }

  js_divCarregando('Aguarde...<br />Buscando os FAQ\'s', 'divCarregandoFaqs', false)
  $('divCarregandoFaqs').style.zIndex = 10001

  var oRequisicao = {
    method: "GET",
    parameters: "json=" + JSON.stringify(oParametros),
    onComplete: function(oAjax) {

      js_removeObj('divCarregandoFaqs')

      var oRetorno = JSON.parse(oAjax.responseText);

      if (oRetorno.iStatus == 2) {
        alert(oRetorno.sMessage.urlDecode())
        return;
      }

      _this.setVersao(oRetorno.sVersao ? oRetorno.sVersao : '');
      _this.setFaqs(oRetorno.aFaqs);

    }
  }

  new Ajax.Request(this.URL_RPC, oRequisicao)

}

DBViewFaq.build = function() {

  var dbView = new DBViewFaq();
  dbView.show();
}

/**
 * Método estatico responsavel por carregar as dependencias da view
 */
DBViewFaq.dependencies = function() {

  if ( !CONTEXT["require_once"] ) {
    throw "Não é possível carregar as dependências (scripts.js não carregado)";
  }

  console.log('Carregando estilos do componente.')
  require_once('estilos/DBViewFaq.css');

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
var CONTEXT = this;

DBViewReleaseNote = function(sVersao, lSomenteNaoLidos, sTipo) {

  DBViewReleaseNote.dependencies();

  this.oDBModal = null

  this.sVersao = sVersao || null;

  this.sVersaoTela = this.sVersao;

  this.lSomenteNaoLidos = lSomenteNaoLidos;

  this.URL_RPC = "con4_dbreleasenote.RPC.php";

  this.sNomeArquivo = "";

  this.aArquivosLidos = [];

  this.sTipo = sTipo;
}

DBViewReleaseNote.TIPO_SISTEMA = 'sistema';
DBViewReleaseNote.TIPO_PLUGIN = 'plugin';
DBViewReleaseNote.TIPO_PREVIA = 'previa';
DBViewReleaseNote.TIPO_MODIFICACAO = 'modificacao';

/**
 * Método responsavel por mostrar a view em tela
 * @param  boolean lRebuild False se vai remontar tudo, ou True se só vai alterar o conteudo.
 */
DBViewReleaseNote.prototype.show = function(lRebuild) {

  if (!lRebuild) {
    this.oDBModal = new DBModal();
  }

  var sTitle = 'O que mudou:';

  if (this.sTipo == DBViewReleaseNote.TIPO_PREVIA) {
    sTitle = 'Veja o que irá mudar nas próximas versões:';
  }

  this.oDBModal.setTitle(sTitle);

  var _this = this;

  var oParametros = {
    exec: "getContent",
    sVersao: this.sVersao,
    lSomenteNaoLidos: this.lSomenteNaoLidos || false,
    sNomeArquivo: this.sNomeArquivo,
    sTipo: this.sTipo,
  }

  var oRequisicao = {
    method: "GET",
    parameters: "json=" + Object.toJSON(oParametros),
    onComplete: function(oAjax) {

      var oRetorno = JSON.parse(oAjax.responseText)

      if (oRetorno.sContent == "") {
        alert('Não há registro de mudanças para esta rotina.')
        return;
      }

      _this.sVersao = oRetorno.sVersaoAtual;
      _this.sVersaoTela = oRetorno.sVersaoTela;
      _this.sNomeArquivo = oRetorno.sArquivoAtual;

      // Div do nome do ususario e da versao
      var oDivName         = CONTEXT.document.createElement('div');
      oDivName.innerHTML   = _this.getSaudacao() + ", ";
      oDivName.style.color      = "#003b85";
      oDivName.style.fontSize   = "18px";
      oDivName.style.textAlign  = "left";
      oDivName.style.marginTop  = "5px";
      oDivName.style.textIndent = "10px";

      var oSpanNomeUsuario            = CONTEXT.document.createElement('span');
      oSpanNomeUsuario.innerHTML      = oRetorno.sNomeUsuario.urlDecode();
      oSpanNomeUsuario.style.fontSize = "21px";

      oDivName.appendChild(oSpanNomeUsuario)

      /* @note: Removido exebicao da versão pois nao ha mais release com a entrega continua */
      // var oSpanVersao            = CONTEXT.document.createElement('span');
      // oSpanVersao.innerHTML      = 'Versão ' + _this.sVersaoTela;
      // oSpanVersao.style.position = "absolute";
      // oSpanVersao.style.right    = "15px";

      // oDivName.appendChild(oSpanVersao);

      _this.oDBModal.setButtons( _this.getModalButtons(oRetorno.sVersaoAnterior, oRetorno.sProximaVersao, oRetorno.sArquivoAnterior, oRetorno.sProximoArquivo) )
      _this.oDBModal.setContent(oDivName.outerHTML + oRetorno.sContent);

      if (!lRebuild) {
        _this.oDBModal.show();
      }

      if (_this.sTipo == DBViewReleaseNote.TIPO_PREVIA) {
        _this.oDBModal.oDivCabecalho.querySelector('.top').classList.add('db-release-note-previa')
      }


    }
  }

  new Ajax.Request(this.URL_RPC, oRequisicao);
};

/**
 * Método responsavel por criar os botoes customizados que irao aparecer na modal
 * @param  string sVersaoAnterior Versão anterior do release note atual
 * @param  string sProximaVersao  Proxima versao do release note atual
 * @return Object[]                 Array de botoes no padrao da DBModal
 */
DBViewReleaseNote.prototype.getModalButtons = function(sVersaoAnterior, sProximaVersao, sArquivoAnterior, sProximoArquivo) {

  var _this = this

  var oButtonNext = {}
  oButtonNext.label = "Ok, entendi!";

  if ( !empty(sProximaVersao) || !empty(sProximoArquivo) ) {
    oButtonNext.label = "Próximo";
  }

  oButtonNext.onclick = function() {

    _this.aArquivosLidos.push({sVersao: _this.sVersao, sNomeArquivo: _this.sNomeArquivo});

    if ( !empty(sProximaVersao) || !empty(sProximoArquivo) ) {

      _this.sNomeArquivo = sProximoArquivo

      if ( sProximoArquivo == "" ) {
        _this.sVersao = sProximaVersao;
        _this.sNomeArquivo = "";
      }

      _this.show(true);

    } else {
      _this.marcarComoLido();
      _this.oDBModal.destroy();
    }

  }

  var oButtonPrev = {}
  oButtonPrev.label = "Anterior"

  if ( !empty(sVersaoAnterior) || !empty(sArquivoAnterior) ) {

    oButtonPrev.onclick = function() {

      _this.aArquivosLidos.pop();

      _this.sNomeArquivo = sArquivoAnterior;

      if ( sArquivoAnterior == "" ) {
        _this.sVersao = sVersaoAnterior
        _this.sNomeArquivo = "";
      }

      _this.show(true);

    }
  } else {
    oButtonPrev.disabled =  true;
  }

  return [oButtonPrev, oButtonNext];
}


/**
 * Retorna a saudacao atual a partir do horario atual
 * @return string "Bom dia"|"Boa tarde"|"Boa noite"
 */
DBViewReleaseNote.prototype.getSaudacao = function() {

  var oData = new Date(),
      iHora = oData.getHours();

  if (iHora >= 6 && iHora <= 12 ) {
    return "Bom dia";
  } else if (iHora > 12 && iHora < 18) {
    return "Boa tarde";
  } else {
    return "Boa noite";
  }
}

/**
 * Marca o release-note como lido no banco
 */
DBViewReleaseNote.prototype.marcarComoLido = function() {

  if (this.sTipo == DBViewReleaseNote.TIPO_PREVIA) {
    return;
  }

  var oParametros = {
    exec: "marcarComoLido",
    aArquivosLidos: this.aArquivosLidos,
    sTipo: this.sTipo,
  }

  var oRequisicao = {
    method: "GET",
    parameters: "json=" + Object.toJSON(oParametros),
    onComplete: function(oAjax) {

      var oRetorno = JSON.parse(oAjax.responseText);

      if (oRetorno.iStatus == 2) {
        alert(oRetorno.sMessage.urlDecode())
        return;
      }

    }
  }

  new Ajax.Request(this.URL_RPC, oRequisicao);
}

/**
 * Método estatico para facilitar a inicializacao da view dos releases notes.
 * @param {String} sVersao Versao do release note que ira aparecer. Vazio pega o primeiro que deve aparecer
 * @param {Boolean} lSomenteNaoLidos
 * @param {String} sTipo
 */
DBViewReleaseNote.build = function(sVersao, lSomenteNaoLidos, sTipo) {

  var oDBReleaseNote = new DBViewReleaseNote(sVersao, lSomenteNaoLidos, sTipo);
  console.log(oDBReleaseNote);
  oDBReleaseNote.show();
};

/**
 * Método estatico responsavel por carregar as dependencias da view
 */
DBViewReleaseNote.dependencies = function() {

  if ( !CONTEXT["require_once"] ) {
    return console.error("Não é possível carregar as dependências (scripts.js não carregado)");
  }

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

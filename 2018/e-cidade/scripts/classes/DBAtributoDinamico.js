require_once("scripts/dates.js");
require_once("scripts/AjaxRequest.js");
require_once("scripts/widgets/DBLookUp.widget.js");
require_once("scripts/widgets/dbtextFieldData.widget.js");

DBAtributoDinamico = function () {

  this.URL_RPC = 'sys1_atributodinamico.RPC.php';

  this.oTiposInput =  {
    'TEXT'    : '1',
    'INTEGER' : '2',
    'DATE'    : '3',
    'FLOAT'   : '4',
    'BOOLEAN' : '5',
    'SELECT'  : '6'
  };

  this.iCodigoAtributos = null;

  this.iCodigoGrupoValores = null;

  this.oCamposMetadados = {};

  this.oCampos = {};
}

DBAtributoDinamico.prototype.salvar = function(callback) {

  var aAtributos = [];
  for (var sNomeCampo in this.oCamposMetadados) {

    var sValor = $(sNomeCampo).getValue();

    if (this.oCamposMetadados[sNomeCampo].iTipo == this.oTiposInput.DATE && sValor) {

        var oData = Date.convertFrom(sValor, DATA_PTBR);
        sValor = oData.getFormatedDate(DATA_EN);
    }

    var oAtributo = {
      'iCodigoAtributo': this.oCamposMetadados[sNomeCampo].iCodigo,
      'sValor': sValor
    };

    Array.push(aAtributos, oAtributo);
  }

  var oParametros = {
    'sMethod': 'salvarValorAtributo',
    'iGrupoAtributos': this.iCodigoAtributos,
    'iGrupoValor': this.iCodigoGrupoValores,
    'aAtributos': aAtributos
  };

  var oAjax = new AjaxRequest(this.URL_RPC, oParametros, function(oRetorno, lErro) {

    if (lErro) {
      return alert(oRetorno.sMsg.urlDecode())
    }

    this.iCodigoGrupoValores = oRetorno.iGrupoValor;

    if (callback) {
      callback.call(this);
    }

  }.bind(this))
    .setMessage('Salvando dados...')
    .execute();
};

DBAtributoDinamico.prototype.getCodigoGrupo = function() {
  return this.iCodigoGrupoValores;
};

DBAtributoDinamico.prototype.tipoValidacao = function(iTipo) {

  if (iTipo == this.oTiposInput.TEXT) {
    return 0;
  } else if (iTipo == this.oTiposInput.INTEGER) {
    return 1;
  } else if (iTipo == this.oTiposInput.FLOAT) {
    return 4;
  }

  return 0;
};

DBAtributoDinamico.prototype.campos = function() {

  for (var sMetadados in this.oCamposMetadados) {

    var oMetadados = this.oCamposMetadados[sMetadados];
    var oCampo     = null;
    var oLabel     = document.createElement("label");
    oLabel.setAttribute("for", oMetadados.sNomeCampo);
    oLabel.setAttribute("class", 'bold');
    oLabel.innerHTML = oMetadados.sDescricao + ":";

    if (!empty(oMetadados.oOpcoes)) {
      oMetadados.iTipo = this.oTiposInput.SELECT;
    }

    switch (oMetadados.iTipo) {

      case this.oTiposInput.SELECT :

        oCampo = document.createElement("select");
        oCampo.setAttribute('id', oMetadados.sNomeCampo);
        oCampo.setAttribute('name', oMetadados.sNomeCampo);

        var oOption = document.createElement('option');
        oOption.setAttribute('value', '');
        oOption.innerHTML ='Selecione';
        oCampo.appendChild(oOption);

        for (sOpcaoValor in oMetadados.oOpcoes) {

          var sOpcaoDescricao = oMetadados.oOpcoes[sOpcaoValor].urlDecode();
          var oOption = document.createElement('option');
          oOption.setAttribute('value', sOpcaoValor);
          oOption.innerHTML = sOpcaoDescricao;
          oCampo.appendChild(oOption);
        }

        break;

      case this.oTiposInput.INTEGER :
      case this.oTiposInput.FLOAT :
      case this.oTiposInput.TEXT :

        var iValidacao = this.tipoValidacao(oMetadados.iTipo);

        oCampo = document.createElement("input");
        oCampo.setAttribute('id', oMetadados.sNomeCampo);
        oCampo.setAttribute('name', oMetadados.sNomeCampo);
        oCampo.setAttribute('type', 'text');
        oCampo.setAttribute('onInput', "js_ValidaCampos(this, " + iValidacao + ", '" + oMetadados.sDescricao + "');");

        if (oMetadados.oCampoReferencia) {

          oCampo.setAttribute('data', oMetadados.oCampoReferencia.sNome);
          oLabel.removeAttribute('class');

          var sUrl        = 'func_' + oMetadados.oCampoReferencia.sTabela + '.php';
          var oParametros = { 'sArquivo' : sUrl };

          var oInputDescricao = document.createElement('input');
          oInputDescricao.setAttribute('type', 'hidden');

          var sUrl    = "func_" + oMetadados.oCampoReferencia.sTabela + ".php";
          var oLookUp = new DBLookUp(oLabel, oCampo, oInputDescricao, oParametros);
        }

        break;

      case this.oTiposInput.DATE:

        var oWrapper = document.createElement('span');
        oData = new DBTextFieldData(oMetadados.sNomeCampo, 'atributo_' + oMetadados.sNomeCampo, '', '');
        oData.show(oWrapper);
        oCampo = oWrapper;

        break;

      case this.oTiposInput.BOOLEAN:

        oCampo = document.createElement("select");
        oCampo.setAttribute('id', oMetadados.sNomeCampo);
        oCampo.setAttribute('name', oMetadados.sNomeCampo);

        var oOption = document.createElement('option');
        oOption.setAttribute('value', '');
        oOption.innerHTML ='Selecione';
        oCampo.appendChild(oOption);

        var oOption = document.createElement('option');
        oOption.setAttribute('value', 'f');
        oOption.innerHTML = 'Não';
        oCampo.appendChild(oOption);

        var oOption = document.createElement('option');
        oOption.setAttribute('value', 't');
        oOption.innerHTML = 'Sim';
        oCampo.appendChild(oOption);

        break;
    }

    this.oCampos[oMetadados.sNomeCampo] = [oLabel, oCampo];

    if (typeof oInputDescricao !== 'undefined') {
      Array.push(this.oCampos[oMetadados.sNomeCampo], oInputDescricao);
    }

  }

  return this.oCampos;
};

DBAtributoDinamico.prototype.carregarAtributos = function(iCodigoArquivo, callback) {

  var oParametros = {
    'sMethod'        : 'consultarAtributos',
    'iCodigoArquivo' : iCodigoArquivo
  };

  var oAjax = new AjaxRequest(this.URL_RPC, oParametros, function(oRetorno, lErro) {

    if (lErro) {
      return alert(oRetorno.sMsg.urlDecode());
    }

    for (var oAtributo of oRetorno.aAtributos) {

      var oCampo = {
        'iCodigo'          : oAtributo.iCodigo,
        'iGrupoAtributo'   : oAtributo.iGrupoAtributo,
        'iTipo'            : oAtributo.iTipo,
        'sNomeCampo'       : oAtributo.sNome,
        'oCampoReferencia' : oAtributo.oCampoReferencia,
        'sDescricao'       : oAtributo.sDescricao.urlDecode(),
        'sValorPadrao'     : oAtributo.sValorDefault.urlDecode(),
        'oOpcoes'          : oAtributo.oOpcoes
      };

      this.oCamposMetadados[oCampo.sNomeCampo] = oCampo;
    }

    this.iCodigoAtributos = oRetorno.iGrupoAtt;

    if (callback) {
      callback.call(this)
    }

  }.bind(this))
    .setMessage('Carregando dados...')
    .execute();
}

DBAtributoDinamico.prototype.carregarValores = function(iCodigoGrupoValores, callback) {

  this.iCodigoGrupoValores = iCodigoGrupoValores;

  var oParametros = {
    'sMethod'     : 'consultaAtributosValor',
    'iGrupoValor' : iCodigoGrupoValores
  };

  var oAjax = new AjaxRequest(this.URL_RPC, oParametros, function (oRetorno, lErro) {

    for (var oObjeto of oRetorno.aValoresAtributos) {

      var sValor = oObjeto.db110_valor.urlDecode();

      if (this.oCamposMetadados[oObjeto.db109_nome].iTipo === this.oTiposInput.DATE && sValor) {

        var oData = Date.convertFrom(sValor, DATA_EN);
        sValor = oData.getFormatedDate(DATA_PTBR);
      }

      $(oObjeto.db109_nome).value = sValor;
    }

    if (callback) {
      callback.call(this)
    }

  }.bind(this))
    .setMessage('Carregando dados...')
    .execute();
}

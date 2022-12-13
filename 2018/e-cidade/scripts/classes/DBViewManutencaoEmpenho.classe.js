DBViewManutencaoEmpenho = function () {

  this.sIdInstancia           = "ViewManutencaoEmpenho" + Math.round((Math.random() * 100000));
  this.oGridNotas             = null;
  this.oDivNotas              = null;
  this.oMessageBoard          = null;
  this.oInputCodigo           = null;
  this.oInputDescricao        = null;
  this.lDispensa              = null;
  this.oTextJustificativa     = null;
  this.oWindowManutencao      = null;
  this.oDivContainer          = null;
  this.oFieldsetJustificatica = null;

  var iIdWindowAux     = "wndManutencaoEmpenho";
  var sTituloWindowAux = "Manutenção de Empenho";
  var iWidthWindowAux  = 830;
  var iHeightWindowAux = 550;
  var fCallbackSalvar  = function() {};
  var me               = this;

  function attributeName(sAttribute) {
    return sAttribute + me.sIdInstancia;
  }

  this.show = function(iNumeroEmpenho, fCallback) {

    if (this.oWindowManutencao !== null) {

      var sMensagem = 'As alterações não salvas serão perdidas. Deseja continuar?';
      if (!confirm(sMensagem)) {
        return;
      }
      this.oWindowManutencao.destroy();
    }

    if (typeof fCallback !== undefined) {
      fCallbackSalvar = fCallback;
    }

    this.iNumeroEmpenho = iNumeroEmpenho;
    this.buildContainer();
    this.buscarDadosEmpenho();
  };

  /**
   * Trata o retorno da busca dos dados do empenho, salvando as informações necessárias no objeto.
   * @param oRetorno
   * @param lErro
   */
  this.retornoBuscaEmpenho = function(oRetorno, lErro) {

    if (lErro) {
      return alert(oRetorno.mensagem.urlDecode());
    }

    me.oGridNotas.clearAll(true);

    oRetorno.aNotasLiquidacao.each(function(oNota, iIndice) {

      var oCampoVencimento = new DBTextFieldData(
        "data_vencimento" + iIndice,
        "oCampoVencimento",
        oNota.sDataVencimento, null
      );

      var oCampoLocalRecebimento = new DBTextField(
        "local_recebimento" + iIndice,
        "oCampoLocalRecebimento",
        oNota.sLocalRecebimento.urlDecode()
      );

      me.oGridNotas.addRow([
        oNota.iCodigo,
        oNota.sNumero.urlDecode(),
        js_formatar(oNota.nValor, 'f', 2),
        oCampoVencimento.toInnerHtml(),
        oCampoLocalRecebimento.toInnerHtml()
      ]);
    });

    me.oGridNotas.renderRows();
    me.oInputCodigo.setAttribute("value", oRetorno.iClassificacao);
    me.oInputDescricao.setAttribute("value", oRetorno.sClassificacao);
    me.oMessageBoard.setHelp("Empenho " + oRetorno.sNumeroEmpenho);
    me.oTextJustificativa.innerHTML = oRetorno.sJustificativa;

    me.lDispensa = oRetorno.lDispensa;
    me.mostraJustificativa();
  };

  this.mostraJustificativa = function () {

    me.oFieldsetJustificatica.style.display = 'none';
    if (me.lDispensa) {
      me.oFieldsetJustificatica.style.display = 'block';
    }
  };

  /**
   * Busca os dados do empenho para manutenção.
   */
  this.buscarDadosEmpenho = function() {

    var sRpc = "emp4_manutencaoclassificacao.RPC.php";
    var oParametros = {
      exec           : "getEmpenhoPorSequencial",
      iNumeroEmpenho : this.iNumeroEmpenho
    };

    new AjaxRequest(sRpc, oParametros, this.retornoBuscaEmpenho)
      .setMessage("Carregando dados do empenho.")
      .execute();
  };

  const MENSAGENS = "financeiro.empenho.emp4_manutencaoclassificacaocredor.";
  const URL_RPC  = "emp4_manutencaoclassificacao.RPC.php";

  this.salvarDadosEmpenho = function() {

    if (me.oInputCodigo.value.trim() == "") {
      return alert(_M(MENSAGENS + 'codigo_classificacao_obrigatorio'));
    }

    var oCampos = {
      classificacao : me.oInputCodigo.value,
      justificativa : me.oTextJustificativa.value
    };

    if (me.lDispensa && empty(oCampos.justificativa.trim())) {
      return alert( _M( MENSAGENS + "justificativa_obrigatorio") );
    }

    var oParametros = {
      exec             : "salvarClassificacaoCredor",
      iNumeroEmpenho   : me.iNumeroEmpenho,
      iClassificacao   : oCampos.classificacao,
      sJustificativa   : oCampos.justificativa,
      aNotasLiquidacao : me.oGridNotas.getRows().map(function(oRow) {

        return {
          iCodigo           : oRow.aCells[0].getValue(),
          sDataVencimento   : oRow.aCells[3].getValue(),
          sLocalRecebimento : oRow.aCells[4].getValue()
        }
      })
    };

    new AjaxRequest( URL_RPC, oParametros, me.retornoSalvarDadosEmpenho)
      .setMessage("Salvando dados do empenho.")
      .execute();
  };

  this.retornoSalvarDadosEmpenho = function(oRetorno, lErro) {

    if (lErro) {
      return alert(oRetorno.mensagem.urlDecode());
    }

    alert( _M( MENSAGENS + "salvo_sucesso" ) );
    fCallbackSalvar();
    this.oWindowManutencao.hide();
  };

  this.buscaDispensa = function() {

    if (empty(me.oInputCodigo.value)) {
      return;
    }
    var sRpc = "emp1_classificacaocredores.RPC.php";
    var oParametros = {
      exec           : "getDados",
      iCodigo : me.oInputCodigo.value
    };

    new AjaxRequest(sRpc, oParametros, function (oRetorno, lErro) {

      if (lErro) {
        return alert(oRetorno.mensagem.urlDecode());
      }
      me.lDispensa = oRetorno.lDispensa;
      me.mostraJustificativa();
    })
      .setMessage("Carregando dados do empenho.")
      .execute();
  };

  /**
   * Constrói os elementos HTML e os organiza.
   */
  this.buildContainer = function() {

    this.oWindowManutencao = new windowAux(
      iIdWindowAux,
      sTituloWindowAux,
      iWidthWindowAux,
      iHeightWindowAux
    );

    var oDivContainer  = document.createElement('div');
    this.oDivContainer = oDivContainer;
    oDivContainer.id   = attributeName('notas_empenho');

    var oDivSubContainer       = document.createElement('div');
    oDivSubContainer.className = 'subcontainer';

    var oTable = document.createElement('table');
    oTable.setAttribute('margin-top', '10px');

    var oTrLookup = document.createElement('tr');
    var oTdAncora = document.createElement('td');

    var oLabelAncora = document.createElement('label');
    oLabelAncora.className = 'bold';
    oLabelAncora.setAttribute('for', attributeName('codigo'));

    var oAncora = document.createElement('a');
    oAncora.id  = attributeName('ancora');
    oAncora.innerHTML = 'Lista de Classificação de Credores: ';

    var oTdInputs = document.createElement('td');

    this.oInputCodigo = document.createElement('input');
    this.oInputCodigo.setAttribute('lang', 'cc30_codigo');
    this.oInputCodigo.setAttribute('type', 'text');
    this.oInputCodigo.setAttribute('id', attributeName('codigo'));

    this.oInputDescricao = document.createElement('input');
    this.oInputDescricao.setAttribute('lang', 'cc30_descricao');
    this.oInputDescricao.setAttribute('type', 'text');
    this.oInputDescricao.setAttribute('id', attributeName('descricao'));

    this.oFieldsetJustificatica = document.createElement("fieldset");
    this.oFieldsetJustificatica.setAttribute('id', attributeName('container_justificativa'));
    this.oFieldsetJustificatica.setAttribute('class', 'separator');
    this.oFieldsetJustificatica.style.display = 'none';

    var oLegendJustificativa = document.createElement('legend');
    var oLabelJustificativa  = document.createElement('label');
    oLabelJustificativa.setAttribute('for', attributeName('justificativa'));
    oLabelJustificativa.setAttribute('class', 'bold');
    oLabelJustificativa.innerHTML = "Justificativa";

    this.oTextJustificativa = document.createElement('textarea');
    this.oTextJustificativa.setAttribute('id', attributeName('justificativa'));
    this.oTextJustificativa.style.width = '100%';
    this.oTextJustificativa.setAttribute('rel', 'ignore-css');

    var oFieldsetNotas = document.createElement('fieldset');
    oFieldsetNotas.setAttribute('class', 'separetor');
    oFieldsetNotas.style.marginTop    = '10px';
    oFieldsetNotas.style.marginBottom = '10px';

    var oLegendNotas = document.createElement('legend');
    oLegendNotas.innerHTML = 'Dados de Notas de Liquidação';

    this.oDivNotas = document.createElement('div');
    this.oDivNotas.setAttribute('id', attributeName('grid_notas'));
    this.oDivNotas.style.width = '800px';

    var oInputSalvar = document.createElement('input');
    oInputSalvar.setAttribute('type', 'button');
    oInputSalvar.setAttribute('value', 'Salvar');
    oInputSalvar.setAttribute('id', attributeName('salvar'));
    oInputSalvar.observe('click', this.salvarDadosEmpenho);

    oLabelAncora.appendChild(oAncora);
    oTdAncora.appendChild(oLabelAncora);
    oTdInputs.appendChild(this.oInputCodigo);
    oTdInputs.appendChild(this.oInputDescricao);
    oTrLookup.appendChild(oTdAncora);
    oTrLookup.appendChild(oTdInputs);
    oTable.appendChild(oTrLookup);

    oLegendJustificativa.appendChild(oLabelJustificativa);
    this.oFieldsetJustificatica.appendChild(oLegendJustificativa);
    this.oFieldsetJustificatica.appendChild(this.oTextJustificativa);

    oFieldsetNotas.appendChild(oLegendNotas);
    oFieldsetNotas.appendChild(this.oDivNotas);

    oDivSubContainer.appendChild(oTable);
    oDivSubContainer.appendChild(this.oFieldsetJustificatica);
    oDivSubContainer.appendChild(oFieldsetNotas);
    oDivSubContainer.appendChild(oInputSalvar);

    oDivContainer.appendChild(oDivSubContainer);

    this.oMessageBoard = new DBMessageBoard("oMessageBoard", "Manutenção de Empenho", "", oDivContainer);
    var oLookup = new DBLookUp(oAncora, this.oInputCodigo, this.oInputDescricao, {
      "sArquivo"              : "func_classificacaocredores.php",
      "sObjetoLookUp"         : "db_iframe_classificacaocredores",
      "sLabel"                : "Pesquisar Lista de Classificaçao de Credores",
      "zIndex"                : (this.oWindowManutencao.zIndex + 1),
      "fCallBack"             : me.buscaDispensa
    });

    this.oWindowManutencao.setContent(oDivContainer);
    this.oMessageBoard.show();

    this.oGridNotas = new DBGrid("oGridNotas");
    this.oGridNotas.setHeader(["Sequencial da Nota", "Número da Nota", "Valor", "Data de Vencimento", "Local de Recebimento"]);
    this.oGridNotas.setCellAlign(["left", "center", "right", "center", "left"]);
    this.oGridNotas.setCellWidth(["15%", "15%", "15%", "20%", "35%"]);
    this.oGridNotas.setHeight(200);

    this.oWindowManutencao.setShutDownFunction(function () {

      me.oWindowManutencao.destroy();
      me.oWindowManutencao = null;
    });

    this.oWindowManutencao.show();
    this.oGridNotas.show(this.oDivNotas);
  };

};
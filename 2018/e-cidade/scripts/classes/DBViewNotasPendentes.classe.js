/**
 * View para apresentação das notas pendentes de um bem
 *
 * @author matheus.felini
 */
DBViewNotasPendentes = function(sNameInstance, lUsaPCASP) {

  var me                     = this;
  this.sNameInstance         = sNameInstance;
  this.sUrlRPC               = "pat4_bensnotaspendentes.RPC.php";
  this.aCodigoNota           = new Array();
  this.lLocationGlobal       = false;
  this.oWindowNotasPendentes = null;
  this.callBackDoubleClick   = function() {};
  this.callBackConfirmar     = function() {};
  this.sTextoRodape          = "";
  this.iOffset               = 0;
  this.lHabilitarCheckbox    = false;

  /**
   * Controla se exibe os itens com entradas de ordem de compra fracionada
   */
  this.lExibirItemFracionado = true;

  this.exibirItemFracionado = function(lExibir) {
    this.lExibirItemFracionado = lExibir;
  }

  this.setHabilitarCheckbox = function(lHabilitarCheckbox) {

    this.iOffset = 1;
    this.lHabilitarCheckbox = lHabilitarCheckbox;
  };

  /**
   * Método Show
   * Este método mostra a windowAux com as notas pendentes existentes
   */
  this.show = function () {

    /**
     * Criamos a WindowAux com que irá apresentar as notas pendentes dos itens
     */
    var sTituloWndNotasPentendes = "Notas Pendentes";
    if (this.getCodigoNota() != "") {
      sTituloWndNotasPentendes = "Bens com notas pendentes";
    }

    this.oWindowNotasPendentes    = new windowAux('oWndNotasPendentes_'+this.sNameInstance, sTituloWndNotasPentendes, 925, 385);
    this.oWindowNotasPendentes.zIndex = 1;

    var sContentNotasPendentes   = "<div class=\"subcontainer\"><fieldset style=\"width: 895px\">";
        sContentNotasPendentes  += "  <div id='ctnGridNotasPendentes'></div> </fieldset>";
        sContentNotasPendentes  += "</div>";

    if (this.getCodigoNota() == "" && !this.lHabilitarCheckbox) {
      sContentNotasPendentes += this.sTextoRodape;
    }

    this.oWindowNotasPendentes.setContent(sContentNotasPendentes);
    this.oWindowNotasPendentes.show();

    /**
     * Esconde janela ao clicar no icone para fechar ou apertar tecla ESC
     */
    this.oWindowNotasPendentes.setShutDownFunction(function() {
      me.oWindowNotasPendentes.hide();
    })

    /**
     * Criamos a messageBoard para a WindowAux
     */
    var sTituloNotasPendentes = _M('patrimonial.patrimonio.DBViewNotasPendentes.notas_pendentes');
    var sHelpNotasPendentes   = _M('patrimonial.patrimonio.DBViewNotasPendentes.existem_bens_notas_pedentes');

    if (!this.lExibirItemFracionado) {
      sHelpNotasPendentes  += ' ' + _M('patrimonial.patrimonio.DBViewNotasPendentes.sem_fracionamento');
    }

    if (this.getCodigoNota() != "") {

      sTituloNotasPendentes = _M('patrimonial.patrimonio.DBViewNotasPendentes.bens_notas_pendentes');
      sHelpNotasPendentes   = _M('patrimonial.patrimonio.DBViewNotasPendentes.bens_nao_localizados');
    }

    var oMsgBoardNotasPendentes  = new DBMessageBoard('oMsgBoardNotasPendentes_' + this.sNameInstance,
                                                      sTituloNotasPendentes,
                                                      sHelpNotasPendentes,
                                                      this.oWindowNotasPendentes.getContentContainer());
    oMsgBoardNotasPendentes.show();

    /**
     * Montamos a grid que vai mostrar as notas pendentes dos itens
     */
    oGridNotasPendentes              = new DBGrid('ctnGridNotasPendentes');
    oGridNotasPendentes.nameInstance = 'oGridNotasPendentes';
    var aCellWidth                   = new Array('17%','15%', '12%', '48%', '13%', '17%');
    var aCellAlign                   = new Array('center', 'right', 'center', 'left', 'right', 'right');
    var aHeaders                     = new Array('Ordem de Compra',
                                                 'Valor da OC',
                                                 'Empenho',
                                                 'Item',
                                                 'Quantidade',
                                                 'Desdobramento',
                                                 'Codigo empnotaitembenspendente',
                                                 'Codigo Nota Item',
                                                 'numeroempenho');

    if (this.lHabilitarCheckbox) {
      oGridNotasPendentes.setCheckbox(0);
    }

    oGridNotasPendentes.setHeight(200);
    oGridNotasPendentes.setCellWidth(aCellWidth);
    oGridNotasPendentes.setHeader(aHeaders);
    oGridNotasPendentes.aHeaders[6 + this.iOffset].lDisplayed = false;
    oGridNotasPendentes.aHeaders[7 + this.iOffset].lDisplayed = false;
    oGridNotasPendentes.aHeaders[8 + this.iOffset].lDisplayed = false;
    oGridNotasPendentes.setCellAlign(aCellAlign);
    oGridNotasPendentes.show($('ctnGridNotasPendentes'));

    /**
     * Verifico se existe código de nota setado. Caso exista, chamamos o método que
     * busca os bens da nota informada.
     */
    if (this.getCodigoNota() == "") {
      this.getNotasPendentes();
    } else {
      this.getBensCodigoNota();
    }

    if (this.lHabilitarCheckbox) {

      var _this = this;
          oDiv = document.createElement("div");
          oInput = document.createElement("input")

      oDiv.classList.add("subcontainer");
      oInput.type = "button";
      oInput.id = "btnConfirmaCheckbox";
      oInput.value = "Confirmar";
      oInput.onclick = function() {
        _this.loadCallBackConfirmar();
      }

      oDiv.appendChild(oInput);
      this.oWindowNotasPendentes.getContentContainer().appendChild(oDiv);
    }
  }

  /**
   * Método que busca as notas pendentes do bem
   */
  this.getNotasPendentes = function() {

    js_divCarregando(_M('patrimonial.patrimonio.DBViewNotasPendentes.buscando_notas_pendentes'), "msgBox");

    oParam = {
      exec : "getNotasPendentes",
      lExibirItemFracionado : this.lExibirItemFracionado
    };

    var oAjax   = new Ajax.Request(this.sUrlRPC,
                                  {method: 'post',
                                   parameters: 'json='+Object.toJSON(oParam),
                                   onComplete: this.preencheGridNotasPendentes
                                  });
  }

  /**
   * Busca os bens de um nota
   */
  this.getBensCodigoNota = function () {

    js_divCarregando(_M('patrimonial.patrimonio.DBViewNotasPendentes.buscando_bens_nota'), "msgBox");

    var oParam         = new Object();
    oParam.exec        = "getBensPorCodigoNota";
    oParam.aCodigoNota = this.getCodigoNota();

    var oAjax   = new Ajax.Request(this.sUrlRPC,
                                  {method: 'post',
                                   parameters: 'json='+Object.toJSON(oParam),
                                   onComplete: this.preencheGridNotasPendentes
                                  });
  }

  /**
   * Preenche notas pendentes do bem
   */
  this.preencheGridNotasPendentes = function(oAjax) {

    js_removeObj("msgBox");
    oGridNotasPendentes.clearAll(true);
    var oRetorno = eval("("+oAjax.responseText+")");

    if ($('db_opcao')) {
      $('db_opcao').disabled = false;
    }
    if (oRetorno.aNotasPendentes.length == 0) {
      //$('db_opcao').disabled = true;
    } else {

      oRetorno.aNotasPendentes.each(function (oNotaPendente, iLinha) {

        var aLinha    = new Array();
            aLinha[0] = oNotaPendente.codigonota;
            aLinha[1] = js_formatar(oNotaPendente.valornota, "f");
            aLinha[2] = "<a href='#' onclick='" + me.sNameInstance + ".consultaEmpenho(" + oNotaPendente.numeroempenho + ");' title='Consultar empenho'>" + oNotaPendente.codigoempenho + "/" +  oNotaPendente.anoempenho + "</a>";
            aLinha[3] = oNotaPendente.descricao.urlDecode();
            aLinha[4] = oNotaPendente.quantidade;
            aLinha[5] = oNotaPendente.desdobramento;
            aLinha[6] = oNotaPendente.e137_sequencial;
            aLinha[7] = oNotaPendente.codigoitemnota;
            aLinha[8] = oNotaPendente.numeroempenho;

        oGridNotasPendentes.addRow(aLinha);

        if (me.getCodigoNota() == "") {
          oGridNotasPendentes.aRows[iLinha].sEvents = "ondblclick='" + me.sNameInstance+".loadCallBackDoubleClick("+iLinha+");'";
        }
      });
      oGridNotasPendentes.renderRows();
    }
  }

  this.consultaEmpenho = function(nEmpenho) {

    js_OpenJanelaIframe('', 'iframeConsultaEmpenho', 'func_empempenho001.php?e60_numemp=' + nEmpenho, 'Empenho ' + nEmpenho, true);
    $('JaniframeConsultaEmpenho').style.zIndex = 2;
  }

  /**
   * Seta um valor para a propriedade aCodigoNota
   */
  this.setCodigoNota = function (aCodigoNota) {
    this.aCodigoNota = aCodigoNota;
  }

  /**
   * Retorna o valor para a propriedade aCodigoNota
   */
  this.getCodigoNota = function () {
    return this.aCodigoNota;
  }

  /**
   * Seta se o componente foi chamado pelo item de menu bens global
   */
  this.setLocationGlobal = function (lGlobal) {
    this.lLocationGlobal = lGlobal;
  }

  /**
   * Retorna o valor para a propriedade lLocationGlobal
   */
  this.getLocationGlobal = function () {
    return this.lLocationGlobal;
  }

  /**
   * Carrega dados da linha e chama funcao definina pelo setCallBackDoubleClick()
   *
   * @param integer iLinhaGrid - linha da grid que foi clickada
   */
  this.loadCallBackDoubleClick = function(iLinhaGrid) {

    /**
     * Objeto com dados da linha que foi dado dois clicks
     */
    var oDadosLinha = new Object();

    oDadosLinha.iCodigoNota         = oGridNotasPendentes.aRows[iLinhaGrid].aCells[0 + this.iOffset].getContent();
    oDadosLinha.nValorNota          = oGridNotasPendentes.aRows[iLinhaGrid].aCells[1 + this.iOffset].getContent();
    oDadosLinha.iNumeroEmpenho      = oGridNotasPendentes.aRows[iLinhaGrid].aCells[8 + this.iOffset].getContent();
    oDadosLinha.sDescricaoItem      = oGridNotasPendentes.aRows[iLinhaGrid].aCells[3 + this.iOffset].getContent();
    oDadosLinha.iQuantidadeItem     = oGridNotasPendentes.aRows[iLinhaGrid].aCells[4 + this.iOffset].getContent();
    oDadosLinha.iCodigoItemPendente = oGridNotasPendentes.aRows[iLinhaGrid].aCells[6 + this.iOffset].getContent();
    oDadosLinha.iCodigoEmpNotaItem  = oGridNotasPendentes.aRows[iLinhaGrid].aCells[7 + this.iOffset].getContent();

    /**
     * Executa funcao definida passando como parametro dados da linha clicada
     */
    return this.callBackDoubleClick(oDadosLinha);
  }

  /**
   * Carrega dados das linhas marcadas e chama a função defininda pelo setCallBackConfirmar()
   */
  this.loadCallBackConfirmar = function() {

    aLinhas = oGridNotasPendentes.getSelection('array');
    return this.callBackConfirmar(aLinhas);
  }

  /**
   * Define a funcao que ser ausada ao clicar duas vezes na linha da grid
   *
   * @param function callBackDoubleClick
   */
  this.setCallBackDoubleClick = function(callBackDoubleClick) {
    this.callBackDoubleClick = callBackDoubleClick;
  }

  /**
   * Define a função de callback a ser utiliza quando o botão "Confirmar" for clicado
   */
  this.setCallBackConfirmar = function(callBackConfirmar) {
    this.callBackConfirmar = callBackConfirmar;
  };

  /**
   * Define o texto que sera exibido no rodape, abaixo da grid
   *
   * @param string sTextoRodape
   */
  this.setTextoRodape = function(sTextoRodape) {
    this.sTextoRodape = sTextoRodape;
  }

  /**
   * Retorna o objeto WindowAux
   *
   * @return windowAux
   */
  this.getWindowAux = function() {
    return this.oWindowNotasPendentes;
  }

}

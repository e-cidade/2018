var DBViewCadastroUnica = function(sInstance) {

  var me                  = this;
  var sTipoPesquisa       = null;
  var sChavePesquisa      = null;
  var dtDataUsu           = null;
  var iAnoUsu             = "";
  var sDataFormatada      = "";
  var sInstanceName       = sInstance;
  this.oForm              = new Object();
  var aDadosTipoOrigem    = new Array();
  var aDadosAnoOrigem     = new Array();
  this.oElemento          = null;
  var aExercicios         = new Array();
  var sRpc                = "arr4_cadastroUnica.RPC.php";
  /**
   * Objeto Com os labels dos Campos
   */
  this.oLabel  = {

    fieldsetOrigem     : "<strong>Origem Secundária:      </strong>",
    fieldsetGridOrigem : "<strong>Débitos:                </strong>",
    fieldsetInformacoes: "<strong>Informações:            </strong>",
    tipoDebito         : "<strong>Tipo de Débito:         </strong>",
    anoOrigem          : "<strong>Ano Origem:             </strong>",
    dataVencimento     : "<strong>Data de Vencimento:    </strong>",
    dataLancamento     : "<strong>Data de Lançamento:     </strong>",
    percentualDesconto : "<strong>Percentual de Desconto: </strong>",
    observacoes        : "<strong>Observações:            </strong>",
  };

  this.oAcoes             = {
    /**
     * Carrega os dados da combo dos exercicios
     * @param iCadTipoDebito - tipo de débito da tabela cadtipo
     */
    loadExercicios       : function(iCadTipoDebito) {

      /**
       * Caso o tipo de inclusão
       * seja individual, desabilita elemetos
       * do formulário de informações
       */
      if (sChavePesquisa !== null) {
        me.oAcoes.disableFieldsForm();
      };
      me.oDatagridDebitos.clear();
      if (iCadTipoDebito == "0") {

        me.oForm.cboAnoDebitos.clearItens();
        me.oForm.cboAnoDebitos.addItem(0, "Selecione");
        me.oForm.cboAnoDebitos.setDisable();

      } else {
        /**
         * Carrega os dados da combo dos exercicios
         * e recarrega os daddos da grid
         */
        js_divCarregando("Carregando exercícios...","msg");

        aExercicios       = me.getDataSource.exerciciosDebito(iCadTipoDebito);
        me.oForm.cboAnoDebitos.clearItens();
        me.oForm.cboAnoDebitos.addItem(0, "Todos");
        me.oForm.cboAnoDebitos.setEnable();
        for (var iI = 0; iI < aExercicios.length; iI++) {
          me.oForm.cboAnoDebitos.addItem(aExercicios[iI],aExercicios[iI]);
        }
        js_removeObj("msg");
      }
      me.oForm.cboAnoDebitos.setValue(new Number(iAnoUsu));

      me.oAcoes.loadDadosGrid(me.oForm.cboAnoDebitos.getValue());

    },
    /**
     * Carrega os numpres dos debitos
     * @param iExercioDebito
     */
    loadDadosGrid        : function(iExercioDebito) {
      /***
       * Limpa os dados da GRID
       */
      me.oDatagridDebitos.clear();

      if (sChavePesquisa !== null) {
        me.oAcoes.disableFieldsForm();
      };

      iCadTipoDebito =  me.oForm.cboTipoDebitos.getValue();
      /**
       * carrega os débitos para o exercicio selecionado.
       */
      if (iCadTipoDebito != "0") {
        me.oDatagridDebitos.loadData(iExercioDebito, iCadTipoDebito);
      } else {
        me.oDatagridDebitos.loadData(0, iCadTipoDebito);
      }
    },
    /**
     * Desabilita os Campos do formulário Informações
     */
    disableFieldsForm    : function() {

      me.oForm.inputDataVencimento.setReadOnly(true);
      me.oForm.inputDataVencimento.setValue("");

      me.oForm.inputDataLancamento.setReadOnly(true);

      me.oForm.inputPercentual    .setReadOnly(true);
      me.oForm.inputPercentual    .setValue("");

      me.oForm.oTextAreaObservacoes.readOnly              = true;
      me.oForm.oTextAreaObservacoes.style.backgroundColor = "rgb(222, 184, 135)";
    },
    /**
     * Habilita os Campos do formulário Informações
     */
    enableFieldsForm     : function() {

      me.oForm.inputDataVencimento.setReadOnly(false);
      me.oForm.inputDataLancamento.setReadOnly(false);
      me.oForm.inputPercentual    .setReadOnly(false);

      me.oForm.oTextAreaObservacoes.readOnly              = false;
      me.oForm.oTextAreaObservacoes.style.backgroundColor = "rgb(255, 255, 255)";

    },
   /**
    * Valida os registros da grid
    */
    checkFieldsForms     : function(oElemento) {

      var aRetorno = new Array();
      var aDados = me.oGridDebitos.getSelection();
      aDados.each(function(aCampo, iIndice) {
        aRetorno[iIndice] = aCampo[0];
      });
      if (aRetorno.length == 0) {
        me.oAcoes.disableFieldsForm();
      } else {
        me.oAcoes.enableFieldsForm();
      }
    },
    /**
     * Faz validação do campo
     * @returns {object}
     */
    validaCampos         : function() {

      var oRetorno  = new Object();
      oRetorno.iStatus   = 1;
      oRetorno.sMensagem = "OK";
      var oDadosForm = me.getDados();

      if (oDadosForm.iCadTipoDebito == null || oDadosForm.iCadTipoDebito == "") {

        oRetorno.iStatus   = 2;
        oRetorno.sMensagem = "Tipo de Débito não Informado";
      } else if (oDadosForm.aExercicio.length == 0) {

        oRetorno.iStatus   = 2;
        oRetorno.sMensagem = "Exercicios não Informado";
      } else if ( (sChavePesquisa !== null) && (oDadosForm.aNumpres.length == 0) ) {

        oRetorno.iStatus   = 2;
        oRetorno.sMensagem = "Nenhum débito selecionado";
      } else if (oDadosForm.dtVencimento == null || oDadosForm.dtVencimento == "") {

        oRetorno.iStatus   = 2;
        oRetorno.sMensagem = "Data de Vencimento não Informada";
      } else if (oDadosForm.dtLancamento == null || oDadosForm.dtLancamento == "") {

        oRetorno.iStatus   = 2;
        oRetorno.sMensagem = "Data de Lançamento não Informada";
      } else if (oDadosForm.nPercentual == null || oDadosForm.nPercentual == "") {

        oRetorno.iStatus   = 2;
        oRetorno.sMensagem = "Percentual de Desconto não Informado";
      } else if (isNaN(oDadosForm.nPercentual)) {

        oRetorno.iStatus   = 2;
        oRetorno.sMensagem = "Percentual de Desconto deve conter apenas números.";
      }
      return oRetorno;
    }
  };

  this.getDataSource      = {
    /**
     * Retorna os débitos passiveis de emissao de cota unica
     * @returns {Array}
     */
    debitosGrid      : function(iExercicio, iCadTipoDebito) {

      var aRetorno          = new Array();
      var oParam            = new Object();
      oParam.exec           = 'getDebitos';
      oParam.sTipoPesquisa  = sTipoPesquisa;
      oParam.sChavePesquisa = sChavePesquisa;
      oParam.iExercicio     = iExercicio;
      oParam.iCadTipoDebito = iCadTipoDebito;

      var oExec            = new Object();
      oExec.method         = 'post';
      oExec.parameters     = 'json=' + Object.toJSON(oParam);
      oExec.asynchronous   = false;
      oExec.onComplete     = function(oAjax) {

        var oRetorno = eval("("+oAjax.responseText+")");

        if (oRetorno.status == "2") {

          alert(oRetorno.message.urlDecode());
          return false;

        } else {

          for (var i = 0; i < oRetorno.aDados.length; i++) {
            aRetorno.push(oRetorno.aDados[i]);
          };
        };
      };
      this.oAjax       = new Ajax.Request(sRpc, oExec);
      return aRetorno;
    },
    /**
     * Retorna um array com os tipos de débito conforme o
     * @returns {Array}
     */
    tiposDebito      : function() {

      var aRetorno            = new Array();

      var oParam              = new Object();
      oParam.exec             = 'getTiposDebito';
      oParam.sTipoPesquisa    = sTipoPesquisa;
      oParam.sChavePesquisa   = sChavePesquisa === null ? "" : sChavePesquisa;

      var oExec               = new Object();
      oExec.method            = 'post';
      oExec.parameters        = 'json=' + Object.toJSON(oParam);
      oExec.asynchronous      = false;
      oExec.onComplete        = function(oAjax) {

        var oRetorno = eval("("+oAjax.responseText+")");

        if (oRetorno.status == "2") {

          alert(oRetorno.message.urlDecode());
          return false;

        } else {
          aRetorno[0]  = "Selecione";
          for (var i = 0; i < oRetorno.aDados.length; i++) {
            with (oRetorno.aDados[i]) {
              aRetorno[k03_tipo] = k03_tipo + " - " + k03_descr.urlDecode();
            };
          };
        };
      };
      this.oAjax       = new Ajax.Request(sRpc, oExec);
      return aRetorno;
    },
    /**
     * Retorna um array com o exercicios disponiveis para execução do cálculo
     * @param iCadTipoDebito
     * @returns {Array}
     */
    exerciciosDebito : function(iCadTipoDebito) {

      var aRetorno            = new Array();
      var oParam              = new Object();
      oParam.exec             = 'getExercicios';
      oParam.sTipoPesquisa    = sTipoPesquisa;
      oParam.sChavePesquisa   = sChavePesquisa;
      oParam.iCadTipoDebito   = iCadTipoDebito;

      var oExec               = new Object();
      oExec.method            = 'post';
      oExec.parameters        = 'json=' + Object.toJSON(oParam);
      oExec.asynchronous      = false;
      oExec.onComplete        = function(oAjax) {

        var oRetorno = eval("("+oAjax.responseText+")");

        if (oRetorno.status == "2") {

          alert(oRetorno.message.urlDecode());
          return false;

        } else {

          for (var i = 0; i < oRetorno.aDados.length; i++) {
            aRetorno[i] = oRetorno.aDados[i].exercicio;
          }
        };
      };
      this.oAjax       = new Ajax.Request(sRpc, oExec);
      return aRetorno;
    }
  };

/**
 * Definições de comportamento
 */
  this.oDatagridDebitos       = {
    /**
     * Renderiza grid
     */
    renderGrid  : function() {

      me.oGridDebitos              = new DBGrid(sInstanceName + '.oGridDebitos');
      me.oGridDebitos.nameInstance = sInstanceName + '.oGridDebitos';
      me.oGridDebitos.sName        = '.oGridDebitos';
      me.oGridDebitos.setCheckbox  ( 0 );
      me.oGridDebitos.setCellAlign (["center", "center", "right"]);
      me.oGridDebitos.setHeader    (["Exercício", "Numpre", "Valor"]);
      me.oGridDebitos.show         ( $(sInstanceName + '_ctnGridTipoDebito') );
    },
    /**
     * Carrega dados e renderiza as linhas
     */
    loadData    : function(iExercicio, iCadTipoDebito) {
      if (sChavePesquisa !== null) {
        js_divCarregando("Carregando débitos...","msg");

        var aDadosGrid  = me.getDataSource.debitosGrid(iExercicio, iCadTipoDebito);
        me.oDatagridDebitos.clear();
        var aChecks = new Array();

        for (var i = 0; i < aDadosGrid.length; i++) {

          with(aDadosGrid[i]) {

            var aLinha  = [exercicio, numpre, js_formatar(valor,"f")];
            me.oGridDebitos.addRow(aLinha);

            var oDadosCheck          = new Object();
            oDadosCheck.idCheckBox   = me.oGridDebitos.aRows[i].aCells[0].sId;
            oDadosCheck.sReference   = numpre;
            aChecks[i]               = oDadosCheck;
          }
        }

        me.oGridDebitos.renderRows();
        /**
         * Define funções personalizadas na seleção da grid
         */
        aChecks.each(function(oTarget, id) {

          var oCheck  = $(oTarget.idCheckBox).getElementsByTagName("input")[0];
          var sOnclick= oCheck.getAttribute("onClick");
              oCheck.setAttribute("onClick",sOnclick + ";" + sInstanceName + ".oAcoes.checkFieldsForms(this); ");
        });
        /***
         * define função personalizada ao clicar no link "marcar todas"
         */
        var oLink = $(me.oGridDebitos.nameInstance + "SelectAll");
        var sOnClick = oLink.getAttribute("onClick");
        oLink.setAttribute("onClick",sOnClick + ";" + sInstanceName + ".oAcoes.checkFieldsForms(this);");
        js_removeObj("msg");
      }
    },
    /**
     * Limpa os elementos da grid
     */
    clear       : function() {

      if (sChavePesquisa !== null) {
        me.oGridDebitos.clearAll(true);
      }
    }
  };

  /**
   * Cria HTML do Componente
   */
  this.renderHTML        = function() {

    if (me.oElemento !== null) {

      me.oForm.oFieldSetOrigem                    = document.createElement("fieldset");

      me.oForm.oLegendOrigem                      = document.createElement("legend");
      me.oForm.oLegendOrigem.innerHTML            = me.oLabel.fieldsetOrigem;

      me.oForm.oTableOrigem                       = document.createElement("table");

      me.oForm.oRowTipoDebito                     = document.createElement("tr");

      me.oForm.oCellLabelTipoDebito               = document.createElement("td");
      me.oForm.oCellLabelTipoDebito.innerHTML     = me.oLabel.tipoDebito;

      me.oForm.oCellCtnTipoDebito                 = document.createElement("td");
      me.oForm.oCellCtnTipoDebito.id              = sInstanceName + "ctnTipoDebitos";

      me.oForm.oRowAnoOrigem                      = document.createElement("tr");

      me.oForm.oCellLabelAnoOrigem                = document.createElement("td");
      me.oForm.oCellLabelAnoOrigem.innerHTML      = me.oLabel.anoOrigem;

      me.oForm.oCellCtnAnoOrigem                  = document.createElement("td");
      me.oForm.oCellCtnAnoOrigem.id               = sInstanceName + "ctnAnoDebitos";



      me.oElemento                                . appendChild(me.oForm.oFieldSetOrigem);
      me.oForm.oFieldSetOrigem                    . appendChild(me.oForm.oLegendOrigem);
      me.oForm.oFieldSetOrigem                    . appendChild(me.oForm.oTableOrigem);
      me.oForm.oTableOrigem                       . appendChild(me.oForm.oRowTipoDebito);
      me.oForm.oTableOrigem                       . appendChild(me.oForm.oRowAnoOrigem);
      me.oForm.oRowTipoDebito                     . appendChild(me.oForm.oCellLabelTipoDebito);
      me.oForm.oRowTipoDebito                     . appendChild(me.oForm.oCellCtnTipoDebito);
      me.oForm.oRowAnoOrigem                      . appendChild(me.oForm.oCellLabelAnoOrigem);
      me.oForm.oRowAnoOrigem                      . appendChild(me.oForm.oCellCtnAnoOrigem);

      if (sChavePesquisa !== null) {

        me.oForm.oFieldSetGridOrigem               = document.createElement("fieldset");
        me.oForm.oFieldSetGridOrigem.style.width   = document.createElement("fieldset");

        me.oForm.oLegendGridOrigem                = document.createElement("legend");
        me.oForm.oLegendGridOrigem.innerHTML      = me.oLabel.fieldsetGridOrigem;
        me.oForm.oDivGridTipoDebito               = document.createElement("div");
        me.oForm.oDivGridTipoDebito.id            = sInstanceName + '_ctnGridTipoDebito';
        me.oForm.oFieldSetOrigem                  . appendChild(me.oForm.oFieldSetGridOrigem);
        me.oForm.oFieldSetGridOrigem              . appendChild(me.oForm.oLegendGridOrigem);
        me.oForm.oFieldSetGridOrigem              . appendChild(me.oForm.oDivGridTipoDebito);
      }
      /*
       * Busca os dados dos combos via RPC;
       */
      var aTiposDebito                          = me.getDataSource.tiposDebito();

      me.oForm.cboTipoDebitos                   = new DBComboBox('cboTipoDebitos',  sInstanceName + ".oForm.cboTipoDebitos", aTiposDebito);
      me.oForm.cboTipoDebitos                   . addEvent("onChange",  sInstanceName + ".oAcoes.loadExercicios(this.value)");
      me.oForm.cboTipoDebitos                   . show($(sInstanceName + "ctnTipoDebitos"));

      me.oForm.cboAnoDebitos                    = new DBComboBox('cboAnoDebitos',   sInstanceName + ".oForm.cboAnoDebitos", ["Selecione"], 90);
      me.oForm.cboAnoDebitos                    . addEvent("onChange",  sInstanceName + ".oAcoes.loadDadosGrid(this.value)");
      me.oForm.cboAnoDebitos                    . show($(sInstanceName + "ctnAnoDebitos"));
      me.oForm.cboAnoDebitos                    . setDisable();
    }
  };

  /**
   * Monta formulário de manutenção da rotina.
   */
  this.renderHTMLInfo    = function() {

    if (me.oElemento !== null) {
      /**
       * CRiando elementos
       */
      me.oForm.oFieldSetInformacoes                             = document.createElement("fieldset");
        me.oForm.oLegendInformacoes                             = document.createElement("legend");
        me.oForm.oLegendInformacoes.innerHTML                   = me.oLabel.fieldsetInformacoes;

        me.oForm.oTableInformacoes                              = document.createElement("table");
          me.oForm.oRowDataVencimento                           = document.createElement("tr");
            me.oForm.oCellLabelDataVencimento                   = document.createElement("td");
            me.oForm.oCellLabelDataVencimento.innerHTML         = me.oLabel.dataVencimento;
            me.oForm.oCellCtnDataVencimento                     = document.createElement("td");
            me.oForm.oCellCtnDataVencimento.id                  = sInstanceName + "ctnDataVencimento";
          me.oForm.oRowDataLancamento                           = document.createElement("tr");
            me.oForm.oCellLabelDataLancamento                   = document.createElement("td");
            me.oForm.oCellLabelDataLancamento.innerHTML         = me.oLabel.dataLancamento;
            me.oForm.oCellCtnDataLancamento                     = document.createElement("td");
            me.oForm.oCellCtnDataLancamento.id                  = sInstanceName + "ctnDataLancamento";
          me.oForm.oRowPercentual                               = document.createElement("tr");
            me.oForm.oCellLabelPercentual                       = document.createElement("td");
            me.oForm.oCellLabelPercentual.innerHTML             = me.oLabel.percentualDesconto;
            me.oForm.oCellCtnPercentual                         = document.createElement("td");
            me.oForm.oCellCtnPercentual.id                      = sInstanceName + "ctnPercentual";
          me.oForm.oRowObservacoes                              = document.createElement("tr");
            me.oForm.oCellLabelObservacoes                      = document.createElement("td");
            me.oForm.oCellLabelObservacoes.innerHTML            = me.oLabel.observacoes;
            me.oForm.oCellCtnObservacoes                        = document.createElement("td");
            me.oForm.oCellCtnObservacoes.id                     = sInstanceName + "ctnObservacoes";
            me.oForm.oTextAreaObservacoes                       = document.createElement("textarea");


      /**
       * Renderizando HTML
       */
      me.oElemento                        . appendChild(me.oForm.oFieldSetInformacoes);
        me.oForm.oFieldSetInformacoes     . appendChild(me.oForm.oLegendInformacoes);
        me.oForm.oFieldSetInformacoes     . appendChild(me.oForm.oTableInformacoes);
          me.oForm.oTableInformacoes      . appendChild(me.oForm.oRowDataVencimento);
            me.oForm.oRowDataVencimento   . appendChild(me.oForm.oCellLabelDataVencimento);
            me.oForm.oRowDataVencimento   . appendChild(me.oForm.oCellCtnDataVencimento);
          me.oForm.oTableInformacoes      . appendChild(me.oForm.oRowDataLancamento);
            me.oForm.oRowDataLancamento   . appendChild(me.oForm.oCellLabelDataLancamento);
            me.oForm.oRowDataLancamento   . appendChild(me.oForm.oCellCtnDataLancamento);
          me.oForm.oTableInformacoes      . appendChild(me.oForm.oRowPercentual);
            me.oForm.oRowPercentual       . appendChild(me.oForm.oCellLabelPercentual);
            me.oForm.oRowPercentual       . appendChild(me.oForm.oCellCtnPercentual);
          me.oForm.oTableInformacoes      . appendChild(me.oForm.oRowObservacoes);
            me.oForm.oRowObservacoes      . appendChild(me.oForm.oCellLabelObservacoes);
            me.oForm.oRowObservacoes      . appendChild(me.oForm.oCellCtnObservacoes);
              me.oForm.oCellCtnObservacoes. appendChild(me.oForm.oTextAreaObservacoes);
      /**
       * Adicionando Componentes
       */

      me.oForm.inputDataVencimento= new DBTextFieldData('inputDataVencimento', sInstanceName + ".oForm.inputDataVencimento", '',  10);
      me.oForm.inputDataVencimento. show($(sInstanceName + "ctnDataVencimento"));

      me.oForm.inputDataLancamento= new DBTextFieldData('inputDataLancamento', sInstanceName +".oForm.inputDataLancamento" , sDataFormatada,  10);
      me.oForm.inputDataLancamento. show($(sInstanceName + "ctnDataLancamento"));

      me.oForm.inputPercentual    = new DBTextField('inputPercentual'        , sInstanceName +".oForm.inputPercentual"     , '',  4);
      me.oForm.inputPercentual    . show($(sInstanceName + "ctnPercentual"));

      if (sChavePesquisa !== null) {
        me.oForm.inputDataVencimento .setReadOnly(true);
        me.oForm.inputDataLancamento .setReadOnly(true);
        me.oForm.inputPercentual     .setReadOnly(true);
        me.oForm.oTextAreaObservacoes.readOnly  = true;
        me.oForm.oTextAreaObservacoes.style.backgroundColor = "rgb(222, 184, 135)";
      }
    }
  };

  /**
   * monta os elementos na tela
   */
  this.show              = function(oElemento) {

    me.oElemento    = oElemento;

    js_divCarregando("Carregando Dados","msg");
    me.renderHTML();
    js_removeObj("msg");
    if (sChavePesquisa !== null) {
      me.oDatagridDebitos.renderGrid();
    }
    me.renderHTMLInfo();
  };

  /**
   * Define o Tipo de Pesquisa para a origem do débito
   *  C - CGM
   *  M - Matricula
   *  I - Inscrição
   */
  this.setTipoPesquisa   = function(sTipo) {

    switch(sTipo) {
      case 'C':
      case 'M':
      case 'I':
        sTipoPesquisa = sTipo;
      break;
      default :
        return false;
        throw new Exception("Tipo de Pesquisa nao definido ou invalido : " + sTipo);
      break;
    };
  };

  /**
   * Define Chave de pesquisa;
   */
  this.setChavePesquisa  = function(sChave) {
    sChavePesquisa = sChave;
  };

  /**
   * Retorna os dados selecionados
   */
   this.getDados         = function() {

     var oRetorno = new Object();
     oRetorno.sTipoPesquisa     = sTipoPesquisa;
     oRetorno.sChavePesquisa    = sChavePesquisa;
     oRetorno.iCadTipoDebito    = me.oForm.cboTipoDebitos.getValue();
     if (me.oForm.cboAnoDebitos.getValue() != 0) {
       oRetorno.aExercicio        = new Array(me.oForm.cboAnoDebitos.getValue());
     } else {
       oRetorno.aExercicio        = aExercicios;
     }
     if (sChavePesquisa !== null) {
       var aDadosGrid = new Array();
       var aDados = me.oGridDebitos.getSelection();
       aDados.each(function(aCampo, iIndice) {
         aDadosGrid[iIndice] = aCampo[2];
       });
       oRetorno.aNumpres  = aDadosGrid;
     }
     oRetorno.dtVencimento = me.oForm.inputDataVencimento.getValue();
     oRetorno.dtLancamento = me.oForm.inputDataLancamento.getValue();
     oRetorno.nPercentual  = me.oForm.inputPercentual    .getValue();
     oRetorno.sObservacoes = me.oForm.oTextAreaObservacoes.value;
     return oRetorno;
   };

   this.setDataUsu       = function(dtSessao){
     var aDataSessao = dtSessao.split("-");
     dtDataUsu       = dtSessao;
     iAnoUsu         = aDataSessao[0];
     sDataFormatada  = aDataSessao[2] + "/" +aDataSessao[1]+ "/" + aDataSessao[0];
   };
};
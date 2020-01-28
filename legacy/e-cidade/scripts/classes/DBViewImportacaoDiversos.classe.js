/**
 * Classe para Importação de Débitos para Diversos
 *
 * @package Diversos
 * @author Rafael Nery <rafael.nery@dbseller.com.br>
 * @author André Hertzog <andre.hertzog@dbseller.com.br>
 *
 */
var DBViewImportacaoDiversos = function(sInstancia, sNomeComponente) {

  /**
   * Instancia do Objeto
   */
  var oInstancia = this;
  /**
   * Tipo de Pesquisa dos dados
   */
  var iTipoPesquisa = null;

  /**
   * Chave de Pesquisa dos Dados
   */
  var aChavePesquisa = [];

  /**
   * Array de procedencias
   */
  var aProcedenciasDiversos  = [];
  var aDebitosSelecao        = [];
  var oElementoDestino       = null;
  var lbuscaDebitos          = true;
  this.importacaoParcial     = false;
  this.importaDividaAtiva = false;
  var procedenciasDividaAtiva = [];

  this.sNomeComponente       = sNomeComponente;
  this.sNomeInstancia        = sInstancia;
  this.origemDebito          = null;
  this.destinoDebito         = null;

  this.lProcedenciaTipoDebito = false;
  this.lUnificarDebitos = false;
  this.iTipoDataVencimento = null;

  this.setProcedenciaTipoDebito = function (lProcedenciaTipoDebito) {
    this.lProcedenciaTipoDebito = lProcedenciaTipoDebito;
  };


  var fcCallBack = function() {
    return true;
  };

  this.setCallBackFunction = function ( fcFunction ) {
    fcCallBack = fcFunction;
  };

  this.setOrigemDebito = function(origemDebito) {
    this.origemDebito = origemDebito;
  };

  this.setDestinoDebito = function(destinoDebito) {
    this.destinoDebito = destinoDebito;
  };

  this.setImportacaoParcial = function(importacaoParcial) {
    this.importacaoParcial = importacaoParcial;
  };

  this.setImportaDividaAtiva = function(importaDividaAtiva) {
    this.importaDividaAtiva = importaDividaAtiva;
  };

  /**
   * Seta o tipo de pesquisa
   * @param iTipo
   * 1 - CODIGO IMPORTACAO
   * 2 - MATRICULA
   * 3 - CGM
   * 4 - Debitos (vindos da CGF)
   * 5 - Inscrição
   */
  this.setTipoPesquisa = function( iTipo ) {
    iTipoPesquisa = iTipo;
  };

  /**
   * Seta as chaves de pesquisa
   * @param aChave
   */
  this.setChavePesquisa = function( aChave ) {

    if (typeof(aChave) !== "object"){
      aChave = new Array(aChave);
    }
    aChavePesquisa = aChave;
  };

  /**
   * @param lUnificarDebitos
   */
  this.setUnificarDebitos = function(lUnificarDebitos) {
    this.lUnificarDebitos = lUnificarDebitos === '2';
  };

  /**
   * Seta qual data de vencimento deve ser considerada
   * 1 - Menor vencimento das parcelas abertas
   * 2 - Maior vencimento das parcelas abertas
   * @param iTipoDataVencimento
   */
  this.setTipoDataVencimento = function(iTipoDataVencimento) {
    this.iTipoDataVencimento = iTipoDataVencimento;
  };

  this.adicionarProcedencias = function( iCodigoProcedencia, iDescricao, iDescricao2, iTipo ) {

    var oProcedencia = {};
    oProcedencia.dv09_procdiver = iCodigoProcedencia;
    oProcedencia.dv09_descra    = iDescricao;
    oProcedencia.dv09_descr     = iDescricao2;
    oProcedencia.dv09_tipo      = iTipo;
    aProcedenciasDiversos.push(oProcedencia);

    lbuscaDebitos = false;
    return true;
  };


  this.adicionarDebitos      = function( iNumpre, iNumpar, sTipoDebito, iCodReceita, sDescrReceita, fValor ) {

    var oDebito = {};
    oDebito.k00_numpre  = iNumpre;
    oDebito.k00_numpar  = iNumpar;
    oDebito.k00_descr   = sTipoDebito;
    oDebito.k02_codigo  = iCodReceita;
    oDebito.k02_descr   = sDescrReceita;
    oDebito.k00_valor   = fValor;
    aDebitosSelecao.push(oDebito);

    lbuscaDebitos = false;
    return true;
  };


  /**
   * Caminho do arquivo RPC do componente
   */
  var sUrl                   = 'dvr3_importacaoiptu.RPC.php';

  /**
   * Renderiza o HTML da janela base
   */
  this.renderizarHTMLBase = function() {

    oInstancia.oForm = {};

    /**
     * Criando Elementos
     */
    oInstancia.oForm.oFieldSetGrid = document.createElement("fieldset");
    oInstancia.oForm.oFieldSetGrid.setStyle({'margin' : '5px'});

    oInstancia.oForm.oLegendGrid           = document.createElement("legend");
    oInstancia.oForm.oLegendGrid.innerHTML = "<STRONG>Débitos Encontrados</STRONG>";

    oInstancia.oForm.oContainerGrid    = document.createElement("div");
    oInstancia.oForm.oContainerGrid.id = 'gridResultados';
    oInstancia.oForm.oContainerGrid.setStyle({'width' : "762px"});
    oInstancia.oForm.oContainerGrid.setStyle({'height' : "200px"});

    oInstancia.oForm.oFieldSetObservacoes = document.createElement("fieldset");
    oInstancia.oForm.oFieldSetObservacoes.setStyle({'margin' : '5px'});

    oInstancia.oForm.oLegendObservacoes           = document.createElement("legend");
    oInstancia.oForm.oLegendObservacoes.innerHTML = "<STRONG>Observações</STRONG>";

    oInstancia.oForm.oDivObservacoes = document.createElement("div");

    oInstancia.oForm.oTextAreaObservacoes    = document.createElement("textarea");
    oInstancia.oForm.oTextAreaObservacoes.id = "observacao";
    oInstancia.oForm.oTextAreaObservacoes.setStyle({'width' : "100%"});
    oInstancia.oForm.oTextAreaObservacoes.setStyle({'height' : "150px"});
    oInstancia.oForm.oTextAreaObservacoes.setStyle({'textTransform' : "uppercase"});
    oInstancia.oForm.oTextAreaObservacoes.maxlength           = "600";
    oInstancia.oForm.oTextAreaObservacoes.setAttribute("onKeyUp","js_maxlenghttextarea(this,event,600)");

    /**
     * Adicionando Componentes ao Elemento de Destino
     */
    oInstancia.oForm.oFieldSetGrid.appendChild(oInstancia.oForm.oLegendGrid);
    oInstancia.oForm.oFieldSetGrid.appendChild(oInstancia.oForm.oContainerGrid);
    oInstancia.oForm.oFieldSetObservacoes.appendChild(oInstancia.oForm.oLegendObservacoes);
    oInstancia.oForm.oFieldSetObservacoes.appendChild(oInstancia.oForm.oDivObservacoes);
    oInstancia.oForm.oDivObservacoes.appendChild(oInstancia.oForm.oTextAreaObservacoes);
    oElementoDestino.appendChild(oInstancia.oForm.oFieldSetGrid);
    oElementoDestino.appendChild(oInstancia.oForm.oFieldSetObservacoes);

    var sContent = '<span id="observacaoerrobar" style="float:left;color:red;font-weight:bold"></span>';
    sContent    += '<b> Caracteres Digitados : </b>';
    sContent    += '<input id="observacaoobsdig" type="text" disabled="" value="0" size="3" name="observacaoobsdig" style="background-color:#FFF;color:#000;"> ';
    sContent    += '<b> - Limite 600 </b>';

    oInstancia.oForm.oDivObservacoes.innerHTML = oInstancia.oForm.oDivObservacoes.innerHTML + sContent;
  };

  /**
   * Renderiza Grid de debitos
   */
  this.renderizarGridDebitos = function() {

    oInstancia.gridDebitos              = new DBGrid(oInstancia.sNomeComponente + "_dataGridDebitos");
    oInstancia.gridDebitos.nameInstance = oInstancia.sNomeInstancia + ".gridDebitos";

    oInstancia.gridDebitos.setHeight( 150 );
    oInstancia.gridDebitos.setCheckbox( 0 );
    oInstancia.gridDebitos.setCellAlign(["center", "center", "left", "center", "left", "right"]);
    oInstancia.gridDebitos.setCellWidth(["15%", "8%", "25%", "13%", "25%","14%"]);
    oInstancia.gridDebitos.setHeader(['Numpre', 'Numpar', 'Tipo de Débito', 'Código Receita', 'Receita', 'Valor (R$)']);
    oInstancia.gridDebitos.show( oInstancia.oForm.oContainerGrid );
    oInstancia.gridDebitos.setCallbackSingle(
      function (checkBoxOrigem) {

        var linhasParaMarcar = [];
        oInstancia.gridDebitos.aRows.each(
          function (row, indice) {

            if (Number(checkBoxOrigem.value) === Number(row.aCells[1].getValue())) {
              linhasParaMarcar.push(indice);
            }
          }
        );

        linhasParaMarcar.each(
          function (codigoLinha) {

            $(oInstancia.gridDebitos.aRows[codigoLinha].aCells[0].sId).childNodes[0].checked = checkBoxOrigem.checked;
            oInstancia.gridDebitos.aRows[codigoLinha].isSelected = checkBoxOrigem.checked;
            $(oInstancia.gridDebitos.aRows[codigoLinha].sId).className = checkBoxOrigem.checked ? 'marcado' : 'normal';
          }
        );
      }
    );
    oInstancia.gridDebitos.clearAll(true);
  };

  /**
   * Renderiza Grid de procedencias
   */
  this.renderizarGridProcedencias = function() {

    /**
     * Renderiza dataGrid
     */
    oInstancia.gridProcendencias              = new DBGrid(oInstancia.sNomeComponente + "_dataGridProcendencias");
    oInstancia.gridProcendencias.nameInstance = oInstancia.sNomeInstancia + ".gridProcendencias";

    oInstancia.gridProcendencias.setHeight( 110 );
    oInstancia.gridProcendencias.setCellAlign(["left", "left", "left"]);
    oInstancia.gridProcendencias.setCellWidth(["20%", "40%", "40%"]);
    oInstancia.gridProcendencias.setHeader(['Código Receita', 'Receita', 'Procedência']);
    oInstancia.gridProcendencias.show( document.getElementById(oInstancia.sNomeComponente + "_procedenciasConteudo") );
    oInstancia.gridProcendencias.clearAll(true);
  };

  /**
   * Cria janela base
   */
  this.criarJanela = function() {

    if ( $(oInstancia.sNomeComponente + '_janelaImportacao') ) {
      oInstancia.oJanelaBase.destroy();
    }

    var sConteudo  = " <div id='" + oInstancia.sNomeComponente + "_importacaoCabecalho'></div>";
    sConteudo     += " <div id='" + oInstancia.sNomeComponente + "_importacaoConteudo'></div>";
    sConteudo     += " <div id='" + oInstancia.sNomeComponente + "_importacaoRodape'>";
    sConteudo     += "   <center>";
    sConteudo     += "     <input type='button' id='btnProcedencias' value='Enviar' onClick='" + oInstancia.sNomeInstancia + ".criarJanelaProcedencias();' />";
    sConteudo     += "     <input type='button' value='Fechar' onClick='" + oInstancia.sNomeInstancia + ".oJanelaBase.destroy();'     style=\"margin-left: 2px;\" />";
    sConteudo     += "   </center>";
    sConteudo     += " </div>";

    oInstancia.oJanelaBase = new windowAux( oInstancia.sNomeComponente + '_janelaImportacao', 'Importação de Débitos', 800, 570 );
    oInstancia.oJanelaBase.setContent( sConteudo );
    oInstancia.oJanelaBase.setShutDownFunction(function(){
      oInstancia.oJanelaBase.destroy();
    });
    oInstancia.oJanelaBase.show();

    var sMsg                 = "Selecione abaixo os débitos a serem processados.\n";
    oInstancia.oMessageBase  = new DBMessageBoard(
      oInstancia.sNomeComponente + 'msgBase',
      'Seleção dos débitos',
      sMsg,
      $(oInstancia.sNomeComponente + "_importacaoCabecalho")
    );
    oInstancia.oMessageBase.show();
    return $( oInstancia.sNomeComponente + '_importacaoConteudo' );
  };

  /**
   * Cria janela de selecao de procedencias
   */
  this.criarJanelaProcedencias = function() {

    if ( $(oInstancia.sNomeComponente + '_janelaProcedencia') ) {
      oInstancia.oJanelaProcedencias.destroy();
    }

    var sConteudo  = " <div id='" + oInstancia.sNomeComponente + "_procedenciasCabecalho'></div>";
    sConteudo     += "   <fieldset>";
    sConteudo     += "     <legend><strong>Procedências do Débito</strong></legend>";
    sConteudo     += "     <div id='" + oInstancia.sNomeComponente + "_procedenciasConteudo'>";
    sConteudo     += "     </div>";
    sConteudo     += "   </fieldset>";
    sConteudo     += " <div id='" + oInstancia.sNomeComponente + "_procedenciasRodape'>";
    sConteudo     += "   <center>";
    sConteudo     += "     <input type='button' value='Processar' onClick='" + oInstancia.sNomeInstancia + ".processarDebitosProcedencias();' />";
    sConteudo     += "     <input type='button' value='Fechar'    onClick='" + oInstancia.sNomeInstancia + ".oJanelaProcedencias.destroy();' style=\"margin-left: 2px;\"/>";
    sConteudo     += "   </center>";
    sConteudo     += " </div>";

    oInstancia.oJanelaProcedencias = new windowAux(oInstancia.sNomeComponente + '_janelaProcedencia', 'Seleção de Procedências', 550, 300);
    oInstancia.oJanelaProcedencias.setContent(sConteudo);
    oInstancia.oJanelaProcedencias.setShutDownFunction(function(){
      oInstancia.oJanelaProcedencias.destroy();
    });
    oInstancia.oJanelaProcedencias.setChildOf(oInstancia.oJanelaBase);
    oInstancia.oJanelaProcedencias.show(null, null, true);
    oInstancia.oJanelaProcedencias.toFront();
    oInstancia.oJanelaProcedencias.allowDrag(false);

    var sMsg                 = _M("tributario.diversos.DBViewImportacaoDiversos.debitos_receitas_processadas");
    oInstancia.oMessageBase  = new DBMessageBoard(
      oInstancia.sNomeComponente + 'msgBase',
      'Seleção dos débitos',
      sMsg,
      $(oInstancia.sNomeComponente + "_procedenciasCabecalho")
    );
    oInstancia.oMessageBase.show();

    oInstancia.renderizarGridProcedencias();
    oInstancia.carregarGridProcedencias();
  };

  /**
   * Carrega dados para Grid de debitos
   */
  this.carregarGridDebitos = function () {

    if ( lbuscaDebitos ) {
      js_divCarregando('Pesquisando Débitos.', 'msgAjax');

      var oParam             = {};
      var oAjax              = {};

      oParam.sExec           = "getDebitos";

      oParam.iTipoPesquisa  = iTipoPesquisa;
      oParam.aChavePesquisa = aChavePesquisa;
      oParam.origemDebito   = this.origemDebito;
      oParam.destinoDebito  = this.destinoDebito;
      oParam.importaDividaAtiva = this.importaDividaAtiva;

      oAjax.method           = 'POST';
      oAjax.parameters       = 'json=' + Object.toJSON(oParam);
      oAjax.onComplete       =  oInstancia.retornoDebitos;
      oAjax.asynchronous     =  false;

      oInstancia.gridDebitos.clearAll(true);
      new Ajax.Request(sUrl, oAjax);
      return;
    }
    oInstancia.retornoDebitos();
  };

  /**
   * Faz a carga dos dados na grid de debitos
   */
  this.retornoDebitos = function (oAjax) {

    if (oAjax !== undefined && lbuscaDebitos) {

      js_removeObj('msgAjax');

      var oRetorno  = eval("("+oAjax.responseText+")");

      if (oRetorno.status === 2) {

        oInstancia.oJanelaBase.destroy();
        alert(oRetorno.message.urlDecode().replace(/\\n/g, '\n'));
        return;
      }

      aProcedenciasDiversos = oRetorno.aProcdiver;
      aDebitosSelecao = oRetorno.aDebitos;
      procedenciasDividaAtiva = oRetorno.procedenciaDividaAtiva;
    }

    var iProcedenciaTipoDebito = 0;

    for (var p = 0; p < aProcedenciasDiversos.length; p++) {

      if (!oInstancia.lProcedenciaTipoDebito || (oInstancia.lProcedenciaTipoDebito && aProcedenciasDiversos[p].dv09_tipo == oInstancia.destinoDebito)) {
        iProcedenciaTipoDebito++;
      }
    }

    if (iProcedenciaTipoDebito == 0 && this.importaDividaAtiva === false) {

      alert(_M("tributario.diversos.DBViewImportacaoDiversos.sem_procedencia"));
      oInstancia.oJanelaBase.destroy();
      return;
    }

    if (oRetorno.procedenciaDividaAtiva.length === 0 && this.importaDividaAtiva === true) {

      alert(_M("tributario.diversos.DBViewImportacaoDiversos.sem_procedencia"));
      oInstancia.oJanelaBase.destroy();
      return;
    }

    for (var iIndiceGrid = 0; iIndiceGrid < aDebitosSelecao.length; iIndiceGrid++) {

      var oDebito = aDebitosSelecao[iIndiceGrid];
      var aCelulas = [];

      aCelulas[0] = oDebito.k00_numpre;
      aCelulas[1] = oDebito.k00_numpar;
      aCelulas[2] = oDebito.k00_descr.urlDecode();
      aCelulas[3] = oDebito.k02_codigo;
      aCelulas[4] = oDebito.k02_descr.urlDecode();
      aCelulas[5] = js_formatar(oDebito.k00_valor, 'f');

      oInstancia.gridDebitos.addRow(aCelulas);
    }

    oInstancia.gridDebitos.renderRows();

    return true;
  };

  /**
   * Carrega dados e faz a carga no Grid de procedencias
   */
  this.carregarGridProcedencias = function () {

    var aDebitos = oInstancia.gridDebitos.getSelection();

    if (aDebitos.length === 0) {

      oInstancia.oJanelaProcedencias.destroy();
      alert(_M("tributario.diversos.DBViewImportacaoDiversos.nenhum_debito"));
      return false;
    }

    if (oInstancia.importaDividaAtiva) {

      var oParametros = {
        'sExec' : "getDebitosOrigem",
        'aDebitos' : aDebitos
      };

      var oRequisicao = new AjaxRequest(sUrl, oParametros, function (oRetorno, lErro) {

        if (lErro) {
          alert(oRetorno.message);
          return false;
        }

        this.renderizarDadosGridProcedencia(oRetorno.aDebitos);
      }.bind(oInstancia));
      oRequisicao.execute();

    } else {
      this.renderizarDadosGridProcedencia(aDebitos);
    }
  };

  this.renderizarDadosGridProcedencia = function (aDebitos) {

    var aReceitas = [];

    for (var iIndiceGridPro = 0; iIndiceGridPro < aDebitos.length; iIndiceGridPro++) {

      var aDebito = [];
      var aCelulas = [];
      aDebito = aDebitos[iIndiceGridPro];

      if (aReceitas.indexOf(aDebito[4]) === -1) {
        aReceitas.push(aDebito[4]);
      } else {
        continue;
      }

      aCelulas[0] = aDebito[4];
      aCelulas[1] = aDebito[5];

      if (this.importaDividaAtiva === false) {

        aCelulas[2] = '<select id="procdiver_' + aDebito[4] + '" name="procdiver_' + aDebito[4] + '" style="width: 190px; font-size: 12px; margin: 3px;">';
        aCelulas[2] += '<option value="">Selecione...</option>';
        for (var p = 0; p < aProcedenciasDiversos.length; p++) {

          var oProcedenciaDiversos = {};
          oProcedenciaDiversos = aProcedenciasDiversos[p];
          if (!this.lProcedenciaTipoDebito || (this.lProcedenciaTipoDebito && oProcedenciaDiversos.dv09_tipo == oInstancia.destinoDebito)) {

            var sDescricao  = oProcedenciaDiversos.dv09_procdiver + ' - ' + oProcedenciaDiversos.dv09_descra.urlDecode();
                sDescricao += ' - ' + oProcedenciaDiversos.receita.urlDecode();
            aCelulas[2] += '<option title="' + oProcedenciaDiversos.dv09_descr.urlDecode() + '" value="' + oProcedenciaDiversos.dv09_procdiver + '">' + sDescricao + '</option>';
          }
        }

        aCelulas[2] += '</select>';
      }

      if (this.importaDividaAtiva === true) {

        var comboboxProcedencia = '<select id="procdiver_' + aDebito[4] + '" name="procdiver_' + aDebito[4] + '" style="width: 190px; font-size: 12px; margin: 3px;">';
        comboboxProcedencia    += '<option value="">Selecione...</option>';

        procedenciasDividaAtiva.each(
          function (procedencia) {

            var sDescricao  = procedencia.codigo + ' - ' + procedencia.descricao.urlDecode();
                sDescricao += ' - ' + procedencia.receita.urlDecode();
            var option = "<option value='"+procedencia.codigo+"'>" + sDescricao + "</option>";
            comboboxProcedencia += option;
          }
        );
        comboboxProcedencia += "</select>";
        aCelulas[2] = comboboxProcedencia;
      }

      oInstancia.gridProcendencias.addRow(aCelulas, true, false, false);
    }

    oInstancia.gridProcendencias.renderRows();
  };

  /**
   * Cria HTML no Local especificado
   */
  this.show = function(oContainer) {

    oElementoDestino = oContainer;

    if (oContainer === undefined) {
      oElementoDestino = oInstancia.criarJanela();
    }

    oInstancia.renderizarHTMLBase();

    oInstancia.renderizarGridDebitos();
    oInstancia.carregarGridDebitos();

  };

  /**
   * PROCESSAR DÉBITOS
   */
  this.processarDebitosProcedencias = function() {

    js_divCarregando('Processando Débitos, aguarde.', 'msgBox');

    var oDadosRetorno            = {};
    oDadosRetorno.iTipoPesquisa  = iTipoPesquisa;
    oDadosRetorno.aChavePesquisa = aChavePesquisa;
    oDadosRetorno.sObservacoes   = encodeURIComponent(tagString($F("observacao")));
    oDadosRetorno.aDebitos       = [];

    var aProcedencias = {};
    var aListaDebitos = oInstancia.gridDebitos.getSelection();
    var lErro         = false;
    oInstancia.gridProcendencias.aRows.each(

      function(oRow) {

        if (oRow.aCells[2].getValue() === "") {
          lErro = true;
        }

        var oProcedencia                        = {};
        oProcedencia.iCodReceita                = oRow.aCells[0].getValue();
        oProcedencia.iProcedencia               = oRow.aCells[2].getValue();
        aProcedencias[oProcedencia.iCodReceita] = oProcedencia;
      }
    );

    if (lErro) {
      alert(_M("tributario.diversos.DBViewImportacaoDiversos.nenhuma_procedencia"));
    }

    aListaDebitos.each(

      function (aRow) {

        var oDebito                = {};
        oDebito.iNumpre            = aRow[1];
        oDebito.iNumpar            = aRow[2];
        oDebito.iReceita           = aRow[4];

        if (!oInstancia.importaDividaAtiva) {
          oDebito.iCodigoProcedencia = aProcedencias[oDebito.iReceita].iProcedencia;
        }

        oDadosRetorno.aDebitos.push(oDebito);
      }
    );

    if (oInstancia.importaDividaAtiva) {
      oDadosRetorno.aProcedencias = aProcedencias;
    }

    /**
     * Requisição AJAX
     */
    var oParam = {};
    oParam.sExec = "processarDebitos";
    oParam.oDadosProcessamento = oDadosRetorno;
    oParam.importaDividaAtiva = this.importaDividaAtiva;

    if (this.importacaoParcial === true && oParam.importaDividaAtiva === false) {
      oParam.sExec = "importacaoParcial";
    }

    if (this.importacaoParcial === true && oParam.importaDividaAtiva === true) {

      oParam.sExec = "importacaoParcialDividaAtiva";

      oParam.processoSistema = $F('processosistema') === 't';
      oParam.codigoProcesso = $F('codigo_processo');
      oParam.titularProcesso = $F('requerente');
      oParam.dataProcesso = '';
      if (oParam.processoSistema === false) {

        oParam.codigoProcesso = $F('codigo_processo_fora_sistema');
        oParam.titularProcesso = $F('titular_processo_fora_sistema');
        oParam.dataProcesso = $F('data_processo_fora_sistema');
      }
    }

    oParam.lUnificarDebitos = this.lUnificarDebitos;
    oParam.iTipoDataVencimento = this.iTipoDataVencimento;

    var oRequisicao          = {};
    oRequisicao.method       = "post";
    oRequisicao.parameters   = "json="+Object.toJSON(oParam);
    oRequisicao.asynchronous = false;
    oRequisicao.onComplete   = function(oAjax) {

      js_removeObj('msgBox');
      var oRetorno = eval("("+oAjax.responseText+")");

      alert(oRetorno.message.urlDecode());

      if (oRetorno.status === "2") {
        lErro  = true;
      }

      if (!lErro) {

        if (oInstancia.importacaoParcial === true) {

          oInstancia.oJanelaBase.destroy();
          oInstancia.oJanelaProcedencias.destroy();
        }

        return fcCallBack();
      }
    };

    new Ajax.Request(sUrl, oRequisicao);
  };

  return this;
};
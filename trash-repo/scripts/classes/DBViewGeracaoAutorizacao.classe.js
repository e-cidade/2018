/**
 * DBViewGeracaoAutorizacao
 * Cria uma view javascript para criar a gera��o de empenho
 * @author Matheus Felini
 * @param sInstancia - Nome da instancia da View
 * @param oNode - n� onde o objeto ser� apresentado.
 *
 * Exemplo de Utiliza��o:
 * var oDBViewGeracao = new DBViewGeracaoAutorizacao('objetoTeste',$('body'));
 * oDBViewGeracao.show();
 *
 */

DBViewGeracaoAutorizacao = function (sInstancia, oNode, iTipoOrigemDados) {

  var me                 = this;
  me.iLicitacao          = '';
  me.iInstit             = '';
  me.sRPC                = "lic4_geraAutorizacoes.RPC.php";
  me.oDestino            = '';
  me.oTipoCompra         = '';
  me.oTipoEmpenho        = '';
  me.oTipoLicitacao      = '';
  me.oPrazoEntrega       = '';
  me.oCondicaoPagamento  = '';
  me.oContato            = '';
  me.oNumeroLicitacao    = '';
  me.oOutrasCondicoes    = '';
  me.oTelefone           = '';
  me.oObservacao         = '';
  me.sInstancia          = sInstancia;
  me.oNode               = null;
  me.oWindowAux          = '';
  me.oWindowAuxItens     = '';
  me.oWindowAuxAuth      = '';
  me.oDataGridItens      = '';
  me.oDataGridAuth       = '';
  me.oToolTipErro        = '';
  me.oCaractPeculiarCod  = '';
  me.oCaractPeculiarDesc = '';
  me.sArquivoOrigem      = '';
  me.iOrigemDados        = '';
  me.sTipoOrigem         = '';
  me.iAno                = 0;
  me.iTipoOrigemDados    = iTipoOrigemDados;

  if (oNode != undefined) {
    me.oNode = oNode;
  }

  /*
   * Verifica o tipo de origem dos dados para executar o RPC correto.
   * undefined = Licitacao (default)
   *         1 = Processo de Compra
   *         2 = Solicitacao
   */
  switch (me.iTipoOrigemDados) {

    /**
     *  Processo de Compras
     */
    case '1':

      me.sRPC           = 'com4_geraAutorizacoes.RPC.php';
      me.sArquivoOrigem = 'com1_geraautorizacao001.php';
      me.sTipoOrigem    = "Processo de Compras";
    break;

    /**
     * Solicita��o de Compras
     */
    case '2':
      me.sRPC = 'sol4_geraAutorizacoes.RPC.php';
      me.sArquivoOrigem = 'com1_geraautorizacao001.php';
      me.sTipoOrigem    = "Solicita��o de Compras";
    break;

    /**
     * Licita��o
     */
    default:
      me.sRPC           = 'lic4_geraAutorizacoes.RPC.php';
      me.sArquivoOrigem = 'lic4_geraaut001.php';
      me.sTipoOrigem    = "Licita��o";
  }

  /**
   *  Cria os inputs padr�es do formul�rio
   */
  me.oTxtDestino        = new DBTextField('oTxtDestino'        , me.sInstancia + '.oTxtDestino'     , '', 50);
  me.oPrazoEntrega      = new DBTextField('oCboPrazoEntrega'   , me.sInstancia + '.oPrazoEntrega'   , '', 50);
  me.oCondicaoPagamento = new DBTextField('oTxtCondPagamento'  , me.sInstancia + '.oCondPagamento'  , '', 50);
  me.oTelefone          = new DBTextField('oTxtTelefone'       , me.sInstancia + '.oTelefone'       , '', 50);
  me.oContato           = new DBTextField('oTxtContato'        , me.sInstancia + '.oContato'        , '', 50);
  me.oNumeroLicitacao   = new DBTextField('oTxtNumeroLicitacao', me.sInstancia + '.oNumeroLicitacao', '', 50);
  me.oOutrasCondicoes   = new DBTextField('oTxtOutrasCondicoes', me.sInstancia + '.oOutrasCondicoes', '', 50);

  me.oTipoCompra        = new DBComboBox('oTxtTipoCompra'   , me.sInstancia + '.oTipoCompra'   , new Array(), 370);
  me.oTipoEmpenho       = new DBComboBox('oCboTipoEmpenho'  , me.sInstancia + '.oTipoEmpenho'  , new Array(), 370);
  me.oTipoLicitacao     = new DBComboBox('oCboTipoLicitacao', me.sInstancia + '.oTipoLicitacao', new Array(), 370);

  me.oCaractPeculiarCod  = new DBTextField('oTxtCaractPeculiarCod' , me.sInstancia + '.oCaractPeculiarCod' , '', 5);
  me.oCaractPeculiarDesc = new DBTextField('oTxtCaractPeculiarDesc', me.sInstancia + '.oCaractPeculiarDesc', '', 40);

  me.oTipoCompra.onChange     = "; " + me.sInstancia + ".buscarTipoLicitacao(this.value);";
  me.oNumeroLicitacao.onKeyUp = '; js_ValidaCampos(this, 1, "N�mero da Licita��o", "t", "f", event);';

  me.oTxtDestino.iMaxLength        = 30;
  me.oPrazoEntrega.iMaxLength      = 30;
  me.oCondicaoPagamento.iMaxLength = 30;
  me.oTelefone.setMaxLength(12);

  me.oCaractPeculiarDesc.setReadOnly(true);

  this.show = function() {

    var sContentForm  = "<div id='divAutEmpenho'>";
        sContentForm += "<center>";
        sContentForm += "  <form id='form_"+me.sInstancia+"' name='form_"+me.sInstancia+"'>";
        sContentForm += "    <fieldset style='width:500px; padding: 20px;'>";
        sContentForm += "      <legend><b>Autoriza��o de "+me.sTipoOrigem+" "+me.getOrigem()+"</b></legend>";
        sContentForm += "        <table id='table_"+me.sInstancia+"' width='100%' border='0'>";
        sContentForm += "          <tr>";
        sContentForm += "            <td nowrap='nowrap'><b>Destino:</b></td>";
        sContentForm += "            <td id='td_destino_"+me.sInstancia+"'></td>";
        sContentForm += "          </tr>";
        sContentForm += "          <tr>";
        sContentForm += "            <td nowrap='nowrap'><b>Tipo de Compra:</b></td>";
        sContentForm += "            <td id='td_tipocompra_"+me.sInstancia+"'>"+me.oTipoCompra.toInnerHtml()+"</td>";
        sContentForm += "          </tr>";
        sContentForm += "          <tr>";
        sContentForm += "            <td nowrap='nowrap'><b>Tipo de Empenho:</b></td>";
        sContentForm += "            <td id='td_tipoempenho_"+me.sInstancia+"'>"+me.oTipoEmpenho.toInnerHtml()+"</td>";
        sContentForm += "          </tr>";
        sContentForm += "          <tr>";
        sContentForm += "            <td nowrap='nowrap'><b>Tipo de Licita��o:</b></td>";
        sContentForm += "            <td id='td_tipolicitacao_" + me.sInstancia + "'>" + me.oTipoLicitacao.toInnerHtml() + "</td>";
        sContentForm += "          </tr>";
        sContentForm += "          <tr>";
        sContentForm += "            <td nowrap='nowrap'><b>N�mero da Licita��o:</b></td>";
        sContentForm += "            <td id='td_numerolicitacao_" + me.sInstancia + "'>" + me.oNumeroLicitacao.toInnerHtml() + "</td>";
        sContentForm += "          </tr>";
        sContentForm += "          <tr>";
        sContentForm += "            <td nowrap='nowrap'><b>Prazo de Entrega:</b></td>";
        sContentForm += "            <td id='td_prazoentrega_"+me.sInstancia+"'>"+me.oPrazoEntrega.toInnerHtml()+"</td>";
        sContentForm += "          </tr>";
        sContentForm += "          <tr>";
        sContentForm += "            <td nowrap='nowrap'><b>Condi��o de Pagamento:</b></td>";
        sContentForm += "            <td id='td_condpagamento_"+me.sInstancia+"'>"+me.oCondicaoPagamento.toInnerHtml()+"</td>";
        sContentForm += "          </tr>";
        sContentForm += "          <tr>";
        sContentForm += "            <td nowrap='nowrap'><b>Telefone:</b></td>";
        sContentForm += "            <td id='td_telefone_" + me.sInstancia + "'>" + me.oTelefone.toInnerHtml() + "</td>";
        sContentForm += "          </tr>";
        sContentForm += "          <tr>";
        sContentForm += "            <td nowrap='nowrap'><b>Contato:</b></td>";
        sContentForm += "            <td id='td_contato_" + me.sInstancia + "'>" + me.oContato.toInnerHtml() + "</td>";
        sContentForm += "          </tr>";
        sContentForm += "          <tr>";
        sContentForm += "            <td nowrap='nowrap'><b>Outras Condi��es:</b></td>";
        sContentForm += "            <td id='td_outrascondicoes_" + me.sInstancia + "'>" + me.oOutrasCondicoes.toInnerHtml() + "</td>";
        sContentForm += "          </tr>";
        sContentForm += "          <tr>";
        sContentForm += "            <td nowrap='nowrap'>";
        sContentForm += "              <b><a href='#' id='ancoraCaractPeculiar'>Caracter�stica Peculiar:</a></b>";
        sContentForm += "            </td>";
        sContentForm += "            <td id='td_condpagamento_"+me.sInstancia+"'>"+me.oCaractPeculiarCod.toInnerHtml()+"";
        sContentForm += "            "+me.oCaractPeculiarDesc.toInnerHtml()+"</td>";
        sContentForm += "          </tr>";
        sContentForm += "          <tr>";
        sContentForm += "            <td colspan='2'>";
        sContentForm += "              <fieldset style='width:500px;'>";
        sContentForm += "                <legend><b>Observa��es</b></legend>";
        sContentForm += "                  <textarea name='oTxtObservacoes' id='oTxtObservacoes' style='width:100%; height:100px;' ></textarea>";
        sContentForm += "            </td>";
        sContentForm += "          </tr>";
        sContentForm += "        </table>";
        sContentForm += "      </fieldset>";
        sContentForm += "    <br><input type='button' name='btnItens' id='btnItens' value='Escolher Itens' />";
        sContentForm += "    &nbsp;<input type='button' name='btnFechar' id='btnFechar' value='Salvar' style='display:none;' />";
        sContentForm += "  </form>";
        sContentForm += "</center>";
        sContentForm += "</div>";
        sContentForm += "<div id='ctnItens'></div>";

    me.oNode.innerHTML = sContentForm;
    me.oTxtDestino.show($('td_destino_'+me.sInstancia));

    /**
     *  Abre a janela para escolher os itens da licita��o
     */
    $('btnItens').observe('click', function() {

      if ($F('oTxtCaractPeculiarCod') == "") {

        alert("Informe a caracter�stica peculiar.");
        return false;
      }
      me.itensAutorizacao();
    });

    /**
     *  Esconde a Window com os dados das autoriza��es
     */
    $('btnFechar').observe('click', function() {
      me.oWindowAux.hide();
    });

    /**
     *  Abre janela para escolha da caracter�stica peculiar
     */
    $('ancoraCaractPeculiar').observe('click', function() {

      var sOpenWindow = "func_concarpeculiar.php?funcao_js=parent.js_preencheCaracteristica|c58_sequencial|c58_descr";
      js_OpenJanelaIframe('','db_iframe_concarpeculiar', sOpenWindow,'Caracter�stica Peculiar',true);
      $('Jandb_iframe_concarpeculiar').style.zIndex = '10000';
    });

    /**
     *  Busca os dados da caracter�stica peculiar e completa o formul�rio
     */
    $('oTxtCaractPeculiarCod').observe('change', function() {

      var sOpenWindow = "func_concarpeculiar.php?pesquisa_chave="+$F('oTxtCaractPeculiarCod')+"&funcao_js=parent.js_completaCaracteristica";
      js_OpenJanelaIframe('','db_iframe_concarpeculiar', sOpenWindow, 'Caracter�stica Peculiar',false);
      $('Jandb_iframe_concarpeculiar').style.zIndex = '10000';
    });

    /**
     * Executa o RPC para buscar os Tipos de Compra e Empenho
     * para montar o select na janela de Dados das Autoriza��es
     */
    me.getTipoCompraEmpenho();
  }

  /**
   * Fun�ao GetTipoCompraEmpenho
   * Busca os dados cadastrados pctipocompra para montar o combo box
   * @return void
   */
  this.getTipoCompraEmpenho = function () {

    var oParamCompraEmpenho          = new Object();
    oParamCompraEmpenho.exec         = "getTipoCompraEmpenho";
    oParamCompraEmpenho.iCodigo      = me.getOrigem();
    oParamCompraEmpenho.iOrigemDados = me.iTipoOrigemDados;

    js_divCarregando("Carregando dados, aguarde...", "msgBox");

    var oAjaxCompraEmpenho = new Ajax.Request("lic4_geraAutorizacoes.RPC.php",
                                               {method:'post',
                                                parameters:'json='+Object.toJSON(oParamCompraEmpenho),
                                                onComplete: me.configTipoCompraEmpenho
                                               }
                                             );
  }

  /**
   * Fun��o Configura Array Tipo Compra Empenho
   *
   * Configura o DBComboBox com os valores retornados pelo AJAX ao iniciar o programa.
   * Ap�s carregar os combobox de tipo de compras e empenho, carregamos o combobox do tipo de licita��o
   * @param object - objeto AJAX
   */
  this.configTipoCompraEmpenho = function(oAjaxRetorno) {

    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjaxRetorno.responseText.urlDecode()+")");

    if (oRetorno.solicitacaoComDotacaoAnoAnterior.length > 0) {

      var sMsgConfirm  = "As solicita��es a seguir est�o com uma ou mais dota��es do ";
      sMsgConfirm     += "ano anterior: "+oRetorno.solicitacaoComDotacaoAnoAnterior.implode(", ")+"\n\n";
      sMsgConfirm     += "Deseja alterar as dota��es?";
      if (!confirm(sMsgConfirm)) {
        alert('os itens com as Dota��es de ano anterior n�o poder�o ser autorizados.');
      } else {

        me.alterarDotacoes(oRetorno.solicitacaoComDotacaoAnoAnterior);
      }

    }

    $('oTxtObservacoes').value = oRetorno.sResumo.urlDecode();
    if (oRetorno.status == 0) {

      var aTipoCompra = oRetorno.aPcTipoCompra;

      me.oObservacao = oRetorno.sResumo;

      aTipoCompra.each(function (oRetTipoCompra) {

        var sConfigDescCompra = oRetTipoCompra.pc50_codcom+" - "+oRetTipoCompra.pc50_descr.urlDecode();
        me.oTipoCompra.addItem(oRetTipoCompra.pc50_codcom, sConfigDescCompra);
      });

      if (oRetorno.iTipoCompraInicial != 0) {
        me.oTipoCompra.setValue(oRetorno.iTipoCompraInicial);
      }

      var aTipoEmpenho = oRetorno.aTipoEmpenho;
      aTipoEmpenho.each(function (oRetTipoEmpenho) {

        var sConfigDescEmpenho = oRetTipoEmpenho.e41_codtipo+" - "+oRetTipoEmpenho.e41_descr.urlDecode();
        me.oTipoEmpenho.addItem(oRetTipoEmpenho.e41_codtipo, sConfigDescEmpenho);
      });

      /**
       * Busca os tipos de licita��o para o tipo de compra escolhido 
       */
      me.buscarTipoLicitacao(me.oTipoCompra.getValue());
    }
  }

  /**
   *  Itens Licita��o
   *  Fun��o que mostra os itens para uma licita��o
   */
  this.itensAutorizacao = function() {

    var sContentItens  = "<center>";
        sContentItens += "<div id='divItensEmp'>";
        sContentItens += "  <form name='frmItens' id='frmItens'>";
        sContentItens += "    <fieldset style='width: 80%; padding: 10px;'>";
        sContentItens += "      <legend><b>Itens da "+me.sTipoOrigem+" "+me.getOrigem()+"</b></legend>";
        sContentItens += "      <div id='dataGridItens'>";
        sContentItens += "      </div>";
        sContentItens += "    </fieldset>";
        sContentItens += "  </form>";
        sContentItens += "  <input type='button' name='btnVisualizarAutorizacao' id='btnVisualizarAutorizacao' value='Visualizar Autoriza��es'>";
        sContentItens += "  &nbsp;&nbsp;";
        sContentItens += "  <input  type='button' name='btnAlterarAutorizacao' id='btnAlterarAutorizacao' value='Alterar Dados Autoriza��es'>";
        sContentItens += "  &nbsp;&nbsp;";
        sContentItens += "  <input type='button' name='btnEscolherLicitacao' id='btnEscolherLicitacao' value='Voltar'>";
        sContentItens += "</div>";
        sContentItens += "</center>";


    /**
     *  Monta Grid com DBGrid
     */

    me.oDataGridItens              = new DBGrid('gridItensLicitacao');
    me.oDataGridItens.nameInstance = me.sInstancia+".oDataGridItens";
    me.oDataGridItens.setCellAlign(new Array("right",  // 1
                                             "left",   // 2
                                             "right",  // 3
                                             "left",   // 4
                                             "left",   // 5
                                             "left",   // 6
                                             "left",   // 7
                                             "center", // 8
                                             "right",  // 9
                                             "right",  // 10
                                             "right",  // 11
                                             "right",  // 12
                                             "right",  // 13
                                             "center", // 14
                                             "center", // 15
                                             "center")); // 16

    me.oDataGridItens.setCheckbox(0);
    me.oDataGridItens.setHeader(new Array("Cod",        // 1
                                          "Ref",        // 2
                                          "Sol",        // 3
                                          "Descri��o",  // 4
                                          "Obs Item",       // 5
                                          "Obs Solicita��o", // 6
                                          "Fornecedor", // 7
                                          "Dot",        // 8
                                          "Tot",        // 9
                                          "Disp",       // 10
                                          "Quant",      // 11
                                          "Val Unt",    // 12
                                          "Total",      // 13
                                          "R",          // 14
                                          "Indice",     // 15
                                          "I"));        // 16

    me.oDataGridItens.aHeaders[15].lDisplayed = false;

    me.oDataGridItens.setHeight(300);
    /**
     *  Executa RPC que busca os itens da autoriza��o
     */
    me.getItensAutorizacao();

    if (me.oNode == null) {

      me.oWindowAux.hide();

      if (me.oWindowAuxItens != "") {
        me.oWindowAuxItens.show();
      } else {

        me.oWindowAuxItens = new windowAux("windowItens_"+me.sInstancia, "Itens "+me.sTipoOrigem, me.getWidth(2), me.getHeight());
        me.oWindowAuxItens.setContent(sContentItens);

        var sHelpMsgBoardItens = "Informe os itens da autoriza��o de empenho.";
        var oMessageBoardItens = new DBMessageBoard('msg_boardItens'+me.sInstancia,
                                                    "Itens "+me.sTipoOrigem+" "+me.getOrigem(),
                                                     sHelpMsgBoardItens,
                                                     me.oWindowAuxItens.getContentContainer()
                                                    );
        oMessageBoardItens.show();
        me.oWindowAuxItens.show();
      }
    } else {

      $('divAutEmpenho').style.display = 'none';
      $('ctnItens').innerHTML = sContentItens;
    }

    me.oDataGridItens.show($('dataGridItens'));
    me.oToolTip     = new DBToolTip('',$('dataGridItens'));
    me.oToolTipErro = new DBToolTip('', this);

    /**
     *  Abre janela para alterar os dados da autoriza��o
     */
    $('btnAlterarAutorizacao').observe('click', function() {
      me.alterarAutorizacao();
    });

    /**
     *  Abre window com os dados das autoriza��es
     */
    $('btnVisualizarAutorizacao').observe('click', function() {
      me.visualizarAutorizacao();
    }); 

    $('btnEscolherLicitacao').observe('click', function() {
      me.setLocation();
    });

  }

  /**
   *  Retorna os itens para a Geracao da Autorizacao de Empenho
   */
  this.getItensAutorizacao = function () {

    js_divCarregando("Carregando itens da licita��o, aguarde...", "msgBox");

    var oParamItens     = new Object();
    oParamItens.exec    = "getItensParaAutorizacao";
    oParamItens.iCodigo = me.getOrigem();

    var oAjaxItens = new Ajax.Request(me.sRPC,
                                     {method: 'post',
                                      parameters:'json='+Object.toJSON(oParamItens),
                                      onComplete:me.preencheItens
                                     }
                                     );
  }

  /**
   * Preenche os itens
   */
  this.preencheItens = function (oResponse) {

    js_removeObj("msgBox");
    var oRetorno = eval("("+oResponse.responseText+")");
    if (oRetorno.status == 1) {

      me.aItens = oRetorno.aItens;
      me.oDataGridItens.clearAll(true);

      /**
       *  Verifica se existem itens para a licita��o informada
       *  Caso n�o existam o usu�rio � direcionado para tela de escolha da licita��o
       */
      if (me.aItens == null || me.aItens.length == 0) {

        alert("N�o existem itens para esta "+me.sTipoOrigem+".");
        me.setLocation();
        return false;
      }

      /**
       *  Adiciona os dados do item na grid
       */
      me.aItens.each( function(oItem, id) {

        var lDisabled = false;
        if (!oItem.dotacaocomsaldo ||
            (oItem.saldoquantidade == 0 || oItem.saldovalor == 0) ||
            oItem.anodotacao < me.getAno() ||
            oItem.fornecedor == "") {
          lDisabled = true;
        }

        var sNomeQuant = "oDbTextQuant"+oItem.codigodotacaoitem;
        var sNomeValor = "oDbTextValor"+oItem.codigodotacaoitem;
        var aRow = new Array();
        aRow[0]  = oItem.codigodotacaoitem;
        aRow[1]  = oItem.descricaomaterial.urlDecode().substr(0,10);
        aRow[2]  = oItem.codigosolicitacao;
        aRow[3]  = oItem.descricaomaterial.urlDecode().substr(0,30);
        aRow[4]  = oItem.observacao.urlDecode().substr(0,20);
        aRow[5]  = oItem.observacao_solicita.urlDecode().substr(0,20);
        aRow[6]  = oItem.fornecedor.urlDecode().substr(0,20);
        aRow[7]  = "<a href='#' onclick='"+me.sInstancia+".showDadosDotacao(\""+oItem.codigodotacao+"\")'>"+oItem.codigodotacao+"</a>";
        if (oItem.contrapartida != '') {
          aRow[7] += "<span title='Recurso de contrapartida'>(CP "+oItem.contrapartida+")</span>";
        }
        aRow[8]  = oItem.quanttotalitem;
        aRow[9]  = oItem.saldoquantidade;
        aRow[10]  = eval(sNomeQuant+" = new DBTextField('"+sNomeQuant+"', '"+sNomeQuant+"', '"+oItem.saldoquantidade+"', 3);");
        aRow[10].addEvent('onKeyPress', ';return '+me.sInstancia+'.validaValor(event);');
        aRow[10].addStyle('text-align', 'right');



        /*
         * Caso o item for servico n�o podemos habilitar digitacao do item
         */
        if (oItem.servico || lDisabled) {

          /**
           * nova maneira de tratar um servi�o, agora se a propriedade pc11_servicoquantidade for true
           * o servi�o sera tratado como um item normal, podendo ter varias quantidades e seu valor total controlado
           * pela quantidade
           */
          if (oItem.servicoquantidade == 't') {

            aRow[10].addEvent('onChange', ';'+me.sInstancia+'.calcularSaldoItem(this,"'+id+'");');
          } else {
            aRow[10].setReadOnly(true);
          }


        } else {
          aRow[10].addEvent('onChange', ';'+me.sInstancia+'.calcularSaldoItem(this,"'+id+'");');
        }
        oItem.saldovalor = js_round(oItem.saldovalor, 2);
        var nValorTotal  = oItem.saldovalor;

        if (!oItem.servico || (oItem.servico && oItem.servicoquantidade == "t")) {
          nValorTotal  = new Number(oItem.saldoquantidade) * new Number(oItem.valorunitariofornecedor);
        }
        aRow[10].addStyle("height", '100%');
        aRow[11] = parseFloat( oItem.valorunitariofornecedor );
        aRow[12] = eval(sNomeValor+" = new DBTextField('"+sNomeValor+"', '"+sNomeValor+"', '"+nValorTotal+"', 5);");
        aRow[12].addEvent('onKeyPress', ';return '+me.sInstancia+'.validaValor(event);');
        aRow[12].addStyle('text-align', 'right');

        /*
         * Caso o item for material n�o podemos habilitar digitacao do valor total
         */
        if (!oItem.servico || lDisabled) {
          aRow[12].setReadOnly(true);
        } else {
          aRow[12].addEvent('onChange', ';'+me.sInstancia+'.validarValorTotalItem(this,"'+id+'");');
        }

        /**
         * nova maneira de tratar um servi�o, agora se a propriedade pc11_servicoquantidade for true
         * o servi�o sera tratado como um item normal, podendo ter varias quantidades e seu valor total controlado
         * pela quantidade
         */
        if (oItem.servicoquantidade == 't') {
          aRow[12].setReadOnly(true);
        }



        aRow[12].addStyle("height", '100%');

        aRow[13] = oItem.valorreserva > 0?"S":"N";
        aRow[14] = new String(id);
        aRow[15] = "<span id='span_"+new String(id)+"' style='font-weight:bold;color:red;'>&nbsp;&nbsp;!&nbsp;&nbsp;</span>";

        me.oDataGridItens.addRow(aRow, false,lDisabled);

         with (me.oDataGridItens) {

           /**
            *  Mostra um ToolTip para os dados da grid que tem limita��o de caracteres
            */
           aRows[id].aCells[2].sEvents += "onMouseOver=\""+me.sInstancia+".oToolTip.setText(\'"+oItem.descricaomaterial.urlDecode()+"\').show();\"";
           aRows[id].aCells[2].sEvents += "onMouseOut='"+me.sInstancia+".oToolTip.hide();'";

           aRows[id].aCells[4].sEvents += "onMouseOver=\""+me.sInstancia+".oToolTip.setText(\'"+oItem.descricaomaterial.urlDecode()+"\').show();\"";
           aRows[id].aCells[4].sEvents += "onMouseOut='"+me.sInstancia+".oToolTip.hide();'";

           aRows[id].aCells[5].sEvents += "onMouseOver=\""+me.sInstancia+".oToolTip.setText(\'"+oItem.observacao.urlDecode()+"\').show();\"";
           aRows[id].aCells[5].sEvents += "onMouseOut='"+me.sInstancia+".oToolTip.hide();'";

           aRows[id].aCells[6].sEvents += "onMouseOver=\""+me.sInstancia+".oToolTip.setText(\'"+oItem.observacao_solicita.urlDecode()+"\').show();\"";
           aRows[id].aCells[6].sEvents += "onMouseOut='"+me.sInstancia+".oToolTip.hide();'";

           aRows[id].aCells[7].sEvents += "onMouseOver=\""+me.sInstancia+".oToolTip.setText(\'"+oItem.fornecedor.urlDecode()+"\').show();\"";
           aRows[id].aCells[7].sEvents += "onMouseOut='"+me.sInstancia+".oToolTip.hide();'";

           /**
            *  Verifica se o item tem quantidade e saldo para gerar a autoriza��o
            */
           if (lDisabled) {

             var sColor = "red";
             aRows[id].setClassName("disabled");
             /*
              * Item sem saldo para gerar autoriza��o
              */
             if (oItem.saldoquantidade == 0 || oItem.saldovalor == 0) {

               var sAutorizacoesGeradas = oItem.autorizacaogeradas.implode(", ");
               var sTextoInformacao = "Item sem saldo para a gera��o de autoriza��es. <br>Autoriza��es Geradas: "+sAutorizacoesGeradas;
             }

             /*
              * Dota��o sem saldo para gerar autoriza��o
              */
             if (!oItem.dotacaocomsaldo) {

               var sTextoInformacao  = "Dota��o "+oItem.codigodotacao+ " sem saldo para gera��o da autoriza��o ";
               sTextoInformacao     += "ou com dados do ano inferior ao atual.<br><br>";
               sTextoInformacao     += "Saldo:  "+js_formatar(oItem.saldofinaldotacao,'f')+"<br>";
               sTextoInformacao     += "Valor Reservado: "+js_formatar(oItem.valorreserva,'f');
             }

             /**
              * Valida se a dota��o do ano � diferente do ano atual. Caso seja a op��o para gerar autoriza��o
              * do empenho do item � bloqueada. O usu�rio dever� alterar a dota��o do item para uma dota��o
              * do ano corrente.
              */
             if (oItem.anodotacao < me.getAno()) {

               var sTextoInformacao  = "O item "+oItem.descricaomaterial.urlDecode()+" possui uma dota��o referente ";
               sTextoInformacao     += "a um ano anterior a "+me.getAno()+", por este motivo voc� deve alterar ";
               sTextoInformacao     += "a dota��o do item.";
             }

             /**
              * Caso n�o exista or�amento, � apresentada a mensagem avisando. A linha ser� bloqueada caso
              * essa condi��o seja verdadeira
              */
             if (oItem.fornecedor == "") {
               var sTextoAutorizacoes = "O item "+oItem.descricaomaterial.urlDecode()+" n�o possui or�amento lan�ado.";
             }

           } else {

             var sTextoInformacao = "Item com saldo para gerar autoriza��es.";
             var sColor = "blue";
           }

           aRows[id].aCells[16].content  = "<span id='span_"+new String(id)+"' style='font-weight:bold;color:"+sColor+";'>";
           aRows[id].aCells[16].content += "&nbsp;&nbsp;!&nbsp;&nbsp;</span>";
           aRows[id].aCells[16].sEvents  = "onMouseOver=\""+me.sInstancia;
           aRows[id].aCells[16].sEvents += ".oToolTipErro.setText(\'"+sTextoInformacao+"\').setElement(this).show('l');\"";
           aRows[id].aCells[16].sEvents += "onMouseOut='"+me.sInstancia+".oToolTipErro.hide();'";
         }
      });
      me.oDataGridItens.renderRows();
    } else {

      alert(oRetorno.message.urlDecode());
      me.setLocation();
    }
  };

  /**
   * Calcula o valor total do item
   * @param {Number}  nQuantidade  quantidade a ser calculada
   * @param {integer} iItem Indice do item
   */
  this.calculaValorTotalItem = function (nQuantidade, iItem) {

    var nValorItem              = nQuantidade.valueOf() * new Number(me.aItens[iItem].valorunitariofornecedor).valueOf();
    me.aItens[iItem].saldovalor = js_round(nValorItem, 2);

    $(me.oDataGridItens.aRows[iItem].aCells[13].getId()).getElementsByTagName('input')[0].setValue(me.aItens[iItem].saldovalor);
  }


  /**
   * Busca o tipo de licita��o para o tipo de compra escolhido 
   * @param {integer} C�digo do tipo de compra
   */
  this.buscarTipoLicitacao = function(iTipoCompra) {
    
    if (iTipoCompra != "" && iTipoCompra != "undefined") {

      var oParamTipoCompra         = new Object();
      oParamTipoCompra.iTipoCompra = iTipoCompra;
      oParamTipoCompra.exec        = "getTipoLicitacao";
      var oAjaxTipoCompra          = new Ajax.Request(me.sRPC, 
                                                      {
                                                        method: 'post', 
                                                        parameters:'json='+Object.toJSON(oParamTipoCompra), 
                                                        onComplete:me.preencheTipoLicitacao
                                                      }
                                                     ); 
    }
  }

  /**
   * Preenche os tipos de licita��o encontrados 
   */
  this.preencheTipoLicitacao = function(oAjax) {

    var oRetorno = eval("("+oAjax.responseText+")");
    me.oTipoLicitacao.clearItens();
    if (oRetorno.aTiposLicitacao != "") {

      me.oTipoLicitacao.setEnable();
      me.oNumeroLicitacao.setReadOnly(false);
      oRetorno.aTiposLicitacao.each(function (oItem) {

        me.oTipoLicitacao.addItem(oItem.l03_tipo, oItem.l03_tipo + " - " + oItem.l03_descr);
      });
    } else {

      me.oNumeroLicitacao.setReadOnly(true);
      me.oNumeroLicitacao.setValue("");
      me.oTipoLicitacao.setDisable();
    }
  }

  /**
   *  Fun��o Calulcar Saldo Item
   *  Verifica se o saldo digitado � inferior ao saldo dispon�vel
   *  @param {object}  oCampo - campo com a quantidade digitada
   *  @param {integer} iItem  - indice do item
   */
  this.calcularSaldoItem = function(oCampo, iItem) {

    var nSaldoCampo = new Number(oCampo.value);
    
    var nSaldoItem  = new Number(me.aItens[iItem].saldoquantidade);


    /**
     * Realiza a valida��o do saldo do item
     */
    if (nSaldoCampo.valueOf() > nSaldoItem.valueOf()) {

      alert("Voc� informou uma quantidade maior que o dispon�vel para o item.");
      oCampo.value = nSaldoItem.valueOf();
      me.calculaValorTotalItem(nSaldoItem, iItem);

    } else {
      me.calculaValorTotalItem(nSaldoCampo, iItem);
    }
  }


  /**
   *  Fun��o Valida Valor Total Item
   *  Valida se o valor digitado � superior ao valor total do item.
   *
   *  @param {object} oCampo valor digitado pelo usu�rio
   *  @param {integer} iItem indice do item
   */
  this.validarValorTotalItem = function (oCampo, iItem) {

    var nValorCampo = new Number(oCampo.value);
    var nValorItem  = new Number(me.aItens[iItem].saldovalor);

    if (nValorCampo.valueOf() > nValorItem.valueOf()) {

      var sMensagemErro  = "Voc� informou um valor superior ao valor do item.\n";
          sMensagemErro += "O valor total para este item/dota��o � "+js_formatar(nValorItem, 'f');

      alert(sMensagemErro);
      oCampo.value = nValorItem.valueOf();
    }
  }


  /**
   *  Visualizar Autoriza��o
   *  Abre a janela para visualizar as autoriza��es de uma licita��o
   */
  this.visualizarAutorizacao = function () {
  

    var oItensSelecionados = me.oDataGridItens.getSelection('object');
    if (oItensSelecionados.length == 0 ) {

      alert("Selecione um item para continuar.");
      return false;
    }
    js_divCarregando("Carregando autoriza��es, aguarde...", "msgBox");
    var aAutorizacoes = new Array();
    /**
     * Cria Objetos para as autoriza��es e seus itens
     */
    oItensSelecionados.each(function (oItem, iLinha) {

      var oItemLicitacao         = me.aItens[oItem.aCells[15].getValue()];
      var sHash                  = new String();
      var iDotacao               = new String($(oItem.aCells[8]).getValue());
      var iCGM                   = new String(oItemLicitacao.codigofornecedor);
      var iContraPartida         = new String(oItemLicitacao.contrapartida);
      var iElemento              = new String(oItemLicitacao.codigoelemento);
      var iPcDotac               = new String(oItem.aCells[1].getValue());
      var iCodigoItemSolicitacao = new String(oItemLicitacao.codigoitemsolicitacao);
      sHash = iDotacao+iCGM+iElemento+iContraPartida;


      var oAutorizacao                   = new Object();
      oAutorizacao.cgm                   = iCGM.valueOf();
      oAutorizacao.elemento              = iElemento.valueOf();
      oAutorizacao.dotacao               = iDotacao.valueOf();
      oAutorizacao.pcdotac               = iPcDotac.valueOf();
      oAutorizacao.contrapartida         = iContraPartida.valueOf();
      oAutorizacao.lMarcada              = true;
      oAutorizacao.codigoitemsolicitacao = iCodigoItemSolicitacao.valueOf();
      oAutorizacao.hash                  = sHash.valueOf();
      oAutorizacao.itens                 = new Array();
      oAutorizacao.valortotal            = new Number(0);
      var iIndiceAutorizacao = -1;

      /**
       * identifica qual o indice da Autoriza��o na collection de autorizacoes
       */
      aAutorizacoes.each(function (oAut, id) {
        if (oAut.hash == sHash) {

          iIndiceAutorizacao = id;
          return true;
        };
      });

      if (iIndiceAutorizacao == -1) {

        aAutorizacoes.push(oAutorizacao);
        iIndiceAutorizacao = aAutorizacoes.length - 1;
      }
      var oItemAutorizacao               = new Object();
      oItemAutorizacao.codigomaterial    = oItemLicitacao.codigomaterial;
      oItemAutorizacao.quantidade        = $(oItem.aCells[11].getId()).getElementsByTagName('input')[0].value;
      oItemAutorizacao.valorunitario     = oItem.aCells[12].getValue();
      oItemAutorizacao.valortotal        = $(oItem.aCells[13].getId()).getElementsByTagName('input')[0].value;

      /**
       * Caso for um servi�o e n�o for controlado por quantidade, setamos o valor unit�rio
       * do servi�o para o valor total
       */
      if ( oItemLicitacao.servico && oItemLicitacao.servicoquantidade == 'f' ) {
        oItemAutorizacao.valorunitario  = oItemAutorizacao.valortotal;
      }

      /**
       * Fazemos o parse para float do valor unitario
       */
      oItemAutorizacao.valorunitario     = parseFloat( oItemAutorizacao.valorunitario );
      oItemAutorizacao.descricaomaterial = oItemLicitacao.descricaomaterial;
      oItemAutorizacao.codigoprocesso    = oItemLicitacao.codigoitemprocesso;
      oItemAutorizacao.codigoelemento    = oItemLicitacao.codigoelemento;
      oItemAutorizacao.descricaoelemento = oItemLicitacao.descricaoelemento;
      oItemAutorizacao.elemento          = oItemLicitacao.elemento;
      oItemAutorizacao.observacao        = oItemLicitacao.observacao.urlDecode();
      oItemAutorizacao.pcdotac           = iPcDotac.valueOf();
      oItemAutorizacao.fornecedor        = oItemLicitacao.fornecedor.urlDecode();
      oItemAutorizacao.solicitem         = oItemLicitacao.codigoitemsolicitacao;
      aAutorizacoes[iIndiceAutorizacao].itens.push(oItemAutorizacao);
      aAutorizacoes[iIndiceAutorizacao].valortotal += new Number(oItemAutorizacao.valortotal);

    });
    me.aAutorizacoesGerar   = aAutorizacoes;
    var sContentVisualizar  = "<center>";
        sContentVisualizar += "<div id='visualizarAuth_"+me.sInstancia+"'>";
        sContentVisualizar += "  <form>";
        sContentVisualizar += "  <fieldset style='width:800px;'>";
        sContentVisualizar += "    <legend><b>Autoriza��es</b></legend>";
        sContentVisualizar += "    <div id='visualizarAuth'>";
        sContentVisualizar += "    </div>";
        sContentVisualizar += "  </fieldset>";
        sContentVisualizar += "  </form>";
        sContentVisualizar += "  <input type='button' name='btnGerarAutorizacao' id='btnGerarAutorizacao' value='Gerar Autoriza��es' />";
        sContentVisualizar += "  &nbsp;";
        sContentVisualizar += "  <input type='button' name='btnCancelarAutorizacao' id='btnCancelarAutorizacao' value='Cancelar' />";
        sContentVisualizar += "</div>";
        sContentVisualizar += "</center>";


    me.oDataGridAuth = new DBGrid('gridAutorizacoes');
    me.oDataGridAuth.nameInstance = me.sInstancia+".oDataGridAuth";
    me.oDataGridAuth.setHeader(new Array("Item",          // 0
                                         "Descri��o",     // 1
                                         "Desdobramento", // 2
                                         "Fornecedor",    // 3
                                         "Qtde",          // 4
                                         "V. Unit�rio",   // 5
                                         "V. Total"       // 6
                                         ));

    me.oWindowAuxAuth = new windowAux('windowAuth_'+me.sInstancia, 'Autoriza��es', me.getWidth(2), me.getHeight());
    me.oWindowAuxAuth.setContent(sContentVisualizar);

    var sHelpMsgBoardAuth = "Confirme os dados das autoriza��es e clique em 'Gerar Autoriza��es'.";
    var oMessageBoardAuth = new DBMessageBoard('msg_boardAuth'+me.sInstancia,
                                               "Autoriza��o da "+me.sTipoOrigem+" "+me.getOrigem(),
                                                sHelpMsgBoardAuth,
                                                me.oWindowAuxAuth.getContentContainer()
                                              );
    oMessageBoardAuth.show();
    me.oWindowAuxAuth.show();
    me.oWindowAuxAuth.setShutDownFunction(function() {
      me.oWindowAuxAuth.destroy();
    });
    me.oDataGridAuth.show($('visualizarAuth'));
    me.oDataGridAuth.clearAll(true);

    /**
     * Cria um tooltip para ser anexado a div id 'visualizarAuth'
     */
    me.oToolTipAuth = new DBToolTip('',$('visualizarAuth'));

    /**
     * Monta a linhas das autoriza�oes
     */
    var iLinha = 0;
    me.aAutorizacoesGerar.each(function(oAutorizacao, iAut) {

      with (oAutorizacao) {

        var sFuncao = me.sInstancia+".marcaAutorizacao("+iAut+")";
        var aRow =  new Array();
        aRow[0]  = "<input type='checkbox' value='"+iAut+"' checked onclick=\""+sFuncao+"\" />";
        aRow[0] += (iAut+1)+"� Aut";
        aRow[1]  = "Dotacao: (<a href='#' onclick='"+me.sInstancia+".showDadosDotacao(\""+dotacao+"\")'>"+dotacao+"</a>)";
        if (contrapartida != '') {
          aRow[1] += "<span title='Recurso de contrapartida'>(CP "+contrapartida+")</span>";
        }
        aRow[2]  = "";
        aRow[3]  = "";
        aRow[4]  = "";
        aRow[5]  = "";
        aRow[6]  = js_formatar(valortotal , "f");

        me.oDataGridAuth.addRow(aRow);
        me.oDataGridAuth.aRows[iLinha].sStyle ='background-color:#eeeee2;';
        me.oDataGridAuth.aRows[iLinha].aCells.each(function(oCell, id) {

          oCell.sStyle +=';border-right: 1px solid #eeeee2;';
          oCell.sStyle += 'text-align:left;font-weight:bold;';
        });

        me.oDataGridAuth.aRows[iLinha].aCells[1].sStyle  = 'border-right: 1px solid #eeeee2;1px solid #eeeee2;';
        me.oDataGridAuth.aRows[iLinha].aCells[6].sStyle += 'text-align:right';
        iLinha++;
        itens.each(function(oItem, id) {

          var aLinha    = new Array();
              aLinha[0] = oItem.codigomaterial;
              aLinha[1] = oItem.descricaomaterial.urlDecode();
              aLinha[2] = oItem.elemento;
              aLinha[3] = oItem.fornecedor;
              aLinha[4] = oItem.quantidade;
              aLinha[5] = oItem.valorunitario;
              aLinha[6] = js_formatar(oItem.valortotal, "f");

          me.oDataGridAuth.addRow(aLinha);

          me.oDataGridAuth.aRows[iLinha].aCells[0].sStyle = "text-align:right;";  // item
          me.oDataGridAuth.aRows[iLinha].aCells[2].sStyle = "text-align:center;"; // elemento
          me.oDataGridAuth.aRows[iLinha].aCells[4].sStyle = "text-align:right;";  // quantidade
          me.oDataGridAuth.aRows[iLinha].aCells[5].sStyle = "text-align:right;";  // valor unitario
          me.oDataGridAuth.aRows[iLinha].aCells[6].sStyle = "text-align:right;";  // valor total

          with (me.oDataGridAuth) {

            var sTexto  = '<b>Elemento:</b> '+oItem.elemento.urlDecode();
                sTexto += '<br><b>Descri��o:</b> '+oItem.descricaoelemento.urlDecode();
            me.oDataGridAuth.aRows[iLinha].aCells[2].sEvents  = "onMouseOver=\""+me.sInstancia+".oToolTipAuth.setText(\'"+sTexto+"\').show();\"";
            me.oDataGridAuth.aRows[iLinha].aCells[2].sEvents += "onMouseOut='"+me.sInstancia+".oToolTipAuth.hide();'";

          }

          iLinha++;
        });
      }
    });

    me.oDataGridAuth.renderRows();
    me.oDataGridAuth.setNumRows(me.aAutorizacoesGerar.length);

    js_removeObj("msgBox");

    /**
     *  Destroy a janela quando clicado no bot�o CANCELAR
     */
    $('btnCancelarAutorizacao').observe('click', function() {
      me.oWindowAuxAuth.destroy();
    });

    $('btnGerarAutorizacao').observe('click', function() {
      me.processarAutorizacoes();
    });
  }

  /**
   *  Retorna o valor da propriedade iLicitacao o n�mero da licita��o que est� sendo gerada a autoriza��o.
   *  @return {integer}
   */
  this.getOrigem = function () {
    return me.iOrigemDados;
  }

  /**
   * Fun��o que seta valor na propriedade iCodigoOrigemDados. O dado deve ser o c�digo de uma
   * solicita��o de compra, licita��o ou processo de compra
   * @param {integer}
   */
  this.setOrigem = function(iCodigoOrigemDados) {
    me.iOrigemDados = iCodigoOrigemDados;
  }

  /**
   * Fun��o SetInstituicao
   * Seta um valor para a propriedade iInstit
   * @param {integer}
   */
  this.setInstituicao = function(iInstit) {
    me.iInstit = iInstit
  }
  /**
   * Fun��o GetInstituicao
   * Retorna o valor setado para a v�ri�vel iInstit
   * @return {integer}
   */
  this.getInstituicao = function () {
    return me.iInstit;
  }

  /**
   *  Fun��o Set Origem
   *  Redireciona o usu�rio para a p�gina respons�vel pela escolha do PC, Licitacao ou Solicitacao
   */
  this.setLocation = function () {
    location.href = me.sArquivoOrigem;
  }

  /**
   * Seta valor na propriedade iAno
   * @return {integer}
   */
  this.setAno = function(iAno) {
    me.iAno = iAno;
  }
  /**
   * Retorna o valor da propriedade iAno
   * @return {integer}
   */
  this.getAno = function() {
    return me.iAno;
  }

  /**
   *  Fun��o GetHeight
   *  Retorna o height da ela do usu�rio configurado para mostrar a windows no tamanho correto.
   *  @return {integer}
   */
  this.getHeight = function () {

    iHeight = (screen.height/2);
    if (iHeight < 450) {
      iHeight = 450;
    }
    return iHeight;
  }

  /**
   *  Fun��o GetWidth
   *  Retorna o width da ela do usu�rio configurado para mostrar a windows no tamanho correto.
   *  @param {integer} - 1 = Autoriza��o de Empenho | 2 = Itens da Licita��o
   *  @return {integer}
   */
  this.getWidth = function (iWindow) {

    iWidth  = (screen.width/2);
    if (iWindow == 1) {
      if (iWidth < 800) {
        iWidth = 800;
      }
    } else if (iWindow == 2) {
      if (iWidth < 830) {
        iWidth = 830;
      }
    }
    return iWidth;
  }

  /**
   * Mostra o saldo da dotacao para o usuario
   * @param {integer} iDotacao Codigo da Dotacao
   *
   */
  this.showDadosDotacao = function(iDotacao) {
    js_OpenJanelaIframe('','db_iframe_dotacao','func_saldoorcdotacao.php?coddot='+iDotacao,'Saldo Dotacao',true);
    $('Jandb_iframe_dotacao').style.zIndex = '10000';
  }

  /**
   * Mostra a tela para atualiza��o de dados da autoriza��o
   */
  this.alterarAutorizacao = function () {

    $('btnItens').style.display = 'none';
    $('btnFechar').style.display = '';

    if (me.oWindowAux == '') {

      me.oWindowAux = new windowAux('window_'+me.sInstancia, 'Autoriza��o de Empenho', me.getWidth(1), me.getHeight());

      var sHelpMsgBoard = "Preencha os dados do destino da autoriza��o de empenho.";
      me.oWindowAux.setObjectForContent($('divAutEmpenho'));
      var oMessageBoard = new DBMessageBoard('msg_board'+me.sInstancia,
                                             'Autoriza��o de Empenho '+me.getOrigem(),
                                              sHelpMsgBoard,
                                              $('divAutEmpenho')
                                           );

     oMessageBoard.show();
    }

    me.oWindowAux.show();
  }

  /**
   * Valida o valor digitado nos campos Quantidade e Valor e valida se existe
   * o caractere "ponto ." S� pode existir uma vez.
   */
  this.validaValor = function (event) {

    var iTecla = event.which;

    if (iTecla == 46) {

      if (event.target.value.indexOf('.') > -1) {
        return false;
      } else {
        return true;
      }
    }
    return js_mask(event,"0-9|.");
  }

  /**
   *  Abre o documento com as autoriza��es geradas.
   */
  this.emiteDocumentoAutorizacao = function(oAjax) {

    var oRetorno        = eval("("+oAjax.responseText+")");
    var sParametroQuery = "";
    var aAuthRetorno    = oRetorno.autorizacoes;
    var sVirgula        = "";

    aAuthRetorno.each(function(iAutorizacao) {

      sParametroQuery += sVirgula + iAutorizacao;
      sVirgula = ",";
    });

    var sURLDocumento = "emp2_emiteautori002.php?sDocAutorizacoes="+sParametroQuery+"&instit="+me.getInstituicao()+"&informa_adic=PC";
    var jan = window.open(sURLDocumento,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }


  /**
   *  Envia para o RPC processar os itens informados.
   */
  this.processarAutorizacoes = function () {

    if (!confirm("Confirma a gera��o das autoriza��es?")) {
      return false;
    }
    $('btnGerarAutorizacao').disabled    = true;
    $('btnCancelarAutorizacao').disabled = true;
    var oParamAutorizacao                = new Object();
    oParamAutorizacao.exec               = "gerarAutorizacoes";
    oParamAutorizacao.aAutorizacoes      = new Array();
    oParamAutorizacao.iCodigo            = me.getOrigem();

    me.aAutorizacoesGerar.each(function(oDadosAutorizacao, iAut) {

      if (oDadosAutorizacao.lMarcada) {

        oAutorizacao = oDadosAutorizacao;
        oAutorizacao.destino           = encodeURIComponent(tagString(me.oTxtDestino.getValue()));
        oAutorizacao.tipoempenho       = encodeURIComponent(tagString(me.oTipoEmpenho.getValue()));
        oAutorizacao.tipocompra        = me.oTipoCompra.getValue();
        oAutorizacao.prazoentrega      = me.oPrazoEntrega.getValue();
        oAutorizacao.concarpeculiar    = $F('oTxtCaractPeculiarCod');
        oAutorizacao.condicaopagamento = encodeURIComponent(tagString(me.oCondicaoPagamento.getValue()));
        oAutorizacao.resumo            = encodeURIComponent(tagString($F('oTxtObservacoes')))

        oAutorizacao.sTelefone         = me.oTelefone.getValue();
        oAutorizacao.sTipoLicitacao    = me.oTipoLicitacao.getValue();
        oAutorizacao.iNumeroLicitacao  = me.oNumeroLicitacao.getValue();
        oAutorizacao.sContato          = encodeURIComponent(tagString(me.oContato.getValue()));
        oAutorizacao.sOutrasCondicoes  = encodeURIComponent(tagString(me.oOutrasCondicoes.getValue()));

        oAutorizacao.itens.each(function (oItem, iLinha) {

          with (oItem) {

            delete descricaomaterial;
            delete descricaoelemento;
            delete elemento;
            delete fornecedor;
            observacao = encodeURIComponent(tagString(observacao));

          }
        });
        oParamAutorizacao.aAutorizacoes.push(oAutorizacao);
      }
    });
    if (oParamAutorizacao.aAutorizacoes.length == 0) {

      alert('Selecione ao m�nino uma autoriza��o para ser gerada;');
      return false;
    }
    js_divCarregando('Aguarde, processando Autoriza��es...', 'msgBox');
    var oAjaxItens = new Ajax.Request(me.sRPC,
                                     {method: 'post',
                                      parameters:'json='+Object.toJSON(oParamAutorizacao),
                                      onComplete: function(oResponse) {

                                        js_removeObj('msgBox');
                                        $('btnGerarAutorizacao').disabled    = false;
                                        $('btnCancelarAutorizacao').disabled = false;
                                        var oRetorno = eval("("+oResponse.responseText+")");
                                        alert(oRetorno.message.urlDecode());

                                        if (oRetorno.status == 1) {
                                          me.oWindowAuxAuth.destroy();
                                          me.emiteDocumentoAutorizacao(oResponse);
                                          me.getItensAutorizacao();
                                        }
                                      }
                                     }
                                     );

  }


  this.marcaAutorizacao = function (iAut) {

    if (me.aAutorizacoesGerar[iAut]) {

      if (me.aAutorizacoesGerar[iAut].lMarcada) {
        me.aAutorizacoesGerar[iAut].lMarcada = false;
      } else {
        me.aAutorizacoesGerar[iAut].lMarcada = true;
      }

    }
  }


  this.alterarDotacoes = function (aSolicitacoes) {

    oWindowSolicitacoes = new windowAux('wndSolicitacoes', "Lista de Solicita��es", 800, 450);
    oWindowSolicitacoes.setShutDownFunction(function () {
      oWindowSolicitacoes.destroy();
    });
    var sContent  = "<div id='ctnSolicitacao'>";
        sContent += "  <fieldset>";
        sContent += "    <legend><b>Solicita��es</b></legend>";
        sContent += "    <div id='ctnGridSolicitacoes'></div>";
        sContent += "  </fieldset>";
        sContent += "<div>";
    oWindowSolicitacoes.setContent(sContent);
    oMessageBoard = new DBMessageBoard('msgBoardSolicitacao',
                                       'Solicita��es Retornadas',
                                       'Clique duplo na solicita��o para visualizar as seus itens e dota��es.',
                                       oWindowSolicitacoes.getContentContainer()
                                       );
    oWindowSolicitacoes.show();
    oGridSolicitacoes              = new DBGrid('Solicitacoes');
    oGridSolicitacoes.nameInstance = 'oGridSolicitacoes';
    oGridSolicitacoes.setCellWidth(new Array( '25px',
                                              '35px',
                                              '50px',
                                              '150px'
                                             ));

    oGridSolicitacoes.setCellAlign(new Array( 'right'  ,
                                              'center'  ,
                                              'left',
                                              'left'
                                             ));

    oGridSolicitacoes.setHeader(new Array( 'Solicita��o',
                                           'Data de Emiss�o',
                                           'Dota��es',
                                           'Resumo'
                                          ));

    oGridSolicitacoes.setHeight(250);
    oGridSolicitacoes.show($('ctnGridSolicitacoes'));
    me.pesquisarSolicitacoesComDotacaoAnoAnterior(aSolicitacoes);
  }

   this.pesquisarSolicitacoesComDotacaoAnoAnterior = function (aSolicitacoes) {

    var aSolicitacoes =  aSolicitacoes
    var msgDiv        = "Carregando Lista de Solicita��es. \n Aguarde ...";
    js_divCarregando(msgDiv,'msgBox');

    var oParam     = new Object();
    oParam.exec    = 'pesquisarSolicitacoes';

    oParam.filtros                = new Object();
    oParam.filtros.aSolicitacoes  = aSolicitacoes;

    var  aAjax = new Ajax.Request('com4_alteradotacaosolicitacao.RPC.php',
                             {method:'post',
                              parameters: 'json='+Object.toJSON(oParam),
                              onComplete: me.retornoPesquisaSolicitacoes
                             });

  }

  this.retornoPesquisaSolicitacoes = function(oAjax) {

    js_removeObj('msgBox');
    var oRetorno      = eval("("+oAjax.responseText+")");

    if (oRetorno.aSolicitacoes.length == 0) {
      alert("N�o existem solicita��es para os filtros.");
      return false;
    }

    oGridSolicitacoes.clearAll(true);
    oRetorno.aSolicitacoes.each( function (oDado, iInd) {

        aRow     = new Array();
        aRow[0]  = oDado.solicitacao;
        aRow[1]  = oDado.dtEmis;
        aRow[2]  = oDado.dotacoes;
        aRow[3]  = oDado.resumo.urlDecode();
        oGridSolicitacoes.addRow(aRow);
        oGridSolicitacoes.aRows[iInd].sEvents += "ondblclick='"+me.sInstancia+".mostraDotacoes("+oDado.solicitacao+")'";
     });
    oGridSolicitacoes.renderRows();
  }

  this.mostraDotacoes = function(iCodigoSolicitacao) {

    oViewSolicitacaoDotacao = new DBViewSolicitacaoDotacao(iCodigoSolicitacao, "oViewSolicitacaoDotacao");
    oViewSolicitacaoDotacao.getDotacoes();

  }

}


/**
 *  Classe DBToolTip
 *  Cria um tooltip quando o mouse estiver sob um elemento
 *
 *  @param {string} sTexto
 *  @param {object} oSender
 */
DBToolTip = function (sTexto, oSender) {

  var me     = this;
  me.sTexto  = sTexto;
  me.oSender = oSender

  me.oElementDiv = document.createElement('div');
  me.oElementDiv.id                    = "DBToolTip";
  me.oElementDiv.style.position        = "absolute";
  me.oElementDiv.style.top             = "200px";
  me.oElementDiv.style.left            = "15px";
  me.oElementDiv.style.border          = "1px solid black";
  me.oElementDiv.style.width           = "300px";
  me.oElementDiv.style.textAlign       = "left";
  me.oElementDiv.style.padding         = "3px";
  me.oElementDiv.style.backgroundColor = "#FFFFCC";
  me.oElementDiv.style.display         = "none";
  me.oElementDiv.style.zIndex          = "10000";

  document.body.appendChild(me.oElementDiv);

  this.setText = function(sTexto) {

    me.sTexto  = sTexto;
    return me;
  }

  this.show = function(position) {

    me.oElementDiv.innerHTML     = me.sTexto;
    me.oElementDiv.style.display = "inline";
    me.setPosition(position);
    return me;
  }

  this.setElement =function(oElement) {

    me.oSender =  oElement;
    return me;
  }
  this.hide = function() {

    me.oElementDiv.style.display = 'none';
    me.oElementDiv.innerHTML     = '';
  }

  this.destroy = function() {
    me.oElementDiv.remove();
  }

  /**
   * Posiciona o elemento
   */
  me.setPosition = function(position) {
    if (position == null) {
      position = 'b';
    }
    var el =  me.oSender;
    var x = 0;
    var y = el.offsetHeight;
    while (el.offsetParent && el.tagName.toUpperCase() != 'BODY') {

     x += el.offsetLeft;
     y += el.offsetTop;
     el = el.offsetParent;
   }

   x += el.offsetLeft;
   y += el.offsetTop;
   switch (position) {

     case 'l':
       x -= new Number(me.oElementDiv.clientWidth);
       break;
   }
   me.oElementDiv.style.top     = y - $('gridItensLicitacaobody').scrollTop;
   me.oElementDiv.style.left    = x;
   }

}

var oScriptDBViewAlteracaoDotacao = document.createElement("script");
oScriptDBViewAlteracaoDotacao.src = "scripts/classes/DBViewSolicitacaoDotacao.classe.js";
var aHead = document.getElementsByTagName("head")[0];
aHead.appendChild(oScriptDBViewAlteracaoDotacao);

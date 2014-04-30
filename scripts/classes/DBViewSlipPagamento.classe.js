/**
 * Componente para o pagamento de um slip
 * @param sNomeInstancia     - Nome da Instancia que esta sendo utilizada
 * @param iTipoTransferencia - Codigo da transferencia (tabela: sliptipooperacao)
 * @param oDivDestino        - Objeto onde este objeto (DBViewSlipPagamento) sera adicionado
 */
DBViewSlipPagamento = function(sNomeInstancia, iTipoTransferencia, iOpcao, oDivDestino, lInscricaoBaixa, lReadOnly) {
  
  var me                = this;
  me.sNomeInstancia     = sNomeInstancia;
  me.sTipoTransferencia = "";
  me.iTipoTransferencia = iTipoTransferencia;
  me.sUrlRpc            = "cai4_transferencia.RPC.php";
  me.oDivDestino        = oDivDestino;
  me.iCodigoSlip        = null;
  me.lUsaPCASP          = false;
  me.iTipoInclusao      = 0;
  me.lImportacao        = false;
  me.iInscricaoPassivo  = null;
  
  if (lReadOnly == null || lReadOnly == 'undefined') {
    me.lReadOnly = false;
  } else {
    me.lReadOnly = lReadOnly;
  }
  
  /*
   * Array contendo as contas para credito/debito
   */
  me.aContasCredito = new Array();
  me.aContasDebito  = new Array();
  
  me.lContaCredito  = false;
  me.lContaDebito   = false;
  
  /*
   * Parametros que serao executados no comando ajax
   */
  me.sParamContaCredito = "";
  me.sParamContaDebito  = "";
  
  /*
   * 1 para inclusao
   * 2 para estorno
   */
  me.iOpcao = iOpcao;
  
  /**
   * Define o tipo de transferencia
   */
  
  switch (iTipoTransferencia) {

    /*
     * Transferencia Financeira
     */
    case 1: // Pagamento
    case 2: // Estorno Pagamento
      
      me.iTipoInclusao         = 1;
      me.sParamContaCredito    = "getContasSaltes";

      me.sPesquisaContaCredito = "Saltes";
      me.sPesquisaContaDebito  = "EventoContabil";

      me.sParamContaDebito     = "getContaEventoContabil";
      me.sTipoTransferencia    = "Concessão de Transferência Financeira";
      me.lContaDebito          = true;
      
    break;
    
    case 3: // Recebimento
    case 4: // Estorno Recebimento
      
      me.iTipoInclusao         = 3;
      me.lContaCredito         = true;
      me.sParamContaCredito    = "getContaEventoContabil";

      me.sPesquisaContaCredito = "EventoContabil";
      me.sPesquisaContaDebito  = "Saltes";

      me.sParamContaDebito     = "getContasSaltes";
      me.sTipoTransferencia    = "Recebimento Transferência Financeira";
    break;
    
    /*
     * Transferencia Bancaria
     */
    case 5: // Inclusao
    case 6: // Estorno
      
      me.iTipoInclusao         = 5;
      me.sParamContaCredito    = "getContasSaltes";

      me.sPesquisaContaCredito = "Saltes";
      me.sPesquisaContaDebito  = "Saltes";

      me.sParamContaDebito     = "getContasSaltes";
      me.sTipoTransferencia    = "Transferência Bancária";
    break;
    
    /*
     * Caucao Recebimento 
     */
    case 7: // inclusao
    case 8: // estorno
                                                                     
      me.iTipoInclusao         = 7;
      me.lContaCredito         = true;
      // buscara somente as contas credito do evento
      me.sParamContaCredito    = "getContaEventoContabil";

      me.sPesquisaContaCredito = "EventoContabil";
      me.sPesquisaContaDebito  = "Saltes";

      me.sParamContaDebito     = "getContasSaltes";
      me.sTipoTransferencia    = "Recebimento de Caução";
    break;
    
    /*
     * Caucao Devolucao
     */
    case 9: // inclusao
    case 10: // estorno

      me.iTipoInclusao         = 9;
      me.lContaDebito          = true;
      me.sParamContaCredito    = "getContasSaltes";

      me.sPesquisaContaCredito = "Saltes";
      me.sPesquisaContaDebito  = "EventoContabil";

      me.sParamContaDebito     = "getContaEventoContabil";
      me.sTipoTransferencia    = "Devolução de Caução";
    break;
    
    /*
     * Dep. Diversas Origens
     */
    case 11: // Recebimento
    case 12: // Estorno Recebimento
      
      me.iTipoInclusao          = 11;
      me.lContaCredito          = true;
      me.sTipoTransferencia     = "Recebimento de Depósito de Diversas Origens";

      me.sPesquisaContaCredito = "EventoContabil";
      me.sPesquisaContaDebito  = "Saltes";

      me.sParamContaCredito    = "getContaEventoContabil";
      me.sParamContaDebito     = "getContasSaltes";
    break;
    
    case 13: // Pagamento
    case 14: // Estorno Pagamento
      
      me.iTipoInclusao      = 13;
      me.lContaDebito       = true;
      me.sTipoTransferencia = "Pagamento de Depósito de Diversas Origens";
      
      if (me.iInscricaoPassivo != null) {
        me.sTipoTransferencia = "Baixa de Inscrição";
      }
      
      me.sPesquisaContaCredito = "Saltes";
      me.sPesquisaContaDebito  = "EventoContabil";

      me.sParamContaCredito    = "getContasSaltes";
      me.sParamContaDebito     = "getContaEventoContabil";

    break;
  }

  me.oTxtCodigoSlip                          = new DBTextField('oTxtCodigoSlip', me.sNomeInstancia+'.oTxtCodigoSlip', '', 8);
  me.oTxtInstituicaoOrigemCodigo             = new DBTextField('oTxtInstituicaoOrigemCodigo', me.sNomeInstancia+'.oTxtInstituicaoOrigemCodigo', '', 8);
  me.oTxtInstituicaoOrigemCodigo.setReadOnly(true);
  me.oTxtDescricaoInstituicaoOrigem          = new DBTextField('oTxtDescricaoInstituicaoOrigem', me.sNomeInstancia+'.oTxtDescricaoInstituicaoOrigem', '', 56);
  me.oTxtInstituicaoDestinoCodigo            = new DBTextField('oTxtInstituicaoDestinoCodigo', me.sNomeInstancia+'.oTxtInstituicaoDestinoCodigo', '', 8);
  me.oTxtInstituicaoDestinoCodigo.addEvent('onChange', ";"+me.sNomeInstancia+".pesquisaInstituicaoDestino(false);");
  me.oTxtDescricaoInstituicaoDestino         = new DBTextField('sDescricaoInstituicaoDestino', me.sNomeInstancia+'.oTxtDescricaoInstituicaoDestino', '', 56);
  me.oTxtFavorecidoInputCodigo               = new DBTextField('oTxtFavorecidoInputCodigo', me.sNomeInstancia+'.oTxtFavorecidoInputCodigo', '', 8);
  me.oTxtFavorecidoInputCodigo.addEvent('onChange', ";"+me.sNomeInstancia+".pesquisaFavorecido(false);");
  me.oTxtFavorecidoInputDescricao            = new DBTextField('oTxtFavorecidoInputDescricao', me.sNomeInstancia+'.oTxtFavorecidoInputDescricao', '', 56);
  me.oTxtCaracteristicaDebitoInputCodigo     = new DBTextField('oTxtCaracteristicaDebitoInputCodigo', me.sNomeInstancia+'.oTxtCaracteristicaDebitoInputCodigo', '', 8);
  me.oTxtCaracteristicaDebitoInputCodigo.addEvent('onChange', ";"+me.sNomeInstancia+".pesquisaCaracteristicaPeculiarDebito(false);");
  me.oTxtCaracteristicaDebitoInputDescricao  = new DBTextField('oTxtCaracteristicaDebitoInputDescricao', me.sNomeInstancia+'.oTxtCaracteristicaDebitoInputDescricao', '', 56);
  me.oTxtCaracteristicaCreditoInputCodigo    = new DBTextField('oTxtCaracteristicaCreditoInputCodigo', me.sNomeInstancia+'.oTxtCaracteristicaCreditoInputCodigo', '', 8);
  me.oTxtCaracteristicaCreditoInputCodigo.addEvent('onChange', ";"+me.sNomeInstancia+".pesquisaCaracteristicaPeculiarCredito(false);");
  me.oTxtCaracteristicaCreditoInputDescricao = new DBTextField('oTxtCaracteristicaCreditoInputDescricao', me.sNomeInstancia+'.oTxtCaracteristicaCreditoInputDescricao', '', 56);

  /**
   * Inputs para conta crédito e débito
   */
  me.oTxtContaCreditoCodigo                  = new DBTextField("oTxtContaCreditoCodigo",    me.sNomeInstancia + ".oTxtContaCreditoCodigo",    "", 8);
  me.oTxtContaCreditoCodigo.addEvent("onChange", ";" + me.sNomeInstancia + ".pesquisaConta" + me.sPesquisaContaCredito + "(false, true);");
  me.oTxtContaCreditoDescricao               = new DBTextField("oTxtContaCreditoDescricao", me.sNomeInstancia + ".oTxtContaCreditoDescricao", "", 56);

  me.oTxtContaDebitoCodigo                   = new DBTextField("oTxtContaDebitoCodigo",     me.sNomeInstancia + ".oTxtContaDebitoCodigo",     "", 8);
  me.oTxtContaDebitoCodigo.addEvent("onChange", ";" + me.sNomeInstancia + ".pesquisaConta" + me.sPesquisaContaDebito + "(false, false);");
  me.oTxtContaDebitoDescricao                = new DBTextField("oTxtContaDebitoDescricao",  me.sNomeInstancia + ".oTxtContaDebitoDescricao",  "", 56);

  /*me.oComboContaDebito                       = new DBComboBox("comboBoxDebito", me.sNomeInstancia+".oComboContaDebito", new Array(), "500px");
  me.oComboContaCredito                      = new DBComboBox("comboBoxCredito", me.sNomeInstancia+".oComboContaCredito", new Array(), "500px");*/

  me.oTxtHistoricoInputCodigo                = new DBTextField('oTxtHistoricoInputCodigo', me.sNomeInstancia+'.oTxtHistoricoInputCodigo', '', 8);
  me.oTxtHistoricoInputCodigo.addEvent('onChange', ";"+me.sNomeInstancia+".pesquisaHistorico(false);");
  me.oTxtHistoricoInputDescricao             = new DBTextField('oTxtHistoricoInputDescricao', me.sNomeInstancia+'.oTxtHistoricoInputDescricao', '', 56);""
  me.oTxtValorInput                          = new DBTextField('oTxtValorInput', me.sNomeInstancia+'.oTxtValorInput', '', 8);
  me.oTxtValorInput.addEvent("onKeyPress", "return js_teclas(event,this)");
  
  
  
  /**
   * Seta se o campo será readonly
   */
  
  
  me.oTxtInstituicaoOrigemCodigo.setReadOnly(me.lReadOnly);
  me.oTxtDescricaoInstituicaoOrigem.setReadOnly(me.lReadOnly);
  me.oTxtInstituicaoDestinoCodigo.setReadOnly(me.lReadOnly);
  me.oTxtDescricaoInstituicaoDestino.setReadOnly(me.lReadOnly);
  me.oTxtFavorecidoInputCodigo.setReadOnly(me.lReadOnly);
  me.oTxtFavorecidoInputDescricao.setReadOnly(me.lReadOnly);
  me.oTxtCaracteristicaDebitoInputDescricao.setReadOnly(me.lReadOnly);
  me.oTxtCaracteristicaDebitoInputCodigo.setReadOnly(me.lReadOnly);
  me.oTxtCaracteristicaCreditoInputCodigo.setReadOnly(me.lReadOnly);
  me.oTxtCaracteristicaCreditoInputDescricao.setReadOnly(me.lReadOnly);
  me.oTxtContaCreditoDescricao.setReadOnly(me.lReadOnly);
  me.oTxtContaCreditoCodigo.setReadOnly(me.lReadOnly);
  me.oTxtContaDebitoCodigo.setReadOnly(me.lReadOnly);
  me.oTxtHistoricoInputCodigo.setReadOnly(me.lReadOnly);
  me.oTxtHistoricoInputDescricao.setReadOnly(me.lReadOnly);
  me.oTxtValorInput.setReadOnly(me.lReadOnly);
  /*
   * Textarea das observacoes 
   */
  me.oTxtObservacaoInput              = document.createElement('textarea');
  me.oTxtObservacaoInput.name         = "observacao_"+me.sNomeInstancia;
  me.oTxtObservacaoInput.id           = "observacao_"+me.sNomeInstancia;
  me.oTxtObservacaoInput.style.width  = "100%";
  me.oTxtObservacaoInput.style.height = "100px";
  me.oTxtObservacaoInput.disabled     = me.lReadOnly;
  if (me.lReadOnly) {
    me.oTxtObservacaoInput.style.backgroundColor = "#DEB887";
    me.oTxtObservacaoInput.style.color           = "#000";
  }
  
  
  /*
   * Textarea das observacoes 
   */
  me.oTxtMotivoAnulacaoInput              = document.createElement('textarea');
  me.oTxtMotivoAnulacaoInput.name         = "motivoanulacao_"+me.sNomeInstancia;
  me.oTxtMotivoAnulacaoInput.id           = "motivoanulacao_"+me.sNomeInstancia;
  me.oTxtMotivoAnulacaoInput.style.width  = "100%";
  me.oTxtMotivoAnulacaoInput.style.height = "100px";
  
  /*
   * Objeto Botao
   */
  me.oButtonSalvar                   = document.createElement('input');
  me.oButtonSalvar.type              = "button";
  me.oButtonSalvar.value             = "Salvar";
  me.oButtonSalvar.id                = "btnSalvar";
  me.oButtonSalvar.name              = "btnSalvar";
  me.oButtonSalvar.style.marginTop   = "10px";
  
  me.oButtonEstornar                 = document.createElement('input');
  me.oButtonEstornar.type            = "button";
  me.oButtonEstornar.value           = "Estornar";
  me.oButtonEstornar.id              = "btnEstornar";
  me.oButtonEstornar.name            = "btnEstornar";
  me.oButtonEstornar.style.marginTop = "10px";
  
  me.oButtonImportar                 = document.createElement('input');
  me.oButtonImportar.type            = "button";
  me.oButtonImportar.value           = "Importar";
  me.oButtonImportar.id              = "btnImportar";
  me.oButtonImportar.name            = "btnImportar";
  me.oButtonImportar.style.marginTop = "10px";
  
  /**
   * Cria botão para gerar nova baixa
   */
  me.oButtonNovaBaixa                   = document.createElement('input');
  me.oButtonNovaBaixa.type              = "button";
  me.oButtonNovaBaixa.value             = "Nova Baixa";
  me.oButtonNovaBaixa.id                = "btnNovaBaixa";
  me.oButtonNovaBaixa.name              = "btnNovaBaixa";
  me.oButtonNovaBaixa.style.marginTop   = "10px";
  
  
  
  me.setPagamentoEmpenhoPassivo = function (iInscricao) {
  
    me.iInscricaoPassivo               = iInscricao;
    me.oTxtProcessoAdministrativoInput = new DBTextField('oTxtProcessoAdministrativoInput', me.sNomeInstancia+'.oTxtProcessoAdministrativoInput', '', 50);
    me.sParamContaDebito               = "getContaFavorecido";
    me.sTipoTransferencia              = "Baixa de Inscrição";
    
    me.oButtonNovaBaixa.onclick        = function (){
      window.location = "con4_baixainscricaopassivopagamento011.php";
    };
  };

  /**
   * Monta o componente e apresenta no objeto passado por parametro
   */
  me.show = function () {
    
    var oFieldset         = document.createElement("fieldset");
    oFieldset.style.width = "670px";
    var oLegend           = document.createElement("legend");
    oLegend.innerHTML     = "<b>"+me.sTipoTransferencia+"</b>";
    oLegend.id            = "legend_"+me.sNomeInstancia;
    oFieldset.id          = "fieldsetPrincipal_"+me.sNomeInstancia;
    oFieldset.appendChild(oLegend);
    
    var oTabela         = document.createElement("table");
    oTabela.id          = "table_"+me.sNomeInstancia;
    oTabela.style.width = "100%";
    
    /*
     * Table Row - Codigo
     */
    var oRowCodigo               = oTabela.insertRow(0);
    var oCellCodigoLabel         = oRowCodigo.insertCell(0);
    
    if (me.iOpcao == 1) {
      oCellCodigoLabel.innerHTML   = "<b>Código:</b>";
    } else if (me.iOpcao == 2) {
      oCellCodigoLabel.innerHTML   = "<b><a href='#' onclick='"+me.sNomeInstancia+".pesquisaCodigoSlip(true);'>Código:</a></b>";
    }
    
    oCellCodigoLabel.style.width = "150px";
    var oCellCodigoInput         = oRowCodigo.insertCell(1);
    oCellCodigoInput.style.width = "50px";
    oCellCodigoInput.id          = "td_codigo_"+me.sNomeInstancia;
    me.oTxtCodigoSlip.setReadOnly(true);
    me.oTxtCodigoSlip.show(oCellCodigoInput);
    
    /*
     * Instituicao Origem 
     */
    var oRowInstituicaoOrigem                = oTabela.insertRow(1);
    var oCellInstituicaoOrigemLabel          = oRowInstituicaoOrigem.insertCell(0);
    oCellInstituicaoOrigemLabel.innerHTML    = "<b>Instituição Origem:</b>";
    var oCellInstituicaoOrigemInput          = oRowInstituicaoOrigem.insertCell(1);
    oCellInstituicaoOrigemInput.id           = "td_InstituicaoOrigem_"+me.sNomeInstancia;
    var oCellDescricaoInstituicaoOrigemInput = oRowInstituicaoOrigem.insertCell(2);
    oCellDescricaoInstituicaoOrigemInput.id  = "td_DescricaoInstituicaoOrigem_"+me.sNomeInstancia;
    me.oTxtInstituicaoOrigemCodigo.show(oCellInstituicaoOrigemInput);
    me.oTxtDescricaoInstituicaoOrigem.setReadOnly(true);
    me.oTxtDescricaoInstituicaoOrigem.show(oCellDescricaoInstituicaoOrigemInput);
    
    /**
     * Instituicao destino
     */
    var oRowInstituicaoDestino                = oTabela.insertRow(2);
    oRowInstituicaoDestino.id                 = "tr_InstituicaoDestino_"+me.sNomeInstancia;
    var oCellInstituicaoDestinoLabel          = oRowInstituicaoDestino.insertCell(0);
    oCellInstituicaoDestinoLabel.id           = "td_instituicaodestino_"+me.sNomeInstancia;
    oCellInstituicaoDestinoLabel.innerHTML    = "<b><a href='#' onclick='"+me.sNomeInstancia+".pesquisaInstituicaoDestino(true);'>Instituição Destino:</a></b>";
    
    var oCellInstituicaoDestinoInputCodigo          = oRowInstituicaoDestino.insertCell(1);
    me.oTxtInstituicaoDestinoCodigo.show(oCellInstituicaoDestinoInputCodigo);
    
    var oCellDescricaoInstituicaoDestinoInput = oRowInstituicaoDestino.insertCell(2);
    oCellDescricaoInstituicaoDestinoInput.id  = "td_DescricaoInstituicaoDestino_"+me.sNomeInstancia;
    me.oTxtDescricaoInstituicaoDestino.setReadOnly(true);
    me.oTxtDescricaoInstituicaoDestino.show(oCellDescricaoInstituicaoDestinoInput);
 
    /**
     * Favorecido
     */
    var oRowFavorecido             = oTabela.insertRow(3);
    oRowFavorecido.id              = "tr_favorecido_"+me.sNomeInstancia;
    var oCellFavorecidoLabel       = oRowFavorecido.insertCell(0);
    oCellFavorecidoLabel.id        = "td_favorecido_"+me.sNomeInstancia;

    if (me.iOpcao == 2) {
      oCellFavorecidoLabel.innerHTML = "<b>Favorecido:</b>";
    } else {
      oCellFavorecidoLabel.innerHTML = "<b><a href='#' onclick='"+me.sNomeInstancia+".pesquisaFavorecido(true);'>Favorecido:</a></b>";
    }
    
    var oCellFavorecidoInputCodigo = oRowFavorecido.insertCell(1);
    me.oTxtFavorecidoInputCodigo.show(oCellFavorecidoInputCodigo);
    
    var oCellFavorecidoInputDescricao = oRowFavorecido.insertCell(2);
    me.oTxtFavorecidoInputDescricao.setReadOnly(true);
    me.oTxtFavorecidoInputDescricao.show(oCellFavorecidoInputDescricao);

    /**
     * Label Conta Debito 
     */
    var oRowContaDebito             = oTabela.insertRow(4);
    var oCellContaDebitoLabel       = oRowContaDebito.insertCell(0);
    oCellContaDebitoLabel.id = "labelContaDebito";
    oCellContaDebitoLabel.innerHTML = "<b><a href='#' onclick='"+me.sNomeInstancia+".pesquisaConta"+me.sPesquisaContaDebito+"(true, false);'>Conta Débito:</a></b>";

    /**
     * Input de codigo para a conta débito
     */
    var oCellContaDebitoCodigo = oRowContaDebito.insertCell(1);
    oCellContaDebitoCodigo.id  = "td_contadebito_"+me.sNomeInstancia;
    me.oTxtContaDebitoCodigo.show(oCellContaDebitoCodigo); 

    /**
     * Input de descrição para a conta débito
     */
    var oCellContaDebitoDescricao = oRowContaDebito.insertCell(2);
    oCellContaDebitoDescricao.id  = "td_contadebito_"+me.sNomeInstancia;
    me.oTxtContaDebitoDescricao.setReadOnly(true);
    me.oTxtContaDebitoDescricao.show(oCellContaDebitoDescricao);

    
    /**
     * Label Caracteristica Peculiar
     */
    var oRowCaracteristica                   = oTabela.insertRow(5);
    var oCellCaracteristicaDebitoLabel       = oRowCaracteristica.insertCell(0);
    oCellCaracteristicaDebitoLabel.id        = "td_cpca_contadebito_"+me.sNomeInstancia;
    oCellCaracteristicaDebitoLabel.innerHTML = "<b><a href='#' onclick='"+me.sNomeInstancia+".pesquisaCaracteristicaPeculiarDebito(true);'>C.Peculiar / C.Aplicação:</a></b>";
    
    /**
     * Codigo Caracteristica Peculiar
     */
    var oCellCaracteristicaDebitoInputCodigo = oRowCaracteristica.insertCell(1);
    me.oTxtCaracteristicaDebitoInputCodigo.show(oCellCaracteristicaDebitoInputCodigo);

    /**
     * Descricao Caracteristica Peculiar
     */
    var oCellCaracteristicaDebitoInputDescricao = oRowCaracteristica.insertCell(2);
    me.oTxtCaracteristicaDebitoInputDescricao.setReadOnly(true);
    me.oTxtCaracteristicaDebitoInputDescricao.show(oCellCaracteristicaDebitoInputDescricao);
    
    /**
     * Label Conta Credito 
     */
    var oRowContaCredito             = oTabela.insertRow(6);
    var oCellContaCreditoLabel       = oRowContaCredito.insertCell(0);
    oCellContaCreditoLabel.innerHTML = "<b><a href='#' onclick='"+me.sNomeInstancia+".pesquisaConta"+me.sPesquisaContaCredito+"(true,true);'>Conta Crédito:</a></b>"; 
    oCellContaCreditoLabel.id = "labelContaCredito";

    /**
     * Input de codigo para a conta credito
     */
    var oCellContaCreditoCodigo = oRowContaCredito.insertCell(1);
    oCellContaCreditoCodigo.id  = "td_contaCredito_"+me.sNomeInstancia;
    me.oTxtContaCreditoCodigo.show(oCellContaCreditoCodigo); 

    /**
     * Input de descrição para a conta credito
     */
    var oCellContaCreditoDescricao = oRowContaCredito.insertCell(2);
    oCellContaCreditoDescricao.id  = "td_contaCredito_"+me.sNomeInstancia;
    me.oTxtContaCreditoDescricao.setReadOnly(true);
    me.oTxtContaCreditoDescricao.show(oCellContaCreditoDescricao);
    
    /**
     * Caracteristica peculiar conta credito
     */
    var oRowCaracteristicaContaCredito        = oTabela.insertRow(7);
    var oCellCaracteristicaCreditoLabel       = oRowCaracteristicaContaCredito.insertCell(0);
    oCellCaracteristicaCreditoLabel.id        = "td_cpca_contacredito_"+me.sNomeInstancia;
    oCellCaracteristicaCreditoLabel.innerHTML = "<b><a href='#' onclick='"+me.sNomeInstancia+".pesquisaCaracteristicaPeculiarCredito(true);'>C.Peculiar / C.Aplicação:</a></b>";
    
    /**
     * Codigo Caracteristica Peculiar
     */
    var oCellCaracteristicaCreditoInputCodigo = oRowCaracteristicaContaCredito.insertCell(1);
    me.oTxtCaracteristicaCreditoInputCodigo.show(oCellCaracteristicaCreditoInputCodigo);
    
    /**
     * Descricao Caracteristica Peculiar
     */
    var oCellCaracteristicaCreditoInputDescricao = oRowCaracteristicaContaCredito.insertCell(2);
    me.oTxtCaracteristicaCreditoInputDescricao.setReadOnly(true);
    me.oTxtCaracteristicaCreditoInputDescricao.show(oCellCaracteristicaCreditoInputDescricao);
    
    /**
     * Label Historico
     */
    var oRowHistorico             = oTabela.insertRow(8);
    var oCellHistoricoLabel       = oRowHistorico.insertCell(0);
    oCellHistoricoLabel.id        = "td_historico_"+me.sNomeInstancia;
    oCellHistoricoLabel.innerHTML = "<b><a href='#' onclick='"+me.sNomeInstancia+".pesquisaHistorico(true);'>Histórico:</a></b>";
    
    /**
     * Codigo Historico
     */
    var oCellHistoricoInputCodigo = oRowHistorico.insertCell(1);
    me.oTxtHistoricoInputCodigo.show(oCellHistoricoInputCodigo);
    
    /**
     * Descricao Historico
     */
    var oCellHistoricoInputDescricao = oRowHistorico.insertCell(2);
    me.oTxtHistoricoInputDescricao.setReadOnly(true);
    me.oTxtHistoricoInputDescricao.show(oCellHistoricoInputDescricao);

    /**
     * Label Valor
     */
    var oRowValor             = oTabela.insertRow(9);
    var oCellValorLabel       = oRowValor.insertCell(0);
    oCellValorLabel.innerHTML = "<b>Valor:</b>";
    
    var oCellValorInput       = oRowValor.insertCell(1);
    oCellValorInput.colSpan   = "2";
    me.oTxtValorInput.show(oCellValorInput);
    
    /**
     * Label Processo Administrativo
     */
    if (me.iInscricaoPassivo != null) {
      
      var oRowProcesso             = oTabela.insertRow(10);
      var oCellRowProcesso         = oRowProcesso.insertCell(0);
      oCellRowProcesso.innerHTML   = "<b>Processo Administrativo:</b>";
      
      var oCellProcessoInput       = oRowProcesso.insertCell(1);
      oCellProcessoInput.colSpan   = "2";
      me.oTxtProcessoAdministrativoInput.show(oCellProcessoInput);
    }
    
    /**
     * Fieldset Observacao
     */
    var oFieldsetObservacao       = document.createElement('fieldset');
    var oLegendObservacao         = document.createElement('legend');
    oLegendObservacao.innerHTML   = "<b>Observação:</b>";
    oFieldsetObservacao.appendChild(oLegendObservacao);
    oFieldsetObservacao.appendChild(me.oTxtObservacaoInput);
    
    /**
     * Fieldset Observacao
     */
    var oFieldsetMotivoEstorno    = document.createElement('fieldset');
    oFieldsetMotivoEstorno.id     = "fieldset_motivo_anulacao_"+me.sNomeInstancia;
    var oLegendObservacao         = document.createElement('legend');
    oLegendObservacao.innerHTML   = "<b>Motivo:</b>";
    oFieldsetMotivoEstorno.appendChild(oLegendObservacao);
    oFieldsetMotivoEstorno.appendChild(me.oTxtMotivoAnulacaoInput);
    
    /**
     * Executamos o APPEND ao objeto destino
     */
    oFieldset.appendChild(oTabela);
    oFieldset.appendChild(oFieldsetObservacao);
    oFieldset.appendChild(oFieldsetMotivoEstorno);
    me.oDivDestino.appendChild(oFieldset);
    me.oDivDestino.appendChild(me.oButtonSalvar);
    me.oDivDestino.appendChild(me.oButtonEstornar);
    me.oDivDestino.appendChild(me.oButtonImportar);

    if (me.iOpcao == 1) {
      
      me.oButtonSalvar.style.display    = '';
      me.oButtonEstornar.style.display  = 'none';
      $("fieldset_motivo_anulacao_"+me.sNomeInstancia).style.display = 'none';
    } else {
      
      me.oButtonSalvar.style.display    = 'none';
      me.oButtonImportar.style.display  = 'none';
      me.oButtonEstornar.style.display  = '';
      $("fieldset_motivo_anulacao_"+me.sNomeInstancia).style.display = '';
    }
    
    if (me.iTipoTransferencia != 1) {
      $("tr_InstituicaoDestino_"+me.sNomeInstancia).style.display = 'none';
    }

    if (me.iTipoTransferencia == 5 || me.iTipoTransferencia == 6) {
      oRowFavorecido.style.display = 'none';
    }
    
    if (me.iInscricaoPassivo != null) {
      
      me.oDivDestino.appendChild(me.oButtonNovaBaixa);
      me.oButtonImportar.style.display  = 'none';
      oLegend.innerHTML = "<b>Baixa por Pagamento</b>";
      
      js_divCarregando("Aguarde, buscando os dados da inscrição...", "msgBox");
      
      var oParam              = new Object();
      oParam.exec             = "getDadosInscricao";
      oParam.iCodigoInscricao = me.iInscricaoPassivo;

      /*
       * Buscamos os valores da inscrição
       */
      var oAjax = new Ajax.Request(me.sUrlRpc,
                                   {method: 'post',
                                    parameters: 'json='+Object.toJSON(oParam),
                                    onComplete: function (oAjax) {
                                      
                                      js_removeObj("msgBox");
                                      var oRetorno = eval("("+oAjax.responseText+")");
                                      
                                      $("td_favorecido_"+me.sNomeInstancia).innerHTML = "<b>Favorecido:</b>";
                                      
                                      me.oTxtFavorecidoInputCodigo.setValue(oRetorno.iCgmFavorecido);
                                      me.oTxtFavorecidoInputDescricao.setValue(oRetorno.sNomeFavorecido.urlDecode());

                                      me.oTxtContaDebitoCodigo.setValue(oRetorno.iContaDebito)
                                      me.oTxtContaDebitoDescricao.setValue(oRetorno.sDescrContaDebito.urlDecode());
                                      
                                      me.oTxtValorInput.setValue(js_formatar(oRetorno.nValorTotalInscricao, 'f'));
                                      
                                      me.oTxtValorInput.setReadOnly(true);
                                    }
                                   });
    }
  };

  /**
   * Método que  ajusta tela para baixa de pagamento
   */
  me.ajustaTelaBaixaPagamento = function() {

    me.oTxtFavorecidoInputCodigo.setReadOnly(true);

    me.oTxtContaDebitoCodigo.setReadOnly(true);
  
    me.oTxtCaracteristicaCreditoInputCodigo.setValue("000");
    me.pesquisaCaracteristicaPeculiarCredito(false);
    
    me.oTxtCaracteristicaDebitoInputCodigo.setValue("000");
    me.pesquisaCaracteristicaPeculiarDebito(false);
  };

  /**
   * Verifica se a conta crédito e conta débito são iguais
   * Devem ser diferentes
   * @return boolean
   */ 
  me.validarContaCreditoDebito = function() {

    if (me.oTxtContaCreditoCodigo.getValue() == me.oTxtContaDebitoCodigo.getValue()) {

      alert("Conta débito e conta crédito devem ser diferentes.");
      return false;
    }
    return true;
  }; 

  
  /**
   * Salva os dados de uma transferencia bancaria
   */
  me.oButtonSalvar.observe('click', function() {

    if (!me.validarInstituicao()) {
      return false;
    }

    if (!me.validarContaCreditoDebito()) {
      return false;
    }
    
    if (me.iTipoTransferencia != 5 && me.iTipoTransferencia != 6) {
      
      if (me.oTxtFavorecidoInputCodigo.getValue() == "") {
        
        alert("Favorecido não informado.");
        return false;
      }
    }
    
    if (me.iTipoTransferencia == 1) {
      if (me.oTxtInstituicaoDestinoCodigo.getValue() == "") {
        alert("Informe a instituição de destino.");
        return false;
      }
    }
    
    if (me.oTxtHistoricoInputCodigo.getValue() == "") {
      
      alert("Informe o histórico para a transferência.");
      return false;
    }
    
    if (me.oTxtValorInput.getValue() == "" || me.oTxtValorInput.getValue() <= 0) {
      
      alert("O campo valor não pode ser vazio nem ser negativo.");
      return false;
    }
    
    
    var aDadosValor = me.oTxtValorInput.getValue().split(',');
    
    if (aDadosValor.length > 2) {
      
      alert("Valor digitado inválido. Verifique.");
      return false;
    }
    
    if (me.getObservacao() == "") {
      
      alert("Campo observação é obrigatório.");
      return false;
    }
    
    if (me.iInscricaoPassivo != null && me.oTxtProcessoAdministrativoInput.getValue() == "") {
      
      alert("Campo processo adminstrativo é obrigatório.");
      return false;
    }
    
    
    js_divCarregando("Aguarde, salvando dados da transferência...", "msgBox");
    var oParam                            = new Object();
    oParam.exec                           = "salvarSlip";
    oParam.k17_codigo                     = me.oTxtCodigoSlip.getValue();
    oParam.iCodigoTipoOperacao            = me.iTipoTransferencia;
    oParam.k17_debito                     = me.oTxtContaDebitoCodigo.getValue();
    oParam.k17_credito                    = me.oTxtContaCreditoCodigo.getValue();
    oParam.k17_valor                      = me.oTxtValorInput.getValue();
    oParam.k17_hist                       = me.oTxtHistoricoInputCodigo.getValue();
    oParam.iCGM                           = me.oTxtFavorecidoInputCodigo.getValue();
    oParam.sCaracteristicaPeculiarDebito  = me.oTxtCaracteristicaDebitoInputCodigo.getValue();
    oParam.sCaracteristicaPeculiarCredito = me.oTxtCaracteristicaCreditoInputCodigo.getValue();
    oParam.k17_texto                      = encodeURIComponent(tagString(me.getObservacao()));
    
    if(me.iInscricaoPassivo != null) {
      
      oParam.k17_texto += " Processo Adminstrativo: ";
      oParam.k17_texto += encodeURIComponent(tagString(me.oTxtProcessoAdministrativoInput.getValue()));
      oParam.iInscricao = me.iInscricaoPassivo;
      sMsg  = "Este expediente deverá ser utilizado para registrar o fato contábil ocorrido,";
      sMsg += " mas fica-se claro que é absolutamente ilegal realizar pagamentos sem o prévio empenho da despesa.\n";
      sMsg += "Logo, recomenda-se a abertura de Processo Administrativo e que este seja anexado a documentação,";
      sMsg += "visando proteger a integridade profissional do responsável técnico da área contábil.";
      alert(sMsg);
    }
    
    if (me.iTipoTransferencia == 1) {
      oParam.iCodigoInstituicaoDestino = me.oTxtInstituicaoDestinoCodigo.getValue();
    }
    
    var oAjax = new Ajax.Request(me.sUrlRpc,
                                {method: 'post',
                                 parameters: 'json='+Object.toJSON(oParam),
                                 onComplete: me.completaSalvar
                                });
  });

  /**
   * Funcao responsavel por tratar os dados do objeto depois de salvar
   */
  me.completaSalvar = function (oAjax) {
    
    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");
    if (oRetorno.status == 1) {
      
      if (confirm(oRetorno.message.urlDecode()+" Deseja emitir o documento?")) {
        window.open('cai1_slip003.php?&numslip='+oRetorno.iCodigoSlip, '', 'location=0');
      }      
      me.clearAllFields();
    } else {
      alert(oRetorno.message.urlDecode());  
    }
  };
  
  /**
   * Executa o estorno do pagamento
   */
  me.oButtonEstornar.observe('click', function() {
    
    if (me.getMotivoAnulacao() == "") {
      
      alert("É necessário informar o motivo do estorno.");
      return false;
    }
    
    var sMsgEstorno = "Deseja estonar a transferência "+me.oTxtCodigoSlip.getValue()+"?";
    if (!confirm(sMsgEstorno)) {
      return false;
    }
    
    js_divCarregando("Aguarde, estornando transferência...", "msgBox");
    
    var oParam                 = new Object();
    oParam.exec                = "anularSlip";
    oParam.sMotivo             = encodeURIComponent(tagString(me.getMotivoAnulacao()));
    oParam.k17_codigo          = me.oTxtCodigoSlip.getValue();
    oParam.iCodigoTipoOperacao = me.iTipoTransferencia;
    
    var oAjax = new Ajax.Request(me.sUrlRpc,
                                {method: 'post',
                                 parameters: 'json='+Object.toJSON(oParam),
                                 onComplete: me.completaEstorno
                                });
  });
  
  me.completaEstorno = function (oAjax) {
    
    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");
    alert(oRetorno.message.urlDecode());
    if (oRetorno.status == 1) {
      me.start();
    }
  }
  
  /**
   * Importa um slip já existente
   */
  me.oButtonImportar.observe('click', function (){

	me.lImportacao = true;
    me.pesquisaCodigoSlip(true);
  });
  
  /**
   * Lookup de pesquisa conta saltes
   */
  me.pesquisaContaSaltes = function (lMostra, lCredito) {

    /**
     * Controla que função chamar para completar os campos
     * e também para pegar o valor do input certo
     */
    var sFunctionCompleta = "Debito";
    var sIframe           = me.sPesquisaContaDebito;
    if (lCredito) {

      sFunctionCompleta = "Credito";
      sIframe           = me.sPesquisaContaCredito;
    }

    var sObjetoTxtConta = "me.oTxtConta" + sFunctionCompleta + "Codigo";
    var oTxtConta       = eval(sObjetoTxtConta);

    var sUrlSaltes = "func_saltesreduz.php?pesquisa_chave="+oTxtConta.getValue()+"&funcao_js=parent."+me.sNomeInstancia+".preenche"+sFunctionCompleta;
    if (lMostra) {
      sUrlSaltes = "func_saltesreduz.php?funcao_js=parent."+me.sNomeInstancia+".completa"+sFunctionCompleta+"|k13_reduz|k13_descr";
    }

    js_OpenJanelaIframe("", 'db_iframe_'+sIframe, sUrlSaltes, "Pesquisa Contas", lMostra);
  };

  me.pesquisaContaEventoContabil = function(lMostra, lCredito) {

    var sFunctionCompleta = "Debito";
    var sIframe           = me.sPesquisaContaDebito;
    if (lCredito) {

      sFunctionCompleta = "Credito";
      sIframe           = me.sPesquisaContaCredito;
    }

    var sObjetoTxtConta = "me.oTxtConta" + sFunctionCompleta + "Codigo";
    var oTxtConta       = eval(sObjetoTxtConta);

    var sUrlEvento  = "func_contaeventocontabil.php?pesquisa_chave="+oTxtConta.getValue()+"&funcao_js=parent."+me.sNomeInstancia+".preenche"+sFunctionCompleta;
    sUrlEvento     += "&iTipoTransferencia="+me.iTipoTransferencia;
    sUrlEvento     += "&lContaCredito="+lCredito;

    if (lMostra) {

      sUrlEvento  = "func_contaeventocontabil.php?iTipoTransferencia="+me.iTipoTransferencia;
      sUrlEvento += "&lContaCredito="+lCredito;
      sUrlEvento += "&funcao_js=parent."+me.sNomeInstancia+".completa"+sFunctionCompleta+"|reduzido|descricao";
    }
    
    if (me.iInscricaoPassivo != null) {
      
      sUrlEvento = "func_contafornecedorinscricaopassivo.php?iInscricao="+me.iInscricaoPassivo+"&funcao_js=parent."+me.sNomeInstancia+".completaDebito|c69_credito|c60_descr";
    }
    
    js_OpenJanelaIframe("", 'db_iframe_'+sIframe, sUrlEvento, "Pesquisa Contas", lMostra);
  };

  me.completaDebito = function(iReduzido, sDescricao) {

    me.oTxtContaDebitoCodigo.setValue(iReduzido);
    me.oTxtContaDebitoDescricao.setValue(sDescricao);

    var sIframeConta = "db_iframe_" + me.sPesquisaContaDebito;
    var oIframe      = eval(sIframeConta);
    oIframe.hide();
  };

  me.preencheDebito = function(sDescricao, lErro) {

    me.oTxtContaDebitoDescricao.setValue(sDescricao);
    if (lErro) {
      me.oTxtContaDebitoCodigo.setValue("");
      $(me.oTxtContaDebitoCodigo.sName).focus();
    }

  };

  me.completaCredito = function(iReduzido, sDescricao) {

    me.oTxtContaCreditoCodigo.setValue(iReduzido);
    me.oTxtContaCreditoDescricao.setValue(sDescricao);

    var sIframeConta = "db_iframe_" + me.sPesquisaContaCredito;
    var oIframe      = eval(sIframeConta);
    oIframe.hide(); 
  };

  me.preencheCredito = function(sDescricao, lErro) {

    me.oTxtContaCreditoDescricao.setValue(sDescricao);
    if (lErro) {
      me.oTxtContaCreditoCodigo.setValue("");
      $(me.oTxtContaCreditoCodigo.sName).focus();
    }
  }
  
  /**
   * Lookup de pesquisa do Historico
   */
  me.pesquisaHistorico = function (lMostra) {
    
    var sUrlHistorico = "func_conhist.php?pesquisa_chave="+me.oTxtHistoricoInputCodigo.getValue()+"&funcao_js=parent."+me.sNomeInstancia+".preencheHistorico";
    if (lMostra) {
      sUrlHistorico = "func_conhist.php?funcao_js=parent."+me.sNomeInstancia+".completaHistorico|c50_codhist|c50_descr";
    }
    js_OpenJanelaIframe("", 'db_iframe_conhist', sUrlHistorico, "Pesquisa Histórico", lMostra);
  };
  
  /**
   * Preenche a descricao Historico
   */
  me.preencheHistorico = function (sDescricao, lErro) {
    
    me.oTxtHistoricoInputDescricao.setValue(sDescricao);
    if (lErro) {
      me.oTxtHistoricoInputCodigo.setValue('');
    }
  };
  
  /**
   * Completa os campos do Historico
   */
  me.completaHistorico = function (iCodigoHistorico, sDescricao) {
    
    me.oTxtHistoricoInputCodigo.setValue(iCodigoHistorico);
    me.oTxtHistoricoInputDescricao.setValue(sDescricao);
    db_iframe_conhist.hide();
  };
  
  /**
   * Abre lookup de pesquisa do CGM
   */
  me.pesquisaFavorecido = function(lMostra) {
    
    if (me.oTxtFavorecidoInputCodigo.getValue() == "") {
      
      me.oTxtFavorecidoInputCodigo.setValue('');
      me.oTxtFavorecidoInputDescricao.setValue('');
    }
    
    var sUrlFavorecido = "func_nome.php?pesquisa_chave="+me.oTxtFavorecidoInputCodigo.getValue()+"&funcao_js=parent."+me.sNomeInstancia+".preencheFavorecido";
    if (lMostra) {
      sUrlFavorecido = "func_nome.php?funcao_js=parent."+me.sNomeInstancia+".completaFavorecido|z01_numcgm|z01_nome|z01_cgccpf";
    }
    js_OpenJanelaIframe("", 'db_iframe_cgm', sUrlFavorecido, "Pesquisa Favorecido", lMostra);
  };
  
  
  /**
   * Preenche o favorecido da transferencia
   */
  me.preencheFavorecido = function (lErro, sNome, sCnpj) {
    
    var sCnpjTratado = "";
    if (sCnpj != "" && sCnpj != undefined) {
      var sCnpjTratado = js_formatar(sCnpj, 'cpfcnpj')+ " - ";
    }
    me.oTxtFavorecidoInputDescricao.setValue(sCnpjTratado+""+sNome);
    
    if (lErro) {
      me.oTxtFavorecidoInputCodigo.setValue('');
    }
  };
  
  /**
   * completa o favorecido da transferencia
   */
  me.completaFavorecido = function (iCodigoFavorecido, sNomeFavorecido, CNPJ) {
    
    var sCnpjTratado = "";
    if (CNPJ != "") {
      var sCnpjTratado = js_formatar(CNPJ, 'cpfcnpj')+ " - ";
    }
    me.oTxtFavorecidoInputCodigo.setValue(iCodigoFavorecido);
    me.oTxtFavorecidoInputDescricao.setValue(sCnpjTratado+""+sNomeFavorecido);
    db_iframe_cgm.hide();
  };
  
  
  /**
   * Lookup de pesquisa da Caracteristica Peculiar
   */
  me.pesquisaCaracteristicaPeculiarDebito = function(lMostra) {
    
    var sUrlCaracteristica = "func_concarpeculiar.php?pesquisa_chave="+me.oTxtCaracteristicaDebitoInputCodigo.getValue()+"&funcao_js=parent."+me.sNomeInstancia+".preencheCaracteristicaDebito";
    if (lMostra) {
      sUrlCaracteristica = "func_concarpeculiar.php?funcao_js=parent."+me.sNomeInstancia+".completaCaracteristicaDebito|c58_sequencial|c58_descr";
    }
    js_OpenJanelaIframe("", 'db_iframe_concarpeculiardebito', sUrlCaracteristica, "Pesquisa Característica Peculiar - Conta Débito", lMostra);
  };
  
  /**
   * Preenche a caracteristica peculiar
   */
  me.preencheCaracteristicaDebito = function (sDescricao, lErro) {
    
    me.oTxtCaracteristicaDebitoInputDescricao.setValue(sDescricao);
    if (lErro) {
      me.oTxtCaracteristicaDebitoInputCodigo.setValue('');
    }
  };
  
  /**
   * Completa os dados da caracteristica peculiar
   */
  me.completaCaracteristicaDebito = function(iCodigo, sDescricao) {
    
    me.oTxtCaracteristicaDebitoInputCodigo.setValue(iCodigo);
    me.oTxtCaracteristicaDebitoInputDescricao.setValue(sDescricao);
    db_iframe_concarpeculiardebito.hide();
  };
  
  /**
   * Lookup de pesquisa da Caracteristica Peculiar Credito
   */
  me.pesquisaCaracteristicaPeculiarCredito = function(lMostra) {
    
    var sUrlCaracteristicaCredito = "func_concarpeculiar.php?pesquisa_chave="+me.oTxtCaracteristicaCreditoInputCodigo.getValue()+"" +
    		                            "&funcao_js=parent."+me.sNomeInstancia+".preencheCaracteristicaCredito";
    if (lMostra) {
      sUrlCaracteristicaCredito = "func_concarpeculiar.php?funcao_js=parent."+me.sNomeInstancia+".completaCaracteristicaCredito|c58_sequencial|c58_descr";
    }
    js_OpenJanelaIframe("", 'db_iframe_concarpeculiarcredito', sUrlCaracteristicaCredito, "Pesquisa Característica Peculiar - Conta Crédito", lMostra);
  };
  
  /**
   * Preenche a caracteristica peculiar da conta credito
   */
  me.preencheCaracteristicaCredito = function (sDescricao, lErro) {
    
    me.oTxtCaracteristicaCreditoInputDescricao.setValue(sDescricao);
    if (lErro) {
      me.oTxtCaracteristicaCreditoInputCodigo.setValue('');
    }
  };
  
  /**
   * Completa a caracteristica peculiar da conta credito
   */
  me.completaCaracteristicaCredito = function(iCodigo, sDescricao) {
    
    me.oTxtCaracteristicaCreditoInputCodigo.setValue(iCodigo);
    me.oTxtCaracteristicaCreditoInputDescricao.setValue(sDescricao);
    db_iframe_concarpeculiarcredito.hide();
  };
  
  /**
   * Funcoes de Pesquisa da Instituicao de Destino
   */
  me.pesquisaInstituicaoDestino = function (lMostra) {
    
    var sUrlDestino = "func_db_config.php?lDiminuirCampos=true&pesquisa_chave="+me.oTxtInstituicaoDestinoCodigo.getValue()+"" +
    		              "&funcao_js=parent."+me.sNomeInstancia+".preencheInstituicaoDestino";
    if (lMostra) {
      sUrlDestino = "func_db_config.php?lDiminuirCampos=true&funcao_js=parent."+me.sNomeInstancia+".completaInstituicaoDestino|codigo|nomeinst";
    }
    js_OpenJanelaIframe("", 'db_iframe_db_config', sUrlDestino, "Pesquisa Instituição Destino", lMostra);
  };
  
  /**
   * Preenche pesquisa da instituicao destino
   */
  me.preencheInstituicaoDestino = function (sDescricao, lErro) {
    
    if (!me.validarInstituicao()) {
      return false;
    }
    
    me.oTxtDescricaoInstituicaoDestino.setValue(sDescricao);
    if (lErro) {
      me.oTxtInstituicaoDestinoCodigo.setValue('');
    }
  };
  
  /**
   * Completa a pesquisa da instituicao destino
   */
  me.completaInstituicaoDestino = function (iCodigoInstituicao, sNomeInstituicao) {
    
    me.oTxtInstituicaoDestinoCodigo.setValue(iCodigoInstituicao);
    me.oTxtDescricaoInstituicaoDestino.setValue(sNomeInstituicao);
    if (!me.validarInstituicao()) {
      return false;
    }
    db_iframe_db_config.hide();
  };
  
  /**
   * Valida se a instituicao de destino é a mesma de origem
   * @return boolean
   */
  me.validarInstituicao = function() {
    
    if (me.oTxtInstituicaoDestinoCodigo.getValue() == me.oTxtInstituicaoOrigemCodigo.getValue()) {
      
      alert("Instituição de destino igual a instituição de origem. Verifique!");
      me.oTxtInstituicaoDestinoCodigo.setValue('');
      me.oTxtDescricaoInstituicaoDestino.setValue('');
      return false;
    }
    return true;
  };
  
  /**
   * Lookup de pesquisa do codigo do slip
   */
  me.pesquisaCodigoSlip = function (lMostra) {

    var sUrlSlip = "func_sliptipovinculo.php?iTipoOperacao="+me.iTipoInclusao+"&funcao_js=parent."+me.sNomeInstancia+".preencheSlip|k17_codigo";
    js_OpenJanelaIframe("", 'db_iframe_slip', sUrlSlip, "Pesquisa Slip", lMostra);
  };
  
  /**
   * Preenche o código do slip e carrega as informacoes
   */
  me.preencheSlip = function (iCodigoSlip) {
    
    me.oTxtCodigoSlip.setValue(iCodigoSlip);
    db_iframe_slip.hide();
    me.getDadosTransferencia();
  }
    
  
  /**
   * Busca os dados da instituicao de origem
   */
  me.getDadosInstituicaoOrigem = function (){
    
   js_divCarregando("Aguarde, carregando dados instituição...", "msgBox");
   var oParam = new Object();
   oParam.exec = "getDadosInstituicaoOrigem";
   var oAjax = new Ajax.Request ( me.sUrlRpc,
                                {
                                method: 'post',
                                parameters: 'json='+Object.toJSON(oParam),
                                onComplete: function(oAjax){
                                  
                                  js_removeObj("msgBox");
                                  var oRetorno = eval("("+oAjax.responseText+")");
                                  
                                  me.oTxtDescricaoInstituicaoOrigem.setValue(oRetorno.sInstituicaoOrigem.urlDecode());
                                  me.oTxtInstituicaoOrigemCodigo.setValue(oRetorno.iCodigoInstituicaoOrigem);
                                  
                                  if (me.iTipoTransferencia == 1) {
                                    me.oTxtFavorecidoInputCodigo.setValue(oRetorno.iCodigoCgm);
                                    me.oTxtFavorecidoInputDescricao.setValue(oRetorno.sCNPJ.urlDecode()+" - "+oRetorno.sNomeCgm.urlDecode());
                                    me.oTxtFavorecidoInputCodigo.setReadOnly(true);
                                  }
                                }
                                });
  };
  
  /**
   * Busca os dados da transferencia financeira
   */
  me.getDadosTransferencia = function() {
    
    js_divCarregando("Aguarde, carregando dados da transferência...", "msgBox");
    var oParam                 = new Object();
    oParam.exec                = "getDadosTransferencia";
    oParam.k17_codigo          = me.oTxtCodigoSlip.getValue();
    oParam.iCodigoTipoOperacao = me.iTipoTransferencia;
    var oAjax = new Ajax.Request ( me.sUrlRpc,
                                  {method: 'post',
                                   parameters: 'json='+Object.toJSON(oParam),
                                   onComplete: me.preencheDadosTransferencia
                                  });
  }
  
  /**
   * Preenche os dados da transferencia com o retorno do ajax
   */
  me.preencheDadosTransferencia = function (oAjax) {
    
    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");

    me.oTxtContaCreditoCodigo.setValue(oRetorno.iContaCredito);
    me.oTxtContaDebitoCodigo.setValue(oRetorno.iContaDebito);

    var sFunctionPesquisa = "me.pesquisaConta";

    var oFunctionDebito =  eval(sFunctionPesquisa + me.sPesquisaContaDebito);
    oFunctionDebito(false, false, true);
    
    window.setTimeout(
      function() {
        var oFunctionCredito = eval(sFunctionPesquisa + me.sPesquisaContaCredito);
        oFunctionCredito(false, true, true);  
      }, 1000
    );
    
    
    if (!me.lImportacao) {
      me.setAllFieldsReadOnly();
    } else {
      me.oTxtCodigoSlip.setValue('');
    }

    me.oTxtInstituicaoOrigemCodigo.setValue(oRetorno.iInstituicaoOrigem);
    me.oTxtDescricaoInstituicaoOrigem.setValue(oRetorno.sDescricaoInstituicaoOrigem.urlDecode());
    
    if (me.iTipoTransferencia == 1) {
      me.oTxtInstituicaoDestinoCodigo.setValue(oRetorno.iInstituicaoDestino);
    }
    
    me.oTxtFavorecidoInputCodigo.setValue(oRetorno.iCodigoCgm);
    me.oTxtFavorecidoInputDescricao.setValue(oRetorno.sCNPJ+" - "+oRetorno.sNomeCgm.urlDecode());
    me.oTxtCaracteristicaDebitoInputCodigo.setValue(oRetorno.sCaracteristicaDebito);
    me.oTxtCaracteristicaCreditoInputCodigo.setValue(oRetorno.sCaracteristicaCredito);
    me.oTxtHistoricoInputCodigo.setValue(oRetorno.iHistorico);
    me.oTxtValorInput.setValue(oRetorno.nValor);
    me.setObservacao(oRetorno.sObservacao.urlDecode());
    me.pesquisaInstituicaoDestino(false);
    me.pesquisaHistorico(false);
    me.pesquisaCaracteristicaPeculiarDebito(false);
    me.pesquisaCaracteristicaPeculiarCredito(false);
  };
  
  
  /**
   * Seta todos os campos do formulario como ReadOnly
   */
  me.setAllFieldsReadOnly = function() {
    
    me.oTxtCodigoSlip.setReadOnly(true);
    me.oTxtInstituicaoOrigemCodigo.setReadOnly(true);
    me.oTxtDescricaoInstituicaoOrigem.setReadOnly(true);
    me.oTxtInstituicaoDestinoCodigo.setReadOnly(true);
    me.oTxtDescricaoInstituicaoDestino.setReadOnly(true);
    me.oTxtFavorecidoInputCodigo.setReadOnly(true);
    me.oTxtFavorecidoInputDescricao.setReadOnly(true);
    me.oTxtCaracteristicaDebitoInputCodigo.setReadOnly(true);
    me.oTxtCaracteristicaDebitoInputDescricao.setReadOnly(true);
    me.oTxtCaracteristicaCreditoInputCodigo.setReadOnly(true);
    me.oTxtCaracteristicaCreditoInputDescricao.setReadOnly(true);

    document.getElementById("labelContaDebito").innerHTML  = "<b>Conta Débito: </b>";
    document.getElementById("labelContaCredito").innerHTML = "<b>Conta Crédito: </b>";
    me.oTxtContaCreditoCodigo.setReadOnly(true);
    me.oTxtContaDebitoCodigo.setReadOnly(true);

    me.oTxtHistoricoInputCodigo.setReadOnly(true);
    me.oTxtHistoricoInputDescricao.setReadOnly(true);
    me.oTxtValorInput.setReadOnly(true);
    me.setObservacaoReadOnly(true);
    
    
    $("td_cpca_contacredito_"+me.sNomeInstancia).innerHTML  = "<b>C.Peculiar / C.Aplicação:</b>";
    $("td_cpca_contadebito_"+me.sNomeInstancia).innerHTML   = "<b>C.Peculiar / C.Aplicação:</b>";
    $("td_instituicaodestino_"+me.sNomeInstancia).innerHTML = "<b>Instituição Destino:</b>";
    $("td_historico_"+me.sNomeInstancia).innerHTML          = "<b>Histórico:</b>";
  }
  
  /**
   * Limpa todos os campos do formulario
   */
  me.clearAllFields = function() {
    
    me.oTxtCodigoSlip.setValue('');

    if (me.iTipoTransferencia <= 2) { 

      me.oTxtInstituicaoDestinoCodigo.setValue('');
      me.oTxtDescricaoInstituicaoDestino.setValue('');
    }
    
    me.oTxtFavorecidoInputCodigo.setValue('');
    me.oTxtFavorecidoInputDescricao.setValue('');

    /**
     * Trazer preenchido característica peculiar 000 por padrão
     */
    me.oTxtCaracteristicaDebitoInputCodigo.setValue("000");
    me.pesquisaCaracteristicaPeculiarDebito(false); 
    me.oTxtCaracteristicaCreditoInputCodigo.setValue("000");
    me.pesquisaCaracteristicaPeculiarCredito(false);
    
    me.oTxtHistoricoInputCodigo.setValue('');
    me.oTxtHistoricoInputDescricao.setValue('');
    me.oTxtValorInput.setValue('');
    me.setObservacao('');
  };
  
  
  /**
   * Retorna o conteudo do textarea observacao
   * @return string
   */
  me.getObservacao = function() {
    return me.oTxtObservacaoInput.value;
  };
  
  /**
   * Seta valor para o campo observacacao
   * @param {string}
   */
  me.setObservacao = function (sObservacao) {
    me.oTxtObservacaoInput.value = sObservacao;
  };
  
  /**
   * Seta o campo observacao como readonly
   */
  me.setObservacaoReadOnly = function (lDisable) {
    
    if (lDisable) {
      me.oTxtObservacaoInput.readOnly = true;
      me.oTxtObservacaoInput.style.backgroundColor = "#DEB887";
    } else {
      me.oTxtObservacaoInput.readOnly = false;
      me.oTxtObservacaoInput.style.backgroundColor = "#FFFFFF";
    }
    return true;
  };
  
  /**
   * Retorna uma string com o motivo da anulação do slip
   */
  me.getMotivoAnulacao = function() {
    return me.oTxtMotivoAnulacaoInput.value;
  };
  
  /**
   * Seta o codigo do Slip
   */
  me.setCodigoSlip = function (iCodigoSlip) {
    me.iCodigoSlip = iCodigoSlip;
  };
  
  /**
   * Retorna o código de um slip
   */
  me.getCodigoSlip = function () {
    return me.iCodigoSlip;
  };
  
  /**
   * Seta se o PCASP esta ativo
   */
  me.setPCASPAtivo = function (lPcaspAtivo) {
    
    if (lPcaspAtivo == "t") {
      me.lUsaPCASP = true;
    } else {
      me.lUsaPCASP = false;
    }
  };
  
  /**
   * Funcoes que so devem ser executadas após o componente estar montado na tela
   */
  me.start = function() {
    
    me.clearAllFields();
    me.getDadosInstituicaoOrigem();
    
    if (me.iOpcao == 2) {
      
      me.setAllFieldsReadOnly();
      me.pesquisaCodigoSlip(true);
    }
    
    if (me.lUsaPCASP === false) {
      
      alert("Para utilizar a rotina, o PCASP deve estar ativado.");
      me.oButtonEstornar.disabled = true;
      me.oButtonSalvar.disabled = true;
    }

    if(me.iInscricaoPassivo != null) {
      me.ajustaTelaBaixaPagamento();
    }
  };
  
  me.setReadOnly = function (lReadOnly) {
    me.lReadOnly = lReadOnly;
  };
};

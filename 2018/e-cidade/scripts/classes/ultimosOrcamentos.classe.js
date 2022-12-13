ultimosOrcamentos = function () {


  me                = this;
  this.iCodigoItem  = null;
  this.aUnidades    = new Array();
  this.iCodigoItem  = null;
  this.iFornecedor  = null;
  this.sDataInicial = null;
  this.sDataFinal   = null;
  this.window       = new windowAux('wndOrcamento','Últimos Orçamentos', screen.width - 40);
  this.mediapreco   = 0 ;
  this.window. setShutDownFunction(function () {me.window.destroy()});
  this.onMediaCalculada = null;
  var sContent  = "<div><fieldset id='cntDataGrid'></fieldset></div>";
  sContent     += "<div style='text-align:center'>";
  sContent     += "<span style='float:left;text-align:right'><b>Unidades:</b><select id='cboUnidadesItem'></select></span>"
  sContent     += "<span><input type='button' id='btnActionImportarMedia' value='Importar Média'>";
  sContent     += "</span><span style='float:right;text-align:right'><b>Média:</b>";
  sContent     += "<input type='text' readonly id='mediaprecosorcamento'>&nbsp;</span></div>";
  this.window.setContent(sContent);
  me.messageboard  = new messageBoard('msg1',
                                     '',
                                     '',
                                     $('windowwndOrcamento_content')
                                     );
  me.messageboard.show();
  this.addUnidade = function (iCodigoUnidade) {
    if (iCodigoUnidade instanceof Array) {

      for (var i = 0; i < iCodigoUnidade.length; i++) {
        this.addUnidade(iCodigoUnidade[i]);
      }
    } else {
      this.aUnidades.push(iCodigoUnidade);
    }
  };

  this.setItem = function(iCodigoItem) {
    this.iCodigoItem = iCodigoItem;
  };



  me.oGridOrcamentos = new DBGrid('gridOrcamentos');
  me.oGridOrcamentos.nameInstance = 'me.oGridOrcamentos';
  me.oGridOrcamentos.setCellWidth(new Array("5%","8%","15%","40%","10%","10%","20%"));
  me.oGridOrcamentos.setHeight(me.window.getHeight()/1.7);
  me.oGridOrcamentos.allowSelectColumns(false);
  me.oGridOrcamentos.setHeader(new Array('Origem', 'Data',
                                         'CGM', 'Fornecedor', 'Unidade', 'Descr. Unidade', 'Vlr. Unit.', "Item"));

  me.oGridOrcamentos.aHeaders[7].lDisplayed = '';
  me.oGridOrcamentos.show($('cntDataGrid'));
  this.getOrcamentos  = function() {

     var oParam         = new Object();
     oParam.exec        = "getUltimosOrcamentos";
     oParam.iMaterial   = me.iCodigoItem;
     oParam.aUnidades   = me.aUnidades;
     oParam.iFornecedor = me.iFornecedor;
     oParam.dtInicial   = me.sDataInicial;
     oParam.dtFinal     = me.sDataFinal;
     js_divCarregando('Aguarde, Pesquisando orcamentos','msgBox');
     var oAjax = new Ajax.Request('com4_solicitacaoCompras.RPC.php',
                                  {
                                  method: 'post',
                                  parameters: 'json='+Object.toJSON(oParam),
                                  onComplete: me.retornoGetOrcamentos
                                  }
                                 )
  };

  this.retornoGetOrcamentos  = function(oAjax) {


    js_removeObj('msgBox');
    var oRetorno  = eval("("+oAjax.responseText+")");
    me.oGridOrcamentos.clearAll(true);
    me.messageboard.setHelp('Orçamentos dos últimos '+oRetorno.itens.iDias+' dias');
    if (oRetorno.itens.sFornecedor == "") {
      me.messageboard.setTitle('Últimos Orçamentos para o item '+oRetorno.itens.sDescricaoItem.urlDecode());
    } else {
      me.messageboard.setTitle('Últimos Orçamentos para o fornecedor '+oRetorno.itens.sFornecedor.urlDecode());

    }
    me.mediapreco   = oRetorno.media;
    var iLinhasGrid = 0;
    if (oRetorno.itens.solicitacoes.length > 0){

        var aLinha  = new Array();
         aLinha[0]   = "<b>Sol<b>";
         aLinha[1]   = "";
         aLinha[2]   = "";
         aLinha[3]   = "";
         aLinha[4]   = "";
         aLinha[5]   = "";
         aLinha[6]   = "";
         aLinha[7]   = '';
         me.oGridOrcamentos.addRow(aLinha);
         me.oGridOrcamentos.aRows[0].sStyle ='background-color:gray;color:white';
         iLinhasGrid++;

    }
    for (var i = 0; i < oRetorno.itens.solicitacoes.length; i++) {

       with(oRetorno.itens.solicitacoes[i]) {
         var aLinha  = new Array();
         aLinha[0]   = "";
         aLinha[1]   = js_formatar(data, 'd');
         aLinha[2]   = codigocgm;
         aLinha[3]   = nomecgm.urlDecode();
         aLinha[4]   = unidade.urlDecode();
         aLinha[5]   = descricaounidade.urlDecode();
         aLinha[6]   = js_formatar(valorunitario,'f');
         aLinha[7]   = descricaomaterial.urlDecode();
         me.oGridOrcamentos.addRow(aLinha);
         iLinhasGrid++;
       }
    }

    if (oRetorno.itens.processodecompras.length > 0){

      var aLinha  = new Array();
      aLinha[0]   = "<b>PC<b>";
      aLinha[1]   = "";
      aLinha[2]   = "";
      aLinha[3]   = "";
      aLinha[4]   = "";
      aLinha[5]   = "";
      aLinha[6]   = "";
      aLinha[7]   = '';
      me.oGridOrcamentos.addRow(aLinha);
      me.oGridOrcamentos.aRows[iLinhasGrid].sStyle ='background-color:gray;color:white';
      iLinhasGrid++;
      for (var i = 0; i < oRetorno.itens.processodecompras.length; i++) {

        with(oRetorno.itens.processodecompras[i]) {

          var aLinha  = new Array();
          aLinha[0]   = "";
          aLinha[1]   = js_formatar(data, 'd');
          aLinha[2]   = codigocgm;
          aLinha[3]   = nomecgm.urlDecode();
          aLinha[4]   = unidade.urlDecode();
          aLinha[5]   = descricaounidade.urlDecode();
          aLinha[6]   = js_formatar(valorunitario,'f');
          aLinha[7]   = descricaomaterial.urlDecode();
          me.oGridOrcamentos.addRow(aLinha);
          iLinhasGrid++;
        }
      }
    }
    if (oRetorno.itens.empenhos.length > 0) {

      var aLinha  = new Array();
      aLinha[0]   = "<b>Empenhos<b>";
      aLinha[1]   = "";
      aLinha[2]   = "";
      aLinha[3]   = "";
      aLinha[4]   = "";
      aLinha[5]   = "";
      aLinha[6]   = "";
      aLinha[7]   = "";
      me.oGridOrcamentos.addRow(aLinha);
      me.oGridOrcamentos.aRows[iLinhasGrid].sStyle ='background-color:gray;color:white';
      iLinhasGrid++;
      for (var i = 0; i < oRetorno.itens.empenhos.length; i++) {

        with(oRetorno.itens.empenhos[i]) {

          var aLinha  = new Array();
          aLinha[0]   = "";
          aLinha[1]   = js_formatar(data, 'd');
          aLinha[2]   = codigocgm;
          aLinha[3]   = nomecgm.urlDecode();
          aLinha[4]   = unidade.urlDecode();
          aLinha[5]   = descricaounidade.urlDecode();
          aLinha[6]   = js_formatar(valorunitario,'f');
          aLinha[7]   = descricaomaterial.urlDecode();
          me.oGridOrcamentos.addRow(aLinha);
          iLinhasGrid++;
        }
      }
    }

    me.oGridOrcamentos.renderRows();
    $('mediaprecosorcamento').value = js_formatar(oRetorno.media, "f");
    if (typeof(me.onMediaCalculada) == 'function') {
      me.onMediaCalculada();
    }
    /**
     * Preenchemos as unidades
     */
    $('cboUnidadesItem').options.length = 0;
    for (var iUni = 0; iUni < oRetorno.unidades.length; iUni++) {

      with (oRetorno.unidades[iUni]) {

        var oOption = new Option(descricaounidade.urlDecode(), codigounidade);
        $('cboUnidadesItem').add(oOption, null);
       }
     }
     if (me.aUnidades.length == 1) {
       $('cboUnidadesItem').value = me.aUnidades[0];
     }
  };

  this.showUltimosOrcamentos = function (iTop) {

    if (me.iFornecedor != "") {
      //me.oGridOrcamentos.showColumn(true, 7);
    }
    if (iTop == null) {
      iTop = 25;
    }
    me.window.show(iTop, 0);
  };

  this.setCallBackMedia = function (oCallBack) {

    $('btnActionImportarMedia').style.display='';
    $('btnActionImportarMedia').observe("click", oCallBack);

  };
  $('btnActionImportarMedia').style.display='none';

  this.getMediaPrecos = function () {
    return me.mediapreco;
  };

  this.setDataInicial = function(sDataInicial) {

    me.sDataInicial   = sDataInicial;
  };

  this.setDataFinal = function(sDataFinal) {

    me.sDataFinal   = sDataFinal
  };
  this.setFornecedor = function(iFornecedor) {
    this.iFornecedor = iFornecedor;
  };

  this.onChanceCboUnidades = function() {
    me.aUnidades.length    = 0;
    me.addUnidade(new Array($F('cboUnidadesItem')));
    me.getOrcamentos();
  };
  $('cboUnidadesItem').observe('change', me.onChanceCboUnidades);
};

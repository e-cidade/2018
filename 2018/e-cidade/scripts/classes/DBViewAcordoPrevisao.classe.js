DBViewAcordoPrevisao = function (iPosicao, sInstance, sTitulo, lReadOnly, lMostrarLegenda, oNode, lExecucao) {

  var me                         = this;
  me.sRPC                        = 'aco4_acordoposicaoprevisao.RPC.php';
  me.instance                    = sInstance;
  me.iPosicao                    = iPosicao;
  me.sTitulo                     = sTitulo;
  me.aPeriodos                   = new Array();
  me.aItens                      = new Array();
  me.sColorNaoExecutado          = '#FFFFFF';
  me.sColorExecutadoParcialmente = '#F2F685';
  me.sColorExecutado             = '#45B2A0';
  me.sColorSemMovimentacao       = '#CAE0FF';
  me.sColorParalisado            = '#DEB887';

  me.sTituloQuantidade           = 'Qtd Total';
  me.iTipoCalculoQuantidade      = 1;

  var iWidth            = document.width - 10;
  this.view             = '';
  this.lReadOnly        = false;
  this.lMostrarLegenda = false;
  if (lReadOnly != null) {
    this.lReadOnly      = lReadOnly;
  }
  me.onSaveComplete   = function (oRetorno) {

  };

  if (lMostrarLegenda != null) {
    this.lMostrarLegenda = lMostrarLegenda;
  }

  if (oNode != null) {
    this.view = oNode;
  }

  this.getDocHeight = function () {
    var D = document;
    return Math.max(
        Math.max(D.body.scrollHeight, D.documentElement.scrollHeight),
        Math.max(D.body.offsetHeight, D.documentElement.offsetHeight),
        Math.max(D.body.clientHeight, D.documentElement.clientHeight)
    );
  };

  this.onBeforeSave = function() {
    return true;
  };

  this.setTipoCalculoQuantidade = function(iTipoCalculo) {

     switch (iTipoCalculo) {

       case 1:

         me.sTituloQuantidade           = 'Qtd Total';
         me.iTipoCalculoQuantidade      = 1;
       break;

     case 2:

       me.sTituloQuantidade           = 'Saldo Exec.';
       me.iTipoCalculoQuantidade      = 2;
       break;

     }
  }
  var iHeightGrid = me.getDocHeight()/2;
  this.wndAcordoPrevisao = new windowAux('wndAcordoPrevisao'+me.iPosicao, me.sTitulo, iWidth);
  var sContent  = '<form id="frmEstrutural'+me.iPosicao+'">';
  sContent     += '<div style="height:80%;width:100%"><center>';
  sContent     += ' <table style="width:100%" border="0">';
  sContent     += ' <tr><td>';
  sContent     += ' <fieldset>';
  sContent     += '  <legend>';
  sContent     += '    <b>'+me.sTitulo+'</b>';
  sContent     += '  </legend>';
  sContent     += '  <table id="tbpai" cellpadding="0" style="border:2px inset white;background: white" cellspacing="0" width="">';
  sContent     += '  <tr><td valign="top">';
  sContent     += '  <table cellspacing="0" cellpadding="0" id="table">';
  sContent     += ' <thead> ';
  sContent     += '   <tr>';
  sContent     += '     <th width="350"  class="table_header" id="teste0" style="text-align:center;"colspan="6">';
  sContent     += '       Itens';
  sContent     += '     </th>';
  sContent     += '  </tr>';
  sContent     += '  <tr>';
  sContent     += '    <th class="table_header" style="width:30px" id="teste1s" nowrap="nowrap">';
  sContent     += '        Ordem';
  sContent     += '    </th>';
  sContent     += '    <th class="table_header" style="width:200px" id="teste1s" nowrap="nowrap">'
  sContent     += '         Descrição';
  sContent     += '    </th>';
  sContent     += '    <th class="table_header" style="width:70px" id="teste1s" nowrap="nowrap">'
  sContent     += '        Unidade';
  sContent     += '    </th>';
  sContent     += '    <th class="table_header" style="width:50px" id="thdQuantidades" nowrap="nowrap">'
  sContent     +=    me.sTituloQuantidade;
  sContent     += '    </th>';
  sContent     += '    <th class="table_header" style="width:50px" id="thdSaldo" nowrap="nowrap">À Executar</th>';
  sContent     += '    <th class="table_header" style="width:50px" id="thdSaldo" nowrap="nowrap">Empenho</th>';
  sContent     += '   </tr>';
  sContent     += '  </thead>';
  sContent     += '  <tbody id="tbodyDescr" style="height: '+iHeightGrid+'px; overflow: hidden; overflow-x:hidden">';
  sContent     += '     <tr style="height:auto" id="ffhackTable1">';
  sContent     += '       <td>&nbsp;</td> ';
  sContent     += '    </tr> ';
  sContent     += '  </tbody>';
  sContent     += '  </table>';
  sContent     += '  </td> ';
  sContent     += '  <td valign="top" width="100%">';
  sContent     += '  <div style="overflow: scroll; overflow-y:hidden;padding:0px" id="tableRol">';
  sContent     += '    <table id="gridAcordoPrevisao'+me.iPosicao+'" cellpadding="0" cellspacing="0">';
  sContent     += '    <thead id="gridAcordoPrevisaoHeader'+me.iPosicao+'">';
  sContent     += '    </thead>';
  sContent     += '     <tbody style="height: '+iHeightGrid+'px; background-color:white;overflow: scroll; overflow-x:hidden" id="tBodyFields"';
  sContent     += '             onscroll="scrollTable(event)">';
  sContent     += '     <tr style="height:auto" id="ffhackTable2">';
  sContent     += '       <td>&nbsp;</td> ';
  sContent     += '    </tr> ';
  sContent     += '      </tbody>';
  sContent     += '    </table>';
  sContent     += '  </div>';
  sContent     += '  </td>';
  sContent     += '  </tr>';
  sContent     += '  </table>';
  sContent     += ' </fieldset>';
  sContent     += ' </tr></td></table>';
  sContent     += '<table style="width:100%" border="0">';
  sContent     += '  <tr>';
  sContent     += '    <td align="left" width="30%" nowrap>';
  //sContent     += '      <fieldset id="fieldLegenda" style="display: none;">';
  sContent     += '      <fieldset id="fieldLegenda" style="">';
  sContent     += '      <fieldset >';
  sContent     += '        <legend>';
  sContent     += '          <b>Legenda</b>';
  sContent     += '        </legend>';

 // sContent     += '  <div id="fieldLegenda" style="display: none; border: 1px solid; width:100%;">';

  sContent     += '        <label id="lblNaoExecutado" class="fieldLegenda" style="display: none;margin:1px 3px 1px 3px;padding:3px auto;border: 1px solid black;background-color:'+me.sColorNaoExecutado+';">';
  sContent     += '          <b>Não Executado</b>';
  sContent     += '        </label>';

  sContent     += '        <label id="lblParcial" class="fieldLegenda" style="display: none;margin:1px 3px 1px 3px;padding:3px auto;border: 1px solid black;background-color:'+me.sColorExecutadoParcialmente+';">';
  sContent     += '          <b>Executado Parcialmente</b>';
  sContent     += '        </label>';

  sContent     += '        <label id="lblExecutado" class="fieldLegenda" style="display: none;margin:1px 3px 1px 3px;padding:3px auto;border: 1px solid black; background-color:'+me.sColorExecutado+';">';
  sContent     += '          <b>Executado</b>';
  sContent     += '        </label>';

  sContent     += '        <label id="lblSemMovimento" class="fieldLegenda" style="display: none;margin:1px 3px 1px 3px;padding:3px auto;border: 1px solid black; background-color:'+me.sColorSemMovimentacao+';">';
  sContent     += '          <b>Sem Movimentação</b>';
  sContent     += '        </label>';

 // sContent     += '  </div>';

  sContent     += '        <label style="margin:1px 3px 1px 3px;padding:3px auto;border: 1px solid black; background-color:'+me.sColorParalisado+';">';
  sContent     += '          <b>Período Paralisado</b>';
  sContent     += '        </label>';

  sContent     += '      </fieldset>';
  sContent     += '    </td>';
  sContent     += '    <td align="center" width="47%" nowrap>';
  sContent     += '      <input type="button" name="btnFecharJanela" id="btnFecharJanela" value="Fechar" onclick="'+me.instance+'.fecharJanela()">';
  sContent     += '      <input type="button" name="btnImprimirExecucao" id="btnImprimirExecucao" value="Imprimir" onclick="'+me.instance+'.imprimirExecucao();">';
  if (!me.lReadOnly) {

    sContent   += '      <input type="button" id="btnSalvar"   value="Salvar Dados">';
    sContent   += '      <input type="button" id="btnCancelar" value="Cancelar">';
  }
  sContent     += '    </td>';
  sContent     += '    </td>';
  sContent     += '    <td align="center" width="25%" nowrap>&nbsp;</td>';
  sContent     += '  </tr>';
  sContent     += '</table>';
  sContent     += ' </center>';
  sContent     += '<div style="margin-left:25%">';
  sContent     += '';
  sContent     += '</div>';
  sContent     += '</div>';
  sContent     += '</form>';
  if (this.view == "") {

    me.wndAcordoPrevisao.allowCloseWithEsc(false);
    me.wndAcordoPrevisao.setContent(sContent);
  } else {

    oNode.style.display = 'none';
    oNode.innerHTML     = sContent;
  }

  if (me.view == "") {
    me.oMessageBoard   = new DBMessageBoard('msgBoardEstrutura'+me.iEstrutura,
                                            me.sTitulo,
                                            '<b>*</b> - Item Com controle mensal de execução.',
                                            me.wndAcordoPrevisao.getContentContainer()
                                            );
    me.oMessageBoard.show();
    $('msgBoardEstrutura'+me.iEstrutura).style.width='99.8%';
  }
  this.wndAcordoPrevisao.setShutDownFunction(function (){
    me.wndAcordoPrevisao.destroy();
  });


  this.show = function() {

    me.getDadosQuadro();
    if (me.view == "") {
     me.wndAcordoPrevisao.show();
    } else {
      me.view.style.display=  '';
    }
  };


  /**
   *CallbackPadrao a para o click do periodo
   */
  this.onPeriodoClick = function(iPeriodo, iItem, nQuantidade) {
   return true;
  };

  this.onBeforePeriodoClick = function(iPeriodo, iItem, nQuantidade) {
   return true;
  };

  this.getDadosQuadro = function() {

    var oParam      = new Object();
    oParam.exec     = 'getQuadroPeriodos';
    oParam.iCodigoPosicao = me.iPosicao;
    var oAjax       = new Ajax.Request(me.sRPC,
                                  {method: 'post',
                                   parameters: 'json='+Object.toJSON(oParam),
                                   onComplete:me.montaQuadro
                                  });
  };

  this.montaQuadro = function (oAjax) {

     var iSizeTable = $('table').getWidth();
     iSize = ((document.body.getWidth()-50)-iSizeTable);
     $('tableRol').style.width = new String(iSize)+"px";
     oRetorno = eval("("+oAjax.responseText+")");
     /**
      * Montamos o Cabecalho dos Meses:
      */
      var oLinhaCabecalho          = document.createElement('tr');
      oLinhaCabecalho.style.height = '1em';
      var iWidthTable = 0;
      var iTamanhoCelulaPeriodo = 200;
      var iTamanhoTotalPeriodo  = iTamanhoCelulaPeriodo * oRetorno.quadro.aPeriodos.length;
      if (iTamanhoTotalPeriodo < iSize) {
        iTamanhoCelulaPeriodo = iSize/oRetorno.quadro.aPeriodos.length;
      }
      oRetorno.quadro.aPeriodos.each(function(oPeriodo, iPeriodo) {

         var oCell = document.createElement('td');
         oCell.className     = 'table_header';
         oCell.colSpan       = 5;
         oCell.style.width   = iTamanhoCelulaPeriodo+'px';
         oCell.innerHTML     = oPeriodo.descricao.urlDecode();
         if (oPeriodo.lParalisado == true) {
           oCell.style.background = me.sColorParalisado;
         }
         oLinhaCabecalho.appendChild(oCell);
         iWidthTable += iTamanhoCelulaPeriodo;
      });
      var oCellPadding              = document.createElement('td');
      oCellPadding.className        = 'table_header';
      oCellPadding.colSpan          = 1;
      oCellPadding.style.width      = '17px';
      oCellPadding.innerHTML        = '<img src="imagens/espaco17_3.gif">';
      oLinhaCabecalho.appendChild(oCellPadding);

      /*
       *Segunda linha dos Cabecalhos
       */
      var oLinhaCabecalhoValores   = document.createElement('tr');
      oLinhaCabecalhoValores.style.height = '1em';
      $('gridAcordoPrevisaoHeader'+me.iPosicao).appendChild(oLinhaCabecalho);
      oRetorno.quadro.aPeriodos.each(function(oPeriodo, iPeriodo) {

         var oCellQuantidadePrevista              = document.createElement('td');
         oCellQuantidadePrevista.className        = 'table_header';
         oCellQuantidadePrevista.colSpan          = 1;
         oCellQuantidadePrevista.style.width      = (iTamanhoCelulaPeriodo/4)+'px';
         oCellQuantidadePrevista.innerHTML        = 'Qtd. Prevista';
         oCellQuantidadePrevista.style.whiteSpace = 'nowrap';
         if (oPeriodo.lParalisado == true) {
           oCellQuantidadePrevista.style.background = me.sColorParalisado;
         }
         oLinhaCabecalhoValores.appendChild(oCellQuantidadePrevista);

         var oCellValorPrevisto              = document.createElement('td');
         oCellValorPrevisto.className        = 'table_header';
         oCellValorPrevisto.colSpan          = 1;
         oCellValorPrevisto.style.width      = (iTamanhoCelulaPeriodo/4)+'px';
         oCellValorPrevisto.innerHTML        = 'Vlr. Previsto';
         oCellValorPrevisto.style.whiteSpace = 'nowrap';
         if (oPeriodo.lParalisado == true) {
           oCellValorPrevisto.style.background = me.sColorParalisado;
         }
         oLinhaCabecalhoValores.appendChild(oCellValorPrevisto);

         var oCellQuantidadePrevistaExecutada              = document.createElement('td');
         oCellQuantidadePrevistaExecutada.className        = 'table_header';
         oCellQuantidadePrevistaExecutada.colSpan          = 1;
         oCellQuantidadePrevistaExecutada.style.width      = (iTamanhoCelulaPeriodo/4)+'px';
         oCellQuantidadePrevistaExecutada.innerHTML        = 'Qtd. Executada';
         if (oPeriodo.lParalisado == true) {
           oCellQuantidadePrevistaExecutada.style.background = me.sColorParalisado;
         }
         oCellQuantidadePrevistaExecutada.style.whiteSpace = 'nowrap';
         oLinhaCabecalhoValores.appendChild(oCellQuantidadePrevistaExecutada);

         var oCellValorPrevistoExecutado              = document.createElement('td');
         oCellValorPrevistoExecutado.className        = 'table_header';
         oCellValorPrevistoExecutado.colSpan          = 1;
         oCellValorPrevistoExecutado.style.width      = (iTamanhoCelulaPeriodo/4)+'px';
         if (oPeriodo.lParalisado == true) {
           oCellValorPrevistoExecutado.style.background = me.sColorParalisado;
         }
         oCellValorPrevistoExecutado.innerHTML        = 'Vlr. Executado';
         oCellValorPrevistoExecutado.style.whiteSpace = 'nowrap';
         oLinhaCabecalhoValores.appendChild(oCellValorPrevistoExecutado);

         var oCellAlterarPeriodo              = document.createElement('td');
         oCellAlterarPeriodo.className        = 'table_header';
         oCellAlterarPeriodo.colSpan          = 1;
         oCellAlterarPeriodo.style.width      = '20px';
         oCellAlterarPeriodo.style.textAlign  = 'center';
         oCellAlterarPeriodo.innerHTML        = 'A';
         oCellAlterarPeriodo.style.whiteSpace = 'nowrap';
         if (oPeriodo.lParalisado == true) {
           oCellAlterarPeriodo.style.background = me.sColorParalisado;
         }
         oLinhaCabecalhoValores.appendChild(oCellAlterarPeriodo);

      });
      var oCellPadding = document.createElement('td');
      oCellPadding.className        = 'table_header';
      oCellPadding.colSpan          = 1;
      oCellPadding.style.width      = '17px';
      oCellPadding.innerHTML        = '&nbsp;';
      oLinhaCabecalhoValores.appendChild(oCellPadding);
      /*
       *Montamos a linha dos Valores
       */
      $('gridAcordoPrevisaoHeader'+me.iPosicao).appendChild(oLinhaCabecalhoValores);
      $('gridAcordoPrevisao'+me.iPosicao).style.width = iWidthTable;
      //$('tableRol').style.width = 1250;
      /**
       * Montamos a lista dos Itens do Contrato.
       */
      me.aItens = oRetorno.quadro.aItens;
      oDBHint = "";
      /**
       * Loop que monta cada linha de item exibida na view
       */
      oRetorno.quadro.aItens.each(function(oItem, iSeq) {

         var nTotalQuantidadeItem         = 0;
         var nTotalQuantidadeTotalPeriodo = 0;
         var oLinhaItem                   = document.createElement('tr');
         oLinhaItem.style.height          = '1em';

         var oCellCodigo = document.createElement('td');
         oCellCodigo.className        = 'linhaGrid';
         oCellCodigo.colSpan          = 1;
         oCellCodigo.style.width      = '30px';
         oCellCodigo.innerHTML        = '<b>'+oItem.ordem+'</b>';
         oCellCodigo.style.height     = '21px';
         oCellCodigo.style.whiteSpace = 'nowrap';
         oCellCodigo.style.textAlign  = 'right';
         oCellCodigo.style.backgroundColor = '#C9C9B5';
         oLinhaItem.appendChild(oCellCodigo);

         var oCellDescricao = document.createElement('td');
         oCellDescricao.className        = 'linhagrid';
         oCellDescricao.colSpan          = 1;
         oCellDescricao.style.width      = '200px';
         oCellDescricao.style.height     = '21px';
         oCellDescricao.id               = "tdDescricao_"+oItem.codigo;
         if (oItem.controlemensal) {
           oItem.descricao = "* "+oItem.descricao;
         }
         oCellDescricao.innerHTML        = '<div style="overflow:hidden;width:200px">'+oItem.descricao.urlDecode()+"</div>";
         oCellDescricao.style.whiteSpace = 'nowrap';
         oCellDescricao.style.textAlign  = 'left';
         oLinhaItem.appendChild(oCellDescricao);

         var oCellUnidade = document.createElement('td');
         oCellUnidade.className        = 'linhaGrid';
         oCellUnidade.colSpan          = 1;
         oCellUnidade.style.width      = '70px';
         oCellUnidade.innerHTML        = '<div style="overflow:hidden;width:70px">'+oItem.unidade.urlDecode()+"</div>";
         oCellUnidade.style.height     = '21px';
         oCellUnidade.style.whiteSpace = 'nowrap';
         oCellUnidade.style.textAlign  = 'left';
         oLinhaItem.appendChild(oCellUnidade);

         var oCellQtdeTotal = document.createElement('td');
         oCellQtdeTotal.className        = 'linhaGrid';
         oCellQtdeTotal.colSpan          = 1;
         oCellQtdeTotal.id               = 'qtdtotalitem'+oItem.codigo;
         oCellQtdeTotal.style.width      = '50px';
         oCellQtdeTotal.innerHTML        = '<div style="overflow:hidden;width:70px">'+0+"</div>";
         oCellQtdeTotal.style.height     = '21px';
         oCellQtdeTotal.style.whiteSpace = 'nowrap';
         oCellQtdeTotal.style.textAlign  = 'right';
         oLinhaItem.appendChild(oCellQtdeTotal);

         var oCellSaldoParaSerExecutado              = document.createElement('td');
         oCellSaldoParaSerExecutado.className        = 'linhaGrid';
         oCellSaldoParaSerExecutado.colSpan          = 1;
         oCellSaldoParaSerExecutado.id               = 'saldoaserexecutado'+oItem.codigo;
         oCellSaldoParaSerExecutado.innerHTML        = '<div style="overflow:hidden; width:70px">'+0+'</div>';
         oCellSaldoParaSerExecutado.style.width      = '50px';
         oCellSaldoParaSerExecutado.style.height     = '21px';
         oCellSaldoParaSerExecutado.style.whiteSpace = 'nowrap';
         oCellSaldoParaSerExecutado.style.textAlign  = 'right';
         oLinhaItem.appendChild(oCellSaldoParaSerExecutado);

         var oCellEmpenho                            = document.createElement('td');
         oCellEmpenho.className                      = 'linhaGrid';
         oCellEmpenho.colSpan                        = 1;
         oCellEmpenho.id                             = 'empenho'+oItem.codigoempenho;
         oCellEmpenho.innerHTML                      = '<div style="overflow:hidden; width:70px">'+oItem.codigoempenho+'</div>';
         oCellEmpenho.style.width                    = '50px';
         oCellEmpenho.style.height                   = '21px';
         oCellEmpenho.style.whiteSpace               = 'nowrap';
         oCellEmpenho.style.textAlign                = 'center';
         oLinhaItem.appendChild(oCellEmpenho);

         $('tbodyDescr').insertBefore(oLinhaItem, $('ffhackTable1'));
         nQuantidadeTotalItem  = new Number(0);

         /**
          * Montamos as colunas com os meses/valor de Previsao e valor Executado
          */
         var oLinhaValores               = document.createElement('tr');
         var nQuantidadeExecutadoItem    = 0; //Somador do valor executado por item
         var nQuantidadeParaSerExecutado = 0; //Somador do valor que deverá ser executado no item
         oLinhaValores.style.height      = '1em';
         /**
          * Loop que monta as colunas de períodos do item
          */
         oRetorno.quadro.aPeriodos.each(function(oPeriodo, iPeriodo) {


           var oCellQuantidadePrevista                   = document.createElement('td');
           oCellQuantidadePrevista.className             = 'linhagrid';
           oCellQuantidadePrevista.colSpan               = 1;
           oCellQuantidadePrevista.style.width           = (iTamanhoCelulaPeriodo/4)+'px';
           oCellQuantidadePrevista.innerHTML             = '&nbsp;';
           oCellQuantidadePrevista.style.height          = '20px';
           oCellQuantidadePrevista.style.whiteSpace      = 'nowrap';
           oCellQuantidadePrevista.style.backgroundColor = me.sColorSemMovimentacao;

           if (oPeriodo.lParalisado == true) {
             oCellQuantidadePrevista.style.backgroundColor = me.sColorParalisado;
           }

           oLinhaValores.appendChild(oCellQuantidadePrevista);

           var oCellValorPrevisto              = document.createElement('td');
           oCellValorPrevisto.id               = 'oCellValorPrevisto_'+iPeriodo;
           oCellValorPrevisto.className        = 'linhagrid';
           oCellValorPrevisto.colSpan          = 1;
           oCellValorPrevisto.style.width      = (iTamanhoCelulaPeriodo/4)+'px';
           oCellValorPrevisto.style.height     = '20px';
           oCellValorPrevisto.style.textAlign  = 'right';
           oCellValorPrevisto.innerHTML        = '&nbsp;';
           oCellValorPrevisto.style.whiteSpace = 'nowrap';
           oCellValorPrevisto.style.background = me.sColorSemMovimentacao;

           if (oPeriodo.lParalisado == true) {
             oCellValorPrevisto.style.backgroundColor = me.sColorParalisado;
           }
           oLinhaValores.appendChild(oCellValorPrevisto);

           var oCellQtdExecutada              = document.createElement('td');
           oCellQtdExecutada.className        = 'linhagrid';
           oCellQtdExecutada.colSpan          = 1;
           oCellQtdExecutada.style.width      = (iTamanhoCelulaPeriodo/4)+'px';
           oCellQtdExecutada.style.height     = '20px';
           oCellQtdExecutada.style.textAlign  = 'right';
           oCellQtdExecutada.innerHTML        = '&nbsp';
           oCellQtdExecutada.style.whiteSpace = 'nowrap';
           oCellQtdExecutada.style.background = me.sColorSemMovimentacao;
           if (oPeriodo.lParalisado == true) {
             oCellQtdExecutada.style.backgroundColor = me.sColorParalisado;
           }
           oLinhaValores.appendChild(oCellQtdExecutada);

           var oCellVlrExecutado              = document.createElement('td');
           oCellVlrExecutado.className        = 'linhagrid';
           oCellVlrExecutado.colSpan          = 1;
           oCellVlrExecutado.style.width      = (iTamanhoCelulaPeriodo/4)+'px';
           oCellVlrExecutado.style.height     = '20px';
           oCellVlrExecutado.style.textAlign  = 'right';
           oCellVlrExecutado.innerHTML        = '&nbsp';
           oCellVlrExecutado.style.whiteSpace = 'nowrap';
           oCellVlrExecutado.style.background = me.sColorSemMovimentacao;
           if (oPeriodo.lParalisado == true) {
             oCellVlrExecutado.style.backgroundColor = me.sColorParalisado;
           }
           oLinhaValores.appendChild(oCellVlrExecutado);

           var oCellAlteraPeriodo               = document.createElement('td');
           oCellAlteraPeriodo.className         = 'linhaGrid';
           oCellAlteraPeriodo.colSpan           = 1;
           /*
            * Controle para permitir alterar período somente quando a VIEW abrir no tipo de configuração de
            * cronograma
            */

           oCellAlteraPeriodo.innerHTML         = '<input type="button" id="btnAlteraPrevisaoItem" value="A" onclick="'+me.instance+'.exibirTelaAlteracaoPeriodo('+oPeriodo.codigo+', '+oItem.codigo+');">';
           if ( oPeriodo.lParalisado == true ) {
             oCellAlteraPeriodo.innerHTML       = '<input type="button" id="btnAlteraPrevisaoItem" disabled ="disabled" value="A">';
           }

           if (me.lMostrarLegenda) {
             oCellAlteraPeriodo.innerHTML       = '<input type="button" id="btnAlteraPrevisaoItem" disabled ="disabled" value="A">';
           }
           oCellAlteraPeriodo.style.width       = '20px';
           oCellAlteraPeriodo.style.height      = '20px';
           oCellAlteraPeriodo.style.textAlign   = 'center';
           oCellAlteraPeriodo.style.whiteSpace  = 'nowrap';
           oLinhaValores.appendChild(oCellAlteraPeriodo);

           /**
            * percorremos os peridos do item, e Verificamos quais periodos oItem possui. Para eles criamos inputs.
            */
            oItem.previsoes.each(function(oPrevisao, iPrev) {

               if (oPrevisao.codigovigencia == oPeriodo.codigo) {

                 var iPer   = oPrevisao.codigo;
                 var iQuant = oPrevisao.quantidadeprevista;
                 var nValor = oPrevisao.valor;

                 if (iQuant == '') {
                   iQuant = 0;
                 }
                 nQuantidadeTotalItem         += new Number(oPrevisao.quantidade);
                 nTotalQuantidadeTotalPeriodo += new Number(oPrevisao.quantidadeprevista);
                 nQuantidadeParaSerExecutado  += new Number(oPrevisao.saldo);
                 oCellQuantidadePrevista.id    = 'quantidade'+iPer;
                 oCellVlrExecutado.id          = 'oCellVlrExecutado_'+iPer;
                 oCellQtdExecutada.id          = 'oCellQtdExecutada_'+iPer;

                 if (!me.lReadOnly && oPeriodo.lParalisado == false ) {

                   /**
                    * Em caso de previsão de execução, libera campo para o usuário digitar a previsao
                    */
	                 var teste  = eval('oTxtValor'+iPer+' = new DBTextField("oTxtValor'+iPer+'", "oTxtValor'+iPer+'", '+iQuant+', 10)');
	                 teste.addStyle('width', '100%');
	                 teste.addStyle('height', '100%');
	                 teste.addStyle('text-align', 'right');
	                 teste.addStyle('border', '1px solid transparent');
	                 teste.addEvent("onFocus", ";"+me.instance+".liberaDigitacao(this);");
	                 teste.addEvent("onChange", ";"+me.instance+".alterarValorPeriodo(this.value, "+iPer+", "+iSeq+");");
	                 teste.addEvent("onKeyDown", ";"+me.instance+".verificaEsc(this, event);");
	                 teste.addEvent("onBlur", ";"+me.instance+".bloqueiaDigitacao(this);");
	                 teste.addEvent("onKeyPress", "return js_mask(event,\"0-9|.|-\")");
	                 oCellQuantidadePrevista.innerHTML = '&nbsp;';
	                 teste.show(oCellQuantidadePrevista);

                   if (oItem.tipocontrole == 4 || oItem.tipocontrole == 5) {
                     oCellQuantidadePrevista.innerHTML = "<div align='right'>0</div>";
                   }

                 } else {

                   /**
                    * Em caso de execução, cria um link para a window de execução
                    */
                   oCellQuantidadePrevista.style.height    = '21px';
                   oCellQuantidadePrevista.style.textAlign = 'right';
                   oCellQuantidadePrevista.innerHTML       = iQuant;
                   oCellQuantidadePrevista.style.padding   = '1px';
                   oCellValorPrevisto.style.height         = '21px';
                   oCellValorPrevisto.style.padding        = '1px';


                   oCellQuantidadePrevista.style.textDecoration = '';
                   oCellQuantidadePrevista.style.color          = '';
                   oCellQuantidadePrevista.style.cursor         = '';

                   if ( lExecucao ) {  //nao esta acessando pela rotina sexecucao manual

                     oCellQuantidadePrevista.style.textDecoration = 'underline';
                     oCellQuantidadePrevista.style.color          = 'blue';
                     oCellQuantidadePrevista.style.cursor         = 'pointer';
                   }


                   if (iQuant > 0 || oItem.tipocontrole == 4 || oItem.tipocontrole == 5) {

                     oCellQuantidadePrevista.observe('click', function () {

                       if (!me.onBeforePeriodoClick(iPer, iSeq, iQuant)) {
                         return false;
                       }
                       me.onPeriodoClick(iPer, iSeq, iQuant);
                     });
                   }
                 }


                 if (me.lMostrarLegenda) {

                   var sCorDefinida = null;
                   if (oPrevisao.saldo <= 0 && (oItem.tipocontrole != 4 && oItem.tipocontrole != 5)) {
                     sCorDefinida = me.sColorExecutado;
                   } else if (oPrevisao.quantidade == oPrevisao.saldo) {
                     sCorDefinida = me.sColorNaoExecutado;
                   } else if (oItem.nQuantidadeDisponivel == 0 && oPrevisao.executado != 0) {
                     sCorDefinida = me.sColorExecutado;
                   }

                   if ( (oItem.tipocontrole != 4 && oItem.tipocontrole != 5) &&
                        oPrevisao.valorexecutado != oPrevisao.valor &&
                        oPrevisao.executado != oPrevisao.quantidade &&
                        oPrevisao.executado != 0 &&
                        oPrevisao.valorexecutado != 0
                   ) {
                     sCorDefinida = me.sColorExecutadoParcialmente;
                   }

                   if ( (oItem.tipocontrole == 4 || oItem.tipocontrole == 5) && oPrevisao.execucoes.length > 0 ) {
                     sCorDefinida = me.sColorExecutadoParcialmente;
                   }

                   if (oPeriodo.lParalisado == true) {

                     sCorDefinida = me.sColorParalisado;
                     oCellAlteraPeriodo.innerHTML       = '<input type="button" id="btnAlteraPrevisaoItem" disabled ="disabled" value="A">';

                   }

                   /**
                    * Item com apenas um período previsto e saldo zero devemos pintar as células de verde (sColorExecutado)
                    */
                   if (oItem.previsoes.length == 1 &&
                       oPrevisao.valorunitario == oPrevisao.valorexecutado &&
                       oPrevisao.quantidade == oPrevisao.quantidadeprevista
                   ) {
                     sCorDefinida = me.sColorExecutado;
                   }

                   oCellQuantidadePrevista.style.backgroundColor 	= sCorDefinida;
                   oCellValorPrevisto.style.backgroundColor      	= sCorDefinida;
                   oCellVlrExecutado.style.backgroundColor        = sCorDefinida;
                   oCellQtdExecutada.style.backgroundColor        = sCorDefinida;

                 } else {

                   if (oPeriodo.lParalisado == true) {

                     oCellQuantidadePrevista.style.backgroundColor  = me.sColorParalisado;
                     oCellValorPrevisto.style.backgroundColor       = me.sColorParalisado;
                     oCellVlrExecutado.style.backgroundColor        = me.sColorParalisado;
                     oCellQtdExecutada.style.backgroundColor        = me.sColorParalisado;
                   } else {

                     oCellQuantidadePrevista.style.backgroundColor  = me.sColorNaoExecutado;
                     oCellValorPrevisto.style.backgroundColor       = me.sColorNaoExecutado;
                     oCellVlrExecutado.style.backgroundColor        = me.sColorNaoExecutado;
                     oCellQtdExecutada.style.backgroundColor        = me.sColorNaoExecutado;
                   }
                 }

                 oCellValorPrevisto.style.backgroundImage = '';
                 oCellValorPrevisto.innerHTML             = js_formatar(nValor, 'f');
                 oCellValorPrevisto.id                    = 'valortotal'+iPer;

                 oCellVlrExecutado.innerHTML  = new Number(oPrevisao.valorexecutado);
                 oCellQtdExecutada.innerHTML  = new Number(oPrevisao.executado);
                 if (oItem.tipocontrole == 4) {

                   oCellQtdExecutada.style.textAlign = 'center';
                   oCellQtdExecutada.innerHTML  = '-';
                 }

                 nQuantidadeExecutadoItem    += oPrevisao.executado; //Adicionamos o valor executado no período ao somador
               }
            });
         });

         if (me.iTipoCalculoQuantidade == 2  && !oItem.controlemensal) {
           nQuantidadeTotalItem = oItem.quantidade - nQuantidadeTotalItem;
         } else if (me.iTipoCalculoQuantidade == 2  && oItem.controlemensal) {
           nQuantidadeTotalItem = nQuantidadeTotalItem - nTotalQuantidadeTotalPeriodo;
         }

         /**
          * Adiciona os valores já executados na planilha
          */
         var sTotalDaDiferenca = oItem.nTotalQuantidadeExecutado;
         if (oItem.tipocontrole == 4) {
           sTotalDaDiferenca = oItem.nTotalValorExecutado;
         }
         $('qtdtotalitem'+oItem.codigo).innerHTML = sTotalDaDiferenca;
         $('tBodyFields').insertBefore(oLinhaValores, $('ffhackTable2'));

         /**
          * Adicionamos o saldo a ser executado na sua respectiva coluna
          */
         var nSaldoParaExecutar = nQuantidadeParaSerExecutado;
         if (oItem.tipocontrole == 4) {
           nSaldoParaExecutar = oItem.nValorDisponivel;
         } else if (oItem.tipocontrole == 5) {
           nSaldoParaExecutar = oItem.nQuantidadeDisponivel;
         }
         oCellSaldoParaSerExecutado.innerHTML = nSaldoParaExecutar;

         /*
          * Define os dados que serão apresentados no ToolTip de cada item do contrato.
          */
         var sTextEvent  = "<b>Unidade: </b>"+oItem.unidade.urlDecode()+"<br>";
             sTextEvent += "<b>Elemento: </b>"+oItem.estruturalelemento+" - "+oItem.descricaoelemento.urlDecode()+"<br>";
             sTextEvent += "<b>Observação: </b>"+oItem.observacao.urlDecode().urlDecode().urlDecode().urlDecode()+"<br>";
         var aEventsIn   = ["onmouseover"];
         var aEventsOut  = ["onmouseout"];
         var oDBHint     = eval("oDBHint_"+oItem.codigo+" = new DBHint('oDBHint_"+oItem.codigo+"')");
         oDBHint.setText(sTextEvent);
         oDBHint.setShowEvents(aEventsIn);
         oDBHint.setHideEvents(aEventsOut);
         oDBHint.setScrollElement(me.wndAcordoPrevisao.getContentContainer());
         oDBHint.make(oCellDescricao);
      });
   }

   /**
    * Altera os valores do Periodo é executado ao haver mudanças nas quantidades.
    * @param {float} nQuantidade Quantidade nova do periodo
    * @param {integer} iPeriodo Código do Periodo
    * @patam {integer} iItem Código de referencia do item.
    */
   this.alterarValorPeriodo = function(nQuantidade, iPeriodo, iItem) {

     var oItem            = me.aItens[iItem];
     var nTotalQuantidade = 0;
     var oParam           = new Object();
     me.oTxtSender        = $('oTxtValor'+iPeriodo);
     oParam.exec          = 'alterarQuantidade';
     oParam.nQuantidade   = nQuantidade;
     oParam.iPeriodo      = iPeriodo;
     oParam.iItem         = oItem.codigo;
     me.aItens[iItem].previsoes.each(function(oPrevisao, Id) {
       nTotalQuantidade += new Number(oPrevisao.quantidade);
     });
     if (oItem.controlemensal) {

       $('valortotal'+iPeriodo).innerHTML = js_formatar((js_round(nQuantidade*oItem.valortotal, 2)) / nTotalQuantidade, 'f');
     } else {
       $('valortotal'+iPeriodo).innerHTML = js_formatar((nQuantidade * oItem.valorunitario), 'f');
     }
     var oAjax          = new Ajax.Request(me.sRPC,
                                  {method: 'post',
                                   parameters: 'json='+Object.toJSON(oParam),
                                   onComplete: function (oAjax) {

                                     var oRetorno = eval("("+oAjax.responseText+")");
                                     if (oRetorno.status == 2) {

                                      me.oTxtSender.value = nValorObjeto;
                                      alert(oRetorno.message.urlDecode());
                                     } else {

                                       if (me.iTipoCalculoQuantidade == 2) {

	                                       var nDiferenca = 0;
	                                       oItem.previsoes.each(function(oPrevisao) {

	                                          if (oPrevisao.codigo == iPeriodo) {
	                                            oPrevisao.quantidadeprevista = oRetorno.oPrevisao.quantidadeprevista;
	                                          }
	                                          nDiferenca += (oPrevisao.quantidade - oPrevisao.quantidadeprevista)
	                                       });

	                                     }
                                     }
                                   }
                                  });
   }

   /**
    *Persiste as informações do item
    */
   this.salvar = function () {

     if (!me.onBeforeSave()) {
       return false;
     }
     js_divCarregando('Aguarde, salvando dados', 'msgBox');
     var oParam         = new Object();
     oParam.exec        = 'salvarPrevisao';
     var oAjax          = new Ajax.Request(me.sRPC,
                                   {method: 'post',
                                   parameters: 'json='+Object.toJSON(oParam),
                                   onComplete: function (oAjax) {

                                     js_removeObj('msgBox');
                                     var oRetorno = eval("("+oAjax.responseText+")");
                                     if (oRetorno.status == 2) {

                                      alert(oRetorno.message.urlDecode());
                                     } else {
                                       alert('Previsão salva com sucesso');
                                     }
                                   }
                                  });
   }

   /**
    * Define o texto de ajuda para o usuário, quando a classe é exibida como uma janela
    * @param {string} sAjuda texto da ajuda
    */
   this.setAjuda = function(sAjuda) {

     if (this.view == "") {
        me.oMessageBoard.setHelp(sAjuda+'<br><b>*</b> - Item Com controle mensal de execução.');
     }
   }

 	/**
	 * Libera  o input passado como parametro para a digitacao.
	 * é Retirado a mascara do valor e liberado para Edição
	 * é Colocado a Variavel nValorObjeto no escopo GLOBAL
	 * @param {HTMLINPUTElement}  object Objeto input
	 */
	 this.liberaDigitacao = function (object) {

	  nValorObjeto        = object.value;
	  object.value        = object.value;
	  object.style.border = '1px solid black';
	  object.readOnly     = false;
	  object.style.fontWeight = "bold";
	  object.select();

	}

	/**
	 * bloqueia  o input passado como parametro para a digitacao.
	 * É colocado  a mascara do valor e bloqueado para Edição
	 * @param {HTMLINPUTElement}  object Objeto input
	 */
	 this.bloqueiaDigitacao = function (object) {


	  object.readOnly         = true;
	  object.style.border     ='0px';
	  object.style.fontWeight = "normal";
	  object.value            = object.value;
	}

	/**
	 * Verifica se  o usuário cancelou a digitação dos valores.
	 * Caso foi cancelado, voltamos ao valor do objeto, e
	 * bloqueamos a digitação
	 * @param {HTMLINPUTElement} Objeto input a ser verificado s
	 * @param {Event} referencia ao Objeto Event
	 */
  this.verificaEsc = function (object,event) {

	  var teclaPressionada = event.which;
	  if (teclaPressionada == 27) {

	     object.value = nValorObjeto;
	     js_bloqueiaDigitacao(object);
	     event.preventDefault();
	  }
	}

	/**
	 * Chamamos o RPC para gerar um arquivo csv com a execução do acordo
	 */
	this.imprimirExecucao = function() {

	  var oParam            = new Object();
    oParam.exec           = 'imprimirExecucao';
    oParam.iCodigoPosicao = me.iPosicao;
    var oAjax             = new Ajax.Request(me.sRPC,
                                            {method: 'post',
                                             parameters: 'json='+Object.toJSON(oParam),
                                             onComplete:me.baixaRelatorio}); // dar uma outra função para o callback
	}

	/**
	 * Forçamos o download do arquivo csv gerado através do método imprimirExecucao
	 */
  this.baixaRelatorio = function(oAjax) {
    oRetorno = eval("("+oAjax.responseText+")");
    window.open("db_download.php?arquivo="+oRetorno.patharquivo);
  }

	if (!this.lReadOnly) {
  	$('btnSalvar').observe("click", me.salvar);
	}

  if (!this.lReadOnly) {

    $('btnCancelar').observe("click", function() {
      me.wndAcordoPrevisao.destroy();
    });
  }

	if (this.lMostrarLegenda) {

	  $$('.fieldLegenda').each( function ( oObj, iInd ){
	    $(oObj.id).style.display = '';
	  });
	}

  /**
   * Função utilizada para destruir uma janela.
   */
  this.fecharJanela = function () {
    me.wndAcordoPrevisao.destroy();
  };

  /**
   * Exibe a tela de alteração de período
   */
  this.exibirTelaAlteracaoPeriodo = function(iCodigoPeriodo, iCodigoItem) {

    /**
     * Fazemos a requisição RPC para buscarmos a informação do período
     */
    var oParam                  = new Object();
        oParam.exec             = 'buscaPeriodoParaAlteracao';
        oParam.iCodigoPeriodo   = iCodigoPeriodo;
        oParam.iCodigoItem      = iCodigoItem;
        oParam.nQuantidadeTotal = $('qtdtotalitem'+iCodigoItem).innerHTML;
        oParam.nQuantidadeSaldo = $('saldoaserexecutado'+iCodigoItem).innerHTML;
    var oAjax                   = new Ajax.Request(me.sRPC, {method: 'post',
                                                             parameters: 'json='+Object.toJSON(oParam),
                                                             onComplete:me.renderizaTelaAlteracaoPeriodo});
  }

  /**
   * Renderiza a tela que permite a alteração do período de um item
   */
  this.renderizaTelaAlteracaoPeriodo = function(oAjax){

    /**
     * Transformamos o retorno JSON em Array
     */
    oRetorno = eval("("+oAjax.responseText+")");

    if (typeof(wndAlteracaoPeriodo) != 'undefined') {
      wndAlteracaoPeriodo.destroy();
    }

    /**
     * Construímos a windowAux
     */
    wndAlteracaoPeriodo = new windowAux('wndAlteracaoPeriodo', 'Alteração Período', 600, 400);
    var sContent  = '<div style="height:70%;width:100%;">';
        sContent += '  <fieldset>';
        sContent += '    <legend><strong>Período</strong></legend>';
        sContent += '      <table width="100%">';
        sContent += '        <tr>';
        sContent += '          <td><strong>Saldo do item: </strong></td>';
        sContent += '          <td id="ctnSaldoItem"></td>';
        sContent += '          <td><strong>Saldo à executar: </strong></td>';
        sContent += '          <td id="ctnSaldoASerExecutado"></td>';
        sContent += '        </tr>';
        sContent += '        <tr>';
        sContent += '          <td><strong>Data inicial: </strong></td>';
        sContent += '          <td id="ctnDataInicial"></td>';
        sContent += '          <td><strong>Data Final: </strong></td>';
        sContent += '          <td id="ctnDataFinal"></td>';
        sContent += '        </tr>';
        sContent += '      </table>';
        sContent += '  </fieldset>';
        sContent += '  <center>';
        sContent += '    <input style="margin-top:10px;" type="button" value="Salvar" onclick="'+me.instance+'.salvaAlteracaoPeriodo('+oRetorno.iCodigoPeriodo+', '+oRetorno.iCodigoItem+');">';
        sContent += '  </center>';
        sContent += '</div>';
    wndAlteracaoPeriodo.setContent(sContent);


    /**
     * Construímos a messageBoard
     */
    var sTituloMessageBoard = "Período Selecionado: "+oRetorno.sDescricaoPeriodo;
    var sTextoMessageBoard  = "Informe o período para o item "+oRetorno.sDescricaoItem.urlDecode()+".";

    var oMessageBoard = new DBMessageBoard('msgboard1',
                                           sTituloMessageBoard,
                                           sTextoMessageBoard,
                                           $('windowwndAlteracaoPeriodo_content'));

    /**
     * Construímos os campos do formulário
     */
    oTxtSaldoItem           = new DBTextField('inputSaldoItem', 'oTxtSaldoItem', oRetorno.nQuantidadeTotal, 10);
    oTxtSaldoItem.lReadOnly = true;
    oTxtSaldoItem.show($('ctnSaldoItem'));

    oTxtSaldoASerExecutado           = new DBTextField('inputSaldoASerExecutado', 'oTxtSaldoASerExecutado', oRetorno.nQuantidadeSaldo, 10);
    oTxtSaldoASerExecutado.lReadOnly = true;
    oTxtSaldoASerExecutado.show($('ctnSaldoASerExecutado'));

    oTxtDataInicial = new DBTextFieldData('inputDataInicial', 'oTxtDataInicial', oRetorno.sDataInicial, '');
    oTxtDataInicial.show($('ctnDataInicial'));

    oTxtDataFinal = new DBTextFieldData('inputDataFinal', 'oTxtDataFinal', oRetorno.sDataFinal, '');
    oTxtDataFinal.show($('ctnDataFinal'));

    dtDataAnteriorInicial = oRetorno.sDataInicial
    dtDataAnteriorFinal   = oRetorno.sDataFinal;

    /**
     * Exibimos a windowaux para alteração
     */
    wndAlteracaoPeriodo.show();
    wndAlteracaoPeriodo.setChildOf(this.wndAcordoPrevisao);
  }

  /**
   * Salva a previsão de execução de um item
   */
  this.salvaAlteracaoPeriodo = function(iCodigoPeriodo, iCodigoItem) {

    /**
     * Validamos as data informadas no formulário
     */
    var aDataAnteriorInicial = dtDataAnteriorInicial.split("/");
    var aDataAnteriorFinal   = dtDataAnteriorFinal.split("/");
    var aDataAtualInicial    = $('inputDataInicial').value.split("/");
    var aDataAtualFinal      = $('inputDataFinal').value.split("/");
    /**
     * Verificamos se houve alteração nas datas fornecidas pelo usuário
     */
    if ((aDataAnteriorInicial[1] == aDataAtualInicial[1] && aDataAnteriorInicial[2] == aDataAtualInicial[2]) ||
        (aDataAnteriorFinal[1]    == aDataAtualFinal[1]   && aDataAnteriorFinal[2]   == aDataAtualFinal[2])) {

      alert("Altere as datas para concluir a transferência de previsão de execução.");
      return false;
    }

    if ($('inputDataInicial').value.trim() == "" || $('inputDataFinal').value.trim() == "") {

      alert('Favor informar as datas inicial e final para o período.');
      return false;
    }
    if (js_comparadata($('inputDataInicial').value.trim(), $('inputDataFinal').value.trim(), '>')){

      alert('A data inicial do período deve ser anterior à data final.');
      return false;
    }

    /**
     * Valida se o mes/ano informado para a data incial é a mesma informada para a data final
     */
    var aDataInicial = $F("inputDataInicial").split("/");
    var aDataFinal   = $F("inputDataFinal").split("/");
    if (aDataInicial[1] != aDataFinal[1]) {

      alert("O mês deve ser igual para a data inicial e data final.");
      return false;
    }
    if (aDataInicial[2] != aDataFinal[2]) {

      alert("O ano deve ser igual para a data inicial e data final.");
      return false;
    }


    /**
     * Passando na validação dos dados enviamos os mesmos em uma requisição RPC para a alteração do período
     */
    var oParam                  = new Object();
    oParam.exec                 = 'alteraPeriodo';
    oParam.iCodigoPeriodo       = iCodigoPeriodo;
    oParam.iCodigoAcordoPosicao = me.iPosicao
    oParam.iCodigoItem          = iCodigoItem;
    oParam.sDataInicial         = $('inputDataInicial').value;
    oParam.sDataFinal           = $('inputDataFinal').value;

    var oAjax             = new Ajax.Request(me.sRPC, {method: 'post',
                                                       parameters: 'json='+Object.toJSON(oParam),
                                                       onComplete: me.retornoAlteracaoPeriodo});
  }

  /**
   * Retorno da alteração do período.
   */
  this.retornoAlteracaoPeriodo = function(oAjax) {

    /**
     * Transformamos o retorno JSON em Array
     */
    oRetorno = eval("("+oAjax.responseText+")");
    alert(oRetorno.message.urlDecode());

    /*
     * Em caso de sucesso, destruímos a janela atual e acessamos novamente
     * para carregar as alterações efetuadas
     */
    if (oRetorno.status == 1) {

      wndAlteracaoPeriodo.destroy();
      me.fecharJanela();
      js_openPrevisao(me.iPosicao);
    }
  }
}

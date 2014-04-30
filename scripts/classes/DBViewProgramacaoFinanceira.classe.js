DBViewProgramacaoFinanceira = function (iCodigo, sNomeInstance, oNode, iWidth) {

  var me                  = this;
  this.iWidthPrincipal    = document.width/1.7;
  if (iWidth != null) {
    this.iWidthPrincipal = iWidth;
  } 
  this.iWidthParcela      = document.width/1.2;
  this.urlRPC             = 'cai1_programacaofinanceira.RPC.php';
  this.iCodigoProgramacao = '';
  if (iCodigo != null) {
    this.iCodigoProgramacao = iCodigo;
  }
  this.sNameInstance      = sNomeInstance;   
  this.iNumeroParcelas    = '';
  this.iDiaPagamento      = '';
  this.iPeriodicidade     = '';
  this.nValorTotal        = '';
  this.iMesInicial        = '';
  this.aParcelas          = new Array();  
  this.view               = '';
  if (oNode != null) {
    this.view            = oNode;
    this.iWidthPrincipal = document.width/1.0;
  }
  this.sCallBack = function (iCodigo) {
    return true;
  }
  
 /**
  * Monda windowAuxiliar
  */
  this.windowProgramacaoFinanceira = new windowAux('wndProgramacaoFinanceira', 'Programacao Financeira', me.iWidthPrincipal);
  
  var sContent  = '<div style="height:50%;">';
  sContent     += ' <fieldset>';
  sContent     += '  <legend>';
  sContent     += '    <b>Programação Financeira</b>';
  sContent     += '  </legend>';
  sContent     += ' <table border="0">';
  sContent     += '   <tr>';
  sContent     += '     <td style="width:10%"><b>Número Parcelas:</b></td>';
  sContent     += '     <td style="width:10%" id="ctnNumeroParcelas"></td>';
  sContent     += '     <td style="width:15%">&nbsp;</td>';
  sContent     += '     <td style="width:10%"><b>Periodicidade:</b></td>';
  sContent     += '     <td style="width:15%" id="ctnCboPeriocidade"></td>';
  sContent     += '   </tr>';
  sContent     += '   <tr>';
  sContent     += '     <td style="width:10%"><b>Dia Pagamento:</b></td>';
  sContent     += '     <td style="width:10%" id="ctnDiaPagamento"></td>';
  sContent     += '     <td style="width:15%">&nbsp;</td>';
  sContent     += '     <td style="width:10%"><b>Mês Inicial:</b></td>';
  sContent     += '     <td style="width:15%" id="ctnCboMesInicial"></td>';
  sContent     += '   </tr>';
  sContent     += ' </table>';
  sContent     += ' </fieldset>';
  sContent     += ' <table border="0" align="center" cellpadding="4">';
  sContent     += '   <tr align="center">';
  sContent     += '     <td><input type="button" id="btnProcessar" value="Processar"></td>';
  sContent     += '   </tr>';
  sContent     += ' </table>';
  sContent     += ' <fieldset>';
  sContent     += ' <div id="ctnGridParcelas"></div>';
  sContent     += ' </fieldset>';
  sContent     += ' <table border="0" align="center" cellpadding="3">';
  sContent     += '   <tr align="center">';
  sContent     += '     <td><input type="button" id="btnSalvar" value="Salvar"></td>';
  sContent     += '     <td><input type="button" id="btnNovaParcela" value="Nova Parcela"></td>';
  sContent     += '   </tr>';
  sContent     += ' </table>';
  sContent     += '</div>';
  
  
  if (me.view == "") {
    me.windowProgramacaoFinanceira.setContent(sContent);
    me.windowProgramacaoFinanceira.setShutDownFunction(function () {
      me.windowProgramacaoFinanceira.destroy();
    });
  } else {
  
    me.view.style.display = 'none';
    me.view.innerHTML     = sContent;
  }
  
 /**
  * Combo box pericidade
  */
  me.oCboPeriocidade = new DBComboBox("cboPeriocidade", "oCboPeriocidade");
  me.oCboPeriocidade.addItem("1", "Mensal");
  me.oCboPeriocidade.addItem("2", "Bimestral");
  me.oCboPeriocidade.addItem("3", "Trimestral");
  me.oCboPeriocidade.addItem("4", "Quadrimestral");
  me.oCboPeriocidade.addItem("5", "Semestral");
  me.oCboPeriocidade.addItem("6", "Anual");
  me.oCboPeriocidade.addStyle("width","100%");
  me.oCboPeriocidade.show($('ctnCboPeriocidade'));
  
 /**
  * Text box número parcelas
  */
  me.oTxtNumeroParcelas = new DBTextField("txtNumeroParcelas", sNomeInstance+".oTxtNumeroParcelas");
  me.oTxtNumeroParcelas.addStyle("width","100%");
  me.oTxtNumeroParcelas.setMaxLength(3);
  me.oTxtNumeroParcelas.addEvent("onKeyPress","return js_mask(event,\"0-9\")");
  me.oTxtNumeroParcelas.show($('ctnNumeroParcelas'));
  
 /**
  * Text box dia do pagamento
  */
  me.oTxtDiaPagamento = new DBTextField("txtDiaPagamento", sNomeInstance+".oTxtDiaPagamento");
  me.oTxtDiaPagamento.addStyle("width","100%");
  me.oTxtDiaPagamento.setMaxLength(2);
  me.oTxtDiaPagamento.addEvent("onKeyPress","return js_mask(event,\"0-9\")");
  me.oTxtDiaPagamento.show($('ctnDiaPagamento'));
  
 /**
  * Combo box com os meses do ano
  */
  me.oCboMesInicial = new DBComboBox("cboMesInicial", "oCboMesInicial");
  me.oCboMesInicial.addItem("01", "Janeiro");
  me.oCboMesInicial.addItem("02", "Fevereiro");
  me.oCboMesInicial.addItem("03", "Março");
  me.oCboMesInicial.addItem("04", "Abril");
  me.oCboMesInicial.addItem("05", "Maio");
  me.oCboMesInicial.addItem("06", "Junho");
  me.oCboMesInicial.addItem("07", "Julho");
  me.oCboMesInicial.addItem("08", "Agosto");
  me.oCboMesInicial.addItem("09", "Setembro");
  me.oCboMesInicial.addItem("10", "Outubro");
  me.oCboMesInicial.addItem("11", "Novembro");
  me.oCboMesInicial.addItem("12", "Dezembro");
  me.oCboMesInicial.addStyle("width","100%");
  me.oCboMesInicial.show($('ctnCboMesInicial'));
  
 /**
  * Cria grid parcelas
  */
  me.oGridParcelas              = new DBGrid("gridParcelas");
  me.oGridParcelas.nameInstance = sNomeInstance+".oGridParcelas"; 
  me.oGridParcelas.setCellWidth(new Array("25%", "20%", "30%", "25%")); 
  me.oGridParcelas.setCellAlign(new Array("center", "center", "center", "center"));                                        
  me.oGridParcelas.setHeader(new Array("Parcela", "Data Pagamento", "Valor", "Ação"));
  me.oGridParcelas.show($('ctnGridParcelas'));
  
 /**
  * Mensagem de ajuda nova parcela
  */
  if (me.view == "") {
    me.oMessageBoard   = new DBMessageBoard('msgBoardProgramacaoFinanceira',
                                            'Programação Financeira',
                                            'Informe a quantidade de parcelas, dia de pagamento, periodicidade e mês inicial',
                                            $('windowwndProgramacaoFinanceira_content')
                                            );   
    me.oMessageBoard.show();
  }
  
 /**
  * Carregar os dados dentro de uma windowAux ou mostrar o componente dentro do nó informado
  */
  this.show = function () {
    
    me.getDados();
    if (me.view == "") {
      me.windowProgramacaoFinanceira.show();
    } else {
      me.view.style.display = '';
    }
  }
  
 /** 
  * Seta o código da programação
  */
  this.setCodigo = function (iCodigo) {
    me.iCodigoProgramacao = iCodigo;
  }
  
 /** 
  * Retorna o código da programação
  */
  this.getCodigo = function () {
    return me.iCodigoProgramacao;
  }
  
 /**
  * Seta valor total das parcelas
  */
  this.setValorTotal = function (nValorTotal) {
    me.nValorTotal = nValorTotal;
  }
  
 /**
  * Processa parcelas programação financeira
  */
  this.processar = function () {

    var iParcelas     = me.oTxtNumeroParcelas.getValue();
    var iDiaPagamento = me.oTxtDiaPagamento.getValue();
    var nValorTotal   = me.nValorTotal;

    if (iParcelas == '') {
    
      alert('Informe o número de parcelas!');
      return false;
    }

    if (iDiaPagamento == '') {
    
      alert('Informe o dia de pagamento!');
      return false;
    }
    
    if (iDiaPagamento > 31 || iDiaPagamento < 1) {
    
      alert('Dia do pagamento é inválido!');
      return false;
    }

    js_divCarregando('Aguarde, processando...',"msgBoxProgramacaoFinanceira");
    
    var oParam            = new Object();
    oParam.exec           = 'processar';
    oParam.numparcelas    = iParcelas;
    oParam.periodicidade  = me.oCboPeriocidade.getValue();
    oParam.diapagamento   = iDiaPagamento;
    oParam.mesinicial     = me.oCboMesInicial.getValue();
    oParam.valortotal     = nValorTotal;
    
    var oAjax             = new Ajax.Request (me.urlRPC,
                                             {
                                               method: 'post',  
                                               parameters:'json='+Object.toJSON(oParam),
                                               onComplete: function (oAjax) {
                                               
                                                 js_removeObj("msgBoxProgramacaoFinanceira");
                                                 
                                                /*
                                                 * Trata o retorno da function processar()
                                                 */                                                 
                                                 var oRetorno  = eval("("+oAjax.responseText+")");
                                                 if (oRetorno.status == 0) {
                                                 
                                                   me.iPeriodicidade = oRetorno.iPeriodicidade;
                                                   me.iDiaPagamento  = oRetorno.iDiaPagamento;
                                                   me.iMesInicial    = oRetorno.iMesInicial;
                                                   me.preencheGridParcelas(oRetorno);
                                                   alert("Programação financeira processada com sucesso.");
                                                 } else {

                                                   alert(oRetorno.message.urlDecode());
                                                   me.getDados();
                                                   return false;
                                                 }                                     
                                               }
                                             });
  }
 
 /**
  * Inclui uma nova parcela
  */
  this.incluirParcela = function () {
    
    var nValor         = me.oTxtValor.getValue();
    var sDataPagamento = me.oTxtData.getValue();
    var iId            = me.oGridParcelas.getNumRows()-1;
    var iNovaParcela   = new Number(me.oGridParcelas.aRows[iId].aCells[0].getValue())+1;

    if (sDataPagamento == '') {
      
      alert('Informe uma data de pagamento!');
      return false;
    }
    
    if (nValor == '') {
    
      alert('Informe um valor!');
      return false;
    }

    js_divCarregando('Aguarde, incluíndo nova parcela...',"msgBoxProgramacaoFinanceira");
    
    var oParam          = new Object();
    oParam.exec         = 'incluirParcela';
    oParam.parcela      = iNovaParcela;
    oParam.dtpagamento  = sDataPagamento;
    oParam.valorparcela = js_strToFloat(nValor);
    
    var oAjax           = new Ajax.Request (me.urlRPC,
                                            {
                                               method: 'post',  
                                               parameters:'json='+Object.toJSON(oParam),
                                               onComplete: function (oAjax) {
                                               
                                                 js_removeObj("msgBoxProgramacaoFinanceira");
                                               
                                                /*
                                                 * Trata o retorno da function incluirParcela()
                                                 */
                                                 var oRetorno = eval("("+oAjax.responseText+")");
                                                 if (oRetorno.status == 1) {
   
                                                   alert(oRetorno.message.urlDecode());
                                                   return false;
                                                 }
                                                 me.preencheGridParcelas(oRetorno);
                                                 me.windowIncluirParcela.destroy();
                                               }  
                                            });
  }
  
 /**
  * Altera valores das parcelas
  */
  this.alterarParcela = function (iParcela, dtPagamento, nValorParcela) {
    
    var nValorParcela = js_strToFloat(nValorParcela);
    
    if (iParcela == '') {
      
      alert('Número da parcela não informado!');
      return false;
    }
    
    if (dtPagamento == '') {
      
      alert('Data de pagamento não informado!');
      return false;    
    }
    
    if (nValorParcela == '') {
      
      alert('Valor da parcela não informado!');
      return false;    
    }
    
    if (me.oGridParcelas.getNumRows() == 1) {
    
      if (nValorParcela < me.nValorTotal) {
        
        alert('Valor da parcela não pode ser menor que o saldo!');
        return false; 
      }
    }
    
    js_divCarregando('Aguarde, alterando parcela...',"msgBoxProgramacaoFinanceira");
    
    var oParam            = new Object();
    oParam.exec           = 'alterarParcela';
    oParam.parcela        = iParcela;
    oParam.dtpagamento    = dtPagamento;
    oParam.nvalorparcela  = nValorParcela;
    
    var oAjax             = new Ajax.Request (me.urlRPC,
                                             {
                                               method: 'post',  
                                               parameters:'json='+Object.toJSON(oParam),
                                               onComplete: function (oAjax) {
                                               
                                                 js_removeObj("msgBoxProgramacaoFinanceira");
                                                 
                                                /*
                                                 * Trata o retorno da function alterarParcela()
                                                 */
                                                 var oRetorno  = eval("("+oAjax.responseText+")");
                                                 if (oRetorno.status == 0) {
                                                 
                                                   me.preencheGridParcelas(oRetorno);
                                                   alert("Parcela alterada com sucesso.");
                                                 } else {

                                                   alert(oRetorno.message.urlDecode());
                                                   me.getDados();
                                                   return false;
                                                 }                                     
                                               } 
                                             });
  }
  
 /**
  * Exclui uma parcela da programação financeira
  */
  this.excluirParcela = function (iParcela) {
  
    if (iParcela == '') {
      
      alert('Parcela não informada!');
      return false;    
    }
    
    js_divCarregando('Aguarde, excluíndo parcela...',"msgBoxProgramacaoFinanceira");
    
    var oParam            = new Object();
    oParam.exec           = 'excluirParcela';
    oParam.parcela        = iParcela;
    
    var oAjax             = new Ajax.Request (me.urlRPC,
                                             {
                                               method: 'post',  
                                               parameters:'json='+Object.toJSON(oParam),
                                               onComplete: function (oAjax) {
                                               
                                                 js_removeObj("msgBoxProgramacaoFinanceira");
                                               
                                                /*
                                                 * Trata o retorno da function excluirParcela()
                                                 */
                                                 var oRetorno  = eval("("+oAjax.responseText+")");
                                                 if (oRetorno.status == 0) {
                                                 
                                                   me.preencheGridParcelas(oRetorno);
                                                   alert("Parcela excluída com sucesso.");
                                                 } else {

                                                   alert(oRetorno.message.urlDecode());
                                                   me.getDados();
                                                   return false;
                                                 }                                     
																					     }  
                                             });
  } 
  
 /**
  * Salva registros da programação financeira
  */
  this.salvarProgramacao = function () {
  
    js_divCarregando('Aguarde, salvando registro...',"msgBoxProgramacaoFinanceira");
    
    var oParam            = new Object();
    oParam.exec           = 'salvarProgramacao';
    oParam.codigo         = me.getCodigo();
    
    var oAjax             = new Ajax.Request (me.urlRPC,
                                             {
                                               method: 'post',  
                                               parameters:'json='+Object.toJSON(oParam),
                                               onComplete: function (oAjax) {
                                               
                                                 js_removeObj("msgBoxProgramacaoFinanceira");
                                               
                                                /*
                                                 * Trata o retorno da function salvarProgramacao()
                                                 */
                                                 var oRetorno  = eval("("+oAjax.responseText+")");
                                                 if (oRetorno.status == 0) {
                                                 
                                                   me.setCodigo(oRetorno.iCodigo);
                                                   me.preencheGridParcelas(oRetorno);
                                                   alert("Programação financeira "+me.getCodigo()+" salva com sucesso.");
                                                   me.sCallBack(oRetorno.iCodigo);
                                                 } else {

                                                   alert(oRetorno.message.urlDecode());
                                                   me.getDados();
                                                   return false;
                                                 }
                                               } 
                                             });
  } 
  
 /**
  * Retorna parcelas na programação financeira 
  * @return Array aParcelas
  */
  this.getParcelas = function () {

    var oParam       = new Object();
    oParam.exec      = 'getDados';
    oParam.codigo    = me.getCodigo();

    var oAjax        = new Ajax.Request (me.urlRPC,
                                           {
                                             method: 'post',  
                                             parameters:'json='+Object.toJSON(oParam),
                                             onComplete: function (oAjax) {
                                             
                                               var oRetorno = eval("("+oAjax.responseText+")");
                                               me.aParcelas = oRetorno.aParcelas;
                                             } 
                                           });                                       
    return me.aParcelas;
  }
  
 /**
  * Retorna todos os dados das parcelas
  */
  this.getDados = function () {
  
    js_divCarregando('Aguarde, buscando registros...',"msgBoxProgramacaoFinanceira");
    
    var oParam       = new Object();
    oParam.exec      = 'getDados';
    oParam.codigo    = me.getCodigo();
    
    var oAjax        = new Ajax.Request (me.urlRPC,
                                          {
                                            method: 'post',  
                                            parameters:'json='+Object.toJSON(oParam),
                                            onComplete: function (oAjax) {
                                              
                                              js_removeObj("msgBoxProgramacaoFinanceira");
                                              
                                             /*
                                              * Trata o retorno da function getdados()
                                              */
                                              var oRetorno = eval("("+oAjax.responseText+")");
                                              if (oRetorno.status == 1) {
                                              
																							  alert(oRetorno.message.urlDecode());
																							  me.getDados();
																							  return false;
																							}
																							
																							if (oRetorno.iCodigo != null) {
																							  me.setCodigo(oRetorno.iCodigo);
																							}																							
																							me.iPeriodicidade = oRetorno.iPeriodicidade;
                                              me.iDiaPagamento  = oRetorno.iDiaPagamento;
                                              me.iMesInicial    = oRetorno.iMesInicial;
																							me.preencheGridParcelas(oRetorno);
                                            }  
                                          });
  }
  
 /**
  * Busca valor para altera parcela na grid
  */
  this.getValorCampoGrid =  function (id) {
    
    var iParcela      = me.oGridParcelas.aRows[id].aCells[0].getValue();
    var dtPagamento   = me.oGridParcelas.aRows[id].aCells[1].content.getValue();
    var nValorParcela = me.oGridParcelas.aRows[id].aCells[2].content.getValue();
    
    me.alterarParcela(iParcela, dtPagamento, nValorParcela);
  }
 
 /**
  * Window adicionar nova parcela
  */
  this.windowNovaParcela = function () {
  
    if ($('wndIncluirParcela')) {
      return false;
    } 
    
   /**
    * Monta window auxiliar para incluir nova parcela
    */
    me.windowIncluirParcela = new windowAux('wndIncluirParcela', 'Incluir Parcela', me.iWidthParcela/3, 190);
    var sContent  = '<div style="">';
    sContent     += ' <fieldset>';
    sContent     += ' <table border="0">';
    sContent     += '   <tr>';
    sContent     += '     <td><b>Data:</b></td>';
    sContent     += '     <td id="ctnData"></td>';
    sContent     += '   </tr>';
    sContent     += '   <tr>';
    sContent     += '     <td><b>Valor:</b></td>';
    sContent     += '     <td id="ctnValor"></td>';
    sContent     += '   </tr>';
    sContent     += ' </table>';
    sContent     += ' </fieldset>';
    sContent     += ' <table border="0" align="center" cellpadding="3">';
    sContent     += '   <tr align="center">';
    sContent     += '     <td><input type="button" id="btnSalvarParcela" value="Salvar"></td>';
    sContent     += '   </tr>';
    sContent     += ' </table>';
    sContent     += '</div>';
    me.windowIncluirParcela.setContent(sContent);
    
    me.windowIncluirParcela.setShutDownFunction(function (){
      me.windowIncluirParcela.destroy();
    });
    
    if (me.view == "") {
      me.windowIncluirParcela.setChildOf(me.windowProgramacaoFinanceira);
    }
    
   /*
    * Mensagem da windowIncluirParcela
    */
    me.oMessageBoard = new DBMessageBoard('msgBoardIncluirParcela',
                                          'Nova Parcela',
                                          'Informe o número da parcela, e sua data de pagamento.',
                                          $('windowwndIncluirParcela_content')
                                          );   
    me.oMessageBoard.show();
    
    me.oTxtData = new DBTextFieldData("txtData", me.sNameInstance+".oTxtData");
    me.oTxtData.show($('ctnData'));
    
    me.oTxtValor = new DBTextField("txtValor", me.sNameInstance+".oTxtValor");
    me.oTxtValor.addStyle("width","47%");
    me.oTxtValor.addEvent("onBlur",me.sNameInstance+".formatarValor(this);");
    me.oTxtValor.show($('ctnValor'));
    
    me.windowIncluirParcela.show(250);
    $('btnSalvarParcela').observe('click', me.incluirParcela);
  }
  
 /**
  * Preenche grid parcelas
  */
  this.preencheGridParcelas = function (oRetorno) {
   
    var aParcelas = oRetorno.aParcelas;
    var iNumrows  = aParcelas.length;

    me.oGridParcelas.clearAll(true);        
    if (iNumrows > 0) {
  
      aParcelas.each(function (oParcela, id) {
         
        var sDataPagamento = js_formatar(oParcela.datapagamento, 'd');
        var flValor        = js_formatar(oParcela.valor, 'f');

        var aLinha  = new Array();
        aLinha[0]   = oParcela.parcela;
        aLinha[1]   = eval(me.sNameInstance+".txtDataPagamento"+
                                             id+"= new DBTextFieldData('txtDataPagamento"+
                                             id+"','"+me.sNameInstance+".txtDataPagamento"+
                                             id+"','"+sDataPagamento+"')");
                                             
        aLinha[2]   = eval(me.sNameInstance+".txtValor"+
                                             id+" = new DBTextField('txtValor"+
                                             id+"','"+me.sNameInstance+".txtValor"+
                                             id+"','"+flValor+"')");
                                                                                   
        aLinha[2].addStyle("text-align","right");
        aLinha[2].addStyle("height","100%");
        aLinha[2].addStyle("width","100%");
        aLinha[2].addStyle("border","1px solid transparent;");
        aLinha[2].addEvent("onKeyPress","return js_mask(event,\"0-9|.|-\")");
        aLinha[2].addEvent("onBlur",me.sNameInstance+".formatarValor(this);");
        
        aLinha[3]  = "<input type='button' id='btnAlterar' value='A' style='width: 50%;'";
        aLinha[3] += "       onclick='"+me.sNameInstance+".getValorCampoGrid("+id+");'>";
        aLinha[3] += "<input type='button' id='btnExcluir' value='E' style='width: 50%;'";
        aLinha[3] += "       onclick='"+me.sNameInstance+".excluirParcela("+oParcela.parcela+");'>";

        me.oGridParcelas.addRow(aLinha);
      });
    }
    
   /*
    * Verifica se retornou algum registro, para bloquear os campos
    */
    if (iNumrows == 0) {
    
      me.oGridParcelas.setStatus('Nenhum registro encontrado.');
      $('txtNumeroParcelas').disabled = false;
      $('txtDiaPagamento').disabled   = false;
      $('cboPeriocidade').disabled    = false;
      $('cboMesInicial').disabled     = false;
      $('btnProcessar').disabled      = false;
      $('btnNovaParcela').disabled    = true;
    } else {
    
      me.oGridParcelas.setStatus('');
      me.oTxtNumeroParcelas.setValue(iNumrows);
	    if (me.iDiaPagamento != '') {
	      me.oTxtDiaPagamento.setValue(me.iDiaPagamento);
	    }
	      
	    if (me.iPeriodicidade != '') {
	      me.oCboPeriocidade.setValue(me.iPeriodicidade);
	    }
	    
      $('txtNumeroParcelas').disabled = true;
      $('txtDiaPagamento').disabled   = true;
      $('cboPeriocidade').disabled    = true;
      $('cboMesInicial').disabled     = true;
      $('btnProcessar').disabled      = true;
      $('btnNovaParcela').disabled    = false;
    }
    
    if (me.iMesInicial != '') {
      me.oCboMesInicial.setValue(me.iMesInicial);
    }
    
    me.oGridParcelas.renderRows();
  }
  
 /**
  * Formata valor da parcela
	*/
	this.formatarValor = function (object) {
	  object.value  = js_formatar(object.value,'f');
	}
  
 /**
  * Adiciona eventos ao botão btnProcessar
  */
  $('btnProcessar').observe('click', function () {
  
    var iParcelas     = me.oTxtNumeroParcelas.getValue();
    var iDiaPagamento = me.oTxtDiaPagamento.getValue();
    var nValorTotal   = me.nValorTotal;

    if (iParcelas == '') {
    
      alert('Informe o número de parcelas!');
      return false;
    }

    if (iDiaPagamento == '') {
    
      alert('Informe o dia de pagamento!');
      return false;
    }
    
    if (iDiaPagamento > 31 || iDiaPagamento < 1) {
    
      alert('Dia do pagamento é inválido!');
      return false;
    }
    
    if (confirm('Deseja processar as parcelas?')) {
      me.processar();
    }
  });
    
 /**
  * Adiciona eventos ao botão btnSalvar
  */
  $('btnSalvar').observe('click', function () {
  
    var iParcelas     = me.oTxtNumeroParcelas.getValue();
    var iDiaPagamento = me.oTxtDiaPagamento.getValue();
    var nValorTotal   = me.nValorTotal;

    if (iParcelas == '') {
    
      alert('Informe o número de parcelas!');
      return false;
    }

    if (iDiaPagamento == '') {
    
      alert('Informe o dia de pagamento!');
      return false;
    }
    
    if (iDiaPagamento > 31 || iDiaPagamento < 1) {
    
      alert('Dia do pagamento é inválido!');
      return false;
    }
    
    if (confirm('Deseja salvar os registros da programação financeira?')) {
      me.salvarProgramacao();
    }
  });
  
 /**
  * Adiciona eventos ao botão btnNovaParcela
  */
  $('btnNovaParcela').observe('click', me.windowNovaParcela); 
  
 /**
  * Seta sCallBack para function de retorno sCallBack
  */
  this.setCallBack = function(sFunction) {
    me.sCallBack = sFunction;
  }
}
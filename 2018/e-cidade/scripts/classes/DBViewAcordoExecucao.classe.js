DBViewAcordoExecucao = function(oItem, iPeriodo, sInstance, oWindowPai, oNode) {
  
  var me              = this;
  me.sRPC             = 'aco4_acordoposicaoprevisao.RPC.php';
  me.sInstance        = sInstance;
  me.iPeriodo         = iPeriodo;  
  me.oItem            = oItem;  
  me.sId              = oItem.codigo+""+iPeriodo;
  
  me.aEmpenhos        = new Array();  
  var iWidth          = document.body.getWidth()/2;
  me.oPeriodo         = null;
  oItem.previsoes.each(function(oPeriodo, id) {
    
    if (oPeriodo.codigo == iPeriodo) {
       me.oPeriodo = oPeriodo;
    }
  });
  me.sTitulo      = 'Execução Item '+oItem.ordem+" para o período "+me.oPeriodo.descricao;
  me.sTituloHelp  = 'Execução Item '+oItem.descricao.urlDecode()+" para o período "+me.oPeriodo.descricao;    
  var iHeight     = document.innerHeight/2.5;
  if (oWindowPai != null || oWindowPai != "") {
  
    iHeight = oWindowPai.getHeight()/1.2;
  }
  me.view             = ''; 
  me.lReadOnly        = false;
  this.onSaveComplete = function (oRetorno) {
  
  } 

  if (oNode != null) {
    me.view = oNode;
  }
  this.onBeforeSave = function() {
    return true;
  }
  
  this.onAfterSave = function (oPeriodo, oItem) {
  
  }
  
  var sTipoCalculo;
  switch (me.oItem.tipocontrole) {
  
    case '1' : 
      sTipoCalculo  = "<b>Divisão Mensal das Quantidades</b></br>";
      sTipoCalculo += "<span style='padding-left:10px;'>";
      sTipoCalculo += "Divide as quantidades automaticamente entre nos períodos informados.</span>";
      break;
    case '2':
      sTipoCalculo  = "<b>Divisão Mensal de Valores (dias)</b></br>";
      sTipoCalculo += "<span style='padding-left:10px;'>";
      sTipoCalculo += "Divide os valores pelo número de dias e agrupa nos períodos.</span>";
      break;
    case '3':
      sTipoCalculo  = "<b>Divisão Mensal de Valores (mês)</b></br>";
      sTipoCalculo += "<span style='padding-left:10px;'>";
      sTipoCalculo += "Divide as quantidade pelo número de períodos (30 dias. Mês Comercial).</span>";
      break;
    case '4':
      sTipoCalculo  = "<b>Por Valor</b></br>";
      sTipoCalculo += "<span style='padding-left:10px;'>";
      sTipoCalculo += "Execução manual dos valores dentro dos períodos informados.</span>";
      break;
    case '5':
      sTipoCalculo  = "<b>Por Quantidade</b></br>";
      sTipoCalculo += "<span style='padding-left:10px;'>";
      sTipoCalculo += "Execução manual das quantidades dentro dos períodos.</span>";
      break;
  }
  
  me.sMsgTipoCalculo  = "<div style='background-color:#FFF; padding: 3px;'>";
  me.sMsgTipoCalculo += sTipoCalculo;
  me.sMsgTipoCalculo += "</div>";
  me.wndAcordoExecucao = new windowAux('wndAcordoExecucao'+me.iPeriodo, me.sTitulo, iWidth, iHeight);
  var sContent  = '<br><div style="height:70%;width:100%;">';
  sContent     += '<div id="rolagem" style="padding-top:10px;margin-top:-10px;height:120%;width:100%;overflow:auto;">';
  sContent     += '<div id="tabs" style="z-index:100;">';
  sContent     += '<a class="tabSelecionada" onclick="'+me.sInstance+'.showTabs(\'fldsDadosExecucao\')"';
  sContent     += '   id="tabfldsDadosExecucao">Execução</a>';
  //se a origem for manual, disponbilizamos a aba empenhos
  if ($('contratoOrigem').value == 3) {
    sContent     += '<a id="tabfldsEmpenho" onclick="'+me.sInstance+'.showTabs(\'fldsEmpenho\')" class="tab">Empenhos</a>';
  }
  sContent     += '<a id="tabfldsExecucoes" onclick="'+me.sInstance+'.showTabs(\'fldsExecucoes\')" ';
  sContent     += '   class="tab">Execuções Realizadas</a>';
  sContent     += '</div>';
  sContent     += '<div style="z-index:101;position:relative;border:1px outset white;text-align:center;height:100%">';
  
  /*
   * Dados da Execução
   */
  sContent     += '<center>';
  sContent     += '<div id="fldsDadosExecucao" style="display:table; margin-top: 10px;">';
  sContent     += ' <fieldset>';
  sContent     += '   <legend>';
  sContent     += '     <b>Dados da Execução</b>';
  sContent     += '   </legend>';
  sContent     += me.sMsgTipoCalculo;
  sContent     += '   <table border="0">';
  sContent     += '     <tr>';
  sContent     += '       <td style="width:87px">';
  var sTipoControle = "Quantidade";
  if (me.oItem.tipocontrole == 4) {
    sTipoControle = "Valor";
  }
  sContent     += '         <b>'+sTipoControle+':</b>';
  sContent     += '       </td>';
  sContent     += '       <td id="ctnTxtQuantidade">';
  sContent     += '       </td>';
  sContent     += '       <td>';
  sContent     += '         <b>Saldo:</b>'
  sContent     += '       </td>'
  sContent     += '       <td id="ctnTxtSaldo">';
  sContent     += '       </td>';
  sContent     += '     </tr>';
  sContent     += '     <tr style="display:none">';
  sContent     += '       <td>';
  sContent     += '         <b>Valor:</b>';
  sContent     += '       </td>'; 
  sContent     += '       <td id="ctnTxtValor">';
  sContent     += '       </td>'; 
  sContent     += '     <tr>';
  sContent     += '       <td colspan="4">';
  sContent     += '          <fieldset style="border:0px;border-top:2px groove white;">';
  sContent     += '            <legend><b>Período da Execução</b></legend>';
  sContent     += '            <table>';
  sContent     += '            <tr>';
  sContent     += '              <td style="width:80px">';
  sContent     += '                 <b>De:</b>';
  sContent     += '              </td>';
  sContent     += '              <td id="ctnTxtDataInicial">';
  sContent     += '              </td>';
  sContent     += '              <td>';
  sContent     += '                 <b>Até:</b>';
  sContent     += '              </td>';
  sContent     += '              <td id="ctnTxtDataFinal">';
  sContent     += '              </td>';
  sContent     += '            </tr>';
  sContent     += '          </table>';
  sContent     += '         </fieldset>';
  sContent     += '       </td>'
  sContent     += '     </tr>';
  sContent     += '     <tr>';
  sContent     += '       <td nowrap="nowrap"><b>Nota Fiscal:</b></td>';
  sContent     += '       <td id="ctnTxtNotaFiscal" colspan="3">';
  sContent     += '       </td>';
  sContent     += '     </tr>';
  sContent     += '     <tr>';
  sContent     += '       <td nowrap="nowrap"><b>Número Processo:</b></td>';
  sContent     += '       <td id="ctnTxtNumeroProcesso" colspan="3">';
  sContent     += '       </td>';
  sContent     += '     </tr>';
  sContent     += '     <tr>';
  sContent     += '       <td colspan="4">';
  sContent     += '         <fieldset>';
  sContent     += '           <legend><b>Observações</b></legend>';
  sContent     += '             <textarea name="sObservacao" id="sObservacao" style="width: 400px; height: 60px;"></textarea>';
  sContent     += '         </fieldset>';
  sContent     += '       </td>';
  sContent     += '     </tr>';
  sContent     += '   </table>';
  sContent     += ' </fieldset>';
  sContent     += ' </div>';
  
  /*
   * Vinculos de Empenho
   */
  sContent     += '<div id="fldsEmpenho" style="display:none; margin-top: 10px;">'
  sContent     += ' <fieldset>';
  sContent     += '   <legend><b>Vincular Empenhos</b>';
  sContent     += '   </legend>';
  sContent     += '   <table>';
  sContent     += '     <tr>';
  sContent     += '      <td style="width:87px">';
  sContent     += '         <b><a href="#" onclick="'+me.sInstance+'.pesquisaEmpenho(true);">Empenho:</a></b>';
  sContent     += '      </td>';
  sContent     += '      <td>';
  sContent     += '      <span  id="ctnTxtEmpenho">';
  sContent     += '      </span>';
  sContent     += '     <input type="button" id="btnVincularEmpenho" value="Vincular" onclick="'+me.sInstance+'.vincularEmpenhos()">';
  sContent     += '      </td>';
  sContent     += '     </tr>';
  sContent     += '     <tr>';
  sContent     += '      <td colspan="2" style="width:90%">';
  sContent     += '      <fieldset style="border:0px;border-top:2px groove white;">';
  sContent     += '        <legend>';
  sContent     += '         <b>Empenhos Cadastrados</b>';
  sContent     += '        </legend>';
  sContent     += '        <div id="ctnGridEmpenhos">';
  sContent     += '        </div>';
  sContent     += '      </fieldset>';
  sContent     += '      </td>';
  sContent     += '     </tr>';
  sContent     += '   </table>';
  sContent     += ' </fieldset>';
  sContent     += ' </div>';
  
  /*
   * Execuções Realizadas
   */
  sContent     += '<div id="fldsExecucoes" style="display:none; margin-top: 10px;">'
  sContent     += ' <fieldset>';
  sContent     += '   <legend><b>Execuções Realizadas</b>';
  sContent     += '   </legend>';
  sContent     += '   <table>';
  sContent     += '     <tr>';
  sContent     += '      <td colspan="2" style="width:90%">';
  sContent     += '      <fieldset style="border:0px;border-top:2px groove white;">';
  sContent     += '        <legend>';
  sContent     += '         <b>Execuções Realizadas</b>';
  sContent     += '        </legend>';
  sContent     += '        <div id="ctnGridExecucoes">';
  sContent     += '        </div>';
  sContent     += '      </fieldset>';
  sContent     += '      </td>';
  sContent     += '     </tr>';
  sContent     += '   </table>';
  sContent     += ' </fieldset>';
  sContent     += ' </div>';
  sContent     += '</center>';
  sContent     += '</div>';
  sContent     += '<center>';
  sContent     += '  <input type="button" id="btnSalvarExecucao" value="Salvar">';
  sContent     += '  <input type="button" id="btnCancelarExecucao" value="Cancelar" onclick="'+me.sInstance+'.wndAcordoExecucao.destroy()">';
  sContent     += '</center>';
  sContent     += '</div>';
  sContent     += '</div>';
  if (me.view == "") {
    
    //me.wndAcordoExecucao.allowCloseWithEsc(true);
    me.wndAcordoExecucao.setContent(sContent);
    me.wndAcordoExecucao.getContentContainer().style.overflow='hidden';
  } else {
  
    oNode.style.display = 'none';
    oNode.innerHTML     = sContent;
  }
  
  me.oPeriodo.saldo = (new Number($("quantidade"+me.iPeriodo).innerHTML) - new Number($("oCellQtdExecutada_"+me.iPeriodo).innerHTML));
  if (oItem.tipocontrole == 4) {
    me.oPeriodo.saldo = oItem.nValorDisponivel;
  } else if (oItem.tipocontrole == 5) {
    me.oPeriodo.saldo = oItem.nQuantidadeDisponivel;
  }
  
  me.oTxtQuantidade = new DBTextField(me.sInstance+".oTxtQuantidade", me.sInstance+".oTxtQuantidade", me.oPeriodo.saldo, 10);
  me.oTxtQuantidade.addEvent("onKeyPress", "return js_mask(event,\"0-9|,|.|-\")");
  me.oTxtQuantidade.addEvent("onKeyUp", me.sInstance+".alteraVirgula(this);");
  
  me.oTxtQuantidade.show($('ctnTxtQuantidade'));
  
  me.oTxtValor    = new DBTextField(me.sInstance+"oTxtValor.", me.sInstance+".oTxtValor", '', 10);
  me.oTxtValor.show($('ctnTxtValor'));
  me.oTxtValor.setReadOnly(true);
  
  me.oTxtSaldo    = new DBTextField(me.sInstance+".oTxtSaldo", me.sInstance+".oTxtSaldo", me.oPeriodo.saldo, 10);
  me.oTxtSaldo.show($('ctnTxtSaldo'));
  me.oTxtSaldo.setReadOnly(true);
  
  /*
   * Nota Fiscal
   */
  me.oTxtNotaFiscal = new DBTextField(me.sInstance+".oTxtNotaFiscal", me.sInstance+".oTxtNotaFiscal", me.oPeriodo.notafiscal, 40);
  me.oTxtNotaFiscal.show($('ctnTxtNotaFiscal'));
  
  me.oTxtNumeroProcesso = new DBTextField(me.sInstance+".oTxtNumeroProcesso", me.sInstance+".oTxtNumeroProcesso", me.oPeriodo.numeroprocesso, 40);
  me.oTxtNumeroProcesso.show($('ctnTxtNumeroProcesso'));
  
  me.oTxtEmpenho    = new DBTextField(me.sInstance+".oTxtEmpenho", me.sInstance+".oTxtEmpenho", '', 10);
  me.oTxtEmpenho.show($('ctnTxtEmpenho'));
  
  me.oTxtDataInicial    = new DBTextFieldData(me.sInstance+'.oTxtDataInicial', me.sInstance+".oTxtDataInicial", '', 10);
  me.oTxtDataInicial.show($('ctnTxtDataInicial'));
  
  me.oTxtDataFinal    = new DBTextFieldData(me.sInstance+'.oTxtDataFinal', me.sInstance+".oTxtDataFinal", '', 10);
  me.oTxtDataFinal.show($('ctnTxtDataFinal'));
  
  me.oGridEmpenhos           = new DBGrid("gridEmpenhos");
  me.oGridEmpenhos.sInstance = me.sInstance+".oGridEmpenhos";
  me.oGridEmpenhos.setHeight(80);
  me.oGridEmpenhos.setCellWidth(new Array('100', '100', '100', '100', '100', '50'));
  me.oGridEmpenhos.setHeader(new Array("Código", "Empenho", "Data", "Valor", "Liquidado", "Pago", "Ação"));
  me.oGridEmpenhos.show($('ctnGridEmpenhos'));
  
  me.oGridExecucoes           = new DBGrid("GridExecucoes");
  me.oGridExecucoes.sInstance = me.sInstance+".oGridExecucoes";
  me.oGridExecucoes.setHeight(100);
  me.oGridExecucoes.setCellWidth(new Array('100', '100', '100', '100', '100', '50'));
  me.oGridExecucoes.setCellAlign(new Array('right', 'center', 'center', 'right', 'right', 'center'));
  me.oGridExecucoes.setHeader(new Array("Código", "Data Inicial", "Data Final", "Quantidade", "Valor", "Ação"));
  me.oGridExecucoes.aHeaders[0].lDisplayed = false;
  me.oGridExecucoes.show($('ctnGridExecucoes'));
  
  /**
   * preenchemos a grid com os dados da Execução.
   */
  this.preencheGridexecucoes = function (aExecucoes) { 
	 
	  me.oGridExecucoes.clearAll(true);
	  var aDadosHintGrid = new Array();
	  var sTextEvent = "";
	  aExecucoes.each(function(oExecucao, id) {
	 
	    var aLinha = new Array();
	    aLinha[0]  = oExecucao.codigo;
	    aLinha[1]  = oExecucao.datainicial;
	    aLinha[2]  = oExecucao.datafinal;
	    aLinha[3]  = oExecucao.quantidade;
	    aLinha[4]  = oExecucao.valor;
	    aLinha[5]  = "<input type='button' value='E' style='width:100%' ";
	    aLinha[5] +=  "      onclick='"+me.sInstance+".excluirExecucao("+oExecucao.codigo+")'>";
	    
        sTextEvent  = "<b>Nota Fiscal: </b>"+oExecucao.notafiscal.urlDecode()+"<br>";
        sTextEvent += "<b>Processo: </b>"+oExecucao.processo.urlDecode()+"<br>";
        sTextEvent += "<b>Observação:</b>"+oExecucao.observacao.urlDecode()+"";
	    
	    me.oGridExecucoes.addRow(aLinha);
	    var oDadosHint       = new Object();
	    oDadosHint.idLinha   = me.oGridExecucoes.aRows[id].sId;
	    oDadosHint.sText     = sTextEvent;
	    oDadosHint.iExecucao = oExecucao.codigo;
	    aDadosHintGrid[id]   = oDadosHint;
	  });
	  me.oGridExecucoes.renderRows();

	  aDadosHintGrid.each(function(oHint, id) {
		  
	    var aEventsIn  = ["onmouseover"];
	    var aEventsOut = ["onmouseout"];
	    var oDBHint    = eval("oDBHint_"+oHint.iExecucao+" = new DBHint('oDBHint_"+oHint.iExecucao+"')");
	    oDBHint.setText(oHint.sText);
	    oDBHint.setShowEvents(aEventsIn);
	    oDBHint.setHideEvents(aEventsOut);
	    oDBHint.make($(oHint.idLinha));
	  });
  };  
  
  me.preencheGridexecucoes(me.oPeriodo.execucoes);
  /**
   * exclui a movimentação dos periodos  
   *
   */
  this.excluirExecucao = function(iExecucao) {
    
    if (!confirm('Esse Procedimento Ira Excluir a execução realizada.\nConfirma a operação?')) {
     return false;
    } 
    js_divCarregando('Aguarde, excluindo execução...', 'msgBox'); 
    var oParam       = new Object();
    oParam.exec      = 'excluirExecucao';
    oParam.iExecucao = iExecucao;
    oParam.iItem     = me.oItem.codigo;
    oParam.iPeriodo  = me.oPeriodo.codigo;
    var oAjax        = new Ajax.Request(me.sRPC,
                                       {method: 'post',
                                        parameters: 'json='+Object.toJSON(oParam), 
                                        onComplete: function (oAjax) {

                                           var oRetorno = eval("("+oAjax.responseText+")");
                                           if (oRetorno.stautus == 2) {
                                             alert(oRetorno.message.urlDecode());
                                           } else {

                                             js_removeObj('msgBox');
                                             alert('Execução excluida com sucesso!');
                                             me.wndAcordoExecucao.destroy();
                                             me.onAfterSave(me.oPeriodo, me.oItem);
                                           }                                        
                                         }
                                        });
  }
  
  if (me.view == "") {
    me.oMessageBoard   = new DBMessageBoard('msgBoardexecucao'+me.sId,
                                            me.sTituloHelp,
                                            '',
                                            me.wndAcordoExecucao.getContentContainer()
    
                                            );   
    me.oMessageBoard.show();
    $('msgBoardexecucao'+me.sId).style.width='99.7%';
  }                                          
  this.wndAcordoExecucao.setShutDownFunction(function (){
   
    me.wndAcordoExecucao.destroy();
    me.onAfterSave(me.oPeriodo, me.oItem);
  });
  if (oWindowPai != null || oWindowPai != "") {
    me.wndAcordoExecucao.setChildOf(oWindowPai);
  }
  this.show = function() {
    
    if (me.view == "") {
      me.wndAcordoExecucao.show();
    } else {
      me.view.style.display = '';
    }
  }
  
  this.pesquisaEmpenho = function (mostra) {
   
    if (mostra) {
     js_OpenJanelaIframe('', 
                         'db_iframe_empenho',
                         'func_empempenho.php?funcao_js=parent.'+me.sInstance+'.completaEmpenho|e60_anousu|e60_codemp',
                         'Escolha o Empenho',
                          true);
    } else {
    
     if (me.oTxtEmpenho.getValue() != '') { 
        js_OpenJanelaIframe('', 
                            'db_iframe_empenho', 
                            'func_conplano.php?pesquisa_chave='+me.oTxtEmpenho.getValue()+
                            '&funcao_js=parent.'+me.sInstance+'.completaEmpenho',
                            'Escolha o  Empenho',false);
      } else {
        me.oTxtEmpenho.setValue(''); 
      }
    }
    $('Jandb_iframe_empenho').style.zIndex='100000';
  }
  
  this.completaEmpenho = function() {
    
    if (arguments[1]) {
      if (typeof(arguments[1]) == "boolean") {
          me.oTxtEmpenho.setValue(''); 
      } else {
        
        me.oTxtEmpenho.setValue(arguments[1]+"/"+arguments[0]);
        db_iframe_empenho.hide();
      }
    }
  }
  
  this.vincularEmpenhos = function () {
    
    var sEmpenho    = me.oTxtEmpenho.getValue();
    var oParam      = new Object();
    oParam.exec     = 'getDadosEmpenho';
    oParam.codemp   = sEmpenho;
    var oAjax       = new Ajax.Request(me.sRPC,
                                   {method: 'post',
                                   parameters: 'json='+Object.toJSON(oParam), 
                                   onComplete: function (oAjax) {
                                     
                                     var oRetorno = eval("("+oAjax.responseText+")");
                                     if (oRetorno.status == 2) {
                                     
                                      me.oTxtEmpenho.setValue('');
                                      alert('Empenho '+sEmpenho+' não encontrado.');
                                      
                                     } else {
                                       
                                       me.aEmpenhos.push(oRetorno.oEmpenho);
                                       me.preencheGridempenhos();
                                     }
                                   }
                                  });
  
  }
  
  /**
   *Preenche os empenhos da execução do Empenho
   */
  this.preencheGridempenhos = function() {
  
    me.oGridEmpenhos.clearAll(true);
    me.aEmpenhos.each(function(oEmpenho, iSeq) {
      
      var aLinha = new Array();
      aLinha[0]  = oEmpenho.e60_numemp;
      aLinha[1]  = oEmpenho.e60_codemp+'/'+oEmpenho.e60_anousu;
      aLinha[2]  = js_formatar(oEmpenho.e60_emiss, 'd');
      aLinha[3]  = js_formatar(oEmpenho.e60_vlremp, 'f');
      aLinha[4]  = js_formatar(oEmpenho.e60_vlrliq, 'f');
      aLinha[5]  = js_formatar(oEmpenho.e60_vlrpag, 'f');
      aLinha[6]  = "<input type='button' value='E' onclick='"+me.sInstance+".removerEmpenho("+iSeq+")'>";
      me.oGridEmpenhos.addRow(aLinha);
    });
    me.oGridEmpenhos.renderRows();
  }
  
  
  this.removerEmpenho = function(iLinha) {
    
    if (!confirm('Confirma Remoção do empenho?')) {
      return false; 
    }
    me.aEmpenhos.splice(iLinha, 1);
    me.preencheGridempenhos(); 
  }
  /**
   *Salva os dados da execução
   */
  this.salvarMovimentacao = function() {
  
    if (me.oTxtQuantidade.getValue() == 0 ||me.oTxtQuantidade.getValue() == '') {
      
      alert('Informe a quantidade à ser executada');
      return false;
    }
    
    if (new Number(me.oTxtQuantidade.getValue()) > new Number(me.oTxtSaldo.getValue())) {
      
      alert("O total de execução não pode ser maior que o saldo disponível.");
      return false;
    }
    
    // se a origem for manual, disponbilizamos a aba empenhos
    if ($F(contratoOrigem) == 3) {
      
      if (me.aEmpenhos.length == 0) {
        
        if (!confirm('Nenhum empenho foi vinculado a execução. \nDeseja continuar assim mesmo?')) {
          return false;
        }
      }
    }
    
    
    js_divCarregando('Aguarde, salvando movimentação...','msgbox');
    var oParam                  = new Object();
    oParam.exec                 = 'salvarMovimentacaoEmpenhoManual';
    oParam.oPeriodo             = new Object();
    oParam.oPeriodo.aEmpenhos   = new Array();
    me.aEmpenhos.each(function (oEmp, id) {
      
      var oEmpenho = new Object();
      oEmpenho.numemp = oEmp.e60_numemp;
      oParam.oPeriodo.aEmpenhos.push(oEmpenho);
    });
    
    oParam.oPeriodo.iPeriodo    = me.iPeriodo;  
    oParam.iItem                = me.oItem.codigo;
    oParam.oPeriodo.datainicial = me.oTxtDataInicial.getValue();  
    oParam.oPeriodo.datafinal   = me.oTxtDataFinal.getValue();  
    oParam.quantidade           = me.oTxtQuantidade.getValue();
    oParam.notafiscal           = me.oTxtNotaFiscal.getValue();
    oParam.numeroprocesso       = me.oTxtNumeroProcesso.getValue();
    oParam.observacao           = $("sObservacao").value;
      
    var oAjax          = new Ajax.Request(me.sRPC,
                                      {method:'post',
                                       parameters:'json='+Object.toJSON(oParam),
                                       onComplete: me.retornoSalvarMovimentacao
                                      } 
                                     )

  }
  
  /**
   * Callback da funcao salvarMovimentacao
   * @private
   */
  this.retornoSalvarMovimentacao = function(oAjax) {
    
    js_removeObj('msgbox');
    var oRetorno  = eval("("+oAjax.responseText+")");
    if (oRetorno.status == 1) {
      
      me.oPeriodo.saldo     -= me.oTxtQuantidade.getValue();   
      me.oPeriodo.executado += me.oTxtQuantidade.getValue();   
      me.oPeriodo.execucoes  = oRetorno.execucoes;
      var iPeriodoExecutado  = oRetorno.iPeriodo;
      
      var oQtdTotalExecutada          = $("qtdtotalitem"+oRetorno.iCodigoAcordoItem);
      var nCalculoQuantidadeExecutada = (new Number(oQtdTotalExecutada.innerHTML) + new Number(oRetorno.nQuantidadeExecutada));
      oQtdTotalExecutada.innerHTML    = nCalculoQuantidadeExecutada;
      
      var oQtdSaldoExecutado          = $("saldoaserexecutado"+oRetorno.iCodigoAcordoItem);
      var nCalculoSaldoRestante       = (new Number(oQtdSaldoExecutado.innerHTML) - new Number(oRetorno.nQuantidadeExecutada));
      oQtdSaldoExecutado.innerHTML    = nCalculoSaldoRestante;
      
      $("oCellQtdExecutada_"+iPeriodoExecutado).innerHTML = '';
      $("oCellVlrExecutado_"+iPeriodoExecutado).innerHTML = '';
      
      var nValorExecutado = 0;
      var iQtdExecutada   = 0;
      oRetorno.execucoes.each(function (oExecucao, iIdExecucao) {
    	 
        nValorExecutado += new Number(oExecucao.valor);
        iQtdExecutada   += new Number(oExecucao.quantidade);
      });
      $("oCellQtdExecutada_"+iPeriodoExecutado).innerHTML = iQtdExecutada;
      $("oCellVlrExecutado_"+iPeriodoExecutado).innerHTML = js_formatar(nValorExecutado, 'f');
      
      /*
       * Seta uma cor no background dos periodos executados
       */
      if ($("oCellQtdExecutada_"+iPeriodoExecutado).innerHTML == $("quantidade"+iPeriodoExecutado).innerHTML) {
        $("oCellQtdExecutada_"+iPeriodoExecutado).style.backgroundColor = "#45B2A0";
        $("oCellVlrExecutado_"+iPeriodoExecutado).style.backgroundColor = "#45B2A0";
      } else {
        $("oCellQtdExecutada_"+iPeriodoExecutado).style.backgroundColor = "#F2F685";
        $("oCellVlrExecutado_"+iPeriodoExecutado).style.backgroundColor = "#F2F685";
      }
      
      alert('Movimentação salva com sucesso!');
      me.wndAcordoExecucao.destroy();
      me.onAfterSave(me.oPeriodo, me.oItem);
      
    } else {
      alert(oRetorno.message.urlDecode());
    }
  }
  $('btnSalvarExecucao').observe('click', me.salvarMovimentacao);
  
  /**
   * Mostra a aba passa como parametro
   * @param {string} sTab Nome da aba a ser Mostrada
   */
  this.showTabs = function(sTab) {
   
    $('btnSalvarExecucao').style.display   = '';
    $('btnCancelarExecucao').style.display = '';
   
    if (sTab == 'fldsExecucoes') {
    
      $('btnSalvarExecucao').style.display   = 'none';
      $('btnCancelarExecucao').style.display = 'none';
    }
    
    $('fldsDadosExecucao').style.display = 'none';
    $('tabfldsDadosExecucao').className  = 'tab';
    $('fldsEmpenho').style.display       = 'none';

    // se a origem for manual, disponbilizamos a aba empenhos
    if ($('contratoOrigem').value == 3) {
      $('tabfldsEmpenho').className        = 'tab';
    }
    $('fldsExecucoes').style.display     = 'none';
    $('tabfldsExecucoes').className      = 'tab';   
    $('tab'+sTab).className              = 'tabSelecionada';
    $(sTab).style.display                = 'table';
    
    switch (sTab) {
      
      case 'fldsDadosExecucao':
       
       me.oMessageBoard.setHelp('Informe a quantidade, e a data da execução.');
      break;
      case 'fldsEmpenho':
       
       me.oMessageBoard.setHelp('Informe os empenhos que foram vinculados a execução.');
      break;
      case 'fldsExecucoes':
       
       me.oMessageBoard.setHelp('Caso deseja excluir alguma movimentação, Verifique a linha e clique em Excluir.');
      break;
    }
  }
  me.showTabs('fldsDadosExecucao'); 
  
  /**
   *Desabilita os botoes do componente, deixando em modo somente leitura
   */
  this.setReadOnly = function(lReadOnly) {

    var aInputs = $$("div#"+me.wndAcordoExecucao.getContentContainer().id+' input[type="button"]');    
    aInputs.each(function (oInput, iSeq) {
      oInput.disabled = lReadOnly;
    });
  }
  
  /**
   * Define quais as Abas Deverão aparecer no sistema.
   */
  me.setTabs = function(aTabList) {
     
    var aTabs = $$('div#tabs a');
    aTabs.each(function(oTab, sId) {
      
      if (!js_search_in_array(aTabList, oTab.id)) {
         oTab.style.display='none';
      } else {
        oTab.style.display='';
      }
    }); 
  }
  
  
  /**
   * Altera os pontos por vírgula
   * @param   oObjeto
   * @returns {Boolean}
   */
  me.alteraVirgula = function (oObjeto) {
    
    if( js_countOccurs(oObjeto.value, ',') > 0 ) {
      oObjeto.value = oObjeto.value.replace(',', '.');
    }

    if( js_countOccurs(oObjeto.value, '.') > 1 ) {
      oObjeto.value = js_getInputValue(oObjeto.name);
      oObjeto.focus();
      return false;
    }
  }
}

var oEstiloCalendar= document.createElement("link");
    oEstiloCalendar.href = "estilos/DBtab.style.css";
    oEstiloCalendar.rel  = "stylesheet";
    oEstiloCalendar.type  = "text/css";
    document.getElementsByTagName("head")[0].appendChild(oEstiloCalendar);
    

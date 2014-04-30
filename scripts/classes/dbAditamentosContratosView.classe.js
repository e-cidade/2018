function dbViewAditamentoContrato(iTipoAditamento, sNomeInstance, oNode) {
  
  var me                    = this;
  this.lLiberaValounitario  = false;
  this.lLiberaDotacoes      = true;
  this.lLiberaQuantidade    = true;
  this.lLiberaNovosItens    = true;
  this.sLabelBotao          = '';
  this.lBloqueiaItem        = false;      
  this.nTotalITensIncluidos = 0;
  this.oWindowPeriodoItens  = "";
  this.aPeriodoItensAcordo  = new Array();
  
  switch(iTipoAditamento) {
   
    case 2: //Reequilibrio;
    
      this.lLiberaValounitario = true;
		  this.lLiberaDotacoes     = false;
		  this.lDatas              = false;
		  this.lLiberaQuantidade   = false;
		  this.lLiberaNovosItens   = false;
		  this.sLabelBotao         = ' Reequilibrio';
		  this.lBloqueiaItem       = true;
      break;
      
   case 4:
     
     this.lLiberaValounitario = true;
     this.lLiberaDotacoes     = false;
     this.lLiberaQuantidade   = true;
     this.lDatas              = false;
     this.lLiberaNovosItens   = true;
     this.sLabelBotao         = ' Aditamento';
     this.lBloqueiaItem       = false;
     break;   
     
    case 5:
     
     this.lLiberaValounitario = false;
     this.lLiberaDotacoes     = false;
     this.lLiberaQuantidade   = false;
     this.lDatas              = true;
     this.lLiberaNovosItens   = false;
     this.sLabelBotao         = ' Renovação';
     this.lBloqueiaItem       = false;
     break; 
     
    case 6:
     
     this.lLiberaValounitario = false;
     this.lLiberaDotacoes     = false;
     this.lLiberaQuantidade   = false;
     this.lDatas              = true;
     this.lLiberaNovosItens   = false;
     this.sLabelBotao         = ' Prazo';
     this.lBloqueiaItem       = true;
     break; 
  }
  this.iItensNovos     = 0;
  this.aPeriodoItensNovos = new Array()
  this.sInstance       = sNomeInstance;
  this.iTipoAditamento = iTipoAditamento; 
  this.sUrlRpc = 'con4_contratosaditamentos.RPC.php';
	oNode.style.display='none'; 
	this.sContainers  =    " <table style='' border='0' width='70%'>";
  this.sContainers +=    "   <tr>";
  this.sContainers +=    "     <td width='100%'> ";
  this.sContainers +=    "       <fieldset> ";
  this.sContainers +=    "         <legend><b>Informe o  Acordo</b></legend>";
  this.sContainers +=    "           <table width='100%' border='0'> ";

  this.sContainers +=    "            <tr> ";
  this.sContainers +=    "               <td style='width:10%;'> ";
  this.sContainers +=    "                  <a href='#' onclick='"+me.sInstance+".consultaAcordo();return false;'>";
  this.sContainers +=    "                  <b>Acordo:</b></a>";
  this.sContainers +=    "                </td>";
  this.sContainers +=    "                <td>";
  this.sContainers +=    "                   <span id='ctnTxtCodigoAcordo'></span>";
  this.sContainers +=    "                   <span id='ctnTxtDescricaoAcordo'></span>";
  this.sContainers +=    "                </td>";
  this.sContainers +=    "            </tr> ";

  /**
   * Numero do aditamento
   */
  this.sContainers +=    "            <tr> ";
  this.sContainers +=    "               <td> ";
  this.sContainers +=    "                  <b>Número aditamento:</b></a>";
  this.sContainers +=    "                </td>";
  this.sContainers +=    "                <td>";
  this.sContainers +=    "                   <span id='ctnTxtNumeroAditamento'></span>";
  this.sContainers +=    "                </td>";
  this.sContainers +=    "            </tr> ";

  this.sContainers +=    "              <tr> ";
  this.sContainers +=    "                <td colspan='2'>";
  this.sContainers +=    "                  <fieldset><legend><b>Vigência</b></legend> ";
  this.sContainers +=    "               <table border='0'> ";
  this.sContainers +=    "                  <tr> ";
  this.sContainers +=    "                    <td style='width:20%;'>";
  this.sContainers +=    "                       <b>Inicial:</b> ";
  this.sContainers +=    "                    </td> ";
  this.sContainers +=    "                    <td id='ctnVigenciaInicial'> ";
  this.sContainers +=    "                    </td> ";
  this.sContainers +=    "                    <td style='width:20%;'> ";
  this.sContainers +=    "                       &nbsp;&nbsp;<b>Final:</b> ";
  this.sContainers +=    "                    </td> ";
  this.sContainers +=    "                    <td id='ctnVigenciaFinal'> ";
  this.sContainers +=    "                    </td> ";
  this.sContainers +=    "                  </tr> ";
  this.sContainers +=    "                </table> ";
  this.sContainers +=    "                </fieldset> ";
  this.sContainers +=    "              </td> ";
  this.sContainers +=    "            </tr> ";
  this.sContainers +=    "            <tr> ";
  this.sContainers +=    "              <td colspan='2'> ";
  this.sContainers +=    "               <fieldset> ";
  this.sContainers +=    "                 <legend><b>Valores do Contrato</b></legend> ";
  this.sContainers +=    "                   <table> ";
  this.sContainers +=    "                     <tr> ";
  this.sContainers +=    "                       <td> ";
  this.sContainers +=    "                         <b>Valor Original:</b> ";
  this.sContainers +=    "                       </td> ";
  this.sContainers +=    "                       <td style='background-color: white; width: 100;text-align: right;'";
  this.sContainers +=    "                           id='ctnValorOriginal'> ";
  this.sContainers +=    "                       </td> ";
  this.sContainers +=    "                       <td> ";
  this.sContainers +=    "                         <b>Valor Atual:</b> ";
  this.sContainers +=    "                       </td> ";
  this.sContainers +=    "                       <td style='background-color: white; width: 100;text-align: right;'";
  this.sContainers +=    "                           id='ctnValorAtual'> ";
  this.sContainers +=    "                       </td> ";
  this.sContainers +=    "                     </tr> ";
  this.sContainers +=    "                   </table> ";
  this.sContainers +=    "                 </fieldset> ";
  this.sContainers +=    "               </td> ";
  this.sContainers +=    "             </tr>    ";     
  this.sContainers +=    "          </table> ";
  this.sContainers +=    "          </fieldset> ";
  this.sContainers +=    "         </td> ";
  this.sContainers +=    "       </tr> ";
  this.sContainers +=    "       <tr> ";
  this.sContainers +=    "         <td> ";
  this.sContainers +=    "           <fieldset> ";
  this.sContainers +=    "             <legend> ";
  this.sContainers +=    "               <b>  ";
  this.sContainers +=    "                 Itens ";
  this.sContainers +=    "               </b>  ";
  this.sContainers +=    "             </legend> ";
  this.sContainers +=    "             <div id='ctnGridItens'> ";
  this.sContainers +=    "           </fieldset> ";
  this.sContainers +=    "         </td> ";
  this.sContainers +=    "       </tr> ";
  this.sContainers +=    "       <tr> ";
  this.sContainers +=    "         <td colspan='2' style='text-align: center;'> ";
  this.sContainers +=    "            <input type='button' disabled value='Adicionar Itens' id='btnItens' style='display: none'> ";
  this.sContainers +=    "            <input type='button' disabled id='btnAditar' value='Salvar"+me.sLabelBotao+"'> ";
  this.sContainers +=    "            <input type='button' id='btnPesquisarAcordo' value='Pesquisar Acordo' > ";
  this.sContainers +=    "         </td> ";
  this.sContainers +=    "       </tr> ";
  this.sContainers +=    "     </table> ";
  
  oNode.innerHTML = this.sContainers;          
  oNode.style.display='';          
   
   /**
    * Pesquisa acordos
    */           
	this.pesquisaAcordo = function(lMostrar) {
	
	  if (lMostrar == true) {
	    
	    var sUrl = 'func_acordo.php?funcao_js=parent.js_mostraacordo1|ac16_sequencial|ac16_resumoobjeto&iTipoFiltro=4';
	    js_OpenJanelaIframe('top.corpo', 
	                        'db_iframe_acordo', 
	                        sUrl,
	                        'Pesquisar Acordo',
	                        true);
	  } else {
	  
	    if (me.oTxtCodigoAcordo.getValue() != '') { 
	    
	      var sUrl = 'func_acordo.php?descricao=true&pesquisa_chave='+me.oTxtCodigoAcordo.getValue()+
	                 '&funcao_js=parent.js_mostraacordo&iTipoFiltro=4';
	                 
	      js_OpenJanelaIframe('top.corpo',
	                          'db_iframe_acordo',
	                          sUrl,
	                          'Pesquisar Acordo',
	                          false);
	     } else {
	       me.oTxtCodigoAcordo.setValue('');
	     }
	  }
	}
  
	/**
	 * Retorno da pesquisa acordos
	 */
	js_mostraacordo = function(chave1,chave2,erro) {
	 
	  if (erro == true) {
	   
	    me.oTxtCodigoAcordo.setValue('');
	    me.oTxtDescricaoAcordo.setValue('');
	    $('oTxtDescricaoAcordo').focus(); 
	  } else {
	  
	    me.oTxtCodigoAcordo.setValue(chave1);
	    me.oTxtDescricaoAcordo.setValue(chave2);
	    me.pesquisarDadosAcordo(); 
	  }
	}

/**
 * Retorno da pesquisa acordos
 */
	js_mostraacordo1 = function (chave1,chave2) {
	
	  me.oTxtCodigoAcordo.setValue(chave1);
	  me.oTxtDescricaoAcordo.setValue(chave2);
	  db_iframe_acordo.hide();
	  me.pesquisarDadosAcordo();
	}

  this.consultaAcordo = function() {
  
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_consultaacordo',
                        'con4_consacordos003.php?ac16_sequencial='+me.oTxtCodigoAcordo.getValue(),
                        'Consulta Dados Acordo',
                        true);
  }
  this.pesquisao47_coddot = function(mostra) {
  
	  query='';
	  if (iElementoDotacao != '') {
	    query="elemento="+iElementoDotacao+"&";
	  } 
	
	  if(mostra==true){
	    js_OpenJanelaIframe('', 
	                        'db_iframe_orcdotacao',
	                        'func_permorcdotacao.php?'+query+'funcao_js=parent.'+me.sInstance+'.mostraorcdotacao1|o58_coddot',
	                        'Pesquisar Dotações',
	                        true,0);
	                        
	    $('Jandb_iframe_orcdotacao').style.zIndex='100000000';                        
	  }else{
	    js_OpenJanelaIframe('',
	                        'db_iframe_orcdotacao',
	                        'func_permorcdotacao.php?'+query+'pesquisa_chave='+document.form1.o47_coddot.value+
	                        '&funcao_js=parent.'+me.sInstance+'.mostraorcdotacao',
	                        'Pesquisar Dotações',
	                        false
	                        );
	  }
	}
	this.mostraorcdotacao = function(chave,erro) {
	
	  if (erro) { 
	    document.form1.o47_coddot.focus(); 
	    document.form1.o47_coddot.value = ''; 
	  }
	  me.getSaldoDotacao(chave);
	}

 this.mostraorcdotacao1 = function(chave1) {

  oTxtDotacao.setValue(chave1);
  db_iframe_orcdotacao.hide();
  $('Jandb_iframe_orcdotacao').style.zIndex='0';
  $('oTxtQuantidadeDotacao').focus();
  me.getSaldoDotacao(chave1);
  
}
  this.pesquisarDadosAcordo = function () {

	  if (me.oTxtCodigoAcordo.getValue() == "") {
	    
	    alert('Informe um acordo!');
	    return false;
	  } 
	  js_divCarregando('Aguarde, pesquisando acordos...', 'msgbox');
	  me.oGridItens.clearAll(true);
	  var oParam       = new Object();
	  oParam.exec      = 'getItensAditar';
	  oParam.renovacao = false;
	  if (me.iTipoAditamento == 5) {
	    oParam.renovacao = true;
	  }
	  oParam.iAcordo   = me.oTxtCodigoAcordo.getValue();
	  var oAjax        = new Ajax.Request(me.sUrlRpc,
	                                     {method:'post',
	                                      parameters:'json='+Object.toJSON(oParam),
	                                      onComplete: me.retornoGetDadosAcordo
	                                    } 
	                                   )
	}
	
	this.retornoGetDadosAcordo = function(oAjax) {
	
	  js_removeObj('msgbox');
	  var oRetorno = eval("("+oAjax.responseText+")");
	  if (oRetorno.status == 1) {
	    
	    $('btnAditar').disabled = false;
	    $('btnItens').disabled = false;
	    $('ctnValorOriginal').innerHTML = js_formatar(oRetorno.valores.valororiginal, "f"); 
	    $('ctnValorAtual').innerHTML    = js_formatar(oRetorno.valores.valoratual, "f"); 
	    var aDataInicial = oRetorno.datainicial.split("/");
	    var aDataFinal   = oRetorno.datafinal.split("/");
	    me.oTxtDataInicial.setData(aDataInicial[0], aDataInicial[1], aDataInicial[2]);
	    me.oTxtDataFinal.setData(aDataFinal[0], aDataFinal[1], aDataFinal[2]);
      me.oTxtNumeroAditamento.setValue('');

	    aItensPosicao = oRetorno.itens; 
	    me.oGridItens.renderRows();
	    me.preencheItens(aItensPosicao);
	    aItensPosicao.each(function (oItem, iLinha){
	       me.salvarInfoDotacoes(iLinha);
	    });
	  }
  }
  /**
   * monta a tela principal do aditamento
   */
  this.main = function() {

	   me.oTxtCodigoAcordo = new DBTextField('oTxtCodigoAcordo', me.sInstance+'.oTxtCodigoAcordo','', 10);
	   me.oTxtCodigoAcordo.addEvent("onChange",";"+me.sInstance+".pesquisaAcordo(false);");
	   me.oTxtCodigoAcordo.show($('ctnTxtCodigoAcordo'));
	   me.oTxtCodigoAcordo.setReadOnly(true);
	   
	   me.oTxtDescricaoAcordo = new DBTextField('oTxtDescricaoAcordo', me.sInstance+'.oTxtDescricaoAcordo','', 50);
	   me.oTxtDescricaoAcordo.show($('ctnTxtDescricaoAcordo'));
	   me.oTxtDescricaoAcordo.setReadOnly(true);

     /**
      * Numero do aditamento 
      */
	   me.oTxtNumeroAditamento = new DBTextField('oTxtNumeroAditamento', me.sInstance+'.oTxtNumeroAditamento','', 10);
	   me.oTxtNumeroAditamento.show($('ctnTxtNumeroAditamento'));
	   
	   me.oTxtDataInicial = new DBTextFieldData('oTxtDataInicial', me.sInstance+'.oTxtDataInicial','');
	   me.oTxtDataInicial.show($('ctnVigenciaInicial'));
	   if (!me.lDatas) {
  	   me.oTxtDataInicial.setReadOnly(true);  
	   }
	   me.oTxtDataFinal = new DBTextFieldData('oTxtDataFinal', me.sInstance+'.oTxtDataFinal','');
	   me.oTxtDataFinal.show($('ctnVigenciaFinal'));
	   if (!me.lDatas) {
       me.oTxtDataFinal.setReadOnly(true);  
     }

	   me.oGridItens = new DBGrid('oGridItens');
	   me.oGridItens.nameInstance = me.sInstance+'.oGridItens';
	   me.oGridItens.setCheckbox(0);
	   me.oGridItens.setCellAlign(new Array('right', 'left', "center", "center", "center", 'right', 'right'));
	   me.oGridItens.setCellWidth(new Array('5%', '50%', "8%", "13%", '13%', '13%', '10%', '10%', '10%', "10%"));
	   me.oGridItens.setHeader(new Array("Cod.", 'Item', "Períodos", 
	                                     'Vlr. Unit.', 'Qtde.', 'Vlr. Total', 'Dotações', 'Ação', "Seq"));
	   me.oGridItens.aHeaders[9].lDisplayed  = false;
	   me.oGridItens.setHeight(200);
	   me.oGridItens.show($('ctnGridItens'));
	   
	   $('btnAditar').observe('click', me.aditar);
	   $('btnPesquisarAcordo').observe('click', function() { me.pesquisaAcordo(true)});
	   
  }

/**
 * Controle das dotacoes do item.
 */
 
  this.ajusteDotacao = function (iLinha, iElemento) {
 
	  iElementoDotacao = iElemento ;
	  if ($('wndDotacoesItem')) {
	     return false;
	  }
	  oDadosItem  =  me.oGridItens.aRows[iLinha];
	  var iHeight = js_round((window.innerHeight/1.3), 0); 
	  var iWidth  = document.width/2; 
	  windowDotacaoItem = new windowAux('wndDotacoesItem',
	                                    'Dotações Item '+oDadosItem.aCells[2].getValue(),
	                                    iWidth,
	                                    iHeight
	                                   );
	  var sContent  = "<div>";
	  sContent     += "<fieldset><legend><b>Adicionar Dotação</b></legend>";
	  sContent     += "  <table>";
	  sContent     += "   <tr>";
	  sContent     += "     <td>";
	  sContent     += "     <a href='#' class='dbancora' style='text-decoration: underline;'"; 
	  sContent     += "       onclick='"+me.sInstance+".pesquisao47_coddot(true);'><b>Dotação:</b></a>";
	  sContent     += "     </td>";
	  sContent     += "     <td id='inputdotacao'></td>";
	  sContent     += "     <td>";
	  sContent     += "      <b>Saldo Dotação:</b>";
	  sContent     += "     </td>";
	  sContent     += "     <td id='inputsaldodotacao'></td>";
	  sContent     += "   </tr>";
	  sContent     += "   <tr>";
	  sContent     += "     <td>";
	  sContent     += "      <b>Quantidade:</b>";
	  sContent     += "     </td>";
	  sContent     += "     <td id='inputquantidadedotacao'></td>";
	  sContent     += "     <td>";
	  sContent     += "      <b>Valor:</b>";
	  sContent     += "     </td>";
	  sContent     += "     <td id='inputvalordotacao'></td>";
	  sContent     += "    </tr>";
	  sContent     += "    <tr>";
	  sContent     += "     <td colspan='4' style='text-align:center'>";
	  sContent     += "       <input type='button' value='Adicionar' id='btnSalvarDotacao'>";
	  sContent     += "     </td>";
	  sContent     += "    </tr>";
	  sContent     += "  </table>";
	  sContent     += "<fieldset>";
	  sContent     += "  <div id='cntgridDotacoes'>";
	  sContent     += "  </div>";
	  sContent     += "</fieldset>";
	  sContent     += "<center>";
	  sContent     += "<input type='button' id='btnSalvarInfoDot' value='Salvar' onclick=''>";
	  sContent     += "</center>";
	  windowDotacaoItem.setContent(sContent);
	  oMessageBoard = new DBMessageBoard('msgboard1', 
	                                    'Adicionar Dotacoes',
	                                    'Dotações Item '+oDadosItem.aCells[2].getValue()+" (valor: <b>"+
	                                    oDadosItem.aCells[6].getValue()+"</b>)",
	                                    $('windowwndDotacoesItem_content')
	                                    );
	  windowDotacaoItem.setShutDownFunction(function() {
	    windowDotacaoItem.destroy();
	  });
	  
	  $('btnSalvarInfoDot').observe("click", function() {
	      
	     var nTotalDotacoes = oGridDotacoes.sum(2, false);
	     if (nTotalDotacoes != js_strToFloat(oDadosItem.aCells[6].getValue())) {
	       
	      // alert('o Valor Total das Dotações não conferem com o total que está sendo autorizado no item!');
	      // return false;
	     }
	     aItensPosicao[iLinha].dotacoes.each(function (oDotacao, iDot) {
	        
	        var nValue = js_strToFloat(oGridDotacoes.aRows[iDot].aCells[2].getValue());
	        oDotacao.valorexecutar = nValue; 
	     });
	     me.oGridItens.aRows[iLinha].select(true);
	     windowDotacaoItem.destroy();
	  });
	  $('btnSalvarDotacao').observe("click", function( ) {me.saveDotacao(iLinha)});
	  oTxtDotacao = new  DBTextField('oTxtDotacao', 'oTxtDotacao','', 10);
	  oTxtDotacao.show($('inputdotacao'));                              
	  oTxtDotacao.setReadOnly(true);
	  
	  oTxtValorDotacao = new  DBTextField('oTxtValorDotacao', 'oTxtValorDotacao','', 10);
	  oTxtValorDotacao.show($('inputvalordotacao'));                              
	  oTxtValorDotacao.setReadOnly(true);
	  
	  oTxtQuantidadeDotacao = new  DBTextField('oTxtQuantidadeDotacao', 'oTxtQuantidadeDotacao','', 10);
	  var nValorMaximo   = js_strToFloat(oDadosItem.aCells[5].getValue());
	  var nValorUnitario = js_strToFloat(oDadosItem.aCells[4].getValue()).valueOf();
	  var sEvent         = ";validaValorDotacao(this,"+nValorMaximo+","+nValorUnitario+",\"oTxtValorDotacao\");"; 
	  oTxtQuantidadeDotacao.addEvent("onChange", sEvent);
	  oTxtQuantidadeDotacao.show($('inputquantidadedotacao'));
	  
	  oTxtSaldoDotacao = new  DBTextField('oTxtSaldoDotacao', 'oTxtSaldoDotacao','', 10);
	  oTxtSaldoDotacao.show($('inputsaldodotacao'));                              
	  oTxtSaldoDotacao.setReadOnly(true);
	  
	  oMessageBoard.show();  
	  oGridDotacoes              = new DBGrid('gridDotacoes');
	  oGridDotacoes.nameInstance = 'oGridDotacoes';
	  oGridDotacoes.setCellWidth(new Array('30%',  '40%', '40%'));
	  oGridDotacoes.setHeader(new Array("Dotação", "Valor Aut.", "valor"));
	  oGridDotacoes.setHeight(iHeight/3);
	  oGridDotacoes.setCellAlign(new Array("center", "right", "Center"));
	  oGridDotacoes.show($('cntgridDotacoes'));
	  oGridDotacoes.clearAll(true);
	  me.preencheGridDotacoes(iLinha);
	  windowDotacaoItem.show();
	  
	}

  this.preencheGridDotacoes = function(iLinha) {
 
	  oGridDotacoes.clearAll(true);
	  var nValor          = js_strToFloat(oDadosItem.aCells[5].getValue()); 
	  var nValorTotalItem = js_strToFloat(oDadosItem.aCells[5].getValue());
	  var nValorTotal     = nValor; 
	  aItensPosicao[iLinha].dotacoes.each(function (oDotacao, iDot) {
	
	     nValorDotacao = js_formatar(oDotacao.valorexecutar, "f");
	     aLinha    = new Array();
	     aLinha[0] = "<a href='#' onclick='"+me.sInstance+".mostraSaldo("+oDotacao.dotacao+");return false'>"+oDotacao.dotacao+"</a>";
	     aLinha[1] = oDotacao.valor;
	     aLinha[2] = eval("valordot"+iDot+" = new DBTextField('valordot"+iDot+"','valordot"+iDot+"','"+nValorDotacao+"')");
	     aLinha[2].addStyle("text-align","right");
	     aLinha[2].addStyle("height","100%");
	     aLinha[2].addStyle("width","100px");
	     aLinha[2].addStyle("border","1px solid transparent;");
	     aLinha[2].addEvent("onBlur","valordot"+iDot+".sValue=this.value;");  
	     aLinha[2].addEvent("onBlur",me.sInstance+".ajustaValorDot(this,"+iDot+");");
	     aLinha[2].addEvent("onBlur","js_bloqueiaDigitacao(this, true);");    
	     aLinha[2].addEvent("onFocus","js_liberaDigitacao(this, true);");  
	     aLinha[2].addEvent("onKeyPress","return js_mask(event,\"0-9|.|-\")");  
	     aLinha[2].addEvent("onKeyDown","return js_verifica(this,event,true)")
	     oGridDotacoes.addRow(aLinha); 
	  });
	  oGridDotacoes.renderRows(); 
	
	}
	
	this.getSaldoDotacao = function(iDotacao) {
	
	  var oParam         = new Object();
	  oParam.exec        = "getSaldoDotacao";
	  oParam.iDotacao    = iDotacao;
	  js_divCarregando('Aguarde, pesquisando saldo Dotações', 'msgBox');
	  var oAjax   = new Ajax.Request(
	                             "con4_contratos.RPC.php", 
	                             {
	                              method    : 'post', 
	                              parameters: 'json='+Object.toJSON(oParam), 
	                              onComplete: me.retornoGetSaldotacao
	                              }
	                            );
	
	}

  me.mostraSaldo = function (chave){

	  var arq = 'func_saldoorcdotacao.php?o58_coddot='+chave 
	  js_OpenJanelaIframe('top.corpo','db_iframe_saldos',arq,'Saldo da dotação',true);
	  $('Jandb_iframe_saldos').style.zIndex='1500000';
	}
  
  this.retornoGetSaldotacao = function(oAjax) {

	  js_removeObj('msgBox');
	  var oRetorno = eval("("+oAjax.responseText+")");
	  oTxtSaldoDotacao.setValue(js_formatar(oRetorno.saldofinal ,"f"));
	}
/**
 * realiza a redistribuição do valor do item nas dotação do mesmo  
 */
  me.ajustaValorDot = function(Obj, iDot) {

	  var nValor         = new Number(Obj.value);
	  var nTotalDotacoes = oGridDotacoes.sum(2, false);
	  var nValorAut      = js_strToFloat(oDadosItem.aCells[5].getValue());
	  if (nValor > nValorAut) {
	    oGridDotacoes.aRows[iDot].aCells[2].content.setValue(nValorObjeto);
	  } else if (nTotalDotacoes > nValorAut) {
	    oGridDotacoes.aRows[iDot].aCells[2].content.setValue(nValorObjeto);
	  }
	}
/**
 * calcula os valores da dotação conforme o valor modificado pelo usuario
 */
  this.salvarInfoDotacoes = function (iLinha) {
  
	  var oDadosItem      = me.oGridItens.aRows[iLinha];
	  var nValor          = js_strToFloat(oDadosItem.aCells[5].getValue()); 
	  var nValorTotalItem = js_strToFloat(oDadosItem.aCells[5].getValue());
	  var nValorTotal     = nValor;
	  aItensPosicao[iLinha].dotacoes.each(function (oDotacao, iDot) {
	    
	    var nPercentual    = (new Number(oDotacao.valor) * 100)/nValorTotalItem;
	    if (nValorTotalItem == 0) {
	      nPercentual = 0;
	    } 
	    var nValorDotacao  = js_round((nValor * nPercentual)/100,2); 
          
	    nValorTotal        -= nValorDotacao;
	    if (iDot == aItensPosicao[iLinha].dotacoes.length -1) {
	       
	      if (nValorTotal != nValor) {
	        nValorDotacao += nValorTotal; 
	      } 
	    }
	    if (nValorDotacao < 0 ) {
	     nValorDotacao = 0;
	    }
	    aItensPosicao[iLinha].dotacoes[iDot].valorexecutar = js_round(nValorDotacao,2);
	    aItensPosicao[iLinha].dotacoes[iDot].valor         = js_round(nValorDotacao,2);
	  });
	}

  /**
   * Função disparada toda vez que o usuário altera o valor de um item do contrato.
   */
	this.calculaValor = function (obj, iLinha) {
	  
	  var aLinha = me.oGridItens.aRows[iLinha];
	  
	  
	  if (aLinha.aCells[4].getValue() <= 0) {
	    
	    aLinha.aCells[5].setValue(aItensPosicao[iLinha].valor);
	    obj.value = aItensPosicao[iLinha].valor
	    aLinha.aCells[4].content.setValue(aItensPosicao[iLinha].quantidade);
	  } else {
	    
	    var nValorTotal = new Number(js_strToFloat(aLinha.aCells[4].getValue()) * js_strToFloat(aLinha.aCells[5].getValue()));
	    $("oGridItensrow"+iLinha+"cell5").innerHTML = js_formatar(new String(nValorTotal), "f");
	  }
	  me.salvarInfoDotacoes(iLinha);
	}

  this.calculaValorUnitario = function(obj, iLinha) {
  
	  var aLinha = me.oGridItens.aRows[iLinha];
	  if (aLinha.aCells[4].getValue() <= 0) {
	    
	    aLinha.aCells[5].setValue(aItensPosicao[iLinha].valor);
	    obj.value = aItensPosicao[iLinha].valorunitario
	    aLinha.aCells[4].content.setValue(aItensPosicao[iLinha].valounitario);
	  } else {
	    
	    var nValorTotal = new Number(js_strToFloat(aLinha.aCells[4].getValue()) * js_strToFloat(aLinha.aCells[5].getValue()));
	    $("oGridItensrow"+iLinha+"cell5").innerHTML = js_formatar(new String(nValorTotal), "f");
	  }
	  me.salvarInfoDotacoes(iLinha);
	  
	}

  this.saveDotacao = function(iLinha) {

	  if (oTxtDotacao.getValue() == "") {
	    
	    alert('Informe a dotação!');
	    js_pesquisao47_coddot(true);
	    return false;
	    
	  }
	  if (new Number(oTxtQuantidadeDotacao.getValue()) == 0 ) {
	    
	    alert('Informe uma quantidade para o item!');
	    $('oTxtQuantidadeDotacao').focus();
	    return false;
	  }
	  var oDotacao           = new Object();
	  oDotacao.dotacao       = oTxtDotacao.getValue();
	  oDotacao.quantidade    = oTxtQuantidadeDotacao.getValue();
	  oDotacao.valor         = js_strToFloat(oTxtValorDotacao.getValue());
	  oDotacao.valorexecutar = js_strToFloat(oTxtValorDotacao.getValue())
	  oDotacao.executado     = 0;
	  oDotacao.reserva       = '';
	  oDotacao.valorreserva  = '';
	  oDotacao.ano           = '';
	  aItensPosicao[iLinha].dotacoes.push(oDotacao);
	  me.preencheGridDotacoes(iLinha);
	}
	
  me.show = function () {
    
    me.main(); 
    me.pesquisaAcordo(true);
  }

  /**
   * Valida os periodos do item
   * - verifica se existem execucao para as datas do periodo do item
   *
   * @param array aPeriodosItem
   */
  this.validarPeriodosExecutados = function(aPeriodosItem) {

    js_divCarregando('Aguarde, aditando contrato...', 'msgBox');

    var oParametros = new Object();

    oParametros.exec      = "validarPeriodosExecutados";
    oParametros.aPeriodos = aPeriodosItem;
    var lRetorno = true;

    var oAjax  = new Ajax.Request(
        me.sUrlRpc,
        {
          method       :'post',
          asynchronous : false,
          parameters   :'json='+Object.toJSON(oParametros),
          onComplete   : function(oAjax) {

            js_removeObj('msgBox');
            var oRetorno = eval("("+oAjax.responseText+")");
            var sMensagem = oRetorno.message.urlDecode();

            /**
             * Erro no RPC
             */
            if (oRetorno.status > 1) {

              alert(sMensagem);
              lRetorno = false;
            }
          }
        }
    );


    return lRetorno;
  }
  
  this.aditar = function() {
  
    var aItens = me.oGridItens.getSelection("object");
    if (aItens.length == 0) {
    
      alert('Nenhum item Selecionado');
      return false;
    }
    js_divCarregando('Aguarde, aditando contrato...', 'msgBox');
    var oParam            = new Object();
    oParam.exec           = "processarAditamento";
    oParam.aItens         = new Array();
    oParam.datainicial    = me.oTxtDataInicial.getValue();
    oParam.datafinal      = me.oTxtDataFinal.getValue();
    oParam.tipoaditamento = me.iTipoAditamento;
    oParam.sNumeroAditamento = me.oTxtNumeroAditamento.getValue();
    
    for (var i = 0; i < aItens.length; i++) {
     
	    with (aItens[i]) {
	     
	     var aPeriodoItens    = new Array();
	     var oItem            = new Object();
	     var oDadosItem       = aItensPosicao[aCells[9].getValue()];
	     oItem.codigo         = oDadosItem.codigo;
	     oItem.quantidade     = aCells[4].getValue();
	     oItem.codigoelemento = oDadosItem.codigoelemento;
	     oItem.codigoitem     = oDadosItem.codigoitem;
	     oItem.unidade        = oDadosItem.unidade;
	     oItem.resumo         = encodeURIComponent(tagString(oDadosItem.resumo));
	     oItem.valorunitario  = js_strToFloat(aCells[4].getValue());
	     oItem.valor          = js_strToFloat(aCells[6].getValue());
	     oItem.quantidade     = js_strToFloat(aCells[5].getValue());
	     
	     /*
	      * Adicionamos em um array de periodos o periodo alterado pelo usuário
	      */
	     me.aPeriodoItensAcordo[i].each(function (oLinha, iLinha) {
	       
	       var oPeriodoItem             = new Object();
	       oPeriodoItem.dtDataInicial   = js_formatar(oLinha.dtDataInicial, "d");
	       oPeriodoItem.dtDataFinal     = js_formatar(oLinha.dtDataFinal, "d");
	       oPeriodoItem.ac41_sequencial = oLinha.ac41_sequencial;
	       aPeriodoItens.push(oPeriodoItem);
	     });
	     oItem.aPeriodos = aPeriodoItens;
	     /**
	      * Validamos o total do item com as dotacoes. 
	      * caso o valor seja diferetntes , devemos cancelar a operação e avisar o usuário 
	      */
	      var nValorDotacao = 0;
	      oDadosItem.dotacoes.each(function(oDotacao, id) {
	      
	         nValorDotacao += oDotacao.valorexecutar;
	         oDotacao.valor = oDotacao.valorexecutar;
	      });
	      oItem.dotacoes = oDadosItem.dotacoes;
	      oParam.aItens.push(oItem);       
	    }
    }
    var oAjax  = new Ajax.Request(me.sUrlRpc,
                                 {method:'post',
                                  parameters:'json='+Object.toJSON(oParam),
                                  onComplete: me.retornoAditar
                               } 
                              );
  }
  
  this.retornoAditar = function(oAjax) { 
    
    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    if (oRetorno.status == 1) {
    
      alert("Aditamento realizado com sucesso!");
      me.pesquisarDadosAcordo();
    } else {
      alert(oRetorno.message.urlDecode().replace(/\\n/g, '\n'));
    }
  }
  
  this.novoItem = function() {
     
    var iHeight = js_round((window.innerHeight/1.3), 0); 
    var iWidth  = document.width/2; 
    windowNovoItem = new windowAux('wndNovoItem',
                                   'Adicionar Novo Item ',
                                   600,
                                   600
                                     );
                                     
    var sContent  = "<center><table><tr><td>";
    sContent     += "<fieldset><legend><b>Adicionar Itens</b></legend>";
    sContent     += "  <table border='0'>";
    sContent     += "    <tr>";
    sContent     += "      <td>";
    sContent     += "        <a href='#' class='dbancora' style='text-decoration: underline;'"; 
    sContent     += "        onclick='"+me.sInstance+".pesquisaMaterial(true);'><b>Item:</b></a>";
    sContent     += "      </td>";
    sContent     += "      <td>"; 
    sContent     += "        <span id='ctntxtCodigoMaterial'></span>";
    sContent     += "        <span id='ctntxtDescricaoMaterial'></span>";
    sContent     += "      </td>";
    sContent     += "    </tr>";
    sContent     += "    <tr>";
    sContent     += "      <td>";
    sContent     += "        <b>Quantidade:</b>";
    sContent     += "      </td>";
    sContent     += "      <td id='ctntxtQuantidade'>";
    sContent     += "      </td>";
    sContent     += "    </tr>";
    sContent     += "    <tr>";
    sContent     += "      <td>";
    sContent     += "        <b>Valor Unitário:</b>";
    sContent     += "      </td>";
    sContent     += "      <td id='ctntxtVlrUnitario'>";
    sContent     += "      </td>";
    sContent     += "    </tr>";
    sContent     += "    <tr>";
    sContent     += "      <td>";
    sContent     += "        <b>Desdobramento:</b>";
    sContent     += "      </td>";
    sContent     += "      <td id='ctnCboDesdobramento'>";
    sContent     += "      </td>";
    sContent     += "    </tr>";
    sContent     += "    <tr>";    
    sContent     += "      <td colspan='2'>";
    sContent     += "        <fieldset style='border:0px;border-top:2px groove white;border-bottom:2px groove white;'>";
    sContent     += "          <legend>";
    sContent     += "            <b>Vigência</b>";
    sContent     += "          </legend>";
    sContent     += "          <table cellpadding='0' border='0' width='100%' style='white-space: nowrap;'>";
    sContent     += "            <tr>";
    sContent     += "              <td style='width:10%;'>";
    sContent     += "                <b>De:</b>";
    sContent     += "              </td>";
    sContent     += "              <td id='ctnDataInicialItem' style=''>";
    sContent     += "              </td>";
    sContent     += "              <td style='width:10%;'>";
    sContent     += "                <b>Até:</b>";
    sContent     += "              </td>";
    sContent     += "              <td id='ctnDataFinalItem' align='right' style=''>";
    sContent     += "              </td>";
    sContent     += "              <td id='ctnBtnAdicionaPeriodoItem' align='right' style=''>";
    sContent     += "                <input type='button' name='btnAdicionarPeriodoItem' id='btnAdicionarPeriodoItem' value='Adicionar' onclick='"+me.sInstance+".adicionarPeriodo();' >";
    sContent     += "              </td>";
    sContent     += "            </tr>";
    sContent     += "          </table>";
    sContent     += "          <div id='ctnGridPeriodoNovoItem'>";
    sContent     += "          </div>";
    sContent     += "        </fieldset>";
    sContent     += "      </td>";
    sContent     += "    </tr>";
    sContent     += "    <tr>";
    sContent     += "      <td>";
    sContent     += "        <b>Unidade:</b>";
    sContent     += "      </td>";
    sContent     += "      <td id='ctnCboUnidade' colspan='3'>";
    sContent     += "      </td>";
    sContent     += "    </tr>";
    sContent     += "    <tr>";
    sContent     += "      <td nowrap colspan='2' title='Observações'>";
    sContent     += "        <fieldset><legend><b>Resumo do Item</b></legend>";
    sContent     += "        <textarea rows='5' style='width:100%' id='oTxtResumo'></textarea>"; 
    sContent     += "      </td>";
    sContent     += "    </tr>";
    sContent     += "  </table>";
    sContent     += "</fieldset>";
    sContent     += "</td>";
    sContent     += "</tr>";
    sContent     += "<tr>";
    sContent     += "  <td style='text-align:center'>";
    sContent     += "    <input type='button' value='Salvar' id='btnSalvarItem' onclick='"+me.sInstance+".adicionarNovoItem()'>";
    sContent     += "  </td>";
    sContent     += "</tr></table></center>";
    windowNovoItem.setContent(sContent);
    windowNovoItem.setShutDownFunction(function() {
      windowNovoItem.destroy();
    });
    
    oMessageBoardItens = new DBMessageBoard('msgboardItens', 
                                      'Adicionar Novo Item',
                                      "Informe os dados do novo Item.",
                                      $('windowwndNovoItem_content')
                                      );
    
    oMessageBoardItens.show();
    
    /**
     * Grid para os novos itens de um contrato que está sendo aditado.
     */
    oGridPeriodoItemNovo              = new DBGrid('ctnGridPeriodoNovoItem');
    oGridPeriodoItemNovo.nameInstance = "oGridPeriodoItemNovo";
    var aHeaders                      = new Array("Data Inicial", "Data Final", "Ação");
    var aCellWidth                    = new Array("45%", "45%", "10%");
    var aCellAlign                    = new Array("center", "center", "center");
    oGridPeriodoItemNovo.setHeader(aHeaders);
    oGridPeriodoItemNovo.setCellWidth(aCellWidth);
    oGridPeriodoItemNovo.setCellAlign(aCellAlign);
    oGridPeriodoItemNovo.setHeight(100);
    oGridPeriodoItemNovo.show($('ctnGridPeriodoNovoItem'));
    oGridPeriodoItemNovo.clearAll(true);
    
    
    
    oTxtMaterial = new DBTextField('oTxtMaterial', 'oTxtMaterial','', 10);
    oTxtMaterial.addEvent("onKeyPress", "return js_mask(event,\"0-9\")");
    oTxtMaterial.addEvent("onChange", ";"+me.sInstance+".pesquisaMaterial(false); ");
    oTxtMaterial.show($('ctntxtCodigoMaterial'));
    
    oTxtDataInicialItem = new DBTextFieldData('oTxtDataInicialItem', 'oTxtDataInicialItem', '');
    oTxtDataInicialItem.show($('ctnDataInicialItem'));
    
    oTxtDataFinalItem = new DBTextFieldData('oTxtDataFinalItem', 'oTxtDataFinalItem', '');
    oTxtDataFinalItem.show($('ctnDataFinalItem'));
    
    oTxtDescrMaterial = new DBTextField('oTxtDescrMaterial', 'oTxtDescrMaterial', '', 40);
    oTxtDescrMaterial.show($('ctntxtDescricaoMaterial'));
    oTxtDescrMaterial.setReadOnly(true);
    
    oTxtQuantidade = new DBTextField('oTxtQuantidade', 'oTxtQuantidade','', 10);
    oTxtQuantidade.addEvent("onKeyPress", "return js_mask(event,\"0-9|.\")");
    oTxtQuantidade.show($('ctntxtQuantidade'));
    
    oTxtVlrUnitario = new DBTextField('oTxtVlrUnitario', 'oTxtVlrUnitario','', 10);
    oTxtVlrUnitario.addEvent("onKeyPress", "return js_mask(event,\"0-9|.\")");
    oTxtVlrUnitario.show($('ctntxtVlrUnitario'));
    
    oCboDesdobramento = new DBComboBox('oCboDesdobramento', 'oCboDesdobramento', new Array("Selecione"));
    oCboDesdobramento.show($('ctnCboDesdobramento'));
    
    oCboUnidade = new DBComboBox('oCboUnidade', 'oCboUnidade', new Array("Selecione"));
    oCboUnidade.show($('ctnCboUnidade'));
    me.getUnidadesMateriais();
    windowNovoItem.show();
  }
  
  /**
   * Adiciona um periodo ao item do novo do acordo
   */
  this.adicionarPeriodo = function() {
    
    var dtDataInicial = oTxtDataInicialItem.getValue();
    var dtDataFinal   = oTxtDataFinalItem.getValue();
    
    if (dtDataInicial == '' || dtDataFinal == '') {
      
      alert("Informe as datas de vigência do item.");
      return false;
    }
    
    if (js_comparadata(dtDataInicial, dtDataFinal, ">=") || 
        js_comparadata(dtDataInicial, me.oTxtDataInicial.getValue(), "<") || 
        js_comparadata(dtDataFinal  , me.oTxtDataFinal.getValue(), ">")) {
      
      alert("Há conflito entre as datas informadas.\n\nO Conflito pode estar ocorrendo entre as datas de vigência e/ou entre os períodos");
      return false;
    }
    
    var oPeriodoNovo             = new Object();
    oPeriodoNovo.dtDataInicial   = js_formatar(oTxtDataInicialItem.getValue(), "d");
    oPeriodoNovo.dtDataFinal     = js_formatar(oTxtDataFinalItem.getValue(), "d");
    oPeriodoNovo.ac41_sequencial = "";
    me.aPeriodoItensNovos.push(oPeriodoNovo);
    me.loadPeriodoItensNovos();
  }
  
  /**
   * Exclui o periodo de um item contido na grid: "oGridPeriodoItemNovo"
   */
  this.excluirPeriodoItemNovo = function (iLinha) {
    
    me.aPeriodoItensNovos.splice(iLinha, 1);
    me.loadPeriodoItensNovos();
  }
  /**
   * Função que carrega os períodos de um item novo na grid "oGridPeriodoItemNovo"
   */
  this.loadPeriodoItensNovos = function () {
    
    oGridPeriodoItemNovo.clearAll(true);
    me.aPeriodoItensNovos.each(function (oPeriodo, iLinha) {
      
      var aLinha = new Array();
      aLinha[0]  = oPeriodo.dtDataInicial;
      aLinha[1]  = oPeriodo.dtDataFinal;
      aLinha[2]  = "<input type='button' name='btnExcluiPeriodo' id='btnExcluirPeriodo' value='E' onclick='"+me.sInstance+".excluirPeriodoItemNovo("+iLinha+");' />";
      oGridPeriodoItemNovo.addRow(aLinha);
    });
    oGridPeriodoItemNovo.renderRows();
  }
  
  this.getElementosMateriais = function(iValorDefault) {
  
	  iValorElemento = ''; 
	  if (iValorDefault != null) {
	    iValorElemento = iValorDefault;
	  }
	  js_divCarregando('Aguarde, pesquisando elementos do material', 'msgBox');
	  var oParam       = new Object();
	  oParam.iMaterial = oTxtMaterial.getValue();
	  oParam.exec      = "getElementosMateriais";
	  var oAjax   = new Ajax.Request(
	                             'con4_contratos.RPC.php',
	                             {
	                              method    : 'post',
	                              parameters: 'json='+Object.toJSON(oParam),
	                              onComplete: me.retornoGetElementosMaterias
	                              }
	                            );
	}

  this.retornoGetElementosMaterias = function(oAjax) {
  
	  js_removeObj('msgBox');
	  $('oCboDesdobramento').options.length = 1;
	  oCboDesdobramento.aItens = new Array();
	  var oRetorno = eval("("+oAjax.responseText+")");
	  if (oRetorno.status == 1) {
	    
	    oRetorno.itens.each(function (oItem, id) {
	       
	       var oParametro   = new Object();
	       oParametro.nome  = "elemento"
	       oParametro.valor = oItem.elemento.substr(0,7); 
	       oCboDesdobramento.addItem(oItem.codigoelemento, oItem.descricao.urlDecode(), null,new Array(oParametro));
	    });
	  }
	}
	
	this.getUnidadesMateriais = function() {
  
    js_divCarregando('Aguarde, pesquisando unidades do material', 'msgBox');
    var oParam       = new Object();
    oParam.exec      = "getUnidades";
    var oAjax   = new Ajax.Request(
                               me.sUrlRpc, 
                               {
                                method    : 'post', 
                                parameters: 'json='+Object.toJSON(oParam), 
                                onComplete: me.retornoGetUnidades
                                }
                              );
     
  }

  this.retornoGetUnidades = function(oAjax) {
  
    js_removeObj('msgBox');
    $('oCboUnidade').options.length = 1;
    oCboUnidade.aItens = new Array();
    var oRetorno = eval("("+oAjax.responseText+")");
    if (oRetorno.status == 1) {
      
      oRetorno.itens.each(function (oItem, id) {
         oCboUnidade.addItem(oItem.m61_codmatunid, oItem.m61_descr.urlDecode());
      });
    }
  }
  
  if (this.lLiberaNovosItens) {
   
     $('btnItens').style.display = '';
     $('btnItens').observe('click', me.novoItem);
  }  
  
  this.preencheItens = function (aItens) {
    
    me.oGridItens.clearAll(true);
    aItens.each(function (oItem, iSeq) {

      var aLinha   = new Array();      
      aLinha[0]    = oItem.codigoitem;
      aLinha[1]    = oItem.descricaoitem.urlDecode();
      sDataInicial = oItem.datainicial;
      if (iTipoAditamento == 2) {
        sDataInicial = oItem.dataaposultimaexecucao;
      }
      aLinha[2] = "<input type='button' name='btnVerPeriodosItem' id='btnVerPeriodosItem' value='Ver' onclick='"+me.sInstance+".windowPeriodoItens("+iSeq+");'  />";
      var nValorUnitario = js_formatar(oItem.valorunitario, 'f', 3);
      aLinha[3] = eval("valunit"+iSeq+" = new DBTextField('valunit"+iSeq+"','valunit"+iSeq+"','"+nValorUnitario+"')");
      aLinha[3].addStyle("text-align","right");
      aLinha[3].addStyle("height","100%");
      aLinha[3].addStyle("width","100px");
      aLinha[3].addStyle("border","1px solid transparent;");
      aLinha[3].addEvent("onBlur","js_bloqueiaDigitacao(this, true);");  
      aLinha[3].addEvent("onBlur","valunit"+iSeq+".sValue=this.value;");  
      aLinha[3].addEvent("onFocus","js_liberaDigitacao(this, true);");  
      aLinha[3].addEvent("onKeyPress","return js_mask(event,\"0-9|.\")");  
      aLinha[3].addEvent("onKeyDown","return js_verifica(this, event, true)");
      aLinha[3].addEvent("onBlur", me.sInstance+".calculaValor(this,"+iSeq+", false);");  
      if (!me.lLiberaValounitario) {
        aLinha[3].setReadOnly(true);
      }
      aLinha[4] = eval("qtditem"+iSeq+" = new DBTextField('qtditem"+iSeq+"','qtditem"+iSeq+"','0')");
      aLinha[4].addStyle("text-align","right");
      aLinha[4].addStyle("height","100%");
      aLinha[4].addStyle("width","100px");
      aLinha[4].addStyle("border","1px solid transparent;");
      aLinha[4].addEvent("onBlur","js_bloqueiaDigitacao(this, false);");  
      aLinha[4].addEvent("onBlur","qtditem"+iSeq+".sValue=this.value;");  
      aLinha[4].addEvent("onFocus","js_liberaDigitacao(this, false);");  
      aLinha[4].addEvent("onBlur",me.sInstance+".calculaValor(this,"+iSeq+", false);");  
      aLinha[4].addEvent("onKeyPress","return js_mask(event,\"0-9|.\")");  
      aLinha[4].addEvent("onKeyDown","return js_verifica(this,event,false)")
      if (!me.lLiberaQuantidade) {
        aLinha[4].setReadOnly(true);
      }
      aLinha[5]  = js_formatar(oItem.valor, "f");
      var lDisabled = " ";
      if (!me.lLiberaDotacoes) {
        lDisabled = " disabled ";
      }
      aLinha[6]  = "<input type='button' id='dotacoes"+iSeq+"' value='Dotações' "+lDisabled;
      aLinha[6] += " onclick='"+me.sInstance+".ajusteDotacao("+iSeq+","+oItem.elemento+")'>";
      aLinha[7]  = "";
      aLinha[8]  = new String(iSeq);
      
      me.aPeriodoItensAcordo[iSeq] = oItem.aPeriodosExecucao;
      me.oGridItens.addRow(aLinha, false, me.lBloqueiaItem, true);
    });
    me.oGridItens.renderRows();
  } 
  
  this.adicionarNovoItem = function() {
  
    var iCodigoMaterial = oTxtMaterial.getValue();
    var sResumo         = $F('oTxtResumo');
    var nQuantidade     = oTxtQuantidade.getValue();
    var nValorUnitario  = oTxtVlrUnitario.getValue();
    var iUnidade        = oCboUnidade.getValue();
    var iElemento       = oCboDesdobramento.getValue();
    
    if (iElemento == '0') {
    
      alert('Informe um elemento!');
      return false;
    }
    if (iUnidade == '0') {
    
      alert('Informe a unidade do elemento!');
      return false;
    }
    var oNovoMaterial               = new Object();
    oNovoMaterial.codigo            = me.iItensNovos;
    oNovoMaterial.descricaoitem     = oTxtDescrMaterial.getValue();
    oNovoMaterial.codigoitem        = oTxtMaterial.getValue();
    oNovoMaterial.novo              = true;
    oNovoMaterial.resumo            = sResumo;
    oNovoMaterial.unidade           = iUnidade;
    oNovoMaterial.servico           = false;
    oNovoMaterial.elemento          = $('oCboDesdobramento').options[$('oCboDesdobramento').selectedIndex].getAttribute('elemento');
    oNovoMaterial.codigoelemento    = iElemento;
    oNovoMaterial.quantidade        = nQuantidade;
    oNovoMaterial.aPeriodosExecucao = me.aPeriodoItensNovos;
    oNovoMaterial.valorunitario     = nValorUnitario;
    oNovoMaterial.valor             = new Number(nQuantidade)* new Number(nValorUnitario);
    oNovoMaterial.dotacoes          = new Array();
    
    me.aPeriodoItensAcordo[me.oGridItens.aRows.length] = me.aPeriodoItensNovos;
    
    aItensPosicao.push(oNovoMaterial);
    me.preencheItens(aItensPosicao);
    me.iItensNovos++;
    windowNovoItem.destroy();
    
  } 
  this.pesquisaMaterial = function(mostra) {
	
	  if (mostra) {
		  
		  js_OpenJanelaIframe('top.corpo',
		                      'db_iframe_pcmater',
		                      'func_pcmater.php?funcao_js=parent.'+me.sInstance+'.mostraMaterial|pc01_codmater|pc01_descrmater',
		                      'Pesquisar Materiais', 
		                      true
		                      );
		  $('Jandb_iframe_pcmater').style.zIndex = 10000000;
		 } else {
		  
		   if (oTxtMaterial.getValue() != '') { 
		   
		      js_OpenJanelaIframe('top.corpo',
		                          'db_iframe_pcmater',
		                          'func_pcmater.php?pesquisa_chave='+oTxtMaterial.getValue()+
		                          '&funcao_js=parent.'+me.sInstance+'.mostrapcmater',
		                          'Pesquisar materiais',
		                          false);
		   } else {
		     oTxtDescrMaterial.setValue(''); 
		   }
		}
  }
  
  this.mostrapcmater = function(chave, erro) {
	
	  oTxtDescrMaterial.setValue(chave); 
		if (erro == true) {
		  oTxtMaterial.setValue(''); 
		} else {
		  me.getElementosMateriais();
		}
  }
		
  this.mostraMaterial = function (chave1,chave2) {
		  
		oTxtMaterial.setValue(chave1);
		oTxtDescrMaterial.setValue(chave2);
		db_iframe_pcmater.hide();
		me.getElementosMateriais();
  }
  
  /**
   * Função que mostra os períodos de um item apresentado na grid
   */
  me.windowPeriodoItens = function (iRowGrid) {
    
    var sContentPeriodoItens  = "<fieldset>";
        sContentPeriodoItens += "  <legend><b>Períodos Cadastrados</b></legend>";
        sContentPeriodoItens += "  <div id='divGridPeriodosCadastrados'></div>";
        sContentPeriodoItens += "</fieldset>";
        sContentPeriodoItens += "<p align='center'>";
        sContentPeriodoItens += "  <input type='button' name='btnSalvarPeriodo' id='btnSalvarPeriodo' value='Salvar' ";
        sContentPeriodoItens += "         onclick='"+me.sInstance+".salvarJanelaPeriodo("+iRowGrid+");'>";
        sContentPeriodoItens += "  <input type='button' name='btnCancelarPeriodo' id='btnCancelarPeriodo' ";
        sContentPeriodoItens += "         value='Cancelar' onclick='"+me.sInstance+".fecharJanelaPeriodo();'></p>";
    
    if (me.oWindowPeriodoItens == "") {
      
      me.oWindowPeriodoItens = new windowAux("oWindow_"+me.sInstance, "Períodos Cadastrados", 600, 400);
      me.oWindowPeriodoItens.setContent(sContentPeriodoItens);
      me.oWindowPeriodoItens.setShutDownFunction(me.fecharJanelaPeriodo);
      oMessageBoardItens  = new DBMessageBoard('oMessageBoard_'+me.sInstance, 
                                               'Item: '+me.oGridItens.aRows[iRowGrid].aCells[2].content,
                                               "Períodos cadastrados para o item.",
                                               me.oWindowPeriodoItens.getContentContainer());
    }
    me.oWindowPeriodoItens.show();
    

    /*
     * Cria uma grid e preenche com os dados retornados do banco
     */
    oGridPeriodoItem              = new DBGrid('divGridPeriodosCadastrados');
    oGridPeriodoItem.nameInstance = 'oGridPeriodoItem';
    var aHeaders                  = new Array("Data Inicial", "Data Final", "Sequencial");
    var aCellAlign                = new Array("center", "center", "center");
    var aCellWidth                = new Array("50%", "50%", "0%");
    oGridPeriodoItem.setHeader(aHeaders);
    oGridPeriodoItem.setCellAlign(aCellAlign);
    oGridPeriodoItem.setCellWidth(aCellWidth);
    oGridPeriodoItem.aHeaders[2].lDisplayed = false;
    oGridPeriodoItem.show($('divGridPeriodosCadastrados'));
    oGridPeriodoItem.clearAll(true);
    
    me.aPeriodoItensAcordo[iRowGrid].each(function (oPeriodo, iLinha) {
      
      /*
       * Configuramos as variáveis retornadas do banco
       */
      var aDataInicial  = oPeriodo.dtDataInicial.split("-");
      var aDataFinal    = oPeriodo.dtDataFinal.split("-");
      var dtDataInicial = aDataInicial[2]+"/"+aDataInicial[1]+"/"+aDataInicial[0];
      var dtDataFinal   = aDataFinal[2]+"/"+aDataFinal[1]+"/"+aDataFinal[0];
      
      var aLinha   = new Array();
      aLinha[0]    = eval("oTxtPeriodoInicial_"+iLinha+" = new DBTextFieldData('dtDataInicial_"+iLinha+"', 'oTxtPeriodoInicial_"+iLinha+"', '"+dtDataInicial+"')");
      aLinha[1]    = eval("oTxtPeriodoFinal_"+iLinha+"   = new DBTextFieldData('dtDataFinal_"+iLinha+"', 'oTxtPeriodoFinal_"+iLinha+"', '"+dtDataFinal+"')");
      if (!me.lDatas) {
        aLinha[1]  = dtDataFinal;
      }
      aLinha[2] = oPeriodo.ac41_sequencial;
      oGridPeriodoItem.addRow(aLinha);
    });
    oGridPeriodoItem.renderRows();
  }

  /**
   * Destrói a janela que mostra os períodos de um item
   */
  me.fecharJanelaPeriodo = function() {
    
    me.oWindowPeriodoItens.destroy();
    me.oWindowPeriodoItens = "";
  }
  /**
   * Salva os períodos selecionados para o item.
   */
  me.salvarJanelaPeriodo = function(iRowGridItens) {
    
    aDatasAlteradas = new Array();
    oGridPeriodoItem.aRows.each(function (oLinha, iLinha) {
      
      var dtDataInicialGrid = oLinha.aCells[0].getValue();
      var dtDataFinalGrid   = oLinha.aCells[1].getValue();
      if (js_comparadata(dtDataInicialGrid, dtDataFinalGrid, ">")) {
        
        alert("Data inicial maior que a data final.");
        return false;
      }
      
      var oDatasItem             = new Object();
      oDatasItem.dtDataInicial   = js_formatar(dtDataInicialGrid, "d");
      oDatasItem.dtDataFinal     = js_formatar(dtDataFinalGrid, 'd');
      oDatasItem.ac41_sequencial = oLinha.aCells[2].getValue();
      aDatasAlteradas.push(oDatasItem);
    });

    lValidarPeriodos = this.validarPeriodosExecutados(aDatasAlteradas);

    if ( !lValidarPeriodos ) {
      return false;
    }

    me.aPeriodoItensAcordo[iRowGridItens] = aDatasAlteradas;
    me.oWindowPeriodoItens.destroy();
    me.oWindowPeriodoItens = "";
  }
}

/**
 * bloqueia  o input passado como parametro para a digitacao.
 * É colocado  a mascara do valor e bloqueado para Edição
 */
function js_bloqueiaDigitacao(object, lFormata) {

  object.readOnly         = true;
  object.style.border     ='1px';
  object.style.fontWeight = "normal";
  if (lFormata) {
    object.value            = js_formatar(object.value,'f');
  }
   
}
  /**
 * Libera  o input passado como parametro para a digitacao.
 * é Retirado a mascara do valor e liberado para Edição
 * é Colocado a Variavel nValorObjeto no escopo GLOBAL
 */
function js_liberaDigitacao(object, lFormata) {
  
  nValorObjeto        = object.value; 
  object.value        = object.value;
  if (lFormata) {
    object.value        = js_strToFloat(object.value).valueOf();
  }
  object.style.border = '1px solid black';
  object.readOnly     = false;
  object.style.fontWeight = "bold";
  object.select();
   
}
/**
 * Verifica se  o usuário cancelou a digitação dos valores.
 * Caso foi cancelado, voltamos ao valor do objeto, e 
 * bloqueamos a digitação
 */
function js_verifica(object,event,lFormata) {

  var teclaPressionada = event.which;
  if (teclaPressionada == 27) {
      object.value = nValorObjeto;
     js_bloqueiaDigitacao(object, lFormata);
  }
} 
validaValorDotacao = function(obj, iQuantMax, nValUnitario, oValorTotal) {

     if (new Number(obj.value) > iQuantMax) {
       obj.value = iQuantMax;
     } else if (obj.value == 0) {
       obj.value = iQuantMax;
     }
     var nValorTotal      =  obj.value*nValUnitario;
     $(oValorTotal).value = js_formatar(nValorTotal, 'f'); 
}

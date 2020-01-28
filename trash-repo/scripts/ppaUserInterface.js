function js_drawSelectVersaoPPA(oHTMLNode, idCampo) {

   if (idCampo == null) {
     idCampo = 'o05_ppaversao';
   }
   cboVersoes = idCampo;
   var aSelectVersion  = "<select name='"+cboVersoes+"' id='"+cboVersoes+"' style='width:100%'>";
   aSelectVersion      += "  <option value='0'>Selecione</option>";
   aSelectVersion      += "</select>";
   oHTMLNode.innerHTML  = aSelectVersion;
     
}

function js_addVersaoPPA(oPPAVersao) {

  $(cboVersoes).add(new Option("P"+oPPAVersao.o119_versao+" - "+
                                    js_formatar(oPPAVersao.o119_datainicio,"d")+" - "+
                                    js_formatar(oPPAVersao.o119_datatermino,"d"),
                                    oPPAVersao.o119_sequencial
                                    ),null);
  var iProcessadoDespesa = 0;
  var iProcessadoReceita = 0;
  if (oPPAVersao.receitaprocessada) {
    iProcessadoReceita = 1;
  }
  if (oPPAVersao.despesaprocessada) {
    iProcessadoDespesa = 1;
  }                                   
  $(cboVersoes).options[$(cboVersoes).options.length-1].processadoreceita = iProcessadoReceita;                                    
  $(cboVersoes).options[$(cboVersoes).options.length-1].processadodespesa = iProcessadoDespesa;                                    
  $(cboVersoes).options[$(cboVersoes).options.length-1].selected   = true;  
  $(cboVersoes).options[$(cboVersoes).options.length-1].homologada = false;  
  if (oPPAVersao.o119_versaofinal == "t") {
  
    $(cboVersoes).options[$(cboVersoes).options.length-1].style.color ='blue';
    $(cboVersoes).options[$(cboVersoes).options.length-1].homologada  = true;
    $(cboVersoes).options[$(cboVersoes).options.length-1].innerHTML   = 
            "*"+$(cboVersoes).options[$(cboVersoes).options.length-1].innerHTML;
  }                                  
}

function js_getVersoesPPA(iCodigoLei, iTipoConsulta, oFunction) {

  if (iTipoConsulta == null) {
    iTipoConsulta = 0;
  }
  if (oFunction == null) {
   oFunction = "";
  }
  
  var oParam                 = new Object();
  oParam.iCodigoLei          = iCodigoLei;
  oParam.exec                = "getVersoesPPA";
  oParam.iTipo               = 1;
  oParam.aParametros         = new Array();
  oParam.lProcessaBase       = true;
  oParam.lProcessaEstimativa = true;
  oParam.oFunction           = oFunction;
  oParam.iTipoConsulta       = iTipoConsulta;
  var oAjax   = new Ajax.Request(
                         'orc4_ppaRPC.php', 
                         {
                          asynchronous:false,
                          method    : 'post', 
                          parameters: 'json='+js_objectToJson(oParam), 
                          onComplete: js_retornoGetVersoesPPA
                          }
                        );
}

function js_retornoGetVersoesPPA (oAjax) {

  var oRetorno = eval("("+oAjax.responseText+")");
  
  /**
   * Quando não for encontrado Perspectiva, limpamos o combobox e adicionamos um novo Option padrão.
   */
  if (oRetorno.status == 2) {
    js_limpaComboBoxPerspectivaPPA();
  } 
  
  if (oRetorno.status == 1) {
    
    if (oRetorno.itens.length == 0) {
      
      alert('PPA sem perspectivas homologadas!');
      return false;
      
    }
    $(cboVersoes).options.length=1;
    for (var i = 0; i < oRetorno.itens.length; i++) {
      js_addVersaoPPA(oRetorno.itens[i]);
    }
  }
}

/**
 * Funcao que limpa a perspectiva.
 */
function js_limpaComboBoxPerspectivaPPA() {
  
  var oOption = new Option("Selecione", "0", "0", true);
  $('o05_ppaversao').options.length = 0;
  $('o05_ppaversao').appendChild(oOption);
}

function js_selecionaPeriodoPPa(oParam) {

  wndPeriodosPPA = new windowAux('wndPeriodos', 'Importação', 650, 300);
  var sContent = "<div>";
  sContent     += "<fieldset>";
  sContent     += "  <legend>";
  sContent     += "  <b>Escolha um Período</b>";
  sContent     += "  </legend>";
  sContent     += "  <table>";
  sContent     += "    <tr>";
  sContent     += "     <td nowrap>";
  sContent     += "      <a href='#' onclick='js_pesquisaoPeriodo(true); return false;'><b>Lei do PPA</b></a>";
  sContent     += "    </td> ";
  sContent     += "    <td> "; 
  sContent     += "      <input id='periodo' name='periodo' size='10' type='text' onchange='js_pesquisaoPeriodo(false);'>";
  sContent     += "    </td> ";
  sContent     += "    <td> "; 
  sContent     += "      <input id='descricaoPeriodo' name='descricaoPeriodo' size='40' type='text' ";
  sContent     += "             readonly style='background-color: rgb(222, 184, 135); text-transform: uppercase;'>";
  sContent     += "    </td> ";
  sContent     += "   </tr> ";
  sContent     += "   <tr> ";
  sContent     += "     <td nowrap>";
  sContent     += "      <b>Perspectiva:</b> ";
  sContent     += "    </td> ";
  sContent     += "    <td id='perspectivas' colspan='2'>"; 
  sContent     += "    </td>";
  sContent     += "   </tr> ";
  sContent     += "  </table>";
  sContent     += "  </fieldset>";
  sContent     += "    <center><input type='button' value='OK' onclick='' id='btnProcessar'></center>";
  sContent     += "</div>";
  wndPeriodosPPA.setContent(sContent);
  wndPeriodosPPA.setShutDownFunction(function () {
    wndPeriodosPPA.destroy();
  });
  $('btnProcessar').onclick = function() {
   js_importarDadosPPA(oParam); 
  }
  js_drawSelectVersaoPPA($('perspectivas'), 'cboperspectivas');
  var oMessageBoard = new DBMessageBoard('msgboard1',
                                         'Importação de Dados',
                                         'Escolha um Período do PPA, e logo após indique qual perspectiva '+
                                         'deseja usar como base.',
                                         $('windowwndPeriodos_content'));
    oMessageBoard.show();
  wndPeriodosPPA.show();
}

function js_pesquisaoPeriodo(mostra) {
  
  if (mostra==true) {
	  
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_ppalei',
                        'func_ppalei.php?funcao_js=parent.js_mostraperiodo1|o01_sequencial|o01_descricao',
                        'Pesquisa de Leis para o PPA',
                        true,
                        25,
                        0);
    
  } else { 
     if ($F('periodo') != '') { 
        js_OpenJanelaIframe('top.corpo',
                            'db_iframe_ppalei',
                            'func_ppalei.php?pesquisa_chave='
                            +$F('periodo')+'&funcao_js=parent.js_mostraperiodo',
                            'Leis PPA',
                            false);
     } else {
       $('descricaoPeriodo').value = ''; 
     }
  }
  $('Jandb_iframe_ppalei').style.zIndex = '1000000';
}
function js_mostraperiodo(chave, erro) {

  $('descricaoPeriodo').value = chave;
  $('Jandb_iframe_ppalei').style.zIndex = '0'; 
  if (erro==true) {
   
    $('periodo').focus(); 
    $('periodo').value = ''; 
  } else {
   js_getVersoesPPA($('periodo').value);
  }
  
}
function js_mostraperiodo1(chave1,chave2){
  
  $('periodo').value          = chave1;
  $('descricaoPeriodo').value = chave2;
  db_iframe_ppalei.hide();
  js_getVersoesPPA($('periodo').value);
  $('Jandb_iframe_ppalei').style.zIndex = '0';  
}

function js_validaLeiPPAPeriodo(iCodigoLei, oCallBack) {
   
  var oParam = new Object();
  oParam.iCodigoLei          = iCodigoLei;
  oParam.exec                = "validaVersaoPPA";
  var oAjax                 = new Ajax.Request(
											                         'orc4_ppaRPC.php', 
											                         {
											                          asynchronous:false,
											                          method    : 'post', 
											                          parameters: 'json='+js_objectToJson(oParam), 
											                          onComplete: function (oAjax) {
											                            var oRetorno = eval("("+oAjax.responseText+")");
 											                            oCallBack(oRetorno.leivalida, oRetorno.message.urlDecode())
											                          }
											                          });
  
}
/**
 * View para alteração das dotaçoes de solicitação.
 * Permite a alteração de dotações de anos anteriores para dotações
 * do ano atual.
 @author Iuri Guntchnigg
 */

DBViewSolicitacaoDotacao = function(iCodigoSolicitacao, sNameInstance) {

  var me                  = this;
  this.iCodigoSolicitacao = iCodigoSolicitacao;
  this.sNameInstance      = sNameInstance;
  this.sUrlRPC            = 'com4_alteradotacaosolicitacao.RPC.php';
  this.aDotacoes          = new Array(); 
  this.iAnoSessao         = '';
  this.oWindow            = new windowAux('wndAlteracaoSolicitacoes', 
                                        "Alteração das Dotações da Solicitacao "+me.iCodigoSolicitacao,
                                        800, 
                                        450
                                        );
                                        
  this.oGridItens               = new DBGrid('gridItensSolicitacao');
  this.oGridItens.sNameInstance = sNameInstance+'oGridItens';
  oWindowDotacaoItem = new windowAux('wndItemDotacao', "Lista de Itens da Solicitação "+me.iCodigoSolicitacao, 800, 450);
    oWindowDotacaoItem.setShutDownFunction(function () {
      oWindowDotacaoItem.destroy();
  });
    
  var sNomeFuncaoAlterarDotacoes = me.sNameInstance+".alterarDotacoes()";
  var sContent  = "<div id='ctnDotacao'>";
      sContent += "  <fieldset>";
      sContent += "    <legend><b>Itens da Dotação</b></legend>";
      sContent += "    <div id='ctnGridDotacoesItens'></div>";
      sContent += "  </fieldset>";
      sContent += "  <div id='ctnBtnAlterar' style='text-align:center'>";
      sContent += "    <input type='button' id='btnAlterarDotacao' value='Alterar Dotações'";
      sContent += "           onclick='"+sNomeFuncaoAlterarDotacoes+"'> ";
      sContent += "  </div>";
      sContent += "</div>";
      
  oWindowDotacaoItem.setContent(sContent);
 
  var sMsgHelp  = "Para alterar a dotação de todos itens, clique em \"M\" para marcar todos itens de uma dotação.";
      sMsgHelp += "Clique no botao \'Alterar\" da Dotação e selecione a nova dotação. ";
      sMsgHelp += "Para alterar a dotação de um item, clique no Botão \"Alterar\". Para confirmar as alterações, clique";
      sMsgHelp += " em <b>\"Alterar Dotações\"</b>.";
  oMessageBoard = new DBMessageBoard('msgBoardDotacao', 
                                    'Dotações Retornadas',
                                     sMsgHelp,
                                     oWindowDotacaoItem.getContentContainer()
                                    );    
  oWindowDotacaoItem.show();
  oGridDotacoes              = new DBGrid('Dotacoes');
  oGridDotacoes.nameInstance = 'oGridDotacoes';
  oGridDotacoes.setHeight(250);
  oGridDotacoes.setCellAlign (new Array("left", 
                                        "right", 
                                        "right", 
                                        "center", 
                                        "center"
                                       ));
  
  oGridDotacoes.setCellWidth(new Array( '400px' ,
                                        ' 70px',
                                        ' 70px',
                                        ' 70px',
                                        ' 45px'
                                       ));
  
  oGridDotacoes.setHeader(new Array('Item',
                                    'Quantidade',
                                    'Valor',
                                    'Dotação',
                                    'Ação'
                                    )
                          );
  oGridDotacoes.show($('ctnGridDotacoesItens'));
  
  /**
   * Retorna as dotações da solicitação, bem como seus itens , agrupados por dotacao
   */
  this.getDotacoes = function () {
  
    var msgDiv                   = "Carregando Lista de Itens \n Aguarde ...";
    js_divCarregando(msgDiv,'msgBox');
    
    var oParam                = new Object();
    oParam.exec               = 'getDotacoes';
    oParam.iCodigoSolicitacao = me.iCodigoSolicitacao;  
    var oAjax                 = new Ajax.Request(me.sUrlRPC, 
                                                    {method:'post',
                                                     parameters:'json='+Object.toJSON(oParam),
                                                     onComplete: me.retornoGetDotacoes 
                                                    }
                                                   );
  }
  
  /**
   * preenche os dados da Grid das dotações
   */
  this.retornoGetDotacoes = function (oAjax) {
  
    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");  
    me.iAnoSessao      = oRetorno.iAnoSessao; 
    me.aDotacoes       = oRetorno.aDotacoes;
    me.renderizaLinhasGrid();
  }

  /**
   * Renderiza os dados da grid
   */
  this.renderizaLinhasGrid = function () {
  
    oGridDotacoes.clearAll(true);
    
    var iLinha         = 0;
    var iTotalDotacoes = 0;
    for (var iCodigoDotacao in me.aDotacoes) {
       
      
       if (typeof(me.aDotacoes[iCodigoDotacao]) == 'function') {
         continue;
       } 
       me.aDotacoes[iCodigoDotacao].lMarcadoTodos = false; 
       var oDotacao = me.aDotacoes[iCodigoDotacao];
       /**
        * Nome da função que ira mostrar o saldo da dotação 
        */
       var sNomeFuncaoDotacao       = me.sNameInstance+".mostrarDadosDotacao("+oDotacao.iDotacao+","+oDotacao.iAnoDotacao+")";
       var sNomeFuncaoAlteraDotacao = me.sNameInstance+".pesquisaDotacaoGrupo('"+iCodigoDotacao+"', '"+oDotacao.sElemento+"')";
       var sNomeFuncaoMarcarTodos   = me.sNameInstance+".marcaTodosItens('"+iCodigoDotacao+"')";
       
      // console.log(oDotacao.aItens.lAutorizado);
       var aRowDotacao        = new Array();
       aRowDotacao[0]  = "<span style='padding:5px;' onclick=\""+sNomeFuncaoMarcarTodos+"\"><b>M</b></span>&nbsp;";
       aRowDotacao[0] += "Dotação: <a onclick='"+sNomeFuncaoDotacao+";return false;' href='#'><b>";
       aRowDotacao[0] += oDotacao.iDotacao+"</b></a> do Ano <b>"+oDotacao.iAnoDotacao+"</b>";
       aRowDotacao[1]  = ""; 
       aRowDotacao[2]  = ""; 
       aRowDotacao[3]  = ""; 
       //alert(oDotacao.lAutorizado);
       
       if (oDotacao.lAutorizado == 'false') {
         
         aRowDotacao[4]  = "<input id='btnAlteraDotacao"+iCodigoDotacao+"' type='button' value='Alterar'";
         aRowDotacao[4] += "       onclick=\""+sNomeFuncaoAlteraDotacao+"\" />";
         
       } else {
         
         aRowDotacao[4]  = "<input id='btnAlteraDotacao"+iCodigoDotacao+"' onclick='alert(\"Essa Dotação Possui Itens Já Autorizados.\")' ";
         aRowDotacao[4] += " type='button' value='Alterar' />";
         
         
         
       }
       
       //aRowDotacao[4]  = "<input id='btnAlteraDotacao"+iCodigoDotacao+"' type='button' value='AlteraDDDr'";
       //aRowDotacao[4] += "       onclick=\""+sNomeFuncaoAlteraDotacao+"\" />";
       
       
       
       
       oGridDotacoes.addRow(aRowDotacao);
       oGridDotacoes.aRows[iLinha].sStyle ='background-color:#eeeee2;';
       oGridDotacoes.aRows[iLinha].aCells.each(function(oCell, iCell) {

          oCell.sStyle +=';border-right: 1px solid #eeeee2;';
        });
       iLinha++;
       
       oDotacao.aItens.each(function (oItem, iIndice) {  
         
         var sElementoItem = oDotacao.sElemento;
         
         var sNomeFuncaoDotacaoItem        = me.sNameInstance+".mostrarDadosDotacao("+oItem.iDotacao+","+oDotacao.iAnoDotacao+")";
         
         if ( sElementoItem == 'false' || sElementoItem == false) {
           
           sElementoItem = oItem.sElemento;
         }
         
         var sNomeFuncaoAlteraDotacaoItem  = me.sNameInstance+".pesquisaDotacaoItem('";
             sNomeFuncaoAlteraDotacaoItem += iCodigoDotacao+"',"+iIndice+", '"+sElementoItem+"')";
         
         var sFunctionToogleLinha  =  me.sNameInstance+".toogleLinhaItem('"+iCodigoDotacao+"',"+iIndice+")";
         aRowItem     = new Array();    
         var sChecked  = '';
         if  (oItem.lAlterado) {
           sChecked = ' checked="checked"';
         }                                                          
         aRowItem[0]  = "<span ><input class='chk"+iCodigoDotacao+"' type='checkbox' "+sChecked+" onclick=\""+sFunctionToogleLinha+"\"";
         aRowItem[0] += "id='chk"+iLinha+"' value='"+oItem.iItem+"'></span> "+oItem.iOrdem+" - "+oItem.sNomeItem.urlDecode();
         aRowItem[1]  = oItem.nQuantidade;
         aRowItem[2]  = js_formatar(oItem.nValor, "f");
         
         var sDotacao  = "<a onclick='"+sNomeFuncaoDotacaoItem+";return false;' href='#'>";
             sDotacao += oItem.iDotacao+"<a>";
             
         if (oItem.iDotacao == null || oItem.iDotacao == '') {
           
               sDotacao  = "";
               //sDotacao += "Selecionar<a>";
         }
             
             
         //aRowItem[3]  = "<a onclick='"+sNomeFuncaoDotacaoItem+";return false;' href='#'>";
         //aRowItem[3] += oItem.iDotacao+"<a>";
         
         aRowItem[3] = sDotacao;
         
         aRowItem[4]  = "<input id='btnAlteraDotacaoItem"+iIndice+"' type='button' value='Alterar'";
         aRowItem[4] += "       onclick=\""+sNomeFuncaoAlteraDotacaoItem+"\" />";
         oGridDotacoes.addRow(aRowItem);
         oGridDotacoes.aRows[iLinha].isSelected = oItem.lAlterado;
         if (oItem.lAlterado) {
            oGridDotacoes.aRows[iLinha].setClassName('marcado');
         }
         
         oItem.iLinhaNaGrid = iLinha;
         iLinha++; 
         
       });
       iTotalDotacoes++;
    };                        
    oGridDotacoes.renderRows();
    oGridDotacoes.setNumRows(iTotalDotacoes);  
  }  
  
  /**
   * Mostra a tela de saldo da Dotação
   */
  this.mostrarDadosDotacao = function(iDotacao, iAno) {
  
    js_OpenJanelaIframe('',
                        'db_iframe_dotacao',
                        'func_saldoorcdotacao.php?coddot='+iDotacao+'&anousu='+iAno,
                        'Saldo Dotação',
                        true);
    $('Jandb_iframe_dotacao').style.zIndex = '10000';
  } 
  
  /**
   * Abre janela para alterar a Dotação do grupo de itens 
   */
  this.pesquisaDotacaoGrupo = function (sDotacao, sElemento ) {
    
    sDotacaoAtual      = sDotacao;
    var sFuncaoRetorno = 'funcao_js=parent.'+me.sNameInstance+'.alteraDotacaoGrupo|o58_coddot';
    
    js_OpenJanelaIframe('',
                        'db_iframe_alterarDotacao',
                        'func_permorcdotacao.php?obriga_depto=nao&elemento='+sElemento+'&'+sFuncaoRetorno,
                        'Escolha uma Dotação',
                        true);
    $('Jandb_iframe_alterarDotacao').style.zIndex = '10000';
  }
  
  /**
   * Altera as dotações de todos os itens que possuem uma mesma Dotação.
   * @param {integer} Código da Nova Dotação
   */
  this.alteraDotacaoGrupo = function (iCodigoDotacao) {

    if (me.aDotacoes[sDotacaoAtual]) {
      
      me.aDotacoes[sDotacaoAtual].iDotacao    = iCodigoDotacao;
      me.aDotacoes[sDotacaoAtual].iAnoDotacao = me.iAnoSessao;
      
      me.aDotacoes[sDotacaoAtual].aItens.each(function (oItem, iInd) {
      
        oItem.iDotacao    = iCodigoDotacao;
        oItem.iAnoDotacao = me.iAnoSessao;
        oItem.lAlterado   = true;
        me.marcaLinhaItem(oItem.iLinhaNaGrid);
      });
    }
    delete sDotacaoAtual;
    db_iframe_alterarDotacao.hide();
    me.renderizaLinhasGrid();
  }
  
  /**
   * Abre janela para alterar a Dotação de um item especifico; 
   */
  this.pesquisaDotacaoItem = function (sDotacao, iIndiceItem, sElemento ) {
    
    //alert("HADOUKEN" + sElemento);
    sDotacaoAtual      = sDotacao;
    iIndiceItemAtual   = iIndiceItem
    var sFuncaoRetorno = 'funcao_js=parent.'+me.sNameInstance+'.alteraDotacaoItem|o58_coddot';
    
    js_OpenJanelaIframe('',
                        'db_iframe_alterarDotacao',
                        'func_permorcdotacao.php?obriga_depto=sim&elemento='+sElemento+'&'+sFuncaoRetorno,
                        'Escolha uma Dotação',
                        true);
    $('Jandb_iframe_alterarDotacao').style.zIndex = '10000';
  }
  
  /**
   * Realiza a alteração da Dotação no item
   * @param {integer} iCodigoDotacao Código da dotação
   */
  this.alteraDotacaoItem = function (iCodigoDotacao) {

    if (me.aDotacoes[sDotacaoAtual]) {
      
      if (me.aDotacoes[sDotacaoAtual].aItens[iIndiceItemAtual]) {
        
        me.aDotacoes[sDotacaoAtual].aItens[iIndiceItemAtual].iDotacao    = iCodigoDotacao;
        me.aDotacoes[sDotacaoAtual].aItens[iIndiceItemAtual].iAnoDotacao = me.iAnoSessao;
        me.aDotacoes[sDotacaoAtual].aItens[iIndiceItemAtual].lAlterado   = true;
      }
    }
    
    delete sDotacaoAtual;
    delete iIndiceItemAtual;
    db_iframe_alterarDotacao.hide();
    me.renderizaLinhasGrid();
  }
  
  /**
   *Realiza as alterações das dotações dos itens selecionados.
   */
  this.alterarDotacoes = function() {
  
    var oRowsSelecionadas     = oGridDotacoes.getSelection();
    
    var oParam                = new Object();
    oParam.exec               = 'alteraDotacoes';
    oParam.iCodigoSolicitacao = me.iCodigoSolicitacao;
    oParam.aItens             = new Array();
    
    for (var iCodigoDotacao in me.aDotacoes) {
      
      if (typeof(me.aDotacoes[iCodigoDotacao]) == 'function') {
        continue;
      }  
      var oDotacao = me.aDotacoes[iCodigoDotacao];
      
      oDotacao.aItens.each(function (oItem, iIndice) {

        var oItemAlterar = new Object();
        if  (oItem.lAlterado) {
          
          oItemAlterar.iCodigoDotacaoItem = oItem.iDotacaoSequencial;
          oItemAlterar.iCodigoItem        = oItem.iCodigoItem;
          oItemAlterar.iCodigoDotacao     = oItem.iDotacao;
          oItemAlterar.iAnoDotacao        = oItem.iAnoDotacao;
          oParam.aItens.push(oItemAlterar);       
        }
      });
    }
    if (oParam.aItens.length == 0) {
    
      alert('Nenhuma dotação foi modificada!\nProcessamento cancelado.');
      return false;
    }
    var iNumeroItens = new String(oParam.aItens.length);
    var sMensagemConfirmacao  = 'Confirma a alteração da dotação dos ';
        sMensagemConfirmacao += iNumeroItens+"("+iNumeroItens.extenso()+") itens selecionados?"; 
    if (!confirm(sMensagemConfirmacao)) {
      return false;
    }
    var msgDiv                = "Alterando dotações modificadas. \n Aguarde ...";
    js_divCarregando(msgDiv, 'msgBox');
    var oAjax = new Ajax.Request(me.sUrlRPC, 
                                  {method:'post',
                                   parameters:'json='+Object.toJSON(oParam),
                                   onComplete: me.retornoAlteracaoDotacoes 
                                  }
                                 );
  
  }
  
  /**
   *função de retorno aós a execução da alteração dos dados da Dotação
   */ 
  this.retornoAlteracaoDotacoes = function(oAjax) {
    
    js_removeObj('msgBox');
    
    var oRetorno = eval("("+oAjax.responseText+")"); 
    if (oRetorno.status == 2) {
      alert(oRetorno.message.urlDecode());
    } else {
    
      alert('Dotações dos itens selecionados, modificados com sucesso.');
      me.getDotacoes();
      me.beforeSave();
    }
  }
  
  /**
   *Marca uma linha da grid
   */
  this.marcaLinhaItem = function(iLinha) {
    
    oGridDotacoes.aRows[iLinha].select(true);
    $(oGridDotacoes.aRows[iLinha].sId).style.color = 'green';
    oGridDotacoes.aRows[iLinha].setClassName('marcado');
  }
  
  /**
   * Controla a marcação dos checboxes dos itens  
   */
  this.toogleLinhaItem = function (sDotacao, iIndiceItem) {
     
    if (me.aDotacoes[sDotacao].aItens[iIndiceItem]) {
      with (me.aDotacoes[sDotacao].aItens[iIndiceItem]) {
       
        if (lAlterado) {
          
          oGridDotacoes.aRows[iLinhaNaGrid].select(false);
          lAlterado = false;
        } else {
          
          lAlterado = true; 
          oGridDotacoes.aRows[iLinhaNaGrid].select(true);
        }
      }
    }
  }
  
  this.beforeSave = function() {
    return true;
  }
  this.onBeforeSave = function (sFunction) {
    me.beforeSave = sFunction; 
  }
  
  /**
   *Marca todos os itens que possuiem o mesmo hash de dotacao
   */ 
  this.marcaTodosItens = function(sDotacao) {
  
    if (me.aDotacoes[sDotacao]) {
     
      var lMarcar  = true;
      if (me.aDotacoes[sDotacao].lMarcadoTodos) {
        
        lMarcar = false;
        me.aDotacoes[sDotacao].lMarcadoTodos = false;
      } else {
        me.aDotacoes[sDotacao].lMarcadoTodos = true;
      }
      
      me.aDotacoes[sDotacao].aItens.each(function (oItem, iIndice) {
        
        with (oItem) {
          
          $('chk'+iLinhaNaGrid).checked = lMarcar;
          oGridDotacoes.aRows[iLinhaNaGrid].select(lMarcar);
          lAlterado = lMarcar;
        }
      });  
    }
  }
}
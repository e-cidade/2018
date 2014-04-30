DBViewEncerramentoAvaliacoesFiltro = function (sInstancia, iEncerra) {

  this.sInstancia          = sInstancia;
  this.oDataGridAlunos     = null;  
  this.oTreeViewAvaliacao  = null;
  this.oWindowAuxAvaliacao = null;
  this.lProgressaoParcial  = false;
  this.oViewAvaliacao      = null;
  this.iTurma              = null;
  this.iEtapa              = null;
  this.sTurma              = '';
  this.sDisplay            = '';
  this.sLarguraDivConteudo = '70%';
  
  
  /**
   * iEncerra = 1 Encerrar
   * iEncerra = 2 Cancelar
   */
  this.iEncerra               = iEncerra;
  this.sLabelCalendarioLetivo = 'Encerramento de Avaliações - Calendário Letivo';
  this.sLabelProgressao       = 'Encerramento de Avaliações - Progressão Parcial';
  this.sBotao                 = 'Encerrar Avaliações';
  var me                      = this;
  me.oCallBackProcessar = function (){};
  
  if (me.iEncerra == 2) {
    
    me.sLabelCalendarioLetivo = 'Cancelar Encerramento de Avaliações - Calendário Letivo';
    me.sLabelProgressao       = 'Cancelar Encerramento de Avaliações - Progressão Parcial';
    me.sBotao                 = 'Cancelar Encerramento Avaliações';
  }
  /**
   * Renderização do HTML do componente
   */
  var renderizarHTML = function () {
    
    var sConteudo    = '<div id="divMessage">                                                                   \n';
        sConteudo   += '</div>                                                                                  \n';
        sConteudo   += '<div id="divPesquisaGrupo">                                                             \n';
        sConteudo   += '  <div id="divListaGrupo">                                                              \n';
        sConteudo   += '    <fieldset style="height:80%">                                                       \n';
        sConteudo   += '      <legend>                                                                          \n';
        if (me.lProgressaoParcial) {
          sConteudo   += '        <b>'+me.sLabelProgressao+'</b> \n';
        } else {
          sConteudo   += '        <b>'+me.sLabelCalendarioLetivo+'</b> \n';
        }
        sConteudo   += '      </legend>                                                                         \n';
        if (!me.lProgressaoParcial) {
          
          sConteudo   += '      <div style="text-align:left">';
          sConteudo   += '         <fieldset style="border:0px; border-top:2px groove white">';
          sConteudo   += '           <legend><b>Opcões</b></legend>';
          sConteudo   += '           <label><b>Mostrar Trocas de Turma:</b></label>';
          sConteudo   += '           <select id="trocaTurma" name="TrocaTurma">';
          sConteudo   += '             <option value="1" selected>Não</option>';
          sConteudo   += '             <option value="2" >Sim</option>';
          sConteudo   += '           </select>';
          sConteudo   += '         </fielset>';        
          sConteudo   += '      </div>';
        }
        
        var sStyleTreeView      = 'text-align:left; height:100%; width:30%; float:left; '+me.sDisplay;
        var sStyleListaConteudo = 'padding:0px; height:100%; width:'+me.sLarguraDivConteudo+'; float:right';
        
        sConteudo   += '      <div id="ctnTreeView" style="'+sStyleTreeView+'">                                 \n';
        sConteudo   += '      </div>                                                                            \n';
        sConteudo   += '      <div id="ctnListaConteudoAlunos" style="'+sStyleListaConteudo+'">                       \n';
        sConteudo   += '          <div id="ctnGrid" style="height:90%; width:99%;padding:0px">                  \n';
        sConteudo   += '          </div>                                                                        \n';
        sConteudo   += '      </div>                                                                            \n';
        sConteudo   += '    </fieldset>                                                                         \n';
        sConteudo   += '  </div>                                                                                \n';
        sConteudo   += '</div>                                                                                  \n';
        sConteudo   += '  <center>                                                                              \n';
        sConteudo   += '     <input type="button" name="processar" value="'+me.sBotao+'" id="processar"         \n';
        sConteudo   += '            onclick="'+me.sInstancia+'.processar()"; />                                 \n';
        sConteudo   += '  </center>                                                                             \n';
        
    return sConteudo;
  };
  
  /**
   * Cria a TreeView
   */
  this.criarTreeView = function () {
    
    me.oTreeViewAvaliacao = new DBTreeView('treeViewAvaliacao');
    me.oTreeViewAvaliacao.allowFind(true);
    me.oTreeViewAvaliacao.setFindOptions('matchedonly');
    me.oTreeViewAvaliacao.show($('ctnTreeView'));
    oNoPrincipal = me.oTreeViewAvaliacao.addNode("0", "Escola");
    
    var oParametro                = new Object();
    oParametro.exec               = 'getDadosDiario';
    oParametro.lProgressaoParcial = me.lProgressaoParcial;
    js_divCarregando("Aguarde, carregando dados do diário de classe.", "msgBox");
    var oAjax = new Ajax.Request('edu4_lancamentoavaliacao.RPC.php',
                                 {
                                   method:     'post',
                                   parameters: 'json='+Object.toJSON(oParametro),
                                   onComplete: js_retornaPesquisaDiario
                                 }
                                );
  };
  
  
  /**
   * Cria DataGrid
   */
  this.criarDataGridAlunos = function () {
    
    me.oDataGridAlunos               = new DBGridMultiCabecalho('gridAlunos');
    me.oDataGridAlunos.nameInstance  = sInstancia+'.oDataGridAlunos';
    me.oDataGridAlunos.setHeight($('ctnListaConteudoAlunos').getHeight() - 100);
    
    var aCellWidth   = new Array('8%', '5%', '58%', '25%', '5%');
    var aAlinharGrid = new Array('right', 'right', 'left', 'left', 'center', 'center');
    var aColunasGrid = new Array('Código', 'Ordem', 'Aluno', 'Resultado Final', 'Status');
    
    if (me.lProgressaoParcial) {
      
      me.oDataGridAlunos.setCheckbox(0);
      me.oDataGridAlunos.allowSelectColumns(true);
      aCellWidth   = new Array('5%', '5%', '54%', '8%', '8%', '20%');
      aAlinharGrid = new Array('right', 'right', 'left', 'left', 'left', 'center', 'center');
      aColunasGrid = new Array('Código', 'Ordem', 'Aluno', 'Disciplina', 'Encerrado', 'Resultado Final', 'Progressão');  
    }
    
    me.oDataGridAlunos.setCellWidth(aCellWidth);
    me.oDataGridAlunos.setCellAlign(aAlinharGrid);
    me.oDataGridAlunos.setHeader(aColunasGrid);
    
    if (me.lProgressaoParcial) {
      me.oDataGridAlunos.aHeaders[7].lDisplayed = false;
    }
    
    me.oDataGridAlunos.show($('ctnGrid'));
    $('ctnTreeView').style.height = $('ctnListaConteudoAlunos').getHeight() - 60;
  };
  
  /**
   * Retorna os dados para montagem da TreeView
   */
  js_retornaPesquisaDiario = function(oResponse) {
    
    js_removeObj("msgBox");
    var oRetorno = eval('('+oResponse.responseText+')');
    
    if(oRetorno.status == 2) {
      
      alert(oRetorno.message.urlDecode());
    } else {
      
      oCheck = function (oNode, event) {

        if (oNode.checkbox.checked) {
          oNode.checkAll(event);
          
        } else {
          oNode.uncheckAll(event);
        }
      };
      
      var oCheckBox      = new Object();
      oCheckBox.onClick  = oCheck;
      oCheckBox.checked  = false;
      oCheckBox.disabled = false;
      
      if (me.lProgressaoParcial) {
        oCheckBox = '';
      }
      
      oRetorno.dados.each(function(oCalendario, iSeq) {
        
        me.oTreeViewAvaliacao.addNode(oCalendario.sIdCalendario,
                                      oCalendario.sDescricaoCalendario.urlDecode(),
                                      '0',
                                      '',
                                      '',
                                      oCheckBox,
                                      null,
                                      {'lProcessa':false}
                                      );
        
        oCalendario.aEnsinos.each(function(oEnsino, iSeq) {

          me.oTreeViewAvaliacao.addNode(oEnsino.sIdEnsino.urlDecode(),
                                        oEnsino.sDescricaoEnsino.urlDecode(),
                                        oCalendario.sIdCalendario,
                                        '',
                                        '',
                                        oCheckBox,
                                        null,
                                        {'lProcessa':false}
                                       );
          
          oEnsino.aBases.each(function(oBase, iSeq) {
            
            me.oTreeViewAvaliacao.addNode(oBase.sIdBase.urlDecode(),
                                          oBase.sDescricaoBase.urlDecode(),
                                          oEnsino.sIdEnsino,
                                          '',
                                          '',
                                          oCheckBox,
                                          null,
                                          {'lProcessa':false}
                                         );
            
            oBase.aEtapas.each(function(oEtapa, iSeq) {
              
              me.oTreeViewAvaliacao.addNode(oEtapa.sIdEtapa.urlDecode(),
                                            oEtapa.sDescricaoEtapa.urlDecode(),
                                            oBase.sIdBase,
                                            '',
                                            '',
                                            oCheckBox,
                                            null,
                                            {'lProcessa':false}
                                           );
              
              oEtapa.aTurmas.each(function(oTurma, iSeq) {
                
                var oCheckBoxTurma      = new Object();
                oCheckBoxTurma.disabled = oCheckBox.disabled;
                oCheckBoxTurma.checked  = oCheckBox.checked;
                
                if (oTurma.lEncerrada && me.iEncerra == 1) {
                  
                  oCheckBoxTurma.disabled = true;
                  oCheckBoxTurma.checked  = false;
                } else if (!oTurma.lEncerrada && me.iEncerra == 2) {
                  
                  if (!oTurma.lEncerradaParcial) {
                    oCheckBoxTurma.disabled = true;
                  }
                  oCheckBoxTurma.checked  = false;
                }
                
                if (me.lProgressaoParcial) {
                  oCheckBoxTurma = '';
                }
                
                me.oTreeViewAvaliacao.addNode(oTurma.sIdTurma.urlDecode(),
                                              oTurma.sDescricaoTurma.urlDecode(),
                                              oEtapa.sIdEtapa,
                                              false,
                                              '',
                                              oCheckBoxTurma,
                                              function(oTurma, Evento) {
                                                me.setCallBackVisualizaDados(oTurma, oEtapa.iEtapa);
                                              },
                                              {'lProcessa':true,'iTurma':oTurma.iTurma.urlDecode(),
                                               'iEtapa':oEtapa.iEtapa
                                              }
                                              );
              });
            });
          });
        });
      });
    }
  };
  
  /**
   * Busca os dados dos alunos matriculados na turma, para preenchimento da Grid
   */
  var oTurmaAnterior = '';
  var iEtapaAnterior = null;
  
  me.setCallBackVisualizaDados = function (oTurma, iEtapa) {
    
    var oParametro  = new Object();    
    
    oParametro.iCodigoTurma = oTurma.iTurma;
    oParametro.iEtapa       = iEtapa;
    
    if (!me.lProgressaoParcial) {
      oParametro.iMostrarTrocaTurma = $F('trocaTurma');
    }
    
    if (oTurmaAnterior != "") {
      oTurmaAnterior.select(false);
    }
    oTurma.select(true);
    oTurmaAnterior = oTurma;
    iEtapaAnterior = iEtapa;
    
    //Verifica se a turma tem aulas dadas para poder liberar o botão de encerramento
    oParametro.exec = 'verificaTurmaSemAulasDadas';
    
    var oAjax = new Ajax.Request('edu4_encerramentoavaliacao.RPC.php',
                                  {
                                    method:     'post',
                                    parameters: 'json='+Object.toJSON(oParametro),
                                    onComplete: function (oResponse) {
                                      
                                                  var oRetorno = eval('('+oResponse.responseText+')');
                                                  if (oRetorno.status == 1) {
                                                    $('processar').disabled = oRetorno.lTurmaEncerrada;
                                                  }
                                                }
                                  }
                                );
    
    oParametro.exec = 'getAlunosMatriculados';
    if (me.lProgressaoParcial) {
      oParametro.exec = 'getAlunosDeProgressao';
    };
    
    //Carrega os alunos matriculados na turma
    js_divCarregando("Aguarde, carregando os alunos matriculados na turma.", "msgBox");
    var oAjax = new Ajax.Request('edu4_encerramentoavaliacao.RPC.php',
                                 {
                                   method:     'post',
                                   parameters: 'json='+Object.toJSON(oParametro),
                                   onComplete: js_montaGridAlunos
                                 }
                                );
    
  };
  
  /**
   * Monta a Grid com os dados retornados
   */
  js_montaGridAlunos = function(oResponse) {
    
    js_removeObj("msgBox");
    var oRetorno                 = eval('('+oResponse.responseText+')');
    var iTamanhoRetorno          = oRetorno.aDadosAlunos.length;
    
    me.oDataGridAlunos.clearAll(true);
    
    if (iTamanhoRetorno == 0) {
      
      alert('Não há alunos na turma solicitada');
      return false;
    }
    
    /**
     * Percorremos os alunos para preencher a Grid
     */
    oRetorno.aDadosAlunos.each(function(oAluno, iSeq) {
      
      var aLinha  = new Array();
      var sStatus = "";
      
      if (!oAluno.lAptoEncerrar) {

        sStatus = "<span><img src='imagens/ecidade-warning.png' border='0'></span>";
      }
      if (oAluno.lConcluido) {
        sStatus = "<span><img src='imagens/gtk_ok.png' border='0'></span>";
      }
      
      aLinha[0]  = oAluno.iCodigoAluno;
      aLinha[1]  = oAluno.iOrdemAluno;
      aLinha[2]  = oAluno.sNomeAluno.urlDecode();

      if (me.lProgressaoParcial) {
        
        aLinha[3]  = oAluno.sDisciplina.urlDecode();
        aLinha[4]  = oAluno.sEncerrado.urlDecode();  
        aLinha[5]  = oAluno.sResultadoFinal.urlDecode();
        aLinha[6]  = oAluno.iProgressao;
      } else {
        
        aLinha[3]  = oAluno.sResultadoFinal.urlDecode();
        aLinha[4]  = sStatus;
      }
      
      var lDesabilitaAluno = false;
      
      if (me.iEncerra == 1 &&  oAluno.lEncerrado ||
          me.iEncerra == 2 && !oAluno.lEncerrado) {
        lDesabilitaAluno = true;
      }
      
      me.oDataGridAlunos.addRow(aLinha, false, lDesabilitaAluno);
      
      if (lDesabilitaAluno == true) {
        me.oDataGridAlunos.aRows[iSeq].setClassName('disabled');
      }
      
      if (!me.lProgressaoParcial) {
        me.oDataGridAlunos.aRows[iSeq].sEvents += "onClick='"+me.sInstancia+".setCallBackLancamentoAvaliacoes(\""+oAluno.iMatricula+" \");'";
      }
    });
    me.oDataGridAlunos.renderRows();
    /**
     * Percorremos o aluno para identificar aqueles que nao estao aptos a encerrar, e apresentar uma mensagem com 
     * as pendencias
     */
    oRetorno.aDadosAlunos.each(function(oAluno, iSeq) {

      var sMensagem = "";
      
      if (!oAluno.lAptoEncerrar) {
        
        var oCelulaStatus     = $(me.oDataGridAlunos.aRows[iSeq].aCells[4].sId);
        var oDBHintPendencias = eval("oDBHint_"+iSeq+"_1 = new DBHint('oDBHint_"+iSeq+"_1')");

        oAluno.aPendencias.each(function (sPendencia) {
          sMensagem += "* "+sPendencia+"<br>";
        });
        
        oDBHintPendencias.setText(sMensagem.urlDecode());
        oDBHintPendencias.setWidth(500);
        oDBHintPendencias.setUseMouse(true);
        oDBHintPendencias.setShowEvents(["onmouseover"]);
        oDBHintPendencias.setHideEvents(["onmouseout"]);
        oDBHintPendencias.setPosition('B', 'L');
        oDBHintPendencias.make(oCelulaStatus);
      }
    });    
    
  }
  
  
  
  /**
   * Chama a Grid com as disciplinas do aluno selecionado
   */
  me.setCallBackLancamentoAvaliacoes = function(iMatricula) {
      
    delete me.oViewAvaliacao;
    me.oViewAvaliacao = new DBViewConsultaAvaliacoesAluno(me.sInstancia + ".oViewAvaliacao", iMatricula);
    me.oViewAvaliacao.show();
  }
  
  /**
   * Carrega as funções iniciais da tela
   * renderizarHTML(), criarDataGridAlunos(), criarTreeView()
   */
  this.show = function ( oElementoDestino ) {
    
    /**
     * Renderizando HTML
     */
    var sHtml = renderizarHTML();
    oElementoDestino.innerHTML = sHtml;
    me.criarDataGridAlunos();
    
    /**
     * Caso o codigo da turma e da etapa tenham sido setados, nao cria a TreeView
     */
    if (js_empty(me.iTurma) && js_empty(me.iEtapa)) {
      me.criarTreeView();
    } else {
      
      /**
       * Criamos um MessageBoard quando a chamada não for originada diretamente da rotina de encerramento
       * Setamos um objeto oTurma para enviar ao metodo setCallBackVisualizaDados
       */
      var sTitulo  = 'Turma: '+me.sTurma;
      var sAjuda   = 'Para verificar informações sobre o aluno, dê um duplo clique no nome do mesmo.';
      new DBMessageBoard('msgAvaliacoes', sTitulo, sAjuda, $('divMessage'));
      
      var oTurma        = new Object();
          oTurma.iTurma = me.iTurma;
          oTurma.select = function(){};
          
      me.setCallBackVisualizaDados(oTurma, me.iEtapa);
    }
  };
  
  this.setProgressaoParcial = function(lProgressaoParcial) {
    me.lProgressaoParcial = lProgressaoParcial;
  }; 
  
  
  /**
   * Verifica o processamento que deve ser feito  
   */
  me.processar = function () {
    
    switch(me.iEncerra) {
      
      case '1':
        
        if (me.lProgressaoParcial) {
          me.encerraProgressaoParcial(); // Encerra progressao parcial
        } else {
          me.encerraCalendarioLetivo(); // Encerra calendario letivo          
        }
        break;
      case '2':
        
        if (me.lProgressaoParcial) {
          me.cancelaProgressaoParcial(); // Cancela progressao parcial
        } else {
          me.cancelaEncerramentoCalendarioLetivo(); // Cancela calendario letivo
        }
        break;
    }
  };
  
  /**
   * Busca as turma selecionadas na tree view
   * Caso tenham sido setados iTurma e iEtapa, criamos o array com estes valores. Caso contrario, percorremos a arvore
   * para verificar as turmas selecionadas
   */
  this.buscaSelecaoDeTurmasNaTreeView = function () {
    
    var aTurma = new Array();
    
    if (!js_empty(me.iTurma) && !js_empty(me.iEtapa)) {
      
      var oTurma      = new Object();
        oTurma.iTurma = me.iTurma;
        oTurma.iEtapa = me.iEtapa;
        
      aTurma.push(oTurma);
    } else {
      
      var aSelecionados = me.oTreeViewAvaliacao.getNodesChecked();
      aSelecionados.each(function(oNode) {
        
        var oTurma = new Object();
        if (!oNode.checkbox.disabled && oNode.lProcessa) {
          
          oTurma.iTurma = oNode.iTurma;
          oTurma.iEtapa = oNode.iEtapa;
          aTurma.push(oTurma);
        }
      });
    }
    
    return aTurma;
  };
  
  /**
   * Busca os alunos seleciaonados na grid dos alunos de uma turma
   */
  this.buscaAlunosSelecionados = function() {
 
    var aAlunosSelecionados = me.oDataGridAlunos.getSelection( "object" );
    
    var aAlunos = new Array();
    
    aAlunosSelecionados.each(function (oAlunoLinha, iSeq) {
      
      var oAluno         = new Object();
      oAluno.iProgressao = oAlunoLinha.aCells[7].getValue();
      aAlunos.push(oAluno);
    });
    
    return aAlunos;
  };
  
  /**
   * Encerra turmas do calendario letivo
   * @todo Metodo não foi 100% implementado. Falta fazer o RPC 
   */
  this.encerraCalendarioLetivo = function () {
    
    aTurmas = me.buscaSelecaoDeTurmasNaTreeView();
    
    if (aTurmas.length == 0) {
      
      alert("Nenhuma turma selecionada.");
      return false;
    }
    
    if (confirm('Confirmar o encerramento da(s) turma(s) selecionada(s)?')) {
      
      var oParametro    = new Object();
      oParametro.exec   = 'encerrarAvaliacoes';
      oParametro.aTurmas = aTurmas;
      
      js_divCarregando("Aguarde, encerrando avaliações.", "msgBox");
      var oAjax = new Ajax.Request('edu4_encerramentoavaliacao.RPC.php',
                                   {
                                     method:     'post',
                                     parameters: 'json='+Object.toJSON(oParametro),
                                     onComplete: me.retornoProcessamento
                                   }
                                  );
      
      };
    }
  
  /**
   * Encerra turmas de progressao parcial
   */
  this.encerraProgressaoParcial = function () {
    
    var aAlunos = me.buscaAlunosSelecionados();
    
    if (aAlunos.length == 0) {
      
      alert("Não há nenhuma turma/aluno selecionado.");
      return false;
    }
    
    if (confirm('Confirmar o encerramento das Progressões selecionadas?')) {
      
      var oParametro     = new Object();
      oParametro.exec    = 'encerrarProgressaoParcial';
      oParametro.aAlunos = aAlunos;
      
      js_divCarregando("Aguarde, encerrando avaliações.", "msgBox");
      var oAjax = new Ajax.Request('edu4_encerramentoavaliacao.RPC.php',
                                   {
                                     method:     'post',
                                     parameters: 'json='+Object.toJSON(oParametro),
                                     onComplete: me.retornoProcessamento
                                   }
                                  );
    }
  };
  
  
  /**
   * Cancela o encerramento de turmas de progressao parcial
   */
  this.cancelaProgressaoParcial = function () {
    
    var aAlunos = me.buscaAlunosSelecionados();
    
    if (aAlunos.length == 0) {
      
      alert("Não há nenhuma turma/aluno selecionado.");
      return false;
    }
    
    if (confirm('Confirmar o cancelamento do encerramento das Progressões selecionadas?')) {
      
      var oParametro     = new Object();
      oParametro.exec    = 'cancelarEncerramentoProgressaoParcial';
      oParametro.aAlunos = aAlunos;
      
      js_divCarregando("Aguarde, cancelando encerramento das avaliações.", "msgBox");
      var oAjax = new Ajax.Request('edu4_encerramentoavaliacao.RPC.php',
                                   {
                                     method:     'post',
                                     parameters: 'json='+Object.toJSON(oParametro),
                                     onComplete: me.retornoProcessamento
                                   }
                                  );
    }
  };
  
  /**
   * Cancela o encerramento de turmas de calendario letivo
   */
  this.cancelaEncerramentoCalendarioLetivo = function () {
    
    aTurmas = me.buscaSelecaoDeTurmasNaTreeView();
    
    if (aTurmas.length == 0) {
      
      alert("Nenhuma turma selecionada.");
      return false;
    }
    
    if (confirm('Confirmar o cancelamento do encerramento das turmas selecionadas?')) {
      
      var oParametro    = new Object();
      oParametro.exec   = 'cancelarEncerramento';
      oParametro.aTurmas = aTurmas;
      
      js_divCarregando("Aguarde, cancelando encerramento das avaliações.", "msgBox");
      var oAjax = new Ajax.Request('edu4_encerramentoavaliacao.RPC.php',
                                   {
                                     method:     'post',
                                     parameters: 'json='+Object.toJSON(oParametro),
                                     onComplete: me.retornoProcessamento
                                   }
                                  );
      
      };
  };
  
  this.retornoProcessamento = function(oResponse) {
    
    js_removeObj("msgBox");    
    var oRetorno = eval('('+oResponse.responseText+')');
    
    if (oRetorno.status == 1) {
      
      alert(oRetorno.message.urlDecode());
      if (oRetorno.aTurmasProcessadas) {
        
        var aSelecionados = new Array();
        
        if (me.oTreeViewAvaliacao != null) {
          
          aSelecionados = me.oTreeViewAvaliacao.getNodesChecked();
        }
        
        aSelecionados.each(function(oNode) {
          
          if (!oNode.checkbox.disabled && oNode.lProcessa) {
            oRetorno.aTurmasProcessadas.each(function(oTurma, iTurma) {
              if (oNode.iTurma == oTurma.iTurma && oNode.iEtapa == oTurma.iEtapa) {

                me.oTreeViewAvaliacao.setChecked(null, oNode.marker);
                oNode.setDisabled(true);
              }
            });
          };
        });
      }
    } else {
    
      var sMsg  = oRetorno.message.urlDecode() + "\n";
          sMsg += "Deseja imprimir um relatório com pendências existentes?";
          
      if (confirm(sMsg)) {
        
        var sUrl = "";
        
        if (me.lProgressaoParcial) {
          
          sUrl  = "edu2_pendenciasprogressaoparcial002.php";
          sUrl += "?&iTurma="+oTurmaAnterior.iTurma;
        } else {
          sUrl = "edu2_pendenciascalendarioletivo002.php";
        }
        
        jan = window.open(sUrl, '',
                          'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
        jan.moveTo(0,0);
      }
    }
    
    me.oCallBackProcessar(oRetorno.aTurmasProcessadas);
    if (oTurmaAnterior != '') {
      me.setCallBackVisualizaDados(oTurmaAnterior, iEtapaAnterior);
    }
  };
  
  /**
   * Seta um codigo de turma, codigo de etapa e o nome da turma, altera o sDisplay para none e aumenta o tamanho da 
   * janela com os alunos
   * @param iTurma - Codigo da turma
   * @param iEtapa - Codigo da etapa
   * @param sTurma - Nome da turma
   */
  this.setTurmaEtapa = function(iTurma, iEtapa, sTurma) {
    
    me.iTurma              = iTurma;
    me.iEtapa              = iEtapa;
    me.sTurma              = sTurma;
    me.sDisplay            = 'display: none;';
    me.sLarguraDivConteudo = '100%';
  };
  
  this.setCallbackProcessar = function(fFunction) {
    me.oCallBackProcessar = fFunction;
  };
};

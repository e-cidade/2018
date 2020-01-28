DBViewLancamentoProgressaoParcialAluno = function(sInstancia, aVinculosAlunoNaTurma, aMatriculasTurma, iTurma, iEtapa) {
  
  this.oDataGridDisciplina = null;  
  this.oWindowAuxAvaliacao = null;
  this.sInstancia          = sInstancia;
  this.aVinculosNaTurma    = aVinculosAlunoNaTurma.split(",");
  this.iMatriculas         = aVinculosAlunoNaTurma;
  this.aMatriculasTurma    = aMatriculasTurma;
  this.iCodigoAluno        = null;
  this.sNomeTurma          = null;
  this.sSituacaoAluno      = null;
  this.sNomeAluno          = null;
  this.iTabIndex           = null;
  this.sMascaraFormatacao  = null;
  this.sDataMatricula      = '';
  this.sCalendario         = '';
  this.sDataSaida          = '';
  this.iTurmaAluno         = iTurma;
  this.iEtapa              = iEtapa;
  this.aPeriodosAluno      = new Array();
  this.aDisciplinas        = new Array();
  this.lDadosAlterados     = false;
  this.lDadosSalvo         = false;
  this.aAvaliacao          = new Array();
  this.aTermos             = new Array();
  this.iTamanhoJanela      = document.body.getWidth() / 2;
  this.sRPC                = "edu4_lancamentoavaliacao.RPC.php";
  var oInstancia           = this;
  
  /**
   * Renderiza a Window
   */
  this.renderizarWindowAvaliacao = function () {
    
    oInstancia.oWindowAuxAvaliacao = new windowAux("wndAlunoAvaliacao", 
                                                   "Lançamento Avaliação", 
                                                   oInstancia.iTamanhoJanela,
                                                   460
                                                   );
    oInstancia.oWindowAuxAvaliacao.setShutDownFunction(function () {
      oInstancia.oWindowAuxAvaliacao.destroy();
    });
    oInstancia.oWindowAuxAvaliacao.allowCloseWithEsc(false);
    var iMatriculaAnterior = this.matriculaAnterior();
    var iProximaMatricula  = this.proximaMatricula();
    var sDisbledAnterior   = '';
    var sDisbledProximo    = '';
    if (iMatriculaAnterior == null) {
      sDisbledAnterior   = " disabled='disabeld'";
    }
    if (iProximaMatricula == null) {
      sDisbledProximo    = " disabled='disabeld'";
    }
    
    var sConteudo    = "<div id='disciplinas_aluno'>                                                  \n";
        sConteudo   += "  <div id='divListaGrupo'>                                                    \n";
        sConteudo   += "    <fieldset style='width:98%; margin-bottom:10px;'>                         \n";
        sConteudo   += "      <legend><b>Disciplinas</b></legend>                                     \n";
        sConteudo   += "      <div id='grid_disciplinas' style='width:100%;'></div>                    \n";
        sConteudo   += "    </fieldset>                                                               \n";
        sConteudo   += "    <center>                                                                  \n";
        sConteudo   += "      <input type='button' id='anterior' value='Anterior' name='anterior'     \n";
        sConteudo   += "             onclick='"+oInstancia.sInstancia+".recriaView(\""+iMatriculaAnterior+"\");' "+sDisbledAnterior+" /> \n";
        sConteudo   += "      <input type='button' id='salvar' value='Salvar' name='salvar'           \n";
        sConteudo   += "             onclick='"+oInstancia.sInstancia+".salvar();'/>                  \n";
        sConteudo   += "      <input type='button' id='proximo' value='Próximo' name='proximo'        \n";
        sConteudo   += "             onclick='"+oInstancia.sInstancia+".recriaView(\""+iProximaMatricula+"\");' "+sDisbledProximo+" /> \n";
        sConteudo   += "    </center>                                                                 \n";
        sConteudo   += "  </div>                                                                      \n";
        sConteudo   += "</div>                                                                        \n";
        
        
    oInstancia.oWindowAuxAvaliacao.setShutDownFunction(function() {
      
      if (oInstancia.lDadosAlterados && !oInstancia.lDadosSalvo) {
        
        if (!confirm('Grade de avaliação do aluno alterada. Deseja fechar sem salvar?')) {
          return true;
        }
      }
      oInstancia.oWindowAuxAvaliacao.destroy();
    });
        
    oInstancia.oWindowAuxAvaliacao.setContent(sConteudo);
    oMessageBoard = new DBMessageBoard('msgBoardAvaliacao', 
                                       'Aluno: ' + oInstancia.iCodigoAluno + ' - ' +oInstancia.sNomeAluno,
                                       ' Turma: <b>' + oInstancia.sTurmaAluno + '</b> '+
                                       '  Data de Vínculo: <b>' + oInstancia.sDataMatricula+ "</b>  "+
                                       '  Calendário:<b>' + oInstancia.sCalendario+'</b>',
                                       oInstancia.oWindowAuxAvaliacao.getContentContainer()
                                      );    
    
    oInstancia.oWindowAuxAvaliacao.show();
    oInstancia.oWindowAuxAvaliacao.addEvent('keyup', function (Event) {
      
      
      var iTecla = Event.which; 
      if (Event.altKey) {
        
        if ((iTecla >= 48 && iTecla <= 57) || (iTecla >= 96 && iTecla <= 105)) {
          
          var iIndex = String.fromCharCode(iTecla);
          if ($('cmbTabIndex').options[iIndex - 1]) {
            
            $('cmbTabIndex').value = $('cmbTabIndex').options[iIndex - 1].value;
            oInstancia.reordenaTabIndex($('cmbTabIndex').value);
          }
        }
      }
      
      if (Event.ctrlKey) {
        
        if (iTecla == 83) {
          oInstancia.salvar();
        }          
        
      }
      if (iTecla == 13) {
        
        oInstancia.salvar();
      }
      Event.preventDefault();
      Event.stopPropagation();
    });
  }
  
  /**
   * Cria o cabecalho da grig calculando os periodos dinamicamente
   */
  this.criarDataGridAvaliacaoAluno = function () {
    
    var aAling  = new Array('left');
    var aHeader = new Array('Disciplina', "Nota Final", "Total de Faltas", "Resultado Final", "Progressao");
    var aWidth  = new Array('50%', "10%", "10%", "30%", '5%');

    /**
     * Calculo do tamanho das colunas dos periodos
     */
    
    oInstancia.oDataGridDisciplina               = new DBGridMultiCabecalho('gridAlunoDisciplina');
    oInstancia.oDataGridDisciplina.sNameInstance = sInstancia+'.oDataGridDisciplina';
    oInstancia.oDataGridDisciplina.setCellWidth(aWidth);
    oInstancia.oDataGridDisciplina.setCellAlign(aAling);
    oInstancia.oDataGridDisciplina.setHeader(aHeader);
    oInstancia.oDataGridDisciplina.aHeaders[4].lDisplayed = false;
    oInstancia.oDataGridDisciplina.setHeight(200);
    oInstancia.oDataGridDisciplina.show($('grid_disciplinas'));
    oInstancia.oDataGridDisciplina.clearAll(true);
  }
  
  /**
   * Buscamos os dados dos periodos de avaliacao
   */
  this.getDadosVinculo = function() {
    
    var oObject        = new Object();
    oObject.exec       = 'getDadosVinculoAluno';
    oObject.aVinculos  = oInstancia.aVinculosNaTurma;
    oObject.iTurma     = oInstancia.iTurmaAluno;
    oObject.iEtapa     = oInstancia.iEtapa;
    var oAjax = new Ajax.Request(oInstancia.sRPC,
                                 {method: 'post',
                                  parameters:'json='+Object.toJSON(oObject),
                                  onComplete: oInstancia.retornogetDadosVinculo,
                                  asynchronous:false
                                 } 
                                );
  }
  
  /**
   * Preenche os dados dos periodos. 
   * e os dados do aluno/turma
   */
  this.retornogetDadosVinculo = function(oAjax) {
    
    var oRetorno = eval("("+oAjax.responseText+")");
    
    if (oRetorno.status == 2) {
      alert(oRetorno.message.urlDecode());
    }
    
    oInstancia.iCodigoAluno       = oRetorno.iCodigoAluno;
    oInstancia.iTurmaAluno        = oRetorno.iCodigoTurma;
    oInstancia.sNomeAluno         = oRetorno.sNomeAluno.urlDecode();
    oInstancia.sTurmaAluno        = oRetorno.sTurma.urlDecode();
    oInstancia.sSituacaoAluno     = '';
    oInstancia.iTabIndex          = oRetorno.iTabIndex;
    oInstancia.sDataMatricula     = oRetorno.dtMatricula;
    oInstancia.sCalendario        = oRetorno.sCalendario.urlDecode();  
    oInstancia.sMascaraFormatacao = oRetorno.sMascaraFormatacao;
    oInstancia.oDados             = oRetorno;
    
    oRetorno.aTermos.each(function(oLinha, iContador) {
      oInstancia.aTermos.push(oLinha);
    });
  }   
  
  
  this.liberarSalvar = function () {
    oInstancia.lDadosAlterados = true; 
  }
  /**
   * Popula a grid com os dados da Disciplina e os input para lancamento das notas e faltas
   */
  this.popularGrid = function () {
    
    
    oInstancia.oDataGridDisciplina.clearAll(true);
    var sFuncaoOnFocus = oInstancia.sInstancia + ".sinalizarLinhaGrid(this, true);";
    var sFuncaoOnBlur  = oInstancia.sInstancia + ".sinalizarLinhaGrid(this, false);";
    var sLiberarSalvar = oInstancia.sInstancia + ".liberarSalvar();";
    oInstancia.oDados.aDisciplinas.each( function(oDisciplina, id) {
      
      
      
      var sDisabled = ' ';
      if (oDisciplina.lEncerrada) {
        sDisabled = ' disabled ';
      }
      mValorTipoAvaliacao  = "<input id='nota_"+oDisciplina.iCodigoProgressao+"'";
      mValorTipoAvaliacao += "onFocus='"+sFuncaoOnFocus+"' ";
      mValorTipoAvaliacao += "onBlur='"+sFuncaoOnBlur+"' ";
      mValorTipoAvaliacao += "onChange='"+sLiberarSalvar+"' ";
      mValorTipoAvaliacao += sDisabled;
      mValorTipoAvaliacao += " value='"+oDisciplina.iNota.urlDecode()+"' type='text'";
      mValorTipoAvaliacao += "style='background-color:transparent;width:100%;height:100%; text-align:right' />";
      
      sCampoFalta  = "<input id='falta_"+oDisciplina.iCodigoProgressao+"'";
      sCampoFalta += "onFocus='"+sFuncaoOnFocus+"' ";
      sCampoFalta += "onBlur='"+sFuncaoOnBlur+"' ";
      sCampoFalta += "onChange='"+sLiberarSalvar+"' ";
      sCampoFalta += "maxlength=3 ";
      sCampoFalta += sDisabled;
      sCampoFalta += "onKeyPress='return js_mask(event, \"0-9\")'";
      sCampoFalta += " value='"+oDisciplina.iFalta+"' type='text'";
      sCampoFalta += "style='background-color:transparent;width:100%;height:100%; text-align:right' />";
      
      oComboResultado = new DBComboBox ("resultado_"+oDisciplina.iCodigoProgressao,
                                        "resultado_"+oDisciplina.iCodigoProgressao,
                                         new Array(), "100%"
                                       );
      oComboResultado.setValue();
      oComboResultado.addEvent("onChange", sLiberarSalvar);
      
      oInstancia.aTermos.each(function(oTermo, iSeq) {
        oComboResultado.addItem(oTermo.sReferencia.urlDecode(), oTermo.sDescricao.urlDecode());
      });
      if (oDisciplina.lEncerrada) {
        oComboResultado.setDisable();
      }
      oComboResultado.addStyle("background-color", "transparent");
      oComboResultado.setValue(oDisciplina.sResultadoFinal);
      var sResultadoFinal = oComboResultado.toInnerHtml();
      
      var aLinha = new Array();
      aLinha[0]  = oDisciplina.sDisciplina.urlDecode();
      aLinha[1]  = mValorTipoAvaliacao;
      aLinha[2]  = sCampoFalta;
      aLinha[3]  = sResultadoFinal;
      aLinha[4]  = oDisciplina.iCodigoProgressao;
      
      oInstancia.oDataGridDisciplina.addRow(aLinha);
      if (oDisciplina.lEncerrada) {
        oInstancia.oDataGridDisciplina.aRows[id].setClassName('disabled');
      } else {
        
        oInstancia.oDataGridDisciplina.aRows[id].sEvents += "onmouseover='"+oInstancia.sInstancia+".sinalizarLinhaGrid(this, true);'";
        oInstancia.oDataGridDisciplina.aRows[id].sEvents += "onmouseout='"+oInstancia.sInstancia+".sinalizarLinhaGrid(this, false);'";        
      }
    });
    oInstancia.oDataGridDisciplina.renderRows();
  }
  
  /**
   * Limpa todos os tabIndex da grid
   */
  this.limpaTabIndex = function() {
    
    var iNumeroColunas = oInstancia.aPeriodosAluno.length;
    for (var i = 0; i < iNumeroColunas; i++) {
      
      $$("."+i+"_nota").each(function (oElemento, id) {
        
        oElemento.removeAttribute("tabIndex");
      });
      
      $$("."+i+"_falta").each(function (oElemento, id) {
        
        oElemento.removeAttribute("tabIndex");
      });
    }
    $('salvar').removeAttribute("tabIndex");
  }

  /**
   * Funcao para ordenar o tabIndex de acordo com o parametro configurado para a escola
   * O TabIndex obedece o parametro (Deslocamento do Cursor) e 
   * ordena pelo periodo selecionado (ComboBox "Lançar disciplinas por periodo")   
   */
  this.reordenaTabIndex = function(iPeriodoSelecionado) {
    
    //oInstancia.limpaTabIndex();
    
    switch (oInstancia.iTabIndex) {
    
      case '1':
        oInstancia.tabIndexNotaFalta(iPeriodoSelecionado);
        break;
      case '2':
        
        oInstancia.tabIndexNotaNota(iPeriodoSelecionado);
        break;
      default:
        oInstancia.tabIndexNotaFalta(iPeriodoSelecionado);
        break;
    }
    
    $$("."+iPeriodoSelecionado+'_nota').each(function (oNota, iInd) {
      
      oNota.focus();
      throw $break;  
    });
  }
  
  /**
   * Ordena o tabIndex do input nota para o input falta do 
   * periodo selecionado (ComboBox  "Lançar disciplinas por periodo")
   */
  this.tabIndexNotaFalta = function(iPeriodoSelecionado) {
    
    var iIndex    = 1;
    var aPeriodos = $('cmbTabIndex').options;
    for (var iPer = 0; iPer <  aPeriodos.length; iPer++) {
      
      iPeriodoSelecionado = aPeriodos[iPer].value;
      $$("."+iPeriodoSelecionado+'_nota').each(function (oNota, iInd) {
        
        oNota.setAttribute('tabIndex', iIndex);
        iIndex += 2;
      });
    }
    iIndex = 2;
    for (var iPer = 0; iPer <  aPeriodos.length; iPer++) {
      
      iPeriodoSelecionado = aPeriodos[iPer].value;
      $$("."+iPeriodoSelecionado+'_falta').each(function (oFalta, iInd) {
        
        oFalta.setAttribute('tabIndex', iIndex);
        iIndex += 2;
      });
    
    }
    $('salvar').setAttribute('tabIndex', iIndex);
  }
  
  /**
   * Ordena o tabIndex do input nota para o proximo input nota do 
   * periodo selecionado (ComboBox  "Lançar disciplinas por periodo")
   */
  this.tabIndexNotaNota = function(iPeriodoSelecionado) {
    
    var iIndex    = 1;
    var aPeriodos = $('cmbTabIndex').options;
    for (var iPer = 0; iPer <  aPeriodos.length; iPer++) {
      
      iPeriodoSelecionado = aPeriodos[iPer].value;
      $$("."+iPeriodoSelecionado+'_nota').each(function (oNota, iInd) {
        
        oNota.setAttribute('tabIndex', iIndex);
        iIndex++;
      });
    }
    $('salvar').setAttribute('tabIndex', iIndex);
  }
  
  this.show = function ( oElementoDestino ) {
    
    oInstancia.getDadosVinculo();
    oInstancia.renderizarWindowAvaliacao();
    oInstancia.criarDataGridAvaliacaoAluno();
    oInstancia.popularGrid();
  }
  
  
  /**
   * Persiste os dados 
   */
  this.salvar = function() {
    
    if (!oInstancia.lDadosAlterados) {
      return true;
    }
    js_divCarregando("Salvando dados, aguarde...", "msgBox");
    var oObject           = new Object();
    oObject.exec          = 'salvarProgressaoAluno';
    oObject.aProgressoes  = new Array();
    oObject.iEtapa        = oInstancia.iEtapa;
    var aProgressoesAluno = oInstancia.oDataGridDisciplina.aRows;
    aProgressoesAluno.each(function(oLinha, iSeq) {
       
      var oProgressao = new Object();
      oProgressao.iCodigoProgressao = oLinha.aCells[4].getValue();
      oProgressao.sNota             = encodeURIComponent(tagString(oLinha.aCells[1].getValue()));
      oProgressao.iFaltas           = oLinha.aCells[2].getValue();
      oProgressao.sResultadoFinal   = encodeURIComponent(tagString(oLinha.aCells[3].getValue()));
      oObject.aProgressoes.push(oProgressao);
    });
      
    var oJson          = new Object();
    oJson.method       = 'post';
    oJson.parameters   = 'json='+Object.toJSON(oObject);
    oJson.onComplete   = oInstancia.retornoSalvar;
    oJson.asynchronous = false;
    
    var oAjax          = new Ajax.Request(oInstancia.sRPC, oJson);
  }
  
  this.retornoSalvar = function(oAjax) {
    
    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");
    if (oRetorno.status == 1) {
      
      oInstancia.lDadosSalvo     = true;
      oInstancia.lDadosAlterados = false;
    }
    alert(oRetorno.message.urlDecode());
  }
  
  /**
   * Retorna a matricula anterior a matricula atual
   * @return mixed
   */
  this.matriculaAnterior = function() {
    
    var iMatriculaAnterior = null;
    oInstancia.aMatriculasTurma.each(function (iMatricula, iIndice) {
      
      if (iMatricula == oInstancia.iMatriculas) {
        
        if (iIndice > 0) {
          iMatriculaAnterior = oInstancia.aMatriculasTurma[iIndice-1];
        }
      }
    });
    return iMatriculaAnterior;
    
  }
  
  /**
   * Retorna a proxima matricula a matricula atual
   * @return mixed
   */
  this.proximaMatricula = function() {
    
    var iMatriculaProxima = null;
    var iTamanhoArray     = oInstancia.aMatriculasTurma.length;
    
    oInstancia.aMatriculasTurma.each(function (iMatricula, iIndice) {
      
      if (iMatricula == oInstancia.iMatriculas) {
        
        if (iIndice < iTamanhoArray) {
          iMatriculaProxima = oInstancia.aMatriculasTurma[iIndice+1];
        }
      }
    });
    return iMatriculaProxima;
  }
  
  /**
   * Recria a view utilizando a nova matricula
   */
  this.recriaView = function (iMatricula) {
   
    if (oInstancia.lDadosAlterados && !oInstancia.lDadosSalvo) {
      
      if (!confirm('Grade de avaliação do aluno alterada. Deseja seguir sem salvar?')) {
        return true;
      }
    }
    oInstancia.oWindowAuxAvaliacao.destroy();
    var aMatriculas = oInstancia.aMatriculasTurma;
    var iTurmaAluno = oInstancia.iTurmaAluno;
    var iEtapa      = oInstancia.iEtapa;
    delete oInstancia;
    oNovaInstancia = new DBViewLancamentoProgressaoParcialAluno('oNovaInstancia', 
                                                                iMatricula, 
                                                                aMatriculas, 
                                                                iTurmaAluno,
                                                                iEtapa
                                                               );
    oNovaInstancia.show();
    
  }
  
  /**
   * Retorna os dados da avaliacao
   * @param {integer} iCodigoPeriodo Código do periodo
   * @param {integer} iCodigoRegencia Código da regencia
   * @return Object;
   */
  this.getValorAproveitamento = function(iCodigoAvaliacao) {
     
    var oRetorno  = ''; 
    oInstancia.aAvaliacao.each(function (oAvaliacaoDisciplina, iIndice) {
      
      if (oAvaliacaoDisciplina.iCodigoRegencia == iCodigoRegencia 
          && oAvaliacaoDisciplina.iPeriodo == iCodigoAvaliacao) {
        
        oRetorno = oAvaliacaoDisciplina;
        throw $break;
      }
    });
    return oRetorno;
  }
 
  /**
   * Retorna um periodo por codigo
   * @param {integer} iCodigoPeriodo
   * @return oPeriodo
   */
  this.getPeriodo = function(iCodigoPeriodo) {
  
    var oRetorno  = ''; 
    oInstancia.aPeriodosAluno.each(function (oPeriodo, iIndice) {
      
      if (oPeriodo.iCodigoAvaliacao == iCodigoPeriodo) {
        
        oRetorno = oPeriodo;
        throw $break;
      }
    });
    return oRetorno;
  }
  /**
   * deixa a linha atual da grid com cor destacada.
   */
  this.sinalizarLinhaGrid = function (oObjeto, lPintar) {
    
    if (oObjeto.nodeName == 'TR') {
      oLinha = oObjeto;
    }
    if (oObjeto.nodeName == 'INPUT') {
      oLinha = oObjeto.parentNode.parentNode;
    }
    var sCor      = 'white';
    var sCorFonte = 'black';
    if (lPintar) {
       
      // sCor      = '#2C7AFE';
      sCor       = 'rgb(240, 240, 240)';
       //sCorFonte = '';
    }   
    oLinha.style.backgroundColor = sCor;
    oLinha.style.color           = sCorFonte;
  }
}

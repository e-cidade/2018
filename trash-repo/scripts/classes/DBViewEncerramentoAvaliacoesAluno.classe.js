DBViewEncerramentoAvaliacoesAluno = function(sInstancia, iMatricula) {
  
  this.sInstancia           = sInstancia;
  this.iMatricula           = iMatricula;
  this.oGridAproveitamento  = null;  
  this.oWindowAuxAvaliacao  = null;
  this.iCodigoAluno         = null;
  this.iTurmaAluno          = null;
  this.sNomeTurma           = null;
  this.sSituacaoAluno       = null;
  this.sNomeAluno           = null;
  this.sDataMatricula       = '';
  this.sCalendario          = '';
  this.sDataSaida           = '';
  this.mMinimoAprovacao     = '';
  this.aGradeAproveitamento = new Array();
  this.iTamanhoJanela       = document.body.getWidth() - 10;
  this.sRPCEncerramento     = "edu4_encerramentoavaliacao.RPC.php";
  this.sRPCAluno            = "edu_dadosaluno.RPC.php";
  var me                    = this;
  
  /**
   * Renderiza a Window
   */
  this.renderizarWindowAvaliacao = function () {
    
    me.oWindowAuxAvaliacao = new windowAux("wndAlunoAvaliacao", 
                                                   "Grade de Aproveitamento", 
                                                   me.iTamanhoJanela
                                                   );
    me.oWindowAuxAvaliacao.setShutDownFunction(function () {
      me.oWindowAuxAvaliacao.destroy();
    });
    
    me.oWindowAuxAvaliacao.allowCloseWithEsc(false);
    
    var sConteudo    = "<div id='disciplinas_aluno'>                                                  \n";
        sConteudo   += "  <div id='divListaGrupo'>                                                    \n";
        sConteudo   += "    <fieldset style='width:98%'>                                              \n";
        sConteudo   += "    <fieldset style='width:98%; margin-bottom:10px;'>                         \n";
        sConteudo   += "      <legend><b>Grade de Aproveitamento</b></legend>                         \n";
        sConteudo   += "      <div id='gridAproveitamento' style='width:100%;'></div>                 \n";
        sConteudo   += "    </fieldset>                                                               \n";
        sConteudo   += "  </div>                                                                      \n";
        sConteudo   += "</div>                                                                        \n";
        
        
    me.oWindowAuxAvaliacao.setShutDownFunction(function() {
      
      me.oWindowAuxAvaliacao.destroy();
    });
        
    var sHelpMsgBox  = ' Turma: <b>' + me.sNomeTurma + '</b> ';
        sHelpMsgBox += ' Situa��o: <b>' +me.sSituacaoAluno+ "</b>";
        sHelpMsgBox += '  Data de Matr�cula: <b>' + me.sDataMatricula+ "</b>  ";
        sHelpMsgBox += '  Data de Sa�da:<b>' + me.sDataSaida+' </b> ';
        sHelpMsgBox += '  Calend�rio:<b>' + me.sCalendario+'</b></br>';
        sHelpMsgBox += '  <div style="text-indent: 15px;">M�nimo para Aprova��o:<b>' + me.mMinimoAprovacao + '</b></div>';
        
    
    me.oWindowAuxAvaliacao.setContent(sConteudo);
    oMessageBoard = new DBMessageBoard('msgBoardAvaliacao', 
                                       'Aluno: ' + me.iCodigoAluno + ' - ' +me.sNomeAluno,
                                       sHelpMsgBox,
                                       me.oWindowAuxAvaliacao.getContentContainer()
                                      );    
    
    me.oWindowAuxAvaliacao.show();
  };
  
  /**
   * Busca as informacoes para o cabecalho da Lockup 
   */
  this.buscaDadosMatricula = function () {
    
    var oObject        = new Object();
    oObject.exec       = 'buscaDadosMatricula';
    oObject.iMatricula = me.iMatricula;
    
    var oAjax = new Ajax.Request(me.sRPCEncerramento,
                                 {method: 'post',
                                  parameters:'json='+Object.toJSON(oObject),
                                  onComplete: me.retornoDadosMatricula,
                                  asynchronous:false
                                 } 
                                );
    
  };
  
  /**
   * Retorna as informacoes da matricula para preenchimento do messageBoard
   */
  this.retornoDadosMatricula = function(oAjax) {
  
    var oRetorno = eval("("+oAjax.responseText+")");
    
    me.sNomeAluno       = oRetorno.sNomeAluno.urlDecode();   
    me.iCodigoAluno     = oRetorno.iCodigoAluno;   
    me.sSituacaoAluno   = oRetorno.sSituacaoAluno.urlDecode();   
    me.iTurmaAluno      = oRetorno.iCodigoTurma;   
    me.sNomeTurma       = oRetorno.sTurma.urlDecode();   
    me.sDataMatricula   = oRetorno.dtMatricula;   
    me.sDataSaida       = oRetorno.dtSaida;   
    me.sCalendario      = oRetorno.sCalendario.urlDecode();
    me.mMinimoAprovacao = oRetorno.mMinimoAprovacao.urlDecode();
  };
  
  /**
   * Buscamos as informacoes da grade de aproveitamento
   */
  this.buscaGradeAproveitamento = function () {
    
    var oParametro        = new Object();
    oParametro.exec       = 'gradeAproveitamentoAluno';
    oParametro.iMatricula = me.iMatricula;
    
    var oAjax = new Ajax.Request(me.sRPCAluno,
                                 {method: 'post',
                                  parameters:'json='+Object.toJSON(oParametro),
                                  onComplete: me.retornoGradeAproveitamento,
                                  asynchronous:false
                                 } 
                                );
  };
  
  this.retornoGradeAproveitamento = function (oAjax) {
    
    var oRetorno = eval('('+oAjax.responseText+')');
    
    if (oRetorno.status == 2) {
      
      alert(oRetorno.message.urlDecode());
      return false;
    }
    
    me.aGradeAproveitamento = oRetorno.aGradeAproveitamento;
    me.montaGridAproveitamento();
    
  };
  
  /**
   * Montamos a grid com os aproveitamentos de cada disciplina e periodo
   */
  this.montaGridAproveitamento = function () {
    
    var aAlign  = new Array('left');
    var aHeader = new Array('Disciplina');
    var aWidth  = new Array('20%'); 
    
    var aGrupos      = new Array();
    var oGrupo       = new Object();
    oGrupo.descricao = "Per�odos";
    oGrupo.aColunas  = new Array('0');
    aGrupos.push(oGrupo);
    
    var iUltimoPeriodo = 0;
    
    me.aGradeAproveitamento.each(function(oDisciplina, iDisciplina) {
      
      oDisciplina.aAproveitamento.each(function (oPeriodo, iPeriodo) {
        
        var oGrupo       = new Object();
        oGrupo.descricao = oPeriodo.sDescricao;
        oGrupo.aColunas  = new Array(iPeriodo+1, iPeriodo+2);
        aGrupos.push(oGrupo);
        
        aHeader.push(oPeriodo.sFormaAvaliacao);
        aHeader.push('Falta');
        aAlign.push('right', 'right');
        aWidth.push('8%');
        iUltimoPeriodo  = iPeriodo+2;
      });
      
      
      /**
       * Coluna da Frequ�ncia
       */
      var oGrupoFreq   = new Object();
      oGrupoFreq.descricao = 'Frequ�ncia';
      oGrupoFreq.aColunas  = new Array(iUltimoPeriodo+1, iUltimoPeriodo+2);
      aGrupos.push(oGrupoFreq);
      
      aHeader.push('Aulas');
      aHeader.push('% Freq');
      aAlign.push('right','right');
      aWidth.push('10%');

      /**
       * Coluna do Resultado Final
       */
      var oGrupoRF   = new Object();
      oGrupoRF.descricao = 'Resultado Final';
      oGrupoRF.aColunas  = new Array(iUltimoPeriodo+3, iUltimoPeriodo+4);
      aGrupos.push(oGrupoRF);
      
      aHeader.push('Aprovado');
      aHeader.push('RF');
      aAlign.push('right', 'center');
      aWidth.push('10%');
      
      throw $break;
      
    });
    
    me.oGridAproveitamento = new DBGridMultiCabecalho('gridAproveitamento');
    me.oGridAproveitamento.setCellWidth(aWidth);
    me.oGridAproveitamento.setCellAlign(aAlign);
    me.oGridAproveitamento.setHeader(aHeader);
    
    aGrupos.each(function(oGrupo, iSeq) {
      me.oGridAproveitamento.adicionarGrupo(oGrupo.descricao, oGrupo.aColunas, '0');
    });
    
    me.oGridAproveitamento.setHeight(me.iTamanhoJanela/6);
    me.oGridAproveitamento.show($('gridAproveitamento'));
    me.oGridAproveitamento.clearAll(true);
    
    me.retornaAproveitamentos();
    
  };
  
  this.retornaAproveitamentos = function () {
    
    me.aGradeAproveitamento.each(function (oDisciplina, iDisciplina) {
      
      var aLinha = new Array();
      aLinha.push(oDisciplina.sNome);
      
      oDisciplina.aAproveitamento.each(function (oPeriodo, iPeriodo) {
        
        var sNota = "<span ";
        
        if (oPeriodo.oAproveitamento.sFormaAvaliacao == 'NOTA') {
          
          if (new Number(oPeriodo.oAproveitamento.nAproveitamento) < new Number(oPeriodo.oAproveitamento.nMinimoAprovacao)) {
            sNota += " style = 'font-weight:bold' ";  
          }
        }
        sNota += ">" + js_mascaraNota(oPeriodo.oAproveitamento.nAproveitamento, oDisciplina.sMascaraNota) + "</span>";
        aLinha.push(sNota);
        aLinha.push(oPeriodo.oAproveitamento.iFaltas);
      });
      
      aLinha.push(oDisciplina.oFrequencia.iTotalAulas);
      aLinha.push(oDisciplina.oFrequencia.nPercentualFrequencia);
      
      var sNotaFinal = "<span ";
      if (new Number(oDisciplina.oResultadoFinal.nAproveitamentoFinal) < new Number(me.mMinimoAprovacao)) {
        sNotaFinal += " style = 'font-weight:bold' ";
      }
      sNotaFinal += ">" + oDisciplina.oResultadoFinal.nAproveitamentoFinal + "</span>";
      
      aLinha.push(sNotaFinal);
      aLinha.push(oDisciplina.oResultadoFinal.sTermoResultadoFinalAbreviado);
      
      me.oGridAproveitamento.addRow(aLinha);
    });
    
    me.oGridAproveitamento.renderRows();
  };
  
  
  this.show = function ( oElementoDestino ) {
    
    me.buscaDadosMatricula();
    me.renderizarWindowAvaliacao();
    me.buscaGradeAproveitamento();
    me.montaGridAproveitamento();
  };
};
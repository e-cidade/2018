DBViewConsultaAvaliacoesAluno = function(sInstancia, iMatricula) {

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


    me.oWindowAuxAvaliacao = new windowAux("wndConsultaAlunoAvaliacaoTurma",
                                           "Grade de Aproveitamento",
                                            me.iTamanhoJanela
                                           );
    me.oWindowAuxAvaliacao.setShutDownFunction(function () {
      me.oWindowAuxAvaliacao.destroy();
    });

    me.oWindowAuxAvaliacao.allowCloseWithEsc(true);

    var sConteudo    = "<div id='disciplinas_aluno'>\n";
        sConteudo   += "  <div id='divListaGrupo'>";
        sConteudo   += "    <fieldset style='width:98%'>                                              \n";
        sConteudo   += "    <fieldset style='width:98%; margin-bottom:10px;'>                         \n";
        sConteudo   += "      <legend><b>Grade de Aproveitamento</b></legend>                      ";
        sConteudo   += "      <div id='gridAproveitamento"+me.iMatricula+"' style='width:100%;'></div>";
        sConteudo   += "    </fieldset>                                                               \n";
        sConteudo   += "  </div>                                                                      \n";
        sConteudo   += "</div>                                                                        \n";

    me.oWindowAuxAvaliacao.setShutDownFunction(function() {

      delete me.oGridAproveitamento;
      me.oWindowAuxAvaliacao.destroy();
    });

    var sHelpMsgBox  = ' Turma: <b>' + me.sNomeTurma + '</b> ';
        sHelpMsgBox += ' Situação: <b>' +me.sSituacaoAluno+ "</b>";
        sHelpMsgBox += '  Data de Matrícula: <b>' + me.sDataMatricula+ "</b>  ";
        sHelpMsgBox += '  Data de Saída:<b>' + me.sDataSaida+' </b> ';
        sHelpMsgBox += '  Calendário:<b>' + me.sCalendario+'</b></br>';
        sHelpMsgBox += '  <div style="text-indent: 15px;">Mínimo para Aprovação:<b>' + me.mMinimoAprovacao + '</b></div>';


    me.oWindowAuxAvaliacao.setContent(sConteudo);
    var oMessageBoard = new DBMessageBoard('msgBoardAvaliacao'+me.iMatricula,
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
    var aWidth  = new Array('10%');

    var aGrupos      = new Array();
    var oGrupo       = new Object();
    oGrupo.descricao = "Períodos";
    oGrupo.aColunas  = new Array('0');
    aGrupos.push(oGrupo);

    var iUltimoPeriodo = 0;

    delete me.oGridAproveitamento;
    me.aGradeAproveitamento.each(function(oDisciplina, iDisciplina) {

      oDisciplina.aAproveitamento.each(function (oPeriodo, iPeriodo) {

        var oGrupo       = new Object();
        oGrupo.descricao = oPeriodo.sDescricao.urlDecode();
        oGrupo.aColunas  = new Array(iPeriodo+1, iPeriodo+2);
        aGrupos.push(oGrupo);

        aHeader.push(oPeriodo.sFormaAvaliacao);
        aHeader.push('Falta');
        aAlign.push('center', 'right');
        aWidth.push('8%', '8%');
        iUltimoPeriodo  = iPeriodo+2;
      });


      /**
       * Coluna da Frequência
       */
      var oGrupoFreq       = new Object();
      oGrupoFreq.descricao = 'Frequência';
      oGrupoFreq.aColunas  = new Array(iUltimoPeriodo+1, iUltimoPeriodo+2);
      aGrupos.push(oGrupoFreq);

      aHeader.push('Aulas');
      aHeader.push('% Freq');
      aAlign.push('right','right');
      aWidth.push('8%', '8%');

      /**
       * Coluna do Resultado Final
       */
      var oGrupoRF       = new Object();
      oGrupoRF.descricao = 'Resultado Final';
      oGrupoRF.aColunas  = new Array(iUltimoPeriodo+3, iUltimoPeriodo+4);
      aGrupos.push(oGrupoRF);

      aHeader.push('Aproveitamento');
      aHeader.push('RF');
      aAlign.push('center', 'center');
      aWidth.push('10%', '10%');

      throw $break;

    });
    $('gridAproveitamento'+me.iMatricula).innerHTML = '';
    me.oGridAproveitamento = new DBGridMultiCabecalho('oGridAproveitamentoConsulta'+me.iMatricula);

    me.oGridAproveitamento.setCellWidth(aWidth);
    me.oGridAproveitamento.setCellAlign(aAlign);
    me.oGridAproveitamento.setHeader(aHeader);

    aGrupos.each(function(oGrupo, iSeq) {
      me.oGridAproveitamento.adicionarGrupo(oGrupo.descricao, oGrupo.aColunas, '0');
    });
    me.oGridAproveitamento.setHeight(me.iTamanhoJanela/6);
    me.oGridAproveitamento.show($('gridAproveitamento'+me.iMatricula));
    me.oGridAproveitamento.clearAll(true);

    me.retornaAproveitamentos();

  };

  this.retornaAproveitamentos = function () {

    me.aGradeAproveitamento.each(function (oDisciplina, iDisciplina) {

      var aLinha          = new Array();
      var sNomeDisciplina = oDisciplina.sNome.urlDecode();

      if ( oDisciplina.lTemProgressaoParcial ) {
        sNomeDisciplina += "<span style = 'font-weight:bold'> AP/DP </span>";
      }

      aLinha.push(sNomeDisciplina);

      var lEmRecuperacao = false;

      oDisciplina.aAproveitamento.each(function (oPeriodo, iPeriodo) {

        var sNota = "<span ";

        if (oPeriodo.oAproveitamento.sFormaAvaliacao == 'NOTA') {

          // sempre que o aluno não atingir o mínimo e não estiver amparado, deve-se colocar em negrito a avaliação
          if (!oPeriodo.oAproveitamento.lAmparado && !oPeriodo.oAproveitamento.lAtingiuMinimo ) {
            sNota += " style = 'font-weight:bold' ";
          }

          if ( oPeriodo.oAproveitamento.lAmparado ) {
            sNota += ">" + oPeriodo.oAproveitamento.nAproveitamento + "</span>";
          } else {
            sNota += ">" + js_mascaraNota(oPeriodo.oAproveitamento.nAproveitamento, oDisciplina.sMascaraNota) + "</span>";
          }

        } else {
          sNota += ">" + oPeriodo.oAproveitamento.nAproveitamento + "</span>";
        }

        aLinha.push(sNota);
        var sPeriodo = oPeriodo.oAproveitamento.iFaltas;

        if ( oPeriodo.oAproveitamento.iFaltasAbonadas != '' ) {
          sPeriodo += "<span class='bold' style = 'font-size: 9px'>   FA</span>";
        }

        if ( oPeriodo.oAproveitamento.lEmRecuperacao ) {
          lEmRecuperacao = true;
        }

        aLinha.push(sPeriodo);
      });

      aLinha.push(oDisciplina.oFrequencia.iTotalAulas);
      aLinha.push(oDisciplina.oFrequencia.nPercentualFrequencia);

      var sNotaFinal = "<span ";
      if (oDisciplina.oResultadoFinal.sResultadoAprovacao != 'A') {
        sNotaFinal += " style = 'font-weight:bold' ";
      }
      sNotaFinal += ">" + oDisciplina.oResultadoFinal.nAproveitamentoFinal + "</span>";

      aLinha.push(sNotaFinal);

      var sTermoResultadoFinal = oDisciplina.oResultadoFinal.sTermoResultadoFinalAbreviado;

      if ( oDisciplina.oResultadoFinal.lAprovadoProgressaoParcial ) {
        sTermoResultadoFinal = "AP/DP";
      }

      if ( lEmRecuperacao ) {
        sTermoResultadoFinal = "Rec";
      }

      aLinha.push(sTermoResultadoFinal);

      me.oGridAproveitamento.addRow(aLinha);
    });

    me.oGridAproveitamento.renderRows();

    me.aGradeAproveitamento.each(function (oDisciplina, iDisciplina) {

      oDisciplina.aAproveitamento.each(function (oPeriodo, iPeriodo) {

        var iColuna   = (iPeriodo * 2) + 2;
        var sMensagem = '';

        if ( oPeriodo.oAproveitamento.iFaltasAbonadas != '' ) {

          var oCelulaStatus         = $(me.oGridAproveitamento.aRows[iDisciplina].aCells[iColuna].sId);
          var oDBHintFaltasAbonadas = eval("oDBHint_"+iDisciplina+"_"+iColuna+" = new DBHint('oDBHint_"+iDisciplina+"_"+iColuna+"')");
          var sMensagem             = "Número de Faltas Abonadas: " + oPeriodo.oAproveitamento.iFaltasAbonadas;

          oDBHintFaltasAbonadas.setText(sMensagem.urlDecode());
          oDBHintFaltasAbonadas.setWidth(195);
          oDBHintFaltasAbonadas.setUseMouse(true);
          oDBHintFaltasAbonadas.setShowEvents(["onmouseover"]);
          oDBHintFaltasAbonadas.setHideEvents(["onmouseout"]);
          oDBHintFaltasAbonadas.setPosition('B', 'L');
          oDBHintFaltasAbonadas.make(oCelulaStatus);
        }
      });
    });

  };


  this.show = function ( oElementoDestino ) {

    me.buscaDadosMatricula();
    me.renderizarWindowAvaliacao();
    me.buscaGradeAproveitamento();
  };
};
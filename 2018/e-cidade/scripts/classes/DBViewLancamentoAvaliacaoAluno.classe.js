require_once("scripts/classes/DBViewLancamentoAvaliacao/PeriodoTurma.classe.js");
require_once("scripts/classes/DBViewConsultaAvaliacoesAluno.classe.js");
require_once("scripts/classes/DBViewLancamentoAvaliacao/LancamentoObservacao.classe.js");
DBViewLancamentoAvaliacaoAluno = function(sInstancia, iMatriculaAluno, aMatriculasTurma) {

  this.oDataGridDisciplina  = null;
  this.oWindowAuxAvaliacao  = null;
  this.sInstancia           = sInstancia;
  this.iMatriculaAluno      = iMatriculaAluno;
  this.aMatriculasTurma     = aMatriculasTurma;
  this.iCodigoAluno         = null;
  this.iTurmaAluno          = null;
  this.iEtapaAluno          = null;
  this.sNomeTurma           = null;
  this.sSituacaoAluno       = null;
  this.sNomeAluno           = null;
  this.iTabIndex            = null;
  this.sMascaraFormatacao   = null;
  this.lAvaliadoParecer     = false;
  this.sDataMatricula       = '';
  this.sCalendario          = '';
  this.sDataSaida           = '';
  this.sFormaAvaliacaoTurma = '';
  this.aPeriodosAluno       = [];
  this.aDisciplinas         = [];
  this.lDadosAlterados      = false;
  this.lDadosSalvo          = false;
  this.aAvaliacao           = new Array();
  this.iTamanhoJanela       = document.body.getWidth() - 10;
  this.sRPC                 = "edu4_lancamentoavaliacao.RPC.php";
  this.aTermosEnsino        = new Array();
  var oInstancia            = this;

  this.oCmbSelectTabIndex  = new DBComboBox ('cmbTabIndex', sInstancia+'TabIndex', new Array(), 200);

  /**
   * Renderiza a Window
   */
  this.renderizarWindowAvaliacao = function () {

    oInstancia.oWindowAuxAvaliacao = new windowAux("wndAlunoAvaliacao",
                                                   "Lançamento Avaliação",
                                                   oInstancia.iTamanhoJanela
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
        sConteudo   += "    <fieldset style='width:98%'>                                              \n";
        sConteudo   += "      <legend><b>Opções</b></legend>                                          \n";
        sConteudo   += "      <div id='lista_opcoes'>                                                \n";
        sConteudo   += "        <table style='width:99%' cellspacing='0' cellpadding='0'>             \n";
        sConteudo   += "          <tr>                                                                \n";
        sConteudo   += "            <td style='width:10%' nowrap>                                     \n ";
        sConteudo   += "               <label><b>Lançar Disciplinas por Período:</b></label></td>     \n";
        sConteudo   += "            <td style='width:50%;1px solid black'>";
        sConteudo   +=                oInstancia.oCmbSelectTabIndex.toInnerHtml()+"</td>";
        sConteudo   += "            <td id='dadosAvaliacao' style='border-left:2px groove white;width:40%'><br><br>";
        sConteudo   += "            </td>                                                             \n";
        sConteudo   += "          </tr>                                                               \n";
        sConteudo   += "        </table>                                                              \n";
        sConteudo   += "      </div>                                                                  \n";
        sConteudo   += "    </fieldset>                                                               \n";
        sConteudo   += "    <fieldset style='width:98%; margin-bottom:10px;'>                         \n";
        sConteudo   += "      <legend><b>Disciplinas</b></legend>                                     \n";
        sConteudo   += "      <div id='grid_disciplinas' style='width:100%;'></div>                    \n";
        sConteudo   += "    </fieldset>                                                               \n";
        sConteudo   += "    <center>                                                                  \n";
        sConteudo   += "      <input type='button' id='anterior' value='Anterior' name='anterior'     \n";
        sConteudo   += "             onclick='"+oInstancia.sInstancia+".recriaView("+iMatriculaAnterior+");' "+sDisbledAnterior+" /> \n";
        sConteudo   += "      <input type='button' id='salvar' value='Salvar' name='salvar'           \n";
        sConteudo   += "             onclick='"+oInstancia.sInstancia+".salvar();'/>                  \n";
        sConteudo   += "      <input type='button' id='proximo' value='Próximo' name='proximo'        \n";
        sConteudo   += "             onclick='"+oInstancia.sInstancia+".recriaView("+iProximaMatricula+");' "+sDisbledProximo+" /> \n";
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
                                       ' Situação: <b>' +oInstancia.sSituacaoAluno+ "</b>"+
                                       '  Data de Matrícula: <b>' + oInstancia.sDataMatricula+ "</b>  "+
                                       '  Data de Saída:<b>' + oInstancia.sDataSaida+' </b> '+
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

    var aAling  = new Array('right');
    var aHeader = new Array('Disciplina');
    var aWidth  = new Array('20%');

    var aGrupos               = new Array();
    var oGrupo       = new Object();
    oGrupo.descricao = "Períodos";
    oGrupo.aColunas  = new Array('0');
    aGrupos.push(oGrupo);
    oInstancia.aPeriodosAluno.each( function(oPeriodo, id) {

      aAling.push('center');
      aHeader.push(oPeriodo.sFormaAvaliacao.toLowerCase().ucFirst());
      if (oPeriodo.sTipoAvaliacao == 'A') {
        aHeader.push('Falta');
      }
      var oGrupo       = new Object();
      oGrupo.descricao = oPeriodo.sDescricaoPeriodoAbreviado.urlDecode();
      oGrupo.aColunas  = new Array(new String(id+1));
      if (oPeriodo.sTipoAvaliacao == 'A') {
        oGrupo.aColunas.push(new String(id+2));
      }
      aGrupos.push(oGrupo);
    });

    oGrupo       = new Object();
    oGrupo.descricao = "Resultado Final";
    oGrupo.aColunas  = new Array('0','1');
    aGrupos.push(oGrupo);
    aHeader.push('TF');
    aHeader.push('RF');
    aAling.push('left');
    aAling.push('left');

    /**
     * Calculo do tamanho das colunas dos periodos
     */
    var iNumeroColunasVariaveis = aHeader.length - 1;
    var iWidthColunaPeriodo     = Math.floor(90 / iNumeroColunasVariaveis);

    for (var i = 0; i < iNumeroColunasVariaveis; i++) {
      aWidth.push(iWidthColunaPeriodo+'%');
    }

    oInstancia.oDataGridDisciplina               = new DBGridMultiCabecalho('gridAlunoDisciplina');
    oInstancia.oDataGridDisciplina.sNameInstance = sInstancia+'.oDataGridDisciplina';
    oInstancia.oDataGridDisciplina.setCellWidth(aWidth);
    oInstancia.oDataGridDisciplina.setCellAlign(aAling);
    oInstancia.oDataGridDisciplina.setHeader(aHeader);
    aGrupos.each(function(oGrupo, iSeq) {
      oInstancia.oDataGridDisciplina.adicionarGrupo(oGrupo.descricao, oGrupo.aColunas, '0');
    });
    oInstancia.oDataGridDisciplina.setHeight(oInstancia.iTamanhoJanela/6);
    oInstancia.oDataGridDisciplina.show($('grid_disciplinas'));
    oInstancia.oDataGridDisciplina.clearAll(true);
  };

  /**
   * Buscamos os dados dos periodos de avaliacao
   */
  this.buscaPeriodosAvaliacao = function() {

    var oObject        = new Object();
    oObject.exec       = 'periodosAvaliacao';
    oObject.iMatricula = oInstancia.iMatriculaAluno;

    var oAjax = new Ajax.Request(oInstancia.sRPC,
                                 {method: 'post',
                                  parameters:'json='+Object.toJSON(oObject),
                                  onComplete: oInstancia.retornoPeriodosAvaliacao,
                                  asynchronous:false
                                 }
                                );
  }

  /**
   * Preenche os dados dos periodos.
   * e os dados do aluno/turma
   */
  this.retornoPeriodosAvaliacao = function(oAjax) {

    var oRetorno = eval("("+oAjax.responseText+")");

    if (oRetorno.status == 2) {
      alert(oRetorno.message.urlDecode());
    }

    oRetorno.dados.each( function(oPeriodo, id) {

      oInstancia.aPeriodosAluno.push(oPeriodo);
      if (oPeriodo.sTipoAvaliacao == 'A') {

        var sPeriodo = oPeriodo.sDescricaoPeriodo.urlDecode()+"   (alt + "+(id+1)+")";
        oInstancia.oCmbSelectTabIndex.addItem(id, sPeriodo);
      }
    });

    oInstancia.iCodigoAluno         = oRetorno.iCodigoAluno;
    oInstancia.iTurmaAluno          = oRetorno.iCodigoTurma;
    oInstancia.iEtapaAluno          = oRetorno.iEtapa;
    oInstancia.sNomeAluno           = oRetorno.sNomeAluno.urlDecode();
    oInstancia.sTurmaAluno          = oRetorno.sTurma.urlDecode();
    oInstancia.sSituacaoAluno       = oRetorno.sSituacaoAluno;
    oInstancia.iTabIndex            = oRetorno.iTabIndex;
    oInstancia.sDataMatricula       = oRetorno.dtMatricula;
    oInstancia.sDataSaida           = oRetorno.dtSaida;
    oInstancia.iLimiteReprovacao    = oRetorno.iLimiteReprovacao;
    oInstancia.lAvaliadoParecer     = oRetorno.lAvaliadoParecer;
    oInstancia.sCalendario          = oRetorno.sCalendario.urlDecode();
    oInstancia.sFormaAvaliacaoTurma = oRetorno.sFormaAvaliacaoTurma.urlDecode();
    oInstancia.sMascaraFormatacao   = oRetorno.sMascaraFormatacao;
    oRetorno.aTermos.each(function(oLinha, iContador) {

      if (oLinha.sReferencia.urlDecode() != 'P') {
        oInstancia.aTermosEnsino.push(oLinha);
      }
    });
  }

  /**
   * Busca as disciplinas da turma para a matricula do aluno
   */
  this.buscaDisciplinas = function () {

    js_divCarregando("Carregando disciplinas do aluno, aguarde...", "msgBox");

    var oObject        = new Object();
    oObject.exec       = 'buscaDisciplinasTurma';
    oObject.iMatricula = oInstancia.iMatriculaAluno;
    oObject.iTurma     = oInstancia.iTurmaAluno;

    var oJson          = new Object();
    oJson.method       = 'post';
    oJson.parameters   = 'json='+Object.toJSON(oObject);
    oJson.onComplete   = oInstancia.retornoDisciplinas;
    oJson.asynchronous = false;
    new Ajax.Request(oInstancia.sRPC, oJson);
  }

  this.retornoDisciplinas = function(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");

    if (oRetorno.status == 2) {
      alert(oRetorno.message.urlDecode());
    }

    oRetorno.dados.each( function(oDisciplina, id) {
      oInstancia.aDisciplinas.push(oDisciplina);
    });
  }

  /**
   * Popula a grid com os dados da Disciplina e os input para lancamento das notas e faltas
   */
  this.popularGrid = function () {

    oInstancia.buscaDisciplinas();

    oInstancia.oDataGridDisciplina.clearAll(true);
    oInstancia.aDisciplinas.each( function(oDisciplina, id) {

      var aLinha  = new Array();
      aLinha[0]   = "<div  style='text-align:left;clear:both;position:relative; line-height:100%; ";
      aLinha[0]  += "             width:80%;float:left;overflow:hidden'>"+oDisciplina.sDescricao.urlDecode()+"</div>";
      if (!oDisciplina.lEncerrada) {

        var sFuncaoParecer   = oInstancia.sInstancia+".abrirParecerComplementar("+oDisciplina.iCodigoRegencia+")";

        if (!oInstancia.lAvaliadoParecer && oInstancia.sFormaAvaliacaoTurma !== 'PARECER') {
          aLinha[0]  += "<span title='Lançar Parecer' onclick='"+sFuncaoParecer+"'style='cursor:pointer' value='P'><b>P</b></span>&nbsp;";
        }
        aLinha[0]  += "<span title='Lançar Observações' ";
        aLinha[0]  += "onclick='"+oInstancia.sInstancia+".lancarObservacao("+oDisciplina.iCodigoRegencia+");'";
        aLinha[0]  += " style='cursor:pointer'><b>O</b></span>&nbsp;";
      }
      var sResultadoFinal = '';
      var sCorReadOnly        = 'rgb(222, 184, 135)';
      oInstancia.aPeriodosAluno.each( function(oPeriodo, id) {

        /**
         * Criamos um array para maperar a estrutura da grade de avaliacao
         * e dele que buscaremos os dados para depois persistir no BD
         */
        var iFaltas                    = '';
        var nNota                      = '';
        var iSequencia                 = '';
        var lMinima                    = '';
        var iPeriodoDependente         = '';
        var lEncerrado                 = false;
        var lNotaBloqueada             = false;
        var lFaltaBloqueada            = false;
        var lAvaliacaoExterna          = false;
        var lAprovadoProgressaoParcial = false;
        var sCorBackGroundFalta        = 'transparent';
        var sCorBackGroundNota         = 'transparent';
        var lRecuperacao               = false;

        oDisciplina.aAproveitamentos.each(function(oAproveitamento, iAproveitamento) {

          if (oAproveitamento.iPeriodo == oPeriodo.iCodigoAvaliacao) {

            lMinima                     = oAproveitamento.lMinima;
            iFaltas                     = oAproveitamento.iFalta;
            nNota                       = oAproveitamento.nNota.urlDecode();
            iSequencia                  = oAproveitamento.iOrdem;
            lEncerrado                  = oAproveitamento.lEncerrado;
            iPeriodoDependente          = oPeriodo.iPeriodoDependenteAprovacao;
            lNotaBloqueada              = oAproveitamento.lNotaBloqueada;
            lFaltaBloqueada             = oAproveitamento.lFaltaBloqueada;
            lAvaliacaoExterna           = oAproveitamento.lAvaliacaoExterna;
            sResultadoFinal             = oAproveitamento.sResultadoFinal;
            lRecuperacao                = oAproveitamento.lRecuperacao;
            iTotalDisciplinasReprovadas = oAproveitamento.iTotalDisciplinasReprovadas;
            iLimiteReprovacao           = oPeriodo.iLimiteReprovacao;
            lAprovadoProgressaoParcial  = oAproveitamento.lAprovadoProgressaoParcial;

            sCorBackGroundFalta = 'transparent';
            sCorBackGroundNota  = 'transparent';
            if (lAvaliacaoExterna) {

              lFaltaBloqueada = true;
              lNotaBloqueada  = true;
            }
          }
        });
        oAvaliacaoDisciplina                             = new Object();
        oAvaliacaoDisciplina.iCodigoRegencia             = oDisciplina.iCodigoRegencia;
        oAvaliacaoDisciplina.sFrequenciaGlobal           = oDisciplina.sFrequenciaGlobal;
        oAvaliacaoDisciplina.iPeriodo                    = oPeriodo.iCodigoAvaliacao;
        oAvaliacaoDisciplina.nNota                       = nNota;
        oAvaliacaoDisciplina.iOrdem                      = iSequencia;
        oAvaliacaoDisciplina.lEncerrado                  = lEncerrado;
        oAvaliacaoDisciplina.iFalta                      = iFaltas;
        oAvaliacaoDisciplina.lAproveitamentoMinimo       = lMinima;
        oAvaliacaoDisciplina.lAvaliacaoExterna           = lAvaliacaoExterna;
        oAvaliacaoDisciplina.lRecuperacao                = lRecuperacao;
        oAvaliacaoDisciplina.lAvaliacaoExterna           = lAvaliacaoExterna;
        oAvaliacaoDisciplina.iTotalDisciplinasReprovadas = iTotalDisciplinasReprovadas;
        oAvaliacaoDisciplina.iLimiteReprovacao           = iLimiteReprovacao;
        oAvaliacaoDisciplina.iPeriodoDependenteAprovacao = iPeriodoDependente;
        oAvaliacaoDisciplina.sResultadoFinal             = sResultadoFinal;
        oAvaliacaoDisciplina.lAprovadoProgressaoParcial  = lAprovadoProgressaoParcial;
        oAvaliacaoDisciplina.iTotalFaltas                = oDisciplina.iTotalFaltas;

        oInstancia.aAvaliacao.push(oAvaliacaoDisciplina);


        /**
         * String das funcoes disparadas ao preencher uma nota ou falta para uma disciplina e um periodo
         */
        var sFuncaoPreencheDisciplina = oInstancia.sInstancia + ".preencheNotaDisciplina(";
        sFuncaoPreencheDisciplina    += oPeriodo.iCodigoAvaliacao + ", " + oDisciplina.iCodigoRegencia;
        sFuncaoPreencheDisciplina    += ", this, event);";

        var sFuncaoPreencheFalta      = oInstancia.sInstancia + ".preencheFaltaDisciplina(";
        sFuncaoPreencheFalta         += oPeriodo.iCodigoAvaliacao + ", " + oDisciplina.iCodigoRegencia;
        sFuncaoPreencheFalta         += ", this, event);";

        var sFuncaoOnFocus = oInstancia.sInstancia + ".sinalizarLinhaGrid(this, true);";
        var sFuncaoOnBlur  = oInstancia.sInstancia + ".sinalizarLinhaGrid(this, false);";
        sFuncaoOnFocus     += oInstancia.sInstancia+".buscarDadosAvaliacao("+oPeriodo.iCodigoAvaliacao+");";
        /**
         * Variavel padrao para valor da NOTA
         */
        var mValorTipoAvaliacao = '';

        lReadOnly      = false;
        var lReadOnlyFalta = false;
        sCorBackGroundNota = 'transparent';

        /**
         * Bloqueamos os campos das notas quando tipo de avaliacao for 'RESULTADO',
         * e forma de obtencao nao for 'ATRIBUIDA';
         * ou se a avaliação esta encerrada
         */
        if (oInstancia.lAvaliadoParecer) {
          oPeriodo.sFormaObtencao = "AT";
        }
        if ((oPeriodo.sTipoAvaliacao == 'R' && oPeriodo.sFormaObtencao != "AT") || lEncerrado || lNotaBloqueada) {

          lReadOnly                 = true;
          sFuncaoPreencheDisciplina = '';
          if (lFaltaBloqueada || lEncerrado) {

            lReadOnlyFalta       = true;
            sFuncaoPreencheFalta = '';
          }
        }

        if (lFaltaBloqueada) {

          lReadOnlyFalta       = true;
          sFuncaoPreencheFalta = '';
        }

        /**
         * Verificamos se a situacao da matricula encontra-se diferente de 'MATRICULADO', bloqueando o preenchimento
         * caso retorne TRUE
         */
        if (oInstancia.sSituacaoAluno != 'MATRICULADO') {

          lReadOnly      = true;
          lReadOnlyFalta = true;
        }

        var sTitle = '';
        if (lAvaliacaoExterna) {
          sTitle = 'Nota Externa';
        }

        if (lEncerrado) {
          sTitle = 'Aluno com avaliação encerrada.';
        }

        /**
         * Bloqueamos o lancamento de notas quando for uma disciplina globalizada 'F'
         */
        if (oAvaliacaoDisciplina.sFrequenciaGlobal == 'F') {
          lReadOnly = true;
        }

        /**
         * Bloqueamos o lancamento de faltas quando tipo de frequencia for 'A'
         */
        if (oAvaliacaoDisciplina.sFrequenciaGlobal == 'A') {
          lReadOnlyFalta = true;
        }

        /**
         * caso o periodo depende do resultado de outro periodo,
         * devemos bloquear a digitacao do periodo caso o periodo dependente tenha atingidido a minima para
         * a aprovação.
         */
        var lRecuperacaoBloqueada = false;
        var sClassRecuperacao     = '';
        if (oPeriodo.iPeriodoDependenteAprovacao != "") {


          sClassRecuperacao     = 'recuperacaoperiodo'+oPeriodo.iPeriodoDependenteAprovacao;
          var oAvaliacaoDepende = oInstancia.getValorAproveitamento(oPeriodo.iPeriodoDependenteAprovacao,
                                                                    oDisciplina.iCodigoRegencia
                                                                   );
          var lBloquearAvaliacao = oAvaliacaoDepende.lAproveitamentoMinimo || oAvaliacaoDepende.nNota.trim() == '';
          var sMensagemNota      = 'dispensado';


          if (oAvaliacaoDisciplina.iLimiteReprovacao > 0 &&
            oAvaliacaoDisciplina.iTotalDisciplinasReprovadas > oAvaliacaoDisciplina.iLimiteReprovacao) {

            lBloquearAvaliacao    = true;

            lRecuperacaoBloqueada = true;
            sMensagemNota         = 'Não Habilitado';
          }

          if (lBloquearAvaliacao) {

            lReadOnly             = true;
            lRecuperacaoBloqueada = true;
            nNota                 = sMensagemNota;

          }
        }

        /**
         * Caso algum campo esteja desabilitado, será também preenchido de cor laranja padrão do sistema.
         */
        if (lReadOnly) {
          sCorBackGroundNota = sCorReadOnly;
        }
        if (lReadOnlyFalta) {
          sCorBackGroundFalta = sCorReadOnly;
        }

        var sIdCampoNota    = oDisciplina.iCodigoRegencia+"_"+oPeriodo.iCodigoAvaliacao+"_nota";
        var sFormaAvaliacao = oPeriodo.sFormaAvaliacao;
        if (oInstancia.lAvaliadoParecer) {
          sFormaAvaliacao = 'PARECER';
        }
        switch (sFormaAvaliacao) {

	        case 'NIVEL':

	          mValorTipoAvaliacao  = new DBComboBox (sIdCampoNota, sIdCampoNota, new Array(), "100%");
	          mValorTipoAvaliacao.addEvent("onChange", sFuncaoPreencheDisciplina);
	          mValorTipoAvaliacao.addEvent("onFocus", sFuncaoOnFocus);
	          mValorTipoAvaliacao.addEvent("onBlur", sFuncaoOnBlur);
	          mValorTipoAvaliacao.addStyle("background-color", sCorBackGroundNota);
	          mValorTipoAvaliacao.addItem('', '');
            mValorTipoAvaliacao.addAttribute('recuperacaobloqueada', lRecuperacaoBloqueada);
	          if (lReadOnly) {
	            mValorTipoAvaliacao.setDisable();
	          }
	          oPeriodo.aConceitos.each(function(oConceito, iSeq) {

	            var oNivel   = new Object();
	            oNivel.nome  = 'ordem';
	            oNivel.valor = oConceito.iOrdem;
	            mValorTipoAvaliacao.addItem(oConceito.sDescricaoConceito, oConceito.sDescricaoConceito,'', new Array(oNivel));
	          });
	          mValorTipoAvaliacao.setValue(nNota);
            break;

	        case 'NOTA':

	          sReadOnly = lReadOnly?' readonly ':'';
	          if (lNotaBloqueada) {
	            sReadOnly = ' disabled ';
	          }
	          mValorTipoAvaliacao  = "<input id="+sIdCampoNota+" class='"+id+"_nota' "+sReadOnly+" onChange='" + sFuncaoPreencheDisciplina +"'";
	          mValorTipoAvaliacao += "onFocus='"+sFuncaoOnFocus+"' ";
	          mValorTipoAvaliacao += "onBlur='"+sFuncaoOnBlur+"' ";
	          mValorTipoAvaliacao += "title='"+sTitle+"'";
            mValorTipoAvaliacao += "recuperacaobloqueada='"+lRecuperacaoBloqueada+"'";
	          mValorTipoAvaliacao += " value='"+nNota+"' type='text' style='background-color:"+sCorBackGroundNota+";width:100%;height:100%; text-align:right' />";
	          break;

	        case 'PARECER':


	          var sFuncaoParecer   = oInstancia.sInstancia+".abrirParecer("+oPeriodo.iCodigoAvaliacao;
	          sFuncaoParecer      +=                                     ","+oDisciplina.iCodigoRegencia+","+oPeriodo.iOrdem+" )";
	          sReadOnly            = '';
	          if (lReadOnly) {

	            sReadOnly      = ' readonly ';
	            sFuncaoParecer = '';
	          }

	          mValorTipoAvaliacao  = "<input id="+sIdCampoNota+" class='"+id+"_nota' "+sReadOnly+" value='"+nNota+"'";
	          mValorTipoAvaliacao += "onFocus='"+sFuncaoParecer+";"+sFuncaoOnFocus+"'";
            mValorTipoAvaliacao += "onBlur='"+sFuncaoOnBlur+"' "+sReadOnly;
            mValorTipoAvaliacao += "recuperacaobloqueada='"+lRecuperacaoBloqueada+"'";
	          var sTamanhoCampo    = "100%";
	          if (oPeriodo.sTipoAvaliacao == 'R') {
	            sTamanhoCampo    = "50%";
	          }
	          mValorTipoAvaliacao += "type='text' style='background-color:"+sCorBackGroundNota+";float:left;height:100%;width:"+sTamanhoCampo+";' />";
	          if (oPeriodo.sTipoAvaliacao == 'R') {

              var sFuncaoPreencheResultado      = oInstancia.sInstancia + ".alterarAproveitamentoMinimo(";
              sFuncaoPreencheResultado         += oPeriodo.iCodigoAvaliacao + ", " + oDisciplina.iCodigoRegencia;
              sFuncaoPreencheResultado         += ", this, event);";
	            oComboResultado = new DBComboBox (sIdCampoNota+"resultado", sIdCampoNota+"resultado", new Array(), "50%");

	            oInstancia.aTermosEnsino.each(function(oTermo, iSeq) {
	              oComboResultado.addItem(oTermo.sReferencia.urlDecode(), oTermo.sDescricao.urlDecode());
	            });
              var oPeriodoDependente = oInstancia.getPeriodoDependenteDoPeriodo(oPeriodo.iCodigoAvaliacao);
	            if (oPeriodoDependente != "") {
                oComboResultado.addItem('rec', 'EM RECUPERAÇÃO');
              }
	            oComboResultado.setValue(lMinima?'A':'R');
              if (lRecuperacao) {
                oComboResultado.setValue('rec');
              }
	            if (lReadOnly) {
                 oComboResultado.setDisable();
              }
	            oComboResultado.addEvent("onChange", sFuncaoPreencheResultado);
              oComboResultado.addAttribute('recuperacaobloqueada', lRecuperacaoBloqueada);
	            mValorTipoAvaliacao += oComboResultado.toInnerHtml(sFuncaoPreencheResultado);
            }
	          break;
	        default:
	          break;
        }

        aLinha.push(mValorTipoAvaliacao);

        if (oPeriodo.sTipoAvaliacao == 'A') {
          sReadOnlyFalta = '';
          if (lFaltaBloqueada || lReadOnlyFalta) {
            sReadOnlyFalta = ' readonly ';
          }
          var sCampoFalta  = "<input class='"+id+"_falta' value='"+iFaltas+"' onChange='" + sFuncaoPreencheFalta + "'";
              sCampoFalta += "onkeyPress='return js_mask(event, \"0-9\")'";
              sCampoFalta += "onFocus ='"+sFuncaoOnFocus+"' ";
              sCampoFalta += "onBlur='"+sFuncaoOnBlur+"' ";
              sCampoFalta += sReadOnlyFalta;
              sCampoFalta += "title='"+sTitle+"'";
              sCampoFalta += "type='text' style='background-color:"+sCorBackGroundFalta+";width:100%;height:100%; text-align:right'/>";
          aLinha.push(sCampoFalta);

        }

      });

      var sCampoTotalFaltas  = "<input id='' class='' readonly ";
      sCampoTotalFaltas += "title='Total de Faltas'";
      sCampoTotalFaltas += " value='"+oAvaliacaoDisciplina.iTotalFaltas+"' type='text' ";
      sCampoTotalFaltas += "style='background-color:"+sCorReadOnly+";width:100%;height:100%; text-align:right' />";

      var sTermoFinal = oInstancia.getTermoResultadoFinal(sResultadoFinal,
                                                          oAvaliacaoDisciplina.lAprovadoProgressaoParcial
                                                         );
      var sCampoResultadoFinal  = "<input id='' class='' readonly ";
      sCampoResultadoFinal += "title='Resultado Final'";
      sCampoResultadoFinal += " value='"+sTermoFinal+"' type='text'";
      sCampoResultadoFinal += "style='background-color:"+sCorReadOnly+";width:100%;height:100%; text-align:right' />";

      iTotalFaltas   = '';
      sReadOnly      = '';
      sCorBackGroundNota = 'transparent';

      aLinha.push(sCampoTotalFaltas);
      aLinha.push(sCampoResultadoFinal);

      oInstancia.oDataGridDisciplina.addRow(aLinha);
      oInstancia.oDataGridDisciplina.aRows[id].sEvents += "onmouseover='"+oInstancia.sInstancia+".sinalizarLinhaGrid(this, true);'";
      oInstancia.oDataGridDisciplina.aRows[id].sEvents += "onmouseout='"+oInstancia.sInstancia+".sinalizarLinhaGrid(this, false);'";
    });

    oInstancia.oDataGridDisciplina.renderRows();
    setTimeout(oInstancia.sInstancia+".reordenaTabIndex($F('cmbTabIndex'));", 200);
    if (!oInstancia.lAvaliadoParecer) {
      oInstancia.setMascaraNota();
    }
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

  /**
   * Percorre a Avaliacao e lança a nota para a disciplina e periodo de avaliacao selecionado
   */
  this.preencheNotaDisciplina = function (iCodigoAvaliacao, iCodigoRegencia, oCampo, event) {

    var nNota = '';
    if (oCampo.value != "") {

      nNota = oCampo.value;
      var oPeriodo = oInstancia.getPeriodo(iCodigoAvaliacao);
      if (oPeriodo.sFormaAvaliacao == 'NOTA' && new String(nNota).indexOf(",") >= 0) {
        nNota = js_strToFloat(nNota).valueOf();
      }
    }

    var lNotaValida = oInstancia.validarValorDaNota(iCodigoAvaliacao, nNota, event);
    if (lNotaValida) {

      oAvaliacaoDisciplina        = oInstancia.getValorAproveitamento(iCodigoAvaliacao, iCodigoRegencia);
      var iOrdem      = '';
      if (oCampo.type == 'select-one') {
          iOrdem = oCampo.options[oCampo.selectedIndex].getAttribute('ordem');
      }
      oAvaliacaoDisciplina.nNota  = nNota;
      oAvaliacaoDisciplina.iOrdem = iOrdem;
      oInstancia.lDadosAlterados  = true;
      oInstancia.calcularResultados(oAvaliacaoDisciplina);
    } else {

      oCampo.value = '';
      event.stopPropagation();
      event.preventDefault();
      setTimeout(function(){
          oCampo.focus();
      }, 10);
    }
  }

  this.validarValorDaNota = function (iCodigoAvaliacao, nNota, event) {

    var lNotaValida = true;
    var oPeriodo    = oInstancia.getPeriodo(iCodigoAvaliacao);
    if (oPeriodo.sFormaAvaliacao == 'NOTA' && nNota != "") {

      if (new Number(nNota) < new Number(oPeriodo.iMenorValor)
          || new Number(nNota) > new Number(oPeriodo.iMaiorValor)) {

        lNotaValida = false;
        alert('Nota deve ser entre '+ oPeriodo.iMenorValor +' e '+ oPeriodo.iMaiorValor +'!');
		    /**
		     * Validamos a variacao da nota
		     */
      }
	    if (lNotaValida) {

	      lNotaValida = oInstancia.validarVariacaoNota(nNota,  oPeriodo.nVariacao);
		    if (!lNotaValida) {
		      alert('Intervalo de nota deve ser de '+oPeriodo.nVariacao);
		    }
	    }
    }

    return lNotaValida;
  }

  /**
   * Percorre a Avaliacao e lança a nota para a disciplina e periodo de avaliacao selecionado
   */
  this.preencheFaltaDisciplina = function (iCodigoAvaliacao, iCodigoRegencia, oCampo) {

    oAvaliacaoDisciplina        = oInstancia.getValorAproveitamento(iCodigoAvaliacao, iCodigoRegencia);
    oAvaliacaoDisciplina.iFalta = oCampo.value;
    oInstancia.lDadosAlterados  = true;
    var oParametros        = new Object();
    oParametros.exec       = 'setFalta';
    oParametros.iMatricula = oInstancia.iMatriculaAluno;
    oParametros.iRegencia  = iCodigoRegencia;
    oParametros.iPeriodo   = iCodigoAvaliacao;
    oParametros.iFalta     = oCampo.value;

    var oJson          = new Object();
    oJson.method       = 'post';
    oJson.parameters   = 'json='+Object.toJSON(oParametros);
    oJson.onComplete   = function () {

    };
    oJson.asynchronous = false;

    var oAjax          = new Ajax.Request(oInstancia.sRPC, oJson);
  }

  /**
   * Percorre a Avaliacao e lança a nota para a disciplina e periodo de avaliacao selecionado
   */
  this.alterarAproveitamentoMinimo = function (iCodigoAvaliacao, iCodigoRegencia, oCampo) {

    oAvaliacaoDisciplina              = oInstancia.getValorAproveitamento(iCodigoAvaliacao, iCodigoRegencia);
    oAvaliacaoDisciplina.lMinima      = true;

    /*
     * Aluno está em recuperacao no resultado
     *
     */
    oAvaliacaoDisciplina.lRecuperacao = oCampo.value == 'rec';

    if (oCampo.value != '' && (oCampo.value == 'R' || oCampo.value == 'rec')) {
      oAvaliacaoDisciplina.lMinima = false;
    }
    oInstancia.lDadosAlterados  = true;

    /**
     *salvamos do resultado definido para o parecer
     */
    var oParametro                   = new Object();
    oParametro.exec                  = 'salvarResultadoParecer';
    oParametro.iRegencia             = iCodigoRegencia;
    oParametro.iPeriodo              = iCodigoAvaliacao;
    oParametro.lAproveitamentoMinimo = oAvaliacaoDisciplina.lMinima;
    oParametro.lRecuperacao          = oAvaliacaoDisciplina.lRecuperacao;

    var oJson        = new Object();
    oJson.method     = 'post';
    oJson.parameters = 'json='+Object.toJSON(oParametro);
    oJson.onComplete = function () {
      return true;
    };

    oJson.asynchronous         = false;
    var oAjax                  = new Ajax.Request(oInstancia.sRPC, oJson);
    oInstancia.lDadosAlterados = true;
  }

  this.setMascaraNota = function() {

    oInstancia.aPeriodosAluno.each(function (oPeriodo, iIndice){

      if (oPeriodo.sFormaAvaliacao == 'NOTA') {

        $$("."+iIndice+'_nota').each(function (oNota, iInd){

          if (oNota.getAttribute('recuperacaobloqueada') != "true") {
            js_observeMascaraNota(oNota, oInstancia.sMascaraFormatacao);
          }
        });
      }
    });

  }

  this.show = function ( oElementoDestino ) {

    oInstancia.buscaPeriodosAvaliacao();
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
    var oObject        = new Object();
    oObject.exec       = 'salvaAvaliacaoAluno';
    oObject.iMatricula = oInstancia.iMatriculaAluno;
    oObject.iTurma     = oInstancia.iTurmaAluno;

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

    oInstancia.recriaView(oInstancia.iMatriculaAluno);
  }

  /**
   * Retorna a matricula anterior a matricula atual
   * @return mixed
   */
  this.matriculaAnterior = function() {

    var iMatriculaAnterior = null;

    oInstancia.aMatriculasTurma.each(function (iMatricula, iIndice){

      if (iMatricula == oInstancia.iMatriculaAluno) {

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

    oInstancia.aMatriculasTurma.each(function (iMatricula, iIndice){

      if (iMatricula == oInstancia.iMatriculaAluno) {

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
    delete oInstancia;
    oNovaInstancia = new DBViewLancamentoAvaliacaoAluno('oNovaInstancia', iMatricula, aMatriculas);
    oNovaInstancia.show();

  }

  this.calcularResultados = function (oAvaliacao) {

    var oParametros        = new Object();
    oParametros.exec       = 'calcularResultado';
    oParametros.iMatricula = oInstancia.iMatriculaAluno;
    oParametros.oAvaliacao = oAvaliacao;

    var oJson          = new Object();
    oJson.method       = 'post';
    oJson.parameters   = 'json='+Object.toJSON(oParametros);
    oJson.onComplete   = oInstancia.retornoCalculo;
    oJson.asynchronous = false;

    var oAjax          = new Ajax.Request(oInstancia.sRPC, oJson);

  }

  this.retornoCalculo = function (oResponse) {


    var oRetorno = eval('('+oResponse.responseText+')');
    if (oRetorno.status == 2) {

      alert(oRetorno.message.urlDecode())
      return;
    }
    if (oRetorno.status == 1) {

      var oPeriodoLancado                   = oInstancia.getValorAproveitamento(oRetorno.iCodigoPeriodo, oRetorno.iCodigoRegencia);
      oPeriodoLancado.lAproveitamentoMinimo = oRetorno.lMinimoAtingido;
      oInstancia.ajustaDependenciaDoPeriodo(oRetorno.iCodigoPeriodo, oRetorno.iCodigoRegencia);
      oRetorno.aResultados.each(function(oResultado, iSeq) {

        var sIdCampoNota = oResultado.iCodigoRegencia+"_"+oResultado.iPeriodo+"_nota";
        var oPeriodoResultado                   = oInstancia.getValorAproveitamento(oResultado.iPeriodo, oResultado.iCodigoRegencia);
        oPeriodoResultado.lAproveitamentoMinimo = oResultado.lMinimoAtingido;
        oInstancia.ajustaDependenciaDoPeriodo(oResultado.iPeriodo, oResultado.iCodigoRegencia);
        if ($(sIdCampoNota))  {

          var oPeriodo = oInstancia.getPeriodo(oResultado.iPeriodo);
          var nNota    = oResultado.nNota;
          if (oPeriodo.sFormaAvaliacao == 'NOTA') {
            nNota = js_mascaraNota(nNota, oInstancia.sMascaraFormatacao);
          }
          $(sIdCampoNota).value = nNota;
        }
      });
    }
  }

  this.abrirParecer = function (iCodigoPeriodo, iRegencia, iOrdem) {

  	var oParametro  = new Object();
  	oParametro.exec = 'getParametroTelaParecer';
  	oWindowParecer  = '';
  	var oAjax = new Ajax.Request(oInstancia.sRPC,
  			                         {
  		                             method:     'post',
  		                             parameters: 'json='+Object.toJSON(oParametro),
  		                             onComplete: function (oResponse) {

    		                           	 var oRetorno = eval('('+oResponse.responseText+')');
  		                            	 if (oRetorno.iParametro == 0) {
  		                            	   oWindowParecer = new DBViewLancamentoAvaliacaoParecer('oWindowParecer',
  		                                                                                       oInstancia.iTurmaAluno,
  		                                                                                       iRegencia,
  		                                                                                       oInstancia.oWindowAuxAvaliacao
  		                                                                                      );
  		                            	} else {
  		                            		oWindowParecer = new DBViewLancamentoParecerDisciplina('oWindowParecer',
                                                                                             oInstancia.iTurmaAluno,
                                                                                             iRegencia,
                                                                                             oInstancia.oWindowAuxAvaliacao
                                                                                            );
  		                            	}
  		                            	oWindowParecer.setMatricula(oInstancia.iMatriculaAluno);
  	                                oWindowParecer.setCodigoPeriodo(iCodigoPeriodo);
                                    oWindowParecer.setEtapa(oInstancia.iEtapaAluno);
                                    oWindowParecer.setOrdem(iOrdem);
  	                                oWindowParecer.show();

  		                            	oWindowParecer.setCallback(function(oDados) {

  		                                 oInstancia.lDadosAlterados = true;
  		                                 oInstancia.lDadosSalvo     = false;
  		                                 sIdCampoNota          = iRegencia+"_"+iCodigoPeriodo+"_nota";
  		                                 $(sIdCampoNota).value = $F('parecer');
  		                                 oWindowParecer.getWindow().destroy();
    		                             });
  		                             }
  			                         }
  			                        );

  }

  /**
   * Retorna os dados da avaliacao
   * @param {integer} iCodigoPeriodo Código do periodo
   * @param {integer} iCodigoRegencia Código da regencia
   * @return Object;
   */
  this.getValorAproveitamento = function(iCodigoAvaliacao, iCodigoRegencia) {

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


  this.ajustaDependenciaDoPeriodo = function(iPeriodo, iRegencia) {

    var oPeriodo           = oInstancia.getPeriodo(iPeriodo);
    if (oPeriodo.iLimiteReprovacao > 0) {
      return;
    }
    var oPeriodoAvaliacao  = oInstancia.getValorAproveitamento(iPeriodo, iRegencia);
    oInstancia.aAvaliacao.each(function (oAvaliacaoDisciplina, iIndice) {

      if (oAvaliacaoDisciplina.iCodigoRegencia == iRegencia
          && oAvaliacaoDisciplina.iPeriodoDependenteAprovacao == iPeriodo) {

        /**
         * @todo implementar aqui o retorno da validacao do numero de disciplinas
         * @type {*}
         */
        var oDependencia = oAvaliacaoDisciplina;
        if (oPeriodoAvaliacao.lAproveitamentoMinimo) {

          oDependencia.nNota                                                         = '';
          $(oDependencia.iCodigoRegencia+"_"+oDependencia.iPeriodo+"_nota").value    = 'dispensado';
          $(oDependencia.iCodigoRegencia+"_"+oDependencia.iPeriodo+"_nota").readOnly = true;
        } else {

          $(oDependencia.iCodigoRegencia+"_"+oDependencia.iPeriodo+"_nota").value    = '';
          $(oDependencia.iCodigoRegencia+"_"+oDependencia.iPeriodo+"_nota").readOnly = false;
        }
        throw $break;
      }
    });
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

  this.validarVariacaoNota = function(nNota, nVariacao) {

	  var lNotaValida   = false;
	  var iTamanhoCasas = 0;
	  if (new String(nVariacao).indexOf('.') >= 0) {
	    iTamanhoCasas = new String(nVariacao).split(".")[1].length;
	  }
	  if (nVariacao != 0) {

	    var nModulo = new Number(nNota) % new Number(nVariacao);
	    if (new String(nModulo).indexOf('e-') > 0) {
        nModulo = nModulo.toFixed(0);
      }

	    if (iTamanhoCasas == 0 && new String(nNota).indexOf('.') >= 0) {
	       return false;
	    }
	    if ((nModulo)  == new Number(0).toFixed(iTamanhoCasas) ||
	       new Number(nModulo).toFixed(iTamanhoCasas)  == new Number(nVariacao).toFixed(iTamanhoCasas)) {
	          lNotaValida = true;
	    }
	  } else if (nVariacao == 0){
	    lNotaValida = true;
	  }
	  return lNotaValida;
  }

  this.buscarDadosAvaliacao = function(iCodigoPeriodo) {

    var oPeriodo                = oInstancia.getPeriodo(iCodigoPeriodo);
    var sMensagemFormaAvaliacao = "<b>Forma de Avaliação por " +oPeriodo.sFormaAvaliacao.urlDecode()+"</b><br>";
    switch(oPeriodo.sFormaAvaliacao) {

      case 'NIVEL':

        sMensagemFormaAvaliacao += "Níveis: ";
        var sVirgula = '';
        oPeriodo.aConceitos.each(function(oConceitos, iSeq) {

          sMensagemFormaAvaliacao += sVirgula+""+oConceitos.sDescricaoConceito.urlDecode();
          sVirgula = ", ";
        });
        sMensagemFormaAvaliacao += " com o mínimo para aprovação: "+oPeriodo.mMinimoAprovacao;
        break;

      case 'NOTA':

        sMensagemFormaAvaliacao += "Notas de "+oPeriodo.iMenorValor+" a "+oPeriodo.iMaiorValor+", ";
        sMensagemFormaAvaliacao += "com variação de "+oPeriodo.nVariacao+", com o ";
        sMensagemFormaAvaliacao += "mínimo para aprovação de "+oPeriodo.mMinimoAprovacao;
        break;

      case 'PARECER':

        sMensagemFormaAvaliacao += "<br>";
        break  ;
    }
    $('dadosAvaliacao').innerHTML = sMensagemFormaAvaliacao;
  }
};

/**
 * Retorna o termo do resultado final
 * @param sResultadoFinal
 * @param lAprovadoDependencia
 * @returns
 */
DBViewLancamentoAvaliacaoAluno.prototype.getTermoResultadoFinal = function(sResultadoFinal, lAprovadoDependencia) {

  var sTermoFinal = '';
  this.aTermosEnsino.each(function(oTermo) {

    if (oTermo.sReferencia == sResultadoFinal) {

      sTermoFinal = oTermo.sAbreviatura.urlDecode();
      if (lAprovadoDependencia) {
        sTermoFinal = 'AP / DP';
      }
      return '' ;
    }
  });
  return sTermoFinal.toUpperCase();
};

DBViewLancamentoAvaliacaoAluno.prototype.lancarObservacao = function (iDisciplina) {

  var oDadosTurma = {iRegencia : iDisciplina,
                    iTurma     : this.iTurmaAluno,
                    iEtapa     : this.iEtapaAluno
                   };
  var oDadosAluno = {
                      iMatricula: this.iMatriculaAluno,
                      iPeriodo  : ''
                     };
  this.oViewLancamentoObservacao = new DBViewAvaliacao.LancamentoObservacao(oDadosTurma,
                                                                           oDadosAluno,
                                                                           false);

  var iAltura  = this.oWindowAuxAvaliacao.getHeight() / 1.1;
  var iLargura = this.oWindowAuxAvaliacao.getWidth() / 2;
  this.oViewLancamentoObservacao.setContainerPai(this.oWindowAuxAvaliacao, {iAltura: iAltura,
                                                                            iLargura:iLargura
                                                                           });

  if (this.oJanelaOrigemNota) {
    return true;
  }

  this.oViewLancamentoObservacao.setCallBackWindow(function() {

    //this.show();
    this.oJanelaOrigemNota = false;
  });
  this.oViewLancamentoObservacao.show();
};
DBViewLancamentoAvaliacaoAluno.prototype.abrirParecerComplementar = function (iCodigoRegencia) {

  var iRegencia = iCodigoRegencia;
  var iTurma    = this.iTurmaAluno;
  var oSelf     = this;
  var oParametro  = {};
  oParametro.exec = 'getParametroTelaParecer';
  oWindowParecer  = '';
  var oAjax = new Ajax.Request('edu4_lancamentoavaliacao.RPC.php',
                               {
                                 method:     'post',
                                 parameters: 'json='+Object.toJSON(oParametro),
                                 onComplete: function (oResponse) {

                                   var oRetorno = eval('('+oResponse.responseText+')');
                                   if (oRetorno.iParametro == 0) {

                                     oWindowParecer = new DBViewLancamentoAvaliacaoParecer('oWindowParecer',
                                                                                           iTurma,
                                                                                           iRegencia,
                                                                                           oSelf.oWindowAuxAvaliacao
                                                                                          );

                                  } else {

                                    oWindowParecer = new DBViewLancamentoParecerDisciplina('oWindowParecer',
                                                                                           iTurma,
                                                                                           iRegencia,
                                                                                           oSelf.oWindowAuxAvaliacao
                                                                                          );
                                  }

                                  oWindowParecer.setEtapa(oSelf.iEtapaAluno);
                                  oWindowParecer.setProfessorLogado(oRetorno.lProfessorLogado);
                                  oWindowParecer.mostrarPesquisaAluno(false);
                                  oWindowParecer.mostrarPesquisaPeriodo(true);
                                  oWindowParecer.setMatricula(oSelf.iMatriculaAluno);
                                  oWindowParecer.show();
                                  oWindowParecer.setCallback(function(oDados) {

                                     oWindowParecer.getWindow().destroy();
                                     oSelf.oWindow.toFront();
                                   });
                                 }
                               }
                              );



};
/**
 * Retorna o periodo dependente  do período passado como parâmetro.
 * @param iPeriodo
 * @returns {Object}
 */
DBViewLancamentoAvaliacaoAluno.prototype.getPeriodoDependenteDoPeriodo = function(iPeriodo) {

  var oSelf              = this;
  var oPeriodoDependente = '';
  oSelf.aPeriodosAluno.each(function (oPeriodo, iIndice) {

    if (oPeriodo.iPeriodoDependenteAprovacao == iPeriodo) {

      oPeriodoDependente = oPeriodo;
      return ;
    }
  });
  return oPeriodoDependente;
};
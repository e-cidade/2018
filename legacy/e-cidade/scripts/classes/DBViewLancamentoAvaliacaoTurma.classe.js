require_once("scripts/classes/DBViewLancamentoAvaliacao/TabIndexNotaAluno.classe.js");
require_once("scripts/classes/DBViewOrigemNota.classe.js");
require_once("scripts/classes/DBViewAbonoFalta.classe.js");
require_once("scripts/classes/DBViewLancamentoAvaliacao/Conversao.classe.js");
require_once("scripts/classes/DBViewLancamentoAvaliacao/ValidacaoVariacaoNota.classe.js");
require_once("scripts/classes/DBViewLancamentoAvaliacao/AlteraResultadoFinal.classe.js");
require_once("scripts/classes/DBViewConsultaAvaliacoesAluno.classe.js");
require_once("scripts/classes/DBViewLancamentoAvaliacao/LancamentoObservacao.classe.js");
require_once("scripts/classes/DBViewLancamentoAvaliacao/LancamentoAmparo.classe.js");
require_once("scripts/classes/educacao/escola/LegendasLancamentoAvaliacao.classe.js");
require_once("scripts/classes/educacao/escola/Proporcionalidade.classe.js");
require_once("scripts/classes/educacao/escola/AvaliacaoAlternativa.classe.js");

/**
 * Monta a grade de avaliação de uma turma
 * @param oDadosTurmaSelecionada
 * @returns {DBViewLancamentoAvaliacaoTurma}
 */
DBViewLancamentoAvaliacaoTurma = function (oDadosTurmaSelecionada ) {

  /**
   * Dados que vem no Objeto oDadosTurmaSelecionada
   * Obs.: No array de períodos, vem sómente os períodos que recebem faltas
   * @exemple
   * ({iTurma:"680",
   *   iEtapa:"17",
   *   sEscola:"EMEF BARAO DO RIO BRANCO",
   *   sCalendario:"CALENDÁC1RIO 2013",
   *   sCurso:"ENSINO FUNDAMENTAL 9 ANOS",
   *   sBaseCurricular:"ANOS FINAIS",
   *   sTurma:"8º ANO",
   *   sEtapa:"8º ANO",
   *   sProcedimentoAvaliacao:"NOTA",
   *   sFormaObtencaoResultado:"SO",
   *   sTurno:"MANHÃ",
   *   sFrequencia:"PERÍCDODOS",
   *   aDisciplinas:[{iCodigo:"5627",
   *                  sDescricao:"LINGUA PORTUGUESA",
   *                  sAbrev:"LP",
   *                  lEncerrada:"",
   *                  aPeriodos:[{iCodigo:"1",
   *                              iAulas:""},
   *                             {iCodigo:"2",
   *                              iAulas:""},
   *                             {iCodigo:"3",
   *                              iAulas:""}
   *                            ]
   *                 },
   *                 {iCodigo:"5628",
   *                  sDescricao:"MATEMATICA",
   *                  sAbrev:"MAT",
   *                  lEncerrada:"",
   *                  aPeriodos:[{iCodigo:"1",
   *                              iAulas:""},
   *                             {iCodigo:"2",
   *                              iAulas:""},
   *                             {iCodigo:"3",
   *                              iAulas:""}
   *                            ]
   *                 }
   *                ]
   *  }
   * )
   * @var {Object}
   */
  this.oDadosTurmaSelecionada = oDadosTurmaSelecionada;

  /**
   * Tamanho que a janela deve possuir
   * @var {integer}
   */
  this.iTamanhoJanela = document.body.getWidth() - 30;

  /**
   * Variável para receber a intancia da grid
   * @var {Object}
   */
  this.oGridAproveitamento = {};

  /**
   * Controla se a grade de aproveitamento foi alterada.
   * Se a grade for alterada devemos avisar o usuário para ele realizar a persistencia dos dados
   * @var {boolean}
   */
  this.lGradeAlterada = false;

  /**
   * Rpc utilizado pela classe
   * @var {string}
   */
  this.sRpcPadrao = 'edu4_lancamentoavaliacaonota.RPC.php';

  /**
   * Períodos de avaliação da turma
   * @var {Array}
   */
  this.aPeriodosAvaliacao = new Array();

  /**
   * Termos da Turma
   * @var Array
   */
  this.aTermos = new Array();

  /**
   * Mascara de formatação da nota;
   * Só usada quando avaliação for do tipo nota.
   * @var {string}
   */
  this.sMascara = '';

  /**
   * Navegação dos campos
   * @var {integer}
   */
  this.iTabIndex = null;

  /**
   * Variável de para controlar se devemos gerar resultado parcial
   * @var {boolean}
   */
  this.lGeraResultadoParcial = true;

  this.lOrigemNota           = false;
  this.lAbonar               = false;
  this.lConversao            = false;
  this.lAlteraResultadoFinal = false;
  this.lAbriuWindowParecer   = false;

  this.lTurmaEncerrada = oDadosTurmaSelecionada.lTurmaEncerrada;

  this.oJanelaOrigemNota = false;

  this.lProfessorLogado  = oDadosTurmaSelecionada.lProfessorLogado;

  this.aSituacoesMatriculaValida = ['MATRICULADO', 'REMATRICULADO'];

  /****************************************************
   ******     Botoes e componentes da View   ***********
   ***************************************************** */
  var sDisplayBotoes = '';
  if (this.lProfessorLogado) {
    sDisplayBotoes = 'none';
  }

  /**
   * Label Exibir troca de Turma
   */
  this.oLabelExibirTrocaTurma = new Element('label', {'class':'bold'}).update('Exibir Troca de Turma:');

  /**
   * ComboBox Exibir troca de Turma
   */
  this.oSelectExibirTrocaTurma                    = document.createElement('select');
  this.oSelectExibirTrocaTurma.id                 = 'exibirTrocaTurma';
  this.oSelectExibirTrocaTurma.style.marginRight  = '30px';
  this.oSelectExibirTrocaTurma.add( new Option('Sim', 1) );
  this.oSelectExibirTrocaTurma.add( new Option('Não', 2) );

  /**
   * Select com as disciplinas da Turma
   * @var {Element} oSelectDisciplinas
   */
  this.oSelectDisciplinas                    = document.createElement('select');
  this.oSelectDisciplinas.id                 = 'disciplina';
  this.oSelectDisciplinas.style.width        = '250px';
  this.oSelectDisciplinas.style.marginRight  = '30px';

  this.oBtnOrigemNota                   = document.createElement('input');
  this.oBtnOrigemNota.type              = 'button';
  this.oBtnOrigemNota.value             = 'Origem da Nota';
  this.oBtnOrigemNota.name              = 'origemNota';
  this.oBtnOrigemNota.id                = 'btnOrigemNota';
  this.oBtnOrigemNota.style.marginRight = '5px';
  this.oBtnOrigemNota.style.height      = '20px';
  this.oBtnOrigemNota.style.display     = sDisplayBotoes;
  this.oBtnOrigemNota.disabled          = true;

  this.oBtnAbonarFaltas                   = document.createElement('input');
  this.oBtnAbonarFaltas.type              = 'button';
  this.oBtnAbonarFaltas.value             = 'Abonar Faltas';
  this.oBtnAbonarFaltas.name              = 'abonarFaltas';
  this.oBtnAbonarFaltas.id                = 'btnAbonarFaltas';
  this.oBtnAbonarFaltas.style.marginRight = '5px';
  this.oBtnAbonarFaltas.style.width       = '115px';
  this.oBtnAbonarFaltas.style.height      = '20px';
  this.oBtnAbonarFaltas.style.display     = sDisplayBotoes;
  this.oBtnAbonarFaltas.disabled          = true;

  this.oBtnParecer                   = document.createElement('input');
  this.oBtnParecer.type              = 'button';
  this.oBtnParecer.value             = 'Parecer';
  this.oBtnParecer.name              = 'parecer';
  this.oBtnParecer.id                = 'btnParecer';
  this.oBtnParecer.style.marginRight = '5px';
  this.oBtnParecer.style.height      = '20px';
  this.oBtnParecer.style.width       = '130px';
  this.oBtnParecer.disabled          = false;

  if (this.oDadosTurmaSelecionada.sTipoProcedimentoAvaliacao == 'PARECER' || this.lTurmaEncerrada) {
    this.oBtnParecer.disabled = true;
  }

  this.oBtnObservacao                   = document.createElement('input');
  this.oBtnObservacao.type              = 'button';
  this.oBtnObservacao.value             = 'Observação';
  this.oBtnObservacao.name              = 'observacao';
  this.oBtnObservacao.id                = 'btnObservacao';
  this.oBtnObservacao.style.marginRight = '5px';
  this.oBtnObservacao.style.height      = '20px';
  this.oBtnObservacao.disabled          = true;

  this.oBtnAmparo                   = document.createElement('input');
  this.oBtnAmparo.type              = 'button';
  this.oBtnAmparo.value             = 'Amparo';
  this.oBtnAmparo.name              = 'amparo';
  this.oBtnAmparo.id                = 'btnAmparo';
  this.oBtnAmparo.style.marginRight = '5px';
  this.oBtnAmparo.style.height      = '20px';
  this.oBtnAmparo.style.display     = sDisplayBotoes;
  this.oBtnAmparo.disabled          = true;

  this.oBtnConversao                   = document.createElement('input');
  this.oBtnConversao.type              = 'button';
  this.oBtnConversao.value             = 'Conversão';
  this.oBtnConversao.name              = 'conversao';
  this.oBtnConversao.id                = 'btnConversao';
  this.oBtnConversao.style.marginRight = '5px';
  this.oBtnConversao.style.height      = '20px';
  this.oBtnConversao.style.display     = sDisplayBotoes;
  this.oBtnConversao.disabled          = true;

  this.oBtnAlteraResultadoFinal                   = document.createElement('input');
  this.oBtnAlteraResultadoFinal.type              = 'button';
  this.oBtnAlteraResultadoFinal.value             = 'Alterar Resultado Final';
  this.oBtnAlteraResultadoFinal.name              = 'btnAlteraResultadoFinal';
  this.oBtnAlteraResultadoFinal.id                = 'btnAlteraResultadoFinal';
  this.oBtnAlteraResultadoFinal.style.marginRight = '5px';
  this.oBtnAlteraResultadoFinal.style.display     = sDisplayBotoes;
  this.oBtnAlteraResultadoFinal.style.height      = '20px';
  this.oBtnAlteraResultadoFinal.disabled          = true;

  this.oBtnSalvar          = document.createElement('input');
  this.oBtnSalvar.type     = 'button';
  this.oBtnSalvar.value    = 'Salvar';
  this.oBtnSalvar.name     = 'salvar';
  this.oBtnSalvar.id       = 'btnSalvar';
  this.oBtnSalvar.disabled = true;

  this.oBtnLegendas = document.createElement( 'input' );
  this.oBtnLegendas.setAttribute( 'type', 'button' );
  this.oBtnLegendas.setAttribute( 'value', 'Legendas' );
  this.oBtnLegendas.setAttribute( 'name', 'legendas' );
  this.oBtnLegendas.setAttribute( 'id', 'btnLegendas' );
  this.oBtnLegendas.setStyle( { 'width' : '106px' } );

  this.oBtnProporcionalidade = document.createElement( 'input' );
  this.oBtnProporcionalidade.setAttribute( 'type', 'button' );
  this.oBtnProporcionalidade.setAttribute( 'value', 'Proporcionalidade' );
  this.oBtnProporcionalidade.setAttribute( 'name', 'proporcionalidade' );
  this.oBtnProporcionalidade.setAttribute( 'id', 'btnProporcionalidade' );
  this.oBtnProporcionalidade.setStyle( { 'width' : '115px' } );

  this.oBtnAvaliacaoAlternativa = document.createElement( 'input' );
  this.oBtnAvaliacaoAlternativa.setAttribute( 'type', 'button' );
  this.oBtnAvaliacaoAlternativa.setAttribute( 'value', 'Avaliação Alternativa' );
  this.oBtnAvaliacaoAlternativa.setAttribute( 'name', 'avaliacaoalternativa' );
  this.oBtnAvaliacaoAlternativa.setAttribute( 'id', 'btnAvaliacaoAlternativa' );
  this.oBtnAvaliacaoAlternativa.setStyle( { 'width' : '130px' } );

  this.oTabelaOpcoes = document.createElement( 'table' );
  this.oTabelaOpcoes.setAttribute( 'id', 'oTabelaOpcoes' );

  this.oLinhaTrocaTurmaLegenda = document.createElement( 'tr' );
  this.oLinhaTrocaTurmaLegenda.setAttribute( 'id', 'oLinhaTrocaTurmaLegenda' );

  this.oColunaTrocaTurma = document.createElement( 'td' );
  this.oColunaTrocaTurma.setAttribute( 'id', 'oColunaTrocaTurma' );

  this.oColunaBotaoLegenda = document.createElement( 'td' );
  this.oColunaBotaoLegenda.setAttribute( 'id', 'oColunaBotaoLegenda' );

  this.oColunaProporcionalidade = document.createElement( 'td' );
  this.oColunaProporcionalidade.setAttribute( 'id', 'oColunaProporcionalidade' );

  this.oColunaAvaliacaoAlternativa = document.createElement( 'td' );
  this.oColunaAvaliacaoAlternativa.setAttribute( 'id', 'oColunaAvaliacaoAlternativa' );

  this.oLinhaDisciplinaBotoes = document.createElement( 'tr' );
  this.oLinhaDisciplinaBotoes.setAttribute( 'id', 'oLinhaDisciplinaBotoes' );

  this.oColunaDisciplinas = document.createElement( 'td' );
  this.oColunaDisciplinas.setAttribute( 'id', 'oColunaDisciplinas' );

  this.oColunaOrigemNota = document.createElement( 'td' );
  this.oColunaOrigemNota.setAttribute( 'id', 'oColunaOrigemNota' );

  this.oColunaAbonarFaltas = document.createElement( 'td' );
  this.oColunaAbonarFaltas.setAttribute( 'id', 'oColunaAbonarFaltas' );

  this.oColunaParecer = document.createElement( 'td' );
  this.oColunaParecer.setAttribute( 'id', 'oColunaParecer' );

  this.oColunaObservacao = document.createElement( 'td' );
  this.oColunaObservacao.setAttribute( 'id', 'oColunaObservacao' );

  this.oColunaAmparo = document.createElement( 'td' );
  this.oColunaAmparo.setAttribute( 'id', 'oColunaAmparo' );

  this.oColunaConversao = document.createElement( 'td' );
  this.oColunaConversao.setAttribute( 'id', 'oColunaConversao' );

  this.oColunaAlteraResultadoFinal = document.createElement( 'td' );
  this.oColunaAlteraResultadoFinal.setAttribute( 'id', 'oColunaAlteraResultadoFinal' );

  /**
   * Callback adicional para o fechamento da View
   */
  this.oCallBackCloseWindow = function() {
    return true;
  };

  this.oDBFormCache = new DBFormCache( 'oDbFormCache', 'DBViewLancamentoAvaliacaoTurma.classe.js' );
  this.oDBFormCache.setElements( new Array( this.oSelectExibirTrocaTurma ) );
};

/**
 * Define um callback para o fechamento da janela de lancamento de avaliacoes
 * @param function sFunction funcao a ser executada
 */
DBViewLancamentoAvaliacaoTurma.prototype.setCallBackCloseWindow = function(sFunction) {
  this.oCallBackCloseWindow = sFunction;
};

/**
 * Renderiza a windown inicial
 */
DBViewLancamentoAvaliacaoTurma.prototype.criarWindow = function () {

  var oSelf    = this;
  this.oWindow = new windowAux("wndAvaliacaoTurma",
                               "Lançamento de Avaliações",
                               this.iTamanhoJanela
                              );
  this.oWindow.allowCloseWithEsc(false);

  var sConteudo  = "<div id='avaliacaoTurma' style='width: "+(this.iTamanhoJanela - 50)+"'>                             ";
      sConteudo += "  <div id='opcoes' >                                                                                ";
      sConteudo += "    <fieldset id='ctnOpcoes' style='width:100%'>                                                    ";
      sConteudo += "      <legend><b>Opções</b></legend>                                                                ";
      sConteudo += "    </fieldset>                                                                                     ";
      sConteudo += "    <div style='position:relative'>                                                                 ";
      sConteudo += "      <div style='width: 50%;display:inline-block;'>                                                ";
      sConteudo += "        <fieldset style='width: 97%; height: 6%;'>                                                  ";
      sConteudo += "          <legend id='ctnControleFrequencia' class='bold'>Controle de Frequência/Avaliação</legend> ";
      sConteudo += "            <span id='legendaControleFrequencia' ></span>                                           ";
      sConteudo += "        </fieldset>                                                                                 ";
      sConteudo += "      </div>                                                                                        ";
      sConteudo += "      <div style='float: right;width: 50%;display:inline-block;'>                                   ";
      sConteudo += "        <fieldset style='width: 100%; height: 6%;'>                                                 ";
      sConteudo += "          <legend id='ctnLegenda' class='bold'>Forma de Avaliação</legend>                          ";
      sConteudo += "          <table>                                                                                   ";
      sConteudo += "            <tr>                                                                                    ";
      sConteudo += "              <td>                                                                                  ";
      sConteudo += "                <span id='legendaFormaAvaliacao' ></span>                                           ";
      sConteudo += "              </td>                                                                                 ";
      sConteudo += "            </tr>                                                                                   ";
      sConteudo += "          </table>                                                                                  ";
      sConteudo += "        </fieldset>                                                                                 ";
      sConteudo += "      </div>                                                                                        ";
      sConteudo += "    </div>                                                                                          ";
      sConteudo += "  </div>                                                                                            ";
      sConteudo += "  <div id='gradeAproveitamento'>                                                                    ";
      sConteudo += "    <fieldset  style='width:100%'>                                                                  ";
      sConteudo += "      <legend><b>Grade de Aproveitamento</b></legend>                                               ";
      sConteudo += "      <div id='ctnGradeAproveitamento'></div>                                                       ";
      sConteudo += "    </fieldset>                                                                                     ";
      sConteudo += "    <center id='ctnSalvar'></center>                                                                ";
      sConteudo += "  </div>                                                                                            ";
      sConteudo += "</div>                                                                                              ";

  this.oWindow.setShutDownFunction( function() {

    if (oSelf.validaSeGradeFoiAlterada()) {
      return false;
    }

    oSelf.oCallBackCloseWindow();
    oSelf.oWindow.destroy();
    delete oSelf.oGridAproveitamento;
    oSelf.oDBFormCache.save();
    oSelf.limparSessao();
  });

  var sMsg  = 'Turma: ' +this.oDadosTurmaSelecionada.sTurma.urlDecode();
      sMsg += ' do calendário: ' + this.oDadosTurmaSelecionada.sCalendario.urlDecode();

  var sHelpMsgBox  = 'Informe os dados das avaliações dos alunos. ';
      sHelpMsgBox += 'Pressionar Ctrl + Seta cima/baixo para alterar a disciplina de lançamento. ';
      sHelpMsgBox += 'Enter salva o lançamento (somente se a grade for alterada).  ';
      sHelpMsgBox += 'Duplo clique no nome do aluno exibe a grade de avaliação individual.';

  this.oWindow.setContent(sConteudo);
  this.oMessageBoard = new DBMessageBoard('msgBoardAvaliacao',
                                         sMsg,
                                         sHelpMsgBox,
                                         this.oWindow.getContentContainer()
                                        );

  this.oWindow.show();
  this.oWindow.addEvent('keyup', function (Event) {

    var iTecla = Event.which;
    if (Event.ctrlKey) {

      if (iTecla == KEY_DOWN || iTecla == KEY_UP) {

        var iIncremento = iTecla == KEY_DOWN ?  1 : -1;
        var iProximoIndiceComboDisciplina = $('disciplina').selectedIndex + iIncremento;
        if ($('disciplina').options[iProximoIndiceComboDisciplina]) {

          var iCodigoDisciplina = $('disciplina').options[iProximoIndiceComboDisciplina].value;
          $('disciplina').value = iCodigoDisciplina;

          oSelf.validaFormaAvaliacaoDisciplina();
          oSelf.getPeriodosAvaliacao();
          oSelf.buscaAlunos();
        }

        if (iTecla == KEY_S && oSelf.lGradeAlterada) {
          oSelf.salvarGradeAproveitamento();
        }
      }
    }

    if (iTecla == KEY_ENTER && oSelf.lGradeAlterada) {
      oSelf.salvarGradeAproveitamento();
    }

    Event.preventDefault();
    Event.stopPropagation();
    return false;
  });
};

/**
 * Cria a janela para visualização do usuário
 */
DBViewLancamentoAvaliacaoTurma.prototype.show = function() {

  var oSelf = this;
  if (!this.oWindow) {
    this.criarWindow();
  }

  $('ctnOpcoes').appendChild( this.oTabelaOpcoes );
  $('oTabelaOpcoes').appendChild( this.oLinhaTrocaTurmaLegenda );
  $('oTabelaOpcoes').appendChild( this.oLinhaDisciplinaBotoes );

  $('oLinhaTrocaTurmaLegenda').appendChild( this.oColunaTrocaTurma );
  $('oLinhaTrocaTurmaLegenda').appendChild( this.oColunaBotaoLegenda );
  $('oLinhaTrocaTurmaLegenda').appendChild( this.oColunaProporcionalidade );
  $('oLinhaTrocaTurmaLegenda').appendChild( this.oColunaAvaliacaoAlternativa );

  $('oColunaTrocaTurma').appendChild( this.oLabelExibirTrocaTurma );
  $('oColunaTrocaTurma').appendChild( this.oSelectExibirTrocaTurma );

  $('oColunaBotaoLegenda').appendChild( this.oBtnLegendas );
  $('oColunaProporcionalidade').appendChild( this.oBtnProporcionalidade );

  $('oColunaAvaliacaoAlternativa').appendChild( this.oBtnAvaliacaoAlternativa );

  $('oLinhaDisciplinaBotoes').appendChild( this.oColunaDisciplinas );
  $('oLinhaDisciplinaBotoes').appendChild( this.oColunaOrigemNota );
  $('oLinhaDisciplinaBotoes').appendChild( this.oColunaAbonarFaltas );
  $('oLinhaDisciplinaBotoes').appendChild( this.oColunaParecer );
  $('oLinhaDisciplinaBotoes').appendChild( this.oColunaObservacao );
  $('oLinhaDisciplinaBotoes').appendChild( this.oColunaAmparo );
  $('oLinhaDisciplinaBotoes').appendChild( this.oColunaConversao );
  $('oLinhaDisciplinaBotoes').appendChild( this.oColunaAlteraResultadoFinal );

  $('oColunaDisciplinas').appendChild( this.oSelectDisciplinas );
  $('oColunaOrigemNota').appendChild( this.oBtnOrigemNota );
  $('oColunaAbonarFaltas').appendChild( this.oBtnAbonarFaltas );
  $('oColunaParecer').appendChild( this.oBtnParecer );
  $('oColunaObservacao').appendChild( this.oBtnObservacao );
  $('oColunaAmparo').appendChild( this.oBtnAmparo );
  $('oColunaConversao').appendChild( this.oBtnConversao );
  $('oColunaAlteraResultadoFinal').appendChild( this.oBtnAlteraResultadoFinal );

  this.oDBFormCache.load();

  this.montaSelectDisciplinas();
  this.buscaTermosTurma();
  this.getPeriodosAvaliacao();
  this.buscaAlunos();

  $('ctnSalvar').appendChild(this.oBtnSalvar);
  $('btnSalvar').onclick  = function () {

    oSelf.salvarGradeAproveitamento();
  };

  /**
   * Atribui a função no combo de disciplina para recarregar a grade de aproveitamento com os dados da disciplina
   * selecionada
   */
  $('disciplina').onchange = function() {

    oSelf.lOrigemNota           = false;
    oSelf.lAbonar               = false;
    oSelf.lConversao            = false;
    oSelf.lAlteraResultadoFinal = false;

    oSelf.bloqueiaBotoes();
    oSelf.validaFormaAvaliacaoDisciplina();

    if (oSelf.validaSeGradeFoiAlterada()) {
      return false;
    }

    oSelf.getPeriodosAvaliacao();
    oSelf.buscaAlunos();
  };

  $('exibirTrocaTurma').onchange = function() {
    oSelf.buscaAlunos();
  }
};

/**
 * Verifica se a grid foi alterada e avisa o usuário caso sim.
 * @returns {Boolean}
 */
DBViewLancamentoAvaliacaoTurma.prototype.validaSeGradeFoiAlterada = function () {

  var oSelf = this;
  if (oSelf.lGradeAlterada) {

    var sMsgConfirm  = 'Grade de aproveitamento foi alterada, se fechar a janela, perderá todas as modificações ';
        sMsgConfirm += 'realizadas.\nDeseja mesmo fechar a janela sem salvar?';
    if (confirm(sMsgConfirm)) {
      return false;
    }
    return true;
  }
  return false;
};

/**
 * Cria o combobox com as disciplinas da turma
 * @returns null
 */
DBViewLancamentoAvaliacaoTurma.prototype.montaSelectDisciplinas = function() {

  var oSelf = this;

  $('disciplina').innerHTML = '';

  this.oDadosTurmaSelecionada.aDisciplinas.each(function(oDisciplina) {

    var sTipoMatricula = "OP";
    if (oDisciplina.lObrigatoria) {
      sTipoMatricula = "OB";
    }

    var oOption       = document.createElement('option');
    oOption.value     = oDisciplina.iCodigo;
    oOption.innerHTML = sTipoMatricula + ' - ' + oDisciplina.sDescricao.urlDecode();
    oSelf.oSelectDisciplinas.appendChild(oOption);
  });
  this.validaFormaAvaliacaoDisciplina();
};

/**
 * Realiza a busca dos termos de encerramento da turma,
 * @returns {DBViewLancamentoAvaliacaoTurma.retornoTermos}
 */
DBViewLancamentoAvaliacaoTurma.prototype.buscaTermosTurma = function () {

  var oParametro             = new Object();
  oParametro.exec            = 'buscaTermosAvaliacao';
  oParametro.iEtapa          = this.oDadosTurmaSelecionada.iEtapa;
  oParametro.iTurma          = this.oDadosTurmaSelecionada.iTurma;
  oParametro.lTrazResultados = true;

  var oSelf = this;

  new Ajax.Request(this.sRpcPadrao,
                   { method:     'post',
                     parameters: 'json='+Object.toJSON(oParametro),
                     asynchronous: false,
                     onComplete: function(oAjax) {
                                   oSelf.retornoTermos(oAjax);
                                 }
                   }
                  );
};

/**
 * Seta os termos do resultado final na classe
 * @param oAjax
 */
DBViewLancamentoAvaliacaoTurma.prototype.retornoTermos = function (oAjax) {

  var oRetorno = eval('('+oAjax.responseText+')');
  this.aTermos = oRetorno.aTermos;
};

/**
 * Pesquisa os periodos de avaliação da turma, baseado no procedimento de avaliaacao da turma.
 * é utilizado para a montagem da grid para o lancamento dos alunos.;
 * @returns {DBViewLancamentoAvaliacaoTurma.retornoDosPeriodos}
 */
DBViewLancamentoAvaliacaoTurma.prototype.getPeriodosAvaliacao = function () {

  var oParametro             = new Object();
  oParametro.exec            = 'getPeriodosAvaliacao';
  oParametro.iEtapa          = this.oDadosTurmaSelecionada.iEtapa;
  oParametro.iTurma          = this.oDadosTurmaSelecionada.iTurma;
  oParametro.iRegencia       = $F('disciplina');
  oParametro.lTrazResultados = true;

  var oSelf = this;

  js_divCarregando("Aguarde, carregando dados da turma.", "msgBox");
  new Ajax.Request(this.sRpcPadrao,
                   { method:     'post',
                     asynchronous:false,
                     parameters: 'json='+Object.toJSON(oParametro),
                     onComplete: function(oAjax) {
                                   oSelf.retornoDosPeriodos(oAjax);
                                 }
                   }
                  );
};

/**
 * Retorno da pesquisa dos periodos de avaliacao
 */
DBViewLancamentoAvaliacaoTurma.prototype.retornoDosPeriodos = function (oAjax) {

  var oSelf    = this;
  var oRetorno = eval('('+oAjax.responseText+')');

  if (oRetorno.status == 2) {

    alert(oRetorno.message.urlDecode());
    return false;
  }

  this.aPeriodosAvaliacao = [];

  this.lProfessorLogado = oRetorno.lProfessorLogado;
  this.bloqueiaBotoes();

  /**
   * aGrupos represenca a primeira coluna do cabeçalho da grid
   * aHeader representa a segunda coluna do cabeçalho da grid
   */
  var aAlign  = new Array('right', 'left');
  var aHeader = new Array('Nº', 'Aluno', 'Situação', 'FA', 'OBS', 'PAR' );
  var aWidth  = new Array('20px', '38%', '8%', '2%', '2%', '2%' );

  var aGrupos      = new Array();
  var oGrupo       = new Object();
  oGrupo.descricao = "Dados do Aluno";
  oGrupo.aColunas  = new Array(0, 1, 2, 3, 4, 5);
  aGrupos.push(oGrupo);

  var iUltimoPeriodo       = 0;

  oRetorno.aPeriodos.each(function (oPeriodo, iPeriodo) {

    oSelf.aPeriodosAvaliacao[oPeriodo.iOrdemAvaliacao] = oPeriodo;
    var oGrupoPeriodo       = new Object();
    oGrupoPeriodo.descricao = oPeriodo.sDescricaoPeriodoAbreviado.urlDecode();
    oGrupoPeriodo.aColunas  = new Array(iPeriodo+1, iPeriodo+2);
    aGrupos.push(oGrupoPeriodo);

    aHeader.push(oPeriodo.sFormaAvaliacao);
    aHeader.push('Falta');
    aAlign.push('center', 'center');
    aWidth.push('7%', '7%');

    iUltimoPeriodo = iPeriodo+2;
  });

  var oGrupoRF       = new Object();
  oGrupoRF.descricao = 'Resultado Final';
  oGrupoRF.aColunas  = new Array(iUltimoPeriodo+3, iUltimoPeriodo+4);
  aGrupos.push(oGrupoRF);

  aHeader.push('Aproveitamento');
  aHeader.push('RF');
  aAlign.push('center', 'center');
  aWidth.push('8%', '9%');

  delete this.oGridAproveitamento;
  $('ctnGradeAproveitamento').innerHTML = '';

  this.oGridAproveitamento = new DBGridMultiCabecalho('oGridAproveitamento');
  this.oGridAproveitamento.setCellWidth(aWidth);
  this.oGridAproveitamento.setCellAlign(aAlign);
  this.oGridAproveitamento.setHeader(aHeader);

  aGrupos.each(function(oGrupo, iSeq) {
    oSelf.oGridAproveitamento.adicionarGrupo(oGrupo.descricao, oGrupo.aColunas, '0');
  });

  this.oGridAproveitamento.setHeight(this.iTamanhoJanela / 4.5);
  this.oGridAproveitamento.show($('ctnGradeAproveitamento'));
  this.oGridAproveitamento.clearAll(true);
};

/**
 * Busca os alunos e avaliações para popular a grid
 */
DBViewLancamentoAvaliacaoTurma.prototype.buscaAlunos = function () {

  if ($("msgBox")) {
    js_removeObj("msgBox");
  }

  $('disciplina').disabled   = true;
  var oParametro             = new Object();
  oParametro.exec            = 'getAlunosAvaliacaoRegencia';
  oParametro.iEtapa          = this.oDadosTurmaSelecionada.iEtapa;
  oParametro.iTurma          = this.oDadosTurmaSelecionada.iTurma;
  oParametro.iRegencia       = $F('disciplina');

  var oSelf = this;

  this.oGridAproveitamento.clearAll(true);
  js_divCarregando("Aguarde, carregando alunos da turma.", "msgBox");
  new Ajax.Request(this.sRpcPadrao,
                   { method:     'post',
                     parameters: 'json='+Object.toJSON(oParametro),
                     asynchronous: false,
                     onComplete: function(oAjax) {
                                   oSelf.populaGrid(oAjax);
                                 }
                   }
                  );
};

/**
 * utiliza os dados retornados pelo metodo buscaAlunos e rederiza os dados na grid
 * @param oAjax
 */
DBViewLancamentoAvaliacaoTurma.prototype.populaGrid = function (oAjax) {

  if ($("msgBox")) {
    js_removeObj("msgBox");
  }
  $('disciplina').disabled = false;
  var oSelf = this;

  var oRetorno = eval('('+oAjax.responseText+')');

  if (oRetorno.status == 2 ) {

    alert( oRetorno.message.urlDecode() );
    return;
  }

  this.sMascara              = oRetorno.sMascaraFormatacao;
  this.iTabIndex             = oRetorno.iTabIndex;
  this.lGeraResultadoParcial = oRetorno.lGeraResultadoParcial;
  var iLinhaGrid = 0;

  var sImagemSituacao     = "<img src='imagens/gtk_ok.png' border='0'>";
  oRetorno.aAlunos.each(function (oAluno) {

    if ($F('exibirTrocaTurma') == 2 && oAluno.sSituacaoAluno.urlDecode() == 'TROCA DE TURMA' ) {
      return;
    }

    var oLabelNome              = document.createElement('label');
    oLabelNome.style.marginLeft = '3px';
    oLabelNome.innerHTML        = oAluno.sNomeAluno.urlDecode();

    var sSeparador  = '   ';
    var sLabelAluno = '';

    if ( oAluno.lAvaliadoParecer ) {

      sLabelAluno += "   (NEE - Parecer)";
      sSeparador            = ' - ';
    }

    if( oAluno.lProgressaoParcialAnterior ) {
      sLabelAluno += sSeparador +  "AP/DP";
    }

    oLabelNome.innerHTML += "<span class='text-right bold'>" + sLabelAluno + "</span>";

    var sSituacaoAbonoFalta = '';
    var sSituacaoObservacao = '';
    var sSituacaoParecer    = '';

    if( oAluno.lTemAbonoFalta ) {
      sSituacaoAbonoFalta = sImagemSituacao;
    }

    if( oAluno.lTemObservacao ) {
      sSituacaoObservacao = sImagemSituacao;
    }

    if( oAluno.lTemParecer ) {
      sSituacaoParecer = sImagemSituacao;
    }

    var iNumeroAluno       = oAluno.iSequencia != '' ? oAluno.iSequencia : '';
    var sSituacaoAluno     = oAluno.sSituacaoAluno.urlDecode();
    var sSituacaoReal      = oAluno.sSituacaoReal.urlDecode();
    var sSituacaoAbreviada = oAluno.sSituacaoAbreviada.urlDecode();

    var aLinha = [];
        aLinha.push( iNumeroAluno );
        aLinha.push( oLabelNome.outerHTML );
        aLinha.push( sSituacaoAbreviada );
        aLinha.push( sSituacaoAbonoFalta );
        aLinha.push( sSituacaoObservacao );
        aLinha.push( sSituacaoParecer );

    /**
     * Renderiza os aproveitamentos
     */
    oAluno.oDisciplina.aAproveitamentos.each(function (oAproveitamento, iSeq) {

      var oParametros               = new Object();
      oParametros.lEncerrado        = false;  // Avaliacao encerrada
      oParametros.lAvaliacaoExterna = false;  // Quando avaliação vem de outra escola
      oParametros.lReadOnlyNota     = false;  // bloqueia somente o campo nota
      oParametros.lReadOnlyFalta    = false;  // bloqueia somente o campo falta
      oParametros.lPrecisaConversao = false;  // quando nota for externa e possuir forma de avaliacao diferente

      /**
       * Bloqueamos o lancamento de avaliacoes quando tipo de frequencia for 'A' ou 'F'
       */
      if (oAluno.oDisciplina.sFrequenciaGlobal == 'F') {
        oParametros.lReadOnlyNota = true;
      }

      /**
       * Bloqueamos o lancamento de faltas quando tipo de frequencia for 'A'
       */
      if (oAluno.oDisciplina.sFrequenciaGlobal == 'A') {
        oParametros.lReadOnlyFalta = true;
      }

      /**
       * Nos Casos onde o aluno esta amparado, com avaliação encerrada, a avaliacão esta marcada como externa
       * ou quando a avaliação for um resultado, bloqueamos os campos e removemos as funções de callback
       */
      if (oAproveitamento.lAmparado || oAproveitamento.lEncerrado ||sSituacaoAluno != "MATRICULADO" ||
          (oAproveitamento.lAvaliacaoExterna) ||
          oAproveitamento.sTipoAvaliacao == 'R') {

        oParametros.lEncerrado     = true;
        oParametros.lReadOnlyNota  = true;
        oParametros.lReadOnlyFalta = true;
      }

      if (oAproveitamento.lAvaliacaoExterna) {
        oParametros.lAvaliacaoExterna = true;
      }

      if (oAproveitamento.lAvaliacaoExterna &&
          oAproveitamento.iFormaAvaliacao != oAproveitamento.oAvaliacaoOrigem.iFormaAvaliacao) {
        oParametros.lPrecisaConversao = true;
      }

      if (!oAproveitamento.lEncerrado && oAproveitamento.nNota != "") {
        oSelf.lOrigemNota = true;
      }

      if (oAproveitamento.lAvaliacaoExterna && (oAproveitamento.oAvaliacaoOrigem.iFormaAvaliacao != '') &&
          (oAproveitamento.iFormaAvaliacao != oAproveitamento.oAvaliacaoOrigem.iFormaAvaliacao)) {

        oSelf.lConversao = true;
      }

      if (oAproveitamento.lNotaBloqueada) {
        oParametros.lReadOnlyNota  = true;
      }

      if ( !oAproveitamento.lPeriodoControlaFrequencia ) {
        oParametros.lReadOnlyFalta = true;
      }

      /**
       * caso seja um aluno com proporcionalidade, e o período não estaja configurado para o calculo do Resultado Final
       */
      if ( oAproveitamento.lBloqueiaPeriodo ) {

        oParametros.lReadOnlyFalta = true;
        oParametros.lReadOnlyNota  = true;
      }

      oParametros.lAprovacaoAutomatica = oRetorno.lAprovacaoAutomatica;

      var oCampoAvaliacao = new Object();
      switch(oAproveitamento.sTipoFormaAvaliacao.urlDecode()) {

        case 'NIVEL':

          oCampoAvaliacao = oSelf.constroiSelectNivel(oAluno, oAproveitamento, oParametros);
          break;

        case 'NOTA':

          oCampoAvaliacao = oSelf.constroiInputNota(oAluno, oAproveitamento, oParametros);
          break;

        case 'PARECER':

          oCampoAvaliacao = oSelf.constroiInputParecer(oAluno, oAproveitamento, oParametros);
          break;
      }

      oCampoFalta = oSelf.constroiInputFalta(oAluno, oAproveitamento, oParametros);

      aLinha.push(oCampoAvaliacao.outerHTML);
      aLinha.push(oCampoFalta.outerHTML);
    });

    var oResultadoFinal = oAluno.oDisciplina.oResultadoFinal;
    var sDescricao      = '';

    if (sSituacaoAluno != "MATRICULADO" && sSituacaoAluno != 'REMATRICULADO') {

      sDescricao                      = sSituacaoAbreviada;
      oResultadoFinal.nValor          = '-';
      oResultadoFinal.sResultadoFinal = '';
    }
    aLinha.push("<div style='text-align:center;'>"+oResultadoFinal.nValor+"</div>");
    if (oResultadoFinal.sResultadoFinal != '') {

      oSelf.aTermos.each(function (oTermo) {

        if (oTermo.sReferencia == oResultadoFinal.sResultadoFinal) {

          sDescricao = oTermo.sSigla;

          /**
           * Só pode deixar limpar o resultado final se aluno não aprovado pelo conselho
           * No momento em que um aluno foi aprovado pelo conselho, ele sempre estará aprovado
           */
          if (    oResultadoFinal.iAprovadoPeloConselho == 0
               && (oResultadoFinal.nValor == '' && oAluno.oDisciplina.sFrequenciaGlobal != 'F')
               && oAluno.oDisciplina.lCaracterReprobatorio
             ) {
            sDescricao = '';
          }

          throw $break;
        }

      });

      if (oResultadoFinal.sResultadoFinal == 'REC') {
        sDescricao = oResultadoFinal.sResultadoFinal;
      }
    }

    if( oAluno.oDisciplina.lProgressaoParcial && !oAluno.lProgressaoParcialAnterior ) {
      sDescricao = 'AP/DP';
    }

    if (!sDescricao) {
      sDescricao = '';
    }

    /**
     * Estilizamos o resultado final do aluno conforme a forma de aprovação do mesmo.
     */
    var oDivResultadoFinal = document.createElement('div');
    oDivResultadoFinal.addClassName('resultadoFinalPadrao');

    var sClasse = 'resultadoFinalPadrao';

    switch (oResultadoFinal.iAprovadoPeloConselho) {

      case 1:

        oDivResultadoFinal.removeClassName('resultadoFinalPadrao');
        oDivResultadoFinal.addClassName('resultadoFinalAprovadoConselho');
        sClasse = 'resultadoFinalAprovadoConselho';

        break;

      case 2:

        oDivResultadoFinal.removeClassName('resultadoFinalPadrao');
        oDivResultadoFinal.addClassName('resultadoFinalReclassificadoBaixaFrequencia');
        sClasse = 'resultadoFinalReclassificadoBaixaFrequencia';

        break;

      case 3:

        oDivResultadoFinal.removeClassName('resultadoFinalPadrao');
        oDivResultadoFinal.addClassName('resultadoFinalConformeRegimentoEscolar');
        sClasse = 'resultadoFinalConformeRegimentoEscolar';

        break;
    }

    if( sSituacaoReal != 'MATRICULADO' && sSituacaoReal != 'REMATRICULADO' ) {

      oDivResultadoFinal.removeClassName( sClasse );
      oDivResultadoFinal.setStyle( { 'textAlign' : 'center' } );
    }

    oDivResultadoFinal.update(sDescricao.toUpperCase());

    if (sDescricao != '') {
      oSelf.lAlteraResultadoFinal = true;
    }

    aLinha.push(oDivResultadoFinal.outerHTML);
    oSelf.oGridAproveitamento.addRow(aLinha);

    if( sSituacaoReal != 'MATRICULADO' && sSituacaoReal != 'REMATRICULADO' ) {
      oSelf.oGridAproveitamento.aRows[iLinhaGrid].setClassName( 'disabled' );
    }

    iLinhaGrid ++
  });

  this.oGridAproveitamento.renderRows();

  /**
   * Percorremos novamente a estrutura para setar as funções e os hints nas colunas
   */
  iLinhaGrid = 0;

  oRetorno.aAlunos.each(function (oAluno) {

    if ($F('exibirTrocaTurma') == 2 && oAluno.sSituacaoAluno.urlDecode() == 'TROCA DE TURMA' ) {
      return;
    }

    $(oSelf.oGridAproveitamento.aRows[iLinhaGrid].sId).onmouseover = function () {
      oSelf.sinalizarLinhaGrid(this, true);
    };
    $(oSelf.oGridAproveitamento.aRows[iLinhaGrid].sId).onmouseout = function () {
      oSelf.sinalizarLinhaGrid(this, false);
    };
    $(oSelf.oGridAproveitamento.aRows[iLinhaGrid].aCells[1].sId).observe('dblclick', function() {
      oSelf.gradeAvalicaoAluno(oAluno.iMatricula);
    });

    oAluno.oDisciplina.aAproveitamentos.each(function (oAproveitamento) {

      oSelf.setaFuncoesNota(oAluno, oAproveitamento, iLinhaGrid);
      oSelf.setaFuncoesFalta(oAluno, oAproveitamento, iLinhaGrid);
    });

    oAluno.oDisciplina.aAproveitamentos.each(function (oAproveitamento) {
      oSelf.ajustaDependenciaDoPeriodo(oAproveitamento.iPeriodo, oAluno, oAproveitamento);
    });

    if ( oAluno.oDisciplina.oResultadoFinal.iAprovadoPeloConselho == 1 &&
         [2,3].in_array(oAluno.oDisciplina.oResultadoFinal.iAlterarNotaFinal) ) {

      /**
       * Calcula a quantidade de colunas totais da grid verificando o tamanho das colunas de aproveitamento e somando
       * as 6 colunas fixas da grid.
       */
      var iColunasAvaliacoes = (oAluno.oDisciplina.aAproveitamentos.length * 2) + 7;
      var sHintConselho      = "Nota do Conselho de Classe: " + oAluno.oDisciplina.oResultadoFinal.sAvaliacaoConselho;
      var oParametros        = {iWidth:'225', oPosition : {sVertical : 'T', sHorizontal : 'R'}};
      oSelf.oGridAproveitamento.setHint(iLinhaGrid, iColunasAvaliacoes, sHintConselho, oParametros);
    }

    if( oAluno.lProgressaoParcialAnterior ) {

      var sHintProgressao       = 'Disciplina(s) em progressão:';
      oAluno.aDisciplinasProgressao.each(function( sDisciplina ) {
        sHintProgressao += "<br>- " + sDisciplina.urlDecode();
      });

      var oParametrosProgressao = {iWidth:'250', oPosition : {sVertical : 'T', sHorizontal : 'R'}};
      oSelf.oGridAproveitamento.setHint( iLinhaGrid, 1, sHintProgressao, oParametrosProgressao );
    }

    iLinhaGrid ++;

    var sMensagemControleFrequencia = '';

    switch( oAluno.oDisciplina.sFrequenciaGlobal ) {

      case 'I':
      case 'FA':

        sMensagemControleFrequencia = 'Disciplina controla <b>FREQUÊNCIA</b> e <b>AVALIAÇÃO</b>';
        break;

      case 'F':

        sMensagemControleFrequencia = 'Disciplina controla somente <b>FREQUÊNCIA</b>';
        break;

      case 'A':

        sMensagemControleFrequencia = 'Disciplina controla somente <b>AVALIAÇÃO</b>';
        break;
    }

    $('legendaControleFrequencia').innerHTML = sMensagemControleFrequencia;
  });

  oSelf.oTabIndex = new DBViewAvaliacao.TabIndexNotaAluno(oSelf.aPeriodosAvaliacao, oSelf.iTabIndex);

  setTimeout(function () {
      oSelf.oTabIndex.reordenarTabIndex();
    },10
  );

  setTimeout(function () {

      if (oSelf.oTabIndex.oPrimeiroElemento != null) {
        oSelf.oTabIndex.oPrimeiroElemento.focus();
      }
    },10
  );

  oSelf.liberarBotoes();
};

/**
 * Retorna um elemento HTML imput para o campo falta
 *
 * @param oAluno
 * @param oAproveitamento
 * @param oParametros
 * @returns Element
 */
DBViewLancamentoAvaliacaoTurma.prototype.constroiInputFalta = function (oAluno, oAproveitamento, oParametros) {

  var oFalta       = document.createElement( 'input' );
  oFalta.type      = 'text';
  oFalta.maxLength = 3;
  oFalta.name      = 'falta#'+oAproveitamento.iPeriodo+'#'+oAluno.iMatricula;
  oFalta.id        = 'falta#'+oAproveitamento.iPeriodo+'#'+oAluno.iMatricula;
  oFalta.setAttribute('rel',"ignore-css");

  var lMatriculaValida = this.isMatriculaValida(oAluno.sSituacaoReal.urlDecode());
  if( !lMatriculaValida ) {

    var oFalta      = document.createElement( 'span' );
        oFalta.name = 'falta#'+oAproveitamento.iPeriodo+'#'+oAluno.iMatricula;
        oFalta.id   = 'falta#'+oAproveitamento.iPeriodo+'#'+oAluno.iMatricula;
        oFalta.setAttribute( 'readonly', 'readonly' );
        oFalta.setStyle( { 'backgroundColor' : '#F7F5F1', 'color' : '#BCB1A2' } );
  }

  oFalta.addClassName(oAproveitamento.iPeriodo+'_falta');
  oFalta.addClassName('tamanhoElemento');
  oFalta.addClassName('alignRight');
  oFalta.setAttribute('value', oAproveitamento.iFalta);
  oFalta.setAttribute('onkeypress', 'return js_mask(event, "0-9")');

  if (oAproveitamento.iFalta != '') {
    this.lAbonar = true;
  }

  if ( oParametros.lReadOnlyFalta && ( lMatriculaValida ) ) {

    oFalta.setAttribute('readonly', 'readonly');
    oFalta.addClassName("readonly");
  }


  if (oAproveitamento.lFaltasAbonadas) {
    oFalta.addClassName('faltasAbonadas');
  }

  return oFalta;
};

/**
 * Constroi um input text para informar a avaliação
 * Retorna um elemento HTML imput para o campo nota
 *
 * @param {Object} oAluno
 * @param {Object} oAproveitamento
 * @param {Object} oParametros
 * @return {Element}
 */
DBViewLancamentoAvaliacaoTurma.prototype.constroiInputNota = function (oAluno, oAproveitamento, oParametros) {

  var oNota             = document.createElement( 'input' );
  oNota.type            = 'text';
  oNota.name            = 'nota#'+oAproveitamento.iPeriodo+'#'+oAluno.iMatricula;
  oNota.id              = 'nota#'+oAproveitamento.iPeriodo+'#'+oAluno.iMatricula;

  var lMatriculaValida = this.isMatriculaValida(oAluno.sSituacaoReal.urlDecode());
  if( !lMatriculaValida ) {

    var oNota      = document.createElement( 'span' );
        oNota.name = 'nota#'+oAproveitamento.iPeriodo+'#'+oAluno.iMatricula;
        oNota.id   = 'nota#'+oAproveitamento.iPeriodo+'#'+oAluno.iMatricula;
        oNota.setAttribute( 'readonly', 'readonly' );
        oNota.setStyle( { 'backgroundColor' : '#F7F5F1', 'color' : '#BCB1A2' } );
  }

  oNota.addClassName(oAproveitamento.iPeriodo+'_nota');
  oNota.addClassName('tamanhoElemento');
  oNota.addClassName('alignRight');

  oNota.setAttribute('rel',"ignore-css");
  oNota.setAttribute('value', oAproveitamento.nNota.urlDecode());
  oNota.setAttribute('mascara', this.sMascara);

  if (!oAproveitamento.lMinimoAtingido && oAproveitamento.nNota.urlDecode() != '') {
    oNota.addClassName('bold');
  }

  /**
   * Validamos se esta amparado
   */
  if (oAproveitamento.lAmparado) {
    oNota.setAttribute('value', 'AMP');
  }

  if (    oParametros.lReadOnlyNota
       && oAproveitamento.sFormaObtencao != 'AT'
       && ( lMatriculaValida )
     ) {

    oNota.setAttribute('readonly', 'readonly');
    oNota.addClassName("readonly");
  }

  if (oAproveitamento.lAvaliacaoExterna) {
    oNota.addClassName('avaliacaoExterna');
  }

  return oNota;
};

/**
 * Constroi um combo com os niveis de avaliacao (conceito) para o período
 * Retorna um elemento select ou um input quando amparado
 *
 * @param {Object} oAluno
 * @param {Object} oAproveitamento
 * @param {Object} oParametros
 * @returns {Element}
 */
DBViewLancamentoAvaliacaoTurma.prototype.constroiSelectNivel = function(oAluno, oAproveitamento, oParametros) {

  var sIdElemento = 'nota#'+oAproveitamento.iPeriodo+'#'+oAluno.iMatricula;

  var oConceito = document.createElement('select');
  oConceito.id  = sIdElemento;

  oConceito.addClassName(oAproveitamento.iPeriodo+'_nota');
  oConceito.addClassName('tamanhoElemento');
  oConceito.addClassName('alignLeft');
  oConceito.setAttribute('rel',"ignore-css");

  var sOptions      = '';
  var oOptionVazio  = document.createElement('option');
  sOptions         += oOptionVazio.outerHTML;
  oAproveitamento.aConceito.each(function (oConceito) {

    var oOption = document.createElement('option');
    oOption.setAttribute('ordem', oConceito.iOrdem);
    oOption.setAttribute('value', oConceito.sConceito);
    oOption.innerHTML = oConceito.sConceito;

    if (oAproveitamento.nNota !='' && oAproveitamento.nNota == oConceito.sConceito) {
      oOption.setAttribute('selected', 'selected');
    }

    sOptions += oOption.outerHTML;
  });
  oConceito.innerHTML = sOptions;

  if ((((oAproveitamento.lAmparado || oParametros.lReadOnlyNota) && (oAproveitamento.sFormaObtencao != 'AT'))
       || oAproveitamento.lEncerrado) || oAluno.oDisciplina.sFrequenciaGlobal == 'F') {


    var oConceito  = document.createElement('input');
    oConceito.type = 'text';
    oConceito.id   = sIdElemento;
    oConceito.addClassName('tamanhoElemento');
    oConceito.addClassName('alignCenter');
    oConceito.setAttribute('value', oAproveitamento.nNota.urlDecode());
    oConceito.setAttribute('readonly', 'true');

  }

  var lMatriculaValida = this.isMatriculaValida(oAluno.sSituacaoReal.urlDecode());
  if ( !lMatriculaValida ) {

    var oConceito  = document.createElement('span');
    oConceito.id   = sIdElemento;
    oConceito.addClassName('tamanhoElemento');
    oConceito.addClassName('alignCenter');
    oConceito.setStyle( { 'backgroundColor' : '#F7F5F1', 'color' : '#BCB1A2' } );
    oConceito.innerHTML = oAproveitamento.nNota.urlDecode();
  }

  /**
   * Quando aluno esta amparado, não devemos apresentar os conceitos e sim uma string 'AMP'
   */


  if (oAproveitamento.lAmparado) {

    oConceito.setAttribute('value', 'AMP');
  }

  if (
       (
           ((oParametros.lReadOnlyNota && (oAproveitamento.sFormaObtencao != 'AT'))
         || oAproveitamento.lEncerrado)
         || oAluno.oDisciplina.sFrequenciaGlobal == 'F'
       )
       && ( lMatriculaValida )
     ) {

    oConceito.addClassName('readonly');
  }

  if(oParametros.lAvaliacaoExterna) {

    oConceito.removeClassName('readonly');
    oConceito.addClassName('avaliacaoExterna');
  }

  return oConceito;
};

/**
 * Constroi um input text para informar o Parecer
 * Retorna um elemento HTML imput
 *
 * @param {Object} oAluno
 * @param {Object} oAproveitamento
 * @param {Object} oParametros
 * @returns {Element}
 */
DBViewLancamentoAvaliacaoTurma.prototype.constroiInputParecer = function(oAluno, oAproveitamento, oParametros) {

  oSelf = this;

  var oParecerInput      = document.createElement('input');
  oParecerInput.type     = 'text';
  oParecerInput.name     = 'nota#'+oAproveitamento.iPeriodo+'#'+oAluno.iMatricula;
  oParecerInput.id       = 'nota#'+oAproveitamento.iPeriodo+'#'+oAluno.iMatricula;
  oParecerInput.readOnly = true;
  oParecerInput.setAttribute('rel',"ignore-css");

  oParecerInput.addClassName('readonly');
  oParecerInput.setAttribute('value', oAproveitamento.nNota.urlDecode());
  var lMatriculaValida = this.isMatriculaValida(oAluno.sSituacaoReal.urlDecode());

  if( !lMatriculaValida ) {

    var oParecerInput      = document.createElement( 'span' );
        oParecerInput.name = 'nota#'+oAproveitamento.iPeriodo+'#'+oAluno.iMatricula;
        oParecerInput.id   = 'nota#'+oAproveitamento.iPeriodo+'#'+oAluno.iMatricula;
        oParecerInput.setStyle( { 'backgroundColor' : '#F7F5F1', 'color' : '#BCB1A2' } );
  }

  oParecerInput.addClassName('tamanhoElemento');
  oParecerInput.addClassName('alignRight');

  var oParecerCombo = '';

  if (oAproveitamento.sTipoAvaliacao == 'R' && lMatriculaValida) {

    oParecerInput.removeClassName('tamanhoElemento');
    oParecerInput.addClassName('fiftyPercentWidth');

    oParecerCombo      = document.createElement('select');
    oParecerCombo.name = 'parecerFinal#'+oAproveitamento.iPeriodo+'#'+oAluno.iMatricula;
    oParecerCombo.id   = 'parecerFinal#'+oAproveitamento.iPeriodo+'#'+oAluno.iMatricula;
    oParecerCombo.addClassName('noMarginPadding');
    oParecerCombo.addClassName('fiftyPercentWidth');
    oParecerCombo.setAttribute('rel',"ignore-css");

    if (oAproveitamento.lEncerrado) {
      oParecerCombo.setAttribute('disabled', 'disabled');
    }

    var sOptions = '';
    oSelf.aTermos.each(function (oTermo) {

      if (oTermo.sReferencia == 'P') {
        return;
      }

      var oOption = document.createElement('option');
      oOption.setAttribute('value', oTermo.sReferencia);
      oOption.innerHTML = oTermo.sDescricao.urlDecode();

      if ( oTermo.sReferencia == 'A' && oParametros.lAprovacaoAutomatica ) {
        oOption.setAttribute('selected', 'selected');
      } else if (oAproveitamento.lMinimoAtingido && oTermo.sReferencia == 'A' && !oParametros.lAprovacaoAutomatica ) {
        oOption.setAttribute('selected', 'selected');
      } else if (!oAproveitamento.lMinimoAtingido && oTermo.sReferencia == 'R' && !oParametros.lAprovacaoAutomatica ) {
        oOption.setAttribute('selected', 'selected');
      }

      sOptions += oOption.outerHTML;
    });
    var oPeriodoDependente = oSelf.getPeriodoDependenteDoPeriodo(oAproveitamento.iPeriodo);
    if (oPeriodoDependente != "") {

      var oOptionRecuperacao = document.createElement('option');
      oOptionRecuperacao.setAttribute('value','rec');
      oOptionRecuperacao.innerHTML = 'EM RECUPERAÇÃO';
      if (oAproveitamento.lRecuperacao) {
        oOptionRecuperacao.setAttribute('selected','selected');
      }
      sOptions += oOptionRecuperacao.outerHTML;
    }
    oParecerCombo.innerHTML = sOptions;

    if ( oParametros.lAprovacaoAutomatica ) {
      oParecerCombo.disabled = true;
    }
  }

  var sCampos = oParecerInput.outerHTML;
  if (oParecerCombo != '') {
    sCampos += oParecerCombo.outerHTML;
  }

  var oDiv           = document.createElement('div');
  oDiv.style.display = 'block';
  oDiv.innerHTML     = sCampos;
  oDiv.addClassName('noMarginPadding');
  oDiv.addClassName('tamanhoElemento');
  oDiv.style.height ='100%';

  return oDiv;
};

/**
 * Trata o valor digitado pelo usuário no campo nota e grava valor na sessão
 *
 * @param {Object} oAluno
 * @param {Object} oAproveitamento
 * @param {Element} oElement
 * @returns {boolean}
 */
DBViewLancamentoAvaliacaoTurma.prototype.preencheNotaDisciplina = function(oAluno, oAproveitamento, oElement) {

  var iMenorValor      = oAproveitamento.nMenorValor;
  var iMaiorValor      = oAproveitamento.nMaiorValor;
  var mMinimoAprovacao = oAproveitamento.mAproveitamentoMinino;
  var nVariacao        = oAproveitamento.nVariacao;
  var lAtingiuMinimo   = true;

  if (this.aPeriodosAvaliacao[oAproveitamento.iPeriodo].sFormaAvaliacao == 'NOTA') {

    var oValorNota = new Number(oElement.value);

    if (oValorNota.valueOf() != '' && (oValorNota.valueOf() < iMenorValor || oValorNota.valueOf() > iMaiorValor)) {

      alert('Nota deve ser entre '+iMenorValor+' e '+iMaiorValor+'!');
      oElement.value = '';
      return false;
    }

    if (!DBViewAvaliacao.ValidacaoVariacaoNota(oValorNota.valueOf(), nVariacao, this.sMascara)) {

      alert('Intervalo de nota deve ser de '+nVariacao);
      oElement.value = '';
      return false;
    }
    oElement.removeClassName('bold');
    if (oValorNota.valueOf() < mMinimoAprovacao) {

      lAtingiuMinimo = false;
      oElement.addClassName('bold');
    }
  }

  this.lGradeAlterada = true;
  $('btnSalvar').removeAttribute('disabled');

  if (oAproveitamento.sTipoFormaAvaliacao.urlDecode() != "PARECER") {
    this.calcularResultados(oAluno, oAproveitamento, oElement, lAtingiuMinimo);
  }

  return true;
};

/**
 * Adiciona em tempo de execução as funções nos campos para preenchimento da nota/avaliação
 *
 * @param {Object} oAluno
 * @param {Object} oAproveitamento
 * @param {integer} iSequencia utilizado para o HINT
 * @return null
 */
DBViewLancamentoAvaliacaoTurma.prototype.setaFuncoesNota = function (oAluno, oAproveitamento, iSequencia) {

  var oSelf               = this;
  var lFuncaoPreencheNota = true;

  if (oAluno.oDisciplina.sFrequenciaGlobal == 'F') {
    lFuncaoPreencheNota  = false;
  }
  if (oAproveitamento.lAmparado || oAproveitamento.lEncerrado ||
      oAproveitamento.lAvaliacaoExterna || oAproveitamento.sTipoAvaliacao == 'R') {
    lFuncaoPreencheNota  = false;
  }

  /**
   * Quando é resultado final ATRIBUIDO, se avaliação ainda não esta encerrada, devemos setar funções na NOTA
   */
  if ((oAproveitamento.sTipoAvaliacao == 'R' && oAproveitamento.sFormaObtencao == 'AT') && !oAproveitamento.lEncerrado) {
    lFuncaoPreencheNota  = true;
  }

  var lMatriculaValida = this.isMatriculaValida(oAluno.sSituacaoAluno.urlDecode());
  var oNota            = $('nota#'+oAproveitamento.iPeriodo+'#'+oAluno.iMatricula);

  oNota.observe("focus", function() {
    oSelf.getInformacoesPeriodo(oAproveitamento.iPeriodo);
  });

  switch (oAproveitamento.sTipoFormaAvaliacao.urlDecode()) {

    case 'PARECER':

      oSelf.getInformacoesPeriodo(oAproveitamento.iPeriodo);
      if (oAproveitamento.sTipoAvaliacao == 'R' && !oAproveitamento.lAmparado && !oAproveitamento.lEncerrado) {
        lFuncaoPreencheNota = true;
      }

      if (lFuncaoPreencheNota && lMatriculaValida) {

        oNota.onclick = function () {
          oSelf.abrirParecer(oAproveitamento, oAluno.iMatricula, oNota.id);
        };
        oNota.onchange = function () {
          oSelf.preencheNotaDisciplina(oAluno, oAproveitamento, this);
        };
      }

      if (oAproveitamento.sTipoAvaliacao == 'R' && lMatriculaValida) {

        var oParecerFinal = $('parecerFinal#'+oAproveitamento.iPeriodo+'#'+oAluno.iMatricula);

        oParecerFinal.onchange = function () {
          oSelf.salvarResultadoFinalParecer(oAproveitamento.iPeriodo,
                                            oAluno.iMatricula,
                                            oAproveitamento.lMinimoAtingido, this);
        };

      }
      break;

    case 'NOTA' :

      if (!oAproveitamento.lAmparado) {
        js_observeMascaraNota(oNota, this.sMascara);
      }
      if (lFuncaoPreencheNota  && lMatriculaValida) {

        oNota.onchange = function () {
          oSelf.preencheNotaDisciplina(oAluno, oAproveitamento, this);
        };
      }

      break;

    default:

      if (lFuncaoPreencheNota  && lMatriculaValida) {

        oNota.onchange = function () {
          oSelf.preencheNotaDisciplina(oAluno, oAproveitamento, this);
        };
      }
      break;
  }

  var lSetaHint    = false;
  var sStringHint  = '';
  var iTamanhoHint = 450;

  if (oAproveitamento.lAvaliacaoExterna) {

    lSetaHint    = true;
    sStringHint  = "<b>Escola:</b> " + oAproveitamento.sEscola.urlDecode();
    sStringHint += "<br><b>Municipio:</b> " + oAproveitamento.sMunicipio.urlDecode();
    sStringHint += "<br><b>Tipo:</b>  " + oAproveitamento.sTipoEscola.urlDecode();
  }

  if (oAproveitamento.lAmparado) {

    lSetaHint    = true;
    sStringHint  = "<b>Amparado</b> ";
    iTamanhoHint = 90;
  }

  if ( oAproveitamento.lPeriodoComAvaliacaoAlternativaSemAvaliacao ) {

    lSetaHint    = true;
    iTamanhoHint = 200;
    sStringHint  = 'Avaliação Alternativa Configurada';
  }


  if (lSetaHint) {

    var oDBHintNota = eval("oDBHintNota_"+iSequencia+"_"+oAproveitamento.iPeriodo+"_"+oAluno.iMatricula+" = new DBHint('oDBHintNota_"+iSequencia+"_"+oAproveitamento.iPeriodo+"_"+oAluno.iMatricula+"')");

    oDBHintNota.setWidth(iTamanhoHint);
    oDBHintNota.setText(sStringHint);
    oDBHintNota.setShowEvents(["onmouseover"]);
    oDBHintNota.setHideEvents(["onmouseout"]);
    oDBHintNota.setPosition('B', 'L');
    /**
     * colocamos como elemento Scroll a div que contem o corpo da grid.
     */
    oDBHintNota.setScrollElement($('oGridAproveitamentobody').parentNode);
    oDBHintNota.make(oNota);
  }
};

/**
 * Adiciona em tempo de execução as funções nos campos para preenchimento da falta
 *
 * @param {Object} oAluno
 * @param {Object} oAproveitamento
 * @param {integer} iSequencia utilizado para o HINT (se for utilizar)
 * @return null
 */
DBViewLancamentoAvaliacaoTurma.prototype.setaFuncoesFalta = function (oAluno, oAproveitamento, iSequencia) {

  var oSelf                = this;
  var lFuncaoPreencheFalta = true;

  if (oAluno.oDisciplina.sFrequenciaGlobal == 'A') {
    lFuncaoPreencheFalta = false;
  }
  if (oAproveitamento.lAmparado || oAproveitamento.lEncerrado ||
      oAproveitamento.lAvaliacaoExterna || oAproveitamento.sTipoAvaliacao == 'R') {
    lFuncaoPreencheFalta = false;
  }

  var oFalta = $('falta#'+oAproveitamento.iPeriodo+'#'+oAluno.iMatricula);

  if (lFuncaoPreencheFalta) {

    oFalta.onchange = function () {

      if (oSelf.validaAulasDoPeriodo(oAproveitamento.iAulasPerido, this, oAproveitamento.iFaltasAbonadas )) {
        oSelf.preencheFaltaDisciplina(oAproveitamento.iPeriodo, oAluno.iMatricula, this);
      }
    };
  }
};

/**
 * Valida as faltas lançadas no período
 *
 * @param {integer} iAulasPeriodo
 * @param {Object}  oElemento
 * @param {integer} iFaltasAbonadas
 * @return boolean
 */
DBViewLancamentoAvaliacaoTurma.prototype.validaAulasDoPeriodo = function (iAulasPeriodo, oElemento, iFaltasAbonadas) {

  var oFaltas = new Number(oElemento.value);

  var lLimparCampoFalta = true;
  var lLancamentoValido = true;
  var sMensagem         = '';
  iAulasPeriodo         = new Number(iAulasPeriodo);

  if (iAulasPeriodo.valueOf() == 0) {

    lLancamentoValido = false;
    sMensagem         = 'Antes de lançar as faltas de um aluno, é necessário informar o número de aulas dadas.';
  }

  if (oFaltas.valueOf() > iAulasPeriodo.valueOf() && lLancamentoValido) {

    lLancamentoValido = false;
    sMensagem         = 'Número de faltas é maior que o número de aulas para o período: '+ iAulasPeriodo.valueOf();
  }

  if (oFaltas.valueOf() < iFaltasAbonadas && lLancamentoValido) {

    lLancamentoValido  = false;
    lLimparCampoFalta  = false;
    sMensagem          = 'Número de faltas é menor que o número de aulas abonadas ('+iFaltasAbonadas+') ';
    sMensagem         += 'lançadas para o período.';
    oElemento.value    = iFaltasAbonadas;
  }

  if (!lLancamentoValido) {

    alert(sMensagem);
    if (lLimparCampoFalta) {
      oElemento.value = '';
    }
    setTimeout(function () {
                 oElemento.focus();
               },10
              );
    return false;
  }
  return true;
};

/**
 * Salva as faltas do aluno na sessão
 *
 * @param {integer} iAulasPeriodo
 * @param {integer} iMatricula
 * @param {Object}  oElemento
 * @return null
 */
DBViewLancamentoAvaliacaoTurma.prototype.preencheFaltaDisciplina = function (iPeriodo, iMatricula, oElement) {

  this.lGradeAlterada    = true;
  var oSelf              = this;
  var oParametros        = new Object();
  oParametros.exec       = 'setFalta';
  oParametros.iMatricula = iMatricula;
  oParametros.iRegencia  = $F('disciplina');
  oParametros.iPeriodo   = iPeriodo;
  oParametros.iFalta     = oElement.value;

  var oJson          = new Object();
  oJson.method       = 'post';
  oJson.parameters   = 'json='+Object.toJSON(oParametros);
  oJson.onComplete   = function () {};

  new Ajax.Request(oSelf.sRpcPadrao, oJson);
  $('btnSalvar').removeAttribute('disabled');
};

/**
 * Adiciona/remove um destaque na linha da grid quando o Mouse passa por cima da linha
 *
 * @param {Element} oObjeto tr que será destacada
 * @param {boolean} lPintar se é para destacar ou remover o destaque
 * @return null
 */
DBViewLancamentoAvaliacaoTurma.prototype.sinalizarLinhaGrid = function (oObjeto, lPintar) {

  if (oObjeto.nodeName == 'TR') {
    oLinha = oObjeto;
  }

  if (oObjeto.nodeName == 'INPUT') {
    oLinha = oObjeto.parentNode.parentNode;
  }

  var sCor      = 'white';
  var sCorFonte = 'black';

  if( oObjeto.hasClassName( 'disabled' ) ) {

    sCor      = '#F7F5F1';
    sCorFonte = '#BCB1A2';
  }

  if (lPintar) {
    sCor       = 'rgb(240, 240, 240)';
  }

  oLinha.style.backgroundColor = sCor;
  oLinha.style.color           = sCorFonte;
};

/**
 * Salvamos do resultado definido para o parecer
 * @param {integer} iPeriodo
 * @param {integer} iMatricula
 * @param {boolean} lMinimo
 * @param {Object} oElement
 * @return boolean
 */
DBViewLancamentoAvaliacaoTurma.prototype.salvarResultadoFinalParecer = function (iPeriodo, iMatricula, lMinimo, oElement) {

  this.lGradeAlterada  = true;
  if (oElement.value != '' && oElement.value == 'R') {
    lMinimo = false;
  } else if (oElement.value != '' && oElement.value == 'A') {
    lMinimo = true;
  }
  var lRecuperacao = false;
  if (oElement.value == 'rec') {

    lRecuperacao = true;
    lMinimo = true;
  }
  var oParametro                   = new Object();
  oParametro.exec                  = 'salvarResultadoParecer';
  oParametro.iRegencia             = $F('disciplina');
  oParametro.iPeriodo              = iPeriodo;
  oParametro.iMatricula            = iMatricula;
  oParametro.lAproveitamentoMinimo = lMinimo;
  oParametro.lRecuperacao          = lRecuperacao;

  var oJson          = new Object();
  oJson.method       = 'post';
  oJson.parameters   = 'json='+Object.toJSON(oParametro);
  oJson.onComplete   = function () {
    return true;
  };

  new Ajax.Request(this.sRpcPadrao, oJson);
  $('btnSalvar').removeAttribute('disabled');
};

/**
 * Quando avaliação do período for por PARECER, ao clicar sobre a celula para informar a nota
 * devemos abrir a lockup de parecer.
 *
 * @param {Object} oAproveitamento
 * @param {integer} iMatricula
 * @param {string} sIdAlvo
 * @return null
 */
DBViewLancamentoAvaliacaoTurma.prototype.abrirParecer = function (oAproveitamento, iMatricula, sIdAlvo) {

  if( this.lAbriuWindowParecer ) {
    return;
  }

  this.lAbriuWindowParecer = true;

  var iRegencia = $F('disciplina');
  var iTurma    = this.oDadosTurmaSelecionada.iTurma;
  var iEtapa    = this.oDadosTurmaSelecionada.iEtapa;
  var oSelf     = this;

  var oParametro  = new Object();
  oParametro.exec = 'getParametroTelaParecer';
  oWindowParecer  = '';
  new Ajax.Request('edu4_lancamentoavaliacao.RPC.php',
                   {
                     method:     'post',
                     parameters: 'json='+Object.toJSON(oParametro),
                     onComplete: function (oResponse) {

                       var oRetorno = eval('('+oResponse.responseText+')');
                       if (oRetorno.iParametro == 0) {

                         oWindowParecer = new DBViewLancamentoAvaliacaoParecer('oWindowParecer',
                                                                               iTurma,
                                                                               iRegencia,
                                                                               oSelf.oWindow,
                                                                               oSelf.sRpcPadrao
                                                                              );

                       } else {

                         oWindowParecer = new DBViewLancamentoParecerDisciplina('oWindowParecer',
                                                                                iTurma,
                                                                                iRegencia,
                                                                                oSelf.oWindow,
                                                                                oSelf.sRpcPadrao
                                                                               );
                       }

                       oWindowParecer.setProfessorLogado(oSelf.lProfessorLogado);
                       oWindowParecer.setMatricula(iMatricula);
                       oWindowParecer.setCodigoPeriodo(oAproveitamento.iCodigoPeriodo);
                       oWindowParecer.setOrdem(oAproveitamento.iPeriodo);
                       oWindowParecer.setEtapa(iEtapa);
                       oWindowParecer.show();

                       oWindowParecer.setCallback(function(oDados) {

                         oSelf.lGradeAlterada = true;
                         $(sIdAlvo).value     = $F('parecer');
                         oWindowParecer.getWindow().destroy();

                         $('btnSalvar').removeAttribute('disabled');
                         oSelf.oWindow.toFront();
                         oSelf.lAbriuWindowParecer = false;
                       });

                       $('window' + oWindowParecer.oWindowParecer.idWindow +'_btnclose').onclick = function() {

                         oWindowParecer.oWindowParecer.destroy();
                         oSelf.lAbriuWindowParecer = false;
                       }
                     }
                   }
                  );
};

/**
 * Ao clicar sobre o botão salvar, devemos persistir os dados informados na grade de aproveitamento
 * @returns {DBViewLancamentoAvaliacaoTurma.retornoSalvarGradeAproveitamento}
 */
DBViewLancamentoAvaliacaoTurma.prototype.salvarGradeAproveitamento = function () {

  var oSelf = this;

  if (!this.lGradeAlterada) {
    return false;
  }

  js_divCarregando("Salvando dados, aguarde...", "msgBox");
  var oObject        = new Object();
  oObject.exec       = 'salvaAvaliacaoAluno';
  oObject.iTurma     = oSelf.iTurma;

  var oJson          = new Object();
  oJson.method       = 'post';
  oJson.parameters   = 'json='+Object.toJSON(oObject);
  oJson.onComplete   = function (oAjax) {
    oSelf.retornoSalvarGradeAproveitamento(oAjax);
  };
  oJson.asynchronous = false;

  new Ajax.Request(this.sRpcPadrao, oJson);
};

/**
 * Retorno do salvar grade de aproveitamento
 *
 * @param {oAjax} oAjax
 * @returns null
 */
DBViewLancamentoAvaliacaoTurma.prototype.retornoSalvarGradeAproveitamento = function(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval('('+oAjax.responseText+')');

  this.lGradeAlterada = false;
  $('btnSalvar').setAttribute('disabled', 'disabled');

  alert(oRetorno.message.urlDecode());

  delete this.oGridAproveitamento;
  this.getPeriodosAvaliacao();
  this.buscaTermosTurma();
  this.buscaAlunos();
};

/**
 * Calcula o nota final em tempo de execução ao informar uma avaliacao do tipo NOTA ou NIVEL
 *
 * @param {Object}  oAluno Dados do aluno
 * @param {Object}  oAproveitamento Dados do aproveitamento
 * @param {Element} oElemento Elemento afetado
 * @param {boolean} lAtingiuMinimo true se atingiu o minimo para período
 * @returns {DBViewLancamentoAvaliacaoTurma.retornoCalculo}
 */
DBViewLancamentoAvaliacaoTurma.prototype.calcularResultados = function (oAluno, oAproveitamento, oElemento, lAtingiuMinimo) {

  var oSelf      = this;
  var oAvaliacao = new Object();

  oAvaliacao.iCodigoRegencia             = $F('disciplina');
  oAvaliacao.sFrequenciaGlobal           = oAluno.oDisciplina.sFrequenciaGlobal;
  oAvaliacao.iPeriodo                    = oAproveitamento.iPeriodo;
  oAvaliacao.nNota                       = oElemento.value;
  oAvaliacao.iOrdem                      = oAproveitamento.iOrdem;
  if (oElemento.type == 'select-one') {
    oAvaliacao.iOrdem = oElemento.options[oElemento.selectedIndex].getAttribute('ordem');
  }

  oAvaliacao.lEncerrado                  = oAproveitamento.lEncerrado;
  oAvaliacao.iFalta                      = oAproveitamento.iFalta;
  oAvaliacao.lAproveitamentoMinimo       = lAtingiuMinimo;
  oAvaliacao.lAvaliacaoExterna           = oAproveitamento.lAvaliacaoExterna;
  oAvaliacao.iPeriodoDependenteAprovacao = oSelf.aPeriodosAvaliacao[oAproveitamento.iPeriodo].iPeriodoDependenteAprovacao;


  var oParametros        = new Object();
  oParametros.exec       = 'calcularResultado';
  oParametros.iMatricula = oAluno.iMatricula;
  oParametros.oAvaliacao = oAvaliacao;

  var oJson          = new Object();
  oJson.method       = 'post';
  oJson.parameters   = 'json='+Object.toJSON(oParametros);
  oJson.onComplete   = function (oAjax) {
    oSelf.retornoCalculo(oAjax, oAluno, oAproveitamento, oSelf);
  };
  oJson.asynchronous = false;

  new Ajax.Request(oSelf.sRpcPadrao, oJson);
};

/**
 * Retorna um objeto com os seguintes parâmetros:
 * ({dados:[],
 *   status:1,
 *   message:"",
 *   iCodigoRegencia:"5627",
 *   iCodigoPeriodo:"1",
 *   lMinimoAtingido:false,
 *   aResultados:[{nNota:"5.00",
 *                 iOrdem:"",
 *                 iCodigoRegencia:"5627",
 *                 iPeriodo:"4",
 *                 lMinimoAtingido:false}
 * })
 * @param {Object} oAjax
 * @param {Object} oAluno
 * @param {Object} oAproveitamento
 */
DBViewLancamentoAvaliacaoTurma.prototype.retornoCalculo = function (oAjax, oAluno, oAproveitamento, oSelf) {

  var oRetorno = eval('('+oAjax.responseText+')');

  if (oRetorno.status == 1) {

    oSelf.ajustaDependenciaDoPeriodo(oAproveitamento.iPeriodo, oAluno, oAproveitamento);
    oRetorno.aResultados.each(function(oResultado) {

      var sIdCampoNota = 'nota#'+oResultado.iPeriodo+'#'+oAluno.iMatricula;
      oSelf.ajustaDependenciaDoPeriodo(oResultado.iPeriodo, oAluno, oResultado);
      if ($(sIdCampoNota))  {

        var nNota = oResultado.nNota;
        if ((oAproveitamento.sTipoFormaAvaliacao.urlDecode() == 'NOTA' && oAproveitamento.sFormaObtencao != 'AT') ||
            oAproveitamento.lEncerrado) {

          nNota = js_mascaraNota(nNota, oSelf.sMascara);
          if (oResultado.lMinimoAtingido) {
            $(sIdCampoNota).removeClassName('bold');
          } else {
            $(sIdCampoNota).addClassName('bold');
          }
        }

        if ((oAproveitamento.sTipoFormaAvaliacao.urlDecode() == 'NIVEL' && oAproveitamento.sFormaObtencao != 'AT') ||
             oAproveitamento.lEncerrado) {

          $(sIdCampoNota).setAttribute('disabled', 'disabled');
          $(sIdCampoNota).addClassName('readonly');
        }
        if (nNota == '' && oSelf.lGeraResultadoParcial && oResultado.iPeriodo == oRetorno.iPeriodoMediaParcial) {

          nNota = oRetorno.nMediaParcial;
          $(sIdCampoNota).removeClassName('bold');
        }
        $(sIdCampoNota).value = nNota;
        if (oResultado.lAmparado) {

          $(sIdCampoNota).removeClassName('bold');
          $(sIdCampoNota).value = "AMP";
        }
      }
    });
  }
};

/**
 * Função para liberar as ações dos botões
 * Ids dos botões: btnOrigemNota, btnAbonarFaltas, btnParecer, btnObservacao, btnAmparo, btnAlteraResultadoFinal
 *
 * @returns null
 */
DBViewLancamentoAvaliacaoTurma.prototype.liberarBotoes = function () {

  var oSelf       = this;
  var sDisciplina = $('disciplina').options[$('disciplina').selectedIndex].innerHTML;

  var sMsgConfirm  = 'Grade de aproveitamento foi alterada, para não perder os dados clique em Salvar ou poderá perder ';
      sMsgConfirm += 'todas as modificações realizadas.\nDeseja continuar e perder os dados?';

  if (oSelf.lOrigemNota && !oSelf.lTurmaEncerrada) {

    $('btnOrigemNota').removeAttribute('disabled');
    $('btnOrigemNota').onclick = function() {

      if (oSelf.lGradeAlterada && !confirm(sMsgConfirm)) {
        return false;
      }

      oSelf.oViewOrigemNota = new DBViewOrigemNota(oSelf.oDadosTurmaSelecionada,
                                                   $F('disciplina'),
                                                   sDisciplina,
                                                   oSelf.aPeriodosAvaliacao
                                                  );
      oSelf.oViewOrigemNota.setContainer(oSelf.oWindow);

      if (oSelf.oJanelaOrigemNota) {
        return true;
      }

      oSelf.oViewOrigemNota.setCallBackWindow(function() {

        oSelf.buscaAlunos();
        oSelf.oJanelaOrigemNota = false;
      });

      oSelf.oJanelaOrigemNota = true;
      oSelf.oViewOrigemNota.show();
    };
  }

  if (oSelf.lAbonar && !oSelf.lTurmaEncerrada) {

    $('btnAbonarFaltas').removeAttribute('disabled');
    $('btnAbonarFaltas').onclick = function() {

      if (oSelf.lGradeAlterada && !confirm(sMsgConfirm)) {
        return false;
      }

      oSelf.oViewAbonoFalta = new DBViewAbonoFalta(oSelf.oDadosTurmaSelecionada,
                                                   $F('disciplina'),
                                                   sDisciplina,
                                                   oSelf.aPeriodosAvaliacao
                                                  );
      oSelf.oViewAbonoFalta.setContainer(oSelf.oWindow);

      if (oSelf.oJanelaOrigemNota) {
        return true;
      }

      oSelf.oViewAbonoFalta.setCallBackWindow(function() {

        oSelf.buscaAlunos();
        oSelf.oJanelaOrigemNota = false;
      });

      oSelf.oJanelaOrigemNota = true;
      oSelf.oViewAbonoFalta.show();
    };
  }

  if (oSelf.lConversao && !oSelf.lTurmaEncerrada) {

    $('btnConversao').removeAttribute('disabled');
    $('btnConversao').onclick = function() {

      if (oSelf.lGradeAlterada && !confirm(sMsgConfirm)) {
        return false;
      }

      oSelf.oViewConversao = new DBViewAvaliacao.Conversao(oSelf.oDadosTurmaSelecionada,
                                                           $F('disciplina'),
                                                           sDisciplina,
                                                           oSelf.aPeriodosAvaliacao);
      oSelf.oViewConversao.setContainer(oSelf.oWindow);

      if (oSelf.oJanelaOrigemNota) {
        return true;
      }

      oSelf.oViewConversao.setCallBackWindow(function() {

        oSelf.getPeriodosAvaliacao();
        oSelf.buscaAlunos();
        oSelf.oJanelaOrigemNota = false;
      });

      oSelf.oJanelaOrigemNota = true;
      oSelf.oViewConversao.show();
    };
  }

  if (oSelf.lAlteraResultadoFinal && !oSelf.lTurmaEncerrada) {

    $('btnAlteraResultadoFinal').removeAttribute('disabled');
    $('btnAlteraResultadoFinal').onclick = function() {

      if (oSelf.lGradeAlterada && !confirm(sMsgConfirm)) {
        return false;
      }

      oSelf.oViewAlteraResultadoFinal = new DBViewAvaliacao.AlteraResultadoFinal(oSelf.oDadosTurmaSelecionada,
                                                                                 $F('disciplina'),
                                                                                  sDisciplina);
      oSelf.oViewAlteraResultadoFinal.setContainer(oSelf.oWindow);

      if (oSelf.oJanelaOrigemNota) {
        return true;
      }

      oSelf.oViewAlteraResultadoFinal.setCallBackWindow(function() {

        oSelf.getPeriodosAvaliacao();
        oSelf.buscaAlunos();
        oSelf.oJanelaOrigemNota = false;
      });

      oSelf.oJanelaOrigemNota = true;
      oSelf.oViewAlteraResultadoFinal.show();
    };
  }

  /**
   * Habilita / Desabilita o botão para lançar observação
   */
  if (!oSelf.lTurmaEncerrada) {

    $('btnObservacao').removeAttribute('disabled');
    $('btnObservacao').onclick = function() {

      if (oSelf.lGradeAlterada && !confirm(sMsgConfirm)) {
        return false;
      }

      var oDadosTurma = {iRegencia : $F('disciplina'), iTurma : oSelf.oDadosTurmaSelecionada.iTurma,
                         iEtapa : oSelf.oDadosTurmaSelecionada.iEtapa};

      oSelf.oViewLancamentoObservacao = new DBViewAvaliacao.LancamentoObservacao(oDadosTurma,
                                                                                 {},
                                                                                 true);
      oSelf.oViewLancamentoObservacao.setContainerPai(oSelf.oWindow);

      if (oSelf.oJanelaOrigemNota) {
        return true;
      }

      oSelf.oViewLancamentoObservacao.setCallBackWindow(function() {

        oSelf.getPeriodosAvaliacao();
        oSelf.buscaAlunos();
        oSelf.oJanelaOrigemNota = false;
      });

      oSelf.oJanelaOrigemNota = true;
      oSelf.oViewLancamentoObservacao.show();
    };
  }

  /**
   * Habilita / Desabilita o botão para "Lançar Amparo"
   */
  if (!oSelf.lTurmaEncerrada) {

    $('btnAmparo').removeAttribute('disabled');
    $('btnAmparo').onclick = function() {

      if (oSelf.lGradeAlterada && !confirm(sMsgConfirm)) {
        return false;
      }

      oSelf.oViewLancamentoAmparo = new DBViewAvaliacao.LancamentoAmparo(oSelf.aPeriodosAvaliacao,
                                                                         $F('disciplina'),
                                                                         sDisciplina,
                                                                         oSelf.oDadosTurmaSelecionada.iTurma,
                                                                         oSelf.oDadosTurmaSelecionada.iEtapa);
      oSelf.oViewLancamentoAmparo.setContainerPai(oSelf.oWindow);

      if (oSelf.oJanelaOrigemNota) {
        return true;
      }

      oSelf.oViewLancamentoAmparo.setCallBackWindow(function() {

        oSelf.getPeriodosAvaliacao();
        oSelf.buscaAlunos();
        oSelf.oJanelaOrigemNota = false;
      });

      oSelf.oJanelaOrigemNota = true;
      oSelf.oViewLancamentoAmparo.show();
    };
  }

  $('btnParecer').onclick = function() {
    oSelf.abrirParecerComplementar();
  };

  $('btnLegendas').onclick = function() {

    var fCallBack = function() {
      oSelf.oJanelaOrigemNota = false;
    };

    oSelf.oViewLegendas = new DBViewFormularioEducacao.LegendasLancamentoAvaliacao(
                                                                                    oSelf.oDadosTurmaSelecionada.iTurma,
                                                                                    oSelf.oDadosTurmaSelecionada.iEtapa
                                                                                  );

    oSelf.oViewLegendas.setContainerPai( oSelf.oWindow );
    if (oSelf.oJanelaOrigemNota) {
      return true;
    }

    oSelf.oJanelaOrigemNota = true;
    oSelf.oViewLegendas.setCallBackShutDown( fCallBack );
    oSelf.oViewLegendas.getLegendas();
    oSelf.oViewLegendas.show();
  };

  $('btnProporcionalidade').onclick = function() {

    var fCallBack = function() {

      oSelf.lGradeAlterada = true;
      oSelf.salvarGradeAproveitamento();
      oSelf.oJanelaOrigemNota = false;
    };

    oSelf.oViewProporcionalidade = new DBViewFormularioEducacao.Proporcionalidade(
                                                                                   oSelf.oDadosTurmaSelecionada.iTurma,
                                                                                   oSelf.oDadosTurmaSelecionada.iEtapa
                                                                                 );

    oSelf.oViewProporcionalidade.setContainerPai( oSelf.oWindow );
    if( oSelf.oJanelaOrigemNota ) {
      return true;
    }

    oSelf.oViewProporcionalidade.setCallBackSalvar( fCallBack );
    oSelf.oViewProporcionalidade.show();
  };

  $('btnAvaliacaoAlternativa').onclick = function (){

    if (oSelf.lGradeAlterada && !confirm(sMsgConfirm)) {
      return false;
    }

    var fCallBack = function() {

      oSelf.lGradeAlterada = true;
      oSelf.salvarGradeAproveitamento();
    };

    oSelf.oViewAvaliacaoAlternativa = new DBViewFormularioEducacao.AvaliacaoAlternativa(
                                                                      oSelf.oDadosTurmaSelecionada.iTurma,
                                                                      oSelf.oDadosTurmaSelecionada.iEtapa
                                                                    );

    oSelf.oViewAvaliacaoAlternativa.setContainerPai( oSelf.oWindow );
    if( oSelf.oJanelaOrigemNota ) {
      return true;
    }
    oSelf.oJanelaOrigemNota = true;
    oSelf.oViewAvaliacaoAlternativa.setCallBackSalvar( fCallBack );
    oSelf.oViewAvaliacaoAlternativa.setCallBack(function () {
      oSelf.oJanelaOrigemNota = false;
    })
    oSelf.oViewAvaliacaoAlternativa.show();
  };
};

DBViewLancamentoAvaliacaoTurma.prototype.abrirParecerComplementar = function () {

  if( $('wndParecer') ) {
    return;
  }

  var iRegencia = $F('disciplina');
  var iTurma    = this.oDadosTurmaSelecionada.iTurma;
  var oSelf     = this;

  var oParametro  = new Object();
  oParametro.exec = 'getParametroTelaParecer';
  oWindowParecer  = '';
  new Ajax.Request( 'edu4_lancamentoavaliacao.RPC.php',
                    {
                      method:     'post',
                      parameters: 'json='+Object.toJSON(oParametro),
                      onComplete: function (oResponse) {

                        var oRetorno = eval('('+oResponse.responseText+')');

                        if (oRetorno.iParametro == 0) {

                          oWindowParecer = new DBViewLancamentoAvaliacaoParecer('oWindowParecer',
                                                                               iTurma,
                                                                               iRegencia,
                                                                               oSelf.oWindow
                                                                              );
                        } else {

                          oWindowParecer = new DBViewLancamentoParecerDisciplina('oWindowParecer',
                                                                                iTurma,
                                                                                iRegencia,
                                                                                oSelf.oWindow
                                                                               );
                        }

                        oWindowParecer.disciplinasProcedimentoAvaliacaoParecer( false );
                        oWindowParecer.setEtapa(this.oDadosTurmaSelecionada.iEtapa);
                        oWindowParecer.setProfessorLogado(oSelf.lProfessorLogado);
                        oWindowParecer.mostrarPesquisaAluno(true);
                        oWindowParecer.mostrarPesquisaPeriodo(true);
                        oWindowParecer.show();

                        oWindowParecer.setCallback(function(oDados) {

                          oSelf.getPeriodosAvaliacao();
                          oSelf.buscaAlunos();
                          oWindowParecer.getWindow().destroy();
                          oSelf.oWindow.toFront();
                        });
                      }
                    });
};

/**
 * Realiza o bloqueio/liberacao de periodos onde existe a dependencia do valor de outro periodo
 * @param iPeriodo
 * @param oAluno
 * @param oAproveitamento
 */
DBViewLancamentoAvaliacaoTurma.prototype.ajustaDependenciaDoPeriodo = function(iPeriodo, oAluno, oAproveitamento) {

  var oSelf = this;
  oSelf.aPeriodosAvaliacao.each(function (oPeriodo) {

    if (oPeriodo.iPeriodoDependenteAprovacao == iPeriodo) {

      var oDependencia = oPeriodo;
      var sIdCampoNota = 'nota#'+oDependencia.iOrdemAvaliacao+'#'+oAluno.iMatricula;
      var lReadOnly    = false;
      var sValor       = '';
      var lBloqueada   = false;

      var sTextoRecuperacao = 'dispensado';
      var iLimiteAprovacao  = new Number(oPeriodo.iLimiteReprovacao);
      if (    ( iLimiteAprovacao > 0 && oAproveitamento.iTotalDisciplinasReprovadas > iLimiteAprovacao )
           || ( iLimiteAprovacao > 0 && iLimiteAprovacao != 999 && !oAproveitamento.lRecuperacao && $(sIdCampoNota).value === "" )
         ) {

        lBloqueada        = true;
        sTextoRecuperacao = 'Não Habilitado';
      }

      if (oAproveitamento.lMinimoAtingido || lBloqueada) {

        sValor    = sTextoRecuperacao;
        lReadOnly = true;
      }
      if ($(sIdCampoNota)) {

        if (!oAproveitamento.lMinimoAtingido &&  $(sIdCampoNota).value != '') {
          sValor  = $(sIdCampoNota).value;
        }
        $(sIdCampoNota).value    = sValor;
        $(sIdCampoNota).readOnly = lReadOnly;
        if ($(sIdCampoNota).type == 'select-one') {

          $(sIdCampoNota).disabled = lReadOnly;
          $(sIdCampoNota).title    = sValor;
        }
      }
      throw $break;
    }
  });
};

/**
 * Cria uma instancia da DBViewConsultaAvaliacoesAluno
 * colocado no ondblclick da 2º coluna da grid (nome aluno)
 * @param iMatricula
 */
DBViewLancamentoAvaliacaoTurma.prototype.gradeAvalicaoAluno = function(iMatricula) {

  if ($('wndConsultaAlunoAvaliacaoTurma')) {
    return false;
  }
  var oGradeAluno = new DBViewConsultaAvaliacoesAluno('oGradeAluno', iMatricula);
  oGradeAluno.show();
};

/**
 * Adiciona estilo disabled nos botões especificados
 */
DBViewLancamentoAvaliacaoTurma.prototype.bloqueiaBotoes = function() {

  $('btnOrigemNota').setAttribute('disabled', 'disabled');
  $('btnAbonarFaltas').setAttribute('disabled', 'disabled');
  $('btnConversao').setAttribute('disabled', 'disabled');
  $('btnAlteraResultadoFinal').setAttribute('disabled', 'disabled');

  if( !this.oDadosTurmaSelecionada.sFormaObtencaoResultado == 'SO' || !this.oDadosTurmaSelecionada.lUtilizaProporcionalidade ) {
    $('btnProporcionalidade').setAttribute( 'disabled', 'disabled' );
  }

  if( !this.oDadosTurmaSelecionada.sFormaObtencaoResultado == 'SO'
      || !this.oDadosTurmaSelecionada.lUtilizaAvaliacaoAlternativa
      || !this.oDadosTurmaSelecionada.lEscolaUtilizaAvaliacaoAlternativa
    ) {
    $('btnAvaliacaoAlternativa').setAttribute( 'disabled', 'disabled' );
  }
};

/**
 * Limpa os dados da Sessao, apos sair da rotina
 * @returns {Boolean}
 */
DBViewLancamentoAvaliacaoTurma.prototype.limparSessao = function() {

  var oParam  = new Object();
  oParam.exec = "destroySession";
  new Ajax.Request('edu4_lancamentoavaliacaonota.RPC.php',
                   {
                    asynchronous:false,
                    method: "post",
                    parameters:'json='+Object.toJSON(oParam)
                  });
  return true;
};

DBViewLancamentoAvaliacaoTurma.prototype.getDadosPeriodo = function(iPeriodo) {

  var oPeriodoRetorno = '';

  this.aPeriodosAvaliacao.each(function(oPeriodo) {

    if (oPeriodo.iOrdemAvaliacao) {
      if (oPeriodo.iOrdemAvaliacao == iPeriodo) {

        oPeriodoRetorno = oPeriodo;
        return;
      }
    }
  });

  return oPeriodoRetorno;
};

DBViewLancamentoAvaliacaoTurma.prototype.getInformacoesPeriodo = function(iPeriodo) {

  var oSelf    = this;
  var oPeriodo = oSelf.getDadosPeriodo(iPeriodo);
  var sMensagemFormaAvaliacao = "<b>Tipo de resultado: " +oPeriodo.sFormaAvaliacao.urlDecode()+"</b><br>";

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
      break
  }

  $('legendaFormaAvaliacao').innerHTML = sMensagemFormaAvaliacao;
  $('legendaFormaAvaliacao').title     = sMensagemFormaAvaliacao.replace('<br>', ' - ');
};

/**
 * Retorna o periodo dependente  do período passado como parâmetro.
 * @param iPeriodo
 * @returns {Object}
 */
DBViewLancamentoAvaliacaoTurma.prototype.getPeriodoDependenteDoPeriodo = function(iPeriodo) {

  var oSelf              = this;
  var oPeriodoDependente = '';
  oSelf.aPeriodosAvaliacao.each(function (oPeriodo, iIndice) {

    if (oPeriodo.iPeriodoDependenteAprovacao == iPeriodo) {

      oPeriodoDependente = oPeriodo;
      return ;
    }
  });
  return oPeriodoDependente;
};

DBViewLancamentoAvaliacaoTurma.prototype.isMatriculaValida = function (sSituacaoMatricula)  {
  return this.aSituacoesMatriculaValida.in_array(sSituacaoMatricula);
};

/**
 * Verifica o tipo de procedimento de avaliação da disciplina selecionada, para bloqueio ou não do botão do parecer
 */
DBViewLancamentoAvaliacaoTurma.prototype.validaFormaAvaliacaoDisciplina = function() {

  var oSelf = this;

  if( !oSelf.lTurmaEncerrada ) {

    oSelf.oBtnParecer.disabled = false;
    oSelf.oDadosTurmaSelecionada.aDisciplinas.each(function( oDisciplina ) {

      if( oDisciplina.iCodigo == $F('disciplina') && oDisciplina.sFormaAvaliacao == 'PARECER' ) {
        oSelf.oBtnParecer.disabled = true;
      }
    });
  }
};
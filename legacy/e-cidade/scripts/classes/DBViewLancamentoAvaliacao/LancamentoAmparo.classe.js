require_once("scripts/classes/DBViewLancamentoAvaliacao/DBViewLancamentoAvaliacao.classe.js");
require_once("scripts/classes/DBViewLancamentoAvaliacao/DisciplinaTurma.classe.js");
require_once("scripts/classes/DBViewLancamentoAvaliacao/AlunoTurma.classe.js");
require_once("scripts/classes/DBViewLancamentoAvaliacao/PeriodoTurma.classe.js");
require_once('scripts/widgets/DBToggleList.widget.js');
require_once("scripts/datagrid.widget.js");
require_once("scripts/object.js");


/**
 * Renderiza View de lançamento de Observação
 * @dependency Utiliza DBViewLancamentoAvaliacao.classe.js
 * @autor Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.6 $
 *
 * @param {Object}  aPeriodosAvaliacao Períodos de avaliacao da turma
 *                  [{iCodigoAvaliacao:"33",
 *                    iOrdemAvaliacao:"2",
 *                    geraResultadoFinal:false,
 *                    sFormaAvaliacao:"NOTA",
 *                    iPeriodoDependenteAprovacao:"",
 *                    mMinimoAprovacao:"15.00",
 *                    lControlaFrequencia:true,
 *                    iMenorValor:"0",
 *                    iMaiorValor:"30",
 *                    nVariacao:"0.5",
 *                    sDescricaoPeriodo:"2%BA+TRIMESTRE",
 *                    sDescricaoPeriodoAbreviado:"2%BA+TRIM",
 *                    sTipoAvaliacao:"A"},
 *                   {iCodigoAvaliacao:"11",
 *                    iOrdemAvaliacao:"4",
 *                    geraResultadoFinal:true,
 *                    sFormaAvaliacao:"NOTA",
 *                    iPeriodoDependenteAprovacao:"",
 *                    mMinimoAprovacao:"50.00",
 *                    lControlaFrequencia:false,
 *                    iMenorValor:"0",
 *                    iMaiorValor:"100",
 *                    nVariacao:"0.5",
 *                    sDescricaoPeriodo:"NOTA+FINAL",
 *                    sDescricaoPeriodoAbreviado:"NF",
 *                    sTipoAvaliacao:"R",
 *                    sFormaObtencao:"SO"
 *                   }]
 * @param {integer} iTurma Código da turma
 * @param {integer} iEtapa Código da etapa
 * @param {HTMLElement} oNode  onde será renderizado, se não informado, uma nova janela é criada
 * @returns void
 */
DBViewAvaliacao.LancamentoAmparo = function (aPeriodosAvaliacao, iRegencia, sDisciplina, iTurma, iEtapa, oNode) {

  /**
   * @var {Array}
   */
  this.aPeriodos = aPeriodosAvaliacao;

  /**
   * código da turma
   * @var {integer}
   */
  this.iTurma = iTurma;

  /**
   * código da etapa
   * @var {integer}
   */
  this.iEtapa = iEtapa;

  /**
   * código da regencia da turma
   * @var {integer}
   */
  this.iRegencia = iRegencia;

  /**
   * Nome da disciplina
   * @var {string}
   */
  this.sDisciplina = sDisciplina;
  /**
   * Largura da janela
   * @var {integer}
   */
  this.iTamanhoJanela = document.body.getWidth() - 20;

  /**
   * Altura da janela
   * @var {integer}
   */
  this.iAlturaJanela  = document.body.getHeight() / 1.1;

  /**
   * RPC para as requisições
   * @var {string}
   */
  this.sUrlRPC = 'edu4_amparo.RPC.php';

  /**
   * Onde será renderizado a windown. Se vazio, cria uma janela.
   */
  this.oView = '';
  if (oNode != null) {
    this.oView = oNode;
  }

  this.oWindowContainer = null;

  /** *********************************************************************** *
   *  *******************          COMPONENTES HTML       *******************
   *  *********************************************************************** */

  this.oCboCargaHoraria = new Element('select', {id: 'somaCargaHoraria'});
  this.oCboCargaHoraria.add(new Option('Sim', 'S'));
  this.oCboCargaHoraria.add(new Option('Não', 'N'));
  this.oCboCargaHoraria.style.width = '100%';


  this.oCboTipoAmparo = new Element('select', {id: 'tipoAmparo'});
  this.oCboTipoAmparo.add(new Option('Selecione', ''));
  this.oCboTipoAmparo.add(new Option('Amparo com Justificativa', 'J'));
  this.oCboTipoAmparo.add(new Option('Amparo com Convenção', 'C'));
  this.oCboTipoAmparo.style.width = '30%';

  /**
   * Monta o toogleList dos alunos
   * @var {DBToggleList}
   */
  this.oToggleAlunos = new DBToggleList([{sId:    'sNome',
                                          sLabel: 'Nome',
                                          sWidth: '180px'
                                         }],
                                        {},
                                        'sToggleAlunos'
                                       );
  /**
   * Monta o toogleList dos períodos
   * @var {DBToggleList
   */
  this.oTogglePeriodos = new DBToggleList([{sId:    'sPeriodo',
                                            sLabel: 'Período'
                                           }],
                                          {},
                                          'sTogglePeriodos'
                                         );

  oInstanciaLancamentoAmparoGlobal = this;

  this.oGridAmparados = new DBGrid('gridAmparados');
  this.oGridAmparados.nameInstance = 'oInstanciaLancamentoAmparoGlobal.oGridAmparados';
  this.oGridAmparados.setCheckbox(4); // <<-- coluna da Matricula
  this.oGridAmparados.setHeader(new Array('Aluno', 'Período', 'Tipo', 'Descrição', 'matricula', 'periodos'));
  this.oGridAmparados.setHeight(200);
  this.oGridAmparados.setCellWidth(new Array('40%', '15%', '18%','27%'));
  this.oGridAmparados.setCellAlign(new Array('left','left','left','left'));
  this.oGridAmparados.aHeaders[5].lDisplayed = false;
  this.oGridAmparados.aHeaders[6].lDisplayed = false;


  /**
   * Ancora para Justificativa
   * @var {HTMLElement}
   */
  this.oAnchorJustificativa = new Element('a', {'class': 'dbancora', href: '#', id:'anchorJustif'});
  this.oAnchorJustificativa.update("Justificativa Legal:");

  var oAttributosPadrao = {'type': 'text', 'class':'readonly', disabled:'disabled'};

  this.oCodigoJustificativa             = new Element('input', {'type': 'text', id:'iJustificativa'});
  this.oCodigoJustificativa.style.width = '83px';
  this.oLabelJustificativa              = new Element('input', oAttributosPadrao);
  this.oLabelJustificativa.id           = "sJustificativa";
  this.oLabelJustificativa.style.width  = "calc(100% - 554px)";

  this.oCodigoConvencao             = new Element('input', {'type': 'text', id:'iConvencao'});
  this.oCodigoConvencao.style.width = '83px';
  this.oLabelConvencao              = new Element('input', oAttributosPadrao);
  this.oLabelConvencao.id           = "sConvencao";
  this.oLabelConvencao.style.width  = "calc(100% - 657px)";

  this.oLabelConvencaoAbrev             = new Element('input', oAttributosPadrao);
  this.oLabelConvencaoAbrev.id          = "sConvencaoAbrev";
  this.oLabelConvencaoAbrev.style.width = "100px";


  /**
   * Ancora para Justificativa
   * @var {HTMLElement}
   */
  this.oAnchorConvencao = new Element('a', {'class': 'dbancora', href: '#', id:'anchorConvencao'}).update("Convenção:");

  this.oBtnSalvar  = new Element('input', {type:'button', value:'Salvar', name:'salvarAmparo', id:'btnSalvarAmparo'});
  this.oBtnLimpar  = new Element('input', {type:'button', value:'Limpar', name:'limparAmparo', id:'btnLimparAmparo'});
  this.oBtnExcluir = new Element('input', {type:'button', value:'Excluir Amparos', name:'excluir', id:'btnExcluirAmparo'});


  /**
   * Variável para setar uma função a ser excutada ao fechar a janela
   * @var {function}
   */
  this.oCallBackCloseWindow = function() {
    return true;
  };
};

/**
 * Seta quem é o container pai. Sobre o qual abrirá
 * var {object} windowAux
 */
DBViewAvaliacao.LancamentoAmparo.prototype.setContainerPai = function(oWindow) {

  this.oWindowContainer = oWindow;
  this.iTamanhoJanela   = oWindow.getWidth() - 50;
  this.iAlturaJanela    = oWindow.getHeight() / 1.1;
};

/**
 * Seta uma funcção para ser executada ao se fechar a janela
 * @param {function} fFunction
 * @returns void
 */
DBViewAvaliacao.LancamentoAmparo.prototype.setCallBackWindow = function (fFunction) {
  this.oCallBackCloseWindow = fFunction;
};


DBViewAvaliacao.LancamentoAmparo.prototype.criarWindow = function () {

  var oSelf = this;

  this.oWindowAmparo = new windowAux("wndAmparo", "Lançar Amparo", this.iTamanhoJanela, this.iAlturaJanela);
  this.oWindowAmparo.setShutDownFunction( function() {

    delete oInstanciaViewAbonoFaltaEscopoGlobal;
    oSelf.oCallBackCloseWindow();
    oSelf.oWindowAmparo.destroy();
  });

  var sConteudo  = "<div id='ctnLancamentoObservacao' style='width:98%'>                                              ";
      sConteudo += "  <fieldset style='width:100%'>                                                                   ";
      sConteudo += "    <legend>Amparo</legend>                                                                       ";
      sConteudo += "    <fieldset id='filtrosDadosAluno' class='separator' style='width:98%'>                         ";
      sConteudo += "      <legend>Selecione os Alunos</legend>                                                        ";
      sConteudo += "      <input type='hidden' id='iCodigoAmparoLancado' name='amparoLancado' value='' />             ";
      sConteudo += "      <table style='width:100%'>                                                                  ";
      sConteudo += "        <tr>                                                                                      ";
      sConteudo += "          <td id='ctnAlunoTurma' ></td>                                                           ";
      sConteudo += "        </tr>                                                                                     ";
      sConteudo += "      </table>                                                                                    ";
      sConteudo += "    </fieldset>                                                                                   ";
      sConteudo += "    <fieldset id='filtrosDadosPeriodo' class='separator' style='width:98%'>                       ";
      sConteudo += "      <legend>Selecione os Períodos</legend>                                                      ";
      sConteudo += "      <table style='width:100%'>                                                                  ";
      sConteudo += "        <tr>                                                                                      ";
      sConteudo += "          <td id='ctnPeriodoTurma' ></td>                                                         ";
      sConteudo += "        </tr>                                                                                     ";
      sConteudo += "      </table>                                                                                    ";
      sConteudo += "    </fieldset>                                                                                   ";
      sConteudo += "    <fieldset class='separator' style='width:98%'>                                                ";
      sConteudo += "      <legend>Amparo</legend>                                                                     ";
      sConteudo += "      <table style='width:100%'>                                                                  ";
      sConteudo += "        <tr>                                                                                      ";
      sConteudo += "          <td class='bold field-size4' nowrap>                                                    ";
      sConteudo += "            Gerar carga horária para esta disciplina no histórico:                                ";
      sConteudo += "          </td>                                                                                   ";
      sConteudo += "          <td id='ctnGerarCargaHoraria' style='width: 5%'></td>                                   ";
      sConteudo += "          <td class='bold' style='width: 5%' nowrap>Tipo Amparo:</td>                             ";
      sConteudo += "          <td id='ctnTipoAmparo'></td>                                                            ";
      sConteudo += "        </tr>                                                                                     ";
      sConteudo += "        <tr id='ctnJustificativa'>                                                                ";
      sConteudo += "          <td class='bold' id='ctnAmchorJustificativa'> </td>                                     ";
      sConteudo += "          <td colspan='3' nowrap='nowrap' id='ctnInputJustificativa'> </td>                       ";
      sConteudo += "        </tr>                                                                                     ";
      sConteudo += "        <tr id='ctnCovencao'>                                                                     ";
      sConteudo += "          <td class='bold' id='ctnAmchorConvencao'></td>                                          ";
      sConteudo += "          <td colspan='3' nowrap='nowrap' id='ctnInputConvencao'></td>                            ";
      sConteudo += "        </tr>                                                                                     ";
      sConteudo += "      </table>                                                                                    ";
      sConteudo += "    </fieldset>                                                                                   ";
      sConteudo += "  </fieldset>                                                                                     ";
      sConteudo += "  <center id='ctnBtnSalvarAmparo'></center>                                                       ";
      sConteudo += "  <fieldset style='width:100%'>                                                                   ";
      sConteudo += "    <legend>Alunos Amparados</legend>                                                             ";
      sConteudo += "    <div id='ctnGridAlunoAmparados'></div>                                                        ";
      sConteudo += "  </fieldset>                                                                                     ";
      sConteudo += "  <center id='ctnBtnExcluirAmparo'></center>                                                      ";
      sConteudo += "</div>                                                                                            ";

  var sMsg = "Lançar amparo para disciplina: " + this.sDisciplina.urlDecode();

  var sHelpMsg  = "Selecione um ou mais alunos e períodos para lançar o amparo. ";
      sHelpMsg += "Para excluir o amparo lançado, marque o checkbox na tabela e clique em excluir.";

  if (this.oView != "") {
    this.oView.innerHTML = sConteudo;
  } else {

    this.oWindowAmparo.setContent(sConteudo);
    this.oWindowAmparo.oMessageBoard = new DBMessageBoard('msgBoardLancamentoAmparo',
                                                          sMsg,
                                                          sHelpMsg,
                                                          this.oWindowAmparo.getContentContainer()
                                                         );

    if (this.oWindowContainer != null) {
      this.oWindowAmparo.setChildOf(this.oWindowContainer);
    }
    this.oWindowAmparo.show();
  }

};


DBViewAvaliacao.LancamentoAmparo.prototype.apresentaTipoAmparo = function () {

  switch (this.oCboTipoAmparo.value) {
    case 'J':

      $('ctnJustificativa').style.display = "";
      $('ctnCovencao').style.display      = "none";
      break;
    case 'C':

      $('ctnJustificativa').style.display = "none";
      $('ctnCovencao').style.display      = "";
      break;
    default:

      $('ctnJustificativa').style.display = "none";
      $('ctnCovencao').style.display      = "none";
      break;
  }

};

/**
 * Função de pesquisa para buscar as Justificativas da escola
 * @param {boolean} lMostra
 * @returns false
 */
DBViewAvaliacao.LancamentoAmparo.prototype.pesquisaJustificativa = function (lMostra) {

  var sUrl = "func_justificativa.php?";
  if (lMostra) {

    sUrl += "funcao_js=parent.oInstanciaLancamentoAmparoGlobal.retornoJustificativa|ed06_i_codigo|ed06_c_descr";
    js_OpenJanelaIframe('', 'db_iframe_justificativa', sUrl, 'Pesquisa Justificativa', true);
  } else if ($F('iJustificativa') != '') {

    sUrl += "pesquisa_chave="+$F('iJustificativa');
    sUrl += "&funcao_js=parent.oInstanciaLancamentoAmparoGlobal.retornoJustificativa2";
    js_OpenJanelaIframe('', 'db_iframe_justificativa', sUrl, 'Pesquisa Justificativa', false);
  } else {

    $('iJustificativa').value = '';
    $('sJustificativa').value = '';
  }
  if ( lMostra ) {
    $('Jandb_iframe_justificativa').style.zIndex = 10000;
  }
};

/**
 * Retorno da função de pesquisa as Justificativas quando clicado na ancora
 * @param {integer} código da justificativa
 * @param {string}  descricao da justificativa
 * @returns false
 */
DBViewAvaliacao.LancamentoAmparo.prototype.retornoJustificativa = function (iCodigo, sDescricao) {

  $('iJustificativa').value = iCodigo;
  $('sJustificativa').value = sDescricao;
  db_iframe_justificativa.hide();
};

/**
 * Retorno da função de pesquisa as Justificativas quando digitado código
 * @param {string}  sDescricao descricao da justificativa
 * @param {boolean} lErro      true se não encontrou justificativa para código digitado
 * @returns false
 */
DBViewAvaliacao.LancamentoAmparo.prototype.retornoJustificativa2 = function (sDescricao, lErro) {

  $('sJustificativa').value = sDescricao;
  if (lErro) {

    $('iJustificativa').value = '';
    $('iJustificativa').focus();
  }
};

/**
 * Função de pesquisa para buscar as Convenções
 * @param {boolean} lMostra
 * @returns false
 */
DBViewAvaliacao.LancamentoAmparo.prototype.pesquisaConvecao = function (lMostra) {

  var sUrl = "func_convencaoamp.php?";
  if (lMostra) {

    sUrl += "funcao_js=parent.oInstanciaLancamentoAmparoGlobal.retornoConvencao|ed250_i_codigo|ed250_c_descr|ed250_c_abrev";
    js_OpenJanelaIframe('', 'db_iframe_convencaoamp', sUrl, 'Pesquisa Convenção', true);
  } else if ($F('iConvencao') != '') {

    sUrl += "pesquisa_chave="+$F('iConvencao');
    sUrl += "&funcao_js=parent.oInstanciaLancamentoAmparoGlobal.retornoConvencao2";
    js_OpenJanelaIframe('', 'db_iframe_convencaoamp', sUrl, 'Pesquisa Convenção', false);
  } else {

    $('iConvencao').value      = '';
    $('sConvencao').value      = '';
    $('sConvencaoAbrev').value = '';
  }
  if ( lMostra ) {
    $('Jandb_iframe_convencaoamp').style.zIndex = 10000;
  }
};

/**
 * Retorno da função de pesquisa as Justificativas quando clicado na ancora
 * @param {integer} iCodigo    código da justificativa
 * @param {string}  sDescricao descricao da Conveção
 * @param {string}  sAbrev     abreviatura
 * @returns false
 */
DBViewAvaliacao.LancamentoAmparo.prototype.retornoConvencao = function (iCodigo, sDescricao, sAbrev) {

  $('iConvencao').value      = iCodigo;
  $('sConvencao').value      = sDescricao;
  $('sConvencaoAbrev').value = sAbrev;
  db_iframe_convencaoamp.hide();
};

/**
 * Retorno da função de pesquisa as Justificativas quando clicado na ancora
 * @param {string}  sDescricao descricao da Conveção
 * @param {string}  sAbrev     abreviatura
 * @param {boolean} lErro      true se não encontrou convencao para código digitado
 * @returns false
 */
DBViewAvaliacao.LancamentoAmparo.prototype.retornoConvencao2 = function (sDescricao, sAbrev, lErro) {

  $('sConvencao').value      = sDescricao;
  $('sConvencaoAbrev').value = sAbrev;
  if (lErro) {

    $('iConvencao').value = '';
    $('iConvencao').focus();
  }
};

/**
 * Busca os alunos para montar no toggleList
 */
DBViewAvaliacao.LancamentoAmparo.prototype.buscaAlunos = function () {

  var oSelf                  = this;
  var oParametros            = new Object();
      oParametros.exec       = 'getAlunos';
      oParametros.iTurma     = this.iTurma;
      oParametros.iEtapa     = this.iEtapa;
      oParametros.iRegencia  = this.iRegencia;

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametros);
      oDadosRequisicao.onComplete = function(oResponse) {
                                      oSelf.retornoBuscaAlunos(oResponse);
                                    };

  js_divCarregando("Aguarde, pesquisando os alunos.", "msgBoxA");
  new Ajax.Request(this.sUrlRPC, oDadosRequisicao);
};

/**
 * Retorno dos alunos
 * @param oAjax
 * @returns {Boolean}
 */
DBViewAvaliacao.LancamentoAmparo.prototype.retornoBuscaAlunos = function (oAjax) {

  js_removeObj("msgBoxA");
  var oSelf    = this;
  var oRetorno = eval('('+oAjax.responseText+')');

  if (oRetorno.aAlunos.length == 0) {

    alert('Não há aluno(s) para amparo.');
    return false;
  }

  oRetorno.aAlunos.each(function (oAluno) {

    var oAlunoDado = {};
    oAlunoDado.iMatricula = oAluno.iMatricula;
    oAlunoDado.sNome      = oAluno.sNome.urlDecode();
    oAlunoDado.iCodigo    = oAluno.iCodigo;

    oSelf.oToggleAlunos.addSelect(oAlunoDado);
  });

  this.oToggleAlunos.show($('ctnAlunoTurma'));

  return true;
};

DBViewAvaliacao.LancamentoAmparo.prototype.buscaAlunosAmparados = function () {

  var oSelf                  = this;
  var oParametros            = new Object();
      oParametros.exec       = 'getAlunosAmparados';
      oParametros.iTurma     = this.iTurma;
      oParametros.iEtapa     = this.iEtapa;
      oParametros.iRegencia  = this.iRegencia;

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametros);
      oDadosRequisicao.onComplete = function(oAjax) {
                                      oSelf.retornoBuscaAlunosAmparados(oAjax);
                                    };

  js_divCarregando("Aguarde, pesquisando os alunos.", "msgBoxB");
  new Ajax.Request(this.sUrlRPC, oDadosRequisicao);
};

DBViewAvaliacao.LancamentoAmparo.prototype.retornoBuscaAlunosAmparados = function (oAjax) {

  js_removeObj("msgBoxB");
  var oSelf    = this;
  var oRetorno = eval('('+oAjax.responseText+')');

  this.oGridAmparados.clearAll(true);
  oRetorno.aAlunos.each( function (oAluno) {

    aLinha = new Array();
    aLinha.push(oAluno.sNome.urlDecode());

    var sPeriodos = '';
    var aPeriodos = new Array();
    oAluno.aPeriodos.each( function (oPeriodo) {

      if (sPeriodos != '') {
        sPeriodos += ', ';
      }
      sPeriodos += oPeriodo.sAbreviatura.urlDecode();
      aPeriodos.push(oPeriodo.iCodigo);
    });

    aLinha.push(sPeriodos);

    var sTipo = oAluno.sTipo == 'J' ? 'Justificativa' : 'Convenção';
    aLinha.push(sTipo);
    aLinha.push(oAluno.sDescricao.urlDecode());
    aLinha.push(oAluno.iMatricula);
    aLinha.push(aPeriodos);


    oSelf.oGridAmparados.addRow(aLinha);
  });

  this.oGridAmparados.renderRows();

};


DBViewAvaliacao.LancamentoAmparo.prototype.alterarAmparoAluno = function (oElement) {


  alert(oElement.outerHTML);

};

DBViewAvaliacao.LancamentoAmparo.prototype.renderizaPeriodos = function () {

  var oSelf = this;
  this.aPeriodos.each( function (oPeriodo) {

    if (oPeriodo.sTipoAvaliacao == 'R') {
      return;
    }

    var oDadoPeriodo      = {};
    oDadoPeriodo.iCodigo  = oPeriodo.iCodigoAvaliacao;
    oDadoPeriodo.sPeriodo = oPeriodo.sDescricaoPeriodo.urlDecode();

    oSelf.oTogglePeriodos.addSelect(oDadoPeriodo);
  });

  this.oTogglePeriodos.show($('ctnPeriodoTurma'));
};

DBViewAvaliacao.LancamentoAmparo.prototype.show = function() {

  var oSelf = this;
  this.criarWindow();
  $('ctnJustificativa').style.display = "none";
  $('ctnCovencao').style.display      = "none";

  $('ctnAmchorJustificativa').appendChild(this.oAnchorJustificativa);
  $('ctnAmchorConvencao').appendChild(this.oAnchorConvencao);
  $('ctnInputJustificativa').appendChild(this.oCodigoJustificativa);
  $('ctnInputJustificativa').appendChild(this.oLabelJustificativa);
  $('ctnInputConvencao').appendChild(this.oCodigoConvencao);
  $('ctnInputConvencao').appendChild(this.oLabelConvencao);
  $('ctnInputConvencao').appendChild(this.oLabelConvencaoAbrev);
  $('ctnGerarCargaHoraria').appendChild(this.oCboCargaHoraria);
  $('ctnTipoAmparo').appendChild(this.oCboTipoAmparo);
  $('ctnBtnSalvarAmparo').appendChild(this.oBtnSalvar);
  $('ctnBtnSalvarAmparo').appendChild(this.oBtnLimpar);
  $('ctnBtnExcluirAmparo').appendChild(this.oBtnExcluir);

  this.oToggleAlunos.closeOrderButtons();
  this.oTogglePeriodos.closeOrderButtons();
  this.oToggleAlunos.show($('ctnAlunoTurma'));
  this.oTogglePeriodos.show($('ctnPeriodoTurma'));

  this.oGridAmparados.show($('ctnGridAlunoAmparados'));

  this.oAnchorJustificativa.onclick = function () {
    oSelf.pesquisaJustificativa(true);
  };

  this.oCodigoJustificativa.onblur = function () {
    oSelf.pesquisaJustificativa(false);
  };

  this.oAnchorConvencao.onclick = function () {
    oSelf.pesquisaConvecao(true);
  };

  this.oCodigoConvencao.onblur = function () {
    oSelf.pesquisaConvecao(false);
  };

  this.oCboTipoAmparo.onchange = function () {
    oSelf.apresentaTipoAmparo();
  };

  this.oBtnSalvar.onclick = function () {
    oSelf.salvar();
  };

  this.oBtnLimpar.onclick = function () {
    oSelf.limpaDados(false);
  };

  this.oBtnExcluir.onclick = function () {
    oSelf.excluirAmparosSelecionados();
  };

  this.renderizaPeriodos();
  this.buscaAlunos();
  this.buscaAlunosAmparados();
};

/**
 * Valida se todos campos obrigatórios foram preenchidos
 * @returns boolean
 */
DBViewAvaliacao.LancamentoAmparo.prototype.validaDadosFormulario = function () {

  if (this.oToggleAlunos.getSelected().length == 0) {

    alert ("Selecione ao menos um aluno.");
    return false;
  }

  if (this.oTogglePeriodos.getSelected().length == 0) {

    alert ("Selecione ao menos um período.");
    return false;
  }

  if ($F('tipoAmparo') == '') {

    alert ("Selecione o tipo de amparo.");
    return false;
  }

  if ($F('tipoAmparo') == 'J' && $F('iJustificativa') == '') {

    alert ("Selecione uma justificativa para o amparo.");
    return false;
  }

  if ($F('tipoAmparo') == 'C' && $F('iConvencao') == '') {

    alert ("Selecione a Convenção do amparo.");
    return false;
  }
  return true;
};

/**
 * Salva os dados
 */
DBViewAvaliacao.LancamentoAmparo.prototype.salvar = function () {

  if (!this.validaDadosFormulario()) {
    return false;
  }

  var oSelf                      = this;
  var oParametros                = new Object();
      oParametros.exec           = 'salvarAmparo';
      oParametros.iTurma         = this.iTurma;
      oParametros.iEtapa         = this.iEtapa;
      oParametros.iRegencia      = this.iRegencia;
      oParametros.aAlunos        = this.oToggleAlunos.getSelected();
      oParametros.aPeriodos      = this.oTogglePeriodos.getSelected();
      oParametros.lCargaHoraria  = $F('somaCargaHoraria') == 'S' ? true : false;
      oParametros.sTipoAmparo    = $F('tipoAmparo');
      oParametros.iJustificativa = $F('iJustificativa');
      oParametros.iConvencao     = $F('iConvencao');
      oParametros.iAmparo        = '';
      if ($F('iCodigoAmparoLancado')) {
        oParametros.iAmparo = $F('iCodigoAmparoLancado');
      }

  var oDadosRequisicao            = new Object();
  oDadosRequisicao.method     = 'post';
  oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametros);
  oDadosRequisicao.onComplete = function(oAjax) {
                                  oSelf.retornoSalvarAmparo(oAjax);
                                };

  js_divCarregando("Aguarde, salvando.", "msgBoxC");
  new Ajax.Request(this.sUrlRPC, oDadosRequisicao);

};

DBViewAvaliacao.LancamentoAmparo.prototype.limpaDados = function (lReloadView) {

  this.oToggleAlunos.oElementos.oBotaoRemoverSelecaoTodos.click();
  this.oTogglePeriodos.oElementos.oBotaoRemoverSelecaoTodos.click();

  $('tipoAmparo').value       = '';
  $('iConvencao').value       = '';
  $('sConvencao').value       = '';
  $('sConvencaoAbrev').value  = '';
  $('iJustificativa').value   ='';
  $('sJustificativa').value   ='';
  $('somaCargaHoraria').value = 'S';

  this.apresentaTipoAmparo();

  if (lReloadView) {

    this.oToggleAlunos.clearAll();
    this.oTogglePeriodos.clearAll();
    this.renderizaPeriodos();
    this.buscaAlunos();
    this.buscaAlunosAmparados();
  }

};

DBViewAvaliacao.LancamentoAmparo.prototype.retornoSalvarAmparo = function (oAjax) {

  js_removeObj("msgBoxC");
  var oRetorno = eval('('+oAjax.responseText+')');

  alert(oRetorno.message.urlDecode());

  if (oRetorno.status != 2) {
    this.limpaDados(true);
  }
};

/**
 * Exclui os amparos selecionados na Grid
 */
DBViewAvaliacao.LancamentoAmparo.prototype.excluirAmparosSelecionados = function () {

  var aSelecionadosGrid  = this.oGridAmparados.getSelection();
  var iArraySelecionados = aSelecionadosGrid.length;

  if (iArraySelecionados == 0) {

    alert('Selecione o(s) aluno(s) na grade para excluir o(s) amparo(s).');
    return false;
  }

  var aAlunosExcluirAmparo = [];
  for (var i = 0; i < iArraySelecionados; i++) {
    aAlunosExcluirAmparo.push(aSelecionadosGrid[i][0]);
  }

  var oSelf                      = this;
  var oParametros                = new Object();
      oParametros.exec           = 'excluirAmparo';
      oParametros.iTurma         = this.iTurma;
      oParametros.iEtapa         = this.iEtapa;
      oParametros.iRegencia      = this.iRegencia;
      oParametros.aAlunos        = aAlunosExcluirAmparo;

  var oDadosRequisicao        = new Object();
  oDadosRequisicao.method     = 'post';
  oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametros);
  oDadosRequisicao.onComplete = function(oAjax) {
                                  oSelf.retornoExcluirAmparo(oAjax);
                                };

  js_divCarregando("Aguarde, salvando.", "msgBoxC");
  new Ajax.Request(this.sUrlRPC, oDadosRequisicao);
};

/**
 * Retorno da função que exclui os amparos
 */
DBViewAvaliacao.LancamentoAmparo.prototype.retornoExcluirAmparo = function (oAjax) {

  js_removeObj("msgBoxC");
  var oRetorno = eval('('+oAjax.responseText+')');

  alert(oRetorno.message.urlDecode());

  if (oRetorno.status != 2) {
    this.limpaDados(true);
  }

};
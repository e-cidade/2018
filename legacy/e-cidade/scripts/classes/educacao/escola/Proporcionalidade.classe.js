/**
 *      E-cidade Software Publico para Gestao Municipal
 *   Copyright (C) 2014  DBSeller Servicos de Informatica
 *                             www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *
 *   Este programa e software livre; voce pode redistribui-lo e/ou
 *   modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versao 2 da
 *   Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *   Este programa e distribuido na expectativa de ser util, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *   COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *   PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *   detalhes.
 *
 *   Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *   junto com este programa; se nao, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *
 *   Copia da licenca no diretorio licenca/licenca_en.txt
 *                 licenca/licenca_pt.txt
 */
const MENSAGENS_PROPORCIONALIDADE = 'educacao.escola.Proporcionalidade_classe.';

require_once("scripts/classes/educacao/DBViewFormularioEducacao.classe.js");

/**
 * Classe responsável por realizar alterações referentes aos períodos que devem ser considerados no cálculo do
 * resultado final, de alunos que vieram de fora da rede
 * @param {int} iTurma - Código da turma
 * @param {int} iEtapa - Código da etapa
 * @constructor
 */
DBViewFormularioEducacao.Proporcionalidade = function( iTurma, iEtapa ) {

  oInstanciaProporcionalidade = this;

  this.iTurma            = iTurma;
  this.iEtapa            = iEtapa;
  this.oGridPeriodos     = null;
  this.aPeriodosTurma    = [];
  this.aPeriodosAluno    = [];
  this.oWindowContainer  = null;

  this.lSalvouDados = false;

  this.fCallBackShutDown = function(){};
  this.fCallBackSalvar   = function(){};

  /**
   * RPC's
   * @type {string}
   */
  this.sRpcTurma     = 'edu4_turmas.RPC.php';
  this.sRpcMatricula = 'edu4_matricula.RPC.php';

  /**
   * Div principal que contém os elementos a serem apresentados
   * @type {HTMLElement}
   */
  this.oDivPrincipal = document.createElement( 'div' );
  this.oDivPrincipal.setAttribute( 'id', 'divPrincipal' );

  /**
   * Div referente as informações do aluno
   * @type {HTMLElement}
   */
  this.oDivAlunos = document.createElement( 'div' );
  this.oDivAlunos.setAttribute( 'id', 'divAlunos' );

  /**
   * Fieldset e legend das informações do aluno
   * @type {HTMLElement}
   */
  this.oFieldsetPrincipal = document.createElement( 'fieldset' );
  this.oFieldsetPrincipal.addClassName( 'separator' );
  this.oLegendPrincipal           = document.createElement( 'legend' );
  this.oLegendPrincipal.innerHTML = 'Alunos para alteração';

  this.oTabelaPrincipal = document.createElement( 'table' );

  /**
   * Linhas e colunas referentes a apresentação dos elementos do aluno
   * @type {HTMLElement}
   */
  this.oLinhaAlunos        = document.createElement( 'tr' );
  this.oColunaLabelAlunos  = document.createElement( 'td' );
  this.oColunaSelectAlunos = document.createElement( 'td' );
  this.oColunaSelectAlunos.setAttribute( 'id', 'colunaSelectAlunos' );
  this.oColunaSelectAlunos.setStyle( { 'width' : '80%' } );

  /**
   * Label Aluno
   * @type {HTMLElement}
   */
  this.oLabelAluno = document.createElement( 'label' );
  this.oLabelAluno.addClassName( 'bold' );
  this.oLabelAluno.innerHTML = 'Aluno:';

  /**
   * Linhas e colunas referentes a apresentação dos elementos da data de matrícula
   * @type {HTMLElement}
   */
  this.oLinhaDataMatricula       = document.createElement( 'tr' );
  this.oColunaLabelDataMatricula = document.createElement( 'td' );
  this.oColunaInputDataMatricula = document.createElement( 'td' );

  /**
   * Label Data de Matrícula
   * @type {HTMLElement}
   */
  this.oLabelDataMatricula = document.createElement( 'label' );
  this.oLabelDataMatricula.addClassName( 'bold' );
  this.oLabelDataMatricula.innerHTML = 'Data de Matrícula:';

  /**
   * Input para apresentação da data de matrícula do aluno selecionado
   * @type {HTMLElement}
   */
  this.oInputDataMatricula = document.createElement( 'input' );
  this.oInputDataMatricula.setAttribute( 'id', 'oInputDataMatricula' );
  this.oInputDataMatricula.setAttribute( 'type', 'text' );
  this.oInputDataMatricula.setAttribute( 'value', '' );
  this.oInputDataMatricula.setAttribute( 'readOnly', 'readOnly' );
  this.oInputDataMatricula.addClassName( 'field-size2' );
  this.oInputDataMatricula.setStyle( { 'backgroundColor' : '#DEB887' } );

  /**
   * Div que contém a Grid dos períodos
   * @type {HTMLElement}
   */
  this.oDivPeriodos = document.createElement( 'div' );
  this.oDivPeriodos.setAttribute( 'id', 'divPeriodos' );

  /**
   * Fieldset e Legend referente aos períodos
   * @type {HTMLElement}
   */
  this.oFielsetPeriodos = document.createElement( 'fieldset' );
  this.oFielsetPeriodos.addClassName( 'separator' );
  this.oLegendPeriodos           = document.createElement( 'legend' );
  this.oLegendPeriodos.innerHTML = 'Períodos';

  /**
   * Div para apresentação da Grid
   * @type {HTMLElement}
   */
  this.oDivGrid = document.createElement( 'div' );
  this.oDivGrid.setAttribute( 'id', 'divGrid' );

  /**
   * Div do elemento botão para Salvar
   * @type {HTMLElement}
   */
  this.oDivBotao = document.createElement( 'div' );
  this.oDivBotao.setAttribute( 'id', 'divBotao' );
  this.oDivBotao.addClassName( 'container' );

  /**
   * Elemento input text para Salvar as alterações da Grid
   * @type {HTMLElement}
   */
  this.oBotaoSalvar = document.createElement( 'input' );
  this.oBotaoSalvar.setAttribute( 'id', 'btnSalvarProporcionalidade' );
  this.oBotaoSalvar.setAttribute( 'type', 'button' );
  this.oBotaoSalvar.setAttribute( 'value', 'Salvar' );


  /**
   * Seta os filhos de cada elemento
   */
  this.oDivPrincipal.appendChild( this.oDivAlunos );
  this.oDivPrincipal.appendChild( this.oDivPeriodos );
  this.oDivAlunos.appendChild( this.oFieldsetPrincipal );

  this.oFieldsetPrincipal.appendChild( this.oLegendPrincipal );
  this.oFieldsetPrincipal.appendChild( this.oTabelaPrincipal );

  this.oTabelaPrincipal.appendChild( this.oLinhaAlunos );
  this.oTabelaPrincipal.appendChild( this.oLinhaDataMatricula );

  this.oLinhaAlunos.appendChild( this.oColunaLabelAlunos );
  this.oLinhaAlunos.appendChild( this.oColunaSelectAlunos );
  this.oColunaLabelAlunos.appendChild( this.oLabelAluno );

  this.oLinhaDataMatricula.appendChild( this.oColunaLabelDataMatricula );
  this.oLinhaDataMatricula.appendChild( this.oColunaInputDataMatricula );
  this.oColunaLabelDataMatricula.appendChild( this.oLabelDataMatricula );
  this.oColunaInputDataMatricula.appendChild( this.oInputDataMatricula );

  this.oDivPeriodos.appendChild( this.oFielsetPeriodos );
  this.oDivPeriodos.appendChild( this.oDivBotao );
  this.oFielsetPeriodos.appendChild( this.oLegendPeriodos );
  this.oFielsetPeriodos.appendChild( this.oDivGrid );
  this.oDivBotao.appendChild( this.oBotaoSalvar );
};

/**
 * Cria a windowAux, executando a chamada para montagem do combo dos alunos e grid do periodos da turma
 */
DBViewFormularioEducacao.Proporcionalidade.prototype.criaJanela = function() {

  var oSelf          = this;
  var iTamanhoJanela = 800;
  var iAlturaJanela  = 440;

  this.oWindowProporcionalidade = new windowAux( 'oWindowProporcionalidade', 'Proporcionalidade', iTamanhoJanela, iAlturaJanela );

  var sMensagemTitulo = 'Períodos para cálculo do resultado final';
  var sMensagemAjuda   = 'Desmarque os períodos a serem desconsiderados para o cálculo do resultado final.';
      sMensagemAjuda  += ' As alterações, serão replicadas para todas as disciplinas, cuja forma de obtenção do';
      sMensagemAjuda  += ' resultado seja SOMA e utilize proporcionalidade.';

  this.oWindowProporcionalidade.setContent( this.oDivPrincipal );
  this.oWindowProporcionalidade.setShutDownFunction( function () {

    oSelf.fCallBackShutDown();
    oSelf.oWindowProporcionalidade.destroy();

    if (oSelf.lSalvouDados) {
      oSelf.fCallBackSalvar();
    }
  });

  if( this.oWindowContainer != '' ) {
    oSelf.oWindowProporcionalidade.setChildOf( this.oWindowContainer );
  }

  this.oMessageBoard = new DBMessageBoard(
                                           'messageBoardProporcionalidade',
                                           sMensagemTitulo,
                                           sMensagemAjuda,
                                           this.oWindowProporcionalidade.getContentContainer()
                                         );
  this.oWindowProporcionalidade.show();

  this.montaComboAlunos();
  this.montaGridPeriodos();

  this.oLabelDataMatricula.setAttribute('for', 'oInputDataMatricula');

  $('btnSalvarProporcionalidade').onclick = function() {
    oSelf.salvar();
  };
};

/**
 * Monta o combo com os alunos que vieram de fora da escola
 */
DBViewFormularioEducacao.Proporcionalidade.prototype.montaComboAlunos = function() {

  var oSelf   = this;
  var fChange = function() {

    var aSelecionado = oSelf.oAlunosOrigemFora.getElementosSelecionados();
    $('oInputDataMatricula').value = aSelecionado[0].getAttribute( 'data_matricula' );

    if( !empty( aSelecionado[0].value ) ) {
      oSelf.periodosProporcionalidadeAluno( aSelecionado[0].value );
    } else {

      oSelf.aPeriodosAluno.length = 0;
      oSelf.preencheGrid();
    }
  };

  this.oAlunosOrigemFora = new DBViewAvaliacao.AlunoTurma( this.iTurma, this.iEtapa );
  this.oAlunosOrigemFora.lTrazerAlunosEncerrados = false;
  this.oAlunosOrigemFora.setLargura( '95%' );
  this.oAlunosOrigemFora.somenteAlunosOrigemForaRede( true );
  this.oAlunosOrigemFora.onChangeCallBack( fChange );
  this.oAlunosOrigemFora.show( $('colunaSelectAlunos') );

  this.oLabelAluno.setAttribute('for', this.oAlunosOrigemFora.oCboAlunos.id);
};

/**
 * Monta a grid dos periodos que compoem o resultado no procedimento de avaliaçao da turma
 */
DBViewFormularioEducacao.Proporcionalidade.prototype.montaGridPeriodos = function() {

  this.oGridPeriodos              = new DBGrid( 'oGridPeriodos' );
  this.oGridPeriodos.nameInstance = 'oInstanciaProporcionalidade.oGridPeriodos';
  this.oGridPeriodos.setCheckbox( 0 );
  this.oGridPeriodos.setHeader( [ 'Ordem', 'Período' ] );
  this.oGridPeriodos.setCellAlign( [ 'left' ] );
  this.oGridPeriodos.setCellWidth( [ '100%' ] );
  this.oGridPeriodos.aHeaders[1].lDisplayed = false;
  this.oGridPeriodos.show( $('divGrid') );

  this.buscaPeriodosTurma();
};

/**
 * Busca os periodos que compoem o resultado final no procedimento de avaliaçao da turma
 */
DBViewFormularioEducacao.Proporcionalidade.prototype.buscaPeriodosTurma = function() {

  var oSelf              = this;
  var oParametros        = {};
      oParametros.exec   = 'periodosCompoemResultado';
      oParametros.iTurma = this.iTurma;
      oParametros.iEtapa = this.iEtapa;

  var oDadosRequisicao            = {};
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json=' + Object.toJSON( oParametros );
      oDadosRequisicao.onComplete = function( oAjax ) {
        oSelf.retornoBuscaPeriodosTurma( oAjax );
      };

  js_divCarregando( _M( MENSAGENS_PROPORCIONALIDADE + 'buscando_periodos' ), "msgBox" );
  new Ajax.Request( this.sRpcTurma, oDadosRequisicao );
};

/**
 * Retorno da busca dos períodos da turma
 * @param oAjax
 * @returns {boolean}
 */
DBViewFormularioEducacao.Proporcionalidade.prototype.retornoBuscaPeriodosTurma = function( oAjax ) {

  js_removeObj( "msgBox" );
  var oRetorno = JSON.parse( oAjax.responseText );

  if( oRetorno.status != 1 ) {

    alert( oRetorno.message.urlDecode() );
    return false;
  }

  if( oRetorno.aPeriodos.length > 0 ) {

    this.aPeriodosTurma = oRetorno.aPeriodos;
    this.preencheGrid();
  }
};

/**
 * Busca os períodos selecionados para cálculo do resultado final do aluno
 * @param {int} iMatricula - Matrícula do aluno
 */
DBViewFormularioEducacao.Proporcionalidade.prototype.periodosProporcionalidadeAluno = function( iMatricula ) {

  var oSelf = this;

  var oParametros            = {};
      oParametros.exec       = 'periodosProporcionalidadeAluno';
      oParametros.iMatricula = iMatricula;

  var oDadosRequisicao            = {};
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json=' + Object.toJSON( oParametros );
      oDadosRequisicao.onComplete = function( oAjax ) {
        oSelf.retornoPeriodosProporcionalidadeAluno( oAjax );
      };

  js_divCarregando( _M( MENSAGENS_PROPORCIONALIDADE + 'buscando_periodos_aluno' ), "msgBox" );
  new Ajax.Request( this.sRpcMatricula, oDadosRequisicao );
};

/**
 * Retorno dos períodos selecionados para cálculo do resultado final
 * @param oAjax
 */
DBViewFormularioEducacao.Proporcionalidade.prototype.retornoPeriodosProporcionalidadeAluno = function( oAjax ) {

  js_removeObj( "msgBox" );
  var oRetorno = JSON.parse( oAjax.responseText );

  this.aPeriodosAluno = oRetorno.aOrdensPeriodos;
  this.preencheGrid();
};

/**
 * Salva os períodos a serem considerados para cálculo do resultado final do aluno
 * @returns {boolean}
 */
DBViewFormularioEducacao.Proporcionalidade.prototype.salvar = function() {

  var oSelf              = this;
  var aAlunoSelecionado  = this.oAlunosOrigemFora.getSelecionados();

  if( empty( aAlunoSelecionado[0] ) ) {

    alert( _M( MENSAGENS_PROPORCIONALIDADE + 'selecione_aluno' ) );
    return false;
  }

  var aLinhasGrid    = this.oGridPeriodos.getRows();
  var aOrdemPeriodos = [];

  aLinhasGrid.each(function( oLinha ) {

    if( oLinha.isSelected ) {
      aOrdemPeriodos.push( oLinha.aCells[1].content );
    }
  });

  this.lSalvouDados = true;

  var oParametros                = {};
      oParametros.exec           = 'permitirProporcionalidadeAluno';
      oParametros.iMatricula     = aAlunoSelecionado[0];
      oParametros.aOrdemPeriodos = aOrdemPeriodos;

  var oDadosRequisicao            = {};
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json=' + Object.toJSON( oParametros );
      oDadosRequisicao.onComplete = function( oAjax ) {
        oSelf.retornoSalvar( oAjax );
      };

  js_divCarregando( _M( MENSAGENS_PROPORCIONALIDADE + 'salvando_periodos' ), "msgBox" );
  new Ajax.Request( this.sRpcMatricula, oDadosRequisicao );
};

/**
 * Retorno do salvar os períodos para cálculo do resultado final
 * @param oAjax
 */
DBViewFormularioEducacao.Proporcionalidade.prototype.retornoSalvar = function( oAjax ) {

  js_removeObj( "msgBox" );
  var oRetorno = JSON.parse( oAjax.responseText );

  alert( oRetorno.sMessage.urlDecode() );
};

/**
 * Preenche a grid com os periodos, validando os periodos do aluno, quando este deve ou nao estar selecionado
 */
DBViewFormularioEducacao.Proporcionalidade.prototype.preencheGrid = function() {

  var oSelf = this;

  oSelf.oGridPeriodos.clearAll( true );
  oSelf.aPeriodosTurma.each(function( oPeriodo ) {

    var aLinha = [];
        aLinha.push( oPeriodo.iOrdem );
        aLinha.push( oPeriodo.sDescricao.urlDecode() );

    var lChecked  = true;
    var lDisabled = false;

    if( oSelf.aPeriodosAluno.length > 0 && !js_search_in_array( oSelf.aPeriodosAluno, oPeriodo.iOrdem ) ) {
      lChecked = false;
    }

    /**
     * Caso este seja o último período que compõe o resultado, a linha fica bloqueada
     */
    if( oPeriodo.lBloqueia === true ) {

      lChecked  = true;
      lDisabled = true;
    }

    oSelf.oGridPeriodos.addRow( aLinha, true, lDisabled, lChecked );
  });

  oSelf.oGridPeriodos.renderRows();
};

/**
 * Seta o container pai da window da Proporcionalidade
 * @param oWindow
 */
DBViewFormularioEducacao.Proporcionalidade.prototype.setContainerPai = function( oWindow ) {
  this.oWindowContainer = oWindow;
};

/**
 * Seta o callback a ser executado quando a window for fechada
 * @param fFunction
 */
DBViewFormularioEducacao.Proporcionalidade.prototype.setCallBack = function( fFunction ) {
  this.fCallBackShutDown = fFunction;
};

/**
 * Seta o callback a ser executado quando a window for fechada e foi salvo
 * @param fFunction
 */
DBViewFormularioEducacao.Proporcionalidade.prototype.setCallBackSalvar = function( fFunction ) {
  this.fCallBackSalvar = fFunction;
};


/**
 * Responsavel por iniciar a criaçao da janela para alteraçoes referentes a proporcionalidade
 */
DBViewFormularioEducacao.Proporcionalidade.prototype.show = function() {
  this.criaJanela();
};
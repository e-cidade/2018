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

const MENSAGENS_AVALIACAO_ALTERNATIVA = 'educacao.escola.AvaliacaoAlternativa_classe.';

require_once("scripts/classes/educacao/DBViewFormularioEducacao.classe.js");

DBViewFormularioEducacao.AvaliacaoAlternativa = function( iTurma, iEtapa ) {

  oInstanciaAvaliacaoAlternativa = this;

  this.iTurma            = iTurma;
  this.iEtapa            = iEtapa;
  this.aPeriodos         = [];
  this.lProcessouAluno   = false;

  this.oWindowContainer  = null;

  this.fCallBackShutDown = function(){};
  this.fCallBackSalvar   = function(){};

  /**
   * Variáveis com informações referente ao aluno selecionado
   */
  this.iAuxiliarAvaliacaoAlternativa    = null;
  this.aAproveitamentosAlunoSelecionado = [];

  /**
   * RPC's
   * @type {string}
   */
  this.sRpcAvaliacaoAlternativa = 'edu4_avaliacaoalternativa.RPC.php';

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
  this.oColunaSelectAlunos.setStyle( { 'width' : '70%' } );

  this.oColunaDataMatricula = document.createElement('td');
  this.oColunaDataMatricula.setAttribute( 'id', 'colunaDataMatricula' );
  this.oColunaDataMatricula.innerHTML = '<b>Data de Matrícula:</b>';

  /**
   * Label Aluno
   * @type {HTMLElement}
   */
  this.oLabelAluno = document.createElement( 'label' );
  this.oLabelAluno.addClassName( 'bold' );
  this.oLabelAluno.innerHTML = 'Aluno:';

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
  this.oLegendPeriodos.innerHTML = 'Avaliações Alternativas';

  /**
   * Div para apresentação da Grid
   * @type {HTMLElement}
   */
  this.oDivGrid = document.createElement( 'div' );
  this.oDivGrid.setAttribute( 'id', 'divGrid' );

  /**
   * Div do elemento botão
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
  this.oBotaoSalvar.setAttribute( 'id', 'btnSalvarAvaliacaoAlternativa' );
  this.oBotaoSalvar.setAttribute( 'type', 'button' );
  this.oBotaoSalvar.setAttribute( 'value', 'Salvar' );
  this.oBotaoSalvar.style.marginRight = '10px';

  this.oBotaoExcluir = document.createElement( 'input' );
  this.oBotaoExcluir.setAttribute( 'id', 'btnExcluirAvaliacaoAlternativa' );
  this.oBotaoExcluir.setAttribute( 'type', 'button' );
  this.oBotaoExcluir.setAttribute( 'value', 'Excluir' );
  this.oBotaoExcluir.setAttribute( 'disabled', 'disabled' );

  /**
   * Seta os filhos de cada elemento
   */
  this.oDivPrincipal.appendChild( this.oDivAlunos );
  this.oDivPrincipal.appendChild( this.oDivPeriodos );
  this.oDivAlunos.appendChild( this.oFieldsetPrincipal );

  this.oFieldsetPrincipal.appendChild( this.oLegendPrincipal );
  this.oFieldsetPrincipal.appendChild( this.oTabelaPrincipal );

  this.oTabelaPrincipal.appendChild( this.oLinhaAlunos );

  this.oLinhaAlunos.appendChild( this.oColunaLabelAlunos );
  this.oLinhaAlunos.appendChild( this.oColunaSelectAlunos );
  this.oLinhaAlunos.appendChild( this.oColunaDataMatricula );
  this.oColunaLabelAlunos.appendChild( this.oLabelAluno );

  this.oDivPeriodos.appendChild( this.oFielsetPeriodos );
  this.oDivPeriodos.appendChild( this.oDivBotao );
  this.oFielsetPeriodos.appendChild( this.oLegendPeriodos );
  this.oFielsetPeriodos.appendChild( this.oDivGrid );
  this.oDivBotao.appendChild( this.oBotaoSalvar );


  this.oDivBotao.appendChild( this.oBotaoExcluir );

};

DBViewFormularioEducacao.AvaliacaoAlternativa.prototype.setContainerPai = function( oWindow ) {
  this.oWindowContainer = oWindow;
};

DBViewFormularioEducacao.AvaliacaoAlternativa.prototype.setCallBackSalvar = function( fFunction ) {
  this.fCallBackSalvar = fFunction;
};

DBViewFormularioEducacao.AvaliacaoAlternativa.prototype.setCallBack = function( fFunction ) {
  this.fCallBackShutDown = fFunction;
};

DBViewFormularioEducacao.AvaliacaoAlternativa.prototype.criaJanela = function() {

  var oSelf          = this;
  var iTamanhoJanela = 800;
  var iAlturaJanela  = 440;

  this.oWindowAvaliacaoAlternativa = new windowAux( 'oWindowAvaliacaoAlternativa', 'Avaliações Alternativas', iTamanhoJanela, iAlturaJanela );

  var sMensagemTitulo = 'Configurar Avaliações Alternativas';
  var sMensagemAjuda   = 'Selecione a opção na qual o aluno se encaixa de acordo com a sua data de matrícula.';

  this.oWindowAvaliacaoAlternativa.setContent( this.oDivPrincipal );
  this.oWindowAvaliacaoAlternativa.setShutDownFunction( function () {

    oSelf.fCallBackShutDown();
    oSelf.oWindowAvaliacaoAlternativa.destroy();

    if (oSelf.lProcessouAluno) {
      oSelf.fCallBackSalvar();
    }
  });

  if( this.oWindowContainer != '' ) {
    oSelf.oWindowAvaliacaoAlternativa.setChildOf( this.oWindowContainer );
  }

  this.oMessageBoard = new DBMessageBoard(
                                           'messageBoardAvaliacaoAlternativa',
                                           sMensagemTitulo,
                                           sMensagemAjuda,
                                           this.oWindowAvaliacaoAlternativa.getContentContainer()
                                         );
  this.oWindowAvaliacaoAlternativa.show();

  this.montaComboAlunos();
  this.buscaPeriodosGrid();

  $('btnSalvarAvaliacaoAlternativa').onclick = function() {
    oSelf.salvar();
  };

  $('btnExcluirAvaliacaoAlternativa').onclick = function() {
    oSelf.excluir();
  };
};


DBViewFormularioEducacao.AvaliacaoAlternativa.prototype.montaComboAlunos = function() {

  var oSelf   = this;
  var fnChange = function() {

    oSelf.iAuxiliarAvaliacaoAlternativa    = null;
    oSelf.aAproveitamentosAlunoSelecionado = [];

    var aSelecionado = oSelf.oAlunos.getElementosSelecionados();

    $('colunaDataMatricula').innerHTML = "<b>Data de Matrícula: </b>" + aSelecionado[0].getAttribute( 'data_matricula' );

    oSelf.preencherGrid();
    if( !empty( aSelecionado[0].value ) ) {
      oSelf.validarAlunoSelecionado( aSelecionado[0].value );
    }
  };

  $('colunaSelectAlunos').innerHTML  = '';
  $('colunaDataMatricula').innerHTML = '<b>Data de Matrícula: </b>';
  $('btnExcluirAvaliacaoAlternativa').setAttribute('disabled', 'disabled');
  this.oAlunos = new DBViewAvaliacao.AlunoTurma(this.iTurma, this.iEtapa);
  this.oAlunos.lTrazerAlunosEncerrados = false;
  this.oAlunos.matriculaMaiorPrimeiroPeriodoCalendario(true);
  this.oAlunos.setLargura('100%');
  this.oAlunos.onChangeCallBack( fnChange );
  this.oAlunos.show( $('colunaSelectAlunos') );
};

DBViewFormularioEducacao.AvaliacaoAlternativa.prototype.validarAlunoSelecionado = function ( iMatriculaAluno ) {

  var oSelf                       = this;
  var oParametros                 = {};
      oParametros.exec            = 'verificarAvaliacaoAluno';
      oParametros.iMatriculaAluno = iMatriculaAluno;

  var oAjaxRequest = new AjaxRequest(this.sRpcAvaliacaoAlternativa, oParametros, function ( oRetorno, lErro ){

    if( lErro ) {

      alert( oRetorno.sMessage.urlDecode() );
      return false;
    }

    $('btnExcluirAvaliacaoAlternativa').setAttribute('disabled', 'disabled');

    oSelf.aAproveitamentosAlunoSelecionado = oRetorno.aAproveitamentosLancados;
    if (oRetorno.iAvaliacaoAlternativa != null) {

      $('btnExcluirAvaliacaoAlternativa').removeAttribute('disabled');

      $$('input[type="radio"].radio-avaliacao-alternativa').each(function (oElement) {

        if (oElement.value == oRetorno.iAvaliacaoAlternativa) {

          oElement.checked                    = true;
          oSelf.iAuxiliarAvaliacaoAlternativa = oRetorno.iAvaliacaoAlternativa;
        }
      });
    }

  });
  oAjaxRequest.setMessage( _M( MENSAGENS_AVALIACAO_ALTERNATIVA + 'buscando_avaliacoes_alternativas_aluno' ) );
  oAjaxRequest.execute();

};


DBViewFormularioEducacao.AvaliacaoAlternativa.prototype.buscaPeriodosGrid = function() {

  var oSelf              = this;
  var oParametros        = {};
      oParametros.exec   = 'buscarPeriodosAvaliacao';
      oParametros.iTurma = this.iTurma;
      oParametros.iEtapa = this.iEtapa;

  var oAjaxRequest = new AjaxRequest(this.sRpcAvaliacaoAlternativa, oParametros, function ( oRetorno, lErro ){

    if( lErro ) {

      alert( oRetorno.sMessage.urlDecode() );
      return false;
    }

    oSelf.aPeriodos = oRetorno.aPeriodosAvaliacao;
    oSelf.montaGridAvaliacoesAlternativas();

  });
  oAjaxRequest.setMessage( _M( MENSAGENS_AVALIACAO_ALTERNATIVA + 'buscando_avaliacoes_alternativas' ) );
  oAjaxRequest.execute();

};

DBViewFormularioEducacao.AvaliacaoAlternativa.prototype.montaGridAvaliacoesAlternativas = function() {

  this.oGridAvaliacoesAlternativas              = new DBGrid( 'oGridAvaliacoesAlternativas' );
  this.oGridAvaliacoesAlternativas.nameInstance = 'oInstanciaAvaliacaoAlternativa.oGridAvaliacoesAlternativas';
  this.aDescricaoPeriodos                       = [];

  var aCellsHeader = [],
      aCellsWidth  = [],
      aCellsAlign  = [];

  this.aPeriodos.forEach( function (oPeriodo) {

    aCellsHeader.push(oPeriodo.sDescricaoPeriodoAbreviado.urlDecode());
    aCellsAlign.push('left');
  });

  aCellsHeader.push('Opção');
  aCellsAlign.push('center');

  var iCelulas       = aCellsHeader.length,
      iTamanhoCelula = 100 / iCelulas  ;

  for ( var i = 0; i < iCelulas; i++ ) {
    aCellsWidth.push(iTamanhoCelula + '%')
  }

  this.oGridAvaliacoesAlternativas.setHeader( aCellsHeader );
  this.oGridAvaliacoesAlternativas.setCellAlign( aCellsAlign );
  this.oGridAvaliacoesAlternativas.setCellWidth( aCellsWidth );
  this.oGridAvaliacoesAlternativas.show( $('divGrid') );

  this.buscaAvaliacoesAlternativas();
};

DBViewFormularioEducacao.AvaliacaoAlternativa.prototype.buscaAvaliacoesAlternativas = function() {

  var oSelf              = this;
  var oParametros        = {};
      oParametros.exec   = 'buscarAvaliacoesAlternativas';
      oParametros.iTurma = this.iTurma;
      oParametros.iEtapa = this.iEtapa;

  var oAjaxRequest = new AjaxRequest(this.sRpcAvaliacaoAlternativa, oParametros, function ( oRetorno, lErro ){

    if( lErro ) {

      alert( oRetorno.sMessage.urlDecode() );
      return false;
    }

    if( oRetorno.aAvaliacoesAlternativas.length > 0 ) {

      oSelf.aAvaliacoesAlternativas = oRetorno.aAvaliacoesAlternativas;
      oSelf.preencherGrid();
    }
  });
  oAjaxRequest.setMessage( _M( MENSAGENS_AVALIACAO_ALTERNATIVA + 'buscando_avaliacoes_alternativas' ) );
  oAjaxRequest.execute();

};

DBViewFormularioEducacao.AvaliacaoAlternativa.prototype.preencherGrid = function() {

  var oSelf = this;

  oSelf.oGridAvaliacoesAlternativas.clearAll( true );
  oSelf.aAvaliacoesAlternativas.each(function( oAvaliacaoAlternativa ) {

    var oRadio = oSelf.createRadioButton(oAvaliacaoAlternativa.iCodigo);
    var aLinha = [];
    oAvaliacaoAlternativa.aConfiguracao.each( function (oRegra){

      var sValor = " Em Branco";
      if ( oRegra.sFormaAvaliacao != '') {
        sValor = oRegra.iMenorValor + " - " + oRegra.iMaiorValor;
      }

      aLinha.push(sValor);
    });

    aLinha.push(oRadio.outerHTML);

    oSelf.oGridAvaliacoesAlternativas.addRow( aLinha, true );
  });

  oSelf.oGridAvaliacoesAlternativas.renderRows();
};

DBViewFormularioEducacao.AvaliacaoAlternativa.prototype.createRadioButton = function( iCodigoAvaliacao ) {

  var oInput       = document.createElement('input');
  oInput.type      = 'radio';
  oInput.name      = 'avaliacao-alternativa';
  oInput.value     = iCodigoAvaliacao;
  oInput.className = 'radio-avaliacao-alternativa';

  return oInput;
};

DBViewFormularioEducacao.AvaliacaoAlternativa.prototype.salvar = function() {

  var oSelf                 = this;
  var aAlunoSelecionado     = this.oAlunos.getSelecionados();
  var iAvaliacaoAlternativa = '';

  if( empty( aAlunoSelecionado[0] ) ) {

    alert( _M( MENSAGENS_AVALIACAO_ALTERNATIVA + 'selecione_aluno' ) );
    return false;
  }

  $$('input[type="radio"]:checked.radio-avaliacao-alternativa').each(function (oElement){
    iAvaliacaoAlternativa = oElement.value;
  });

  if ( iAvaliacaoAlternativa == '' ) {
    alert( _M( MENSAGENS_AVALIACAO_ALTERNATIVA + 'selecione_avaliacao_alternativa' ) );
    return false;
  }

  var aMensagemConflito = this.validaConflitoNaAvaliacaoAlunoComAvaliacaoAlternativa(iAvaliacaoAlternativa);
  if (aMensagemConflito.length > 0) {

    var oMsgErro = { 'sDisciplinas' : aMensagemConflito.implode("\n") };
    alert(_M( MENSAGENS_AVALIACAO_ALTERNATIVA + "conflito_avaliacoe_x_alternativa", oMsgErro) );
    return;
  }

  var oParametros                       = {};
      oParametros.exec                  = 'salvar';
      oParametros.iMatricula            = aAlunoSelecionado[0];
      oParametros.iAvaliacaoAlternativa = iAvaliacaoAlternativa;

  var oAjaxRequest = new AjaxRequest(this.sRpcAvaliacaoAlternativa, oParametros, function ( oRetorno, lErro ){

    alert(oRetorno.sMessage.urlDecode());
    if ( lErro ) {
      return false;
    }

    oSelf.lProcessouAluno = true;
    oSelf.montaComboAlunos();
    oSelf.preencherGrid();
  });
  oAjaxRequest.setMessage( _M( MENSAGENS_AVALIACAO_ALTERNATIVA + 'salvar_avaliacoes_alternativas_aluno' ) );
  oAjaxRequest.execute();
}

DBViewFormularioEducacao.AvaliacaoAlternativa.prototype.validaConflitoNaAvaliacaoAlunoComAvaliacaoAlternativa = function (iAvaliacaoAlternativa) {

  var oSelf             = this;
  var aMensagemConflito = [];

  if ( oSelf.aAproveitamentosAlunoSelecionado.length == 0) {
    return [];
  }

  for (var i in oSelf.aAproveitamentosAlunoSelecionado) {

    var aPeriodosMensagem = [];
    oSelf.aAproveitamentosAlunoSelecionado[i].aPeriodos.forEach (function( oPeriodo ) {

      oSelf.aAvaliacoesAlternativas.each(function( oAvaliacaoAlternativa ) {

        if ( iAvaliacaoAlternativa != oAvaliacaoAlternativa.iCodigo ) {
          return;
        }
        oAvaliacaoAlternativa.aConfiguracao.forEach( function(oRegra){

          if (oPeriodo.iOrdemPeriodo != oRegra.iOrdemPeriodo ) {
            return;
          }

          if ( oRegra.sFormaAvaliacao == '' && oPeriodo.nAproveitamento != '') {
            aPeriodosMensagem.push(oPeriodo.sPeriodo.urlDecode());
          }
        });

      });
    });

    if ( aPeriodosMensagem.length > 0 ) {

      var sDisciplina = oSelf.aAproveitamentosAlunoSelecionado[i].sDisciplina.urlDecode();
      aMensagemConflito.push( sDisciplina +' - '+ aPeriodosMensagem.implode(' / ') );
    }
  }

  return aMensagemConflito;

};
DBViewFormularioEducacao.AvaliacaoAlternativa.prototype.excluir = function() {

  var oSelf              = this;
  var aAlunoSelecionado  = this.oAlunos.getSelecionados();

  if( empty( aAlunoSelecionado[0] ) ) {

    alert( _M( MENSAGENS_AVALIACAO_ALTERNATIVA + 'selecione_aluno' ) );
    return false;
  }

  if( !confirm( _M( MENSAGENS_AVALIACAO_ALTERNATIVA + 'confirma_exclusao_avaliacao_alternativa' ) ) ) {
    return false;
  }

  var oParametros            = {};
      oParametros.exec       = 'excluir';
      oParametros.iMatricula = aAlunoSelecionado[0];

  var oAjaxRequest = new AjaxRequest(this.sRpcAvaliacaoAlternativa, oParametros, function ( oRetorno, lErro ){

    alert(oRetorno.sMessage.urlDecode());
    if ( lErro ) {
      return false;
    }

    oSelf.lProcessouAluno = true;
    oSelf.montaComboAlunos();
    oSelf.preencherGrid();
  });
  oAjaxRequest.setMessage( _M( MENSAGENS_AVALIACAO_ALTERNATIVA + 'excluir_avaliacoes_alternativas_aluno' ) );
  oAjaxRequest.execute();
};

DBViewFormularioEducacao.AvaliacaoAlternativa.prototype.show = function() {
  this.criaJanela();
};
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

var MENSAGENS_DBVIEWMOTIVOSALTA = 'saude.ambulatorial.DBViewMotivosAlta.';

DBViewMotivosAlta = function() {

  /**
   * RPC utilizado para salvar e buscar os motivos de alta
   * @type {string}
   */
  this.sMotivoAltaRpc = "sau4_motivoalta.RPC.php";

  /**
   * Código do Prontuário
   * @type {int}
   */
  this.iProntuario = null;

  /**
   * Nome do paciente do prontuário
   * @type {string}
   */
  this.sNomePaciente = '';

  /**
   * Callback a ser executado após finalizar o atendimento
   * @type {function}
   */
  this.fCallbackSalvar = function(){};

  /**
   * Callback a ser executado ao fechar a window
   */
  this.fCallbackFechar = function(){};

  /**
   * Controla se o prontuário está finalizado
   * @type {bool}
   */
  this.lFinalizado = false;

  /**
   * Elemento DIV que contém todos os demais elementos
   * @type {HTMLElement}
   */
  this.oDivPrincipal       = document.createElement( 'div' );
  this.oDivPrincipal.addClassName( 'container' );

  /**
   * Elemento FIELDSET
   * @type {HTMLElement}
   */
  this.oFieldSetMotivoAlta = document.createElement( 'fieldset' );
  this.oFieldSetMotivoAlta.setAttribute( 'rel', 'ignore-css' );
  this.oFieldSetMotivoAlta.setStyle( { 'width' : '100%' } );

  /**
   * Elemento LEGEND referente ao fieldset do motivo da alta
   * @type {HTMLElement}
   */
  this.oLegendMotivoAlta           = document.createElement( 'legend' );
  this.oLegendMotivoAlta.innerHTML = 'Dados da Alta';

  /**
   * Elemento TABLE
   * @type {HTMLElement}
   */
  this.oTabelaPrincipal = document.createElement( 'table' );
  this.oTabelaPrincipal.addClassName( 'form-container' );

  /**
   * Elementos TR e TD referentes aos motivos de alta
   * @type {HTMLElement}
   */
  this.oLinhaMotivoAlta       = document.createElement( 'tr' );
  this.oColunaLabelMotivoAlta = document.createElement( 'td' );
  this.oColunaComboMotivoAlta = document.createElement( 'td' );

  /**
   * Elemento label do motivo da alta
   * @type {HTMLElement}
   */
  this.oLabelMotivoAlta = document.createElement( 'label' );
  this.oLabelMotivoAlta.setAttribute( 'for', 'oComboMotivoAlta' );
  this.oLabelMotivoAlta.innerHTML = 'Motivo de Alta:';

  /**
   * Combobox contendo os motivos de alta cadastrados no banco
   * @type {HTMLElement}
   */
  this.oComboMotivoAlta = document.createElement( 'select' );
  this.oComboMotivoAlta.setAttribute( 'id', 'oComboMotivoAlta' );

  /**
   * Elemento do botão Salvar, que finaliza o atendimento
   * @type {HTMLElement}
   */
  this.oBotaoSalvar     = document.createElement( 'input' );
  this.oBotaoSalvar.setAttribute( 'id', 'oBotaoSalvar' );
  this.oBotaoSalvar.setAttribute( 'type', 'button' );
  this.oBotaoSalvar.setAttribute( 'value', 'Salvar' );

  /**
   * Define os elementos filhos de cada
   */
  this.oDivPrincipal.appendChild( this.oFieldSetMotivoAlta );
  this.oFieldSetMotivoAlta.appendChild( this.oLegendMotivoAlta );
  this.oFieldSetMotivoAlta.appendChild( this.oTabelaPrincipal );
  this.oTabelaPrincipal.appendChild( this.oLinhaMotivoAlta );
  this.oLinhaMotivoAlta.appendChild( this.oColunaLabelMotivoAlta );
  this.oLinhaMotivoAlta.appendChild( this.oColunaComboMotivoAlta );
  this.oColunaLabelMotivoAlta.appendChild( this.oLabelMotivoAlta );
  this.oColunaComboMotivoAlta.appendChild( this.oComboMotivoAlta );
  this.oDivPrincipal.appendChild( this.oBotaoSalvar );
};

/**
 * Responsável por montar a janela, instanciando windowAux, DBMessageBoard
 */
DBViewMotivosAlta.prototype.criaJanela = function() {

  if( this.lFinalizado ) {
    return false;
  }

  var oSelf          = this;
  var iTamanhoJanela = 500;
  var iAlturaJanela  = 200;

  this.oWindowMotivosAlta = new windowAux( 'oWindowMotivosAlta', 'Motivos de Alta', iTamanhoJanela, iAlturaJanela );

  var sMensagemTitulo = 'Motivos de alta do paciente.';
  var sMensagemAjuda  = "Selecione um motivo de alta para finalizar o atendimento do paciente";
      sMensagemAjuda += " <label class='bold'>" + this.sNomePaciente + "</label>";

  this.oWindowMotivosAlta.setContent( this.oDivPrincipal );
  this.oWindowMotivosAlta.setShutDownFunction( function () {

    oSelf.oWindowMotivosAlta.destroy();
    oSelf.fCallbackFechar();
  });

  this.oMessageBoard = new DBMessageBoard(
                                           'messageBoardMotivosAlta',
                                           sMensagemTitulo,
                                           sMensagemAjuda,
                                           this.oWindowMotivosAlta.getContentContainer()
                                         );

  $('oBotaoSalvar').onclick = function() {
    oSelf.finalizarAtendimento();
  };

  this.oWindowMotivosAlta.show( null, null, true );
};

/**
 * Busca os motivos de alta cadastrados e que finalizam atendimento
 */
DBViewMotivosAlta.prototype.buscarMotivosAlta = function() {

  var oSelf = this;

  var oParametros                      = {};
      oParametros.sExecucao            = "buscaMotivosAlta";
      oParametros.lFinalizaAtendimento = true;

  var oDadosRequisicao            = {};
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json=' + Object.toJSON( oParametros );
      oDadosRequisicao.onComplete = function( oAjax ) {
        oSelf.retornoBuscarMotivosAlta( oAjax, oSelf );
      };

  js_divCarregando( _M( MENSAGENS_DBVIEWMOTIVOSALTA + 'buscando_motivos_alta' ), 'msgBoxA' );
  new Ajax.Request( this.sMotivoAltaRpc, oDadosRequisicao );
};

/**
 * Retorno dos motivos de alta, montando os mesmos no combo
 */
DBViewMotivosAlta.prototype.retornoBuscarMotivosAlta = function( oAjax, oSelf ) {

  js_removeObj( 'msgBoxA' );
  var oRetorno               = JSON.parse( oAjax.responseText );
  var iPosicaoElementoPadrao = null;

  oRetorno.aMotivosAlta.each( function( oMotivoAlta, iSeq ) {

    oSelf.oComboMotivoAlta.add( new Option( oMotivoAlta.sDescricao.urlDecode(), oMotivoAlta.iCodigo ) );

    /**
     * Código padrão do SUS para o motivo de alta 'ALTA MELHORADO'
     */
    if( oMotivoAlta.iCodigoSus == 12 ) {
      iPosicaoElementoPadrao = iSeq;
    }
  });

  oSelf.oComboMotivoAlta.options[ iPosicaoElementoPadrao ].selected = true;
};

/**
 * Busca os dados do prontuário setado
 */
DBViewMotivosAlta.prototype.buscaDadosProntuario = function() {

  var oSelf = this;

  var oParametros             = {};
      oParametros.sExecucao   = 'buscaDadosProntuario';
      oParametros.iProntuario = this.iProntuario;

  var oDadosRequisicao              = {};
      oDadosRequisicao.method       = 'post';
      oDadosRequisicao.parameters   = 'json=' + Object.toJSON( oParametros );
      oDadosRequisicao.asynchronous = false;
      oDadosRequisicao.onComplete   = function( oAjax ) {
        oSelf.retornoBuscaDadosProntuario( oAjax, oSelf );
      };

  js_divCarregando( _M( MENSAGENS_DBVIEWMOTIVOSALTA + 'buscando_dados_prontuario' ), 'msgBoxB' );
  new Ajax.Request( this.sMotivoAltaRpc, oDadosRequisicao );
};

/**
 * Retorna o nome do paciente.
 * Caso o prontuário esteja finalizado, apresenta a mensagem e fecha a window
 */
DBViewMotivosAlta.prototype.retornoBuscaDadosProntuario = function( oAjax, oSelf ) {

  js_removeObj( 'msgBoxB' );
  var oRetorno = JSON.parse( oAjax.responseText );

  oSelf.sNomePaciente = oRetorno.sNomePaciente.urlDecode();

  if( oRetorno.iStatus == 2 ) {

    alert( oRetorno.sMensagem.urlDecode() );
    oSelf.lFinalizado = true;
    return false;
  }

  oSelf.buscarMotivosAlta();
};

/**
 * Finaliza o atendimento, informando o motivo da alta
 */
DBViewMotivosAlta.prototype.finalizarAtendimento = function() {

  var oSelf       = this;
  var oMotivoAlta = this.motivoAltaSelecionado();

  if( empty( oMotivoAlta.value ) ) {

    alert( _M( MENSAGENS_DBVIEWMOTIVOSALTA + 'selecione_motivo_alta' ) );
    return false;
  }

  var oParametros             = {};
      oParametros.sExecucao   = "finalizaAtendimento";
      oParametros.iMotivoAlta = oMotivoAlta.value;
      oParametros.iProntuario = this.iProntuario;

  var oDadosRequisicao            = {};
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json=' + Object.toJSON( oParametros );
      oDadosRequisicao.onComplete = function( oAjax ) {
        oSelf.retornoFinalizarAtendimento( oAjax, oSelf );
      };

  js_divCarregando( _M( MENSAGENS_DBVIEWMOTIVOSALTA + 'finalizando_atendimento' ), 'msgBoxC' );
  new Ajax.Request( this.sMotivoAltaRpc, oDadosRequisicao );
};

/**
 * Retorna se o atendimento foi finalizado com sucesso, fechando a window e executando o callback setado
 */
DBViewMotivosAlta.prototype.retornoFinalizarAtendimento = function( oAjax, oSelf ) {

  js_removeObj( 'msgBoxC' );

  var oRetorno = JSON.parse( oAjax.responseText );

  alert( oRetorno.sMensagem.urlDecode() );

  if ( oRetorno.iStatus == 1 ) {

    oSelf.oWindowMotivosAlta.destroy();
    oSelf.fCallbackSalvar();
  }
};

/**
 * Busca o motivo de alta selecionado, retornando o elemento OPTION referente ao mesmo
 * @returns {HTMLElement}
 */
DBViewMotivosAlta.prototype.motivoAltaSelecionado = function() {

  var oElemento    = null;
  var iTotalOpcoes = this.oComboMotivoAlta.length;

  for ( var iContador = 0; iContador < iTotalOpcoes; iContador++ ) {

    if( this.oComboMotivoAlta.options[iContador].selected ) {
      oElemento = this.oComboMotivoAlta.options[iContador];
    }
  }

  return oElemento;
};

/**
 * Define um código de prontuário
 * @param {int} iProntuario
 */
DBViewMotivosAlta.prototype.setProntuario = function( iProntuario ) {
  this.iProntuario = iProntuario;
};

/**
 * Define um callback a ser executado após salvar
 * @param fFunction
 */
DBViewMotivosAlta.prototype.setCallbackSalvar = function( fFunction ) {
  this.fCallbackSalvar = fFunction;
};

/**
 * Define um callback a ser executado ao fechar a window
 * @param fFunction
 */
DBViewMotivosAlta.prototype.setCallbackFechar = function( fFunction ) {
  this.fCallbackFechar = fFunction;
};

/**
 * Responsável por executar a chamada dos dados do prontuário, quando definido, e criação da window
 */
DBViewMotivosAlta.prototype.show = function() {

  if( !empty( this.iProntuario ) ) {
    this.buscaDadosProntuario();
  }

  this.criaJanela();
};
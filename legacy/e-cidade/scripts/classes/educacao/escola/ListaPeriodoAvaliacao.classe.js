require_once("scripts/classes/educacao/DBViewFormularioEducacao.classe.js");
require_once("scripts/strings.js");

/**
 * Constantes para o tipo de período a ser pesquisado e do arquivo de mensagens
 */
const AVALIACAO_RESULTADO               = 1;
const AVALIACAO                         = 2;
const RESULTADO                         = 3;
const MENSAGENS_LISTA_PERIODO_AVALIACAO = 'educacao.escola.ListaPeriodoAvaliacao.';

/**
 *
 * @constructor
 */
DBViewFormularioEducacao.ListaPeriodoAvaliacao = function() {

  /**
   * RPC
   * @type {string}
   */
  this.sRpc = 'edu4_turmas.RPC.php';

  /**
   * Função de callback após carregar o combo
   * @returns {boolean}
   */
  this.fCallBackLoad = function() {
    return true;
  }

  /**
   * Função de callback ao alterar a opção do combo
   * @returns {boolean}
   */
  this.fCallBackChange = function() {
    return true;
  }

  /**
   * Elemento do tipo
   * @type {HTMLElement}
   */
  this.oElemento             = document.createElement( 'select' );
  this.oElemento.style.width = '100%';
  this.oElemento.id          = 'cboPeriodoAvaliacao';
  this.oElemento.add( new Option( 'Selecione um período', '' ) );

  this.lSomenteCargaHoraria = false;
};

/**
 * Seta uma função de callback ao carregar os dados do combo
 * @param fCallBackLoad
 */
DBViewFormularioEducacao.ListaPeriodoAvaliacao.prototype.setCallBackLoad = function( fCallBackLoad ) {
  this.fCallBackLoad = fCallBackLoad;
};

/**
 * Seta uma função de callback ao alterar a opção
 * @param fCallBackChange
 */
DBViewFormularioEducacao.ListaPeriodoAvaliacao.prototype.setCallBackChange = function( fCallBackChange ) {
  this.fCallBackChange = fCallBackChange;
};

/**
 * Busca os períodos de acordo com a turma e etapa informados, setando também o tipo de busca
 * @param iTurma
 * @param iEtapa
 * @param sTipoRetornoPeriodo
 *        AVALIACAO_RESULTADO = 1
 *        AVALIACAO           = 2
 *        RESULTADO           = 3
 */
DBViewFormularioEducacao.ListaPeriodoAvaliacao.prototype.getPeriodos = function( iTurma, iEtapa, sTipoRetornoPeriodo ) {

  var oSelf = this;

  var oParametros                      = new Object();
      oParametros.exec                 = 'pesquisaPeriodosTurma';
      oParametros.iTurma               = iTurma;
      oParametros.iEtapa               = iEtapa;
      oParametros.iTipoBusca           = sTipoRetornoPeriodo;
      oParametros.lSomenteCargaHoraria = this.lSomenteCargaHoraria;

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json=' + Object.toJSON( oParametros );
      oDadosRequisicao.onComplete = function( oResponse ) {
        oSelf.retornoPeriodos( oResponse, oSelf );
      }

  js_divCarregando( _M( MENSAGENS_LISTA_PERIODO_AVALIACAO + "buscando_periodos" ), "msgBox" );
  new Ajax.Request( this.sRpc, oDadosRequisicao );
};

/**
 * Retorno da busca dos períodos para preenchimento do combo
 * @param oResponse
 * @param oSelf
 */
DBViewFormularioEducacao.ListaPeriodoAvaliacao.prototype.retornoPeriodos = function( oResponse, oSelf ) {

  js_removeObj( "msgBox" );
  var oRetorno = eval( '(' + oResponse.responseText + ')' );

  this.limpaElemento();

  if ( oRetorno.aPeriodos.length == 0 ) {

    alert( MENSAGENS_LISTA_PERIODO_AVALIACAO + "nenhum_periodo" );
    return false;
  }


  oRetorno.aPeriodos.each(function( oPeriodo ) {

    var oOption = new Option( oPeriodo.sDescricao.urlDecode(), oPeriodo.iPeriodo );
    oOption.setAttribute('resultado', oPeriodo.lResultado);
    oSelf.oElemento.add( oOption );
  });

  if ( oRetorno.aPeriodos.length == 1 ) {
    oSelf.oElemento.value = oRetorno.aPeriodos[0].iPeriodo;
  }

  this.oElemento.onchange = this.fCallBackChange;
  this.fCallBackLoad();
};

/**
 * Informa se devem ser pesquisados somente períodos que calculam carga horária
 * @param {boolean} lSomenteCargaHoraria
 */
DBViewFormularioEducacao.ListaPeriodoAvaliacao.prototype.somentePeriodoCalculaCargaHoraria = function( lSomenteCargaHoraria ) {
  this.lSomenteCargaHoraria = lSomenteCargaHoraria;
};

/**
 * Retorna o item selecionado no combo
 */
DBViewFormularioEducacao.ListaPeriodoAvaliacao.prototype.getSelecionado = function() {

  var iSelecionado                 = this.oElemento.selectedIndex;
  var oDadosSelecionado            = new Object();
      oDadosSelecionado.iCodigo    = this.oElemento.options[iSelecionado].value;
      oDadosSelecionado.sDescricao = this.oElemento.options[iSelecionado].innerHTML;

  return oDadosSelecionado;
};

/**
 * Limpa o combo e seta o valor padrão
 */
DBViewFormularioEducacao.ListaPeriodoAvaliacao.prototype.limpaElemento = function() {

  this.oElemento.length = 0;
  this.oElemento.add( new Option( 'Selecione um período', '' ) );
};

/**
 * Cria o elemento select dentro do elemento pai informado
 * @param oElementoPai
 */
DBViewFormularioEducacao.ListaPeriodoAvaliacao.prototype.show = function( oElementoPai ) {
  oElementoPai.appendChild( this.oElemento );
};
/**
 * Classe para apresentação da árvore da turma, e vínculo de pareceres com estas turmas
 * @param aDisciplinas
 * @constructor
 * @author  Fábio Esteves <fabio.esteves@dbseller.com.br>
 * @package educacao
 */
const MENSAGEM_PARECER_TURMA = 'educacao.escola.DBViewParecerTurma.';

DBViewParecerTurma = function( iParecer, aDisciplinas ) {

  this.sRPC                = 'edu4_parecer.RPC.php';
  this.aDisciplinas        = [];

  if (aDisciplinas != "") {
    this.aDisciplinas        = aDisciplinas.split(",");
  }
  this.iParecer            = iParecer;
  this.oElementoPrincipal  = null;
  this.oArvoreTurmaParecer = null;
};

/**
 * ******************************
 * Monta a estrutura HTML da tela
 * ******************************
 */
DBViewParecerTurma.prototype.montaHTML = function() {

  var oSelf = this;

  /**
   * Atributos da div da arvore
   * @type {HTMLElement}
   */
  var oAtributosDivArvore    = new Object();
      oAtributosDivArvore.id = 'divArvoreTurma';

  /**
   * Atributos do elemento fieldset
   * @type {Object}
   */
  var oAtributosFieldSet       = new Object();
      oAtributosFieldSet.style = 'width: 50%; height: 50%;';

  /**
   * Atributos do elemento input 'Vincular'
   * @type {Object}
   */
  var oAtributosBotao       = new Object();
      oAtributosBotao.id    = 'vinvularTurma';
      oAtributosBotao.type  = 'button';
      oAtributosBotao.value = 'Salvar';
      oAtributosBotao.style = 'margin-left: 25%;';

  /**
   * Criação dos elementos HTML
   * @type {HTMLElement}
   */
  this.oDivArvore = new Element( 'div', oAtributosDivArvore );
  this.oFieldset  = new Element( "fieldset", oAtributosFieldSet );
  this.oLegend    = new Element( "legend" ).update( "Turmas" );
  this.oBotao     = new Element( "input", oAtributosBotao );

  /**
   * Seta onde os elementos devem ser apresentados
   */
  this.oFieldset.appendChild( this.oLegend );
  this.oFieldset.appendChild( this.oDivArvore );
  this.oElementoPrincipal.appendChild( this.oFieldset );
  this.oElementoPrincipal.appendChild( this.oBotao );

  this.oBotao.onclick = function() {
    oSelf.vincularParecer();
  }
};

/**
 * ****************************
 * Monta a árvore com as turmas
 * ****************************
 */
DBViewParecerTurma.prototype.montaArvore = function() {

  var oSelf = this;

  this.oArvoreTurmaParecer = new DBViewArvoreTurma( 'viewArvoreTurmaParecer' );
  this.oArvoreTurmaParecer.lTurmasProgressaoParcial = false;
  this.oArvoreTurmaParecer.temAlunosMatriculados(false);
  this.oArvoreTurmaParecer.setCheckBox(true, this.oArvoreTurmaParecer.fCallBackCheckBox);
  this.aDisciplinas.each(function( iDisciplina ) {
    oSelf.oArvoreTurmaParecer.adicionarDisciplina( iDisciplina );
  });

  this.buscarVinculos();
  this.oArvoreTurmaParecer.expandirNoPaiSelecionado( true );
  this.oArvoreTurmaParecer.show( $('divArvoreTurma') );
};

/**
 * ****************************************
 * Retorna as turmas selecionadas na árvore
 * @returns {Array}
 * ****************************************
 */
DBViewParecerTurma.prototype.buscaSelecionados = function() {

  var oSelf         = this;
  var aSelecionados = new Array();

  this.oArvoreTurmaParecer.oTreeViewAvaliacao.getNodesChecked().each(function( oNode ) {

    if( oNode.checkbox.checked && !oNode.checkbox.disabled && oNode.lProcessa ) {
      aSelecionados.push( oNode.iTurma );
    }
  });

  return aSelecionados;
};

/**
 * *************************************
 * Víncula uma ou mais turmas ao parecer
 * *************************************
 */
DBViewParecerTurma.prototype.vincularParecer = function() {

  var oSelf               = this;
  var oParametros         = new Object();
      oParametros.exec    = 'vincularTurma';
      oParametros.iCodigo = this.iParecer;
      oParametros.aTurmas = this.buscaSelecionados();

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json=' + Object.toJSON( oParametros );
      oDadosRequisicao.onComplete = function( oResponse ) {
                                                            oSelf.retornoVinculoParecer( oResponse, oSelf );
                                                          }

  js_divCarregando( _M( MENSAGEM_PARECER_TURMA + "vinculando_turmas" ), "msgBox" );
  new Ajax.Request( this.sRPC, oDadosRequisicao );
};

/**
 * ******************************************
 * Retorno do vínculo das turmas a um parecer
 * @param oResponse
 * @param oSelf
 * ******************************************
 */
DBViewParecerTurma.prototype.retornoVinculoParecer = function( oResponse, oSelf ) {

  js_removeObj( "msgBox" );
  var oRetorno = JSON.parse( oResponse.responseText );

  alert( oRetorno.message.urlDecode() );
};

/**
 * *************************************
 * Busca as turmas vinculadas ao parecer
 * *************************************
 */
DBViewParecerTurma.prototype.buscarVinculos = function() {

  var oSelf               = this;
  var oParametros         = new Object();
      oParametros.exec    = 'buscarParecer';
      oParametros.iCodigo = this.iParecer;

  var oDadosRequisicao              = new Object();
      oDadosRequisicao.method       = 'post';
      oDadosRequisicao.parameters   = 'json=' + Object.toJSON( oParametros );
      oDadosRequisicao.asynchronous = false;
      oDadosRequisicao.onComplete   = function( oResponse ) {
                                                              oSelf.retornoBuscaVinculos( oResponse, oSelf );
                                                            }

  js_divCarregando( _M( MENSAGEM_PARECER_TURMA + "buscando_vinculos" ), "msgBox" );
  new Ajax.Request( this.sRPC, oDadosRequisicao );
};

/**
 * **************************************
 * Retorno da busca das turmas vinculadas
 * @param oResponse
 * @param oSelf
 * **************************************
 */
DBViewParecerTurma.prototype.retornoBuscaVinculos = function( oResponse, oSelf ) {

  js_removeObj( "msgBox" );
  var oRetorno = JSON.parse( oResponse.responseText );

  this.oArvoreTurmaParecer.setCallbackMarcarTurma(function( oTurma, oEtapa ) {

    var lMarcarTurma = false;

    oRetorno.aTurmasVinculadas.each(function( iTurma ) {

      if( oTurma.iTurma == iTurma ) {
        lMarcarTurma = true;
      }
    });

    return lMarcarTurma;
  });
};

/**
 * ************************************************************************************************************
 * Recebe o elemento onde deve ser carregada a classe, setando o mesmo e chamando os métodos que montam o HTML
 * e a árvore
 * @param oElemento
 * ************************************************************************************************************
 */
DBViewParecerTurma.prototype.show = function( oElemento ) {

  this.oElementoPrincipal = oElemento;
  this.montaHTML();
  this.montaArvore();
};
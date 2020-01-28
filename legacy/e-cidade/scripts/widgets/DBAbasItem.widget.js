/**
 * Constroi um componente aba para compor o componente DBAbas
 *
 * @author Rafael Nery
 * @author Alberto Ferri
 * @version  $Revision: 1.5 $
 *
 * @constructor
 * @return void
 */
var DBAbasItem = function() {

  var oSelf      = this;
  this.sNome     = null;
  this.sId       = null;

  var oGrupoAbas = null;
  /**
   * O Elemento Seletor da Aba
   */
  var oElementoSeletor = null;

  /**
   * Se true bloqueia aba
   */
  this.lBloqueada = false;

  /**
   * O Elemento que guardará o conteudo da aba
   */
  var oElementoConteudo = null;

  /**
   * Nome da função do callback
   */
  this.fCallback = null;

  /**
   * Seta o callback após clicar na aba
   */
  this.setCallback = function ( oFunction ) {
    this.fCallback = oFunction;
  }

  /**
   * Retorna o Elemento com o Seletor da Aba
   */
  this.getSeletor = function () {
    return oElementoSeletor;
  };

  /**
   * Bloqueia a aba
   */
  this.bloquear = function () {

    this.lBloqueada = true;
    return this;
  }

  /**
   * Desbloqueia a aba
   */
  this.desbloquear = function() {

    this.lBloqueada = false;
    return this;
  }

  /**
   * Define o elemento seletor
   */
  this.setElementoSeletor = function ( oElemento ) {
    oElementoSeletor = oElemento;
  }

  /**
   * Define o elemento seletor
   */
  this.setElementoConteudo = function ( oElemento ) {
    oElementoConteudo = oElemento;
  }

  /**
   * Retorna o Elemento onde o conteúdo será exibido
   */
  this.getConteudo = function () {
    return oElementoConteudo;
  };

  /*
   * Define o objeto HTMLElement que será o conteúdo da aba
   */
  this.setConteudo = function ( oConteudo ) {

    oElementoConteudo.innerHTML = "";
    oElementoConteudo.appendChild( oConteudo );
  };

  this.setGrupo  = function( oGrupo ) {

    if ( !(oGrupo instanceof DBAbas) ) {
      throw "Objeto não é do Componente de Abas";
    }
    oGrupoAbas = oGrupo;
  };

  this.getGrupo = function () {
    return oGrupoAbas;
  };

  /**
   * Define o label da Aba
   */
  this.setNome = function (sNome) {
    oSelf.sNome = sNome;
  };

  /**
   * Define o id da Aba
   */
  this.setId = function (sId) {
    oSelf.sId = sId;
  };

  this.show = function () {
    oSelf.criarElementos();
  };

};

/**
 * Cria elementos Básicos para Criação do Componente
 */
DBAbasItem.prototype.criarElementos = function () {

  var Self = this;
  oElementoSeletor           = document.createElement('span');
  oElementoSeletor.id        = this.sId;
  oElementoSeletor.innerHTML = this.sNome;

  oElementoSeletor.classList.add('aba');

  oElementoSeletor.onclick   = function () {

    if (Self.lBloqueada) {
       return false;
    }

    Self.getGrupo().mostraFilho( Self );

    if (Self.fCallback) {
      Self.fCallback();
    }
  };

  oElementoConteudo           = document.createElement('div');
  oElementoConteudo.classList.add('abaInativaConteudo');


  this.setElementoSeletor ( oElementoSeletor  );
  this.setElementoConteudo( oElementoConteudo );

};

/**
 * Define a Visibilidade da Aba e Seu conteudo
 */
DBAbasItem.prototype.setVisibilidade = function( lVisivel ) {

  var oElementoSeletor = this.getSeletor();
  var oElementoConteudo= this.getConteudo();

  oElementoSeletor.classList.remove('abaAtiva');


  if ( lVisivel ) {
    oElementoSeletor.classList.add('abaAtiva');
    oElementoConteudo.classList.remove('abaInativaConteudo');
    return true;
  }
  oElementoSeletor.classList.add('aba');
  oElementoConteudo.classList.add('abaInativaConteudo');
  return false;

};

/** 
 * @fileoverview Define um objeto do tipo DBAbasItem
 *
 * @author Rafael Nery
 * @author Alberto Ferri
 * @version  $Revision: 1.2 $
 *
 * Constroi um componente aba para compor o componente DBAbas 
 * @class DBAbasItem
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
   * Retorna o Elemento com o Seletor da Aba
   */
  this.getSeletor = function () {
    return oElementoSeletor;    
  };
  
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
  
  oElementoSeletor.className = 'aba';

  oElementoSeletor.onclick   = function () {
    
    if (Self.lBloqueada) {
       return false; 
    }
    Self.getGrupo().mostraFilho( Self );
  };
 
  oElementoConteudo           = document.createElement('div');
  oElementoConteudo.className = 'abaInativaConteudo';
  
  
  this.setElementoSeletor ( oElementoSeletor  );
  this.setElementoConteudo( oElementoConteudo );
  
};

/**
 * Define a Visibilidade da Aba e Seu conteudo
 */
DBAbasItem.prototype.setVisibilidade = function( lVisivel ) {
  
  var oElementoSeletor = this.getSeletor();
  var oElementoConteudo= this.getConteudo();
  
  if ( lVisivel ) {
    oElementoSeletor.className  = 'abaAtiva';
    oElementoConteudo.className = '';
    return true;
  }
  oElementoSeletor.className  = 'aba';
  oElementoConteudo.className = 'abaInativaConteudo';
  return false;
  
};

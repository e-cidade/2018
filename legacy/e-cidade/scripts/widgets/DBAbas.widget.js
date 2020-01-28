require_once('scripts/widgets/DBAbasItem.widget.js');
require_once('estilos/DBtab.style.css');
/**
 * Constroi um componente para organizar abas de conteúdo
 *
 * @author Rafael Nery
 * @author Alberto Ferri
 * @version  $Revision: 1.9 $
 *
 * @constructor
 * @param {HTMLElement} Onde será exibida a aba
 * @requires DBAbasItem
 * @example
 *  <body>
 *    <div id="ctnAbas"></div>
 *    <div id='aba1'>
 *      conteúdo da aba
 *    </div>
 *    <div id='aba2' >
 *      conteúdo da aba
 *    </div>
 *  </body>
 *  <scripts>
 *    var oAbas = new DBAbas($('ctnAbas'));
 *    var oAba1 = oAbas.adicionarAba('Aba 1', $('aba1'));
 *    var oAba2 = oAbas.adicionarAba('Aba 2', $('aba2'));
 *    oAba2.bloquear();
 *    oAba2.desbloquear();
 *
 *  </scripts>
 */
var DBAbas = function ( oContainerDestino ) {

  var oSelf  = this;

  /**
   * HTMLElement onde sera montada a aba
   */
  this.oContainerDestino = oContainerDestino;

  /**
   * Div com componente abas
   */
  this.oContainerPrincipal;

  /**
   * Div com os seletores de abas
   */
  this.oContainerSeletores;

  /**
   * Div com o conteudo de cada aba
   */
  this.oContainerConteudo;

  /**
   * Vetor que armazena objetos das abas
   */
  this.aAbas = new Array();

  this.mostraFilho = function( oItemFilho ) {

    if ( !( oItemFilho instanceof DBAbasItem ) ) {
      throw "Objeto informado não é uma Aba";
    }

    for ( var sNomeFilho in oSelf.aAbas ) {


      if ( !(oSelf.aAbas[sNomeFilho] instanceof DBAbasItem ) ) {
        continue;
      }

      var oAbaItem = oSelf.aAbas[sNomeFilho];

      if ( oAbaItem == oItemFilho ) {
        oAbaItem.setVisibilidade(true);
        continue;
      }
      oAbaItem.setVisibilidade(false);
    }

  }

  oSelf.criarElementosHTML();
};

/**
 * Cria uma nova aba no componente
 * @param sNome {string} Nome da aba
 * @param oConteudo {HTMLElement} Elemento que será renderizado na aba
 * @param lAtiva {boolean} Define se a aba será ativada
 */
DBAbas.prototype.adicionarAba = function (sNome, oConteudo, lAtiva) {

  var oAba = new DBAbasItem();

  oAba.setGrupo( this );
  oAba.setNome( sNome );
  oAba.setId( sNome );
  oAba.show();
  oAba.setConteudo( oConteudo );

  if (this.aAbas.length == 0) {
    lAtiva = true;
  }

  this.aAbas.push(oAba);

  if (lAtiva) {
    this.mostraFilho(oAba);
  }

  var oObjetoSeletor  = oAba.getSeletor();
  var oObjetoConteudo = oAba.getConteudo();

  this.oContainerSeletores.appendChild( oObjetoSeletor  );
  this.oContainerConteudo .appendChild( oObjetoConteudo );

  return oAba;
};

/**
 * Cria estrutura padrão das abas
 */
DBAbas.prototype.criarElementosHTML = function () {

  this.oContainerPrincipal           = document.createElement('div');
  this.oContainerPrincipal.classList.add("containerPrincipal");
  this.oContainerSeletores           = document.createElement('div');
  this.oContainerConteudo            = document.createElement('div');
  this.oContainerConteudo.classList.add("containerConteudo");

  this.oContainerPrincipal.appendChild(this.oContainerSeletores);
  this.oContainerPrincipal.appendChild(this.oContainerConteudo);

  this.oContainerDestino.appendChild(this.oContainerPrincipal);
  return true;
};

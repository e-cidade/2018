 /*
  * @fileoverview Esse arquivo cria um componente semelhante ao DBAncora
  * 
  * passamos o rotulo da ancora e o fonte que sera direcionado
  *
  * @author Rafael Lopes rafael.lopes@dbseller.com.br
  * @author Rafael Nery  rafael.nery@dbseller.com.br
  * @version  $Revision: 1.7 $
  */

DBAncora = function ( sLabel, sURL, lHabilitado ) {
	
  if (lHabilitado === undefined ) {
  	lHabilitado = true;
  } 

  if (!sURL) {
    sURL = "javascript: void(0);";
  }
  
  this.lHabilitado = lHabilitado;
	
  this.funcaoClique = function(){};
  
  this.oElementos = new Object();
  
  this.getLabel = function() {
    return sLabel;  
  };
  
  this.getUrl = function() {
    return sURL;
  };

};

/**
 * Cria Elementos HTML
 * @returns void
 */
DBAncora.prototype.criarElementos = function() {
  
  var me = this;

  this.oElementos.oTexto = document.createTextNode( this.getLabel() );
  this.oElementos.oLink  = document.createElement("a");
  this.oElementos.oLink.setAttribute('class', 'DBAncora') ;
  
  if (!me.lHabilitado) {
    this.oElementos.oLink.setAttribute("onclick", "");
    this.oElementos.oLink.setAttribute("style", "text-decoration: none; color: #000;");
  }
  
  this.oElementos.oLink.onclick = this.funcaoClique;
  
  this.oElementos.oLink.href = this.getUrl();
  this.oElementos.oLink.sUrl = this.getUrl();

  this.oElementos.oLink.appendChild(this.oElementos.oTexto);
  return this.oElementos.oLink;
};

/**
 * Mostra o DBAncora
 * @returns void
 */
DBAncora.prototype.show = function( oElemento ) {
  
  oElemento.appendChild( this.criarElementos() );
  return;
};

/**
 * Define Comportamento a Ser Executado ao Clicar no Link
 * @param fFuncao
 */
DBAncora.prototype.onClick = function (fFuncao) {
	
  this.funcaoClique = fFuncao;
  if (this.oElementos.oLink) {
    this.oElementos.oLink.onclick = this.funcaoClique;
  }
  return;
};

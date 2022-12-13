require_once('scripts/widgets/dbtextField.widget.js');
require_once('scripts/widgets/DBAncora.widget.js');
require_once('scripts/classes/DBViewFormularioFolha/DBViewFormularioFolha.classe.js');
/**
 * PesquisaCargo
 *
 */
DBViewFormularioFolha.PesquisaCargo = function( iInstituicao ) {

  oSelf                   = this;
  this.iInstituicao       = iInstituicao;
  this.iCodigoInstancia   = DBViewFormularioFolha.PesquisaCargo.addInstance(this);

  this.oAncora            = new DBAncora("Cargo:");
  this.oAncora.onClick(function() {
    oSelf.abrirLookUp(true);
  });
  this.oInputCargo     = new DBTextField("rh37_funcao");
  this.oInputCargo.getElement();
  
  this.oInputNomeCargo = new DBTextField("rh37_descr");
  this.oInputNomeCargo.setReadOnly(true);

  this.oBotaoEnvio        = document.createElement("input");
  this.oBotaoEnvio.type   = "button";
  this.oBotaoEnvio.value  = "Pesquisar";
  return;
};


DBViewFormularioFolha.PesquisaCargo.prototype.setCallBackDados = function( fCallBack ) {

  this.fCallBackDados = fCallBack;
  return;
}

/**
 * Adiciona o componente da Ancora
 *
 * @param {HTMLElement} oElemento
 */
DBViewFormularioFolha.PesquisaCargo.prototype.fixarAncora  = function( oElemento ) {

  /**
   * Caso a ancora fora renderizada, apenas move o Elemento.
   **/
  if ( this.lAncoraFixada ) {

    oElemento.appendChild(  this.oAncora.getElemento() );
    return;
  }
  this.oAncora.show( oElemento );
  this.lAncoraFixada = true;
  return;
};

/**
 * Adiciona o componente do código do Cargo
 *
 * @param {HTMLElement} oElemento
 * @param {Integer}     iCodigoCargo
 */
DBViewFormularioFolha.PesquisaCargo.prototype.fixarCargo = function(oElemento, iCodigoCargo) {

  if ( !!iCodigoCargo ) {
    this.oInputCargo.setValue(iCargo);
  }
  this.oInputCargo.getElement().addClassName("field-size1");
  this.oInputCargo.show(oElemento, true);
  this.oInputCargo.getElement().onchange = function() {
    oSelf.abrirLookUp(false);
  };
  return;
};

/**
 * Adiciona o componente do Nome do Cargo
 *
 * @param {HTMLElement} oElemento
 * @param {Integer}     iCodigoCargo
 */
DBViewFormularioFolha.PesquisaCargo.prototype.fixarNomeCargo = function( oElemento, sNomeCargo ) {

  if ( !!sNomeCargo ) {
    this.oInputNomeCargo.setValue(sNomeCargo);
  }
  this.oInputNomeCargo.getElement().addClassName("field-size7");
  this.oInputNomeCargo.show(oElemento, true);
  return;
};

/**
 * Adiciona o componente do Nome do Cargo
 *
 * @param {HTMLElement} oElemento
 * @param {Function}    fCallBack
 */
DBViewFormularioFolha.PesquisaCargo.prototype.fixarBotao = function( oElemento, fCallBack ) {

  if ( !fCallBack ) {
    fCallBack = function(){};
  }
  oElemento.appendChild(this.oBotaoEnvio);
  this.oBotaoEnvio.onclick = fCallBack;
  return;
};

DBViewFormularioFolha.PesquisaCargo.prototype.abrirLookUp = function( lExibeJanela ) {

  var sDestino        = "top.corpo";      
  var sLabelJanela    = "Pesquisa de Cargo";;
  var sNomeObjeto     = "db_iframe_rhfuncao";
  var sFuncaoPesquisa = "";
  var sQueryString    = "";
  var sUrlDestino     = "func_rhfuncao.php?";

  if ( lExibeJanela === true ) {
    /**
     * Ao clicar na Ancora
     */
    sDestino        = "top.corpo";
    sLabelJanela    = "Pesquisa de Cargo";
    sNomeObjeto     = "db_iframe_rhfuncao";
    sFuncaoPesquisa = "parent.DBViewFormularioFolha.PesquisaCargo.getInstance(" + this.iCodigoInstancia + ").retornoPesquisa|rh37_funcao|rh37_descr";
    sQueryString    = "funcao_js=" + escape(sFuncaoPesquisa);
    sQueryString   += "&instit="   + this.iInstituicao;
    sUrlDestino    += sQueryString;
    js_OpenJanelaIframe(sDestino, sNomeObjeto, sUrlDestino, sLabelJanela, lExibeJanela);

    return;
  }

  /**
   * Caso seja Digitação
   */
  if ( this.oInputCargo.getValue() == '' ) {

    this.oInputNomeCargo.setValue('');
    return;
  }

  sFuncaoPesquisa = "parent.DBViewFormularioFolha.PesquisaCargo.getInstance(" + this.iCodigoInstancia + ").retornoPesquisaDigitada";
  sQueryString    = "funcao_js="          + escape(sFuncaoPesquisa);
  sQueryString   += "&pesquisa_chave="    + this.oInputCargo.getValue();
  sQueryString   += "&instit="            + this.iInstituicao;
  sUrlDestino    += sQueryString;
  js_OpenJanelaIframe(sDestino, sNomeObjeto, sUrlDestino, sLabelJanela, lExibeJanela);
  return;
};

/**
 * Retorna a pesquisa quando for pela ancora
 */

DBViewFormularioFolha.PesquisaCargo.prototype.retornoPesquisa= function() {

  var sNomeCargo       = '';
  var iCargo           = '';

  sNomeCargo           = arguments[1];
  iCargo               = arguments[0];

  this.oInputCargo.getElement().value = iCargo;
  this.oInputNomeCargo.setValue(sNomeCargo);
  db_iframe_rhfuncao.hide();
  
  return;
};

/**
 * Retorna a pesquisa quando for digitado código
 */
DBViewFormularioFolha.PesquisaCargo.prototype.retornoPesquisaDigitada= function() {

  var sNomeCargo = '';
  var lErro      = '';

  sNomeCargo     = arguments[0] ;
  lErro          = arguments[1] ;

   this.oInputNomeCargo.setValue(sNomeCargo);

   if (lErro) {
      this.oInputCargo.getElement().setValue('');
    }
    return;
};
/**
 * Repositório das Instancias
 */
DBViewFormularioFolha.PesquisaCargo.oInstances  = DBViewFormularioFolha.PesquisaCargo.oInstances || {};
DBViewFormularioFolha.PesquisaCargo.iCounter    = DBViewFormularioFolha.PesquisaCargo.iCounter   || 0;
DBViewFormularioFolha.PesquisaCargo.addInstance = function( oPesquisaCargo ) { 

  var iNumeroInstancia = DBViewFormularioFolha.PesquisaCargo.iCounter++;
  DBViewFormularioFolha.PesquisaCargo.oInstances['PesquisaCargo' + iNumeroInstancia] = oPesquisaCargo;
  return iNumeroInstancia;
}

DBViewFormularioFolha.PesquisaCargo.getInstance = function( iInstancia ) { 
  return DBViewFormularioFolha.PesquisaCargo.oInstances['PesquisaCargo' + iInstancia];
};

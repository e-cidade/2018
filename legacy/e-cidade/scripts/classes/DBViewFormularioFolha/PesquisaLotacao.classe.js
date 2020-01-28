require_once('scripts/widgets/dbtextField.widget.js');
require_once('scripts/widgets/DBAncora.widget.js');
require_once('scripts/classes/DBViewFormularioFolha/DBViewFormularioFolha.classe.js');
/**
 * PesquisaLotacao
 *
 */
DBViewFormularioFolha.PesquisaLotacao = function( iInstituicao ) {

  oSelf                   = this;
  this.iInstituicao       = iInstituicao;
  this.iCodigoInstancia   = DBViewFormularioFolha.PesquisaLotacao.addInstance(this);

  this.oAncora            = new DBAncora("Lotação:");
  this.oAncora.onClick(function() {
    oSelf.abrirLookUp(true);
  });
  this.oInputLotacao     = new DBTextField("r70_codigo");
  this.oInputLotacao.getElement();
  
  this.oInputNomeLotacao = new DBTextField("r70_descr");
  this.oInputNomeLotacao.setReadOnly(true);

  this.oBotaoEnvio        = document.createElement("input");
  this.oBotaoEnvio.type   = "button";
  this.oBotaoEnvio.value  = "Pesquisar";
  return;
};


DBViewFormularioFolha.PesquisaLotacao.prototype.setCallBackDados = function( fCallBack ) {

  this.fCallBackDados = fCallBack;
  return;
}

/**
 * Adiciona o componente da Ancora
 *
 * @param {HTMLElement} oElemento
 */
DBViewFormularioFolha.PesquisaLotacao.prototype.fixarAncora  = function( oElemento ) {

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
 * Adiciona o componente do código do Lotacao
 *
 * @param {HTMLElement} oElemento
 * @param {Integer}     iCodigoLotacao
 */
DBViewFormularioFolha.PesquisaLotacao.prototype.fixarLotacao = function(oElemento, iCodigoLotacao) {

  if ( !!iCodigoLotacao ) {
    this.oInputLotacao.setValue(iLotacao);
  }
  this.oInputLotacao.getElement().addClassName("field-size1");
  this.oInputLotacao.show(oElemento, true);
  this.oInputLotacao.getElement().onchange = function() {
    oSelf.abrirLookUp(false);
  };
  return;
};

/**
 * Adiciona o componente do Nome do Lotacao
 *
 * @param {HTMLElement} oElemento
 * @param {Integer}     iCodigoLotacao
 */
DBViewFormularioFolha.PesquisaLotacao.prototype.fixarNomeLotacao = function( oElemento, sNomeLotacao ) {

  if ( !!sNomeLotacao ) {
    this.oInputNomeLotacao.setValue(sNomeLotacao);
  }
  this.oInputNomeLotacao.getElement().addClassName("field-size7");
  this.oInputNomeLotacao.show(oElemento, true);
  return;
};

/**
 * Adiciona o componente do Nome do Lotacao
 *
 * @param {HTMLElement} oElemento
 * @param {Function}    fCallBack
 */
DBViewFormularioFolha.PesquisaLotacao.prototype.fixarBotao = function( oElemento, fCallBack ) {

  if ( !fCallBack ) {
    fCallBack = function(){};
  }
  oElemento.appendChild(this.oBotaoEnvio);
  this.oBotaoEnvio.onclick = fCallBack;
  return;
};

DBViewFormularioFolha.PesquisaLotacao.prototype.abrirLookUp = function( lExibeJanela ) {

  var sDestino        = "top.corpo";      
  var sLabelJanela    = "Pesquisa de Lotacao";;
  var sNomeObjeto     = "db_iframe_rhlota";
  var sFuncaoPesquisa = "";
  var sQueryString    = "";
  var sUrlDestino     = "func_rhlota.php?";

  if ( lExibeJanela === true ) {
    /**
     * Ao clicar na Ancora
     */
    sDestino        = "top.corpo";
    sLabelJanela    = "Pesquisa de Lotacao";
    sNomeObjeto     = "db_iframe_rhlota";
    sFuncaoPesquisa = "parent.DBViewFormularioFolha.PesquisaLotacao.getInstance(" + this.iCodigoInstancia + ").retornoPesquisa|r70_codigo|r70_descr";
    sQueryString    = "funcao_js=" + escape(sFuncaoPesquisa);
    sQueryString   += "&instit="   + this.iInstituicao;
    sUrlDestino    += sQueryString;
    js_OpenJanelaIframe(sDestino, sNomeObjeto, sUrlDestino, sLabelJanela, lExibeJanela);

    return;
  }

  /**
   * Caso seja Digitação
   */
  if ( this.oInputLotacao.getValue() == '' ) {

    this.oInputNomeLotacao.setValue('');
    return;
  }

  sFuncaoPesquisa = "parent.DBViewFormularioFolha.PesquisaLotacao.getInstance(" + this.iCodigoInstancia + ").retornoPesquisaDigitada";
  sQueryString    = "funcao_js="          + escape(sFuncaoPesquisa);
  sQueryString   += "&pesquisa_chave="    + this.oInputLotacao.getValue();
  sQueryString   += "&instit="            + this.iInstituicao;
  sUrlDestino    += sQueryString;
  js_OpenJanelaIframe(sDestino, sNomeObjeto, sUrlDestino, sLabelJanela, lExibeJanela);
  return;
};

/**
 * Retorna a pesquisa quando for pela ancora
 */

DBViewFormularioFolha.PesquisaLotacao.prototype.retornoPesquisa= function() {

  var sNomeLotacao       = '';
  var iLotacao           = '';

  sNomeLotacao           = arguments[1];
  iLotacao               = arguments[0];

  this.oInputLotacao.getElement().value = iLotacao;
  this.oInputNomeLotacao.setValue(sNomeLotacao);
  db_iframe_rhlota.hide();
  
  return;
};

/**
 * Retorna a pesquisa quando for digitado código
 */
DBViewFormularioFolha.PesquisaLotacao.prototype.retornoPesquisaDigitada= function() {

  var sNomeLotacao = '';
  var lErro        = '';    
  
  sNomeLotacao     = arguments[0] ;
  lErro            = arguments[1] ;

   this.oInputNomeLotacao.setValue(sNomeLotacao);

   if (lErro) {
      this.oInputLotacao.getElement().setValue('');
    }
    return;
};
/**
 * Repositório das Instancias
 */
DBViewFormularioFolha.PesquisaLotacao.oInstances  = DBViewFormularioFolha.PesquisaLotacao.oInstances || {};
DBViewFormularioFolha.PesquisaLotacao.iCounter    = DBViewFormularioFolha.PesquisaLotacao.iCounter   || 0;
DBViewFormularioFolha.PesquisaLotacao.addInstance = function( oPesquisaLotacao ) { 

  var iNumeroInstancia = DBViewFormularioFolha.PesquisaLotacao.iCounter++;
  DBViewFormularioFolha.PesquisaLotacao.oInstances['PesquisaLotacao' + iNumeroInstancia] = oPesquisaLotacao;
  return iNumeroInstancia;
}

DBViewFormularioFolha.PesquisaLotacao.getInstance = function( iInstancia ) { 
  return DBViewFormularioFolha.PesquisaLotacao.oInstances['PesquisaLotacao' + iInstancia];
};

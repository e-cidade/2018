require_once('scripts/widgets/dbtextField.widget.js');
require_once('scripts/widgets/DBAncora.widget.js');
require_once('scripts/classes/DBViewFormularioFolha/DBViewFormularioFolha.classe.js');
/**
 * PesquisaServidores
 *
 */
DBViewFormularioFolha.PesquisaServidores = function( iInstituicao ) {

  oSelf                   = this;
  this.iInstituicao       = iInstituicao;
  this.iCodigoInstancia   = DBViewFormularioFolha.PesquisaServidores.addInstance(this);

  this.oAncora            = new DBAncora("Matrícula:");
  this.oAncora.onClick(function() {
    oSelf.abrirLookUp(true);
  });
  this.oInputMatricula    = new DBTextField("rh01_regist");
  this.oInputMatricula.getElement();
  
  this.oInputNomeServidor = new DBTextField("z01_nome");
  this.oInputNomeServidor.setReadOnly(true);

  this.oBotaoEnvio        = document.createElement("input");
  this.oBotaoEnvio.type   = "button";
  this.oBotaoEnvio.value  = "Pesquisar";
  return;
};


DBViewFormularioFolha.PesquisaServidores.prototype.setCallBackDados = function( fCallBack ) {

  this.fCallBackDados = fCallBack;
  return;
}

/**
 * Adiciona o componente da Ancora
 *
 * @param {HTMLElement} oElemento
 */
DBViewFormularioFolha.PesquisaServidores.prototype.fixarAncora  = function( oElemento ) {

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
 * Adiciona o componente do código do Servidor
 *
 * @param {HTMLElement} oElemento
 * @param {Integer}     iCodigoServidor
 */
DBViewFormularioFolha.PesquisaServidores.prototype.fixarMatricula = function(oElemento, iCodigoServidor) {

  if ( !!iCodigoServidor ) {
    this.oInputMatricula.setValue(iMatricula);
  }
  this.oInputMatricula.getElement().addClassName("field-size2");
  this.oInputMatricula.show(oElemento, true);
  this.oInputMatricula.getElement().onchange = function() {
    oSelf.abrirLookUp(false);
  };
  return;
};

/**
 * Adiciona o componente do Nome do Servidor
 *
 * @param {HTMLElement} oElemento
 * @param {Integer}     iCodigoServidor
 */
DBViewFormularioFolha.PesquisaServidores.prototype.fixarNomeServidor = function( oElemento, sNomeServidor ) {

  if ( !!sNomeServidor ) {
    this.oInputNomeServidor.setValue(sNomeServidor);
  }
  this.oInputNomeServidor.getElement().addClassName("field-size7");
  this.oInputNomeServidor.show(oElemento, true);
  return;
};

/**
 * Adiciona o componente do Nome do Servidor
 *
 * @param {HTMLElement} oElemento
 * @param {Function}    fCallBack
 */
DBViewFormularioFolha.PesquisaServidores.prototype.fixarBotao = function( oElemento, fCallBack ) {

  if ( !fCallBack ) {
    fCallBack = function(){};
  }
  oElemento.appendChild(this.oBotaoEnvio);
  this.oBotaoEnvio.onclick = fCallBack;
  return;
};

DBViewFormularioFolha.PesquisaServidores.prototype.abrirLookUp = function( lExibeJanela ) {

  var sDestino        = "top.corpo";             ;
  var sLabelJanela    = "Pesquisa de Servidores";;
  var sNomeObjeto     = "db_iframe_rhpessoal";   ;
  var sFuncaoPesquisa = "";
  var sQueryString    = "";
  var sUrlDestino     = "func_rhpessoal.php?";

  if ( lExibeJanela === true ) {
    /**
     * Ao clicar na Ancora
     */
    sDestino        = "top.corpo";
    sLabelJanela    = "Pesquisa de Servidores";
    sNomeObjeto     = "db_iframe_rhpessoal";
    sFuncaoPesquisa = "parent.DBViewFormularioFolha.PesquisaServidores.getInstance(" + this.iCodigoInstancia + ").retornoPesquisa|rh01_regist|z01_nome|r45_dtafas|r45_dtreto";
    sQueryString    = "funcao_js=" + escape(sFuncaoPesquisa);
    sQueryString   += "&instit="   + this.iInstituicao;
    sUrlDestino    += sQueryString;
    js_OpenJanelaIframe(sDestino, sNomeObjeto, sUrlDestino, sLabelJanela, lExibeJanela);

    return;
  }

  /**
   * Caso seja Digitação
   */
  if ( this.oInputMatricula.getValue() == '' ) {

    this.oInputNomeServidor.setValue('');
    return;
  }

  sFuncaoPesquisa = "parent.DBViewFormularioFolha.PesquisaServidores.getInstance(" + this.iCodigoInstancia + ").retornoPesquisa";
  sQueryString    = "funcao_js="          + escape(sFuncaoPesquisa);
  sQueryString   += "&pesquisa_chave="    + this.oInputMatricula.getValue();
  sQueryString   += "&instit="            + this.iInstituicao;
  sUrlDestino    += sQueryString;
  js_OpenJanelaIframe(sDestino, sNomeObjeto, sUrlDestino, sLabelJanela, lExibeJanela);
  return;
};

DBViewFormularioFolha.PesquisaServidores.prototype.retornoPesquisa= function() {

  var sNomeServidor    = '';
  var iMatricula       = '';
  var sDataAfastamento = '';
  var sDataRetorno     = '';

  if ( arguments.length == 4 && arguments[1] !== false ) { // RETORNO LOOKUP DE PESQUISA

    sNomeServidor    = arguments[1];
    iMatricula       = arguments[0];
    sDataAfastamento = arguments[2];
    sDataRetorno     = arguments[3];
    this.oInputMatricula.getElement().value = iMatricula;
    this.oInputNomeServidor.setValue(sNomeServidor);
    db_iframe_rhpessoal.hide();
    return;
  } else {// Retorno Digitacao

    sNomeServidor = arguments[0] ;
    lErro         = arguments[1] ;

    this.oInputNomeServidor.setValue(sNomeServidor);

    if (lErro) {
      this.oInputMatricula.getElement().setValue('');
    }
    return;
  }
};
/**
 * Repositório das Instancias
 */
DBViewFormularioFolha.PesquisaServidores.oInstances  = DBViewFormularioFolha.PesquisaServidores.oInstances || {};
DBViewFormularioFolha.PesquisaServidores.iCounter    = DBViewFormularioFolha.PesquisaServidores.iCounter   || 0;
DBViewFormularioFolha.PesquisaServidores.addInstance = function( oPesquisaServidores ) { 

  var iNumeroInstancia = DBViewFormularioFolha.PesquisaServidores.iCounter++;
  DBViewFormularioFolha.PesquisaServidores.oInstances['PesquisaServidores' + iNumeroInstancia] = oPesquisaServidores;
  return iNumeroInstancia;
}

DBViewFormularioFolha.PesquisaServidores.getInstance = function( iInstancia ) { 
  return DBViewFormularioFolha.PesquisaServidores.oInstances['PesquisaServidores' + iInstancia];
};

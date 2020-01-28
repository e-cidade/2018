/**
 * Classe para organização de itens de menu
 * @param oDestino - Container onde será carregado o componente
 * @returns {DBViewMenus}
 */
DBViewMenus = function( oDestino ) {
  
  /**
   * RPC com as chamadas da View
   * @var {string}
   */
  this.sRpc = 'sys4_itensmenus.RPC.php';

  /**
   * Caminho das mensagens apresentadas no componente
   */
  this.sCaminhoMensagens = 'configuracao.configuracao.DBViewMenus.';
  
  /**
   * Local onde será carregada a classe
   */
  this.oDestino = oDestino;
  
  /**
   * Item de menu selecionado
   */
  this.iItemMenu = null;
  
  /**
   * Guarda a instancia de DBTreeView
   */
  this.oArvoreMenus = null;

  /**
   * Array com os menus pai que devem ser expandidos
   * @type {Array}
   */
  this.aMenusExpand = new Array();
  
  /**
   * Elemento select que recebe os módulos existentes
   * @type {Object}
   */
  this.oSelectModulos    = document.createElement( 'select' );
  this.oSelectModulos.id = 'selectModulos';
  this.oSelectModulos.add( new Option( "Selecione", 0 ) );

  /**
   * Menus que um item está vinculado
   * @type {Array}
   */
  this.aMenusVinculados = new Array();

  /**
   * Código do módulo que o menu está vinculado
   * @type {integer}
   */
  this.iModuloVinculado = null;
};

/**
 * *******************************************************************
 * Cria a janela dentro do container em que a mesma deve ser carregada
 * *******************************************************************
 */
DBViewMenus.prototype.criaJanela = function() {
  
  var oSelf = this;

  /**
   * Variável que armazena o conteúdo HTML
   */
  var sConteudo  = '<div id="ctnMenus" class="container">';
      sConteudo += '  <fieldset style="width: 100%;">';
      sConteudo += '    <legend>Módulo</legend>';
      sConteudo += '    <table>';
      sConteudo += '      <tr>';
      sConteudo += '        <td><label class="bold">Selecione o módulo:</label></td>';
      sConteudo += '        <td id="ctnSelectModulos"></td>';
      sConteudo += '      </tr>';
      sConteudo += '    </table>';
      sConteudo += '  </fieldset>';
      sConteudo += '</div>';
      sConteudo += '<div id="ctnFieldSetArvore">';
      sConteudo += '  <fieldset style="width: 50%;">';
      sConteudo += '    <legend class="bold">Itens de Menu</legend>';
      sConteudo += '    <div id="ctnArvore"></div>';
      sConteudo += '  </fieldset>';
      sConteudo += '</div>';
      sConteudo += '<div class="center">';
      sConteudo += '  <input id="btnSalvar" type="button" value="Salvar">';
      sConteudo += '</div>';

  this.oDestino.innerHTML = sConteudo;
  this.criaInstanciaTreeView();
  
  $('ctnSelectModulos').appendChild( this.oSelectModulos );
  
  this.oSelectModulos.onchange = function() {
    oSelf.buscaItensFilhos();
  };

  $('btnSalvar').onclick = function() {
    oSelf.salvaVinculo();
  };
  
  /**
   * Ajustes no estilo das div's
   */
  $('ctnFieldSetArvore').style.paddingLeft = document.body.clientWidth / 3.5;
  $('ctnArvore').style.width               = document.body.clientWidth / 2.5;
  $('ctnArvore').style.height              = document.body.clientHeight / 2;
};

/**
 * *********************************************************************************
 * Cria a instacia de DBTreeView, limpando os elementos nos quais ela será carregada
 * *********************************************************************************
 */
DBViewMenus.prototype.criaInstanciaTreeView = function() {
  
  this.oArvoreMenus = null;
  
  if ( $('arvoreMenus') != null ) {
    $('arvoreMenus').innerHTML = '';
  }
  
  if ( $('ctnArvore') != null ) {
    $('ctnArvore').innerHTML = '';
  }

  this.oArvoreMenus = new DBTreeView( 'arvoreMenus' );
  this.oArvoreMenus.allowFind( true );
  this.oArvoreMenus.setFindOptions( 'matchedonly' );
  this.oArvoreMenus.show( $('ctnArvore') );
};

/**
 * ********************************************************
 * Busca os módulos existentes para preenchimento do select
 * ********************************************************
 */
DBViewMenus.prototype.buscaModulos = function() {
  
  var oSelf                = this;
  var oParametro           = new Object();
      oParametro.sExecucao = 'buscaModulos';
      
  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json=' + Object.toJSON( oParametro );
      oDadosRequisicao.onComplete = function( oResponse ) { oSelf.retornoBuscaModulos( oResponse, oSelf ); };

  js_divCarregando( _M( this.sCaminhoMensagens + "buscando_modulos" ), "msgBox" );
  new Ajax.Request( this.sRpc, oDadosRequisicao );
};

/**
 * **************************************************
 * Retorna os módulos encontrados e preenche o select
 * @param oResposta
 * @param oSelf
 * **************************************************
 */
DBViewMenus.prototype.retornoBuscaModulos = function( oResponse, oSelf ) {

  js_removeObj( "msgBox" );
  var oRetorno = eval( '(' + oResponse.responseText + ')' );

  if ( oRetorno.iStatus != 1 ) {

    alert( oRetorno.sMensagem.urlDecode() );
    return;
  }

  oRetorno.aModulos.each(function( oModulo, iSeq ) {

    oSelf.oSelectModulos.add( new Option( oModulo.sNome.urlDecode(), oModulo.iCodigo ) );

    /**
     * Caso o menu já possua vínculo, e exista apenas 1 vínculo, seta o módulo como selecionado e busca os itens filho
     */
    if ( !empty( oSelf.iModuloVinculado ) && oModulo.iCodigo == oSelf.iModuloVinculado ) {

      oSelf.oSelectModulos.options[ iSeq + 1 ].selected = true;
      oSelf.buscaItensFilhos();
    }
  });
};

/**
 * *************************************************
 * Busca os itens filho do módulo e menu selecionado
 * *************************************************
 */
DBViewMenus.prototype.buscaItensFilhos = function() {
  
  var oSelf                = this;
  var oParametro           = new Object();
      oParametro.sExecucao = 'buscaMenusFilhos';
      oParametro.iModulo   = this.oSelectModulos.value;
      
  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json=' + Object.toJSON( oParametro );
      oDadosRequisicao.onComplete = function( oResponse ) { oSelf.retornoItensFilhos( oResponse, oSelf ); };

  js_divCarregando( _M( this.sCaminhoMensagens + "buscando_itens_filhos" ), "msgBox" );
  new Ajax.Request( this.sRpc, oDadosRequisicao );
};

/**
 * ****************************************************
 * Retorno os itens filhos do módulo e menu selecionado
 * @param oResponse
 * @param oSelf
 * ****************************************************
 */
DBViewMenus.prototype.retornoItensFilhos = function( oResponse, oSelf ) {
  
  js_removeObj( "msgBox" );
  var oSelf    = this;
  var oRetorno = eval( '(' + oResponse.responseText + ')' );

  if ( oRetorno.iStatus != 1 ) {

    alert( oRetorno.sMensagem.urlDecode() );
    return;
  }

  this.aMenusExpand.length = 0;
  this.menusVinculados();
  this.criaInstanciaTreeView();

  /**
   * Objeto que guarda o código do módulo para criação do primeiro nó ( Menu Principal ), a descrição e um array com os
   * menus principais
   * @type {Object}
   */
  var oDadosNoPrincipal            = new Object();
      oDadosNoPrincipal.iCodigo    = this.oSelectModulos.value;
      oDadosNoPrincipal.sDescricao = "Menu Principal";
      oDadosNoPrincipal.aFilhos    = oRetorno.aMenus;

  /**
   * Cria o objeto checkbox principal
   * @type {Object}
   */
  var oCheckBoxPrincipal          = new Object();
      oCheckBoxPrincipal.checked  = oSelf.comparaMenu( oDadosNoPrincipal );
      oCheckBoxPrincipal.disabled = false;

  /**
   * Cria o nó Menu Principal
   */
  oSelf.oArvoreMenus.addNode( this.oSelectModulos.value, "Menu Principal", '0', '', '', oCheckBoxPrincipal );

  /**
   * Envia os dados do nó principal, e monta a árvore a partir deste
   */
  oSelf.montaMenusFilho( oDadosNoPrincipal );

  /**
   * Expande os nós pai dos itens marcados
   */
  oSelf.aMenusExpand.each(function( oItem ) {
    oItem.expand( null, true );
  });
};

/**
 * ********************************************************************************************************************
 * Monta os menus filhos do principal, validando se estes mesmos menus possuem filhos, chamando a função recursivamente
 * @param oDadosMenu - Objeto com os dados do menu a ter o nó criado
 * @param oCheckBox  - Objeto do checkbox
 * ********************************************************************************************************************
 */
DBViewMenus.prototype.montaMenusFilho = function( oDadosMenu ) {

  var oSelf = this;

  if ( oDadosMenu.aFilhos.length > 0 ) {

    oDadosMenu.aFilhos.each(function( oDadosMenuFilho, iSeq ) {

      /**
       * Cria o objeto checkbox dos menus filhos. Caso o código do menu selecionado seja o mesmo do menu percorrido,
       * desabilita o nó para evitar o vínculo deste com ele mesmo
       */
      var oCheckBoxFilho          = new Object();
          oCheckBoxFilho.checked  = oSelf.comparaMenu( oDadosMenuFilho );
          oCheckBoxFilho.disabled = $('inputCodigo').value == oDadosMenuFilho.iCodigo;

      var oNodeFilho = oSelf.oArvoreMenus.addNode(
                                                   oDadosMenuFilho.iCodigo,
                                                   oDadosMenuFilho.sDescricao.urlDecode(),
                                                   oDadosMenu.iCodigo,
                                                   '',
                                                   '',
                                                   oCheckBoxFilho
                                                 );

      /**
       * Caso o item esteja marcado, guarda o nó pai para expandir ao final da construção da árvore
       */
      if ( oCheckBoxFilho.checked ) {
        oSelf.aMenusExpand.push( oNodeFilho.parentNode );
      }

      if ( oDadosMenuFilho.aFilhos.length > 0 ) {
        oSelf.montaMenusFilho( oDadosMenuFilho );
      }
    });
  }
};

/**
 * ***************************************************************************************************
 * Compara os menus que estariam vinculados com o nó da árvore passado por parâmetro, marcando o mesmo
 * @param oNo
 * @returns {boolean}
 * ***************************************************************************************************
 */
DBViewMenus.prototype.comparaMenu = function( oNo ) {

  var lMenuEncontrado = false;
  var oSelf           = this;
  if ( oSelf.aMenusVinculados.length > 0 ) {

    oSelf.aMenusVinculados.each(function( oDadosVinculo ) {

      if ( oNo.iCodigo == oDadosVinculo.iMenu ) {
        lMenuEncontrado = true;
      }
    });
  }

  return lMenuEncontrado;
};

/**
 * *****************************************************************************************
 * Salva o(s) vínculo(s) do item de menu selecionado
 * Passa como parâmetro o código do item de menu, o módulo e os menus selecionados na árvore
 * *****************************************************************************************
 */
DBViewMenus.prototype.salvaVinculo = function() {

  var oSelf                        = this;
  var oParametros                  = new Object();
      oParametros.sExecucao        = 'salvarVinculo';
      oParametros.iMenu            = $('inputCodigo').value;
      oParametros.iModulo          = oSelf.oSelectModulos.value;
      oParametros.aNosSelecionados = new Array();

  oSelf.oArvoreMenus.getNodesChecked().each(function( oNo, iSeq ) {
    oParametros.aNosSelecionados.push( oNo.value );
  });

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json=' + Object.toJSON( oParametros );
      oDadosRequisicao.onComplete = function( oResponse ) { oSelf.retornoSalvaVinculo( oResponse, oSelf ); };

  js_divCarregando( _M( this.sCaminhoMensagens + "salvando_vinculos" ), "msgBox" );
  new Ajax.Request( this.sRpc, oDadosRequisicao );
};

/**
 * ***************************
 * Retorno dos vínculos salvos
 * @param oResponse
 * @param oSelf
 * ***************************
 */
DBViewMenus.prototype.retornoSalvaVinculo = function( oResponse, oSelf ) {

  js_removeObj( "msgBox" );
  var oRetorno = eval( '(' + oResponse.responseText + ')' );

  alert( oRetorno.sMensagem.urlDecode() );

  if ( oRetorno.iStatus == 1 ) {
    this.buscaItensFilhos();
  }
};

/**
 * ****************************************************************
 * Busca os menus vinculados ao item de menu e módulos selecionados
 * ****************************************************************
 */
DBViewMenus.prototype.menusVinculados = function() {

  var oSelf                = this;
  var oParametro           = new Object();
      oParametro.sExecucao = 'menusVinculados';
      oParametro.iModulo   = this.oSelectModulos.value;
      oParametro.iMenu     = $('inputCodigo').value;

  var oDadosRequisicao              = new Object();
      oDadosRequisicao.method       = 'post';
      oDadosRequisicao.parameters   = 'json=' + Object.toJSON( oParametro );
      oDadosRequisicao.asynchronous = false;
      oDadosRequisicao.onComplete   = function( oResponse ) { oSelf.retornoMenusVinculados( oResponse, oSelf ); };

  js_divCarregando( _M( this.sCaminhoMensagens + "buscando_menus_vinculados" ), "msgBox" );
  new Ajax.Request( this.sRpc, oDadosRequisicao );
};

/**
 * *************************************
 * Retorno da busca dos menus vinculados
 * @param oResponse
 * @param oSelf
 * *************************************
 */
DBViewMenus.prototype.retornoMenusVinculados = function( oResponse, oSelf ) {

  js_removeObj( "msgBox" );
  var oSelf    = this;
  var oRetorno = eval( '(' + oResponse.responseText + ')' );

  if ( oRetorno.iStatus != 1 ) {

    alert( oRetorno.sMensagem.urlDecode() );
    return;
  }

  this.aMenusVinculados = oRetorno.aMenus;
};

/**
 * ********************************
 * Retorna o código do item de menu
 * @returns integer
 * ********************************
 */
DBViewMenus.prototype.getItemMenu = function() {
  return this.iItemMenu;
};

/**
 * *****************************
 * Seta o código do item de menu
 * @param iItemMenu
 * *****************************
 */
DBViewMenus.prototype.setItemMenu = function( iItemMenu ) {
  this.iItemMenu = iItemMenu;
};

/**
 * *************************************************
 * Seta o código do módulo que o menu está vinculado
 * @param {integer} iModuloVinculado
 * *************************************************
 */
DBViewMenus.prototype.setModuloVinculado = function( iModuloVinculado ) {
  this.iModuloVinculado = iModuloVinculado;
};

/**
 * ***********************
 * Limpa o elemento select
 * ***********************
 */
DBViewMenus.prototype.limpaSelect = function() {

  var iTamanho = this.oSelectModulos.options.length;

  for ( var iContador = 0; iContador < iTamanho; iContador++ ) {
    this.oSelectModulos.options.remove( iContador );
  }

  this.oSelectModulos.add( new Option( "Selecione", 0 ) );
};

/**
 * *************************************************************************
 * Chama as funções iniciais a serem executadas após instanciar o componente
 * *************************************************************************
 */
DBViewMenus.prototype.show = function() {
  
  this.criaJanela();
  this.limpaSelect();
  this.buscaModulos();
};
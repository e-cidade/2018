require_once("scripts/strings.js");
require_once("scripts/widgets/dbtextField.widget.js");
require_once("scripts/datagrid.widget.js");
require_once("scripts/widgets/DBAncora.widget.js");

/** 
 * @fileoverview Esse arquivo cria um componente semelhante ao cl_arquivoauxiliar
 *               no qual passamos campos de pesquisa e ele possibilita lançalos numa grid
 *
 * @author   Rafael Lopes rafael.lopes@dbseller.com.br
 * @author   Rafael Nery  rafael.nery@dbseller.com.br
 * @version  $Revision: 1.16 $
 * @example - 
 * var oLancador = new DBLancador('Lancador');   
 *     oLancador.setNomeInstancia('oLancador');  
 *     oLancador.setLabelAncora('Testando: ');   
 *     oLancador.setParametrosPesquisa('func_cflicita.php', ['rh55_codigo','rh55_descr'], "lRetornaEstrutural=true");
 *     oLancador.show($('ctnLancador'));
 */
DBLancador = function( sName ) {
  
  this.sName               = sName;
  this.nameInstance        = '';
  this.oElementos          = new Object(); 
  this.sTextoFieldset      = 'Adicionar Registros';
  this.oRegistros           = new Object();
  var me                   = this;
  var sInstancia           = '';
  var sAncora              = 'Pesquisa: ';
  var sQueryPesquisaAncora = '';
  this.iTipoValidacao      = 1;
  this.iGridHeight         = 300;
  this.aParametros         = new Array();
  this.lHabilitado         = true;
  this.oDadosPesquisa = { sFontePesquisa   : "",
                          aCamposRetorno   : "",
                          sStringAdicional : ""};
 
  /**
   * definimos parametros de pesquisa
   * @param sFontePesquisa   string fonte php da lookup
   * @param aCamposRetorno   array  campos a serem pesquisados
   * @param sStringAdicional string buscas adicionais necessarias na lookup
   */
  this.setParametrosPesquisa  = function( sFontePesquisa, aCamposRetorno, sStringAdicional ) {

    this.oDadosPesquisa = {sFontePesquisa   : sFontePesquisa, 
                           aCamposRetorno   : aCamposRetorno, 
                           sStringAdicional : sStringAdicional};
    return true;
  };
  this.getQueryAncora = function() {
    return sQueryPesquisaAncora;
  };
  
  /**
   * setamos o texto do link da ancora
   */
  this.setLabelAncora  = function(sLabelAncora) {
    
    sAncora = sLabelAncora;
  };
  
  /**
   * retornamos o texto do link da ancora
   */
  this.getLabelAncora = function(){
    
    return sAncora;
  };
  
  this.setNomeInstancia = function (sNomeInstancia) {
    sInstancia = sNomeInstancia;
  };
  
  this.getNomeInstancia = function () {
    return sInstancia;
  };

  this.setRegistros = function ( oDados ) {
    
    var oSelf        = this; 
    oSelf.oRegistros = oDados;
  };
  
  
  /**
   * Seta se o componente DBLancador estará habilitado para adição de novos registros
   *   Valor padrão (true)
   * Ex.: (definido antes do método show)
   * ->DBLancador.setHabilitado( false );  
   *   DBLancador.show($('idDiv'));
   */
  this.setHabilitado = function ( lHabilita ) {
	  this.lHabilitado = lHabilita;  
  };
  
  this.adicionarRegistro = function ( sCodigo, sDescricao ) {
    
    var oSelf            = this; 
    var oRegistro        = new Object();
    var oDadosRegistros  = this.getRegistros(true);
    
    oRegistro.sCodigo    = sCodigo;
    oRegistro.sDescricao = sDescricao;
    
    for (var iRegistro  in oDadosRegistros ) {
      
      var sCodigoAtual = oDadosRegistros[iRegistro].sCodigo;
      if ( sCodigoAtual == sCodigo ) {
        return false;
      }
    }
    
    oDadosRegistros[ sInstancia + sCodigo ] = oRegistro;
    me.renderizarRegistros();
    return true;
  };
  
  /*
   * funcao para remover registros
   * da grid;
   */
  this.removerRegistro = function ( sCodigo ) {
    
    var oSelf = this;

    if (oSelf.oRegistros[sInstancia + sCodigo]) { // se o registro clicado estar dentro do array em memoria retiramos ele
      delete(oSelf.oRegistros[sInstancia + sCodigo]);
    }
    this.renderizarRegistros(); // e a grid será novamente renderizada
  };
  
  /**
   * Retorna registro adicionados
   *
   * @returns array de objetos
   */
  this.getRegistros = function ( lObjetos ) {

    var oSelf = this; 
    lObjetos  = ( lObjetos == null ) ? false : lObjetos;
    
    if ( !lObjetos ) {
     
      var aRetorno = new Array();
      for ( var iRetorno in oSelf.oRegistros ) {
        
        var oRegistroLancado = oSelf.oRegistros[iRetorno];
        aRetorno.push(oRegistroLancado);
      }
      return aRetorno;
    }
    return oSelf.oRegistros;
  };
  
  this.getFieldset = function () {
    return this.oElementos.oFieldSet;
  };
  
};

/**
 * 
 * @returns
 */
DBLancador.prototype.criarElementosBasicos = function () {
  
  // field set agrupando o componente
  // div principal
  // div ações
  // div grid
  var me = this;
  var oElementos    = this.oElementos;

  oElementos.oDivPrincipal       = document.createElement('div');
                                 
  oElementos.oFieldSet           = document.createElement('fieldset');
  oElementos.oLegend             = document.createElement('legend');
  oElementos.oTexto              = document.createTextNode(this.sTextoFieldset);
                                 
  oElementos.oDivAcoes           = document.createElement('div');
  oElementos.oDivAcoes.style.paddingBottom = '5px';

  oElementos.oSpanAncora         = document.createElement('span');
  oElementos.oSpanAncora.style.paddingRight = '5px';

  oElementos.oSpanCodigo         = document.createElement('span');
  oElementos.oSpanDescricao      = document.createElement('span');
  oElementos.oSpanBotaoLancar    = document.createElement('span');
  
  oElementos.oButtonLancar       = document.createElement('input');
  oElementos.oButtonLancar.value = 'Adicionar';
  oElementos.oButtonLancar.type  = 'button';

  if (!me.lHabilitado) {
	 oElementos.oButtonLancar.disabled = true;
  }
  
  oElementos.oDivGrid            = document.createElement('div');
  
  oElementos.oLegend.appendChild(oElementos.oTexto);                 // Adiciona Texto a Legenda
  oElementos.oFieldSet.appendChild(oElementos.oLegend);              // Adiciona Legenda ao Fieldset
  oElementos.oDivPrincipal.appendChild(oElementos.oFieldSet);        // Adiciona Fieldset a Div Principal
  oElementos.oFieldSet.appendChild(oElementos.oDivAcoes);            // Adiciona a DIV de Ações dentro do fieldset
  oElementos.oFieldSet.appendChild(oElementos.oDivGrid);             // Adiciona a DIV da grid dentro do Fieldset, abaixo da div das ações.
                                                                     
  oElementos.oDivAcoes.appendChild(oElementos.oSpanAncora);          // Adiciona SPAN da Ancora na DIV das Ações
  oElementos.oDivAcoes.appendChild(oElementos.oSpanCodigo);          // Adiciona SPAN do Campo Código na DIV das Ações
  oElementos.oDivAcoes.appendChild(oElementos.oSpanDescricao);       // Adiciona SPAN da Descricao na DIV das Ações
  oElementos.oDivAcoes.appendChild(oElementos.oSpanBotaoLancar);     // Adiciona SPAN do Botão Lançar na DIV das Ações
  oElementos.oSpanBotaoLancar.appendChild(oElementos.oButtonLancar); // Adiciona Botão Lançar no SPAN do Botão Lançar
  
  oElementos.oSpanCodigo.onchange = function() {
    
    oElementos.oButtonLancar.disabled = true;
    
    var oParametros = me.oDadosPesquisa;
    var sQuery      = oParametros.sFontePesquisa;
    var sIframe     = 'db_iframe_' + oParametros.sFontePesquisa.replace('.php', '').replace('func_', '');

    sQuery         += '?funcao_js=parent.' + me.getNomeInstancia() + '.retornoPesquisaDigitacao';
    sQuery         += '&pesquisa_chave='  +   oElementos.oInputCodigo.value.urlEncode();
    
    if (oParametros.sStringAdicional != '') {      
      sQuery += '&' + oParametros.sStringAdicional;
    }
    
    me.getParametros().each(function(oParametro) {      
      sQuery += '&' + oParametro.nome + '=' + oParametro.valor;
    });
    
    js_OpenJanelaIframe('',
                        sIframe,
                        sQuery,
                        'Pesquisar Dados: ',
                        false);    
    
  };
  
  return oElementos.oDivPrincipal;
  
};

DBLancador.prototype.adicionarComponentes = function() {
  
  var me      = this;
  
  var oAncora = new DBAncora(this.getLabelAncora(),  '#', me.lHabilitado);
  
  oAncora.onClick(function () {
    
    var oParametros = me.oDadosPesquisa;
    var sQuery      = oParametros.sFontePesquisa;
    var sIframe     = 'db_iframe_' + oParametros.sFontePesquisa.replace('.php', '').replace('func_', '');

    sQuery += '?funcao_js=parent.' + me.getNomeInstancia() + '.retornoPesquisaLookUp|';
    sQuery += oParametros.aCamposRetorno.join("|");

    if (oParametros.sStringAdicional != '') {      
      sQuery += '&' + oParametros.sStringAdicional;
    }
    
    me.getParametros().each(function(oParametro) {      
      sQuery += '&' + oParametro.nome + '=' + oParametro.valor;
    });
    
    js_OpenJanelaIframe('', 
                        sIframe,
                        sQuery,
                        'Pesquisa',
                        true);
    
  });
  
  oAncora.show(this.oElementos.oSpanAncora);
      
  var oTextCodigo       = new DBTextField("txtCodigo" + me.getNomeInstancia(), "", '', 10);
  var oTextDescricao    = new DBTextField("txtDescricao" + me.getNomeInstancia(), "", '', 40);
  
  if (!me.lHabilitado) {
  	oTextCodigo.lReadOnly = true;
  }
  
  oTextCodigo.show(this.oElementos.oSpanCodigo);
  oTextDescricao.show(this.oElementos.oSpanDescricao);
  oTextDescricao.setReadOnly(true);
  
  this.oElementos.oInputCodigo             = oTextCodigo.getElement();
  this.oElementos.oInputDescricao          = oTextDescricao.getElement();
  this.oElementos.oInputCodigo   .setAttribute("onChange", "");
  this.oElementos.oInputCodigo   .setAttribute("onKeyUp","js_ValidaCampos(this," + this.iTipoValidacao + ",'" + this.getLabelAncora() + "','f','f',event);");
  this.oElementos.oInputDescricao.setAttribute("onChange", "");
  
  var oBotao            = this.oElementos.oButtonLancar;
  
  oBotao.onclick        = function() {
    
    me.lancarRegistro();
    me.oElementos.oInputCodigo.value    = "";
    me.oElementos.oInputDescricao.value = "";
  };

  /**
   * Cria DataGrid com a Base para a Pesquisa
   */
 this.criaGridLancador();
  
};

/**
 * adiciona o registro selecionado na grid
 * validando os dados dijgitados e chama a fnção responsavel por 
 * renderizar a linha
 * @returns {Boolean}
 */
DBLancador.prototype.lancarRegistro = function() {
  
  var oInputCodigo    = this.oElementos.oInputCodigo;
  var oInputDescricao = this.oElementos.oInputDescricao;
  
  if ( oInputCodigo.value == '' ) {
    
    oInputCodigo.value    = '';
    oInputDescricao.value = '';
    
    alert('Nenhum registro selecionado.');
    return false;
  }
  
  var lAdicionouRegistro = this.adicionarRegistro( oInputCodigo.value, oInputDescricao.value);
  
  if ( !lAdicionouRegistro ) {
    
    oInputCodigo.value    = '';
    oInputDescricao.value = '';
    alert("Registro já adicionado.");
  }
  return true;
};

/**
 * Mostra Componente na Tela
 * @param oElemento
 * @returns {Boolean}
 */
DBLancador.prototype.show = function( oElemento ) {
  
  var oElementos = this.criarElementosBasicos();

  /**
   * Limpa o container antes de por os elementos  
   * para poder alterar os parametros e somente chamar o show novamente
   */
  oElemento.innerHTML = '';
  oElemento.appendChild(oElementos);

  this.adicionarComponentes();
  return true;
};

/**
 * Renderiza DataGrid com os dados 
 * @returns {DBGrid}
 */
DBLancador.prototype.criaGridLancador = function() {
  
  this.oGridLancador                 = new DBGrid(this.getNomeInstancia() +'Lancador');
  this.oGridLancador.nameInstance    = this.getNomeInstancia() + '.oGridLancador';
  this.oGridLancador.setCellWidth(new Array( '70px', '330px', '75px'));
  this.oGridLancador.setCellAlign(new Array( 'left', 'left', 'center'));
  this.oGridLancador.setHeader(new Array( 'Código','Descrição', 'Ação'));
  this.oGridLancador.setHeight(this.iGridHeight);
  this.oGridLancador.show( this.oElementos.oDivGrid );
  this.oGridLancador.clearAll(true);
  
  return this.oGridLancador;
};

DBLancador.prototype.setGridHeight = function(iHeight) {
  this.iGridHeight = iHeight;
};

/**
 * Rendereiza registros na Grid
 * @returns {Boolean}
 */
DBLancador.prototype.renderizarRegistros = function() {
  
  var me            = this;
  
  var aCelulasBotao = new Array();
  
  var oDataGrid     = this.oGridLancador;
      oDataGrid.clearAll(true);
      
  var oRegistros    = this.getRegistros(true);
  /*
   * percorremos os registros criando as linhas da grid
   * sem o botao de remover
   */
  var iIndiceLinhaGrid = 0;
  
  for ( var sIdRegistro in oRegistros ) {
    
    var oRegistro = oRegistros[sIdRegistro];
    
    var oBotaoRemover = document.createElement('input');
        oBotaoRemover.type  = 'button';
        oBotaoRemover.value = 'Remover';
        
        
	if (!me.lHabilitado) {
      oBotaoRemover.disabled = true;	
    } else {
        oBotaoRemover.setAttribute("onClick", this.getNomeInstancia() + ".removerRegistro( '" + oRegistro.sCodigo + "' )");
    }        
    
    oDataGrid.addRow([oRegistro.sCodigo, oRegistro.sDescricao, '']);
    
    var oDadosAcao = new Object();
    oDadosAcao.sIdCelulaGrid = oDataGrid.aRows[iIndiceLinhaGrid].aCells[2].sId;
    oDadosAcao.oBotaoRemover = oBotaoRemover;
    aCelulasBotao.push(oDadosAcao);
    iIndiceLinhaGrid++;
  }
  oDataGrid.renderRows();
  
  /*
   * com a grid renderizada , percorremos os botao remover criados
   * para que sejam adicionados
   */
  for (var iDadosAcao = 0; iDadosAcao < aCelulasBotao.length; iDadosAcao++) {
    
    var oDadosAcao = aCelulasBotao[iDadosAcao];
    var oCelulaGrid = document.getElementById(oDadosAcao.sIdCelulaGrid);
    oCelulaGrid.appendChild(oDadosAcao.oBotaoRemover);
  }
  return true;
};

DBLancador.prototype.retornoPesquisaLookUp = function(sCodigo, sDescricao) {
  
  this.oElementos.oInputCodigo.value    = sCodigo;
  this.oElementos.oInputDescricao.value = sDescricao;

  var oParametros = this.oDadosPesquisa;
  var sIframe     = 'db_iframe_' + oParametros.sFontePesquisa.replace('.php', '').replace('func_', '');

  eval(sIframe + '.hide();');
};

DBLancador.prototype.retornoPesquisaDigitacao = function() {
  
  var aArgumentos   = arguments;
  var lErro         = null;//aArgumentos[iUltimoIndice];
  var sDescricao    = null;//aArgumentos[0];
  
  for (var iArgumentos = 0; iArgumentos < aArgumentos.length; iArgumentos++ ) {
  
    if (typeof(aArgumentos[iArgumentos]) == "boolean") {
      
      lErro = aArgumentos[iArgumentos];
    }
    if (typeof(aArgumentos[iArgumentos]) == 'string' && sDescricao == null ) {
      sDescricao = aArgumentos[iArgumentos];
    }
    
  }
  
  this.oElementos.oButtonLancar.disabled = false;
  
  if (lErro) {
    
    this.oElementos.oInputCodigo.value    = "";
    this.oElementos.oInputDescricao.value = sDescricao;
    return;
  }
  
  this.oElementos.oInputDescricao.value = sDescricao;
  
};

DBLancador.prototype.setTipoValidacao = function(iTipoValidacao) {
  
  this.iTipoValidacao = iTipoValidacao;
  
  if ( this.oElementos.oButtonLancar ) {
    this.oElementos.oInputCodigo.setAttribute("onKeyUp", "js_ValidaCampos(this," + this.iTipoValidacao + ",'" + this.getLabelAncora() + "','f','f',event);");
  }
  
  js_ValidaCampos(this,0,'Seleção','f','f','event');
};

/**
 * Seta o Texto que será utilizado no fieldset
 * @param sTextoFieldset Texto informado para o fieldset
 * @returns void
 */
DBLancador.prototype.setTextoFieldset = function(sTextoFieldset) {
  this.sTextoFieldset = sTextoFieldset; 
  return;
};

/**
 * Seta um parâmetro e um valor
 * @param sNomeParametro
 * @param sValor
 */
DBLancador.prototype.setParametro = function(sNomeParametro, sValor) {
  
  var lAchou = false;
  var oSelf  = this;
  oSelf.aParametros.each(function(oParametro, iIndice) {
    
    if (oParametro.nome == sNomeParametro) {
      
      lAchou           = true;
      oParametro.valor = sValor;
      throw $break;
    }
  });
  
  if (!lAchou) {
    
    var oParametro = new Object();
    oParametro.nome  = sNomeParametro;
    oParametro.valor = sValor; 
    oSelf.aParametros.push(oParametro);
  }
};

/**
 * Retorna o parâmetro
 */
DBLancador.prototype.getParametros = function() {
  
  var oSelf = this;
  return oSelf.aParametros;
};

/**
 * Limpa toda Grid
 * @returns {Boolean}
 */
DBLancador.prototype.clearAll = function() {
  
  var oSelf        = this; 
  var oDataGrid    = oSelf.oGridLancador;
  oSelf.oRegistros = new Object();
  
  oDataGrid.clearAll(true);
  oDataGrid.renderRows();
  
  return true;
};

/**
 * Método que monta registros pre-definidos na grid, de um array bi-dimensional.
 * - Deve ser usado apos criar elementos com o metodo show
 * 
 * aRegistros    = [];
 * aRegistros[0] = ['Código 1', 'Descrição 1'];
 * aRegistros[1] = ['Código 2', 'Descrição 2'];
 * 
 * DBLancador.show( $('containder') )
 * DBLancador.carregarRegistros(aRegistros);
 * 
 * @param aRegistros
 */
DBLancador.prototype.carregarRegistros = function (aRegistros) {
	
  var me = this;
  
  aRegistros.each( function (aRegistro){ 
	  me.adicionarRegistro(aRegistro[0], aRegistro[1]);
  });
  
};

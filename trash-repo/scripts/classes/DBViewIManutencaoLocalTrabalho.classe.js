/**
 * Componente para manipulação dos dados referente aos Locais de Trabalho de um servidor (funcionário)
 * 
 * @package Pessoal
 * @author Everton Catto Heckler <everton.heckler@dbseller.com.br>
 * 
 */
var DBViewIManutencaoLocalTrabalho = function(sInstancia, sNomeComponente, iCodigoServidor) {

  var oInstancia              = this;
  
  oInstancia.oForm            = new Object();
  
  this.sNomeComponente        = sNomeComponente;
  
  this.sNomeInstancia         = sInstancia;
  
  var iCodigoServidor         = iCodigoServidor;
  
  this.aDadosLocaisTrabalho   = new Array();
  
  this.oElementoDestino       = null;
  
  this.janelaAtiva            = false;
  
  var lbuscaLocais            = true;
  
  sUrl = 'pes1_manutencaLocalTrabalho.RPC.php';
  
  this.setCodigoServidor      = function (iCodigoServidor) {
    
    this.iCodigoServidor = iCodigoServidor
  }
  
  /**
   * Retorna objeto com a informação do local de trabalho
   */
  this.getLocalTrabalho       = function (iCodigoLocal) {
    
    for ( var iIndiceLocal in this.aDadosLocaisTrabalho ) {
      
      var oDadosLocal   = this.aDadosLocaisTrabalho[iIndiceLocal];
      
      if (oDadosLocal.iCodigoLocal == iCodigoLocal) {
        
        return oDadosLocal;
      }
    }
    
    throw 'Codigo de Local não existente na lista de locais de trabalho deste Servidor!';
  };
  
  this.oLocalEmManutencao     = null;
  return this;

};


/**
 * Cria HTML no Local especificado
 */
DBViewLocaisDeTrabalho.prototype.show = function(oContainer) {
  
  oElementoDestino = oContainer;
  
  if (oContainer == null) {
    
    this.janelaAtiva = true;
    oElementoDestino = this.criarJanela();
  }
  
  this.lbuscaLocais = true;
  this.renderizarHTMLBase();
  this.criarComponentes();
  this.renderizarGridLocaisDeTrabalho();
  this.carregarGridLocaisDeTrabalho(this.iCodigoServidor);
  
};


/**
 * Renderiza o HTML da janela base
 */
DBViewLocaisDeTrabalho.prototype.renderizarHTMLBase = function() {
  
  var oInstancia = this;
  
  this.oForm.oFieldSetDadosLocalTrabalho              = document.createElement( 'fieldset' );
  this.oForm.oFieldSetDadosLocalTrabalho.style.margin = "5px";
  this.oForm.oLegendDadosLocalTrabalho                = document.createElement( 'legend' );
  this.oForm.oLegendDadosLocalTrabalho.innerHTML      = "<STRONG>Dados do Local de Trabalho: </STRONG>";
  this.oForm.oDivDadosLocalTrabalho                   = document.createElement( 'div' );
  this.oForm.oDivDadosLocalTrabalho.style.float       = "right";
  this.oForm.oDivDadosLocalTrabalho.style.textAlign   = "right";
  
  this.oForm.oTable                          = document.createElement( 'table' );
  this.oForm.oTable.style.margin             = '0 auto';
  this.oForm.oDivDadosLocalTrabalho.appendChild(this.oForm.oTable);
  
  this.oForm.oLinhaLocalTrabalho                    = document.createElement( 'tr' );
  this.oForm.oLinhaLocalTrabalho.id                 = this.sNomeComponente + "_llocalTrabalho"; 
  
  this.oForm.oCelulaLabelLocalTrabalho              = document.createElement( 'td' );
  this.oForm.oElementoAncoraLocalTrabalho           = document.createElement( 'a' );
  this.oForm.oElementoAncoraLocalTrabalho.className = "DBAncora";
  this.oForm.oElementoAncoraLocalTrabalho.innerHTML = "Local de Trabalho:";
  this.oForm.oElementoAncoraLocalTrabalho.href      = "#";
  
  this.oForm.oElementoAncoraLocalTrabalho.onclick   = function() {
    
    oInstancia.pesquisarLocalTrabalho(true);
  };
  
  this.oForm.oCelulaLocalTrabalhoCodigo                   = document.createElement( 'td' );
  this.oForm.oCelulaLocalTrabalhoCodigo.innerHTML         = '<div id="' + this.sNomeComponente + '_localTrabalhoCodigo"></div>';
  
  
  this.oForm.oCelulaLocalTrabalhoDescricao                = document.createElement( 'td' );
  this.oForm.oCelulaLocalTrabalhoDescricao.innerHTML      = '<div id="' + this.sNomeComponente + '_localTrabalhoDescricao"></div>';
  
  this.oForm.oCelulaLabelLocalTrabalho.appendChild(this.oForm.oElementoAncoraLocalTrabalho);
  this.oForm.oTable.appendChild(this.oForm.oLinhaLocalTrabalho);
  
  this.oForm.oLinhaLocalTrabalho.appendChild(this.oForm.oCelulaLabelLocalTrabalho);
  this.oForm.oLinhaLocalTrabalho.appendChild(this.oForm.oCelulaLocalTrabalhoCodigo);
  this.oForm.oLinhaLocalTrabalho.appendChild(this.oForm.oCelulaLocalTrabalhoDescricao);
  
  this.oForm.oLinhaQuantidade                  = document.createElement( 'tr' );
  this.oForm.oLinhaQuantidade.id               = this.sNomeComponente + "_lquantidade"; 
  this.oForm.oTable.appendChild(this.oForm.oLinhaQuantidade);
  
  this.oForm.oCelulaLabelQuantidade            = document.createElement( 'td' );
  this.oForm.oCelulaLabelQuantidade.innerHTML  = '<strong>Quantidade:</strong>';
  this.oForm.oCelulaQuantidade                 = document.createElement( 'td' );
  this.oForm.oCelulaQuantidade.innerHTML       = '<div id="' + this.sNomeComponente + '_quantidade"></div>';
  
  this.oForm.oLinhaQuantidade.appendChild( this.oForm.oCelulaLabelQuantidade );
  this.oForm.oLinhaQuantidade.appendChild( this.oForm.oCelulaQuantidade );
  
  this.oForm.oLinhaPrincipal                  = document.createElement( 'tr' );
  this.oForm.oLinhaPrincipal.id               = this.sNomeComponente + "_lprincipal"; 
  this.oForm.oTable.appendChild( this.oForm.oLinhaPrincipal );
  
  this.oForm.oCelulaLabelPrincipal            = document.createElement( 'td' );
  this.oForm.oCelulaLabelPrincipal.innerHTML  = '<strong>Principal:</strong>';
  this.oForm.oCelulaPrincipal                 = document.createElement( 'td' );
  
  this.oForm.oLinhaPrincipal.appendChild( this.oForm.oCelulaLabelPrincipal );
  this.oForm.oLinhaPrincipal.appendChild( this.oForm.oCelulaPrincipal );
  
  this.oForm.oLinhaPercentual                  = document.createElement( 'tr' );
  this.oForm.oLinhaPercentual.id               = this.sNomeComponente + "_lpercentual"; 
  this.oForm.oTable.appendChild( this.oForm.oLinhaPercentual );
  
  this.oForm.oCelulaLabelPercentual            = document.createElement( 'td' );
  this.oForm.oCelulaLabelPercentual.innerHTML  = '<strong>Percentual:</strong>';
  this.oForm.oCelulaPercentual                 = document.createElement( 'td' );
  this.oForm.oCelulaPercentual.innerHTML       = '<div id="' + this.sNomeComponente + '_percentual"></div>';
  
  this.oForm.oLinhaPercentual.appendChild( this.oForm.oCelulaLabelPercentual );
  this.oForm.oLinhaPercentual.appendChild( this.oForm.oCelulaPercentual );
  
  
  this.oForm.oLinhaEstrutural                  = document.createElement( 'tr' );
  this.oForm.oLinhaEstrutural.id               = this.sNomeComponente + "_lEstrutural"; 
  this.oForm.oTable.appendChild ( this.oForm.oLinhaEstrutural );
  
  this.oForm.oCelulaLabelEstrutural            = document.createElement( 'td' );
  this.oForm.oCelulaLabelEstrutural.setAttribute ( "colspan", "3" );
  
  this.oForm.oLinhaEstrutural.appendChild( this.oForm.oCelulaLabelEstrutural );
  
  
  this.oForm.oLinhalancar                  = document.createElement( 'tr' );
  this.oForm.oLinhalancar.id               = this.sNomeComponente + "_lLancar"; 
  this.oForm.oTable.appendChild ( this.oForm.oLinhalancar );
  
  this.oForm.oCelulaLabelLancar            = document.createElement( 'td' );
  this.oForm.oCelulaLabelLancar.setAttribute ( "colspan", "3" );
  
  this.oForm.oLinhalancar.appendChild( this.oForm.oCelulaLabelLancar );
  
  
  this.oForm.oFieldSetGrid               = document.createElement( 'fieldset' );
  this.oForm.oFieldSetGrid.style.margin  = "5px";
  this.oForm.oLegendGrid                 = document.createElement( 'legend' );
  this.oForm.oLegendGrid.innerHTML       = "<STRONG>Locais de Trabalho Lançados: </STRONG>";
  this.oForm.oContainerGrid              = document.createElement( 'div' );
  this.oForm.oContainerGrid.id           = 'gridLocaisDeTrabalho';
  this.oForm.oContainerGrid.style.width  = "762px";
  
  
  this.oForm.oFieldSetDadosLocalTrabalho.appendChild ( this.oForm.oLegendDadosLocalTrabalho );
  this.oForm.oFieldSetDadosLocalTrabalho.appendChild ( this.oForm.oDivDadosLocalTrabalho );
  
  this.oForm.oFieldSetGrid.appendChild ( this.oForm.oLegendGrid );
  this.oForm.oFieldSetGrid.appendChild ( this.oForm.oContainerGrid );
  
  oElementoDestino.appendChild ( this.oForm.oFieldSetDadosLocalTrabalho );
  oElementoDestino.appendChild ( this.oForm.oFieldSetGrid );
  
};


/**
 * Cria Janela Bases
 */
DBViewLocaisDeTrabalho.prototype.criarJanela = function() {

  /**
   * Utilizado em Closures
   */
  var oInstancia = this;
  
  if ( $( this.sNomeComponente + '_janelaLocaisDeTrabalho' ) ) {
    
    this.oJanelaBase.destroy();
  }
  
  var sConteudo  = " <div id='" + this.sNomeComponente + "_locaisDeTrabalhoCabecalho'></div>                                                              ";
  sConteudo     += " <div id='" + this.sNomeComponente + "_locaisDeTrabalhoConteudo'></div>                                                               ";
  sConteudo     += " <div id='" + this.sNomeComponente + "_locaisDeTrabalhoRodape'>                                                                       ";
  sConteudo     += "   <center>                                                                                                                           ";
  sConteudo     += "     <input type='button' id='btnSalvar' value='Salvar' onClick='" + this.sNomeInstancia + ".salvarRegistros();' />                   ";
  sConteudo     += "     <input type='button' value='Fechar' onClick='" + this.sNomeInstancia + ".oJanelaBase.destroy();' style=\"margin-left: 2px;\" />  ";
  sConteudo     += "   </center>                                                                                                                          ";
  sConteudo     += " </div>                                                                                                                               ";
  
  this.oJanelaBase = new windowAux ( this.sNomeComponente + '_janelaLocaisDeTrabalho',
                                     'Locais de Trabalho',
                                     800,
                                     500
                                   );
  
  this.oJanelaBase.setContent( sConteudo );
  
  this.oJanelaBase.setShutDownFunction( function() {
    oInstancia.oJanelaBase.destroy();
  });
  
  this.oJanelaBase.show();
  
  var sMsg                 = "Abaixo o estado do(s) local(is) de trabalho do Servidor.\n";
  
  this.oMessageBase  = new DBMessageBoard ( this.sNomeComponente + 'msgBase',
                                            'Manutenção Locais de Trabalho:',
                                            sMsg,
                                            $( this.sNomeComponente + "_locaisDeTrabalhoCabecalho" ) );
  
  this.oMessageBase.show();
  
  return $( this.sNomeComponente + '_locaisDeTrabalhoConteudo' );
};


/**
 * Cria Componentes para manipulaçao
 */
DBViewLocaisDeTrabalho.prototype.criarComponentes = function() {
  
  var oInstancia = this;
  
  this.oInputLocalTrabalhoCodigo = new DBTextField( this.sNomeComponente + '_oInputLocalTrabalhoCodigo',
                                                    this.sNomeInstancia + '.oInputLocalTrabalho',
                                                    '',
                                                    5 );
  
  this.oInputLocalTrabalhoCodigo.addEvent( "onChange", "js_pesquisarh56_localtrab(false);" );
  this.oInputLocalTrabalhoCodigo.show( $( this.sNomeComponente + '_localTrabalhoCodigo' ) );
  
  var oElementoInput = this.oInputLocalTrabalhoCodigo.getElement();
  
  oElementoInput.onchange = function () {
    
    oInstancia.pesquisarLocalTrabalho( false );
  };
  
  
  this.oInputLocalTrabalhoDescricao = new DBTextField( this.sNomeComponente + '_oInputLocalTrabalhoDescricao',
                                                      this.sNomeInstancia + '.oInputLocalTrabalhoDescricao',
                                                             '',
                                                             40 );
  
  this.oInputLocalTrabalhoDescricao.setReadOnly(true);
  
  this.oInputLocalTrabalhoDescricao.show( $( this.sNomeComponente + '_localTrabalhoDescricao' ) );
  
  this.oInputQuantidade = new DBTextField( this.sNomeComponente + '_oInputQuantidade',
                                           this.sNomeInstancia + '.oInputQuantidade',
                                                 '',
                                                 5 );
  
  this.oInputQuantidade.show( $( this.sNomeComponente + '_quantidade' ) );
  
  var oElementoInputQuantidade = this.oInputQuantidade.getElement();
  
  oElementoInputQuantidade.onchange = function () {
    
    var nPercentual = oInstancia.atualizarPercentual( new Number( oInstancia.oInputQuantidade.getValue() ) );
    
    oInstancia.oInputPercentual.setValue( nPercentual + "%" );
  };
  

  this.oInputPrincipal =  document.createElement( 'input' );
  this.oInputPrincipal.setAttribute( 'type', 'checkbox');
  this.oInputPrincipal.setAttribute( 'id'  , this.sNomeComponente + '_InputPrincipal' );
  this.oInputPrincipal.setAttribute( 'name', this.sNomeComponente + '_InputPrincipal' );
  
  this.oForm.oCelulaPrincipal.appendChild ( this.oInputPrincipal);
  
  this.oInputPercentual = new DBTextField( this.sNomeComponente + '_oInputPercentual',
                                           this.sNomeInstancia + '.oInputPercentual',
                                                 '',
                                                 5);
  
  this.oInputPercentual.show( $( this.sNomeComponente + '_percentual' ) );
  this.oInputPercentual.setReadOnly(true);
  this.oAlinhamentoCenter = document.createElement ( 'center' );
  
  this.oBotaoLancar =  document.createElement( 'input' );
  this.oBotaoLancar.setAttribute( 'type' , 'button');
  this.oBotaoLancar.setAttribute( 'id'   , this.sNomeComponente + '_BotaoLancar' );
  this.oBotaoLancar.setAttribute( 'name' , this.sNomeComponente + '_BotaoLancar' );
  this.oBotaoLancar.setAttribute( 'value', 'Lançar' );
  this.oBotaoLancar.onclick = function () {
    
    oInstancia.lancarRegistros();
  };
  
  
  this.oInputEstrutural =  document.createElement( 'input' );
  this.oInputEstrutural.setAttribute( 'type', 'hidden');
  this.oInputEstrutural.setAttribute( 'id'  , this.sNomeComponente + '_InputEstrutural' );
  this.oInputEstrutural.setAttribute( 'name', this.sNomeComponente + '_InputEstrutural' );
  
  this.oForm.oCelulaLabelEstrutural.appendChild ( this.oInputEstrutural);
  
  
  
  this.oAlinhamentoCenter.appendChild ( this.oBotaoLancar );
  this.oForm.oCelulaLabelLancar.appendChild ( this.oAlinhamentoCenter );
  
  return true;
};


/**
 * Renderiza Grid contendo os Locais de Trabalho
 */
DBViewLocaisDeTrabalho.prototype.renderizarGridLocaisDeTrabalho = function() {
  
  this.gridLocais               = new DBGrid( this.sNomeComponente + "_dataGridLocais" );
  this.gridLocais.nameInstance  = this.sNomeInstancia + ".gridLocais";
  
  this.gridLocais.setHeight   ( 100 );
  this.gridLocais.setCheckbox ( 0 );
  this.gridLocais.hasCheckbox = false;
  
  this.gridLocais.setCellAlign ( new Array ("center",
                                            "left"  ,
                                            "center",
                                            "center",
                                            "right" ,
                                            "center"
                                           )
                               );
  
  this.gridLocais.setCellWidth ( new Array ("15%",
                                            "50%",
                                            "10%",
                                            "20%",
                                            "20%",
                                            "10%"
                                           )
                               );
  
  this.gridLocais.setHeader ( new Array ('Estrut.'  ,
                                         'Descr.'   ,
                                         'Principal',
                                         'Quant.'   ,
                                         'Percent.' ,
                                         'Ação'
                                        )
                            );
  
  this.gridLocais.show    ( this.oForm.oContainerGrid );
  this.gridLocais.clearAll( true );
  
  return;
};


/**
 * Carrega os dados para lançar na grid
 * @param iCodigoServidor integer
 */
DBViewLocaisDeTrabalho.prototype.carregarGridLocaisDeTrabalho = function(iCodigoServidor) {
  
  var oInstancia = this;
  
  if (this.lbuscaLocais) {
    
    this.gridLocais.clearAll(true);
    
    js_divCarregando('Pesquisando Locais de Trabalho.', 'msgAjax');
    
    var oParam             = new Object();
    var oAjax              = new Object();
    
    oParam.sExec           = "getDadosServidorLocaisDeTrabalho";
    oParam.iCodigoServidor = iCodigoServidor;
    
    oAjax.method           = 'POST';
    oAjax.parameters       = 'json=' + Object.toJSON(oParam);
    oAjax.onComplete       =  function(oAjax) {
                                oInstancia.retornoLocais(oAjax);
                              };
    oAjax.asynchronous     =  false;
    var oRequest           = new Ajax.Request( sUrl, oAjax );
    
  }
  
  return true;
};


/**
 * Tratamento para o retorno dos Locais de Trabalho do servidor
 * @param oAjax object
 */
DBViewLocaisDeTrabalho.prototype.retornoLocais = function(oAjax) {
  
  js_removeObj('msgAjax');
  
  if (oAjax != null) {
    
    var oRetorno  = eval("(" + oAjax.responseText + ")");
    
    if ( oRetorno.status == 2 ) {
      
      this.oJanelaBase.destroy();
      alert(oRetorno.message.urlDecode().replace(/\\n/g, '\n'));
      
      return false;
    } 
    
  };
  
  for ( var iIndiceGrid = 0; iIndiceGrid < oRetorno.aLocaisDeTrabalho.length; iIndiceGrid++ ) {
    
    var oLocal   = oRetorno.aLocaisDeTrabalho[iIndiceGrid];
    
    this.adicionarLocalTrabalho(new String(oLocal.rh55_estrut),
                                new String(oLocal.rh55_descr), 
                                oLocal.rh56_princ == 't',
                                new Number(oLocal.rh56_quantidadecusto), 
                                new Number(oLocal.rh56_percentualcusto),
                                new Number(oLocal.rh55_codigo) );
  }
  
  this.incluirRegistrosGrid();
  
  return true;
};


/**
 * Inclui registros na grid para manutenção
 */
DBViewLocaisDeTrabalho.prototype.incluirRegistrosGrid = function() {
  
  this.gridLocais.clearAll(true);
  
  for ( var iIndiceGrid in this.aDadosLocaisTrabalho ) {
    
    if (!this.aDadosLocaisTrabalho[iIndiceGrid]) {
      
      continue;
    }
    
    var oDadosLocal   = this.aDadosLocaisTrabalho[iIndiceGrid];
    
    if ( !oDadosLocal.lAtivo ) {
      
      continue;
    }
    
    var aCelulas = new Array();
    
    aCelulas[0]  = oDadosLocal.sEstrutural;
    aCelulas[1]  = oDadosLocal.sDescricao;
    if ( oDadosLocal.lPrincipal ) {
    
      aCelulas[2]  = '<input type="radio" name="lPrincipal" onClick="' + this.sNomeInstancia + '.trocaLocalPrincipal(' + oDadosLocal.iCodigoLocal + ');" checked>';
    } else {
      
      aCelulas[2]  = '<input type="radio" name="lPrincipal" onClick="' + this.sNomeInstancia + '.trocaLocalPrincipal(' + oDadosLocal.iCodigoLocal + ');">';
    }
    
    aCelulas[3]  = js_formatar(oDadosLocal.iQuantidade, "f");
    aCelulas[4]  = js_formatar(oDadosLocal.nPercentual, "f") + ' %';
    aCelulas[5]  = '<a href="#" onClick="' + this.sNomeInstancia + '.liberaManutencaoRegistro(' +  oDadosLocal.iCodigoLocal + ', true);">A</a>'; 
    aCelulas[5] += ' - ';
    aCelulas[5] += '<a href="#" onClick="' + this.sNomeInstancia + '.excluiRegistro(' +  oDadosLocal.iCodigoLocal + ', false);">E</a>';
    
    this.gridLocais.addRow( aCelulas );
  }
  
  this.gridLocais.renderRows();
  
  return true;
};


/**
 * Excluir registro (Exclusão dados, Exclusão Manutenção)
 * @param iCodigoLocal
 * @param lManutencao
 */
DBViewLocaisDeTrabalho.prototype.excluiRegistro = function(iCodigoLocal, lManutencao) {
  
  var oLocalExclusao   = this.getLocalTrabalho(iCodigoLocal);
  
  if ( false ) {
    oLocalExclusao.lAtivo = false;
    
  } else {
    
    for ( var iIndiceLocal in this.aDadosLocaisTrabalho ) {
      
      var oDadosLocal   = this.aDadosLocaisTrabalho[iIndiceLocal];
      
      if ( oDadosLocal == oLocalExclusao ) {
        delete(this.aDadosLocaisTrabalho[iIndiceLocal]);
        delete(oDadosLocal);
      }
    }
  }
  
  this.incluirRegistrosGrid();
  
  return true;
};


/**
 * Libera o registro para manutenção
 * Obs: Retira o registro da grid e mostra os dados nos campos para alteração
 * @param iCodigoLocalTrabalho
 */
DBViewLocaisDeTrabalho.prototype.liberaManutencaoRegistro = function( iCodigoLocalTrabalho ) {
  
  
  var oLocalTrabalho = this.getLocalTrabalho(iCodigoLocalTrabalho);
  
  this.oInputLocalTrabalhoCodigo.setValue(oLocalTrabalho.iCodigoLocal);
  this.oInputLocalTrabalhoDescricao.setValue(oLocalTrabalho.sDescricao);
  this.oInputEstrutural.setValue(oLocalTrabalho.sEstrutural);
  
  this.oInputQuantidade.setValue(oLocalTrabalho.iQuantidade);
  
  this.oInputPrincipal.checked = oLocalTrabalho.lPrincipal;
  
  this.oInputPercentual.setValue(oLocalTrabalho.nPercentual + "%");
  
  if ( this.oLocalEmManutencao == null) {
    this.oLocalEmManutencao = this.getLocalTrabalho( iCodigoLocalTrabalho );
  } else {
  this.aDadosLocaisTrabalho.push( this.oLocalEmManutencao );
  this.oLocalEmManutencao = this.getLocalTrabalho( iCodigoLocalTrabalho );
  }
  this.excluiRegistro( iCodigoLocalTrabalho, true);
  
  return true;
};


/**
 * Objeto que contem os dados dos locais para manipulação
 * @param iIndice
 * @param iEstrutural
 * @param sDescricao
 * @param lPrincipal
 * @param iQuantidade
 * @param iPercentual
 * @param iCodigoLocal
 */
DBViewLocaisDeTrabalho.prototype.adicionarLocalTrabalho = function(iEstrutural, sDescricao,
                                                                   lPrincipal , iQuantidade,
                                                                   iPercentual, iCodigoLocal) {
  
  var oLocalTrabalho          = new Object();
  oLocalTrabalho.iCodigoLocal = iCodigoLocal;
  oLocalTrabalho.sEstrutural  = iEstrutural;
  oLocalTrabalho.sDescricao   = sDescricao;
  oLocalTrabalho.lPrincipal   = lPrincipal;
  oLocalTrabalho.iQuantidade  = iQuantidade;
  oLocalTrabalho.nPercentual  = iPercentual;
  oLocalTrabalho.lAtivo       = true;
  
  this.aDadosLocaisTrabalho.push(oLocalTrabalho);
  
  if (lPrincipal ) {
    
    this.trocaLocalPrincipal(iCodigoLocal);
  }
  
  return true;
};


/**
 * Define novo Local Principal de trabalho para o servidor
 * @param iCodigoLocalTrabalho
 */
DBViewLocaisDeTrabalho.prototype.trocaLocalPrincipal = function( iCodigoLocalTrabalho ) {
  
  
  for ( var iIndiceDados in this.aDadosLocaisTrabalho ) {
      
    if ( !this.aDadosLocaisTrabalho[iIndiceDados] ) {
      
      continue;
    }
    
    var oLocalTrabalho = this.aDadosLocaisTrabalho[iIndiceDados];
    
    if ( oLocalTrabalho.iCodigoLocal == iCodigoLocalTrabalho ) {
      
      oLocalTrabalho.lPrincipal = true;
    } else {
      
      oLocalTrabalho.lPrincipal = false;
    }
  }
  
  return true;
};


/**
 * Salva Registros no Banco de Dados
 * @returns {Boolean}
 */
DBViewLocaisDeTrabalho.prototype.salvarRegistros = function() {
  
  var oInstancia = this;
  
  js_divCarregando('Salvando Alterações.', 'msgAjax');
  
  var oParam                  = new Object();
  var oAjax                   = new Object();
  
  oParam.sExec                = "salvarDadosLocaisTrabalho";
  oParam.oDadosLocaisTrabalho = this.aDadosLocaisTrabalho;
  oParam.iCodigoServidor      = this.iCodigoServidor;
  
  oAjax.method                = 'POST';
  oAjax.parameters            = 'json=' + Object.toJSON(oParam);
  oAjax.onComplete            = function(oAjax) {
                                  oInstancia.retornoSalvarLocais(oAjax);
                                };
                                 
  oAjax.asynchronous          = false;
  var oRequest                = new Ajax.Request( sUrl, oAjax );
  
  return true;
};


/**
 * retorno  Registros no Banco de Dados
 * @returns {Boolean}
 */
DBViewLocaisDeTrabalho.prototype.retornoSalvarLocais = function(oAjax) {
  
  js_removeObj('msgAjax');

  if (oAjax != null) {
    
    var oRetorno  = eval("(" + oAjax.responseText + ")");
   
    if ( oRetorno.status == 2 ) {
      
      alert(oRetorno.message.urlDecode().replace(/\\n/g, '\n'));
      return false;
    } 
    
    if (this.janelaAtiva == true) {
      
      this.oJanelaBase.destroy();
    }
    alert("Registros Alterados com Sucesso!");
    return true;
  }
};


/**
 * Pesquisa Locais de Trabalho (func)
 * @param mostra
 */
DBViewLocaisDeTrabalho.prototype.pesquisarLocalTrabalho = function(mostra) {

   if ( mostra == true ) {
     
     js_OpenJanelaIframe( '',
                          'db_iframe_rhlocaltrab',
                          'func_rhlocaltrab.php?funcao_js=parent.' + this.sNomeInstancia + 
                            '.retornoPesquisaAncora|rh55_codigo|rh55_descr|rh55_estrut|0',
                          'Pesquisa',
                          true,
                          '0'
                        );
   } else {

   if ( this.oInputLocalTrabalhoCodigo.getValue() != '' ) {
       
       js_OpenJanelaIframe( '',
                            'db_iframe_rhlocaltrab',
                            'func_rhlocaltrab.php?lRetornaEstrutural=true&pesquisa_chave=' + this.oInputLocalTrabalhoCodigo.getValue() + 
                              '&funcao_js=parent.'+ this.sNomeInstancia + '.retornoPesquisaAncora',
                            'Pesquisa',
                            false
                          );
     } else {
       
       this.oInputLocalTrabalhoDescricao.setValue( '' );
     }
   }
   
   if ( mostra == true ) {
     
     $("Jandb_iframe_rhlocaltrab").style.zIndex = 12000;
   }
   
   return true;
};


/**
 * Retorno da Pesquisa (func)
 */
DBViewLocaisDeTrabalho.prototype.retornoPesquisaAncora = function() {
  
  var aArgumentos = arguments;

  
  if (aArgumentos.length == 2) {
    
    this.oInputLocalTrabalhoDescricao.setValue( aArgumentos[0] );
    this.oInputLocalTrabalhoCodigo.setValue( '' );
    this.oInputEstrutural.setValue( '' );
    this.oInputLocalTrabalhoCodigo.getElement().focus();
    
    return false;
  
  } else if ( aArgumentos.length == 3 ) {
    
     var sRetornoLocalTrabalho = aArgumentos[0];
     var sRetornoEstrutural    = aArgumentos[1];
     var lRetornoErro          = aArgumentos[2]; 
     
     this.oInputLocalTrabalhoDescricao.setValue( sRetornoLocalTrabalho );
     this.oInputEstrutural.setValue( sRetornoEstrutural );
    
    return true;
  }
  
  var iCodigoLocalTrabalho  = aArgumentos[0];
  var sRetornoLocalTrabalho = aArgumentos[1];
  var sRetornoEstrutural    = aArgumentos[2];
  
   
  this.oInputLocalTrabalhoCodigo.setValue( iCodigoLocalTrabalho );
  this.oInputLocalTrabalhoDescricao.setValue( sRetornoLocalTrabalho );
  this.oInputEstrutural.setValue( sRetornoEstrutural );
  db_iframe_rhlocaltrab.hide ();
  
  return true;

};


DBViewLocaisDeTrabalho.prototype.atualizarPercentual = function( nValorInformado ) {
    
    var iTotalValorTotal  = new Number(this.getTotalQuantidadeLocaisTrabalho());
        iTotalValorTotal += new Number( nValorInformado );
        
    
        
    for ( iLocalTrabalho in this.aDadosLocaisTrabalho ) {
      
      if ( !this.aDadosLocaisTrabalho[iLocalTrabalho].iQuantidade ) {
        
        continue;
      }

      var oLocal         = this.aDadosLocaisTrabalho[iLocalTrabalho];
      oLocal.nPercentual = Math.round((oLocal.iQuantidade * 100) / iTotalValorTotal,2);
    }
    
    
    var nValorPercentual = Math.round((nValorInformado * 100) / iTotalValorTotal,2);
    
    return nValorPercentual;
};
  

DBViewLocaisDeTrabalho.prototype.getTotalQuantidadeLocaisTrabalho = function() {
     
  var iTotalQuandidade = new Number(0);
  
  for ( var iLocalTrabalho in this.aDadosLocaisTrabalho ) {
    
    if ( !this.aDadosLocaisTrabalho[iLocalTrabalho].iQuantidade ) {
      
      continue;
    }
    
    var oLocalTrabalho = this.aDadosLocaisTrabalho[iLocalTrabalho];
    
    /**
     * Caso o Item seja o que esta sendo alterado não deve somar ao total
     */
    if ( !oLocalTrabalho.lAtivo ) {
      
      continue;
    }
    
    iTotalQuandidade += new Number(oLocalTrabalho.iQuantidade);
    
  }
  
  return iTotalQuandidade;
}


/**
 * Função para atualizar os registros no objeto
 */
DBViewLocaisDeTrabalho.prototype.lancarRegistros = function() {
  
  if (this.oInputLocalTrabalhoCodigo.getValue() == '' ||
       (this.oInputQuantidade.getValue() == null || this.oInputQuantidade.getValue() == 0)) {
    
    alert('Favor Preencher corretamente os campos!');
    return false;
  }
  
  if (this.validarCodigoTrabalho(this.oInputLocalTrabalhoCodigo.getValue())) {
    
    alert('Local de Trabalho já lançado!');
    this.oInputLocalTrabalhoCodigo.setValue( '' );
    this.oInputEstrutural.setValue( '' );
    this.oInputLocalTrabalhoDescricao.setValue( '' );
    this.oInputLocalTrabalhoCodigo.getElement().focus();
    
    return false;
  }
  
  var nPercentual = this.atualizarPercentual( new Number(this.oInputQuantidade.getValue()) );
  
  this.adicionarLocalTrabalho(this.oInputEstrutural.getValue(), 
                              this.oInputLocalTrabalhoDescricao.getValue(),
                              this.oInputPrincipal.checked, 
                              this.oInputQuantidade.getValue(), 
                              nPercentual,
                              this.oInputLocalTrabalhoCodigo.getValue());
  
  this.oInputEstrutural.setValue('');
  this.oInputLocalTrabalhoDescricao.setValue('');
  this.oInputPrincipal.setValue('');
  this.oInputQuantidade.setValue('');
  this.oInputPercentual.setValue('');
  this.oInputLocalTrabalhoCodigo.setValue('');
  this.oLocalEmManutencao = null;
  
  if (this.oInputPrincipal.checked) {
    
    this.trocaLocalPrincipal(this.oInputLocalTrabalhoCodigo.getValue());
  }
  
  this.incluirRegistrosGrid();

  return true;
};


/**
 * Valida registro se local de trabalho já lançado
 * @param iCodigoLocalTrabalho integer
 */
DBViewLocaisDeTrabalho.prototype.validarCodigoTrabalho = function(iCodigoLocalTrabalho) {
  
  for ( var iIndiceLocal in this.aDadosLocaisTrabalho ) {
    
    var oDadosLocal   = this.aDadosLocaisTrabalho[iIndiceLocal];
    
    if (oDadosLocal.iCodigoLocal == iCodigoLocalTrabalho && oDadosLocal.lAtivo == true) {
      
      return true;
    }
  }

  return false;
};



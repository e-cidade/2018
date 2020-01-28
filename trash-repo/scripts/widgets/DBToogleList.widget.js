require_once('scripts/arrays.js');
require_once('scripts/datagrid.widget.js');
require_once('estilos/DBToogleList.css');

const DBTOOGLELIST_RIGHT  = 'R';
const DBTOOGLELIST_LEFT   = 'L';
const DBTOOGLELIST_TOP    = 'T';
const DBTOOGLELIST_BOTTOM = 'B';

/**
 * DBToogleList 
 * - componente para selecionar e ordenar itens entre duas grids
 *
 * @author jeferson.belmiro@dbseller.com.br
 * @constructor
 * @requires DBGrid
 * @param {Array} aHeaders - Headers das grids
 */
var DBToogleList = function( aHeaders ) {

  this.aHeaders         = aHeaders;
  this.oInformacoesGrid = {};
  this.aRowsSelect      = [];
  this.aRowsSelected    = [];

  /**
   * Elementos HTML
   */
  this.oElementos                                   = {};
  this.oElementos.oLinhasGrid                       = {};
  this.oElementos.oLinhasGrid.aRowsGridSelecao      = [];
  this.oElementos.oLinhasGrid.aRowsGridSelecionados = [];
}

/**
 * Inicializa componente, define os headers das grid pelos parametros informados no construtor
 *
 * @return void 
 */
DBToogleList.prototype.init = function() {

  this.oInformacoesGrid.aHeaders = [];
  this.oInformacoesGrid.aLabels  = [];
  this.oInformacoesGrid.aAligns  = [];

  for ( var iHeader = 0; iHeader < this.aHeaders.length; iHeader++ ) {

    var oHeader = this.aHeaders[iHeader];

    this.oInformacoesGrid.aHeaders.push(oHeader.sWidth);
    this.oInformacoesGrid.aLabels.push(oHeader.sLabel);
    this.oInformacoesGrid.aAligns.push(oHeader.sAlign);
  }
}

/**
 * Cria elementos HTML, grids e botoes
 * 
 * @return {Object} - Div com todos os elementos 
 */
DBToogleList.prototype.createElements = function() {

  /**
   * Div com os elementos html da grid da ESQUERDA, itens PARA SELECIONAR
   */
  this.oElementos.oListaParaSelecionar = document.createElement('div');
  this.oElementos.oListaParaSelecionar.setAttribute('class', 'toogleListSelect');

  /**
   * Div com os elementos html da grid da DIREITA, itens SELECINADOS
   */
  this.oElementos.oListaSelecionados = document.createElement('div');
  this.oElementos.oListaSelecionados.setAttribute('class', 'toogleListSelected');

  /**
   * Div com botoes de acao
   */
  this.oElementos.oBotoesAcao = document.createElement('div');
  this.oElementos.oBotoesAcao.setAttribute('class', 'toogleListActionButons');

  /**
   * Botao - Selecionar todos ( >> )
   */
  this.oElementos.oBotaoSelecionarTodos = document.createElement('input');
  this.oElementos.oBotaoSelecionarTodos.setAttribute('class', 'toogleListButton field-size1');
  this.oElementos.oBotaoSelecionarTodos.setAttribute('type', 'button');
  this.oElementos.oBotaoSelecionarTodos.setAttribute('value', '>>');
  this.oElementos.oBotaoSelecionarTodos.setAttribute('title', 'Selecionar todos os itens');

  /**
   * Botao - Remover selecao de todos ( << )
   */
  this.oElementos.oBotaoRemoverSelecaoTodos = document.createElement('input');
  this.oElementos.oBotaoRemoverSelecaoTodos.setAttribute('class', 'toogleListButton field-size1');
  this.oElementos.oBotaoRemoverSelecaoTodos.setAttribute('type', 'button');
  this.oElementos.oBotaoRemoverSelecaoTodos.setAttribute('value', '<<');
  this.oElementos.oBotaoRemoverSelecaoTodos.setAttribute('title', 'Remover seleção de todos os itens');

  /**
   * Botao - Selecionar linha ( > )
   */
  this.oElementos.oBotaoSelecionar = document.createElement('input');
  this.oElementos.oBotaoSelecionar.setAttribute('class', 'toogleListButton field-size1');
  this.oElementos.oBotaoSelecionar.setAttribute('type', 'button');
  this.oElementos.oBotaoSelecionar.setAttribute('value', '>');
  this.oElementos.oBotaoSelecionar.setAttribute('title', 'Selecionar itens marcados');

  /**
   * Botao - Remover selecao linha ( < )
   */
  this.oElementos.oBotaoRemoverSelecao = document.createElement('input');
  this.oElementos.oBotaoRemoverSelecao.setAttribute('class', 'toogleListButton field-size1');
  this.oElementos.oBotaoRemoverSelecao.setAttribute('type', 'button');
  this.oElementos.oBotaoRemoverSelecao.setAttribute('value', '<');
  this.oElementos.oBotaoRemoverSelecao.setAttribute('title', 'Removar seleção dos itens marcados');

  /**
   * Div com botoes de ordenacao 
   */
  this.oElementos.oBotoesOrdenacao = document.createElement('div');
  this.oElementos.oBotoesOrdenacao.setAttribute('class', 'toogleListOrderButons');

  /**
   * Botao - Mover para cima ( ^ )
   */
  this.oElementos.oBotaoMoverCima = document.createElement('input');
  this.oElementos.oBotaoMoverCima.setAttribute('class', 'toogleListButton field-size1');
  this.oElementos.oBotaoMoverCima.setAttribute('type', 'button');
  this.oElementos.oBotaoMoverCima.setAttribute('value', '^');
  this.oElementos.oBotaoMoverCima.setAttribute('title', 'Mover itens selecionados para cima');

  /**
   * Botao - Mover para baixo ( v )
   */
  this.oElementos.oBotaoMoverBaixo = document.createElement('input');
  this.oElementos.oBotaoMoverBaixo.setAttribute('class', 'toogleListButton field-size1');
  this.oElementos.oBotaoMoverBaixo.setAttribute('type', 'button');
  this.oElementos.oBotaoMoverBaixo.setAttribute('value', 'v');
  this.oElementos.oBotaoMoverBaixo.setAttribute('title', 'Mover itens selecionados para baixo');

  /**
   * Agrupa os boteos de ordenacao
   */
  this.oElementos.oBotoesOrdenacao.appendChild(this.oElementos.oBotaoMoverCima);
  this.oElementos.oBotoesOrdenacao.appendChild(this.oElementos.oBotaoMoverBaixo);

  /**
   * Agrupa os botoes de acao na div .toogleListActionButons
   */
  this.oElementos.oBotoesAcao.appendChild(this.oElementos.oBotaoSelecionar);
  this.oElementos.oBotoesAcao.appendChild(this.oElementos.oBotaoRemoverSelecao);
  this.oElementos.oBotoesAcao.appendChild(this.oElementos.oBotaoSelecionarTodos);
  this.oElementos.oBotoesAcao.appendChild(this.oElementos.oBotaoRemoverSelecaoTodos);

  /**
   * Div com todos os componentes
   */
  this.oElementos.oListas = document.createElement('div');
  this.oElementos.oListas.setAttribute('class', 'DBToogleListBox');
  this.oElementos.oListas.appendChild(this.oElementos.oListaParaSelecionar);

  /**
   * Coloca div com botoes de ordenacao na div com todos os elementos
   */
  this.oElementos.oListas.appendChild(this.oElementos.oBotoesOrdenacao);
  this.oElementos.oListas.appendChild(this.oElementos.oListaSelecionados);

  /**
   * Coloca div com botoes de acao na div com todos os elementos
   */
  this.oElementos.oListas.appendChild(this.oElementos.oBotoesAcao);

  return this.oElementos.oListas;
}

/**
 * Cria grids
 *
 * @return void
 */
DBToogleList.prototype.createTables = function() {

  /**
   * Grid com dados a serem selecionados 
   */
  var oGridSelect          = new DBGrid('oGridSelect');
  oGridSelect.sName        = 'oGridSelect';
  oGridSelect.nameInstance = 'oGridSelect';
  oGridSelect.setCellWidth( this.oInformacoesGrid.aHeaders );
  oGridSelect.setCellAlign( this.oInformacoesGrid.aAligns );
  oGridSelect.setHeader( this.oInformacoesGrid.aLabels );
  oGridSelect.show( this.oElementos.oListaParaSelecionar );
  oGridSelect.clearAll(true);

  this.oGridSelect = oGridSelect;

  /**
   * Grid com dados selecionados 
   */
  var oGridSelected          = new DBGrid('oGridSelected');
  oGridSelected.sName        = 'oGridSelected';
  oGridSelected.nameInstance = 'oGridSelected';
  oGridSelected.setCellWidth( this.oInformacoesGrid.aHeaders );
  oGridSelected.setCellAlign( this.oInformacoesGrid.aAligns );
  oGridSelected.setHeader( this.oInformacoesGrid.aLabels );
  oGridSelected.show( this.oElementos.oListaSelecionados );
  oGridSelected.clearAll(true);

  this.oGridSelected = oGridSelected;
}

/**
 * Adiciona elementos a grid a serem selecionados
 *
 * @param {Object} - Adiciona item na grid da esquerda, para selecioar
 */
DBToogleList.prototype.add = function( oItem ) {
  this.aRowsSelect.push(oItem);
}

/**
 * Renderiza as grid 
 *
 * @param {Array} - array com linhas selecionadas e marcadas, usado apenas para usar classe marcado
 * @return void
 */
DBToogleList.prototype.renderRows = function(aLinhasMarcadas) {

  var aIdLinhaSelecao      = [];
  var aIdLinhaSelecionados = [];

  /**
   * Limpa grid dos itens para selecionar ( grid da esquerda )
   */
  this.oGridSelect.clearAll(true);

  /**
   * Renderiza as linhas da Grid de Itens para selecionar
   */
  for ( var iLinha = 0; iLinha < this.aRowsSelect.length; iLinha++ ) {
    
    var aColunas = [];
    var oLinha   = this.aRowsSelect[iLinha];

    for ( var iHeader in this.aHeaders ) {
      
      if ( typeof this.aHeaders[iHeader] == 'function' ) {
        continue;
      }
      
      var oHeader   = this.aHeaders[iHeader];
      var sConteudo = "";
    
      if ( oLinha[oHeader.sId] && !js_empty(oLinha[oHeader.sId] ) ) {
        sConteudo = oLinha[oHeader.sId];
      }

      aColunas.push(sConteudo);
    }

    this.oGridSelect.addRow(aColunas);

    /**
     * Pega Id do dataGrid para manipulação
     */
    aIdLinhaSelecao.push(this.oGridSelect.aRows[iLinha].sId);
  }

  /**
   * Renderiza grid com itens para selecionar ( grid da esquerda )
   */
  this.oGridSelect.renderRows();

  /**
   * Limpa grid dos itens selecionados ( grid da direita )
   */
  this.oGridSelected.clearAll(true);

  /**
   * Renderiza as linhas da Grid de Itens Selecionados
   */
  for ( var iLinha = 0; iLinha < this.aRowsSelected.length; iLinha++ ) {

    var aColunas = [];
    var oLinha   = this.aRowsSelected[iLinha];
    
    for ( var iHeader in this.aHeaders ) {
      
      if ( typeof this.aHeaders[iHeader] == 'function' ) {
        continue;
      }
      
      var oHeader   = this.aHeaders[iHeader];
      var sConteudo = "";
    
      if ( oLinha[oHeader.sId] && !js_empty(oLinha[oHeader.sId] ) ) {
        sConteudo = oLinha[oHeader.sId];
      }

      aColunas.push(sConteudo);
    }
   
    this.oGridSelected.addRow(aColunas);
    
    /**
     * Pega Id do dataGrid para manipulação
     */
    aIdLinhaSelecionados.push(this.oGridSelected.aRows[iLinha].sId);
  }        

  /**
   * Renderiza as grids
   */
  this.oGridSelected.renderRows();

  /**
   * Linhas das grids - <tr>
   */
  this.oElementos.oLinhasGrid = {
    aRowsGridSelecao      : [],
    aRowsGridSelecionados : []
  }

  /**
   * Grid para selecionar ( grid da esquerda )
   * - Monta array com id das linhas
   */
  for ( var iIdLinha = 0; iIdLinha < aIdLinhaSelecao.length; iIdLinha++) {

    var oElemento = document.getElementById( aIdLinhaSelecao[iIdLinha] );
    this.oElementos.oLinhasGrid.aRowsGridSelecao.push(oElemento);
  }

  /**
   * Grid com itens selecionados ( grid da direita )
   * - Monta array com id das linhas
   * - Caso for informado parametro aLinhasMarcadas, adiciona classe marcado as linhas desse array
   */
  for ( var iIdLinha = 0; iIdLinha < aIdLinhaSelecionados.length; iIdLinha++) {

    var oElemento = document.getElementById( aIdLinhaSelecionados[iIdLinha] );

    /**
     * Mantem elementos marcados
     */
    if ( !js_empty(aLinhasMarcadas) && aLinhasMarcadas.in_array(iIdLinha) ) {
      oElemento.setAttribute('class', 'marcado');
    }

    this.oElementos.oLinhasGrid.aRowsGridSelecionados.push(oElemento);
  }
 
  this.setBehaviors();
}

/**
 * Define o comportamento dos botoes e linhas
 *
 * @return void
 */
DBToogleList.prototype.setBehaviors = function() {

  var me = this;

  var aLinhasSelecao      = this.oElementos.oLinhasGrid.aRowsGridSelecao;
  var aLinhasSelecionados = this.oElementos.oLinhasGrid.aRowsGridSelecionados;

  var iLinhasSelecao      = aLinhasSelecao.length;
  var iLinhasSelecionados = aLinhasSelecionados.length;

  /**
   * Grid itens PARA SELECIONAR (grid da esquerda)
   * - Define comportamentos das linhas das grids
   * - Adiciona ou remove classe 'marcado' ao clicar
   */
  for ( var iLinhaSelecao = 0; iLinhaSelecao < iLinhasSelecao; iLinhaSelecao++ ) {

    var oLinhaSelecao = aLinhasSelecao[iLinhaSelecao];

    oLinhaSelecao.onclick = function() {

      if ( this.getAttribute('class') == 'marcado') {

        this.setAttribute('class', 'normal');
        return;
      }

      this.setAttribute('class', 'marcado');
    }
  }

  /**
   * Grid SELECIONADOS (grid da direita)
   * - Adiciona ou remove classe 'marcado' ao clicar
   */
  for ( var iLinhaSelecionda = 0; iLinhaSelecionda < iLinhasSelecionados; iLinhaSelecionda++ ) {

    var oLinhaSelecao = aLinhasSelecionados[iLinhaSelecionda];

    oLinhaSelecao.onclick = function() {

      if ( this.getAttribute('class') == 'marcado') {

        this.setAttribute('class', 'normal');
        return;
      }

      this.setAttribute('class', 'marcado');
    }
  }

  /**
   * Botao - Selecionar ( > )
   * - move itens para grid dos selecionados(grid da direita)
   */
  this.oElementos.oBotaoSelecionar.onclick = function() {
    me.moveRows(DBTOOGLELIST_RIGHT); 
  }
     
  /**
   * Botao - Remover Selecao ( < )
   * - move itens para grid dos itens para selecionar(grid da esquerda)
   */
  this.oElementos.oBotaoRemoverSelecao.onclick = function() {
    me.moveRows(DBTOOGLELIST_LEFT); 
  }

  /**
   * Botao - Selecionar todos ( >> )
   * - move todos os itens para grid dos selecionador(grid da direita)
   */
  this.oElementos.oBotaoSelecionarTodos.onclick = function() {
   
    var aLinhasSelecao = me.oElementos.oLinhasGrid.aRowsGridSelecao;

    for ( var iLinhaSelecao = 0; iLinhaSelecao < aLinhasSelecao.length; iLinhaSelecao++ ) {
      aLinhasSelecao[iLinhaSelecao].setAttribute("class", "marcado");
    }

    me.moveRows(DBTOOGLELIST_RIGHT);
  }

  /**
   * Botao - Remover todos ( << )
   * - move todos os itens para grid dos itens para selecionar(grid da esquerda)
   */
  this.oElementos.oBotaoRemoverSelecaoTodos.onclick = function() {
   
    var aLinhasSelecionadas = me.oElementos.oLinhasGrid.aRowsGridSelecionados;

    for ( var iLinhaSelecionada = 0; iLinhaSelecionada < aLinhasSelecionadas.length; iLinhaSelecionada++ ) {
      aLinhasSelecionadas[iLinhaSelecionada].setAttribute("class", "marcado");
    }

    me.moveRows(DBTOOGLELIST_LEFT);
  }

  /**
   * Botao - mover linhas selecionadas para cima ( ^ ) 
   */
  this.oElementos.oBotaoMoverCima.onclick = function() {
    me.orderRows(DBTOOGLELIST_TOP);
  }

  /**
   * Botao - mover linhas selecionadas para baixo ( v ) 
   */
  this.oElementos.oBotaoMoverBaixo.onclick = function() {
    me.orderRows(DBTOOGLELIST_BOTTOM);
  }
}

/**
 * Move itens entre as grids
 * - Apos mover todas as linhas marcadas renderiza grid novamente
 *
 * @param {string} sDirecao - direcao que ira mover itens
 * @return void
 */
DBToogleList.prototype.moveRows = function(sDirecao) {

  /**
   * Direita/esquerda 
   */
  if ( sDirecao == DBTOOGLELIST_RIGHT ) {
       
    var aLinhasSelecao = this.oElementos.oLinhasGrid.aRowsGridSelecao;
    var iLinhasSelecao = aLinhasSelecao.length;
    var aRowsDestino   = this.aRowsSelected; 
    var aRowsOrigem    = this.aRowsSelect;

  } else {

    var aLinhasSelecao = this.oElementos.oLinhasGrid.aRowsGridSelecionados;
    var iLinhasSelecao = aLinhasSelecao.length;
    var aRowsDestino   = this.aRowsSelect; 
    var aRowsOrigem    = this.aRowsSelected;
  }

  /**
   * Percorre grid "origem" e limpa linhas que foram marcadas, class marcado
   */
  for ( var iLinhaSelecao = 0; iLinhaSelecao < iLinhasSelecao; iLinhaSelecao++ ) {

    var oLinhaSelecao = aLinhasSelecao[iLinhaSelecao];

    /**
     * Substitui itens selecionados por string vazia para posteriormente remover
     */
    if ( oLinhaSelecao.getAttribute('class') == 'marcado') {

      aRowsDestino.push( aRowsOrigem[iLinhaSelecao] );
      aRowsOrigem.splice( iLinhaSelecao , 1, '' );
    }
  }

  /**
   * Remove linhas vazias da grid origem
   */
  for ( var iItemRemover = 0; iItemRemover < aRowsOrigem.length; iItemRemover++ ) {

    if ( aRowsOrigem[ iItemRemover ] == '' ) {

      aRowsOrigem.splice(iItemRemover, 1);
      iItemRemover--;
    }
  }

  this.renderRows();
}

/**
 * Move linhas da grid dos itens selecionados ( grid da direita ) 
 * - Apos mover todas as linhas marcadas renderiza grid novamente
 *
 * @param {string} sDirecao - direcao que ira mover itens, cima/baixo
 * @return void
 */
DBToogleList.prototype.orderRows = function(sDirecao) {

  var aLinhasSelecionadas = this.oElementos.oLinhasGrid.aRowsGridSelecionados;
  var iLinhasSelecionadas = aLinhasSelecionadas.length;
  var aLinhasMarcadas     = new Array();

  /**
   * Percorre grid com itens selecionados e move itens marcados para cima ou para baixo
   */
  for ( var iLinhaSelecionada = 0; iLinhaSelecionada < iLinhasSelecionadas; iLinhaSelecionada++ ) {

    /**
     * Objeto com elemento html <tr>
     */
    var oLinhaSelecionada = aLinhasSelecionadas[iLinhaSelecionada];

    /**
     * Itens marcados
     * - Verifica qual direcao mover itens marcados
     */
    if ( oLinhaSelecionada.getAttribute('class') == 'marcado') {

      /**
       * Cima ( ^ )
       * Se nao for a primeira linha, move item atual uma linha acima
       */
      if ( sDirecao == DBTOOGLELIST_TOP ) {

        if ( iLinhaSelecionada == 0 ) {

          aLinhasMarcadas.push(iLinhaSelecionada);
          continue;
        }

        var iLinhaAnterior = iLinhaSelecionada - 1;
      }

      /**
       * Cima ( v )
       * Se nao for a ultima linha, move item atual uma linha abaixo
       */
      if ( sDirecao == DBTOOGLELIST_BOTTOM ) {

        if ( iLinhaSelecionada + 1 == iLinhasSelecionadas ) {

          aLinhasMarcadas.push(iLinhaSelecionada);
          continue;
        }

        var iLinhaAnterior = iLinhaSelecionada + 1;
      }

      aLinhasMarcadas.push(iLinhaAnterior);
      var oLinhaSelecionada = this.aRowsSelected[iLinhaSelecionada];

      /**
       * 1 - Substitui linha atual, selecionada, pela anterior(uma linha acima ou abaixo) 
       * 2 - Substitui linha anterior pela linha atual, selecionada
       */
      this.aRowsSelected.splice( iLinhaSelecionada, 1, this.aRowsSelected[iLinhaAnterior]);
      this.aRowsSelected.splice( iLinhaAnterior, 1, oLinhaSelecionada );
    }
  }

  this.renderRows(aLinhasMarcadas);
}

/**
 * Renderiza elemento ao elemento informado
 *
 * @param {Object} oElement - Elemento html onde ira renderizar 
 * @return void
 */
DBToogleList.prototype.show = function( oElement ) {

  this.init();

  oElement.innerHTML = '';

  var oElementos = this.createElements();
  oElement.appendChild( oElementos );
 
  this.createTables();
  this.renderRows();
}

/**
 * Retorna objeto com itens selecionados ( grid da direita ) 
 *
 * @return {Object} - Objeto com propriedades iguais ao objeto definino no construtor 
 */
DBToogleList.prototype.getSelected = function() {
  return this.aRowsSelected;
}

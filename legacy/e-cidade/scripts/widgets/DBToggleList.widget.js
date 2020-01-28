require_once('scripts/arrays.js');
require_once('scripts/object.js');
require_once('scripts/datagrid.widget.js');
require_once('estilos/DBToggleList.css');

const DBTOGGLELIST_RIGHT  = 'R';
const DBTOGGLELIST_LEFT   = 'L';
const DBTOGGLELIST_TOP    = 'T';
const DBTOGGLELIST_BOTTOM = 'B';

/**
 * DBToggleList
 * - componente para selecionar e ordenar itens entre duas grids
 *
 * @author jeferson.belmiro@dbseller.com.br
 * @constructor
 * @requires DBGrid
 *
 * @param {Array} aHeaders - Headers das grids
 *   [{sWidth : '100px',
 *     sLabel : 'Número',
 *     sId    : 'numero',
 *     sAlign : 'left'}];
 * @param
 * @param {string} sIdGrid - usado para compor um nome único da grid
 */
var DBToggleList = function( aHeaders, oLabels, sIdGrid ) {

  if (sIdGrid == null) {
    this.iIdInstanceGrid  = DBToggleList.iQuantidadeGrids++;//ESTATICO
  } else {
    this.iIdInstanceGrid  = sIdGrid;
  }

  this.aHeaders         = aHeaders;
  this.oInformacoesGrid = {};
  this.oInformacoesGrid.oLabels        = oLabels || {};
  this.aRowsSelect      = [];
  this.aRowsSelected    = [];
  this.lEnabled         = true;

  this.lViewButtonOrder = true;

  /**
   * Elementos HTML
   */
  this.oElementos                                   = {};
  this.oElementos.oLinhasGrid                       = {};
  this.oElementos.oLinhasGrid.aRowsGridSelecao      = [];
  this.oElementos.oLinhasGrid.aRowsGridSelecionados = [];

  /**
   * Callbacks do componente
   */
  var me = this;

  this.oElementos.oLinhasGrid.oCallbacks = {
    selecao : {
      onclick: function() {

        if ( this.getAttribute('class') == 'marcado') {
          this.setAttribute('class', 'normal');
          return;
        }
        this.setAttribute('class', 'marcado');
      },
      ondblclick: function() {
        this.setAttribute('class', 'marcado');
        me.moveRows(DBTOGGLELIST_RIGHT);
      },
      afterMove: function() {

      }

    },
    selecionados: {
      onclick: function() {

        if ( this.getAttribute('class') == 'marcado') {
          this.setAttribute('class', 'normal');
          return;
        }
        this.setAttribute('class', 'marcado');
      },
      ondblclick: function() {
        this.setAttribute('class', 'marcado');
        me.moveRows(DBTOGGLELIST_LEFT);
      },
      afterMove: function() {

      }
    }
  };

};
/**
 * Grids Instanciadas
 */
DBToggleList.oInstances       = {}; //ESTATICO
DBToggleList.iQuantidadeGrids = 0;

/**
 * Inicializa componente, define os headers das grid pelos parametros informados no construtor
 *
 * @return {void}
 */
DBToggleList.prototype.init = function() {

  this.oInformacoesGrid.aHeaders       = [];
  this.oInformacoesGrid.aLabels        = [];
  this.oInformacoesGrid.aAligns        = [];
  this.oInformacoesGrid.aHeadersHidden = [];

  for ( var iHeader = 0; iHeader < this.aHeaders.length; iHeader++ ) {

    var oHeader = this.aHeaders[iHeader];

    /**
     * Esconde headers com propriedade 'lVisible' : false
     */
    if ( oHeader.lVisible == false ) {
      this.oInformacoesGrid.aHeadersHidden.push(iHeader);
    }

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
DBToggleList.prototype.createElements = function() {

  /**
   * Div com os elementos html da grid da ESQUERDA, itens PARA SELECIONAR
   */
  this.oElementos.oListaParaSelecionar = document.createElement('div');
  this.oElementos.oListaParaSelecionar.setAttribute('class', 'toggleListSelect');

  /**
   * Label da primeira grid
   */
   if (this.oInformacoesGrid.oLabels["selecao"]) {
    this.oElementos.oLabelSelecao = document.createElement("div");
    this.oElementos.oLabelSelecao.setAttribute("class", "labelGrid");
    this.oElementos.oLabelSelecao.innerHTML = this.oInformacoesGrid.oLabels["selecao"];
   }

   /**
    * Label da segunda grid
    */
   if (this.oInformacoesGrid.oLabels["selecionados"]) {
    this.oElementos.oLabelSelecionados = document.createElement("div");
    this.oElementos.oLabelSelecionados.setAttribute("class", "labelGrid");
    this.oElementos.oLabelSelecionados.innerHTML = this.oInformacoesGrid.oLabels["selecionados"];
   }

  /**
   * Div com os elementos html da grid da DIREITA, itens SELECINADOS
   */
  this.oElementos.oListaSelecionados = document.createElement('div');
  this.oElementos.oListaSelecionados.setAttribute('class', 'toggleListSelected');

  /**
   * Div com botoes de acao
   */
  this.oElementos.oBotoesAcao = document.createElement('div');
  this.oElementos.oBotoesAcao.setAttribute('class', 'toggleListActionButons');

  /**
   * Botao - Selecionar todos ( >> )
   */
  this.oElementos.oBotaoSelecionarTodos = document.createElement('input');
  this.oElementos.oBotaoSelecionarTodos.setAttribute('class', 'toggleListButton field-size1');
  this.oElementos.oBotaoSelecionarTodos.setAttribute('type', 'button');
  this.oElementos.oBotaoSelecionarTodos.setAttribute('value', '>>');
  this.oElementos.oBotaoSelecionarTodos.setAttribute('title', 'Selecionar todos os itens');

  /**
   * Botao - Remover selecao de todos ( << )
   */
  this.oElementos.oBotaoRemoverSelecaoTodos = document.createElement('input');
  this.oElementos.oBotaoRemoverSelecaoTodos.setAttribute('class', 'toggleListButton field-size1');
  this.oElementos.oBotaoRemoverSelecaoTodos.setAttribute('type', 'button');
  this.oElementos.oBotaoRemoverSelecaoTodos.setAttribute('value', '<<');
  this.oElementos.oBotaoRemoverSelecaoTodos.setAttribute('title', 'Remover seleção de todos os itens');

  /**
   * Botao - Selecionar linha ( > )
   */
  this.oElementos.oBotaoSelecionar = document.createElement('input');
  this.oElementos.oBotaoSelecionar.setAttribute('class', 'toggleListButton field-size1');
  this.oElementos.oBotaoSelecionar.setAttribute('type', 'button');
  this.oElementos.oBotaoSelecionar.setAttribute('value', '>');
  this.oElementos.oBotaoSelecionar.setAttribute('title', 'Selecionar itens marcados');

  /**
   * Botao - Remover selecao linha ( < )
   */
  this.oElementos.oBotaoRemoverSelecao = document.createElement('input');
  this.oElementos.oBotaoRemoverSelecao.setAttribute('class', 'toggleListButton field-size1');
  this.oElementos.oBotaoRemoverSelecao.setAttribute('type', 'button');
  this.oElementos.oBotaoRemoverSelecao.setAttribute('value', '<');
  this.oElementos.oBotaoRemoverSelecao.setAttribute('title', 'Remover seleção dos itens marcados');

  /**
   * Div com botoes de ordenacao
   */
  this.oElementos.oBotoesOrdenacao = document.createElement('div');
  this.oElementos.oBotoesOrdenacao.setAttribute('class', 'toggleListOrderButons');
  if (!this.lViewButtonOrder) {
    this.oElementos.oBotoesOrdenacao.style.display = 'none';
  }

  /**
   * Botao - Mover para cima ( ^ )
   */
  this.oElementos.oBotaoMoverCima = document.createElement('input');
  this.oElementos.oBotaoMoverCima.setAttribute('class', 'toggleListButton field-size1');
  this.oElementos.oBotaoMoverCima.setAttribute('type', 'button');
  this.oElementos.oBotaoMoverCima.setAttribute('value', '^');
  this.oElementos.oBotaoMoverCima.setAttribute('title', 'Mover itens selecionados para cima');

  /**
   * Botao - Mover para baixo ( v )
   */
  this.oElementos.oBotaoMoverBaixo = document.createElement('input');
  this.oElementos.oBotaoMoverBaixo.setAttribute('class', 'toggleListButton field-size1');
  this.oElementos.oBotaoMoverBaixo.setAttribute('type', 'button');
  this.oElementos.oBotaoMoverBaixo.setAttribute('value', 'v');
  this.oElementos.oBotaoMoverBaixo.setAttribute('title', 'Mover itens selecionados para baixo');

  /**
   * Agrupa os boteos de ordenacao
   */
  this.oElementos.oBotoesOrdenacao.appendChild(this.oElementos.oBotaoMoverCima);
  this.oElementos.oBotoesOrdenacao.appendChild(this.oElementos.oBotaoMoverBaixo);

  /**
   * Agrupa os botoes de acao na div .toggleListActionButons
   */
  this.oElementos.oBotoesAcao.appendChild(this.oElementos.oBotaoSelecionar);
  this.oElementos.oBotoesAcao.appendChild(this.oElementos.oBotaoRemoverSelecao);
  this.oElementos.oBotoesAcao.appendChild(this.oElementos.oBotaoSelecionarTodos);
  this.oElementos.oBotoesAcao.appendChild(this.oElementos.oBotaoRemoverSelecaoTodos);

  /**
   * Div com todos os componentes
   */
  this.oElementos.oListas = document.createElement('div');
  this.oElementos.oListas.setAttribute('class', 'DBToggleListBox');
  this.oElementos.oListas.appendChild(this.oElementos.oListaParaSelecionar);

  /**
   * Coloca div com botoes de acao na div com todos os elementos
   */
  this.oElementos.oListas.appendChild(this.oElementos.oBotoesAcao);
  /**
   * Coloca div com botoes de ordenacao na div com todos os elementos
   */
  this.oElementos.oListas.appendChild(this.oElementos.oListaSelecionados);
  this.oElementos.oListas.appendChild(this.oElementos.oBotoesOrdenacao);


  return this.oElementos.oListas;
}

/**
 * Remove as linhas da grid do lado do select
 */
DBToggleList.prototype.clearSelect = function() {

  var sNameInstanceSelect = 'oGridSelect'   + this.iIdInstanceGrid;
  DBToggleList.oInstances[sNameInstanceSelect].clearAll(true);
  this.aRowsSelect = [];
};

/**
 * Remove as linhas da grid do lado do selected
 */
DBToggleList.prototype.clearSelected = function() {

  var sNameInstanceSelected = 'oGridSelected'   + this.iIdInstanceGrid;
  DBToggleList.oInstances[sNameInstanceSelected].clearAll(true);
  this.aRowsSelected = [];
};

/**
 * Remove todas as linhas de ambas grid
 */
DBToggleList.prototype.clearAll = function () {

  this.clearSelect();
  this.clearSelected();
};


/**
 * Cria grids
 * @return void
 */
DBToggleList.prototype.createTables = function() {


  var sNameInstanceSelect                = 'oGridSelect'   + this.iIdInstanceGrid;
  var sNameInstanceSelected              = 'oGridSelected' + this.iIdInstanceGrid;

  DBToggleList.oInstances[sNameInstanceSelect]   = new DBGrid('oGridSelect');
  DBToggleList.oInstances[sNameInstanceSelected] = new DBGrid('oGridSelected');
  /**
   * Grid com dados a serem selecionados
   */
  var oGridSelect          = DBToggleList.oInstances[sNameInstanceSelect];
  oGridSelect.sName        = sNameInstanceSelect;
  oGridSelect.nameInstance = "DBToggleList.oInstances." + sNameInstanceSelect;
  oGridSelect.setCellWidth( this.oInformacoesGrid.aHeaders );
  oGridSelect.setCellAlign( this.oInformacoesGrid.aAligns );
  oGridSelect.setHeader( this.oInformacoesGrid.aLabels );

  /**
   * Grid com dados selecionados
   */

  var oGridSelected          = DBToggleList.oInstances[sNameInstanceSelected];
  oGridSelected.sName        = sNameInstanceSelected;
  oGridSelected.nameInstance = "DBToggleList.oInstances." + sNameInstanceSelected;
  oGridSelected.setCellWidth( this.oInformacoesGrid.aHeaders );
  oGridSelected.setCellAlign( this.oInformacoesGrid.aAligns );
  oGridSelected.setHeader( this.oInformacoesGrid.aLabels );

  /**
   * Esconde header que foram passados pelo construtor como 'lVisible' : false
   */
  for ( var iHidden = 0; iHidden < this.oInformacoesGrid.aHeadersHidden.length; iHidden++ ) {

    oGridSelect.aHeaders[this.oInformacoesGrid.aHeadersHidden[iHidden]].lDisplayed = false;
    oGridSelected.aHeaders[this.oInformacoesGrid.aHeadersHidden[iHidden]].lDisplayed = false;
  }

  oGridSelect.show( this.oElementos.oListaParaSelecionar );
  oGridSelect.clearAll(true);

  if (this.oElementos["oLabelSelecao"]) {
    this.oElementos.oListaParaSelecionar.insertBefore(this.oElementos.oLabelSelecao, this.oElementos.oListaParaSelecionar.firstChild);
  }

  oGridSelected.show( this.oElementos.oListaSelecionados );
  oGridSelected.clearAll(true);

  if (this.oElementos["oLabelSelecionados"]) {
    this.oElementos.oListaSelecionados.insertBefore(this.oElementos.oLabelSelecionados, this.oElementos.oListaSelecionados.firstChild);
  }

  this.oGridSelect   = oGridSelect;
  this.oGridSelected = oGridSelected;

}

/**
 * Adiciona elementos a grid a serem selecionados ( grid da esquerda )
 *
 * @param {Object} - Adiciona item na grid da esquerda, para selecionar
 */
DBToggleList.prototype.addSelect = function( oItem ) {

  this.aRowsSelect.push(oItem);
}

/**
 * Adiciona elementos a grid dos selecionados ( grid da direita )
 *
 * @param {Object} - Adiciona item na grid da direita, selecionados
 */
DBToggleList.prototype.addSelected = function( oItem ) {
  this.aRowsSelected.push(oItem);
}

/**
 * Renderiza as grid
 *
 * @param {Array} - array com linhas selecionadas e marcadas, usado apenas para usar classe marcado
 * @return void
 */
DBToggleList.prototype.renderRows = function(aLinhasMarcadas) {

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
  this.oElementos.oLinhasGrid.aRowsGridSelecao      = [];
  this.oElementos.oLinhasGrid.aRowsGridSelecionados = [];

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
DBToggleList.prototype.setBehaviors = function() {

  var me = this;

  var aLinhasSelecao      = this.oElementos.oLinhasGrid.aRowsGridSelecao;
  var aLinhasSelecionados = this.oElementos.oLinhasGrid.aRowsGridSelecionados;

  var iLinhasSelecao      = aLinhasSelecao.length;
  var iLinhasSelecionados = aLinhasSelecionados.length;

  /**
   * Verifica se as ações devem estar desabilitada.
   */
  if ( !this.lEnabled ) {

    this.oElementos.oBotaoSelecionarTodos.disabled     = true;
    this.oElementos.oBotaoRemoverSelecaoTodos.disabled = true;
    this.oElementos.oBotaoSelecionar.disabled          = true;
    this.oElementos.oBotaoRemoverSelecao.disabled      = true;
    this.oElementos.oBotaoMoverCima.disabled           = true;
    this.oElementos.oBotaoMoverBaixo.disabled          = true;

    return false;
  }

  /**
   * Grid itens PARA SELECIONAR (grid da esquerda)
   * - Define comportamentos das linhas das grids
   * - Adiciona ou remove classe 'marcado' ao clicar
   */
  for ( var iLinhaSelecao = 0; iLinhaSelecao < iLinhasSelecao; iLinhaSelecao++ ) {

    var oLinhaSelecao = aLinhasSelecao[iLinhaSelecao];

    /**
     * Click
     * - Deixa marcado linha clicada
     */
    oLinhaSelecao.onclick = this.oElementos.oLinhasGrid.oCallbacks.selecao.onclick;

    /**
     * Click duplo com botao esquerdo do mouse
     * - Move linha clicada e linhas marcadas, para grid dos itens selecionados( grid da direita )
     */
    oLinhaSelecao.ondblclick = this.oElementos.oLinhasGrid.oCallbacks.selecao.ondblclick;

  }

  /**
   * Grid SELECIONADOS (grid da direita)
   * - Adiciona ou remove classe 'marcado' ao clicar
   */
  for ( var iLinhaSelecionda = 0; iLinhaSelecionda < iLinhasSelecionados; iLinhaSelecionda++ ) {

    var oLinhaSelecao = aLinhasSelecionados[iLinhaSelecionda];

    /**
     * Click
     * - Deixa marcado linha clicada
     */
    oLinhaSelecao.onclick = this.oElementos.oLinhasGrid.oCallbacks.selecionados.onclick;

    /**
     * Click duplo com botao esquerdo do mouse
     * - Move linha clicada e linhas marcadas, para grid dos itens para selecionar( grid da esquerda )
     */
    oLinhaSelecao.ondblclick = this.oElementos.oLinhasGrid.oCallbacks.selecionados.ondblclick;

  }

  /**
   * Botao - Selecionar ( > )
   * - move itens para grid dos selecionados(grid da direita)
   */
  this.oElementos.oBotaoSelecionar.onclick = function() {
    me.moveRows(DBTOGGLELIST_RIGHT);
  };

  /**
   * Botao - Remover Selecao ( < )
   * - move itens para grid dos itens para selecionar(grid da esquerda)
   */
  this.oElementos.oBotaoRemoverSelecao.onclick = function() {
    me.moveRows(DBTOGGLELIST_LEFT);
  };

  /**
   * Botao - Selecionar todos ( >> )
   * - move todos os itens para grid dos selecionador(grid da direita)
   */
  this.oElementos.oBotaoSelecionarTodos.onclick = function() {

    var aLinhasSelecao = me.oElementos.oLinhasGrid.aRowsGridSelecao;

    for ( var iLinhaSelecao = 0; iLinhaSelecao < aLinhasSelecao.length; iLinhaSelecao++ ) {
      aLinhasSelecao[iLinhaSelecao].setAttribute("class", "marcado");
    }

    me.moveRows(DBTOGGLELIST_RIGHT);
  };

  /**
   * Botao - Remover todos ( << )
   * - move todos os itens para grid dos itens para selecionar(grid da esquerda)
   */
  this.oElementos.oBotaoRemoverSelecaoTodos.onclick = function() {

    var aLinhasSelecionadas = me.oElementos.oLinhasGrid.aRowsGridSelecionados;

    for ( var iLinhaSelecionada = 0; iLinhaSelecionada < aLinhasSelecionadas.length; iLinhaSelecionada++ ) {
      aLinhasSelecionadas[iLinhaSelecionada].setAttribute("class", "marcado");
    }

    me.moveRows(DBTOGGLELIST_LEFT);
  };

  /**
   * Botao - mover linhas selecionadas para cima ( ^ )
   */
  this.oElementos.oBotaoMoverCima.onclick = function() {
    me.orderRows(DBTOGGLELIST_TOP);
  };

  /**
   * Botao - mover linhas selecionadas para baixo ( v )
   */
  this.oElementos.oBotaoMoverBaixo.onclick = function() {
    me.orderRows(DBTOGGLELIST_BOTTOM);
  };
};

/**
 * Move itens entre as grids
 * - Apos mover todas as linhas marcadas renderiza grid novamente
 *
 * @param {string} sDirecao - direcao que ira mover itens
 * @return void
 */
DBToggleList.prototype.moveRows = function(sDirecao) {

  /**
   * Direita/esquerda
   */
  if ( sDirecao == DBTOGGLELIST_RIGHT ) {

    var aLinhasSelecao   = this.oElementos.oLinhasGrid.aRowsGridSelecao;
    var iLinhasSelecao   = aLinhasSelecao.length;
    var aRowsDestino     = this.aRowsSelected;
    var aRowsOrigem      = this.aRowsSelect;
    var oCallbackDestino = this.oElementos.oLinhasGrid.oCallbacks.selecionados;

  } else {

    var aLinhasSelecao   = this.oElementos.oLinhasGrid.aRowsGridSelecionados;
    var iLinhasSelecao   = aLinhasSelecao.length;
    var aRowsDestino     = this.aRowsSelect;
    var aRowsOrigem      = this.aRowsSelected;
    var oCallbackDestino = this.oElementos.oLinhasGrid.oCallbacks.selecao;
  }

  var aEnviados = [];

  /**
   * Percorre grid "origem" e limpa linhas que foram marcadas, class marcado
   */
  for ( var iLinhaSelecao = 0; iLinhaSelecao < iLinhasSelecao; iLinhaSelecao++ ) {

    var oLinhaSelecao = aLinhasSelecao[iLinhaSelecao];

    /**
     * Substitui itens selecionados por string vazia para posteriormente remover
     */

    if ( oLinhaSelecao.getAttribute('class') == 'marcado') {

      /**
       * Obtem o item atual da tela, para enviar para o item de destino
       */
      var me            = this;
      var aOrigem       = {};
      var oLinhaAtual   = $$('#'+oLinhaSelecao.getAttribute('id') + ' td');
      var aConteudo     = [];
      oLinhaAtual.each(function(oElemento){
        aConteudo.push(oElemento.innerHTML)
      })

      me.aHeaders.each(function(oElemento, i) {

        aOrigem[oElemento.sId] = aConteudo[i];
      })

      aOrigem = mergeObject(aRowsOrigem[iLinhaSelecao], aOrigem);
      aEnviados.push(aOrigem);

      aRowsDestino.push( aOrigem );
      aRowsOrigem.splice( iLinhaSelecao , 1, '' );

    }


  }

  /**
   * Executa callback após mover os registros
   */
  oCallbackDestino.afterMove(aEnviados)

  /**
   * Remove linhas vazias da grid origem
   */
  for ( var iItemRemover = 0; iItemRemover < aRowsOrigem.length; iItemRemover++ ) {

    if ( aRowsOrigem[ iItemRemover ] == '' ) {
      aRowsOrigem[ iItemRemover ]
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
DBToggleList.prototype.orderRows = function(sDirecao) {

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
      if ( sDirecao == DBTOGGLELIST_TOP ) {

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
      if ( sDirecao == DBTOGGLELIST_BOTTOM ) {

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
 *iss1_processararquivosimplesnacional.RPC.php
 * @param {Object} oElement - Elemento html onde ira renderizar
 * @return void
 */
DBToggleList.prototype.show = function( oElement ) {

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
DBToggleList.prototype.getSelected = function() {
  return this.aRowsSelected;
};

DBToggleList.prototype.closeOrderButtons = function () {
  this.lViewButtonOrder = false;
};

/**
 * Desabilita os botões de seleção
 * Apos desabilitar deve ser chamado metodo show para ter efeito
 */
DBToggleList.prototype.disable = function() {
  this.lEnabled = false;
};

/**
 * Habilita os botões de seleção e ordenacao
 * Apos habilitar deve ser chamado metodo show para ter efeito
 */
DBToggleList.prototype.enable = function() {
  this.lEnabled = true;
};


/**
 * Define os callbacks de comportamento para as linha da GRID
 * @example
 *  {selecao: {
 *    onclick: function() {
 *    },
 *    ondbclick :function() {
 *    }
 *  }}
 */
DBToggleList.prototype.setCallback = function(oCallbacks) {

  for (tipoCallback in oCallbacks) {

    for (callback in oCallbacks[tipoCallback]) {
      this.oElementos.oLinhasGrid.oCallbacks[tipoCallback][callback] = oCallbacks[tipoCallback][callback];
    }

  }
};

/**
 * Seta os labels das grids
 * @example {selecao: "Grid 1", selecionados: "Grid 2"}
 */
DBToggleList.prototype.setLabels = function(oLabels) {
  this.oInformacoesGrid.oLabels = oLabels;
};


DBToggleList.prototype.getElement = function(iSequencial) {

  for (i = 0; i < this.aRowsSelect.length; i++) {
    var oElemento = this.aRowsSelect[i];

    if (oElemento.iSequencial == iSequencial){
      return oElemento
    }
  }

  for (i = 0; i < this.aRowsSelected.length; i++) {
    var oElemento = this.aRowsSelected[i];

    if (oElemento.iSequencial == iSequencial){
      return oElemento
    }
  }

};

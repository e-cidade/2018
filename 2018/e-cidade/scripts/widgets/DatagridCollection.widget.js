(function(exports, Collection, DBGrid) {

  if (Collection === undefined) {
    throw "O Objeto 'Collection' deve estar carregado em Memoria.";
  }
  if (DBGrid  === undefined) {
    throw "O Objeto 'DBGrid' deve estar carregado em Memoria.";
  }

  /**
   *  Representação da Coleção em forma de Grid
   *
   *  @return {void}
   */
  var DatagridCollection = function(ChildCollection, sIdGrid) {

    if (!(ChildCollection instanceof Collection)) {
      throw "Uma instância de Collection é necessária";
    }

    this.collection               = ChildCollection;
    this.options                  = mergeObject({}, DatagridCollection.GRID_OPTIONS);
    this.columns                  = {};

    this.gridID                   = "collection_" + (Date.now() * Math.random()).floor();

    if (sIdGrid != null) {
      this.gridID = sIdGrid;
    }
    this.grid                     = new DBGrid(this.gridID);
    this.grid.nameInstance        = "DBGrid.instances." + this.gridID;
    this.selectedItem             = null;
    this.selectedItens            = [];
    this.actions                  = new Collection();
	  this.sEventSelectRow          = null;
    this.actions.setId("uid");     
    this.aColumnsDisplayed = [];

    DBGrid.instances              = window.DBGrid.instances || {};
    DBGrid.instances[this.gridID] = this.grid;
  };

  DatagridCollection.prototype = {

    "_getActionUid" : function(label) {
      return "action_" + label.toLowerCase().removeAcento().replace(/\s/g, "_");
    },

    /**
     * Adiciona um botão de Ação a Grid
     */
    "addAction": function(label, title, callback) {

      callback = callback || function() {
        console.log("Ação sem callback");
      };

      var uid = this._getActionUid(label);
      this.actions.add({"uid" : uid, "label" : label, "title" : title || label, "callback" : callback});
      return this;
    },

    /**
     *  Configura as colunas da Grid
     *
     *  @return {Void}
     */
    "_configureColumns" : function() {

      var aLabel = [], aAlign = [], aWidth = [];

      /**
       * Percorre as colunas adicioandas e cria cabeçalhos, define alinhamentos e largura
       */
      for(var sColuna in this.columns) {

        var oColuna = this.columns[sColuna];
        aLabel.push(oColuna.label);
        aAlign.push(oColuna.align);
        aWidth.push(oColuna.width);
      }

      /**
       * Ações do CRUD
       */
      if (!!this.options.update) {

        this.addAction("A", "Alterar", function(event, itemCollection) {
          return this._events.onclickupdate(event, itemCollection);
        }.bind(this));
      }

      if (!!this.options.delete) {

        this.addAction("E", "Excluir", function(event, itemCollection) {
          return this._events.onclickdelete(event, itemCollection);
        }.bind(this));
      }


      /**
       * Caso alguma ação seja definida adiciona a coluna ações
       */
      if (this.actions.get().length > 0) {

        aLabel.push(this.options.action.label);
        aAlign.push(this.options.action.alingn);
        aWidth.push(this.options.action.width);
      }

      this.grid.setCellWidth(aWidth);
      this.grid.setCellAlign(aAlign);
      this.grid.setHeader(aLabel);
    },

    /**
     *  Cria os Eventos da Visualização da Coleção na instancia
     *
     *  @return {void}
     */
    "_makeEvents" : function() {

      var makeCallback = function(action, element, itemCollection) {

        element.observe("click", function(event){
          return action.callback(event, itemCollection);
        });

      }.bind(this);

      for(var action of this.actions.get()) {

        for (var element of $$("." + action.uid + "_" + this.gridID) ) {
          makeCallback(action, element, this.collection.get(element.getAttribute("collection_id")));
        }
      }


    },

    /**
     *  Retorna os botões que serão utilizados na coluna de ações
     *
     *  @param  {Mixed} id Id da Coleção
     *  @return {Array} Conteudo dos inputs
     */
    "_getControlButtons" : function(id) {

      var aRetorno = [];

      for(var action of this.actions.get()) {

        var button = document.createElement("input");

        button.setAttribute("id",            action.uid + "_" + id);
        button.setAttribute("value",         action.label);
        button.setAttribute("collection_id", id);
        button.setAttribute("action_id",     action.uid);
        button.setAttribute("action",        action.label);

        button.className          = action.uid + "_" + this.gridID + " collection_button ";
        button.readOnly           = true;
        button.type               = "button";
        button.style.paddingLeft  = "3px";
        button.style.paddingRight = "3px";
        button.title              = action.title;

        this._events.onaftercreatebutton(button);

        aRetorno.push(button.outerHTML); //IF dos coiso
      }

      return aRetorno;
    },

    /**
     *  Define o evento utilizado no comportamento da view
     *  A string pode ser me maiusculo ou minusculo, camel case, com ou sem o "on" antes da Descrição do evento
     *  Mas os eventos permitidos são "ClickUpdate", "ClickDelete", "BeforeRenderRows", "AfterRenderRows"
     *
     *  @example
     *    gridcollection.setEvent("onclickdelete", function(event, element, collection) {
     *       console.log(event, element, collection);
     *    });
     *  @example
     *    gridcollection.setEvent("clickUpdate", function(event, element, collection) {
     *       console.log(event, element, collection);
     *    });
     *
     *  @param  {String}   sEventName [description]
     *  @param  {Function} callback   [description]
     *  @return
     */
    "setEvent" : function(sEventName, callback) {

      sEventName = sEventName
        .toLowerCase()           //Deixa em minusculo
        .replace(/^on/, "")      //Remove o on do inicio da string
        ;
      sEventName = "on" + sEventName;                   //concatena on no inicio      

      if (!this._events[sEventName]) {
        throw "O evento '"+sEventName+"' não existe.";
      }

      this._events[sEventName] = callback;
      
      switch (sEventName) {
      
        case 'onclickrow':
          
         this.getGrid().clickRow = function (id) {
            var item = this.collection.get(id);
            return this._events.onclickrow(item);            
          }.bind(this);
          break;
      }
            
    },

    /**
     *  Ajusta as configurações da Visualização da Coleção
     *  @example
     *
     *    gridcollection
     *      .configure("height", "300")
     *      .configure({"delete":true})
     *      .configure("update", false);
     *
     *  @param  {Object} oOptions [description]
     *  @return {[type]}          [description]
     */
    "configure" : function(options) {

      if ( arguments[0] instanceof Object) {
        mergeObject(this.options, options);
        return this;
      }
      if (typeof(arguments[0]) === "string") {

        var object = {};
        object[arguments[0]] = arguments[1];
        return this.configure(object);
      }
    },

    /**
     *  Adiciona uma coluna na visualização dos dados da coleção
     *
     *  @example
     *   var collumn = gridcollection.addColumn("db149_descricao")
     *   collumn.options({"width": "200px"});
     *   collumn.configure("alingn","right");
     *   collumn.setOptions("label","Descrição");
     *
     *  @example
     *    gridcollection.addColumn("db150_sequencial", {"width": "130px"})
     *      .setOption("align","center");
     *
     *  @param  {Mixed} id           ID da Coluna, será o mesmo ID da Coleção
     *  @param  {Object} userOptions Opções do usuario - Opcional
     *
     *  @return {Object}             Coluna Recem adicionada
     */
    "addColumn" : function(id, userOptions) {

      if (!!this.columns[id]) {
        delete(this.columns[id]);
      }

      userOptions      = userOptions || {};
      var oOptions     = mergeObject({}, DatagridCollection.COLUMN_OPTIONS);

      this.columns[id] = mergeObject(oOptions, userOptions);
      this.columns[id].label             = this.columns[id].label || id;
      this.columns[id].gridCollection    = this;
      this.columns[id].id                = id;
      /**
       * Configurta opções
       */
      this.columns[id].setOptions        = DatagridCollection.configureColumn.bind(this.columns[id]);
      this.columns[id].setOption         = DatagridCollection.configureColumn.bind(this.columns[id]);
      this.columns[id].options           = DatagridCollection.configureColumn.bind(this.columns[id]);
      this.columns[id].option            = DatagridCollection.configureColumn.bind(this.columns[id]);
      this.columns[id].configure         = DatagridCollection.configureColumn.bind(this.columns[id]);

      /**
       * Define o transform
       */
      this.columns[id].transformer       = DatagridCollection.transformColumn.bind(this.columns[id]);
      this.columns[id].transform         = DatagridCollection.transformColumn.bind(this.columns[id]);
      this.columns[id].transformCallback = null;

      /**
       * Define o algoritimo de ordenação
       */
      this.columns[id].sorter            = DatagridCollection.sortColumn.bind(this.columns[id]);
      this.columns[id].sort              = DatagridCollection.sortColumn.bind(this.columns[id]);
      this.columns[id].sortCallback      = null;

      return this.columns[id];
    },

    /**
     *  Retonrna a Coleção utilizada
     *
     *  @return {Collection}
     */
    "getCollection" : function() {
      return this.collection;
    },

    /**
     *  Retorna a Grid
     *
     *  @return {DBGrid}
     */
    "getGrid" : function() {
      return this.grid;
    },

    /**
     *  Popula os Dados da Coleção na grid
     *
     *  @return
     */
    "populate" : function() {

      this.grid.clearAll(true);

      var iRegistro = 0;

      for(var oRegistro of this.collection.get()) {

        var lChecked       = false;
        var iRegistroAtual = iRegistro++;//Pega valor depois incrementa =)
        var aValores       = [];

        for (var sColuna in this.columns) {

          var coluna = this.columns[sColuna];
          var sValor = oRegistro[sColuna] === undefined ? "" : oRegistro[sColuna] + '';//Forçando string
          if (coluna.transformCallback) {
            sValor = coluna.transformCallback(sValor, oRegistro);
          }
          aValores.push(sValor);
        }

        if(this.getSelectedItens().length > 0 && this.getSelectedItens().indexOf(oRegistro.ID) > -1) {
          lChecked = true;
        }

        /**
         *  Adiciona a coluna de botoes
         */
        if (this.actions.get().length > 0) {
          aValores.push(this._getControlButtons(oRegistro.ID).join(" "));
        }
        this.grid.addRow(aValores, false, false, lChecked);

        // identifica na collection o item da grid a qual ele referência
        oRegistro.datagridRow = this.grid.aRows[iRegistroAtual];
        // identifica na grid qual o item da coleção que ele referência
        this.grid.aRows[iRegistroAtual].itemCollection = oRegistro;
        var eventClick='';
        if (this._events.onclickrow != '' && this.sEventSelectRow  == null) {
          
          eventClick = " onclick='"+this.getGrid().nameInstance+".clickRow(\""+oRegistro.ID+"\");'";
          this.grid.aRows[iRegistroAtual].sEvents += eventClick;
        }
        if (this.sEventSelectRow != null ) {
          this.grid.aRows[iRegistroAtual].sEvents += this.sEventSelectRow+";";
        }
      }

      this.grid.renderRows();
    },

    /**
     *  Renderiza a Coleção no alvo especificado
     *  @param  {HTMLElement} target
     *  @return
     */
    "show" : function(target)  {

      this.target = !!target ? target : (this.target || target);

      this.grid.allowSelectColumns(false);
      this._configureColumns();
      this.grid.setHeight(this.options.height);

      for(iColuna of this.aColumnsDisplayed) {
        this.grid.aHeaders[iColuna].lDisplayed = false;
      }

      this.grid.show(this.target);
      this.makeOrder();
      this.reload();

    },

    /**
     * Recarrega a grid e recria os eventos
     */
    "reload" : function() {

      try {
        this._events.onbeforerenderrows(this.collection);
        this.populate();
      } catch (e) {
        console.error(e);
      } finally {
        this._events.onafterrenderrows(this.collection);
      }
      this._makeEvents();
    },

    /**
     * Retorna o Item selecionado
     */
    "getSelectedItem": function() {
      return this.selectedItem;
    },

    /**
     * Define o item selecionado
     */
    "setSelectedItem": function(item) {

      this.selectedItem = item;
      return this;
    },

    /**
     * Define os itens selecionados da grid
     */
    "setSelectedItens": function (itens) {

      if(Array.isArray(itens)) {
        this.selectedItens = itens;
      }
      return this;
    },

    /**
     * Adiciona os itens selecionados
     */
    "addSelectedItens": function (item) {

      this.selectedItens.push(item);
      return this;
    },

    /**
     * Retorna os itens selecionados da grid
     */
    "getSelectedItens": function () {
      return this.selectedItens;
    },

    /**
     * Cria a ordenação das colunas
     */
    "makeOrder" : function() {

      if (!this.options.order) {
        return false;
      }

      var iColuna = 1;

      for (var sColuna in this.columns) {

        var coluna  = this.columns[sColuna];
        var id      = '#grid'+this.gridID + ' #col'+iColuna;
        var header  = document.querySelector(id);
        header.style.cursor = 'pointer' ;
        header.title        = 'Ordernar Registros A-Z';
        header.setAttribute("data-target", sColuna);
        header.setAttribute("data-type", "asc");
        header.removeClassName("sort-asc");
        header.removeClassName("sort-desc");
        header.addClassName("sort");


        header.onclick = function(event){

          var header        = event.target;
          var tipoOrdenacao = header.getAttribute("data-type");
          var campo         = header.getAttribute("data-target");
          this.collection.sort(tipoOrdenacao, [campo], this.columns[campo].sortCallback || null);

          /**
           * Re-renderiza os titulos para ordenarem de forma crescente
           * quando trocar de coluna
           */
          this.makeOrder();

          if (tipoOrdenacao !== "asc") {

            header.style.cursor = 'pointer' ;
            header.setAttribute("data-target", campo);
            header.setAttribute("data-type", "asc");
            header.removeClassName("sort-asc");
            header.removeClassName("sort-desc");
            header.addClassName("sort");
            header.addClassName("sort-asc");

          } else {
            header.style.cursor = 'pointer' ;
            header.setAttribute("data-target", campo);
            header.setAttribute("data-type", "desc");
            header.removeClassName("sort-asc");
            header.removeClassName("sort-desc");
            header.addClassName("sort");
            header.addClassName("sort-desc");
          }

          this.reload();
        }.bind(this);
        iColuna++;
      }
    },

    /**
     * Limpa os dados da grid
     */
    "clear": function() {

      this.grid.clearAll(true);
      this.collection.clear();
      this.selectedItens = [];
    },

    /**
     * Limpa o array dos dados selecionados
     */
    "clearSelectedItens": function() {

      this.grid.clearAll(true);
      this.selectedItens = [];
    },

    "getCollectionByRowClass": function(sClassName) {
      var aRow = document.getElementsByClassName(sClassName);

      if (aRow.length == 0) {
        return false;
      }

      var oRow         = aRow[0];
      var aCollections = [];

      for( var oRegistro of this.collection.get() ) {

        if ( oRegistro.datagridRow.sId == oRow.id ) {
          aCollections.push(oRegistro);
        }
      }

      return aCollections;
    },

    "hideColumns": function(aColunas) {

      if(Array.isArray(aColunas)) {
        this.aColumnsDisplayed = aColunas;
      }
    }
  };

  DatagridCollection.prototype._events = {
    "onclickupdate"       : function(event, itemCollection) {},
    "onclickdelete"       : function(event, itemCollection) {},
    "onclickrow"          : function(){},
    "onbeforerenderrows"  : function(itemCollection) {},
    "onafterrenderrows"   : function(itemCollection) {},
    "onaftercreatebutton" : function(button) {}
  };

  /**
   * Constantes
   */
  Object.freeze(DatagridCollection.GRID_OPTIONS = {
    "height"  : "300",
    "delete"  : false,
    "update"  : false,
    "order"   : true,
    "action"  : {"width" : "",
                 "alingn": "center",
                 "label" : "Ações"
                }
  });

  Object.freeze(DatagridCollection.COLUMN_OPTIONS = {
    "width" : "50px",
    "alingn": "left",
    "label" : null

  });

  /**
   *  Configura a coluna adicionada
   *
   *  @param  {Object|String} options Objeto com a configuração ou chave, valor
   *  @return {Object} Própria coluna
   */
  Object.freeze(DatagridCollection.configureColumn = function(options) {

    if ( arguments[0] instanceof Object) {
      return mergeObject(this, options);
    }

    if (typeof(arguments[0]) === "string") {

      var object = {};
      object[arguments[0]] = arguments[1];
      return this.options(object);
    }

  });

  /**
   *  Ordena a coluna adicionada
   */
  Object.freeze(DatagridCollection.sortColumn = function(callback) {
    this.sortCallback = callback;
    return this;
  });

  /**
   *  Transforma a saida de dados para a coluna adicionada
   */
  Object.freeze(DatagridCollection.transformColumn = function(callback) {

    if ( (typeof callback === 'string') && DatagridCollection.defaultTransformers[callback]) {
      callback = DatagridCollection.defaultTransformers[callback];
    }

    if(!callback) {
      callback = DatagridCollection.defaultTransformer.default;
    }

    this.transformCallback = callback;

    return this;
  });

  Object.freeze(DatagridCollection.defaultTransformers = {
    "string" : function(data) {
      return data.toString();
    },

    "date" : function(data) {
      if ( data instanceof Date) {
        return data.getDateBR();
      }
      return data.toString().getDate().getDateBR();
    },

    "integer" : function(data) {
      return parseInt(data);
    },

    "number" : function(data) {
      return js_formatar(data.getNumber(), "f");
    },

    "array" : function(data) {
      return data.join(", ");
    },

    "dinheiro" : function(value) {

        var numObj = parseFloat(value);

        return numObj.toLocaleString('pt-BR', { 
            style: 'currency', 
            currency: 'BRL' 
        });
    },

    "list" : function(data) {

      var retorno = "<ul>\n";

      for(var valor of data) {
        retorno += "<li>" + valor + "</li>\n";
      }
      retorno += "</ul>\n";
      return retorno;
    },
    
    "decode" : function(data) {
      return data.urlDecode();
    },
    
    clickRow : function(id) {
      console.log(id)
    }
    
  });
  
  /**
   * Cria o objeto(construtor estático)
   * @returns {DatagridCollection}
   */
  Object.freeze(DatagridCollection.create = function(oCollection) {
    return new DatagridCollection(oCollection);
  });

  /**
   * Injeta um "seletor" de linha da grid
   */
  Object.freeze(window.tableRow.prototype.selectLine = function() {

    for( var oElemento of $$("tr.collection_line")) {
      oElemento.style.display = '';
    }

    $(this.getId()).style.display = 'none';
    this.addClassName('collection_line');
  });

  exports.DatagridCollection = DatagridCollection;
  return DatagridCollection;

})(this, window.Collection, window.DBGrid); // Dependencias, Collection e DBGrid

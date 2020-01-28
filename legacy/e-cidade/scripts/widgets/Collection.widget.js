(function(exports) {

  require_once("scripts/strings.js");
  require_once("scripts/object.js");

/**
 * Representa uma colecao do sistema
 *
 * @author Rafael Nery <rafael.nery@dbseller.com.br>
 * @version  $Revision: 1.14 $
 *
 * @constructor
 * @module Collection
 *
 * @example
 *
 * var collection = new Collection();
 * collection.setId("cpf");
 * collection.add({"cpf": "12345678909", "nome": "Chaves",    "telefone" : "+55 51 9876-5432", "jargao": "Foi sem querer... querendo"});
 * collection.add({"cpf": "98765432102", "nome": "Girafales", "telefone" : "+55 51 9865-5558", "jargao": "Tah... Tah... Tah... Tah..."});
 * collection.add({"cpf": "74185296378", "nome": "Quico",     "telefone" : "+55 51 8274-9632", "jargao": "Gentalha. Gentalha. Gentalha!"});
 *
 * console.table(collection.get());
 *
 */
  var Collection = function() {

    this.itens     = [];
    this.sColunaId = null;
    this.chaves    = {};
    this.options   = mergeObject({}, Collection.OPTIONS);

    this.events= {
      "onBeforeCreate" : function(item) { return true; }.bind(this),
      "onAfterCreate"  : function(item) { return true; }.bind(this),
      "onBeforeUpdate" : function(item) { return true; }.bind(this),
      "onAfterUpdate"  : function(item) { return true; }.bind(this),
      "onBeforeDelete" : function(item) { return true; }.bind(this),
      "onAfterDelete"  : function(item) { return true; }.bind(this),
      "onOrder"        : function()     { return true; }.bind(this),
    };

  };

  Collection.prototype = {

    "clear" : function() {

      this.itens = [];
      return this;
    },
    
    "count": function() {
      return this.itens.length;
    },

    "setEvent" : function(sEvento, callback) {

      if (this.events[sEvento]) {

        this.events[sEvento] = callback;
        return true;
      }
      return false;
    },
    /**
     *  Define o identificador da lista
     *
     *  @function setId
     *  @param  {String} sIdentificador identificador do registro
     *  @returns {void}
     */
    "setId" : function(sIdentificador) {

      this.sColunaId = sIdentificador;
      return this;
    },

    /**
     *  Adiciona um objeto a colecao, caso exista o anterior sera removido.
     *
     *  @function add
     *  @param  {Object|Array} itemCollection Objeto que será adicionado na coleção
     *  @returns {Object|Array} Item(ns) adicionado(s)
     */
    "add" : function(itemCollection, force) {

      var indice, chave, item, id, acao;

      force = (force === undefined) ? false : !!force;

      if (itemCollection instanceof Array) {
        for( var subItem of itemCollection) {
          this.add(subItem);
        }
        return this.get();
      }

      if (!this.sColunaId) {
        throw TypeError("Coluna identificadora é necessária. Utilize o método 'Collection.setId' ");
      }

      if (itemCollection[this.sColunaId] === undefined) {
        throw TypeError("Coluna " + this.sColunaId + " não existe no objeto adicionado.");
      }

      if (itemCollection.ID !== undefined) {
        throw TypeError("Coluna id não pode ser definida.");
      }

      if (!this.__assertId(itemCollection[this.sColunaId])) {
        acao = "incluir";
        this.events.onBeforeCreate(itemCollection);
      } else {
        acao = "alterar";
        this.events.onBeforeUpdate(itemCollection);
      }

      indice = this.__getId(itemCollection[this.sColunaId]);
      chave  = this.sColunaId;

      this.remove(itemCollection[this.sColunaId], false); //@TODO Usar force -- Remove o item existente

      itemCollection.ID = itemCollection[chave];

      itemCollection.toJSON     = Collection.toJSON.bind(itemCollection);
      itemCollection.toJson     = Collection.toJSON.bind(itemCollection);
      itemCollection.build      = Collection.build.bind(itemCollection);
      this.itens.push(itemCollection);

      if(acao === "incluir") {

        this.events.onAfterCreate(itemCollection);
        return itemCollection;
      }

      this.events.onAfterUpdate(itemCollection);
      return itemCollection;
    },

    /**
     *  Retorna a posição do item na coleção
     *
     *  @function getIndex
     *  @param  {Mixed} id Identificador do Registro
     *  @return {Integer}    posicção do objeto na coleção
     */
    "__getIndex" : function(id) {

      for(var indice = 0; indice < this.itens.length; indice++) {

        var oRegistro = this.itens[indice];

        if (oRegistro[this.sColunaId] === id) {
          return indice;
        }
      }
      throw "Item da coleção com a chave "+this.sColunaId+" = " + id + " não existe.";

    },

    /**
     *  Remove um Registro da coleção
     *
     *  @param  {String} sIdentificador do registro
     *  @return {void}
     */
    "remove" : function(id, callEvent) {

      callEvent = callEvent === undefined ? true : callEvent;
      try {
        id = this.get(id)[this.sColunaId];
        indice = this.__getIndex(id);
        item   = this.get(id);
      } catch(e) {
        return false;
      }

      if (callEvent) {
        this.events.onBeforeDelete(item);
      }
      this.itens.splice(indice, 1);

      if (callEvent) {
        this.events.onAfterDelete(item);
      }
      return true;
    },

    /**
     *  Retorna um ou todos os registros da coleção
     *
     *  @param  {String|null} sIdentificador do registro
     *  @return {Object|Array}
     */
    "get" : function(id) {

      id = this.__getId(id);

      if (!!id && arguments.length === 0) {
        return this.itens;
      }

      for(var oRegistro of this.itens) {

        if (oRegistro[this.sColunaId] == id) {
          return oRegistro;
        }
      }

      throw "Item da coleção com a chave "+this.sColunaId+" = " + id + " não existe.";
    },

    /**
     *  Verifica a existencia do id na coleção
     *
     *  @param  {String} id [description]
     *  @return {Boolean}
     */
    "__assertId" : function(id) {

      for(var oRegistro of this.itens) {

        if (oRegistro[this.sColunaId] === id) {
          return true;
        }
      }
      return false;
    },

    /**
     *  Retorna o ID Formatado
     *
     *  @param  {String} id
     *  @return {String}
     */
    "__getId" : function(id) {
      return id + '';
    },

    "build" : function() {

      return this.get().map(function(itemCollection){
        return itemCollection.build();
      });
    },

    "sort": function(order, byFields, sorter) {


      if ( !sorter) {

        sorter = function (item1, item2, campo) {

          if (item1[campo] > item2[campo]) {
            return 1;
          }

          if (item1[campo] < item2[campo]) {
            return -1;
          }

          return 0;
        };

      }

      this.itens = this.itens.sort(function(a, b) {

        var valorOrdenado = 0;
        var sortCallback = sorter;

        /**
         * Percorre a lista de parametros para que a lista possa ser ordenada
         * na ordem.
         *
         * Utiliza o -1 no caso de ordenação reversa
         */
        for( var campoOrdenacao of byFields) {
          valorOrdenado = sortCallback(a, b, campoOrdenacao);

          if (valorOrdenado === 0) {
            continue;
          }

          if (order === "desc") {
            valorOrdenado = valorOrdenado * -1;
          }

          return valorOrdenado;
        }

        return valorOrdenado;
      });

      this.events.onOrder();
    }
  };

  Collection.toJSON = function() {
    return JSON.stringify(this.buildItem());
  };
  /**
   * Limpa os dados desnecessários para o envio
   */
  Collection.build = function() {

    var chave,
        retorno = {},
        teste,
        blackList = ['id', 'tojson', 'build', 'rollback', 'anterior',"datagridrow"]
        ;

    for( chave in this) {

      teste = chave.toLowerCase();

      if (js_search_in_array(blackList, teste)) {
        continue;
      }

      retorno[chave] = this[chave];
    }
    return retorno;
  };

  /**
   *  Construtor estatico que não pode ser reescrito/sobrecarregado
   *  @return Collection;
   */
  Object.freeze(Collection.create = function() {
    return new Collection();
  });

  exports.Collection = Collection;
  return Collection;
})(this);

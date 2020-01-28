(function(exports, DatagridCollection) {

  /**
   * Cria uma DatagridCollection orden�vel.
   * @param {DatagridCollection} oDatagridCollection Inst�ncia da grid.
   */
  var DatagridCollectionOrderer = function(oDatagridCollection) {

    if (!(oDatagridCollection instanceof DatagridCollection)) {
      throw "Deve ser passada uma inst�ncia de DatagridCollection.";
    }

    var oDatagridCollection = oDatagridCollection;
    var oCollection         = oDatagridCollection.getCollection();
    var iOrdem              = 0;
    var sSortOrder          = 'asc';
    var sOrderAtribute      = 'ordem';

    /**
     * Configura as int�ncias para a ordena��o
     */
    function initialize() {

      var integerSorter = function (item1, item2, campo) {

        if (parseInt(item1[campo]) > parseInt(item2[campo])) {
          return 1;
        }

        if (parseInt(item1[campo]) < parseInt(item2[campo])) {
          return -1;
        }

        return 0;
      };

      /**
       * Caso seja aplicada ordena��o por fora, redefine a ordena��o interna
       */
      oCollection.setEvent('onOrder', function() {

        var aItems = oCollection.get();
        var iOrdem = 0;
        for (var oItem of aItems) {
          oItem[sOrderAtribute] = ++iOrdem;
        }
      });

      /**
       * Todo item adicionado a lista deve ter uma ordena��o sequencial
       */
      oCollection.setEvent('onBeforeCreate', function(oItem) {
        oItem[sOrderAtribute] = ++iOrdem;
      });

      /**
       * Garante que exista uma ordena��o inicial
       */
      oCollection.sort(sSortOrder, [sOrderAtribute], integerSorter);

      oDatagridCollection.addAction("Subir", null, function(oEvento, oActionItem) {

        var aItems        = oCollection.get();
        var oPreviousItem = null;

        /**
         * Move o item
         */
        for (var oCollectionItem of aItems) {

          if (oCollectionItem.ID == oActionItem.ID && oPreviousItem !== null) {

            var iOriginalOrder            = oPreviousItem[sOrderAtribute];
            oPreviousItem[sOrderAtribute] = oActionItem[sOrderAtribute];
            oActionItem[sOrderAtribute]   = iOriginalOrder;
            break;
          }

          oPreviousItem = oCollectionItem;
        }

        /**
         * Re-ordena a cole��o e atualiza a grid
         */
        oCollection.sort(sSortOrder, [sOrderAtribute], integerSorter);
        oDatagridCollection.reload();
      });

      oDatagridCollection.addAction("Descer", null, function(oEvento, oActionItem) {

        var aItems       = oCollection.get();
        var lGetNextItem = false;

        /**
         * Move o item
         */
        for (var oCollectionItem of aItems) {

          if (lGetNextItem) {

            var iOriginalOrder              = oCollectionItem[sOrderAtribute];
            oCollectionItem[sOrderAtribute] = oActionItem[sOrderAtribute];
            oActionItem[sOrderAtribute]     = iOriginalOrder;
            break;
          }

          if (oCollectionItem.ID == oActionItem.ID) {
            lGetNextItem = true;
          }
        }

        /**
         * Re-ordena a cole��o e atualiza a grid
         */
        oCollection.sort(sSortOrder, [sOrderAtribute], integerSorter);
        oDatagridCollection.reload();
      });

      oDatagridCollection.setEvent('onAfterCreateButton', function(oButton) {

        var sActionID = oButton.getAttribute('action_id');
        if (sActionID == 'action_subir' || sActionID == 'action_descer') {

          oButton.style.width              = '30px';
          oButton.style.margin             = '0px';
          oButton.style.padding            = '0px';
          oButton.style.border             = '1px solid #888';
          oButton.style.textIndent         = '-1000em';
          oButton.style.cursor             = 'pointer';
          oButton.style.backgroundPosition = '50% 50%';
          oButton.style.backgroundRepeat   = 'no-repeat';
        }
        if (sActionID == 'action_subir') {
          oButton.style.backgroundImage = 'url("imagens/setacima.gif")';
        }
        if (sActionID == 'action_descer') {
          oButton.style.backgroundImage = 'url("imagens/setabaixo.gif")';
        }
      });

    }

    /**
     * Retorna a Collection
     */
    this.getCollection = function() {
      return oCollection;
    };

    /**
     * Troca a inst�ncia do DatagridCollection
     */
    this.getGrid = function() {
      return oDatagridCollection;
    };

    /**
     * Define o nome do atributo usado para a ordena��o.
     * O valor padr�o � 'ordem'.
     */
    this.setOrderAtribute = function(sOrderAtributeValue) {
      sOrderAtribute = sOrderAtributeValue;
    };

    /**
     * Define a dire��o da ordena��o
     */
    this.setSortOrder = function(sSortOrderValue) {
      sSortOrder = sSortOrderValue;
    };

    /**
     * Mostra o DatagridCollection
     */
    this.show = function(target) {

      initialize();
      oDatagridCollection.show(target);
    };

  };

  exports.DatagridCollectionOrderer = DatagridCollectionOrderer;
  return DatagridCollectionOrderer;
})(this, window.DatagridCollection); // Depend�ncias

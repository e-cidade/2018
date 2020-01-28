(function(exports) {

  var FormCollection = function(datagridCollection, oForm) {

    this.datagridCollection = datagridCollection
                              .configure({update:true, delete:true});
    this.collection         = datagridCollection.collection;
    this.form               = oForm;

    this.saveButton         = null;
    this.deleteButton       = null;
    this.cancelButton       = null;

    this.selectedItem       = null;


    this.callbacksSave           = [];
    this.callbackBeforeSelectRow = function(acao, itemCollection) {return true};
    this.callbackAfterSelectRow  = function(acao, itemCollection) {return true};
    this.callbacksDelete        = [];

    this.transformUpdateFields = Collection.create().setId('updateField');
    this.transformDeleteFields = Collection.create().setId('deleteField');

    this.__init();
  };

  FormCollection.create = function(datagridCollection, oForm) {
    return new FormCollection(datagridCollection, oForm);
  };

  exports.FormCollection = FormCollection;
  exports.HTMLFormElement.prototype.manageCollection = function(datagridCollection) {
    return new FormCollection(datagridCollection, this);
  };


  FormCollection.prototype = {

    "__init" : function() {

      this.datagridCollection.setEvent("onClickUpdate", this.__select.bind(this));
      this.datagridCollection.setEvent("onClickDelete", this.__select.bind(this));

      var elementos = Array.from(this.form.elements);

      for(var elemento of elementos) {

        if (!!elemento.getAttribute("data-form")) {
          this.makeBehavior(elemento, elemento.getAttribute("data-form"));
        }
      }
    },

    "makeBehavior": function(elemento, dataForm, fnCallback) {
      switch (dataForm.toLowerCase()) {
        case "save":
          this.saveButton = elemento;
          if(fnCallback) this.callbacksSave.push(fnCallback);
          elemento.observe("click", this.events.onClickSave.bind(this) );
        break;
        case "delete":
          this.deleteButton = elemento;
          if(fnCallback) this.callbacksDelete.push(fnCallback);
          elemento.observe("click", this.events.onClickDelete.bind(this) );
        break;
        case "cancel":
          this.cancelButton = elemento;
          elemento.observe("click", this.events.onClickCancel.bind(this));
        break;
        default:

      }
      return;
    },

    "setCallback" : function(ev, fnCallback) {
      switch(ev) {
        case "save":
          this.callbacksSave.push(fnCallback);
          break;
        case "delete":
          this.callbacksDelete.push(fnCallback);
          break;
      }
    },

    "selectItem" : function(sAcao, itemCollection) {

      itemCollection.datagridRow.selectLine();
      this.callbackBeforeSelectRow(sAcao, itemCollection);
      this.selectedItem = itemCollection;

      this.mapCollection(itemCollection, function(campo, valor){
        campo.setValue(valor);
        campo.removeClassName("readOnly");
      }, sAcao);

      this.form.enable();
      if(this.deleteButton) {
        this.deleteButton.disable();
      }


      if (sAcao === "E") {

        this.form.disable();
        if(this.deleteButton) {
          this.deleteButton.enable();
        }

        this.mapCollection(itemCollection, function(campo){
          campo.addClassName("readOnly");
        }, sAcao)
        ;
      } else {

        campo = this.form[this.collection.sColunaId];

        if (campo) {
          campo.readOnly = true;
          campo.addClassName("readOnly");
        }
      }
      this.callbackAfterSelectRow(sAcao, itemCollection);
      if(this.cancelButton) {
        this.cancelButton.enable();
      }
      return;
    },

    /**
     *  Seleciona um item da grid e coloca as informcoes no formulario
     *  @param  {[type]} event          [description]
     *  @param  {[type]} itemCollection [description]
     *  @return {[type]}                [description]
     */
    "__select" : function(event, itemCollection) {

      var campo, chave;
      var sAcao = event.target.value;
      return this.selectItem(sAcao, itemCollection);
    },


    /**
     *  Limpa o formulario e renderiza todos os elementos da grid
     *
     *  @return {void}
     */
    "clearForm" : function() {

      this.form.enable();
      this.datagridCollection.reload();
      this.form.reset();

      this.mapCollection(this.selectedItem, function(campo){
        campo.removeClassName("readOnly");
      });

      var campo = this.form[this.collection.sColunaId];

      if (campo) {
        campo.readOnly = false;
      }

      this.selectedItem = null;
      this.deleteButton.disabled = true;
    },

    /**
     *  Percorre a Collection executando o callback
     *
     *  @param  {ItemCollection}
     *  @param  {Function} callback       [description]
     *  @return {void}
     */
    "mapCollection" : function(itemCollection, callback, sAcao) {

      for(var chave in itemCollection) {

        var transformField, campo = this.form[chave];

        try {
          transformField = null;
          if(sAcao == 'A') {
            transformField = this.transformUpdateFields.get(chave);
          } else if(sAcao == 'E') {
            transformField = this.transformDeleteFields.get(chave);
          }
        } catch(e) {
          transformField = null;
        }

        if (campo) {
          if(sAcao == 'A' && transformField && transformField.beforeUpdate) {
            callback(campo, transformField.beforeUpdate(itemCollection[chave], campo, itemCollection));
          } else if(sAcao == 'E' && transformField && transformField.beforeDelete) {
            callback(campo, transformField.beforeDelete(itemCollection[chave], campo, itemCollection));
          } else {
            callback(campo, itemCollection[chave]);
          }
        }
      }
    },

    "transformFieldOnUpdate" : function(field, callbackBeforeUpdate, callbackAfterUpdate) {
      this.__transformField('update', field, callbackBeforeUpdate, callbackAfterUpdate);
    },

    "transformFieldUpdate" : function(field, callbackBeforeUpdate, callbackAfterUpdate) {
      this.__transformField('update', field, callbackBeforeUpdate, callbackAfterUpdate);
    },

    "transformFieldOnDelete" : function(field, callbackBeforeDelete, callbackAfterDelete) {
      this.__transformField('delete', field, callbackBeforeDelete, callbackAfterDelete);
    },

    "transformFieldDelete" : function(field, callbackBeforeDelete, callbackAfterDelete) {
      this.__transformField('delete', field, callbackBeforeDelete, callbackAfterDelete);
    },

    "transformField" : function(action, field, callbackBefore, callbackAfter) {
      this.__transformField(action, field, callbackBefore, callbackAfter);
    },

    "__transformField" : function(action, field, callbackBefore, callbackAfter) {

      switch(action.toLowerCase()) {

        case "update":
          this.transformUpdateFields.add({
            updateField  : field,
            beforeUpdate : callbackBefore,
            afterUpdate  : callbackAfter,
          });
          break;

        case "delete":
          this.transformDeleteFields.add({
            deleteField  : field,
            beforeDelete : callbackBefore,
            afterDelete  : callbackAfter,
          });
          break;
      }

      return this;
    },
    "onAfterSelectRow" :function(fFunction) {
      this.callbackAfterSelectRow = fFunction;
    },
    "onBeforeSelectRow" : function(fFunction) {
      this.callbackBeforeSelectRow = fFunction;
    },
  };

  FormCollection.prototype.events = {

    "onClickSave" : function() {

      for(var callbackSave of this.callbacksSave) {
        if(callbackSave(this.form.serialize(true)) === false) {
          return;
        }
      }

      var item = this.selectedItem = this.collection.add(this.form.serialize(true));
      this.clearForm();
      this.selectedItem = item;

      return this.selectedItem;
    },

    "onClickDelete" : function() {

      for(var callbackDelete of this.callbacksDelete) {
        if(callbackDelete(this.selectedItem.build()) === false) {
          return;
        }
      }

      this.mapCollection(this.selectedItem, function(campo, valor){
        campo.removeClassName("readOnly");
      });

      if (!!this.selectedItem) {
        this.collection.remove(this.selectedItem.ID);
        this.clearForm();
      }

    },

    "onClickCancel" :function() {
      return this.clearForm();
    }
   };

})(this);

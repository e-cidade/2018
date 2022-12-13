/* jshint esversion: 6 */
/* jshint -W100 */

/**
 * Classe que representa um tutorial do sistema.
 * Responsável por iniciar o tutorial integrado com o IntroJS
 * 
 * @author Vitor Rocha <vitor@dbseller.com.br>
 * 
 */
;(function(exports) {

  'use strict';

  var TutorialEtapa = function() {

    this.id = null;
    this.descricao = null;

    this.menu = null;
    this.modulo = null;
    this.ordem = null;

    this.permissao = false;

    this.passoAtual = null;
    this.passos = Collection.create().setId('id');
  };

  TutorialEtapa.makeFromObject = function(objTutorialEtapa) {

    var tutorialEtapa = new TutorialEtapa() , passo;
    tutorialEtapa.setId(objTutorialEtapa.id);
    tutorialEtapa.setDescricao(objTutorialEtapa.descricao);
    tutorialEtapa.setMenu(objTutorialEtapa.menu);
    tutorialEtapa.setModulo(objTutorialEtapa.modulo);
    tutorialEtapa.setOrdem(Number(objTutorialEtapa.ordem));
    tutorialEtapa.setPermissao(objTutorialEtapa.permissao);

    for (passo of objTutorialEtapa.passos) {
      passo.ordem = Number(passo.ordem);
      tutorialEtapa.getPassos().add(passo);

      if ( objTutorialEtapa.passoAtual.id === passo.id ) {
        tutorialEtapa.setPassoAtual(passo);
      }
    }

    return tutorialEtapa;
  };

  TutorialEtapa.prototype = {

    "setId": function(id) {
      this.id = id;
      return this;
    },

    "getId": function() {
      return this.id;
    },

    "setDescricao": function(descricao) {
      this.descricao = descricao;
      return this;
    },

    "getDescricao": function() {
      return this.descricao;
    },

    "getPassos": function() {
      return this.passos;
    },

    "setMenu": function(menu) {
      this.menu = menu;
      return this;
    },

    "getMenu": function() {
      return this.menu;
    },

    "setModulo": function(modulo) {
      this.modulo = modulo;
      return this;
    },

    "getModulo": function() {
      return this.modulo;
    },

    "setOrdem": function(ordem) {
      this.ordem = ordem;
      return this;
    },

    "getOrdem": function() {
      return this.ordem;
    },

    "setPassoAtual": function(passo, persist) {
      this.passoAtual = passo;

      return new Promise(function(resolve, reject) {

        if (!persist) return resolve();

        TutorialRepository.__request({
          exec: 'setPassoAtual',
          passoAtual: passo.id
        }, resolve);

      });

      return this;
    },

    "getPassoAtual": function() {
      return this.passoAtual;
    },

    "setPermissao": function(permissao) {
      this.permissao = permissao;
      return this;
    },

    "getPermissao": function() {
      return this.permissao;
    }

  };

  exports.TutorialEtapa = TutorialEtapa;

})(this);
/* jshint esversion: 6 */
/* jshint -W100 */

/**
 * Classe responsável pelo gerenciamento de tutoriais do e-cidade.
 * 
 * @author Vitor Rocha <vitor@dbseller.com.br>
 * 
 */
(function(exports) {

  'use strict';

  var TutorialRepository = function() {

    TutorialRepository.dependencies();

    this.mask = null;
    this.container = document.createElement('div');

  };

  TutorialRepository.URL_RPC = 'con4_dbtutorial.RPC.php';

  TutorialRepository.prototype = {

    "loadTutoriais": function() {

        TutorialRepository.__request(
          {exec: "getTutoriaisDisponiveis"},
          function(response) {
            
            var result = response.responseJSON, element;

            if (result.erro) {
              return console.error(result.message.urlDecode());
            }

            this.__renderListaTutoriais(result.tutoriais);

          }.bind(this)
        );

    },

    "show": function() {
      this.__renderContainer();
      this.loadTutoriais();
    },

    "showTutorial": function(tutorialId) {

      var _this = this;

      this.getTutorial(tutorialId, function(result) {
        _this.__renderTutorial(result.tutorial);
      });

    },

    "startTutorial": function(tutorialObj, idEtapa) {

      var tutorial = Tutorial.makeFromObject(tutorialObj);

      TutorialRepository.__request({
        exec: "iniciarTutorial",
        tutorialId: tutorial.getId()
      }, function(response) {

        var etapaAtual = null, etapa;

        try {
          etapaAtual = tutorial.getEtapas().get()[0];
        } catch (e) {}

        if (idEtapa) {
          etapaAtual = tutorial.getEtapas().get(idEtapa);
        }

        tutorial.setEtapaAtual(etapaAtual, true).then(function() {
          this.mask.destroy();
          tutorial.setup();          
        }.bind(this));

      }.bind(this));

    },

    "resumeTutorial": function() {

      var _this = this;

      this.getCurrentTutorial(function(result) {
        var tutorial = Tutorial.makeFromObject(result.tutorial);
        tutorial.start();
      });      

    },

    "getCurrentTutorial": function(callback) {

      var _this = this;

      TutorialRepository.__request({
        exec: "getTutorialCorrente"
      }, function(response) {

        var result = response.responseJSON;

        if (result.erro) {
          return console.error(result.message);
        }

        if (!result.tutorial) {
          return;
        }

        callback(result);

      });
    },

    "getTutorial": function(tutorialId, callback) {

      TutorialRepository.__request({
        exec: "getTutorial",
        tutorialId: tutorialId
      }, function(response) {

        var result = response.responseJSON;

        if (result.erro) {
          return console.error(result.message);
        }

        callback(result);
      });

    },

    /**
     * Metodo que renderiza a janela com a mascara por tras
     */
    "__renderContainer": function() {

      var closeNode = document.createElement('div');
      closeNode.classList.add('close');
      closeNode.innerHTML = '&times;';
      closeNode.addEventListener('click', this.destroy.bind(this));

      this.container.appendChild(closeNode);

      this.container.classList.add('tutoriais-container');      
      this.mask = new DBMask();
      this.mask.getMaskElement().appendChild(this.container);

    },

    /**
     * Metodo que renderiza uma lista de tutoriais
     * @param  {array} tutoriais 
     */
    "__renderListaTutoriais": function(tutoriais) {
      
      var _this = this,
          tutorial,
          itemNode,
          itemNodeClick,
          lista = document.createElement('div');

      lista.classList.add('tutoriais-list');

      itemNodeClick = function(evt) {
        _this.showTutorial(evt.target.dataset.tutorialId);
      };

      for (tutorial of tutoriais) {

        itemNode = document.createElement('div');
        itemNode.classList.add('tutoriais-list-item');
        itemNode.dataset.tutorialId = tutorial.id;
        itemNode.textContent = tutorial.descricao;
        
        itemNode.addEventListener('click', itemNodeClick);

        lista.appendChild(itemNode);
      }

      if (tutoriais.length === 0) {

        itemNode = document.createElement('em');
        itemNode.classList.add('tutoriais-list-item');
        itemNode.textContent = 'Não há tutoriais disponíveis.';

        lista.classList.add('tutoriais-list-empty');
        lista.appendChild(itemNode);
      }

      this.container.appendChild(lista);
    },

    /**
     * Metodo que renderiza as informacoes de um turorial
     * @param  {object} tutorial 
     */
    "__renderTutorial": function(tutorial) {
      
      var containerNode = document.createElement('div'),
          infoNode = document.createElement('div'),
          backNode = document.createElement('div'),
          tituloNode = document.createElement('div'),
          startButtonNode,
          listaEtapasNode = document.createElement('div'),
          etapaNode, passoNode, etapa, passo, listaPassosNode, tituloEtapaNode;

      containerNode.classList.add('tutorial-info');

      backNode.classList.add('back');
      backNode.textContent = '< Voltar';
      backNode.addEventListener('click', function() {
        this.container.removeChild(containerNode);
      }.bind(this));

      containerNode.appendChild(backNode);

      // titulo do tutorial
      tituloNode.classList.add('tutorial-info-titulo');
      tituloNode.textContent = tutorial.descricao;
      infoNode.appendChild(tituloNode);

      for (etapa of tutorial.etapas) {

        etapaNode = document.createElement('div');
        etapaNode.classList.add('tutorial-info-etapa');

        if (!etapa.permissao) {
          etapaNode.classList.add('tutorial-info-etapa-disabled');
          etapaNode.setAttribute('title', 'Você não possui permissão para executar esta etapa.');
        }

        // botao de iniciar o tutorial
        startButtonNode = document.createElement('span');
        startButtonNode.setAttribute('title', 'Iniciar a partir desta etapa.');
        startButtonNode.classList.add('start');
        if (!!etapa.permissao) {        
          startButtonNode.addEventListener('click', function(etapa) {
            this.startTutorial(tutorial, etapa.id);
          }.bind(this, etapa));
        }

        etapaNode.appendChild(startButtonNode);        

        tituloEtapaNode = document.createElement('h4');
        tituloEtapaNode.innerHTML = etapa.ordem + ') ' + etapa.descricao + '<span>(' + etapa.passos.length + ' passos)</span>';
        etapaNode.appendChild(tituloEtapaNode);
       
        listaEtapasNode.appendChild(etapaNode);
      }

      infoNode.appendChild(listaEtapasNode);
      containerNode.appendChild(infoNode);
      this.container.appendChild(containerNode);
    },

    "destroy": function() {
      this.mask.destroy();
    }

  };

  TutorialRepository.build = function() {

    var repository = new TutorialRepository();
    repository.show();
  };

  TutorialRepository.resume = function() {
    var repository = new TutorialRepository();
    repository.resumeTutorial();    
  };

  TutorialRepository.__request = function(params, callback) {

    var oRequisicao = {
      method: "POST",
      parameters: "json=" + JSON.stringify(params),
      onComplete: callback || function() {}
    };

    new Ajax.Request(TutorialRepository.URL_RPC, oRequisicao);
  };

  TutorialRepository.dependencies = function() {

    if ( !exports.require_once ) {
      return console.error("Não é possível carregar as dependências (scripts.js não carregado)");
    }

    if ( !exports.Ajax ) {
      require_once("scripts/prototype.js");
    }

    if ( !exports.DBMask ) {
      require_once("scripts/widgets/DBMask.widget.js");
    }

    if (!exports.Collection) {
      require_once('scripts/widgets/Collection.widget.js');
    }

    if (!exports.Tutorial) {
      require_once('skins/estilos.php?file=views/tutoriais.css');
      require_once('scripts/classes/configuracao/Tutorial.classe.js');
      require_once('scripts/classes/configuracao/TutorialEtapa.classe.js');
    }

    if (!exports.introJs) {
      require_once('ext/javascript/introjs/introjs.min.css');
      require_once('ext/javascript/introjs/intro.js');
    }

  };

  exports.TutorialRepository = TutorialRepository;

})(this);
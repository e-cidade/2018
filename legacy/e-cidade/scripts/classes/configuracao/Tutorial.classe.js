/* jshint esversion: 6 */
/* jshint -W100 */

/**
 * Classe que representa um tutorial do sistema.
 * Responsavel por iniciar o tutorial integrado com o IntroJS
 * @author Vitor Rocha <vitor@dbseller.com.br>
 */
;(function(exports) {

  'use strict';

  var Tutorial = function() {

    this.id = null;
    this.descricao = null;
    this.introJs = null;

    this.etapaAtual = null;
    this.etapas = Collection.create().setId('id');

  };

  Tutorial.makeFromObject = function(objTutorial) {

    var tutorial = new Tutorial(), etapa, instanceEtapa;

    tutorial.setId(objTutorial.id);
    tutorial.setDescricao(objTutorial.descricao);

    for (etapa of objTutorial.etapas) {

      instanceEtapa = TutorialEtapa.makeFromObject(etapa);
      tutorial.getEtapas().add(instanceEtapa);

      if (instanceEtapa.getId() === objTutorial.etapaAtual.id) {
        tutorial.setEtapaAtual(instanceEtapa);
      }

    }

    return tutorial;
  };

  Tutorial.prototype = {

    "setup": function() {

      var etapa = this.getEtapaAtual(),
          newLocation;

      if (!top.ECIDADE_DESKTOP) {

        TutorialRepository.__request({
          exec: "setModulo",
          moduloId: etapa.getModulo()
        }, function() {
          console.log('redirecting', etapa)
          CurrentWindow.corpo.location = etapa.getMenu();
        });

        return;
      }

      newLocation = CurrentWindow.getURL().replace(/(^.*)(action.*)/, '$1');

      var oParams = {
          action: etapa.getMenu(),
          iInstitId: top.jQuery('#instituicoes span.active').data('id'),
          iAreaId: top.jQuery('#areas span.active').data('id'),
          iModuloId: etapa.getModulo()
        };

      newLocation += top.jQuery.param(oParams);

      CurrentWindow.options.url = newLocation;
      CurrentWindow.refresh();

    },

    /**
     * Inicializa o tutorial na tela para o usuario, baseados nas informacoes das propriedades da classe
     * @return void
     */
    "start": function() {

      this.getIntroJs().setOption('steps', this.getStepsForIntroJS());

      if (this.getEtapaAtual().getPassoAtual().ordem-1 > 0) {
        this.getIntroJs().goToStep(this.getEtapaAtual().getPassoAtual().ordem-1);
      }

      this.getIntroJs().start();

    },

    /**
     * Metodo lazy load responsavel por retornar a instacia do introJs
     * @return {introJs} Instancia do introJs
     */
    "getIntroJs": function() {

      if (this.introJs === null) {
        this.introJs = this.buildIntroJs();
      }

      return this.introJs;
    },

    /**
     * Cria a instancia do introJs e define os eventos necessarios para utilizacao no ecidade
     * @return {introJs}  Instancia do introJs
     */
    "buildIntroJs": function() {

      var _this = this;

      var _introJs = introJs().setOptions({
        nextLabel: "Próximo",
        prevLabel: "Reiniciar Etapa",
        skipLabel: "Sair",
        doneLabel: "Finalizar Etapa",
        exitOnOverlayClick: false,
        showOverlay: false,
        enlargeElement: true,
        exitOnEsc: false,
        scrollToElement: false,
        showButtons: true,
        showStepNumbers: false,
        showBullets: false,
        showProgress: false,
        tooltipPosition: 'auto',
        keyboardNavigation: false
      });

      _introJs.onbeforechange(function() {

        return new Promise(function(resolve, reject) {

          var _intervalHandler,
              _currentStep = _introJs._introItems[_introJs._currentStep];

          // caso o usuarios clicou em "Anterior/Reiniciar Etapa"
          // atualizamos a tela para começar do inicio
          if ( _introJs._direction == 'backward' ) {
            _this.getEtapaAtual().setPassoAtual( _this.getEtapaAtual().getPassos().get()[0] , true).then(function() {

              if (!top.ECIDADE_DESKTOP) {
                CurrentWindow.corpo.location = _this.getEtapaAtual().getMenu();
                return;
              }

              exports.CurrentWindow.refresh();
            });
            return;
          }

          // controle de passos
          // se for a ultima etapa, setamos o label para finalizar o tutorial
          if (_this.getEtapaAtual().getOrdem() >= _this.getEtapas().get().length) {
            _introJs.setOption('doneLabel', 'Finalizar Tutorial');
          }

          if (!_currentStep) {
            return resolve();
          }

          // se o elemento ja foi encontrado && o elemento possui uma window (caso tenha recarregado a pagina, perdemos a referencia)
          if (_currentStep.elementFound && _currentStep.element.ownerDocument.defaultView) {
            return resolve();
          }

          // procuramos até encontrar o elemento, (pode ser q a tela nao tenha carregado ainda (ex iframes))
          _intervalHandler = setInterval(function() {

            var _element;

            if (_currentStep !== _introJs._introItems[_introJs._currentStep]) return;

            try {
              _element = _this.getElementByXPath(_currentStep.xpath);
            } catch (e) {
              _element = null;
            }

            if (_element) {

              _currentStep.element = _element;
              _currentStep.elementFound = true;
              clearInterval(_intervalHandler);
              resolve();
            }

          }, 100);

        });

      });

      // para cada passo que mudar (ir/voltar) informar ao backend o passo
      _introJs.onchange(function(element) {

        var proximoPasso = _this.getEtapaAtual().getPassos().get().filter(function(obj) {
          return obj.ordem-1 === _introJs._currentStep;
        })[0];

        _this.getEtapaAtual().setPassoAtual(proximoPasso, true);

        if (_introJs._introItems[_introJs._currentStep] && _introJs._introItems[_introJs._currentStep].element) {

          _introJs._introItems[_introJs._currentStep].element.ownerDocument.defaultView.frameElement.addEventListener('load', function() {
            _introJs.goToStep(_introJs._currentStep + 1);
          });
        }

      });

      _introJs.onafterchange(function(element) {

        // corrige o layer
        element.addEventListener('click', function() {
          _introJs.refresh();
        });

        // se for input colocamos o foco nele
        if (element instanceof HTMLInputElement) {
          element.focus();
        } else {
          // se nao for input procuramos pelo input mais proximo e damo foco
          var input = element.ownerDocument.querySelector('input, textarea');
          if (input) input.focus();
        }

        _introJs.prevTooltipButton.style.display = 'inline-block';
        // se for o primeiro passo, removemos o botao de anterior
        if (_introJs._currentStep === 0) {
          _introJs.prevTooltipButton.style.display = 'none';
        }

        // se for o utlimo passo, nao fazemos nada
        if (_introJs._currentStep == _introJs._introItems.length) {
          return;
        }

        // sempre bloqueia o botao de proximo
        _introJs.nextTooltipButton.style.pointerEvents = 'none';
        _introJs.nextTooltipButton.classList.add('introjs-disabled');

        // quando encontrar o proximo elemento e ele for visivel,
        // desbloqueamos o botao
        var _interval;
        _interval = setInterval(function() {

          var _element;

          if (!_introJs._introItems[_introJs._currentStep + 1]) return;

          _element = _this.getElementByXPath(_introJs._introItems[_introJs._currentStep + 1].xpath, exports);

          if (!_element) return;

          if (!_this.__isElementVisible(_element)) return;

          _introJs._introItems[_introJs._currentStep + 1].element = _element;
          _introJs.nextTooltipButton.style.pointerEvents = 'auto';
          _introJs.nextTooltipButton.classList.remove('introjs-disabled');
          clearInterval(_interval);
        }, 100);

        if (_introJs._introItems[_introJs._currentStep].element) {
          return;
        }

      });


      // quando finalizar o tutorial, informa o backend para finalizar (quando nao houver mais passos em outra rotina)
      _introJs.oncomplete(function() {

        if (_introJs._currentStep < (_this.getEtapaAtual().getPassos().get().length - 1) ) {
          return;
        }

        var proximaEtapa, numeroProximaEtapa;

        for (proximaEtapa of _this.getEtapas().get()) {

          if (proximaEtapa.getOrdem() <= _this.getEtapaAtual().getOrdem()) {
            continue;
          }

          if (!proximaEtapa.getPermissao()) {
            continue;
          }

          break;
        }

        numeroProximaEtapa = proximaEtapa.getOrdem();

        // se a etapa atual for a mesma que a proxima, entao chegamos ao final,
        // pois nao foi possivel encontrar a proxima etapa.
        if (numeroProximaEtapa == _this.getEtapaAtual().getOrdem()) {

          TutorialRepository.__request({
            exec: 'finalizarTutorial',
            tutorialId: _this.getId()
          });

          return;
        }

        Promise.all([
          _this.setEtapaAtual(proximaEtapa, true),
          _this.getEtapaAtual().setPassoAtual( _this.getEtapaAtual().getPassos().get()[0] , true),
          (new Promise(function(resolve) {
            _this.setup();
            resolve();
          }))
        ]);

      });


      // quando fechar o tutoral (sem finalizar/ler todo), informa o backend para finalizar
      _introJs.onexit(function() {

        TutorialRepository.__request({
          exec: 'finalizarTutorial',
          tutorialId: _this.getId()
        });

      });

      return _introJs;
    },

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

    "getEtapas": function() {
      return this.etapas;
    },

    "setEtapaAtual": function(etapaAtual, persist) {
      this.etapaAtual = etapaAtual;

      return new Promise(function(resolve, reject) {

        if (!persist) return resolve();

        TutorialRepository.__request({
          exec: 'setEtapaAtual',
          etapaAtual: etapaAtual.getId()
        }, resolve);

      });

    },

    "getEtapaAtual": function() {
      return this.etapaAtual;
    },

    /**
     * Retorna um array do passos no formato aceito pelo introJs
     * @return {array}
     */
    "getStepsForIntroJS": function() {

      var steps = Collection.create().setId('id'), passo, element;

      for (passo of this.getEtapaAtual().getPassos().get()) {

        try {
          element = this.getElementByXPath(passo.xpath);
        } catch (e) {
          element = null;
        }

        steps.add({
          id: passo.id,
          element: element,
          xpath: passo.xpath,
          intro: passo.conteudo
        });
      }

      return steps.get();
    },

    /**
     * Retorna um elemento da tela pelo xpath informado
     * @param  {string} path    XPath modificado no padrao do ecidade (aceitando iframes)
     * @param  {window} context Objeto da window no qual sera feita a busca
     * @return {DOMElement}         Elemento do DOM referente ao XPath
     */
    "getElementByXPath": function(path, context) {

      var parent, evaluator, element,  result;

      // xpath contem separador '>', transforma em array
      if (typeof path == 'string' && path.indexOf('>') !== -1) {
        path = path.split('>');
      }

      // xpath eh um array
      if (typeof path != 'string' && Object.prototype.toString.call(path) == '[object Array]') {

        for (var index = 0, length = path.length; index < length; index++) {

          element = this.getElementByXPath(path[index], parent);
          parent = (element && element.contentWindow ? element.contentWindow : element);
        }

        return element;
      }

      evaluator = new XPathEvaluator();
      context = context || exports;
      result = evaluator.evaluate(path, context.document.documentElement, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null);

      return result ? result.singleNodeValue : null;
    },

    /**
     * Verify if element is visible to user by zIndex and sizing
     * @param  {Element} element
     * @return {boolean}
     */
    "__isElementVisible": function(element, context) {

      var bounding = element.getBoundingClientRect()

      if (bounding.width <= 0 || bounding.height <= 0) return false;

      var pointElement = (context || exports).document.elementFromPoint(bounding.left, bounding.top);

      if (!pointElement) return false;

      // caso possua um iframe na frente do element devemos verificar
      if (pointElement.nodeName.toLowerCase() == 'iframe') {

        // se o element estiver na window corpo entao o elemento nao esta visisvel
        if (element.ownerDocument.defaultView === context) return false;

        // senao nós verificamos no contexto do elemento
        return this.__isElementVisible(element, pointElement.contentWindow);
        // return this.__isElementVisible(element, element.ownerDocument.defaultView);
      }
      // se for o introjs, ignoramos
      if (String(pointElement.getAttribute('class')).indexOf('introjs-tooltip') >= 0 ) return true;

      // se for o proprio element, encontramos
      if (pointElement === element) return true;

      // se o element for filho de outro do mesmo tamanho, "encontramos"
      var comparison = (Node.DOCUMENT_POSITION_PRECEDING | Node.DOCUMENT_POSITION_FOLLOWING | Node.DOCUMENT_POSITION_CONTAINS | Node.DOCUMENT_POSITION_CONTAINED_BY);
      if ( (element.compareDocumentPosition(pointElement) & comparison ) !== 0) return true;

      return false;
    }

  };

  exports.Tutorial = Tutorial;

})(this);

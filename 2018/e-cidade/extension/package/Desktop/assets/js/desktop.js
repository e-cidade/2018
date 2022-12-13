/**
 * Eventos customizados disparados neste arquivo:
 *
 * app:
 *
 * "app:window:create" = Disparado quando uma janela eh criada.
 * "app:window:destroy" = Disparado quando uma janela eh destruida
 *
 */

(function($, global) {

  var Desktop = {
    Taskbar : {
      Windows : {
        Button : {},
        Menu : {}
      },
    },
    Topbar : {},
    Menu : {},
    Window : {
      Iframe : {},
      Inspect: {}
    },
    Session : {},
    TopWindow : top,
    Timer : {},
    Loader : {},
  };

  Window.keepMultiModalWindow = true;
  Window.prototype.eventHandler = function(event) {
    Desktop.Window.Iframe.eventHandler(event, this);
  }

  var topbarHeight = $$('.topbar')[0].getHeight();
  var taskbarHeight = $$('.taskbar-container')[0].getHeight();
  var windowIndex = 0;

  Desktop.Window.create = function(title, data) {

    var url = 'extension/desktop/window/index/?' + $.param(data);
    var win = Desktop.Window.createDefaultWindow(url, title);

    var buttonList = [
      {
        'class': 'help',
        'title': 'Ajuda do Sistema',
        'alt': 'Ajuda do Sistema',
        'text': 'Ajuda do Sistema'
      },
      {
        'class': 'faq',
        'title': 'Perguntas Frequentes',
        'alt': 'Perguntas Frequentes',
        'text': 'Perguntas Frequentes'
      },
      {
        'class': 'release_notes',
        'title': 'Notas da Versão',
        'alt': 'Notas da Versão',
        'text': 'Notas da Versão',
      },
      {
        'class': 'tutoriais',
        'title': 'Tutoriais',
        'alt': 'Tutoriais guiados do sistema',
        'text': 'Tutoriais'
      }
    ];

    var settingButton = win.addButton({
      "img": 'assets/vendors/window/ecidade/setting.png',
      "class": "window_button",
      "alt": 'Configurações da Janela',
      "title": 'Configurações da Janela',
    });

    settingButton.on('click', function() {
      Desktop.Window.createSettingModal(win);
    });

    var dropdown = win.addButtonList(buttonList);

    dropdown.dropdownToggle.append($('<img>', {
      src: 'assets/vendors/window/ecidade/faq.png',
      alt: 'Central de Ajuda',
      title: 'Central de Ajuda'
    }));

    var helpButton = dropdown.dropdownMenu.find('.help');

    helpButton.on('click', function(e) {
      win.corpo.require_once('scripts/classes/configuracao/DBViewHelp.classe.js');
      win.corpo.DBViewHelp.build();
    });

    // Cria botao do faq
    var faqButton = dropdown.dropdownMenu.find('.faq');

    faqButton.on('click', function() {
      win.corpo.require_once('scripts/classes/configuracao/DBViewFaq.classe.js');
      win.corpo.DBViewFaq.build();
    });

    // Cria botao do release notes
    var releaseNotesButton = dropdown.dropdownMenu.find('.release_notes');

    releaseNotesButton.on('click', function() {
      win.corpo.require_once('scripts/classes/configuracao/DBViewReleaseNote.classe.js');
      win.corpo.DBViewReleaseNote.build();
    });

    // Cria botao do tutoriais
    var tutoriaisButton = dropdown.dropdownMenu.find('.tutoriais');

    tutoriaisButton.on('click', function() {
      win.corpo.TutorialRepository.build();
    });

    $(window).trigger('app:window:create', [win]);

    return win;
  }

  Desktop.Window.createDefaultWindow = function(uri, title, options) {

    options = jQuery.extend({
      id: 'w'+ (++windowIndex),
      maximized: true,
    }, options)

    var width = options.width || 800;
    var height = options.height || 600;
    var paddingHeight = taskbarHeight + topbarHeight;

    if (window.innerWidth <= width) {
      width = parseInt(window.innerWidth * .8);
    }

    if (window.innerHeight <= height + paddingHeight + 27) {
      height = parseInt((window.innerHeight - paddingHeight) * .8);
    }

    var left = (window.innerWidth - width) / 2;
    var top = ((window.innerHeight - height) / 2) - 30;

    if (top + height > window.innerHeight || top < 0) {
      top = 0;
    }

    var id = options.id;
    var win = new Window(id, {
      wiredDrag:true,
      className: "ecidade",
      width: width,
      height: height,
      top: top,
      left: left,
      closeCallback : function() {
        this.destroy();
      },

      onDestroy : function() {

        if (this.taskbarButton) {

          this.taskbarButton.remove()
          Desktop.Taskbar.Windows.Menu.update();
        }

        $(window).trigger('app:window:destroy', [this]);
      },

      onFocus : function() {

        if (this.taskbarButton) {
          Desktop.Taskbar.Windows.Button.focus(this.taskbarButton);
        }

        Desktop.Window.updateIndex();
      },

      onBlur : function() {

        if (this.taskbarButton) {
          Desktop.Taskbar.Windows.Button.blur(this.taskbarButton);
        }
      },
    });

    var sessionId = options.sessionId ? options.sessionId + '/' : windowIndex;
    var path = ECIDADE_REQUEST_PATH + 'w/' + sessionId + '/';
    var url = path + uri.replace(ECIDADE_REQUEST_PATH, '');

    win.setURL(url);
    win.setTitle(title);
    // win.setConstraint(true, {bottom: taskbarHeight});
    win.setConstraint(true, {bottom: taskbarHeight, top: topbarHeight});

    Desktop.Taskbar.Windows.Button.create(win);
    Desktop.Taskbar.Windows.Menu.update();

    win.show();
    if (options.maximized) {
      win.maximize();
    }
    win.focus();

    document.querySelector('#' +id +'_row1').addEventListener('dblclick', function(event) {
      Windows.maximize(id, event);
    }, false);

    win.getContent().contentWindow.Desktop = Desktop;
    win.getContent().contentWindow.CurrentWindow = win;

    return win;
  };

  Desktop.Window.createModal = function (id, title, action, options) {

    var win = new Window(id, {
      className: "ecidade",
      width: options.width || 480,
      height: options.height || 360,
      closable: options.closable || false,
      minimizable: false,
      maximizable: false,
      draggable: false,
      resizable: false,
      onFocus : options.onFocus || false,
      destroyOnClose: true,
      closeCallback : options.closeCallback || false,
      parentNode : options.parentNode || document.body,
      type : 'modal',
    });

    var sessionId = options.sessionId ? options.sessionId + '/' : '';
    var path = ECIDADE_REQUEST_PATH + sessionId + 'extension/desktop/' + action.replace(ECIDADE_REQUEST_PATH, '');

    win.setURL(path);
    win.setTitle(title);

    if (options.zIndex) {
      win.setZIndex(options.zIndex);
    }

    win.showCenter();
    return win;
  };

  Desktop.Window.createSettingModal = function(win) {

    var id = win.getId();
    var settingId = id + '-setting';

    // janela ja criada
    for (var index = 0, length = win.childrens.length; index < length; index++) {
      if (win.childrens[index].getId() == settingId) {
        return false;
      }
    }

    var modal = Desktop.Window.createModal(settingId, 'Configurações',  'window/setting/', {
      width: 420,
      height: 380,
      closable: true,
      sessionId : id.replace('w', 'w/'),
      onFocus : function() {
        win.focus();
      },
    });

    win.addChildren(modal, true);
  }

  Desktop.Window.updateIndex = function() {

    var menu = $('#menu');
    var menuIndex = parseInt(menu.css('zIndex'));

    if (menuIndex > Windows.maxZIndex) {
      return false;
    }

    menu.css({zIndex : Windows.maxZIndex + 100});
    $('.taskbar-container').css({zIndex : Windows.maxZIndex + 101});
    $('.topbar').css({zIndex : Windows.maxZIndex + 102});
  }

  Desktop.Window.Iframe.eventHandler = function(event, win) {

    Desktop.Timer.get('session.block').restart();

    win.focus();
    $.getOutsideListeners().each(function() {
      $(this).trigger(event.type + 'outside');
    });
  };

  Desktop.Taskbar.Windows = {

    container : $('.taskbar-container'),

    Button : {},
    Menu : {},

    find : function(selector) {
      return this.container.find(selector);
    },

  };

  Desktop.Taskbar.Windows.Button = {

    container : Desktop.Taskbar.Windows.find('> .taskbar-buttons'),

    create : function(win) {

      var element = $('<li />', {
        'class': 'taskbar-buttons-item'
      }).data('window', win);

      win.taskbarButton = element;
      element.html(win.getTitle());

      this.container.append(element);

      element.on("mousedown", function(event) {

        if (event.which === 2) {
          return win.close();
        }

        Desktop.Taskbar.Windows.find('.taskbar-buttons-item.active').removeClass('active');

        if (win.isVisible() && !win.isFocused()) {
          return win.focus();
        }

        if (win.isVisible() && win.isFocused()) {
          return win.minimize();
        }

        win.minimize();
      });

      Desktop.Taskbar.Windows.Menu.show();

    }, // create()

    click : function(button, event) {
      button.triggerHandler(event || 'mousedown');
    },

    focus : function(button) {

      this.container.find('.taskbar-buttons-item.active').removeClass('active');
      button.addClass('active');
    },

    blur : function(button) {
      button.removeClass('active');
    },

  } // button()

  Desktop.Taskbar.Windows.Menu = {

    container : Desktop.Taskbar.Windows.find('.taskbar-buttons-modal'),
    active : false,

    show : function() {
      return this.container.show();
    },

    hide : function() {
      return this.container.hide();
    },

    close : function() {

      this.active = false;
      this.container.toggleClass('active', false);
      this.toggleEvents();
    },

    toggle : function() {

      this.active = !this.container.hasClass('active');
      this.container.toggleClass('active', this.active);
      this.toggleEvents();
    },

    toggleEvents : function() {

      var self = this;
      if (this.active) {
        return this.container.on('mousedownoutside', function(e, target) {
          self.close();
        });
      }

      this.container.off('mousedownoutside');
    },

    update : function() {

      var itens = Desktop.Taskbar.Windows.Button.container.find('li');

      if ( !itens.length ) {

        this.active = false;
        this.container.toggleClass('active', false);
        return this.hide();
      }

      var $modal = this.container.find('.content');

      var ul = $modal.find('ul');
      if (ul.length > 0) {
        ul.scrollator('destroy');
      }

      $modal.empty();
      $modal.append($('<ul />', {'class': 'taskbar-buttons'}));

      itens.each(function(index, item) {
        Desktop.Taskbar.Windows.Menu.add($(this));
      });

      $modal.find('ul').scrollator({zIndex: Windows.maxZIndex + 104});
    },

    add : function(button) {

      var oNewList = $('<li>', {
        text: button.text(),
        'class': button.attr('class')
      });

      oNewList.on('mousedown', function(event) {
        Desktop.Taskbar.Windows.Button.click(button, event);
        // Desktop.Taskbar.Windows.Menu.close();
      });

      this.container.find('ul').append(oNewList);
    }

  };

  Desktop.Session.updateGlobal = function(data, callback) {

    $.ajax({
      url: 'desktop/session',
      data: data,
      type: 'POST',
      dataType: 'json'
    }).done(function(data) {

      callback(null, data);

    }).fail(function(xhr) {

      var data = JSON.parse(xhr.responseText);
      callback(data.message, data);
    });
  };

  /**
   * Timer
   */
  ;(function() {

    'use strict';

    var timers = {}, Timer = function(id, countdown, callback, loop) {

      this.id = id;
      this.countdown = countdown;
      this.callback = function() {

        callback();

        if (this._loop) {
          this.start();
        }
      };
      this.timeout = false;
      this._loop = loop || false;

      if (timers[id]) {
        console.warn('timer já registrado:', id);
      }

      timers[id] = this;
    }

    Timer.prototype = {

      start : function() {
        this.timeout = setTimeout(this.callback, this.countdown);
        return this;
      },

      stop : function() {
        return this.clear();
      },

      restart : function() {
        return this.stop().start();
      },

      clear : function() {
        clearTimeout(this.timeout);
        return this;
      },

      loop : function() {
        this._loop = true;
        this.start();
      }
    }

    Timer.get = function(id) {
        return timers.hasOwnProperty(id) ? timers[id] : false;
    };

    Desktop.Timer = Timer;

  })();

  /**
   * Loader
   */
  ;(function(exports) {

    /**
     * @param {HTMLElement} parentNode
     */
    function createModal(parentNode) {

      var imgUrl = ECIDADE_REQUEST_PATH + 'extension/desktop/assets/img/Window/loader.gif';
      var node = parentNode.ownerDocument.createElement('div');
      node.style.background = 'no-repeat center center url("'+ imgUrl +'") #e1dede';
      node.style.height = '100%';
      node.style.width = '100%';
      node.style.webkitOpacity = '0.6';
      node.style.mozOpacity = '0.6';
      node.style.opacity = '0.6';
      node.style.position = 'absolute';
      node.style.top = '0';
      node.style.left = '0';
      node.style.zIndex = '201';
      return parentNode.appendChild(node);
    }

    /**
     * @param {HTMLElement} parentNode
     */
    function createMensageContainer(parentNode) {

      var element = parentNode.ownerDocument.createElement('div');
      element.style.position = 'absolute';
      element.style.top = '0';
      element.style.left = '50%';
      element.style.transform = 'translateX(-50%)';
      element.style.mozTransform = 'translateX(-50%)';
      element.style.webkitTransform = 'translateX(-50%)';
      element.style.margin = 'auto';
      element.style.maxWidth = '90%';
      element.style.fontFamily = 'Verdana, Arial, Helvetica, sans-serif';
      element.style.fontSize = '14px';
      element.style.color = '#333';
      element.style.border = '1px solid #333';
      element.style.borderBottom = '0';
      element.style.zIndex = '202';
      element.style.display = 'none';
      element.style.textAlign= 'center';
      element.style.background = '#ffffcc';
      element.style.borderCollase = 'collapse';
      element.style.opacity = '0.8';
      return parentNode.appendChild(element);
    }

    /**
     * @param {Object} data {id, html}
     * @param {HTMLElement} parentNode
     */
    function createMensage(data, parentNode) {

      var element = parentNode.ownerDocument.createElement('div');
      element.id = data.id;
      element.innerHTML = data.html;
      element.style.borderBottom = '1px solid #333';
      element.style.padding = '2px 5px';
      return parentNode.appendChild(element);
    }

    /**
     * @param {HTMLElement} parentNode
     * @param {Desktop.Loader} parent
     */
    function Message(parentNode, parent) {

      this.element = createMensageContainer(parentNode);
      this.parentNode = parentNode;
      this.parent = parent;
    }

    Message.prototype = {

      /**
       * @type {HTMLElement}
       */
      element : null,

      /**
       * @param {Object} data {ìd, html}
       */
      add : function(data) {

        this.remove(data.id);
        this.element.appendChild(createMensage(data, this.parentNode));

        // loader esta visivel, exibe msg atual
        if (this.parent.isVisible()) {
          this.show();
        }
      },

      /**
       * Remove todas as mensagens
       */
      clear : function() {

        var childrens = Array.prototype.slice.call(this.element.children);
        for (var i = 0, len = childrens.length; i < len; i++) {
          childrens[i].parentNode.removeChild(childrens[i]);
        }
      },

      /**
       * @param {String} id
       */
      remove : function(id) {

        var node = this.element.querySelector('#' + id);
        if (node) {
          node.parentNode.removeChild(node);
        }
        if (!this.has()) {
          this.parent.hide();
        }
      },

      /**
       * @return {Boolean}
       */
      has : function() {
        return this.element.children.length > 0;
      },

      /**
       * @return {Boolean}
       */
      isVisible : function() {
        return this.element.style.display != 'none';
      },

      show : function() {
        this.element.style.display = 'block';
      },

      hide : function() {
        this.element.style.display = 'none';
      },

    };

    /**
     * @param {HTMLElement} parentNode
     */
    var Loader = function(parentNode) {

      this.parentNode = parentNode;

      this.element = createModal(this.parentNode);
      this.message = new Message(this.parentNode, this);
    }

    Loader.prototype = {

      /**
       * @type {HTMLElement}
       */
      parentNode : null,

      /**
       * @type {HTMLElement}
       */
      element : null,

      /**
       * @type {Message}
       */
      message : null,

      show : function() {

        if (this.message.has()) {
          this.message.show();
        }

        this.element.style.display = 'block';
      },

      hide : function() {

        if (this.message.has() && this.message.isVisible()) {
          return false;
        }

        this.element.style.display = 'none';
        this.message.hide();
      },

      /**
       * @return {Boolean}
       */
      isVisible : function() {
        return this.element.style.display != 'none';
      }

    };

    exports.Loader = Loader;

  })(Desktop);

  /**
   * Notification
   */
  ;(function(exports) {

    // sem suporte
    if (!window.Notification) {
      return;
    }

    var _Notification = window.Notification, Notification = function() {};

    Notification.prototype = {

      hasPermission : function() {
        return _Notification.permission == 'granted';
      },

      requestPermission : function() {
        if (!this.hasPermission()) {
          _Notification.requestPermission();
        }
      },

      send : function(title, content) {

        this.requestPermission();
        return new _Notification(title, { body: content });
      },

    }

    exports.Notification = Notification;

  })(Desktop);

  /**
   * Inspect
   */
  ;(function(exports) {

    var Inspect = function(targetWindow, callerWindow) {

      this.callerWindow = callerWindow;
      this.targetWindow = targetWindow;
      this.root = this.targetWindow.corpo;
    }

    Inspect.prototype = {

      nodes: [],
      callerWindow: null,
      targetWindow: null,
      root : null,
      focusedElement: null,
      onXPathFound: function() {},

      init: function() {

        var _this = this;

        this.nodes = this.getDocuments(this.root.document);
        this.injectEvents();
        this.targetWindow.focus();

        this.targetWindow.addObserver("onBlur", function() {

          _this.removeEvents();

          if (_this.focusedElement) {
            setTimeout(function() {
              _this.restoreElement(_this.focusedElement);
            }, 666);
          }

        });
      },

      createLayer: function(context) {

        var layer = context.createElement('layer');

        layer.class = 'inspect-layer';
        layer.style.background = 'rgba(82, 168, 236, 0.4)';
        layer.style.position = 'absolute';
        layer.style.display = 'none';
        layer.style.pointerEvents = 'none';
        layer.style.zIndex = 99999

        context.body.appendChild(layer);

        return layer;
      },

      elementToXPath: function(elm, context) {

        var context = context || this.root || window;
        var paths = [];

        if (context.frameElement && context.frameElement.getAttribute('id') != 'corpo' && context.frameElement.contentWindow.parent) {
          paths = this.elementToXPath(context.frameElement, context.frameElement.contentWindow.parent);
        }

        var allNodes = context.document.getElementsByTagName('*');
        for (var segs = []; elm && elm.nodeType == 1; elm = elm.parentNode) {
            if (elm.hasAttribute('id')) {
                    var uniqueIdCount = 0;
                    for (var n=0;n < allNodes.length;n++) {
                        if (allNodes[n].hasAttribute('id') && allNodes[n].id == elm.id) uniqueIdCount++;
                        if (uniqueIdCount > 1) break;
                    };

                   if (uniqueIdCount == 1 && elm.getAttribute('id').indexOf(' ') === -1) {

                        segs.unshift('id("' + elm.getAttribute('id') + '")');
                        paths.push(segs.join('/'));
                        return paths;

                    } else {
                        segs.unshift(elm.localName.toLowerCase() + '[@id="' + elm.getAttribute('id') + '"]');
                    }
            } else if (elm.hasAttribute('name')) {
                segs.unshift(elm.localName.toLowerCase() + '[@name="' + elm.getAttribute('name') + '"]');
            } else {
                for (i = 1, sib = elm.previousSibling; sib; sib = sib.previousSibling) {
                  if (sib.localName == elm.localName)  i++;
                };
                var tag = elm.localName.toLowerCase();
                if (i > 1) {
                  tag += '[' + i + ']';
                }
                segs.unshift(tag);
            };
        };

        if (segs.length > 0) {
          paths.push('/' + segs.join('/'));
        }

        return paths;
      },

      /**
       * @param {String|Array} path
       * @param {HTMLElement} context
       * @return {HTMLElement}
       */
      getElementByXPath : function(path, context) {

        // xpath contem separador '>', transforma em array
        if (typeof path == 'string' && path.indexOf('>') !== -1) {
          path = path.split('>');
        }

        // xpath eh um array
        if (typeof path != 'string' && Object.prototype.toString.call(path) == '[object Array]') {

          var parent = null;

          for (var index = 0, length = path.length; index < length; index++) {

            var element = this.getElementByXPath(path[index], parent);
            parent = element.contentWindow || element;
          }

          return element;
        }

        var evaluator = new XPathEvaluator();
        var context = context || this.root || window;
        var result = evaluator.evaluate(path, context.document.documentElement, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null);
        return result.singleNodeValue;
      },

      /**
       * @param {HTMLElement} node
       * @param {String} query
       * @return {Array}
       */
      getDocuments : function(document) {

        var nodes = [document];
        var iframes = document.getElementsByTagName('iframe');
        for (var index = 0, len = iframes.length; index < len; index++) {
          nodes = nodes.concat(this.getDocuments(iframes[index].contentWindow.document));
        }
        return nodes;
      },

      injectEvents: function() {

        var _this = this;
        this.removeEvents();
        this.nodes.forEach(function(node) {

          node.defaultData = {
            listenerMouseMove : _this.mouseMove.bind(_this),
            layer: _this.createLayer(node),
          };
          node.addEventListener('mousemove', node.defaultData.listenerMouseMove);
        });
      },

      removeEvents: function() {

        var _this = this;
        this.nodes.forEach(function(node) {
          if (node.defaultData) {
            node.removeEventListener('mousemove', node.defaultData.listenerMouseMove);
          }
        });
      },

      mouseMove: function(event) {

        var element = event.target.tagName ? event.target : event.target.parentNode;
        var _this = this;


        this.focusElement(element);

        element.defaultData.onmousedown = onmousedown;

        element.onmousedown = function(event) {

          element.style.pointerEvents = 'none';

          event.preventDefault();
          event.stopPropagation();
          event.stopImmediatePropagation();

          _this.callerWindow.focus();

          var xpath = _this.elementToXPath(event.target, event.view);

          if (_this.onXPathFound) {
            _this.onXPathFound(xpath.join('>'));
          }

          return false;
        }

      },

      focusElement: function(element) {

        if (this.focusElement == element) {
          return false;
        }

        if (this.focusedElement) {
          this.restoreElement(this.focusedElement);
        }

        this.focusedElement = element;

        if (!element.defaultData) {

          element.defaultData = {
            title : element.title,
            style : {
              outline: element.style.outline,
              cursor: element.style.cursor,
            }
          };
        }

        element.style.cursor = 'default';
        element.title = '<' + element.tagName.toLowerCase() + '>';

        if (!element.defaultData.layer && element.ownerDocument.defaultData && element.ownerDocument.defaultData.layer) {

          var layer = element.ownerDocument.defaultData.layer;
          elementRect = element.getBoundingClientRect();
          layer.style.width = elementRect.width + 'px';
          layer.style.height = elementRect.height + 'px';
          layer.style.top = elementRect.top;
          layer.style.left = elementRect.left;
          layer.style.display = 'block';

          element.defaultData.layer = layer;
        } else {
          element.style.outline = "2px solid rgba(82, 168, 236, 0.8)";
        }

      },

      restoreElement: function(element) {

        if (!element.defaultData) {
          return;
        }

        element.style.outline = element.defaultData.style.outline;
        element.style.cursor = element.defaultData.style.cursor;
        element.style.pointerEvents = 'auto';
        element.title = element.defaultData.title;

        if (element.defaultData.layer) {
          element.defaultData.layer.style.display = 'none';
        }

        element.onmousedown = element.defaultData.onmousedown;

        element.defaultData = false;
      }

    };

    exports.Inspect = Inspect;

  })(Desktop.Window);

  global.Desktop = Desktop;

})(jQuery.noConflict(), this);

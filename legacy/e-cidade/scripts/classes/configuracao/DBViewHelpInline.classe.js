var CONTEXT = this;

/**
 *  Gerador do Help
 *  @constructor
 *  @return {DBViewHelpInline} Help
 */
DBViewHelpInline = function() {

  DBViewHelpInline.dependencies();

  var _this = this;

  /**
   * URL do RPC para buscar os dados.
   */
  _this.URL_RPC = "con4_dbhelp.RPC.php";

  _this.aFields = [];

  _this.stopRaf = false;

  _this.runningRaf = false;

  _this.raf = '';

  ['requestAnimationFrame', 'mozRequestAnimationFrame', 'webkitRequestAnimationFrame'].forEach(function(val) {
    if (CONTEXT[val] && !_this.raf) {
      _this.raf = val;
    }
  });

};

DBViewHelpInline.build = function() {

  var oDBHelpInline = new DBViewHelpInline();
  oDBHelpInline.init();

};

DBViewHelpInline.prototype.init = function() {
  this.retrieveFields(this.registerEvents.bind(this));
};

DBViewHelpInline.prototype.retrieveFields = function(callback) {

  var _this = this;

  var oParametros = {
    exec: "getHelpFields"
  };

  var oRequisicao = {
    method: "GET",
    parameters: "json=" + JSON.stringify(oParametros),
    onComplete: function(oAjax) {

      var oRetorno = JSON.parse(oAjax.responseText);

      if (oRetorno.iStatus == 2) {
        return;
      }

      _this.aFields = oRetorno.aFields;

      callback();

    }
  };

  new Ajax.Request(this.URL_RPC, oRequisicao);
};

DBViewHelpInline.prototype.registerEvents = function() {

  var _this = this, context = CONTEXT, CurrentWindow = context.parent.CurrentWindow;


  context.addEventListener('load', function() {
    _this.onWindowLoaded(context);
  });

  if (context.document.readyState == "complete") {
    _this.onWindowLoaded(context);
  }

  // Verificacao do ecidade 3
  if (!top.ECIDADE_DESKTOP) return;

  if (CurrentWindow.onFocusHelpCallback) CurrentWindow.removeObserver('onFocus', CurrentWindow.onFocusHelpCallback);

  CurrentWindow.onFocusHelpCallback = function onFocusHelpCallback(win) {
    _this.stopRaf = false;
    _this.onWindowLoaded(context);
  };

  // Ao dar foco na window atual, reexecutamos os loop
  CurrentWindow.addObserver('onFocus', CurrentWindow.onFocusHelpCallback);

  if (CurrentWindow.onBlurHelpCallback) CurrentWindow.removeObserver('onBlur', CurrentWindow.onBlurHelpCallback);

  CurrentWindow.onBlurHelpCallback =  function onBlurHelpCallback(win) {
    _this.stopRaf = true;
  };

  // Ao dar blur na window atual, paramos o loop para evitar processamento desnecessario
  CurrentWindow.addObserver('onBlur', CurrentWindow.onBlurHelpCallback);

};

/**
 * Executado sempre que o corpo é carregado.
 */
DBViewHelpInline.prototype.onWindowLoaded = function(context) {

  var _this = this, fps = 60, frame = 0, handler = context[_this.raf];

  if (_this.runningRaf) return;

  var loop = function(time) {

    _this.runningRaf = true;

    // Caso tenho sido solicitada a parada do RAF
    var fields = _this.aFields &&  _this.aFields.length === 0;

    if (_this.stopRaf || fields) {

      _this.runningRaf = false;
      return;
    }

    // executa enquanto ainda existir elementos para adicionar help
    if (_this.aFields.length > 0 ) {
      handler(loop);
    }

    // nao busca elementos ate frame atual ser >= ao fps
    if (++frame < fps) {
      return false;
    }

    frame = 0;

    // Inicia a busca dos elementos
    _this.findElement(context);

  };

  // Executa o loop
  loop();

};

/**
 * busca elementos do help e adicioan icone do help
 */
DBViewHelpInline.prototype.findElement = function(context) {

  var _this = this;

  // Itera todos o fields que vieram da API
  _this.aFields.forEach(function(field, index) {

    var element;

    // elemento ja foi encontrado e adicionado ao Document
    if (field.element && field.element.parentNode && context.document.contains(field.element)) {
      return;
    }

    try {
      element = _this.getElementByXPath(field.xpath, CONTEXT);
    } catch(e) {}

    // encontrou elemento e Document ja foi carreagdo
    if (element) {
      field.element = _this.startElement(element, field);
    }

  });

};

DBViewHelpInline.prototype.startElement = function(element, field) {

  var _this = this;

  if (element.field) {
    return;
  }

  element.field = field;

  var icon = new DBViewHelpInline.Icon(element);
  icon.registerEvents();
  icon.initTooltip();

  return element;
};

DBViewHelpInline.prototype.getElementByXPath = function(path, context) {

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
  context = context || window;
  result = evaluator.evaluate(path, context.document.documentElement, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null);

  return result ? result.singleNodeValue : null;
};

/**
 * Método estatico responsavel por carregar as dependencias da view
 */
DBViewHelpInline.dependencies = function() {

  if ( !CONTEXT.require_once ) {
    throw "Não é possível carregar as dependências (scripts.js não carregado)";
  }

  if ( !CONTEXT.Ajax ) {
    require_once("scripts/prototype.js");
  }
}

/**
 * Icon module
 *
 * @module DBViewHelpInline/Icon
 * @type {Object}
 */
;(function(exports) {

  var _display = (function() {

    var _timeout, _icon, _delay = 500;

    var timer = {
      add : function(done) {

        this.clear();
        _timeout = setTimeout(done, _delay);
      },
      clear: function() {
        if (_timeout) {
          clearTimeout(_timeout);
        }
      }
    };

    return {
      timer : timer,
      show : function(icon) {

        this.timer.clear();

        if (_icon) {
          _icon.hide();
        }

        _icon = icon;
        _icon.show();
      },
      hide : function() {

        this.timer.add(function() {
          _icon.hide();
        });
      }
    };

  })();

  function _create(element) {

    var doc = element.ownerDocument;
    var oSpan = doc.createElement('span');
    oSpan.style.borderRadius    = '100%';
    oSpan.style.background      = 'url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAACXBIWXMAAAsTAAALEwEAmpwYAAABNmlDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjarY6xSsNQFEDPi6LiUCsEcXB4kygotupgxqQtRRCs1SHJ1qShSmkSXl7VfoSjWwcXd7/AyVFwUPwC/0Bx6uAQIYODCJ7p3MPlcsGo2HWnYZRhEGvVbjrS9Xw5+8QMUwDQCbPUbrUOAOIkjvjB5ysC4HnTrjsN/sZ8mCoNTIDtbpSFICpA/0KnGsQYMIN+qkHcAaY6addAPAClXu4vQCnI/Q0oKdfzQXwAZs/1fDDmADPIfQUwdXSpAWpJOlJnvVMtq5ZlSbubBJE8HmU6GmRyPw4TlSaqo6MukP8HwGK+2G46cq1qWXvr/DOu58vc3o8QgFh6LFpBOFTn3yqMnd/n4sZ4GQ5vYXpStN0ruNmAheuirVahvAX34y/Axk/96FpPYgAAACBjSFJNAAB6JQAAgIMAAPn/AACA6AAAUggAARVYAAA6lwAAF2/XWh+QAAABjklEQVR42ozTPUhVcRgG8J/nHoSCqEghxMSEIHIJG4WiD8jKocUGh0AQtaIiAgehsQahmhquWQYNBWEhVDT0IXEHXVJBaoh7UwgDiYiGjFtmy7lyOJx76Bn/z/O87/N/ed8a7VclsAe9OIad0VsJLzCK93FxTaxALW7gDALp+IvbuIQVCGPmZzgiGwH6sStKWK50upk0P7ly3FphyFphyPLEeV1tDXH6UJRWgNao6jqude9z8uheC5+/elX4oH7bJsODnck0A2gNo4Hl4szC0nf3H08Zm5j1pvjNp4f1mhvrkgVy6A3RkWRGJotGJovraZob60zPlNJm0hGgpdrEutoaXOw56OdK2fWxt2mSljBr5Jd79tu4odapC/c8ereUJikH0ZKk4svyD9MzpWpmWAyjDdudxk7NLdqxfUtWyJcB7mA1jR3obnfu9IFq5lWMhphHHmeTisHhp1nd85iv3ML/rnIFr5OrXMYJ3IoORsYx5dEZeeQ0HY7/6TnG8RtbsRm/8BEP0Ie7+FMx/RsAOCBrqUPuxKMAAAAASUVORK5CYII=) no-repeat transparent';
    oSpan.style.display         = 'none';
    oSpan.style.color           = '#fff';
    oSpan.style.position        = 'fixed';
    oSpan.style.width           = '16px';
    oSpan.style.height          = '16px';
    oSpan.style.lineHeight      = '14px';
    oSpan.style.fontSize        = '14px';
    oSpan.style.cursor          = 'pointer';
    oSpan.style.zIndex          = '9999999';

    doc.body.appendChild(oSpan);

    return oSpan;
  }

  var Icon = function(parent) {

    this.element = _create(parent);
    this.parent = parent;
    this.tooltip = null;
  };

  Icon.prototype.registerEvents = function() {

    var callback = {
      clear : _display.timer.clear.bind(_display.timer),
      hide : _display.hide.bind(_display),
      show : _display.show.bind(_display),
      update : function() {

        var rect = this.parent.getBoundingClientRect();

        var top = rect.top;
        var left = rect.left;

        this.element.style.top = top + 'px';
        this.element.style.left = (left + rect.width) + 'px';

        _display.show(this);

      }.bind(this),
    };

    this.element.addEventListener('mouseenter', callback.clear);
    this.element.addEventListener('mouseout', callback.hide);
    this.parent.addEventListener('mouseenter', callback.update);
    this.parent.addEventListener('mouseout', callback.hide);
    this.parent.addEventListener('focus', callback.update);
    this.parent.addEventListener('blur', callback.hide);

  };

  Icon.prototype.show = function() {

    this.element.style.display = 'block';
  };
  Icon.prototype.hide = function() {

    this.element.style.display = 'none';
    if (this.tooltip) this.tooltip.hide();
  };

  Icon.prototype.initTooltip = function() {

    if (this.tooltip) return this.tooltip;

    this.tooltip = new Tooltip(this.element, this.parent.field.label, this.parent.field.content);
    this.tooltip.registerEvents();

    return this.tooltip;
  };

  // Aplica a classe Icon no help
  exports.Icon = Icon;

  var Tooltip = function(parent, title, content) {

    this.parent = parent;
    this.title = title;
    this.element = null;
    this.content = content;

    this.init();
  };

  Tooltip.prototype.init = function() {

    this.element = CONTEXT.document.createElement('div');

    this.element.style.borderRadius    = '4px';
    this.element.style.backgroundColor = '#fff';
    this.element.style.boxShadow       = '0px 0px 5px 1px #000';
    // this.element.style.border          = '1px solid #000';
    this.element.style.position        = 'fixed';
    this.element.style.padding         = '10px';
    this.element.style.maxWidth        = '300px';
    this.element.style.lineHeight      = '20px';
    this.element.style.fontSize        = '14px';
    this.element.style.display         = 'none';
    this.element.style.zIndex          = '9999999';
    this.element.style.minWidth        = '200px';

    this.element.innerHTML = this.title + '<hr style="pointer-events: none; border: none; border-top: 1px solid; margin: 5px 0px;" noshade size="1px" />' + this.content;

    CONTEXT.document.body.appendChild(this.element);

  };

  Tooltip.prototype.registerEvents = function() {

    var _this = this;

    _this.parent.addEventListener('click', function() {
      _this.show();
      _display.timer.clear();
    });

    _this.element.addEventListener('mouseenter', function() {
      _display.timer.clear();
    });

    _this.element.addEventListener('mouseout', function() {
      _display.hide();
    });

  };

  Tooltip.prototype.show = function() {

    this.element.style.display = 'block';

    var rectParent  = this.parent.getBoundingClientRect();
    var rectFrame = computeFrameOffset(this.parent.ownerDocument.defaultView);

    // rectFrame.top = distacia da window atual em relacao ao top/CurrentWindow
    // rectParent.top = distancia do icone de ? em relacao a sua window
    // this.element.clientHeight - 5 = tamanho do help para ser colocado abaixo do icone
    var top = rectFrame.top + rectParent.top - this.element.clientHeight - 5;

    if (top < 0) {
      top = rectFrame.top + rectParent.top + rectParent.height + 5;
    }

    // rectFrame.left = distancia da window atual em relacao ao top/CurrentWindow
    // rectParent.left = distancia do icone de ? em relacao a sua window
    // thi.element.clientWidth/2 = metade do tamado do help a ser colocado acima do ?
    var left = rectFrame.left + rectParent.left - ( this.element.clientWidth/2 );

    if (left < 0) {
      left = 5;
    }

    // Caso o help ultrapasse as dimensoes da tela, ele eh ajustado para a esquerda.
    if ( (left + this.element.clientWidth) > CONTEXT.document.body.clientWidth) {
      left = CONTEXT.document.body.clientWidth - this.element.clientWidth - 5;
    }

    this.element.style.top = top + 'px';
    this.element.style.left = left  + 'px';
  };

  /**
   * Obtem a distancia da window informada ate o top/CurrentWindow
   */
  function computeFrameOffset(win, dims ) {
      dims = (typeof dims === 'undefined')?{ top: 0, left: 0}:dims;
      if (win !== CONTEXT) {
          var rect = win.frameElement.getBoundingClientRect();
          dims.left += rect.left;
          dims.top += rect.top;
          computeFrameOffset(win.parent, dims );
      }
      return dims;
  }

  Tooltip.prototype.hide = function() {
    this.element.style.display = 'none';
  };

})(DBViewHelpInline);

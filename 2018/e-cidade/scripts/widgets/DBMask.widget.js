var CONTEXT = this;

var DBMask = function(options) {

  this.settings = {
    zIndex: 0,
    miniMask: false,
    context: CONTEXT,
    fade: true
  }

  this.corpoMask = null;

  this.topoMask = null;

  this.bstatusMask = null;

  /**
   * jQuery.extend só que não
   */
  for (var prop in options) {
    if (this.settings.hasOwnProperty(prop)) {
      this.settings[prop] = options[prop]
    }
  }

  this.__init();

}

DBMask.prototype.__init = function() {

  var _this = this

  var oMask = _this.settings.context.document.createElement('DIV');

  var zIndex = _this.settings.zIndex || ( 10000 + (_this.settings.context.document.getElementsByClassName('db-mask').length * 2) );

  oMask.setAttribute('class', 'db-mask');
  oMask.style.width           = '100%';
  oMask.style.height          = '100%';
  oMask.style.position        = 'fixed';
  oMask.style.background      = 'url("imagens/ecidade/db-mask-bg.png")';
  oMask.style.left            = '0';
  oMask.style.top             = '0';
  oMask.style.zIndex          = zIndex;
  oMask.style.overflow        = 'auto';

  if (_this.settings.fade) {
    oMask.style.opacity       = '0';
    oMask.style.MozTransition = 'opacity 0.25s';
    oMask.style.webkitTransition = 'opacity 0.25s';
    oMask.style.transition = 'opacity 0.25s';
  }

  _this.oMasks = [];

  _this.insereMascaras(oMask);

  _this.oMaskElement = _this.corpoMask

  if (_this.settings.fade) {

    setTimeout(function() {

      if (_this.corpoMask) _this.corpoMask.style.opacity   = 1;
      if (_this.topoMask) _this.topoMask.style.opacity    = 1;
      if (_this.bstatusMask) _this.bstatusMask.style.opacity = 1;

    }, 50)
  }
}

/**
 * Método responsavel por colocar as mascaras na tela.
 */
DBMask.prototype.insereMascaras = function(oMaskBase) {

  var CurrentWindow = top;

  if (top.ECIDADE_DESKTOP) {
    CurrentWindow = parent.CurrentWindow;
  }

  if (this.settings.miniMask) {

    CurrentWindow.corpo.__DBMask = this.settings.context.document.importNode(oMaskBase, true);
    CurrentWindow.corpo.document.body.appendChild(CurrentWindow.corpo.__DBMask);

    this.corpoMask = CurrentWindow.corpo.__DBMask;
    this.oMasks.push(this.corpoMask);

  } else {

    if (CurrentWindow.corpo) {

      if (!CurrentWindow.corpo.__DBMask) {
        CurrentWindow.corpo.__DBMask = CurrentWindow.corpo.document.importNode(oMaskBase, true)
        CurrentWindow.corpo.document.body.appendChild(CurrentWindow.corpo.__DBMask);
      }

      this.corpoMask = CurrentWindow.corpo.__DBMask;
      this.oMasks.push(this.corpoMask)
    }

    if (CurrentWindow.topo) {

      if (!CurrentWindow.topo.__DBMask) {
        CurrentWindow.topo.__DBMask = CurrentWindow.topo.document.importNode(oMaskBase, true)
        CurrentWindow.topo.document.body.appendChild(CurrentWindow.topo.__DBMask);
      }

      this.topoMask = CurrentWindow.topo.__DBMask;
      this.oMasks.push(this.topoMask)
    }

    if (CurrentWindow.bstatus) {

      if (!CurrentWindow.bstatus.__DBMask) {
        CurrentWindow.bstatus.__DBMask = CurrentWindow.bstatus.document.importNode(oMaskBase, true)
        CurrentWindow.bstatus.document.body.appendChild(CurrentWindow.bstatus.__DBMask);
      }

      this.bstatusMask = CurrentWindow.bstatus.__DBMask;
      this.oMasks.push(this.bstatusMask)
    }

  }

}

/**
 * Método responsável por remover por completo os dados da Dialog.
 */
DBMask.prototype.destroy = function() {

  for (var indexMasks = 0; indexMasks < this.oMasks.length; indexMasks++ ) {

    if (this.oMasks[indexMasks].parentNode.ownerDocument.defaultView.__DBMask) {
      delete this.oMasks[indexMasks].parentNode.ownerDocument.defaultView.__DBMask;
    }

    this.oMasks[indexMasks].parentNode.removeChild(this.oMasks[indexMasks]);
    this.oMasks[indexMasks] = null;
  }

  this.oMasks = []
}

DBMask.prototype.getZIndex = function() {
  return this.oMasks[1].style.zIndex
}

DBMask.prototype.getMaskElement = function() {
  return this.oMaskElement;
}

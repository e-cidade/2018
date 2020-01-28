;(function($, global) {

  var KEY_ENTER = 13;
  var KEY_ESC = 27;
  var KEY_DOWN = 40;
  var KEY_UP = 38;
  var KEY_BACKSPACE = 8;
  var KEY_SPACE = 32;
  var KEY_DELETE = 46;

  var containerLoader = $('<div />', {'class': 'menu-search-loading'});

  var MenuSearch = function(input) {

    this.input = input;

    var options = {
      delay : 500,
      instit : null,
      onCreate: null,
      onSelect: this.onSelect,
      uri: '',
      background : !(typeof Worker == 'undefined'),
      containerMaxHeight: 0,
    };

    this.options = function(option, value) {

      // invalid
      if (!option)
        return;

      // merge
      if (typeof option == 'object')
        return options = $.extend(options, option);

      // get
      if (typeof option == 'string' && typeof value == 'undefined')
        return options[option];

      // set
      return options[option] = value;
    }

  }

  MenuSearch.build = function(input) {

    var search = new MenuSearch(input);
    search.container = $('<div>', {class : 'menu-search-results'});

    input.after(search.container);
    input.on('keydown', keydown);

    return search;
  }

  MenuSearch.prototype = {

    input : null,
    container: null,

    request : false,
    timeout : false,

    execute : function(delay) {

      /**
       * Cancela timeout anterior
       */
      if (this.timeout) {
        clearTimeout(this.timeout);
      }

      var _this = this;
      delay = typeof delay != 'undefined' ? delay : this.options('delay');

      if (delay > 0 && this.options('background')) {
        delay = 100;
      }

      this.timeout = setTimeout(function() {

        if (!$.trim(_this.input.val())) {
          return false;
        }

        _this.input.on('mousedownoutside', outside);

        if (!_this.options('instit')) {

          _this.container.empty().text('Instituição não selecionada.').show();
          return false;
        }

        if (!_this.container.is(":visible")) {
          _this.container.empty().append(containerLoader);
        }

        _this.container.show();
        _this.sendRequest();

      }, delay);
    },

    sendMessage : function() {

      if (this.background) {
        this.background.postMessage('abort');
      }

      if (!this.background)  {
        var _matchsHandler = matchsHandler.bind(this);
        this.background = new Worker(this.options('uri') + 'assets/vendors/jquery.menu-search/menu-search-worker.js');
        this.background.addEventListener('message', function(event) {
          _matchsHandler(event.data);
        }, false);
      }

      this.background.postMessage({
        term : this.input.val(),
        url: this.options('uri') + 'menu/getEstruturaMenu?instit='+ this.options('instit')
      });
    },

    sendRequest : function() {

      if (this.options('background')) {
        return this.sendMessage();
      }

      /**
       * Aborta conexao anterior
       */
      if (this.request && this.request.readystate != 4) {
        this.request.abort();
      }

      this.request = $.ajax({
        url: this.options('uri') + 'menu/search/',
        data: {term: this.input.val(), instit : this.options('instit')},
        dataType: 'JSON',
        type: 'GET',
        success: matchsHandler.bind(this)
      });
    },

    moveSelection : function(key) {

      var selectedNode = this.container.find('li.selected');
      var newSelectedNode = null;

      if (key === KEY_DOWN) {
        newSelectedNode = selectedNode.next();
      }
      else if (key === KEY_UP) {
        newSelectedNode = selectedNode.prev();
      }

      if (newSelectedNode.length == 0) {
        return false;
      }

      var scrollTop = false;
      var scrollPadding = 1;
      var container = this.container[0];
      var selectedElement = newSelectedNode[0];
      var offsetTop = selectedElement.offsetTop - container.scrollTop;

      if (key === KEY_UP && offsetTop < 0) {
        scrollTop = selectedElement.offsetTop - scrollPadding;
      }
      else if (key === KEY_DOWN && offsetTop + selectedElement.clientHeight > container.clientHeight) {
        scrollTop = selectedElement.offsetTop + scrollPadding + selectedElement.clientHeight - container.clientHeight;
      }

      if (scrollTop !== false) {
        container.scrollTop = scrollTop;
        this.container.scrollator('refresh');
      }

      this.toogleSelection(newSelectedNode, selectedNode);
    },

    toogleSelection : function(newSelectedNode, selectedNode) {

      selectedNode = selectedNode || this.container.find('li.selected');
      selectedNode.removeClass('selected');
      newSelectedNode.addClass('selected');
    },

    select : function(e) {
      if (typeof this.options('onSelect') == 'function') {
        return this.options('onSelect').call(this, e, this.container.find('li.selected'));
      }
    },

    onSelect: function(e, selected) {
      e.preventDefault();

      this.input.val(selected.text())
      this.container.hide()
    }

  }

  function matchsHandler(matchs) {

    var _this = this;
    var ul = $('<ul>');
    this.container.empty().append(ul);

    if (matchs.length == 0) {
      return this.container.append($('<li>').html('Nenhum registro encontrado'));
    }

    $.each(matchs, function(indice, match) {

      var li = $('<li>');

      if (indice == 0) {
        li.addClass('selected');
      }

      li.html(match.highlight);
      li.data('context', match.context);
      ul.append(li);
    });

    ul.find('li').on('click', function(event) {
      _this.select(event);
    });

    ul.find('li').on('mousemove', function(event) {
      _this.toogleSelection($(this));
    });

    if (this.options('containerMaxHeight')) {
      this.container.css('maxHeight', this.options('containerMaxHeight'));
    }

    if (typeof this.options('onCreate') == 'function') {
      this.options('onCreate').call(this);
    }

    this.container.scrollator({zIndex: 666});
    this.container.scrollator('refresh');
    this.container.scrollTop(0);
  }

  function keydown(event) {

    var keyCode = getKeyCode(event);

    if (keyCode == KEY_ENTER) {

      event.preventDefault();

      if (!this.search.container.is(":visible")) {
        return this.search.execute(0);
      }

      if (this.search.container.find('li.selected').length > 0) {
        return this.search.select(event);
      }
    }

    if (keyCode == KEY_ESC) {

      this.search.container.hide();
      this.search.input.val('');
      return false;
    }

    if (keyCode == KEY_DOWN || keyCode == KEY_UP) {
      event.preventDefault();
      return this.search.moveSelection(keyCode);
    }

    if (isAlphanumeric(String.fromCharCode(keyCode)) || keyCode == KEY_BACKSPACE || keyCode == KEY_SPACE || keyCode == KEY_DELETE) {
      this.search.execute();
    }
  }

  function getKeyCode(event) {
    event = event || window.event;
    return event.which || event.keyCode;
  }

  function isAlphanumeric(value) {
    return /[a-z0-9]/i.test(value);
  }

  function outside(event, target) {

    if (target) {
      var target = $(target);

      if ( target.closest(this.search.container).length > 0 || target.is(this.search.input) ) {
        return;
      }

      if (target.closest('.scrollator_lane_holder').length > 0) {
        return;
      }
    }

    $(this).off('mousedownoutside');
    this.search.container.hide();
  }

  /**
   * Cria o plugin
   * @param mixed options
   * @param mixed value
   */
  $.fn.menuSearch = function(options, value) {

    /**
     * get option
     */
    if (this.length == 1 && this.get(0).search && typeof option == 'string' && typeof value == 'undefined') {
      return this.get(0).search.options(options);
    }

    this.each(function(index, input) {

      if (!input.search) {
        input.search = MenuSearch.build($(this));
      }

      input.search.options(options, value);
    });

    return this;
  }

})(jQuery, this);

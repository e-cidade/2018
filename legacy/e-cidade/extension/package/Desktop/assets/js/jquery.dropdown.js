(function($) {

  var Dropdown = function(elem) {
    this.init(elem);
  };

  Dropdown.prototype = {

    dropdownToggle: null,

    dropdownMenu: null,

    active: false,

    init: function(elem) {

      this.element = elem;

      this.dropdownToggle = elem.find('.dropdown-toggle');
      this.dropdownMenu = elem.find('.dropdown-menu');

      var self = this;

      this.dropdownToggle.on('click', function(e) {
        self.toggle();
      });

      this.dropdownMenu.find('a').on('click', function() {
        self.hide();
      })

    },

    show: function() {

      this.dropdownMenu.fadeIn(100);
      var self = this;
      this.element.on('mousedownoutside', function(e) {

        var target = $(e.target);
        
        if (target.closest('#alertify').length || target.closest('#alertify-cover').length) return;

        self.hide()
      })

      this.active = true;

    },

    hide: function() {

      this.dropdownMenu.fadeOut(100);
      this.element.off('mousedownoutside')

      this.active = false;
    },

    toggle: function() {

      if (this.active) {
        this.hide();
      } else {
        this.show();
      }

    }
  }

  $.fn.dropdown = function() {

    this.each(function() {

      var self = $(this)

      if (self.data('dropdown')) return;

      var dropdown = new Dropdown(self);

      self.data('dropdown', dropdown);

    })

  }

  $(function() {
    $('[data-type="dropdown"]').dropdown();
  })

})(jQuery.noConflict());

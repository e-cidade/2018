(function($) {

  $(document).on('keydown', function(e) {
    
    if (e.keyCode == 191) {

      $('#menu').trigger('menu.toggle', [true]);
      $('#menu-search').trigger('focus');
      return false;
    }

    if (e.keyCode == 118) {

      var oParams = {
        action: 'debug.php',
        iInstitId: 1,
        iAreaId: 1,
        iModuloId: 1
      }

      Desktop.Window.create('Debug', oParams);
      $('#menu').trigger('menu.close');

      return false;
    }
  })

   $(window).on('app:window:create', function(event, win) {

      var reloadButton = win.addButton({
        "img": 'assets/vendors/window/ecidade/reload.png',
        "class": "window_button",
        "alt": 'Reload',
        "title": 'Reload',
      });

      reloadButton.on('click', function() {
        win.refresh();
      });

   });

   $('.taskbar-menu-button').text('CARDÁPIO')

})(jQuery);

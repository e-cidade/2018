/**
 * Eventos customizados disparados neste arquivo:
 *
 * app:
 * 
 * "app:start" = $() = $(document).ready()
 * "app:beforeunload" = window.onbeforeunload
 *
 * menu:
 * "menu.close"
 * 
 */

Object.extend(Windows, {
  maximize: function(id, event) {
    var win = this.getWindow(id)

    if (win && win.visible) {

      var img = $(id + '_maximize').querySelector('img');

      img.setAttribute('src', 'assets/vendors/window/ecidade/expandir.png');
      if ( win.isMaximized() ) {
        img.setAttribute('src', 'assets/vendors/window/ecidade/miximize.png');
      }
      win.maximize();
    }
    Event.stop(event);
  }
});

Object.extend(Window.prototype, {
  setTitle: function(newTitle) {
    if (!newTitle || newTitle == "")
      newTitle = "&nbsp;";

    Element.update(this.element.id + '_top', newTitle);

    if (this.taskbarButton) {
      this.taskbarButton.html(newTitle);
      window.Desktop.Taskbar.Windows.Menu.update();
    }
  }
})

;(function($) {

  $(function() {

    $(window).trigger('app:start')
    var menu = $('#menu');

    /**
     * fecha menu ao redimensionar janela
     */
    $(window).on('resize', function(event) {
      menu.trigger('menu.close');
    });

    /**
     * Evento global para dar blur na janela com focu
     */
    $(document).on('mousedown', function(event) {

      // nenhuma janela com focus
      if (Windows.focusedWindow == null) {
        return;
      }

      var target = $(event.target);

      // win
      if (target.closest('.dialog').length > 0) {
        return;
      }

      // menu
      if (target.closest('#menu').length > 0) {
        return;
      }

      // taskbar
      if (target.closest('.taskbar-container').length > 0) {
        return;
      }

      // topbar
      if (target.closest('.topbar').length > 0) {
        return;
      }

      // window blur
      Windows.focusedWindow.blur();
    });

    /**
     * EVENTO DO BOTÃO BLOCK
     * Bloqueia a sessão do usuário, impedindo-o de realizar ações até
     * entrar com sua senha novamente.
     */
    $('#block').on('click', function(e) {

      if ( jQuery('#blocked_session').length ) {
        return;
      }

      var options = {
        width: 450,
        height: 250,
        zIndex: (Windows.maxZIndex + 200),
      };

      $('.topbar .settings').removeClass('active');
      Desktop.Window.createModal("blocked_session", "Sessão bloqueada", "Window/block", options);

      if (Windows.focusedWindow) {
        Windows.focusedWindow.blur();
      }
    });

    /** TaskBar Events */

    $('.taskbar-buttons-modal .icon, .taskbar-buttons-modal .icon > span').on('click', function(e) {

      if ( !$(e.target).is($(this)) ) {
        return;
      }

      Desktop.Taskbar.Windows.Menu.toggle();
    });

    /** End Taskbar Events */

    /**
     * funcao utilizada para dar hits no servidor e
     * descobrir status do usuario no sistema
     */
    function pingCallback() {

      $.get('Window/ping/ ', function(response) {

        if (response == "blocked") {
          $('#block').triggerHandler('click');
          return;
        }

        if (response == 'dead') {

          alertify.alert('Sua sessão expirou, favor fazer login novamente.', function() {
            window.close();
          });
        }
      }, 'text');

    }

    pingCallback();

    /**
     * EVENTO DO BOTÃO LOGOUT
     * Envia requisição para matar a sessão no servidor e fecha a window
     */
    $('#logout').on('click', function(e) {
      e.preventDefault();

      var _this = this;

      alertify.confirm('Tem certeza que deseja fazer logout?', function(ok) {

        if (ok) {

          $.get($(_this).attr('href'), function(response) {

            if (response.session == 'dead') {
              window.close();
            }
          }, 'json');

        }

      });

    });

    /**
     * Evento do botão fallback
     * Retira o usuário do modo de visualização do e-cidade 3.0
     */
    $('#fallback').on('click', function(e) {

      e.preventDefault();
      var path = $(this).attr('href');

      alertify.confirm('Voltar para versão antiga?', function(resposta) {

        if (!resposta) {
          return false;
        }

        var loader = window.loader || new Desktop.Loader(window.document.body);
        loader.message.add({id: 'fallback', html : 'Trocando versão...'});
        loader.show();

        $.get(path, function(login) {

          var sizeWidth  = screen.availWidth;
          var sizeHeight = screen.availHeight;
          var inicio = 'inicio.php?uso='+login+'&janelaWidth='+sizeWidth+'&janelaHeight='+sizeHeight;
          window.document.location.href = ECIDADE_REQUEST_PATH + inicio;

        }, 'json')
        .fail(function(xhr) {
          
          loader.message.hide();
          loader.hide();
          var message = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : xhr.responseText;
          alertify.log(message);
        });

      });

    });

    /**
     * identifica inatividade do usuário no sistema
     */
    ;(function($) {

      var timer = new Desktop.Timer('session.block', 1200000, function(event) {
        $('#block').triggerHandler('click');
      });

      $(window).on('mousemove mousedown keydown', function(event) {
        timer.restart();
      });

    })(jQuery);

    function launchIntoFullscreen(element) {
      if(element.requestFullscreen) {
        element.requestFullscreen();
      } else if(element.mozRequestFullScreen) {
        element.mozRequestFullScreen();
      } else if(element.webkitRequestFullscreen) {
        element.webkitRequestFullscreen();
      }
    }

    function exitFullscreen() {
      if(document.exitFullscreen) {
        document.exitFullscreen();
      } else if(document.mozCancelFullScreen) {
        document.mozCancelFullScreen();
      } else if(document.webkitExitFullscreen) {
        document.webkitExitFullscreen();
      }
    }

    $('#fullscreen').on('click', function(event) {

      event.preventDefault();

      var inFullScreen = $(this).data('in-full-screen');

      if (inFullScreen) {
        return exitFullscreen(document.documentElement);
      }

      launchIntoFullscreen(document.documentElement);
    });

    $(document).on('fullscreenchange webkitfullscreenchange mozfullscreenchange', function(event) {

      var element = $('#fullscreen');
      var inFullScreen = !element.data('in-full-screen');
      element.data('in-full-screen', inFullScreen);

      if (!inFullScreen) {
        return element.text('Usar tela cheia');
      }

      element.text('Sair do modo tela cheia');
    });

    var $menu = $('#menu');

    $menu.on('load.area', function(event, instit) {

      /**
       * Define id da instituicao, para usar na pesquida dos menus
       */
      $('#menu-search').menuSearch('instit', instit).each(function(index, input) {
        if (input.search.options('background')) {
          input.search.sendMessage();
        }
      }); 

      exibeBases(instit);
    });

    function exibeBases(instit) {

      instit = instit || $('#instituicoes span.active').data('id');

      /**
       * Troca de base
       */
      $.getJSON('desktop/getBases?instit='+instit, function(response) {

        var baseAtual = response.atual;
        var nodeBaseName = $('.dropdown-menu .profile .system-info .base-name');
        var parent = nodeBaseName.parent();
        var select = $('<select>', {class: 'bases'});

        // remove select anterior, caso exista
        parent.find('select.bases').remove();

        // usuario sem permissao para alterar base
        if (!response.acesso) {
          return nodeBaseName.show();
        }

        // esconde tag com nome da base para exibir select
        nodeBaseName.hide();

        for (var index = 0, total = response.bases.length; index < total; index++) {

          var base = response.bases[index];
          select.append($('<option>', {value: base, text: base, selected : (base == baseAtual)}));
        }

        select.on('change', function(event) {

          var select = $(this);

          alertify.confirm('Deseja trocar de base?<br />As janelas abertas serão fechadas.', function(change) {

            if (!change) {
              return select.val(baseAtual);
            }

            var loader = window.loader || new Desktop.Loader(window.document.body);
            loader.message.add({id: 'fallback', html : 'Trocando base...'});
            loader.show();

            Desktop.Session.updateGlobal({"DB_NBASE": select.val()}, function(error) {

              if (error) {
                select.val(baseAtual);
                return console.error(error);
              }

              window.document.location.href = window.document.location.href; 
            });

          });
        });

        parent.append(select);
      });
    }

  });

})(jQuery);

window.onbeforeunload = function() {

  jQuery(window).trigger('app:beforeunload');
  return;
}

// Redefine os labels do alertify
alertify.set({
  labels: {
    ok: 'Ok',
    cancel: 'Cancelar'
  }
});

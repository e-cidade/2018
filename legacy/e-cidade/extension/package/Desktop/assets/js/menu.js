/**
 * Eventos customizados disparados neste arquivo:
 *
 * menu:
 *
 * "menu.close" = Disparado quando eh solicitado que o menu feche
 * "menu.toggle" = Disparado quando eh solicitado que o menu abra/feche
 *
 * "load.instituicao" = Disparado quando eh solicitado para buscar as insituicoes
 * "load.area" = Disparado quando eh solicitado para buscar as areas
 * "load.modulo" = Disparado quando eh solicitado para buscar os modulos
 * "load.itens" = Disparado quando eh solicitado para buscar os itens de menu
 */

;(function($){

  $(function($) {

    // Menu Events
    var $menu = $('#menu');

    // Busca
    $('#menu-search').menuSearch({
      uri: top.document.baseURI,
      containerBounds: $('.taskbar-container'),
      onCreate: function() {

        var height = $menu.height() - $('.taskbar-container').height();
        this.container.css('maxHeight', height);
      },
      onSelect : function(e, selectedNode) {

        var context = selectedNode.data('context');
        var path = context.breadcrumb.split('>').reverse().slice(0, 2).reverse().join('>');

        this.input.val('');
        this.container.hide();

        var oParams = {
          action: context.action,
          iInstitId: this.options('instit'),
          iAreaId: context.area,
          iModuloId: context.modulo
        }

        Desktop.Window.create(path, oParams);
        $('#menu').trigger('menu.close');
      }
    });

    $('.taskbar-menu-button').on('click', function(e) {
        $('#menu').trigger('menu.toggle');
    });

    // Botão para fechar o menu
    $menu.find('.menu-close span').on('click', function(){
      $menu.trigger('menu.close')
    });

    // Eventos de fechar e abrir o menu.
    $menu.on('menu.toggle', function(e, force) {

      $('.menu-list').scrollator("hide");
      $menu.toggleClass('active', force);

      $menu.off('mousedownoutside');

      $menu.css('bottom', '');
      if ( !$menu.hasClass('active')  ) {
        $menu.css('bottom', '-' + ($menu.height() + 10) + 'px' );
      } else {

        $menu.on('mousedownoutside', function(e, target) {

          var target = $(e.target);

          if ( target.closest('.scrollator_lane_holder').length > 0 || target.is('.taskbar-menu-button') ) {
            return;
          }

          $menu.trigger('menu.close');
        });
      }

    }).on('menu.close', function() {
      $(this).trigger('menu.toggle', [false]);
    })

    // Ao clicar no icone home, move o menu para o inicio (caso esteja pela direita)
    $menu.find('.menu-action-home').on('click', function() {

      $('.menu-list-container').animate({left: '-20px'}, 'fast', function() {

        $menu.find('.menu-hidden').removeClass('menu-hidden');
        $menu.find('.menu-pager-left').addClass('disabled');
        $menu.find('.menu-pager-right').removeClass('disabled');
        $(this).find('.menu-list').scrollator('refresh');
      })

    });

    // Ao mover o conteudo do menu, sumir/mostrar os scrolls
    $menu.on('transitionend', function() {
        $('.menu-list-container').find('.menu-list').scrollator('refresh');
    });

    // Aplica o plugin scrollator nas listas do menu
    $('.menu-list').scrollator({zIndex: 101});

    // Move o conteudo do menu para a direita com animação.
    $('.menu-pager-right').on('click', function(e, iForceLeft) {

      if ($(this).hasClass('disabled') || $('.menu-list-container:animated').length > 0) {
        return;
      }

      var $menuHidden = $('.menu-list-container:not(.menu-hidden):first');

      if (!$menuHidden.length) {
        return;
      }

      $('.menu-pager-left').removeClass('disabled');

      var actLeft = 0;
      $('.menu-list-container.menu-hidden:first').each(function() {
        actLeft = $(this).position().left;
      });

      $('.menu-list-container').animate({
        left: iForceLeft ? iForceLeft : (actLeft - $menuHidden.outerWidth() )
      }, 'fast', function() {

        $menuHidden.addClass("menu-hidden");
        Scrollator.refreshAll();

        if ( $('.menu-list-container:not(.menu-hidden)').length == 1 ) {
          $(e.currentTarget).addClass('disabled');
        }

      });

    });

    // Move o conteudo do menu para a esquerda com animação.
    $('.menu-pager-left').on('click', function(e) {

      if ($(this).hasClass('disabled') || $('.menu-list-container:animated').length > 0) {
        return;
      }

      $('.menu-pager-right').removeClass('disabled');

      var $menuHidden = $('.menu-list-container.menu-hidden:last');
      var menuLeft = 0;

      $menuHidden.each(function() {
        menuLeft = $menuHidden.position().left;
      })

      var actLeft = 0;

      $('.menu-list-container.menu-hidden:first').each(function() {
        actLeft = $(this).position().left;
      })

      $('.menu-list-container').animate({
        left: (actLeft - menuLeft - 20) + "px"
      }, 'fast', function() {

        $menuHidden.removeClass("menu-hidden")
        $(this).find('.menu-list').scrollator('refresh');

        if ( $('.menu-hidden').length == 0 ) {
          $(e.currentTarget).addClass('disabled');
        }

        // Scrollator.refreshAll();

      }).trigger('click');

    });

    // Acao ao clicar em um item do menu
    $menu.find('.menu-content').on('click', '.menu-list-container .menu-list span', function() {

      var $self = $(this);
      var data = $self.data('menu.filhos');

      // Remove
      $self.closest('.menu-list-container').nextAll('.menu-itens').remove();

      // Se trocar de menu faz a busca dos filhos
      if (data) {

        if (data.filhos) {

          createNewList(data.filhos);

        } else if (data.action) {

          var title = $('.menu-list-container:last').prev().find('span.active').text();
          title += ' > ' + data.nome;

          var oParams = {
            action: data.action,
            iInstitId: $('#instituicoes span.active').data('id'),
            iAreaId: $('#areas span.active').data('id'),
            iModuloId: $('#modulos span.active').data('id')
          }

          Desktop.Window.create(title, oParams);
          $menu.trigger('menu.close');
        }

      }

      $self.closest('.menu-list-container').find('.active').removeClass('active');
      $self.addClass('active');

      // Senão desmarca todas as listas a sua direita
      $self.closest('.menu-list-container')
           .nextAll('.menu-list-container')
           .find('span.active').removeClass('active')


      var $menuBreadcrumb = $('#menu-breadcrumb');
      var textMenuBreadCrumb = '';

      $('.menu-list-container span.active').each(function(index) {
        if (index > 0) textMenuBreadCrumb += ' > ';
        textMenuBreadCrumb += $(this).text();
      });

      $menuBreadcrumb.empty();
      $('<li />', {text: textMenuBreadCrumb}).appendTo($menuBreadcrumb);

      $('.menu-list-container').find('.menu-list').scrollator('refresh');
    });

    $menu.find('.menu-resizer').on('mousedown', function(e) {

      var iResizeMouseY = e.clientY;

      if (e.which != 1) {
        return;
      }

      $('.menu-list').scrollator('hide');

      $('<div />', {
          id: 'resize-ghost'
        }).css({
          position: 'absolute',
          top: iResizeMouseY,
          width: '100%',
          height: '0px',
          backgroundColor: 'rgba(255,255,255,0.5)',
          border: '1px dotted #000',
          zIndex: $menu.css('zIndex') + 1
        }).appendTo('body')

      if (window['WindowUtilities']) {
        WindowUtilities.disableFrames();
      }

      $(document)
        .on('mousemove', resizeMenu)
        .on('mouseup', endResizeMenu);

      function resizeMenu(e) {

        iResizeMouseY = e.clientY;
        $('#resize-ghost').css('top', iResizeMouseY)

      }

      function endResizeMenu(){

        localStorage["Menu"] = true;

        var iHeightOld = $('#menu').height(),
            newHeight = $(window).height() - iResizeMouseY - 40;
        $('#menu').height( newHeight );

        localStorage["Menu.height"] = newHeight;

        $menuContent = $('#menu .menu-content');

        var newContentHeight = $menuContent.height() +  ( $('#menu').height() - iHeightOld );
        $menuContent.height( newContentHeight );

        localStorage["Menu.content.height"] = newContentHeight;

        $('#resize-ghost').remove()
        lResizeStarted = false;

        $('.menu-list').scrollator('refresh');

        if (window['WindowUtilities']) {
          WindowUtilities.enableFrames();
        }

        $(document)
          .off('mousemove', resizeMenu)
          .off('mouseup', endResizeMenu)
      }

    });

    var timeoutRoll;
    $menu.find('#menu-search').on('keydown', function(e) {
      if (e.keyCode == 13) {
        if (this.value == 'do a barrel roll') {
          $('body').addClass('roll')
        }
      }
    })

    $menu.on('load.instituicao', function() {

      $('#instituicoes').empty().append(getDivLoading());

      $.getJSON('Menu/getInstituicoes/', function(data) {

        $('#instituicoes').empty();

        $.each(data, function(iKey, oInstit) {

          var oSpanInstit = $('<span />', {
            text: oInstit.nome,
            'class': 'instituicao_' + oInstit.tipo_instit
          });

          oSpanInstit.data('id', oInstit.id);

          $('#instituicoes').append(oSpanInstit);

        });

        $('#instituicoes span:first').trigger('click');

      });

    }).trigger('load.instituicao');

    $menu.on('load.area', function(event, iInstitId) {

      $('#areas').empty().append(getDivLoading());
      $('#modulos').empty();

      $.getJSON('Menu/getAreas/?iInstitId=' + iInstitId, function(data) {

        $('#areas').empty();

        $.each(data, function(iKey, oArea) {

          var oSpanArea = $('<span />', {
            text: oArea.nome,
            'class': 'area_' + oArea.id
          });

          oSpanArea.data('id', oArea.id);

          $('#areas').append(oSpanArea);

        });

      });

    });

    $menu.on('load.modulo', function(event, iInstitId, iAreaId) {

      $('#modulos').empty().append(getDivLoading());

      var oParams = {
        iInstitId: iInstitId,
        iAreaId: iAreaId
      };

      $.ajax({
        url: 'Menu/getModulos/',
        data: oParams,
        dataType: 'JSON',
        type: 'GET',
        success: function(data) {

          $('#modulos').empty();

          $.each(data, function(iKey, oModulo) {

            var oSpanModulo = $('<span />', {
              text: oModulo.nome,
              'class': 'modulo_' + oModulo.id
            });

            oSpanModulo.data('id', oModulo.id)

            $('#modulos').append(oSpanModulo);

          });

        }
      });

    });

    var xhrLoadItens = null;

    $menu.on('load.itens', function(event, iInstitId, iAreaId, iModuloId) {

      if (xhrLoadItens) {
        return;
      }

      $('#modulos').parent().nextAll('.menu-list-container').remove();

      var oListLoading = createNewList([], true);

      oListLoading.find('.menu-list').append(getDivLoading())

      var oParams = {
        iInstitId: iInstitId,
        iAreaId: iAreaId,
        iModuloId: iModuloId
      };

      xhrLoadItens = $.ajax({
        url: 'Menu/getItensMenu/',
        data: oParams,
        dataType: 'JSON',
        type: 'GET',
        success: function(data) {

          $('#modulos').parent().nextAll('.menu-list-container').remove();
          createNewList(data);

          xhrLoadItens = null;
        }
      })

    })

    function createNewList(data, notAnItem) {

      var oList = $('<div />', {
        'class': 'menu-list-container divider-left ' + (notAnItem ? '' : 'menu-itens')
      });

      oList.append($('<div />', {'class': 'menu-list-title'}));
      oList.append($('<div />', {'class': 'menu-list'}))

      oMenuList = oList.find('.menu-list');

      $.each(data, function(iKey, oMenu) {

        var oSpan = $('<span />', {
          text: oMenu.nome,
          'class': oMenu.action ? 'action' : '',
          'id': 'menu_id_' + oMenu.id
        });

        // Menu sem filho e sem action
        if (oMenu && !oMenu.filhos && !oMenu.action) {
          oSpan.addClass('disabled');
        }

        if (oMenu.filhos) {
          oSpan.append($('<i />', {
            'class': 'arrow'
          }))
        }

        oMenuList.append(oSpan);
        oSpan.data('menu.filhos', oMenu);
      })
      $('.menu-pager-right').before(oList);
      oMenuList.scrollator({zIndex: 101});
      oMenuList.scrollator('refresh');

      if ( $('.menu-hidden').length ) {
        oList.css('left', $('.menu-list-container:last').prev().css('left') );
      }

      if (!notAnItem) {
        slideListToView(oList);
      }

      return oList;
    }

    function slideListToView(oList) {

      if ( !isListVisible(oList) ) {

        var oLastVisibleList = null;
        oList.prevAll().each(function (iKey, oPrevList) {

          if (oLastVisibleList == null && isListVisible($(oPrevList))) {
            oLastVisibleList = $(oPrevList);
          }

        });

        var iSizeLeft = oList.position().left + oList.outerWidth();
        iSizeLeft -= oLastVisibleList.position().left + oLastVisibleList.outerWidth();

        var iActLeft = $('.menu-list-container:first').position().left;

        var iLeft = (iActLeft - iSizeLeft);
        $('.menu-pager-right').trigger('click', [iLeft])
      }

    }

    function isListVisible(oList) {

      var oAnterior = oList.prev(),
          iMaxWidth = $('.menu-content').width();
          iWidthAct = oAnterior.position().left + oAnterior.outerWidth();

      var iAvailWidth = iMaxWidth - iWidthAct;

      return oList.outerWidth() < iAvailWidth;
    }

    $('#instituicoes').on('click', 'span', function() {
      $menu.trigger('load.area', [$(this).data('id')])
    });

    $('#areas').on('click', 'span', function() {

      var iInstitId = $('#instituicoes span.active').data('id'),
          iAreaId   = $(this).data('id');

      $menu.trigger('load.modulo', [iInstitId, iAreaId])
    });

    $('#modulos').on('click', 'span', function() {

      var iInstitId = $('#instituicoes span.active').data('id'),
          iAreaId   = $('#areas span.active').data('id'),
          iModuloId   = $(this).data('id');

      $menu.trigger('load.itens', [iInstitId, iAreaId, iModuloId]);
    })

    if (localStorage["Menu"]) {

      $menu.height(localStorage["Menu.height"])
      $menu.find('.menu-content').height(localStorage["Menu.content.height"]);
    }

  });

  function getDivLoading() {

    var oLoading = $('<div />', {
      'class': 'menu-loading'
    });

    return oLoading;
  }

})(jQuery);

<html>
<head>
  <style type="text/css">
  * {
    margin: 0;
    padding: 0;
    border: 0px;
  }

  iframe {
    width: 100%;
    height: 100%;
  }

  html, body, #sistema {
    width: 100%;
    height: 100%;
    position:absolute;
    top:0;
    left:0;
  }

  .quadro {
    width: 100%;
  }

  #quadro-corpo {
    height: -moz-calc(100% - 20px);
    height: -webkit-calc(100% - 20px);
    height: calc(100% - 20px);
  }

  #quadro-status {
    height: 20px;
  }

  #alertify-logs {
    bottom: 22px;
  }

  .alertify-log-alert {
    background: #ffffcc !important;
    color: #545454 !important;
    text-shadow: none !important;
  }
  </style>
  <link type="text/css" rel="stylesheet" href="assets/vendors/alertify/themes/alertify.core.css" />
  <link type="text/css" rel="stylesheet" href="assets/vendors/alertify/themes/alertify.bootstrap.css" />

</head>
<body>

    <div id="sistema">

      <div id="quadro-corpo" class="quadro">
        <iframe src="" name="corpo" scrolling="auto" id="corpo"></iframe>
      </div>

      <div id="quadro-status" class="quadro">
        <iframe src="" name="bstatus" scrolling="no" id="bstatus"></iframe>
      </div>

    </div>

</body>

<script type="text/javascript" src='assets/vendors/alertify/alertify.js'></script>
<script type="text/javascript" src="scripts/jquery-2.1.1.min.js"></script>

<script type="text/javascript">
var Desktop = parent.Desktop, CurrentWindow, Window, corpo, bstatus;

(function($, global) {

  if (!parent.Windows) {
    throw new Error('Janela não encontrada');
  }

  var windowId = 'w<?php echo $this->window->id(); ?>';
  var ECIDADE_REQUEST_PATH = '<?php echo ECIDADE_REQUEST_PATH . '/w/' . $this->window->id() . '/'; ?>';

  // usuario alterou data do sistema, exibe janela de settings para usuario escolhar data do sistema
  var dateUser = <?php echo $this->dateUser ? 'true' : 'false'; ?>;

  // data do sistema esta diferente da data do servidor, exibe aviso
  var dateSystemDiffServer = <?php echo $this->dateSystemDiffServer ? 'true' : 'false'; ?>;

  // mensagem de aviso ja exibida
  var notificationDateSystemDiffServer = false;

  // ultima url, usado para verifiar se deve alterar o titulo da janela
  var lastUrl = null;

  var browser = {
    mozilla : /mozilla/.test(navigator.userAgent.toLowerCase()) && !/webkit/.test(navigator.userAgent.toLowerCase()),
    webkit : /webkit/.test(navigator.userAgent.toLowerCase()),
  }

  Windows = parent.Windows;
  CurrentWindow = Windows.getWindow(windowId);
  corpo = document.getElementById('corpo').contentWindow;
  bstatus = document.getElementById('bstatus').contentWindow;

  CurrentWindow.corpo = corpo;
  CurrentWindow.bstatus = bstatus;
  CurrentWindow.fixSize = {divIframe : {}, windowAux : {}, camada : {}, divCarregando : {}};
  CurrentWindow.ECIDADE_REQUEST_PATH = ECIDADE_REQUEST_PATH;
  CurrentWindow.ECIDADE_DESKTOP = true;

  CurrentWindow._eventHandler =  CurrentWindow.eventHandler.bind(CurrentWindow);

  CurrentWindow.createLoading = function(iframe, parentNode) {

    // loader ja registrado
    if (iframe.loader) {
      return false;
    }

    // usa loader da CurrentWindow
    if (!parentNode) {
      iframe.loader = CurrentWindow.loader;
    }

    // cria loader
    else {

      if (!parentNode.style || !parentNode.style.position) {
        parentNode.style.position = 'relative';
      }

      iframe.loader = new Desktop.Loader(parentNode);
    }

    iframe.addEventListener('load', function(event) {

      iframe.loader.hide();
      iframe.contentWindow.addEventListener('beforeunload', function(event) {
        iframe.loader.show();
      });

      iframe.contentWindow.addEventListener('unload', function(event) {
        iframe.loader.message.clear();
      });

    });

    return iframe.contentWindow.document.readyState == 'complete' ? iframe.loader.hide() : iframe.loader.show();
  }

  /**
    * verifica se url foi alterada
    * - altera titulo, caso nova url for um menu
    * - valida permissao para novo menu
   */
  function checkUrl() {

    current = CurrentWindow.corpo.document.location.pathname + CurrentWindow.corpo.document.location.search;

    // url diferente
    if (lastUrl != current) {

      lastUrl = current;
      $.getJSON(ECIDADE_REQUEST_PATH + 'extension/desktop/menu/getMenuArquivo/?file=' + current, function(data) {

        // erro ao buscar menu ou arquivo nao eh um menu
        if (!data || !data.breadcrumb) {
          return;
        }

        // altera titulo da janela
        var title = data.breadcrumb.split('>').reverse().slice(0, 2).reverse().join('>');
        CurrentWindow.setTitle(title);

        // sem permissao para o menu
        if (data.permission === false) {

          CurrentWindow.loader.show();

          var params = $.param({db_erro: 'Sem permissão para acessar está rotina.'});
          var url = ECIDADE_REQUEST_PATH + 'db_erros.php?' + params;
          CurrentWindow.content.contentWindow.document.location.href = url;
        }

      });
    }
  }

  /**
   * Exibe aviso quando data do sistema esta diferente com a do servidor
   */
  function checkSystemDate() {

    if (!dateSystemDiffServer || notificationDateSystemDiffServer) {
      return false;
    }

    notificationDateSystemDiffServer = true;
    alertify.log('Data ou exercício do sistema está diferente do servidor.', "alert", 0, function() {
      notificationDateSystemDiffServer = false;
    });
  }

  // ao dar focu na janela, verifica se deve exibir aviso de data diferente
  CurrentWindow.addObserver('onFocus', function() {
    checkSystemDate();
  });

  // @todo mover funcao para window.js, usar em todos os iframes filhos do iframe da window, ex: corpo e bstatus
  function inject(frame) {

    frame.CurrentWindow = CurrentWindow;
    frame.Desktop = Desktop;
    frame.Windows = Windows;
    frame.document.addEventListener('mousedown', CurrentWindow._eventHandler)
  }

  if (browser.webkit) {
    $('#corpo').css({visibility: 'hidden'});
  }

  $('#bstatus').on('load', function() {
    inject(bstatus);
  });

  $('#corpo').on('load', function() {

    inject(corpo);
    CurrentWindow.loader.hide();

    checkSystemDate();
    checkUrl();

    if (dateUser) {
      dateUser = false;
      Desktop.Window.createSettingModal(CurrentWindow);
    }

    if (browser.webkit) {

      $('#corpo').css({visibility: 'visible'});
      corpo.addEventListener('unload', function(event) {
        $('#corpo').css({visibility: 'hidden'});
      });
    }

    corpo.addEventListener('beforeunload', function(event) {
      CurrentWindow.loader.show();
    });

  });

  ;(function(global) {

    var notifyCount = 0;
    var lastMessage = null;
    var _labels = Object.create(alertify.labels);

    CurrentWindow.alert = function(message, done, label) {

      var message = message.replace(/\n/g, '<br />');

      if (message == lastMessage) {
        return;
      }

      notifyCount++;
      lastMessage = message;
      var taskbarButton = CurrentWindow.taskbarButton;

      alertify.set({labels: _labels});
      if (label) {
        alertify.set({labels: {ok : label}});
      }

      alertify.alert(message, function() {

        notifyCount = 0;
        lastMessage = null;
        taskbarButton.find('.notify').remove();

        if (done) {
          done.call(CurrentWindow);
        }
      });

      if (CurrentWindow != Windows.getFocusedWindow()) {

        messageLog = '<div style="border-bottom:1px solid #666;margin-bottom:5px;float:left;width:100%;">';
        messageLog += CurrentWindow.getTitle() + "</div>" + message;

        parent.alertify.log(messageLog, "", 0);

        taskbarButton.find('.notify').remove();
        var taskButtonNotify = $('<div>', {class: 'notify'});
        taskButtonNotify.text(notifyCount);
        taskbarButton.append(taskButtonNotify);
      }
    }

    CurrentWindow.confirm = function(message, callback, labels) {

      alertify.set({labels: _labels});
      if (labels) {
        alertify.set({labels: labels});
      }
      alertify.confirm(message, callback);
      return true;
    }

  })(this);

  $(function() {

    // @todo - mudar os for para callbacks: CurrentWindow._fix['windowAux'].push(fn);
    window.addEventListener('resize', function() {

      /**
       * windowAux
       */
      for (var id in CurrentWindow.fixSize.windowAux) {

        var windowAux = CurrentWindow.fixSize.windowAux[id];
        windowAux.style.left = (CurrentWindow.corpo.innerWidth - $(windowAux).width())/2;
      }

      /**
       * js_OpenJanelaIframe
       */
      for (var id in CurrentWindow.fixSize.divIframe) {

        var divIframe = CurrentWindow.fixSize.divIframe[id];
        divIframe.style.top = CurrentWindow.corpo.scrollY + 'px';
        divIframe.style.left = CurrentWindow.corpo.scrollX + 10 + 'px';
        divIframe.style.width = (CurrentWindow.corpo.innerWidth - divIframe._fix.width)+'px';
        divIframe.style.height = (CurrentWindow.corpo.innerHeight - divIframe._fix.height)+'px';
      }

      /**
       * cl_criaabas
       */
      for (var id in CurrentWindow.fixSize.camada) {

        var camada = CurrentWindow.fixSize.camada[id];
        camada.style.width = CurrentWindow.corpo.innerWidth +'px';
        camada.style.height = (CurrentWindow.corpo.innerHeight - 44)+'px';
      }

      /**
       * js_divCarregando
       */
      for (var id in CurrentWindow.fixSize.divCarregando) {

        var divCarregando = CurrentWindow.fixSize.divCarregando[id];
        divCarregando.style.left = ((CurrentWindow.corpo.innerWidth / 2) - 120 ) +'px';
        divCarregando.style.top = ((CurrentWindow.corpo.innerHeight / 2) - 50) +'px';
      }
    });

    document.getElementById('corpo').addEventListener('load', function() {
      CurrentWindow._notify('onAppLoaded');
    })

    document.getElementById('corpo').src = '<?php echo $this->pathBody; ?>';
    document.getElementById('bstatus').src = '<?php echo $this->pathStatus; ?>';

  });

})(jQuery, this);
</script>
</html>

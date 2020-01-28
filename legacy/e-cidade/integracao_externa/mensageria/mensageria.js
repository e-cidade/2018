(function() {

  'use strict';

  /**
   * Processa dependencias
   * - carrega scripts da mensageria e depois busca login e sistema para exibir as notificacoes
   */
  function processarDependencias() {
  
    /**
     * Requere arquivo de mensageria
     */
    require_once('integracao_externa/mensageria/DBSeller/Mensageria/View/js/mensageria.js');

    /**
     * Configura caminho da mensageria e carrega as suas dependencias
     */
    Mensageria.config({path: 'integracao_externa/mensageria/'});

    /**
     * Registra funcao para ser chamada quando tiver algum erro
     */
    Mensageria.on('error', function(sErro) {    
      console.log('MENSAGERIA ERRO: ' + sErro);
    }); 

    buscarLoginSistema();
  } 

  /**
   * Busca dados do usuario logado
   */
  function buscarLoginSistema() {

    /**
     * Caso pagina não tenha carregado scripts/strings.js
     */
    if (!new String().hasOwnProperty('urlDecode')) {
      String.prototype.urlDecode = function() {
        return unescape(this.replace(/\+/g," "));
      }
    }

    /**
     * Prototype nao adicionanada na pagina atual
     */
    if (typeof($) == 'undefined') {
      require_once('scripts/prototype.js');
    }

    /**
     * Parametros da requisicao
     * @type {Object}
     */
    var oParametros = {
      method: 'post',
      parameters: 'json=' + js_objectToJson({exec : 'getUsuarioSistema'}),
      onComplete: function(oAjax) {
         
        var oRetorno = eval("("+oAjax.responseText+")");
        var sMensagem = oRetorno.sMensagem.urlDecode();

        /**
         * Erro no RPC
         */
        if (oRetorno.lErro) {
          return Mensageria.trigger('error', sMensagem);
        }

        /**
         * Inicia notificacao, define usuario e senha e renderiza no container #mensageria-notificacoes
         */
        var notificacoes = new Mensageria.Notificacoes(oRetorno.sLogin, oRetorno.sSistema);
        notificacoes.render('#mensageria-notificacoes');  
      }
    }

    var oRequisicao = new Ajax.Request('con4_mensageria.RPC.php', oParametros); 
  }

  /**
   * Aguarda documento estar carregado, caso nao esteja, registra evento
   */
  if (document.readyState === 'interactive' || document.readyState === 'complete') {
    return processarDependencias();
  }

  if (!!(window.addEventListener)) {
    return document.addEventListener("DOMContentLoaded", processarDependencias, false);
  }

  // MSIE
  document.attachEvent("onload", processarDependencias);

})();

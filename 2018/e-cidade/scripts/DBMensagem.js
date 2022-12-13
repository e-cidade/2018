require_once('scripts/arrays.js');
require_once('scripts/strings.js');

/**
 * Classe para emissão de mensagens do sistema
 * Sempre existira apenas uma instancia da classe 
 * @constructor
 * @returns {DBMensagem}
 */
DBMensagem = function ()  {
  
  if (DBMensagem.prototype._oInstance ) {
    return DBMensagem.prototype._oInstance;
  }

  DBMensagem.prototype._oInstance = this;
};

/**
 * Retorna o nome do arquivo da mensagem passada como parametro 
 * @param  {string} sCaminhoMensagem Caminho da mensagem a ser executada
 * @private
 * @returns {string} nome do arquivo
 */
DBMensagem.prototype.getNomeArquivo = function(sCaminhoMensagem) {
  
  var aPartesMensagem = sCaminhoMensagem.split(".");
  var sArquivo        = aPartesMensagem.splice(0, aPartesMensagem.length - 1).implode("/");
  return sArquivo;
  
};

/**
 * Retorna o nome da mensagem passada no paramentro
 * @private
 * @param sCaminhoMensagem caminho da mensagem
 * @returns Nome da mensagem do caminho
 */
DBMensagem.prototype.getNomeMensagem = function(sCaminhoMensagem) {
  
  var aPartesMensagem = sCaminhoMensagem.split(".");
  var sNomeMensagem   = aPartesMensagem[aPartesMensagem.length - 1];
  return sNomeMensagem;
  
};

/**
 * Retorna o arquivo da mensagem, e coloca o mesmo no cache de requisições
 * @param {string} sArquivoMensagem Arquivo da mensagem
 * @private
 * @returns o arquivo
 */
DBMensagem.prototype.getArquivo  = function (sArquivoMensagem) {
  
  var sArquivo = this.getNomeArquivo(sArquivoMensagem)+".json";

  this.sArquivoAtual = sArquivo;

  if (!__Requisicoes__[sArquivo]) {
    
    var oRequisicao = {exec:'getFile', file:sArquivo};
    new Ajax.Request('con4_mensagems.RPC.php', 
                     {
                        method:'post',
                        asynchronous:false,
                        parameters:'json='+Object.toJSON(oRequisicao),
                        onComplete: function (oResponse) {
                          
                          var oRetorno        = {};
                          if (oResponse.responseText != '') {
                            oRetorno = JSON.parse(oResponse.responseText.urlDecode());
                          }
                          __Requisicoes__[sArquivo] = oRetorno;
                        }
                     });
  }
  return __Requisicoes__[sArquivo];
};

/**
 * Retorna o texto da mensagem
 * @param {string} sCaminhoMensagem caminho de mensagem
 * @example DBMensagem.getMensagem('configuracao.mensagem.con4_mensagem001.mensagem_nao_informada');
 *          Aonde: DBPortal.configuracao.con4_mensagem001.mensagem_nao_informada
 *                 |______| |__________| |______________| |____________________|
 *                    |           |             |                   |
 *           Área  <--+           |             |                   |
 *         Módulo  <--------------+             |                   |
 *       Programa  <----------------------------+                   |
 *       Mensagem  <------------------------------------------------+
 *
 * @returns {string} texto da mensagem
 */
DBMensagem.prototype.getMensagem = function (sCaminhoMensagem) {
  var oArquivo        = this.getArquivo(sCaminhoMensagem);
  var sNomeMensagem   = this.getNomeMensagem(sCaminhoMensagem);
  this.sMensagemAtual = sNomeMensagem;
  return oArquivo[sNomeMensagem];
};

/**
 * Realiza a troca das variaveis pelos valores corretos no sistema
 * @param {string} sMensagem texto com a mensagem 
 * @param {Object} oVariaveis Objeto literal com as variaveis
 * @returns {string} Texto com as variaveis substituidas
 */
DBMensagem.prototype.aplicarVariaveis = function(sMensagem, oVariaveis) {
  
  for (sVariavel in oVariaveis) {

    var sValor = new RegExp(sVariavel, 'g');
    sMensagem = sMensagem.replace(sValor, oVariaveis[sVariavel]);    
  }
  sMensagem = sMensagem.replace(/[\[\]]/g, '');
  return sMensagem;
};

/**
 * Guarda historico das mensagens usadas na rotina atual
 * @returns void 
 */
DBMensagem.prototype.guardarHistorico = function() {

  var oRequisicao = {};

  oRequisicao.exec          = 'guardarHistorico';
  oRequisicao.sArquivo      = this.sArquivoAtual;
  oRequisicao.sNomeMensagem = this.sMensagemAtual;

  new Ajax.Request(
    'con4_mensagems.RPC.php', {
      method:'post',
      asynchronous:true,
      parameters:'json='+Object.toJSON(oRequisicao),
    }
  );
}

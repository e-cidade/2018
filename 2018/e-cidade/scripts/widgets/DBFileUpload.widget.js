/**
 * DBFileUpload
 * - componente para fazer upload de arquivos usando iframe
 *
 * @author jeferson.belmiro@dbseller.com.br
 * @author vinicius@dbseller.com.br
 * @param {Object} parameters - configuracoes do componente
 * @constructor
 *
 * @example
 *   var oFileUpload = new DBFileUpload();
 *   oFileUpload.show($('containerFileUpload'));
 *
 * @example
 *   var oFileUpload = new DBFileUpload({
 *     container: document.querySelector('#container'),
 *
 *     // funcao chamada ao final do upload
 *     callBack : function(oRetorno) {
 *       console.log(oRetorno); // objeto com nome do arquivo
 *     }
 *   }).show();
 *
 *   // apos selecionar um arquivo, a instancia do componente tera seguintes propriedades:
 *   console.log(oFileUpload.file); // nome do arquivo enviado
 *   console.log(oFileUpload.filePath); // arquivo gerado no tmp do dbportal_prj
 */
function DBFileUpload(parameters) {

  /**
   * Elementos HTML do componente
   *
   * @type {Object}
   */
  this.elements = {};

  /**
   * Configuracoes
   *
   * @type {Object}
   */
  this.config = {

    /**
     * Tamanho limite aceito do arquivo
     * 0 - nao tem limite
     *
     * @type {integer}
     */
    'sizeLimit' : 0,

    /**
     * Texto do botao para selecionar arquivo
     *
     * @type {String}
     */
    'labelButton' : 'Selecionar Arquivo...',

    /**
     * Mensagem usara na funcao js_divCarregando
     *
     * @type {String}
     */
    'messageUploading' : 'Enviando arquivo, aguarde!',

    /**
     * Elemento HTML onde sera adicionado os elementos do componente, botao e input
     *
     * @type {Object}
     */
    'container' : {},

    /**
     * Funcao que sera executada apos envio do arquivo
     *
     * @type {Function}
     */
    'callBack' : function() {},

    /**
     * Id da instancia, usadao pelo metodo DBFileUpload.getInstance()
     *
     * @type {integer}
     */
    'id' : DBFileUpload.instances.length++
  }

  this.bindConfig(parameters).createElements().registerEvents();
  DBFileUpload.instances[this.config.id] = this;
  return this;
}

/**
 * Define as configuracoes do componente
 *
 * @param {Object} parameters
 * @returns {DBFileUpload}
 */
DBFileUpload.prototype.bindConfig = function(parameters) {

  /**
   * Altera as configuracoes definidas pela assinatura, parameters
   */
  for (var param in parameters) {

    if (this.config[param] === undefined) {
      throw new Error('DBFileUpload: configuração inválida: ' + param);
    }

    this.config[param] = parameters[param];
  }

  this.setCallBack(this.config.callBack);
  return this;
}

/**
 * Define funcao para ser executada apos enviar arquivo
 *
 * @param {Function} callBack
 * @returns {DBFileUpload}
 */
DBFileUpload.prototype.setCallBack = function(callBack) {

  if (typeof(callBack) != 'function') {
    throw new Error('DBFileUpload: callBack deve ser uma função.');
  }

  this.config.callBack = callBack;
  return this;
}

/**
 * Cria elmentos HTML do componente
 *
 * @returns {DBFileUpload}
 */
DBFileUpload.prototype.createElements = function() {

  this.elements.file = document.createElement('input');
  this.elements.file.type = 'file';
  this.elements.file.name = 'file-upload-' + this.config.id;
  this.elements.file.id = 'file-upload-' + this.config.id;
  this.elements.file.style.display = 'none';

  this.elements.container = document.createElement('div');
  this.elements.container.id = 'divFileUpload';

  this.elements.button = document.createElement('input');
  this.elements.button.type = 'button';
  this.elements.button.value = this.config.labelButton;
  this.elements.button.className = 'btnUploadFile';
  this.elements.button.style.marginRight = '5px';
  this.elements.button.style.minHeight   = '18px';


  this.elements.text = document.createElement('input');
  this.elements.text.type = 'text';
  this.elements.text.value = '';
  this.elements.text.readOnly = true;
  this.elements.text.style.color = 'black';
  this.elements.text.className   = 'inputUploadFile';
  this.elements.text.style.minHeight = '20px';

  this.elements.container.appendChild(this.elements.button);
  this.elements.container.appendChild(this.elements.text);
  document.body.appendChild(this.elements.file);

  return this;
}

/**
 * Adiciona elementos do componente em um container
 *
 * @param container - elemento HTML para adcionar botao para enviar arquivo
 *                    caso nao informado, busca dos parametros definidos ao instanciar objeto
 * @returns {DBFileUpload}
 */
DBFileUpload.prototype.show = function(container) {

  var parent = container === undefined ? this.config.container : container;

  /**
   * Verifica se container é um elemento HTML
   */
  if (parent === null || parent === undefined || parent.nodeType === undefined) {
    throw new Error('DBFileUpload: Container não encontrado ou inválido.');
  }

  parent.appendChild(this.elements.container);
  return this;
}

/**
 * Registra enventos dos elementos
 *
 * @returns {DBFileUpload}
 */
DBFileUpload.prototype.registerEvents = function() {

  var self = this;

  this.elements.button.addEventListener('click', function() {
    self.elements.file.click();
  }, false);

  this.elements.file.addEventListener('change', function() {

    js_divCarregando(self.config.messageUploading ,'msgboxDBFileUpload');

    var query = 'input=file-upload&id=' + self.config.id + '&sizeLimit=' + self.config.sizeLimit;

    self.elements.iframe = document.createElement("iframe");
    self.elements.iframe.src = 'DBFileUpload.php?' + query;
    self.elements.iframe.style.display = 'none';
    document.body.appendChild(self.elements.iframe);
  }, false);

  return this;
}

/**
 * Processa funcao de retorno, apos enviar arquivo
 * - adiciona nome do arquivo original no input gerado pelo componente
 * - adiciona propriedade com nome do arquivo na instancia do componente
 * - executa funcao de callback definida
 *
 * @returns {DBFileUpload}
 */
DBFileUpload.prototype.processCallBack = function(parameters) {

  if (parameters.error == '') {
    this.elements.text.value = parameters.file;
  }

  for (param in parameters) {
    this[param] = parameters[param];
  }

  js_removeObj('msgboxDBFileUpload');
  this.config.callBack(parameters);
  return this;
}

/**
 * Instancias do componente
 *
 * @type {Array}
 */
DBFileUpload.instances = [];

/**
 * Retorna instancia do componente pelo id
 * - caso nao exista nenhuma instancia, cria
 *
 * @param {integer} id
 * @returns {DBFileUpload}
 */
DBFileUpload.getInstance = function(id) {

  /**
   * Busca id da ultima instancia gerada
   */
  if (id === undefined) {
    id = DBFileUpload.instances.length > 0 ? DBFileUpload.instances.length - 1 : 0;
  }

  /**
   * Caso nao exista nenhuma instancia, cria, com id 0
   */
  if (DBFileUpload.instances.length === 0) {
    DBFileUpload.instances[id] = new DBFileUpload({id : id});
  }

  /**
   * Instancia nao encontrada
   */
  if (DBFileUpload.instances[id] === undefined) {
    throw new Error('DBFileUpload: Instancia pelo id "' + id + '" não encontrada.');
  }

  return DBFileUpload.instances[id];
}

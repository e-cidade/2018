<?php

/**
 * DO NOT CHANGE THIS FILE
 * ONLY USE THIS TO COPY FOR YOUR config/application.php FILE
 */

\ECidade\V3\Extension\Registry::get('app.config')->merge(array(

  /**
   * Charset do projeto
   * @type string
   */
  'charset' => 'UTF-8',

  /**
   * Exibir erros
   * @type boolean
   */
  'php.display_errors' => false,

  /**
   * Tipos de erros para capturar
   */
  'php.error_reporting' => E_ALL & ~E_DEPRECATED & ~E_STRICT,

  /**
   * Lista de URL's de api's usadas pelo ecidade
   */
  'app.api' => array(
    'centraldeajuda' => 'http://centraldeajuda.dbseller.com.br/help/api/index.php/',
    'esocial' => array(
        'url' => '', // informe a api do eSocial
        'login' => '', // login do cliente 
        'password' => '' // senha do cliente
    )
  ),

  /**
   * Configuração de proxy para o e-cidade
   */
  'app.proxy' => array(
      'http'  => '', // e.g. 192.168.0.1:3128
      'https' => '', // e.g. 192.168.0.1:3128
      'tcp'   => ''  // e.g. 192.168.0.1:3128
  ),

  /**
   * Requisicoes que usaram sessao
   * @type string - glob pattern
   */
  'app.request.session.attachOn' => '*.php',

  /**
   * Requisicoes que usaram sessao somente leitura
   * @type string - glob pattern
   */
  'app.request.session.readOnlyOn' => '{skins/*,*.js,*.css}',

  /**
   * Extensoes de arquivos para cachear - 304
   * @type array
   */
  'app.request.asset.cacheable.extension' => array('js', 'css', 'jpg', 'jpeg', 'png', 'bmp', 'ttf', 'gif'),

  /**
   * Log de erros do php
   * @type boolean
   */
  'app.error.log' => true,

  /**
   * Caminho do arquivo para gravar erros do php
   * @type string
   */
  'app.error.log.path' => ECIDADE_EXTENSION_LOG_PATH . 'error.log',

  /**
   * @type string
   */
  'app.error.log.mask' => "{type} - {message} in {file} on line {line}\n{trace}",

  /**
   * @type string
   */
  'app.error.log.mask.trace' => "#{index} {file}:{line} - {class}{type}{function}({args})\n",

  /**
   * Eventos
   * - app.error: executado a cada erro, respeitando a config php.error_reporting
   * - app.shutdown: executado ao final de cada requisicao
   * @type Array
   */
  'app.events' => array('app.error' => '\ECidade\V3\Error\EventHandler'),

  /**
   * @type Integer
   * 0 : quiet
   * 1 : info    [info]
   * 2 : notice  [info, notice]
   * 3 : warning [info, notice, warning]
   * 4 : error   [info, notice, warning, error]
   * 5 : debug   [info, notice, warning, error, debug]
   */
  'app.log.verbosity' => \ECidade\V3\Extension\Logger::ERROR,

  /**
   * @type String
   */
  'app.log.path' => ECIDADE_EXTENSION_LOG_PATH . 'application.log',

  /**
   * @type String
   */
  'app.modifications.log.path' => ECIDADE_EXTENSION_LOG_PATH . 'modifications.log',

  /**
   * UTF8 -> UNICODE
   * ISO-8859-1 -> LATIN1
   * @see https://secure.php.net/manual/pt_BR/function.pg-set-client-encoding.php
   * @type string
   **/
  'db.client_encoding' => 'LATIN1',

));

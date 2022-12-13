<?php

use \ECidade\V3\Extension\Registry;
use \ECidade\V3\Modification\Data\FileSync;
use \ECidade\V3\Modification\Data\File as FileData;
use \ECidade\V3\Modification\Manager as ModificationManager;
use \ECidade\V3\Extension\Logger;

/**********************************************************************************************************************/

//
// CONSTANTS DEFINITIONS
//

// @deprecated Nao existe mais o extension, mas mantemos isso aqui por compatibilidade
// versao atual do extension
define('ECIDADE_EXTENSION_VERSION', '3.0.0');

// separador de diretorios: '/'
define('DS', DIRECTORY_SEPARATOR);

// caminho absoluto do ecidade (ex.: /var/www/e-cidade/ )
define('ECIDADE_PATH', __DIR__ . DS);

$host = null;
if (isset($_SERVER['HTTP_HOST'])) {

  $protocol = 'http://';
  if (isset($_SERVER['HTTPS']) && strtoupper($_SERVER['HTTPS']) == 'ON') {
    $protocol = 'https://';
  }

  $host = $protocol . $_SERVER['HTTP_HOST'];
}

// diretorio raiz para requisicoes: http:/localhost/e-cidade/
// @todo - achar um metodo mais eficiente
define('ECIDADE_REQUEST_ROOT',
  str_replace(array('extension/index.php', 'FrontController.php'), '', $_SERVER['SCRIPT_NAME'])
);

define('ECIDADE_REQUEST_PATH', $host . ECIDADE_REQUEST_ROOT);

// extension dirs
define('ECIDADE_EXTENSION_PATH', ECIDADE_PATH . 'extension' . DS);
define('ECIDADE_EXTENSION_PACKAGE_PATH', ECIDADE_EXTENSION_PATH . 'package' . DS);
define('ECIDADE_EXTENSION_VENDOR_PATH', ECIDADE_EXTENSION_PATH . 'vendor' . DS);
define('ECIDADE_EXTENSION_DATA_PATH', ECIDADE_EXTENSION_PATH . 'data' . DS);
define('ECIDADE_EXTENSION_LOG_PATH', ECIDADE_EXTENSION_PATH . 'log' . DS);

// modification dirs
define('ECIDADE_MODIFICATION_PATH', ECIDADE_EXTENSION_PATH . 'modification' . DS);
define('ECIDADE_MODIFICATION_DATA_PATH', ECIDADE_MODIFICATION_PATH . 'data' . DS);
define('ECIDADE_MODIFICATION_CACHE_PATH', ECIDADE_MODIFICATION_DATA_PATH . 'cache' . DS);
define('ECIDADE_MODIFICATION_LOG_PATH', ECIDADE_MODIFICATION_PATH . 'log' . DS);
define('ECIDADE_MODIFICATION_XML_PATH', ECIDADE_MODIFICATION_PATH . 'xml' . DS);

// fatal errors
define('E_FATAL', (E_ERROR | E_USER_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_RECOVERABLE_ERROR));

/**********************************************************************************************************************/

//
// FUNCTION DEFINITIONS
//

/**
 * Verifica se um arquivo tem modificacao
 * caso possua retorna o caminho absoluto do arquivo modificado
 *
 * @param string $file
 * @return string
 */
function modification($file) {

  try {

    if (!file_exists($file)) {
      throw new Exception('Arquivo não existe: ' . $file);
    }

    $user = null;
    $request = Registry::get('app.request');

    if ($request && $request->session()->has('DB_login')) {
      $user = $request->session()->get('DB_login');
    }

    // mantem arquivo de cache atualizado
    // caso nao exista cache ou ocorrer erro retorna false
    $fileData = FileSync::update($file, $user);

    if ($fileData) {
      return $fileData->getPath();
    }

  } catch (Exception $error) {
    $logger = Registry::get('app.container')->get('app.logger');
    $logger->error('modification() - update file: ' . $file . ' : '. $error->getMessage());
  }

  // nao possui cache valido
  return $file;
}

/**********************************************************************************************************************/

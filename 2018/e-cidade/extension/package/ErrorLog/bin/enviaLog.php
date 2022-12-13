<?php

ini_set('post_max_size','500M');
ini_set('memory_limit', '-1');

require(dirname(dirname(dirname(dirname(__DIR__)))) . '/definitions.php');

define('URL_SERVICE', 'http://mensageria.dbseller.com.br/ecidade_error_colector/awsReceiver.php?DBSELLER=1');
define('DIR_QUEUE', ECIDADE_EXTENSION_PACKAGE_PATH . "ErrorLog/queue/");
define('DIR_RUNNING', ECIDADE_EXTENSION_PACKAGE_PATH . "ErrorLog/running/");
define('ERROR_FILE', ECIDADE_EXTENSION_LOG_PATH . "error.data");

// arquivo de configuracao que fica no cliente, contem uma constante com o nome do cliente.
include('config.php');

/**
 * Cria diretorios caso nao exista
 */
if (!is_dir(DIR_QUEUE)) {
  if (!mkdir(DIR_QUEUE, 0777, true)) {
    _log('Erro ao criar diretorio:' . DIR_QUEUE);
    exit(1);
  }
  if (!mkdir(DIR_RUNNING, 0777, true)) {
    _log('Erro ao criar diretorio:' . DIR_RUNNING);
    exit(1);
  }
}

/**
 * move arquivo de error.json para fila
 */
if (file_exists(ERROR_FILE)) {
  if (!rename(ERROR_FILE, DIR_QUEUE . FILE_PREFIX . '_' . time() . '.data')) {
    _log('Erro ao renomear arquivo:' . ERROR_FILE . ' para '. DIR_QUEUE . FILE_PREFIX . '_' . time() . '.data');
  }
}

$files = glob(DIR_QUEUE .'*.data');

/**
 * Nenhum arquivo para enviar
 */
if (empty($files)) {
  exit(1);
}

foreach ($files as $file) {

  $fileJson = DIR_RUNNING . basename($file);

  /**
   * Move arquivo do diretorio QUEUE para RUNNING
   */
  if (!rename($file, $fileJson)) {

    _log('Erro ao mover aquivo ' . $file . ' para  ' . $fileJson);
    continue;
  }

  $fileCompress = compress($fileJson);

  /**
   * Envia arquivo, compactado ou json
   */
  $result = sendFile($fileCompress ?: $fileJson);

  /**
   * Apos enviado remove arquivo tar.xz
   */
  if ($fileCompress && !unlink($fileCompress)) {
    _log('Erro ao remover arquivo: ' . $fileCompress);
  }

  /**
   * Aquivo enviado, remove
   */
  if ($result == "success") {

    if (!unlink($fileJson)) {
      _log('Erro ao remover arquivo:' . $fileJson);
    }

    continue;
  }

  /**
   * Erro ao enviar, devolve arquivo para fila
   */
  if (!rename($fileJson, DIR_QUEUE . basename($file))) {
    _log('Erro ao remover arquivo:' . $fileJson);
    }
  }

/**
 * @param string $file - arquivo .json
 * @return string | bool - nome arquivo compactado ou false
 */
function compress($file) {

  $filename = pathinfo($file, PATHINFO_FILENAME) . '.tar.xz';
  $path = dirname($file) . DIRECTORY_SEPARATOR;

  $command = 'cd ' . $path;
  $command .= ' && tar -Jcf ' . escapeshellarg($filename) . ' ' . escapeshellarg(basename($file));
  exec($command, $output, $status);

  if ($status > 0) {

    unlink($path . $filename);
    _log('Erro ao compactar arquivo: ' . $command . ' | ' . $status . " | " . print_r($output, true));
    return false;
}

  return $path . $filename;
}

/**
 * @param string $message
 * @return void
 */
function _log($message) {

  $output = '['.date('Y-m-d H:i:s').'] ' . $message;
  if (!file_put_contents(ECIDADE_EXTENSION_LOG_PATH . 'extension-error-log', $output . PHP_EOL, FILE_APPEND)) {
    echo "Erro ao escrever no arquivo: " . ECIDADE_EXTENSION_LOG_PATH . 'extension-error-log' . "\n";
  }
}

/**
 * @param string $file
 * @return bool
 */
function sendFile($file) {

  $data = array(
    'filename' => basename($file),
    'rawData' => file_get_contents($file),
  );

  $context = stream_context_create(
    array(
      'http' => array(
        'method' => 'POST',
        'header' => array(
          'Content-Type: application/x-www-form-urlencoded'
        ),
        'content' => http_build_query($data)
      )
    )
  );

  $output = file_get_contents(URL_SERVICE, false, $context);

  if (!$output) {
    _log('Erro ao enviar arquivo: ' . $file);
  }

  return $output;
}

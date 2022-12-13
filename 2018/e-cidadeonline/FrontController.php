<?php

if (file_exists('vendor/autoload.php')) {
  require_once 'vendor/autoload.php';
}

if (file_exists('extension/index.php')) {
  return require('extension/index.php');
}

// separador de diretorios: '/'
define('DS', DIRECTORY_SEPARATOR);

// Caminho absoluto do ecidade: /var/www/e-cidade/
define('ECIDADE_PATH', __DIR__ . DS);

$host = null;
if (isset($_SERVER['HTTP_HOST'])) {

  $protocol = 'http://';
  if (isset($_SERVER['HTTPS']) && strtoupper($_SERVER['HTTPS']) == 'ON') {
    $protocol = 'https://';
  }

  $host = $protocol . $_SERVER['HTTP_HOST'];
}

// Diretorio raiz para requisicoes: http:/localhost/e-cidade/
define('ECIDADE_REQUEST_ROOT',
  str_replace(array('extension/index.php', 'FrontController.php'), '', $_SERVER['SCRIPT_NAME'])
);

define('ECIDADE_REQUEST_PATH', $host . ECIDADE_REQUEST_ROOT);
// modification dirs
define('ECIDADE_MODIFICATION_PATH', ECIDADE_PATH . 'modification' . DS);
define('ECIDADE_MODIFICATION_CACHE_PATH', ECIDADE_MODIFICATION_PATH . 'cache' . DS);
define('ECIDADE_MODIFICATION_LOG_PATH', ECIDADE_MODIFICATION_PATH . 'log' . DS);
define('ECIDADE_MODIFICATION_XML_PATH', ECIDADE_MODIFICATION_PATH . 'xml' . DS);

require_once "std/Modification.php";

$sPath = $_GET['_path'];
if (empty($sPath)) {
  $sPath = 'index.php';
}

fixQueryString($sPath);

$realpath = realpath(ECIDADE_PATH . $sPath);

// Arquivo nao existe, 404
if ($realpath === false || !file_exists($sPath)) {
  header('HTTP/1.0 404 Not Found');
  exit;
}

/**
 * security issue
 */
if (strpos($realpath, ECIDADE_PATH) !== 0) {
  header('HTTP/1.0 403 Forbiden');
  exit;
}

$fileExtension = pathinfo($sPath, PATHINFO_EXTENSION);

if ($fileExtension != 'php') {

  if (in_array($fileExtension, array('js', 'css') )) {
    $sPath = \modification($sPath);
  }

  mb_internal_encoding('ISO-8859-1');
  mb_http_output('ISO-8859-1');
  readAsset($sPath);
  exit;
}

header("Content-Type: text/html; charset=ISO-8859-1", true);
require \modification($sPath);

function fixQueryString($sPath) {

  $aQueryString = array();
  parse_str($_SERVER['QUERY_STRING'], $aQueryString);
  unset($aQueryString['_path'], $_GET['_path']);
  $PHP_SELF = explode("?", $_SERVER['REQUEST_URI']);
  $_SERVER['QUERY_STRING'] = urldecode(http_build_query($aQueryString));
  $_SERVER['PHP_SELF'] = $PHP_SELF[0];
  $_SERVER['SCRIPT_NAME'] = $PHP_SELF[0];
  $_SERVER['SCRIPT_FILENAME'] = ECIDADE_PATH . $sPath;

  $GLOBALS['PHP_SELF']         = $PHP_SELF[0];
  $GLOBALS['HTTP_SERVER_VARS'] = & $_SERVER;
}


function isCacheable($ext) {
  return in_array($ext, array('js', 'css', 'jpg', 'jpeg', 'png', 'bmp', 'ttf', 'gif'));
}

function readAsset($file) {

    $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1');
    header($protocol . ' 200 OK', true);

    $filesize = filesize($file);
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    header("Content-Length: $filesize", true);

    $contentType = getMimeType($file, $ext);
    header("Content-Type: $contentType; charset=ISO-8859-1", true);

    if (isCacheable($ext)) {

      $filemtime = filemtime($file);
      $modified = gmdate('r', $filemtime);

      header("Last-Modified: $modified", true);
      header("Cache-Control: public", true);


      if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $filemtime) {
        header($protocol . ' 304 Not Modified', true);
        return;
      }

    }

    return readfile($file);
}

function getMimeType($filename, $ext = null) {

  $mime_types = array(

    'txt' => 'text/plain',
    'htm' => 'text/html',
    'html' => 'text/html',
    'php' => 'text/php',
    'css' => 'text/css',
    'js' => 'application/javascript',
    'json' => 'application/json',
    'xml' => 'application/xml',
    'swf' => 'application/x-shockwave-flash',
    'flv' => 'video/x-flv',

    // images
    'png' => 'image/png',
    'jpe' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'jpg' => 'image/jpeg',
    'gif' => 'image/gif',
    'bmp' => 'image/bmp',
    'ico' => 'image/vnd.microsoft.icon',
    'tiff' => 'image/tiff',
    'tif' => 'image/tiff',
    'svg' => 'image/svg+xml',
    'svgz' => 'image/svg+xml',

    // archives
    'zip' => 'application/zip',
    'rar' => 'application/x-rar-compressed',
    'exe' => 'application/x-msdownload',
    'msi' => 'application/x-msdownload',
    'cab' => 'application/vnd.ms-cab-compressed',

    // audio/video
    'mp3' => 'audio/mpeg',
    'qt' => 'video/quicktime',
    'mov' => 'video/quicktime',

    // adobe
    'pdf' => 'application/pdf',
    'psd' => 'image/vnd.adobe.photoshop',
    'ai' => 'application/postscript',
    'eps' => 'application/postscript',
    'ps' => 'application/postscript',

    // ms office
    'doc' => 'application/msword',
    'rtf' => 'application/rtf',
    'xls' => 'application/vnd.ms-excel',
    'ppt' => 'application/vnd.ms-powerpoint',

    // open office
    'odt' => 'application/vnd.oasis.opendocument.text',
    'ods' => 'application/vnd.oasis.opendocument.spreadsheet',

    'pgbkp' => 'application/octet-stream',
    'csv' => 'text/csv',
  );

  if (empty($ext)) {
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
  }

  if (array_key_exists($ext, $mime_types)) {
    return $mime_types[$ext];
  }

  if (function_exists('mime_content_type')) {
    return mime_content_type($filename);
  }

  if (function_exists('finfo_open')) {

    $finfo = finfo_open(FILEINFO_MIME);
    $mimetype = finfo_file($finfo, $filename);
    finfo_close($finfo);
    return $mimetype;
  }

  return 'application/octet-stream';
}

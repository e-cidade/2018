<?php
namespace ECidade\V3\Extension;

use \ECidade\V3\Extension\Registry;

class Response {

  /**
   * @var array
   */
	protected $headers = array();

  /**
   * @var string
   */
	protected $body;

  /**
   * @var string
   */
	protected $file;

  /**
   * @var string
   */
  protected $contentType = 'text/html';

  /**
   * @var string
   */
  protected $charset = 'UTF-8';

  /**
   * @var integer
   */
	protected $code = 200;

  /**
   * @var array
   */
  protected $codes = array(
    100 => 'Continue',
    101 => 'Switching Protocols',
    200 => 'OK',
    201 => 'Created',
    202 => 'Accepted',
    203 => 'Non-Authoritative Information',
    204 => 'No Content',
    205 => 'Reset Content',
    206 => 'Partial Content',
    300 => 'Multiple Choices',
    301 => 'Moved Permanently',
    302 => 'Found',
    303 => 'See Other',
    304 => 'Not Modified',
    305 => 'Use Proxy',
    306 => '(Unused)',
    307 => 'Temporary Redirect',
    400 => 'Bad Request',
    401 => 'Unauthorized',
    402 => 'Payment Required',
    403 => 'Forbidden',
    404 => 'Not Found',
    405 => 'Method Not Allowed',
    406 => 'Not Acceptable',
    407 => 'Proxy Authentication Required',
    408 => 'Request Timeout',
    409 => 'Conflict',
    410 => 'Gone',
    411 => 'Length Required',
    412 => 'Precondition Failed',
    413 => 'Request Entity Too Large',
    414 => 'Request-URI Too Long',
    415 => 'Unsupported Media Type',
    416 => 'Requested Range Not Satisfiable',
    417 => 'Expectation Failed',
    500 => 'Internal Server Error',
    501 => 'Not Implemented',
    502 => 'Bad Gateway',
    503 => 'Service Unavailable',
    504 => 'Gateway Timeout',
    505 => 'HTTP Version Not Supported'
  );

  /**
   * @var array
   */
  protected $contentTypes = array(

    'txt' => 'text/plain',
    'htm' => 'text/html',
    'html' => 'text/html',
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
    'sxw' => 'application/otcet-stream',

    'csv' => 'text/csv',

    'default' => 'application/octet-stream'
  );

  /**
   * @return bool
   */
  public function isActive() {
    return connection_status() === CONNECTION_NORMAL && !connection_aborted();
  }

  /**
   * @param string $key
   * @param string $value
   * @return void
   */
	public function addHeader($key, $value) {
		$this->headers[$key] = $value;
	}

  /**
   * @param integer $code
   * @return void
   */
  public function setCode($code) {
    $this->code = (int) $code;
  }

  /**
   * @para string $contentType
   * @return void
   */
  public function setContentType($contentType) {

    $this->contentType = $contentType;

    /**
     * alias
     */
    if (strpos($contentType, '/') === false) {

      $contentTypeKey = isset($this->contentTypes[$contentType]) ? $contentType : 'default';
      $this->contentType = $this->contentTypes[$contentTypeKey];
    }

  }

  /**
   * @param string $charset
   * @return void
   */
  public function setCharset($charset) {
    $this->charset = $charset;
  }

  /**
   * @return string
   */
  public function getCharset() {
    return $this->charset;
  }

  /**
   * @param string $url
   * @param integer $status
   * @return void
   */
	public function redirect($url, $status = 302) {

		header('Status: ' . $status, true);
		header('Location: ' . str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), $url));
		exit();
	}

  /**
   * @param string $filePath
   * @return void
   */
	public function setFile($filePath) {

    $this->file = $filePath;
    $filesize = filesize($this->file);
    $ext = pathinfo($this->file, PATHINFO_EXTENSION);
    $this->addHeader('Content-Length', $filesize);

    $this->setContentType($ext);

    if (!$this->isCacheable($ext)) {
      return false;
    }

    $filemtime = filemtime($this->file);
    $modified = gmdate('r', $filemtime);

    $this->addHeader('Last-Modified', $modified);
    $this->addHeader('Cache-Control', 'public');

    if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $filemtime) {
      $this->setCode(304);
    }
	}

  /**
   * @param string $ext - extensao do arquivo, caso nao informado usa $this->file
   * @return bool
   */
  public function isCacheable($ext = null) {
    return in_array(
      $ext ?: pathinfo($this->file, PATHINFO_EXTENSION),
      (array) Registry::get('app.config')->get('app.request.asset.cacheable.extension')
    );
  }

  /**
   * @param mixed $body
   * @return void
   */
	public function setBody($body) {

    switch ($this->contentType) {

      case 'application/json' :

        if (is_object($body) && method_exists($body, 'toJSON')) {
          $this->body = $body->toJSON();
        } else {
          $this->body = json_encode($body);
        }

      break;

      default :

        $this->body = $body;

        if ((is_object($this->body) && !method_exists($this->body, '__toString')) || !is_string($this->body)) {
          $this->body = print_r($body, true);
        }

      break;
    }
	}

  /**
   * @return string
   */
	public function getBody() {
		return $this->body;
	}

  /**
   * @return bool
   */
  public function hasBody() {
    return !empty($this->body);
  }

  /**
   * @return bool
   */
  public function hasSend() {
    return headers_sent();
  }

  /**
   * @return bool
   */
  public function send() {

    if ($this->hasSend()) {
      return false;
    }

    // http response code
    $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1');
    header($protocol . ' ' . $this->code . ' ' . $this->codes[$this->code], true);

    if (!empty($this->contentType)) {
      header('Content-Type: ' . $this->contentType . '; charset=' . $this->charset, true);
    }

    foreach ($this->headers as $key => $value) {
      header("$key: $value", true);
    }

    return true;
  }

  /**
   * @return void|bool
   */
  public function output() {

    $this->send();

    if (!empty($this->file) && $this->code !== 304) {
      readfile($this->file);
    }

    if (!empty($this->body)) {
      echo $this->body;
    }
  }

}

<?php
namespace ECidade\V3\Extension;

use \ECidade\V3\Extension\Registry;
use \ECidade\V3\Window\Session;
use \ECidade\V3\Window\Window;

class Front {

  private $sPath;
  private $sFile;
  private $windowId;
  private $lExtension = false;

  public function __construct() {

    $this->sPath = $_GET['_path'];
    $aPath = explode('/', $this->sPath);
    $sPath = array_shift($aPath);

    // multi janela
    if ($sPath == 'w') {

      $this->windowId = array_shift($aPath);
      $this->sPath = implode('/', $aPath);
      $sPath = current($aPath);
    }

    if ($sPath == 'extension') {

      if ($aPath[0] == 'extension') {
        array_shift($aPath);
      }
      $this->sPath = implode('/', $aPath);
      $this->lExtension = true;
    }

    unset($_GET['_path'], $_REQUEST['_path']);
  }

  public function isExtension() {
    return $this->lExtension;
  }

  /**
   * Remove indice _path da query string, e do GET
   * - para nao impacter em rotinas que usam: parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));
   * @return void
   */
  public function fixQueryString() {

    $aQueryString = array();
    parse_str($_SERVER['QUERY_STRING'], $aQueryString);
    unset($aQueryString['_path']);
    $PHP_SELF = explode("?", $_SERVER['REQUEST_URI']);
    $_SERVER['QUERY_STRING'] = urldecode(http_build_query($aQueryString));
    $_SERVER['PHP_SELF'] = $PHP_SELF[0];
    $_SERVER['SCRIPT_NAME'] = $PHP_SELF[0];
    $_SERVER['SCRIPT_FILENAME'] = ECIDADE_PATH . $this->sPath;

    $this->emulateRegisterGlobals(array('SERVER'));
  }

  /**
   * variables_order = "EGPCS"
   * @deprecated
   * @see \DBSeller\Legacy\PHP53\Emulate::$egpcs
   */
  protected function getSuperGlobals() {
    return array('ENV', 'GET', 'POST', 'COOKIE', 'SERVER', 'SESSION');
  }

  /**
   * Emula: register_long_arrays = On
   * @deprecated
   * @see \DBSeller\Legacy\PHP53\Emulate::registerLongArrays()
   *
   * @param array $superglobals
   * @return void
   */
  public function emulateRegisterLongArrays(array $superglobals = array()) {

    if (empty($_ENV)) {
      $_ENV['PATH_INFO'] = getenv('PATH_INFO');
      $_ENV['HTTP_USER_AGENT'] = getenv('HTTP_USER_AGENT');
    }

    $superglobals = $superglobals ?: $this->getSuperGlobals();
    foreach ($superglobals as $name) {
      $GLOBALS["HTTP_{$name}_VARS"] =& $GLOBALS["_$name"];
    }
  }

  /**
   * Emula: register_globals = On
   * @deprecated
   * @see \DBSeller\Legacy\PHP53\Emulate::registerGlobals()
   *
   * @param array $superglobals
   * @return void
   */
  public function emulateRegisterGlobals(array $superglobals = array()) {

    $superglobals = $superglobals ?: $this->getSuperGlobals();
    foreach ($superglobals as $name) {

      if (!isset($GLOBALS["_$name"])) {
        continue;
      }

      foreach($GLOBALS["_$name"] as $key => & $value) {
        $GLOBALS[$key] = $value;
      }

      reset($GLOBALS["_$name"]);
    }
  }

  /**
   * @return void
   */
  public function createWindow() {

    $request = Registry::get('app.request');
    $window = new Window($this->windowId);
    $request->session($window->session());

    Registry::set('app.window', $window);
  }

  /**
   * @return string
   */
  public function getPath() {
    return $this->sPath;
  }

  /**
   * @return string
   */
  public function getWindowRequestPath() {

    $suffix = '';

    if ($this->windowId) {
      $suffix = 'w' . DS . $this->windowId . DS ;
    }

    return ECIDADE_REQUEST_PATH . $suffix;
  }

}

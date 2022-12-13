<?php

class EmulatePHP53 {

  private static $egpcs = array(
    'ENV', 'GET', 'POST', 'COOKIE', 'SERVER', 'SESSION'
  );

  /**
   * Emula a variavel de ambiente $_ENV
   *
   * @return boolean
   */
  public static function env() {

    $GLOBAL['_ENV'] = array();
    $GLOBAL['_ENV']['PATH_INFO'] = getenv('PATH_INFO');
    $GLOBAL['_ENV']['HTTP_USER_AGENT'] = getenv('HTTP_USER_AGENT');
    return true;
  }

  /**
   * Emula: register_long_arrays = On
   *
   * @return boolean
   */
  public static function registerLongArrays() {

    foreach (self::$egpcs as $name) {
      $GLOBALS["HTTP_{$name}_VARS"] =& $GLOBALS["_$name"];
    }
    return true;
  }

  /**
   * Emula: register_globals = On
   *
   * @return boolean
   */
  public static function registerGlobals() {

    foreach (self::$egpcs as $name) {

      if (!isset($GLOBALS["_$name"])) {
        continue;
      }

      foreach($GLOBALS['_'.$name] as $key => & $value) {
        $GLOBALS[$key] = $value;
      }

      reset($GLOBALS["_$name"]);
    }

    return true;
  }

  /**
   * Emula: magic_quotes_gpc = On|Off
   *
   * @return void
   */
  public static function magicQuotesGPCR($directive = 'On') {

    $active = true;

    if (strtolower($directive) == 'off' || (is_bool($directive) && $directive === false))  {
      $active = false;
    }

    $handler = function(&$value) use($active) {
      $value = $active ? addslashes($value) : stripslashes($value);
    };

    array_walk_recursive($_GET, $handler);
    array_walk_recursive($_POST, $handler);
    array_walk_recursive($_COOKIE, $handler);
    array_walk_recursive($_REQUEST, $handler);

    reset($_GET);
    reset($_POST);
    reset($_COOKIE);
    reset($_REQUEST);    
  }

  /**
   * Emula o comportamento do session_register
   * @static
   * @link http://php.net/manual/pt_BR/function.session-register.php
   */
  public static function sessionRegister(){
    
    $args = func_get_args();
    foreach ($args as $key){

      $_SESSION[$key]= isset($GLOBALS[$key]) ? $GLOBALS[$key] : null;
    }
  }

  /**
   * Emula o comportamento da função session_is_registered 
   *
   * @link http://php.net/manual/pt_BR/function.session-is-registered.php
   * @static
   * @param mixed $key
   */
  public static function sessionIsRegistered($key){
    return isset($_SESSION[$key]);
  }

  /**
   * Emula o comportamento da função sesion_unregister
   *
   * @link http://php.net/manual/pt_BR/function.session-unregister.php
   * @static  
   * @param mixed $key
   */
  public static function sessionUnregister($key){
    unset($_SESSION[$key]);
  }
}

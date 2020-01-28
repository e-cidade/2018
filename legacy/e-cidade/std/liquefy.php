<?php 
/**
 * Adapta a aplicação com diversos ambientes.
 */


/**
 * Servidor com php 5.4 
 */
if (version_compare(PHP_VERSION, '5.4.0') >= 0) {

  require_once 'std/legacy/EmulatePHP53.php';

  if (!isset($_ENV)) {
    \EmulatePHP53::env();
  }

  /**
   * Register LongArrays
   */
  \EmulatePHP53::registerLongArrays();

  if (!ini_get('register_globals')) {
    \EmulatePHP53::registerGlobals();
  }

  if (!ini_get('magic_quotes_gpc')) {
    \EmulatePHP53::magicQuotesGPCR(); 
  }

  if (!function_exists('session_register')) {

    function session_register() {
      return call_user_func_array('\EmulatePHP53::sessionRegister', func_get_args());
    }

    function session_unregister($key) {
      return \EmulatePHP53::sessionUnregister($key);
    }

    function session_is_registered($key) {
      return \EmulatePHP53::sessionIsRegistered($key);
    }
  } 
}


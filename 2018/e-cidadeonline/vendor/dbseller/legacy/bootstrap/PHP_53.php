<?php

if (version_compare(PHP_VERSION, '5.4.0') >= 0) {

  if (!isset($_ENV)) {
    \DBSeller\Legacy\PHP53\Emulate::env();
  }

  /**
   * Register LongArrays
   */
  \DBSeller\Legacy\PHP53\Emulate::registerLongArrays();

  if (!ini_get('register_globals')) {
    \DBSeller\Legacy\PHP53\Emulate::registerGlobals();
  }

  if (!ini_get('magic_quotes_gpc')) {
    \DBSeller\Legacy\PHP53\Emulate::magicQuotesGPCR();
  }

  if (!function_exists('session_register')) {

    /**
     * Register one or more global variables with the current session
     * @return bool
     */
    function session_register() {
      return call_user_func_array('\DBSeller\Legacy\PHP53\Emulate::sessionRegister', func_get_args());
    }

    /**
     * Unregister a global variable from the current session
     *
     * @param  String $key Name
     * @return bool
     */
    function session_unregister($key) {
      return \DBSeller\Legacy\PHP53\Emulate::sessionUnregister($key);
    }

    /**
     * Find out whether a global variable is registered in a session
     *
     * @param  String  $key Name
     * @return boolean
     */
    function session_is_registered($key) {
      return \DBSeller\Legacy\PHP53\Emulate::sessionIsRegistered($key);
    }
  }
}

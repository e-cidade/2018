<?php
use DBSeller\Legacy\PHP56\Emulate;

if (version_compare(PHP_VERSION, '7.0') >= 0) {

  if (!function_exists('split')) {

    /**
     * Register one or more global variables with the current session
     *
     * @return bool
     */
    function split($pattern, $string) {
      return Emulate::split($pattern, $string);
    }
  }
}

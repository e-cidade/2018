<?php

namespace DBSeller\Legacy\PHP56;

class Emulate {

  /**
   * Split string into array by regular expression
   *
   * @return array
   */
  public static function split($pattern, $string) {
    return explode($pattern, $string);
  }
}

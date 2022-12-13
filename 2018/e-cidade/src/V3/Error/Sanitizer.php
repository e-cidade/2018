<?php

namespace ECidade\V3\Error;

class Sanitizer {

  public static function clearPath($filePath) {
    return str_replace(ECIDADE_PATH, '', $filePath);
  }

}
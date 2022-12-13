<?php

namespace ECidade\V3\Extension\Exceptions;

use Exception;

class ResponseException extends Exception {

  public function __construct($sMessage = '', $code = 500) {
    parent::__construct($sMessage, $code);
  }

}
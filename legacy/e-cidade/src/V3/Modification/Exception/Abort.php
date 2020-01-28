<?php

namespace ECidade\V3\Modification\Exception;
use Exception;

class Abort extends Exception {

  public function __construct($message = null, $code = 0) {
    parent::__construct($message, $code);
  }

}

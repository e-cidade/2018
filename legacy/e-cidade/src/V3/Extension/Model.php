<?php

namespace ECidade\V3\Extension;

use ECidade\V3\Datasource\Database;

class Model {

  public function __construct() {
    $this->db = Database::getInstance();
  }

}
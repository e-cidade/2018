<?php

namespace ECidade\V3\Extension;

use \ECidade\V3\Extension\ParameterBag;

class ReferenceBag extends ParameterBag {

  /**
   * Constructor.
   *
   * @param array $data
   */
  public function __construct(array & $data = array()) {
    $this->data =& $data;
  }

  /**
   * @return array
   */
  public function & all() {
    return $this->data;
  }

  /**
   * Replaces the current data by a new set.
   *
   * @param array
   * @return ParameterBag
   */
  public function replace(array & $data = array()) {
    $this->data =& $data;
    return $this;
  }

}


<?php

namespace ECidade\V3\Error;

use \Exception;

class Trace {

  /**
   * @var array
   */
  protected $data = array();

  public function __construct($exception = null) {

    if ($exception instanceof Exception) {
      return $this->setData($exception->getTrace());
    }

    try {
      throw new Exception();
    } catch (Exception $error) {

      $trace = $error->getTrace();
      // here we remove this class from trace stack
      array_shift($trace);
      $this->setData($trace);
    }
  }

  /**
   * @param  callable $callback
   * @return array
   */
  public function filter($callback) {
    return $this->data = array_values(array_filter($this->data, $callback));
  }

  public function getData() {
    return $this->data;
  }

  public function setData($data) {
    $this->data = $data;
  }

  public function getSanitizedData() {

    $data = $this->data;
    array_walk_recursive($data, array($this, 'sanitize'));
    return $data;
  }

  public function sanitize(&$value) {

    $type = gettype($value);

    if ($type == 'string') {
      return $value = Sanitizer::clearPath($value);
    }   

    if ($type == 'object') {
      return $value = get_class($value);
    } 

    return;
  }

}
<?php

namespace ECidade\V3\Extension;

use ArrayAccess;
use \ECidade\V3\Extension\ReferenceBag;
use \ECidade\V3\Extension\ParameterBag;

class RequestBag extends ReferenceBag implements ArrayAccess {

  /**
   * @param string $key
   * @return mixed
   */
  public function __get($key) {
    return $this->get($key);
  }

  /**
   * @param string $key
   * @param mixed $value
   * @return ParameterBag
   */
  public function __set($key, $value) {
    return $this->set($key, $value);
  }

  public function __isset($key) {
    return $this->offsetExists($key);
  }

  /**
   * @param string | integer $offset
   * @return boolean
   */
  public function offsetExists($offset) {
    return array_key_exists($offset, $this->data);
  }

  /**
   * @param string | integer $offset
   * @return mixed
   */
  public function offsetGet($offset) {
    return $this->data[$offset];
  }

  /**
   * @param string | integer $offset
   * @param mixed $value
   * @return ParameterBag
   */
  public function offsetSet($offset, $value) {
    $this->data[$offset] = $value;
    return $this;
  }

  /**
   * @param string | integer $offset
   * @return ParameterBag
   */
  public function offsetUnset($offset) {
    unset($this->data[$offset]);
    return $this;
  }

}


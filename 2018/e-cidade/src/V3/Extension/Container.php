<?php

namespace ECidade\V3\Extension;

use RuntimeException;

/**
 * @package Extension
 */
class Container {
  
  /**
   * @var array
   */
  private $factory = array();

  /**
   * @var array
   */
  private $values = array();

  /**
   * @var array
   */
  private $immutable = array();

  /**
   * @param string $name
   * @param mixed $value
   * @return boolean
   */
  public function register($name, $value) {

    if (isset($this->immutable[$name])) {
      throw new RuntimeException("attempted re-registration of active component: {$name}");
    }

    $fn = $value;
    if (!is_callable($fn)) {
      $fn = function & ($container) use (& $value) {
        return $value;
      };
    }

    $this->factory[$name] = $fn;
    return true;
  }

  /**
   * @param string $name
   * @return mixed
   */
  public function & get($name) {

    if (!isset($this->values[$name])) {

      if (!isset($this->factory[$name])) {
        throw new RuntimeException("component not found: " . $name);
      }

      $factory = $this->factory[$name];
      $this->values[$name] = $factory($this);
      $this->immutable[$name] = true;
    }

    return $this->values[$name]; 
  }

  /**
   * @param string $name
   * @return boolean
   */
  public function has($name) {
    return isset($this->values[$name]) || isset($this->factory[$name]);
  }

  /**
   * @param string $name
   * @return boolean
   */
  public function isActive($name) {
    return isset($this->values[$name]);
  }

}

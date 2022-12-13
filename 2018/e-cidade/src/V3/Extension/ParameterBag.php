<?php

namespace ECidade\V3\Extension;

class ParameterBag implements \IteratorAggregate, \Countable {

  /**
   * Parameter storage.
   *
   * @var array
   */
  protected $data = array();

  /**
   * Constructor.
   *
   * @param array $data
   */
  public function __construct(array $data = array()) {
    $this->data = $data;
  }
  
  /**
   * Returns the data.
   *
   * @return array
   */
  public function all() {
    return $this->data;
  }

  /**
   * Returns the parameter keys.
   *
   * @return array
   */
  public function keys() {
    return array_keys($this->data);
  }

  /**
   * Replaces the current data by a new set.
   *
   * @param array
   * @return ParameterBag
   */
  public function replace(array & $data = array()) {
    $this->data = $data;
    return $this;
  }

  /**
   * Adds data.
   *
   * @param array $data 
   * @param bool $replace 
   * @return ParameterBag
   */
  public function add(array $data, $replace = false) {
    return $this->merge($data, $replace);
  }

  /**
   * Marge
   *
   * @param array $data 
   * @param bool $replace 
   * @return ParameterBag
   */
  public function merge(array $data, $replace = true) {
    $this->data = $replace ? array_merge($this->data, $data) : array_merge($data, $this->data);
    return $this;
  }

  /**
   * Returns a parameter by name.
   *
   * @param string $key 
   * @return mixed
   */
  public function get($key, $default = null) {
    return array_key_exists($key, $this->data) ? $this->data[$key] : $default;
  }

  /**
   * Sets a parameter by name.
   *
   * @param string $key
   * @param mixed $value
   * @return ParameterBag
   */
  public function set($key, $value) {
    $this->data[$key] = $value;
    return $this;
  }

  /**
   * Returns true if the parameter is defined.
   *
   * @param string $key The key
   * @return bool 
   */
  public function has($key) {
    return array_key_exists($key, $this->data);
  }

  /**
   * Returns true if the data contains.
   *
   * @param string $value
   * @return bool 
   */
  public function contains($value) {
    return in_array($value, $this->data);
  }

  /**
   * Removes a parameter.
   *
   * @param string $key
   * @return ParameterBag
   */
  public function remove($key) {
    unset($this->data[$key]);
    return $this;
  }

  /**
   * Returns an iterator for data.
   *
   * @return \ArrayIterator
   */
  public function getIterator() {
    return new \ArrayIterator($this->data);
  }

  /**
   * Returns the number of data.
   *
   * @return int
   */
  public function count() {
    return count($this->data);
  }

  /**
   * Returns if is empty
   * @param $key string
   * @return boolean
   */
  public function isEmpty($key = null) {
    if ($key === null) {
      $empty = empty($this->data);
    } else {
      $empty = empty($this->data[$key]);
    }
    return $empty;
  }

  /**
   * Returns content as JSON
   * @return string
   */
  public function toJSON() {
    return json_encode($this->all());
  }

  /**
   * @param object | array $object
   * @return ParameterBag
   */
  public static function fromObject($object) {

    $parameterBag = new static();

    foreach ($object as $key => $value) {

      if ( is_object($value) || is_array($value) ) {
        $value = static::fromObject( (object) $value);
      } 

      $parameterBag->set($key, $value);
    }

    return $parameterBag;
  }

}

<?php 
namespace PHP;

class ArrayDebugger extends \ArrayObject { 

  const TYPE_GET    = 'GET';
  const TYPE_SET    = 'SET';
  const TYPE_EXISTS = 'EXISTS';
  const TYPE_UNSET  = 'UNSET';

  private $logger;

  public function __construct() {

    $this->logger = function($data_log) {
      return dump($data_log);
    };

    return call_user_func_array('parent::__construct', func_get_args()); 
  }

  /**
   * Write log
   *
   * @param string $type
   * @param string $key
   * @param string $value
   * @param array  $backtrace
   */
  public function sendLog($type, $key, $value = null, array $backtrace ) {

    $logger           = $this->logger;
    $retorno['type']  = $type;  
    $retorno['key']   = $key;
    $retorno['value'] = $value;
    $retorno['file']  = isset($backtrace[0]['file']) ? $backtrace[0]['file'] : '';
    $retorno['line']  = isset($backtrace[0]['line']) ? $backtrace[0]['line'] : '';
    return $logger($retorno);
  }

  /**
   * Defines logger
   *
   * @param \Closure $logger
   */
  public function setLogger(\Closure $logger) {
    $this->logger = $logger;
  }

  /**
   * Called when getting a value from array
   *
   * @param mixed $name
   */
  public function offsetGet($name) { 

    $this->sendLog(static::TYPE_GET, $name, null, debug_backtrace());
    return call_user_func_array('parent::offsetGet', func_get_args()); 
  } 

  /**
   * Called when setting a value from array
   *
   * @param mixed $name
   * @param mixed $value
   */
  public function offsetSet($name, $value) { 

    $this->sendLog(static::TYPE_SET, $name, $value, debug_backtrace());
    return call_user_func_array('parent::offsetSet', func_get_args()); 
  } 

  /**
   * Called when checking a value from array 
   *
   * @param mixed $name
   */
  public function offsetExists($name) { 

    $this->sendLog(static::TYPE_EXISTS, $name, null, debug_backtrace());
    return call_user_func_array('parent::offsetExists', func_get_args()); 
  } 

  /**
   * Called when removing a value from array
   *
   * @param mixed $name
   */
  public function offsetUnset($name) { 

    $this->sendLog(static::TYPE_UNSET, $name, null, debug_backtrace());
    return call_user_func_array('parent::offsetUnset', func_get_args()); 
  } 
} 

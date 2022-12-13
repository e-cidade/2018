<?php

namespace ECidade\V3\Extension;

class Registry {

  private static $data = array();

  public static function has($key) {
    return array_key_exists($key, static::$data);
  }

  public static function set($key, $value) {
    static::$data[$key] = $value;
  }

  public static function get($key, $default = null) {
    return array_key_exists($key, static::$data) ? static::$data[$key] : $default;
  }

}

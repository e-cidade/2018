<?php

class DBConfig {

  const CONFIG_FILE = 'config/config.php';

  private $config = array();

  public function __construct() {
    $this->load();
  }

  private function load() {

    $distFile = static::CONFIG_FILE . ".dist";

    if (file_exists($distFile)) {
      $this->merge(require($distFile));
    }

    if (file_exists(static::CONFIG_FILE)) {
      $this->merge(require(static::CONFIG_FILE));
    }

  }

  private function merge($newConfig) {
    $this->config = DBArray::merge($this->config, $newConfig);
  }  

  public function get($key = null) {
    return isset($this->config[$key]) ? $this->config[$key] : $this->config;
  }

}
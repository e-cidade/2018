<?php 
namespace PHP;

class Log {

  private static $oInstance;

  private $oLogger; 

  private function __construct() {

    $this->oLogger = new \Monolog\Logger("PHPUtils");
    $this->oLogger->pushHandler( new \Monolog\Handler\StreamHandler("/tmp/PHPUtils.log") );
    $this->oLogger->pushHandler( new \Monolog\Handler\ChromePHPHandler() );
    $this->oLogger->pushHandler( new \Monolog\Handler\FirePHPHandler() );
  }

  public function log() {}

  public function debug() { }

  public function warn() { }

  public function info() { }

  public function error() {  }

}

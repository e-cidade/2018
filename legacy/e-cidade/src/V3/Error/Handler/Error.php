<?php

namespace ECidade\V3\Error\Handler;

use \ECidade\V3\Extension\Registry;
use \ECidade\V3\Error\Trace;
use \ECidade\V3\Error\EntityFactory;

class Error implements HandlerInterface {

  private $entity;

  public static function register() {
    return set_error_handler(array(__CLASS__, '_handle'), Registry::get('app.config')->get('php.error_reporting'));
  }

  /**
   * Function to explicit return false and populate php_errormsg
   */
  public static function _handle($type, $message, $file, $line, $context) {
    static::handle($type, $message, $file, $line, $context);
    return false;
  }

  public static function handle($type, $message, $file, $line, $context) {

    $suppress = error_reporting() === 0;

    if ($suppress) {
      return false;
    }

    $trace = new Trace();
    $trace->filter(function($trace) {
      if (!isset($trace['class'])) return true;
      return $trace['class'] !== __CLASS__;
    });

    $entity = EntityFactory::create($type, $suppress, $message, $file, $line, time(), $trace);

    Registry::get('app.eventManager')->trigger('app.error', null, array($entity));

    return $entity;
  }

}

<?php

namespace ECidade\V3\Error;

use \ECidade\V3\Event\Handler;
use \ECidade\V3\Extension\Logger;
use \Zend\EventManager\Event;
use \ECidade\V3\Extension\Registry;
use \ECidade\V3\Error\Entity;

class EventHandler extends Handler {

  public $config;

  public function __construct() {
    $this->config = Registry::get('app.config');
  }

  public function execute(Event $event) {

    $params = $event->getParams();
    $entity = $params[0];

    if (!$this->config->get('app.error.log', false)) {
      return false;
    }

    $logger = new Logger( $this->config->get('app.error.log.path'), Logger::ERROR);

    $logMessage = $this->formatMessage($entity);
    $logger->error($logMessage);

  }

  public function formatMessage(Entity $entity) {

    $mask = $this->config->get('app.error.log.mask');
    $traceMask = $this->config->get('app.error.log.mask.trace');
    $traces = '';

    $trace = $entity->getTrace();

    if ($trace) {

      foreach ($trace->getSanitizedData() as $index => $trace) {

        $args = array();
        if (!empty($trace['args'])) {
          foreach ($trace['args'] as $arg) {
            if (!is_scalar($arg)) $arg = print_r($arg, true);
            $args[] = $arg;
          }
        }

        $args = implode(', ', $args);

        $trace = strtr($traceMask, array(
          '{index}' => $index + 1,
          '{file}' => isset($trace['file']) ? $trace['file'] : '',
          '{line}' => isset($trace['line']) ? $trace['line'] : '',
          '{class}' => isset($trace['class']) ? $trace['class'] : '',
          '{function}' => isset($trace['function']) ? $trace['function'] : '',
          '{type}' => isset($trace['type']) ? $trace['type'] : '',
          '{args}' => $args,
        ));
        $traces .= $trace;
      }
    }

    $output = strtr($mask, array(
      '{type}' => $entity->getTypeAsString(),
      '{message}' => $entity->getMessage(),
      '{file}' => $entity->getFile(),
      '{line}' => $entity->getLine(),
      '{trace}' => $traces,
    ));

    return $output;
  }

}

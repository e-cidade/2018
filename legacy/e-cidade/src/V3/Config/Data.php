<?php

namespace ECidade\V3\Config;

use \ECidade\V3\Extension\AbstractMetadata;
use \ECidade\V3\Extension\Registry;
use \Extension;

class Data extends AbstractMetadata {

  private $events = array();

  public function __construct() {
    parent::__construct(ECIDADE_EXTENSION_DATA_PATH . 'config');
  }

  public function addEvent($event, $triggers) {

    if (!is_array($triggers)) {
      $triggers = array($triggers);
    }

    foreach ($triggers as $trigger) {
      $this->events[$trigger][$event] = $event;
    }
  }

  public function setEvents(Array $events) {
    $this->events = $events;
  }

  public function getEvents() {
    return $this->events;
  }

  public function removeEvent($remove) {

    foreach ($this->events as $trigger => $events) {
      foreach($events as $event) {

        if ($event != $remove) {
          continue;
        }

        unset($this->events[$trigger][$event]);

        if (empty($this->events[$trigger])) {
          unset($this->events[$trigger]);
        }
      }
    }
  }

  public function loadEvents() {

    $eventManager = Registry::get('app.eventManager');
    
    // config events
    foreach(Registry::get('app.config')->get('app.events', array()) as $event => $callback) {
      $eventManager->register($event, $this->normalizaCallable($callback) );
    }

    // data eventos
    foreach ($this->getEvents() as $event => $callbacks) {
      foreach ($callbacks as $callback) {
        $eventManager->register($event, $this->normalizaCallable($callback));
      }
    }

    return $this;
  }

  public function normalizaCallable($callable) {

    if (is_callable($callable)) {
      return $callable;
    }

    $class = new $callable;

    if (method_exists($class, 'execute')) {
      return array($class, 'execute');
    }

    throw new Exception(get_class($class) . ' não é um handler válido.');
  }

}

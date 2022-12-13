<?php

namespace ECidade\V3\Extension;

use Exception;
use \ECidade\V3\Extension\PackageMetadata;

class Data extends PackageMetadata {

  /**
   * @var array
   */
  private $modifications = array();

  /**
   * @var array
   */
  private $events = array();

  /**
   * @var string
   */
  private $manager;

  /**
   * @var string $path
   */
  public function __construct($path) {
    parent::__construct(ECIDADE_EXTENSION_DATA_PATH . 'extension/' . $path);
  }

  public function setModifications(array $modifications) {
    $this->modifications = $modifications;
    return $this;
  }

  public function addModification($id) {
    $this->modifications[] = $id;
  }

  public function getModifications() {
    return $this->modifications;
  }

  public function hasModifications() {
    return !empty($this->modifications);
  }

  public function setEvents(array $events) {
    $this->events = $events;
    return $this;
  }

  public function addEvent($event, $triggers) {

    if (!is_array($triggers)) {
      $triggers = array($triggers);
    }

    foreach ($triggers as $trigger) {
      $this->events[$trigger][$event] = $event;
    }
    return $this;
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
    return $this;
  }

  public function hasEvents() {
    return !empty($this->events);
  }

  public function getEvents() {
    return $this->events;
  }

  public function setManager($manager) {
    $this->manager = $manager;
    return $this;
  }

  public function getManager() {
    return $this->manager;
  }

  public function hasManager() {
    return !empty($this->manager);
  }

}

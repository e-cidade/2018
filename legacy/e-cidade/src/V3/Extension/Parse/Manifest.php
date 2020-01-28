<?php

namespace ECidade\V3\Extension\Parse;

use DOMDocument, Exception;
use \ECidade\V3\Modification\Manager;
use \ECidade\V3\Extension\Registry;

/**
 * Parse do arquivo manifest
 */
class Manifest extends XML {

  /**
   * @param integer
   */
  private $id;

  /**
   * @param integer
   */
  private $version;

  /**
   * @param string
   */
  private $type;

  /**
   * @param string
   */
  private $manager;

  /**
   * @param array
   */
  private $modifications = array();

  /**
   * @param array
   */
  private $events = array();

  /**
   * @return integer
   */
  public function getId() {
    return $this->id;
  }

  /**
   * @return integer
   */
  public function getVersion() {
    return $this->version;
  }

  /**
   * @return string
   */
  public function getType() {
    return $this->type;
  }

  /**
   * @return string
   */
  public function getManager() {
    return $this->manager;
  }

  /**
   * @return array
   */
  public function getModifications() {
    return $this->modifications;
  }

  /**
   * @return array
   */
  public function getEvents() {
    return $this->events;
  }

  /**
   * @return \ECidade\V3\Exception\Parse\Manifest
   */
  public function parse() {

    $this->id = null;
    $this->version = null;
    $this->manager = null;
    $this->type = 'global';
    $this->modifications = array();
    $this->events = array();

    $nodeId = $this->dom->getElementsByTagName('id')->item(0);
    $nodeVersion = $this->dom->getElementsByTagName('version')->item(0);
    $nodeType = $this->dom->getElementsByTagName('type')->item(0);
    $nodeModifications = $this->dom->getElementsByTagName('modifications')->item(0);
    $nodeEvents = $this->dom->getElementsByTagName('events')->item(0);
    $nodeManager = $this->dom->getElementsByTagName('manager')->item(0);

    if (empty($nodeId)) {
      throw new Exception("ID da extensão não informado");
    }

    $this->id = $nodeId->textContent;

    if (!empty($nodeVersion)) {
      $this->version = $nodeVersion->textContent;
    }

    if (!empty($nodeType)) {
      $this->type = $nodeType->textContent;
    }

    if (!empty($nodeModifications)) {

      $modificationManager = new Manager();
      $modifications = array();

      foreach ($nodeModifications->getElementsByTagName('modification') as $nodeModification) {

        $pathXml = ECIDADE_EXTENSION_PACKAGE_PATH . $this->id .'/'. $nodeModification->getAttribute('path');
        $modifications[] = $modificationManager->unpack($pathXml, true)->getId();
      }

      $this->modifications = $modifications;
    }

    if (!empty($nodeEvents)) {

      foreach ($nodeEvents->getElementsByTagName('event') as $nodeEvent) {

        $event = $nodeEvent->getAttribute('class');
        $trigger = $nodeEvent->getAttribute('trigger');

        if (!class_exists($event) && false === Registry::get('app.loader')->loadClass($event)) {
          throw new Exception('Arquivo do evento não encontrado: '. $event);
        }

        $this->events[$trigger][$event] = $event;
      }
    }

    if (!empty($nodeManager)) {

      $manager = $nodeManager->getAttribute('class');

      // dump($manager);
      if (false === Registry::get('app.loader')->loadClass($manager)) {
        throw new Exception('Arquivo do manager não encontrado: '. $manager);
      }

      $this->manager = $manager;
    }

    return $this;
  }

}

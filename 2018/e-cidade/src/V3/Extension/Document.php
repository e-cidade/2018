<?php

namespace ECidade\V3\Extension;

use Exception;

class Document {

	private $title;
  private $base;
  private $charset = 'UTF-8';
	private $links = array();
	private $scripts = array();

	public function setTitle($title) {
		$this->title = $title;
	}

	public function getTitle() {
		return $this->title;
	}

  public function setCharset($charset) {
    $this->charset = $charset;
  }

  public function getCharset() {
    return $this->charset;
  }

  public function setBase($path) {
    $this->base = rtrim($path, DS) . DS;
  }

  public function getBase() {
    return $this->base;
  }

  public function addLink($href, array $data = array()) {
    $data['href'] = $href;
    $this->links[$href] = $data;
  }

	public function getLinks() {
		return $this->links;
	}

  public function addScript($src, array $data = array()) {
    $data['src'] = $src;
    $this->scripts[$src] = $data;
  }

	public function getScripts() {
		return $this->scripts;
	}

  public function renderScripts() {

    $html = null;
    foreach ($this->scripts as $src => $data) {
      $html .= sprintf('<script%s></script>', $this->dataToProperties($data));
    }
    return $html;
  }

  public function renderLinks() {

    $html = null;
    foreach ($this->links as $href => $data) {
      $html .= sprintf('<link%s />', $this->dataToProperties($data));
    }
    return $html;
  }

  public function renderBase() {
    return $this->base ? sprintf('<base href="'. $this->base .'" />') : null;
  }

  private function dataToProperties($data) {

    $properties = null;
    foreach ($data as $key => $value) {
      $properties .= " $key='$value'";
    }
    return $properties;
  }

  /**
   * empacota todos os js/css em um arquivo unico
   */
  public function packageLinks($name) {}
  public function packageScripts($name) {} 

}

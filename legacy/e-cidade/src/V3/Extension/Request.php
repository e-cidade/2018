<?php

namespace ECidade\V3\Extension;

use Exception;

use \ECidade\V3\Extension\RequestBag;
use \ECidade\V3\Extension\ParameterBag;

class Request {

  /**
   * @var string
   */
  private $uri;

  /**
   * @var RequestBag
   */
  private $get;

  /**
   * @var RequestBag
   */
  private $post;

  /**
   * @var RequestBag
   */
  private $server;

  /**
   * @var RequestBag
   */
  private $cookie;

  /**
   * @var RequestBag
   */
  private $session;

  /**
   * @var ParameterBag
   */
  private $params;

  /**
   * Nome da extensao
   *
   * @var string
   */
  private $extension;

  /**
   * Namespace controller
   *
   * @var string
   */
  private $controller;

  /**
   * Caminho do arquivo do controller
   *
   * @var string
   */
  private $controllerPath;

  /**
   * @var string
   */
  private $action;

  /**
   * @var string
   */
  private $accept;

  /**
   * @var bool
   */
  private $isExtension = false;

  /**
   * @var bool
   */
  private $isAsset = false;

  public function __construct($uri = null) {
    $this->uri = $uri;
  }

  /**
   * @param ParameterBag $get
   * @return ParameterBag
   */
  public function get(ParameterBag $get = null) {

    if ($get !== null) {
      $this->get = $get;
    }

    if ($this->get == null) {
      $this->get = new RequestBag($_GET);
    }

    return $this->get;
  }

  /**
   * @param ParameterBag $post
   * @return ParameterBag
   */
  public function post(ParameterBag $post = null) {

    if ($post !== null) {
      $this->post = $post;
    }

    if ($this->post == null) {
      $this->post = new RequestBag($_POST);
    }

    return $this->post;
  }

  /**
   * @param ParameterBag $server
   * @return ParameterBag
   */
  public function server(ParameterBag $server = null) {

    if ($server !== null) {
      $this->server = $server;
    }

    if ($this->server == null) {
      $this->server = new RequestBag($_SERVER);
    }

    return $this->server;
  }

  /**
   * @param ParameterBag $cookie
   * @return ParameterBag
   */
  public function cookie(ParameterBag $cookie = null) {

    if ($cookie !== null) {
      $this->cookie = $cookie;
    }

    if ($this->cookie == null) {
      $this->cookie = new RequestBag($_COOKIE);
    }

    return $this->cookie;
  }

  /**
   * @param ParameterBag $session
   * @return ParameterBag
   */
  public function session(ParameterBag $session = null) {

    if ($session !== null) {
      $this->session = $session;
    }

    if ($this->session == null) {
      $this->session = new Session();
    }

    return $this->session;
  }

  /**
   * @param ParameterBag $get
   * @return ParameterBag
   */
  public function params(ParameterBag $param = null) {

    if ($param !== null) {
      $this->params = $param;
    }

    if ($this->params == null) {
      $this->params = new ParameterBag();
    }

    return $this->params;
  } 

  public function setUri($uri) {
    return $this->uri = $uri;
  }

  public function getUri() {
    return $this->uri;
  }

  public function isExtension($isExtension = null) {

    if (!is_null($isExtension)) {
      $this->isExtension = $isExtension;
    }
    return $this->isExtension;
  }

  public function isAsset($isAsset = null) {

    if (!is_null($isAsset)) {
      $this->isAsset = $isAsset;
    }
    return $this->isAsset;
  }

  public function parseAccept() {

    $header = explode(',', $this->server()->get('HTTP_ACCEPT') );
    $this->accept = current($header);
    return $this->accept;
  }

  public function accept($type) {

    if (!$this->accept) {
      $this->parseAccept();
    }

    return $this->accept == $type;
  }

  public function setExtension($extension) {
    $this->extension = $extension;
  }

  public function getExtension() {
    return $this->extension;
  }

  public function setControllerPath($controllerPath) {
    $this->controllerPath = $controllerPath;
  }

  public function getControllerPath() {
    return $this->controllerPath;
  }

  public function setController($controller) {
    $this->controller = $controller;
  }

  public function getController() {
    return $this->controller;
  }

  public function setAction($action) {
    $this->action = $action;
  }

  public function getAction() {
    return $this->action;
  }

}

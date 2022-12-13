<?php

namespace ECidade\V3\Extension;

use \Exception;
use \ECidade\V3\Extension\Controller;
use \ECidade\V3\Extension\Document;

class View {

  private $controller;
  public $document;

  public function __construct(Controller $controller, Document $document) {

    $this->controller = $controller;
    $this->document = $document;
    $this->request = $controller->getRequest();
    $this->response = $controller->getResponse();

    $this->document->setCharset($this->response->getCharset());

    $base = defined('ECIDADE_CURRENT_EXTENSION_REQUEST_PATH') ? ECIDADE_CURRENT_EXTENSION_REQUEST_PATH : ECIDADE_REQUEST_PATH;

    $this->document->setBase($base);
  
  }

  public function render($pathView = null, $params = array()) {

    if (empty($pathView)) {
      $pathView = basename(str_replace("\\", '/', $this->request->getController())) . '/' . $this->request->getAction();
    }
   
    $path = null;

    if (defined('ECIDADE_CURRENT_EXTENSION_PATH')) {
      $path = ECIDADE_CURRENT_EXTENSION_PATH . "views/$pathView.php";
    }

    /**
     * @todo - mudar diretorio das view e dos controllers da extension, tirar do vendor/ por em extension/view
     */
    if (empty($path) || !file_exists($path) ) {
      $path = ECIDADE_PATH . "src/V3/Extension/View/$pathView.php";
    }

    if (!file_exists($path) ) {
      throw new Exception("Caminho da view não encontrado: ". ECIDADE_CURRENT_EXTENSION_PATH . "views/$pathView.php");
    }

    ob_start();

    extract($params);
    require_once($path);

    $this->response->setBody(ob_get_contents());
    ob_clean();
  }

}

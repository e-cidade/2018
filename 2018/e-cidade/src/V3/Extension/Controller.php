<?php
namespace ECidade\V3\Extension;

use Exception;
use \ECidade\V3\Extension\Request;
use \ECidade\V3\Extension\Response;
use \ECidade\V3\Extension\View;

class Controller {

  protected $request;
  protected $response;
  protected $view;

  public function beforeAction() {}

  public function setRequest(Request $request) {
    $this->request = $request;
  }

  public function getRequest() {
    return $this->request;
  }

  public function setResponse(Response $response) {
    $this->response = $response;
  }

  public function getResponse() {
    return $this->response;
  }

  public function setView(View $view) {
    $this->view = $view;
  }
  
  public function getView() {
    return $this->view;
  }

  public function render($view = null, $params = array()) {
    return $this->view->render($view, $params);
  }

  public function redirect($action) {
    $this->response->redirect(ECIDADE_CURRENT_EXTENSION_REQUEST_PATH . $action);    
  }

}

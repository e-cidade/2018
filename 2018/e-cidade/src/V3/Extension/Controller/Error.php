<?php

namespace ECidade\V3\Extension\Controller;

use \ECidade\V3\Extension\Controller;
use \ECidade\V3\Error\Entity;

/**
 * @todo - mudar diretorio das view e dos controllers da extension, tirar do vendor/ por em extension/view
 */
class Error extends Controller {

  public function index(Entity $entity) {

    $debug = $this->request->session()->get('DB_DEBUG', false);
    $message = $entity->getMessage();
    $htmlMessage = str_replace("\n", '<br />', $message);    

    if ($this->request->accept('application/json')) {
      return $this->response->setBody(array('message' => $entity->getMessage()));
    }

    // erro nao eh excesao e debug desativado 
    if (!$debug && $entity->getCode() !== Entity::CODE_EXCEPTION) {
      $htmlMessage = 'Erro interno';
    }

    // @todo - disparar evento para formatar mensagem | redirect to debug | lib formatter
    if ($debug) {
      //$htmlMessage =\ECidade\Package\DBug\Library\Formatter::exception($entity);
      $htmlMessage = $entity->getMessage();
    }

    $this->view->entity = $entity;
    $this->view->htmlMessage = $htmlMessage;  

    $this->render('Error/index');    
  }

}

<?php

namespace ECidade\Package\DBug\Event;

class Bootstrap extends \ECidade\V3\Event\Handler {

  /**
   * Event handler
   * @param  mixed $controller Only on event 'extension.desktop.bootstrap'
   */
  public function execute(\Zend\EventManager\Event $event) {

    $params = $event->getParams();
    $controller = $params[0];
    
    $controller->getView()->document->addScript(
      ECIDADE_REQUEST_PATH . 'extension/DBug/assets/js/dbug.js', array('type' => 'text/javascript')
    );

    $controller->getRequest()->session()->set('DB_DEBUG', true);
  }

}

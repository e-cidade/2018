<?php

namespace ECidade\V3\Error;

use \ECidade\V3\Extension\Registry;
use \ECidade\V3\Extension\View;
use \ECidade\V3\Extension\Document;

use \ECidade\V3\Error\Entity;
use \ECidade\V3\Extension\Controller\Error;

class Renderer {

  public static function render(Entity $entity) {

    $response = Registry::get('app.response');
    $request = Registry::get('app.request'); 

    if (!$response || $response->hasSend()) {
      return false;
    }

    $controller = new Error(); 
    $controller->setRequest($request);
    $controller->setResponse($response);
    $controller->setView(new View($controller, new Document()));

    $result = $controller->index($entity);
    $response->output();
  }

}
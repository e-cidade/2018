<?php

namespace ECidade\V3\Extension;

use Exception;
use \ECidade\V3\Extension\Request;

class Router {

  public function __construct(Request $request) {

    $uri = explode('/', trim($request->getUri(), '/'));
    $extension = ucfirst(array_shift($uri)); 

    /**
     * URI nao é uma extensao
     */
    if (empty($extension) || !is_dir(ECIDADE_EXTENSION_PACKAGE_PATH . $extension)) {
      return;
    }

    $request->setExtension($extension);
    $request->isExtension(true);

    /**
     * Verifica se é um asset
     */
    $sExtensao = pathinfo($request->getUri(), PATHINFO_EXTENSION);
    if (!empty($sExtensao) && $sExtensao != 'php') {
      $request->isAsset(true);
    } 

    $controllerPath = ECIDADE_EXTENSION_PACKAGE_PATH . ucfirst($extension) . '/Controller/';
    $controller = '\\ECidade\\Package\\' . ucfirst($extension) . '\\Controller\\';
    $action = 'index';

    /**
     * Usa controller padrao
     */
    if (empty($uri)) {

      $controller = $controller . ucfirst($extension);
      $controllerPath .= ucfirst($extension) .'.php';
    } 

    /**
     * Busca controller e action
     */
    foreach ($uri as $indice => $part) {

      $controller .= ucfirst($part);
      $controllerPath .= ucfirst($part);

      if (is_file($controllerPath . '.php')) {

        unset($uri[$indice]); 
        $controllerPath .= '.php';

        if (isset($uri[$indice + 1])) {

          $action = $uri[$indice + 1];
          unset($uri[$indice + 1]);
        }

        break;
      }

      if (is_dir($controllerPath)) {

        $controllerPath .= '/';
        $controller .= '//';
        unset($uri[$indice]); 
      }

    }  

    $request->params()->replace($_GET);
    $request->setControllerPath($controllerPath);
    $request->setController($controller);
    $request->setAction($action);
    $request->setUri(implode('/', $uri));
  }

}

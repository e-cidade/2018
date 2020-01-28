<?php

namespace ECidade\V3\Extension;

use \ECidade\V3\Extension\Exceptions\ResponseException;
use \ECidade\V3\Extension\Request;
use \ECidade\V3\Extension\Response;
use \ECidade\V3\Extension\Controller;
use \ECidade\V3\Extension\Document;
use \ECidade\V3\Extension\View;
use \ECidade\V3\Extension\Data as ExtensionData;

class Dispatcher {

  /**
   * @param Request $request
   * @param Response $response
   */
  public function execute(Request $request, Response $response) {

    $response->setContentType($request->parseAccept());

    // Carrega asset
    if ($request->isAsset()) {

      $request->session()->close();
      return $this->loadAsset($request, $response);
    }

    $extensionData = ExtensionData::restore($request->getExtension());

    if (false === $extensionData->exists()) {
      throw new ResponseException("Extensão não instalada: " . $request->getExtension());
    }

    if (false === $extensionData->isEnabled($request->session()->get('DB_login'))) {
      throw new ResponseException("Extensão desativada: " . $request->getExtension());
    }

    if (!file_exists($request->getControllerPath()) || is_dir($request->getControllerPath())) {
      throw new ResponseException("Arquivo do Controller não encontrado: ". $request->getControllerPath());
    }

    $class = $request->getController();
    $action = $request->getAction();

    if (!class_exists($class)) {
      throw new ResponseException("Controller inválido: " . $class);
    }

    if (!method_exists($class, $action)) {
      throw new ResponseException("Action inválida: ". $class ."::". $action);
    }

    $controller = new $class();

    if (!($controller instanceof Controller)) {
      throw new ResponseException("Controller inválido: " . $class);
    }

    $controller->setRequest($request);
    $controller->setResponse($response);
    $controller->setView(new View($controller, new Document()));

    call_user_func_array(array($controller, 'beforeAction'), $request->params()->all());
    $result = call_user_func_array(array($controller, $action), $request->params()->all());

    if (!$response->hasBody()) {
      $response->setBody($result);
    }

  }

  /**
   * @param Request $request
   * @param Response $response
   * @return void
   */
  public function loadAsset(Request $request, Response $response) {

    // Diretorio da extensao atual
    $filePath = ECIDADE_CURRENT_EXTENSION_PATH . $request->getUri();

    // Nao encontrou asset no diretorio da extensao atual, procura na raiz
    if (!file_exists($filePath)) {
      $filePath = ECIDADE_PATH . $request->getUri();
    }

    // Nao encontrou arquivo no diretorio da extensao nem na raiz
    if (!file_exists($filePath)) {
      throw new ResponseException('Arquivo não encontrado: ' . $filePath, 404);
    }

    $response->setFile($filePath);
  }

}

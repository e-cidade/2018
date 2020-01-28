<?php

namespace ECidade\V3\Error\Handler;

use \ECidade\V3\Extension\Registry;
use \ECidade\V3\Error\Renderer;

class Shutdown implements HandlerInterface {

  public static function register() {
    return register_shutdown_function(array(__CLASS__, 'handle'));
  }

  public static function handle() {

    $error = error_get_last();
    // @todo verificar se uma exception passa por aqui e pelo Hanlder\Exception
    if (empty($error) || !($error['type'] & E_FATAL)) {
      return;
    }

    $entity = Error::handle($error['type'], $error['message'], $error['file'], $error['line'], array());

    Registry::get('app.eventManager')->trigger('app.shutdown');

    Renderer::render($entity);

    return $entity;
  }

}

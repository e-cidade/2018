<?php
namespace ECidade\Api\V1\Providers;

use Silex\Application;
use Silex\ControllerProviderInterface;
use ECidade\Api\V1\Controllers\Protocolo\Cgm;

/**
 * Class ProtocoloControllerProvider
 * @package ECidade\Api\V1\Providers
 */
class ProtocoloControllerProvider implements ControllerProviderInterface {

  public function connect(Application $app)
  {

    $app["cgm.controller"] = $app->share(function() use ($app) {
      return new Cgm($app["request"]);
    });

    // creates a new controller based on the default route
    $controllers = $app['controllers_factory'];

    $controllers->get('/cgm', "cgm.controller:getAll");
    $controllers->get('/cgm/{id}', "cgm.controller:get")->assert('id', '\d+');

    return $controllers;
  }
}

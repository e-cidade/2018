<?php
namespace ECidade\Api\V1\Providers;

use Silex\Application;
use Silex\ControllerProviderInterface;
use ECidade\Api\V1\Controllers\Configuracao\Formulario;


class ConfiguracaoControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app)
    {

        $app["formularios.controller"] = $app->share(function () use ($app) {
            return new Formulario($app["request"]);
        });

        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];
        $controllers->get('/formulario/{id}/instituicao/{instituicao}', "formularios.controller:getAll");

        return $controllers;
    }
}

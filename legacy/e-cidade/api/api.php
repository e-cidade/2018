<?php

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Silex\Provider\ServiceControllerServiceProvider;
use \ECidade\V3\Extension\Registry;
use \ECidade\V3\Extension\Front;
use \ECidade\V3\Extension\Request as EcidadeRequest;


require_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php');

// @todo Revisar essa logica
// Criamos um request fake para poder utilizar o recursos dos modifications.

$_SERVER['REQUEST_URI'] = preg_replace('/(.*?)\/w\/\d+(.*)/', '$1$2', $_SERVER['REQUEST_URI']);

$front = new Front();
$ecidadeRequest = new EcidadeRequest($front->getPath());
Registry::set('app.request', $ecidadeRequest);
$front->createWindow();

$app = new Application();

$app['debug'] = true;

$app['class.loader'] = Registry::get('app.loader');

// aplica o service provider do ServiceController
$app->register(new ServiceControllerServiceProvider());

// app authentication
$app->before(function (Request $request, Application $app) {

  require_once modification("libs/db_stdlib.php");
  Registry::get('app.request')->session()->start();

  /**
   * @see https://tools.ietf.org/html/rfc7235#section-3.1
   */
  if (empty($_SESSION) || empty($_SESSION['DB_login'])) {
    throw new AccessDeniedHttpException('Sessão inválida ou expirada. Tente logar novamente.');
  }

  $DB_SERVIDOR = db_getsession("DB_servidor");
  $DB_BASE     = db_getsession("DB_base");
  $DB_PORTA    = db_getsession("DB_porta");
  $DB_USUARIO  = db_getsession("DB_user");
  $DB_SENHA    = db_getsession("DB_senha");

  global $conn;
  $conn = pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA");

});

// app api version1 routes
$app->register(new \ECidade\Api\V1\APIServiceProvider(), array(
  'ecidade_api.mount_prefix' => '/api/v1'
));

// app error handling
$app->error(function (\Exception $e, $code) use ($app) {

    $response = array(
      "statusCode" => $code,
      "message" => \DBString::utf8_encode_all($e->getMessage())
    );

    if ($app['debug']) {
      $response["stacktrace"] = $e->getTraceAsString();
    }

    return new JsonResponse($response);
});

$app->run();

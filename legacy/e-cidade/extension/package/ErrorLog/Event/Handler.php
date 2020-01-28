<?php

namespace ECidade\Package\ErrorLog\Event;

use \ECidade\V3\Error\Entity;
use \ECidade\V3\Event\Handler as EventHandler;
use \ECidade\V3\Extension\Registry;

class Handler extends EventHandler {

  public function execute(\Zend\EventManager\Event $event ) {

    $params = $event->getParams();
    $entity = $params[0];

    $request = Registry::get('app.request');
    $error = $entity->toArray();

    if ($request) {

      $error['session'] = array(
        'DB_login'             => $request->session()->get('DB_login'),
        'DB_id_usuario'        => $request->session()->get('DB_id_usuario'),
        'DB_administrador'     => $request->session()->get('DB_administrador'),
        'DB_ip'                => $request->session()->get('DB_ip'),
        'DB_base'              => $request->session()->get('DB_base'),
        'DB_NBASE'             => $request->session()->get('DB_NBASE'),
        'DB_servidor'          => $request->session()->get('DB_servidor'),
        'DB_porta'             => $request->session()->get('DB_porta'),
        'DB_user'              => $request->session()->get('DB_user'),
        'DB_uol_hora'          => $request->session()->get('DB_uol_hora'),
        'DB_itemmenu_acessado' => $request->session()->get('DB_itemmenu_acessado'),
        'DB_SELLER'            => $request->session()->get('DB_SELLER'),
        'DB_instit'            => $request->session()->get('DB_instit'),
        'DB_COMPLEMENTAR'      => $request->session()->get('DB_COMPLEMENTAR'),
        'DB_totalmodulos'      => $request->session()->get('DB_totalmodulos'),
        'DB_use_pcasp'         => $request->session()->get('DB_use_pcasp'),
        'DB_Area'              => $request->session()->get('DB_Area'),
        'DB_modulo'            => $request->session()->get('DB_modulo'),
        'DB_nome_modulo'       => $request->session()->get('DB_nome_modulo'),
        'DB_anousu'            => $request->session()->get('DB_anousu'),
        'DB_datausu'           => $request->session()->get('DB_datausu'),
        'DB_coddepto'          => $request->session()->get('DB_coddepto'),
        'DB_nomedepto'         => $request->session()->get('DB_nomedepto'),
        'DB_ano_pcasp'         => $request->session()->get('DB_ano_pcasp'),
      );

      $error['server'] = array(
        'REDIRECT_STATUS' => $request->server()->get('REDIRECT_STATUS'),
        'HTTP_HOST' => $request->server()->get('HTTP_HOST'),
        'HTTP_USER_AGENT' => $request->server()->get('HTTP_USER_AGENT'),
        'HTTP_REFERER' => $request->server()->get('HTTP_REFERER'),
        'QUERY_STRING' => $request->server()->get('QUERY_STRING'),
        'REQUEST_URI' => $request->server()->get('REQUEST_URI'),
      );

      if (($entity->getType() & E_FATAL)) {
        $error['get'] = $request->get()->all();
        $error['post'] = $request->post()->all();
      }

      $error['cookie'] = $request->cookie()->all();

    }

    $error['type'] = $entity->getTypeAsString();

    $magicQuotes = ini_get('magic_quotes_gpc');

    array_walk_recursive($error, function(&$value, $key) use ($magicQuotes) {
      $value = utf8_encode($value);
      if ($magicQuotes) {
        $value = stripslashes($value);
      }
    });

    $output = json_encode($error);

    if (empty($output)) {
      return false;
    }

    file_put_contents(ECIDADE_EXTENSION_LOG_PATH . 'error.data', $output . PHP_EOL, FILE_APPEND | LOCK_EX);
  }

}

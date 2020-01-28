<?php
namespace ECidade\V3\Window;

use \Exception;
use \ECidade\V3\Extension\Exceptions\ResponseException;
use \ECidade\V3\Extension\Registry;

class Session extends \ECidade\V3\Extension\Session {

  private $name;
  private $parentName;

  const PREFIX = 'ECIDADEWINDOW';
  const MAIN_NAME = 'ECIDADEWINDOWMAIN';

  public function __construct($name) {

    $this->name = static::PREFIX . '' . $name;
    $this->setup(); 
  }

  public function isMain() {
    return $this->name == static::MAIN_NAME;
  }

  protected function setup() {

    if (!ini_get("session.use_cookies")) {
      throw new ResponseException('A diretiva de cookies para sessão não está habilitada nas configurações do PHP.');
    }

    if (ini_get('suhosin.session.encrypt')) {
      throw new ResponseException('Sistema não é compatível com a criptografia de sessão da extensão Suhosin.');
    }
    
    $currentCookieParams = session_get_cookie_params(); 
    session_set_cookie_params(
      $currentCookieParams["lifetime"],
      ECIDADE_REQUEST_ROOT,
      $currentCookieParams["domain"],
      $currentCookieParams["secure"],
      $currentCookieParams["httponly"]
    );
    
  }

  /**
   * @todo - guardar id das sessoes criadas para usar no metodo destroyAll
   * @return \ECidade\V3\Window\Session
   */
  public function create() {

    // Sessao ja criada, utiliza
    if (isset($_COOKIE[$this->name])) {

      $this->name($this->name);
      $this->id($_COOKIE[$this->name]);
      return $this;
    } 

    // sessao base
    if (!$this->isMain() && isset($_COOKIE[static::MAIN_NAME])) {

      // starta sessao base
      $this->close();
      $this->name(static::MAIN_NAME);
      $this->id($_COOKIE[static::MAIN_NAME]);
      $this->start();

      // dados da sessao
      $base = $this->all();
    }

    // Cria uma nova sessao
    $this->close();
    $this->name($this->name);
    $this->id(sha1(mt_rand()));
    $this->start();

    // copia sessao base
    if (isset($base)) {
      $_SESSION = $base;
    }

    $this->close();
    return $this;
  }

  public static function iterateAll($callback) {

    $restart = isset($_SESSION);
    $currenteSessionName = session_name(); 
    $currenteSessionId = session_id(); 
    session_write_close();

    // Limpa as sessoes e cookies criados
    foreach ($_COOKIE as $key => $value) {

      if (strpos($key, static::PREFIX) !== 0) {
        continue;
      } 

      session_name($key);
      session_id($value);
      session_start();

      $callback($key, $value);

      session_write_close();
    }

    if (!empty($currenteSessionName)) {
      session_name($currenteSessionName);
    }

    if (!empty($currenteSessionId)) {
      session_id($currenteSessionId);
    }

    if ($restart) {
      session_start();
    }

    return true;
  }

  public static function update($_name, Array $data) {
  
    $updated = false;
    return Session::iterateAll(function($name, $id) use ($data, $_name, & $updated) {

      if ($name !== $_name) {
        return false;
      } 

      foreach ($data as $key => $value) {

        // indice da sessao nao pode ser numeric 
        // @todo - save log | throw exception | trigger error
        if (is_numeric($key)) {
          throw new Exception('Erro ao atualizar sessão.');
        }

        $updated = true;
        $_SESSION[$key] = $value;
      }
    });

    return $updated;
  }

  public static function destroyAll() {

    Session::iterateAll(function($name, $id) {

      if (strpos($name, Session::PREFIX) !== 0 || $name == Session::MAIN_NAME) {
        return false;
      } 

      $params = session_get_cookie_params();
      setcookie($name, '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
      unset($_COOKIE[$id]);
      session_destroy();
      session_write_close();
    });

    if ( Registry::has('app.request') ) {
      Registry::get('app.request')->session()->replace($_SESSION);
    }

  }

}

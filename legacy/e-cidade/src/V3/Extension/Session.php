<?php
namespace ECidade\V3\Extension;

use Exception;
use \ECidade\V3\Extension\RequestBag;

/**
 * Constrole de sessao
 */
class Session extends RequestBag {

  /**
   * @const Sessao desabilidada
   * @var integer
   */
  const DISABLED = 0;

  /**
   * @const Sessao nao iniciada
   * @var integer
   */
  const NONE = 1;

  /**
   * @const Sessao iniciada
   * @var integer
   */
  const ACTIVE = 2;

  /**
   * status atual da sessao
   * @var integer
   */
  private $status = Session::NONE;

  /**
   * @var boolean
   */
  private $writeable = true;
  
  /**
   * @param integer $id
   * @return integer | Session
   */
  public function id($id = null) {

    if (!is_null($id)) {
      session_id($id);
      return $this;
    }
		return session_id();
  }

  /**
   * @param string $name
   * @return string | Session
   */
  public function name($name = null) {

    if (!is_null($name)) {
      session_name($name);
      return $this;
    }
		return session_name();
  }

  /**
   * @return Session
   */
	public function destroy() {

    if ($this->writeable()) {
	    session_destroy();
    }
    return $this;
	}

  /**
   * Fecha arquivo da sessao, para escrita no arquivo
   *  - modificacoes na sessao apos fechada, nao serao salvas
   * @return Session
   */
	public function close() {

    if ($this->writeable()) {
      session_write_close();
    }
    $this->status = Session::NONE;
    return $this;
	}

  /**
   * @return array
   */
  private function getCurrentSessionData() {
  
    $result = array();
    $path = session_save_path() . DS . 'sess_' . $this->id();

    // @todo - guardar log de erro
    if (!is_readable($path)) {
      return $result;
    }

    // conteudo da sessao
    $content = file_get_contents($path);

    // guardamos o id e name para restaurar mais a frente
    $name = session_name();
    $id = session_id();

    // Criamos uma sessão "fake"
    // somente para utilizar o session_decode e restaurar os dados da sessão do usuário
    session_name('w' . (string) mt_rand());
    session_id(uniqid());
    session_start();
    session_decode($content);
    $result = $_SESSION;

    // após o session_decode destruimos a sessao fake, como se nada tivesse acontecido
    unset($_SESSION);
    session_destroy();

    // removemos o cookie para nao ser repassado ao browser
    header_remove('Set-Cookie');

    // restauramos o id e name antigo
    session_name($name);
    session_id($id);

    return $result;
  }

  /**
   * Inicia sessao
   * @return Session
   */
  public function start() {

    // read-only
    if (!$this->writeable() && $this->status === Session::NONE) {
      
      $_SESSION = $this->getCurrentSessionData();
      $this->replace($_SESSION);
      $this->status = Session::ACTIVE;

      return $this;
    }

    if ($this->status === Session::NONE || !isset($_SESSION)) {

      if (headers_sent($file, $line)) {
        throw new Exception("Erro ao iniciar sessão, cabeçalhos da requisição já enviadados no arquivo $file:$line.");
      }

      session_start();
      $this->data =& $_SESSION;
      $this->status = Session::ACTIVE;
    }

    return $this;
  }

  /**
   * @return integer
   */
  public function status() {
    return $this->status;
  }

  /**
   * @return boolean
   */
  public function writeable($writeable = null) {

    if ($writeable === null) {
      return $this->writeable;
    }

    $this->writeable = (boolean) $writeable;
    return $this;
  }

  /**
   * @todo - mover html para class Document
   *
   * Set or retrieve flash message from session
   * @param  mixed $message Flash content
   * @return mixed          Flash content
   */
  public function flash($message = null, $type = '') {

    if (empty($message)) {
      $flash = $this->get('flash');
      $this->remove('flash');
      return $flash;
    }

    return $this->set('flash', "<p class='flash $type'>" . $message . "</p>");
  }

}

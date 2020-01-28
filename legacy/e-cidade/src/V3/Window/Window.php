<?php
namespace ECidade\V3\Window;

class Window {

  /**
   * @var integer
   */
  private $id;

  /**
   * @var \ECidade\V3\Window\Session
   */
  private $session;

  /**
   * @param integer $id
   * @return void
   */
  public function __construct($id = null) {

    $this->id = $id ?: 'MAIN';
    $this->session = new Session($this->id);
    $this->session->create();
  }

  /**
   * @return integer
   */
  public function id() {
    return $this->id;
  }

  /**
   * @return \ECidade\V3\Window\Session
   */
  public function session() {
    return $this->session;
  }

}

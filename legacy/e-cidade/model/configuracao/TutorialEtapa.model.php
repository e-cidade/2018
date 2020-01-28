<?php

/**
 * Classe que representa uma etapa de um tutorial
 */
class TutorialEtapa {

  /**
   * Id sequencial do registro
   * @var integer
   */
  private $id;

  /**
   * Descricao da etapa
   * @var string
   */
  private $descricao;

  /**
   * Ordem de exibicao da etapa
   * @var integer
   */
  private $ordem;

  /**
   * Passo atual a ser executado no tutorial
   * @var TutorialEtapaPasso
   */
  private $passoAtual;

  /**
   * Coleção de passos da etapa
   * @var TutorialEtapaPasso[]
   */
  private $passos;

  /**
   * Instancia do menu vinculado a esta etapa
   * @var MenuSistema
   */
  private $menu;

  private $modulo;

  /**
   * @var Tutorial
   */
  private $tutorial;

  public function setId($id) {
    $this->id = $id;
  }

  public function getId() {
    return $this->id;
  }

  public function setDescricao($descricao) {
    $this->descricao = $descricao;
  }

  public function getDescricao() {
    return $this->descricao;
  }

  public function setOrdem($ordem) {
    $this->ordem = $ordem;
  }

  public function getOrdem() {
    return $this->ordem;
  }

  public function setPassoAtual(TutorialEtapaPasso $passo) {
    $this->passoAtual = $passo;
  }

  public function getPassoAtual() {

    if (!empty($this->passoAtual)) {
      return $this->passoAtual;
    }

    $passos = $this->getPassos();
    $this->passoAtual = isset($passos[0]) ? $passos[0] : new TutorialEtapaPasso();

    return $this->passoAtual;
  }

  public function setPassos(array $passos) {
    $this->passos = $passos;
  }

  public function getPassos() {

    if (empty($this->passos)) {
      $this->passos = TutorialEtapaPassoRepository::getByTutorialEtapa($this);
    }

    return $this->passos;
  }

  public function setMenu(MenuSistema $menu) {
    $this->menu = $menu;
  }

  public function getMenu() {
    return $this->menu;
  }

  public function setModulo(ModuloSistema $modulo) {
    $this->modulo = $modulo;
  }

  public function getModulo() {
    return $this->modulo;
  }

  public function setTutorial(Tutorial $tutorial) {
    $this->tutorial = $tutorial;
  }

  public function getTutorial() {
    return $this->tutorial;
  }

  /**
   * Retorna os dados da classe no formato de stdClass
   * @return \stdClass Objeto de retorno
   */
  public function toObject() {

    $obj = new \stdClass();

    $obj->id = $this->getId();
    $obj->descricao = $this->getDescricao();
    $obj->ordem = $this->getOrdem();
    $obj->passoAtual = $this->getPassoAtual()->toObject();
    $obj->passos = array();

    foreach ($this->getPassos() as $passo) {
      $obj->passos[] = $passo->toObject();
    }

    $menu = $this->getMenu();
    $obj->menu = '';

    if (!empty($menu)) {
      $obj->menu = $menu->getFuncao();
    }

    $modulo = $this->getModulo();
    $obj->modulo = '';

    if (!empty($modulo)) {
      $obj->modulo = $modulo->getCodigo();
    }

    $obj->permissao = false;

    if (!empty($obj->menu) && !empty($obj->modulo)) {
      $obj->permissao = db_permissaomenu($_SESSION['DB_anousu'], $modulo->getCodigo(), $menu->getCodigo()) === "true";
    }

    return $obj;
  }

  public function save() {

    $daoEtapa = new cl_db_tutorialetapas();
    $daoEtapa->id = $this->getId();
    $daoEtapa->db_tutorial_id = $this->getTutorial()->getId();
    $daoEtapa->descricao = $this->getDescricao();
    $daoEtapa->ordem = $this->getOrdem();
    $daoEtapa->menu_id = $this->getMenu()->getCodigo();
    $daoEtapa->modulo_id = $this->getModulo()->getCodigo();
    $daoEtapa->setSalvarAccount(false);

    if (!empty($daoEtapa->id)) {
      $daoEtapa->alterar($daoEtapa->id);
    } else {
      $daoEtapa->incluir();
      $this->setId($daoEtapa->id);
    }

    if ($daoEtapa->erro_status === '0') {
      throw new DBException($daoEtapa->erro_msg);
    }

    return true;
  }

  public function remove() {

    foreach($this->getPassos() as $passo) {
      $passo->remove();
    }

    $daoEtapa = new cl_db_tutorialetapas();
    $daoEtapa->setSalvarAccount(false);
    $daoEtapa->excluir($this->getId());

    if ($daoEtapa->erro_status === '0') {
      throw new DBException($daoEtapa->erro_msg);
    }

    return true;
  }

}

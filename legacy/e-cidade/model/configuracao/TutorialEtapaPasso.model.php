<?php

/**
 * Classe que representa um passo de uma etapa de um tutorial
 */
class TutorialEtapaPasso {

  /**
   * Id sequencial do passo
   * @var integer
   */
  private $id;

  /**
   * Xpath que representa o element da tela
   * @var string
   */
  private $xpath;

  /**
   * Mensagem que o passo ira mostrar
   * @var string
   */
  private $conteudo;

  /**
   * Ordem de visualização dos passos
   * @var integer
   */
  private $ordem;  

  /**
   * Instancia da etapa respectiva a esse passo
   * @var \TutorialEtapa
   */
  private $etapa;

  public function setId($id) {
    $this->id = $id;
  }

  public function getId() {
    return $this->id;
  }

  public function setXpath($xpath) {
    $this->xpath = $xpath;
  }

  public function getXpath() {
    return $this->xpath;
  }

  public function setConteudo($conteudo) {
    $this->conteudo = $conteudo;
  }

  public function getConteudo() {
    return $this->conteudo;
  }

  public function setOrdem($ordem) {
    $this->ordem = $ordem;
  }

  public function getOrdem() {
    return $this->ordem;
  }

  public function setEtapa(\TutorialEtapa $etapa) {
    $this->etapa = $etapa;
  }

  public function getEtapa() {
    return $this->etapa;
  }

  public function toObject() {

    $obj = new \stdClass();

    $obj->id = $this->getId();
    $obj->xpath = $this->getXpath();
    $obj->conteudo = $this->getConteudo();
    $obj->ordem = $this->getOrdem();

    return $obj;
  }

  public function save() {

    $daoEtapaPasso = new cl_db_tutorialetapapassos();
    $daoEtapaPasso->id = $this->getId();
    $daoEtapaPasso->db_tutorialetapa_id = $this->getEtapa()->getId();
    $daoEtapaPasso->conteudo = $this->getConteudo();
    $daoEtapaPasso->xpath = $this->getXpath();
    $daoEtapaPasso->ordem = $this->getOrdem();
    $daoEtapaPasso->setSalvarAccount(false);

    if (!empty($daoEtapaPasso->id)) {
      $daoEtapaPasso->alterar($daoEtapaPasso->id);
    } else {
      $daoEtapaPasso->incluir();
      $this->setId($daoEtapaPasso->id);
    }

    if ($daoEtapaPasso->erro_status === '0') {
      throw new DBException($daoEtapaPasso->erro_msg);
    }

    return true;

  }

  public function remove() {

    $daoEtapaPasso = new cl_db_tutorialetapapassos();
    $daoEtapaPasso->setSalvarAccount(false);
    $daoEtapaPasso->excluir($this->getId());

    if ($daoEtapaPasso->erro_status === '0') {
      throw new DBException($daoEtapaPasso->erro_msg);
    }

    return true;

  }

}
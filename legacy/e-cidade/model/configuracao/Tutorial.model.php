<?php

/**
 * Classe que representa um Tutorial do sistema
 */
class Tutorial {

  /**
   * Id sequencial do tutorial
   * @var integer
   */
  private $id;

  /**
   * Descricao do tutorial
   * @var string
   */
  private $descricao;

  /**
   * Coleção de etapas
   * @var TutorialEtapa[]
   */
  private $etapas = null;

  /**
   * Instancia da etapa atual
   * @var TutorialEtapa
   */
  private $etapaAtual;

  public function setId($id) {
    $this->id = $id;
  }

  public function getId() {
    return $this->id;
  }

  public function setDescricao($descricao = '') {
    $this->descricao = $descricao;
  }

  public function getDescricao() {
    return $this->descricao;
  }

  public function setEtapaAtual($etapaAtual) {
    $this->etapaAtual = $etapaAtual;
  }

  public function getEtapaAtual() {

    if (!empty($this->etapaAtual)) {
      return $this->etapaAtual;
    }

    // caso nao tenha etapa atual, utiliza a primeira etapa
    $etapas = $this->getEtapas();
    $this->etapaAtual = isset($etapas[0]) ? $etapas[0] : new TutorialEtapa();

    return $this->etapaAtual;
  }

  public function setEtapas($etapas) {

    $this->etapas = $etapas;

    foreach ($this->etapas as $etapa) {
      $etapa->setTutorial($this);
    }
  }

  public function getEtapas() {

    if ($this->etapas === null) {
      $this->etapas = TutorialEtapaRepository::getByTutorial($this);
    }

    return $this->etapas ?: array();
  }

  public function toObject() {

    $objTutorial = new \stdClass();

    $objTutorial->id = $this->getId();
    $objTutorial->descricao = $this->getDescricao();
    $objTutorial->etapaAtual = $this->getEtapaAtual()->toObject();

    $objTutorial->etapas = array();

    foreach($this->getEtapas() as $etapa) {
      $objTutorial->etapas[] = $etapa->toObject();
    };

    return $objTutorial;
  }

  public function save() {

    $daoTutorial = new cl_db_tutorial();
    $daoTutorial->id = $this->getId();
    $daoTutorial->descricao = $this->getDescricao();

    // desabilita account pois nao tem item de menu para gerar DB_acessado
    $daoTutorial->setSalvarAccount(false);

    // id com etapas ja salvas
    $etapasSalvas = array();
    $alteracao = !empty($daoTutorial->id);

    if ($alteracao) {
      $daoTutorial->alterar($daoTutorial->id);
    } else {
      $daoTutorial->incluir();
      $this->setId($daoTutorial->id);
    }

    if ($daoTutorial->erro_status === '0') {
      throw new DBException($daoTutorial->erro_msg);
    }

    foreach($this->getEtapas() as $etapa) {
      $etapa->save();
      $etapasSalvas[] = $etapa->getId();
    }

    if ($alteracao) {
      foreach(\TutorialEtapaRepository::getByTutorial($this) as $step) {
        if (!in_array($step->getId(), $etapasSalvas)) {
          $step->remove();
        }
      }
    }

    return true;
  }

  public function remove() {

    foreach($this->getEtapas() as $etapa) {
      $etapa->remove();
    }

    $daoTutorial = new cl_db_tutorial();
    $daoTutorial->setSalvarAccount(false);
    $daoTutorial->excluir($this->getId());

    if ($daoTutorial->erro_status === '0') {
      throw new DBException($daoTutorial->erro_msg);
    }

    return true;
  }

}

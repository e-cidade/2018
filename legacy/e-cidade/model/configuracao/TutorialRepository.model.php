<?php

class TutorialRepository {

  private static $tutoriais = array();

  public function getTutoriaisDisponiveis($idMenu, $idModulo) {

    $daoTutorial = new cl_db_tutorial();

    $sqlTutorial  = "select distinct t.id, t.descricao";
    $sqlTutorial .= "  from db_tutorial t";
    $sqlTutorial .= " inner join db_tutorialetapas te on t.id = te.db_tutorial_id";
    $sqlTutorial .= " where te.menu_id = $idMenu and te.modulo_id = $idModulo";
    $rsTutorial = db_query($sqlTutorial);

    if (!$rsTutorial) {
      throw new DBException("Erro ao buscar os tutoriais.");
    }

    return db_utils::makeCollectionFromRecord($rsTutorial, function($obj) {
      return array(
        'id' => $obj->id,
        'descricao' => $obj->descricao
      );
    });

  }

  public function iniciarTutorial(Tutorial $tutorial) {
    static::storeOnSession($tutorial);
  }

  public function finalizarTutorial(Tutorial $tutorial) {
    static::clearSession($tutorial);    
  }

  public static function getById($id) {

    if (!empty(static::$tutoriais[$id])) {
      return static::$tutoriais[$id];
    }

    $daoTutorial = new cl_db_tutorial();
    $rsTutorial = db_query($daoTutorial->sql_query_file($id));

    if (!$rsTutorial) {
      throw new DBException("Erro ao buscar o tutorial.");
    }

    $tutorial = db_utils::makeFromRecord($rsTutorial, function($obj) {

      $tutorial = new Tutorial();
      $tutorial->setId($obj->id);
      $tutorial->setDescricao($obj->descricao);

      return $tutorial;
    }, 0);
    
    return static::$tutoriais[$id] = $tutorial;
  }

  public static function storeOnSession(Tutorial $tutorial) {

    $_SESSION['Tutorial'] = array(
      'id' => $tutorial->getId(),
      'etapaAtual' => $tutorial->getEtapaAtual()->getId(),
      'passoAtual' => $tutorial->getEtapaAtual()->getPassoAtual()->getId(),
    );

  }

  public static function restoreFromSession() {

    if (empty($_SESSION['Tutorial'])) {
      return null;
    }

    $tutorial = static::getById($_SESSION['Tutorial']['id']);
    $tutorial->setEtapaAtual(TutorialEtapaRepository::getById($_SESSION['Tutorial']['etapaAtual']));
    $tutorial->getEtapaAtual()->setPassoAtual(TutorialEtapaPassoRepository::getById($_SESSION['Tutorial']['passoAtual']));

    return $tutorial;
  }

  public static function clearSession(Tutorial $tutorial) {
    if (!empty($_SESSION['Tutorial']) && $_SESSION['Tutorial']['id'] == $tutorial->getId())  {
      unset($_SESSION['Tutorial']);
    }
  }

  public static function render() {
    
    $sHtmlTutorial  = '<script type="text/javascript" src="scripts/classes/configuracao/TutorialRepository.classe.js"></script>';
    $sHtmlTutorial .= '<script type="text/javascript">try { TutorialRepository.resume();} catch(e) { console.error(e); }</script>';

    return $sHtmlTutorial;
  }

}
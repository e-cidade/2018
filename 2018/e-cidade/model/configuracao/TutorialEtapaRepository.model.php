<?php

class TutorialEtapaRepository {

  private static $etapas = array();

  public static function getById($id) {

    if (!empty(self::$etapas[$id])) {
      return self::$etapas[$id];
    }

    $daoEtapa = new cl_db_tutorialetapas();
    $sqlEtapa = $daoEtapa->sql_query_file($id);
    $rsEtapa = db_query($sqlEtapa);

    if (!$rsEtapa) {
      throw new DBException("Erro ao buscar a etapa do tutorial.");
    }

    $etapa = db_utils::makeFromRecord($rsEtapa, function($obj) {

      $etapa = new TutorialEtapa();
      $etapa->setId($obj->id);
      $etapa->setDescricao($obj->descricao);
      $etapa->setOrdem($obj->ordem);
      $etapa->setMenu(new MenuSistema($obj->menu_id));
      $etapa->setModulo(new ModuloSistema($obj->modulo_id));

      return $etapa;
    }, 0);

    return self::$etapas[$id] = $etapa;
  }

  public static function getByTutorial(Tutorial $tutorial) {

    $sqlEtapas = "select * from db_tutorialetapas where db_tutorial_id = {$tutorial->getId()} order by ordem";
    $rsEtapas = db_query($sqlEtapas);

    if (!$rsEtapas) {
      throw new DBException("Erro ao buscar as etapas do tutorial.");
    }

    $aDadosEtapas = db_utils::getCollectionByRecord($rsEtapas);

    $aEtapas = array();

    foreach ($aDadosEtapas as $obj) {

      if ( !empty(self::$etapas[$obj->id]) ) {
        $aEtapas[] = self::$etapas[$obj->id];
        continue;
      }

      $etapa = new TutorialEtapa();

      $etapa->setId($obj->id);
      $etapa->setDescricao($obj->descricao);
      $etapa->setOrdem($obj->ordem);
      $etapa->setMenu(new MenuSistema($obj->menu_id));
      $etapa->setModulo(new ModuloSistema($obj->modulo_id));
      $etapa->setTutorial($tutorial);

      self::$etapas[$obj->id] = $etapa;
      $aEtapas[] = $etapa;
    }

    return $aEtapas;
  }

}

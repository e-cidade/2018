<?php

class TutorialEtapaPassoRepository {

  private static $passos = array();

  public static function getById($id) {

    if (!empty(static::$passos[$id])) {
      return static::$passos[$id];
    }

    $daoTutorialEtapaPasso = new cl_db_tutorialetapapassos();
    $sqlPassos = $daoTutorialEtapaPasso->sql_query_file($id);
    $rsEtapaPassos = db_query($sqlPassos);

    if (!$rsEtapaPassos) {
      throw new DBException("Erro ao buscar o passo da etapa.");
    }

    $passo = db_utils::makeFromRecord($rsEtapaPassos, function($obj) {

      $passo = new TutorialEtapaPasso();
      $passo->setId($obj->id);
      $passo->setXpath($obj->xpath);
      $passo->setConteudo($obj->conteudo);
      $passo->setOrdem($obj->ordem);

      return $passo;      

    }, 0);    

    return static::$passos[$id] = $passo;
  }

  public static function getByTutorialEtapa(TutorialEtapa $etapa) {

    $etapaId = $etapa->getId();

    if (empty($etapaId)) {
      return array();
    }

    $sqlPassos = "select * from db_tutorialetapapassos where db_tutorialetapa_id = {$etapaId} order by ordem";
    $rsPassos = db_query($sqlPassos);

    if (!$rsPassos) {
      throw new DBException("Erro ao buscar os passos da etapa.");
    }

    $aDadosPassos = db_utils::getCollectionByRecord($rsPassos);

    $aPassos = array();

    foreach ($aDadosPassos as $obj) {

      if (!empty(self::$passos[$obj->id])) {
        $aPassos[] = self::$passos[$obj->id];
        continue;
      }

      $passo = new TutorialEtapaPasso();
      $passo->setId($obj->id);
      $passo->setXpath($obj->xpath);
      $passo->setConteudo($obj->conteudo);
      $passo->setOrdem($obj->ordem);

      self::$passos[$obj->id] = $passo;
      $aPassos[] = $passo;
    }

    return $aPassos;    
  }

}
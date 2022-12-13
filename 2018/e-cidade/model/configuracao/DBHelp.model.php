<?php

abstract class DBHelp extends DBCentralAjuda {

  /**
   * @var mixed
   */
  private $oData;

  /**
   * metodo responsavel para carregar os dados, propriedade oData
   */
  abstract public function load();

  /**
   * @param mixed
   */
  public function setData($oData = null) {
    $this->oData = $oData;
  }

  /**
   * @return stdClass
   */
  public function getData() {
    return $this->oData;
  }

  /**
   * @param Array $aGrupos
   * @param Closure $callback
   */
  protected function recursiveGroupIterate(array & $aGrupos, Closure $callback) {

    foreach ($aGrupos as $oGrupo) {

      if (!empty($oGrupo->groups)) {
        $this->recursiveGroupIterate($oGrupo->groups, $callback);
      }

      $callback($oGrupo);
    }
  }

}

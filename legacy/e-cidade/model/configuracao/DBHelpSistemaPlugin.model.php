<?php

use \ECidade\Core\Config as AppConfig;

class DBHelpSistemaPlugin extends DBHelpSistema {

  private $oPlugin;

  public function __construct(AppConfig $oConfig, $iIdItemMenu, Plugin $oPlugin) {

    parent::__construct($oConfig, $iIdItemMenu);
    $this->oPlugin = $oPlugin;
  }

  private function loadHelpFile() {

    $sCaminhoDados = "plugins/{$this->oPlugin->getNome()}/helps/{$this->getIdItemMenu()}.json";

    if (!file_exists($sCaminhoDados)) {
      return false;
    }

    $oHelp = json_decode(file_get_contents($sCaminhoDados));

    if (json_last_error() !== JSON_ERROR_NONE) {
      throw new BusinessException('Erro ao ler arquivo json: ' . $sCaminhoDados);
    }

    return $oHelp;
  }

  public function load() {

    $oTemplate = (object) array(
      'helps_releases' => (object) array(
        'group' => (object) array(
          'id' => null,
          'parent_id' => null,
          'title' => null,
          'content' => null, 
          'fields' => array(),
          'groups' => array(),
        ),
      )
    );

    $oHelp = $this->loadHelpFile();

    if (!empty($oHelp->groups)) {
      $oTemplate->helps_releases->group->groups = $oHelp->groups;      
    }

    if (!empty($oHelp->fields)) {
      $oTemplate->helps_releases->group->fields = $oHelp->fields;      
    }

    if (empty($oHelp->fields) && empty($oHelp->groups)) {
      $oTemplate = null;
    }

    $this->setData($oTemplate);
  }

}

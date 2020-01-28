<?php

use \ECidade\Core\Config as AppConfig;

class DBHelpInlinePlugin extends DBHelpInline {

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
    return $oHelp;
  }

  public function load() {

    $oHelp = $this->loadHelpFile();

    if (empty($oHelp->fields)) {
      return;
    }

    $this->setData($oHelp->fields);
  }


}
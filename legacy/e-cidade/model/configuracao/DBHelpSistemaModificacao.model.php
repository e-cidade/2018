<?php

class DBHelpSistemaModificacao extends DBHelpSistema {

  public function load() {

    parent::load();

    $oHelp = $this->getData();

    if (empty($oHelp->helps_releases)) {

      $oHelp = (object) array(
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
    }

    foreach ($this->getPlugins() as $oPlugin) {
      
      $oDBHelpPlugin = new DBHelpSistemaPlugin($this->getConfig(), $this->getIdItemMenu(), $oPlugin);
      $oDBHelpPlugin->load();
      $oHelpPlugin = $oDBHelpPlugin->getData();

      if (empty($oHelpPlugin)) {
        continue;
      }

      $oHelp->helps_releases->group->groups = array_merge($oHelp->helps_releases->group->groups, $oHelpPlugin->helps_releases->group->groups);
      $oHelp->helps_releases->group->fields = array_merge($oHelp->helps_releases->group->fields, $oHelpPlugin->helps_releases->group->fields);
    }

    if (empty($oHelp->helps_releases->group->groups) && empty($oHelp->helps_releases->group->fields)) {
      $oHelp = null;
    }

    $this->setData($oHelp);
  }

  /**
   * @todo - metodo igual ao DBReleaseNoteModificacao::getPlugins
   */
  private function getPlugins() {

    $aPlugins = array();

    foreach (static::getPluginsMenu($this->getIdItemMenu()) as $sNomePlugin) {
      
      $oPlugin = new Plugin(null, $sNomePlugin);

      if ($oPlugin->isAtivo()) {
        $aPlugins[] = $oPlugin;
      }
    }

    return $aPlugins;
  }

  /**
   * @todo - metodo igual ao DBReleaseNoteModificacao::getPluginsMenu
   */
  public static function getPluginsMenu($iIdItemMenu) {

    $aPlugins = array();
    $aArquivos = glob('plugins/*/helps/' . $iIdItemMenu . '.json');

    foreach ($aArquivos as $sArquivo) {
      $aPlugins[] = static::extrairNomePlugin($sArquivo);
    }

    return $aPlugins;
  }

  /**
   * @todo - metodo igual ao DBReleaseNoteModificacao::extrairNomePlugin
   */
  public static function extrairNomePlugin($sArquivo) {

    $lResult = preg_match('/^plugins\/(.*)\/helps.*/', $sArquivo, $aMatches);
    return $lResult ? $aMatches[1] : false;
  }

}

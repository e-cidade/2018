<?php

class DBHelpInlineModificacao extends DBHelpInline {

  public function load() {

    parent::load();

    $aFields = $this->getData() ?: array();
    $fnMergeData = function($oGroup) use ( & $aFields) {
      if (!empty($oGroup->fields)) {
        $aFields = array_merge($aFields, $oGroup->fields);
      }
    };

    foreach ($this->getPlugins() as $oPlugin) {

      $oHelpPlugin = $this->getPluginData($oPlugin, $this->getIdItemMenu());

      if (!empty($oHelpPlugin->fields)) {
        $aFields = array_merge($aFields, $oHelpPlugin->fields);
      }

      if (!empty($oHelpPlugin->groups)) {
        DBHelp::recursiveGroupIterate($oHelpPlugin->groups, $fnMergeData);
      }
    }
   
    if (empty($aFields)) {
      $aFields = null;
    }

    $this->setData($aFields);
  }

  /**
   * @param Plugin $oPlugin
   * @param integer $iIdItemMenu
   * @throws BusinessException
   * @return stdClass
   */
  private function getPluginData(Plugin $oPlugin, $iIdItemMenu) {

    $sArquivo = sprintf("plugins/%s/helps/%s.json", $oPlugin->getNome(), $iIdItemMenu);

    if (!is_readable($sArquivo)) {
      throw new BusinessException('Arquivo sem permissão de leitura: ' . $sArquivo);
    }

    $oHelpPlugin = json_decode(file_get_contents($sArquivo));

    if (json_last_error() !== JSON_ERROR_NONE) {
      throw new BusinessException('Erro ao ler arquivo json: ' . $sArquivo);
    }

    return $oHelpPlugin;
  }

  /**
   * @todo - metodo igual ao DBHelpSistemaModificacao::getPlugins
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
   * @todo - metodo igual ao DBHelpSistemaModificacao::getPluginsMenu
   */
  public static function getPluginsMenu($iIdItemMenu) {
    return DBHelpSistemaModificacao::getPluginsMenu($iIdItemMenu);
  }

}

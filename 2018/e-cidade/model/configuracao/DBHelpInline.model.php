<?php

use \ECidade\Core\Config as AppConfig;

class DBHelpInline extends DBHelp {

  /**
   * Busca os dados do help do sistema da API e seta na propriedade da classe
   */
  public function load() {

    $sUri = sprintf('help/findFieldsByMenuAndVersion/%s/%s/1', $this->getIdItemMenu(), $this->getVersao());

    $oHttpRequest = $this->getHttpRequest();
    $oHttpRequest->send($sUri);
    $sRetorno = $oHttpRequest->getBody();

    $aFields = json_decode($sRetorno);

    if (!empty($aFields->error)) {
      throw new BusinessException(utf8_decode($aFields->message));      
    }

    $this->setData($aFields);
  }

  public static function render() {

    $sHtml = "";
    $sHtml .= "<script type='text/javascript' src='scripts/classes/configuracao/DBViewHelpInline.classe.js'></script>";
    $sHtml .= "<script type='text/javascript'>DBViewHelpInline.build(true);</script>";

    return $sHtml;
  }

  public static function create(AppConfig $oConfig, $iIdItemMenu) {

    // rotina de plugin
    if (PluginService::getPluginAtual($iIdItemMenu)) {
      return new DBHelpInlinePlugin($oConfig, $iIdItemMenu, PluginService::getPluginAtual($iIdItemMenu));
    }

    //rotina do sistama modificada pelo plugin
    if (count(DBHelpInlineModificacao::getPluginsMenu($iIdItemMenu)) > 0) {
      return new DBHelpInlineModificacao($oConfig, $iIdItemMenu);
    }

    // default
    return new DBHelpInline($oConfig, $iIdItemMenu);
  }

}

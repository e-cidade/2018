<?php

use \ECidade\Core\Config as AppConfig;

class DBHelpSistema extends DBHelp {

  /**
   * Busca os dados do help do sistema da API e seta na propriedade da classe
   */
  public function load() {

    $oHttpRequest = $this->getHttpRequest();

    $sUri = sprintf('help/findHelpByMenuAndVersion/%s/%s/1', $this->getIdItemMenu(), $this->getVersao());
    
    $oHttpRequest->send($sUri);
    $sRetorno = $oHttpRequest->getBody();

    $oHelp = json_decode($sRetorno);

    if (!empty($oHelp->error)) {
      throw new BusinessException(utf8_decode($oHelp->message));
    }

    $this->setData($oHelp);
  }

  public static function create(AppConfig $oConfig, $iIdItemMenu) {

    // rotina de plugin
    if (PluginService::getPluginAtual($iIdItemMenu)) {
      return new DBHelpSistemaPlugin($oConfig, $iIdItemMenu, PluginService::getPluginAtual($iIdItemMenu));
    }

    // rotina do sistama modificada pelo plugin
    if (count(DBHelpSistemaModificacao::getPluginsMenu($iIdItemMenu)) > 0) {
      return new DBHelpSistemaModificacao($oConfig, $iIdItemMenu);
    }

    // default
    return new DBHelpSistema($oConfig, $iIdItemMenu);
  }

}

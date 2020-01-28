<?php

use \ECidade\Core\Config as AppConfig;

/**
 * Classe responsável por abstrair o controle do Help e Faq (e suas subclasses)
 */
abstract class DBCentralAjuda {

  /**
   * Instancia da classe de configuracao do sistema
   * @var AppConfig
   */
  private $oSysConfig;

  /**
   * String representando a versão do sistema
   * @var string
   */
  private $sVersao;

  /**
   * Id do item de menu do sistema
   * @var integer
   */
  private $iIdItemMenu;

  /**
   * Instancia da classe de requisicoes HTTP do sistema
   * @var DBHttpRequest
   */
  private $oHttpRquest;

  /**
   * @param AppConfig $oSysConfig  Instancia da classe de configuracao do sistema
   * @param integer   $iIdItemMenu Id do item de menu do sistema
   */
  public function __construct(AppConfig $oSysConfig, $iIdItemMenu) {
    $this->oSysConfig = $oSysConfig;

    include(modification('libs/db_acessa.php'));
    $this->sVersao = "2.$db_fonte_codversao.$db_fonte_codrelease";

    $this->iIdItemMenu = $iIdItemMenu;
  }

  /**
   * Metodo obrigatorio para retornar os dados de cada sub-classe 
   * @return mixed Dados de retorno
   */
  abstract function getData();

  /**
   * @return AppConfig
   */
  public function getConfig() {
    return $this->oSysConfig;
  }

  /**
   * @param string
   */
  public function setVersao($sVersao) {
    $this->sVersao = $sVersao;
  }

  /**
   * @return string
   */
  public function getVersao() {
    return $this->sVersao;
  }

  /**
   * @param integer
   */
  public function setIdItemMenu($iIdItemMenu) {
    $this->iIdItemMenu = $iIdItemMenu;
  }

  /**
   * @return integer
   */
  public function getIdItemMenu() {
    return $this->iIdItemMenu;
  }

  /**
   * @return DBHttpRequest retorna a instancia da classe de requisicoes http do sistema, 
   * ja configurada para requisicoes da central de ajuda
   */
  public function getHttpRequest() {

    if ($this->oHttpRquest !== null) {
      return $this->oHttpRquest;
    }
      
    $aApiConfig = $this->getConfig()->get('app.api');

    $this->oHttpRquest = new DBHttpRequest($this->getConfig());
    $this->oHttpRquest->addOptions(array(
      'baseUrl' => $aApiConfig['centraldeajuda'],
      'headers' => array(
        'Accept' => 'application/json'
      )
    ));

    return $this->oHttpRquest;
  }

}
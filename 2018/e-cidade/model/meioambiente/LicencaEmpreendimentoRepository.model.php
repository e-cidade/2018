<?php

/**
 * Classe repository para classes LicencaEmpreendimento
 *
 * @author
 * @package
 */
 class LicencaEmpreendimentoRepository {

  /**
   * Collection de LicencaEmpreendimento
   *
   * @var array
   */
  private $aItens = array();

  /**
   * Instancia da classe
   *
   * @var LicencaEmpreendimentoRepository
   */
  private static $oInstance;

  private function __construct() {

  }

  private function __clone() {

  }

  /**
   * Retorna uma instancia do LicencaEmpreendimento pelo Codigo
   *
   * @param integer $iCodigo Codigo do LicencaEmpreendimento
   * @return LicencaEmpreendimento
   */
  public static function getByCodigo($iCodigo) {

    if (!array_key_exists($iCodigo, LicencaEmpreendimentoRepository::getInstance()->aItens)) {
      LicencaEmpreendimentoRepository::getInstance()->aItens[$iCodigo] = new LicencaEmpreendimento($iCodigo);
    }
    return LicencaEmpreendimentoRepository::getInstance()->aItens[$iCodigo];
  }

  /**
   * Retorna a instancia da classe
   *
   * @return LicencaEmpreendimentoRepository
   */
  protected static function getInstance() {

    if (self::$oInstance == null) {
      self::$oInstance = new LicencaEmpreendimentoRepository();
    }
    return self::$oInstance;
  }

  /**
   * Adiciona uma instancia de LicencaEmpreendimento ao repositorio
   *
   * @param LicencaEmpreendimento $oLicencaEmpreendimento Instancia de LicencaEmpreendimento
   * @return boolean
   */
  public static function adicionarLicencaEmpreendimento(LicencaEmpreendimento $oLicencaEmpreendimento) {

    if (!array_key_exists($oLicencaEmpreendimento->getSequencial(), LicencaEmpreendimentoRepository::getInstance()->aItens)) {
      LicencaEmpreendimentoRepository::getInstance()->aItens[$oLicencaEmpreendimento->getSequencial()] = $oLicencaEmpreendimento;
    }
    return true;
  }

  /**
   * Remove a instancia passada como parametro do repository
   *
   * @param LicencaEmpreendimento $oLicencaEmpreendimento
   * @return boolean
   */
  public static function remover(LicencaEmpreendimento $oLicencaEmpreendimento) {

    if (array_key_exists($oLicencaEmpreendimento->getSequencial(), LicencaEmpreendimentoRepository::getInstance()->aItens)) {
      unset(LicencaEmpreendimentoRepository::getInstance()->aItens[$oLicencaEmpreendimento->getSequencial()]);
    }
    return true;
  }

  /**
   * Retorna o total de itens existentes no repositorio;
   *
   * @return integer;
   */
  public static function getTotalLicencaEmpreendimento() {
    return count(LicencaEmpreendimentoRepository::getInstance()->aItens);
  }

}
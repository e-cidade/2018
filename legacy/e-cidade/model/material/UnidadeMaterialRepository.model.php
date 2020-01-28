<?php

/**
 * Classe repository para classes UnidadeMaterial
 *
 * @author
 * @package
 */
class UnidadeMaterialRepository {

  /**
   * Collection de UnidadeMaterial
   *
   * @var array
   */
  private $aItens = array();
  /**
   * Instancia da classe
   *
   * @var UnidadeMaterialRepository
   */
  private static $oInstance;

  private function __construct() {

  }

  private function __clone() {

  }

  /**
   * Retorna uma instancia do UnidadeMaterial pelo Codigo
   *
   * @param integer $iCodigo Codigo do UnidadeMaterial
   * @return UnidadeMaterial
   */
  public static function getByCodigo($iCodigo) {

    if (!array_key_exists($iCodigo, UnidadeMaterialRepository::getInstance()->aItens)) {
      UnidadeMaterialRepository::getInstance()->aItens[$iCodigo] = new UnidadeMaterial($iCodigo);
    }
    return UnidadeMaterialRepository::getInstance()->aItens[$iCodigo];
  }

  /**
   * Retorna a instancia da classe
   *
   * @return UnidadeMaterialRepository
   */
  protected static function getInstance() {

    if (self::$oInstance == null) {

      self::$oInstance = new UnidadeMaterialRepository();
    }
    return self::$oInstance;
  }

  /**
   * Adiciona uma instancia de UnidadeMaterial ao repositorio
   *
   * @param UnidadeMaterial $oUnidadeMaterial Instancia de UnidadeMaterial
   * @return boolean
   */
  public static function adicionarUnidadeMaterial(UnidadeMaterial $oUnidadeMaterial) {

    if (!array_key_exists($oUnidadeMaterial->getCodigo(), UnidadeMaterialRepository::getInstance()->aItens)) {
      UnidadeMaterialRepository::getInstance()->aItens[$oUnidadeMaterial->getCodigo()] = $oUnidadeMaterial;
    }
    return true;
  }

  /**
   * Remove a instancia passada como parametro do repository
   *
   * @param UnidadeMaterial $oUnidadeMaterial
   * @return boolean
   */
  public static function remover(UnidadeMaterial $oUnidadeMaterial) {
    /**
     *
     */
    if (array_key_exists($oUnidadeMaterial->getCodigo(), UnidadeMaterialRepository::getInstance()->aItens)) {
      unset(UnidadeMaterialRepository::getInstance()->aItens[$oUnidadeMaterial->getCodigo()]);
    }
    return true;
  }

  /**
   * Retorna o total de itens existentes no repositorio;
   *
   * @return integer;
   */
  public static function getTotalUnidadeMaterial() {
    return count(UnidadeMaterialRepository::getInstance()->aItens);
  }

}
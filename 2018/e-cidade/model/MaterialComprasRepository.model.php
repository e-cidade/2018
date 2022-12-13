<?php

/**
 * Classe repository para classes MaterialCompras
 *
 * @author
 * @package
 */
class MaterialComprasRepository {

  /**
   * Collection de MaterialCompras
   *
   * @var array
   */
  private $aItens = array();
  /**
   * Instancia da classe
   *
   * @var MaterialComprasRepository
   */
  private static $oInstance;

  private function __construct() {

  }

  private function __clone() {

  }

  /**
   * Retorna uma instancia do MaterialCompras pelo Codigo
   *
   * @param integer $iCodigo Codigo do MaterialCompras
   * @return MaterialCompras
   */
  public static function getByCodigo($iCodigo) {

    if (!array_key_exists($iCodigo, MaterialComprasRepository::getInstance()->aItens)) {
      MaterialComprasRepository::getInstance()->aItens[$iCodigo] = new MaterialCompras($iCodigo);
    }
    return MaterialComprasRepository::getInstance()->aItens[$iCodigo];
  }

  /**
   * Retorna a instancia da classe
   *
   * @return MaterialComprasRepository
   */
  protected static function getInstance() {

    if (self::$oInstance == null) {

      self::$oInstance = new MaterialComprasRepository();
    }
    return self::$oInstance;
  }

  /**
   * Adiciona uma instancia de MaterialCompras ao repositorio
   *
   * @param MaterialCompras $oMaterialCompras Instancia de MaterialCompras
   * @return boolean
   */
  public static function adicionarMaterialCompras(MaterialCompras $oMaterialCompras) {

    if (!array_key_exists($oMaterialCompras->getCodigo(), MaterialComprasRepository::getInstance()->aItens)) {
      MaterialComprasRepository::getInstance()->aItens[$oMaterialCompras->getCodigo()] = $oMaterialCompras;
    }
    return true;
  }

  /**
   * Remove a instancia passada como parametro do repository
   *
   * @param MaterialCompras $oMaterialCompras
   * @return boolean
   */
  public static function remover(MaterialCompras $oMaterialCompras) {
    /**
     *
     */
    if (array_key_exists($oMaterialCompras->getCodigo(), MaterialComprasRepository::getInstance()->aItens)) {
      unset(MaterialComprasRepository::getInstance()->aItens[$oMaterialCompras->getCodigo()]);
    }
    return true;
  }

  /**
   * Retorna o total de itens existentes no repositorio;
   *
   * @return integer;
   */
  public static function getTotalMaterialCompras() {
    return count(MaterialComprasRepository::getInstance()->aItens);
  }

}
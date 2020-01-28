<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 22/03/17
 * Time: 09:28
 */

namespace ECidade\Patrimonial\Acordo\RegimeCompetencia\Model;


class Item {

  /**
   * Item do acordo
   * @var \AcordoItem
   */
  protected $item;

  /**
   * Código do item 
   * @var integer
   */
  protected $codigo;

  /**
   *  
   * @var Parcela[]
   * 
   */
  protected $parcelas;

  /**
   * @return \AcordoItem
   */
  public function getItem() {

    return $this->item;
  }

  /**
   * @param \AcordoItem $item
   */
  public function setItem($item) {

    $this->item = $item;
  }

  /**
   * @return int
   */
  public function getCodigo() {

    return $this->codigo;
  }

  /**
   * @param int $codigo
   */
  public function setCodigo($codigo) {

    $this->codigo = $codigo;
  }

  /**
   * @return \ECidade\Patrimonial\Acordo\RegimeCompetencia\Model\Parcela[]
   */
  public function getParcelas() {
    return $this->parcelas;
  }

  /**
   * @param \ECidade\Patrimonial\Acordo\RegimeCompetencia\Model\Parcela[] $parcelas
   */
  public function setParcelas($parcelas) {
    $this->parcelas = $parcelas;
  }
  
  
  
}
<?php

namespace ECidade\Tributario\Agua\Calculo\Isencao;

use \AguaCategoriaConsumo;

abstract class Isencao {

  /**
   * @var boolean
   */
  protected $lIsencaoTarifaBasicaEsgoto;

  /**
   * @var boolean
   */
  protected $lIsencaoTarifaBasicaAgua;

  /**
   * Quantidade de água consumida
   *
   * @var integer
   */
  protected $iConsumo;

  /**
   * @var AguaCategoriaConsumo
   */
  protected $oCategoriaConsumo;

  /**
   * Valor cobrado pelo consumo em cada faixa.
   *
   * @var array
   */
  protected $aResultadosPorFaixaConsumo;

  /**
   * @param integer $iConsumo
   */
  public function setConsumo($iConsumo) {
    $this->iConsumo = $iConsumo;
  }

  /**
   * @return int
   */
  public function getConsumo() {
    return $this->iConsumo;
  }

  /**
   * @return array
   */
  public function getResultadosPorFaixaConsumo() {
    return $this->aResultadosPorFaixaConsumo;
  }

  /**
   * @param array $aResultadosPorFaixaConsumo
   */
  public function setResultadosPorFaixaConsumo(array $aResultadosPorFaixaConsumo) {
    $this->aResultadosPorFaixaConsumo = $aResultadosPorFaixaConsumo;
  }

  /**
   * @param AguaCategoriaConsumo $oCategoria
   */
  public function setCategoriaConsumo(AguaCategoriaConsumo $oCategoria) {
    $this->oCategoriaConsumo = $oCategoria;
  }

  /**
   * @return AguaCategoriaConsumo
   */
  public function getCategoriaConsumo() {
    return $this->oCategoriaConsumo;
  }

  /**
   * @return boolean
   */
  public function temIsencaoTarifaBasicaEsgoto() {
    return $this->lIsencaoTarifaBasicaEsgoto;
  }

  /**
   * @return boolean
   */
  public function temIsencaoTarifaBasicaAgua() {
    return $this->lIsencaoTarifaBasicaAgua;
  }

  /**
   * @param float $nValorConsumo
   */
  abstract public function calcular($nValorConsumo);
}

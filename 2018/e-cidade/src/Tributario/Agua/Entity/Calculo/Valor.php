<?php

namespace ECidade\Tributario\Agua\Entity\Calculo;

class Valor {

  /**
   * @var integer
   */
  private $iCodigoCalculo;

  /**
   * @var integer
   */
  private $iCodigoTipoConsumo;

  /**
   * @var float
   */
  private $nValor;

  /**
   * @return integer
   */
  public function getCodigoCalculo() {
    return $this->iCodigoCalculo;
  }

  /**
   * @param integer $iCodigoCalculo
   */
  public function setCodigoCalculo($iCodigoCalculo) {
    $this->iCodigoCalculo = $iCodigoCalculo;
  }

  /**
   * @return integer
   */
  public function getCodigoTipoConsumo() {
    return $this->iCodigoTipoConsumo;
  }

  /**
   * @param integer $iCodigoTipoConsumo
   */
  public function setCodigoTipoConsumo($iCodigoTipoConsumo) {
    $this->iCodigoTipoConsumo = $iCodigoTipoConsumo;
  }

  /**
   * @return float
   */
  public function getValor() {
    return $this->nValor;
  }

  /**
   * @param float $nValor
   */
  public function setValor($nValor) {
    $this->nValor = $nValor;
  }
}

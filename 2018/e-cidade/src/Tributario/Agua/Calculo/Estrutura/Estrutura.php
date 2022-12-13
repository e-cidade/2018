<?php

namespace ECidade\Tributario\Agua\Calculo\Estrutura;

abstract class Estrutura {

  /**
   * @var integer
   */
  protected $iConsumo;

  /**
   * @var \AguaEstruturaTarifaria
   */
  protected $oEstrutura;

  /**
   * @param \AguaEstruturaTarifaria $oEstrutura
   */
  public function setEstruturaTarifaria(\AguaEstruturaTarifaria $oEstrutura) {
    $this->oEstrutura = $oEstrutura;
  }

  /**
   * @return \AguaEstruturaTarifaria
   */
  public function getEstruturaTarifaria() {
    return $this->oEstrutura;
  }

  /**
   * @param integer $iConsumo
   */
  public function setConsumo($iConsumo) {
    $this->iConsumo = $iConsumo;
  }

  /**
   * @return integer
   */
  public function getConsumo() {
    return $this->iConsumo;
  }

  /**
   * @throws \BusinessException
   * @return float
   */
  public function calcular() {

    if (!$this->oEstrutura) {
      throw new \BusinessException('Estrutura tarifária não informada');
    }

    return $this->calcularValor();
  }

  /**
   * @return float
   */
  abstract protected function calcularValor();

}
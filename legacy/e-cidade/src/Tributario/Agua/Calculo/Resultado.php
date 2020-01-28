<?php

namespace ECidade\Tributario\Agua\Calculo;

use \AguaEstruturaTarifaria;

class Resultado {

  /**
   * @var float
   */
  private $valor;

  /**
   * @var AguaEstruturaTarifaria
   */
  private $estruturaTarifaria;

  /**
   * @return float
   */
  public function getValor() {
    return $this->valor;
  }

  /**
   * @param float $valor
   */
  public function setValor($valor) {
    $this->valor = $valor;
  }

  /**
   * @return AguaEstruturaTarifaria
   */
  public function getEstrutura() {
    return $this->estruturaTarifaria;
  }

  /**
   * @param AguaEstruturaTarifaria $estruturaTarifaria
   */
  public function setEstrutura(AguaEstruturaTarifaria $estruturaTarifaria) {
    $this->estruturaTarifaria = $estruturaTarifaria;
  }

}

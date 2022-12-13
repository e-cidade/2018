<?php

namespace ECidade\Tributario\Agua\Calculo\Isencao;

/**
 * Totalmente isento
 */
class Imune extends Isencao {

  public function __construct() {
    $this->lIsencaoTarifaBasicaEsgoto = true;
    $this->lIsencaoTarifaBasicaAgua   = true;
  }

  public function calcular($nValorConsumo) {
    return $nValorConsumo;
  }

}

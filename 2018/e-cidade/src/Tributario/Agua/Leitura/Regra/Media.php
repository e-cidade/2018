<?php

namespace ECidade\Tributario\Agua\Leitura\Regra;

use ECidade\Tributario\Agua\Leitura\ResumoMensal;
use BusinessException;

class Media implements RegraInterface {

  /**
   * @var ResumoMensal[]
   */
  private $aLeituras;

  public function __construct(array $aLeituras) {
    $this->aLeituras = $aLeituras;
  }

  /**
   * @return int
   */
  public function calcular() {

    if (!$this->aLeituras) {
      return 0;
    }

    $nConsumo = 0;
    foreach ($this->aLeituras as $aLeitura) {
      $nConsumo += $aLeitura->getConsumo();
    }

    return (int) round($nConsumo / count($this->aLeituras));
  }
}

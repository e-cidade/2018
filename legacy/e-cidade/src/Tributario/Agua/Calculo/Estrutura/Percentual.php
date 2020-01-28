<?php

namespace ECidade\Tributario\Agua\Calculo\Estrutura;

/**
 * Aplica um percentual no valor informado e retorna o valor de acréscimo
 */
class Percentual extends Estrutura {

  /**
   * @var float
   */
  private $nValor;

  /**
   * @param float $nValor
   */
  public function setValor($nValor) {
    $this->nValor = $nValor;
  }

  /**
   * @return float
   */
  public function getValor() {
    return $this->nValor;
  }

  protected function calcularValor() {

    if ($this->nValor === null) {
      throw new \BusinessException('Valor não informado.');
    }

    return bcmul($this->nValor, (1 + ($this->oEstrutura->getPercentual() / 100 )), 2) - $this->nValor;
  }

}
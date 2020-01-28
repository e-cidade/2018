<?php

namespace ECidade\Tributario\Agua\Calculo\Estrutura;

class FaixaConsumo extends Estrutura {

  const INTERVALO_ATE = 1;
  const INTERVALO_DE_ATE = 2;
  const INTERVALO_ACIMA_DE = 3;

  /**
   * @return integer
   */
  private function tipoIntervalo() {

    if ($this->oEstrutura->getValorInicial() == null && $this->oEstrutura->getValorFinal() != null) {
      return self::INTERVALO_ATE;
    }

    if ($this->oEstrutura->getValorInicial() != null && $this->oEstrutura->getValorFinal() != null) {
      return self::INTERVALO_DE_ATE;
    }

    if ($this->oEstrutura->getValorInicial() != null && $this->oEstrutura->getValorFinal() == null) {
      return self::INTERVALO_ACIMA_DE;
    }

    throw new \BusinessException('Não foi possível identificar o tipo de faixa de consumo.');
  }

  /**
   * @return float
   */
  private function regraIntervaloAte() {

    if ($this->iConsumo >= $this->oEstrutura->getValorFinal()) {
      return bcmul($this->oEstrutura->getValorFinal(), $this->oEstrutura->getValor(), 2);
    }

    return bcmul($this->iConsumo, $this->oEstrutura->getValor(), 2);
  }

  /**
   * @return float
   */
  private function regraIntervaloDeAte() {

    if ($this->iConsumo >= $this->oEstrutura->getValorFinal()) {
      return bcmul(($this->oEstrutura->getValorFinal() - ($this->oEstrutura->getValorInicial() - 1)), $this->oEstrutura->getValor(), 2);
    } else {

      if (($this->iConsumo - ($this->oEstrutura->getValorInicial() - 1)) > 0) {
        return bcmul(($this->iConsumo - ($this->oEstrutura->getValorInicial() - 1)), $this->oEstrutura->getValor(), 2);
      }
    }

    return 0;
  }

  /**
   * @return float
   */
  private function regraIntervaloAcimaDe() {

    if (($this->iConsumo - ($this->oEstrutura->getValorInicial() - 1)) > 0) {
      return bcmul(($this->iConsumo - ($this->oEstrutura->getValorInicial() - 1)), $this->oEstrutura->getValor(), 2);
    }

    return 0;
  }

  protected function calcularValor() {

    if ($this->oEstrutura->getCodigoTipoEstrutura() !== \AguaEstruturaTarifaria::TIPO_FAIXA_CONSUMO) {
      throw new BusinessException('Estrutura tarifária não aplicável para a estratégia de cálculo.');
    }

    $iIntervalo = $this->tipoIntervalo();

    if ($iIntervalo === self::INTERVALO_ATE) {
      return $this->regraIntervaloAte();
    }

    if ($iIntervalo === self::INTERVALO_DE_ATE) {
      return $this->regraIntervaloDeAte();
    }

    if ($iIntervalo === self::INTERVALO_ACIMA_DE) {
      return $this->regraIntervaloAcimaDe();
    }
  }

}

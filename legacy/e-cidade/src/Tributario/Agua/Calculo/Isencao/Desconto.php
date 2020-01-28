<?php

namespace ECidade\Tributario\Agua\Calculo\Isencao;

/**
 * As entidades culturais, religiosas, filantr�picas e os usu�rios residenciais
 * (n�o classificados na categoria Residencial Social) poder�o fazer jus aos
 * benef�cios de desconto como segue:
 *
 * - 40% para consumo total dentro da primeira faixa de consumo;
 * - 30% para consumo total dentro da segunda faixa de consumo;
 * - 20% para consumo total dentro da terceira faixa de consumo.
 *
 * O desconto � progressivo. � concedido desconto de 40% no consumo da primeira faixa,
 * 30% no consumo que se encaixar na segunda faixa e 20% no consumo da terceira faixa,
 * qualquer consumo da quarta faixa em diante n�o tem desconto algum.
 */
class Desconto extends Isencao {

  const PERCENTUAL_PRIMEIRA_FAIXA = 0.40;
  const PERCENTUAL_SEGUNDA_FAIXA  = 0.30;
  const PERCENTUAL_TERCEIRA_FAIXA = 0.20;

  public function calcular($nValorConsumo) {

    if ($this->iConsumo === null) {
      throw new \BusinessException('Consumo n�o informado.');
    }

    if (empty($this->aResultadosPorFaixaConsumo)) {
      throw new \BusinessException('N�o foram informados os valores por faixa de consumo.');
    }

    $nDescontoPrimeiraFaixa = $this->aResultadosPorFaixaConsumo[1] * self::PERCENTUAL_PRIMEIRA_FAIXA;
    $nDescontoSegundaFaixa  = $this->aResultadosPorFaixaConsumo[2] * self::PERCENTUAL_SEGUNDA_FAIXA;
    $nDescontoTerceiraFaixa = $this->aResultadosPorFaixaConsumo[3] * self::PERCENTUAL_TERCEIRA_FAIXA;

    return $nDescontoPrimeiraFaixa + $nDescontoSegundaFaixa + $nDescontoTerceiraFaixa;
  }

}

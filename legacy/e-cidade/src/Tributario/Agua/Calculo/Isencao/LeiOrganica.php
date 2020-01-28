<?php

namespace ECidade\Tributario\Agua\Calculo\Isencao;

use \AguaEstruturaTarifaria;

/**
 * Isentos de cobran�a dos servi�os de abastecimento de �gua e de esgotamento
 * sanit�rio, at� o limite de 10m�, conforme o disposto no Art. 110 da
 * Lei Org�nica do Munic�pio (Bag�).
 */
class LeiOrganica extends Isencao {

  /**
   * Quantidade m�xima de consumo (em m�) isenta de cobran�a
   */
  const LIMITE_CONSUMO_ISENTO = 10;

  /**
   * Construtor.
   */
  public function __construct() {

    $this->lIsencaoTarifaBasicaEsgoto = true;
    $this->lIsencaoTarifaBasicaAgua = true;
  }

  /**
   * Calcular Valor de Desconto
   *
   * @param float $nValorConsumo
   * @return float
   * @throws \Exception
   */
  public function calcular($nValorConsumo) {

    if ($this->iConsumo === null) {
      throw new \Exception('Consumo n�o informado.');
    }

    if (!$this->oCategoriaConsumo) {
      throw new \Exception('Categoria de consumo n�o informada.');
    }

    $aEstruturas = $this->oCategoriaConsumo->getEstruturasPorTipo(AguaEstruturaTarifaria::TIPO_FAIXA_CONSUMO);
    if (empty($aEstruturas)) {
      throw new \Exception('Nenhuma faixa de consumo foi encontrada.');
    }

    /**
     * Se o consumo � menor que 10m� o desconto � igual ao valor total de consumo
     */
    if ($this->iConsumo <= self::LIMITE_CONSUMO_ISENTO) {
      return $nValorConsumo;
    }

    /**
     * Se o consumo � maior que 10m� o desconto � dado nos primeiros 10m�
     */
    $oEstrutura = current($aEstruturas);
    return $oEstrutura->getValor() * self::LIMITE_CONSUMO_ISENTO;
  }

}

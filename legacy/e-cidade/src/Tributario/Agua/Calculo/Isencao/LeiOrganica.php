<?php

namespace ECidade\Tributario\Agua\Calculo\Isencao;

use \AguaEstruturaTarifaria;

/**
 * Isentos de cobrança dos serviços de abastecimento de água e de esgotamento
 * sanitário, até o limite de 10m³, conforme o disposto no Art. 110 da
 * Lei Orgânica do Município (Bagé).
 */
class LeiOrganica extends Isencao {

  /**
   * Quantidade máxima de consumo (em m³) isenta de cobrança
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
      throw new \Exception('Consumo não informado.');
    }

    if (!$this->oCategoriaConsumo) {
      throw new \Exception('Categoria de consumo não informada.');
    }

    $aEstruturas = $this->oCategoriaConsumo->getEstruturasPorTipo(AguaEstruturaTarifaria::TIPO_FAIXA_CONSUMO);
    if (empty($aEstruturas)) {
      throw new \Exception('Nenhuma faixa de consumo foi encontrada.');
    }

    /**
     * Se o consumo é menor que 10m³ o desconto é igual ao valor total de consumo
     */
    if ($this->iConsumo <= self::LIMITE_CONSUMO_ISENTO) {
      return $nValorConsumo;
    }

    /**
     * Se o consumo é maior que 10m³ o desconto é dado nos primeiros 10m³
     */
    $oEstrutura = current($aEstruturas);
    return $oEstrutura->getValor() * self::LIMITE_CONSUMO_ISENTO;
  }

}

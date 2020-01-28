<?php

namespace ECidade\Tributario\Agua\Calculo;

use ECidade\Tributario\Agua\Calculo\Estrutura\Estrutura;
use ECidade\Tributario\Agua\Calculo\Estrutura\FaixaConsumo;
use ECidade\Tributario\Agua\Calculo\Estrutura\ValorFixo;
use ECidade\Tributario\Agua\Calculo\Estrutura\Percentual;
use \AguaEstruturaTarifaria;

class EstruturaFactory {

  /**
   * Cria uma inst�ncia de Estrutura de C�lculo de acordo com o tipo de estrutura tarif�ria.
   *
   * @param  integer $iTipoEstruturaTarifaria C�digo do tipo de estrutura tarif�ria
   * @return Estrutura
   * @throws \Exception
   */
  public static function create($iTipoEstruturaTarifaria) {

    switch ($iTipoEstruturaTarifaria) {

      case AguaEstruturaTarifaria::TIPO_FAIXA_CONSUMO:
        return new FaixaConsumo;

      case AguaEstruturaTarifaria::TIPO_PERCENTUAL:
        return new Percentual;

      case AguaEstruturaTarifaria::TIPO_VALOR_FIXO:
        return new ValorFixo;

      default:
        throw new \Exception('Nenhuma Estrutura de C�lculo aplic�vel.');
    }
  }
}

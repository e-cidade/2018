<?php

namespace ECidade\Tributario\Agua\Calculo\Processamento;

use AguaEstruturaTarifaria;

class EconomiaSemHidrometro extends Economia {

  /**
   * @return array
   */
  public function processar() {

    $aEstruturas = $this->oCategoriaConsumo->getEstruturas();

    $oEstrutura = current(array_filter($aEstruturas, function (AguaEstruturaTarifaria $oEstrutura) {
      return (
        $oEstrutura->getCodigoTipoEstrutura() == AguaEstruturaTarifaria::TIPO_FAIXA_CONSUMO &&
        $oEstrutura->getValorInicial() == 0
      );
    }));

    if ($oEstrutura) {
      $this->setConsumo($oEstrutura->getValorFinal());
    }

    return parent::processar();
  }
}

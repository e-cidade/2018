<?php

namespace ECidade\Tributario\Agua\Calculo;

use AguaContrato;
use ECidade\Tributario\Agua\Calculo\Processamento\Condominio;
use ECidade\Tributario\Agua\Calculo\Processamento\Economia;
use ECidade\Tributario\Agua\Calculo\Processamento\EconomiaSemHidrometro;

class ProcessamentoFactory {

  /**
   * @param AguaContrato $oContrato
   *
   * @return Condominio|Economia
   */
  public static function create(AguaContrato $oContrato) {

    $oProcessamento = new Economia;
    if ($oContrato->isCondominio()) {
      $oProcessamento = new Condominio;
    }

    $lContratoSemHidrometro = $oContrato->getCodigoTipoContrato() == AguaContrato::TIPO_CONTRATO_SEM_HIDROMETRO;
    if ($lContratoSemHidrometro && !$oContrato->isCondominio()) {
      $oProcessamento = new EconomiaSemHidrometro;
    }

    $oAguaIsencao = $oContrato->getIsencao();
    $oIsencao = $oAguaIsencao ? $oAguaIsencao->getIsencao() : null;

    if (!$oContrato->isCondominio()) {
      $oProcessamento->setCategoriaConsumo($oContrato->getCategoriaConsumo());
    }

    if ($oIsencao) {
      $oProcessamento->setIsencao($oIsencao);
    }

    if ($oContrato->getEconomias()) {

      foreach ($oContrato->getEconomias() as $oEconomia) {
        $oProcessamento->adicionarEconomia($oEconomia);
      }
    }

    return $oProcessamento;
  }
}

<?php

namespace ECidade\Tributario\Agua\Calculo\Processamento;

class Condominio extends Processamento {

  /**
   * @throws \BusinessException
   */
  private function validarParametros() {

    if (!$this->aEconomias) {
      throw new \BusinessException('Nenhuma economia foi encontrada para calcular.');
    }
  }

  public function processar() {

    $this->validarParametros();

    $nConsumo = $this->getConsumo() / count($this->aEconomias);
    $aResultados = array();
    foreach ($this->getEconomias() as $oEconomia) {

      $oAguaIsencao = $oEconomia->getIsencao();
      $oIsencao = $oAguaIsencao ? $oAguaIsencao->getIsencao() : null;

      $oProcessamento = new Economia;
      $oProcessamento->setConsumo($nConsumo);
      $oProcessamento->setCategoriaConsumo($oEconomia->getCategoriaConsumo());
      $oProcessamento->setCodigoTipoConsumoIsencao($this->iCodigoTipoConsumoIsencao);
      if ($oIsencao) {
        $oProcessamento->setIsencao($oIsencao);
      }
      $oProcessamento->processar();

      $aResultados[] = array(
        'responsavel' => $oEconomia,
        'resultado' => $oProcessamento->getResultadoCollection(),
      );
    }

    return $aResultados;
  }
}

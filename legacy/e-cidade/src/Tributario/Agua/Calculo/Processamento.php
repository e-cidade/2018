<?php

namespace ECidade\Tributario\Agua\Calculo;

use ECidade\Tributario\Agua\Calculo\EstruturaFactory;

/**
 *
 * $oResultado = new Processamento();
 * $oResultado->adicionarEstruturaTarifaria(new AguaEstruturaTarifaria);
 * $oResultado->adicionarEstruturaTarifaria(new AguaEstruturaTarifaria);
 * $oResultado->adicionarEstruturaTarifaria(new AguaEstruturaTarifaria);
 * $oResultado->processar();
 *
 * $aResultados = $oResultado->resultados();
 * $aResultados = $oResultado->resultadoPorTipoConsumo(1);
 * $aResultados = $oResultado->resultadoPorTipoConsumo(2);
 * $aResultados = $oResultado->resultadoPorTipoConsumo(3);
 *
 */
class Processamento {

  /**
   * @var \AguaEstruturaTarifaria[]
   */
  private $aEstruturasTarifarias = array();

  /**
   * @var Estrutura[]
   */
  private $aEstruturasFinalCalculo = array();

  /**
   * @var array
   */
  private $aResultadosTipoEstrutura = array();

  /**
   * @var array
   */
  private $aResultados;

  /**
   * @var integer
   */
  private $iConsumo;

  /**
   * @param \AguaEstruturaTarifaria $oEstruturaTarifaria
   */
  public function adicionarEstruturaTarifaria(\AguaEstruturaTarifaria $oEstruturaTarifaria) {
    $this->aEstruturasTarifarias[] = $oEstruturaTarifaria;
  }

  /**
   * @return \AguaEstruturaTarifaria[]
   */
  public function getEstruturasTarifarias() {
    return $this->aEstruturasTarifarias;
  }

  /**
   * @param integer $iConsumo
   */
  public function setConsumo($iConsumo) {
    $this->iConsumo = $iConsumo;
  }

  /**
   * @param \AguaEstruturaTarifaria $oEstruturaTarifaria
   * @param float                   $nResultado
   */
  private function contabilizarResultado(\AguaEstruturaTarifaria $oEstruturaTarifaria, $nResultado) {

    $iCodigoTipoEstrutura = $oEstruturaTarifaria->getCodigoTipoEstrutura();
    if (isset($this->aResultadosTipoEstrutura[$iCodigoTipoEstrutura])) {
      $this->aResultadosTipoEstrutura[$iCodigoTipoEstrutura] += $nResultado;
    } else {
      $this->aResultadosTipoEstrutura[$iCodigoTipoEstrutura] = $nResultado;
    }

    $iCodigoTipoConsumo = $oEstruturaTarifaria->getCodigoTipoConsumo();
    if (isset($this->aResultados[$iCodigoTipoConsumo])) {
      $this->aResultados[$iCodigoTipoConsumo] += $nResultado;
    } else {
      $this->aResultados[$iCodigoTipoConsumo] = $nResultado;
    }
  }

  /**
   * @param \AguaEstruturaTarifaria $oEstruturaTarifaria
   */
  private function executarEstruturaCalculo(\AguaEstruturaTarifaria $oEstruturaTarifaria) {

    $oEstruturaCalculo = EstruturaFactory::create($oEstruturaTarifaria->getCodigoTipoEstrutura());
    $oEstruturaCalculo->setEstruturaTarifaria($oEstruturaTarifaria);
    $oEstruturaCalculo->setConsumo($this->iConsumo);
    if ($oEstruturaTarifaria->getCodigoTipoEstrutura() !== \AguaEstruturaTarifaria::TIPO_PERCENTUAL) {

      $nResultado = $oEstruturaCalculo->calcular();
      $this->contabilizarResultado($oEstruturaTarifaria, $nResultado);
    } else {

      /**
       * Adiciona na fila, o percentual só é aplicado ao final da execução do cálculo
       */
      $this->aEstruturasFinalCalculo[] = $oEstruturaCalculo;
    }
  }

  public function processar() {

    foreach ($this->aEstruturasTarifarias as $oEstruturaTarifaria) {
      $this->executarEstruturaCalculo($oEstruturaTarifaria);
    }

    foreach ($this->aEstruturasFinalCalculo as $oEstruturaCalculo) {

      $nTotalFaixaConsumo = $this->resultadoPorTipoEstrutura(\AguaEstruturaTarifaria::TIPO_FAIXA_CONSUMO);
      if ($nTotalFaixaConsumo > 0) {

        $oEstruturaCalculo->setValor($nTotalFaixaConsumo);
        $nResultado = $oEstruturaCalculo->calcular();
        $this->contabilizarResultado($oEstruturaCalculo->getEstruturaTarifaria(), $nResultado);
      }
    }
  }

  /**
   * @return array
   */
  public function resultados() {
    return $this->aResultados;
  }

  /**
   * @param  integer $iCodigoTipoConsumo
   * @return float
   */
  public function resultadoPorTipoConsumo($iCodigoTipoConsumo) {

    if (isset($this->aResultados[$iCodigoTipoConsumo])) {
      return $this->aResultados[$iCodigoTipoConsumo];
    }

    return null;
  }

  /**
   * @param  integer $iCodigoTipoEstrutura [description]
   * @return float
   */
  private function resultadoPorTipoEstrutura($iCodigoTipoEstrutura) {

    if (isset($this->aResultadosTipoEstrutura[$iCodigoTipoEstrutura])) {
      return $this->aResultadosTipoEstrutura[$iCodigoTipoEstrutura];
    }

    return null;
  }

}

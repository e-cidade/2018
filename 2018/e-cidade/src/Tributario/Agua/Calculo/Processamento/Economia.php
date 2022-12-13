<?php

namespace ECidade\Tributario\Agua\Calculo\Processamento;

use ECidade\Tributario\Agua\Calculo\EstruturaFactory;
use ECidade\Tributario\Agua\Calculo\Isencao\Isencao;
use ECidade\Tributario\Agua\Calculo\Isencao\Imune;
use ECidade\Tributario\Agua\Calculo\Resultado;
use ECidade\Tributario\Agua\Calculo\ResultadoCollection;
use \AguaEstruturaTarifaria;
use \AguaCategoriaConsumo;

class Economia extends Processamento {

  /**
   * @var ResultadoCollection
   */
  private $oResultadoCollection;

  /**
   * @var Estrutura[]
   */
  private $aEstruturasFinalCalculo = array();

  public function __construct() {
    $this->oResultadoCollection = new ResultadoCollection;
  }

  /**
   * @return ResultadoCollection
   */
  public function getResultadoCollection() {
    return $this->oResultadoCollection;
  }

  /**
   * @param ResultadoCollection $oResultadoCollection
   */
  public function setResultadoCollection(ResultadoCollection $oResultadoCollection) {
    $this->oResultadoCollection = $oResultadoCollection;
  }

  /**
   * @param  float                  $nValor
   * @param  AguaEstruturaTarifaria $oEstruturaTarifaria
   */
  private function adicionarResultado($nValor, $oEstruturaTarifaria) {

    $oResultado = new Resultado;
    $oResultado->setValor($nValor);
    $oResultado->setEstrutura($oEstruturaTarifaria);

    $this->oResultadoCollection->adicionar($oResultado);
  }

  /**
   * @param  AguaEstruturaTarifaria $oEstruturaTarifaria
   * @param  float                  $nTotalConsumo
   */
  private function executarEstruturaCalculo(AguaEstruturaTarifaria $oEstruturaTarifaria, $nTotalConsumo = null) {

    $oEstruturaCalculo = EstruturaFactory::create($oEstruturaTarifaria->getCodigoTipoEstrutura());
    $oEstruturaCalculo->setEstruturaTarifaria($oEstruturaTarifaria);
    $oEstruturaCalculo->setConsumo($this->iConsumo);
    if ($nTotalConsumo !== null) {
      $oEstruturaCalculo->setValor($nTotalConsumo);
    }

    $nResultado = $oEstruturaCalculo->calcular();
    $this->adicionarResultado($nResultado, $oEstruturaTarifaria);
  }

  /**
   * @return boolean
   */
  private function hasIsencaoTarifaBasica() {

    if (!$this->oIsencao) {
      return false;
    }
    return $this->oIsencao->temIsencaoTarifaBasicaEsgoto() && $this->oIsencao->temIsencaoTarifaBasicaAgua();
  }

  /**
   * Calcula as isenções
   */
  private function calcularIsencao() {

    $aResultados   = $this->oResultadoCollection->getPorFaixaConsumo();
    $nTotalConsumo = $this->oResultadoCollection->getTotalPorConsumo();

    /**
     * Se tiver isenção, calcula o valor do desconto e aplica no tipo de consumo apropriado.
     */
    if ($this->oIsencao) {

      if (!$this->iCodigoTipoConsumoIsencao) {
        throw new \Exception('O código do tipo de consumo onde a isenção deve ser aplicada não foi informado.');
      }

      $this->oIsencao->setConsumo($this->iConsumo);
      $this->oIsencao->setCategoriaConsumo($this->oCategoriaConsumo);
      $this->oIsencao->setResultadosPorFaixaConsumo($aResultados);

      $nDesconto = $this->oIsencao->calcular($nTotalConsumo);
      if ($this->oIsencao instanceof Imune) {

        foreach ($this->oResultadoCollection->getPorTipoConsumo() as $iCodigo => $nValor) {
          $this->oResultadoCollection->aplicarDesconto($iCodigo, $nValor);
        }
      } else {
        $this->oResultadoCollection->aplicarDesconto($this->iCodigoTipoConsumoIsencao, $nDesconto);
      }
    }
  }

  /**
   * Calcula estruturas de faixa de consumo e valor fixo
   */
  private function calcularEstruturas() {

    foreach ($this->oCategoriaConsumo->getEstruturas() as $oEstruturaTarifaria) {

      /**
       * Desconsidera as estruturas de valor fixo, no caso de isenção de tarifas básicas
       */
      if ($this->hasIsencaoTarifaBasica() && $oEstruturaTarifaria->isValorFixo()) {
        continue;
      }

      if (!$oEstruturaTarifaria->isPercentual()) {
        $this->executarEstruturaCalculo($oEstruturaTarifaria);
      } else {

        /**
         * Adiciona na fila, o percentual só é aplicado ao final da execução do cálculo
         */
        $this->aEstruturasFinalCalculo[] = $oEstruturaTarifaria;
      }
    }
  }

  /**
   * Calcula estruturas de percentual em cima do valor do consumo de água
   */
  private function calcularPercentuais() {

    $nTotalConsumo = $this->oResultadoCollection->getTotalPorConsumo();

    foreach ($this->aEstruturasFinalCalculo as $oEstruturaTarifaria) {

      if ($nTotalConsumo > 0) {
        $this->executarEstruturaCalculo($oEstruturaTarifaria, $nTotalConsumo);
      }
    }
  }

  public function processar() {

    $this->calcularEstruturas();
    $this->calcularIsencao();
    $this->calcularPercentuais();

    return array(
      array(
        'responsavel' => null,
        'resultado' => $this->oResultadoCollection,
      ),
    );
  }
}

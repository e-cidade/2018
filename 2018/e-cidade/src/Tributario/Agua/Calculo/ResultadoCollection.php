<?php

namespace ECidade\Tributario\Agua\Calculo;

use \AguaEstruturaTarifaria;

class ResultadoCollection {

  /**
   * @var array
   */
  private $porTipoConsumo = array();

  /**
   * @var array
   */
  private $porFaixaConsumo = array();

  /**
   * @var array
   */
  private $porTipoEstrutura = array();

  /**
   * @var float
   */
  private $total = 0;

  /**
   * @var float
   */
  private $totalConsumo = 0;

  /**
   * @var Resultado[]
   */
  private $aResultados = array();

  /**
   * @return array
   */
  public function getPorTipoConsumo() {
    return $this->porTipoConsumo;
  }

  /**
   * @return array
   */
  public function getPorFaixaConsumo() {
    return $this->porFaixaConsumo;
  }

  /**
   * @return float
   */
  public function getTotalPorConsumo() {
    return $this->totalConsumo;
  }

  /**
   * @return array
   */
  public function getPorTipoEstrutura() {
    return $this->porTipoEstrutura;
  }

  /**
   * @return float
   */
  public function getTotal() {
    return $this->total;
  }

  /**
   * @param  Resultado $resultado
   */
  public function adicionar(Resultado $resultado) {

    $this->aResultados[] = $resultado;
    $this->incrementarPorFaixaConsumo($resultado);
    $this->incrementarPorTipoConsumo($resultado);
    $this->incrementarPorTipoEstrutura($resultado);
    $this->incrementarTotal($resultado);
    $this->incrementarTotalConsumo($resultado);
  }

  /**
   * @return Resultado[]
   */
  public function getResultados() {
    return $this->aResultados;
  }

  /**
   * @param  Resultado $resultado
   */
  private function incrementarPorTipoConsumo(Resultado $resultado) {

    $tipoConsumo = $resultado->getEstrutura()->getCodigoTipoConsumo();

    if (isset($this->porTipoConsumo[$tipoConsumo])) {
      $this->porTipoConsumo[$tipoConsumo] += $resultado->getValor();
    } else {
      $this->porTipoConsumo[$tipoConsumo] = $resultado->getValor();
    }
  }

  /**
   * @param  Resultado $resultado
   */
  private function incrementarPorFaixaConsumo(Resultado $resultado) {

    if ($resultado->getEstrutura()->getCodigoTipoEstrutura() == AguaEstruturaTarifaria::TIPO_FAIXA_CONSUMO) {
      $this->porFaixaConsumo[$resultado->getEstrutura()->getOrdem()] = $resultado->getValor();
    }
  }

  /**
   * @param  Resultado $resultado
   */
  private function incrementarPorTipoEstrutura(Resultado $resultado) {

    $tipoEstrutura = $resultado->getEstrutura()->getCodigoTipoEstrutura();

    if (isset($this->porTipoEstrutura[$tipoEstrutura])) {
      $this->porTipoEstrutura[$tipoEstrutura] += $resultado->getValor();
    } else {
      $this->porTipoEstrutura[$tipoEstrutura] = $resultado->getValor();
    }
  }

  /**
   * @param  Resultado $resultado
   */
  private function incrementarTotal(Resultado $resultado) {
    $this->total += $resultado->getValor();
  }

  /**
   * @param  Resultado $resultado
   */
  private function incrementarTotalConsumo(Resultado $resultado) {

    if ($resultado->getEstrutura()->getCodigoTipoEstrutura() == AguaEstruturaTarifaria::TIPO_FAIXA_CONSUMO) {
      $this->totalConsumo += $resultado->getValor();
    }
  }

  /**
   * @param  integer $tipoConsumo
   * @param  float   $desconto
   */
  public function aplicarDesconto($tipoConsumo, $desconto) {

    if (!isset($this->porTipoConsumo[$tipoConsumo])) {
      throw new \InvalidArgumentException('O tipo de consumo informado não existe.');
    }

    $this->porTipoConsumo[$tipoConsumo] -= $desconto;
    $this->totalConsumo -= $desconto;
    $this->total -= $desconto;
  }
}

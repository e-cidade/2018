<?php

namespace ECidade\Tributario\Agua\Calculo\Processamento;

use \AguaContratoEconomia;
use \AguaCategoriaConsumo;
use ECidade\Tributario\Agua\Calculo\Isencao\Isencao;

abstract class Processamento {

  /**
   * @var integer
   */
  protected $iConsumo;

  /**
   * @var AguaCategoriaConsumo
   */
  protected $oCategoriaConsumo;

  /**
   * @var Isencao
   */
  protected $oIsencao;

  /**
   * @var int
   */
  protected $iCodigoTipoConsumoIsencao;

  /**
   * @var array
   */
  protected $aEconomias = array();

  /**
   * @param AguaCategoriaConsumo $oCategoriaConsumo
   */
  public function setCategoriaConsumo(AguaCategoriaConsumo $oCategoriaConsumo) {
    $this->oCategoriaConsumo = $oCategoriaConsumo;
  }

  /**
   * @return AguaCategoriaConsumo
   */
  public function getCategoriaConsumo() {
    return $this->oCategoriaConsumo;
  }

  /**
   * @param Isencao $oIsencao
   */
  public function setIsencao(Isencao $oIsencao) {
    $this->oIsencao = $oIsencao;
  }

  /**
   * @return Isencao
   */
  public function getIsencao() {
    return $this->oIsencao;
  }

  /**
   * @param AguaContratoEconomia $oEconomia
   */
  public function adicionarEconomia(AguaContratoEconomia $oEconomia) {
    $this->aEconomias[] = $oEconomia;
  }

  /**
   * @return AguaContratoEconomia[]
   */
  public function getEconomias() {
    return $this->aEconomias;
  }

  /**
   * @param integer $iConsumo
   */
  public function setConsumo($iConsumo) {
    $this->iConsumo = $iConsumo;
  }

  /**
   * @return integer
   */
  public function getConsumo() {
    return $this->iConsumo;
  }

  /**
   * @param $iCodigoTipoConsumoIsencao
   */
  public function setCodigoTipoConsumoIsencao($iCodigoTipoConsumoIsencao) {
    $this->iCodigoTipoConsumoIsencao = $iCodigoTipoConsumoIsencao;
  }

  /**
   * @return int
   */
  public function getCodigoTipoConsumoIsencao() {
    return $this->iCodigoTipoConsumoIsencao;
  }

  abstract public function processar();
}

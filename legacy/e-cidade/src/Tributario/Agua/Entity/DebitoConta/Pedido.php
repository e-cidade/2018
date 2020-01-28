<?php

namespace ECidade\Tributario\Agua\Entity\DebitoConta;

use DateTime;
use AguaContratoEconomia as Economia;
use AguaContrato as Contrato;
use ECidade\Tributario\Arrecadacao\TipoDebito;

class Pedido
{

  /**
   * @var int
   */
  private $iCodigo;

  /**
   * @var int
   */
  private $iInstituicao;

  /**
   * @var int
   */
  private $iBanco;

  /**
   * @var string
   */
  private $sAgencia;

  /**
   * @var string
   */
  private $sConta;

  /**
   * @var DateTime
   */
  private $oDataLancamento;

  /**
   * @var int
   */
  private $iStatus;

  /**
   * @var string
   */
  private $sIdEmpresa;

  /**
   * @var Contrato
   */
  private $oContrato;

  /**
   * @var Economia
   */
  private $oEconomia;

  /**
   * @var TipoDebito[]
   */
  private $aTipoDebito = array();

  public function getCodigo() {
    return $this->iCodigo;
  }

  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  public function getInstituicao() {
    return $this->iInstituicao;
  }

  public function setInstituicao($iInstituicao) {
    $this->iInstituicao = $iInstituicao;
  }

  public function getBanco() {
    return $this->iBanco;
  }

  public function setBanco($iBanco) {
    $this->iBanco = $iBanco;
  }

  public function getAgencia() {
    return $this->sAgencia;
  }

  public function setAgencia($sAgencia) {
    $this->sAgencia = $sAgencia;
  }

  public function getConta() {
    return $this->sConta;
  }

  public function setConta($sConta) {
    $this->sConta = $sConta;
  }

  public function getDataLancamento() {
    return $this->oDataLancamento;
  }

  public function setDataLancamento(DateTime $oDataLancamento) {
    $this->oDataLancamento = $oDataLancamento;
  }

  public function getStatus() {
    return $this->iStatus;
  }

  public function setStatus($iStatus) {
    $this->iStatus = $iStatus;
  }

  public function getIdEmpresa() {
    return $this->sIdEmpresa;
  }

  public function setIdEmpresa($sIdEmpresa) {
    $this->sIdEmpresa = $sIdEmpresa;
  }

  public function getContrato() {
    return $this->oContrato;
  }

  public function setContrato(Contrato $oContrato) {
    $this->oContrato = $oContrato;
  }

  public function getEconomia() {
    return $this->oEconomia;
  }

  public function setEconomia(Economia $oEconomia) {
    $this->oEconomia = $oEconomia;
  }

  public function getTiposDebito() {
    return $this->aTipoDebito;
  }

  public function setTiposDebito(array $aTipos) {
    $this->aTipoDebito = $aTipos;
  }

  public function adicionarTipoDebito(TipoDebito $oPedidoTipo) {
    $this->aTipoDebito[] = $oPedidoTipo;
  }
}

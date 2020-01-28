<?php

namespace ECidade\Tributario\Agua\Entity\Calculo;

use ECidade\Tributario\Agua\Repository\Calculo as CalculoRepository;

class Calculo {

  /**
   * @var integer
   */
  private $iCodigo;

  /**
   * @var integer
   */
  private $iCodigoConsumo;

  /**
   * @var integer
   */
  private $iCodigoUsuario;

  /**
   * @var integer
   */
  private $iCodigoContrato;

  /**
   * @var integer
   */
  private $iCodigoEconomia;

  /**
   * @var integer
   */
  private $iCodigoProcessamento;

  /**
   * @var integer
   */
  private $iCodigoMatricula;

  /**
   * @var integer
   */
  private $iExercicio;

  /**
   * @var integer
   */
  private $iMes;

  /**
   * @var integer
   */
  private $nArea;

  /**
   * @var string
   */
  private $sManual;

  /**
   * @var integer
   */
  private $iTipo;

  /**
   * @var \DateTime
   */
  private $oData;

  /**
   * @var string
   */
  private $sHora;

  /**
   * @var array
   */
  private $aValores;

  /**
   * @var CalculoRepository
   */
  private $oRepository;

  public function __construct() {
    $this->oRepository = new CalculoRepository;
  }

  /**
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @param integer $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @return integer
   */
  public function getCodigoConsumo() {
    return $this->iCodigoConsumo;
  }

  /**
   * @param integer $iCodigoConsumo
   */
  public function setCodigoConsumo($iCodigoConsumo) {
    $this->iCodigoConsumo = $iCodigoConsumo;
  }

  /**
   * @return integer
   */
  public function getCodigoUsuario() {
    return $this->iCodigoUsuario;
  }

  /**
   * @param integer $iCodigoUsuario
   */
  public function setCodigoUsuario($iCodigoUsuario) {
    $this->iCodigoUsuario = $iCodigoUsuario;
  }

  /**
   * @return integer
   */
  public function getCodigoContrato() {
    return $this->iCodigoContrato;
  }

  /**
   * @param integer $iCodigoContrato
   */
  public function setCodigoContrato($iCodigoContrato) {
    $this->iCodigoContrato = $iCodigoContrato;
  }

  /**
   * @return integer
   */
  public function getCodigoEconomia() {
    return $this->iCodigoEconomia;
  }

  /**
   * @param integer $iCodigoEconomia
   */
  public function setCodigoEconomia($iCodigoEconomia) {
    $this->iCodigoEconomia = $iCodigoEconomia;
  }

  /**
   * @return integer
   */
  public function getCodigoRecibo() {
    return $this->iCodigoProcessamento;
  }

  /**
   * @param integer $iCodigoProcessamento
   */
  public function setCodigoProcessamento($iCodigoProcessamento) {
    $this->iCodigoProcessamento = $iCodigoProcessamento;
  }

  /**
   * @return integer
   */
  public function getCodigoProcessamento() {
    return $this->iCodigoProcessamento;
  }

  /**
   * @return integer
   */
  public function getCodigoMatricula() {
    return $this->iCodigoMatricula;
  }

  /**
   * @param integer $iCodigoMatricula
   */
  public function setCodigoMatricula($iCodigoMatricula) {
    $this->iCodigoMatricula = $iCodigoMatricula;
  }

  /**
   * @return integer
   */
  public function getExercicio() {
    return $this->iExercicio;
  }

  /**
   * @param integer $iExercicio
   */
  public function setExercicio($iExercicio) {
    $this->iExercicio = $iExercicio;
  }

  /**
   * @return integer
   */
  public function getMes() {
    return $this->iMes;
  }

  /**
   * @param integer $iMes
   */
  public function setMes($iMes) {
    $this->iMes = $iMes;
  }

  /**
   * @return float
   */
  public function getArea() {
    return $this->nArea;
  }

  /**
   * @param float $nArea
   */
  public function setArea($nArea) {
    $this->nArea = $nArea;
  }

  /**
   * @return string
   */
  public function getManual() {
    return $this->sManual;
  }

  /**
   * @param string $iManual
   */
  public function setManual($iManual) {
    $this->sManual = $iManual;
  }

  /**
   * @return integer
   */
  public function getTipo() {
    return $this->iTipo;
  }

  /**
   * @param integer $iTipo
   */
  public function setTipo($iTipo) {
    $this->iTipo = $iTipo;
  }

  /**
   * @return \DateTime
   */
  public function getData() {
    return $this->oData;
  }

  /**
   * @param \DateTime $oData
   */
  public function setData(\DateTime $oData = null) {
    $this->oData = $oData;
  }

  /**
   * @return string
   */
  public function getHora() {
    return $this->sHora;
  }

  /**
   * @param string $sHora
   */
  public function setHora($sHora) {
    $this->sHora = $sHora;
  }

  /**
   * @return Valor[]
   */
  public function getValores() {

    if ($this->aValores === null && $this->iCodigo) {
      $this->aValores = $this->oRepository->findValores($this->iCodigo);
    }
    return $this->aValores;
  }

  /**
   * @param Valor[] $aValores
   */
  public function setValores(array $aValores) {
    $this->aValores = $aValores;
  }

  /**
   * @param Valor $oValor
   */
  public function adicionarValor(Valor $oValor) {

    $oValor->setCodigoCalculo($this->iCodigo);
    $this->aValores[$oValor->getCodigoTipoConsumo()] = $oValor;
  }

  /**
   * @param Valor $oValor
   */
  public function removerValor(Valor $oValor) {
    unset($this->aValores[$oValor->getCodigoTipoConsumo()]);
  }
}

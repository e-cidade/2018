<?php

/**
 * Class ContaCorrenteDetalhe
 */
class ContaCorrenteDetalhe {

  /**
   * @var Recurso
   */
  private $oRecurso;

  /**
   * @var string
   */
  private $sEstrutural;

  /**
   * @var Dotacao
   */
  private $oDotacao;

  /**
   * @var EmpenhoFinanceiro
   */
  private $oEmpenho;

  /**
   * @var ContaBancaria
   */
  private $oContaBancaria;

  /**
   * @var Acordo
   */
  private $oAcordo;

  /**
   * @var CgmBase
   */
  private $oCredor;

  /**
   * @param ContaBancaria $oContaBancaria
   */
  public function setContaBancaria(ContaBancaria $oContaBancaria = null) {
    $this->oContaBancaria = $oContaBancaria;
  }

  /**
   * @param Recurso $oRecurso
   */
  public function setRecurso(Recurso $oRecurso = null) {
    $this->oRecurso = $oRecurso;
  }

  /**
   * @return Recurso
   */
  public function getRecurso() {
    return $this->oRecurso;
  }

  /**
   * @param string $sEstrutural
   */
  public function setEstrutural($sEstrutural = null) {
    $this->sEstrutural = $sEstrutural;
  }

  /**
   * @return string
   */
  public function getEstrutural() {
    return $this->sEstrutural;
  }

  /**
   * @param Dotacao $oDotacao
   */
  public function setDotacao(Dotacao $oDotacao = null) {
    $this->oDotacao = $oDotacao;
  }

  /**
   * @return ContaBancaria
   */
  public function getContaBancaria() {
    return $this->oContaBancaria;
  }

  /**
   * @return Dotacao
   */
  public function getDotacao() {
    return $this->oDotacao;
  }

  /**
   * @param EmpenhoFinanceiro $oEmpenho
   */
  public function setEmpenho(EmpenhoFinanceiro $oEmpenho = null) {
    $this->oEmpenho = $oEmpenho;
  }

  /**
   * @return EmpenhoFinanceiro
   */
  public function getEmpenho() {
    return $this->oEmpenho;
  }

  /**
   * @param Acordo $oAcordo
   */
  public function setAcordo(Acordo $oAcordo = null) {
    $this->oAcordo = $oAcordo;
  }

  /**
   * @return Acordo
   */
  public function getAcordo() {
    return $this->oAcordo;
  }

  /**
   * @param CgmBase $oCredor
   */
  public function setCredor(CgmBase $oCredor = null) {
    $this->oCredor = $oCredor;
  }

  /**
   * @return CgmBase
   */
  public function getCredor() {
    return $this->oCredor;
  }
}

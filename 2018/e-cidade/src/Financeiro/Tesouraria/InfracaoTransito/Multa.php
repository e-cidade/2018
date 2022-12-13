<?php

namespace ECidade\Financeiro\Tesouraria\InfracaoTransito;

use DateTime;

/**
 * Class Multa
 * Classe que representa a Multa
 * @package ECidade\Financeiro\Tesouraria\InfracaoTransito
 */

class Multa {

  /**
   * @var int
   */
  private $iCodigo;

  /**
   * @var ArquivoInfracao
   */
  private $oArquivoInfracao;

  /**
   * @var int
   */
  private $iIdArquivoInfracao;

  /**
   * @var int
   */
  private $iCodigoInfracaoTransito;

  /**
   * @var DateTime
   */
  private $oDataPagamento;

  /**
   * @var DateTime
   */
  private $oDataRepasse;

  /**
   * @var int
   */
  private $iNivel;

  /**
   * @var float
   */
  private $nValorFunset;

  /**
   * @var float
   */
  private $nValorDetran;

  /**
   * @var float
   */
  private $nValorPrefeitura;

  /**
   * @var float
   */
  private $nValorBruto;

  /**
   * @var string
   */
  private $sNossoNumero;

  /**
   * @var string
   */
  private $sAutoInfracao;

  /**
   * @var boolean
   */
  private $lDuplicado;

  /**
   * @return int
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @param int $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @return ArquivoInfracao
   */
  public function getArquivoInfracao() {
    return $this->oArquivoInfracao;
  }

  /**
   * @param ArquivoInfracao $oArquivoInfracao
   */
  public function setArquivoInfracao(ArquivoInfracao $oArquivoInfracao) {
    $this->oArquivoInfracao = $oArquivoInfracao;
    $this->setIdArquivoInfracao($oArquivoInfracao->getId());
  }

  /**
   * @return int
   */
  public function getIdArquivoInfracao() {
    return $this->iIdArquivoInfracao;
  }

    /**
     * @param int $iIdArquivoInfracao
     */
  public function setIdArquivoInfracao($iIdArquivoInfracao) {
    $this->iIdArquivoInfracao = $iIdArquivoInfracao;
  }

  /**
   * @return int
   */
  public function getCodigoInfracaoTransito() {
    return $this->iCodigoInfracaoTransito;
  }

  /**
   * @param int $iCodigoInfracaoTransito
   */
  public function setCodigoInfracaoTransito($iCodigoInfracaoTransito) {
    $this->iCodigoInfracaoTransito = $iCodigoInfracaoTransito;
  }

  /**
   * @return DateTime
   */
  public function getDataPagamento() {
    return $this->oDataPagamento;
  }

  /**
   * @param DateTime $oDataPagamento
   */
  public function setDataPagamento(DateTime $oDataPagamento) {
    $this->oDataPagamento = $oDataPagamento;
  }

  /**
   * @return DateTime
   */
  public function getDataRepasse() {
    return $this->oDataRepasse;
  }

  /**
   * @param DateTime $oDataRepasse
   */
  public function setDataRepasse(DateTime $oDataRepasse) {
    $this->oDataRepasse = $oDataRepasse;
  }

  /**
   * @return int
   */
  public function getNivel() {
    return $this->iNivel;
  }

  /**
   * @param int $iNivel
   */
  public function setNivel($iNivel) {
    $this->iNivel = $iNivel;
  }

  /**
   * @return float
   */
  public function getValorFunset() {
    return $this->nValorFunset;
  }

  /**
   * @param float $nValorFunset
   */
  public function setValorFunset($nValorFunset) {
    $this->nValorFunset = $nValorFunset;
  }

  /**
   * @return int
   */
  public function getValorDetran() {
    return $this->nValorDetran;
  }

  /**
   * @param float $nValorDetran
   */
  public function setValorDetran($nValorDetran) {
    $this->nValorDetran = $nValorDetran;
  }

  /**
   * @return float
   */
  public function getValorPrefeitura() {
    return $this->nValorPrefeitura;
  }

  /**
   * @param float $nValorPrefeitura
   */
  public function setValorPrefeitura($nValorPrefeitura) {
    $this->nValorPrefeitura = $nValorPrefeitura;
  }

  /**
   * @return float
   */
  public function getValorBruto() {
    return $this->nValorBruto;
  }

  /**
   * @param float $nValorBruto
   */
  public function setValorBruto($nValorBruto) {
    $this->nValorBruto = $nValorBruto;
  }

  /**
   * @return string
   */
  public function getNossoNumero() {
    return $this->sNossoNumero;
  }

  /**
   * @param string $sNossoNumero
   */
  public function setNossoNumero($sNossoNumero) {
    $this->sNossoNumero = $sNossoNumero;
  }

  /**
   * @return string
   */
  public function getAutoInfracao() {
    return $this->sAutoInfracao;
  }

  /**
   * @param string $sAutoInfracao
   */
  public function setAutoInfracao($sAutoInfracao) {
    $this->sAutoInfracao = $sAutoInfracao;
  }

  /**
   * @return bool
   */
  public function isDuplicado()
  {
    return $this->lDuplicado;
  }

  /**
   * @param bool $lDuplicado
   */
  public function setDuplicado($lDuplicado = FALSE)
  {
    $this->lDuplicado = $lDuplicado;
  }
}

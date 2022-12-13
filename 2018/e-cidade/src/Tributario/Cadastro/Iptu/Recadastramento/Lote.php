<?php
namespace ECidade\Tributario\Cadastro\Iptu\Recadastramento;

class Lote
{

  /**
   * Código da matrícula
   * @var integer
   */
  private $iMatricula = null;

  /**
   *  Código do Setor
   * @var string
   */
  private $sSetor = null;

  /**
   * Total da área do lote.
   * @var integer
   */
  private $iLoteArea = null;

  /**
   * Valor da testada
   * @var integer
   */
  private $iValorTestada = null;

  /**
   * Array de características
   * @var array
   */
  private $aCaracteristicasLote = array();

  /**
   * Código do Lote(idbql)
   * @var integer
   */
  private $iIdbql = null;

  /**
   * Retorna a matrícula do lote
   * @return integer
   */
  public function getMatricula() {
    return $this->iMatricula;
  }

  /**
   * Retorna o código do Setor
   * @return string
   */
  public function getSetor() {
    return $this->sSetor;
  }

  /**
   * Retorna a área do lote
   * @return integer
   */
  public function getLoteArea() {
    return $this->iLoteArea;
  }

  /**
   * Retorna o valor da testada
   * @return integer
   */
  public function getValorTestada() {
    return $this->iValorTestada;
  }

  /**
   * Retorna as características do lote
   * @return array
   */
  public function getCaracteristicasLote() {
    return $this->aCaracteristicasLote;
  }

  /**
   * Retorna o código do lote(idbql)
   * @return integer
   */
  public function getIdbql() {
    return $this->iIdbql;
  }

  /**
   * Define o código da matrícula
   * @param integer $iMatricula
   */
  public function setMatricula($iMatricula) {
    $this->iMatricula = $iMatricula;
  }

  /**
   * Define o código do setor
   * @param string $sSetor
   */
  public function setSetor($sSetor) {
    $this->sSetor = str_pad($sSetor, 4, "0", STR_PAD_LEFT);
  }

  /**
   * Define a área do lote
   * @param integer $iLoteArea
   */
  public function setLoteArea($iLoteArea) {
    $this->iLoteArea = $iLoteArea;
  }

  /**
   * Define o valor da testada
   * @param integer $iValorTestada
   */
  public function setValorTestada($iValorTestada) {
    $this->iValorTestada = $iValorTestada;
  }

  /**
   * Define as características do lote
   * @param array $aCaracteristicasLote
   */
  public function setCaracteristicasLote($aCaracteristicasLote) {
    $this->aCaracteristicasLote = $aCaracteristicasLote;
  }

  /**
   * Define o código do lote(idbql)
   * @param integer $iIdbql
   */
  public function setIdbql($iIdbql) {
    $this->iIdbql = $iIdbql;
  }

  /**
   * Atualiza os dados do lote da matrícula informada
   */
  public function atualizar() {

    if ( empty($this->iMatricula) ) {
      throw new \BussinessException("Matrícula não informada.");
    }

    if ( empty($this->iIdbql) ) {
      throw new \BussinessException("Código do lote não informado.");
    }

    if ( empty($this->iValorTestada) ) {
      throw new \BussinessException("Código do lote não informado.");
    }

    $oDaoLote            = new \cl_lote();
    $oDaoLote->j34_idbql = $this->iIdbql;
    $oDaoLote->j34_setor = $this->sSetor;
    $oDaoLote->j34_area  = $this->iLoteArea;
    $oDaoLote->alterar($this->iIdbql);

    if ( $oDaoLote->erro_status == '0' ) {
      throw new \DBException("Erro ao atualizar o lote da matrícula {$this->iMatricula}");
    }

    $oDaoTestada             = new \cl_testada();
    $oDaoTestada->j36_idbql  = $this->iIdbql;
    $oDaoTestada->j36_testad = $this->iValorTestada;
    $oDaoTestada->alterar($this->iIdbql);

    if ( $oDaoTestada->erro_status == '0' ) {
      throw new \DBException("Erro ao atualizar a tetada da matrícula {$this->iMatricula}");
    }

    $oDaoCarlote = new \cl_carlote();

    $oDaoCarlote->excluir($this->iIdbql);

    if ( $oDaoCarlote->erro_status == '0' ) {
      throw new \DBException("Erro ao excluir características do lote.");
    }

    foreach ($this->aCaracteristicasLote as $iCaracteristica) {

      $oDaoCarlote->incluir($this->iIdbql,$iCaracteristica);

      if ( $oDaoCarlote->erro_status == '0' ) {
        throw new \DBException("Erro ao atualizar as características do lote.");
      }
    }
  }
}
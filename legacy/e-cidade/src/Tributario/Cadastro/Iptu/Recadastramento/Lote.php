<?php
namespace ECidade\Tributario\Cadastro\Iptu\Recadastramento;

class Lote
{

  /**
   * C�digo da matr�cula
   * @var integer
   */
  private $iMatricula = null;

  /**
   *  C�digo do Setor
   * @var string
   */
  private $sSetor = null;

  /**
   * Total da �rea do lote.
   * @var integer
   */
  private $iLoteArea = null;

  /**
   * Valor da testada
   * @var integer
   */
  private $iValorTestada = null;

  /**
   * Array de caracter�sticas
   * @var array
   */
  private $aCaracteristicasLote = array();

  /**
   * C�digo do Lote(idbql)
   * @var integer
   */
  private $iIdbql = null;

  /**
   * Retorna a matr�cula do lote
   * @return integer
   */
  public function getMatricula() {
    return $this->iMatricula;
  }

  /**
   * Retorna o c�digo do Setor
   * @return string
   */
  public function getSetor() {
    return $this->sSetor;
  }

  /**
   * Retorna a �rea do lote
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
   * Retorna as caracter�sticas do lote
   * @return array
   */
  public function getCaracteristicasLote() {
    return $this->aCaracteristicasLote;
  }

  /**
   * Retorna o c�digo do lote(idbql)
   * @return integer
   */
  public function getIdbql() {
    return $this->iIdbql;
  }

  /**
   * Define o c�digo da matr�cula
   * @param integer $iMatricula
   */
  public function setMatricula($iMatricula) {
    $this->iMatricula = $iMatricula;
  }

  /**
   * Define o c�digo do setor
   * @param string $sSetor
   */
  public function setSetor($sSetor) {
    $this->sSetor = str_pad($sSetor, 4, "0", STR_PAD_LEFT);
  }

  /**
   * Define a �rea do lote
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
   * Define as caracter�sticas do lote
   * @param array $aCaracteristicasLote
   */
  public function setCaracteristicasLote($aCaracteristicasLote) {
    $this->aCaracteristicasLote = $aCaracteristicasLote;
  }

  /**
   * Define o c�digo do lote(idbql)
   * @param integer $iIdbql
   */
  public function setIdbql($iIdbql) {
    $this->iIdbql = $iIdbql;
  }

  /**
   * Atualiza os dados do lote da matr�cula informada
   */
  public function atualizar() {

    if ( empty($this->iMatricula) ) {
      throw new \BussinessException("Matr�cula n�o informada.");
    }

    if ( empty($this->iIdbql) ) {
      throw new \BussinessException("C�digo do lote n�o informado.");
    }

    if ( empty($this->iValorTestada) ) {
      throw new \BussinessException("C�digo do lote n�o informado.");
    }

    $oDaoLote            = new \cl_lote();
    $oDaoLote->j34_idbql = $this->iIdbql;
    $oDaoLote->j34_setor = $this->sSetor;
    $oDaoLote->j34_area  = $this->iLoteArea;
    $oDaoLote->alterar($this->iIdbql);

    if ( $oDaoLote->erro_status == '0' ) {
      throw new \DBException("Erro ao atualizar o lote da matr�cula {$this->iMatricula}");
    }

    $oDaoTestada             = new \cl_testada();
    $oDaoTestada->j36_idbql  = $this->iIdbql;
    $oDaoTestada->j36_testad = $this->iValorTestada;
    $oDaoTestada->alterar($this->iIdbql);

    if ( $oDaoTestada->erro_status == '0' ) {
      throw new \DBException("Erro ao atualizar a tetada da matr�cula {$this->iMatricula}");
    }

    $oDaoCarlote = new \cl_carlote();

    $oDaoCarlote->excluir($this->iIdbql);

    if ( $oDaoCarlote->erro_status == '0' ) {
      throw new \DBException("Erro ao excluir caracter�sticas do lote.");
    }

    foreach ($this->aCaracteristicasLote as $iCaracteristica) {

      $oDaoCarlote->incluir($this->iIdbql,$iCaracteristica);

      if ( $oDaoCarlote->erro_status == '0' ) {
        throw new \DBException("Erro ao atualizar as caracter�sticas do lote.");
      }
    }
  }
}
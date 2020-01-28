<?php

/**
 * Class DocumentoEventoContabil
 */
class DocumentoEventoContabil {

  const TIPO_PAGAMENTO_EMPENHO         = 30;
  const TIPO_ESTORNO_PAGAMENTO_EMPENHO = 31;

  const TIPO_ARRECADACAO_RECEITA         = 100;
  const TIPO_ESTORNO_ARRECADACAO_RECEITA = 101;


  /**
   * @var int
   */
  private $iCodigo;

  /**
   * @var string
   */
  private $sDescricao;

  /**
   * @var int
   */
  private $iTipo;


  public function __construct() {}

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
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * @return int
   */
  public function getTipo() {
    return $this->iTipo;
  }

  /**
   * @param int $iTipo
   */
  public function setTipo($iTipo) {
    $this->iTipo = $iTipo;
  }
}
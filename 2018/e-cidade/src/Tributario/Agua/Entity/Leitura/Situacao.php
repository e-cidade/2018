<?php

namespace ECidade\Tributario\Agua\Entity\Leitura;

class Situacao {

  const REGRA_NORMAL                = 0;
  const REGRA_SEM_LEITURA_SEM_SALDO = 1;
  const REGRA_CANCELAMENTO          = 2;
  const REGRA_SEM_LEITURA_COM_SALDO = 3;
  const REGRA_MEDIA_ULTIMOS_MESES   = 4;
  const REGRA_MEDIA_PENALIDADE      = 5;

  /**
   * @var integer
   */
  private $codigo;

  /**
   * @var string
   */
  private $descricao;

  /**
   * @var integer
   */
  private $regra;

  /**
   * @return integer
   */
  public function getCodigo() {
    return $this->codigo;
  }

  /**
   * @param integer $codigo
   */
  public function setCodigo($codigo) {
    $this->codigo = $codigo;
  }

  /**
   * @return string
   */
  public function getDescricao() {
    return $this->descricao;
  }

  /**
   * @param string $descricao
   */
  public function setDescricao($descricao) {
    $this->descricao = $descricao;
  }

  /**
   * @return integer
   */
  public function getRegra() {
    return $this->regra;
  }

  /**
   * @param integer $regra
   */
  public function setRegra($regra) {
    $this->regra = $regra;
  }

  public function isMedia() {

    return in_array($this->regra, array(
      self::REGRA_MEDIA_ULTIMOS_MESES,
      self::REGRA_MEDIA_PENALIDADE,
    ));
  }

  public function isMediaUltimosMeses() {
    return (int) $this->regra === self::REGRA_MEDIA_ULTIMOS_MESES;
  }

  public function isMediaPenalidade() {
    return (int) $this->regra === self::REGRA_MEDIA_PENALIDADE;
  }
}

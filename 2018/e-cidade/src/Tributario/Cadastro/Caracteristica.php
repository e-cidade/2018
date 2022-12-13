<?php

namespace ECidade\Tributario\Cadastro;

use BusinessException;
use DBException;
use cl_caracter;

class Caracteristica {

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
  private $iCodigoGrupo;

  /**
   * @var int
   */
  private $iPontos;

  /**
   * @param int $iCodigo
   *
   * @throws BusinessException
   * @throws DBException
   */
  public function __construct($iCodigo = null) {

    if ($iCodigo) {

      $oDao = new cl_caracter();
      $sSql = $oDao->sql_query($iCodigo);
      $rsDados = db_query($sSql);

      if (!$rsDados) {
        throw new DBException('Ocorreu um erro ao buscar a Característica.');
      }

      if (pg_num_rows($rsDados) === 0) {
        throw new BusinessException('Característica não encontrada.');
      }

      $oDados = pg_fetch_object($rsDados);

      $this->iCodigo = $iCodigo;
      $this->sDescricao = $oDados->j31_descr;
      $this->iCodigoGrupo = $oDados->j31_grupo;
      $this->iPontos = $oDados->j31_pontos;
    }
  }

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
  public function getCodigoGrupo() {
    return $this->iCodigoGrupo;
  }

  /**
   * @param int $iCodigoGrupo
   */
  public function setCodigoGrupo($iCodigoGrupo) {
    $this->iCodigoGrupo = $iCodigoGrupo;
  }

  /**
   * @return int
   */
  public function getPontos() {
    return $this->iPontos;
  }

  /**
   * @param int $iPontos
   */
  public function setPontos($iPontos) {
    $this->iPontos = $iPontos;
  }
}

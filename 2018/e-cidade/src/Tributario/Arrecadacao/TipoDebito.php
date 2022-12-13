<?php

namespace ECidade\Tributario\Arrecadacao;

use BusinessException;
use DBException;
use cl_arretipo;

class TipoDebito {

  /**
   * @var int
   */
  private $iCodigo;

  /**
   * @var int
   */
  private $iCodigoInstituicao;

  /**
   * @var int
   */
  private $iCodigoGrupoDebito;

  /**
   * @var string
   */
  private $sDescricao;

  /**
   * @param int $iCodigo
   *
   * @throws BusinessException
   * @throws DBException
   */
  public function __construct($iCodigo = null) {

    if ($iCodigo) {

      $oDao = new cl_arretipo();
      $sSql = $oDao->sql_query($iCodigo);
      $rsDados = db_query($sSql);

      if (!$rsDados) {
        throw new DBException('Ocorreu um erro ao buscar o Tipo de Débito.');
      }

      if (pg_num_rows($rsDados) === 0) {
        throw new BusinessException('Tipo de Débito não encontrado.');
      }

      $oDados = pg_fetch_object($rsDados);

      $this->iCodigo = $iCodigo;
      $this->iCodigoGrupoDebito = $oDados->k03_tipo;
      $this->iCodigoInstituicao = $oDados->k00_instit;
      $this->sDescricao = $oDados->k00_descr;
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
   * @return int
   */
  public function getCodigoInstituicao() {
    return $this->iCodigoInstituicao;
  }

  /**
   * @param int $iCodigoInstituicao
   */
  public function setCodigoInstituicao($iCodigoInstituicao) {
    $this->iCodigoInstituicao = $iCodigoInstituicao;
  }

  /**
   * @return int
   */
  public function getCodigoGrupoDebito() {
    return $this->iCodigoGrupoDebito;
  }

  /**
   * @param int $iCodigoGrupoDebito
   */
  public function setCodigoGrupoDebito($iCodigoGrupoDebito) {
    $this->iCodigoGrupoDebito = $iCodigoGrupoDebito;
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
}

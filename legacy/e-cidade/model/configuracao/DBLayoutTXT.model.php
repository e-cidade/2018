<?php

/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 02/05/16
 * Time: 17:44
 */
class DBLayoutTXT {

  private $iCodigo;

  private $sDescricao;

  public function __construct($iCodigo = null) {

    if (empty($iCodigo)) {
      return;
    }

    $oDaoDBLayout = new cl_db_layouttxt();
    $oDadosLayout         = db_utils::getRowFromDao($oDaoDBLayout, array($iCodigo));
    if (empty($oDadosLayout)) {
      return;
    }

    $this->setCodigo($oDadosLayout->db50_codigo);
    $this->setDescricao($oDadosLayout->db50_descr);

  }

  /**
   * @return mixed
   */
  public function getDescricao() {

    return $this->sDescricao;
  }

  /**
   * @param mixed $sDescricao
   */
  public function setDescricao($sDescricao) {

    $this->sDescricao = $sDescricao;
  }

  /**
   * @return mixed
   */
  public function getCodigo() {

    return $this->iCodigo;
  }

  /**
   * @param mixed $iCodigo
   */
  public function setCodigo($iCodigo) {

    $this->iCodigo = $iCodigo;
  }

}
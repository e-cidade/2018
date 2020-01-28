<?php

/**
 * Class VeiculoTipoManutencao
 */
class VeiculoTipoManutencao{

  /**
   * @type integer
   */
  private $iCodigo;

  /**
   * @type string
   */
  private $sDescricao;


  public function __construct($iCodigo) {

    if (empty($iCodigo)) {
      throw new ParameterException("C�digo informado por par�metro n�o existente.");
    }

    $oDaoTipoServico = new cl_veiccadtiposervico();
    $rsBuscaServico  = $oDaoTipoServico->sql_record($oDaoTipoServico->sql_query_file($iCodigo));

    if (!$rsBuscaServico || $oDaoTipoServico->erro_status == "0") {
      throw new BusinessException("N�o localizado tipo de servi�o com c�digo {$iCodigo}.");
    }

    $oStdTipoServico  = db_utils::fieldsMemory($rsBuscaServico, 0);
    $this->iCodigo    = $oStdTipoServico->ve28_codigo;
    $this->sDescricao = $oStdTipoServico->ve28_descr;
    unset($oStdTipoServico);
  }

  /**
   * @return int
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }
}
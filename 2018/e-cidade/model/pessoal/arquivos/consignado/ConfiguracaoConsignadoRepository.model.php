<?php

class ConfiguracaoConsignadoRepository {

  static $aItens = array();

  private
  function __construct() {

  }

  /**
   *
   * @param $iCodigo
   * @return ConfiguracaoConsignado
   */
  public static function getByCodigo($iCodigo) {

    if (empty(self::$aItens[$iCodigo])) {
      self::$aItens[$iCodigo] = new ConfiguracaoConsignado($iCodigo);
    }
    return self::$aItens[$iCodigo];
  }

  /**
   * @param \Instituicao $oInstituicao
   * @return \ConfiguracaoConsignado[]
   * @throws \DBException
   * @throws \ParameterException
   */
  public static function getConfiguracaoInstituicao(Instituicao $oInstituicao) {

    if (empty($oInstituicao)) {
      throw new ParameterException("Instituição nao informada");
    }
    $oDaoConfiguracaoConsignado = new cl_rhconsignacaobancolayout();
    $sSql = $oDaoConfiguracaoConsignado->sql_query_file(null, "*", "rh178_sequencial", "rh178_instit = {$oInstituicao->getCodigo()}");

    $rsDadosConfiguracao = db_query($sSql);
    if (!$rsDadosConfiguracao) {
      throw new DBException("Erro ao realizar a pesquisa das configurações de consignação da instituição");
    }
    $aConfiguracoes = array();
    $iTotalItens = pg_num_rows($rsDadosConfiguracao);
    for ($iConfig = 0; $iConfig < $iTotalItens; $iConfig++) {

      $oDadosConfiguracao = db_utils::fieldsMemory($rsDadosConfiguracao, $iConfig);
      $aConfiguracoes[]   = self::getByCodigo($oDadosConfiguracao->rh178_sequencial);
    }
    return $aConfiguracoes;
  }

  /**
   * @param \Banco       $oBanco
   * @param \Instituicao $oInstituicao
   * @return \ConfiguracaoConsignado|null
   * @throws \DBException
   * @throws \ParameterException
   */
  public static function getConfiguracaoDoBancoNaInstituicao(Banco $oBanco, Instituicao $oInstituicao) {

    if (empty($oInstituicao)) {
      throw new ParameterException("Instituição não informada");
    }

    if (empty($oBanco)) {
      throw new ParameterException("Banco não informado");
    }
    $oDaoConfiguracaoConsignado = new cl_rhconsignacaobancolayout();
    $sWhereBanco  = "rh178_instit = {$oInstituicao->getCodigo()} and ";
    $sWhereBanco .= "rh178_db_banco = '{$oBanco->getCodigo()}'";
    $sSql = $oDaoConfiguracaoConsignado->sql_query_file(null, "*", "rh178_sequencial", $sWhereBanco);

    $rsDadosConfiguracao = db_query($sSql);
    if (!$rsDadosConfiguracao) {
      throw new DBException("Erro ao realizar a pesquisa das configurações de consignação da instituição");
    }
    $iTotalItens = pg_num_rows($rsDadosConfiguracao);
    if ($iTotalItens == 0) {
      return null;
    }
    $oDadosConfiguracao = db_utils::fieldsMemory($rsDadosConfiguracao, 0);
    return self::getByCodigo($oDadosConfiguracao->rh178_sequencial);
  }
}
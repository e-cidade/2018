<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 24/04/14
 * Time: 11:40
 */

class ParametroRegistroPreco {


  /**
   * Permite incluir novos itens na estimativa
   * @var bool
   */
  protected $lIncluirItensEstimativa = false;

  /**
   * Permite a alteracao da Abertura apos a inclusao da Estimativa
   * @var bool
   */
  protected $lAlterarAbertura   = false;

  /**
   * Percentual maximo do execente de itens
   * @var float
   */
  protected $nPercentualExecedente = 0;

  /**
   * @var int
   */
  protected $iOrdenacaoExtimativa  = 1;

  /**
   * Instancia unica do Registry
   * @var ParametroRegistroPreco
   */
  private static $sInstance = null;

  /**
   * Metodo construtor marcado como privado,
   * para evitar mais de uma instancia
   */
  private function __construct() {

    $oDaoParametros     = new cl_registroprecoparam();
    $iCodigoInstituicao = db_getsession("DB_instit");
    $sSqlParametros     = $oDaoParametros->sql_query_file($iCodigoInstituicao);
    $rsParametros       = $oDaoParametros->sql_record($sSqlParametros);
    if ($oDaoParametros->numrows > 0) {

      $oDadosParametros = db_utils::fieldsMemory($rsParametros, 0);
      $this->lAlterarAbertura        = $oDadosParametros->pc08_alteraabertura == 't';
      $this->lIncluirItensEstimativa = $oDadosParametros->pc08_incluiritemestimativa == 't';
      $this->iOrdenacaoExtimativa    = $oDadosParametros->pc08_ordemitensestimativa;
      $this->nPercentualExecedente   = $oDadosParametros->pc08_percentuquantmax;
    }
  }

  private function __clone() {}


  /**
   * Metodo que define a criação da instancia da classe
   * @return ParametroRegistroPreco
   */
  private static function getInstance() {

    if (self::$sInstance == null) {
      self::$sInstance = new ParametroRegistroPreco();
    }
    return self::$sInstance;
  }

  /**
   * @return int
   */
  public static function getOrdenacaoEstimativa() {
    return ParametroRegistroPreco::getInstance()->iOrdenacaoExtimativa;
  }

  /**
   * @return boolean
   */
  public static function permiteAlterarAbertura() {
    return ParametroRegistroPreco::getInstance()->lAlterarAbertura;
  }

  /**
   * @return boolean
   */
  public static function permiteIncluirItensNaEstimativa() {
    return ParametroRegistroPreco::getInstance()->lIncluirItensEstimativa;
  }

  /**
   * @return float
   */
  public static function getPercentualExecedente() {
    return ParametroRegistroPreco::getInstance()->nPercentualExecedente;
  }


} 
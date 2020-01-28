<?php
namespace ECidade\Tributario\Cadastro\Iptu\Recadastramento\Repository;
use ECidade\Tributario\Cadastro\Iptu\Recadastramento\Lote as LoteModel;

class Lote
{

  /**
   * @var \ECidade\Tributario\Cadastro\Iptu\Recadastramento\Repository\Lote
   */
  protected static $instance;

  /**
   * @var LoteModel[]
   */
  protected $lotes = array();


  private function __construct() {

  }

  private function __clone() {

  }

  /**
   * @return \ECidade\Tributario\Cadastro\Iptu\Recadastramento\Repository\Lote
   */
  public static function getInstance() {

    if (self::$instance == null) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  /**
   * @param $iMatricula
   * @return \ECidade\Tributario\Cadastro\Iptu\Recadastramento\Lote
   * @throws \BusinessException
   * @throws \Exception
   */
  public static function getLotePorMatricula($iMatricula) {

     $oDadoLote = new \cl_lote();
     $sWhere    = " iptubase.j01_matric = {$iMatricula} ";
     $sSqlDadosLote = $oDadoLote->sql_query_dados_lote(null, '*',null,$sWhere);
     $rsDadosLote = db_query($sSqlDadosLote);
     if (!$rsDadosLote || pg_num_rows($rsDadosLote) == 0) {
        throw new \BusinessException("Matrícula {$iMatricula} não cadastrada na importação.");
     }
    $dadosMatricula = \db_utils::fieldsMemory($rsDadosLote, 0);
    $oLote  = new LoteModel;
    $oLote->setIdbql($dadosMatricula->j34_idbql);
    $oLote->setLoteArea($dadosMatricula->j34_area);
    $oLote->setMatricula($iMatricula);
    $oLote->setSetor($dadosMatricula->j34_setor);
    $oLote->setValorTestada($dadosMatricula->j36_testad);

    /**
     * Consultamos as caractetisticas do matricula
     */
    $oDaoCarlote   = new \cl_carlote();
    $sSqlDadosLote = $oDaoCarlote->sql_query_file($dadosMatricula->j34_idbql);
    $rsDadosLote   = db_query($sSqlDadosLote);

    if (!$rsDadosLote || pg_num_rows($rsDadosLote) == 0) {
        throw new \Exception("Erro ao buscar as características do lote da matrícula {$iMatricula}.");
    }
    $aCaracteristicasLote = \db_utils::makeCollectionFromRecord($rsDadosLote, function ($oDados){
      return $oDados->j35_caract;
    });

    $oLote->setCaracteristicasLote($aCaracteristicasLote);

    return $oLote;

  }
}
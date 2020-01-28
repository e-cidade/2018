<?php
namespace ECidade\Tributario\Cadastro\Iptu\Recadastramento\Repository;
use ECidade\Tributario\Cadastro\Iptu\Recadastramento\Construcao as ConstrucaoModel;

class Construcao
{

  /**
   * @var \ECidade\Tributario\Cadastro\Iptu\Recadastramento\Repository\Construcao
   */
  protected static $instance;

  /**
   * @var ConstrucaoModel[]
   */
  protected $construcao = array();


  private function __construct() {

  }

  private function __clone() {

  }

  /**
   * @return \ECidade\Tributario\Cadastro\Iptu\Recadastramento\Repository\Construcao
   */
  public static function getInstance() {

    if (self::$instance == null) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  /**
   * @param $iCodigoMatricula
   * @return \ECidade\Tributario\Cadastro\Iptu\Recadastramento\Construcao|null
   * @throws \DBException
   * @throws \Exception
   */
  public static function getConstrucaoPorMatricula ( $iCodigoMatricula ) {

    $oDadoConstrucao = new \cl_iptuconstr();
    $sSqlDadoConstrucao = $oDadoConstrucao->sql_query($iCodigoMatricula);
    $rsDadoConstrucao = db_query($sSqlDadoConstrucao);

    if (!$rsDadoConstrucao || pg_num_rows($rsDadoConstrucao) == 0) {
      throw new \DBException("Erro ao buscar as construções da matrícula {$iCodigoMatricula}");
    }

    if (pg_num_rows($rsDadoConstrucao) == 0) {
      return null;
    }
    $oDados = \db_utils::fieldsMemory($rsDadoConstrucao, 0);
    $construcao = new ConstrucaoModel();
    $construcao->setMatricula($iCodigoMatricula);
    $construcao->setAreaConstrucao($oDados->j39_area);
    $construcao->setIdConstrucao(1);
    $construcao->setNumero($oDados->j39_numero);
    $construcao->setRua($oDados->j39_codigo);
    $construcao->setIdbql($oDados->j01_idbql);
    if ( !empty($oDados->j39_dtdemo) ) {
      $construcao->setDataDemolicao(new \DBDate($oDados->j39_dtdemo));
    }

    $aCgm [] =  $oDados->j01_numcgm;

    $oDaoPropri = new \cl_propri();
    $sSqlDadoPropri = $oDaoPropri->sql_query_file($iCodigoMatricula);
    $rsDados = db_query($sSqlDadoPropri);

    if(!$rsDados) {
      throw new \DBException("Erro ao verificar outros proprietários do imóvel da matrícula {$iCodigoMatricula}.");
    }

    $aCgmPropri = \db_utils::makeCollectionFromRecord($rsDados, function ($Dados){
      return $Dados->j42_numcgm;
    });

    $aCgm = array_merge($aCgm,$aCgmPropri);
    $construcao->setCgm($aCgm);

    $oDaoCarConstr = new \cl_carconstr();
    $sSqlCaracteristicasConstrucao = $oDaoCarConstr->sql_query_file($iCodigoMatricula, 1);
    $rsCaracteristicas  = db_query($sSqlCaracteristicasConstrucao);

    if (!$rsCaracteristicas || pg_num_rows($rsCaracteristicas) == 0) {
      throw new \Exception("Erro ao buscar as características da construção da matrícula {$iCodigoMatricula}.");
    }

    $aCaracteristicasLote = \db_utils::makeCollectionFromRecord($rsCaracteristicas, function ($oDados){
      return $oDados->j48_caract;
    });

    $construcao->setCaracteristicas($aCaracteristicasLote);

     return $construcao;
  }
}
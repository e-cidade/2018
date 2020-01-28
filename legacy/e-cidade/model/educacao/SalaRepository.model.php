<?php


/**
 * Classe repository para classes Sala
 * @author 
 * @package
 */
class SalaRepository {

  /**
   * Collection de Sala
   * @var array
   */
  private $aItens = array();
  /**
   * Instancia da classe
   * @var SalaRepository
   */
  private static $oInstance;

  private function __construct() {

  }
  private function __clone() {

  }
  
  /**
   * Retorna uma instancia do Sala pelo Codigo
   * @param integer $iCodigo Codigo do Sala
   * @return Sala
   */
  public static function getByCodigo($iCodigo) {

    if (!array_key_exists($iCodigo, SalaRepository::getInstance()->aItens)) {
      SalaRepository::getInstance()->aItens[$iCodigo] = new Sala($iCodigo);
    }
    return SalaRepository::getInstance()->aItens[$iCodigo];
  }
  
  /**
   * Retorna a instancia da classe
   * @return SalaRepository
   */
  protected static function getInstance() {

    if (self::$oInstance == null) {

      self::$oInstance = new SalaRepository();
    }
    return self::$oInstance;
  }
  
  /**
   * Adiciona uma instancia de Sala ao repositorio
   * @param Sala $oSala Instancia de Sala
   * @return boolean
   */
  public static function adicionarSala(Sala $oSala) {

    if (!array_key_exists($oSala->getCodigo(), SalaRepository::getInstance()->aItens)) {
      SalaRepository::getInstance()->aItens[$oSala->getCodigo()] = $oSala;
    }
    return true;
  }

  /**
   * Remove a instancia passada como parametro do repository
   * @param Sala $oSala
   * @return boolean
   */
  public static function remover(Sala $oSala) {
     /**
      *
      */
    if (array_key_exists($oSala->getCodigo(), SalaRepository::getInstance()->aItens)) {
      unset(SalaRepository::getInstance()->aItens[$oSala->getCodigo()]);
    }
    return true;
  }

  /**
   * Retorna o total de itens existentes no repositorio;
   * @return integer;
   */
  public static function getTotalSala() {
    return count(SalaRepository::getInstance()->aItens);
  }


  /**
   * Retorna as dependencias em que o uso é compartilhada com mais de uma turma
   * @param Escola     $oEscola
   * @param Calendario $oCalendario
   * @param            $iTurnoReferente
   * @return Sala[]
   * @throws DBException
   */
  public static function getDependenciasComMaisDeUmaTurmaVinculada (Escola $oEscola, Calendario $oCalendario, $iTurnoReferente) {

    $sDataInicio = $oCalendario->getDataInicio()->getDate();
    $sDataFim    = $oCalendario->getDataFinal()->getDate();

    $aWhere   = array();
    $aWhere[] = " ed57_i_escola      = {$oEscola->getCodigo()} ";
    $aWhere[] = " ed231_i_referencia = {$iTurnoReferente} ";
    $aWhere[] = " ('{$sDataInicio}', '{$sDataFim}') overlaps (ed52_d_inicio, ed52_d_fim) ";
    $aWhere[] = " ed57_i_tipoturma <> 6 ";
    $aWhere[] = " ed52_i_ano = {$oCalendario->getAnoExecucao()} ";

    $sWhere = implode(" and ", $aWhere);

    $sSql  = "select count(*), ed57_i_sala ";
    $sSql .= "  from turma  ";
    $sSql .= " inner join calendario     on calendario.ed52_i_codigo     = turma.ed57_i_calendario ";
    $sSql .= " inner join sala           on sala.ed16_i_codigo           = turma.ed57_i_sala ";
    $sSql .= " inner join turnoreferente on turnoreferente.ed231_i_turno = turma.ed57_i_turno ";
    $sSql .= " where {$sWhere}";
    $sSql .= " group by ed57_i_escola, ed57_i_calendario ,ed231_i_referencia , ed57_i_sala";
    $sSql .= " having count(*) > 1 ; ";
    $rsDependencia = db_query($sSql);

    if (!$rsDependencia) {

      $oErro           = new stdClass();
      $oErro->sMsgErro = pg_last_error();
      throw new DBException( "Erro ao buscar as dependências. \n". pg_last_error() );
    }

    $aSalas  = array();
    $iLinhas = pg_num_rows($rsDependencia);

    for ( $i = 0; $i < $iLinhas; $i++ ) {
      $aSalas[] = SalaRepository::getByCodigo(db_utils::fieldsMemory($rsDependencia, $i)->ed57_i_sala);
    }

    return $aSalas;

  }


}

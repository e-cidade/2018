<?php

/**
 * Repositoy para os tipos de horas de trabalho
 * @package   Educacao
 * @author    Andrio Costa - andrio.costa@dbseller.com.br
 * @version   $Revision: 1.3 $
 */
class TipoHoraTrabalhoRepository {

  private $aTipoHora = array();
  private static $oInstance;

  private function __construct() {

  }
  private function __clone() {

  }

  /**
   * Retorna a instancia do Repositorio
   * @return TipoHoraTrabalhoRepository
   */
  protected static function getInstance() {

    if(self::$oInstance == null) {
      self::$oInstance = new TipoHoraTrabalhoRepository();
    }
    return self::$oInstance;
  }

  /**
   * Verifica se o aluno possui instancia, se não instancia e retorna a instancia de Tipo de Hora de Trabalho
   * @param integer $iCodigo
   * @return TipoHoraTrabalho
   */
  public static function getByCodigo($iCodigo) {

    if (!array_key_exists($iCodigo, self::getInstance()->aTipoHora)) {
      self::getInstance()->aTipoHora[$iCodigo] = new TipoHoraTrabalho($iCodigo);
    }

    return self::getInstance()->aTipoHora[$iCodigo];
  }

  /**
   * Retorna os tipos de hora cadastrados compativeis com a atividade informada
   * @param  AtividadeEscolar $oAtividade
   * @return AtividadeEscolar[]
   */
  public static function getByAtividade(AtividadeEscolar $oAtividade) {

    $iTipoEfetividadeAtividade = 2;

    if ( $oAtividade->getEfetividade() == AtividadeEscolar::EFETIVIDADE_FUNCIONARIO) {
      $iTipoEfetividadeAtividade = 3;
    }

    $sWhere  = "     ed128_tipoefetividade in ( 1, {$iTipoEfetividadeAtividade}) ";
    $sWhere .= " and ed128_ativo is true ";

    $oDaoTipoHora = new cl_tipohoratrabalho();
    $sSqlTipoHora = $oDaoTipoHora->sql_query_file(null, "*", null, $sWhere);
    $rsTipoHora   = db_query($sSqlTipoHora);

    $oMsgErro = new stdClass();
    if ( !$rsTipoHora ) {

      $oMsgErro->sErro = pg_last_error();
      throw new DBException( _M(TipoHoraTrabalho::MENSAGENS_TIPOHORATRABALHO . "erro_buscar_tipo_hora", $oMsgErro) );
    }

    if ( pg_num_rows($rsTipoHora) == 0 ) {
      throw new Exception( _M(TipoHoraTrabalho::MENSAGENS_TIPOHORATRABALHO . "nenhum_tipo_hora_cadastrado_para_atividade") );
    }

    $iLinhas = pg_num_rows($rsTipoHora);

    $aTipoHora = array();
    for ( $i = 0; $i < $iLinhas; $i++ ) {

      $oDados      = db_utils::fieldsMemory( $rsTipoHora, $i );
      $aTipoHora[] = TipoHoraTrabalhoRepository::getByCodigo($oDados->ed128_codigo);
    }

    return $aTipoHora;
  }


  /**
   * Adiciona um Tipo de Hora de Trabalho ao repositorio
   * @param TipoHoraTrabalho $oTipoHoraTrabalho
   * @return boolean
   */
  public static function adicionarTipoHora(TipoHoraTrabalho $oTipoHoraTrabalho) {

    if(!array_key_exists($oTipoHoraTrabalho->getCodigo(), self::getInstance()->aTipoHora)) {
      self::getInstance()->aTipoHora[$oTipoHoraTrabalho->getCodigo()] = $oTipoHoraTrabalho;
    }
    return true;
  }

  /**
   * Remove um Tipo de Hora de Trabalho do repository
   * @param TipoHoraTrabalho $oTipoHoraTrabalho
   * @return boolean
   */
  public static function removerAluno(TipoHoraTrabalho $oTipoHoraTrabalho) {

    if (array_key_exists($oTipoHoraTrabalho->getCodigo(), self::getInstance()->aTipoHora)) {
      unset(self::getInstance()->aTipoHora[$oTipoHoraTrabalho->getCodigo()]);
    }
    return true;
  }

}
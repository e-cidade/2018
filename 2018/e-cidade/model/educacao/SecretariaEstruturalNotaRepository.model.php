<?php

/**
 *
 * @package  educacao
 * @author   Andrio Costa  <andrio.costa@dbseller.com.br>
 * @revision $Revision $
 */
class SecretariaEstruturalNotaRepository {

  private $aEstruturalNota = array();

  private static $oInstance;

  private function __construct() {}
  private function __clone() {}

  /**
   * Retorna uma instancia do repository
   * @return SecretariaEstruturalNotaRepository
   */

  private static function getInstance() {

    if ( self::$oInstance == null ) {
      self::$oInstance = new SecretariaEstruturalNotaRepository();
    }

    return self::$oInstance;
  }

  /**
   * Retorna por codigo
   * @param  integer   $iCodigo
   * @return SecretariaEstruturalNota
   */
  public static function getByCodigo($iCodigo) {

    if ( !array_key_exists($iCodigo, self::getInstance()->aEstruturalNota) ) {
      self::getInstance()->aEstruturalNota[$iCodigo] = new SecretariaEstruturalNota($iCodigo);
    }
    return self::getInstance()->aEstruturalNota[$iCodigo];
  }

  /**
   * Retorna a configuração da nota pelo ano.
   *
   * @param  integer   $iAno
   * @return SecretariaEstruturalNota|null
   */
  public static function getAtivoByAno($iAno) {

    $sWhere = "ed139_ativo is true and ed139_ano = {$iAno}";
    $oDao   = new cl_avaliacaoestruturanotapadrao();
    $sSql   = $oDao->sql_query(null, "ed139_sequencial", null, $sWhere);
    $rs     = db_query($sSql);

    if ( !$rs ) {
      throw new Exception( _M(EstruturalNota::ESTRUTURAL_NOTA ."erro_buscar_configuracao") );
    }

    if ( pg_num_rows($rs) == 0 ) {
      return null;
    }

    return self::getByCodigo(db_utils::fieldsMemory($rs, 0)->ed139_sequencial);
  }

  public static function adicionarEstruturalNota( SecretariaEstruturalNota $oSecretariaEstruturalNota ) {

    self::getInstance()->aEstruturalNota[$oSecretariaEstruturalNota->getCodigo()] = $oSecretariaEstruturalNota;
  }

  public static function removeEstruturalNota( SecretariaEstruturalNota $oSecretariaEstruturalNota ) {

    if (array_key_exists($oSecretariaEstruturalNota->getCodigo(), self::getInstance()->aEstruturalNota)) {
      unset( self::getInstance()->aEstruturalNota[$oSecretariaEstruturalNota->getCodigo()] );
    }
  }


  /**
   * Reseta Repository
   */
  public static function removeAll() {

    unset(self::getInstance()->aEstruturalNota);
    self::getInstance()->aEstruturalNota = array();
  }
}
<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

/**
 *
 * @package  educacao
 * @author   Andrio Costa  <andrio.costa@dbseller.com>
 * @revision $Revision $
 */
class EscolaEstruturalNotaRepository {

  private $aEscolaEstruturalNota = array();

  private static $oInstance;

  private function __construct() {}
  private function __clone() {}

  /**
   * Retorna uma instancia do repository
   * @return EscolaEstruturalNotaRepository
   */

  private static function getInstance() {

    if ( self::$oInstance == null ) {
      self::$oInstance = new EscolaEstruturalNotaRepository();
    }

    return self::$oInstance;
  }

  /**
   * Retorna por codigo
   * @param  integer   $iCodigo
   * @return EscolaEstruturalNota
   */
  public static function getByCodigo($iCodigo) {

    if ( !array_key_exists($iCodigo, self::getInstance()->aEscolaEstruturalNota) ) {
      self::getInstance()->aEscolaEstruturalNota[$iCodigo] = new EscolaEstruturalNota($iCodigo);
    }
    return self::getInstance()->aEscolaEstruturalNota[$iCodigo];
  }

  /**
   * Retorna a configuração da nota pelo ano.
   *
   * @param  integer   $iAno
   * @return EscolaEstruturalNota|null
   */
  public static function getAtivoByAno(Escola $oEscola, $iAno) {

    $sWhere  = "ed315_ativo is true and ed315_ano = {$iAno}";
    $sWhere .= " and ed315_escola = {$oEscola->getCodigo()} ";
    $oDao    = new cl_avaliacaoestruturanota();
    $sSql    = $oDao->sql_query(null, "ed315_sequencial", null, $sWhere);
    $rs      = db_query($sSql);

    if ( !$rs ) {
      throw new Exception( _M(EstruturalNota::ESTRUTURAL_NOTA ."erro_buscar_configuracao") );
    }

    if ( pg_num_rows($rs) == 0 ) {
      return null;
    }

    return self::getByCodigo(db_utils::fieldsMemory($rs, 0)->ed315_sequencial);
  }

  public static function clonaEstruturalNaEscola(Escola $oEscola, SecretariaEstruturalNota $oEstrutural) {

    $oDados = new stdClass();
    $oDados->iCodigo              = null;
    $oDados->iEstrutural          = $oEstrutural->getEstrutural()->getCodigo();
    $oDados->lAtivo               = $oEstrutural->isAtivo();
    $oDados->lArredondaMedia      = $oEstrutural->deveArredondarMedia();
    $oDados->sObservacao          = $oEstrutural->getObservacao();
    $oDados->iAno                 = $oEstrutural->getAno();
    $oDados->iRegraArredondamento = null;
    $oDados->iEscola              = $oEscola->getCodigo();

    $oRegra = $oEstrutural->getRegraArredondamento();
    if ( !is_null($oRegra) ) {

      $oDados->iRegraArredondamento = $oRegra->getCodigo();
      $oDados->sRegraArredondamento = $oRegra->getDescricao();
    }

    $oEscolaEstrutural = new EscolaEstruturalNota();
    $oEscolaEstrutural->salvar($oDados);

    self::getInstance()->aEscolaEstruturalNota[$oEscolaEstrutural->getCodigo()] = $oEscolaEstrutural;
    return $oEscolaEstrutural;
  }


  public static function adicionarEscolaEstruturalNota( EscolaEstruturalNota $oEscolaEstruturalNota ) {

    self::getInstance()->aEscolaEstruturalNota[$oEscolaEstruturalNota->getCodigo()] = $oEscolaEstruturalNota;
  }

  public static function removeEscolaEstruturalNota( EscolaEstruturalNota $oEscolaEstruturalNota ) {

    if (array_key_exists($oEscolaEstruturalNota->getCodigo(), self::getInstance()->aEscolaEstruturalNota)) {
      unset( self::getInstance()->aEscolaEstruturalNota[$oEscolaEstruturalNota->getCodigo()] );
    }
  }


  /**
   * Reseta Repository
   */
  public static function removeAll() {

    unset(self::getInstance()->aEscolaEstruturalNota);
    self::getInstance()->aEscolaEstruturalNota = array();
  }
}
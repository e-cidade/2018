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
 * Repositoy para as AvaliacaoAlternativas
 * @package   Educacao
 * @author    Andrio Costa - andrio.costa@dbseller.com.br
 * @version   $Revision: 1.5 $
 */
class AvaliacaoAlternativaRepository {

  const URL_MSG_AVALIACAOALTERNATIVA = "educacao.escola.AvaliacaoAlternativa.";

  /**
   * Array com instancias de AvaliacaoAlternativa
   * @var array
   */
  private $aAvaliacaoAlternativa = array();

  /**
   * Array de controle para os diários
   * @var array
   */
  private $aDiarioAvaliacaoAlternativa = array();

  private static $oInstance;

  private function __construct() {

  }

  private function __clone(){

  }

  /**
   * Retorna a instancia do Repository
   * @return AvaliacaoAlternativaRepository
   */
  protected function getInstance() {

    if (self::$oInstance == null) {
      self::$oInstance = new AvaliacaoAlternativaRepository();
    }
    return self::$oInstance;
  }

  /**
   * Verifica se a AvaliacaoAlternativa possui instancia, se não instancia e retorna a instancia de AvaliacaoAlternativa
   * @param integer $iCodigo código de uma Avaliacao Alternativa
   * @return AvaliacaoAlternativa
   */
  public static function getByCodigo($iCodigo) {

    if (!array_key_exists($iCodigo, AvaliacaoAlternativaRepository::getInstance()->aAvaliacaoAlternativa)) {
      AvaliacaoAlternativaRepository::getInstance()->aAvaliacaoAlternativa[$iCodigo] = new AvaliacaoAlternativa($iCodigo);
    }
    return AvaliacaoAlternativaRepository::getInstance()->aAvaliacaoAlternativa[$iCodigo];
  }

  public static function getByResultado( ResultadoAvaliacao $oResultadoAvaliacao ) {

    $sWhere = " ed281_i_procresultado = {$oResultadoAvaliacao->getCodigo()} ";

    $oDaoAlternativa = new cl_procavalalternativa();
    $sSqlAlternativa = $oDaoAlternativa->sql_query_file(null, "ed281_i_codigo", "ed281_i_alternativa", $sWhere);
    $rsAlternativa   = db_query($sSqlAlternativa);

    $oMsgErro = new stdClass();
    if ( !$rsAlternativa ) {

      $oMsgErro->sErro = pg_last_error();
      throw new DBException( _M(URL_MSG_AVALIACAOALTERNATIVA . "erro_executar_query", $oMsgErro) );
    }

    if ( pg_num_rows($rsAlternativa) == 0 ) {
      return array();
    }

    $aAvaliacaoAlternativa = array();

    $iAlternativas = pg_num_rows($rsAlternativa);
    for ( $i = 0; $i < $iAlternativas; $i++ ) {

      $oDados                  = db_utils::fieldsMemory($rsAlternativa, $i);
      $aAvaliacaoAlternativa[] = AvaliacaoAlternativaRepository::getByCodigo($oDados->ed281_i_codigo);
    }

    return $aAvaliacaoAlternativa;
  }

  /**
   * Adiciona uma AvaliacaoAlternativa ao Repository
   * @param AvaliacaoAlternativa $oAvaliacaoAlternativa
   */
  public static function adicionarAvaliacaoAlternativa(AvaliacaoAlternativa $oAvaliacaoAlternativa) {

    AvaliacaoAlternativaRepository::getInstance()->aAvaliacaoAlternativa[$oAvaliacaoAlternativa->getCodigo()] = $oAvaliacaoAlternativa;
    return true;
  }

  /**
   * Remove uma AvaliacaoAlternativa do repositorio
   * @param AvaliacaoAlternativa $oAvaliacaoAlternativa
   * @return boolean
   */
  public static function removerAvaliacaoAlternativa(AvaliacaoAlternativa $oAvaliacaoAlternativa) {

    if (array_key_exists($oAvaliacaoAlternativa->getCodigo(), AvaliacaoAlternativaRepository::getInstance()->aAvaliacaoAlternativa)) {
      unset(AvaliacaoAlternativaRepository::getInstance()->aAvaliacaoAlternativa[$oAvaliacaoAlternativa->getCodigo()]);
    }
    return true;
  }

  /**
   * Recupera a instância da avaliação alternativa de acordo com o diário
   * @param integer $iCodigoDiario
   * @return AvaliacaoAlternativa|null
   * @throws DBException
   */
  public static function getByDiario( $iCodigoDiario ) {

    if ( !array_key_exists($iCodigoDiario, AvaliacaoAlternativaRepository::getInstance()->aDiarioAvaliacaoAlternativa)) {

      $sWhere = " ed136_diario = {$iCodigoDiario} ";

      $oDaoAvaliacaoAlternativa    = new cl_diarioavaliacaoalternativa();
      $sSqlAvaliacaoAlternativa    = $oDaoAvaliacaoAlternativa->sql_query_file(null, "ed136_procavalalternativa", null, $sWhere);
      $rsAvaliacaoAlternativa      = db_query($sSqlAvaliacaoAlternativa);
      $iCodigoAvaliacaoAlternativa = null;

      if ( !$rsAvaliacaoAlternativa ) {

        $sMsgErro = "Erro ao excutar a query que verifica se aluno possui avaliação alternativa.\n". pg_last_error();
        throw new DBException($sMsgErro);
      }

      if ( pg_num_rows($rsAvaliacaoAlternativa) > 0) {
        $iCodigoAvaliacaoAlternativa = db_utils::fieldsMemory($rsAvaliacaoAlternativa, 0)->ed136_procavalalternativa;
      }

      AvaliacaoAlternativaRepository::getInstance()->aDiarioAvaliacaoAlternativa[$iCodigoDiario] = $iCodigoAvaliacaoAlternativa;
    }

    return empty(AvaliacaoAlternativaRepository::getInstance()->aDiarioAvaliacaoAlternativa[$iCodigoDiario]) ? null
            : AvaliacaoAlternativaRepository::getByCodigo(AvaliacaoAlternativaRepository::getInstance()->aDiarioAvaliacaoAlternativa[$iCodigoDiario]);
  }
}
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

define( "MENSAGENS_RECURSOHUMANOREPOSITORY", 'educacao.escola.RecursoHumanoAusenteRepository.' );
/**
 * Representa uma coleção de RecursoHumanoAusente
 * @package    Educacao
 * @subpackage recursohumano
 * @author     Fábio Esteves <fabio.esteves@dbseller.com.br>
 *             André Mello   <andre.mello@dbseller.com.br>
 * @version    $Revision: 1.1 $
 */
class RecursoHumanoAusenteRepository {

  /**
   * Instancia da classe
   * @var RecursoHumanoAusenteRepository
   */
  private static $oInstance;

  private $aRecursoHumanoAusente = array();

  private function __construct() {}
  private function __clone() {}

  /**
   * Retorno uma instancia de recursohumanoausente
   * @param integer $iCodigo
   * @return RecursoHumanoAusente
   */
  public static function getByCodigo($iCodigo) {

    if (!array_key_exists($iCodigo, RecursoHumanoAusenteRepository::getInstance()->aRecursoHumanoAusente)) {
      RecursoHumanoAusenteRepository::getInstance()->aRecursoHumanoAusente[$iCodigo] = new RecursoHumanoAusente($iCodigo);
    }
    return RecursoHumanoAusenteRepository::getInstance()->aRecursoHumanoAusente[$iCodigo];
  }

  /**
   * Retorna a instancia da classe
   * @return RecursoHumanoAusenteRepository
   */
  protected static function getInstance() {

    if (self::$oInstance == null) {

      self::$oInstance = new RecursoHumanoAusenteRepository();
    }
    return self::$oInstance;
  }

  /**
   * Retorna uma coleção de instâncias de RecursoHumanoAusente
   * @param integer $iRecHumano
   * @param integer $iEscola
   * @return RecursoHumanoAusente[]
   * @throws DBException
   */
  public static function getAusenciasByRecursoHumanoEscola( $iRecHumano, $iEscola = null ) {

    $oDaoRecHumanoAusente   = new cl_rechumanoausente();
    $sWhereRecHumanoAusente = "ed348_rechumano = {$iRecHumano}";

    if( !empty( $iEscola ) ) {
      $sWhereRecHumanoAusente .= " AND ed348_escola = {$iEscola}";
    }

    $sSqlRecHumanoAusente = $oDaoRecHumanoAusente->sql_query_file( null, 'ed348_sequencial', null, $sWhereRecHumanoAusente );
    $rsRecHumanoAusente   = db_query( $sSqlRecHumanoAusente );

    if( !$rsRecHumanoAusente ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();
      throw new DBException( _M( MENSAGENS_RECURSOHUMANOREPOSITORY . 'erro_buscar_ausencias', $oErro ) );
    }

    $aRecursoHumanoAusente = array();
    $iQuantidadeLinhas     = pg_num_rows($rsRecHumanoAusente);

    if ( $iQuantidadeLinhas > 0 ) {

      for ( $iContador = 0; $iContador < $iQuantidadeLinhas; $iContador++ ) {

        $iAusencia               = db_utils::fieldsMemory($rsRecHumanoAusente, $iContador)->ed348_sequencial;
        $oRecursoHumanoAusente   = RecursoHumanoAusenteRepository::getByCodigo( $iAusencia );
        $aRecursoHumanoAusente[] = $oRecursoHumanoAusente;
      }
    }

    return $aRecursoHumanoAusente;
  }
}
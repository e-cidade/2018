<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
 * Repository para evitar sobre carga
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @package educacao
 * @version $Revision: 1.1 $
 */
class FormaCalculoCargaHorariaRepository {

  private static $oInstance;

  private $aFormaCalculo = array();

  private $aFormaCalculoAnoEscola = array();

  private function __construct() {

  }
  private function __clone() {

  }

  /**
   * Retorna a instancia do Repositorio
   * @return FormaCalculoCargaHoraria
   */
  protected function getInstance() {

    if(self::$oInstance == null) {
      self::$oInstance = new FormaCalculoCargaHorariaRepository();
    }
    return self::$oInstance;
  }

  /**
   * Verifica se a FormaCalculoCargaHoraria possui instancia, se não instancia e retorna a instancia de FormaCalculoCargaHoraria
   * @param  integer $iCodigo
   * @return FormaCalculoCargaHoraria
   */
  public static function getByCodigo ( $iCodigo ) {

    if (!array_key_exists($iCodigo, FormaCalculoCargaHorariaRepository::getInstance()->aFormaCalculo)) {
      FormaCalculoCargaHorariaRepository::getInstance()->aFormaCalculo[$iCodigo] = new FormaCalculoCargaHoraria($iCodigo);
    }

    return FormaCalculoCargaHorariaRepository::getInstance()->aFormaCalculo[$iCodigo];
  }

  /**
   * Retorna a regra de calculo para o calendário
   * @param  Calendario $oCalendario
   * @return FormaCalculoCargaHoraria
   */
  public static function getByCalendario( Calendario $oCalendario ) {

    $oEscola = $oCalendario->getEscola();
    if ( is_null($oEscola) ) {
      throw new Exception( _M(FormaCalculoCargaHoraria::MSG_FORMACALCULOCARGAHORARIA . "calendario_sem_vinculo_escola") );
    }
    $sHash = $oCalendario->getAnoExecucao() . "#" . $oEscola->getCodigo();

    if ( !array_key_exists($sHash, FormaCalculoCargaHorariaRepository::getInstance()->aFormaCalculoAnoEscola) ) {

      $sWhere    = "     ed127_ano    = {$oCalendario->getAnoExecucao()} ";
      $sWhere   .= " and ed127_escola = " . $oEscola->getCodigo();

      $oDaoRegra = new cl_regracalculocargahoraria();
      $sSqlRegra = $oDaoRegra->sql_query_file(null, " ed127_codigo ", null, $sWhere );
      $rsRegra   = db_query($sSqlRegra);
      if ( !$rsRegra || pg_num_rows($rsRegra) == 0 ) {

        $oMsgErro          = new stdClass();
        $oMsgErro->iAno    = $oCalendario->getAnoExecucao();
        $oMsgErro->sEscola = $oEscola->getNome();
        throw new Exception( _M(FormaCalculoCargaHoraria::MSG_FORMACALCULOCARGAHORARIA . "sem_configuracao_ano_escola", $oMsgErro) );
      }
      $iCodigoRegra = db_utils::fieldsMemory($rsRegra, 0)->ed127_codigo;
      FormaCalculoCargaHorariaRepository::getInstance()->aFormaCalculoAnoEscola[$sHash] = new FormaCalculoCargaHoraria($iCodigoRegra);
    }

    return FormaCalculoCargaHorariaRepository::getInstance()->aFormaCalculoAnoEscola[$sHash];
  }

}

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

class PeriodoAquisitivoFeriasRepository {

  /**
   * @var PeriodoAquisitivoFerias[]
   */
  protected  $itens = array();

  /**
   * @var \PeriodoAquisitivoFeriasRepository
   */
  private static $instance;


  private function __construct() {

  }

  /**
   *
   * @return \PeriodoAquisitivoFeriasRepository
   */
  public static function getInstance() {

    if (empty(self::$instance)) {
      self::$instance = new PeriodoAquisitivoFeriasRepository();
    }
    return self::$instance;
  }

  /**
   * @param $oDados
   * @return \PeriodoAquisitivoFerias
   */
  public static function make($oDados) {

    if (isset(self::getInstance()->itens[$oDados->rh109_sequencial])) {
      return self::getInstance()->itens[$oDados->rh109_sequencial];
    }

    $oPeriodoAquisitivoFerias = new PeriodoAquisitivoFerias();
    $oPeriodoAquisitivoFerias->setCodigo($oDados->rh109_sequencial);
    $oPeriodoAquisitivoFerias->setServidor(ServidorRepository::getInstanciaByCodigo($oDados->rh109_regist));
    $oPeriodoAquisitivoFerias->setDataInicial(new DBDate($oDados->rh109_periodoaquisitivoinicial));
    $oPeriodoAquisitivoFerias->setDataFinal(new DBDate($oDados->rh109_periodoaquisitivofinal));
    $oPeriodoAquisitivoFerias->setDiasDireito($oDados->rh109_diasdireito);
    $oPeriodoAquisitivoFerias->setFaltasPeriodoAquisitivo($oDados->rh109_faltasperiodoaquisitivo);
    $oPeriodoAquisitivoFerias->setObservacao($oDados->rh109_observacao);
    return $oPeriodoAquisitivoFerias;
  }

  /**
   * Retorna os Periodos Aquisitivos que o servidor possui saldo para férias
   *
   * @param Servidor $oServidor
   * @throws BusinessException
   * @return  \PeriodoAquisitivoFerias[]
   */
  public static function getPeriodosDisponiveisDoServidor( Servidor $oServidor ) {

    $oDaoRhFerias = new cl_rhferias();
    $sSqlRhferias = $oDaoRhFerias->sql_query_periodos_aquisitivos_com_saldo($oServidor->getMatricula(), '*');
    $rsRhFerias   = db_query($sSqlRhferias);

    /**
     * Erro na query de pesquisa
     */
    if ( !$rsRhFerias ) {
      throw new BusinessException(_M(
        PeriodoAquisitivoFerias::MENSAGENS . 'erro_buscar_periodo_aquisitivo',
        (object) array('sErroBanco' => pg_last_error())
      ));
    }

    /**
     * Nenhum registro encontrado pela matricula informada
     */
    $iTotalLinhas = pg_num_rows($rsRhFerias);
    if ($iTotalLinhas == 0 ) {

      throw new BusinessException(_M(
        PeriodoAquisitivoFerias::MENSAGENS . 'busca_periodo_aquisitivo_pela_matricula',
        (object) array('iCodigo' => $oServidor->getMatricula())
      ));
    }


    $aPeriodos   = array();
    for ($iLinha = 0; $iLinha < $iTotalLinhas; $iLinha++ ) {

      $oDadosPeriodoAquisitivo = db_utils::fieldsMemory($rsRhFerias, $iLinha);
      $oPeriodo                = self::make($oDadosPeriodoAquisitivo);
      self::getInstance()->itens[$oDadosPeriodoAquisitivo->rh109_sequencial] = $oPeriodo;
      $aPeriodos[] = $oPeriodo;
    }

    return $aPeriodos;
  }

  /**
   * @param $iCodigo
   * @return \PeriodoAquisitivoFerias
   */
  public static function getPeriodosPorCodigo($iCodigo) {

    if (!isset(self::getInstance()->itens[$iCodigo])) {
      self::getInstance()->itens[$iCodigo] = new PeriodoAquisitivoFerias($iCodigo);
    }
    return self::getInstance()->itens[$iCodigo];
  }
}
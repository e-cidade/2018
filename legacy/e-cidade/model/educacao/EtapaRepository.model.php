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


define("URL_MENSAGEM_ETAPAREPOSITORY", "educacao.escola.EtapaRepository.");

/**
 * Classe repository para classes Etapa
 * @author Iuri Guntchnigg <iuri@dbseller.com.br>
 * @package
 */
class EtapaRepository {

  /**
   * Collection de Etapa
   * @var array
   */
  private $aEtapa = array();

  /**
   * Instancia da classe
   * @var EtapaRepository
   */
  private static $oInstance;

  private function __construct() {

  }
  private function __clone() {

  }

  /**
   * Retorno uma instancia do Etapa pelo Codigo
   *
   * @param $iCodigoEtapa
   * @return Etapa
   */
  public static function getEtapaByCodigo($iCodigoEtapa) {

    if (!array_key_exists($iCodigoEtapa, EtapaRepository::getInstance()->aEtapa)) {
      EtapaRepository::getInstance()->aEtapa[$iCodigoEtapa] = new Etapa($iCodigoEtapa);
    }
    return EtapaRepository::getInstance()->aEtapa[$iCodigoEtapa];
  }

  /**
   * Retorna a instancia da classe
   * @return EtapaRepository
   */
  protected static function getInstance() {

    if (self::$oInstance == null) {

      self::$oInstance = new EtapaRepository();
    }
    return self::$oInstance;
  }

  /**
   * Adiciona um Etapa dao repositorio
   * @param Etapa $oEtapa Instancia do Etapa
   * @return boolean
   */
  public static function adicionarEtapa(Etapa $oEtapa) {

    if(!array_key_exists($oEtapa->getCodigo(), EtapaRepository::getInstance()->aEtapa)) {
      EtapaRepository::getInstance()->aEtapa[$oEtapa->getCodigo()] = $oEtapa;
    }
    return true;
  }

  /**
   * Remove o Etapa passado como parametro do repository
   * @param Etapa $oEtapa
   * @return boolean
   */
  public static function removerEtapa(Etapa $oEtapa) {
     /**
      *
      */
    if (array_key_exists($oEtapa->getCodigo(), EtapaRepository::getInstance()->aEtapa)) {
      unset(EtapaRepository::getInstance()->aEtapa[$oEtapa->getCodigo()]);
    }
    return true;
  }

  /**
   * Retorna o total de cidadoes existentes no repositorio;
   * @return integer;
   */
  public static function getTotalEtapa() {
    return count(EtapaRepository::getInstance()->aEtapa);
  }

  /**
   * Retorna a Proxima Etapa que devera ser cursada
   * A etapa que deve ser cursada é verificada conforme a turma caso a turma seja de eja, devemos retornar como
   * proxima etapa a maior etapa da totalidade.
   * @param Turma $oTurma Turma
   * @param Etapa $oEtapa Etapa de origem
   * @return Etapa proxima etapa a ser cursada
   */
  static public function getProximaEtapa(Turma $oTurma, Etapa $oEtapa) {


    $sCampos   = 'max(ed11_i_sequencia) as ordem_turma, ed11_i_codigo as codigo_serie_turma, ';
    $sCampos  .= 'ed11_i_ensino as ensino_turma';
    $sWhere    = "ed220_i_turma = {$oTurma->getCodigo()}";
    $sGroupBy  = 'GROUP BY ed11_i_codigo,ed11_i_ensino,ed223_i_ordenacao';
    $sOrderBy  = 'ed223_i_ordenacao DESC LIMIT 1';
    /**
     * Quando a turma em que estamos verificando a etapa é uma turma normal, ou
     * uma turma multietapa, devemos pesquisar a proxima etapa atravez da etapa do parametro.
     * Turmas de EJA, sempre procuramos a proxima etapa da totalidade.
     */
    if (in_array($oTurma->getTipoDaTurma(), array(1, 3))) {

      $sOrderBy = '';
      $sWhere .= " and ed223_i_serie = {$oEtapa->getCodigo()}";
    }

    $oDaoTurmaSerieRegimeMat = db_utils::getdao("turmaserieregimemat");
    $sSqlDadosEtapa          = $oDaoTurmaSerieRegimeMat->sql_query(null, $sCampos, $sOrderBy, $sWhere.$sGroupBy);
    $rsDadosEtapa            = $oDaoTurmaSerieRegimeMat->sql_record($sSqlDadosEtapa);
    if ($oDaoTurmaSerieRegimeMat->numrows > 0) {

      /**
       * Verificamos qual a proxima etapa apos estpa etapa
       */
      $oDaoSerieRegimeMat    = db_utils::getdao("serieregimemat");
      $iCodigoRegime         = $oTurma->getBaseCurricular()->getRegimeMatricula()->getCodigo();
      $oDadosSerie           = db_utils::fieldsMemory($rsDadosEtapa, 0);

      $sCamposEtapaRegimeMat  = " ed11_i_codigo";
      $sWhereEtapaRegMat      = " ed223_i_regimemat = {$iCodigoRegime} ";
      $sWhereEtapaRegMat     .= " AND ed11_i_sequencia > {$oDadosSerie->ordem_turma} ";
      $sWhereEtapaRegMat     .= " AND ed11_i_ensino = {$oDadosSerie->ensino_turma}";
      $sOrder                 = " ed223_i_ordenacao limit 1";
      $sSqlEtapaRegimeMat     = $oDaoSerieRegimeMat->sql_query("",
                                                               $sCamposEtapaRegimeMat,
                                                               $sOrder,
                                                               $sWhereEtapaRegMat
                                                              );
      $rsProximaEtapa         = $oDaoSerieRegimeMat->sql_record($sSqlEtapaRegimeMat);
      if ($oDaoSerieRegimeMat->numrows > 0) {
        return EtapaRepository::getEtapaByCodigo(db_utils::fieldsMemory($rsProximaEtapa, 0)->ed11_i_codigo);
      } else {

        /**
         * Caso nao exista etapa no mesmo curso, procuramos a primeira etapa no proximoCurso
         */
        $oBase = $oTurma->getBaseCurricular();
        if ($oBase->getBaseDeContinuacao() != null) {

          $oBaseContinuacao = $oBase->getBaseDeContinuacao();
          if ($oBase->getCurso()->getEnsino()->getCodigo() !=
             $oBaseContinuacao->getCurso()->getEnsino()->getCodigo()) {

            $iEnsinoSeguinte    = $oBaseContinuacao->getCurso()->getEnsino()->getCodigo();
            $sCamposEtapaRegMat = "ed11_i_codigo";
            $sWhereSerieRegMat  = " ed223_i_regimemat = {$oBaseContinuacao->getRegimeMatricula()->getCodigo()}";
            $sWhereSerieRegMat .= " AND ed11_i_ensino = {$iEnsinoSeguinte}";
            $sSqlSerieRegMat    = $oDaoSerieRegimeMat->sql_query("",
                                                                $sCamposEtapaRegMat,
                                                                "ed223_i_ordenacao limit 1",
                                                               $sWhereSerieRegMat
                                                               );
            $rsResultProximaEtapa = $oDaoSerieRegimeMat->sql_record($sSqlSerieRegMat);
            if ($oDaoSerieRegimeMat->numrows > 0) {
              return EtapaRepository::getEtapaByCodigo(db_utils::fieldsMemory($rsResultProximaEtapa, 0)->ed11_i_codigo);
            }
          }
        }
      }
    }
    return null;
  }

  /**
   * Busca uma etapa pelo código da SerieRegimeMat
   *
   * @param integer $iTurmaSerieRegimeMat código do vinculo da etapa com turmaserieregimemat
   * @return Etapa
   */
  public static function getEtapaByCodigoTurmaSerieRegimeMat($iTurmaSerieRegimeMat) {

    $oDaoTurmaSerieRegimeMat = new cl_turmaserieregimemat();
    $sSqlTurmaSerieRegimeMat = $oDaoTurmaSerieRegimeMat->sql_query($iTurmaSerieRegimeMat, "ed11_i_codigo");
    $rsTurmaSerieRegimeMat   = $oDaoTurmaSerieRegimeMat->sql_record($sSqlTurmaSerieRegimeMat);

    if ($oDaoTurmaSerieRegimeMat->numrows == 1) {

      $iCodigoEtapa = db_utils::fieldsMemory($rsTurmaSerieRegimeMat, 0)->ed11_i_codigo;
      return EtapaRepository::getInstance()->getEtapaByCodigo($iCodigoEtapa);
    }
    return null;
  }

  /**
   * Retorna todas as etapas de uma modalidade de ensino
   *
   * @param  Ensino $oEnsino
   * @throws DBException
   * @return Etapa[] array com instancias das etapas do ensino
   */
  public static function getEtapasEnsino(Ensino $oEnsino) {


    $sWhere     = " ed11_i_ensino = {$oEnsino->getCodigo()} ";
    $oDaoEtapa  = new cl_serie();
    $sSqlEtapas = $oDaoEtapa->sql_query(null, "ed11_i_codigo", "ed11_i_sequencia", $sWhere);
    $rsEtapas   = db_query($sSqlEtapas);

    if ( !$rsEtapas ) {
      throw new DBException(_M(URL_MENSAGEM_ETAPAREPOSITORY."erro_query_etapas_ensino"));
    }

    $iLinhas = pg_num_rows($rsEtapas);

    $aEtapasEnsino = array();
    for ( $i = 0; $i < $iLinhas; $i++ ) {
      $aEtapasEnsino[] = EtapaRepository::getEtapaByCodigo(db_utils::fieldsMemory($rsEtapas, $i)->ed11_i_codigo);
    }

    return $aEtapasEnsino;
  }

  /**
   * Retorna as etapas posteriores a etapa informada para o ensino da etapa
   * @param  Etapa $oEtapa
   * @return Etapa[] array com as etapas posteriores
   */
  public static function getEtapasPosteriores(Etapa $oEtapa) {

    $aEtapasEnsino      = EtapaRepository::getEtapasEnsino($oEtapa->getEnsino());
    $aEtapasPosteriores = array();
    foreach ($aEtapasEnsino as $oEtapaEnsino) {

      if ( $oEtapaEnsino->getOrdem() > $oEtapa->getOrdem() ) {
        $aEtapasPosteriores[] = $oEtapaEnsino;
      }
    }

    return $aEtapasPosteriores;
  }


  /**
   * Retorna as etapas anteriores a etapa informada para o ensino da etapa
   *
   * @param  Etapa $oEtapa
   * @return Etapa[] $aEtapasAnteriores[] array com as etapas posteriores
   */
  public static function getEtapasAnteriores(Etapa $oEtapa) {

    $aEtapasEnsino      = EtapaRepository::getEtapasEnsino($oEtapa->getEnsino());
    $aEtapasAnteriores = array();
    foreach ($aEtapasEnsino as $oEtapaEnsino) {

      if ( $oEtapaEnsino->getOrdem() < $oEtapa->getOrdem() ) {
        $aEtapasAnteriores[] = $oEtapaEnsino;
      }
    }

    return $aEtapasAnteriores;
  }

  public static function removeAll() {

    unset(EtapaRepository::getInstance()->aEtapa);
    EtapaRepository::getInstance()->aEtapa = array();
    return true;
  }

}
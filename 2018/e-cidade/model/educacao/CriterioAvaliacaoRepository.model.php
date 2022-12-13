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
 * Classe repository para classes CriterioAvaliacao
 * @author  Fábio Esteves <fabio.esteves@dbseller.com.br>
 * @package educacao
 */
class CriterioAvaliacaoRepository {

  /**
   * Coleção de CriterioAvaliacao
   * @var array
   */
  private $aCriterioAvaliacao = array();

  /**
   * Instancia da classe
   * @var CriterioAvaliacaoRepository
   */
  private static $oInstance;

  private function __construct() {}
  private function __clone() {}

  /**
   * Retorna a instancia da classe
   * @return CriterioAvaliacaoRepository
   */
  protected static function getInstance() {

    if ( self::$oInstance == null ) {
      self::$oInstance = new CriterioAvaliacaoRepository();
    }
    return self::$oInstance;
  }

  /**
   * Retorno uma instancia do CriterioAvaliacao pelo Codigo
   * @param  integer $iCodigo - Codigo do CriterioAvaliacao
   * @return CriterioAvaliacao
   */
  public static function getCriterioAvaliacaoByCodigo( $iCodigo ) {

    if ( !array_key_exists( $iCodigo, CriterioAvaliacaoRepository::getInstance()->aCriterioAvaliacao ) ) {
      CriterioAvaliacaoRepository::getInstance()->aCriterioAvaliacao[$iCodigo] = new CriterioAvaliacao( $iCodigo );
    }
    return CriterioAvaliacaoRepository::getInstance()->aCriterioAvaliacao[$iCodigo];
  }

  /**
   * Adiciona um CriterioAvaliacao ao repositorio
   * @param  CriterioAvaliacao $oCriterioAvaliacao - Instancia do CriterioAvaliacao
   * @return boolean
   */
  public static function adicionarCriterioAvaliacao( CriterioAvaliacao $oCriterioAvaliacao ) {

    if( !array_key_exists( $oCriterioAvaliacao->getCodigo(), CriterioAvaliacaoRepository::getInstance()->aCriterioAvaliacao ) ) {
      CriterioAvaliacaoRepository::getInstance()->aCriterioAvaliacao[$oCriterioAvaliacao->getCodigo()] = $oCriterioAvaliacao;
    }
    return true;
  }

  /**
   * Remove o CriterioAvaliacao passado como parametro do repository
   * @param  CriterioAvaliacao $oCriterioAvaliacao
   * @return boolean
   */
  public static function removerCriterioAvaliacao( CriterioAvaliacao $oCriterioAvaliacao ) {

    if ( array_key_exists( $oCriterioAvaliacao->getCodigo(), CriterioAvaliacaoRepository::getInstance()->aCriterioAvaliacao ) ) {
      unset(CriterioAvaliacaoRepository::getInstance()->aCriterioAvaliacao[$oCriterioAvaliacao->getCodigo()]);
    }
    return true;
  }

  /**
   * Retorna um array de instancias de CriterioAvaliacao de acordo com os parâmetros passados
   * @param Disciplina $oDisciplina
   * @param Turma $oTurma
   * @param PeriodoAvaliacao $oPeriodoAvaliacao
   * @return CriterioAvaliacao[]
   */
  public static function getCriteriosPorVinculos( Disciplina $oDisciplina, Turma $oTurma, PeriodoAvaliacao $oPeriodoAvaliacao ) {

    $aCriterios              = array();
    $oDaoCriterioAvaliacao   = new cl_criterioavaliacao();
    $sWhereCriterioAvaliacao = "      ed339_disciplina = {$oDisciplina->getCodigoDisciplina()}";
    $sWhereCriterioAvaliacao .= " AND ed341_turma = {$oTurma->getCodigo()} ";
    $sWhereCriterioAvaliacao .= " AND ed340_periodoavaliacao = {$oPeriodoAvaliacao->getCodigo()} ";

    $sSqlCriterioAvaliacao   = $oDaoCriterioAvaliacao->sql_query_vinculos(
                                                                           null,
                                                                           "distinct ed338_sequencial, ed338_ordem",
                                                                           "ed338_ordem, ed338_sequencial",
                                                                           $sWhereCriterioAvaliacao
                                                                         );


    $rsCriterioAvaliacao     = db_query( $sSqlCriterioAvaliacao );
    $iTotalCriterioAvaliacao = pg_num_rows( $rsCriterioAvaliacao );

    for ( $iContador = 0; $iContador < $iTotalCriterioAvaliacao; $iContador++ ) {

      $oDadosCriterio     = db_utils::fieldsMemory( $rsCriterioAvaliacao, $iContador );
      $oCriterioAvaliacao = new CriterioAvaliacao( $oDadosCriterio->ed338_sequencial );
      $aCriterios[]       = $oCriterioAvaliacao;
    }

    return $aCriterios;
  }
}
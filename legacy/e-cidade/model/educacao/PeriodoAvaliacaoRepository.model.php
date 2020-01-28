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
 * Classe repository para classes PeriodoAvaliacao
 * @author  Fábio Esteves <fabio.esteves@dbseller.com.br>
 * @package educacao
 */
class PeriodoAvaliacaoRepository {

  /**
   * Coleção de PeriodoAvaliacao
   * @var array
   */
  private $aPeriodoAvaliacao = array();

  /**
   * Instancia da classe
   * @var PeriodoAvaliacaoRepository
   */
  private static $oInstance;

  private function __construct() {}
  private function __clone() {}

  /**
   * Retorna a instancia da classe
   * @return PeriodoAvaliacaoRepository
   */
  protected static function getInstance() {

    if ( self::$oInstance == null ) {
      self::$oInstance = new PeriodoAvaliacaoRepository();
    }
    return self::$oInstance;
  }

  /**
   * Retorno uma instancia do PeriodoAvaliacao pelo Codigo
   * @param  integer $iCodigo - Codigo do PeriodoAvaliacao
   * @return PeriodoAvaliacao
   */
  public static function getPeriodoAvaliacaoByCodigo( $iCodigo ) {

    if ( !array_key_exists( $iCodigo, PeriodoAvaliacaoRepository::getInstance()->aPeriodoAvaliacao ) ) {
      PeriodoAvaliacaoRepository::getInstance()->aPeriodoAvaliacao[$iCodigo] = new PeriodoAvaliacao( $iCodigo );
    }
    return PeriodoAvaliacaoRepository::getInstance()->aPeriodoAvaliacao[$iCodigo];
  }

  /**
   * Adiciona um PeriodoAvaliacao ao repositorio
   * @param  PeriodoAvaliacao $oPeriodoAvaliacao - Instancia do PeriodoAvaliacao
   * @return boolean
   */
  public static function adicionarPeriodoAvaliacao( PeriodoAvaliacao $oPeriodoAvaliacao ) {

    if( !array_key_exists( $oPeriodoAvaliacao->getCodigo(), PeriodoAvaliacaoRepository::getInstance()->aPeriodoAvaliacao ) ) {
      PeriodoAvaliacaoRepository::getInstance()->aPeriodoAvaliacao[$oPeriodoAvaliacao->getCodigo()] = $oPeriodoAvaliacao;
    }
    return true;
  }

  /**
   * Remove o PeriodoAvaliacao passado como parametro do repository
   * @param  PeriodoAvaliacao $oPeriodoAvaliacao
   * @return boolean
   */
  public static function removerPeriodoAvaliacao( PeriodoAvaliacao $oPeriodoAvaliacao ) {

    if ( array_key_exists( $oPeriodoAvaliacao->getCodigo(), PeriodoAvaliacaoRepository::getInstance()->aPeriodoAvaliacao ) ) {
      unset(PeriodoAvaliacaoRepository::getInstance()->aPeriodoAvaliacao[$oPeriodoAvaliacao->getCodigo()]);
    }
    return true;
  }

  /**
   * Retorna um array de instâncias de PeriodoAvaliacao que tem critério vinculado, de acordo com a escola passada por
   * parâmetro
   * @return PeriodoAvaliacao[]
   */
  public static function getPeriodosCriteriosAvaliacaoPorEscola( Escola $oEscola ) {

    $aPeriodoAvaliacao      = array();
    $oDaoPeriodoAvaliacao   = new cl_criterioavaliacaoperiodoavaliacao();
    $sWherePeriodoAvaliacao = "ed338_escola = {$oEscola->getCodigo()}";
    $sSqlPeriodoAvaliacao   = $oDaoPeriodoAvaliacao->sql_query( null, "ed09_i_codigo", null, $sWherePeriodoAvaliacao );
    $rsPeriodoAvaliacao     = db_query( $sSqlPeriodoAvaliacao );
    $iTotalPeriodoAvaliacao = pg_num_rows( $rsPeriodoAvaliacao );

    if ( $rsPeriodoAvaliacao && $iTotalPeriodoAvaliacao > 0 ) {

      for ( $iContador = 0; $iContador < $iTotalPeriodoAvaliacao; $iContador++ ) {

        $iPeriodoAvaliacao   = db_utils::fieldsMemory( $rsPeriodoAvaliacao, $iContador )->ed09_i_codigo;
        $aPeriodoAvaliacao[] = new PeriodoAvaliacao( $iPeriodoAvaliacao );
      }
    }

    return $aPeriodoAvaliacao;
  }
}
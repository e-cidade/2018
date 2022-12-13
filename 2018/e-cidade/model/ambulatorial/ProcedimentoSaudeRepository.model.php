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
 * @package  ambulatorial
 * @author   Andrio Costa  <andrio.costa@dbseller.com.br>
 * @author   Mariana Reck  <mariana.reck@dbseller.com.br>
 * @revision $Revision $
 */
class ProcedimentoSaudeRepository {

  private $aProcedimentoSaude      = array();
  private $aProcedimentoEstrutural = array();

  private static $oInstance;

  private function __construct() {}
  private function __clone() {}

  /**
   * Retorna uma instancia do repository
   * @return ProcedimentoSaudeRepository
   */

  private static function getInstance() {

    if ( self::$oInstance == null ) {
      self::$oInstance = new ProcedimentoSaudeRepository();
    }

    return self::$oInstance;
  }

  /**
   * Retorna por codigo
   * @param  integer   $iCodigo
   * @return ProcedimentoSaude
   */
  public static function getByCodigo($iCodigo) {

    if ( !array_key_exists($iCodigo, self::getInstance()->aProcedimentoSaude) ) {

      $oProcedimento = new ProcedimentoSaude($iCodigo);

      self::getInstance()->aProcedimentoSaude[$iCodigo]                             = $oProcedimento;
      self::getInstance()->aProcedimentoEstrutural[$oProcedimento->getEstrutural()] = $oProcedimento;
    }
    return self::getInstance()->aProcedimentoSaude[$iCodigo];
  }

  /**
   * Busca o procedimento pelo estrutural utilizando a ultima competência importada
   *
   * @param  string $sEstrutural código estrutural do procedimento
   * @return ProcedimentoSaude|null
   */
  public static function getByEstrutural($sEstrutural) {

    if ( !array_key_exists($sEstrutural, self::getInstance()->aProcedimentoEstrutural) ) {

      $oCompetenciaAtual = CompetenciaSigtap::getCompetencia();

      $sWhere  = "     sd63_c_procedimento = '{$sEstrutural}' ";
      $sWhere .= " and sd63_i_anocomp = {$oCompetenciaAtual->getAno()}";
      $sWhere .= " and sd63_i_mescomp = {$oCompetenciaAtual->getMes()}";
      $oDao   = new cl_sau_procedimento();


      $sSqlProcedimento   = $oDao->sql_query_file(null, "sd63_i_codigo ", null, $sWhere);
      $rsProcedimento     = db_query($sSqlProcedimento);
      if(!$rsProcedimento){
        throw new DBException("Erro ao buscar procedimentos: ".pg_last_error());
      }

      if (pg_num_rows($rsProcedimento) == 0) {
        return null;
      }

      $iCodigo = db_utils::fieldsMemory($rsProcedimento, 0)->sd63_i_codigo;
      self::getByCodigo($iCodigo);
    }

    return self::getInstance()->aProcedimentoEstrutural[$sEstrutural];
  }

  public static function adicionarProcedimentoSaude( ProcedimentoSaude $oProcedimentoSaude ) {

    self::getInstance()->aProcedimentoSaude[$oProcedimentoSaude->getCodigo()] = $oProcedimentoSaude;
  }

  public static function removeProcedimentoSaude( ProcedimentoSaude $oProcedimentoSaude ) {

    if (array_key_exists($oProcedimentoSaude->getCodigo(), self::getInstance()->aProcedimentoSaude)) {
      unset( self::getInstance()->aProcedimentoSaude[$oProcedimentoSaude->getCodigo()] );
    }
  }


  /**
   * Reseta Repository
   */
  public static function removeAll() {

    unset(self::getInstance()->aProcedimentoSaude);
    self::getInstance()->aProcedimentoSaude = array();
  }
}
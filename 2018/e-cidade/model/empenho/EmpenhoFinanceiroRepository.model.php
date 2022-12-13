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
 * Classe repository para classes EmpenhoFinanceiro
 *
 * @author Vinicius Martins <vinicius@dbseller.com.br>
 * @author Matheus Felini <matheus.felini@dbseller.com.br>
 * @package Empenho
 */
class EmpenhoFinanceiroRepository {

  /**
   * Collection de Empenho
   *
   * @var EmpenhoFinanceiro[]
   */
  private $aEmpenhoFinanceiro = array();

  /**
   * Instancia da classe
   *
   * @var EmpenhoFinanceiroRepository
   */
  private static $oInstance;

  /**
   * Construtor privado para não ser possível instanciar a classe
   */
  private function __construct() {}

  private function __clone() {}

  /**
   * Retorno uma instancia do empenho pelo Codigo
   *
   * @param integer $iCodigoEmpenho Codigo do Empenho
   * @return EmpenhoFinanceiro
   */
  public static function getEmpenhoFinanceiroPorNumero($iCodigoEmpenho) {

    if (!array_key_exists($iCodigoEmpenho, EmpenhoFinanceiroRepository::getInstance()->aEmpenhoFinanceiro)) {
      EmpenhoFinanceiroRepository::getInstance()->aEmpenhoFinanceiro[$iCodigoEmpenho] = new EmpenhoFinanceiro($iCodigoEmpenho);
    }

    return EmpenhoFinanceiroRepository::getInstance()->aEmpenhoFinanceiro[$iCodigoEmpenho];
  }

  /**
   * Retorna uma instancia de EmpenhoFinanceiro por código/ano
   * @param int         $iCodigoEmpenho
   * @param int         $iAnoEmpenho
   * @param Instituicao $oInstituicao
   *
   * @return EmpenhoFinanceiro
   * @throws Exception
   */
  public static function getEmpenhoFinanceiroPorCodigoAno($iCodigoEmpenho, $iAnoEmpenho, Instituicao $oInstituicao) {

    $oDaoEmpempenho = new cl_empempenho();
    $sSqlEmpempenho = $oDaoEmpempenho->sql_query_file(null, "e60_numemp", null, "e60_codemp = '{$iCodigoEmpenho}' and e60_anousu = {$iAnoEmpenho} and e60_instit = {$oInstituicao->getCodigo()}");
    $rsEmpenho      = $oDaoEmpempenho->sql_record( $sSqlEmpempenho );
    if (!$rsEmpenho || $oDaoEmpempenho->erro_status == "0" || $oDaoEmpempenho->numrows == 0) {
      throw new Exception("Empenho {$iCodigoEmpenho}/{$iAnoEmpenho} não encontrado.");
    }
    $iNumeroEmpenho = db_utils::fieldsMemory($rsEmpenho, 0)->e60_numemp;
    return self::getEmpenhoFinanceiroPorNumero($iNumeroEmpenho);
  }

  /**
   * Retorna a instancia da classe
   *
   * @return EmpenhoFinanceiroRepository
   */
  protected static function getInstance() {

    if (self::$oInstance == null) {
      self::$oInstance = new EmpenhoFinanceiroRepository();
    }

    return self::$oInstance;
  }
}
<?php
/**
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
 * Classe repository para classes ReceitaOrcamentaria
 *
 * @package Orcamento
 */
class ReceitaOrcamentariaRepository {

  /**
   * Collection de ReceitaOrcamentaria
   *
   * @var ReceitaOrcamentaria[]
   */
  private $aItens = array();

  /**
   * Instancia da classe
   *
   * @var ReceitaOrcamentariaRepository
   */
  private static $oInstancia;

  /**
   * Bloqueia instancia
   */
  private function __construct() {}
  private function __clone() {}

  /**
   * Retorna a instancia da classe
   * @return ReceitaOrcamentariaRepository
   */
  protected static function getInstancia() {

    if (self::$oInstancia == null) {
      self::$oInstancia = new ReceitaOrcamentariaRepository();
    }
    return self::$oInstancia;
  }

  /**
   * @param $sEstrutural
   * @param $iAno
   * @return bool|ReceitaOrcamentaria
   */
  public static function getPorEstrutural($sEstrutural, $iAno = null) {

    if (empty($iAno)) {
      $iAno = db_getsession('DB_anousu');
    }

    $oDaoOrcFontes    = new cl_orcfontes();
    $sWhere           = "o57_anousu = {$iAno} and o57_fonte = '{$sEstrutural}'";
    $sSqlBuscaReceita = $oDaoOrcFontes->sql_query_fonte_receita('o70_codrec as receita', null, $sWhere);
    $rsBuscaReceita   = $oDaoOrcFontes->sql_record($sSqlBuscaReceita);
    if ($oDaoOrcFontes->erro_status == "0") {
      return false;
    }

    $iCodigoReceita = db_utils::fieldsMemory($rsBuscaReceita, 0)->receita;
    $sHash = "{$iCodigoReceita}{$iAno}";
    if (!array_key_exists($sEstrutural, ReceitaOrcamentariaRepository::getInstancia()->aItens)) {
      ReceitaOrcamentariaRepository::getInstancia()->aItens[$sHash] = new ReceitaOrcamentaria($iCodigoReceita, $iAno);
    }
    return ReceitaOrcamentariaRepository::getInstancia()->aItens[$sHash];
  }

}
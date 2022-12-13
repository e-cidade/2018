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


class CensoUFRepository {

  protected static $oInstance;

  protected $aUF;

  protected function __construct() {

    $oDaoCensoUF = db_utils::getDao("censouf");
    $sSqlCenso   = $oDaoCensoUF->sql_query_file(null, "*", "ed260_i_codigo");
    $rsCensoUF   = $oDaoCensoUF->sql_record($sSqlCenso);
    $aUF         = db_utils::getCollectionByRecord($rsCensoUF);
    foreach ($aUF as $oUF) {

      $oCensoUF = new CensoUF($oUF->ed260_i_codigo, $oUF->ed260_c_sigla, $oUF->ed260_c_nome);
      $this->aUF[$oUF->ed260_i_codigo] = $oCensoUF;
    }
    unset($aUF);
  }


  /**
   * Retorna a instancia da classe
   * @return CensoUFRepository
   */
  protected static function getInstance() {

    if (self::$oInstance == null) {
      self::$oInstance = new CensoUFRepository();
    }
    return self::$oInstance;
  }

  /**
   * Retorna a UF por Codigo
   * @return CensoUF
   */
  public static function getEstadoPorCodigo($iCodigo) {

    if (isset(CensoUFRepository::getInstance()->aUF[$iCodigo])) {
      return CensoUFRepository::getInstance()->aUF[$iCodigo];
    }
    return false;
  }
}

?>
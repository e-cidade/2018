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

class DBAttDinamicoValor {

  /**
   * @var integer
   */
  private $iCodigo;

  /**
   * @var DBAttDinamicoAtributo
   */
  private $oAtributo;

  /**
   * @var integer
   */
  private $iCodigoGrupo;

  /**
   * @var string
   */
  private $sValor;

  /**
   * @param string $sCodigo
   */
  public function setCodigo($sCodigo) {
    $this->iCodigo = $sCodigo;
  }

  /**
   * @return string
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @param DBAttDinamicoAtributo $oAtributo
   */
  public function setAtributo(DBAttDinamicoAtributo $oAtributo) {
    $this->oAtributo = $oAtributo;
  }

  /**
   * @return DBAttDinamicoAtributo
   */
  public function getAtributo() {
    return $this->oAtributo;
  }

  /**
   * @param integer $iCodigoGrupo
   */
  public function setCodigoGrupo($iCodigoGrupo) {
    $this->iCodigoGrupo = $iCodigoGrupo;
  }

  /**
   * @return string
   */
  public function getCodigoGrupo() {
    return $this->iCodigoGrupo;
  }

  /**
   * @param string $sValor
   */
  public function setValor($sValor) {
    $this->sValor = $sValor;
  }

  /**
   * @return string
   */
  public function getValor() {
    return $this->sValor;
  }

  /**
   * Busca os valores dos atributos dinamicos pelo grupo de valores
   *
   * @param  integer $iCodigoGrupo
   * @return DBAttDinamicoValor[]
   */
  public static function getValores($iCodigoGrupo) {

    $aValores = array();

    if (empty($iCodigoGrupo)) {
      return $aValores;
    }

    $oDaoValor = new cl_db_cadattdinamicoatributosvalor();
    $sSqlValor = $oDaoValor->sql_query_file(null, "*", null, "db110_cadattdinamicovalorgrupo = {$iCodigoGrupo}");
    $rsValor   = $oDaoValor->sql_record($sSqlValor);

    if ($rsValor && $oDaoValor->numrows > 0) {

      $aValores = db_utils::makeCollectionFromRecord($rsValor, function($oItem) {

        $oValor = new DBAttDinamicoValor();
        $oValor->setCodigo($oItem->db110_sequencial);
        $oValor->setAtributo(DBAttDinamicoAtributoRepository::getDBAttDinamicoAtributoPorCodigo($oItem->db110_db_cadattdinamicoatributos));
        $oValor->setCodigoGrupo($oItem->db110_cadattdinamicovalorgrupo);
        $oValor->setValor($oItem->db110_valor);

        return $oValor;
      });
    }

    return $aValores;
  }
}
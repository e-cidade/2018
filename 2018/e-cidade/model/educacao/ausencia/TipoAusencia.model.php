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
 * Tipos de Ausencia / Justificativa da ausencia
 * @package educacao
 * @subpackage ausencia
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 */
class TipoAusencia {

  private $iCodigo;
  private $sDescricao;
  private $iTipo;

  static private $aTiposAusencia = array( 1 => "Nenhum",
                                          2 => "Licença",
                                          3 => "Falta Abonada",
                                          4 => "Falta Não Justificada");

  public function __construct($iCodigo = null) {

    if (!empty($iCodigo)) {

      $oDaoTipoAusencia = db_utils::getDao('tipoausencia');
      $sSqlTipoAusencia = $oDaoTipoAusencia->sql_query_file($iCodigo);
      $rsTipoAusencia   = $oDaoTipoAusencia->sql_record($sSqlTipoAusencia);


      if ($oDaoTipoAusencia->numrows == 1) {

        $oTipoAusencia    = db_utils::fieldsMemory($rsTipoAusencia, 0);
        $this->iCodigo    = $oTipoAusencia->ed320_sequencial;
        $this->sDescricao = $oTipoAusencia->ed320_descricao;
        $this->iTipo      = $oTipoAusencia->ed320_tipo;

      }
    }

    return null;
  }

  /**
   * Retorna o codigo do tipo de ausencia
   */
  public function getCodigo() {

    return $this->iCodigo;
  }

  /**
   * Atribui a descricao da ausencia
   */
  public function setDescricao($sDescricao) {

    $this->sDescricao = $sDescricao;
  }

  /**
   * Retorna a descricao da Ausencia
   */
  public function getDescricao() {

    return $this->sDescricao;
  }

  /**
   * Verifica se o tipo de ausencia é uma licença
   *
   * @todo verficar impactos
   * @todo remover
   *
   * @return boolean
   */
  public function isLicenca() {

    return $this->lLicenca;
  }



  /**
   * Define o tipo da Ausencia
   * @param integer
   */
  public function setTipo ($iTipo) {
    $this->iTipo = $iTipo;
  }

  /**
   * Retorna o tipo da ausencia
   * @param integer
   */
  public function getTipo () {
    return $this->iTipo;
  }


  public function getDescricaoTipo() {

    return self::$aTiposAusencia($this->iTipo);
  }


}
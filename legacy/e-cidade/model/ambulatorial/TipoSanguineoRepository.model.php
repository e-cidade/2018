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
 * Classe repository para os tipos sanguineos
 */
class TipoSanguineoRepository {

  /**
   * Array com os tipos sanguineos
   * @var array
   */
  private $aTipoSanguineo = array();

  /**
   * Instancia da classe
   * @var TipoSanguineoRepository
   */
  private static $oInstance;

  private function __construct() {

  }
  private function __clone() {

  }

  /**
   * Retorna a instancia da classe
   * @return TipoSanguineoRepository
   */
  protected static function getInstance() {

    if (self::$oInstance == null) {
      self::$oInstance = new TipoSanguineoRepository();
    }
    return self::$oInstance;
  }

  /**
   * Retorno o Fator Rh do sangue de acordo com o código informado
   * @param  integer $iCodigo Codigo do tipo sanguineo
   * @return string
   */
  public static function getByCodigo($iCodigo) {

    if (!array_key_exists($iCodigo, TipoSanguineoRepository::getInstance()->aTipoSanguineo)) {

      $oDao = new cl_tiposanguineo();
      $rs   = db_query( $oDao->sql_query_file($iCodigo) );

      if ( !$rs || pg_num_rows($rs) == 0 ) {
        return "";
      }
      TipoSanguineoRepository::getInstance()->aTipoSanguineo[$iCodigo] = db_utils::fieldsMemory($rs, 0)->sd100_tipo;
    }

    return TipoSanguineoRepository::getInstance()->aTipoSanguineo[$iCodigo];
  }

}
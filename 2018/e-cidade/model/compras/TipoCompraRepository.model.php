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
 * Dependencias
 */
require_once 'model/compras/TipoCompra.model.php';

/**
 * Classe repository para classes TipoCompra
 * @package compras
 */
class TipoCompraRepository {

  /**
   * Collection de TipoCompra
   * @var array
   */
  private $aTipoCompra = array();

  /**
   * Instancia da classe
   * @var TipoCompraRepository
   */
  private static $oInstance;

  private function __construct() {

  }

  private function __clone() {

  }

  /**
   * Retorno uma instancia de TipoCompra pelo Codigo
   * @param integer $iCodigo Codigo do TipoCompra
   * @return TipoCompra
   */
  public static function getTipoCompraByCodigo($iCodigoTipoCompra) {

    if (!array_key_exists($iCodigoTipoCompra, TipoCompraRepository::getInstance()->aTipoCompra)) {
      TipoCompraRepository::getInstance()->aTipoCompra[$iCodigoTipoCompra] = new TipoCompra($iCodigoTipoCompra);
    }
    return TipoCompraRepository::getInstance()->aTipoCompra[$iCodigoTipoCompra];
  }

  /**
   * Retorna a instancia da classe
   * @return TipoCompraRepository
   */
  protected static function getInstance() {

    if (self::$oInstance == null) {

      self::$oInstance = new TipoCompraRepository();
    }
    return self::$oInstance;
  }

  /**
   * Adiciona um TipoCompra dao repositorio
   * @param TipoCompra $oTipoCompra Instancia do TipoCompra
   * @return boolean
   */
  public static function adicionarTipoCompra(TipoCompra $oTipoCompra) {

    if(!array_key_exists($oTipoCompra->getSequencial(), TipoCompraRepository::getInstance()->aTipoCompra)) {
      TipoCompraRepository::getInstance()->aTipoCompra[$oTipoCompra->getSequencial()] = $oTipoCompra;
    }
    return true;
  }

  /**
   * Remove o TipoCompra passado como parametro do repository
   * @param TipoCompra $oTipoCompra
   * @return boolean
   */
  public static function removerTipoCompra(TipoCompra $oTipoCompra) {

    if (array_key_exists($oTipoCompra->getSequencial(), TipoCompraRepository::getInstance()->aTipoCompra)) {
      unset(TipoCompraRepository::getInstance()->aTipoCompra[$oTipoCompra->getSequencial()]);
    }
    return true;
  }

  /**
   * Retorna o total de cidadoes existentes no repositorio;
   * @return integer;
   */
  public static function getTotalTipoCompra() {
    return count(TipoCompraRepository::getInstance()->aTipoCompra);
  }
}
<?php
/**
 * E-cidade Software Publico para Gest�o Municipal
 *   Copyright (C) 2014 DBSeller Servi�os de Inform�tica Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa � software livre; voc� pode redistribu�-lo e/ou
 *   modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a vers�o 2 da
 *   Licen�a como (a seu crit�rio) qualquer vers�o mais nova.
 *   Este programa e distribu�do na expectativa de ser �til, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia impl�cita de
 *   COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM
 *   PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais
 *   detalhes.
 *   Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU
 *   junto com este programa; se n�o, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   C�pia da licen�a no diret�rio licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */

/**
 * Classe repository para classes Cgm
 *
 * @author
 * @package
 */
class CgmRepository {

  /**
   * Collection de Cgm
   *
   * @var array
   */
  private $aItens = array();
  /**
   * Instancia da classe
   *
   * @var CgmRepository
   */
  private static $oInstance;

  private function __construct() {

  }

  private function __clone() {

  }

  /**
   * Retorna uma instancia do Cgm pelo Codigo
   *
   * @param integer $iCodigo Codigo do Cgm
   * @return CgmFisico|CgmJuridico
   */
  public static function getByCodigo($iCodigo) {

    if (!array_key_exists($iCodigo, CgmRepository::getInstance()->aItens)) {
      CgmRepository::getInstance()->aItens[$iCodigo] = CgmFactory::getInstanceByCgm($iCodigo);
    }
    return CgmRepository::getInstance()->aItens[$iCodigo];
  }

  /**
   * Retorna a instancia da classe
   *
   * @return CgmRepository
   */
  protected static function getInstance() {

    if (self::$oInstance == null) {

      self::$oInstance = new CgmRepository();
    }
    return self::$oInstance;
  }

  /**
   * Adiciona uma instancia de Cgm ao repositorio
   *
   * @param CgmBase $oCgm Instancia de Cgm
   * @return boolean
   */
  public static function adicionarCgm(CgmBase $oCgm) {

    if (!array_key_exists($oCgm->getCodigo(), CgmRepository::getInstance()->aItens)) {
      CgmRepository::getInstance()->aItens[$oCgm->getCodigo()] = $oCgm;
    }
    return true;
  }

  /**
   * Remove a instancia passada como parametro do repository
   *
   * @param CgmBase $oCgm
   * @return boolean
   */
  public static function remover(CgmBase $oCgm) {
    /**
     *
     */
    if (array_key_exists($oCgm->getCodigo(), CgmRepository::getInstance()->aItens)) {
      unset(CgmRepository::getInstance()->aItens[$oCgm->getCodigo()]);
    }
    return true;
  }

  /**
   * Retorna o total de itens existentes no repositorio;
   *
   * @return integer;
   */
  public static function getTotalCgm() {
    return count(CgmRepository::getInstance()->aItens);
  }

}
<?php
/**
 * E-cidade Software Publico para Gestão Municipal
 *   Copyright (C) 2014 DBSeller Serviços de Informática Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa é software livre; você pode redistribuí-lo e/ou
 *   modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versão 2 da
 *   Licença como (a seu critério) qualquer versão mais nova.
 *   Este programa e distribuído na expectativa de ser útil, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *   COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *   PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *   detalhes.
 *   Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *   junto com este programa; se não, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   Cópia da licença no diretório licenca/licenca_en.txt
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
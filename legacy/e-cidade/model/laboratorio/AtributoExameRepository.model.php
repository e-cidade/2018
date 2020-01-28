<?php
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

/**
 * Classe repository para classes AtributoExame
 *
 * @author
 * @package
 * @static
 */
class AtributoExameRepository {

  /**
   * Collection de AtributoExame
   *
   * @var array
   */
  private $aItens = array();
  /**
   * Instancia da classe
   *
   * @var AtributoExameRepository
   */
  private static $oInstance;

  private function __construct() {

  }

  private function __clone() {

  }

  /**
   * Retorna uma instancia do AtributoExame pelo Codigo
   *
   * @param integer $iCodigo Codigo do AtributoExame
   * @return AtributoExame
   */
  public static function getByCodigo($iCodigo) {

    if (!array_key_exists($iCodigo, AtributoExameRepository::getInstance()->aItens)) {
      AtributoExameRepository::getInstance()->aItens[$iCodigo] = new AtributoExame($iCodigo);
    }
    return AtributoExameRepository::getInstance()->aItens[$iCodigo];
  }

  /**
   * Retorna a instancia da classe
   *
   * @return AtributoExameRepository
   */
  protected static function getInstance() {

    if (self::$oInstance == null) {

      self::$oInstance = new AtributoExameRepository();
    }
    return self::$oInstance;
  }

  /**
   * Adiciona uma instancia de AtributoExame ao repositorio
   *
   * @param AtributoExame $oAtributoExame Instancia de AtributoExame
   * @return boolean
   */
  public static function adicionarAtributoExame(AtributoExame $oAtributoExame) {

    if (!array_key_exists($oAtributoExame->getCodigo(), AtributoExameRepository::getInstance()->aItens)) {
      AtributoExameRepository::getInstance()->aItens[$oAtributoExame->getCodigo()] = $oAtributoExame;
    }
    return true;
  }

  /**
   * Remove a instancia passada como parametro do repository
   *
   * @param AtributoExame $oAtributoExame
   * @return boolean
   */
  public static function remover(AtributoExame $oAtributoExame) {
    /**
     *
     */
    if (array_key_exists($oAtributoExame->getCodigo(), AtributoExameRepository::getInstance()->aItens)) {
      unset(AtributoExameRepository::getInstance()->aItens[$oAtributoExame->getCodigo()]);
    }
    return true;
  }

  /**
   * Retorna o total de itens existentes no repositorio;
   *
   * @return integer;
   */
  public static function getTotalAtributoExame() {
    return count(AtributoExameRepository::getInstance()->aItens);
  }
}
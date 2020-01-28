<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
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
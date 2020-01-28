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

require_once 'model/patrimonio/Item.model.php';

/**
 * Item
 * 
 * @package patrimonio 
 * @author Jeferson Belmiro <jeferson.belmiro@dbseller.com.br> 
 */
class ItemRepository {

  /**
   * Itens instanciados
   * 
   * @var array
   * @access private
   */
  private $aItens = array();

  /**
   * Instancia do repository
   * 
   * @static
   * @var ItemRepository
   * @access private
   */
  private static $oInstancia;

  /**
   * Bloqueia instanciar e clonar objeto externamente
   */
  private function __construct() {}
  private function __clone() {}

  /**
   * Retorna instancia do repository
   *
   * @access public
   * @return ItemRepository
   */
  public function getInstancia() {

    if(self::$oInstancia == null) {
      self::$oInstancia = new ItemRepository();
    }

    return self::$oInstancia;
  }

  /**
   * Retorna item pelo codigo
   *
   * @param integer $iCodigo
   * @static
   * @access public
   * @return Item
   */
  public static function getItemByCodigo($iCodigo) {

    if (!array_key_exists($iCodigo, ItemRepository::getInstancia()->aItens)) {
      ItemRepository::getInstancia()->aItens[$iCodigo] = new Item($iCodigo);
    }

    return ItemRepository::getInstancia()->aItens[$iCodigo];
  }

}
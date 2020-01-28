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
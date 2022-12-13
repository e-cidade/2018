<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
   * Classe repository para classes UnidadeProntoSocorro
   * @package ambulatorial
   * @author Andrio Costa <andrio.costa@dbseller.com.br>
   * @version $Revision: 1.1 $
   */
  class UnidadeProntoSocorroRepository {
    
    /** 
     * Collection de UnidadeProntoSocorro 
     * @var array
     */    
    private $aUnidadeProntoSocorro = array();
    
    /**
     * Instancia da classe 
     * @var UnidadeProntoSocorroRepository
     */
    private static $oInstance;
    
    private function __construct() {
      
    }
    private function __clone() {
     
    }
 
    /**
     * Retorno uma instancia do UnidadeProntoSocorro pelo Codigo
     * @param integer $iCodigo Codigo do UnidadeProntoSocorro
     * @return UnidadeProntoSocorro
     */
    public static function getUnidadeProntoSocorroByCodigo($iCodigoUnidadeProntoSocorro) {
      
      if (!array_key_exists($iCodigoUnidadeProntoSocorro, UnidadeProntoSocorroRepository::getInstance()->aUnidadeProntoSocorro)) {
        UnidadeProntoSocorroRepository::getInstance()->aUnidadeProntoSocorro[$iCodigoUnidadeProntoSocorro] = new UnidadeProntoSocorro($iCodigoUnidadeProntoSocorro);
      }
      return UnidadeProntoSocorroRepository::getInstance()->aUnidadeProntoSocorro[$iCodigoUnidadeProntoSocorro];
    } 
    
    /**
     * Retorna a instancia da classe
     * @return UnidadeProntoSocorroRepository
     */
    protected static function getInstance() {
      
      if (self::$oInstance == null) {
              
        self::$oInstance = new UnidadeProntoSocorroRepository();     
      }
      return self::$oInstance;
    }
    
    /**
     * Adiciona um UnidadeProntoSocorro dao repositorio
     * @param UnidadeProntoSocorro $oUnidadeProntoSocorro Instancia do UnidadeProntoSocorro
     * @return boolean
     */
    public static function adicionarUnidadeProntoSocorro(UnidadeProntoSocorro $oUnidadeProntoSocorro) {
    
      if(!array_key_exists($oUnidadeProntoSocorro->getCodigo(), UnidadeProntoSocorroRepository::getInstance()->aUnidadeProntoSocorro)) {
        UnidadeProntoSocorroRepository::getInstance()->aUnidadeProntoSocorro[$oUnidadeProntoSocorro->getCodigo()] = $oUnidadeProntoSocorro;
      }
      return true;
    }
    
    /**
     * Remove o UnidadeProntoSocorro passado como parametro do repository
     * @param UnidadeProntoSocorro $oUnidadeProntoSocorro
     * @return boolean 
     */ 
    public static function removerUnidadeProntoSocorro(UnidadeProntoSocorro $oUnidadeProntoSocorro) {
       /**
        * 
        */ 
      if (array_key_exists($oUnidadeProntoSocorro->getCodigo(), UnidadeProntoSocorroRepository::getInstance()->aUnidadeProntoSocorro)) {
        unset(UnidadeProntoSocorroRepository::getInstance()->aUnidadeProntoSocorro[$oUnidadeProntoSocorro->getCodigo()]);
      }
      return true;
    }
    
    /** 
     * Retorna o total de cidadoes existentes no repositorio; 
     * @return integer; 
     */
    public static function getTotalUnidadeProntoSocorro() {
      return count(UnidadeProntoSocorroRepository::getInstance()->aUnidadeProntoSocorro);
    }
  }
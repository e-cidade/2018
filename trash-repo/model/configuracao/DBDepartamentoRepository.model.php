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
 * Repository para classe DBDepartamento
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 * @package configuracao
 */
class DBDepartamentoRepository {
  
  /** 
   * Collection de DBDepartamento 
   * @var array
   */    
  private $aDBDepartamento = array();
  
  /**
   * Instancia da classe 
   * @var DBDepartamentoRepository
   */
  private static $oInstance;
  
  private function __construct() {
    
  }
  private function __clone() {
   
  }

  /**
   * Retorno uma instancia do DBDepartamento pelo Codigo
   * @param integer $iCodigo Codigo do DBDepartamento
   * @return DBDepartamento
   */
  public static function getDBDepartamentoByCodigo($iCodigoDBDepartamento) {
    
    if (!array_key_exists($iCodigoDBDepartamento, DBDepartamentoRepository::getInstance()->aDBDepartamento)) {
      DBDepartamentoRepository::getInstance()->aDBDepartamento[$iCodigoDBDepartamento] = new DBDepartamento($iCodigoDBDepartamento);
    }
    return DBDepartamentoRepository::getInstance()->aDBDepartamento[$iCodigoDBDepartamento];
  } 
  
  /**
   * Retorna a instancia da classe
   * @return DBDepartamentoRepository
   */
  protected static function getInstance() {
    
    if (self::$oInstance == null) {
      self::$oInstance = new DBDepartamentoRepository();     
    }
    return self::$oInstance;
  }
  
  /**
   * Adiciona um DBDepartamento ao repositorio
   * @param DBDepartamento $oDBDepartamento Instancia do DBDepartamento
   * @return boolean
   */
  public static function adicionarDBDepartamento(DBDepartamento $oDBDepartamento) {
  
    if(!array_key_exists($oDBDepartamento->getCodigo(), DBDepartamentoRepository::getInstance()->aDBDepartamento)) {
      DBDepartamentoRepository::getInstance()->aDBDepartamento[$oDBDepartamento->getCodigo()] = $oDBDepartamento;
    }
    return true;
  }
  
  /**
   * Remove o DBDepartamento passado como parametro do repository
   * @param DBDepartamento $oDBDepartamento
   * @return boolean 
   */ 
  public static function removerDBDepartamento(DBDepartamento $oDBDepartamento) {
    
    if (array_key_exists($oDBDepartamento->getCodigo(), DBDepartamentoRepository::getInstance()->aDBDepartamento)) {
      unset(DBDepartamentoRepository::getInstance()->aDBDepartamento[$oDBDepartamento->getCodigo()]);
    }
    return true;
  }
}
?>
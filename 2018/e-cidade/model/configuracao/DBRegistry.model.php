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
 * Classe Para controle de cache de informações
 * @author iuri@dbseller.com.br
 * @package configuracao
 */
final class DBRegistry {

  /**
   * itens guardados no cache
   * @var array
   */
  private $aitens = array();

  /**
   * Instancia unica do Registry
   * @var DBRegistry
   */
  private static $sInstance = null;

  /**
   * Metodo construtor marcado como privado,
   * para evitar mais de uma instancia
   */
  private function __construct() {

  }

  private function __clone() {}

  /**
   * Metodo que define a criação da instancia da classe
   * @return DBRegistry
   */
  private static function getInstance() {

    if (self::$sInstance == null) {
       self::$sInstance = new DBRegistry();
    }
    return self::$sInstance;
  }
  /**
   * Adiciona um item ao cache
   * @param mixed $sIdentifier chave que identifica o valor do cache
   * @param mixed $mContent conteudo a ser cacheado
   */
  public static function add($sIdentifier, $mContent) {

    self::getInstance()->aitens[$sIdentifier] = $mContent;
  }

  /**
   * Remove o item correspondente a chave
   * @param mixed $sIdentifier chave a ser removida
   */
  public static function remove($sIdentifier) {
    unset(self::getInstance()->aitens[$sIdentifier]);
  }

  /**
   * Retorna um item do repositorio
   * Caso nao exista o item no repositorio, retorna NULL
   * @param mixed $sIdentifier chave a ser pesquisada
   * @return mixed
   */
  public static function get($sIdentifier) {

    if (isset(self::getInstance()->aitens[$sIdentifier])) {
      return self::getInstance()->aitens[$sIdentifier];
    }
    return null;
  }

  /**
   * Verifica se a chave existe no registry
   * @param $sIdentifier
   * @return bool
   */
  public static function has($sIdentifier) {
    return isset(self::getInstance()->aitens[$sIdentifier]);
  }

  /**
   * Retorna o total de itens salvos no repository
   * @return number total de itens no repositorio
   */
  public static  function getTotalItens() {
    return count(self::getInstance()->aitens);
  }
}
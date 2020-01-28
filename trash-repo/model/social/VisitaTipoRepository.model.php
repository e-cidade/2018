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
 * Classe repository para classes VisitaTipo
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 * @package social
 */
class VisitaTipoRepository {
  
  /** 
   * Collection de VisitaTipo 
   * @var array
   */    
  private $aVisitaTipo = array();
  
  /**
   * Instancia da classe 
   * @var VisitaTipoRepository
   */
  private static $oInstance;
  
  private function __construct() {
    
  }
  private function __clone() {
   
  }

  /**
   * Retorno uma instancia do VisitaTipo pelo Codigo
   * @param integer $iCodigo Codigo do VisitaTipo
   * @return VisitaTipo
   */
  public static function getVisitaTipoByCodigo($iCodigoVisitaTipo) {
    
    if (!array_key_exists($iCodigoVisitaTipo, VisitaTipoRepository::getInstance()->aVisitaTipo)) {
      VisitaTipoRepository::getInstance()->aVisitaTipo[$iCodigoVisitaTipo] = new VisitaTipo($iCodigoVisitaTipo);
    }
    return VisitaTipoRepository::getInstance()->aVisitaTipo[$iCodigoVisitaTipo];
  } 
  
  /**
   * Retorna a instancia da classe
   * @return VisitaTipoRepository
   */
  protected static function getInstance() {
    
    if (self::$oInstance == null) {
      self::$oInstance = new VisitaTipoRepository();     
    }
    return self::$oInstance;
  }
  
  /**
   * Adiciona um VisitaTipo ao repositorio
   * @param VisitaTipo $oVisitaTipo Instancia do VisitaTipo
   * @return boolean
   */
  public static function adicionarVisitaTipo(VisitaTipo $oVisitaTipo) {
  
    if(!array_key_exists($oVisitaTipo->getCodigo(), VisitaTipoRepository::getInstance()->aVisitaTipo)) {
      VisitaTipoRepository::getInstance()->aVisitaTipo[$oVisitaTipo->getCodigo()] = $oVisitaTipo;
    }
    return true;
  }
  
  /**
   * Remove o VisitaTipo passado como parametro do repository
   * @param VisitaTipo $oVisitaTipo
   * @return boolean 
   */ 
  public static function removerVisitaTipo(VisitaTipo $oVisitaTipo) {
    
    if (array_key_exists($oVisitaTipo->getCodigo(), VisitaTipoRepository::getInstance()->aVisitaTipo)) {
      unset(VisitaTipoRepository::getInstance()->aVisitaTipo[$oVisitaTipo->getCodigo()]);
    }
    return true;
  }
}
?>
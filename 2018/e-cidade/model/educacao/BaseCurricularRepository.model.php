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
   * Classe repository para classes BaseCurricular
   * @author Iuri Guntchnigg <iuri@dbseller.com.br>
   * @package
   */
  class BaseCurricularRepository {

    /**
     * Collection de BaseCurricular
     * @var array
     */
    private $aBaseCurricular = array();

    /**
     * Instancia da classe
     * @var BaseCurricularRepository
     */
    private static $oInstance;

    private function __construct() {

    }
    private function __clone() {

    }

    /**
     * Retorno uma instancia do BaseCurricular pelo Codigo
     * @param integer $iCodigo Codigo do BaseCurricular
     * @return BaseCurricular
     */
    public static function getBaseCurricularByCodigo($iCodigoBaseCurricular) {

      if (!array_key_exists($iCodigoBaseCurricular, BaseCurricularRepository::getInstance()->aBaseCurricular)) {
        BaseCurricularRepository::getInstance()->aBaseCurricular[$iCodigoBaseCurricular] = new BaseCurricular($iCodigoBaseCurricular);
      }
      return BaseCurricularRepository::getInstance()->aBaseCurricular[$iCodigoBaseCurricular];
    }

    /**
     * Retorna a instancia da classe
     * @return BaseCurricularRepository
     */
    protected static function getInstance() {

      if (self::$oInstance == null) {

        self::$oInstance = new BaseCurricularRepository();
      }
      return self::$oInstance;
    }

    /**
     * Adiciona um BaseCurricular dao repositorio
     * @param BaseCurricular $oBaseCurricular Instancia do BaseCurricular
     * @return boolean
     */
    public static function adicionarBaseCurricular(BaseCurricular $oBaseCurricular) {

      if(!array_key_exists($oBaseCurricular->getCodigo(), BaseCurricularRepository::getInstance()->aBaseCurricular)) {
        BaseCurricularRepository::getInstance()->aBaseCurricular[$oBaseCurricular->getCodigo()] = $oBaseCurricular;
      }
      return true;
    }

    /**
     * Remove o BaseCurricular passado como parametro do repository
     * @param BaseCurricular $oBaseCurricular
     * @return boolean
     */
    public static function removerBaseCurricular(BaseCurricular $oBaseCurricular) {
       /**
        *
        */
      if (array_key_exists($oBaseCurricular->getCodigo(), BaseCurricularRepository::getInstance()->aBaseCurricular)) {
        unset(BaseCurricularRepository::getInstance()->aBaseCurricular[$oBaseCurricular->getCodigo()]);
      }
      return true;
    }

    /**
     * Retorna o total de cidadoes existentes no repositorio;
     * @return integer;
     */
    public static function getTotalBaseCurricular() {
      return count(BaseCurricularRepository::getInstance()->aBaseCurricular);
    }
  }
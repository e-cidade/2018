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
   * Classe repository para classes CensoMunicipio
   * @author Iuri Guntchnigg <iuri@dbseller.com.br>
   * @package
   */
  class CensoMunicipioRepository {
    
    /**
     * Collection de CensoMunicipio
     * @var array
     */
    private $aItens = array();
    
    /**
     * Instancia da classe
     * @var CensoMunicipioRepository
     */
    private static $oInstance;
    
    private function __construct() {
      
    }
    private function __clone() {
     
    }
 
    /**
     * Retorno uma instancia do CensoMunicipio pelo Codigo
     * @param integer $iCodigo Codigo do CensoMunicipio
     * @return CensoMunicipio
     */
    public static function getMunicipioByCodigo($iCodigo) {
      
      if (!array_key_exists($iCodigo, CensoMunicipioRepository::getInstance()->aItens)) {
        CensoMunicipioRepository::getInstance()->aItens[$iCodigo] = new CensoMunicipio($iCodigo);
      }
      return CensoMunicipioRepository::getInstance()->aItens[$iCodigo];
    }
    
    /**
     * Retorna a instancia da classe
     * @return CensoMunicipioRepository
     */
    protected static function getInstance() {
      
      if (self::$oInstance == null) {
              
        self::$oInstance = new CensoMunicipioRepository();
      }
      return self::$oInstance;
    }
    
    /**
     * Adiciona um CensoMunicipio dao repositorio
     * @param CensoMunicipio $oCensoMunicipio Instancia do CensoMunicipio
     * @return boolean
     */
    public static function adicionarCensoMunicipio(CensoMunicipio $oCensoMunicipio) {
    
      if(!array_key_exists($oCensoMunicipio->getCodigo(), CensoMunicipioRepository::getInstance()->aItens)) {
        CensoMunicipioRepository::getInstance()->aItens[$oCensoMunicipio->getCodigo()] = $oCensoMunicipio;
      }
      return true;
    }
    
    /**
     * Remove o CensoMunicipio passado como parametro do repository
     * @param CensoMunicipio $oCensoMunicipio
     * @return boolean
     */
    public static function removerCensoMunicipio(CensoMunicipio $oCensoMunicipio) {
       /**
        *
        */
      if (array_key_exists($oCensoMunicipio->getCodigo(), CensoMunicipioRepository::getInstance()->aItens)) {
        unset(CensoMunicipioRepository::getInstance()->aItens[$oCensoMunicipio->getCodigo()]);
      }
      return true;
    }
    
    /**
     * Retorna o total de cidadoes existentes no repositorio;
     * @return integer;
     */
    public static function getTotalCensoMunicipio() {
      return count(CensoMunicipioRepository::getInstance()->aItens);
    }
  }
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
   * Classe repository para classes CensoCartorio
   * @author Iuri Guntchnigg <iuri@dbseller.com.br>
   * @package
   */
  class CensoCartorioRepository {
    
    /**
     * Collection de CensoCartorio
     * @var array
     */
    private $aCartorios = array();
    
    /**
     * Instancia da classe
     * @var CensoCartorioRepository
     */
    private static $oInstance;
    
    private function __construct() {
      
    }
    private function __clone() {
     
    }
 
    /**
     * Retorno uma instancia do CensoCartorio pelo Codigo
     * @param integer $iCodigo Codigo do CensoCartorio
     * @return CensoCartorio
     */
    public static function getCensoCartorioByCodigo($iCartorio) {
      
      if (!array_key_exists($iCartorio, CensoCartorioRepository::getInstance()->aCartorios)) {
        CensoCartorioRepository::getInstance()->aCartorios[$iCartorio] = new CensoCartorio($iCartorio);
      }
      return CensoCartorioRepository::getInstance()->aCartorios[$iCartorio];
    }
    
    /**
     * Retorna a instancia da classe
     * @return CensoCartorioRepository
     */
    protected static function getInstance() {
      
      if (self::$oInstance == null) {
              
        self::$oInstance = new CensoCartorioRepository();
      }
      return self::$oInstance;
    }
    
    /**
     * Adiciona um CensoCartorio dao repositorio
     * @param CensoCartorio $oCensoCartorio Instancia do CensoCartorio
     * @return boolean
     */
    public static function adicionarCensoCartorio(CensoCartorio $oCensoCartorio) {
    
      if(!array_key_exists($oCensoCartorio->getCodigo(), CensoCartorioRepository::getInstance()->aCartorios)) {
        CensoCartorioRepository::getInstance()->aCartorios[$oCensoCartorio->getCodigo()] = $oCensoCartorio;
      }
      return true;
    }
    
    /**
     * Remove o CensoCartorio passado como parametro do repository
     * @param CensoCartorio $oCensoCartorio
     * @return boolean
     */
    public static function removerCensoCartorio(CensoCartorio $oCensoCartorio) {
       /**
        *
        */
      if (array_key_exists($oCensoCartorio->getCodigo(), CensoCartorioRepository::getInstance()->aCartorios)) {
        unset(CensoCartorioRepository::getInstance()->aCartorios[$oCensoCartorio->getCodigo()]);
      }
      return true;
    }
    
    /**
     * Retorna o total de cidadoes existentes no repositorio;
     * @return integer;
     */
    public static function getTotalCensoCartorio() {
      return count(CensoCartorioRepository::getInstance()->aCartorios);
    }
  }
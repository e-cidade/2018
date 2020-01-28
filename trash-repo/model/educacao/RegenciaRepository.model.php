<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
   * Classe repository para classes Regencia
   * @author Iuri Guntchnigg <iuri@dbseller.com.br>
   * @package
   */
  class RegenciaRepository {

    /**
     * Collection de Regencia
     * @var array
     */
    private $aRegencia = array();

    /**
     * Instancia da classe
     * @var RegenciaRepository
     */
    private static $oInstance;

    private function __construct() {

    }
    private function __clone() {

    }

    /**
     * Retorno uma instancia do Regencia pelo Codigo
     * @param integer $iCodigo Codigo do Regencia
     * @return Regencia
     */
    public static function getRegenciaByCodigo($iCodigoRegencia) {

      if (!array_key_exists($iCodigoRegencia, RegenciaRepository::getInstance()->aRegencia)) {
        RegenciaRepository::getInstance()->aRegencia[$iCodigoRegencia] = new Regencia($iCodigoRegencia);
      }
      return RegenciaRepository::getInstance()->aRegencia[$iCodigoRegencia];
    }

    /**
     * Retorna a instancia da classe
     * @return RegenciaRepository
     */
    protected static function getInstance() {

      if (self::$oInstance == null) {

        self::$oInstance = new RegenciaRepository();
      }
      return self::$oInstance;
    }

    /**
     * Adiciona um Regencia ao repositorio
     * @param Regencia $oRegencia Instancia do Regencia
     * @return boolean
     */
    public static function adicionarRegencia(Regencia $oRegencia) {

      if(!array_key_exists($oRegencia->getCodigo(), RegenciaRepository::getInstance()->aRegencia)) {
        RegenciaRepository::getInstance()->aRegencia[$oRegencia->getCodigo()] = $oRegencia;
      }
      return true;
    }

    /**
     * Remove o Regencia passado como parametro do repository
     * @param Regencia $oRegencia
     * @return boolean
     */
    public static function removerRegencia(Regencia $oRegencia) {
       /**
        *
        */
      if (array_key_exists($oRegencia->getCodigo(), RegenciaRepository::getInstance()->aRegencia)) {
        unset(RegenciaRepository::getInstance()->aRegencia[$oRegencia->getCodigo()]);
      }
      return true;
    }

    /**
     * Retorna o total de cidadoes existentes no repositorio;
     * @return integer;
     */
    public static function getTotalRegencia() {
      return count(RegenciaRepository::getInstance()->aRegencia);
    }
  }
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
   * Classe repository para classes TipoFamiliar
   * @author Iuri Guntchnigg <iuri@dbseller.com.br>
   * @package
   */
  class TipoFamiliarRepository {

    /**
     * Collection de TipoFamiliar
     * @var array
     */
    private $aTipos = array();

    /**
     * Instancia da classe
     * @var TipoFamiliarRepository
     */
    private static $oInstance;

    private function __construct() {

    }
    private function __clone() {

    }

    /**
     * Retorno uma instancia do TipoFamiliar pelo Codigo
     * @param integer $iCodigo Codigo do TipoFamiliar
     * @return TipoFamiliar
     */
    public static function getTipoFamiliarByCodigo($iCodigoTipoFamiliar) {

      if (!array_key_exists($iCodigoTipoFamiliar, TipoFamiliarRepository::getInstance()->aTipos)) {
        TipoFamiliarRepository::getInstance()->aTipos[$iCodigoTipoFamiliar] = new TipoFamiliar($iCodigoTipoFamiliar);
      }
      return TipoFamiliarRepository::getInstance()->aTipos[$iCodigoTipoFamiliar];
    }

    /**
     * Retorna a instancia da classe
     * @return TipoFamiliarRepository
     */
    protected static function getInstance() {

      if (self::$oInstance == null) {

        self::$oInstance = new TipoFamiliarRepository();
      }
      return self::$oInstance;
    }

    /**
     * Adiciona um TipoFamiliar dao repositorio
     * @param TipoFamiliar $oTipoFamiliar Instancia do TipoFamiliar
     * @return boolean
     */
    public static function adicionarTipoFamiliar(TipoFamiliar $oTipoFamiliar) {

      if(!array_key_exists($oTipoFamiliar->getCodigo(), TipoFamiliarRepository::getInstance()->aTipos)) {
        TipoFamiliarRepository::getInstance()->aTipos[$oTipoFamiliar->getCodigo()] = $oTipoFamiliar;
      }
      return true;
    }

    /**
     * Remove o TipoFamiliar passado como parametro do repository
     * @param TipoFamiliar $oTipoFamiliar
     * @return boolean
     */
    public static function removerTipoFamiliar(TipoFamiliar $oTipoFamiliar) {
       /**
        *
        */
      if (array_key_exists($oTipoFamiliar->getCodigo(), TipoFamiliarRepository::getInstance()->aTipos)) {
        unset(TipoFamiliarRepository::getInstance()->aTipos[$oTipoFamiliar->getCodigo()]);
      }
      return true;
    }

    /**
     * Retorna o total de cidadoes existentes no repositorio;
     * @return integer;
     */
    public static function getTotalTipoFamiliar() {
      return count(TipoFamiliarRepository::getInstance()->aTipos);
    }
  }
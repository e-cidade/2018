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
   * Classe repository para classes SistemaConta
   * @author Iuri Guntchnigg <iuri@dbseller.com.br>
   * @package
   */
  class SistemaContaRepository {

    /**
     * Collection de SistemaConta
     * @var array
     */
    private $aInstancias = array();

    /**
     * Instancia da classe
     * @var SistemaContaRepository
     */
    private static $oInstance;

    private function __construct() {

    }
    private function __clone() {

    }

    /**
     * Retorno uma instancia do SistemaConta pelo Codigo
     * @param integer $iCodigo Codigo do SistemaConta
     * @return SistemaConta
     */
    public static function getSistemaContaByCodigo($iCodigoSistemaConta) {

      if (!array_key_exists($iCodigoSistemaConta, SistemaContaRepository::getInstance()->aInstancias)) {
        SistemaContaRepository::getInstance()->aInstancias[$iCodigoSistemaConta] = new SistemaConta($iCodigoSistemaConta);
      }
      return SistemaContaRepository::getInstance()->aInstancias[$iCodigoSistemaConta];
    }

    /**
     * Retorna a instancia da classe
     * @return SistemaContaRepository
     */
    protected static function getInstance() {

      if (self::$oInstance == null) {

        self::$oInstance = new SistemaContaRepository();
      }
      return self::$oInstance;
    }

    /**
     * Adiciona um SistemaConta dao repositorio
     * @param SistemaConta $oSistemaConta Instancia do SistemaConta
     * @return boolean
     */
    public static function adicionarSistemaConta(SistemaConta $oSistemaConta) {

      if (!array_key_exists($oSistemaConta->getCodigoSistemaConta(),
                           SistemaContaRepository::getInstance()->aInstancias)) {
        SistemaContaRepository::getInstance()->aInstancias[$oSistemaConta->getCodigoSistemaConta()] = $oSistemaConta;
      }
      return true;
    }

    /**
     * Remove o SistemaConta passado como parametro do repository
     * @param SistemaConta $oSistemaConta
     * @return boolean
     */
    public static function removerSistemaConta(SistemaConta $oSistemaConta) {
       /**
        *
        */
      if (array_key_exists($oSistemaConta->getCodigoSistemaConta(),
                           SistemaContaRepository::getInstance()->aInstancias)) {
        unset(SistemaContaRepository::getInstance()->aInstancias[$oSistemaConta->getCodigoSistemaConta()]);
      }
      return true;
    }

    /**
     * Retorna o total de cidadoes existentes no repositorio;
     * @return integer;
     */
    public static function getTotalSistemaConta() {
      return count(SistemaContaRepository::getInstance()->aInstancias);
    }
  }
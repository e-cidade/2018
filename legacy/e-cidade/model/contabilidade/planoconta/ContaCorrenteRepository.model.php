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
   * Classe repository para classes ContaCorrente
   * @author Iuri Guntchnigg <iuri@dbseller.com.br>
   * @package contabilidade
   * @subpackage planoconta
   */
  class ContaCorrenteRepository {

    /**
     * Collection de ContaCorrente
     * @var array
     */
    private $aContaCorrente = array();

    /**
     * Instancia da classe
     * @var ContaCorrenteRepository
     */
    private static $oInstance;

    private function __construct() {

    }
    private function __clone() {

    }

    /**
     * Retorno uma instancia do ContaCorrente pelo Codigo
     * @param integer $iCodigo Codigo do ContaCorrente
     * @return ContaCorrente
     */
    public static function getContaCorrenteByCodigo($iCodigoContaCorrente) {

      if (!array_key_exists($iCodigoContaCorrente, ContaCorrenteRepository::getInstance()->aContaCorrente)) {
        ContaCorrenteRepository::getInstance()->aContaCorrente[$iCodigoContaCorrente] = new ContaCorrente($iCodigoContaCorrente);
      }
      return ContaCorrenteRepository::getInstance()->aContaCorrente[$iCodigoContaCorrente];
    }

    /**
     * Retorna a instancia da classe
     * @return ContaCorrenteRepository
     */
    protected static function getInstance() {

      if (self::$oInstance == null) {

        self::$oInstance = new ContaCorrenteRepository();
      }
      return self::$oInstance;
    }

    /**
     * Adiciona um ContaCorrente dao repositorio
     * @param ContaCorrente $oContaCorrente Instancia do ContaCorrente
     * @return boolean
     */
    public static function adicionarContaCorrente(ContaCorrente $oContaCorrente) {

      if(!array_key_exists($oContaCorrente->getCodigo(), ContaCorrenteRepository::getInstance()->aContaCorrente)) {
        ContaCorrenteRepository::getInstance()->aContaCorrente[$oContaCorrente->getCodigo()] = $oContaCorrente;
      }
      return true;
    }

    /**
     * Remove o ContaCorrente passado como parametro do repository
     * @param ContaCorrente $oContaCorrente
     * @return boolean
     */
    public static function removerContaCorrente(ContaCorrente $oContaCorrente) {
       /**
        *
        */
      if (array_key_exists($oContaCorrente->getCodigo(), ContaCorrenteRepository::getInstance()->aContaCorrente)) {
        unset(ContaCorrenteRepository::getInstance()->aContaCorrente[$oContaCorrente->getCodigo()]);
      }
      return true;
    }

    /**
     * Retorna o total de cidadoes existentes no repositorio;
     * @return integer;
     */
    public static function getTotalContaCorrente() {
      return count(ContaCorrenteRepository::getInstance()->aContaCorrente);
    }
  }
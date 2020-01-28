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
   * Classe repository para classes ProgressaoParcialParametro
   * @author Iuri Guntchnigg <iuri@dbseller.com.br>
   * @package
   */
  class ProgressaoParcialParametroRepository {

    /**
     * Collection de ProgressaoParcialParametro
     * @var array
     */
    private $aProgressaoParcial = array();

    /**
     * Instancia da classe
     * @var ProgressaoParcialParametroRepository
     */
    private static $oInstance;

    private function __construct() {

    }
    private function __clone() {

    }

    /**
     * Retorno uma instancia do ProgressaoParcialParametro pelo Codigo
     * @param integer $iEscola Codigo da escola da que controla a progressao.
     * @return ProgressaoParcialParametro
     */
    public static function getProgressaoParcialParametroByCodigo($iEscola) {

      if (!array_key_exists($iEscola,
                            ProgressaoParcialParametroRepository::getInstance()->aProgressaoParcial)) {
        ProgressaoParcialParametroRepository::getInstance()->
        aProgressaoParcial[$iEscola] = new ProgressaoParcialParametro($iEscola);
      }
      return ProgressaoParcialParametroRepository::getInstance()->aProgressaoParcial[$iEscola];
    }

    /**
     * Retorna a instancia da classe
     * @return ProgressaoParcialParametroRepository
     */
    protected static function getInstance() {

      if (self::$oInstance == null) {

        self::$oInstance = new ProgressaoParcialParametroRepository();
      }
      return self::$oInstance;
    }

    /**
     * Adiciona um ProgressaoParcialParametro dao repositorio
     * @param ProgressaoParcialParametro $oProgressaoParcialParametro Instancia do ProgressaoParcialParametro
     * @return boolean
     */
    public static function adicionarProgressaoParcialParametro(ProgressaoParcialParametro
                                                               $oProgressaoParcialParametro) {

      if(!array_key_exists($oProgressaoParcialParametro->getEscola(),
          ProgressaoParcialParametroRepository::getInstance()->aProgressaoParcial)) {
        ProgressaoParcialParametroRepository::getInstance()->
        aProgressaoParcial[$oProgressaoParcialParametro->getEscola()] = $oProgressaoParcialParametro;
      }
      return true;
    }

    /**
     * Remove o ProgressaoParcialParametro passado como parametro do repository
     * @param ProgressaoParcialParametro $oProgressaoParcialParametro
     * @return boolean
     */
    public static function removerProgressaoParcialParametro(ProgressaoParcialParametro $oProgressaoParcialParametro) {

      if (array_key_exists($oProgressaoParcialParametro->getEscola(),
        ProgressaoParcialParametroRepository::getInstance()->aProgressaoParcial)) {
        unset(ProgressaoParcialParametroRepository::getInstance()->
              aProgressaoParcial[$oProgressaoParcialParametro->getEscola()]);
      }
      return true;
    }

    /**
     * Retorna o total de cidadoes existentes no repositorio;
     * @return integer;
     */
    public static function getTotalProgressaoParcialParametro() {
      return count(ProgressaoParcialParametroRepository::getInstance()->aProgressaoParcial);
    }
  }
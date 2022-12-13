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
   * Classe repository para classes Ensino
   * @author  Fábio Esteves <fabio.esteves@dbseller.com.br>
   * @package educacao
   */
  class EnsinoRepository {

    /**
     * Coleção de Ensino
     * @var array
     */
    private $aEnsino = array();

    /**
     * Instancia da classe
     * @var EnsinoRepository
     */
    private static $oInstance;

    private function __construct() {}
    private function __clone() {}

    /**
     * Retorna a instancia da classe
     * @return EnsinoRepository
     */
    protected static function getInstance() {
    
      if ( self::$oInstance == null ) {
        self::$oInstance = new EnsinoRepository();
      }
      return self::$oInstance;
    }
    
    /**
     * Retorno uma instancia do Ensino pelo Codigo
     * @param  integer $iCodigo - Codigo do Ensino
     * @return Ensino
     */
    public static function getEnsinoByCodigo( $iCodigo ) {

      if ( !array_key_exists( $iCodigo, EnsinoRepository::getInstance()->aEnsino ) ) {
        EnsinoRepository::getInstance()->aEnsino[$iCodigo] = new Ensino( $iCodigo );
      }
      return EnsinoRepository::getInstance()->aEnsino[$iCodigo];
    }

    /**
     * Adiciona um Ensino ao repositorio
     * @param  Ensino $oEnsino - Instancia do Ensino
     * @return boolean
     */
    public static function adicionarEnsino( Ensino $oEnsino ) {

      if( !array_key_exists( $oEnsino->getCodigo(), EnsinoRepository::getInstance()->aEnsino ) ) {
        EnsinoRepository::getInstance()->aEnsino[$oEnsino->getCodigo()] = $oEnsino;
      }
      return true;
    }

    /**
     * Remove o Ensino passado como parametro do repository
     * @param  Ensino $oEnsino
     * @return boolean
     */
    public static function removerEnsino( Ensino $oEnsino ) {
      
      if ( array_key_exists( $oEnsino->getCodigo(), EnsinoRepository::getInstance()->aEnsino ) ) {
        unset(EnsinoRepository::getInstance()->aEnsino[$oEnsino->getCodigo()]);
      }
      return true;
    }
    
    /**
     * Retorna um array de instâncias de Ensino
     * @return Ensino[]
     */
    public static function getEnsinos() {
      
      $oDaoEnsino   = new cl_ensino();
      $sSqlEnsino   = $oDaoEnsino->sql_query_file( null, "ed10_i_codigo" );
      $rsEnsino     = db_query( $sSqlEnsino );
      $iTotalEnsino = pg_num_rows( $rsEnsino );
      
      if ( $rsEnsino && $iTotalEnsino > 0 ) {
        
        for ( $iContador = 0; $iContador < $iTotalEnsino; $iContador++ ) {
          
          $iEnsino = db_utils::fieldsMemory( $rsEnsino, $iContador )->ed10_i_codigo;
          EnsinoRepository::adicionarEnsino( new Ensino( $iEnsino ) );
        }
      }
      
      return EnsinoRepository::getInstance()->aEnsino;
    }

    /**
     * Retorna um array de instâncias de Esinos Infantis
     * @return EnsinoInfantil[]
     */
    public static function getEnsinosInfantil() {

      $aEnsinosInfantil = array();

      foreach (EnsinoRepository::getEnsinos() as $oEnsino) {
        
        if ($oEnsino->isInfantil()) {
          $aEnsinosInfantil[] = $oEnsino;
        }
      }

      return $aEnsinosInfantil;
    }
  }
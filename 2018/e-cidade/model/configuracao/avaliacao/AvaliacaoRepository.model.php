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
   * Classe repository para classes Avaliacao
   * @author Iuri Guntchnigg <iuri@dbseller.com.br>
   * @package 
   */
  class AvaliacaoRepository {
    
    /** 
     * Collection de Avaliacao 
     * @var array
     */    
    private $aAvaliacao = array();
    
    /**
     * Instancia da classe 
     * @var AvaliacaoRepository
     */
    private static $oInstance;
    
    private function __construct() {
      
    }
    private function __clone() {
     
    }
 
    /**
     * Retorno uma instancia do Avaliacao pelo Codigo
     * @param integer $iCodigo Codigo do Avaliacao
     * @return Avaliacao
     */
    public static function getAvaliacaoByCodigo($iCodigoAvaliacao) {
      
      if (!array_key_exists($iCodigoAvaliacao, AvaliacaoRepository::getInstance()->aAvaliacao)) {
        AvaliacaoRepository::getInstance()->aAvaliacao[$iCodigoAvaliacao] = new Avaliacao($iCodigoAvaliacao);
      }
      return AvaliacaoRepository::getInstance()->aAvaliacao[$iCodigoAvaliacao];
    } 
    
    /**
     * Retorna a instancia da classe
     * @return AvaliacaoRepository
     */
    protected static function getInstance() {
      
      if (self::$oInstance == null) {
              
        self::$oInstance = new AvaliacaoRepository();     
      }
      return self::$oInstance;
    }
    
    /**
     * Adiciona um Avaliacao dao repositorio
     * @param Avaliacao $oAvaliacao Instancia do Avaliacao
     * @return boolean
     */
    public static function adicionarAvaliacao(Avaliacao $oAvaliacao) {
    
      if(!array_key_exists($oAvaliacao->getAvaliacao(), AvaliacaoRepository::getInstance()->aAvaliacao)) {
        AvaliacaoRepository::getInstance()->aAvaliacao[$oAvaliacao->getAvaliacao()] = $oAvaliacao;
      }
      return true;
    }
    
    /**
     * Remove o Avaliacao passado como parametro do repository
     * @param Avaliacao $oAvaliacao
     * @return boolean 
     */ 
    public static function removerAvaliacao(Avaliacao $oAvaliacao) {
       /**
        * 
        */ 
      if (array_key_exists($oAvaliacao->getAvaliacao(), AvaliacaoRepository::getInstance()->aAvaliacao)) {
        unset(AvaliacaoRepository::getInstance()->aAvaliacao[$oAvaliacao->getAvaliacao()]);
      }
      return true;
    }
    
    /** 
     * Retorna o total de cidadoes existentes no repositorio; 
     * @return integer; 
     */
    public static function getTotalAvaliacao() {
      return count(AvaliacaoRepository::getInstance()->aAvaliacao);
    }
    
    /**
     * Retorna avaliacao atravez de seu identificador
     * @param string $sIdentificador identificador da avaliacao
     * @return Avaliacao
     */
    public static function getAvaliacaoByIdentificador($sIdentificador) {
      
       $oDaoAvaliacao = db_utils::getDao("avaliacao");
       $sSqlAvaliacao = $oDaoAvaliacao->sql_query_file(null, 
                                                       "db101_sequencial", 
                                                       null, 
                                                       "db101_identificador = '{$sIdentificador}'"
                                                      );
      $rsAvaliacao = $oDaoAvaliacao->sql_record ($sSqlAvaliacao);
      if ($oDaoAvaliacao->numrows == 1) {
        
        $iCodigoAvaliacao = db_utils::fieldsMemory($rsAvaliacao, 0)->db101_sequencial;
        return AvaliacaoRepository::getAvaliacaoByCodigo($iCodigoAvaliacao);
      }  
      return false;
    }
  }
?>
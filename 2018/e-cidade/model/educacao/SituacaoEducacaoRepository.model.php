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
   * Classe repository para classes SituacaoEducacao
   * @author Iuri Guntchnigg <iuri@dbseller.com.br>
   * @package
   */
  class SituacaoEducacaoRepository {
    
    /**
     * Collection de SituacaoEducacao
     * @var array
     */
    private $aSituacaoEducacao = array();
    
    /**
     * Instancia da classe
     * @var SituacaoEducacaoRepository
     */
    private static $oInstance;
    
    private function __construct() {
      
    }
    private function __clone() {
     
    }
 
    /**
     * Retorno uma instancia do SituacaoEducacao pelo Codigo
     * @param integer $iCodigo Codigo do SituacaoEducacao
     * @return SituacaoEducacao
     */
    public static function getSituacaoEducacaoByCodigo($iCodigoSituacaoEducacao) {
      
      if (!array_key_exists($iCodigoSituacaoEducacao, SituacaoEducacaoRepository::getInstance()->aSituacaoEducacao)) {
        SituacaoEducacaoRepository::getInstance()->aSituacaoEducacao[$iCodigoSituacaoEducacao] = new SituacaoEducacao($iCodigoSituacaoEducacao);
      }
      return SituacaoEducacaoRepository::getInstance()->aSituacaoEducacao[$iCodigoSituacaoEducacao];
    }
    
    /**
     * Retorna a instancia da classe
     * @return SituacaoEducacaoRepository
     */
    protected static function getInstance() {
      
      if (self::$oInstance == null) {
              
        self::$oInstance = new SituacaoEducacaoRepository();
      }
      return self::$oInstance;
    }
    
    /**
     * Adiciona um SituacaoEducacao dao repositorio
     * @param SituacaoEducacao $oSituacaoEducacao Instancia do SituacaoEducacao
     * @return boolean
     */
    public static function adicionarSituacaoEducacao(SituacaoEducacao $oSituacaoEducacao) {
    
      if (!array_key_exists($oSituacaoEducacao->getCodigo(), SituacaoEducacaoRepository::getInstance()->aSituacaoEducacao)) {
        SituacaoEducacaoRepository::getInstance()->aSituacaoEducacao[$oSituacaoEducacao->getCodigo()] = $oSituacaoEducacao;
      }
      return true;
    }
    
    /**
     * Remove o SituacaoEducacao passado como parametro do repository
     * @param SituacaoEducacao $oSituacaoEducacao
     * @return boolean
     */
    public static function removerSituacaoEducacao(SituacaoEducacao $oSituacaoEducacao) {
       /**
        *
        */
      if (array_key_exists($oSituacaoEducacao->getCodigo(), SituacaoEducacaoRepository::getInstance()->aSituacaoEducacao)) {
        unset(SituacaoEducacaoRepository::getInstance()->aSituacaoEducacao[$oSituacaoEducacao->getCodigo()]);
      }
      return true;
    }
    
    /**
     * Retorna o total de cidadoes existentes no repositorio;
     * @return integer;
     */
    public static function getTotalSituacaoEducacao() {
      return count(SituacaoEducacaoRepository::getInstance()->aSituacaoEducacao);
    }
  }
?>
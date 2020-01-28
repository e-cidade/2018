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
 * Classe Singletown para verificação de configurações de desdobramento do patrimônio
 * @author vinicius.silva@dbseller.com.br
 * @package patrimonio
 */

class ConfiguracaoDesdobramentoPatrimonio {
  
  static $oInstance;
  
  protected $aListaDesdobramentos = array();
  
  /**
   * Método construtor
   */
  protected function __construct() {
    
    $oDaoConfiguracaoDesdobramentoPatrimonio = db_utils::getDao('configuracaodesdobramentopatrimonio');
    $sSqlBuscaDesdobramentos                 = $oDaoConfiguracaoDesdobramentoPatrimonio->sql_query_file();
    $rsBuscaDesdobramentos                   = $oDaoConfiguracaoDesdobramentoPatrimonio->sql_record($sSqlBuscaDesdobramentos);
    $this->aListaDesdobramentos[]            = db_utils::getCollectionByRecord($rsBuscaDesdobramentos);
  }
  
  /**
   * Retorna a instância da classe
   * @return ConfiguracaoDesdobramentoPatrimonio
   */
  protected function getInstance() {
    
    if (self::$oInstance == null) {
      self::$oInstance = new ConfiguracaoDesdobramentoPatrimonio();
    }
    return self::$oInstance;
  }
  
  /**
   * Verifica se o elemento passado no parâmetro possui configuração de desdobramento
   * @param integer $iCodigoElemento codido do elemento
   * @return mixed retorna objeto da configuração, ou false se não houver a configuração
   */
  public function getConfiguracaoElemento($iCodigoElemento) {
    
    $aDesdobramentos = self::getInstance()->aListaDesdobramentos;
    $mRetorno        = false;
    
    foreach ($aDesdobramentos as $aDesdobramento) {
      
      foreach ($aDesdobramento as $oDesdobramento) {
        
        if ($oDesdobramento->e135_desdobramento == $iCodigoElemento) {
          
          $mRetorno = $oDesdobramento;
          break;
        }
      }
    }
    return $mRetorno;
  }
}
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
 * Classe os dados do imovel do bem 
 */
final class BemDadosImovel {
  
  protected $iBem;
  
  protected $iIdBql;
  
  protected $sObservacao;
  
 /**
   * 
   */
  function __construct($iBem = null) {
    
    if (!empty($iBem)) {
      
      $oDaoBensImoveis = db_utils::getDao("bensimoveis");
      $sSqlDados       = $oDaoBensImoveis->sql_query_file($iBem);
      $rsDados         = $oDaoBensImoveis->sql_record($sSqlDados);
      if ($oDaoBensImoveis->numrows > 0) {
        
        $this->iBem   = $iBem;
        $oDadosImovel = db_utils::fieldsMemory($rsDados, 0);
        $this->setObservacao($oDadosImovel->t54_obs);
        $this->setIdBql($oDadosImovel->t54_idbql);
      }
    }
  }
   
  /**
   * @return unknown
   */
  public function getBem() {
    return $this->iBem;
  }
  
  /**
   * Cуdigo do setor/quadro/lote
   * @return Integer
   */
  public function getIdBql() {
    return $this->iIdBql;
  }
  
  /**
   * @return string
   */
  public function getObservacao() {
    return $this->sObservacao;
  }
  
  public function setBem($iBem) {
    $this->iBem = $iBem;
  }
  /**
   * Define a setor/quadra/lote do Imovel
   * @param integer $iIdBql
   */
  public function setIdBql($iIdBql) {
    $this->iIdBql = $iIdBql;
  }
  
  /**
   * @param string $sObservacao
   */
  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
  }

  /**
   * Persiste os dados na base de dados
   */
  public function salvar() {
    
    if (!db_utils::inTransaction()) {
      throw new Exception('Sem transaзгo ativa com o banco de dados.1111');
    }
    
    if (!empty($this->iBem)) {
      
      /**
       * excluimos o registro e incluim  
       */   
      $this->remover();
    }
    if (!empty($this->iIdBql)) {

      $oDaoBensImoveis             = db_utils::getDao("bensimoveis");
      $oDaoBensImoveis->t54_codbem = $this->iBem;
      $oDaoBensImoveis->t54_idbql  = $this->iIdBql;
      $oDaoBensImoveis->t54_obs    = $this->sObservacao;
      $oDaoBensImoveis->incluir($this->iBem, $this->iIdBql);
      if ($oDaoBensImoveis->erro_status == 0) {
        throw new Exception("Erro ao salvar dados do imуvel do bem.\n{$oDaoBensImoveis->erro_msg}");
      }
    }
  }
  
  /**
   * remove o vinculo dos dado do imovel
   */
  public function remover() {
    
    if (!empty($this->iBem)) {
      
      $oDaoBensImoveis = db_utils::getDao("bensimoveis");
      $oDaoBensImoveis->excluir($this->iBem);
      if ($oDaoBensImoveis->erro_status == 0) {
        throw new Exception('Erro ao excluir dados do imovel do bem;');
      }
    }
  }
}

?>
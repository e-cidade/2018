<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
  * Cria Regras de Rateio para os criteios 
  * @version $Author: dbiuri $  -  $Revision: 1.1 $ 
  * @package Custos
  */
final class custoRegraRateio {
  
  /**
   * Código da regra
   *
   * @var integer
   */
  private $iCodigoRegra = 0;
  
  /**
   * Codigo do plano de contas de custos 
   *
   * @var integer
   */
  private $iContaPlano = 0;
  
  /**
   * Quantidade
   *
   * @var integer
   */
  private $iQuantidade = 0;
  
  /**
   * Percentual da regra em relacao a quantidade total da regra.
   *
   * @var floar
   */
  private $nPercentual = 0;
   
  /**
   * Quantidade do plano de contas
   *
   * @param integer $iCodigoRegra 
   */
  function __construct( $iCodigoRateio, $iCodigoRegra = null) {

     if ($iCodigoRateio != null) {
       $this->iCodigoRateio = $iCodigoRateio;
     }
     if ($iCodigoRegra != null) {
       $this->iCodigoRegra = $iCodigoRegra;
     }
  }
  
  /**
   * @return integer
   */
  public function getCodigoRegra() {

    return $this->iCodigoRegra;
  }
  
  /**
   * @return integer
   */
  public function getContaPlano() {

    return $this->iContaPlano;
  }
  
  /**
   * @param integer $iContaPlano
   */
  public function setContaPlano($iContaPlano) {

    $this->iContaPlano = $iContaPlano;
  }
  
  /**
   * @return integer
   */
  public function getQuantidade() {

    return $this->iQuantidade;
  }
  
  /**
   * @param integer $iQuantidade
   */
  public function setQuantidade($iQuantidade) {
    $this->iQuantidade = $iQuantidade;
  }
  
  /**
   * @return float
   */
  public function getPercentual() {

    return $this->nPercentual;
  }

  public function setPercentual($nValor) {
    $this->nPercentual = $nValor;
  }
  public function save() {
    
    $oDaoCriterioRegra = db_utils::getDao("custoplanoanaliticacriteriorateio");
    if (empty($this->iCodigoRateio)) {
      throw new Exception("Código do criterio não informado", 1);
    }
    
    $oDaoCriterioRegra->cc07_automatico          = "false";
    $oDaoCriterioRegra->cc07_percentual          = $this->getPercentual();
    $oDaoCriterioRegra->cc07_custocriteriorateio = $this->iCodigoRateio;
    $oDaoCriterioRegra->cc07_custoplanoanalitica = $this->getContaPlano();
    $oDaoCriterioRegra->cc07_quantidade          = $this->getQuantidade();
    if ($this->getCodigoRegra() != null) {

      $oDaoCriterioRegra->cc07_sequencial = $this->getCodigoRegra();
      $oDaoCriterioRegra->alterar($this->getCodigoRegra());
      
    } else {
      
       $oDaoCriterioRegra->incluir(null);
       $this->iCodigoRegra  = $oDaoCriterioRegra->cc07_sequencial;
    }
    if ($oDaoCriterioRegra->erro_status == 0) {
      throw new Exception($oDaoCriterioRegra->erro_msg, 2);
    }
    return true;
  }
  
}
?>
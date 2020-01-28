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


require_once("model/custoRegraRateio.model.php");
/**
  * cria criterios para reateio de valores nos planos de custos
  * @version $Author: dbiuri $  -  $Revision: 1.4 $ 
  * @package Custos
  */
class custorateio {
  
  /**
   * Código do rateio
   * @var integer
   */   
  private $iCodigoRegraRateio = 0;

  /**
   * observações do Criterio
   * @var string
   */
  private $sObservacao = null;
  
  /**
   * Rateio está ativo
   *
   * @var boolean
   */
  private $lAtivo  = true;
  
  /**
   * Descrição do Criterio
   * @var string
   */
  private $sDescricao = null;
  
  /**
   * Código do departamento do Criterio
   *
   * @var integer
   */
  private $iCodigoDepartamento = 0;
  
  /**
   * instituição do rateio 
   *
   * @var integer
   */
  private $iInstituicao        = 0;
   
  /**
   * Daoda tabela criterio rateio
   *
   * @var object
   */
  private $oDaoCriterio   = null;
  
  /**
   * Regras de Rateio definidas para o criterio;
   *
   * @var array
   */
  
  private $aRegrasRaterio = array();
  /**
   * Criterio de Raterio 
   *
   * @param integer $iCodigoRegraRateio Código do rateio
   */
  function __construct($iCodigoRegraRateio) {
      
    if (empty($iCodigoRegraRateio)) {
      throw new Exception('Código da regra de rateio não informada', 1);
    }
    $this->iCodigoRegraRateio = $iCodigoRegraRateio;
    $this->oDaoCriterio       = new cl_custocriteriorateio();
    $this->aRegrasRaterio     = $this->getRegrasRateio(); 
  }
  /**
   * @return integer
   */
  public function getCodigoDepartamento() {

    return $this->iCodigoDepartamento;
  }
  
  /**
   * @return integer
   */
  public function getCodigoRegraRateio() {

    return $this->iCodigoRegraRateio;
  }
  
  /**
   * @return integer
   */
  public function getInstituicao() {

    return $this->iInstituicao;
  }
  
  /**
   * @return boolean
   */
  public function isAtivo() {

    return $this->lAtivo;
  }
  
  /**
   * @return string
   */
  public function getsDescricao() {

    return $this->sDescricao;
  }
  
  /**
   * @return string
   */
  public function getsObservacao() {

    return $this->sObservacao;
  }
  
  /**
   * @param integer $iCodigoDepartamento
   */
  public function setCodigoDepartamento($iCodigoDepartamento) {

    $this->iCodigoDepartamento = $iCodigoDepartamento;
  }
  
  /**
   * @param integer $iCodigoRegraRateio
   */
  public function setCodigoRegraRateio($iCodigoRegraRateio) {

    $this->iCodigoRegraRateio = $iCodigoRegraRateio;
  }
  
  /**
   * @param integer $iInstituicao
   */
  public function setInstituicao($iInstituicao) {

    $this->iInstituicao = $iInstituicao;
  }
  
  /**
   * @param boolean $lAtivo
   */
  public function setAtivo($lAtivo) {

    $this->lAtivo = $lAtivo;
  }
  
  /**
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {

    $this->sDescricao = $sDescricao;
  }
  
  /**
   * @param string $sObservacao
   */
  public function setObservacao($sObservacao) {

    $this->sObservacao = $sObservacao;
  }

  /**
   * Define as regras para o rateio de valores
   *
   * @param custoRegraRateio $oReGraRateio
   * @return void
   */
  function addRegraRateio(custoRegraRateio $oRegraRateio) {

    foreach ($this->aRegrasRaterio as $oRegraAtual) {
      
      if ($oRegraAtual->getContaPlano() == $oRegraRateio->getContaPlano() 
         && ($oRegraAtual->getCodigoRegra() == "")) {
        throw new Exception("Conta já cadastrada para esse Criterio",3);
      }
    }
    $this->aRegrasRaterio[$oRegraRateio->getCodigoRegra()] = $oRegraRateio;
    return true;
    
  }
  /**
   * Persite as informações do Rateio na base de dadps
   *
   * @param boolean $lApenasRegras define se apenas salva as regras, ou modifica os dados do criterio
   * @return void
   */
  function save($lApenasRegras = false) {
    
    if ($lApenasRegras) {

      /**
       * Atualizamos apenas os valores das regras (percentuais)
       * conforme os criterios adicionados/removidos/atualizados
       */
      if (count($this->aRegrasRaterio) == 1) {
        
        reset($this->aRegrasRaterio);
        $this->aRegrasRaterio[key($this->aRegrasRaterio)]->setPercentual(100);
        $this->aRegrasRaterio[current($this->aRegrasRaterio)->getCodigoRegra()]->save();
         
      } else {

        $nQuantidade = 0;
        foreach ($this->aRegrasRaterio as $oRegraAtual) {
          $nQuantidade += $oRegraAtual->getQuantidade();
        }
        
        /**
         * Persistimos os novos percentuais
         */
        foreach ($this->aRegrasRaterio as $oRegraAtual) {

          $oRegraAtual->setPercentual(round((($oRegraAtual->getQuantidade()*100)/$nQuantidade)), 2);
          $oRegraAtual->save();
          
        }
      }
    }
    return true;
  }
  
  
  /**
   * Retorna as regras Cadastradas para o Criterio
   *
   * @return custoRegraRateio
   */
  function getRegrasRateio() {
    
    $oDaoCriterioRegra = db_utils::getDao("custoplanoanaliticacriteriorateio");
    $sSqlCriteriosAtualizar = $oDaoCriterioRegra->sql_query_file(null,
                                                                 "*",
                                                                 null,
                                                                 "cc07_custocriteriorateio = {$this->iCodigoRegraRateio}"
                                                                 );

    $rsCriteriosAtualizar  = $oDaoCriterioRegra->sql_record($sSqlCriteriosAtualizar);
    $aRegrasCadastradas    = array();
    if ($oDaoCriterioRegra->numrows > 0) {

      $aCriteriosAtualizar   = db_utils::getColectionByRecord($rsCriteriosAtualizar);
      foreach ($aCriteriosAtualizar as $oRegraCadastrada) {
        
        $oRegraCriterio = new custoRegraRateio($this->getCodigoRegraRateio(), $oRegraCadastrada->cc07_sequencial);
        $oRegraCriterio->setContaPlano($oRegraCadastrada->cc07_custoplanoanalitica);
        $oRegraCriterio->setQuantidade($oRegraCadastrada->cc07_quantidade);
        $oRegraCriterio->setPercentual($oRegraCadastrada->cc07_percentual);
        $aRegrasCadastradas[$oRegraCadastrada->cc07_sequencial] = $oRegraCriterio;
        
      }
    }
    return $aRegrasCadastradas;
  }
  
  function excluirRegraRateio($iCodigoRegraRateio) {
    
    $oDaoCriterioRegra = new cl_custoplanoanaliticacriteriorateio();
    if (!empty($iCodigoRegraRateio)) {
      
      $oDaoCriterioRegra->excluir($iCodigoRegraRateio);
      unset($this->aRegrasRaterio[$iCodigoRegraRateio]);
      $this->save(true);
    }
  }
  
  /**
   * Aplica as regras de rateio do Criterio na quantidade/Rateio Informados 
   *
   * @param float $nQuantidade Quantidade total
   * @param float $nValor      Valor total
   * 
   * @return stdClass com valores/quantidades separado por regra do criterio 
   */
  
  public function aplicarRegras($nQuantidade, $nValor) {
    
    $aRegras          = $this->getRegrasRateio();
    $nTotalQuantidade = 0;
    $nTotalValor      = 0;
    $aRateioRealizado = array();
    foreach ($aRegras as $oRegra) {
      
      $oRateio = new stdClass();
      $oRateio->iContaPlano = $oRegra->getContaPlano();
      
      $nQuantidadeRateio    = round(($oRegra->getPercentual()*$nQuantidade)/100, 2);
      $nTotalQuantidade    += $nQuantidadeRateio; 
      $oRateio->nQuantidade = $nQuantidadeRateio;
      $nValorRateio         = round(($oRegra->getPercentual()*$nValor)/100, 2);
      $nTotalValor         += $nValorRateio;
      $oRateio->nValor      = $nValorRateio; 
      $aRateioRealizado[]   = $oRateio;
    }
    
    return $aRateioRealizado;
  }
}

?>
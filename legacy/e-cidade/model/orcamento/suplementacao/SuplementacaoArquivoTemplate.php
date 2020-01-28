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
* Classe Singleton para verificar o nome do arquivo template da suplementacao.
* @author andrio.costa@dbseller.combr
* @package orcamento
* @subpackage suplementacao
*
*/
class SuplementacaoArquivoTemplate {

  static $oInstance;
   
  protected $aListaArquivos = array();

  /**
   * método construtor
   */
  protected function __construct() {  	
  	
    $this->aListaArquivos['1001'] = $this->getCreditoSuplementarReducao();
    $this->aListaArquivos['1002'] = $this->getCreditoSuplementarExcessoArrecadacao();
    $this->aListaArquivos['1003'] = $this->getCreditoSuplementarSuperavitFinanceiro();
    $this->aListaArquivos['1004'] = $this->getCreditoSuplementarExcessoArrecadacao();
    $this->aListaArquivos['1005'] = $this->getCreditoSuplementarExcessoArrecadacao();
    $this->aListaArquivos['1006'] = $this->getCreditoEspecialReducao();
    $this->aListaArquivos['1007'] = $this->getCreditoEspecialPorExcessoArrecadacao();
    $this->aListaArquivos['1008'] = $this->getCreditoEspecialSuperavitFinanceiro();
    $this->aListaArquivos['1009'] = $this->getCreditoEspecialPorExcessoArrecadacao();
    $this->aListaArquivos['1010'] = $this->getCreditoEspecialPorExcessoArrecadacao();    
    $this->aListaArquivos['1011'] = $this->getCreditoExtraordinario();    
    $this->aListaArquivos['1012'] = $this->getRemanejamento();
    $this->aListaArquivos['1013'] = $this->getReaberturaCreditoExtraordinarios();    
    $this->aListaArquivos['1014'] = $this->getTransferenciaRecursos();        
    $this->aListaArquivos['1015'] = $this->getRemanejamento();    
    $this->aListaArquivos['1016'] = $this->getRemanejamento();    
  }
  
  /**
   * Retorna a instancia da classe
   * @return SuplementacaoArquivoTemplate
   */
  protected function getInstance() {

    if (self::$oInstance == null) {
      self::$oInstance = new SuplementacaoArquivoTemplate();
    }
    return self::$oInstance;
  }

  /**
   * Verifica o arquivo template para tipo de suplementação passado no paramento 
   * @param integer $iTipoSuplementacao codigo do tipo de Suplementação
   * @return retorna array, ou false em caso de não encontrar
   */
  public function getNomeArquivo($iTipoSuplementacao) {
     
    $aArquivos = self::getInstance()->aListaArquivos;
    $mReturno  = false;
    
    if (array_key_exists($iTipoSuplementacao, $aArquivos)) {
      $mReturno =  $aArquivos[$iTipoSuplementacao];
    }
    return $mReturno;
  }

  private function getCreditoEspecialPorExcessoArrecadacao() {
    
    $aArray = array ("arquivo"=>"credito_especial_por_excesso_de_arrecadacao.agt", 
    								 "template" => array (20));
    return $aArray;
  }
  
  private function getCreditoEspecialReducao() {
    
    $aArray = array ("arquivo"=>"credito_especial_por_reducao.agt",
                     "template" => array (21));
    return $aArray;
  }
  
  private function getCreditoEspecialSuperavitFinanceiro() {
    
    $aArray = array ("arquivo"=>"credito_especial_por_superavit_financeiro.agt",
                     "template"=> array (22));
    return $aArray;
  }
  
  private function getCreditoSuplementarExcessoArrecadacao() {
  
    $aArray = array ("arquivo"=>"credito_suplementar_por_excesso_de_arrecadacao.agt",
                     "template" => array(23));
    return $aArray;
  }

  private function getCreditoSuplementarReducao() {
  
    $aArray = array ("arquivo"=>"credito_suplementar_por_reducao.agt",
                     "template"=>array(24));
    return $aArray;
  }
    
  private function getCreditoSuplementarSuperavitFinanceiro() {
  
    $aArray = array ("arquivo"=>"credito_suplementar_por_superavit_financeiro.agt",
                     "template" => array(25));
    return $aArray;
  }

  private function getRemanejamento() {
  
    $aArray = array ("arquivo"=>"remanejamento.agt",
                     "template" => array(26, 38, 41, 42) );
    return $aArray;
  }
  
  private function getCreditoExtraordinario() {
  
  	$aArray = array ("arquivo"=>"credito_suplementar_por_excesso_de_arrecadacao.agt",
  			"template" => array(37) );
  	return $aArray;
  }
    
  private function getReaberturaCreditoExtraordinarios() {
  	
  	$aArray = array ("arquivo"=>"credito_suplementar_por_excesso_de_arrecadacao.agt",
  									"template" => array(39) );
  	return $aArray;  	
  }
  
   private function getTransferenciaRecursos() {
   	
   	$aArray = array ("arquivo"=>"remanejamento.agt",
   									"template" => array(40) );
   	return $aArray;
   }
  
}
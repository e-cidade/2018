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
 * Classe para manipula��o de Nome da Classe
 *
 * @package Pessoal
 * @revision $Author: dbrenan.silva $
 * @version  $Revision: 1.1 $
 */

class FaixasIRRF {

  /**
   * Representa o valor de Inicio da faixa do IRRF
   */
  private $nValorInicio;

  /**
   * Representa o valor de Fim da faixa do IRRF
   */
  private $nValorFim;

  /**
   * Representa o valor de Percentual da faixa do IRRF
   */
  private $nValorPercentual;

  /**
   * Representa o valor de Dedu��o da faixa do IRRF
   */
  private $nValorDedu��o;


  /**
   * Define o Valor de In�cio da Faixa
   * @param Number
   */
  public function setInicio ($nValorInicio) {
    $this->nValorInicio = $nValorInicio;
  }
  
  /**
   * Retorna o Valor de In�cio da Faixa
   * @return Number
   */
  public function getInicio () {
    return $this->nValorInicio; 
  }


  /**
   * Define o Valor de Fim da Faixa
   * @param Number
   */
  public function setFim ($nValorFim) {
    $this->nValorFim = $nValorFim;
  }
  
  /**
   * Retorna o Valor de Fim da Faixa
   * @return Number
   */
  public function getFim () {
    return $this->nValorFim; 
  }
  
  /**
   * Define o valor do percentual de al�quota da faixa
   * @param Number
   */
  public function setPercentual ($nValorPercentual) {
    $this->nValorPercentual = $nValorPercentual;
  }
  
  /**
   * Retorna o valor do percentual de al�quota da faixa
   * @return Number
   */
  public function getPercentual () {
    return $this->nValorPercentual; 
  }
  
  /**
   * Define o valor da dedu��o do imposto da respectiva faixa
   * @param Number
   */
  public function setDeducao ($nValorDedu��o) {
    $this->nValorDedu��o = $nValorDedu��o;
  }
  
  /**
   * Retorna o valor da dedu��o do imposto da respectiva faixa
   * @return Number
   */
  public function getDeducao () {
    return $this->nValorDedu��o; 
  }
}
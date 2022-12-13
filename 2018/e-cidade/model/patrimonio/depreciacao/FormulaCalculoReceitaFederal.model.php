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


require_once ('model/patrimonio/depreciacao/interface/IFormulaCalculo.interface.php');

class FormulaCalculoReceitaFederal implements IFormulaCalculo {
  
  /**
   * Codigo do tipo de calculo
   * @var integer
   */
  protected $iTipoCalculo;
  
  /**
   * Valor da aquisicao
   * @var float
   */
  protected $nValorAquisicao;
  
  /**
   * Ano do calculo
   * @var integer
   */
  protected $iAnoCalculo;
  
  /**
   * valor atual do bem
   * @var float 
   */
  protected $nValorAtual;
  
  /**
   * Valor residual
   * @var float
   */
  protected $nValorResidual;
  
  /**
   * Valor depreciado
   * @var float
   */
  protected $nPercentualDepreciacao;
  
  /**
   * Vida util
   * @var integer
   */
  protected $iVidaUtil;
  
  /**
   * valor calculado para a depreciacao
   * @var float
   */
  protected $nValorDepreciado;
  
  /**
   * valor percentual calculado para a depreciacao
   * @var float
   */
  protected $nPercentualDepreciado;
  
  /**
   * Quantidade de meses no ano
   * @var integer
   */
  const NUMERO_MESES_ANO = 12;
  
  /**
   * Construtor
   */
  public function __construct() {
    $this->iTipoCalculo = 3;
  }
  
  /**
   * define a quantidade de anos que o bem  jс foi depreciado
   */
  public function setQuantidadeAnosCalculados($iAno) {
    $this->iAnoCalculo = $iAno;
  }
  
  public function setValorAquisicao($nValorAquisicao) {
    $this->nValorAquisicao = $nValorAquisicao;
  }
  
  public function setValorAtual($nValorAtual = 0) {
    $this->nValorAtual = $nValorAtual;
  }
  
  public function setPercentualDepreciacao($nPercentualDepreciacao = 0) {
    $this->nPercentualDepreciacao = $nPercentualDepreciacao;
  }
  
  public function setValorResidual($nValorResidual = 0) {
    $this->nValorResidual = $nValorResidual;
  }
  
  public function setVidaUtil($iVidaUtil) {
    $this->iVidaUtil = $iVidaUtil;
  }
  
 /**
   * Retorna o percentual depreciado
   * @return float
   */
  public function getPercentualDepreciado() {
    return $this->nPercentualDepreciado;
  }
  
  /**
   * Retorna o valor depreciado
   * @return float
   */
  public function getValorDepreciado() {
    return $this->nValorDepreciado;
  }
  
  public function calcular() {

    /**
     * Calcula o valor do bem
     */
    $nValorParaCalculo = $this->nValorAquisicao - $this->nValorResidual;
    
    /**
     * Percentual anual de depreciaчуo do bem 
     */
    $nPercentualAno = 100 / $this->iVidaUtil;
    
    /**
     * Valor mensal de depreciaчуo 
     */
    $nPercentualMes              = $nPercentualAno/12;
    $this->nPercentualDepreciado = round($nPercentualMes, 2);
    $nValorDepreciado            = round((($nPercentualMes * $nValorParaCalculo) / 100), 2);
    $this->nValorDepreciado      = $nValorDepreciado;
    return $this->nValorDepreciado; 
  }
}

?>
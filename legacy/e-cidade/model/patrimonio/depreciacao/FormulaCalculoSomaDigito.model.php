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

class FormulaCalculoSomaDigito implements IFormulaCalculo {
  
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
   * Construtor
   */
  public function __construct() {
    $this->iTipoCalculo = 1;
  }
  
  /**
   * define a quantidade de anos que o bem  já foi depreciado
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
  
  /**
   * Método que efetua o cálculo 
   */
  public function calcular() {

    /**
     * Calculo do valor atual;
     * para esse calculo, nao devemos usar o valor atual do bem para a base do calculo, 
     * pois a a propria formula de calculo, diminui o valor a ser depreciado.
     */
    $nValorParaCalculo = $this->nValorAquisicao - $this->nValorResidual;
    if ($nValorParaCalculo <= 0) {
      throw new Exception('Bem com valor inconsistente para a fórmula');
    }
    
    if ($this->iAnoCalculo < 0) {
      throw new Exception('Ano do calculo nao informado');
    }
    
    /**
     * o Ano para calculo é a diferenca da vida util com os anos que já foram depreciados do bem 
     */
    $iAnoParaCalculo = $this->iVidaUtil - $this->iAnoCalculo; 
    $iSomaDosDigitos = $this->getSomaDosDigitosDoAno();
    
    /**
     * Calculo do valor de depreciação ao ano:
     * Formula
     */
    $nValorAnual  = round(($iAnoParaCalculo / $iSomaDosDigitos) * $nValorParaCalculo, 2);
    /**
     * Valor mensal a ser Depreciado
     */
    $nValorMensal = DBNumber::truncate($nValorAnual / 12, 2);
    /** 
     * Calculo do valor percentual depreciado no mes;
     */
    $this->nPercentualDepreciado = ($nValorMensal*100) / $nValorParaCalculo;
    $this->nValorDepreciado      = $nValorMensal;
    return $this->nValorDepreciado;
  }
  
  /**
   * Realiza a soma dos digitos do ano da vida util.
   */
  public function getSomaDosDigitosDoAno() {

    $nValorDosDigitos      = 0;
    $aValorInteiroVidaUtil = explode(".", $this->iVidaUtil); 
    $nValorInteiroVidaUtil = $aValorInteiroVidaUtil[0];
    if ($nValorInteiroVidaUtil <= 0) {
      throw new Exception('Bem com vida util menor ou igual a 0 (zero)');
    }
    
    for ($iDigito = 1; $iDigito <= $nValorInteiroVidaUtil; $iDigito++) {
      $nValorDosDigitos += $iDigito;
    }
    return $nValorDosDigitos;    
  }
}

?>
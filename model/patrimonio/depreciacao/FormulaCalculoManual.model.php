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

/**
 * Model para executar a forma de cсlculo Manual
 * @author matheus.felini@dbseller.com.br
 * @package patrimonio
 * @subpackage depreciacao
 * @version $Revision: 1.4 $
 */
class FormulaCalculoManual implements IFormulaCalculo {
  
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
   * Seta o tipo de cсlculo 5
   */
  public function __construct() {
    $this->iTipoCalculo = 5;
  }
  
  /**
   * define a quantidade de anos que o bem  jс foi depreciado
   */
  public function setQuantidadeAnosCalculados($iAno) {
    $this->iAnoCalculo = $iAno;
  }
  
  /**
   * Valor da Aquisiчуo
   * @param float
   */
  public function setValorAquisicao($nValorAquisicao) {
    $this->nValorAquisicao = $nValorAquisicao;
  }
  
  /**
   * Valor da Atual
   * @param float
   */
  public function setValorAtual($nValorAtual = 0) {
    $this->nValorAtual = $nValorAtual;
  }
  
  /**
   * Valor Depreciacao
   * @param float
   */
  public function setPercentualDepreciacao($nPercentualDepreciacao = 0) {
    $this->nPercentualDepreciacao = $nPercentualDepreciacao;
  }
  
  /**
   * Valor Residual
   * @param float
   */
  public function setValorResidual($nValorResidual = 0) {
    $this->nValorResidual = $nValorResidual;
  }
  
  /**
   * Vida Util
   * @param float
   */
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
   * Efetua o cсlculo de depreciaчуo sob o percentual fornecido pelo usuсrio
   */
  public function calcular() {
    
    $nValorParaCalculo           = $this->nValorAquisicao - $this->nValorResidual;
    $nValorDepreciado            = round(($this->nPercentualDepreciacao * $nValorParaCalculo) / 100, 2);
    $this->nValorDepreciado      = $nValorDepreciado;
    $this->nPercentualDepreciado = $this->nPercentualDepreciacao;
    
    return $this->nValorDepreciado;
  }
}
?>
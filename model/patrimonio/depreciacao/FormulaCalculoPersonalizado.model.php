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
 * Model que efetua o cсlculo de depreciaчуo de um item com base no tipo de depreciaчуo cadastrado
 * pelo usuсrio.
 * @author matheus.felini@dbseller.com.br
 * @package patrimonio
 * @subpackage depreciacao
 * @version $Revision: 1.1 $
 */
class FormulaCalculoPersonalizado implements IFormulaCalculo {
  
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
  

  protected $nPercentualAnual;
  
  /**
   * Construtor
   * Seta o tipo de cсlculo e busca o percentual anual cadastrado pelo usuсrio
   */
  public function __construct($iTipoCalculo) {
    
    $this->iTipoCalculo  = $iTipoCalculo;
    $oDaoTipoDepreciacao = db_utils::getDao("benstipodepreciacao");
    $sSqlTipoDepreciacao = $oDaoTipoDepreciacao->sql_query_file($this->iTipoCalculo);
    $rsTipoDepreciacao   = $oDaoTipoDepreciacao->sql_record($sSqlTipoDepreciacao);
    
    if ($oDaoTipoDepreciacao->numrows == 0) {
      throw new Exception("Tipo de depreciaчуo nуo encontrada.");
    }
    $this->nPercentualAnual = db_utils::fieldsMemory($rsTipoDepreciacao, 0)->t46_percentual;
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
   * Efetua o cсlculo de depreciaчуo com base nos dados cadastrado pelo usuсrio
   */
  public function calcular() {

    /**
     * Descobrimos o percentual anual e descontamos este percentual sob o valor do bem
     */
    $nValorParaCalculo        = $this->nValorAquisicao - $this->nValorResidual;
    $nPercentualPorMes        = $this->nPercentualAnual / self::NUMERO_MESES_ANO;
    $nDiferencaPercentualMes  = $this->nPercentualAnual - ($nPercentualPorMes*(self::NUMERO_MESES_ANO));
    $nPercentualPorMes        = $nPercentualPorMes + $nDiferencaPercentualMes;
    
    $nValorPersonalizado         = ($nPercentualPorMes * $nValorParaCalculo) / 100;
    $this->nValorDepreciado      = $nValorPersonalizado;
    $this->nPercentualDepreciado = $nPercentualPorMes;
    return $this->nValorDepreciado;
  }
}
?>
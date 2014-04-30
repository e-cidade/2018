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
 * Strategy FormulaCalculo
 * Identifica a fуrmula de cбlculo de depreciaзгo de um bem e aplica a fуrmula configurada.
 * @author iuri@dbseller.com.br, matheus.felini@dbseller.com.br
 * @package patrimonio
 * @subpackage depreciacao
 * @version $Revision: 1.3 $
 */
class FormulaCalculo implements IFormulaCalculo {
  
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
   * @var IFormulaCalculo
   */
  protected $oFormulaCalculo;
  
  /**
   * 
   */
  public function __construct($iTipoCalculoDepreciacao) {

    switch ($iTipoCalculoDepreciacao) {
      
      case 1:
        
        require_once("model/patrimonio/depreciacao/FormulaCalculoSomaDigito.model.php");
        $this->oFormulaCalculo = new FormulaCalculoSomaDigito($iTipoCalculoDepreciacao);        
      break;
      
      case 2:
        
        require_once("model/patrimonio/depreciacao/FormulaCalculoQuotaConstante.model.php");
        $this->oFormulaCalculo = new FormulaCalculoQuotaConstante($iTipoCalculoDepreciacao);        
      break;
      
      case 3:
        
        require_once("model/patrimonio/depreciacao/FormulaCalculoReceitaFederal.model.php");
        $this->oFormulaCalculo = new FormulaCalculoReceitaFederal($iTipoCalculoDepreciacao);        
      break;
      
      case 4:
      
        require_once("model/patrimonio/depreciacao/FormulaCalculoSemCalculo.model.php");
        $this->oFormulaCalculo = new FormulaCalculoSemCalculo($iTipoCalculoDepreciacao);
      break;
      
      case 5:
      
        require_once("model/patrimonio/depreciacao/FormulaCalculoManual.model.php");
        $this->oFormulaCalculo = new FormulaCalculoManual($iTipoCalculoDepreciacao);
      break;
      
      default:

        $oDaoBensTipoDepreciacao = db_utils::getDao("benstipodepreciacao");
        $sSqlBensTipoDepreciacao = $oDaoBensTipoDepreciacao->sql_query_file($iTipoCalculoDepreciacao);
        $rsTipoDepreciacao       = $oDaoBensTipoDepreciacao->sql_record($sSqlBensTipoDepreciacao);
        
        if ($oDaoBensTipoDepreciacao->numrows == 1) {
          require_once("model/patrimonio/depreciacao/FormulaCalculoPersonalizado.model.php");
          $this->oFormulaCalculo = new FormulaCalculoPersonalizado($iTipoCalculoDepreciacao);
        } else {
          throw new Exception("Tipo de cбlculo de depreciaзгo й invбlido.");
        }
        break;
      
    }
  }
  
  /**
   * 
   * @param integer $iAno ano que deverГЎ ser calculo a depreciacao 
   * @see IFormulaCalculo::setAnoCalculo()
   */
  public function setQuantidadeAnosCalculados($iAno) {
    $this->oFormulaCalculo->setQuantidadeAnosCalculados($iAno);
  }
  
  /**
   * 
   * @param float $nValorAquisicao valor da aquisiГ§ГЈo 
   * @see IFormulaCalculo::setValorAquisicao()
   */
  public function setValorAquisicao($nValorAquisicao) {
    $this->oFormulaCalculo->setValorAquisicao($nValorAquisicao);
  }
  
  /**
   * 
   * @param float $nValorAtual 
   * @see IFormulaCalculo::setValorAtual()
   */
  public function setValorAtual($nValorAtual = 0) {
    $this->oFormulaCalculo->setValorAtual($nValorAtual);
  }
  
  /**
   * 
   * @param float $nValorDepreciacao 
   * @see IFormulaCalculo::setValorDepreciacao()
   */
  public function setPercentualDepreciacao($nPercentualDepreciacao = 0) {
    $this->oFormulaCalculo->setPercentualDepreciacao($nPercentualDepreciacao);
  }
  
  /**
   * 
   * @param float $nValorResidual valor residual do bem 
   * @see IFormulaCalculo::setValorResidual()
   */
  public function setValorResidual($nValorResidual = 0) {
    $this->oFormulaCalculo->setValorResidual($nValorResidual);
  }
  
  /**
   * 
   * @param integer $iVidaUtil tempo de vida util do bem (em anos) 
   * @see IFormulaCalculo::setVidaUtil()
   */
  public function setVidaUtil($iVidaUtil) {
    $this->oFormulaCalculo->setVidaUtil($iVidaUtil);
  }
  
  /**
   * Retorna o percentual depreciado
   * @return float
   */
  public function getPercentualDepreciado() {
    return $this->oFormulaCalculo->getPercentualDepreciado();
  }
  
  /**
   * Retorna o valor depreciado
   * @return float
   */
  public function getValorDepreciado() {
    return $this->oFormulaCalculo->getValorDepreciado();
  }
  
  public function calcular() {
    return $this->oFormulaCalculo->calcular();
  }
  
  function setMesCalculo($iMesCalculo) {
    $this->oFormulaCalculo->setMesCalculo($iMesCalculo);
  }
}

?>
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

require_once("model/contrato/IAcordoItemTipoCalculo.interface.php");
/**
 * Model para efetuar o tipo de cálculo quantidade
 * @author  matheus.felini@dbseller.com.br
 * @package contrato
 * @version $Revision: 1.8 $
 */
class AcordoItemTipoCalculoMesQuantidade implements IAcordoItemTipoCalculo {

  /**
   * Data inicial do item
   * @var string
   */
  protected $dtDataInicial;
  
  /**
   * Data final do item
   * @var string
   */
  protected $dtDataFinal;
  
  /**
   * Quantidade do Item
   * @var integer
   */
  protected $iQuantidade;

  /**
   * Valor Total
   * @var float
   */
  protected $nValorTotal;
  
  /**
   * Coleção de períodos de um item
   * @var array
   */
  protected $aPeriodosItem = array();
  
  /**
   * Construtor
   */
  public function __construct() {
    $this->iTipoCalculo = 1;
  }

  /**
   * @see IAcordoItemTipoCalculo::setDataInicial()
   */
  public function setDataInicial($dtDataInicial) {
    $this->dtDataInicial = $dtDataInicial;
  }

  /**
   * @see IAcordoItemTipoCalculo::setDataFinal()
   */
  public function setDataFinal($dtDataFinal) {
    $this->dtDataFinal = $dtDataFinal;
  }

  /**
   * @see IAcordoItemTipoCalculo::setQuantidade()
   */
  public function setQuantidade($iQuantidade) {
    $this->iQuantidade = $iQuantidade;
  }
  
  /**
   * @see IAcordoItemTipoCalculo::setValorTotal()
   */
  public function setValorTotal($nValorTotal) {
    $this->nValorTotal = $nValorTotal;
  }
  
  /**
   * @see IAcordoItemTipoCalculo::setPeriodosItem()
   */
  public function setPeriodosItem($aPeriodos) {
    $this->aPeriodosItem = $aPeriodos;
  }

  /**
   * @see   IAcordoItemTipoCalculo::calcular()
   * @param $oParametro stdClass - objeto com parâmetros para efetuar o cálculo
   * 
   * Neste caso de tipo de cálculo, precisamos do parâmetro nSaldo. Esta propriedade controla o saldo atual do item
   * para efetuar o arredondamento da quantidade no último período 
   */
  public function calcular($iAcordo, $oParametro = null) {
    
    $iTotalPeriodos = 0;
    foreach ($this->aPeriodosItem as $iIndice => $oPeriodo) {
  
      $iPeriodo        = AcordoPosicao::calculaDiferencaMeses($iAcordo, $oPeriodo->dtDataInicial, $oPeriodo->dtDataFinal);
      $iTotalPeriodos += $iPeriodo;
    }
  
    $nCalculoQuantidadePeriodo = round($this->iQuantidade / $iTotalPeriodos, 3);

    if ($oParametro->nSaldo < $nCalculoQuantidadePeriodo) {
     
      $nDiferenca                 = ($oParametro->nSaldo - $nCalculoQuantidadePeriodo );
      $nCalculoQuantidadePeriodo += $nDiferenca;
      $oParametro->nSaldo         = 0;
    }
    
    $oParametro->nSaldo             = ($oParametro->nSaldo - $nCalculoQuantidadePeriodo);
    $oRetornoCalculo                = new stdClass();
    $oRetornoCalculo->quantidade    = $nCalculoQuantidadePeriodo;
    $oRetornoCalculo->valorunitario = 0;
    $oRetornoCalculo->nSaldo        = $oParametro->nSaldo;
    return $oRetornoCalculo;
  }
}
?>
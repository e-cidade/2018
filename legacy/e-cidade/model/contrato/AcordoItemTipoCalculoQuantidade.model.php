<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
 * Model para efetuar o tipo de cálculo por quantidade
 * @author  matheus.felini@dbseller.com.br
 * @package contrato
 * @version $Revision: 1.5 $
 */
class AcordoItemTipoCalculoQuantidade implements IAcordoItemTipoCalculo {

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
   * Construtor
   */
  public function __construct() {
    $this->iTipoCalculo = 5;
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
   * @see IAcordoItemTipoCalculo::calcular()
   * @param $oParametro stdClass - Não utilizado
   * @param $iAcordo    integer  - Não utilizado
   */
  public function calcular($iAcordo, $oParametro = null) {
    
    $oRetornoCalculo                = new stdClass();
    $oRetornoCalculo->quantidade    = 0;
    $oRetornoCalculo->valorunitario = 0;
    $oRetornoCalculo->nSaldo        = 0;
    return $oRetornoCalculo;
  }
}
?>
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
 * Model para efetuar o tipo de cálculo comercial (30 dias)
 * @author  matheus.felini@dbseller.com.br
 * @package contrato
 * @version $Revision: 1.11 $
 */
class AcordoItemTipoCalculoMesComercial implements IAcordoItemTipoCalculo {

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
   * Constante com os dias do mes comercial
   * @var integer
   */
  const TOTAL_DIAS_MES = 30;

  /**
   * Coleção de períodos de um item
   * @var array
   */
  protected $aPeriodosItem = array();

  /**
   * Construtor
   */
  public function __construct() {
    $this->iTipoCalculo = 3;
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
   * @param $oParametro stdClass - Não Utilizado
   * @param $iAcordo    integer  - Código do acordo - Não Utilizado
   */
  public function calcular($iAcordo, $oParametro = null) {

    /**
     * @todo refatorar em outro momento, retirar datas da classe e passar para a assinatura do método calcular
     */
    list($iDiaInicial, $iMesInicial, $iAnoInicial) = explode("/", $this->dtDataInicial);
    list($iDiaFinal,   $iMesFinal,   $iAnoFinal)   = explode("/", $this->dtDataFinal);
    
    
     /*
     * Calcula a diferença de dias entre a data final e inicial do período, somando UM para
     * considerar o dia inicial
     */
    if ($iDiaFinal > self::TOTAL_DIAS_MES) {
      $iDiaFinal = self::TOTAL_DIAS_MES;
    }
    if ($iMesInicial== 2 && ($iDiaFinal == 28 || $iDiaFinal == 29)) {
      $iDiaFinal = self::TOTAL_DIAS_MES;
    }
    $nQuantidadeDiasPeriodo = ($iDiaFinal - $iDiaInicial)+1;

    /**
     * Verifica se o resultado e superior ao numero total de dias mes (30), caso seja a quantidade de dias a
     * executar eh o total = 30
     */
    if ($nQuantidadeDiasPeriodo > self::TOTAL_DIAS_MES) {
      $nQuantidadeDiasPeriodo = self::TOTAL_DIAS_MES;
    }
    
    $oAcordo = new Acordo($iAcordo);
    
    if ($oAcordo->getPeriodoComercial()) {
      $nQuantidadeDiasPeriodo = 30;
    }
    
    $oRetornoCalculo                = new stdClass();
    $oRetornoCalculo->quantidade    = $nQuantidadeDiasPeriodo;
    $oRetornoCalculo->data          = $this->dtDataInicial;
    $oRetornoCalculo->valorunitario = 0;
    $oRetornoCalculo->nSaldo        = 0;
    return $oRetornoCalculo;
  }
}
?>
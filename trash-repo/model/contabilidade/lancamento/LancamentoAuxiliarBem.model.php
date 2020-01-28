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

require_once ("interfaces/ILancamentoAuxiliar.interface.php");
require_once ("model/contabilidade/lancamento/LancamentoAuxiliarBase.model.php");

/**
 * Salva os lançamentos auxiliares do Bem 
 * @author rafael.lopes <rafael.lopes@dbseller.com.br>
 * @package contabilidade
 * @subpackage lancamento
 * @version $Revision: 1.8 $
 */
class LancamentoAuxiliarBem extends LancamentoAuxiliarBase implements ILancamentoAuxiliar {

	/**
	 * Complemento para o lançamento contábil
	 * @var string
	 */
	protected $sComplemento;
	
  /**
   * Dados da tabela conhist
   * @var integer
   */
  private $iHistorico;

  /**
   * Bem
   * @var Bem
   */
  private $oBem;

  /**
   * Variável de controle para sabermos se o lançamento é um estorno
   * @var boolean
   */
  protected $lEstorno = false;
  
  /**
   * Executa os lançamentos auxiliares dos Movimentos de uma liquidacao
   * @see ILancamentoAuxiliar::executaLancamentoAuxiliar()
   * @param integer $iCodigoLancamento - Código do Lancamento (conlancam)
   * @param date    $dtLancamento      - data do lancamento
   */
  public function executaLancamentoAuxiliar($iCodigoLancamento, $dtLancamento)  {

    $this->setCodigoLancamento($iCodigoLancamento);
    $this->setDataLancamento($dtLancamento);
    if ($this->getEmpenho() instanceof EmpenhoFinanceiro && $this->getEmpenho()->getNumero() != "") { 
    	
      $this->setNumeroEmpenho($this->getEmpenho()->getNumero());
	    $this->salvarVinculoEmpenho();
    }
    $this->salvarVinculoBem();
    parent::salvarVinculoComplemento();
    
    if ($this->getCodigoNotaLiquidacao() != "") {
      $this->salvarVinculoNotaDeLiquidacao();
    }
    return true;
  }

  /**
   * Salva o vínculo do lançamento com o Bem
   * @throws BusinessException
   * @return boolean
   */
  protected function salvarVinculoBem() {

    $oDaoConLancamBem                  = db_utils::getDao('conlancambem');
    $oDaoConLancamBem->c110_sequencial = null;
    $oDaoConLancamBem->c110_codlan     = $this->iCodigoLancamento;
    $oDaoConLancamBem->c110_bem        = $this->getBem()->getCodigoBem();
    $oDaoConLancamBem->incluir($oDaoConLancamBem->c110_sequencial);
    if ($oDaoConLancamBem->erro_status == "0") {

      $sMsgErro  = "Não foi possível salvar o vínculo do Bem {$this->getBem()->getCodigoBem()} com o lançamento. ";
      $sMsgErro .= $oDaoConLancamBem->erro_msg;
      throw new BusinessException($sMsgErro);
    }
    
    return true;
  }

  /**
   * @see ILancamentoAuxiliar::setHistorico()
   */
  public function setHistorico($iHistorico) {
    $this->iHistorico = $iHistorico;
  }

  /**
   * @see ILancamentoAuxiliar::getHistorico()
   */
  public function getHistorico() {
    return $this->iHistorico;
  }

  /**
   * @see ILancamentoAuxiliar::setValorTotal()
   */
  public function setValorTotal($nValorTotal) {
    $this->nValorTotal = $nValorTotal;
  }

  /**
   * @see ILancamentoAuxiliar::getValorTotal()
   */
  public function getValorTotal() {
    return $this->nValorTotal;
  }

  /**
   * Seta o Bem que iremos lançar contábilmente
   * @param Bem $oBem
   */
  public function setBem(Bem $oBem) {
    $this->oBem = $oBem;
  }

  /**
   * Retorna o objeto Bem
   * @return Bem
   */
  public function getBem() {
    return $this->oBem;
  }
  /**
   * Seta valor para o complemento do lançamento contábil
   * @see LancamentoAuxiliarBase::setObservacaoHistorico()
   */
  public function setObservacaoHistorico($sObservacaoHistorico) {
  	$this->sComplemento = $sObservacaoHistorico;
  }
  
  /**
   * Retorna o complemento do lançamento contábil
   * @see LancamentoAuxiliarBase::getObservacaoHistorico()
   */
  public function getObservacaoHistorico() {
  	return $this->sComplemento;
  }
  
  /**
   * Seta se o lançamento é um estorno
   * @param boolean $lEstorno
   */
  public function setEstorno($lEstorno) {
  	$this->lEstorno = $lEstorno;
  }
  
  /**
   * Retorna se o lançamento é um estorno
   * @return boolean
   */
  public function isEstorno() {
  	return $this->lEstorno;
  } 

  
  /**
   * irá construir um lançamento auxiliar do tipo bem
   * @param integer $iCodigoLancamento (codlan)
   * @return object $oLancamentoAuxiliar
   */
  public static function getInstance($iCodigoLancamento){
  
  	$oDaoConlancamBem  = db_utils::getDao("conlancambem");
  	$sCampos             = "bens.*, conlancam.*, conlancamcompl.*";
  	
  	// buscamos o historico do lancamento
  	$sSqlConLancamBem = $oDaoConlancamBem->sql_query_dadoslancamento(null, "*", null, "c72_codlan = {$iCodigoLancamento}");
  	$rsConLancamBem   = $oDaoConlancamBem->sql_record($sSqlConLancamBem);

  	if ($oDaoConlancamBem->numrows == 0) {
  		throw new BusinessException("Vinculo do lançamento {$iCodigoLancamento} com o bem não encontrado.");
  	}
  	$oConLancamBem          = db_utils::fieldsMemory($rsConLancamBem, 0);
  	$oBem                   = new Bem($oConLancamBem->t52_bem);
  	 
  	$oLancamentoauxiliarBem = new LancamentoAuxiliarBem();
  	$oLancamentoauxiliarBem->setValorTotal($oConLancamBem->c70_valor);
  	$oLancamentoauxiliarBem->setBem($oBem);
  	$oLancamentoauxiliarBem->setObservacaoHistorico($oConLancamBem->c72_complem);
  	
  	return $oLancamentoauxiliarBem;
  }
  
}
?>
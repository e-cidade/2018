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
 * Salva os lançamentos auxiliares do reconhecimento contabil 
 * @author rafael.lopes <rafael.lopes@dbseller.com.br>
 * @package contabilidade
 * @subpackage lancamento
 * @version 1.0 $
 */
class LancamentoAuxiliarReconhecimentoContabil extends LancamentoAuxiliarBase implements ILancamentoAuxiliar {
	
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
   * Reconhecimento contábil
   * @var Reconhecimento contabil
   */
  private $oReconhecimentoContabil;
  
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
  	$this->setFavorecido($this->getReconhecimentoContabil()->getCredor()->getCodigo());
  	
  	$this->salvarVinculoComplemento();
  	$this->salvarVinculoCgm();
  	$this->salvarVinculoReconhecimentoContabil();
  	
  	return true;
  }	
  	
  /**
   * Salva o vínculo do lançamento com o Reconhecimento contabil
   * @throws BusinessException
   * @return boolean
   */
  protected function salvarVinculoReconhecimentoContabil() {

    $oDaoConLancamReconhecimentoContabil = db_utils::getDao('conlancamreconhecimentocontabil');
    $oDaoConLancamReconhecimentoContabil->c113_reconhecimentocontabil = $this->getReconhecimentoContabil()->getSequencial(); 
    $oDaoConLancamReconhecimentoContabil->c113_codlan                 = $this->iCodigoLancamento;  
    $oDaoConLancamReconhecimentoContabil->incluir(null);
    
    if ($oDaoConLancamReconhecimentoContabil->erro_status == "0") {

      $sMsgErro  = "Não foi possível salvar o vínculo do reconhecimento contábil com o lançamento. ";
      $sMsgErro .= $oDaoConLancamReconhecimentoContabil->erro_msg;
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
   * Seta o Reconhecimento Contabil que iremos lançar contábilmente
   * @param Reconecimento contabil $oBem
   */
  public function setReconhecimentoContabil(ReconhecimentoContabil $oReconhecimentoContabil) {
    $this->oReconhecimentoContabil = $oReconhecimentoContabil;
  }
  
  /**
   * Retorna o objeto Reconhecimento Contabil
   * @return Reconhecimento Contabil
   */
  public function getReconhecimentoContabil() {
    return $this->oReconhecimentoContabil;
  }  
  
  /**
   * irá construir um lançamento auxiliar do tipo reconhecimento contabil
   * @param integer $iCodigoLancamento (codlan)
   * @return object $oLancamentoAuxiliar
   */
  public static function getInstance($iCodigoLancamento){
  	
  	$oDaoConlancamReconhecimentoContabil = db_utils::getDao("conlancamreconhecimentocontabil");
  	$sWhere 														 = "c113_codlan = {$iCodigoLancamento}";
  	$sSql 															 = $oDaoConlancamReconhecimentoContabil->sql_query_dadoslancamento(null, "*", null, $sWhere );
  	$rsResultado 												 = $oDaoConlancamReconhecimentoContabil->sql_record($sSql);
  	
  	if ($oDaoConlancamReconhecimentoContabil->numrows == 0) {
  		throw new BusinessException("Vinculo do lançamento {$iCodigoLancamento} não encontrado com o reconhecimento contábil.");
  	}
  	
  	$oDadosReconhecimento    = db_utils::fieldsMemory($rsResultado, 0);
  	$oDataAtual 						 = new DBDate($oDadosReconhecimento->c70_data);
  	$oReconhecimentoContabil = new ReconhecimentoContabil($oDadosReconhecimento->c112_sequencial, $oDataAtual);
  	$oLancamentoAuxiliar     = new LancamentoAuxiliarReconhecimentoContabil();
  	$oLancamentoAuxiliar->setObservacaoHistorico($oDadosReconhecimento->c72_complem);
  	$oLancamentoAuxiliar->setFavorecido($oDadosReconhecimento->c112_numcgm);
  	$oLancamentoAuxiliar->setDataLancamento($oDadosReconhecimento->c70_data);
  	$oLancamentoAuxiliar->setCodigoLancamento($iCodigoLancamento);
  	$oLancamentoAuxiliar->setValorTotal($oDadosReconhecimento->c70_valor);
  	$oLancamentoAuxiliar->setFavorecido($oDadosReconhecimento->c112_numcgm);
  	$oLancamentoAuxiliar->setReconhecimentoContabil($oReconhecimentoContabil);
  	
  	return $oLancamentoAuxiliar;
  } 
  
}
?>
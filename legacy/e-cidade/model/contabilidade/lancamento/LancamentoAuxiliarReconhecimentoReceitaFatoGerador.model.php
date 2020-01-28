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
 * Executa os lan�amentos contabeis auxiliares de uma receita reconhecida pela fato gerador
 * @author Matheus Felini
 * @package contabilidade
 * @subpackage lancamento
 * @version $Revision: 1.6 $
 */
class LancamentoAuxiliarReconhecimentoReceitaFatoGerador extends LancamentoAuxiliarBase implements ILancamentoAuxiliar {
  
  /**
   * Observa��es
   * @var string
   */
  protected $sObservacao;
  
  /**
   * Codigo do Hist�rico
   * @var integer
   */
  protected $iCodigoHistorico;
  
  /**
   * Valor total do lan�amento
   * @var float
   */
  protected $nValorTotal;
  
  /**
   * Codigo da Receita
   * @var integer
   */
  protected $iCodigoReceita;
  
  /**
   * Ano da Receita
   * @var integer
   */
  protected $iAnoReceita;
  
  /**
   * C�digo sequencial da abertura do exercicio
   * @var integer
   */
  protected $iCodigoAberturaExercicio;
  
  /**
   * Codigo da Conta Debito
   * @var integer
   */
  protected $iCodigoContaDebito;
  
  /**
   * Codigo da conta credito
   * @var integer
   */
  protected $iCodigoContaCredito;
  
  
  /**
   * Executa os lancamentos auxiliares da receita reconhecida pelo fato gerador
   * @see ILancamentoAuxiliar::executaLancamentoAuxiliar()
   * @param integer $iCodigoLancamento
   * @param date $dtLancamento
   */
  public function executaLancamentoAuxiliar ($iCodigoLancamento, $dtLancamento) {
    
    $oDaoConLancamCompl = db_utils::getDao('conlancamcompl');
    $oDaoConLancamCompl->c72_codlan  = $iCodigoLancamento;
    $oDaoConLancamCompl->c72_complem = addslashes($this->getObservacaoHistorico());
    $oDaoConLancamCompl->incluir($iCodigoLancamento);
    
    if ($oDaoConLancamCompl->erro_status == "0") {
    	$sErroMsg  = "N�o foi poss�vel adicionar um complemento/observa��o ao lan�amento.";
    	throw new BusinessException($sErroMsg);
    }
    
    $oDaoConLancamRec = db_utils::getDao('conlancamrec');
    $oDaoConLancamRec->c74_codlan = $iCodigoLancamento;
    $oDaoConLancamRec->c74_anousu = $this->iAnoReceita;
    $oDaoConLancamRec->c74_codrec = $this->iCodigoReceita;
    $oDaoConLancamRec->c74_data   = $dtLancamento;
    $oDaoConLancamRec->incluir($iCodigoLancamento);
    if ($oDaoConLancamRec->erro_status == "0") { 
      $sErroMsg  = "N�o foi poss�vel vincular a receita com o lan�amento.";
      
      echo $oDaoConLancamRec->erro_msg;
      throw new BusinessException($sErroMsg);
    }

    $oDaoLancamentoAberturaExercicio = db_utils::getDao('conlancamaberturaexercicio');
    $oDaoLancamentoAberturaExercicio->c80_conlancam         = $iCodigoLancamento;
    $oDaoLancamentoAberturaExercicio->c80_aberturaexercicio = $this->iCodigoAberturaExercicio;
    $oDaoLancamentoAberturaExercicio->incluir($iCodigoLancamento);
    if ($oDaoLancamentoAberturaExercicio->erro_status == "0") {
    	$sErroMsg  = "N�o foi poss�vel vincular a abertura do exerc�cio com o lan�amento.";
    	throw new BusinessException($sErroMsg);
    }
    return true;
  }
  
  /**
   * Seta codigo da conta credito
   * @param integer $iCodigoContaCredito
   */
  public function setCodigoContaCredito($iCodigoContaCredito) {
    $this->iCodigoContaCredito = $iCodigoContaCredito;
  }
  
  /**
   * Retorna o codigo da conta credito
   * @return integer
   */
  public function getCodigoContaCredito() {
  	return $this->iCodigoContaCredito;
  }
  
  /**
   * Seta codigo da conta credito
   * @param integer $iCodigoContaDebito
   */
  public function setCodigoContaDebito($iCodigoContaDebito) {
  	$this->iCodigoContaDebito = $iCodigoContaDebito;
  }
  
  /**
   * Retorna o codigo da conta debito
   * @return integer
   */
  public function getCodigoContaDebito() {
  	return $this->iCodigoContaDebito;
  }
  
  /**
   * Seta o codigo sequencial da abertura do exercicio
   * @param integer $iCodigoAberturaExercicio
   */
  public function setCodigoAberturaExercicio($iCodigoAberturaExercicio) {
    $this->iCodigoAberturaExercicio = $iCodigoAberturaExercicio;
  }
  
  /**
   * Seta o c�digo da receita
   * @param integer $iCodigoReceita
   */
  public function setCodigoReceita($iCodigoReceita) {
    $this->iCodigoReceita = $iCodigoReceita;
  }
  
  /**
   * Seta o ano da receita
   * @param integer $iAnoReceita
   */
  public function setAnoReceita($iAnoReceita) {
    $this->iAnoReceita = $iAnoReceita;
  }
  
  /**
   * Seta a observa��o do hist�rico da opera��o
   * @param string $sObservacaoHistorico
   */
  public function setObservacaoHistorico($sObservacaoHistorico) {
    $this->sObservacao = $sObservacaoHistorico;
  }

  /**
   * Retorna a observacao do historico
   * @see ILancamentoAuxiliar::getObservacaoHistorico()
   */
  public function getObservacaoHistorico() {
    return $this->sObservacao;
  }

  /**
   * Seta o codigo historico
   * @see ILancamentoAuxiliar::setHistorico()
   */
  public function setHistorico($iHistorico) {
    $this->iCodigoHistorico = $iHistorico;
  }

  /**
   * Retorna o codigo historico
   * @see ILancamentoAuxiliar::getHistorico()
   */
  public function getHistorico() {
    return $this->iCodigoHistorico;
  }

  /**
   * Seta o valor total
   * @see ILancamentoAuxiliar::setValorTotal()
   */
  public function setValorTotal($nValorTotal) {
  	$this->nValorTotal = $nValorTotal;
  }

  /**
   * Retorna o valor total
   * @see ILancamentoAuxiliar::getValorTotal()
   */
  public function getValorTotal() {
    return $this->nValorTotal;
  }
}
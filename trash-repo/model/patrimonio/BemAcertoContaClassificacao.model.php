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


/**
 * Classe para acerto da classifica��o de contas dos bens patrimoniais no momento da baixa
 * Service de acerto, que realiza lan�amento de acerto entre as contas da classifica��o e de deprecia��o
 *
 * @author Bruno Silva <bruno.silva@dbseller.com.br>
 * @package patrimonio
 * @version 1.00 $
 */

class BemAcertoContaClassificacao {

  const EXISTE_PENDENCIA = true;
  const SEM_PENDENCIA    = false;
  const SEM_DEPRECIACAO  = 4;

  /**
   * Status poss�veis da flag de execu��o do acerto do bem
   * @var unknown
   */
  const STATUS_ERRO      = false;
  const STATUS_OK        = true;

  /**
   * Status do Acerto
   * @var boolean
   */
  private $lFlagStatus;

  /**
   * Guarda o status de erro
   * @var String
   */
  private $sMensagemPendencia;

  public function __construct() {

    $this->lFlagStatus        = BemAcertoContaClassificacao::STATUS_OK;
    $this->sMensagemPendencia = null;
  }

  /**
   * Retorna Status do Service de Acerto
   * @return boolean
   */
  public function getFlagStatus() {
    return $this->lFlagStatus;
  }

  /**
   * Retorna a mensagem interna de pend�ncia
   * @return string
   */
  public function getMensagemPendencia() {
    return $this->sMensagemPendencia;
  }


  /**
   * M�otodo principal que acerta as contas de deprecia��o e classfica��o, antes da baixa de um bem
   * O m�todo verifica se existem pend�ncia para a baixa, para ent�o realizar o ajuste
   * @param Bem $oBem
   * @param DBDate $oDataCorrente
   * @throws Exception
   */
  public function acertaContasDepreciacaoClassificacao(Bem $oBem, DBDate $oDataCorrente) {

    $lPossuiPendencia = $this->verificaExistenciaPendeciaParaBaixa($oBem, $oDataCorrente);

    if ($lPossuiPendencia) {

      $this->lFlagStatus = self::STATUS_ERRO;
      throw new Exception($this->sMensagemPendencia);
    } else {

    	$oDataAquisicaoBem = new DBDate($oBem->getDataAquisicao());
    	if ($oBem->getQuantidadeMesesDepreciados() == 0 && $oDataCorrente->getMes() == $oDataAquisicaoBem->getMes() && $oDataCorrente->getAno() == $oDataAquisicaoBem->getAno()) {
    		return false;
    	}

    	$lRealizouAcerto = $this->realizaLancamentoAjustesDasContas($oBem, $oDataCorrente);
	    if (!$lRealizouAcerto) {
	      $this->lFlagStatus  = self::STATUS_ERRO;
	      throw new Exception($this->sMensagemPendencia);
	    }
    }
  }

  /**
   * M�todo que verifica existencia de pend�ncias para baixa de um Bem
   * Caso exista pend�ncias, � setada a mensagem de pend�ncia e retornado true
   * @param Bem $oBem
   * @param DBDate $oDataCorrente
   * @return boolean
   */
  public function verificaExistenciaPendeciaParaBaixa(Bem $oBem, DBDate $oDataCorrente) {

    $iTipoDepreciacao = $oBem->getTipoDepreciacao()->getCodigo();
    if ($oBem->verificaSituacaoNota() == Bem::EMLIQUIDACAO) {

      $this->sMensagemPendencia = "O bem � oriundo de ordem de compra, mas n�o est� liquidado.";
      $this->lFlagStatus        = self::STATUS_ERRO;
      return self::EXISTE_PENDENCIA;
    }

    if ($iTipoDepreciacao != self::SEM_DEPRECIACAO) {

    	$oDataAquisicaoBem = new DBDate($oBem->getDataAquisicao());
    	if ($oBem->getQuantidadeMesesDepreciados() == 0 && $oDataCorrente->getMes() == $oDataAquisicaoBem->getMes() && $oDataCorrente->getAno() == $oDataAquisicaoBem->getAno()) {
    		return self::SEM_PENDENCIA;
    	}

      $iMesCorrente             = $oDataCorrente->getMes();
      $iAnoCorrente             = $oDataCorrente->getAno();
      $oDadosUltimaDepreciacao  = BemDepreciacao::getInstance($oBem);
      
      if (!empty($oDadosUltimaDepreciacao) && $oDadosUltimaDepreciacao instanceof BemDepreciacao) {
        
        $iUltimoMesDepreciado     = $oDadosUltimaDepreciacao->getMes();
        $iUltimoAnoDepreciado     = $oDadosUltimaDepreciacao->getAno();
  
        if ($iUltimoAnoDepreciado != $iAnoCorrente || $iUltimoMesDepreciado <= $iMesCorrente) {
  
          $this->sMensagemPendencia = "Bem n�o depreciado at� a data corrente";
          $this->lFlagStatus        = self::STATUS_ERRO;
          return self::EXISTE_PENDENCIA;
        }
      }
    }
    return self::SEM_PENDENCIA;
  }

  /**
   *
   * @param Bem $oBem
   * @param DBDate $oDataCorrente
   * @return boolean
   */
  private function realizaLancamentoAjustesDasContas(Bem $oBem, DBDate $oDataCorrente) {

    $nValorAquisicao   = $oBem->getValorAquisicao();
    $nValorAtual       = $oBem->getValorAtual();
    
    if ($nValorAquisicao == $nValorAtual) {
      return self::STATUS_OK;
    }

    $oDadosReavaliacao = InventarioBem::buscaDadosDaReavaliacaoBem($oBem);

    if (!empty($oDadosReavaliacao)) {
      $nValorAquisicao = $oDadosReavaliacao->getValorDepreciavel() + $oDadosReavaliacao->getValorResidual();
    }

    $oDadosUltimaDepreciacao  = BemDepreciacao::getInstance($oBem);

    if (!empty($oDadosUltimaDepreciacao)) {
      $nValorAtual = $oDadosUltimaDepreciacao->getValorAtual();
    }

    $nValorLancamentoAjuste = $nValorAquisicao - $nValorAtual;
    $oClassificacao         = $oBem->getClassificacao();

    if (empty($oClassificacao)) {

      $this->sMensagemPendencia  = "Bem n�o possui classifica��o associada";
      $this->lFlagStatus         = self::STATUS_ERRO;
      return $this->lFlagStatus;
    }

    $oContaClassificacao = $oClassificacao->getContaContabil();

    if (empty($oContaClassificacao)) {

      $this->sMensagemPendencia  = "Bem n�o possui Conta de classifica��o associada";
      $this->lFlagStatus         = self::STATUS_ERRO;
      return $this->lFlagStatus;
    }

    $oContaDepreciacao   = $oClassificacao->getContaDepreciacao();

    if (empty($oContaDepreciacao)) {

      $this->sMensagemPendencia  = "Bem n�o possui Conta de deprecia��o associada";
      $this->lFlagStatus         = self::STATUS_ERRO;
      return $this->lFlagStatus;
    }
    $iReduzidoContaClassificacao = $oClassificacao->getContaContabil()->getReduzido();
    $iReduzidoContaDeprecicaao   = $oClassificacao->getContaDepreciacao()->getReduzido();

    $oEventoContabil        = new EventoContabil(703, $oDataCorrente->getAno());
    $aLancamentos           = $oEventoContabil->getEventoContabilLancamento();
    $oLancamentoAuxiliarBem = new LancamentoAuxiliarBem();
    $oLancamentoAuxiliarBem->setBem($oBem);
    $oLancamentoAuxiliarBem->setValorTotal($nValorLancamentoAjuste);
    $oLancamentoAuxiliarBem->setObservacaoHistorico("Lan�amento de ajuste da baixa do bem.");
    $oLancamentoAuxiliarBem->setHistorico($aLancamentos[0]->getHistorico());
    $oEventoContabil->executaLancamento($oLancamentoAuxiliarBem);
    return self::STATUS_OK;
  }
}
?>
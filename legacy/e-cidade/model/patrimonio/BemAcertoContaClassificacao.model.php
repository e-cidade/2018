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


/**
 * Classe para acerto da classificação de contas dos bens patrimoniais no momento da baixa
 * Service de acerto, que realiza lançamento de acerto entre as contas da classificação e de depreciação
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
   * Status possíveis da flag de execução do acerto do bem
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
   * Retorna a mensagem interna de pendência
   * @return string
   */
  public function getMensagemPendencia() {
    return $this->sMensagemPendencia;
  }


  /**
   * Méotodo principal que acerta as contas de depreciação e classficação, antes da baixa de um bem
   * O método verifica se existem pendência para a baixa, para então realizar o ajuste
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

      $sDataultimaAvaliacao = $oBem->getDataUltimaAvaliacao();
    	$oDataAquisicaoBem    = new DBDate( ($sDataultimaAvaliacao ?: $oBem->getDataAquisicao()) );
    	if ( $oBem->getQuantidadeMesesDepreciados() == 0 &&
    	     $oDataCorrente->getMes() == $oDataAquisicaoBem->getMes() &&
    	     $oDataCorrente->getAno() == $oDataAquisicaoBem->getAno()     ) {
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
   * Método que verifica existencia de pendências para baixa de um Bem
   * Caso exista pendências, é setada a mensagem de pendência e retornado true
   * @param Bem $oBem
   * @param DBDate $oDataCorrente
   * @return boolean
   */
  public function verificaExistenciaPendeciaParaBaixa(Bem $oBem, DBDate $oDataCorrente) {

    if (!$oBem->getTipoDepreciacao() instanceof BemTipoDepreciacao) {
      throw new Exception("Bem sem tipo de depreciação configurado.");
    }

    $iTipoDepreciacao = $oBem->getTipoDepreciacao()->getCodigo();
    if ($oBem->verificaSituacaoNota() == Bem::EMLIQUIDACAO) {

      $this->sMensagemPendencia = "O bem é oriundo de ordem de compra, mas não está liquidado.";
      $this->lFlagStatus        = self::STATUS_ERRO;
      return self::EXISTE_PENDENCIA;
    }

    if ($iTipoDepreciacao != self::SEM_DEPRECIACAO) {

    	$oDataAquisicaoBem = new DBDate($oBem->getDataAquisicao());
    	if ( $oBem->getQuantidadeMesesDepreciados() == 0 &&
    	     $oDataCorrente->getMes() == $oDataAquisicaoBem->getMes() &&
    	     $oDataCorrente->getAno() == $oDataAquisicaoBem->getAno()     ) {
    		return self::SEM_PENDENCIA;
    	}

      $iMesCorrente             = $oDataCorrente->getMes();
      $iAnoCorrente             = $oDataCorrente->getAno();
      $oDadosUltimaDepreciacao  = BemDepreciacao::getInstance($oBem);

      if (!empty($oDadosUltimaDepreciacao) && $oDadosUltimaDepreciacao instanceof BemDepreciacao) {

        /**
         * Verifica se o bem foi depreciado até o mês anterior
         */

        $oDataUltimaDepreciacao = new DBDate("01/{$oDadosUltimaDepreciacao->getMes()}/{$oDadosUltimaDepreciacao->getAno()}");
        $oDateInterval = DBDate::getIntervaloEntreDatas($oDataCorrente, $oDataUltimaDepreciacao);

        if ($oDateInterval->m > 1 && $oDadosUltimaDepreciacao->getValorAtual() != 0) {

          $this->sMensagemPendencia = "Não é possível realizar a baixa pois não foi efetuada a depreciação no mês anterior.";
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

    $nValorAquisicao   = round($oBem->getValorAquisicao(), 2);
    $nValorAtual       = round($oBem->getValorAtual(), 2);

    if ($nValorAquisicao == $nValorAtual) {
      return self::STATUS_OK;
    }

    $oDadosReavaliacao = InventarioBem::buscaDadosDaReavaliacaoBem($oBem);

    if (!empty($oDadosReavaliacao)) {
      $nValorAquisicao = round($oDadosReavaliacao->getValorDepreciavel() + $oDadosReavaliacao->getValorResidual(), 2);
    }

    $oDadosUltimaDepreciacao  = BemDepreciacao::getInstance($oBem);

    if (!empty($oDadosUltimaDepreciacao)) {
      $nValorAtual = round($oDadosUltimaDepreciacao->getValorAtual(), 2);
    }

    $nValorLancamentoAjuste = $nValorAquisicao - $nValorAtual;
    $oClassificacao         = $oBem->getClassificacao();

    if (empty($oClassificacao)) {

      $this->sMensagemPendencia  = "Bem não possui classificação associada";
      $this->lFlagStatus         = self::STATUS_ERRO;
      return $this->lFlagStatus;
    }

    $oContaClassificacao = $oClassificacao->getContaContabil();

    if (empty($oContaClassificacao)) {

      $this->sMensagemPendencia  = "Bem não possui Conta de classificação associada";
      $this->lFlagStatus         = self::STATUS_ERRO;
      return $this->lFlagStatus;
    }

    $oContaDepreciacao   = $oClassificacao->getContaDepreciacao();

    if (empty($oContaDepreciacao)) {

      $this->sMensagemPendencia  = "Bem não possui Conta de depreciação associada";
      $this->lFlagStatus         = self::STATUS_ERRO;
      return $this->lFlagStatus;
    }

    $oEventoContabil        = new EventoContabil(703, $oDataCorrente->getAno());
    $aLancamentos           = $oEventoContabil->getEventoContabilLancamento();
    $oLancamentoAuxiliarBem = new LancamentoAuxiliarBem();
    $oLancamentoAuxiliarBem->setBem($oBem);
    $oLancamentoAuxiliarBem->setValorTotal($nValorLancamentoAjuste);
    $oLancamentoAuxiliarBem->setObservacaoHistorico("Lançamento de ajuste da baixa do bem.");
    $oLancamentoAuxiliarBem->setHistorico($aLancamentos[0]->getHistorico());
    $oEventoContabil->executaLancamento($oLancamentoAuxiliarBem);
    return self::STATUS_OK;
  }
}
?>

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

use ECidade\Patrimonial\Acordo\RegimeCompetencia\Model\Parcela;

require_once(modification("interfaces/ILancamentoAuxiliar.interface.php"));
require_once(modification("model/contabilidade/lancamento/LancamentoAuxiliarBase.model.php"));

/**
 * Class LancamentoAuxiliarReconhecimentoCompetencia
 */
class LancamentoAuxiliarReconhecimentoCompetencia extends LancamentoAuxiliarBase implements ILancamentoAuxiliar {

  /**
   * Dados da tabela conhist
   * @var integer
   */
  private $iHistorico;

  /**
   * Valor total do empenho
   * @var float
   */
  private $nValorTotal;

  /**
   * Acordo
   * @var Acordo
   */
  private $oAcordo;

  /**
   * Empenho Financeiro
   * @var EmpenhoFinanceiro
   */
  private $oEmpenhoFinanceiro;

  /**
   * @var ContaPlanoPCASP
   */
  private $oContaCredito;

  /**
   * @var ContaPlanoPCASP
   */
  private $oContaDebito;

  /**
   * @var Parcela
   */
  private $oParcela;


  /**
   * @param int    $iCodigoLancamento
   * @param string $dtLancamento
   *
   * @return bool
   */
  public function executaLancamentoAuxiliar($iCodigoLancamento, $dtLancamento)  {

    $this->setCodigoLancamento($iCodigoLancamento);
    $this->setDataLancamento($dtLancamento);
    $this->salvarVinculoAcordo();
    $this->salvarVinculoEmpenho();
    $this->salvarVinculoNotaDeLiquidacao();
    $this->salvarVinculoRegimeCompetencia();
    $this->salvarVinculoCgm();
    $this->salvarVinculoComplemento();

    return true;
  }

  /**
   * @return bool
   */
  public function salvarVinculoNotaDeLiquidacao() {

    if ($this->getCodigoNotaLiquidacao() != "") {
      parent::salvarVinculoNotaDeLiquidacao();
    }
    return true;
  }

  /**
   * @return bool
   */
  protected function salvarVinculoEmpenho() {

    if (!empty($this->oEmpenhoFinanceiro)) {

      $this->iNumeroEmpenho = $this->oEmpenhoFinanceiro->getNumero();
      parent::salvarVinculoEmpenho();
    }
    return true;
  }

  /**
   * Salva o vínculo do lançamento com o acordo
   * @throws BusinessException
   * @return boolean
   */
  protected function salvarVinculoAcordo() {

    if (empty($this->oAcordo)) {
      return true;
    }

    $oDaoConLancamAcordo                 = new cl_conlancamacordo();
    $oDaoConLancamAcordo->c87_sequencial = null;
    $oDaoConLancamAcordo->c87_codlan     = $this->iCodigoLancamento;
    $oDaoConLancamAcordo->c87_acordo     = $this->oAcordo->getCodigoAcordo();
    $oDaoConLancamAcordo->incluir($this->iCodigoLancamento);
    if ($oDaoConLancamAcordo->erro_status == "0") {

      $sMsgErro = "Não foi possível salvar o vínculo do acordo {$this->oAcordo->getCodigoAcordo()} com o contrato.";
      throw new BusinessException($sMsgErro);
    }
    return true;
  }

  /**
   * @return bool
   * @throws BusinessException
   */
  protected function salvarVinculoRegimeCompetencia() {

    $oDaoCompetencia = new cl_conlancamprogramacaofinanceiraparcela();
    $oDaoCompetencia->c118_conlancam = $this->iCodigoLancamento;
    $oDaoCompetencia->c118_programacaofinanceiraparcela = $this->oParcela->getCodigo();
    $oDaoCompetencia->incluir();

    if ($oDaoCompetencia->erro_status == "0") {
      throw new BusinessException("Ocorreu um erro ao salvar o vínculo entre o lançamento e a parcela do reconhecimento de competência.");
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
   * Seta o acordo que iremos lançar contábilmente
   * @param Acordo $oAcordo
   */
  public function setAcordo(Acordo $oAcordo) {
    $this->oAcordo = $oAcordo;
  }

  /**
   * Retorna o objeto Acordo
   * @return Acordo
   */
  public function getAcordo() {
    return $this->oAcordo;
  }

  /**
   * Seta o empenho do do acordo
   * @param EmpenhoFinanceiro $oEmpenho
   */
  public function setEmpenho(EmpenhoFinanceiro $oEmpenho) {
    $this->oEmpenhoFinanceiro = $oEmpenho;
  }

  /**
   * Retorna o empenho financeiro
   * @return EmpenhoFinanceiro
   */
  public function getEmpenho() {
    return $this->oEmpenhoFinanceiro;
  }

  /**
   * @param Parcela $oParcela
   */
  public function setParcela(Parcela $oParcela) {
    $this->oParcela = $oParcela;
  }

  /**
   * @return Parcela
   */
  public function getParcela() {
    return $this->oParcela;
  }

  /**
   * @param ContaPlanoPCASP $oContaContabil
   */
  public function setContaCredito(ContaPlanoPCASP $oContaCredito) {
    $this->oContaCredito = $oContaCredito;
  }

  /**
   * @return ContaPlanoPCASP
   */
  public function getContaCredito() {
    return $this->oContaCredito;
  }

  /**
   * @param ContaPlanoPCASP $oContaDebito
   */
  public function setContaDebito(ContaPlanoPCASP $oContaDebito) {
    $this->oContaDebito = $oContaDebito;
  }

  /**
   * @return mixed
   */
  public function getContaDebito() {
    return $this->oContaDebito;
  }
}

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
require_once(modification("model/empenho/EmpenhoFinanceiro.model.php"));

/**
 * Model que executa os lancamentos auxiliares de uma liquidacao de empenho.
 * @author matheus felini
 * @package contabilidade
 * @subpackage lancamento
 * @version $Revision: 1.19 $
 */
class LancamentoAuxiliarEmpenhoLiquidacao extends LancamentoAuxiliarBase implements ILancamentoAuxiliar {

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
   * Sequencial da ordem de pagamento
   * @var integer
   */
  private $iCodigoOrdemPagameanto;


  /**
   * Retonar uma conta do plano (PCASP) para identificar a conta credito e debito do lancamento
   * @var integer
   */
  private $iCodigoContaPlano;


  /**
   * Caracteristica Peculiar da conta credito
   * @var string
   */
  private $sCaracteristicaPeculiarCredito;

  /**
   * Característica Peculiar da conta Débito
   * @var string
   */
  private $sCaracteristicaPeculiarDebito;

  /**
   * Empenho o qual está sendo liquidado
   * @var EmpenhoFinanceiro
   */
  private $oEmpenhoFinanceiro;

  /**
   * @var Parcela
   */
  private $oParcela;

  /**
   * Seta a característica peculiar da conta débito
   * @param string $sCaracteristicaPeculiarDebito
   */
  public function setCaracteristicaPeculiarDebito($sCaracteristicaPeculiarDebito) {
  	$this->sCaracteristicaPeculiarDebito = $sCaracteristicaPeculiarDebito;
  }

  /**
   * Retorna a característica peculiar da conta débito
   * @return string
   */
  public function getCaracteristicaPeculiarDebito() {
  	return $this->sCaracteristicaPeculiarDebito;
  }

  /**
   * Seta a característica peculiar da conta crédito
   * @param string $sCaracteristicaPeculiarCredito
   */
  public function setCaracteristicaPeculiarCredito($sCaracteristicaPeculiarCredito) {
  	$this->sCaracteristicaPeculiarCredito = $sCaracteristicaPeculiarCredito;
  }

  /**
   * Retorna a característica peculiar da conta crédito
   * @return string
   */
  public function getCaracteristicaPeculiarCredito() {
  	return $this->sCaracteristicaPeculiarCredito;
  }


  /**
   * Executa os lançamentos auxiliares de uma liquidacao de Empenho
   * @param int   $iCodigoLancamento
   * @param string $dtLancamento
   *
   * @return bool
   * @throws \BusinessException
   *
   * @todo fazer com que chame os métodos da classe LancamentoAuxiliarBase
   */
  public function executaLancamentoAuxiliar($iCodigoLancamento, $dtLancamento) {

    $this->iCodigoLancamento = $iCodigoLancamento;

    /**
     * Incluindo vinculo do Lançamento com Favorecido
     */
    $oDaoConLanCamCgm = db_utils::getDao('conlancamcgm');
    $oDaoConLanCamCgm->c76_codlan = $iCodigoLancamento;
    $oDaoConLanCamCgm->c76_numcgm = $this->getFavorecido();
    $oDaoConLanCamCgm->c76_data   = $dtLancamento;
    $oDaoConLanCamCgm->incluir($iCodigoLancamento);

    if ($oDaoConLanCamCgm->erro_status == 0) {

      $sErroMsg  = "Não foi possível incluir vinculo do lançamento com o Favorecido.\n\n";
      $sErroMsg .= "Erro Técnico: {$oDaoConLanCamCgm->erro_msg}";
      throw new BusinessException($sErroMsg);
    }

    /**
     * Incluindo vinculo do Lançamento com o Complemento (observação do histórico [conhist])
     */
    if ($this->getObservacaoHistorico() != '') {

      $oDaoConLanCamCompl              = db_utils::getDao('conlancamcompl');
      $oDaoConLanCamCompl->c72_codlan  = $iCodigoLancamento;
      $oDaoConLanCamCompl->c72_complem = $this->getObservacaoHistorico();
      $oDaoConLanCamCompl->incluir($iCodigoLancamento);

      if ($oDaoConLanCamCompl->erro_status == 0) {

        $sErroMsg = "Não foi possível incluir o complemento do lançamento.\n\n";
        $sErroMsg .= "Erro Técnico: {$oDaoConLanCamCompl->erro_msg}";
        throw new BusinessException($sErroMsg);
      }
    }

    /**
     * Grava o desdobramento da inscrição.
     */
    $oDaoConLancamEle             = db_utils::getDao('conlancamele');
    $oDaoConLancamEle->c67_codlan = $iCodigoLancamento;
    $oDaoConLancamEle->c67_codele = $this->getCodigoElemento();
    $oDaoConLancamEle->incluir($iCodigoLancamento);

    if ($oDaoConLancamEle->erro_status == 0) {

    	$sErroMsg  = "Não foi possível incluir o vínculo com o elemento.\n\n";
    	$sErroMsg .= "Erro Técnico: {$oDaoConLancamEle->erro_msg}";
    	throw new BusinessException($sErroMsg);
    }

    /**
     * Vínculo do empenho com o conlancam
     */

    $oDaoConLancamEmp = db_utils::getDao('conlancamemp');
    $oDaoConLancamEmp->c75_codlan = $iCodigoLancamento;
    $oDaoConLancamEmp->c75_numemp = $this->getNumeroEmpenho();
    $oDaoConLancamEmp->c75_data   = $dtLancamento;
    $oDaoConLancamEmp->incluir($iCodigoLancamento);
    if ($oDaoConLancamEmp->erro_status == 0) {

    	$sErroMsg  = "Não foi possível incluir o vínculo do lançamento com o empenho.\n\n";
    	$sErroMsg .= "Erro Técnico: {$oDaoConLancamEmp->erro_msg}";
    	throw new BusinessException($sErroMsg);
    }

    /**
     * Vinculo da dotacao com o lancamento
     * Só realizamos o vínculo caso o Empenho Financeiro não seja um RP (Restos à Pagar)
     */
    if ($this->getEmpenhoFinanceiro()->getAno() == db_getsession("DB_anousu")) {

      $oDaoConLancamDot = db_utils::getDao('conlancamdot');
      $oDaoConLancamDot->c73_codlan = $iCodigoLancamento;
      $oDaoConLancamDot->c73_anousu = db_getsession("DB_anousu");
      $oDaoConLancamDot->c73_coddot = $this->getCodigoDotacao();
      $oDaoConLancamDot->c73_data   = $dtLancamento;
      $oDaoConLancamDot->incluir($iCodigoLancamento);
      if ($oDaoConLancamDot->erro_status == 0) {

        $sErroMsg  = "Não foi possível incluir o vínculo da dotacão com o lançamento.\n\n";
        $sErroMsg .= "Erro Técnico: {$oDaoConLancamDot->erro_msg}";
        throw new BusinessException($sErroMsg);
      }
    }

    /**
     * Vinculo da ordem de pagamento com o lançamento
     */
    if ($this->getCodigoOrdemPagamento() != '') {

      $oDaoConLancamOrd             = db_utils::getDao('conlancamord');
      $oDaoConLancamOrd->c80_codlan = $iCodigoLancamento;
      $oDaoConLancamOrd->c80_codord = $this->getCodigoOrdemPagamento();
      $oDaoConLancamOrd->c80_data   = $dtLancamento;
      $oDaoConLancamOrd->incluir($iCodigoLancamento);
      if ($oDaoConLancamOrd->erro_status == 0) {

        $sErroMsg = "Não foi possível incluir o vínculo da ordem de pagamento com o lançamento.\n\n";
        $sErroMsg .= "Erro Técnico: {$oDaoConLancamDot->erro_msg}";
        throw new BusinessException($sErroMsg);
      }
    }

    /**
     * Vinculo da nota de liquidacao com o lançamento
     */
    if ($this->getCodigoNotaLiquidacao() != '') {

      $oDaoConLancamNota              = db_utils::getDao('conlancamnota');
      $oDaoConLancamNota->c66_codlan  = $iCodigoLancamento;
      $oDaoConLancamNota->c66_codnota = $this->getCodigoNotaLiquidacao();
      $oDaoConLancamNota->incluir($iCodigoLancamento, $this->getCodigoNotaLiquidacao());
      if ($oDaoConLancamNota->erro_status == 0) {

        $sErroMsg = "Não foi possível incluir o vínculo da nota de liquidacao com o lançamento.\n\n";
        $sErroMsg .= "Erro Técnico: {$oDaoConLancamDot->erro_msg}";
        throw new BusinessException($sErroMsg);
      }
    }

    $this->salvarVinculoParcelaRegimeDeCompetencia();

    return true;
  }

  /**
   * @return bool
   * @throws BusinessException
   */
  protected function salvarVinculoParcelaRegimeDeCompetencia() {

    if (empty($this->oParcela)) {
      return true;
    }

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
   * Seta o codigo da nota de liquidacao
   * @param integer $iCodigoNotaLiquidacao
   */
  public function setCodigoNotaLiquidacao($iCodigoNotaLiquidacao) {
    $this->iCodigoNotaLiquidacao = $iCodigoNotaLiquidacao;
  }

  /**
   * Retorna o codigo da nota de liquidacao
   * @return integer
   */
  public function getCodigoNotaLiquidacao() {
    return $this->iCodigoNotaLiquidacao;
  }

  /**
   * Seta o codigo da ordem de pagamento
   * @param integer $iCodigoOrdemPagameanto
   */
  public function setCodigoOrdemPagamento($iCodigoOrdemPagameanto){
    $this->iCodigoOrdemPagameanto = $iCodigoOrdemPagameanto;
  }

  /**
   * Retorna o codigo da ordem de pagamento
   * @return integer
   */
  public function getCodigoOrdemPagamento() {
    return $this->iCodigoOrdemPagameanto;
  }

  /**
   * Seta o codigo da dotacao
   * @param integer $iCodigoDotacao
   */
  public function setCodigoDotacao($iCodigoDotacao) {
    $this->iCodigoDotacao = $iCodigoDotacao;
  }

  /**
   * Retorna o codigo da dotacao
   * @return integer
   */
  public function getCodigoDotacao() {
    return $this->iCodigoDotacao;
  }

  /**
   * Seta o numero do empenho
   * @param integer $iNumeroEmpenho
   */
  public function setNumeroEmpenho($iNumeroEmpenho) {
    $this->iNumeroEmpenho = $iNumeroEmpenho;
  }

  /**
   * Retorna o numero do empenho
   * @return integer
   */
  public function getNumeroEmpenho() {
    return $this->iNumeroEmpenho;
  }

  /**
   * Seta o favorecido CGM
   * @param integer $iFavorecido
   */
  public function setFavorecido($iFavorecido) {
    $this->iFavorecido = $iFavorecido;
  }

  /**
   * Retorna o favorecido CGM
   * @return integer
   */
  public function getFavorecido() {
  	return $this->iFavorecido;
  }

  /**
   * Seta o codigo do elemento
   * @param integer $iCodigoElemento
   */
  public function setCodigoElemento($iCodigoElemento) {
  	$this->iCodigoElemento = $iCodigoElemento;
  }

  /**
   * Retorna o codigo do elemento
   * @return integer
   */
  public function getCodigoElemento() {
  	return $this->iCodigoElemento;
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
   * @see ILancamentoAuxiliar::setObservacaoHistorico()
   */
  public function setObservacaoHistorico($sObservacaoHistorico) {
    $this->sObservacao = $sObservacaoHistorico;
  }
  /**
   * @see ILancamentoAuxiliar::getObservacaoHistorico()
   */
  public function getObservacaoHistorico() {
    return $this->sObservacao;
  }

  /**
   * Atribui uma conta do plano (PCASP) para identificar a conta credito e debito do lancamento
   * @param integer $iContaPlano
   */
  public function setCodigoContaPlano($iContaPlano) {

    $this->iCodigoContaPlano = $iContaPlano;
  }

  /**
   * Retonar uma conta do plano (PCASP) para identificar a conta credito e debito do lancamento
   * @return integer $iContaPlano
   */
  public function getCodigoContaPlano() {
    return $this->iCodigoContaPlano;
  }

  /**
   * Seta um empenho financeiro
   * @param EmpenhoFinanceiro
   */
  public function setEmpenhoFinanceiro(EmpenhoFinanceiro $oEmpenhoFinanceiro) {
    $this->oEmpenhoFinanceiro = $oEmpenhoFinanceiro;
  }

  /**
   * Retorna uma instancia do objeto EmpenhoFinanceiro
   * @return EmpenhoFinanceiro $oEmpenhoFinanceiro
   */
  public function getEmpenhoFinanceiro() {

    if (!empty($this->iNumeroEmpenho) && empty($this->oEmpenhoFinanceiro) ) {
      $this->oEmpenhoFinanceiro = new EmpenhoFinanceiro($this->iNumeroEmpenho);
    }
    return $this->oEmpenhoFinanceiro;
  }

  /**
   * @param Parcela $oParcela
   */
  public function setParcelaRegimeDeCompetencia(Parcela $oParcela) {
    $this->oParcela = $oParcela;
  }

  /**
   * @param Parcela $oParcela
   * @return mixed
   */
  public function getParcelaRegimeDeCompetencia(Parcela $oParcela) {
    return $this->oParcela;
  }

  /**
   * Função da classe que constroi uma instância de LancamentoAuxiliarEmpenhoLiquidacao,
   * de acordo com código do lançamento, passado como parâmetro
   * @param $iCodigoLancamento
   *
   * @return LancamentoAuxiliarEmpenhoLiquidacao
   * @throws BusinessException
   * @throws Exception
   */
  public static function getInstance($iCodigoLancamento) {

    $oDaoConlancam  = db_utils::getDao("conlancam");
    $sSqlLancamento = $oDaoConlancam->sql_query_empenho($iCodigoLancamento, " distinct c70_valor, c75_numemp, c67_codele");
    $rsLancamento   = $oDaoConlancam->sql_record($sSqlLancamento);

    if ($oDaoConlancam->numrows != 1) {
      throw new BusinessException("Erro ao buscar os dados do lançamento.");
    }

    $oLancamento = db_utils::fieldsMemory($rsLancamento, 0);
    $iAnoSessao  = db_getsession("DB_anousu");

    if (USE_PCASP) {

      $oPlanoContaOrcamento = new ContaOrcamento( $oLancamento->c67_codele, $iAnoSessao, null, db_getsession("DB_instit") );
      $oPlanoConta          = $oPlanoContaOrcamento->getPlanoContaPCASP();

      if (empty($oPlanoConta)) {
        throw new Exception("Conta do orçamento {$oPlanoContaOrcamento->getEstrutural()} no ano {$iAnoSessao}");
      }

    } else {
      $oPlanoConta = new ContaPlanoPCASP($oLancamento->c67_codele, $iAnoSessao, null, db_getsession("DB_instit"));
    }

    $oEmpenhoFinanceiro = new EmpenhoFinanceiro($oLancamento->c75_numemp);
    $oLancamentoAuxiliar = new LancamentoAuxiliarEmpenhoLiquidacao();
    $oLancamentoAuxiliar->setValorTotal($oLancamento->c70_valor);
    $oLancamentoAuxiliar->setEmpenhoFinanceiro($oEmpenhoFinanceiro);
    $oLancamentoAuxiliar->setCodigoContaPlano($oPlanoConta->getReduzido());

    /**
     * Dados para o conta corrente despesa e recurso
     */
    $oContaCorrenteDetalhe = new ContaCorrenteDetalhe();
    $oContaCorrenteDetalhe->setEmpenho($oEmpenhoFinanceiro);
    $oContaCorrenteDetalhe->setRecurso($oEmpenhoFinanceiro->getDotacao()->getDadosRecurso());
    $oContaCorrenteDetalhe->setDotacao($oEmpenhoFinanceiro->getDotacao());
    $oLancamentoAuxiliar->setContaCorrenteDetalhe($oContaCorrenteDetalhe);

    return $oLancamentoAuxiliar;
  }
}

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

require_once ("interfaces/ILancamentoAuxiliar.interface.php");
require_once ("model/contabilidade/lancamento/LancamentoAuxiliarBase.model.php");

/**
 * Model reponsavel por realizar os lancamentos auxiliares para um empenho
 * @author     Matheus Felini <matheus.felini@dbseller.com.br>
 * @package    contabilidade
 * @subpackage lancamento
 * @version    $Revision: 1.21 $
 */
class LancamentoAuxiliarEmpenho extends LancamentoAuxiliarBase implements ILancamentoAuxiliar {

  /**
   * Valor total do Lancamento
   * @var float
   */
  private $nValorTotal;

  /**
   * Codigo do historico
   * @var integer
   */
  private $iCodigoHistorico;

  /**
   * Observacao / Complemento
   * @var string
   */
  private $sObservacaoHistorico;

  /**
   * Conta Credito
   * @var integer
   */
  private $iContaCredito;

  /**
   * Conta Debito
   * @var integer
   */
  private $iContaDebito;

  /**
   * Informa que o empenho eh um empenho de prestacao de contas.
   * @var boolean
   */
  private $isPrestacaoContas = false;

  /**
   * Caracteristica Peculiar
   * @var string
   */
  private $sCaracteristicaPeculiar;

  /**
   * sequencial do código do empenho
   * @var integer
   */
  private $iEmpenho;

  /**
   * sequencial do numero do cgm
   * @var integer
   */
  private $iCgm;

  /**
   * Codigo do recurso
   * @var integer
   */
  private $iCodigoRecurso;

  /**
   * codigo do contrato
   * @var integer
   */
  private $iCodigoContrato;

  /**
   * objeto empenho financeiro
   * @var object empenhoFinanceiro
   */
  protected $oEmpenhoFinanceiro;

  /**
   * Código da Ordem de Pagamento
   * @var integer
   */
  private $iCodigoOrdemPagamento;


  /**
   * Executa o Lancamento auxiliar
   * @param int  $iCodigoLancamento Código do lancamento contabil
   * @param date $dtLancamento data do Lancamento
   * @return bool
   * @throws BusinessException
   */
  public function executaLancamentoAuxiliar($iCodigoLancamento, $dtLancamento)  {

    parent::setCodigoLancamento($iCodigoLancamento);
    parent::setDataLancamento($dtLancamento);
    parent::salvarVinculoComplemento();
    parent::salvarVinculoCgm();
    parent::salvarVinculoElemento();
    parent::salvarVinculoEmpenho();
    parent::salvarVinculoDotacao();

    $this->salvarVinculoCaracteristicaPeculiar();

    if (!empty($this->iCodigoOrdemPagamento)) {
      $this->salvarVinculoOrdemDePagamento();
    }

    return true;
  }

  /**
   * Vincula a caracteristica peculiar de um empenho
   * @throws BusinessException
   * @return boolean true
   */
  protected function salvarVinculoCaracteristicaPeculiar() {

    $oDaoLancamentoCaracteristica                     = db_utils::getDao("conlancamconcarpeculiar");
    $oDaoLancamentoCaracteristica->c08_sequencial     = null;
    $oDaoLancamentoCaracteristica->c08_codlan         = $this->iCodigoLancamento;
    $oDaoLancamentoCaracteristica->c08_concarpeculiar = $this->sCaracteristicaPeculiar;
    $oDaoLancamentoCaracteristica->incluir(null);
    if ($oDaoLancamentoCaracteristica->erro_status == "0") {
      throw new BusinessException("Não foi possível vincular a caracteristica peculiar do empenho.");
    }
    return true;
  }

  /**
   * Vincula o lançamento com uma ordem de pagamento
   *
   * @throws BusinessException
   * @return boolean - true em caso de sucesso
   */
  protected function salvarVinculoOrdemDePagamento() {

    $oDaoLancamentoOrdemPagamento             = db_utils::getDao('conlancamord');
    $oDaoLancamentoOrdemPagamento->c80_codlan = $this->iCodigoLancamento;
    $oDaoLancamentoOrdemPagamento->c80_codord = $this->iCodigoOrdemPagamento;
    $oDaoLancamentoOrdemPagamento->c80_data   = $this->dtLancamento;
    $oDaoLancamentoOrdemPagamento->incluir($this->iCodigoLancamento);
    if ($oDaoLancamentoOrdemPagamento->erro_status == "0") {
      throw new BusinessException("Não foi possível vincular o lançamento com a ordem de pagamento.");
    }
    return true;
  }

  /**
   * Seta valor para a caracteristica peculiar do empenho
   * @param string $sCaracteristicaPeculiar
   */
  public function setCaracteristicaPeculiar($sCaracteristicaPeculiar) {
    $this->sCaracteristicaPeculiar = $sCaracteristicaPeculiar;
  }

  /**
   * Seta o valor total do evento
   * @param float $nValorTotal
   */
  public function setValorTotal($nValorTotal) {
    $this->nValorTotal = $nValorTotal;
  }

  /**
   * Retorna o valor total
   * @return float $nValorTotal
   */
  public function getValorTotal() {
    return $this->nValorTotal;
  }

  /**
   * Retorna o histórico da operação
   */
  public function getHistorico() {
    return $this->iCodigoHistorico;
  }

  /**
   * Seta o histórico da operação
   * @param integer $iHistorico
   */
  public function setHistorico($iHistorico) {
    $this->iCodigoHistorico = $iHistorico;
  }

  /**
   * Retorna a observação do histórico da operação
   */
  public function getObservacaoHistorico() {
    return $this->sObservacaoHistorico;
  }

  /**
   * Seta a observação do histórico da operação
   * @param string $sObservacaoHistorico
   */
  public function setObservacaoHistorico($sObservacaoHistorico) {
    $this->sObservacaoHistorico = $sObservacaoHistorico;
  }

  /**
   * Seta a conta credito
   * @param integer $iContaCredito
   */
  public function setContaCredito($iContaCredito) {
    $this->iContaCredito = $iContaCredito;
  }

  /**
   * Retorna a conta credito
   * @return integer
   */
  public function getContaCredito() {
    return $this->iContaCredito;
  }

  /**
   * Seta a conta debito
   * @param integer $iContaDebito
   */
  public function setContaDebito($iContaDebito) {
    $this->iContaDebito = $iContaDebito;
  }

  /**
   * Retorna a conta debito
   * @return integer
   */
  public function getContaDebito() {
    return $this->iContaDebito;
  }

  /**
   * Seta o tipo de empenho como prestacao de contas
   * @param boolean
   */
  public function setPrestacaoContas($lPrestacao) {

    $this->isPrestacaoContas = $lPrestacao;
  }

  /**
   * Seta o valor Empenho
   * @param integer $iEmpenho
   */
  public function setEmpenho($iEmpenho) {
    $this->iEmpenho = $iEmpenho;
  }

  /**
   * Retorna o $iEmpenho
   * @return integer $iEmpenho
   */
  public function getEmpenho() {
    return $this->iEmpenho;
  }

  /**
   * Seta o Cgm
   * @param integer $iCgm
   */
  public function setCgm($iCgm) {
    $this->iCgm = $iCgm;
  }

  /**
   * Retorna o codigo do cgm
   * @return integer $iCgm
   */
  public function getCgm() {
    return $this->iCgm;
  }


  /**
   * Seta o codigo da dotacao
   * @param integer $iCodigoDotacao
   */
  public function setDotacao($iCodigoDotacao) {
    parent::setCodigoDotacao($iCodigoDotacao);
  }

  /**
   * Retorna o codigo da dotacao
   * @return integer parent::$iCodigoDotacao
   */
  public function getDotacao() {
    return parent::getCodigoDotacao();
  }

  /**
   * Seta codigo do elemento
   * @param integer $iCodigoElemento
   */
  public function setElemento($iCodigoElemento) {
    parent::setCodigoElemento($iCodigoElemento);
  }

  /**
   * Retorna o codigo do elemento
   * @return integer $iCodigoElemento
   */
  public function getElemento() {
    return parent::getCodigoElemento();
  }

  /**
   * Seta o recurso do lancamento
   * Utilizado para realizar a criacao da conta-corrente
   * @param integer $iRecurso
   */
  public function setCodigoRecurso($iRecurso) {
    $this->iCodigoRecurso = $iRecurso;
  }

  /**
   * Retorna o Recurso
   * @return integer codigo do recurso
   */
  public function getCodigoRecurso() {
    return $this->iCodigoRecurso;
  }

  /**
   * Retorna a característica peculiar do empenho
   * @return string
   */
  public function getCaracteristicaPeculiar() {
    return $this->sCaracteristicaPeculiar;
  }

  /**
   * Define o codigo do Contrato
   * @param integer $iAcordo Codigo do contrato
   */
  public function setAcordo($iAcordo) {
    $this->iCodigoContrato = $iAcordo;
  }

  /**
   * Retorna codigo do Contrato
   *
   * @param integer $iAcordo Código do contrato
   * @return int
   */
  public function getAcordo() {
    return $this->iCodigoContrato;
  }
  /**
   * Seta o empenho financeiro
   * @param EmpenhoFinanceiro $oEmpenhoFinanceiro
   */
  public function setEmpenhoFinanceiro(EmpenhoFinanceiro $oEmpenhoFinanceiro){
    $this->oEmpenhoFinanceiro = $oEmpenhoFinanceiro;
  }

  /**
   * Retorna o empenho financeiro
   * @return EmpenhoFinanceiro
   */
  public function getEmpenhoFinanceiro() {

    if (!empty($this->iNumeroEmpenho)) {
      $this->oEmpenhoFinanceiro = new EmpenhoFinanceiro($this->iNumeroEmpenho);
    }
    return $this->oEmpenhoFinanceiro;
  }

  /**
   * Seta o código da Ordem de Pagamento
   * @param integer $iCodigoOrdemPagamento Código da Ordem
   */
  public function setCodigoOrdemPagamento($iCodigoOrdemPagamento) {
    $this->iCodigoOrdemPagamento = $iCodigoOrdemPagamento;
  }

  /**
   * Função da classe que constroi uma instância de LancamentoAuxiliarEmpenho,
   * de acordo com código do lançamento, passado como parâmetro
   *
   * @param  integer $iCodigoLancamento
   * @throws BusinessException
   * @throws Exception
   * @return LancamentoAuxiliarAcordo
   */
  public static function getInstance($iCodigoLancamento) {

    $oDaoConlancamEmp = db_utils::getDao("conlancamemp");
    $sSql             = $oDaoConlancamEmp->sql_query_dadoslancamento(null, "*", null, "c70_codlan = {$iCodigoLancamento}");
    $rsEmpenho        = $oDaoConlancamEmp->sql_record($sSql);

    if ($oDaoConlancamEmp->numrows == 0) {
      throw new BusinessException("Vinculo do lançamento {$iCodigoLancamento} com o suprimento não encontrado.");
    }

    $oDadosEmpenho = db_utils::fieldsMemory($rsEmpenho, 0);

    $oLancamentoAuxiliar = new LancamentoAuxiliarEmpenho();
    $oLancamentoAuxiliar->setCodigoLancamento($iCodigoLancamento);
    $oLancamentoAuxiliar->setDataLancamento($oDadosEmpenho->c70_data);

    $sObservacao = $oDadosEmpenho->c72_complem;

    if ( empty($oDadosEmpenho->c72_complem) ) {
      $sObservacao = 'Lançamento de reprocessamento';
    }

    $oEmpenho = EmpenhoFinanceiroRepository::getEmpenhoFinanceiroPorNumero($oDadosEmpenho->e60_numemp);

    $oLancamentoAuxiliar->setObservacaoHistorico($sObservacao);

    $oLancamentoAuxiliar->setFavorecido($oDadosEmpenho->c76_numcgm);
    $oLancamentoAuxiliar->setCaracteristicaPeculiar($oDadosEmpenho->e60_concarpeculiar);
    $oLancamentoAuxiliar->setNumeroEmpenho($oDadosEmpenho->e60_numemp);
    $oLancamentoAuxiliar->setValorTotal($oDadosEmpenho->c70_valor);
    $oLancamentoAuxiliar->setElemento($oDadosEmpenho->c67_codele);

    /**
     * Dados para conta corrente credor
     */
    $oContaCorrenteDetalhe = new ContaCorrenteDetalhe();
    $oContaCorrenteDetalhe->setCredor(CgmFactory::getInstanceByCgm($oDadosEmpenho->c76_numcgm));
    $oContaCorrenteDetalhe->setEmpenho($oEmpenho);
    $oContaCorrenteDetalhe->setDotacao($oEmpenho->getDotacao());
    $oContaCorrenteDetalhe->setRecurso($oEmpenho->getDotacao()->getDadosRecurso());
    $oLancamentoAuxiliar->setContaCorrenteDetalhe($oContaCorrenteDetalhe);

    return $oLancamentoAuxiliar;
  }

}

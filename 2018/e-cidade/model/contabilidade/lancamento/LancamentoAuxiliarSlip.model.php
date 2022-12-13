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

require_once("interfaces/ILancamentoAuxiliar.interface.php");
require_once("model/contabilidade/lancamento/LancamentoAuxiliarBase.model.php");

define("URL_LANCAMENTO_AUXILIAR_SLIP", "financeiro.contabilidade.LancamentoAuxiliarSlip.");

/**
 * Executa os lançamentos contabeis auxiliares de um SLIP
 * @author Matheus Felini
 * @package contabilidade
 * @subpackage lancamento
 * @version $Revision: 1.15 $
 */
class LancamentoAuxiliarSlip  extends LancamentoAuxiliarBase implements ILancamentoAuxiliar {

  /**
   * Codigo do historico
   * @var integer
   */
  private $iCodigoHistorico;

  /**
   * Valor total do slip
   * @var floar
   */
  private $nValorTotal;

  /**
   * Texto de observações
   * @var string
   */
  private $sObservacaoHistorico;

  /**
   * Codigo do SLIP
   * @var integer
   */
  private $iCodigoSlip;

  /**
   * Codigo da Conta Credito
   * @var integer
   */
  private $iCodigoReduzido;

  /**
   * Codigo CGM do favorecido
   * @var integer
   */
  private $iCodigoFavorecido;

  /**
   * Define se o lançamento é um estorno
   * @var boolean
   */
  private $lEstorno = false;

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
   * Codigo do terminal da autenticacao
   * @var integer
   */
  private $iIDTerminal;

  /**
   * numero sequencial da autenticao
   * @var integer
   */
  private $iNumeroAutenticacao;

  /**
   * data da autenticao
   * @var string
   */
  private $sDataAutenticacao;

  /**
   * Executa o lancamento de registros em tabelas auxiliares da contabilidade
   * @see ILancamentoAuxiliar::executaLancamentoAuxiliar()
   */
  public function executaLancamentoAuxiliar($iCodigoLancamento, $dtLancamento) {

    $this->setCodigoLancamento($iCodigoLancamento);
    $this->setDataLancamento($dtLancamento);
    $this->iFavorecido = $this->getFavorecido();    

    $this->salvarVinculoSlip();
    $this->salvarVinculoComplemento();
    $this->salvarVinculoCgm();  

    $this->salvarVinculoPagamento();
    $this->salvarVinculoCorrente();

    return true;
  }

  /**
   * Retorna a instância do lançamento pelo código
   * @param  integer $iCodigoLancamento
   * @return LancamentoAuxiliarSlip
   */
  public static function getInstance($iCodigoLancamento) {

    $oDaoLancamentoSlip = db_utils::getDao('conlancamslip');
    $sSqlLancamentoSlip = $oDaoLancamentoSlip->sql_query_lancamento_slip($iCodigoLancamento);
    $rsLancamentoSlip = $oDaoLancamentoSlip->sql_record($sSqlLancamentoSlip);

    if ($oDaoLancamentoSlip->erro_status == "0") {
      throw new BusinessException (_M (URL_LANCAMENTO_AUXILIAR_SLIP. "erro_lancamento_slip"));
    }
    
    $oLancamentoSlip         = db_utils::fieldsMemory($rsLancamentoSlip, 0);
    $oLancamentoAuxiliarSlip = new LancamentoAuxiliarSlip();
    $oSlisp                  = new slip($oLancamentoSlip->k17_codigo); 
    
    $oLancamentoAuxiliarSlip->setIDTerminal($oLancamentoSlip->c86_id);
    $oLancamentoAuxiliarSlip->setDataAutenticacao($oLancamentoSlip->c86_data);
    $oLancamentoAuxiliarSlip->setNumeroAutenticacao($oLancamentoSlip->c86_autent);
    $oLancamentoAuxiliarSlip->setValorTotal($oLancamentoSlip->c70_valor);
    $oLancamentoAuxiliarSlip->setObservacaoHistorico($oLancamentoSlip->c72_complem);
    $oLancamentoAuxiliarSlip->setCodigoSlip($oLancamentoSlip->k17_codigo);
    $oLancamentoAuxiliarSlip->setCodigoReduzido($oLancamentoSlip->c82_reduz);
    $oLancamentoAuxiliarSlip->setFavorecido($oLancamentoSlip->c76_numcgm);
    $oLancamentoAuxiliarSlip->setDataLancamento($oLancamentoSlip->c70_data);
    $oLancamentoAuxiliarSlip->setCodigoLancamento($iCodigoLancamento);
    $oLancamentoAuxiliarSlip->setCaracteristicaPeculiarCredito($oSlisp->getCaracteristicaPeculiarCredito());
    $oLancamentoAuxiliarSlip->setCaracteristicaPeculiarDebito ($oSlisp->getCaracteristicaPeculiarDebito());

    $oContaPlano           = ContaPlanoPCASPRepository::getContaPorReduzido($oLancamentoSlip->c82_reduz, $oLancamentoSlip->c70_anousu);
    $oContaCorrenteDetalhe = new ContaCorrenteDetalhe();
    $oContaCorrenteDetalhe->setRecurso(new Recurso($oContaPlano->getRecurso()));
    $oContaCorrenteDetalhe->setContaBancaria($oContaPlano->getContaBancaria());
    if (!empty($oLancamentoSlip->c76_numcgm)) {
      $oContaCorrenteDetalhe->setCredor(CgmFactory::getInstanceByCgm($oLancamentoSlip->c76_numcgm));
    }
    $oLancamentoAuxiliarSlip->setContaCorrenteDetalhe($oContaCorrenteDetalhe);
    return $oLancamentoAuxiliarSlip;
  }

  private function salvarVinculoSlip() {

    $oDaoConLancamSlip = db_utils::getDao('conlancamslip');
    $oDaoConLancamSlip->c84_conlancam = $this->iCodigoLancamento;
    $oDaoConLancamSlip->c84_slip      = $this->getCodigoSlip();
    $oDaoConLancamSlip->incluir($this->iCodigoLancamento);
    if ($oDaoConLancamSlip->erro_status == 0) {

      $sMensagemErro  = "Não foi possível vincular o slip ao lançamento.\n\n";
      $sMensagemErro .= "Erro Técnico: {$oDaoConLancamSlip->erro_msg}";
      throw new BusinessException($sMensagemErro);
    }
  }

  private function salvarVinculoPagamento() {

    $oDaoConLancamPag = db_utils::getDao('conlancampag');
    $oDaoConLancamPag->c82_codlan = $this->iCodigoLancamento;
    $oDaoConLancamPag->c82_anousu = db_getsession("DB_anousu");
    $oDaoConLancamPag->c82_reduz  = $this->getCodigoReduzido();
    $oDaoConLancamPag->incluir($this->iCodigoLancamento);

    if ($oDaoConLancamPag->erro_status == 0) {

      $sErroMsg  = "Não foi possível incluir o pagamento do lançamento.\n\n";
      $sErroMsg .= "Erro Técnico: {$oDaoConLancamPag->erro_msg}";
      throw new BusinessException($sErroMsg);
    }
  }

  private function salvarVinculoCorrente() {

    $oDaoConlancamCorrente = db_utils::getDao('conlancamcorrente');
    $oDaoConlancamCorrente->c86_id         = $this->getIDTerminal();
    $oDaoConlancamCorrente->c86_data       = $this->getDataAutenticacao();
    $oDaoConlancamCorrente->c86_autent     = $this->getNumeroAutenticacao();
    $oDaoConlancamCorrente->c86_conlancam  = $this->iCodigoLancamento;

    $oDaoConlancamCorrente->incluir(null);
    if ($oDaoConlancamCorrente->erro_status == 0) {

      $sData = implode("/",array_reverse(explode("-",$this->getDataAutenticacao())));
      $sMensagemErro  = "Não foi possível vincular os dados da autenticação. \n\n ";
      $sMensagemErro .= "Data : {$sData} Terminal : {$this->getIDTerminal()} ";
      $sMensagemErro .= "Autenticação : {$this->getNumeroAutenticacao()}  ao lançamento.\n\n";
      $sMensagemErro .= "Erro Técnico: {$oDaoConlancamCorrente->erro_msg}";
      throw new BusinessException($sMensagemErro);
    }
  }

  /**
   * Seta o código do slip
   * @param integer $iCodigoSlip
   */
  public function setCodigoSlip($iCodigoSlip) {
    $this->iCodigoSlip = $iCodigoSlip;
  }

  /**
   * Retorna o código do slip
   * @return integer
   */
  public function getCodigoSlip() {
    return $this->iCodigoSlip;
  }

  /**
   * Seta o código do favorecido
   * @param integer $iCodigoFavorecido
   */
  public function setFavorecido($iCodigoFavorecido) {
  	$this->iCodigoFavorecido = $iCodigoFavorecido;
  }

  /**
   * Retorna o código do favorecido
   * @return integer
   */
  public function getFavorecido() {
  	return $this->iCodigoFavorecido;
  }

  /**
   * Seta o codigo reduzido
   * @return integer
   */
  public function setCodigoReduzido($iCodigoReduzido) {
  	$this->iCodigoReduzido = $iCodigoReduzido;
  }

  /**
   * Retorna o código reduzido
   * @return integer
   */
  public function getCodigoReduzido() {
  	return $this->iCodigoReduzido;
  }

  /**
   * Seta a observação do histórico da operação
   * @param string $sObservacaoHistorico
   */
  public function setObservacaoHistorico($sObservacaoHistorico) {
    $this->sObservacaoHistorico = $sObservacaoHistorico;
  }

  /**
   * Retorna a observacao do historico
   * @see ILancamentoAuxiliar::getObservacaoHistorico()
   */
  public function getObservacaoHistorico() {
    return $this->sObservacaoHistorico;
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

  /**
   * Define valor para quando o lançamento for estorno
   * @param boolean $lEstorno
   */
  public function setEstorno($lEstorno) {
    $this->lEstorno = $lEstorno;
  }

  /**
   * Retorna o valor da propriedade $lEstorno
   * @return boolean
   */
  public function isEstorno() {
    return $this->lEstorno;
  }

  /**
   * Vincula um código de slip com um código de inscricao
   * @param integer $iCodigoSlip
   * @param integer $iCodigoLancamento
   * @throws BusinessException
   *
   *
   * @todo remover o método deste model e incluir ele no model InscricaoPassivoOrcamento
   */
  static function vinculaSlipInscricao ($iCodigoSlip, $iCodigoInscricao) {

    $oDaoInscricaopassivoslip = db_utils::getDao('inscricaopassivoslip');
    $oDaoInscricaopassivoslip->c109_slip             = $iCodigoSlip;
    $oDaoInscricaopassivoslip->c109_inscricaopassiva = $iCodigoInscricao;
    $oDaoInscricaopassivoslip->incluir(null);

    if ($oDaoInscricaopassivoslip->erro_status == 0) {

      $sMensagemErro  = "Não foi possível vincular o slip à Inscrição.\n\n";
      $sMensagemErro .= "Erro Técnico: {$oDaoInscricaopassivoslip->erro_msg}";
      throw new BusinessException($sMensagemErro);
    }
  }

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
   * Define a data de autenticacao do SLIP
   * @param string $sDataAutenticacao data da autenticacao
   */
  public function setDataAutenticacao($sDataAutenticacao) {
    $this->sDataAutenticacao = $sDataAutenticacao;
  }

  /**
   * retorna a data de autenticacao do SLIP
   * @return string data de autenticacao
   */
  public function getDataAutenticacao() {
    return $this->sDataAutenticacao;
  }

  /**
   * Define o Numero autenticacao do SLIP
   * @param integer $iNumeroAutenticacao numero da autenticacao
   */
  public function setNumeroAutenticacao($iNumeroAutenticacao) {
    $this->iNumeroAutenticacao = $iNumeroAutenticacao;
  }

  /**
   * retorna  numero da autenticacao
   * @return integer numero da autenticacao
   */
  public function getNumeroAutenticacao() {
    return $this->iNumeroAutenticacao;
  }

  /**
   * Define o o id do terminal da autenticacao do SLIP
   * @param integer $iIDTerminal o id do terminal da autenticacao
   */
  public function setIDTerminal($iIDTerminal) {
    $this->iIDTerminal = $iIDTerminal;
  }

  /**
   * retorna o id do terminal da autenticacao
   * @return integer id do terminal autenticacao
   */
  public function getIDTerminal() {
    return $this->iIDTerminal;
  }
}
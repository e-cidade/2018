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

require_once "interfaces/ILancamentoAuxiliar.interface.php";
require_once "model/contabilidade/lancamento/LancamentoAuxiliarBase.model.php";
/**
 * Lancamentos auxiliares de uma arrecadação de receita
 * @author matheus.felini
 * @package contabilidade
 * @subpackage lancamento
 * @version $Revision: 1.12 $
 */
class LancamentoAuxiliarArrecadacaoReceita extends LancamentoAuxiliarBase implements ILancamentoAuxiliar {

  /**
   * Valor total do lancamento
   * @var float
   */
  protected $nValorTotal;

  /**
   * Codigo historico do lancamento
   * @var integer
   */
  protected $iCodigoHistorico;

  /**
   * Complemento do lancamento
   * @var string
   */
  protected $sObservacao;

  /**
   * Codigo da conta a debitar/creditar
   * @var integer
   */
  protected $iCodigoConta = 0;

  /**
   * Codigo da receita
   * @var integer
   */
  protected $iCodigoReceita;

  /**
   * Codigo do documento
   * @var integer
   */
  protected $iCodigoDocumento;

  /**
   * Mes do lancamento
   * @var integer
   */
  protected $iMes;

  /**
   * Codigo do favorecido
   * @var integer
   */
  protected $iCodigoCGM;

  /**
   * Conta credito do lancamento
   * @var integer
   */
  protected $iContaCredito;

  /**
   * Coddigo da conta do orcamento
   * @var integer
   */
  protected $iCodigoContaOrcamento;

  /**
   * Conta debito do lancamento
   * @var integer
   */
  protected $iContaDebito;

  /**
   * Codigo do grupo corrente
   * @var integer
   */
  protected $iCodigoGrupoCorrente;

  /**
   * Variavel de controle que informa se o lancamento e um estorno ou nao
   * @var boolean
   */
  protected $lEstorno = false;

  /**
   * Característica Peculiar da Receita
   * @var string
   */
  protected $sCaracteristicaPeculiar;

  /**
   * Codigo do Recurso do empenho para conta corrente
   * @var integer
   */
  protected $iCodigoRecurso;
  
  
  /**
   * Codigo autenticacao em determinado dia
   * @var integer
   */
  protected $iIdAutenticacao;
  
  /**
   * data autenticacao
   * @var date
   */
  protected $dtDataAutenticacao;
  
  /**
   * Codigo autenticadora
   * @var integer
   */
  protected $iAutenticadora;

  /**
   * Variável de controle para sabermos se é uma arrecadação de prestação de contas ou não
   * @var boolean
   */
  protected $lArrecadacaoPrestacaoContas = false;

  /**
   * Executa os lancametos auxiliares
   * @see ILancamentoAuxiliar::executaLancamentoAuxiliar()
   */
  public function executaLancamentoAuxiliar($iCodigoLancamento, $dtLancamento) {

    $iAnoSessao = db_getsession('DB_anousu');
    $this->iCodigoLancamento = $iCodigoLancamento;
    $this->dtLancamento      = $dtLancamento;

    if ($this->sObservacao == "") {
      $this->sObservacao = "Lancamento contábil da arrecadação de receita";
    }
    $this->salvarVinculoComplemento();
    $this->salvarVinculoCaracteristicaPeculiar();
    $this->salvarVinculoContaPagadora();
    $this->salvarVinculoGrupoContaCorrente();
    $this->salvarVinculoReceita();
    $this->salvarVinculoCGM();
    $this->agruparValoresReceita();
    $this->salvarVinculoArrecadacao();

    if ($this->getNumeroEmpenho() != "") {
      $this->salvarVinculoEmpenho();
    }
    return true;
  }

  /**
   * Seta o codigo do grupo
   * @param integer $iCodigoGrupoCorrente
   */
  public function setCodigoGrupoCorrente ($iCodigoGrupoCorrente) {
    $this->iCodigoGrupoCorrente = $iCodigoGrupoCorrente;
  }

  /**
   * Seta a conta credito
   * @param integer $iContaCredito
   */
  public function setContaCredito ($iContaCredito) {
    $this->iContaCredito = $iContaCredito;
  }

  /**
   * Retorna a conta credito
   * @return integer
   */
  public function getContaCredito () {
  	return $this->iContaCredito;
  }

  /**
   * Seta a conta debito
   * @param integer $iContaDebito
   */
  public function setContaDebito ($iContaDebito) {
  	$this->iContaDebito = $iContaDebito;
  }

  /**
   * Retorna o codigo da conta debito
   * @return integer
   */
  public function getContaDebito () {
  	return $this->iContaDebito;
  }

  /**
   * Seta o codigo do favorecido
   * @param integer $iCodigoCGM
   */
  public function setCodigoCgm($iCodigoCGM) {
    $this->iCodigoCGM = $iCodigoCGM;
  }

  public function setFavorecido($iFavorecido) {
    $this->iCodigoCGM = $iFavorecido;
  }

  public function getFavorecido() {
    return $this->iCodigoCGM;
  }


  /**
   * Seta o codigo do documento
   * @param integer $iCodigoDocumento
   */
  public function setCodigoDocumento($iCodigoDocumento) {
    $this->iCodigoDocumento = $iCodigoDocumento;
  }

  /**
   * Seta o mes do lancamento
   * @param integer $iMesLancamento
   */
  public function setMesLancamento($iMesLancamento) {
    $this->iMes = $iMesLancamento;
  }

  /**
   * Seta o codigo da receita
   * @param integer $iCodigoReceita
   */
  public function setCodigoReceita($iCodigoReceita) {
    $this->iCodigoReceita = $iCodigoReceita;
  }

  /**
   * Retorna o código da receita arrecadada
   * @return integer
   */
  public function getCodigoReceita() {
    return $this->iCodigoReceita;
  }

  /**
   * Seta o código da conta corrente.
   * @param integer $iCodigoConta
   */
  public function setCodigoContaCorrente($iCodigoConta) {
    $this->iCodigoConta = $iCodigoConta;
  }

  /**
   * Retorna o código da conta salvo na tabela corrente
   * @param integer $iCodigoConta
   */
  public function getCodigoContaBancoArrecadacaoCorrente() {
    return $this->iCodigoConta;
  }

  /**
   * Seta o valor total do lancamento
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
   * Seta o codigo do historico
   * @see ILancamentoAuxiliar::setHistorico()
   */
  public function setHistorico($iHistorico) {
    $this->iCodigoHistorico = $iHistorico;
  }

  /**
   * Retorna o codigo do historico
   * @see ILancamentoAuxiliar::getHistorico()
   */
  public function getHistorico() {
    return $this->iCodigoHistorico;
  }

  /**
   * Retorna o complemento do lancamento contabil
   * @see ILancamentoAuxiliar::getObservacaoHistorico()
   */
  public function getObservacaoHistorico() {
    return $this->sObservacao;
  }

  /**
   * Seta o complemento do lancamento contabil
   * @see ILancamentoAuxiliar::setObservacaoHistorico()
   */
  public function setObservacaoHistorico($sObservacao) {
    $this->sObservacao = $sObservacao;
  }

  /**
   * Seta se o processamento e um estorno
   * @param boolean $lProcessamento
   */
  public function setEstorno($lProcessamento) {
    $this->lEstorno = $lProcessamento;
  }

  /**
   * Retorna se o lancamento e um estorno
   * @return boolean
   */
  public function isEstorno() {
    return $this->lEstorno;
  }

  /**
   * Característica Peculiar
   * @param string $sCaracteristicaPeculiar
   */
  public function setCaracteristicaPeculiar($sCaracteristicaPeculiar) {
    $this->sCaracteristicaPeculiar = $sCaracteristicaPeculiar;
  }

  /**
   * Retorna a Característica peculiar
   * @string string
   */
  public function getCaracteristicaPeculiar() {
    return $this->sCaracteristicaPeculiar;
  }

  /**
   * Define a conta do orcamento
   * @param integer $iCodigoContaOrcamento Código da conta do orcamento
   */
  public function setCodigoContaOrcamento($iCodigoContaOrcamento) {
    $this->iCodigoContaOrcamento = $iCodigoContaOrcamento;
  }

  /**
   * Retorna o codigo da conta do orcamento
   * @return number integer
   */
  public function getCodigoContaOrcamento() {
    return $this->iCodigoContaOrcamento;
  }  
  
  /** 
   * Seta código da autenticação
   * @param integer $iIdAutenticacao - id da autenticacao
   */
  public function setAutenticacao($iIdAutenticacao) {
    $this->iIdAutenticacao = $iIdAutenticacao;
  }

  /** 
   * Retorna código da autenticação
   * @return integer
   */
  public function getAutenticacao() {
    return $this->iIdAutenticacao;
  }

  /** 
   * Seta data da autenticação
   * @param date $dtDataAutenticacao - data da autenticacao
   */
  public function setDataAutenticacao($dtDataAutenticacao) {
    $this->dtDataAutenticacao = $dtDataAutenticacao;
  }

  /** 
   * Retorna data da autenticação
   * @return date 
   */
  public function getDataAutenticacao() {
    return $this->dtDataAutenticacao;
  }

  /** 
   * Retorna data da autenticação
   * @param integer - Codigo Autenticadora 
   */
  public function setAutenticadora($iAutenticadora) {
    $this->iAutenticadora = $iAutenticadora;
  }

  /** 
   * Retorna data da autenticação
   * @return integer 
   */
  public function getAutenticadora() {
    return $this->iAutenticadora;
  }

  /**
   * Vincula a característica peculiar com o lançamento contábil.
   * @throws BusinessException
   * @return boolean
   */
  protected function salvarVinculoCaracteristicaPeculiar() {

    if (!empty($this->sCaracteristicaPeculiar)) {

      $oDaoConLancamConCarPeculiar = db_utils::getDao('conlancamconcarpeculiar');
      $oDaoConLancamConCarPeculiar->c08_sequencial     = null;
      $oDaoConLancamConCarPeculiar->c08_codlan         = $this->iCodigoLancamento;
      $oDaoConLancamConCarPeculiar->c08_concarpeculiar = $this->getCaracteristicaPeculiar();
      $oDaoConLancamConCarPeculiar->incluir(null);
      if ($oDaoConLancamConCarPeculiar->erro_status == "0") {
        throw new BusinessException("Não foi possível vincular o lançamento com a característica peculiar.");
      }
    }
    return true;
  }


  /**
   * Vincula o lançamento com a conta pagadora
   * @throws BusinessException
   * @return boolean
   */
  protected function salvarVinculoContaPagadora() {

    if (isset($this->iCodigoConta) && !empty($this->iCodigoConta) && $this->iCodigoConta != 0) {

      $oDaoConLancamPag = db_utils::getDao('conlancampag');
      $oDaoConLancamPag->c82_codlan  = $this->iCodigoLancamento;
      $oDaoConLancamPag->c82_anousu  = db_getsession("DB_anousu");
      $oDaoConLancamPag->c82_reduz   = $this->iCodigoConta;
      $oDaoConLancamPag->incluir($this->iCodigoLancamento);
      if ($oDaoConLancamPag->erro_status == "0") {
        throw new BusinessException("[1] Não foi possível criar vínculo com a conta pagadora.");
      }
    }
    return true;
  }

  protected function salvarVinculoGrupoContaCorrente() {

    if (!empty($this->iCodigoGrupoCorrente)) {

      $oDaoGrupoCorrente = db_utils::getDao('conlancamcorgrupocorrente');
      $oDaoGrupoCorrente->c23_sequencial       = null;
      $oDaoGrupoCorrente->c23_conlancam        = $this->iCodigoLancamento;
      $oDaoGrupoCorrente->c23_corgrupocorrente = $this->iCodigoGrupoCorrente;
      $oDaoGrupoCorrente->incluir(null);
      if ($oDaoGrupoCorrente->erro_status == "0") {
        throw new BusinessException("[7] Não foi possível víncular o grupo com o lançamento");
      }
    }
    return true;
  }

  /**
   * Vincula o lançamento contábil na receita arrecadada
   * @throws BusinessException
   * @return boolean
   */
  protected function salvarVinculoReceita() {

    $oDaoConLancamRec = db_utils::getDao('conlancamrec');
    $oDaoConLancamRec->c74_codlan = $this->iCodigoLancamento;
    $oDaoConLancamRec->c74_anousu = db_getsession("DB_anousu");
    $oDaoConLancamRec->c74_codrec = $this->iCodigoReceita;
    $oDaoConLancamRec->c74_data   = $this->dtLancamento;
    $oDaoConLancamRec->incluir($this->iCodigoLancamento);
    if ($oDaoConLancamRec->erro_status == "0") {
      throw new BusinessException("[2] Não foi possível criar vínculo com a receita.");
    }
    return true;
  }

  /**
   * Salva o vínculo da arrecadação de receita com o CGM
   * @throws BusinessException
   * @return boolean
   */
  protected function salvarVinculoCGM() {

    if (!empty($this->iCodigoCGM)) {

      $oDaoConLancamCGM = db_utils::getDao('conlancamcgm');
      $oDaoConLancamCGM->c76_codlan = $this->iCodigoLancamento;
      $oDaoConLancamCGM->c76_numcgm = $this->iCodigoCGM;
      $oDaoConLancamCGM->c76_data   = $this->dtLancamento;
      $oDaoConLancamCGM->incluir($this->iCodigoLancamento);
      if ($oDaoConLancamCGM->erro_status == "0") {
        throw new BusinessException("[6] Não foi possível criar o vínculo do lançamento com o CGM.");
      }
    }
    return true;
  }

  /**
   * Agrupa os valores da receita no mes e ano
   * @throws BusinessException
   * @return boolean
   */
  protected function agruparValoresReceita() {

    /*
     * Agrupa os valores lançados para a receita por mês
    * - Validamos se o mês/ano/receita/documento está cadastrado, se não estiver, incluimos registro, do contrário
    * alteramos o existente somando o valor corrente do objeto
    */
    $iAnoSessao = db_getsession('DB_anousu');
    $oDaoOrcReceitaVal       = db_utils::getDao('orcreceitaval');
    $sWhereReceitaVal        = "     o71_anousu = {$iAnoSessao}";
    $sWhereReceitaVal       .= " and o71_codrec = {$this->iCodigoReceita}";
    $sWhereReceitaVal       .= " and o71_coddoc = {$this->iCodigoDocumento}";
    $sWhereReceitaVal       .= " and o71_mes    = {$this->iMes}";
    $sSqlBuscaOrcReceitaVal  = $oDaoOrcReceitaVal->sql_query_file(null, null, null, null, "*", null, $sWhereReceitaVal);
    $rsBuscaOrcReceitaVal    = $oDaoOrcReceitaVal->sql_record($sSqlBuscaOrcReceitaVal);

    $oDaoOrcReceitaVal->o71_anousu = $iAnoSessao;
    $oDaoOrcReceitaVal->o71_codrec = $this->iCodigoReceita;
    $oDaoOrcReceitaVal->o71_coddoc = $this->iCodigoDocumento;
    $oDaoOrcReceitaVal->o71_mes    = $this->iMes;

    if ($oDaoOrcReceitaVal->numrows == 0) {

      $oDaoOrcReceitaVal->o71_valor  = $this->nValorTotal;
      $oDaoOrcReceitaVal->incluir($iAnoSessao, $this->iCodigoReceita, $this->iCodigoDocumento, $this->iMes);
      if ($oDaoOrcReceitaVal->erro_status == "0") {
        throw new BusinessException("[4] Não foi possível criar o vínculo com os valores da receita.");
      }
    } else {

      $oDadoReceitaValor = db_utils::fieldsMemory($rsBuscaOrcReceitaVal, 0);
      $oDaoOrcReceitaVal->o71_valor = ($oDaoOrcReceitaVal->o71_valor + $oDadoReceitaValor->o71_valor);
      $oDaoOrcReceitaVal->alterar($iAnoSessao, $this->iCodigoReceita, $this->iCodigoDocumento, $this->iMes);
      if ($oDaoOrcReceitaVal->erro_status == "0") {
        throw new BusinessException("[5] Não foi possível alterar o vínculo com os valores da receita.");
      }
    }
    return true;
  }

  /**
   * Retorna o código do recurso
   * @return integer
   */
  public function getCodigoRecurso() {
    return $this->iCodigoRecurso;
  }

  /**
   * Seta o código do recurso
   * @param integer $iCodigoRecurso
   */
  public function setCodigoRecurso($iCodigoRecurso) {
    $this->iCodigoRecurso = $iCodigoRecurso;
  }
  
  public function salvarVinculoArrecadacao() {
    
    if (!empty($this->iIdAutenticacao) && !empty($this->dtDataAutenticacao) && !empty($this->iAutenticadora)) {
      
      $oDAOConlancamcorrente = db_utils::getDao('conlancamcorrente');
      $oDAOConlancamcorrente->c86_id        = $this->iIdAutenticacao; 
      $oDAOConlancamcorrente->c86_data      = $this->dtDataAutenticacao;
      $oDAOConlancamcorrente->c86_autent    = $this->iAutenticadora;
      $oDAOConlancamcorrente->c86_conlancam = $this->iCodigoLancamento;
      $oDAOConlancamcorrente->incluir(null);
      
      if ($oDAOConlancamcorrente->erro_status == "0") {
        throw new BusinessException("Não foi possível criar o vínculo do lançamento com corrente.");
      }
    }
  }


  /**
   * Setter para sabermos se é uma prestação de contas
   * @param boolean
   */
  public function setArrecadacaoEmpenhoPrestacaoContas ($lArrecadacaoPrestacaoContas) {
    $this->lArrecadacaoPrestacaoContas = $lArrecadacaoPrestacaoContas;
  }
  
  /**
   * Getter para sabermos se é uma prestação de contas
   * @return boolean
   */
  public function arrecadacaoEmpenhoPrestacaoContas () {
    return $this->lArrecadacaoPrestacaoContas; 
  }
  
 
}
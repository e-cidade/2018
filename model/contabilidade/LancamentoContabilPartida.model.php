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
 * Model responsável pelos dados contábeis do lançamento contábil
 *
 * @author Matheus Felini <matheus.felini@dbseller.com.br>
 * @package contabilidade
 * @version $Revision: 1.1 $
 */
class LancamentoContabilPartida {

  /**
   * Código da partida/contra-partida executada
   * @var integer
   */
  protected $iCodigo;

  /**
   * Código agrupador dos lançamentos
   * @var integer
   */
  protected $iCodigoLancamento;

  /**
   * Ano do Lançamento
   * @var integer
   */
  protected $iAno;

  /**
   * Código da Conta Creditada
   * @var integer
   */
  protected $iContaCredito;

  /**
   * Código da Conta Debitada
   * @var integer
   */
  protected $iContaDebito;

  /**
   * Código do histórico
   * @var integer
   */
  protected $iHistorico;

  /**
   * Valor do Lançamento
   * @var float
   */
  protected $nValor;

  /**
   * Data do Lançamento
   * @var date
   */
  protected $dtLancamento;

  /**
   * Conta do Plano de contas para a conta crédito do lançamento
   * @var ContaPlanoPCASP
   */
  protected $oContaPlanoCredito;

  /**
   * Conta do Plano de contas para a conta débito do lançamento
   * @var ContaPlanoPCASP
   */
  protected $oContaPlanoDebito;

  /**
   * Constrói os dados da partida de um lançamento contábil
   * @param integer $iCodigo
   * @throws BusinessException
   */
  public function __construct($iCodigo = null) {

    $this->iCodigo = $iCodigo;
    if (!empty($this->iCodigo)) {

      $oDaoConLancamVal = db_utils::getDao('conlancamval');
      $sSqlBuscaPartida = $oDaoConLancamVal->sql_query_file($this->iCodigo);
      $rsBuscaPartida   = $oDaoConLancamVal->sql_record($sSqlBuscaPartida);
      if ($oDaoConLancamVal->erro_status == "0") {
        throw new BusinessException("Não foi possível localizar o lancamento contábil {$this->iCodigo}.");
      }

      $oStdConLancamVal        = db_utils::fieldsMemory($rsBuscaPartida, 0);
      $this->iCodigo           = $oStdConLancamVal->c69_sequen;
      $this->iAno              = $oStdConLancamVal->c69_anousu;
      $this->iCodigoLancamento = $oStdConLancamVal->c69_codlan;
      $this->iHistorico        = $oStdConLancamVal->c69_codhist;
      $this->iContaCredito     = $oStdConLancamVal->c69_credito;
      $this->iContaDebito      = $oStdConLancamVal->c69_debito;
      $this->nValor            = $oStdConLancamVal->c69_valor;
      $this->dtLancamento      = $oStdConLancamVal->c69_data;
      unset($oStdConLancamVal);
    }
  }

  /**
   * Método que salva os dados contábeis de um lançamento contábil.
   *
   * Este método não pode alterar valores, eles somente pode incluir um novo registro.
   *
   * @throws BusinessException
   * @return boolean true
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new DBException("Transação com o banco de dados não encontrada.");
    }

    $oDaoConLancamVal              = db_utils::getDao('conlancamval');
    $oDaoConLancamVal->c69_sequen  = null;
    $oDaoConLancamVal->c69_anousu  = $this->iAno;
    $oDaoConLancamVal->c69_codlan  = $this->iCodigoLancamento;
    $oDaoConLancamVal->c69_codhist = $this->iHistorico;
    $oDaoConLancamVal->c69_credito = $this->iContaCredito;
    $oDaoConLancamVal->c69_debito  = $this->iContaDebito;
    $oDaoConLancamVal->c69_valor   = $this->nValor;
    $oDaoConLancamVal->c69_data    = $this->dtLancamento;
    $oDaoConLancamVal->incluir(null);
    if ($oDaoConLancamVal->erro_status == "0") {
      throw new BusinessException("Não foi possível incluir os dados contábeis do lançamento contábil.");
    }
    $this->iCodigo = $oDaoConLancamVal->c69_sequen;
    return true;
  }

  /**
   * Exclui os dados contabeis do lançamento contábil
   * @throws DBException
   * @throws BusinessException
   * @return boolean
   */
  public function excluir() {

    if (!db_utils::inTransaction()) {
      throw new DBException("Transação com o banco de dados não encontrada.");
    }

    $oDaoConLancamVal = db_utils::getDao('conlancamval');
    $oDaoConLancamVal->excluir($this->iCodigo);
    if ($oDaoConLancamVal->erro_status == "0") {
      throw new BusinessException("Não foi possível excluir os dados contábeis do lançamento.");
    }
    return true;
  }

  /**
   * Retorna o código do lançamento contábil
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna o código agrupador do lançamento
   * @return integer
   */
  public function getCodigoLancamento() {
    return $this->iCodigoLancamento;
  }

  /**
   * Seta o código agrupador do lançamento
   * @param $iCodigoLancamento
   */
  public function setCodigoLancamento($iCodigoLancamento) {
    $this->iCodigoLancamento = $iCodigoLancamento;
  }

  /**
   * Retorna o Ano do Lançamento
   * @return integer
   */
  public function getAno() {
    return $this->iAno;
  }

  /**
   * Seta o ano do lançamento
   * @param $iAno
   */
  public function setAno($iAno) {
    $this->iAno = $iAno;
  }

  /**
   * Retorna o código da conta crédito
   * @return integer
   */
  public function getCodigoContaCredito() {
    return $this->iContaCredito;
  }

  /**
   * Seta o código da conta crédito
   * @param $iContaCredito
   */
  public function setCodigoContaCredito($iContaCredito) {
    $this->iContaCredito = $iContaCredito;
  }

  /**
   * Retorna o código da conta débito
   * @return
   */
  public function getCodigoContaDebito() {
    return $this->iContaDebito;
  }

  /**
   * Seta a conta débito
   * @param $iContaDebito
   */
  public function setCodigoContaDebito($iContaDebito) {
    $this->iContaDebito = $iContaDebito;
  }

  /**
   * Retorna o código do histórico
   * @return integer
   */
  public function getHistorico() {
    return $this->iHistorico;
  }

  /**
   * Seta o histórico do lançamento
   * @param $iHistorico
   */
  public function setHistorico($iHistorico) {
    $this->iHistorico = $iHistorico;
  }

  /**
   * Retorna o valor do lançamento
   * @return number
   */
  public function getValor() {
    return $this->nValor;
  }

  /**
   * Seta o valor do lançamento
   * @param $nValor
   */
  public function setValor($nValor) {
    $this->nValor = $nValor;
  }

  /**
   * Retorna a data do lançamento
   * @return date
   */
  public function getDataLancamento() {
    return $this->dtLancamento;
  }

  /**
   * Data do Lançamento
   * @param $dtLancamento
   */
  public function setDataLancamento($dtLancamento) {
    $this->dtLancamento = $dtLancamento;
  }

  /**
   * Retorna o objeto ContaPlanoPCASP com os dados da conta crédito
   * @return ContaPlanoPCASP
   */
  public function getContaCredito() {

    if (!empty($this->iContaCredito)) {
      $this->oContaPlanoCredito = new ContaPlanoPCASP(null, $this->iAno, $this->iContaCredito);
    }
    return $this->oContaPlanoCredito;
  }

  /**
   * Retorna o objeto ContaPlanoPCASP com os dados da conta débito
   * @return ContaPlanoPCASP
   */
  public function getContaDebito() {

    if (!empty($this->iContaDebito)) {
      $this->oContaPlanoDebito = new ContaPlanoPCASP(null, $this->iAno, $this->iContaDebito);
    }
    return $this->oContaPlanoDebito;
  }
}
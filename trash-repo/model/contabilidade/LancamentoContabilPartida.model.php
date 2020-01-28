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
 * Model respons�vel pelos dados cont�beis do lan�amento cont�bil
 *
 * @author Matheus Felini <matheus.felini@dbseller.com.br>
 * @package contabilidade
 * @version $Revision: 1.1 $
 */
class LancamentoContabilPartida {

  /**
   * C�digo da partida/contra-partida executada
   * @var integer
   */
  protected $iCodigo;

  /**
   * C�digo agrupador dos lan�amentos
   * @var integer
   */
  protected $iCodigoLancamento;

  /**
   * Ano do Lan�amento
   * @var integer
   */
  protected $iAno;

  /**
   * C�digo da Conta Creditada
   * @var integer
   */
  protected $iContaCredito;

  /**
   * C�digo da Conta Debitada
   * @var integer
   */
  protected $iContaDebito;

  /**
   * C�digo do hist�rico
   * @var integer
   */
  protected $iHistorico;

  /**
   * Valor do Lan�amento
   * @var float
   */
  protected $nValor;

  /**
   * Data do Lan�amento
   * @var date
   */
  protected $dtLancamento;

  /**
   * Conta do Plano de contas para a conta cr�dito do lan�amento
   * @var ContaPlanoPCASP
   */
  protected $oContaPlanoCredito;

  /**
   * Conta do Plano de contas para a conta d�bito do lan�amento
   * @var ContaPlanoPCASP
   */
  protected $oContaPlanoDebito;

  /**
   * Constr�i os dados da partida de um lan�amento cont�bil
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
        throw new BusinessException("N�o foi poss�vel localizar o lancamento cont�bil {$this->iCodigo}.");
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
   * M�todo que salva os dados cont�beis de um lan�amento cont�bil.
   *
   * Este m�todo n�o pode alterar valores, eles somente pode incluir um novo registro.
   *
   * @throws BusinessException
   * @return boolean true
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new DBException("Transa��o com o banco de dados n�o encontrada.");
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
      throw new BusinessException("N�o foi poss�vel incluir os dados cont�beis do lan�amento cont�bil.");
    }
    $this->iCodigo = $oDaoConLancamVal->c69_sequen;
    return true;
  }

  /**
   * Exclui os dados contabeis do lan�amento cont�bil
   * @throws DBException
   * @throws BusinessException
   * @return boolean
   */
  public function excluir() {

    if (!db_utils::inTransaction()) {
      throw new DBException("Transa��o com o banco de dados n�o encontrada.");
    }

    $oDaoConLancamVal = db_utils::getDao('conlancamval');
    $oDaoConLancamVal->excluir($this->iCodigo);
    if ($oDaoConLancamVal->erro_status == "0") {
      throw new BusinessException("N�o foi poss�vel excluir os dados cont�beis do lan�amento.");
    }
    return true;
  }

  /**
   * Retorna o c�digo do lan�amento cont�bil
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna o c�digo agrupador do lan�amento
   * @return integer
   */
  public function getCodigoLancamento() {
    return $this->iCodigoLancamento;
  }

  /**
   * Seta o c�digo agrupador do lan�amento
   * @param $iCodigoLancamento
   */
  public function setCodigoLancamento($iCodigoLancamento) {
    $this->iCodigoLancamento = $iCodigoLancamento;
  }

  /**
   * Retorna o Ano do Lan�amento
   * @return integer
   */
  public function getAno() {
    return $this->iAno;
  }

  /**
   * Seta o ano do lan�amento
   * @param $iAno
   */
  public function setAno($iAno) {
    $this->iAno = $iAno;
  }

  /**
   * Retorna o c�digo da conta cr�dito
   * @return integer
   */
  public function getCodigoContaCredito() {
    return $this->iContaCredito;
  }

  /**
   * Seta o c�digo da conta cr�dito
   * @param $iContaCredito
   */
  public function setCodigoContaCredito($iContaCredito) {
    $this->iContaCredito = $iContaCredito;
  }

  /**
   * Retorna o c�digo da conta d�bito
   * @return
   */
  public function getCodigoContaDebito() {
    return $this->iContaDebito;
  }

  /**
   * Seta a conta d�bito
   * @param $iContaDebito
   */
  public function setCodigoContaDebito($iContaDebito) {
    $this->iContaDebito = $iContaDebito;
  }

  /**
   * Retorna o c�digo do hist�rico
   * @return integer
   */
  public function getHistorico() {
    return $this->iHistorico;
  }

  /**
   * Seta o hist�rico do lan�amento
   * @param $iHistorico
   */
  public function setHistorico($iHistorico) {
    $this->iHistorico = $iHistorico;
  }

  /**
   * Retorna o valor do lan�amento
   * @return number
   */
  public function getValor() {
    return $this->nValor;
  }

  /**
   * Seta o valor do lan�amento
   * @param $nValor
   */
  public function setValor($nValor) {
    $this->nValor = $nValor;
  }

  /**
   * Retorna a data do lan�amento
   * @return date
   */
  public function getDataLancamento() {
    return $this->dtLancamento;
  }

  /**
   * Data do Lan�amento
   * @param $dtLancamento
   */
  public function setDataLancamento($dtLancamento) {
    $this->dtLancamento = $dtLancamento;
  }

  /**
   * Retorna o objeto ContaPlanoPCASP com os dados da conta cr�dito
   * @return ContaPlanoPCASP
   */
  public function getContaCredito() {

    if (!empty($this->iContaCredito)) {
      $this->oContaPlanoCredito = new ContaPlanoPCASP(null, $this->iAno, $this->iContaCredito);
    }
    return $this->oContaPlanoCredito;
  }

  /**
   * Retorna o objeto ContaPlanoPCASP com os dados da conta d�bito
   * @return ContaPlanoPCASP
   */
  public function getContaDebito() {

    if (!empty($this->iContaDebito)) {
      $this->oContaPlanoDebito = new ContaPlanoPCASP(null, $this->iAno, $this->iContaDebito);
    }
    return $this->oContaPlanoDebito;
  }
}
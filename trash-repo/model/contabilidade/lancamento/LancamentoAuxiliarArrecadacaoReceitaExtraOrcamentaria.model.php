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
 * Executa os lan�amentos auxiliares para uma arrecada��o de receita extra-or�ament�ria
 * @author Matheus Felini <matheus.felini@dbseller.com.br>
 * @package contabilidade
 * @subpackage lancamento
 * @version $Revision: 1.8 $
 *
 * @todo
 * verificar a possibilidade de refatorar este model e pass�-lo para dentro do LancamentoAuxiliarArrecadacaoReceita
 */
class LancamentoAuxiliarArrecadacaoReceitaExtraOrcamentaria extends LancamentoAuxiliarBase implements ILancamentoAuxiliar {

  /**
   * Complemento para o lan�amento cont�bil
   * @var string
   */
  protected $sComplemento;

  /**
   * Valor total do lan�amento
   * @var float
   */
  protected $nValorTotal;

  /**
   * C�digo do hist�rico
   * @var integer
   */
  protected $iHistorico;

  /**
   * C�digo da Conta Cr�dito
   * @var integer
   */
  protected $iContaCredito;

  /**
   * C�digo da conta d�bito
   * @var integer
   */
  protected $iContaDebito;

  /**
   * C�digo do grupo de conta corrente
   * @var integer
   */
  protected $iCodigoGrupoCorrente;

  /**
   * Vari�vel de controle para sabermos se o lan�amento � um estorno
   * @var boolean
   */
  protected $lEstorno = false;

  /**
   * Caracter�stica Peculiar da Receita
   * @var string
   */
  protected $sCaracteristicaPeculiar;

  /**
   * Codigo do recurso
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
   * Seta c�digo da autentica��o
   * @param integer $iIdAutenticacao - id da autenticacao
   */
  public function setAutenticacao($iIdAutenticacao) {
    $this->iIdAutenticacao = $iIdAutenticacao;
  }

  /** 
   * Retorna c�digo da autentica��o
   * @return integer
   */
  public function getAutenticacao() {
    return $this->iIdAutenticacao;
  }

  /** 
   * Seta data da autentica��o
   * @param date $dtDataAutenticacao - data da autenticacao
   */
  public function setDataAutenticacao($dtDataAutenticacao) {
    $this->dtDataAutenticacao = $dtDataAutenticacao;
  }

  /** 
   * Retorna data da autentica��o
   * @return date 
   */
  public function getDataAutenticacao() {
    return $this->dtDataAutenticacao;
  }

  /** 
   * Retorna data da autentica��o
   * @param integer - Codigo Autenticadora 
   */
  public function setAutenticadora($iAutenticadora) {
    $this->iAutenticadora = $iAutenticadora;
  }

  /** 
   * Retorna data da autentica��o
   * @return integer 
   */
  public function getAutenticadora() {
    return $this->iAutenticadora;
  }

  /**
   * Executa os lan�amentos cont�beis de uma arrrecada��o de receita extra-or�ament�ria
   * @see ILancamentoAuxiliar::executaLancamentoAuxiliar()
   * @return boolean true
   */
  public function executaLancamentoAuxiliar($iCodigoLancamento, $dtLancamento) {

    parent::setCodigoLancamento($iCodigoLancamento);
    parent::setDataLancamento($dtLancamento);
    parent::salvarVinculoComplemento();
    $this->salvarVinculoCaracteristicaPeculiar();
    $this->salvarVinculoArrecadacao();
    if ( !empty($this->iCodigoGrupoCorrente) ) {
      $this->salvarVinculoGrupoContaCorrente();
    }
    if (!empty($this->iNumeroEmpenho)) {
      $this->salvarVinculoEmpenho();
    }
    return true;
  }

  /**
   * Salva o v�nculo com o grupo da conta corrente
   * @throws BusinessException
   */
  private function salvarVinculoGrupoContaCorrente() {

    $oDaoGrupoCorrente = db_utils::getDao('conlancamcorgrupocorrente');
    $oDaoGrupoCorrente->c23_sequencial       = null;
    $oDaoGrupoCorrente->c23_conlancam        = $iCodigoLancamento;
    $oDaoGrupoCorrente->c23_corgrupocorrente = $this->iCodigoGrupoCorrente;
    $oDaoGrupoCorrente->incluir(null);
    if ($oDaoGrupoCorrente->erro_status == "0") {
      throw new BusinessException("N�o foi poss�vel v�ncular o grupo com o lan�amento.");
    }
    return true;
  }

  /**
   * Seta valor para o complemento do lan�amento cont�bil
   * @see LancamentoAuxiliarBase::setObservacaoHistorico()
   */
  public function setObservacaoHistorico($sObservacaoHistorico) {
    $this->sComplemento = $sObservacaoHistorico;
  }

  /**
   * Retorna o complemento do lan�amento cont�bil
   * @see LancamentoAuxiliarBase::getObservacaoHistorico()
   */
  public function getObservacaoHistorico() {
    return $this->sComplemento;
  }

  /**
   * Seta o valor total
   * @param float $nValorTotal
   * @see ILancamentoAuxiliar::setValorTotal()
   */
  public function setValorTotal($nValorTotal){
    $this->nValorTotal = $nValorTotal;
  }

  /**
   * Retorna o valor total
   * @return float $nValorTotal
   */
  public function getValorTotal(){
    return $this->nValorTotal;
  }

  /**
   * Retorna o hist�rico da opera��o
   * @return integer
   */
  public function getHistorico(){
    return $this->iHistorico;
  }


  /**
   * Seta o hist�rico da opera��o
   * @param integer $iHistorico
   */
  public function setHistorico($iHistorico){
    $this->iHistorico = $iHistorico;
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
   * Seta se o lan�amento � um estorno
   * @param boolean $lEstorno
   */
  public function setEstorno($lEstorno) {
    $this->lEstorno = $lEstorno;
  }

  /**
   * Retorna se o lan�amento � um estorno
   * @return boolean
   */
  public function isEstorno() {
    return $this->lEstorno;
  }

  /**
   * Caracter�stica Peculiar
   * @param string $sCaracteristicaPeculiar
   */
  public function setCaracteristicaPeculiar($sCaracteristicaPeculiar) {
    $this->sCaracteristicaPeculiar = $sCaracteristicaPeculiar;
  }

  /**
   * Retorna a Caracter�stica peculiar
   * @string string
   */
  public function getCaracteristicaPeculiar() {
    return $this->sCaracteristicaPeculiar;
  }
  /**
   * Vincula a caracter�stica peculiar com o lan�amento cont�bil.
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
        throw new BusinessException("N�o foi poss�vel vincular o lan�amento com a caracter�stica peculiar.");
      }
    }
    return true;
  }
  /**
   * Retorna o c�digo do recurso
   * @return integer
   */
  public function getCodigoRecurso() {
    return $this->iCodigoRecurso;
  }

  /**
   * Seta o c�digo do recurso
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
        throw new BusinessException("N�o foi poss�vel criar o v�nculo do lan�amento com corrente.");
      }
    } 
  }
}
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
 * Model para cadastro dos periodos de ferias
 * @author  Jeferson Belmiro   <jeferson.belmiro@dbseller.com.br>
 * @author  Rafael Lopes       <rafael.lopes@dbseller.com.br>
 * @require db_utils
 * @version $
 */
class FeriasPeriodo {

  /**
   * Código do periodo
   * @var integer
   */
  public $iCodigoPeriodo;

  /**
   * Código ferias
   * @var integer
   */
  public $iCodigoFerias;

  /**
   * Dias de gozo
   * @var integer
   */
  public $iDiasGozo;

  /**
   * Periodo inicial de gozo
   * @var date
   */
  public $dPeriodoInicial;

  /**
   * Periodo final de gozo
   * @var date
   */
  public $dPeriodoFinal;

  /**
   * Observação
   * @var string
   */
  public $sObservacao;

  /**
   * Ano de pagamento
   * @var integer
   */
  public $iAnoPagamento;

  /**
   * Mês de pagamento
   * @var integer
   */
  public $iMesPagamento;

  /**
   * Dia de abono
   * @var integer
   */
  public $iDiasAbono;

  /**
   * Paga 1/3 do salário
   * @var boolean
   */
  public $lPagaTerco;
  
  /**
   * Tipo de pagamento das férias
   * 1 - Salário
   * 2 - Complementar
   * @var integer
   */
  public $iTipoPonto;  

  public $oDaoFeriasPeriodo;
  /**
   * Construtor
   * @param integer $iCodigoPeriodo
   * @return void
   */
  public function __construct($iCodigoPeriodo = null) {

    $this->oDaoFeriasPeriodo = db_utils::getDao('rhferiasperiodo');

    if ( isset($iCodigoPeriodo) ) {


      $sSqlFeriasPeriodo = $this->oDaoFeriasPeriodo->sql_query_file($iCodigoPeriodo);
      $rsFeriasPeriodo   = $this->oDaoFeriasPeriodo->sql_record($sSqlFeriasPeriodo);

      if ($this->oDaoFeriasPeriodo->numrows == 0) {
        throw new DBException('Erro [0] - Nenhum registro encontrado');
      }

      $this->setCodigoPeriodo($iCodigoPeriodo);

      $oFeriasPeriodo = db_utils::fieldsMemory($rsFeriasPeriodo, 0, true);

      $this->setCodigoFerias   ($oFeriasPeriodo->rh110_rhferias);
      $this->setDiasGozo       ($oFeriasPeriodo->rh110_dias);
      $this->setPeriodoInicial ($oFeriasPeriodo->rh110_datainicial);
      $this->setPeriodoFinal   ($oFeriasPeriodo->rh110_datafinal);
      $this->setObservacao     ($oFeriasPeriodo->rh110_observacao);
      $this->setAnoPagamento   ($oFeriasPeriodo->rh110_anopagamento);
      $this->setMesPagamento   ($oFeriasPeriodo->rh110_mespagamento);
      $this->setDiasAbono      ($oFeriasPeriodo->rh110_diasabono);
      $this->setPagaTerco      ($oFeriasPeriodo->rh110_pagaterco);
      $this->setTipoPonto      ($oFeriasPeriodo->rh110_tipoponto);

    }

  }

  /**
   * Retorna o código do periodo
   * @return integer
   */
  public function getCodigoPeriodo() {
    return $this->iCodigoPeriodo;
  }

  /**
   * Define o código do periodo
   * @param integer $iCodigoPeriodo
   */
  public function setCodigoPeriodo($iCodigoPeriodo) {
    $this->iCodigoPeriodo = $iCodigoPeriodo;
  }

  /**
   * Retorna o código da tabela rhferias
   * @return integer
   */
  public function getCodigoFerias() {
    return $this->iCodigoFerias;
  }

  /**
   * Define o código da tabela rhferias
   * @param integer $iCodigoFerias
   */
  public function setCodigoFerias($iCodigoFerias) {
    $this->iCodigoFerias = $iCodigoFerias;
  }

  /**
   * Retorna quantidade de dias de gozo
   * @return integer
   */
  public function getDiasGozo() {
    return $this->iDiasGozo;
  }

  /**
   * Define quantidade de dias de gozo
   * @param integer $iDiasGozo
   */
  public function setDiasGozo($iDiasGozo) {
    $this->iDiasGozo = $iDiasGozo;
  }

  /**
   * Retorna o periodo iniciali
   * @return date
   */
  public function getPeriodoInicial() {
    return $this->dPeriodoInicial;
  }

  /**
   * Define o periodo inicial
   * @param date $dPeriodoInicial
   */
  public function setPeriodoInicial($dPeriodoInicial) {
    $this->dPeriodoInicial = $dPeriodoInicial;
  }

  /**
   * Retorna o periodo final
   * @return date
   */
  public function getPeriodoFinal() {
    return $this->dPeriodoFinal;
  }

  /**
   * Define o periodo final
   * @param date $dPeriodoFinal
   */
  public function setPeriodoFinal($dPeriodoFinal) {
    $this->dPeriodoFinal = $dPeriodoFinal;
  }

  /**
   * Retorna a observação
   * @return string
   */
  public function getObservacao() {
    return $this->sObservacao;
  }

  /**
   * Define a observação
   * @param string $sObservacao
   */
  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
  }

  /**
   * Retorna o ano de pagamento
   * @return integer
   */
  public function getAnoPagamento() {
    return $this->iAnoPagamento;
  }

  /**
   * Define o ano de pagemento
   * @param integer $iAnoPagamento
   */
  public function setAnoPagamento($iAnoPagamento) {
    $this->iAnoPagamento = $iAnoPagamento;
  }

  /**
   * Retorna o mês de pagamento
   * @return integer
   */
  public function getMesPagamento() {
    return $this->iMesPagamento;
  }

  /**
   * Define o mês de pagamento
   * @param integer $iMesPagamento
   */
  public function setMesPagamento($iMesPagamento) {
    $this->iMesPagamento = $iMesPagamento;
  }

  /**
   * Retorna os dias de abono do gozo
   * @return integer
   */
  public function getDiasAbono() {
    return $this->iDiasAbono;
  }

  /**
   * Define os dias de abono do gozo
   * @param integer $iDiasAbonorh110_observacao
   */
  public function setDiasAbono($iDiasAbono) {
    $this->iDiasAbono = $iDiasAbono;
  }

  /**
   * Retorna verdadeiro caso seja pago 1/3 do salário
   * @return boolean
   */
  public function isPagaTerco() {
    return $this->lPagaTerco;
  }
  
  /**
   * Define se será pago 1/3 do salário
   * @param boolean $lPagaTerco
   */
  public function setPagaTerco($lPagaTerco) {
    $this->lPagaTerco = $lPagaTerco;  
  }
  
  /**
   * Retorna o tipo de pagamento do ponto
   * 1 - Salário
   * 2 - Complementar
   * @return integer 
   */
  public function getTipoPonto() {
    return $this->iTipoPonto;
  }
  
  /**
  * Define o tipo de pagamento do ponto
  * 1 - Salário
  * 2 - Complementar
  * @param integer $iTipoPonto
  */
  public function setTipoPonto($iTipoPonto) {
    $this->iTipoPonto = $iTipoPonto;
  }
  
  /**
   * Salvar
   *
   * @throws Exception 1 sem transação ativa
   * @throws Exception 2/3 Erro de sql incluir()/alterar()
   * @return boolean
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new DBException("Erro [1] - Não existe Transação Ativa.");
    }

    $this->oDaoFeriasPeriodo = db_utils::getDao('rhferiasperiodo');

    /**
     * Salvar
     * não está definido o código então inclui
     */
    if ( !isset($this->iCodigoPeriodo) ) {

      $this->oDaoFeriasPeriodo->rh110_rhferias     = $this->getCodigoFerias();
      $this->oDaoFeriasPeriodo->rh110_dias         = $this->getDiasGozo();
      $this->oDaoFeriasPeriodo->rh110_datainicial  = $this->getPeriodoInicial();
      $this->oDaoFeriasPeriodo->rh110_datafinal    = $this->getPeriodoFinal();
      $this->oDaoFeriasPeriodo->rh110_observacao   = $this->getObservacao();
      $this->oDaoFeriasPeriodo->rh110_anopagamento = $this->getAnoPagamento();
      $this->oDaoFeriasPeriodo->rh110_mespagamento = $this->getMesPagamento();
      $this->oDaoFeriasPeriodo->rh110_diasabono    = $this->getDiasAbono();
      $this->oDaoFeriasPeriodo->rh110_pagaterco    = $this->isPagaTerco();
      $this->oDaoFeriasPeriodo->rh110_tipoponto    = $this->getTipoPonto();
      
      $this->oDaoFeriasPeriodo->incluir(null);

      if ($this->oDaoFeriasPeriodo->erro_status == "0") {
        throw new DBException("ERRO [2] " . $this->oDaoFeriasPeriodo->erro_msg);
      }

      /**
       * @todo alterar metodo setFerias para setPeriodosFerias 
       */
      $oFerias      = new Ferias($this->getCodigoFerias());
      $oServidor    = new Servidor($oFerias->getMatricula());
      $oPontoFerias = new PontoFerias($oServidor);

      $oPontoFerias->setFerias($oFerias);
      $oPontoFerias->gerar(); 

      return $this->oDaoFeriasPeriodo->erro_msg;

    } else {

      /**
       * Update
       * Está definido o código então altera
       */
      $this->oDaoFeriasPeriodo->rh110_sequencial   = $this->getCodigoPeriodo();
      $this->oDaoFeriasPeriodo->rh110_rhferias     = $this->getCodigoFerias();
      $this->oDaoFeriasPeriodo->rh110_dias         = $this->getDiasGozo();
      $this->oDaoFeriasPeriodo->rh110_datainicial  = $this->getPeriodoInicial();
      $this->oDaoFeriasPeriodo->rh110_datafinal    = $this->getPeriodoFinal();
      $this->oDaoFeriasPeriodo->rh110_observacao   = $this->getObservacao();
      $this->oDaoFeriasPeriodo->rh110_anopagamento = $this->getAnoPagamento();
      $this->oDaoFeriasPeriodo->rh110_mespagamento = $this->getMesPagamento();
      $this->oDaoFeriasPeriodo->rh110_diasabono    = $this->getDiasAbono();
      $this->oDaoFeriasPeriodo->rh110_pagaterco    = $this->isPagaTerco();
      $this->oDaoFeriasPeriodo->rh110_tipoponto    = $this->getTipoPonto();

      $this->oDaoFeriasPeriodo->alterar( $this->getCodigoFerias() );

      if ($this->oDaoFeriasPeriodo->erro_status == "0") {
        throw new DBException("ERRO [3] " . $this->oDaoFeriasPeriodo->erro_msg);
      }
    }

    return $this->oDaoFeriasPeriodo->erro_msg;
  }

  public function excluir() {

    if (!db_utils::inTransaction()) {
      throw new DBException("Erro [1] - Não existe Transação Ativa.");
    }

    $this->oDaoFeriasPeriodo->excluir($this->getCodigoPeriodo());
    if ($this->oDaoFeriasPeriodo->erro_status == "0") {
      throw new DBException("ERRO [0] " . $this->oDaoFeriasPeriodo->erro_msg);
    }

    return true;

  }
  
}
?>
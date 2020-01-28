<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
 * Model que descobre as contas deverão ser debitados e creditados os valores.
 * @author rafael.lopes
 * @author bruno.silva
 * @package contabilidade
 * @subpackage lancamento
 * @version 1.0 $
 * 
 */

class ReceitaFatoGerador {
	

  private $iSequencial;
  private $dtAno;
  private $lEstornado;
  
  private $iHistorico;
  private $nValor;
  private $iCodigoReceita;
  
  private $iContaCredito;
  private $iContaDebito;
  
  /**
   * Construtor da classe
   * @param int $iSequencial
   */
  public function __construct($iSequencial = null) {
    
    if (isset($iSequencial)) {
      
      
      $this->setSequencial(null);
      $oDaoAberturaexercicio = db_utils::getDao('aberturaexercicio');//new cl_aberturaexercicio;
      $sSqlAberturaexercicio =  $oDaoAberturaexercicio->sql_query($iSequencial);
      $rsAberturaexercicio   =  $oDaoAberturaexercicio->sql_record($sSqlAberturaexercicio);
      $iAberturaexercicio    =  $oDaoAberturaexercicio->numrows;
      
      if ($iAberturaexercicio != 0) {

        $this->setSequencial($iSequencial);
        $oLinha           = db_utils::fieldsMemory($rsAberturaexercicio, 0);
        $this->iAnousu    = $oLinha->c81_anousu;
        $this->lEstornado = $oLinha->c81_estornado;
      }
    }
  }

  public function getContaDebito() {
    return $this->iContaDebito;
  }
  
  public function setContaDebito($iContaDebito) {
    
    
    $this->iContaDebito = $iContaDebito;
  }
  
  public function getContaCredito() {
    return $this->iContaCredito;
  }
  
  public function setContaCredito ($iContaCredito) {
    
    $this->iContaCredito = $iContaCredito;
  }
  
  public function getCodigoReceita() {
    return $this->iCodigoReceita;
  }
  
  public function setCodigoReceita($iCodigoReceita) {
    $this->iCodigoReceita = $iCodigoReceita;
  }
  
  public function getValor() {
    return $this->nValor;
  }
  
  public function setValor($nValor) {
    $this->nValor = $nValor;
  }
  
  
  public function getHistorico() {
    return $this->iHistorico;
  }
  
  public function setHistorico($iHistorico) {
    $this->iHistorico = $iHistorico;
  }
  
  
  public function getSequencial(){
    
    return $this->iSequencial;
  }
  
  public function setSequencial($iSequencial){
    
    $this->iSequencial = $iSequencial;
  }
  
  public function getAno(){
  
    return $this->iAnousu;
  }
  
  public function setAno($dtAno){
  
    $this->iAnousu = $dtAno;
  }  
  

  public function getEstornado(){
  
    return $this->lEstornado;
  }
  
  public function setEstornado($lEstornado){
  
    $this->lEstornado = $lEstornado;
  } 
  
  /**
   * Método para Salvar dados da classe 
   * 
   *  Inclui na tabela "aberturaexercicio" e executa o lançamento contábil
   */
  
  public function salvar($sMotivo) {
    
    if (!(db_utils::inTransaction())) {
      throw new DBException("ERRO[0] - Não Existe Transação Ativa !!");
    }

    $oDaoAberturaexercicio                = db_utils::getDao('aberturaexercicio');
    $oDaoAberturaexercicio->c81_anousu    = $this->getAno();
    $oDaoAberturaexercicio->c81_estornado = 'false';
    
    if (isset($this->iSequencial ) || $this->iSequencial == null ) {
      $oDaoAberturaexercicio->incluir(null);
    } else {
      
      $oDaoAberturaexercicio->c81_sequencial = $this->getSequencial();
      $oDaoAberturaexercicio->alterar($oDaoAberturaexercicio->c81_sequencial);
    }
    
    if ($oDaoAberturaexercicio->erro_status == "0") {
      throw new DBException($oDaoAberturaexercicio->erro_msg);
    }

    $this->setSequencial($oDaoAberturaexercicio->c81_sequencial);
    $this->executarLancamentoContabilReceita($sMotivo, false);
  }
  
  /**
   * Método para Estornar
   * 
   *  Modifica registro na tabela "aberturaexercicio" para estornado e executa os lançamentos
   */
  
  public function estornar($sMotivo) {
    
    if (!(db_utils::inTransaction())) {
      throw new DBException("ERRO[0] - Não Existe Transação Ativa !!");
    }
    /**
     * Altera status da Receita
     */
    $oDaoAberturaexercicio                 = db_utils::getDao('aberturaexercicio');
    $oDaoAberturaexercicio->c81_sequencial = $this->getSequencial();
    $oDaoAberturaexercicio->c81_estornado  = 'true';
    
    //echo "SEQUENCIAL = " . $this->getSequencial();
    //die();
    
    $oDaoAberturaexercicio->alterar($oDaoAberturaexercicio->c81_sequencial);
    
    $this->setEstornado($oDaoAberturaexercicio->c81_estornado);
    $this->executarLancamentoContabilReceita($sMotivo, true);

    if ($oDaoAberturaexercicio->erro_status == '0') {
      throw new DBException("Erro [1]: Erro ao alterar o status para estornado {$oDaoAberturaexercicio->erro_msg}" );
    }
    
    return true;
  }

  /**
   * Executa lançamentos contábeis para a Receita atual
   */
  
  private function executarLancamentoContabilReceita($sMotivo,$lEstorno) {
    
    $iCodigoDocumentoExecutar = 105;
    if ($lEstorno) {
      $iCodigoDocumentoExecutar = 106;
    }
    $oEventoContabil 						= new EventoContabil($iCodigoDocumentoExecutar, db_getsession("DB_anousu"));
    $aLancamentosEventoContabil = $oEventoContabil->getEventoContabilLancamento();
    $iCodigoHistorico           = $aLancamentosEventoContabil[0]->getHistorico();
    
    $oLancamentoAuxiliarFatoGerador = new LancamentoAuxiliarReconhecimentoReceitaFatoGerador();
    $oLancamentoAuxiliarFatoGerador->setHistorico($iCodigoHistorico);
    $oLancamentoAuxiliarFatoGerador->setValorTotal($this->nValor);
    $oLancamentoAuxiliarFatoGerador->setObservacaoHistorico($sMotivo); 
    $oLancamentoAuxiliarFatoGerador->setCodigoReceita($this->iCodigoReceita);
    $oLancamentoAuxiliarFatoGerador->setAnoReceita($this->iAnousu);
    $oLancamentoAuxiliarFatoGerador->setCodigoAberturaExercicio($this->iSequencial);
    $oLancamentoAuxiliarFatoGerador->setCodigoContaCredito($this->iContaCredito);
    $oLancamentoAuxiliarFatoGerador->setCodigoContaDebito($this->iContaDebito);

    $oEventoContabil->executaLancamento($oLancamentoAuxiliarFatoGerador);
  }
}
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
 * Classe que representa um reconhecimento contábil
 * 
 * @author Robson Inacio robson@dbseller.com.br
 */

class ReconhecimentoContabil {

  /**
   * Código sequencial do reconhecimento contábil
   * @var integer
   */
  private $iSequencial;

  /**
   * Objeto Tipo de reconhecimento contábil
   * @var TipoReconhecimentoContabil
   */
  private $oTipoReconhecimentoContabil;

  /**
   * Instancia da classe CGMBase
   * @var CGMBase
   */
  private $oCredor;

  /**
   * Código do processo administrativo
   * @var string
   */
  private $sProcessoAdministrativo;

  /**
   * Valor do reconhecimento contabil
   * @var numeric
   */
  private $nValor;

  /**
   * Data do reconhecimento contabil, usada para buscar a transcao
   * @var DBDate
   */
  private $oDate;

  /**
   * Se esta estornado
   * @var boolean
   */
  private $lEstornado;

  /**
   * Construtor da classe
   *
   * @param integer - sequencial da tabela reconhecimentocontabil
   * @param DBDate  - data do reconhecimento contabil
   */
  function __construct($iSequencial = null, DBDate $oDate = null) {

  	$this->setSequencial($iSequencial);
    if($iSequencial != null) {

      $this->setDate($oDate);
      $oDAOReconhecimentoContabil = db_utils::getDao("reconhecimentocontabil");

      $sSqlReconhecimentoContabil = $oDAOReconhecimentoContabil->sql_query($this->getSequencial());
      $rsReconhecimentoContabil   = $oDAOReconhecimentoContabil->sql_record($sSqlReconhecimentoContabil);

      if ($oDAOReconhecimentoContabil->numrows > 0) {

        $oDadosReconhecimentoContabil = db_utils::fieldsMemory($rsReconhecimentoContabil, 0);

        $this->setTipoReconhecimentoContabil(TipoReconhecimentoContabil::getInstance($oDadosReconhecimentoContabil->c112_reconhecimentocontabiltipo, $this->getDate()) );
        $this->setCredor( CgmFactory::getInstanceByCgm($oDadosReconhecimentoContabil->c112_numcgm) );
        $this->setProcessoAdministrativo($oDadosReconhecimentoContabil->c112_processoadm);
        $lEstornado = ( $oDadosReconhecimentoContabil->c112_estornado == 't' ? true : false);
        $this->setEstornado($lEstornado);
        $this->setValor($oDadosReconhecimentoContabil->c112_valor);
      } else {
        throw new Exception("Transações não configuradas para os documentos 513 e 514");        
      }

    }
    return true;
  }

  /**
   * Salva os dados na tabela reconhecimentocontabil e efetua os lancamentos contabeis
   */
  private function salvarDados() {
    
    if (!db_utils::inTransaction()) {
      throw new Exception("Sem transação com o banco de dados");        
    }

  	$oDaoReconhecimentoContabil = db_utils::getDao("reconhecimentocontabil");

  	$oDaoReconhecimentoContabil->c112_sequencial                 = $this->getSequencial();
  	$oDaoReconhecimentoContabil->c112_reconhecimentocontabiltipo = $this->getTipoReconhecimentoContabil()->getCodigo();
  	$oDaoReconhecimentoContabil->c112_numcgm                     = $this->getCredor()->getCodigo();
  	$oDaoReconhecimentoContabil->c112_processoadm                = $this->getProcessoAdministrativo();
  	$oDaoReconhecimentoContabil->c112_valor                      = $this->getValor();
  	$oDaoReconhecimentoContabil->c112_estornado                  = ( $this->isEstornado() == true ? 'true' : 'false' );

  	if ($this->getSequencial() == "") {

  		$oDaoReconhecimentoContabil->incluir(null);
  		$this->setSequencial($oDaoReconhecimentoContabil->c112_sequencial);
  	} else {
  		$oDaoReconhecimentoContabil->alterar($this->getSequencial());
  	}

  	if ($oDaoReconhecimentoContabil->erro_status == 0) {

  		$sErro = "Não foi possivel salvar os dados do reconhecimento contabil. \n\nErro técnico : {$oDaoReconhecimentoContabil->erro_msg} ";
  		throw new BusinessException($sErro);
  	}
    
    return true;

  }

  /**
   * Salva os dados e efetua lancamento contabil
   */
  public function salvar($sObservacao = ""){
    
    $this->salvarDados();

    $this->efetuarLancamentos($sObservacao);

    return true;
  }

  /**
   * Exclui os dados da tabela reconhecimento contabil
   * @return boolean 
   */
  public function excluir(){
    
    if (!db_utils::inTransaction()) {
      throw new Exception("Sem transação com o banco de dados");        
    }

    $oDaoReconhecimentoContabil = db_utils::getDao("reconhecimentocontabil");
    $oDaoReconhecimentoContabil->excluir($this->iSequencial);
   
    if ( $oDaoReconhecimentoContabil->erro_status == 0 ) {
      throw new BusinessException("Erro ao excluir o Tipo de Reconhecimento contábil. Tipo não existente.");
    } 
    return true;
    
  }

  /*
   * cria objeto lancamento Auxiliar
   */
  private function buildLancamentoAuxiliar ($sObservacao) {

    $oLancamentoAuxiliarReconhecimentoContabil = new LancamentoAuxiliarReconhecimentoContabil();
    $oLancamentoAuxiliarReconhecimentoContabil->setReconhecimentoContabil($this);
    $oLancamentoAuxiliarReconhecimentoContabil->setValorTotal($this->getValor());
    $oLancamentoAuxiliarReconhecimentoContabil->setObservacaoHistorico($sObservacao);
    return $oLancamentoAuxiliarReconhecimentoContabil;    
  }

  /*
   * cria objeto lancamento Auxiliar
   */
  public function efetuarLancamentos($sObservacao) {

    if (!db_utils::inTransaction()) {
      throw new Exception("Sem transação com o banco de dados");        
    }

    $this->oTipoReconhecimentoContabil
         ->getEventoContabilLancamento()
         ->executaLancamento($this->buildLancamentoAuxiliar($sObservacao));

    return true;
  }

  /*
   * Efetua estorno do reconhecimento contabil
   */
  public function estornar($sObservacao){

    if (!db_utils::inTransaction()) {
      throw new Exception("Sem transação com o banco de dados");        
    }
    $this->setEstornado(true);
    $this->salvarDados();

    $this->oTipoReconhecimentoContabil
         ->getEventoContabilEstorno()
         ->executaLancamento($this->buildLancamentoAuxiliar($sObservacao));

    return true;
  }
  
  public function setCredor(CgmBase $oCgm) {

    $this->oCredor = $oCgm;
  }

  public function getCredor() {

  	return $this->oCredor;
  }

  public function setDate(DBDate $oDate){

    $this->oDate = $oDate;    
  }

  public function getDate(){

    return $this->oDate;    
  }

  public function setSequencial($iSequencial) {

    $this->iSequencial = $iSequencial;
  }

  public function getSequencial() {

  	return $this->iSequencial;
  }

  public function setProcessoAdministrativo($sProcessoAdministrativo) {

    $this->sProcessoAdministrativo = $sProcessoAdministrativo;
  }

  public function getProcessoAdministrativo() {

  	return $this->sProcessoAdministrativo;
  }

  public function setEstornado($lEstornado) {

    $this->lEstornado = $lEstornado;
  }

  public function isEstornado() {

  	return $this->lEstornado;
  }
  
  public function setValor($nValor) {

    $this->nValor = $nValor;
  }

  public function getValor() {

  	return $this->nValor;
  }

  public function setTipoReconhecimentoContabil(TipoReconhecimentoContabil $oTipoReconhecimentoContabil){
    
    $this->oTipoReconhecimentoContabil = $oTipoReconhecimentoContabil;
  }

  public function getTipoReconhecimentoContabil(){

    return $this->oTipoReconhecimentoContabil;
  }

}

?>
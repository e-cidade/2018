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


class transacaoContabilLancamento {
  
  protected $iCodigo;
  
  protected $aContas;
  
  protected $iCodigoTransacao;
  
  protected $sObservacao = null;
  
  protected $iHistorico  = null;
  
  protected $lObrigatorio = false;
   
  protected $iEvento    = null;
  
  protected $iOrdem;
  
  protected $sDescricao;
  
  /**
   * 
   */
  function __construct($iCodigoLancamento = null) {

    /**
     * 
     */    
    if (!empty($iCodigoLancamento)) {
       
      $oDaoContranslan     = db_utils::getDao("contranslan");
      $sSqlDadosLancamento = $oDaoContranslan->sql_query_file($iCodigoLancamento);
      $rsDadosLancamento   = $oDaoContranslan->sql_record($sSqlDadosLancamento);
      if ($oDaoContranslan->numrows > 0) {
         
        $oDadosContranslan      = db_utils::fieldsMemory($rsDadosLancamento, 0);
        $this->iCodigo          = $oDadosContranslan->c46_seqtranslan;
        $this->iCodigoTransacao = $oDadosContranslan->c46_seqtrans;
        $this->sObservacao      = $oDadosContranslan->c46_obs;
        $this->iEvento          = $oDadosContranslan->c46_evento;
        $this->iHistorico       = $oDadosContranslan->c46_codhist;
        $this->lObrigatorio     = $oDadosContranslan->c46_obrigatorio=='t'?true:false;
      }
      
      /**
       * Retornamos as contas cadastradas do lancamento
       */
      $oDaoContranslanlr = new cl_contranslr;
      $sCampos  = "c47_seqtranslr as codigo,c47_debito as debito,";
      $sCampos .= "c47_credito as credito, c47_anousu as ano,c47_obs as observacao, ";
      $sCampos .= "c47_ref as referencia,c47_instit as instituicao , c47_compara as compara ,c47_tiporesto as tiporesto";
      $sSqlContas = $oDaoContranslanlr->sql_query(null, $sCampos, "c47_seqtranslr",
                                                  "c47_seqtranslan = {$this->getCodigo()}"
                                                 );
      $rsContas      = $oDaoContranslanlr->sql_record($sSqlContas);
      $this->aContas = db_utils::getColectionByRecord($rsContas);   
                                                      
    }
  }
  
  /**
   * @return unknown
   */
  public function getCodigo() {

    return $this->iCodigo;
  }
  
  /**
   * @return unknown
   */
  public function getCodigoTransacao() {

    return $this->iCodigoTransacao;
  }
  
  /**
   * @return unknown
   */
  public function getEvento() {

    return $this->iEvento;
  }
  
  /**
   * @return unknown
   */
  public function getHistorico() {

    return $this->iHistorico;
  }
  
  /**
   * @return unknown
   */
  public function getObservacao() {

    return $this->sObservacao;
  }
  
  /**
   * @param unknown_type $iCodigoTransacao
   */
  public function setCodigoTransacao($iCodigoTransacao) {

    $this->iCodigoTransacao = $iCodigoTransacao;
  }
  
  /**
   * @param unknown_type $iEvento
   */
  public function setEvento($iEvento) {

    $this->iEvento = $iEvento;
  }
  
  /**
   * @param unknown_type $iHistorico
   */
  public function setHistorico($iHistorico) {

    $this->iHistorico = $iHistorico;
  }
  
  /**
   * @param unknown_type $sObservacao
   */
  public function setObservacao($sObservacao) {

    $this->sObservacao = $sObservacao;
  }
  
  function isObrigatorio() {
    return $this->lObrigatorio;
  }
  function getContas() {
   return $this->aContas; 
  }
  
  /**
   * Persiste os dados na base de dados
   *
   */
  function save() {
    
    $oDaoContranslan                  = db_utils::getDao("contranslan");
    $oDaoContranslan->c46_codhist     = $this->getHistorico();
    $oDaoContranslan->c46_evento      = $this->getEvento();
    $oDaoContranslan->c46_obrigatorio = $this->isObrigatorio()==true?"true":"false";
    $oDaoContranslan->c46_obs         = $this->getObservacao();
    $oDaoContranslan->c46_seqtrans    = $this->getCodigoTransacao();
    $oDaoContranslan->c46_ordem       = $this->getProximaOrdemLancamentoTransacao();
    $oDaoContranslan->c46_descricao   = $this->getDescricao();
    if ($this->getCodigo() == null) {
      $oDaoContranslan->incluir(null);
    } else {
      
      $oDaoContranslan->c46_seqtranslan = $this->iCodigo;
      $oDaoContranslan->alterar($this->getCodigo());
    }
    
    if ($oDaoContranslan->erro_status == 0) {
      
      $sErroMensagem  = "Nгo foi possнvel salvar dados da transaзгo.\n";
      $sErroMensagem .= "Erro Retornado: \n{$oDaoContranslan->erro_msg}";
      throw new Exception($sErroMensagem);
      
    }
    
    return true;
  }
  
//   public function salvarConta() {
    
//     /**
//      * Gravamos os dados dascontas na tabela contranslr
//      */
//     $oDaoContranslanlr = new cl_contranslr();
//     foreach ($this->aContas as $oContaLancamento) {
      
//       $oDaoContranslanlr->c47_anousu      = $oContaLancamento->ano;
//       $oDaoContranslanlr->c47_compara     = "$oContaLancamento->compara";
//       $oDaoContranslanlr->c47_ref         = "$oContaLancamento->referencia";
//       $oDaoContranslanlr->c47_credito     = "$oContaLancamento->credito";
//       $oDaoContranslanlr->c47_debito      = "$oContaLancamento->debito";
//       $oDaoContranslanlr->c47_instit      = $oContaLancamento->instituicao;
//       $oDaoContranslanlr->c47_obs         = $oContaLancamento->observacao;
//       $oDaoContranslanlr->c47_seqtranslan = $this->getCodigoTransacao();
//       $oDaoContranslanlr->c47_tiporesto   = "$oContaLancamento->tiporesto";
//       if (empty($oContaLancamento->codigo)) {
//         $oDaoContranslanlr->incluir(null);
//       } else {

//         $oDaoContranslanlr->c47_seqtranslr = $oContaLancamento->codigo; 
//         $oDaoContranslanlr->alterar($oContaLancamento->codigo);
//       }
//       if ($oDaoContranslanlr->erro_status == 0) {
        
//         $sErroMensagem  = "Nгo foi possнvel salvar dados da transaзгo.\n";
//         $sErroMensagem  = "Inconsistкncia no cadastro das contas.\n";
//         $sErroMensagem .= "Erro Retornado: \n{$oDaoContranslanlr->erro_msg}";
//         throw new Exception($sErroMensagem);
        
//       }
//     }
//     return true;
//   }
  
  
//   function addConta($iAno, $iCredito, $iDebito, $iInstituicao, $iTipoResto=0, $iReferencia = 0, $iCompara = 0, 
//                     $sObservacao = '' ) {

//     if (empty($iAno)) {
//       throw new Exception("Ano nгo informado.");
//     }
//     if ($iCredito == "") {
//       throw new Exception("Conta Crйdito ({$iCredito}) nгo informada.");
//     }
//     if ($iDebito == "") {
//        throw new Exception("Conta Dйbito nгo informada.");
//     }
//     if (empty($iInstituicao)) {
//        throw new Exception("Instituiзгo nгo Informada.");
//     }
//     $oContaLancamento              = new stdClass();
//     $oContaLancamento->ano         = $iAno;
//     $oContaLancamento->compara     = $iCompara;
//     $oContaLancamento->referencia  = $iReferencia;
//     $oContaLancamento->credito     = $iCredito;
//     $oContaLancamento->debito      = $iDebito;
//     $oContaLancamento->instituicao = $iInstituicao;
//     $oContaLancamento->observacao  = $sObservacao;
//     $oContaLancamento->tiporesto   = $iTipoResto;
//     $oContaLancamento->codigo      = null; 
//     $this->aContas[] = $oContaLancamento;
//   }
  
  
  /**
   * Mйtodo que retorna a ordem do prуximo lanзamento de uma transaзгo
   * @return integer
   */
  public function getProximaOrdemLancamentoTransacao() {
  	
  	$oDaoTranslan        = db_utils::getDao("contranslan");
  	$sWhereTranslan      = "c46_seqtrans = {$this->getCodigoTransacao()}";
  	$sSqlOrdemLancamento = $oDaoTranslan->sql_query_file(null, "max(c46_ordem) as c46_ordem", null, $sWhereTranslan);
  	$rsOrdemLancamento   = $oDaoTranslan->sql_record($sSqlOrdemLancamento);

  	if ($oDaoTranslan->numrows == 0) {
  		$iProximaOrdem = 1;
  	} else {
	  	$iProximaOrdem = (db_utils::fieldsMemory($rsOrdemLancamento, 0)->c46_ordem + 1);
  	}
  	return $iProximaOrdem;
  }
  
  /**
   * Exclui um lanзamento e suas contas
   * @throws Exception
   * @return true
   */
  public function excluirLancamento() {
  	
  	$oDaoContransLan = db_utils::getDao("contranslan");
  	$oDaoContransLR  = db_utils::getDao("contranslr");
  	
  	$oDaoContransLR->excluir(null, "c47_seqtranslan = {$this->getCodigo()}");
  	if ($oDaoContransLR->erro_status == 0) {
  		
  		$sMensagemErro  = "Nгo foi possнvel excluir as contas do lanзamento {$this->getCodigo()}.\n\n";
  		$sMensagemErro .= "{$oDaoContransLR->erro_msg}";
  		throw new Exception($sMensagemErro);
  	}
  	
  	$oDaoContransLan->excluir($this->getCodigo());
  	if ($oDaoContransLan->erro_status == 0) {
  		
  		$sMensagemErroLan  = "Nгo foi possнvel excluir o lanзamento {$this->getCodigo()}.\n\n";
  		$sMensagemErroLan .= "{$oDaoContransLan->erro_msg}";
  		throw new Exception($sMensagemErroLan);
  	}
  	return true; 	
  }

  public function salvarContaLancamento($iAno, $iCredito, $iDebito, $iInstituicao, $iTipoResto=0, $iReferencia = 0,
  		                                 $iCompara = 0, $sObservacao = '') {
  	
  	$oDaoContranslanlr->c47_anousu      = $iAno;
  	$oDaoContranslanlr->c47_compara     = "$iCompara";
  	$oDaoContranslanlr->c47_ref         = "$iReferencia";
  	$oDaoContranslanlr->c47_credito     = "$iCredito";
  	$oDaoContranslanlr->c47_debito      = "$iDebito";
  	$oDaoContranslanlr->c47_instit      = $iInstituicao;
  	$oDaoContranslanlr->c47_obs         = $sObservacao;
  	$oDaoContranslanlr->c47_seqtranslan = $this->getCodigoTransacao();
  	$oDaoContranslanlr->c47_tiporesto   = "$iTipoResto";
  	if (empty($oContaLancamento->codigo)) {
  		$oDaoContranslanlr->incluir(null);
  	} else {
  	
  		$oDaoContranslanlr->c47_seqtranslr = $oContaLancamento->codigo;
  		$oDaoContranslanlr->alterar($oContaLancamento->codigo);
  	}
  	if ($oDaoContranslanlr->erro_status == 0) {
  	
  		$sErroMensagem  = "Nгo foi possнvel salvar dados da transaзгo.\n";
  		$sErroMensagem  = "Inconsistкncia no cadastro das contas.\n";
  		$sErroMensagem .= "Erro Retornado: \n{$oDaoContranslanlr->erro_msg}";
  		throw new Exception($sErroMensagem);
  	}
  	return true;
  }
  
  /**
   * Exclui uma conta de um lanзamento
   * @param  integer $iCodigoConta
   * @throws Exception
   * @return true
   */
  public function excluirContaLancamento($iCodigoConta) {
  	
  	$oDaoContransLR  = db_utils::getDao("contranslr");
  	$oDaoContransLR->excluir($iCodigoConta);
  	if ($oDaoContransLR->erro_status == 0) {
  	
  		$sMensagemErro  = "Nгo foi possнvel excluir a conta {$iCodigoConta}.\n\n";
  		$sMensagemErro .= "{$oDaoContransLR->erro_msg}";
  		throw new Exception($sMensagemErro);
  	}
  	return true;
  }
  
  

  public function setOrdem($iOrdem) {
  	$this->iOrdem = $iOrdem;
  }
  
  public function getOrdem() {
  	return $this->iOrdem;
  }
  
  public function setDescricao($sDescricao) {
  	$this->sDescricao = $sDescricao;
  }
  
  public function getDescricao() {
  	return $this->sDescricao;
  }
}
?>
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


class transacaoContabil {
  
  /**
   * Cуdigo da transaзгo
   * OBS: Campo c46_seqtranslan da tabela 'contranslan'
   * @var integer
   */
  protected $iCodigo;
  
  /**
   * Cуdigo do documento
   * OBS: Campo c46_codhist da tabela 'contranslan'
   * @var integer
   */
  protected $iCodDoc;
  
  protected $iAnoTransacao;
  
  private $iInstituicao;
  
  protected $aLancamentos = array();
  
  /**
   * Observaзхes da transaзгo
   * OBS: Campo c46_obs da tabela 'contranslan'
   * @var string
   */
  protected $sObservacao;
  
  /**
   * Valor da transaзгo
   * OBS: Campo c46_valor da tabela 'contranslan'
   * @var number
   */
  protected $nValor;
  
  /**
   * OBS: Campo c46_obrigatorio da tabela 'contranslan'
   * @var boolean
   */
  protected $lObrigatorio;
  
  /**
   * Evento da transaзгo
   * OBS: Campo c46_evento da tabela 'contransplano'
   * @var integer
   */
  protected $iEvento;
  
  protected $iCodigoTransacao;
  
  protected $iOrdem;
  
  protected $sDescricao;
  
  /**
   * Configura as transacoes de lancamentos contabeis
   *
   * @param integer $iCodDoc    Codigo do Documento
   * @param integer $iInstuicao instuicao do lancamento
   */
  function __construct($iCodDoc = null, $iInstuicao = null) {
    
    if ((!empty($iCodDoc)) && (empty($iInstuicao))) {
          
      $this->iAnoTransacao = db_getsession("DB_anousu");
      
      /**
       * Verificamos se os Dados ja existem
       */
      $oDaoContrans = db_utils::getDao("contrans");
      $sWhere       = "c45_anousu     = {$this->getAnoTransacao()} ";
      $sWhere      .= "and c45_instit = {$iInstuicao}";
      $sWhere      .= "and c45_coddoc = {$iCodDoc}";
      
      $sSqlContrans = $oDaoContrans->sql_query_file(null, "*", null, $sWhere);
      $rsContrans   = $oDaoContrans->sql_record($sSqlContrans);
      if ($oDaoContrans->numrows > 0) {
        
        require_once("model/transacaoContabilLancamento.model.php");
        $this->iCodigo  = db_utils::fieldsMemory($rsContrans, 0)->c45_seqtrans;
        /**
         * Buscamos todos os lancamentos da transacao
         */
        $oDaoContranslan     = db_utils::getDao("contranslan");
        $sSqlTransacoes = $oDaoContranslan->sql_query_file(null, 
                                                          "c46_seqtranslan", 
                                                           null, 
                                                          "c46_seqtrans = {$this->iCodigo}"
                                                           );
                                                           
        $rsTransacoes = $oDaoContranslan->sql_record($sSqlTransacoes);
        $aLancamentos = db_utils::getColectionByRecord($rsTransacoes);
        foreach($aLancamentos as $oLancamento) {
  
          $oLancamentoTransacao = new transacaoContabilLancamento($oLancamento->c46_seqtranslan);
          $this->aLancamentos[] = $oLancamentoTransacao;          
        }
      }
    }
  }
  
  public function delete($iCodigoTransacao) {
    
    $oDaoContranslan = db_utils::getDao('contranslan');
    $oDaoContranslan->excluir($iCodigoTransacao);
    if ($oDaoContranslan->erro_status == 0) {
      throw new Exception("Erro ao excluir os dados da Transaзгo.\n{$oDaoContranslan->erro_msg}");
    }
  }
  
  public function save() {
    
    $oDaoContranslan = db_utils::getDao('contranslan');
    $oDaoContranslan->c46_seqtrans    = $this->getCodigo();
    $oDaoContranslan->c46_codhist     = $this->getCodDoc();
    $oDaoContranslan->c46_obs         = $this->getObservacao();
    $oDaoContranslan->c46_valor       = $this->getValor();
    $oDaoContranslan->c46_obrigatorio = $this->getObrigatorio();
    $oDaoContranslan->c46_evento      = $this->getEvento();
    $oDaoContranslan->c46_ordem       = $this->getOrdem();
    $oDaoContranslan->c46_descricao   = $this->getDescricao();
    if ($this->getCodigoTransacao() != null) {
      $oDaoContranslan->incluir(null);
    } else {
      
      $oDaoContranslan->c46_seqtranslan = $this->getCodigoTransacao();
      $oDaoContranslan->alterar($this->getCodigoTransacao());
    }
    if ($oDaoContranslan->erro_status == 0) {
      throw new Exception("Erro ao salvar os dados da Transaзгo.\n{$oDaoContranslan->erro_msg}");
    }
  }
  
  /**
   * Busca o ano da transaзгo
   * @return integer
   */
  public function getAnoTransacao() {

    return $this->iAnoTransacao;
  }
  
  /**
   * Busca o cуdigo do documento
   * @return integer
   */
  public function getCodDoc() {

    return $this->iCodDoc;
  }
  
  public function setCodDoc($iCodDoc) {
     $this->iCodDoc = $iCodDoc;
  }
  
  /**
   * Busca o cуdigo da transaзгo
   * @return integer
   */
  public function getCodigo() {

    return $this->iCodigo;
  }
  
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }
  
  /**
   * Busca o cуdigo do evento
   * @return integer
   */
  public function getEvento() {

    return $this->iEvento;
  }
  
  /**
   * Seta o cуdigo do evento
   * @param integer $iEvento
   */
  public function setEvento($iEvento) {

    $this->iEvento = $iEvento;
  }
  
  /**
   * Busca a instituiзгo da transaзгo
   * @return integer
   */
  public function getInstituicao() {

    return $this->iInstituicao;
  }

  /**
   * Retorna o primero lancamento contabil da transaзгo
   * @return transacaoContabilLancamento 
   */
  public function getPrimeiroLancamento() {
    
    $oPrimeiroLancamento = null;
    foreach ($this->aLancamentos as $oLancamento) {

      if (strtolower(substr($oLancamento->getObservacao(),0,3)) == "pri") {
        
        $oPrimeiroLancamento = $oLancamento;
        break;
      }
    }
    return $oPrimeiroLancamento;
  }
  
  public function getLancamentos() {
    return $this->aLancamentos;    
  }

  /**
   * Seta a observaзгo da transaзгo
   * @param string $sObservacao
   */
  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
  }
  
  /**
   * Busca a observaзгo da transacao
   * @return string
   */
  public function getObservacao() {
    return $this->sObservacao;
  }
  
  /**
   * Seta o valor da transaзгo
   * @param number $nValor
   */
  public function setValor($nValor) {
    $this->nValor = $nValor;
  }
  
  /**
   * Busva o valor da transaзгo
   * @return number
   */
  public function getValor() {
    return $this->nValor;
  }
  
  /**
   * Seta a variбvel obrigatуrio para a transaзгo
   * @param boolean $lObrigatorio
   */
  public function setObrigatorio($lObrigatorio) {
    $this->lObrigatorio = $lObrigatorio;
  }
  
  /**
   * Busca a variбvel obrigatуrio da transaзгo
   * @return boolean
   */
  public function getObrigatorio() {
    return $this->lObrigatorio;
  }

  public function setCodigoTransacao($iCodigoTransacao) {
    $this->iCodigoTransacao = $iCodigoTransacao;
  }
  
  public function getCodigoTransacao() {
    return $this->iCodigoTransacao;
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
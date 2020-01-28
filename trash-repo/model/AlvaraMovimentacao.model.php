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
 * @fileoverview - Classe de Modelo para movimenta��es do Alvar� 
 * @author    Rafael Serpa Nery - rafael.nery@dbseller.com.br	
 * @package   ISSQN
 * @revision  $Author: dbrobson $
 * @version   $Revision: 1.1 $
 */
require_once('libs/db_stdlib.php');
require_once('libs/db_utils.php');

abstract class AlvaraMovimentacao {
  
  /**
   * C�digo com o alvara que ser� realizada a movimenta��o
   * @var integer
   */
  private $iCodigoAlvara        = null;
  
  /**
   * Sequencial da Tabela issmovalvara
   * @var integer
   */
  private $iCodigoMovimentacao  = null;
  
  /**
   * Tipo de Movimentacao do Alvar�
   * Dados cadastrados na tabela isstipomovalvara
   * @var integer
   */
  private $iTipoMovimentacao    = null;
  
  /**
   * Data da ocorr�ncia da movimentcao
   * @var date
   */
  private $dtMovimentacao       = null;
  
  /**
   * Id do usuario que realizou a movimentacao
   * @var integer
   */
  private $iIdUsuario           = null;
  
  /**
   * 
   * Observa��o da movimentacao
   * @var string
   */
  private $sObservacao          = " ";
  
  /**
   * N�mero do processo do protocolo, na tabela issmovalvaraproc
   * @var integer
   */
  private $iCodigoProcesso      = null;
  
 /**
  * Dias da validade do alvar�
  * @var integer
  */
  private $iValidadeAlvara      = null;
  
  /**
   * Array dos documentos da movimenta��o
   * @var array
   */
  private $aDocumentos          = array();
  
  /**
   * Construtor da funcao
   */
  function __construct($iCodigoAlvara = null){
    
    if($iCodigoAlvara == null){
      throw new ErrorException("Selecione o Alvar� que deseja realizar a movimentacao");
    }
    $oDaoIssAlvara       = db_utils::getDAO("issalvara",true);
    $sSqlValidaAlvara    = $oDaoIssAlvara->sql_query_file($iCodigoAlvara);
    $rsSqlValidaAlvara   = $oDaoIssAlvara->sql_record($sSqlValidaAlvara);
    
    if($oDaoIssAlvara->numrows == 0){
      throw new ErrorException("Nenhum alvar� encontrado com este c�digo: ".$iCodigoAlvara);
    }
    
    $this->iCodigoAlvara = $iCodigoAlvara;
    $this->iIdUsuario    = db_getsession("DB_id_usuario");
  }
  
  /**
   * Salva movimenta��o do alvar�
   * @see iMovimentacaoAlvara::salvar()
   */  
  protected function salvar() {
    /**
     * Seta vari�vel para executar uma nova inser��o
     */
    $this->setCodigoMovimentacao(null);
    
    if ($this->iTipoMovimentacao == null) {
      throw new ErrorException("Sem tipo de movimenta��o setada");
    }
    
    if(db_utils::inTransaction() == false){
      throw new ErrorException("Sem transa��o ativa no banco de dados");
    }
    $oDaoIssMovAlvara  = db_utils::getDAO("issmovalvara",true);
      
    $oDaoIssMovAlvara->q120_isstipomovalvara  = $this->iTipoMovimentacao;
    $oDaoIssMovAlvara->q120_dtmov             = $this->dtMovimentacao;
    $oDaoIssMovAlvara->q120_usuario           = $this->iIdUsuario;
    $oDaoIssMovAlvara->q120_obs               = $this->sObservacao;
    $oDaoIssMovAlvara->q120_validadealvara    = $this->iValidadeAlvara;
    $oDaoIssMovAlvara->q120_issalvara         = $this->iCodigoAlvara;
    $oDaoIssMovAlvara->incluir(null);
    /**
     * Seta c�digo atual da movimentacao
     */
    $this->iCodigoMovimentacao = $oDaoIssMovAlvara->q120_sequencial;
    
    if($oDaoIssMovAlvara->erro_status != "0"){
      /**
       * Grava os Documentos 
       */
      $this->gravaDocumentos($this->aDocumentos);
      /**
       * Grava os Processos
       */
      if($this->iCodigoProcesso != null || $this->iCodigoProcesso != ""){
        $this->gravaProcesso();
      }
    } else {
      throw new ErrorException($oDaoIssMovAlvara->erro_msg);
    }
    
  }
    
  /**
   * Adiciona documento a lista de documentos da movimenta��o do alvar�
   * @param integer $iCodigoDocumento - SEQUENCIA TABELA caddocumento
   */
  public function addDocumento($iCodigoDocumento){
    $this->aDocumentos[] = $iCodigoDocumento;
  }
  
  /**
   * Salva os Documentos da movimenta��o
   * Enter description here ...
   * @param unknown_type $aDocumentos
   */
  protected function gravaDocumentos(){
    
    $oDaoIssAlvaraDocumento = db_utils::getDAO('issalvaradocumento');
    $oDaoIssAlvaraDocumento->excluir("", "q122_issalvara = {$this->iCodigoAlvara}");
    
    foreach ($this->aDocumentos as $iDocumento){
      
      $oDaoIssAlvaraDocumento->q122_issalvara    = $this->iCodigoAlvara;
      $oDaoIssAlvaraDocumento->q122_caddocumento = $iDocumento;
      $oDaoIssAlvaraDocumento->incluir("");
      if($oDaoIssAlvaraDocumento->erro_status == "0"){
        throw new ErrorException($oDaoIssAlvaraDocumento->erro_msg);
      } 
    }
  }

  /**
   * Salva o Processo ligado a movimentacao
   * Enter description here ...
   */
  private function gravaProcesso(){
    
    if($this->iCodigoProcesso != "" || $this->iCodigoProcesso != null){
      
      $oDaoIssMovAlvaraProcesso  = db_utils::getDAO("issmovalvaraprocesso",true);
      
      $oDaoIssMovAlvaraProcesso->q124_codproc      = $this->iCodigoProcesso;
      $oDaoIssMovAlvaraProcesso->q124_issmovalvara = $this->iCodigoMovimentacao;
      $oDaoIssMovAlvaraProcesso->incluir("");
      if($oDaoIssMovAlvaraProcesso->erro_status == "0"){
        throw new ErrorException($oDaoIssMovAlvaraProcesso->erro_msg);
      }
    }
  }

  /* Setters */
  
  /**
   * Seta valor da vari�vel $iCodigoMovimentacao
   * @param integer $iCodigoMovimentacao
   */
  public function setCodigoMovimentacao($iCodigoMovimentacao) {

    $this->iCodigoMovimentacao = $iCodigoMovimentacao;
    return $this;
  }
  
  /**
  * Seta valor da vari�vel $iTipoMovimentacao
  * @param integer $iTipoMovimentacao
  */
  public function setTipoMovimentacao($iTipoMovimentacao) {
    $this->iTipoMovimentacao = $iTipoMovimentacao;
    return $this;
  }
  
  /**
  * Seta valor da vari�vel $dtMovimentacao
  * @param date $dtMovimentacao
  */
  public function setDataMovimentacao($dtMovimentacao) {
    $this->dtMovimentacao = $dtMovimentacao;
    return $this;
  }
  
  /**
  * Seta valor da vari�vel $sObservacao
  * @param strinfg $sObservacao
  */
  public function setObservacao($sObservacao) {
    if($sObservacao == ""){
      $sObservacao = " ";
    }
    $this->sObservacao = $sObservacao;
    return $this;
  }
  
  /**
  * Seta valor da vari�vel $iCodigoProcesso
  * @param integer $iCodigoProcesso
  */
  public function setCodigoProcesso($iCodigoProcesso) {
    $this->iCodigoProcesso = $iCodigoProcesso;
    return $this;
  }
  
 /**
  * Seta validade do alvar� $iCodigoProcesso
  * @param integer $iCodigoProcesso
  */
  public function setValidadeAlvara($iValidadeAlvara) {
    $this->iValidadeAlvara = $iValidadeAlvara;
    return $this;
  }
  
  /* Getters */
  
  /**
   * Retorna valor da vari�vel $iCodigoAlvara
   * @return integer $iCodigoAlvara
   */
  public function getCodigoAlvara() {
    return $this->iCodigoAlvara;
  }

 /**
  * Retorna valor da vari�vel $iCodigoMovimentacao
  * @return integer $iCodigoMovimentacao
  */
  public function getCodigoMovimentacao() {
    return $this->iCodigoMovimentacao;
  }
  
 /**
  * Retorna valor da vari�vel $iTipoMovimentacao
  * @return integer $iTipoMovimentacao
  */
  public function getTipoMovimentacao() {
    return $this->iTipoMovimentacao;
  }
  
 /**
  * Retorna valor da vari�vel $dtMovimentacao
  * @return date $dtMovimentacao
  */
  public function getDataMovimentacao() {
    return $this->dtMovimentacao;
  }
  
 /**
  * Retorna valor da vari�vel $iIdUsuario
  * @return integer $iIdUsuario
  */
  public function getIdUsuario() {
    return $this->iIdUsuario;
  }
  
  /**
  * Retorna valor da vari�vel $sObservacao
  * @return integer $sObservacao
  */
  public function getObservacao() {
    return $this->sObservacao;
  }
  
  /**
  * Retorna valor da vari�vel $iCodigoProcesso
  * @return integer $iCodigoProcesso
  */
  public function getCodigoProcesso() {
    return $this->iCodigoProcesso;
  }
  
  /**
  * Retorna valor da vari�vel $iCodigoProcesso
  * @return integer $iCodigoProcesso
  */
  public function getValidadeAlvara() {
    return $this->iValidadeAlvara;
  }
  
  /**
  * M�todo de que busca as movimenta��es do alvar�
  * @return array;
  */
  public function getMovimentacoesAlvara($sOrdem=''){
  
    $oDaoIssMovAlvara = db_utils::getDAO("issmovalvara",true);
    $sSqlMovAlvara    = $oDaoIssMovAlvara->sql_query(null, "*", $sOrdem, "q120_issalvara = {$this->iCodigoAlvara}");
    $rsSqlMovAlvara   = $oDaoIssMovAlvara->sql_record($sSqlMovAlvara);
  
    if($oDaoIssMovAlvara->numrows == 0){
      return array();
    } else {
      return db_utils::getColectionByRecord($rsSqlMovAlvara);
    }
  }
  
  public function getUltimaMovimentacao(){
  
    return $this->getMovimentacoesAlvara("q120_sequencial desc limit 1");
  }
  
}
?>
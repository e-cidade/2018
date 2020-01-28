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
 * @fileoverview - Classe de Modelo para movimentaчѕes do Alvarс 
 * @author    Rafael Serpa Nery - rafael.nery@dbseller.com.br	
 * @package   ISSQN
 * @revision  $Author: dbrafael.nery $
 * @version   $Revision: 1.2 $
 */
require_once('libs/db_stdlib.php');
require_once('libs/db_utils.php');

abstract class AlvaraMovimentacao {
  
  /**
   * Cѓdigo com o alvara que serс realizada a movimentaчуo
   * @var integer
   */
  private $iCodigoAlvara        = null;
  
  /**
   * Sequencial da Tabela issmovalvara
   * @var integer
   */
  private $iCodigoMovimentacao  = null;
  
  /**
   * Tipo de Movimentacao do Alvarс
   * Dados cadastrados na tabela isstipomovalvara
   * @var integer
   */
  private $iTipoMovimentacao    = null;
  
  /**
   * Data da ocorrъncia da movimentcao
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
   * Observaчуo da movimentacao
   * @var string
   */
  private $sObservacao          = "";
  
  /**
   * Nњmero do processo do protocolo, na tabela issmovalvaraproc
   * @var integer
   */
  private $iCodigoProcesso      = null;
  
 /**
  * Dias da validade do alvarс
  * @var integer
  */
  private $iValidadeAlvara      = null;
  
  /**
   * Array dos documentos da movimentaчуo
   * @var array
   */
  private $aDocumentos          = array();
  
  /**
   * Construtor da funcao
   */
  function __construct($iCodigoAlvara = null){
    
    if(!empty($iCodigoAlvara)){
      
      $this->iCodigoAlvara = $iCodigoAlvara;
      $this->iIdUsuario    = db_getsession("DB_login");
    } else {
      
      throw new ErrorException("Selecione o Alvarс que deseja realizar a movimentacao");
    }
    
  }
  
  
  /**
   * Salva movimentaчуo do alvarс
   * @see iMovimentacaoAlvara::salvar()
   */  
  protected function salvar() {
    /**
     * Seta variсvel para executar uma nova inserчуo
     */
    $this->setCodigoMovimentacao(null);
    
    if ($this->$iTipoMovimentacao == null) {
      throw new ErrorException("Sem tipo de movimentaчуo setada");
    }
    if(db_utils::inTransaction()){
      
      $oDaoIssMovAlvara  = db_utils::getDAO("issmovalvara",true);
      
      $oDaoIssMovAlvara->q120_issalvara         = $this->getCodigoAlvara();
      $oDaoIssMovAlvara->q120_isstipomovalvara  = $this->getTipoMovimentacao();
      $oDaoIssMovAlvara->q120_dtmov             = $this->getDataMovimentacao();
      $oDaoIssMovAlvara->q120_usuario           = $this->getIdUsuario();
      $oDaoIssMovAlvara->q120_obs               = $this->getObservacao();
      $oDaoIssMovAlvara->q120_validadealvara    = $this->getValidadeAlvara();
      $oDaoIssMovAlvara->q120_issalvara         = $this->getCodigoAlvara();
      $oDaoIssMovAlvara->incluir("");
      
      if($oDaoIssMovAlvara != "0"){
        /**
         * Grava os Documentos 
         */
        $this->gravaDocumentos($this->aDocumentos);
        /**
         * Grava os Processos
         */
        if($this->getCodigoProcesso() == null){
          $this->gravaProcesso();
        }
      } else {
        throw new ErrorException($oDaoIssMovAlvara->erro_msg);
      }
    } else {
      throw new ErrorException("Sem transaчуo ativa no banco de dados");
    }
  }
    
  /**
   * Adiciona documento a lista de documentos da movimentaчуo do alvarс
   * @param integer $iCodigoDocumento - SEQUENCIA TABELA caddocumento
   */
  public function addDocumento($iCodigoDocumento){
    $this->aDocumentos[] = $iCodigoDocumento;
  }
  
  /**
   * Salva os Documentos da movimentaчуo
   * Enter description here ...
   * @param unknown_type $aDocumentos
   */
  private function gravaDocumentos($aDocumentos){
    
    $oDaoIssMovAlvaraDocumento = db_utils::getDAO('issalvaradocumento');
    
    foreach ($aDocumentos as $iDocumento){
      
      $oDaoIssMovAlvaraDocumento->q122_issmovalvara = $this->getCodigoMovimentacao();
      $oDaoIssMovAlvaraDocumento->q122_caddocumento = $iDocumento;
      $oDaoIssMovAlvaraDocumento->incluir("");
      if($oDaoIssMovAlvaraDocumento->erro_status == "0"){
        throw new ErrorException($oDaoIssMovAlvaraDocumento->erro_msg);
      } 
    }
  }

  /**
   * Salva o Processo ligado a movimentacao
   * Enter description here ...
   */
  private function gravaProcesso(){
    
    $oDaoIssMovAlvaraProcesso  = db_utils::getDAO("issmovalvaraprocesso",true);
    
    $oDaoIssMovAlvaraProcesso->q124_codproc      = $this->getCodigoProcesso();
    $oDaoIssMovAlvaraProcesso->q124_issmovalvara = $this->getCodigoMovimentacao();
    $oDaoIssMovAlvaraProcesso->incluir("");
    if($oDaoIssMovAlvaraProcesso->erro_status == "0"){
      throw new ErrorException($oDaoIssMovAlvaraProcesso->erro_msg);
    }
  }

  /**
   * Mщtodo de que busca as movimentaчѕes do alvarс
   * @return array;
   */
  public function getMovimentacoesAlvara(){
    
    $oDaoIssMovAlvara = db_utils::getDAO("issmovalvara",true);
    $sSqlMovAlvara    = $oDaoIssMovAlvara->sql_query_file(null, "*", "q120_issalvara = {$this->getCodigoAlvara()}");
    $rsSqlMovAlvara   = $oDaoIssMovAlvara->sql_record($sSqlMovAlvara);
    
    if($oDaoIssMovAlvara->num_rows == 0){
      return array();
    } else {
      return db_utils::getColectionByRecord($rsSqlMovAlvara);
    }
  }

  public function getCodigoUltimaMovimentacao(){
    
  }
  /**
   * Retorna array com os cѓdigos(caddocumento) de documentos 
   * Depende que o Cѓdigo da Movimentaчуo esteja setado
   */
  public function getDocumentosMovimentacao(){
    
    if($this->iCodigoMovimentacao == null){
      throw new ErrorException("Cѓdigo da movimentaчуo nуo estс setado");
    }
    
    $oDaoIssMovAlvaraDocumento  = db_utils::getDAO('issalvaradocumento');
    $sSqlIssMovAlvaraDocumento  = $oDaoIssMovAlvaraDocumento->sql_query_file(null,"q122_caddocumento", "q122_issmovalvara = {$this->iCodigoMovimentacao}");
    $rsSqlIssMovAlvaraDocumento = $oDaoIssMovAlvaraDocumento->sql_record($sSqlIssMovAlvaraDocumento);
    if($oDaoIssMovAlvaraDocumento->num_rows == 0){
      return array();
    } else {
      
      $aIssMovAlvaraDocumentos = db_utils::getColetionByRecord($rsSqlIssMovAlvaraDocumento);
      
      foreach ($aIssMovAlvaraDocumentos as $oDocumentos) {
        $aDocumentos[] = $oDocumentos->q122_caddocumento;
      }
      return $aDocumentos;
    }
  }
  
  
  /* Setters */
  
  /**
   * Seta valor da variсvel $iCodigoMovimentacao
   * @param integer $iCodigoMovimentacao
   */
  public function setCodigoMovimentacao($iCodigoMovimentacao) {

    $this->iCodigoMovimentacao = $iCodigoMovimentacao;
    return $this;
  }
  
  /**
  * Seta valor da variсvel $iTipoMovimentacao
  * @param integer $iTipoMovimentacao
  */
  public function setTipoMovimentacao($iTipoMovimentacao) {
    $this->iTipoMovimentacao = $iTipoMovimentacao;
    return $this;
  }
  
  /**
  * Seta valor da variсvel $dtMovimentacao
  * @param date $dtMovimentacao
  */
  public function setDataMovimentacao($dtMovimentacao) {
    $this->dtMovimentacao = $dtMovimentacao;
    return $this;
  }
  
  /**
  * Seta valor da variсvel $sObservacao
  * @param strinfg $sObservacao
  */
  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
    return $this;
  }
  
  /**
  * Seta valor da variсvel $iCodigoProcesso
  * @param integer $iCodigoProcesso
  */
  public function setCodigoProcesso($iCodigoProcesso) {
    $this->iCodigoProcesso = $iCodigoProcesso;
    return $this;
  }
  
 /**
  * Seta validade do alvarс $iCodigoProcesso
  * @param integer $iCodigoProcesso
  */
  public function setValidadeAlvara($iValidadeAlvara) {
    $this->iValidadeAlvara = $iValidadeAlvara;
    return $this;
  }
  
  /* Getters */
  
  /**
   * Retorna valor da variсvel $iCodigoAlvara
   * @return integer $iCodigoAlvara
   */
  public function getCodigoAlvara() {
    return $this->iCodigoAlvara;
  }

 /**
  * Retorna valor da variсvel $iCodigoMovimentacao
  * @return integer $iCodigoMovimentacao
  */
  public function getCodigoMovimentacao() {
    return $this->iCodigoMovimentacao;
  }
  
 /**
  * Retorna valor da variсvel $iTipoMovimentacao
  * @return integer $iTipoMovimentacao
  */
  public function getTipoMovimentacao() {
    return $this->iTipoMovimentacao;
  }
  
 /**
  * Retorna valor da variсvel $dtMovimentacao
  * @return date $dtMovimentacao
  */
  public function getDataMovimentacao() {
    return $this->dtMovimentacao;
  }
  
 /**
  * Retorna valor da variсvel $iIdUsuario
  * @return integer $iIdUsuario
  */
  public function getIdUsuario() {
    return $this->iIdUsuario;
  }
  
  /**
  * Retorna valor da variсvel $sObservacao
  * @return integer $sObservacao
  */
  public function getObservacao() {
    return $this->sObservacao;
  }
  
  /**
  * Retorna valor da variсvel $iCodigoProcesso
  * @return integer $iCodigoProcesso
  */
  public function getCodigoProcesso() {
    return $this->iCodigoProcesso;
  }
  
  /**
  * Retorna valor da variсvel $iCodigoProcesso
  * @return integer $iCodigoProcesso
  */
  public function getValidadeAlvara() {
    return $this->$iValidadeAlvara;
  }
  /**
   * Retorna um array com os cѓdigos dos documentos vinculados a movimentacao do alvara;
   */
  public function getDocumentosMovimentacao(){
        
  }
}
?>
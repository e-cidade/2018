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

require_once "libs/exceptions/DBException.php";
require_once "model/issqn/alvara/Alvara.model.php";
require_once "model/configuracao/UsuarioSistema.model.php";


/**
 * @fileoverview - Classe de Modelo para movimentações do Alvará
 * @author    Rafael Serpa Nery - rafael.nery@dbseller.com.br
 * @package   ISSQN
 * @revision  $Author: dbjeferson.belmiro $
 * @version   $Revision: 1.1 $
 */
abstract class MovimentacaoAlvara {

  const TIPO_LIBERACAO              = 1;
  const TIPO_BAIXA                  = 2;
  const TIPO_CANCELAMENTO           = 3;
  const TIPO_RENOVACAO              = 4;
  const TIPO_TRANSFORMACAO          = 5;
  const TIPO_CANCELAMENTO_LIBERACAO = 6;
  const TIPO_CANCELAMENTO_BAIXA     = 7;
  const TIPO_CANCELAMENTO_RENOVACAO = 8;

  /**
   * Instancia do Alvará
   * @var integer
   */
  private $oAlvara;

  /**
   * Sequencial da Tabela issmovalvara
   * @var integer
   */
  private $iCodigo;

  /**
   * Tipo de Movimentacao do Alvará
   * Dados cadastrados na tabela isstipomovalvara
   * @var integer
   */
  private $iTipoMovimentacao;

  /**
   * Data da ocorrência da movimentcao
   * @var date
   */
  private $dtMovimentacao;

 /**
  * Dias da validade do alvará
  * @var integer
  */
  private $iValidadeAlvara;

  /**
   * Id do usuario que realizou a movimentacao
   * @var integer
   */
  private $oUsuario;

  /**
   *
   * Observação da movimentacao
   * @var string
   */
  private $sObservacao;

  /**
   * Número do processo do protocolo, na tabela issmovalvaraproc
   * @var integer
   */
  private $iCodigoProcesso;

  /**
   * Construtor da funcao
   */
  function __construct($iCodigo = null) {

    if( is_null($iCodigo) ) {
      return;
    }

    $oDaoIssMovAlvara  = db_utils::getDAO("issmovalvara");
    $sSqlMovimentacao  = $oDaoIssMovAlvara->sql_query_file($iCodigo);
    $rsMovimentacao    = $oDaoIssMovAlvara->sql_record($sSqlMovimentacao);

    if ($oDaoIssMovAlvara->numrows == 0) {
      throw new DBException("Nenhum movimentação encontrado com este código: " . $iCodigo);
    }

    $oDadosMovimentacao = db_utils::fieldsMemory($rsMovimentacao, 0);

    $this->iCodigo           = $oDadosMovimentacao->q120_sequencial;
    $this->oAlvara           = new Alvara( $oDadosMovimentacao->q120_issalvara );
    $this->iTipoMovimentacao = $oDadosMovimentacao->q120_isstipomovalvara;
    $this->dtMovimentacao    = $oDadosMovimentacao->q120_dtmov;
    $this->iValidadeAlvara   = $oDadosMovimentacao->q120_validadealvara;
    $this->oUsuario          = new UsuarioSistema($oDadosMovimentacao->q120_usuario);
    $this->sObservacao       = $oDadosMovimentacao->q120_obs;
  }

  /**
   * Salva movimentação do alvará
   * @see iMovimentacaoAlvara::salvar()
   */
  protected function salvar() {

    /**
     * Seta variável para executar uma nova inserção
     */
    $this->setCodigo(null);

    if ($this->iTipoMovimentacao == null) {
      throw new DBException("Sem tipo de movimentação setada");
    }
    if ( !db_utils::inTransaction() ) {
      throw new DBException("Sem transação ativa no banco de dados");
    }

    $oDaoIssMovAlvara  = db_utils::getDAO("issmovalvara",true);

    $oDaoIssMovAlvara->q120_isstipomovalvara  = $this->iTipoMovimentacao;
    $oDaoIssMovAlvara->q120_dtmov             = $this->dtMovimentacao;
    $oDaoIssMovAlvara->q120_usuario           = $this->getUsuario()->getIdUsuario();
    $oDaoIssMovAlvara->q120_obs               = $this->sObservacao;
    $oDaoIssMovAlvara->q120_validadealvara    = $this->iValidadeAlvara;
    $oDaoIssMovAlvara->q120_issalvara         = $this->getAlvara()->getCodigo();
    $oDaoIssMovAlvara->incluir(null);

    /**
     * Seta código atual da movimentacao
     */
    $this->iCodigo = $oDaoIssMovAlvara->q120_sequencial;

    if ( $oDaoIssMovAlvara->erro_status == "0" ) {
      throw new DBException($oDaoIssMovAlvara->erro_msg);
    }

    /**
     * Grava os Documentos
     */
    $this->getAlvara()->gravaDocumentos();

    /**
     * Grava os Processos
     */
    if ($this->iCodigoProcesso != null || $this->iCodigoProcesso != "") {
      $this->gravaProcesso();
    }
  }


  /**
   * Salva o Processo ligado a movimentacao
   * Enter description here ...
   */
  private function gravaProcesso() {

    if ($this->iCodigoProcesso != "" || $this->iCodigoProcesso != null) {

      $oDaoIssMovAlvaraProcesso                    = db_utils::getDAO("issmovalvaraprocesso");
      $oDaoIssMovAlvaraProcesso->q124_codproc      = $this->iCodigoProcesso;
      $oDaoIssMovAlvaraProcesso->q124_issmovalvara = $this->iCodigo;
      $oDaoIssMovAlvaraProcesso->incluir("");

      if ($oDaoIssMovAlvaraProcesso->erro_status == "0") {
        throw new DBException($oDaoIssMovAlvaraProcesso->erro_msg);
      }
    }
  }

  /**
   * Define o codigo da movimentacao
   * @param integer $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * Retorna o codigo da movimentacao
   *
   * @access public
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
  * Seta valor da variável $iTipoMovimentacao
  * @param integer $iTipoMovimentacao
  */
  public function setTipoMovimentacao($iTipoMovimentacao) {

    $this->iTipoMovimentacao = $iTipoMovimentacao;
    return $this;
  }

  /**
  * Seta valor da variável $dtMovimentacao
  * @param date $dtMovimentacao
  */
  public function setDataMovimentacao($dtMovimentacao) {

    $this->dtMovimentacao = $dtMovimentacao;
    return $this;
  }

  /**
  * Seta valor da variável $sObservacao
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
  * Seta valor da variável $iCodigoProcesso
  * @param integer $iCodigoProcesso
  */
  public function setCodigoProcesso($iCodigoProcesso) {

    $this->iCodigoProcesso = $iCodigoProcesso;
    return $this;
  }

 /**
  * Seta validade do alvará $iCodigoProcesso
  * @param integer $iCodigoProcesso
  */
  public function setValidadeAlvara($iValidadeAlvara) {

    $this->iValidadeAlvara = $iValidadeAlvara;
    return $this;
  }

  /**
   * Retorna o Alvara que da Movimentacao  
   * @return Alvara
   */
  public function getAlvara() {
    return $this->oAlvara;
  }

  /**
   * Retorna o Alvara que da Movimentacao  
   * @return Alvara
   */
  public function setAlvara( Alvara $oAlvara ) {
    
    $this->oAlvara = $oAlvara;
    return;
  }

 /**
  * Retorna valor da variável $iTipoMovimentacao
  * @return integer $iTipoMovimentacao
  */
  public function getTipoMovimentacao() {
    return $this->iTipoMovimentacao;
  }

 /**
  * Retorna valor da variável $dtMovimentacao
  * @return date $dtMovimentacao
  */
  public function getDataMovimentacao() {
    return $this->dtMovimentacao;
  }

 /**
  * Retorna usuario do sistema que incluiu movimentacao
  * @return UsuarioSistema
  */
  public function getUsuario() {
    return $this->oUsuario;
  }

  /**
   * Define usuario do sistema
   *
   * @access public
   * @return void
   */
  public function setUsuario(UsuarioSistema $oUsuario) {
    $this->oUsuario = $oUsuario;
  }

  /**
  * Retorna valor da variável $sObservacao
  * @return integer $sObservacao
  */
  public function getObservacao() {
    return $this->sObservacao;
  }

  /**
  * Retorna valor da variável $iCodigoProcesso
  * @return integer $iCodigoProcesso
  */
  public function getCodigoProcesso() {
    return $this->iCodigoProcesso;
  }

  /**
  * Retorna valor da varável $iCodigoProcesso
  * @return integer $iCodigoProcesso
  */
  public function getValidadeAlvara() {
    return $this->iValidadeAlvara;
  }

  /**
   * Método de que busca as movimentações do alvará
   * @return array;0
   * @TODO REMOVER
   */
  public function getMovimentacoesAlvara($sOrdem='') {

    $oDaoIssMovAlvara = db_utils::getDAO("issmovalvara");
    $sSqlMovAlvara    = $oDaoIssMovAlvara->sql_query(null, "*", $sOrdem, "q120_issalvara = {$this->getAlvara()->getCodigo()}");
    $rsSqlMovAlvara   = $oDaoIssMovAlvara->sql_record($sSqlMovAlvara);

    if ($oDaoIssMovAlvara->numrows == 0) {
      return array();
    }

    return db_utils::getColectionByRecord($rsSqlMovAlvara);
  }

  /**
   * @TODO REMOVER
   */
  public function getUltimaMovimentacao(){
    return $this->getMovimentacoesAlvara("q120_sequencial desc limit 1");
  }

  public abstract function processar();

}
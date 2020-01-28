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
 *
 * Classe para cadastro de contabancaria e agencia
 * Utilizando as tabelas db_banco, bancoagencia e contabancсria
 *
 * @author Rafael Serpa Nery rafael.nery@dbseller.com.br
 * @package Caixa
 * @revision $Author: dbvitor $
 * @version $Revision: 1.7 $
 *
 */
class ContaBancaria {
  
  /**
   * Tabela bancoagencia
   * @var string
   */
  protected $sCodigoBanco;
  
  /**
   * Tabela db_bancos
   * @var string
   */
  protected $sDescricaoBanco;
  
  /**
   * Tabela bancoagencia
   * @var string
   */
  protected $sNumeroAgencia;
  
  /**
   * Tabela bancoagencia
   * @var string
   */
  protected $sDVAgencia;
  
  /**
   * Tabela contabancaria
   * @var string
   */
  protected $sNumeroConta;
  
  /**
   * Tabela contabancaria
   * @var string
   */
  protected $sDVConta;
  
  /**
   * Tabela contabancaria
   * @var string
   */
  protected $sIdentificador;
  
  /**
   * Tabela contabancaria
   * @var string
   */
  protected $sCodigoOperacao;
  
  /**
   * Tabela contabancaria
   * @var integer
   */
  protected $iTipoConta;
  
  /**
   * Tabela contabancaria
   * @var boolean
   */
  protected $lContaSistema;

  /**
   * sequencial da tabela bancoagencia
   * @var integer
   */
  protected $iSequencialBancoAgencia = null;
  
  /**
   * sequencial  da tabela contabancaria
   * @var integer
   */
  protected $iSequencialContaBancaria = null;
  
  /**
   *
   * Cѓdigo do erro
   * 0 = Erro
   * 1 = Sem erro
   * @var integer
   */
  protected $iErrorStatus   = 1;
  
  /**
   *
   * string com a mesngaem de erro
   * @var string
   */
  protected $sErrorMessage  = "";
  
  /**
   * valida erro no banco
   * @var boolean
   */
  protected $lErrorBanco    = false;
  
  
  /**
   * Diz se a conta bancсria pertence ao plano de contas ou nуo
   * @var boolean
   */
  protected $lPlanoConta    = false;
  
  /**
   * Construtor da classe
   *
   * @param integer CodigoContaBancaria
   */
  function __construct($iCodigoContaBancaria = null) {
    
    if (!empty($iCodigoContaBancaria)) {

      $oDaoContaBancaria              = db_utils::getDao("contabancaria");
      $sSqlContaBancaria              = $oDaoContaBancaria->sql_query($iCodigoContaBancaria);
      $rsSqlContaBancaria             = $oDaoContaBancaria->sql_record($sSqlContaBancaria);
      $iNumRowsContaBancaria          = $oDaoContaBancaria->numrows;
      $oRetorno->numrows              = $iNumRowsContaBancaria;
      
      if ($iNumRowsContaBancaria > 0) {
        
         $oContaBancaria                    = db_utils::fieldsMemory($rsSqlContaBancaria,0);
         $this->setSequencialBancoAgencia($oContaBancaria->db89_sequencial);
         $this->setCodigoBanco($oContaBancaria->db89_db_bancos);
         $this->setDescricaoBanco($oContaBancaria->db90_descr);
         $this->setNumeroAgencia($oContaBancaria->db89_codagencia);
         $this->setDVAgencia($oContaBancaria->db89_digito);
         $this->setNumeroConta($oContaBancaria->db83_conta);
         $this->setDVConta($oContaBancaria->db83_dvconta);
         $this->setIdentificador($oContaBancaria->db83_identificador);
         $this->setCodigoOperacao($oContaBancaria->db83_codigooperacao);
         $this->setTipoConta($oContaBancaria->db83_tipoconta);
         $this->setSequencialContaBancaria($oContaBancaria->db83_sequencial);
         unset($oContaBancaria);
      }
    }
  }
  /**
   * Salva os dados na tabela bancoagencia e contabancaria
   *
   */
  public function salvar(){
    
    if (db_utils::inTransaction()) {
      
      $oDaoBancoAgencia  = db_utils::getDao("bancoagencia");
      $oDaoContaBancaria = db_utils::getDao("contabancaria");
      
      /**
       * Executando as funчѕes de inclusуo ou alteraчуo da tabela bancoagencia
       */
      $oDaoBancoAgencia->db89_digito     = $this->getDVAgencia();
      $oDaoBancoAgencia->db89_db_bancos  = $this->getCodigoBanco();
      $oDaoBancoAgencia->db89_codagencia = $this->getNumeroAgencia();
      
      $this->setSequencialBancoAgencia();
      
      if($this->getSequencialBancoAgencia() == null){
        
        $oDaoBancoAgencia->incluir(null);
        if ($oDaoBancoAgencia->erro_status == "0") {
          throw new Exception("Erro BancoAgencia: ".$oDaoBancoAgencia->erro_msg);
        }
      }
      
      /**
       * caso nуo ocorra erro inclui na tabela caontabancaria
       */
      if(($oDaoBancoAgencia->erro_status != "0" && $this->getSequencialBancoAgencia() == null) || $this->getSequencialBancoAgencia() != null){
         
         /**
          * Incluindo na tabela contabancaria
          */
        $this->setSequencialBancoAgencia($oDaoBancoAgencia->db89_sequencial);
        $oDaoContaBancaria->db83_descricao       = " ";
        $oDaoContaBancaria->db83_bancoagencia    = $this->getSequencialBancoAgencia();
        $oDaoContaBancaria->db83_conta           = $this->getNumeroConta();
        $oDaoContaBancaria->db83_dvconta         = $this->getDVConta();
        $oDaoContaBancaria->db83_identificador   = "{$this->getIdentificador()}";
        $oDaoContaBancaria->db83_codigooperacao  = $this->getCodigoOperacao();
        $oDaoContaBancaria->db83_tipoconta       = $this->getTipoConta();
        $oDaoContaBancaria->db83_contaplano      = $this->isPlanoConta()?"true":"false";

        /**
         * Valida se щ para incluir ou alterar um registro
         */
        $this->setSequencialContaBancaria();
        
        if($this->getSequencialContaBancaria() == null){
         
          $oDaoContaBancaria->incluir(null);
          
          if($oDaoContaBancaria->erro_status != "0"){
            $this->setSequencialContaBancaria($oDaoContaBancaria->db83_sequencial);
          } else {
           
            throw new Exception("Erro ContaBancaria :\n\n".$oDaoContaBancaria->erro_sql);
            $this->lErrorBanco  = true;
          }
        }
      } else {
         
         $this->lErrorBanco = true;
         throw new Exception("Erro BancoAgencia:\n\n".$oDaoBancoAgencia->erro_sql);
      }
    } else {
      
       $this->lErrorBanco = true;
       throw new Exception("Erro geral:\n\n"."Nуo existe transaчуo ativa para salvar os dados.!");
    }
  }
  
  
  /**
   * @return integer
   */
  public function getCodigoBanco() {
  	return $this->sCodigoBanco;
  }
  
  /**
   * @return integer
   */
  public function getTipoConta() {
  	return $this->iTipoConta;
  }
  
  /**
   * @return boolean
   */
  public function getContaSistema() {
  	return $this->lContaSistema;
  }
  
  /**
   * @return string
   */
  public function getCodigoOperacao() {
  	return $this->sCodigoOperacao;
  }
  
  /**
   * @return string
   */
  public function getDescricaoBanco() {
  	return $this->sDescricaoBanco;
  }
  
  /**
   * @return string
   */
  public function getDescricaoConta() {
  	return $this->sDescricaoConta;
  }
  
  /**
   * @return string
   */
  public function getDVAgencia() {
  	return $this->sDVAgencia;
  }
  
  /**
   * @return string
   */
  public function getDVConta() {
  	return $this->sDVConta;
  }
  
  /**
   * @return string
   */
  public function getIdentificador() {
  	return $this->sIdentificador;
  }
  
  /**
   * @return string
   */
  public function getNumeroAgencia() {
  	return $this->sNumeroAgencia;
  }
  
  /**
   * @return string
   */
  public function getNumeroConta() {
  	return $this->sNumeroConta;
  }
  /**
   * @return boolean
   */
  public function getErroBanco() {
  	return $this->lErrorBanco;
  }
  
  /**
   * @return integer
   */
  public function getSequencialBancoAgencia() {
  	return $this->iSequencialBancoAgencia;
  }

  /**
   * @return integer
   */
  public function getSequencialContaBancaria() {
  	return $this->iSequencialContaBancaria;
  }
    
  /**
   * @return integer
   */
  public function setSequencialBancoAgencia($sequencial = null) {

    if ($sequencial == null) {
      
      $oDaoBancoAgencia  = new cl_bancoagencia();
      $sSqlAgencias      = $oDaoBancoAgencia->sql_query("","db89_sequencial","",
                                                          " db90_codban     = '{$this->getCodigoBanco()}'   and
                                                            db89_codagencia = '{$this->getNumeroAgencia()}' and
                                                            db89_digito     = '{$this->getDVAgencia()}'
                                                  ");
      $rsSqlAgencias     = $oDaoBancoAgencia->sql_record($sSqlAgencias);
      $iNumRowsAgencia   = $oDaoBancoAgencia->numrows;
      
      /**
       * Verifica se trouxe registro e seta o sequencial da tabela bancoagencia na variсvel $iSeqAgencia
       * caso contrсrio seta a variсvel como nula
       */
      if ($iNumRowsAgencia > 0 ) {
         
        $oAgencia    = db_utils::fieldsMemory($rsSqlAgencias,0);
        $this->iSequencialBancoAgencia = $oAgencia->db89_sequencial;
      } else {
         $this->iSequencialBancoAgencia = null;
      }
    } else {
       $this->iSequencialBancoAgencia = $sequencial;
    }
    return $this;
  }
  
  /**
   * @return integer
   */
  public function setSequencialContaBancaria($iSequencial = null) {
    if($iSequencial == null){
      $oDaoContaBancaria = new cl_contabancaria();
      
      $sContaPlano = $this->isPlanoConta() ? 'true':'false';
      $sSqlContas        = $oDaoContaBancaria->sql_query("","db83_sequencial","",
                                                           " db90_codban          = '{$this->getCodigoBanco()}'               and
                                                             db89_codagencia      = '{$this->getNumeroAgencia()}'             and
                                                             db83_conta           = '{$this->getNumeroConta()}'               and
                                                             db83_dvconta         = '{$this->getDVConta()}'                   and
                                                             db83_codigooperacao  = '{$this->getCodigoOperacao()}'            and
                                                             db83_contaplano      = {$sContaPlano}
                                                    ");
      $rsSqlContas       = $oDaoContaBancaria->sql_record($sSqlContas);
      $iNumRowsConta     = $oDaoContaBancaria->numrows;
      
      /**
       * Verifica se trouxe registro e seta o sequencial da tabela contabancaria na variсvel $iSeqConta
       * caso contrсrio seta a variсvel como nula
       */
      if ($iNumRowsConta > 0 ) {
         
        $oConta                        = db_utils::fieldsMemory($rsSqlContas,0);
        $this->iSequencialContaBancaria = $oConta->db83_sequencial;
      } else {
        $this->iSequencialContaBancaria= null;
      }
    } else {
      $this->iSequencialContaBancaria = $iSequencial;
    }
    return $this;
  }

    
  /**
   * @param string $sCodigoBanco
   */
  public function setCodigoBanco($sCodigoBanco) {
  	
  	$this->sCodigoBanco = $sCodigoBanco;
  	return $this;
  }
  
  
  /**
   * @param integer $iTipoConta
   * @return ContaBancaria
   */
  public function setTipoConta($iTipoConta) {
  	
  	$this->iTipoConta = $iTipoConta;
  	return $this;
  }
  
  /**
   * @param string $sCodigoOperacao
   */
  public function setCodigoOperacao($sCodigoOperacao) {
  	
  	$this->sCodigoOperacao = $sCodigoOperacao;
  	return $this;
  }
  
  /**
   * @param string $sDescricaoBanco
   * @return ContaBancaria
   */
  public function setDescricaoBanco($sDescricaoBanco) {
  	
  	$this->sDescricaoBanco = $sDescricaoBanco;
  	return $this;
  }
  
  /**
   * @param string $sDVAgencia
   * @return ContaBancaria
   */
  public function setDVAgencia($sDVAgencia) {
  	
  	$this->sDVAgencia = $sDVAgencia;
  	return $this;
  }
  
  /**
   * @param string $sDVConta
   */
  public function setDVConta($sDVConta) {
  	
  	$this->sDVConta = $sDVConta;
    return $this;
  }
  
  /**
   * @param string $sIdentificador
   */
  public function setIdentificador($sIdentificador) {
  	
  	$this->sIdentificador = $sIdentificador;
    return $this;
  }
  
  /**
   * @param string $sNumeroAgencia
   */
  public function setNumeroAgencia($sNumeroAgencia) {
  	
  	$this->sNumeroAgencia = $sNumeroAgencia;
  	return $this;
  }
  
  /**
   * @param string $sNumeroConta
   */
  public function setNumeroConta($sNumeroConta) {
  	
  	$this->sNumeroConta = $sNumeroConta;
  	return $this;
  }
  
  
  /**
   * Seta status do erro
   * @param unknown_type $iErrorStatus
   */
  public function setErrorStatus($iErrorStatus) {

    $this->iErrorStatus = $iErrorStatus;
     return $this;
  }
  
  /**
   *
   * define a mensagem de erro
   * @param $sErrorMessage
   */
  public function setErrorMessage($sErrorMessage) {
      
       $this->sErrorMessage = $sErrorMessage;
       return $this;
    }

  public function getErrorStatus(){
    return $this->iErrorStatus;
  }
  
  public function getErrorMessage(){
    return $this->sErrorMessage;
  }
  
  /**
   * Define se a conta cadastrada щ utilizado no plano de contas.
   * @return ContaBancaria
   */
  public function setPlanoConta($lPlanoConta) {

    $this->lPlanoConta = $lPlanoConta;
    return $this;
  }
  
  public function isPlanoConta() {
    return $this->lPlanoConta;
  }

  public function getDadosConta() {
         
    $sDescricaoConta  = "Bco: {$this->getCodigoBanco()} Ag: {$this->getNumeroAgencia()}-{$this->getDVAgencia()} ";
    $sDescricaoConta .= "Cta: {$this->getNumeroConta()}-{$this->getDVConta()}";
    return $sDescricaoConta;
    
  }
}

?>
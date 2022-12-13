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

require_once("model/financeiro/ContaBancaria.model.php");
/**
 * 
 * Classe para cadastro de Favorecido de custas da cobrança registrada
 * @author Rafael Serpa Nery rafael.nery@dbseller.com.br
 * @package Configuração
 * @revision $Author: dbrafael.nery $
 * @version $Revision: 1.4 $
 *
 */
class Favorecido extends ContaBancaria{
  
  /**
   * Sequencial do Favorecido
   * @var integer
   */
  protected $iCodigoFavorecido;
  /**
   * Tabela favorecido
   * @var integer
   */  
  protected $iNumCgm; 
  
  protected $sContaInterna;
  
   /**
   * 
   * Código do erro
   * 0 = Erro
   * 1 = Sem erro
   * @var integer
   */
  protected $iErrorStatusFavorecido   = 1;
  /**
   * 
   * string com a mesngaem de erro
   * @var string
   */
  protected $sErrorMessageFavorecido  = "";
  /**
   * valida erro no banco
   * @var boolean
   */
  protected $lErrorBancoFavorecido    = false;
  
  /**
   * Construtor da classe
   *
   * @param integer $iCodigoFavorecido
   */
  function __construct($iCodigoFavorecido = null) {

    if (!empty($iCodigoFavorecido)) {
  
      $this->iCodigoFavorecido = $iCodigoFavorecido;
      $oDaoFavorecido     = db_utils::getDao("favorecido");      
      $sSqlFavorecido     = $oDaoFavorecido->sql_query_dados($iCodigoFavorecido);
      $rsFavorecido       = $oDaoFavorecido->sql_record($sSqlFavorecido);
      $oDadosFavorecido   = db_utils::fieldsMemory($rsFavorecido, 0);
      
      $this->setCodigoFavorecido($oDadosFavorecido->v86_sequencial);
      $this->setNumCgm          ($oDadosFavorecido->v86_numcgm);
      $this->setContaInterna    ($oDadosFavorecido->v86_containterna);
      parent::__construct($oDadosFavorecido->v86_contabancaria);      
      unset($oDadosFavorecido);  
    }
  }
  /**
   * Salva dados do favorecido contabancaria
   */
  public function salvar(){
    
    $oDaoFavorecido    = new cl_favorecido();
    
    try{
      
      db_inicio_transacao();
      
      parent::salvar();
      $oDaoFavorecido->v86_contabancaria    = $this->getSequencialContaBancaria();
      $oDaoFavorecido->v86_numcgm           = $this->getNumCgm();
      $oDaoFavorecido->v86_containterna     = $this->getContaInterna();
  
      if($this->getCodigoFavorecido() != null){
      
        $oDaoFavorecido->v86_sequencial   = $this->getCodigoFavorecido();
        $oDaoFavorecido->alterar($this->getCodigoFavorecido());
      } else {
        $oDaoFavorecido->incluir(null);
      }
      if($oDaoFavorecido->erro_status != "0"){
         
        $this->setErrorMessageFavorecido("Dados salvos com sucesso");
        $this->setCodigoFavorecido($oDaoFavorecido->v86_sequencial);
        $this->setErrorStatusFavorecido(1);
      } else {
        throw new Exception("Erro Favorecido:\\n\\n".$oDaoFavorecido->erro_sql);
      }
      db_fim_transacao(false);
      
    } catch (Exception $e){
      
      $this->setErrorStatusFavorecido(2);
      $this->setErrorMessageFavorecido($e->getMessage());
    }
  }

  /**
   * exclui favorecido
   */
  public function excluir(){
    
    $oDaoFavorecido    = new cl_favorecido();
    try{
      
      db_inicio_transacao();
      $oDaoFavorecido->excluir(null,"v86_numcgm = ".$this->getNumCgm());
      
      if($oDaoFavorecido->erro_status == "0"){
        
        throw new Exception($oDaoFavorecido->erro_msg);
      }

      $this->setErrorMessageFavorecido($oDaoFavorecido->erro_msg);
      $this->setErrorStatusFavorecido('1');
      db_fim_transacao(false);
      
    } catch(Exception $e) {

      $this->setErrorMessageFavorecido($e->getMessage());
      $this->setErrorStatusFavorecido('2');
    }
  }
  
  /**
   * @return integer
   */
  public function getCodigoFavorecido() {
  	return $this->iCodigoFavorecido;
  }
  
  /**
   * @return integer
   */
  public function getNumCgm() {
  	return $this->iNumCgm;
  }
  
    /**
   * @return string
   */
  public function getContaInterna() {
  	return $this->sContaInterna;
  }
  
  /**
   * @return boolean
   */
  public function getErroBancoFavorecido() {
  	return $this->lErrorBancoFavorecido;
  }
  /**
   * @param integer $iCodigoFavorecido
   */
  public function setCodigoFavorecido($iCodigoFavorecido = null) {
  	
  	$this->iCodigoFavorecido = $iCodigoFavorecido;
  	return $this;
  }
  
  /**
   * @param integer $iNumCgm
   */
  public function setNumCgm($iNumCgm) {
  	
  	$this->iNumCgm = $iNumCgm;
  	return $this;
  }
  
  public function setContaInterna($sContaInterna){
    
    $this->sContaInterna = $sContaInterna;
  	return $this;
  }
  /**
   * Seta status do erro
   * @param unknown_type $iErrorStatusFavorecido
   */
  public function setErrorStatusFavorecido($iErrorStatusFavorecido) {

    $this->iErrorStatusFavorecido = $iErrorStatusFavorecido;
     return $this;
  }
  public function setErrorBancoFavorecido($lErro){
    $this->lErrorBancoFavorecido = $lErro;
    return $this;
  }
  /**
   * 
   * define a mensagem de erro
   * @param $sErrorMessageFavorecido
   */
  public function setErrorMessageFavorecido($sErrorMessageFavorecido) {
      
       $this->sErrorMessageFavorecido = $sErrorMessageFavorecido;
       return $this;
    }

  public function getErrorStatusFavorecido(){
    return $this->iErrorStatusFavorecido;
  }
  
  public function getErrorMessageFavorecido(){
    return $this->sErrorMessageFavorecido;
  }
}

?>
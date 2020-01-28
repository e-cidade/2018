<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
 * @package Habitacao
 *
 */
class InscricaoHabitacao {
  
  protected $iCodigo;
  
  protected $iCandidato;
  
  protected $iPrograma;
  
  protected $iUsuario;
  
  protected $sHora; 
  
  protected $data;
  
  protected $iTipoInscricao;
  
  protected $iSituacao;
  
  protected $iPrioridade;
  
  public function __construct($iInscricao = null) {
    
    if (!empty($iInscricao)) {
      
      $oDaoInscricao = new cl_habitinscricao;
      $sSqlInscricao = $oDaoInscricao->sql_query_file($iInscricao);
      $rsInscricao   = $oDaoInscricao->sql_record($sSqlInscricao);
      if ($oDaoInscricao->numrows > 0) {
        
        $oDadosInscricao      = db_utils::fieldsMemory($rsInscricao, 0);
        $this->iCodigo        = $oDadosInscricao->ht15_sequencial;
        $this->iCandidato     = $oDadosInscricao->ht15_candidato;
        $this->iPrioridade    = $oDadosInscricao->ht15_tipoprioridade;
        $this->iTipoInscricao = $oDadosInscricao->ht15_tipoinscricao;
        $this->iPrograma      = $oDadosInscricao->ht15_habitprograma;
        $this->iUsuario       = $oDadosInscricao->ht15_id_usuario;
        $this->sHora          = $oDadosInscricao->ht15_id_usuario;
        $this->iSituacao      = $oDadosInscricao->ht15_habitsituacaoinscricao;
      }
    }
  }
  
  /**
   * @return integer
   */
  public function getCodigo() {

    return $this->iCodigo;
  }
  
  /**
   * @return unknown
   */
  public function getTipoInscricao() {

    return $this->iTipoInscricao;
  }
  
  /**
   * @param unknown_type $iItipoInscricao
   */
  public function setTipoInscricao($iTipoInscricao) {

    $this->iTipoInscricao = $iTipoInscricao;
  }
  
  /**
   * @return unknown
   */
  public function getPrioridade() {

    return $this->iPrioridade;
  }
  
  /**
   * @param unknown_type $iPrioridade
   */
  public function setPrioridade($iPrioridade) {

    $this->iPrioridade = $iPrioridade;
  }
  
  /**
   * @return unknown
   */
  public function getPrograma() {

    return $this->iPrograma;
  }
  
  /**
   * @param unknown_type $iPrograma
   */
  public function setPrograma($iPrograma) {

    $this->iPrograma = $iPrograma;
  }
  
  /**
   * @return unknown
   */
  public function getSituacao() {

    return $this->iSituacao;
  }
  
  /**
   * @param unknown_type $iSituacao
   */
  public function setSituacao($iSituacao) {

    $this->iSituacao = $iSituacao;
  }
  
  /**
   * @return unknown
   */
  public function getUsuario() {

    return $this->iUsuario;
  }
  
  /**
   * @return unknown
   */
  public function getHora() {

    return $this->sHora;
  }
  
  function save($iCandidato) {
    
    $oDaoInscricao                              = db_utils::getDao("habitinscricao");
    $oDaoInscricao->ht15_candidato              = $iCandidato;
    $oDaoInscricao->ht15_habitprograma          = $this->getPrograma();
    $oDaoInscricao->ht15_id_usuario             = db_getsession("DB_id_usuario");
    $oDaoInscricao->ht15_tipoinscricao          = $this->getTipoInscricao();
    $oDaoInscricao->ht15_habitsituacaoinscricao = $this->getSituacao();
    $oDaoInscricao->ht15_tipoprioridade         = "".$this->getPrioridade()."";
    if (empty($this->iCodigo)) {

      $oDaoInscricao->ht15_datalancamento         =  date("Y-m-d", db_getsession("DB_datausu"));
      $oDaoInscricao->ht15_hora                   =  db_hora();
      $oDaoInscricao->incluir(null);
      $this->iCodigo = $oDaoInscricao->ht15_sequencial;
      
    } else {
      
      $oDaoInscricao->ht15_sequencial = $this->iCodigo;
      $oDaoInscricao->alterar($this->getCodigo());
    }
    
    if ($oDaoInscricao->erro_status == 0) {
      throw new Exception("Erro ao salvar dados da inscrição!\\n{$oDaoInscricao->erro_msg}");
    }
  }
  
  function desistencia($sMotivo) {
  	
    if (!db_utils::inTransaction()) {
      throw new Exception("Nenhuma transação com o banco de dados aberta!\\n\\nInclusão cancelado.");
    }
  	
    if (empty($sMotivo)) {
    	throw new Exception("Nenhum motivo da desistência informado!\\n\\nInclusão cancelado.");
    }
    
    $iCodigoInscricao = $this->getCodigo();
    if (empty($iCodigoInscricao)) {
    	throw new Exception("Nenhuma inscrição informada!\\n\\nInclusão cancelado.");
    }
    
    $iCodigoSituacao = $this->getSituacao();
    if (empty($iCodigoSituacao)) {
      throw new Exception("Nenhuma situação informada!\\n\\nInclusão cancelado.");
    }
    
  	$oDaoInscricao                                 = db_utils::getDao("habitinscricao");
  	$oDaoInscricaoDesistencia                      = db_utils::getDao("habitinscricaodesistencia");

  	$oDaoInscricaoDesistencia->ht22_habitinscricao = $this->getCodigo();
  	$oDaoInscricaoDesistencia->ht22_id_usuario     = db_getsession("DB_id_usuario");
  	$oDaoInscricaoDesistencia->ht22_data           = date("Y-m-d", db_getsession("DB_datausu"));
  	$oDaoInscricaoDesistencia->ht22_hora           = db_hora();
  	$oDaoInscricaoDesistencia->ht22_situacao       = $this->getSituacao();
  	$oDaoInscricaoDesistencia->ht22_motivo         = $sMotivo;
  	$oDaoInscricaoDesistencia->incluir(null);
  	if ($oDaoInscricaoDesistencia->erro_status == 0) {
  		
  		$sMensagem = "Erro ao incluir dados da inscrição desistência! \\n{$oDaoInscricaoDesistencia->erro_msg}";
  		throw new Exception($sMensagem);
  	}
  	
  	$oDaoInscricao->ht15_sequencial             = $this->getCodigo();
  	$oDaoInscricao->ht15_habitsituacaoinscricao = 4;
    $oDaoInscricao->alterar($this->getCodigo());
    if ($oDaoInscricao->erro_status == 0) {
    	
      $sMensagem = "Erro ao alterar situação da incrição! \n {$oDaoInscricao->erro_msg}";
      throw new Exception($sMensagem);
    }
    
    return $this;
  }
}
?>
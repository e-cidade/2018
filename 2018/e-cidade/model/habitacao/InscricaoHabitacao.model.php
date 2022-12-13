<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
  
  protected $iInteressePrograma;
    
  protected $iPrioridade;
    
  protected $sLembrete;  
  
  public function __construct($iInscricao = null) {
    
    if (!empty($iInscricao)) {
      
      $oDaoInscricao = db_utils::getDao('habitinscricao');
      $sSqlInscricao = $oDaoInscricao->sql_query($iInscricao);
      $rsInscricao   = $oDaoInscricao->sql_record($sSqlInscricao);
      
      if ($oDaoInscricao->numrows > 0) {
      	        
        $oDadosInscricao = db_utils::fieldsMemory($rsInscricao,0);        
        $this->setCodigo    ($oDadosInscricao->ht15_sequencial);
        $this->setPrioridade($oDadosInscricao->ht15_tipoprioridade);
      }
    }
  }
  
  public function getCodigo() {
    return $this->iCodigo;
  }
  
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }
  
  public function getPrioridade() {
    return $this->iPrioridade;
  }
  
  public function setPrioridade($iPrioridade) {
    $this->iPrioridade = $iPrioridade;
  }
  
  public function getInteressePrograma() {
    return $this->iInteressePrograma;
  }
  
  public function setinteressePrograma($iInteressePrograma) {
    $this->iInteressePrograma = $iInteressePrograma;
  }
  
  public function getLembrete() {
    return $this->sLembrete;
  }
  
  public function setLembrete($sLembrete) {
    $this->sLembrete = $sLembrete;
  }
  
  public function salvar() {
    
    if (!db_utils::inTransaction()) {
      throw new Exception("Nenhuma transaчуo com o banco de dados aberta!\\n\\nInclusуo cancelada.");
    }    
    
    $oDaoInscricao = db_utils::getDao("habitinscricao");
    
    $oDaoInscricao->ht15_sequencial                      = $this->getCodigo();
    $oDaoInscricao->ht15_habitcandidatointeresseprograma = $this->getInteressePrograma();
    $oDaoInscricao->ht15_tipoprioridade                  = $this->getPrioridade();
    $oDaoInscricao->ht15_lembrete                        = $this->getLembrete();
    
    if (empty($this->iCodigo)) {

      $oDaoInscricao->ht15_id_usuario     = db_getsession("DB_id_usuario");
      $oDaoInscricao->ht15_datalancamento = date("Y-m-d", db_getsession("DB_datausu"));
      $oDaoInscricao->ht15_hora           = db_hora();
      $oDaoInscricao->incluir(null);
      
      $this->setCodigo($oDaoInscricao->ht15_sequencial);
 
      $oInteressePrograma = new InteresseProgramaHabitacao($this->getInteressePrograma());      
      $iCodTransferencia  = $oInteressePrograma->getProcesso()->transferirPorAndamentoPadrao();
      $iProximoDepto      = $oInteressePrograma->getProcesso()->getProximoDeptoAndamentoPadrao();

      $iUsuario           = db_getsession( "DB_id_usuario" );
      $iCodRecebimento    = $oInteressePrograma->getProcesso()->receber($iCodTransferencia,$iProximoDepto,$iUsuario);
      
    } else {
      
      $oDaoInscricao->alterar($this->getCodigo());
    }
    
    if ($oDaoInscricao->erro_status == 0) {
      throw new Exception("Erro ao salvar dados da inscriчуo!\\n{$oDaoInscricao->erro_msg}");
    }
    
    
  }
  
  
  public function desistir($sMotivo='') {
    $this->cancelar($sMotivo,2);
  }
  
  public function cancelar($sMotivo='',$iTipoCancelamento=1,$lGeraInteresse=true) {
  	
    if (!db_utils::inTransaction()) {
      throw new Exception("Nenhuma transaчуo com o banco de dados aberta!\\n\\nOperaчуo Cancelada.");
    }
  	
    if ($this->getCodigo() == '') {
    	throw new Exception("Nenhuma inscriчуo informada!\\n\\nInclusуo cancelado.");
    }
    
  	$oDaoInscricao             = db_utils::getDao("habitinscricao");
  	$oDaoInscricaoCancelamento = db_utils::getDao("habitinscricaocancelamento");

  	
  	/**
  	 * Cancela o interesse no programa
  	 */
  	
	  $rsDadosCandidato = $oDaoInscricao->sql_record($oDaoInscricao->sql_query($this->getCodigo()));
 	  $oDadosCandidato  = db_utils::fieldsMemory($rsDadosCandidato,0);
  	  
 	  $oCandidato       = new CandidatoHabitacao($oDadosCandidato->ht10_numcgm);
 	  $oCandidato->cancelaInteressePrograma($oDadosCandidato->ht01_sequencial);

    /**
     * Caso seja necessсrio serс gerado interesse no grupo
     */ 	  
  	if ($lGeraInteresse) {
      $oCandidato->addInteresseGrupo($oDadosCandidato->ht01_habitgrupoprograma);  	  
  	}

  	/**
  	 * Gera registro de cancelamento
  	 */
  	$oDaoInscricaoCancelamento->ht22_habitinscricao = $this->getCodigo();
  	$oDaoInscricaoCancelamento->ht22_id_usuario     = db_getsession("DB_id_usuario");
  	$oDaoInscricaoCancelamento->ht22_data           = date("Y-m-d", db_getsession("DB_datausu"));
  	$oDaoInscricaoCancelamento->ht22_hora           = db_hora();
  	$oDaoInscricaoCancelamento->ht22_motivo         = $sMotivo;
  	$oDaoInscricaoCancelamento->ht22_tipo           = $iTipoCancelamento;
  	$oDaoInscricaoCancelamento->incluir(null);
  	
  	if ($oDaoInscricaoCancelamento->erro_status == 0) {
  		throw new Exception($oDaoInscricaoCancelamento->erro_msg);
  	}
    
  	
    return $this;
  }
}
?>
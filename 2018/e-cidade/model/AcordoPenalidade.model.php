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

class AcordoPenalidade  {
  
  /**
   * Código da Penalidade
   *
   * @var integer
   */
  private $iCodigo = null;
  
  /**
   * Descrição da Penalidade
   *
   * @var string
   */
  private $sDescricao = '';
  
  /**
   * Observação da Penalidade
   *
   * @var string
   */
  private $sObservacao = '';
  
  /**
   * Texto Padrão da Penalidade
   *
   * @var string
   */
  private $sTextoPadrao = '';
  
  /**
   * Data Limite da Penalidade
   *
   * @var string
   */
  private $dtDataLimite = '';
  
  private $aTiposContratos = array();
  /**
   * 
   * 
   * @param integer $iCodigo
   */
  function __construct($iCodigoPenalidade = null) {
  	
  	if (!empty($iCodigoPenalidade)) {
  		
	  	$oDaoAcordoPenalidade           = db_utils::getDao("acordopenalidade");
	  	$oDaoAcordoPenalidadeAcordoTipo = db_utils::getDao("acordopenalidadeacordotipo");
	  	
	  	$sCampos                   = "acordopenalidade.*";
	  	$sWhere                    = "ac13_sequencial = {$iCodigoPenalidade}";
	    $sSqlAcordoPenalidade      = $oDaoAcordoPenalidade->sql_query(null,$sCampos,null,$sWhere);
	    $rsSqlAcordoPenalidade     = $oDaoAcordoPenalidade->sql_record($sSqlAcordoPenalidade);
	    $iNumRowsAcordoPenalidade  = $oDaoAcordoPenalidade->numrows;

	    if ($iNumRowsAcordoPenalidade == 0) {
	      throw  new Exception("Nenhum Registro Encontrado para a Penalidade {$iCodigoPenalidade}!");
	    }
	    
      $oPenalidade = db_utils::fieldsMemory($rsSqlAcordoPenalidade, 0);
          
      $this->setCodigo($oPenalidade->ac13_sequencial);
      $this->setDescricao($oPenalidade->ac13_descricao);
      $this->setObservacao($oPenalidade->ac13_obs);
      $this->setTextoPadrao($oPenalidade->ac13_textopadrao);
      $this->setDataLimite($oPenalidade->ac13_validade);
      
      $sCampos                            = "acordopenalidadeacordotipo.*";
      $sWhere                             = "ac14_acordopenalidade = {$iCodigoPenalidade}";
      $sSqlAcordoPenalidadeAcordoTipo     = $oDaoAcordoPenalidadeAcordoTipo->sql_query(null,$sCampos,null,$sWhere);
      $rsSqlAcordoPenalidadeAcordoTipo    = $oDaoAcordoPenalidadeAcordoTipo->sql_record($sSqlAcordoPenalidadeAcordoTipo);
      $iNumRowsAcordoPenalidadeAcordoTipo = $oDaoAcordoPenalidadeAcordoTipo->numrows;
	    
  	  if ($iNumRowsAcordoPenalidadeAcordoTipo == 0) {
        throw  new Exception("Nenhum Registro Encontrado para a Penalidade {$iCodigoPenalidade}!");
      }
      
	    for ($iInd = 0; $iInd < $iNumRowsAcordoPenalidadeAcordoTipo; $iInd++) {

		    $oAcordoPenalidadeAcordoTipo = db_utils::fieldsMemory($rsSqlAcordoPenalidadeAcordoTipo, $iInd);
		    $this->addTipoContrato($oAcordoPenalidadeAcordoTipo->ac14_acordotipo);
	    }
  	}
  }
  
  /**
   * Retorna Código Penalidade
   * 
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }
  
  /**
   * Seta Código Penalidade
   * 
   * @param integer $iCodigo
   */
  private function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }
  
  /**
   * Retorna valor da Descrição da Penalidade
   * 
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }
  
  /**
   * Seta Descrição da Penalidade
   * 
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }
  
  /**
   * Retorna Obeservação da Penalidade
   * 
   * @return string
   */
  public function getObservacao() {
    return $this->sObservacao;
  }
  
  /**
   * Seta Observação da Penalidade
   * 
   * @param string $sObservacao
   */
  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
  }
  
  /**
   * Retorna Texto Padrão da Penalidade
   * 
   * @return string
   */
  public function getTextoPadrao() {
    return $this->sTextoPadrao;
  }
  
  /**
   * Seta Texto Padrão da Penalidade
   * 
   * @param string $sTextoPadrao
   */
  public function setTextoPadrao($sTextoPadrao) {
    $this->sTextoPadrao = $sTextoPadrao;
  }
  
  /**
   * Retorna Data Limite da Penalidade 
   * 
   * @return string
   */
  public function getDataLimite() {
    return $this->dtDataLimite;
  }
  
  /**
   * Seta Data Limite da Penalidade
   * 
   * @param string $dtDataLimite
   */
  public function setDataLimite($dtDataLimite) {
    $this->dtDataLimite = $dtDataLimite;
  }
  
  /**
   * 
   *
   */
  public function addTipoContrato($iCodigoTipo) {
  	
  	if (!in_array($iCodigoTipo, $this->aTiposContratos)) {
  		$this->aTiposContratos[] = $iCodigoTipo;
  	}
  }
  
/**
 * Remove um tipode contrato da penalidade
 *
 * @param integer $iCodigo codigo do tipo do contrato
 * @return AcordoPenalidade 
 */
  public function removeTipoContrato($iTiposContratos = null) {
  	
  	if (!empty($iTiposContratos)) {
  		
  		for ($i = 0; $i < count($this->aTiposContratos); $i++ ) {
  		  if ($this->aTiposContratos[$i] ==  $iTiposContratos) {
  		    unset($this->aTiposContratos[$i]);	
  		  }
  		}
  	} else {
  		$this->aTiposContratos = array();
  	}
  	return $this;
  }
  
  /**
   * Retorna os Tipos de Contratos
   *
   * @return array $this->aTiposContratos 
   */
  public function getTiposContratos() {
  	return $this->aTiposContratos;
  }
  
  /**
   * Exclui os dados das tabelas acordopenalidade e acordopenalidadeacordotipo
   */
  public function excluir() {
    
    $oDaoAcordoPenalidade           = db_utils::getDao("acordopenalidade");
    $oDaoAcordoPenalidadeAcordoTipo = db_utils::getDao("acordopenalidadeacordotipo");
    
    $sMsgErro = "Erro:\n\n Não foi possível excluir dados da penalidade.\n\n";
    
    $iGetCodigo = $this->getCodigo();
    if (empty($iGetCodigo)) {
       throw new Exception("Erro:\n\n Código da penalidade não informado. Exclusão cancelada!");
    }
    
    $oDaoAcordoPenalidadeAcordoTipo->excluir(null, "ac14_acordopenalidade = {$this->getCodigo()}");
    if ($oDaoAcordoPenalidadeAcordoTipo->erro_status == 0) {
      throw new Exception($sMsgErro.$oDaoAcordoPenalidadeAcordoTipo->erro_msg);
    }
    
    $oDaoAcordoPenalidade->excluir(null,"ac13_sequencial = {$this->getCodigo()}");
    if ($oDaoAcordoPenalidade->erro_status == 0) {
      throw new Exception($sMsgErro.$oDaoAcordoPenalidade->erro_msg);
    }
    
  }
  
  /**
   * Persiste os dados da penalidade da base de dados
   *
   * @return AcordoPenalidade
   */
  public function save() {
      	
  	$oDaoAcordoPenalidade           = db_utils::getDao("acordopenalidade");
  	$oDaoAcordoPenalidadeAcordoTipo = db_utils::getDao("acordopenalidadeacordotipo");
  	
  	$sMsgErro = "Erro:\n\n Não foi possível salvar dados da penalidade.\n\n";
  	
  	$oDaoAcordoPenalidade->ac13_descricao   = $this->getDescricao();
  	$oDaoAcordoPenalidade->ac13_obs         = pg_escape_string($this->getObservacao());
  	$oDaoAcordoPenalidade->ac13_textopadrao = pg_escape_string($this->getTextoPadrao());
  	$oDaoAcordoPenalidade->ac13_validade    = $this->getDataLimite();
  	
  	$iGetCodigo = $this->getCodigo();
  	if (empty($iGetCodigo)) {
  		
  		$oDaoAcordoPenalidade->incluir($this->getCodigo());
  		$this->setCodigo($oDaoAcordoPenalidade->ac13_sequencial);
  	} else {
  		
  		$oDaoAcordoPenalidade->ac13_sequencial = $this->getCodigo();
  		$oDaoAcordoPenalidade->alterar($this->getCodigo());
  	}
  	
    if ($oDaoAcordoPenalidade->erro_status == 0) {
      throw new Exception($sMsgErro.$oDaoAcordoPenalidade->erro_msg);
    }
    
  	$oDaoAcordoPenalidadeAcordoTipo->excluir(null, "ac14_acordopenalidade = {$this->getCodigo()}");
  	if ($oDaoAcordoPenalidadeAcordoTipo->erro_status == 0) {
  	  throw new Exception($sMsgErro.$oDaoAcordoPenalidadeAcordoTipo->erro_msg);
  	}
  	
  	$aGetTipoContratos = $this->getTiposContratos();
  	
  	foreach ($aGetTipoContratos as $iTipo) {
  		
  	  $oDaoAcordoPenalidadeAcordoTipo->ac14_acordotipo       = $iTipo;
  	  $oDaoAcordoPenalidadeAcordoTipo->ac14_acordopenalidade = $this->getCodigo();
  		$oDaoAcordoPenalidadeAcordoTipo->incluir(null);
  		
  	  if ($oDaoAcordoPenalidadeAcordoTipo->erro_status == 0) {
        throw new Exception($sMsgErro.$oDaoAcordoPenalidadeAcordoTipo->erro_msg);
      }
  	}
  	
  	return $this;
  }
}
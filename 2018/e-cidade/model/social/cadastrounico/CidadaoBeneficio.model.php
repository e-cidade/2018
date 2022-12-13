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
 * Classe para controle do Beneficio do Cidadao
 * @package social
 * @subpackage cadastrounico
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.3 $
 */
class CidadaoBeneficio {
	
	private $iCodigoBeneficio;	
	
	/**
	 * Cуdigo do programa social
	 * @var integer
	 */
	private $iProgramaSocial;	
	
	/**
	 * Mes de competencia
	 * @var integer
	 */
	private $iMesCompetencia;
	
	/**
	 * Ano de competencia
	 * @var integer
	 */
	private $iAnoCompetencia;
	
	/**
	 * Situacao do programa. Se esta ativo, cancelado ...
	 * @var string
	 */
	private $sSituacao;
	
	/**
	 * Numero de Identificacao Social
	 * @var string
	 */
	private $sNis;
	
	/**
	 * Tipo de Beneficio
	 * @var string
	 */
	private $sTipoBeneficio;
	
	/**
	 * Data da situacao dos beneficios
	 * @var DBDate
	 */
	private $oDataSituacao;
	
	/**
	 * Data que foi concedido o beneficio
	 * @var DBDate
	 */
	private $oDataConcessao;
	
	/**
	 * Motivo de liberaзгo / bloqueio do beneficio  
	 * @var string
	 */
	private $sMotivo;
	
	/**
	 * Justificativa de liberaзгo / bloqueio do beneficio   text
	 * @var string
	 */
	private $sJustificativa;
	
	
	public function __construct($iBeneficio = null) {
		
		if (!empty($iBeneficio)) {
			
			$oDaoBeneficio = db_utils::getDao('cidadaobeneficio');
			$sSqlBeneficio = $oDaoBeneficio->sql_query_file($iBeneficio);
			$rsBeneficio   = $oDaoBeneficio->sql_record($sSqlBeneficio);
			
			if ($oDaoBeneficio->numrows > 0) {
			
				$oBeneficio            = db_utils::fieldsMemory($rsBeneficio, 0);
				$this->iCodigoBeneficio  = $oBeneficio->as08_sequencial;
				$this->iProgramaSocial   = $oBeneficio->as08_programasocial;
				$this->iMesCompetencia   = $oBeneficio->as08_mes;
				$this->iAnoCompetencia   = $oBeneficio->as08_ano;
				$this->sSituacao         = $oBeneficio->as08_situacao;
				$this->sNis              = $oBeneficio->as08_nis;
				$this->sTipoBeneficio    = $oBeneficio->as08_tipobeneficio;
        $this->oDataSituacao = '';
			  $this->oDataConcessao = '';
				if (!empty($oBeneficio->as08_datasituacao)) {
				  $this->oDataSituacao = new DBDate($oBeneficio->as08_datasituacao);
				}
				if (!empty($oBeneficio->as08_dataconcessao)) {
				  $this->oDataConcessao = new DBDate($oBeneficio->as08_dataconcessao);
				}
				
				$this->sMotivo           = $oBeneficio->as08_motivo;
				$this->sJustificativa    = $oBeneficio->as08_justificativa;
								
				unset($oBeneficio);
			}
		}
	}
	
	/**
	 * Salvamos os dados do beneficio do cidadao
	 * @return boolean
	 * @throws BusinessException
	 */
	public function salvar() {
		
		$oDaoBeneficio                      = db_utils::getDao('cidadaobeneficio');
		$oDaoBeneficio->as08_sequencial     = null;
		$oDaoBeneficio->as08_programasocial = $this->iProgramaSocial; 
		$oDaoBeneficio->as08_mes            = $this->iMesCompetencia;
		$oDaoBeneficio->as08_ano            = $this->iAnoCompetencia;
		$oDaoBeneficio->as08_situacao       = $this->sSituacao;
		$oDaoBeneficio->as08_nis            = $this->sNis;
		$oDaoBeneficio->as08_tipobeneficio  = $this->sTipoBeneficio;
		if  ($this->oDataSituacao != "") {
		  $oDaoBeneficio->as08_datasituacao   = $this->oDataSituacao->getDate();
		}
		if ($this->oDataConcessao != "") {
		  $oDaoBeneficio->as08_dataconcessao  = $this->oDataConcessao->getDate();
		}
		$oDaoBeneficio->as08_motivo         = $this->sMotivo;
		$oDaoBeneficio->as08_justificativa  = $this->sJustificativa;
		
		if (!empty($this->iCodigoBeneficio)) {
			
			$oDaoBeneficio->as08_sequencial = $this->iCodigoBeneficio;     
			$oDaoBeneficio->alterar($this->iCodigoBeneficio);
		} else {
			$oDaoBeneficio->incluir(null);
		}
		
		if ($oDaoBeneficio->erro_status == 0) {
			
			$sMsgErro  = "Nгo foi possнvel salvar os dados.\n";
			$sMsgErro .= "{$oDaoBeneficio->erro_msg}";
			throw new BusinessException($sMsgErro);
		} 
		return true;
	}
	
	
	/**
	 * Retorna o sequencial da tabela de cidadaobeneficio
	 */
	public function getCodigoBeneficio () {
		
		return $this->iCodigoBeneficio;
	}
	
	/**
	 * atribui o cуdigo de um programa social
	 * @param integer $iProgramaSocial
	 */
	public function setProgramaSocial($iProgramaSocial) {
		
		$this->iProgramaSocial = $iProgramaSocial;
	}
	
	/**
	 * retorna o cуdigo de um programa social
	 * @return integer 
	 */
	public function getProgramaSocial() {
	
	  return $this->iProgramaSocial;
	}
	
	/**
	 * atribui o mes de competencia do beneficio
	 * @param integer $iMesCompetencia
	 */
	public function setMesCompetencia($iMesCompetencia) {
		
		$this->iMesCompetencia  = $iMesCompetencia;
	}
	
	/**
	 * retorna o mes de competencia do beneficio
	 * @return integer
	 */
	public function getMesCompetencia () {
		
		return $this->iMesCompetencia ;
	}
	
	/**
	 * atribui o ano de competencia do beneficio
	 * @param integer $iAnoCompetencia
	 */
	public function setAnoCompetencia ($iAnoCompetencia) {
		
		$this->iAnoCompetencia  = $iAnoCompetencia;
	}
	
	/**
	 * retorna o ano de competencia do beneficio
	 * @return interger
	 */
	public function getAnoCompetencia () {
	
		return $this->iAnoCompetencia;
	}
	
	/**
	 * Situacao do Beneficio
	 * @param string $sSituacao
	 */
	public function setSituacao ($sSituacao) {
		
		$this->sSituacao  = $sSituacao;
	}
	
	/**
	 * Situacao do Beneficio
	 * @return string
	 */
	public function getSituacao () {
	
		return $this->sSituacao;
	}
	
	/**
	 * atribui o Numero de Identificacao Social
	 * @param string sNis
	 */
	public function setNis ($sNis) {
		
		$this->sNis  = $sNis;
	}
	
	/**
	 * retorna o Numero de Identificacao Social
	 * @return string
	 */
	public function getNis () {
	
		return $this->sNis ;
	}
	
	/**
	 * atribui o Tipo de Beneficio
	 * @param string $sTipoBeneficio 
	 */
	public function setTipoBeneficio ($sTipoBeneficio) {
		
		$this->sTipoBeneficio = $sTipoBeneficio;
	}
	
	/**
	 * retorna o Tipo de Beneficio
	 * @return string 
	 */
	public function getTipoBeneficio () {
		
		return $this->sTipoBeneficio ;
	}
	
	/**
	 * atribui a Data da situacao dos beneficios
	 * @param DBDate oDataSituacao
	 */
	public function setDataSituacao (DBDate $oDataSituacao) {
		
		$this->oDataSituacao  = $oDataSituacao;
	}
	
	/**
	 * retorna a Data da situacao dos beneficios
	 * @return DBDate
	 */
	public function getDataSituacao () {
	
		return $this->oDataSituacao;
	}
	
	/**
	 * atribui a Data que foi concedido o beneficio
	 * @param DBDate $oDataConcessao
	 */
	public function setDataConcessao (DBDate $oDataConcessao) {
		
		$this->oDataConcessao  = $oDataConcessao;
	}
	
	/**
	 * return a Data que foi concedido o beneficio
	 * @return DBDate
	 */
	public function getDataConcessao () {
	
		return $this->oDataConcessao;
	}
	
	/**
	 * atribui o Motivo de liberaзгo / bloqueio do beneficio
	 * @param string $sMotivo
	 */
	public function setMotivo ($sMotivo) {
		
		$this->sMotivo  = $sMotivo;
	}
	
	/** 
	 * retorna o Motivo de liberaзгo / bloqueio do beneficio
	 * @return string
	 */
	public function getMotivo () {
	
		return $this->sMotivo ;
	}
	
	/**
	 * retorna a Justificativa de liberaзгo / bloqueio do beneficio
	 * @param string $sJustificativa
	 */
	public function setJustificativa ($sJustificativa) {
		$this->sJustificativa  = $sJustificativa;
	}
	
	/**
	 * retorna a Justificativa de liberaзгo / bloqueio do beneficio
	 * @return string
	 */
	public function getJustificativa () {
	
		return $this->sJustificativa ;
	}
}
?>
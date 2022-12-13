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

require_once(modification('model/cadastro/Imovel.model.php'));
require_once(modification('model/cadastro/CertidaoExistencia.model.php'));


/**
 * Classe para manipulação de construções
 *
 * @author   Rafael Serpa Nery  rafael.nery@dbseller.com.br
 * @author   Alberto Ferri Neto alberto@dbseller.com.br
 * @package  Cadastro
 * @revision $Author: dblucas.dumer $
 * @version  $Revision: 1.9 $
 */
class Construcao {

	/**
	 *
	 * Matricula da construção
	 * @var integer
	 */
	protected $iMatricula;

	/**
	 * Codigo da construção
	 * @var integer
	 */
	protected $iCodigoConstrucao;

	/**
	 * Ano da construção
	 * @var integer
	 */
	protected $iAnoConstrucao;

	/**
	 * Área da construção
	 * @var float
	 */
	protected $nArea;

	/**
	 * Área de construção para uso privado
	 * @var float
	 */
	protected $nAreaPrivada;

	/**
	 * Data de cadastro da construção
	 * @var date
	 */
	protected $dtDataCadastro;

	/**
	 * Código do endereço da construção
	 * @var integer
	 */
	protected $iCodigoRua;

	/**
	 * Número do endereço da construção
	 * @var integer
	 */
	protected $iNumeroEndereco;

	/**
	 * Complemento do endereço
	 * @var string
	 */
	protected $sComplementoEndereco;

	/**
	 * Data da demolição, caso exista
	 * @var date
	 */
	protected $dtDataDemolicao;

	/**
	 * Código de origem da construção
	 * @var integer
	 */
	protected $iCodigoOrigemConstrucao;

	/**
	 * Construção Principal
	 * @var boolean
	 */
	protected $lConstrucaoPrincipal;

	/**
	 * Data do habite-se
	 * @var date
	 */
	protected $dtDataHabite;

	/**
	 * Quantidade de pavimentos da construção
	 * @var integer
	 */
	protected $iQuantidadePavimentos;

	/**
	 * Código do processo externo de demolição
	 * @var integer
	 */
	protected $iCodigoProcessoDemolicao;

	/**
	 * Observações sobre a construção
	 * @var string
	 */
	protected $sObservacaoConstrucao;

	/**
	 * Construtor da Classe
	 * @param integer $iMatricula
	 * @param integer $iCodigoConstrucao
	 */
	public function __construct($iMatricula = null, $iCodigoConstrucao = null) {

		if (empty($iMatricula)) {
			throw new Exception('Matricula da construção não informada.');
		}

		if (!empty($iCodigoConstrucao)) {
				
			$oDaoIPTUConstr = db_utils::getDao('iptuconstr');

			$sSqlIptuConstr = $oDaoIPTUConstr->sql_query_file($iMatricula, $iCodigoConstrucao);

			$rsIptuConstr   = $oDaoIPTUConstr->sql_record($sSqlIptuConstr);

			if ($oDaoIPTUConstr->numrows == 0) {
				throw new Exception ('Nenhum registro encontrado');
			}

			$oConstrucao = db_utils::fieldsMemory($rsIptuConstr, 0);

			$this->iMatricula 							= $oConstrucao->j39_matric     ;
			$this->iCodigoConstrucao 				= $oConstrucao->j39_idcons     ;
			$this->iAnoConstrucao 					= $oConstrucao->j39_ano        ;
			$this->nArea 										= $oConstrucao->j39_area       ;
			$this->nAreaPrivada 						= $oConstrucao->j39_areap      ;
			$this->dtDataCadastro 			  	= $oConstrucao->j39_dtlan      ;
			$this->iCodigoRua 							= $oConstrucao->j39_codigo     ;
			$this->iNumeroEndereco 					= $oConstrucao->j39_numero     ;
			$this->sComplementoEndereco 		= $oConstrucao->j39_compl      ;
			$this->dtDataDemolicao 					= $oConstrucao->j39_dtdemo     ;
			$this->iCodigoOrigemConstrucao 	= $oConstrucao->j39_idaument   ;
			$this->lConstrucaoPrincipal 		= $oConstrucao->j39_idprinc    ;
			$this->dtDataHabite 						= $oConstrucao->j39_habite     ;
			$this->iQuantidadePavimentos    = $oConstrucao->j39_pavim      ;
			$this->iCodigoProcessoDemolicao = $oConstrucao->j39_codprotdemo;
			$this->sObservacaoConstrucao    = $oConstrucao->j39_obs        ;
				
		}

	}

	/**
	 * Retorna Matrícula do imóvel
	 * @return $iMatricula
	 */
	public function getMatricula() {
		return $this->iMatricula;
	}

	/**
	 * Define codigo da matricula do Imóvel
	 * @param integer $iMatricula
	 */
	public function setMatricula($iMatricula) {
		$this->iMatricula = $iMatricula;
	}

	/**
	 * Retorna Codigo da Construção
	 * @return $iCodigoConstrucao
	 */
	public function getCodigoConstrucao() {
		return $this->iCodigoConstrucao;
	}

	/**
	 * Define codigo da Construção
	 * @param integer $iCodigoConstrucao
	 */
	public function setCodigoConstrucao($iCodigoConstrucao) {
		$this->iCodigoConstrucao = $iCodigoConstrucao;
	}

	/**
	 * Retona ano da Construção
	 * @return $iAnoConstrucao
	 */
	public function getAnoConstrucao() {
		return $this->iAnoConstrucao;
	}

	/**
	 * Define ano da Construção
	 * @param integer $iAnoConstrucao
	 */
	public function setAnoConstrucao($iAnoConstrucao) {
		$this->iAnoConstrucao = $iAnoConstrucao;
	}

	/**
	 * Retorna área da Construção
	 * @return $nArea
	 */
	public function getArea() {
		return $this->nArea;
	}

	/**
	 * Define área da Construção
	 * @param numeric $nArea
	 */
	public function setArea($nArea) {
		$this->nArea = $nArea;
	}

	/**
	 * Retorna Área Privada da Construção
	 * @return numeric $nAreaPrivada
	 */
	public function getAreaPrivada() {
		return $this->nAreaPrivada;
	}
	/**
	 * Define área privada da construção
	 * @param numeric $nAreaPrivada
	 */
	public function setAreaPrivada($nAreaPrivada) {
		$this->nAreaPrivada = $nAreaPrivada;
	}

	/**
	 * Retorna data do cadastro
	 * @return date $dtDataCadastro
	 */
	public function getDataCadastro() {
		return $this->dDataCadastro;
	}

	/**
	 *
	 * Define a data de cadastro
	 * @param date $dtDataCadastro
	 */
	public function setDataCadastro($dtDataCadastro) {
		$this->dDataCadastro = $dtDataCadastro;
	}

	/**
	 * Retorna codigo da rua
	 * @return integer
	 */
	public function getCodigoRua() {
		return $this->iCodigoRua;
	}

	public function getNomeRua() {
	  
	  $oDaoRuas    = db_utils::getDao('ruas');
	  $sSql        = $oDaoRuas->sql_query_file( $this->getCodigoRua() );
	  $rsResultado = $oDaoRuas->sql_record($sSql);
	  
	  if ( !$rsResultado ) {
	    throw new BusinessException("Erro ao Buscar dados da Rua:" . $oDaoRuas->error_msg);
	  }
	  
	  return db_utils::fieldsMemory($rsResultado, 0)->j14_nome;
	} 
	
	/**
	 * Define código da rua
	 * @param integer $iCodigoRua
	 */
	public function setCodigoRua($iCodigoRua) {
		$this->iCodigoRua = $iCodigoRua;
	}

	/**
	 * Retorna número do endereço
	 * @return integer
	 */
	public function getNumeroEndereco() {
		return $this->iNumeroEndereco;
	}

	/**
	 * Define número do endereço
	 * @param integer $iNumeroEndereco
	 */
	public function setNumeroEndereco($iNumeroEndereco) {
		$this->iNumeroEndereco = $iNumeroEndereco;
	}

	/**
	 * Retorna complemento do endereço
	 * @return string sComplementoEndereco
	 */
	public function getComplementoEndereco() {
		return $this->sComplementoEndereco;
	}

	/**
	 * Define complemento do endereço
	 * @param string $sComplementoEndereco
	 */
	public function setComplementoEndereco($sComplementoEndereco) {
		$this->sComplementoEndereco = $sComplementoEndereco;
	}

	/**
	 * Retorna data de demolição
	 * @return date dtDataDemolicao
	 */
	public function getDataDemolicao() {
		return $this->dtDataDemolicao;
	}

	/**
	 * Define data de demolição
	 * @param date $dtDataDemolicao
	 */
	public function setDataDemolicao($dtDataDemolicao) {
		$this->dtDataDemolicao = $dtDataDemolicao;
	}

	/**
	 * Retorna codigo da origem da constução
	 * @return integer iCodigoOrigemConstrucao
	 */
	public function getCodigoOrigemConstrucao() {
		return $this->iCodigoOrigemConstrucao;
	}

	/**
	 * Define codigo da origem da construcao
	 * @param integer $iCodigoOrigemConstrucao
	 */
	public function setCodigoOrigemConstrucao($iCodigoOrigemConstrucao) {
		$this->iCodigoOrigemConstrucao = $iCodigoOrigemConstrucao;
	}

	/**
	 * Retorna true caso for a construção principal
	 * @return boolean $lConstrucaoPrincipal
	 */
	public function isConstrucaoPrincipal() {
		return $this->lConstrucaoPrincipal;
	}

	/**
	 * Define construção principal
	 * @param boolean $lConstrucaoPrincipal
	 */
	public function setConstrucaoPrincipal($lConstrucaoPrincipal) {
		$this->lConstrucaoPrincipal = $lConstrucaoPrincipal;
	}

	/**
	 * Retorna data do habite-se
	 * @return integer iCodigoOrigemConstrucao
	 */
	public function getDataHabite() {
		return $this->dtDataHabite;
	}

	/**
	 * Define data do habite-se
	 * @param date $dtDataHabite
	 */
	public function setDataHabite($dtDataHabite) {
		$this->dtDataHabite = $dtDataHabite;
	}

	/**
	 * Retorna quantidade de pavimentos
	 * @return integer $iQuantidadePavimentos
	 */
	public function getQuantidadePavimentos() {
		return $this->iQuantidadePavimentos;
	}

	/**
	 * Define quantidade de pavimentos
	 * @param integer $iQuantidadePavimentos
	 */
	public function setQuantidadePavimentos($iQuantidadePavimentos) {
		$this->iQuantidadePavimentos = $iQuantidadePavimentos;
	}

	/**
	 * Retorna codigo processo de demolição
	 * @return integer $iCodigoProcessoDemolicao
	 */
	public function getCodigoProcessoDemolicao() {
		return $this->iCodigoProcessoDemolicao;
	}

	/**
	 * Define código processo de demolição
	 * @param integer $iCodigoProcessoDemolicao
	 */
	public function setCodigoProcessoDemolicao($iCodigoProcessoDemolicao) {
		$this->iCodigoProcessoDemolicao = $iCodigoProcessoDemolicao;
	}

	/**
	 * Retorna observação da construção
	 * @return string $sObservacaoConstrucao
	 */
	public function getObservacaoConstrucao() {
		return $this->sObservacaoConstrucao;
	}

	/**
	 * Define observação da construção
	 * @param string $sObservacaoConstrucao
	 */
	public function setObservacaoConstrucao($sObservacaoConstrucao) {
		$this->sObservacaoConstrucao = $sObservacaoConstrucao;
	}

	/**
	 * Retorna instanica da classe imóvel
	 * @return Imovel
	 */
	public function getInstanceOfImovel() {
		return new Imovel($this->iMatricula);
	}

	/**
	 * Salva construção
	 */
	public function salvar() {

	}

	/**
	 * Emite certidão de existência
	 */
	public function emiteCertidaoExistencia() {
    
		$oCertidaoExistencia = new CertidaoExistencia();
		$oCertidaoExistencia->setMatricula       ($this->getMatricula()       );
		$oCertidaoExistencia->setCodigoConstrucao($this->getCodigoConstrucao());
		return $oCertidaoExistencia;
	}
	
	public function getCalculoIptu($iAnoCalculo) {
	  
	  db_app::import('cadastro.CalculoIptu');
	  
	  $oCalculo = new CalculoIPTU($this->getMatricula(), $iAnoCalculo);
	  
	  return $oCalculo->getCalculoConstrucao($this->getCodigoConstrucao());
	  
	}
	
	public function getCaracteristicasConstrucao() {
	  
	  	if(empty($this->iMatricula)) {
	    	throw new Exception('Matrícula não informada.');
	  	}
	  
	  	if(empty($this->iCodigoConstrucao)) {
	    	throw new Exception('Código da construção não informado.');
	  	}
	  
	  	$oDaoCarconstr = db_utils::getDao('carconstr');
	  
	  	$sSqlCarconstr = $oDaoCarconstr->sql_queryCaracteristicas($this->getMatricula(), $this->getCodigoConstrucao());
	  
	  	$rsCarconstr = $oDaoCarconstr->sql_record($sSqlCarconstr);
	  
	  	$aCarconstr = db_utils::getCollectionByRecord($rsCarconstr);
	  
	  	$aCaracteristicas = array();
	  
	  	foreach ($aCarconstr as $oCarconstr) {
	    
	    	$oCaracteristica = new stdClass();
	    
	    	$oCaracteristica->iCodigoCaracteristica = $oCarconstr->j31_codigo;
	    	$oCaracteristica->sCaracteristica       = $oCarconstr->j31_descr ;
	    	$oCaracteristica->iNumeroPontos         = $oCarconstr->j31_pontos;
	    	$oCaracteristica->iCodigoGrupo          = $oCarconstr->j32_grupo ;
	    	$oCaracteristica->sDescricaoGrupo       = $oCarconstr->j32_descr ;
	    
	    	$aCaracteristicas[] = $oCaracteristica;
	    
	  	}
	  
	  	return $aCaracteristicas; 
	}

	public function getHabite()
	{  
	  	if(empty($this->iMatricula)) {
    		throw new Exception('Matrícula não informada.');
	  	}
	  
	  	if(empty($this->iCodigoConstrucao)) {
			throw new Exception('Código da construção não informado.');
	  	}
	  
	  	$oDaoIptuConstrHabite = db_utils::getDao('iptuconstrhabite');
	  
	  	$sSqlIptuConstrHabite = $oDaoIptuConstrHabite->sql_query_dados(null, "*", null, "j131_matric = ".$this->getMatricula()." and j131_idcons = ".$this->getCodigoConstrucao());
	  
	  	$rsIptuConstrHabite = $oDaoIptuConstrHabite->sql_record($sSqlIptuConstrHabite);
	  
	  	return db_utils::fieldsMemory($rsIptuConstrHabite, 0);
	}
}
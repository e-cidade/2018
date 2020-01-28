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

require_once('model/cadastro/imovel.model.php');
require_once('model/cadastro/certidaoExistencia.model.php');


/**
 * Classe para manipula��o de constru��es
 *
 * @author   Rafael Serpa Nery  rafael.nery@dbseller.com.br
 * @author   Alberto Ferri Neto alberto@dbseller.com.br
 * @package  Cadastro
 * @revision $Author: dbrafael.nery $
 * @version  $Revision: 1.1 $
 */
class Construcao {

	/**
	 *
	 * Matricula da constru��o
	 * @var integer
	 */
	protected $iMatricula;

	/**
	 * Codigo da constru��o
	 * @var integer
	 */
	protected $iCodigoConstrucao;

	/**
	 * Ano da constru��o
	 * @var integer
	 */
	protected $iAnoConstrucao;

	/**
	 * �rea da constru��o
	 * @var float
	 */
	protected $nArea;

	/**
	 * �rea de constru��o para uso privado
	 * @var float
	 */
	protected $nAreaPrivada;

	/**
	 * Data de cadastro da constru��o
	 * @var date
	 */
	protected $dtDataCadastro;

	/**
	 * C�digo do endere�o da constru��o
	 * @var integer
	 */
	protected $iCodigoRua;

	/**
	 * N�mero do endere�o da constru��o
	 * @var integer
	 */
	protected $iNumeroEndereco;

	/**
	 * Complemento do endere�o
	 * @var string
	 */
	protected $sComplementoEndereco;

	/**
	 * Data da demoli��o, caso exista
	 * @var date
	 */
	protected $dtDataDemolicao;

	/**
	 * C�digo de origem da constru��o
	 * @var integer
	 */
	protected $iCodigoOrigemConstrucao;

	/**
	 * Constru��o Principal
	 * @var boolean
	 */
	protected $lConstrucaoPrincipal;

	/**
	 * Data do habite-se
	 * @var date
	 */
	protected $dtDataHabite;

	/**
	 * Quantidade de pavimentos da constru��o
	 * @var integer
	 */
	protected $iQuantidadePavimentos;

	/**
	 * C�digo do processo externo de demoli��o
	 * @var integer
	 */
	protected $iCodigoProcessoDemolicao;

	/**
	 * Observa��es sobre a constru��o
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
			throw new Exception('Matricula da constru��o n�o informada.');
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
	 * Retorna Matr�cula do im�vel
	 * @return $iMatricula
	 */
	public function getMatricula() {
		return $this->iMatricula;
	}

	/**
	 * Define codigo da matricula do Im�vel
	 * @param integer $iMatricula
	 */
	public function setMatricula($iMatricula) {
		$this->iMatricula = $iMatricula;
	}

	/**
	 * Retorna Codigo da Constru��o
	 * @return $iCodigoConstrucao
	 */
	public function getCodigoConstrucao() {
		return $this->iCodigoConstrucao;
	}

	/**
	 * Define codigo da Constru��o
	 * @param integer $iCodigoConstrucao
	 */
	public function setCodigoConstrucao($iCodigoConstrucao) {
		$this->iCodigoConstrucao = $iCodigoConstrucao;
	}

	/**
	 * Retona ano da Constru��o
	 * @return $iAnoConstrucao
	 */
	public function getAnoConstrucao() {
		return $this->iAnoConstrucao;
	}

	/**
	 * Define ano da Constru��o
	 * @param integer $iAnoConstrucao
	 */
	public function setAnoConstrucao($iAnoConstrucao) {
		$this->iAnoConstrucao = $iAnoConstrucao;
	}

	/**
	 * Retorna �rea da Constru��o
	 * @return $nArea
	 */
	public function getArea() {
		return $this->nArea;
	}

	/**
	 * Define �rea da Constru��o
	 * @param numeric $nArea
	 */
	public function setArea($nArea) {
		$this->nArea = $nArea;
	}

	/**
	 * Retorna �rea Privada da Constru��o
	 * @return numeric $nAreaPrivada
	 */
	public function getAreaPrivada() {
		return $this->nAreaPrivada;
	}
	/**
	 * Define �rea privada da constru��o
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

	/**
	 * Define c�digo da rua
	 * @param integer $iCodigoRua
	 */
	public function setCodigoRua($iCodigoRua) {
		$this->iCodigoRua = $iCodigoRua;
	}

	/**
	 * Retorna n�mero do endere�o
	 * @return integer
	 */
	public function getNumeroEndereco() {
		return $this->iNumeroEndereco;
	}

	/**
	 * Define n�mero do endere�o
	 * @param integer $iNumeroEndereco
	 */
	public function setNumeroEndereco($iNumeroEndereco) {
		$this->iNumeroEndereco = $iNumeroEndereco;
	}

	/**
	 * Retorna complemento do endere�o
	 * @return string sComplementoEndereco
	 */
	public function getComplementoEndereco() {
		return $this->sComplementoEndereco;
	}

	/**
	 * Define complemento do endere�o
	 * @param string $sComplementoEndereco
	 */
	public function setComplementoEndereco($sComplementoEndereco) {
		$this->sComplementoEndereco = $sComplementoEndereco;
	}

	/**
	 * Retorna data de demoli��o
	 * @return date dtDataDemolicao
	 */
	public function getDataDemolicao() {
		return $this->dDataDemolicao;
	}

	/**
	 * Define data de demoli��o
	 * @param date $dtDataDemolicao
	 */
	public function setDataDemolicao($dtDataDemolicao) {
		$this->dDataDemolicao = $dtDataDemolicao;
	}

	/**
	 * Retorna codigo da origem da constu��o
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
	 * Retorna true caso for a constru��o principal
	 * @return boolean $lConstrucaoPrincipal
	 */
	public function isConstrucaoPrincipal() {
		return $this->lConstrucaoPrincipal;
	}

	/**
	 * Define constru��o principal
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
		return $this->dDataHabite;
	}

	/**
	 * Define data do habite-se
	 * @param date $dtDataHabite
	 */
	public function setDataHabite($dtDataHabite) {
		$this->dDataHabite = $dtDataHabite;
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
	 * Retorna codigo processo de demoli��o
	 * @return integer $iCodigoProcessoDemolicao
	 */
	public function getCodigoProcessoDemolicao() {
		return $this->iCodigoProcessoDemolicao;
	}

	/**
	 * Define c�digo processo de demoli��o
	 * @param integer $iCodigoProcessoDemolicao
	 */
	public function setCodigoProcessoDemolicao($iCodigoProcessoDemolicao) {
		$this->iCodigoProcessoDemolicao = $iCodigoProcessoDemolicao;
	}

	/**
	 * Retorna observa��o da constru��o
	 * @return string $sObservacaoConstrucao
	 */
	public function getObservacaoConstrucao() {
		return $this->sObservacaoConstrucao;
	}

	/**
	 * Define observa��o da constru��o
	 * @param string $sObservacaoConstrucao
	 */
	public function setObservacaoConstrucao($sObservacaoConstrucao) {
		$this->sObservacaoConstrucao = $sObservacaoConstrucao;
	}

	/**
	 * Retorna instanica da classe im�vel
	 * @return Imovel
	 */
	public function getInstanceOfImovel() {
		return new Imovel($this->iMatricula);
	}

	/**
	 * Salva constru��o
	 */
	public function salvar() {

	}

	/**
	 * Emite certid�o de exist�ncia
	 */
	public function emiteCertidaoExistencia() {
    
		$oCertidaoExistencia = new CertidaoExistencia();
		$oCertidaoExistencia->setMatricula       ($this->getMatricula()       );
		$oCertidaoExistencia->setCodigoConstrucao($this->getCodigoConstrucao());
		return $oCertidaoExistencia;
	}
}
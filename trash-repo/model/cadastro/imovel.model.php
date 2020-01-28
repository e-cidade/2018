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


require_once('model/CgmFactory.model.php');
require_once('model/cadastro/construcao.model.php');

/**
 * Classe para manipua��o de im�veis
 *
 * @author   Rafael Serpa Nery  rafael.nery@dbseller.com.br
 * @author   Alberto Ferri Neto alberto@dbseller.com.br
 * @package  Cadastro
 * @revision $Author: dbrafael.nery $
 * @version  $Revision: 1.1 $
 */
class Imovel {
	
	/**
	 * Matricula de registro do im�vel
	 * @var integer
	 */
	private $iMatricula;
	
	/**
	 * C�digo do lote do im�vel 
	 * @var integer
	 */
	private $iCodigoLote;
	
	/**
	 * Data da baixa do im�vel
	 * @var date
	 */
	private $dtDataBaixa;
	
	/**
	 * C�digo de averba��o do im�vel
	 * @var integer
	 */
	private $iCodigoAverbacao;
	
	/**
	 * N�mero da fra��o do im�vel 
	 * @var float
	 */
	private $nFracao;
	
	/**
	 * 
	 * Cole��o com todos os proprietarios do im�vel
	 * @var array
	 */
	private $aProprietarios = array();
	
	/**
 	 *
	 * Cole��o com todos os promitentes do im�vel
	 * @var array
	 */
	private $aPromitentes = array();
	
	/**
	 * Construtor da Classe
	 * @param integer $iMatricula - Matricula de registro do im�vel
	 */
	public function __construct( $iMatricula = null ) {
		
		/**
		 * Valida se a matricula informada � valida e define os atributos
		 */
		if ( !empty($iMatricula) ) {
		  
		  $oDaoIPTUBase = db_utils::getDao('iptubase');
		  
		  $sSqlImovel   = $oDaoIPTUBase->sql_query_file($iMatricula);
		  $rsImovel     = $oDaoIPTUBase->sql_record($sSqlImovel);
		  
		  if ( $oDaoIPTUBase->erro_status == "0" ) {
        throw new Exception("Erro ao selecionar dados da tabela issbase: \n".$oDaoIPTUBase->erro_msg);		  	
		  }
		  
		  $oImovel                = db_utils::fieldsMemory($rsImovel, 0);
		  $this->iMatricula       = $iMatricula;
		  $this->iCodigoLote      = $oImovel->j01_idbql;
		  $this->iCodigoLote      = $oImovel->j01_idbql;
		  $this->dtDataBaixa          = $oImovel->j01_baixa;
		  $this->iCodigoAverbacao = $oImovel->j01_codave;
		  $this->nFracao          = $oImovel->j01_fracao;
		  
		  /**
		   * Busca Propriet�rios do Imovel
		   */
		  $sSqlProprietarios      = $oDaoIPTUBase->sql_query_proprietarios($iMatricula);
		  $rsProprietarios        = $oDaoIPTUBase->sql_record($sSqlProprietarios);
		  
			if ( (int)$oDaoIPTUBase->erro_status == 0 && !empty($oDaoIPTUBase->erro_banco) ) {
        throw new Exception("Erro Construtor da Classe: \n".$oDaoIPTUBase->erro_msg);		  	
		  }
		  
		  $aProprietarios         = db_utils::getCollectionByRecord($rsProprietarios);
		  
		  foreach ($aProprietarios as $oProprietario) {
				$this->aProprietarios[$oProprietario->j01_numcgm] = CgmFactory::getInstanceByCgm($oProprietario->j01_numcgm);
		  }
		  /**
		   * Busca promitente do Imovel
		   */
		  $sSqlPromitentes       = $oDaoIPTUBase->sql_query_promitentes($iMatricula);
		  $rsPromitentes         = $oDaoIPTUBase->sql_record($sSqlPromitentes);
		  
			if ( (int)$oDaoIPTUBase->erro_status == 0 && !empty($oDaoIPTUBase->erro_banco) ) {
		    throw new Exception("Erro Construtor da Classe: \n".$oDaoIPTUBase->erro_msg);
		  }
		  
		  $aPromitentes          = db_utils::getCollectionByRecord($rsPromitentes);
		  
		  foreach ($aPromitentes as $oPromitente) {
		  	$this->aPromitentes[$oPromitente->j41_numcgm] = CgmFactory::getInstanceByCgm($oPromitente->j41_numcgm);
		  }
		}
	}

	/**
	 * Retorna Matricula do im�vel
	 * @return integer
	 */
	public function getMatricula()
	{
	    return $this->iMatricula;
	}

	/**
	 * Define matr�cula do im�vel
	 * @param $iMatricula
	 */
	public function setMatricula($iMatricula) {
		$this->iMatricula = $iMatricula;
	}

	/**
	 * Retorna c�digo do lote do im�vel
	 * @return integer
	 */
	public function getCodigoLote() {
		return $this->iCodigoLote;
	}

	/**
	 * Define c�digo do lote do im�vel
	 * @param $iCodigoLote
	 */
	public function setCodigoLote($iCodigoLote)	{
		$this->iCodigoLote = $iCodigoLote;
	}

	/**
	 * Retorna data da baixa do im�vel
	 * @return date
	 */
	public function getDataBaixa()
	{
		return $this->dtDataBaixa;
	}

	/**
	 * Define data da baixa do im�vel
	 * @param $dtDataBaixa
	 */
	public function setDataBaixa($dtDataBaixa) {
		$this->dtDataBaixa = $dtDataBaixa;
	}

	/**
	 * Retorna c�digo da averba��o
	 * @return integer
	 */
	public function getCodigoAverbacao() {
		return $this->iCodigoAverbacao;
	}

	/**
	 * Define c�digo da averba��o
	 * @param $iCodigoAverbacao
	 */
	public function setCodigoAverbacao($iCodigoAverbacao) {
		$this->iCodigoAverbacao = $iCodigoAverbacao;
	}

	/**
	 * Retorna fra��o do im�vel
	 * @return float
	 */
	public function getFracao() {
		return $this->nFracao;
	}

	/**
	 * Define fra��o do im�vel
	 * @param $nFracao
	 */
	public function setFracao($nFracao) {
		$this->nFracao = $nFracao;
	}

	/**
	 * Salva registros na base de dados
	 */
	public function salvar() {

	}

	/**
	 * Retorna os propriet�rios do im�vel
	 * @return array
	 */
	public function getProprietarios() {
		return $this->aProprietarios;
	}

	/**
	 * Retorna os promitentes do im�vel
	 * @return array
	 */
	public function getPromitentes() {
		return $this->aPromitentes;
	}

	/**
	 * Retorna o propriet�rio principal do im�vel
	 * @return object
	 */
	public function getProprietarioPrincipal() {

		$oDaoIPTUBase          = db_utils::getDao('iptubase');
		$sSqlProprietarios     = $oDaoIPTUBase->sql_query_proprietarios($this->iMatricula, true);
		$rsProprietarios       = $oDaoIPTUBase->sql_record($sSqlProprietarios);

		if ( (int)$oDaoIPTUBase->erro_status == 0 && !empty($oDaoIPTUBase->erro_banco) ) {
			throw new Exception("Erro Construtor da Classe: \n".$oDaoIPTUBase->erro_msg);
		}

		$oProprietario = db_utils::fieldsMemory($rsProprietarios, 0);

		return CgmFactory::getInstanceByCgm($oProprietario->j01_numcgm);

	}

	/**
	 * Retorna uma inst�ncia do cgm do promitente principal do im�vel
	 * @return object
	 */
	public function getPromitentePrincipal() {

		$oDaoIPTUBase          = db_utils::getDao('iptubase');
		$sSqlPromitentes       = $oDaoIPTUBase->sql_query_promitentes($this->iMatricula, true);
		$rsPromitentes         = $oDaoIPTUBase->sql_record($sSqlPromitentes);

		if ( (int)$oDaoIPTUBase->erro_status == 0 && !empty($oDaoIPTUBase->erro_banco) ) {
			throw new Exception("Erro Construtor da Classe: \n".$oDaoIPTUBase->erro_msg);
		}

		$oPromitente = db_utils::fieldsMemory($rsPromitentes, 0);

		return CgmFactory::getInstanceByCgm($oPromitente->j41_numcgm);

	}

	/**
	 * Retorna respons�vel legal do imovel 
	 */
	public function getResponsavelLegal() {
			

	}

	/**
	 *
	 * Retorna todas as constru��es vinculadas a obra
	 * @return array com instancia da classe Construcao
	 */
	public function getConstrucoes() {

		$oDaoIPTUConstr  = db_utils::getDao('iptuconstr');

		$sSqlConstrucoes = $oDaoIPTUConstr->sql_query_file($this->iMatricula, null, 'j39_idcons');

		$rsConstrucoes   = $oDaoIPTUConstr->sql_record($sSqlConstrucoes);

		if (!$rsConstrucoes) {
			throw new Exception('Erro ao selecionar dados da tabela iptuconstr.');
		}

		$aIdConstrucoes = db_utils::getCollectionByRecord($rsConstrucoes);

		$aConstrucoes = array();

		/**
		 * Constroi um array com a instancia do objeto constru��es
		 */
		foreach ($aIdConstrucoes as $oConstrucao) {
				
			$aConstrucoes[$oConstrucao->j39_idcons] = new Construcao($this->iMatricula, $oConstrucao->j39_idcons);
		}

		return $aConstrucoes;


	}
}
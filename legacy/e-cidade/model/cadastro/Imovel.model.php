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
require_once('model/cadastro/Construcao.model.php');

/**
 * Classe para manipuação de imóveis
 *
 * @author   Rafael Serpa Nery  rafael.nery@dbseller.com.br
 * @author   Alberto Ferri Neto alberto@dbseller.com.br
 * @package  Cadastro
 * @revision $Author: dbanderson $
 * @version  $Revision: 1.8 $
 */
class Imovel {
	
	/**
	 * Matricula de registro do imóvel
	 * @var integer
	 */
	private $iMatricula;
	
	/**
	 * Código do lote do imóvel 
	 * @var integer
	 */
	private $iCodigoLote;
	
	/**
	 * Data da baixa do imóvel
	 * @var date
	 */
	private $dtDataBaixa;
	
	/**
	 * Código de averbação do imóvel
	 * @var integer
	 */
	private $iCodigoAverbacao;
	
	/**
	 * Número da fração do imóvel 
	 * @var float
	 */
	private $nFracao;
	
	/**
	 * 
	 * Coleção com todos os proprietarios do imóvel
	 * @var array
	 */
	private $aProprietarios = array();
	
	/**
 	 *
	 * Coleção com todos os promitentes do imóvel
	 * @var array
	 */
	private $aPromitentes = array();
	
	/**
	 * Construtor da Classe
	 * @param integer $iMatricula - Matricula de registro do imóvel
	 */
	public function __construct( $iMatricula = null ) {
		
		/**
		 * Valida se a matricula informada é valida e define os atributos
		 */
		if ( !empty($iMatricula) ) {
		  
		  $oDaoIPTUBase = db_utils::getDao('iptubase');
		  
		  $sSqlImovel   = $oDaoIPTUBase->sql_query_file($iMatricula);
		  $rsImovel     = $oDaoIPTUBase->sql_record($sSqlImovel);
		  
		  if ( $oDaoIPTUBase->erro_status == "0" ) {
        throw new Exception("Erro ao selecionar dados da tabela iptubase: \n".$oDaoIPTUBase->erro_msg);		  	
		  }
		  
		  $oImovel                = db_utils::fieldsMemory($rsImovel, 0);
		  $this->iMatricula       = $iMatricula;
		  $this->iCodigoLote      = $oImovel->j01_idbql;
		  $this->dtDataBaixa      = $oImovel->j01_baixa;
		  $this->iCodigoAverbacao = $oImovel->j01_codave;
		  $this->nFracao          = $oImovel->j01_fracao;
		  
		}
		
	}

	/**
	 * Retorna Matricula do imóvel
	 * @return integer
	 */
	public function getMatricula()
	{
	    return $this->iMatricula;
	}

	/**
	 * Define matrícula do imóvel
	 * @param $iMatricula
	 */
	public function setMatricula($iMatricula) {
		$this->iMatricula = $iMatricula;
	}

	/**
	 * Retorna código do lote do imóvel
	 * @return integer
	 */
	public function getCodigoLote() {
		return $this->iCodigoLote;
	}

	/**
	 * Define código do lote do imóvel
	 * @param $iCodigoLote
	 */
	public function setCodigoLote($iCodigoLote)	{
		$this->iCodigoLote = $iCodigoLote;
	}

	/**
	 * Retorna data da baixa do imóvel
	 * @return date
	 */
	public function getDataBaixa()
	{
		return $this->dtDataBaixa;
	}

	/**
	 * Define data da baixa do imóvel
	 * @param $dtDataBaixa
	 */
	public function setDataBaixa($dtDataBaixa) {
		$this->dtDataBaixa = $dtDataBaixa;
	}

	/**
	 * Retorna código da averbação
	 * @return integer
	 */
	public function getCodigoAverbacao() {
		return $this->iCodigoAverbacao;
	}

	/**
	 * Define código da averbação
	 * @param $iCodigoAverbacao
	 */
	public function setCodigoAverbacao($iCodigoAverbacao) {
		$this->iCodigoAverbacao = $iCodigoAverbacao;
	}

	/**
	 * Retorna fração do imóvel
	 * @return float
	 */
	public function getFracao() {
		return $this->nFracao;
	}

	/**
	 * Define fração do imóvel
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
	 * Retorna os proprietários do imóvel
	 * @return array
	 */
	public function getProprietarios() {
	  
	  /**
	  * Busca Proprietários do Imovel
	  */
	  
	  if(empty($this->iMatricula)) {
	    throw new Exception('Matrícula não informada para busca dos proprietários');
	  }
	  
	  $oDaoIPTUBase           = db_utils::getDao('iptubase');
	  
	  $sSqlProprietarios      = $oDaoIPTUBase->sql_query_proprietarios($this->iMatricula);
	  $rsProprietarios        = $oDaoIPTUBase->sql_record($sSqlProprietarios);
	  
	  if ( (int)$oDaoIPTUBase->erro_status == 0 && !empty($oDaoIPTUBase->erro_banco) ) {
	    throw new Exception("Erro ao constultar proprietários da matrícula {$this->iMatricula}: \n".$oDaoIPTUBase->erro_msg);
	  }
	  
	  $aProprietarios         = db_utils::getCollectionByRecord($rsProprietarios);
	  
	  foreach ($aProprietarios as $oProprietario) {
	    $this->aProprietarios[$oProprietario->j01_numcgm] = CgmFactory::getInstanceByCgm($oProprietario->j01_numcgm);
	  }
	  
		return $this->aProprietarios;
	}

	/**
	 * Retorna os promitentes do imóvel
	 * @return array
	 */
	public function getPromitentes() {
	  
	  /**
	  * Busca promitente do Imovel
	  */
	  if(empty($this->iMatricula)) {
	    throw new Exception('Matrícula não informada para busca dos proprietários');
	  }
	   
	  $oDaoIPTUBase           = db_utils::getDao('iptubase');
	  
	  $sSqlPromitentes       = $oDaoIPTUBase->sql_query_promitentes($this->iMatricula);
	  $rsPromitentes         = $oDaoIPTUBase->sql_record($sSqlPromitentes);
	  
	  if ( (int)$oDaoIPTUBase->erro_status == 0 && !empty($oDaoIPTUBase->erro_banco) ) {
	    throw new Exception("Erro ao consultar promitentes da matrícula {$this->iMatricula}.: \n".$oDaoIPTUBase->erro_msg);
	  }
	  
	  $aPromitentes          = db_utils::getCollectionByRecord($rsPromitentes);
	  
	  foreach ($aPromitentes as $oPromitente) {
	    $this->aPromitentes[$oPromitente->j41_numcgm] = CgmFactory::getInstanceByCgm($oPromitente->j41_numcgm);
	  }
	  
		return $this->aPromitentes;
		
	}

	/**
	 * Retorna o proprietário principal do imóvel
	 * @return object
	 */
	public function getProprietarioPrincipal() {

		$oDaoIPTUBase          = db_utils::getDao('iptubase');
		$sSqlProprietarios     = $oDaoIPTUBase->sql_query_proprietarios($this->iMatricula, true);
		$rsProprietarios       = db_query($sSqlProprietarios);

		if ( !$rsProprietarios ) {
			throw new Exception("Erro ao consultar proprietario principal da matricula {$this->iMatricula}: \n".pg_last_error());
		}

		if (pg_num_rows($rsProprietarios) > 0) {
  		$oProprietario = db_utils::fieldsMemory($rsProprietarios, 0);
  
  		return CgmFactory::getInstanceByCgm($oProprietario->j01_numcgm);
		}
		return false;

	}

	/**
	 * Retorna uma instância do cgm do promitente principal do imóvel
	 * @return object
	 */
	public function getPromitentePrincipal() {

		$oDaoIPTUBase          = db_utils::getDao('iptubase');
		$sSqlPromitentes       = $oDaoIPTUBase->sql_query_promitentes($this->iMatricula, true);
		$rsPromitentes         = db_query($sSqlPromitentes);

		if (!$rsPromitentes) {
			throw new Exception("Erro ao consultar promitente principal da matrícula {$this->iMatricula}: \n".pg_last_error());
		}
		
		if (pg_num_rows($rsPromitentes) > 0) {

  		$oPromitente = db_utils::fieldsMemory($rsPromitentes, 0);
  
  		return CgmFactory::getInstanceByCgm($oPromitente->j41_numcgm);
		}

		return false;
	}

	/**
	 * Retorna responsável legal do imovel 
	 */
	public function getResponsavelLegal() {
			

	}

	/**
	 *
	 * Retorna todas as construções vinculadas a obra
	 * @return array com instancia da classe Construcao
	 */
	public function getConstrucoes($lSomenteAtivas = false) {

		$oDaoIPTUConstr  = db_utils::getDao('iptuconstr');

		$sWhere = null;
    if ($lSomenteAtivas) {
      $sWhere = " j39_matric = {$this->iMatricula} and j39_dtdemo is null ";
    }

		$sSqlConstrucoes = $oDaoIPTUConstr->sql_query_file($this->iMatricula, null, 'j39_idcons',null, $sWhere);

		$rsConstrucoes   = $oDaoIPTUConstr->sql_record($sSqlConstrucoes);

		$aConstrucoes    = array();
		
		if ($oDaoIPTUConstr->numrows > 0) {
			
		  $aIdConstrucoes = db_utils::getCollectionByRecord($rsConstrucoes);
  		/**
  		 * Constroi um array com a instancia do objeto construções
  		 */
  		foreach ($aIdConstrucoes as $oConstrucao) {
  				
  			$aConstrucoes[$oConstrucao->j39_idcons] = new Construcao($this->iMatricula, $oConstrucao->j39_idcons);
  		}
  		
		}

		return $aConstrucoes;
		
	}
	
	/**
	* Retorna o lote ao qual pertence o imóvel
	* @throws Exception Código do lote
	* @return object Lote
	*/
	public function getLote() {
	
	  db_app::import('cadastro.Lote');
	   
	  if(empty($this->iCodigoLote)) {
	    throw new Exception('Código do lote não informado');
	  }
	   
	  return new Lote($this->iCodigoLote);
	   
	}
	
	/**
	 * Retorna dados da isenção de uma matrícula, caso haja
	 * @return Object 
	 */
	public function getDadosIsencaoExercicio() {
	  
	  if (empty($this->iMatricula)) {
	    throw new Exception('Matrícula não informada');
	  }
	  
	  $oDaoIptuisen = db_utils::getDao('iptuisen');
	  
	  $sSqlIptuIsen = $oDaoIptuisen->sql_queryIsencao(db_getsession('DB_anousu'), $this->iMatricula);
	  
	  $rsIptuisen   = $oDaoIptuisen->sql_record($sSqlIptuIsen);
	  
    $oIsencao  = new stdClass();
    
    $oIsencao->iTipoIsencao      = '';
    $oIsencao->sDescricaoIsencao = '';
	    
    if($oDaoIptuisen->numrows > 0) {
	    $oIptuisen = db_utils::fieldsMemory($rsIptuisen, 0);
	    
	    $oIsencao->iTipoIsencao      = $oIptuisen->j45_tipo;
	    $oIsencao->sDescricaoIsencao = $oIptuisen->j45_descr;
    }
	  
	  return $oIsencao;
	  
	}
	
	/**
	 * Retona um objeto ImovelEndereco com dados do endereço da matrícula
	 * @return object
	 */
	public function getImovelEndereco() {
	  
	  db_app::import('cadastro.ImovelEndereco');
	      
    return new ImovelEndereco($this->iMatricula);	  
	  
	}
	
	/**
	 * Retorna um objeto com resultados do calculo
	 * @return object
	 */
	public function getCalculo() {
	  
	  db_app::import('cadastro.CalculoIPTU');
	  
	  return new CalculoIPTU($this->getMatricula(), db_getsession('DB_anousu'));
	  
	} 
	
	/**
	 * Retorna dados da referência anterior de uma matricula
	 * @return integer
	 */
	public function getReferenciaAnterior() {
	  
	  if (empty($this->iMatricula)) {
	    throw new Exception('Matrícula para a busca da referência anterior não encontrada');
	  }
	  
	  $oDaoIptuant = db_utils::getDao('iptuant');
	  
	  $rsIptuant   = $oDaoIptuant->sql_record($oDaoIptuant->sql_query_file($this->iMatricula));
	  
	  if ($oDaoIptuant->numrows > 0) {
	    
	    return db_utils::fieldsMemory($rsIptuant, 0)->j40_refant;
	    
	  }
	  
	  
	}
	
}
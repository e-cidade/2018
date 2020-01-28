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

require_once('libs/exceptions/DBException.php');

/**
 * Classe utilizada para salvar e verificar as regras 
 * referende ao Lançamento no ponto
 *
 * @package  Pessoal
 * @author   Renan Melo <renan@dbseller.com.br>
 * 
 */
class RegraPonto {

	/**
	 * Constante para o comportamento de aviso
	 * @var const
	 */
	const COMPORTAMENTO_AVISO    = 1;

	/**
	 * Contante para o comportamento de bloqueio
	 * @var const
	 */
	const COMPORTAMENTO_BLOQUEIO = 2;

	/**
	 * Codigo da Regra
	 * @var integer
	 * @access private
	 */
	private $iCodigo;

	/**
	 * Descrição da Regra
	 * @var string $sDescricao
	 * @access public
	 */
	private $sDescricao;

  /**
   * Selecao que a regra pertence.
   * @var integer $iSelecao
   * @access public
   */
	private $iCodigoSelecao;

  /**
   * Comportamento que a regra deve obedecer
   * - 1 -> Aviso
   * - 2 -> Bloqueio
   * @var integer $iComportamento
   * @access public
   */
	private $iComportamento;

	/**
	 * Rubbricas selecionadas para a regra
	 * @var Array
	 * @access public
	 */
	private $aRubricas = array();
	
	/**
	 * Instância do objeto Instituição
	 * @var Instituicao
	 */
	private $oInstituicao;

	/**
	 * Instânciando a classe com o código da regra
	 * @param string $iCodigo
	 * @param string $iCodigoInstituicao
	 * @throws DBException
	 */
	function __construct ( $iCodigo = null, $iCodigoInstituicao = null ) {

		if ( empty($iCodigo) ) {
			return;
		}
		
		if ( empty($iCodigoInstituicao)) {
			$iCodigoInstituicao = db_getsession('DB_instit');
		}
			
		$oDaoRegraPonto = db_utils::getDao('regraponto');
		
		$sSqlRegraPonto = $oDaoRegraPonto->sql_query_file($iCodigo);
		$rsRegraPonto   = $oDaoRegraPonto->sql_record($sSqlRegraPonto);
		
		if (!$rsRegraPonto || $oDaoRegraPonto->numrows == 0) {
			throw new DBException("Nenhuma regra de lançamento encontrada para o código {$iCodigo}.");
		} 
			
		$oRegraPonto    = db_utils::fieldsMemory($rsRegraPonto, 0);
		
		$this->setCodigo($oRegraPonto->rh123_sequencial);
		$this->setDescricao($oRegraPonto->rh123_descricao);
		$this->setCodigoSelecao($oRegraPonto->rh123_selecao);
		$this->setComportamento($oRegraPonto->rh123_comportamento);
		$this->setInstituicao(new Instituicao($oRegraPonto->rh123_instit));
		
		$oDaoRegraPontoRhRubricas    = db_utils::getDao('regrapontorhrubricas');
		$sWhereRegraPontoRhRubricas  = "rh124_instit = {$iCodigoInstituicao} and rh124_regraponto = {$this->getCodigo()}";
		$sCamposRegraPontoRhRubricas = "rh124_rubrica as codigo_rubrica";
		$sSqlRegraPontoRhRubricas    = $oDaoRegraPontoRhRubricas->sql_query_file(null, 
				 																																     $sCamposRegraPontoRhRubricas, 
																																						 "rh124_sequencial",
																																						 $sWhereRegraPontoRhRubricas);
		$rsRegraPontoRhRubricas      = $oDaoRegraPontoRhRubricas->sql_record($sSqlRegraPontoRhRubricas);
		
		$this->aRubricas = array();
		foreach (db_utils::getCollectionByRecord($rsRegraPontoRhRubricas) as $oRegraPontoRhRubrica) {
			$this->adicionarRubrica(new Rubrica($oRegraPontoRhRubrica->codigo_rubrica));
		}
		return;
	}
	
	/**
	 * Seta o código da Regra
	 *
	 * @param integer $iCodigo.
	 * @access public
	 * @return void.
	 */
	public function setCodigo ($iCodigo) {
		$this->iCodigo = $iCodigo;
	}
	
	/**
	 * Retorna o código da regra
	 * @return integer
	 */
	public function getCodigo() {
		return $this->iCodigo;
	}
	
	/**
	 * Seta a seleção da regra
	 *
	 * @param integer $iSelecao.
	 * @access public
	 * @return void.
	 */
	public function setCodigoSelecao ($iCodigoSelecao) {
		$this->iCodigoSelecao = $iCodigoSelecao;
	}
	
	/**
	 * Retorna o código da seleção
	 * @return integer
	 */
	public function getCodigoSelecao() {
		return $this->iCodigoSelecao;
	}

	/**
	 * Seta a descrição da Regra
	 * 
	 * @param String $sDescricao.
	 * @access public
	 * @return void.
	 */
	public function setDescricao ($sDescricao) {
		$this->sDescricao = $sDescricao;	
	}

	/**
	 * Retorna a descrição da regra
	 * @return string
	 */
	public function getDescricao() {
		return $this->sDescricao;
	}

	/**
	 * Seta o comportamento da Regra
	 * - 1 -> Aviso
   * - 2 -> Bloqueio
   * 
	 * @param integer $iComportamento.
	 * @access public
	 * @return void.
	 */
	public function setComportamento ($iComportamento) {
		$this->iComportamento = $iComportamento;		
	}
	
	/**
	 * Retorna o código do comportamento que devera ser aplicado a regra
	 * 1 Aviso, 2 Bloqueio
	 * @return number
	 */
	public function getComportamento() {
		return $this->iComportamento;
	}
	
	/**
	 * Instancia do objeto Instituicao 
	 * @param Instituicao $oInstituicao
	 */
	public function setInstituicao(Instituicao $oInstituicao) {
		$this->oInstituicao = $oInstituicao;
	}
	
	/**
	 * Retorna a instância do objeto Instituicao
	 * @return Instituicao
	 */
	public function getInstituicao() {
		return $this->oInstituicao;
	}

	/**
	 * Limpa o Array de Rubricas
	 * 
	 * @access public
	 * @return void.
	 */
	public function limparRubricas() {
		$this->aRubricas = array();
	}

	/**
	 * Salvar os dados referente a regra na tabela regraPonto
	 * Salva também as Rubricas que são vinculadas a esta Regra.
	 * 
	 * @access public
	 * @return boolean.
	 */
	public function salvar() {

    /**
     * necessario chamar funcao db_inicio_transacao()
     */
    if( !db_utils::inTransaction() ) {
			throw new Exception("Nenhuma Transação com o banco Ativa.");
		}
		
    /**
     * Inclui regra ponto
     */
    if ($this->iCodigo == '') {

			$oDaoRegraPonto = db_utils::getDao('regraponto');
			
			$oDaoRegraPonto->rh123_descricao 		 = $this->getDescricao();
			$oDaoRegraPonto->rh123_selecao 			 = $this->getCodigoSelecao();
			$oDaoRegraPonto->rh123_comportamento = $this->getComportamento();
			$oDaoRegraPonto->rh123_instit        = $this->getInstituicao()->getSequencial();
			$oDaoRegraPonto->incluir(null);
	
			$this->setCodigo( $oDaoRegraPonto->rh123_sequencial );
	
			if ( $oDaoRegraPonto->erro_status == "0" ) {
				throw new DBException("Não foi possível cadastrar a Regra para o Ponto. {$oDaoRegraPonto->erro_msg}");
			}
			
		} else {
			
      /**
       * Alterar regra ponto
       */
      $oDaoRegraPonto = db_utils::getDao('regraponto');
			$oDaoRegraPonto->rh123_sequencial    = $this->getCodigo(); 
			$oDaoRegraPonto->rh123_descricao 		 = $this->getDescricao();
			$oDaoRegraPonto->rh123_selecao 			 = $this->getCodigoSelecao();
			$oDaoRegraPonto->rh123_comportamento = $this->getComportamento();
			$oDaoRegraPonto->rh123_instit        = $this->getInstituicao()->getSequencial();
			$oDaoRegraPonto->alterar($this->getCodigo());
			
			if ( $oDaoRegraPonto->erro_status == "0" ) {
				throw new DBException("Não foi possível cadastrar a Regra para o Ponto. {$oDaoRegraPonto->erro_msg}");
			}
		}
		
		db_utils::getDao('regrapontorhrubricas', false);

    /**
     * Exclui rubricas do ponto 
     */
    $oDaoRegraPontoRhRubricas = new cl_regrapontorhrubricas();
		$sWhereExclusao           = "    rh124_regraponto = {$this->getCodigo()} 								  ";
		$sWhereExclusao          .= "and rh124_instit = {$this->getInstituicao()->getSequencial()}";
		$oDaoRegraPontoRhRubricas->excluir(null, $sWhereExclusao);
		
		if ($oDaoRegraPontoRhRubricas->erro_status == '0') {
			throw new DBException("Erro ao alterar rubricas da regra {$this->getCodigo()}. {$oDaoRegraPonto->erro_msg}");
		}
		
    /**
     * Inclui rubricas do ponto
     */
    foreach ($this->getRubricas() as $oRubrica) {
		
			$oDaoRegraPontoRhRubricas = new cl_regrapontorhrubricas();
			$oDaoRegraPontoRhRubricas->rh124_regraponto  = $this->getCodigo();
			$oDaoRegraPontoRhRubricas->rh124_rubrica     = $oRubrica->getCodigo();
			$oDaoRegraPontoRhRubricas->rh124_instit      = $oRubrica->getInstituicao();
			$oDaoRegraPontoRhRubricas->incluir(null);
		
			if ( $oDaoRegraPontoRhRubricas->erro_status == "0" ) {
				throw new DBException("Não foi possível cadastrar a Rubrica: {$oRubrica->getInstituicao} para a regra. {$oDaoRegraPontoRhRubricas->erro_msg}");
			}
		}
		
		return true;
	}
	
  /**
   * Cria uma coleção de Objetos Rubricas
   * @param Rubrica $oRubrica
   */
	public function adicionarRubrica(Rubrica $oRubrica)  {
		$this->aRubricas[$oRubrica->getCodigo()] = $oRubrica;
	}
	
	/**
	 * Cria uma coleção de Objetos Rubricas
	 * @param Rubrica $oRubrica
	 */
	public function removerRubrica(Rubrica $oRubrica)  {
		unset($this->aRubricas[$oRubrica->getCodigo()]);
	}
	
	/**
	 * Retorna uma coleção de objetos Rubricas com as rubricas pertencentes a regra
	 * @return array:
	 */
	public function getRubricas() {
		return $this->aRubricas;
	}

	/**
	 * Retorna o campo r44_where da tabela selecao a partir do codigo da regra informado.
	 * 
	 * @access private
	 * @return string r44_where.
	 */
  private function getSelecao () {

  	$oDaoRegraPonto   = db_utils::getDao('regraponto');
  	$sSqlRegraPonto   = $oDaoRegraPonto->sql_query( $this->getCodigo(), "r44_where");
  	$rsRegraPonto     = $oDaoRegraPonto->sql_record($sSqlRegraPonto);
		$oDadosRegraPonto = db_utils::fieldsMemory($rsRegraPonto, 0);

		if ( $oDaoRegraPonto->erro_status == "0" ) {
			throw new DBException("Não foi possível retornar a seleção. {$oDaoRegraPonto->erro_msg}");
		}

		return $oDadosRegraPonto->r44_where;
	}

	/**
	 * Verifica se o ponto e a rubrica informado satisfazem a condição para a regra cadastrada.
	 * - Se satisfazer retorna FALSE senão TRUE.
	 * 
	 * @param Ponto $oPonto.
	 * @access public
	 * @return boolean
	 */
	public function testarRegistroPonto( Ponto $oPonto) {

	  db_utils::getDao('rhpessoalmov', false);
	  $oDaoRhPessoalMov   = new cl_rhpessoalmov();
    $oReflectionClass   = new ReflectionClass($oPonto);
                       
    $sTabela            = $oReflectionClass->getConstant('TABELA');
    $sSigla             = $oReflectionClass->getConstant('SIGLA_TABELA');
    $sCampos            = '*';
    $sWhere             = "rh01_regist = {$oPonto->getServidor()->getMatricula()} ";
    $sWhere            .= " and {$this->getSelecao()} ";

    $sSqlBaseServidores = $oDaoRhPessoalMov->sql_query_baseServidores( $oPonto->getMesCompetencia(), 
    									                                               $oPonto->getAnoCompetencia(), 
    									                                               $oPonto->getServidor()->getInstituicao()->getSequencial(),
    									                                               $sCampos,
    									                                               $sWhere );
    
    $rsRhPessoalMov 		= db_query($sSqlBaseServidores);
    
    if ( !$rsRhPessoalMov ) {
			throw new DBException("Não foi possível retornar a seleção.\n\nErro técnico:\n" . pg_last_error());
		}

    /**
     * Encontrou registros que satisfazem a selecao 
     */
		if ( pg_num_rows($rsRhPessoalMov) > 0 ) {
			return false;
		} 

    return true;
	}
	
	/**
	 * Retorna uma coleção de objetos RegraPonto da rubrica informada
	 * @param Rubrica $oRubrica
	 * @throws ParameterException 
	 * @return array RegraPonto
	 */
	public static function getRegrasPorRubrica ( Rubrica $oRubrica ) {
	
		if (empty($oRubrica)) {
			throw new ParameterException('Rubrica não encontrada ou inválida.');
		}
	
		$oDaoRegraPontoRhrubricas   = db_utils::getDao('regrapontorhrubricas');
	
		$sWhereRegraPontoRhRubricas = "rh124_rubrica = '{$oRubrica->getCodigo()}' and rh124_instit = {$oRubrica->getInstituicao()}";
		$sSqlRegraPontoRhRubricas   = $oDaoRegraPontoRhrubricas->sql_query_file (null,
				                                                                     "rh124_regraponto as codigo_regra",
				                                                                     "rh124_regraponto",
				                                                                     $sWhereRegraPontoRhRubricas);
		$rsRegraPontoRhRubricas   	= $oDaoRegraPontoRhrubricas->sql_record($sSqlRegraPontoRhRubricas);
		$aRegras                    = db_utils::getCollectionByRecord($rsRegraPontoRhRubricas);
		
		$aRegrasLancamento          = array();
		foreach ($aRegras as $oRegra) {

			$aRegrasLancamento[] = new RegraPonto($oRegra->codigo_regra);
		}
		
		return $aRegrasLancamento;
	}

	/**
   * Excluir regra ponto e suas rubricas
   *
   * @access public
   * @return boolean
   */
  public function excluir() {

    /**
     * necessario chamar funcao db_inicio_transacao()
     */
    if( !db_utils::inTransaction() ) {
			throw new Exception("Nenhuma Transação com o banco Ativa.");
		}

		if ( empty($this->iCodigo) ) {
      throw new DBException("Erro ao excluir regra do ponto, código da regra não informado.");
    }

    $oDaoRegraPontoRhRubricas = db_utils::getDao('regrapontorhrubricas');
    $sWhereExclusao           = "    rh124_regraponto = {$this->getCodigo()} 								  ";
    $sWhereExclusao          .= "and rh124_instit = {$this->getInstituicao()->getSequencial()}";
    $oDaoRegraPontoRhRubricas->excluir(null, $sWhereExclusao);

    if ($oDaoRegraPontoRhRubricas->erro_status == '0') {
      throw new DBException("Erro ao excluir rubricas da regra {$this->getCodigo()}. {$oDaoRegraPonto->erro_msg}");
    }

    $oDaoRegraPonto = db_utils::getDao('regraponto');
    $oDaoRegraPonto->excluir($this->getCodigo());

    if ( $oDaoRegraPonto->erro_status == "0" ) {
      throw new DBException("Não foi possível excluir regra ponto.\n\n{$oDaoRegraPonto->erro_msg}");
    }

    return true;
  }
}
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
 * Model para agenda de assentamentos
 *
 * @package pessoal
 * @author Renan Silva <renan.silva@dbseller.com.br>
 */
class AgendaAssentamento {

	/**
	 * Código assentamento
	 * @var integer
	 */
	private $iCodigo;

	/**
	 * Tipo de Assentamento agendado
	 * @var TipoAssentamento
	 */
	private $oTipoAssentamento;

	/**
	 * Instituicao do agendamento do tipo de assentamento
	 * @var Instituicao
	 */
	private $oInstituicao;
	
	/**
	 * Fórmula de Início do Agendamento
	 * @var String
	 */
	private $sNomeFormulaInicio;

	/**
	 * Fórmula de Fim do Agendamento
	 * @var String
	 */
	private $sNomeFormulaFim;

	/**
	 * Fórmula de Faltas Período do Agendamento
	 * @var String
	 */
	private $sNomeFormulaFaltasPeriodo;

	/**
	 * Fórmula de Condição do Lançamento de Assentamento
	 * @var String
	 */
	private $sNomeFormulaCondicao;

	/**
	 * Fórmula de prorrogação da data final do assentamento
	 * @var String
	 */
	private $sNomeFormulaProrrogaFim;

	/**
	 * Seleção onde será aplicada a agenda
	 * @var Integer
	 */
	private $iSelecao;

	/**
	 * Lista com as seleções que serão aplicadas na agenda de assentamentos
	 * @var Selecao[]
	 */
	private $aListaSelecao;

	/**
	 * Construtor da classe
	 * 
	 * @param Integer $iCodigo
	 */
	public function __construct($iCodigo = null) {

		if ( empty($iCodigo) ) {
			return;
		}
		
		$this->setCodigo($iCodigo);
	}

	/**
	 * Retorna o código do assentamento
	 * @return number
	 */
	public function getCodigo() {
		return $this->iCodigo;
	}

	/**
	 * Define o código do assentamento
	 * @param integer $iCodigo
	 */
	public function setCodigo($iCodigo) {
		$this->iCodigo = $iCodigo;
	}

	/**
	 * Retorna o Tipo de Assentamento da agenda de assentamentos
	 * @return TipoAssentamento
	 */
	public function getTipoAssentamento() {
		return $this->oTipoAssentamento;
	}

	/**
	 * Define o Tipo de Assentamento para a agenda
	 * @param TipoAssentamento $oTipoAssentamento
	 */
	public function setTipoAssentamento ($oTipoAssentamento) {
		$this->oTipoAssentamento = $oTipoAssentamento;
	}

	/**
	 * Retorna a Instituicao da agenda de assentamentos
	 * @return Instituicao
	 */
	public function getInstituicao() {
		return $this->oInstituicao;
	}

	/**
	 * Define a Instituicao para a agenda de assentamentos
	 * @param Instituicao $oInstituicao
	 */
	public function setInstituicao ($oInstituicao) {
		$this->oInstituicao = $oInstituicao;
	}

	/**
	 * Retorna a Formula de Inicio da agenda de assentamentos
	 * @return String
	 */
	public function getNomeFormulaInicio() {
		return $this->sNomeFormulaInicio;
	}

	/**
	 * Define a Formula de Inicio para a agenda de assentamentos
	 * @param String $oFormulaInicio
	 */
	public function setNomeFormulaInicio ($sNomeFormulaInicio) {
		$this->sNomeFormulaInicio = $sNomeFormulaInicio;
	}

	/**
	 * Retorna a Formula de Fim da agenda de assentamentos
	 * @return String
	 */
	public function getNomeFormulaFim() {
		return $this->sNomeFormulaFim;
	}

	/**
	 * Define a Formula de Fim para a agenda de assentamentos
	 * @param String $oFormulaFim
	 */
	public function setNomeFormulaFim ($sNomeFormulaFim) {
		$this->sNomeFormulaFim = $sNomeFormulaFim;
	}

	/**
	 * Retorna a Formula de Faltas Periodo da agenda de assentamentos
	 * @return String
	 */
	public function getNomeFormulaFaltasPeriodo() {
		return $this->sNomeFormulaFaltasPeriodo;
	}

	/**
	 * Define a Formula de Faltas Periodo para a agenda de assentamentos
	 * @param String $oFormulaInicio
	 */
	public function setNomeFormulaFaltasPeriodo ($sNomeFormulaFaltasPeriodo) {
		$this->sNomeFormulaFaltasPeriodo = $sNomeFormulaFaltasPeriodo;
	}

	/**
	 * Retorna a Formula de Condicao da agenda de assentamentos
	 * @return String
	 */
	public function getNomeFormulaCondicao() {
		return $this->sNomeFormulaCondicao;
	}

	/**
	 * Define a Formula de Condicao para a agenda de assentamentos
	 * @param String $sNomeFormulaCondicao
	 */
	public function setNomeFormulaCondicao ($sNomeFormulaCondicao) {
		$this->sNomeFormulaCondicao = $sNomeFormulaCondicao;
	}

	/**
	 * Retorna a Selecao que a agenda de assentamentos será aplicada
	 * @return String
	 */
	public function getSelecao() {
		return $this->iSelecao;
	}

	/**
	 * Define a Selecao que a agenda de assentamentos será aplicada
	 * @param Integer $iSelecao
	 */
	public function setSelecao ($iSelecao) {
		$this->iSelecao = $iSelecao;
	}

	/**
	 * Retorna uma lista com a Selecao que a agenda de assentamentos será aplicada
	 * @return String
	 */
	public function getListaSelecao() {
		return $this->aListaSelecao;
	}

	/**
	 * Define uma lista com a Selecao que a agenda de assentamentos será aplicada
	 * @param Integer $aListaSelecao
	 */
	public function setListaSelecao ($aListaSelecao) {
		$this->aListaSelecao = $aListaSelecao;
	}

	/**
	 * Persist na base a agenda de assentamento
	 * @return mixed true | String mensagem de erro
	 */
	public function persist() {}

	/**
	 * Transforma o objeto em um formato JSON
	 * @return JSON
	 */
  public function toJSON() {}


	/**
	 * Define a formula de prorrogação do assentamento
	 * @param $sNomeFormulaProrrogaFim
	 * @return mixed
	 */
	public function setNomeFormulaProrrogaFim($sNomeFormulaProrrogaFim) {
		return $this->sNomeFormulaProrrogaFim = $sNomeFormulaProrrogaFim;
	}

	/**
	 * Retorna a formula de prorrogação do assentamento
	 * @return mixed
	 */
	public function getNomeFormulaProrrogaFim() {
		return $this->sNomeFormulaProrrogaFim;
	}

}
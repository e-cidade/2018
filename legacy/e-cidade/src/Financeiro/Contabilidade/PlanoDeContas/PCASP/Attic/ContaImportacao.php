<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 28/11/16
 * Time: 16:41
 */

namespace ECidade\Financeiro\Contabilidade\PlanoDeContas\PCASP;

class ContaImportacao {

	/**
	 * @var int
	 */
	private $iCodigo;

	/**
	 * @var int
	 */
	private $iModelo;

	/**
	 * @var string
	 */
	private $sEstrutural;

	/**
	 * @var string
	 */
	private $sTitulo;

	/**
	 * @var string
	 */
	private $sFuncao;

	/**
	 * @var int
	 */
	private $iNaturezaSaldo;

	/**
	 * @var boolean
	 */
	private $lAnalitica;

	/**
	 * @var int
	 */
	private $iSistema;

	/**
	 * @var string
	 */
	private $sIndicardorSuperavit;

	/**
	 * @return int
	 */
	public function getCodigo() {
		return $this->iCodigo;
	}

	/**
	 * @param int $iCodigo
	 */
	public function setCodigo($iCodigo) {
		$this->iCodigo = $iCodigo;
	}

	/**
	 * @return int
	 */
	public function getModelo() {
		return $this->iModelo;
	}

	/**
	 * @param int $iModelo
	 */
	public function setModelo($iModelo) {
		$this->iModelo = $iModelo;
	}

	/**
	 * @return string
	 */
	public function getEstrutural() {
		return $this->sEstrutural;
	}

	/**
	 * @param string $sEstrutural
	 */
	public function setEstrutural($sEstrutural) {
		$this->sEstrutural = $sEstrutural;
	}

	/**
	 * @return string
	 */
	public function getTitulo() {
		return $this->sTitulo;
	}

	/**
	 * @param string $sTitulo
	 */
	public function setTitulo($sTitulo) {
		$this->sTitulo = $sTitulo;
	}

	/**
	 * @return string
	 */
	public function getFuncao() {
		return $this->sFuncao;
	}

	/**
	 * @param string $sFuncao
	 */
	public function setFuncao($sFuncao) {
		$this->sFuncao = $sFuncao;
	}

	/**
	 * @return int
	 */
	public function getNaturezaSaldo() {
		return $this->iNaturezaSaldo;
	}

	/**
	 * @param int $iNaturezaSaldo
	 */
	public function setNaturezaSaldo($iNaturezaSaldo) {
		$this->iNaturezaSaldo = $iNaturezaSaldo;
	}

	/**
	 * @return boolean
	 */
	public function isAnalitica() {
		return $this->lAnalitica;
	}

	/**
	 * @param boolean $lAnalitica
	 */
	public function setAnalitica($lAnalitica) {
		$this->lAnalitica = $lAnalitica;
	}

	/**
	 * @return int
	 */
	public function getSistema() {
		return $this->iSistema;
	}

	/**
	 * @param int $iSistema
	 */
	public function setSistema($iSistema) {
		$this->iSistema = $iSistema;
	}

	/**
	 * @return string
	 */
	public function getIndicardorSuperavit() {
		return $this->sIndicardorSuperavit;
	}

	/**
	 * @param string $sIndicardorSuperavit
	 */
	public function setIndicardorSuperavit($sIndicardorSuperavit) {
		$this->sIndicardorSuperavit = $sIndicardorSuperavit;
	}


}
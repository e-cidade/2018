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

namespace ECidade\Financeiro\Contabilidade\PlanoDeContas\PCASP\Importacao;

use ECidade\Financeiro\Contabilidade\PlanoDeContas\PCASP\Importacao\Modelo;

class Conta {

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
	private $sIndicadorSuperavit;

	/**
	 * @var boolean
	 */
	private $lExclusao;

  /**
   * @var Modelo;
   */
  private $oModelo;
  
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
	public function getCodigoModelo() {
		return $this->iModelo;
	}

	/**
	 * @param int $iModelo
	 */
	public function setCodigoModelo($iModelo) {
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
	public function getIndicadorSuperavit() {
		return $this->sIndicadorSuperavit;
	}

	/**
	 * @param $sIndicadorSuperavit
	 */
	public function setIndicadorSuperavit($sIndicadorSuperavit) {
		$this->sIndicadorSuperavit = $sIndicadorSuperavit;
	}

	/**
	 * @return boolean
	 */
	public function isExclusao() {
		return $this->lExclusao;
	}

	/**
	 * @param boolean $lExclusao
	 */
	public function setExclusao($lExclusao) {
		$this->lExclusao = $lExclusao;
	}

  /**
   * @return \ECidade\Financeiro\Contabilidade\PlanoDeContas\PCASP\Importacao\Modelo
   */
  public function getModelo() {
    
    if (empty($this->oModelo) && !empty($this->iModelo)) {
      $this->oModelo = new Modelo($this->iModelo);
    }
    return $this->oModelo;
  }
  
	/**
	 * Retorna o estrutural formatado.
	 * @return string
	 */
	public function getEstruturalFormatado() {

		if (empty($this->sEstrutural)) {
			return null;
		}
		
		return str_replace("." , "", $this->getEstrutural());
	}
}
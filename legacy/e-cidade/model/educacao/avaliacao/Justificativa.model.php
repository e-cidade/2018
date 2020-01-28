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
 * Classe com os dados de uma Justificativa
 * @package educacao
 * @subpackage avaliacao
 * @author Fabio Esteves <fabio.esteves@dbseller.com.br>
 *
 */
class Justificativa {

	/**
	 * Codigo da justificativa
	 * @var integer
	 */
	protected $iCodigo;

	/**
	 * Descricao da justificativa
	 * @var string
	 */
	protected $sDescricao;

	/**
	 * Identifica se a justificatica esta ativa ou nao
	 * @var boolean
	 */
	protected $lAtivo;

	/**
	 * Instancia de Escola
	 * @var Escola
	 */
	protected $oEscola;

	/**
	 * Abreviatura da justificativa
	 * @var string
	 */
	protected $sAbreviatura;

	/**
	 * Construtor da classe. Recebe o codigo da justificativa como parametro
	 * @param integer $iCodigo
	 */
	public function __construct( $iCodigo = null ) {

		if (!empty($iCodigo)) {

			$oDaoJustificativa = db_utils::getDao("justificativa");
			$sSqlJustificativa = $oDaoJustificativa->sql_query_file($iCodigo);
			$rsJustitificativa = $oDaoJustificativa->sql_record($sSqlJustificativa);

			if ($oDaoJustificativa->numrows > 0) {

				$oDadosJustificativa = db_utils::fieldsMemory($rsJustitificativa, 0);
				$this->iCodigo       = $oDadosJustificativa->ed06_i_codigo;
				$this->sDescricao    = $oDadosJustificativa->ed06_c_descr;
				$this->lAtivo        = $oDadosJustificativa->ed06_c_ativo == 't' ? true : false;
				$this->sAbreviatura  = $oDadosJustificativa->ed06_abreviatura;
				$this->oEscola       = new Escola($oDadosJustificativa->ed06_i_escola);
			}
		}
	}

	/**
	 * Retorna o codigo da justificativa
	 * @return integer
	 */
	public function getCodigo() {
		return $this->iCodigo;
	}

	/**
	 * Setamos o codigo da justificativa
	 * @param integer $iCodigo
	 */
	public function setCodigo( $iCodigo ) {
		$this->iCodigo = $iCodigo;
	}

	/**
	 * Retorna a descricao da justificativa
	 * @return string
	 */
	public function getDescricao() {
		return $this->sDescricao;
	}

	/**
	 * Setamos a descricao da justificativa
	 * @param string $sDescricao
	 */
	public function setDescricao( $sDescricao ) {
		$this->sDescricao = $sDescricao;
	}

	/**
	 * Retorna se a justificativa esta ativa
	 * @return boolean
	 */
	public function isAtivo() {
		return $this->lAtivo;
	}

	/**
	 * Seta se a justificativa esta ativa ou nao
	 * @param boolean $lAtivo
	 */
	public function setAtivo( $lAtivo ) {
		$this->lAtivo = $lAtivo;
	}

	/**
	 * Retorna uma instancia de Escola
	 * @return Escola
	 */
	public function getEscola() {
		return $this->oEscola;
	}

	/**
	 * Seta uma instancia de Escola
	 * @param Escola $oEscola
	 */
	public function setEscola( Escola $oEscola ) {
		$this->oEscola = $oEscola;
	}


	/**
	 * Setter abreviatura
	 * @param string
	 */
	public function setAbreviatura($sAbreviatura) {
	  $this->sAbreviatura = $sAbreviatura;
	}

	/**
	 * Getter abreviatura
	 * @param string
	 */
	public function getAbreviatura() {
	  return $this->sAbreviatura;
	}

}
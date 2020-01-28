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

namespace ECidade\Financeiro\Contabilidade\PlanoDeContas\PCASP;

use ECidade\Financeiro\Contabilidade\PlanoDeContas\PCASP\Modelo;

/**
 * Class Importacao
 * Classe que represetação a importação do plano de contas do PCASP.
 * @package ECidade\Financeiro\Contabilidade\PlanoDeContas\PCASP
 */
class Importacao {

	/**
	 * @var int
	 */
	private $iId;

	/**
	 * @var int
	 */
	private $iModelo;

	/**
	 * @var \DBDate
	 */
	private $oData;

	/**
	 * @var Modelo
	 */
	private $oModelo;

	public function __construct($iCodigo = null) {

		if (!empty($iCodigo)) {

			$oDao = new \cl_importacaoplanoconta();
			$sSql = $oDao->sql_query_file($iCodigo);
			$rsResult = db_query($sSql);

			if (!$rsResult) {
				throw new \DBException("Houve uma falha ao buscar a imortação do PCASP com código {$iCodigo}.");
			}

			if (pg_num_rows($rsResult) != 1) {
				throw new \DBException("Importação do PCASP com código {$iCodigo} não encontrado.");
			}

			$oStd = \db_utils::fieldsMemory($rsResult, 0);
			$this->setId($oStd->c96_sequencial);
			$this->setCodigoModelo($oStd->c96_modeloplanoconta);
			$this->setData(new \DBDate($oStd->c96_data));
		}
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->iId;
	}

	/**
	 * @param int $iId
	 */
	public function setId($iId) {
		$this->iId = $iId;
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
	 * @return \DBDate
	 */
	public function getData() {
		return $this->oData;
	}

	/**
	 * @param \DBDate $oData
	 */
	public function setData($oData) {
		$this->oData = $oData;
	}

	/**
	 * @return Modelo
	 * @throws \ParameterException
	 */
	public function getModelo() {

		if (empty($this>$this->getCodigoModelo())) {
			throw new \ParameterException("Código do modelo do PCASP não informado.");
		}

		if (empty($this>$this->oModelo)) {
			$this->oModelo = new Modelo($this->getCodigoModelo());
		}
		return $this->oModelo;
	}

	public function salvar() {

		$oDao = new \cl_importacaoplanoconta();
		$oDao->c96_modeloplanoconta = $this->getCodigoModelo();
		$oDao->c96_data = $this->getData()->getDate(\DBDate::DATA_EN);

		if (!$oDao->incluir()) {
			return false;
		}

		$this->setId($oDao->c96_sequencial);
		return true;
	}

}
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


namespace ECidade\Patrimonial\Licitacao\Licitacon;

use ECidade\Patrimonial\Licitacao\Licitacon\Julgamento;
use ECidade\Patrimonial\Licitacao\Licitacon\Versao;
use ECidade\Patrimonial\Licitacao\Licitacon\Situacao;
use \ParameterException;
use \EventoLicitacao;
use \SituacaoLicitacao;

/**
 * Class Resultado
 * Classe para centralizar as regras de tipo de resultado, utilizados em diversos lugares do LicitaCon.
 * @package ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao
 */
class Resultado {

	/**
	 * @var Versao
	 */
	private $oVersao;

	/**
	 * @var \licitacao
	 */
	private $oLicitacao;

	/**
	 * @var int|null
	 */
	private $iNumeroCgm;

	/**
	 * @var int|null
	 */
	private $iCodigoOrcamentoItem;

	/**
	 * Retorna o resultado da licitação de acordo com o seu tipo de julgamento.
	 * @param Versao $oVersao
	 * @param \licitacao $oLicitacao
	 */
	public function __construct(Versao $oVersao, \licitacao $oLicitacao) {

		$this->oVersao = $oVersao;
		$this->oLicitacao = $oLicitacao;
	}

	/**
	 * Número do fornecedor.
	 * @param int $iNumeroCgm
	 */
	public function setNumeroCgm($iNumeroCgm ) {
		$this->iNumeroCgm = $iNumeroCgm;
	}

	/**
	 * Código do orçamento do item.
	 * @param int $iCodigoOrcamentoItem
	 */
	public function setCodigoOrcamentoItem($iCodigoOrcamentoItem) {
		$this->iCodigoOrcamentoItem = $iCodigoOrcamentoItem;
	}

	/**
	 * Retorna o resultado da licitação de acordo com o seu tipo de julgamento conforma regras do LicitaCon.
	 * @return Situacao
	 */
	public function getResultado() {

		switch ($this->oLicitacao->getTipoJulgamento()) {

			case \licitacao::TIPO_JULGAMENTO_GLOBAL:
				return $this->getResultadoGlobal();
				break;

			case \licitacao::TIPO_JULGAMENTO_POR_ITEM:
				return $this->getResultadoItem();
				break;

			case \licitacao::TIPO_JULGAMENTO_POR_LOTE:
				return $this->getResultadoLote();
				break;
		}
		return null;
	}

	/**
	 * Retorna o resultado global para a licitação conforma regras do LicitaCon.
	 * @return Situacao
	 */
	public function getResultadoGlobal() {

		$iTipoJulgamento = $this->oLicitacao->getTipoJulgamento();
		$iCodigoSituacao = $this->oLicitacao->getSituacao()->getCodigo();

		$nVersao = floatval($this->oVersao->getVersao());
		$oSituacao = new Situacao($iCodigoSituacao);
		$oJulgamento = new Julgamento($iTipoJulgamento);

		$lVersao3 = $nVersao >= 1.3;
		if (!$oJulgamento->isGlobal() || !$this->oLicitacao->encerrada($lVersao3, $lVersao3, false)) {
			return null;
		}

		if ($oSituacao->isHomologada() || ($oSituacao->isRevogada() && $nVersao < 1.3)) {
			$oSituacao = new Situacao(SituacaoLicitacao::SITUACAO_ADJUDICADA);
		}

		return $oSituacao;
	}

	/**
	 * Retorna o resultado do item da licitação conforma regras do LicitaCon.
	 * @return Situacao
	 */
	public function getResultadoItem() {

		$iFase              = $this->oLicitacao->getFase();
		$iModalidade        = $this->oLicitacao->getModalidade()->getSiglaTipoCompraTribunal();
		$iTipoJulgamento    = $this->oLicitacao->getTipoJulgamento();
		$iSituacaoLicitacao = $this->oLicitacao->getSituacao()->getCodigo();

		$oJulgamento = new Julgamento($iTipoJulgamento);
		$oSituacao   = new Situacao($iSituacaoLicitacao);

		if (!$oJulgamento->isItem()) {
			return null;
		}

		if ($iFase != EventoLicitacao::FASE_ADJUDICACAO_HOMOLOGACAO) {
			return null;
		}

		$aModalidadesIgnorar = array('CPC', 'MAI', 'RPO', 'PRD', 'PRI');
		if (in_array($iModalidade, $aModalidadesIgnorar)) {
			return null;
		}

		if ($oSituacao->isDeserta()) {
			return $oSituacao;
		}

		if ($oSituacao->isHomologada() || $oSituacao->isAdjudicada()) {

			$iSituacaoRetorno = SituacaoLicitacao::SITUACAO_ADJUDICADA;
			if (empty($this->iCodigoOrcamentoItem)) {
				$iSituacaoRetorno = SituacaoLicitacao::SITUACAO_DESERTA;
			}
			$oSituacao = new Situacao($iSituacaoRetorno);
			return $oSituacao;
		}

		$iSituacaoRetorno = SituacaoLicitacao::SITUACAO_ADJUDICADA;
		if (empty($this->iNumeroCgm)) {
			$iSituacaoRetorno = SituacaoLicitacao::SITUACAO_FRACASSADA;
		}

		$oSituacao = new Situacao($iSituacaoRetorno);
		return $oSituacao;
	}

	/**
	 * Retorna o resultado do lote da licitação conforma regras do LicitaCon.
	 *
	 * @return Situacao
	 * @throws ParameterException
	 */
	public function getResultadoLote($oFornecedor = null) {

		if (empty($this->oLicitacao)) {
			throw new ParameterException("Licitação não informada.");
		}

		$iFase = $this->oLicitacao->getFase();
		$iTipoJulgamento = $this->oLicitacao->getTipoJulgamento();
		$iTipoModalidade = $this->oLicitacao->getModalidade()->getSiglaTipoCompraTribunal();
		$iCodigoSituacao = $this->oLicitacao->getSituacao()->getCodigo();

		$nVersao = floatval($this->oVersao->getVersao());

		$oSituacao   = new Situacao($iCodigoSituacao);
		$oJulgamento = new Julgamento($iTipoJulgamento);

		/**
		 * Conforme documentação o campo NÃO deve ser preenchido nesses casos
		 */
		if (in_array(strtoupper($iTipoModalidade), array('CPC', 'MAI', 'RPO', 'PRD', 'PRI'))) {
			return null;
		}

		if (!$oJulgamento->isLote()) {

			return null;

		} else { // $oJulgamento->isLote()

			if(!empty($oFornecedor)) {

				if(empty($oFornecedor->vencedor->documento)) {
					
					if ($iFase == EventoLicitacao::FASE_ADJUDICACAO_HOMOLOGACAO) {
						return new Situacao(SituacaoLicitacao::SITUACAO_DESERTA);
					}
				}
      }
		}

		if ($iFase != EventoLicitacao::FASE_ADJUDICACAO_HOMOLOGACAO) {
			return null;
		}

		if ($oSituacao->isHomologada() || ($oSituacao->isRevogada() && $nVersao < 1.3)) {
			$oSituacao = new Situacao(SituacaoLicitacao::SITUACAO_ADJUDICADA);
		}

		return $oSituacao;
	}
}
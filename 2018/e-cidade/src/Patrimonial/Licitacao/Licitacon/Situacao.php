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

use SituacaoLicitacao;

/**
 * Class Situacao
 * Classe para centralizar as regras da situação, utilizados em diversos lugares do LicitaCon.
 * @package ECidade\Patrimonial\Licitacao\Licitacon
 */
class Situacao {

	const SIGLA_SITUACAO_EM_ANDAMENTO = null;
	const SIGLA_SITUACAO_JULGADA      = null;
	const SIGLA_SITUACAO_REVOGADA     = 'R';
	const SIGLA_SITUACAO_DESERTA      = 'D';
	const SIGLA_SITUACAO_FRACASSADA   = 'F';
	const SIGLA_SITUACAO_ANULADA      = null;
	const SIGLA_SITUACAO_ADJUDICADO   = 'A';
	const SIGLA_SITUACAO_CONCLUIDA    = 'C';
	const SIGLA_SITUACAO_HOMOLOGADA   = 'H';

	/**
	 * @var int
	 */
	private $iCodigo;

	/**
	 * Situacao constructor.
	 *
	 * @param int $iCodigo
	 */
	public function __construct($iCodigo) {
		$this->iCodigo = $iCodigo;
	}

	/**
	 * @return int
	 */
	public function getCodigo() {
		return $this->iCodigo;
	}

	/**
	 * Retorna a sigla da situalçaoi do licitacon.
	 *
	 * @return null|string
	 */
	public function getSigla() {

		switch ($this->iCodigo) {

			case SituacaoLicitacao::SITUACAO_ANULADA:
				return self::SIGLA_SITUACAO_ANULADA;

			case SituacaoLicitacao::SITUACAO_DESERTA;
				return self::SIGLA_SITUACAO_DESERTA;

			case SituacaoLicitacao::SITUACAO_EM_ANDAMENTO:
				return self::SIGLA_SITUACAO_EM_ANDAMENTO;

			case SituacaoLicitacao::SITUACAO_FRACASSADA:
				return self::SIGLA_SITUACAO_FRACASSADA;

			case SituacaoLicitacao::SITUACAO_ADJUDICADA:
				return self::SIGLA_SITUACAO_ADJUDICADO;

			case SituacaoLicitacao::SITUACAO_HOMOLOGADA:
				return self::SIGLA_SITUACAO_HOMOLOGADA;

			case SituacaoLicitacao::SITUACAO_JULGADA:
				return self::SIGLA_SITUACAO_JULGADA;

			case SituacaoLicitacao::SITUACAO_REVOGADA:
				return self::SIGLA_SITUACAO_REVOGADA;

			default:
				return null;
		}
	}

	/**
	 * Diz se a situação é anulada.
	 * @return bool
	 */
	public function isAnulada() {
		return $this->getCodigo() == SituacaoLicitacao::SITUACAO_ANULADA;
	}

	/**
	 * Diz se a situação é deserta.
	 * @return bool
	 */
	public function isDeserta() {
		return $this->getCodigo() == SituacaoLicitacao::SITUACAO_DESERTA;
	}

	/**
	 * Diz se a situação é em andamento.
	 * @return bool
	 */
	public function isEmAndamento() {
		return $this->getCodigo() == SituacaoLicitacao::SITUACAO_EM_ANDAMENTO;
	}

	/**
	 * Diz se a situação é fracasssada.
	 * @return bool
	 */
	public function isFracassada() {
		return $this->getCodigo() == SituacaoLicitacao::SITUACAO_FRACASSADA;
	}

	/**
	 * Diz se a situação é adjudicada.
	 * @return bool
	 */
	public function isAdjudicada() {
		return $this->getCodigo() == SituacaoLicitacao::SITUACAO_ADJUDICADA;
	}

	/**
	 * Diz se a situação é homologada.
	 * @return bool
	 */
	public function isHomologada() {
		return $this->getCodigo() == SituacaoLicitacao::SITUACAO_HOMOLOGADA;
	}

	/**
	 * Diz se a situação é julgada.
	 * @return bool
	 */
	public function isJulgada() {
		return $this->getCodigo() == SituacaoLicitacao::SITUACAO_JULGADA;
	}

	/**
	 * Diz se a situação é revogada.
	 * @return bool
	 */
	public function isRevogada() {
		return $this->getCodigo() == SituacaoLicitacao::SITUACAO_REVOGADA;
	}


}
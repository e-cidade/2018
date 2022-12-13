<?php
/**
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

use ECidade\Patrimonial\Licitacao\Licitacon\Julgamento;
use ECidade\Patrimonial\Licitacao\Licitacon\Situacao;

/**
 * Class Licitacon
 */
class Licitacon {

  /**
   * @var licitacao
   */
  private $oLicitacao;

  /**
   * Licitacon constructor.
   *
   * @param licitacao $oLicitacao
   */
  public function __construct(licitacao $oLicitacao) {
    $this->oLicitacao = $oLicitacao;
  }

  /**
	 * @deprecated Usar ECidade\Patrimonial\Licitacao\Licitacon\Resultado
   * Retorna o resultado da licitação de acordo com o seu tipo de julgamento.
   * @param integer $iNumeroCgm
   * @param integer $iCodigoOrcamentoItem
   * @return null|string
   */
  public function getResultadoLicitacao($iNumeroCgm = null, $iCodigoOrcamentoItem = null) {

    switch ($this->oLicitacao->getTipoJulgamento()) {

      case licitacao::TIPO_JULGAMENTO_GLOBAL:
        return $this->getResultadoGlobal();
        break;

      case licitacao::TIPO_JULGAMENTO_POR_ITEM:
        return $this->getTipoResultadoItem($iNumeroCgm, $iCodigoOrcamentoItem);
        break;

      case licitacao::TIPO_JULGAMENTO_POR_LOTE:
        return $this->getResultadoLote();
        break;
    }
    return null;
  }

  /**
	 * @deprecated Usar ECidade\Patrimonial\Licitacao\Licitacon\Resultado
   * Retorna o resultado global da licitação.
   * @return null|string
   */
  public function getResultadoGlobal() {

    $iTipoJulgamento = $this->oLicitacao->getTipoJulgamento();

		$oJulgamento = new Julgamento($iTipoJulgamento);
    if (!$oJulgamento->isGlobal()) {
      return null;
    }

    $oSituacao = new Situacao(SituacaoLicitacao::SITUACAO_ADJUDICADA);
		return $oSituacao->getSigla();
  }

  /**
	 * @deprecated Usar ECidade\Patrimonial\Licitacao\Licitacon\Resultado
   * Retorna o resultado do item da licitação.
   * @param int $iNumeroCgm
   * @param int $iCodigoOrcamentoItem
   * @return string
   *
   */
  public function getTipoResultadoItem($iNumeroCgm = null, $iCodigoOrcamentoItem = null) {

    $iFase              = $this->oLicitacao->getFase();
    $iModalidade        = $this->oLicitacao->getModalidade()->getSiglaTipoCompraTribunal();
    $iTipoJulgamento    = $this->oLicitacao->getTipoJulgamento();
    $iSituacaoLicitacao = $this->oLicitacao->getSituacao()->getCodigo();

		$oSituacao   = new Situacao($iSituacaoLicitacao);
		$oJulgamento = new Julgamento($iTipoJulgamento);

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
    	return $oSituacao->getSigla();
    }

		if ($oSituacao->isAdjudicada() || $oSituacao->isHomologada()) {

		  $iSituacaoRetorno = SituacaoLicitacao::SITUACAO_ADJUDICADA;
		  if (empty($iCodigoOrcamentoItem)) {
        $iSituacaoRetorno = SituacaoLicitacao::SITUACAO_DESERTA;
      }

      $oSituacao = new Situacao($iSituacaoRetorno);
      return $oSituacao->getSigla();
		}

		$iSituacaoRetorno = SituacaoLicitacao::SITUACAO_ADJUDICADA;
    if ($iNumeroCgm == null) {
			$iSituacaoRetorno = SituacaoLicitacao::SITUACAO_FRACASSADA;
    }

    $oSituacao = new Situacao($iSituacaoRetorno);
		return $oSituacao->getSigla();
  }

  /**
	 * @deprecated Usar ECidade\Patrimonial\Licitacao\Licitacon\Resultado
   * Retorna o resultado para o lote da licitação.
   * @return string
   */
  public function getResultadoLote() {

    $iTipoJulgamento = $this->oLicitacao->getTipoJulgamento();
    $iTipoModalidade = $this->oLicitacao->getModalidade()->getSiglaTipoCompraTribunal();
    $iFase = $this->oLicitacao->getFase();

		$oJulgamento = new Julgamento($iTipoJulgamento);

    /**
     * Conforme documentação o campo NÃO deve ser preenchido nesses casos
     */
    if (in_array(strtoupper($iTipoModalidade), array('CPC', 'MAI', 'RPO', 'PRD', 'PRI'))) {
      return null;
    }

    if (!$oJulgamento->isLote()) {
      return null;
    }

    if ($iFase != EventoLicitacao::FASE_ADJUDICACAO_HOMOLOGACAO) {
      return null;
    }

    $oSituacao = new Situacao(SituacaoLicitacao::SITUACAO_ADJUDICADA);
		return $oSituacao->getSigla();
  }
}
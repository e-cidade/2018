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

namespace ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao;

use ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\BaseAbstract;
use ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\Contrato as RegraContrato;

class DotacaoCon extends BaseAbstract {

	const CODIGO_LAYOUT = 252;

	public function getCodigoLayout() {
		return self::CODIGO_LAYOUT;
	}

	/**
	 * Diz se deve enviar o registro.
	 * @param int $iLicitacao Código da licitação.
	 *
	 * @return bool
	 */
	public function enviar($iLicitacao) {

		if ($this->oConfiguracao->getVersao() == '1.2') {
			return true;
		}

		if (empty($iLicitacao)) {
			return true;
		}

		$aValoresNaoMostrar = array('CON', 'PER', 'ALB');

		$oAttDinamico = new \LicitacaoAtributosDinamicos($iLicitacao);
		$sTipoObjeto = $oAttDinamico->getAtributo('tipoobjeto', null);

		return !in_array($sTipoObjeto, $aValoresNaoMostrar);
	}

	/**
	 * @param int     $iContrato Código do contrato.
	 * @param \DBDate $oDataGeracao Data da geração do arquivo.
	 *
	 * @return \stdClass
	 */
	public function getDadosDaLicitacao($iContrato, \DBDate $oDataGeracao) {

		$oRegraContrato = new RegraContrato($oDataGeracao);
		return $oRegraContrato->getDadosDaLicitacaoDoContrato($iContrato);
	}
}
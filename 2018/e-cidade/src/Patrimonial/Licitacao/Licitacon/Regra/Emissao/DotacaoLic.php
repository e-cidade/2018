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
use \LicitacaoAtributosDinamicos;

/**
 * Class RegraEmissaoLicitaconDotacaoLic
 */
class DotacaoLic extends BaseAbstract {

	/**
	 * Tipos de objeto da licitação para os quais não deve-se enviar a licitação.
	 * @var array
	 */
	private static $aTiposObjetosNaoMostrar = array('CON', 'PER', 'ALB');

  /**
   * Código do leiaute versão 1.2
   * @var integer
   */
	const CODIGO_LAYOUT_V12 = 237;

	/**
	 * @return int
	 */
	public function getCodigoLayout() {
		return self::CODIGO_LAYOUT_V12;
	}

	/**
	 * Verifica se deve mostrar a dotação de acordo com as informações da licitacao.
	 * @param \licitacao $oLicitacao
	 *
	 * @return bool
	 */
	public function mostrarDotacao(\licitacao $oLicitacao) {

		if (!$this->mostrarPorLicitacao($oLicitacao)) {
			return false;
		}

		$oAtributos = new LicitacaoAtributosDinamicos();
		$oAtributos->setCodigoLicitacao($oLicitacao->getCodigo());
		$sTipoObjeto = $oAtributos->getAtributo("tipoobjeto", null);

		return $this->mostrarPorTipoDeObjeto($sTipoObjeto);
	}

	/**
	 * Verifica se deve mostrar a dotação ou não, de acordo com o valor do tipo de objeto informado.
	 * @param $sTipoObjeto
	 *
	 * @return bool
	 */
	private function mostrarPorTipoDeObjeto($sTipoObjeto) {
		return !in_array($sTipoObjeto, self::$aTiposObjetosNaoMostrar);
	}

	/**
	 * Verifica se deve exibir o registro de dotação conforme dados da licitacao.
	 * @param \licitacao $oLicitacao
	 *
	 * @return bool
	 */
	private function mostrarPorLicitacao(\licitacao $oLicitacao) {

		$sModalidade = $oLicitacao->getModalidade()->getSiglaTipoCompraTribunal();
		$lUsaRegistroPreco = $oLicitacao->usaRegistroDePreco();
		if (in_array($sModalidade, array('RPO'))) {
			return false;
		}

		if (in_array($sModalidade, array('CNC', 'PRE', 'PRP', 'RDC'))) {
			return !$lUsaRegistroPreco;
		}
		return true;
	}
}
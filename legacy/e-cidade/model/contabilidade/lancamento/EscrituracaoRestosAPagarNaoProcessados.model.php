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
 * Realiza as Escrituracoes de resto a pagar (RP)
 * @author Andrio Costa
 * @package contabilidade
 * @subpackage lancamento
 */
class EscrituracaoRestosAPagarNaoProcessados {

	/**
	 * Ano da Escrituracao
	 * @var integer
	 */
	private $iAno;

	/**
	 * Instituicao em que esta sendo realizada a Escrituracao
	 * @var Instituicao
	 */
	private $oInstituicao;

	function __construct($iAno, $iInstituicao) {

		$this->iAno         = $iAno;
		$this->oInstituicao = new Instituicao($iInstituicao);
	}

	/**
	 * Realiza a Escrituracao para o Ano e Instituicao
	 * @return integer
	 */
	public function escriturar() {
		return $this->salvar();
	}

	/**
	 * Realiza o cancelamento da Escrituracao para o Ano e Instituicao
	 * @return integer
	 */
	public function cancelarEscrituracao() {
		return $this->salvar(false);
	}

	/**
	 * Realiza a Escrituracao/Cancelamento da Escrituracao
	 * @param boolean $lIncluir
	 * @throws BusinessException
	 * @return integer
	 */
	private function salvar($lProcessar = true) {

		$sProcessar = $lProcessar ? 'true' : 'false';

		$sInstituicao = $this->oInstituicao->getSequencial() . " - " . $this->oInstituicao->getDescricao();
		$sCampos      = "c107_sequencial, c107_processado";
		$sWhere       = "    c107_instit = " .$this->oInstituicao->getSequencial();
		$sWhere      .= "and c107_ano    = {$this->iAno}";

		$oDaoCabecalhoInscricaoRP = db_utils::getDao('inscricaorestosapagarnaoprocessados');
		$sSqlCabecalhoInscricaoRP = $oDaoCabecalhoInscricaoRP->sql_query_file(null, $sCampos, null, $sWhere);
		$rsCabecalhoInscricaoRP   = $oDaoCabecalhoInscricaoRP->sql_record($sSqlCabecalhoInscricaoRP);

		$lIncluir         = false;
		$oDadosCabecalho  = null;

		if ($oDaoCabecalhoInscricaoRP->numrows > 1) {

			$sMsgErro  = "Erro Técnico: Há mais de uma escrituração para o ano {$this->iAno} e a ";
			$sMsgErro .= " instituicao {$sInstituicao}.";
			throw new BusinessException($sMsgErro);
		}

		if ($oDaoCabecalhoInscricaoRP->numrows == 0 && $lProcessar) {
			$lIncluir = true;
		} else if ($oDaoCabecalhoInscricaoRP->numrows == 0 && !$lProcessar) {

			$sMsgErro  = "Não é possível estornar a inscrição de restos a pagar do ano {$this->iAno} e a ";
			$sMsgErro .= "instituicao {$sInstituicao}.";
			$sMsgErro .= "\nAinda não foi realizada a inscrição escrituração para o ano {$this->iAno}.";
			throw new BusinessException($sMsgErro);
		}

		if (!$lIncluir) {

			$oDadosCabecalho = db_utils::fieldsMemory($rsCabecalhoInscricaoRP, 0);

			if ( !$lProcessar && $oDadosCabecalho->c107_processado == 'f' ) {

				$sMsgErro  = "Não é possível desprocessar novamente lançamentos da inscrição dos RPs do ano {$this->iAno} e a ";
				$sMsgErro .= " instituicao {$sInstituicao}.";
				throw new BusinessException($sMsgErro);

			} else if ($lProcessar && $oDadosCabecalho->c107_processado == 't') {

				$sMsgErro  = "Não é possível processar novamente lançamentos da inscrição dos RPs do ano {$this->iAno} e a ";
				$sMsgErro .= " instituicao {$sInstituicao}.";
				throw new BusinessException($sMsgErro);
			}
		}

		if (!empty($oDadosCabecalho)) {
			$lIncluir = false;
		}

		$oDaoCabecalhoInscricaoRP->c107_sequencial = null;
		$oDaoCabecalhoInscricaoRP->c107_usuario    = db_getsession("DB_id_usuario");
		$oDaoCabecalhoInscricaoRP->c107_instit     = $this->oInstituicao->getSequencial();
		$oDaoCabecalhoInscricaoRP->c107_ano        = $this->iAno;
		$oDaoCabecalhoInscricaoRP->c107_processado = "$sProcessar";

		if ($lIncluir) {
			$oDaoCabecalhoInscricaoRP->incluir(null);
		} else {

			$oDaoCabecalhoInscricaoRP->c107_sequencial = $oDadosCabecalho->c107_sequencial;
			$oDaoCabecalhoInscricaoRP->alterar($oDadosCabecalho->c107_sequencial);
		}

		if ($oDaoCabecalhoInscricaoRP->erro_status == '0') {

			$sMsgErro  = "Erro Técnico: Não foi possível escriturar/cancelar a inscricao dos restos a pagar do ";
			$sMsgErro .="exercício {$this->iAno} para a instituicao {$sInstituicao}.";
			$sMsgErro .= str_replace('\n', '\\n', $oDaoCabecalhoInscricaoRP->erro_msg);
			throw new BusinessException($sMsgErro);
		}

		return $oDaoCabecalhoInscricaoRP->c107_sequencial;
	}

	/**
	 * Processa os lancamentos contabeis para estorno/estricutacao
	 * @param LancamentoAuxiliarInscricaoRestosAPagarNaoProcessado $oLancamentoAuxiliar
	 * @param integer $iCodigoDocumento
	 * @throws BusinessException
	 */
	public function processarLancamentosContabeis($oLancamentoAuxiliar, $iCodigoDocumento) {

		$oEventoContabil = new EventoContabil($iCodigoDocumento, db_getsession("DB_anousu"));

		/**
		 * Buscamos o Historico do Evento
		 */
		$aLancamentos = $oEventoContabil->getEventoContabilLancamento();

		if (count($aLancamentos) == 0) {

			$sMsgErro  = "Nenhum lancamento encontrado para o documento {$iCodigoDocumento} ";
			$sMsgErro .= "- " .$oEventoContabil->getDescricaoDocumento();
			throw new BusinessException($sMsgErro);
		}

		$iCodigoHistorico = $aLancamentos[0]->getHistorico();
		$oLancamentoAuxiliar->setHistorico($iCodigoHistorico);
		$oEventoContabil->executaLancamento($oLancamentoAuxiliar);
	}

	/**
	 * Retorna o valor do último lançamento
	 *
	 * @param integer $iAno
	 * @param integer $iInstituicao
	 * @param integer $iTipo
	 * @return float
	 */
	public static function getValorLancamento($iAno, $iInstituicao, $iTipo) {

		$oDaoRP  = new cl_conlancaminscrestosapagar;
		$sWhere  = " c107_instit          = {$iInstituicao}      ";
		$sWhere .= " and c107_ano         = {$iAno}              ";
		$sWhere .= " and c107_tipo        = {$iTipo}             ";
		$sWhere .= " and c71_coddoc in(2005,2009)                ";
		$sOrder  = " c108_sequencial desc limit 1                ";
		$sSqlRP  = $oDaoRP->sql_query(null, 'c70_valor, c71_coddoc', $sOrder, $sWhere);
		$rsRP    = $oDaoRP->sql_record($sSqlRP);

		if ( $oDaoRP->numrows == 0 ) {
			return 0;
		}

		$oRestosAPagar = db_utils::fieldsMemory($rsRP, 0);
		return $oRestosAPagar->c70_valor;
	}

	/**
	 * Retorna o valor de exercícios anteriores do último lançamento
	 *
	 * @param integer $iAno
	 * @param integer $iInstituicao
	 * @param integer $iTipo
	 * @return float
	 */
	public static function getValorLancamentoExerciciosAnteriores($iAno, $iInstituicao, $iTipo) {

		$oDaoRP  = new cl_conlancaminscrestosapagar;
		$sWhere  = " c107_instit          = {$iInstituicao}      ";
		$sWhere .= " and c107_ano         = {$iAno}              ";
		$sWhere .= " and c107_tipo        = {$iTipo}             ";
		$sWhere .= " and c71_coddoc in(2007,2011)                ";
		$sOrder  = " c108_sequencial desc limit 1                ";
		$sSqlRP  = $oDaoRP->sql_query(null, 'c70_valor, c71_coddoc', $sOrder, $sWhere);
		$rsRP    = $oDaoRP->sql_record($sSqlRP);

		if ( $oDaoRP->numrows == 0 ) {
			return 0;
		}

		$oRestosAPagar = db_utils::fieldsMemory($rsRP, 0);
		return $oRestosAPagar->c70_valor;
	}

	/**
	 * Verifica se existe lancamento para o periodo
	 *
	 * @param integer $iAno
	 * @param integer $iInstituicao
	 * @param boolean $sDesprocessar
	 * @access public
	 * @return bool
	 */
	public static function existeLancamentoPeriodo($iAno, $iInstituicao, $sProcessados, $iTipo) {

		$oDaoRP  = new cl_conlancaminscrestosapagar;
		$sWhere  = " c107_instit         = {$iInstituicao}       ";
		$sWhere .= " and c107_ano        = {$iAno}               ";
		$sWhere .= " and c107_processado = '{$sProcessados}'     ";
		$sWhere .= " and c107_tipo       = '{$iTipo}'     ";
		$sOrder  = " c108_sequencial desc limit 1                ";
		$sSqlRP  = $oDaoRP->sql_query(null, '1', $sOrder, $sWhere);
		$rsRP    = $oDaoRP->sql_record($sSqlRP);

		if ( $oDaoRP->numrows > 0 ) {
			return true;
		}

		return false;
	}

}

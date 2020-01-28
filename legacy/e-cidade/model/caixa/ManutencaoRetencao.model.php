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
 * Class ManutencaoRetencao
 */
class ManutencaoRetencao {

	/**
	 * Verifica se o lançamento contábil tem vínculo com retenção.
	 * @param $iLancamento int Código do lançamento contábil.
	 *
	 * @return bool
	 * @throws DBException
	 * @throws ParameterException
	 */
	public static function temRetencao($iLancamento) {

		if (empty($iLancamento)) {
			throw new ParameterException("o parâmetro código do lançamento deve ser informado para verificação de retenções.");
		}

		$sCamposCorGrupo = "k105_corgrupo";
		$sWhereCorGrupo  = "c23_conlancam in ({$iLancamento}) and k105_corgrupotipo in(1, 2, 4, 5)";

		$sSqlCorGrupo = "
			select {$sCamposCorGrupo}
			from conlancamcorgrupocorrente
				inner join corgrupocorrente on c23_corgrupocorrente = k105_sequencial
			where {$sWhereCorGrupo}
		";

		$sCamposRetencao = "c70_codlan";
		$sWhereRetencao  = "k105_corgrupo in ({$sSqlCorGrupo}) and k105_corgrupotipo in (3, 6)";

		$sSqlRetencao = "
			select {$sCamposRetencao}
			from conlancam
				inner join conlancamcorgrupocorrente on c70_codlan = c23_conlancam
				inner join corgrupocorrente on c23_corgrupocorrente =  k105_sequencial
				inner join corgrupotipo on k105_corgrupotipo = k106_sequencial
			where {$sWhereRetencao}
		";

		$rsRetencao = db_query($sSqlRetencao);
		if (!$rsRetencao) {
			throw new DBException("Houve um erro ao verificar se o lançamento possui retenções.");
		}

		return pg_num_rows($rsRetencao) != 0;
	}

	/**
	 * Verifica se o lançamento contábil tem retenção vinculada. Caso tenha, exclui a retenção, seus lançamentos
	 * contábeis e dados da tesouraria equivalente.
	 *
	 * @param $iLancamento int Código do lançamento contábil.
	 * @throws DBException
	 */
	public static function excluirRetencao($iLancamento) {

		$iRetencaoReceita = self::buscaRetencaoLancamento($iLancamento);

		if (empty($iRetencaoReceita)) {
			return;
		}

		$aLancamentosRetencao = self::buscaLancamentosRetencao($iRetencaoReceita);
		foreach ($aLancamentosRetencao as $iLancamentoRetencao) {
			ManutencaoRetencao::excluirLancamentoRetencao($iLancamentoRetencao);
		}

		$oDaoRetencaoCorGrupoCorrente= new cl_retencaocorgrupocorrente();
		$oDaoRetencaoEmpAgeMov = new cl_retencaoempagemov();
		$oDaoRetencaoReceitas = new cl_retencaoreceitas();
		$oDaoRetencaoPagOrdem = new cl_retencaopagordem();

		$oDaoRetencaoCorGrupoCorrente->excluir(null, "e47_retencaoreceita = {$iRetencaoReceita}");
		if ($oDaoRetencaoCorGrupoCorrente->erro_status == 0) {

			$sErroMensagem = "Erro ao excluir a autenticação da retenção.\n";
			$sErroMensagem .= "{$oDaoRetencaoCorGrupoCorrente->erro_msg}";
			throw new DBException($sErroMensagem);
		}

		$oDaoRetencaoEmpAgeMov->excluir(null, "e27_retencaoreceitas = {$iRetencaoReceita}");
		if ($oDaoRetencaoEmpAgeMov->erro_status == 0) {

			$sErroMensagem = "Erro ao excluir a retenção.\n";
			$sErroMensagem .= "{$oDaoRetencaoEmpAgeMov->erro_msg}";
			throw new DBException($sErroMensagem);
		}

		$sSqlRetencaoPagOrdem = $oDaoRetencaoReceitas->sql_query_file($iRetencaoReceita, "e23_retencaopagordem");
		$rsRetencaoPagOrdem = db_query($sSqlRetencaoPagOrdem);
		if (!$rsRetencaoPagOrdem || pg_num_rows($rsRetencaoPagOrdem) != 1) {
			throw new DBException("Houve um erro ao buscar as informações da retenção do lançamento.");
		}
		$iRetencaoPagOrdem = db_utils::fieldsMemory($rsRetencaoPagOrdem, 0)->e23_retencaopagordem;

		$oDaoRetencaoReceitas->excluir($iRetencaoReceita);
		if ($oDaoRetencaoReceitas->erro_status == 0) {

			$sErroMensagem = "Erro ao excluir a retenção.\n";
			$sErroMensagem .= "{$oDaoRetencaoReceitas->erro_msg}";
			throw new DBException($sErroMensagem);
		}

		$oDaoRetencaoPagOrdem->excluir($iRetencaoPagOrdem);
		if ($oDaoRetencaoPagOrdem->erro_status == 0) {

			$sErroMensagem = "Erro ao excluir retenção da ordem de pagamento.\n";
			$sErroMensagem .= "{$oDaoRetencaoPagOrdem->erro_msg}";
			throw new DBException($sErroMensagem);
		}
	}

	/**
	 * Exclui o lançamento contábil da retenção e a tesouraria.
	 * @param $iLancamento int Código do lançamento da retenção.
	 */
	private static function excluirLancamentoRetencao($iLancamento) {

		$aTesouraria = self::buscaTesouraria($iLancamento);
		foreach ($aTesouraria as $oTesouraria) {
			ManutencaoTesouraria::excluirTesouraria($oTesouraria->id, $oTesouraria->data, $oTesouraria->autenticadora);
		}
		lancamentoContabil::excluirLancamento($iLancamento);
	}

	/**
	 * Busca a retenção para um lançamento contábel de retenção.
	 * @param $iCodigoLancamento int Código do lançamento contábil.
	 *
	 * @return int
	 * @throws DBException
	 */
	private static function buscaRetencaoLancamento($iCodigoLancamento) {

		$sCampos = "e27_retencaoreceitas as codigo_rentencao_receita";
		$sWhere  = "c23_conlancam = {$iCodigoLancamento} and k105_corgrupotipo in (2, 5)";

		$sSqlRetencao = "
				select  distinct {$sCampos}
				from conlancamcorgrupocorrente
					inner join corgrupocorrente on c23_corgrupocorrente = k105_sequencial 
					inner join corempagemov on (k12_id, k12_data, k12_autent) = (k105_id, k105_data, k105_autent)
					inner join empagemov on k12_codmov = e81_codmov
					inner join retencaoempagemov on e27_empagemov = e81_codmov 
				where {$sWhere}";

		$rsRetencao = db_query($sSqlRetencao);
		if (!$rsRetencao) {
			throw new DBException("Houve um erro ao verificar as retenções do lançamento.");
		}

		if (pg_num_rows($rsRetencao) > 0) {
			return db_utils::fieldsMemory($rsRetencao, 0)->codigo_rentencao_receita;
		}
		return 0;
	}

	/**
	 * Busca os lançamentos contábeis da retenção.
	 * @param $iRetencaoReceita int Código da rentenção
	 *
	 * @return int[] Códigos dos lançamentos contábeis da retenção.
	 * @throws DBException
	 */
	private static function buscaLancamentosRetencao($iRetencaoReceita) {

		$aLancamentos = array();

		$sCampos = "c23_conlancam as lancamento";
		$sWhere  = "e23_sequencial = {$iRetencaoReceita} ";

		$sSqlLancamentoRetencao = "
					select distinct {$sCampos}
					from retencaoreceitas
  					inner join retencaocorgrupocorrente  on e47_retencaoreceita = e23_sequencial
  					inner join corgrupocorrente          on e47_corgrupocorrente = k105_sequencial
  					inner join conlancamcorgrupocorrente on c23_corgrupocorrente = k105_sequencial
					where {$sWhere} ";

		$rsLancamentosRetencao = db_query($sSqlLancamentoRetencao);
		if (!$rsLancamentosRetencao || pg_num_rows($rsLancamentosRetencao) == 0) {
			throw new DBException("Houve um erro ao buscar os lançamentos da retenção.");
		}

		$iLinhas = pg_num_rows($rsLancamentosRetencao);
		for ($i = 0; $i < $iLinhas; $i++) {
			$aLancamentos[] = db_utils::fieldsMemory($rsLancamentosRetencao, $i)->lancamento;
		}

		return $aLancamentos;
	}

	/**
	 * Busca os dados da tesouraria para o lançamento de retenção.
	 * @param $iLancamento int Código do lançamento de retenção.
	 *
	 * @return stdClass[] Informações da tesouraria.
	 * @throws DBException
	 */
	private static function buscaTesouraria($iLancamento) {

		$aTesouraria = array();

		$sCampos = "k105_data as data, k105_autent as autenticadora, k105_id as id";
		$sWhere  = "c23_conlancam = {$iLancamento}";

		$sSqlLancamentoCorrente = "
						select distinct {$sCampos}
						from conlancamcorgrupocorrente
							inner join corgrupocorrente on k105_sequencial = c23_corgrupocorrente
						where {$sWhere} ";

		$rsLancamentoCorrente = db_query($sSqlLancamentoCorrente);
		if (!$rsLancamentoCorrente) {
			throw new DBException("Houve um erro ao buscar os dados da tesouraria da retenção.");
		}

		$iLinhas = pg_num_rows($rsLancamentoCorrente);
		for ($i = 0; $i < $iLinhas; $i++) {
			$aTesouraria[] = db_utils::fieldsMemory($rsLancamentoCorrente, $i);
		}

		return $aTesouraria;
	}
}
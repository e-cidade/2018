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
 * Class ManutencaoTesouraria
 */
class ManutencaoTesouraria {

	/**
	 * Remove todos os registros da tesouratia conforme parâmetros passados.
	 * @param $iId
	 * @param $sData
	 * @param $iAutent
	 *
	 * @throws BusinessException
	 * @throws DBException
	 * @throws ParameterException
	 */
	public static function excluirTesouraria($iId, $sData, $iAutent) {

		if (empty($iId) || empty($sData) || empty($iAutent)) {

			$sErro = "Parâmetros id, data e autenticadora são de preenchimentos obirgatórios para exclusão da tesouraria.";
			throw new ParameterException($sErro);
		}

		$oDaoCorrente   = new cl_corrente();
		$sSqlCorrente = $oDaoCorrente->sql_query_file($iId, $sData, $iAutent);
		$rsCorrente   = db_query($sSqlCorrente);
		if (!$rsCorrente) {
			throw new DBException("Houve um erro ao buscar o registro da tesouraria.");
		}

		if (pg_num_rows($rsCorrente) != 1) {
			throw new BusinessException("Registro da tesouraria não encontrado.");
		}

		$oDaoCorEmp = new cl_coremp();
		$oDaoCorCla = new cl_corcla();
		$oDaoCorLanc = new cl_corlanc();
		$oDaoCorConf = new cl_corconf();
		$oDaoCorHist = new cl_corhist();
		$oDaoCorNump = new cl_cornump();
		$oDaoCorAutent = new cl_corautent();
		$oDaoCorrenteId = new cl_correnteid();
		$oDaoConciliaCor = new cl_conciliacor();
		$oDaoCorPlacaixa = new cl_corplacaixa();
		$oDaoCorEmpAgeMov = new cl_corempagemov();
		$oDaoSlipCorrente = new cl_slipcorrente();
		$oDaoCorNumpDesconto = new cl_cornumpdesconto();
		$oDaoCorGrupoCorrente = new cl_corgrupocorrente();
		$oDaoConlancamCorrente = new cl_conlancamcorrente();
		$oDaoConciliApendCorrente = new cl_conciliapendcorrente();
		$oDaoRetencaoCorGrupoCorrente = new cl_retencaocorgrupocorrente();
		$oDaoConlancamCorGrupoCorrente = new cl_conlancamcorgrupocorrente();

		$sWhereCorrente = "(k12_id, k12_data, k12_autent) = ({$iId}, '{$sData}', {$iAutent})";
		$sWhereCorrenteId = "(k56_id, k56_data, k56_autent) = ({$iId}, '{$sData}', {$iAutent})";
		$sWhereConciliaCor = "(k84_id, k84_data, k84_autent) = ({$iId}, '{$sData}', {$iAutent})";
		$sWhereSlipCorrente = "(k112_id, k112_data, k112_autent) = ({$iId}, '{$sData}', {$iAutent})";
		$sWhereCorGrupoCorrente = "(k105_id, k105_data, k105_autent) = ({$iId}, '{$sData}', {$iAutent})";
		$sWhereConlancamCorrente = "(c86_id, c86_data, c86_autent) = ({$iId}, '{$sData}', {$iAutent})";
		$sWhereConciliaPendCorrente = "(k89_id, k89_data, k89_autent) = ({$iId}, '{$sData}', {$iAutent})";

		$sSqlCorGrupoCorrente = $oDaoCorGrupoCorrente->sql_query_file(null, "k105_sequencial", null, $sWhereCorGrupoCorrente);
		$sWhereRetencaoCorGrupoCorrente = "e47_corgrupocorrente in ($sSqlCorGrupoCorrente)";
		$sWhereConlancamCorGrupoCorrente = "c23_corgrupocorrente in ({$sSqlCorGrupoCorrente}) ";

		$sErroMensagem = "Erro ao excluir informações da autenticação.\n";

		$oDaoConciliaCor->excluir(null, $sWhereConciliaCor);
		if ($oDaoConciliaCor->erro_status == 0) {

			$sErroMensagem .= "[ET] - {$oDaoConciliaCor->erro_msg}";
			throw new DBException($sErroMensagem);
		}

		$oDaoConciliApendCorrente->excluir(null, $sWhereConciliaPendCorrente);
		if ($oDaoConciliApendCorrente->erro_status == 0) {

			$sErroMensagem .= "[ET] - {$oDaoConciliApendCorrente->erro_msg}";
			throw new DBException($sErroMensagem);
		}

		$oDaoConlancamCorrente->excluir(null, $sWhereConlancamCorrente);
		if ($oDaoConlancamCorrente->erro_status == 0) {

			$sErroMensagem .= "[ET] - {$oDaoConlancamCorrente->erro_msg}";
			throw new DBException($sErroMensagem);
		}

		$oDaoCorCla->excluir($iId, $sData, $iAutent);
		if ($oDaoCorCla->erro_status == 0) {

			$sErroMensagem .= "[ET] - {$oDaoCorCla->erro_msg}";
			throw new DBException($sErroMensagem);
		}

		$oDaoCorEmp->excluir($iId, $sData, $iAutent);
		if ($oDaoCorEmp->erro_status == 0) {

			$sErroMensagem .= "[ET] - {$oDaoCorEmp->erro_msg}";
			throw new DBException($sErroMensagem);
		}

		$oDaoCorLanc->excluir($iId, $sData, $iAutent);
		if ($oDaoCorLanc->erro_status == 0) {

			$sErroMensagem .= "[ET] - {$oDaoCorLanc->erro_msg}";
			throw new DBException($sErroMensagem);
		}

		$oDaoCorConf->excluir($iId, $sData, $iAutent);
		if ($oDaoCorConf->erro_status == 0) {

			$sErroMensagem .= "[ET] - " . pg_last_error();
			throw new DBException($sErroMensagem);
		}

		$oDaoCorEmpAgeMov->excluir(null, $sWhereCorrente);
		if ($oDaoCorEmpAgeMov->erro_status == 0) {

			$sErroMensagem .= "[ET] - {$oDaoCorEmpAgeMov->erro_msg}";
			throw new DBException($sErroMensagem);
		}

		$oDaoRetencaoCorGrupoCorrente->excluir(null, $sWhereRetencaoCorGrupoCorrente);
		if ($oDaoRetencaoCorGrupoCorrente->erro_status == 0) {

			$sErroMensagem .= "[ET] - {$oDaoRetencaoCorGrupoCorrente->erro_msg}";
			throw new DBException($sErroMensagem);
		}

		$oDaoConlancamCorGrupoCorrente->excluir(null, $sWhereConlancamCorGrupoCorrente);
		if ($oDaoConlancamCorGrupoCorrente->erro_status == 0) {

			$sErroMensagem .= "[ET] - {$oDaoConlancamCorGrupoCorrente->erro_msg}";
			throw new DBException($sErroMensagem);
		}

		$oDaoCorGrupoCorrente->excluir(null, $sWhereCorGrupoCorrente);
		if ($oDaoCorGrupoCorrente->erro_status == 0) {

			$sErroMensagem .= "[ET] - {$oDaoCorGrupoCorrente->erro_msg}";
			throw new DBException($sErroMensagem);
		}

		$oDaoCorHist->excluir($iId, $sData, $iAutent);
		if ($oDaoCorHist->erro_status == 0) {

			$sErroMensagem .= "[ET] - {$oDaoCorHist->erro_msg}";
			throw new DBException($sErroMensagem);
		}

		$oDaoCorNump->excluir($iId, $sData, $iAutent);
		if ($oDaoCorNump->erro_status == 0) {

			$sErroMensagem .= "[ET] - {$oDaoCorNump->erro_msg}";
			throw new DBException($sErroMensagem);
		}

		$oDaoCorNumpDesconto->excluir($iId, $sData, $iAutent);
		if ($oDaoCorNumpDesconto->erro_status == 0) {

			$sErroMensagem .= "[ET] - {$oDaoCorNumpDesconto->erro_msg}";
			throw new DBException($sErroMensagem);
		}

		$oDaoCorPlacaixa->excluir($iId, $sData, $iAutent);
		if ($oDaoCorPlacaixa->erro_status == 0) {

			$sErroMensagem .= "[ET] - {$oDaoCorPlacaixa->erro_msg}";
			throw new DBException($sErroMensagem);
		}

		$oDaoCorrenteId->excluir(null, $sWhereCorrenteId);
		if ($oDaoCorrenteId->erro_status == 0) {

			$sErroMensagem .= "[ET] - {$oDaoCorrenteId->erro_msg}";
			throw new DBException($sErroMensagem);
		}

		$oDaoSlipCorrente->excluir(null, $sWhereSlipCorrente);
		if ($oDaoSlipCorrente->erro_status == 0) {

			$sErroMensagem .= "[ET] - {$oDaoSlipCorrente->erro_msg}";
			throw new DBException($sErroMensagem);
		}

		$oDaoCorAutent->excluir($iId, $sData, $iAutent);
		if ($oDaoCorAutent->erro_status == 0) {

			$sErroMensagem .= "[ET] - {$oDaoCorAutent->erro_msg}";
			throw new DBException($sErroMensagem);
		}

		$oDaoCorrente->excluir($iId, $sData, $iAutent);
		if ($oDaoCorrente->erro_status == 0) {

			$sErroMensagem .= "[ET] - {$oDaoCorrente->erro_msg}";
			throw new DBException($sErroMensagem);
		}
	}
}
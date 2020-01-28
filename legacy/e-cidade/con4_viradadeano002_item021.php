<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

$oDaoDbSysArquivo                = db_utils::getDao("db_sysarquivo");
$oDaoCissqn                      = db_utils::getDao("cissqn");
$oDaoParIssqn                    = db_utils::getDao("parissqn");
$oDaoTipCalc                     = db_utils::getDao("tipcalc");
$oDaoArreTipo                    = db_utils::getDao("arretipo");
$oDaoCadVenc                     = db_utils::getDao("cadvenc");
$oDaoCadVencDesc                 = db_utils::getDao("cadvencdesc");
$oDaoCadCalc                     = db_utils::getDao("cadcalc");
$oDaoTipCalcExe                  = db_utils::getDao("tipcalcexe");
$oDaoIssConfiguracaoGrupoServico = db_utils::getDao("issconfiguracaogruposervico");
$oDaoConfVencISSQNVariavel       = db_utils::getDao("confvencissqnvariavel");

db_atutermometro(1, 2, 'termometroitem', 1, $sMensagemTermometroItem);

if ($sqlerro == false) {

	try {

		/*
		 * BUSCA PERCENTUAL PADRAO
		 */
	  $sSqlPercentual     = $oDaoCissqn->sql_query_file(null, "q04_perccorrepadrao", null, "q04_anousu = {$anoorigem}");
	  $rsSqlPercentual    = $oDaoCissqn->sql_record($sSqlPercentual);
		if ($oDaoCissqn->numrows == 0) {

	    $sMensagem = "Percentual para o exercício {$anoorigem} não encontrado!";
	    throw new Exception($sMensagem, 55);
		}

		$oPercentual = db_utils::fieldsMemory($rsSqlPercentual, 0);

		/*
		 *  ISSQN FIXO
		 */
		$sWhere                  = "q81_tipo = 1 and q85_var is false";
		$sSqlQuantVencAtualFixo  = $oDaoTipCalc->sql_query_virada_issqn(null, "count(distinct q83_codven)", null, $sWhere);
		$rsSqlQuantVencAtualFixo = $oDaoTipCalc->sql_record($sSqlQuantVencAtualFixo);

	  if ($oDaoTipCalc->numrows != 1) {

	    $sMensagem = "Quantidade de vencimentos do fixo diferente de 1!  \\n\\nContate suporte!";
	    throw new Exception($sMensagem, 69);
	  }

		/*
		 * VENCIMENTO ATUAL FIXO
		 */
	  $sWhere             = "q81_tipo = 1 and q85_var is false";
	  $sSqlVencAtualFixo  = $oDaoTipCalc->sql_query_virada_issqn(null, "distinct q83_codven", null, $sWhere);
	  $rsSqlVencAtualFixo = $oDaoTipCalc->sql_record($sSqlVencAtualFixo);
	  if ($oDaoTipCalc->numrows == 0) {

	    $sMensagem = "Nenhum registro encontrado para o vencimento atual fixo!";
	    throw new Exception($sMensagem, 69);
	  }

	  $oVencAtualFixo = db_utils::fieldsMemory($rsSqlVencAtualFixo, 0);

		/*
		 * TIPO ATUAL FIXO
		 */
	  $sWhere             = "q81_tipo = 1 and q85_var is false";
	  $sSqlTipoAtualFixo  = $oDaoTipCalc->sql_query_virada_issqn(null, "distinct q92_tipo", null, $sWhere);
	  $rsSqlTipoAtualFixo = $oDaoTipCalc->sql_record($sSqlTipoAtualFixo);
	  if ($oDaoTipCalc->numrows == 0) {

	    $sMensagem = "Nenhum registro encontrado para o tipo atual fixo!";
	    throw new Exception($sMensagem, 69);
	  }

	  $oTipoAtualFixo = db_utils::fieldsMemory($rsSqlTipoAtualFixo, 0);

		/*
		 * VISTORIAS LOCALIZACAO
		 */
	  $sWhere                     = "q81_tipo = 3";
	  $sSqlQuantVencAtualVistLoc  = $oDaoTipCalc->sql_query_virada_issqn(null, "count(distinct q83_codven)", null, $sWhere);
	  $rsSqlQuantVencAtualVistLoc = $oDaoTipCalc->sql_record($sSqlQuantVencAtualVistLoc);
	  if ($oDaoTipCalc->numrows != 1) {

	    $sMensagem = "Nenhum registro encontrado para o tipo de calculo vistorias localização!";
	    throw new Exception($sMensagem, 69);
	  }

		/*
		 * VENCIMENTO ATUAL VISTLOC
		 */
	  $sWhere                = "q81_tipo = 3";
	  $sSqlVencAtualVistLoc  = $oDaoTipCalc->sql_query_virada_issqn(null, "distinct q83_codven", null, $sWhere);
	  $rsSqlVencAtualVistLoc = $oDaoTipCalc->sql_record($sSqlVencAtualVistLoc);
	  if ($oDaoTipCalc->numrows == 0) {

	    $sMensagem = "Nenhum registro encontrado para o tipo de calculo vistorias localização!";
	    throw new Exception($sMensagem, 69);
	  }

	  $oVencAtualVistLoc = db_utils::fieldsMemory($rsSqlVencAtualVistLoc, 0);

		/*
		 * TIPO ATUAL VISTLOC
		 */
	  $sWhere                = "q81_tipo = 3";
	  $sSqlTipoAtualVistLoc  = $oDaoTipCalc->sql_query_virada_issqn(null, "distinct q92_tipo", null, $sWhere);
	  $rsSqlTipoAtualVistLoc = $oDaoTipCalc->sql_record($sSqlTipoAtualVistLoc);
	  if ($oDaoTipCalc->numrows == 0) {

	    $sMensagem = "Nenhum registro encontrado para o tipo de calculo vistorias localização!";
	    throw new Exception($sMensagem, 69);
	  }

	  $oTipoAtualVistLoc = db_utils::fieldsMemory($rsSqlTipoAtualVistLoc, 0);

		/*
		 * VENCIMENTO ATUAL VISTORIAS SANITARIO
		 */
	  $sWhere                 = "q81_tipo = 6";
	  $sSqlVencAtualVistSani  = $oDaoTipCalc->sql_query_virada_issqn(null, "distinct q83_codven", null, $sWhere);
	  $rsSqlVencAtualVistSani = $oDaoTipCalc->sql_record($sSqlVencAtualVistSani);
	  if ($oDaoTipCalc->numrows == 0) {

	    $sMensagem = "Nenhum registro encontrado para o tipo de calculo vistorias sanitário!";
	    throw new Exception($sMensagem, 69);
	  }

	  $oVencAtualVistSani = db_utils::fieldsMemory($rsSqlVencAtualVistSani, 0);

		/*
		 * TIPO ATUAL VISTORIAS SANITARIO
		 */
	  $sWhere                 = "q81_tipo = 6";
	  $sSqlTipoAtualVistSani  = $oDaoTipCalc->sql_query_virada_issqn(null, "distinct q92_tipo", null, $sWhere);
	  $rsSqlTipoAtualVistSani = $oDaoTipCalc->sql_record($sSqlTipoAtualVistSani);
	  if ($oDaoTipCalc->numrows == 0) {

	    $sMensagem = "Nenhum registro encontrado para o tipo de calculo vistorias sanitário!";
	    throw new Exception($sMensagem, 69);
	  }

	  $oTipoAtualVistSani = db_utils::fieldsMemory($rsSqlTipoAtualVistSani, 0);

		/*
		 * VENCIMENTO ATUAL ALVARA LOCALIZACAO
		 */
	  $sWhere               = "q81_tipo = 4";
	  $sSqlVencAtualAlvLoc  = $oDaoTipCalc->sql_query_virada_issqn(null, "distinct q83_codven", null, $sWhere);
	  $rsSqlVencAtualAlvLoc = $oDaoTipCalc->sql_record($sSqlVencAtualAlvLoc);
	  if ($oDaoTipCalc->numrows == 0) {

	    $sMensagem = "Nenhum registro encontrado para o tipo de calculo alvará!";
	    throw new Exception($sMensagem, 69);
	  }

	  $oVencAtualAlvLoc = db_utils::fieldsMemory($rsSqlVencAtualAlvLoc, 0);

		/*
		 * VENCIMENTO ATUAL ALVARA SANITARIO
		 */
	  $sWhere                = "q81_tipo = 2";
	  $sSqlVencAtualAlvSani  = $oDaoTipCalc->sql_query_virada_issqn(null, "distinct q83_codven", null, $sWhere);
	  $rsSqlVencAtualAlvSani = $oDaoTipCalc->sql_record($sSqlVencAtualAlvSani);
	  if ($oDaoTipCalc->numrows == 0) {

	    $sMensagem = "Nenhum registro encontrado para o tipo de calculo sanitário!";
	    throw new Exception($sMensagem, 69);
	  }

    $oVencAtualAlvSani = db_utils::fieldsMemory($rsSqlVencAtualAlvSani, 0);

		/*
		 * QUANTIDADES VENCIMENTO ATUAL TAXAS
		 */
	  $sWhere                     = "q81_tipo = 5";
	  $sSqlQuantVencAtualVistLoc  = $oDaoTipCalc->sql_query_virada_issqn(null, "count(distinct q83_codven)", null, $sWhere);
	  $rsSqlQuantVencAtualVistLoc = $oDaoTipCalc->sql_record($sSqlQuantVencAtualVistLoc);
	  if ($oDaoTipCalc->numrows != 1) {

	    $sMensagem = "Nenhum registro encontrado para o tipo de calculo taxas!";
	    throw new Exception($sMensagem, 69);
	  }

		/*
		 * VENCIMENTO ATUAL TAXAS
		 */
	  $sWhere              = "q81_tipo = 5";
	  $sSqlVencAtualTaxas  = $oDaoTipCalc->sql_query_virada_issqn(null, "distinct q83_codven", null, $sWhere);
	  $rsSqlVencAtualTaxas = $oDaoTipCalc->sql_record($sSqlVencAtualTaxas);
	  if ($oDaoTipCalc->numrows == 0) {

	    $sMensagem = "Nenhum registro encontrado para o tipo de calculo taxas!";
	    throw new Exception($sMensagem, 69);
	  }

    $oVencAtualTaxas = db_utils::fieldsMemory($rsSqlVencAtualTaxas, 0);

		/*
		 * TIPO ATUAL TAXAS
		 */
	  $sWhere              = "q81_tipo = 5";
	  $sSqlTipoAtualTaxas  = $oDaoTipCalc->sql_query_virada_issqn(null, "distinct q92_tipo as k00_tipotaxas", null, $sWhere);
	  $rsSqlTipoAtualTaxas = $oDaoTipCalc->sql_record($sSqlTipoAtualTaxas);
	  if ($oDaoTipCalc->numrows == 0) {

	    $sMensagem = "Nenhum registro encontrado para o tipo de calculo taxas!";
	    throw new Exception($sMensagem, 69);
	  }

    $oTipoAtualTaxas = db_utils::fieldsMemory($rsSqlTipoAtualTaxas, 0);

		/*
		 * QUANTIDADE VENCIMENTO ATUAL VARIAVEL
		 */
	  $sWhere                      = "q81_tipo = 1 and q85_var is true";
	  $sSqlQuantVencAtualVariavel  = $oDaoTipCalc->sql_query_virada_issqn(null, "count(distinct q83_codven)", null, $sWhere);
	  $rsSqlQuantVencAtualVariavel = $oDaoTipCalc->sql_record($sSqlQuantVencAtualVariavel);
	  if ($oDaoTipCalc->numrows == 0) {

	    $sMensagem = "Nenhum registro encontrado para quantidade vencimento atual!";
	    throw new Exception($sMensagem, 69);
	  }

		/*
		 * VENCIMENTO ATUAL VARIAVEL
		 */
	  $sWhere                 = "q81_tipo = 1 and q85_var is true";
	  $sSqlVencAtualVariavel  = $oDaoTipCalc->sql_query_virada_issqn(null, "distinct q83_codven", null, $sWhere);
	  $rsSqlVencAtualVariavel = $oDaoTipCalc->sql_record($sSqlVencAtualVariavel);
	  if ($oDaoTipCalc->numrows == 0) {

	    $sMensagem = "Nenhum registro encontrado para o vencimento atual variavel!";
	    throw new Exception($sMensagem, 69);
	  }

    $oVencAtualVariavel = db_utils::fieldsMemory($rsSqlVencAtualVariavel, 0);

		/*
		 * TIPO ATUAL VARIAVEL
		 */
	  $sWhere                 = "q81_tipo = 1 and q85_var is true";
	  $sSqlTipoAtualVariavel  = $oDaoTipCalc->sql_query_virada_issqn(null, "distinct q92_tipo as k00_tipovariavel", null, $sWhere);
	  $rsSqlTipoAtualVariavel = $oDaoTipCalc->sql_record($sSqlTipoAtualVariavel);
	  if ($oDaoTipCalc->numrows == 0) {

	    $sMensagem = "Nenhum registro encontrado para o tipo atual variavel!";
	    throw new Exception($sMensagem, 69);
	  }

    $oTipoAtualVariavel = db_utils::fieldsMemory($rsSqlTipoAtualVariavel, 0);

		$sSqlArretipo     = " select (max(k00_tipo) + 1) as k00_tipo from arretipo ";
		$rsSqlArretipo    = db_query($sSqlArretipo);
		$iNumRowsArretipo = pg_num_rows($rsSqlArretipo);
		if ($iNumRowsArretipo == 0) {

	    $sMensagem = "Nenhum registro encontrado na arretipo!";
	    throw new Exception($sMensagem, 82);
		}

	  $oArretipo = db_utils::fieldsMemory($rsSqlArretipo, 0);

		/*
		 * PESQUISA NA TABELA arretipo PELO TIPO ATUALFIXO
		 */
		$sSqlArreTipoTipoAtualFixo  = $oDaoArreTipo->sql_query_file($oTipoAtualFixo->q92_tipo);
		$rsSqlArreTipoTipoAtualFixo = $oDaoArreTipo->sql_record($sSqlArreTipoTipoAtualFixo);
		if ($oDaoArreTipo->numrows == 0) {

	    $sMensagem = "Nenhum registro encontrado na arretipo para k00_tipo = {$oTipoAtualFixo->q92_tipo}!";
	    throw new Exception($sMensagem, 82);
		}

    $oArreTipoTipoAtualFixo = db_utils::fieldsMemory($rsSqlArreTipoTipoAtualFixo, 0);

		/*
		 * INCLUIR REGISTROS NA TABELA arretipo PARA ISSQN FIXO EXERCICIO NOVO
		 */
	  $oDaoArreTipo->k00_codbco              = $oArreTipoTipoAtualFixo->k00_codbco;
	  $oDaoArreTipo->k00_codage              = $oArreTipoTipoAtualFixo->k00_codage;
	  $oDaoArreTipo->k00_descr               = "ISSQN FIXO {$anodestino}";
	  $oDaoArreTipo->k00_emrec               = ($oArreTipoTipoAtualFixo->k00_emrec=='t'?'true':'false');
	  $oDaoArreTipo->k00_agnum               = ($oArreTipoTipoAtualFixo->k00_agnum=='t'?'true':'false');
	  $oDaoArreTipo->k00_agpar               = ($oArreTipoTipoAtualFixo->k00_agpar=='t'?'true':'false');
	  $oDaoArreTipo->k00_msguni              = $oArreTipoTipoAtualFixo->k00_msguni;
	  $oDaoArreTipo->k00_msguni2             = $oArreTipoTipoAtualFixo->k00_msguni2;
	  $oDaoArreTipo->k00_msgparc             = $oArreTipoTipoAtualFixo->k00_msgparc;
	  $oDaoArreTipo->k00_msgparc2            = $oArreTipoTipoAtualFixo->k00_msgparc2;
	  $oDaoArreTipo->k00_msgparcvenc         = $oArreTipoTipoAtualFixo->k00_msgparcvenc;
	  $oDaoArreTipo->k00_msgparcvenc2        = $oArreTipoTipoAtualFixo->k00_msgparcvenc2;
	  $oDaoArreTipo->k00_msgrecibo           = $oArreTipoTipoAtualFixo->k00_msgrecibo;
	  $oDaoArreTipo->k00_tercdigcarneunica   = $oArreTipoTipoAtualFixo->k00_tercdigcarneunica;
	  $oDaoArreTipo->k00_tercdigcarnenormal  = $oArreTipoTipoAtualFixo->k00_tercdigcarnenormal;
	  $oDaoArreTipo->k00_tercdigrecunica     = $oArreTipoTipoAtualFixo->k00_tercdigrecunica;
	  $oDaoArreTipo->k00_tercdigrecnormal    = $oArreTipoTipoAtualFixo->k00_tercdigrecnormal;
	  $oDaoArreTipo->k00_txban               = $oArreTipoTipoAtualFixo->k00_txban;
	  $oDaoArreTipo->k00_rectx               = $oArreTipoTipoAtualFixo->k00_rectx;
	  $oDaoArreTipo->codmodelo               = $oArreTipoTipoAtualFixo->codmodelo;
	  $oDaoArreTipo->k00_impval              = ($oArreTipoTipoAtualFixo->k00_impval=='t'?'true':'false');
	  $oDaoArreTipo->k00_vlrmin              = $oArreTipoTipoAtualFixo->k00_vlrmin;
	  $oDaoArreTipo->k03_tipo                = $oArreTipoTipoAtualFixo->k03_tipo;
	  $oDaoArreTipo->k00_marcado             = $oArreTipoTipoAtualFixo->k00_marcado;
	  $oDaoArreTipo->k00_hist1               = $oArreTipoTipoAtualFixo->k00_hist1;
	  $oDaoArreTipo->k00_hist2               = $oArreTipoTipoAtualFixo->k00_hist2;
	  $oDaoArreTipo->k00_hist3               = $oArreTipoTipoAtualFixo->k00_hist3;
	  $oDaoArreTipo->k00_hist4               = $oArreTipoTipoAtualFixo->k00_hist4;
	  $oDaoArreTipo->k00_hist5               = $oArreTipoTipoAtualFixo->k00_hist5;
	  $oDaoArreTipo->k00_hist6               = $oArreTipoTipoAtualFixo->k00_hist6;
	  $oDaoArreTipo->k00_hist7               = $oArreTipoTipoAtualFixo->k00_hist7;
	  $oDaoArreTipo->k00_hist8               = $oArreTipoTipoAtualFixo->k00_hist8;
	  $oDaoArreTipo->k00_tipoagrup           = $oArreTipoTipoAtualFixo->k00_tipoagrup;
	  $oDaoArreTipo->k00_recibodbpref        = $oArreTipoTipoAtualFixo->k00_recibodbpref;
	  $oDaoArreTipo->k00_instit              = $oArreTipoTipoAtualFixo->k00_instit;
	  $oDaoArreTipo->k00_formemissao         = $oArreTipoTipoAtualFixo->k00_formemissao;
	  $oDaoArreTipo->k00_receitacredito      = $oArreTipoTipoAtualFixo->k00_receitacredito;
	  $oDaoArreTipo->incluir(null);
	  if ($oDaoArreTipo->erro_status == 0) {
	    throw new Exception($oDaoArreTipo->erro_msg, 82);
	  }

	  $k00_tipofixo = $oDaoArreTipo->k00_tipo;

	  $sSqlArreTipoTipoAtualVistLoc  = $oDaoArreTipo->sql_query_file($oTipoAtualVistLoc->q92_tipo);
	  $rsSqlArreTipoTipoAtualVistLoc = $oDaoArreTipo->sql_record($sSqlArreTipoTipoAtualVistLoc);
	  if ($oDaoArreTipo->numrows == 0) {

	    $sMensagem = "Nenhum registro encontrado na arretipo para k00_tipo = {$oTipoAtualVistLoc->q92_tipo}!";
	    throw new Exception($sMensagem, 82);
	  }

    $oArreTipoTipoAtualVistLoc = db_utils::fieldsMemory($rsSqlArreTipoTipoAtualVistLoc, 0);

    /*
     * INCLUIR REGISTROS NA TABELA arretipo PARA VISTORIA LOCALIZACAO EXERCICIO NOVO
     */
	  $oDaoArreTipo->k00_codbco              = $oArreTipoTipoAtualVistLoc->k00_codbco;
	  $oDaoArreTipo->k00_codage              = $oArreTipoTipoAtualVistLoc->k00_codage;
	  $oDaoArreTipo->k00_descr               = "VISTORIA LOCALIZACAO {$anodestino}";
	  $oDaoArreTipo->k00_emrec               = ($oArreTipoTipoAtualVistLoc->k00_emrec=='t'?'true':'false');
	  $oDaoArreTipo->k00_agnum               = ($oArreTipoTipoAtualVistLoc->k00_agnum=='t'?'true':'false');
	  $oDaoArreTipo->k00_agpar               = ($oArreTipoTipoAtualVistLoc->k00_agpar=='t'?'true':'false');
	  $oDaoArreTipo->k00_msguni              = $oArreTipoTipoAtualVistLoc->k00_msguni;
	  $oDaoArreTipo->k00_msguni2             = $oArreTipoTipoAtualVistLoc->k00_msguni2;
	  $oDaoArreTipo->k00_msgparc             = $oArreTipoTipoAtualVistLoc->k00_msgparc;
	  $oDaoArreTipo->k00_msgparc2            = $oArreTipoTipoAtualVistLoc->k00_msgparc2;
	  $oDaoArreTipo->k00_msgparcvenc         = $oArreTipoTipoAtualVistLoc->k00_msgparcvenc;
	  $oDaoArreTipo->k00_msgparcvenc2        = $oArreTipoTipoAtualVistLoc->k00_msgparcvenc2;
	  $oDaoArreTipo->k00_msgrecibo           = $oArreTipoTipoAtualVistLoc->k00_msgrecibo;
	  $oDaoArreTipo->k00_tercdigcarneunica   = $oArreTipoTipoAtualVistLoc->k00_tercdigcarneunica;
	  $oDaoArreTipo->k00_tercdigcarnenormal  = $oArreTipoTipoAtualVistLoc->k00_tercdigcarnenormal;
	  $oDaoArreTipo->k00_tercdigrecunica     = $oArreTipoTipoAtualVistLoc->k00_tercdigrecunica;
	  $oDaoArreTipo->k00_tercdigrecnormal    = $oArreTipoTipoAtualVistLoc->k00_tercdigrecnormal;
	  $oDaoArreTipo->k00_txban               = $oArreTipoTipoAtualVistLoc->k00_txban;
	  $oDaoArreTipo->k00_rectx               = $oArreTipoTipoAtualVistLoc->k00_rectx;
	  $oDaoArreTipo->codmodelo               = $oArreTipoTipoAtualVistLoc->codmodelo;
	  $oDaoArreTipo->k00_impval              = ($oArreTipoTipoAtualVistLoc->k00_impval=='t'?'true':'false');
	  $oDaoArreTipo->k00_vlrmin              = $oArreTipoTipoAtualVistLoc->k00_vlrmin;
	  $oDaoArreTipo->k03_tipo                = $oArreTipoTipoAtualVistLoc->k03_tipo;
	  $oDaoArreTipo->k00_marcado             = $oArreTipoTipoAtualVistLoc->k00_marcado;
	  $oDaoArreTipo->k00_hist1               = $oArreTipoTipoAtualVistLoc->k00_hist1;
	  $oDaoArreTipo->k00_hist2               = $oArreTipoTipoAtualVistLoc->k00_hist2;
	  $oDaoArreTipo->k00_hist3               = $oArreTipoTipoAtualVistLoc->k00_hist3;
	  $oDaoArreTipo->k00_hist4               = $oArreTipoTipoAtualVistLoc->k00_hist4;
	  $oDaoArreTipo->k00_hist5               = $oArreTipoTipoAtualVistLoc->k00_hist5;
	  $oDaoArreTipo->k00_hist6               = $oArreTipoTipoAtualVistLoc->k00_hist6;
	  $oDaoArreTipo->k00_hist7               = $oArreTipoTipoAtualVistLoc->k00_hist7;
	  $oDaoArreTipo->k00_hist8               = $oArreTipoTipoAtualVistLoc->k00_hist8;
	  $oDaoArreTipo->k00_tipoagrup           = $oArreTipoTipoAtualVistLoc->k00_tipoagrup;
	  $oDaoArreTipo->k00_recibodbpref        = $oArreTipoTipoAtualVistLoc->k00_recibodbpref;
	  $oDaoArreTipo->k00_instit              = $oArreTipoTipoAtualVistLoc->k00_instit;
	  $oDaoArreTipo->k00_formemissao         = $oArreTipoTipoAtualVistLoc->k00_formemissao;
	  $oDaoArreTipo->incluir(null);
	  if ($oDaoArreTipo->erro_status == 0) {
	    throw new Exception($oDaoArreTipo->erro_msg, 82);
	  }
		$iArreTipoTipoNovoVistLocalizacao = $oDaoArreTipo->k00_tipo;

	  $sSqlArreTipoTipoAtualVistSani  = $oDaoArreTipo->sql_query_file($oTipoAtualVistSani->q92_tipo);
	  $rsSqlArreTipoTipoAtualVistSani = $oDaoArreTipo->sql_record($sSqlArreTipoTipoAtualVistSani);
	  if ($oDaoArreTipo->numrows == 0) {

	    $sMensagem = "Nenhum registro encontrado na arretipo para k00_tipo = {$oTipoAtualVistSani->q92_tipo}!";
	    throw new Exception($sMensagem, 82);
	  }

    $oArreTipoTipoAtualVistSani = db_utils::fieldsMemory($rsSqlArreTipoTipoAtualVistSani, 0);

    /*
     * INCLUIR REGISTROS NA TABELA arretipo PARA VISTORIA SANITARIO EXERCICIO NOVO
     */
	  $oDaoArreTipo->k00_codbco              = $oArreTipoTipoAtualVistSani->k00_codbco;
	  $oDaoArreTipo->k00_codage              = $oArreTipoTipoAtualVistSani->k00_codage;
	  $oDaoArreTipo->k00_descr               = "VISTORIA SANITARIO {$anodestino}";
	  $oDaoArreTipo->k00_emrec               = ($oArreTipoTipoAtualVistSani->k00_emrec=='t'?'true':'false');
	  $oDaoArreTipo->k00_agnum               = ($oArreTipoTipoAtualVistSani->k00_agnum=='t'?'true':'false');
	  $oDaoArreTipo->k00_agpar               = ($oArreTipoTipoAtualVistSani->k00_agpar=='t'?'true':'false');
	  $oDaoArreTipo->k00_msguni              = $oArreTipoTipoAtualVistSani->k00_msguni;
	  $oDaoArreTipo->k00_msguni2             = $oArreTipoTipoAtualVistSani->k00_msguni2;
	  $oDaoArreTipo->k00_msgparc             = $oArreTipoTipoAtualVistSani->k00_msgparc;
	  $oDaoArreTipo->k00_msgparc2            = $oArreTipoTipoAtualVistSani->k00_msgparc2;
	  $oDaoArreTipo->k00_msgparcvenc         = $oArreTipoTipoAtualVistSani->k00_msgparcvenc;
	  $oDaoArreTipo->k00_msgparcvenc2        = $oArreTipoTipoAtualVistSani->k00_msgparcvenc2;
	  $oDaoArreTipo->k00_msgrecibo           = $oArreTipoTipoAtualVistSani->k00_msgrecibo;
	  $oDaoArreTipo->k00_tercdigcarneunica   = $oArreTipoTipoAtualVistSani->k00_tercdigcarneunica;
	  $oDaoArreTipo->k00_tercdigcarnenormal  = $oArreTipoTipoAtualVistSani->k00_tercdigcarnenormal;
	  $oDaoArreTipo->k00_tercdigrecunica     = $oArreTipoTipoAtualVistSani->k00_tercdigrecunica;
	  $oDaoArreTipo->k00_tercdigrecnormal    = $oArreTipoTipoAtualVistSani->k00_tercdigrecnormal;
	  $oDaoArreTipo->k00_txban               = $oArreTipoTipoAtualVistSani->k00_txban;
	  $oDaoArreTipo->k00_rectx               = $oArreTipoTipoAtualVistSani->k00_rectx;
	  $oDaoArreTipo->codmodelo               = $oArreTipoTipoAtualVistSani->codmodelo;
	  $oDaoArreTipo->k00_impval              = ($oArreTipoTipoAtualVistSani->k00_impval=='t'?'true':'false');
	  $oDaoArreTipo->k00_vlrmin              = $oArreTipoTipoAtualVistSani->k00_vlrmin;
	  $oDaoArreTipo->k03_tipo                = $oArreTipoTipoAtualVistSani->k03_tipo;
	  $oDaoArreTipo->k00_marcado             = $oArreTipoTipoAtualVistSani->k00_marcado;
	  $oDaoArreTipo->k00_hist1               = $oArreTipoTipoAtualVistSani->k00_hist1;
	  $oDaoArreTipo->k00_hist2               = $oArreTipoTipoAtualVistSani->k00_hist2;
	  $oDaoArreTipo->k00_hist3               = $oArreTipoTipoAtualVistSani->k00_hist3;
	  $oDaoArreTipo->k00_hist4               = $oArreTipoTipoAtualVistSani->k00_hist4;
	  $oDaoArreTipo->k00_hist5               = $oArreTipoTipoAtualVistSani->k00_hist5;
	  $oDaoArreTipo->k00_hist6               = $oArreTipoTipoAtualVistSani->k00_hist6;
	  $oDaoArreTipo->k00_hist7               = $oArreTipoTipoAtualVistSani->k00_hist7;
	  $oDaoArreTipo->k00_hist8               = $oArreTipoTipoAtualVistSani->k00_hist8;
	  $oDaoArreTipo->k00_tipoagrup           = $oArreTipoTipoAtualVistSani->k00_tipoagrup;
	  $oDaoArreTipo->k00_recibodbpref        = $oArreTipoTipoAtualVistSani->k00_recibodbpref;
	  $oDaoArreTipo->k00_instit              = $oArreTipoTipoAtualVistSani->k00_instit;
	  $oDaoArreTipo->k00_formemissao         = $oArreTipoTipoAtualVistSani->k00_formemissao;
	  $oDaoArreTipo->incluir(null);
	  if ($oDaoArreTipo->erro_status == 0) {
	    throw new Exception($oDaoArreTipo->erro_msg, 82);
	  }
		$iArreTipoTipoNovoVistSani = $oDaoArreTipo->k00_tipo;

		$oCodigoVencimento = new stdClass;

	  /*
	   * DEFINE CAMPOS DAS CONSULTAS NA TABELA cadvenc
	   */
	  $sCamposCadVenc                = "q82_parc,                                      ";
	  $sCamposCadVenc               .= "q82_venc + '1 year':: interval as q82_venc,    ";
	  $sCamposCadVenc               .= "q82_desc,                                      ";
	  $sCamposCadVenc               .= "q82_perc,                                      ";
	  $sCamposCadVenc               .= "q82_hist,                                      ";
	  $sCamposCadVenc               .= "q82_calculaparcvenc                            ";

	  $sWhere                        = "q92_codigo = {$oVencAtualFixo->q83_codven}";
		$sSqlTipoAtualFixoCadVencDesc  = $oDaoCadVencDesc->sql_query_file(null, "*", null, $sWhere);
		$rsSqlTipoAtualFixoCadVencDesc = $oDaoCadVencDesc->sql_record($sSqlTipoAtualFixoCadVencDesc);
		$iLinhasCadVencDesc = $oDaoCadVencDesc->numrows;
	  if ($iLinhasCadVencDesc == 0) {

	    $sMensagem = "Nenhum registro encontrado na cadvencdesc para q92_codigo = {$oVencAtualFixo->q83_codven}!";
	    throw new Exception($sMensagem, 54);
	  }

	  /*
	   * Inclui na tabela cadvencdesc para issqn fixo
	   */
	  for ($iInd = 0; $iInd < $iLinhasCadVencDesc; $iInd++) {

	  	$oCadVencDescTipoAtualFixo = db_utils::fieldsMemory($rsSqlTipoAtualFixoCadVencDesc, $iInd);

		  $oDaoCadVencDesc->q92_descr             = "ISSQN FIXO {$anodestino}";
		  $oDaoCadVencDesc->q92_tipo              = $k00_tipofixo;
		  $oDaoCadVencDesc->q92_hist              = $oCadVencDescTipoAtualFixo->q92_hist;
		  $oDaoCadVencDesc->q92_diasvcto          = $oCadVencDescTipoAtualFixo->q92_diasvcto;
		  $oDaoCadVencDesc->q92_vlrminimo         = (empty($oCadVencDescTipoAtualFixo->q92_vlrminimo)?'0':$oCadVencDescTipoAtualFixo->q92_vlrminimo);
		  $oDaoCadVencDesc->q92_formacalcparcvenc = $oCadVencDescTipoAtualFixo->q92_formacalcparcvenc;
		  $oDaoCadVencDesc->incluir(null);
		  if ($oDaoCadVencDesc->erro_status == 0) {
		    throw new Exception($oDaoCadVencDesc->erro_msg, 54);
		  }
	  }

		$oCodigoVencimento->issqnFixo = $oDaoCadVencDesc->q92_codigo;

	  $sWhere                    = "q82_codigo = {$oVencAtualFixo->q83_codven}  ";
	  $sSqlTipoAtualFixoCadVenc  = $oDaoCadVenc->sql_query_file(null, null, $sCamposCadVenc, null, $sWhere);
	  $rsSqlTipoAtualFixoCadVenc = $oDaoCadVenc->sql_record($sSqlTipoAtualFixoCadVenc);
	  $iLinhasCadVenc = $oDaoCadVenc->numrows;
	  if ($iLinhasCadVenc == 0) {

	    $sMensagem = "Nenhum registro encontrado na cadvenc para q82_codigo = {$oVencAtualFixo->q83_codven}!";
	    throw new Exception($sMensagem, 53);
	  }

	  /*
	   * Inclui na tabela cadvenc para issqn fixo
	   */
	  for ($xInd = 0; $xInd < $iLinhasCadVenc; $xInd++) {

	  	$oCadVencTipoAtualFixo = db_utils::fieldsMemory($rsSqlTipoAtualFixoCadVenc, $xInd);

		  $oDaoCadVenc->q82_codigo          = $oDaoCadVencDesc->q92_codigo;
		  $oDaoCadVenc->q82_parc            = $oCadVencTipoAtualFixo->q82_parc;
		  $oDaoCadVenc->q82_venc            = $oCadVencTipoAtualFixo->q82_venc;
		  $oDaoCadVenc->q82_desc            = $oCadVencTipoAtualFixo->q82_desc;
		  $oDaoCadVenc->q82_perc            = $oCadVencTipoAtualFixo->q82_perc;
		  $oDaoCadVenc->q82_hist            = $oCadVencTipoAtualFixo->q82_hist;
		  $oDaoCadVenc->q82_calculaparcvenc = ($oCadVencTipoAtualFixo->q82_calculaparcvenc=='t'?'true':'false');
		  $oDaoCadVenc->incluir($oDaoCadVenc->q82_codigo, $oDaoCadVenc->q82_parc);
		  if ($oDaoCadVenc->erro_status == 0) {
		    throw new Exception($oDaoCadVenc->erro_msg, 53);
		  }
	  }

	  $sWhere                            = "q92_codigo = {$oVencAtualVariavel->q83_codven}";
	  $sSqlVencAtualVariavelCadVencDesc  = $oDaoCadVencDesc->sql_query_file(null, "*", null, $sWhere);
	  $rsSqlVencAtualVariavelCadVencDesc = $oDaoCadVencDesc->sql_record($sSqlVencAtualVariavelCadVencDesc);
	  $iLinhasCadVencDesc = $oDaoCadVencDesc->numrows;
	  if ($iLinhasCadVencDesc == 0) {

	    $sMensagem = "Nenhum registro encontrado na cadvencdesc para q92_codigo = {$oVencAtualVariavel->q83_codven}!";
	    throw new Exception($sMensagem, 54);
	  }

	  /*
	   * Inclui na tabela cadvencdesc para issqn variavel
	   */
	  for ($iInd = 0; $iInd < $iLinhasCadVencDesc; $iInd++) {

	  	$oCadVencDescVencAtualVariavel = db_utils::fieldsMemory($rsSqlVencAtualVariavelCadVencDesc, $iInd);

		  $oDaoCadVencDesc->q92_descr             = "ISSQN VARIAVEL {$anodestino}";
		  $oDaoCadVencDesc->q92_tipo              = $oTipoAtualVariavel->k00_tipovariavel;
		  $oDaoCadVencDesc->q92_hist              = $oCadVencDescVencAtualVariavel->q92_hist;
		  $oDaoCadVencDesc->q92_diasvcto          = $oCadVencDescVencAtualVariavel->q92_diasvcto;
		  $oDaoCadVencDesc->q92_vlrminimo         = (empty($oCadVencDescVencAtualVariavel->q92_vlrminimo)?'0':$oCadVencDescVencAtualVariavel->q92_vlrminimo);
		  $oDaoCadVencDesc->q92_formacalcparcvenc = $oCadVencDescVencAtualVariavel->q92_formacalcparcvenc;
		  $oDaoCadVencDesc->incluir(null);
		  if ($oDaoCadVencDesc->erro_status == 0) {
		    throw new Exception($oDaoCadVencDesc->erro_msg, 54);
		  }
	  }

	  $oCodigoVencimento->issqnVariavelHist = $oCadVencDescVencAtualVariavel->q92_hist;
		$oCodigoVencimento->issqnVariavel     = $oDaoCadVencDesc->q92_codigo;

	  $sWhere                    = "q82_codigo = {$oVencAtualVariavel->q83_codven}";
	  $sSqlTipoAtualFixoCadVenc  = $oDaoCadVenc->sql_query_file(null, null, $sCamposCadVenc, null, $sWhere);
	  $rsSqlTipoAtualFixoCadVenc = $oDaoCadVenc->sql_record($sSqlTipoAtualFixoCadVenc);
	  $iLinhasCadVenc = $oDaoCadVenc->numrows;
	  if ($iLinhasCadVenc == 0) {

	    $sMensagem = "Nenhum registro encontrado na cadvenc para q82_codigo = {$oVencAtualVariavel->q83_codven}!";
	    throw new Exception($sMensagem, 53);
	  }

	  /*
	   * Inclui na tabela cadvenc para issqn variavel
	   */
	  for ($xInd = 0; $xInd < $iLinhasCadVenc; $xInd++) {

	  	$oCadVencTipoAtualFixo = db_utils::fieldsMemory($rsSqlTipoAtualFixoCadVenc, $xInd);

		  $oDaoCadVenc->q82_codigo          = $oDaoCadVencDesc->q92_codigo;
		  $oDaoCadVenc->q82_parc            = $oCadVencTipoAtualFixo->q82_parc;
		  $oDaoCadVenc->q82_venc            = $oCadVencTipoAtualFixo->q82_venc;
		  $oDaoCadVenc->q82_desc            = $oCadVencTipoAtualFixo->q82_desc;
		  $oDaoCadVenc->q82_perc            = $oCadVencTipoAtualFixo->q82_perc;
		  $oDaoCadVenc->q82_hist            = $oCadVencTipoAtualFixo->q82_hist;
		  $oDaoCadVenc->q82_calculaparcvenc = ($oCadVencTipoAtualFixo->q82_calculaparcvenc=='t'?'true':'false');
		  $oDaoCadVenc->incluir($oDaoCadVenc->q82_codigo, $oDaoCadVenc->q82_parc);
		  if ($oDaoCadVenc->erro_status == 0) {
		    throw new Exception($oDaoCadVenc->erro_msg, 53);
		  }
	  }

	  $sWhere                            = "q92_codigo = {$oVencAtualVistLoc->q83_codven}";
	  $sSqlVencAtualVariavelCadVencDesc  = $oDaoCadVencDesc->sql_query_file(null, "*", null, $sWhere);
	  $rsSqlVencAtualVariavelCadVencDesc = $oDaoCadVencDesc->sql_record($sSqlVencAtualVariavelCadVencDesc);
	  $iLinhasCadVencDesc = $oDaoCadVencDesc->numrows;
	  if ($iLinhasCadVencDesc == 0) {

	    $sMensagem = "Nenhum registro encontrado na cadvencdesc para q92_codigo = {$oVencAtualVistLoc->q83_codven}!";
	    throw new Exception($sMensagem, 54);
	  }

	  /*
	   * Inclui na tabela cadvencdesc para vistorias localizacao
	   */
	  for ($iInd = 0; $iInd < $iLinhasCadVencDesc; $iInd++) {

	    $oCadVencDescVencAtualVariavel = db_utils::fieldsMemory($rsSqlVencAtualVariavelCadVencDesc, $iInd);

	    $oDaoCadVencDesc->q92_descr             = "VISTORIAS LOCALIZACAO {$anodestino}";
	   	$oDaoCadVencDesc->q92_tipo 							= (empty($iArreTipoTipoNovoVistLocalizacao) ? $oTipoAtualVariavel->k00_tipovariavel : $iArreTipoTipoNovoVistLocalizacao );
	    $oDaoCadVencDesc->q92_hist              = $oCadVencDescVencAtualVariavel->q92_hist;
	    $oDaoCadVencDesc->q92_diasvcto          = $oCadVencDescVencAtualVariavel->q92_diasvcto;
	    $oDaoCadVencDesc->q92_vlrminimo         = (empty($oCadVencDescVencAtualVariavel->q92_vlrminimo)?'0':$oCadVencDescVencAtualVariavel->q92_vlrminimo);
	    $oDaoCadVencDesc->q92_formacalcparcvenc = $oCadVencDescVencAtualVariavel->q92_formacalcparcvenc;
	    $oDaoCadVencDesc->incluir(null);
	    if ($oDaoCadVencDesc->erro_status == 0) {
	      throw new Exception($oDaoCadVencDesc->erro_msg, 54);
	    }
	  }

		$oCodigoVencimento->vistoriaLocalizacao = $oDaoCadVencDesc->q92_codigo;

	  $sWhere                    = "q82_codigo = {$oVencAtualVistLoc->q83_codven}";
	  $sSqlVencAtualVistLoc  = $oDaoCadVenc->sql_query_file(null, null, $sCamposCadVenc, null, $sWhere);
	  $rsSqlVencAtualVistLoc = $oDaoCadVenc->sql_record($sSqlVencAtualVistLoc);
	  $iLinhasCadVenc = $oDaoCadVenc->numrows;
	  if ($iLinhasCadVenc == 0) {

	    $sMensagem = "Nenhum registro encontrado na cadvenc para q82_codigo = {$oVencAtualVistLoc->q83_codven}!";
	    throw new Exception($sMensagem, 53);
	  }

	  /*
	   * Inclui na tabela cadvenc para vistorias localizacao
	   */
	  for ($xInd = 0; $xInd < $iLinhasCadVenc; $xInd++) {

	    $oCadVencTipoAtualFixo = db_utils::fieldsMemory($rsSqlVencAtualVistLoc, $xInd);

	    $oDaoCadVenc->q82_codigo          = $oDaoCadVencDesc->q92_codigo;
	    $oDaoCadVenc->q82_parc            = $oCadVencTipoAtualFixo->q82_parc;
	    $oDaoCadVenc->q82_venc            = $oCadVencTipoAtualFixo->q82_venc;
	    $oDaoCadVenc->q82_desc            = $oCadVencTipoAtualFixo->q82_desc;
	    $oDaoCadVenc->q82_perc            = $oCadVencTipoAtualFixo->q82_perc;
	    $oDaoCadVenc->q82_hist            = $oCadVencTipoAtualFixo->q82_hist;
	    $oDaoCadVenc->q82_calculaparcvenc = ($oCadVencTipoAtualFixo->q82_calculaparcvenc=='t'?'true':'false');
	    $oDaoCadVenc->incluir($oDaoCadVenc->q82_codigo, $oDaoCadVenc->q82_parc);
	    if ($oDaoCadVenc->erro_status == 0) {
	      throw new Exception($oDaoCadVenc->erro_msg, 53);
	    }
	  }

	  $sWhere                            = "q92_codigo = {$oVencAtualVistSani->q83_codven}";
	  $sSqlVencAtualVistSaniCadVencDesc  = $oDaoCadVencDesc->sql_query_file(null, "*", null, $sWhere);
	  $rsSqlVencAtualVistSaniCadVencDesc = $oDaoCadVencDesc->sql_record($sSqlVencAtualVistSaniCadVencDesc);
	  $iLinhasCadVencDesc = $oDaoCadVencDesc->numrows;
	  if ($iLinhasCadVencDesc == 0) {

	    $sMensagem = "Nenhum registro encontrado na cadvencdesc para q92_codigo = {$oVencAtualVistSani->q83_codven}!";
	    throw new Exception($sMensagem, 54);
	  }

	  /*
	   * Inclui na tabela cadvencdesc para vistorias sanitario
	   */
	  for ($iInd = 0; $iInd < $iLinhasCadVencDesc; $iInd++) {

	    $oCadVencDescVencAtualVistSani = db_utils::fieldsMemory($rsSqlVencAtualVistSaniCadVencDesc, $iInd);

	    $oDaoCadVencDesc->q92_descr             = "VISTORIAS SANITARIO {$anodestino}";
	   	$oDaoCadVencDesc->q92_tipo 							= (empty($iArreTipoTipoNovoVistSani) ? $oTipoAtualVariavel->k00_tipovariavel : $iArreTipoTipoNovoVistSani );
	    $oDaoCadVencDesc->q92_hist              = $oCadVencDescVencAtualVistSani->q92_hist;
	    $oDaoCadVencDesc->q92_diasvcto          = $oCadVencDescVencAtualVistSani->q92_diasvcto;
	    $oDaoCadVencDesc->q92_vlrminimo         = (empty($oCadVencDescVencAtualVistSani->q92_vlrminimo)?'0':$oCadVencDescVencAtualVistSani->q92_vlrminimo);
	    $oDaoCadVencDesc->q92_formacalcparcvenc = $oCadVencDescVencAtualVistSani->q92_formacalcparcvenc;
	    $oDaoCadVencDesc->incluir(null);
	    if ($oDaoCadVencDesc->erro_status == 0) {
	      throw new Exception($oDaoCadVencDesc->erro_msg, 54);
	    }
	  }

		$oCodigoVencimento->vistoriaSanitario = $oDaoCadVencDesc->q92_codigo;

	  $sWhere                    = "q82_codigo = {$oVencAtualVistSani->q83_codven}";
	  $sSqlVencAtualVistSani  = $oDaoCadVenc->sql_query_file(null, null, $sCamposCadVenc, null, $sWhere);
	  $rsSqlVencAtualVistSani = $oDaoCadVenc->sql_record($sSqlVencAtualVistSani);
	  $iLinhasCadVenc = $oDaoCadVenc->numrows;
	  if ($iLinhasCadVenc == 0) {

	    $sMensagem = "Nenhum registro encontrado na cadvenc para q82_codigo = {$oVencAtualVistSani->q83_codven}!";
	    throw new Exception($sMensagem, 53);
	  }

	  /*
	   * Inclui na tabela cadvenc para vistorias sanitario
	   */
	  for ($xInd = 0; $xInd < $iLinhasCadVenc; $xInd++) {

	    $oCadVencVencAtualVistSani = db_utils::fieldsMemory($rsSqlVencAtualVistSani, $xInd);

	    $oDaoCadVenc->q82_codigo          = $oDaoCadVencDesc->q92_codigo;
	    $oDaoCadVenc->q82_parc            = $oCadVencVencAtualVistSani->q82_parc;
	    $oDaoCadVenc->q82_venc            = $oCadVencVencAtualVistSani->q82_venc;
	    $oDaoCadVenc->q82_desc            = $oCadVencVencAtualVistSani->q82_desc;
	    $oDaoCadVenc->q82_perc            = $oCadVencVencAtualVistSani->q82_perc;
	    $oDaoCadVenc->q82_hist            = $oCadVencVencAtualVistSani->q82_hist;
	    $oDaoCadVenc->q82_calculaparcvenc = ($oCadVencVencAtualVistSani->q82_calculaparcvenc=='t'?'true':'false');
	    $oDaoCadVenc->incluir($oDaoCadVenc->q82_codigo, $oDaoCadVenc->q82_parc);
	    if ($oDaoCadVenc->erro_status == 0) {
	      throw new Exception($oDaoCadVenc->erro_msg, 53);
	    }
	  }

	  $sWhere                         = "q92_codigo = {$oVencAtualTaxas->q83_codven}";
	  $sSqlVencAtualTaxasCadVencDesc  = $oDaoCadVencDesc->sql_query_file(null, "*", null, $sWhere);
	  $rsSqlVencAtualTaxasCadVencDesc = $oDaoCadVencDesc->sql_record($sSqlVencAtualTaxasCadVencDesc);
	  $iLinhasCadVencDesc = $oDaoCadVencDesc->numrows;
	  if ($iLinhasCadVencDesc == 0) {

	    $sMensagem = "Nenhum registro encontrado na cadvencdesc para q92_codigo = {$oVencAtualTaxas->q83_codven}!";
	    throw new Exception($sMensagem, 54);
	  }

	  /*
	   * Inclui na tabela cadvencdesc para taxas
	   */
	  for ($iInd = 0; $iInd < $iLinhasCadVencDesc; $iInd++) {

	    $oCadVencDescVencAtualTaxas = db_utils::fieldsMemory($rsSqlVencAtualTaxasCadVencDesc, $iInd);

	    $oDaoCadVencDesc->q92_descr             = "TAXA {$anodestino}";
	    $oDaoCadVencDesc->q92_tipo              = $oTipoAtualTaxas->k00_tipotaxas;
	    $oDaoCadVencDesc->q92_hist              = $oCadVencDescVencAtualTaxas->q92_hist;
	    $oDaoCadVencDesc->q92_diasvcto          = $oCadVencDescVencAtualTaxas->q92_diasvcto;
	    $oDaoCadVencDesc->q92_vlrminimo         = (empty($oCadVencDescVencAtualTaxas->q92_vlrminimo)?'0':$oCadVencDescVencAtualTaxas->q92_vlrminimo);
	    $oDaoCadVencDesc->q92_formacalcparcvenc = $oCadVencDescVencAtualTaxas->q92_formacalcparcvenc;
	    $oDaoCadVencDesc->incluir(null);
	    if ($oDaoCadVencDesc->erro_status == 0) {
	      throw new Exception($oDaoCadVencDesc->erro_msg, 54);
	    }
	  }

		$oCodigoVencimento->taxa = $oDaoCadVencDesc->q92_codigo;

	  $sWhere              = "q82_codigo = {$oVencAtualVistSani->q83_codven} ";
	  $sSqlVencAtualTaxas  = $oDaoCadVenc->sql_query_file(null, null, $sCamposCadVenc, null, $sWhere);
	  $rsSqlVencAtualTaxas = $oDaoCadVenc->sql_record($sSqlVencAtualTaxas);
	  $iLinhasCadVenc = $oDaoCadVenc->numrows;
	  if ($iLinhasCadVenc == 0) {

	    $sMensagem = "Nenhum registro encontrado na cadvenc para q82_codigo = {$oVencAtualTaxas->q83_codven}!";
	    throw new Exception($sMensagem, 53);
	  }

	  /*
	   * Inclui na tabela cadvenc para taxas
	   */
	  for ($xInd = 0; $xInd < $iLinhasCadVenc; $xInd++) {

	    $oCadVencVencAtualTaxas = db_utils::fieldsMemory($rsSqlVencAtualTaxas, $xInd);

	    $oDaoCadVenc->q82_codigo          = $oDaoCadVencDesc->q92_codigo;
	    $oDaoCadVenc->q82_parc            = $oCadVencVencAtualTaxas->q82_parc;
	    $oDaoCadVenc->q82_venc            = $oCadVencVencAtualTaxas->q82_venc;
	    $oDaoCadVenc->q82_desc            = $oCadVencVencAtualTaxas->q82_desc;
	    $oDaoCadVenc->q82_perc            = $oCadVencVencAtualTaxas->q82_perc;
	    $oDaoCadVenc->q82_hist            = $oCadVencVencAtualTaxas->q82_hist;
	    $oDaoCadVenc->q82_calculaparcvenc = ($oCadVencVencAtualTaxas->q82_calculaparcvenc=='t'?'true':'false');
	    $oDaoCadVenc->incluir($oDaoCadVenc->q82_codigo, $oDaoCadVenc->q82_parc);
	    if ($oDaoCadVenc->erro_status == 0) {
	      throw new Exception($oDaoCadVenc->erro_msg, 53);
	    }
	  }

	  /*
	   * Deleta registros da tipcalcexe para o novo ano
	   */
		$sSqlTipCalcExe   = " delete from tipcalcexe                                     ";
		$sSqlTipCalcExe  .= "       using tipcalc,                                       ";
		$sSqlTipCalcExe  .= "             cadcalc                                        ";
		$sSqlTipCalcExe  .= "       where tipcalc.q81_codigo    = tipcalcexe.q83_tipcalc ";
		$sSqlTipCalcExe  .= "         and tipcalcexe.q83_anousu = {$anodestino}          ";
		$sSqlTipCalcExe  .= "         and tipcalc.q81_cadcalc   = cadcalc.q85_codigo     ";
		$sSqlTipCalcExe  .= "         and tipcalc.q81_tipo      = 1                      ";
		$sSqlTipCalcExe  .= "         and cadcalc.q85_var is false;                      ";

	  $rsSqlTipCalcExe  = db_query($sSqlTipCalcExe);

	  $sWhere          = "q83_anousu    = {$anoorigem} ";
	  $sWhere         .= "and q81_tipo  = 1            ";
	  $sWhere         .= "and q85_var is false         ";
	  $sSqlTipCalcExe  = $oDaoTipCalcExe->sql_query_tipocalc(null, "tipcalcexe.*", null, $sWhere);

	  $rsSqlTipCalcExe = $oDaoTipCalcExe->sql_record($sSqlTipCalcExe);
	  if ($oDaoTipCalcExe->numrows == 0) {

	    $sMensagem = "Nenhum registro encontrado na tipcalcexe!";
	    throw new Exception($sMensagem, 1682);
	  }

	  $iLinhasTipoCalc = $oDaoTipCalcExe->numrows;
	  /*
	   * Inclui registros na tipcalcexe para o novo ano por tipo
	   */
	  for ($iInd = 0; $iInd < $iLinhasTipoCalc; $iInd++) {

	  	$oTipCalcExe = db_utils::fieldsMemory($rsSqlTipCalcExe, $iInd);

	    $oDaoTipCalcExe->q83_tipcalc            = $oTipCalcExe->q83_tipcalc;
	    $oDaoTipCalcExe->q83_anousu             = $anodestino;
	    $oDaoTipCalcExe->q83_codven             = $oCodigoVencimento->issqnFixo;
			if ( !empty($oTipCalcExe->q83_cadvencdescsimples) ) {
				$oDaoTipCalcExe->q83_cadvencdescsimples = $oTipCalcExe->q83_cadvencdescsimples;
			} else {
				$oDaoTipCalcExe->q83_cadvencdescsimples = 'null';
			}
	    $oDaoTipCalcExe->incluir(null);
	    if ($oDaoTipCalcExe->erro_status == 0) {
	      throw new Exception($oDaoTipCalcExe->erro_msg, 1682);
	    }
	  }

	  /*
	   * Deleta registros da tipcalcexe para o novo ano
	   */
	  $sSqlTipCalcExe   = " delete from tipcalcexe                                     ";
	  $sSqlTipCalcExe  .= "       using tipcalc,                                       ";
	  $sSqlTipCalcExe  .= "             cadcalc                                        ";
	  $sSqlTipCalcExe  .= "       where tipcalc.q81_codigo    = tipcalcexe.q83_tipcalc ";
	  $sSqlTipCalcExe  .= "         and tipcalcexe.q83_anousu = {$anodestino}          ";
	  $sSqlTipCalcExe  .= "         and tipcalc.q81_cadcalc   = cadcalc.q85_codigo     ";
	  $sSqlTipCalcExe  .= "         and tipcalc.q81_tipo      = 1                      ";
	  $sSqlTipCalcExe  .= "         and cadcalc.q85_var is true;                       ";
	  $rsSqlTipCalcExe  = db_query($sSqlTipCalcExe);

	  $sWhere          = "q83_anousu    = {$anoorigem} ";
	  $sWhere         .= "and q81_tipo  = 1            ";
	  $sWhere         .= "and q85_var is true          ";
	  $sSqlTipCalcExe  = $oDaoTipCalcExe->sql_query_tipocalc(null, "tipcalcexe.*", null, $sWhere);
	  $rsSqlTipCalcExe = $oDaoTipCalcExe->sql_record($sSqlTipCalcExe);
	  if ($oDaoTipCalcExe->numrows == 0) {

	    $sMensagem = "Nenhum registro encontrado na tipcalcexe!";
	    throw new Exception($sMensagem, 1682);
	  }


	  $iLinhasTipoCalc = $oDaoTipCalcExe->numrows;
	  /*
	   * Inclui registros na tipcalcexe para o novo ano por tipo
	   */
	  for ($iInd = 0; $iInd < $iLinhasTipoCalc; $iInd++) {

	    $oTipCalcExe = db_utils::fieldsMemory($rsSqlTipCalcExe, $iInd);

	    $oDaoTipCalcExe->q83_tipcalc            = $oTipCalcExe->q83_tipcalc;
	    $oDaoTipCalcExe->q83_anousu             = $anodestino;
	    $oDaoTipCalcExe->q83_codven             = $oCodigoVencimento->issqnVariavel;
			if ( !empty($oTipCalcExe->q83_cadvencdescsimples) ) {
				$oDaoTipCalcExe->q83_cadvencdescsimples = $oTipCalcExe->q83_cadvencdescsimples;
			} else {
				$oDaoTipCalcExe->q83_cadvencdescsimples = 'null';
			}
	    $oDaoTipCalcExe->incluir(null);
	    if ($oDaoTipCalcExe->erro_status == 0) {
	      throw new Exception($oDaoTipCalcExe->erro_msg, 1682);
	    }
	  }

	  /*
	   * Deleta registros da tipcalcexe para o novo ano
	   */
	  $sSqlTipCalcExe   = " delete from tipcalcexe                                     ";
	  $sSqlTipCalcExe  .= "       using tipcalc,                                       ";
	  $sSqlTipCalcExe  .= "             cadcalc                                        ";
	  $sSqlTipCalcExe  .= "       where tipcalc.q81_codigo    = tipcalcexe.q83_tipcalc ";
	  $sSqlTipCalcExe  .= "         and tipcalcexe.q83_anousu = {$anodestino}          ";
	  $sSqlTipCalcExe  .= "         and tipcalc.q81_cadcalc   = cadcalc.q85_codigo     ";
	  $sSqlTipCalcExe  .= "         and tipcalc.q81_tipo      = 4                      ";
	  $rsSqlTipCalcExe  = db_query($sSqlTipCalcExe);

	  $sWhere          = "q83_anousu    = {$anoorigem} ";
	  $sWhere         .= "and q81_tipo  = 4            ";
	  $sSqlTipCalcExe  = $oDaoTipCalcExe->sql_query_tipocalc(null, "tipcalcexe.*", null, $sWhere);
	  $rsSqlTipCalcExe = $oDaoTipCalcExe->sql_record($sSqlTipCalcExe);
	  if ($oDaoTipCalcExe->numrows == 0) {

	    $sMensagem = "Nenhum registro encontrado na tipcalcexe!";
	    throw new Exception($sMensagem, 1682);
	  }

	  $iLinhasTipoCalc = $oDaoTipCalcExe->numrows;
	  /*
	   * Inclui registros na tipcalcexe para o novo ano por tipo
	   */
	  for ($iInd = 0; $iInd < $iLinhasTipoCalc; $iInd++) {

	    $oTipCalcExe = db_utils::fieldsMemory($rsSqlTipCalcExe, $iInd);

	    $oDaoTipCalcExe->q83_tipcalc            = $oTipCalcExe->q83_tipcalc;
	    $oDaoTipCalcExe->q83_anousu             = $anodestino;
	    $oDaoTipCalcExe->q83_codven             = $oVencAtualAlvLoc->q83_codven;
			if ( !empty($oTipCalcExe->q83_cadvencdescsimples) ) {
				$oDaoTipCalcExe->q83_cadvencdescsimples = $oTipCalcExe->q83_cadvencdescsimples;
			} else {
				$oDaoTipCalcExe->q83_cadvencdescsimples = 'null';
			}
	    $oDaoTipCalcExe->incluir(null);
	    if ($oDaoTipCalcExe->erro_status == 0) {
	      throw new Exception($oDaoTipCalcExe->erro_msg, 1682);
	    }
	  }

	  /*
	   * Deleta registros da tipcalcexe para o novo ano
	   */
	  $sSqlTipCalcExe   = " delete from tipcalcexe                                     ";
	  $sSqlTipCalcExe  .= "       using tipcalc,                                       ";
	  $sSqlTipCalcExe  .= "             cadcalc                                        ";
	  $sSqlTipCalcExe  .= "       where tipcalc.q81_codigo    = tipcalcexe.q83_tipcalc ";
	  $sSqlTipCalcExe  .= "         and tipcalcexe.q83_anousu = {$anodestino}          ";
	  $sSqlTipCalcExe  .= "         and tipcalc.q81_cadcalc   = cadcalc.q85_codigo     ";
	  $sSqlTipCalcExe  .= "         and tipcalc.q81_tipo      = 2                      ";
	  $rsSqlTipCalcExe  = db_query($sSqlTipCalcExe);

	  $sWhere          = "q83_anousu    = {$anoorigem} ";
	  $sWhere         .= "and q81_tipo  = 2            ";
	  $sSqlTipCalcExe  = $oDaoTipCalcExe->sql_query_tipocalc(null, "tipcalcexe.*", null, $sWhere);
	  $rsSqlTipCalcExe = $oDaoTipCalcExe->sql_record($sSqlTipCalcExe);
	  if ($oDaoTipCalcExe->numrows == 0) {

	    $sMensagem = "Nenhum registro encontrado na tipcalcexe!";
	    throw new Exception($sMensagem, 1682);
	  }

	  $iLinhasTipoCalc = $oDaoTipCalcExe->numrows;
	  /*
	   * Inclui registros na tipcalcexe para o novo ano por tipo
	   */
	  for ($iInd = 0; $iInd < $iLinhasTipoCalc; $iInd++) {

	    $oTipCalcExe = db_utils::fieldsMemory($rsSqlTipCalcExe, $iInd);

	    $oDaoTipCalcExe->q83_tipcalc            = $oTipCalcExe->q83_tipcalc;
	    $oDaoTipCalcExe->q83_anousu             = $anodestino;
	    $oDaoTipCalcExe->q83_codven             = $oVencAtualAlvSani->q83_codven;
			if ( !empty($oTipCalcExe->q83_cadvencdescsimples) ) {
				$oDaoTipCalcExe->q83_cadvencdescsimples = $oTipCalcExe->q83_cadvencdescsimples;
			} else {
				$oDaoTipCalcExe->q83_cadvencdescsimples = 'null';
			}
	    $oDaoTipCalcExe->incluir(null);
	    if ($oDaoTipCalcExe->erro_status == 0) {
	      throw new Exception($oDaoTipCalcExe->erro_msg, 1682);
	    }
	  }

	  /*
	   * Deleta registros da tipcalcexe para o novo ano
	   */
	  $sSqlTipCalcExe   = " delete from tipcalcexe                                     ";
	  $sSqlTipCalcExe  .= "       using tipcalc,                                       ";
	  $sSqlTipCalcExe  .= "             cadcalc                                        ";
	  $sSqlTipCalcExe  .= "       where tipcalc.q81_codigo    = tipcalcexe.q83_tipcalc ";
	  $sSqlTipCalcExe  .= "         and tipcalcexe.q83_anousu = {$anodestino}          ";
	  $sSqlTipCalcExe  .= "         and tipcalc.q81_cadcalc   = cadcalc.q85_codigo     ";
	  $sSqlTipCalcExe  .= "         and tipcalc.q81_tipo      = 3                      ";
	  $rsSqlTipCalcExe  = db_query($sSqlTipCalcExe);

	  $sWhere          = "q83_anousu    = {$anoorigem} ";
	  $sWhere         .= "and q81_tipo  = 3            ";
	  $sSqlTipCalcExe  = $oDaoTipCalcExe->sql_query_tipocalc(null, "tipcalcexe.*", null, $sWhere);
	  $rsSqlTipCalcExe = $oDaoTipCalcExe->sql_record($sSqlTipCalcExe);
	  if ($oDaoTipCalcExe->numrows == 0) {

	    $sMensagem = "Nenhum registro encontrado na tipcalcexe!";
	    throw new Exception($sMensagem, 1682);
	  }

	  $iLinhasTipoCalc = $oDaoTipCalcExe->numrows;
	  /*
	   * Inclui registros na tipcalcexe para o novo ano por tipo
	   */
	  for ($iInd = 0; $iInd < $iLinhasTipoCalc; $iInd++) {

	    $oTipCalcExe = db_utils::fieldsMemory($rsSqlTipCalcExe, $iInd);

	    $oDaoTipCalcExe->q83_tipcalc            = $oTipCalcExe->q83_tipcalc;
	    $oDaoTipCalcExe->q83_anousu             = $anodestino;
	    $oDaoTipCalcExe->q83_codven             = $oCodigoVencimento->vistoriaLocalizacao;
			if ( !empty($oTipCalcExe->q83_cadvencdescsimples) ) {
				$oDaoTipCalcExe->q83_cadvencdescsimples = $oTipCalcExe->q83_cadvencdescsimples;
			} else {
				$oDaoTipCalcExe->q83_cadvencdescsimples = 'null';
			}
	    $oDaoTipCalcExe->incluir(null);
	    if ($oDaoTipCalcExe->erro_status == 0) {
	      throw new Exception($oDaoTipCalcExe->erro_msg, 1682);
	    }
	  }

	  /*
	   * Deleta registros da tipcalcexe para o novo ano
	   */
	  $sSqlTipCalcExe   = " delete from tipcalcexe                                     ";
	  $sSqlTipCalcExe  .= "       using tipcalc,                                       ";
	  $sSqlTipCalcExe  .= "             cadcalc                                        ";
	  $sSqlTipCalcExe  .= "       where tipcalc.q81_codigo    = tipcalcexe.q83_tipcalc ";
	  $sSqlTipCalcExe  .= "         and tipcalcexe.q83_anousu = {$anodestino}          ";
	  $sSqlTipCalcExe  .= "         and tipcalc.q81_cadcalc   = cadcalc.q85_codigo     ";
	  $sSqlTipCalcExe  .= "         and tipcalc.q81_tipo      = 6                      ";
	  $rsSqlTipCalcExe  = db_query($sSqlTipCalcExe);

	  $sWhere          = "q83_anousu    = {$anoorigem} ";
	  $sWhere         .= "and q81_tipo  = 6            ";
	  $sSqlTipCalcExe  = $oDaoTipCalcExe->sql_query_tipocalc(null, "tipcalcexe.*", null, $sWhere);
	  $rsSqlTipCalcExe = $oDaoTipCalcExe->sql_record($sSqlTipCalcExe);
	  if ($oDaoTipCalcExe->numrows == 0) {

	    $sMensagem = "Nenhum registro encontrado na tipcalcexe!";
	    throw new Exception($sMensagem, 1682);
	  }

	  $iLinhasTipoCalc = $oDaoTipCalcExe->numrows;
	  /*
	   * Inclui registros na tipcalcexe para o novo ano por tipo
	   */
	  for ($iInd = 0; $iInd < $iLinhasTipoCalc; $iInd++) {

	    $oTipCalcExe = db_utils::fieldsMemory($rsSqlTipCalcExe, $iInd);

	    $oDaoTipCalcExe->q83_tipcalc            = $oTipCalcExe->q83_tipcalc;
	    $oDaoTipCalcExe->q83_anousu             = $anodestino;
	    $oDaoTipCalcExe->q83_codven             = $oCodigoVencimento->vistoriaSanitario;
			if ( !empty($oTipCalcExe->q83_cadvencdescsimples) ) {
				$oDaoTipCalcExe->q83_cadvencdescsimples = $oTipCalcExe->q83_cadvencdescsimples;
			} else {
				$oDaoTipCalcExe->q83_cadvencdescsimples = 'null';
			}
	    $oDaoTipCalcExe->incluir(null);
	    if ($oDaoTipCalcExe->erro_status == 0) {
	      throw new Exception($oDaoTipCalcExe->erro_msg, 1682);
	    }
	  }

	  /*
	   * Deleta registros da tipcalcexe para o novo ano
	   */
	  $sSqlTipCalcExe   = " delete from tipcalcexe                                     ";
	  $sSqlTipCalcExe  .= "       using tipcalc,                                       ";
	  $sSqlTipCalcExe  .= "             cadcalc                                        ";
	  $sSqlTipCalcExe  .= "       where tipcalc.q81_codigo    = tipcalcexe.q83_tipcalc ";
	  $sSqlTipCalcExe  .= "         and tipcalcexe.q83_anousu = {$anodestino}          ";
	  $sSqlTipCalcExe  .= "         and tipcalc.q81_cadcalc   = cadcalc.q85_codigo     ";
	  $sSqlTipCalcExe  .= "         and tipcalc.q81_tipo      = 5                      ";
	  $rsSqlTipCalcExe  = db_query($sSqlTipCalcExe);

	  $sWhere          = "q83_anousu    = {$anoorigem} ";
	  $sWhere         .= "and q81_tipo  = 5            ";
	  $sSqlTipCalcExe  = $oDaoTipCalcExe->sql_query_tipocalc(null, "tipcalcexe.*", null, $sWhere);
	  $rsSqlTipCalcExe = $oDaoTipCalcExe->sql_record($sSqlTipCalcExe);
	  if ($oDaoTipCalcExe->numrows == 0) {

	    $sMensagem = "Nenhum registro encontrado na tipcalcexe!";
	    throw new Exception($sMensagem, 1682);
	  }

	  $iLinhasTipoCalc = $oDaoTipCalcExe->numrows;
	  /*
	   * Inclui registros na tipcalcexe para o novo ano por tipo
	   */
	  for ($iInd = 0; $iInd < $iLinhasTipoCalc; $iInd++) {

	    $oTipCalcExe = db_utils::fieldsMemory($rsSqlTipCalcExe, $iInd);

	    $oDaoTipCalcExe->q83_tipcalc            = $oTipCalcExe->q83_tipcalc;
	    $oDaoTipCalcExe->q83_anousu             = $anodestino;
	    $oDaoTipCalcExe->q83_codven             = $oCodigoVencimento->taxa;
			if ( !empty($oTipCalcExe->q83_cadvencdescsimples) ) {
				$oDaoTipCalcExe->q83_cadvencdescsimples = $oTipCalcExe->q83_cadvencdescsimples;
			} else {
				$oDaoTipCalcExe->q83_cadvencdescsimples = 'null';
			}
	    $oDaoTipCalcExe->incluir(null);
	    if ($oDaoTipCalcExe->erro_status == 0) {
	      throw new Exception($oDaoTipCalcExe->erro_msg, 1682);
	    }
	  }

	  /*
	   * Deleta registros da cissqn para o novo ano
	   */
	  $oDaoCissqn->excluir(null, "q04_anousu = {$anodestino}");
	  if ($oDaoCissqn->erro_status == 0) {
	    throw new Exception($oDaoCissqn->erro_msg, 55);
	  }

	  $sCampoCissqn  = "q04_inflat,                                                                 ";
	  $sCampoCissqn .= "case                                                                        ";
	  $sCampoCissqn .= "  when q04_inflat <> 'REAL'                                                 ";
	  $sCampoCissqn .= "    then q04_vbase                                                          ";
	  $sCampoCissqn .= "  else q04_vbase + (q04_vbase * {$oPercentual->q04_perccorrepadrao} / 100)  ";
	  $sCampoCissqn .= "end as q04_vbase,                                                           ";
	  $sCampoCissqn .= "'{$anodestino}-01-01' as q04_dtbase,                                        ";
	  $sCampoCissqn .= "q04_proced,                                                                 ";
	  $sCampoCissqn .= "q04_calfixvar,                                                              ";
	  $sCampoCissqn .= "q04_diasvcto                                                                ";
	  $sWhere        = "q04_anousu = {$anoorigem}";
	  $sSqlCissqn    = $oDaoCissqn->sql_query_file(null, $sCampoCissqn, null, $sWhere);
	  $rsSqlCissqn   = $oDaoCissqn->sql_record($sSqlCissqn);
	  if ($oDaoCissqn->numrows == 0) {

	    $sMensagem = "Nenhum registro encontrado na cissqn!";
	    throw new Exception($sMensagem, 55);
	  }

	  /*
	   * Inclui registros na cissqn para o novo ano
	   */
	  for ($iInd = 0; $iInd < $oDaoCissqn->numrows; $iInd++) {

	    $oCissqn = db_utils::fieldsMemory($rsSqlCissqn, $iInd);

	    $oDaoCissqn->q04_anousu          = $anodestino;
	    $oDaoCissqn->q04_inflat          = $oCissqn->q04_inflat;
	    $oDaoCissqn->q04_vbase           = $oCissqn->q04_vbase;
	    $oDaoCissqn->q04_dtbase          = $oCissqn->q04_dtbase;
	    $oDaoCissqn->q04_proced          = $oCissqn->q04_proced;
	    $oDaoCissqn->q04_calfixvar       = $oCissqn->q04_calfixvar;
	    $oDaoCissqn->q04_diasvcto        = $oCissqn->q04_diasvcto;
	    $oDaoCissqn->q04_perccorrepadrao = $oPercentual->q04_perccorrepadrao;
	    $oDaoCissqn->incluir($oDaoCissqn->q04_anousu);
	    if ($oDaoCissqn->erro_status == 0) {
	      throw new Exception($oDaoCissqn->erro_msg, 55);
	    }
	  }

	  /*
	   * Altera valor q60_codvencvar da tabela parissqn
	   */
		$oDaoParIssqn->q60_codvencvar = $oCodigoVencimento->issqnVariavel;
		$oDaoParIssqn->q60_histsemmov = $oCodigoVencimento->issqnVariavelHist;
	  $oDaoParIssqn->alterarParametro();
	  if ($oDaoParIssqn->erro_status == 0) {
	  	throw new Exception($oDaoParIssqn->erro_msg, 664);
	  }


	  /*
	   * Inclui registro da issconfiguracaogruposervico para o próximo ano
	   */

	  $sSqlIssConfiguaracaoGrupoServico = $oDaoIssConfiguracaoGrupoServico->sql_query_file(null,
	                                                                                       "*",
	                                                                                       null,
	                                                                                       "q136_exercicio = {$anoorigem}");

	  $rsIssConfiguracaoGrupoServico    = $oDaoIssConfiguracaoGrupoServico->sql_record($sSqlIssConfiguaracaoGrupoServico);

	  if ($oDaoIssConfiguracaoGrupoServico->numrows > 0) {

	    $aIssConfiguracaoGrupoServico = db_utils::getCollectionByRecord($rsIssConfiguracaoGrupoServico);

	    foreach ($aIssConfiguracaoGrupoServico as $oIssConfiguracaoGrupoServico) {

	      $oDaoIssConfiguracaoGrupoServico->q136_exercicio       = $anodestino                                        ;
	      $oDaoIssConfiguracaoGrupoServico->q136_sequencial      = $oIssConfiguracaoGrupoServico->q136_sequencial     ;
	      $oDaoIssConfiguracaoGrupoServico->q136_issgruposervico = $oIssConfiguracaoGrupoServico->q136_issgruposervico;
	      $oDaoIssConfiguracaoGrupoServico->q136_tipotributacao  = $oIssConfiguracaoGrupoServico->q136_tipotributacao ;
	      $oDaoIssConfiguracaoGrupoServico->q136_valor           = $oIssConfiguracaoGrupoServico->q136_valor          ;
	      $oDaoIssConfiguracaoGrupoServico->q136_localpagamento  = $oIssConfiguracaoGrupoServico->q136_localpagamento ;
	      $oDaoIssConfiguracaoGrupoServico->incluir(null);

	      if ($oDaoIssConfiguracaoGrupoServico->erro_status == '0') {

	        throw new Exception ('Erro ao incluir na tabela issconfiguracaogruposervico. \n
	                              ERRO: ' . $oDaoIssConfiguracaoGrupoServico->erro_msg);

	      }
	    }
	  }

    $sWhere                       = "q144_ano = {$anodestino} AND q144_codvenc = {$oCodigoVencimento->issqnVariavel}";
    $sSqlConfVencISSQNVariavel    = $oDaoConfVencISSQNVariavel->sql_query_file(null, "*", null, $sWhere);
    $rsSqlConfVencISSQNVariavel   = $oDaoConfVencISSQNVariavel->sql_record($sSqlConfVencISSQNVariavel);
    $iLinhasConfVencISSQNVariavel = $oDaoConfVencISSQNVariavel->numrows;
    if ($iLinhasConfVencISSQNVariavel == 0) {

      // Busca pemo maior dia de vencimento cadastrado no vencimento atual
      $iDiaVencimento = $oDaoConfVencISSQNVariavel->getISSQNVariavelMaiorVencimento($oCodigoVencimento->issqnVariavel);

      /**
       * Buscamos a receita configurada anteriormente, para utilizarmos na nova configuração
       */
      $sWhere                           = "q144_ano = {$anoorigem}";
      $sSqlConfVencISSQNVariavelOrigem  = $oDaoConfVencISSQNVariavel->sql_query_file(null, "q144_receita", null, $sWhere);
      $rsSqlConfVencISSQNVariavelOrigem = $oDaoConfVencISSQNVariavel->sql_record($sSqlConfVencISSQNVariavelOrigem);

      if ( empty($rsSqlConfVencISSQNVariavelOrigem) ) {
      	throw new DBException("Erro ao buscar a configuração de vencimento do exercício de origem para ISSQN variável.");
      }

      $oConfVencISSQNVariavelOrigem = db_utils::fieldsMemory($rsSqlConfVencISSQNVariavelOrigem, 0);

      // Inclui registro das novas configurações para issqn variavel
      $oDaoConfVencISSQNVariavel->q144_ano     = $anodestino;
      $oDaoConfVencISSQNVariavel->q144_codvenc = $oCodigoVencimento->issqnVariavel;
      $oDaoConfVencISSQNVariavel->q144_receita = $oConfVencISSQNVariavelOrigem->q144_receita;
      $oDaoConfVencISSQNVariavel->q144_tipo    = $oTipoAtualVariavel->k00_tipovariavel;
      $oDaoConfVencISSQNVariavel->q144_hist    = $oCodigoVencimento->issqnVariavelHist;
      $oDaoConfVencISSQNVariavel->q144_diavenc = $iDiaVencimento;
      $oDaoConfVencISSQNVariavel->q144_valor   = 0.00;
      $oDaoConfVencISSQNVariavel->incluir(null);
      if ($oDaoConfVencISSQNVariavel->erro_status == '0') {
        throw new Exception($oDaoConfVencISSQNVariavel->erro_msg, 54);
      }
    }

    $sqlerro = false;
	} catch (Exception $eErro) {

		$iCodigoErro = $eErro->getCode();
	  $sMsg        = "Usuário: \\n\\n ".$eErro->getMessage()." \\n\\n";

		if ( !empty($iCodigoErro) ) {

	    $sWhere            = "codarq = {$iCodigoErro}";
	    $sSqlDbSysArquivo  = $oDaoDbSysArquivo->sql_query_file(null, "nomearq", null, $sWhere);
	    $rsSqlDbSysArquivo = $oDaoDbSysArquivo->sql_record($sSqlDbSysArquivo);
	    if ($oDaoDbSysArquivo->numrows > 0) {
	    	$oDbSysArquivo = db_utils::fieldsMemory($rsSqlDbSysArquivo, 0);
	    }

	  	$sMsgAdmin = "Erro: Verificar tabela {$oDbSysArquivo->nomearq}!";
    	$sMsg     .= str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$sMsgAdmin." \\n"));
		}

    $erro_msg = $sMsg;
	  $sqlerro  = true;
	}
}

db_atutermometro(1, 2, 'termometroitem', 1, $sMensagemTermometroItem);

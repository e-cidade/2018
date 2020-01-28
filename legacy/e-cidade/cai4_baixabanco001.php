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

use \ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Arquivo\BaseAbstract as ArquivoBaseCobrancaRegistrada;
use \ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Retorno\Retorno as CobrancaRegistradaRetorno;
use \ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Retorno\RetornoRepository as CobrancaRegistradaRetornoRepository;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_app::import('exceptions.*');

db_postmemory($HTTP_POST_VARS);

$cldb_config      = new cl_db_config();
$clCadBan         = new cl_cadban();
$clDisArq         = new cl_disarq();
$clDisBanco       = new cl_disbanco();
$clDisBancoTXT    = new cl_disbancotxt();
$clDisBancoTXTReg = new cl_disbancotxtreg();
$oDaoNumpref      = new cl_numpref();

define('MENSAGENS', 'tributario.arrecadacao.cai4_baixabanco001.');
define('CONVENIO_SIGCB_REGISTRO', 'SIGCB REGISTRO');

$situacao      = "";
$sMd5Arquivo   = null;

/**
 * Variável de Debug da rotina
 */
$lDebugAtivo   = false;

$iInstitSessao = db_getsession("DB_instit");

$result        = $cldb_config->sql_record($cldb_config->sql_query_file($iInstitSessao, "cgc, db21_codcli"));
db_fieldsmemory($result, 0);

/**
 * Busca parametros da numpref
 */
$sCamposParametrosNumpref = "k03_agrupadorarquivotxtbaixabanco, k03_pgtoparcial";
$sSqlParametrosNumpref    = $oDaoNumpref->sql_query_file(db_getsession("DB_anousu"), db_getsession("DB_instit"), $sCamposParametrosNumpref);
$rsParametrosNumpref      = $oDaoNumpref->sql_record($sSqlParametrosNumpref);
$oDadosParametrosNumpref  = db_utils::fieldsMemory($rsParametrosNumpref, 0);

/**
 * Função responsavel por controlar a taxa bancaria
 */
function geraTaxaBancaria( $oParametros ){

	$oTabDescCadBan = new cl_tabdesccadban();
	$oArreNumCgm    = new cl_arrenumcgm();
	$oArreMatric    = new cl_arrematric();
	$oArreInscr     = new cl_arreinscr();
	$oDisBanco      = new cl_disbanco();

	require_once(modification("model/recibo.model.php"));

	$nVlrTaxaBancaria = 0;
	$iCodigoHistCalc  = 507;

	/*
	 * Verificamos se existe taxa específica configurada para o banco e agencia
	 * Não pode haver mais de uma taxa configurada para o mesmo banco e agencia
	 * Caso a data de validade esteja setada a mesma deve ser respeitada
	 */
	$sWhere  = "     k15_codbco = {$oParametros->k15_codbco}   ";
	$sWhere .= " and k15_codage = '{$oParametros->k15_codage}' ";
	$sWhere .= " and (k07_dtval is null or k07_dtval > '".date("Y-m-d", db_getsession("DB_datausu"))."')";
	$sSqlTaxaBancaria    = $oTabDescCadBan->sql_query(null, "*", null, $sWhere);
	$rsTaxaBancaria      = $oTabDescCadBan->sql_record($sSqlTaxaBancaria);

	$iLinhasTaxaBancaria = $oTabDescCadBan->numrows;

	/**
	 * Não pode haver mais de uma taxa configurada para o mesmo banco e agencia
	 */
	if($iLinhasTaxaBancaria > 1){
		throw new DBException( _M( MENSAGENS . "taxa_especifica_duplicada" ) );
	}

	if ($iLinhasTaxaBancaria > 0) {

		$oDadosTaxaBancaria = db_utils::fieldsMemory($rsTaxaBancaria, 0);
		$nVlrTaxaBancaria   = $oDadosTaxaBancaria->k07_valorf;

		/*
		 * Verificamos se o pagamento parcial está ativado
		 * Caso esteja ativado, será gerado um recibo avulso para a taxa bancaria e este valor será classificado
		 */
		if ( $oParametros->k03_pgtoparcial == "t" ) {

			$rsCgmRecibo = $oArreNumCgm->sql_record($oArreNumCgm->sql_query_file(null, $oParametros->numpre));
			if ($oArreNumCgm->numrows > 0) {

				$oCgmRecibo = db_utils::getColectionByRecord($rsCgmRecibo);
				$iCgmRecibo = $oCgmRecibo[0]->k00_numcgm;

			}	else {

				$sSqlNumCgm  = " (select k00_numcgm as numcgm                       ";
				$sSqlNumCgm .= "    from arrecad                                    ";
				$sSqlNumCgm .= "   where k00_numpre = $oParametros->numpre limit 1) ";
				$sSqlNumCgm .= "   union                                            ";
				$sSqlNumCgm .= " (select k00_numcgm as numcgm                       ";
				$sSqlNumCgm .= "    from arrecant                                   ";
				$sSqlNumCgm .= "   where k00_numpre = $oParametros->numpre limit 1) ";
				$sSqlNumCgm .= "   union                                            ";
				$sSqlNumCgm .= " (select k00_numcgm as numcgm                       ";
				$sSqlNumCgm .= "    from arreold                                    ";
				$sSqlNumCgm .= "   where k00_numpre = $oParametros->numpre limit 1) ";
				$sSqlNumCgm .= "   union                                            ";
				$sSqlNumCgm .= " (select k00_numcgm as numcgm                       ";
				$sSqlNumCgm .= "    from arreforo                                   ";
				$sSqlNumCgm .= "   where k00_numpre = $oParametros->numpre limit 1) ";
				$sSqlNumCgm .= "   union                                            ";
				$sSqlNumCgm .= " (select k30_numcgm as numcgm                       ";
				$sSqlNumCgm .= "    from arreprescr                                 ";
				$sSqlNumCgm .= "   where k30_numpre = $oParametros->numpre limit 1) ";
				$sSqlNumCgm .= "   union                                            ";
				$sSqlNumCgm .= " (select k00_numcgm as numcgm                       ";
				$sSqlNumCgm .= "    from recibo                                     ";
				$sSqlNumCgm .= "   where k00_numpre = $oParametros->numpre limit 1) ";
				$sSqlNumCgm .= "   union                                            ";
				$sSqlNumCgm .= " (select k00_numcgm as numcgm                       ";
				$sSqlNumCgm .= "    from recibopaga                                 ";
				$sSqlNumCgm .= "   where k00_numnov = $oParametros->numpre limit 1) ";
				$rsNumCgm    = $oArreNumCgm->sql_record($sSqlNumCgm);
				if ($oArreNumCgm->numrows > 0) {
					$iCgmRecibo = db_utils::fieldsMemory($rsNumCgm, 0)->numcgm;
				}
			}

			/**
			 * Caso o cgm seja nulo o nao deve ser gerado o recibo avulso.
			 *  - Este caso apenas ocorrera quando nao encontrar os dados do numpre
			 *  - O parametro de pagamento parcial estiver ativo
			 *  - Tiver taxa especifica configurada para taxa bancaria
			 *  - E o cliente não for Araruama / RJ
			 */
			if(empty($iCgmRecibo)){
				return 0;
			}

			$oRecibo = new Recibo(1, $iCgmRecibo);
			$oRecibo->adicionarReceita($oDadosTaxaBancaria->k07_codigo,
			$oDadosTaxaBancaria->k07_valorf,
			$oDadosTaxaBancaria->codsubrec);
			$oRecibo->setCodigoHistorico($iCodigoHistCalc);
			$sMsgHistorico = _M( MENSAGENS . 'historico_recibo_taxa_bancaria',  $oParametros);
			$oRecibo->setHistorico($sMsgHistorico);

			/*
			 * Vinculação do recibo com CGM, Matricula e Inscrição
			 */
			foreach($oCgmRecibo as $oCgm) {
				$oRecibo->setVinculoCgm($oCgm->k00_numcgm);
			}

			$rsMatriculaRecibo = $oArreMatric->sql_record($oArreMatric->sql_query_file($oParametros->numpre));
			if ($oArreMatric->numrows > 0) {

				for ($iIndMatricula = 0; $iIndMatricula < $oArreMatric->numrows; $iIndMatricula++) {
					$oRecibo->setMatricula(db_utils::fieldsMemory($rsMatriculaRecibo, $iIndMatricula)->k00_matric);
				}
			}

			$rsInscricaoRecibo = $oArreInscr->sql_record($oArreInscr->sql_query_file($oParametros->numpre));
			if ($oArreInscr->numrows > 0) {

				for($iIndInscricao = 0; $iIndInscricao < $oArreInscr->numrows; $iIndInscricao++) {
					$oRecibo->setInscricao(db_utils::fieldsMemory($rsInscricaoRecibo, $iIndInscricao)->k00_inscr);
				}
			}
			/*
			 * Fim das vinculações
			 */

			$oRecibo->emiteRecibo();

			/**
			 * Pega numpre do recibo gerado e insere na disbanco
			 *
			 */
			$iNumpreReciboTaxaBancaria = $oRecibo->getNumpreRecibo();

			/**
			 * Insere o recibo na disbanco
			 */
			/**
			 * Habilita variavel de sessao para permitir numpre's de outras instituições
			 */
			permiteNumpreOutraInstituicao( true );

			$oDisBanco->codret     = $oParametros->codret;
			$oDisBanco->k15_codbco = $oParametros->k15_codbco;
			$oDisBanco->k15_codage = $oParametros->k15_codage;
			$oDisBanco->dtarq      = $oParametros->dtarq;
			$oDisBanco->dtpago     = $oParametros->dtpago;
			$oDisBanco->dtcredito  = $oParametros->dtcredito;
			$oDisBanco->vlrpago    = $oDadosTaxaBancaria->k07_valorf;
			$oDisBanco->vlrcalc    = $oDadosTaxaBancaria->k07_valorf;
			$oDisBanco->vlrtot     = $oDadosTaxaBancaria->k07_valorf;
			$oDisBanco->classi     = "false";
			$oDisBanco->k00_numpre = $iNumpreReciboTaxaBancaria;
			$oDisBanco->k00_numpar = "0";
			$oDisBanco->instit     = db_getsession("DB_instit");
			$oDisBanco->incluir(null);

			/**
			 * Desabilita variavel de sessao para permitir numpre's de outras instituições
			 */
			permiteNumpreOutraInstituicao( false );
			if ($oDisBanco->erro_status == "0") {
				throw new DBException($oDisBanco->erro_msg);
			}

		}

	}

	return $nVlrTaxaBancaria;
}

/**
 * Função que controla variável de sessao da trigger da disbanco
 * permitindo ou nao a insercao de numpre's de outras intituicoes
 * @param  boolean $lPermitir
 * @return void
 */
function permiteNumpreOutraInstituicao( $lPermitir = false ){

	$sSql = "select fc_putsession('DB_permiteNumpreOutraInstituicao', '{$lPermitir}')";
	db_query($sSql);
}

/**
 * Testa se cliente MARICA inclui fonte específico
 */
if ( $db21_codcli == 19985 ) {

	if( isset($k15_codbco) && isset($k15_codage) ){

		if( !($k15_codbco == 104 && $k15_codage == '1244F') ){

			require_once(modification("cai4_baixabanco001_codcli_19985.php"));
			exit;
		}
	}
}

if (isset ($processar)) {

	try {

		/**
		 * Recebe codbco,codage,tamanho
		 * Verifica banco e agencia
		 */
		db_postmemory($_FILES["arqret"]);
		$arq_name    = basename($name);
		$arq_type    = $type;
		$arq_tmpname = basename($tmp_name);
		$arq_size    = $size;
		$arq_array   = file($tmp_name);

		$sMd5Arquivo = md5(file_get_contents($tmp_name));
		db_putsession('disarq_md5', $sMd5Arquivo);

		/**
		 * Verifica se arquivo já foi importado
		 */
		$sSqlArquivoImportado = $clDisArq->sql_query_file(null, 'true', null, "md5 = '$sMd5Arquivo'");
		$rsArquivoImportado   = $clDisArq->sql_record($sSqlArquivoImportado);

		if ($clDisArq->numrows > 0) {
			throw new BusinessException( _M( MENSAGENS . "arquivo_importado" ) );
		}

		system("cp -f ".$tmp_name." ". ECIDADE_PATH ."tmp/", $intret);

		if($intret != 0) {

			$oParametrosMsg               = new stdClass();
			$oParametrosMsg->sNomeArquivo = $tmp_name;
			$sMsg                         = _M( MENSAGENS . 'erro_ao_copiar_arquivo', $oParametrosMsg);
			throw new Exception($sMsg);
		}

		$sWhere     = "     k15_codbco = {$k15_codbco}    ";
		$sWhere    .= " and k15_codage = '{$k15_codage}'  ";
		$sWhere    .= " and k15_instit = {$iInstitSessao} ";
		$sSqlCadBan = $clCadBan->sql_query(null,"*",null,$sWhere);
		$rsCadBan   = $clCadBan->sql_record($sSqlCadBan);

		if ($clCadBan->numrows == 0) {
			throw new Exception( _M( MENSAGENS . 'banco_agencia_nao_encontrados' ) );
		}

		db_fieldsmemory($rsCadBan, 0);

		$_tamanprilinha = $arq_array[0];
		$atipo          = substr($arq_array[0], 0, 3);
		$totalproc      = sizeof($arq_array) - 2;
		$priregistro    = 1;
		$acodbco        = substr($arq_array[0], substr($k15_posbco, 0, 3), substr($k15_posbco, 3, 3));

		// if ($cgc == '88073291000199') { // bage

		// 	if (substr($arq_name, 0, 4) == "daeb") {

		// 		if (substr($arq_array[0], 0, 3) == "826") {

		// 			$_tamanprilinha = str_repeat(" ", $k15_taman);
		// 			$atipo          = "XXX";
		// 			$totalproc      = sizeof($arq_array);
		// 			$priregistro    = 0;
		// 			$acodbco        = 999;
		// 		}
		// 	}
		// }

		$k15_codbco = (int) $k15_codbco;
		$acodbco    = (int) $acodbco;

		if ( strlen($_tamanprilinha) != $k15_taman ) {

			$oParametrosMsg                   = new stdClass();
			$oParametrosMsg->sTamanhoRegistro = strlen($arq_array[0]);
			$oParametrosMsg->sSistema         = $k15_taman;
			$sMsg                             = _M( MENSAGENS . 'tamanho_sistema_invalido', $oParametrosMsg);
			throw new Exception($sMsg);
		}

		if ($k15_codbco != $acodbco && $atipo != "BSJ") {

			$oParametrosMsg                   = new stdClass();
			$oParametrosMsg->sBancoDigitado   = $k15_codbco;
			$oParametrosMsg->sArquivoCodBanco = $acodbco;
			$sMsg                             = _M( MENSAGENS . 'banco_digitado_nao_confere', $oParametrosMsg);
			throw new Exception($sMsg);
		}

		$situacao = 1;
		$sCampos  = "codret     as codretexiste,   ";
		$sCampos .= "k15_codbco as bancoexiste,    ";
		$sCampos .= "k15_codage as agenciaexiste,  ";
		$sCampos .= "dtarquivo  as dtarquivoexiste ";
		$rsDisArq = $clDisArq->sql_record($clDisArq->sql_query_file(null,
		$sCampos,
		null,
				"md5 = '{$sMd5Arquivo}' and instit = $iInstitSessao"));

		if ($clDisArq->numrows > 0) {
			db_fieldsmemory($rsDisArq, 0);
		}

		$totalvalorpago = 0;

		for ($i = $priregistro; $i <= $totalproc - ($priregistro == 0 ? 1 : 0); $i ++) {

			$sCodigoSegmento = substr($arq_array[$i], 13, 1);
			$sCodigoRetorno  = substr($arq_array[$i], 15, 2);

			/**
			 * Ignora linhas referentes a Débito de Tarifas/Custas, para os segmentos T e U do convênio SIGCB com registro
			 */
			if (in_array($sCodigoSegmento, array('T', 'U')) && $sCodigoRetorno != '06') {
				continue;
			}

			/**
			 * Grava arquivo disbanco
			 */
			if ($k15_taman == 242) {

				//Acerto 1/2 para arapiraca
				$totalproc = sizeof($arq_array) - 3;
				if (substr($arq_array[$i], 7, 1) != '3' || substr($arq_array[$i], 13, 1) != 'U') {
					continue;
				}
			} elseif ($k15_taman == 402) {

				if (substr($arq_array[$i], 0, 1) == '9') {
					continue;
				}
			} elseif ($k15_taman == 90) {

				if (substr($arq_array[$i], 0, 5) <> 'BSJI2') {
					continue;
				}
			} elseif (substr($arq_array[$i], 0, 1) <> "G") {

				if (substr($arq_array[$i], 0, 3) <> "104" && substr($arq_array[$i], 0, 3) <> "BSJ") {
					continue;
				}
			}

			if (substr($arq_array[$i], 0, 4) == 'BSJI') {

				if (substr($arq_array[0], 0, 5) == 'BSJI0' && $i == 1) {

					if (substr($k15_plano, 3, 3) == '002') {
						$dtarq = '20'.substr($arq_array[0], substr($k15_plano, 0, 3) - 1, substr($k15_plano, 3, 3));
					} else {
						$dtarq = substr($arq_array[0], substr($k15_plano, 0, 3) - 1, substr($k15_plano, 3, 3));
					}

					$dtarq .= "-".substr($arq_array[0], substr($k15_plmes, 0, 3) - 1, substr($k15_plmes, 3, 3));
					$dtarq .= "-".substr($arq_array[0], substr($k15_poslan, 0, 3) - 1, substr($k15_poslan, 3, 3));
				}
			} else {

				if (substr($k15_plano, 3, 3) == '002') {
					$dtarq = '20'.substr($arq_array[$i], substr($k15_plano, 0, 3) - 1, substr($k15_plano, 3, 3));
				} else {
					$dtarq = substr($arq_array[$i], substr($k15_plano, 0, 3) - 1, substr($k15_plano, 3, 3));
				}

				$dtarq .= "-".substr($arq_array[$i], substr($k15_plmes, 0, 3) - 1, substr($k15_plmes, 3, 3));
				$dtarq .= "-".substr($arq_array[$i], substr($k15_poslan, 0, 3) - 1, substr($k15_poslan, 3, 3));
			}

			if (substr($k15_ppano, 3, 3) == '002') {
				$dtpago = '20'.substr($arq_array[$i], substr($k15_ppano, 0, 3) - 1, substr($k15_ppano, 3, 3));
			} else {
				$dtpago = substr($arq_array[$i], substr($k15_ppano, 0, 3) - 1, substr($k15_ppano, 3, 3));
			}

			$dtpago .= "-".substr($arq_array[$i], substr($k15_ppmes , 0, 3) - 1, substr($k15_ppmes, 3, 3));
			$dtpago .= "-".substr($arq_array[$i], substr($k15_pospag, 0, 3) - 1, substr($k15_pospag, 3, 3));

			if ($dtpago == '0000-00-00') {

				$dtpago = $dtarquivo;
				$dtarq  = $dtarquivo;
			}

			if (substr($k15_anocredito, 3, 3) == '002') {
				$dtcredito = '20'.substr($arq_array[$i], substr($k15_anocredito, 0, 3) - 1, substr($k15_anocredito, 3, 3));
			} else {
				$dtcredito = substr($arq_array[$i], substr($k15_anocredito, 0, 3) - 1, substr($k15_anocredito, 3, 3));
			}

			$dtcredito .= "-".substr($arq_array[$i], substr($k15_mescredito, 0, 3) - 1, substr($k15_mescredito, 3, 3));
			$dtcredito .= "-".substr($arq_array[$i], substr($k15_diacredito, 0, 3) - 1, substr($k15_diacredito, 3, 3));

			if (empty($dtcredito) || $dtcredito == '0000-00-00') {
				$dtcredito = $dtpago;
			}

			$vlrpago  = (substr($arq_array[$i], substr($k15_posvlr, 0, 3) - 1, substr($k15_posvlr, 3, 3)) / 100) + 0;
			$vlrjuros = (substr($arq_array[$i], substr($k15_posjur, 0, 3) - 1, substr($k15_posjur, 3, 3)) / 100) + 0;
			$vlrmulta = (substr($arq_array[$i], substr($k15_posmul, 0, 3) - 1, substr($k15_posmul, 3, 3)) / 100) + 0;
			$vlracres = (substr($arq_array[$i], substr($k15_posacr, 0, 3) - 1, substr($k15_posacr, 3, 3)) / 100) + 0;
			$vlrdesco = (substr($arq_array[$i], substr($k15_posdes, 0, 3) - 1, substr($k15_posdes, 3, 3)) / 100) + 0;
			$convenio =  substr($arq_array[$i], substr($k15_poscon, 0, 3) - 1, substr($k15_poscon, 3, 3));
			$cedente  =  substr($arq_array[$i], substr($k15_posced, 0, 3) - 1, substr($k15_posced, 3, 3));

			$totalvalorpago += $vlrpago;
		}

	} catch (Exception $oException) {
		db_msgbox("{$oException->getMessage()}");
	}

} else if (isset ($geradisbanco)) {

	$situacao = 2;
	$sWhere   = "    k15_codbco = {$k15_codbco}     ";
	$sWhere  .= " and k15_codage = '{$k15_codage}'  ";
	$sWhere  .= " and k15_instit = {$iInstitSessao} ";
	$rsCadBan = $clCadBan->sql_record($clCadBan->sql_query_file(null,"*",null,$sWhere));
	db_fieldsmemory($rsCadBan, 0);

	$arq_array = file(ECIDADE_PATH . "tmp/".$arqret);

	if ($arq_array == false) {

		$oParametrosMsg                = new stdClass();
		$oParametrosMsg->sDocumentRoot = ECIDADE_PATH;
		$sMsg                          = _M( MENSAGENS . 'erro_permissao_pasta', $oParametrosMsg);
		db_msgbox( $sMsg );
		exit;
	}
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
db_app::load("scripts.js, strings.js, prototype.js, estilos.css");
?>
</head>
<body class="body-default" onLoad="a=1">
<?php

if ($situacao == "") {

	include (modification("forms/db_caiarq001.php"));
} else if ($situacao == 1 && empty($codretexiste)) {

	include (modification("forms/db_caiarq002.php"));
} else if ($situacao == 2) {

	include (modification("forms/db_caiarq003.php"));
}
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<?php
if ($situacao == 1) {

	if (isset ($codretexiste) && $codretexiste != "") {

		$oParametrosMsg                   = new stdClass();
		$oParametrosMsg->sBancoExiste     = $bancoexiste;
		$oParametrosMsg->sAgenciaExiste   = $agenciaexiste;
		$oParametrosMsg->sDtArquivoExiste = db_formatar($dtarquivoexiste, 'd');
		$sMsg                             = _M( MENSAGENS . 'arquivo_ja_existe', $oParametrosMsg);

		echo "<script type=\"text/javascript\">
		alert('$sMsg');
		location.href='cai4_baixabanco001.php';
		</script>";
		flush();
	}
}

if ($situacao == 2) {

	echo "<script type=\"text/javascript\">
	      function js_termometro(xvar){
	       document.form1.processa.value = xvar;
        }
        </script>";

	flush();

	try {

		/**
		 * Grava arquivo disarq
		 */
		db_inicio_transacao();

		if (substr($k15_pdano, 3, 3) == '002') {
			$dtarquivo = '20'.substr($arq_array[0], substr($k15_pdano, 0, 3) - 1, substr($k15_pdano, 3, 3));
		} else {
			$dtarquivo = substr($arq_array[0], substr($k15_pdano, 0, 3) - 1, substr($k15_pdano, 3, 3));
		}

		$dtarquivo .= "-".substr($arq_array[0], substr($k15_pdmes, 0, 3) - 1, substr($k15_pdmes, 3, 3));
		$dtarquivo .= "-".substr($arq_array[0], substr($k15_posdta, 0, 3) - 1, substr($k15_posdta, 3, 3));

		// if ($cgc == '88073291000199') { // bage
		// 	if (substr($arqname, 0, 4) == "daeb") {

		// 		if (substr($arq_array[0], 0, 3) == "826") {
		// 			$dtarquivo = substr($arqname, 4, 8);
		// 		}
		// 	}
		// }

		$clDisArq->id_usuario = db_getsession("DB_id_usuario");
		$clDisArq->k15_codbco = $k15_codbco;
		$clDisArq->k15_codage = $k15_codage;
		$clDisArq->arqret     = $arqname;
		$clDisArq->textoret   = "";
		$clDisArq->dtretorno  = date('Y-m-d', db_getsession("DB_datausu"));
		$clDisArq->dtarquivo  = $dtarquivo;
		$clDisArq->k00_conta  = $k15_conta;
		$clDisArq->autent     = "false";
		$clDisArq->instit     = $iInstitSessao;
		$clDisArq->md5        = db_getsession('disarq_md5');
		$clDisArq->incluir(null);

		if ($clDisArq->erro_status == "0") {

			$oParametrosMsg           = new stdClass();
			$oParametrosMsg->sMsgErro = $clDisArq->erro_msg;
			$sMsg                     = _M( MENSAGENS . 'erro_inclusao_disarq', $oParametrosMsg);
			throw new DBException($sMsg);
		}

		$codret         = $clDisArq->codret;

		$achou_arrecant = 0;

		$sWhereCadBan  = "     k15_codbco = {$k15_codbco}    ";
		$sWhereCadBan .= " and k15_codage = '{$k15_codage}'  ";
		$sWhereCadBan .= " and k15_instit = {$iInstitSessao} ";
		$sSqlCabBan    = $clCadBan->sql_query_file(null, "k15_seq, k15_poscon, k15_contat", null, $sWhereCadBan);
		$rsCadBan      = $clCadBan->sql_record($sSqlCabBan);

		db_fieldsmemory($rsCadBan, 0);

		$k15_numpreori = $k15_numpre;
		$k15_numparori = $k15_numpar;
		$priregistro = 1;

		// if ($cgc == '88073291000199') { // bage
		// 	if (substr($arqname, 0, 4) == "daeb") {
		// 		$priregistro = 0;
		// 	}
		// }

		/**
		 * Processa Registros do Arquivo para Gravar em DISBANCO
		 */
		$passou_pelo_t  = true;
		$k15_numbco_ant = $k15_numbco;

		$total_tx_bancaria = 0;

    $oLayoutCadastrado = new DBLayoutReader(ArquivoBaseCobrancaRegistrada::CODIGO_LAYOUT_TXT, ECIDADE_PATH . "tmp/".$arqret, false, false);

		for ($i = $priregistro; $i <= $totalproc - ($priregistro == 0 ? 1 : 0); $i ++) {

			if ($lDebugAtivo == true) {
				echo "i: $i - cgc: $cgc - passou_pelo_t: $passou_pelo_t<br/>";
			}

			$k15_numbco    = $k15_numbco_ant;
			$tipo_convenio = "";

			/**
			 * Testa tipo do registro
			 */
			if ($k15_taman == 242) {

				if (substr($arq_array[$i], 7, 1) != '3' && (substr($arq_array[$i], 13, 1) != 'U' && substr($arq_array[$i], 13, 1) != 'T')) {

					if ($lDebugAtivo == true) {
						echo "   continuando 1.00110011...<br/>";
					}
					continue;
				}

			} elseif ($k15_taman == 402) {

				if (substr($arq_array[$i], 0, 1) == '9') {
					continue;
				}

			} elseif ($k15_taman == 90) {

				if (substr($arq_array[$i], 0, 5) <> 'BSJI2') {
					continue;
				}
			} elseif (substr($arq_array[$i], 0, 1) <> "G") {

				if (substr($arq_array[$i], 0, 3) <> "104" && substr($arq_array[$i], 0, 3) <> "BSJ") {
					continue;
				}
			}

			$lArquivoArrecadacao = false;

			if ( $k15_taman == 152 ) {
				$lArquivoArrecadacao = true;
			}

			$sCodigoSegmento = substr($arq_array[$i], 13, 1);
			$sCodigoRetorno  = substr($arq_array[$i], 15, 2);

			/**
			 * Importação do retorno da cobrança registrada
			 */
			if (in_array($sCodigoSegmento, array('T', 'U')) && $sCodigoRetorno != '06') {

				if (($sCodigoSegmento != 'T')
				or ($sCodigoRetorno != '02' and $sCodigoRetorno != '03' and $sCodigoRetorno != '09')
				) {
          continue;
        }

        $oLinha  = $oLayoutCadastrado->processarLinha($arq_array[$i], 1, false, true);
        $iNumpre = $oLinha->identificacao_titulo;

        if ( empty($iNumpre) ) {
        	continue;
        }

        $iCodigoMovimento = $oLinha->codigo_movimento;
				$aOcorrencia      = Array();

    		preg_match_all("/\w{2}/", $oLinha->motivo, $aOcorrencia, PREG_PATTERN_ORDER);
    		$aOcorrencia = $aOcorrencia[0];

    		if (empty($aOcorrencia)) {
    			$aOcorrencia[] = '00';
    		}

				$oCobrancaRegistradaRetorno           = new CobrancaRegistradaRetorno($iNumpre, $iCodigoMovimento, $codret, $aOcorrencia);
				$oCobrancaRegistradaRetornoRepository = new CobrancaRegistradaRetornoRepository;
				$oCobrancaRegistradaRetornoRepository->incluir($oCobrancaRegistradaRetorno);
        continue;
			}

			/**
			 * Grava arquivo disbanco
			 */
			$k15_numpre = $k15_numpreori;
			$k15_numpar = $k15_numparori;

			if (@$numpre == "") {
				$numpre = substr($arq_array[$i], substr($k15_numpre, 0, 3) - 1, substr($k15_numpre, 3, 3));
				$numpar = substr($arq_array[$i], substr($k15_numpar, 0, 3) - 1, substr($k15_numpar, 3, 3));
			}

			// bage
			// if ($cgc == '88073291000199') {

			// 	if (substr($numpre, 0, 2) == "00") {

			// 		if (substr($arqname, 0, 4) == "daeb") {

			// 			$k15_numpre = "034008";
			// 			$k15_numpar = "042003";
			// 		} else {

			// 			$k15_numpre = "071008";
			// 			$k15_numpar = "079003";
			// 		}
			// 	}
			// }

			// itaqui
			if ($cgc == '88120662000146') {

				if (substr($numpre, 0, 2) == "00") {

					$k15_numpre = "071008";
					$k15_numpar = "079003";
				}
			}

			// osorio
			if ($cgc == '88814181000130') {

				if (substr($numpre, 0, 2) == "00") {

					$k15_numpre = "052008";
					$k15_numpar = "060003";
				}
			}

			//arroio
			if ($cgc == '91103093000135') {

				// numpre do sistema novo
				if (substr($numpre, 0, 2) == "00") {

					$k15_numpre = "071008";
					$k15_numpar = "079003";
				}
			}

			// capivari
			if ($cgc == '01610503000141') {

				// numpre do sistema novo
				if (substr($numpre, 0, 2) == "00") {

					$k15_numpre = "071008";
					$k15_numpar = "079003";
				}
			}

			// dom feliciano
			if ($cgc == '88601943000110') {

				// numpre do sistema novo
				if ( (int) substr($numpre, 0, 4) == 0 ) {

					$k15_numpre = "071008";
					$k15_numpar = "079003";
					$k15_numbco = "";
				} else { // sistema antigo

					$teste_numpre = (int) substr($arq_array[$i], 65, 1);
					$xxx          = substr($arq_array[$i], 60, 10);

					if ( $teste_numpre == 0 ) {

						$k15_numpre = "070011";
						$k15_numpar = "079003";
					}

				}
			}

			// araruama/rj
			if ($cgc == '28531762000133' && $k15_contat != 'BDL') {

				$k15_seq  = "";
				$convenio = "";

				if ($k15_codbco != 399 || ($k15_codbco == 399 && strcmp(trim($k15_codage), "1252") == 0) ) {

					$numbco   = "";

					// verificar se numpre eh do nosso sistema ou do anterior, posicao 65,5
					$isNumpre = (substr($arq_array[$i], 64, 5) === "00000");

					// Se nao eh numpre do e-cidade...
					if (!$isNumpre) {

						$iTipo = (int)substr($arq_array[$i], 64, 3);

						// Verifica se eh DAM do sistema anterior
						$isDam = ($iTipo == 803);

						if ($isDam) {

							$k15_numpre = "068014";
							$k15_numbco = $k15_numpre;

							$numbco   = substr($arq_array[$i], substr($k15_numbco, 0, 3) - 1, substr($k15_numbco, 3, 3));
							$k15_seq  = "";
							$convenio = "";

							/**
							 * DAM do sistema anterior $numbco
							 */
						} else {

							/* Verificar REFIS em funcao da parcela com 3 digitos */
							if ($iTipo >= 201 && $iTipo <= 380) {

								$k15_numpre = "072008";

								$numpre = substr($arq_array[$i], substr($k15_numpre, 0, 3) - 1, substr($k15_numpre, 3, 3));
								$numpar = (200 - $iTipo);

							} else {

								$k15_numpre = "072008";
								$k15_numpar = "080002";

								$numpre = substr($arq_array[$i], substr($k15_numpre, 0, 3) - 1, substr($k15_numpre, 3, 3));
								$numpar = substr($arq_array[$i], substr($k15_numpar, 0, 3) - 1, substr($k15_numpar, 3, 3));
							}

						}

					}

				}
			} // fim araruama/rj

			if ($cgc == '12198693000158') {

				// arapiraca
				if (substr($arq_array[$i], 13, 1) == 'T') {

					if($k15_codage == '542-8' && substr($numpre,0,3) != '000'){

						if ($lDebugAtivo == true) {
							echo "   continuando 1.001 === 542-8...<br/>";
						}
						$passou_pelo_t = false;
					}
				}

			}

			// coruripe sigcb
			if ($cgc == '12264230000147') {

				if (substr($arq_array[$i], 13, 1) == 'T') {

					if ($lDebugAtivo == true) {
						echo "   continuando 1.002001 === numpre: $numpre...<br/>";
					}

					if($k15_codage == '2117S' && substr($numpre,0,3) != '000') {

						if ($lDebugAtivo == true) {
							echo "   continuando 1.002002 === 2117S...<br/>";
						}

						/**
						 * Necessario para arapiraca sigcb/sicob
						 */
					}
				}
			}

			/**
			 * Duplicado if de arapiraca para que o sistema consiga baixar debitos
			 *   com convenio do tipo sicob pois atualmente ja funciona em arapiraca
			 *   TAREFA 36779
			 */
			// sapiranga
			if ($cgc == '87366159000102') {

				// sapiranga
				if (substr($arq_array[$i], 13, 1) == 'T' || substr($arq_array[$i], 13, 1) == 'U') {

					if ($lDebugAtivo == true) {
						echo "      continuando 1.1 - k15_codage: $k15_codage<br/>";
					}

					if($k15_codage == '00514' && $k15_codbco != '104'){

						$tipo_convenio = "sigcb";
						$passou_pelo_t = false;
					}

				}

			}

			// SIGCB - k15_contat
			if ($k15_contat == 'BDL' && $k15_contat == 'SIGCB') {

				if (substr($arq_array[$i], 13, 1) == 'T' || substr($arq_array[$i], 13, 1) == 'U') {

					$tipo_convenio = strtolower($k15_contat);
					$passou_pelo_t = false;
				}

				if (substr($arq_array[$i], 13, 1) == 'U' && substr($arq_array[$i],15,2) == '50' ) {

					$numpre = "";
					$numpar = "";
					continue;
				}

			}

			// itaqui sigcb
			if ($cgc == '88120662000146') {

				if (substr($arq_array[$i], 13, 1) == 'T' || substr($arq_array[$i], 13, 1) == 'U') {

					if ($lDebugAtivo == true) {

						echo "      continuando 1.2 - k15_codage: $k15_codage<br/>";
						echo "   continuando 1.003003 === ITAQUI ( 104/0484X )... - tipo_convenio: $tipo_convenio<br/>";
					}

					if($k15_codage == '0484X' && substr($numpre,0,3) != '000'){
						$tipo_convenio = "sigcb";
					}
				}
			}

			// arapiraca sigcb
			if ($cgc == '12198693000158') {

				if ($lDebugAtivo == true) {
					echo "      continuando 1.3<br/>";
				}

				if ( substr($arq_array[$i], 13, 1) == 'T' || substr($arq_array[$i], 13, 1) == 'U' ) {

					if ($lDebugAtivo == true) {
						echo "      continuando 1.4 - k15_codage: $k15_codage<br/>";
					}

					if ($k15_codage == '0056') {

						if ($lDebugAtivo == true) {
							echo "      passando para false 1... numpre: $numpre<br/>";
						}
						$tipo_convenio = "sigcb";
					}
				}
			}

			// coruripe sigcb
			if ($cgc == '12264230000147') {

				if ($lDebugAtivo == true) {
					echo "      continuando 1.5<br/>";
				}

				if ( substr($arq_array[$i], 13, 1) == 'T' || substr($arq_array[$i], 13, 1) == 'U' ) {

					if ($lDebugAtivo == true) {
						echo "      continuando 1.6 - k15_codage: $k15_codage<br/>";
					}

					if ($k15_codage == '2117S') {

						if ($lDebugAtivo == true) {
							echo "      passando para false 1... numpre: $numpre<br/>";
						}
						$tipo_convenio = "sigcb";
					}
				}
			}

			if ($k15_taman == 242) {

				if (substr($arq_array[$i], 13, 1) == 'T') {

					$numbco   = substr($arq_array[$i], substr($k15_numbco, 0, 3) - 1, substr($k15_numbco, 3, 3));
					$convenio = substr($arq_array[$i], substr($k15_poscon, 0, 3) - 1, substr($k15_poscon, 3, 3));
					if ($lDebugAtivo == true) {
						echo "   continuando 5...<br/>";
					}
					continue;
				}

			} else {

				$convenio = substr($arq_array[$i], substr($k15_poscon, 0, 3) - 1, substr($k15_poscon, 3, 3));
				$numbco   = substr($arq_array[$i], substr($k15_numbco, 0, 3) - 1, substr($k15_numbco, 3, 3));
			}

			// dom feliciano
			if ($cgc == '88601943000110') {

				// numpre do sistema antigo
				if ( (int) substr($numpre, 0, 4) != 0 ) {

					$teste_numpre = (int) substr($arq_array[$i], 65, 1);

					if ( $teste_numpre == 0 ) {

						$k15_numbco = "071011";
						$numbco     = substr($arq_array[$i], substr($k15_numbco, 0, 3) - 1, substr($k15_numbco, 3, 3));
					}
				}
			}

			if ($lDebugAtivo == true) {
				echo "   continuando 6...<br/>";
			}

			if ($numbco != "") {

				if ($lDebugAtivo == true) {
					echo "   continuando 6.1...<br/>";
				}

				$numbco = $k15_seq.$convenio.$numbco;

                                /* @note: substitui o X por 1 quando vem X no numbanco */
				if (strpos($numbco, 'X') !== false) {
					$numbco = str_replace('X', '1', $numbco);
				}
        $numbcoFormatado = $numbco;

        /**
         * Adicionado if para verificar se o numbco é de 11 posições para ignorar o DV que esta salvo na tabela arrebanco
         */
        if (strlen($numbco) == 11) {
          $numbcoFormatado = trim($numbco) . '_';
        }

				$sSqlBuscaArrebanco  = "select arrebanco.k00_numpre as numpre,                                       ";
				$sSqlBuscaArrebanco .= "       arrebanco.k00_numpar as numpar                                        ";
				$sSqlBuscaArrebanco .= "  from arrebanco                                                             ";
				$sSqlBuscaArrebanco .= "       inner join arreinstit on arreinstit.k00_numpre = arrebanco.k00_numpre ";
				$sSqlBuscaArrebanco .= " where arrebanco.k00_numbco ilike trim('" . trim($numbcoFormatado) . "')     ";
				$sSqlBuscaArrebanco .= "   and arreinstit.k00_instit = $iInstitSessao                                ";
				$rsArrebanco = db_query($sSqlBuscaArrebanco);

				if (pg_numrows($rsArrebanco) != 0) {
          db_fieldsmemory($rsArrebanco, 0);
        }

				/*
				 * [ EXTENSAO Jaguarao ]
				 */
			} else {

				if ($lDebugAtivo == true) {
					echo "   continuando 6.2...<br/>";
				}

				$processaposnumpre = true;

				// Arapiraca
				if ($numpre <> "" && $cgc=='12198693000158') {
					$processaposnumpre = false;
				}

				// Coruripe
				if ($numpre <> "" && $cgc=='12264230000147') {
					$processaposnumpre = false;
				}

				// Alegrete
				if ($numpre <> "" && $cgc=='87896874000157') {
					$processaposnumpre = false;
				}

				if ( $k15_contat == "BDL" || $k15_contat == "SIGCB" ) {
					$processaposnumpre = false;
				}

				/**
				 * Duplicado if de arapiraca para que o sistema consiga baixar debitos
				 *   com convenio do tipo sicob pois atualmente ja funciona em arapiraca
				 *   TAREFA 36779
				 */
				// Sapiranga
				if ($numpre <> "" && $cgc=='87366159000102') {
					$processaposnumpre = false;
				}

				if( $processaposnumpre == true ) {

					$numpre = substr($arq_array[$i], substr($k15_numpre, 0, 3) - 1, substr($k15_numpre, 3, 3));
					$numpar = substr($arq_array[$i], substr($k15_numpar, 0, 3) - 1, substr($k15_numpar, 3, 3));
				}

			}

			if ($lDebugAtivo == true) {
				echo "      continuando 7 - tipo_convenio: $tipo_convenio<br/>";
			}

			if ($k15_codage === "00110" && $k15_codbco == 41) {

				$sqlbanco    = "select arrebanco.k00_numpre, arrebanco.k00_numpar                            ";
				$sqlbanco   .= "  from arrebanco                                                             ";
				$sqlbanco   .= "       inner join arreinstit on arreinstit.k00_numpre = arrebanco.k00_numpre ";
				$sqlbanco   .= " where arrebanco.k00_numbco  = '".substr($arq_array[$i], ( (int) substr($k15_numbco, 0, 3) ) - 1, ( (int) substr($k15_numbco, 3, 3) ) )."'";
				$sqlbanco   .= "   and arreinstit.k00_instit = $iInstitSessao ";
				$resultbanco = db_query($sqlbanco) or die($sqlbanco);

				if (pg_numrows($resultbanco) == 0) {

					$oParametrosMsg            = new stdClass();
					$oParametrosMsg->iNumBanco = substr($arq_array[$i], 6, 13);
					$sMsg                      = _M( MENSAGENS . 'erro_numbanco_nao_encontrado', $oParametrosMsg);
					echo "<script>alert('".$sMsg."');</script>";
				} else {

					db_fieldsmemory($resultbanco, 0, true);
					$numpre = $k00_numpre;
					$numpar = $k00_numpar;
				}

			} else if ($k15_codage === "00712" && $k15_codbco == 41 && $cgc === "01610503000141" ) {

				// Capivari
				$sqlbanco    = "select arrebanco.k00_numpre, arrebanco.k00_numpar                            ";
				$sqlbanco   .= "  from arrebanco                                                             ";
				$sqlbanco   .= "       inner join arreinstit on arreinstit.k00_numpre = arrebanco.k00_numpre ";
				$sqlbanco   .= " where arrebanco.k00_numbco  = '".substr($arq_array[$i], ( (int) substr($k15_numbco, 0, 3) ) - 1, ( (int) substr($k15_numbco, 3, 3) ) )."'";
				$sqlbanco   .= "   and arreinstit.k00_instit = $iInstitSessao ";
				$resultbanco = db_query($sqlbanco) or die($sqlbanco);

				if (pg_numrows($resultbanco) == 0) {

					$oParametrosMsg            = new stdClass();
					$oParametrosMsg->iNumBanco = $numbco;
					$sMsg                      = _M( MENSAGENS . 'erro_arrebanco_numbanco_nao_encontrado', $oParametrosMsg);
					echo "<script>alert('".$sMsg."');</script>";
				} else {

					db_fieldsmemory($resultbanco, 0, true);
					$numpre = $k00_numpre;
					$numpar = $k00_numpar;
				}

			} elseif ($tipo_convenio == "sigcb" && $tipo_convenio == "bdl" ) {

				if ($lDebugAtivo == true) {
					echo "      continuando 8 ( numbco: $numbco )...<br/>";
				}

				$numbco = trim($numbco);

				if ($lDebugAtivo == true) {
					echo "         continuando 8.1 ( numbco: $numbco )...<br/>";
				}

				$sSqlArrebanco  = "select arrebanco.k00_numpre, arrebanco.k00_numpar                            ";
				$sSqlArrebanco .= "  from arrebanco                                                             ";
				$sSqlArrebanco .= "       inner join arreinstit on arreinstit.k00_numpre = arrebanco.k00_numpre ";
				$sSqlArrebanco .= " where arrebanco.k00_numbco = '$numbco'                                      ";
				$sSqlArrebanco .= "   and arreinstit.k00_instit = $iInstitSessao                                ";
				$rsArrebanco    = db_query($sSqlArrebanco);

				if (pg_numrows($rsArrebanco) == 0) {

					$oParametrosMsg            = new stdClass();
					$oParametrosMsg->iNumBanco = $numbco;
					$sMsg                      = _M( MENSAGENS . 'erro_numbanco_nao_encontrado_instit', $oParametrosMsg);
					echo "<script>alert('".$sMsg."');</script>";
				} else {

					db_fieldsmemory($rsArrebanco, 0, true);
					$numpre = $k00_numpre;
					$numpar = $k00_numpar;
				}

				if ($lDebugAtivo == true) {
					echo "            continuando 8.2 ( numbco: $numbco )...<br/>";
				}

			}

			if (substr($arq_array[0], 0, 5) == 'BSJI0') {

				if (substr($k15_plano, 3, 3) == '002') {
					$dtarq = '20'.substr($arq_array[0], substr($k15_plano, 0, 3) - 1, substr($k15_plano, 3, 3));
				} else {
					$dtarq = substr($arq_array[0], substr($k15_plano, 0, 3) - 1, substr($k15_plano, 3, 3));
				}

				$dtarq .= "-".substr($arq_array[0], substr($k15_plmes, 0, 3) - 1, substr($k15_plmes, 3, 3));
				$dtarq .= "-".substr($arq_array[0], substr($k15_poslan, 0, 3) - 1, substr($k15_poslan, 3, 3));

			} else {

				if (substr($k15_plano, 3, 3) == '002') {
					$dtarq = '20'.substr($arq_array[$i], substr($k15_plano, 0, 3) - 1, substr($k15_plano, 3, 3));
				} else {
					$dtarq = substr($arq_array[$i], substr($k15_plano, 0, 3) - 1, substr($k15_plano, 3, 3));
				}

				$dtarq .= "-".substr($arq_array[$i], substr($k15_plmes, 0, 3) - 1, substr($k15_plmes, 3, 3));
				$dtarq .= "-".substr($arq_array[$i], substr($k15_poslan, 0, 3) - 1, substr($k15_poslan, 3, 3));

			}

			if (substr($k15_ppano, 3, 3) == '002') {
				$dtpago = '20'.substr($arq_array[$i], substr($k15_ppano, 0, 3) - 1, substr($k15_ppano, 3, 3));
			} else {
				$dtpago = substr($arq_array[$i], substr($k15_ppano, 0, 3) - 1, substr($k15_ppano, 3, 3));
			}

			$dtpago .= "-".substr($arq_array[$i], substr($k15_ppmes, 0, 3) - 1, substr($k15_ppmes, 3, 3));
			$dtpago .= "-".substr($arq_array[$i], substr($k15_pospag, 0, 3) - 1, substr($k15_pospag, 3, 3));

			if ($dtpago == '0000-00-00') {

				$dtpago = $dtarquivo;
				$dtarq  = $dtarquivo;
			}

			if (substr($k15_anocredito, 3, 3) == '002') {
				$dtcredito = '20'.substr($arq_array[$i], substr($k15_anocredito, 0, 3) - 1, substr($k15_anocredito, 3, 3));
			} else {
				$dtcredito  = substr($arq_array[$i], substr($k15_anocredito, 0, 3) - 1, substr($k15_anocredito, 3, 3));
			}

			$dtcredito .= "-".substr($arq_array[$i], substr($k15_mescredito, 0, 3) - 1, substr($k15_mescredito, 3, 3));
			$dtcredito .= "-".substr($arq_array[$i], substr($k15_diacredito, 0, 3) - 1, substr($k15_diacredito, 3, 3));


			if (empty($dtcredito) || $dtcredito == '0000-00-00') {
				$dtcredito = $dtpago;
			}

			$vlrpago  = (substr($arq_array[$i], substr($k15_posvlr, 0, 3) - 1, substr($k15_posvlr, 3, 3)) / 100) + 0;
			$vlrjuros = (substr($arq_array[$i], substr($k15_posjur, 0, 3) - 1, substr($k15_posjur, 3, 3)) / 100) + 0;
			$vlrmulta = (substr($arq_array[$i], substr($k15_posmul, 0, 3) - 1, substr($k15_posmul, 3, 3)) / 100) + 0;
			$vlracres = (substr($arq_array[$i], substr($k15_posacr, 0, 3) - 1, substr($k15_posacr, 3, 3)) / 100) + 0;
			$vlrdesco = (substr($arq_array[$i], substr($k15_posdes, 0, 3) - 1, substr($k15_posdes, 3, 3)) / 100) + 0;
			$cedente  = $convenio;
			$convenio = "";

			/**
			 * D A E B
			 *
			 * Caso o cnpj seja da instituição DAEB
			 * Incluímos o arquivo cai4_baixabanco_daeb.php que possui a lógica própria para o processamento dos arquivos
			 * de baixa bancária destinados ao DAEB.
			 */
			// if ($cgc == '90940172000138') {

			// 	include(modification("cai4_baixabanco_daeb.php"));
			// 	continue;
			// }

			if ($k15_codage == '88888') {

				/**
				 * Inclui fonte que contém a lógica para quando agencia for 88888 (numpremigra)
				 */
				include(modification("cai4_baixabanco001_agencia_88888.php"));

			} else {

				// Araruama/rj
				if ($cgc == '28531762000133') {

					$numpre_procura = $numpre;
					$sql_recibo     = "select k00_numpre from recibopaga where k00_numnov = $numpre_procura limit 1";
					$result_recibo  = db_query($sql_recibo);

					if (pg_numrows($result_recibo) > 0) {
						$numpre_procura = pg_result($result_recibo, 0);
					}

					$sql_arretipo    = " (select k00_tipo from arrecad    where k00_numpre = $numpre_procura limit 1) ";
					$sql_arretipo   .= "   union                                                                      ";
					$sql_arretipo   .= " (select k00_tipo from arrecant   where k00_numpre = $numpre_procura limit 1) ";
					$sql_arretipo   .= "   union                                                                      ";
					$sql_arretipo   .= " (select k00_tipo from arreold    where k00_numpre = $numpre_procura limit 1) ";
					$sql_arretipo   .= "   union                                                                      ";
					$sql_arretipo   .= " (select k00_tipo from arreforo   where k00_numpre = $numpre_procura limit 1) ";
					$sql_arretipo   .= "   union                                                                      ";
					$sql_arretipo   .= " (select k30_tipo from arreprescr where k30_numpre = $numpre_procura limit 1) ";
					$sql_arretipo   .= "   union                                                                      ";
					$sql_arretipo   .= " (select k00_tipo from recibo     where k00_numpre = $numpre_procura limit 1) ";
					$result_arretipo = db_query($sql_arretipo);

					if (pg_numrows($result_arretipo)>0) {
						$k00_tipo = pg_result($result_arretipo, 0);
					} else {

						/**
						 * Força ser o ARRETIPO 1-IPTU caso NAO ENCONTRE o tipo de debito do NUMPRE
						 */
						$k00_tipo = 1;
					}

					/**
					 * @todo verificar possibilidade de utilizar o cadastro de taxas especificas ao invés do arretipo
					 * remover lógica que busca a taxa bancaria da paritbi pelo cadtipo passando a verificar apenas por arretipo
					 * retirar o campo it24_taxabancaria da tabela paritbi
					 */
					$sSqlGrupoDebito = "select k03_tipo from cadtipo natural join arretipo where k00_tipo = $k00_tipo";
					$rsGrupoTipo     = db_query($sSqlGrupoDebito);
					$iGrupoTipo      = db_utils::fieldsMemory($rsGrupoTipo, 0)->k03_tipo;

					if ($iGrupoTipo == 8) {

						$oDaoParITBI     = new cl_paritbi();
						$oData           = new DBDate($dtpago);
						$iAnoPagamento   = $oData->getAno();
						$rsTxParItbi     = db_query($oDaoParITBI->sql_query($iAnoPagamento, 'it24_taxabancaria'));

						if (pg_numrows($rsTxParItbi) > 0) {
							$k00_txban = db_utils::fieldsMemory($rsTxParItbi, 0)->it24_taxabancaria;
						} else {
							$k00_txban = 0;
						}

					} else {

						$sql_arretipo    = "select k00_txban from arretipo where k00_tipo = $k00_tipo and k00_instit = $iInstitSessao";
						$result_arretipo = db_query($sql_arretipo);

						if (pg_numrows($result_arretipo)>0) {
							$k00_txban = pg_result($result_arretipo, 0);
						} else {
							$k00_txban = 0;
						}
					}

					if ( (float)$vlrpago > $k00_txban ) {

						$total_tx_bancaria += $k00_txban;
						$vlrpago           -= $k00_txban;
					}

					$nTaxaExpediente = $k00_txban;
					$nTarifaBancaria = $k15_txban;

					$clDisBancoTXT->k34_numpremigra = (string) $numpre;
					$clDisBancoTXT->k34_valor       = (string) ($nTaxaExpediente+0);
					$clDisBancoTXT->k34_dtvenc      = $dtpago;
					$clDisBancoTXT->k34_dtpago      = $dtpago;
					$clDisBancoTXT->k34_codret      = $codret;
					$clDisBancoTXT->k34_diferenca   = $nTarifaBancaria+0;
					$clDisBancoTXT->incluir(null);

					if ($clDisBancoTXT->erro_status == "0") {

						$oParametrosMsg            = new stdClass();
						$oParametrosMsg->iLinhaTXT = $arq_array[$i];
						$oParametrosMsg->sMsgErro  = $clDisBancoTXT->erro_msg;
						$sMsg                      = _M( MENSAGENS . 'erro_inclusao_disbancotxt', $oParametrosMsg);
						throw new DBException($sMsg);
					}

					$k34_sequencial = $clDisBancoTXT->k34_sequencial;

					$vlracres = 0;
					$vlrdesco = 0;
				} else {

					/**
					 * Função de tratamento da taxa bancaria
					 */
					$oParametrosTaxaBancaria = new stdClass();
					$oParametrosTaxaBancaria->codret          = $codret;
					$oParametrosTaxaBancaria->numpre          = $numpre;
					$oParametrosTaxaBancaria->k15_codage      = $k15_codage;
					$oParametrosTaxaBancaria->k15_codbco      = $k15_codbco;
					$oParametrosTaxaBancaria->k03_pgtoparcial = $oDadosParametrosNumpref->k03_pgtoparcial;
					$oParametrosTaxaBancaria->dtarq           = $dtarq;
					$oParametrosTaxaBancaria->dtpago          = $dtpago;
					$oParametrosTaxaBancaria->dtcredito       = $dtcredito;

					$iVlrTaxaBancaria = geraTaxaBancaria($oParametrosTaxaBancaria);
					if ($iVlrTaxaBancaria > 0 && $vlrpago > $iVlrTaxaBancaria) {
						$vlrpago -= $iVlrTaxaBancaria;
					}

				}

				/**
				 * Habilita variavel de sessao para permitir numpre's de outras instituições
				 */
				permiteNumpreOutraInstituicao( true );

				$clDisBanco->codret     = $codret;
				$clDisBanco->k15_codbco = $k15_codbco;
				$clDisBanco->k15_codage = $k15_codage;
				$clDisBanco->k00_numbco = $numbco;
				$clDisBanco->dtarq      = $dtarq;
				$clDisBanco->dtpago     = $dtpago;
				$clDisBanco->dtcredito  = $dtcredito;
				$clDisBanco->vlrpago    = "$vlrpago";
				$clDisBanco->vlrjuros   = "$vlrjuros";
				$clDisBanco->vlrmulta   = "$vlrmulta";
				$clDisBanco->vlracres   = "$vlracres";
				$clDisBanco->vlrdesco   = "$vlrdesco";
				$clDisBanco->vlrcalc    = "$vlrpago+$vlrjuros+$vlrmulta+$vlracres-$vlrdesco";
				$clDisBanco->cedente    = $cedente;
				$clDisBanco->vlrtot     = "$vlrpago+$vlrjuros+$vlrmulta+$vlracres-$vlrdesco";
				$clDisBanco->classi     = "false";
				$clDisBanco->k00_numpre = "".($numpre+0)."";
				$clDisBanco->k00_numpar = "".($numpar+0)."";
				$clDisBanco->convenio   = $convenio;
				$clDisBanco->instit     = $iInstitSessao;
				$clDisBanco->incluir(null);

				if ($clDisBanco->erro_status == "0") {

					$oParametrosMsg           = new stdClass();
					$oParametrosMsg->iNumpre  = $clDisBanco->k00_numpre;
					$oParametrosMsg->sMsgErro = $clDisBanco->erro_msg;
					$sMsg                     = _M( MENSAGENS . 'erro_inclusao_disbanco_numpre', $oParametrosMsg);
					throw new DBException($sMsg);
				}

				$idRet = $clDisBanco->idret;

				/**
				 * Inserimos as informações da tarifa bancária
				 */
				if ( $lArquivoArrecadacao ) {

					$nValorTarifaBanco = substr($arq_array[$i], 93, 7);
					$nValorTarifaBanco = (float) chunk_split($nValorTarifaBanco, 5, '.');

					$sFormaArrecadacao = trim(substr($arq_array[$i], 116, 1));

					if(!empty($sFormaArrecadacao)) {

						$oFormaArrecadacaoDao = new cl_formaarrecadacao();
						$sSqlFormaArrecadacao = $oFormaArrecadacaoDao->sql_query_file(null, "*", null, "k178_codigo = '$sFormaArrecadacao'");
						$rsFormaArrecadacao   = $oFormaArrecadacaoDao->sql_record($sSqlFormaArrecadacao);

						if ( !$rsFormaArrecadacao ) {
							throw new \DBException(_M( MENSAGENS . 'erro_forma_arrecadacao'));
						}

						$oFormaArrecadacao = db_utils::fieldsMemory($rsFormaArrecadacao, 0);

						$oDisBancoTarifDao                        = new cl_disbancotarifa();
						$oDisBancoTarifDao->k179_idret            = $idRet;
						$oDisBancoTarifDao->k179_formaarrecadacao = $oFormaArrecadacao->k178_sequencial;
						$oDisBancoTarifDao->k179_valor            = $nValorTarifaBanco;
						$oDisBancoTarifDao->incluir(null);

						if ($oDisBancoTarifDao->erro_status == "0") {

							$oParametrosMsg           = new stdClass();
							$oParametrosMsg->iNumpre  = $clDisBanco->k00_numpre;
							$oParametrosMsg->sMsgErro = $oDisBancoTarifDao->erro_msg;
							$sMsg                     = _M( MENSAGENS . 'erro_inclusao_tarifa', $oParametrosMsg);
							throw new DBException($sMsg);
						}
					}
				}

				/**
				 * Desabilita variavel de sessao para permitir numpre's de outras instituições
				 */
				permiteNumpreOutraInstituicao( false );

				// Araruama/rj
				if ($cgc == '28531762000133') {

					$clDisBancoTXTReg->k35_disbancotxt = $k34_sequencial;
					$clDisBancoTXTReg->k35_idret       = $idRet;
					$clDisBancoTXTReg->incluir(null);
					if ($clDisBancoTXTReg->erro_status == "0") {

						$oParametrosMsg           = new stdClass();
						$oParametrosMsg->sMsgErro = $clDisBancoTXTReg->erro_msg;
						$sMsg                     = _M( MENSAGENS . 'erro_inclusao_disbancotxtreg', $oParametrosMsg);
						throw new DBException($sMsg);
					}
				}

				echo "<script>js_termometro(". $i.");</script>";
				flush();

				$numpre = "";
				$numpar = "";
			}
		}

		if ($lDebugAtivo == true) {

			echo "<br/>F I M<br/>";
			exit;
		}

		$sql  = "  select dtarq,                  ";
		$sql .= "         sum(vlrpago)            ";
		$sql .= "    from disbanco                ";
		$sql .= "   where codret = $codret        ";
		$sql .= "     and instit = $iInstitSessao ";
		$sql .= "group by dtarq                   ";
		$result = db_query($sql);

		$total = 0;

		for ($x = 0; $x < pg_numrows($result); $x ++) {

			db_fieldsmemory($result, $x, true);
			echo "Data: $dtarq - Valor: ".db_formatar($sum, "f")."<br/>";
			$total += $sum;
		}

		$oParametrosMsg                 = new stdClass();
		$oParametrosMsg->iTotalArquivo  = trim(db_formatar($total, "f"));
		$oParametrosMsg->iCodRet        = $codret;

		echo "Total do Arquivo: ".db_formatar($total, "f")." - Codret: $codret<br/>";

		// Araruama/rj
		if ($cgc == '28531762000133') {

			$oParametrosMsg->iTotalTaxaBancaria = trim(db_formatar($total_tx_bancaria, "f"));
			$oParametrosMsg->iTotalArquivo      = trim(db_formatar($total+$total_tx_bancaria, "f"));

			echo "Total Taxa Bancária: ".db_formatar($total_tx_bancaria, "f")."<br/>";
			echo "Total do Arquivo: ".db_formatar($total+$total_tx_bancaria, "f")."<br/>";
		}

		if ($achou_arrecant == 0) {

			/**
			 * Verificamos a forma de processamento do arquivo txt (k03_agrupadorarquivotxtbaixabanco)
			 *
			 * 0 - Classificação por arquivo:           Gerado somente um registros na disarq
			 * 1 - Classificação por data do Pagamento: Gerado mais de um registro na disarq de acordo com a quantidade
			 *                                          de datas de pagamentos encontradas no arquivo (campo: dtpago)
			 * 2 - Classificação por data de Crédito:   Gerado mais de um registro na disarq de acordo com a quantidade
			 *                                          de datas de créditos encontradas no arquivo (campo: dtcredito)
			 */
			if (   $oDadosParametrosNumpref->k03_agrupadorarquivotxtbaixabanco == 1
			    || $oDadosParametrosNumpref->k03_agrupadorarquivotxtbaixabanco == 2) {

				$iCodRet = $codret;
				include_once(modification("cai4_desmembramentodisbanco001.php"));
			}

			db_fim_transacao(false);
			unset($_POST);

			$sTipoMensagem = 'documento_processado';
			if( !empty($oParametrosMsg->iTotalTaxaBancaria) ){
				$sTipoMensagem = 'documento_processado_com_taxa_bancaria';
			}

			db_msgbox( _M( MENSAGENS . $sTipoMensagem, $oParametrosMsg ) );
			db_redireciona();

		} else {
			throw new BusinessException( _M( MENSAGENS . 'documento_nao_processado' ) );
		}

	} catch (DBException $eErro){           // DB Exception

		db_fim_transacao(true);
		echo $eErro->getMessage();
		db_msgbox($eErro->getMessage());
		db_redireciona();

	} catch (BusinessException $eErro){     // Business Exception

		db_fim_transacao(true);
		db_msgbox($eErro->getMessage());

	} catch (ParameterException $eErro){     // Parameter Exception

		db_fim_transacao(true);
		db_msgbox($eErro->getMessage());

	} catch (Exception $eErro){

		db_fim_transacao(true);
		db_msgbox($eErro->getMessage());
	}

}

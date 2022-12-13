<?
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

require_once 'libs/db_stdlib.php';
require_once 'libs/db_conecta.php';
require_once 'libs/db_sessoes.php';
require_once 'libs/db_usuariosonline.php';
require_once 'dbforms/db_funcoes.php';
require_once 'classes/db_selecao_classe.php';
require_once 'classes/db_pontofx_classe.php';
require_once 'classes/db_pontofs_classe.php';
require_once 'classes/db_pontofa_classe.php';
require_once 'classes/db_pontofe_classe.php';
require_once 'classes/db_pontofr_classe.php';
require_once 'classes/db_pontof13_classe.php';
require_once 'classes/db_pontocom_classe.php';
require_once 'classes/db_rhrubricas_classe.php';
require_once 'classes/db_rhpessoal_classe.php';
require_once 'libs/db_utils.php';

$clselecao    = new cl_selecao;
$clpontofx    = new cl_pontofx;
$clpontofs    = new cl_pontofs;
$clpontofa    = new cl_pontofa;
$clpontofe    = new cl_pontofe;
$clpontofr    = new cl_pontofr;
$clpontof13   = new cl_pontof13;
$clpontocom   = new cl_pontocom;
$clrhrubricas = new cl_rhrubricas;
$clrhpessoal  = new cl_rhpessoal;
db_postmemory($HTTP_POST_VARS);

try {
	/**
	 * Valida rubrica
	 */
	$rsRubrica = $clrhrubricas->sql_record($clrhrubricas->sql_query_file($rh27_rubric,db_getsession("DB_instit")));

	if ($clrhrubricas->numrows == 0) {
		throw new Exception("Campo Código da Rubrica Inexistente.");
	}

	if ( isset($processar) ) {

		db_inicio_transacao();

		$erro_msgFA = "";
		$erro_msgFC = "";
		$erro_msgF3 = "";
		$erro_msgFX = "";
		$erro_msgFS = "";

		$erro_ALTEF = "Todos ";

		$erro_conFA = 0;
		$erro_conFC = 0;
		$erro_conF3 = 0;
		$erro_conFX = 0;
		$erro_conFS = 0;

		$whereatualiza = " #s#_instit = ".db_getsession("DB_instit")." ";
		if(isset($valoratu) && trim($valoratu) != ""){
			$erro_ALTEF.= "com valor R$ ".db_formatar($valoratu,"f")." ";
			$whereatualiza.= " and #s#_valor = ".$valoratu;
		}else if(isset($quantatu) && trim($quantatu) != ""){
			$erro_ALTEF.= "com quantidade ".db_formatar($quantatu,"f")." ";
			$whereatualiza.= " and #s#_quant = ".$quantatu;
		}else	if(isset($dtlimatu) && trim($dtlimatu) != ""){
			$erro_ALTEF.= "com Ano/Mês ".$datlimatu." ";
			$whereatualiza.= " and #s#_datlim = '".$datlimatu."'";
		}
		$whereatualizacad = '';

		if (isset($r44_selec) && trim($r44_selec) != "") {

			$result_selecao = $clselecao->sql_record($clselecao->sql_query_file($r44_selec,db_getsession("DB_instit"),"r44_where"));

			if ($clselecao->numrows > 0) {

				db_fieldsmemory($result_selecao, 0);

				/**
				 * inclusao
				 */
				if( !isset($ativos) && $iae == "i" ) {
					 $whereatualiza .= " and ".strtolower($r44_where);
				}

				/**
				 * alteracao
				 */
				if ( $iae == 'a' and $r44_where != '') {
					$whereatualiza .= " and ".strtolower($r44_where);
				}

				/**
				 * exclusao
				 */
				if ( $iae == 'e' and $r44_where != '') {
					$whereatualiza .= " and ".strtolower($r44_where);
				}

				if ($r44_where != '') {
				  $whereatualizacad .= " and ".strtolower($r44_where);
				}

			}
		}

		if(isset($valornov) && trim($valornov) != ""){
			$erro_ALTEF.= "para valor R$ ".db_formatar($valornov,"f");
			$setdadosnovos = $valornov;
		}else if(isset($quantnov) && trim($quantnov) != ""){
			$erro_ALTEF.= "para quantidade ".db_formatar($quantnov,"f");
			$setdadosnovos = $quantnov;
		}else	if(isset($dtlimnov) && trim($dtlimnov) != ""){
			$erro_ALTEF.= "para Ano/Mês ".$datlimnov;
			$setdadosnovos = $datlimnov;
		}

		if(isset($porcentv) && trim($porcentv) != ""){
			if($sosuv == "so"){
				$erro_ALTEF.= "somar $porcentv % ao valor";
				$setdadosnovos = "#s#_valor+";
			}else{
				$erro_ALTEF.= "subtrair $porcentv % do valor";
				$setdadosnovos = "#s#_valor-";
			}
			$setdadosnovos.= "(#s#_valor*".$porcentv.")/100";
		}else if(isset($porcentq) && trim($porcentq) != ""){
			if($sosuq == "so"){
				$erro_ALTEF.= "somar $porcentq % à quantidade";
				$setdadosnovos = "#s#_quant+";
			}else{
				$erro_ALTEF.= "subtrair $porcentq % da quantidade";
				$setdadosnovos = "#s#_quant-";
			}
			$setdadosnovos.= "(#s#_quant*".$porcentq.")/100";
		}

    switch($iae) {

  	/**
  	 * ------------------------------------------------------------------------------------------------------------------
  	 * Inclusao
  	 * ------------------------------------------------------------------------------------------------------------------
  	 */
  	case "i":
			$arr_registrosFA = Array();
			$arr_registrosFC = Array();
			$arr_registrosF3 = Array();
			$arr_registrosFX = Array();
			$arr_registrosFS = Array();

			if (isset($ativos)) {

				$sWhere  = " rh02_anousu = ".db_anofolha();
				$sWhere .= " and rh02_mesusu = ".db_mesfolha();
				$sWhere .= " and rh05_seqpes is null ";
				$sWhere .= $whereatualizacad;

				$result_ativos = $clrhpessoal->sql_record($clrhpessoal->sql_query_pesquisa(null,
						                                                                       "*",
						                                                                       "",
						                                                                       $sWhere,
						                                                                       db_anofolha(),
						                                                                       db_mesfolha()));

				if ($clrhpessoal->numrows == 0) {
					throw new Exception("Nenhum registro encontrado no ano/mês atual.");
				}

				for($i=0; $i<$clrhpessoal->numrows; $i++){

					db_fieldsmemory($result_ativos, $i);
					if(isset($fa)){
							$arr_registrosFA[$rh01_regist] = $rh02_lota;
					}
					if(isset($fc)){
							$arr_registrosFC[$rh01_regist] = $rh02_lota;
					}
					if(isset($f3)){
							$arr_registrosF3[$rh01_regist] = $rh02_lota;
					}
					if(isset($fx)){
							$arr_registrosFX[$rh01_regist] = $rh02_lota;
					}
					if(isset($fs)){
							$arr_registrosFS[$rh01_regist] = $rh02_lota;
					}
				}


			} else {

				if (isset($fa)) {

					$whereatualizaFA = str_replace("#s#","r21",$whereatualiza);
					$sWhere  = " r21_anousu = ".db_anofolha();
					$sWhere .= " and r21_mesusu = ".db_mesfolha();
					$sWhere .= " and r21_instit = ".db_getsession("DB_instit");
					$sWhere .= " and $whereatualizaFA ";
					$result_PTFA = $clpontofa->sql_record($clpontofa->sql_query_seleciona(null,
							                                                                  null,
							                                                                  null,
							                                                                  null,
							                                                                  "distinct r21_regist as regist,r21_lotac as lotac",
							                                                                  "",
							                                                                  $sWhere));
					$iLinhas = $clpontofa->numrows;
					for($i=0; $i<$iLinhas; $i++){
						db_fieldsmemory($result_PTFA, $i);
						$arr_registrosFA[$regist] = $lotac;
					}

				}

				if (isset($fc)) {

					$whereatualizaFC = str_replace("#s#","r47",$whereatualiza);
					$sWhere  = " r47_anousu = ".db_anofolha();
					$sWhere .= " and r47_mesusu = ".db_mesfolha();
					$sWhere .= " and r47_instit = ".db_getsession("DB_instit");
					$sWhere .= " and $whereatualizaFC";
					$result_PTFC = $clpontocom->sql_record($clpontocom->sql_query_seleciona(null,
							                                                                    null,
							                                                                    null,
							                                                                    null,
							                                                                    "distinct r47_regist as regist,r47_lotac as lotac",
							                                                                    "",
							                                                                    $sWhere));
					$iLinhas = $clpontocom->numrows;
					for($i=0; $i<$iLinhas; $i++){
						db_fieldsmemory($result_PTFC, $i);
						$arr_registrosFC[$regist] = $lotac;
					}

				}

				if (isset($f3)) {

					$whereatualizaF3 = str_replace("#s#","r34",$whereatualiza);
					$sWhere = " r34_anousu = ".db_anofolha();
					$sWhere = " and r34_mesusu = ".db_mesfolha();
					$sWhere = " and r34_instit = ".db_getsession("DB_instit");
					$sWhere = " and $whereatualizaF3";
					$result_PTF3 = $clpontof13->sql_record($clpontof13->sql_query_seleciona(null,
							                                                                    null,
							                                                                    null,
							                                                                    null,
							                                                                    "distinct r34_regist as regist,r34_lotac as lotac",
							                                                                    "",
							                                                                    $sWhere));
					$iLinhas = $clpontof13->numrows;
					for($i=0; $i<$iLinhas; $i++){
						db_fieldsmemory($result_PTF3, $i);
						$arr_registrosF3[$regist] = $lotac;
					}

				}

				if (isset($fx)) {

					$whereatualizaFX = str_replace("#s#","r90",$whereatualiza);
					$whereatualizaFX = ' and '.$whereatualizaFX;
					$sWhere  = " r90_anousu = ".db_anofolha();
					$sWhere .= " and r90_mesusu = ".db_mesfolha();
					$sWhere .= " and r90_instit = ".db_getsession("DB_instit");
					$sWhere .= $whereatualizaFX;
					$result_PTFX = $clpontofx->sql_record($clpontofx->sql_query_seleciona(null,
							                                                                  null,
							                                                                  null,
							                                                                  null,
							                                                                  "distinct r90_regist as regist,r90_lotac as lotac",
							                                                                  "",
							                                                                  $sWhere));
					$iLinhas = $clpontofx->numrows;
					for($i=0; $i<$iLinhas; $i++){
						db_fieldsmemory($result_PTFX, $i);
						$arr_registrosFX[$regist] = $lotac;
					}

				}

				/**
				 * Ponto salarios $fs
				 */
				if ( isset($fs)) {

					$whereatualizaFS = str_replace("#s#","r10",$whereatualiza);
					$sWhere  = " r10_anousu = ".db_anofolha();
					$sWhere .= " and r10_mesusu = ".db_mesfolha();
					$sWhere .= " and r10_instit = ".db_getsession("DB_instit");
					$sWhere .= " and $whereatualizaFS";
					$result_PTFS = $clpontofs->sql_record($clpontofs->sql_query_seleciona(null,
							                                                                  null,
							                                                                  null,
							                                                                  null,
							                                                                  "distinct r10_regist as regist,r10_lotac as lotac",
							                                                                  "",
							                                                                  $sWhere));
					$iLinhas = $clpontofs->numrows;
					for($i=0; $i<$iLinhas; $i++){
						db_fieldsmemory($result_PTFS, $i);
						$arr_registrosFS[$regist] = $lotac;
					}

				}

			}

			reset($arr_registrosFA);
			for ($i=0; $i<count($arr_registrosFA); $i++) {
				$registrocorrente = key($arr_registrosFA);
				$lotacregcorrente = $arr_registrosFA[$registrocorrente];

				$whereatualizaFA = str_replace("#s#","r21",$whereatualiza);
				$sWhere  = " r21_anousu = ".db_anofolha();
				$sWhere .= " and r21_mesusu = ".db_mesfolha();
				$sWhere .= " and r21_regist = ".$registrocorrente;
				$sWhere .= " and r21_rubric = '".$rh27_rubric."'";
				$sWhere .= " and r21_instit = ".db_getsession("DB_instit");
				$sWhere .= " and $whereatualizaFA";
				$result_PTFAtest = $clpontofa->sql_record($clpontofa->sql_query_seleciona(null,
																																									null,
																																									null,
																																									null,
																																									"distinct r21_regist as regist,r21_lotac as lotac",
																																									"",
																																									$sWhere));
				$iLinhas = $clpontofa->numrows; 
				if ($iLinhas == 0) {

					if (isset($valornov) && trim($valornov) != "") {
						$clpontofa->r21_valor  = "round($setdadosnovos,2)";
						$clpontofa->r21_quant  = "0";
					} else if (isset($quantnov) && trim($quantnov) != "") {
						$clpontofa->r21_valor  = "0";
						$clpontofa->r21_quant  = "$setdadosnovos";
					}
					$clpontofa->r21_lotac  = $lotacregcorrente;
					$clpontofa->r21_instit = db_getsession("DB_instit");
					$clpontofa->incluir(db_anofolha(),db_mesfolha(),$registrocorrente,$rh27_rubric);
					if ($clpontofa->erro_status==0) {
						throw new Exception($clpontofa->erro_msg);
					}
					$erro_conFA++;
				}

				next($arr_registrosFA);
			}
			$erro_msgFA = $erro_conFA." inclusões no Ponto Adiantamento";

			reset($arr_registrosFC);
			for ($i=0; $i<count($arr_registrosFC); $i++) {
				$registrocorrente = key($arr_registrosFC);
				$lotacregcorrente = $arr_registrosFC[$registrocorrente];

				$whereatualizaFC = str_replace("#s#","r47",$whereatualiza);
				$sWhere  = " r47_anousu = ".db_anofolha();
				$sWhere .= " and r47_mesusu = ".db_mesfolha();
				$sWhere .= " and r47_regist = ".$registrocorrente;
				$sWhere .= " and r47_rubric = '".$rh27_rubric."'";
				$sWhere .= " and r47_instit = ".db_getsession("DB_instit");
				$sWhere .= " and $whereatualizaFC";
				$result_PTFCtest = $clpontocom->sql_record($clpontocom->sql_query_seleciona(null,
						                                                                        null,
						                                                                        null,
						                                                                        null,
						                                                                        "distinct r47_regist as regist,r47_lotac as lotac",
						                                                                        "",
						                                                                        $sWhere));
				if ($clpontocom->numrows == 0) {

					if (isset($valornov) && trim($valornov) != "") {
						$clpontocom->r47_valor  = "round($setdadosnovos,2)";
						$clpontocom->r47_quant  = "0";
					} else if(isset($quantnov) && trim($quantnov) != "") {
						$clpontocom->r47_valor  = "0";
						$clpontocom->r47_quant  = "$setdadosnovos";
					}
					$clpontocom->r47_lotac  = $lotacregcorrente;
					$clpontocom->r47_instit = db_getsession("DB_instit");
					$clpontocom->incluir(db_anofolha(),db_mesfolha(),$registrocorrente,$rh27_rubric);
					if ($clpontocom->erro_status==0) {
						throw new Exception($clpontocom->erro_msg);
					}
					$erro_conFC++;
				}

				next($arr_registrosFC);

			}
			$erro_msgFC = $erro_conFC." inclusões no Ponto Complementar";

			reset($arr_registrosF3);
			for ($i=0; $i<count($arr_registrosF3); $i++) {
				$registrocorrente = key($arr_registrosF3);
				$lotacregcorrente = $arr_registrosF3[$registrocorrente];

				$whereatualizaF3 = str_replace("#s#","r34",$whereatualiza);
				$sWhere  = " r34_anousu = ".db_anofolha();
				$sWhere .= " and r34_mesusu = ".db_mesfolha();
				$sWhere .= " and r34_regist = $registrocorrente";
				$sWhere .= " and r34_rubric = '".$rh27_rubric."'";
				$sWhere .= " and r34_instit = ".db_getsession("DB_instit");
				$sWhere .= " and $whereatualizaF3";
				$result_PTF3test = $clpontof13->sql_record($clpontof13->sql_query_seleciona(null,
						                                                                        null,
						                                                                        null,
						                                                                        null,
						                                                                        "distinct r34_regist as regist,r34_lotac as lotac",
						                                                                        "",
						                                                                        $sWhere));
				if ($clpontof13->numrows == 0) {

					if (isset($valornov) && trim($valornov) != "") {
						$clpontof13->r34_valor  = "round($setdadosnovos,2)";
						$clpontof13->r34_quant  = "0";
					} else if(isset($quantnov) && trim($quantnov) != "") {
						$clpontof13->r34_valor  = "0";
						$clpontof13->r34_quant  = "$setdadosnovos";
					}
					$clpontof13->r34_media  = "0";
					$clpontof13->r34_calc   = "0";
					$clpontof13->r34_lotac  = $lotacregcorrente;
					$clpontof13->r34_instit = db_getsession("DB_instit");
					$clpontof13->incluir(db_anofolha(),db_mesfolha(),$registrocorrente,$rh27_rubric);
					if($clpontof13->erro_status==0){
						throw new Exception($clpontof13->erro_msg);
					}
					$erro_conF3++;

				}

				next($arr_registrosF3);
			}
			$erro_msgF3 = $erro_conF3." inclusões no Ponto 13o";

			reset($arr_registrosFX);
			for ($i=0; $i<count($arr_registrosFX); $i++) {
				$registrocorrente = key($arr_registrosFX);
				$lotacregcorrente = $arr_registrosFX[$registrocorrente];

				$whereatualizaFX = str_replace("#s#","r90",$whereatualiza);
				$sWhere  = " r90_anousu = ".db_anofolha();
				$sWhere .= " and r90_mesusu = ".db_mesfolha();
				$sWhere .= " and r90_regist = ".$registrocorrente;
				$sWhere .= " and r90_rubric = '".$rh27_rubric."'";
				$sWhere .= " and r90_instit = ".db_getsession("DB_instit");
				$sWhere .= " and $whereatualizaFX";
				$result_PTFXtest = $clpontofx->sql_record($clpontofx->sql_query_seleciona(null,
						                                                                      null,
						                                                                      null,
						                                                                      null,
						                                                                      "distinct r90_regist as regist,r90_lotac as lotac",
						                                                                      "",$sWhere));
				if ($clpontofx->numrows == 0) {

					if (isset($valornov) && trim($valornov) != "") {
						$clpontofx->r90_valor  = "round($setdadosnovos,2)";
						$clpontofx->r90_quant  = "0";
					} else if(isset($quantnov) && trim($quantnov) != "") {
						$clpontofx->r90_valor  = "0";
						$clpontofx->r90_quant  = "$setdadosnovos";
					}

					if ($rh27_limdat == "t") {
						$clpontofx->r90_datlim = "9999/12";
					} else {
						$clpontofx->r90_datlim = "";
					}

					$clpontofx->r90_lotac  = $lotacregcorrente;
					$clpontofx->r90_instit = db_getsession("DB_instit");
					$clpontofx->r90_rubric = $rh27_rubric;

					$clpontofx->incluir(db_anofolha(),db_mesfolha(),$registrocorrente,$rh27_rubric);
					if($clpontofx->erro_status==0){
						throw new Exception($clpontofx->erro_msg);
					}
					$erro_conFX++;

				}

				next($arr_registrosFX);
			}
			$erro_msgFX = $erro_conFX." inclusões no Ponto Fixo";

			reset($arr_registrosFS);
			for ($i=0; $i<count($arr_registrosFS); $i++) {
				$registrocorrente = key($arr_registrosFS);
				$lotacregcorrente = $arr_registrosFS[$registrocorrente];

				$whereatualizaFS = str_replace("#s#","r10",$whereatualiza);
				$sWhere  = " r10_anousu = ".db_anofolha();
				$sWhere .= " and r10_mesusu = ".db_mesfolha();
				$sWhere .= " and r10_regist = ".$registrocorrente;
				$sWhere .= " and r10_rubric = '".$rh27_rubric."'";
				$sWhere .= " and r10_instit = ".db_getsession("DB_instit");
				$sWhere .= " and $whereatualizaFS";
				$result_PTFStest = $clpontofs->sql_record($clpontofs->sql_query_seleciona(null,
						                                                                      null,
						                                                                      null,
						                                                                      null,
						                                                                      "distinct r10_regist as regist,r10_lotac as lotac",
						                                                                      "",
						                                                                      $sWhere));
				if ($clpontofs->numrows == 0) {

					if (isset($valornov) && trim($valornov) != "") {
						$clpontofs->r10_valor  = "round($setdadosnovos,2)";
						$clpontofs->r10_quant  = "0";
					} else if(isset($quantnov) && trim($quantnov) != "") {
						$clpontofs->r10_valor  = "0";
						$clpontofs->r10_quant  = "$setdadosnovos";
					}

					if ($rh27_limdat == "t") {
						$clpontofs->r10_datlim = "9999/12";
					} else {
						$clpontofs->r10_datlim = "";
					}

					$clpontofs->r10_lotac  = $lotacregcorrente;
					$clpontofs->r10_instit = db_getsession("DB_instit");
					$clpontofs->incluir(db_anofolha(),db_mesfolha(),$registrocorrente,$rh27_rubric);
					if ($clpontofs->erro_status==0) {
            throw new Exception($clpontofs->erro_msg);
					}
					$erro_conFS++;

				}

				next($arr_registrosFS);
			}
			$erro_msgFS = $erro_conFS." inclusões no Ponto Salário";

		break;

		/**
		 * ------------------------------------------------------------------------------------------------------------------
		 * Alteração
		 * ------------------------------------------------------------------------------------------------------------------
		 */
		case "a":

			/**
			 * Ponto adiantamento
			 */
			if ( isset($fa)) {

				$whereatualizaFA = str_replace("#s#","r21",$whereatualiza);
				$setaratualizaFA = str_replace("#s#","r21",$setdadosnovos);

				$sWhere  = "     r21_anousu = ".db_anofolha();
				$sWhere .= " and r21_mesusu = ".db_mesfolha();
				$sWhere .= " and r21_rubric = '".$rh27_rubric."'";
				$sWhere .= " and r21_instit = ".db_getsession("DB_instit");
				$sWhere .= " and $whereatualizaFA";
				$rsTestesFA = $clpontofa->sql_record($clpontofa->sql_query_seleciona(null,
															              																 null,
															              																 null,
															              																 null,
															              																 "distinct r21_regist as regist,r21_lotac as lotac",
															              																 "",
															              																 $sWhere));
				$iLinhas = $clpontofa->numrows;
				if ( $iLinhas == 0 ) {
					throw new Exception($clpontofa->erro_msg);
				}

				$aFuncionariosSelecao = db_utils::getCollectionByRecord($rsTestesFA);
				$iContador = 0;

				foreach ($aFuncionariosSelecao as $oFuncionariosSelecao) {

					$clpontofa->r21_anousu = db_anofolha();
					$clpontofa->r21_mesusu = db_mesfolha();
					$clpontofa->r21_regist = $oFuncionariosSelecao->regist;
					$clpontofa->r21_instit = db_getsession("DB_instit");
					$clpontofa->r21_rubric = $rh27_rubric;

					if (    (isset($valoratu) && trim($valoratu) != "")
							 || (isset($valornov) && trim($valornov) != "")
							 || (isset($porcentv) && trim($porcentv) != "") ) {

						$clpontofa->r21_valor  = "round($setaratualizaFA, 2)";

					} else if (     isset($quantatu) && trim($quantatu) != ""
							        || (isset($quantnov) && trim($quantnov) != "")
							        || (isset($porcentq) && trim($porcentq) != "") ) {

						$clpontofa->r21_quant = $setaratualizaFA;
					}

					$clpontofa->alterar(db_anofolha(),db_mesfolha(), $oFuncionariosSelecao->regist, $rh27_rubric);
					if ( $clpontofa->erro_status == 0 ) {
						throw new Exception("Erro :".$clpontofa->erro_msg);
					}

					$iContador += $clpontofa->numrows_alterar;

				}

				$erro_msgFA = "{$iContador} alterações no Ponto Adiantamento";

			}

			/**
			 * Ponto complementar
			 */
			if ( isset($fc)) {

				$whereatualizaFC = str_replace("#s#","r47",$whereatualiza);
				$whereatualizaFC = ' and '.$whereatualizaFC;
				$setaratualizaFC = str_replace("#s#","r47",$setdadosnovos);

				$rsTestesFC = $clpontocom->sql_record($clpontocom->sql_query_seleciona(null,
																															                 null,
																															                 null,
																															                 null,
																															                 "distinct r47_regist as regist,r47_lotac as lotac",
																															                 "",
																															                 "   r47_anousu = ".db_anofolha()."
																															                 and r47_mesusu = ".db_mesfolha()."
																															                 and r47_rubric = '".$rh27_rubric."'
																															                 and r47_instit = ".db_getsession("DB_instit").$whereatualizaFC));
				$iLinhas = $clpontocom->numrows;
				if ( $iLinhas == 0 ) {
					throw new Exception($clpontocom->erro_msg);
				}

				
				$aFuncionariosSelecao = db_utils::getCollectionByRecord($rsTestesFC);
				$iContador = 0;

			  foreach ($aFuncionariosSelecao as $oFuncionariosSelecao) {

			  	$clpontocom->r47_anousu = db_anofolha();
			  	$clpontocom->r47_mesusu = db_mesfolha();
			  	$clpontocom->r47_regist = $oFuncionariosSelecao->regist;
			  	$clpontocom->r47_instit = db_getsession("DB_instit");
			  	$clpontocom->r47_rubric = $rh27_rubric;

			  	if ( (isset($valoratu) && trim($valoratu) != "") || (isset($valornov) && trim($valornov) != "") || (isset($porcentv) && trim($porcentv) != "") ) {

			  		$clpontocom->r47_valor = "round($setaratualizaFC,2)";

			  	} else if( isset($quantatu) && trim($quantatu) != "" || (isset($quantnov) && trim($quantnov) != "") || (isset($porcentq) && trim($porcentq) != "") ) {

			  		$clpontocom->r47_quant = $setaratualizaFC;
			  	}

			  	$clpontocom->alterar(db_anofolha(), db_mesfolha(), $oFuncionariosSelecao->regist, $rh27_rubric);
			  	if ( $clpontocom->erro_status == 0 ) {
			  		throw new Exception("Erro :".$clpontocom->erro_msg);
			  	}

			  	$iContador += $clpontocom->numrows_alterar;

			  }

			  $erro_msgFC = "{$iContador} alterações no Ponto Complementar";
			  
			}

			/**
			 * Ponto 13o
			 */
			if ( isset($f3)) {

				$whereatualizaF3 = str_replace("#s#","r34",$whereatualiza);
				$whereatualizaF3 = ' and '.$whereatualizaF3;
				$setaratualizaF3 = str_replace("#s#","r34",$setdadosnovos);

				$rsTestesF3 = $clpontof13->sql_record($clpontof13->sql_query_seleciona(null,
																														               	   null,
																														               	   null,
																														               	   null,
																														               	   "distinct r34_regist as regist,r34_lotac as lotac",
																														               	   "",
																														               	   "   r34_anousu = ".db_anofolha()."
																														               	   and r34_mesusu = ".db_mesfolha()."
																														               	   and r34_rubric = '".$rh27_rubric."'
																														               	   and r34_instit = ".db_getsession("DB_instit").$whereatualizaF3));
				$iLinhas = $clpontof13->numrows;
				if ( $iLinhas == 0 ) {
					throw new Exception($clpontof13->erro_msg);
				}

				$aFuncionariosSelecao = db_utils::getCollectionByRecord($rsTestesF3);
				$iContador = 0;

				foreach ($aFuncionariosSelecao as $oFuncionariosSelecao) {

					$clpontof13->r34_anousu = db_anofolha();
					$clpontof13->r34_mesusu = db_mesfolha();
					$clpontof13->r34_regist = $oFuncionariosSelecao->regist;
					$clpontof13->r34_instit = db_getsession("DB_instit");
					$clpontof13->r34_rubric = $rh27_rubric;

					if (    (isset($valoratu) && trim($valoratu) != "")
							 || (isset($valornov) && trim($valornov) != "")
							 || (isset($porcentv) && trim($porcentv) != "") ) {

						$clpontof13->r34_valor = "round($setaratualizaF3, 2)";

					} else if (    (isset($quantatu) && trim($quantatu) != "")
							        || (isset($quantnov) && trim($quantnov) != "")
							        || (isset($porcentq) && trim($porcentq) != "") ) {

						$clpontof13->r34_quant = $setaratualizaF3;
					}

					$clpontof13->alterar(db_anofolha(), db_mesfolha(), $oFuncionariosSelecao->regist, $rh27_rubric);
					if ( $clpontof13->erro_status == 0 ) {
						throw new Exception("Erro :".$clpontof13->erro_msg);
					}

					$iContador += $clpontof13->numrows_alterar;

				}

				$erro_msgF3 = $clpontof13->numrows_alterar." alterações no Ponto 13o";
				
			}

			/**
			 * Ponto fixo
			 */
			if ( isset($fx)) {

				$whereatualizaFX = str_replace("#s#","r90",$whereatualiza);
				$setaratualizaFX = str_replace("#s#","r90",$setdadosnovos);

				$sWhere  = "     r90_anousu = ".db_anofolha();
				$sWhere .= " and r90_mesusu = ".db_mesfolha();
				$sWhere .= " and r90_rubric = '".$rh27_rubric."'";
				$sWhere .= " and r90_instit = ".db_getsession("DB_instit");
				$sWhere .= " and $whereatualizaFX";
				
				$rsTestesFX = $clpontofx->sql_record($clpontofx->sql_query_seleciona(null,
																															               null,
																															               null,
																															               null,
																															               "distinct r90_regist as regist,r90_lotac as lotac",
																															               "",
																															               $sWhere));
				$iLinhas = $clpontofx->numrows;
				if ( $iLinhas == 0 ) {
					throw new Exception("2 - Erro ao Buscar os Dados:" . $clpontofx->erro_msg);
				}

				$aFuncionariosSelecao = db_utils::getCollectionByRecord($rsTestesFX);
				$iContador            = 0;

				foreach ($aFuncionariosSelecao as $oFuncionariosSelecao) {

					$clpontofx->r90_anousu = db_anofolha();
					$clpontofx->r90_mesusu = db_mesfolha();
					$clpontofx->r90_regist = $oFuncionariosSelecao->regist;
					$clpontofx->r90_instit = db_getsession("DB_instit");
					$clpontofx->r90_rubric = $rh27_rubric;

					if (    (isset($valoratu) && trim($valoratu) != "")
							 || (isset($valornov) && trim($valornov) != "")
							 || (isset($porcentv) && trim($porcentv) != "") ) {

						$clpontofx->r90_valor  = "round($setaratualizaFX,2)";

					} else if (    (isset($quantatu) && trim($quantatu) != "")
							        || (isset($quantnov) && trim($quantnov) != "")
							        || (isset($porcentq) && trim($porcentq) != "") ) {

						$clpontofx->r90_quant  = "$setaratualizaFX";

					} else	if ( isset($dtlimatu) && trim($dtlimatu) != "" ) {

						$clpontofx->r90_datlim = "$setaratualizaFX";
					}

					$clpontofx->alterar(db_anofolha(),db_mesfolha(), $oFuncionariosSelecao->regist, $rh27_rubric);
					if ($clpontofx->erro_status==0) {
						throw new Exception($clpontofx->erro_msg);
					}

					$iContador += $clpontofx->numrows_alterar;

				}

				$erro_msgFX = "{$iContador} alterações no Ponto Fixo";
				
			}

			/**
			 * Ponto rescisao
			 */
			if ( isset($fr)) {

				$whereatualizaFR = str_replace("#s#","r19",$whereatualiza);
				$setaratualizaFR = str_replace("#s#","r19",$setdadosnovos);

				$sWhere  = "     r19_anousu = ".db_anofolha();
				$sWhere .= " and r19_mesusu = ".db_mesfolha();
				$sWhere .= " and r19_rubric = '".$rh27_rubric."'";
				$sWhere .= " and r19_instit = ".db_getsession("DB_instit");
				$sWhere .= " and $whereatualizaFS";
				$rsTestesFR = $clpontofr->sql_record($clpontofr->sql_query_seleciona(null,
																															               null,
																															               null,
																															               null,
																															               "distinct r19_regist as regist,r19_lotac as lotac",
																															               "",
																															               $sWhere));
				$iLinhas = $clpontofr->numrows;
				if ( $iLinhas == 0 ) {
					throw new Exception($clpontofr->erro_msg);
				}

				$aFuncionariosSelecao = db_utils::getCollectionByRecord($rsTestesFR);
				$iContador = 0;

				foreach ($aFuncionariosSelecao as $oFuncionariosSelecao) {

					$clpontofr              = new cs_pontofr();
					$clpontofr->r19_anousu = db_anofolha();
					$clpontofr->r19_mesusu = db_mesfolha();
					$clpontofr->r19_regist = $oFuncionariosSelecao->regist;
					$clpontofr->r19_instit = db_getsession("DB_instit");
					$clpontofr->r19_rubric = $rh27_rubric;

					if ( isset($valoratu) && trim($valoratu) != "" ) {

						$clpontofr->r19_valor  = "round($setaratualizaFR, 2)";

					} else if ( isset($quantatu) && trim($quantatu) != "" ) {

						$clpontofr->r19_quant  = $setaratualizaFR;
					}

					$clpontofr->alterar( db_anofolha(), db_mesfolha(), $oFuncionariosSelecao->regist, $rh27_rubric);
					if ( $clpontofr->erro_status == 0 ) {
						throw new Exception("Erro :".$clpontofr->erro_msg);
					}

					$iContador += $clpontofr->numrows_alterar;

				}

				$erro_msgFR = "{$iContador} alterações no Ponto Rescisão";
				
			}

			/**
			 * Ponto salario
			 */
			if ( isset($fs)) {

				$whereatualizaFS = str_replace("#s#","r10",$whereatualiza);
				$setaratualizaFS = str_replace("#s#","r10",$setdadosnovos);

				$sWhere  = "     r10_anousu = ".db_anofolha();
				$sWhere .= " and r10_mesusu = ".db_mesfolha();
				$sWhere .= " and r10_rubric = '".$rh27_rubric."'";
				$sWhere .= " and r10_instit = ".db_getsession("DB_instit");
				$sWhere .= " and $whereatualizaFS";
				$rsTestesFS = $clpontofs->sql_record($clpontofs->sql_query_seleciona(null,
																													              		 null,
																													              		 null,
																													              		 null,
																													              		 "distinct r10_regist as regist,r10_lotac as lotac",
																													              		 "",
																													              		 $sWhere));
				$iLinhas = $clpontofs->numrows;
				if ( $iLinhas == 0 ) {
					throw new Exception($clpontofs->erro_msg);
				}

				$aFuncionariosSelecao = db_utils::getCollectionByRecord($rsTestesFS);
				$iContador = 0;

				foreach ($aFuncionariosSelecao as $oFuncionariosSelecao) {

					$clpontofs             = new cl_pontofs();
					$clpontofs->r10_anousu = db_anofolha();
					$clpontofs->r10_mesusu = db_mesfolha();
					$clpontofs->r10_regist = $oFuncionariosSelecao->regist;
					$clpontofs->r10_instit = db_getsession("DB_instit");
					$clpontofs->r10_rubric = $rh27_rubric;

					if (    (isset($valoratu) && trim($valoratu) != "")
							 || (isset($valornov) && trim($valornov) != "")
							 || (isset($porcentv) && trim($porcentv) != "") ) {

						$clpontofs->r10_valor  = "round($setaratualizaFS, 2)";

					} else if(    (isset($quantatu) && trim($quantatu) != "")
							       || (isset($quantnov) && trim($quantnov) != "")
							       || (isset($porcentq) && trim($porcentq) != "") ) {

						$clpontofs->r10_quant  = $setaratualizaFS;

					} else if( isset($dtlimatu) && trim($dtlimatu) != "" ) {

						$clpontofs->r10_datlim = $setaratualizaFS;
					}

					$clpontofs->alterar( db_anofolha(), db_mesfolha(), $oFuncionariosSelecao->regist,  $rh27_rubric);
					if ( $clpontofs->erro_status == "0" ) {
						throw new Exception("Erro :".$clpontofs->erro_msg);
					}

					$iContador += $clpontofs->numrows_alterar;

				}

				$erro_msgFS = "{$iContador} alterações no Ponto Salário";
				
				
			}
			
     break;

		/**
		 * ------------------------------------------------------------------------------------------------------------------
		 * Exclusao
		 * ------------------------------------------------------------------------------------------------------------------
		 */
			case "e":

			  /**
			   * Ponto adiantamento
			   */
			  if ( isset($fa)) {

			  	$whereatualizaFA = str_replace("#s#","r21",$whereatualiza);
          $sWhere  = "     r21_anousu = ".db_anofolha();
			  	$sWhere .= " and r21_mesusu = ".db_mesfolha();
			  	$sWhere .= " and r21_rubric = '".$rh27_rubric."'";
			  	$sWhere .= " and r21_instit = ".db_getsession("DB_instit");
			  	$sWhere .= " and $whereatualizaFA";
			  	
			  	$sSqlTesteFA = $clpontofa->sql_query_seleciona(null,
			  																								 null,
			  																								 null,
			  																								 null,
			  																								 "distinct r21_regist as regist,r21_lotac as lotac",
			  																								 "",
			  																								 $sWhere);
			  	$rsTestesFA = $clpontofa->sql_record($sSqlTesteFA);
			  	$iLinhas = $clpontofa->numrows; 
			  	if ( $iLinhas == 0 ) {
			  		throw new Exception($clpontofa->erro_msg);
			  	}

			  	$aFuncionariosSelecao = db_utils::getCollectionByRecord($rsTestesFA);
			  	$iContador = 0;

			  	foreach ($aFuncionariosSelecao as $oFuncionariosSelecao) {

			  		$clpontofa->excluir(db_anofolha(), db_mesfolha(), $oFuncionariosSelecao->regist, $rh27_rubric);
			  		if ( $clpontofa->erro_status == 0 ) {
			  			throw new Exception("Erro :".$clpontofa->erro_msg);
			  		}

			  		$iContador += $clpontofa->numrows_excluir;

			  	}

			  	$erro_msgFA = "{$iContador} exclusões no Ponto Adiantamento";
			  	
			  }

			  /**
			   * Ponto complmentar
			   */
			  if ( isset($fc) ) {

			  	$whereatualizaFC = str_replace("#s#","r47",$whereatualiza);
		      $sWhere  = "     r47_anousu = ".db_anofolha();
			  	$sWhere .= " and r47_mesusu = ".db_mesfolha();
			  	$sWhere .= " and r47_rubric = '".$rh27_rubric."'";
			  	$sWhere .= " and r47_instit = ".db_getsession("DB_instit");
			  	$sWhere .= " and $whereatualizaFC";
			  	
			  	$sSqlTesteFC = $clpontocom->sql_query_seleciona(null,
			  																								  null,
			  																								  null,
			  																								  null,
			  																								  "distinct r47_regist as regist,r47_lotac as lotac",
			  																								  "",
			  																								  $sWhere);
			  	$rsTestesFC = $clpontocom->sql_record($sSqlTesteFC);
			  	$iLinhas = $clpontocom->numrows;
			  	if ( $iLinhas == 0) {
			  		throw new Exception($clpontocom->erro_msg);
			  	}

			  	$aFuncionariosSelecao = db_utils::getCollectionByRecord($rsTestesFC);
			  	$iContador = 0;

			  	foreach ($aFuncionariosSelecao as $oFuncionariosSelecao) {

			  		$clpontocom->excluir(db_anofolha(), db_mesfolha(), $oFuncionariosSelecao->regist, $rh27_rubric);
			  		if($clpontocom->erro_status==0){
			  			throw new Exception("Erro :".$clpontocom->erro_msg);
			  		}

			  		$iContador += $clpontocom->numrows_excluir;

			  	}

			  	$erro_msgFC = "{$iContador} exclusões no Ponto Complementar";
			  		
			  }

			  /**
			   * Ponto 13o
			   */
			  if ( isset($f3) ) {

			  	$whereatualizaF3 = str_replace("#s#","r34",$whereatualiza);
          $sWhere  = "     r34_anousu = ".db_anofolha();
			  	$sWhere .= " and r34_mesusu = ".db_mesfolha();
			  	$sWhere .= " and r34_rubric = '{$rh27_rubric}'";
			  	$sWhere .= " and r34_instit = ".db_getsession("DB_instit");
			  	$sWhere .= " and {$whereatualizaF3}";
          
			  	$sSqlTesteF3 = $clpontof13->sql_query_seleciona(null,
			  																								  null,
			  																								  null,
			  																								  null,
			  																								  "distinct r34_regist as regist,r34_lotac as lotac",
			  																								  "",
			  																								  $sWhere);
			  	$rsTestesF3 = $clpontof13->sql_record($sSqlTesteF3);
			  	$iLinhas = $clpontof13->numrows;
			  	if ( $iLinhas == 0) {
			  		throw new Exception($clpontof13->erro_msg);
			  	}

			  	$aFuncionariosSelecao = db_utils::getCollectionByRecord($rsTestesF3);
			  	$iContador = 0;

			  	foreach ($aFuncionariosSelecao as $oFuncionariosSelecao) {

			  		$clpontof13->excluir(db_anofolha(), db_mesfolha(), $oFuncionariosSelecao->regist, $rh27_rubric);
			  		if ( $clpontof13->erro_status == 0 ) {
			  			throw new Exception("Erro :".$clpontof13->erro_msg);
			  		}

			  		$iContador += $clpontof13->numrows_excluir;

			  	}

			  	$erro_msgF3 = "{$iContador} exclusões no Ponto 13o";
			  	
			  }

			  /**
			   * Ponto fixo
			   */
			  if ( isset($fx) ) {

			  	$whereatualizaFX = str_replace("#s#","r90",$whereatualiza);
		      $sWhere  = "     r90_anousu = ".db_anofolha();
			  	$sWhere .= " and r90_mesusu = ".db_mesfolha();
			  	$sWhere .= " and r90_rubric = '".$rh27_rubric."'";
			  	$sWhere .= " and r90_instit = ".db_getsession("DB_instit");
			  	$sWhere .= " and $whereatualizaFX";

			  	$sSqlTesteFX = $clpontofx->sql_query_seleciona(null,
			  																								 null,
			  																								 null,
			  																								 null,
			  																								 "distinct r90_regist as regist,r90_lotac as lotac",
			  																								 "",
			  																								 $sWhere);
			  	$rsTestesFX = $clpontofx->sql_record($sSqlTesteFX);
			  	$iLinhas    = $clpontofx->numrows;
			  	if ( $iLinhas == 0 ) {
			  		throw new Exception($clpontofx->erro_msg);
			  	}

			  	$aFuncionariosSelecao = db_utils::getCollectionByRecord($rsTestesFX);
			  	$iContador = 0;

			  	foreach ($aFuncionariosSelecao as $oFuncionariosSelecao) {

			  		$clpontofx->excluir(db_anofolha(), db_mesfolha(), $oFuncionariosSelecao->regist, $rh27_rubric);
			  		if ( $clpontofx->erro_status == 0 ) {
			  			throw new Exception("Erro :".$clpontofx->erro_msg);
			  		}

			  		$iContador += $clpontofx->numrows_excluir;

			  	}

			  	$erro_msgFX = "{$iContador} exclusões no Ponto Fixo";
			  	
			  }

			  /**
			   * Ponto salario
			   */
			  if ( isset($fs)) {

			  	$whereatualizaFS = str_replace("#s#","r10",$whereatualiza);
          $sWhere  = "     r10_anousu = ".db_anofolha();
			  	$sWhere .= " and r10_mesusu = ".db_mesfolha();
			  	$sWhere .= " and r10_rubric = '".$rh27_rubric."'";
			  	$sWhere .= " and r10_instit = ".db_getsession("DB_instit");
			  	$sWhere .= " and $whereatualizaFS";

			  	$sSqlTesteFS = $clpontofs->sql_query_seleciona(null,
			  																								 null,
			  																								 null,
			  																								 null,
			  																								 "distinct r10_regist as regist,r10_lotac as lotac",
			  																								 "",
			  																								 $sWhere);
			  	$rsTestesFS = $clpontofs->sql_record($sSqlTesteFS);
			  	$iLinhas    = $clpontofs->numrows;
			  	if ( $iLinhas == 0) {
			  		throw new Exception($clpontofs->erro_msg);
			  	}

			  	$aFuncionariosSelecao = db_utils::getCollectionByRecord($rsTestesFS);
			  	$iContador = 0;

			  	foreach ($aFuncionariosSelecao as $oFuncionariosSelecao) {

			  		$clpontofs->excluir(db_anofolha(), db_mesfolha(), $oFuncionariosSelecao->regist, $rh27_rubric);
			  		if ( $clpontofs->erro_status == 0 ) {
			  			throw new Exception("Erro :".$clpontofs->erro_msg);
			  		}

			  		$iContador += $clpontofs->numrows_excluir;

			  	}

			  	$erro_msgFS = "{$iContador} exclusões no Ponto Salário";
			  	
			  }

			break;
		}

		db_fim_transacao();

		$erro_msg = "Atualizações efetuadas:";
		$barrans = "\\n\\n\\n";
		$erro_msg.= $barrans.$erro_ALTEF;
		if (isset($fa)) {
			$erro_msg.= $barrans.$erro_msgFA;
		  $barrans = "\\n\\n";
		}
		if (isset($fc)) {
		  $erro_msg.= $barrans.$erro_msgFC;
			$barrans = "\\n\\n";
		}
		if (isset($f3)) {
			$erro_msg.= $barrans.$erro_msgF3;
			$barrans = "\\n\\n";
		}
		if (isset($fx)) {
			$erro_msg.= $barrans.$erro_msgFX;
			$barrans = "\\n\\n";
		}
		if (isset($fs)) {
			$erro_msg.= $barrans.$erro_msgFS;
			$barrans = "\\n\\n";
		}

		unset($valoratu,$valornov,$porcentv,$sosuv);
		unset($quantatu,$quantnov,$porcentq,$sosuq);
		unset($datlimatu,$datlimnov);

		db_msgbox($erro_msg);
	  db_redireciona('pes1_aturubricas001.php');		

	}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
      <center>
		  <?
		  include("forms/db_frmaturubricas.php");
		  ?>
      </center>
	  </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
// js_tabulacaoforms("form1","valoratu",true,1,"valoratu",true);
js_datlim(true);
</script>

<?php
} catch(Exception $oErro) {

	db_msgbox($oErro->getMessage());
	db_redireciona('pes1_aturubricas001.php');
	db_fim_transacao(true);

}

?>
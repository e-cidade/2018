<?php
/**
 * 
 */
class CalculoPensao { 
	/**
	 * Folha utilizada durante o cálculo
	 * @var FolhaPagamento
	 */
	public static $oFolhaAtual;
	private static $aPensoes = array();
	public static function calcular($icalc, $opcao_geral, $opcao_tipo, $chamada_geral_arquivo=null) {

		$oDaoPensao            = new cl_pensao();

		global $F001, $F002, $F004, $F005, $F006, $F007, $F008, $F009, $F010, $F011,$F012, $F013, $F014, $F015, $F016,
		       $F017, $F018, $F019, $F020, $F021,
		       $F022, $F023, $F006_clt, $F024, $F003, $F025, $F026, $F027, $F028, $F030;

		global $quais_diversos, $db_debug;
		eval($quais_diversos);
		global $anousu, $mesusu, $DB_instit;
		global $siglap, $db21_codcli, $cfpess, $subpes ,$r110_regisi,$pensao;
		global $$chamada_geral_arquivo,$minha_calcula_pensao,$campos_pessoal;

		global $opcao_filtro,$opcao_gml,$r110_regisf,$r110_lotaci, $r110_lotacf,$faixa_regis,$faixa_lotac;

    $aFolhasComEstruturaSuplementar  = array(PONTO_COMPLEMENTAR,
                                             PONTO_SALARIO
                                            );
    $lComplementarOUSalario = false;
    if (in_array($opcao_geral, $aFolhasComEstruturaSuplementar)) {
      $lComplementarOUSalario = true;
    }
		LogCalculoFolha::write("Iniciando calculo de pensão.");
		LogCalculoFolha::write("Opcao Geral....: $opcao_geral.");
		LogCalculoFolha::write("Opcao Tipo.....: $opcao_tipo.");

		if ($opcao_geral == 1) {

			$sigla      = "r10_";
			$sigla1     = "r14_";
			$qual_ponto = "pontofs";
		} else if ($opcao_geral == 8) {

			$sigla      = "r47_";
			$sigla1     = "r48_";
			$qual_ponto = "pontocom";
		} else if ($opcao_geral == 3) {

			$sigla      = "r29_";
			$sigla1     = "r31_";
			$qual_ponto = "pontofe";
		} else if ($opcao_geral == 4) {

			$sigla      = "r19_";
			$sigla1     = "r20_";
			$qual_ponto = "pontofr";
		} else if ($opcao_geral == 5) {

			$sigla      = "r34_";
			$sigla1     = "r35_";
			$qual_ponto = "pontof13";
		}
		$siglag = $sigla1;

		if ($opcao_tipo == 2) {

			if ($opcao_geral== 1 || $opcao_geral== 8) {
				$stringferias  = "('".$cfpess[0]["r11_ferias"]."','".$cfpess[0]["r11_fer13"]."','";
				$stringferias .= $cfpess[0]["r11_fer13a"]."','".$cfpess[0]["r11_ferabo"]."','";
				$stringferias .= $cfpess[0]["r11_feradi"]."','".$cfpess[0]["r11_ferant"]."','";
				$stringferias .= $cfpess[0]["r11_feabot"]."','".$cfpess[0]["r11_fadiab"]."')";
				if ($opcao_geral == 1 ) {
					$condicaoaux = " and ( r10_rubric in  " . $stringferias;
					//echo "<BR> 2 - stringferias --> $stringferias";
					$condicaoaux .= "  or r10_rubric between '2000' and '3999' )";
					$retornar = db_delete("pontofs", bb_condicaosubpes("r10_").$condicaoaux );
				} else if ($opcao_geral == 8) {
					$condicaoaux = " and ( r47_rubric in ".$stringferias;
					$condicaoaux .= "  or r47_rubric between '2000' and '3999' )";
					$retornar = db_delete("pontocom", bb_condicaosubpes("r47_").$condicaoaux );
				}
			}
			if ($opcao_geral == 1) {

				$condicaoaux  = " and  rh05_recis is null ";
				$condicaoaux .= " order by r52_regist ";
				db_selectmax("pensao", "select pensao.*,
						rh01_regist as r01_regist,
						trim(TO_CHAR(RH02_LOTA,'9999')) as r01_lotac
						from pensao
						inner join rhpessoalmov on pensao.r52_anousu         = rhpessoalmov.rh02_anousu
						and pensao.r52_mesusu         = rhpessoalmov.rh02_mesusu
						and pensao.r52_regist         = rhpessoalmov.rh02_regist
						and rhpessoalmov.rh02_instit  = ".db_getsession('DB_instit')."
						inner join rhpessoal    on rhpessoal.rh01_regist     = rhpessoalmov.rh02_regist
						inner join rhlota       on rhlota.r70_codigo         = rhpessoalmov.rh02_lota
						and rhlota.r70_instit         = rhpessoalmov.rh02_instit
						inner join cgm          on cgm.z01_numcgm            = rhpessoal.rh01_numcgm
						left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes
						".bb_condicaosubpes("r52_" ).$condicaoaux );
			} else if ($opcao_geral == 2 ) {

				$condicaoaux  = " and  rh05_recis is null ";
				$condicaoaux .= " order by r52_regist ";
				db_selectmax("pensao", "select pensao.*,
						rh01_regist as r01_regist,
						trim(TO_CHAR(RH02_LOTA,'9999')) as r01_lotac
						from pensao
						inner join rhpessoalmov on pensao.r52_anousu         = rhpessoalmov.rh02_anousu
						and pensao.r52_mesusu         = rhpessoalmov.rh02_mesusu
						and pensao.r52_regist         = rhpessoalmov.rh02_regist
						and rhpessoalmov.rh02_instit  = ".db_getsession('DB_instit')."
						inner join rhpessoal    on rhpessoal.rh01_regist     = rhpessoalmov.rh02_regist
						inner join rhlota       on rhlota.r70_codigo         = rhpessoalmov.rh02_lota
						and rhlota.r70_instit         = rhpessoalmov.rh02_instit
						inner join cgm          on cgm.z01_numcgm            = rhpessoal.rh01_numcgm
						left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes
						left join rhregime      on rhregime.rh30_codreg      = rhpessoalmov.rh02_codreg
						and rhregime.rh30_instit      = rhpessoalmov.rh02_instit
						left join rhpesrubcalc on rhpesrubcalc.rh65_seqpes   = rhpessoalmov.rh02_seqpes
						left join rhinssoutros on rh51_seqpes                = rh02_seqpes
						left join rhpesprop    on rh19_regist                = rh02_regist
						".bb_condicaosubpes("r52_" ).$condicaoaux );
			} else if ($opcao_geral == 3 ) {

				$condicaoaux  = " and  rh05_recis is null ";
				$condicaoaux .= " order by r52_regist ";
				db_selectmax("pensao", "select distinct(r52_regist+r52_numcgm),
						pensao.*,
						rh01_regist as r01_regist,
						trim(TO_CHAR(RH02_LOTA,'9999')) as r01_lotac
						from pensao
						inner join rhpessoalmov on pensao.r52_anousu         = rhpessoalmov.rh02_anousu
						and pensao.r52_mesusu         = rhpessoalmov.rh02_mesusu
						and pensao.r52_regist         = rhpessoalmov.rh02_regist
						and rhpessoalmov.rh02_instit  = ".db_getsession('DB_instit')."
						left  join pontofe      on pontofe.r29_anousu        = rhpessoalmov.rh02_anousu
						and pontofe.r29_mesusu        = rhpessoalmov.rh02_mesusu
						and pontofe.r29_regist        = rhpessoalmov.rh02_regist
						inner join rhpessoal    on rhpessoal.rh01_regist     = rhpessoalmov.rh02_regist
						inner join rhlota       on rhlota.r70_codigo         = rhpessoalmov.rh02_lota
						and rhlota.r70_instit         = rhpessoalmov.rh02_instit
						inner join cgm          on cgm.z01_numcgm            = rhpessoal.rh01_numcgm
						left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes
						".bb_condicaosubpes("r52_" ).$condicaoaux );
			} else if ($opcao_geral == 4 ) {

				$condicaoaux  = " and  rh05_recis is not null ";
				$condicaoaux .= " and ( (  extract(year  from rh05_recis)=".db_sqlformat(substr("#".$subpes,1,4) );
				$condicaoaux .= "      and extract(month from rh05_recis)>=".db_sqlformat(substr("#".$subpes,6,2) );
				$condicaoaux .= " ) or extract(year from rh05_recis)>".db_sqlformat(substr("#".$subpes,1,4) ).")";
				$condicaoaux .= " order by r52_regist ";
				db_selectmax("pensao", "select pensao.*,
						rh01_regist as r01_regist,
						trim(TO_CHAR(RH02_LOTA,'9999')) as r01_lotac
						from pensao
						inner join rhpessoalmov on pensao.r52_anousu         = rhpessoalmov.rh02_anousu
						and pensao.r52_mesusu         = rhpessoalmov.rh02_mesusu
						and pensao.r52_regist         = rhpessoalmov.rh02_regist
						and rhpessoalmov.rh02_instit  = ".db_getsession('DB_instit')."
						inner join rhpessoal    on rhpessoal.rh01_regist     = rhpessoalmov.rh02_regist
						inner join rhlota       on rhlota.r70_codigo         = rhpessoalmov.rh02_lota
						and rhlota.r70_instit         = rhpessoalmov.rh02_instit
						inner join cgm          on cgm.z01_numcgm            = rhpessoal.rh01_numcgm
						left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes
						".bb_condicaosubpes("r52_" ).$condicaoaux );
			} else if ($opcao_geral == 5 ) {

				$condicaoaux  = " and ( rh05_recis is null or rh05_recis >= ".db_sqlformat(db_ctod("01/".substr("#".$subpes,6,2)."/".substr("#".$subpes,1,4))).")";
				$condicaoaux .= " order by r52_regist ";
				db_selectmax("pensao", "select distinct(r52_regist+r52_numcgm),
						pensao.*,
						rh01_regist as r01_regist,
						trim(TO_CHAR(RH02_LOTA,'9999')) as r01_lotac,
						r34_regist
						from pensao
						inner join rhpessoalmov on pensao.r52_anousu         = rhpessoalmov.rh02_anousu
						and pensao.r52_mesusu         = rhpessoalmov.rh02_mesusu
						and pensao.r52_regist         = rhpessoalmov.rh02_regist
						and rhpessoalmov.rh02_instit  = ".db_getsession('DB_instit')."
						left  join pontof13     on pontof13.r34_anousu       = rhpessoalmov.rh02_anousu
						and pontof13.r34_mesusu       = rhpessoalmov.rh02_mesusu
						and pontof13.r34_regist       = rhpessoalmov.rh02_regist
						inner join rhpessoal    on rhpessoal.rh01_regist     = rhpessoalmov.rh02_regist
						inner join rhlota       on rhlota.r70_codigo         = rhpessoalmov.rh02_lota
						and rhlota.r70_instit         = rhpessoalmov.rh02_instit
						inner join cgm          on cgm.z01_numcgm            = rhpessoal.rh01_numcgm
						left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes
						".bb_condicaosubpes("r52_" ).$condicaoaux );
			} else if ($opcao_geral == 8 ) {

				$condicaoaux  = " and ( r47_regist is not null or r29_regist is not null ) ";
				$condicaoaux .= " and ( rh05_recis is null or rh05_recis >= ".db_sqlformat(db_ctod("01/".substr("#".$subpes,6,2)."/".substr("#".$subpes,1,4))).")";
				$condicaoaux .= " order by r52_regist ";
				db_selectmax("pensao", "select distinct(r52_regist+r52_numcgm),
						pensao.*,
						rh01_regist as r01_regist,
						r47_regist,
						trim(TO_CHAR(RH02_LOTA,'9999')) as r01_lotac,
						r29_regist
						from pensao
						inner join rhpessoalmov on rhpessoalmov.rh02_anousu  = pensao.r52_anousu
						and rhpessoalmov.rh02_mesusu  = pensao.r52_mesusu
						and rhpessoalmov.rh02_regist  = pensao.r52_regist
						and rhpessoalmov.rh02_instit  = ".db_getsession('DB_instit')."
						left  join pontocom     on pontocom.r47_anousu       = rhpessoalmov.rh02_anousu
						and pontocom.r47_mesusu       = rhpessoalmov.rh02_mesusu
						and pontocom.r47_regist       = rhpessoalmov.rh02_regist
						and pontocom.r47_instit       = rhpessoalmov.rh02_instit
						left  join pontofe      on pontofe.r29_anousu        = rhpessoalmov.rh02_anousu
						and pontofe.r29_mesusu        = rhpessoalmov.rh02_mesusu
						and pontofe.r29_regist        = rhpessoalmov.rh02_regist
						and pontofe.r29_instit        = rhpessoalmov.rh02_instit
						inner join rhpessoal    on rhpessoal.rh01_regist     = rhpessoalmov.rh02_regist
						inner join rhlota       on rhlota.r70_codigo         = rhpessoalmov.rh02_lota
						and rhlota.r70_instit         = rhpessoalmov.rh02_instit
						inner join cgm          on cgm.z01_numcgm            = rhpessoal.rh01_numcgm
						left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes
						".bb_condicaosubpes("r52_" ).$condicaoaux );
			}

		} else {

			$condicaoaux = "";
			if ($opcao_geral <= 3 ) {
				$condicaoaux .= " and  rh05_recis is null ";
			}
			$condicaoaux .= db_condicaoaux($opcao_filtro,$opcao_gml,"rh02_",$r110_regisi,$r110_regisf,$r110_lotaci,
					$r110_lotacf,$faixa_regis,$faixa_lotac,"rh01_");
			$condicaoaux .= " order by r52_regist ";
			db_selectmax("pensao", "select pensao.*,
					rh01_regist as r01_regist,
					trim(TO_CHAR(RH02_LOTA,'9999')) as r01_lotac
					from pensao
					inner join rhpessoalmov  on pensao.r52_anousu         = rhpessoalmov.rh02_anousu
					and pensao.r52_mesusu         = rhpessoalmov.rh02_mesusu
					and pensao.r52_regist         = rhpessoalmov.rh02_regist
					and rhpessoalmov.rh02_instit  = ".db_getsession('DB_instit')."
					inner join rhpessoal     on rhpessoal.rh01_regist     = rhpessoalmov.rh02_regist
					inner join rhlota        on rhlota.r70_codigo         = rhpessoalmov.rh02_lota
					and rhlota.r70_instit         = rhpessoalmov.rh02_instit
					inner join cgm           on cgm.z01_numcgm            = rhpessoal.rh01_numcgm
					left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes
					".bb_condicaosubpes("r52_" ).$condicaoaux );
		}

		$contador        = 1;
		$primeira_pensao = true;

		if (count($pensao)>0) {
			$minha_calcula_pensao=true;
		}

		if ($db_debug == true) {
			echo "[calc_pensao] Pensões encontradas: <br>";
			echo "<pre>";
			print_r($pensao);
			echo "</pre>";
		}

		for ($Ipensao=0; $Ipensao<count($pensao); $Ipensao++) {


			$oServidor = ServidorRepository::getInstanciaByCodigo(
					$pensao[$Ipensao]["r52_regist"],
					$pensao[$Ipensao]["r52_anousu"],
					$pensao[$Ipensao]["r52_mesusu"]);

			$oVariaveisCalculo = DBPessoal::getVariaveisCalculo($oServidor);

			$F001     = $oVariaveisCalculo->f001;
			$F002     = $oVariaveisCalculo->f002;
			$F003     = $oVariaveisCalculo->f003;
			$F004     = $oVariaveisCalculo->f004;
			$F005     = $oVariaveisCalculo->f005;
			$F006     = $oVariaveisCalculo->f006;
			$F006_clt = $oVariaveisCalculo->f006_clt;
			$F007     = $oVariaveisCalculo->f007;
			$F008     = $oVariaveisCalculo->f008;
			$F009     = $oVariaveisCalculo->f009;
			$F010     = $oVariaveisCalculo->f010;
			$F011     = $oVariaveisCalculo->f011;
			$F012     = $oVariaveisCalculo->f012;
			$F013     = $oVariaveisCalculo->f013;
			$F014     = $oVariaveisCalculo->f014;
			$F015     = $oVariaveisCalculo->f015;
			$F022     = $oVariaveisCalculo->f022;
			$F024     = $oVariaveisCalculo->f024;
			$F025     = $oVariaveisCalculo->f025;
			$F030     = $oVariaveisCalculo->f030;

			$pvalor_obriga       = 0;
			$pvalor_liquido      = 0;
			$pvalor_bruto        = 0;
			$pvalor_salfamilia   = 0;
			$pvalor_ad_13salario = 0;
			LogCalculoFolha::write("Valor Obrigações..................:" . $pvalor_obriga      );      
			LogCalculoFolha::write("Valor Liquido.....................:" . $pvalor_liquido     );      
			LogCalculoFolha::write("Valor Bruto.......................:" . $pvalor_bruto       );
			LogCalculoFolha::write("Valor Salário Família.............:" . $pvalor_salfamilia  );
			LogCalculoFolha::write("Valor Adiantamento Salário Família:" . $pvalor_ad_13salario);

			if ($db_debug == true) {
				echo "[calc_pensao] calculando pensao $Ipensao... <br>";
				echo "[calc_pensao] chamada_geral_arquivo: {$chamada_geral_arquivo} <br>";
				echo "[calc_pensao] r11_mes13:".$cfpess[0]["r11_mes13"]." - mes:".$mesusu."<br>";
			}

			/**
			 * Somente 13º - Início
			 */
			if ($chamada_geral_arquivo == "gerfs13" && $cfpess[0]["r11_mes13"] == $mesusu) {

				if ($db_debug == true) {
					echo "[calc_pensao] Buscando valores das pensões anteriores a ".$pensao[$Ipensao]["r52_mesusu"]." para o cgm ".$pensao[$Ipensao]["r52_numcgm"]." no ano ".$pensao[$Ipensao]["r52_anousu"]."<br>";
				}
				$sSqlValorPensao = "select sum(coalesce(pensao.r52_val13,0)) as r52_val13
					from pensao
					where r52_regist = ".$pensao[$Ipensao]["r52_regist"]."
					and r52_numcgm = ".$pensao[$Ipensao]["r52_numcgm"]."
					and r52_anousu = ".$pensao[$Ipensao]["r52_anousu"]."
					and r52_mesusu < ".$pensao[$Ipensao]["r52_mesusu"];
				$rsValorPensao       = db_query($sSqlValorPensao);

				if (pg_num_rows($rsValorPensao) > 0) {
					$pvalor_ad_13salario = db_utils::fieldsMemory($rsValorPensao,0)->r52_val13;
					LogCalculoFolha::write("Valor Adiantamento Salário Família:" . $pvalor_ad_13salario);
				}

				if ($db_debug == true) {
					echo "[calc_pensao] Adiantamentos de 13º (pvalor_ad_13salario): {$pvalor_ad_13salario} <br>";
				}
			}

			/**
			 * Somente 13º - FIM
			 */

			if ($db_debug) {
				echo "[calc_pensao] Tabela: $chamada_geral_arquivo...<br>";
			}

			/**
			 * TABELA GERF13o - Cálculo de 13º Salário
			 */
			if ($chamada_geral_arquivo == "gerfs13") {

				/**
				 * Valida se paga 13º conforme cadastro de pensão
				 */
				if ('f' == $pensao[$Ipensao]["r52_pag13"]) {

					if ($db_debug) {
						echo "[calc_pensao] r52_pag13:".$pensao[$Ipensao]["r52_pag13"]." - continuando calculo pulando o registro... <br>";
					}
					continue;
				}

				$lCalculaPensaoAdiantamento13 = false;

				if ($cfpess[0]["r11_mes13"] != $mesusu) {

					if ($db_debug) {
						echo "[calc_pensao] r11_mes13: ".$cfpess[0]["r11_mes13"]." != mes: $mesusu <br>";
					}
					if ($pensao[$Ipensao]["r52_adiantamento13"] == 't') {
						$lCalculaPensaoAdiantamento13 = true;
					} else {

						if ($db_debug) {
							echo "[calc_pensao] r52_adiantamento13: ".$pensao[$Ipensao]["r52_adiantamento13"]." - continuando calculo pulando o registro... <br>";
						}
						continue;
					}
				}

				/**
				 * TABELA GERFCOM - Cálculo de Folha Complementar
				 */
			} else if ($chamada_geral_arquivo == "gerfcom") {
				if ('f' == $pensao[$Ipensao]["r52_pagcom"]) {
					if ($db_debug) {
						echo "[calc_pensao] r52_pagcom: ".$pensao[$Ipensao]["r52_pagcom"]." - continuando calculo pulando o registro... <br>";
					}
					continue;
				}
				/**
				 * TABELA GERFFER - Cálculo de Folha Férias
				 */
			} else if ($chamada_geral_arquivo == "gerffer") {
				if ('f' == $pensao[$Ipensao]["r52_pagfer"]) {
					if ($db_debug) {
						echo "[calc_pensao] r52_pagfer: ".$pensao[$Ipensao]["r52_pagfer"]." - continuando calculo pulando o registro... <br>";
					}
					continue;
				}

				/**
				 * TABELA GERFRES - Cálculo de Folha Rescisão
				 */
			} else if ($chamada_geral_arquivo == "gerfres") {

				if ('f' == $pensao[$Ipensao]["r52_pagres"]) {
					if ($db_debug) {
						echo "[calc_pensao] r52_pagres: ".$pensao[$Ipensao]["r52_pagres"]." - continuando calculo pulando o registro... <br>";
					}
					continue;
				}
			} 

			/**
			 * Termina aqui validaçao das NEGAÇÕES de pagamento de pensão
			 */
			$registrop    = $pensao[$Ipensao]["r52_regist"];
			$numcgmp      = $pensao[$Ipensao]["r52_numcgm"];
			$condicaoaux  = " and r52_regist = ".db_sqlformat($registrop);
			$condicaoaux .= " and r52_numcgm = ".db_sqlformat($numcgmp);

			$matriz1      = array();
			$matriz2      = array();
			$retornar     = true;

			if ($chamada_geral_arquivo == "gerfs13") {

				$matriz1[1] = "r52_val13";
				$matriz2[1] = 0;
				LogCalculoFolha::write("Alterando o valor do campo {$matriz1[1]} da tabela pensao para 0");
				db_update("pensao", $matriz1, $matriz2, bb_condicaosubpes("r52_").$condicaoaux );

			} else if ($chamada_geral_arquivo == "gerfcom") {

				$matriz1[1] = "r52_valcom";
				$matriz2[1] = 0;
				LogCalculoFolha::write("Alterando o valor do campo {$matriz1[1]} da tabela pensao para 0");
				db_update("pensao", $matriz1, $matriz2, bb_condicaosubpes("r52_").$condicaoaux );
				self::removerHistorico( $registrop, $numcgmp );

			} else if ($chamada_geral_arquivo == "gerfres") {

				$matriz1[1] = "r52_valres";
				$matriz2[1] = 0;
				LogCalculoFolha::write("Alterando o valor do campo {$matriz1[1]} da tabela pensao para 0");
				db_update("pensao", $matriz1, $matriz2, bb_condicaosubpes("r52_").$condicaoaux );

			} else {

				$matriz1[1] = "r52_valor";
				$matriz2[1] = 0;
				LogCalculoFolha::write("Alterando o valor do campo r52_valor da tabela pensao para 0");
				db_update("pensao", $matriz1, $matriz2, bb_condicaosubpes("r52_").$condicaoaux );
				self::removerHistorico( $registrop, $numcgmp );
			}

			$condicaoaux  =  " and r30_regist = ".db_sqlformat($pensao[$Ipensao]["r52_regist"] ) ;
			$condicaoaux .= " order by r30_perai desc";
			global $cadferia;
			db_selectmax("cadferia", "select r30_regist,r30_proc2,r30_per2i, r30_per2f,r30_proc1,r30_per1i,r30_per1f,r30_paga13,r30_descad from cadferia ".bb_condicaosubpes("r30_" ).$condicaoaux );
			if (db_empty($cadferia[0]["r30_proc2"]) ) {
				$r30_proc = "r30_proc1";
				$r30_peri = "r30_per1i";
				$r30_perf = "r30_per1f";
			} else {
				$r30_proc = "r30_proc2";
				$r30_peri = "r30_per2i";
				$r30_peri = "r30_per2f";
			}
			$condicaoaux = " and ".$siglag."regist = ".db_sqlformat($pensao[$Ipensao]["r52_regist"] );
			$tem_calculo = db_selectmax($chamada_geral_arquivo, "select * from ".$chamada_geral_arquivo." ".bb_condicaosubpes($siglag ).$condicaoaux );
			if ($tem_calculo) {

				if ($db_debug) {
					echo "[calc_pensao] encontrou calculo executando a query: select * from ".$chamada_geral_arquivo." ".bb_condicaosubpes($siglag ).$condicaoaux."<br>";
				}
				$pvalor_bruto        = 0;
				LogCalculoFolha::write("Valor Bruto.......................:" . $pvalor_bruto       );
				$pvalor_liquido    = 0;
				LogCalculoFolha::write("Valor Desconto para Liquido.....................:" . $pvalor_liquido     );      
				$pvalor_obriga     = 0;
				LogCalculoFolha::write("Valor Obrigações..................:" . $pvalor_obriga      );      
				$pvalor_salfamilia = 0;
				LogCalculoFolha::write("Valor Salário Família.............:" . $pvalor_salfamilia  );
				$qual_reg          = $sigla1."regist";
				$qual_rub          = $sigla1."rubric";
				$qual_tpp          = " ";
				if ($opcao_geral == 3) {
					$qual_tpp = $sigla1."tpp";
				}

				$chamada_geral_ = $$chamada_geral_arquivo;
				for ($Igeral=0; $Igeral<count($chamada_geral_); $Igeral++) {

					if( $chamada_geral_[$Igeral][$qual_rub] == "R993" ){
						continue;
					}

					if ($opcao_geral == 3
							&& (( db_month($cadferia[0][$r30_peri]) > db_val(substr("#".$cadferia[0][$r30_proc],6,2)) &&
									db_year($cadferia[0][$r30_peri]) == db_val(substr("#".$cadferia[0][$r30_proc],1,4)))
								|| ( db_month($cadferia[0][$r30_peri]) < db_val(substr("#".$cadferia[0][$r30_proc],6,2)) &&
									db_year($cadferia[0][$r30_peri]) > db_val(substr("#".$cadferia[0][$r30_proc],1,4)))
							   )
					   ) {
						if (strtolower($cfpess[0]["r11_fersal"]) == "f" && ('t' == $cadferia[0]["r30_paga13"]) ) {
							// Quando do Adiantamento de Férias , não Calcula a Pensão de Férias se for Pagar como Férias e somente 1/3 for sim
							continue;
						}
						if ('f' == $cadferia[0]["r30_paga13"] && $cadferia[0][$r30_proc] < $subpes && strtolower($chamada_geral_[$Igeral][$qual_tpp]) == "d" ) {
							// Não Processar no Calculo da Pensão de Férias as Rubricas de Férias Adiantadas quando somente 1/3 for não  e  Data de Pagto não Venceu
							continue;
						}
					}
					// a rubrica de pensao passa a ser calculada no gerffer
					// e depois repassada para o salario ou complentar
					if (( ( strtolower($cfpess[0]["r11_fersal"]) == "f" && ('t' ==  $cadferia[0]["r30_paga13"]) &&
									db_month($cadferia[0][$r30_peri]) == db_val(substr("#".$cadferia[0][$r30_proc],6,2))
					      )
								|| 'f' == $cadferia[0]["r30_paga13"]
					    )
							&&
							( $chamada_geral_[$Igeral][$qual_rub] == $cfpess[0]["r11_ferias"]  || // Rubrica onde é pago as férias
							  $chamada_geral_[$Igeral][$qual_rub] == $cfpess[0]["r11_fer13"] || // Rubrica onde é pago um 1/3 de férias
							  $chamada_geral_[$Igeral][$qual_rub] == $cfpess[0]["r11_fer13a"] || // Rubrica onde é pago um 1/3 s/ abono de férias
							  $chamada_geral_[$Igeral][$qual_rub] == $cfpess[0]["r11_ferabo"] || // Rubrica onde é pago o abono de férias
							  $chamada_geral_[$Igeral][$qual_rub] == $cfpess[0]["r11_feradi"] || // Rubrica onde é pago o adiantamento de férias
							  $chamada_geral_[$Igeral][$qual_rub] == $cfpess[0]["r11_fadiab"]    // Rubrica onde é descontado as férias pagas no mês anterior
							)
					   ) {
						// As Rubricas Especiais não entran no calculo da Pensão, quando do Calculo da Pensão no Salário ou na Complementar
						continue;
					}
					if ($opcao_geral != 3 && $opcao_geral != 4) {
						// No Calculo da Pensão de Salario ou Complementar as rubricas de Férias existente no Ponto não entram no Calculo da Pensão
						// Somente no Calculo da Pensão de Férias deve ler as rubricas 2000 e
						// os descontos referentess a ferias ( previdencia e ir )

						if (( substr("#".$chamada_geral_[$Igeral][$qual_rub],1,1) != "R" && ( db_val($chamada_geral_[$Igeral][$qual_rub] ) >= 2000
										&& db_val($chamada_geral_[$Igeral][$qual_rub] ) < 4000 ))
								|| $chamada_geral_[$Igeral][$qual_rub] == "R915" ) {
							continue;
						}
					}
					if ($opcao_geral == 3 ) {
						// Para pagamento somente 1/3 sim e restante em salario (Pagar como Salário)
						// para a geracao da Pensão de Férias so deve levar em conta para o Calculo da Pensão 1/3 Férias

						if (strtolower($cfpess[0]["r11_fersal"]) == "s" && ('t' == $cadferia[0]["r30_paga13"])) {
							if (( substr("#". $chamada_geral_[$Igeral][$qual_rub],1,1) != "R" && strtolower($chamada_geral_[$Igeral][$qual_tpp]) != "a" ) ) {
								continue;
							}
						}
					}
					if (substr("#".$chamada_geral_[$Igeral][$qual_rub],1,1) != "R"
							|| (( substr("#".$chamada_geral_[$Igeral][$qual_rub],1,1) == "R"
									&& ( (   db_val(substr("#".$chamada_geral_[$Igeral][$qual_rub],2,3)) < 950
											&& $chamada_geral_[$Igeral][$qual_rub] != "R928"
											&& db_val(substr("#".$chamada_geral_[$Igeral][$qual_rub],2,3)) > 900
									     )
										|| db_val(substr("#".$chamada_geral_[$Igeral][$qual_rub],2,3)) == 980 ))) ) {
						$qual_val = $chamada_geral_[$Igeral][$sigla1."valor"];
						$qual_pd  = $chamada_geral_[$Igeral][$sigla1."pd"];
						if ($chamada_geral_arquivo == "gerfres") {
							if (db_at($chamada_geral_[$Igeral][$qual_rub], "R902-R905-R908-R911-R914-")>0) {
								if (('t' == $pensao[$Ipensao]["r52_pag13"])) {
									$pvalor_obriga += $qual_val;
									LogCalculoFolha::write("Valor Obrigações..................:" . $pvalor_obriga      );      
									//echo "<BR> 1 pvalor_obriga ---> $pvalor_obriga rubrica --> ".$chamada_geral_[$Igeral][$qual_rub]." valor --> $qual_val" ;
								}
							} else if (substr("#".$chamada_geral_[$Igeral][$qual_rub],1,1) == "R"
									&& db_val(substr("#".$chamada_geral_[$Igeral][$qual_rub],2,3)) < 916 ) {
								$pvalor_obriga += $qual_val;
								LogCalculoFolha::write("Valor Obrigações..................:" . $pvalor_obriga      );      
								//echo "<BR> 2 pvalor_obriga ---> $pvalor_obriga rubrica --> ".$chamada_geral_[$Igeral][$qual_rub]." valor --> $qual_val" ;
							}
						} else {
							// Obs : Não entra no Calculo das Obrigacoes os Descontos de Previdencia de Ferias
							// quando tiver Calculando Pensão no Salario ou Complemetar
							if (( substr("#".$chamada_geral_[$Igeral][$qual_rub],1,1) == "R"
										&& db_val(substr("#".$chamada_geral_[$Igeral][$qual_rub],2,3)) < 916
										&& ((db_at($chamada_geral_[$Igeral][$qual_rub], "R903-R906-R909-R912-") > 0 && $opcao_geral == 3)
											||
											(db_at($chamada_geral_[$Igeral][$qual_rub], "R903-R906-R909-R912-") == 0 && $opcao_geral != 3)
										   ))
									||  ( $chamada_geral_[$Igeral][$qual_rub] == "R915" && $opcao_geral == 3 ) ) {
								$pvalor_obriga += $qual_val;
								LogCalculoFolha::write("Valor Obrigações..................:" . $pvalor_obriga      );      
								//echo "<BR> 3 pvalor_obriga ---> $pvalor_obriga rubrica --> ".$chamada_geral_[$Igeral][$qual_rub]." valor --> $qual_val" ;
							}
							if ($opcao_geral == 3 &&  db_at($chamada_geral_[$Igeral][$qual_rub] , "R903-R906-R909-R912") > 0 ) {
								// para ferias nao deve considerar os descontos
								// de previdencia normais

								continue;
							}
						}
						if ($chamada_geral_[$Igeral][$qual_rub] == "R980") {
							//$pvalor_ad_13salario += $qual_val;
							continue;
						}
						if (substr("#".$chamada_geral_[$Igeral][$qual_rub],1,1) == "R"
								&& db_val(substr("#".$chamada_geral_[$Igeral][$qual_rub],2,3)) >= 918
								&& db_val(substr("#".$chamada_geral_[$Igeral][$qual_rub],2,3)) <= 921 ) {
							$pvalor_salfamilia += $qual_val;
							LogCalculoFolha::write("Valor Salário Família.............:" . $pvalor_salfamilia  );
						}
						if ($chamada_geral_arquivo == "gerfres") {
							if (substr("#".$chamada_geral_[$Igeral][$qual_rub],1,1) !="R"
									&& $chamada_geral_[$Igeral][$qual_rub] > "4000"
									&& $chamada_geral_[$Igeral][$qual_rub] < "6000"
									&& 'f' == $pensao[$Ipensao]["r52_pag13"] ) {
								continue;
							}
						}
						if ($qual_pd == 1) {
							$pvalor_bruto += $qual_val;
							LogCalculoFolha::write("Valor Bruto.......................:" . $pvalor_bruto       );
							//echo "<BR> 2 pvalor_bruto ---> $pvalor_bruto";
						} else {
							if ((($opcao_geral==1 && ('t' == $cadferia[0]["r30_paga13"]) ) || $opcao_geral == 3 )
									&& ($chamada_geral_[$Igeral][$qual_rub] == $cfpess[0]["r11_ferant"] // Rubrica onde é descontado as férias pagas no mês anterior
										|| $chamada_geral_[$Igeral][$qual_rub] == $cfpess[0]["r11_feabot"] ) ) {
								// Rubrica em que será lançado o abono do mês anterior
								$pvalor_bruto -= $qual_val;
								LogCalculoFolha::write("Valor Bruto.......................:" . $pvalor_bruto       );
								//echo "<BR> 2 palor_bruto ---> $pvalor_bruto";
							} else {
								//echo "<BR> 2 pvalor_liquido ---> $qual_val qual_rub --> ".$chamada_geral_[$Igeral][$qual_rub];
								$pvalor_liquido += $qual_val;
								LogCalculoFolha::write("Valor Desconto para Liquido.....................:$pvalor_liquido(+{$qual_val}[{$chamada_geral_[$Igeral][$qual_rub]}])");      
								//echo "<BR> 2 pvalor_liquido ---> $pvalor_liquido";
							}
						}
					}
				}

				if ($opcao_geral == 3 && $cadferia[0][$r30_proc] < $subpes) {
					ferias($pensao[$Ipensao]["r52_regist"]," " );

					// F019 - Numero de dias a pagar no mes
					// F020 - Numero de dias abono p/ pagar no mes

					// verificar a necessidade de proporcionalizar: por
					// exemplo  se for so 1/3 todo adiantado no mes
					// anterior , porem tem 25 dias de gozo neste mes e
					// outros 5 no proximo mes

					$pvalor_bruto -= ( ( $cadferia[0]["r30_descad"] / ($cadferia[0]["r30_ndias"]-$cadferia[0]["r30_abono"]+$F020) ) * ($F019+$F020) );
					LogCalculoFolha::write("Valor Bruto.......................:" . $pvalor_bruto       );
					//echo "<BR> 3 palor_bruto ---> $pvalor_bruto";
				}

				if ($pvalor_bruto < 0) {
					$pvalor_bruto = 0;
					LogCalculoFolha::write("Valor Bruto.......................:" . $pvalor_bruto       );
					//echo "<BR> 3 palor_bruto ---> $pvalor_bruto";
				}

				//echo "<BR> $pvalor_liquido = ( $pvalor_bruto-$pvalor_liquido > 0 ? $pvalor_bruto-$pvalor_liquido: 0 ) ;";
				LogCalculoFolha::write("   Valor Bruto................................:" . $pvalor_bruto);      
				LogCalculoFolha::write("(-)Valor Desconto para Liquido................:" . $pvalor_liquido);      
				LogCalculoFolha::write("----------------------------------------------------------------------");      
				$pvalor_liquido = ( $pvalor_bruto-$pvalor_liquido > 0 ? $pvalor_bruto-$pvalor_liquido: 0 ) ;
				LogCalculoFolha::write("Valor Liquido.................................:" . $pvalor_liquido     );      

			}

			$formula_pensao = trim($pensao[$Ipensao]["r52_formul"]);
			if (!db_empty($formula_pensao)) {

				// if ($db_debug ) {


				// }

				if ($tem_calculo) {

					$formpensao = $pensao[$Ipensao]["r52_formul"];

					global $rubricas_;
          $iChaveRubricasPensao = 'rubricas_pensao';
          $rubricas_ = DBRegistry::get($iChaveRubricasPensao);
          if (empty($rubricas_)) {
            db_selectmax("rubricas_", "select * from rhrubricas  where rh27_instit = $DB_instit ");
            DBRegistry::add($iChaveRubricasPensao, $rubricas_);
          }
          $iTotalRubricas = count($rubricas_);
					for ($Irubricas=0; $Irubricas < $iTotalRubricas; $Irubricas++) {

						if (db_at($rubricas_[$Irubricas]["rh27_rubric"],$pensao[$Ipensao]["r52_formul"]) > 0) {

							//echo "<BR> ".$rubricas_[$Irubricas]["rh27_rubric"] ;
							$condicaoaux  = " and ".$siglag."regist = ".db_sqlformat($pensao[$Ipensao]["r52_regist"] );
							$condicaoaux .= " and ".$siglag."rubric = ".db_sqlformat($rubricas_[$Irubricas]["rh27_rubric"] );

							if (db_selectmax($chamada_geral_arquivo, "select * from ".$chamada_geral_arquivo." ".bb_condicaosubpes($siglag ).$condicaoaux )) {

								$arq_ = $$chamada_geral_arquivo;
								$vararq = $arq_[0][$sigla1."valor"] ;
								$formpensao = db_strtran($formpensao,$rubricas_[$Irubricas]["rh27_rubric"],db_strtran(db_str($vararq,15,2),",","."));
								//echo "<BR> formula da pensao 3 ->  $formpensao";
							} else {

								$formpensao = db_strtran($formpensao,$rubricas_[$Irubricas]["rh27_rubric"],"0");
								//echo "<BR> formula da pensao 4 ->  $formpensao";
							}
						}

					}

					while (1==1) {

						$temtroca = false;
						if (db_at("7777",$formpensao) > 0) {
							$formpensao = db_strtran($formpensao,"7777",db_strtran(db_str($pvalor_liquido,15,2),",","."));
							//echo "<BR> formula da pensao 5 ->  $formpensao";
							$temtroca = true;
						}
						if (db_at("8888",$formpensao) > 0) {
							$formpensao = db_strtran($formpensao,"8888",db_strtran(db_str($pvalor_obriga,15,2),",","."));
							//echo "<BR> formula da pensao 6 ->  $formpensao";
							$temtroca = true;
						}
						if (db_at("9999",$formpensao) > 0) {
							$formpensao = db_strtran($formpensao,"9999",db_strtran(db_str($pvalor_bruto,15,2),",","."));
							//echo "<BR> formula da pensao 7 ->  $formpensao";
							$temtroca = true;
						}
						if ($temtroca == false) {
							break;
						}

					}

					$formpensao = str_replace('D','$D',$formpensao);
					$formpensao = str_replace('F','$F',$formpensao);

					//            ver a possibilidade de incluir na formula o calculo de bases

					//            $formpensao = le_var_bxxx($formpensao,$qual_ponto, $chamada_geral_arquivo, $sigla, $sigla1, 0,"");

					//echo "<BR> formula da pensao 8 ->  $formpensao rubrica --> ".$pensao[$Ipensao]["r52_regist"];
					global $valor_pensao;
					ob_start();

					eval('$valor_pensao = '.$formpensao.";");

					$sMsgErroCalcPensao  = "Formula cadastrada no cadastro da pensão!\\n";
					$sMsgErroCalcPensao .= " Ano/Mes: ".$pensao[$Ipensao]["r52_anousu"]."/".$pensao[$Ipensao]["r52_mesusu"];
					$sMsgErroCalcPensao .= " - Cgm: ".$pensao[$Ipensao]["r52_numcgm"];
					db_alerta_erro_eval($pensao[$Ipensao]["r52_regist"],$formpensao,"\\n $sMsgErroCalcPensao");
					//echo "<BR> formula com % pensao = $valor_pensao * (".$pensao[$Ipensao]["r52_perc"]."/100)";

					if ($db_debug) {
						echo "[calc_pensao] calculando valor da pensao... <br>";
						echo "[calc_pensao] valor_pensao = valor_pensao * (r52_perc / 100) => $valor_pensao * $valor_pensao (".$pensao[$Ipensao]["r52_perc"]."/100) = ".$valor_pensao * ($pensao[$Ipensao]["r52_perc"]/100)."<br>";
					}
					$valor_pensao = $valor_pensao * ($pensao[$Ipensao]["r52_perc"]/100);
					//echo "<BR> valor da Pensao Alimenticia --> $valor_pensao";
				} else {
					$valor_pensao = 0;
				}

			} else {

				if ($db_debug) {
					echo "[calc_pensao] não encontrou formula para a pensao... <br>";
				}

				if (($pvalor_bruto - $pvalor_salfamilia) > 0) {

					if ($db_debug) {
						echo "[calc_pensao] pvalor_bruto($pvalor_bruto) - pvalor_salfamilia($pvalor_salfamilia) > 0 <br>";
						echo "[calc_pensao] valor_pensao = ".$pensao[$Ipensao]["r52_vlrpen"]."<br>";
					}
					$valor_pensao = $pensao[$Ipensao]["r52_vlrpen"];

				} else {

					if ($db_debug) {
						echo "[calc_pensao] valor_pensao = 0<br>";
					}
					$valor_pensao = 0;

				}

				if ($opcao_geral == 3) {
					if ($cadferia[0][$r30_proc] < $subpes
							&& db_month($cadferia[0][$r30_peri] ) == db_month($cadferia[0][$r30_perf] )
							&& db_month($cadferia[0][$r30_peri] ) != db_val(substr("#". $cadferia[0][$r30_proc],6,2)) ) {
						if ($db_debug) {
							echo "[calc_pensao] zerando valor da pensao<br>";
							echo "[calc_pensao] valor_pensao antes {$valor_pensao}<br>";
							echo "[calc_pensao] valor_pensao depois 0<br>";
						}
						$valor_pensao = 0;
					}
				}
			}
			//echo "<BR> pensao 8 ->  $chamada_geral_arquivo";

			if ($pvalor_ad_13salario > 0) {

				if ($db_debug) {
					echo "[calc_pensao] subtraindo pvalor_ad_13salario da variavel valor_pensao  <br>";
					echo "[calc_pensao] valor_pensao = {$valor_pensao} - {$pvalor_ad_13salario} = ".($valor_pensao-$pvalor_ad_13salario)."<br>";
				}
				$valor_pensao -= $pvalor_ad_13salario;
			}

			if ($valor_pensao <= 0){
				if ($db_debug) {
					echo "[calc_pensao] valor da pensao menor que zero, zerando valor da pensao! <br>";
				}
				$valor_pensao = 0;
			}

			if ($valor_pensao >= 0  ) {

				if ($db_debug) {
					echo "[calc_pensao] Valor da Pensao: {$valor_pensao} <br>";
				}

				if ($chamada_geral_arquivo == "gerfs13") {
					$rubrica_pensao = db_str(db_val($cfpess[0]["r11_palime"])+4000, 4,0);
				} else if ($chamada_geral_arquivo == "gerffer") {
					$rubrica_pensao = db_str(db_val($cfpess[0]["r11_palime"])+2000, 4,0);
				} else {
					$rubrica_pensao = $cfpess[0]["r11_palime"];
				}
				$condicaoaux  = " and ".$siglap."regist = ".db_sqlformat($pensao[$Ipensao]["r52_regist"] );
				$condicaoaux .= " and ".$siglap."rubric = ".db_sqlformat($rubrica_pensao );

				global $$qual_ponto;
				if (db_selectmax($qual_ponto, "select * from ".$qual_ponto." ".bb_condicaosubpes($siglap ).$condicaoaux )) {

					$acao = "altera";
				} else {
					$acao = "insere";
				}

				if ($chamada_geral_arquivo == "gerfs13") {

					if ($lCalculaPensaoAdiantamento13) {
						if ($db_debug) {
							echo "[calc_pensao] calculando valor da pensao com adiantamento<br>";
							echo "[calc_pensao] valor da pensao = (valor da pensao * (r52_percadiantamento13)/100) = {$valor_pensao} * (".$pensao[$Ipensao]["r52_percadiantamento13"]."/100) = ".($valor_pensao * ($pensao[$Ipensao]["r52_percadiantamento13"]/100))."<br>";
						}
						$valor_pensao = ($valor_pensao * ($pensao[$Ipensao]["r52_percadiantamento13"]/100));
					}
				}

				$ponto = $$qual_ponto;

				$qual_val = $sigla."valor";
				$qual_rep = $sigla;

				$valor_pensao   = round($valor_pensao,2 );

				if ( DBPessoal::verificarUtilizacaoEstruturaSuplementar() && ($chamada_geral_arquivo == "gerfsal" || $chamada_geral_arquivo == "gerfcom") ) {

					$iCodigoPensao  = $oDaoPensao->getSequencial( $pensao[$Ipensao]["r52_regist"], $pensao[$Ipensao]["r52_numcgm"] );
					$nValorPensao   = 0;

					if ( !self::$oFolhaAtual ) {
						return false;
					}

					$iCodigoFolha   = self::$oFolhaAtual->getSequencial();
				}

				if ($valor_pensao > 0 ) {


					LogCalculoFolha::write("Valor Encontrado para pensão: $valor_pensao");
					LogCalculoFolha::write("Valor Encontrado para pensão: $valor_pensao");


					if ($opcao_geral == PONTO_SALARIO) {
						$matriz1 = array();
						$matriz2 = array();

						$matriz1[1] = "r10_regist";
						$matriz1[2] = "r10_rubric";
						$matriz1[3] = "r10_valor";
						$matriz1[4] = "r10_quant";
						$matriz1[5] = "r10_lotac";
						$matriz1[6] = "r10_anousu";
						$matriz1[7] = "r10_mesusu";
						$matriz1[8] = "r10_instit";

						$matriz2[1] = $pensao[$Ipensao]["r52_regist"];
						$matriz2[2] = $cfpess[0]["r11_palime"];
						if ($primeira_pensao) {
							$matriz2[3] = round($valor_pensao,2);
						} else {
							if ($acao == "altera") {
								$matriz2[3] = round($ponto[0]["r10_valor"] + $valor_pensao,2);
							} else {
								$matriz2[3] = round($valor_pensao,2);
							}
						}
						$matriz2[4] = 1;
						$matriz2[5] = $pensao[$Ipensao]["r01_lotac"];
						$matriz2[6] = $anousu;
						$matriz2[7] = $mesusu;
						$matriz2[8] = $DB_instit;

					} else if ($opcao_geral == PONTO_FERIAS ) {

						$matriz1 = array();
						$matriz2 = array();
						$matriz1[1] = "r29_regist";
						$matriz1[2] = "r29_rubric";
						$matriz1[3] = "r29_valor";
						$matriz1[4] = "r29_quant";
						$matriz1[5] = "r29_lotac";
						$matriz1[6] = "r29_media";
						$matriz1[7] = "r29_calc";
						$matriz1[8] = "r29_tpp";
						$matriz1[9] = "r29_anousu";
						$matriz1[10]= "r29_mesusu";
						$matriz1[11]= "r29_instit";

						$matriz2[1] = $pensao[$Ipensao]["r52_regist"];
						$matriz2[2] = $rubrica_pensao;

						if ($primeira_pensao) {
							$matriz2[3] = round($valor_pensao,2);
						} else {
							if ($acao == "altera") {
								$matriz2[3] = round($ponto[0]["r29_valor"] + $valor_pensao,2);
							} else {
								$matriz2[3] = round($valor_pensao,2);
							}
						}
						$matriz2[4] = 1;
						$matriz2[5] = $pensao[$Ipensao]["r01_lotac"];
						$matriz2[6] = 0;
						$matriz2[7] = 0;
						$matriz2[8] = " ";
						$matriz2[9] = $anousu;
						$matriz2[10] = $mesusu;
						$matriz2[11] = $DB_instit;
					} else if ($opcao_geral == PONTO_COMPLEMENTAR) {

						$matriz1 = array();
						$matriz2 = array();

						$matriz1[1] = "r47_regist";
						$matriz1[2] = "r47_rubric";
						$matriz1[3] = "r47_valor";
						$matriz1[4] = "r47_quant";
						$matriz1[5] = "r47_lotac";
						$matriz1[6] = "r47_anousu";
						$matriz1[7] = "r47_mesusu";
						$matriz1[8]=  "r47_instit";

						$matriz2[1] = $pensao[$Ipensao]["r52_regist"];
						$matriz2[2] = $cfpess[0]["r11_palime"];
						if ($primeira_pensao) {
							$matriz2[3] = round($valor_pensao,2 );
						} else {
							if ($acao == "altera") {
								$matriz2[3] = round($ponto[0]["r47_valor"] + $valor_pensao, 2);
							} else {
								$matriz2[3] =  round($valor_pensao,2 );
							}
						}
						$matriz2[4] = 1;
						$matriz2[5] = $pensao[$Ipensao]["r01_lotac"];
						$matriz2[6] = $anousu;
						$matriz2[7] = $mesusu;
						$matriz2[8] = $DB_instit;

					} else if ($opcao_geral == PONTO_RESCISAO) {

						$matriz1 = array();
						$matriz2 = array();

						$matriz1[1] = "r19_regist";
						$matriz1[2] = "r19_rubric";
						$matriz1[3] = "r19_valor";
						$matriz1[4] = "r19_quant";
						$matriz1[5] = "r19_lotac";
						$matriz1[6] = "r19_tpp";
						$matriz1[7] = "r19_anousu";
						$matriz1[8] = "r19_mesusu";
						$matriz1[9]=  "r19_instit";

						$matriz2[1] = $pensao[$Ipensao]["r52_regist"];
						$matriz2[2] = $cfpess[0]["r11_palime"];
						if ($primeira_pensao) {
							$matriz2[3] = round($valor_pensao,2);
						} else {
							if ($acao == "altera") {
								$matriz2[3] = round($ponto[0]["r19_valor"] + $valor_pensao,2);
							} else {
								$matriz2[3] = round($valor_pensao,2);
							}
						}
						$matriz2[4] = 1;
						$matriz2[5] = $pensao[$Ipensao]["r01_lotac"];
						$matriz2[6] = " ";
						$matriz2[7] = $anousu;
						$matriz2[8] = $mesusu;
						$matriz2[9] = $DB_instit;

					} else if ($opcao_geral == PONTO_13_SALARIO) {

						$matriz1 = array();
						$matriz2 = array();

						$matriz1[1] = "r34_regist";
						$matriz1[2] = "r34_rubric";
						$matriz1[3] = "r34_valor";
						$matriz1[4] = "r34_quant";
						$matriz1[5] = "r34_lotac";
						$matriz1[6] = "r34_media";
						$matriz1[7] = "r34_calc";
						$matriz1[8] = "r34_anousu";
						$matriz1[9] = "r34_mesusu";
						$matriz1[10]= "r34_instit";

						$matriz2[1] = $pensao[$Ipensao]["r52_regist"];
						$matriz2[2] = $rubrica_pensao;
						if ($primeira_pensao) {
							$matriz2[3] = $valor_pensao;
						} else {
							if ($acao == "altera") {
								$matriz2[3] = $ponto[0]["r34_valor"] + $valor_pensao;
							} else {
								$matriz2[3] = $valor_pensao;
							}
						}
            LogCalculoFolha::write("Ação para executar: {$acao} primeira_pensao: {$primeira_pensao}  valor: {$valor_pensao} Ponto: {$ponto[0]["r34_valor"]}");
						$matriz2[4] = 1;
						$matriz2[5] = $pensao[$Ipensao]["r01_lotac"];
						$matriz2[6] = 0;
						$matriz2[7] = 0;
						$matriz2[8] = $anousu;
						$matriz2[9] = $mesusu;
						$matriz2[10] = $DB_instit;

					}

					if ( DBPessoal::verificarUtilizacaoEstruturaSuplementar() && ($chamada_geral_arquivo == "gerfsal" || $chamada_geral_arquivo == "gerfcom") ) {

						$iMatricula       = $pensao[$Ipensao]["r52_regist"];
						$iCGMBeneficiario = $pensao[$Ipensao]["r52_numcgm"];
						self::$aPensoes[$iMatricula][$iCGMBeneficiario] = $matriz2[3];
						/**
						 * Pega o valor de todas as pensões lançadas anteriormente em salário e suplementar
						 */
						$iTipoFolha = "1,6";//Salario e suplementar 

						if ($chamada_geral_arquivo == "gerfcom") { 
							$iTipoFolha = "3";
						}
						$sSqlValorPensao   = "select coalesce(sum(rh145_valor), 0) as valor    ";
						$sSqlValorPensao  .= "  from rhhistoricopensao                         ";
						$sSqlValorPensao  .= "       inner join rhfolhapagamento  on rh145_rhfolhapagamento = rh141_sequencial ";
						$sSqlValorPensao  .= "                                   and rh141_tipofolha in ($iTipoFolha) ";
						$sSqlValorPensao  .= " where rh145_pensao           = {$iCodigoPensao} ";

						LogCalculoFolha::write($sSqlValorPensao);
						$rsValorAtualizado = db_query($sSqlValorPensao);
						$nValorPensao      = db_utils::fieldsMemory($rsValorAtualizado,0)->valor;

						LogCalculoFolha::write("***Valor total de pensoes..................: {$matriz2[3]}");
						$nValorPensao      = $matriz2[3] - $nValorPensao;
						LogCalculoFolha::write("***Valor da pensão nesta Folha de Pagamento: {$nValorPensao}");

						$matriz2[3] = array_sum(self::$aPensoes[$iMatricula]);

						LogCalculoFolha::write("Valor acumulado de pensoes.................: {$matriz2[3]}");
					}
				}

				$condicaoaux  = " and ".$siglap."regist = ".db_sqlformat($pensao[$Ipensao]["r52_regist"] );
				$condicaoaux .= " and ".$siglap."rubric = ".db_sqlformat($rubrica_pensao );
				if ( count($matriz1) > 2) {
					if ($acao == "altera"  ) {

						LogCalculoFolha::write("Valores alterados no ponto $qual_ponto");
						LogCalculoFolha::write(print_r(array_combine($matriz1, $matriz2),true));
						db_update($qual_ponto, $matriz1, $matriz2, bb_condicaosubpes($siglap).$condicaoaux );
					} else {

						LogCalculoFolha::write("Valores inseridos no ponto $qual_ponto");
						LogCalculoFolha::write(print_r(array_combine($matriz1, $matriz2),true));
						db_insert($qual_ponto, $matriz1, $matriz2 );
					}

				}

				$matriz1      = array();
				$matriz2      = array();
				$registrop    = $pensao[$Ipensao]["r52_regist"];
				$numcgmp      = $pensao[$Ipensao]["r52_numcgm"];
				$condicaoaux  = " and r52_regist = " . db_sqlformat($registrop);
				$condicaoaux .= " and r52_numcgm = " . db_sqlformat($numcgmp);

				if ($opcao_geral == 5) {

					$matriz1[1] = "r52_val13";
					$matriz2[1] = $valor_pensao;

					LogCalculoFolha::write("Alterando o valor do campo r52_val13 para {$valor_pensao}");
					$retornar = db_update("pensao", $matriz1, $matriz2, bb_condicaosubpes("r52_").$condicaoaux );

				} else if ($opcao_geral == PONTO_COMPLEMENTAR) {

					$matriz1[1] = "r52_valcom";
					$matriz2[1] = $valor_pensao;

					LogCalculoFolha::write("Alterando o valor do campo r52_valcom para {$valor_pensao}");
					$retornar = db_update("pensao", $matriz1, $matriz2, bb_condicaosubpes("r52_").$condicaoaux );

					if ( DBPessoal::verificarUtilizacaoEstruturaSuplementar() ) {
						self::salvarHistorico( $registrop, $numcgmp, $nValorPensao );
					}
				} else if ($opcao_geral == 3) {

					$matriz1[1] = "r52_valfer";
					$matriz2[1] = $valor_pensao;

					LogCalculoFolha::write("Alterando o valor do campo r52_valfer para {$valor_pensao}");
					$retornar = db_update("pensao", $matriz1, $matriz2, bb_condicaosubpes("r52_").$condicaoaux );

				} else if ($opcao_geral == 1) {

					$matriz1[1] = "r52_valor";
					$matriz2[1] = $valor_pensao;
					LogCalculoFolha::write("Alterando o valor do campo r52_valor para ".round($ponto[0]["r10_valor"] + $valor_pensao,2));
					$retornar = db_update("pensao", $matriz1, $matriz2, bb_condicaosubpes("r52_").$condicaoaux );
					if ( DBPessoal::verificarUtilizacaoEstruturaSuplementar() ) {
						self::salvarHistorico( $registrop, $numcgmp, $nValorPensao );
					}
				} else if ($opcao_geral == 4) {

					$matriz1[1] = "r52_valres";
					$matriz2[1] = $valor_pensao;

					LogCalculoFolha::write("Alterando o valor do campo r52_valres para {$valor_pensao}");
					$retornar = db_update("pensao", $matriz1, $matriz2, bb_condicaosubpes("r52_").$condicaoaux );

				}
			}

			if ((!DBPessoal::verificarUtilizacaoEstruturaSuplementar()) ||
         (DBPessoal::verificarUtilizacaoEstruturaSuplementar() && !$lComplementarOUSalario)) {

		  	$primeira_pensao = false;
			}
		}

		$valor_pensao = 0;
		LogCalculoFolha::write("Fim do cálculo de pensão.");
	}

	/**
	 * removerHistorico
	 *
	 * @param mixed $iMatricula
	 * @param mixed $iCGMBeneficiario
	 * @static
	 * @access public
	 * @return void
	 */
	public static function removerHistorico($iMatricula, $iCGMBeneficiario) {

		if ( !DBPessoal::verificarUtilizacaoEstruturaSuplementar() ) {
			return true;
		}

		if ( !self::$oFolhaAtual ) {
			return false;
		}

		$oDaoPensao            = new cl_pensao();
		$oDaoRHHistoricoPensao = new cl_rhhistoricopensao();

		$iCodigoPensao         = $oDaoPensao->getSequencial($iMatricula, $iCGMBeneficiario);
		$iCodigoFolha          = self::$oFolhaAtual->getSequencial();

		$oDaoRHHistoricoPensao->excluir(null, "rh145_pensao = {$iCodigoPensao} and rh145_rhfolhapagamento = {$iCodigoFolha}");

		if ( $oDaoRHHistoricoPensao->erro_status == "0") {
			throw new DBException("Erro ao excluir dados do Histórico de Pensões");
		}

		LogCalculoFolha::write("Excluindo valor da pensão do histórico");
	}

	/**
	 * Salva o histórico de cálculo de pensão
	 * 
	 * @param  Integer $iMatricula
	 * @param  Integer $iCGMBeneficiario
	 * @param  Number  $nValorPensao
	 * @return void
	 */
	public static function salvarHistorico($iMatricula, $iCGMBeneficiario, $nValorPensao) {

		if ( !DBPessoal::verificarUtilizacaoEstruturaSuplementar() ) {
			return true;
		}

		if ( !self::$oFolhaAtual ) {
			return false;
		}

		if ( $nValorPensao == "0" ) {
			return false;
		}
		$oDaoPensao            = new cl_pensao();
		$oDaoRHHistoricoPensao = new cl_rhhistoricopensao();

		$iCodigoPensao         = $oDaoPensao->getSequencial( $iMatricula, $iCGMBeneficiario );
		$iCodigoFolha          = self::$oFolhaAtual->getSequencial();

		$oDaoRHHistoricoPensao->rh145_pensao           = $iCodigoPensao;
		$oDaoRHHistoricoPensao->rh145_rhfolhapagamento = $iCodigoFolha;
		$oDaoRHHistoricoPensao->rh145_valor            = "$nValorPensao";
		$oDaoRHHistoricoPensao->incluir(null);

		if ($oDaoRHHistoricoPensao->erro_status == "0") {

			throw new Exception("Erro ao incluir histórico de pensão.");
		}
		return true;
	}

}

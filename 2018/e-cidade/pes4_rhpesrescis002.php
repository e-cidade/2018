<?
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

if ( !empty($_POST['rh01_admiss']) && empty($_POST['r01_admiss']) ) {
  $_POST['r01_admiss'] = $_POST['rh01_admiss'];
}

function cadastro_164 (){

	global $cfpess,
	$cadferia,
	$d08_carnes,
	$db21_codcli,
	$datafim,
	$datainicio,
	$gerfcom,
	$gerffer,
	$gerfsal ,
	$gerfs13 ,
	$lotacaoatual,
	$m_media ,
	$m_quant,
	$m_rubr,
	$m_tipo,
	$m_valor,
	$matri2,
	$matric,
	$matriz1,
	$matriz2,
	$max,
	$nres,
	$ns13,
	$nsal,
	$nsaldo,
	$pessoal,
	$pontofr,
	$pontofs,
	$pontofx,
	$qten ,
	$r01_aviso,
	$r59_aviso,
	$r01_caub,
	$r01_causa,
	$r01_mremun,$qmeses,
	$r01_recis,
	$r01_taviso,
	$rescisao,
	$rubricas,
	$subpes,
	$subpes_original,
	$pagar_13_salario_na_rescisao,
	$vlrn;

	global $sequencia,$r01_admiss,$r01_recis, $r01_mrenum;
	global $rh05_codigoseguranca, $rh05_trct;

	$sql = "select rh22_codaec    as r11_codaec
	rh22_natest    as r11_natest
	rh22_cdfpas    as r11_cdfpas
	rh22_cdactr    as r11_cdactr
	rh22_peactr    as r11_peactr
	rh22_pctemp    as r11_pctemp
	rh22_pcterc    as r11_pcterc
	rh22_fgts      as r11_fgts
	rh22_cdcef     as r11_cdcef
	rh22_cdfgts    as r11_cdfgts
	rh22_arredn    as r11_arredn
	rh22_sald13    as r11_sald13
	rh22_ultreg    as r11_ultreg
	rh22_mes13     as r11_mes13
	rh22_tbprev    as r11_tbprev
	rh22_confer    as r11_confer
	rh22_implan    as r11_implan
	rh22_codestrut as r11_codestrut
	rh22_anoatu    as r11_anoatu
	rh22_mesatu    as r11_mesatu
	rh22_dtconv    as r11_dtconv
	rh23_rubmat    as r11_rubmat
	rh23_rubdec    as r11_rubdec
	rh23_palime    as r11_palime
	rh23_ferias    as r11_ferias
	rh23_fer13     as r11_fer13
	rh23_ferant    as r11_ferant
	rh23_fer13o    as r11_fer13o
	rh23_fer13a    as r11_fer13a
	rh23_ferabo    as r11_ferabo
	rh23_feabot    as r11_feabot
	rh23_feradi    as r11_feradi
	rh23_fadiab    as r11_fadiab
	from rhcfpess,rhcfpessrub";

	//db_selectmax("cfpess",$sql);
	db_selectmax("cfpess","select * from cfpess where r11_anousu=".db_substr($subpes,1,4)." and r11_mesusu=".db_substr($subpes,-2)." and r11_instit = ".db_getsession("DB_instit") );

	$refazer_13_do_mes = true;
	$subpes_original = $subpes;

	$m_rubr = array();
	$m_quant= array();
	$m_valor= array();
	$m_media= array();
	$m_tipo = array();
	$qten   = array();
	$vlrn   = array();
	$nsaldo = 30;

	global $Ipessoal;
	$Ipessoal = 0;

	$condicaoaux = " and rh01_regist = " .db_sqlformat($matric);

	$sql = "select rh01_regist as r01_regist,
	rh02_hrsmen as r01_hrsmen,
	trim(to_char(rh02_lota,'9999')) as r01_lotac,
	rh01_numcgm as r01_numcgm,
	rh30_regime as r01_regime,
	rh02_tbprev as r01_tbprev,
	rh01_admiss as r01_admiss
	from rhpessoalmov
	inner join rhpessoal     on  rhpessoal.rh01_regist   = rhpessoalmov.rh02_regist
	left  join rhpespadrao   on  rhpespadrao.rh03_seqpes = rhpessoalmov.rh02_seqpes
	left join rhregime       on  rhregime.rh30_codreg    = rhpessoalmov.rh02_codreg
	and  rhregime.rh30_instit    = rhpessoalmov.rh02_instit";

	db_selectmax( "pessoal",$sql.bb_condicaosubpes( "rh02_" ).$condicaoaux );


	$lotacaoatual = $pessoal[0]["r01_lotac"];

	$retornar = true;

	if($pagar_13_salario_na_rescisao == 'true'){
		if( db_boolean( $rescisao[0]["r59_13sal"] )){
			$condicaoaux = " and r35_regist = " . db_sqlformat( $matric );
			if( db_selectmax( "gerfs13", "select * from gerfs13 ". bb_condicaosubpesproc("r35_",$r01_recis).$condicaoaux )){
				db_delete( "gerfs13", bb_condicaosubpesproc("r35_",$r01_recis).$condicaoaux );
				$condicaoaux = " and r34_regist = " . db_sqlformat( $matric );
				db_delete( "pontof13", bb_condicaosubpesproc("r34_",$r01_recis).$condicaoaux );
			}
		}
	}

	$condicaoaux = " and r19_regist = " . db_sqlformat( $matric );
	db_delete("pontofr", bb_condicaosubpes("r19_").$condicaoaux );
	$nsal = 0;
	$nres = 0;
	$ns13 = 0;
	$nind = 0;
	$nm13 = 0;
	$ndias = 0;
	$datres = db_str(db_year($r01_recis),1,4,"0")."/".db_str(db_month($r01_recis),2,0,'0');
	$datavi = db_str(db_year($r01_aviso),1,4,"0")."/".db_str(db_month($r01_aviso),2,0,'0');
	global $dias_mes;
	$meses_afastado = calcula_afastamentos();
	//echo "<BR> datres --> $datres datavi --> $datavi subpes --> $subpes r01_regime --> ".$pessoal[0]["r01_regime"];
	// Estatutario e Extra-quadro
	if( $pessoal[0]["r01_regime"] == 1 || $pessoal[0]["r01_regime"] == 3){
		//echo "<BR> 1 passou aqui !!";
		if( $datres < $subpes){
			//echo "<BR> 2 passou aqui !!";
			$nsal = 0;
			$nres = 0;
		}else{
			//echo "<BR> 3 passou aqui !!";
			$nres = 0;
			$ndias = db_datedif($r01_recis,db_ctod("01/".db_substr($subpes,6,2)."/".db_substr($subpes,1,4))) + 1;
			$nsal = $ndias;
			if( $nsal == 31){
				//echo "<BR> 7 passou aqui !!";
				$nsal = 30;
				$ndias = $nsal;
			}
			//echo "<BR> nsal 3.2 --> $nsal";
		}
	}else{  // CLT
		//echo "<BR> 4 passou aqui !!";
		if( $datavi == $subpes && $datres > $subpes ){
			//echo "<BR> 5 passou aqui !!";
			if( $r01_taviso != 2  ){ // Diferente de Indenizado
				//echo "<BR> 6 passou aqui !!";
				$nsal = db_datedif($r01_recis,db_ctod("01/".db_str(db_month($r01_recis),2,0,"0")."/".db_str(db_year($r01_recis),4)));
				if( $nsal == 31){
					//echo "<BR> 7 passou aqui !!";
					$nsal = 30;
				}
				$nsal += 30;
				$nres = db_datedif($r01_recis,$r01_aviso);
				//echo "<BR> 8 recis --> ".$r01_recis." datavi --> ".$r01_aviso;
			}
		}else if( $datres == $subpes){
			$nsal = db_day($r01_recis);
			//echo "<BR> nsal 9 --> $nsal";
			if( $datavi != "0/00" && $datavi <= $subpes){
				$nres = db_datedif($r01_recis,$r01_aviso);
			}
			if( $r01_taviso != 2 ){ // Diferente de Indenizado
				$nres = 0;
				if( $nsal == 31){
					$nsal = 30;
					//echo "<BR> nsal 2 --> $nsal";
				}
				//echo "<BR> 10 recis --> ".$r01_recis." datavi --> ".$datavi;

			}else{
				$nres = 30;
				//echo "<BR> nsal 11 --> $nsal";
			}
		}
	}
	// Aviso Previo e Indenizavel
	if($r59_aviso == 't' && $r01_taviso == 2){
		$nres = 30;
	}
	if( db_month($pessoal[0]["r01_admiss"] ) == db_val(db_substr($subpes,6,2)) && db_year($pessoal[0]["r01_admiss"] ) == db_val(db_substr($subpes,1,4)) ){
		$nsal -= db_day( $pessoal[0]["r01_admiss"] );
		//echo "<BR> 12 nsal --> $nsal";
		$nsal++ ;
		//echo "<BR> 13 nsal --> $nsal";
	}
	//// dias de salario a serem pagos na rescisao.
	//echo "<BR> 14 nsal --> $nsal";
	//echo "<BR> 14.1 ndias --> $ndias    dias_mes --> $dias_mes";
	global $dias_afastados;
	$res_afastados = db_query("select 30 - fc_dias_trabalhados(" . db_sqlformat( $matric ).",".db_str(db_year($r01_recis),4).",".db_str(db_month($r01_recis),2,0,"0").",false,".db_getsession('DB_instit').") as dias_afastados" );
	db_fieldsmemory($res_afastados,0,1);
	//echo "<BR> 14 dias_afastados --> $dias_afastados";
	if($dias_afastados > 0){
		$nsal = $ndias - $dias_afastados;
	}
	if($nsal < 0){
		$nsal = 0;
	}
		//echo "<br><15 br>". $r01_aviso."  ". $r01_taviso;
    //echo "<BR> 16 nsal --> $nsal";
    //echo "<BR> 17 nres --> $nres";
    //echo "<BR> 18 ndias --> $ndias";
    //echo "<BR> 19 dias_afastados --> $dias_afastados";
    //exit;
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
	$matriz1[9] = "r19_instit";


	if( $nsal != 0){
		// vai gerar o ponto de rescisao proporcional , baseado no ponto fixo, mas baseado nos dias de salario
		// a rescisao se baseia no Ponto Fixo
		salres($matric,$nsal);
	}

	$condicaoaux = " and r10_regist = " .db_sqlformat( $matric );
  db_selectmax("pontofs", "select * from pontofs " .bb_condicaosubpes("r10_").$condicaoaux );

	for($Ipontofs=0;$Ipontofs<count($pontofs);$Ipontofs++){

		$condicaoaux = " where rh27_instit = ". db_getsession("DB_instit") ." and rh27_rubric = " .db_sqlformat( $pontofs[$Ipontofs]["r10_rubric"] );
				if(db_selectmax("rubricas", "select * from rhrubricas ".$condicaoaux )
					    && $rubricas[0]["rh27_tipo"] == 2 ){

			$matriz2[1] = $pontofs[$Ipontofs]["r10_regist"];
			$matriz2[2] = $pontofs[$Ipontofs]["r10_rubric"];
			$matriz2[5] = $lotacaoatual;
			$matriz2[6] = "S";
			$matriz2[7] = db_val(db_substr($subpes,1,4));
			$matriz2[8] = db_val(db_substr($subpes, -2));
			$matriz2[9] = db_getsession("DB_instit");

			$condicaoaux  = " and r19_regist = " .db_sqlformat( $pessoal[0]["r01_regist"] );
			$condicaoaux .= " and r19_rubric = " .db_sqlformat( $pontofs[$Ipontofs]["r10_rubric"] );
      global $pontofr;


      /**
       * Valor achado no ponto de Salário.
       * O Valor no ponto de Salário sobrescreve o valor do ponto fixo.
       * No caso de ser proporcional(quantidade/valor no cadastro de rubricas). Proporcionaliza os dias trabalhados
       */
      $nValor      = $pontofs[$Ipontofs]["r10_valor"];
      $nQuantidade = $pontofs[$Ipontofs]["r10_quant"];

      if ( !!db_boolean($rubricas[0]["rh27_calcp"]) ) {
        $nValor      =  round( ($nValor/30) * $nsal );
        echo "Valor Modificado: {$nValor}<br>";
      }

      if ( !!db_boolean($rubricas[0]["rh27_propq"]) ) {
        $nQuantidade =  round( ($nQuantidade/30) * $nsal );
        echo "Quantidade Modificado: {$nQuantidade}<br>";
      }

      $matriz2[3] = $nValor;
      $matriz2[4] = $nQuantidade;

			if( db_selectmax( "pontofr", "select * from pontofr ". bb_condicaosubpes("r19_").$condicaoaux )){
				db_update( "pontofr", $matriz1, $matriz2, bb_condicaosubpes("r19_").$condicaoaux );
			}else{
				db_insert( "pontofr", $matriz1, $matriz2 );
			}

		}
  }

	if( $nres != 0 ){

		// vai gerar o ponto de rescisao proporcional , baseado no ponto fixo
		$condicaoaux = " and r90_regist = ".db_sqlformat( $matric );
		global $pontofx;
		db_selectmax("pontofx", "select * from pontofx ".bb_condicaosubpes("r90_").$condicaoaux );
		for($Ipontofx=0;$Ipontofx<count($pontofx);$Ipontofx++){
			$condicaoaux = " where rh27_instit = ". db_getsession("DB_instit") ." and rh27_rubric = ".db_sqlformat( $pontofx[$Ipontofx]["r90_rubric"] );

			if( db_selectmax( "rubricas", "select * from rhrubricas ".$condicaoaux )){
				$matriz2[1] = $pontofx[$Ipontofx]["r90_regist"];
				$matriz2[2] = db_str(db_val($pontofx[$Ipontofx]["r90_rubric"])+6000,4);
				$matriz2[3] = round((db_empty($pontofx[$Ipontofx]["r90_valor"])?0:($pontofx[$Ipontofx]["r90_valor"]/30)*$nres),2);
				$matriz2[4] = round((db_empty($pontofx[$Ipontofx]["r90_quant"])?0:($pontofx[$Ipontofx]["r90_quant"]/30)*$nres),2);
				$matriz2[5] = $lotacaoatual;
				$matriz2[6] = "R";
				$matriz2[7] = db_val( db_substr( $subpes,1,4 ) );
				$matriz2[8] = db_val( db_substr( $subpes, -2 ) );
				$matriz2[9] = db_getsession("DB_instit");

				db_insert( "pontofr", $matriz1, $matriz2 );
			}
		}
	}

	$datafim = date("Y-m-d",db_mktime($r01_recis)+(($r01_taviso == 2 ?($nres*86400) : 0 )));

	// Tem 13 salario Proporcional
	if( db_boolean($rescisao[0]["r59_13sal"]) && $cfpess[0]["r11_mes13"] >= db_month( $r01_recis )){

		$gera_13sal = true;
		if($pagar_13_salario_na_rescisao == 'false' && $cfpess[0]["r11_mes13"] == db_month($r01_recis)){
			$gera_13sal = false;
		}
		if($gera_13sal){
			gera_13_salario( $datafim );
		}
	}

	// Paga ferias vencidas ou Paga ferias Proporcionais
	if( db_boolean($rescisao[0]["r59_fvenc"]) || db_boolean($rescisao[0]["r59_fprop"])){

		$datarescisao = date("Y-m-d",db_mktime($r01_recis) + (( $r01_taviso == 2? $nres: 0 )*86400));
		$tipoferias = " ";
		$dias_diferenca_ferias = 0;
		$condicaoaux =  " and r30_regist = ".db_sqlformat( $matric );
		$condicaoaux .= " order by r30_perai desc";

		if( !db_selectmax( "cadferia", "select * from cadferia ".bb_condicaosubpes("r30_").$condicaoaux )){
			$datainicio = $pessoal[0]["r01_admiss"];
		}else{

			if( $cadferia[0]["r30_ndias"] > ($cadferia[0]["r30_dias1"] + $cadferia[0]["r30_dias2"] + $cadferia[0]["r30_abono"]) ){

				$dias_diferenca_ferias = $cadferia[0]["r30_ndias"] - ($cadferia[0]["r30_dias1"] + $cadferia[0]["r30_dias2"] + $cadferia[0]["r30_abono"] );
				$datainicio = $cadferia[0]["r30_perai"];
				$tipoferias = "D";

			}else{
				$datainicio = date("Y-m-d",db_mktime($cadferia[0]["r30_peraf"]) + 86400);
			}
		}

		if( strtolower($tipoferias) != "d"){
			if( db_substr(db_dtoc($datainicio),1,2) > "28" && db_substr(db_dtoc($datainicio),4,2) == "02"){
				$dataconsiderar = "28/02/";
			}else{
				$dataconsiderar = db_substr(db_dtoc($datainicio),1,6);
			}
			$datafim = date("Y-m-d",db_mktime(db_ctod($dataconsiderar.db_str((db_year($datainicio)+1),4,0,"0"))) - 86400);
			if( db_mktime($datafim) > db_mktime($datarescisao)){
				$datafim = $datarescisao       ;
			}
		}else{
			$datafim = $cadferia[0]["r30_peraf"];
		}
		$qtdvencidas = 0;
		while (db_mktime($datainicio) < db_mktime($datarescisao)){
			//echo "<BR> datarescisao 1.1 --> $datarescisao";
			//echo "<BR> datainicio   1.1 --> $datainicio";
			//echo "<BR> datafim      1.1 --> $datafim";
			$lancarferias = true;
			if( strtolower($tipoferias) != "d"){
				if( bcdiv(db_datedif($datafim,$datainicio),30,0) == 12){
					$tipoferias = "V";

					//echo "<BR> tipoferias 1.1 --> $tipoferias";
				}else{
					$tipoferias = "P";
					//echo "<BR> tipoferias 1.2 --> $tipoferias";
				}
			}
			if( strtolower($tipoferias) == "d"){
				$tipoferias = " ";
			}
			// Paga ferias Vencidas
			if( db_boolean($rescisao[0]["r59_fvenc"]) && strtolower($tipoferias) == "v"){

				if( afas_periodo_aquisitivo( $datainicio,$datafim ) <= 180){
					//echo "<BR> afas_periodo_aquisitivo é menor que 180";
					ferias_para_rescisao( $datainicio, $datafim, $tipoferias );
				}else{
					$lancarferias = false;
				}
				$qtdvencidas += 1;
			}
			// Paga ferias Proporcional
			if( db_boolean($rescisao[0]["r59_fprop"])  && strtolower($tipoferias) == "p"){
				ferias_para_rescisao( $datainicio, $datafim, $tipoferias );
			}
			$datainicio = date("Y-m-d",(db_mktime($datafim) + 86400));
			$datafim = date("Y-m-d",db_mktime(db_ctod(db_substr(db_dtoc($datainicio),1,6).db_str((db_year($datainicio)+1),4,0,"0"))) - 86400);
			//echo "<BR> datainicio   1.2 --> $datainicio";
			//echo "<BR> datafim      1.2 --> $datafim";
			if( db_mktime($datafim) > db_mktime($datarescisao)){
				$datafim = $datarescisao;
				//echo "<BR> datafim      1.3 --> $datafim";
			}
		}
	}
	// Pagar Indenizacao conforme 479 CLT
	if( db_boolean($rescisao[0]["r59_479clt"])){
		$matriz2[1] = $matric;
		$matriz2[2] = "R937";
		$matriz2[3] = 0;
		$matriz2[4] = 1;
		$matriz2[5] = $lotacaoatual;
		$matriz2[6] = "S";
		$matriz2[7] = db_val( db_substr( $subpes,1,4 ) );
		$matriz2[8] = db_val( db_substr( $subpes, -2 ) );
		$matriz2[9] = db_getsession("DB_instit");

		db_insert( "pontofr", $matriz1, $matriz2 );
	}

	$subpes = $subpes_original;

	$condicaoaux = " and r14_regist = ".db_sqlformat( $matric );
	db_delete( "gerfsal", bb_condicaosubpes("r14_").$condicaoaux );

	$condicaoaux  = " and r60_numcgm = ".db_sqlformat( $pessoal[0]["r01_numcgm"] );
	$condicaoaux .= " and r60_tbprev = ".db_sqlformat( $pessoal[0]["r01_tbprev"] );
	db_delete( "previden", bb_condicaosubpes("r60_").$condicaoaux );

	$condicaoaux  = " and r61_numcgm = ".db_sqlformat( $pessoal[0]["r01_numcgm"] );
	db_delete( "ajusteir", bb_condicaosubpes("r61_").$condicaoaux );

	global $pensao;
	$condicaoaux  = " and  rh05_recis is null ";
	$condicaoaux .= " and r52_regist = ".db_sqlformat($matric);
	$condicaoaux .= " order by r52_regist ";
	$sql = "select distinct(r52_regist+r52_numcgm),
	r52_regist,
	r52_numcgm
	from pensao
	inner join rhpessoalmov on pensao.r52_anousu         = rhpessoalmov.rh02_anousu
	and pensao.r52_mesusu         = rhpessoalmov.rh02_mesusu
	and pensao.r52_regist         = rhpessoalmov.rh02_regist
	left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes
	".bb_condicaosubpes("r52_" ).$condicaoaux ;
	db_selectmax("pensao", $sql);
	for ($Ipensao=0; $Ipensao<count($pensao); $Ipensao++) {
		$matriz1 = array();
		$matriz2 = array();
		$condicaoaux  = " and r52_regist = ".db_sqlformat($pensao[$Ipensao]["r52_regist"]);
		$condicaoaux .= " and r52_numcgm = ".db_sqlformat($pensao[$Ipensao]["r52_numcgm"]);

		$matriz1[1] = "r52_valor";
		$matriz1[2] = "r52_valcom";
		$matriz1[3] = "r52_val13";
		$matriz1[4] = "r52_valfer";
		$matriz2[1] = 0;
		$matriz2[2] = 0;
		$matriz2[3] = 0;
		$matriz2[4] = 0;
		$retornar = db_update("pensao", $matriz1, $matriz2, bb_condicaosubpes("r52_").$condicaoaux );
	}

	$matriz1 = array();
	$matriz2 = array();
	$matriz1[1] = "rh05_seqpes" ;
	$matriz1[2] = "rh05_recis"  ;
	$matriz1[3] = "rh05_causa"  ;
	$matriz1[4] = "rh05_caub"  ;
	$matriz1[5] = "rh05_aviso"  ;
	$matriz1[6] = "rh05_taviso" ;
	$matriz1[7] = "rh05_mremun" ;
	$matriz1[8] = "rh05_codigoseguranca" ;
	$matriz1[9] = "rh05_trct" ;
	$matriz2[1] = $sequencia ;
	$matriz2[2] = db_nulldata($r01_recis);
	$matriz2[3] = $r01_causa  ;
	$matriz2[4] = $r01_caub  ;
	$matriz2[5] = db_nulldata($r01_aviso);
	$matriz2[6] = $r01_taviso ;
	$matriz2[7] = $r01_mremun;
	$matriz2[8] = $rh05_codigoseguranca;
	$matriz2[9] = $rh05_trct;

	//pg_exec("update pg_class set reltriggers = 0 where relname = 'rhpesrescisao'");
	db_insert( "rhpesrescisao", $matriz1, $matriz2 );
	//pg_exec("update pg_class set reltriggers = (select count(*) from pg_trigger where pg_class.oid = tgrelid) where relname = 'rhpesrescisao'");

  /**
   * Altera rhpesrescisao informando os avos de ferias, 13 salario e quantidade de ferias vencidas
   */
  global $iAvos13Salario, $iFeriasVencidas, $iAvosFeriasPeriodo;

  $oDaoRhpesrescisao = db_utils::getDao('rhpesrescisao');
  $oDaoRhpesrescisao->rh05_seqpes = $sequencia;
  $oDaoRhpesrescisao->rh05_feriasavos     = $iAvosFeriasPeriodo;
  $oDaoRhpesrescisao->rh05_feriasvencidas = $iFeriasVencidas;
  $oDaoRhpesrescisao->rh05_13salarioavos  = $iAvos13Salario;
  $oDaoRhpesrescisao->alterar($sequencia);

  /**
   * Procura afastamento com data de retorno maior ou igual data da rescisao ou sem data de retorno
   */
  $oDaoAfasta = db_utils::getDao('afasta');
  $sWhereAfastamento  = "r45_regist = {$matric} ";
  $sWhereAfastamento .= " and ( r45_dtreto >= '{$r01_recis}' or r45_dtreto is null )";

  $sSqlAfastamento    = $oDaoAfasta->sql_query_file(null, 'r45_codigo', null, $sWhereAfastamento);
  $rsAfastamento      = $oDaoAfasta->sql_record($sSqlAfastamento);
  $iTotalAfastamentos = $oDaoAfasta->numrows;

  /**
   * Encontrou afastamento para o servidor
   * altearao data de retorno para a mesma data
   */
  if ( $iTotalAfastamentos > 0 ) {

  	for ( $iIndice = 0; $iIndice < $iTotalAfastamentos; $iIndice++ ) {

  		$oAfastamento = db_utils::fieldsMemory($rsAfastamento, $iIndice);

  		$oDaoAfasta->r45_dtreto = $r01_recis;
  		$oDaoAfasta->r45_codigo = $oAfastamento->r45_codigo;
  		$oDaoAfasta->alterar($oAfastamento->r45_codigo);

  		/**
  		 * Erro ao alterar data do afastamento
  		 */
  		if($oDaoAfasta->erro_status == '0'){
  			db_msgbox(str_replace("\n", '\n', $oDaoAfasta->erro_msg));
  		}
  	}

  }

}



function salres($matric,$nsal ){

	global $matriz1, $matriz2, $rubricas, $pontofx, $lotacaoatual, $subpes;

	$retornar = true;
	$condicaoaux = " and r90_regist = ".db_sqlformat( $matric );

	db_selectmax("pontofx", "select * from pontofx ".bb_condicaosubpes("r90_").$condicaoaux );

	for($Ipontofx=0;$Ipontofx<count($pontofx);$Ipontofx++){

		$condicaoaux = " where rh27_instit = ". db_getsession("DB_instit") ." and rh27_rubric = ".db_sqlformat( $pontofx[$Ipontofx]["r90_rubric"] );

		if( db_selectmax( "rubricas", "select * from rhrubricas ".$condicaoaux )){

			$valor  = round((!db_boolean($rubricas[0]["rh27_calcp"])?$pontofx[$Ipontofx]["r90_valor"]:($pontofx[$Ipontofx]["r90_valor"]/30)*$nsal),2);
			$quanti = round((!db_boolean($rubricas[0]["rh27_propq"])?$pontofx[$Ipontofx]["r90_quant"]:($pontofx[$Ipontofx]["r90_quant"]/30)*$nsal),2);
			$matriz2[1] = $pontofx[$Ipontofx]["r90_regist"];
			$matriz2[2] = $pontofx[$Ipontofx]["r90_rubric"];
			$matriz2[3] = $valor;
			$matriz2[4] = $quanti;
			$matriz2[5] = $lotacaoatual;
			$matriz2[6] = "S";
			$matriz2[7] = db_val( db_substr( $subpes,1,4 ) );
			$matriz2[8] = db_val( db_substr( $subpes, -2 ) );
			$matriz2[9] = db_getsession("DB_instit");
			$retornar = db_insert( "pontofr", $matriz1, $matriz2 );
			if( $retornar == false ){
	   break;
			}
		}
	}
}

function traz_aviso (){

	global $r01_recis, $r01_aviso;

	$r01_aviso = $r01_recis - 30;
	return true ;
}




// ---------------- inicio do programa


global $cfpess,$subpes,$d08_carnes,$db21_codcli, $db_debug,$matric,$sequencia,$r01_admiss,$r01_recis, $r59_aviso ;
global $r01_causa, $r01_taviso, $r01_mremun, $r01_aviso,$pagar_13_salario_na_rescisao;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_libpessoal.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("pes4_avaliaferiasrescisao.php"));
require_once(modification("classes/db_rhpesrescisao_classe.php"));

db_postmemory($HTTP_POST_VARS);
db_inicio_transacao();

if(!isset($campomatriculas)){
	$matric = $rh01_regist;
	$r01_admiss = $rh01_admiss_ano."-".$rh01_admiss_mes."-".$rh01_admiss_dia;
}else{
	$matric = $r30_regist;
}
$sequencia = $rh02_seqpes;
$r01_recis  = $rh05_recis_ano."-".$rh05_recis_mes."-".$rh05_recis_dia;
$r01_aviso  = $rh05_aviso_ano."-".$rh05_aviso_mes."-".$rh05_aviso_dia;
$r01_causa  = $rh05_causa;
$r01_caub   = $rh05_caub;
$r01_taviso = $rh05_taviso;
$r01_mremun = $rh05_mremun;
global $db_config;
db_selectmax("db_config","select lower(trim(munic)) as d08_carnes , cgc, db21_codcli from db_config where codigo = ".db_getsession("DB_instit"));

if(trim($db_config[0]["cgc"]) == "90940172000138"){
	$d08_carnes = "daeb";
}else{
	$d08_carnes = $db_config[0]["d08_carnes"];
}
$db21_codcli = $db_config[0]["db21_codcli"];


$db_erro = false;

db_selectmax("rhregime","select * from rhregime where rh30_codreg = ".$rh02_codreg ."
		and rh30_instit = ".db_getsession("DB_instit"));

$subpes = db_anofolha().'/'.db_mesfolha();

$admissao_mais_um_ano = db_ctod( substr("#". db_dtoc($r01_admiss),1,6).db_str(db_year($r01_admiss)+1,4) );

$menos_um_ano = ( db_mktime($r01_recis) < db_mktime($admissao_mais_um_ano)? "S": "N" );

$condicao = " and r59_regime = ".$rhregime[0]["rh30_regime"];
$condicao.= " and r59_causa  = ".$rh05_causa;
$condicao.= " and trim(r59_caub)  = '".trim($rh05_caub)."'";
$condicao.= " and r59_menos1  = '".$menos_um_ano."'";
global $rescisao;
db_selectmax("rescisao","select * from rescisao ".bb_condicaosubpes("r59_").$condicao );

cadastro_164();

db_fim_transacao();
//sleep(2);
if(!isset($campomatriculas)){
	db_redireciona("pes4_rhpesrescis001.php");
}else{

	$_SESSION['campomatriculas'] = $campomatriculas;

	$qry  = "?r59_menos1=$r59_menos1&r30_regist=$r30_regist";
	$qry .= "&selecao=$selecao&tipo=$tipo&rh02_codreg=$rh02_codreg&rh02_seqpes=$rh02_seqpes&rh01_admiss=$rh01_admiss";
	$qry .= "&caub=$caub&causa=$causa&rescisao=$rescisao&taviso=$taviso&aviso=$aviso&remun=$remun";
	$qry .= "&descr=$descr&descr1=$descr1&rescisao=$rescisao&taviso=$taviso&aviso=$aviso&remun=$remun";
	$qry .= "&recis_ano=$recis_ano&recis_mes=$recis_mes&recis_dia=$recis_dia&aviso_ano=$aviso_ano&aviso_mes=$aviso_mes&aviso_dia=$aviso_dia";

	db_redireciona("pes4_rhpesrescis004.php".$qry);

}

?>

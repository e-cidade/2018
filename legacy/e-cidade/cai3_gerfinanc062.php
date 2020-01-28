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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//echo($HTTP_SERVER_VARS['QUERY_STRING']);
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_utils.php"));
include(modification("libs/db_usuariosonline.php"));
require(modification("libs/db_sql.php"));
require(modification("classes/db_termo_classe.php"));
require(modification("classes/db_cgm_classe.php"));
require(modification("classes/db_protprocesso_classe.php"));
include(modification("dbforms/db_funcoes.php"));
db_postmemory($HTTP_POST_VARS);

//print_r($HTTP_SESSION_VARS);

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$sDataVenc      = @date("Y-m-d",$oPost->H_DATAUSU);

$clcgm          = new cl_cgm;
$cltermo        = new cl_termo;
$clprotprocesso = new cl_protprocesso;
$clrotulo       = new rotulocampo;
$iCodProc       = 'null';
//db_msgbox(db_getsession("conteudoparc"));
// @$mostra = 1;
if (isset($envia) or @$mostra == 1) {
  $entra=true;
} else {
  $entra=false;
}
if(!session_is_registered("DB_tipodebitoparcel")){
  session_register("DB_tipodebitoparcel");
  db_putsession("DB_tipodebitoparcel",$tipo_debito);
}else{

  $tb = db_getsession("DB_tipodebitoparcel")."X".@$tipo_debito;
  db_putsession("DB_tipodebitoparcel",$tb);

}
$wheretipodeb = "";
$and = "";
$tipo = db_getsession("DB_tipodebitoparcel");
$tipodeb = split( 'X',$tipo );
for($tb = 0 ; $tb< count($tipodeb); $tb++){
  if($tipodeb[$tb]!=""){
    $wheretipodeb .= $and." exists (select k41_arretipo
					           from cadtipoparcdeb
					           where k41_cadtipoparc = k40_codigo
					             and k41_arretipo    = $tipodeb[$tb]
					           order by k41_arretipo)";
    $and = " and ";
  }
}


$conteudoaparcelar="";
$valoresportipo="";

$loteador = false;

if (!empty($ver_numcgm)) {

  $sqlloteador  = "  select *                                                                       ";
  $sqlloteador .= "    from loteam                                                                  ";
  $sqlloteador .= "         inner join loteamcgm  on loteamcgm.j120_loteam = loteam.j34_loteam      ";
  $sqlloteador .= "   where j120_cgm = {$ver_numcgm}                                                ";

	$resultloteador = db_query($sqlloteador) or die($sqlloteador);
	if (pg_numrows($resultloteador) > 0) {
		$loteador = true;
	}

}

$whereloteador = " and k40_forma <> 3";

if ($loteador == true) {
	$whereloteador = " and k40_forma = 3";
}

if((isset($ver_matric) or isset($ver_inscr) or (!empty($ver_numcgm))) and (!isset($numpre))){

  $lInicial = true;

  if(!isset($inicial)){
    $lInicial = false;
  }

  // Faz utilização da model responsavel pela captura e processamento da requisição de debitos da CGF
  $oGetalFinanceiraDebitos = new GeralFinanceiraDebitosRequest();
  $oGetalFinanceiraDebitos->processDebitosRequest($lInicial);

  // Remove todos os dados de debitos salvos apos processados
  $oGetalFinanceiraDebitos->clearDebitos();

  // Retorna strings formatadas conforme processamento dos debitos e necessidade para utilização no processamento abaixo
  $conteudoaparcelar = $oGetalFinanceiraDebitos->getConteudoParcelar();
  $numpre1           = $oGetalFinanceiraDebitos->getNumpres();
  $numpar1           = $oGetalFinanceiraDebitos->getNumpars();
  $numpres           = $oGetalFinanceiraDebitos->getNumpresString();

	if (isset($oPost->marcarvencidas) && isset($oPost->marcartodas)) {

	  if ($oPost->marcarvencidas == 'true' && $oPost->marcartodas == 'false') {

	    $aNumpres   = split("N", $numpres);
	    $numpres    = "";
	    $sNumPreAnt = "";
	    $sAuxiliar  = "";
	    for ($iInd = 0; $iInd < count($aNumpres); $iInd++) {

	      if ($aNumpres[$iInd] == "") {
	        continue;
	      }

	      $iNumpre = split("P",$aNumpres[$iInd]);
	      $iNumpar = split("P", strstr($aNumpres[$iInd],"P"));
	      $iNumpar = split("R",$iNumpar[1]);
	      $iReceit = $iNumpar[1];
	      $iNumpar = $iNumpar[0];
	      $iNumpre = $iNumpre[0];

	      $sSqlArrecad  = "  select *                               ";
	      $sSqlArrecad .= "    from arrecad                         ";
	      $sSqlArrecad .= "   where k00_numpre   = {$iNumpre}       ";
	      $sSqlArrecad .= "     and k00_numpar   = {$iNumpar}       ";
	      $sSqlArrecad .= "     and k00_dtvenc   > '{$sDataVenc}'   ";
	      $rsSqlArrecad = db_query($sSqlArrecad);
	      $iNumRows     = pg_num_rows($rsSqlArrecad);
	      if ($iNumRows == 0) {

	        if ($_POST["tipo_debito"] == 3 || $_POST["tipo_debito"] == 5) {

	          if (empty($sNumPreAnt) || $sNumPreAnt != $iNumpre) {

	            $sNumPreAnt = $iNumpre;
	            $sAuxiliar  = "N";
	          }

	          $numpres .= "{$sAuxiliar}N".$iNumpre."P".$iNumpar."R".$iReceit;
	          $sAuxiliar = "";
	        } else {
	          $numpres .= 'N'.$iNumpre."P".$iNumpar."R".$iReceit;
	        }
	      }

	    }
	  }

	}

  $numpres = split("N",$numpres);
  $totalregistrospassados = 0;
  for($i = 0; $i < sizeof($numpres); $i++) {
    $valores = split("P",$numpres[$i]);
    $totalregistrospassados += sizeof($valores)-1;
  }

} elseif (isset($numpre)) {
  $numpre1 = $numpre;
  $numpar1 = $numpar;
}

if(!session_is_registered("conteudoparc")) {
  session_register("conteudoparc");
  db_putsession("conteudoparc",$conteudoaparcelar);
} else {
  db_putsession("conteudoparc",db_getsession("conteudoparc").$conteudoaparcelar);
}

$sql_cgc = "select cgc, db21_codcli from db_config where codigo = ".db_getsession("DB_instit");
$rs_cgc = db_query($sql_cgc);
$oConfig = new stdClass();
$oConfig->cgc = pg_result($rs_cgc,0,0);
$oConfig->db21_codcli = pg_result($rs_cgc,0,1);

$iTemDesconto = 0;


echo "Selecione a regra de parcelamento: ";

$sqlcadtipoparc = "	select k40_codigo, k40_descr, k40_aplicacao, k40_ordem, tipovlr
					from cadtipoparc
					inner join tipoparc on k40_codigo = cadtipoparc
                    where k40_instit = ".db_getsession('DB_instit')."
						  and maxparc > 1 and '". date("Y-m-d",db_getsession("DB_datausu")) . "' >= k40_dtini
                          and '" . date("Y-m-d",db_getsession("DB_datausu")) . "' <= k40_dtfim $whereloteador
                          and ((
                                $wheretipodeb
                                )or
                          not exists (select k41_arretipo
                                      from cadtipoparcdeb
                                      where k41_cadtipoparc = k40_codigo ))";
$sqlcadtipoparc .= " order by k40_ordem ";

//echo "<br>$sqlcadtipoparc <br>";
$resultcadtipoparc = db_query($sqlcadtipoparc) or die($sqlcadtipoparc);

if (pg_numrows($resultcadtipoparc) == 0) {
  db_msgbox("Nao existem regras para parcelamento cadastrados na faixa da data atual ou conflito entre regras de parcelamento e tipos de débito! Contate suporte!");
  exit;
}

$arr = Array();
for($r=0; $r<pg_numrows($resultcadtipoparc); $r++){
  db_fieldsmemory($resultcadtipoparc,$r);
  $arr[$k40_codigo] = $k40_descr;
}
flush();

db_select("k40_cadtipoparc",$arr,true,1,"onchange='js_reload(this.value)'");

if (!isset($k40_cadtipoparc) and (pg_numrows($resultcadtipoparc) > 0)) {
  $k40_cadtipoparc = pg_result($resultcadtipoparc,0,0);
}

//Verificar tipo de débito e data limite
$lValidaReparcelamento = false;
$lBtnParcelar					 = false;

$sSqlDataLimite = "select k40_dtreparc,k40_qtdreparc,k40_regraunif,k40_bloqueio
												from cadtipoparc
                        where 1=1 ";
$sSqlDataLimite .= " and k40_codigo = $k40_cadtipoparc and k40_instit = ".db_getsession('DB_instit');
$rsSqlDataLimite = db_query($sSqlDataLimite);

if (pg_num_rows($rsSqlDataLimite) > 0) {
	db_fieldsmemory($rsSqlDataLimite,0);
} else {
	$lValidaReparcelamento 		= true;
	$sMensagemReparcelamento 	= "Usuário:\\n\\n Não foi possível verificar a data limite para reparcelamento! Contate suporte!\\n\\nAdministrador:\\n\\n";
}

if (($k03_tipo == 5 || $k03_tipo == 6 || $k03_tipo == 13 || $k03_tipo == 16 || $k03_tipo == 17) && !$lValidaReparcelamento) {

	if(trim($k40_dtreparc) != ""){
		$dDataatual   = date("Ymd",db_getsession("DB_datausu"));

		$dDataLimite  = ereg_replace("-","",$k40_dtreparc);
		if ($dDataatual > $dDataLimite && $k03_tipo != 5) {

			$lValidaReparcelamento 		= true;
			$lBtnParcelar 						= true;
			$sMensagemReparcelamento 	= "Excedida data limite para reparcelamento!";

		}
	}

	if (!$lValidaReparcelamento) {


		//Verifica o numero de reparcelamentos por numpre.
		$aNumpres = array();
		$matnumpres = explode("XXX",db_getsession("conteudoparc"));
		for ($contanumpres=0; $contanumpres < sizeof($matnumpres); $contanumpres++) {
			if ($matnumpres[$contanumpres] == "") {
				continue;

			}

	  	if (gettype(strpos($matnumpres[$contanumpres], "NUMPRE")) != "boolean") {
				$tiporeg = "NUMPRE";
			} else {
				$tiporeg = "INICIAL";
			}
			$registro = explode($tiporeg, $matnumpres[$contanumpres]);

		  if ($tiporeg == "NUMPRE") {
				$registros=explode("R", $registro[1]);
				$numpre=explode("P", $registros[0]);
				$numpar = $numpre[1];
				$numpre = substr($numpre[0],1);
				/*
				$sqltipo = "select k03_tipo as k03_tipodebito
									from arrecad
									inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre
									                     and arreinstit.k00_instit = ".db_getsession('DB_instit')."
									inner join arretipo on arrecad.k00_tipo = arretipo.k00_tipo
									where arrecad.k00_numpre = $numpre
									limit 1";
				$resulttipo = db_query($sqltipo) or die($sqltipo);
				db_fieldsmemory($resulttipo, 0);
				*/
			} else {
				$numpre = substr($registro[1],1);

	    	//$sqlprocura = "select * from NUMPRES_CALC where k00_numpre = $numpre and k00_numpar = 0";
			}
			$aNumpres[]=$numpre;
		}
		$aNumpres = array_unique($aNumpres);
		//echo "<pre>";
		//echo var_dump($aNumpres);
		//echo "</pre>";
	  //exit();
		foreach ($aNumpres as $value){
			$sSqlQtdPar = "select count(v07_desconto) as qtd from fc_origemparcelamento($value) as o inner join termo on riparcel = v07_parcel
																	where v07_desconto = $k40_cadtipoparc";
			$rsSqlQtdPar = db_query($sSqlQtdPar);

			if (pg_num_rows($rsSqlQtdPar) > 0) {
				db_fieldsmemory($rsSqlQtdPar,0);
				$k40_qtdreparc += 1 ;
				if ($qtd >= $k40_qtdreparc) {
					$lValidaReparcelamento	= true;
					$lBtnParcelar 					= true;
					$sMensagemReparcelamento  = "Usuário:\\n\\n Excedido limite de vezes para reparcelamento!\\n\\nAdministrador:\\n\\n";
					break;
				}
			}

		}
	}
}

$sIniciaisForo = '';
$sVirgula      = '';
$lPermitir     = false;
//Regra para não permitir parcelamento de uma inicial com mais de uma origem
if($k40_regraunif == 2) {

	if(isset($inicial)) {

		if(isset($numpres)) {
			foreach ($numpres as $iInicial) {
				if(trim($iInicial) != '') {
					$sIniciaisForo .= $sVirgula.$iInicial;
					$sVirgula       = ', ';
				}
			}
		}

		//se for selecionado o tipo de regra as iniciais estarao na variavel inicial

		$sIniciaisForo = $sIniciaisForo == '' ? $oGet->inicial : $sIniciaisForo;
		//Sql que verifica se inicial possui mais de uma origem
    $sSqlValidaOrigem  = " select distinct arrematric.k00_matric                                                 ";
    $sSqlValidaOrigem .= "            from inicial                                                               ";
    $sSqlValidaOrigem .= "      inner join inicialnumpre on inicialnumpre.v59_inicial = inicial.v50_inicial      ";
    $sSqlValidaOrigem .= "      inner join arrenumcgm    on arrenumcgm.k00_numpre     = inicialnumpre.v59_numpre ";
    $sSqlValidaOrigem .= "      inner join arrematric    on arrematric.k00_numpre     = arrenumcgm.k00_numpre    ";
    $sSqlValidaOrigem .= "           where inicial.v50_inicial in ({$sIniciaisForo})                             ";
    $sSqlValidaOrigem .= " union                                                                                 ";
    $sSqlValidaOrigem .= " select distinct arreinscr.k00_inscr                                                   ";
    $sSqlValidaOrigem .= "            from inicial                                                               ";
    $sSqlValidaOrigem .= "      inner join inicialnumpre on inicialnumpre.v59_inicial = inicial.v50_inicial      ";
    $sSqlValidaOrigem .= "      inner join arrenumcgm    on arrenumcgm.k00_numpre     = inicialnumpre.v59_numpre ";
    $sSqlValidaOrigem .= "      inner join arreinscr     on arreinscr.k00_numpre      = arrenumcgm.k00_numpre    ";
    $sSqlValidaOrigem .= "           where inicial.v50_inicial in ({$sIniciaisForo})                             ";

    $rsValidaOrigem = db_query($sSqlValidaOrigem) or die('Origens da inicial não encontradas');

		if(pg_num_rows($rsValidaOrigem) > 1) { // mais de uma origem
			$sMensagemReparcelamento = "Usuário:\\n\\n A regra está configurada para não permitir parcelar débitos que não sejam da sua própria origem!\\n\\nAdministrador:\\n\\n";
			$lValidaReparcelamento = true;
			$lBtnParcelar          = true;
			if($k40_bloqueio == 'f') {
				$lPermitir = true;
			}
		}

	}

}

if($entra == false) {

	$cadtipoparc = 0;

	$sqltipoparc = "select *
									from tipoparc
									     inner join cadtipoparc on cadtipoparc = k40_codigo
									where k40_instit = ".db_getsession('DB_instit')."
									  and maxparc > 1
										and '". date("Y-m-d",db_getsession("DB_datausu")) . "' >= k40_dtini
										and '" . date("Y-m-d",db_getsession("DB_datausu")) . "' <= k40_dtfim
										and	k40_codigo = $k40_cadtipoparc $whereloteador";
  $sqltipoparc .= " order by k40_ordem ";
	$resulttipoparc = db_query($sqltipoparc) or die($sqltipoparc);
	if (pg_numrows($resulttipoparc) > 0) {
		db_fieldsmemory($resulttipoparc,0);
	} else {
		$k40_todasmarc = false;
	}

	$sqltipoparcdeb = "	select * from cadtipoparcdeb
											where k41_cadtipoparc = $k40_cadtipoparc
											limit 1";
	$resulttipoparcdeb = db_query($sqltipoparcdeb);
	$passar = false;

	if(isset($inicial) && $inicial != "") {
//		$k03_tipo = 18;
		$totalregistrospassados = $totregistros;
	}

	if (pg_numrows($resulttipoparcdeb) == 0) {
		$passar = true;
	} else {
		$sqltipoparcdeb = "select * from cadtipoparcdeb where k41_cadtipoparc = $k40_cadtipoparc and k41_arretipo = $tipo_debito";
		$resulttipoparcdeb = db_query($sqltipoparcdeb);
		if (pg_numrows($resulttipoparcdeb) > 0) {
			$passar = true;
		}
	}

	if (!isset($totalregistrospassados)) {
		$totalregistrospassados = 0;
	}

	if (!isset($totregistros)) {
		$totregistros = 0;
	}

	if (pg_numrows($resulttipoparc) == 0 or ($k40_todasmarc == 't'?$totalregistrospassados <> $totregistros:false) or $passar == false) {
		$desconto = 0;
	} else {
		$desconto = $k40_codigo;
	}


	$tiposparc = "";

	for ( $parcelas=0; $parcelas < pg_numrows($resulttipoparc); $parcelas++ ) {
		db_fieldsmemory($resulttipoparc,$parcelas,true);
		if ($desconto == 0 and 1==2) {
			$descmul = 0;
			$descjur = 0;
		}
		$tiposparc .= $tipoparc . "=" . $maxparc . "=" . $descmul . "=" . $descjur . "=" . (int) $k42_minentrada . "=" . $k40_forma . "=" . $descvlr . "=" . $vlrmin . "=" . $tipovlr . "=" . $minparc . "=" . ($parcelas == (pg_numrows($resulttipoparc) -1)?"":"-");
	}

	if ($tiposparc == "") {
		db_msgbox("Nao existem regras para parcelamento cadastrados na faixa da data atual! Contate suporte!");
		exit;
	}

}

if((isset($inicial) && $inicial != "") and ( $entra == false)) {

  $numpre = $numpre1;
  $sql = " select v59_numpre,k00_numpar
           from inicialnumpre
           inner join arrecad    on v59_numpre = k00_numpre
		   inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre
			                    and arreinstit.k00_instit = ".db_getsession('DB_instit')."
           where v59_inicial in ($numpre) ";

  $result = db_query($sql) or die($sql);
  $numrows= pg_numrows($result);
  $virgula = "";
  $numpar1 = "";
  $numpre1 = "";
//  $k03_tipo = 18;
  for($j = 0;$j < $numrows;$j++) {
    db_fieldsmemory($result,$j);
    $numpar1 .= $virgula.$k00_numpar;
    $numpre1 .= $virgula.$v59_numpre;
    $virgula = ",";
  }
  $numpre = $numpre1;
  $numpar = $numpar1;
}

if(!session_is_registered("conteudoparc")) {
  session_register("conteudoparc");
//	db_msgbox('1 - '.$conteudoaparcelar);
  db_putsession("conteudoparc",$conteudoaparcelar);
} else {
//	db_msgbox('2 - '.db_getsession("conteudoparc").$conteudoaparcelar);
  db_putsession("conteudoparc",db_getsession("conteudoparc").$conteudoaparcelar);
}

//db_msgbox('antes da matriz --- '.db_getsession("conteudoparc"));
$matriz	= split("XXX", db_getsession("conteudoparc"));

$novamatrizval = array();

for ($x=0; $x < sizeof($matriz); $x++) {
  if ($matriz[$x] == "") {
    continue;
  }
  if (gettype(strpos($matriz[$x], "NUMPRE")) != "boolean") {
		$tiporeg = "NUMPRE";
	} else {
		$tiporeg = "INICIAL";
	}
	$registro  = split($tiporeg, $matriz[$x]);
	$registros = split("N", $registro[1]);
	for ($reg=0; $reg < sizeof($registros); $reg++) {
		if ($registros[$reg] == "") {
			continue;
		}
		if (!in_array($tiporeg . "N ". $registros[$reg], $novamatrizval)) {
			$novamatrizval[] = $tiporeg . "N" . $registros[$reg] . "XXX";
		}
	}
}

$conteudofinal="";
for ($x=0; $x < sizeof($novamatrizval); $x++) {
  $conteudofinal.=$novamatrizval[$x];
}

//db_msgbox('3 - '.$conteudofinal);

db_putsession("conteudoparc",$conteudofinal);

?>

<script>

parent.document.form1.japarcelou.value="1";

parent.document.form1.numpresaparcelar.value=parent.document.form1.numpresaparcelar.value + '<?=@$numpre1?>' + ',';
parent.document.form1.numparaparcelar.value=parent.document.form1.numparaparcelar.value + '<?=@$numpar1?>' + ',';

</script>

<?

//echo "\natual: " . db_getsession("conteudoparc") . "\n";

//@db_query("drop table numpres_calc");
//@db_query("drop table totalportipo");
//@db_query("drop index numpres_parc_in");
//@db_query("drop table numpres_parc");
//@db_query("drop table numpres_parc1");

//@db_query("delete from pg_class where relname='numpres_calc'");
//@db_query("delete from pg_class where relname='totalportipo'");

//@db_query("delete from pg_class where relname='numpres_parc_in'");
//@db_query("delete from pg_class where relname='numpres_parc'");
//@db_query("delete from pg_class where relname='numpres_parc1'");

db_query("begin");
$sql = "create temporary table NUMPRES_CALC (k00_numpre integer, k00_numpar integer) on commit drop";
db_query($sql) or die($sql);

$totalvlrhis			= 0;
$totalvlrcor			= 0;
$totalvlrjuros		= 0;
$totalvlrmulta		= 0;
$totalvlrdesconto = 0;
$totaltotal				= 0;

$sql= "create temporary table totalportipo (k03_tipodebito	integer,
																						k00_cadtipoparc	integer,
																						k00_vlrhis			float8,
																						k00_vlrcor			float8,
																						k00_juros				float8,
																						k00_multa				float8,
																						k00_desconto		float8,
																						k00_total				float8) on commit drop";
db_query($sql) or die($sql);
if (@$mostra == 1) {
	echo "<br>begin;<br>";
	echo $sql . ";<br>";
}

$sql= "create temporary table NUMPRES_PARC1 (k00_numpre integer, k00_numpar integer, k03_tipodebito integer) on commit drop";
db_query($sql) or die($sql);
if (@$mostra == 1) {
	echo $sql . ";<br>";
}
//db_msgbox("Teste : ".db_getsession("conteudoparc"));
$matnumpres = split("XXX",db_getsession("conteudoparc"));
for ($contanumpres=0; $contanumpres < sizeof($matnumpres); $contanumpres++) {
	if ($matnumpres[$contanumpres] == "") {
		continue;

	}

  if (gettype(strpos($matnumpres[$contanumpres], "NUMPRE")) != "boolean") {
		$tiporeg = "NUMPRE";
	} else {
		$tiporeg = "INICIAL";
	}
	$registro = split($tiporeg, $matnumpres[$contanumpres]);

  if ($tiporeg == "NUMPRE") {
		$registros=split("R", $registro[1]);
		$numpre=split("P", $registros[0]);
		$numpar = $numpre[1];
		$numpre = substr($numpre[0],1);

		$sqltipo = "select k03_tipo as k03_tipodebito
								from arrecad
								inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre
								                     and arreinstit.k00_instit = ".db_getsession('DB_instit')."
								inner join arretipo on arrecad.k00_tipo = arretipo.k00_tipo
								where arrecad.k00_numpre = $numpre
								limit 1";
		$resulttipo = db_query($sqltipo) or die($sqltipo);
		db_fieldsmemory($resulttipo, 0);

    $sqlprocura = "select * from NUMPRES_CALC where k00_numpre = $numpre and k00_numpar = $numpar";

	} else {
		$numpre = substr($registro[1],1);

    $sqlprocura = "select * from NUMPRES_CALC where k00_numpre = $numpre and k00_numpar = 0";
	}
	$resultprocura = db_query($sqlprocura) or die($sqlprocura);

	if (pg_numrows($resultprocura) == 0) {

		$sqlparc = "insert into NUMPRES_CALC values ($numpre,".($tiporeg == "NUMPRE"?$numpar:"0").")";
		db_query($sqlparc) or die($sqlparc);

		if ($tiporeg == "INICIAL") {
			$sqlcalc = "select xxx.k00_numpre,
			                   xxx.k00_numpar,
												 fc_calcula(xxx.k00_numpre, xxx.k00_numpar, xxx.k00_receit, current_date, current_date, extract (year from current_date)::integer)
              			from (select distinct
              			             arrecad.k00_numpre,
              			             arrecad.k00_numpar,
              			             arrecad.k00_receit
              			        from inicialnumpre
										             inner join arrecad    on arrecad.k00_numpre    = v59_numpre
																 inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre
                     																  and arreinstit.k00_instit = ".db_getsession('DB_instit')."
						       where v59_inicial = $numpre) as xxx";
			$k03_tipodebito = 18;
//	  db_msgbox("Tipo:$tiporeg");
//		db_msgbox("$numpre-------".@$numpar);
//	  db_msgbox("iniciallllllllllllll");
//	  db_msgbox($k03_tipodebito);

		} else {
			$sqltipo = "select k03_tipo as k03_tipodebito
									from arrecad
									inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre
                    									 and arreinstit.k00_instit =	".db_getsession('DB_instit')."
									inner join arretipo   on arrecad.k00_tipo = arretipo.k00_tipo
									                     and arretipo.k00_instit = ".db_getsession('DB_instit')."
									where arrecad.k00_numpre = $numpre
									limit 1";
			$resulttipo = db_query($sqltipo) or die($sqltipo);
			db_fieldsmemory($resulttipo, 0);
//	  db_msgbox("Tipo:$tiporeg");
//		db_msgbox("$numpre-------".@$numpar);
//	  db_msgbox("numpreeeeeeeeeee");
//	  db_msgbox($k03_tipodebito);
			$sqlcalc = "select $numpre as k00_numpre, $numpar as k00_numpar, fc_calcula($numpre, $numpar, 0, current_date, current_date, extract (year from current_date)::integer)";
		}

		$cadtipoparc = 0;

		$sqltipoparc = "select *
										from tipoparc
										inner join cadtipoparc on cadtipoparc = k40_codigo
										                      and k40_instit = ".db_getsession('DB_instit') ."
										where maxparc > 1
										  and '".date("Y-m-d",db_getsession("DB_datausu")) . "' >= k40_dtini
											and '".date("Y-m-d",db_getsession("DB_datausu")) . "' <= k40_dtfim
											and	k40_codigo = $k40_cadtipoparc
										$whereloteador";
    $sqltipoparc .= " order by maxparc ";
		$resulttipoparc = db_query($sqltipoparc) or die($sqltipoparc);
		if (pg_numrows($resulttipoparc) > 0) {
			db_fieldsmemory($resulttipoparc,0);
		} else {
			$k40_todasmarc = false;
		}

		$sqltipoparcdeb = "	select * from cadtipoparcdeb
												where k41_cadtipoparc = $k40_cadtipoparc
												limit 1";
		$resulttipoparcdeb = db_query($sqltipoparcdeb);
		$passar = false;

		if(isset($inicial) && $inicial != "") {
//			db_msgbox("Entrou");
//			$k03_tipodebito = 18;
			$totalregistrospassados = $totregistros;
		}

		if (pg_numrows($resulttipoparcdeb) == 0) {
			$passar = true;
		} else {
			$sqltipoparcdeb = "	select * from cadtipoparcdeb
													where k41_cadtipoparc = $k40_cadtipoparc
   													and	k41_arretipo = $tipo_debito";
			$resulttipoparcdeb = db_query($sqltipoparcdeb);
			if (pg_numrows($resulttipoparcdeb) > 0) {
				$passar = true;
			}
		}

		if (!isset($totalregistrospassados)) {
			$totalregistrospassados = 0;
		}

		if (!isset($totregistros)) {
			$totregistros = 0;
		}

//		die("numrows: " . pg_numrows($resulttipoparc) . " - todasmarc: $k40_todasmarc - totalregpassados: $totalregistrospassados - totregistros: $totregistros - passar: $passar");

		if (pg_numrows($resulttipoparc) == 0 or ($k40_todasmarc == 't'?$totalregistrospassados <> $totregistros:false) or $passar == false) {
			$desconto = 0;
		} else {
			$desconto = $k40_codigo;
		}

		//echo "<br>numrows: " . pg_numrows($resulttipoparc) . " - todasmarc: $k40_todasmarc - totregpas: $totalregistrospassados - totreg: $totregistros - passar: $passar - desconto: $desconto<br>";

		$tiposparc = "";

		for ( $parcelas=0; $parcelas < pg_numrows($resulttipoparc); $parcelas++ ) {
			db_fieldsmemory($resulttipoparc,$parcelas,true);
			if ($desconto == 0 and 1==2) {
				$descmul = 0;
				$descjur = 0;
			}
		  $tiposparc .= $tipoparc . "=" . $maxparc . "=" . $descmul . "=" . $descjur . "=" . (int) $k42_minentrada . "=" . $k40_forma . "=" . $descvlr . "=" . $vlrmin . "=" . $tipovlr . "=" . $minparc . "=" . ($parcelas == (pg_numrows($resulttipoparc) -1)?"":"-");
		}

		$sqlcalc_desativado = "select
									 substr(fc_calcula,2,13)::float8 as vlrhis,
									 substr(fc_calcula,15,13)::float8 as vlrcor,
									 substr(fc_calcula,28,13)::float8 as vlrjuros,
									 substr(fc_calcula,41,13)::float8 as vlrmulta,
									 substr(fc_calcula,54,13)::float8 as vlrdesconto,
									 (substr(fc_calcula,15,13)::float8+
									 substr(fc_calcula,28,13)::float8+
									 substr(fc_calcula,41,13)::float8-
									 substr(fc_calcula,54,13)::float8) as vlrtotal
								from ($sqlcalc) as x";
		$resultcalc = db_query($sqlcalc) or die($sqlcalc);

		for ($calc=0; $calc < pg_numrows($resultcalc); $calc++) {
			db_fieldsmemory($resultcalc, $calc);

			$totalvlrhis			+= 0 + (float) substr($fc_calcula,01,13);
			$totalvlrcor			+= 0 + (float) substr($fc_calcula,14,13);
			$totalvlrjuros		+= 0 + (float) substr($fc_calcula,27,13);
			$totalvlrmulta		+= 0 + (float) substr($fc_calcula,40,13);
			$totalvlrdesconto += 0 + (float) substr($fc_calcula,53,13);
			$totaltotal				+= 0 + (float) substr($fc_calcula,14,13) + (float) substr($fc_calcula,27,13) + (float) substr($fc_calcula,40,13) - (float) substr($fc_calcula,53,13);

			if ($tiporeg == "NUMPRE") {
				$sqlparc = "insert into NUMPRES_PARC1 values ($numpre,$numpar,$k03_tipodebito)";
			} else {
				$sqlparc = "insert into NUMPRES_PARC1
										select distinct v59_numpre, 0, 18
										from inicialnumpre
										where v59_inicial = $numpre";
			}
			db_query($sqlparc) or die($sqlparc);
			if (@$mostra == 1) {
				echo $sqlparc . ";<br>";
			}

			$sqlportipo = "select * from totalportipo where k03_tipodebito = $k03_tipodebito";
			$resultportipo = db_query($sqlportipo) or die($sqlportipo);

			$k00_vlrhis		= 0 + (float) substr($fc_calcula,01,13);
			$k00_vlrcor		= 0 + (float) substr($fc_calcula,14,13);
			$k00_juros		= 0 + (float) substr($fc_calcula,27,13);
			$k00_multa		= 0 + (float) substr($fc_calcula,40,13);
			$k00_desconto	= 0 + (float) substr($fc_calcula,53,13);
      $k00_total    = round( ( 0 + $k00_vlrcor + $k00_juros + $k00_multa - $k00_desconto ) ,2);

			if (pg_numrows($resultportipo) == 0) {
//				db_msgbox("Tipo de Debito = ".$k03_tipodebito);
				$sql  = "insert into totalportipo values ($k03_tipodebito, $desconto, ";
				$sql .= "$k00_vlrhis, ";
				$sql .= "$k00_vlrcor, ";
				$sql .= "$k00_juros, ";
				$sql .= "$k00_multa, ";
				$sql .= "$k00_desconto, ";
				$sql .= "$k00_total)";
			} else {
				$sql  = "update totalportipo set k00_vlrhis		= k00_vlrhis		+ $k00_vlrhis, ";
				$sql .= "                        k00_vlrcor		= k00_vlrcor		+ $k00_vlrcor, ";
				$sql .= "                        k00_juros		= k00_juros			+ $k00_juros ,";
				$sql .= "                        k00_multa		= k00_multa			+ $k00_multa, ";
				$sql .= "                        k00_desconto = k00_desconto  + $k00_desconto, ";
				$sql .= "                        k00_total		= k00_total			+ $k00_total ";
				$sql .= "where k03_tipodebito = $k03_tipodebito";
			}
			if (@$mostra == 1) {
				echo $sql . ";<br>";
			}
			db_query($sql) or die($sql);
//			echo "<br>$sql<br>";

		}

	}

}
$sqltotalportipo = "select
										k03_tipodebito,
										k03_descr,
										k00_cadtipoparc,
										sum(k00_vlrhis) as k00_vlrhis,
										sum(k00_vlrcor) as k00_vlrcor,
										sum(k00_juros) as k00_juros,
										sum(k00_multa) as k00_multa,
										sum(k00_desconto) as k00_desconto,
										sum(k00_total) as k00_total
							from totalportipo
										inner join cadtipo on k03_tipo = k03_tipodebito
										group by k03_tipodebito, k03_descr, k00_cadtipoparc";
$resulttotalportipo = db_query($sqltotalportipo) or die($sqltotalportipo);

$valoresportipo="";

$frase = "<font color=\"red\"><b>DEBITOS MARCADOS: </b>";

for ($x=0; $x < pg_numrows($resulttotalportipo); $x++) {
	db_fieldsmemory($resulttotalportipo, $x);
	$valoresportipo .= $k03_tipodebito . "-" . $k00_cadtipoparc . "-" . $k00_vlrhis . "-" . $k00_vlrcor . "-" . $k00_juros . "-" . $k00_multa . "-" . $k00_desconto . "-" . $k00_total . "=";

	$frase .= $k03_tipodebito . "-" . $k03_descr . ": " . db_formatar($k00_total, "f") . " - ";

}

if (@$mostra != 1) {
	echo $frase . "<font color=\"black\">";
}

if(isset($envia) or (@$mostra == 1) ) {

  $totparc=$parc+1;
  $sql= "create temporary table NUMPRES_PARC (k00_numpre integer, k00_numpar integer, k03_tipodebito integer) on commit drop";
  if (@$mostra == 1) {
    echo $sql . ";<br>";
  }
  db_query($sql) or die($sql);

  $sql= "insert into NUMPRES_PARC (k00_numpre, k00_numpar, k03_tipodebito) select distinct k00_numpre, k00_numpar, k03_tipodebito from NUMPRES_PARC1";
  if (@$mostra == 1) {
    echo $sql . ";<br>";
  }
  $iCodProc = "null";
  db_query($sql) or die($sql);
  if (!empty($p58_codproc)) {
  	$iCodProc = $p58_codproc;
  }
  $sql ="select fc_parcelamento($v07_numcgm,'$datpri_ano-$datpri_mes-$datpri_dia'::date,'$datsec_ano-$datsec_mes-$datsec_dia'::date,$dia,$totparc,$ent,".db_getsession('DB_id_usuario').",$k03_tipo,$k40_cadtipoparc,$desconto,$parcval,$parcult,'$v07_hist',$iCodProc) as retorno";
  if (@$mostra == 1) {
  	echo $sql . ";<br>";exit;
  } else {
    $r = db_query($sql) or die($sql);
    db_fieldsmemory($r,0);
  }
  ?>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script>parent.document.getElementById('processando').style.visibility = 'hidden';
  </script>
  <?
  if (@$mostra == 1) {
    echo "<br>";
  } else {
    if($retorno == 1){
      echo $retorno;
      $parc = split(":",$retorno);
      $parc = split("-",$parc[1]);
      db_query("COMMIT");
    }else{
      echo "Ocorreu um erro durante o processamento\n".$retorno;
      db_query("ROLLBACK");
    }
  }
  ?>
  <script>
  function js_emite(){
    window.open('div2_termoparc_002.php?parcel=<?=$parc[0]?>','','width=790,height=530,scrollbars=1,location=0');
    parent.document.getElementById('pesquisar').click()
  }
  </script>
  <?
  if (@$mostra != 1) {
    ?>
    <input type='button' value='OK' <?=(@$retorno == 1?'onClick="js_emite();"':'')?>>
    <?
  }
  exit;
}

$cltermo->rotulo->label();
$clprotprocesso->rotulo->label();

$clrotulo->label("z01_nome");
$clrotulo->label("p58_codproc");
?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="parent.document.getElementById('processando').style.visibility = 'hidden'">
<form name="form1" method="post" action="">
<?
echo "<input type='hidden' name='ver_matric'             value='".@$ver_matric."'>\n";
echo "<input type='hidden' name='ver_inscr'              value='".@$ver_inscr."'>\n";
echo "<input type='hidden' name='ver_numcgm'             value='".@$ver_numcgm."'>\n";
echo "<input type='hidden' name='numpre'                 value='".@$numpre1."'>\n";
echo "<input type='hidden' name='numpar'                 value='".@$numpar1."'>";
echo "<input type='hidden' name='k03_tipo'               value='".@$k03_tipo."'>\n";
echo "<input type='hidden' name='tipo_debito'            value='".@$tipo_debito."'>\n";
echo "<input type='hidden' name='tiposparc'              value='".@$tiposparc."'>\n";
echo "<input type='hidden' name='valoresportipo'         value='".@$valoresportipo."'>\n";
echo "<input type='hidden' name='desconto'               value='".@$desconto."'>\n";
echo "<input type='hidden' name='mostra'                 value=0>\n";
echo "<input type='hidden' name='k40_cadtipoparc'        value=".@$k40_cadtipoparc.">\n";
echo "<input type='hidden' name='k40_aplicacao'          value=".@$k40_aplicacao.">\n";
echo "<input type='hidden' name='valortotalcomdesconto'  value=".@$valortotalcomdesconto.">\n";
echo "<input type='hidden' name='totregistros'           value=".@$totregistros.">\n";
echo "<input type='hidden' name='totalregistrospassados' value=".@$totalregistrospassados.">\n";
echo "<input type='hidden' name='permitediverso'         value=''>\n";
echo "<input type='hidden' name='permiteInicial'         value=''>\n";
?>
<center>
<table border="1" width="100%">
<input type="hidden" name="matric" value="<?=@$ver_matric?>">
<tr>
<td  align="center" colspan="3" style='border: 1px outset #cccccc'>
<a onclick='js_mostra()'>
<b>Parcelamento de Dívida</b>
</td>
</tr>
<tr>

<td valign="top">
<table border="0">
<tr nowrap>
<td nowrap title="<?=@$Tv07_numcgm?>">
<?
db_ancora(@$Lv07_numcgm,"js_pesquisav07_numcgm(true);",1);
?>
</td>
<td nowrap colspan="1">
<?
db_input('v07_numcgm',6,$Iv07_numcgm,true,'hidden',1," onchange='js_pesquisav07_numcgm(false);'")
?>
<?
db_input('z01_nome',35,$Iz01_nome,true,'text',3,'')
?>
</td>
</tr>
<input style="background-color:#DEB887"  type="hidden" name="valortotal" size="10" readonly value='<?=$totaltotal?>'>
<input style="background-color:#DEB887"  type="hidden" name="valorcorr" size="10" readonly value='<?=$totalvlrcor?>'>
<input style="background-color:#DEB887"  type="hidden" name="juros" size="10" readonly value='<?=$totalvlrjuros?>'>
<input style="background-color:#DEB887"  type="hidden" name="multa" size="10" readonly value='<?=$totalvlrmulta?>'>
<input style="background-color:#DEB887"  type="hidden" name="temdesconto" size="10" readonly value='<?=$iTemDesconto?>'>
<tr>
<td>
<strong>Parcelas:</strong>
</td>
<td nowrap>
<input type="text" name="parc" size="10" readonly style="background-color:#DEB887" onChange="js_troca_parc(this)">
<strong>Valor de cada parcela:</strong>
</td>
<td>



<?
if ($k40_permvalparc == 'f') {
  ?>
  <input type="text" name="parcval" size="10" readonly style="background-color:#DEB887" >
  <?
} else {
  ?>
  <input type="text" name="parcval" size="10" onBlur="js_troca_valores_parc(this.value)">
  <?
}

if(isset($inicial)){
  ?>
  <input type="hidden" name="inicial">
  <?
}
?>
</td>
</tr>
<tr>
<td>
<strong>Entrada:</strong>
</td>
<td nowrap>
<input type="text" name="ent" size="10" onBlur="js_troca_valores(this.value)">
<strong>Última parcela:</strong>
</td>
<td>
<input type="text" name="parcult" size="10" readonly style="background-color:#DEB887">
</td>
</tr>
<tr>
<td nowrap title="">
<strong>Primeiro vencimento:</strong>
</td>
<td>
<?
$novadata  = mktime (0, 0, 0, date('m',db_getsession("DB_datausu")), date('d',db_getsession("DB_datausu"))+10, date('Y',db_getsession("DB_datausu")) );
if ( $oConfig->db21_codcli == 19985 ) { // marica/rj
  $datpri_dia = date("d",$novadata);
  $datpri_mes = date("m",$novadata);
  $datpri_ano = date("Y",$novadata);
} else {
  $datpri_dia = date("d",db_getsession("DB_datausu"));
  $datpri_mes = date("m",db_getsession("DB_datausu"));
  $datpri_ano = date("Y",db_getsession("DB_datausu"));
}
db_inputdata('datpri',@$datpri_dia,@$datpri_mes,@$datpri_ano,true,'text',1,"")
?>
</td>
</tr>
<tr>
<td nowrap title="">
<strong>Segundo vencimento:</strong>
</td>
<td>
<?

if (date("d",db_getsession("DB_datausu")) >= $k40_diapulames and $k40_diapulames > 0) {
  $pulames = 2;
} else {
  $pulames = 1;
}

if ($k40_vctopadrao > 0) {
  $diapadrao = $k40_vctopadrao;
} else {
  $diapadrao = date("d",db_getsession("DB_datausu"));
}

$sqlsegvenc = "select '" . date("Y",db_getsession("DB_datausu")) . "-" . date("m",db_getsession("DB_datausu")) . "-" . $diapadrao . "'::date + '$pulames months'::interval as segvenc";
if ( $oConfig->db21_codcli == 19985 ) { // marica/rj
  $sqlsegvenc = "select '$datpri_dia-$datpri_mes-$datpri_ano'::date + '1 months'::interval as segvenc";
}
$resultsegvenc = db_query($sqlsegvenc) or die($sqlsegvenc);
db_fieldsmemory($resultsegvenc,0);
$datsec_dia = substr($segvenc,8,2);
$datsec_mes = substr($segvenc,5,2);
$datsec_ano = substr($segvenc,0,4);

$diaprox = date("d",db_getsession("DB_datausu"));
$diaprox = $diapadrao;
db_inputdata('datsec',@$datsec_dia,@$datsec_mes,@$datsec_ano,true,'text',1,"");
?>
</td>
</tr>

<tr>
<td>
<strong>Dia dos próximos vencimentos:</strong>
</td>
<td>
<input type="text" name="dia" size="10" value="<?=$diaprox?>">
<strong>Valor mínimo das parcelas:</strong>
</td>
<td>
<input type="text" name="vlrmin" size="10" value="<?=$vlrmin?>" readonly style="background-color:#DEB887">
</td>
</tr>


<tr>
<td>
<strong>Tipo de arredondamento:</strong>
</td>
<td>
<?
$matarredonda = array ("I"=>"Próximo inteiro","D"=>"Próximo decimal","N"=>"Não arredonda");
db_select('arredondamento',$matarredonda,true,2,"onchange='parcelas.location.href=\"cai3_gerfinanc063.php?valor=$totaltotal&valorcorr=$totalvlrcor&juros=$totalvlrjuros&multa=$totalvlrmulta&valorcomdesconto=$totaltotal&tiposparc=$tiposparc&valoresportipo=$valoresportipo&temdesconto=$iTemDesconto&arredondamento=\"+this.value'");
?>
</td>
</tr>
<tr>
<td>
</td>
<td colspan="2" align="center">
<input type="submit" id="envia" name="envia" value="Parcelar" <?echo $lBtnParcelar==true?"disabled=\"disabled\"":"";?> onClick="return js_verifica(<?=$k03_tipo?>)">
</td>
</tr>
</table>
</td>
<td align="left" valign="top">
<table border="0">
<tr>
	<td nowrap title="<?=@$Tp58_codproc?>">
	  <?
	    db_ancora(@$Lp58_codproc,"js_pesquisap58_codproc(true);",1);
	  ?>
	</td>
	<td>
	  <?
	    db_input('p58_codproc',6,$Ip58_codproc,true,'hidden',1," onchange='js_pesquisap58_codproc(false);'");
      db_input('p58_requer',30,$Ip58_requer,true,'text',3,'')
	  ?>
	</td>
</tr>
<tr>
  <td valign="top"><b>Observações:</b></td>
  <td>
    <?
      db_textarea("v07_hist", 5, 27, "", false, "text", 3,"js_v();", "", "", "128");
    ?>
  </td>
</tr>
</table>
</td>
<td valign="top">
<iframe name='parcelas' src='cai3_gerfinanc063.php?valoresportipo=<?=$valoresportipo?>&valor=<?=$totaltotal?>&valorcorr=<?=$totalvlrcor?>&juros=<?=$totalvlrjuros?>&multa=<?=$totalvlrmulta?>&valorcomdesconto=<?=$totaltotal?>&arredondamento=D&temdesconto=<?=$iTemDesconto?>&tiposparc=<?=$tiposparc?>&k40_aplicacao=<?=$k40_aplicacao?>' frameborder='0' align='center' width='350' height='180'>
</iframe>
</td>
</tr>
</table>
<?
	if ($lValidaReparcelamento) {
		db_msgbox($sMensagemReparcelamento);

		if(!$lPermitir)
			echo "<script>parent.document.getElementById('pesquisar').click();</script>";
	}

?>
<script>
function js_verifica(k03_tipo){
  f = document.form1;
  alerta = '';

  var oSegundaData = new Date( f.datsec.value.substring(6,10),
                               f.datsec.value.substring(3,5),
                               f.datsec.value.substring(0,2) );

  var oPrimeiraData = new Date( f.datpri.value.substring(6,10),
                                f.datpri.value.substring(3,5),
                                f.datpri.value.substring(0,2) );

  if( oPrimeiraData >= oSegundaData ){

    alert('A primeira data de vencimento não deve ser maior ou igual a segunda.');
    return false;
  }

	if(f.permitediverso.value == 't'){
		alert('Para parcelar diverso com outro tipo de débito deve ser importado para divida.');
		return false;
	}
	if(f.permiteInicial.value == 't'){
		alert('Não é possível parcelar INICIAL DO FORO ou PARCELAMENTO DO FORO com outro tipo de débito.');
		return false;
	}
  /*--------------------------------------------------------------------------------------------------------------------
	  Comentado o alerta quando os dados do processo ou das observações não foram preenchidos
    if (f.p58_requer.value == '' && f.v07_hist.value == '') {
      alerta += " - Processo ou Observações\n";
    }
  ---------------------------------------------------------------------------------------------------------------------*/

//  if (f.v07_hist.value == '') {
//    alerta += " - Observações\n";
//

  if(f.parc.value == ""){
    alerta += " - Parcelas\n"
  }
  if(f.dia.value == ""){
    alerta += " - Dia dos próximos vencimentos\n"
  }
  if(f.v07_numcgm.value == ""){
    alerta += " - Responsável\n"
  }
  if(alerta != ""){
    alert('verifique o(s) campo(s)\n'+ alerta);
    return false;
  }else{
    if (k03_tipo == 6 || k03_tipo == 13) {
      if (confirm('Tem certeza de que deseja efetuar um reparcelamento?') == false) {
        return false;
      }
    }
    parent.document.getElementById('processandoTD').innerHTML = '<h3>Aguarde, processando <?(!isset($inicial)?'PARCELAMENTO':'INICIAL FORO')?>...</h3>';
    parent.document.getElementById('processando').style.visibility = 'visible';
    return true;
  }
  return false;
}

function js_troca_parc(obj){

  if(isNaN(obj.value)){
    alert('campo parcela deve ser preenchido somente com números');
    obj.value = '';
    obj.focus();
  }else{
    valor = parcelas.document.getElementById('vt').innerHTML;
    total = valor/obj.value
    document.form1.parcval.value = total.toFixed(2);
    if(isNaN(parcelas.document.getElementById('val'+obj.value))){
      parcelas.document.getElementById('val'+obj.value).checked = true;
      parcelas.document.getElementById('val'+obj.value).focus();
    }
    document.form1.ent.value = total.toFixed(2);
  }
}
var x = 0;
var y = 0;

function js_valparc(id,vlrmin) {

	valTotalParc = new Number( document.form1.valortotal.value);

  if(parcelas.document.getElementById('vt').innerHTML != document.form1.valortotal.value){
    js_troca_valores('0');
  }

  var descontocor = 0;
  var descontomul = 0;
  var descontojur = 0;
	document.form1.permitediverso.value = '';

  var tipo1 = document.form1.tiposparc.value.split("-");
  var parcela = Number(document.form1.parc.value);
  var parcela = parcela + 1;

  for (contatipo = 0; contatipo < tipo1.length; contatipo++) {
    var tipo2 = tipo1[contatipo].split("=");
    var forma = tipo2[5];
    var tipovlr = tipo2[8];
		var entradaminima = tipo2[4];
    var ultparc = tipo2[9];

    if (parcela >= ultparc && parcela <= tipo2[1]) {
      var descontocor = tipo2[6];
      var descontomul = tipo2[2];
      var descontojur = tipo2[3];
      break;
    }

    var ultparc = tipo2[1];

  }

	var valoresportipo	= document.form1.valoresportipo.value.split("=");
	var valtotal				= 0;
	var valtothist			= 0
	var valtotcorr			= 0
	var valtotjuros			= 0;
	var valtotmulta			= 0;
	var temTipos = new Array();

	for (x = 0; x < valoresportipo.length; x++) {
	  if (valoresportipo[x] == '') {
			continue;
		}
		var valores = valoresportipo[x].split('-');

    var procurar = valores[0];

		if (procurar == 6) {
			procurar = 5;
		}
		if (procurar == 13) {
			procurar = 18;
		}

    if (js_search_in_array(temTipos,procurar) == false) {
			temTipos[temTipos.length] = procurar;
		}

		var cadtipoparc	=	valores[1];
		var valhist			= new Number(valores[2]);
		var valcorr			= new Number(valores[3]);
		var valjuros		= new Number(valores[4]);
		var valmulta		= new Number(valores[5]);
    var aplicacao   = document.form1.k40_aplicacao.value;
		var valdesconto	= 0;
		var valdescontocorrecao	= 0;

		if (cadtipoparc > 0 && aplicacao != '2') {

      var valdescontocorrecao = 0;
      if (tipovlr == 1) {
        valdescontocorrecao = (valcorr - valhist) * descontocor / 100;
      } else if (tipovlr == 2) {
        valdescontocorrecao = (valcorr) * descontocor / 100;
      }

      valdescontocorrecao = valdescontocorrecao.toFixed(2);

      var val_desc_jur = (valjuros * descontojur / 100);
      val_desc_jur = val_desc_jur.toFixed(2);

      var val_desc_mul = (valmulta * descontomul / 100);
      val_desc_mul = val_desc_mul.toFixed(2);

			valdesconto += valdescontocorrecao + val_desc_jur + val_desc_mul;
			valtotal		+= valcorr + (valjuros + valmulta) - valdesconto;

	  } else {
			valtotal		+= valcorr + valjuros + valmulta;
		}
    valtotcorr	= valtotcorr + valcorr;
		valtotjuros = valtotjuros + valjuros;
		valtotmulta	= valtotmulta + valmulta;
	}

  if (temTipos.length > 1) {

		document.form1.permiteInicial.value = 't';
		alert('Não é permitido parcelar os débitos marcados no mesmo parcelamento!');
		document.form1.envia.disabled=true;
		return false;

	}

	valtotal = valtotal.toFixed(2);

	if (forma == 2) {
    valor = new Number(document.form1.valorcorr.value);
		valor = valor / (new Number(id) - 1);
		valor = new String(valor);
	} else {
    valor = parcelas.document.getElementById(id).innerHTML;
	}

  if(valor.indexOf(",") != -1){
    valor = new String(valor);
    valor = valor.replace('.','');
    valor = valor.replace(',','.');
    valor = new Number(valor);
  }

  valentrada = Math.round(id);

  document.form1.ent.disabled = false;

  if (document.form1.parc.value >= 1) {

    if (document.form1.arredondamento.value == "D" ) {

      strInteiro        = new String( (valTotalParc / valentrada).toFixed(2) ).split('.')[0];
      valInteiro        = new Number( strInteiro );
      valDecimal        = ( (valTotalParc / valentrada) - valInteiro ).toFixed(2);
      valDecimalParcial = js_round( new Number( valDecimal*10 ),2);
      valDecimalParcial = js_round( valDecimalParcial, 2 );
      arrValDecimalParcial = new String( valDecimalParcial ).split('.');
      if( arrValDecimalParcial.length == 1 ){
        tmpVal            = new Number( arrValDecimalParcial[0] );
        valDecimalParcial = new String( new Number( (tmpVal + 1) / 10 ).toFixed(2));
        tmpVal2           = new Number(valDecimalParcial);
        valentrada        = new Number( ( valInteiro+tmpVal2) );
      }else{
        valDecimalParcial = new String( arrValDecimalParcial[1] );
        valDecimalParcial = js_round( ( valDecimalParcial/100 ),2 );
        valDecimalParcial = new Number( valDecimalParcial-0.1);
        valDecimalParcial = Math.abs(valDecimalParcial);
        valDecimal        = new Number(valDecimal);
        valentrada        = new Number(valentrada);
        valTotalParc      = new Number(valTotalParc);
        valComplemento    = valDecimalParcial;
        valentrada        = js_round( ( ( valTotalParc / valentrada ) + valComplemento  ),2 );
      }

      valentrada     = js_round( valentrada,2 );
      valentrada     = valentrada.toFixed(2);

    } else {

      if (document.form1.arredondamento.value == "I" ) {
        valentrada = Math.round(valor)
      } else {
        valentrada = valor
      }

    }
  } else {
    valentrada = valtotal;
    document.form1.parcult.value = 0;
    document.form1.parcval.value = 0;
  }

  nPercentualEntrada = new Number(entradaminima);

  entradaminima = valtotal * entradaminima / 100;
  entradaminima = entradaminima.toFixed(2);

	if ((valentrada < entradaminima) || (nPercentualEntrada > 0)) {
		valentrada = entradaminima;
	}

  /**
   * Caso o valor da entrada seja menor que o valor mínimo da parcela
   */
  vlrmin = new Number(vlrmin);

  if (valentrada < vlrmin) {
    valentrada = new Number(vlrmin);
  }

  document.form1.ent.value = valentrada;

  if (document.form1.parc.value >= 1) {

    if (forma == 2) {

      valtotalsemdesconto = valtotal;

      if (document.form1.parc.value == 1) {
        x = valtotcorr;
      } else {
        x = (valtotcorr - document.form1.ent.value)/(document.form1.parc.value-1);
      }
      document.form1.parcval.value = x.toFixed(2);
      x = document.form1.parc.value * document.form1.parcval.value;
  //    x = valtotal - eval(x +'+'+ document.form1.ent.value)

      document.form1.parcult.value = (valtotjuros + valtotmulta).toFixed(2);

      parcelas.document.getElementById('vtcomdesconto').innerHTML = valtotalsemdesconto;

    } else {

      x = (valtotal - document.form1.ent.value)/document.form1.parc.value;
      document.form1.parcval.value = x.toFixed(2);
      x = document.form1.parc.value * document.form1.parcval.value;

      x = valtotal - eval(x +'+'+ document.form1.ent.value)
      document.form1.parcult.value = eval(document.form1.parcval.value +'+'+ x).toFixed(2);

      parcelas.document.getElementById('vtcomdesconto').innerHTML = valtotal;

    }

  }
  document.form1.vlrmin.value = vlrmin;
  <?
    if ($lBtnParcelar == true) {
      echo "document.form1.envia.disabled=true;";
    } else {
    	echo "document.form1.envia.disabled=false;";
    }
  ?>

}

function js_troca_valores(entrada){

	if ( document.form1.parc.value == 0 ) {
    alert('Campo entrada nao pode ser alterado no caso de uma única parcela!');
    document.form1.ent.value = '';
    document.form1.ent.focus();
    document.form1.envia.disabled=true;
    return false;
  }

  if(isNaN(entrada)){
    alert('Campo entrada deve ser preenchido somente com números');
    document.form1.ent.value = '';
    document.form1.ent.focus();
    document.form1.envia.disabled=true;
    return false;
  } else if(entrada <= 0){
    alert('Entrada deve ser maior que 0');
    document.form1.ent.value = '';
    document.form1.ent.focus();
    document.form1.envia.disabled=true;
    return false;
  }else{

		var tipo1 = document.form1.tiposparc.value.split("-");
		var parcela = Number(document.form1.parc.value);
		var parcela = parcela + 1;

		for (contatipo = 0; contatipo < tipo1.length; contatipo++) {
			var tipo2 = tipo1[contatipo].split("=");
			var forma = tipo2[5];
			var entradaminima = tipo2[4];
			var vlrmin = tipo2[7];
      var ultparc = tipo2[9];

			if (parcela >= ultparc && parcela <= tipo2[1]) {
				var descontomul = tipo2[2];
				var descontojur = tipo2[3];
				break;
			}

			var ultparc = tipo2[1];

		}

	  valortotalcomdesconto = new Number(parcelas.document.getElementById('vtcomdesconto').innerHTML);
    valorentradaminima = valortotalcomdesconto * entradaminima / 100;
    valorentradaminima = valorentradaminima.toFixed(2);

    /**
     * Caso o valor da entrada seja menor que o valor mínimo da parcela
     */
    vlrmin = new Number(vlrmin);

    if (valorentradaminima < vlrmin) {
      valorentradaminima = vlrmin;
    }

    if (entrada < valorentradaminima) {
      alert('Entrada deve ser maior ou igual a entrada minima');
      document.form1.envia.disabled=true;
      return false;
    }

    <?
    if ($lBtnParcelar == true) {
      echo "document.form1.envia.disabled=true;";
    } else {
      echo "document.form1.envia.disabled=false;";
    }
    ?>

    if(entrada.indexOf(",") != -1){
      entrada = new String(entrada)
      entrada = entrada.replace(',','.');
      document.form1.ent.value = entrada;
    }

		var valoresportipo	= document.form1.valoresportipo.value.split("=");
		var valdesconto			= 0;
		var valtotal				= 0;

		for (x = 0; x < valoresportipo.length; x++) {
			if (valoresportipo[x] == '') {
				continue;
			}
			var valores = valoresportipo[x].split('-');

			var cadtipoparc	=	valores[1];
			var valcorr			= new Number(valores[3]);
			var valjuros		= new Number(valores[4]);
			var valmulta		= new Number(valores[5]);

			if (cadtipoparc > 0) {
				valdesconto += (valjuros * descontojur / 100) + (valmulta * descontomul / 100);
				valtotal		+= valcorr + (valjuros + valmulta) - valdesconto;
			} else {
				valtotal		+= valcorr + valjuros + valmulta;
			}

		}

		valtotal = valtotal.toFixed(2);

		if (forma == 2) {

			quantparcelas = new Number(document.form1.parc.value);
			valorparcela = new Number(document.form1.parcval.value);
			valorultima = new Number(document.form1.parcult.value);
			valentrada = new Number(document.form1.ent.value);

			valortotal = (valorparcela * (quantparcelas - 1)) + valorultima;
			valortotal = Number(document.form1.valorcorr.value);

			valcadaparcela =  (Math.round(( (valortotal - valentrada)/(quantparcelas-1)) * 100))/100;

			document.form1.parcval.value = valcadaparcela;

			valorsomenteparcelas = document.form1.parcval.value * (quantparcelas -2);

			valorultima = valorultima.toFixed(2);

			document.form1.parcult.value = valorultima;

		} else {

			quantparcelas = new Number(document.form1.parc.value);
			valorparcela = new Number(document.form1.parcval.value);
			valorultima = new Number(document.form1.parcult.value);
			valentrada = new Number(document.form1.ent.value);

			valortotal = (valorparcela * (quantparcelas - 1)) + valorultima;
			valortotal = new Number(parcelas.document.getElementById('vtcomdesconto').innerHTML);

			valcadaparcela =  (Math.round(( (valortotal - valentrada)/quantparcelas  ) * 100))/100;

			if (valcadaparcela < vlrmin) {
				alert('Valor calculado da parcela nao pode ser menor que o valor mínimo: ' + vlrmin);
				document.form1.envia.disabled=true;
				return false;
			}

			document.form1.parcval.value = valcadaparcela;

			valorsomenteparcelas = document.form1.parcval.value * (quantparcelas -1);

			valorultima = valortotal - valorsomenteparcelas - valentrada;
			valorultima = valorultima.toFixed(2);

			document.form1.parcult.value = valorultima;

		}

		document.form1.dia.focus();

    for(i=2;i<500;i++){
      parcelas.document.getElementById('val'+i).checked = false;
    }

  }

}

function js_troca_valores_parc(valor) {

  if (isNaN(valor)) {

    alert('campo valor da parcela deve ser preenchido somente com números');
    document.form1.parcval.value = '';
    document.form1.parcval.focus();
    return false;

  } else if(valor <= 0) {

    alert('Valor da parcela deve ser maior que 0');
    document.form1.parcval.value = '';
    document.form1.parcval.focus();
    return false;
  }else{

    valentrada = document.form1.ent.value;
    valorparcela = new Number(document.form1.parc.value);
    quantparcelas = new Number(document.form1.parcval.value);
    valorultima = new Number(document.form1.parcult.value);

    valortotal = new Number(parcelas.document.getElementById('vtcomdesconto').innerHTML);

    ultimaparcela = valortotal - valentrada - (quantparcelas * (valorparcela -1));

    ultimaparcela = (Math.round(ultimaparcela*100))/100;

    maximoparc = (valortotal - valentrada) / valorparcela;
    maximoparc = (Math.round(maximoparc*100))/100;

    if (ultimaparcela < 0) {

      alert('Valor de cada parcela nao pode ultrapassar ' + maximoparc);
      return false;
    } else {
      document.form1.parcult.value = ultimaparcela;
    }

  }

  document.form1.dia.focus();
  return true;
}

function js_pesquisav07_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cgm','func_nome.php?testanome=true&funcao_js=parent.debitos.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
    if(document.form1.v07_numcgm.value != ''){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cgm','func_nome.php?testanome=true&pesquisa_chave='+document.form1.v07_numcgm.value+'&funcao_js=parent.debitos.js_mostracgm','Pesquisa',false);
    }else{
      document.form1.z01_nome.value = '';
    }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.v07_numcgm.focus();
    document.form1.v07_numcgm.value = '';
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.v07_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  if(parent.document.getElementById('id_resp_parc').value == "")
  parent.document.getElementById('id_resp_parc').value = chave1;
  if(parent.document.getElementById('resp_parc').value == "")
  parent.document.getElementById('resp_parc').value = chave2;
  (window.CurrentWindow || parent.CurrentWindow).corpo.db_iframe_cgm.hide();
}
onload = js_pnome();
function js_pnome(){
  if(parent.document.getElementById('id_resp_parc').value != "")
  document.form1.v07_numcgm.value = parent.document.getElementById('id_resp_parc').value;
  if(parent.document.getElementById('resp_parc').value != "")
  document.form1.z01_nome.value = parent.document.getElementById('resp_parc').value;
}
function js_mostra(){
  document.form1.mostra.value = 1;
  document.form1.submit();
}
function js_reload(valor){
  document.form1.k40_cadtipoparc.value = valor;
  document.form1.submit();
}
function js_pesquisap58_codproc(mostra){
  var bMostra = mostra;
  if(bMostra==true){
    var js_funcao   = 'parent.debitos.js_preenchepesquisap58_codproc';
    var sUrl = 'func_protprocesso.php?funcao_js='+js_funcao+'|p58_codproc|p58_requer';
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_protprocesso',sUrl,'Pesquisa',true);
  }else{
    var p58_codproc = document.getElementById("p58_codproc").value;
    var js_funcao   = 'parent.debitos.js_preenchepesquisap58_codproc';
    var sUrl        = 'func_protprocesso.php?pesquisa_chave='+p58_codproc+'&funcao_js='+js_funcao;
    if(p58_codproc != ''){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_protprocesso',sUrl,'Pesquisa',false);
    }else{
      document.form1.p58_codproc.value = '';
      document.form1.p58_requer.value  = '';
    }
  }
}
function js_preenchepesquisap58_codproc(chave1,chave2){
  document.form1.p58_codproc.value = chave1;
  document.form1.p58_requer.value  = chave2;
  (window.CurrentWindow || parent.CurrentWindow).corpo.db_iframe_protprocesso.hide();
}
</script>
</center>
</form>
</body>
</html>

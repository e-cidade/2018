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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_utils.php"));
require(modification("std/db_stdClass.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));

include(modification("libs/JSON.php"));

$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["dados"]));
$oRetorno          = new stdClass;
$oRetorno->status  = 1;
$oRetorno->message = urlencode("OK");

$sWhere = "";
$sWhere2 = "";

if(isset($oParam->exec) && $oParam->exec == 'getCheques'){

	$sWhere = "";
	$sWhere2 = "";
	if (isset($oParam->e86_cheque) && trim($oParam->e86_cheque) != "") {

		if($sWhere != ""){
			$sWhere .= " and e91_cheque = '$oParam->e86_cheque' ";
			$sWhere2 .= " and e93_cheque = '$oParam->e86_cheque' ";
		}else{
			$sWhere .= " e91_cheque = '$oParam->e86_cheque' ";
			$sWhere2 .= " e93_cheque = '$oParam->e86_cheque' ";
		}

	}
	if (isset($oParam->e60_codempini) && trim($oParam->e60_codempini) != "" &&
		 isset($oParam->e60_codempfim) && trim($oParam->e60_codempfim) != "") {

		$e60_codemp = explode('/',$oParam->e60_codempini);
		$e60_codempini = $e60_codemp[0];
		$e60_anousuini = db_getsession("DB_anousu");
		if(count($e60_codemp)>1){
			$e60_anousuini = $e60_codemp[1];
		}
		$oRetorno->e60_codempini = $e60_codempini;
		$e60_codemp = explode('/',$oParam->e60_codempfim);
		$e60_codempfim = $e60_codemp[0];
		$e60_anousufim = db_getsession("DB_anousu");
		 if(count($e60_codemp)>1){
			$e60_anousufim = $e60_codemp[1];
		}
		if($sWhere != ""){

			$sWhere .= " and e60_codemp between '$e60_codempini' and '$e60_codempfim' ";
			$sWhere2 .= " and e60_codemp between $e60_codempini' and '$e60_codempfim' ";
			$sWhere .= " and e60_anousu between $e60_anousuini' and $e60_anousufim ";
			$sWhere2 .= " and e60_anousu between $e60_anousuini' and $e60_anousufim ";
		}else{

			$sWhere .= " e60_codemp between '$e60_codempini' and '$e60_codempfim' ";
			$sWhere2 .= " e60_codemp between '$e60_codempini' and '$e60_codempfim' ";
			$sWhere .= " and e60_anousu  between $e60_anousuini and $e60_anousufim ";
			$sWhere2 .= " and e60_anousu  between $e60_anousuini and $e60_anousufim ";
		}

	}else if (isset($oParam->e60_codempini) && trim($oParam->e60_codempini) != "" ) {

		$e60_codemp = explode('/',$oParam->e60_codempini);
		$e60_codempini = $e60_codemp[0];
		$e60_anousuini = db_getsession("DB_anousu");
		if(count($e60_codemp)>1){
			$e60_anousuini = $e60_codemp[1];
		}
		$oRetorno->e60_codempini = $e60_codempini;
		$e60_codemp = explode('/',$oParam->e60_codempfim);
		$e60_codempfim = $e60_codemp[0];
		$e60_anousufim = db_getsession("DB_anousu");
		 if(count($e60_codemp)>1){
			$e60_anousufim = $e60_codemp[1];
		}

		if($sWhere != ""){

			$sWhere .= " and e60_codemp = '$e60_codempini'  ";
			$sWhere2 .= " and e60_codemp = '$e60_codempini'  ";
			$sWhere .= " and e60_anousu = $e60_anousuini  ";
			$sWhere2 .= " and e60_anousu = $e60_anousuini  ";
		}else{

			$sWhere .= " e60_codemp = '$e60_codempini' ";
			$sWhere2 .= " e60_codemp = '$e60_codempini' ";
			$sWhere .= " and e60_anousu = $e60_anousuini ";
			$sWhere2 .= " and e60_anousu = $e60_anousuini ";
		}

	}
	//verificar se pode ser na e82_codord ou e50_cordord
	if (isset($oParam->e50_codordini) && trim($oParam->e50_codordini) != "" &&
		 isset($oParam->e50_codordfim) && trim($oParam->e50_codordfim) != "") {

		if($sWhere != ""){

			$sWhere .= " and e82_codord between $oParam->e50_codordini and $oParam->e50_codordfim ";
			$sWhere2 .= " and e82_codord between $oParam->e50_codordini and $oParam->e50_codordfim ";
		}else{

			$sWhere .= " e82_codord between $oParam->e50_codordini and $oParam->e50_codordfim ";
			$sWhere2 .= " e82_codord between $oParam->e50_codordini and $oParam->e50_codordfim ";
		}

	}else if (isset($oParam->e50_codordini) && trim($oParam->e50_codordini) != ""){

		if($sWhere != ""){

			$sWhere .= " and e82_codord = $oParam->e50_codordini ";
			$sWhere2 .= " and e82_codord  = $oParam->e50_codordini ";
		}else{

			$sWhere .= " e82_codord = $oParam->e50_codordini  ";
			$sWhere2 .= " e82_codord = $oParam->e50_codordini  ";
		}

	}

	if (isset($oParam->k17_codigoini) && trim($oParam->k17_codigoini) != "" &&
		 isset($oParam->k17_codigofim) && trim($oParam->k17_codigofim) != "") {

		if($sWhere != ""){

			$sWhere .= " and slip.k17_codigo between $oParam->k17_codigoini and $oParam->k17_codigofim ";
			$sWhere2 .= " and slip.k17_codigo between $oParam->k17_codigoini and $oParam->k17_codigofim ";
		}else{

			$sWhere .= " slip.k17_codigo between $oParam->k17_codigoini and $oParam->k17_codigofim ";
			$sWhere2 .= " slip.k17_codigo between $oParam->k17_codigoini and $oParam->k17_codigofim ";
		}

	}else if (isset($oParam->k17_codigoini) && trim($oParam->k17_codigoini) != ""){

		if($sWhere != ""){

			$sWhere .= " and slip.k17_codigo = $oParam->k17_codigoini ";
			$sWhere2 .= " and slip.k17_codigo  = $oParam->k17_codigoini ";
		}else{

			$sWhere .= " slip.k17_codigo = $oParam->k17_codigoini  ";
			$sWhere2 .= " slip.k17_codigo = $oParam->k17_codigoini  ";
		}

	}

	if (isset($oParam->z01_numcgm) && trim($oParam->z01_numcgm) != "" ) {

		if($sWhere != ""){

			$sWhere .= " and e60_numcgm = $oParam->z01_numcgm ";
			$sWhere2 .= " and e60_numcgm = $oParam->z01_numcgm ";
		}else{

			$sWhere .= " e60_numcgm = $oParam->z01_numcgm ";
			$sWhere2 .= " e60_numcgm = $oParam->z01_numcgm ";
		}

	}

	if (isset($oParam->dtini) && trim($oParam->dtini) != "" &&
		 isset($oParam->dtfim) && trim($oParam->dtfim) != "") {

		if($sWhere != ""){

			$sWhere  .= " and e86_data between '$oParam->dtini' and '$oParam->dtfim' ";
			$sWhere2 .= " and e86_data between '$oParam->dtini' and '$oParam->dtfim' ";
		}else{

			$sWhere  .= " e86_data between '$oParam->dtini' and '$oParam->dtfim' ";
			$sWhere2 .= " e86_data between '$oParam->dtini' and '$oParam->dtfim' ";
		}

	}else if(isset($oParam->dtini) && trim($oParam->dtini) != "" ) {

		if($sWhere != ""){

			$sWhere  .= " and e86_data >= '$oParam->dtini' ";
			$sWhere2 .= " and e88_data >= '$oParam->dtini' ";
		}else{

			$sWhere  .= " e86_data >= '$oParam->dtini' ";
			$sWhere2 .= " e88_data >= '$oParam->dtini' ";
		}

	}else if(isset($oParam->dtfim) && trim($oParam->dtfim) != "" ) {

		if($sWhere != ""){

			$sWhere  .= " and e86_data <= '$oParam->dtfim' ";
			$sWhere2 .= " and e88_data <= '$oParam->dtfim' ";
		}else{

			$sWhere  .= " e86_data <= '$oParam->dtfim' ";
			$sWhere2 .= " e88_data <= '$oParam->dtfim' ";
		}

	}

	if (isset($oParam->e83_conta) && trim($oParam->e83_conta) != "" ) {

		if($sWhere != ""){

			$sWhere 	.= " and e83_conta = $oParam->e83_conta ";
			$sWhere2  .= " and e83_conta = $oParam->e83_conta ";
		}else{

			$sWhere   .= " e83_conta = $oParam->e83_conta ";
			$sWhere2  .= " e83_conta = $oParam->e83_conta ";
		}

	}

	if (isset($oParam->db90_codban) && trim($oParam->db90_codban) != "" ) {

		if($sWhere != ""){

			$sWhere .= " and c63_banco = '$oParam->db90_codban' ";
			$sWhere2 .= " and c63_banco = '$oParam->db90_codban' ";
		}else{

			$sWhere .= " c63_banco = '$oParam->db90_codban' ";
			$sWhere2 .= " c63_banco = '$oParam->db90_codban' ";
		}

	}

	if (isset($oParam->o15_codigo) && trim($oParam->o15_codigo) != "" ) {

		if($sWhere != ""){

			$sWhere .= " and e85_codtipo = $oParam->o15_codigo ";
			$sWhere2 .= " and e85_codtipo = $oParam->o15_codigo ";
		}else{

			$sWhere .= " e85_codtipo = $oParam->o15_codigo ";
			$sWhere2 .= " e85_codtipo = $oParam->o15_codigo ";
		}

	}
}

if($sWhere != ""){

	$sWhere = " where ".$sWhere;
	$sWhere2 = " where ".$sWhere2;
}

switch ($oParam->exec){

	case 'getCheques':

		$sql  = "select distinct e91_codcheque, ";
    $sql .= "  e86_cheque,	";
    $sql .= "  e91_cheque ,	";
    $sql .= "  case when cgmslip.z01_numcgm is null then cgmemp.z01_numcgm else cgmslip.z01_numcgm end  as numcgm,	";
    $sql .= "  case when cgmslip.z01_nome is null then cgmemp.z01_nome else cgmslip.z01_nome end  as credor, 				";
    $sql .= "  e86_data, 		";
    $sql .= "  case when e81_numemp <> 0 then e60_codemp||'/'||e60_anousu else 'slip' end  as empenho,	";
    $sql .= "  e83_descr,";
    $sql .= "  case when e89_codigo is null then e82_codord else e89_codigo end as codigo_origem,	";
    $sql .= "  case when cpreduzslip.c61_reduz is null then o58_codigo else cpreduzslip.c61_codigo end as recurso,	";
    $sql .= "  e91_valor,	";
    $sql .= "  c63_banco,	";
    $sql .= "  c63_conta,	";
    $sql .= "  e83_descr,	";
    $sql .= "  contareduz.c61_reduz,	";
    $sql .= "  c63_agencia,	";
    $sql .= "  case when e91_ativo is true then 'Não' else 'Sim' end as anulado";
 		$sql .= "  from empageconfche	";
    $sql .= "  inner join empageconf   on e86_codmov      = e91_codmov ";
    $sql .= "  left  join empord       on e82_codmov      = e86_codmov ";
    $sql .= "  left  join empageslip   on e86_codmov      = e89_codmov ";
    $sql .= "  inner join empagemov    on e86_codmov      = e81_codmov ";
    $sql .= "  left  join pagordem     on e50_codord      = e82_codord ";
    $sql .= "  left  join empempenho   on e60_numemp      = e50_numemp ";
    $sql .= "  left  join cgm          on cgm.z01_numcgm  = e60_numcgm ";
    $sql .= "  left  join orcdotacao   on e60_coddot      = o58_coddot ";
    $sql .= "                         and e60_anousu      = o58_anousu ";
    $sql .= "  left join slip          on slip.k17_codigo = e89_codigo ";
    $sql .= "  left join conplanoreduz cpreduzslip on slip.k17_credito = c61_reduz	";
    $sql .= "                         and cpreduzslip.c61_anousu::integer  = extract(year from k17_data)::integer	";
    $sql .= "  left join cgm cgmemp    on e60_numcgm = cgmemp.z01_numcgm ";
    $sql .= "  left join slipnum       on slipnum.k17_codigo = slip.k17_codigo	";
    $sql .= "  left join cgm cgmslip   on slipnum.k17_numcgm  = cgmslip.z01_numcgm	";
    $sql .= "  inner join empagepag    on e85_codmov = e81_codmov 	";
    $sql .= "  inner join empagetipo   on e85_codtipo = e83_codtipo ";
    $sql .= "  inner join conplanoreduz contareduz  on contareduz.c61_reduz = e83_conta	";
    $sql .= "                                      and contareduz.c61_anousu = ".db_getsession('DB_anousu');
    $sql .= "  inner join conplanoconta on contareduz.c61_codcon = c63_codcon ";
 		$sql .= "                          and contareduz.c61_anousu = c63_anousu ";
 		$sql .= " ".$sWhere." ";

 		$sql .= " union ";

 		$sql .= "select  distinct  e93_codcheque, ";
    $sql .= "  e88_cheque as e86_cheque,	";
    $sql .= "  e93_cheque as e91_cheque,	";
    $sql .= "  case when cgmslip.z01_numcgm is null then cgmemp.z01_numcgm else cgmslip.z01_numcgm end  as numcgm,	";
    $sql .= "  case when cgmslip.z01_nome is null then cgmemp.z01_nome else cgmslip.z01_nome end  as credor, 				";
    $sql .= "  e88_data as e86_data, 		";
    $sql .= "  case when e81_numemp <> 0 then e60_codemp||'/'||e60_anousu else 'slip' end  as empenho,	";
    $sql .= "  e83_descr,";
    $sql .= "  case when e89_codigo is null then e82_codord else e89_codigo end as codigo_origem,	";
    $sql .= "  case when cpreduzslip.c61_reduz is null then o58_codigo else cpreduzslip.c61_codigo end as recurso,	";
    $sql .= "  e93_valor,	";
    $sql .= "  c63_banco,	";
    $sql .= "  c63_conta,	";
    $sql .= "  e83_descr,	";
    $sql .= "  contareduz.c61_reduz,	";
    $sql .= "  c63_agencia,	";
    $sql .= "  'Sim' as anulado	";
 		$sql .= "  from empageconfchecanc	";
    $sql .= "  inner join  empageconfcanc on e88_codmov  = e93_codmov	";
    $sql .= "  left  join empord       on e82_codmov  = e88_codmov 	";
    $sql .= "  left  join empageslip   on e88_codmov  = e89_codmov 	";
    $sql .= "  inner join empagemov    on e88_codmov  = e81_codmov 	";
    $sql .= "  left  join pagordem     on e50_codord      = e82_codord ";
    $sql .= "  left  join empempenho   on e60_numemp      = e50_numemp ";
    $sql .= "  left  join cgm          on cgm.z01_numcgm  = e60_numcgm ";
    $sql .= "  left  join orcdotacao   on e60_coddot  = o58_coddot 	";
    $sql .= "                         and e60_anousu  = o58_anousu 	";
    $sql .= "  left join slip          on slip.k17_codigo  = e89_codigo	";
    $sql .= "  left join conplanoreduz cpreduzslip on slip.k17_credito = c61_reduz	";
    $sql .= "                         and cpreduzslip.c61_anousu::integer  = extract(year from k17_data)::integer	";
    $sql .= "  left join cgm cgmemp    on e60_numcgm = cgmemp.z01_numcgm ";
    $sql .= "  left join slipnum       on slipnum.k17_codigo = slip.k17_codigo	";
    $sql .= "  left join cgm cgmslip   on slipnum.k17_numcgm  = cgmslip.z01_numcgm	";
    $sql .= "  inner join empagepag    on e85_codmov = e81_codmov 	";
    $sql .= "  inner join empagetipo   on e85_codtipo = e83_codtipo ";
    $sql .= "  inner join conplanoreduz contareduz  on contareduz.c61_reduz = e83_conta	";
    $sql .= "                                      and contareduz.c61_anousu = ".db_getsession('DB_anousu');
    $sql .= "  inner join conplanoconta on contareduz.c61_codcon = c63_codcon ";
 		$sql .= "                          and contareduz.c61_anousu = c63_anousu ";
 		$sql .= " ".$sWhere2." ";
 		$sql .= "  order by e86_data, e91_cheque, empenho ";

		$rsSql = db_query($sql);
 		if(pg_num_rows($rsSql) > 0){
 			$oRetorno->dados = db_utils::getCollectionByRecord($rsSql,false,false,true);
 		}
		break;

	case 'getHistCheque':

		$sql  = "select distinct e91_codcheque, ";
    $sql .= "  e86_cheque,	";
    $sql .= "  e91_cheque ,	";
    $sql .= "  case when cgmslip.z01_numcgm is null then cgmemp.z01_numcgm else cgmslip.z01_numcgm end  as numcgm,	";
    $sql .= "  case when cgmslip.z01_nome is null then cgmemp.z01_nome else cgmslip.z01_nome end  as credor, 				";
    $sql .= "  e86_data, 		";
    $sql .= "  case when e81_numemp <> 0 then e60_codemp||'/'||e60_anousu else 'slip' end  as empenho,	";
    $sql .= "  e83_descr,";
    $sql .= "  case when e89_codigo is null then e82_codord else e89_codigo end as codigo_origem,	";
    $sql .= "  case when cpreduzslip.c61_reduz is null then o58_codigo else cpreduzslip.c61_codigo end as recurso,	";
    $sql .= "  e91_valor,	";
    $sql .= "  c63_banco,	";
    $sql .= "  c60_codcon,	";
    $sql .= "  c60_descr,	";
    $sql .= "  o15_codigo,	";
    $sql .= "  o15_descr,	";
    $sql .= "  e83_descr,	";
    $sql .= "  contareduz.c61_reduz,	";
    $sql .= "  db90_descr,	";
    $sql .= "  c63_agencia	,";
    $sql .= "  case when e91_ativo is true then 'Não' else 'Sim' end as anulado";
 		$sql .= "  from empageconfche	";
    $sql .= "  inner join empageconf   on e86_codmov  = e91_codmov	";
    $sql .= "  left  join empord       on e82_codmov  = e86_codmov 	";
    $sql .= "  left  join empageslip   on e86_codmov  = e89_codmov 	";
    $sql .= "  inner join empagemov    on e86_codmov  = e81_codmov 	";
    $sql .= "  left  join pagordem     on e50_codord      = e82_codord ";
    $sql .= "  left  join empempenho   on e60_numemp      = e50_numemp ";
    $sql .= "  left  join cgm          on cgm.z01_numcgm  = e60_numcgm ";
    $sql .= "  left  join orcdotacao   on e60_coddot  = o58_coddot 	";
    $sql .= "                         and e60_anousu  = o58_anousu 	";
    $sql .= "	 	";
    $sql .= "  left join slip          on slip.k17_codigo  = e89_codigo	";
    $sql .= "  left join conplanoreduz cpreduzslip on slip.k17_credito = c61_reduz	";
    $sql .= "                         and cpreduzslip.c61_anousu::integer  = extract(year from k17_data)::integer	";
    $sql .= "  left join cgm cgmemp    on e60_numcgm = cgmemp.z01_numcgm ";
    $sql .= "  left join slipnum       on slipnum.k17_codigo = slip.k17_codigo	";
    $sql .= "  left join cgm cgmslip   on slipnum.k17_numcgm  = cgmslip.z01_numcgm	";
    $sql .= "  inner join empagepag    on e85_codmov = e81_codmov 	";
    $sql .= "  inner join empagetipo   on e85_codtipo = e83_codtipo ";
    $sql .= "  inner join conplanoreduz contareduz  on contareduz.c61_reduz = e83_conta	";
    $sql .= "                                      and contareduz.c61_anousu = ".db_getsession('DB_anousu');
    $sql .= "  inner join conplano conta  on contareduz.c61_codcon = c60_codcon	";
    $sql .= "                                      and contareduz.c61_anousu = c60_anousu";
    $sql .= "  inner join orctiporec on contareduz.c61_codigo = o15_codigo  	";
    $sql .= "  inner join conplanoconta on contareduz.c61_codcon = c63_codcon ";
 		$sql .= "                          and contareduz.c61_anousu = c63_anousu ";
 		$sql .= "  left join db_bancos on c63_banco = db90_codban ";
 		$sql .= " where e91_cheque  = '".$oParam->e91_cheque ."'";
 		$sql .= "   and c63_banco   = '".$oParam->c63_banco  ."'";
 		$sql .= "   and c63_agencia = '".$oParam->c63_agencia."'";
 		$sql .= "   and c63_conta 	= '".$oParam->c63_conta."'";

 		$sql .= " union ";

 		$sql .= "select distinct e93_codcheque, ";
    $sql .= "  e88_cheque as e86_cheque,	";
    $sql .= "  e93_cheque as e91_cheque,	";
    $sql .= "  case when cgmslip.z01_numcgm is null then cgmemp.z01_numcgm else cgmslip.z01_numcgm end  as numcgm,	";
    $sql .= "  case when cgmslip.z01_nome is null then cgmemp.z01_nome else cgmslip.z01_nome end  as credor, 				";
    $sql .= "  e88_data as e86_data, 		";
    $sql .= "  case when e81_numemp <> 0 then e60_codemp||'/'||e60_anousu else 'slip' end  as empenho,	";
    $sql .= "  e83_descr,";
    $sql .= "  case when e89_codigo is null then e82_codord else e89_codigo end as codigo_origem,	";
    $sql .= "  case when cpreduzslip.c61_reduz is null then o58_codigo else cpreduzslip.c61_codigo end as recurso,	";
    $sql .= "  e93_valor,	";
    $sql .= "  c63_banco,	";
    $sql .= "  c60_codcon,	";
    $sql .= "  c60_descr,	";
    $sql .= "  o15_codigo,	";
    $sql .= "  o15_descr,	";
    $sql .= "  e83_descr,	";
    $sql .= "  contareduz.c61_reduz,	";
    $sql .= "  db90_descr,	";
    $sql .= "  c63_agencia	,";
    $sql .= "  'Sim' as anulado ";
 		$sql .= "  from empageconfchecanc	";
    $sql .= "  inner join empageconfcanc  on e88_codmov  = e93_codmov	";
    $sql .= "  left  join empord       on e82_codmov  = e88_codmov 	";
    $sql .= "  left  join empageslip   on e88_codmov  = e89_codmov 	";
    $sql .= "  inner join empagemov    on e88_codmov  = e81_codmov 	";
    $sql .= "  left  join pagordem     on e50_codord      = e82_codord ";
    $sql .= "  left  join empempenho   on e60_numemp      = e50_numemp ";
    $sql .= "  left  join cgm          on cgm.z01_numcgm  = e60_numcgm ";
    $sql .= "  left  join orcdotacao   on e60_coddot  = o58_coddot 	";
    $sql .= "                         and e60_anousu  = o58_anousu 	";
    $sql .= "	 	";
    $sql .= "  left join slip          on slip.k17_codigo  = e89_codigo	";
    $sql .= "  left join conplanoreduz cpreduzslip on slip.k17_credito = c61_reduz	";
    $sql .= "                         and cpreduzslip.c61_anousu::integer  = extract(year from k17_data)::integer	";
    $sql .= "  left join cgm cgmemp    on e60_numcgm = cgmemp.z01_numcgm ";
    $sql .= "  left join slipnum       on slipnum.k17_codigo = slip.k17_codigo	";
    $sql .= "  left join cgm cgmslip   on slipnum.k17_numcgm  = cgmslip.z01_numcgm	";
    $sql .= "  inner join empagepag    on e85_codmov = e81_codmov 	";
    $sql .= "  inner join empagetipo   on e85_codtipo = e83_codtipo ";
    $sql .= "  inner join conplanoreduz contareduz  on contareduz.c61_reduz = e83_conta	";
    $sql .= "                                      and contareduz.c61_anousu = ".db_getsession('DB_anousu');
    $sql .= "  inner join conplano conta  on contareduz.c61_codcon = c60_codcon	";
    $sql .= "                                      and contareduz.c61_anousu = c60_anousu";
    $sql .= "  inner join orctiporec on contareduz.c61_codigo = o15_codigo  	";
    $sql .= "  inner join conplanoconta on contareduz.c61_codcon = c63_codcon ";
 		$sql .= "                          and contareduz.c61_anousu = c63_anousu ";
 		$sql .= "  left join db_bancos on c63_banco = db90_codban ";

 		$sql .= " where e93_cheque  = '".$oParam->e91_cheque ."'";
 		$sql .= "   and c63_banco   = '".$oParam->c63_banco  ."'";
 		$sql .= "   and c63_agencia = '".$oParam->c63_agencia."'";
 		$sql .= "   and c63_conta 	= '".$oParam->c63_conta."'";
 		$sql .= "  order by e86_data, e91_cheque, empenho ";

 		$rsSql = db_query($sql);
 		if(pg_num_rows($rsSql) > 0){

 			$aTemp = array();
 			$aTemp = db_utils::getCollectionByRecord($rsSql,false,false,true);
 			$oRetorno->dados	= array();
 			$oDado 					 	= new stdClass();
 			$oDado->valor 			= 0;
 			$oDado->numcgm 			= $aTemp[0]->numcgm;
 			$oDado->credor 			= $aTemp[0]->credor;
 			$oDado->o15_codigo 	= $aTemp[0]->o15_codigo;
 			$oDado->o15_descr 	= $aTemp[0]->o15_descr;
 			$oDado->e83_descr 	= $aTemp[0]->e83_descr;
 			$oDado->c61_reduz 	= $aTemp[0]->c61_reduz;
 			$oDado->c60_descr 	= $aTemp[0]->c60_descr;
 			$oDado->c63_banco 	= $aTemp[0]->c63_banco;
 			$oDado->recurso 		= $aTemp[0]->recurso;
 			$oDado->e91_cheque	= $aTemp[0]->e91_cheque;
 			$oDado->db90_descr	= $aTemp[0]->db90_descr;
 			$oDado->e86_data		= $aTemp[0]->e86_data;
 			$oDado->empenho			= "";
 			$oDado->ordem				= "";
 			$oDado->slip				= "";
 			$oDado->anulado			= $aTemp[0]->anulado;
 			$virgula = "";
 			$virgula_slip = "";
 			$virgula_ordem = "";
 			$str_e91_codcheque = "";
 			$add = true;
 			foreach ($aTemp as $oRow){

 				if($oRow->empenho == 'slip' && $add == true){

 					$oDado->empenho  		.= $virgula.$oRow->empenho;
 					$add = false;
 				}else if($oRow->empenho != 'slip'){
 					$oDado->empenho  		.= $virgula.$oRow->empenho;
 				}

				if($oRow->empenho == 'slip '){
					$oDado->slip	= $oRow->codigo_origem;
				}else{
 					$oDado->ordem	.= $virgula_ordem.$oRow->codigo_origem;
 					$virgula_ordem = ", ";
				}
 				$oDado->valor   		+= $oRow->e91_valor;
 				$str_e91_codcheque 	.= $virgula.$oRow->e91_codcheque;
 				$virgula = ",";
 			}
 			$oRetorno->dados[] = $oDado;

 			$sql  = "SELECT corrente.k12_data, ";
      $sql .= " case when corrente.k12_estorn is true then 'Estornado' else 'Autenticado' end as situacao, ";
      $sql .= " k11_tesoureiro, ";
      $sql .= " case when coremp.k12_codord is null then 'S: '||corlanc.k12_codigo::text else  'OP: '||coremp.k12_codord::text end as k12_codord ";
   		$sql .= " from corconf inner join ";
      $sql .= " 							 corrente 			on corrente.k12_id 		=	corconf.k12_id ";
      $sql .= "           								and corrente.k12_data 		= corconf.k12_data ";
      $sql .= "           								and corrente.k12_autent 	= corconf.k12_autent ";
      $sql .= "     inner join cfautent 			on corrente.k12_id 		= k11_id ";
      $sql .= "     inner join empageconfche 	on corconf.k12_codmov = e91_codcheque ";
      $sql .= "     left join coremp 				on coremp.k12_id 		 	= corrente.k12_id ";
      $sql .= "     									 		and coremp.k12_data 	 	  = corrente.k12_data ";
      $sql .= "     									 		and coremp.k12_autent 		= corrente.k12_autent ";
      $sql .= "			left join corlanc on corlanc.k12_id  = corrente.k12_id ";
 			$sql .= "			                 and corlanc.k12_data    = corrente.k12_data    ";
 			$sql .= "			                 and corlanc.k12_autent = corrente.k12_autent  ";
  		$sql .= " 	where e91_codcheque in (".$str_e91_codcheque.")";

  		$sql .= " union ";

  		$sql .= "SELECT corrente.k12_data, ";
      $sql .= " case when corrente.k12_estorn is true then 'Estornado' else 'Autenticado' end as situacao, ";
      $sql .= " k11_tesoureiro, ";
      $sql .= " case when coremp.k12_codord is null then 'S: '||corlanc.k12_codigo::text else  'OP: '||coremp.k12_codord::text end as k12_codord ";
   		$sql .= " from corconf inner join ";
      $sql .= " 							 corrente 			on corrente.k12_id 		=	corconf.k12_id ";
      $sql .= "           								and corrente.k12_data 		= corconf.k12_data ";
      $sql .= "           								and corrente.k12_autent 	= corconf.k12_autent ";
      $sql .= "     inner join cfautent 			on corrente.k12_id 		= k11_id ";
      $sql .= "     inner join empageconfchecanc 	on corconf.k12_codmov = e93_codcheque ";
      $sql .= "     left join coremp 				on coremp.k12_id 		 	= corrente.k12_id ";
      $sql .= "     									 		and coremp.k12_data 	 	  = corrente.k12_data ";
      $sql .= "     									 		and coremp.k12_autent 		= corrente.k12_autent ";
      $sql .= "			left join corlanc on corlanc.k12_id  = corrente.k12_id ";
 			$sql .= "			                 and corlanc.k12_data    = corrente.k12_data    ";
 			$sql .= "			                 and corlanc.k12_autent = corrente.k12_autent  ";
  		$sql .= " 	where e93_codcheque in (".$str_e91_codcheque.")";

  		$rsSql = db_query($sql);

  		if(pg_num_rows($rsSql) > 0){
				$oRetorno->historico = db_utils::getCollectionByRecord($rsSql,false,false,true);
 			}

 		}
		break;
}

echo $oJson->encode($oRetorno);

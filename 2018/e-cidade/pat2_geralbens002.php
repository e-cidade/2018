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

require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("classes/db_bens_classe.php");
require_once("classes/db_bensbaix_classe.php");
require_once("classes/db_cfpatriplaca_classe.php");
require_once("classes/db_benscadcedente_classe.php");
$clbenscadcedente = new cl_benscadcedente();
$clbens         = new cl_bens;
$clbensbaix     = new cl_bensbaix;
$clcfpatriplaca = new cl_cfpatriplaca;

$clbens->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label('z01_nome');
$clrotulo->label('descrdepto');
$clrotulo->label('t64_descr');
$clrotulo->label('t64_class');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$sSqlPatriPlaca   = $clcfpatriplaca->sql_query_file(db_getsession("DB_instit"));
$res_cfpatriplaca = $clcfpatriplaca->sql_record($sSqlPatriPlaca);
if ($clcfpatriplaca->numrows > 0){
  db_fieldsmemory($res_cfpatriplaca,0);
} else {

  $sMsg = _M('patrimonial.patrimonio.pat2_geralbens002.nao_existem_placas_para_instituicao');
  db_redireciona('db_erros.php?fechar=true&db_erro=' . $sMsg);
  exit;
}

$where_instit = "";
$ordem        = "";
$info         = "Ordenado por ";
$quebra				= "";
if ($ordenar == "depart"){
  $ordem = "t52_depart";
  $info  .= "Departamento";
}else if ($ordenar == "placa"){
  if($t07_confplaca==1 or $t07_confplaca==4) {
    $ordem = "cast( regexp_replace( coalesce(nullif(trim(t52_ident),''), '0') , '[^0-9.,-]' , '', 'g') as numeric)";
  } else {
    $ordem = "t52_ident";
  }
  $info  .= "Placa";
}else if ($ordenar == "bem"){
  $ordem = "t52_bem";
  $info  .= "Bem";
}else if ($ordenar == "classi"){
  $ordem = "t64_class";
  $info  .= "Classificação";
}else if ($ordenar == "data"){
  $ordem = "t52_dtaqu";
  $info  .= "Data de Aquisição";

}else if ($ordenar == "orgao"){
	$ordem  = "db01_orgao,db01_unidade,t52_depart, t52_ident";
  $info  .= "Órgão";
}else if ($ordenar == "unidade"){
	$ordem  = "db01_orgao,db01_unidade,t52_depart, t52_ident";
	$info  .= "Unidade";
}else if ($ordenar == "descricao"){
	$ordem  = "t52_descr";
	$info  .= "Descrição do Bem";
}

if($bens_convenio == "T"){
	$head7 = "Bens: Todos";
}else if ($bens_convenio == "N"){
	$head7 = "Bens: Nenhum Convênio";
}else if ($bens_convenio == "S"){
	$head7 = "Bens: Com Convênio";
}


if(isset($quebra_por) && $quebra_por != "" && $imp_classi == "S"){

	if($quebra_por == 2){
		if($ordenar == "depart"){
			$ordem .= ",t33_divisao";
		}else{
			$ordem .= "t52_depart,t33_divisao,".$ordem;
		}

	}else if($quebra_por == 3){
		$ordem = $ordem == "" ? "t64_class" : "t64_class,".$ordem;
	}

}

//if($q_pagina == 'departamento'){
//	$ordem .= ",db01_coddepto ";
//}

if (isset($coddepart) and $coddepart!=0 && $coddepart != ""){
  $where_instit.=" t52_depart=$coddepart ";
}

if(isset($divisao) && trim($divisao) != "" && $divisao != 0){
	if ($where_instit != "") {
		$where_instit .= " and t33_divisao = $divisao ";
	}else{
		$where_instit .= " t33_divisao = $divisao ";
	}

}

//die($where_instit);
$flag_datas = 0;
if (isset($data_inicial) && trim(@$data_inicial) != "" && isset($data_final) && trim(@$data_final) != ""){
  $flag_datas = 1;
} else if (isset($data_inicial) && trim(@$data_inicial) != ""){
  $flag_datas = 2;
} else if (isset($data_final) && trim(@$data_final) != ""){
  $flag_datas = 3;
}

if( ($flag_datas == 1 || $flag_datas == 2 || $flag_datas == 3) && $where_instit != ""){
  $where_instit .= " and ";
}

if ($flag_datas == 1){
  $where_instit.= "t52_dtaqu between '$data_inicial' and '$data_final'";
  $info        .= "\nPeriodo de ".db_formatar($data_inicial,"d")." a ".db_formatar($data_final,"d");
}

if ($flag_datas == 2){
  $where_instit.= "t52_dtaqu >= '$data_inicial'";
  $info        .= "\nAquisição a partir de ".db_formatar($data_inicial,"d");
}

if ($flag_datas == 3){
  $where_instit.= "t52_dtaqu <= '$data_final'";
  $info        .= "\nAquisição até ".db_formatar($data_final,"d");
}


$flag_forn   = false;
$flag_classi = false;

if ($imp_forn == "S"){
  $flag_forn = true;
}

if ($imp_classi == "S"){
  $flag_classi = true;
}


$head3  = "RELATÓRIO GERAL DE BENS";
$head4  = $info;

if($q_pagina == 'S'){
	$head5 = "";
}elseif ($q_pagina == 'orgao'){
	$head5 = "Quebra por Órgão ";
}else if ($q_pagina == 'unidade'){
	$head5 = "Quebra por Unidade ";
}else if ($q_pagina == 'departamento'){
	$head5 = "Quebra por Departamento ";
}


if ($where_instit != ""){
	$where_instit .= " and ";
}

$where_instit .= "t52_instit = ".db_getsession("DB_instit");


if($t07_confplaca==1 or $t07_confplaca==4) {
  $campos = "t52_bem, t52_descr, round(t52_valaqu,2) as t52_valaqu, t52_dtaqu, cast( regexp_replace( coalesce(nullif(trim(t52_ident),''), '0') , '[^0-9.,-]' , '', 'g') as numeric) as t52_ident,
  					 t52_depart, descrdepto, t52_numcgm, z01_nome, t52_obs, t64_class, t64_descr, t33_divisao, departdiv.t30_descr,
  					 (select count(*)
                     from bensplaca
                          inner join bensplacaimpressa on bensplacaimpressa.t73_bensplaca = bensplaca.t41_codigo
                    where t41_bem = t52_bem) as totaletiquetas";
} else {
  $campos = "t52_bem, t52_descr, round(t52_valaqu,2) as t52_valaqu, t52_dtaqu, t52_ident, t52_depart, descrdepto, t52_numcgm, z01_nome, t52_obs,
  					 t64_class, t64_descr, t33_divisao, departdiv.t30_descr,
  					 (select count(*)
                     from bensplaca
                          inner join bensplacaimpressa on bensplacaimpressa.t73_bensplaca = bensplaca.t41_codigo
                    where t41_bem = t52_bem) as totaletiquetas";
}

if(isset($orgaos) && isset($unidades) && isset($departamentos)){

	$campos .= ",o40_descr,o40_orgao,o41_unidade,o41_descr,db01_orgao,db01_unidade";
	$campos = "distinct ".$campos;
	if($orgaos != ""){
		$where_instit .= " and db01_orgao in $orgaos ";
	}
	if($unidades != ""){
		$where_instit .= " and db01_unidade in $unidades ";
	}
	if($departamentos != ""){
		$where_instit .= " and db01_coddepto in $departamentos ";
	}
	$where_instit .= " and db01_anousu =".db_getsession('DB_anousu');

	if(isset($conv) && trim($conv) !="" && isset($bens_convenio) && trim($bens_convenio) == "S"){
		$where_instit .= " and t09_benscadcedente = $conv";
		$sqlrelatorio = $clbens->sql_query_orgao_convenio(null,"$campos",$ordem,"$where_instit");
		$rsConvenio = $clbenscadcedente->sql_record($clbenscadcedente->sql_query($conv,"z01_nome as convenio"));
		if($clbenscadcedente->numrows > 0){
			db_fieldsmemory($rsConvenio,0);
		}
		$head6 = "Convênio : $convenio";
	}else if(isset($bens_convenio) && trim($bens_convenio) == "S"){
		$where_instit .= " and benscedente.t09_bem is not null ";
		$sqlrelatorio = $clbens->sql_query_left_convenio(null,"$campos",$ordem,"$where_instit");
	}else if (isset($bens_convenio) && trim($bens_convenio) == "N"){
		$where_instit .= " and benscedente.t09_bem is null ";
		$sqlrelatorio = $clbens->sql_query_left_convenio(null,"$campos",$ordem,"$where_instit");
	}else{
		$sqlrelatorio = $clbens->sql_query_orgao(null,"$campos",$ordem,"$where_instit");
	}

}else{
	$campos = "distinct ".$campos;

	if(isset($conv) && trim($conv) !="" && isset($bens_convenio) && trim($bens_convenio) == "S"){
		$where_instit .= " and t09_benscadcedente = $conv";
		$sqlrelatorio = $clbens->sql_query_convenio(null,"$campos",$ordem,"$where_instit");
		$rsConvenio = $clbenscadcedente->sql_record($clbenscadcedente->sql_query($conv,"z01_nome as convenio"));
		if($clbenscadcedente->numrows > 0){
			db_fieldsmemory($rsConvenio,0);
		}
		$head6 = "Convênio : $convenio" 	;
	}else if(isset($bens_convenio) && trim($bens_convenio) == "N"){
		//Opção nehum convênio
		$where_instit .= " and benscedente.t09_bem is null ";
		$sqlrelatorio = $clbens->sql_query_left_convenio(null,"$campos",$ordem,"$where_instit");
	}else if(isset($bens_convenio) && trim($bens_convenio) == "S"){
		//Opção nehum convênio
		$where_instit .= " and benscedente.t09_bem is not null ";
		$sqlrelatorio = $clbens->sql_query_left_convenio(null,"$campos",$ordem,"$where_instit");
	}else{
		//Opção todos
		$sqlrelatorio = $clbens->sql_query(null,"$campos",$ordem,"$where_instit");
	}
}

//$sqlrelatorio = $clbens->sql_query(null,"$campos",$ordem,"$where_instit");

$result = $clbens->sql_record($sqlrelatorio);
if ($clbens->numrows == 0){

  $sMsg = _M('patrimonial.patrimonio.pat2_geralbens002.nao_existem_registros');
  db_redireciona('db_erros.php?fechar=true&db_erro=' . $sMsg);
  exit;
}

$pdf = new PDF("L");
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$pdf->AddPage("L");
$troca   = 1;
$alt     = 4;
$p       = 1;
$total   = 0;
$totalvalor = 0;
$numrows = $clbens->numrows;

//quebra de página
$valortotal				= 0;
$ident						= 0;
$contaregistro		= 0;
$passa						= 0;
$iOrgao 					= 0;
$iUnidade 				= 0;
$depto_anterior 	= 0;
$divisao_anterior = 0;
$fSoma						= 0;
$iTotal						= 0;
$divisao_null			= false;
$class_anterior		= 0;
$background 			= 1;
$limprime_total_depto = false;
$aClassiImpressos 	  = array();
$aDeptosImpressos 	  = array();
$aDivisaoImpressos 	  = array();


for($x = 0; $x < $numrows; $x++){

  db_fieldsmemory($result,$x);

  $sWhereBensBaixados    = " t55_codbem = {$t52_bem}  ";
  if (!empty($data_final)) {
    $sWhereBensBaixados .= " and t55_baixa <= '{$data_final}' ";
  }
  $sSqlBuscaBensBaixados = $clbensbaix->sql_query_file(null, "*", null, $sWhereBensBaixados);
  $result_bensbaix = $clbensbaix->sql_record($sSqlBuscaBensBaixados);
  if ($clbensbaix->numrows>0) {
    continue;
  }

	if (($quebra_por == 1 && $imp_classi == "S") || $imp_classi == "N") {

	  if ($q_pagina == 'orgao') {

	  	if ($iUnidade != $o41_unidade) {

	  			$pdf->Ln(3);
	    		$pdf->setfont('arial','b',10);
	    		$pdf->cell(20,$alt,"Unidade",0,0,"L",0);
				  $pdf->cell(30,$alt,$o41_unidade." - ".$o41_descr,0,1,"L",0);
				  $pdf->Ln(3);
				  orgao_cabecalho($pdf,$RLt52_descr,$RLt52_ident,$RLt52_depart,$alt);
				  $iUnidade = $o41_unidade;
	  	}
	  }

	  //quebrar página
	  if ($q_pagina=="S"){

	    //quebra por departamento
	    if ($ordenar=="depart"){

	      if ($ident==0){
	        $passa=1;
	      }else{
	        $passa=2;
	      }

	      if (isset($identifica)){

	        if ($passa==2 and $identifica!=$t52_depart){

	          $pdf->cell(135,$alt,'VALOR TOTAL:  '.$valortotal,"T",0,"R",0);
	          $pdf->cell(130,$alt,'TOTAL DE REGISTROS  :  '.$contaregistro,"T",1,"R",0);
	          $pdf->addpage("L");
	          $imprime_total_parcial=true;
	          $contaregistro=0;
	          $valortotal=0;
	        }
	      }

	      if ($ident==0){
	        $valortotal+=$t52_valaqu;
	        $contaregistro++;

	      } else{
	        if ($ident==$t52_depart ){
	          $contaregistro++;
	          $valortotal+=$t52_valaqu;
	        }else{
	          $valortotal+=$t52_valaqu;
	          $contaregistro++;
	        }
	      }

	      $identifica=$t52_depart;
	      $ident=2;
	    }//fim quebra por departamento

	    //quebra por placa
	    if ($ordenar=="placa"){

	      if ($ident==0){
	        $passa=1;
	      }else{
	        $passa=2;
	      }

	      if (isset($identifica)){

	        if ($passa==2 and $identifica!=$t52_ident){

	          $pdf->cell(135,$alt,'VALOR TOTAL:  '.$valortotal,"T",0,"R",0);
	          $pdf->cell(130,$alt,'TOTAL DE REGISTROS  :  '.$contaregistro,"T",1,"R",0);
	          $pdf->addpage("L");
	          $imprime_total_parcial=true;
	          $contaregistro=0;
	          $valortotal=0;
	        }
	      }

	      if ($ident==0){

	        $valortotal+=$t52_valaqu;
	        $contaregistro++;
	      } else{
	        if ($ident==$t52_ident ){
	          $contaregistro++;
	          $valortotal+=$t52_valaqu;
	        }else{
	          $valortotal+=$t52_valaqu;
	          $contaregistro++;

	        }
	      }

	      $identifica=$t52_ident;
	      $ident=2;
	    }//fim quebra por placa


	    //quebra por bem
	    if ($ordenar=="bem"){

	      if ($ident==0){
	        $passa=1;
	      }else{
	        $passa=2;
	      }

	      if (isset($identifica)){

	        if ($passa==2 and $identifica!=$t52_bem){

	          $pdf->cell(135,$alt,'VALOR TOTAL:  '.$valortotal,"T",0,"R",0);
	          $pdf->cell(130,$alt,'TOTAL DE REGISTROS  :  '.$contaregistro,"T",1,"R",0);
	          $pdf->addpage("L");
	          $imprime_total_parcial=true;
	          $contaregistro=0;
	          $valortotal=0;

	        }
	      }

	      if ($ident==0){
	        $valortotal+=$t52_valaqu;
	        $contaregistro++;

	      } else{
	        if ($ident==$t52_bem ){
	          $contaregistro++;
	          $valortotal+=$t52_valaqu;
	        }else{
	          $valortotal+=$t52_valaqu;
	          $contaregistro++;

	        }
	      }

	      $identifica=$t52_bem;
	      $ident=2;
	    }//fim quebra por bem

	    //quebra por classificação
	    if ($ordenar == "classi") {

	      if ($ident == 0) {
	        $passa = 1;
	      } else {
	        $passa = 2;
	      }

	      if (isset($identifica)){

	        if ($passa==2 and $identifica!=$t64_class){

	          $pdf->cell(135,$alt,'VALOR TOTAL:  '.$valortotal,"T",0,"R",0);
	          $pdf->cell(130,$alt,'TOTAL DE REGISTROS  :  '.$contaregistro,"T",1,"R",0);
	          $pdf->AddPage("L");
	          $imprime_total_parcial=true;
	          $contaregistro=0;
	          $valortotal=0;
	        }
	      }

	      if ($ident==0){

	        $valortotal+=$t52_valaqu;
	        $contaregistro++;
	      } else{
	        if ($ident==$t64_class ){

	          $contaregistro++;
	          $valortotal+=$t52_valaqu;
	        }else{

	          $valortotal+=$t52_valaqu;
	          $contaregistro++;
	        }
	      }

	      $identifica=$t64_class;
	      $ident=2;
	    }//fim quebra por classificação


	    //quebra por data aquisição
	    if ($ordenar=="data"){

	      if ($ident==0){
	        $passa=1;
	      }else{
	        $passa=2;
	      }

	      if (isset($identifica)){

	        if ($passa==2 and $identifica!=$t52_dtaqu){

	          $pdf->cell(135,$alt,'VALOR TOTAL:  '.$valortotal,"T",0,"R",0);
	          $pdf->cell(130,$alt,'TOTAL DE REGISTROS  :  '.$contaregistro,"T",1,"R",0);
	          $pdf->addpage("L");
	          $imprime_total_parcial=true;
	          $quebra_pagina=false;
	          $contaregistro=0;
	          $valortotal=0;

	        }
	      }

	      if ($ident==0){
	        $valortotal+=$t52_valaqu;
	        $contaregistro++;

	      } else{
	        if ($ident==$t52_dtaqu ){
	          $contaregistro++;
	          $valortotal+=$t52_valaqu;
	        }else{
	          $valortotal+=$t52_valaqu;
	          $contaregistro++;

	        }
	      }

	      $identifica=$t52_dtaqu;
	      $ident=2;
	    }//fim quebra por data aquisição


	  }else if ($q_pagina == 'orgao'){

	  		if ($ident==0){
	        $passa=1;
	      }else{
	        $passa=2;
	      }

	      if (isset($identifica)){

	        if ($passa==2 and $identifica!=$o40_orgao){

	          $pdf->cell(135,$alt,'VALOR TOTAL:  '.$valortotal,"T",0,"R",0);
	          $pdf->cell(130,$alt,'TOTAL DE REGISTROS  :  '.$contaregistro,"T",1,"R",0);
	          $pdf->addpage("L");
	          $pdf->setfont('arial','b',10);
	          $pdf->cell(20,$alt,"Órgão",0,0,"L",0);
			    	$pdf->cell(30,$alt,$o40_orgao." - ".$o40_descr,0,1,"L",0);

	          $imprime_total_parcial=true;
	          $quebra_pagina=false;
	          $contaregistro=0;
	          $valortotal=0;

	        }
	      }

	      if ($ident==0){
	        $valortotal+=$t52_valaqu;
	        $contaregistro++;

	      } else{
	        if ($ident==$o40_orgao ){
	          $contaregistro++;
	          $valortotal+=$t52_valaqu;
	        }else{
	          $valortotal+=$t52_valaqu;
	          $contaregistro++;

	        }
	      }
	      $identifica=$o40_orgao;
	      $ident=2;
	  }else if ($q_pagina == 'unidade'){

	  		if ($ident==0){
	        $passa=1;
	      }else{
	        $passa=2;
	      }

	      if (isset($identifica)){

	        if ($passa==2 and $identifica!=$o41_unidade){

	          $pdf->cell(135,$alt,'VALOR TOTAL:  '.$valortotal,"T",0,"R",0);
	          $pdf->cell(130,$alt,'TOTAL DE REGISTROS  :  '.$contaregistro,"T",1,"R",0);
	          $pdf->addpage("L");
	          $pdf->setfont('arial','b',10);
	          $pdf->cell(20,$alt,"Unidade",0,0,"L",0);
			    	$pdf->cell(30,$alt,$o41_unidade." - ".$o41_descr,0,1,"L",0);
			    	orgao_cabecalho($pdf,$RLt52_descr,$RLt52_ident,$RLt52_depart,$alt);
			    	$p=0;
	          $imprime_total_parcial=true;
	          $quebra_pagina=false;
	          $contaregistro=0;
	          $valortotal=0;

	        }
	      }

	      if ($ident==0){
	        $valortotal+=$t52_valaqu;
	        $contaregistro++;

	      } else{
	        if ($ident==$o41_unidade ){
	          $contaregistro++;
	          $valortotal+=$t52_valaqu;
	        }else{
	          $valortotal+=$t52_valaqu;
	          $contaregistro++;

	        }
	      }
	      $identifica=$o41_unidade;
	      $ident=2;
	  }else if ($q_pagina == 'departamento'){

	  		if ($ident==0){
	        $passa=1;
	      }else{
	        $passa=2;
	      }

	      if (isset($identifica)){

	        if ($passa==2 and $identifica!=$t52_depart) {

	          $pdf->cell(135,$alt,'VALOR TOTAL:  '.$valortotal,"T",0,"R",0);
	          $pdf->cell(130,$alt,'TOTAL DE REGISTROS  :  '.$contaregistro,"T",1,"R",0);
	          $pdf->addpage("L");
	          $pdf->setfont('arial','b',10);
	          $pdf->cell(30,$alt,"Departamento",0,0,"L",0);
			    	$pdf->cell(30,$alt,$t52_depart." - ".$descrdepto,0,1,"L",0);
			    	orgao_cabecalho($pdf,$RLt52_descr,$RLt52_ident,$RLt52_depart,$alt);
			    	$p=0;
	          $imprime_total_parcial=true;
	          $quebra_pagina=false;
	          $contaregistro=0;
	          $valortotal=0;

	        }
	      }

	      if ($ident==0){
	        $valortotal+=$t52_valaqu;
	        $contaregistro++;

	      } else{
	        if ($ident==$t52_depart ){
	          $contaregistro++;
	          $valortotal+=$t52_valaqu;
	        }else{
	          $valortotal+=$t52_valaqu;
	          $contaregistro++;

	        }
	      }
	      $identifica=$t52_depart;
	      $ident=2;
	  }

	  if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ) {

	  	if ($pdf->gety() > $pdf->h - 30) {
	      $pdf->addpage("L");
	  	}
			$lCabecalho = false;
	    if(isset($orgaos) && isset($unidades) && isset($departamentos)&& $q_pagina != "N"){

	    	if($iOrgao!=$o40_orgao){
		    	$pdf->setfont('arial','b',10);
		      $pdf->cell(30,$alt,"Órgão",0,0,"L",0);
				  $pdf->cell(30,$alt,$o40_orgao." - ".$o40_descr,0,1,"L",0);
				  if($q_pagina != "N"){
				  $pdf->cell(30,$alt,"Unidade",0,0,"L",0);
				  $pdf->cell(30,$alt,$o41_unidade." - ".$o41_descr,0,1,"L",0);
				  }
				  if($q_pagina == 'departamento'){
				  	$pdf->cell(30,$alt,"Departamento",0,0,"L",0);
			    	$pdf->cell(30,$alt,$t52_depart." - ".$descrdepto,0,1,"L",0);
				  }
				  $pdf->Ln(3);
				  orgao_cabecalho($pdf,$RLt52_descr,$RLt52_ident,$RLt52_depart,$alt);
				  $lCabecalho = true;
				  $p = 0;
				 // $iUnidade = 0;
	    	}else if($iUnidade != $o41_unidade){

	    		$pdf->setfont('arial','b',10);
	    		$pdf->cell(20,$alt,"Unidade",0,0,"L",0);
				  $pdf->cell(30,$alt,$o41_unidade." - ".$o41_descr,0,1,"L",0);
				  $pdf->Ln(3);



	    	}else{
	    		orgao_cabecalho($pdf,$RLt52_descr,$RLt52_ident,$RLt52_depart,$alt);
	    		$lCabecalho = true;
	    		$p = 0;
	    	}
	    	$iOrgao = $o40_orgao;
	    	$iUnidade = $o41_unidade;
	    }else{
	    	orgao_cabecalho($pdf,$RLt52_descr,$RLt52_ident,$RLt52_depart,$alt);
	    	$lCabecalho = true;
	    	$p = 0;
	    }
	    if(!$lCabecalho){
	    	orgao_cabecalho($pdf,$RLt52_descr,$RLt52_ident,$RLt52_depart,$alt);
	    	$p = 0;
	    }

	    if ($flag_forn == true){
	      $pdf->cell(20,$alt,$RLt52_numcgm,1,0,"C",1);
	      $pdf->cell(100,$alt,$RLz01_nome,1,0,"C",1);
	      $pdf->cell(150,$alt,$RLt52_obs,1,1,"C",1);
	    }

	    if ($flag_classi == true){
	      $pdf->cell(20,$alt,$RLt64_class,1,0,"C",1);
	      $pdf->cell(258,$alt,$RLt64_descr,1,1,"C",1);
	    }

	    $troca = 0;
	  }
	  if (strlen(trim($t52_ident)) > 0){
	    if ($t07_confplaca == 4){
	      $t52_ident = db_formatar($t52_ident,"s","0",$t07_digseqplaca,"e",0);
	    }
	  }

	  $pdf->setfont('arial','',7);
	  $pdf->cell(15,$alt,$t52_bem											,0,0,"C",$p);
	  $pdf->cell(65,$alt,substr($t52_descr,0,50)			,0,0,"L",$p);
	  $pdf->cell(20,$alt,db_formatar($t52_valaqu,"f")	,0,0,"R",$p);
	  $pdf->cell(25,$alt,db_formatar($t52_dtaqu,"d")	,0,0,"C",$p);
	  $pdf->cell(30,$alt,$t52_ident										,0,0,"C",$p);
	  $pdf->cell(60,$alt,$t52_depart."-".substr($descrdepto,0,36)	,0,0,"L",$p);
	  $pdf->cell(48,$alt,$t33_divisao."-".substr($t30_descr,0,27)	,0,0,"L",$p);
	  $placaIdentificacao = $totaletiquetas >= 1 ? "Sim" : "Não";
	  $pdf->cell(15,$alt,$placaIdentificacao	,0,1,"C",$p);

	  if ($flag_forn == true){
	    $pdf->cell(20,$alt,$t52_numcgm,0,0,"C",$p);
	    $pdf->cell(100,$alt,$z01_nome,0,0,"L",$p);
	    $pdf->multicell(150,$alt,$t52_obs,0,"L",$p);
	  }

	  if ($flag_classi == true){
	    $pdf->cell(20,$alt,$t64_class,0,0,"R",$p);
	    $pdf->cell(258,$alt,$t64_descr,0,1,"L",$p);
	  }


	  if ($p==0){
	    $p=1;
	  }else{
	    $p=0;
	  }

	  $total++;
	  $totalvalor += $t52_valaqu;

	}else if(($quebra_por == 2 || $quebra_por == 3) && $imp_classi == "S"){
		//die('aqui');
		if(isset($orgaos) && isset($unidades) && isset($departamentos)){
	    	if($iOrgao!=$o40_orgao){
	    		$iUnidade = 0;

	    		if($quebra_por == 2 && $depto_anterior !=0){
					 	$pdf->setfont('arial','b',8);
						$pdf->Ln();
				   	$pdf->cell(65,$alt,"Total da Divisão:"																							,0,0,"L",1);
				   	$pdf->cell(30,$alt,"Vlr Aquisição "																									,0,0,"R",1);
				   	$pdf->cell(20,$alt,db_formatar($aDivisaoImpressos[$divisao_anterior]["vlr_total"],'f'),0,0,"R",1);
				   	$pdf->cell(25,$alt,"Quantidade"																											,0,0,"R",1);
				   	$pdf->cell(20,$alt,$aDivisaoImpressos[$divisao_anterior]["qtd_total"]								,0,1,"C",1);
				   	$pdf->ln();
				   	$pdf->cell(65,$alt,"Total do Departamento:"																					,0,0,"L",1);
				   	$pdf->cell(30,$alt,"Vlr Aquisição "																									,0,0,"R",1);
				   	$pdf->cell(20,$alt,db_formatar($aDeptosImpressos[$depto_anterior]["vlr_total"],'f')	,0,0,"R",1);
				   	$pdf->cell(25,$alt,"Quantidade"																											,0,0,"R",1);
				   	$pdf->cell(20,$alt,$aDeptosImpressos[$depto_anterior]["qtd_total"]									,0,1,"C",1);
				   	$pdf->ln(2);
				   	$aDivisaoImpressos["semdivisao"]["vlr_total"] = 0;
			    	$aDivisaoImpressos["semdivisao"]["qtd_total"] = 0;
			    	$aDivisaoImpressos["semdivisao"]["descricao"] = "";
			    	$limprime_total_depto = false;
					 }else if ($quebra_por == 3 && $class_anterior !=0 ){
					 	$pdf->setfont('arial','b',8);
						$pdf->Ln();
			    	$pdf->cell(65,$alt,"Total da Classificação:"																				,0,0,"L",1);
			    	$pdf->cell(30,$alt,"Vlr Aquisição "																									,0,0,"R",1);
			    	$pdf->cell(20,$alt,db_formatar($aClassiImpressos[$class_anterior]["vlr_total"],'f')	,0,0,"R",1);
			    	$pdf->cell(25,$alt,"Quantidade"																											,0,0,"R",1);
			    	$pdf->cell(20,$alt,$aClassiImpressos[$class_anterior]["qtd_total"]									,0,1,"C",1);
			    	if($quebra_por == 3 && isset($orgaos)){
			    		unset($aClassiImpressos[$class_anterior]);
			    	}
			    	$pdf->ln();
					 }
					$class_anterior = 0;
					$lCabecalho = true;

		    	$pdf->setfont('arial','b',10);
		      $pdf->cell(30,$alt,"Órgão",0,0,"L",0);
				  $pdf->cell(30,$alt,$o40_orgao." - ".$o40_descr,0,1,"L",0);

	    	}

				if($iUnidade != $o41_unidade){

					if($quebra_por == 2 && $depto_anterior !=0 && $limprime_total_depto == true){
					 	$pdf->setfont('arial','b',8);
						$pdf->Ln();
				   	$pdf->cell(65,$alt,"Total da Divisão:"																									,0,0,"L",1);
				   	$pdf->cell(30,$alt,"Vlr Aquisição "																											,0,0,"R",1);
				   	$pdf->cell(20,$alt,db_formatar($aDivisaoImpressos[$divisao_anterior]["vlr_total"],'f')	,0,0,"R",1);
				   	$pdf->cell(25,$alt,"Quantidade"																													,0,0,"R",1);
				   	$pdf->cell(20,$alt,$aDivisaoImpressos[$divisao_anterior]["qtd_total"]										,0,1,"C",1);
				   	$pdf->ln();
				   	$pdf->cell(65,$alt,"Total do Departamento:"																							,0,0,"L",1);
				   	$pdf->cell(30,$alt,"Vlr Aquisição "																											,0,0,"R",1);
				   	$pdf->cell(20,$alt,db_formatar($aDeptosImpressos[$depto_anterior]["vlr_total"],'f')			,0,0,"R",1);
				   	$pdf->cell(25,$alt,"Quantidade"																													,0,0,"R",1);
				   	$pdf->cell(20,$alt,$aDeptosImpressos[$depto_anterior]["qtd_total"]											,0,1,"C",1);
				   	$pdf->ln(2);
				   	$aDivisaoImpressos["semdivisao"]["vlr_total"] = 0;
			    	$aDivisaoImpressos["semdivisao"]["qtd_total"] = 0;
			    	$aDivisaoImpressos["semdivisao"]["descricao"] = "";
			    	$limprime_total_depto = false;
					}else if ($quebra_por == 3 && $class_anterior != 0){
						$pdf->setfont('arial','b',8);
						$pdf->Ln();
			    	$pdf->cell(65,$alt,"Total da Classificação:"											,0,0,"L",1);
			    	$pdf->cell(30,$alt,"Vlr Aquisição "																,0,0,"R",1);
			    	$pdf->cell(20,$alt,db_formatar($aClassiImpressos[$class_anterior]["vlr_total"],'f'),0,0,"R",1);
			    	$pdf->cell(25,$alt,"Quantidade"																		,0,0,"R",1);
			    	$pdf->cell(20,$alt,$aClassiImpressos[$class_anterior]["qtd_total"],0,1,"C",1);
			    	$pdf->ln();
			    	$class_anterior = 0;
					}
					 	$pdf->setfont('arial','b',10);
						$pdf->cell(30,$alt,"Unidade"										,0,0,"L",0);
						$pdf->cell(30,$alt,$o41_unidade." - ".$o41_descr,0,1,"L",0);

				}
				$pdf->Ln(3);
				//orgao_cabecalho($pdf,$RLt52_descr,$RLt52_ident,$RLt52_depart,$alt);
				$iOrgao 	= $o40_orgao;
				$iUnidade	= $o41_unidade;
				//$p = 0;
	   	}

		if(array_key_exists($t52_depart,$aDeptosImpressos) && $quebra_por == 2){
			//imprimo os dados
			$aDeptosImpressos[$t52_depart]["vlr_total"] += $t52_valaqu;
			$aDeptosImpressos[$t52_depart]["qtd_total"] += 1;
		}else if ($quebra_por == 2) {
			if ($depto_anterior != $t52_depart && $depto_anterior != 0) {

				if (!isset($orgaos) || $limprime_total_depto == true) {
					$pdf->setfont('arial','b',8);
					$pdf->Ln();
		    	$pdf->cell(65,$alt,"Total da Divisão:"																								,0,0,"L",1);
		    	$pdf->cell(30,$alt,"Vlr Aquisição "																										,0,0,"R",1);
		    	$pdf->cell(20,$alt,db_formatar($aDivisaoImpressos[$divisao_anterior]["vlr_total"],'f'),0,0,"R",1);
		    	$pdf->cell(25,$alt,"Quantidade"																												,0,0,"R",1);
		    	$pdf->cell(20,$alt,$aDivisaoImpressos[$divisao_anterior]["qtd_total"]									,0,1,"C",1);
		    	$pdf->ln();
		    	$pdf->cell(65,$alt,"Total do Departamento:"																						,0,0,"L",1);
		    	$pdf->cell(30,$alt,"Vlr Aquisição "																										,0,0,"R",1);
		    	$pdf->cell(20,$alt,db_formatar($aDeptosImpressos[$depto_anterior]["vlr_total"],'f')		,0,0,"R",1);
		    	$pdf->cell(25,$alt,"Quantidade"																												,0,0,"R",1);
		    	$pdf->cell(20,$alt,$aDeptosImpressos[$depto_anterior]["qtd_total"]										,0,1,"C",1);
		    	$pdf->ln();
		    	$aDivisaoImpressos["semdivisao"]["vlr_total"] = 0;
	    		$aDivisaoImpressos["semdivisao"]["qtd_total"] = 0;
	    		$aDivisaoImpressos["semdivisao"]["descricao"] = "";
				}
				/*
	    	$aDivisaoImpressos["semdivisao"]["vlr_total"] = 0;
	    	$aDivisaoImpressos["semdivisao"]["qtd_total"] = 0;
	    	$aDivisaoImpressos["semdivisao"]["descricao"] = "";
	    	*/
			}
			$aDeptosImpressos[$t52_depart]["descricao"] = $descrdepto;
			$aDeptosImpressos[$t52_depart]["vlr_total"] = $t52_valaqu;
			$aDeptosImpressos[$t52_depart]["qtd_total"] = 1;
			$pdf->setfont('arial','b',8);
	    $pdf->cell(100,$alt,"Departamento:".$t52_depart." - ".$descrdepto,0,1,"L",0);
			$lCabecalho 	= true;
			$divisao_null = false;
			//$aDivisaoImpressos = array();
		}

		if($t33_divisao == "" || $t33_divisao == null){
			$t33_divisao = "semdivisao";
			$divisao_null = true;
		}

		$limprime_total_depto = true;

		if(array_key_exists($t33_divisao,$aDivisaoImpressos) && $quebra_por == 2){
			//imprimo os dados
			$aDivisaoImpressos[$t33_divisao]["vlr_total"] += $t52_valaqu;
			$aDivisaoImpressos[$t33_divisao]["qtd_total"] += 1;
		}else if($quebra_por == 2){
			if($divisao_anterior != $t33_divisao && $divisao_anterior != 0){
				$pdf->setfont('arial','b',8);
				$pdf->Ln();
	    	$pdf->cell(65,$alt,"Total da Divisão:"																									,0,0,"L",1);
	    	$pdf->cell(30,$alt,"Vlr Aquisição "																											,0,0,"R",1);
	    	$pdf->cell(20,$alt,db_formatar($aDivisaoImpressos[$divisao_anterior]["vlr_total"],'f')	,0,0,"R",1);
	    	$pdf->cell(25,$alt,"Quantidade"																													,0,0,"R",1);
	    	$pdf->cell(20,$alt,$aDivisaoImpressos[$divisao_anterior]["qtd_total"]										,0,1,"C",1);
	    	$pdf->ln();
			}
			$aDivisaoImpressos[$t33_divisao]["descricao"] = $t30_descr;
			$aDivisaoImpressos[$t33_divisao]["vlr_total"] = $t52_valaqu;
			$aDivisaoImpressos[$t33_divisao]["qtd_total"] = 1;
			$pdf->setfont('arial','b',8);
			$t33_divisao =  $divisao_null == true ? "" : $t33_divisao;
	    $pdf->cell(100,$alt,"Divisao:".$t33_divisao." - ".$t30_descr,0,1,"L",0);
	    $t33_divisao =  $divisao_null == true ? "semdivisao" : $t33_divisao;
			$lCabecalho = true;
		}

		if(array_key_exists($t64_class,$aClassiImpressos) && $quebra_por == 3) {
			//imprimo os dados
			$aClassiImpressos[$t64_class]["vlr_total"] += $t52_valaqu;
			$aClassiImpressos[$t64_class]["qtd_total"] += 1;
		}else if($quebra_por == 3){
			if($class_anterior != $t64_class && $class_anterior != 0){
				$pdf->setfont('arial','b',8);
				$pdf->Ln();
	    	$pdf->cell(65,$alt,"Total da Classificação:"																				,0,0,"L",1);
	    	$pdf->cell(30,$alt,"Vlr Aquisição "																									,0,0,"R",1);
	    	$pdf->cell(20,$alt,db_formatar($aClassiImpressos[$class_anterior]["vlr_total"],'f')	,0,0,"R",1);
	    	$pdf->cell(25,$alt,"Quantidade"																											,0,0,"R",1);
	    	$pdf->cell(20,$alt,$aClassiImpressos[$class_anterior]["qtd_total"]									,0,1,"C",1);
	    	$pdf->ln();
				if($quebra_por == 3 && isset($orgaos)){
			  	unset($aClassiImpressos[$class_anterior]);
			  }

			}
			$aClassiImpressos[$t64_class]["descricao"] = $descrdepto;
			$aClassiImpressos[$t64_class]["vlr_total"] = $t52_valaqu;
			$aClassiImpressos[$t64_class]["qtd_total"] = 1;
			$pdf->setfont('arial','b',8);
	    $pdf->cell(100,$alt,"Classificação: ".$t64_class." - ".$t64_descr,0,1,"L",0);
			$lCabecalho 	= true;

		}

	  if ($x==0 && $quebra_por == 3) {

	    if ($pdf->GetY() > $pdf->h - 30) {
        $pdf->AddPage('L');
      }

      $pdf->setfont('arial','b',8);
      $pdf->cell(100,$alt,"Classificação: ".$t64_class." - ".$t64_descr,0,1,"L",0);
    }

		if ($lCabecalho == true ) {

			if ($pdf->GetY() > $pdf->h - 30) {
				  $pdf->AddPage('L');
			}

			//Cabeçalho Inicio
			$pdf->setfont('arial','b',7);
	    $pdf->cell(15,$alt,"Código"						,1,0,"C",1);
	    $pdf->cell(65,$alt,"Descrição do Bem"	,1,0,"C",1);
	    $pdf->cell(20,$alt,"Vlr Aquisição"		,1,0,"C",1);
	    $pdf->cell(25,$alt,"Data Aquisição"		,1,0,"C",1);
	    $pdf->cell(20,$alt,"Placa"						,1,0,"C",1);
	    $pdf->cell(60,$alt,"Departamento"			,1,0,"C",1);
	    $pdf->cell(60,$alt,"Divisao"					,1,0,"C",1);
	    $pdf->cell(15,$alt,"Pl. Ident "				,1,1,"C",1);
	    //Imprime classificação
	    if($quebra_por == 2){
				$pdf->cell(20,$alt,"Classificação"							,1,0,"C",1);
	    	$pdf->cell(260,$alt,"Descrição da classificação",1,1,"C",1);
	    }
	    //Imprime fornecedor
			if ($flag_forn == true){
	      $pdf->cell(20,$alt,$RLt52_numcgm,1,0,"C",1);
	      $pdf->cell(100,$alt,$RLz01_nome	,1,0,"C",1);
	      $pdf->cell(160,$alt,$RLt52_obs	,1,1,"C",1);
	    }

			$pdf->ln(1);
	    $lCabecalho = false;
			//Cabeçalho Fim
			$depto_anterior 	= $t52_depart;
			$divisao_anterior = $t33_divisao;
			$class_anterior		= $t64_class;
		}

		//Imprime os dados
		$background = $background == 1 ? 0 : 1;

		$pdf->setfont('arial','',7);
	  $pdf->cell(15,$alt,$t52_bem											,0,0,"C",$background);
	  $pdf->cell(65,$alt,substr($t52_descr,0,50)			,0,0,"L",$background);
	  $pdf->cell(20,$alt,db_formatar($t52_valaqu,"f")	,0,0,"R",$background);
	  $pdf->cell(25,$alt,db_formatar($t52_dtaqu,"d")	,0,0,"C",$background);
	  $pdf->cell(20,$alt,$t52_ident										,0,0,"C",$background);
	  $pdf->cell(60,$alt,$t52_depart."-".substr($descrdepto,0,36)	,0,0,"L",$background);
	  $t33_divisao =  $divisao_null == true ? "" : $t33_divisao;
	  $pdf->cell(60,$alt,$t33_divisao."-".substr($t30_descr,0,28)	,0,0,"L",$background);
	  $placaIdentificacao = $totaletiquetas >= 1 ? "Sim" : "Não";
    $pdf->cell(15,$alt,$placaIdentificacao  ,0,1,"C",$p);

	  $fSoma += $t52_valaqu;
	  $iTotal += 1;
	  //Imprime classificação
	  if($quebra_por == 2){
	  	$pdf->cell(20,$alt,$t64_class			,0,0,"R",$background);
	  	$pdf->cell(26,$alt,$t64_descr			,0,1,"L",$background);
	  }
		if ($flag_forn == true){
	    $pdf->cell(20,$alt,$t52_numcgm		,0,0,"C",$background);
	    $pdf->cell(100,$alt,$z01_nome			,0,0,"L",$background);
	    $pdf->multicell(160,$alt,$t52_obs	,0,"L",$background);
	  }


		if ($pdf->GetY() > $pdf->h - 25) {

		  $pdf->AddPage('L');
		  $lCabecalho = true;
		}

	  if($x+1 == $numrows && $quebra_por == 2){
	  	if(isset($aDivisaoImpressos["semdivisao"])){
		  	$pdf->setfont('arial','b',8);
				$pdf->Ln();
				$pdf->cell(65,$alt,"Total da Divisão:",0,0,"L",1);
		    $pdf->cell(30,$alt,"Vlr Aquisição "		,0,0,"R",1);
		    $pdf->cell(20,$alt,db_formatar($aDivisaoImpressos["semdivisao"]["vlr_total"],'f'),0,0,"R",1);
		    $pdf->cell(25,$alt,"Quantidade"				,0,0,"R",1);
		    $pdf->cell(20,$alt,$aDivisaoImpressos["semdivisao"]["qtd_total"]								 ,0,1,"C",1);
	  	}else if (isset($aDivisaoImpressos[$divisao_anterior])){
	  		$pdf->setfont('arial','b',8);
				$pdf->Ln();
				$pdf->cell(65,$alt,"Total da Divisão:",0,0,"L",1);
		    $pdf->cell(30,$alt,"Vlr Aquisição "		,0,0,"R",1);
		    $pdf->cell(20,$alt,db_formatar($aDivisaoImpressos[$divisao_anterior]["vlr_total"],'f'),0,0,"R",1);
		    $pdf->cell(25,$alt,"Quantidade"				,0,0,"R",1);
		    $pdf->cell(20,$alt,$aDivisaoImpressos[$divisao_anterior]["qtd_total"]								 ,0,1,"C",1);
	  	}
	    $pdf->ln();
	    $pdf->cell(65,$alt,"Total do Departamento:",0,0,"L",1);
	    $pdf->cell(30,$alt,"Vlr Aquisição "		,0,0,"R",1);
	    $pdf->cell(20,$alt,db_formatar($aDeptosImpressos[$depto_anterior]["vlr_total"],'f'),0,0,"R",1);
	    $pdf->cell(25,$alt,"Quantidade"				,0,0,"R",1);
	    $pdf->cell(20,$alt,$aDeptosImpressos[$depto_anterior]["qtd_total"]								 ,0,1,"C",1);
	    $pdf->ln();

	    //$total 			= 0;
	    //$totalvalor = 0;
	    foreach ($aDeptosImpressos as $key=>$value){
	    	$total 			+= $aDeptosImpressos[$key]["qtd_total"];
	    	$totalvalor += $aDeptosImpressos[$key]["vlr_total"];
	    }

	  }

		if ($x+1 == $numrows && $quebra_por == 3){

		  	$pdf->setfont('arial','b',8);
				$pdf->Ln();
		    $pdf->cell(65,$alt,"Total da Classificação:",0,0,"L",1);
		    $pdf->cell(30,$alt,"Vlr Aquisição "		,0,0,"R",1);
		    $pdf->cell(20,$alt,db_formatar($aClassiImpressos[$class_anterior]["vlr_total"],'f'),0,0,"R",1);
		    $pdf->cell(25,$alt,"Quantidade"				,0,0,"R",1);
		    $pdf->cell(20,$alt,$aClassiImpressos[$class_anterior]["qtd_total"]								 ,0,1,"C",1);
		    $pdf->ln();
		 	if(!isset($orgaos)){

		    foreach ($aClassiImpressos as $key=>$value){
		    	$total 			+= $aClassiImpressos[$key]["qtd_total"];
		    	$totalvalor += $aClassiImpressos[$key]["vlr_total"];

		    }

			}else{
				$total 			= $iTotal;
		    $totalvalor = $fSoma;
			}

	  }
	 //$total++;
   //$totalvalor += $t52_valaqu;
	}
}

if (isset($imprime_total_parcial)){

  $pdf->cell(135,$alt,'VALOR TOTAL:  '.$valortotal,"T",0,"R",0);
  $pdf->cell(130,$alt,'TOTAL DE REGISTROS  :  '.$contaregistro,"T",1,"R",0);
}

$pdf->setfont('arial','b',8);
$pdf->Ln(1);
$pdf->cell(135,$alt,'VALOR TOTAL:'.db_formatar($totalvalor,"f"),"T",0,"R",0);
$pdf->cell(135,$alt,'TOTAL GERAL DE REGISTROS  :  '.$total,"T",1,"R",0);

$pdf->Output();

function orgao_cabecalho($pdf,$RLt52_descr,$RLt52_ident,$RLt52_depart,$alt){

  $pdf->setfont('arial','b',8);
  $pdf->cell(15,$alt,"Código"					,1,0,"C",1);
  $pdf->cell(65,$alt,$RLt52_descr			,1,0,"C",1);
  $pdf->cell(20,$alt,"Vlr Aquisição"	,1,0,"C",1);
  $pdf->cell(25,$alt,"Data Aquisição"	,1,0,"C",1);
  $pdf->cell(30,$alt,$RLt52_ident			,1,0,"C",1);
  $pdf->cell(60,$alt,$RLt52_depart		,1,0,"C",1);
  $pdf->cell(48,$alt,'Divisão'				,1,0,"C",1);
  $pdf->cell(15,$alt,"Pl. Ident "     ,1,1,"C",1);
}
?>
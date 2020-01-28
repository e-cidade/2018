<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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


include ("libs/db_liborcamento.php");
include ("libs/db_utils.php");
include ("fpdf151/pdf.php");
include("fpdf151/assinatura.php");
include ("libs/db_sql.php");

db_postmemory($HTTP_POST_VARS); 

$classinatura = new cl_assinatura;

//---------------------------------------------------------------  
$clselorcdotacao = new cl_selorcdotacao();
$clselorcdotacao->setDados($filtra_despesa); // passa os parametros vindos da func_selorcdotacao_abas.php
// $instits = $clselorcdotacao->getInstit();

$db_selinstit = $instituicao;

$instits =  str_replace('-',',',$db_selinstit);

$receita = 0;
//@ recupera as informações fornecidas para gerar os dados
$data_ini = "$data_ini_ano-$data_ini_mes-$data_ini_dia";
$data_fim = "$data_fim_ano-$data_fim_mes-$data_fim_dia";
if ($data_ini == "--")
    $data_ini = db_getsession("DB_anousu")."-01-01";
if ($data_fim == "--")
    $data_fim = date('Y-m-d', db_getsession("DB_datausu"));

$txt_modelo = "";
if ($processados == 1) {
    $txt_modelo = "PROCESSADOS";
} elseif ($processados == 2) {
    $txt_modelo = "NAO PROCESSADOS";
} elseif ($processados == 3) {
    $txt_modelo = "TODOS AS SUPLEMENTAÇÔES";
}

$head1 = "RELATORIO DE SUPLEMENTAÇÔES";
$head2 = "MODELO : $txt_modelo";
$head3 = "EXERCÍCIO: ".db_getsession("DB_anousu");
$head4 = "PERÍODO: ".db_formatar($data_ini, 'd')." a ".db_formatar($data_fim, 'd');
$resultinst = pg_exec("select codigo,nomeinst from db_config where codigo in ($instits)");
$descr_inst = '';
$xvirg = '';
for ($xins = 0; $xins < pg_numrows($resultinst); $xins ++) {
	db_fieldsmemory($resultinst, $xins);
	$descr_inst .= $xvirg.$nomeinst;
	$xvirg = ', ';
}
$head5 = "INSTITUIÇÕES : ".$descr_inst;

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
// $pdf->AddPage("L");
$pdf->SetFillColor(235);
$pdf->SetFont('Arial', '', 9);
$alt = 4;

$imprimecabec = false;

$array_totais = array();
$sWhere       = "" ;
for ($tiporel = 0; $tiporel <= 1; $tiporel++) {
  
  $wheretipo = ($tiporel == 0?"( o39_usalimite is true or o139_orcprojeto is not null)":" (o39_usalimite is false and o139_orcprojeto is null)") . " and ";

	/////////////////////////////////////////////////////////
	// $sele_work = $clselorcdotacao->getDados()." and w.o58_instit in $instits ";
	$sele_work = $clselorcdotacao->getDados(false);

	$sql = "select o47_codsup,
							 o49_data,
						 o45_numlei as lei,
						 o39_codproj,
						 o39_numero as decreto,
						 o46_tiposup,
						 o48_descr,
						 o47_coddot,
						 o58_orgao,
						 o47_anousu,
						 case when o47_valor>0 then
								o47_valor
						 end as suplementado,
						 case when o47_valor<0 then
								o47_valor *-1 
						 end as reduzido,
						 o58_codigo,
						 o39_usalimite,
						 o139_orcprojeto
				from orcsuplem 
							inner join orcsuplemtipo on o48_tiposup = orcsuplem.o46_tiposup
							inner join orcsuplemval on o47_codsup=o46_codsup 
							left outer join orcsuplemlan on o49_codsup = o47_codsup  
							inner join orcprojeto on o39_codproj = orcsuplem.o46_codlei
							left  join orcprojetoorcprojetolei on o39_codproj = o139_orcprojeto   
							inner join orclei on o45_codlei = orcprojeto.o39_codlei
							left  join orcsuplemretif on o48_retificado = orcprojeto.o39_codproj                   
							inner join orcdotacao on o58_coddot =orcsuplemval.o47_coddot 
																	 and o58_anousu=orcsuplemval.o47_anousu
													 and o58_instit in ($instits)
							inner join orcelemento on o58_codele = o56_codele and o56_anousu = o58_anousu
				 where $wheretipo o48_retificado is null and o46_tiposup in ($vTipos) and $sele_work 
						";
  $sWherePPA        = str_replace("o58_", "o08_", $sele_work);
  $sWherePPA        = str_replace("o08_codele", "o08_elemento", $sWherePPA); 				 
  $sWherePPA        = str_replace("o08_codigo", "o08_recurso", $sWherePPA); 				 
	$sSqlDotacoesPPA  = "select o46_codsup, ";
  $sSqlDotacoesPPA .= "       o49_data, ";
  $sSqlDotacoesPPA .= "       o45_numlei as lei, ";
  $sSqlDotacoesPPA .= "       o39_codproj, ";
  $sSqlDotacoesPPA .= "       o39_numero as decreto, ";
  $sSqlDotacoesPPA .= "       o46_tiposup, ";
  $sSqlDotacoesPPA .= "       o48_descr, ";
  $sSqlDotacoesPPA .= "       0 as o47_coddot, ";
  $sSqlDotacoesPPA .= "       o08_orgao, ";
  $sSqlDotacoesPPA .= "       o08_ano, ";
  $sSqlDotacoesPPA .= "       case when o136_valor>0 then ";
  $sSqlDotacoesPPA .= "                 o136_valor ";
  $sSqlDotacoesPPA .= "       end as suplementado, ";
  $sSqlDotacoesPPA .= "       case when o136_valor<0 then ";
  $sSqlDotacoesPPA .= "                 o136_valor *-1 "; 
  $sSqlDotacoesPPA .= "       end as reduzido, ";
  $sSqlDotacoesPPA .= "       o08_recurso, ";
  $sSqlDotacoesPPA .= "       o39_usalimite, ";
  $sSqlDotacoesPPA .= "       o139_orcprojeto ";
  $sSqlDotacoesPPA .= "  from orcsuplem  ";
  $sSqlDotacoesPPA .= "       inner join orcsuplemtipo        on o48_tiposup = orcsuplem.o46_tiposup ";
  $sSqlDotacoesPPA .= "       inner join orcsuplemdespesappa  on o136_orcsuplem = o46_codsup  ";
  $sSqlDotacoesPPA .= "       left outer join orcsuplemlan    on o49_codsup     = o46_codsup   ";
  $sSqlDotacoesPPA .= "       inner join orcprojeto           on o39_codproj    = orcsuplem.o46_codlei    ";
  $sSqlDotacoesPPA .= "       inner join orclei               on o45_codlei     = orcprojeto.o39_codlei ";
  $sSqlDotacoesPPA .= "       left  join orcsuplemretif       on o48_retificado = orcprojeto.o39_codproj ";                   
  $sSqlDotacoesPPA .= "       inner join ppaestimativadespesa on o136_ppaestimativadespesa = o07_sequencial "; 
  $sSqlDotacoesPPA .= "       left  join orcprojetoorcprojetolei on o39_codproj = o139_orcprojeto ";
  $sSqlDotacoesPPA .= "       inner join ppadotacao           on o08_sequencial     = o07_coddot "; 
  $sSqlDotacoesPPA .= "                                      and o08_instit in ($instits) ";
  $sSqlDotacoesPPA .= "       inner join orcelemento          on o08_elemento = o56_codele  ";
  $sSqlDotacoesPPA .= "                                      and o08_ano      = o56_anousu "; 
  $sSqlDotacoesPPA .= " where {$wheretipo} o48_retificado is null "; 
  $sSqlDotacoesPPA .= "   and o46_tiposup in ({$vTipos}) "; 
  $sSqlDotacoesPPA .= "   and {$sWherePPA} "; 	
  
	if ($processados == 1) { // so processador
		if (isset ($data_ini))
			$sWhere .= " and orcsuplemlan.o49_data >= '$data_ini' ";
		if (isset ($data_fim))
			$sWhere .= " and orcsuplemlan.o49_data <= '$data_fim' ";
		$sWhere .= " and o49_codsup is not null  ";
	} elseif ($processados == 2) { // não processados  
		if (isset ($data_ini))
			$sWhere .= " and orcsuplem.o46_data >= '$data_ini' ";
		if (isset ($data_fim))
			$sWhere .= " and orcsuplem.o46_data <= '$data_fim' ";
		$sWhere .= " and o49_codsup is null  ";
	} else {
		// todos
		if (isset ($data_ini))
			$sWhere .= " and orcsuplem.o46_data >= '$data_ini' ";
		if (isset ($data_fim))
			$sWhere .= " and orcsuplem.o46_data <= '$data_fim' ";
	}
	// filtro de tipo 
	if ($tipo == 'decreto') {
		$sWhere .= "  and orcprojeto.o39_tipoproj=1  ";
	}
	elseif ($tipo == 'lei') {
		$sWhere .= "  and orcprojeto.o39_tipoproj=2  ";
	}
	
	$sql .= $sWhere;
  $sSqlDotacoesPPA .= $sWhere;
  $sSqlDotacoesPPA .= " order by 1, 4, 7";
	//--//
	$sSqlSuplementacoes = "{$sql} union all {$sSqlDotacoesPPA}";
	$res = pg_exec($sSqlSuplementacoes) or die($sSqlSuplementacoes);
	if (pg_numrows($res) == 0) {
		continue;
//		db_redireciona('db_erros.php?db_erro=Sem projetos neste periodo&fechar=true');
	}

	//echo $sql;
	//db_criatabela($res);
	//exit;

	$rows = pg_numrows($res);
	$pagina              = 1;
	$tot_sup             = 0;
	$tot_red             = 0;
	$tot_rec             = 0;
	$nTotalUtilizado     = 0;
	$codsup_suplementado = 0;
	$codsup_reduzido     = 0;
	$codsup_receita      = 0;

	if (pg_numrows($res)>0){
		db_fieldsmemory($res, 0);
		$codsup = $o47_codsup;
	}  
	$dotant = "";

	for ($i = 0; $i < $rows; $i ++) {
		db_fieldsmemory($res, $i);

		if ($pagina == 1 || $pdf->getY() > 170 or $imprimecabec == true) {
			$pagina = 0;
			if ($imprimecabec == false) {
				$pdf->AddPage("L");
			}
			$imprimecabec = false;
			$pdf->setX(3);
			$pdf->Cell(288,$alt, ($tiporel == 0?"CRÉDITOS ADICIONAIS":"REMANEJAMENTOS AUTORIZADOS PELA LOA"), 1, 1, "C", '1');
			$pdf->setX(3);
			$pdf->Cell(15, $alt, "SUPL.", 0, 0, "C", '0');
			$pdf->Cell(15, $alt, "DT.PROC", 0, 0, "C", '0');
			$pdf->Cell(15, $alt, "PROJ.", 0, 0, "C", '0');
			$pdf->Cell(62, $alt, "LEI", 0, 0, "C", '0');
			$pdf->Cell(16, $alt, "DECRETO", 0, 0, "C", '0');
			$pdf->Cell(70, $alt, "TIPO", 0, 0, "C", '0');
			$pdf->Cell(20, $alt, "DOTACAO", 0, 0, "C", '0');
			$pdf->Cell(20, $alt, "RECURSO", 0, 0, "C", '0');
			$pdf->Cell(25, $alt, "SUPLEMENTADO", 0, 0, "R", '0');
			$pdf->Cell(25, $alt, "REDUZIDO", 0, 1, "R", '0');
		}
		if ($codsup != $o47_codsup) {

				$sSqlReceitasOrcamento  = "select  o85_codsup,";
				$sSqlReceitasOrcamento .= "        o85_codrec,";
				$sSqlReceitasOrcamento .= "        o85_anousu, ";
				$sSqlReceitasOrcamento .= "        o85_valor ,";
				$sSqlReceitasOrcamento .= "        o57_descr ";
				$sSqlReceitasOrcamento .= "  from orcsuplemrec ";
				$sSqlReceitasOrcamento .= "       inner join orcsuplem  on o46_codsup  = o85_codsup ";
        $sSqlReceitasOrcamento .= "   	  inner join orcreceita on o70_anousu = o85_anousu ";
        $sSqlReceitasOrcamento .= "                            and o70_codrec = o85_codrec ";
        $sSqlReceitasOrcamento .= "                            and o70_instit in ($instits) ";
        $sSqlReceitasOrcamento .= " 		 inner join orcfontes   on o57_anousu = o70_anousu ";
        $sSqlReceitasOrcamento .= "                            and o57_codfon = o70_codfon ";
				$sSqlReceitasOrcamento .= " where o46_tiposup in ($vTipos) ";
				$sSqlReceitasOrcamento .= "   and o85_codsup = $codsup ";
							 
				 $sSqlReceitasPPA  = "select  o137_orcsuplem, "; 
				 $sSqlReceitasPPA .= "        0 as o85_codrec,";
				 $sSqlReceitasPPA .= "        o06_anousu,";
				 $sSqlReceitasPPA .= "        o137_valor, ";
				 $sSqlReceitasPPA .= "        o57_descr ";
         $sSqlReceitasPPA .= "   from orcsuplemreceitappa ";
         $sSqlReceitasPPA .= "        inner join orcsuplem            on o137_orcsuplem = o46_codsup";
         $sSqlReceitasPPA .= "        inner join ppaestimativareceita on o137_ppaestimativareceita = o06_sequencial";
         $sSqlReceitasPPA .= "        inner join orcfontes            on o57_codfon  = o06_codrec ";
         $sSqlReceitasPPA .= "                                       and o57_anousu = o06_anousu  ";
         $sSqlReceitasPPA .= "        inner join conplanoreduz       on o57_codfon  = c61_codcon ";
         $sSqlReceitasPPA .= "                                       and o57_anousu = c61_anousu  ";                     
         $sSqlReceitasPPA .= "        inner join orcsuplemtipo on o48_tiposup = orcsuplem.o46_tiposup ";
         $sSqlReceitasPPA .= "  where o46_tiposup in ($vTipos) and o137_orcsuplem = $codsup ";
         $sSqlReceitas = "{$sSqlReceitasOrcamento} union all {$sSqlReceitasPPA} ";
         
    		 $ress = pg_exec(analiseQueryPlanoOrcamento($sSqlReceitas));
			   //$codsup_reduzido = 0;
				 for ($xx = 0; $xx < pg_num_rows($ress); $xx++) {

				   $oReceita = db_utils::fieldsMemory($ress, $xx);
					 $pdf->setX(3);	   
					 $pdf->Cell(18, $alt, "", 0, 0, "R", '0');
					 $pdf->Cell(50, $alt, "Receita", 0, 0, "L", '0');
					 $pdf->Cell(90, $alt, "$oReceita->o57_descr", 0, 0, "L", '0');
					 $pdf->Cell(20, $alt, "$oReceita->o85_codrec", 0, 0, "C", '0');
					 $pdf->Cell(25, $alt, '', 0, 0, "R", '0');
					 $pdf->Cell(25, $alt, db_formatar($oReceita->o85_valor, 'f'), 0, 1, "R", '0');
			
					 $codsup_reduzido += $oReceita->o85_valor;
					 $tot_rec += $oReceita->o85_valor;
			 
			 }	  
			 $pdf->setX(3);
			 $pdf->Cell(15, $alt, "TOTAL", "TB", 0, "R", '0');
			 $pdf->Cell(15, $alt, "", "TB", 0, "C", '0');
			 $pdf->Cell(15, $alt, "", "TB", 0, "R", '0');
			 $pdf->Cell(62, $alt, "", "TB", 0, "L", 'L');
			 $pdf->Cell(16, $alt, "", "TB", 0, "L", 'L');
			 $pdf->Cell(70, $alt, "", "TB", 0, "L", '0');
			 $pdf->Cell(20, $alt, "", "TB", 0, "C", '0');
			 $pdf->Cell(20, $alt, "", "TB", 0, "C", '0');
			 $pdf->Cell(25, $alt, db_formatar($codsup_suplementado, 'f'), "TB", 0, "R", '0');
			 $pdf->Cell(25, $alt, db_formatar($codsup_reduzido, 'f'), "TB", 1, "R", '0');
			 $pdf->Ln();
			 $codsup = $o47_codsup;
			 
			 $codsup_suplementado = 0;
			 $codsup_reduzido = 0;
			 $codsup_receita = 0;

					 
		}
		$pdf->setX(3);
		$pdf->Cell(15, $alt, "$o47_codsup", 0, 0, "C", '0');
		$pdf->Cell(15, $alt, db_formatar($o49_data, "d"), 0, 0, "C", '0');
		$pdf->Cell(15, $alt, "$o39_codproj", 0, 0, "C", '0');
		$pdf->Cell(62, $alt, "$lei", 0, 0, "L", 'L');
		$pdf->Cell(16, $alt, substr("$decreto",0,8), 0, 0, "C", 'L');
		$pdf->Cell(70, $alt, substr($o48_descr, 0, 37), 0, 0, "L", '0');
		$pdf->Cell(20, $alt, "$o47_coddot", 0, 0, "C", '0');
		$pdf->Cell(20, $alt, "$o58_codigo", 0, 0, "C", '0');
		$pdf->Cell(25, $alt, db_formatar($suplementado, 'f'), 0, 0, "R", '0');
		$pdf->Cell(25, $alt, db_formatar($reduzido, 'f'), 0, 1, "R", '0');
		$codsup_suplementado += $suplementado;
		$codsup_reduzido += $reduzido;
		$codsup_receita += $receita;		
		$tot_sup += $suplementado;
		$tot_red += $reduzido;
    if ($tiporel == 0 && $o39_usalimite == 't') {
      $nTotalUtilizado += $suplementado;
    }
    if (!(isset($array_totais[$o48_descr][0]))) {
      $array_totais[$o48_descr][0] = 0;
    }

    if (!(isset($array_totais[$o48_descr][1]))) {
      $array_totais[$o48_descr][1] = 0;
    }
   
    $array_totais[$o48_descr][0] += $suplementado;
    $array_totais[$o48_descr][1] += $reduzido;
    

	}

	$pdf->setX(3);
					
  $sSqlReceitasOrcamento  = "select  o85_codsup,";
  $sSqlReceitasOrcamento .= "        o85_codrec,";
  $sSqlReceitasOrcamento .= "        o85_anousu, ";
  $sSqlReceitasOrcamento .= "        o85_valor ,";
  $sSqlReceitasOrcamento .= "        o57_descr ";
  $sSqlReceitasOrcamento .= "  from orcsuplemrec ";
  $sSqlReceitasOrcamento .= "       inner join orcsuplem  on o46_codsup  = o85_codsup ";
  $sSqlReceitasOrcamento .= "       inner join orcreceita on o70_anousu = o85_anousu ";
  $sSqlReceitasOrcamento .= "                            and o70_codrec = o85_codrec ";
  $sSqlReceitasOrcamento .= "                            and o70_instit in ($instits) ";
  $sSqlReceitasOrcamento .= "      inner join orcfontes   on o57_anousu = o70_anousu ";
  $sSqlReceitasOrcamento .= "                            and o57_codfon = o70_codfon ";
  $sSqlReceitasOrcamento .= " where o46_tiposup in ($vTipos) ";
  $sSqlReceitasOrcamento .= "   and o85_codsup = $codsup ";
         
  $sSqlReceitasPPA  = "select  o137_orcsuplem, "; 
  $sSqlReceitasPPA .= "        0 as o85_codrec,";
  $sSqlReceitasPPA .= "        o06_anousu,";
  $sSqlReceitasPPA .= "        o137_valor, ";
  $sSqlReceitasPPA .= "        o57_descr ";
  $sSqlReceitasPPA .= "   from orcsuplemreceitappa ";
  $sSqlReceitasPPA .= "        inner join orcsuplem            on o137_orcsuplem = o46_codsup";
  $sSqlReceitasPPA .= "        inner join ppaestimativareceita on o137_ppaestimativareceita = o06_sequencial";
  $sSqlReceitasPPA .= "        inner join orcfontes            on o57_codfon  = o06_codrec ";
  $sSqlReceitasPPA .= "                                       and o57_anousu = o06_anousu  ";
  $sSqlReceitasPPA .= "        inner join conplanoreduz       on o57_codfon  = c61_codcon ";
  $sSqlReceitasPPA .= "                                       and o57_anousu = c61_anousu  ";                     
  $sSqlReceitasPPA .= "        inner join orcsuplemtipo on o48_tiposup = orcsuplem.o46_tiposup ";
  $sSqlReceitasPPA .= "  where o46_tiposup in ($vTipos) and o137_orcsuplem = $codsup ";
  $sSqlReceitas = "{$sSqlReceitasOrcamento} union all {$sSqlReceitasPPA} ";
  
	$ress = pg_exec($sSqlReceitas);
	for ($xx = 0; $xx < pg_num_rows($ress); $xx++) {
		 
	  $oReceita = db_utils::fieldsMemory($ress,$xx);
		$pdf->setX(3);
 	  $pdf->Cell(18, $alt, "", 0, 0, "R", '0');
	  $pdf->Cell(50, $alt, "Receita", 0, 0, "L", '0');
		$pdf->Cell(90, $alt, "$oReceita->o57_descr", 0, 0, "L", '0');
		$pdf->Cell(20, $alt, "$oReceita->o85_codrec", 0, 0, "C", '0');
		$pdf->Cell(25, $alt, '', 0, 0, "R", '0');
		$pdf->Cell(25, $alt, db_formatar($oReceita->o85_valor, 'f'), 0, 1, "R", '0');
			
		$codsup_reduzido += $oReceita->o85_valor;
		$tot_rec         += $oReceita->o85_valor;

	}

	$pdf->setX(3);
	$pdf->Cell(15, $alt, "TOTAL", "TB", 0, "R", '0');
	$pdf->Cell(15, $alt, "", "TB", 0, "C", '0');
	$pdf->Cell(15, $alt, "", "TB", 0, "R", '0');
	$pdf->Cell(62, $alt, "", "TB", 0, "L", 'L');
	$pdf->Cell(16, $alt, "", "TB", 0, "L", 'L');
	$pdf->Cell(70, $alt, "", "TB", 0, "L", '0');
	$pdf->Cell(20, $alt, "", "TB", 0, "C", '0');
	$pdf->Cell(20, $alt, "", "TB", 0, "C", '0');
	$pdf->Cell(25, $alt, db_formatar($codsup_suplementado, 'f'), "TB", 0, "R", '0');
	$pdf->Cell(25, $alt, db_formatar($codsup_reduzido, 'f'), "TB", 1, "R", '0');
	//$pdf->Cell(30, $alt, db_formatar($codsup_receita, 'f'), "TB", 1, "R", '0');



	$pdf->Ln(2);
	$pdf->setX(3);
	$pdf->Cell(15, $alt, "GERAL", "TB", 0, "R", '0');
	$pdf->Cell(15, $alt, "", "TB", 0, "C", '0');
	$pdf->Cell(15, $alt, "", "TB", 0, "R", '0');
	$pdf->Cell(62, $alt, "", "TB", 0, "L", 'L');
	$pdf->Cell(16, $alt, "", "TB", 0, "L", 'L');
	$pdf->Cell(70, $alt, "", "TB", 0, "L", '0');
	$pdf->Cell(20, $alt, "", "TB", 0, "C", '0');
	$pdf->Cell(20, $alt, "", "TB", 0, "C", '0');
	$pdf->Cell(25, $alt, db_formatar($tot_sup, 'f'), "TB", 0, "R", '0');
	$pdf->Cell(25, $alt, db_formatar($tot_red, 'f'), "TB", 1, "R", '0');
	$pdf->Ln(5);
	$pdf->setX(3);
	$sTexto = "Total utilizado no limite autorizado pela LOA para créditos suplementares: ";
	$pdf->Cell(150, $alt, "Total da previsão adicional da receita: ".trim(db_formatar($tot_rec, 'f')), 0, 1, "L");
	$pdf->setX(3);
	if ($tiporel == 0) { 
  	$pdf->Cell(150, $alt, $sTexto.trim(db_formatar($nTotalUtilizado, 'f')), 0, 1, "L");
	}





	$pdf->ln(5);

	// -----------------------------------------------------------------------------------

	$imprimecabec = true;

}



// RETIFICACOES




$sql = "select o47_codsup,
	           o49_data,
		       o39_codproj,
               o45_numlei as lei,
	    	   o39_numero as decreto,
		       o46_tiposup,
		       o48_descr,
		       o47_coddot,
		       o58_orgao,
		       o47_anousu,
		       case when o47_valor>0 then
		          o47_valor
		       end as suplementado,
		       case when o47_valor<0 then
		          o47_valor *-1 
		       end as reduzido,
		       o58_codigo
      from orcsuplem                  
	          inner join orcsuplemtipo on o48_tiposup = orcsuplem.o46_tiposup
	          inner join orcsuplemval on o47_codsup=o46_codsup 
	          left outer join orcsuplemlan on o49_codsup = o47_codsup  
		      inner join orcprojeto on o39_codproj = orcsuplem.o46_codlei   
              inner join orclei on o45_codlei = orcprojeto.o39_codlei
	          inner join orcsuplemretif on o48_retificado = orcprojeto.o39_codproj 
  		      inner join orcdotacao on o58_coddot =orcsuplemval.o47_coddot 
	                                 and o58_anousu=orcsuplemval.o47_anousu
			           		         and o58_instit in ($instits)
	          inner join orcelemento on o58_codele = o56_codele and o56_anousu = o58_anousu
      where o46_tiposup in ($vTipos) and $sele_work
      ";
if ($processados == 1) { // so processador
	if (isset ($data_ini))
		$sql .= " and orcsuplemlan.o49_data >= '$data_ini' ";
	if (isset ($data_fim))
		$sql .= " and orcsuplemlan.o49_data <= '$data_fim' ";
	$sql .= " and o49_codsup is not null  ";
} elseif ($processados == 2) { // não processados  
	if (isset ($data_ini))
		$sql .= " and orcsuplem.o46_data >= '$data_ini' ";
	if (isset ($data_fim))
		$sql .= " and orcsuplem.o46_data <= '$data_fim' ";
	$sql .= " and o49_codsup is null  ";
} else {
	// todos
	if (isset ($data_ini))
		$sql .= " and orcsuplem.o46_data >= '$data_ini' ";
	if (isset ($data_fim))
		$sql .= " and orcsuplem.o46_data <= '$data_fim' ";
}
// filtro de tipo 
if ($tipo == 'decreto') {
	$sql .= "  and orcprojeto.o39_tipoproj=1  ";
}
elseif ($tipo == 'lei') {
	$sql .= "  and orcprojeto.o39_tipoproj=2  ";
}
$sql .= " order by orcprojeto.o39_codproj, o47_codsup ,o47_coddot";

//--//
$res = pg_exec($sql) or die($sql);

$rows = pg_numrows($res);
$pagina  = 0;
$tot_sup = 0;
$tot_red = 0;
$tot_rec = 0;
$codsup_suplementado = 0;
$codsup_reduzido = 0;
$codsup_receita = 0;

if ($rows > 0 ) {
  db_fieldsmemory($res, 0);
  $codsup = $o47_codsup;

	$dotant = "";

	$pdf->setX(3);
	$pdf->Cell(258, $alt, "RETIFICAÇÕES", 1, 1, "C", '1');
	$pdf->setX(3);
	$pdf->Cell(18, $alt, "COD.SUP", 0, 0, "R", '0');
	$pdf->Cell(20, $alt, "DT.PROC", 0, 0, "C", '0');
	$pdf->Cell(20, $alt, "PROJETO", 0, 0, "R", '0');
	$pdf->Cell(30, $alt, "LEI", 0, 0, "L", '0');
	$pdf->Cell(30, $alt, "DECRETO", 0, 0, "L", '0');
	$pdf->Cell(70, $alt, "TIPO", 0, 0, "L", '0');
	$pdf->Cell(20, $alt, "DOTACAO", 0, 0, "C", '0');
	$pdf->Cell(20, $alt, "RECURSO", 0, 0, "C", '0');
	$pdf->Cell(25, $alt, "SUPLEMENTADO", 0, 0, "R", '0');
	$pdf->Cell(25, $alt, "REDUZIDO", 0, 1, "R", '0');

	for ($i = 0; $i < $rows; $i ++) {
		db_fieldsmemory($res, $i);

		if ($pagina == 1 || $pdf->getY() > 170) {
			$pagina = 0;
			$pdf->AddPage("L");
			$pdf->setX(3);
			$pdf->Cell(258, $alt, "RETIFICAÇÕES", 1, 1, "C", '1');
			$pdf->setX(3);
			$pdf->Cell(18, $alt, "COD.SUP", 0, 0, "R", '0');
			$pdf->Cell(20, $alt, "DT.PROC", 0, 0, "C", '0');
			$pdf->Cell(20, $alt, "PROJETO", 0, 0, "R", '0');
			$pdf->Cell(30, $alt, "LEI", 0, 0, "L", '0');
			$pdf->Cell(30, $alt, "DECRETO", 0, 0, "L", '0');
			$pdf->Cell(70, $alt, "TIPO", 0, 0, "L", '0');
			$pdf->Cell(20, $alt, "DOTACAO", 0, 0, "C", '0');
			$pdf->Cell(20, $alt, "RECURSO", 0, 0, "C", '0');
			$pdf->Cell(25, $alt, "SUPLEMENTADO", 0, 0, "R", '0');
			$pdf->Cell(25, $alt, "REDUZIDO", 0, 1, "R", '0');
		}
		if ($codsup != $o47_codsup) {

				 $sql = "select  o85_codsup,o85_codrec,o85_anousu,o85_valor ,o57_descr
							 from orcsuplemrec 
									 inner join orcsuplem on o46_codsup = o85_codsup
											 inner join orcprojeto on o39_codproj = orcsuplem.o46_codlei   
											 inner join orcsuplemretif on o48_retificado = orcprojeto.o39_codproj 
									 inner join orcreceita on o70_anousu = o85_anousu and o70_codrec = o85_codrec and	o70_instit in ($instits)
									 inner join orcfontes  on o57_anousu = o70_anousu and o57_codfon = o70_codfon
						 where o46_tiposup in ($vTipos) and o85_codsup = $codsup";
				 $ress = pg_exec($sql);
			
			 for($xx=0;$xx<pg_numrows($ress);$xx++){
						 db_fieldsmemory($ress,$xx);
						 
							 $pdf->setX(3);	   
						 $pdf->Cell(18, $alt, "", 0, 0, "R", '0');
						 $pdf->Cell(50, $alt, "Receita", 0, 0, "L", '0');
					 $pdf->Cell(90, $alt, "$o57_descr", 0, 0, "L", '0');
					 $pdf->Cell(20, $alt, "$o85_codrec", 0, 0, "C", '0');
					 $pdf->Cell(25, $alt, '', 0, 0, "R", '0');
					 $pdf->Cell(25, $alt, db_formatar($o85_valor, 'f'), 0, 1, "R", '0');
		
					 $codsup_reduzido += $o85_valor;
					 $tot_rec += $o85_valor;
			 
			 }	  
			 $pdf->setX(3);
			 $pdf->Cell(18, $alt, "TOTAL", "TB", 0, "R", '0');
			 $pdf->Cell(20, $alt, "", "TB", 0, "C", '0');
			 $pdf->Cell(20, $alt, "", "TB", 0, "R", '0');
			 $pdf->Cell(30, $alt, "", "TB", 0, "L", 'L');
			 $pdf->Cell(30, $alt, "", "TB", 0, "L", 'L');
			 $pdf->Cell(70, $alt, "", "TB", 0, "L", '0');
			 $pdf->Cell(20, $alt, "", "TB", 0, "C", '0');
			 $pdf->Cell(20, $alt, "", "TB", 0, "C", '0');
			 $pdf->Cell(25, $alt, db_formatar($codsup_suplementado, 'f'), "TB", 0, "R", '0');
			 $pdf->Cell(25, $alt, db_formatar($codsup_reduzido, 'f'), "TB", 1, "R", '0');
			 $pdf->Ln();
			 $codsup = $o47_codsup;
			 
			 $codsup_suplementado = 0;
			 $codsup_reduzido = 0;
			 $codsup_receita = 0;

					 
		}
		$pdf->setX(3);
		$pdf->Cell(18, $alt, "$o47_codsup", 0, 0, "C", '0');
		$pdf->Cell(20, $alt, db_formatar($o49_data, "d"), 0, 0, "C", '0');
		$pdf->Cell(20, $alt, "$o39_codproj", 0, 0, "C", '0');
		$pdf->Cell(30, $alt, "$lei", 0, 0, "L", 'L');
		$pdf->Cell(30, $alt, "$decreto", 0, 0, "L", 'L');
		$pdf->Cell(70, $alt, substr($o48_descr, 0, 37), 0, 0, "L", '0');
		$pdf->Cell(20, $alt, "$o47_coddot", 0, 0, "C", '0');
		$pdf->Cell(20, $alt, "$o58_codigo", 0, 0, "C", '0');
		$pdf->Cell(25, $alt, db_formatar($suplementado, 'f'), 0, 0, "R", '0');
		$pdf->Cell(25, $alt, db_formatar($reduzido, 'f'), 0, 1, "R", '0');
		$codsup_suplementado += $suplementado;
		$codsup_reduzido += $reduzido;
		$codsup_receita += $receita;		
		$tot_sup += $suplementado;
			$tot_red += $reduzido;

      if (!(isset($array_totais[$o48_descr][0]))) {
        $array_totais[$o48_descr][0] = 0;
      }

      if (!(isset($array_totais[$o48_descr][1]))) {
        $array_totais[$o48_descr][1] = 0;
      }
     
      $array_totais[$o48_descr][0] += $suplementado;
      $array_totais[$o48_descr][1] += $reduzido;


	}

	$pdf->setX(3);
		 $sql = "select  o85_codsup,o85_codrec,o85_anousu,o85_valor ,o57_descr
			from orcsuplemrec 

					 inner join orcsuplem on o46_codsup = o85_codsup
								 inner join orcprojeto on o39_codproj = orcsuplem.o46_codlei   
								 inner join orcsuplemretif on o48_retificado = orcprojeto.o39_codproj 
			
					 inner join orcreceita on o70_anousu = o85_anousu and 
																		o70_codrec = o85_codrec and
						o70_instit in ($instits)	       
					 inner join orcfontes  on o57_anousu = o70_anousu and o57_codfon = o70_codfon
						 where o46_tiposup in ($vTipos) and o85_codsup = $codsup";
						 $ress = pg_exec($sql);
		
			 for($xx=0;$xx<pg_numrows($ress);$xx++){
				 db_fieldsmemory($ress,$xx);
							 $pdf->setX(3);
				 
				
						 $pdf->Cell(18, $alt, "", 0, 0, "R", '0');
				 $pdf->Cell(50, $alt, "Receita", 0, 0, "L", '0');
				 $pdf->Cell(90, $alt, "$o57_descr", 0, 0, "L", '0');
				 $pdf->Cell(20, $alt, "$o85_codrec", 0, 0, "C", '0');
				 $pdf->Cell(25, $alt, '', 0, 0, "R", '0');
				 $pdf->Cell(25, $alt, db_formatar($o85_valor, 'f'), 0, 1, "R", '0');
		
				 $codsup_reduzido += $o85_valor;
					 $tot_rec += $o85_valor;

	}
	$pdf->setX(3);
	$pdf->Cell(18, $alt, "TOTAL ", "TB", 0, "R", '0');
	$pdf->Cell(20, $alt, "", "TB", 0, "C", '0');
	$pdf->Cell(20, $alt, "", "TB", 0, "R", '0');
	$pdf->Cell(30, $alt, "", "TB", 0, "L", 'L');
	$pdf->Cell(30, $alt, "", "TB", 0, "L", 'L');
	$pdf->Cell(70, $alt, "", "TB", 0, "L", '0');
	$pdf->Cell(20, $alt, "", "TB", 0, "C", '0');
	$pdf->Cell(20, $alt, "", "TB", 0, "C", '0');
	$pdf->Cell(25, $alt, db_formatar($codsup_suplementado, 'f'), "TB", 0, "R", '0');
	$pdf->Cell(25, $alt, db_formatar($codsup_reduzido, 'f'), "TB", 1, "R", '0');

}

$pdf->SetFont('Arial', 'b', 9);

$pdf->Cell(180,10, "T O T A L    P O R    T I P O    D E    S U P L E M E N T A Ç Ã O", 1, 1, "C", '1');
$pdf->ln(5);

$pdf->Cell(80, $alt, "DESCRICAO", 0, 0, "L", '0');
$pdf->Cell(50, $alt, "SUPLEMENTADO", 0, 0, "R", '0');
$pdf->Cell(50, $alt, "REDUZIDO", 0, 0, "R", '0');
$pdf->ln();
$pdf->SetFont('Arial', '', 9);

$total_sup = 0;
$total_red = 0;

foreach($array_totais as $a => $b) {

  $pdf->Cell(80, $alt, $a, 0, 0, "L", '0');
  $pdf->Cell(50, $alt, db_formatar($b[0], 'f'), 0, 0, "R", '0');
  $pdf->Cell(50, $alt, db_formatar($b[1], 'f'), 0, 0, "R", '0');
  $total_sup += $b[0];
  $total_red += $b[1];
  $pdf->ln();

}

$pdf->SetFont('Arial', 'b', 9);
$pdf->Cell(80, $alt, "", 0, 0, "L", '0');
$pdf->Cell(50, $alt, db_formatar($total_sup, 'f'), 0, 0, "R", '0');
$pdf->Cell(50, $alt, db_formatar($total_red, 'f'), 0, 0, "R", '0');
$pdf->SetFont('Arial', '', 9);

//-- imprime parametros
if ($imprime_filtro == 's') {
	if (($pdf->getY() + 44) > 170) {
		$pdf->AddPage("L");
	} else {
		$pdf->setY(130);
	}
	$pdf->Ln(10);
	$parametros = $clselorcdotacao->getParametros();
	$pdf->multicell(270, $alt, $parametros, 1, 1, "R", '0');
}
if ($imprime_filtro == 'n') {
    if (($pdf->getY() + 44) > 170)  {
          $pdf->AddPage("L");
  }

};
// --------------------------
$tes =  "______________________________"."\n"."Tesoureiro";
$sec =  "______________________________"."\n"."Secretaria da Fazenda";
$cont =  "______________________________"."\n"."Contador";
$pref =  "______________________________"."\n"."Prefeito";
$ass_pref = $classinatura->assinatura(1000,$pref);
//$ass_pref = $classinatura->assinatura_usuario();
$ass_sec  = $classinatura->assinatura(1002,$sec);
$ass_tes  = $classinatura->assinatura(1004,$tes);
$ass_cont = $classinatura->assinatura(1005,$cont);
//echo $ass_pref;
$largura = ( $pdf->w ) / 2;
$pdf->ln(10);
$pos = $pdf->gety();


$pdf->multicell($largura,4,$ass_pref,0,"C",0,0);
$pdf->setxy($largura,$pos);
$pdf->multicell($largura,4,ucwords($ass_sec),0,"C",0,0);
$pdf->Ln(10);
$pos = $pdf->gety();
$pdf->multicell($largura,4,$ass_cont,0,"C",0,0);
$pdf->setxy($largura,$pos);
// $pdf->multicell($largura,2,$ass_controle,0,"C",0,0);

// --------------------------

$pdf->output();

?>
<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

include("fpdf151/pdf.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$pdf = new PDF("L"); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(220);

$borda        = 1; 
$bordat       = 1;
$preenc       = 0;
$pre		  = 0;
$TPagina      = 57;

$V_quantter   = 0;
$V_quantpre   = 0;
$valorter     = 0;
$valorpre     = 0;
$vlrvenalter  = 0;
$vlrvenalpre  = 0;
$vlrvenalpred = 0;
$quanttaxa    = 0;
$quant        = 0;
$valor        = 0;

$sql  = " select j23_tipoim, ";
$sql .= "        round(sum(vlrvenalter),2) as vlrvenalter, ";
$sql .= "        round(sum(vlrvenalpre),2) as vlrvenalpre, ";
$sql .= "        round(sum(valor),2) as valor, ";
$sql .= "        round(sum(tot_venal),2) as venal, ";
$sql .= "        count(*) as quant ";
$sql .= "    from ( select j23_matric, ";
$sql .= "                  j23_tipoim, ";
$sql .= "                  vlr_ter as vlrvenalter, ";
$sql .= "                  vlr_edif as vlrvenalpre, ";
$sql .= "                  tot_venal, ";
$sql .= "                  valor ";
$sql .= "             from ( select j21_matric, ";
$sql .= "                           sum(j21_valor) as valor ";
$sql .= "                      from iptucalv ";
$sql .= "                    where j21_anousu = $exercicio";
$sql .= "                      and j21_receit not in ( select j19_receit ";
$sql .= "                                                from iptutaxa ";
$sql .= "                                               where j19_anousu = $exercicio ";
$sql .= "                                                 and j19_receit = j21_receit) ";
$sql .= "                   group by j21_matric) as z ";
$sql .= "     inner join ( ";
$sql .= "               select j23_matric, ";
$sql .= "                      j23_tipoim, ";
$sql .= "                      j23_vlrter as vlr_ter, ";
$sql .= "                      coalesce(vlr_edif,0) as vlr_edif, ";
$sql .= "                      (j23_vlrter + coalesce(vlr_edif,0)) as tot_venal ";
$sql .= "                 from ( ";
$sql .= "                     select sum(j23_vlrter) as j23_vlrter, ";
$sql .= "                            j23_tipoim, ";
$sql .= "                            j23_matric, ";
$sql .= "                            count(j23_matric) as quantter ";
$sql .= "                       from iptucalc ";
$sql .= "                     where  j23_anousu = $exercicio ";
$sql .= "                   group by j23_tipoim, ";
$sql .= "                            j23_matric) as x ";
$sql .= "            left join ( ";
$sql .= "                     select j22_matric, ";
$sql .= "                            sum(j22_valor) as vlr_edif ";
$sql .= "                       from iptucale ";
$sql .= "                      where j22_anousu = $exercicio ";
$sql .= "                   group by j22_matric) as y on ";
$sql .= "           x.j23_matric = y.j22_matric) as k on ";
$sql .= "           z.j21_matric = k.j23_matric ";
$sql .= "   group by j21_matric, ";
$sql .= "            j23_matric, ";
$sql .= "            valor, ";
$sql .= "            j23_tipoim, ";
$sql .= "            vlr_ter, ";
$sql .= "            vlr_edif, ";
$sql .= "            tot_venal ";
$sql .= "   ) as xx ";
$sql .= "   group by j23_tipoim ";

//die($sql);

$result = pg_query($sql) or die($sql);

for($i=0;$i < pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  if($j23_tipoim == 'P'){
    $v_vlrvenalpred = $vlrvenalpre+$vlrvenalter;
    $v_quantpre     = $quant;
    $v_valorpre     = $valor;
  }
  if($j23_tipoim == 'T'){
    $v_vlrvenalter = $vlrvenalter;
    $v_quantter    = $quant;
    $v_valorter    = $valor;
  }
}

//	$vlrvenalter = 0;
//	$quantter    = 0;
//	$valorter    = 0;

$sql  = " select j21_receit as taxa,";
$sql .= "        k02_drecei as descrtaxa, ";
$sql .= "        count(j21_valor) as quanttaxa, ";
$sql .= "        round(sum(j21_valor),2) as totaltaxa  ";
$sql .= "  from ( select j23_matric,  ";
$sql .= "                j23_tipoim,  ";
$sql .= "                vlr_ter as vlrvenalter,  ";
$sql .= "                vlr_edif as vlrvenalpre,  ";
$sql .= "                tot_venal,  ";
$sql .= "                valor  ";
$sql .= "           from ( select j21_matric,  ";
$sql .= "                         sum(j21_valor) as valor  ";
$sql .= "                    from iptucalv  ";
$sql .= "                  where j21_anousu = $exercicio ";
$sql .= "                    and j21_receit not in ( select j19_receit  ";
$sql .= " 	                                            from iptutaxa  ";
$sql .= " 	                                           where j19_anousu = $exercicio ";
$sql .= " 	                                             and j19_receit = j21_receit) ";
$sql .= "                  group by j21_matric ) as z  ";
$sql .= "       inner join ( select j23_matric, ";
$sql .= " 		                       j23_tipoim,  ";
$sql .= " 		                       j23_vlrter as vlr_ter,  ";
$sql .= " 		                       coalesce(vlr_edif,0) as vlr_edif,  ";
$sql .= " 		                       (j23_vlrter + coalesce(vlr_edif,0)) as tot_venal  ";
$sql .= " 		          from ( select sum(j23_vlrter) as j23_vlrter,  ";
$sql .= " 		                        j23_tipoim,  ";
$sql .= " 		                        j23_matric, ";
$sql .= " 		                        count(j23_matric) as quantter ";
$sql .= " 		                   from iptucalc  ";
$sql .= " 		                 where j23_anousu = $exercicio  ";
$sql .= " 	                   group by j23_tipoim, ";
$sql .= " 		                          j23_matric ) as x     		 ";
$sql .= " 	     left  join ( select j22_matric, ";
$sql .= " 	                         sum(j22_valor) as vlr_edif  ";
$sql .= " 		                  from iptucale  ";
$sql .= " 		                where j22_anousu = $exercicio  ";
$sql .= " 		                group by j22_matric ) as y on x.j23_matric = y.j22_matric ) as k on z.j21_matric = k.j23_matric  ";
$sql .= "  group by j21_matric, ";
$sql .= "           j23_matric, ";
$sql .= "           valor, ";
$sql .= "           j23_tipoim, ";
$sql .= "           vlr_ter, ";
$sql .= " 	         vlr_edif, ";
$sql .= " 	         tot_venal ) as xxa ";
$sql .= "       left join ( select j21_matric, ";
$sql .= "                          k02_drecei, ";
$sql .= "                          j21_valor, ";
$sql .= " 		                      j21_receit ";
$sql .= "                     from iptucalv  ";
$sql .= "                          inner join iptutaxa on j19_receit = j21_receit ";
$sql .= "                                             and j19_anousu = j21_anousu ";
$sql .= "                          inner join tabrec   on j19_receit = k02_codigo ";
$sql .= "                   where j21_anousu = $exercicio ";
$sql .= "                     and j21_valor > 0 ) as xxb on xxa.j23_matric = xxb.j21_matric ";
$sql .= " where xxb.j21_receit is not null ";
$sql .= " group by j21_receit, ";
$sql .= " 		   k02_drecei ";

//echo $sql;exit;

$result=pg_query($sql) or die($sql);

$TotalTaxas  = 0;
$TotalQTaxas = 0;

for($i = 0;$i < pg_numrows($result);$i++){
  $TotalTaxas  += pg_result($result,$i,"totaltaxa");
  $TotalQTaxas += pg_result($result,$i,"quanttaxa");
}

$head4 = "IPTU PAGO";
$head5 = "EXERCÍCIO DE ".$exercicio;

$pdf = new PDF(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage(); // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
$pdf->SetFont('Arial','B',11);

$TotalVenal = $v_vlrvenalpred+$v_vlrvenalter;
$TotalQuant = $v_quantpre+$v_quantter;
$TotalValor = $v_valorpre+$v_valorter;
$TotalGeral = $TotalValor+$TotalTaxas;

$pdf->Cell(30,6,"",0,1,"L",$preenc);
$pdf->Cell(50,6,"",$bordat,0,"L",1);
$pdf->Cell(40,6,"Valor Venal",$bordat,0,"C",1);
$pdf->Cell(30,6,"Quantidade",$bordat,0,"C",1);
$pdf->Cell(30,6,"Valor ",$bordat,0,"C",1);
$pdf->Cell(30,6,"Perc.S/ Total",$bordat,1,"C",1);
$pdf->SetFont('Arial','B',11);

$pdf->Cell(50,8,"IPTU Predial",$bordat,0,"L",$preenc);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(40,8,number_format($v_vlrvenalpred,2,",","."),$bordat,0,"R",$preenc);
$pdf->Cell(30,8,number_format($v_quantpre,0,",","."),$bordat,0,"R",$preenc);
$pdf->Cell(30,8,number_format($v_valorpre,2,",","."),$bordat,0,"R",$preenc);
$pdf->Cell(30,8,number_format(($v_valorpre/$TotalGeral)*100,2,",",".")." %",$bordat,1,"R",$preenc);

$pdf->SetFont('Arial','B',11);
$pdf->Cell(50,8,"IPTU Territorial",$bordat,0,"L",$preenc);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(40,8,number_format($v_vlrvenalter,2,",","."),$bordat,0,"R",$preenc);
$pdf->Cell(30,8,number_format($v_quantter,0,",","."),$bordat,0,"R",$preenc);
$pdf->Cell(30,8,number_format($v_valorter,2,",","."),$bordat,0,"R",$preenc);
$pdf->Cell(30,8,number_format(($v_valorter/$TotalGeral)*100,2,",",".")." %",$bordat,1,"R",$preenc);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(50,8,"Total",$bordat,0,"L",$preenc);
$pdf->Cell(40,8,number_format($TotalVenal,2,",","."),$bordat,0,"R",$preenc);
$pdf->Cell(30,8,number_format($TotalQuant,0,",","."),$bordat,0,"R",$preenc);
$pdf->Cell(30,8,number_format($TotalValor,2,",","."),$bordat,0,"R",$preenc);
$pdf->Cell(30,8,number_format(($TotalValor/$TotalGeral)*100,2,",",".")." %",$bordat,1,"R",$preenc);
$pdf->Cell(30,8,"",0,1,"R",$preenc);
$pdf->SetFont('Arial','B',11);
if ($TotalQTaxas != 0){
  $pdf->Cell(90,6,"TAXAS",$bordat,0,"C",1);
  $pdf->Cell(30,6,"Quantidade",$bordat,0,"C",1);
  $pdf->Cell(30,6,"Valor ",$bordat,0,"C",1);
  $pdf->Cell(30,6,"Perc.S/ Total",$bordat,1,"C",1);
  $pdf->SetFont('Arial','B',9);
}

for($i = 0;$i < pg_numrows($result);$i++){
  //   $pdf->Cell(30,8,"",$bordat,0,"L",$preenc);
  if($TotalQTaxas != 0){ 
    $pdf->Cell(20,8,pg_result($result,$i,"taxa")." - ","LTB",0,"R",$preenc);
    $pdf->Cell(70,8,pg_result($result,$i,"descrtaxa"),"RTB",0,"L",$preenc);
    $pdf->Cell(30,8,number_format(pg_result($result,$i,"quanttaxa"),0,",","."),$bordat,0,"R",$preenc);
    $pdf->Cell(30,8,number_format(pg_result($result,$i,"totaltaxa"),2,",","."),$bordat,0,"R",$preenc);
    $pdf->Cell(30,8,number_format((pg_result($result,$i,"totaltaxa")/$TotalGeral)*100,2,",",".")." %",$bordat,1,"R",$preenc);
  }
}
if($TotalQTaxas != 0){ 
  $pdf->SetFont('Arial','B',11);
  $pdf->Cell(90,8,"Total",1,0,"L",$preenc);
  $pdf->Cell(30,8,number_format($TotalQTaxas,0,",","."),1,0,"R",$preenc);
  $pdf->Cell(30,8,number_format($TotalTaxas,2,",","."),1,0,"R",$preenc);
  $pdf->Cell(30,8,number_format(($TotalTaxas/$TotalGeral)*100,2,",",".")." %",1,1,"R",$preenc);
  
  $pdf->Cell(60,8,"",0,1,"C",0);
  $pdf->Cell(90,4,"",0,0,"C",0);
  $pdf->Cell(30,4,"Valor",$bordat,0,"C",1);
  $pdf->Cell(30,4,"Perc.S/ Total",$bordat,0,"C",1);
}

// TOTAL GERAL
$pdf->Cell(60,4,"",0,1,"C",0);
$pdf->Cell(90,6,"Total Geral - IPTU/TAXAS",1,0,"L",1);
$pdf->Cell(30,6,number_format($TotalGeral,2,",","."),1,0,"R",0);
$pdf->Cell(30,6,number_format(100,2,",",".")." %",1,1,"R",0);




$pdf->SetFont('Arial','',8);
$head1 = "RELATÓRIO DO IPTU PAGO: " . $exercicio;

$mesatu = (int) date("m");
$impcab = false;


$tot_iptu_parc  = 0;
$tot_iptu_unica = 0;

$tot_taxa_parc  = 0;
$tot_taxa_unica = 0;

$tot_matric = 0;
$tot_propri = 0;

$tot_perc_arrec = 0;
$tot_perc_matric = 0;

for ($mes = $mesini; $mes <= $mesfim; $mes++ ) {
  
  if($pdf->GetY() > ( $pdf->h - 30 ) or $impcab == false){
    $pdf->AddPage();
    $pdf->SetFont('Arial','',7);
    $pdf->Cell(10,6,"MES",1,0,"C",1);
    $pdf->Cell(22,6,"IPTU",1,0,"C",1);
    $pdf->Cell(22,6,"TAXAS",1,0,"C",1);
    $pdf->Cell(22,6,"IPTU ",1,0,"C",1);
    $pdf->Cell(22,6,"TAXAS",1,0,"C",1);
    $pdf->Cell(22,6,"QUANTIDADE",1,0,"C",1);
    $pdf->Cell(22,6,"QUANTIDADE",1,0,"C",1);
    $pdf->Cell(22,6,"PERCENTUAL",1,0,"C",1);
    $pdf->Cell(22,6,"PERCENTUAL",1,0,"C",1);
    $pdf->ln();
    
    $pdf->Cell(10,6,"",1,0,"C",1);
    $pdf->Cell(22,6,"PARCELADO",1,0,"C",1);
    $pdf->Cell(22,6,"PARCELADO",1,0,"C",1);
    $pdf->Cell(22,6,"COTA UNICA",1,0,"C",1);
    $pdf->Cell(22,6,"COTA UNICA",1,0,"C",1);
    $pdf->Cell(22,6,"MATRICULAS",1,0,"C",1);
    $pdf->Cell(22,6,"PROPRIETARIOS",1,0,"C",1);
    $pdf->Cell(22,6,"ARRECADADO",1,0,"C",1);
    $pdf->Cell(22,6,"MATRICULAS",1,0,"C",1);
    
    $pdf->SetFont('Arial','',8);
    $pdf->ln();
    $pagina = $pdf->PageNo();
    $impcab = true;
  }
  
  $iptu_parc	  = 0;
  $taxa_parc	  = 0;
  $iptu_unica  	  = 0;
  $totquantmatric = 0;
  $totquantpropri = 0;  
  
  $sqlpagos  = " select k00_hist, ";
  $sqlpagos .= "   	 	 j08_iptucadtaxaexe , ";
  $sqlpagos .= "   		  x.k00_numpre, ";
  $sqlpagos .= " 			  k00_matric, ";
  $sqlpagos .= "   		  k00_valor, ";
  $sqlpagos .= "   		  x.j20_forma,";
  $sqlpagos .= " 			  case when j41_numcgm is null then j01_numcgm else j41_numcgm end as j01_numcgm ";
  $sqlpagos .= "   from ( select distinct j20_matric,  ";
  $sqlpagos .= "                          k00_numpre, ";
  $sqlpagos .= " 													k00_numpar, ";
  $sqlpagos .= "  												case ";
  $sqlpagos .= "                          	when max(k00_hist) = 990 then 'UNICA' else 'PARC' ";
  $sqlpagos .= "													end as j20_forma";
  $sqlpagos .= "                     from iptunump ";
  $sqlpagos .= "                          inner join arrepaga on j20_numpre = k00_numpre ";
  $sqlpagos .= "                    where j20_anousu = $exercicio ";
  $sqlpagos .= "                      and extract (month from k00_dtpaga) = $mes group by j20_matric,k00_numpre,k00_numpar ) as x ";
  $sqlpagos .= " 	     inner join arrepaga   on arrepaga.k00_numpre   = x.k00_numpre ";
  $sqlpagos .= " 	                          and arrepaga.k00_numpar   = x.k00_numpar ";
  $sqlpagos .= " 			 inner join iptubase   on j01_matric            = x.j20_matric ";
  $sqlpagos .= " 			 left  join promitente on j41_matric            = x.j20_matric ";
  $sqlpagos .= " 			                      and j41_tipopro is true ";
  $sqlpagos .= " 			 inner join arrematric on arrematric.k00_numpre = x.k00_numpre ";
  $sqlpagos .= " 			 left  join iptucadtaxaexe on j08_tabrec         = k00_receit   ";
  $sqlpagos .= " 			                         and j08_anousu         = $exercicio   ";
//  $sqlpagos .= " 			 left  join iptutaxa   on j19_receit            = k00_receit   ";
//  $sqlpagos .= " 			                      and j19_anousu            = $exercicio   ";  
  
  $sql  = " select j20_forma, ";
  $sql .= "   		 case when j08_iptucadtaxaexe is null then 'IPTU' else 'TAXA' end as j20_tipo, ";
  $sql .= " 			 count(distinct k00_matric) as quantmatric, ";
  $sql .= " 			 count(distinct j01_numcgm) as quantpropri, ";
  $sql .= " 			 sum(k00_valor) as k00_valor ";
  $sql .= " 	from ($sqlpagos) as x ";
  $sql .= " group by	j20_forma, ";
  $sql .= " 					case when j08_iptucadtaxaexe is null then 'IPTU' else 'TAXA' end";

  //die($sql);			

  $result = pg_exec($sql) or die($sql);
  if (pg_numrows($result)==0){
    continue;
  }
  
  $iptu_parc			= 0;
  $iptu_unica			= 0;
  $taxa_parc			= 0;
  $taxa_unica			= 0;
  $iptu_parc			= 0;
  $taxa_parc			= 0;
  $iptu_unica			= 0;
  $totquantmatric 		= 0;
  $totquantpropri 		= 0;  
  
  for ($reg = 0; $reg < pg_numrows($result); $reg++) {
    db_fieldsmemory($result,$reg);
    if ($j20_tipo == "IPTU") {
      if ($j20_forma == "UNICA") {
        $iptu_unica = $k00_valor;
      } else {
        $iptu_parc = $k00_valor;
      }
    } elseif ($j20_tipo == "TAXA") {
      if ($j20_forma == "UNICA") {
        $taxa_unica = $k00_valor;
      } else {
        $taxa_parc = $k00_valor;
      }
    }
		$totquantmatric += $quantmatric;
		$totquantpropri += $quantpropri;
  }
  
  if($mes % 2 == 0){
    $pre = 0;
  }else {
    $pre = 1;
  }
  
  $pdf->cell(10,4,str_pad($mes,2,'0',STR_PAD_LEFT),0,0,"R",$pre);
  
  $pdf->cell(22,4,db_formatar($iptu_parc,'f'),0,0,"R",$pre);
  $pdf->cell(22,4,db_formatar($taxa_parc,'f'),0,0,"R",$pre);
  
  $pdf->cell(22,4,db_formatar($iptu_unica,'f'),0,0,"R",$pre);
  $pdf->cell(22,4,db_formatar($taxa_unica,'f'),0,0,"R",$pre);
  
  $pdf->cell(22,4,db_formatar($totquantmatric,'s'),0,0,"R",$pre);
  $pdf->cell(22,4,db_formatar($totquantpropri,'s'),0,0,"R",$pre);

  $pdf->cell(22,4,round((($iptu_parc + $taxa_parc + $iptu_unica + $taxa_unica) / $TotalGeral) * 100,2)."%",0,0,"R",$pre);
  $pdf->cell(22,4,round(($totquantmatric) / ($v_quantpre + $v_quantter) * 100,2)."%",0,0,"R",$pre);
  
  $tot_iptu_parc  += $iptu_parc;
  $tot_iptu_unica += $iptu_unica;
  
  $tot_taxa_parc  += $taxa_parc;
  $tot_taxa_unica += $taxa_unica;
  
  $tot_matric += $totquantmatric;
  $tot_propri += $totquantpropri;
  
  $tot_perc_arrec  += (($iptu_parc + $taxa_parc + $iptu_unica + $taxa_unica) / $TotalGeral) * 100;
  $tot_perc_matric += ($totquantmatric) / ($v_quantpre + $v_quantter) * 100;
  
  $pdf->ln();
  
}

$pdf->ln();

$pdf->cell(10,4,"",0,0,"R",$pre);

$pdf->cell(22,4,db_formatar($tot_iptu_parc,'f'),0,0,"R",$pre);
$pdf->cell(22,4,db_formatar($tot_taxa_parc,'f'),0,0,"R",$pre);

$pdf->cell(22,4,db_formatar($tot_iptu_unica,'f'),0,0,"R",$pre);
$pdf->cell(22,4,db_formatar($tot_taxa_unica,'f'),0,0,"R",$pre);

//$pdf->cell(22,4,db_formatar($tot_matric,'s'),0,0,"R",$pre);
$pdf->cell(22,4,"",0,0,"R",$pre);
//$pdf->cell(22,4,db_formatar($tot_propri,'s'),0,0,"R",$pre);
$pdf->cell(22,4,"",0,0,"R",$pre);

$pdf->cell(22,4,round($tot_perc_arrec,2)."%",0,0,"R",$pre);
//$pdf->cell(22,4,round($tot_perc_matric,2)."%",0,0,"R",$pre);
$pdf->cell(22,4,"",0,0,"R",$pre);

$pdf->Output();

?>
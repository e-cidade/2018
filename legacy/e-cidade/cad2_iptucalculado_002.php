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

require_once("fpdf151/pdf.php");

db_postmemory($HTTP_SERVER_VARS);

$borda        = 1;
$bordat       = 1;
$preenc       = 0;
$pre 		      = 0;
$TPagina      = 57;

$v_vlrvenalpred = 0;
$v_quantter     = 0;
$v_quantpre     = 0;
$v_valorter     = 0;
$v_valorpre     = 0;
$v_vlrvenalter  = 0;
$v_quanttaxa    = 0;
$v_quant        = 0;
$v_valor        = 0;

$sqlrec ="select j18_rpredi, j18_rterri from cfiptu where j18_anousu = $exercicio";
$resultrec= db_query($sqlrec);
db_fieldsmemory($resultrec,0);

if(!isset($vlrvenalini) || $vlrvenalini ==""){
  $vlrvenalini = 0;
};

if(!isset($vlrvenalfim) || $vlrvenalfim ==""){
  $whereand = "";
}else {
  $whereand = "and tot_venal <= ".$vlrvenalfim;
};

$sql=" select j23_tipoim,
			  round(sum(vlrvenalter),2) as vlrvenalter,
			  round(sum(vlrvenalpre),2) as vlrvenalpre,
			  round(sum(valor),2) as valor,
			  round(sum(tot_venal),2) as venal,
			  count(*) as quant
		 from (	select j23_matric,
					   j23_tipoim,
					   vlr_ter as vlrvenalter,
					   vlr_edif as vlrvenalpre,
					   tot_venal,
					   valor
				  from ( select j21_matric,
								sum(j21_valor) as valor
						   from iptucalv
						  where j21_anousu = $exercicio
						  	and j21_receit not in ( select j19_receit
													  from iptutaxa
													 where j19_anousu = $exercicio
													   and j19_receit = j21_receit )
					   group by j21_matric) as z
			  inner join ( select j23_matric,
								  j23_tipoim,
								  j23_vlrter as vlr_ter,
								  coalesce(vlr_edif,0) as vlr_edif,
								  (j23_vlrter + coalesce(vlr_edif,0)) as tot_venal
							 from ( select sum(j23_vlrter) as j23_vlrter,
										   j23_tipoim,
										   j23_matric,
										   count(j23_matric) as quantter
									  from iptucalc " .
					($considerarisentos == "n"?"inner join iptunump on j20_matric = j23_matric and j20_anousu = j23_anousu":"") . "
									 where j23_anousu = $exercicio
								  group by j23_tipoim,
										   j23_matric) as x
								  left join ( select j22_matric,
													 sum(j22_valor) as vlr_edif
												from iptucale " .
					($considerarisentos == "n"?"inner join iptunump on j20_matric = j22_matric and j20_anousu = j22_anousu":"") . "
											   where j22_anousu = $exercicio
											group by j22_matric) as y on x.j23_matric = y.j22_matric) as k on z.j21_matric = k.j23_matric
						    where tot_venal > $vlrvenalini $whereand
						      and 1 = 1
						 group by j21_matric,
								  j23_matric,
								  valor,
								  j23_tipoim,
								  vlr_ter,
								  vlr_edif,
								  tot_venal ) as xx
						 group by j23_tipoim ";


$result=db_query($sql) or die($sql);

for($i = 0;$i < pg_numrows($result);$i++){
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

$sql = " select j21_receit as taxa,
				k02_drecei as descrtaxa,
				count(j21_valor) as quanttaxa,
			  round(sum(j21_valor),2) as totaltaxa
		   from ( select j23_matric,
						 j23_tipoim,
						 vlr_ter as vlrvenalter,
						 vlr_edif as vlrvenalpre,
						 tot_venal,
						 valor
				    from ( select j21_matric,
								  sum(j21_valor) as valor
							 from iptucalv
							where j21_anousu = $exercicio
							  and j21_receit not in ( select j19_receit
														from iptutaxa
													   where j19_anousu = $exercicio
													     and j19_receit = j21_receit )
						 group by j21_matric ) as z
						 inner join ( select j23_matric,
											 j23_tipoim,
											 j23_vlrter as vlr_ter,
											 coalesce(vlr_edif,0) as vlr_edif,
											 (j23_vlrter + coalesce(vlr_edif,0)) as tot_venal
										from ( select sum(j23_vlrter) as j23_vlrter,
													  j23_tipoim,
													  j23_matric,
													  count(j23_matric) as quantter
												 from iptucalc " .
			($considerarisentos == "n"?"inner join iptunump on j20_matric = j23_matric and j20_anousu = j23_anousu":"") . "
											    where j23_anousu = $exercicio
										     group by j23_tipoim,
													  j23_matric ) as x
						 left join ( select j22_matric,
											sum(j22_valor) as vlr_edif
									   from iptucale " .
		    ($considerarisentos == "n"?"inner join iptunump on j20_matric = j22_matric and j20_anousu = j22_anousu":"") . "
									  where j22_anousu = $exercicio
								   group by j22_matric ) as y on x.j23_matric = y.j22_matric) as k on z.j21_matric = k.j23_matric
				  where tot_venal > $vlrvenalini $whereand
				    and 1 = 1
			   group by j21_matric,
						j23_matric,
						valor,
						j23_tipoim,
						vlr_ter,
						vlr_edif,
						tot_venal ) as xxa
	   left join ( select j21_matric,
						  k02_drecei,
						  j21_valor,
						  j21_receit
					 from iptucalv
						  inner join iptutaxa on j19_receit = j21_receit
											 and j19_anousu = j21_anousu
						  inner join tabrec   on j19_receit = k02_codigo
					where j21_anousu = $exercicio
					  ) as xxb
		    on xxa.j23_matric = xxb.j21_matric
	  where xxb.j21_receit is not null
   group by j21_receit,
			k02_drecei ";


$result=db_query($sql) or die($sql);
$TotalTaxas  = 0;
$TotalQTaxas = 0;

for($i = 0;$i < pg_numrows($result);$i++){
  $TotalTaxas  += pg_result($result,$i,"totaltaxa");
  $TotalQTaxas += pg_result($result,$i,"quanttaxa");
}

$head4 = "POSIÇÃO DO IPTU CALCULADO";
$head5 = "EXERCÍCIO DE ".$exercicio;

$pdf = new PDF(); 	  // abre a classe
$pdf->Open(); 		  // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage("L");   // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
$pdf->SetFont('Arial','B',11);

$TotalVenal = $v_vlrvenalpred + $v_vlrvenalter;
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
$pdf->SetFont('Courier','B',9);
$pdf->Cell(40,8,number_format($v_vlrvenalpred,2,",","."),$bordat,0,"R",$preenc);
$pdf->Cell(30,8,number_format($v_quantpre,0,",","."),$bordat,0,"R",$preenc);
$pdf->Cell(30,8,number_format($v_valorpre,2,",","."),$bordat,0,"R",$preenc);
$pdf->Cell(30,8,number_format(($v_valorpre/$TotalGeral)*100,2,",",".")." %",$bordat,1,"R",$preenc);

$pdf->SetFont('Arial','B',11);
$pdf->Cell(50,8,"IPTU Territorial",$bordat,0,"L",$preenc);
$pdf->SetFont('Courier','B',9);
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
  $pdf->SetFont('Courier','B',9);
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

if((int) $vlrvenalini > 0 or (int) $vlrvenalfim > 0){
	//VALOR VENAL ACIMA DE
	$pdf->Cell(60,8,"",0,1,"C",0);
	$pdf->Cell(80,5,"Intervalo de Valor Venal",1,0,"C",1);
	$pdf->Cell(60,5,"",0,1,"C",0);
	if($vlrvenalini != "" || $vlrvenalini == 0){
		$pdf->Cell(50,5,"Valor Venal Inicial...",1,0,"L",0);
		$pdf->Cell(30,5,number_format($vlrvenalini,2,",","."),1,0,"R",0);
		$pdf->Cell(60,5,"",0,1,"C",0);
	}else{
		$pdf->Cell(50,5,"Valor Venal Inicial...",1,0,"L",0);
		$pdf->Cell(30,5,"Mínimo...",1,0,"R",0);
		$pdf->Cell(60,5,"",0,1,"C",0);
	}
	if($vlrvenalfim != ""){
		$pdf->Cell(50,5,"Valor Venal Final...",1,0,"L",0);
		$pdf->Cell(30,5,number_format($vlrvenalfim,2,",","."),1,0,"R",0);
	}else{
		$pdf->Cell(50,5,"Valor Venal Final...",1,0,"L",0);
		$pdf->Cell(30,5,"Máximo...",1,0,"R",0);
	};
}

if($considerarisentos=="n"){
  $pdf->ln(10);
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(100,6,"* Este relatório não considera matrículas com isenção lançada",0,0,"L",0);
}

if ($emitirpormes == "s") {


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

	$tot_matric_iptu_unica = 0;
	$tot_matric_taxa_unica = 0;

  for ($mes = $mesini; $mes <= $mesfim; $mes++ ) {

    if($pdf->GetY() > ( $pdf->h - 30 ) or $impcab == false){
      $pdf->AddPage("L");
      $pdf->SetFont('Arial','',7);
      $pdf->Cell(10,6,"MES",1,0,"C",1);
      $pdf->Cell(44,6,"IPTU",1,0,"C",1);
      $pdf->Cell(44,6,"TAXAS",1,0,"C",1);

      $pdf->Cell(88,6,"QUANTIDADE DE MATRICULAS PAGANTES",1,0,"C",1);

      $pdf->Cell(44,6,"QUANTIDADE PROPRIETÁRIOS",1,0,"C",1);
      $pdf->Cell(22,6,"PERCENTUAL",1,0,"C",1);
      $pdf->Cell(22,6,"PERCENTUAL",1,0,"C",1);
      $pdf->ln();

      $pdf->Cell(10,6,"",1,0,"C",1);
      $pdf->Cell(22,6,"UNICA",1,0,"C",1);
      $pdf->Cell(22,6,"PARCELADO",1,0,"C",1);
      $pdf->Cell(22,6,"UNICA",1,0,"C",1);
      $pdf->Cell(22,6,"PARCELADO",1,0,"C",1);

      $pdf->Cell(22,6,"IPTU UNICA",1,0,"C",1);
      $pdf->Cell(22,6,"IPTU PARC",1,0,"C",1);
      $pdf->Cell(22,6,"TAXAS UNICA",1,0,"C",1);
      $pdf->Cell(22,6,"TAXAS PARC",1,0,"C",1);

      $pdf->Cell(22,6,"IPTU",1,0,"C",1);
      $pdf->Cell(22,6,"TAXAS",1,0,"C",1);
      $pdf->Cell(22,6,"ARRECADADO",1,0,"C",1);
      $pdf->Cell(22,6,"MATRICULAS",1,0,"C",1);

      $pdf->SetFont('Arial','',8);
      $pdf->ln();
      $pagina = $pdf->PageNo();
      $impcab = true;
    }

    $iptu_parc		= 0;
    $taxa_parc		= 0;
    $iptu_unica		= 0;
    $totquantmatric = 0;
    $totquantpropri = 0;


  $sqlpagos   = "   select j20_matric, 																	                                              ";
	$sqlpagos  .= "	    	   j20_numpre, 																	                                              ";
	$sqlpagos  .= "			     k00_numpar, 																	                                              ";
	$sqlpagos  .= "			     j21_receit,																	                                              ";
	$sqlpagos  .= "			     min(j21_codhis) as j21_codhis, 												                                    ";
	$sqlpagos  .= "			     case when j41_numcgm is null then j01_numcgm else j41_numcgm end as j01_numcgm,            ";
	$sqlpagos  .= "			     case when ( select max(k00_hist)                                                           ";
	$sqlpagos  .= "			                   from arrepaga as u                                                           ";
	$sqlpagos  .= "			                  where u.k00_numpre = j20_numpre) in (0,10,20,60,90,91,100,110,190,890,918,990,1018 ) ";
	$sqlpagos  .= "			            then 'UNICA'                                                                        ";
	$sqlpagos  .= "			          else 'PARC'                                                                           ";
	$sqlpagos  .= "			     end as j20_forma, 	                                                                        ";
	$sqlpagos  .= "			     case when iptutaxa.j19_receit is not null then 'TAXA'  else 'IPTU' end as j20_tipo,        ";
	$sqlpagos  .= "			     round(arrepaga.k00_valor,2) as k00_valor																                    ";
  $sqlpagos  .= "	    from iptunump  																		                                              ";
	$sqlpagos  .= "			 inner join iptubase   	   on j01_matric 			    = j20_matric  			                          ";
	$sqlpagos  .= "			 left  join promitente 	   on j41_matric 			    = j20_matric  			                          ";
	$sqlpagos  .= "								 	  and j41_tipopro is true 								                                          ";
	$sqlpagos  .= "			 inner join iptucalv	   on j20_anousu 			    = j21_anousu 			                              ";
	$sqlpagos  .= "									  and j20_matric 			    = j21_matric 			                                        ";
	$sqlpagos  .= "		     left  join iptutaxa on iptutaxa.j19_receit = iptucalv.j21_receit 	                          ";
	$sqlpagos  .= "     	    	                  and iptutaxa.j19_anousu = {$exercicio}			                          ";
	$sqlpagos  .= "			 inner join arrepaga   	   on j20_numpre 			    = arrepaga.k00_numpre 	                      ";
	$sqlpagos  .= "								 	  and j21_receit 			    = k00_receit 			                                        ";
	$sqlpagos  .= "		 	 inner join arrematric 	   on arrematric.k00_numpre 	= j20_numpre			                        ";
  $sqlpagos  .= "	   where j20_anousu = $exercicio  														                                      ";
	$sqlpagos  .= "      and extract (month from k00_dtpaga) = $mes 										                                ";
	$sqlpagos  .= " group by j20_matric,  																	                                            ";
	$sqlpagos  .= " 	     j20_numpre,  																	                                              ";
	$sqlpagos  .= "		     k00_numpar,  																	                                              ";
	$sqlpagos  .= "		     j21_receit, 																	                                                ";
	$sqlpagos  .= "		     k00_valor, 																	                                                ";
	$sqlpagos  .= "		     iptutaxa.j19_receit,														                                              ";
	$sqlpagos  .= "		     case when j41_numcgm is null then j01_numcgm else j41_numcgm end 				                    ";



    $sql  = "     select j20_forma, 								             ";
    $sql .= "   		 j20_tipo, 								                   ";
    $sql .= " 			 count(distinct j20_matric) as quantmatric,  ";
    $sql .= " 			 count(distinct j01_numcgm) as quantpropri,  ";
    $sql .= " 			 round(sum(k00_valor),2) as k00_valor 			 ";
    $sql .= " 	    from ($sqlpagos) as x 						           ";
    $sql .= "   group by j20_forma, 							               ";
    $sql .= " 			 j20_tipo									                   ";


    $result = db_query($sql) or die($sql);
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

    $totquantmatric = 0;
    $totquantpropri = 0;

		$quant_iptu_unica = 0;
		$quant_taxa_unica = 0;
		$quant_iptu_parc  = 0;
		$quant_taxa_parc  = 0;

		$quant_iptu_propri = 0;
		$quant_taxa_propri = 0;

    for ($reg = 0; $reg < pg_numrows($result); $reg++) {

      db_fieldsmemory($result,$reg);
      if ($j20_tipo == "IPTU") {

        if ($j20_forma == "UNICA") {

          $iptu_unica       = $k00_valor;
					$quant_iptu_unica = $quantmatric;
        } else {

          $iptu_parc       = $k00_valor;
					$quant_iptu_parc = $quantmatric;
        }
				$quant_iptu_propri = $quantpropri;
      } elseif ($j20_tipo == "TAXA") {

        if ($j20_forma == "UNICA") {

          $taxa_unica       = $k00_valor;
					$quant_taxa_unica = $quantmatric;
        } else {

          $taxa_parc       = $k00_valor;
					$quant_taxa_parc = $quantmatric;
        }
				$quant_taxa_propri = $quantpropri;
      }

      $totquantmatric += $quantmatric;
      $totquantpropri += $quantpropri;
    }

    $pre = 1;
    if ($mes % 2 == 0) {
      $pre = 0;
    }

    $pdf->cell(10,4,str_pad($mes,2,'0',STR_PAD_LEFT),0,0,"R",$pre);
    $pdf->cell(22,4,db_formatar($iptu_unica,'f'),0,0,"R",$pre);
    $pdf->cell(22,4,db_formatar($iptu_parc,'f'),0,0,"R",$pre);

    $pdf->cell(22,4,db_formatar($taxa_unica,'f'),0,0,"R",$pre);
    $pdf->cell(22,4,db_formatar($taxa_parc,'f'),0,0,"R",$pre);

    $pdf->cell(22,4,db_formatar($quant_iptu_unica,'s'),0,0,"R",$pre);
    $pdf->cell(22,4,db_formatar($quant_iptu_parc,'s'),0,0,"R",$pre);
    $pdf->cell(22,4,db_formatar($quant_taxa_unica,'s'),0,0,"R",$pre);
    $pdf->cell(22,4,db_formatar($quant_taxa_parc,'s'),0,0,"R",$pre);

    $pdf->cell(22,4,db_formatar($quant_iptu_propri,'s'),0,0,"R",$pre);
    $pdf->cell(22,4,db_formatar($quant_taxa_propri,'s'),0,0,"R",$pre);

    $pdf->cell(22,4,round((($iptu_parc + $taxa_parc + $iptu_unica + $taxa_unica) / $TotalGeral) * 100,2)."%",0,0,"R",$pre);
    $pdf->cell(22,4,round(($totquantmatric) / ($v_quantpre + $v_quantter) * 100,2)."%",0,0,"R",$pre);

		$tot_matric_iptu_unica += (float) $quant_iptu_unica;
    $tot_matric_taxa_unica += (float) $quant_taxa_unica;

    $tot_iptu_parc  += $iptu_parc;
    $tot_iptu_unica += $iptu_unica;

    $tot_taxa_parc  += $taxa_parc;
    $tot_taxa_unica += $taxa_unica;

    $tot_matric += $totquantmatric;
    $tot_propri += $totquantpropri;

    $tot_perc_arrec += (($iptu_parc + $taxa_parc + $iptu_unica + $taxa_unica) / $TotalGeral) * 100;
    $tot_perc_matric += ($totquantmatric) / ($v_quantpre + $v_quantter) * 100;

    $pdf->ln();

  }

  $pdf->ln();

  $pdf->cell(10,4,"",0,0,"R",$pre);

  $pdf->cell(22,4,db_formatar($tot_iptu_unica,'f'),0,0,"R",$pre);
  $pdf->cell(22,4,db_formatar($tot_iptu_parc,'f'),0,0,"R",$pre);

  $pdf->cell(22,4,db_formatar($tot_taxa_unica,'f'),0,0,"R",$pre);
  $pdf->cell(22,4,db_formatar($tot_taxa_parc,'f'),0,0,"R",$pre);

  $pdf->cell(22,4,db_formatar($tot_matric_iptu_unica, 's'),0,0,"R",$pre);
  $pdf->cell(22,4,"",0,0,"R",$pre);
  $pdf->cell(22,4,db_formatar($tot_matric_taxa_unica, 's'),0,0,"R",$pre);
  $pdf->cell(22,4,"",0,0,"R",$pre);

  $pdf->cell(22,4,"",0,0,"R",$pre);
  $pdf->cell(22,4,"",0,0,"R",$pre);

  $pdf->cell(22,4,round($tot_perc_arrec,2)."%",0,0,"R",$pre);
  $pdf->cell(22,4,"",0,0,"R",$pre);

}

$pdf->Output();
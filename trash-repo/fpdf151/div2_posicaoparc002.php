<?
include ("libs/db_sql.php");
include ("fpdf151/pdf.php");
include ("classes/db_arrecad_classe.php");
include ("classes/db_termo_classe.php");
$clarrecad = new cl_arrecad;
$cltermo = new cl_termo;
$cltermo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('v07_parcel');
$clrotulo->label('z01_numcgm');
$clrotulo->label('z01_nome');
$clrotulo->label('k00_tipo');
$clrotulo->label('k00_numpre');
$clrotulo->label('k00_numtot');
$clrotulo->label('k00_descr');
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_SERVER_VARS);
if ($sele == "S") {
	$simnao = ' in ';
} else {
	$simnao = ' not in ';
}

if (isset ($tipodivida)) {
	$tipo = ' and arrecad.k00_tipo '.$simnao.' ('.str_replace("-", ",", $tipodivida).') ';
} else {
	$tipo = '';
}
if (isset ($atraso) && $atraso != "") {
	$numvenc = $atraso;
} else {
	$numvenc = "0";
}

$datausu = date("Y/m/d", db_getsession("DB_datausu"));

//$head4 = "Relatório com dados atualizados até " .db_formatar($data,'d') ;
/*
$xparcelas = '';
if ($parcelas == '' || $parcelas == 0) {
	$parcelas = 0;
	$head5 = 'Completo';
} else {
	$xparcelas = " having count(v07_numpre) >= ".$parcelas;
	$head5 = $parcelas.' parcelas ou mais em atraso';
}
*/
$head6 = "periodo ". ($datai == '--' ? "" : " de ".db_formatar($datai, 'd'))." até ".db_formatar($dataf, 'd');

if ($datai == '--') {
	$datai = '1900-01-01';
}

$head2 = 'RELATÓRIO DA POSIÇÃO DOS PARCELAMENTOS';
/*
$sql = "select v07_parcel, v07_numpre, sum(valor_vencidos) as valor_vencidos, 
	               sum(valor_tudo) as valor_tudo, count(v07_numpre) 
              from (
                  select v07_parcel,
		      	           v07_numpre,
			                 k22_numpar,
			                 sum(k22_vlrcor+k22_juros+k22_multa-k22_desconto) as valor_tudo,
			                 sum(k22_vlrcor+k22_juros+k22_multa-k22_desconto) as valor_vencidos
		               from debitos inner join termo on k22_numpre = v07_numpre
		      	     where v07_dtlanc between '$datai' and '$dataf' and k22_data = '$data'
			                   and k22_dtvenc < '".$datausu."'
		      	       $tipo group by v07_parcel, v07_numpre,k22_numpar
	           ) as x group by v07_parcel,v07_numpre".$xparcelas;
//echo $sql;exit;
 */ /*
 substr(fc_calcula,2,13)::float8 as vlrhis,
                 substr(fc_calcula,15,13)::float8 as vlrcor,
                 substr(fc_calcula,28,13)::float8 as vlrjuros,
                 substr(fc_calcula,41,13)::float8 as vlrmulta,
                 substr(fc_calcula,54,13)::float8 as vlrdesconto,
                 (substr(fc_calcula,15,13)::float8+
                 substr(fc_calcula,28,13)::float8+
                 substr(fc_calcula,41,13)::float8-
                 substr(fc_calcula,54,13)::float8) as total
*/
$sql = "select   distinct v07_parcel,v07_numpre,v07_numcgm,k00_numpar,v07_dtlanc,k00_dtvenc,k00_tipo,k00_descr,z01_nome,z01_telef,v07_totpar,k00_numtot,v07_valor,fc_calcula                 
        from ( select v07_parcel,
 					  v07_numpre,
					  v07_numcgm,
                      k00_numpar,
               		  v07_dtlanc,
                      k00_dtvenc,
                      arrecad.k00_tipo,
                      k00_descr,
                      z01_nome,
                      z01_telef,
      				  v07_totpar,
                      v07_valor,
                      k00_numtot,
                      fc_calcula(v07_numpre,k00_numpar,0,now(),now(),".db_getsession("DB_anousu").")
               from termo 
                    inner join arrecad on k00_numpre = v07_numpre 
                    inner join arretipo on arrecad.k00_tipo = arretipo.k00_tipo 
                    inner join cgm on v07_numcgm = z01_numcgm 
               where v07_dtlanc between '$datai' and '$dataf' $tipo order by v07_numpre,k00_numpar)as x";

/*  
$sql = "select v07_parcel,
 					  v07_numpre,
                      k00_numpar,
               		  v07_dtlanc,
                      k00_dtvenc,
                      arrecad.k00_tipo,
                      k00_descr,
                      z01_nome,
      				  v07_totpar,
                      v07_valor,
                      fc_calcula(v07_numpre,k00_numpar,0,now(),now(),".db_getsession("DB_anousu").") as fc_calcula
               from termo 
                    inner join arrecad on k00_numpre = v07_numpre 
                    inner join arretipo on arrecad.k00_tipo = arretipo.k00_tipo 
                    inner join cgm on v07_numcgm = z01_numcgm 
               where v07_dtlanc between '$datai' and '$dataf' $tipo order by v07_numpre,k00_numpar";
//echo $sql;exit;
 * 
 */
//die($sql);
$result = pg_exec($sql);
if (pg_numrows($result) == 0) {
	db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
}
$pdf = new PDF();
$pdf->Open();
$pdf->AliASNbPages();
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFillColor(220);
$pdf->SetFont('Arial', 'B', 7);
$pag = 1;

$totalparcvenc = "0";
$totalparcaber = 0;
$totalparcpag = 0;
$totalvenc = "0";
$totalaber = 0;
$totalpag = 0;
$totalparc = 0;
$totalval = 0;
$totaldiv = 0;
$totalreg = 0;
$totvalor = 0;
$parc_venc = 0;
$parc_dia = 0;
$valor_dia = 0;
$b = 0;
$numpre = 0;
$par_pag = "0";
$par_aber = "0";
$par_venc = "0";
$val_pag = "0";
$val_venc = "0";
$val_aber = "0";
$total = 0;
$vencido = false;
$pre=0;
$k00_dtvenc_one="";
for ($x = 0; $x < pg_numrows($result); $x ++) {
	db_fieldsmemory($result, $x);
	
	$vlrhis = (float) substr($fc_calcula, 1, 13);
	$vlrcor = (float)  substr($fc_calcula, 14, 13);
	$vlrjuros = (float)  substr($fc_calcula, 27, 13);
	$vlrmulta = (float)  substr($fc_calcula, 40, 13);
	$vlrdesconto = (float)  substr($fc_calcula, 53, 13);	
	$total = $vlrcor + $vlrjuros + $vlrmulta - $vlrdesconto;
			
	//echo $fc_calcula."<br>";
	//die(substr($fc_calcula,15,12)."---------".substr($fc_calcula,28,12)."---------".substr($fc_calcula,41,12)."---------".substr(fc_calcula,54,12));
	//die($fc_calcula);
	//$total=substr($fc_calcula,14,12)+substr($fc_calcula,27,12)+substr($fc_calcula,40,12)-substr(fc_calcula,53,12);
	$numpre = $v07_numpre;
	if ($x == 0) {
		$numpre_ant = $numpre;
	}
	if ($numpre == $numpre_ant) {
		if ($k00_dtvenc < date('Y-m-d', db_getsession("DB_datausu"))) {
			$par_venc += 1;
			$val_venc += $total;
			$vencido = true;
			if ($k00_dtvenc_one==""){
				$k00_dtvenc_one = $k00_dtvenc;
			}			
		} else {
			$valor_dia += $total;
			$par_aber ++;
			$val_aber += $total;
		}		
		$v07_parcel_ant = $v07_parcel;
		$k00_tipo_ant = $k00_tipo;
		$k00_descr_ant = $k00_descr;
		$z01_nome_ant = $z01_nome;
		$z01_telef_ant = $z01_telef;
		$v07_numcgm_ant = $v07_numcgm;
		$v07_dtlanc_ant = $v07_dtlanc;
		//$v07_totpar_ant = $v07_totpar;
		$k00_numtot_ant = $k00_numtot;
		$v07_valor_ant = $v07_valor;
		continue;
	} else {
		if (strpos($grafico, "R") > 0) {
			if (($pdf->gety() > $pdf->h - 30) || $pag == 1) {
				$pdf->addpage("L");
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->Cell(25, 5, strtoupper($RLv07_parcel), 1, 0, "C", 1);
				$pdf->Cell(22, 5, "DATA LANC.", 1, 0, "C", 1);
				$pdf->Cell(70, 5, "CONTRIBUINTE", 1, 0, "C", 1);
				$pdf->Cell(70, 5, 'RESPONSAVEL', 1, 0, "C", 1);
				$pdf->Cell(26, 5, 'FONE RESP.', 1, 0, "C", 1);
				$pdf->Cell(60, 5, strtoupper($RLk00_descr), 1, 1, "C", 1);
				//	$pdf->Ln(5); 
				$pdf->Cell(33, 5, 'DT 1º PARC VENC', 1, 0, "C", 1);
				$pdf->Cell(30, 5, 'TOTAL PARC.', 1, 0, "C", 1);
				$pdf->Cell(30, 5, 'PARC. PAGAS', 1, 0, "C", 1);
				$pdf->Cell(30, 5, 'PARC. Ñ VENCIDAS', 1, 0, "C", 1);
				$pdf->Cell(30, 5, 'PARC. VENCIDAS', 1, 0, "C", 1);
				$pdf->Cell(30, 5, 'VLR PARCELAMENTO', 1, 0, "C", 1);
				$pdf->Cell(30, 5, 'VLR PAGO', 1, 0, "C", 1);
				$pdf->Cell(30, 5, 'VLR ABERTO', 1, 0, "C", 1);
				$pdf->Cell(30, 5, 'VLR VENCIDO', 1, 1, "C", 1);

				$pag = 0;
				$pre = 1;

			}
		}	

		/*
		$result_pag = pg_exec("select distinct(arrecant.k00_numpar)as par_pag 
							                         from arrecant inner join arrepaga on arrepaga.k00_numpre = arrecant.k00_numpre and 
										                                      arrepaga.k00_numpar = arrecant.k00_numpar 
										where arrecant.k00_numpre = $v07_numpre");
		if (pg_numrows($result_pag) > 0) {
			$par_pag = pg_numrows($result_pag);
		}
		$result_pag = pg_exec("select sum(arrecant.k00_valor) as val_pag 
							                         from arrecant inner join arrepaga on arrepaga.k00_numpre = arrecant.k00_numpre and 
				 							                              arrepaga.k00_numpar = arrecant.k00_numpar 
										where arrecant.k00_numpre = $v07_numpre");
		if (pg_numrows($result_pag) > 0) {
			db_fieldsmemory($result_pag, 0);
		}
		$result_aber = pg_exec("select distinct(k00_numpar) from arrecad where k00_numpre = $v07_numpre");
		if (pg_numrows($result_aber) > 0) {
			$par_aber = pg_numrows($result_aber);
		}
		$result_aber = pg_exec("select sum(k00_valor) as val_aber from arrecad where k00_numpre = $v07_numpre");
		if (pg_numrows($result_aber) > 0) {
			db_fieldsmemory($result_aber, 0);
		}
		$result_venc = pg_exec("select distinct(k00_numpar) 
							                          from arrecad 
										 where k00_numpre = $v07_numpre and 
										       k00_dtvenc<'".date('Y-m-d', db_getsession("DB_datausu"))."'");
		if (pg_numrows($result_venc) > 0) {
			$par_venc = pg_numrows($result_venc);
			$parc_venc += 1;
		} else {
			$parc_dia += 1;
			$valor_dia += $val_aber;
		}
		if ($par_venc < $parcelas) {
			continue;
		}
		$result_venc = pg_exec("select sum(k00_valor) as val_venc 
							                          from arrecad 
										 where k00_numpre = $v07_numpre and 
										       k00_dtvenc<'".date('Y-m-d', db_getsession("DB_datausu"))."'");
		if (pg_numrows($result_venc) > 0) {
			db_fieldsmemory($result_venc, 0);
		}
		$result_termo = pg_exec("select v07_totpar,v07_dtlanc, z01_nome, v07_valor 
							                           from termo inner join cgm on v07_numcgm = z01_numcgm 
										  where v07_parcel = $v07_parcel");
		db_fieldsmemory($result_termo, 0);
		$result_arrecad = $clarrecad->sql_record($clarrecad->sql_query(null, "*", null, "k00_numpre=$v07_numpre"));
		if ($clarrecad->numrows > 0) {
			db_fieldsmemory($result_arrecad, 0);
		}
		*/
		$result_pag = pg_exec("select distinct(arrecant.k00_numpar)as par_pag 
									                         from arrecant inner join arrepaga on arrepaga.k00_numpre = arrecant.k00_numpre and 
												                                      arrepaga.k00_numpar = arrecant.k00_numpar 
												where arrecant.k00_numpre = $numpre_ant");
		if (pg_numrows($result_pag) > 0) {
			$par_pag = pg_numrows($result_pag);
		}
		$result_pag = pg_exec("select sum(arrecant.k00_valor) as val_pag 
									                         from arrecant inner join arrepaga on arrepaga.k00_numpre = arrecant.k00_numpre and 
						 							                              arrepaga.k00_numpar = arrecant.k00_numpar 
												where arrecant.k00_numpre = $numpre_ant");
		if (pg_numrows($result_pag) > 0) {
			db_fieldsmemory($result_pag, 0);
		}

		if ($par_venc >= $numvenc) {
			if ($pre == 0) {
				$pre = 1;
			} else
				if ($pre == 1) {
					$pre = 0;
				}
			if (strpos($grafico, "R") > 0) {
				//Busca o nome do contribuinte---------------------------------------------------------------------------------
				

/*		echo "select k00_matric,k00_inscr,z01_nome as contrib from arrenumcgm 
																   left join arrematric on arrematric.k00_numpre = arrenumcgm.k00_numpre 
																   left join arreinscr on arreinscr.k00_numpre = arrenumcgm.k00_numpre 
																   left join cgm on k00_numcgm=z01_numcgm
															    where arrenumcgm.k00_numpre=$numpre_ant <br> Numpre:$numpre <br> Parcelamento:$v07_parcel_ant<br>";*/
		$result_contrib = pg_exec("select k00_matric,k00_inscr,k00_numcgm as cgm_contrib,z01_nome as contrib from arrenumcgm 
																   left join arrematric on arrematric.k00_numpre = arrenumcgm.k00_numpre
																   left join arreinscr on arreinscr.k00_numpre = arrenumcgm.k00_numpre 
																   left join cgm on k00_numcgm=z01_numcgm
															    where arrenumcgm.k00_numpre=$numpre_ant");
		if (pg_numrows($result_contrib) > 0) {
			db_fieldsmemory($result_contrib, 0);
			if ($k00_matric != "") {
				$result_propri = pg_exec("select z01_cgmpri as cgm_contrib,z01_nome as contrib from proprietario where j01_matric = $k00_matric");
				db_fieldsmemory($result_propri, 0);
			} else
				if ($k00_inscr != "") {
					$result_empre = pg_exec("select q02_numcgm as cgm_contrib,z01_nome as contrib from empresa where q02_inscr=$k00_inscr");
					db_fieldsmemory($result_empre, 0);
				}
		}
		//----------------------------------------------------------------------------------------------------------------

				$pdf->SetFont('Arial', '', 7);
				$pdf->Cell(25, 5, $v07_parcel_ant, $b, 0, "C", $pre);
				$pdf->Cell(22, 5, db_formatar($v07_dtlanc_ant, 'd'), $b, 0, "C", $pre);
				$pdf->Cell(70, 5, $cgm_contrib."-".@$contrib, $b, 0, "L", $pre);
				$pdf->Cell(70, 5,$v07_numcgm_ant."-".@$z01_nome_ant, $b, 0, "L", $pre);
				$pdf->Cell(26, 5,$z01_telef_ant, $b, 0, "C", $pre);
				$pdf->Cell(60, 5, @ $k00_tipo_ant.'-'.@ $k00_descr_ant, $b, 1, "L", $pre);
				$pdf->Cell(33, 5, db_formatar($k00_dtvenc_one,'d'), 0, 0, "C", $pre);
				//$pdf->Cell(30, 5, @ $v07_totpar_ant, $b, 0, "C", $pre);
				$pdf->Cell(30, 5, @ $k00_numtot_ant, $b, 0, "C", $pre);
				$pdf->Cell(30, 5, @ $par_pag, $b, 0, "C", $pre);
				$pdf->Cell(30, 5, $par_aber, $b, 0, "C", $pre);
				$pdf->Cell(30, 5, @ $par_venc, $b, 0, "C", $pre);
				$pdf->Cell(30, 5, db_formatar(@ $v07_valor_ant, 'f'), $b, 0, "R", $pre);
				$pdf->Cell(30, 5, db_formatar(@ $val_pag, 'f'), $b, 0, "R", $pre);
				$pdf->Cell(30, 5, db_formatar(@ $val_aber, 'f'), $b, 0, "R", $pre);
				$pdf->Cell(30, 5, db_formatar(@ $val_venc, 'f'), $b, 1, "R", $pre);				

			}

			$totalparcvenc += $par_venc;
			$totalparcpag += $par_pag;
			$totalparcaber += $par_aber;
			$totalvenc += $val_venc;
			$totalpag += $val_pag;
			$totalaber += $val_aber;
			$totalparc += $k00_numtot_ant;
			$totalval += $v07_valor_ant;
			$totalreg += 1;
			if ($vencido == true) {
				$parc_venc ++;
			} else {
				$parc_dia ++;
			}
			$vencido = false;
		}		
		$par_pag = "0";
		$par_aber = "0";
		$par_venc = "0";
		$val_pag = "0";
		$val_venc = "0";
		$val_aber = "0";
		$k00_dtvenc_one = "";
		if ($k00_dtvenc < date('Y-m-d', db_getsession("DB_datausu"))) {
			$par_venc += 1;
			$val_venc += $total;
			if ($k00_dtvenc_one==""){
				$k00_dtvenc_one = $k00_dtvenc;
			}
		} else {
			$valor_dia += $total;
			$par_aber ++;
			$val_aber += $total;
		}		
		$numpre_ant = $numpre;
		$v07_parcel_ant = $v07_parcel;
		$k00_tipo_ant = $k00_tipo;
		$k00_descr_ant = $k00_descr;
		$z01_nome_ant = $z01_nome;
		$z01_telef_ant = $z01_telef;
		$v07_numcgm_ant = $v07_numcgm;
		$v07_dtlanc_ant = $v07_dtlanc;
		//$v07_totpar_ant = $v07_totpar;
		$k00_numtot_ant = $k00_numtot;
		$v07_valor_ant = $v07_valor;
	}
}
if ($totalreg == 0) {
	db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
}
if (strpos($grafico, "R") > 0) {
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->Ln(15);
	$pdf->Cell(148, 7, 'TOTAL DE PARCELAMENTOS : ', 'T', 0, "R", 0);
	$pdf->Cell(25, 7, $totalreg, 'T', 0, "R", 0);
	$pdf->Cell(25, 7, "PARCELAS:", 'T', 0, "R", 0);
	$pdf->Cell(25, 7, $totalparc, 'T', 0, "R", 0);
	$pdf->Cell(25, 7, "VALOR:", 'T', 0, "R", 0);
	$pdf->Cell(25, 7, db_formatar($totalval, 'f'), 'T', 1, "R", 0);
	$pdf->Cell(148, 7, 'TOTAL VALOR  PAGO :', 'T', 0, "R", 0);
	$pdf->Cell(25, 7, db_formatar($totalpag, 'f'), 'T', 0, "R", 0);
	$pdf->Cell(25, 7, "Ñ VENCIDAS:", 'T', 0, "R", 0);
	$pdf->Cell(25, 7, db_formatar($totalaber, 'f'), 'T', 0, "R", 0);
	$pdf->Cell(25, 7, "VENCIDO:", 'T', 0, "R", 0);
	$pdf->Cell(25, 7, db_formatar($totalvenc, 'f'), 'T', 1, "R", 0);
	$pdf->Cell(148, 7, 'TOTAL PARCELAS  PAGO :', 'T', 0, "R", 0);
	$pdf->Cell(25, 7, $totalparcpag, 'T', 0, "R", 0);
	$pdf->Cell(25, 7, "Ñ VENCIDAS:", 'T', 0, "R", 0);
	$pdf->Cell(25, 7, $totalparcaber, 'T', 0, "R", 0);
	$pdf->Cell(25, 7, "VENCIDO:", 'T', 0, "R", 0);
	$pdf->Cell(25, 7, $totalparcvenc, 'T', 1, "R", 0);
}

if (strpos($grafico, "G") > 0) {
	/*
			$sql1 = 
	       "
	 select k00_tipo,
	        k00_descr,
	        round(sum(valor_vencidos),2) as total,
		  round(sum(valor_tudo),2) as tudo
	 from 
	      (select v07_parcel,
	              v07_numcgm,
	 	        z01_nome,
	              x.k00_tipo,
		        k00_descr,
		        k00_numpre,
	              k00_numtot,
	              case when matric is not null 
		             then 'Matr. '||matric 
	 				 	 
					 	        else case when inscr is not null
	 				                    then 'Inscr. '||inscr
	  	     else 'Numcgm '||numcgm
	              end
	        end as matinscr,
	             sum(case when k00_dtvenc <  ".$datausu." then 1 else 0 end) as vencidas,
	             sum(case when k00_dtvenc >= ".$datausu." then 1 else 0 end) as emdia, 
	        sum(valor_tudo) as valor_tudo,
	        sum(valor_vencidos) as valor_vencidos
	      from (select distinct v07_parcel,
	                       v07_numcgm,
	                            k00_tipo, 
	 	  	      k00_numpre,
	                            k00_numpar,
	                            k00_dtvenc,     
	                            k00_numtot
	                       from arrecad inner join termo  on k00_numpre = v07_numpre 
	 				             where v07_dtlanc between '$datai' and '$dataf' 
					             $tipo )as x
	       inner join debitos on k22_numpre = k00_numpre and k22_data = '$data'
	       inner join cgm on v07_numcgm = z01_numcgm
	       inner join arretipo a on a.k00_tipo = x.k00_tipo 
	      group by v07_parcel,
	          v07_numcgm,
	 	 z01_nome,
	               x.k00_tipo,
	 	 k00_descr,
	               k00_numpre,
	 				                k00_numtot,
					  	 matinscr
	 				  $xparcelas
	 				  	 ) as y
	 				  group by k00_tipo,k00_descr
	 				      ";
	 				      echo $sql1;exit;
	 				
	 				 $sql1 = "
	   select k00_tipo,
	     k00_descr,
	     round(sum(valor_vencidos),2) as total,
	     round(sum(valor_tudo),2) as tudo
	   from (
	 	  select distinct z.*,
	 		 arrecad.k00_tipo,
	 		 k00_numtot,
	 				  		 k00_descr,
					  		 case when matric is not null 
	 				  		      then 'Matr. '||matric 
	 	   
	 		 else case when inscr is not null
	 			   then 'Inscr. '||inscr
	 		      else 'Numcgm '||numcgm
	 		      end
	 		 end as matinscr,
	 		 case when matric is not null 
	 		      then (select z01_nome from proprietario_nome where j01_matric = matric limit 1)
	 		 else case when inscr is not null 
	 			   then (select z01_nome from empresa where q02_inscr = inscr limit 1) 
	 			   else (select z01_nome from cgm where numcgm = z01_numcgm limit 1)
	 		      end
	 		 end as proprietario,
	 		 sum(case when k00_dtvenc <  '".$datausu."' then 1 else 0 end) as vencidas,
	 		 sum(case when k00_dtvenc >= '".$datausu."' then 1 else 0 end) as emdia
	 	  from (
	 				  		  select y.*,
				 sum(valor_tudo) as valor_tudo,
	 			 sum(valor_vencidos) as valor_vencidos
	 		  from (
	 			  select v07_parcel,
	 				 v07_numpre
	 			  from (select distinct v07_parcel,
	 						v07_numpre
	 					   from arrecad inner join termo  on k00_numpre = v07_numpre 
	 				where k00_numcgm <> 8639 and v07_dtlanc between '$datai' and '$dataf' 
	 				$tipo ) as x
	 				inner join debitos on k22_numpre = x.v07_numpre and k22_data = '$data'
	 				  				inner join arrecad on arrecad.k00_numpre = x.v07_numpre
					  			  group by v07_parcel,
	 				  				   v07_numpre
	 				  			  $xparcelas) as y
	 				  		  inner join debitos on k22_numpre = y.v07_numpre and k22_data = '$data'
	 				  		  group by v07_parcel,
	 				  			   v07_numpre) as z
	 				  	  inner join arrecad on arrecad.k00_numpre = z.v07_numpre
	 	  inner join debitos on k22_numpre = z.v07_numpre and k22_data = '$data'
	 	  inner join arretipo on arretipo.k00_tipo = debitos.k22_tipo
	 	  group by v07_parcel, v07_numpre, z.valor_tudo, z.valor_vencidos, arrecad.k00_tipo, k00_numtot, k00_descr, matric, inscr, numcgm
	 	  order by z.valor_tudo desc) as a
	   group by k00_tipo,k00_descr order by tudo desc
	
	  ";
	  
	//echo $sql1;exit;
	$sql1 = "select k22_tipo as k00_tipo, k00_descr,
				   			  			    sum(k22_vlrcor+k22_juros+k22_multa-k22_desconto) as tudo,
												 sum(k22_vlrcor+k22_juros+k22_multa-k22_desconto) as total
											from debitos inner join termo on k22_numpre = v07_numpre
				                                  inner join arretipo on k22_tipo = k00_tipo
						      	     where v07_dtlanc between '$datai' and '$dataf' and k22_data = '$data'
						      	                 $tipo group by k22_tipo, k00_descr $xparcelas";
	$result1 = pg_exec($sql1);
	
	/*$pdf->addpage("L");
	$pdf->SetFont('Arial','B',20);
	$pdf->Cell(170,10,'TOTALIZAÇÃO POR TIPO DE DÉBITO',1,1,"C",1);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(20,5,'TIPO',1,0,"C",1);
	$pdf->Cell(80,5,'DESCRIÇÃO',1,0,"C",1);
	$pdf->Cell(35,5,'VALOR VENCIDAS',1,0,"C",1);
	$pdf->SetFont('Arial','',9);
	$pdf->cell(35,5,'VALOR TOTAL',1,1,"L",1);
	$total_total=0;
	$total_tudo=0;
	for($i=0;$i<pg_numrows($result1);$i++){
	  	db_fieldsmemory($result1,$i);
	  	$pdf->Cell(20,5,$k00_tipo,1,0,"C",0);
	  	$pdf->Cell(80,5,$k00_descr,1,0,"C",0);
	  	$pdf->Cell(35,5,db_formatar($total,'f'),1,0,"R",0);
	  	$pdf->cell(35,5,db_formatar($tudo,'f'),1,1,"R",0);
	  	$total_total+=$total;
	  	$total_tudo +=$tudo;
	}	
	
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(100,5,"TOTAL",1,0,"C",0);
	$pdf->Cell(35,5,db_formatar($total_total,'f'),1,0,"R",0);
	$pdf->cell(35,5,db_formatar($total_tudo,'f'),1,1,"R",0);
	*/
	//$pdf->addpage("L");
	$data = array ();
	$data1 = array ();
	$data2 = array ();
	$data3 = array ();
	$data4 = array ();
	$col = array ();
	$cor = 240;
	/*
	for ($i = 0; $i < pg_numrows($result1); $i ++) {
		$data[pg_result($result1, $i, 'k00_descr')] = pg_result($result1, $i, 'total');
		$data1[pg_result($result1, $i, 'k00_descr')] = pg_result($result1, $i, 'tudo');
		$cor -= 20;
		if ($cor < 80)
			$cor = 248;
		$col[$i] = array ($cor, $cor, $cor);
	
	}
	/*
	$pdf->SetFont('Arial', 'BIU', 10);
	$pdf->Cell(0, 5, 'Gráfico dos Valores Vencidos', 0, 1);
	//$pdf->Ln(8);
	
	$pdf->SetFont('Arial', '', 6);
	$valX = $pdf->GetX();
	$valY = $pdf->GetY();
	$pdf->SetXY(10, $valY+5);
	$pdf->PieChart(180, 60, $data, '%l - %v - (%p)', $col);
	$pdf->SetXY($valX, $valY + 40);
	
	//$pdf->addpage("L");
	$pdf->ln(30);			   
	$pdf->SetFont('Arial', 'BIU', 10);
	$pdf->Cell(0, 5, 'Gráfico da Dívida Total', 0, 1);
	//$pdf->Ln(8);
	
	$pdf->SetFont('Arial', '', 6);
	$valX = $pdf->GetX();
	$valY = $pdf->GetY();
	$pdf->SetXY(10, $valY+10);
	$pdf->PieChart(180, 60, $data1, '%l - %v - (%p)', $col);
	$pdf->SetXY($valX, $valY + 40);
	            	*/
	if ($parc_dia != 0 || $parc_venc != 0) {
		$cor -= 20;
		if ($cor < 80)
			$cor = 248;
		$col[0] = array ($cor, $cor, $cor);
		$cor -= 20;
		if ($cor < 80)
			$cor = 248;
		$col[1] = array ($cor, $cor, $cor);
		/*
		$pdf->addpage("L");
		//$pdf->ln(40);			   
		$pdf->SetFont('Arial', 'BI', 15);
		$pdf->Cell(0, 15, 'Estatisticas dos Parcelamentos(Quantidade)', 0, 1, "C", 0);
		//$pdf->Ln(8);
		$pdf->SetFont('Arial', 'BIU', 10);
		$data2["Parcelamentos em dia"] = $parc_dia;
		$data2["Parcelamentos com parcelas vencidas"] = $parc_venc;
		$pdf->SetFont('Arial', '', 6);
		$valX = $pdf->GetX();
		$valY = $pdf->GetY();
		$pdf->SetXY(10, $valY +10);
		//$pdf->BarDiagram(200, 50, $data2, '%l - %v - (%p)');
		$pdf->PieChart(200, 50, $data2, '%l - %v - (%p)', $col);
		$pdf->SetXY($valX, $valY +40);
		//$pdf->addpage("L");
		$pdf->ln(20);
		$pdf->SetFont('Arial', 'BI', 15);
		$pdf->Cell(0, 15, 'Estatisticas dos Parcelamentos(Valor)', 0, 1, "C", 0);
		//$pdf->Ln(8);
		$pdf->SetFont('Arial', 'BIU', 10);
		$data3["Parcelamentos em dia"] = $valor_dia;
		$data3["Parcelamentos com parcelas vencidas"] = $totalvenc;
		$pdf->SetFont('Arial', '', 6);
		$valX = $pdf->GetX();
		$valY = $pdf->GetY();
		$pdf->SetXY(10, $valY +10);
		$pdf->PieChart(200, 50, $data3, '%l - %v - (%p)', $col);
		$pdf->SetXY($valX, $valY +40);
		*/
		//-----------Grafico em coluna

		$pdf->addpage("L");
		//$pdf->ln(40);			   
		$pdf->SetFont('Arial', 'BI', 15);
		$pdf->Cell(0, 15, 'Estatisticas das Parcelas(Quantidade)', 0, 1, "C", 0);
		//$pdf->Ln(8);

		$pdf->SetFont('Arial', 'BIU', 10);
		//			$data4["Total de Parcelas"] = $totalparc;
		//$data4["Em dia"]=$totalparcdia; 
		$data4["Pagas"] = $totalparcpag;
		//		$data4["Vencidas"] = $totalparcvenc;
		$data4["Em aberto"] = $totalparcaber+$totalparcvenc;
		$pdf->SetFont('Arial', '', 6);
		$valX = $pdf->GetX();
		$valY = $pdf->GetY();
		$pdf->SetXY(10, $valY +10);
		$pdf->BarDiagram(200, 50, $data4, '%l - %v - (%p)');
		//$pdf->PieChart(200, 50, $data2, '%l - %v - (%p)', $col);
		/*
		$pdf->SetFont('Arial', 'B', 10);
		$pdf->Cell(0, 5, 'TOTAL DE PARCELAS : '.$totalparc, 0,1, "L", 0);
		$pdf->Cell(0, 5, 'PAGAS : '.$totalparcpag, 0,1, "L", 0);
		$pdf->Cell(0, 5, 'VENCIDAS : '.$totalparcvenc, 0,1, "L", 0);
		$pdf->Cell(0, 5, 'EM ABERTO : '.$totalparcaber, 0,1, "L", 0);
		*/
		$pdf->SetXY($valX, $valY +40);
		//$pdf->addpage("L");
		$pdf->ln(20);
		$pdf->SetFont('Arial', 'BI', 15);
		$pdf->Cell(0, 15, 'Estatisticas das Parcelas(Valor)', 0, 1, "C", 0);
		//$pdf->Ln(8);

		$pdf->SetFont('Arial', 'BIU', 10);
		//	$data4["Total de Parcelas"] = $totalval;
		//$data4["Em dia"]=$totalparcdia; 
		$data4["Pagas"] = $totalpag;
		//$data4["Vencidas"] = $totalvenc;
		$data4["Em aberto"] = $totalaber+$totalvenc;

		$pdf->SetFont('Arial', '', 6);
		$valX = $pdf->GetX();
		$valY = $pdf->GetY();
		$pdf->SetXY(10, $valY +10);
		$pdf->BarDiagram(200, 50, $data4, '%l - %v - (%p)');
		//$pdf->PieChart(200, 50, $data2, '%l - %v - (%p)', $col);

		/*
		$pdf->SetFont('Arial', 'B', 10);
		$pdf->Cell(0, 5, 'TOTAL DE PARCELAS : '.db_formatar($totalval,'f'), 0,1, "L", 0);
		$pdf->Cell(0, 5, 'PAGAS : '.db_formatar($totalpag,'f'), 0,1, "L", 0);
		$pdf->Cell(0, 5, 'VENCIDAS : '.db_formatar($totalvenc,'f'), 0,1, "L", 0);
		$pdf->Cell(0, 5, 'EM ABERTO : '.db_formatar($totalaber,'f'), 0,1, "L", 0);
		*/
		$pdf->SetXY($valX, $valY +40);
		//$pdf->addpage("L");
	}
}
$pdf->Output();
?>





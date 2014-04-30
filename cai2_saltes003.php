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


include ("fpdf151/pdf.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$head3 = "Relatorio de Saldo da Tesouraria";
$head5 = "DATA: $datai_dia/$datai_mes/$datai_ano ";
$head6 = "TIPO:  $tipo";

$anousu = db_getsession("DB_anousu");
$instit = db_getsession("DB_instit");

$pdf = new PDF; // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage("L"); // adiciona uma pagina
$pdf->SetFont('Arial', '', 10); // seta a fonte do relatorio
$alt = 4;
$pdf->setY(40);

if ($tipo == "conta") {
	$pdf->Cell(20, $alt, "CODIGO", "LRTB", 0, "C", 0);
	$pdf->Cell(100, $alt, "DESCRICAO", "LRTB", 0, "C", 0);
	$pdf->Cell(35, $alt, "SALD.ANTERIOR", "LRTB", 0, "C", 0);
	$pdf->Cell(35, $alt, "VLR.DEBITO", "LRTB", 0, "C", 0);
	$pdf->Cell(35, $alt, "VLR.CREDITO", "LRTB", 0, "C", 0);
	$pdf->Cell(35, $alt, "SALDO ATUAL", "LRTB", 1, "C", 0);

} else {
	$pdf->Cell(20, $alt, "R.", "LRTB", 0, "C", 0);
	$pdf->Cell(100, $alt, "DESCRICAO", "LRTB", 0, "C", 0);
	$pdf->Cell(35, $alt, "SALD.ANTERIOR", "LRTB", 0, "C", 0);
	$pdf->Cell(35, $alt, "VLR.DEBITO", "LRTB", 0, "C", 0);
	$pdf->Cell(35, $alt, "VLR.CREDITO", "LRTB", 0, "C", 0);
	$pdf->Cell(35, $alt, "SALDO ATUAL", "LRTB", 1, "C", 0);
}
$totval1 = 0;
$totval2 = 0;
$totval3 = 0;
$totval4 = 0;
$tval1 = 0;
$tval2 = 0;
$tval3 = 0;
$tval4 = 0;

if ($tipo == "conta") {
	$sql = "select k13_conta,k13_descr,c60_estrut,c61_codigo,o15_descr
			              from saltes 
			 	          inner join conplanoexe on c62_anousu = ".db_getsession("DB_anousu")." and c62_reduz = k13_conta
				          inner join conplanoreduz on c61_anousu = ".db_getsession("DB_anousu")." and 
                                                                       c61_reduz = c62_reduz and 
                                                                       c61_instit = ".db_getsession("DB_instit")."                                                                         
				          inner join conplano on c61_codcon = c60_codcon and c61_anousu = c60_anousu
				          inner join orctiporec on o15_codigo = c61_codigo  
					  where c60_codsis in (5,6)
				      order by k13_descr";

} else
	if ($tipo == "instituicao") {
		$sql = "select db90_codban,db90_descr
						    from saltes 
							   inner join conplanoexe on c62_anousu = ".db_getsession("DB_anousu")." and c62_reduz = k13_conta
							   inner join conplanoreduz on c61_anousu = ".db_getsession("DB_anousu")." and 
                                                                            c61_reduz = c62_reduz and 
                                                                            c61_instit = ".db_getsession("DB_instit")."
							   inner join conplano on c61_codcon = c60_codcon and c61_anousu=c60_anousu
							   inner join orctiporec on o15_codigo = c61_codigo
				               inner join conplanoconta on c63_codcon = conplano.c60_codcon and c63_anousu=c60_anousu                                                  
				               inner join db_bancos on db90_codban = conplanoconta.c63_banco
							where c60_codsis in (5,6)
				            group by db90_codban, db90_descr
						    order by db90_codban";

	} else {
		$sql = "select c61_codigo,o15_descr
					              from saltes 
					 	          inner join conplanoexe on c62_anousu = ".db_getsession("DB_anousu")." and c62_reduz = k13_conta
						          inner join conplanoreduz on c61_anousu = ".db_getsession("DB_anousu")." and 
             											   c61_reduz = c62_reduz  and
          										   c61_instit = ".db_getsession("DB_instit")."
						          inner join conplano on c61_codcon = c60_codcon and c61_anousu=c60_anousu
						          inner join orctiporec on o15_codigo = c61_codigo  
							where c60_codsis in (5,6)
					              group by c61_codigo, o15_descr
						      order by c61_codigo ";
	}
$result = db_query($sql);
if (empty ($datai_dia)) {
	$datai_dia = date('d', db_getsession("DB_datausu"));
	$datai_mes = date('m', db_getsession("DB_datausu"));
	$datai_ano = date('Y', db_getsession("DB_datausu"));
}
for ($i = 0; $i < pg_numrows($result); $i ++) {
	db_fieldsmemory($result, $i);
	if ($tipo == "conta") {
		$result1 = db_query("select fc_saltessaldo($k13_conta,'$datai_ano-$datai_mes-$datai_dia','$datai_ano-$datai_mes-$datai_dia',null,".db_getsession("DB_instit").")");
		$valor = pg_result($result1, 0, 0);
		$valor = preg_split("/\s+/", $valor);

		$pdf->Cell(20, $alt, "$k13_conta", "LRTB", 0, "C", 0);
		$pdf->Cell(100, $alt, "$k13_descr", "LRTB", 0, "L", 0);
		if ($valor[0] == "2")
			$pdf->Cell(20, $alt, "Nada no Corrente", "LRTB", 0, "L", 0);
		else
			if ($valor[0] == "3")
				$pdf->Cell(20, $alt, "Nada no cfautent", "LRTB", 0, "L", 0);
			else {
				$pdf->Cell(35, $alt, db_formatar($valor[1], 'f'), "LRTB", 0, "R", 0);
				$pdf->Cell(35, $alt, db_formatar($valor[2], 'f'), "LRTB", 0, "R", 0);
				$pdf->Cell(35, $alt, db_formatar($valor[3], 'f'), "LRTB", 0, "R", 0);
				$pdf->Cell(35, $alt, db_formatar($valor[4], 'f'), "LRTB", 0, "R", 0);

				$totval1 += (float) str_replace(",", "", $valor[1]);
				$totval2 += (float) str_replace(",", "", $valor[2]);
				$totval3 += (float) str_replace(",", "", $valor[3]);
				$totval4 += (float) str_replace(",", "", $valor[4]);
			}
		$pdf->Ln();
	} else
		if ($tipo == "recurso") { // tipo = recurso
			// imprime recurso e totaliza contas
			$pdf->Cell(20, $alt, "$c61_codigo", "LRTB", 0, "C", 0);
			$pdf->Cell(100, $alt, "$o15_descr", "LRTB", 0, "L", 0);
			$sql = "select k13_conta
									                 from saltes 
										               inner join conplanoexe on c62_anousu = ".db_getsession("DB_anousu")."
											                          and c62_reduz = k13_conta
										               inner join conplanoreduz on c61_anousu =".db_getsession("DB_anousu")." and 
        																c61_reduz = c62_reduz and 
																	c61_instit = ".db_getsession("DB_instit")."
										               inner join conplano on c61_codcon = c60_codcon and c61_anousu=c60_anousu
										               inner join orctiporec on o15_codigo = c61_codigo  
									                 where orctiporec.o15_codigo = $c61_codigo 
									                 		and c60_codsis in (5,6) 
											 order by k13_conta";
			$result_contas = db_query($sql);
			$nrows = pg_numrows($result_contas);
			for ($h = 0; $h < $nrows; $h ++) {
				db_fieldsmemory($result_contas, $h);
				$result1 = db_query("select fc_saltessaldo($k13_conta,'$datai_ano-$datai_mes-$datai_dia','$datai_ano-$datai_mes-$datai_dia',null, ".db_getsession("DB_instit").")");
				$valor = pg_result($result1, 0, 0);
				$valor = preg_split("/\s+/", $valor);
				if ($valor[0] != "2" || $valor[0] != "3") {
					$tval1 += (float) str_replace(",", "", $valor[1]);
					$tval2 += (float) str_replace(",", "", $valor[2]);
					$tval3 += (float) str_replace(",", "", $valor[3]);
					$tval4 += (float) str_replace(",", "", $valor[4]);
				}
			}
			$pdf->Cell(35, $alt, db_formatar($tval1, 'f'), "LRTB", 0, "R", 0);
			$pdf->Cell(35, $alt, db_formatar($tval2, 'f'), "LRTB", 0, "R", 0);
			$pdf->Cell(35, $alt, db_formatar($tval3, 'f'), "LRTB", 0, "R", 0);
			$pdf->Cell(35, $alt, db_formatar($tval4, 'f'), "LRTB", 0, "R", 0);

			$pdf->Ln();

			$totval1 += $tval1;
			$totval2 += $tval2;
			$totval3 += $tval3;
			$totval4 += $tval4;
			$tval1 = 0;
			$tval2 = 0;
			$tval3 = 0;
			$tval4 = 0;

		} else
			if ($tipo == "instituicao") {
				// quebra por bancos e lista as contas abaixo				
				$sql = "   select k13_conta, 
								          k13_descr, 
								          c61_codigo,
								          fc_saltessaldo(k13_conta,
								                                 '$datai_ano-$datai_mes-$datai_dia',
								                                 '$datai_ano-$datai_mes-$datai_dia',
								                                  null,
								                                  ".$instit.")   as valor
								from saltes 
										    inner join conplanoexe on c62_anousu = ".$anousu."
								                        and c62_reduz = k13_conta
								            inner join conplanoreduz on c61_anousu = ".$anousu." and 
       												 c61_reduz = c62_reduz and 
                                                                                         c61_instit = ".$instit."
										    inner join conplano on c61_codcon = c60_codcon and c61_anousu=c60_anousu
										    inner join orctiporec on o15_codigo = c61_codigo
								            inner join conplanoconta on c63_codcon = conplano.c60_codcon and c63_anousu=c60_anousu
								            inner join db_bancos on trim(db90_codban) = conplanoconta.c63_banco::varchar(10)  
							  where trim(db_bancos.db90_codban)::integer = $db90_codban 
									     and c60_codsis in (5,6)                          
							  order by k13_descr								                      
								              ";								 
				$result_contas = db_query($sql);
				$nrows = pg_numrows($result_contas);

				// db_criatabela($result_contas); exit;
				 
				$tot_livre_ant = 0;
				$tot_livre_deb = 0;
				$tot_livre_cre = 0;
				$tot_livre_final = 0;
				$tot_vinculado_ant = 0;
				$tot_vinculado_deb = 0;
				$tot_vinculado_cre = 0;
				$tot_vinculado_final = 0;
				for ($h = 0; $h < $nrows; $h ++) {
					db_fieldsmemory($result_contas, $h);
					$valor = preg_split("/\s+/", $valor);
					if ($c61_codigo == 1) { // totalizamos o recurso livre						
						if ($valor[0] != "2" || $valor[0] != "3") {
							$tot_livre_ant += (float) str_replace(",", "", $valor[1]);
							$tot_livre_deb += (float) str_replace(",", "", $valor[2]);
							$tot_livre_cre += (float) str_replace(",", "", $valor[3]);
							$tot_livre_final += (float) str_replace(",", "", $valor[4]);
						}
					} else { //  agora totalizaremos os valores de recurso vinculado
						if ($valor[0] != "2" || $valor[0] != "3") {
							$tot_vinculado_ant += (float) str_replace(",", "", $valor[1]);
							$tot_vinculado_deb += (float) str_replace(",", "", $valor[2]);
							$tot_vinculado_cre += (float) str_replace(",", "", $valor[3]);
							$tot_vinculado_final += (float) str_replace(",", "", $valor[4]);
						}

					}
				}
				///  END FOR
				$pdf->SetFont('Arial', 'B', 9); // seta a fonte do relatorio
				$pdf->Cell(20, $alt, "$db90_codban", "LRTB", 0, "C", 0);
				$pdf->Cell(100, $alt, "$db90_descr", "LRTB", 0, "L", 0);
				$pdf->Cell(35, $alt, db_formatar(($tot_livre_ant  + $tot_vinculado_ant), 'f'), "LRTB", 0, "R", 0);
				$pdf->Cell(35, $alt, db_formatar(($tot_livre_deb + $tot_vinculado_deb), 'f'), "LRTB", 0, "R", 0);
				$pdf->Cell(35, $alt, db_formatar(($tot_livre_cre  + $tot_vinculado_cre), 'f'), "LRTB", 0, "R", 0);
				$pdf->Cell(35, $alt, db_formatar(($tot_livre_final + $tot_vinculado_final), 'f'), "LRTB", 0, "R", 0);
                $pdf->Ln();
                $totval1 += $tot_livre_ant  + $tot_vinculado_ant;
			    $totval2 += $tot_livre_deb + $tot_vinculado_deb;
			    $totval3 += $tot_livre_cre  + $tot_vinculado_cre;
			    $totval4 += $tot_livre_final + $tot_vinculado_final;
			
                // total livre
                $pdf->SetFont('Arial', '', 8); // seta a fonte do relatorio
                $pdf->Cell(20, $alt, "", "LRTB", 0, "C", 0);
				$pdf->Cell(100, $alt, "TOTAL LIVRE", "LRTB", 0, "L", 0);
                $pdf->Cell(35, $alt, db_formatar($tot_livre_ant , 'f'), "LRTB", 0, "R", 0);
				$pdf->Cell(35, $alt, db_formatar($tot_livre_deb, 'f'), "LRTB", 0, "R", 0);
				$pdf->Cell(35, $alt, db_formatar($tot_livre_cre  , 'f'), "LRTB", 0, "R", 0);
				$pdf->Cell(35, $alt, db_formatar($tot_livre_final , 'f'), "LRTB", 0, "R", 0);
                $pdf->Ln();
                // total vinculado
                $pdf->SetFont('Arial', '', 8); // seta a fonte do relatorio
                $pdf->Cell(20, $alt, "", "LRTB", 0, "C", 0);
				$pdf->Cell(100, $alt, "TOTAL VINCULADO", "LRTB", 0, "L", 0);
                $pdf->Cell(35, $alt, db_formatar( $tot_vinculado_ant, 'f'), "LRTB", 0, "R", 0);
				$pdf->Cell(35, $alt, db_formatar($tot_vinculado_deb, 'f'), "LRTB", 0, "R", 0);
				$pdf->Cell(35, $alt, db_formatar($tot_vinculado_cre, 'f'), "LRTB", 0, "R", 0);
				$pdf->Cell(35, $alt, db_formatar($tot_vinculado_final, 'f'), "LRTB", 0, "R", 0);                			 
				$pdf->Ln();

                // listar contas
                for ($h = 0; $h < $nrows; $h ++) {
					db_fieldsmemory($result_contas, $h);
					$valor = preg_split("/\s+/", $valor);
				    if ($valor[0] != "2" || $valor[0] != "3") {
					   $tval1 = (float) str_replace(",", "", $valor[1]);
					   $tval2 = (float) str_replace(",", "", $valor[2]);
					   $tval3 = (float) str_replace(",", "", $valor[3]);
					   $tval4 = (float) str_replace(",", "", $valor[4]);
					   $pdf->setX(40);
					   $pdf->Cell(90, $alt, "$k13_conta"."$k13_descr", "LRTB", 0, "L", 0);
			           $pdf->Cell(35, $alt, db_formatar($tval1, 'f'), "LRTB", 0, "R", 0);
			           $pdf->Cell(35, $alt, db_formatar($tval2, 'f'), "LRTB", 0, "R", 0);
			           $pdf->Cell(35, $alt, db_formatar($tval3, 'f'), "LRTB", 0, "R", 0);
			           $pdf->Cell(35, $alt, db_formatar($tval4, 'f'), "LRTB", 0, "R", 0);
					   
					   
				   }
                   $pdf->Ln();
                }
                
                /////// fim do lista contas
			} else {
				$pdf->SetFont('Arial', 'B', 10); // seta a fonte do relatorio
				$pdf->Cell(20, $alt, "$c61_codigo", "LRTB", 0, "C", 0);
				$pdf->Cell(100, $alt, "$o15_descr", "LRTB", 0, "L", 0);
				$sql = "select k13_conta, k13_descr, c60_estrut
												             from saltes 
												                inner join conplanoexe on c62_anousu = ".db_getsession("DB_anousu")."
													                          and c62_reduz = k13_conta
												                inner join conplanoreduz on c61_anousu=".$anousu." and 
              															 c61_reduz = c62_reduz and 
																 c61_instit = ".db_getsession("DB_instit")."
												                inner join conplano on c61_codcon = c60_codcon and c61_anousu=c60_anousu
												                inner join orctiporec on o15_codigo = c61_codigo  
												             where orctiporec.o15_codigo = $c61_codigo 
											                    and c60_codsis in (5,6)
												  	         order by k13_descr";
				$result_contas = db_query($sql);
				$nrows = pg_numrows($result_contas);
				for ($h = 0; $h < $nrows; $h ++) {
					db_fieldsmemory($result_contas, $h);
					$result1 = db_query("select fc_saltessaldo($k13_conta,'$datai_ano-$datai_mes-$datai_dia','$datai_ano-$datai_mes-$datai_dia',null, ".db_getsession("DB_instit").")");
					$valor = pg_result($result1, 0, 0);
					$valor = preg_split("/\s+/", $valor);
					if ($valor[0] != "2" || $valor[0] != "3") {
						$tval1 += (float) str_replace(",", "", $valor[1]);
						$tval2 += (float) str_replace(",", "", $valor[2]);
						$tval3 += (float) str_replace(",", "", $valor[3]);
						$tval4 += (float) str_replace(",", "", $valor[4]);
					}
				}
				$pdf->Cell(35, $alt, db_formatar($tval1, 'f'), "LRTB", 0, "R", 0);
				$pdf->Cell(35, $alt, db_formatar($tval2, 'f'), "LRTB", 0, "R", 0);
				$pdf->Cell(35, $alt, db_formatar($tval3, 'f'), "LRTB", 0, "R", 0);
				$pdf->Cell(35, $alt, db_formatar($tval4, 'f'), "LRTB", 0, "R", 0);

				$pdf->SetFont('Arial', '', 10); // seta a fonte do relatorio
				$pdf->Ln();

				$totval1 += $tval1;
				$totval2 += $tval2;
				$totval3 += $tval3;
				$totval4 += $tval4;
				$tval1 = 0;
				$tval2 = 0;
				$tval3 = 0;
				$tval4 = 0;
				/////// lista contas
				for ($h = 0; $h < $nrows; $h ++) {
					db_fieldsmemory($result_contas, $h);
					$result1 = db_query("select fc_saltessaldo($k13_conta,'$datai_ano-$datai_mes-$datai_dia','$datai_ano-$datai_mes-$datai_dia',null,".db_getsession("DB_instit").")");
					$valor = pg_result($result1, 0, 0);
					$valor = preg_split("/\s+/", $valor);

					$pdf->Cell(20, $alt, " ", "", 0, "C", 0);
					$pdf->Cell(100, $alt, "($k13_conta) $k13_descr", "LRTB", 0, "L", 0);
					$pdf->Cell(35, $alt, db_formatar($valor[1], 'f'), "LRTB", 0, "R", 0);
					$pdf->Cell(35, $alt, db_formatar($valor[2], 'f'), "LRTB", 0, "R", 0);
					$pdf->Cell(35, $alt, db_formatar($valor[3], 'f'), "LRTB", 0, "R", 0);
					$pdf->Cell(35, $alt, db_formatar($valor[4], 'f'), "LRTB", 0, "R", 0);
					$pdf->Ln();

				}
			}

}

$pdf->Ln(7);
$pdf->Cell(60, $alt, "Totais", "LRTB", 0, "C", 0);
$pdf->Cell(50, $alt, "Anterior:".db_formatar($totval1, 'f'), "LRTB", 0, "R", 0);
$pdf->Cell(50, $alt, "Debito  :".db_formatar($totval2, 'f'), "LRTB", 0, "R", 0);
$pdf->Cell(50, $alt, "Credito :".db_formatar($totval3, 'f'), "LRTB", 0, "R", 0);
$pdf->Cell(50, $alt, "Atual   :".db_formatar($totval4, 'f'), "LRTB", 0, "R", 0);

$pdf->Output();
?>
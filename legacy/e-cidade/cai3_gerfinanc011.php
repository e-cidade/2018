<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

set_time_limit(0);
include ("libs/db_sql.php");
include ("libs/db_utils.php");
require ("fpdf151/pdf.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$sTiposDebitos = $tipos;
$tipos 		  = split(",", $tipos);

if (isset ($db_datausu)) {
	if (!checkdate(substr($db_datausu, 5, 2), substr($db_datausu, 8, 2), substr($db_datausu, 0, 4))) {
		echo "Data para Cálculo Inválida. <br><br>";
		echo "Data deverá ser superior a : " . date('Y-m-d', db_getsession("DB_datausu"));
		exit;
	}
	if (mktime(0, 0, 0, substr($db_datausu, 5, 2), substr($db_datausu, 8, 2), substr($db_datausu, 0, 4)) < mktime(0, 0, 0, date('m', db_getsession("DB_datausu")), date('d', db_getsession("DB_datausu")), date('Y', db_getsession("DB_datausu")))) {
		echo "Data no permitida para cálculo. <br><br>";
		echo "Data deverá ser superior a : " . date('Y-m-d', db_getsession("DB_datausu"));
		exit;
	}
	$DB_DATACALC = mktime(0, 0, 0, substr($db_datausu, 5, 2), substr($db_datausu, 8, 2), substr($db_datausu, 0, 4));
} else {
	$DB_DATACALC = db_getsession("DB_datausu");
}

$pdf = new pdf();
$pdf->Open();
$pdf->AliasNbPages();
$head2 = "SECRETARIA DA FAZENDA";
$head4 = "Relatório do Total dos Débitos Sintético";
$linha = 60;
$TPagina = 40;

if (isset ($matric)) {
	$result = debitos_tipos_matricula($matric);
	$chave = $matric;
	$sql = "select * from proprietario where j01_matric = $matric limit 1";
	$result1 = pg_exec($sql);
	db_fieldsmemory($result1, 0);
	$nome = $z01_nome;
//	$ender = $j14_tipo . ' ' . $j14_nome . ', ' . $j39_numero . ' ' . $j39_compl;
	$ender = $tipopri . ' ' . $nomepri . ', ' . $j39_numero . ' ' . $j39_compl;

//	die($ender);

	$outros1 = 'REF. ANTER.';
	$outros2 = $j40_refant;
	$outros3 = 'MATRÍCULA';
	@ $outros4 = "Setor: " . $j34_setor . "   Quadra: " . $j34_quadra . "   Lote: " . $j34_lote;

} else
	if (isset ($inscr)) {
		$result = debitos_tipos_inscricao($inscr);
		$chave = $inscr;
		$sql = "select * from empresa where q02_inscr = $inscr";
		$result1 = pg_exec($sql);
		db_fieldsmemory($result1, 0);
		$nome = $z01_nome;
		$ender = $j14_tipo . ' ' . $z01_ender . ', ' . $z01_numero . ' ' . $z01_compl;
		$outros1 = 'ATIVIDADE';
		$outros2 = $q03_descr;
		$outros3 = 'INSCRIÇÃO';
	} else
		if (isset ($numcgm)) {
			$result = debitos_tipos_numcgm($numcgm);
			$chave = $numcgm;
			$sql = "select * from cgm where z01_numcgm = $numcgm";
			$result1 = pg_exec($sql);
			db_fieldsmemory($result1, 0);
			$nome = $z01_nome;
			$ender = $z01_ender . ', ' . $z01_numero . ' ' . $z01_compl;
			$outros1 = '';
			$outros2 = '';
			$outros3 = 'CGM';
		} else
			if (isset ($numpre)) {
				$result = debitos_tipos_numpre($numpre);
				$chave = $numpre;
				$sql = "select cgm.* 
				          from arrecad inner join cgm on z01_numcgm = k00_numcgm 
				               inner join arreinstit  on arreinstit.k00_numpre = arrecad.k00_numpre 
											                       and arreinstit.k00_instit = ".db_getsession('DB_instit')." 
         				 where arrecad.k00_numpre = $numpre limit 1";
				$result1 = pg_exec($sql);
				db_fieldsmemory($result1, 0);
				$nome = $z01_nome;
				$ender = $z01_ender . ', ' . $z01_numero . ' ' . $z01_compl;
				$outros1 = '';
				$outros2 = '';
				$outros3 = 'NUMPRE';
			} else {
				$chave = 0;
			}
$where = "";
$and = " and ";
if ($parReceit != ''){
	if(isset($numpre)){
 	 $where .= $where." y.k00_receit in($parReceit)"; 	
	}else{
     $where .= $where.$and." y.k00_receit in($parReceit)";		
	}
}

if (($dtini != "--") && ($dtfim != "--")) {
	$where = $where. $and. " k00_dtoper  between '$dtini' and '$dtfim'  ";
	$dtini = db_formatar($dtini, "d");
	$dtfim = db_formatar($dtfim, "d");
	$info = "De $dtini até $dtfim.";
	$and = " and ";
} else if ($dtini != "--") {
	$where = $where. $and. " k00_dtoper >= '$dtini'  ";
	$dtini = db_formatar($dtini, "d");
	$info = "Apartir de $dtini.";
	$and = " and ";
} else if ($dtfim != "--") {
	$where = $where. $and. " k00_dtoper <= '$dtfim'   ";
	$dtfim = db_formatar($dtfim, "d");
	$info = "Até $dtfim.";
	$and = " and ";
}
if (($exercini != "") && ($exercfim != "")) {
	$where = $where. $and. " fc_arrecexerc(y.k00_numpre,y.k00_numpar)  between '$exercini' and '$exercfim'  ";	
	$info1 = "Do exercício $exercini até $exercfim.";
	$and = " and ";
} else if ($exercini != "") {
	$where = $where. $and. " fc_arrecexerc(y.k00_numpre,y.k00_numpar) >= '$exercini'  ";	
	$info1 = "Apartir do exercício $exercini.";
	$and = " and ";
} else if ($exercfim != "") {
	$where = $where. $and. " fc_arrecexerc(y.k00_numpre,y.k00_numpar) <= '$exercfim'   ";	
	$info1 = "Até o exercício $exercfim.";
	$and = " and ";
}
$head5 = @$info;
$head6 = @$info1;

if ($chave != 0) {
	if ($result != false && pg_numrows($result) > 0) {
		$cor = "#EFE029";
		$ttvlrhis = 0;
		$ttvlrcor = 0;
		$ttvlrjuros = 0;
		$ttvlrmulta = 0;
		$ttvlrdesconto = 0;
		$tttotal = 0;
		for ($x = 0; $x < pg_numrows($result); $x++) {
			db_fieldsmemory($result, $x, true);
							
			if (in_array($k00_tipo, $tipos) == true) {

				if (isset ($matric)) {
					$debitos = debitos_matricula($matric, 0, $k00_tipo, $DB_DATACALC, db_getsession("DB_anousu"),"","",$where);
				} else if (isset ($inscr)) {
					$debitos = debitos_inscricao($inscr, 0, $k00_tipo, $DB_DATACALC, db_getsession("DB_anousu"),"","",$where);
				} else if (isset ($numcgm)) {
					$debitos = debitos_numcgm($numcgm, 0, $k00_tipo, $DB_DATACALC, db_getsession("DB_anousu"),"","",$where);
				} else if (isset ($numpre)) {
					$debitos = debitos_numpre($numpre, 0, $k00_tipo, $DB_DATACALC, db_getsession("DB_anousu"),"","",$where);
				} else {
				  break;
				}
										
				if ($debitos==false||$debitos==1){
					continue;
				}			
				
				if (pg_numrows($debitos) > 0) {
					$tvlrhis = 0;
					$tvlrcor = 0;
					$tvlrjuros = 0;
					$tvlrmulta = 0;
					$tvlrdesconto = 0;
					$ttotal = 0;
					for ($xx = 0; $xx < pg_numrows($debitos); $xx++) {
						db_fieldsmemory($debitos, $xx);
						$tvlrhis += $vlrhis;
						$tvlrcor += $vlrcor;
						$tvlrjuros += $vlrjuros;
						$tvlrmulta += $vlrmulta;
						$tvlrdesconto += $vlrdesconto;
						$ttotal += $total;
					}
					$ttvlrhis += $tvlrhis;
					$ttvlrcor += $tvlrcor;
					$ttvlrjuros += $tvlrjuros;
					$ttvlrmulta += $tvlrmulta;
					$ttvlrdesconto += $tvlrdesconto;
					$tttotal += $ttotal;
					if ($cor == "#EFE029")
						$cor = "#E4F471";
					else
						if ($cor == "#E4F471")
							$cor = "#EFE029";
					if (in_array($k00_tipo, $tipos) == true) {
						if ($linha++ > $TPagina) {
							$linha = 0;
							$pdf->AddPage();
							$pdf->SetFillColor(235);
							$pdf->SetLineWidth(0.5);
							$pdf->Ln(3);
							$pdf->Cell(191, 2, '', "T", 1, "R", 0);
							$pdf->SetFont('Arial', 'B', 8);
							$pdf->Cell(25, 5, $outros3, 0, 0, "L", 0);
							$pdf->SetFont('Arial', 'I', 8);
							$pdf->Cell(80, 5, ': ' . $chave . '    ' . @ $outros4, 0, 1, "L", 0);
							$pdf->SetFont('Arial', 'B', 8);
							$pdf->Cell(25, 5, "NOME", 0, 0, "L", 0);
							$pdf->SetFont('Arial', 'I', 8);
							$pdf->Cell(80, 5, ': ' . $nome, 0, 1, "L", 0);
							$pdf->SetFont('Arial', 'B', 8);
							$pdf->Cell(25, 5, "ENDEREÇO", 0, 0, "L", 0);
							$pdf->SetFont('Arial', 'I', 8);
							$pdf->Cell(80, 5, ': ' . $ender, 0, 1, "L", 0);
							if ($outros1 != '') {
								$pdf->SetFont('Arial', 'B', 8);
								$pdf->Cell(25, 5, $outros1, 0, 0, "L", 0);
								$pdf->SetFont('Arial', 'I', 8);
								$pdf->Cell(80, 5, ': ' . $outros2, 0, 1, "L", 0);
							}
							$pdf->SetFont('Arial', 'BI', 12);
							$pdf->Cell(191, 2, '', "B", 1, "R", 0);
							$pdf->MultiCell(0, 20, "Valores Válidos Até a Data : " . db_formatar(date('Y-m-d', $DB_DATACALC), 'd'), 0, "C", 0);
							$pdf->SetLineWidth(0.2);
							$pdf->SetFont('Arial', 'B', 8);
							$pdf->Cell(7, 05, "Tipo", 1, 0, "C", 1);
							$pdf->Cell(60, 05, "Descrição", 1, 0, "C", 1);
							$pdf->Cell(20, 05, "Vlr Histórico", 1, 0, "C", 1);
							$pdf->Cell(20, 05, "Vlr Corrigido", 1, 0, "C", 1);
							$pdf->Cell(20, 05, "Vlr Juros", 1, 0, "C", 1);
							$pdf->Cell(20, 05, "Vlr Multa", 1, 0, "C", 1);
							$pdf->Cell(20, 05, "Vlr Desconto", 1, 0, "C", 1);
							$pdf->Cell(20, 05, "Vlr Total", 1, 1, "C", 1);
						}
					}
					$pdf->SetFont('Arial', '', 8);
					$pdf->Cell(7, 05, $k00_tipo, 1, 0, "C", 0);
					$pdf->Cell(60, 05, substr($k00_descr, 0, 35), 1, 0, "L", 0);
					$pdf->Cell(20, 05, db_formatar($tvlrhis, 'f'), 1, 0, "R", 0);
					$pdf->Cell(20, 05, db_formatar($tvlrcor, 'f'), 1, 0, "R", 0);
					$pdf->Cell(20, 05, db_formatar($tvlrjuros, 'f'), 1, 0, "R", 0);
					$pdf->Cell(20, 05, db_formatar($tvlrmulta, 'f'), 1, 0, "R", 0);
					$pdf->Cell(20, 05, db_formatar($tvlrdesconto, 'f'), 1, 0, "R", 0);
					$pdf->Cell(20, 05, db_formatar($ttotal, 'f'), 1, 1, "R", 0);
				}
			}
		}
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(7, 05, "", 1, 0, "L", 0);
		$pdf->Cell(60, 05, "Total", 1, 0, "C", 0);
		$pdf->Cell(20, 05, db_formatar($ttvlrhis, 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, 05, db_formatar($ttvlrcor, 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, 05, db_formatar($ttvlrjuros, 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, 05, db_formatar($ttvlrmulta, 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, 05, db_formatar($ttvlrdesconto, 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, 05, db_formatar($tttotal, 'f'), 1, 1, "R", 0);
	} else {
		echo "Sem Débitos para esta chave.";
	}
	if ($parReceit){

      $sqlReceit = "select distinct k02_descr from tabrec where k02_codigo in ($parReceit)";
			//echo $sqlReceit;
			$rsRec     = pg_query($sqlReceit);
			$numRows   = pg_num_rows($rsRec);
			$vRec      = "";
			$legRec    = '';
			if ($numRows > 0){

          for ($i = 0;$i < $numRows;$i++){

              db_FieldsMemory($rsRec,$i);
              $legRec .= $vRec.$k02_descr;
							$vRec   =  ', ';

					}
			}
			$pdf->setFont("Arial",'',10);
			$pdf->ln();
			$pdf->multiCell(190,4,"Receitas Selecionadas: $legRec");
   }
   
   $pdf->Ln();
   
   if (isset ($matric)) {
   	$sSqlInnerTabela = " inner join arrematric on arrematric.k00_numpre = arresusp.k00_numpre ";
   	$sSqlWhereTabela = " arrematric.k00_matric = $matric ";
   } else if (isset ($inscr)) {
   	$sSqlInnerTabela = " inner join arreinscr on arreinscr.k00_numpre = arresusp.k00_numpre ";
   	$sSqlWhereTabela = " arreinscr.k00_inscr  = $inscr ";   	
   } else if (isset ($numcgm)) {
   	$sSqlInnerTabela = " inner join arrenumcgm on arrenumcgm.k00_numpre = arresusp.k00_numpre ";
   	$sSqlWhereTabela = " arrenumcgm.k00_numcgm = $numcgm ";   	
   } else if (isset ($numpre)) {
   	$sSqlInnerTabela = "";
   	$sSqlWhereTabela = " arresusp.k00_numpre   = $numpre ";   	
   }   
   
   $sSqlSuspensao  = " select arresusp.*,		  								 	  				";
   $sSqlSuspensao .= " 		  arretipo.k00_descr	  							 	  				";
   $sSqlSuspensao .= " 	 from arresusp  		  								 	  				";
   $sSqlSuspensao .= " 	 inner join suspensao on suspensao.ar18_sequencial = arresusp.k00_suspensao ";
   $sSqlSuspensao .= " 	 inner join arretipo  on arretipo.k00_tipo = arresusp.k00_tipo 				";
   $sSqlSuspensao .= " 	 {$sSqlInnerTabela}		 								 	  				";
   $sSqlSuspensao .= " 	 where {$sSqlWhereTabela} 							 	 	  				";
   $sSqlSuspensao .= " 	   and suspensao.ar18_situacao = 1 											";
   if (trim($parReceit) != ""){
	 $sSqlSuspensao .= "   and arresusp.k00_receit in ({$parReceit})			 	  				";   	
   }      
   $sSqlSuspensao .= " 	   and arresusp.k00_tipo   in ({$sTiposDebitos})			 	  		    ";
   $sSqlSuspensao .= " 	   and arretipo.k00_instit = ".db_getsession('DB_instit');
   
   $rsSuspensao      = pg_query($sSqlSuspensao);
   $iLinhasSuspensao = pg_num_rows($rsSuspensao);
   $aSuspensao		 = array();
	
   if ( $iLinhasSuspensao > 0 ) {
   	
     for ($i=0; $i < $iLinhasSuspensao; $i++) {
     	
       $oSuspensao = db_utils::fieldsMemory($rsSuspensao,$i);
     
       if (isset($aSuspensao[$oSuspensao->k00_tipo][$oSuspensao->k00_descr])) {
         $aSuspensao[$oSuspensao->k00_tipo][$oSuspensao->k00_descr]['vlrhis'] += $oSuspensao->k00_valor;
         $aSuspensao[$oSuspensao->k00_tipo][$oSuspensao->k00_descr]['vlrcor'] += $oSuspensao->k00_vlrcor;
         $aSuspensao[$oSuspensao->k00_tipo][$oSuspensao->k00_descr]['vlrjur'] += $oSuspensao->k00_vlrjur;
         $aSuspensao[$oSuspensao->k00_tipo][$oSuspensao->k00_descr]['vlrmul'] += $oSuspensao->k00_vlrmul;
         $aSuspensao[$oSuspensao->k00_tipo][$oSuspensao->k00_descr]['vlrdes'] += $oSuspensao->k00_vlrdes;
       } else {
         $aSuspensao[$oSuspensao->k00_tipo][$oSuspensao->k00_descr]['vlrhis']  = $oSuspensao->k00_valor;
         $aSuspensao[$oSuspensao->k00_tipo][$oSuspensao->k00_descr]['vlrcor']  = $oSuspensao->k00_vlrcor;
         $aSuspensao[$oSuspensao->k00_tipo][$oSuspensao->k00_descr]['vlrjur']  = $oSuspensao->k00_vlrjur;
         $aSuspensao[$oSuspensao->k00_tipo][$oSuspensao->k00_descr]['vlrmul']  = $oSuspensao->k00_vlrmul;
         $aSuspensao[$oSuspensao->k00_tipo][$oSuspensao->k00_descr]['vlrdes']  = $oSuspensao->k00_vlrdes;     	  
       }
     
     }
   
   
     $pdf->SetFont('Arial', 'BI', 12);
     $pdf->Cell(0,5,'Débitos Suspensos',0,1,"C",0);
     $pdf->Ln();
     
     $pdf->SetFont('Arial', 'B', 8);
     $pdf->Cell(7 ,5, "Tipo"		 ,1,0,"C",1);
     $pdf->Cell(60,5, "Descrição"	 ,1,0,"C",1);
     $pdf->Cell(20,5, "Vlr Histrico" ,1,0,"C",1);
     $pdf->Cell(20,5, "Vlr Corrigido",1,0,"C",1);
     $pdf->Cell(20,5, "Vlr Juros"	 ,1,0,"C",1);
     $pdf->Cell(20,5, "Vlr Multa"    ,1,0,"C",1);
     $pdf->Cell(20,5, "Vlr Desconto" ,1,0,"C",1);
     $pdf->Cell(20,5, "Vlr Total"	 ,1,1,"C",1);
        
     $pdf->SetFont('Arial', '', 8);
     
   	 $aTotalSuspensao[0]['vlrhis'] = 0;
   	 $aTotalSuspensao[0]['vlrcor'] = 0;
   	 $aTotalSuspensao[0]['vlrjur'] = 0;
   	 $aTotalSuspensao[0]['vlrmul'] = 0;
   	 $aTotalSuspensao[0]['vlrdes'] = 0;
   	 $aTotalSuspensao[0]['vlrtot'] = 0;
     
     foreach ($aSuspensao as $iTipo => $aDescrTipo ) {
   	   foreach ($aDescrTipo as $sDescrTipo => $aValores ) {
   	   	
   	   	 $nTotal = ( $aValores['vlrcor'] + $aValores['vlrjur'] + $aValores['vlrmul'] ) - $aValores['vlrdes'];
   	   	 
   	     $pdf->Cell(7 ,5, $iTipo							   , 1, 0, "C", 0);
   	     $pdf->Cell(60,5, substr($sDescrTipo, 0, 35)		   , 1, 0, "L", 0);
   	     $pdf->Cell(20,5, db_formatar($aValores['vlrhis'], 'f'), 1, 0, "R", 0);
   	     $pdf->Cell(20,5, db_formatar($aValores['vlrcor'], 'f'), 1, 0, "R", 0);
   	     $pdf->Cell(20,5, db_formatar($aValores['vlrjur'], 'f'), 1, 0, "R", 0);
   	     $pdf->Cell(20,5, db_formatar($aValores['vlrmul'], 'f'), 1, 0, "R", 0);
   	     $pdf->Cell(20,5, db_formatar($aValores['vlrdes'], 'f'), 1, 0, "R", 0);
   	     $pdf->Cell(20,5, db_formatar(($nTotal),'f')		   , 1, 1, "R", 0);

   	     $aTotalSuspensao[0]['vlrhis'] += $aValores['vlrhis'];
   	     $aTotalSuspensao[0]['vlrcor'] += $aValores['vlrcor'];
   	     $aTotalSuspensao[0]['vlrjur'] += $aValores['vlrjur'];
   	     $aTotalSuspensao[0]['vlrmul'] += $aValores['vlrmul'];
   	     $aTotalSuspensao[0]['vlrdes'] += $aValores['vlrdes'];
   	     $aTotalSuspensao[0]['vlrtot'] += $nTotal;
   	   }
     }
   
     foreach ( $aTotalSuspensao as $iInd => $aTotValSuspensao ){

   	   $nTotal = ( $aTotValSuspensao['vlrcor'] + $aTotValSuspensao['vlrjur'] + $aTotValSuspensao['vlrmul'] ) - $aTotValSuspensao['vlrdes'];
   	 
   	   $pdf->Cell(7 ,5, ''		 	   						  	     ,1,0,"C",0);
   	   $pdf->SetFont('Arial', 'B', 8);  	 
   	   $pdf->Cell(60,5, 'Total'		   				 			     ,1,0,"C",0);
   	   $pdf->SetFont('Arial', '', 8);
   	   $pdf->Cell(20,5, db_formatar($aTotValSuspensao['vlrhis'], 'f'),1,0,"R",0);
   	   $pdf->Cell(20,5, db_formatar($aTotValSuspensao['vlrcor'], 'f'),1,0,"R",0);
   	   $pdf->Cell(20,5, db_formatar($aTotValSuspensao['vlrjur'], 'f'),1,0,"R",0);
   	   $pdf->Cell(20,5, db_formatar($aTotValSuspensao['vlrmul'], 'f'),1,0,"R",0);
   	   $pdf->Cell(20,5, db_formatar($aTotValSuspensao['vlrdes'], 'f'),1,0,"R",0);
   	   $pdf->Cell(20,5, db_formatar(($nTotal),'f')			         ,1,1,"R",0);   			
     }
   }

}

$pdf->Output();
?>
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
//$pdf->AddPage(); 
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(220);
$pdf->SetFont('Arial','',11);
$head1 = "RELATÓRIO DO IPTU PAGO: " . $exercicio;

/*
$sql = "
select tj21_receit,
       tk02_drecei,
       round(sum(j21_valor),2) as tvalor
from iptucalv
     inner join tabrec on j21_receit = k02_codigo
group by j21_receit,k02_drecei
";

$sql = "
select k00_receit,k02_drecei,count(sum) as total, sum(sum) as valor from (select iptunump.j20_numpre,
       k00_receit,
       sum(k00_valor)
from iptunump
     inner join arrepaga on j20_numpre = k00_numpre
group by j20_numpre,k00_receit
order by k00_receit) as x
     left outer join tabrec on k02_codigo = k00_receit
group by k00_receit,k02_drecei
";
*/
//$exercicio = db_getsession("DB_anousu");
$sql = "select count(*) from iptucalc where j23_anousu = $exercicio";
$result = pg_exec($sql);
$totcontrib = pg_result($result,0);
$sql = "
select * from
	(select k00_receit as rec_pago,
		k02_drecei as drec_pago,
		round(sum(sum),2) as valor_pago from 

		(select iptunump.j20_numpre,
	       		k00_receit,
       			sum(k00_valor) 
			from iptunump
   			inner join arrepaga on 
				j20_numpre = k00_numpre and j20_anousu = $exercicio
			group by j20_numpre,k00_receit
			order by k00_receit) as x
     		left outer join tabrec on 
			k02_codigo = k00_receit
		group by k00_receit,k02_drecei) as x

	full outer join
		
	(select j21_receit as rec_calc,
       		k02_drecei as drec_calc,
       		round(sum(j21_valor),2) as valor_calc
		from iptucalv
     		inner join tabrec on 
			j21_receit = k02_codigo and j21_anousu = $exercicio
		group by j21_receit,k02_drecei) as y 
	on x.rec_pago = y.rec_calc

	full outer join

	(select k00_receit as rec_unica20,
		round(sum(sum),2) as valor_pago_unica20 from 

		(select iptunump.j20_numpre,
	       		arrepaga.k00_receit,
       			sum(arrepaga.k00_valor) 
			from iptunump
   			inner join arrepaga on 
				j20_numpre = k00_numpre and j20_anousu = $exercicio
			where arrepaga.k00_hist = 990 and k00_dtpaga >= '$exercicio-01-01'
			group by j20_numpre,arrepaga.k00_receit
			order by arrepaga.k00_receit) as xxx
     		left outer join tabrec on 
			k02_codigo = k00_receit
		group by k00_receit,k02_drecei) as xx
	on xx.rec_unica20 = x.rec_pago

	full outer join

	(select k00_receit as rec_unica10,
		round(sum(sum),2) as valor_pago_unica10 from 

		(select iptunump.j20_numpre,
	       		arrepaga.k00_receit,
       			sum(arrepaga.k00_valor) 
			from iptunump
   			inner join arrepaga on 
				j20_numpre = k00_numpre and j20_anousu = $exercicio
			where arrepaga.k00_hist = 990 and k00_dtpaga >= '$exercicio-12-31'
			group by j20_numpre,arrepaga.k00_receit
			order by arrepaga.k00_receit) as fff
     		left outer join tabrec on 
			k02_codigo = k00_receit
		group by k00_receit,k02_drecei) as ff
	on ff.rec_unica10 = x.rec_pago

	full outer join

	(select k00_receit as rec_parc1,
		round(sum(sum),2) as valor_parc1 from 

		(select iptunump.j20_numpre,
	       		k00_receit,
       			sum(k00_valor) 
			from iptunump
   			inner join arrepaga on 
				j20_numpre = k00_numpre and j20_anousu = $exercicio
		        where k00_hist <> 990
			and k00_numpar = 1
			group by j20_numpre,k00_receit
			order by k00_receit) as ggg
     		left outer join tabrec on 
			k02_codigo = k00_receit
		group by k00_receit,k02_drecei) as gg
	on gg.rec_parc1 = x.rec_pago

	full outer join

	(select k00_receit as rec_parc2,
		round(sum(sum),2) as valor_parc2 from 

		(select iptunump.j20_numpre,
	       		k00_receit,
       			sum(k00_valor) 
			from iptunump
   			inner join arrepaga on 
				j20_numpre = k00_numpre and j20_anousu = $exercicio
		        where k00_hist <> 990
			and k00_numpar = 2
			group by j20_numpre,k00_receit
			order by k00_receit) as hhh
     		left outer join tabrec on 
			k02_codigo = k00_receit
		group by k00_receit,k02_drecei) as hh
	on hh.rec_parc2 = x.rec_pago

	full outer join

	(select k00_receit as rec_parc3,
		round(sum(sum),2) as valor_parc3 from 

		(select iptunump.j20_numpre,
	       		k00_receit,
       			sum(k00_valor) 
			from iptunump
   			inner join arrepaga on 
				j20_numpre = k00_numpre and j20_anousu = $exercicio
		        where k00_hist <> 990
			and k00_numpar = 3
			group by j20_numpre,k00_receit
			order by k00_receit) as iii
     		left outer join tabrec on 
			k02_codigo = k00_receit
		group by k00_receit,k02_drecei) as ii
	on ii.rec_parc3 = x.rec_pago
	
	full outer join

	(select k00_receit as rec_parc4,
		round(sum(sum),2) as valor_parc4 from 

		(select iptunump.j20_numpre,
	       		k00_receit,
       			sum(k00_valor) 
			from iptunump
   			inner join arrepaga on 
				j20_numpre = k00_numpre and j20_anousu = $exercicio
		        where k00_hist <> 990
			and k00_numpar = 4
			group by j20_numpre,k00_receit
			order by k00_receit) as jjj
     		left outer join tabrec on 
			k02_codigo = k00_receit
		group by k00_receit,k02_drecei) as jj
	on jj.rec_parc4 = x.rec_pago

	full outer join

	(select k00_receit as rec_parc5,
		round(sum(sum),2) as valor_parc5 from 

		(select iptunump.j20_numpre,
	       		k00_receit,
       			sum(k00_valor) 
			from iptunump
   			inner join arrepaga on 
				j20_numpre = k00_numpre and j20_anousu = $exercicio
		        where k00_hist <> 990
			and k00_numpar = 5
			group by j20_numpre,k00_receit
			order by k00_receit) as kkk
     		left outer join tabrec on 
			k02_codigo = k00_receit
		group by k00_receit,k02_drecei) as kk
	on kk.rec_parc5 = x.rec_pago
       "; 
$result = pg_exec($sql);
$num = pg_numrows($result);
// j23_matric, z01_nome, percentual, valordb, valorsap, diferenca
$linha = 60;
//$pdf->MultiCell(0,4,"teste",0,"J",0,0);
$pre = 0;
$total_calc = 0;
$total_pago = 0;
$totuni20 = 0;
$totuni10 = 0;
$tot_par1 = 0;
$tot_par2 = 0;
$tot_par3 = 0;
$tot_par4 = 0;
$tot_par5 = 0;
$pagina = 0;
for($i=0;$i<$num;$i++) {
   if($linha++>45){
      $linha = 0;
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',7);
      $pdf->Cell(15,6,"RECEITA",1,0,"C",1);
      $pdf->Cell(60,6,"DESCRIÇÃO",1,0,"C",1);
      $pdf->Cell(20,6,"VALOR CALC.",1,0,"C",1);
      $pdf->Cell(20,6,"VALOR PAGO",1,0,"C",1);
      $pdf->Cell(20,6,"UNICA",1,0,"C",1);
      $pdf->Cell(20,6,"PARCELA 1",1,0,"C",1);
      $pdf->Cell(20,6,"PARCELA 2",1,0,"C",1);
      $pdf->Cell(20,6,"PARCELA 3",1,0,"C",1);
      $pdf->Cell(20,6,"PARCELA 4",1,0,"C",1);
      $pdf->Cell(20,6,"PARCELA 5",1,0,"C",1);
      $pdf->Cell(20,6,"PERCENTUAL",1,1,"C",1);
      $pagina = $pdf->PageNo();
   }
   if($linha % 2 == 0){
     $pre = 0;
   }else {
     $pre = 1;
   }
   db_fieldsmemory($result,$i);
   $pdf->SetFont('Arial','',7);
   if ($rec_calc != '' ){
      $pdf->cell(15,4,$rec_calc,0,0,"R",$pre);
      $pdf->cell(60,4,strtoupper($drec_calc),0,0,"L",$pre);
   }else{
      $pdf->cell(15,4,$rec_pago,0,0,"R",$pre);
      $pdf->cell(60,4,strtoupper($drec_pago),0,0,"L",$pre);
   }
   $pdf->cell(20,4,db_formatar($valor_calc,'f'),0,0,"R",$pre);
   $pdf->cell(20,4,db_formatar($valor_pago,'f'),0,0,"R",$pre);
   $pdf->cell(20,4,db_formatar($valor_pago_unica20+$valor_pago_unica10,'f'),0,0,"R",$pre);
//   $pdf->cell(20,4,db_formatar($valor_pago_unica10,'f'),0,0,"R",$pre);
   $pdf->cell(20,4,db_formatar($valor_parc1,'f'),0,0,"R",$pre);
   $pdf->cell(20,4,db_formatar($valor_parc2,'f'),0,0,"R",$pre);
   $pdf->cell(20,4,db_formatar($valor_parc3,'f'),0,0,"R",$pre);
   $pdf->cell(20,4,db_formatar($valor_parc4,'f'),0,0,"R",$pre);
   $pdf->cell(20,4,db_formatar($valor_parc5,'f'),0,0,"R",$pre);
   if ( ($valor_pago != 0 ) && ($valor_calc != 0)) {
      $pdf->cell(20,4,number_format($valor_pago/$valor_calc*100,6,',','.'),0,1,"R",$pre);
   }else{
      $pdf->cell(20,4,number_format(0,6,',','.'),0,1,"R",$pre);
   }
   $total_calc += $valor_calc;
   $total_pago += $valor_pago;
   $totuni20   += $valor_pago_unica20 + $valor_pago_unica10;
//   $totuni10   += $valor_pago_unica10;
   $tot_par1   += $valor_parc1;
   $tot_par2   += $valor_parc2;
   $tot_par3   += $valor_parc3;
   $tot_par4   += $valor_parc4;
   $tot_par5   += $valor_parc5;
}
$pdf->Ln(5);
$pdf->Cell(75,6,"Total: ","T",0,"L",0);
$pdf->Cell(20,6,db_formatar($total_calc,'f'),"T",0,"R",0);
$pdf->Cell(20,6,db_formatar($total_pago,'f'),"T",0,"R",0);
$pdf->Cell(20,6,db_formatar($totuni20,'f'),"T",0,"R",0);
//$pdf->Cell(20,6,db_formatar($totuni10,'f'),"T",0,"R",0);
$pdf->Cell(20,6,db_formatar($tot_par1,'f'),"T",0,"R",0);
$pdf->Cell(20,6,db_formatar($tot_par2,'f'),"T",0,"R",0);
$pdf->Cell(20,6,db_formatar($tot_par3,'f'),"T",0,"R",0);
$pdf->Cell(20,6,db_formatar($tot_par4,'f'),"T",0,"R",0);
$pdf->Cell(20,6,db_formatar($tot_par5,'f'),"T",0,"R",0);
if ( ($total_pago != 0 ) && ($total_calc != 0)) {
   $pdf->Cell(20,6,number_format($total_pago/$total_calc*100,6,',','.'),"T",1,"R",$pre);
}
//$pdf->Ln(5);
$pdf->Cell(75,6,"Percentuais: ","T",0,"L",0);
$pdf->Cell(20,6,"","T",0,"R",0);
$pdf->Cell(20,6,db_formatar($total_pago/$total_calc*100,'f'),"T",0,"R",0);
$pdf->Cell(20,6,db_formatar($totuni20/$total_calc*100,'f'),"T",0,"R",0);
//$pdf->Cell(20,6,db_formatar($totuni10/$total_calc*100,'f'),"T",0,"R",0);
$pdf->Cell(20,6,db_formatar($tot_par1/$total_calc*100,'f'),"T",0,"R",0);
$pdf->Cell(20,6,db_formatar($tot_par2/$total_calc*100,'f'),"T",0,"R",0);
$pdf->Cell(20,6,db_formatar($tot_par3/$total_calc*100,'f'),"T",0,"R",0);
$pdf->Cell(20,6,db_formatar($tot_par4/$total_calc*100,'f'),"T",0,"R",0);
$pdf->Cell(20,6,db_formatar($tot_par5/$total_calc*100,'f'),"T",0,"R",0);
//if ( ($total_pago != 0 ) && ($total_calc != 0)) {
   $pdf->cell(20,6,"","T",0,"R",$pre);
//}

$pdf->Ln(5);
$pdf->Cell(275,6,"","T",1,"L",0);

$pdf->Cell(95,4,"Total pago parcela única: ",0,0,"L",0);
$pdf->Cell(20,4,db_formatar($totuni20+$totuni10,'f'),0,0,"R",0);
$pdf->Cell(20,4,db_formatar(($totuni20+$totuni10)/$total_calc*100,'f')."%",0,1,"R",0);
$pdf->Cell(95,4,"Total pago parcelado: ",0,0,"L",0);
$pdf->Cell(20,4,db_formatar($tot_par1+$tot_par2+$tot_par3+$tot_par4+$tot_par5,'f'),0,0,"R",0);
$pdf->Cell(20,4,db_formatar(($tot_par1+$tot_par2+$tot_par3+$tot_par4+$tot_par5)/$total_calc*100,'f')."%",0,1,"R",0);

$sql = "select count(*) as quant_calculada from (select distinct j20_matric from iptunump where j20_anousu = $exercicio) as x";
$result = pg_exec($sql);
db_fieldsmemory($result,0);
$pdf->Cell(95,4,"Quantidade de matriculas com emissão de carnês: ",0,0,"L",0);
$pdf->Cell(20,4,db_formatar($quant_calculada,'s'),0,1,"R",0);

$sql = "select count(*) as quant_unica from (select distinct j20_matric from iptunump inner join arrepaga on j20_numpre = k00_numpre where j20_anousu = $exercicio and k00_hist = 990) as x";
$result = pg_exec($sql);
db_fieldsmemory($result,0);
$pdf->Cell(95,4,"Quantidade de matrículas que pagaram em cota unica: ",0,0,"L",0);
$pdf->Cell(20,4,db_formatar($quant_unica,'s'),0,1,"R",0);

$sql = "select count(*) as quant_parcelado from (select distinct j20_matric from iptunump inner join arrepaga on j20_numpre = k00_numpre where j20_anousu = $exercicio and k00_hist <> 990) as x";
$result = pg_exec($sql);
db_fieldsmemory($result,0);
$pdf->Cell(95,4,"Quantidade de matrículas que pagaram parcelado: ",0,0,"L",0);
$pdf->Cell(20,4,db_formatar($quant_parcelado,'s'),0,1,"R",0);

$pdf->ln(10);

$pdf->Cell(95,4,"* OBSERVAÇÕES:","T",0,"L",0);
$pdf->Cell(180,4,"","T",1,"L",0);

$pdf->Cell(95,4,"  - nos valores do imposto estão incluídos as onerações. ",0,1,"L",0);

$result = pg_exec("select max(dtarq) as dtarq from disbanco");
db_fieldsmemory($result,0);
$pdf->Cell(95,4,"  - arquivos recebidos dos bancos até " . db_formatar($dtarq,'d'),0,0,"L",0);


//   $data = array();

//   $data["parcela unica"] = ($totuni20+$totuni10)/$total_calc*100;
//   $dt["parcela unica"]   = ($totuni20+$totuni10)/$total_calc*100;
//   $data["parcelados"]    = ($tot_par1+$tot_par2+$tot_par3+$tot_par4+$tot_par5)/$total_calc*100;
//   $dt["parcelados"]      = ($tot_par1+$tot_par2+$tot_par3+$tot_par4+$tot_par5)/$total_calc*100;
   
//   $col[0] = array(50);
//   $col[1] = array(150);

//   for($i=0;$i<pg_numrows($result1);$i++){
//     $data[pg_result($result1,$i,'dois')] = pg_result($result1,$i,'valor');
//   }
   //$data = array('Parcelamento do Foro' => 3229.78, 'Parcelamento de Diversos' => 4479.89, 'Alvara' => 495,'Parcelamentos de Melhorias'=> 29264.32);
   //Pie chart


//   $pdf->SetFont('Arial', 'BIU', 10);
//   $pdf->Cell(0, 5, '1 - Gráfico Comparativo', 0, 1);

  // $pdf->SetFont('Arial', '', 6);
  // $valX = $pdf->GetX();
  // $valY = $pdf->GetY();

  // $pdf->SetXY(10, $valY+30);
  // $pdf->PieChart(180, 60, $dt, '%l - %v - (%p)', $col);
  // $pdf->SetXY($valX, $valY + 40);

$pdf->Output();

?>
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
include("libs/db_sql.php");

$clrotulo = new rotulocampo;
$clrotulo->label('');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$head3 = "ESTATÍSTICAS D0 IPTU";
$head5 = "Exercício : $exercicio";

$where_considerar = "";
if ($considerar == "p") {
  $where_considerar = " and j23_tipoim = 'P' ";
  $head4="Tipo: PREDIAL";
} elseif ($considerar == "t") {
  $where_considerar = " and j23_tipoim = 'T' ";
  $head4="Tipo: TERRITORIAL";
} else {
  $head4="Tipos: PREDIAL E TERRITORIAL";
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total = 0;
$calculado_total=0;
$p=0; 
$pdf->addpage();
$sql_calc = "select j21_receit, k02_drecei, round(sum(j21_valor),2) as calculado 
from iptucalv 
inner join tabrec on j21_receit = k02_codigo 
inner join iptucalc on j23_matric = j21_matric and j23_anousu = j21_anousu
where j21_anousu = $exercicio $where_considerar
group by j21_receit, k02_drecei";
$Result_calc =  pg_exec($sql_calc);
$NumRows_calc = pg_numrows($Result_calc);
for($w=0;$w<$NumRows_calc;$w++){
	db_fieldsmemory($Result_calc,$w);
    if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){    	
    	$pdf->setfont('arial','b',8);
    	$pdf->cell(20,$alt,'Código',1,0,"C",1);
   		$pdf->cell(100,$alt,'Descrição',1,0,"C",1); 
   		$pdf->cell(40,$alt,'Valor Calculado',1,1,"C",1);
   		$troca=0;
    }
   	$pdf->setfont('arial','',7);
   	$pdf->cell(20,$alt,$j21_receit,0,0,"C",$p);
   	$pdf->cell(100,$alt,$k02_drecei,0,0,"L",$p);
   	$pdf->cell(40,$alt,db_formatar($calculado,'f'),0,1,"R",$p);
   	if ($p==0){
   		$p=1;
   	}else{
   		$p=0;
   	}
   	$calculado_total+=$calculado;
   	$total++;
}
$pdf->setfont('arial','b',8);
$sql_mat_calc="select count(*) as mat_calc from iptucalc where j23_anousu = $exercicio $where_considerar";
$Result_mat_calc =  pg_exec($sql_mat_calc);
$NumRows_mat_calc = pg_numrows($Result_mat_calc);
if ($NumRows_mat_calc>0){
	db_fieldsmemory($Result_mat_calc,0);
    $pdf->cell(120,$alt,'TOTAL DE MATRICULAS CALCULADAS :  '.$mat_calc,"T",0,"L",0);						
}
$pdf->cell(40,$alt,'TOTAL :  '.db_formatar($calculado_total,'f'),"T",1,"R",0);
$p=0;
$pdf->Ln();
$troca = 1;
$pago_total=0;
$sql_pago = "select k00_receit, k02_drecei, round(sum(k00_valor),2) as pago 
from iptunump 
inner join arrepaga on j20_numpre = k00_numpre 
inner join tabrec on k02_codigo = k00_receit 
inner join iptucalc on j23_matric = j20_matric and j23_anousu = j20_anousu
where j20_anousu = $exercicio $where_considerar
group by k00_receit, k02_drecei";
$Result_pago =  pg_exec($sql_pago);
$NumRows_pago = pg_numrows($Result_pago);
for($w=0;$w<$NumRows_pago;$w++){
	db_fieldsmemory($Result_pago,$w);
    if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){    	
    	$pdf->setfont('arial','b',8);
    	$pdf->cell(20,$alt,'Código',1,0,"C",1);
   		$pdf->cell(100,$alt,'Descrição',1,0,"C",1); 
   		$pdf->cell(40,$alt,'Valor Pago',1,1,"C",1);
   		$troca=0;
    }
   	$pdf->setfont('arial','',7);
   	$pdf->cell(20,$alt,$k00_receit,0,0,"C",$p);
   	$pdf->cell(100,$alt,$k02_drecei,0,0,"L",$p);
   	$pdf->cell(40,$alt,db_formatar($pago,'f'),0,1,"R",$p);
   	if ($p==0){
   		$p=1;
   	}else{
   		$p=0;
   	}
   	$pago_total+=$pago;
   	$total++;
}
$pdf->setfont('arial','b',8);
$sql_mat_pag="select count(*) as mat_pag from (
select distinct j20_matric 
from iptunump 
inner join arrepaga on j20_numpre = k00_numpre 
inner join iptucalc on j20_matric = j23_matric and j20_anousu = j23_anousu
where j20_anousu = $exercicio
) as x";
$Result_mat_pag =  pg_exec($sql_mat_pag);
$NumRows_mat_pag = pg_numrows($Result_mat_pag);
if ($NumRows_mat_pag>0){
	db_fieldsmemory($Result_mat_pag,0);
    $pdf->cell(120,$alt,'TOTAL DE MATRICULAS QUE EFETUARAM PAGAMENTO :  '.$mat_pag,"T",0,"L",0);						
}
$pdf->cell(40,$alt,'TOTAL :  '.db_formatar($pago_total,'f'),"T",1,"R",0);
$p=0;
$pdf->Ln();
$troca = 1;
$div_total=0;
$sql_div = "select v03_receit, k02_drecei, round(sum(v01_vlrhis),2) as div 
from divida 
inner join divold on v01_coddiv = k10_coddiv
inner join iptunump on j20_numpre = k10_numpre
inner join iptucalc on j20_matric = j23_matric and j20_anousu = j23_anousu 
inner join arrematric on arrematric.k00_numpre = v01_numpre 
inner join proced on v03_codigo = v01_proced 
inner join tabrec on v03_receit = k02_codigo 
where v01_exerc = $exercicio $where_considerar
group by v03_receit, k02_drecei";
$Result_div =  pg_exec($sql_div);
$NumRows_div = pg_numrows($Result_div);
for($w=0;$w<$NumRows_div;$w++){
	db_fieldsmemory($Result_div,$w);
    if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){    	
    	$pdf->setfont('arial','b',8);
    	$pdf->cell(20,$alt,'Código',1,0,"C",1);
   		$pdf->cell(100,$alt,'Descrição',1,0,"C",1); 
   		$pdf->cell(40,$alt,'Valor Inscrito em Divida',1,1,"C",1);
   		$troca=0;
    }
   	$pdf->setfont('arial','',7);
   	$pdf->cell(20,$alt,$v03_receit,0,0,"C",$p);
   	$pdf->cell(100,$alt,$k02_drecei,0,0,"L",$p);
   	$pdf->cell(40,$alt,db_formatar($div,'f'),0,1,"R",$p);
   	if ($p==0){
   		$p=1;
   	}else{
   		$p=0;
   	}
   	$div_total+=$div;
   	$total++;
}
$pdf->setfont('arial','b',8);
$sql_mat_div="select count(*) as mat_div from (

select distinct k00_matric 
from divida 
inner join divold on v01_coddiv = k10_coddiv
inner join iptunump on j20_numpre = k10_numpre
inner join iptucalc on j20_matric = j23_matric and j20_anousu = j23_anousu 
inner join arrematric on arrematric.k00_numpre = v01_numpre 
where v01_exerc = $exercicio $where_considerar
) as x";
$Result_mat_div =  pg_exec($sql_mat_div);
$NumRows_mat_div = pg_numrows($Result_mat_div);
if ($NumRows_mat_div>0){
	db_fieldsmemory($Result_mat_div,0);
    $pdf->cell(120,$alt,'TOTAL DE MATRICULAS INSCRITAS EM DIVIDA :  '.$mat_div,"T",0,"L",0);						
}
$pdf->cell(40,$alt,'TOTAL :  '.db_formatar($div_total,'f'),"T",1,"R",0);
$pdf->Ln();

$pdf->Output();	
?>
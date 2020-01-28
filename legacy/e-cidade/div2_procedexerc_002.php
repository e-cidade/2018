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

set_time_limit(0);
include("libs/db_sql.php");
require("fpdf151/pdf.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//echo $exerc;
//exit;
if ( $ordem == 'v01_proced' ){
   $xordem = " group by v01_proced,v03_dcomp,v01_exerc ";
}else{
   $xordem = " group by v01_exerc, v01_proced,v03_dcomp ";
}

$pdf = new pdf();
$pdf->Open();
$pdf->AliasNbPages();
$head2 = "SECRETARIA DA FAZENDA";
$head4 = "Total Por Procedência / Exercício";
$linha = 60;
$pdf->SetFillColor(220);
$TPagina = 40;

$sql = "select v01_exerc,
               v01_proced,
	           k00_numpre,
               k00_numpar,
	           k00_tipo 
		from divida 
		     inner join arrecad 
				 on v01_numpre = k00_numpre 
				and v01_numpar = k00_numpar 
		left outer join proced 
		         on v01_proced = v03_codigo 
         " ;
$result = pg_exec($sql);

$num = pg_numrows($result);
/*
pg_exec("drop table totproced");			
pg_exec("create table totproced(v01_exerc int4,v01_proced int4,k00_numpre int4,k00_numpar float8,vlrhis float8,vlrcor float8,vlrjuros float8,vlrmulta float8,vlrdesconto float8,total float8)");

for($x=0;$x<$num;$x++){
  db_fieldsmemory($result,$x,true);
  $xdata = mktime(0,0,0,date('m',db_getsession("DB_datausu")),date('d',db_getsession("DB_datausu")),date('Y',db_getsession("DB_datausu"))); 
  $debitos = debitos_numpre($k00_numpre,0,0,$xdata,$DB_anousu,$k00_numpar);
  if(pg_numrows($debitos)>0){
     $tvlrhis=0;
     $tvlrcor=0;
     $tvlrjuros=0;
     $tvlrmulta=0;
     $tvlrdesconto=0;
     $ttotal=0;
     for($xx=0;$xx<pg_numrows($debitos);$xx++){
         db_fieldsmemory($debitos,$xx);
         $tvlrhis+=$vlrhis;
         $tvlrcor+=$vlrcor;
         $tvlrjuros+=$vlrjuros;
         $tvlrmulta+=$vlrmulta;
         $tvlrdesconto+=$vlrdesconto;
         $ttotal+=$total;
     }
  }
  pg_exec("insert into totproced values($v01_exerc,$v01_proced,$k00_numpre,$k00_numpar,$vlrhis,$vlrcor,$vlrjuros,$vlrmulta,$vlrdesconto,$total)");
}

*/
$sql = "select v01_exerc,
               v01_proced,
			   count(k00_numpre) as k00_numpre,
               upper(v03_dcomp) as v03_dcomp,
	       round(sum(vlrhis),2) as vlrhis ,
	       round(sum(vlrcor),2) as vlrcor ,
	       round(sum(vlrjuros),2) as vlrjuros,
	       round(sum(vlrmulta),2) as vlrmulta,
	       round(sum(vlrdesconto),2) as vlrdesconto,
	       round(sum(total),2) as total 
	from totproced 
	     left outer join proced 
	          on v01_proced = v03_codigo 
        where v01_exerc between $valorminimo 
	  and $valormaximo 
	  $xordem ";
$result2 = pg_exec($sql);
db_fieldsmemory($result2,0);
if ( $ordem == 'v01_proced' ){
   $quebra = $v01_proced.'-'.$v03_dcomp;
}else{
   $quebra = $v01_exerc;
}
$totalexerc = 0;
$totalprocd = 0;
$tvlrhis1 = 0;
$tvlrcor1 = 0;
$tvlrjuros1 = 0;
$tvlrmulta1 = 0;
$tvlrdesconto1 = 0;
$ttotal1 = 0;
$tvlrhis2 = 0;
$tvlrcor2 = 0;
$tvlrjuros2 = 0;
$tvlrmulta2 = 0;
$tvlrdesconto2 = 0;
$ttotal2 = 0;
for($s=0;$s<pg_numrows($result2);$s++){
  db_fieldsmemory($result2,$s);
  if ( $ordem == 'v01_proced' ){
     if ($quebra != $v01_proced.'-'.$v03_dcomp){
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(87,05,'Total da Procedencia',"B",0,"L",0);
        $pdf->Cell(24,05,db_formatar($tvlrhis1,'f'),"B",0,"R",0);
        $pdf->Cell(24,05,db_formatar($tvlrcor1,'f'),"B",0,"R",0);
        $pdf->Cell(24,05,db_formatar($tvlrjuros1,'f'),"B",0,"R",0);
        $pdf->Cell(24,05,db_formatar($tvlrmulta1,'f'),"B",0,"R",0);
        $pdf->Cell(24,05,db_formatar($tvlrdesconto1,'f'),"B",0,"R",0);
        $pdf->Cell(24,05,db_formatar($ttotal1,'f'),"B",1,"R",0);
        $linha ++;
        $pdf->Ln(2);
        $linha += 2;
        $pdf->SetFont('Arial','B',8);
        $pdf->MultiCell(231,5,$v01_proced.'-'.$v03_dcomp,0,"L",1);
        $linha ++;
        $tvlrhis1 = 0;
        $tvlrcor1 = 0;
        $tvlrjuros1 = 0;
        $tvlrmulta1 = 0;
        $tvlrdesconto1 = 0;
        $ttotal1 = 0;
	$quebra = $v01_proced.'-'.$v03_dcomp;
      }
  }else{
     if ( $quebra != $v01_exerc){
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell( 7,05,'',"B",0,"C",0);
        $pdf->Cell(80,05,'Total do Exercício',"B",0,"L",0);
        $pdf->Cell(24,05,db_formatar($tvlrhis1,'f'),"B",0,"R",0);
        $pdf->Cell(24,05,db_formatar($tvlrcor1,'f'),"B",0,"R",0);
        $pdf->Cell(24,05,db_formatar($tvlrjuros1,'f'),"B",0,"R",0);
        $pdf->Cell(24,05,db_formatar($tvlrmulta1,'f'),"B",0,"R",0);
        $pdf->Cell(24,05,db_formatar($tvlrdesconto1,'f'),"B",0,"R",0);
        $pdf->Cell(24,05,db_formatar($ttotal1,'f'),"B",1,"R",0);
        $linha ++;
        $pdf->Ln(2);
        $linha += 2;
        $pdf->SetFont('Arial','B',8);
        $pdf->MultiCell(231,5,'Exercício : '.$v01_exerc,0,"L",1);
        $linha ++;
        $tvlrhis1 = 0;
        $tvlrcor1 = 0;
        $tvlrjuros1 = 0;
        $tvlrmulta1 = 0;
        $tvlrdesconto1 = 0;
        $ttotal1 = 0;
	$quebra = $v01_exerc;
     }
  }
  if ( $linha++ > 60 ){
     $linha = 0;
     $pdf->AddPage("L");
     $pdf->SetFont('Arial','B',8);
     if ( $ordem == 'v01_proced' ){
        $pdf->Cell(87,05,"Exercicio",1,0,"C",1);
     }else{
        $pdf->Cell(87,05,"Procedência",1,0,"C",1);
     }
     $pdf->Cell(24,05,"Valor Histórico",1,0,"C",1);
     $pdf->Cell(24,05,"Valor Corrigido",1,0,"C",1);
     $pdf->Cell(24,05,"Valor Juros",1,0,"C",1);
     $pdf->Cell(24,05,"Valor Multa",1,0,"C",1);
     $pdf->Cell(24,05,"Valor Desconto",1,0,"C",1);
     $pdf->Cell(24,05,"Valor Total",1,1,"C",1);
     $pdf->Ln(2);
     $linha += 2;
     if ( $ordem == 'v01_proced' ){
        $pdf->MultiCell(231,5,$v01_proced.'-'.$v03_dcomp,0,"L",1);
     }else{
        $pdf->MultiCell(231,5,'Exercício: '.$v01_exerc,0,"L",1);
     }
     $linha ++;
  }
     $pdf->SetFont('Arial','',8);
   if ( $ordem == 'v01_proced' ){
      $pdf->Cell(87,05,$v01_exerc,0,0,"L",0);
   }else{
      $pdf->Cell(87,05,$v01_proced.'-'.$v03_dcomp,0,0,"L",0);
   }
   $linha ++;
   $pdf->Cell(24,05,db_formatar($vlrhis,'f'),0,0,"R",0);
   $pdf->Cell(24,05,db_formatar($vlrcor,'f'),0,0,"R",0);
   $pdf->Cell(24,05,db_formatar($vlrjuros,'f'),0,0,"R",0);
   $pdf->Cell(24,05,db_formatar($vlrmulta,'f'),0,0,"R",0);
   $pdf->Cell(24,05,db_formatar($vlrdesconto,'f'),0,0,"R",0);
   $pdf->Cell(24,05,db_formatar($total,'f'),0,1,"R",0);
   $linha ++;
   $tvlrhis1      += $vlrhis;
   $tvlrcor1      += $vlrcor;
   $tvlrjuros1    += $vlrjuros;
   $tvlrmulta1    += $vlrmulta;
   $tvlrdesconto1 += $vlrdesconto;
   $ttotal1       += $total;
   $tvlrhis2      += $vlrhis;
   $tvlrcor2      += $vlrcor;
   $tvlrjuros2    += $vlrjuros;
   $tvlrmulta2    += $vlrmulta;
   $tvlrdesconto2 += $vlrdesconto;
   $ttotal2       += $total;

}
$pdf->SetFont('Arial','B',8);
if ( $ordem == 'v01_proced' ){
   $pdf->Cell(87,05,'Total da Procedência',0,0,"L",0);
}else{
   $pdf->Cell(87,05,'Total do Exercício',0,0,"L",0);
}
$linha ++;
$pdf->Cell(24,05,db_formatar($tvlrhis1,'f'),0,0,"R",0);
$pdf->Cell(24,05,db_formatar($tvlrcor1,'f'),0,0,"R",0);
$pdf->Cell(24,05,db_formatar($tvlrjuros1,'f'),0,0,"R",0);
$pdf->Cell(24,05,db_formatar($tvlrmulta1,'f'),0,0,"R",0);
$pdf->Cell(24,05,db_formatar($tvlrdesconto1,'f'),0,0,"R",0);
$pdf->Cell(24,05,db_formatar($ttotal1,'f'),0,1,"R",0);
$pdf->Ln(5);

$pdf->Cell(87,05,'Total Geral',0,0,"L",0);
$pdf->Cell(24,05,db_formatar($tvlrhis2,'f'),0,0,"R",0);
$pdf->Cell(24,05,db_formatar($tvlrcor2,'f'),0,0,"R",0);
$pdf->Cell(24,05,db_formatar($tvlrjuros2,'f'),0,0,"R",0);
$pdf->Cell(24,05,db_formatar($tvlrmulta2,'f'),0,0,"R",0);
$pdf->Cell(24,05,db_formatar($tvlrdesconto2,'f'),0,0,"R",0);
$pdf->Cell(24,05,db_formatar($ttotal2,'f'),0,1,"R",0);

$pdf->Output();
?>
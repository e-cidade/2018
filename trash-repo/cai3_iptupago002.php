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

include("fpdf151/pdf1.php");
$pdf = new PDF1(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
//$pdf->AddPage(); 
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(220);
$pdf->SetFont('Arial','',11);
$head1 = "RELATÓRIO DO IPTU PAGO";

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

$sql = "
select * from
(select k00_receit as rec_pago,k02_drecei as drec_pago, round(sum(sum),2) as valor_pago from (select iptunump.j20_numpre,
       k00_receit,
       sum(k00_valor)
from iptunump
     inner join arrepaga on j20_numpre = k00_numpre
group by j20_numpre,k00_receit
order by k00_receit) as x
     left outer join tabrec on k02_codigo = k00_receit
group by k00_receit,k02_drecei) as x
 full outer join
(select j21_receit as rec_calc,
       k02_drecei as drec_calc,
       round(sum(j21_valor),2) as valor_calc
from iptucalv
     inner join tabrec on j21_receit = k02_codigo
group by j21_receit,k02_drecei) as y on x.rec_pago = y.rec_calc
       ";
$result = pg_exec($sql);
$num = pg_numrows($result);
// j23_matric, z01_nome, percentual, valordb, valorsap, diferenca
$linha = 60;
//$pdf->MultiCell(0,4,"teste",0,"J",0,0);
$pre = 0;
$total_calc = 0;
$total_pago = 0;
$pagina = 0;
for($i=0;$i<$num;$i++) {
   if($linha++>45){
      $linha = 0;
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',10);
      $pdf->SetFont('Arial','B',9);
      $pdf->Cell(15,6,"RECEITA",1,0,"C",1);
      $pdf->Cell(80,6,"DESCRIÇÃO",1,0,"C",1);
      $pdf->Cell(30,6,"VALOR CALC.",1,0,"C",1);
      $pdf->Cell(30,6,"VALOR PAGO",1,0,"C",1);
      $pdf->Cell(30,6,"PERCENTUAL",1,1,"C",1);
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
      $pdf->cell(80,4,$drec_calc,0,0,"L",$pre);
   }else{
      $pdf->cell(15,4,$rec_pago,0,0,"R",$pre);
      $pdf->cell(80,4,$drec_pago,0,0,"L",$pre);
   }
   $pdf->cell(30,4,db_formatar($valor_calc,'f'),0,0,"R",$pre);
   $pdf->cell(30,4,db_formatar($valor_pago,'f'),0,0,"R",$pre);
   if ( ($valor_pago != 0 ) && ($valor_calc != 0)) {
      $pdf->cell(30,4,number_format($valor_pago/$valor_calc*100,6,',','.'),0,1,"R",$pre);
   }
   $total_calc += $valor_calc;
   $total_pago += $valor_pago;
}
$pdf->Ln(5);
$pdf->Cell(95,6,"Total : ","T",0,"L",0);
$pdf->Cell(30,6,db_formatar($total_calc,'f'),"T",0,"R",0);
$pdf->Cell(30,6,db_formatar($total_pago,'f'),"T",0,"R",0);
if ( ($total_pago != 0 ) && ($total_calc != 0)) {
   $pdf->cell(30,4,number_format($total_pago/$total_calc*100,6,',','.'),"T",1,"R",$pre);
}
$pdf->Output();

?>
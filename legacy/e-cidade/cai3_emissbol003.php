<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
db_postmemory($HTTP_SERVER_VARS);
$seleciona_conta = '';
$descr_conta = 'TOTAS AS CONTAS';

if($conta != 0) {
   $seleciona_conta = " and a.k12_conta";
   $sql = "select k13_descr from saltes where k13_conta";
   $pos = strpos($conta,",");
   if($pos > 0) {
       $seleciona_conta .= " in (".$conta.")";
       $sql             .= " in (".$conta.")";
   }
   else {
       $seleciona_conta .= " = ".$conta;
       $sql             .= " = ".$conta;
   }
   $result  = db_query($sql);
   if($pos < 0) {
       db_fieldsmemory($result,0);
       $descr_conta .= "CONTA : ".$conta.' - '.$k13_descr;
   }
}

$selecao = 'TODOS OS CAIXAS';
$seleciona = '';
$ordem = ' order by a.k12_conta, a.k12_autent ';
if($caixa != 0) {
   $seleciona = ' and a.k12_id = '.$caixa;
   $ordem = ' order by a.k12_conta, a.k12_id, a.k12_autent ';
   $sql = "select * from cfautent where k11_id = $caixa";
   $result = db_query($sql);
   db_fieldsmemory($result,0);
   $selecao = "CAIXA : ".$caixa.' - '.$k11_local;
}
if($datai == $dataf){
    $sql = "select k11_numbol
            from boletim
	    where k11_data = '".$datai."' and k11_anousu = ".db_getsession("DB_anousu")." and k11_instit = ".db_getsession("DB_instit");

    $result = db_query($sql);
    if(pg_numrows($result) > 0) {
	db_fieldsmemory($result,0);
    }

    $head1 = "BOLETIM DA TESOURARIA";
    $head2 = "BOLETIM NÚMERO: ".@$k11_numbol;
    $head3 = "DATA : ".db_formatar(@$datai,"d");
}
else {
    $head4 = "BOLETIM DE AUTENTICAÇÕES";
}
$head5 = $selecao;
$head6 = $descr_conta;
$pdf   = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(0,100,255);
//$pdf->Cell(190,15,"RECEITAS ORÇAMENTÁRIAS",1,1,"C",0);

$pdf->SetFont('Arial','B',8);
$pdf->SetTextColor(0,100,255);
$CoL1 = 12;
$CoL2 = 20;
$CoL3 = 30;
$CoL4 = 17;
$CoL5 = 15;
$CoL6 = 30;
$CoL7 = 20;
$CoL8 = 20;

$StrPad1 = 20;
$StrPad2 = 26;
$pdf->Cell($CoL1,5,"CAIXA",1,0,"C",0);
$pdf->Cell($CoL2,5,"DATA",1,0,"C",0);
$pdf->Cell($CoL3,5,"AUTENTICAÇÃO",1,0,"C",0);
$pdf->Cell($CoL4,5,"HORA",1,0,"C",0);
$pdf->Cell($CoL5,5,"CONTA",1,0,"C",0);
$pdf->Cell($CoL6,5,"VALOR",1,1,"C",0);
//$pdf->Cell($CoL7,5,"NUMPRE",1,0,"C",0);
//$pdf->Cell($CoL8,5,"PARCELA",1,1,"C",0);

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$exercicio = $GLOBALS["DB_anousu"];
//echo
//$sql  = "select distinct a.k12_id,a.k12_data,a.k12_autent,
//                     a.k12_hora,a.k12_conta,round(a.k12_valor,2) as k12_valor ,
//		     case when b.k12_numnov = 0 then b.k12_numpre else b.k12_numnov end as k12_numpre,
//		     case when b.k12_numnov = 0 then b.k12_numpar else 0 end as k12_numpar
//            from corrente a
//	           left outer join cornump b on b.k12_id = a.k12_id
//		                            and b.k12_data = a.k12_data
//		                            and b.k12_autent = a.k12_autent
//
//	      where a.k12_data between '$datai' and '$dataf'
//	            $seleciona
//		    $seleciona_conta
//		    $ordem ";
$sql  = "select a.k12_id,a.k12_data,a.k12_autent,
                     a.k12_hora,a.k12_conta,round(a.k12_valor,2) as k12_valor
              from corrente a
	      where a.k12_data between '$datai' and '$dataf'
	            $seleciona
		    $seleciona_conta
		    $ordem ";
//exit;
$result = db_query($sql);
$numrows = pg_numrows($result);
$QuebraPagina = 10;
$Total1 = 0;
$Total2 = 0;
$SubTotal1 = 0;
$SubTotal2 = 0;
$pdf->SetFont('Courier','',8);
for($i = 0;$i < $numrows;$i++) {
  db_fieldsmemory($result,$i);
  if($pdf->GetY() > ( $pdf->h - 30 )){
     $pdf->SetFont('Arial','B',8);
     $pdf->SetTextColor(255,0,0);
     $pdf->Cell($CoL1+$CoL2,5,"SUB-TOTAL",1,0,"C",0);
     $pdf->Cell($CoL3,5," ",1,0,"R",0);
     $pdf->Cell($CoL4,5," ",1,0,"R",0);
     $pdf->Cell($CoL5,5," ",1,0,"R",0);
     $pdf->Cell($CoL6,5,db_formatar($SubTotal2,'f'),1,1,"R",0);
//     $pdf->Cell($CoL7,5," ",1,0,"R",0);
//     $pdf->Cell($CoL8,5," ",1,1,"R",0);
     $pdf->SetTextColor(0,0,0);
     $pdf->AddPage();
     $pdf->SetFont('Arial','B',8);
     $pdf->SetTextColor(0,100,255);
     $pdf->Cell($CoL1,5,"CAIXA",1,0,"C",0);
     $pdf->Cell($CoL2,5,"DATA",1,0,"C",0);
     $pdf->Cell($CoL3,5,"AUTENTICAÇÃO",1,0,"C",0);
     $pdf->Cell($CoL4,5,"HORA",1,0,"C",0);
     $pdf->Cell($CoL5,5,"CONTA",1,0,"C",0);
     $pdf->Cell($CoL6,5,"VALOR",1,1,"C",0);
//     $pdf->Cell($CoL7,5,"NUMPRE",1,0,"C",0);
//     $pdf->Cell($CoL8,5,"PARCELA",1,1,"C",0);
     $pdf->SetTextColor(0,0,0);
     $pdf->SetFont('Courier','',8);
     $QuebraPagina = 0;
     $SubTotal1 = 0;
     $SubTotal2 = 0;
  }
    if ( $QuebraPagina % 2  == 0 ) {
        $pdf->SetFillColor(255,255,255);
    } else {
        $pdf->SetFillColor(202,242,249);
    }
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell($CoL1,5,$k12_id,1,0,"C",1);
    $pdf->Cell($CoL2,5,db_formatar($k12_data,'d'),1,0,"L",1);
    $pdf->Cell($CoL3,5,$k12_autent,1,0,"C",1);
    $pdf->Cell($CoL4,5,$k12_hora,1,0,"R",1);
    $pdf->Cell($CoL5,5,$k12_conta,1,0,"C",1);
    if ($k12_valor < 0)
       $pdf->SetTextColor(255,0,0);
    $pdf->Cell($CoL6,5,"R$".str_pad(number_format($k12_valor,2,",","."),14," ",STR_PAD_LEFT),1,1,"R",1);
    $SubTotal2 += $k12_valor;
    $Total2 += $k12_valor;
//  $pdf->Cell($CoL7,5,$k12_numpre,1,0,"C",1);
//  $pdf->Cell($CoL8,5,$k12_numpar,1,1,"C",1);
    $pdf->SetTextColor(0,0,0);
}
if ( ($SubTotal2 + $Total2 ) != 0 ) {
	$pdf->SetFont('Arial','B',8);
    $pdf->SetTextColor(255,0,0);
    $pdf->Cell($CoL1+$CoL2,5,"SUB-TOTAL",1,0,"C",0);
//    $pdf->Cell($CoL2,5," ",1,0,"R",0);
    $pdf->Cell($CoL3,5," ",1,0,"R",0);
    $pdf->Cell($CoL4,5," ",1,0,"R",0);
    $pdf->Cell($CoL5,5," ",1,0,"R",0);
    $pdf->Cell($CoL6,5,number_format($SubTotal2,2,",","."),1,1,"R",0);
//    $pdf->Cell($CoL7,5," ",1,0,"R",0);
//    $pdf->Cell($CoL8,5," ",1,1,"R",0);

    $pdf->SetFont('Arial','B',8);
    $pdf->SetTextColor(255,0,0);
    $pdf->Cell($CoL1+$CoL2,5,"TOTAL",1,0,"C",0);
//    $pdf->Cell($CoL2,5," ",1,0,"R",0);
    $pdf->Cell($CoL3,5," ",1,0,"R",0);
    $pdf->Cell($CoL4,5," ",1,0,"R",0);
    $pdf->Cell($CoL5,5," ",1,0,"R",0);
    $pdf->Cell($CoL6,5,number_format($Total2,2,",","."),1,1,"R",0);
//    $pdf->Cell($CoL7,5," " ,1,0,"R",0);
//    $pdf->Cell($CoL8,5," ",1,1,"R",0);
    $pdf->SetTextColor(0,0,0);
}
$pdf->Output();
?>
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
  $seleciona_conta = ' and corrente.k12_conta = '.$conta;
  $sql = 'select * from saltes where k13_conta = '.$conta;
  $result = db_query($sql);
  db_fieldsmemory($result,0);
  $descr_conta = "CONTA : ".$conta.' - '.$k13_descr;
}

$selecao = 'TODOS OS CAIXAS';
$seleciona = '';
if ( $caixa != 0 ){
   $seleciona = ' and corrente.k12_id = '.$caixa;
   $sql = "select * from cfautent where k11_id = $caixa";
   $result = db_query($sql);
   db_fieldsmemory($result,0);
   $selecao = "CAIXA : ".$caixa.' - '.$k11_local;
}
if($datai == $dataf){
  $sql = "select k12_data
          from boletim";

  $head1 = "BOLETIM DA TESOURARIA";
  $head3 = "BOLETIM NÚMERO: ".@$numbol;
  $head5 = "DATA : ".substr($datai,8,2)."-".substr($datai,5,2)."-".substr($datai,0,4);

}else{
$head5 = "BOLETIM DE CAIXA E DE BANCOS";
}
$head7 = $selecao;
$head9 = $descr_conta;
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(0,100,255);
$pdf->Cell(190,15,"RECEITAS ORÇAMENTÁRIAS",1,1,"C",0);

$pdf->SetFont('Arial','B',8);
$pdf->SetTextColor(0,100,255);
$CoL1 = 30;
$CoL2 = 70;
$CoL3 = 30;
$CoL4 = 30;
$CoL5 = 30;
$StrPad1 = 20;
$StrPad2 = 26;
$pdf->Cell($CoL1,5,"ESTRUTURAL",1,0,"C",0);
$pdf->Cell($CoL2,5,"DESCRICAO",1,0,"C",0);
$pdf->Cell($CoL3,5,"COD.RECEITA",1,0,"C",0);
$pdf->Cell($CoL4,5,"ARRECADACAO",1,0,"C",0);
$pdf->Cell($CoL5,5,"ESTORNO",1,1,"C",0);

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);



$result = db_query("
select k02_estorc,upper(k02_drecei) as o02_descr,receitas.k12_receit,arrec, estorno
from
  (select k02_estorc,k12_receit,sum(k12_arrec) as arrec, sum(k12_estorno) as estorno
   from
       (select k02_estorc,corrente.k12_conta,cornump.k12_receit,case when cornump.k12_valor > 0 then cornump.k12_valor else 0 end as k12_arrec, case when cornump.k12_valor < 0 then cornump.k12_valor else 0 end as k12_estorno
        from corrente
		left join cornump	on corrente.k12_id	= cornump.k12_id
           				and corrente.k12_data	= cornump.k12_data
           				and corrente.k12_autent	= cornump.k12_autent
		left join tabrec	on cornump.k12_receit	= k02_codigo
     		left join taborc	on tabrec.k02_codigo	= taborc.k02_codigo
					and taborc.k02_anousu	= ".$GLOBALS["DB_anousu"]."
 	where 	corrente.k12_data between '".$datai."' and '".$dataf."' ".$seleciona."".$seleciona_conta."
	order by taborc.k02_estorc
	) as x
   group by k02_estorc, k12_receit
   order by k02_estorc
   ) as receitas
   inner join tabrec on tabrec.k02_codigo = receitas.k12_receit
where arrec <> 0 or estorno <> 0 order by k02_estorc

");

$numrows = pg_numrows($result);
$QuebraPagina = 10;
$Total1 = 0;
$Total2 = 0;
$SubTotal1 = 0;
$SubTotal2 = 0;
$pdf->SetFont('Courier','',8);
for($i = 0;$i < $numrows;$i++) {
  db_fieldsmemory($result,$i);
//  if ( ($arrec) != 0 || ($estorno) != 0 ) {
     if($QuebraPagina++ == 46) {
   	    $pdf->SetFont('Arial','B',8);
        $pdf->SetTextColor(255,0,0);
        $pdf->Cell($CoL1,5,"SUB-TOTAL",1,0,"C",0);
        $pdf->Cell($CoL2,5," ",1,0,"R",0);
        $pdf->Cell($CoL3,5," ",1,0,"R",0);
        $pdf->Cell($CoL4,5,number_format($SubTotal1,2,",","."),1,0,"R",0);
        $pdf->Cell($CoL5,5,number_format($SubTotal2,2,",","."),1,1,"R",0);
        $pdf->SetTextColor(0,0,0);
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',8);
        $pdf->SetTextColor(0,100,255);
        $pdf->Cell($CoL1,5,"ESTRUTURAL",1,0,"C",0);
        $pdf->Cell($CoL2,5,"DESCRICAO",1,0,"C",0);
        $pdf->Cell($CoL3,5,"COD.RECEITA",1,0,"C",0);
        $pdf->Cell($CoL4,5,"ARRECADACAO",1,0,"C",0);
        $pdf->Cell($CoL5,5,"ESTORNO",1,1,"C",0);
	    $pdf->SetTextColor(0,0,0);
	    $pdf->SetFont('Courier','',8);
	    $QuebraPagina = 0;
	    $SubTotal1 = 0;
        $SubTotal2 = 0;
     } else {
      if ( $QuebraPagina % 2  == 0 ) {
	  $pdf->SetFillColor(255,255,255);
      } else {
	  $pdf->SetFillColor(202,242,249);
      }
     }
      $pdf->SetTextColor(0,0,0);
      $pdf->Cell($CoL1,5,$k02_estorc,1,0,"L",1);
      $pdf->Cell($CoL2,5,$o02_descr,1,0,"L",1);
      $pdf->Cell($CoL3,5,$k12_receit,1,0,"C",1);
      if(substr($k02_estorc,0,3)=='497'){
        $pdf->Cell($CoL4,5,"R$".str_pad(number_format($arrec*-1,2,",","."),14," ",STR_PAD_LEFT),1,0,"R",1);
        $SubTotal1 -= $arrec;
        $Total1 -= $arrec;
      }else{
        $pdf->Cell($CoL4,5,"R$".str_pad(number_format($arrec,2,",","."),14," ",STR_PAD_LEFT),1,0,"R",1);
        $SubTotal1 += $arrec;
        $Total1 += $arrec;
      }
      if(substr($k02_estorc,0,3)=='497'){
        $pdf->Cell($CoL5,5,"R$".str_pad(number_format($estorno*-1,2,",","."),14," ",STR_PAD_LEFT),1,1,"R",1);
        $SubTotal2 -= $estorno;
        $Total2 -= $estorno;
      }else{
        $pdf->Cell($CoL5,5,"R$".str_pad(number_format($estorno,2,",","."),14," ",STR_PAD_LEFT),1,1,"R",1);
        $SubTotal2 += $estorno;
        $Total2 += $estorno;
      }
  //  }
//  }
}
if ( ($SubTotal1 + $SubTotal2 + $Total1 + $Total2 ) != 0 ) {
	$pdf->SetFont('Arial','B',8);
    $pdf->SetTextColor(255,0,0);
    $pdf->Cell($CoL1,5,"SUB-TOTAL",1,0,"C",0);
    $pdf->Cell($CoL2,5," ",1,0,"R",0);
    $pdf->Cell($CoL3,5," ",1,0,"R",0);
    $pdf->Cell($CoL4,5,number_format($SubTotal1,2,",","."),1,0,"R",0);
    $pdf->Cell($CoL5,5,number_format($SubTotal2,2,",","."),1,1,"R",0);

    $pdf->SetFont('Arial','B',8);
    $pdf->SetTextColor(255,0,0);
    $pdf->Cell($CoL1,5,"TOTAL",1,0,"C",0);
    $pdf->Cell($CoL2,5," ",1,0,"R",0);
    $pdf->Cell($CoL3,5," ",1,0,"R",0);
    $pdf->Cell($CoL4,5,number_format($Total1,2,",","."),1,0,"R",0);
    $pdf->Cell($CoL5,5,number_format($Total2,2,",","."),1,1,"R",0);
    $pdf->SetTextColor(0,0,0);

    $pdf->SetFont('Arial','B',8);
    $pdf->SetTextColor(255,0,0);
    $pdf->Cell($CoL1,5,"TOTAL GERAL",1,0,"C",0);
    $pdf->Cell($CoL2,5," ",1,0,"R",0);
    $pdf->Cell($CoL3,5," ",1,0,"R",0);
    $pdf->Cell($CoL4,5,number_format(($Total1+$Total2),2,",","."),1,0,"R",0);
    $pdf->Cell($CoL5,5,'',1,"R",0);
    $pdf->SetTextColor(0,0,0);
}
$Totalreceitaorcamentaria = $Total1+$Total2;
//////  FIM RECEITA ORÇAMENTÁRIA ///////////





//////  RECEITA EXTRA-ORÇAMENTÁRIA


$result = db_query("
select k02_estpla, c01_descr ,k12_conta,k12_receit,sum(k12_arrec) as arrec, sum(k12_estorno) as estorno
from plano
	inner join
(select k02_estpla,corrente.k12_conta,cornump.k12_receit,case when corrente.k12_valor > 0 then corrente.k12_valor else 0 end as k12_arrec, case when corrente.k12_valor < 0 then corrente.k12_valor else 0 end as k12_estorno
 from corrente
      inner join cornump   on corrente.k12_id     = cornump.k12_id and
            corrente.k12_data   = cornump.k12_data and
            corrente.k12_autent = cornump.k12_autent
      inner join tabrec on cornump.k12_receit = k02_codigo
      inner join tabplan
	         on tabrec.k02_codigo = tabplan.k02_codigo and tabplan.k02_anousu = ".$GLOBALS["DB_anousu"]."
 where corrente.k12_data  between '".$datai."' and '".$dataf."' ".$seleciona."
) as x
  on k02_estpla = c01_estrut and c01_anousu = ".$GLOBALS["DB_anousu"]."
group by k02_estpla,c01_descr,k12_conta,k12_receit;");
$numrows = pg_numrows($result);

if($numrows>0){


$Y = ($pdf->GetY() + 5);
$pdf->SetY($Y);

$pdf->AddPage();
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(0,100,255);
$pdf->Cell(190,15,"RECEITAS EXTRA-ORÇAMENTÁRIAS",1,1,"C",0);

$pdf->SetFont('Arial','B',8);
$pdf->SetTextColor(0,100,255);
$CoL1 = 30;
$CoL2 = 70;
$CoL3 = 30;
$CoL4 = 30;
$CoL5 = 30;
$StrPad1 = 20;
$StrPad2 = 26;
$pdf->Cell($CoL1,5,"ESTRUTURAL",1,0,"C",0);
$pdf->Cell($CoL2,5,"DESCRICAO",1,0,"C",0);
$pdf->Cell($CoL3,5,"COD.RECEITA",1,0,"C",0);
$pdf->Cell($CoL4,5,"ARRECADACAO",1,0,"C",0);
$pdf->Cell($CoL5,5,"ESTORNO",1,1,"C",0);
$QuebraPagina = 10;
$Total1 = 0;
$Total2 = 0;
$SubTotal1 = 0;
$SubTotal2 = 0;
$pdf->SetFont('Courier','',8);
for($i = 0;$i < $numrows;$i++) {

  db_fieldsmemory($result,$i);

  if($QuebraPagina++ == 46) {
	$pdf->SetFont('Arial','B',8);
    $pdf->SetTextColor(255,0,0);
    $pdf->Cell($CoL1,5,"SUB-TOTAL",1,0,"C",0);
    $pdf->Cell($CoL2,5," ",1,0,"R",0);
    $pdf->Cell($CoL3,5," ",1,0,"R",0);
    $pdf->Cell($CoL4,5,number_format($SubTotal1,2,",","."),1,0,"R",0);
    $pdf->Cell($CoL5,5,number_format($SubTotal2,2,",","."),1,1,"R",0);
    $pdf->SetTextColor(0,0,0);
    $pdf->AddPage();
	$pdf->SetFont('Arial','B',8);
    $pdf->SetTextColor(0,100,255);
    $pdf->Cell($CoL1,5,"ESTRUTURAL",1,0,"C",0);
    $pdf->Cell($CoL2,5,"DESCRICAO",1,0,"C",0);
    $pdf->Cell($CoL3,5,"COD.RECEITA",1,0,"C",0);
    $pdf->Cell($CoL4,5,"ARRECADACAO",1,0,"C",0);
    $pdf->Cell($CoL5,5,"ESTORNO",1,1,"C",0);
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Courier','',8);
	$QuebraPagina = 0;
	$SubTotal1 = 0;
        $SubTotal2 = 0;
  } else {
      if ( $QuebraPagina % 2  == 0 ) {
	  $pdf->SetFillColor(255,255,255);

      } else {
	  $pdf->SetFillColor(202,242,249);
      }

    $pdf->SetTextColor(0,0,0);
    $pdf->Cell($CoL1,5,$k02_estpla,1,0,"L",1);
    $pdf->Cell($CoL2,5,$c01_descr,1,0,"L",1);
    $pdf->Cell($CoL3,5,$k12_receit,1,0,"L",1);
    $pdf->Cell($CoL4,5,"R$".str_pad(number_format($arrec,2,",","."),14," ",STR_PAD_LEFT),1,0,"R",1);
    $pdf->Cell($CoL5,5,"R$".str_pad(number_format($estorno,2,",","."),14," ",STR_PAD_LEFT),1,1,"R",1);

    $SubTotal1 += $arrec;
    $SubTotal2 += $estorno;
    $Total1 += $arrec;
    $Total2 += $estorno;
  }
}

	$pdf->SetFont('Arial','B',8);
    $pdf->SetTextColor(255,0,0);
    $pdf->Cell($CoL1,5,"SUB-TOTAL",1,0,"C",0);
    $pdf->Cell($CoL2,5," ",1,0,"R",0);
    $pdf->Cell($CoL3,5," ",1,0,"R",0);
    $pdf->Cell($CoL4,5,number_format($SubTotal1,2,",","."),1,0,"R",0);
    $pdf->Cell($CoL5,5,number_format($SubTotal2,2,",","."),1,1,"R",0);

    $pdf->SetFont('Arial','B',8);
    $pdf->SetTextColor(255,0,0);
    $pdf->Cell($CoL1,5,"TOTAL",1,0,"C",0);
    $pdf->Cell($CoL2,5," ",1,0,"R",0);
    $pdf->Cell($CoL3,5," ",1,0,"R",0);
    $pdf->Cell($CoL4,5,number_format($Total1,2,",","."),1,0,"R",0);
    $pdf->Cell($CoL5,5,number_format($Total2,2,",","."),1,1,"R",0);
    $pdf->SetTextColor(0,0,0);

}
//////  FIM RECEITA EXTRA-ORÇAMENTÁRIA ///////////
$pdf->Output();
?>
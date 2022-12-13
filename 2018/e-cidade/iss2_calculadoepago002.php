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
/*
 echo "<br>ano = $anousu <br>";
exit;*/

// FIXO
$sqlfixo ="
select ano,
       codrec,
       receita,
       sum(qtdcalc)          as qtd_calcfixo,
       round(sum(vlrcalc),2) as vlr_calcfixo,
       round(sum(pago),2)    as vlr_pago_fixo,
       sum(emdia)            as qtd_emdia,
       sum(divida)           as qtd_divida,
       sum(qtdpago)          as qtd_pagofixo
from (
select q01_anousu as ano,
       q01_inscr  as inscr,
       q01_numpre as numpre,
       k02_codigo as codrec,
       k02_descr  as receita,
       cast(1 as integer) as qtdcalc,

       (select sum(k00_valor)
          from (select coalesce(sum(k00_valor), 0) as k00_valor 
                  from arrecad 
                 where k00_numpre = q01_numpre 
                   and k00_valor > 0
                union all
                select coalesce(sum(k00_valor), 0) as k00_valor 
                  from arrecant 
                 where k00_numpre = q01_numpre 
                   and k00_valor > 0
                union all
                select coalesce(sum(k00_valor), 0) as k00_valor 
                  from arreold 
                 where k00_numpre = q01_numpre 
                   and k00_valor > 0
               ) as x
       ) as vlrcalc,
       ( select sum(k00_valor) as pagos  
           from arrepaga 
          where k00_numpre = q01_numpre) as pago,
       case 
         when (  select k00_numpre
                   from arrecad
                  where k00_dtvenc < '".date("Y-m-d", db_getsession("DB_datausu"))."'
                    and k00_numpre = q01_numpre 
                    and k00_receit = q01_recei
               order by k00_dtvenc
                  limit 1 ) is not null
          then 0
           else 1
         end as emdia,
       case 
         when (  select k00_numpre
                   from arrepaga
                  where k00_numpre = q01_numpre 
                    and k00_receit = q01_recei
               order by k00_dtvenc
                  limit 1 ) is not null
          then 0
           else 1
         end as qtdpago,         
      case 
         when (  select k10_numpre
                   from divold
                  where k10_numpre = q01_numpre 
                    and k10_receita = q01_recei
                  limit 1 ) is not null
          then 1
           else 0
         end as divida

  from isscalc
       inner join cadcalc  on q85_codigo = q01_cadcal
       inner join tabrec on k02_codigo = q01_recei
 where q01_anousu = $anousu and q85_codigo=2

) as xx
group by ano, receita,codrec
";
$resultfixo= pg_query($sqlfixo);
$linhasfixo= pg_num_rows($resultfixo);
db_fieldsmemory($resultfixo,0);

// variavel calculado
$sqlvarcalc="
select count(distinct k00_inscr) as qtd_calcvar,
	     sum(vlr_calcvar)          as vlr_calcvar,
	     sum(pago)                 as vlr_pago_var

from (

select k00_inscr,
       vlr_calcvar,
       coalesce(pago,0) as pago
	 from (

select q05_ano,
       q05_mes,
       case 
         when q05_vlrinf is null and q05_valor > 0 then 
           q05_valor 
         else 
           case 
             when q05_vlrinf = 0 and q05_valor = 0 and arrepaga.k00_valor > 0 then 
               arrepaga.k00_valor   
		         else 
               q05_vlrinf 
	         end
	     end as vlr_calcvar,

       case 
         when arrepaga.k00_numpre is null then 
           0 
         else 
           case 
             when arrepaga.k00_numpre is not null and exists(select 1 
                                                               from arrecant 
                                                              where arrecant.k00_numpre = arrepaga.k00_numpre 
                                                                and arrecant.k00_numpar = arrepaga.k00_numpar) then 
               arrepaga.k00_valor 
             else 
               0 
           end 
       end as pago,

       k00_inscr
  from issvar
       inner join arreinscr on issvar.q05_numpre  = arreinscr.k00_numpre
       left join arrepaga   on arrepaga.k00_numpre = q05_numpre
                           and arrepaga.k00_numpar = q05_numpar
 where q05_ano = $anousu 
   and case 
         when q05_vlrinf is null and q05_valor >= 0 then 
           q05_valor 
         else 
           q05_vlrinf 
       end >= 0 

) as x 
--where pago is null or pago > 0

) as y
";
$resultvarcalc= pg_query($sqlvarcalc);
$linhasvarcalc= pg_num_rows($resultvarcalc);
db_fieldsmemory($resultvarcalc,0);

$sqlvencvar = "
select count(k00_inscr) as vencido
from (
select k00_inscr 
from arrecad
    inner join arreinscr on arreinscr.k00_numpre = arrecad.k00_numpre
    inner join issvar    on arrecad.k00_numpre = q05_numpre
                        and arrecad.k00_numpar = q05_numpar
where k00_dtvenc < '".date("Y-m-d", db_getsession("DB_datausu"))."'
      and q05_ano = $anousu 
      and case when q05_vlrinf is null and q05_valor >= 0 then q05_valor else q05_vlrinf end >= 0  
group by k00_inscr
) as x ";
$resultvencvar= pg_query($sqlvencvar);
$linhasvencvar= pg_num_rows($resultvencvar);
db_fieldsmemory($resultvencvar,0);

$sqlqtdpagvar = "
select count(k00_inscr) as qtd_pagovar
from (
select k00_inscr 
from arrepaga
    inner join arreinscr on arreinscr.k00_numpre = arrepaga.k00_numpre
    inner join issvar    on arrepaga.k00_numpre = q05_numpre
                        and arrepaga.k00_numpar = q05_numpar
where q05_ano = $anousu 
      and case when q05_vlrinf is null and q05_valor >= 0 then q05_valor else q05_vlrinf end >= 0  
group by k00_inscr
) as x ";
$resultqtdpagvar= pg_query($sqlqtdpagvar);
$linhasqtdpagvar= pg_num_rows($resultqtdpagvar);
db_fieldsmemory($resultqtdpagvar,0);


$sqldivida= "
select count(k00_inscr) as divida
from (
select k00_inscr 
from divold
    inner join arreinscr on arreinscr.k00_numpre = k10_numpre
    inner join issvar    on k10_numpre = q05_numpre
                        and k10_numpar = q05_numpar
where q05_ano = $anousu
      and case when q05_vlrinf is null and q05_valor >= 0 then q05_valor else q05_vlrinf end >= 0  
group by k00_inscr
) as x ";
$resultdivida= pg_query($sqldivida);
$linhasdivida= pg_num_rows($resultdivida);
db_fieldsmemory($resultdivida,0);

$head2 = "ESTATÍSTICAS DO ISSQN CALCULADO E PAGO";
$head4 = "Exercício do ISS: ".$anousu;

$pdf = new PDF(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage(); // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
$pdf->SetFont('Arial','B',8);
$alt = 6 ; 
$pdf->Cell(190,$alt,"DADOS DO LANÇAMENTO",0,0,"C",1);
$pdf->ln();
$pdf->Cell(70,$alt,"TIPO",0,0,"L",0);
$pdf->Cell(40,$alt,"QTD CALCULADO",0,0,"R",0);
$pdf->Cell(40,$alt,"VALOR TRIBUTO",0,0,"R",0);
$pdf->Cell(40,$alt,"PERCENTUAL",0,0,"R",0);
$pdf->ln();
$pdf->SetFont('Arial','',8);
$total_qtd = "";
$total_venal = "";
$total_tributo = "";

// ########################## DADOS DO LANÇAMENTO ############################
	$qtd_total = $qtd_calcfixo + $qtd_calcvar;
    $pdf->Cell(70,$alt,"ISSQN FIXO",0,0,"L",0);
    $pdf->Cell(40,$alt,$qtd_calcfixo,0,0,"R",0);
    $pdf->Cell(40,$alt,db_formatar($vlr_calcfixo,"f"),0,0,"R",0);
    $pdf->Cell(40,$alt,round(($qtd_calcfixo*100)/$qtd_total,2)."%",0,0,"R",0);
    $pdf->ln();
    $pdf->Cell(70,$alt,"ISSQN VARIÁVEL",0,0,"L",0);
    $pdf->Cell(40,$alt,$qtd_calcvar,0,0,"R",0);
    $pdf->Cell(40,$alt,db_formatar($vlr_calcvar,"f"),0,0,"R",0);
    $pdf->Cell(40,$alt,round(($qtd_calcvar*100)/$qtd_total,2)."%",0,0,"R",0);
    $pdf->ln();
       
// total
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(70,$alt,"Total","T",0,"L",0);
    $pdf->Cell(40,$alt,$qtd_total,"T",0,"R",0);
    $pdf->Cell(40,$alt,db_formatar($vlr_calcvar + $vlr_calcfixo,"f"),"T",0,"R",0);
    $pdf->Cell(40,$alt,"100%","T",0,"R",0);
	$pdf->ln(10);
	
// ########################## DADOS DOS PAGAMENTOS ############################
$pdf->SetFont('Arial','B',8);
//$pdf->ln(5);
$pdf->Cell(190,$alt,"DADOS DOS PAGAMENTOS",0,0,"C",1);
$pdf->ln();
$pdf->Cell(70,$alt,"TIPO",0,0,"L",0);
$pdf->Cell(40,$alt,"QUANTIDADE",0,0,"R",0);
$pdf->Cell(80,$alt,"VALOR PAGO",0,0,"R",0);
$pdf->ln();
$pdf->SetFont('Arial','',8);
$pdf->Cell(70,$alt,"ISSQN FIXO",0,0,"L",0);
$pdf->Cell(40,$alt,$qtd_pagofixo,0,0,"R",0);
$pdf->Cell(80,$alt,db_formatar($vlr_pago_fixo,"f"),0,0,"R",0);
$pdf->ln();
$pdf->Cell(70,$alt,"ISSQN VARIÁVEL",0,0,"L",0);
$pdf->Cell(40,$alt,$qtd_pagovar,0,0,"R",0);
$pdf->Cell(80,$alt,db_formatar($vlr_pago_var,"f"),0,0,"R",0);
$pdf->ln();
$pdf->SetFont('Arial','B',8);
$pdf->Cell(70,$alt,"Total","T",0,"L",0);
$pdf->Cell(120,$alt,db_formatar($vlr_pago_fixo + $vlr_pago_var,"f"),"T",0,"R",0);
$pdf->ln(10);

// ########################## DADOS DOS INADIMPLÊNCIA ############################
$pdf->Cell(190,$alt,"DADOS DE INADIMPLÊNCIA",0,0,"C",1);
$pdf->ln();
$pdf->Cell(70,$alt,"TIPO",0,0,"L",0);
$pdf->Cell(40,$alt,"QUANTIDADE",0,0,"R",0);
$pdf->Cell(40,$alt,"QTD EM DIA",0,0,"R",0);
$pdf->Cell(40,$alt,"INADIMP (%)",0,0,"R",0);
$pdf->ln();
$pdf->SetFont('Arial','',8);
$qtd_emdia = $qtd_emdia - $qtd_divida;
$percenteF = 100-(($qtd_emdia  * 100)/$qtd_calcfixo);
$percenteF = round($percenteF,2);

$pdf->Cell(70,$alt,"ISSQN FIXO",0,0,"L",0);
$pdf->Cell(40,$alt,($qtd_calcfixo-$qtd_emdia),0,0,"R",0);
$pdf->Cell(40,$alt,$qtd_emdia,0,0,"R",0);
$pdf->Cell(40,$alt,$percenteF."%",0,0,"R",0);
$pdf->ln();

$emdiavar = $qtd_calcvar - $vencido - $divida;
$percenteV = 100-(($emdiavar  * 100)/$qtd_calcvar);
$percenteV = round($percenteV,2);
$pdf->Cell(70,$alt,"ISSQN VARIÁVEL",0,0,"L",0);
$pdf->Cell(40,$alt,($qtd_calcvar-$emdiavar),0,0,"R",0);
$pdf->Cell(40,$alt,$emdiavar,0,0,"R",0);
$pdf->Cell(40,$alt,$percenteV."%",0,0,"R",0);
$pdf->ln();

$pdf->Output();

?>
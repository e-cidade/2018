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

include("fpdf151/pdf.php");
include("libs/db_sql.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

if($ponto == 's'){
  $arquivo = 'gerfsal';
  $sigla   = 'r14_';
  $head7   = 'PONTO : SALÁRIO';
}elseif($ponto == 'c'){
  $arquivo = 'gerfcom';
  $sigla   = 'r48_';
  $head7   = 'PONTO : COMPLEMENTAR';
}elseif($ponto == 'a'){
  $arquivo = 'gerfadi';
  $sigla   = 'r22_';
  $head7   = 'PONTO : ADIANTAMENTO';
}elseif($ponto == 'r'){
  $arquivo = 'gerfres';
  $sigla   = 'r20_';
  $head7   = 'PONTO : RESCISÃO';
}elseif($ponto == 'd'){
  $arquivo = 'gerfs13';
  $sigla   = 'r35_';
  $head7   = 'PONTO : 13o. SALÁRIO';
}

$head2 = "TOTAIS PARA O FPS";
$head4 = "PERIODO : ".$mes." / ".$ano;

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfont('arial','',8);
$pdf->setfillcolor(235);

$alt = 4;
$pdf->addpage();

/// TOTAL DOS INATIVOS
$sql1 =
"
select count(distinct ".$sigla."regist) as tot_func, 
       round(sum(".$sigla."valor),2)    as total_val 
from $arquivo 
     inner join rhpessoalmov      on rh02_regist = ".$sigla."regist 
                                 and rh02_anousu = ".$sigla."anousu 
                                 and rh02_mesusu = ".$sigla."mesusu 
where ".$sigla."pd != 3 
  and ".$sigla."anousu = $ano 
  and ".$sigla."mesusu = $mes
  and ".$sigla."pd     = 1
  and rh02_lota  = 71
";

$result1 = pg_exec($sql1);
db_fieldsmemory($result1,0);
$pdf->ln(4);

$pdf->cell(65,$alt,'QUANTIDADE DE INATIVOS',0,0,"L",0);
$pdf->cell(2,$alt,':',0,0,"R",0);
$pdf->cell(25,$alt,$tot_func,0,1,"R",0);
$pdf->cell(65,$alt,'VALOR BRUTO DOS INATIVOS',0,0,"L",0);
$pdf->cell(2,$alt,':',0,0,"R",0);
$pdf->cell(25,$alt,db_formatar($total_val,'f'),0,1,"R",0);

$pdf->ln(4);


//// TOTAL DAS PENSIONISTAS
$sql2 =
"
select count(distinct ".$sigla."regist) as tot_func, 
       round(sum(".$sigla."valor),2)    as total_val 
from $arquivo 
     inner join rhpessoalmov      on rh02_regist = ".$sigla."regist 
                                 and rh02_anousu = ".$sigla."anousu 
                                 and rh02_mesusu = ".$sigla."mesusu 
where ".$sigla."pd    != 3 
  and ".$sigla."anousu = $ano 
  and ".$sigla."mesusu = $mes
  and ".$sigla."pd     = 1
  and rh02_lota  = 72;
";

$result2 = pg_exec($sql2);
db_fieldsmemory($result2,0);

$pdf->cell(65,$alt,'QUANTIDADE DE PENSIONISTAS',0,0,"L",0);
$pdf->cell(2,$alt,':',0,0,"R",0);
$pdf->cell(25,$alt,$tot_func,0,1,"R",0);
$pdf->cell(65,$alt,'VALOR BRUTO DOS PENSIONISTAS',0,0,"L",0);
$pdf->cell(2,$alt,':',0,0,"R",0);
$pdf->cell(25,$alt,db_formatar($total_val,'f'),0,1,"R",0);

$pdf->ln(4);


/// TOTAL DOS FUNCIONARIOS
$sql3 =
"
select count(distinct ".$sigla."regist) as tot_func, 
       round(sum(case when ".$sigla."rubric in ('R904', 'R905', 'R906') then ".$sigla."valor else 0 end),2)    as desconto,
       sum(case when ".$sigla."rubric in ('R904', 'R905', 'R906')       then 1         else 0 end)       as total_desc,
       round(sum(case when ".$sigla."rubric <> ('R992') and ".$sigla."pd = 1  then ".$sigla."valor else 0 end),2)    as bruto,
       round(sum(case when ".$sigla."rubric =  ('R992')                 then ".$sigla."valor else 0 end),2)    as base

from $arquivo 
     inner join rhpessoalmov      on rh02_regist = ".$sigla."regist 
                                 and rh02_anousu = ".$sigla."anousu 
                                 and rh02_mesusu = ".$sigla."mesusu 
                                 and rh02_instit = ".$sigla."instit
where (".$sigla."pd != 3 or ".$sigla."rubric = 'R992')
  and ".$sigla."anousu  = $ano 
  and ".$sigla."mesusu  = $mes
  and rh02_lota   not in (71, 72, 73, 74)
  and rh02_tbprev = 2
";

$result3 = pg_exec($sql3);
db_fieldsmemory($result3,0);

$pdf->cell(65,$alt,'QUANTIDADE DE ATIVOS',0,0,"L",0);
$pdf->cell(2,$alt,':',0,0,"R",0);
$pdf->cell(25,$alt,$tot_func,0,1,"R",0);
$pdf->cell(65,$alt,'VALOR FPS DOS ATIVOS',0,0,"L",0);
$pdf->cell(2,$alt,':',0,0,"R",0);
$pdf->cell(25,$alt,db_formatar($desconto,'f'),0,1,"R",0);
$pdf->cell(65,$alt,'VALOR BRUTO DOS ATIVOS',0,0,"L",0);
$pdf->cell(2,$alt,':',0,0,"R",0);
$pdf->cell(25,$alt,db_formatar($bruto,'f'),0,1,"R",0);
$pdf->cell(65,$alt,'VALOR BASE DE CALCULO FPS',0,0,"L",0);
$pdf->cell(2,$alt,':',0,0,"R",0);
$pdf->cell(25,$alt,db_formatar($base,'f'),0,1,"R",0);

$pdf->ln(4);


/// TOTAL DOS SALARIO FAMILIA
$sql4 =
"
select count(distinct ".$sigla."regist) as tot_func, 
       round(sum(".$sigla."valor),2)    as total_val 
from $arquivo 
     inner join rhpessoalmov      on rh02_regist = ".$sigla."regist 
                                 and rh02_anousu = ".$sigla."anousu 
                                 and rh02_mesusu = ".$sigla."mesusu 
where ".$sigla."rubric = '0194' 
  and ".$sigla."anousu = $ano
  and ".$sigla."mesusu = $mes
"  ;

$result4 = pg_exec($sql4);
db_fieldsmemory($result4,0);

$pdf->cell(65,$alt,'QTD. DE SALARIO FAMILIA',0,0,"L",0);
$pdf->cell(2,$alt,':',0,0,"R",0);
$pdf->cell(25,$alt,$tot_func,0,1,"R",0);
$pdf->cell(65,$alt,'VALOR DE SALARIO FAMILIA',0,0,"L",0);
$pdf->cell(2,$alt,':',0,0,"R",0);
$pdf->cell(25,$alt,db_formatar($total_val,'f'),0,1,"R",0);

$pdf->ln(4);

/// TOTAL DOS SALARIO MATERNIDADE
$sql5 = 
"
select count(distinct ".$sigla."regist) as tot_func, 
       round(sum(case when ".$sigla."rubric in ('R904', 'R905', 'R906') then ".$sigla."valor else 0 end),2)    as desconto,
       sum(case when ".$sigla."rubric in ('R904', 'R905', 'R906')       then 1         else 0 end)       as total_desc,
       round(sum(case when ".$sigla."pd = 1  then ".$sigla."valor else 0 end),2)    as bruto,
       round(sum(case when ".$sigla."pd = 1  then ".$sigla."valor else ".$sigla."valor *(-1) end),2)    as liquido

from $arquivo 
     inner join rhpessoalmov      on rh02_regist = ".$sigla."regist 
                                 and rh02_anousu = ".$sigla."anousu 
                                 and rh02_mesusu = ".$sigla."mesusu 
                                 and rh02_instit = ".$sigla."instit
where ".$sigla."pd     != 3 
  and ".$sigla."anousu  = $ano
  and ".$sigla."mesusu  = $mes
  and rh02_lota   = 74
  and rh02_tbprev = 2
 " ;


$result5 = pg_exec($sql5);
db_fieldsmemory($result5,0);

$pdf->cell(65,$alt,'QUANTIDADE DE SALARIO MATERNIDADE',0,0,"L",0);
$pdf->cell(2,$alt,':',0,0,"R",0);
$pdf->cell(25,$alt,$tot_func,0,1,"R",0);
$pdf->cell(65,$alt,'VALOR LIQUIDO SALARIO MATERNIDADE',0,0,"L",0);
$pdf->cell(2,$alt,':',0,0,"R",0);
$pdf->cell(25,$alt,db_formatar($liquido,'f'),0,1,"R",0);
$pdf->cell(65,$alt,'VALOR BRUTO SALARIO MATERNIDADE',0,0,"L",0);
$pdf->cell(2,$alt,':',0,0,"R",0);
$pdf->cell(25,$alt,db_formatar($bruto,'f'),0,1,"R",0);
$pdf->cell(65,$alt,'QTD. SAL. MATERNIDADE COM FPS',0,0,"L",0);
$pdf->cell(2,$alt,':',0,0,"R",0);
$pdf->cell(25,$alt,$total_desc,0,1,"R",0);
$pdf->cell(65,$alt,'VALOR FPS SALARIO MATERNIDADE',0,0,"L",0);
$pdf->cell(2,$alt,':',0,0,"R",0);
$pdf->cell(25,$alt,db_formatar($desconto,'f'),0,1,"R",0);

$pdf->ln(4);


/// TOTAL DOS AUXILIO DOENCA
$sql6 =
"
select count(distinct ".$sigla."regist) as tot_func, 
       round(sum(case when ".$sigla."rubric in ('R904', 'R905', 'R906') then ".$sigla."valor else 0 end),2)    as desconto,
       sum(case when ".$sigla."rubric in ('R904', 'R905', 'R906')       then 1         else 0 end)       as total_desc,
       round(sum(case when ".$sigla."pd = 1  then ".$sigla."valor else 0 end),2)    as bruto,
       round(sum(case when ".$sigla."pd = 1  then ".$sigla."valor else ".$sigla."valor *(-1) end),2)    as liquido

from $arquivo 
     inner join rhpessoalmov      on rh02_regist = ".$sigla."regist 
                                 and rh02_anousu = ".$sigla."anousu 
                                 and rh02_mesusu = ".$sigla."mesusu 
                                 and rh02_instit = ".$sigla."instit
where ".$sigla."pd     != 3 
  and ".$sigla."anousu  = $ano
  and ".$sigla."mesusu  = $mes
  and rh02_lota   = 73
  and rh02_tbprev = 2
 " ;


$result6 = pg_exec($sql6);
db_fieldsmemory($result6,0);

$pdf->cell(65,$alt,'QUANTIDADE DE AUXILIO DOENCA',0,0,"L",0);
$pdf->cell(2,$alt,':',0,0,"R",0);
$pdf->cell(25,$alt,$tot_func,0,1,"R",0);
$pdf->cell(65,$alt,'VALOR LIQUIDO AUXILIO DOENCA',0,0,"L",0);
$pdf->cell(2,$alt,':',0,0,"R",0);
$pdf->cell(25,$alt,db_formatar($liquido,'f'),0,1,"R",0);
$pdf->cell(65,$alt,'VALOR BRUTO AUXILIO DOENCA',0,0,"L",0);
$pdf->cell(2,$alt,':',0,0,"R",0);
$pdf->cell(25,$alt,db_formatar($bruto,'f'),0,1,"R",0);
$pdf->cell(65,$alt,'QTD. AUX. DOENCA COM FPS',0,0,"L",0);
$pdf->cell(2,$alt,':',0,0,"R",0);
$pdf->cell(25,$alt,$total_desc,0,1,"R",0);
$pdf->cell(65,$alt,'VALOR FPS AUXILIO DOENCA',0,0,"L",0);
$pdf->cell(2,$alt,':',0,0,"R",0);
$pdf->cell(25,$alt,db_formatar($desconto,'f'),0,1,"R",0);

$pdf->ln(4);


/// TOTAL INATIVOS COM DESCONTO DE PREVIDENCIA
$sql7 =
"
select count(distinct ".$sigla."regist) as tot_func, 
       round(sum(case when ".$sigla."rubric in ('R904', 'R905', 'R906') then ".$sigla."valor else 0 end),2)    as desconto,
       sum(case when ".$sigla."rubric in ('R904', 'R905', 'R906')       then 1         else 0 end)       as total_desc,
       round(sum(case when ".$sigla."rubric <> ('R992') and ".$sigla."pd = 1  then ".$sigla."valor else 0 end),2)    as bruto,
       round(sum(case when ".$sigla."rubric =  ('R992')                 then ".$sigla."valor else 0 end),2)    as base

from $arquivo 
     inner join rhpessoalmov      on rh02_regist = ".$sigla."regist 
                                 and rh02_anousu = ".$sigla."anousu 
                                 and rh02_mesusu = ".$sigla."mesusu 
                                 and rh02_instit = ".$sigla."instit
where (".$sigla."pd    != 3 or ".$sigla."rubric = 'R992')
  and ".$sigla."anousu  = $ano
  and ".$sigla."mesusu  = $mes
  and rh02_lota   = 71
  and ".$sigla."regist in ( select ".$sigla."regist from $arquivo where ".$sigla."rubric in ('R904', 'R905', 'R906') and ".$sigla."anousu  = $ano and ".$sigla."mesusu  = $mes and ".$sigla."lotac = '71')
  and rh02_tbprev = 2
  ";

$result7 = pg_exec($sql7);
db_fieldsmemory($result7,0);

$pdf->cell(65,$alt,'QUANTIDADE INATIVOS COM FPS',0,0,"L",0);
$pdf->cell(2,$alt,':',0,0,"R",0);
$pdf->cell(25,$alt,$tot_func,0,1,"R",0);
$pdf->cell(65,$alt,'VALOR DO FPS DOS INATIVOS',0,0,"L",0);
$pdf->cell(2,$alt,':',0,0,"R",0);
$pdf->cell(25,$alt,db_formatar($desconto,'f'),0,1,"R",0);
$pdf->cell(65,$alt,'VALOR BRUTO DOS INATIVOS COM FPS',0,0,"L",0);
$pdf->cell(2,$alt,':',0,0,"R",0);
$pdf->cell(25,$alt,db_formatar($bruto,'f'),0,1,"R",0);
$pdf->cell(65,$alt,'VALOR BASE DE CALCULO FPS INATIVOS',0,0,"L",0);
$pdf->cell(2,$alt,':',0,0,"R",0);
$pdf->cell(25,$alt,db_formatar($base,'f'),0,1,"R",0);

$pdf->ln(4);



/// TOTAL PENSIONISTAS COM DESCONTO DE PREVIDENCIA
$sql8 =
"
select count(distinct ".$sigla."regist) as tot_func, 
       round(sum(case when ".$sigla."rubric in ('R904', 'R905', 'R906') then ".$sigla."valor else 0 end),2)    as desconto,
       sum(case when ".$sigla."rubric in ('R904', 'R905', 'R906')       then 1         else 0 end)       as total_desc,
       round(sum(case when ".$sigla."rubric <> ('R992') and ".$sigla."pd = 1  then ".$sigla."valor else 0 end),2)    as bruto,
       round(sum(case when ".$sigla."rubric =  ('R992')                 then ".$sigla."valor else 0 end),2)    as base

from $arquivo 
     inner join rhpessoalmov      on rh02_regist = ".$sigla."regist 
                                 and rh02_anousu = ".$sigla."anousu 
                                 and rh02_mesusu = ".$sigla."mesusu 
                                 and rh02_instit = ".$sigla."instit
where (".$sigla."pd    != 3 or ".$sigla."rubric = 'R992')
  and ".$sigla."anousu  = $ano
  and ".$sigla."mesusu  = $mes
  and rh02_lota   = 72
  and ".$sigla."regist in ( select ".$sigla."regist from $arquivo where ".$sigla."rubric in ('R904', 'R905', 'R906') and ".$sigla."anousu  = $ano and ".$sigla."mesusu  = $mes and ".$sigla."lotac = '72')
  and rh02_tbprev = 2
  ";


$result8 = pg_exec($sql8);
db_fieldsmemory($result8,0);

$pdf->cell(65,$alt,'QUANTIDADE PENSIONISTAS COM FPS',0,0,"L",0);
$pdf->cell(2,$alt,':',0,0,"R",0);
$pdf->cell(25,$alt,$tot_func,0,1,"R",0);
$pdf->cell(65,$alt,'VALOR DO FPS DAS PENSIONISTAS',0,0,"L",0);
$pdf->cell(2,$alt,':',0,0,"R",0);
$pdf->cell(25,$alt,db_formatar($desconto,'f'),0,1,"R",0);
$pdf->cell(65,$alt,'VALOR BRUTO DAS PENSIONISTAS COM FPS',0,0,"L",0);
$pdf->cell(2,$alt,':',0,0,"R",0);
$pdf->cell(25,$alt,db_formatar($bruto,'f'),0,1,"R",0);
$pdf->cell(65,$alt,'VALOR BASE DE CALCULO FPS PENSIONISTAS',0,0,"L",0);
$pdf->cell(2,$alt,':',0,0,"R",0);
$pdf->cell(25,$alt,db_formatar($base,'f'),0,1,"R",0);

$pdf->ln(8);

/// TOTAL DOS SALARIO FAMILIA
$sql9 =
"
select count(distinct ".$sigla."regist) as tot_func, 
       round(sum(".$sigla."valor),2)    as total_val 
from $arquivo 
     inner join rhpessoalmov      on rh02_regist = ".$sigla."regist 
                                 and rh02_anousu = ".$sigla."anousu 
                                 and rh02_mesusu = ".$sigla."mesusu 
where ".$sigla."rubric = '0148' 
  and ".$sigla."anousu = $ano
  and ".$sigla."mesusu = $mes
"  ;

$result9 = pg_exec($sql9);
db_fieldsmemory($result9,0);

$pdf->cell(85,$alt,'VALOR DA RUBRICA 0148 (PROGRAMA SAUDE DA FAMILIA)',0,0,"L",0);
$pdf->cell(2,$alt,':',0,0,"R",0);
$pdf->cell(25,$alt,db_formatar($total_val,'f'),0,1,"R",0);
$pdf->cell(85,$alt,'VALOR DO SAL. FAM. DE AUX.DOENCA E SAL.MATERNIDADE',0,0,"L",0);
$pdf->cell(2,$alt,':',0,1,"R",0);
$pdf->cell(85,$alt,'VALOR DO SAL. FAM. DOS APOSENTADOS E PENSIONISTAS',0,0,"L",0);
$pdf->cell(2,$alt,':',0,1,"R",0);



$pdf->Output();
?>
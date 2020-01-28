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
$clrotulo->label('r06_codigo');
$clrotulo->label('r06_descr');
$clrotulo->label('r06_elemen');
$clrotulo->label('r06_pd');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$rubrica = 'R992';

$sql1 = "select r06_codigo,
                r06_descr 
	 from rubricas 
	 where r06_anousu = $ano 
	   and r06_mesusu = $mes 
	   and r06_codigo = '$rubrica'";
//echo $sql1;exit;
$result1 = pg_query($sql1);
db_fieldsmemory($result1,0);
if (pg_numrows($result1) == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Rubrica não cadastrada no período de '.$mes.' / '.$ano);
}

$head3 = strtoupper($r06_descr);
$head5 = "PERÍODO : ".$mes." / ".$ano;

$previdencia = $prev;


if ( $previdencia == 1 ){
  $prev      = " and r01_tbprev = $previdencia ";
  $xbases    = " ('R992') ";
  $xrubricas = " ('R901','R902','R903','1360','1156') ";
  $xdeducao  = " ('R919','0255') ";
  $devolucao = " ('0505')";
  $cod_pagto = 2402;
}elseif ( $previdencia == 2){
  $prev      = " and r01_tbprev = $previdencia ";
  $xbases    = " ('R992') ";
  $xdeducao  = " ('0255') ";	
  $xrubricas = " ('R904','R905','R906','1350') ";
  $devolucao = " ('0501') ";	
  $cod_pagto = '';
}elseif ( $previdencia == 3){
  $prev      = " and r01_tbprev = $previdencia ";
  $xdeducao  = " ('0255') ";	
  $xbases    = " ('R992') ";
  $xrubricas = " ('R907','R908','R909') ";
  $devolucao = " ('')";
  $cod_pagto = 2402;
}elseif ( $previdencia == 0){  /// FGTS
  $prev      = " ";
  $xdeducao  = " ('') ";	
  $xbases    = " ('R991') ";
  $xrubricas = " ('') ";
  $cod_pagto =  '';
}
/*

if ( $previdencia == 1 ){
  $prev      = " and r01_tbprev = $previdencia ";
  $xbases    = " ('R992') ";
  $xrubricas = " ('R901','R902','R903') ";
  $xdeducao  = " ('R919','0255') "; 
}elseif ( $previdencia == 2){
  $prev      = " and r01_tbprev = $previdencia ";
  $xbases    = " ('R992') ";
  $xrubricas = " ('R903','R904','R905','1350','R906') ";
  $xdeducao  = " ('') ";    
}elseif ( $previdencia == 3){
  $prev      = " and r01_tbprev = $previdencia ";
  $xdeducao  = " ('') ";    
  $xbases    = " ('R992') ";
  $xrubricas = " ('R906','R907','R908') ";
}elseif ( $previdencia == 0){  /// FGTS
  $prev      = " ";
  $xdeducao  = " ('') ";    
  $xbases    = " ('R991') ";
  $xrubricas = " ('') ";
}
*/


if($previdencia != 0 ){
  $sql1 = "SELECT * 
           FROM inssirf 
       WHERE r33_anousu = $ano 
         and r33_mesusu = $mes
         and r33_codtab = $previdencia+2 limit 1
      ";
  $res1 = pg_query($sql1);
  db_fieldsmemory($res1,0);
  $perc_patro = $r33_ppatro;
}else{
  $perc_patro = 8;
  $r33_nome   = 'fgts';
}


if($tipo == 's'){
$head3 = "RELAÇÃO DA PREVIDÊNCIA - SALARIO";
$sql = "
select
       r01_regist ,
       z01_nome,
       case when recurso = 'FUNDEF 60%' and lota = '04' then 'FUNDEF 40%' else recurso end as recurso,
       sum(base)       as base,
       sum(ded)        as ded,
       sum(desco)      as desco
from 
(
select r01_regist,
       z01_nome,
       r01_lotac,
       r01_padrao,
       substr(r70_estrut,10,2) as lota,
       case rh25_recurso       
            when 1  then 'PROPRIO/LIVRE'
            when 1004 then 'PAB'
            when 4510 then 'PAB'
            when 20 then 'MDE'
            when 30 then 'FUNDEF 60%'
            when 1049 then 'FMS/PROPRIOS'
            when 4530 then 'FMS/PROPRIOS'
            when 40 then 'FMS/PROPRIOS'
            when 1058 then 'VIGILANCIA EPIDEMIOLOGICA'
            when 4710 then 'VIGILANCIA EPIDEMIOLOGICA'
            when 50 then 'FAPS'
            when 1155 then 'FARM. POPULAR'
            when 4840 then 'FARM. POPULAR'
       else 'SEM RECURSO'
       end as recurso,
       case when r14_rubric in ".$xrubricas." then r14_valor else 0 end as desco,
       case when r14_rubric in ".$xdeducao." then r14_valor else 0 end as ded ,
       case when r14_rubric in ".$xbases."    then r14_valor else 0 end as base,
       1 as tes
from gerfsal 
     inner join pessoal on r01_anousu = r14_anousu and r01_mesusu = r14_mesusu and r01_regist = r14_regist 
		                   and r01_instit = r14_instit
     inner join rhlota   on r70_codigo = to_number(r01_lotac,'9999')
		                   and r01_instit = r14_instit
     inner join (select distinct rh25_codigo, rh25_recurso from rhlotavinc where rh25_anousu = $ano ) as rhlotavinc on rh25_codigo = r70_codigo 
     inner join cgm on r01_numcgm = z01_numcgm  
where r14_anousu = $ano 
  and r14_mesusu = $mes
	and r14_instit = ".db_getsession("DB_instit")."
  $prev
  and ( r14_rubric in ".$xrubricas." 
     or r14_rubric in ".$xdeducao." 
     or r14_rubric in ".$xbases.")

union all

select r01_regist,
       z01_nome,
       r01_lotac,
       r01_padrao,
       substr(r70_estrut,10,2) as lota,
       case rh25_recurso       
            when 1  then 'PROPRIO/LIVRE'
            when 1004 then 'PAB'
            when 4510 then 'PAB'
            when 20 then 'MDE'
            when 30 then 'FUNDEF 60%'
            when 1049 then 'FMS/PROPRIOS'
            when 4530 then 'FMS/PROPRIOS'
            when 40 then 'FMS/PROPRIOS'
            when 1058 then 'VIGILANCIA EPIDEMIOLOGICA'
            when 4710 then 'VIGILANCIA EPIDEMIOLOGICA'
            when 50 then 'FAPS'
            when 1155 then 'FARM. POPULAR'
            when 4840 then 'FARM. POPULAR'
       else 'SEM RECURSO'
       end as recurso,
       case when r48_rubric in ".$xrubricas." then r48_valor else 0 end as desco,
       case when r48_rubric in ".$xdeducao." then r48_valor else 0 end as ded ,
       case when r48_rubric in ".$xbases."    then r48_valor else 0 end as base,
       2 
from gerfcom
     inner join pessoal on r01_anousu = r48_anousu and r01_mesusu = r48_mesusu and r01_regist = r48_regist
		                   and r01_instit = r48_instit
     inner join rhlota   on r70_codigo = to_number(r01_lotac,'9999')
		                   and r01_instit = r48_instit
     inner join (select distinct rh25_codigo, rh25_recurso from rhlotavinc where rh25_anousu = $ano ) as rhlotavinc on rh25_codigo = r70_codigo 
     inner join cgm on r01_numcgm = z01_numcgm
where r48_anousu = $ano
  and r48_mesusu = $mes
	and r48_instit = ".db_getsession("DB_instit")."
  $prev
  and ( r48_rubric in ".$xrubricas." 
     or r48_rubric in ".$xdeducao." 
     or r48_rubric in ".$xbases." )
                             
union all

select r01_regist,
       z01_nome,
       r01_lotac,
       r01_padrao,
       substr(r70_estrut,10,2) as lota,
       case rh25_recurso       
            when 1  then 'PROPRIO/LIVRE'
            when 1004 then 'PAB'
            when 4510 then 'PAB'
            when 20 then 'MDE'
            when 30 then 'FUNDEF 60%'
            when 1049 then 'FMS/PROPRIOS'
            when 4530 then 'FMS/PROPRIOS'
            when 40 then 'FMS/PROPRIOS'
            when 1058 then 'VIGILANCIA EPIDEMIOLOGICA'
            when 4710 then 'VIGILANCIA EPIDEMIOLOGICA'
            when 50 then 'FAPS'
            when 1155 then 'FARM. POPULAR'
            when 4840 then 'FARM. POPULAR'
       else 'SEM RECURSO'
       end as recurso,
case when r20_rubric in ".$xrubricas." then r20_valor else 0 end as desco,
       case when r20_rubric in ".$xdeducao." then r20_valor else 0 end as ded ,
       case when r20_rubric in ".$xbases."    then r20_valor else 0 end as base,
       3
from gerfres
     inner join pessoal on r01_anousu = r20_anousu and r01_mesusu = r20_mesusu and r01_regist = r20_regist
		                   and r01_instit = r20_instit
     inner join rhlota   on r70_codigo = to_number(r01_lotac,'9999')
		                   and r01_instit = r20_instit
     inner join (select distinct rh25_codigo, rh25_recurso from rhlotavinc where rh25_anousu = $ano) as rhlotavinc on rh25_codigo = r70_codigo 
     inner join cgm on r01_numcgm = z01_numcgm
where r20_anousu = $ano
  and r20_mesusu = $mes
	and r20_instit = ".db_getsession("DB_instit")."
  $prev
  and ( r20_rubric in ".$xrubricas." 
     or r20_rubric in ".$xdeducao." 
     or r20_rubric in ".$xbases.")
) as xx 
group by r01_regist,z01_nome,recurso,lota
order by recurso,z01_nome
       ";
}else{
$head3 = "RELAÇÃO DA PREVIDÊNCIA - 13o SALARIO ";
$sql = "
select
       r01_regist ,
       z01_nome,
       case when recurso = 'FUNDEF 60%' and lota = '04' then 'FUNDEF 40%' else recurso end as recurso,
       sum(base)       as base,
       sum(ded)        as ded,
       sum(desco)      as desco
from 
(
select r01_regist,
       z01_nome,
       r01_lotac,
       r01_padrao,
       substr(r70_estrut,10,2) as lota,
       case rh25_recurso       
            when 1  then 'PROPRIO/LIVRE'
            when 1004 then 'PAB'
            when 4510 then 'PAB'
            when 20 then 'MDE'
            when 30 then 'FUNDEF 60%'
            when 1049 then 'FMS/PROPRIOS'
            when 4530 then 'FMS/PROPRIOS'
            when 40 then 'FMS/PROPRIOS'
            when 1058 then 'VIGILANCIA EPIDEMIOLOGICA'
            when 4710 then 'VIGILANCIA EPIDEMIOLOGICA'
            when 50 then 'FAPS'
            when 1155 then 'FARM. POPULAR'
            when 4840 then 'FARM. POPULAR'
       else 'SEM RECURSO'
       end as recurso,
       case when r35_rubric in ".$xrubricas." then r35_valor else 0 end as desco,
       case when r35_rubric in ".$xdeducao." then r35_valor else 0 end as ded ,
       case when r35_rubric in ".$xbases."    then r35_valor else 0 end as base,
       1 as tes
from gerfs13 
     inner join pessoal on r01_anousu = r35_anousu and r01_mesusu = r35_mesusu and r01_regist = r35_regist 
		                   and r01_instit = r35_instit
     inner join rhlota   on r70_codigo = to_number(r01_lotac,'9999')
		                    and r70_instit = r35_instit 
     inner join (select distinct rh25_codigo, rh25_recurso from rhlotavinc where rh25_anousu = $ano ) as rhlotavinc on rh25_codigo = r70_codigo 
     inner join cgm on r01_numcgm = z01_numcgm  
where r35_anousu = $ano 
  and r35_mesusu = $mes
	and r35_instit = ".db_getsession("DB_instit")."
  $prev
  and ( r35_rubric in ".$xrubricas." 
     or r35_rubric in ".$xdeducao." 
     or r35_rubric in ".$xbases.")
) as xx 
group by r01_regist,z01_nome,recurso,lota
order by recurso,z01_nome
       ";

}
//echo $sql ; exit;

$head5 = "PERÍODO : ".$mes." / ".$ano;
$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem descontos de mensalidadedo sindicato no período de '.$mes.' / '.$ano);

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$func   = 0;
$func_c = 0;
$tot_c  = 0;
$total  = 0;

$tot_base       = 0;
$tot_desco     = 0;
$tot_ded         = 0;
$tot_patro      =0;

$rec_base    = 0;
$rec_desco  = 0;
$rec_ded      = 0;
$rec_patro   = 0;

$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 6;

////// TOTAL POR RECURSO

/*
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',9);
      $pdf->cell(60,$alt,'RECURSO',1,0,"C",1);
      $pdf->cell(20,$alt,'VALOR',1,1,"C",1);
      $creche = '';
      $troca = 0;
   }
   $pdf->setfont('arial','',9);
   $pdf->cell(60,$alt,$recurso,0,0,"L",0);
   $pdf->cell(20,$alt,db_formatar($valor,'f'),0,1,"R",0);
   $func   += 1;
   $func_c += 1;
   $tot_c  += $valor;
   $total  += $valor;
}
$pdf->ln(3);
$pdf->cell(115,$alt,'Total da Geral  :  '.$func,0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($total,'f'),0,1,"R",0);

*/
///// POR FUNCIONARIO

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'MATRÍC.',1,0,"C",1);
      $pdf->cell(60,$alt,'NOME',1,0,"C",1);
      $pdf->cell(25,$alt,'DEDUÇÕES',1,0,"C",1);
      $pdf->cell(25,$alt,'DESCONTO',1,0,"C",1);
      $pdf->cell(25,$alt,'BASE',1,1,"R",1);
      $quebra = '';
      $troca = 0;
   }
   if ( $quebra != $recurso ){
      if($quebra != ''){
        $pdf->ln(1);
        $pdf->cell(75,$alt,'Total do Recurso  :  '.$func_c,"T",0,"L",0);
        $pdf->cell(25,$alt,db_formatar($rec_ded,'f'),"T",0,"R",0);
        $pdf->cell(25,$alt,db_formatar($rec_desco,'f'),"T",0,"R",0);
        $pdf->cell(25,$alt,db_formatar($rec_base,'f'),"T",0,"R",0);
        $pdf->cell(0,$alt,'PATRONAL  : '.db_formatar(($rec_base / 100 * $perc_patro ),'f'),"T",1,"L",0);
        
	   $func_c = 0;
	   $tot_c  = 0;
       $rec_base   = 0;
       $rec_ded     = 0;
       $rec_desco = 0;
       $tot_patro += $rec_base / 100 * $perc_patro ;
      }
      $pdf->setfont('arial','b',9);
      $pdf->ln(4);
      $pdf->cell(50,$alt,$recurso,0,1,"L",1);
      $quebra = $recurso;
   }
   if($funcion == 's'){
     $pdf->setfont('arial','',7);
     $pdf->cell(15,$alt,$r01_regist,0,0,"C",0);
     $pdf->cell(60,$alt,$z01_nome,0,0,"L",0);
     $pdf->cell(25,$alt,db_formatar($ded,'f'),0,0,"R",0);
     $pdf->cell(25,$alt,db_formatar($desco,'f'),0,0,"R",0);
     $pdf->cell(25,$alt,db_formatar($base,'f'),0,1,"L",0);
   }

   $func           += 1;
   $func_c       += 1;

   $tot_base    += $base; 
   $tot_ded      += $ded;
   $tot_desco  += $desco;

   $rec_base   += $base;
   $rec_ded     += $ded;
   $rec_desco += $desco;
   
}
$pdf->ln(1);
$pdf->cell(75,$alt,'Total do Recurso  :  '.$func_c,"T",0,"L",0);
$pdf->cell(25,$alt,db_formatar($rec_ded,'f'),"T",0,"R",0);
$pdf->cell(25,$alt,db_formatar($rec_desco,'f'),"T",0,"R",0);
$pdf->cell(25,$alt,db_formatar($rec_base,'f'),"T",0,"R",0);
$pdf->cell(0,$alt,'PATRONAL  : '.db_formatar(($rec_base / 100 * $perc_patro ),'f'),"T",1,"L",0);
$tot_patro += $rec_base / 100 * $perc_patro ;

$pdf->ln(3);
$pdf->cell(75,$alt,'Total da Geral  :  '.$func,"T",0,"L",0);
$pdf->cell(25,$alt,db_formatar($tot_ded,'f'),"T",0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_desco,'f'),"T",0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_base,'f'),"T",0,"R",0);
$pdf->cell(0,$alt,'PATRONAL  : '.db_formatar($tot_base / 100 * $perc_patro,'f'),"T",1,"L",0);

$pdf->Output();
   
?>
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
$ano = 2005;
$mes = 12;

$head3 = "EMPENHOS DO INSS 13 SALARIO";
$head5 = "PER�ODO : ".$mes." / ".$ano;

$sql = "

select 
       rh26_orgao,
       o40_descr,
       rh26_unidade,
       o41_descr,
       rh25_projativ,
       o55_descr,
       rh25_recurso,
       o15_descr,
       fund60,
       fund60e,
       ded60,
       ded60e,
       fund40,
       fund40e,
       ded40,
       ded40e,
       inss,
       insse,
       sub,
       ded,
       dede
from 
(
select 
       rh26_orgao,
       o41_descr,
       rh26_unidade,
       o40_descr,
       rh25_projativ,
       o55_descr,
       case when rh26_orgao = 8 
                   and  rh26_unidade = 1 
                   and rh25_recurso in (1049,1058,1004) 
                 then 40 
       else rh25_recurso
       end as rh25_recurso,
       case when rh26_orgao = 8 
                   and  rh26_unidade = 1 
                   and rh25_recurso in (1049,1058,1004) 
                 then 'FMS/RECURSOS PROPRIOS' 
       else o15_descr
       end as o15_descr,
       round(sum(case when substr(r70_estrut,8,2) = '25' 
                       and substr(r70_estrut,1,1) =  '4' then inss else 0 end),2) as fund60e,
       round(sum(case when substr(r70_estrut,8,2) = '25' 
                       and substr(r70_estrut,1,1) <> '4' then inss else 0 end),2) as fund60,
       round(sum(case when substr(r70_estrut,8,2) = '26' 
                       and substr(r70_estrut,1,1) =  '4' then inss else 0 end),2) as fund40e,
       round(sum(case when substr(r70_estrut,8,2) = '26' 
                       and substr(r70_estrut,1,1) <> '4' then inss else 0 end),2) as fund40,
       round(sum(case when substr(r70_estrut,8,2) = '25' 
                       and substr(r70_estrut,1,1) =  '4' then ded else 0 end),2) as ded60e,
       round(sum(case when substr(r70_estrut,8,2) = '25' 
                       and substr(r70_estrut,1,1) <> '4' then ded else 0 end),2) as ded60,
       round(sum(case when substr(r70_estrut,8,2) = '26' 
                       and substr(r70_estrut,1,1) =  '4' then ded else 0 end),2) as ded40e,
       round(sum(case when substr(r70_estrut,8,2) = '26' 
                       and substr(r70_estrut,1,1) <> '4' then ded else 0 end),2) as ded40,
       round(sum(case when r01_padrao in ('CC07','PA57','PA58') then inss else 0 end),2) as sub, 
       round(sum(case when r01_padrao not in ('CC07','PA57','PA58') 
                       and substr(r70_estrut,1,1) =  '4'
                        or r01_padrao is null then inss else 0 end),2) as insse, 
       round(sum(case when r01_padrao not in ('CC07','PA57','PA58') 
                        and substr(r70_estrut,1,1) <>  '4'
			or r01_padrao is null then inss else 0 end),2) as inss, 
       round(sum(case when r01_padrao not in ('CC07','PA57','PA58') 
                        and substr(r70_estrut,1,1) =  '4'
			or r01_padrao is null then ded  else 0 end),2) as dede, 
       round(sum(case when r01_padrao not in ('CC07','PA57','PA58') 
                        and substr(r70_estrut,1,1) <>  '4'
			or r01_padrao is null then ded  else 0 end),2) as ded 

from 

(
select r01_regist,
       z01_nome,
       r01_lotac,
       r01_padrao,
       case when r35_rubric = 'R992' then r35_valor else 0 end as inss,
       case when r35_rubric in ('R919','0255') then r35_valor else 0 end as ded
from gerfs13 
     inner join pessoal on r01_anousu = r35_anousu and r01_mesusu = r35_mesusu and r01_regist = r35_regist 
     inner join cgm on r01_numcgm = z01_numcgm  
where r35_anousu = $ano 
  and r35_mesusu = $mes
  and r35_rubric in ('R992','R919','0255')
  and r01_tbprev in (1,3)

						     
) as x
left join rhlota on to_number(r01_lotac,'9999') = r70_codigo
left  join rhlotavinc on r70_codigo = rh25_codigo
left  join rhlotaexe  on r70_codigo = rh26_codigo
left  join orcprojativ on o55_anousu = 2005 
                      and o55_projativ = rh25_projativ
left  join orcorgao    on o40_orgao = rh26_orgao
                      and o40_anousu = 2005
left join orcunidade   on o41_anousu = 2005
                      and o41_orgao = rh26_orgao
                      and o41_unidade = rh26_unidade
left join orctiporec   on o15_codigo = rh25_recurso
		      
group by 
       rh26_orgao,
       o40_descr,
       rh26_unidade,
       o41_descr,
       rh25_projativ,
       rh25_recurso,
       o15_descr,
       o55_descr
) as xxxx
       ";
//echo $sql ; exit;

$result = pg_exec($sql);
//db_criatabela($result);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=N�o existem C�digos cadastrados no per�odo de '.$mes.' / '.$ano);

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$orgao = '';
$unidade = '';
$pdf->addpage();
$val_fgts     = 0;
$val_fgts_seg = 0;
$val_fgts_pad = 0;
$val_ded      = 0;
$val_pat      = 0;
$pat60        = 0;
$pat40        = 0;
$pat      = 0;
$teste = 0;

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
//   $teste += $inss+$sub;
   /*
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'C�DIGO',1,0,"C",1);
      $pdf->cell(25,$alt,'ESTRUTURAL',1,0,"C",1);
      $pdf->cell(80,$alt,'DESCRI��O',1,0,"C",1);
      $pdf->cell(20,$alt,'REDUZ',1,1,"C",1);
      $total = 0;
      $troca = 0;
   }
   */
   $pdf->setfont('arial','B',8);
   if($orgao != $rh26_orgao){
     $pdf->cell(15,$alt,db_formatar($rh26_orgao,'orgao'),0,0,"C",1);
     $pdf->cell(0,$alt,$o40_descr,0,1,"L",1);
     $orgao = $rh26_orgao;
   }
   if($unidade != $rh26_orgao.$rh26_unidade){
     $pdf->cell(5,$alt,'',0,0,"C",1);
     $pdf->cell(15,$alt,db_formatar($rh26_orgao,'orgao').db_formatar($rh26_unidade,'orgao'),0,0,"C",1);
     $pdf->cell(0,$alt,$o41_descr,0,1,"L",1);
     $unidade = $rh26_orgao.$rh26_unidade;
   }
   if($proj != $rh25_projativ){
     $pdf->cell(5,$alt,'',0,0,"C",1);
     $pdf->cell(15,$alt,$rh25_projativ,0,0,"C",1);
     $pdf->cell(0,$alt,$o55_descr,0,1,"L",1);
     $proj= $rh25_projativ;
   }
   $pdf->setfont('arial','',7);
   if($sub != 0){
      $pat1 = $sub / 100 * 21;
      $pdf->cell(10,$alt,'',0,0,"C",0);
      $pdf->cell(15,$alt,$rh25_recurso,0,0,"C",0);
      $pdf->cell(80,$alt,$o15_descr.'  (SUBS�DIO) ',0,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($sub,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($pat1,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar(0,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar(($pat1),'f'),0,1,"R",0);
   }
   if($rh25_recurso == 30){
     $pdf->cell(25,$alt,'',0,0,"C",0);
     if($fund60 != 0){
       $pat60 = $fund60 /100 * 21;
       $pdf->cell(80,$alt,'FUNDEF 60%',0,0,"L",0);
       $pdf->cell(20,$alt,db_formatar($fund60,'f'),0,0,"R",0);
       $pdf->cell(20,$alt,db_formatar($pat60,'f'),0,0,"R",0);
       $pdf->cell(20,$alt,db_formatar($ded60,'f'),0,0,"R",0);
       $pdf->cell(20,$alt,db_formatar(($pat60 - $ded60),'f'),0,1,"R",0);
     }
     if($fund60e != 0){
       $pat60e = $fund60e /100 * 21;
       $pdf->cell(80,$alt,'FUNDEF 60% - EMERGENCIAL',0,0,"R",0);
       $pdf->cell(20,$alt,db_formatar($fund60e,'f'),0,0,"R",0);
       $pdf->cell(20,$alt,db_formatar($pat60e,'f'),0,0,"R",0);
       $pdf->cell(20,$alt,db_formatar($ded60e,'f'),0,0,"R",0);
       $pdf->cell(20,$alt,db_formatar(($pat60e - $ded60e),'f'),0,1,"R",0);
     }  
     if($fund40 != 0){
       $pdf->cell(25,$alt,'',0,0,"C",0);
       $pat40 = $fund40 / 100 * 21;
       $pdf->cell(80,$alt,'FUNDEF 40%',0,0,"L",0);
       $pdf->cell(20,$alt,db_formatar($fund40,'f'),0,0,"R",0);
       $pdf->cell(20,$alt,db_formatar($pat40,'f'),0,0,"R",0);
       $pdf->cell(20,$alt,db_formatar($ded40,'f'),0,0,"R",0);
       $pdf->cell(20,$alt,db_formatar(($pat40 - $ded40),'f'),0,1,"R",0);
     }
     if($fund40e != 0){
       $pdf->cell(25,$alt,'',0,0,"C",0);
       $pat40e = $fund40e / 100 * 21;
       $pdf->cell(80,$alt,'FUNDEF 40% - EMERGENCIAL ',0,0,"L",0);
       $pdf->cell(20,$alt,db_formatar($fund40e,'f'),0,0,"R",0);
       $pdf->cell(20,$alt,db_formatar($pat40e,'f'),0,0,"R",0);
       $pdf->cell(20,$alt,db_formatar($ded40e,'f'),0,0,"R",0);
       $pdf->cell(20,$alt,db_formatar(($pat40e - $ded40e),'f'),0,1,"R",0);
     }
   }else{
     if(db_formatar($rh26_orgao,'orgao').db_formatar($rh26_unidade,'orgao') == '0203'){ 
       $pat = $inss / 100 * 20;
       $pate= $insse/ 100 * 20;
     }else{
       $pat = $inss / 100 * 21;
       $pate= $insse/ 100 * 21;
     }
     if($inss != 0){
       $pdf->cell(10,$alt,'',0,0,"C",0);
       $pdf->cell(15,$alt,$rh25_recurso,0,0,"C",0);
       $pdf->cell(80,$alt,$o15_descr,0,0,"L",0);
       $pdf->cell(20,$alt,db_formatar($inss,'f'),0,0,"R",0);
       $pdf->cell(20,$alt,db_formatar($pat,'f'),0,0,"R",0);
       $pdf->cell(20,$alt,db_formatar($ded,'f'),0,0,"R",0);
       $pdf->cell(20,$alt,db_formatar(($pat - $ded),'f'),0,1,"R",0);
     }
     if($insse != 0){
       $pdf->cell(10,$alt,'',0,0,"C",0);
       $pdf->cell(15,$alt,$rh25_recurso,0,0,"C",0);
       $pdf->cell(80,$alt,$o15_descr." - EMERGENCIAL",0,0,"L",0);
       $pdf->cell(20,$alt,db_formatar($insse,'f'),0,0,"R",0);
       $pdf->cell(20,$alt,db_formatar($pate,'f'),0,0,"R",0);
       $pdf->cell(20,$alt,db_formatar($dede,'f'),0,0,"R",0);
       $pdf->cell(20,$alt,db_formatar(($pate - $dede),'f'),0,1,"R",0);
     }
   }
   if(db_formatar($rh26_orgao,'orgao').db_formatar($rh26_unidade,'orgao') == '0203'){ 
     $val_pat      += (($inss+$insse+$sub+$sube)/100)*20;
   }else{
     $val_pat      += (($inss+$insse+$sub+$sube)/100)*21;
   }
   $val_fgts     += $inss+$insse+$sub+$sube;
   $val_ded      += $ded+$dede;
}

//echo $teste;exit;
   $pdf->setfont('arial','B',8);
   $pdf->cell(105,$alt,'TOTAL ',0,0,"C",0);
   $pdf->cell(20,$alt,db_formatar($val_fgts,'f'),0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($val_pat,'f'),0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($val_ded,'f'),0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($val_pat - $val_ded,'f'),0,1,"R",0);

$pdf->Output();
   
?>
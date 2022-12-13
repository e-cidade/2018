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
$mes = 8;

$head3 = "EMPENHOS DO FGTS";
$head5 = "PERÍODO : ".$mes." / ".$ano;

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
       fund40,
       fgts,
       sub
from 
(
select 
       rh26_orgao,
       o41_descr,
       rh26_unidade,
       o40_descr,
       rh25_projativ,
       o55_descr,
       rh25_recurso,
       o15_descr,
       round(sum(case when substr(r70_estrut,8,2) = '25' then inss else 0 end),2) as fund60,
       round(sum(case when substr(r70_estrut,8,2) = '26' then inss else 0 end),2) as fund40,
       round(sum(case when r01_padrao in ('CC07','PA57','PA58') then inss else 0 end),2) as sub, 
       round(sum(case when r01_padrao not in ('CC07','PA57','PA58') then inss else 0 end),2) as fgts 
from 

(
select r01_regist,
       z01_nome,
       r01_lotac,
       r01_padrao,
       case when r14_rubric = 'R991' then r14_valor else 0 end as inss
from gerfsal 
     inner join pessoal on r01_anousu = r14_anousu and r01_mesusu = r14_mesusu and r01_regist = r14_regist 
     inner join cgm on r01_numcgm = z01_numcgm  
where r14_anousu = $ano 
  and r14_mesusu = $mes
  and r14_rubric in ('R991')

union

select r01_regist,
       z01_nome,
       r01_lotac,
       r01_padrao,
       case when r48_rubric = 'R991' then r48_valor else 0 end as inss
from gerfcom
     inner join pessoal on r01_anousu = r48_anousu and r01_mesusu = r48_mesusu and r01_regist = r48_regist
     inner join cgm on r01_numcgm = z01_numcgm
where r48_anousu = $ano
  and r48_mesusu = $mes
  and r48_rubric in ('R991')
						     
union

select r01_regist,
       z01_nome,
       r01_lotac,
       r01_padrao,
       case when r20_rubric = 'R991' then r20_valor else 0 end as inss
from gerfres
     inner join pessoal on r01_anousu = r20_anousu and r01_mesusu = r20_mesusu and r01_regist = r20_regist
     inner join cgm on r01_numcgm = z01_numcgm
where r20_anousu = $ano
  and r20_mesusu = $mes
  and r20_rubric in ('R991')
						     
) as x
inner join rhlota on to_number(r01_lotac,'9999') = r70_codigo
left  join rhlotavinc on r70_codigo = rh25_codigo
left  join rhlotaexe  on r70_codigo = rh26_codigo
left  join orcprojativ on o55_anousu = $ano 
                      and o55_projativ = rh25_projativ
left  join orcorgao    on o40_orgao = rh26_orgao
                      and o40_anousu = $ano
left join orcunidade   on o41_anousu = $ano
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
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Códigos cadastrados no período de '.$mes.' / '.$ano);

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
$val_ded          = 0;
$val_pat           = 0;
$fgts1              = 0;
$fgts1_seg      = 0;
$fgts1_pad      = 0;
$pat60              = 0;
$pat40              = 0;
$pat                   = 0;

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   /*
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'CÓDIGO',1,0,"C",1);
      $pdf->cell(25,$alt,'ESTRUTURAL',1,0,"C",1);
      $pdf->cell(80,$alt,'DESCRIÇÃO',1,0,"C",1);
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
      $pdf->cell(80,$alt,$o15_descr.'  (SUBSÍDIO) ',0,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($sub,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($pat1,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar(0,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar(($pat1),'f'),0,1,"R",0);
   }
   $fgts1              = db_formatar($fgts,'p')+0;
   $fgts1_seg     = db_formatar($fgts/100*8,'p')+0;  
   $fgts1_pad     = db_formatar($fgts/100*0.5,'p')+0;
   
   $val_fgts         += $fgts1;
   $val_fgts_seg+= $fgts1_seg;  
   $val_fgts_pad+= $fgts1_pad;
   $val_fgts_tot  += $fgts1_seg + $fgts1_pad ;
   
   $pdf->cell(10,$alt,'',0,0,"C",0);
   $pdf->cell(15,$alt,$rh25_recurso,0,0,"C",0);
   $pdf->cell(80,$alt,$o15_descr,0,0,"L",0);
   $pdf->cell(20,$alt,db_formatar($fgts,'f'),0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($fgts1_seg,'f'),0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($fgts1_pad,'f'),0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar(($fgts1_seg + $fgts1_pad),'f'),0,1,"R",0);
   if($rh25_recurso == 30){
     $pdf->cell(25,$alt,'',0,0,"C",0);
     $pat60 = $fund60 /100 * 21;
     $pdf->cell(80,$alt,'FUNDEF 60%',0,0,"L",0);
     $pdf->cell(20,$alt,db_formatar($fund60,'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar(($fund60/100*8),'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar(($fund60/100*0.5),'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar(($fund60/100*8)+($fund60/100*0.5),'f'),0,1,"R",0);
     
     $pdf->cell(25,$alt,'',0,0,"C",0);
     $pat40 = $fund40 / 100 * 21;
     $pdf->cell(80,$alt,'FUNDEF 40%',0,0,"L",0);
     $pdf->cell(20,$alt,db_formatar($fund40,'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar(($fund40/100*8),'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar(($fund40/100*0.5),'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar(($fund40/100*8) + ($fund40/100*0.5) ,'f'),0,1,"R",0);
   }
}
   $pdf->setfont('arial','B',8);
   $pdf->cell(105,$alt,'TOTAL ',0,0,"C",0);
   $pdf->cell(20,$alt,db_formatar($val_fgts,'f'),0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($val_fgts_seg,'f'),0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($val_fgts_pad,'f'),0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($val_fgts_tot,'f'),0,1,"R",0);

$pdf->Output();
   
?>
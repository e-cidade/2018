<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("classes/db_inssirf_classe.php");

$clrotulo = new rotulocampo;
$clrotulo->label('r06_codigo');
$clrotulo->label('r06_descr');
$clrotulo->label('r06_elemen');
$clrotulo->label('r06_pd');

$clinssirf = new cl_inssirf;
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;
//$ano = 2006;
//$mes = 3;
if($prev == 's'){
  $tbp = 4;
  $especial = '2.90';
  $head2 = "EMPENHOS DO RPPS - SERVIDORES";
}else{
  $tbp = 6;
  $especial = '1.22';
  $head2 = "EMPENHOS DO RPPS - MAGISTERIO";
}
$res_prev = $clinssirf->sql_record($clinssirf->sql_query_file(null,
                                   db_getsession('DB_instit'),"r33_ppatro,r33_nome,r33_rubmat",
                                   "r33_nome limit 1","r33_anousu = $ano and r33_mesusu = $mes and r33_codtab = $tbp and r33_instit = ".db_getsession('DB_instit')));
db_fieldsmemory($res_prev,0);
$head4 = "PERÍODO : ".$mes." / ".$ano;
$head6 = "PATRONAL         : ".db_formatar($r33_ppatro,'f');
$head7 = "TAXA ESPECIAL : ".db_formatar($especial,'f');
$head8 = "TAXA ADMINIST : 1%";

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
       ded60,
       fund40,
       ded40,
       inss,
       sub,
       ded,
       inss/100*$r33_ppatro as pat,
       sub/100*$r33_ppatro as pat_sub,
       round(fund60/100*$r33_ppatro,2) as pat60,
       round(fund40/100*$r33_ppatro,2) as pat40,
       round(fund60/100*1,2) as ad_pat60,
       round(fund40/100*1,2) as ad_pat40,
       inss/100*1 as ad_pat,
       sub/100*1 as ad_pat_sub
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
                   and rh26_unidade = 1
                   and rh25_recurso in (1049)
                 then 40
       else rh25_recurso
       end as rh25_recurso,
       case when rh26_orgao = 8
                   and  rh26_unidade = 1
                   and rh25_recurso in (1049)
                 then 'FMS/RECURSOS PROPRIOS'
       else o15_descr
       end as o15_descr,
       round(sum(case when substr(r70_estrut,8,2) = '25' or
                           (substr(r70_estrut,1,1) = '2' and substr(r70_estrut,8,2) = '28') or
                           (substr(r70_estrut,1,1) = '2' and substr(r70_estrut,8,2) = '29') or
                           (substr(r70_estrut,1,1) = '4' and substr(r70_estrut,8,2) = '28')
                      then inss else 0 end),2) as fund60,
       round(sum(case when substr(r70_estrut,8,2) = '26' or
                           (substr(r70_estrut,1,1) = '1' and substr(r70_estrut,8,2) = '28')
                      then inss else 0 end),2) as fund40,
       round(sum(case when substr(r70_estrut,8,2) = '25' or
                           (substr(r70_estrut,1,1) = '2' and substr(r70_estrut,8,2) = '28') or
                           (substr(r70_estrut,1,1) = '4' and substr(r70_estrut,8,2) = '28')
                      then ded else 0 end),2) as ded60,
       round(sum(case when substr(r70_estrut,8,2) = '26' or
                           (substr(r70_estrut,1,1) = '1' and substr(r70_estrut,8,2) = '28')
                      then ded else 0 end),2) as ded40,
       round(sum(case when (r01_padrao in ('CC07','PA57','PA58') and rh01_funcao not in (9408,11007)) then inss else 0 end),2) as sub,
       round(sum(case when (r01_padrao not in ('CC07','PA57','PA58') or rh01_funcao in (9408,11007)) or r01_padrao is null then inss else 0 end),2) as inss,
       round(sum(case when (r01_padrao not in ('CC07','PA57','PA58') or rh01_funcao in (9408,11007)) or r01_padrao is null then ded  else 0 end),2) as ded
from

(
select rh02_regist as r01_regist,
       z01_nome,
       rh02_lota ,
       rh01_funcao,
       rh03_padrao as r01_padrao,
       rh02_instit,
       case when r14_rubric = 'R992' then r14_valor else 0 end as inss,
       case when r14_rubric in ('9919','9255') then r14_valor else 0 end as ded
from gerfsal
     inner join rhpessoalmov on rh02_anousu = r14_anousu
                            and rh02_mesusu = r14_mesusu
                            and rh02_regist = r14_regist
                            and rh02_instit = r14_instit
     inner join rhpessoal    on rh01_regist = rh02_regist
     inner join cgm on rh01_numcgm = z01_numcgm
     left join rhpespadrao   on rh03_seqpes    = rhpessoalmov.rh02_seqpes
where r14_anousu = $ano
  and r14_mesusu = $mes
  and r14_instit = ".db_getsession("DB_instit")."
  and r14_rubric in ('R992','9919','9255')
  and rh02_tbprev in (".($tbp - 2).")

union all

select rh02_regist as r01_regist,
       z01_nome,
       rh02_lota,
       rh01_funcao,
       rh03_padrao as r01_padrao,
       rh02_instit,
       case when r48_rubric = 'R992' then r48_valor else 0 end as inss,
       case when r48_rubric in ('9919','9255') then r48_valor else 0 end as ded
from gerfcom
     inner join rhpessoalmov on rh02_anousu = r48_anousu
                            and rh02_mesusu = r48_mesusu
                            and rh02_regist = r48_regist
                            and rh02_instit = r48_instit
     inner join rhpessoal on rh01_regist    = rh02_regist
     left join rhpespadrao   on rh03_seqpes    = rhpessoalmov.rh02_seqpes
     inner join cgm on rh01_numcgm = z01_numcgm
where r48_anousu = $ano
  and r48_mesusu = $mes
  and r48_instit = ".db_getsession("DB_instit")."
  and r48_rubric in ('R992','9919','9255')
  and rh02_tbprev in (".($tbp - 2).")

union all

select rh02_regist as r01_regist,
       z01_nome,
       rh02_lota,
       rh01_funcao,
       rh03_padrao as r01_padrao,
       rh02_instit,
       case when r20_rubric = 'R992' then r20_valor else 0 end as inss,
       case when r20_rubric in ('9919','9255') then r20_valor else 0 end as ded
from gerfres
     inner join rhpessoalmov on rh02_anousu = r20_anousu
                            and rh02_mesusu = r20_mesusu
                            and rh02_regist = r20_regist
                            and rh02_instit = r20_instit
     inner join rhpessoal on rh01_regist    = rh02_regist
     left join rhpespadrao   on rh03_seqpes    = rhpessoalmov.rh02_seqpes
     inner join cgm on rh01_numcgm = z01_numcgm
where r20_anousu = $ano
  and r20_mesusu = $mes
  and r20_instit = ".db_getsession("DB_instit")."
  and r20_rubric in ('R992','9919','9255')
  and rh02_tbprev in (".($tbp - 2).")

) as x
left join rhlota on r70_codigo = rh02_lota
                and r70_instit = x.rh02_instit
left join (select distinct rh25_codigo,rh25_projativ, rh25_recurso from rhlotavinc where rh25_anousu = $ano ) as rhlotavinc on rh25_codigo = r70_codigo
left  join rhlotaexe  on r70_codigo = rh26_codigo and rh26_anousu = $ano
left  join orcprojativ on o55_anousu = $ano
                      and o55_projativ = rh25_projativ
                      and o55_instit = x.rh02_instit
left  join orcorgao    on o40_orgao = rh26_orgao
                      and o40_anousu = $ano
                      and o40_instit = x.rh02_instit
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
order by rh26_orgao
       ";
//echo $sql ; exit;
$result = db_query($sql);
//db_criatabela($result);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=No existem movimentos cadastrados no perodo de '.$mes.' / '.$ano);

}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$proj  = '';
$orgao = '';
$unidade = '';
//$pdf->addpage();
$val_fgts     = 0;
$val_fgts_seg = 0;
$val_fgts_pad = 0;
$val_ded      = 0;
$val_pat      = 0;
$val_ad_pat   = 0;
$pat60        = 0;
$pat40        = 0;
$pat      = 0;
$teste = 0;

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','B',8);
      $pdf->cell(105,$alt,'DESCRIÇÃO',1,0,"C",0);
      $pdf->cell(20,$alt,'BASE',1,0,"R",0);
      $pdf->cell(20,$alt,'PATRONAL',1,0,"R",0);
      $pdf->cell(20,$alt,'TAXA ADM',1,0,"R",0);
      $pdf->cell(20,$alt,'TOTAL',1,1,"R",0);
      $troca = 0;
   }
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
      //$pat1 = $sub / 100 * 21;
      $pdf->cell(10,$alt,'',0,0,"C",0);
      $pdf->cell(15,$alt,$rh25_recurso,0,0,"C",0);
      $pdf->cell(80,$alt,$o15_descr.'  (SUBSDIO) ',0,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($sub,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($pat_sub,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($ad_pat_sub,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar(($pat_sub + $ad_pat_sub),'f'),0,1,"R",0);
   }
   if($rh25_recurso == 31){
     $pdf->cell(25,$alt,'',0,0,"C",0);
     //$pat60 = $fund60 /100 * 21;
     $pdf->cell(80,$alt,'FUNDEB 60%',0,0,"L",0);
     $pdf->cell(20,$alt,db_formatar($fund60,'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar($pat60,'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar($ad_pat60,'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar(($pat60 + $ad_pat60),'f'),0,1,"R",0);

     $pdf->cell(25,$alt,'',0,0,"C",0);
     //$pat40 = $fund40 / 100 * 21;
     $pdf->cell(80,$alt,'FUNDEB 40%',0,0,"L",0);
     $pdf->cell(20,$alt,db_formatar($fund40,'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar($pat40,'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar($ad_pat40,'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar(($pat40 + $ad_pat40),'f'),0,1,"R",0);
     $pat     = $pat60  + $pat40;
     $ad_pat  = $ad_pat60  + $ad_pat40;
     $ded     = $ded60  + $ded40;
     $inss    = $fund60 + $fund40;
   }else{
   //  if(db_formatar($rh26_orgao,'orgao').db_formatar($rh26_unidade,'orgao') == '0203'){
   //    $pat = $inss / 100 * 20;
   //  }else{
   //    $pat = $inss / 100 * 21;
   //  }
     $pdf->cell(10,$alt,'',0,0,"C",0);
     $pdf->cell(15,$alt,$rh25_recurso,0,0,"C",0);
     $pdf->cell(80,$alt,$o15_descr,0,0,"L",0);
     $pdf->cell(20,$alt,db_formatar($inss,'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar($pat,'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar($ad_pat,'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar(($pat + $ad_pat),'f'),0,1,"R",0);
   }
  // if(db_formatar($rh26_orgao,'orgao').db_formatar($rh26_unidade,'orgao') == '0203'){
  //   $val_pat      += (($inss+$sub)/100)*20;
  // }else{
  //   $val_pat      += (($inss+$sub)/100)*21;
   //}
   $val_pat      += $pat + $pat_sub;
   $val_ad_pat   += $ad_pat + $ad_pat_sub;
   $val_fgts     += $inss+$sub;
   $val_ded      += $ded;
}

$pdf->ln(5);

//echo $teste;exit;
$pdf->setfont('arial','B',8);
$pdf->cell(105,$alt,'SUB-TOTAL ','T',0,"C",0);
$pdf->cell(20,$alt,db_formatar($val_fgts,'f'),'T',0,"R",0);
$pdf->cell(20,$alt,db_formatar($val_pat ,'f'),'T',0,"R",0);
$pdf->cell(20,$alt,db_formatar($val_ad_pat,'f'),'T',0,"R",0);
$pdf->cell(20,$alt,db_formatar($val_pat + $val_ad_pat,'f'),'T',1,"R",0);

$pdf->setfont('arial','B',8);
$pdf->ln(3);
$pdf->cell(0,10,'ALÍQUOTA ESPECIAL',0,1,"L",1);
$pdf->cell(15,$alt,'05',0,0,"C",1);
$pdf->cell(0,$alt,'SECRETARIA DA FAZENDA',0,1,"L",1);

$pdf->cell(5,$alt,'',0,0,"C",1);
$pdf->cell(15,$alt,'0501',0,0,"C",1);
$pdf->cell(0,$alt,'DEPARTAMENTO DE CONTABILIDADE E FINANCAS',0,1,"L",1);

$pdf->cell(5,$alt,'',0,0,"C",1);
$pdf->cell(15,$alt,'3003',0,0,"C",1);
$pdf->cell(0,$alt,'AMORTIZACAO DA DIVIDA',0,1,"L",1);

$pdf->setfont('arial','',7);

$taxa_ad = $val_fgts/100*$especial;
$pdf->cell(10,$alt,'',0,0,"C",0);
$pdf->cell(15,$alt,'1',0,0,"C",0);
$pdf->cell(80,$alt,'LIVRE',0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($val_fgts,'f'),0,0,"R",0);
$pdf->cell(20,$alt,db_formatar($taxa_ad,'f'),0,0,"R",0);
$pdf->cell(20,$alt,db_formatar(0,'f'),0,0,"R",0);
$pdf->cell(20,$alt,db_formatar($taxa_ad,'f'),0,1,"R",0);

$pdf->ln(5);

//echo $teste;exit;
   $pdf->setfont('arial','B',8);
   $pdf->cell(105,$alt,'TOTAL GERAL','T',0,"C",0);
   $pdf->cell(20,$alt,db_formatar($val_fgts,'f'),'T',0,"R",0);
   $pdf->cell(20,$alt,db_formatar($val_pat + $taxa_ad,'f'),'T',0,"R",0);
   $pdf->cell(20,$alt,db_formatar($val_ad_pat,'f'),'T',0,"R",0);
   $pdf->cell(20,$alt,db_formatar($val_pat + $taxa_ad + $val_ad_pat,'f'),'T',1,"R",0);

$pdf->Output();

?>
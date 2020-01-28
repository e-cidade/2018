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

$res_prev = $clinssirf->sql_record($clinssirf->sql_query_file(null,
                                   db_getsession('DB_instit'),"r33_ppatro,r33_nome,r33_rubmat",
										               "r33_nome limit 1","r33_anousu = $ano and r33_mesusu = $mes and r33_codtab = 3"));
db_fieldsmemory($res_prev,0);
$head3 = "EMPENHOS DO INSS - 13o SALARIO";
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
       ded60,
       fund40,
       ded40,
       inss,
       sub,
       eme,
       ded,
       ded_eme,
       round(case when rh26_orgao = 2 and rh26_unidade = 3 then (inss)/100*20 else (inss)/100*$r33_ppatro end,2) as pat,
       round(case when rh26_orgao = 2 and rh26_unidade = 3 then (sub)/100*20 else (sub)/100*$r33_ppatro end,2) as pat_sub,
       round(eme/100*$r33_ppatro,2) as pat_eme,
       round(fund60/100*$r33_ppatro,2) as pat60,
       round(fund40/100*$r33_ppatro,2) as pat40
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
       round(sum(case when ( substr(r70_estrut,8,2) = '25' or 
                           (substr(r70_estrut,1,1) = '2' and substr(r70_estrut,8,2) = '28') or
                           (substr(r70_estrut,1,1) = '4' and substr(r70_estrut,8,2) = '28')
                           ) and rh02_codreg <> 7
                      then inss else 0 end),2) as fund60,
       round(sum(case when ( substr(r70_estrut,8,2) = '26' or 
                           (substr(r70_estrut,1,1) = '1' and substr(r70_estrut,8,2) = '28') 
                           ) and rh02_codreg <> 7
                      then inss else 0 end),2) as fund40,
       round(sum(case when ( substr(r70_estrut,8,2) = '25' or 
                           (substr(r70_estrut,1,1) = '2' and substr(r70_estrut,8,2) = '28') or
                           (substr(r70_estrut,1,1) = '4' and substr(r70_estrut,8,2) = '28')
                           ) and rh02_codreg <> 7
                      then ded else 0 end),2) as ded60,
       round(sum(case when ( substr(r70_estrut,8,2) = '26' or 
                           (substr(r70_estrut,1,1) = '1' and substr(r70_estrut,8,2) = '28') 
                           ) and rh02_codreg <> 7
                      then ded else 0 end),2) as ded40,
       round(sum(case when (r01_padrao in ('CC07','PA57','PA58') and rh01_funcao not in (9408,11007)) then inss else 0 end),2) as sub, 
       round(sum(case when rh02_codreg = 7 then inss else 0 end),2) as eme, 
       round(sum(case when rh02_codreg = 7 then ded  else 0 end),2) as ded_eme, 
       round(sum(case when ( ( r01_padrao not in ('CC07','PA57','PA58') or rh01_funcao in (9408,11007) ) or r01_padrao is null ) and rh02_codreg <> 7 then inss else 0 end),2) as inss, 
       round(sum(case when ( ( r01_padrao not in ('CC07','PA57','PA58') or rh01_funcao in (9408,11007) ) or r01_padrao is null ) and rh02_codreg <> 7 then ded  else 0 end),2) as ded 

from 

(
select rh02_regist as r01_regist,
       z01_nome,
       rh02_lota,
       rh01_funcao,
       rh03_padrao as r01_padrao,
	   rh02_instit,
       rh02_codreg,
       case when r35_rubric = 'R992' then r35_valor else 0 end as inss,
       case when r35_rubric in ('R919','0255') then r35_valor else 0 end as ded
from gerfs13 
     inner join rhpessoalmov on rh02_anousu = r35_anousu 
		                    and rh02_mesusu = r35_mesusu 
							and rh02_regist = r35_regist
		                    and rh02_instit = r35_instit
     inner join rhregime     on rh30_codreg = rh02_codreg
                            and rh30_instit = rh02_instit
	 inner join rhpessoal    on rh01_regist    = rh02_regist												
      left join rhpespadrao   on rh03_seqpes    = rhpessoalmov.rh02_seqpes
     inner join cgm on rh01_numcgm = z01_numcgm  
where r35_anousu = $ano 
  and r35_mesusu = $mes
  and r35_instit = ".db_getsession("DB_instit")."
  and r35_rubric in ('R992','R919','0255')
  and rh02_tbprev in (1,3)

) as x
left join rhlota on rh02_lota = r70_codigo
                and r70_instit = x.rh02_instit
left join (select distinct rh25_codigo,rh25_projativ, rh25_recurso from rhlotavinc where rh25_anousu = $ano ) as rhlotavinc on rh25_codigo = r70_codigo
left  join rhlotaexe  on r70_codigo = rh26_codigo and rh26_anousu = $ano
left  join orcprojativ on o55_anousu = $ano
                      and o55_projativ = rh25_projativ
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

$result = pg_exec($sql);
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
      $pdf->cell(20,$alt,'DEDUÇÕES',1,0,"R",0);
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
      $pdf->cell(20,$alt,db_formatar(0,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar(($pat_sub),'f'),0,1,"R",0);
   }
   if($eme != 0){
      //$pat1 = $sub / 100 * 21;
      $pdf->cell(10,$alt,'',0,0,"C",0);
      $pdf->cell(15,$alt,$rh25_recurso,0,0,"C",0);
      $pdf->cell(80,$alt,$o15_descr.'  (EMERGENCIAL) ',0,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($eme,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($pat_eme,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($ded_eme,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar(($pat_eme),'f'),0,1,"R",0);
   }
   if($rh25_recurso == 31){
     $pdf->cell(25,$alt,'',0,0,"C",0);
     //$pat60 = $fund60 /100 * 21;
     $pdf->cell(80,$alt,'FUNDEB 60%',0,0,"L",0);
     $pdf->cell(20,$alt,db_formatar($fund60,'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar($pat60,'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar($ded60,'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar(($pat60 - $ded60),'f'),0,1,"R",0);
     
     $pdf->cell(25,$alt,'',0,0,"C",0);
     //$pat40 = $fund40 / 100 * 21;
     $pdf->cell(80,$alt,'FUNDEB 40%',0,0,"L",0);
     $pdf->cell(20,$alt,db_formatar($fund40,'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar($pat40,'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar($ded40,'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar(($pat40 - $ded40),'f'),0,1,"R",0);
     $pat  = $pat60  + $pat40;
     $ded  = $ded60  + $ded40;
     $inss = $fund60 + $fund40;
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
     $pdf->cell(20,$alt,db_formatar($ded,'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar(($pat - $ded),'f'),0,1,"R",0);
   }
  // if(db_formatar($rh26_orgao,'orgao').db_formatar($rh26_unidade,'orgao') == '0203'){ 
  //   $val_pat      += (($inss+$sub)/100)*20;
  // }else{
  //   $val_pat      += (($inss+$sub)/100)*21;
   //}
   $val_pat      += $pat + $pat_sub + $pat_eme;
   $val_fgts     += $inss+$sub+$eme;
   $val_ded      += $ded + $ded_eme;
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
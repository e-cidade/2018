<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

include(modification("fpdf151/pdf.php"));
include(modification("libs/db_sql.php"));
include(modification("classes/db_inssirf_classe.php"));

$clrotulo = new rotulocampo;
$clrotulo->label('r06_codigo');
$clrotulo->label('r06_descr');
$clrotulo->label('r06_elemen');
$clrotulo->label('r06_pd');

$clinssirf = new cl_inssirf;
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

if($prev == 's'){

  $tbp      = 4;
  $especial = '2.90';
  $head2    = "EMPENHOS DO RPPS - SERVIDORES";
}else{

  $tbp      = 6;
  $especial = '1.22';
  $head2    = "EMPENHOS DO RPPS - MAGISTERIO";
}

$res_prev = $clinssirf->sql_record($clinssirf->sql_query_file(null,
                                   db_getsession('DB_instit'),"r33_ppatro,r33_nome,r33_rubmat",
                                   "r33_nome limit 1","r33_anousu = $ano and r33_mesusu = $mes and r33_codtab = $tbp and r33_instit = ".db_getsession('DB_instit')));

db_fieldsmemory($res_prev,0);

$head4 = "PERÍODO       : ".$mes." / ".$ano;
$head6 = "PATRONAL      : ".db_formatar($r33_ppatro,'f');
$head7 = "TAXA ESPECIAL : ".db_formatar($especial,'f');
$head8 = "TAXA ADMINIST : 1%";

$sSql  = "select rh26_orgao,                                                                                                        ";
$sSql .= "       o40_descr,                                                                                                         ";
$sSql .= "       rh26_unidade,                                                                                                      ";
$sSql .= "       o41_descr,                                                                                                         ";
$sSql .= "       rh25_projativ,                                                                                                     ";
$sSql .= "       o55_descr,                                                                                                         ";
$sSql .= "       rh25_recurso,                                                                                                      ";
$sSql .= "       o15_descr,                                                                                                         ";
$sSql .= "       fund60,                                                                                                            ";
$sSql .= "       ROUND(fund60 / 100 * 1, 2) AS ded60,                                                                               ";
$sSql .= "       fund40,                                                                                                            ";
$sSql .= "       ROUND(fund40 / 100 * 1, 2) AS ded40,                                                                               ";
$sSql .= "       inss,                                                                                                              ";
$sSql .= "       sub,                                                                                                               ";
$sSql .= "       inss / 100 * 1 AS ded,                                                                                             ";
$sSql .= "       round(case when rh26_orgao = 2 and rh26_unidade = 3 then (inss)/100*20 else (inss)/100*$r33_ppatro end,2) as pat,  ";
$sSql .= "       round(case when rh26_orgao = 2 and rh26_unidade = 3 then (sub)/100*20 else (sub)/100*$r33_ppatro end,2) as pat_sub,";
$sSql .= "       round(fund60/100*$r33_ppatro,2) as pat60,                                                                          ";
$sSql .= "       round(fund40/100*$r33_ppatro,2) as pat40                                                                           ";
$sSql .= "from                                                                                                                      ";
$sSql .= "(                                                                                                                         ";
$sSql .= "select rh26_orgao,                                                                                                        ";
$sSql .= "       o41_descr,                                                                                                         ";
$sSql .= "       rh26_unidade,                                                                                                      ";
$sSql .= "       o40_descr,                                                                                                         ";
$sSql .= "       rh25_projativ,                                                                                                     ";
$sSql .= "       o55_descr,                                                                                                         ";
$sSql .= "       case when rh26_orgao = 8                                                                                           ";
$sSql .= "                   and rh26_unidade = 1                                                                                   ";
$sSql .= "                   and rh25_recurso in (1049)                                                                             ";
$sSql .= "                 then 40                                                                                                  ";
$sSql .= "       else rh25_recurso                                                                                                  ";
$sSql .= "       end as rh25_recurso,                                                                                               ";
$sSql .= "       case when rh26_orgao = 8                                                                                           ";
$sSql .= "                   and  rh26_unidade = 1                                                                                  ";
$sSql .= "                   and rh25_recurso in (1049)                                                                             ";
$sSql .= "                 then 'FMS/RECURSOS PROPRIOS'                                                                             ";
$sSql .= "       else o15_descr                                                                                                     ";
$sSql .= "       end as o15_descr,                                                                                                  ";
$sSql .= "       round(sum(case when substr(r70_estrut,8,2) = '25' or                                                               ";
$sSql .= "                           (substr(r70_estrut,1,1) = '2' and substr(r70_estrut,8,2) = '28') or                            ";
$sSql .= "                           (substr(r70_estrut,1,1) = '4' and substr(r70_estrut,8,2) = '28')                               ";
$sSql .= "                      then inss else 0 end),2) as fund60,                                                                 ";
$sSql .= "       round(sum(case when substr(r70_estrut,8,2) = '26' or                                                               ";
$sSql .= "                           (substr(r70_estrut,1,1) = '1' and substr(r70_estrut,8,2) = '28')                               ";
$sSql .= "                      then inss else 0 end),2) as fund40,                                                                 ";
$sSql .= "       round(sum(case when substr(r70_estrut,8,2) = '25' or                                                               ";
$sSql .= "                           (substr(r70_estrut,1,1) = '2' and substr(r70_estrut,8,2) = '28') or                            ";
$sSql .= "                           (substr(r70_estrut,1,1) = '4' and substr(r70_estrut,8,2) = '28')                               ";
$sSql .= "                      then ded else 0 end),2) as ded60,                                                                   ";
$sSql .= "       round(sum(case when substr(r70_estrut,8,2) = '26' or                                                               ";
$sSql .= "                           (substr(r70_estrut,1,1) = '1' and substr(r70_estrut,8,2) = '28')                               ";
$sSql .= "                      then ded else 0 end),2) as ded40,                                                                   ";
$sSql .= "       round(sum(case when (r01_padrao in ('CC07','PA57','PA58') and rh01_funcao not in (9408,11007)) then inss else 0 end),2) as sub,                       ";
$sSql .= "       round(sum(case when (r01_padrao not in ('CC07','PA57','PA58') or rh01_funcao in (9408,11007)) or r01_padrao is null then inss else 0 end),2) as inss, ";
$sSql .= "       round(sum(case when (r01_padrao not in ('CC07','PA57','PA58') or rh01_funcao in (9408,11007)) or r01_padrao is null then ded  else 0 end),2) as ded   ";
$sSql .= "                                                                                                                          ";
$sSql .= "from                                                                                                                      ";
$sSql .= "(                                                                                                                         ";
$sSql .= "select rh02_regist as r01_regist,                                                                                         ";
$sSql .= "       z01_nome,                                                                                                          ";
$sSql .= "       rh02_lota,                                                                                                         ";
$sSql .= "       rh01_funcao,                                                                                                       ";
$sSql .= "       rh03_padrao as r01_padrao,                                                                                         ";
$sSql .= "       rh02_instit,                                                                                                       ";
$sSql .= "       case when r35_rubric = 'R992' then r35_valor else 0 end as inss,                                                   ";
$sSql .= "       case when r35_rubric in ('R919','0255') then r35_valor else 0 end as ded                                           ";
$sSql .= "from gerfs13                                                                                                              ";
$sSql .= "     inner join rhpessoalmov on rh02_anousu = r35_anousu                                                                  ";
$sSql .= "                            and rh02_mesusu = r35_mesusu                                                                  ";
$sSql .= "                            and rh02_regist = r35_regist                                                                  ";
$sSql .= "                            and rh02_instit = r35_instit                                                                  ";
$sSql .= "     inner join rhpessoal on rh01_regist = rh02_regist                                                                    ";
$sSql .= "     left  join rhpespadrao on rh03_seqpes    = rhpessoalmov.rh02_seqpes                                                  ";
$sSql .= "     inner join cgm on rh01_numcgm = z01_numcgm                                                                           ";
$sSql .= "where r35_anousu = $ano                                                                                                   ";
$sSql .= "  and r35_mesusu = $mes                                                                                                   ";
$sSql .= "  and r35_instit = ".db_getsession("DB_instit")."                                                                         ";
$sSql .= "  and r35_rubric in ('R992','R919','0255')                                                                                ";
$sSql .= "  and rh02_tbprev in (".($tbp - 2).")                                                                                     ";
$sSql .= ") as x                                                                                                                    ";
$sSql .= "left join rhlota on r70_codigo = rh02_lota                                                                                ";
$sSql .= "                and r70_instit = x.rh02_instit                                                                            ";
$sSql .= "left join (select distinct rh25_codigo,rh25_projativ, rh25_recurso from rhlotavinc where rh25_anousu = $ano ) as rhlotavinc on rh25_codigo = r70_codigo      ";
$sSql .= "left  join rhlotaexe  on r70_codigo = rh26_codigo and rh26_anousu = $ano                                                  ";
$sSql .= "left  join orcprojativ on o55_anousu = $ano                                                                               ";
$sSql .= "                      and o55_projativ = rh25_projativ                                                                    ";
$sSql .= "                      and o55_instit = x.rh02_instit                                                                      ";
$sSql .= "left  join orcorgao    on o40_orgao = rh26_orgao                                                                          ";
$sSql .= "                      and o40_anousu = $ano                                                                               ";
$sSql .= "                      and o40_instit = x.rh02_instit                                                                      ";
$sSql .= "left join orcunidade   on o41_anousu = $ano                                                                               ";
$sSql .= "                      and o41_orgao = rh26_orgao                                                                          ";
$sSql .= "                      and o41_unidade = rh26_unidade                                                                      ";
$sSql .= "left join orctiporec   on o15_codigo = rh25_recurso                                                                       ";
$sSql .= "group by                                                                                                                  ";
$sSql .= "      rh26_orgao,                                                                                                         ";
$sSql .= "      o40_descr,                                                                                                          ";
$sSql .= "      rh26_unidade,                                                                                                       ";
$sSql .= "      o41_descr,                                                                                                          ";
$sSql .= "      rh25_projativ,                                                                                                      ";
$sSql .= "      rh25_recurso,                                                                                                       ";
$sSql .= "      o15_descr,                                                                                                          ";
$sSql .= "      o55_descr                                                                                                           ";
$sSql .= ") as xxxx                                                                                                                 ";
$sSql .= "order by rh26_orgao                                                                                                       ";

$result = db_query($sSql);
$xxnum  = pg_numrows($result);

if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem movimentos cadastrados no período de '.$mes.' / '.$ano);
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca   = 1;
$alt     = 4;
$proj    = '';
$orgao   = '';
$unidade = '';

$val_fgts     = 0;
$val_fgts_seg = 0;
$val_fgts_pad = 0;
$val_ded      = 0;
$val_pat      = 0;
$val_ad_pat   = 0;
$pat60        = 0;
$pat40        = 0;
$pat          = 0;
$teste        = 0;
//Enviar uma mensagem
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
     $pdf->cell(80,$alt,'FUNDEB 60%',0,0,"L",0);
     $pdf->cell(20,$alt,db_formatar($fund60,'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar($pat60,'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar($ded60,'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar(($pat60 + $ded60),'f'),0,1,"R",0);

     $pdf->cell(25,$alt,'',0,0,"C",0);
     $pdf->cell(80,$alt,'FUNDEB 40%',0,0,"L",0);
     $pdf->cell(20,$alt,db_formatar($fund40,'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar($pat40,'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar($ded40,'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar(($pat40 + $ded40),'f'),0,1,"R",0);
     $pat  = $pat60  + $pat40;
     $ded  = $ded60  + $ded40;
     $inss = $fund60 + $fund40;

   }else{

     $pdf->cell(10,$alt,'',0,0,"C",0);
     $pdf->cell(15,$alt,$rh25_recurso,0,0,"C",0);
     $pdf->cell(80,$alt,$o15_descr,0,0,"L",0);
     $pdf->cell(20,$alt,db_formatar($inss,'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar($pat,'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar($ded,'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar(($pat + $ded),'f'),0,1,"R",0);
   }

   $val_pat      += $pat + $pat_sub;
   $val_fgts     += $inss+$sub;
   $val_ded      += $ded;
}

$pdf->ln(5);

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

$pdf->setfont('arial','B',8);
$pdf->cell(105,$alt,'TOTAL GERAL',0,0,"C",0);
$pdf->cell(20,$alt,db_formatar($val_fgts,'f'),0,0,"R",0);
$pdf->cell(20,$alt,db_formatar($val_pat + $taxa_ad,'f'),0,0,"R",0);
$pdf->cell(20,$alt,db_formatar($val_ded,'f'),0,0,"R",0);
$pdf->cell(20,$alt,db_formatar($val_pat + $val_ded,'f'),0,1,"R",0);

$pdf->Output();
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
  $sigla   = 'r14';
  $head7   = 'PONTO : SALÁRIO';
}elseif($ponto == 'c'){
  $arquivo = 'gerfcom';
  $sigla   = 'r48';
  $head7   = 'PONTO : COMPLEMENTAR';
}elseif($ponto == 'a'){
  $arquivo = 'gerfadi';
  $sigla   = 'r22';
  $head7   = 'PONTO : ADIANTAMENTO';
}elseif($ponto == 'r'){
  $arquivo = 'gerfres';
  $sigla   = 'r20';
  $head7   = 'PONTO : RESCISÃO';
}elseif($ponto == 'd'){
  $arquivo = 'gerfs13';
  $sigla   = 'r35';
  $head7   = 'PONTO : 13o. SALÁRIO';
}


$sql_basico = 
"
 select rhpessoal.*,
        rhrubricas.*,
        cgm.*,
        rhpessoalmov.*,
        rhpeslocaltrab.*,
        rhlocaltrab.*,
        rhlota.*,
        rhlotaexe.*,
        orcorgao.*,
        rhregime.*,
        rhpesbanco.*,
        ".$sigla."_regist as r14_regist,
        ".$sigla."_rubric as r14_rubric,
        ".$sigla."_valor  as r14_valor ,
        ".$sigla."_quant  as r14_quant ,
        ".$sigla."_pd     as r14_pd    
 from ".$arquivo." 
      inner join rhrubricas     on rh27_rubric = ".$sigla."_rubric
      inner join rhpessoal      on ".$sigla."_regist  = rh01_regist 
      inner join cgm            on rh01_numcgm = z01_numcgm 
      inner join rhpessoalmov   on rh01_regist = rh02_regist 
                               and rh02_anousu = ".$sigla."_anousu 
                               and rh02_mesusu = ".$sigla."_mesusu 
      left  join rhpeslocaltrab on rh02_seqpes = rh56_seqpes 
                               and rh56_princ  = true  
      left join rhlocaltrab     on rh56_localtrab = rh55_codigo
      inner join rhlota         on r70_codigo  = rh02_lota 
      left  join rhlotaexe      on rh26_codigo = r70_codigo
                               and rh26_anousu = rh02_anousu
      left  join orcorgao       on o40_anousu  = rh26_anousu
                               and o40_orgao   = rh26_orgao
      inner join rhregime       on rh02_codreg = rh30_codreg 
      left  join rhpesbanco     on rh44_seqpes = rh02_seqpes 
      where ".$sigla."_anousu  = $ano and 
            ".$sigla."_mesusu  = $mes and 
            ".$sigla."_pd     != 3 
";



$head2 = "RELAÇÃO DA CONTABILIDADE";
$head4 = "PERIODO : ".$mes." / ".$ano;
$head6 = "BANCO DO BRASIL";

///// IMPRIME FPS

$sql1_fps = 
"
select func_cef as fps_func_cef, 
       round(cef_provento - cef_desconto,2) as fps_cef_liquido, 
       func_bb_cc as fps_func_bb_cc, 
       round(bb_provento_cc - bb_desconto_cc,2) as fps_bb_liquido_cc,
       func_sem_conta as fps_func_sem_conta,
       round(provento_sem_conta - desconto_sem_conta,2) as fps_liquido_sem_conta,
       tot_pensao as fps_tot_pensao,
       round(pensao,2) as fps_pensao
from
(
select  
        count( distinct (
               case 
                 when trim(rh44_codban) = '104' and rh02_fpagto = 3 then
                   r14_regist
                 else
                   null
               end ) ) as func_cef,
        round(sum(case 
                    when r14_pd = 1 and trim(rh44_codban) = '104' and rh02_fpagto = 3 then 
                      r14_valor 
                    else 0 
                  end),2) as cef_provento , 
        round(sum(case 
                    when r14_pd = 2 and trim(rh44_codban) = '104' and rh02_fpagto = 3 then 
                      r14_valor 
                    else 0 
                  end),2) as cef_desconto , 
        count( distinct (
               case 
                 when trim(rh44_codban) = '001' and rh02_fpagto = 3 then
                   r14_regist
                 else
                   null
               end ) ) as func_bb_cc,
        round(sum(case 
                    when r14_pd = 1 and (trim(rh44_codban) = '001' and rh02_fpagto = 3) then 
                      r14_valor 
                    else 0 
                  end),2) as bb_provento_cc , 
        round(sum(case 
                    when r14_pd = 2 and (trim(rh44_codban) = '001' and rh02_fpagto = 3) then 
                      r14_valor 
                    else 0 
                  end),2) as bb_desconto_cc ,
        count( distinct (
               case 
                 when rh02_fpagto <> 3 then
                   r14_regist
                 else
                   null
               end ) ) as func_sem_conta,
        round(sum(case 
                    when r14_pd = 1 and rh02_fpagto <> 3 then 
                      r14_valor 
                    else 0 
                  end),2) as provento_sem_conta , 
        round(sum(case 
                    when r14_pd = 2 and rh02_fpagto <> 3 then 
                      r14_valor 
                    else 0 
                  end),2) as desconto_sem_conta ,

        sum(case 
              when r14_rubric   = '0333' then 
                1 
             else 0   
            end) as tot_pensao,
        round(sum(case 
                    when r14_rubric = '0333' then 
                      r14_valor 
                    else 0 
                  end),2) as pensao
 from ($sql_basico) as x1
 where rh56_localtrab = 1 
) as x
";
//echo $sql1_fps;exit;
$result1_fps = pg_exec($sql1_fps);
//db_criatabela($result1_fps);
db_fieldsmemory($result1_fps,0);



$sql2_fps =
"
select rh27_descr, rh27_rubric, round(sum(r14_valor),2) as valor

from (".$sql_basico.") as x1
where r14_pd  = 2    and
      rh56_localtrab = 1 
group by rh27_descr, rh27_rubric
order by rh27_rubric
";

$result2_fps = pg_exec($sql2_fps);
//db_criatabela($result2_fps);exit;
if(pg_numrows($result2_fps) > 0){
  db_fieldsmemory($result2_fps,0);
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfont('arial','b',8);
$pdf->setfillcolor(235);

$pdf->addpage();

$alt = 4;

$pdf->cell(0,6,"FUNDO DE PREVIDÊNCIA - APOSENTADOS E PENSIONISTAS",0,1,"C",0);

$pdf->ln(4);

$pdf->cell(55,$alt,'',1,0,"C",1);
$pdf->cell(45,$alt,'EM CONTA',1,0,"C",1);
$pdf->cell(45,$alt,'EM CH EQUE',1,0,"C",1);
$pdf->cell(45,$alt,'TODOS',1,1,"C",1);

$pdf->cell(55,$alt,'TIPO DE FOLHA',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,1,"C",1);

$pdf->cell(55,$alt,'PREFEITURA',1,0,"C",0);
$pdf->cell(15,$alt,$fps_func_bb_cc,1,0,"C",0);
$pdf->cell(30,$alt,db_formatar($fps_bb_liquido_cc,'f'),1,0,"R",0);
$pdf->cell(15,$alt,$fps_func_sem_conta,1,0,"C",0);
$pdf->cell(30,$alt,db_formatar($fps_liquido_sem_conta,'f'),1,0,"R",0);
$pdf->cell(15,$alt,$fps_func_bb_cc+$fps_func_sem_conta,1,0,"C",0);
$pdf->cell(30,$alt,db_formatar( $fps_bb_liquido_cc + $fps_liquido_sem_conta ,'f'),1,1,"R",0);

$pdf->ln(4);

$pdf->cell(55,$alt,'PENSÃO ALIMENTICIA',1,0,"C",0);
$pdf->cell(15,$alt,'',1,0,"C",0);
$pdf->cell(30,$alt,'',1,0,"C",0);
$pdf->cell(15,$alt,$fps_tot_pensao,1,0,"C",0);
$pdf->cell(30,$alt,db_formatar($fps_pensao,'f'),1,0,"R",0);
$pdf->cell(15,$alt,$fps_tot_pensao,1,0,"C",0);
$pdf->cell(30,$alt,db_formatar( $fps_pensao ,'f'),1,1,"R",0);

$pdf->ln(4);

$pdf->cell(55,$alt, 'TOTAL',1,0,"C",0);
$total_func_cc = 0;
$total_func_cc        = $fps_func_bb_cc ;
$pdf->cell(15,$alt, $total_func_cc,1,0,"C",0);
$total_liquido_cc = 0;
$total_liquido_cc     = $fps_bb_liquido_cc;

$pdf->cell(30,$alt, db_formatar( $total_liquido_cc,'f'),1,0,"R",0);
$total_func_sc = 0;
$total_func_sc        = $fps_func_sem_conta 
                      + $fps_tot_pensao;
$pdf->cell(15,$alt, $total_func_sc ,1,0,"C",0);
$total_liquido_sc = 0;
$total_liquido_sc     = $fps_liquido_sem_conta 
                      + $fps_pensao;  
$pdf->cell(30,$alt, db_formatar( $total_liquido_sc ,'f'),1,0,"R",0);


$pdf->cell(15,$alt, $total_func_cc + $total_func_sc,1,0,"C",0);
$pdf->cell(30,$alt, db_formatar( $total_liquido_cc + $total_liquido_sc ,'f'),1,1,"R",0);


$pdf->ln(6);

$alt     =  4;

$pdf->cell(0,6,"CONSIGNAÇÕES",0,1,"C",0);

$pdf->cell(80,$alt,'DESCRIÇÃO',1,0,"C",1);
$pdf->cell(15,$alt,'RUBRICA',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,1,"C",1);

$total_consig = 0;
$pdf->setfont('arial','',8);
for($x = 0; $x < pg_numrows($result2_fps);$x++){
  db_fieldsmemory($result2_fps,$x);
  $pdf->cell(80,$alt,$rh27_descr,1,0,"L",0);
  $pdf->cell(15,$alt,$rh27_rubric,1,0,"C",0);
  $pdf->cell(30,$alt,db_formatar($valor,'f'),1,1,"R",0);
  $total_consig += $valor;
}
$pdf->setfont('arial','b',8);
$pdf->cell(80,$alt,'SUB-TOTAL',1,0,"L",0);
$pdf->cell(15,$alt,'',1,0,"C",0);
$pdf->cell(30,$alt,db_formatar($total_consig,'f'),1,1,"R",0);

$pdf->ln();

$pdf->cell(80,$alt,'VALOR LÍQUIDO DA FOLHA',1,0,"L",0);
$pdf->cell(15,$alt,'',1,0,"C",0);
$pdf->cell(30,$alt,db_formatar( $total_liquido_cc + $total_liquido_sc,'f'),1,1,"R",0);

$pdf->cell(80,$alt,'VALOR DAS CONSIGNAÇÕES',1,0,"L",0);
$pdf->cell(15,$alt,'',1,0,"C",0);
$pdf->cell(30,$alt,db_formatar($total_consig,'f'),1,1,"R",0);
$pdf->cell(80,$alt,'TOTAL LÍQUIDO + CONSIGNAÇÕES',1,0,"L",0);
$pdf->cell(15,$alt,'',1,0,"C",0);
$pdf->cell(30,$alt,db_formatar($total_liquido_cc + $total_liquido_sc + $total_consig,'f'),1,1,"R",0);






/// IMPRIME OUTRAS SECRETARIAS

$sql1_outras = "
select func_cef as efe_outras_func_cef, 
       round(cef_provento - cef_desconto,2) as efe_outras_cef_liquido, 
       func_bb_cc as efe_outras_func_bb_cc, 
       round(bb_provento_cc - bb_desconto_cc,2) as efe_outras_bb_liquido_cc,
       func_sem_conta as efe_outras_func_sem_conta,
       round(provento_sem_conta - desconto_sem_conta,2) as efe_outras_liquido_sem_conta,
       tot_pensao as efe_outras_tot_pensao,
       round(pensao,2) as efe_outras_pensao
from
(
select  
        count( distinct (
               case 
                 when trim(rh44_codban) = '104' and rh02_fpagto = 3 then
                   r14_regist
                 else
                   null
               end ) ) as func_cef,
        round(sum(case 
                    when r14_pd = 1 and trim(rh44_codban) = '104' and rh02_fpagto = 3 then 
                      r14_valor 
                    else 0 
                  end),2) as cef_provento , 
        round(sum(case 
                    when r14_pd = 2 and trim(rh44_codban) = '104' and rh02_fpagto = 3 then 
                      r14_valor 
                    else 0 
                  end),2) as cef_desconto , 
        count( distinct (
               case 
                 when trim(rh44_codban) = '001' and rh02_fpagto = 3 then
                   r14_regist
                 else
                   null
               end ) ) as func_bb_cc,
        round(sum(case 
                    when r14_pd = 1 and (trim(rh44_codban) = '001' and rh02_fpagto = 3) then 
                      r14_valor 
                    else 0 
                  end),2) as bb_provento_cc , 
        round(sum(case 
                    when r14_pd = 2 and (trim(rh44_codban) = '001' and rh02_fpagto = 3) then 
                      r14_valor 
                    else 0 
                  end),2) as bb_desconto_cc ,
        count( distinct (
               case 
                 when rh02_fpagto <> 3 then
                   r14_regist
                 else
                   null
               end ) ) as func_sem_conta,
        round(sum(case 
                    when r14_pd = 1 and rh02_fpagto <> 3 then 
                      r14_valor 
                    else 0 
                  end),2) as provento_sem_conta , 
        round(sum(case 
                    when r14_pd = 2 and rh02_fpagto <> 3 then 
                      r14_valor 
                    else 0 
                  end),2) as desconto_sem_conta ,

        sum(case 
              when r14_rubric   = '0333' then 
                1 
             else 0
            end) as tot_pensao,
        round(sum(case 
                    when r14_rubric = '0333' then 
                      r14_valor 
                    else 0 
                  end),2) as pensao
 from (".$sql_basico.") as x1
 where  rh56_localtrab = 2 
) as x
";

$result1_outras = pg_exec($sql1_outras);
//db_criatabela($result1_outras);
db_fieldsmemory($result1_outras,0);


// CCS MENOS SAUDE E EDUCACAO
$sql2_outras = "
select func_cef as cc_outras_func_cef, 
       round(cef_provento - cef_desconto,2) as cc_outras_cef_liquido, 
       func_bb_cc as cc_outras_func_bb_cc, 
       round(bb_provento_cc - bb_desconto_cc,2) as cc_outras_bb_liquido_cc,
       func_sem_conta as cc_outras_func_sem_conta,
       round(provento_sem_conta - desconto_sem_conta,2) as cc_outras_liquido_sem_conta,
       tot_pensao as cc_outras_tot_pensao,
       round(pensao,2) as cc_outras_pensao
from
(
select  
        count( distinct (
               case 
                 when trim(rh44_codban) = '104' and rh02_fpagto = 3 then
                   r14_regist
                 else
                   null
               end ) ) as func_cef,
        round(sum(case 
                    when r14_pd = 1 and trim(rh44_codban) = '104' and rh02_fpagto = 3 then 
                      r14_valor 
                    else 0 
                  end),2) as cef_provento , 
        round(sum(case 
                    when r14_pd = 2 and trim(rh44_codban) = '104' and rh02_fpagto = 3 then 
                      r14_valor 
                    else 0 
                  end),2) as cef_desconto , 
        count( distinct (
               case 
                 when trim(rh44_codban) = '001' and rh02_fpagto = 3 then
                   r14_regist
                 else
                   null
               end ) ) as func_bb_cc,
        round(sum(case 
                    when r14_pd = 1 and (trim(rh44_codban) = '001' and rh02_fpagto = 3) then 
                      r14_valor 
                    else 0 
                  end),2) as bb_provento_cc , 
        round(sum(case 
                    when r14_pd = 2 and (trim(rh44_codban) = '001' and rh02_fpagto = 3) then 
                      r14_valor 
                    else 0 
                  end),2) as bb_desconto_cc ,
        count( distinct (
               case 
                 when rh02_fpagto <> 3 then
                   r14_regist
                 else
                   null
               end ) ) as func_sem_conta,
        round(sum(case 
                    when r14_pd = 1 and rh02_fpagto <> 3 then 
                      r14_valor 
                    else 0 
                  end),2) as provento_sem_conta , 
        round(sum(case 
                    when r14_pd = 2 and rh02_fpagto <> 3 then 
                      r14_valor 
                    else 0 
                  end),2) as desconto_sem_conta ,

        sum(case 
              when r14_rubric   = '0333' then 
                1 
             else 0 
            end) as tot_pensao,
        round(sum(case 
                    when r14_rubric = '0333' then 
                      r14_valor 
                    else 0 
                  end),2) as pensao
 from (".$sql_basico.") as x1
 where o40_orgao      not in (8, 9)  and
       rh56_localtrab = 9 
) as x
";

$result2_outras = pg_exec($sql2_outras);
//db_criatabela($result2_outras);exit;
db_fieldsmemory($result2_outras,0);

// CCS EDUCACAO
$sql3_outras =
"
select func_cef as cc_edu_outras_func_cef, 
       round(cef_provento - cef_desconto,2) as cc_edu_outras_cef_liquido, 
       func_bb_cc as cc_edu_outras_func_bb_cc , 
       round(bb_provento_cc - bb_desconto_cc,2) as cc_edu_outras_bb_liquido_cc,
       func_sem_conta as cc_edu_outras_func_sem_conta,
       round(provento_sem_conta - desconto_sem_conta,2) as cc_edu_outras_liquido_sem_conta,
       tot_pensao as cc_edu_outras_tot_pensao,
       round(pensao,2) as cc_edu_outras_pensao
from
(
select  
        count( distinct (
               case 
                 when trim(rh44_codban) = '104' and rh02_fpagto = 3 then
                   r14_regist
                 else
                   null
               end ) ) as func_cef,
        round(sum(case 
                    when r14_pd = 1 and trim(rh44_codban) = '104' and rh02_fpagto = 3 then 
                      r14_valor 
                    else 0 
                  end),2) as cef_provento , 
        round(sum(case 
                    when r14_pd = 2 and trim(rh44_codban) = '104' and rh02_fpagto = 3 then 
                      r14_valor 
                    else 0 
                  end),2) as cef_desconto , 
        count( distinct (
               case 
                 when trim(rh44_codban) = '001' and rh02_fpagto = 3 then
                   r14_regist
                 else
                   null
               end ) ) as func_bb_cc,
        round(sum(case 
                    when r14_pd = 1 and (trim(rh44_codban) = '001' and rh02_fpagto = 3) then 
                      r14_valor 
                    else 0 
                  end),2) as bb_provento_cc , 
        round(sum(case 
                    when r14_pd = 2 and (trim(rh44_codban) = '001' and rh02_fpagto = 3) then 
                      r14_valor 
                    else 0 
                  end),2) as bb_desconto_cc ,
        count( distinct (
               case 
                 when rh02_fpagto <> 3 then
                   r14_regist
                 else
                   null
               end ) ) as func_sem_conta,
        round(sum(case 
                    when r14_pd = 1 and rh02_fpagto <> 3 then 
                      r14_valor 
                    else 0 
                  end),2) as provento_sem_conta , 
        round(sum(case 
                    when r14_pd = 2 and rh02_fpagto <> 3 then 
                      r14_valor 
                    else 0 
                  end),2) as desconto_sem_conta ,

        sum(case 
              when r14_rubric   = '0333' then 
                1 
             else 0
            end) as tot_pensao,
        round(sum(case 
                    when r14_rubric = '0333' then 
                      r14_valor 
                    else 0 
                  end),2) as pensao
 from (".$sql_basico.") as x1
 where rh56_localtrab = 9 and
       o40_orgao   = 8     
) as x
";

$result3_outras = pg_exec($sql3_outras);
//db_criatabela($result3_outras);
db_fieldsmemory($result3_outras,0);



//// CONSIGNACOES DAS OUTRAS SECRETARIAS

$sql4_outras = 
"
select rh27_descr, rh27_rubric , round(sum(r14_valor),2) as valor
 from (".$sql_basico.") as x1
where r14_pd = 2 and
      ( rh56_localtrab = 2  
        or ( rh56_localtrab = 9 and o40_orgao <> 9 ) 
      )
group by rh27_descr, rh27_rubric
order by rh27_rubric
"; 

//echo $sql;exit;
$result4_outras = pg_exec($sql4_outras);
//db_criatabela($result4_outras);exit;
$xxnum = pg_numrows($result4_outras);
if($result4_outras == false){
  db_redireciona('db_erros.php?fechar=true&db_erro=1 - Não nenhum registro encontrado no período de '.$mes.' / '.$ano);
}

/*
$pdf->cell(0,$alt,"EFETIVOS DE OUTRAS SECRETARIAS",1,0,"C",1);

$pdf->ln(4);

$pdf->cell(50,$alt,'',1,0,"C",1);
$pdf->cell(50,$alt,'EM CONTA',1,0,"C",1);
$pdf->cell(50,$alt,'EM CH EQUE',1,0,"C",1);
$pdf->cell(50,$alt,'TODOS',1,1,"C",1);

$pdf->cell(50,$alt,'TIPO DE FOLHA',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(35,$alt,'VALOR',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(35,$alt,'VALOR',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(35,$alt,'VALOR',1,1,"C",1);

$pdf->cell(50,$alt, '',1,0,"C",1);
$pdf->cell(15,$alt, ,1,0,"C",1);
$pdf->cell(35,$alt, db_formatar( ,'f'),1,0,"C",1);
$pdf->cell(15,$alt, ,1,0,"C",1);
$pdf->cell(35,$alt, db_formatar( ,'f'),1,0,"C",1);
$pdf->cell(15,$alt, ,1,0,"C",1);
$pdf->cell(35,$alt, db_formatar(  ,'f'),1,1,"C",1);
*/

$pdf->addpage();

$alt = 4;

$pdf->cell(0,6,"EFETIVOS DE OUTRAS SECRETARIAS",0,1,"C",0);

$pdf->ln(4);

$pdf->cell(55,$alt,'',1,0,"C",1);
$pdf->cell(45,$alt,'EM CONTA',1,0,"C",1);
$pdf->cell(45,$alt,'EM CH EQUE',1,0,"C",1);
$pdf->cell(45,$alt,'TODOS',1,1,"C",1);

$pdf->cell(55,$alt,'TIPO DE FOLHA',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,1,"C",1);

$pdf->cell(55,$alt,'EFETIVOS',1,0,"C",0);
$pdf->cell(15,$alt,$efe_outras_func_bb_cc,1,0,"C",0);
$pdf->cell(30,$alt,db_formatar($efe_outras_bb_liquido_cc,'f'),1,0,"R",0);
$pdf->cell(15,$alt,$efe_outras_func_sem_conta,1,0,"C",0);
$pdf->cell(30,$alt,db_formatar($efe_outras_liquido_sem_conta,'f'),1,0,"R",0);
$pdf->cell(15,$alt,$efe_outras_func_bb_cc+$efe_outras_func_sem_conta,1,0,"C",0);
$pdf->cell(30,$alt,db_formatar( $efe_outras_bb_liquido_cc + $efe_outras_liquido_sem_conta ,'f'),1,1,"R",0);

$pdf->ln(4);

$pdf->cell(55,$alt,'PENSÃO ALIMENTICIA',1,0,"C",0);
$pdf->cell(15,$alt,'',1,0,"C",0);
$pdf->cell(30,$alt,'',1,0,"C",0);
$pdf->cell(15,$alt,$efe_outras_tot_pensao,1,0,"C",0);
$pdf->cell(30,$alt,db_formatar($efe_outras_pensao,'f'),1,0,"R",0);
$pdf->cell(15,$alt,$efe_outras_tot_pensao,1,0,"C",0);
$pdf->cell(30,$alt,db_formatar( $efe_outras_pensao ,'f'),1,1,"R",0);

$pdf->ln(4);

$pdf->cell(0,6,"CC'S DE OUTRAS SECRETARIAS E EDUCAÇÃO",0,1,"C",0);

$pdf->ln(4);

$pdf->cell(55,$alt,'',1,0,"C",1);
$pdf->cell(45,$alt,'EM CONTA',1,0,"C",1);
$pdf->cell(45,$alt,'EM CH EQUE',1,0,"C",1);
$pdf->cell(45,$alt,'TODOS',1,1,"C",1);

$pdf->cell(55,$alt,'TIPO DE FOLHA',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,1,"C",1);

$pdf->cell(55,$alt, 'CCS OUTRAS',1,0,"C",0);
$pdf->cell(15,$alt, $cc_outras_func_bb_cc,1,0,"C",0);
$pdf->cell(30,$alt, db_formatar($cc_outras_bb_liquido_cc ,'f'),1,0,"R",0);
$pdf->cell(15,$alt, $cc_outras_func_sem_conta,1,0,"C",0);
$pdf->cell(30,$alt, db_formatar( $cc_outras_liquido_sem_conta ,'f'),1,0,"R",0);
$pdf->cell(15,$alt, $cc_outras_func_bb_cc + $cc_outras_func_sem_conta,1,0,"C",0);
$pdf->cell(30,$alt, db_formatar( $cc_outras_bb_liquido_cc + $cc_outras_liquido_sem_conta ,'f'),1,1,"R",0);

$pdf->cell(55,$alt, 'CCS EDUCAÇÃO',1,0,"C",0);
$pdf->cell(15,$alt, $cc_edu_outras_func_bb_cc,1,0,"C",0);
$pdf->cell(30,$alt, db_formatar($cc_edu_outras_bb_liquido_cc ,'f'),1,0,"R",0);
$pdf->cell(15,$alt, $cc_edu_outras_func_sem_conta,1,0,"C",0);
$pdf->cell(30,$alt, db_formatar( $cc_edu_outras_liquido_sem_conta ,'f'),1,0,"R",0);
$pdf->cell(15,$alt, $cc_edu_outras_func_bb_cc + $cc_edu_outras_func_sem_conta,1,0,"C",0);
$pdf->cell(30,$alt, db_formatar( $cc_edu_outras_bb_liquido_cc + $cc_edu_outras_liquido_sem_conta ,'f'),1,1,"R",0);

$pdf->ln(4);

$pdf->cell(55,$alt, 'PENSÃO ALIMENTÍCIA',1,0,"C",0);
$pdf->cell(15,$alt, '',1,0,"C",0);
$pdf->cell(30,$alt, '',1,0,"C",0);
$pdf->cell(15,$alt, $cc_outras_tot_pensao + $cc_edu_outras_tot_pensao,1,0,"C",0);
$pdf->cell(30,$alt, db_formatar( $cc_outras_pensao + $cc_edu_outras_pensao   ,'f'),1,0,"R",0);
$pdf->cell(15,$alt, $cc_outras_tot_pensao + $cc_edu_outras_tot_pensao,1,0,"C",0);
$pdf->cell(30,$alt, db_formatar( $cc_outras_pensao + $cc_edu_outras_pensao   ,'f'),1,1,"R",0);

$pdf->ln(4);

$pdf->cell(55,$alt, 'TOTAL',1,0,"C",0);
$total_func_cc = 0;
$total_func_cc        = $efe_outras_func_bb_cc 
                      + $cc_outras_func_bb_cc
                      + $cc_edu_outras_func_bb_cc;
$pdf->cell(15,$alt, $total_func_cc,1,0,"C",0);
$total_liquido_cc = 0;
$total_liquido_cc     = $efe_outras_bb_liquido_cc
                      + $cc_outras_bb_liquido_cc
                      + $cc_edu_outras_bb_liquido_cc;

$pdf->cell(30,$alt, db_formatar( $total_liquido_cc,'f'),1,0,"R",0);
$total_func_sc = 0;
$total_func_sc        = $efe_outras_func_sem_conta 
                      + $cc_outras_func_sem_conta 
                      + $cc_edu_outras_func_sem_conta 
                      + $efe_outras_tot_pensao 
                      + $cc_outras_tot_pensao
                      + $cc_edu_outras_tot_pensao;
$pdf->cell(15,$alt, $total_func_sc ,1,0,"C",0);
$total_liquido_sc = 0;
$total_liquido_sc     = $efe_outras_liquido_sem_conta 
                      + $cc_outras_liquido_sem_conta 
                      + $cc_edu_outras_liquido_sem_conta
                      + $efe_outras_pensao
                      + $cc_outras_pensao 
                      + $cc_edu_outras_pensao; 
$pdf->cell(30,$alt, db_formatar( $total_liquido_sc ,'f'),1,0,"R",0);


$pdf->cell(15,$alt, $total_func_cc + $total_func_sc,1,0,"C",0);
$pdf->cell(30,$alt, db_formatar( $total_liquido_cc + $total_liquido_sc ,'f'),1,1,"R",0);


$pdf->ln(6);

$alt     =  4;

$pdf->cell(0,6,"CONSIGNAÇÕES",0,1,"C",0);

$pdf->cell(80,$alt,'DESCRIÇÃO',1,0,"C",1);
$pdf->cell(15,$alt,'RUBRICA',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,1,"C",1);

$total_consig = 0;
$pdf->setfont('arial','',8);
for($x = 0; $x < pg_numrows($result4_outras);$x++){
  db_fieldsmemory($result4_outras,$x);
  $pdf->cell(80,$alt,$rh27_descr,1,0,"L",0);
  $pdf->cell(15,$alt,$rh27_rubric,1,0,"C",0);
  $pdf->cell(30,$alt,db_formatar($valor,'f'),1,1,"R",0);
  $total_consig += $valor;
}
$pdf->setfont('arial','b',8);
$pdf->cell(80,$alt,'SUB-TOTAL',1,0,"L",0);
$pdf->cell(15,$alt,'',1,0,"C",0);
$pdf->cell(30,$alt,db_formatar($total_consig,'f'),1,1,"R",0);

$pdf->ln();

$pdf->cell(80,$alt,'VALOR LÍQUIDO DA FOLHA',1,0,"L",0);
$pdf->cell(15,$alt,'',1,0,"C",0);
$pdf->cell(30,$alt,db_formatar( $total_liquido_cc + $total_liquido_sc,'f'),1,1,"R",0);

$pdf->cell(80,$alt,'VALOR DAS CONSIGNAÇÕES',1,0,"L",0);
$pdf->cell(15,$alt,'',1,0,"C",0);
$pdf->cell(30,$alt,db_formatar($total_consig,'f'),1,1,"R",0);

$pdf->cell(80,$alt,'TOTAL LÍQUIDO + CONSIGNAÇÕES',1,0,"L",0);
$pdf->cell(15,$alt,'',1,0,"C",0);
$pdf->cell(30,$alt,db_formatar($total_liquido_cc + $total_liquido_sc + $total_consig,'f'),1,1,"R",0);



/////////////// IMPRIME EDU_PMA

$sql1_edu_pma = 
"
select func_cef as edu_pma_func_cef, 
       round(cef_provento - cef_desconto,2) as edu_pma_cef_liquido, 
       func_bb_cc as edu_pma_func_bb_cc, 
       round(bb_provento_cc - bb_desconto_cc,2) as edu_pma_bb_liquido_cc,
       func_sem_conta as edu_pma_func_sem_conta,
       round(provento_sem_conta - desconto_sem_conta,2) as edu_pma_liquido_sem_conta,
       tot_pensao as edu_pma_tot_pensao,
       round(pensao,2) as edu_pma_pensao
from
(
select  
        count( distinct (
               case 
                 when trim(rh44_codban) = '104' and rh02_fpagto = 3 then
                   r14_regist
                 else
                   null
               end ) ) as func_cef,
        round(sum(case 
                    when r14_pd = 1 and trim(rh44_codban) = '104' and rh02_fpagto = 3 then 
                      r14_valor 
                    else 0 
                  end),2) as cef_provento , 
        round(sum(case 
                    when r14_pd = 2 and trim(rh44_codban) = '104' and rh02_fpagto = 3 then 
                      r14_valor 
                    else 0 
                  end),2) as cef_desconto , 
        count( distinct (
               case 
                 when trim(rh44_codban) = '001' and rh02_fpagto = 3 then
                   r14_regist
                 else
                   null
               end ) ) as func_bb_cc,
        round(sum(case 
                    when r14_pd = 1 and (trim(rh44_codban) = '001' and rh02_fpagto = 3) then 
                      r14_valor 
                    else 0 
                  end),2) as bb_provento_cc , 
        round(sum(case 
                    when r14_pd = 2 and (trim(rh44_codban) = '001' and rh02_fpagto = 3) then 
                      r14_valor 
                    else 0 
                  end),2) as bb_desconto_cc ,
        count( distinct (
               case 
                 when rh02_fpagto <> 3 then
                   r14_regist
                 else
                   null
               end ) ) as func_sem_conta,
        round(sum(case 
                    when r14_pd = 1 and rh02_fpagto <> 3 then 
                      r14_valor 
                    else 0 
                  end),2) as provento_sem_conta , 
        round(sum(case 
                    when r14_pd = 2 and rh02_fpagto <> 3 then 
                      r14_valor 
                    else 0 
                  end),2) as desconto_sem_conta ,

        sum(case 
              when r14_rubric   = '0333' then 
                1 
             else 0
            end) as tot_pensao,
        round(sum(case 
                    when r14_rubric = '0333' then 
                      r14_valor 
                    else 0 
                  end),2) as pensao
 from (".$sql_basico.") as x1
 where o40_orgao   = 8    and
       rh01_clas1  = 'E'  and
       rh56_localtrab <> 9 
) as x
";
$result1_edu_pma = pg_exec($sql1_edu_pma);
//db_criatabela($result1_edu_pma);
db_fieldsmemory($result1_edu_pma,0);

$sql2_edu_pma =
"
select rh27_descr, rh27_rubric , round(sum(r14_valor),2) as valor
from (".$sql_basico.") as x1
where o40_orgao   = 8    and
      rh01_clas1  = 'E'  and
      r14_pd = 2        and
      rh56_localtrab <> 9 
group by rh27_descr, rh27_rubric
order by rh27_rubric

";
$result2_edu_pma = pg_exec($sql2_edu_pma);
//db_criatabela($result2_edu_pma);exit;
db_fieldsmemory($result2_edu_pma,0);

$pdf->addpage();

$alt = 4;

$pdf->cell(0,6,"EDUCAÇÃO PAGO COM RECURSOS DA  P.M.A.",0,1,"C",0);

$pdf->ln(4);

$pdf->cell(55,$alt,'',1,0,"C",1);
$pdf->cell(45,$alt,'EM CONTA',1,0,"C",1);
$pdf->cell(45,$alt,'EM CH EQUE',1,0,"C",1);
$pdf->cell(45,$alt,'TODOS',1,1,"C",1);

$pdf->cell(55,$alt,'TIPO DE FOLHA',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,1,"C",1);

$pdf->cell(55,$alt,'FOLHA - E',1,0,"C",0);
$pdf->cell(15,$alt,$edu_pma_func_bb_cc,1,0,"C",0);
$pdf->cell(30,$alt,db_formatar($edu_pma_bb_liquido_cc,'f'),1,0,"R",0);
$pdf->cell(15,$alt,$edu_pma_func_sem_conta,1,0,"C",0);
$pdf->cell(30,$alt,db_formatar($edu_pma_liquido_sem_conta,'f'),1,0,"R",0);
$pdf->cell(15,$alt,$edu_pma_func_bb_cc+$edu_pma_func_sem_conta,1,0,"C",0);
$pdf->cell(30,$alt,db_formatar( $edu_pma_bb_liquido_cc + $edu_pma_liquido_sem_conta ,'f'),1,1,"R",0);

$pdf->ln(4);

$pdf->cell(55,$alt,'PENSÃO ALIMENTICIA',1,0,"C",0);
$pdf->cell(15,$alt,'',1,0,"C",0);
$pdf->cell(30,$alt,'',1,0,"C",0);
$pdf->cell(15,$alt,$edu_pma_tot_pensao,1,0,"C",0);
$pdf->cell(30,$alt,db_formatar($edu_pma_pensao,'f'),1,0,"R",0);
$pdf->cell(15,$alt,$edu_pma_tot_pensao,1,0,"C",0);
$pdf->cell(30,$alt,db_formatar( $edu_pma_pensao ,'f'),1,1,"R",0);

$pdf->ln(4);

$pdf->cell(55,$alt, 'TOTAL',1,0,"C",0);
$total_func_cc = 0;
$total_func_cc        = $edu_pma_func_bb_cc;
$pdf->cell(15,$alt, $total_func_cc,1,0,"C",0);
$total_liquido_cc = 0;
$total_liquido_cc     = $edu_pma_bb_liquido_cc;

$pdf->cell(30,$alt, db_formatar( $total_liquido_cc,'f'),1,0,"R",0);
$total_func_sc = 0;
$total_func_sc        = $edu_pma_func_sem_conta 
                      + $edu_pma_tot_pensao;      
$pdf->cell(15,$alt, $total_func_sc ,1,0,"C",0);
$total_liquido_sc = 0;
$total_liquido_sc     = $edu_pma_liquido_sem_conta 
                      + $edu_pma_pensao; 
$pdf->cell(30,$alt, db_formatar( $total_liquido_sc ,'f'),1,0,"R",0);


$pdf->cell(15,$alt, $total_func_cc + $total_func_sc,1,0,"C",0);
$pdf->cell(30,$alt, db_formatar( $total_liquido_cc + $total_liquido_sc ,'f'),1,1,"R",0);


$pdf->ln(6);

$alt     =  4;

$pdf->cell(0,6,"CONSIGNAÇÕES",0,1,"C",0);

$pdf->cell(80,$alt,'DESCRIÇÃO',1,0,"C",1);
$pdf->cell(15,$alt,'RUBRICA',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,1,"C",1);

$total_consig = 0;
$pdf->setfont('arial','',8);
for($x = 0; $x < pg_numrows($result2_edu_pma);$x++){
  db_fieldsmemory($result2_edu_pma,$x);
  $pdf->cell(80,$alt,$rh27_descr,1,0,"L",0);
  $pdf->cell(15,$alt,$rh27_rubric,1,0,"C",0);
  $pdf->cell(30,$alt,db_formatar($valor,'f'),1,1,"R",0);
  $total_consig += $valor;
}
$pdf->setfont('arial','b',8);
$pdf->cell(80,$alt,'SUB-TOTAL',1,0,"L",0);
$pdf->cell(15,$alt,'',1,0,"C",0);
$pdf->cell(30,$alt,db_formatar($total_consig,'f'),1,1,"R",0);

$pdf->ln();

$pdf->cell(80,$alt,'VALOR LÍQUIDO DA FOLHA',1,0,"L",0);
$pdf->cell(15,$alt,'',1,0,"C",0);
$pdf->cell(30,$alt,db_formatar( $total_liquido_cc + $total_liquido_sc,'f'),1,1,"R",0);

$pdf->cell(80,$alt,'VALOR DAS CONSIGNAÇÕES',1,0,"L",0);
$pdf->cell(15,$alt,'',1,0,"C",0);
$pdf->cell(30,$alt,db_formatar($total_consig,'f'),1,1,"R",0);

$pdf->cell(80,$alt,'TOTAL LÍQUIDO + CONSIGNAÇÕES',1,0,"L",0);
$pdf->cell(15,$alt,'',1,0,"C",0);
$pdf->cell(30,$alt,db_formatar($total_liquido_cc + $total_liquido_sc + $total_consig,'f'),1,1,"R",0);





////// IMPRIME SAUDE

$sql1_saude =
"
select func_cef as saude_func_cef, 
       cef_provento - cef_desconto as saude_cef_liquido, 
       func_bb_cc as saude_func_bb_cc, 
       bb_provento_cc - bb_desconto_cc as saude_bb_liquido_cc,
       func_sem_conta as saude_func_sem_conta,
       provento_sem_conta - desconto_sem_conta as saude_liquido_sem_conta,
       tot_pensao as saude_tot_pensao ,
       pensao as saude_pensao
from
(
select  
        count( distinct (
               case 
                 when trim(rh44_codban) = '104' and rh02_fpagto = 3 then
                   r14_regist
                 else
                   null
               end ) ) as func_cef,
        round(sum(case 
                    when r14_pd = 1 and trim(rh44_codban) = '104' and rh02_fpagto = 3 then 
                      r14_valor 
                    else 0 
                  end),2) as cef_provento , 
        round(sum(case 
                    when r14_pd = 2 and trim(rh44_codban) = '104' and rh02_fpagto = 3 then 
                      r14_valor 
                    else 0 
                  end),2) as cef_desconto , 
        count( distinct (
               case 
                 when trim(rh44_codban) = '001' and rh02_fpagto = 3 then
                   r14_regist
                 else
                   null
               end ) ) as func_bb_cc,
        round(sum(case 
                    when r14_pd = 1 and (trim(rh44_codban) = '001' and rh02_fpagto = 3) then 
                      r14_valor 
                    else 0 
                  end),2) as bb_provento_cc , 
        round(sum(case 
                    when r14_pd = 2 and (trim(rh44_codban) = '001' and rh02_fpagto = 3) then 
                      r14_valor 
                    else 0 
                  end),2) as bb_desconto_cc ,
        count( distinct (
               case 
                 when rh02_fpagto <> 3 then
                   r14_regist
                 else
                   null
               end ) ) as func_sem_conta,
        round(sum(case 
                    when r14_pd = 1 and rh02_fpagto <> 3 then 
                      r14_valor 
                    else 0 
                  end),2) as provento_sem_conta , 
        round(sum(case 
                    when r14_pd = 2 and rh02_fpagto <> 3 then 
                      r14_valor 
                    else 0 
                  end),2) as desconto_sem_conta ,

        sum(case 
              when r14_rubric   = '0333' then 
                1 
             else 0 
            end) as tot_pensao,
        round(sum(case 
                    when r14_rubric = '0333' then 
                      r14_valor 
                    else 0 
                  end),2) as pensao
 from (".$sql_basico.") as x1
 where rh56_localtrab = 4 
) as x
";
$result1_saude = pg_exec($sql1_saude);
//db_criatabela($result1_saude);
db_fieldsmemory($result1_saude,0);



// CCS DA SAUDE

$sql2_saude=
"
select func_cef as saude_cc_func_cef, 
       cef_provento - cef_desconto as saude_cc_cef_liquido, 
       func_bb_cc as saude_cc_func_bb_cc, 
       bb_provento_cc - bb_desconto_cc as saude_cc_bb_liquido_cc,
       func_sem_conta as saude_cc_func_sem_conta,
       provento_sem_conta - desconto_sem_conta as saude_cc_liquido_sem_conta,
       tot_pensao as saude_cc_tot_pensao,
       pensao as saude_cc_pensao
from
(
select  
        count( distinct (
               case 
                 when trim(rh44_codban) = '104' and rh02_fpagto = 3 then
                   r14_regist
                 else
                   null
               end ) ) as func_cef,
        round(sum(case 
                    when r14_pd = 1 and trim(rh44_codban) = '104' and rh02_fpagto = 3 then 
                      r14_valor 
                    else 0 
                  end),2) as cef_provento , 
        round(sum(case 
                    when r14_pd = 2 and trim(rh44_codban) = '104' and rh02_fpagto = 3 then 
                      r14_valor 
                    else 0 
                  end),2) as cef_desconto , 
        count( distinct (
               case 
                 when trim(rh44_codban) = '001' and rh02_fpagto = 3 then
                   r14_regist
                 else
                   null
               end ) ) as func_bb_cc,
        round(sum(case 
                    when r14_pd = 1 and (trim(rh44_codban) = '001' and rh02_fpagto = 3) then 
                      r14_valor 
                    else 0 
                  end),2) as bb_provento_cc , 
        round(sum(case 
                    when r14_pd = 2 and (trim(rh44_codban) = '001' and rh02_fpagto = 3) then 
                      r14_valor 
                    else 0 
                  end),2) as bb_desconto_cc ,
        count( distinct (
               case 
                 when rh02_fpagto <> 3 then
                   r14_regist
                 else
                   null
               end ) ) as func_sem_conta,
        round(sum(case 
                    when r14_pd = 1 and rh02_fpagto <> 3 then 
                      r14_valor 
                    else 0 
                  end),2) as provento_sem_conta , 
        round(sum(case 
                    when r14_pd = 2 and rh02_fpagto <> 3 then 
                      r14_valor 
                    else 0 
                  end),2) as desconto_sem_conta ,

        sum(case 
              when r14_rubric   = '0333' then 
                1 
             else 0 
            end) as tot_pensao,
        round(sum(case 
                    when r14_rubric = '0333' then 
                      r14_valor 
                    else 0 
                  end),2) as pensao
 from (".$sql_basico.") as x1
 where rh56_localtrab = 9 and
       o40_orgao      = 9     
) as x
";
$result2_saude = pg_exec($sql2_saude);
//db_criatabela($result2_saude);
db_fieldsmemory($result2_saude,0);



$sql3_saude = 
"
select rh27_descr, rh27_rubric, round(sum(r14_valor),2) as valor
 from (".$sql_basico.") as x1 
 where r14_pd = 2 and
      ( rh56_localtrab = 4
        or (rh56_localtrab = 9 and o40_orgao = 9 )
      )
            
group by rh27_descr, rh27_rubric
order by rh27_rubric
";            
$result3_saude = pg_exec($sql3_saude);
//db_criatabela($result3_saude);exit;

$pdf->addpage();

$alt = 4;

$pdf->cell(0,6,"PAGOS COM RECURSOS DA SEC. DE SAÚDE",0,1,"C",0);

$pdf->ln(4);

$pdf->cell(55,$alt,'',1,0,"C",1);
$pdf->cell(45,$alt,'EM CONTA',1,0,"C",1);
$pdf->cell(45,$alt,'EM CH EQUE',1,0,"C",1);
$pdf->cell(45,$alt,'TODOS',1,1,"C",1);

$pdf->cell(55,$alt,'TIPO DE FOLHA',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,1,"C",1);

$pdf->cell(55,$alt,'EFETIVOS',1,0,"C",0);
$pdf->cell(15,$alt,$saude_func_bb_cc,1,0,"C",0);
$pdf->cell(30,$alt,db_formatar($saude_bb_liquido_cc,'f'),1,0,"R",0);
$pdf->cell(15,$alt,$saude_func_sem_conta,1,0,"C",0);
$pdf->cell(30,$alt,db_formatar($saude_liquido_sem_conta,'f'),1,0,"R",0);
$pdf->cell(15,$alt,$saude_func_bb_cc+$saude_func_sem_conta,1,0,"C",0);
$pdf->cell(30,$alt,db_formatar( $saude_bb_liquido_cc + $saude_liquido_sem_conta ,'f'),1,1,"R",0);

$pdf->ln(4);

$pdf->cell(55,$alt,'PENSÃO ALIMENTICIA',1,0,"C",0);
$pdf->cell(15,$alt,'',1,0,"C",0);
$pdf->cell(30,$alt,'',1,0,"C",0);
$pdf->cell(15,$alt,$saude_tot_pensao,1,0,"C",0);
$pdf->cell(30,$alt,db_formatar($saude_pensao,'f'),1,0,"R",0);
$pdf->cell(15,$alt,$saude_tot_pensao,1,0,"C",0);
$pdf->cell(30,$alt,db_formatar( $saude_pensao,'f'),1,1,"R",0);

$pdf->ln(4);

$pdf->cell(0,6,"CC'S DA SAÚDE",0,1,"C",0);

$pdf->ln(4);

$pdf->cell(55,$alt,'',1,0,"C",1);
$pdf->cell(45,$alt,'EM CONTA',1,0,"C",1);
$pdf->cell(45,$alt,'EM CH EQUE',1,0,"C",1);
$pdf->cell(45,$alt,'TODOS',1,1,"C",1);

$pdf->cell(55,$alt,'TIPO DE FOLHA',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,1,"C",1);

$pdf->cell(55,$alt, 'CCS',1,0,"C",0);
$pdf->cell(15,$alt, $saude_cc_func_bb_cc, 1,0,"C",0);
$pdf->cell(30,$alt, db_formatar($saude_cc_bb_liquido_cc ,'f'),1,0,"R",0);
$pdf->cell(15,$alt, $saude_cc_func_sem_conta,1,0,"C",0);
$pdf->cell(30,$alt, db_formatar( $saude_cc_liquido_sem_conta ,'f'),1,0,"R",0);
$pdf->cell(15,$alt, $saude_cc_func_bb_cc + $saude_cc_func_sem_conta,1,0,"C",0);
$pdf->cell(30,$alt, db_formatar( $saude_cc_bb_liquido_cc + $saude_cc_liquido_sem_conta ,'f'),1,1,"R",0);

$pdf->ln(4);

$pdf->cell(55,$alt, 'PENSÃO ALIMENTÍCIA',1,0,"C",0);
$pdf->cell(15,$alt, '',1,0,"C",0);
$pdf->cell(30,$alt, '',1,0,"C",0);
$pdf->cell(15,$alt, $saude_cc_tot_pensao,1,0,"C",0);
$pdf->cell(30,$alt, db_formatar( $saude_cc_pensao ,'f'),1,0,"R",0);
$pdf->cell(15,$alt, $saude_cc_tot_pensao,1,0,"C",0);
$pdf->cell(30,$alt, db_formatar( $saude_cc_tot_pensao ,'f'),1,1,"R",0);

$pdf->ln(4);

$pdf->cell(55,$alt, 'TOTAL',1,0,"C",0);
$total_func_cc = 0;
$total_func_cc        = $saude_func_bb_cc 
                      + $saude_cc_func_bb_cc;
$pdf->cell(15,$alt, $total_func_cc,1,0,"C",0);
$total_liquido_cc = 0;
$total_liquido_cc     = $saude_bb_liquido_cc
                      + $saude_cc_bb_liquido_cc;

$pdf->cell(30,$alt, db_formatar( $total_liquido_cc,'f'),1,0,"R",0);
$total_func_sc = 0;
$total_func_sc        = $saude_func_sem_conta 
                      + $saude_cc_func_sem_conta 
                      + $saude_tot_pensao
                      + $saude_cc_tot_pensao; 
$pdf->cell(15,$alt, $total_func_sc ,1,0,"C",0);
$total_liquido_sc = 0;
$total_liquido_sc     = $saude_liquido_sem_conta 
                      + $saude_cc_liquido_sem_conta 
                      + $saude_pensao
                      + $saude_cc_pensao;
$pdf->cell(30,$alt, db_formatar( $total_liquido_sc ,'f'),1,0,"R",0);


$pdf->cell(15,$alt, $total_func_cc + $total_func_sc,1,0,"C",0);
$pdf->cell(30,$alt, db_formatar( $total_liquido_cc + $total_liquido_sc ,'f'),1,1,"R",0);


$pdf->ln(6);

$alt     =  4;

$pdf->cell(0,6,"CONSIGNAÇÕES",0,1,"C",0);

$pdf->cell(80,$alt,'DESCRIÇÃO',1,0,"C",1);
$pdf->cell(15,$alt,'RUBRICA',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,1,"C",1);

$total_consig = 0;
$pdf->setfont('arial','',8);
for($x = 0; $x < pg_numrows($result3_saude);$x++){
  db_fieldsmemory($result3_saude,$x);
  $pdf->cell(80,$alt,$rh27_descr,1,0,"L",0);
  $pdf->cell(15,$alt,$rh27_rubric,1,0,"C",0);
  $pdf->cell(30,$alt,db_formatar($valor,'f'),1,1,"R",0);
  $total_consig += $valor;
}
$pdf->setfont('arial','b',8);
$pdf->cell(80,$alt,'SUB-TOTAL',1,0,"L",0);
$pdf->cell(15,$alt,'',1,0,"C",0);
$pdf->cell(30,$alt,db_formatar($total_consig,'f'),1,1,"R",0);

$pdf->ln();

$pdf->cell(80,$alt,'VALOR LÍQUIDO DA FOLHA',1,0,"L",0);
$pdf->cell(15,$alt,'',1,0,"C",0);
$pdf->cell(30,$alt,db_formatar( $total_liquido_cc + $total_liquido_sc,'f'),1,1,"R",0);

$pdf->cell(80,$alt,'VALOR DAS CONSIGNAÇÕES',1,0,"L",0);
$pdf->cell(15,$alt,'',1,0,"C",0);
$pdf->cell(30,$alt,db_formatar($total_consig,'f'),1,1,"R",0);

$pdf->cell(80,$alt,'TOTAL LÍQUIDO + CONSIGNAÇÕES',1,0,"L",0);
$pdf->cell(15,$alt,'',1,0,"C",0);
$pdf->cell(30,$alt,db_formatar($total_liquido_cc + $total_liquido_sc + $total_consig,'f'),1,1,"R",0);




///////// EDUCAÇÃO FUNDEB

$sql1_fundeb = 
"
select rh01_clas1 as fundeb_folha,  
       func_cef as fundeb_func_cef, 
       cef_provento - cef_desconto as fundeb_cef_liquido, 
       func_bb_cc as fundeb_func_bb_cc, 
       bb_provento_cc - bb_desconto_cc as fundeb_bb_liquido_cc,
       func_sem_conta as fundeb_func_sem_conta,
       provento_sem_conta - desconto_sem_conta as fundeb_liquido_sem_conta,
       tot_pensao as fundeb_tot_pensao ,
       pensao as fundeb_pensao
from
(
select rh01_clas1,  
        count( distinct (
               case 
                 when trim(rh44_codban) = '104' and rh02_fpagto = 3 then
                   r14_regist
                 else
                   null
               end ) ) as func_cef,
        round(sum(case 
                    when r14_pd = 1 and trim(rh44_codban) = '104' and rh02_fpagto = 3 then 
                      r14_valor 
                    else 0 
                  end),2) as cef_provento , 
        round(sum(case 
                    when r14_pd = 2 and trim(rh44_codban) = '104' and rh02_fpagto = 3 then 
                      r14_valor 
                    else 0 
                  end),2) as cef_desconto , 
        count( distinct (
               case 
                 when trim(rh44_codban) = '001' and rh02_fpagto = 3 then
                   r14_regist
                 else
                   null
               end ) ) as func_bb_cc,
        round(sum(case 
                    when r14_pd = 1 and (trim(rh44_codban) = '001' and rh02_fpagto = 3) then 
                      r14_valor 
                    else 0 
                  end),2) as bb_provento_cc , 
        round(sum(case 
                    when r14_pd = 2 and (trim(rh44_codban) = '001' and rh02_fpagto = 3) then 
                      r14_valor 
                    else 0 
                  end),2) as bb_desconto_cc ,
        count( distinct (
               case 
                 when rh02_fpagto <> 3 then
                   r14_regist
                 else
                   null
               end ) ) as func_sem_conta,
        round(sum(case 
                    when r14_pd = 1 and rh02_fpagto <> 3 then 
                      r14_valor 
                    else 0 
                  end),2) as provento_sem_conta , 
        round(sum(case 
                    when r14_pd = 2 and rh02_fpagto <> 3 then 
                      r14_valor 
                    else 0 
                  end),2) as desconto_sem_conta ,

        sum(case 
              when r14_rubric   = '0333' then 
                1 
             else 0 
            end) as tot_pensao,
        round(sum(case 
                    when r14_rubric = '0333' then 
                      r14_valor 
                    else 0 
                  end),2) as pensao
 from (".$sql_basico.") as x1
where rh01_clas1  <> 'E' and
      o40_orgao   = 8    and
      rh56_localtrab <> 9

group by rh01_clas1
order by rh01_clas1
) as x
";
$result1_fundeb = pg_exec($sql1_fundeb);
//db_criatabela($result1_fundeb);


$sql2_fundeb =
"
select rh27_descr, rh27_rubric, round(sum(r14_valor),2) as valor 
from (".$sql_basico.") as x1
where r14_pd = 2          and
      rh01_clas1  <> 'E'  and
      o40_orgao   = 8     and
      r14_pd     != 3     and
      rh56_localtrab <> 9
group by rh27_descr, rh27_rubric
order by rh27_rubric
";

$result2_fundeb = pg_exec($sql2_fundeb);
//db_criatabela($result2_fundeb);


$pdf->addpage();

$alt = 4;

$pdf->cell(0,6,"EDUCAÇÃO PAGO COM RECURSOS DO FUNDEB",0,1,"C",0);

$pdf->ln(4);

$pdf->cell(55,$alt,'',1,0,"C",1);
$pdf->cell(45,$alt,'EM CONTA',1,0,"C",1);
$pdf->cell(45,$alt,'EM CH EQUE',1,0,"C",1);
$pdf->cell(45,$alt,'TODOS',1,1,"C",1);

$pdf->cell(55,$alt,'TIPO DE FOLHA',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,1,"C",1);

$total_v_fundeb_cc = 0;
$total_q_fundeb_cc = 0;
$total_v_fundeb_sc = 0;
$total_q_fundeb_sc = 0;

$total_v_fundeb_pen_sc = 0;
$total_q_fundeb_pen_sc = 0;

for($xx=0;$xx<pg_numrows($result1_fundeb);$xx++ ){
  db_fieldsmemory($result1_fundeb,$xx);
  $pdf->cell(55,$alt,'FOLHA '.$fundeb_folha,1,0,"C",0);
  $pdf->cell(15,$alt,$fundeb_func_bb_cc,1,0,"C",0);
  $pdf->cell(30,$alt,db_formatar($fundeb_bb_liquido_cc,'f'),1,0,"R",0);
  $pdf->cell(15,$alt,$fundeb_func_sem_conta,1,0,"C",0);
  $pdf->cell(30,$alt,db_formatar($fundeb_liquido_sem_conta,'f'),1,0,"R",0);
  $pdf->cell(15,$alt,$fundeb_func_bb_cc + $fundeb_func_sem_conta,1,0,"C",0);
  $pdf->cell(30,$alt,db_formatar( $fundeb_bb_liquido_cc + $fundeb_liquido_sem_conta ,'f'),1,1,"R",0);
  $total_v_fundeb_cc += $fundeb_bb_liquido_cc;
  $total_q_fundeb_cc += $fundeb_func_bb_cc;
  $total_v_fundeb_sc += $fundeb_liquido_sem_conta;
  $total_q_fundeb_sc += $fundeb_func_sem_conta;
}
$pdf->cell(55,$alt,'SUB-TOTAL',1,0,"C",0);
$pdf->cell(15,$alt,$total_q_fundeb_cc,1,0,"C",0);
$pdf->cell(30,$alt,db_formatar($total_v_fundeb_cc,'f'),1,0,"R",0);
$pdf->cell(15,$alt,$total_q_fundeb_sc,1,0,"C",0);
$pdf->cell(30,$alt,db_formatar($total_v_fundeb_sc,'f'),1,0,"R",0);
$pdf->cell(15,$alt,$total_q_fundeb_cc + $total_q_fundeb_sc ,1,0,"C",0);
$pdf->cell(30,$alt,db_formatar( $total_v_fundeb_cc + $total_v_fundeb_sc ,'f'),1,1,"R",0);
$pdf->ln(4);

for($xy=0;$xy<pg_numrows($result1_fundeb);$xy++ ){
  db_fieldsmemory($result1_fundeb,$xy);
  $pdf->cell(55,$alt,'PENSÃO FOLHA '.$fundeb_folha,1,0,"C",0);
  $pdf->cell(15,$alt,'',1,0,"C",0);
  $pdf->cell(30,$alt,'',1,0,"R",0);
  $pdf->cell(15,$alt,$fundeb_tot_pensao,1,0,"C",0);
  $pdf->cell(30,$alt,db_formatar($fundeb_pensao,'f'),1,0,"R",0);
  $pdf->cell(15,$alt,$fundeb_tot_pensao,1,0,"C",0);
  $pdf->cell(30,$alt,db_formatar( $fundeb_pensao ,'f'),1,1,"R",0);
  $total_q_fundeb_pen_sc += $fundeb_tot_pensao;
  $total_v_fundeb_pen_sc += $fundeb_pensao;
}

$pdf->ln(4);

$pdf->cell(55,$alt, 'SUB-TOTAL',1,0,"C",0);
$total_func_cc = 0;
$total_func_cc        = $total_q_fundeb_cc; 
$pdf->cell(15,$alt, $total_func_cc,1,0,"C",0);
$total_liquido_cc = 0;
$total_liquido_cc     = $total_v_fundeb_cc;

$pdf->cell(30,$alt, db_formatar( $total_liquido_cc,'f'),1,0,"R",0);
$total_func_sc = 0;
$total_func_sc        = $total_q_fundeb_sc 
                      + $total_q_fundeb_pen_sc;

$pdf->cell(15,$alt, $total_func_sc ,1,0,"C",0);
$total_liquido_sc = 0;
$total_liquido_sc     = $total_v_fundeb_sc 
                      + $total_v_fundeb_pen_sc; 
$pdf->cell(30,$alt, db_formatar( $total_liquido_sc ,'f'),1,0,"R",0);


$pdf->cell(15,$alt, $total_func_cc + $total_func_sc,1,0,"C",0);
$pdf->cell(30,$alt, db_formatar( $total_liquido_cc + $total_liquido_sc ,'f'),1,1,"R",0);


$pdf->ln(6);

$alt     =  4;

$pdf->cell(0,6,"CONSIGNAÇÕES",0,1,"C",0);

$pdf->cell(80,$alt,'DESCRIÇÃO',1,0,"C",1);
$pdf->cell(15,$alt,'RUBRICA',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,1,"C",1);

$total_consig = 0;
$pdf->setfont('arial','',8);

for($x = 0; $x < pg_numrows($result2_fundeb);$x++){
  db_fieldsmemory($result2_fundeb,$x);
  $pdf->cell(80,$alt,$rh27_descr,1,0,"L",0);
  $pdf->cell(15,$alt,$rh27_rubric,1,0,"C",0);
  $pdf->cell(30,$alt,db_formatar($valor,'f'),1,1,"R",0);
  $total_consig += $valor;
}
$pdf->setfont('arial','b',8);
$pdf->cell(80,$alt,'SUB-TOTAL',1,0,"L",0);
$pdf->cell(15,$alt,'',1,0,"C",0);
$pdf->cell(30,$alt,db_formatar($total_consig,'f'),1,1,"R",0);

$pdf->ln();

$pdf->cell(80,$alt,'VALOR LÍQUIDO DA FOLHA',1,0,"L",0);
$pdf->cell(15,$alt,'',1,0,"C",0);
$pdf->cell(30,$alt,db_formatar( $total_liquido_cc + $total_liquido_sc,'f'),1,1,"R",0);

$pdf->cell(80,$alt,'VALOR DAS CONSIGNAÇÕES',1,0,"L",0);
$pdf->cell(15,$alt,'',1,0,"C",0);
$pdf->cell(30,$alt,db_formatar($total_consig,'f'),1,1,"R",0);

$pdf->cell(80,$alt,'TOTAL LÍQUIDO + CONSIGNAÇÕES',1,0,"L",0);
$pdf->cell(15,$alt,'',1,0,"C",0);
$pdf->cell(30,$alt,db_formatar($total_liquido_cc + $total_liquido_sc + $total_consig,'f'),1,1,"R",0);



////////// IMPRIME CONVENIOS DA SAUDE

$sql1_conv =
"
select substr(rh55_descr,1,30) as conv_localtrab,
       func_cef as conv_func_cef, 
       cef_provento - cef_desconto as conv_cef_liquido, 
       func_bb_cc as conv_func_bb_cc, 
       bb_provento_cc - bb_desconto_cc as conv_bb_liquido_cc,
       func_sem_conta as conv_func_sem_conta,
       provento_sem_conta - desconto_sem_conta as conv_liquido_sem_conta,
       tot_pensao as conv_tot_pensao,
       pensao as conv_pensao
from
(
select rh55_descr ,
        count( distinct (
               case 
                 when trim(rh44_codban) = '104' and rh02_fpagto = 3 then
                   r14_regist
                 else
                   null
               end ) ) as func_cef,
        round(sum(case 
                    when r14_pd = 1 and trim(rh44_codban) = '104' and rh02_fpagto = 3 then 
                      r14_valor 
                    else 0 
                  end),2) as cef_provento , 
        round(sum(case 
                    when r14_pd = 2 and trim(rh44_codban) = '104' and rh02_fpagto = 3 then 
                      r14_valor 
                    else 0 
                  end),2) as cef_desconto , 
        count( distinct (
               case 
                 when trim(rh44_codban) = '001' and rh02_fpagto = 3 then
                   r14_regist
                 else
                   null
               end ) ) as func_bb_cc,
        round(sum(case 
                    when r14_pd = 1 and (trim(rh44_codban) = '001' and rh02_fpagto = 3) then 
                      r14_valor 
                    else 0 
                  end),2) as bb_provento_cc , 
        round(sum(case 
                    when r14_pd = 2 and (trim(rh44_codban) = '001' and rh02_fpagto = 3) then 
                      r14_valor 
                    else 0 
                  end),2) as bb_desconto_cc ,
        count( distinct (
               case 
                 when rh02_fpagto <> 3 then
                   r14_regist
                 else
                   null
               end ) ) as func_sem_conta,
        round(sum(case 
                    when r14_pd = 1 and rh02_fpagto <> 3 then 
                      r14_valor 
                    else 0 
                  end),2) as provento_sem_conta , 
        round(sum(case 
                    when r14_pd = 2 and rh02_fpagto <> 3 then 
                      r14_valor 
                    else 0 
                  end),2) as desconto_sem_conta ,

        sum(case 
              when r14_rubric   = '0333' then 
                1 
             else 0 
            end) as tot_pensao,
        round(sum(case 
                    when r14_rubric = '0333' then 
                      r14_valor 
                    else 0 
                  end),2) as pensao
from (".$sql_basico.") as x1
where rh56_localtrab in (5, 6, 7, 8) 
group by rh55_descr
order by rh55_descr
) as x

";
$result1_conv = pg_exec($sql1_conv);
//db_criatabela($result1_conv);exit;

$pdf->addpage();

$alt = 4;

$pdf->cell(0,6,"SEC. DE SAÚDE - PROGRAMAS FEDERAIS",0,1,"C",0);

$pdf->ln(4);

$pdf->cell(55,$alt,'',1,0,"C",1);
$pdf->cell(45,$alt,'EM CONTA',1,0,"C",1);
$pdf->cell(45,$alt,'EM CH EQUE',1,0,"C",1);
$pdf->cell(45,$alt,'TODOS',1,1,"C",1);

$pdf->cell(55,$alt,'TIPO DE FOLHA',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,1,"C",1);

$total_v_conv_cc = 0;
$total_q_conv_cc = 0;
$total_v_conv_sc = 0;
$total_q_conv_sc = 0;

$total_v_conv_pen_sc = 0;
$total_q_conv_pen_sc = 0;

for($xx=0;$xx<pg_numrows($result1_conv);$xx++ ){
  db_fieldsmemory($result1_conv,$xx);
  if($conv_bb_liquido_cc + $conv_liquido_sem_conta != 0 ){
    $pdf->cell(55,$alt,$conv_localtrab,1,0,"C",0);
    $pdf->cell(15,$alt,$conv_func_bb_cc,1,0,"C",0);
    $pdf->cell(30,$alt,db_formatar($conv_bb_liquido_cc,'f'),1,0,"R",0);
    $pdf->cell(15,$alt,$conv_func_sem_conta,1,0,"C",0);
    $pdf->cell(30,$alt,db_formatar($conv_liquido_sem_conta,'f'),1,0,"R",0);
    $pdf->cell(15,$alt,$conv_func_bb_cc + $conv_func_sem_conta,1,0,"C",0);
    $pdf->cell(30,$alt,db_formatar( $conv_bb_liquido_cc + $conv_liquido_sem_conta ,'f'),1,1,"R",0);
    $total_v_conv_cc += $conv_bb_liquido_cc;
    $total_q_conv_cc += $conv_func_bb_cc;
    $total_v_conv_sc += $conv_liquido_sem_conta;
    $total_q_conv_sc += $conv_func_sem_conta;
  }
}
$pdf->ln(4);
for($xy=0;$xy<pg_numrows($result1_conv);$xy++ ){
  db_fieldsmemory($result1_conv,$xy);
  $pdf->cell(55,$alt,'PENSÃO FOLHA '.substr($conv_localtrab,0,18),1,0,"C",0);
  $pdf->cell(15,$alt,'',1,0,"C",0);
  $pdf->cell(30,$alt,'',1,0,"R",0);
  $pdf->cell(15,$alt,$conv_tot_pensao,1,0,"C",0);
  $pdf->cell(30,$alt,db_formatar($conv_pensao,'f'),1,0,"R",0);
  $pdf->cell(15,$alt,$conv_tot_pensao,1,0,"C",0);
  $pdf->cell(30,$alt,db_formatar( $conv_pensao ,'f'),1,1,"R",0);
  $total_q_conv_pen_sc += $conv_tot_pensao;
  $total_v_conv_pen_sc += $conv_pensao;
}

$pdf->ln(4);

$pdf->cell(55,$alt, 'SUB-TOTAL',1,0,"C",0);
$total_func_cc = 0;
$total_func_cc        = $total_q_conv_cc; 
$pdf->cell(15,$alt, $total_func_cc,1,0,"C",0);
$total_liquido_cc = 0;
$total_liquido_cc     = $total_v_conv_cc;


$pdf->cell(30,$alt, db_formatar( $total_liquido_cc,'f'),1,0,"R",0);
$total_func_sc = 0;
$total_func_sc        = $total_q_conv_sc 
                      + $total_q_conv_pen_sc;

$pdf->cell(15,$alt, $total_func_sc ,1,0,"C",0);
$total_liquido_sc = 0;
$total_liquido_sc     = $total_v_conv_sc 
                      + $total_v_conv_pen_sc; 
$pdf->cell(30,$alt, db_formatar( $total_liquido_sc ,'f'),1,0,"R",0);


$pdf->cell(15,$alt, $total_func_cc + $total_func_sc,1,0,"C",0);
$pdf->cell(30,$alt, db_formatar( $total_liquido_cc + $total_liquido_sc ,'f'),1,1,"R",0);


$pdf->ln(6);

$head6 = "CAIXA ECONÔMICA FEDERAL";

$pdf->addpage();

$alt = 4;

$pdf->cell(0,6,"FUNDO DE PREVIDÊNCIA - APOSENTADOS E PENSIONISTAS",0,1,"C",0);

$pdf->ln(4);

$pdf->cell(55,$alt,'',1,0,"C",1);
$pdf->cell(45,$alt,'EM CONTA',1,0,"C",1);
$pdf->cell(45,$alt,'EM CH EQUE',1,0,"C",1);
$pdf->cell(45,$alt,'TODOS',1,1,"C",1);

$pdf->cell(55,$alt,'TIPO DE FOLHA',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,1,"C",1);

$pdf->cell(55,$alt,'PREFEITURA',1,0,"C",0);
$pdf->cell(15,$alt,$fps_func_cef,1,0,"C",0);
$pdf->cell(30,$alt,db_formatar($fps_cef_liquido,'f'),1,0,"R",0);
$pdf->cell(15,$alt,'',1,0,"C",0);
$pdf->cell(30,$alt,'',1,0,"R",0);
$pdf->cell(15,$alt,$fps_func_cef,1,0,"C",0);
$pdf->cell(30,$alt,db_formatar( $fps_cef_liquido ,'f'),1,1,"R",0);






$pdf->addpage();

$alt = 4;

$pdf->cell(0,6,"EFETIVOS DE OUTRAS SECRETARIAS",0,1,"C",0);

$pdf->ln(4);

$pdf->cell(55,$alt,'',1,0,"C",1);
$pdf->cell(45,$alt,'EM CONTA',1,0,"C",1);
$pdf->cell(45,$alt,'EM CH EQUE',1,0,"C",1);
$pdf->cell(45,$alt,'TODOS',1,1,"C",1);

$pdf->cell(55,$alt,'TIPO DE FOLHA',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,1,"C",1);

$pdf->cell(55,$alt,'EFETIVOS',1,0,"C",0);
$pdf->cell(15,$alt,$efe_outras_func_cef,1,0,"C",0);
$pdf->cell(30,$alt,db_formatar($efe_outras_cef_liquido,'f'),1,0,"R",0);
$pdf->cell(15,$alt,'',1,0,"C",0);
$pdf->cell(30,$alt,'',1,0,"R",0);
$pdf->cell(15,$alt,$efe_outras_func_cef,1,0,"C",0);
$pdf->cell(30,$alt,db_formatar( $efe_outras_cef_liquido ,'f'),1,1,"R",0);

$pdf->ln(4);

$pdf->cell(0,6,"CC'S DE OUTRAS SECRETARIAS E EDUCAÇÃO",0,1,"C",0);

$pdf->ln(4);

$pdf->cell(55,$alt,'',1,0,"C",1);
$pdf->cell(45,$alt,'EM CONTA',1,0,"C",1);
$pdf->cell(45,$alt,'EM CH EQUE',1,0,"C",1);
$pdf->cell(45,$alt,'TODOS',1,1,"C",1);

$pdf->cell(55,$alt,'TIPO DE FOLHA',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,1,"C",1);

$pdf->cell(55,$alt, 'CCS OUTRAS',1,0,"C",0);
$pdf->cell(15,$alt, $cc_outras_func_cef,1,0,"C",0);
$pdf->cell(30,$alt, db_formatar($cc_outras_cef_liquido ,'f'),1,0,"R",0);
$pdf->cell(15,$alt, '',1,0,"C",0);
$pdf->cell(30,$alt, '',1,0,"R",0);
$pdf->cell(15,$alt, $cc_outras_func_cef,1,0,"C",0);
$pdf->cell(30,$alt, db_formatar( $cc_outras_cef_liquido ,'f'),1,1,"R",0);

$pdf->cell(55,$alt, 'CCS EDUCAÇÃO',1,0,"C",0);
$pdf->cell(15,$alt, $cc_edu_outras_func_cef,1,0,"C",0);
$pdf->cell(30,$alt, db_formatar($cc_edu_outras_cef_liquido ,'f'),1,0,"R",0);
$pdf->cell(15,$alt, '',1,0,"C",0);
$pdf->cell(30,$alt, '',1,0,"R",0);
$pdf->cell(15,$alt, $cc_edu_outras_func_cef ,1,0,"C",0);
$pdf->cell(30,$alt, db_formatar( $cc_edu_outras_cef_liquido ,'f'),1,1,"R",0);

$pdf->ln(4);


$pdf->cell(55,$alt, 'TOTAL',1,0,"C",0);
$total_func_cc = 0;
$total_func_cc        = $efe_outras_func_cef 
                      + $cc_outras_func_cef
                      + $cc_edu_outras_func_cef;
$pdf->cell(15,$alt, $total_func_cc,1,0,"C",0);
$total_liquido_cc = 0;
$total_liquido_cc     = $efe_outras_cef_liquido
                      + $cc_outras_cef_liquido
                      + $cc_edu_outras_cef_liquido;

$pdf->cell(30,$alt, db_formatar( $total_liquido_cc,'f'),1,0,"R",0);
$pdf->cell(15,$alt, '' ,1,0,"C",0);
$pdf->cell(30,$alt, '',1,0,"R",0);


$pdf->cell(15,$alt, $total_func_cc,1,0,"C",0);
$pdf->cell(30,$alt, db_formatar( $total_liquido_cc ,'f'),1,1,"R",0);


//db_criatabela($result1_edu_pma);exit;


$pdf->addpage();

$alt = 4;

$pdf->cell(0,6,"EDUCAÇÃO PAGO COM RECURSOS DA  P.M.A.",0,1,"C",0);

$pdf->ln(4);

$pdf->cell(55,$alt,'',1,0,"C",1);
$pdf->cell(45,$alt,'EM CONTA',1,0,"C",1);
$pdf->cell(45,$alt,'EM CH EQUE',1,0,"C",1);
$pdf->cell(45,$alt,'TODOS',1,1,"C",1);

$pdf->cell(55,$alt,'TIPO DE FOLHA',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,1,"C",1);

$pdf->cell(55,$alt,'FOLHA - E',1,0,"C",0);
$pdf->cell(15,$alt,$edu_pma_func_cef,1,0,"C",0);
$pdf->cell(30,$alt,db_formatar($edu_pma_cef_liquido,'f'),1,0,"R",0);
$pdf->cell(15,$alt,'' ,1,0,"C",0);
$pdf->cell(30,$alt,'' ,1,0,"R",0);
$pdf->cell(15,$alt,$edu_pma_func_cef ,1,0,"C",0);
$pdf->cell(30,$alt,db_formatar( $edu_pma_cef_liquido ,'f'),1,1,"R",0);




$pdf->addpage();

$alt = 4;

$pdf->cell(0,6,"PAGOS COM RECURSOS DA SEC. DE SAÚDE",0,1,"C",0);

$pdf->ln(4);

$pdf->cell(55,$alt,'',1,0,"C",1);
$pdf->cell(45,$alt,'EM CONTA',1,0,"C",1);
$pdf->cell(45,$alt,'EM CH EQUE',1,0,"C",1);
$pdf->cell(45,$alt,'TODOS',1,1,"C",1);

$pdf->cell(55,$alt,'TIPO DE FOLHA',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,1,"C",1);

$pdf->cell(55,$alt,'EFETIVOS',1,0,"C",0);
$pdf->cell(15,$alt,$saude_func_cef,1,0,"C",0);
$pdf->cell(30,$alt,db_formatar($saude_cef_liquido,'f'),1,0,"R",0);
$pdf->cell(15,$alt,'' ,1,0,"C",0);
$pdf->cell(30,$alt,'' ,1,0,"R",0);
$pdf->cell(15,$alt,$saude_func_cef,1,0,"C",0);
$pdf->cell(30,$alt,db_formatar( $saude_cef_liquido ,'f'),1,1,"R",0);

$pdf->ln(4);

$pdf->cell(0,6,"CC'S DA SAÚDE",0,1,"C",0);

$pdf->ln(4);

$pdf->cell(55,$alt,'',1,0,"C",1);
$pdf->cell(45,$alt,'EM CONTA',1,0,"C",1);
$pdf->cell(45,$alt,'EM CH EQUE',1,0,"C",1);
$pdf->cell(45,$alt,'TODOS',1,1,"C",1);

$pdf->cell(55,$alt,'TIPO DE FOLHA',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,1,"C",1);

$pdf->cell(55,$alt, 'CCS',1,0,"C",0);
$pdf->cell(15,$alt, $saude_cc_func_cef, 1,0,"C",0);
$pdf->cell(30,$alt, db_formatar($saude_cc_cef_liquido ,'f'),1,0,"R",0);
$pdf->cell(15,$alt, '' ,1,0,"C",0);
$pdf->cell(30,$alt, '' ,1,0,"R",0);
$pdf->cell(15,$alt, $saude_cc_func_cef,1,0,"C",0);
$pdf->cell(30,$alt, db_formatar( $saude_cc_cef_liquido ,'f'),1,1,"R",0);

$pdf->ln(4);

$pdf->cell(55,$alt, 'TOTAL',1,0,"C",0);
$total_func_cc = 0;
$total_func_cc        = $saude_func_cef 
                      + $saude_cc_func_cef;
$pdf->cell(15,$alt, $total_func_cc,1,0,"C",0);
$total_liquido_cc = 0;
$total_liquido_cc     = $saude_cef_liquido
                      + $saude_cc_cef_liquido;

$pdf->cell(30,$alt, db_formatar( $total_liquido_cc,'f'),1,0,"R",0);
$pdf->cell(15,$alt, '' ,1,0,"C",0);
$pdf->cell(30,$alt, '' ,1,0,"R",0);


$pdf->cell(15,$alt, $total_func_cc ,1,0,"C",0);
$pdf->cell(30,$alt, db_formatar( $total_liquido_cc ,'f'),1,1,"R",0);



$pdf->addpage();

$alt = 4;

$pdf->cell(0,6,"EDUCAÇÃO PAGO COM RECURSOS DO FUNDEB",0,1,"C",0);

$pdf->ln(4);

$pdf->cell(55,$alt,'',1,0,"C",1);
$pdf->cell(45,$alt,'EM CONTA',1,0,"C",1);
$pdf->cell(45,$alt,'EM CH EQUE',1,0,"C",1);
$pdf->cell(45,$alt,'TODOS',1,1,"C",1);

$pdf->cell(55,$alt,'TIPO DE FOLHA',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,1,"C",1);

$total_v_fundeb_cc = 0;
$total_q_fundeb_cc = 0;
$total_v_fundeb_sc = 0;
$total_q_fundeb_sc = 0;

$total_v_fundeb_pen_sc = 0;
$total_q_fundeb_pen_sc = 0;

for($xx=0;$xx<pg_numrows($result1_fundeb);$xx++ ){
  db_fieldsmemory($result1_fundeb,$xx);
  $pdf->cell(55,$alt,'FOLHA '.$fundeb_folha,1,0,"C",0);
  $pdf->cell(15,$alt,$fundeb_func_cef,1,0,"C",0);
  $pdf->cell(30,$alt,db_formatar($fundeb_cef_liquido,'f'),1,0,"R",0);
  $pdf->cell(15,$alt, '',1,0,"C",0);
  $pdf->cell(30,$alt, '',1,0,"R",0);
  $pdf->cell(15,$alt,$fundeb_func_cef,1,0,"C",0);
  $pdf->cell(30,$alt,db_formatar( $fundeb_cef_liquido ,'f'),1,1,"R",0);
  $total_v_fundeb_cc += $fundeb_cef_liquido;
  $total_q_fundeb_cc += $fundeb_func_cef;
}
$pdf->cell(55,$alt,'SUB-TOTAL',1,0,"C",0);
$pdf->cell(15,$alt,$total_q_fundeb_cc,1,0,"C",0);
$pdf->cell(30,$alt,db_formatar($total_v_fundeb_cc,'f'),1,0,"R",0);
$pdf->cell(15,$alt, '',1,0,"C",0);
$pdf->cell(30,$alt, '',1,0,"R",0);
$pdf->cell(15,$alt,$total_q_fundeb_cc ,1,0,"C",0);
$pdf->cell(30,$alt,db_formatar( $total_v_fundeb_cc ,'f'),1,1,"R",0);
$pdf->ln(4);



$pdf->addpage();

$alt = 4;

$pdf->cell(0,6,"SEC. DE SAÚDE - PROGRAMAS FEDERAIS",0,1,"C",0);

$pdf->ln(4);

$pdf->cell(55,$alt,'',1,0,"C",1);
$pdf->cell(45,$alt,'EM CONTA',1,0,"C",1);
$pdf->cell(45,$alt,'EM CH EQUE',1,0,"C",1);
$pdf->cell(45,$alt,'TODOS',1,1,"C",1);

$pdf->cell(55,$alt,'TIPO DE FOLHA',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,0,"C",1);
$pdf->cell(15,$alt,'QTD',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,1,"C",1);

$total_v_conv_cc = 0;
$total_q_conv_cc = 0;
$total_v_conv_sc = 0;
$total_q_conv_sc = 0;

$total_v_conv_pen_sc = 0;
$total_q_conv_pen_sc = 0;

for($xx=0;$xx<pg_numrows($result1_conv);$xx++ ){
  db_fieldsmemory($result1_conv,$xx);
  if($conv_cef_liquido != 0 ){
    $pdf->cell(55,$alt,$conv_localtrab,1,0,"C",0);
    $pdf->cell(15,$alt,$conv_func_cef,1,0,"C",0);
    $pdf->cell(30,$alt,db_formatar($conv_cef_liquido,'f'),1,0,"R",0);
    $pdf->cell(15,$alt, '',1,0,"C",0);
    $pdf->cell(30,$alt, '',1,0,"R",0);
    $pdf->cell(15,$alt,$conv_func_cef ,1,0,"C",0);
    $pdf->cell(30,$alt,db_formatar( $conv_cef_liquido ,'f'),1,1,"R",0);
    $total_v_conv_cc += $conv_cef_liquido;
    $total_q_conv_cc += $conv_func_cef;
  }
}
$pdf->cell(55,$alt,'SUB-TOTAL',1,0,"C",0);
$pdf->cell(15,$alt,$total_q_conv_cc,1,0,"C",0);
$pdf->cell(30,$alt,db_formatar($total_v_conv_cc,'f'),1,0,"R",0);
$pdf->cell(15,$alt, '',1,0,"C",0);
$pdf->cell(30,$alt, '',1,0,"R",0);
$pdf->cell(15,$alt,$total_q_conv_cc ,1,0,"C",0);
$pdf->cell(30,$alt,db_formatar( $total_v_conv_cc ,'f'),1,1,"R",0);

$pdf->Output();
?>
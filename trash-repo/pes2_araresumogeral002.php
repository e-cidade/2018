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

//$ano = 2006;
//$mes = 4;

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


$head2 = "RESUMO GERAL";
$head4 = "PERIODO : $mes / $ano";


$sql = 
"
select rh55_codigo, 
       rh55_descr, 
       count(distinct r14_regist) as soma,
       round(sum(provento),2) as provento,
       round(sum(desconto),2) as desconto,
       round(sum(liquido),2) as liquido
from
(
select ".$sigla."_regist as r14_regist,
       case when rh55_codigo = 3 and rh01_clas1 = 'E' then 10 else rh55_codigo end as rh55_codigo,
       case when rh55_codigo = 3 and rh01_clas1 = 'E' then 'FOLHA E' else rh55_descr end as rh55_descr,
       count(distinct ".$sigla."_regist) as soma,
       round(sum(case when ".$sigla."_pd = 1 then ".$sigla."_valor else 0 end),2) as provento,
       round(sum(case when ".$sigla."_pd = 2 then ".$sigla."_valor else 0 end),2) as desconto,
       round(sum(case when ".$sigla."_pd = 1 then ".$sigla."_valor else ".$sigla."_valor *(-1) end),2) as liquido
from ".$arquivo."
     inner join rhpessoal      on rh01_regist = ".$sigla."_regist
     inner join cgm            on z01_numcgm  = rh01_numcgm
     inner join rhpessoalmov   on rh02_regist = rh01_regist
                              and rh02_anousu = ".$sigla."_anousu
                              and rh02_mesusu = ".$sigla."_mesusu
                              and rh02_instit = ".$sigla."_instit
     inner join rhlota         on rh02_lota   = r70_codigo
                              and rh02_instit = r70_instit
     left join rhpesrescisao   on rh05_seqpes = rh02_seqpes
     left  join rhpeslocaltrab on rh02_seqpes = rh56_seqpes
                              and rh56_princ  = true
     left join rhlocaltrab     on rh56_localtrab = rh55_codigo
                              and rh55_instit = rh02_instit
     inner join rhregime       on rh30_codreg = rh02_codreg
                              and rh30_instit = rh02_instit

where ".$sigla."_anousu = $ano
  and ".$sigla."_mesusu = $mes
  and ".$sigla."_pd    != 3 
  and rh05_seqpes is null
group by ".$sigla."_regist, rh01_clas1, rh55_codigo, rh55_descr
) as xx
group by
         rh55_codigo,
         rh55_descr
order by rh55_codigo

";
  //and rh55_codigo = 3
//echo $sql;exit; 
$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if($xxnum == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não nenhum registro encontrado no período de '.$mes.' / '.$ano);
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfont('arial','b',8);
$total      = 0;
$troca      = 1;
$totalo     = 0;
$toto_prov  = 0;
$toto_desc  = 0;
$toto_liq   = 0;
$totalc     = 0;
$totc_prov  = 0;
$totc_desc  = 0;
$totc_liq   = 0;
$totalg     = 0;
$totg_prov  = 0;
$totg_desc  = 0;
$totg_liq   = 0;
$tot_valor  = 0;
$alt        = 4;
$xsec       = 0;
$xtot       = '';
$pdf->setfillcolor(235);
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(70,$alt,'DESCRICAO',1,0,"C",1);
      $pdf->cell(30,$alt,'QTD',1,0,"C",1);
      $pdf->cell(30,$alt,'BRUTO',1,0,"C",1);
      $pdf->cell(30,$alt,'DESCONTO',1,0,"C",1);
      $pdf->cell(30,$alt,'LIQUIDO',1,1,"C",1);
      $troca = 0;
      $pre = 1;
   }
   if($pre == 1){
     $pre = 0;
   }else{
     $pre = 1;
   }  
//   if($xtot != $t52_descr && $x > 0){
//     $xtot = $t52_descr;
//     $pdf->setfont('arial','b',8);
//     $pdf->cell(175,$alt,'TOTAL DE REGISTROS '.$total,"T",1,"L",0);
//     $total = 0;
     
//   }
   $pdf->setfont('arial','',7);
   $pdf->cell(70,$alt,$rh55_descr,0,0,"L",$pre);
   $pdf->cell(30,$alt,$soma,0,0,"R",$pre);
   $pdf->cell(30,$alt,db_formatar($provento,'f'),0,0,"R",$pre);
   $pdf->cell(30,$alt,db_formatar($desconto,'f'),0,0,"R",$pre);
   $pdf->cell(30,$alt,db_formatar($liquido,'f'),0,1,"R",$pre);
   if($rh55_codigo == 1 ||
      $rh55_codigo == 2 ||
      $rh55_codigo == 3 ||
      $rh55_codigo == 4 ||
      $rh55_codigo == 9 ||
      $rh55_codigo == 10
     ){
     $totalo    += $soma;
     $toto_prov += $provento;
     $toto_desc += $desconto;
     $toto_liq  += $liquido;
   }elseif($rh55_codigo == 5 ||
           $rh55_codigo == 6 ||
           $rh55_codigo == 7 ||
           $rh55_codigo == 8 
     ){
     $totalc    += $soma;
     $totc_prov += $provento;
     $totc_desc += $desconto;
     $totc_liq  += $liquido;
   }
   $totalg    += $soma;
   $totg_prov += $provento;
   $totg_desc += $desconto;
   $totg_liq  += $liquido;
}
if($pre == 1){
  $pre = 0;
}else{
  $pre = 1;
}  
$pdf->setfont('arial','b',8);
$pdf->cell(70,$alt,'TOTAIS OUTRAS',"T",0,"L",$pre);
$pdf->cell(30,$alt,$totalo,"T",0,"R",$pre);
$pdf->cell(30,$alt,db_formatar($toto_prov,'f'),"T",0,"R",$pre);
$pdf->cell(30,$alt,db_formatar($toto_desc,'f'),"T",0,"R",$pre);
$pdf->cell(30,$alt,db_formatar($toto_liq,'f'),"T",1,"R",$pre);
$pdf->cell(70,$alt,'TOTAIS CONVENIOS ',"T",0,"L",$pre);
$pdf->cell(30,$alt,$totalc,"T",0,"R",$pre);
$pdf->cell(30,$alt,db_formatar($totc_prov,'f'),"T",0,"R",$pre);
$pdf->cell(30,$alt,db_formatar($totc_desc,'f'),"T",0,"R",$pre);
$pdf->cell(30,$alt,db_formatar($totc_liq,'f'),"T",1,"R",$pre);
$pdf->cell(70,$alt,'TOTAIS GERAL ',"T",0,"L",$pre);
$pdf->cell(30,$alt,$totalg,"T",0,"R",$pre);
$pdf->cell(30,$alt,db_formatar($totg_prov,'f'),"T",0,"R",$pre);
$pdf->cell(30,$alt,db_formatar($totg_desc,'f'),"T",0,"R",$pre);
$pdf->cell(30,$alt,db_formatar($totg_liq,'f'),"T",1,"R",$pre);
$pdf->Output();
?>
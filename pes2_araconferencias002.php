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


$head3 = "CADASTRO DE CONFERENCIA";
$head5 = "PERÍODO : ".$ano." / ".$mes;

//// CC fora do local de trabalho

$sql1 = 
 "
select rh01_regist, z01_nome, rh55_codigo, rh55_descr
from rhpessoal
     inner join cgm            on z01_numcgm  = rh01_numcgm
     inner join rhpessoalmov   on rh02_regist = rh01_regist
     inner join rhlota         on rh02_lota   = r70_codigo
                              and rh02_instit = r70_instit
     left join rhpesrescisao   on rh05_seqpes = rh02_seqpes
     left  join rhpeslocaltrab on rh02_seqpes = rh56_seqpes
                              and rh56_princ  = true
     left join rhlocaltrab     on rh56_localtrab = rh55_codigo
                              and rh55_instit = rh02_instit
     inner join rhregime       on rh30_codreg = rh02_codreg
                              and rh30_instit = rh02_instit

where rh02_anousu = $ano
  and rh02_mesusu = $mes
  and rh05_seqpes is null
  and rh30_regime = 3
  and rh55_codigo <> 9
 ";
//echo $sql ; exit;


///// auxilio doenca ou maternidade e salario
$sql2 = 
"
select rh01_regist, z01_nome
from ".$arquivo."
     inner join rhpessoal on rh01_regist = ".$sigla."_regist
     inner join cgm       on rh01_numcgm = z01_numcgm
where ".$sigla."_anousu = $ano
  and ".$sigla."_mesusu = $mes
  and ".$sigla."_rubric in ('0020', '0021')
  and ".$sigla."_regist in
                 (select ".$sigla."_regist
                  from ".$arquivo."
                  where ".$sigla."_anousu = $ano
                    and ".$sigla."_mesusu = $mes
                    and (   ".$sigla."_rubric between '0087' and '0101'
                         or ".$sigla."_rubric in ('0103', '0104', '0105', '0107')
                        )
                 )
order by z01_nome

";

//// educacao sem letra
$sql3 = 
"
select rh01_regist, z01_nome, rh01_clas1
from rhpessoal
     inner join cgm            on z01_numcgm  = rh01_numcgm
     inner join rhpessoalmov   on rh02_regist = rh01_regist
     inner join rhlota         on rh02_lota   = r70_codigo
                              and rh02_instit = r70_instit
     left join rhpesrescisao   on rh05_seqpes = rh02_seqpes
     left  join rhpeslocaltrab on rh02_seqpes = rh56_seqpes
                              and rh56_princ  = true
     left join rhlocaltrab     on rh56_localtrab = rh55_codigo
                              and rh55_instit = rh02_instit

where rh02_anousu = $ano
  and rh02_mesusu = $mes
  and rh05_seqpes is null
  and substr(r70_estrut,1,2) = '08'
  and trim(rh01_clas1) not in ('A', 'B', 'C', 'D', 'E', 'F', 'G')
  and rh55_codigo <> 9
";

////Inativo/Pensionista com salario
$sql4 = 
"
select rh01_regist, z01_nome
from rhpessoal
     inner join cgm          on rh01_numcgm = z01_numcgm
     inner join rhpessoalmov on rh01_regist = rh02_regist
     left join rhpesrescisao on rh05_seqpes = rh02_seqpes
     inner join rhregime       on rh30_codreg = rh02_codreg
                              and rh30_instit = rh02_instit
     inner join (select ".$sigla."_regist
                 from ".$arquivo."
                 where ".$sigla."_anousu = $ano
                   and ".$sigla."_mesusu = $mes
                   and (    ".$sigla."_rubric between '0087' and '0094'
                         or ".$sigla."_rubric in ('0096', '0097', '0098', '0099', '100', '101', '0103', '0104', '0020', '0021'))
               ) as x on ".$sigla."_regist = rh01_regist

where rh02_anousu = $ano
  and rh02_mesusu = $mes
  and rh05_seqpes is null
  and rh30_vinculo <> 'A'
";


////// sem local de trabalho
$sql5 = 
"
select distinct rh01_regist, z01_nome
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
  and rh05_seqpes is null
  and rh55_descr is null

";

//// Funcionario sem salario
$sql6 = 
"
select rh01_regist, z01_nome
from rhpessoal
     inner join cgm          on rh01_numcgm = z01_numcgm
     inner join rhpessoalmov on rh01_regist = rh02_regist
     left join rhpesrescisao on rh05_seqpes = rh02_seqpes
     left join afasta        on r45_regist  = rh01_regist
                            and r45_anousu  = rh02_anousu
                            and r45_mesusu  = rh02_mesusu
                            and (r45_dtreto > '$ano-$mes'||ndias($ano,$mes) or r45_dtreto is null)
     left join (select ".$sigla."_regist
                 from ".$arquivo."
                 where ".$sigla."_anousu = $ano
                   and _mesusu = $mes
                   and (    ".$sigla."_rubric between '0087' and '0101'
                         or ".$sigla."_rubric in ('0103', '0104', '0105', '0107', '0020', '0021'))
               ) as x on ".$sigla."_regist = rh01_regist

where rh02_anousu = $ano
  and rh02_mesusu = $mes
  and rh05_seqpes is null
  and r45_regist is null
  and ".$sigla."_regist is null

";
//echo $sql6;
//// inativos/pensionistas fora do local de trabalho
$sql7 = 
"
select rh01_regist, z01_nome, rh55_codigo, rh55_descr
from rhpessoal
     inner join cgm            on z01_numcgm  = rh01_numcgm
     inner join rhpessoalmov   on rh02_regist = rh01_regist
     inner join rhlota         on rh02_lota   = r70_codigo
                              and rh02_instit = r70_instit
     left join rhpesrescisao   on rh05_seqpes = rh02_seqpes
     left  join rhpeslocaltrab on rh02_seqpes = rh56_seqpes
                              and rh56_princ  = true
     left join rhlocaltrab     on rh56_localtrab = rh55_codigo
                              and rh55_instit = rh02_instit
     inner join rhregime       on rh30_codreg = rh02_codreg
                              and rh30_instit = rh02_instit

where rh02_anousu = $ano
  and rh02_mesusu = $mes
  and rh05_seqpes is null
  and rh30_vinculo <> 'A'
  and rh55_codigo <> 1

";

//// Local = fundo e lotacao diferente de fundo
$sql8 = 
"
select rh01_regist, z01_nome, rh55_codigo, rh55_descr
from rhpessoal
     inner join cgm            on z01_numcgm  = rh01_numcgm
     inner join rhpessoalmov   on rh02_regist = rh01_regist
     inner join rhlota         on rh02_lota   = r70_codigo
                              and rh02_instit = r70_instit
     left join rhpesrescisao   on rh05_seqpes = rh02_seqpes
     left  join rhpeslocaltrab on rh02_seqpes = rh56_seqpes
                              and rh56_princ  = true
     left join rhlocaltrab     on rh56_localtrab = rh55_codigo
                              and rh55_instit = rh02_instit
     inner join rhregime       on rh30_codreg = rh02_codreg
                              and rh30_instit = rh02_instit

where rh02_anousu = $ano
  and rh02_mesusu = $mes
  and rh05_seqpes is null
  and rh55_codigo = 1
  and substr(r70_estrut,1,4) <> '0507'

";

//// local de trabalho = fundo e lotacao <> fundo
$sql9 = 
"
select rh01_regist, z01_nome, rh55_codigo, rh55_descr
from rhpessoal
     inner join cgm            on z01_numcgm  = rh01_numcgm
     inner join rhpessoalmov   on rh02_regist = rh01_regist
     inner join rhlota         on rh02_lota   = r70_codigo
                              and rh02_instit = r70_instit
     left join rhpesrescisao   on rh05_seqpes = rh02_seqpes
     left  join rhpeslocaltrab on rh02_seqpes = rh56_seqpes
                              and rh56_princ  = true
     left join rhlocaltrab     on rh56_localtrab = rh55_codigo
                              and rh55_instit = rh02_instit
     inner join rhregime       on rh30_codreg = rh02_codreg
                              and rh30_instit = rh02_instit

where rh02_anousu = $ano
  and rh02_mesusu = $mes
  and rh05_seqpes is null
  and rh55_codigo <> 1
  and substr(r70_estrut,1,4) = '0507'

";

//echo $sql7;exit;

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$alt = 4;
$pdf->addpage();


$result1 = pg_query($sql1);
$xxnum = pg_numrows($result1);
$pdf->cell(0,$alt,'CC FORA DO LOCAL DE TRABALHO',1,1,"C",1);
$pdf->ln(3);
$total = 0;
$troca = 1;
if($xxnum > 0){
  for($x = 0; $x < $xxnum;$x++){
     db_fieldsmemory($result1,$x);
     if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
        $pdf->setfont('arial','b',8);
        $pdf->cell(20,$alt,'MATRICULA',1,0,"C",1);
        $pdf->cell(60,$alt,'NOME',1,0,"C",1);
        $pdf->cell(90,$alt,'LOCAL',1,1,"C",1);
        $troca = 0;
        $pre = 1;
     }
     if($pre == 1){
       $pre = 0;
     }else{
       $pre = 1;
     }
     $pdf->setfont('arial','',7);
     $pdf->cell(20,$alt,$rh01_regist,0,0,"C",$pre);
     $pdf->cell(60,$alt,$z01_nome,0,0,"L",$pre);
     $pdf->cell(90,$alt,$rh55_codigo.' - '.$rh55_descr,0,1,"L",$pre);
     $total += 1;
  }
  $pdf->setfont('arial','b',8);
  $pdf->cell(0,$alt,'TOTAL DE REGISTROS :  '.$total,"T",0,"C",0);
}else{
$pdf->cell(0,$alt,'Nao tem CC fora local de trabalho. ',"T",0,"C",0);
}
$pdf->ln(6);



$result2 = pg_query($sql2);
$xxnum = pg_numrows($result2);
$pdf->cell(0,$alt,'AUXILIO DOENCA/MATERNIDADE COM SALARIO',1,1,"C",1);
$pdf->ln(3);
$total = 0;
$troca = 1;
if($xxnum > 0){
  for($x = 0; $x < $xxnum;$x++){
     db_fieldsmemory($result2,$x);
     if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
        $pdf->setfont('arial','b',8);
        $pdf->cell(20,$alt,'MATRICULA',1,0,"C",1);
        $pdf->cell(60,$alt,'NOME',1,1,"C",1);
        $troca = 0;
        $pre = 1;
     }
     if($pre == 1){
       $pre = 0;
     }else{
       $pre = 1;
     }
     $pdf->setfont('arial','',7);
     $pdf->cell(20,$alt,$rh01_regist,0,0,"C",$pre);
     $pdf->cell(60,$alt,$z01_nome,0,1,"L",$pre);
     $total += 1;
  }
  $pdf->setfont('arial','b',8);
  $pdf->cell(0,$alt,'TOTAL DE REGISTROS :  '.$total,"T",0,"C",0);
}else{
$pdf->cell(0,$alt,'Nao tem Aux. Doenca/Maternidade com Salario.',"T",0,"C",0);
}
$pdf->ln(6);



$result3 = pg_query($sql3);
$xxnum = pg_numrows($result3);
$pdf->cell(0,$alt,'EDUCACAO SEM LETRA CADASTRADA',1,1,"C",1);
$pdf->ln(3);
$total = 0;
$troca = 1;
if($xxnum > 0){
  for($x = 0; $x < $xxnum;$x++){
     db_fieldsmemory($result3,$x);
     if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
        $pdf->setfont('arial','b',8);
        $pdf->cell(20,$alt,'MATRICULA',1,0,"C",1);
        $pdf->cell(60,$alt,'NOME',1,1,"C",1);
        $troca = 0;
        $pre = 1;
     }
     if($pre == 1){
       $pre = 0;
     }else{
       $pre = 1;
     }
     $pdf->setfont('arial','',7);
     $pdf->cell(20,$alt,$rh01_regist,0,0,"C",$pre);
     $pdf->cell(60,$alt,$z01_nome,0,1,"L",$pre);
     $total += 1;
  }
  $pdf->setfont('arial','b',8);
  $pdf->cell(0,$alt,'TOTAL DE REGISTROS :  '.$total,"T",0,"C",0);
}else{
$pdf->cell(0,$alt,'Nao tem Educacao sem Letra.',"T",0,"C",0);
}
$pdf->ln(6);


$result4 = pg_query($sql4);
$xxnum = pg_numrows($result4);
$pdf->cell(0,$alt,'INATIVO/PENSIONISTA COM SALARIO',1,1,"C",1);
$pdf->ln(3);
$total = 0;
$troca = 1;
if($xxnum > 0){
  for($x = 0; $x < $xxnum;$x++){
     db_fieldsmemory($result4,$x);
     if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
        $pdf->setfont('arial','b',8);
        $pdf->cell(20,$alt,'MATRICULA',1,0,"C",1);
        $pdf->cell(60,$alt,'NOME',1,1,"C",1);
        $troca = 0;
        $pre = 1;
     }
     if($pre == 1){
       $pre = 0;
     }else{
       $pre = 1;
     }
     $pdf->setfont('arial','',7);
     $pdf->cell(20,$alt,$rh01_regist,0,0,"C",$pre);
     $pdf->cell(60,$alt,$z01_nome,0,1,"L",$pre);
     $total += 1;
  }
  $pdf->setfont('arial','b',8);
  $pdf->cell(0,$alt,'TOTAL DE REGISTROS :  '.$total,"T",0,"C",0);
}else{
$pdf->cell(0,$alt,'Nao tem Inativo/Pensionista com Salario.',"T",0,"C",0);
}
$pdf->ln(6);


$result7 = pg_query($sql7);
$xxnum = pg_numrows($result7);
$pdf->cell(0,$alt,'INATIVO/PENSIONISTAS FORA DO LOCAL DE TRABALHO',1,1,"C",1);
$pdf->ln(3);
$total = 0;
$troca = 1;
if($xxnum > 0){
  for($x = 0; $x < $xxnum;$x++){
     db_fieldsmemory($result7,$x);
     if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
        $pdf->setfont('arial','b',8);
        $pdf->cell(20,$alt,'MATRICULA',1,0,"C",1);
        $pdf->cell(60,$alt,'NOME',1,0,"C",1);
        $pdf->cell(90,$alt,'LOCAL',1,1,"C",1);
        $troca = 0;
        $pre = 1;
     }
     if($pre == 1){
       $pre = 0;
     }else{
       $pre = 1;
     }
     $pdf->setfont('arial','',7);
     $pdf->cell(20,$alt,$rh01_regist,0,0,"C",$pre);
     $pdf->cell(60,$alt,$z01_nome,0,0,"L",$pre);
     $pdf->cell(90,$alt,$rh55_codigo.' - '.$rh55_descr,0,1,"L",$pre);
     $total += 1;
  }
  $pdf->setfont('arial','b',8);
  $pdf->cell(0,$alt,'TOTAL DE REGISTROS :  '.$total,"T",0,"C",0);
}else{
$pdf->cell(0,$alt,'Nao tem Inativo/Pensionista fora local de trabalho. ',"T",0,"C",0);
}
$pdf->ln(6);



$result5 = pg_query($sql5);
$xxnum = pg_numrows($result5);
$pdf->cell(0,$alt,'FUNCIONARIO SEM LOCAL DE TRABALHO',1,1,"C",1);
$pdf->ln(3);
$total = 0;
$troca = 1;
if($xxnum > 0){
  for($x = 0; $x < $xxnum;$x++){
     db_fieldsmemory($result5,$x);
     if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
        $pdf->setfont('arial','b',8);
        $pdf->cell(20,$alt,'MATRICULA',1,0,"C",1);
        $pdf->cell(60,$alt,'NOME',1,1,"C",1);
        $troca = 0;
        $pre = 1;
     }
     if($pre == 1){
       $pre = 0;
     }else{
       $pre = 1;
     }
     $pdf->setfont('arial','',7);
     $pdf->cell(20,$alt,$rh01_regist,0,0,"C",$pre);
     $pdf->cell(60,$alt,$z01_nome,0,1,"L",$pre);
     $total += 1;
  }
  $pdf->setfont('arial','b',8);
  $pdf->cell(0,$alt,'TOTAL DE REGISTROS :  '.$total,"T",0,"C",0);
}else{
$pdf->cell(0,$alt,'Funcionarios sem local de trabalho.',"T",0,"C",0);
}
$pdf->ln(6);


$result6 = pg_query($sql6);
$xxnum = pg_numrows($result6);
$pdf->cell(0,$alt,'FUNCIONARIO SEM SALARIO',1,1,"C",1);
$pdf->ln(3);
$total = 0;
$troca = 1;
if($xxnum > 0){
  for($x = 0; $x < $xxnum;$x++){
     db_fieldsmemory($result6,$x);
     if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
        $pdf->setfont('arial','b',8);
        $pdf->cell(20,$alt,'MATRICULA',1,0,"C",1);
        $pdf->cell(60,$alt,'NOME',1,1,"C",1);
        $troca = 0;
        $pre = 1;
     }
     if($pre == 1){
       $pre = 0;
     }else{
       $pre = 1;
     }
     $pdf->setfont('arial','',7);
     $pdf->cell(20,$alt,$rh01_regist,0,0,"C",$pre);
     $pdf->cell(60,$alt,$z01_nome,0,1,"L",$pre);
     $total += 1;
  }
  $pdf->setfont('arial','b',8);
  $pdf->cell(0,$alt,'TOTAL DE REGISTROS :  '.$total,"T",0,"C",0);
}else{
$pdf->cell(0,$alt,'Funcionarios sem salario.',"T",0,"C",0);
}
$pdf->ln(6);


$result8 = pg_query($sql8);
$xxnum = pg_numrows($result8);
$pdf->cell(0,$alt,'LOCAL = FUNDO E LOTACAO <> FUNDO',1,1,"C",1);
$pdf->ln(3);
$total = 0;
$troca = 1;
if($xxnum > 0){
  for($x = 0; $x < $xxnum;$x++){
     db_fieldsmemory($result8,$x);
     if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
        $pdf->setfont('arial','b',8);
        $pdf->cell(20,$alt,'MATRICULA',1,0,"C",1);
        $pdf->cell(60,$alt,'NOME',1,1,"C",1);
        $troca = 0;
        $pre = 1;
     }
     if($pre == 1){
       $pre = 0;
     }else{
       $pre = 1;
     }
     $pdf->setfont('arial','',7);
     $pdf->cell(20,$alt,$rh01_regist,0,0,"C",$pre);
     $pdf->cell(60,$alt,$z01_nome,0,1,"L",$pre);
     $total += 1;
  }
  $pdf->setfont('arial','b',8);
  $pdf->cell(0,$alt,'TOTAL DE REGISTROS :  '.$total,"T",0,"C",0);
}else{
$pdf->cell(0,$alt,'Sem Funcionarios Com Diferenca.',"T",0,"C",0);
}
$pdf->ln(6);


$result9 = pg_query($sql9);
$xxnum = pg_numrows($result9);
$pdf->cell(0,$alt,'LOTACAO = FUNDO E LOCAL DE TRABALHO <> FUNDO',1,1,"C",1);
$pdf->ln(3);
$total = 0;
$troca = 1;
if($xxnum > 0){
  for($x = 0; $x < $xxnum;$x++){
     db_fieldsmemory($result9,$x);
     if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
        $pdf->setfont('arial','b',8);
        $pdf->cell(20,$alt,'MATRICULA',1,0,"C",1);
        $pdf->cell(60,$alt,'NOME',1,1,"C",1);
        $troca = 0;
        $pre = 1;
     }
     if($pre == 1){
       $pre = 0;
     }else{
       $pre = 1;
     }
     $pdf->setfont('arial','',7);
     $pdf->cell(20,$alt,$rh01_regist,0,0,"C",$pre);
     $pdf->cell(60,$alt,$z01_nome,0,1,"L",$pre);
     $total += 1;
  }
  $pdf->setfont('arial','b',8);
  $pdf->cell(0,$alt,'TOTAL DE REGISTROS :  '.$total,"T",0,"C",0);
}else{
$pdf->cell(0,$alt,'Sem Funcionarios Com Diferenca.',"T",0,"C",0);
}
$pdf->ln(6);


$pdf->Output();
   
?>
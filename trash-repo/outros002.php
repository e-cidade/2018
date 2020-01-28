<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

include("fpdf151/impcarne.php");
include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("classes/db_basesr_classe.php");
$clbasesr = new cl_basesr;

$sql_in = $clbasesr->sql_query_file($ano,$mes,"B995",null,db_getsession("DB_instit"),"r09_rubric");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$sql_inst = "select * from db_config where codigo = ".db_getsession("DB_instit");
$result_inst = pg_exec($sql_inst);

db_fieldsmemory($result_inst,0);


//// nome das previdencias
$sql_nome = "SELECT distinct (r33_codtab-2) as r33_codtab,r33_nome, r33_ppatro
         FROM inssirf 
   WHERE r33_anousu = $ano 
	   and r33_mesusu = $mes
	   and r33_codtab in ($previdencia)
	   and r33_codtab > 2
	   and r33_instit = ".db_getsession("DB_instit")."
	  ";
$res_nome = pg_query($sql_nome);
//db_criatabela($res_nome);
$virg_nome = '';
$descr_nome = '';
for($inome=0;$inome<pg_numrows($res_nome);$inome++){
 db_fieldsmemory($res_nome,$inome);
 $descr_nome .= $virg_nome.$r33_nome;
 $virg_nome   = ', '; 
}

$head2 = 'RESUMO DA PREVIDÊNCIA';
$head4 = 'TABELAS '.$descr_nome;
$head6 = 'PERÍODO : '.$ano.' / '.$mes;

$xbases    = " ('R992') ";
$xdeducao  = " (".$sql_in.") ";
$xrubricas = " ('R901','R902','R903','R904','R905','R906','R907','R908','R909','R910','R911','R912') ";
$prev      = " and rh02_tbprev in ($previdencia) ";
$devolucao = " ('')";


$soma     = 0;
$base     = 0;
$ded      = 0;
$dev      = 0;
$desco    = 0;
$patronal = 0;

for($inome=0;$inome<pg_numrows($res_nome);$inome++){
    db_fieldsmemory($res_nome,$inome);
    if($tipo == 's'){

    $sql = "
    select *,desco1+patronal1-ded1 as total
    from
    (    
    select r70_estrut,
           r70_descr,
           count(soma) as soma1,
           round(sum(base),2)       as base1,
           round(sum(ded),2)        as ded1,
           round(sum(dev),2)        as dev1,
           round(sum(desco),2)      as desco1,
           round(sum(base)/100*$r33_ppatro,2) as patronal1
    from 
    (
    select r70_estrut,
           r70_descr,
           r01_regist as soma ,
           sum(base)       as base,
           sum(ded)        as ded,
           sum(dev)        as dev,
           sum(desco)      as desco
    from 
    (
    select 
           rh01_regist as r01_regist,
           z01_nome,
           r70_estrut,
           r70_descr,
           rh03_padrao,
           sum(case when r14_rubric in ".$xrubricas." then r14_valor else 0 end) as desco,
           sum(case when r14_rubric in ".$xdeducao." then r14_valor else 0 end) as ded ,
           sum(case when r14_rubric in ".$devolucao." then r14_valor else 0 end) as dev ,
           sum(case when r14_rubric in ".$xbases."    then r14_valor else 0 end) as base
    from gerfsal 
         inner join rhpessoalmov on rh02_anousu = r14_anousu 
                                and rh02_mesusu = r14_mesusu 
                                and rh02_regist = r14_regist
                                and rh02_instit = r14_instit
         inner join rhlota       on r70_codigo  = rh02_lota
                                and r70_instit  = rh02_instit
         inner join rhpessoal    on rh01_regist = r14_regist 
         left join rhpespadrao   on rh03_seqpes    = rhpessoalmov.rh02_seqpes
         inner join cgm on rh01_numcgm = z01_numcgm  
    where r14_anousu = $ano 
      and r14_mesusu = $mes
      and r14_instit = ".db_getsession('DB_instit')."
      and rh02_tbprev = $r33_codtab
      and ( r14_rubric in ".$xrubricas." 
         or r14_rubric in ".$xdeducao." 
         or r14_rubric in ".$devolucao." 
         or r14_rubric in ".$xbases.")
      group by 
           rh01_regist,
           z01_nome,
           r70_estrut,
           r70_descr,
           rh03_padrao

    union all

    select rh01_regist,
           z01_nome,
           r70_estrut,
           r70_descr,
           rh03_padrao,
           sum(case when r48_rubric in ".$xrubricas." then r48_valor else 0 end) as desco,
           sum(case when r48_rubric in ".$xdeducao."  then r48_valor else 0 end) as ded ,
           sum(case when r48_rubric in ".$devolucao." then r48_valor else 0 end) as dev ,
           sum(case when r48_rubric in ".$xbases."    then r48_valor else 0 end) as base
    from gerfcom
         inner join rhpessoalmov on rh02_anousu = r48_anousu 
                                and rh02_mesusu = r48_mesusu 
                                and rh02_regist = r48_regist
                                and rh02_instit = r48_instit
         inner join rhlota       on r70_codigo  = rh02_lota
                                and r70_instit  = rh02_instit
         inner join rhpessoal    on rh01_regist = r48_regist 
         left join rhpespadrao   on rh03_seqpes    = rhpessoalmov.rh02_seqpes
         inner join cgm on rh01_numcgm = z01_numcgm  
    where r48_anousu = $ano
      and r48_mesusu = $mes
      and r48_instit = ".db_getsession('DB_instit')."
      and rh02_tbprev = $r33_codtab
      and ( r48_rubric in ".$xrubricas." 
         or r48_rubric in ".$xdeducao." 
         or r48_rubric in ".$devolucao." 
         or r48_rubric in ".$xbases." )
      group by 
           rh01_regist,
           z01_nome,
           r70_estrut,
           r70_descr,
           rh03_padrao
                     
    union all

    select rh01_regist,
           z01_nome,
           r70_estrut,
           r70_descr,
           rh03_padrao,
           sum(case when r20_rubric in ".$xrubricas." then r20_valor else 0 end) as desco,
           sum(case when r20_rubric in ".$xdeducao."  then r20_valor else 0 end) as ded ,
           sum(case when r20_rubric in ".$devolucao." then r20_valor else 0 end) as dev ,
           sum(case when r20_rubric in ".$xbases."    then r20_valor else 0 end) as base
    from gerfres
         inner join rhpessoalmov on rh02_anousu = r20_anousu 
                                and rh02_mesusu = r20_mesusu 
                                and rh02_regist = r20_regist
                                and rh02_instit = r20_instit
         inner join rhlota       on r70_codigo  = rh02_lota
                                and r70_instit  = rh02_instit
         inner join rhpessoal    on rh01_regist = r20_regist 
         left join rhpespadrao   on rh03_seqpes    = rhpessoalmov.rh02_seqpes
         inner join cgm on rh01_numcgm = z01_numcgm  
    where r20_anousu = $ano
      and r20_mesusu = $mes
      and r20_instit = ".db_getsession('DB_instit')."
      and rh02_tbprev = $r33_codtab
      and ( r20_rubric in ".$xrubricas." 
         or r20_rubric in ".$xdeducao." 
         or r20_rubric in ".$devolucao." 
         or r20_rubric in ".$xbases.")
      group by 
           rh01_regist,
           z01_nome,
           r70_estrut,
           r70_descr,
           rh03_padrao
    ) as xx group by r01_regist,
                     r70_estrut,
                     r70_descr
    ) as xxx
      group by r70_estrut,
               r70_descr
      order by r70_estrut
) as yyyy
                     
           ";
    }else{
    $sql = "
    select *,desco1+patronal1-ded1 as total
    from
    (
    select r70_estrut,
           r70_descr,
           count(soma) as soma1,
           round(sum(base),2)       as base1,
           round(sum(ded),2)        as ded1,
           round(sum(dev),2)        as dev1,
           round(sum(desco),2)      as desco1,
           round(sum(base)/100*$r33_ppatro,2) as patronal1
    from 
    (
    select r70_estrut,
           r70_descr,
           rh01_regist as soma ,
           sum(base)       as base,
           sum(ded)        as ded,
           sum(dev)        as dev,
           sum(desco)      as desco
    from 
    (
    select rh01_regist,
           z01_nome,
           r70_estrut,
           r70_descr,
           rh03_padrao,
           case when r35_rubric in ".$xrubricas." then r35_valor else 0 end as desco,
           case when r35_rubric in ".$xdeducao."  then r35_valor else 0 end as ded ,
           case when r35_rubric in ".$devolucao." then r35_valor else 0 end as dev ,
           case when r35_rubric in ".$xbases."    then r35_valor else 0 end as base
    from gerfs13 
         inner join rhpessoalmov on rh02_anousu = r35_anousu 
                                and rh02_mesusu = r35_mesusu 
                                and rh02_regist = r35_regist
                                and rh02_instit = r35_instit
         inner join rhlota       on r70_codigo  = rh02_lota  
                                and r70_instit  = rh02_instit
         inner join rhpessoal    on rh01_regist = r35_regist 
         left join rhpespadrao   on rh03_seqpes    = rhpessoalmov.rh02_seqpes
         inner join cgm on rh01_numcgm = z01_numcgm  
    where r35_anousu = $ano 
      and r35_mesusu = $mes
      and r35_instit = ".db_getsession('DB_instit')."
      and rh02_tbprev = $r33_codtab
      and ( r35_rubric in ".$xrubricas." 
         or r35_rubric in ".$xdeducao." 
         or r35_rubric in ".$devolucao." 
         or r35_rubric in ".$xbases.")

    ) as xx group by rh01_regist,
                     r70_estrut,
                     r70_descr
    ) as xxx
    group by r70_estrut,
             r70_descr
    order by r70_estrut
) as yyy
                     
           ";
    }
}
$result = pg_exec($sql);
//  echo $sql;exit;
    //db_criatabela($result);exit;
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
$tsoma1     = 0;
$tbase1     = 0;
$tded1      = 0;
$tdesco1    = 0;
$tpatronal1 = 0;
$ttotal     = 0;

for($x = 0; $x < $xxnum;$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',7);
      $pdf->cell(15,$alt,'LOTAÇÃO',1,0,"C",1);
      $pdf->cell(60,$alt,'DESCRIÇÃO',1,0,"C",1);
      $pdf->cell(15,$alt,'FUNC.',1,0,"C",1);
      $pdf->cell(20,$alt,'BASE',1,0,"C",1);
      $pdf->cell(20,$alt,'DEDUÇÃO',1,0,"C",1);
      $pdf->cell(20,$alt,'DESCONTO',1,0,"C",1);
      $pdf->cell(20,$alt,'PATRONAL',1,0,"C",1);
      $pdf->cell(20,$alt,'TOTAL',1,1,"C",1);
      $troca = 0;
      $pre = 1;
   }
   if($pre == 1){
     $pre = 0;
   }else{
     $pre = 1;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$r70_estrut,0,0,"C",$pre);
   $pdf->cell(60,$alt,$r70_descr,0,0,"L",$pre);
   $pdf->cell(15,$alt,$soma1,0,0,"C",$pre);
   $pdf->cell(20,$alt,db_formatar($base1,'f'),0,0,"R",$pre);
   $pdf->cell(20,$alt,db_formatar($ded1,'f'),0,0,"R",$pre);
   $pdf->cell(20,$alt,db_formatar($desco1,'f'),0,0,"R",$pre);
   $pdf->cell(20,$alt,db_formatar($patronal1,'f'),0,0,"R",$pre);
   $pdf->cell(20,$alt,db_formatar($total,'f'),0,1,"R",$pre);
   $tsoma1     += $soma1     ;
   $tbase1     += $base1     ;
   $tded1      += $ded1      ;
   $tdesco1    += $desco1    ;
   $tpatronal1 += $patronal1 ;
   $ttotal     += $total     ;
}
if($pre == 1){
  $pre = 0;
}else{
  $pre = 1;
}
$pdf->setfont('arial','B',7);
$pdf->cell(75,$alt,'TOTAL',1,0,"C",$pre);
$pdf->cell(15,$alt,$tsoma1,1,0,"C",$pre);
$pdf->cell(20,$alt,db_formatar($tbase1,'f'),1,0,"R",$pre);
$pdf->cell(20,$alt,db_formatar($tded1,'f'),1,0,"R",$pre);
$pdf->cell(20,$alt,db_formatar($tdesco1,'f'),1,0,"R",$pre);
$pdf->cell(20,$alt,db_formatar($tpatronal1,'f'),1,0,"R",$pre);
$pdf->cell(20,$alt,db_formatar($ttotal,'f'),1,1,"R",$pre);

$pdf->Output();
?>
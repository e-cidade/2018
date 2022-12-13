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

require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");

$clrotulo = new rotulocampo;
$clrotulo->label('r06_codigo');
$clrotulo->label('r06_descr');
$clrotulo->label('r06_elemen');
$clrotulo->label('r06_pd');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;


if($sel != 0){
  $result_sel = db_query("select r44_where , r44_descr from selecao where r44_selec = {$sel} and r44_instit = " . db_getsession('DB_instit'));
  if(pg_numrows($result_sel) > 0){
    db_fieldsmemory($result_sel, 0, 1);
    $wherepes .= " and ".$r44_where;
    $head6 = $r44_descr;
    $erroajuda = " ou seleção informada é inválida";
  }
}

$head2 = "CADASTRO DE RUBRICAS ANUAIS";
$head4 = "ANO : ".$ano;
$head8 = "RUBRICAS : ".$rubrs;

if($tipo == 'v'){
  $campo = 'valor';
}else{
  $campo = 'quant';
}

$union_calculo = '';

$primeiro = 0;

$pos_calculo = stripos(' '.$ponts,'1');

if($pos_calculo != false){
  $dbwhererubs = '';
  if(trim($rubrs) != ""){
    $dbwhererubs = " and r14_rubric in ('".str_replace(",","','",$rubrs)."')";
  }
  if($primeiro != 0){
    $union_calculo .= ' union all ';
  }
  $union_calculo .= " select r14_regist as regist , r14_mesusu as mesusu , round(sum(r14_$campo),2) as valor 
                      from gerfsal 
                      where r14_anousu = $ano 
                        and r14_instit = ".db_getsession('DB_instit')."
                        $dbwhererubs
                      group by r14_regist, r14_mesusu
                      ";
  $primeiro = 1;
}

$pos_calculo = stripos(' '.$ponts,'2');

if($pos_calculo != false){
  $dbwhererubs = '';
  if(trim($rubrs) != ""){
    $dbwhererubs = " and r22_rubric in ('".str_replace(",","','",$rubrs)."')";
  }
  if($primeiro != 0){
    $union_calculo .= ' union all ';
  }
  $union_calculo .= " select r22_regist as regist , r22_mesusu as mesusu , round(sum(r22_$campo),2) as valor 
                      from gerfadi 
                      where r22_anousu = $ano 
                        and r22_instit = ".db_getsession('DB_instit')."
                        $dbwhererubs
                      group by r22_regist r22_mesusu
                      ";
  $primeiro = 1;
}

$pos_calculo = stripos(' '.$ponts,'3');

if($pos_calculo != false){
  $dbwhererubs = '';
  if(trim($rubrs) != ""){
    $dbwhererubs = " and r48_rubric in ('".str_replace(",","','",$rubrs)."')";
  }
  if($primeiro != 0){
    $union_calculo .= ' union all ';
  }
  $union_calculo .= " select r48_regist as regist , r48_mesusu as mesusu , round(sum(r48_$campo),2) as valor
                      from gerfcom 
                      where r48_anousu = $ano 
                        and r48_instit = ".db_getsession('DB_instit')."
                        $dbwhererubs
                      group by r48_regist, r48_mesusu
                      ";
  $primeiro = 1;
}

$pos_calculo = stripos(' '.$ponts,'4');

if($pos_calculo != false){
  $dbwhererubs = '';
  if(trim($rubrs) != ""){
    $dbwhererubs = " and r20_rubric in ('".str_replace(",","','",$rubrs)."')";
  }
  if($primeiro != 0){
    $union_calculo .= ' union all ';
  }
  $union_calculo .= " select r20_regist as regist , r20_mesusu as mesusu , round(sum(r20_$campo),2) as valor
                      from gerfres 
                      where r20_anousu = $ano 
                        and r20_instit = ".db_getsession('DB_instit')."
                        $dbwhererubs
                      group by r20_regist, r20_mesusu
                      ";
  $primeiro = 1;
}

$pos_calculo = stripos(' '.$ponts,'5');

if($pos_calculo != false){
  $dbwhererubs = '';
  if(trim($rubrs) != ""){
    $dbwhererubs = " and r35_rubric in ('".str_replace(",","','",$rubrs)."')";
  }
  if($primeiro != 0){
    $union_calculo .= ' union all ';
  }
  $union_calculo .= " select r35_regist as regist , r35_mesusu as mesusu , round(sum(r35_$campo),2) as valor
                      from gerfs13 
                      where r35_anousu = $ano 
                        and r35_instit = ".db_getsession('DB_instit')."
                        $dbwhererubs
                      group by r35_regist, r35_mesusu
                      ";
  $primeiro = 1;
}

$pos_calculo = stripos(' '.$ponts,'6');

if($pos_calculo != false){
  $dbwhererubs = '';
  if(trim($rubrs) != ""){
    $dbwhererubs = " and r53_rubric in ('".str_replace(",","','",$rubrs)."')";
  }
  if($primeiro != 0){
    $union_calculo .= ' union all ';
  }
  $union_calculo .= " select r53_regist as regist , r53_mesusu as mesusu , round(sum(r53_$campo),2) as valor
                      from gerffx  
                      where r53_anousu = $ano 
                        and r53_instit = ".db_getsession('DB_instit')."
                        $dbwhererubs
                      group by r53_regist, r53_mesusu
                      ";
  $primeiro = 1;
}

$sql_princ = 
"
select
 matricula as Matricula, 
       z01_nome as Nome ,
    round (jan, 2) as jan,
     fev  as fev,
     mar  as mar,
     abr  as abr,
     mai  as mai,
     jun  as jun,
     jul  as jul,
     ago  as ago,
     sete as set,
     outu as out,
     nov  as nov,
     dez  as dez,
     round (jan+fev+mar+abr+mai+jun+jul+ago+sete+outu+nov+dez,2)  as total 
     from
(select distinct rh01_regist as Matricula,
        z01_nome,
sum (case when mesusu =01 then valor else 0  end) as jan,
sum (case when mesusu =02 then valor else 0  end) as fev,
sum (case when mesusu =03 then valor else 0  end) as mar,
sum (case when mesusu =04 then valor else 0  end) as abr,
sum (case when mesusu =05 then valor else 0  end) as mai,
sum (case when mesusu =06 then valor else 0  end) as jun,
sum (case when mesusu =07 then valor else 0  end) as jul,
sum (case when mesusu =08 then valor else 0  end) as ago,
sum (case when mesusu =09 then valor else 0  end) as sete,
sum (case when mesusu =10 then valor else 0  end) as outu,
sum (case when mesusu =11 then valor else 0  end) as nov,
sum (case when mesusu =12 then valor else 0  end) as dez
from rhpessoal
      inner join cgm            on rh01_numcgm = z01_numcgm
      inner join rhfuncao       on rh37_funcao = rh01_funcao
                                and rh37_instit = ".db_getsession('DB_instit')." 
      inner join rhpessoalmov   on rh02_anousu = $anofolha
                               and rh02_mesusu = $mesfolha
                               and rh02_instit = ".db_getsession('DB_instit')."
                               and rh02_regist = rh01_regist
     left join rhpesrescisao    on rh02_seqpes = rh05_seqpes
     inner join ( select * from ($union_calculo) as x ) as calculo              
                                on regist = rh02_regist
     inner join rhlota          on r70_codigo  = rh02_lota
                               and r70_instit  = rh02_instit
     left join rhlotaexe        on r70_codigo  = rh26_codigo
                               and rh26_anousu = rh02_anousu
     inner join rhregime        on rh30_codreg = rh02_codreg
                               and rh30_instit = rh02_instit
     left join orcorgao         on rh26_orgao  = o40_orgao
                               and rh26_anousu = o40_anousu
                               and o40_instit  = rh02_instit
                               and o40_anousu  = rh02_anousu
       
  where 1 = 1
   $wherepes
   group by rh01_regist, z01_nome
 order by  z01_nome) as xy
";

//echo "<br><br>".$sql_princ;exit; 
$result = db_query($sql_princ);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Códigos cadastrados no período de '.$mesfolha.' / '.$anofolha);

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$totfun = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;

$t_jan = 0;
$t_fev = 0; 
$t_mar = 0;
$t_abr = 0;
$t_mai = 0;
$t_jun = 0;
$t_jul = 0;
$t_ago = 0;
$t_set = 0;
$t_out = 0;
$t_nov = 0;
$t_dez = 0;
$xtotal= 0;

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage('L');
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'Matricula',1,0,"C",1);
      $pdf->cell(60,$alt,'Nome',1,0,"C",1);
      $pdf->cell(15,$alt,'Janeiro',1,0,"C",1);
      $pdf->cell(15,$alt,'Fevereiro',1,0,"C",1);
      $pdf->cell(15,$alt,'Março',1,0,"C",1);
      $pdf->cell(15,$alt,'Abril',1,0,"C",1);
      $pdf->cell(15,$alt,'Maio',1,0,"C",1);
      $pdf->cell(15,$alt,'Junho',1,0,"C",1);
      $pdf->cell(15,$alt,'Julho',1,0,"C",1);
      $pdf->cell(15,$alt,'Agosto',1,0,"C",1);
      $pdf->cell(15,$alt,'Setembro',1,0,"C",1);
      $pdf->cell(15,$alt,'Outubro',1,0,"C",1);
      $pdf->cell(15,$alt,'Novembro',1,0,"C",1);
      $pdf->cell(15,$alt,'Dezembro',1,0,"C",1);
      $pdf->cell(15,$alt,'Total',1,1,"C",1);
      $troca = 0;
      $pre = 1;
   }
   if($pre == 1){
     $pre = 0;
   }else{
     $pre = 1;
   }
   $pdf->setfont('arial','',6);
   $pdf->cell(15,$alt,$matricula,0,0,"C",$pre);
   $pdf->cell(60,$alt,$nome,0,0,"L",$pre);
   $pdf->cell(15,$alt,db_formatar($jan,'f'),0,0,"R",$pre);
   $pdf->cell(15,$alt,db_formatar($fev,'f'),0,0,"R",$pre);
   $pdf->cell(15,$alt,db_formatar($mar,'f'),0,0,"R",$pre);
   $pdf->cell(15,$alt,db_formatar($abr,'f'),0,0,"R",$pre);
   $pdf->cell(15,$alt,db_formatar($mai,'f'),0,0,"R",$pre);
   $pdf->cell(15,$alt,db_formatar($jun,'f'),0,0,"R",$pre);
   $pdf->cell(15,$alt,db_formatar($jul,'f'),0,0,"R",$pre);
   $pdf->cell(15,$alt,db_formatar($ago,'f'),0,0,"R",$pre);
   $pdf->cell(15,$alt,db_formatar($set,'f'),0,0,"R",$pre);
   $pdf->cell(15,$alt,db_formatar($out,'f'),0,0,"R",$pre);
   $pdf->cell(15,$alt,db_formatar($nov,'f'),0,0,"R",$pre);
   $pdf->cell(15,$alt,db_formatar($dez,'f'),0,0,"R",$pre);
   $xtotal = $jan+$fev+$mar+$abr+$mai+$jun+$jul+$ago+$set+$out+$nov+$dez;
   $pdf->cell(15,$alt,db_formatar($xtotal,'f'),0,1,"R",$pre);

   $totfun    += 1;
   $t_jan    += $jan;  
   $t_fev    += $fev;
   $t_mar    += $mar;
   $t_abr    += $abr;
   $t_mai    += $mai;
   $t_jun    += $jun;
   $t_jul    += $jul;
   $t_ago    += $ago;
   $t_set    += $set;
   $t_out    += $out;
   $t_nov    += $nov;
   $t_dez    += $dez;
   $t_xtotal += $xtotal;
}
$pdf->setfont('arial','b',6);
$pdf->cell(75,$alt,'TOTAIS : '.$totfun.' REGISTROS',0,0,"L",$pre);
$pdf->cell(15,$alt,db_formatar($t_jan,'f'),0,0,"R",$pre);
$pdf->cell(15,$alt,db_formatar($t_fev,'f'),0,0,"R",$pre);
$pdf->cell(15,$alt,db_formatar($t_mar,'f'),0,0,"R",$pre);
$pdf->cell(15,$alt,db_formatar($t_abr,'f'),0,0,"R",$pre);
$pdf->cell(15,$alt,db_formatar($t_mai,'f'),0,0,"R",$pre);
$pdf->cell(15,$alt,db_formatar($t_jun,'f'),0,0,"R",$pre);
$pdf->cell(15,$alt,db_formatar($t_jul,'f'),0,0,"R",$pre);
$pdf->cell(15,$alt,db_formatar($t_ago,'f'),0,0,"R",$pre);
$pdf->cell(15,$alt,db_formatar($t_set,'f'),0,0,"R",$pre);
$pdf->cell(15,$alt,db_formatar($t_out,'f'),0,0,"R",$pre);
$pdf->cell(15,$alt,db_formatar($t_nov,'f'),0,0,"R",$pre);
$pdf->cell(15,$alt,db_formatar($t_dez,'f'),0,0,"R",$pre);
$pdf->cell(15,$alt,db_formatar($t_xtotal,'f'),0,1,"R",$pre);

$pdf->Output();
   
?>
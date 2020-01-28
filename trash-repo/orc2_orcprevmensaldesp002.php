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

include("libs/db_liborcamento.php");

$tipo_mesini = 1;
$tipo_mesfim = 1;

//$tipo_impressao = 1;
// 1 = orcamento
// 2 = balanco
//$tipo_agrupa = 1;
// 1 = geral
// 2 = orgao
// 3 = unidade
//$tipo_nivel = 6;
// 1 = funcao
// 2 = subfuncao
// 3 = programa
// 4 = projeto/atividade
// 5 = elemento 
// 6 = recurso 
$tipo_agrupa = 3;
$tipo_nivel = 6;

$qorgao = 0;
$qunidade = 0;


include("fpdf151/pdf.php");
include("libs/db_sql.php");

//db_postmemory($HTTP_POST_VARS,2);exit;
db_postmemory($HTTP_POST_VARS);

$anousu  = db_getsession("DB_anousu");

if($bimestre == 1){
  $bim = '1o. Bimestre';
  $dataini = $anousu.'-01-01';
  $datafin = $anousu.'-01-01';
}elseif($bimestre == 2){
  $bim = '2o. Bimestre';
  $dataini = $anousu.'-03-01';
  $datafin = $anousu.'-03-01';
}elseif($bimestre == 3){
  $bim = '3o. Bimestre';
  $dataini = $anousu.'-05-01';
  $datafin = $anousu.'-05-01';
}elseif($bimestre == 4){
  $bim = '4o. Bimestre';
  $dataini = $anousu.'-07-01';
  $datafin = $anousu.'-07-01';
}elseif($bimestre == 5){
  $bim = '5o. Bimestre';
  $dataini = $anousu.'-09-01';
  $datafin = $anousu.'-09-01';
}elseif($bimestre == 6){
  $bim = '6o. Bimestre';
  $dataini = $anousu.'-11-01';
  $datafin = $anousu.'-11-01';
}  

if($nivel == 1){
  $quebra = 'Orgão';
  $sele  = ' o58_orgao as codigo, initcap(o40_descr) as descr';
  $grupo = 'o58_orgao, o40_descr '; 
}elseif($nivel == 2){
  $quebra = 'Unidade';
  $sele  = ' o58_unidade as codigo, initcap(o41_descr) as descr';
  $grupo = ' o58_unidade, o41_descr '; 
}elseif($nivel == 3){
  $quebra = 'Função';
  $sele  = ' o58_funcao as codigo, initcap(o52_descr) as descr';
  $grupo = ' o58_funcao, o52_descr '; 
}elseif($nivel == 4){
  $quebra = 'Subfunção';
  $sele  = ' o58_subfuncao as codigo, initcap(o53_descr) as descr';
  $grupo = ' o58_subfuncao, o53_descr '; 
}elseif($nivel == 5){
  $quebra = 'Programa';
  $sele  = ' o58_programa as codigo, initcap(o54_descr) as descr';
  $grupo = ' o58_programa, o54_descr '; 
}elseif($nivel == 6){
  $quebra = 'Projeto/Atividade';
  $sele  = ' o58_projativ as codigo, initcap(o55_descr) as descr';
  $grupo = ' o58_projativ, o55_descr '; 
}elseif($nivel == 7){
  $quebra = 'Elemento';
  $sele  = ' o58_elemento as codigo, initcap(o56_descr) as descr';
  $grupo = ' o58_elemento, o56_descr '; 
}elseif($nivel == 8){
  $quebra = 'Recurso';
  $sele  = ' o58_codigo as codigo, initcap(o15_descr) as descr';
  $grupo = ' o58_codigo, o15_descr '; 
}
//---------------------------------------------------------------  
$clselorcdotacao = new cl_selorcdotacao();
$clselorcdotacao->setDados($filtra_despesa); // passa os parametros vindos da func_selorcdotacao_abas.php
$instits= $clselorcdotacao->getInstit();

if (trim(@$instits)==""){
     $instits = db_getsession("DB_instit");
}

/*
 echo "<br>instit : $instits";
 echo "<br>parametros : $parametros";
 echo "<br>dados : ".$selorcdotacao->getDados();
 exit;
*/

//@ recupera as informa??es fornecidas para gerar os dados
//---------------------------------------------------------------  

$head1 = "PREVISÃO DA DESPESA";
$head3 = $bim;
$head5 = 'Quebra :  '.$quebra;
$resultinst = pg_exec("select codigo,nomeinst from db_config where codigo in ($instits)");
$descr_inst = '';
$xvirg = '';
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
  db_fieldsmemory($resultinst,$xins);
  $descr_inst .= $xvirg.$nomeinst ; 
  $xvirg = ', ';
}
//$head5 = "INSTITUI??ES : ".$descr_inst;
//$head6 = "Per?odo : ".$data_ini_exibida."   ?  ".$data_fin_exibida;

/////////////////////////////////////////////////////////

$sele_work = $clselorcdotacao->getDados()." and w.o58_instit in ($instits) ";
// echo $sele_work;
// exit;

  $completo = false;
  $nivela = substr($nivel,0,1);
  if($nivela=="9"){
    $completo = true;
    $nivela = "8";
  }
  //db_criatabela(pg_exec("select * from t"));
  /*
  
  $dataini = date("m-d",db_getsession("DB_datausu"));
  $datafin = date("m-d",db_getsession("DB_datausu"));
  // ajuste pra pegar o exercicio e n?o a data do linux
  $dataini = $anousu."-".$dataini;
  $datafin = $anousu."-".$datafin;
 */
$nivela = 8; 
$sql_dotacao = db_dotacaosaldo($nivela,2,2,true,$sele_work,$anousu,$dataini,$datafin,8,0,true);
 
pg_query("create temporary table prev_desp as ".$sql_dotacao);
/*
$sql = "
        select 
          o58_orgao, 
          o40_descr, 
          o58_unidade, 
          o41_descr, 
          o58_funcao, 
          o52_descr, 
          o58_subfuncao, 
          o53_descr,
          o58_programa,
          o54_descr,
          o58_projativ,
          o55_descr,
          o58_elemento,
          o56_descr,
          o58_coddot,
          o58_codigo,
          o15_descr,
          atual,
          atual/100*max(jan) as jan,
          atual/100*max(fev) as fev,
          atual/100*max(mar) as mar,
          atual/100*max(abr) as abr,
          atual/100*max(mai) as mai,
          atual/100*max(jun) as jun,
          atual/100*max(jul) as jul,
          atual/100*max(ago) as ago,
          atual/100*max(set) as set,
          atual/100*max(out) as out,
          atual/100*max(nov) as nov,
          atual/100*max(dez) as dez
          
       from 
        (
        select 
          o58_orgao, 
          o40_descr, 
          o58_unidade, 
          o41_descr, 
          o58_funcao, 
          o52_descr, 
          o58_subfuncao, 
          o53_descr,
          o58_programa,
          o54_descr,
          o58_projativ,
          o55_descr,
          o58_elemento,
          o56_descr,
          o58_coddot,
          o58_codigo,
          o15_descr,
          case when o33_mes = 1  then o33_perc else 0 end as jan,
          case when o33_mes = 2  then o33_perc else 0 end as fev,
          case when o33_mes = 3  then o33_perc else 0 end as mar,
          case when o33_mes = 4  then o33_perc else 0 end as abr,
          case when o33_mes = 5  then o33_perc else 0 end as mai,
          case when o33_mes = 6  then o33_perc else 0 end as jun,
          case when o33_mes = 7  then o33_perc else 0 end as jul,
          case when o33_mes = 8  then o33_perc else 0 end as ago,
          case when o33_mes = 9  then o33_perc else 0 end as set,
          case when o33_mes = 10 then o33_perc else 0 end as out,
          case when o33_mes = 11 then o33_perc else 0 end as nov,
          case when o33_mes = 12 then o33_perc else 0 end as dez,
          atual
        from prev_desp
          left join orcreserprev on o33_projativ = o58_projativ 
                                and o33_codigo   = o58_codigo
                                and o33_anousu   = $anousu
        ) as x
        group by
          o58_orgao, 
          o40_descr, 
          o58_unidade, 
          o41_descr, 
          o58_funcao, 
          o52_descr, 
          o58_subfuncao, 
          o53_descr,
          o58_programa,
          o54_descr,
          o58_projativ,
          o55_descr,
          o58_elemento,
          o56_descr,
          o58_coddot,
          o58_codigo,
          o15_descr,
          atual
";
*/
$sql = "
        select
          $sele,
          sum(atual) as atual,
          sum(jan) as jan,
          sum(fev) as fev,
          sum(mar) as mar,
          sum(abr) as abr,
          sum(mai) as mai,
          sum(jun) as jun,
          sum(jul) as jul,
          sum(ago) as ago,
          sum(set) as set,
          sum(out) as out,
          sum(nov) as nov,
          sum(dez) as dez
       from 
       (
        select 
          o58_orgao, 
          o40_descr, 
          o58_unidade, 
          o41_descr, 
          o58_funcao, 
          o52_descr, 
          o58_subfuncao, 
          o53_descr,
          o58_programa,
          o54_descr,
          o58_projativ,
          o55_descr,
          o58_elemento,
          o56_descr,
          o58_coddot,
          o58_codigo,
          o15_descr,
          atual,
          atual/100*max(jan) as jan,
          atual/100*max(fev) as fev,
          atual/100*max(mar) as mar,
          atual/100*max(abr) as abr,
          atual/100*max(mai) as mai,
          atual/100*max(jun) as jun,
          atual/100*max(jul) as jul,
          atual/100*max(ago) as ago,
          atual/100*max(set) as set,
          atual/100*max(out) as out,
          atual/100*max(nov) as nov,
          atual/100*max(dez) as dez
          
       from 
        (
        select 
          o58_orgao, 
          o40_descr, 
          o58_unidade, 
          o41_descr, 
          o58_funcao, 
          o52_descr, 
          o58_subfuncao, 
          o53_descr,
          o58_programa,
          o54_descr,
          o58_projativ,
          o55_descr,
          o58_elemento,
          o56_descr,
          o58_coddot,
          o58_codigo,
          o15_descr,
          case when o33_mes = 1  then o33_perc else 0 end as jan,
          case when o33_mes = 2  then o33_perc else 0 end as fev,
          case when o33_mes = 3  then o33_perc else 0 end as mar,
          case when o33_mes = 4  then o33_perc else 0 end as abr,
          case when o33_mes = 5  then o33_perc else 0 end as mai,
          case when o33_mes = 6  then o33_perc else 0 end as jun,
          case when o33_mes = 7  then o33_perc else 0 end as jul,
          case when o33_mes = 8  then o33_perc else 0 end as ago,
          case when o33_mes = 9  then o33_perc else 0 end as set,
          case when o33_mes = 10 then o33_perc else 0 end as out,
          case when o33_mes = 11 then o33_perc else 0 end as nov,
          case when o33_mes = 12 then o33_perc else 0 end as dez,
          atual
        from prev_desp
          left join orcreserprev on o33_projativ = o58_projativ 
                                and o33_codigo   = o58_codigo
                                and o33_anousu   = $anousu
        ) as x
        group by
          o58_orgao, 
          o40_descr, 
          o58_unidade, 
          o41_descr, 
          o58_funcao, 
          o52_descr, 
          o58_subfuncao, 
          o53_descr,
          o58_programa,
          o54_descr,
          o58_projativ,
          o55_descr,
          o58_elemento,
          o56_descr,
          o58_coddot,
          o58_codigo,
          o15_descr,
          atual
        ) as xx
        group by
          $grupo
        order by 1
";
//echo $sql;exit;
$result = pg_query($sql); 
 
//db_criatabela($result);exit;
 
//pg_exec("commit");

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$troca         = 1;
$alt           = 4;
$pagina        = 1;
$t1 = 0;
$t2 = 0;
$t3 = 0;
$t4 = 0;
$t5 = 0;
$t6 = 0;
$t7 = 0;
$t8 = 0;
$t9 = 0;
$t10 = 0;
$t11 = 0;
$t12 = 0;


$tt = 0;

for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);

  if($pdf->gety()>$pdf->h-30 || $pagina ==1){
  	$pagina = 0;
  	$pdf->addpage('L');
    $pdf->setfont('arial','b',7);
    if($tipoimp=="B") {
      $pdf->cell(25,$alt,"Código",1,0,"L",1);
      $pdf->cell(80,$alt,"Descrição",1,0,"C",1);
      $pdf->cell(18,$alt,"1o. Bimetre",1,0,"C",1);
      $pdf->cell(18,$alt,"2o. Bimetre",1,0,"C",1);
      $pdf->cell(18,$alt,"3o. Bimetre",1,0,"C",1);
      $pdf->cell(18,$alt,"4o. Bimetre",1,0,"C",1);
      $pdf->cell(18,$alt,"5o. Bimetre",1,0,"C",1);
      $pdf->cell(18,$alt,"6o. Bimetre",1,0,"C",1);
      $pdf->cell(18,$alt,"TOTAL",1,1,"C",1);
    } else {
      if($nivel==7) { //Elemento
        $pdf->cell(20,$alt,"Código",1,0,"C",1);
        $pdf->cell(48,$alt,"Descrição",1,0,"C",1);
      } else {
        $pdf->cell(7,$alt,"Cód",1,0,"C",1);
        $pdf->cell(61,$alt,"Descrição",1,0,"C",1);
      }
      $pdf->cell(16,$alt,"Janeiro",1,0,"C",1);
      $pdf->cell(16,$alt,"Fevereiro",1,0,"C",1);
      $pdf->cell(16,$alt,"Março",1,0,"C",1);
      $pdf->cell(16,$alt,"Abril",1,0,"C",1);
      $pdf->cell(16,$alt,"Maio",1,0,"C",1);
      $pdf->cell(16,$alt,"Junho",1,0,"C",1);
      $pdf->cell(16,$alt,"Julho",1,0,"C",1);
      $pdf->cell(16,$alt,"Agosto",1,0,"C",1);
      $pdf->cell(16,$alt,"Setembro",1,0,"C",1);
      $pdf->cell(16,$alt,"Outubro",1,0,"C",1);
      $pdf->cell(16,$alt,"Novembro",1,0,"C",1);
      $pdf->cell(16,$alt,"Dezembro",1,0,"C",1);
      $pdf->cell(18,$alt,"TOTAL",1,1,"C",1);
    }
    $pdf->setfont('arial','',7);
    $pre = 1;
  }

  if($pre == 1) {
    $pre = 0;
  } else {
    $pre = 1;
  }
  if($tipoimp=="B") {
    $pdf->cell(25,$alt,$codigo,0,0,"L",$pre);
    $pdf->cell(80,$alt,$descr,0,0,"L",$pre);
    $pdf->cell(18,$alt,db_formatar($jan+$fev,'f'),0,0,"R",$pre);
    $pdf->cell(18,$alt,db_formatar($mar+$abr,'f'),0,0,"R",$pre);
    $pdf->cell(18,$alt,db_formatar($mai+$jun,'f'),0,0,"R",$pre);
    $pdf->cell(18,$alt,db_formatar($jul+$ago,'f'),0,0,"R",$pre);
    $pdf->cell(18,$alt,db_formatar($set+$out,'f'),0,0,"R",$pre);
    $pdf->cell(18,$alt,db_formatar($nov+$dez,'f'),0,0,"R",$pre);
    $pdf->cell(18,$alt,db_formatar($jan+$fev+$mar+$abr+$mai+$jun+$jul+$ago+$set+$out+$nov+$dez,'f'),0,1,"R",$pre);

    $t1 += $jan+$fev;
    $t2 += $mar+$abr;
    $t3 += $mai+$jun;
    $t4 += $jul+$ago;
    $t5 += $set+$out;
    $t6 += $nov+$dez;

  } else {
    if($nivel==7) { //Elemento
      $pdf->cell(20,$alt,$codigo,0,0,"R",$pre);
      $pdf->cell(48,$alt,substr($descr,0,36),0,0,"L",$pre);
    } else {
      $pdf->cell(7,$alt,$codigo,0,0,"R",$pre);
      $pdf->cell(61,$alt,substr($descr,0,47),0,0,"L",$pre);
    }

    $pdf->cell(16,$alt,db_formatar($jan,'f'),0,0,"R",$pre);
    $pdf->cell(16,$alt,db_formatar($fev,'f'),0,0,"R",$pre);
    $pdf->cell(16,$alt,db_formatar($mar,'f'),0,0,"R",$pre);
    $pdf->cell(16,$alt,db_formatar($abr,'f'),0,0,"R",$pre);
    $pdf->cell(16,$alt,db_formatar($mai,'f'),0,0,"R",$pre);
    $pdf->cell(16,$alt,db_formatar($jun,'f'),0,0,"R",$pre);
    $pdf->cell(16,$alt,db_formatar($jul,'f'),0,0,"R",$pre);
    $pdf->cell(16,$alt,db_formatar($ago,'f'),0,0,"R",$pre);
    $pdf->cell(16,$alt,db_formatar($set,'f'),0,0,"R",$pre);
    $pdf->cell(16,$alt,db_formatar($out,'f'),0,0,"R",$pre);
    $pdf->cell(16,$alt,db_formatar($nov,'f'),0,0,"R",$pre);
    $pdf->cell(16,$alt,db_formatar($dez,'f'),0,0,"R",$pre);
    $pdf->cell(18,$alt,db_formatar($jan+$fev+$mar+$abr+$mai+$jun+$jul+$ago+$set+$out+$nov+$dez,'f'),0,1,"R",$pre);

    $t1 += $jan;
    $t2 += $fev;
    $t3 += $mar;
    $t4 += $abr;
    $t5 += $mai;
    $t6 += $jun;
    $t7 += $jul;
    $t8 += $ago;
    $t9 += $set;
    $t10 += $out;
    $t11 += $nov;
    $t12 += $dez;

  }
  
   $tt += $jan+$fev+$mar+$abr+$mai+$jun+$jul+$ago+$set+$out+$nov+$dez;
}  
if($pre == 1) {
  $pre = 0;
} else {
  $pre = 1;
}
$pdf->setfont('arial','b',7);
if($tipoimp=="B") {
  $pdf->cell(105,$alt,'TOTAIS',"T",0,"L",$pre);
  $pdf->cell(18,$alt,db_formatar($t1,'f'),"T",0,"R",$pre);
  $pdf->cell(18,$alt,db_formatar($t2,'f'),"T",0,"R",$pre);
  $pdf->cell(18,$alt,db_formatar($t3,'f'),"T",0,"R",$pre);
  $pdf->cell(18,$alt,db_formatar($t4,'f'),"T",0,"R",$pre);
  $pdf->cell(18,$alt,db_formatar($t5,'f'),"T",0,"R",$pre);
  $pdf->cell(18,$alt,db_formatar($t6,'f'),"T",0,"R",$pre);
  $pdf->cell(18,$alt,db_formatar($tt,'f'),"T",1,"R",$pre);
} else {
  $pdf->cell(68,$alt,'TOTAIS',"T",0,"L",$pre);
  $pdf->cell(16,$alt,db_formatar($t1,'f'),"T",0,"R",$pre);
  $pdf->cell(16,$alt,db_formatar($t2,'f'),"T",0,"R",$pre);
  $pdf->cell(16,$alt,db_formatar($t3,'f'),"T",0,"R",$pre);
  $pdf->cell(16,$alt,db_formatar($t4,'f'),"T",0,"R",$pre);
  $pdf->cell(16,$alt,db_formatar($t5,'f'),"T",0,"R",$pre);
  $pdf->cell(16,$alt,db_formatar($t6,'f'),"T",0,"R",$pre);
  $pdf->cell(16,$alt,db_formatar($t7,'f'),"T",0,"R",$pre);
  $pdf->cell(16,$alt,db_formatar($t8,'f'),"T",0,"R",$pre);
  $pdf->cell(16,$alt,db_formatar($t9,'f'),"T",0,"R",$pre);
  $pdf->cell(16,$alt,db_formatar($t10,'f'),"T",0,"R",$pre);
  $pdf->cell(16,$alt,db_formatar($t11,'f'),"T",0,"R",$pre);
  $pdf->cell(16,$alt,db_formatar($t12,'f'),"T",0,"R",$pre);
  $pdf->cell(18,$alt,db_formatar($tt,'f'),"T",1,"R",$pre);
}

//include("fpdf151/geraarquivo.php");
$pdf->Output();

//pg_exec("commit");

?>
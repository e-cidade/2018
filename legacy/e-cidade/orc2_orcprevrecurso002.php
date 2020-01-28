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
include("libs/db_liborcamento.php");
include("classes/db_orctiporec_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$ano = db_getsession("DB_anousu");

$head3 = "PREVISAO POR RECURSO";
$head5 = "EXERCICIO : ".$ano;

//////// dados da despesa


$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$pre = 0;



$cltiporec = new cl_orctiporec;

if(empty($recurso)){

  $resultr = $cltiporec->sql_record($cltiporec->sql_query_file());
  
}else{

  $resultr = $cltiporec->sql_record($cltiporec->sql_query_file($recurso));
	
}
for($tiporec=0;$tiporec<$cltiporec->numrows;$tiporec++){

   db_fieldsmemory($resultr,$tiporec);
   
   $recurso = $o15_codigo;

pg_exec("begin");

$tipo_mesini = 1;
$tipo_mesfim = 1;
$tipo_agrupa = 3;
$tipo_nivel = 6;
$qorgao = 0;
$qunidade = 0;

$anousu  = db_getsession("DB_anousu");

/*
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
*/

$dataini = $anousu.'-01-01';
$datafin = $anousu.'-12-31';


$quebra = 'Recurso';
$sele  = ' o58_codigo as codigo, o15_descr as descr';
$grupo = ' o58_codigo, o15_descr '; 



//---------------------------------------------------------------  
$clselorcdotacao = new cl_selorcdotacao();

$instits = "(".db_getsession("DB_instit").")";

$resultinst = pg_exec("select codigo,nomeinst from db_config where codigo in $instits");
$descr_inst = '';
$xvirg = '';
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
  db_fieldsmemory($resultinst,$xins);
  $descr_inst .= $xvirg.$nomeinst ; 
  $xvirg = ', ';
}

$head5 = "Instituições:".$descr_inst;

/////////////////////////////////////////////////////////

$sele_work = " w.o58_codigo = $recurso and w.o58_instit in $instits ";

$completo = false;

$nivela = 8; 
$sql_dotacao = db_dotacaosaldo($nivela,2,2,true,$sele_work,$anousu,$dataini,$datafin,8,0,true);
 
pg_query("create temporary table prev_desp as ".$sql_dotacao);

$sql = "
        select
          $sele,
          sum(atual) as atual,
          sum(jan) as djan,
          sum(fev) as dfev,
          sum(mar) as dmar,
          sum(abr) as dabr,
          sum(mai) as dmai,
          sum(jun) as djun,
          sum(jul) as djul,
          sum(ago) as dago,
          sum(set) as dset,
          sum(out) as dout,
          sum(nov) as dnov,
          sum(dez) as ddez
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
";
//echo $sql;exit;
$resultdesp = pg_query($sql); 
 
pg_exec("commit");
pg_query("drop table prev_desp");

if(pg_numrows($resultdesp)==0){
  continue;
}


//////// dados da receita

$dbwhere = "";

if(trim($recurso) != ''){
  $dbwhere = ' and o15_codigo = '.$recurso;
}






$sql = 
"
select * from (
select o70_codigo as codigo , o15_descr as descr,
       max(jan) as jan, 
       max(fev) as fev, 
       max(mar) as mar, 
       max(abr) as abr, 
       max(mai) as mai, 
       max(jun) as jun, 
       max(jul) as jul, 
       max(ago) as ago, 
       max(set) as set, 
       max(out) as out, 
       max(nov) as nov, 
       max(dez) as dez
from
   (
   select o34_codrec, 
       o34_anousu,
       case when o34_mes = 1  then round(o34_valor,2) else 0 end as jan,
       case when o34_mes = 2  then round(o34_valor,2) else 0 end as fev,
       case when o34_mes = 3  then round(o34_valor,2) else 0 end as mar,
       case when o34_mes = 4  then round(o34_valor,2) else 0 end as abr,
       case when o34_mes = 5  then round(o34_valor,2) else 0 end as mai,
       case when o34_mes = 6  then round(o34_valor,2) else 0 end as jun,
       case when o34_mes = 7  then round(o34_valor,2) else 0 end as jul,
       case when o34_mes = 8  then round(o34_valor,2) else 0 end as ago,
       case when o34_mes = 9  then round(o34_valor,2) else 0 end as set,
       case when o34_mes = 10 then round(o34_valor,2) else 0 end as out,
       case when o34_mes = 11 then round(o34_valor,2) else 0 end as nov,
       case when o34_mes = 12 then round(o34_valor,2) else 0 end as dez
   from orcprevrec 
   where o34_anousu = $ano
   ) as x
   left join  orcreceita on o34_codrec = o70_codrec and o34_anousu = o70_anousu
   inner join orcfontes  on o70_codfon = o57_codfon and o70_anousu = o57_anousu
   inner join orctiporec on o15_codigo = o70_codigo
where o70_codigo = $recurso 
  $dbwhere
group by o70_codigo,o15_descr

) as x

";

//echo $sql ; exit;

$result = pg_exec($sql);


$xxnum = pg_numrows($result);
if ($xxnum == 0){
  continue;
}

$x = 0;

   db_fieldsmemory($result,$x);

   if ($pdf->gety() > $pdf->h - 30 || $tiporec == 0 ){



      $pdf->addpage("L");

   }

     $pdf->cell(20,$alt,'RECURSO: '.$codigo,0,0,"L",0);
     $pdf->cell(60,$alt,$descr,0,1,"L",0);

      $pdf->setfont('arial','b',8);
      $pdf->cell(25,$alt,'TIPO',1,0,"C",1);

      if($mensal == 'm'){
        $pdf->cell(15,$alt,'JANEIRO',1,0,"C",1);
        $pdf->cell(15,$alt,'FEVEREIRO',1,0,"C",1);
        $pdf->cell(15,$alt,'MAR?O',1,0,"C",1);
        $pdf->cell(15,$alt,'ABRIL',1,0,"C",1);
        $pdf->cell(15,$alt,'MAIO',1,0,"C",1);
        $pdf->cell(15,$alt,'JUNHO',1,0,"C",1);
        $pdf->cell(15,$alt,'JULHO',1,0,"C",1);
        $pdf->cell(15,$alt,'AGOSTO',1,0,"C",1);
        $pdf->cell(15,$alt,'SETEMBRO',1,0,"C",1);
        $pdf->cell(15,$alt,'OUTUBRO',1,0,"C",1);
        $pdf->cell(15,$alt,'NOVEMBRO',1,0,"C",1);
        $pdf->cell(15,$alt,'DEZEMBRO',1,0,"C",1);
        $pdf->cell(15,$alt,'TOTAL',1,0,"C",1);
        $pdf->cell(15,$alt,'OR?ADO',1,1,"C",1);
      }else{
      	$pdf->cell(25,$alt,'1o. BIMESTRE',1,0,"C",0);
      	$pdf->cell(10,$alt,' %  ',1,0,"C",0);
        $pdf->cell(25,$alt,'2o. BIMESTRE',1,0,"C",0);
      	$pdf->cell(10,$alt,' %  ',1,0,"C",0);
        $pdf->cell(25,$alt,'3o. BIMESTRE',1,0,"C",0);
      	$pdf->cell(10,$alt,' %  ',1,0,"C",0);
        $pdf->cell(25,$alt,'4o. BIMESTRE',1,0,"C",0);
      	$pdf->cell(10,$alt,' %  ',1,0,"C",0);
        $pdf->cell(25,$alt,'5o. BIMESTRE',1,0,"C",0);
      	$pdf->cell(10,$alt,' %  ',1,0,"C",0);
        $pdf->cell(25,$alt,'6o. BIMESTRE',1,0,"C",0);
      	$pdf->cell(10,$alt,' %  ',1,0,"C",0);
        $pdf->cell(25,$alt,'TOTAL',1,1,"C",0);
      }
     $pre = 0;

   $total = 0;
   $bim_t = 0;
   $total = $jan+$fev+$mar+$abr+$mai+$jun+$jul+$ago+$set+$out+$nov+$dez;
   $bim_t = $total;
   $pdf->setfont('arial','',8);
   if($mensal == 'm'){
     $pdf->cell(25,$alt,'Receita',0,0,"L",$pre);
     $pdf->cell(15,$alt,db_formatar($jan,'f'  ),0,0,"R",$pre);
     $pdf->cell(15,$alt,db_formatar($fev,'f'  ),0,0,"R",$pre);
     $pdf->cell(15,$alt,db_formatar($mar,'f'  ),0,0,"R",$pre);
     $pdf->cell(15,$alt,db_formatar($abr,'f'  ),0,0,"R",$pre);
     $pdf->cell(15,$alt,db_formatar($mai,'f'  ),0,0,"R",$pre);
     $pdf->cell(15,$alt,db_formatar($jun,'f'  ),0,0,"R",$pre);
     $pdf->cell(15,$alt,db_formatar($jul,'f'  ),0,0,"R",$pre);
     $pdf->cell(15,$alt,db_formatar($ago,'f'  ),0,0,"R",$pre);
     $pdf->cell(15,$alt,db_formatar($set,'f'  ),0,0,"R",$pre);
     $pdf->cell(15,$alt,db_formatar($out,'f'  ),0,0,"R",$pre);
     $pdf->cell(15,$alt,db_formatar($nov,'f'  ),0,0,"R",$pre);
     $pdf->cell(15,$alt,db_formatar($dez,'f'  ),0,0,"R",$pre);
     $pdf->cell(15,$alt,db_formatar($total,'f'),0,0,"R",$pre);
     $pdf->cell(15,$alt,db_formatar($o70_valor,'f'),0,1,"R",$pre);
   }else{
     $pdf->cell(25,$alt,'Receita',0,0,"L",$pre);
     $pdf->cell(25,$alt,db_formatar($jan+$fev,'f'  ),0,0,"R",$pre);
     $pdf->cell(10,$alt,db_formatar((($jan+$fev)*100)/($bim_t==0?100:$bim_t),'f'),0,0,"R",$pre);
     $pdf->cell(25,$alt,db_formatar($mar+$abr,'f'  ),0,0,"R",$pre);
     $pdf->cell(10,$alt,db_formatar((($mar+$abr)*100)/($bim_t==0?100:$bim_t),'f'),0,0,"R",$pre);
     $pdf->cell(25,$alt,db_formatar($mai+$jun,'f'  ),0,0,"R",$pre);
     $pdf->cell(10,$alt,db_formatar((($mai+$jun)*100)/($bim_t==0?100:$bim_t),'f'),0,0,"R",$pre);
     $pdf->cell(25,$alt,db_formatar($jul+$ago,'f'  ),0,0,"R",$pre);
     $pdf->cell(10,$alt,db_formatar((($jul+$ago)*100)/($bim_t==0?100:$bim_t),'f'),0,0,"R",$pre);
     $pdf->cell(25,$alt,db_formatar($set+$out,'f'  ),0,0,"R",$pre);
     $pdf->cell(10,$alt,db_formatar((($set+$out)*100)/($bim_t==0?100:$bim_t),'f'),0,0,"R",$pre);
     $pdf->cell(25,$alt,db_formatar($nov+$dez,'f'  ),0,0,"R",$pre);
     $pdf->cell(10,$alt,db_formatar((($nov+$dez)*100)/($bim_t==0?100:$bim_t),'f'),0,0,"R",$pre);
     $pdf->cell(25,$alt,db_formatar($bim_t,'f'  ),0,1,"R",$pre);
     
   }

// despesa

  $x = 0;
   db_fieldsmemory($resultdesp,$x);

   if ($pdf->gety() > $pdf->h - 30 ){
      $pdf->addpage("L");
   }
   if($pre == 1)
     $pre = 0;
   else
     $pre = 1;
     
   $totald = 0;
   $bim_td = 0;
   $totald = $djan+$dfev+$dmar+$dabr+$dmai+$djun+$djul+$dago+$dset+$dout+$dnov+$ddez;
   $bim_td = $totald;
   $pdf->setfont('arial','',8);
   if($mensal == 'm'){
     $pdf->cell(25,$alt,'Despesa',0,0,"L",$pre);
     $pdf->cell(15,$alt,db_formatar($djan,'f'  ),0,0,"R",$pre);
     $pdf->cell(15,$alt,db_formatar($dfev,'f'  ),0,0,"R",$pre);
     $pdf->cell(15,$alt,db_formatar($dmar,'f'  ),0,0,"R",$pre);
     $pdf->cell(15,$alt,db_formatar($dabr,'f'  ),0,0,"R",$pre);
     $pdf->cell(15,$alt,db_formatar($dmai,'f'  ),0,0,"R",$pre);
     $pdf->cell(15,$alt,db_formatar($djun,'f'  ),0,0,"R",$pre);
     $pdf->cell(15,$alt,db_formatar($djul,'f'  ),0,0,"R",$pre);
     $pdf->cell(15,$alt,db_formatar($dago,'f'  ),0,0,"R",$pre);
     $pdf->cell(15,$alt,db_formatar($dset,'f'  ),0,0,"R",$pre);
     $pdf->cell(15,$alt,db_formatar($dout,'f'  ),0,0,"R",$pre);
     $pdf->cell(15,$alt,db_formatar($dnov,'f'  ),0,0,"R",$pre);
     $pdf->cell(15,$alt,db_formatar($ddez,'f'  ),0,0,"R",$pre);
     $pdf->cell(15,$alt,db_formatar($totald,'f'),0,0,"R",$pre);
     $pdf->cell(15,$alt,db_formatar($o70_valor,'f'),0,1,"R",$pre);
   }else{
     $pdf->cell(25,$alt,'Despesa',0,0,"L",$pre);
     $pdf->cell(25,$alt,db_formatar($djan+$dfev,'f'  ),0,0,"R",$pre);
     $pdf->cell(10,$alt,db_formatar((($djan+$dfev)*100)/($bim_td==0?100:$bim_td),'f'),0,0,"R",$pre);
     $pdf->cell(25,$alt,db_formatar($dmar+$dabr,'f'  ),0,0,"R",$pre);
     $pdf->cell(10,$alt,db_formatar((($dmar+$dabr)*100)/($bim_td==0?100:$bim_td),'f'),0,0,"R",$pre);
     $pdf->cell(25,$alt,db_formatar($dmai+$djun,'f'  ),0,0,"R",$pre);
     $pdf->cell(10,$alt,db_formatar((($dmai+$djun)*100)/($bim_td==0?100:$bim_td),'f'),0,0,"R",$pre);
     $pdf->cell(25,$alt,db_formatar($djul+$dago,'f'  ),0,0,"R",$pre);
     $pdf->cell(10,$alt,db_formatar((($djul+$dago)*100)/($bim_td==0?100:$bim_td),'f'),0,0,"R",$pre);
     $pdf->cell(25,$alt,db_formatar($dset+$dout,'f'  ),0,0,"R",$pre);
     $pdf->cell(10,$alt,db_formatar((($dset+$dout)*100)/($bim_td==0?100:$bim_td),'f'),0,0,"R",$pre);
     $pdf->cell(25,$alt,db_formatar($dnov+$ddez,'f'  ),0,0,"R",$pre);
     $pdf->cell(10,$alt,db_formatar((($dnov+$ddez)*100)/($bim_td==0?100:$bim_td),'f'),0,0,"R",$pre);
     $pdf->cell(25,$alt,db_formatar($bim_td,'f'  ),0,1,"R",$pre);
     
   }


if($pre == 1)
  $pre = 0;
else
  $pre = 1;

$pdf->cell(25,$alt,"DIFERENÇA ",0,0,"l",$pre);
if($mensal == 'm'){
	
  $pdf->cell(15,$alt,db_formatar($jan-$djan,'f'  ),0,0,"R",$pre);
  $pdf->cell(15,$alt,db_formatar($fev-$fev,'f'  ),0,0,"R",$pre);
  $pdf->cell(15,$alt,db_formatar($mar-$mar,'f'  ),0,0,"R",$pre);
  $pdf->cell(15,$alt,db_formatar($abr-$abr,'f'  ),0,0,"R",$pre);
  $pdf->cell(15,$alt,db_formatar($mai-$mai,'f'  ),0,0,"R",$pre);
  $pdf->cell(15,$alt,db_formatar($jun-$jun,'f'  ),0,0,"R",$pre);
  $pdf->cell(15,$alt,db_formatar($jul-$jul,'f'  ),0,0,"R",$pre);
  $pdf->cell(15,$alt,db_formatar($ago-$ago,'f'  ),0,0,"R",$pre);
  $pdf->cell(15,$alt,db_formatar($set-$set,'f'  ),0,0,"R",$pre);
  $pdf->cell(15,$alt,db_formatar($out-$out,'f'  ),0,0,"R",$pre);
  $pdf->cell(15,$alt,db_formatar($nov-$nov,'f'  ),0,0,"R",$pre);
  $pdf->cell(15,$alt,db_formatar($dez-$dez,'f'  ),0,0,"R",$pre);
  $pdf->cell(15,$alt,db_formatar($total-$totald,'f'),0,0,"R",$pre);
  $pdf->cell(15,$alt,db_formatar($orcad,'f'),0,1,"R",$pre);
  
}else{
	
  $pdf->cell(25,$alt,db_formatar(($jan+$fev)-($djan+$dfev),'f'  ),0,0,"R",$pre);
  $pdf->cell(10,$alt,'',0,0,"R",$pre);
  $pdf->cell(25,$alt,db_formatar(($mar+$abr)-($dmar+$dabr),'f'  ),0,0,"R",$pre);
  $pdf->cell(10,$alt,'',0,0,"R",$pre);
  $pdf->cell(25,$alt,db_formatar(($mai+$jun)-($dmai+$djun),'f'  ),0,0,"R",$pre);
  $pdf->cell(10,$alt,'',0,0,"R",$pre);
  $pdf->cell(25,$alt,db_formatar(($jul+$ago)-($djul+$dago),'f'  ),0,0,"R",$pre);
  $pdf->cell(10,$alt,'',0,0,"R",$pre);
  $pdf->cell(25,$alt,db_formatar(($set+$out)-($dset+$dout),'f'  ),0,0,"R",$pre);
  $pdf->cell(10,$alt,'',0,0,"R",$pre);
  $pdf->cell(25,$alt,db_formatar(($nov+$dez)-($dnov+$ddez),'f'  ),0,0,"R",$pre);
  $pdf->cell(10,$alt,'',0,0,"R",$pre);
  $pdf->cell(25,$alt,'',0,1,"R",$pre);

}

//$pdf->setfont('arial','b',8);
//$pdf->cell(80,$alt,'TOTAL DO BANCO',"T",0,"C",0);
//$pdf->cell(20,$alt,'',"T",0,"C",0);
 $pdf->cell(10,6,'',0,1,"R",0);

 if($imprime_grafico=='S'){
 
 
 
		$pdf->SetFont('Arial', 'BI', 15);

       if($mensal == 'm'){
		
		$pdf->Cell(0, 5, 'Previsão Mensal de Despesa/Receita', 0, 1, "C", 0);

		$pdf->SetFont('Arial', 'BIU', 10);

		
		$meses   = array("Desp Jan"=>$djan,
		                 "Rec  Jan"=>$jan, 
                         "Desp Fev"=>$dfev,
		                 "Rec  Fev"=>$fev,
                         "Desp Mar"=>$dmar,
		                 "Rec  Mar"=>$mar,
                         "Desp Abr"=>$dabr,
		                 "Rec  Abr"=>$abr,
                         "Desp Mai"=>$dmai,
		                 "Rec  Mai"=>$mai,
                         "Desp Jun"=>$djun,
		                 "Rec  Jun"=>$jun,
                         "Desp Jul"=>$djul,
		                 "Rec  Jul"=>$jul,
                         "Desp Ago"=>$dago,
		                 "Rec  Ago"=>$ago,
                         "Desp Set"=>$dset,
		                 "Rec  Set"=>$set,
                         "Desp Out"=>$dout,
		                 "Rec  Out"=>$out,
                         "Desp Nov"=>$dnov,
		                 "Rec  Nov"=>$nov,
                         "Desp Dez"=>$ddez,
		                 "Rec  Dez"=>$dez
		                 );
		
		                 $col1=array(255);
		                 $col2=array(255);
		                 $col3=array(255);
		                 $col4=array(255);
		                 $col5=array(255);
		                 $col6=array(255);
		                 $col7=array(255);
		                 $col8=array(255);
		                 $col9=array(255);
		                 $col10=array(255);
		                 $col11=array(255);
		                 $col12=array(255);

        }else{
        	
        		
		$pdf->Cell(0, 5, 'Previsão Bimestral de Despesa/Receita', 0, 1, "C", 0);

		$pdf->SetFont('Arial', 'BIU', 10);

		
		$meses   = array("Despesa 1 Bim"=>$djan+$dfev,
		                 "Receita  1 Bim"=>$jan+$fev, 
                         "Despesa 2 Bim"=>$dmar+$dabr,
		                 "Receita  2 Bim"=>$mar+$abr,
                         "Despesa 3 Bim"=>$dmai+$djun,
		                 "Receita  3 Bim"=>$mai+$jun,
                         "Despesa 4 Bim"=>$djul+$dago,
		                 "Receita  4 Bim"=>$jul+$ago,
                         "Despesa 5 Bim"=>$dset+$dout,
		                 "Receita  5 Bim"=>$set+$out,
                         "Despesa 6 Bim"=>$dnov+$ddez,
		                 "Receita  6 Bim"=>$nov+$dez
		                 );
		
		                 $col1=array(255);
		                 $col2=array(255);
		                 $col3=array(255);
		                 $col4=array(255);
		                 $col5=array(255);
		                 $col6=array(255);
        	
        }



		$pdf->SetFont('Arial', '', 6);
		$valX = $pdf->GetX();
		$valY = $pdf->GetY();
		$pdf->SetXY(10, $valY +10);
		




		$pdf->BarDiagram(200, 120, $meses, '%l',array(235,205) );
     
     if($cltiporec->numrows>1){
     	
       $pdf->addpage("L");
     }
 
 
 
  }


}

$pdf->Output();
   
?>
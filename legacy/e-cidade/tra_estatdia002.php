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
 require ("libs/jpgraph/jpgraph.php");
 require ("libs/jpgraph/jpgraph_line.php");
 //$rs = pg_exec($sql);
 $pdf = new pdf();
 $grafico = new graph(800,400,"png");
 $head3 = 'Resumo dos acidentes Em sapiranga';
 $head4 = 'Acidentos por Dia da Semana';
 $pdf->open();
 $pdf->addpage();
 $pdf->aliasNbPages();
 $pdf->setx(10);
 $pdf->setfillcolor(204);
 $pdf->cell(75,5,"DIAS DA SEMANA",1,1,"C",1);
 //$pdf->setxy(10)
 $pdf->cell(50,5,"DOMINGO",1,0,"L");
 $pdf->cell(25,5,"DOM",1,1,"C");
 $pdf->cell(50,5,"SEGUNDA-FEIRA",1,0,"L");
 $pdf->cell(25,5,"2ª",1,1,"C");
 $pdf->cell(50,5,"TERÇA-FEIRA",1,0,"L");
 $pdf->cell(25,5,"3ª",1,1,"C");
 $pdf->cell(50,5,"QUARTA-FEIRA",1,0,"L");
 $pdf->cell(25,5,"4ª",1,1,"C");
 $pdf->cell(50,5,"QUINTA-FEIRA",1,0,"L");
 $pdf->cell(25,5,"5ª",1,1,"C");
 $pdf->cell(50,5,"SEXTA-FEIRA",1,0,"L");
 $pdf->cell(25,5,"6ª",1,1,"C");
 $pdf->cell(50,5,"SÁBADO",1,0,"L");
 $pdf->cell(25,5,"SAB",1,1,"C");
 $pdf->cell(75,5,"Total",1,1,"L");

 $x = 0;
 $sum   = 0;
 for ($i = 1; $i <= 12 ;$i++){
      $pdf->setxy(85+$x,35);
      $pdf->cell(10,5,$i,1,1,"C",1);
     // $rs = pg_exec($sql);
     /* $numrows = pg_num_rows($rs);
     // $ds = 0;
     // while($ln = pg_fetch_array($rs)){
           $pdf->setx(85+$x);
           $pdf->cell(10,5,$ln["quantidade"],1,1,"C");
      }*/
      $resta = 8;
      $sum   = 0;
      for ($k = 0; $k < $resta ;$k++){
          $pdf->setx(85+$x);
          $sql2 = "select extract(dow from tr07_data) as dia_semana,
                        count(*) as quantidade
                  from   Acidentes
                  where  (extract(month from tr07_data) = $i
                  and    extract(dow from tr07_data) = $k)
                  and    extract(year from tr07_data) = $ano
                  group by dia_semana order by dia_semana";

          $rs2      = pg_exec($sql2);
          $numrows2 = pg_num_rows($rs2);
          $ln       = pg_fetch_array($rs2);
          $sum +=  $ln["quantidade"];
          if ($numrows2 > 0){
              $pdf->cell(10,5,$ln["quantidade"],1,1,"C");
          }else if ($k == 7){
             $pdf->cell(10,5,$sum,1,1,"C");
          }else{
             $pdf->cell(10,5,"",1,1,"C");
          }
       }
  $x +=10;

  }
/* $sqlg = "SELECT EXTRACt(dow from tr07_data) as dia,
                 extract(month from tr07_data) as mes,
                 count(*) as qt
         from    acidentes
         group by mes,dia";
//echo $sqlg;exit;
$rsg = pg_exec($sqlg);
while ($lng = pg_fetch_array($rsg)){
    if($lng["qt"] == "" ){
       $lng["qt"] = 0;
    }
    $dia[]  = $lng["dia"];
    $qt[]   = $lng["qt"];
    $gBarras = new linePlot($qt);
    $gBarras->SetColor("#FF9933");
    //$gBarras1->SetShadow("yellow");
    $gBarras->SetCenter();
    $grafico->add($gBarras);

 }

/* $sqlm = "select extract(month from tr07_data) as mes,count(*) as total
                 from  acidentes where extract(year from tr07_data) = $ano
           group by mes order by mes";*/
 $sqlm = "select (case when x.mes = 1 then 'Janeiro'
                   when x.mes = 2 then 'Fevereiro'
                   when x.mes = 3 then 'Março'
                   when x.mes = 4 then 'Abril'
                   when x.mes = 5 then 'Maio'
                   when x.mes = 6 then 'Junho'
                   when x.mes = 7 then 'Julho'
                   when x.mes = 8 then 'Agosto'
                   when x.mes = 9 then 'Setembro'
                   when x.mes = 10 then 'Outubro'
                   when x.mes = 11 then 'Novembro'
                   when x.mes = 12 then 'Dezembro'end) as mes,count(*) as total
           from (SELECT extract(month from tr07_data) as mes
                 from  acidentes where extract(year from tr07_data) = $ano) as x
           group by mes order by x.mes";
//echo $sqlm;exit;
$rsm = pg_exec($sqlm);
while ($lnm = pg_fetch_array($rsm)){
      $legendm[] = $lnm["mes"];
      $totalm[]  = $lnm["total"];
 }
$mes = array(  1  => "Janeiro",
               2  => "Fevereiro",
               3  => "Março",
               4  => "Abril",
               5  => "Maio",
               6  => "Junho",
               7  => "Julho",
               8  => "Agosto",
               9  => "Setembro",
               10 => "Outubro",
               11 => "Novembro",
               12 => "Dezembro");


$grafico->img->SetMargin(40,40,40,40);

$grafico->SetScale("textlin");
$grafico->SetShadow();
$grafico->legend->SetFont(FF_ARIAL,FS_NORMAL,8);
$grafico->title->Set('Acidentes por Dia da Semana');
//$grafico->subtitle->Set('SEMEC');
$grafico->ygrid->Show(true);
$gBarras1 = new linePlot($totalm);
$gBarras1->SetColor("#FF9933");
//$gBarras1->SetShadow("yellow");
$gBarras1->SetCenter();
$gBarras1->SetLegend("mes");

$grafico->yaxis->title->Set("Ocorrencias");
$grafico->xaxis->title->Set("Mes");
$grafico->xaxis->SetTickLabels($legendm);
$grafico->add($gBarras1);
$nome = "/tmp/".mt_rand(1,3000).date("Ymd").".png";
$grafico->stroke($nome);
$pdf->image($nome,30,95,150,70);
unlink($nome);
 //exit;

  $pdf->output();
 ?>
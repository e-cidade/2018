<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

set_time_limit(0);
include(modification("libs/db_sql.php"));
require(modification("fpdf151/pdf.php"));
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);
if (isset($campo)){
  $empr = "and b.k00_inscr in (".str_replace("-",",",$campo).") ";
}else{
  $empr = '';
}
$mesini = substr($datai,5,2);
$anoini = substr($datai,0,4);
$mesfin = substr($dataf,5,2);
$anofin = substr($dataf,0,4);
 for ($x = 1; $x < 13;$x++){
   if ($anoini.db_formatar($mesini,'s','0',2,'e') <= $anofin.$mesfin){
//   echo 'anoini : '.$anoini.$mesini.'<br>';
//   echo 'mesfin : '.$anofin.$mesfin.'<br>';
      @$xcase .= " case when mes||'/'||ano = '".db_formatar($mesini,'s','0',2,'e')."/".$anoini."' then valor  else 0 end as xx".$x;
      @$xanofin = $anoini;
      @$xmesfin = $mesini;
      @$xmax  .= " max(xx".$x.") as xx".$x;
   }else{
      @$xmax  .= " 0 as xx".$x;
   }
   $mesini += 1;
   if ($mesini > 12){
      $anoini += 1;
      $mesini = 1;
   }
   if ($x < 12){
     if ($anoini.db_formatar($mesini,'s','0',2,'e') <= $anofin.$mesfin)
        $xcase .= ", ";
     $xmax  .= ", ";
   }  
 }

$ordenacao = "";

if ($ordem == "i") {
  $ordenacao = " order by k00_inscr";
} elseif ($ordem == "n") {
  $ordenacao = " order by z01_nome";
} elseif ($ordem == "t") {
  $ordenacao = " order by (xx1 + xx2 + xx3 + xx4 + xx5 + xx6 + xx7 + xx8 + xx9 + xx10 + xx11 + xx12)";
}

if ($tipoordem == "a") {
  $ordenacao .= " asc";
} elseif ($tipoordem == "d") {
  $ordenacao .= " desc";
}

if ($tipo == 'c'){
   $head7 = 'COMPETÊNCIA';
   $sql = "
   select k00_inscr,z01_nome, $xmax
   from
   (select k00_inscr, z01_nome, $xcase
   from        
     (select b.k00_inscr,z01_nome,
             q05_ano as ano,lpad(q05_mes,2,'0') as mes,
             sum(c.k00_valor) as valor 
     from arreinscr b
          inner join issbase d on d.q02_inscr = b.k00_inscr
          inner join cgm on cgm.z01_numcgm = d.q02_numcgm
          inner join arrepaga c on c.k00_numpre = b.k00_numpre 
          inner join issvar as a on a.q05_numpre = c.k00_numpre and a.q05_numpar = c.k00_numpar  
     where q05_ano||lpad(q05_mes,2,'0') between '".substr($datai,0,4).substr($datai,5,2)."' 
                                            and '".substr($dataf,0,4).substr($dataf,5,2)."' 
           $empr
      and not exists(
        select 1 from abatimentoutilizacaodestino
          inner join abatimentoutilizacao on k170_utilizacao = k157_sequencial
          inner join abatimento on k157_abatimento = k125_sequencial
        where k170_numpre = c.k00_numpre
          and k170_numpar = c.k00_numpar
          and k125_tipoabatimento = 3
        limit 1
       )
     group by b.k00_inscr,z01_nome,ano,mes 
   order by b.k00_inscr,z01_nome,ano,mes ) as x ) as y group by k00_inscr,z01_nome";

   $sql = "select * from ($sql) as z $ordenacao";

}else{
   $head7 = 'PAGAMENTO';
   $sql = "
   select k00_inscr,z01_nome, $xmax
   from
   (select k00_inscr, z01_nome, $xcase
     
    from        
   (select b.k00_inscr,z01_nome,
          date_part('year',c.k00_dtpaga) as ano,lpad(date_part('month',c.k00_dtpaga),2,'0') as mes,
          sum(c.k00_valor) as valor 
   from arreinscr b
          inner join issbase d on d.q02_inscr = b.k00_inscr
          inner join cgm on cgm.z01_numcgm = d.q02_numcgm
        inner join arrepaga c on c.k00_numpre = b.k00_numpre 
        inner join (select distinct q05_numpre 
                    from issvar) as a on a.q05_numpre = c.k00_numpre  
   where 
     c.k00_dtpaga between '$datai' and '$dataf' 
     $empr
     and not exists(
      select 1 from abatimentoutilizacaodestino
        inner join abatimentoutilizacao on k170_utilizacao = k157_sequencial
        inner join abatimento on k157_abatimento = k125_sequencial
      where k170_numpre = c.k00_numpre
        and k170_numpar = c.k00_numpar
        and k125_tipoabatimento = 3
      limit 1
     )
   group by b.k00_inscr,z01_nome,ano,mes 
   order by b.k00_inscr,z01_nome,ano,mes ) as x ) as y group by k00_inscr,z01_nome";

   $sql = "select * from ($sql) as z $ordenacao";

}
//echo $sql;exit;
$result = db_query($sql) or die($sql);
//db_criatabela($result);
$num = pg_numrows($result);
if ($num == 0 ){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existe pagamentos efetuados no período de '.db_formatar($datai,'d').' até '.db_formatar($dataf,'d'));
}

$pdf = new pdf();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setleftmargin(5);
$mesini = substr($datai,5,2);
$anoini = substr($datai,0,4);
$mesfin = substr($dataf,5,2);
$anofin = substr($dataf,0,4);
$head2 = "RELATÓRIO DOS PAGAMENTOS";
$head3 = "ISSQN VARIÁVEL";
$head5 = "PERÍODO DE : ".$mesini."/".$anoini." A ".db_formatar($xmesfin,'s','0',2,'e')."/".$xanofin ;
$pdf->addpage('L');
$pdf->SetFillColor(220);
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Arial','B',8);
$espaco     = 17;
$altura     = 4.5;
$totalxx1   = 0;
$totalxx2   = 0;
$totalxx3   = 0;
$totalxx4   = 0;
$totalxx5   = 0;
$totalxx6   = 0;
$totalxx7   = 0;
$totalxx8   = 0;
$totalxx9   = 0;
$totalxx10  = 0;
$totalxx11  = 0;
$totalxx12  = 0;
$totalgeral = 0;
$pdf->cell(10,$altura,'Inscr.',1,0,'C',1);
$pdf->cell(50,$altura,'Nome/Razão Social',1,0,'C',1);
for ($x = 1; $x < 13;$x++){
   if ($x < 12){
    $pdf->cell($espaco,$altura,db_formatar($mesini,'s','0',2,'e').'/'.$anoini,1,0,'C',1);
   }else{
    $pdf->cell($espaco,$altura,db_formatar($mesini,'s','0',2,'e').'/'.$anoini,1,0,'C',1);
   }
   
   $mesini += 1;
   if ($mesini > 12){
      $anoini += 1;
      $mesini = 1;
   }
 }
 $pdf->cell(20,$altura,'Total',1,1,'C',1);
for ( $i = 0; $i < $num; $i++) {
   db_fieldsmemory($result,$i);
   if ( $pdf->gety() > $pdf->h - 30 ) {
      $pdf->addpage('L');
      $pdf->SetFont('Arial','B',6);
      $pdf->cell(10,$altura,'Inscr.',1,0,'C',1);
      $pdf->cell(50,$altura,'Nome/Razão Social',1,0,'C',1);
      $mesini = substr($datai,5,2);
      $anoini = substr($datai,0,4);
      $mesfin = substr($dataf,5,2);
      $anofin = substr($dataf,0,4);
      for ($x = 1; $x < 13;$x++){
         if ($x < 12){
            $pdf->cell($espaco,$altura,db_formatar($mesini,'s','0',2,'e').'/'.$anoini,1,0,'C',1);
         }else{
            $pdf->cell($espaco,$altura,db_formatar($mesini,'s','0',2,'e').'/'.$anoini,1,0,'C',1);
         }
         $mesini += 1;
         if ($mesini > 12){
            $anoini += 1;
            $mesini = 1;
         }
      }
      $pdf->cell(20,$altura,'Total',1,1,'C',1);
   }
   $pdf->SetFont('Arial','',6);
   $total = $xx1+$xx2+$xx3+$xx4+$xx5+$xx6+$xx7+$xx8+$xx9+$xx10+$xx11+$xx12;
   if ($total > 0){
      $pdf->cell(10,$altura,$k00_inscr,1,0,'C',0);
      $pdf->cell(50,$altura,substr($z01_nome,0,35),1,0,'L',0);
      $pdf->cell($espaco,$altura,db_formatar($xx1,'f'),1,0,'R',0);
      $pdf->cell($espaco,$altura,db_formatar($xx2,'f'),1,0,'R',0);
      $pdf->cell($espaco,$altura,db_formatar($xx3,'f'),1,0,'R',0);
      $pdf->cell($espaco,$altura,db_formatar($xx4,'f'),1,0,'R',0);
      $pdf->cell($espaco,$altura,db_formatar($xx5,'f'),1,0,'R',0);
      $pdf->cell($espaco,$altura,db_formatar($xx6,'f'),1,0,'R',0);
      $pdf->cell($espaco,$altura,db_formatar($xx7,'f'),1,0,'R',0);
      $pdf->cell($espaco,$altura,db_formatar($xx8,'f'),1,0,'R',0);
      $pdf->cell($espaco,$altura,db_formatar($xx9,'f'),1,0,'R',0);
      $pdf->cell($espaco,$altura,db_formatar($xx10,'f'),1,0,'R',0);
      $pdf->cell($espaco,$altura,db_formatar($xx11,'f'),1,0,'R',0);
      $pdf->cell($espaco,$altura,db_formatar($xx12,'f'),1,0,'R',0);
      $pdf->cell(20,$altura,db_formatar($total,'f'),1,1,'R',0);
      $totalxx1   += $xx1;
      $totalxx2   += $xx2;
      $totalxx3   += $xx3;
      $totalxx4   += $xx4;
      $totalxx5   += $xx5;
      $totalxx6   += $xx6;
      $totalxx7   += $xx7;
      $totalxx8   += $xx8;
      $totalxx9   += $xx9;
      $totalxx10  += $xx10;
      $totalxx11  += $xx11;
      $totalxx12  += $xx12;
      $totalgeral += $total;
   }
}
$pdf->cell(60,$altura,'Total',1,0,'C',0);
$pdf->cell($espaco,$altura,db_formatar($totalxx1,'f'),1,0,'R',0);
$pdf->cell($espaco,$altura,db_formatar($totalxx2,'f'),1,0,'R',0);
$pdf->cell($espaco,$altura,db_formatar($totalxx3,'f'),1,0,'R',0);
$pdf->cell($espaco,$altura,db_formatar($totalxx4,'f'),1,0,'R',0);
$pdf->cell($espaco,$altura,db_formatar($totalxx5,'f'),1,0,'R',0);
$pdf->cell($espaco,$altura,db_formatar($totalxx6,'f'),1,0,'R',0);
$pdf->cell($espaco,$altura,db_formatar($totalxx7,'f'),1,0,'R',0);
$pdf->cell($espaco,$altura,db_formatar($totalxx8,'f'),1,0,'R',0);
$pdf->cell($espaco,$altura,db_formatar($totalxx9,'f'),1,0,'R',0);
$pdf->cell($espaco,$altura,db_formatar($totalxx10,'f'),1,0,'R',0);
$pdf->cell($espaco,$altura,db_formatar($totalxx11,'f'),1,0,'R',0);
$pdf->cell($espaco,$altura,db_formatar($totalxx12,'f'),1,0,'R',0);
$pdf->cell(20,$altura,db_formatar($totalgeral,'f'),1,1,'R',0);


$pdf->Output();
?>
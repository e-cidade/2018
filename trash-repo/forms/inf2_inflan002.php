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

set_time_limit(0);
include("libs/db_sql.php");
require("fpdf151/pdf.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$sql = "select * from inflan where upper(i01_codigo) = upper('$codigo')";
$result = pg_exec($sql);
db_fieldsmemory($result,0);

$pdf = new pdf();
$pdf->Open();
$pdf->AliasNbPages();
$head2 = "SECRETARIA DA FAZENDA";
$head4 = "Relat�rio de ".$i01_codigo;
$head5 = $i01_descr;
$head7 = "Per�odo de ".$exercicioi." at� ".$exerciciof;
//$pdf->addpage();
$pdf->SetFillColor(220);
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Arial','B',8);
if ( $i01_dm == 0 ){
$xtipo = "Ano";
//echo 
$sql = "
select exerc as tipo,
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
(select substr(i02_data,1,4) as exerc ,
       case when substr(i02_data,6,2) = '01' then i02_valor  else 0 end as jan,
       case when substr(i02_data,6,2) = '02' then i02_valor  else 0 end as fev,
       case when substr(i02_data,6,2) = '03' then i02_valor  else 0 end as mar,
       case when substr(i02_data,6,2) = '04' then i02_valor  else 0 end as abr,
       case when substr(i02_data,6,2) = '05' then i02_valor  else 0 end as mai,
       case when substr(i02_data,6,2) = '06' then i02_valor  else 0 end as jun,
       case when substr(i02_data,6,2) = '07' then i02_valor  else 0 end as jul,
       case when substr(i02_data,6,2) = '08' then i02_valor  else 0 end as ago,
       case when substr(i02_data,6,2) = '09' then i02_valor  else 0 end as set,
       case when substr(i02_data,6,2) = '10' then i02_valor  else 0 end as out,
       case when substr(i02_data,6,2) = '11' then i02_valor  else 0 end as nov,
       case when substr(i02_data,6,2) = '12' then i02_valor  else 0 end as dez
from infla
where i02_codigo = '$codigo'
  and substr(i02_data,1,4) between '$exercicioi' and '$exerciciof'
order by exerc ) as x
group by tipo
";
//exit;
}else{
$xtipo = "Dia";

$sql = "
select exerc,dia as tipo,
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
(select substr(i02_data,1,4) as exerc ,substr(i02_data,9,2) as dia,
       case when substr(i02_data,6,2) = '01' then i02_valor  else 0 end as jan,
       case when substr(i02_data,6,2) = '02' then i02_valor  else 0 end as fev,
       case when substr(i02_data,6,2) = '03' then i02_valor  else 0 end as mar,
       case when substr(i02_data,6,2) = '04' then i02_valor  else 0 end as abr,
       case when substr(i02_data,6,2) = '05' then i02_valor  else 0 end as mai,
       case when substr(i02_data,6,2) = '06' then i02_valor  else 0 end as jun,
       case when substr(i02_data,6,2) = '07' then i02_valor  else 0 end as jul,
       case when substr(i02_data,6,2) = '08' then i02_valor  else 0 end as ago,
       case when substr(i02_data,6,2) = '09' then i02_valor  else 0 end as set,
       case when substr(i02_data,6,2) = '10' then i02_valor  else 0 end as out,
       case when substr(i02_data,6,2) = '11' then i02_valor  else 0 end as nov,
       case when substr(i02_data,6,2) = '12' then i02_valor  else 0 end as dez
from infla
where i02_codigo = '$codigo'
  and substr(i02_data,1,4) between '$exercicioi' and '$exerciciof'
order by exerc,dia) as x
group by exerc,tipo
";

}
$result = pg_exec($sql);
$num = pg_numrows($result);
if ($num == 0 ){
   db_redireciona('db_erros.php?fechar=true&db_erro=N�o existe valores do inflator '.$codigo.' para o per�odo '.$exercicioi.'/'.$exerciciof);
}

$espaco = 21; 
$altura = 4.5;
$linha = 60;
$xexerc = '';
$exerc = '';
if ($i01_dm == 1 ){
   $xexerc = pg_result($result,0,"exerc");
}

for ( $i = 0; $i < $num; $i++) {
   db_fieldsmemory($result,$i);
   if ( $xexerc != $exerc ){
      $linha = 50;
   }
   if ( $linha > 40 ) {
      $linha = 0;
      $pdf->addpage('L');
      if ($i01_dm == 1){
         $pdf->SetFont('Arial','B',9);
         $pdf->cell(100,7,'Exerc�cio : '.$exerc,0,1,"L",0);
      }
      $pdf->SetFont('Arial','B',6);
      $pdf->cell($espaco,$altura,$xtipo,1,0,'C',1);
      $pdf->cell($espaco,$altura,'Janeiro',1,0,'C',1);
      $pdf->cell($espaco,$altura,'Fevereiro',1,0,'C',1);
      $pdf->cell($espaco,$altura,'Mar�o',1,0,'C',1);
      $pdf->cell($espaco,$altura,'Abril',1,0,'C',1);
      $pdf->cell($espaco,$altura,'Maio',1,0,'C',1);
      $pdf->cell($espaco,$altura,'Junho',1,0,'C',1);
      $pdf->cell($espaco,$altura,'Julho',1,0,'C',1);
      $pdf->cell($espaco,$altura,'Agosto',1,0,'C',1);
      $pdf->cell($espaco,$altura,'Setembro',1,0,'C',1);
      $pdf->cell($espaco,$altura,'Outubro',1,0,'C',1);
      $pdf->cell($espaco,$altura,'Novembro',1,0,'C',1);
      $pdf->cell($espaco,$altura,'Dezembro',1,1,'C',1);
   }
   $pdf->SetFont('Arial','',6);
   $pdf->cell($espaco,$altura,$tipo,1,0,'C',0);
   $pdf->cell($espaco,$altura,db_formatar($jan,'f',' ',' ',' ',6),1,0,'R',0);
   $pdf->cell($espaco,$altura,db_formatar($fev,'f',' ',' ',' ',6),1,0,'R',0);
   $pdf->cell($espaco,$altura,db_formatar($mar,'f',' ',' ',' ',6),1,0,'R',0);
   $pdf->cell($espaco,$altura,db_formatar($abr,'f',' ',' ',' ',6),1,0,'R',0);
   $pdf->cell($espaco,$altura,db_formatar($mai,'f',' ',' ',' ',6),1,0,'R',0);
   $pdf->cell($espaco,$altura,db_formatar($jun,'f',' ',' ',' ',6),1,0,'R',0);
   $pdf->cell($espaco,$altura,db_formatar($jul,'f',' ',' ',' ',6),1,0,'R',0);
   $pdf->cell($espaco,$altura,db_formatar($ago,'f',' ',' ',' ',6),1,0,'R',0);
   $pdf->cell($espaco,$altura,db_formatar($set,'f',' ',' ',' ',6),1,0,'R',0);
   $pdf->cell($espaco,$altura,db_formatar($out,'f',' ',' ',' ',6),1,0,'R',0);
   $pdf->cell($espaco,$altura,db_formatar($nov,'f',' ',' ',' ',6),1,0,'R',0);
   $pdf->cell($espaco,$altura,db_formatar($dez,'f',' ',' ',' ',6),1,1,'R',0);
   $linha += 1;
   $xexerc = $exerc;
}



$pdf->Output();
?>
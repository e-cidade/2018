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

$clrotulo = new rotulocampo;
$clrotulo->label('r06_codigo');
$clrotulo->label('r06_descr');
$clrotulo->label('r06_elemen');
$clrotulo->label('r06_pd');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;


$where_ati = '';
$xtipo = 'Todos';
if($ativos != 'i'){
  $xtipo = 'Ativos';
  $where_ati = " and rh27_ativo = '$ativos'";
  if($ativos == 'f'){
    $xtipo = 'Inativos';
  }
}

$head3 = "CADASTRO DE CÓDIGOS";
$head5 = "TIPO : ".$xtipo;

$sql = "
select rh27_rubric,rh27_descr,o56_codele,o56_elemento,o56_descr,rh27_pd, k02_codigo, k02_drecei
from rhrubricas
     left join rhrubelemento        on rh27_rubric = rh23_rubric
		                   and rh27_instit = rh23_instit
     left join rhrubretencao        on rh75_rubric = rh27_rubric
                                   and rh75_instit = rh27_instit
     left join retencaotiporec      on e21_sequencial = rh75_retencaotiporec
     left join tabrec               on k02_codigo = e21_receita
     left join orcelemento          on rh23_codele = o56_codele and o56_anousu = $ano
where rh27_instit = ".db_getsession("DB_instit")."		 
      $where_ati
order by rh27_rubric
       ";
//echo $sql ; exit;

$result = pg_exec($sql);
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
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,$RLr06_codigo,1,0,"C",1);
      $pdf->cell(60,$alt,$RLr06_descr,1,0,"C",1);
      $pdf->cell(20,$alt,$RLr06_pd,1,0,"C",1);
      $pdf->cell(90,$alt,'ELEMENTO / RECEITA',1,1,"C",1);
      $troca = 0;
      $pre = 1;
   }
   if($pre == 1){
     $pre = 0;
   }else{
     $pre = 1;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$rh27_rubric,0,0,"C",$pre);
   $pdf->cell(60,$alt,$rh27_descr,0,0,"L",$pre);
   if ($rh27_pd == 1 && $rh27_pd  != 3 ){
      $pdf->cell(20,$alt,'PROVENTO',0,0,"L",$pre);
   }elseif ($rh27_pd == 2 && $rh27_pd != 3 ){
      $pdf->cell(20,$alt,'DESCONTO',0,0,"L",$pre);
   }else{
      $pdf->cell(20,$alt,'BASE',0,0,"L",$pre);
   }
   if($o56_elemento != ''){
     $pdf->cell(90,$alt,'E - '.$o56_elemento.' - '.$o56_descr,0,1,"L",$pre);
   }elseif($k02_codigo != ''){
     $pdf->cell(90,$alt,'R - '.$k02_codigo.' - '.$k02_drecei,0,1,"L",$pre);
   }else{
     $pdf->cell(90,$alt,'',0,1,"L",$pre);
   }
   $total += 1;
//   $pdf->SetXY($pdf->lMargin,$pdf->gety() + $alt);
}
$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,'TOTAL DE REGISTROS :  '.$total,"T",0,"C",0);
//$pdf->cell(20,$alt,'',"T",0,"C",0);
//$pdf->cell(30,$alt,db_formatar($total,'f'),"T",1,"R",0);

$pdf->Output();
   
?>
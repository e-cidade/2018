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
include("classes/db_pcsubgrupo_classe.php");

$pcsubgrupo = new cl_pcsubgrupo;

$clrotulo = new rotulocampo;
$clrotulo->label('pc04_codsubgrupo');
$clrotulo->label('pc04_descrsubgrupo');
$clrotulo->label('pc04_codgrupo');
$clrotulo->label('pc03_descrgrupo');
$clrotulo->label('pc04_codtipo');
$clrotulo->label('pc05_descr');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

if($grupo == "a") {
  if($ordem == "a") {
    $desc_ordem = "Alfebética";
    $order_by = "pc04_descrsubgrupo";
  }else {
  $desc_ordem = "Numérica";
  $order_by = "pc04_codsubgrupo";
  }
$desc_grupo = "Geral";
}elseif($grupo == "b") {
  if($ordem == "a") {
    $desc_ordem = "Alfebética";
    $order_by = "pc03_descrgrupo";
  }else {
  $desc_ordem = "Numérica";
  $order_by = "pc03_codgrupo";
  }
$desc_grupo = "Por Grupo";
}elseif($grupo == "c") {
  if($ordem == "a") {
    $desc_ordem = "Alfebética";
    $order_by = "pc05_descr";
  }else {
  $desc_ordem = "Numérica";
  $order_by = "pc05_codtipo";
  } 
$desc_grupo = "Por Tipo";  
}else {
    if($ordem == "a") {
    $desc_ordem = "Alfebética";
    $order_by = "pc03_descrgrupo,pc05_descr";
  }else {
  $desc_ordem = "Numérica";
  $order_by = "pc05_codtipo,pc03_codgrupo";
  } 
$desc_grupo = "Por Grupo e Tipo";
}
//echo $grupo;
//echo $desc_grupo;exit;

$head1 = "RELATÓRIO DOS SUB-GRUPOS DE MATERIAIS/SERVIÇOS";
$head3 = "CLASSIFICAÇÃO $desc_grupo";
$head5 = "ORDEM $desc_ordem";

$result = $pcsubgrupo->sql_record($pcsubgrupo->sql_query("","*",$order_by));
//db_criatabela($result);exit;

if ($pcsubgrupo->numrows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem sub-grupos de materiais/serviços cadastrados.');

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total = 0;
$totg = 0;
$quebra_cod = 0;

if($grupo == "a") {
for($x = 0; $x < $pcsubgrupo->numrows;$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage("L");
      $pdf->setfont('arial','b',8);
      $pdf->cell(17,$alt,$RLpc04_codsubgrupo,1,0,"C",1);
      $pdf->cell(60,$alt,$RLpc04_descrsubgrupo,1,0,"C",1);
      $pdf->cell(15,$alt,$RLpc04_codgrupo,1,0,"C",1);
      $pdf->cell(60,$alt,$RLpc03_descrgrupo,1,0,"C",1);
      $pdf->cell(15,$alt,$RLpc04_codtipo,1,0,"C",1);
      $pdf->cell(60,$alt,$RLpc05_descr,1,1,"C",1);
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(17,$alt,$pc04_codsubgrupo,0,0,"C",0);
   $pdf->cell(60,$alt,$pc04_descrsubgrupo,0,0,"L",0);
   $pdf->cell(15,$alt,$pc04_codgrupo,0,0,"C",0);
   $pdf->cell(60,$alt,$pc03_descrgrupo,0,0,"L",0);
   $pdf->cell(15,$alt,$pc04_codtipo,0,0,"C",0);
   $pdf->cell(60,$alt,$pc05_descr,0,1,"L",0);
   $total++;
}

$pdf->setfont('arial','b',8);
$pdf->cell(227,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"L",0);
}else {
for($x = 0; $x < $pcsubgrupo->numrows;$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(17,$alt,$RLpc04_codsubgrupo,1,0,"C",1);
      $pdf->cell(60,$alt,$RLpc04_descrsubgrupo,1,0,"C",1);
      if($grupo != "b") {
      $pdf->cell(15,$alt,$RLpc04_codgrupo,1,0,"C",1);
      $pdf->cell(60,$alt,$RLpc03_descrgrupo,1,1,"C",1);
      }
      if($grupo != "c") {
      $pdf->cell(15,$alt,$RLpc04_codtipo,1,0,"C",1);
      $pdf->cell(60,$alt,$RLpc05_descr,1,1,"C",1);
      }
      $troca = 0;
   }
   if($quebra_cod != $pc04_codgrupo && $grupo == "b") {
     if($totg > 0) {
     $pdf->setfont('arial','b',7);
     $pdf->cell(154,$alt,'TOTAL  :  '.$totg,"B",1,"L",0);
     }
     $pdf->ln(3);
     $pdf->cell(15,$alt,$pc04_codgrupo,0,0,"C",0);
     $pdf->cell(60,$alt,$pc03_descrgrupo,0,1,"L",0);
     $quebra_cod = $pc04_codgrupo;
     $totg = 0;
   }
   if($quebra_cod != $pc04_codtipo && $grupo == "c") {
     if($totg > 0) {
     $pdf->setfont('arial','b',7);
     $pdf->cell(154,$alt,'TOTAL  :  '.$totg,"B",1,"L",0);
     }
     $pdf->setfont('arial','b',9);
     $pdf->ln(3);
     $pdf->cell(15,$alt,$pc04_codtipo,0,0,"C",0);
     $pdf->cell(60,$alt,$pc05_descr,0,1,"L",0);
     $quebra_cod = $pc04_codtipo;
     $totg = 0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$pc04_codsubgrupo,0,0,"C",0);
   $pdf->cell(60,$alt,$pc04_descrsubgrupo,0,0,"L",0);
   if($grupo != "b") {
     $pdf->cell(15,$alt,$pc04_codgrupo,0,0,"C",0);
     $pdf->cell(60,$alt,$pc03_descrgrupo,0,1,"L",0);
   }
   if($grupo != "c") {
     $pdf->cell(15,$alt,$pc04_codtipo,0,0,"C",0);
     $pdf->cell(60,$alt,$pc05_descr,0,1,"L",0);
   }
   $total++;
   $totg++;
}

$pdf->setfont('arial','b',7);
$pdf->cell(154,$alt,'TOTAL  :  '.$totg,"B",1,"L",0);

$pdf->setfont('arial','b',8);
$pdf->cell(150,$alt,'TOTAL GERAL  :  '.$total,"T",0,"L",0);
}

$pdf->Output();
   
?>
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
include("classes/db_pctipo_classe.php");
include("classes/db_pctipoelemento_classe.php");

$cl_pctipoelemento = new cl_pctipoelemento;
$cl_pctipo = new cl_pctipo;

$clrotulo = new rotulocampo;
$clrotulo->label('pc05_codtipo');
$clrotulo->label('pc05_descr');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

if($modo == "resumido") {
  if($ordem == "a") {
    $desc_ordem = "Alfebética";
    $order_by = "pc05_descr";
  } else {
    $desc_ordem = "Numérica";
    $order_by = "pc05_codtipo";
  }
  $desc_modo = "Resumido";
} else {
  if($ordem == "a") {
    $desc_ordem = "Alfebética";
    $order_by = "pc05_descr";
  } else {
    $desc_ordem = "Numérica";
    $order_by = "pc05_codtipo";
  }
  $desc_modo = "Completo";
}
 
$head1 = "RELATÓRIO DOS TIPOS DE MATERIAL/SERVIÇO";
$head3 = "MODO $desc_modo";
$head5 = "ORDEM $desc_ordem";

$result = $cl_pctipo->sql_record($cl_pctipo->sql_query("","*",$order_by));
//db_criatabela($result);exit;

if ($cl_pctipo->numrows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem tipos de materiais/serviços cadastrados.');

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

for($x = 0; $x < $cl_pctipo->numrows;$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,$RLpc05_codtipo,1,0,"C",1);
      $pdf->cell(60,$alt,$RLpc05_descr,1,1,"C",1);
      $troca = 0;
   }
   $pdf->setfont('arial','B',8);
   $pdf->cell(15,$alt,$pc05_codtipo,0,0,"C",0);
   $pdf->cell(60,$alt,$pc05_descr,0,1,"L",0);
   $total++;
   if($modo == "completo") {
     $pdf->setfont('arial','',7);
     $result2 = $cl_pctipoelemento->sql_record($cl_pctipoelemento->sql_query($pc05_codtipo));
     for($y = 0; $y < $cl_pctipoelemento->numrows;$y++){
     db_fieldsmemory($result2,$y);
     $pdf->cell(15,$alt,"",0,0,"C",0);
     $pdf->cell(20,$alt,$o56_elemento,0,0,"C",0);
     $pdf->cell(60,$alt,$o56_descr,0,1,"L",0);     
     }
   }
}

$pdf->setfont('arial','b',8);
$pdf->cell(75,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"L",0);

$pdf->Output();
   
?>
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
include("classes/db_graficas_classe.php");

$clgraficas = new cl_graficas;

$clrotulo = new rotulocampo;
$clrotulo->label('y20_grafica');
$clrotulo->label('z01_numcgm');
$clrotulo->label('z01_nome');
$clrotulo->label('z01_ender');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

if($ordem == "a") {
$desc_ordem = "Alfebética";
$order_by = "z01_nome";
}
else {
$desc_ordem = "Numérica";
$order_by = "y20_grafica";
}
 

$head3 = "CADASTRO DAS SEGURADORAS";
$head5 = "ORDEM $desc_ordem";

$result = $clgraficas->sql_record($clgraficas->sql_query("","*",$order_by));
//echo $clgraficas->sql_query("","*",$order_by); exit;
//db_criatabela($result);

if ($clgraficas->numrows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem graficas cadastradas.');

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

for($x = 0; $x < $clgraficas->numrows;$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
  //    $pdf->cell(15,$alt,$RLy20_grafica,1,0,"C",1);
      $pdf->cell(15,$alt,$RLz01_numcgm,1,0,"C",1);
      $pdf->cell(90,$alt,$RLz01_nome,1,0,"C",1);
      $pdf->cell(90,$alt,$RLz01_ender,1,1,"C",1);
      $total = 0;
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
//   $pdf->cell(15,$alt,$y20_grafica,0,0,"C",0);
   $pdf->cell(15,$alt,$z01_numcgm,0,0,"C",0);
   $pdf->cell(90,$alt,$z01_nome,0,0,"L",0);
   $pdf->cell(90,$alt,$z01_ender.', '.$z01_numero,0,1,"L",0);
   $total++;
}

$pdf->setfont('arial','b',8);
$pdf->cell(150,$alt,'TOTAL DE SEGURADORAS  :  '.$total,"T",0,"L",0);

$pdf->Output();
   
?>
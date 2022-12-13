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

include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("classes/db_curric_classe.php");

$clrotulo = new rotulocampo;
$clcurric = new cl_curric;
$clrotulo->label('r06_codigo');
$clrotulo->label('r06_descr');
$clrotulo->label('r06_elemen');
$clrotulo->label('r06_pd');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;


$head3 = "RELATÓRIO POR TIPO DE CURSO";
if($tiposa == 'a'){
  $head5 = "TIPO : ANALITICO";
}else{
  $head5 = "TIPO : SINTETICO";
}

if($ordem == 'a'){
  $xordem = " h02_descr, z01_nome ";
}else{
  $xordem = "  h02_descr, h01_descr, z01_nome ";
}

$where = '' ; 
if($selec != ''){
  $where = " tabcurritipo.h02_codigo in ($selec)";

}

$sql = $clcurric->sql_query(null,
                            ' h03_seq, z01_numcgm, z01_nome, substr(h01_descr,1,65) as h01_descr, h03_data,h02_codigo, h02_descr', 
                            $xordem , 
                            $where);

$result = db_query($sql);
$xxnum  = pg_numrows($result);

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
$tot_func_geral = 0;
$tot_func_tipo  = 0;

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if($tipo_curr != $h02_descr){
     if($tiposa == 'a'){
       $troca = 1;
     }
     $pdf->setfont('arial','b',8);
     $pdf->cell(0,$alt,"TOTAL $cod_curr - ".strtoupper($tipo_curr)." :  ".$tot_func_tipo,0,1,"L",$pre);
     $tipo_curr = $h02_descr;
     $cod_curr  = $h02_codigo;
     $tot_func_tipo  = 0;
   }
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      if($tiposa == 'a'){
        $pdf->cell(15,$alt,'NUMCGM',1,0,"C",1);
        $pdf->cell(60,$alt,'NOME',1,0,"C",1);
        $pdf->cell(15,$alt,'DATA',1,0,"C",1);
        $pdf->cell(0,$alt,'CURSO',1,1,"C",1);
        $pdf->cell(0, 6, $h02_descr, 0,1,"L",0);
      }
      $troca = 0;
      $pre = 1;
   }
   if($pre == 1){
     $pre = 0;
   }else{
     $pre = 1;
   }
   if($tiposa == 'a'){
     $pdf->setfont('arial','',7);
     $pdf->cell(15,$alt,$z01_numcgm,0,0,"C",$pre);
     $pdf->cell(60,$alt,$z01_nome,0,0,"L",$pre);
     $pdf->cell(15,$alt,db_formatar($h03_data,'d'),0,0,"L",$pre);
     $pdf->cell(0,$alt,$h01_descr,0,1,"L",$pre);
   }
   $tot_func_tipo  += 1;
   $tot_func_geral += 1;
//   $pdf->SetXY($pdf->lMargin,$pdf->gety() + $alt);
}
$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,"TOTAL $cod_curr - ".strtoupper($tipo_curr)." :  ".$tot_func_tipo,0,1,"L",$pre);
$pdf->cell(0,6,'TOTAL DE REGISTROS :  '.$tot_func_geral,"T",0,"L",0);
//$pdf->cell(20,$alt,'',"T",0,"C",0);
//$pdf->cell(30,$alt,db_formatar($total,'f'),"T",1,"R",0);

$pdf->Output();
   
?>
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


$clrotulo = new rotulocampo;
$clrotulo->label('j34_setor');
$clrotulo->label('j34_quadra');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$setor_arr = split(",",$setor);
$vir = "";
$set = "";
for($i=0;$i<count($setor_arr);$i++){
   $set .= $vir."'".$setor_arr[$i]."'";
   $vir = ",";
}
$result=pg_exec("select j34_setor,j34_quadra  from lote  where j34_setor in ($set)");
if (pg_numrows($result) == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem quadras  cadastrados.');

}

$head3 = "QUADRAS POR LOTE ";
$head5 = "ORDEM POR SETOR.";


      
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total = 0;
$quadras="";
$codigo= "";
$vir="";
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(60,$alt,$RLj34_setor,1,0,"C",1);
      $pdf->cell(0,$alt,$RLj34_quadra,1,1,"C",1); 
       
      $troca = 0;
   }
   if($j34_setor!=$codigo){
     $pdf->setfont('arial','',7);
     $pdf->cell(60,$alt,$j34_setor,0,0,"C",0);
     $vir="";
     $total++;     
     $res=pg_query("select distinct j34_quadra from lote where j34_setor='$j34_setor'");
     for($y=0;$y<pg_numrows($res);$y++){
       db_fieldsmemory($res,$y);
       $quadras.=$vir.$j34_quadra;
       $vir=", ";
     }
     $pdf->multicell(0,$alt,$quadras,0,"J",0);
     $codigo=$j34_setor;
     $quadras="";
     $pdf->cell(0,$alt,"","T",1,"C",0);
   }
}

$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"L",0);

$pdf->Output();
   
?>
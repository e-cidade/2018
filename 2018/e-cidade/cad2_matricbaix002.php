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
include("classes/db_iptubase_classe.php");


$cliptubase = new cl_iptubase;

$clrotulo = new rotulocampo;
$clrotulo->label('j01_matric');
$clrotulo->label('j01_numcgm');
$clrotulo->label('z01_nome');
$clrotulo->label('j01_baixa');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;


$where = "1=1 and ";

if (($data!="--")&&($data1!="--")) {
    $where =" j01_baixa  between '$data' and '$data1'  ";
     }else if ($data!="--"){
	$where=" j01_baixa >= '$data'  ";
	}else if ($data1!="--"){
	   $where=" j01_baixa <= '$data1'   ";
	   }

$ordem = $ordem;

if($ordem == "j01_matric"){
  $info = "Matrículas";
}else if ($ordem == "j01_numcgm"){
  $info = "CGM";
}else if ($ordem == "z01_nome"){
  $info = "Nome";
}else if ($ordem == "j01_baixa"){
  $info = "Data";
}

$head3 = "CADASTRO DE MATRÍCULAS BAIXADAS ";
$head5 = "ORDEM POR $info";

$result=$cliptubase->sql_record($cliptubase->sql_query(null,"*","$ordem","$where and j01_baixa is not null "));

if($cliptubase->numrows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
   exit;
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$prenc = 0;
$alt = 4;
$total = 0;

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x,true);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage("L");
      $pdf->setfont('arial','b',8);
      $pdf->cell(30,$alt,$RLj01_matric,1,0,"C",1);
      $pdf->cell(20,$alt,$RLj01_numcgm,1,0,"C",1); 
      $pdf->cell(60,$alt,$RLz01_nome,1,0,"L",1); 
      $pdf->cell(25,$alt,$RLj01_baixa,1,1,"C",1); 

      $troca = 0;
      $prenc = 1;
   }
     if ($prenc == 0){
        $prenc = 1;
       }else $prenc = 0;
   $pdf->setfont('arial','',7);
   $pdf->cell(30,$alt,$j01_matric,0,0,"C",$prenc);
   $pdf->cell(20,$alt,$j01_numcgm,0,0,"C",$prenc); 
   $pdf->cell(60,$alt,$z01_nome,0,0,"L",$prenc); 
   $pdf->cell(25,$alt,$j01_baixa,0,1,"C",$prenc); 
   
   
//     if ($prenc == 0){
//        $prenc = 1;
//       }else $prenc = 0;
   $total++;

}

$pdf->setfont('arial','b',8);
$pdf->cell(135,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"L",0);

$pdf->Output();
   
?>
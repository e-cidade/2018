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
include("classes/db_empnota_classe.php");

$clempnota = new cl_empnota;

$clrotulo = new rotulocampo;
$clrotulo->label('e69_codnota');
$clrotulo->label('e69_numero');
$clrotulo->label('e60_codemp');
$clrotulo->label('e70_valor');
$clrotulo->label('e70_vlrliq');
$clrotulo->label('e70_vlranu');
$clrotulo->label('e69_dtnota');
$clrotulo->label('e69_dtrecebe');
$clrotulo->label('z01_nome');
$clrotulo->label('z01_numcgm');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$ordem = "z01_nome desc,e69_codnota";
$where = "1=1";
$where1 = "";
$where2 = "";
$where3 = "";

if (($data!="--")&&($data1!="--")) {
    $where ="    e69_dtnota  between '$data' and '$data1'  ";
     }else if ($data!="--"){
	$where="    e69_dtnota >= '$data'  ";
	}else if ($data1!="--"){
	   $where="    e69_dtnota <= '$data1'   ";
	   }


if (($data2!="--")&&($data3!="--")) {
    $where1="and    e69_dtrecebe  between '$data2' and '$data3'  ";
     }else if ($data2!="--"){
	$where1="and    e69_dtrecebe >= '$data2'  ";
	}else if ($data3!="--"){
	   $where1="and    e69_dtrecebe <= '$data3'   ";
	   }


if (($numempini!="")&&($numempfim!="")) {
    $where2=" and  e69_numemp  between $numempini and $numempfim  ";
      }else if ($numempini!=""){
	$where2=" and  e69_numemp >= $numempini  ";
	}else if ($numempfim!=""){
	   $where2=" and  e69_numemp <= $numempfim   ";
	   }

if (($ver=="S")&&($codigos!="")){
    $where3=" and z01_numcgm in ($codigos)";
}else if (($ver=="N")&&($codigos!="")){ 
   $where3=" and z01_numcgm not in ($codigos)";
}

$head3 = "RELATÓRIO DE NOTAS";
$head5 = "ORDEM por cod. da Nota";

//die($clempnota->sql_query_usuarios(null,"z01_numcgm,z01_nome,e69_codnota,e69_numero,e60_codemp,e70_valor,e70_vlranu,e70_vlrliq,
//                                         e69_dtnota,e69_dtrecebe","$ordem","$where $where1 $where2 $where3"));
$result=$clempnota->sql_record($clempnota->sql_query_emp(null,"z01_numcgm,z01_nome,e69_codnota,e69_numero,e60_anousu,e60_codemp,e70_valor,e70_vlranu,e70_vlrliq,
                                                                    e69_dtnota,e69_dtrecebe", "$ordem","$where $where1 $where2 $where3"));

if($clempnota->numrows == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Notas não Encontrada.');
  exit; 
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca   = 1;
$prenc   = 0;
$alt     = 4;
$total   = 0;
$cod_cgm = 0;

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x,true);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage("");
      $pdf->setfont('arial','b',10);
      $pdf->cell(185,$alt,$RLz01_numcgm."-".$RLz01_nome,1,1,"L",1);
      $pdf->setfont('arial','b',8);
      $pdf->cell(20,$alt,"Sequência",1,0,"C",1);
      $pdf->cell(25,$alt,$RLe69_numero,1,0,"C",1);
      $pdf->cell(20,$alt,$RLe60_codemp,1,0,"C",1); 
      $pdf->cell(20,$alt,$RLe70_valor,1,0,"C",1); 
      $pdf->cell(25,$alt,$RLe70_vlranu,1,0,"C",1); 
      $pdf->cell(25,$alt,$RLe70_vlrliq,1,0,"C",1); 
      $pdf->cell(25,$alt,$RLe69_dtnota,1,0,"C",1); 
      $pdf->cell(25,$alt,$RLe69_dtrecebe,1,1,"C",1); 

      $troca = 0;
      $prenc = 1;
   }
     if ($prenc == 0){
        $prenc = 1;
       }else $prenc = 0;
   

   if($cod_cgm != $z01_numcgm) {
	   $pdf->setfont('arial','b',10);
	   $pdf->cell(185,$alt,$z01_numcgm."-".$z01_nome,"BT",1,"L",$prenc);
	   
	   $cod_cgm = $z01_numcgm;
   } 	 	
   
   $pdf->setfont('arial','',7);
   $pdf->cell(20,$alt,$e69_codnota,0,0,"C",$prenc);
   $pdf->cell(25,$alt,$e69_numero,0,0,"C",$prenc); 
   $pdf->cell(20,$alt,$e60_codemp."/".$e60_anousu,0,0,"C",$prenc); 
   $pdf->cell(20,$alt,db_formatar($e70_valor,"f"),0,0,"C",$prenc); 
   $pdf->cell(25,$alt,db_formatar($e70_vlranu,"f"),0,0,"C",$prenc); 
   $pdf->cell(25,$alt,db_formatar($e70_vlrliq,"f"),0,0,"C",$prenc); 
   $pdf->cell(25,$alt,$e69_dtnota,0,0,"C",$prenc); 
   $pdf->cell(25,$alt,$e69_dtrecebe,0,1,"C",$prenc); 
 
   $total++;

}

$pdf->setfont('arial','b',8);

$pdf->cell(185,$alt,'TOTAL DE REGISTROS:  '.$total,"T",0,"L",0);

$pdf->Output();
   
?>
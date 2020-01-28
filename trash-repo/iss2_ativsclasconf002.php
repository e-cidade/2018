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
include("classes/db_ativid_classe.php");
//include("classes/db_clasativ_classe.php");

//$clclasativ = new cl_clasativ;
$clativid = new cl_ativid;

$clrotulo = new rotulocampo;
$clrotulo->label('q03_ativ');
$clrotulo->label('q03_descr');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$lista="";
$vir="";

$head3 = "ATIVIDADES SEM CLASSE CONFIGURADA.";
$head5 = "ORDEM POR COD.";

$result1=pg_query("select q82_ativ from clasativ;");
if(pg_numrows($result1) == 0){
    
}else{
  for($x1 = 0; $x1 < pg_numrows($result1);$x1++){
    db_fieldsmemory($result1,$x1);
    $lista .= $vir.$q82_ativ;
    $vir = ",";
  }
}

//die($clativid->sql_query(null,"q03_ativ, q03_descr","q03_ativ","q03_ativ not in ($lista)"));
$result=$clativid->sql_record($clativid->sql_query(null,"q03_ativ, q03_descr","q03_ativ","q03_ativ not in ($lista)"));
if($clativid->numrows == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem atividades sem classe configurada.');
  exit; 
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
$prenc = 0;

for($x = 0; $x < $clativid->numrows;$x++){
  db_fieldsmemory($result,$x,true);
  if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
    $pdf->addpage('');
    $pdf->setfont('arial','b',7);
    $pdf->cell(30,$alt,$RLq03_ativ,1,0,"C",1);
    $pdf->cell(70,$alt,$RLq03_descr,1,1,"C",1);
    $troca = 0;
  }
  $pdf->setfont('arial','',6);
  $pdf->cell(30,$alt,$q03_ativ,0,0,"C",$prenc);
  $pdf->cell(70,$alt,$q03_descr,0,1,"L",$prenc);
  $total++;
  if ($prenc == 0){
    $prenc = 1;
  }else $prenc = 0;
}

$pdf->cell(100,$alt,"TOTAL DE REGISTROS  :".$total,"T",0,"L",0);
$pdf->Output();

?>
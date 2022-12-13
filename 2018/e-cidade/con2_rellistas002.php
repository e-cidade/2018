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
include("classes/db_projmelhorias_classe.php");
include("classes/db_editalproj_classe.php");

$clprojmelhorias = new cl_projmelhorias;
$cleditalproj = new cl_editalproj;
$clprojmelhorias->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label('j14_nome');
$clrotulo->label('d01_numero');
$clrotulo->label('nome');
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$dbwhere="";
$and="";
if(isset($dataini) && $dataini!=""){
  $dataini=str_replace("X","-",$dataini);
  $dbwhere.=" d40_data > '$dataini'";
  $and=" and ";
}
if(isset($datafim) && $datafim!=""){
  $datafim=str_replace("X","-",$datafim);
  $dbwhere.= $and." d40_data < '$datafim'";
}

$result = $clprojmelhorias->sql_record($clprojmelhorias->sql_query("","d40_codigo,d40_data,d40_login,d40_codlog,j14_nome,nome,d40_trecho",$ordem,$dbwhere));
$numrows = $clprojmelhorias->numrows; 
$alt="5";
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$head2 = "Listas de projetos";
$pdf->AddPage("L");
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',10);
$pdf->cell(10,7,$RLd40_codigo,1,0,"C",1);
$pdf->cell(57,7,$RLj14_nome,1,0,"C",1);
$pdf->cell(100,7,$RLd40_trecho,1,0,"C",1);
$pdf->cell(60,7,$RLnome,1,0,"C",1);
$pdf->cell(18,7,$RLd40_data,1,0,"C",1);
$pdf->cell(26,7,$RLd01_numero,1,0,"C",1);
$pdf->setfont('arial','',8);
$pdf->ln();
for ($i = 0;$i < $numrows;$i++){
  db_fieldsmemory($result,$i);
  if ($pdf->gety() > $pdf->h -30  ){
      $pdf->addpage("L");
      $pdf->setfillcolor(235);
      $pdf->setfont('arial','b',10);
      $pdf->cell(10,7,$RLd40_codigo,1,0,"C",1);
      $pdf->cell(57,7,$RLj14_nome,1,0,"C",1);
      $pdf->cell(100,7,$RLd40_trecho,1,0,"C",1);
      $pdf->cell(60,7,$RLnome,1,0,"C",1);
      $pdf->cell(18,7,$RLd40_data,1,0,"C",1);
      $pdf->cell(26,7,$RLd01_numero,1,0,"C",1);
      $pdf->setfont('arial','',8);
      $pdf->ln();
  }  
  $pdf->cell(10,6,$d40_codigo,1,0,"C",0);
  $pdf->cell(57,6,$j14_nome,1,0,"C",0);
  $pdf->cell(100,6,$d40_trecho,1,0,"C",0);
  $pdf->cell(60,6,$nome,1,0,"C",0);
  $pdf->cell(18,6,db_formatar($d40_data,'d'),1,0,"C",0);
  $result45=$cleditalproj->sql_record($cleditalproj->sql_query("","","d01_numero","","d10_codigo=$d40_codigo"));
  $d01_numero="";
  if($cleditalproj->numrows>0){
    db_fieldsmemory($result45,0);
  }  
  $pdf->cell(26,6,$d01_numero,1,0,"C",0);
  $pdf->ln();
}
$pdf->cell(50,6,"Total de registros:$numrows",0,0,"C",0);

$pdf->Output();
?>
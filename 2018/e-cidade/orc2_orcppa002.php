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
include("classes/db_orcppa_classe.php");
include("classes/db_orcppatiporec_classe.php");

$clorcppa = new cl_orcppa;
$clorcppatiporec = new cl_orcppatiporec;

$clorcppa->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('j14_nome');


parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$dbwhere="";
$and="";

$result01  = $clorcppa->sql_record($clorcppa->sql_query_compl(null,"distinct o23_orgao as orgao,o40_descr"));
$numrows01 = $clorcppa->numrows; 

$alt="5";
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$head2 = "PLURIANUAL";
$pdf->AddPage("L");
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',10);
//echo $numrows01;

$pdf->SetWidths(array(140,140));
$pdf->SetAligns(array('J','J'));

for($x=0; $x<$numrows01; $x++){
  db_fieldsmemory($result01,$x); 
  
  $result  = $clorcppa->sql_record($clorcppa->sql_query_compl(null,"*","","o23_orgao=$orgao"));
  $numrows = $clorcppa->numrows; 

  $pdf->ln();
  $pdf->setfont('arial','b',10);
  $pdf->cell(280,7,"$RLo23_orgao:$orgao - $o40_descr ",1,0,"L",1);
  $pdf->ln();



  $pdf->setfont('arial','b',9);
  $pdf->cell(140,6,"OBJETIVOS:",1,0,"L",0);
  $pdf->cell(140,6,"META:",1,0,"L",0);
  
  $pdf->setfont('arial','',8);
  $pdf->ln();
  for ($i = 0;$i < $numrows;$i++){
    db_fieldsmemory($result,$i);
    if ($pdf->gety() > $pdf->h -44  ){
	$pdf->addpage("L");
	$pdf->setfillcolor(235);
	$pdf->setfont('arial','b',10);
        $pdf->cell(280,7,"$RLo23_orgao:$orgao - $o40_descr ",1,1,"L",1);
	$pdf->setfont('arial','b',9);
	$pdf->cell(140,6,"OBJETIVOS:",1,0,"L",0);
	$pdf->cell(140,6,"META:",1,0,"L",0);
	
	$pdf->setfont('arial','',8);
	$pdf->ln();
    }  
  //  $pdf->cell(140,6,"$o23_programa-$o23_programatxt",1,0,"L",0);
//    $pdf->cell(140,6,"$o23_acao-$o23_acaotxt",1,0,"L",0);
    $pdf->Row(array("$o23_programa-$o23_programatxt","$o23_acao-$o23_acaotxt"),3,true,4);
  //  $pdf->ln();
  }
  
  $result02  = $clorcppatiporec->sql_record($clorcppatiporec->sql_query(null,null,"distinct o26_codigo,o15_descr",'',"o23_orgao=$orgao"));
  $numrows02 = $clorcppatiporec->numrows; 
  $recursos  = "";
  $sep       = "";
  for($t=0; $t<$numrows02; $t++){
      db_fieldsmemory($result02,$t);
      $recursos .= $sep.$o15_descr;
      $sep = ",";
  }
  $pdf->cell(50,6,"Total:$numrows",0,0,"L",0);
  $pdf->cell(50,6,"Recursos:$recursos",0,0,"L",0);
}  

$pdf->Output();
?>
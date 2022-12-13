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
include("classes/db_rhlota_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);

$clrhlota = new cl_rhlota;

if(!isset($ano)){
  $ano = db_anofolha();
}
if(!isset($mes)){
  $mes = db_mesfolha();
}

$sql_lotacoes = $clrhlota->sql_query_orgao(null,
    "r70_codigo,
     r70_estrut,
     r70_descr,           
     o40_codtri as o40_orgao,
     o40_descr",
    "o40_orgao,r70_codigo",
    " o40_anousu  = $ano");

$result_lotacoes = $clrhlota->sql_record($sql_lotacoes);

$numrows = $clrhlota->numrows;

if($numrows == 0){
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhuma lotação encontrada");
}


$head3 = "LOTAÇÕES";
$head5 = "TODAS AS LOTAÇÕES";

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);

$totalp = 0;
$totalt = 0;

$troca = 1;
$p = 1;
$alt = 4;

$orgaoantigo = "";

for($x = 0; $x < $numrows; $x ++) {
  db_fieldsmemory($result_lotacoes,$x);

  $orgao = false;
  if($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
    $pdf->addpage();
    $pdf->setfont('arial','b',8);

    $pdf->cell(20,$alt,"Lotação"   ,1,0,"C",1);
    $pdf->cell(30,$alt,"Estrutural",1,0,"C",1);
    $pdf->cell(80,$alt,"Descrição" ,1,1,"C",1);
    
    $orgao = true;

    $troca = 0;
  }
  
  $totalt++;

  $pdf->setfont('arial','b',7);
  if($orgaoantigo != $o40_orgao || $orgao == true){
  	
  	if($orgaoantigo != "" && ($orgao==false || ($orgao==true && $orgaoantigo != $o40_orgao))){
      $pdf->cell(130,$alt,"TOTAL DE REGISTROS NESTE ÓRGÃO  ".$totalp,"T",1,"L",0);
      $totalp = 0;
  	}

  	$orgaoantigo = $o40_orgao;
  	$pdf->ln(2);
    $pdf->cell( 20,$alt,$o40_orgao,"LTB",0,"C",1);
    $pdf->cell(110,$alt,$o40_descr,"RTB",1,"L",1);  	
  }
  $totalp++;
  $pdf->setfont('arial','',7);
  $pdf->cell(20,$alt,$r70_codigo,"T",0,"C",0);
  $pdf->cell(30,$alt,$r70_estrut,"T",0,"C",0);
  $pdf->cell(80,$alt,$r70_descr ,"T",1,"L",0);
}

$pdf->setfont('arial','b',7);
$pdf->cell(130,$alt,"TOTAL DE REGISTROS NESTE ÓRGÃO  ".$totalp,"T",1,"L",0);
$pdf->ln(2);
$pdf->cell(130,$alt,"TOTAL DE REGISTROS  ".$totalt,"T",0,"L",0);
$pdf->Output();
?>
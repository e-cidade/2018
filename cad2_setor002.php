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
include("libs/db_utils.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

///////////////////////////////////////////////////////////////////////
$head1 = "RELATÓRIO DE QUADRAS POR ZONAS FISCAIS";

$orderBy = "j34_setor,j34_quadra,j34_zona";

$head2 = "ORDEM: ";
if ($ordem == "S") {
  $head2 .= "SETOR";
  $orderBy = "j34_setor,j34_quadra,j34_zona";
} elseif ($ordem == "Q") {
  $head2 .= "QUADRA";
  $orderBy = "j34_quadra,j34_setor,j34_zona";
} elseif ($ordem == "Z") {
  $head2 .= "ZONA";
  $orderBy = "j34_zona,j34_setor,j34_quadra";
}

if ($ordem == "Z"){
	$head3 = "TIPO: ";
	if ($tipo == "A") {
	  $head3 .= "ALFABÉTICA";
	  $orderBy = "j50_descr,j34_setor,j34_quadra";
	} elseif ($tipo == "N") {
	  $head3 .= "NUMÉRICA";
	  $orderBy = "j34_zona,j34_setor,j34_quadra";
	}
}

$sJ34Setor 	= "'".implode("','",explode(',',$j34_setor))."'";
$sJ34Quadra = "'".implode("','",explode(',',$j34_quadra))."'";

$sSql = " select distinct j34_setor,
													j34_quadra,
													j34_zona,
                					j50_descr
          		from lote
              inner join zonas on j50_zona = j34_zona
        		where j34_setor 			in ($sJ34Setor) 
        					and j34_quadra 	in ($sJ34Quadra)
        					and j34_zona 		in ($j34_zona) 
        					order by $orderBy 	
        				";
        					
//die($sSql);
$rsSql = pg_query($sSql);
$iNumRows = pg_num_rows($rsSql);
if ($iNumRows == 0){
	db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado com os dados informados.");	       					
}

if($iNumRows > 0){
	$oRelatorio = db_utils::getColectionByRecord($rsSql);
}

$oTotal = new stdClass();
$oTotal->zona = array();
$oTotal->setor = array();
$oTotal->quadra = array();
//Conta o total de registros de cada um
foreach ($oRelatorio as $oQuadra){
	if(!isset($oTotal->zona[$oQuadra->j34_zona])) 								 $oTotal->zona[$oQuadra->j34_zona] 		= 1;
	if(!isset($oTotal->setor[$oQuadra->j34_setor])) 							 $oTotal->setor[$oQuadra->j34_setor] 	= 1;
	if(!isset($oTotal->quadra[$oQuadra->j34_setor][$oQuadra->j34_quadra])) $oTotal->quadra[$oQuadra->j34_setor][$oQuadra->j34_quadra] = 1;	
}

$oTotal->totalZona 	= count($oTotal->zona);
$oTotal->totalSetor = count($oTotal->setor);
$oTotal->totalQuadra = 0;
$oTotal->totalRegistros = $iNumRows;

foreach ($oTotal->quadra as $aSetor){
	$oTotal->totalQuadra += count($aSetor);
}

$pdf = new PDF(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas

$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
$pdf->SetFont('Arial','B',11);
$alt    = 4;
$lin    = 1;

$pdf->AddPage("P");
$linm = 1;
$lin  = 1;
$p=0;
for($i = 0; $i < $iNumRows; $i++){
  
  if($pdf->gety() > $pdf->h - 30 || $i ==0){
    if($pdf->gety() > $pdf->h - 30){
      $pdf->AddPage("P");
    }
    $p=0;
    $pdf->SetFont('Arial','b',8);
    $pdf->Cell(15,$alt,"Setor",1,0,"C",1);
    $pdf->Cell(15,$alt,"Quadra",1,0,"C",1);
    $pdf->Cell(15,$alt,"Zona"  ,1,0,"C",1);
    $pdf->Cell(145,$alt,"Descrição",1,1,"C",1);
        
  }
  $pdf->SetFont('Arial','',7);
  $pdf->Cell(15,$alt	,$oRelatorio[$i]->j34_setor,	0,	0,	"C",	$p);
  $pdf->Cell(15,$alt	,$oRelatorio[$i]->j34_quadra,	0,	0,	"C",	$p);
  $pdf->Cell(15,$alt	,$oRelatorio[$i]->j34_zona,		0,	0,	"R",	$p);
  $pdf->Cell(145,$alt	,$oRelatorio[$i]->j50_descr,	0,	1,	"L",	$p);
  
  if ($p==0){
    $p=1;
  }else{
    $p=0;
  }
  
}
$pdf->ln();
$pdf->SetFont('Arial','b',8);
$pdf->Cell(175,$alt,"TOTAL DE REGISTROS :","",0,"R",0);
$pdf->Cell(15,$alt,$oTotal->totalRegistros,"",1,"R",0);

$pdf->Cell(30,$alt,"TOTAIS","",1,"L",0);
$pdf->Cell(35,$alt,"TOTAL DE SETOR(ES) :","",0,"L",1);
$pdf->Cell(10,$alt,$oTotal->totalSetor,"",1,"R",1);
$pdf->Cell(35,$alt,"TOTAL DE QUADRA(S) :","",0,"L",0);
$pdf->Cell(10,$alt,$oTotal->totalQuadra,"",1,"R",0);
$pdf->Cell(35,$alt,"TOTAL DE ZONA(S) :","",0,"L",1);
$pdf->Cell(10,$alt,$oTotal->totalZona,"",1,"R",1);
$pdf->ln(3);

$pdf->Output();
?>
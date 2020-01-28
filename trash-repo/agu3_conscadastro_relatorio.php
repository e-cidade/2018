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


$head5 = "Relatório de Imóveis";
include("fpdf151/pdf.php");

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage();
require("libs/db_conectapdf.php");
for ($totalRegistos=0;$totalRegistos<sizeof($parametro);$totalRegistos++){
  // $parametro recebe cod_matricula
$sql = "
  select proprietario.* , c.z01_nome as promitente,
    c.z01_ender as ender_promitente,
    j.z01_nome as imobiliaria,j.z01_ender as ender_imobiliaria
  from proprietario
    left outer join cgm c on j41_numcgm = c.z01_numcgm
    left outer join cgm j on j44_numcgm = j.z01_numcgm
  where j01_matric = $parametro[$totalRegistos] limit 1
";
$matriculaSelecionada = pg_exec($sql);
$numMatriculaSelecionada = pg_numrows($matriculaSelecionada);
  if ($numMatriculaSelecionada == 0) {

    $pdf->SetFont('Arial','B',9);
    $pdf->setX(5);
    $pdf->Cell(200,4,"Matrícula ".$parametro[$totalRegistos]." Inexistente","LRBT",1,"C",0);
  } else {
    db_fieldsmemory($matriculaSelecionada,0);
    $pdf->SetFont('Arial','B',9);


    $pdf->setX(5);
    $pdf->Cell(200,4,"","",1,"C",0);


	$pdf->setX(5);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(20,4,"Matrícula :","",0,"L",0);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(80,4,"$j01_matric","",0,"L",0);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(20,4,"Tipo :","",0,"L",0);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(80,4,"$j01_tipoimp","",1,"L",0);

    $pdf->setX(5);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(20,4,"Proprietário :","",0,"L",0);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(80,4,"$z01_nome","",0,"L",0);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(20,4,"Endereço :","",0,"L",0);
    $pdf->SetFont('Arial','B',9);
	$ender = substr($z01_ender,0,30);
    $pdf->Cell(70,4,"$ender","",1,"L",0);

	$pdf->setX(5);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(15,4,"Setor :","",0,"L",0);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(50,4,"$j34_setor","",0,"L",0);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(15,4,"Quadra :","",0,"L",0);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(50,4,"$j34_quadra","",0,"L",0);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(15,4,"Lote :","",0,"L",0);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(50,4,"$j34_lote","",1,"L",0);

    $pdf->setX(5);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(20,4,"Logradouro :","",0,"L",0);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(180,4,"$codpri   , $nomepri, $j39_numero / $j39_compl","",1,"L",0);
}
}
$pdf->Output();
?>
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
require_once("agu3_conscadastro_002_classe.php");
$Consulta = new ConsultaAguaBase($matric);
$sqlender = " select j01_matric,j01_numcgm,z01_ender,z01_numero,z01_nome 
							from iptubase 
							inner join cgm on z01_numcgm = j01_numcgm 
							where j01_matric = $matric";
$resultender  = pg_query($sqlender);
$linhasender =pg_num_rows($resultender);
if($linhasender>0){
  db_fieldsmemory($resultender,0);
}

$head2 = "Histórico de Cortes";
$head4 = "Matrícula: ".$matric;

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(220);
$pdf->SetFont('Arial','',11);
$primeiro =0;
$total = 0;
$sql = $Consulta->GetAguaCorteMatMovSQL();
$result = pg_exec($sql);
$linhas= pg_num_rows($result);
if($linhas>0){
  for($i=0;$i<$linhas;$i++) {
    db_fieldsmemory($result,$i);
    if($pdf->GetY() > ( $pdf->h - 30 )||($primeiro ==0)){
      $primeiro =1;
      $pdf->Text($pdf->w-20,$pdf->h-5, $pdf->PageNo());
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',10);
      $pdf->MultiCell(0,12,"HISTÓRICO DE CORTES",0,"C",0);
      $pdf->SetFont('Arial','B',8);

      $pdf->Cell(90,5,"MATRÍCULA: ".$matric,0,0,"L",0);
      $pdf->Cell(100,5,"NOME: ".@$z01_nome,0,1,"L",0);
      $pdf->Cell(190,5,"ENDEREÇO: ".@$z01_ender .", ".@$z01_numero,0,1,"L",0);
      $pdf->Ln(2);
      
      $pdf->Cell(15,6,"CORTE",1,0,"C",1);
      $pdf->Cell(17,6,"SITUAÇÃO",1,0,"C",1);
      $pdf->Cell(80,6,"DESCRIÇÃO",1,0,"C",1);
      $pdf->Cell(20,6,"DATA",1,0,"C",1);
      $pdf->Cell(60,6,"HISTÓRICO",1,0,"C",1);
      $pdf->Ln();

    }
    // para fazer zebrado
    if($i % 2 == 0){
      $pre = 0;
    }else {
      $pre = 1;
    }
    
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(15,6,$x40_codcorte,0,0,"C",$pre);
    $pdf->Cell(17,6,$x42_codsituacao,0,0,"C",$pre);
    $pdf->Cell(80,6,$x43_descr,0,0,"L",$pre);
    $pdf->Cell(20,6,db_formatar($x42_data,"d"),0,0,"C",$pre);
    $pdf->Cell(60,6,$x42_historico,0,0,"L",$pre);
    $pdf->Ln();
    $total ++;

  }
}
$pdf->Ln(5);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(192,6,"Total de Registros :   ".@$total,"TB",0,"L",0);
$pdf->Output();

?>
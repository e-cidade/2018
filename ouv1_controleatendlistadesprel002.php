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


require_once("fpdf151/pdf.php");
require_once("libs/db_utils.php");
require_once("libs/JSON.php");

$oGet       = db_utils::postMemory($_GET);
$oJson      = new services_json();
$aDespachos = $oJson->decode(str_replace("\\","",$oGet->aObjDespachos));

$head2 = "Despachos do Processo {$oGet->iCodProc}";

$pdf = new PDF("L");
$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);

$iAlt = 5;
$iCor = 1;


foreach ($aDespachos as $iInd => $oDespacho ) {
	
	
  if ($pdf->gety() > $pdf->h - 30  || $iInd == 0 ){
    
  	if ( $iInd != 0 ) {
  	  $pdf->AddPage('L');  	
  	}
    $pdf->SetFont('Arial','B',7);
		$pdf->Cell(20,$iAlt,"Data"              ,1,0,"C",1);
		$pdf->Cell(20,$iAlt,"Hora"              ,1,0,"C",1);
		$pdf->Cell(90,$iAlt,"Depto"             ,1,0,"C",1);
		$pdf->Cell(25,$iAlt,"Usuário Envolvido" ,1,0,"C",1);
		$pdf->Cell(125,$iAlt,"Despacho"         ,1,1,"C",1);  	
  }	
  
  if ( $iCor == 1) {
  	$iCor = 0;
  } else {
  	$iCor = 1;
  }

  $sDespacho = str_replace("<br>","",$oDespacho->despacho);
  
  $pdf->SetFont('Arial','',7);
	$pdf->Cell(20,$iAlt,db_formatar($oDespacho->data,'d'),0,0,"C",$iCor);
	$pdf->Cell(20,$iAlt,$oDespacho->hora                 ,0,0,"C",$iCor);
	$pdf->Cell(90,$iAlt,$oDespacho->descrdepto           ,0,0,"L",$iCor);
	$pdf->Cell(25,$iAlt,$oDespacho->nome                 ,0,0,"C",$iCor);
	$pdf->MultiCell(125,$iAlt,$sDespacho,0,'L',$iCor);
	
}
  
$pdf->Output();

?>
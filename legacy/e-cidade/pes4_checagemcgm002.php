<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);
 
$head1 = "Relatório de Checagem CGM";

$oPdf  = new PDF(); 
$oPdf->Open(); 
$oPdf->AliasNbPages(); 
$oPdf->SetTextColor(0,0,0);
$oPdf->SetFillColor(220);
$oPdf->SetAutoPageBreak(false);

$lImprime   = true;
$lPreencher = true;
$iFonte     = 8;
$iAlt       = 4 ;

$aDadosRelatorio = $_SESSION['oChecagemCGM'];  
foreach ($aDadosRelatorio as $oDado) {
	
	if ($oPdf->gety() > $oPdf->h - 30 || $lImprime) {
		
		$lImprime = false;
		$oPdf->addpage("P");
		$oPdf->SetFont('Arial','b',$iFonte);
		$oPdf->Cell(30, $iAlt, 'CGM', 1, 0, 'C', 1);
		$oPdf->Cell(65, $iAlt, 'Nome', 1, 0, 'C', 1);
		$oPdf->Cell(30, $iAlt, 'Matricula', 1, 0, 'C', 1);
		$oPdf->Cell(65, $iAlt, 'Nome Arquivo', 1, 1, 'C', 1);
	}
	
  if ($lPreencher == true) {
                  
    $lPreencher = false;
    $iCorFundo  = 0;    
    $oPdf->SetFillColor(220);
  } else {
            
    $lPreencher = true;
    $iCorFundo  = 1;
    $oPdf->SetFillColor(240);
  }
	
	$oPdf->SetFont('Arial','',$iFonte);
	$oPdf->Cell(30, $iAlt, $oDado->z01_numcgm, "TB", 0, 'C', $iCorFundo);
	$oPdf->Cell(65, $iAlt, substr($oDado->z01_nome, 0, 50), 1, 0, 'L', $iCorFundo);
	$oPdf->Cell(30, $iAlt, $oDado->matricula, 1, 0, 'C', $iCorFundo);
	$oPdf->Cell(65, $iAlt, substr($oDado->nome, 0, 50), "TB", 1, 'L', $iCorFundo);
}

$oPdf->Output();
?>
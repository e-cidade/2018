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

include("fpdf151/pdf.php");
include("libs/db_libpessoal.php");
include("libs/db_utils.php");
include("classes/db_rhsuspensaopag_classe.php");

$oGet   = db_utils::postMemory($_GET);

$cl_rhsuspensaopag = new cl_rhsuspensaopag();

$sCamposSuspensao  = "rh101_regist, z01_nome, rh101_dtcadastro, rh101_usuario, db_usuarios.nome, rh101_dtinicial, rh101_dtfinal";
$sWhereSuspensao   = "  ((rh101_dtinicial     BETWEEN '{$oGet->dDataIni}' AND '{$oGet->dDataFin}')  ";
$sWhereSuspensao  .= "OR (rh101_dtfinal       BETWEEN '{$oGet->dDataIni}' AND '{$oGet->dDataFin}')) ";
$sWhereSuspensao  .= "AND rh101_dtdesativacao IS NULL";

$sRhSuspensao      = $cl_rhsuspensaopag->sql_query("", $sCamposSuspensao,"",$sWhereSuspensao);
$rsRhSuspensao     = $cl_rhsuspensaopag->sql_record($sRhSuspensao);
$iSuspensaoNumRows = $cl_rhsuspensaopag->numrows;
$aSuspensoes       = db_utils::getColectionByRecord($rsRhSuspensao);

$pdf    = new PDF(); 
$head3  = "RELATÓRIO DE SUSPENSÕES ATIVAS POR PERÍODO";
$head5  = "Período: ".db_formatar($oGet->dDataIni,"d")."  à  ".db_formatar($oGet->dDataFin,"d");
$pdf   -> Open(); 
$pdf   -> AddPage('L');
$pdf   -> AliasNbPages(); 
$pdf   -> setfillcolor(235);

cabecalhoPagina($pdf);

foreach ($aSuspensoes as $oSuspensao) {
  
	if ($pdf->GetY() > $pdf->h - 25){

		$pdf->AddPage();
    cabecalhoPagina($pdf);
  }   
  $pdf->Cell(20, 5, $oSuspensao->rh101_regist,1,0,'C',0,"","");
  $pdf->Cell(95, 5, $oSuspensao->z01_nome,1,0,'L',0,"","");
  $pdf->Cell(25, 5, db_formatar($oSuspensao->rh101_dtcadastro,"d"),1,0,'C',0,"","");
  $pdf->Cell(89, 5, $oSuspensao->nome,1,0,'L',0,"","");
  $pdf->Cell(25, 5, db_formatar($oSuspensao->rh101_dtinicial,"d"),1,0,'C',0,"","");
  $pdf->Cell(25, 5, db_formatar($oSuspensao->rh101_dtfinal,"d"),1,1,'C',0,"","");
}
$pdf->Output();


function cabecalhoPagina(&$pdf) {
  
  $pdf->SetFont('','BI');
  $pdf->Cell(20, 4, "MATRÍCULA",1,0,'C',1,"","");
  $pdf->Cell(95, 4, "NOME",1,0,'C',1,"","");
  $pdf->Cell(25, 4, "DATA CADASTRO",1,0,'C',1,"","");
  $pdf->Cell(89, 4, "USUÁRIO",1,0,'C',1,"","");
  $pdf->Cell(25, 4, "DATA INICIAL",1,0,'C',1,"","");
  $pdf->Cell(25, 4, "DATA FINAL",1,1,'C',1,"","");
  $pdf->SetFont('','');
}
?>
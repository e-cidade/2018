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
require_once("libs/JSON.php");

$oGet       = db_utils::postMemory($_GET);
$oJson      = new services_json();
$aProcessos = $oJson->decode(str_replace("\\","",$oGet->aObjProcessos));


require_once("classes/db_processoouvidoriaprorrogacao_classe.php");
$clProcessoOuvidoriaProrrogacao = new cl_processoouvidoriaprorrogacao();

$head2 = "Controle de Atendimento";

$pdf = new PDF("L"); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage(); // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);

$iAlt = 5;
$iCor = 1;

$pdf->SetFont('Arial','B',7);
$pdf->Cell(15,$iAlt,"Processo"          ,1,0,"C",1);
$pdf->Cell(20,$iAlt,"Atendimento"       ,1,0,"C",1);
$pdf->Cell(65,$iAlt,"Requerente"        ,1,0,"C",1);
$pdf->Cell(70,$iAlt,"Tipo de Processo"  ,1,0,"C",1);
$pdf->Cell(20,$iAlt,"Prazo Previsto"    ,1,0,"C",1);
$pdf->Cell(60,$iAlt,"Departamento Atual",1,0,"C",1);
$pdf->Cell(25,$iAlt,"Usuário Atual"     ,1,1,"C",1);

foreach ($aProcessos as $iInd => $oProcesso ) {
	
	$sWhereProrrogacao    = "     ov15_protprocesso = {$oProcesso->p58_codproc}";
	$sWhereProrrogacao   .= " and ov15_ativo is true                           ";
	$sSqlProrrogacaoPrazo = $clProcessoOuvidoriaProrrogacao->sql_query_file(null,"max(ov15_dtfim) as ov15_dtfim",null,$sWhereProrrogacao);
	$rsProrrogacaoPrazo   = $clProcessoOuvidoriaProrrogacao->sql_record($sSqlProrrogacaoPrazo); 
	
	if ( $clProcessoOuvidoriaProrrogacao->numrows > 0) {
		$oProrrogacaoPrazo = db_utils::fieldsMemory($rsProrrogacaoPrazo,0);
		$sPrazoPrevisto    = db_formatar($oProrrogacaoPrazo->ov15_dtfim,'d'); 
	} else {
		$sPrazoPrevisto    = '';
	}
	
  if ($pdf->gety() > $pdf->h - 30 ){
  	
    $pdf->AddPage('L');
  	$pdf->SetFont('Arial','B',7);
  	$pdf->Cell(15,$iAlt,"Processo"          ,1,0,"C",1);
  	$pdf->Cell(20,$iAlt,"Atendimento"       ,1,0,"C",1);
		$pdf->Cell(65,$iAlt,"Requerente"        ,1,0,"C",1);
		$pdf->Cell(70,$iAlt,"Tipo de Processo"  ,1,0,"C",1);
		$pdf->Cell(20,$iAlt,"Prazo Previsto"    ,1,0,"C",1);
		$pdf->Cell(60,$iAlt,"Departamento Atual",1,0,"C",1);
		$pdf->Cell(25,$iAlt,"Usuário Atual"     ,1,1,"C",1);
  }	
  
  if ( $iCor == 1) {
  	$iCor = 0;
  } else {
  	$iCor = 1;
  }
  
  $pdf->SetFont('Arial','',7);
	$pdf->Cell(15,$iAlt,$oProcesso->p58_codproc ,0,0,"C",$iCor);
	$pdf->Cell(20,$iAlt,$oProcesso->ov01_anousu ,0,0,"C",$iCor);
	$pdf->Cell(65,$iAlt,substr($oProcesso->p58_requer, 0, 35) ,0,0,"L",$iCor);
	$pdf->Cell(70,$iAlt,substr($oProcesso->p58_codigo, 0, 40)  ,0,0,"L",$iCor);
	$pdf->Cell(20,$iAlt,$sPrazoPrevisto         ,0,0,"C",$iCor);
	$pdf->Cell(60,$iAlt,$oProcesso->p61_coddepto,0,0,"L",$iCor);
	$pdf->Cell(25,$iAlt,$oProcesso->login       ,0,1,"L",$iCor);
	
}
  
$pdf->Output();

?>
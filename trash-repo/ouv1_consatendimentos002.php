<?php
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

require_once ('libs/db_utils.php');
require_once ('fpdf151/pdf.php');

$oGet = db_utils::postMemory($_GET);

/**
 * Carregamos a DAO da tabela 'ouvidoriaatendimento'
 */
$oDaoOuvidoriaAtendimento = db_utils::getDao('ouvidoriaatendimento');
		
/**
 * Relação dos campos que serão retornados pela pesquisa
 */
$sCampos  = " DISTINCT ov01_sequencial,                           "; 
$sCampos .= " fc_numeroouvidoria(ov01_sequencial) as ov01_numero, ";
$sCampos .= " p51_descr,                                          "; 
$sCampos .= " ov01_requerente,                                    "; 
$sCampos .= " descrdepto,                                         ";
$sCampos .= " ov01_dataatend                                      ";

/**
 * Testamos os valores passados pelo filtro e montamos a string do Where
 */
$sWhereBuscaAtendimentos = '';
if (isset($oGet->data_inicial) && trim($oGet->data_inicial) != "") {
  $sWhereBuscaAtendimentos .=  " AND ov01_dataatend > '{$oGet->data_inicial}' ";
}
if (isset($oGet->data_final) && trim($oGet->data_final) != "") {
  $sWhereBuscaAtendimentos .=  " AND ov01_dataatend < '{$oGet->data_final}' ";
}
if (isset($oGet->tipoProcesso) && trim($oGet->tipoProcesso) != "") {
  $sWhereBuscaAtendimentos .= " AND ov01_tipoprocesso = {$oGet->tipoProcesso} ";
}
if (isset($oGet->numeroAtendimento) && trim($oGet->numeroAtendimento) != "") {
  $sWhereBuscaAtendimentos .= " AND ov01_numero = {$oGet->numeroAtendimento} ";
}
if (isset($oGet->numeroProcesso) && trim($oGet->numeroProcesso) != "") {
  $sWhereBuscaAtendimentos .= " AND ov09_protprocesso = {$oGet->numeroProcesso} ";
}
if (isset($oGet->codigoDepart) && $oGet->codigoDepart != "0") {
  $sWhereBuscaAtendimentos .= " AND ov01_depart = {$oGet->codigoDepart} ";
}
$sWhereBuscaAtendimentos = substr($sWhereBuscaAtendimentos, 5, (strlen($sWhereBuscaAtendimentos) - 5));
$sSqlBuscaAtendimentos   = $oDaoOuvidoriaAtendimento->sql_query_consultaatendimentos(null, $sCampos, 
                                                                                     null, $sWhereBuscaAtendimentos);
$rsBuscaAtendimentos     = $oDaoOuvidoriaAtendimento->sql_record($sSqlBuscaAtendimentos);
$aResultado              = db_utils::getColectionByRecord($rsBuscaAtendimentos);

/**
 * Começamos a criar o PDF
 */

$pdf    = new PDF ('L', 'mm', 'A4');
$head2 = 'Relatório de Atendimentos';
$pdf_cabecalho = true;
$pdf->Open();
$pdf->AliasNbPages();

$iNumRows = count($aResultado);

for ($i = 0; $i < $iNumRows; $i++) {
	
	//$pdf->AutoPageBreak(false);
	
	if ($pdf->GetY() > $pdf->h -25 || $pdf_cabecalho == true) {
		
		$pdf_cabecalho = false;
		$pdf->SetFont('Courier','',7);
    $pdf->SetTextColor(0,0,0);
    $pdf->setfillcolor(235);
    $preenc = 0;
    $linha  = 1;
    $bordat = 0;
    $pdf->AddPage('L');
    $pdf->SetFont('Arial','b',7);
    $pdf->ln(2);
    
    $pdf->Cell(25,5,"Número"          , 1, 0, "C", 1);
    $pdf->Cell(85,5,"Tipo de Processo", 1, 0, "C", 1);
    $pdf->Cell(85,5,"Requerente"      , 1, 0, "C", 1);
    $pdf->Cell(55,5,"Depto Atual"     , 1, 0, "C", 1);
    $pdf->Cell(25,5,"Data Criação"    , 1, 1, "C", 1);
	}
	
	$pdf->SetFont('Arial','',7);
  $pdf->Cell(25,5,$aResultado[$i]->ov01_numero,                     0, 0, "C", 0);
  $pdf->Cell(85,5,$aResultado[$i]->p51_descr,                       0, 0, "L", 0);
  $pdf->Cell(85,5,$aResultado[$i]->ov01_requerente,                 0, 0, "L", 0);
  $pdf->Cell(55,5,$aResultado[$i]->descrdepto,                      0, 0, "L", 0);
  $pdf->Cell(25,5,db_formatar($aResultado[$i]->ov01_dataatend,'d'), 0, 1, "C", 0);
}

$pdf->Ln(4);
$pdf->Cell(245,5,'Total de Registros:','',0,"R",1);
$pdf->Cell(30,5,$iNumRows,'',1,"R",1);
$pdf->Output();

?>
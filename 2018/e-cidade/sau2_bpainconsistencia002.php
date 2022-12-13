<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require_once ("fpdf151/pdf.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/JSON.php");
require_once ("std/DBDate.php");
require_once ("dbforms/db_funcoes.php");

$sArquivo    = file_get_contents("tmp/erro_bpa_magnetico.json");
$oLogArquivo = json_decode($sArquivo);
$oDados      = db_utils::postMemory($_GET);

$aInconsistenciaPaciente = array();
$aInconsistenciaMedico   = array();
$aInconsistenciaFaa      = array();


/**
 * Percorremos cada erro retornado, e armazenamos em um array de acordo com a origem
 * @example Propriedades
 * 
 * Array de pacientes
 * $aInconsistenciaPaciente[]->paciente
 *                           ->nome
 *                           ->erro
 *                   
 * Array de médicos 
 * $aInconsistenciaMedico[]->medico
 *                         ->nome
 *                         ->erro
 *                         
 * Array de FAA
 * $aInconsistenciaFaa[]->faa         
 *                      ->procedimento
 *                      ->erro                        
 */
foreach ($oLogArquivo->aLogs as $oLog) {

  if (isset($oLog->paciente)) {
    $aInconsistenciaPaciente[$oLog->paciente] = $oLog;
  }
  
  if (isset($oLog->medico)) {
    $aInconsistenciaMedico[$oLog->medico] = $oLog;
  }

  if (isset($oLog->faa)) {
    $aInconsistenciaFaa[] = $oLog;
  }
}

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetFillColor(235);

$head1 = "Relatório de inconsistência BPA Magnético";

/** ***************************************
 * Imprime as inconsistências dos pacientes 
 */
$lImprimeCabecalho = true;
$iContador         = 0;

foreach ($aInconsistenciaPaciente as $oLogPaciente) {
	
  if ($lImprimeCabecalho || $oPdf->h < $oPdf->GetY() + 25) {
    
    $lAdicionaPagina = $oPdf->h < ($oPdf->GetY() + 25) || $lImprimeCabecalho ? true : false;
    cabecalho($oPdf, 1, $lAdicionaPagina);
    $lImprimeCabecalho = false;
  }   

  $oPdf->SetFont("arial", "", 8);
  $oPdf->Cell(25, 4, $oLogPaciente->paciente,           "TBR", 0, "C");
  $oPdf->Cell(80, 4, utf8_decode($oLogPaciente->nome),      1, 0, "L");
  $oPdf->Cell(87, 4, utf8_decode($oLogPaciente->erro),  "TBL", 1, "L");
  
  $iContador++;
}

if (count($aInconsistenciaPaciente) > 0) {

  $oPdf->SetFont("arial", "b", 8);
  $oPdf->Cell(172, 4, "Total de pacientes com inconsistência:",  "TBR", 0, "R");
  $oPdf->Cell(20,  4, $iContador,  "TBL", 1, "L");
  $oPdf->Ln(2);
}

/** *************************************
 * Imprime as inconsistencias dos médicos
 */
$lImprimeCabecalho = true;
$iContador         = 0;
foreach ($aInconsistenciaMedico as $oLogMedico) {
	
  if ($lImprimeCabecalho || $oPdf->h < $oPdf->GetY() + 25) {
    
    $lAdicionaPagina = true;
    if (!$lImprimeCabecalho) {
      $lAdicionaPagina = $oPdf->h < ($oPdf->GetY() + 25) ? true : false; 
    }
    cabecalho($oPdf, 2, $lAdicionaPagina);
    $lImprimeCabecalho = false;
  }
  
  $oPdf->SetFont("arial", "", 8);
  $oPdf->Cell(25, 4, $oLogMedico->medico,             "TBR", 0, "C");
  $oPdf->Cell(80, 4, utf8_decode($oLogMedico->nome),      1, 0, "L");
  $oPdf->Cell(87, 4, utf8_decode($oLogMedico->erro),  "TBL", 1, "L");
  $iContador++;
}

if (count($aInconsistenciaMedico) > 0) {
  
  $oPdf->SetFont("arial", "b", 8);
  $oPdf->Cell(172, 4, "Total de médicos com inconsistência:",  "TBR", 0, "R");
  $oPdf->Cell(20,  4, $iContador,  "TBL", 1, "L");
  $oPdf->Ln(2);
}

/** **********************************
 * Imprime as inconsistências das FAAs
 */
$lImprimeCabecalho = true;
$iContador         = 0;

foreach ($aInconsistenciaFaa as $oLogFaa) {

  if ($lImprimeCabecalho || $oPdf->h < $oPdf->GetY() + 25) {
    
    $lAdicionaPagina = $oPdf->h < ($oPdf->GetY() + 25) ? true : false;
    cabecalho($oPdf, 3, $lAdicionaPagina);
    $lImprimeCabecalho = false;
  }
  
  $oPdf->SetFont("arial", "", 8);
  $oPdf->Cell(25, 4, $oLogFaa->faa,                "TBR", 0, "C");
  $oPdf->Cell(80, 4, $oLogFaa->procedimento,           1, 0, "L");
  $oPdf->Cell(87, 4, utf8_decode($oLogFaa->erro),  "TBL", 1, "L");
  $iContador++;
}
if (count($aInconsistenciaFaa) > 0) {
  
  $oPdf->SetFont("arial", "b", 8);
  $oPdf->Cell(172, 4, "Total de FAA com inconsistência:",  "TBR", 0, "R");
  $oPdf->Cell(20,  4, $iContador,  "TBL", 1, "L");
  $oPdf->Ln(2);
}


/**
 * Imprime o cabeçalho 
 * @param PDF $oPdf
 * @param tipo $iTipo = 1 - Paciente 
 *                      2 - Médico
 *                      3 - FAA  
 */
function cabecalho(PDF $oPdf, $iTipo, $lAdicionaPagina = true) {
	
  if ($lAdicionaPagina) {
    $oPdf->AddPage("P");
  }
  
  $sTipo      = "Paciente";
  $sDescricao = "Nome";
  switch ($iTipo) {
    
  	case 2 :

  	  $sTipo = "Médico";
  	  break;
  	  
  	case 3 : 

  	  $sTipo      = "FAA";
  	  $sDescricao = "Procedimento";
  	  break;
  }
  
  $oPdf->SetFont("arial", "b", 9);
  $oPdf->Cell(25, 4, $sTipo,      1, 0, "C", 1);
  $oPdf->Cell(80, 4, $sDescricao, 1, 0, "C", 1);
  $oPdf->Cell(87, 4, "Mensagem",  1, 1, "C", 1);
  
}

$oPdf->Output();
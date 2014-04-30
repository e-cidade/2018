<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("fpdf151/pdfwebseller.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");
require_once("dbforms/db_funcoes.php");
require_once("std/DBDate.php");
db_app::import("educacao.*");

$oJson       = new Services_JSON();
$oLogArquivo = $oJson->decode(file_get_contents("tmp/encerramento.json"));
$oDados      = db_utils::postMemory($_GET);

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetFillColor(235);
$oPdf->SetAutoPageBreak(true);

$lPrimeiraVolta = true;
$iHeigth        = 4;
$oEscola        = new Escola(db_getsession("DB_coddepto"));

$head1 = "Relatório de Pendências para Encerramento";
$head2 = "Etapa: ".urldecode($oLogArquivo->aLogs[0]->etapa);

/**
 * Buscamos as informacoes da turma para complemento do cabecalho
 */
if (!empty($oDados->iTurma)) {
  
  $oTurma = new Turma($oDados->iTurma);
  $head3  = "Turma: {$oTurma->getDescricao()}";
  $head4  = "Calendário: {$oTurma->getCalendario()->getDescricao()}";
}

foreach ($oLogArquivo->aLogs as $oLog) {
  
  if ($lPrimeiraVolta || $oPdf->GetY() > $oPdf->h - 15) {
    
    imprimeCabecalho($oPdf);
    $lPrimeiraVolta = false;
  }
  
  $oPdf->SetFont("arial", "", 7);
  
  $iYInicial = $oPdf->GetY();
  $oPdf->SetX(115);
  $oPdf->MultiCell(87, 4, urldecode($oLog->mensagem), "TBL");
  $iHeight = $oPdf->GetY() - $iYInicial;
  $oPdf->SetXY(10, $iYInicial);
  
  $oPdf->Cell(35, $iHeight, urldecode($oLog->disciplina), "TBR", 0, "L" );
  $oPdf->Cell(70, $iHeight, urldecode($oLog->aluno),         1, 1, "L" );
}

$oPdf->Output();

/**
 * Imprimimos o cabecalho
 * @param FPDF $oPdf
 */
function imprimeCabecalho($oPdf) {

  $oPdf->AddPage();
  $oPdf->SetFont("arial", "b", 7);
  $oPdf->Cell(35,  4, "Disciplina", "TBR", 0, "C", 1);
  $oPdf->Cell(70,  4, "Aluno",          1, 0, "C", 1);
  $oPdf->Cell(87,  4, "Mensagem",   "TBL", 1, "C", 1);
}
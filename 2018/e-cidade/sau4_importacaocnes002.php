<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("fpdf151/FpdfMultiCellBorder.php");

$sArquivoLog      = "tmp/log_inconsistencia_cnes.json";
$sArquivoLogNovos = "tmp/log_inconsistencia_cnes_novos.json";

$oLogs      = null;
$oLogsNovos = null;
if (file_exists($sArquivoLog)) {
  $oLogs = json_decode(file_get_contents($sArquivoLog));
}

if (file_exists($sArquivoLogNovos)) {
  $oLogsNovos = json_decode(file_get_contents($sArquivoLogNovos));
}


$aLogs = array();
if ( isset($oLogs->aLogs) ) {
  $aLogs = array_merge($aLogs, $oLogs->aLogs);
}

if ( isset($oLogsNovos->aLogs) ) {
  $aLogs = array_merge($aLogs, $oLogsNovos->aLogs);
}

$aDadosOrganizados = array();
foreach ($aLogs as $oLog) {

  $sMensagem = "";
  if ( $oLog->tipo != 'ERRO' ) {
    continue;
  }
  switch ($oLog->iTipo) {

    case 1:
      $sMensagem = "CNES : {$oLog->sCNES} - ". urldecode($oLog->sMensagem);
      break;
    case 2:

      $sMensagem = "CPF: {$oLog->iCpf} - " . urldecode($oLog->sNome) . " - " . urldecode($oLog->sMensagem);
      break;

    case 3:
      $sMensagem = "CPF: {$oLog->iCpf} - " . urldecode($oLog->sNome) . " - " . urldecode($oLog->sMensagem);
      break;
  }
  $aDadosOrganizados[$oLog->iTipo][] = $sMensagem;
}

if (file_exists($sArquivoLog)) {
  unlink($sArquivoLog);
}
if (file_exists($sArquivoLogNovos)) {
  unlink($sArquivoLogNovos);
}

$head1 = "LOG DE ERROS NA IMPORTAÇÃO DO ARQUIVO SCNES";

$oPdf = new FpdfMultiCellBorder();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->exibeHeader(true);
$oPdf->setFillColor(220);
$oPdf->SetAutoPageBreak(false, 15);
$oPdf->AddPage();

foreach ($aDadosOrganizados as $iTipo => $aLog) {

  $oPdf->SetFont('Arial','B',7);

  $sTitulo = "Dados Unidade";

  switch ($iTipo) {
    case 1:

      $sTitulo = "Dados Unidade";
      break;
    case 2:

      $sTitulo = "Dados do Profissional";
      break;
    case 3:
      $sTitulo = "Dados do Vínculo do Profissional";
      break;
  }

  imprimeCabecalho($oPdf, $sTitulo);

  $oPdf->SetFont('Arial','',7);
  foreach ( $aLog as $sMensagem ) {

    if ( $oPdf->getY() >= $oPdf->h - 12) {

      $oPdf->AddPage();
      imprimeCabecalho($oPdf, $sTitulo);
    }
    $oPdf->MultiCell(192, 4, $sMensagem, 1, "L");
  }
  $oPdf->Ln();

}

$oPdf->Output();

function imprimeCabecalho($oPdf, $sTitulo) {

  $oPdf->SetFont('Arial','B',7);
  $oPdf->cell(192, 4, $sTitulo, 1, 1, 'C', 1 );
  $oPdf->SetFont('Arial','',7);
}
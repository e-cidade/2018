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

require_once(modification("fpdf151/fpdf.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("std/DBDate.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oDados      = db_utils::postMemory($_GET);
$oJson       = new Services_JSON();
$oLogArquivo = $oJson->decode(file_get_contents($oDados->sArquivo));

$oPdf = new FpdfMultiCellBorder();
$oPdf->exibeHeader(true);
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetFillColor(235);
$oPdf->SetAutoPageBreak(true, 20);

$head1 = "Alunos que possuem proporcionalidade configurada.";

foreach ($oLogArquivo->aLogs as $oDados) {

  if ( count($oDados->aAlunos) == 0 ) {
    continue;
  }

  $oPdf->AddPage();
  $oPdf->SetFont("arial", "b", 7);

  $oPdf->Cell(80,   4, "Turma: "      . urldecode( $oDados->sTurma ),      1, 0, "C", 1);
  $oPdf->Cell(40,   4, "Etapa: "      . urldecode( $oDados->sEtapa ),      1, 0, "C", 1);
  $oPdf->Cell(72,   4, "Calendário: " . urldecode( $oDados->sCalendario ), 1, 1, "C", 1);
  $oPdf->Cell(192,  4, "Alunos", 1, 1, "C", 1);

  foreach ($oDados->aAlunos as $sAluno) {

    $oPdf->SetFont("arial", "", 7);
    $oPdf->Cell(192,  4, urldecode($sAluno), 1, 1, "L");
  }
}

$oPdf->Output();
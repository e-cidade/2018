<?php
/**
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("fpdf151/PDFDocument.php"));

$oGet             = db_utils::postMemory($_GET);
$aInconsistencias = (array) DBString::utf8_decode_all(json_decode(file_get_contents('tmp/servidores_inconsistencia.json')));

$filtros                     = new stdClass();
$filtros->colunaMatricula    = 20;
$filtros->colunaNome         = 170;
$filtros->limiteAlturaPagina = 275;

$oPdf = new PDFDocument();
$oPdf->addHeaderDescription("RELATÓRIO DE MATRÍCULAS COM INCONSISTÊNCIA NO PONTO ELETRÔNICO");
$oPdf->addHeaderDescription("");
if (!empty($oGet->data)) {
  $oPdf->addHeaderDescription("Data: {$oGet->data}");
}

$oPdf->Open();
$oPdf->SetFillColor(225);
$oPdf->SetAutoPageBreak(false);

$oPdf->AddPage();
$oPdf->setFontFamily('arial');
$oPdf->SetFontSize(8);

foreach ($aInconsistencias as $inconsistencia) {

  imprimeCabecalho($oPdf, $filtros, $inconsistencia->titulo);

  foreach($inconsistencia->matriculas as $servidor) {

    if($oPdf->GetY() > $filtros->limiteAlturaPagina) {

      $oPdf->AddPage();
      imprimeCabecalho($oPdf, $filtros, $inconsistencia->titulo);
    }

    $oPdf->Cell($filtros->colunaMatricula, 4, $servidor->matricula, 1, 0, 'R');
    $oPdf->Cell($filtros->colunaNome,      4, $servidor->nome,      1, 1, 'L');
  }

  $oPdf->Ln(4);
}

$oPdf->showPDF();

/**
 * @param PDFDocument $oPdf
 * @param $filtros
 * @param $titulo
 */
function imprimeCabecalho(PDFDocument $oPdf, $filtros, $titulo) {

  $oPdf->setBold(true);

  $oPdf->MultiCell(190, 4, "Inconsistência: {$titulo}", 1, 'L', 1);
  $oPdf->Cell($filtros->colunaMatricula, 4, 'Matrícula', 1, 0, 'C', 1);
  $oPdf->Cell($filtros->colunaNome,      4, 'Nome',      1, 1, 'C', 1);

  $oPdf->setBold(false);
}
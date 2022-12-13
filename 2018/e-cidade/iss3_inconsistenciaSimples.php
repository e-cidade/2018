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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("fpdf151/PDFDocument.php");

$total        = 0;
$troca        = 1;
$iAlturalinha = 4;
$iFonte       = 6;

$pdf = new PDFDocument("P");
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(false);
$pdf->setfillcolor(235);
$pdf->setfont('arial', 'b', 6);

$pdf->addHeaderDescription("RELATÓRIO DE INCONSISTÊNCIAS");
$pdf->addHeaderDescription("Arquivo Processamento do Simples");

$pdf->AddPage("P");

$pdf->setfont('arial','b',$iFonte);
$pdf->cell(25 ,  $iAlturalinha, "TIPO",     "TBR",  0, "C", 1);
$pdf->cell(50 ,  $iAlturalinha, "REGISTRO", "LTBR", 0, "C", 1);
$pdf->cell(115,  $iAlturalinha, "DETALHE",  "TBL",  1, "C", 1);

/**
 * se haver erro ou aviso criamos ele na seção para o relatorio
 * e a variavel $iInconsistencia passa para 1
 */
$aInconsistencia  = db_getsession("aInconsistenciaSimples");
rsort($aInconsistencia);

foreach ($aInconsistencia as $oIndiceDados => $oValorDados) {

	$pdf->setfont('arial','',$iFonte);

	$iAltura = $pdf->getMultiCellHeight(115, $iAlturalinha, urldecode($oValorDados->sDetalhe));

	$pdf->cell(25 ,  $iAltura, "{$oValorDados->sTipo}"           ,  "TBR",  0, "L", 0);
	$pdf->cell(50 ,  $iAltura, urldecode($oValorDados->sCnpj), "LTBR", 0, "L", 0);
	$pdf->MultiCell(115,  $iAlturalinha, urldecode($oValorDados->sDetalhe) ,  "TBL", "L");
	imprimirCabecalho($pdf, $iAlturalinha, false);
}

$pdf->showPDF("inconsistencias_simples_" . time());

function imprimirCabecalho($oPdf, $iAlturalinha, $lImprime) {

	if ( $oPdf->GetY() > $oPdf->h - 25 || $lImprime ) {

		$oPdf->SetFont('arial', 'b', 6);

		if ( !$lImprime ) {

			$oPdf->AddPage("P");
		}

		$oPdf->setfont('arial','b',6);
		$oPdf->cell(25 ,  $iAlturalinha, "TIPO",     "TBR",  0, "C", 1);
		$oPdf->cell(50 ,  $iAlturalinha, "Cnpj / Cpf", "LTBR", 0, "C", 1);
		$oPdf->cell(115,  $iAlturalinha, "DETALHE",  "TBL",  1, "C", 1);

	}
}
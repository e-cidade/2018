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

require_once modification("libs/db_utils.php");
require_once(modification("dbforms/db_funcoes.php"));
require_once modification("fpdf151/FpdfMultiCellBorder.php");

$oGet = db_utils::postMemory($_GET);

$oGet->sAluno      = base64_decode($oGet->sAluno);
$oGet->sEscola     = base64_decode($oGet->sEscola);
$oGet->sEtapa      = base64_decode($oGet->sEtapa);
$oGet->sCurso      = base64_decode($oGet->sCurso);
$oGet->sTurno      = base64_decode($oGet->sTurno);
$oGet->sData       = base64_decode($oGet->sData);
$oGet->sObservacao = base64_decode($oGet->sObservacao);

$oUsuario = new UsuarioSistema(db_getsession('DB_id_usuario'));
$oData    = new DBDate($oGet->sData);

$oPdf = new FpdfMultiCellBorder();
$oPdf->exibeHeader(true);
$oPdf->setExibeBrasao(true);
$oPdf->mostrarRodape(true);
$oPdf->Open();
$oPdf->SetAutoPageBreak(false, 15);
$oPdf->AliasNbPages();

$head1 = "ATESTADO DE VAGA";
$head3 = "Emissor: " . $oUsuario->getNome();

$sTexto  = "               Atesto, para os devidos fins, que há vaga na etapa {$oGet->sEtapa}, ensino {$oGet->sCurso},";
$sTexto .= " turno {$oGet->sTurno}, no ano letivo de {$oData->getAno()}, para o(a) aluno(a) {$oGet->sAluno} neste";
$sTexto .= " estabelecimento de ensino.";

for( $i = 1; $i <= 2; $i++) {

  $oPdf->AddPage();
  $oPdf->SetY(50);
  $oPdf->SetFont('arial', 'B', 13);
  $oPdf->Cell(191, 8, "ATESTADO DE VAGA", 0, 1, 'C');
  $oPdf->ln();
  $oPdf->SetFont('arial', 'B', 8);
  $oPdf->MultiCell(191, 4, $sTexto, 0, 'J');

  $oPdf->ln(12);
  $oPdf->SetFont('arial', 'B', 9);
  $oPdf->Cell(191, 4, "{$oData->dataPorExtenso()}.", 0, 1, 'C');

  $oPdf->SetY(150);

  $oPdf->SetFont('arial', 'B', 8);
  $oPdf->Cell(191, 4, "______________________________________________________", 0, 1, 'C');
  $oPdf->Cell(191, 4, "Assinatura da Direção", 0, 1, 'C');

  if ( !empty($oGet->sObservacao) ) {

    $oPdf->SetY(200);
    $oPdf->SetFont('arial', 'B', 7);
    $oPdf->Cell(191, 4, "Observação:", 0, 1, 'L');
    $oPdf->SetFont('arial', '', 7);
    $oPdf->MultiCell(191, 4, $oGet->sObservacao, 0, 'J');
  }

}

$oPdf->Output();
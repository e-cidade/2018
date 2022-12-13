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

require_once "libs/db_stdlib.php";
require_once "libs/db_conecta.php";
require_once "libs/db_sessoes.php";
require_once "libs/db_usuariosonline.php";
require_once "dbforms/db_funcoes.php";
require_once "fpdf151/fpdf.php";

define('FPDF_FONTPATH','fpdf151/font/');

$oGet = db_utils::postMemory($_GET);

if (empty($oGet->numero_inicial)) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Processo inicial inválido.");
}

if (empty($oGet->numero_final)) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Processo final inválido.");
}

$oDaoProtProcesso = new cl_protprocesso();

$sSqlProcessos = $oDaoProtProcesso->sql_query( null,
                                               "*",
                                               "p58_numero::integer",
                                               "p58_numero::integer between {$oGet->numero_inicial} and {$oGet->numero_final} and p58_ano = " . db_getsession("DB_anousu"));
$rsProcessos   = $oDaoProtProcesso->sql_record($sSqlProcessos);

if (!$rsProcessos || !pg_num_rows($rsProcessos)) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum Processo encontrado.");
}

$oPdf = new fpdf("P", "mm", "A4");
$oPdf->open();
$oPdf->setLeftMargin(6);
$oPdf->setTopMargin(12.5);
$oPdf->SetAutoPageBreak(true, 12.5);

$oPdf->AddPage();
$oPdf->SetFont("Times", "b", 12);

$nDistX = 2.6;
$nDistY = 9;
$nLineHeight = 5;

for ($iRow = 0; $iRow < pg_num_rows($rsProcessos); $iRow++) {

  if (($iRow % 8) == 0 && $iRow) {
    $oPdf->addPage();
  }

  $oProcesso = db_utils::fieldsMemory($rsProcessos, $iRow);

  $oPdf->setY($oPdf->getY() + 5);

  $oPdf->cell(99.1, $nLineHeight, "Protocolo: {$oProcesso->p58_numero}/{$oProcesso->p58_ano}");
  $oPdf->setX($oPdf->getX() + $nDistX);
  $oPdf->cell(99.1, $nLineHeight, "Protocolo: {$oProcesso->p58_numero}/{$oProcesso->p58_ano}");

  $oPdf->ln();

  /**
   * Limita o tamanho do texto
   */
  $sDepartamento = $oProcesso->descrdepto;
  while ($oPdf->GetStringWidth($sDepartamento) > 97) {
    $sDepartamento = substr($sDepartamento, 0, strlen($sDepartamento)-1);
  }

  $oPdf->cell(99.1, $nLineHeight, $sDepartamento);
  $oPdf->setX($oPdf->getX() + $nDistX);
  $oPdf->cell(99.1, $nLineHeight, $sDepartamento);

  $oPdf->ln();

  $oPdf->cell(99.1, $nLineHeight, date("d/m/Y", strtotime($oProcesso->p58_dtproc)) . "   {$oProcesso->p58_hora}");
  $oPdf->setX($oPdf->getX() + $nDistX);
  $oPdf->cell(99.1, $nLineHeight, date("d/m/Y", strtotime($oProcesso->p58_dtproc)) . "   {$oProcesso->p58_hora}");

  $oPdf->ln();

  $iY = $oPdf->getY();

  $oPdf->multicell(98, $nLineHeight, $oProcesso->p58_requer, 0, 'L');

  $oPdf->setY($iY);
  $oPdf->setX(99.1 + $oPdf->getX() + $nDistX);

  $oPdf->multicell(98, $nLineHeight, $oProcesso->p58_requer, 0, 'L');

  $oPdf->ln();
  $oPdf->setY($iY + $nLineHeight + $nDistY);
}

Header('Content-disposition: inline; filename=etiquetas_processo_' . time() . '.pdf');
$oPdf->Output();
?>
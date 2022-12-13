<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
include_once("libs/db_sessoes.php");
require_once("fpdf151/pdfnovo.php");

$oGet = db_utils::postMemory($_GET);

$oPdf = new PDFNovo();

$oPdf->addTableHeader('BEM', 20, 4.5, 'C');
$oPdf->addTableHeader('DESCRI��O', 97, 4.5, 'C');
$oPdf->addTableHeader('CLASSIFICAC�O', 23, 4.5, 'C');
$oPdf->addTableHeader('DESCRI��O', 50, 4.5, 'C');

$oPdf->addHeader('RELAT�RIO DE BENS POR LOTE');
$oPdf->addHeader("LOTE: {$oGet->lote}");

$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->AddPage("p");
$oPdf->SetFillColor(235);

$oLote = new BemLote($oGet->lote);

foreach ($oLote->getBens() as $oBem) {

  $oClassificacao = $oBem->getClassificacao();

  $oPdf->cell(20, 4.5, $oBem->getCodigoBem(), 'B,L,R', 0, 'C');
  $oPdf->cell(97, 4.5, $oBem->getDescricao(), 'B,L,R');
  $oPdf->cell(23, 4.5, $oClassificacao->getClassificacao(), 'B,L,R', 0, 'C');
  $oPdf->cell(50, 4.5, $oClassificacao->getDescricao(), 'B,L,R');
  $oPdf->ln();
}

Header('Content-disposition: inline; filename=bens_lote_' . time() . '.pdf');
$oPdf->output();

?>
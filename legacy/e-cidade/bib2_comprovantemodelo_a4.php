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

require_once(modification("fpdf151/scpdf.php"));

$ov02_sequencial = $oDadosEmprestimos->aEmprestimos[0]->ov02_sequencial;
$ov02_nome       = $oDadosEmprestimos->aEmprestimos[0]->ov02_nome;
$bi17_nome       = $oDadosEmprestimos->aEmprestimos[0]->bi17_nome;
$barras          = str_pad($ov02_sequencial, 8, 0, STR_PAD_LEFT);

$oPdf = new scpdf();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetMargins(10, 10);

$oPdf->AddPage('P');

$iMeiaPagina  = ($oPdf->h/2);

for ($w = 1; $w <= 2; $w++) {

  if ( $w > 1) {

    $oPdf->setY($iMeiaPagina + 5);
    $iMeiaPagina = ($iMeiaPagina * 2) - 10; // diminui as margens
  }

  // posiciona e imprime o código de barras
  $oPdf->setfont('arial','b',8);
  $oPdf->setX(175);
  $oPdf->Cell(23, 3, $barras, 0, 1, 'C'); //numeros do codbarras
  $oPdf->int25(175, $oPdf->getY(), $barras, 15, 0.341);

  $oPdf->setfont('arial','b',8);
  $oPdf->Cell(190, 4, $bi17_nome, 0, 1);
  $oPdf->setfont('arial', 'b', 18);
  $oPdf->Cell(190, 9, $oDadosEmprestimos->sTitulo, 0, 1);

  $oPdf->ln(2);
  $oPdf->setfont('arial','b',7);
  $oPdf->Cell(20,  4, "Empréstimo", "B", 0);
  $oPdf->Cell(20,  4, "Retirada", "B", 0);
  $oPdf->Cell(20,  4, $oDadosEmprestimos->sLabel, "B", 0);
  $oPdf->Cell(95, 4, "Leitor", "B", 1);

  $oPdf->setfont('arial','',7);

  ///dados do emprestimo
  foreach ($oDadosEmprestimos->aEmprestimos as $oEmprestimo) {

    $oPdf->Cell(20,  4, $oEmprestimo->bi18_codigo, 0, 0);
    $oPdf->Cell(20,  4, db_formatar($oEmprestimo->bi18_retirada,'d'),  0, 0);
    $oPdf->Cell(20,  4, db_formatar($oEmprestimo->bi18_devolucao,'d'), 0, 0);
    $oPdf->Cell(110, 4, $barras." - ".$ov02_nome, 0, 1);
  }

  $oPdf->ln(4);
  //relaçao dos itens
  $oPdf->setfont('arial','b',7);
  $oPdf->Cell(190, 4, $oDadosEmprestimos->sSubTitulo, 0, 1);
  $oPdf->Cell(40,  4, "Cód. Barras", "B", 0);
  $oPdf->Cell(150, 4, "Acervo",      "B", 1);

  foreach ($oDadosEmprestimos->aAcervos as $oAcervo) {

    $oPdf->setfont('arial','',7);
    $oPdf->Cell(40,  4, $oAcervo->bi23_codbarras);
    $oPdf->Cell(150, 4, $oAcervo->bi06_titulo, 0, 1);
  }
  $oPdf->ln(2);
  $oPdf->setfont('arial','b',7);
  $oPdf->Cell(190, 4, "Total de ítens: " . count($oDadosEmprestimos->aAcervos), "T", 1) ;

  $oPdf->setY($iMeiaPagina - 20);

  // assinaturas
  $oPdf->Cell(90, 4, "", "B", 0);
  $oPdf->Cell(10, 4, "", "",  0);
  $oPdf->Cell(90, 4, "", "B", 1);
  $oPdf->Cell(90, 4, "ASSINATURA DO LEITOR", 0, 0, 'C');
  $oPdf->Cell(10, 4, "", "",  0);
  $oPdf->Cell(90, 4, "RESPONSÁVEL PELA BIBLIOTECA", 0, 1, 'C');

  //linha de corte
  if ($w == 1) {
    $oPdf->line(0, $iMeiaPagina - 5 , 220, $iMeiaPagina - 5);
  }
}

$oPdf->Output();

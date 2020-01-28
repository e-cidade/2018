<?php
$iAcervosEmprestados = count( $oDadosEmprestimos->aAcervos );

$iAltura  = 150;
$iAltura += ($iAcervosEmprestados * 4) * 2;

$oPdf = new scpdf('P','mm', array(80, $iAltura) );
$oPdf->Open();
$oPdf->SetAutoPageBreak( false, 1 );
$oPdf->SetMargins( 5, 5 );
$oPdf->SetFillColor( 240 );

for ( $iContador = 0; $iContador < 2; $iContador++ ) {

  $oPdf->AddPage();

  $oPdf->setfont('arial', 'b', 7);
  $oPdf->MultiCell(72, 4, $oDadosEmprestimos->aEmprestimos[0]->bi17_nome, 0, 'C' );

  $oPdf->setfont('arial', 'b', 8);
  $oPdf->cell(72, 4, $oDadosEmprestimos->sTitulo,   0, 1, 'C' );

  $oPdf->setfont('arial', 'b', 6);
  $oPdf->cell(72/3, 4, "Empréstimo",         0, 0, 'L' );
  $oPdf->cell(72/3, 4, "Retirada",           0, 0, 'L' );
  $oPdf->cell(72/3, 4, $oDadosEmprestimos->sLabel, 0, 1, 'L' );
  $oPdf->Line(5, $oPdf->GetY(), 77, $oPdf->GetY());

  $oPdf->setfont('arial', '', 6);
  foreach ( $oDadosEmprestimos->aEmprestimos as $oEmprestimo ) {

    $oPdf->cell(72/3, 4, $oEmprestimo->bi18_codigo,    0, 0, 'L' );
    $oPdf->cell(72/3, 4, db_formatar($oEmprestimo->bi18_retirada, 'd'),  0, 0, 'L' );
    $oPdf->cell(72/3, 4, db_formatar($oEmprestimo->bi18_devolucao, 'd'), 0, 1, 'L' );
  }

  $oPdf->setfont('arial', '', 7);
  $oPdf->Ln(4);
  $oPdf->cell(72, 4, "{$oDadosEmprestimos->sSubTitulo}", 0, 1, 'C' );

  $oPdf->setfont('arial', 'b', 6);
  $oPdf->cell(20, 4, "Cód. Barras",  0, 0, 'L' );
  $oPdf->cell(52, 4, "Acervo", 0, 1, 'L' );
  $oPdf->Line(5, $oPdf->GetY(), 77, $oPdf->GetY());

  $oPdf->setfont('arial', '', 6);
  $lPreencher = true;

  foreach ( $oDadosEmprestimos->aAcervos as $oAcervo ) {

    $iLinhas = $oPdf->NbLines(52, $oAcervo->bi06_titulo);
    $iAltura = 4;
    if ($iLinhas > 1) {
      $iAltura *= $iLinhas;
    }

    $lPreencher = !$lPreencher;
    $oPdf->cell(20, $iAltura, $oAcervo->bi23_codbarras, 0, 0, 'L', $lPreencher );
    $oPdf->MultiCell(52, 4, $oAcervo->bi06_titulo, 0, 'L', $lPreencher );
  }

  $oPdf->Line(5, $oPdf->GetY(), 77, $oPdf->GetY());
  $oPdf->cell(72, 4, "Total de itens: {$iAcervosEmprestados}", 0, 1, 'R' );
  $oPdf->Ln(8);

  $oPdf->MultiCell(72, 4, $oDadosEmprestimos->aEmprestimos[0]->ov02_nome, 0, 'C' );
  $sCodigoBarras = str_pad($oDadosEmprestimos->aEmprestimos[0]->ov02_sequencial, 8, 0, STR_PAD_LEFT);

  $oPdf->SetFillColor( 000 );
  $oPdf->int25(24, $oPdf->getY(), $sCodigoBarras, 10, 0.5);
  $oPdf->SetFillColor( 240 );
  $oPdf->Ln(10);
  $oPdf->cell( 72, 4, $sCodigoBarras, 0, 1, 'C' );

  $oPdf->Ln(12);
  $oPdf->Line(5, $oPdf->GetY(), 77, $oPdf->GetY());
  $oPdf->cell( 72, 4, "ASSINATURA DO USUÁRIO", 0, 1, 'C' );

  $oPdf->Ln(12);
  $oPdf->Line(5, $oPdf->GetY(), 77, $oPdf->GetY());
  $oPdf->cell( 72, 4, "RESPONSÁVEL PELA BIBLIOTECA", 0, 1, 'C' );
  $oPdf->Ln(30);

}
$oPdf->Output();

<?php
require_once(modification("libs/db_utils.php"));
require_once(modification("fpdf151/pdf.php"));

parse_str(base64_decode($_GET['q']), $aGet);
define('MSG_LINHA', 'educacao.transporteescolar.tre2_linha002.');

/**
 * Guarda os dados de forma organizada para imprimir no relatorio
 */
$oDados = new stdClass();
try {

  if ( $aGet["iLinha"] == "") {
    throw new Exception( _M(MSG_LINHA . "informe_linha") );
  }

  if ( $aGet["iItinerario" ] === "") {
    throw new Exception( _M(MSG_LINHA . "informe_itinerario") );
  }

  $oDados->sLinha       = $aGet["sLinha"];
  $oDados->aItinerarios = array();

  $oLinha       = new LinhaTransporte($aGet["iLinha"]);
  $aItinerarios = $oLinha->getItinerarios();

  if ( count($aItinerarios) == 0 ) {
    throw new Exception( _M(MSG_LINHA . "informe_itinerario") );
  }

  $iItinerarioSelecionado = $aGet["iItinerario"];
  $aTemLogradouros        = array();
  foreach ($aItinerarios as $oItinerario) {

    if ( (int)$iItinerarioSelecionado !== 0 && $iItinerarioSelecionado != $oItinerario->getTipo() ) {
      continue;
    }

    $oDadosItinerario               = new stdClass();
    $oDadosItinerario->iTipo        = $oItinerario->getTipo();
    $oDadosItinerario->sTipo        = $oItinerario->getDescricaoTipo();
    $oDadosItinerario->aLogradouros = array();
    $oDadosItinerario->aHorarios    = array();

    $aLogradouros = $oItinerario->getLogradouros();
    $aTemLogradouros[] = count($aLogradouros) > 0;
    foreach ($aLogradouros as $oLogradouro) {

      $oLogradouroBairro = $oLogradouro->getLogradouroBairro();

      $oDadosLogradouro              = new stdClass();
      $oDadosLogradouro->iOrdem      = $oLogradouro->getOrdem();
      $oDadosLogradouro->sBairro     = $oLogradouroBairro->getBairro()->getDescricao();
      $oDadosLogradouro->sLogradouro = $oLogradouroBairro->getLogradouro()->getDescricao();
      $oDadosLogradouro->aParada     = array();

      $aParadas = $oLogradouro->getPontosDeParada();
      foreach ($aParadas as $oParada) {
        $oDadosLogradouro->aParada[] = $oParada->getPontoParada()->getNome();
      }

      $oDadosItinerario->aLogradouros[] = $oDadosLogradouro;
    }

    if ( !in_array(true, $aTemLogradouros) ) {
      throw new Exception( _M(MSG_LINHA . "sem_itinerario_cadastrado") );
    }

    $aHorarios = $oItinerario->getHorarios();
    foreach ($aHorarios as $oHorario) {

      $oDadoHorario                   = new stdClass();
      $oDadoHorario->sSaida           = $oHorario->getHoraSaida();
      $oDadoHorario->sChegada         = $oHorario->getHoraChegada();
      $oDadosItinerario->aHorarios [] = $oDadoHorario;
    }

    $oDados->aItinerarios[] = $oDadosItinerario;
  }

} catch (Exception $e) {

  $sMsg = urlencode($e->getMessage());
  db_redireciona('db_erros.php?fechar=true&db_erro=' . $sMsg);
}

$head1 = "RELATÓRIO DE LINHAS";
$head2 = "Linha:     {$aGet["sLinha"]}";

$oPdf = new PDF();
$oPdf->Open();
$oPdf->SetAutoPageBreak(false,10);
$oPdf->AliasNbPages();
$oPdf->SetFillColor(240);

$oPdf->SetFont('arial', 'B', 8);

foreach ($oDados->aItinerarios as $oItinerario) {

  if ( count($oItinerario->aLogradouros) == 0) {
    continue;
  }
  adicionaPagina($oPdf, $oItinerario->sTipo);

  $oPdf->SetFont('arial', '', 8);
  $lPrimeriraPagina = true;
  foreach( $oItinerario->aLogradouros as $oLogradouro ) {

    if ( $lPrimeriraPagina ) {

      imprimeCabecalhoLogradouro($oPdf);
      $lPrimeriraPagina = false;
    }

    $sPontoParada   = implode("\n", $oLogradouro->aParada);

    $aTotalLinhas   = array();
    $aTotalLinhas[] = $oPdf->NbLines(60, $oLogradouro->sBairro);
    $aTotalLinhas[] = $oPdf->NbLines(80, $oLogradouro->sLogradouro);
    $aTotalLinhas[] = $oPdf->NbLines(39, $sPontoParada);

    $iAlturaLinha = max($aTotalLinhas) * 4; // calcula a aturaLinha

    $iTotalY = $oPdf->GetY() + $iAlturaLinha; // calcula posição do eixo Y após impressão e valida se necessita addPage
    if ($iTotalY > ($oPdf->h - 20)) {

      adicionaPagina($oPdf, $oItinerario->sTipo);
      imprimeCabecalhoLogradouro($oPdf);
    }

    $iYInicial = $oPdf->GetY();

    $iX = $oPdf->GetX();
    $iY = $oPdf->GetY();

    $oPdf->MultiCell(12, 4, $oLogradouro->iOrdem,      0, "C");
    $iX += 12;
    $oPdf->SetXY($iX, $iY);
    $oPdf->MultiCell(60, 4, $oLogradouro->sBairro,     0, "L");
    $iX += 60;
    $oPdf->SetXY($iX, $iY);
    $oPdf->MultiCell(80, 4, $oLogradouro->sLogradouro, 0, "L");
    $iX += 80;
    $oPdf->SetXY($iX, $iY);
    $oPdf->MultiCell(39, 4, $sPontoParada,             0, "L");

    $oPdf->SetY( $iYInicial + $iAlturaLinha);

    $oPdf->Line(10, $oPdf->GetY(), 201, $oPdf->GetY());

    $x = 10;
    $oPdf->Line($x,  $iYInicial, $x, $oPdf->GetY());
    $x += 12;
    $oPdf->Line($x,  $iYInicial, $x, $oPdf->GetY());
    $x += 60;
    $oPdf->Line($x,  $iYInicial, $x, $oPdf->GetY());
    $x += 80;
    $oPdf->Line($x,  $iYInicial, $x, $oPdf->GetY());
    $x += 39;
    $oPdf->Line($x,  $iYInicial, $x, $oPdf->GetY());
  }

  $oPdf->ln();

  $iLinhasHorarios = count($oItinerario->aHorarios);
  if ( $iLinhasHorarios > 0 ) {

    $iAltura = (($iLinhasHorarios * 4) + 4) + $oPdf->GetY();

    if ($iAltura > ($oPdf->h - 20)) {

      adicionaPagina($oPdf, $oItinerario->sTipo);
    }
  }

  $lPrimeriraPagina = true;
  foreach( $oItinerario->aHorarios as $oHorario ) {

    if ( $lPrimeriraPagina ) {

      $lPrimeriraPagina = false;
      imprimeCabecalhoHorarios($oPdf);
    }
    $oPdf->Cell(20, 4, $oHorario->sSaida,      1, 0, "C");
    $oPdf->Cell(20, 4, $oHorario->sChegada,    1, 1, "C");
  }
}

$oPdf->output();

function imprimeCabecalhoLogradouro($oPdf) {

  $oPdf->SetFont('arial', 'B', 8);
  $oPdf->Cell(12, 4, 'Ordem',           1, 0, "C", 1);
  $oPdf->Cell(60, 4, 'Bairro',          1, 0, "C", 1);
  $oPdf->Cell(80, 4, 'Logradouro',      1, 0, "C", 1);
  $oPdf->Cell(39, 4, 'Ponto de parada', 1, 1, "C", 1);
  $oPdf->SetFont('arial', '', 8);
}

function imprimeCabecalhoHorarios($oPdf) {

  $oPdf->SetFont('arial', 'B', 8);
  $oPdf->Cell(40, 4, 'Horários',        1, 1, "C", 1);
  $oPdf->Cell(20, 4, 'Saída',           1, 0, "C", 1);
  $oPdf->Cell(20, 4, 'Chegada',         1, 1, "C", 1);
  $oPdf->SetFont('arial', '', 8);
}

function adicionaPagina($oPdf, $sTipo) {

  $oPdf->addPage();
  $oPdf->SetFont('arial', 'B', 8);
  $oPdf->Cell(191, 5, $sTipo, 0, 1, "L");
}
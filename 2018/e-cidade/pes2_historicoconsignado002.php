<?php
require_once(modification("fpdf151/pdfnovo.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_utils.php"));

$oGet = db_utils::postMemory($_GET);
$oConsignado = ArquivoConsignadoManualRepository::getByCodigo($oGet->codigoconsignado);
$oCompetencia  = DBPessoal::getCompetenciaFolha();
/**
 * @var ArquivoConsignadoManual
 */
$aListaConsignados = array($oConsignado);
while ($oConsignado->getConsignadoOrigem() != '') {

  try {

    $oConsignado = $oConsignado->getConsignadoOrigem();
    array_unshift($aListaConsignados, $oConsignado);
  } catch (\Exception $e) {
    break;
  }
}
$aSituacao = array(
  "N" => 'Contratado',
  "P" => "Portabilidade",
  "R" => "Refinanciado",
  "C" => "Cancelado",
  "I" => "Inativo",
);

$aMotivos = array(
  '' => 'DESCONTADO EM FOLHA',
  1 => 'NÃO DESCONTADO - FALECIMENTO',
  2 => 'SERVIDOR NÃO IDENTIFICADO',
  3 => "TIPO DE CONTRATO NÃO PERMITE EMPRÉSTIMO",
  4 => "MARGEM CONSIGNÁVEL EXCEDIDA",
  5 => "NÃO DESCONTADO - OUTROS MOTIVOS",
  6 => "SERVIDOR DESLIGADO",
  7 => "SERVIDOR AFASTADO EM LICENÇA SAÚDE",
  8 => "EXCLUÍDO",
  9 => "NÃO DESCONTADO - SALDO INSUFICIENTE"
);
$aListaParcelas = array();
foreach ($aListaConsignados as $oConsignado) {

  $oDadosConsignado              = new \stdClass();
  $oDadosConsignado->banco       = $oConsignado->getBanco()->getCodigo()."- ".$oConsignado->getBanco()->getNome();
  $oDadosConsignado->parcelas    = array();
  $oDadosConsignado->situacao    = $aSituacao[$oConsignado->getSituacao()];
  $oDadosConsignado->rubrica     = "{$oConsignado->getRubrica()->getCodigo()} - {$oConsignado->getRubrica()->getDescricao()}";
  $oDadosConsignado->competencia = $oConsignado->getCompetencia()->getCompetencia();
  $aParcelas  = ArquivoConsignadoManualParcelaRepository::getParcelasDoFinanciamentoAteACompetencia($oConsignado, $oCompetencia);
  if (count($aParcelas) == 0) {
    continue;
  }
  
  foreach ($aParcelas as $oParcela) {

    $oDadosParcela = new \stdClass();
    $oDadosParcela->parcela       = $oParcela->getParcela()."/".$oParcela->getTotalDeParcelas();
    $oDadosParcela->competencia   = $oParcela->getCompetencia()->getCompetencia();
    $oDadosParcela->valor         = $oParcela->getValor();
    $oDadosParcela->motivo        = $aMotivos[$oParcela->getMotivo()];
    if (!$oParcela->isProcessado()) {
      $oDadosParcela->motivo = 'PENDENTE DE PROCESSAMENTO';
    }
    $oDadosConsignado->parcelas[] = $oDadosParcela;
  }
  $aListaParcelas[] = $oDadosConsignado;

}

$oPdf = new PDFNovo();
$oPdf->Open();
$oPdf->addHeader( "Histórico do Consignado" );
$oPdf->addHeader( "Servidor: {$oConsignado->getServidor()->getMatricula()} - {$oConsignado->getServidor()->getCgm()->getNome()}" );
$oPdf->addHeader( "Consignado: {$oConsignado->getCodigo()}");
$oPdf->addHeader( "Posição Até: {$oCompetencia->getCompetencia()}");
$oPdf->AliasNbPages();
$oPdf->setHeaderMargin(0.2);
$oPdf->SetFillColor(235);
$iAltura = 4;

$oPdf->addTableHeader('Parcela', 20, $iAltura, 'C', true);
$oPdf->addTableHeader('Banco', 50, $iAltura, 'C', true);
$oPdf->addTableHeader('Valor', 30, $iAltura, 'C', true);
$oPdf->addTableHeader('Competência', 20, $iAltura, 'C', true);
$oPdf->addTableHeader('Situação', 70, $iAltura, 'C', true);
$oPdf->AddPage();
foreach ($aListaParcelas as $oConsignado) {

  $oPdf->SetFont($oPdf->FontFamily, 'B', $oPdf->FontSizePt);

  $sCompetencia = $oConsignado->competencia;

  if($oConsignado->situacao == 'Cancelado') {
    $sCompetencia = $oConsignado->parcelas[count($oConsignado->parcelas)-1]->competencia;
  }

  $oPdf->Cell(190, $iAltura, "Situação: ".$oConsignado->situacao." em ". $sCompetencia. "  Rubrica: ".$oConsignado->rubrica, "B", 1, "L");
  $oPdf->SetFont($oPdf->FontFamily, '', $oPdf->FontSizePt);

  foreach ($oConsignado->parcelas as $parcela) {
    
    if($oConsignado->situacao == 'Cancelado' && $parcela->motivo == 'PENDENTE DE PROCESSAMENTO') {
      continue;
    }

    $oPdf->Cell(20, $iAltura, "Parcela: ".$parcela->parcela, 0, 0,"R");
    $oPdf->Cell(50, $iAltura, $oConsignado->banco , 0, 0,"L");
    $oPdf->Cell(30, $iAltura, db_formatar($parcela->valor == '' ? 0 : $parcela->valor, 'f'), 0, 0,"R");
    $oPdf->Cell(20, $iAltura, $parcela->competencia, 0, 0,"C");
    $oPdf->Cell(70, $iAltura, $parcela->motivo, 0, 1,"L");
  }
}
$oPdf->Output();
$iAltura = 4;

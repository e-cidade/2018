<?php
require_once(modification("fpdf151/pdfnovo.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_utils.php"));

$oGet = db_utils::postMemory($_GET);


$oTipoAssentamento   = TipoAssentamentoRepository::getInstanciaPorCodigo($oGet->tipo_assentamento);
$oInstituicao        = InstituicaoRepository::getInstituicaoByCodigo(db_getsession("DB_instit"));
$oSelecao            = new Selecao($oGet->selecao);
$oAgendaAssentamento = AgendaAssentamentoRepository::getInstanciaPorTipoSelecaoInstituicao($oTipoAssentamento, $oSelecao, $oInstituicao);
$aServidores         = ServidorRepository::getServidoresBySelecao(DBPessoal::getAnoFolha(),
  DBPessoal::getMesFolha(),
  $oGet->selecao
);


$aServidoresComDireito = array();
$oDataInicio = new DBDate($oGet->data_inicio);
$oDataFim    = new DBDate($oGet->data_fim);
foreach ($aServidores as $oServidor) {

  $oFormula             = new DBFormulaServidorAgendaAssentamentos($oServidor);
  $sFormulaCondicao     = "[". $oAgendaAssentamento->getNomeFormulaCondicao() ."] as condicao";
  $sFormulaDataInicio   = "([". $oAgendaAssentamento->getNomeFormulaInicio() ."])::date as data_inicio";
  $sFormulaDataFim      = "null as data_direito";
  if ($oAgendaAssentamento->getNomeFormulaFim() != '') {
    $sFormulaDataFim      = "[". $oAgendaAssentamento->getNomeFormulaFim() ."] as data_direito";
  }

  $sFormulaFaltas      = "null as afastamentos";
  if ($oAgendaAssentamento->getNomeFormulaFaltasPeriodo() != '') {
    $sFormulaFaltas      = "[". $oAgendaAssentamento->getNomeFormulaFaltasPeriodo() ."] as afastamentos";
  }

  $sFormulaProrrogaFim      = "null as data_prevista";
  if ($oAgendaAssentamento->getNomeFormulaProrrogaFim() != '') {
    $sFormulaProrrogaFim      = "[". $oAgendaAssentamento->getNomeFormulaProrrogaFim() ."] as data_prevista";
  }

  $sSqlCondicaoServidor = $oFormula->parse("SELECT {$sFormulaCondicao}, {$sFormulaDataInicio}, {$sFormulaDataFim}, {$sFormulaFaltas}, {$sFormulaProrrogaFim}");
  $rsCondicaoServidor   = db_query($sSqlCondicaoServidor);

  if(!$rsCondicaoServidor) {
    throw new BusinessException(_M(MENSAGEM .'erro_executar_formula_condicao'));
  }

  if (pg_num_rows($rsCondicaoServidor) > 0) {

    $oDadosCondicao = db_utils::fieldsMemory($rsCondicaoServidor, 0);

    $sDataVerificar = $oDadosCondicao->data_direito;
    if (empty($oDadosCondicao->data_direito)) {
      $sDataVerificar = $oDadosCondicao->data_inicio;
    }
    if (!empty($oDadosCondicao->data_prevista)) {
      $sDataVerificar = $oDadosCondicao->data_prevista;
    }

    if (empty($sDataVerificar) ) {
      continue;
    }

    $oDataVerificar = new DBDate($sDataVerificar);

    if (!DBDate::dataEstaNoIntervalo($oDataVerificar, $oDataInicio, $oDataFim)) {
      continue;
    }
    if (empty($oDadosCondicao->data_prevista) && empty($oDadosCondicao->data_direito)) {
      $oDadosCondicao->data_prevista = $oDadosCondicao->data_inicio;
    }
    $oStdServidorComDireito                  = new \stdClass;
    $oStdServidorComDireito->iMatricula      = $oServidor->getMatricula();
    $oStdServidorComDireito->condicao        = $oDadosCondicao->condicao == 0 ? 'Sem Direito' : ' Tem Direito';
    $oStdServidorComDireito->data_direito        = $oDadosCondicao->data_direito;
    $oStdServidorComDireito->afastamentos    = $oDadosCondicao->afastamentos;
    $oStdServidorComDireito->data_prevista = $oDadosCondicao->data_prevista;
    $oStdServidorComDireito->data_concessao  = $oDataVerificar;
    $oStdServidorComDireito->sNome           = $oServidor->getCgm()->getNome();

    $aServidoresComDireito[] = $oStdServidorComDireito;
  }

  uasort($aServidoresComDireito, function ($oServidorAtual, $oServidor) {
    return $oServidorAtual->data_concessao->getTimeStamp() > $oServidor->data_concessao->getTimeStamp();
  });
}
$oPdf = new PDFNovo();
$oPdf->addHeader( "Previsão de Direitos" );
$oPdf->addHeader( "Seleção: {$oSelecao->getDescricao()}" );
$oPdf->addHeader( "Assentamento: {$oTipoAssentamento->getDescricao()}");
$oPdf->addHeader( "Período: ".$oDataInicio->getDate(DBDate::DATA_PTBR)." a ".$oDataFim->getDate(DBDate::DATA_PTBR));

$iAltura = 4;

$oPdf->addTableHeader('Matrícula', 20, $iAltura, 'C', true);
$oPdf->addTableHeader('Nome', 98, $iAltura, 'C', true);
$oPdf->addTableHeader('Data de Direito', 25, $iAltura, 'C', true);
$oPdf->addTableHeader('Nº dias Afastado', 25, $iAltura, 'C', true);
$oPdf->addTableHeader('Data Prevista', 25, $iAltura, 'C', true);

$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->setHeaderMargin(0.2);
$oPdf->SetFillColor(235);
$oPdf->AddPage();

$lPreenche = false;
foreach ($aServidoresComDireito as $oServidor) {

  $oPdf->SetFont('arial','',7);
  $oPdf->Cell(20, $iAltura, $oServidor->iMatricula, 0, 0, "C");
  $oPdf->Cell(98, $iAltura, $oServidor->sNome, 0, 0, "L");
  $oPdf->Cell(25, $iAltura, db_formatar($oServidor->data_direito, 'd'), 0, 0, "C");
  $oPdf->Cell(25, $iAltura, $oServidor->afastamentos, 0, 0, "R");
  $oPdf->Cell(25, $iAltura, db_formatar($oServidor->data_prevista, 'd'), 0, 1, "C");
}

$oPdf->Cell(168, $iAltura, 'Total de Servidores:', 'T', 0, 'R');
$oPdf->Cell(25, $iAltura, count($aServidoresComDireito), 'T', 0, 'R');
$oPdf->Output();
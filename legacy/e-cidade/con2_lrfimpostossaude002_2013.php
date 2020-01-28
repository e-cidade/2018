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

require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once('libs/db_app.utils.php');
require_once('libs/db_libcontabilidade.php');
require_once('libs/db_liborcamento.php');
require_once('classes/db_db_config_classe.php');
require_once("dbforms/db_funcoes.php");

db_app::import("linhaRelatorioContabil");
db_app::import("relatorioContabil");
db_app::import("contabilidade.relatorios.AnexoXVILRF");

$oGet              = db_utils::postMemory($_GET);
$iAnoUsu           = db_getsession('DB_anousu');
$sInstituicoes     = db_getsession("DB_instit");
$cldb_config       = new cl_db_config;

$oReltorioContabil = new relatorioContabil(124, false);

$oAnexoXVI         = new AnexoXVILRF($iAnoUsu, 124,  $oGet->periodo);
$oAnexoXVI->setInstituicoes($sInstituicoes);

$aDados = $oAnexoXVI->getDados();

$iNumRows = count($aDados);
if ($iNumRows == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
}

/**
 * Adiciona nome abreviado das instituições selecionadas
 */
$sWhere           = "codigo in({$sInstituicoes})";
$sSqlDbConfig     = $cldb_config->sql_query_file(null, "nomeinstabrev", 'codigo', $sWhere);
$rsSqlDbConfig    = $cldb_config->sql_record($sSqlDbConfig);
$iNumRowsDbConfig = $cldb_config->numrows;
if ($iNumRowsDbConfig == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Instituição não informada.');
}

$sNomeInstAbrev = "";
$sVirgula       = "";
for ($iInd = 0; $iInd < $iNumRowsDbConfig; $iInd++) {

  $oMunicipio      = db_utils::fieldsMemory($rsSqlDbConfig, $iInd);
  $sNomeInstAbrev .= $sVirgula.$oMunicipio->nomeinstabrev;
  $sVirgula        = ", ";
}

/**
 * Adiciona descrição do periodo selecionado
 */
$sDescricaoPeriodo = "";
$aPeriodos         = $oReltorioContabil->getPeriodos();
foreach ($aPeriodos as $oPeriodo) {

	if ($oPeriodo->o114_sequencial == $oGet->periodo) {
    $sDescricaoPeriodo = $oPeriodo->o114_descricao;
	}
}

$head1 = "RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA";
$head2 = "DEMONSTRATIVO DAS RECEITAS E DESPESAS COM AÇÕES E SERVIÇOS PÚBLICOS DE SAÚDE";
$head3 = "ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
$head4 = "EXERCÍCIO: {$iAnoUsu}";
$head5 = "INSTITUIÇÃO: {$sNomeInstAbrev}";
$head7 = "ANEXO XII - PERÍODO: {$sDescricaoPeriodo}";

$oPdf   = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(true, 30);
$oPdf->AddPage("P");
$oPdf->SetFillColor(235);

$iTamFonte       = 6;
$iAltCell        = 4;
$iPosX           = 0;
$iPosicaoReceita = 0;
$iPosicaoDespesa = 0;

$oPdf->SetFont('arial', '', 6);
$oPdf->Cell(120, $iAltCell, "RREO - ANEXO 12 (LC 141/2012, art. 35)", '', 0, "L", 0);
$oPdf->Cell(70, $iAltCell, "R$ 1,00",  '', 1, "R", 0);

//echo ("<pre>".print_r($aDados, 1)."</pre>");die();

// * Percorre todos as linhas do relatório para o primeiro cabeçalho
imprimePrimeiroCabecalho($oPdf, $iAltCell, true);

$oPdf->SetFont('arial', '', 6);

for ($iLinhaPrimeiroCabecalho = 1; $iLinhaPrimeiroCabecalho <= 19; $iLinhaPrimeiroCabecalho++) {

  $oDados       = $aDados[$iLinhaPrimeiroCabecalho];
  $lTotalizador = $oDados->totalizar;
  $sDescricao   = setIdentacao($oDados->nivellinha).$oDados->descricao;
  $oPdf->SetFont('arial', '', 6);

  if ($lTotalizador == 1) {
    $oPdf->SetFont('arial', 'b', 6);
  }

  $sTopo = "";
  if ($iLinhaPrimeiroCabecalho == 19) {
    $sTopo = "T";
  }

  $nValorInicial    = db_formatar($oDados->prev_ini  , "f");
  $nValorAtual      = db_formatar($oDados->prev_atual, "f");
  $nValorBimestral  = db_formatar($oDados->rec_atebim, "f");
  $nValorPercentual = db_formatar($oDados->percentual, "f");

  $oPdf->Cell(120, $iAltCell, $sDescricao      , 'R'.$sTopo, 0, "L", 0);
  $oPdf->Cell(20, $iAltCell,  $nValorInicial   , 'R'.$sTopo, 0, "R", 0);
  $oPdf->Cell(20, $iAltCell,  $nValorAtual     , 'R'.$sTopo, 0, "R", 0);
  $oPdf->Cell(15, $iAltCell,  $nValorBimestral , 'R'.$sTopo, 0, "R", 0);
  $oPdf->Cell(15, $iAltCell,  $nValorPercentual,  ''.$sTopo, 1, "R", 0);
}

$oPdf->Cell(190, $iAltCell,  '', 'T', 1,  "R", 0);
$oPdf->ln();
//==============================================================================================

imprimeSegundoCabecalho($oPdf, $iAltCell, true);

$oPdf->SetFont('arial', '', 6);
for ($iLinhaPrimeiroCabecalho = 20; $iLinhaPrimeiroCabecalho <= 28; $iLinhaPrimeiroCabecalho++) {

  $oDados       = $aDados[$iLinhaPrimeiroCabecalho];
  $lTotalizador = $oDados->totalizar;
  $sDescricao   = setIdentacao($oDados->nivellinha).$oDados->descricao;
  $oPdf->SetFont('arial', '', 6);

  if ($lTotalizador == 1) {
    $oPdf->SetFont('arial', 'b', 6);
  }

  $sTopo = "";
  if ($iLinhaPrimeiroCabecalho == 28) {
    $sTopo = "T";
  }

  $nValorInicial    = db_formatar($oDados->prev_ini  , "f");
  $nValorAtual      = db_formatar($oDados->prev_atual, "f");
  $nValorBimestral  = db_formatar($oDados->rec_atebim, "f");
  $nValorPercentual = db_formatar($oDados->percentual, "f");

  $oPdf->Cell(120, $iAltCell, $sDescricao,       'R'.$sTopo, 0, "L", 0);
  $oPdf->Cell(20, $iAltCell,  $nValorInicial   , 'R'.$sTopo, 0, "R", 0);
  $oPdf->Cell(20, $iAltCell,  $nValorAtual     , 'R'.$sTopo, 0, "R", 0);
  $oPdf->Cell(15, $iAltCell,  $nValorBimestral , 'R'.$sTopo, 0, "R", 0);
  $oPdf->Cell(15, $iAltCell,  $nValorPercentual,  ''.$sTopo, 1,  "R", 0);
}
$oPdf->Cell(190, $iAltCell,  '', 'T', 1,  "R", 0);
$oPdf->ln();
//==============================================================================================

if (in_array($oGet->periodo, array(11, 13))) {
  imprimirCabecalhoDespesaSaudeUltimoPeriodo($oPdf, $iAltCell, true, $oGet->periodo);
} else {
  imprimeTerceiroCabecalho($oPdf, $iAltCell, true, $oGet->periodo);
}
for ($iLinhaPrimeiroCabecalho = 29; $iLinhaPrimeiroCabecalho <= 37; $iLinhaPrimeiroCabecalho++) {

  $oDados       = $aDados[$iLinhaPrimeiroCabecalho];
  $lTotalizador = $oDados->totalizar;
  $sDescricao   = setIdentacao($oDados->nivellinha).$oDados->descricao;
  $oPdf->SetFont('arial', '', 6);

  if ($lTotalizador == 1) {
    $oPdf->SetFont('arial', 'b', 6);
  }

  $sTopo = "";
  if ($iLinhaPrimeiroCabecalho == 37) {
    $sTopo = "T";
  }

  $nDot_ini              = db_formatar($oDados->dot_ini    , 'f');
  $nDot_atual            = db_formatar($oDados->dot_atual  , 'f');
  $nEmp_atebim           = db_formatar($oDados->emp_atebim , 'f');
  $nLiq_atebim           = db_formatar($oDados->liq_atebim , 'f');
  $nInscritosRP          = db_formatar($oDados->insc_rp_np , 'f');
  $nPercentualLiquidado  = db_formatar($oDados->percentualLiquidado, 'f');
  $nPercentualEmpenhado  = db_formatar($oDados->percentualEmpenhado, 'f');

  $oPdf->Cell(70, $iAltCell, $sDescricao           , 'R'.$sTopo, 0, "L", 0);
  $oPdf->Cell(20, $iAltCell, $nDot_ini             , 'R'.$sTopo, 0, "R", 0);
  $oPdf->Cell(20, $iAltCell, $nDot_atual           , 'R'.$sTopo, 0, "R", 0);
  if (!in_array($oGet->periodo, array(11, 13))) {

    $oPdf->Cell(20, $iAltCell, $nEmp_atebim, 'R' . $sTopo, 0, "R", 0);
    $oPdf->Cell(20, $iAltCell, $nPercentualEmpenhado, 'R' . $sTopo, 0, "R", 0);
    $oPdf->Cell(20, $iAltCell, $nLiq_atebim, 'R' . $sTopo, 0, "R", 0);
    $oPdf->Cell(20, $iAltCell, $nPercentualLiquidado, '' . $sTopo, 1, "R", 0);

  } else {

    $oPdf->Cell(26, $iAltCell, $nLiq_atebim, 'R' . $sTopo, 0, "R", 0);
    $oPdf->Cell(31, $iAltCell, $nInscritosRP, 'R' . $sTopo, 0, "R", 0);
    $oPdf->Cell(23, $iAltCell, $nPercentualLiquidado, '' . $sTopo, 1, "R", 0);
  }
}

//==============================================================================================

$oPdf->SetFont('arial', '', 5);
imprimeInfoProxPagina($oPdf, $iAltCell, 6);


//=================================================QUEBRA PAGINA ==============================

if (in_array($oGet->periodo, array(11, 13))) {
  imprimirCabecalhoDespesaSaudeNaoComputadasUltimoPeriodo($oPdf, $iAltCell, true, $oGet->periodo);
} else {
  imprimeQuartoCabecalho($oPdf, $iAltCell, true);
}

$oPdf->SetFont('arial', '', 6);
for ($iLinhaPrimeiroCabecalho = 38; $iLinhaPrimeiroCabecalho <= 49; $iLinhaPrimeiroCabecalho++) {

  $iPosYAntes   = $oPdf->getY();
  $iPosXAntes   = $oPdf->getX();
  $oDados       = $aDados[$iLinhaPrimeiroCabecalho];
  $lTotalizador = $oDados->totalizar;
  $sDescricao   = setIdentacao($oDados->nivellinha).$oDados->descricao;
  $oPdf->SetFont('arial', '', 6);

  if ($lTotalizador == 1 && $iLinhaPrimeiroCabecalho != 40) {
    $oPdf->SetFont('arial', 'b', 6);
  }

  $sTopo = "";
  if ($iLinhaPrimeiroCabecalho == 49 || $iLinhaPrimeiroCabecalho == 48) {
    $sTopo = "T";
  }


  $nDot_ini             = db_formatar($oDados->dot_ini   , 'f');
  $nDot_atual           = db_formatar($oDados->dot_atual , 'f');
  $nEmp_atebim          = db_formatar($oDados->emp_atebim, 'f');
  $nLiq_atebim          = db_formatar($oDados->liq_atebim, 'f');
  $nPercentualLiquidado = is_numeric($oDados->percentualLiquidado) ? db_formatar($oDados->percentualLiquidado, 'f'): '-';
  $nPercentualEmpenhado = ($oDados->percentualEmpenhado != '-') ? db_formatar($oDados->percentualEmpenhado, 'f'): '-';
  $nInscritosRP         = db_formatar($oDados->insc_rp_np , 'f');
  $nTotalLiquidacoes    = db_formatar($oDados->liq_atebim + $oDados->insc_rp_np, 'f');

  if ($iLinhaPrimeiroCabecalho == 45) {

    $nDot_ini    = '-';
    $nDot_atual  = '-';
    $nLiq_atebim = '-';
  }
  $oPdf->multicell(70, $iAltCell, $sDescricao, "R".$sTopo, "L", 0);
  $iPosYAtual = $oPdf->getY();
  $oPdf->SetXY(80, $iPosYAntes);
  $oPdf->Line(100, $iPosYAtual, 100, $oPdf->getY());
  $oPdf->multicell(20, $iAltCell, $nDot_ini, "R".$sTopo, "R", 0);
  $oPdf->SetXY(100, $iPosYAntes );
  $oPdf->Line(120, $iPosYAtual, 120, $oPdf->getY());
  $oPdf->multicell(20, $iAltCell, $nDot_atual, "LR".$sTopo, "R", 0);
  if (!in_array($oGet->periodo, array(11, 13))) {

    $oPdf->SetXY(120, $iPosYAntes);
    $oPdf->Line(140, $iPosYAtual, 140, $oPdf->getY());
    $oPdf->multicell(20, $iAltCell, $nEmp_atebim, "LR" . $sTopo, "R", 0);
    $oPdf->SetXY(140, $iPosYAntes);
    $oPdf->Line(160, $iPosYAtual, 160, $oPdf->getY());
    $oPdf->multicell(20, $iAltCell, $nPercentualEmpenhado, "LR" . $sTopo, "R", 0);
    $oPdf->SetXY(160, $iPosYAntes);
    $oPdf->Line(180, $iPosYAtual, 180, $oPdf->getY());
    $oPdf->multicell(20, $iAltCell, $nLiq_atebim, "LR" . $sTopo, "R", 0);
    $oPdf->SetXY(180, $iPosYAntes);
    $oPdf->multicell(20, $iAltCell, $nPercentualLiquidado, "L" . $sTopo, "R", 0);

  } else {

    $oPdf->SetXY(120, $iPosYAntes);
    if (in_array($iLinhaPrimeiroCabecalho, array(48, 49))) {

      $oPdf->Line(177, $iPosYAtual, 177, $oPdf->getY());
      $oPdf->multicell(57, $iAltCell, $nTotalLiquidacoes, "LR" . $sTopo, "R", 0);

    } else {

      $oPdf->Line(146, $iPosYAtual, 146, $oPdf->getY());
      $oPdf->multicell(26, $iAltCell, $nLiq_atebim, "LR" . $sTopo, "R", 0);

      $oPdf->SetXY(146, $iPosYAntes);
      $oPdf->Line(177, $iPosYAtual, 177, $oPdf->getY());
      $oPdf->multicell(31, $iAltCell, $nInscritosRP, "LR" . $sTopo, "R", 0);
    }
    $oPdf->SetXY(177, $iPosYAntes);
    $oPdf->multicell(23, $iAltCell, $nPercentualLiquidado, "L" . $sTopo, "R", 0);
  }
  $oPdf->SetY($iPosYAtual);
}

$oPdf->Cell(190, $iAltCell,  '', 'T', 1,  "R", 0);
$oPdf->ln();
//==========================================================================

imprimeQuintoCabecalho($oPdf, $iAltCell, true, $aDados[70]->dot_in );
$oPdf->Cell(190, $iAltCell,  '', 'T', 1,  "R", 0);
$oPdf->ln();

imprimeSextoCabecalho($oPdf, $iAltCell, true, $aDados[71]->linhaVIII);

$oPdf->Cell(190, $iAltCell,  '', 'T', 1,  "R", 0);
$oPdf->ln();

//==========================================================================
imprimeSetimoCabecalho($oPdf, $iAltCell, true);

for ($iLinhaPrimeiroCabecalho = 50; $iLinhaPrimeiroCabecalho <= 53; $iLinhaPrimeiroCabecalho++) {

  $oDados       = $aDados[$iLinhaPrimeiroCabecalho];
  $lTotalizador = $oDados->totalizar;
  $sDescricao   = setIdentacao($oDados->nivellinha).$oDados->descricao;
  $oPdf->SetFont('arial', '', 6);

  if ($lTotalizador == 1) {
    $oPdf->SetFont('arial', 'b', 6);
  }

  $oPdf->Cell(80, $iAltCell, $sDescricao, 'R', 0, "L", 0);
  $oPdf->Cell(25, $iAltCell, db_formatar($oDados->rp_inscrit, "f") , 'R', 0, "R", 0);
  $oPdf->Cell(25, $iAltCell, db_formatar($oDados->rp_cancel , "f") , 'R', 0, "R", 0);
  $oPdf->Cell(20, $iAltCell, db_formatar($oDados->rp_pagos  , "f") , 'R', 0, "R", 0);
  $oPdf->Cell(20, $iAltCell, db_formatar($oDados->rp_apagar , "f") , 'R', 0, "R", 0);
  $oPdf->Cell(20, $iAltCell, db_formatar($oDados->rp_prc_lim, "f") ,  '', 1, "R", 0);
}
//==========================================================================

$oPdf->Cell(190, $iAltCell,  '', 'T', 1,  "R", 0);
$oPdf->ln();

$oPdf->SetFont('arial', '', 5);

imprimeOitavoCabecalho($oPdf, $iAltCell, true);
for ($iLinhaPrimeiroCabecalho = 54; $iLinhaPrimeiroCabecalho <= 57; $iLinhaPrimeiroCabecalho++) {

  $oDados       = $aDados[$iLinhaPrimeiroCabecalho];
  $lTotalizador = $oDados->totalizar;
  $sDescricao   = setIdentacao($oDados->nivellinha).$oDados->descricao;
  $oPdf->SetFont('arial', '', 6);

  if ($lTotalizador == 1) {
    $oPdf->SetFont('arial', 'b', 6);
  }

  $nRP_sd_ini  = db_formatar($oDados->RP_sd_ini , 'f');
  $nCust_exerc = db_formatar($oDados->Cust_exerc, 'f');
  $nSd_naoapli = db_formatar($oDados->sd_naoapli, 'f');

  $oPdf->Cell(100, $iAltCell, $sDescricao , 'R', 0, "L", 0);
  $oPdf->Cell( 30, $iAltCell, $nRP_sd_ini , 'R', 0, "R", 0);
  $oPdf->Cell( 30, $iAltCell, $nCust_exerc, 'R', 0, "R", 0);
  $oPdf->Cell( 30, $iAltCell, $nSd_naoapli,  '', 1, "R", 0);
}

//==========================================================================

imprimeInfoProxPagina($oPdf, $iAltCell, 6);

imprimeNonoCabecalho($oPdf, $iAltCell, true);
for ($iLinhaPrimeiroCabecalho = 58; $iLinhaPrimeiroCabecalho <= 61; $iLinhaPrimeiroCabecalho++) {

  $oDados       = $aDados[$iLinhaPrimeiroCabecalho];
  $lTotalizador = $oDados->totalizar;
  $sDescricao   = setIdentacao($oDados->nivellinha).$oDados->descricao;
  $oPdf->SetFont('arial', '', 6);

  if ($lTotalizador == 1) {
    $oPdf->SetFont('arial', 'b', 6);
  }

  $nRP_sd_ini  = db_formatar($oDados->RP_sd_ini , 'f');
  $nCust_exerc = db_formatar($oDados->Cust_exerc, 'f');
  $nSd_naoapli = db_formatar($oDados->sd_naoapli, 'f');

  $oPdf->Cell(100, $iAltCell, $sDescricao, 'R', 0, "L", 0);
  $oPdf->Cell(30, $iAltCell, $nRP_sd_ini , 'R', 0, "R", 0);
  $oPdf->Cell(30, $iAltCell, $nCust_exerc, 'R', 0, "R", 0);
  $oPdf->Cell(30, $iAltCell, $nSd_naoapli,  '', 1, "R", 0);
}

//==========================================================================
$oPdf->Cell(190, $iAltCell,  '', 'T', 1,  "R", 0);
$oPdf->ln();

if (in_array($oGet->periodo, array(11, 13))) {
  imprimirDespesasPorSubfuncaoUltimoPeriodo($oPdf, $iAltCell, true);
} else {
  imprimeDecimoCabecalho($oPdf, $iAltCell, true);
}

for ($iLinhaPrimeiroCabecalho = 62; $iLinhaPrimeiroCabecalho <= 69; $iLinhaPrimeiroCabecalho++) {

  $oDados       = $aDados[$iLinhaPrimeiroCabecalho];
  $lTotalizador = $oDados->totalizar;
  $sDescricao   = setIdentacao($oDados->nivellinha).$oDados->descricao;
  $oPdf->SetFont('arial', '', 6);

  if ($lTotalizador == 1) {
    $oPdf->SetFont('arial', 'b', 6);
  }

  $sTopo = "";
  if ($iLinhaPrimeiroCabecalho == 69) {
    $sTopo = "T";
  }

  $nDot_ini              = db_formatar($oDados->dot_ini    , 'f');
  $nDot_atual            = db_formatar($oDados->dot_atual  , 'f');
  $nEmp_atebim           = db_formatar($oDados->emp_atebim , 'f');
  $nLiq_atebim           = db_formatar($oDados->liq_atebim , 'f');
  $nInscritosRP          = db_formatar($oDados->insc_rp_np , 'f');
  $nPercentualLiquidado  = db_formatar($oDados->percentualLiquidado, 'f');
  $nPercentualEmpenhado  = db_formatar($oDados->percentualEmpenhado, 'f');

  $oPdf->Cell(70, $iAltCell, $sDescricao           , 'R'.$sTopo, 0, "L", 0);
  $oPdf->Cell(20, $iAltCell, $nDot_ini             , 'R'.$sTopo, 0, "R", 0);
  $oPdf->Cell(20, $iAltCell, $nDot_atual           , 'R'.$sTopo, 0, "R", 0);
  if (!in_array($oGet->periodo, array(11, 13))) {

    $oPdf->Cell(20, $iAltCell, $nEmp_atebim, 'R' . $sTopo, 0, "R", 0);
    $oPdf->Cell(20, $iAltCell, $nPercentualEmpenhado, 'R' . $sTopo, 0, "R", 0);
    $oPdf->Cell(20, $iAltCell, $nLiq_atebim, 'R' . $sTopo, 0, "R", 0);
    $oPdf->Cell(20, $iAltCell, $nPercentualLiquidado, '' . $sTopo, 1, "R", 0);

  } else {

    $oPdf->Cell(26, $iAltCell, $nLiq_atebim, 'R' . $sTopo, 0, "R", 0);
    $oPdf->Cell(31, $iAltCell, $nInscritosRP, 'R' . $sTopo, 0, "R", 0);
    $oPdf->Cell(23, $iAltCell, $nPercentualLiquidado, '' . $sTopo, 1, "R", 0);
  }

}
$oPdf->Cell(190, $iAltCell,  '', 'T', 1,  "R", 0);
//$oPdf->ln();
//==========================================================================

$oReltorioContabil->getNotaExplicativa($oPdf, $oGet->periodo);
$oReltorioContabil->assinatura($oPdf, 'BG');

$oPdf->Output();




function imprimeDecimoCabecalho($oPdf, $iAltCell, $lImprime) {

  $iTamFonte = 6;
  if ( $oPdf->GetY() > $oPdf->h - 25 || $lImprime ) {

    $oPdf->SetFont('arial', '', $iTamFonte);

    if ( !$lImprime ) {

      $oPdf->AddPage("P");
    }

    $oPdf->Cell(70, $iAltCell, '', 'TR', 0, "C", 0);

    $oPdf->Cell(20, $iAltCell, 'DOTAÇÃO', 'TR', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell, 'DOTAÇÃO', 'TR', 0, "C", 0);
    $oPdf->Cell(40, $iAltCell, 'DESPESAS EMPENHADAS', 'TBR', 0, "C", 0);
    $oPdf->Cell(40, $iAltCell, 'DESPESAS LIQUIDADAS', 'TB', 1, "C", 0);

    $oPdf->SetFont('arial', 'u', $iTamFonte);
    $oPdf->Cell(70, $iAltCell, 'DESPESAS COM SAÚDE', 'R', 0, "C", 0);
    $oPdf->SetFont('arial', '', $iTamFonte);
    $oPdf->Cell(20, $iAltCell,  'INICIAL', 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  'ATUALIZADA', 'R', 0, "C", 0);

    $oPdf->Cell(20, $iAltCell,  'Até o', 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  '', 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  'Até o', 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  '', '', 1, "C", 0);

    $oPdf->Cell(70, $iAltCell,  '', 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  '', 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  '', 'R', 0, "C", 0);

    $oPdf->Cell(20, $iAltCell,  'Bimestre', 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  '%', 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  'Bimestre', 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  '%', '', 1, "C", 0);

    $oPdf->SetFont('arial', '', 5);
    $oPdf->Cell(70, $iAltCell,  '(Por Subfunção)', 'BR', 0, "C", 0);

    $oPdf->SetFont('arial', '', $iTamFonte);
    $oPdf->Cell(20, $iAltCell,  '', 'BR', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  '', 'BR', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  '(I)', 'BR', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  '(I/total I) x 100', 'BR', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  '(m)', 'BR', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  '(m/total m) x 100', 'B', 1, "C", 0);
  }
}

function imprimirDespesasPorSubfuncaoUltimoPeriodo($oPdf, $iAltCell, $lImprime) {

  $iTamFonte = 6;
  if ( $oPdf->GetY() > $oPdf->h - 25 || $lImprime ) {

    $oPdf->SetFont('arial', '', $iTamFonte);

    if ( !$lImprime ) {

      $oPdf->AddPage("P");
    }

    $oPdf->Cell(70, $iAltCell, 'DESPESAS COM SAÚDE', 'TR', 0, "C", 0);

    $oPdf->Cell(20, $iAltCell, 'DOTAÇÃO', 'TR', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell, 'DOTAÇÃO', 'TR', 0, "C", 0);
    $oPdf->Cell(80, $iAltCell, '', 'TLB', 1, "C", 0);

    $oPdf->SetFont('arial', 'u', $iTamFonte);
    $oPdf->Cell(70, $iAltCell, '', 'R', 0, "C", 0);
    $oPdf->SetFont('arial', '', $iTamFonte);
    $oPdf->Cell(20, $iAltCell, 'INICIAL', 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell, 'ATUALIZADA', 'R', 0, "C", 0);

    $oPdf->Cell(26, $iAltCell, 'Liquidadas', 'R', 0, "C", 0);
    $oPdf->Cell(31, $iAltCell, 'INSCRITAS EM RESTOS A', 'R', 0, "C", 0);
    $oPdf->Cell(23, $iAltCell, '', '', 1, "C", 0);

    $oPdf->Cell(70, $iAltCell, '', 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell, '', 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell, '', 'R', 0, "C", 0);

    $oPdf->Cell(26, $iAltCell, 'Até o Bimestre', 'R', 0, "C", 0);
    $oPdf->Cell(31, $iAltCell, 'PAGAR NÃO PROCESSADOS', 'R', 0, "C", 0);
    $oPdf->Cell(23, $iAltCell, '%', '', 1, "C", 0);

    $oPdf->SetFont('arial', '', 5);
    $oPdf->Cell(70, $iAltCell, '(Por Subfunção)', 'BR', 0, "C", 0);

    $oPdf->SetFont('arial', '', $iTamFonte);
    $oPdf->Cell(20, $iAltCell, '', 'BR', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell, '(e)', 'BR', 0, "C", 0);

    $oPdf->Cell(26, $iAltCell, '(f)', 'BR', 0, "C", 0);
    $oPdf->Cell(31, $iAltCell, '(g)', 'BR', 0, "C", 0);
    $oPdf->Cell(23, $iAltCell, '((f+g)/e)', 'B', 1, "C", 0);
  }
}

function imprimeNonoCabecalho($oPdf, $iAltCell, $lImprime) {

  $iTamFonte = 6;

  if ( $oPdf->GetY() > $oPdf->h - 25 || $lImprime ) {

    $oPdf->SetFont('arial', '', $iTamFonte);

    if ( !$lImprime ) {

      $oPdf->AddPage("P");
    }

    $oPdf->SetFont('arial', '', $iTamFonte);
    $oPdf->Cell(100, $iAltCell, 'CONTROLE DO VALOR REFERENTE AO PERCENTUAL MÍNIMO NÃO CUMPRIDO  ', 'TR', 0, "C", 0);
    $oPdf->Cell( 90, $iAltCell, 'LIMITE NÃO CUMPRIDO', 'TB', 1, "C", 0);

    $oPdf->Cell(100, $iAltCell, 'EM EXERCÍCIOS ANTERIORES PARA FINS DE APLICAÇÃO DOS RECURSOS ', 'R', 0, "C", 0);

    $oPdf->Cell(30, $iAltCell, 'Saldo Inicial', 'R', 0, "C", 0);
    $oPdf->Cell(30, $iAltCell, 'Despesas custeadas no', 'R', 0, "C", 0);
    $oPdf->Cell(30, $iAltCell, 'Saldo Final (Não Aplicado)', '', 1, "C", 0);

    $oPdf->Cell(100, $iAltCell, 'VINCULADOS CONFORME ARTIGOS 25 E 26', 'BR', 0, "C", 0);
    $oPdf->Cell(30, $iAltCell, '', 'BR', 0, "C", 0);
    $oPdf->Cell(30, $iAltCell, 'exercício de referência(k)', 'BR', 0, "C", 0);
    $oPdf->Cell(30, $iAltCell, '', 'B', 1, "C", 0);
  }
}
function imprimeOitavoCabecalho($oPdf, $iAltCell, $lImprime) {

  $iTamFonte = 6;

  if ( $oPdf->GetY() > $oPdf->h - 25 || $lImprime ) {

    $oPdf->SetFont('arial', '', $iTamFonte);

    if ( !$lImprime ) {

      $oPdf->AddPage("P");
    }

    $oPdf->SetFont('arial', '', $iTamFonte);
    $oPdf->Cell(100, $iAltCell, 'CONTROLE DOS RESTOS A PAGAR CANCELADOS OU PRESCRITOS PARA ', 'TR', 0, "C", 0);
    $oPdf->Cell( 90, $iAltCell, 'RESTOS A PAGAR CANCELADOS OU PRESCRITOS', 'TB', 1, "C", 0);

    $oPdf->Cell(100, $iAltCell, 'FINS DE APLICAÇÃO DA DISPONIBILIDADE DE CAIXA', 'R', 0, "C", 0);

    $oPdf->Cell(30, $iAltCell, 'Saldo Inicial', 'R', 0, "C", 0);
    $oPdf->Cell(30, $iAltCell, 'Despesas custeadas no', 'R', 0, "C", 0);
    $oPdf->Cell(30, $iAltCell, 'Saldo Final (Não Aplicado)', '', 1, "C", 0);

    $oPdf->Cell(100, $iAltCell, 'CONFORME ARTIGO 24, §1º E 2º', 'BR', 0, "C", 0);
    $oPdf->Cell(30, $iAltCell, '', 'BR', 0, "C", 0);
    $oPdf->Cell(30, $iAltCell, 'exercício de referência(j)', 'BR', 0, "C", 0);
    $oPdf->Cell(30, $iAltCell, '', 'B', 1, "C", 0);
  }
}
function imprimeSetimoCabecalho($oPdf, $iAltCell, $lImprime) {

  $iTamFonte = 6;

  if ( $oPdf->GetY() > $oPdf->h - 25 || $lImprime ) {

    $oPdf->SetFont('arial', '', $iTamFonte);

    if ( !$lImprime ) {

      $oPdf->AddPage("P");
    }

    $oPdf->SetFont('arial', 'u', $iTamFonte);
    $oPdf->Cell(80, $iAltCell, 'EXECUÇÃO DE RESTOS A PAGAR NÃO PROCESSADOS INCRITOS', 'TR', 0, "C", 0);
    $oPdf->SetFont('arial', '', $iTamFonte);

    $oPdf->Cell(25, $iAltCell, '', 'TR', 0, "C", 0);
    $oPdf->Cell(25, $iAltCell, 'CANCELADOS/', 'TR', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell, '', 'TR', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell, '', 'TR', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell, 'PARCELA', 'T', 1, "C", 0);


    $oPdf->SetFont('arial', 'u', $iTamFonte);
    $oPdf->Cell(80, $iAltCell, 'COM DISPONIBILIDADE DE CAIXA', 'R', 0, "C", 0);
    $oPdf->SetFont('arial', '', $iTamFonte);

    $oPdf->Cell(25, $iAltCell, 'INSCRITOS'  , 'R', 0, "C", 0);
    $oPdf->Cell(25, $iAltCell, 'PRESCRITOS' , 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell, 'PAGOS'      , 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell, 'A PAGAR'    , 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell, 'CONSIDERADA', '', 1, "C", 0);

    $oPdf->Cell(80, $iAltCell, '', 'BR', 0, "C", 0);
    $oPdf->Cell(25, $iAltCell, '', 'BR', 0, "C", 0);
    $oPdf->Cell(25, $iAltCell, '', 'BR', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell, '', 'BR', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell, '', 'BR', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell, 'NO LIMITE', 'B', 1, "C", 0);

  }

}
function imprimeSextoCabecalho($oPdf, $iAltCell, $lImprime, $nValor) {

  $iTamFonte = 6;
  if ( $oPdf->GetY() > $oPdf->h - 25 || $lImprime ) {

    $oPdf->SetFont('arial', '', $iTamFonte);

    if ( !$lImprime ) {

      $oPdf->AddPage("P");
    }
    $nValor = db_formatar($nValor, 'f');

    $oPdf->Cell(130, $iAltCell, 'VALOR REFERENTE À DIFERENÇA ENTRO O VALOR EXECUTADO E O LIMITE MÍNIMO ', 'TR', 0, "L", 0);
    $oPdf->Cell( 60, $iAltCell,  $nValor, 'T', 1, "R", 0);

    $oPdf->Cell(130, $iAltCell, 'CONSTITUCIONAL [(VII - 15) / 100 X IIIb]  ', 'BR', 0, "L", 0);
    $oPdf->Cell( 60, $iAltCell, '', 'B', 1, "L", 0);
  }
}
function imprimeQuintoCabecalho($oPdf, $iAltCell, $lImprime, $nValor) {

  $iTamFonte = 6;
  if ( $oPdf->GetY() > $oPdf->h - 25 || $lImprime ) {

    $oPdf->SetFont('arial', '', $iTamFonte);

    if ( !$lImprime ) {

      $oPdf->AddPage("P");
    }

    $nValor = db_formatar($nValor, 'f');

    $oPdf->Cell(130, $iAltCell, 'PERCENTUAL DE APLICAÇÃO EM AÇÕES E SERVIÇOS PÚBLICOS DE SAÚDE SOBRE A RECEITA DE ', 'TR', 0, "L", 0);
    $oPdf->Cell( 60, $iAltCell, '', 'T', 1, "L", 0);

    $oPdf->Cell(130, $iAltCell, 'IMPOSTOS LÍQUIDA E TRANSFERÊNCIAS CONSTITUCIONAIS E LEGAIS (VII%) = (VI h/III b) x 100  ', 'R', 0, "L", 0);
    $oPdf->Cell( 60, $iAltCell, $nValor, '', 1, "R", 0);

    $oPdf->Cell(130, $iAltCell, '- LIMITE CONSTITUCIONAL 15% 4 e 5 ', 'BR', 0, "L", 0);
    $oPdf->Cell( 60, $iAltCell, '', 'B', 1, "L", 0);



  }
}
function imprimeQuartoCabecalho($oPdf, $iAltCell, $lImprime) {

  $iTamFonte = 6;
  if ( $oPdf->GetY() > $oPdf->h - 25 || $lImprime ) {

    $oPdf->SetFont('arial', '', $iTamFonte);

    if ( !$lImprime ) {

      $oPdf->AddPage("P");
    }

    $oPdf->Cell(70, $iAltCell, '', 'TR', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell, 'DOTAÇÃO', 'TR', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell, 'DOTAÇÃO', 'TR', 0, "C", 0);
    $oPdf->Cell(40, $iAltCell, 'DESPESAS EMPENHADAS', 'TBR', 0, "C", 0);
    $oPdf->Cell(40, $iAltCell, 'DESPESAS LIQUIDADAS', 'TB', 1, "C", 0);

    $oPdf->SetFont('arial', 'u', $iTamFonte);
    $oPdf->Cell(70, $iAltCell, 'DESPESAS COM SAÚDE NÃO COMPUTADAS PARA FINS', 'R', 0, "C", 0);
    $oPdf->SetFont('arial', '', $iTamFonte);

    $oPdf->Cell(20, $iAltCell,  'INICIAL', 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  'ATUALIZADA', 'R', 0, "C", 0);

    $oPdf->Cell(20, $iAltCell,  'Até o', 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  '', 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  'Até o', 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  '', '', 1, "C", 0);

    $oPdf->SetFont('arial', 'u', $iTamFonte);
    $oPdf->Cell(70, $iAltCell,  'DE APURAÇÃO DO PERCENTUAL MÍNIMO', 'R', 0, "C", 0);
    $oPdf->SetFont('arial', '', $iTamFonte);

    $oPdf->Cell(20, $iAltCell,  '', 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  '', 'R', 0, "C", 0);

    $oPdf->Cell(20, $iAltCell,  'Bimestre', 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  '%', 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  'Bimestre', 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  '%', '', 1, "C", 0);

    $oPdf->Cell(70, $iAltCell,  '', 'BR', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  '', 'BR', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  '', 'BR', 0, "C", 0);

    $oPdf->Cell(20, $iAltCell,  '(h)', 'BR', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  '(h/lVf) x 100', 'BR', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  '(i)', 'BR', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  '(i/lVg) x 100', 'B', 1, "C", 0);
  }
}

function imprimirCabecalhoDespesaSaudeUltimoPeriodo($oPdf, $iAltCell, $lImprime, $iCodigoPeriodo) {

  $iTamFonte = 6;
  if ( $oPdf->GetY() > $oPdf->h - 25 || $lImprime ) {

    $oPdf->SetFont('arial', '', $iTamFonte);

    if (!$lImprime) {

      $oPdf->AddPage("P");
    }

    $oPdf->Cell(70, $iAltCell, 'DESPESAS COM SAÚDE', 'TR', 0, "C", 0);

    $oPdf->Cell(20, $iAltCell, 'DOTAÇÃO', 'TR', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell, 'DOTAÇÃO', 'TR', 0, "C", 0);
    $oPdf->Cell(80, $iAltCell, '', 'TLB', 1, "C", 0);

    $oPdf->SetFont('arial', 'u', $iTamFonte);
    $oPdf->Cell(70, $iAltCell, '', 'R', 0, "C", 0);
    $oPdf->SetFont('arial', '', $iTamFonte);
    $oPdf->Cell(20, $iAltCell, 'INICIAL', 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell, 'ATUALIZADA', 'R', 0, "C", 0);

    $oPdf->Cell(26, $iAltCell, 'Liquidadas', 'R', 0, "C", 0);
    $oPdf->Cell(31, $iAltCell, 'INSCRITAS EM RESTOS A', 'R', 0, "C", 0);
    $oPdf->Cell(23, $iAltCell, '', '', 1, "C", 0);

    $oPdf->Cell(70, $iAltCell, '', 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell, '', 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell, '', 'R', 0, "C", 0);

    $oPdf->Cell(26, $iAltCell, 'Até o Bimestre', 'R', 0, "C", 0);
    $oPdf->Cell(31, $iAltCell, 'PAGAR NÃO PROCESSADOS', 'R', 0, "C", 0);
    $oPdf->Cell(23, $iAltCell, '%', '', 1, "C", 0);

    $oPdf->SetFont('arial', '', 5);
    $oPdf->Cell(70, $iAltCell, '(Por Grupo de Natureza da Despesa)', 'BR', 0, "C", 0);

    $oPdf->SetFont('arial', '', $iTamFonte);
    $oPdf->Cell(20, $iAltCell, '', 'BR', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell, '(e)', 'BR', 0, "C", 0);

    $oPdf->Cell(26, $iAltCell, '(f)', 'BR', 0, "C", 0);
    $oPdf->Cell(31, $iAltCell, '(g) x 100', 'BR', 0, "C", 0);
    $oPdf->Cell(23, $iAltCell, '((f+g)/e)', 'B', 1, "C", 0);
  }

}
function imprimeTerceiroCabecalho($oPdf, $iAltCell, $lImprime) {

  $iTamFonte = 6;
  if ( $oPdf->GetY() > $oPdf->h - 25 || $lImprime ) {

    $oPdf->SetFont('arial', '', $iTamFonte);

    if ( !$lImprime ) {

      $oPdf->AddPage("P");
    }

    $oPdf->Cell(70, $iAltCell, 'DESPESAS COM SAÚDE', 'TR', 0, "C", 0);

    $oPdf->Cell(20, $iAltCell, 'DOTAÇÃO', 'TR', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell, 'DOTAÇÃO', 'TR', 0, "C", 0);
    $oPdf->Cell(40, $iAltCell, 'DESPESAS EMPENHADAS', 'TBR', 0, "C", 0);
    $oPdf->Cell(40, $iAltCell, 'DESPESAS LIQUIDADAS', 'TB', 1, "C", 0);
    $oPdf->SetFont('arial', 'u', $iTamFonte);
    $oPdf->Cell(70, $iAltCell,'', 'R', 0, "C", 0);
    $oPdf->SetFont('arial', '', $iTamFonte);
    $oPdf->Cell(20, $iAltCell,  'INICIAL', 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  'ATUALIZADA', 'R', 0, "C", 0);

    $oPdf->Cell(20, $iAltCell,  'Até o', 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  'B', 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  'Até o', 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  'B', '', 1, "C", 0);

    $oPdf->Cell(70, $iAltCell,  '', 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  '', 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  '', 'R', 0, "C", 0);

    $oPdf->Cell(20, $iAltCell,  'Bimestre', 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  '%', 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  'Bimestre', 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  '%', '', 1, "C", 0);

    $oPdf->SetFont('arial', '', 5);
    $oPdf->Cell(70, $iAltCell,  '(Por Grupo de Natureza da Despesa)', 'BR', 0, "C", 0);

    $oPdf->SetFont('arial', '', $iTamFonte);
    $oPdf->Cell(20, $iAltCell,  'D', 'BR', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  '(e)', 'BR', 0, "C", 0);

    $oPdf->Cell(20, $iAltCell,  '(f)', 'BR', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  '(f/e) x 100', 'BR', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  '(g)', 'BR', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  '(g/e) x 100', 'B', 1, "C", 0);
  }
}
function imprimeSegundoCabecalho($oPdf, $iAltCell, $lImprime) {

  $iTamFonte = 6;
  if ( $oPdf->GetY() > $oPdf->h - 25 || $lImprime ) {

    $oPdf->SetFont('arial', '', $iTamFonte);

    if ( !$lImprime ) {

      $oPdf->AddPage("P");
    }
    $oPdf->Cell(120, $iAltCell, '', 'TR', 0, "C", 0);

    $oPdf->Cell(20, $iAltCell, 'PREVISÃO', 'TR', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell, 'PREVISÃO', 'TR', 0, "C", 0);
    $oPdf->Cell(30, $iAltCell, 'RECEITAS REALIZADAS', 'TB', 1, "C", 0);

    $oPdf->SetFont('arial', 'u', $iTamFonte);
    $oPdf->Cell(120, $iAltCell, 'RECEITAS ADICIONAIS PARA FINANCIAMENTO DA SAÚDE', 'R', 0, "C", 0);
    $oPdf->SetFont('arial', '', $iTamFonte);
    $oPdf->Cell(20, $iAltCell,  'INICIAL', 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  'ATUALIZADA', 'R', 0, "C", 0);
    $oPdf->Cell(15, $iAltCell,  'Até o Bimestre', 'R', 0, "C", 0);
    $oPdf->Cell(15, $iAltCell,  '%', '', 1, "C", 0);

    $oPdf->Cell(120, $iAltCell, '', 'BR', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  '', 'BR', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  '(c)', 'BR', 0, "C", 0);
    $oPdf->Cell(15, $iAltCell,  '(d)', 'BR', 0, "C", 0);
    $oPdf->Cell(15, $iAltCell,  '(d/c) x 100', 'B', 1, "C", 0);
  }
}
function imprimePrimeiroCabecalho($oPdf, $iAltCell, $lImprime) {

  $iTamFonte = 6;

  if ( $oPdf->GetY() > $oPdf->h - 25 || $lImprime ) {

    $oPdf->SetFont('arial', '', $iTamFonte);

    if ( !$lImprime ) {

      $oPdf->AddPage("P");
    }

    $oPdf->SetFont('arial', 'u', $iTamFonte);
    $oPdf->Cell(120, $iAltCell, 'RECEITAS PARA APURAÇÃO DA APLICAÇÃO EM AÇÕES ', 'TR', 0, "C", 0);

    $oPdf->SetFont('arial', '', $iTamFonte);
    $oPdf->Cell(20, $iAltCell, 'PREVISÃO', 'TR', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell, 'PREVISÃO', 'TR', 0, "C", 0);
    $oPdf->Cell(30, $iAltCell, 'RECEITAS REALIZADAS', 'TB', 1, "C", 0);


    $oPdf->SetFont('arial', 'u', $iTamFonte);
    $oPdf->Cell(120, $iAltCell, 'E SERVIÇOS PÚBLICOS DE SAÚDE', 'R', 0, "C", 0);

    $oPdf->SetFont('arial', '', $iTamFonte);
    $oPdf->Cell(20, $iAltCell,  'INICIAL', 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  'ATUALIZADA', 'R', 0, "C", 0);
    $oPdf->Cell(15, $iAltCell,  'Até o Bimestre', 'R', 0, "C", 0);
    $oPdf->Cell(15, $iAltCell,  '%', '', 1, "C", 0);

    $oPdf->Cell(120, $iAltCell, '', 'BR', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  '', 'BR', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell,  '(a)', 'BR', 0, "C", 0);
    $oPdf->Cell(15, $iAltCell,  '(b)', 'BR', 0, "C", 0);
    $oPdf->Cell(15, $iAltCell,  '(b/a) x 100', 'B', 1, "C", 0);
  }

}
function imprimeInfoProxPagina($oPdf, $iAltCell, $iTamFonte) {

  //if ( $oPdf->GetY() > $oPdf->h - 31) {

    $oPdf->SetFont('arial', '', $iTamFonte);

    $oPdf->Cell(190, ($iAltCell*3), 'Continua na página '.($oPdf->PageNo()+1)."/{nb}",    'T', 1, "R", 0);
    $oPdf->AddPage("P");

    $oPdf->Cell(190, ($iAltCell*2), 'Continuação '.($oPdf->PageNo())."/{nb}",             'T', 1, "R", 0);

    $oPdf->Cell(190, 1, '',    'B', 1, "C", 0);
  //}
}


function imprimirCabecalhoDespesaSaudeNaoComputadasUltimoPeriodo($oPdf, $iAltCell, $lImprime) {

  $iTamFonte = 6;
  if ( $oPdf->GetY() > $oPdf->h - 25 || $lImprime ) {

    $oPdf->SetFont('arial', '', $iTamFonte);

    if (!$lImprime) {

      $oPdf->AddPage("P");
    }

    $oPdf->Cell(70, $iAltCell, '', 'TR', 0, "C", 0);

    $oPdf->Cell(20, $iAltCell, 'DOTAÇÃO', 'TR', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell, 'DOTAÇÃO', 'TR', 0, "C", 0);
    $oPdf->Cell(80, $iAltCell, 'DESPESAS EXECUTADAS', 'TLB', 1, "C", 0);

    $oPdf->SetFont('arial', 'u', $iTamFonte);
    $oPdf->Cell(70, $iAltCell, 'DESPESAS COM SAÚDE NÃO COMPUTADAS PARA FINS', 'R', 0, "C", 0);
    $oPdf->SetFont('arial', '', $iTamFonte);
    $oPdf->Cell(20, $iAltCell, 'INICIAL', 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell, 'ATUALIZADA', 'R', 0, "C", 0);

    $oPdf->Cell(26, $iAltCell, 'Liquidadas', 'R', 0, "C", 0);
    $oPdf->Cell(31, $iAltCell, 'INSCRITAS EM RESTOS A', 'R', 0, "C", 0);
    $oPdf->Cell(23, $iAltCell, '', '', 1, "C", 0);

    $oPdf->SetFont('arial', 'u', $iTamFonte);
    $oPdf->Cell(70, $iAltCell, 'DE APURAÇÃO DO PERCENTUAL MÍNIMO', 'R', 0, "C", 0);
    $oPdf->SetFont('arial', '', $iTamFonte);
    $oPdf->Cell(20, $iAltCell, '', 'R', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell, '', 'R', 0, "C", 0);

    $oPdf->Cell(26, $iAltCell, 'Até o Bimestre', 'R', 0, "C", 0);
    $oPdf->Cell(31, $iAltCell, 'PAGAR NÃO PROCESSADOS', 'R', 0, "C", 0);
    $oPdf->Cell(23, $iAltCell, '%', '', 1, "C", 0);

    $oPdf->SetFont('arial', 'u', $iTamFonte);
    $oPdf->Cell(70, $iAltCell, '', 'BR', 0, "C", 0);

    $oPdf->SetFont('arial', '', $iTamFonte);
    $oPdf->Cell(20, $iAltCell, '', 'BR', 0, "C", 0);
    $oPdf->Cell(20, $iAltCell, '', 'BR', 0, "C", 0);

    $oPdf->Cell(26, $iAltCell, '(h)', 'BR', 0, "C", 0);
    $oPdf->Cell(31, $iAltCell, '(i)', 'BR', 0, "C", 0);
    $oPdf->Cell(23, $iAltCell, '[(h+i)/IV(f+g)]', 'B', 1, "C", 0);
  }

}
/**
 * Seta identação das linhas
 *
 * @param integer_type $iNivel
 * @return $sEspaco
 */
function setIdentacao($iNivel) {

	$sEspaco = "";
	if ($iNivel > 1) {
		$sEspaco = str_repeat("   ", $iNivel);
	}

	return $sEspaco;
}
?>
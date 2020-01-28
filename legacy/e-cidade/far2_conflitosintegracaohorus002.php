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

require_once(modification(modification("libs/db_utils.php")));
require_once(modification(modification("libs/db_stdlibwebseller.php")));
require_once(modification(modification("fpdf151/pdf.php")));

$oGet = db_utils::postMemory($_GET);
$oUPS = new UnidadeProntoSocorro( db_getsession( 'DB_coddepto' ) );

$oDaoDadosDispensacao = new cl_dadoscompetenciadispensacao();
$oDaoDadosEntrada     = new cl_dadoscompetenciaentrada();
$oDaoDadosSaida       = new cl_dadoscompetenciasaida();

$aWhere   = array();
$aWhere[] = " fa59_mesreferente = " . (int) $oGet->iMes;
$aWhere[] = " fa59_anoreferente = " . $oGet->iAno;
$aWhere[] = " fa59_db_depart    = " . db_getsession( 'DB_coddepto' );
$sOrdem   = "m60_descr";

$sWhereIntegracao = implode(" and ", $aWhere);


$aInconsistenciaSaida       = array();
$aInconsistenciaEntrada     = array();
$aInconsistenciaDispensacao = array();
$aInconsistenciaCGS         = array();

try {

  $sCamposDispensacao  = " m60_codmater as agrupador, m60_codmater, m60_descr, fa61_cnes, fa61_catmat,  ";
  $sCamposDispensacao .= " fa61_quantidade, fa61_dispensacao, fa61_cns, z01_i_cgsund, z01_v_cgccpf, z01_v_nome, ";
  $sCamposDispensacao .= " fa61_far_retiradaitens, fa61_lote as lote, fa61_valor as valor, fa61_validade as validade ";
  $sWhereDispensacao   = " fa61_enviar is false and {$sWhereIntegracao} ";

  $sSqlDispensacao = $oDaoDadosDispensacao->sqlMedicamentosCompetenciaHorus(null, $sCamposDispensacao, $sOrdem, $sWhereDispensacao);
  $rsDispensacao   = db_query($sSqlDispensacao);

  if (!$rsDispensacao) {
    throw new Exception(pg_last_error());
  }

  $sWhereEntrada   = " fa62_enviar is false and {$sWhereIntegracao} ";
  $sCamposEntrada  = " m60_codmater, m60_descr, fa62_cnes, fa62_catmat, fa62_tipo, fa62_valor as valor,   ";
  $sCamposEntrada .= " fa62_quantidade, fa62_recebimento, fa62_movimentacao, fa62_matestoqueinimei as agrupador, ";
  $sCamposEntrada .= " fa62_validade as validade, fa62_lote as lote ";

  $sSqlEntrada = $oDaoDadosEntrada->sqlMedicamentosCompetenciaHorus(null, $sCamposEntrada, $sOrdem, $sWhereEntrada);
  $rsEntrada   = db_query($sSqlEntrada);

  if (!$rsEntrada) {
    throw new Exception(pg_last_error());
  }

  $sCamposSaida  = " m60_codmater, m60_descr, fa63_catmat, fa63_cnes, fa63_tipo, fa63_valor as valor,  ";
  $sCamposSaida .= " fa63_quantidade, fa63_data, fa63_movimentacao, fa63_matestoqueinimei as agrupador, ";
  $sCamposSaida .= " fa63_lote as lote, fa63_validade as validade ";
  $sWhereSaida   = " fa63_enviar is false and  {$sWhereIntegracao} ";

  $sSqlSaida = $oDaoDadosSaida->sqlMedicamentosCompetenciaHorus(null, $sCamposSaida, $sOrdem, $sWhereSaida);
  $rsSaida   = db_query($sSqlSaida);

  if (!$rsSaida) {
    throw new Exception(pg_last_error());
  }

  $iLinhasDispensacao = pg_num_rows($rsDispensacao);
  $iLinhasEntrada     = pg_num_rows($rsEntrada);
  $iLinhasSaida       = pg_num_rows($rsSaida);

  if ( empty($iLinhasDispensacao) && empty($iLinhasEntrada) && empty($iLinhasSaida) ) {

    $sCompetencia = DBDate::getMesExtenso($oGet->iMes). "/{$oGet->iAno}";
    throw new Exception("Não foi encontrado inconsistência para competência: {$sCompetencia}.");
  }

  for ($i=0; $i < $iLinhasDispensacao; $i++) {

    $oDados = db_utils::fieldsMemory($rsDispensacao, $i);
    $oCGSInconsistente         = new stdClass();
    if ( empty($oDados->fa61_cns) ||
         !validaCnsDefinitivo($oDados->fa61_cns) && !validaCnsProvisorio($oDados->fa61_cns) ) {

      $oCGSInconsistente->iCgs     = $oDados->z01_i_cgsund;
      $oCGSInconsistente->sNome    = $oDados->z01_v_nome;
      $oCGSInconsistente->sCGS     = $oDados->fa61_cns;
      $oCGSInconsistente->sCPF     = $oDados->z01_v_cgccpf;
      $oCGSInconsistente->sMsgErro = "CNS não informado ou inválido.";

      $aInconsistenciaCGS[$oDados->z01_i_cgsund] = $oCGSInconsistente;
    }

    $aInconsistenciaEntrada = validarMedicamento($aInconsistenciaDispensacao, $oDados);
  }

  for ($i=0; $i < $iLinhasEntrada; $i++) {

    $oDados = db_utils::fieldsMemory($rsEntrada, $i);
    $aInconsistenciaEntrada = validarMedicamento($aInconsistenciaEntrada, $oDados);
  }


  for ($i=0; $i < $iLinhasSaida; $i++) {

    $oDados = db_utils::fieldsMemory($rsSaida, $i);
    $aInconsistenciaEntrada = validarMedicamento($aInconsistenciaSaida, $oDados);
  }

} catch (Exception $e) {
  db_redireciona('db_erros.php?fechar=true&db_erro='.$e->getMessage());
}


function validarMedicamento($aInconsistencia, $oDadosMedicamento) {

  if ( empty($oDadosMedicamento->validade) ) {
    $aInconsistencia[$oDadosMedicamento->agrupador] = criaObjetoLog($oDadosMedicamento, 'validade');
  }

  if ( empty($oDadosMedicamento->lote) ) {
    $aInconsistencia[$oDadosMedicamento->agrupador] = criaObjetoLog($oDadosMedicamento, 'lote');
  }
  if ( $oDadosMedicamento->valor < 0 ) {
    $aInconsistencia[$oDadosMedicamento->agrupador] = criaObjetoLog($oDadosMedicamento, 'valor');
  }

  return $aInconsistencia;
}

function criaObjetoLog($oDadosMedicamento, $sTipo) {

  $oLog = new stdClass();
  $oLog->iMaterial = $oDadosMedicamento->m60_codmater;
  $oLog->sMaterial = $oDadosMedicamento->m60_descr;

  switch ($sTipo) {
    case 'validade':
      $oLog->sMsgErro = "Data de validade não informada.";
      break;
    case 'lote':
      $oLog->sMsgErro = "Número do Lote não informado.";
      break;
    case 'valor':
      $oLog->sMsgErro = "Preço médio com valor negativo.";
      break;
  }
  return $oLog;
}


/**
 * Ordera array de pacientes em ordem alfabética
 */
uasort($aInconsistenciaCGS, "ordemAlfabetica");
function ordemAlfabetica($aArrayAtual, $aProximoArray) {
  return strcasecmp($aArrayAtual->sNome, $aProximoArray->sNome);
}


/**
 * Impressão dos dados processados acima
 */
$head1 = "Relatório de inconsistências.";
$head2 = "Departamento: " . $oUPS->getCodigo() . ' - ' . $oUPS->getDepartamento()->getNomeDepartamento();
$head3 = "Competência: " . DBDate::getMesExtenso($oGet->iMes). "/{$oGet->iAno}";


$oPdf = new PDF();
$oPdf->AliasNbPages();
$oPdf->Open();
$oPdf->SetAutoPageBreak(true, 10);
$oPdf->SetFillColor(240);

$lPrimeiraPagina = true;
foreach ($aInconsistenciaSaida as $oInconsistencia) {

  if ( $lPrimeiraPagina || $oPdf->GetY() > ($oPdf->h - 20) ) {

    criarHeaderArquivos($oPdf, "Saída");
    $lPrimeiraPagina = false;
  }

  imprimeLinhaInconsistencia($oPdf, $oInconsistencia);
}

$lPrimeiraPagina = true;
foreach ($aInconsistenciaEntrada as $oInconsistencia) {

  if ( $lPrimeiraPagina || $oPdf->GetY() > ($oPdf->h - 20) ) {

    criarHeaderArquivos($oPdf, "Entrada");
    $lPrimeiraPagina = false;
  }

  imprimeLinhaInconsistencia($oPdf, $oInconsistencia);
}

$lPrimeiraPagina = true;
foreach ($aInconsistenciaDispensacao as $oInconsistencia) {

  if ( $lPrimeiraPagina || $oPdf->GetY() > ($oPdf->h - 20) ) {

    criarHeaderArquivos($oPdf, "Dispensação");
    $lPrimeiraPagina = false;
  }
  imprimeLinhaInconsistencia($oPdf, $oInconsistencia);

}

$lPrimeiraPagina = true;
foreach( $aInconsistenciaCGS as $oCGS) {

  if ( $lPrimeiraPagina || $oPdf->GetY() > ($oPdf->h - 20) ) {

    $lImprimirLabel = (count($aInconsistenciaDispensacao) == 0);
    criarHeaderCGS($oPdf, $lImprimirLabel);
    $lPrimeiraPagina = false;
  }

  $oPdf->SetFont("Arial", "", 7);
  $oPdf->Cell(92, 4, "{$oCGS->iCgs} - {$oCGS->sNome}",  1, 0, "L");
  $oPdf->Cell(28, 4, $oCGS->sCPF,                       1, 0, "C");
  $oPdf->Cell(72, 4, $oCGS->sMsgErro,                   1, 1, "L");
}

function criarHeaderCGS( $oPdf, $lImprimirLabel ) {

  $oPdf->SetFont("Arial", "B", 8);
  $oPdf->ln();

  if ($oPdf->GetY() > ($oPdf->h - 20) )  {

    $oPdf->AddPage();
    $oPdf->Cell(192, 4, "Dispensação", "B", 1);
  }

  if ( $lImprimirLabel ) {

    $oPdf->AddPage();
    $oPdf->Cell(192, 4, "Dispensação", "B", 1);
  }

  $oPdf->Cell(92, 4, "CGS",  1, 0, "C", 1);
  $oPdf->Cell(28, 4, "CPF",  1, 0, "C", 1);
  $oPdf->Cell(72, 4, "Erro", 1, 1, "C", 1);
}


function criarHeaderArquivos($oPdf, $sLabel) {

  $oPdf->AddPage();

  $oPdf->SetFont("Arial", "B", 8);
  $oPdf->Cell(192, 4, $sLabel, "B", 1);
  $oPdf->Cell(20,  4, "Material", 1, 0, "C", 1);
  $oPdf->Cell(72,  4, "Nome", 1, 0, "C", 1);
  $oPdf->Cell(100, 4, "Erro", 1, 1, "C", 1);

}

function imprimeLinhaInconsistencia($oPdf, $oInconsistencia) {

  $oPdf->SetFont("Arial", "", 7);
  $oPdf->Cell(20,  4,  $oInconsistencia->iMaterial, 1, 0, "L");
  $oPdf->Cell(72,  4,  $oInconsistencia->sMaterial, 1, 0, "L");
  $oPdf->Cell(100, 4,  $oInconsistencia->sMsgErro,  1, 1, "L");
}

$oPdf->Output();
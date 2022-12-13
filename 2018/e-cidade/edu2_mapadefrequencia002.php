<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("std/DBDate.php");
require_once ("fpdf151/pdf.php");
require_once ("classes/db_edu_parametros_classe.php");
require_once ("model/educacao/avaliacao/iFormaObtencao.interface.php");
require_once ("model/educacao/avaliacao/iElementoAvaliacao.interface.php");

$oGet = db_utils::postMemory($_GET);
db_app::import("CgmFactory");
db_app::import("educacao.progressaoparcial.*");
db_app::import("educacao.avaliacao.*");
db_app::import("educacao.*");
db_app::import("exceptions.*");

$aTurmaProcessada = array();
$aTurmas          = array();

foreach (explode(",", $oGet->aTurmas) as $iCodigoTurma) {

  $aTurmas[] = TurmaRepository::getTurmaByCodigo($iCodigoTurma);
}

/**
 * Percorremos as e filtra os dados necessarios para o relatorio
 */
foreach ($aTurmas as $oTurma) {

  $aEtapas                      = $oTurma->getEtapas();

  $sNomeEscola       = $oTurma->getEscola()->getNome();
  $iCodigoReferencia = $oTurma->getEscola()->getCodigoReferencia();

  if ( $iCodigoReferencia != null ) {
    $sNomeEscola = "{$iCodigoReferencia} - {$sNomeEscola}";
  }

  $oDadosTurma                  = new stdClass();
  $oDadosTurma->aAlunos         = array();
  $oDadosTurma->iCodigoTurma    = $oTurma->getCodigo();
  $oDadosTurma->sDescricaoTurma = $oTurma->getDescricao();
  $oDadosTurma->sCalendario     = $oTurma->getCalendario()->getDescricao();
  $oDadosTurma->iAno            = $oTurma->getCalendario()->getAnoExecucao();
  $oDadosTurma->sEscola         = $sNomeEscola;
  $oDadosTurma->sCurso          = $aEtapas[0]->getEtapa()->getEnsino()->getNome();
  $oDadosTurma->sEtapa          = $aEtapas[0]->getEtapa()->getNome();
  $oDadosTurma->sTurma          = $oTurma->getDescricao();
  $oDadosTurma->iTotalAulas     = 0;


  foreach ($oTurma->getDisciplinas() as $oRegencia) {

    $oDadosTurma->iTotalAulas += $oRegencia->getTotalDeAulas();
  }

  /**
   * Buscamos todos os alunos matriculados na turma
   */
  foreach ($oTurma->getAlunosMatriculados(true) as $oMatricula) {

    db_inicio_transacao();
    $oDiario    = $oMatricula->getDiarioDeClasse();
    db_fim_transacao();

    $oAluno                 = new stdClass();
    $oAluno->sNome          = $oMatricula->getAluno()->getNome();
    $oAluno->iCodigoAluno   = $oMatricula->getAluno()->getCodigoAluno();
    $oAluno->sSituacao      = $oMatricula->getSituacao();
    $oAluno->iTotalFaltas   = 0;
    $oAluno->iTotalAbonadas = 0;


    foreach ($oDiario->getDisciplinas() as $oDiarioAvaliacaoDisciplina) {

      $oAluno->iTotalFaltas   += $oDiarioAvaliacaoDisciplina->getTotalFaltas();
      $oAluno->iTotalAbonadas += $oDiarioAvaliacaoDisciplina->getTotalFaltasAbonadas();
    }

    if ($oDadosTurma->iTotalAulas == 0) {

      $nPercentualFaltas     = "";
      $nPercentualFrequencia = "";
    } else {

      $iTotalFaltas              = $oAluno->iTotalFaltas - $oAluno->iTotalAbonadas;
      $nPercentualFaltas         = ArredondamentoFrequencia::arredondar(($iTotalFaltas * 100) / $oDadosTurma->iTotalAulas, $oDadosTurma->iAno);
      $nPercentualFrequencia     = ArredondamentoFrequencia::formatar(100 - $nPercentualFaltas, $oDadosTurma->iAno);
    }
    $oAluno->nPercentualFrequencia = "{$nPercentualFrequencia}";
    $oAluno->nPercentualFaltas     = "{$nPercentualFaltas}";

    $oDadosTurma->aAlunos[] = $oAluno;
  }

  $aTurmaProcessada[$oTurma->getCodigo()] = $oDadosTurma;
}

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetFillColor(235);
$oPdf->SetAutoPageBreak(false);

$lPrimeiraVolta = true;
$iHeigth        = 4;

/**
 * Processando impressao do relatorio
 */
foreach ($aTurmaProcessada as $oTurma) {


  $head1 = "Escola : {$oTurma->sEscola}";
  $head2 = "Calendario : {$oTurma->sCalendario}";
  $head3 = "Curso: {$oTurma->sCurso}";
  $head4 = "Etapa: {$oTurma->sEtapa}";
  $head5 = "Turma: {$oTurma->sTurma}";

  foreach ($oTurma->aAlunos as $oAluno) {

    if (($oPdf->GetY() > $oPdf->h -10) || $lPrimeiraVolta) {

      $lPrimeiraVolta = false;
      imprimeHeader($oPdf, $iHeigth);
    }

    $nPercentualFaltas     = "0%";
    $nPercentualFrequencia = "100%";

    if ( $oAluno->nPercentualFaltas != '' ) {
      $nPercentualFaltas = "{$oAluno->nPercentualFaltas}%";
    }
    if ( $oAluno->nPercentualFrequencia != '' ) {
      $nPercentualFrequencia = "{$oAluno->nPercentualFrequencia}%";
    }

    $oPdf->SetFont("arial", "", 6);
    $oPdf->Cell(12,  $iHeigth, $oAluno->iCodigoAluno,      "TBR", 0, "R");
    $oPdf->Cell(100, $iHeigth, $oAluno->sNome,                 1, 0, "L");
    $oPdf->Cell(10,  $iHeigth, "{$oAluno->iTotalFaltas}",      1, 0, "R");
    $oPdf->Cell(15,  $iHeigth, "{$nPercentualFaltas}",         1, 0, "R");
    $oPdf->Cell(15,  $iHeigth, "{$nPercentualFrequencia}",     1, 0, "R");
    $oPdf->Cell(40,  $iHeigth, "{$oAluno->sSituacao}",     "TBL", 1, "L");

  }
  $oPdf->ln();
  $oPdf->SetFont("arial", "b", 7);
  $oPdf->Cell(35,  $iHeigth, "Total de Alunos na Turma: ",  0, 0, "L");
  $oPdf->Cell(80,  $iHeigth, count($oTurma->aAlunos),       0, 0, "L");
  $oPdf->Cell(30,  $iHeigth, "Total da Carga Horária: ",    0, 0, "L");
  $oPdf->Cell(60,  $iHeigth, "{$oTurma->iTotalAulas}",      0, 1, "L");

  $oPdf->Cell(115,  $iHeigth, "Data: _____/_____/__________",                  0, 0, "L");
  $oPdf->Cell(80,  $iHeigth, "Assinatura: ________________________________",  0, 1, "L");

  $lPrimeiraVolta = true;
}
//die("fim");

$oPdf->Output();

/**
 * Cria o cabeçalho da truma
 * @param PDF $oPdf
 * @param integer $iHeigth altura da linha
 */
function imprimeHeader($oPdf, $iHeigth) {

  $oPdf->AddPage();
  $oPdf->SetFont("arial", "b", 7);
  $oPdf->Cell(12,  $iHeigth, "Código",   "TBR", 0, "C", 1);
  $oPdf->Cell(100, $iHeigth, "Nome",         1, 0, "C", 1);
  $oPdf->Cell(10,  $iHeigth, "Faltas",       1, 0, "C", 1);
  $oPdf->Cell(15,  $iHeigth, "% Faltas",     1, 0, "C", 1);
  $oPdf->Cell(15,  $iHeigth, "% Freq",       1, 0, "C", 1);
  $oPdf->Cell(40,  $iHeigth, "Situação", "TBL", 1, "C", 1);
}
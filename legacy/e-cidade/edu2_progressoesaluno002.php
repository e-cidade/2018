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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("fpdf151/pdf.php");

$oGet = db_utils::postMemory( $_GET );

if( !isset( $oGet->iAluno ) || empty( $oGet->iAluno ) ) {

  $sMensagem = "Aluno não informado.";
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMensagem}");
}

$oAluno            = AlunoRepository::getAlunoByCodigo( $oGet->iAluno );
$aProgressoes      = $oAluno->getProgressaoParcial( true );
$iTotalProgressoes = count( $aProgressoes );

$oConfig                 = new stdClass();
$oConfig->iLarguraMaxima = 290;
$oConfig->iAlturaLinha   = 4;
$oConfig->aSituacoes     = array( 0 => "TODAS", 1 => "ATIVA", 2 => "INATIVA", 3 => "CONCLUÍDA" );

$oPdf = new PDF();
$oPdf->Open();

$head1 = "Relatório de Progressão";
$head2 = "Aluno: {$oAluno->getNome()}";
$head3 = "Situação: {$oConfig->aSituacoes[$oGet->iSituacao]}";

$oPdf->AddPage( 'L' );
$oPdf->SetFont( 'arial', '', 6 );

if( $iTotalProgressoes == 0 ) {

  $sMensagem = "Aluno sem progressão parcial.";
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMensagem}");
}

/**
 * Percorre as progressões existentes para o aluno, independente da situação da mesma
 */
foreach( $aProgressoes as $oProgressao ) {

  if( $oGet->iSituacao != 0 && $oGet->iSituacao != $oProgressao->getSituacaoProgressao()->getCodigo() ) {

    $iTotalProgressoes--;
    continue;
  }

  $sEscolaOrigem = "Escola de Origem: {$oProgressao->getEscola()->getNome()}";
  $sAnoOrigem    = "Ano: {$oProgressao->getAno()}";
  $sEtapa        = "Etapa: {$oProgressao->getEtapa()->getNome()}";
  $sDisciplina   = "Disciplina: {$oProgressao->getDisciplina()->getNomeDisciplina()}";
  $sSituacao     = "Situação: {$oProgressao->getSituacaoProgressao()->getDescricao()}";

  $sDescricaoOrigem = !is_null( $oProgressao->getCodigoDiarioFinal() ) ? "Diário" : "Manual";
  $sOrigem          = "Origem: {$sDescricaoOrigem}";

  $oPdf->Cell( 110, $oConfig->iAlturaLinha, $sEscolaOrigem, 0, 0, 'L' );
  $oPdf->Cell(  16, $oConfig->iAlturaLinha, $sAnoOrigem,    0, 0, 'L' );
  $oPdf->Cell(  32, $oConfig->iAlturaLinha, $sEtapa,        0, 0, 'L' );
  $oPdf->Cell(  76, $oConfig->iAlturaLinha, $sDisciplina,   0, 0, 'L' );
  $oPdf->Cell(  28, $oConfig->iAlturaLinha, $sSituacao,     0, 0, 'L' );
  $oPdf->Cell(  20, $oConfig->iAlturaLinha, $sOrigem,       0, 1, 'L' );

  /**
   * Caso a progressão já tenha sido vinculada a uma turma ao menos uma vez, apresenta as informações referentes a esta
   * matrícula da progressão
   */
  foreach( $oProgressao->getVinculosProgressao() as $oProgressaoParcialVinculoDisciplina ) {

    $sEscola          = $oProgressaoParcialVinculoDisciplina->getRegencia()->getTurma()->getEscola()->getNome();
    $sEscolaMatricula = "Escola de Matrícula: {$sEscola}";
    $sAno             = "Ano: {$oProgressaoParcialVinculoDisciplina->getAno()}";
    $sTurma           = "Turma: {$oProgressaoParcialVinculoDisciplina->getRegencia()->getTurma()->getDescricao()}";
    $sAproveitamento  = "Aproveitamento: " . substr( $oProgressaoParcialVinculoDisciplina->getResultadoFinal()->getNota(), 0, 29 );
    $sResultado       = "";

    if( $oProgressaoParcialVinculoDisciplina->getResultadoFinal()->getResultado() != "" ) {
      $sResultado = $oProgressaoParcialVinculoDisciplina->getResultadoFinal()->getResultado();
    }

    $sResultadoFinal  = "RF: {$sResultado}";

    $oPdf->Cell(   5, $oConfig->iAlturaLinha, "",                0, 0, 'L' );
    $oPdf->Cell( 110, $oConfig->iAlturaLinha, $sEscolaMatricula, 0, 0, 'L' );
    $oPdf->Cell(  16, $oConfig->iAlturaLinha, $sAno,             0, 0, 'L' );
    $oPdf->Cell(  65, $oConfig->iAlturaLinha, $sTurma,           0, 0, 'L' );
    $oPdf->Cell(  66, $oConfig->iAlturaLinha, $sAproveitamento,  0, 0, 'L' );
    $oPdf->Cell(  30, $oConfig->iAlturaLinha, $sResultadoFinal,  0, 1, 'L' );
  }

  $oPdf->Ln( 4 );
}

if( $iTotalProgressoes == 0 ) {

  $sMensagem = "Aluno sem progressão parcial com situação {$oConfig->aSituacoes[$oGet->iSituacao]}.";
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMensagem}");
}

$oPdf->Output();
<?php

/**
 * E-cidade Software Publico para Gestão Municipal
 *   Copyright (C) 2014 DBSeller Serviços de Informática Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa é software livre; você pode redistribuí-lo e/ou
 *   modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versão 2 da
 *   Licença como (a seu critério) qualquer versão mais nova.
 *   Este programa e distribuído na expectativa de ser útil, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *   COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *   PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *   detalhes.
 *   Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *   junto com este programa; se não, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   Cópia da licença no diretório licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */


/**
 * Grade de Avaliacao Voltada para Criação de Grade de Aproveitamento com as colunas de Médias Ponderadas em PDF
 */
class GradeAvaliacaoMediaPonderada {

  /**
   * Matricula do Aluno
   * @var Matricula
   */
  protected $oMatricula =null;

  /**
   * Grade de avaliacao o Aluno
   * @var GradeAproveitamentoAluno
   */
  protected  $oGradeAvaliacao  = null;

  /**
   * Documento PDF para a geracao do Documento
   * @var FPDF
   */
  protected  $oPdf = null;

  /**
   * ALtura da Celula
   * @var int
   */
  protected $iAlturaCelula = 3.5;

  /**
   * @var int
   */
  protected $iTamanhoDisponivelPeriodo = 90;


  /**
   * Total de Faltas por Periodo
   */
  protected $aTotalFaltas = array();

  /**
   * Percentual da frequencia
   * @var string
   */
  protected $nPercentualFrequencia = '';

  /**
   * Ano do Calendario
   * @var integer
   */
  protected $iAnoCalendario;

  /**
   * @param Matricula $oMatricula
   * @param FPDF      $oPdf
   */
  public function __construct(Matricula $oMatricula, FPDF $oPdf) {

    $this->oMatricula      = $oMatricula;
    $this->oGradeAvaliacao = new GradeAproveitamentoAluno($oMatricula);
    $this->oPdf            = $oPdf;
    $this->iAnoCalendario  = $oMatricula->getTurma()->getCalendario()->getAnoExecucao();
    $this->escreveCabecalho();
    $this->escreverDisciplinas();

  }

  /**
   * Escreve o cabecalho das disciplinas
   */
  private function escreveCabecalho () {

    $this->oPdf->Cell(50, $this->iAlturaCelula * 2, 'DISCIPLINAS', 1, 0, "C");
    $aPeriodos              = $this->getAvaliacoes();
    $iTamanhoCelulaPeriodos = ($this->iTamanhoDisponivelPeriodo / (count($aPeriodos) * 2));
    $iAlturaInicial         = $this->oPdf->GetY();

    $iPosicaoColuna         = 60;
    $iPeriodo               = 1;
    $this->oPdf->SetFont('arial', '', 6);
    foreach ($aPeriodos as $oAvaliacao) {

      $this->oPdf->SetXY($iPosicaoColuna, $iAlturaInicial);
      $sNomePeriodo = $oAvaliacao->getPeriodoAvaliacao()->getDescricaoAbreviada();
      $this->oPdf->Cell($iTamanhoCelulaPeriodos, $this->iAlturaCelula, $sNomePeriodo, 1, 0, "C");
      $this->oPdf->Cell($iTamanhoCelulaPeriodos, $this->iAlturaCelula, "P = {$iPeriodo}", 1, 1, "C");

      $this->oPdf->SetX($iPosicaoColuna);

      $this->oPdf->Cell($iTamanhoCelulaPeriodos, $this->iAlturaCelula, "N", 1, 0, "C");
      $this->oPdf->Cell($iTamanhoCelulaPeriodos, $this->iAlturaCelula, "N X P", 1, 0, "C");

      $iPeriodo ++;
      $iPosicaoColuna += $iTamanhoCelulaPeriodos * 2;
    }
    $this->oPdf->SetXY($iPosicaoColuna, $iAlturaInicial);
    $this->oPdf->Cell(11, $this->iAlturaCelula * 2, 'MÉDIA', 1, 0, "C");
    $this->oPdf->Cell(40, $this->iAlturaCelula * 2, 'RESULTADO', 1, 1, "C");
  }

  /**
   * Retorna todas das Avaliacoes Periodidas dos Alunos.
   * @return AvaliacaoPeriodica[]
   */
  private function getAvaliacoes () {

    $aAvaliacoes = array();
    foreach ($this->oGradeAvaliacao->getPeriodos() as $oPeriodo) {
      if ($oPeriodo->isResultado()) {
        continue;
      }
      $aAvaliacoes[] = $oPeriodo;
    }
    return $aAvaliacoes;
  }

  /**
   * Escreve as linhas das Disciplinas
   */
  private function escreverDisciplinas () {

    $aPeriodos              = $this->getAvaliacoes();
    $iTamanhoCelulaPeriodos = ($this->iTamanhoDisponivelPeriodo / (count($aPeriodos) * 2));
    $this->oPdf->SetFont('arial', '', 6);
    foreach ($this->oGradeAvaliacao->getGradeAproveitamento() as $oGrade) {

      $this->oPdf->Cell(50, $this->iAlturaCelula, $oGrade->sNome, 1, 0);
      $iPeriodo  = 1;
      foreach ($oGrade->aAproveitamento as $oAproveitamento) {

        if (!$oAproveitamento->oAproveitamento->lApareceBoletim) {
          continue;
        }
        if (!isset($this->aTotalFaltas[$oAproveitamento->iCodigo])) {
          $this->aTotalFaltas[$oAproveitamento->iCodigo] = 0;
        }
        $nNotaComPeso    = $oAproveitamento->oAproveitamento->nAproveitamento * $iPeriodo;
        $iFaltas         = $oAproveitamento->oAproveitamento->iFaltas;
        $iFaltasAbonadas = $oAproveitamento->oAproveitamento->iFaltasAbonadas;
        $this->aTotalFaltas[$oAproveitamento->iCodigo] += ($iFaltas - $iFaltasAbonadas);

        if (!$oAproveitamento->oAproveitamento->lAtingiuMinimo) {
          $this->oPdf->SetFont('arial', 'b', 6);
        }
        $this->oPdf->Cell($iTamanhoCelulaPeriodos, $this->iAlturaCelula, $oAproveitamento->oAproveitamento->nAproveitamento, 1, 0, "C");
        $this->oPdf->SetFont('arial', '', 6);
        $this->oPdf->Cell($iTamanhoCelulaPeriodos, $this->iAlturaCelula, ArredondamentoNota::formatar($nNotaComPeso, $this->iAnoCalendario), 1, 0, "C");
        $iPeriodo ++;
      }

      if ($oGrade->oResultadoFinal->sResultadoFinal == 'R') {
        $this->oPdf->SetFont('arial', 'b', 6);
      }
      $this->oPdf->Cell(11, $this->iAlturaCelula , $oGrade->oResultadoFinal->nAproveitamentoFinal, 1, 0, "C");
      $this->oPdf->SetFont('arial', '', 6);
      $this->oPdf->Cell(40, $this->iAlturaCelula, $oGrade->oResultadoFinal->sTermoResultadoFinal, 1, 1, "C");
    }
    $this->escreverTotalDeFaltas();
  }

  /**
   * Escreve o Total de Faltas Dos Periodos
   */
  protected function escreverTotalDeFaltas() {

    $this->oPdf->Cell(50, $this->iAlturaCelula, 'FALTAS / DIAS', 1, 0);
    $aPeriodos              = $this->getAvaliacoes();
    $iTamanhoCelulaPeriodos = ($this->iTamanhoDisponivelPeriodo / (count($aPeriodos) * 2));
    $this->oPdf->SetFont('arial', '', 6);
    $iTamanhoColunasPeriodos = 0;
    $nTotalFaltas = 0;
    foreach ($aPeriodos as $oAvaliacao) {

      $iFaltas = $this->aTotalFaltas[$oAvaliacao->getCodigo()];
      $sNomePeriodo = $oAvaliacao->getPeriodoAvaliacao()->getDescricaoAbreviada();
      $this->oPdf->Cell($iTamanhoCelulaPeriodos, $this->iAlturaCelula, $sNomePeriodo, 1, 0, "C");
      $this->oPdf->Cell($iTamanhoCelulaPeriodos, $this->iAlturaCelula, $iFaltas, 1, 0, "C");
      $iTamanhoColunasPeriodos += $iTamanhoCelulaPeriodos * 2;
      $nTotalFaltas += $iFaltas;
    }
    $this->oPdf->Cell(11, $this->iAlturaCelula, '', 1);
    $this->oPdf->Cell(21, $this->iAlturaCelula, 'TOTAL DE FALTAS', 1, 0, "L");
    $this->oPdf->Cell(19, $this->iAlturaCelula, $nTotalFaltas, 1, 1);

    $aDisciplinas = $this->oMatricula->getDiarioDeClasse()->getDisciplinas();
    $oDisciplina  = $aDisciplinas[0]->getRegencia();

    $nPercentualFrequencia = $this->oGradeAvaliacao->getDadosFrequenciaDaDiscplina($oDisciplina)->nPercentualFrequencia;
    $iDiasLetivos          = $this->oMatricula->getTurma()->getCalendario()->getDiasLetivos();
    $sResultadoFinal       = ResultadoFinal($this->oMatricula->getCodigo(),
      $this->oMatricula->getAluno()->getCodigoAluno(),
      $this->oMatricula->getTurma()->getCodigo(),
      $this->oMatricula->getSituacao(),
      $this->oMatricula->isConcluida() ? 'S' : 'N'
    ) ;
    $this->oPdf->Cell(50, $this->iAlturaCelula, 'DIAS LETIVOS', 'TBL', 0, "L");
    $this->oPdf->Cell($iTamanhoCelulaPeriodos, $this->iAlturaCelula, $iDiasLetivos, 'TBR', 0, 'R');
    $this->oPdf->Cell($iTamanhoColunasPeriodos - $iTamanhoCelulaPeriodos, $this->iAlturaCelula, "PERCENTUAL DE PRESENÇA", 1, 0, 'C');
    $this->oPdf->Cell(11, $this->iAlturaCelula, "{$nPercentualFrequencia}%", 1, 0, 'R');
    $this->oPdf->Cell(40, $this->iAlturaCelula, "RESULTADO FINAL: {$sResultadoFinal}", 1, 0, "L");
  }
}
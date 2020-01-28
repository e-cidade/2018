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

require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_libdocumento.php");
require_once ("libs/db_libparagrafo.php");
require_once ("libs/JSON.php");
require_once ("dbforms/db_funcoes.php");
require_once ("std/DBDate.php");
require_once ("fpdf151/scpdf.php");

$oJson   = new services_json();
$oGet    = db_utils::postMemory($_GET);

$aTurmas = $oJson->decode(str_replace("\\","",$oGet->aTurmas));

$oPdf = new scpdf("L");
$oPdf->Open();
$oPdf->SetMargins(8, 8);
$oPdf->AliasNbPages();
$oPdf->SetFillColor(235);
$oPdf->SetAutoPageBreak(false);
$oPdf->setfont('arial', '', 7);

$lPrimeiroLaco = true;

/**
 * Objeto com os parametros de controle do documento
 */
$oControle                                     = new stdClass();
$oControle->iNumeroElementosAvaliacaoPorPagina = $oGet->iAvaliacaoPagina;
$oControle->iNumeroAlunosPorPagina             = 35;
$oControle->iAlturaLinha                       = 4;
$oControle->lProgressaoParcial                 = false;
$oControle->sFormaAvaliacao                    = '';
$oControle->lExibeAssinatura                   = $oGet->iExibeAssinatura == 2 ? true : false;
$oControle->lExibeTrocaTurma                   = $oGet->iTrocaTurma == 2 ? true : false;

/**
 * Nunca serao impressos no relatorio alunos com as situacoes
 */
$aSituacoes = array("TRANSFERIDO FORA", "CANCELADO", "TROCA DE MODALIDADE");

foreach ($aTurmas as $oDadosTurma) {

  $oTurma                 = new Turma($oDadosTurma->ed57_i_codigo);
  $oEtapa                 = new Etapa($oDadosTurma->codigo_etapa);
  $oProcedimentoAvaliacao = $oTurma->getProcedimentoDeAvaliacaoDaEtapa($oEtapa);
  $aAlunosMatriculados    = $oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapa);
  $aDisciplinas           = $oTurma->getDisciplinasPorEtapa($oEtapa);
  $aElementosAvaliacao    = $oProcedimentoAvaliacao->getElementos();
  $iAnoCalendario         = $oTurma->getCalendario()->getAnoExecucao();

  /**
   * Em funcao do limite de periodos que podem ser impressos em uma pagina
   * temos que controlar o numero paginas que iremos imprimir
   */
  $iNumeroElementosAvaliacao = count($aElementosAvaliacao);
  $iNumeroDePaginas          = 1;
  $iNumeroDeAlunosImpresso   = 0;

  if ($iNumeroElementosAvaliacao > $oControle->iNumeroElementosAvaliacaoPorPagina) {
    $iNumeroDePaginas = ceil($iNumeroElementosAvaliacao / $oControle->iNumeroElementosAvaliacaoPorPagina);
  }

  /**
   * Criamos uma estrutura com os PERIODOS a serem impresso por pagina
   */
  $aElementosAvaliacaoPorPagina = organizaPeriodosPorPagina($iNumeroDePaginas, $iNumeroElementosAvaliacao,
                                                            $oControle, $aElementosAvaliacao);
  /**
   * Imprime uma pagina por Disciplina
   */
  foreach ($aDisciplinas as $oRegencia) {

    $iTotalAlunosTurma = count($aAlunosMatriculados);
    /**
     * Imprime ate 4 periodos por Pagina
     */
    foreach ($aElementosAvaliacaoPorPagina as $iPagina => $aPeriodo) {

      $iTemObservacao   = 0;
      $iNumeroAluno     = 0;
      $iAlunosImpressos = 0;

      /**
       * Calculo do tamanho das colunas dos periodos de avaliacao
       */
      $iLarguraColunaMaxima    = 148;
      $iNumeroColuna           = count($aPeriodo);
      $iLarguraColuna          = $iLarguraColunaMaxima / $iNumeroColuna;
      $iLarguraQuadroAvaliacao = $iLarguraColuna / 3;

      /**
       * Impressao do cabecalho
       */
      if ($lPrimeiroLaco || $oPdf->gety() > $oPdf->h - 8) {

        imprimeCabecalho($oPdf, $oControle, $oTurma, $aPeriodo, $oRegencia);
        $lPrimeiroLaco = false;
      }

      $iLinhasEmBranco = 0;

      foreach ($aAlunosMatriculados as $iAluno => $oMatricula) {

      	if(!$oControle->lExibeTrocaTurma && $oMatricula->getSituacao() == "TROCA DE TURMA") {

      	  /**
      	   * Diminuimos do total de alunos na turma, quando aluno tivér a situação "TROCA DE TURMA" e no filtro
      	   * não estiver marcado para exibir a troca de turma
      	   */
      	  $iTotalAlunosTurma --;
      		continue;
      	}

        /**
         * Variavel para controle da observacao. Caso seja maior que 0 (Aprovado com Progressao Parcial ou Forma de
         * Avaliacao = PARECER), apresentamos a observacao
         */
        $iNumeroAluno ++;
        $iAlunosImpressos ++;
        $iNumeroRealAluno = $iNumeroAluno;

        if ($oMatricula->getNumeroOrdemAluno() != "") {
          $iNumeroRealAluno = $oMatricula->getNumeroOrdemAluno();
        }

        db_inicio_transacao();
        $oGradeAproveitamento = new GradeAproveitamentoAluno($oMatricula);

        db_fim_transacao();

        $oPdf->Cell(5,  $oControle->iAlturaLinha, $iNumeroRealAluno, 1, 0, "C");
        $oPdf->Cell(112, $oControle->iAlturaLinha, $oMatricula->getAluno()->getNome(), 1, 0, "L");

        if ($oMatricula->getSituacao() == "MATRICULADO") {

          foreach ($aPeriodo as $oElementoAvaliacao) {


            $oAproveitamento = $oGradeAproveitamento->getAproveitamentoParaRegenciaPorPeriodo($oRegencia,
                                                                                              $oElementoAvaliacao
                                                                                             );

            $lNotaExterna    = $oAproveitamento->lTemNotaExterna;
            $nAproveitamento = $oAproveitamento->nAproveitamento;
            $nAproveitamento = ArredondamentoNota::formatar($nAproveitamento, $iAnoCalendario);

            /**
             * Verificamos se a forma de avaliacao eh por parecer. Caso seja, o campo das notas para cada periodo,
             * apresentará 'PD'
             */
            if ( $oRegencia->getProcedimentoAvaliacao()->getFormaAvaliacao()->getTipo() == 'PARECER') {

              $nAproveitamento            = 'PD';
              $oControle->sFormaAvaliacao = 'PARECER';
              $iTemObservacao++;
            }

            /**
             * Altera o resultado quando aluno é avaliado por parecer
             */
            if ( $oMatricula->isAvaliadoPorParecer() ) {
              $nAproveitamento = "PD";
            }

            if ($oAproveitamento->lDispensado && $oAproveitamento->nAproveitamento == "") {
              $nAproveitamento = "Dispensado";
            }

            /**
             * Caso o aluno tenha sido amparado para o periodo na disciplina, eh apresentado 'Amp' ao inves da nota
             */
            if ($oAproveitamento->lAmparado) {
              $nAproveitamento = "Amp";
            }

            /**
             * Se o aluno nao atingiu o aproveitamento minimo para o periodo, e ele nao esta amparado ou a forma de
             * avaliacao eh por parecer, mostra a nota em negrito
             */
            if ( !$oAproveitamento->lAtingiuMinimo && !$oAproveitamento->lAmparado
                && $oElementoAvaliacao->getFormaDeAvaliacao()->getTipo() != 'PARECER') {
              $oPdf->setfont('arial', 'b', 7);
            }

            if ($lNotaExterna && $nAproveitamento != '') {
              $nAproveitamento = "*{$nAproveitamento}";
            }

            $oPdf->Cell($iLarguraQuadroAvaliacao * 2, $oControle->iAlturaLinha, $nAproveitamento, 1, 0, "C");
            $oPdf->setfont('arial', '', 7);
            $oPdf->Cell($iLarguraQuadroAvaliacao, $oControle->iAlturaLinha, $oAproveitamento->iFaltas, 1, 0, "C");
          }

          $oResultadoFinal = $oGradeAproveitamento->getResultadoFinalDaRegencia($oRegencia);
          $aTermosAprovado = DBEducacaoTermo::getTermoEncerramento($oEtapa->getEnsino()->getCodigo(),
                                                                   $oResultadoFinal->getResultadoFinal(),
                                                                   $iAnoCalendario
                                                                  );
          $sLabelAprovado = '';
          if (count($aTermosAprovado) > 0 && $oResultadoFinal->getResultadoFinal() != '') {
            $sLabelAprovado = $aTermosAprovado[0]->sAbreviatura;
          }

          /**
           * Caso o aluno tenha sido aprovado com progressao parcial para a disciplina, apresenta um * junto do
           * resultado
           */

          if ($oGradeAproveitamento->aprovadoComProgressaoParcial($oRegencia)) {

            $sLabelAprovado                = $sLabelAprovado."*";
            $oControle->lProgressaoParcial = true;
            $iTemObservacao++;
          }

          $oPdf->Cell(15, $oControle->iAlturaLinha, $sLabelAprovado, 1, 1, "C");
        } else {
          $oPdf->Cell(163, $oControle->iAlturaLinha, $oMatricula->getSituacao(), 1, 1, "C");
        }

        /**
         * Quebra a página quando o número de alunos impressos é >= ao numero de alunos configurados para imprimir por
         * página
         */
        if ($iAlunosImpressos >= $oControle->iNumeroAlunosPorPagina) {

        	if ($oControle->lExibeAssinatura) {
        		imprimeAssinaturas($oPdf, $oControle);
        	}

        	/**
        	 * Quando foi impresso o total de alunos da turma, devemos sair do laço
        	 */
          if ($iAlunosImpressos == $iTotalAlunosTurma) {

            $oPdf->Ln();
            continue;
          }

          $iAlunosImpressos = 0;
          imprimeCabecalho($oPdf, $oControle, $oTurma, $aPeriodo, $oRegencia);
        }
      }

      if ($iTotalAlunosTurma == $iNumeroAluno) {
        $lPrimeiroLaco = true;
      }

      /**
       * Imprime linhas em branco
       */
      if ($iAlunosImpressos < $oControle->iNumeroAlunosPorPagina) {

        $iLinhasEmBranco = $oControle->iNumeroAlunosPorPagina - $iAlunosImpressos;
        for ($i = 1; $i < $iLinhasEmBranco; $i++) {

          $oPdf->Cell(5,  $oControle->iAlturaLinha, '', 1, 0, "C");
          $oPdf->Cell(112, $oControle->iAlturaLinha, '', 1, 0, "L");

          foreach ($aPeriodo as $oElementoAvaliacao) {

            $oPdf->Cell($iLarguraQuadroAvaliacao * 2, $oControle->iAlturaLinha, '', 1, 0, "C");
            $oPdf->Cell($iLarguraQuadroAvaliacao, $oControle->iAlturaLinha, '', 1, 0, "C");
          }
          $oPdf->Cell(15, $oControle->iAlturaLinha, '', 1, 1, "C");
        }
      }
    }

    $lPrimeiroLaco = true;

    /**
     * Caso exista ao menos 1 aluno que necessite apresentar observacao, chamamos o metodo para impressao
     */
    if ($iTemObservacao > 0) {

      /**
       * Pegamos a posicao final de Y e X ao terminar de imprimir os alunos
       */
      $oControle->iPosicaoX = $oPdf->GetX();
      $oControle->iPosicaoY = $oPdf->GetY();
      mostraObservacoes($oPdf, $oControle);
    }

    if ($oControle->lExibeAssinatura) {
    	imprimeAssinaturas($oPdf, $oControle);
    }
  }
}

$oPdf->Output();

/**
 *
 * @param SCPF     $oPdf
 * @param stdClass $oControle
 * @param Turma    $oTurma
 * @param array    $aPeriodo
 * @param Regencia $oRegencia
 * @param integer  $iNumeroDaPagina
 */
function imprimeCabecalho( scpdf $oPdf, $oControle, $oTurma, $aPeriodo, $oRegencia ) {

  $sNomeDisciplinaAbreviado = trim($oRegencia->getDisciplina()->getAbreviatura());
  $sNomeDisciplina          = trim($oRegencia->getDisciplina()->getNomeDisciplina());

  $oPdf->AddPage();
  $oPdf->setfont('arial', 'b', 7);

  $oPdf->Cell(117,  $oControle->iAlturaLinha, "DADOS DE IDENTIFICAÇÃO-LIVRO NOTAS", 1, 0, "C", 1);

  $iLarguraColunaMaxima = 148;
  $iNumeroColuna        = count($aPeriodo);
  $iLarguraColuna       = $iLarguraColunaMaxima / $iNumeroColuna;

  /** *************************** *
   * Primeira Linha do Cabecalho  *
   ** *************************** */
  foreach ($aPeriodo as $oPeriodo) {

    $sDescricaoPeriodo = '';
    if ($oPeriodo instanceof AvaliacaoPeriodica) {
      $sDescricaoPeriodo = $oPeriodo->getPeriodoAvaliacao()->getDescricao();
    }

    if ($oPeriodo instanceof ResultadoAvaliacao) {
      $sDescricaoPeriodo = $oPeriodo->getTipoResultado()->getDescricao();
    }

    $oPdf->Cell($iLarguraColuna, $oControle->iAlturaLinha, $sDescricaoPeriodo, 1, 0, "C", 1);
  }
  $oPdf->Cell(15, $oControle->iAlturaLinha, " ", "LTR", 1, "C");

  /** ************************** *
   * Segunda Linha do Cabecalho  *
   ** ************************** */

  $oPdf->setfont('arial', '', 7);
  /**
   * Quadro de Identificacao do Livro de Notas
   */
  $iYInicioQuadroNotas = $oPdf->GetY();

  /**
   * Valida se a escola possui Código Referência e o adiciona na frente do nome
   */
  $sNomeEscola = $oTurma->getCalendario()->getEscola()->getNome();
  $iCodigoReferencia = $oTurma->getCalendario()->getEscola()->getCodigoReferencia();

  if ( $iCodigoReferencia != null ) {
    $sNomeEscola = "{$iCodigoReferencia} - {$sNomeEscola}";
  }

  $sProfessor  = "";
  $aRegente  = $oRegencia->getDocentes();

  if (count($aRegente) > 0) {

  	foreach ($aRegente as $oRegente) {

	  	$sProfessor  = $oRegente->getNome();
	  	break;
  	}
  }

  $sDados  = "Escola: {$sNomeEscola}\n";
  $sDados .= "Profº: {$sProfessor}\n";
  $sDados .= "Série: {$oTurma->getBaseCurricular()->getDescricao()}\n";
  $sDados .= "Turma: {$oTurma->getDescricao()}\n";
  $sDados .= "Turno: {$oTurma->getTurno()->getDescricao()}\n";
  $sDados .= "Dias Letivos: {$oTurma->getCalendario()->getDiasLetivos()}  ";
  $sDados .= "Ano: {$oTurma->getCalendario()->getAnoExecucao()}\n";
  $sDados .= "Disciplina: {$sNomeDisciplinaAbreviado} - {$sNomeDisciplina}";

  $oPdf->SetXY(10, 14);
  $oPdf->SetFont('arial', '', 7);
  $oPdf->MultiCell(115, 4, $sDados, 0);

  $oPdf->Rect(8, 8, 117, $oPdf->GetY() - 8);

  $iYFimQuadroNotas = $oPdf->GetY();

  /**
   * Imprimiremos o Nome da Disciplina e Falta
   */
  $oPdf->setfont('arial', '', 7);
  $oPdf->SetXY(125, $iYInicioQuadroNotas);
  $iAlturaQuadroDisciplina = $iYFimQuadroNotas - $iYInicioQuadroNotas;
  $iLarguraQuadroAvaliacao = $iLarguraColuna / 3;

  foreach ($aPeriodo as $oPeriodo) {

    $oPdf->Cell($iLarguraQuadroAvaliacao * 2, $iAlturaQuadroDisciplina, $sNomeDisciplinaAbreviado, 1, 0, "C");
    $oPdf->VCell($iLarguraQuadroAvaliacao, $iAlturaQuadroDisciplina, "Faltas", 1, 0, "C");
  }

  $oPdf->VCell(15, $iAlturaQuadroDisciplina, "RESULTADO FINAL", "LBR", 1, "C");
}

/**
 * Retorna uma estrutura organizada com os Periodos que serão impresso em uma pagina
 * @param integer  $iNumeroDePaginas
 * @param integer  $iNumeroElementosAvaliacao
 * @param stdClass $oControle
 * @param array    $aElementosAvaliacao
 * @return array   <PeriodoAvaliacao, ResultadoAvaliacao>
 */
function organizaPeriodosPorPagina($iNumeroDePaginas, $iNumeroElementosAvaliacao, $oControle, $aElementosAvaliacao) {

  $iNumeroElementosMovidos      = 0;
  $aElementosAvaliacaoPorPagina = array();

  for ($iPagina = 1; $iPagina <= $iNumeroDePaginas; $iPagina++) {

    for ($iColuna = 0; $iColuna < $oControle->iNumeroElementosAvaliacaoPorPagina; $iColuna++) {

      if ($iNumeroElementosMovidos == $iNumeroElementosAvaliacao) {
        break;
      }

      $iNumeroElementosMovidos++;
      $aElementosAvaliacaoPorPagina[$iPagina][$iColuna] = $aElementosAvaliacao[$iNumeroElementosMovidos];
    }
  }

  return $aElementosAvaliacaoPorPagina;
}

/**
 * Método que imprime as observacoes em casos de aprovado com progressao parcial ou parecer descritivo
 * @param scpdf $oPdf
 * @param stdClass $oDadosRelatorio
 */
function mostraObservacoes( scpdf $oPdf, $oControle ) {

  $sObservacoes = '';

  $iTamanhoRect = 15;
  if ($oControle->lProgressaoParcial) {

    $sObservacoes .= "OBSERVAÇÕES:\n";
    $sObservacoes .= "* Aprovado com Progressão Parcial\n";
  }

  if ($oControle->sFormaAvaliacao == 'PARECER') {

    $sObservacoes .= "LEGENDA:\n";
    $sObservacoes .= "PD - Parecer Descritivo";
  }
  $oPdf->Ln(1);
  if (trim($sObservacoes) != '') {
    $oPdf->MultiCell(280, 4, $sObservacoes, 0);
  }
  $oPdf->Rect($oControle->iPosicaoX, $oControle->iPosicaoY, 280, $iTamanhoRect);
}

/**
 * Imprime assinaturas de acordo com o parâmettro $oControle->lExibeAssinatura
 * @param scpdf $oPdf
 * @param stdClass $oControle
 */
function imprimeAssinaturas( scpdf $oPdf, $oControle ) {

	$oPdf->Ln();
	$oPdf->Ln();
	$oPdf->SetX(90);
	$oPdf->Cell(50,  $oControle->iAlturaLinha, 'Data: _____/_____/__________', 0, 0);
	$oPdf->Cell(100, $oControle->iAlturaLinha, '____________________________________________________', 0, 1);
	$oPdf->SetX(90);
	$oPdf->Cell(160, $oControle->iAlturaLinha, 'Assinatura do Professor', 0, 1, "C");
}
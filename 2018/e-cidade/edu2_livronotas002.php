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

require_once(modification("fpdf151/scpdf.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("std/DBDate.php"));

$oDados         = db_utils::postMemory($_GET);
$oJson          = new services_json();
$aRetornoTurmas = $oJson->decode(str_replace("\\","",$_GET["aTurmas"]));

/**
 * Objeto com os dados do relatorio
 */
$oDadosRelatorio = new stdClass();

/**
 * Imprimindo relatorio
 */
$oPdf = new scpdf("L");
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(true);
$oPdf->SetRightMargin(8);
$oPdf->SetFillColor(225);

/**
 * Limite de disciplinas por pagina
 */
$oDadosRelatorio->iDisciplinasPorPagina = 11;

/**
 * Limite de aluno por pagina
 */
$oDadosRelatorio->iTotalAlunoPorPagina = 32;

/**
 * Tamanho de cada coluna das disciplinas e das faltas
 */
$oDadosRelatorio->iTamanhoColunasDisciplinas = 8;
$oDadosRelatorio->iTamanhoColunasFaltas      = 6;

/**
 * Altura das celulas
 */
$oDadosRelatorio->iAltura = 4;

/**
 * Atribuimos ao objeto Pdf os dados passados por parametro
 */
$oDadosRelatorio->oDados = $oDados;
$aTurmas                 = explode(",", $oDadosRelatorio->oDados->aTurmas);
$oCalendario             = new Calendario($oDadosRelatorio->oDados->iCalendario);

/**
 * Percorremos as turmas/etapas passadas por parametro
 */
foreach ($aRetornoTurmas as $oRetornoTurma) {

  $oTurma    = TurmaRepository::getTurmaByCodigo($oRetornoTurma->ed57_i_codigo);
  $oEtapa    = EtapaRepository::getEtapaByCodigo($oRetornoTurma->codigo_etapa);
  $oElemento = $oTurma->getProcedimentoDeAvaliacaoDaEtapa($oEtapa);

  /**
   * Atribuimos ao objeto Pdf os dados necessarios para impressao
   */
  $oDadosRelatorio->sTurma                          = $oTurma->getDescricao();
  $oDadosRelatorio->sTurno                          = $oTurma->getTurno()->getDescricao();
  $oDadosRelatorio->iAno                            = $oTurma->getCalendario()->getAnoExecucao();
  $oDadosRelatorio->iDiasLetivos                    = $oTurma->getCalendario()->getDiasLetivos();
  $oDadosRelatorio->aRegencias                      = $oTurma->getDisciplinasPorEtapa($oEtapa);
  $oDadosRelatorio->iEnsino                         = $oTurma->getBaseCurricular()->getCurso()->getEnsino()->getCodigo();
  $oDadosRelatorio->sDocente                        = '';
  $oDadosRelatorio->sFormaAvaliacao                 = '';
  $oDadosRelatorio->ltemObservacaoProgressaoParcial = false;
  $oDadosRelatorio->iTrocaTurma                     = $oDados->iTrocaTurma;

  $oConselheiro = $oTurma->getProfessorConselheiro();
  if (!empty($oConselheiro)) {

    $oDocente = $oTurma->getProfessorConselheiro();
    if ( !is_null($oDocente->getCodigoDocente()) ) {
      $oDadosRelatorio->sDocente = $oTurma->getProfessorConselheiro()->getNome();
    }
  }

  /**
   * Buscamos os alunos matriculados na turma
   */
  $oDadosRelatorio->aAlunosMatriculados = array();
  $oDadosRelatorio->aAlunosMatriculados = $oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapa);
  $oDadosRelatorio->iTotalAlunos        = count($oDadosRelatorio->aAlunosMatriculados);
  $oDadosRelatorio->sEtapa              = $oEtapa->getNome();
  $oDadosRelatorio->iTotalDisciplinas   = 0;

  /**
   * Variaveis para controle do array do limite de disciplinas.
   */
  $iPagina               = 0;
  $iContadorAux          = 0;
  $aDisciplinasPorPagina = array();

  /**
   * Organizamos um array com o limite de disciplinas por pagina, e o total de paginas
   */
  foreach ($oDadosRelatorio->aRegencias as $oRegencia) {

    $aDisciplinasPorPagina[$iPagina][$iContadorAux] = $oRegencia;

    if ($iContadorAux >= $oDadosRelatorio->iDisciplinasPorPagina - 1) {

      $iPagina++;
      $iContadorAux = 0;
    }

    $iContadorAux++;
    $oDadosRelatorio->iTotalDisciplinas++;
  }
  /**
   * Percorremos cada periodo do calendario por turma
   */
  foreach ($oElemento->getElementos() as $oPeriodo) {

    $oDadosRelatorio->iPeriodo = $oPeriodo->getCodigo();
    $oDadosRelatorio->iOrdem   = $oPeriodo->getOrdemSequencia();

    /**
     * Validamos o tipo de instancia do periodo, para atribuir a descricao e um tipo de acordo com o retornado
     * $oPdf->iTipoPeriodo:
     * 1 - AvaliacaoPeriodica
     * 2 - ResultadoAvaliacao
     */
    if ($oPeriodo instanceof AvaliacaoPeriodica) {

      $oDadosRelatorio->sPeriodo     = $oPeriodo->getPeriodoAvaliacao()->getDescricao();
      $oDadosRelatorio->iTipoPeriodo = 1;
    } else {

      $oDadosRelatorio->sPeriodo     = $oPeriodo->getTipoResultado()->getDescricao();
      $oDadosRelatorio->iTipoPeriodo = 2;
    }

    /**
     * Iteramos sobre o array das disciplinas, chamando as funcoes para impressao do relatorio
     */
    foreach ($aDisciplinasPorPagina as $iDisciplina => $aDisciplinasPagina) {

      $oPdf->AddPage();

      cabecalhoPadrao($oPdf, $oDadosRelatorio);
      posicionamentoCabecalho($oPdf, $oDadosRelatorio);
      cabecalhoPeriodosDisciplinas($oPdf, $aDisciplinasPagina, $oDadosRelatorio);
      imprimeGradeAproveitamentoAluno($oPdf, $aDisciplinasPagina, $oDadosRelatorio);
    }
  }
}

/**
 * Imprimimos a grade de aproveitamento de cada aluno
 */
function imprimeGradeAproveitamentoAluno( scpdf $oPdf, $aDisciplinasPagina, $oDadosRelatorio ) {

  $iLinhasImpressas = 1;
  $oPdf->SetY(54);
  $oPdf->SetFont('arial', '', 7);

  /**
   * Percorremos a matricula de cada aluno, para montar a grade de aproveitamento
   */
  foreach ($oDadosRelatorio->aAlunosMatriculados as $oMatricula) {

    if ($oDadosRelatorio->iTrocaTurma == 1 && $oMatricula->getSituacao() == 'TROCA DE TURMA') {
      continue;
    }

    $oDadosRelatorio->lProgressaoParcial = false;
    $iTemObservacao                      = 0;

    $oPdf->SetX(10);
    $iContadorDisciplinas = 1;

    if ($iLinhasImpressas > $oDadosRelatorio->iTotalAlunoPorPagina || $oPdf->gety() > $oPdf->h - 8) {

      $oPdf->AddPage();
      cabecalhoPadrao($oPdf, $oDadosRelatorio);
      posicionamentoCabecalho($oPdf, $oDadosRelatorio);
      cabecalhoPeriodosDisciplinas($oPdf, $aDisciplinasPagina, $oDadosRelatorio);

      $oPdf->SetY(54);
      $oPdf->SetX(10);
      $oPdf->SetFont('arial', '', 7);
      $iLinhasImpressas = 1;
    }

    $oPdf->SetFont('arial', '', 7);
    /**
     * Imprimimos o aluno e setamos a posicao do "X"
     */
    $oPdf->Cell(5,   $oDadosRelatorio->iAltura, $oMatricula->getNumeroOrdemAluno(), 1, 0, "C");
    $oPdf->Cell(112, $oDadosRelatorio->iAltura, $oMatricula->getAluno()->getNome(), 1, 0, "L");

    $oPdf->SetX($oPdf->GetX());

    /**
     * Verificamos se o aluno nao esta matriculado na turma, apresentando o status na grade
     */
    if ($oMatricula->getSituacao() != 'MATRICULADO') {

      $iTamanhoColuna = ($oDadosRelatorio->iDisciplinasPorPagina * 2) * 7;
      $oPdf->Cell($iTamanhoColuna, $oDadosRelatorio->iAltura, $oMatricula->getSituacao(), 1, 0, "C");
    } else {

      /**
       * Percorremos cada regencia do aluno para preenchimento das notas
       */
      foreach ($aDisciplinasPagina as $oRegencia) {

        db_inicio_transacao();
        $oAproveitamentoPeriodo     = '';
        $oDiarioClasse              = $oMatricula->getDiarioDeClasse();
        $oDiarioAvaliacaoDisciplina = $oMatricula->getDiarioDeClasse()
                                                 ->getDisciplinasPorRegencia(new Regencia($oRegencia->getCodigo()));
        db_fim_transacao();

        foreach( $oDiarioAvaliacaoDisciplina->getAvaliacoes() as $oAvaliacaoAproveitamento ) {

          if( $oAvaliacaoAproveitamento->getElementoAvaliacao()->getOrdemSequencia() == $oDadosRelatorio->iOrdem ) {
            $oAproveitamentoPeriodo = $oAvaliacaoAproveitamento;
          }
        }

        $sAproveitamento = "";
        $iNumeroFaltas   = "";
        $sResultadoFinal = "";
        $lNotaExterna    = false;
        $iAnoCalendario  = $oRegencia->getTurma()->getCalendario()->getAnoExecucao();


        if ( $oAproveitamentoPeriodo != "" ) {

          $sAproveitamento = $oAproveitamentoPeriodo->getValorAproveitamento()->getAproveitamentoReal();
          $sAproveitamento = ArredondamentoNota::formatar($sAproveitamento, $iAnoCalendario);
          $lNotaExterna    = $oAproveitamentoPeriodo->isAvaliacaoExterna();
          $iNumeroFaltas   = $oAproveitamentoPeriodo->getNumeroFaltas();
        }

        /**
         * Verificamos se trata-se do resultado da avaliacao ($oPdf->iTipoPeriodo = 2), buscando o total de faltas
         */
        if ($oDadosRelatorio->iTipoPeriodo == 2) {

          $iNumeroFaltas   = $oDiarioAvaliacaoDisciplina->getTotalFaltas();
          $sResultadoFinal = $oDiarioClasse->getResultadoFinal();

          /**
           * Verificamos o termo a ser utilizado para o ensino
           */
          if (!empty($oDadosRelatorio->iEnsino) && ($sResultadoFinal == 'A' || $sResultadoFinal == 'R')) {

            $aDadosTermo = DBEducacaoTermo::getTermoEncerramento($oDadosRelatorio->iEnsino, $sResultadoFinal, $iAnoCalendario);
            if (isset($aDadosTermo[0])) {
              $sResultadoFinal = $aDadosTermo[0]->sAbreviatura;
            }
          }

          /**
           * Verificamos se o aluno foi aprovado na disciplina, com progressao parcial, adicionando um * ao fim e
           * mostrando nas observações
           */
          if ($oDiarioAvaliacaoDisciplina->aprovadoComProgressaoParcial()) {

            $oDadosRelatorio->lProgressaoParcial              = true;
            $oDadosRelatorio->ltemObservacaoProgressaoParcial = true;
            $iTemObservacao++;
          }
        }

        /**
         * Caso a forma de avaliacao seja PARECER, cada periodo apresentará apenas 'PD', com excessão do Resultado
         * Final.
         * Se o aluno for amparado para a disciplina no periodo, é apresentado 'Amp'
         */
        if ( $oAproveitamentoPeriodo != "" ) {

          if ( $oRegencia->getProcedimentoAvaliacao()->getFormaAvaliacao()->getTipo() == 'PARECER') {

            $sAproveitamento                  = "PD";
            $oDadosRelatorio->sFormaAvaliacao = 'PARECER';
            $iTemObservacao++;
          }

          if ($oAproveitamentoPeriodo->isAmparado()) {
            $sAproveitamento = "Amp";
          }
        }

        /**
         * Altera o resultado quando aluno é avaliado por parecer
         */
        if ( $oMatricula->isAvaliadoPorParecer() ) {
          $sAproveitamento = "PD";
        }

        if ( $lNotaExterna && $sAproveitamento != '' ) {
          $sAproveitamento = "*{$sAproveitamento}";
        }

        if (empty($sAproveitamento)) {
          $sAproveitamento = "-";
        }

        /**
         * Verificamos se foi atingido o limite maximo de disciplinas, para entao quebrar a pagina
         */
        if ($iContadorDisciplinas > $oDadosRelatorio->iDisciplinasPorPagina) {
          $iContadorDisciplinas = 1;
        }

        /**
         * Verificamos se atingiu o aproveitamento minimo no periodo. Caso nao, alteramos a fonte para negrito, desde
         * que nao seja PARECER, nem esteja amparado
         */
        if (   $oAproveitamentoPeriodo != ""
            && !$oAproveitamentoPeriodo->temAproveitamentoMinimo()
            && $oAproveitamentoPeriodo->getElementoAvaliacao()->getFormaDeAvaliacao()->getTipo() != 'PARECER'
            && !$oAproveitamentoPeriodo->isAmparado()) {
          $oPdf->SetFont('arial', 'b', 7);
        }

        $oPdf->Cell($oDadosRelatorio->iTamanhoColunasDisciplinas, $oDadosRelatorio->iAltura, "{$sAproveitamento}", 1, 0, "C");

        $oPdf->SetFont('arial', '', 7);
        $oPdf->Cell($oDadosRelatorio->iTamanhoColunasFaltas, $oDadosRelatorio->iAltura, "{$iNumeroFaltas}",   1, 0, "C");
        $iContadorDisciplinas++;
      }

      /**
       * Caso nao tenham sido preenchidos todos os campos de aproveitamento, completamos com colunas em branco
       */
      if ($iContadorDisciplinas <= $oDadosRelatorio->iDisciplinasPorPagina) {

        $iDisciplinasPorPagina = $oDadosRelatorio->iDisciplinasPorPagina;

        /**
         * Verificamos se trata-se do resultado da avaliacao ($oPdf->iTipoPeriodo = 2), removendo a impressao de uma
         * coluna disciplina
         */
        if ($oDadosRelatorio->iTipoPeriodo == 2) {
          $iDisciplinasPorPagina = $oDadosRelatorio->iDisciplinasPorPagina - 1;
        }

        for ($iContador = $iContadorDisciplinas; $iContador <= $iDisciplinasPorPagina; $iContador++) {

          $oPdf->Cell($oDadosRelatorio->iTamanhoColunasDisciplinas, $oDadosRelatorio->iAltura, "", 1, 0, "C");
          $oPdf->Cell($oDadosRelatorio->iTamanhoColunasFaltas, $oDadosRelatorio->iAltura, "", 1, 0, "C");
        }

        /**
         * Verificamos se trata-se do resultado da avaliacao ($oPdf->iTipoPeriodo = 2), imprimindo o resultado
         */
        if ($oDadosRelatorio->iTipoPeriodo == 2) {

          $oPdf->Cell($oDadosRelatorio->iTamanhoColunasFaltas, $oDadosRelatorio->iAltura, "", 1, 0, "C");

          if ($oDadosRelatorio->lProgressaoParcial) {
            $sResultadoFinal = $sResultadoFinal."*";
          }
          $oPdf->Cell($oDadosRelatorio->iTamanhoColunasDisciplinas, $oDadosRelatorio->iAltura, $sResultadoFinal, 1, 0, "C");
        }
      }
    }
    $iLinhasImpressas++;
    $oPdf->Ln();
  }

  /**
   * Caso exista ao menos 1 aluno que necessite apresentar observacao, chamamos o metodo para impressao
   */
  if ($iTemObservacao > 0) {

    /**
     * Pegamos a posicao final de Y e X ao terminar de imprimir os alunos
     */
    $oDadosRelatorio->iPosicaoX = $oPdf->GetX();
    $oDadosRelatorio->iPosicaoY = $oPdf->GetY();
    mostraObservacoes($oPdf, $oDadosRelatorio);
  }
}

/**
 * Montamos o cabecalho das disciplinas/faltas por periodo
 */
function cabecalhoPeriodosDisciplinas( scpdf $oPdf, $aDisciplinasPagina, $oDadosRelatorio ) {

  /**
   * Controlador de disciplinas impressas
   */
  $iContadorDisciplinas = 1;

  /**
   * Percorremos as disciplinas a serem impressas
   */
  foreach ($aDisciplinasPagina as $oRegencia) {

    /**
     * Verificamos se foi atingido o limite maximo de disciplinas, para entao quebrar a pagina
     */
    if ($iContadorDisciplinas > $oDadosRelatorio->iDisciplinasPorPagina) {
      $iContadorDisciplinas = 1;
    }

    $oPdf->VCell($oDadosRelatorio->iTamanhoColunasDisciplinas, 40, $oRegencia->getDisciplina()->getNomeDisciplina(), 1, 0, 'C', 0);
    $oPdf->VCell($oDadosRelatorio->iTamanhoColunasFaltas, 40, "Faltas",                                         1, 0, 'C', 0);
    $iContadorDisciplinas++;
  }

  /**
   * Caso nao tenha sido preenchido o total de disciplinas permitido por pagina, completamos com colunas em branco
   */
  if ($iContadorDisciplinas <= $oDadosRelatorio->iDisciplinasPorPagina) {

    $iDisciplinasPorPagina = $oDadosRelatorio->iDisciplinasPorPagina;

    /**
     * Verificamos se trata-se do resultado da avaliacao ($oPdf->iTipoPeriodo = 2), removendo a impressao de uma
     * coluna disciplina
     */
    if ($oDadosRelatorio->iTipoPeriodo == 2) {
      $iDisciplinasPorPagina = $oDadosRelatorio->iDisciplinasPorPagina - 1;
    }

    for ($iContador = $iContadorDisciplinas; $iContador <= $iDisciplinasPorPagina; $iContador++) {

      $oPdf->VCell($oDadosRelatorio->iTamanhoColunasDisciplinas, 40, "", 1, 0, 'C', 0);
      $oPdf->VCell($oDadosRelatorio->iTamanhoColunasFaltas, 40, "", 1, 0, 'C', 0);
    }
  }

  /**
   * Verificamos se trata-se do resultado da avaliacao ($oPdf->iTipoPeriodo = 2), imprimindo o texto do resultado
   */
  if ($oDadosRelatorio->iTipoPeriodo == 2 && $oDadosRelatorio->iTotalDisciplinas < $oDadosRelatorio->iDisciplinasPorPagina) {

    $oPdf->VCell($oDadosRelatorio->iTamanhoColunasFaltas, 40, "", 1, 0, 'C', 0);
    $oPdf->VCell($oDadosRelatorio->iTamanhoColunasDisciplinas, 40, "Apr. / Rep. RESULTADO", 1, 0, 'C', 0);
  }
}

/**
 * Metodo com as posicoes padroes dos periodos e disciplinas/faltas
 */
function posicionamentoCabecalho( scpdf $oPdf, $oDadosRelatorio) {

  $oPdf->SetXY(127, 10);

  $oPdf->SetFont('arial', 'bi', 7);
  $oPdf->Cell(154, $oDadosRelatorio->iAltura, $oDadosRelatorio->sPeriodo, 1, 1, 'C', 1);

  $oPdf->SetXY(127, 14);
}

/**
 * Montamos o cabecalho padrao do relatorio
 */
function cabecalhoPadrao( scpdf $oPdf, $oDadosRelatorio ) {

  $oPdf->SetXY(10, 10);

  /**
   * Buscamos o nome do escola
   * Valida se a escola possui Código Referência e o adiciona na frente do nome
   */
  $oEscola  = new Escola($oDadosRelatorio->oDados->iEscola);

  $sNomeEscola       = $oEscola->getNome();
  $iCodigoReferencia = $oEscola->getCodigoReferencia();

  if ( $iCodigoReferencia != null ) {
    $sNomeEscola = "{$iCodigoReferencia} - {$sNomeEscola}";
  }

  $oDadosRelatorio->sEscola = $sNomeEscola;
  $sTituloPadrao            = "DADOS DE IDENTIFICAÇÃO - LIVRO NOTAS";

  $oPdf->SetFont('arial', 'bi', 7);
  $oPdf->Cell(117, $oDadosRelatorio->iAltura, $sTituloPadrao, 1, 0, 'C', 1);

  $sDados  = "Escola: {$oDadosRelatorio->sEscola}\n";
  $sDados .= "Profº: {$oDadosRelatorio->sDocente}\n";
  $sDados .= "Série: {$oDadosRelatorio->sEtapa}\n";
  $sDados .= "Turma: {$oDadosRelatorio->sTurma}\n";
  $sDados .= "Turno: {$oDadosRelatorio->sTurno}\n";
  $sDados .= "Dias Letivos: {$oDadosRelatorio->iDiasLetivos}               ";
  $sDados .= "Ano: {$oDadosRelatorio->iAno}\n\n\n\n";

  $oPdf->SetXY(10, 14);
  $oPdf->SetFont('arial', '', 7);
  $oPdf->MultiCell(117, 4, $sDados, 0);

  $oPdf->Rect(10, 10, 117, 44);
}

/**
 * Método que imprime as observacoes em casos de aprovado com progressao parcial ou parecer descritivo
 * @param SCPF $oPdf
 * @param object $oDadosRelatorio
 */
function mostraObservacoes( scpdf $oPdf, $oDadosRelatorio ) {

  $sObservacoes = '';

  /**
   * 202 eh o limite maximo permitido na pagina
   */
  $iTamanhoRect  = 202 - $oDadosRelatorio->iPosicaoY;

  if ($oDadosRelatorio->ltemObservacaoProgressaoParcial) {

    $sObservacoes .= "OBSERVAÇÕES:\n";
    $sObservacoes .= "* Aprovado com Progressão Parcial\n";
  }

  if ($oDadosRelatorio->sFormaAvaliacao == 'PARECER') {

    $sObservacoes .= "\nLEGENDA:\n";
    $sObservacoes .= "PD - Parecer Descritivo";
  }

  $oPdf->MultiCell(271, 4, $sObservacoes, 0);
  $oPdf->Rect($oDadosRelatorio->iPosicaoX, $oDadosRelatorio->iPosicaoY, 271, $iTamanhoRect);
}

$oPdf->Output();
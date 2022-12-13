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
require_once ("fpdf151/FpdfMultiCellBorder.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_stdlibwebseller.php" );
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");
require_once ("libs/db_libdocumento.php");

$oGet   = db_utils::postMemory( $_GET );
$oEtapa = EtapaRepository::getEtapaByCodigo( $oGet->iEtapa );
$oTurma = TurmaRepository::getTurmaByCodigo( $oGet->iTurma );

/**
 * Objeto com dados a serem utilizados para valida��es e impress�o do relat�rio
 */
$oDadosRelatorio                        = new stdClass();
$oDadosRelatorio->aDisciplinasImpressao = explode( ",", $oGet->aRegencias );
$oDadosRelatorio->lTemNotaParcial       = VerParametroNota( db_getsession("DB_coddepto") ) == 'S';
$oDadosRelatorio->iMaximoPeriodosPagina = 9;
$oDadosRelatorio->iMinimoAlunosPagina   = 20;
$oDadosRelatorio->iColunaNumero         = 5;
$oDadosRelatorio->iColunaAluno          = 65;
$oDadosRelatorio->iColunaPeriodo        = 20;
$oDadosRelatorio->iColunaAvaliacao      = 15;
$oDadosRelatorio->iColunaFalta          = 5;
$oDadosRelatorio->iColunaResultadoFinal = 30;
$oDadosRelatorio->iAltura               = 4;
$oDadosRelatorio->lExibeTrocaTurma      = $oGet->lExibirTrocaTurma == 'S';
$oDadosRelatorio->aAprovadosConselho    = array();
$oDadosRelatorio->iDisciplinaAtual      = '';
$oDadosRelatorio->sSituacaoAlunoAtual   = '';
$oDadosRelatorio->aSituacoesAluno       = array(
                                                 'AVAN�ADO'             => 'AVAN�ADO',
                                                 'CANCELADO'            => 'CANCELADO',
                                                 'EVADIDO'              => 'EVADIDO',
                                                 'FALECIDO'             => 'FALECIDO',
                                                 'CLASSIFICADO'         => 'CLASSIFICADO',
                                                 'RECLASSIFICADO'       => 'RECLASSIFI',
                                                 'MATRICULA TRANCADA'   => 'MT',
                                                 'TRANSFERIDO REDE'     => 'TR',
                                                 'TRANSFERIDO FORA'     => 'TF',
                                                 'TROCA DE TURMA'       => 'TT',
                                                 'TROCA DE MODALIDADE'  => 'TM',
                                                 'MATRICULA INDEVIDA'   => 'MI',
                                                 'MATRICULA INDEFERIDA' => 'IN'
                                               );
$oDadosRelatorio->aEstruturaGeral = montaEstruturaGeral( $oTurma, $oEtapa, $oDadosRelatorio );

$oPdf = new FpdfMultiCellBorder( 'L' );
$oPdf->Open();
$oPdf->exibeHeader( true );
$oPdf->setExibeBrasao( true );

/**
 * Dados do cabe�alho
 */
$sCurso = $oTurma->getBaseCurricular()->getCurso()->getCodigo() . ' - ' . $oTurma->getBaseCurricular()->getCurso()->getNome();

$head1 = "FICHA DE RESUMO DE APROVEITAMENTO";
$head2 = "Curso: {$sCurso}";
$head3 = "Turno: {$oTurma->getTurno()->getDescricao()}";
$head4 = "Calend�rio: {$oTurma->getCalendario()->getDescricao()}";
$head5 = "Turma: {$oTurma->getDescricao()}";
$head6 = "Etapa: {$oEtapa->getNome()}";

/**
 * Percorre a estrutura criada, quebrando primeiramente por disciplina
 */
foreach( $oDadosRelatorio->aEstruturaGeral as $oDisciplina ) {

  $head7 = "Disciplina: {$oDisciplina->sDisciplina}";
  $head8 = "Regente: {$oDisciplina->sRegente}";

  /**
   * Dentro da disciplina, percorre os per�odos configurados para a turma
   */
  foreach( $oDisciplina->aPeriodos as $iPeriodo => $aPaginaPeriodo ) {

    $iContadorPaginasAluno = 0;
    $iTotalPaginasAluno    = count( $oDisciplina->aAlunos );

    /**
     * Percorre o array dos alunos organizado por p�gina matriculados na turma
     */
    foreach( $oDisciplina->aAlunos as $aPaginaAluno ) {

      $oPdf->AddPage( 'L' );
      $oPdf->SetAutoPageBreak( false );
      $oPdf->SetFont( 'arial', 'b', 7 );

      /**
       * Imprime as 2 primeiras linhas da grade: Per�odos e SubCabe�alho
       */
      linhaPeriodos( $oPdf, $aPaginaPeriodo, $oDadosRelatorio );
      linhaSubCabecalho( $oPdf, $aPaginaPeriodo, $oDadosRelatorio, 1 );

      $iContadorPaginasAluno++;
      $iTotalAlunosPagina = count( $aPaginaAluno );

      /**
       * Percorre os alunos da p�gina atual
       */
      foreach( $aPaginaAluno as $oAluno ) {

        /**
         * Guarda a situa��o do aluno percorrido atualmente, para valida��o de impress�o das avalia��es ou situa��o
         */
        $oDadosRelatorio->sSituacaoAlunoAtual = $oAluno->sSituacao;

        $oPdf->SetFont( 'arial', '', 7 );
        $oPdf->Cell( $oDadosRelatorio->iColunaNumero, $oDadosRelatorio->iAltura, $oAluno->iNumero, 1, 0 );
        $oPdf->Cell( $oDadosRelatorio->iColunaAluno,  $oDadosRelatorio->iAltura, $oAluno->sAluno,  1, 0 );

        /**
         * Percorre as disciplinas do aluno, para impress�o das avalia��es e resultados do mesmo
         */
        foreach( $oAluno->aDisciplinas as $iDisciplina => $oGrade ) {

          if( $iDisciplina != $oDisciplina->iDisciplina ) {
            continue;
          }

          $oDadosRelatorio->iDisciplinaAtual = $iDisciplina;

          /**
           * Percorre as avalia��es do aluno na disciplina, imprimindo o resultado de cada per�odo
           */
          foreach( $oGrade->aAvaliacoes as $oAvaliacao ) {
            imprimeAvaliacao( $oPdf, $oAvaliacao, $oDadosRelatorio );
          }

          $iColunasEmBranco = $oDadosRelatorio->iMaximoPeriodosPagina - count( $aPaginaPeriodo );

          if( $oDadosRelatorio->lTemNotaParcial ) {

            if( $oDadosRelatorio->sSituacaoAlunoAtual == 'MATRICULADO' ) {
              $iColunasEmBranco = $iColunasEmBranco - 1;
            }

            imprimeNotaParcial( $oPdf, $oGrade, $oDadosRelatorio );
          }

          /**
           * Preenche as demais colunas em branco
           */
          colunasEmBranco( $oPdf, $oDadosRelatorio, $iColunasEmBranco );

          /**
           * Imprime as informa��es do resultado final do aluno na disciplina
           */
          imprimeResultado( $oPdf, $oGrade, $oDadosRelatorio );
        }
      }

      /**
       * Caso tenha sido impresso o �ltimo aluno:
       * 1� Verifica se o m�nimo de linhas de alunos foi impresso
       */
      if( $iContadorPaginasAluno == $iTotalPaginasAluno ) {

        if( $iTotalAlunosPagina < $oDadosRelatorio->iMinimoAlunosPagina - 1 ) {

          $iLinhasEmBranco = $oDadosRelatorio->iMinimoAlunosPagina - $iContadorPaginasAluno - 2;

          for( $iContador = 0; $iContador < $iLinhasEmBranco; $iContador++ ) {
            linhaSubCabecalho( $oPdf, $aPaginaPeriodo, $oDadosRelatorio, 2 );
          }
        }

        /**
         * 2� Imprime as linhas com as aulas previstas e aulas dadas
         */
        linhaAulas( $oPdf, $aPaginaPeriodo, $oDadosRelatorio, 1 );
        linhaAulas( $oPdf, $aPaginaPeriodo, $oDadosRelatorio, 2 );

        $iContadorPaginasAluno = 0;

        /**
         * Caso existam alunos com altera��o do resultado final na disciplina atual, imprime estas por �ltimo
         */
        if( count( $oDadosRelatorio->aAprovadosConselho ) > 0 ) {

          foreach( $oDadosRelatorio->aAprovadosConselho as $iDisciplinaConselho => $aAprovadosConselho ) {

            if( $iDisciplinaConselho != $oDadosRelatorio->iDisciplinaAtual ) {
              continue;
            }

            quadroObservacoes( $oPdf, $aAprovadosConselho );
          }
        }

        /**
         * Legendas referentes as situa��es dos alunos que n�o estejam com situa��o de MATRICULADO
         */
        $sLegenda  = "MT = Matr�cula Trancada, MI = Matr�cula Indevida, IN = Matricula Indeferida, TR = Transferido Rede";
        $sLegenda .= ", TF = Transferido Fora, TM = Troca de Modalidade, TT = Troca de Turma";

        $oPdf->SetFont( 'arial', '', 7 );
        $oPdf->Cell( 280, 4, $sLegenda, 'T', 1, 'L' );
      }
    }
  }
}

/**
 * Imprime a primeira linha com os dados dos per�odos de avalia��o da turma
 * @param FpdfMultiCellBorder $oPdf
 * @param array $aPaginaPeriodo
 * @param stdClass $oDadosRelatorio
 */
function linhaPeriodos( FpdfMultiCellBorder $oPdf, $aPaginaPeriodo, $oDadosRelatorio ) {

  $sNotaParcial     = $oDadosRelatorio->lTemNotaParcial ? 'NP' : '';
  $iColunasEmBranco = $oDadosRelatorio->iMaximoPeriodosPagina - count( $aPaginaPeriodo ) - 1;

  $oPdf->Cell( $oDadosRelatorio->iColunaNumero, $oDadosRelatorio->iAltura, '', 1, 0 );
  $oPdf->Cell( $oDadosRelatorio->iColunaAluno,  $oDadosRelatorio->iAltura, '', 1, 0 );

  foreach( $aPaginaPeriodo as $oPeriodo ) {
    $oPdf->Cell( $oDadosRelatorio->iColunaPeriodo, $oDadosRelatorio->iAltura, $oPeriodo->sPeriodo, 1, 0, 'C' );
  }

  $oPdf->Cell( $oDadosRelatorio->iColunaPeriodo, $oDadosRelatorio->iAltura, $sNotaParcial, 1, 0, 'C' );

  colunasEmBranco( $oPdf, $oDadosRelatorio, $iColunasEmBranco, false );

  $oPdf->Cell( $oDadosRelatorio->iColunaResultadoFinal, $oDadosRelatorio->iAltura, '', 1, 1 );
}

/**
 * Fun��o para imprimir a segunda linha do cabe�alho, sendo utilizada tamb�m para imprimir as linhas em branco dos alunos,
 * quando n�o for atingido o limite m�nimo
 * @param FpdfMultiCellBorder $oPdf
 * @param $aPaginaPeriodo
 * @param $oDadosRelatorio
 * @param $sTipoImpressao
 *        1 - Imprime os textos padr�es do subcabe�alho
 *        2 - Imprime somente as colunas sem texto
 */
function linhaSubCabecalho( FpdfMultiCellBorder $oPdf, $aPaginaPeriodo, $oDadosRelatorio, $iTipoImpressao ) {

  $sNumero         = '';
  $sNomeAluno      = '';
  $sFormaAvaliacao = '';
  $sFalta          = '';
  $sAproveitamento = '';
  $sFrequencia     = '';
  $sResultadoFinal = '';

  if( $iTipoImpressao == 1 ) {

    $sNumero         = 'N�';
    $sNomeAluno      = 'Nome do Aluno';
    $sFalta          = 'Ft';
    $sAproveitamento = 'Aprov';
    $sFrequencia     = '% Freq';
    $sResultadoFinal = 'RF';
    $sFormaAvaliacao = 'AVAL.';
  }

  $iColunasEmBranco = $oDadosRelatorio->iMaximoPeriodosPagina - count( $aPaginaPeriodo );

  $oPdf->Cell( $oDadosRelatorio->iColunaNumero, $oDadosRelatorio->iAltura, $sNumero,    1, 0, 'C' );
  $oPdf->Cell( $oDadosRelatorio->iColunaAluno,  $oDadosRelatorio->iAltura, $sNomeAluno, 1, 0, 'C' );

  for( $iContador = 0; $iContador < count( $aPaginaPeriodo ); $iContador++ ) {

    $oPdf->Cell( $oDadosRelatorio->iColunaAvaliacao, $oDadosRelatorio->iAltura, $sFormaAvaliacao, 1, 0, 'C' );
    $oPdf->Cell( $oDadosRelatorio->iColunaFalta,     $oDadosRelatorio->iAltura, $sFalta         , 1, 0, 'C' );
  }

  colunasEmBranco( $oPdf, $oDadosRelatorio, $iColunasEmBranco );

  $oPdf->Cell( $oDadosRelatorio->iColunaResultadoFinal / 3, $oDadosRelatorio->iAltura, $sAproveitamento, 1, 0, 'C' );
  $oPdf->Cell( $oDadosRelatorio->iColunaResultadoFinal / 3, $oDadosRelatorio->iAltura, $sFrequencia,     1, 0, 'C' );
  $oPdf->Cell( $oDadosRelatorio->iColunaResultadoFinal / 3, $oDadosRelatorio->iAltura, $sResultadoFinal, 1, 1, 'C' );
}

/**
 * Imprime as linhas referentes as aulas previstas e aulas dadas em cada per�odo
 * @param FpdfMultiCellBorder $oPdf
 * @param $aPaginaPeriodo
 * @param $oDadosRelatorio
 * @param $iTipo
 *        1 - Aulas Previstas
 *        2 - Aulas Dadas
 */
function linhaAulas( FpdfMultiCellBorder $oPdf, $aPaginaPeriodo, $oDadosRelatorio, $iTipo ) {

  $iTamanhoColunaAulas = $oDadosRelatorio->iColunaNumero + $oDadosRelatorio->iColunaAluno;
  $sAulas              = $iTipo == 1 ? 'Aulas Previstas' : 'Aulas Dadas';
  $iColunasEmBranco    = $oDadosRelatorio->iMaximoPeriodosPagina - count( $aPaginaPeriodo );

  $oPdf->SetFont( 'arial', 'b', 7 );
  $oPdf->Cell( $iTamanhoColunaAulas, $oDadosRelatorio->iAltura, $sAulas, 1, 0, 'R' );

  foreach( $aPaginaPeriodo as $oPeriodo ) {

    foreach( $oPeriodo->aDisciplinas as $iDisciplina => $oDadosDisciplina ) {

      if( $oDadosRelatorio->iDisciplinaAtual != $iDisciplina ) {
        continue;
      }

      $oPdf->SetFont( 'arial', '', 7 );
      $iAulas = $iTipo == 1 ? "{$oDadosDisciplina->iAulasPrevistas}" : $oDadosDisciplina->iAulasDadas;
      $oPdf->Cell( $oDadosRelatorio->iColunaPeriodo, $oDadosRelatorio->iAltura, $iAulas, 1, 0, 'C' );
    }
  }

  colunasEmBranco( $oPdf, $oDadosRelatorio, $iColunasEmBranco, false );

  $oPdf->Cell( $oDadosRelatorio->iColunaResultadoFinal, $oDadosRelatorio->iAltura, '', 1, 1 );
}

/**
 * Imprime colunas em branco
 * @param FpdfMultiCellBorder $oPdf
 * @param $oDadosRelatorio
 * @param $iTotalLinhas     - Total de linhas que devem ter as colunas em branco
 * @param bool $lComDivisao - Controla se a coluna deve ser dividida entre avalia��o/falta
 */
function colunasEmBranco( FpdfMultiCellBorder $oPdf, $oDadosRelatorio, $iTotalLinhas, $lComDivisao = true ) {

  for( $iContador = 0; $iContador < $iTotalLinhas; $iContador++ ) {

    if( $lComDivisao ) {

      $oPdf->Cell( $oDadosRelatorio->iColunaAvaliacao, $oDadosRelatorio->iAltura, '', 1, 0 );
      $oPdf->Cell( $oDadosRelatorio->iColunaFalta,     $oDadosRelatorio->iAltura, '', 1, 0 );
    } else {
      $oPdf->Cell( $oDadosRelatorio->iColunaPeriodo, $oDadosRelatorio->iAltura, '', 1, 0 );
    }
  }
}

/**
 * Imprime as colunas com os dados das avalia��es de cada per�odo. Caso a matr�cula do aluno esteja com situa��o diferente
 * de matriculado, imprime a situa��o em cada per�odo
 * @param FpdfMultiCellBorder $oPdf
 * @param $oAvaliacao
 * @param $oDadosRelatorio
 */
function imprimeAvaliacao( FpdfMultiCellBorder $oPdf, $oAvaliacao, $oDadosRelatorio ) {

  $oPdf->SetFont( 'arial', '', 7 );

  if( $oDadosRelatorio->sSituacaoAlunoAtual == 'MATRICULADO' ) {

    if( !$oAvaliacao->lAproveitamentoMinimo && $oAvaliacao->mAproveitamento != 'Parecer' ) {
      $oPdf->SetFont( 'arial', 'b', 7 );
    }

    $mAproveitamento = $oAvaliacao->mAproveitamento;
    if ($oAvaliacao->lAvaliacaoExterna && $oAvaliacao->mAproveitamento != '') {
      $mAproveitamento = "*" . $oAvaliacao->mAproveitamento;
    }

    $oPdf->Cell( $oDadosRelatorio->iColunaAvaliacao, $oDadosRelatorio->iAltura, $mAproveitamento, 1, 0, 'C' );

    $oPdf->SetFont( 'arial', '', 7 );
    $oPdf->Cell( $oDadosRelatorio->iColunaFalta, $oDadosRelatorio->iAltura, $oAvaliacao->iFaltas, 1, 0, 'C' );
  } else {

    $sSituacao = $oDadosRelatorio->aSituacoesAluno[$oDadosRelatorio->sSituacaoAlunoAtual];
    $oPdf->Cell( $oDadosRelatorio->iColunaPeriodo, $oDadosRelatorio->iAltura, $sSituacao, 1, 0, 'C' );
  }
}

/**
 * Imprime a coluna da nota parcial, caso o par�metro para c�lculo esteja sim
 * @param FpdfMultiCellBorder $oPdf
 * @param $oGrade
 * @param $oDadosRelatorio
 */
function imprimeNotaParcial( FpdfMultiCellBorder $oPdf, $oGrade, $oDadosRelatorio ) {

  $oPdf->SetFont( 'arial', '', 7 );
  if( $oDadosRelatorio->sSituacaoAlunoAtual == 'MATRICULADO' ) {

    $oPdf->Cell( $oDadosRelatorio->iColunaAvaliacao, $oDadosRelatorio->iAltura, $oGrade->sNotaParcial, 1, 0, 'C' );
    $oPdf->Cell( $oDadosRelatorio->iColunaFalta,     $oDadosRelatorio->iAltura, '',                    1, 0, 'C' );
  }
}

/**
 * Imprime o resultado da disciplina. Caso a matr�cula do aluno esteja com situa��o diferente de matriculado, imprime
 * a situa��o nas colunas dos dados finais
 * @param FpdfMultiCellBorder $oPdf
 * @param $oGrade
 * @param $oDadosRelatorio
 */
function imprimeResultado( FpdfMultiCellBorder $oPdf, $oGrade, $oDadosRelatorio ) {

  if( $oDadosRelatorio->sSituacaoAlunoAtual == 'MATRICULADO' ) {

    $iAltura               = $oDadosRelatorio->iAltura;
    $iColunaResultadoFinal = $oDadosRelatorio->iColunaResultadoFinal;

    $oPdf->Cell( $iColunaResultadoFinal / 3, $iAltura, $oGrade->sAproveitamentoFinal, 1, 0, 'C' );
    $oPdf->Cell( $iColunaResultadoFinal / 3, $iAltura, $oGrade->sFrequencia,          1, 0, 'C' );
    $oPdf->Cell( $iColunaResultadoFinal / 3, $iAltura, $oGrade->sResultadoFinal,      1, 1, 'C' );
  } else {

    $sSituacao = $oDadosRelatorio->aSituacoesAluno[$oDadosRelatorio->sSituacaoAlunoAtual];
    $oPdf->Cell( $oDadosRelatorio->iColunaResultadoFinal, $oDadosRelatorio->iAltura, $sSituacao, 1, 1, 'C' );
  }
}

/**
 * Monta a estrutura com as informa��es padr�o para cada per�odo. Guarda as informa��es do per�odo, e as aulas previstas
 * e dadas para cada disciplina em cada per�odo
 * Estrutura separada por p�gina
 * @param Turma $oTurma
 * @param Etapa $oEtapa
 * @param $oDadosRelatorio
 * @return array
 */
function montaEstruturaImpressaoPeriodos( Turma $oTurma, Etapa $oEtapa, $oDadosRelatorio ) {

  $aEstruturaPeriodos     = array();
  $iContadorPagina        = 0;
  $iContadorPeriodos      = 0;
  $oProcedimentoAvaliacao = $oTurma->getProcedimentoDeAvaliacaoDaEtapa( $oEtapa );
  $aDisciplinas           = $oTurma->getDisciplinasPorEtapa( $oEtapa );

  /**
   * Percorre os per�odos de avalia��o da turma, guardando somente os dados de per�odos que n�o sejam um Resultado
   */
  foreach( $oProcedimentoAvaliacao->getElementos() as $oElementoAvaliacao ) {

    if( $oElementoAvaliacao instanceof ResultadoAvaliacao ) {
      continue;
    }

    $oDadosPeriodo                  = new stdClass();
    $oDadosPeriodo->sPeriodo        = $oElementoAvaliacao->getPeriodoAvaliacao()->getDescricaoAbreviada();
    $oDadosPeriodo->sFormaAvaliacao = $oElementoAvaliacao->getFormaDeAvaliacao()->getTipo();
    $oDadosPeriodo->aDisciplinas    = array();

    /**
     * Percorre as disciplinas, armazenando o total de aulas previstas e dadas em cada per�odo
     */
    foreach( $aDisciplinas as $oRegencia ) {

      $oDadosAulas                   = new stdClass();
      $oDadosAulas->iAulasPrevistas  = 0;

      foreach( $oRegencia->getTurma()->getCalendario()->getPeriodos() as $oPeriodoCalendario ) {

        if( $oPeriodoCalendario->getPeriodoAvaliacao()->getCodigo() != $oElementoAvaliacao->getPeriodoAvaliacao()->getCodigo() ) {
          continue;
        }

        $oDadosAulas->iAulasPrevistas = $oPeriodoCalendario->getSemanasLetivas() * $oRegencia->getHorasAula();
      }

      $oDadosAulas->iAulasDadas = $oRegencia->getTotalDeAulasNoPeriodo( $oElementoAvaliacao->getPeriodoAvaliacao() );

      $oDadosPeriodo->aDisciplinas[ $oRegencia->getCodigo() ] = $oDadosAulas;
    }

    /**
     * Caso o total de per�odos permitidos por p�gina tenha sido atingido, armazena os demais em uma nova p�gina
     */
    if( $iContadorPeriodos > $oDadosRelatorio->iMaximoPeriodosPagina ) {

      $iContadorPagina++;
      $iContadorPeriodos = 0;
    }

    $aEstruturaPeriodos[ $iContadorPagina ][ $oElementoAvaliacao->getPeriodoAvaliacao()->getCodigo() ] = $oDadosPeriodo;
    $iContadorPeriodos++;
  }

  return $aEstruturaPeriodos;
}

/**
 * Monta a estrutura dos alunos com as informa��es referentes ao mesmo, necess�rias para impress�o do relat�rio
 * Estrutura separada por p�gina
 * @param Turma $oTurma
 * @param Etapa $oEtapa
 * @param $oDadosRelatorio
 * @return array
 */
function montaEstruturaImpressaoAlunos( Turma $oTurma, Etapa $oEtapa, $oDadosRelatorio ) {

  $aEstruturaAlunos       = array();
  $iMaximoAlunosPorPagina = 38;
  $iContadorPagina        = 0;
  $iContadorAlunos        = 0;
  $iAnoCalendario         = $oTurma->getCalendario()->getAnoExecucao();
  $iEnsino                = $oTurma->getBaseCurricular()->getCurso()->getEnsino()->getCodigo();

  /**
   * Percorre os alunos matriculados na turma e s�rie selecionados
   */
  foreach( $oTurma->getAlunosMatriculadosNaTurmaPorSerie( $oEtapa ) as $oMatricula ) {

    /**
     * Pula alunos que tenham situa��o de TROCA DE TURMA, caso tenha sido selecionado para n�o exibir os mesmos
     */
    if( !$oDadosRelatorio->lExibeTrocaTurma && $oMatricula->getSituacao() == 'TROCA DE TURMA' ) {
      continue;
    }

    /**
     * Objeto com os dados do aluno
     */
    $oDadosAluno               = new stdClass();
    $oDadosAluno->iAluno       = $oMatricula->getAluno()->getCodigoAluno();
    $oDadosAluno->iMatricula   = $oMatricula->getCodigo();
    $oDadosAluno->sAluno       = $oMatricula->getAluno()->getNome();
    $oDadosAluno->iNumero      = $oMatricula->getNumeroOrdemAluno();
    $oDadosAluno->sSituacao    = $oMatricula->getSituacao();
    $oDadosAluno->aDisciplinas = array();

    db_inicio_transacao();

    $oDiario = $oMatricula->getDiarioDeClasse();

    /**
     * Percorre as avalia��es de cada disciplina do di�rio do aluno para buscar os aproveitamentos
     */
    foreach( $oDiario->getDisciplinas() as $oDiarioAvaliacaoDisciplina ) {

      $oDadosDisciplina                       = new stdClass();
      $oDadosDisciplina->sAproveitamentoFinal = '';
      $oDadosDisciplina->sFrequencia          = '';
      $oDadosDisciplina->sResultadoFinal      = '';
      $oDadosDisciplina->sNotaParcial         = '';
      $oDadosDisciplina->aAvaliacoes          = array();

      /**
       * Percorre as avalia��es configuradas no procedimento da turma
       */
      foreach( $oDiarioAvaliacaoDisciplina->getAvaliacoes() as $oElementosAvaliacao ) {

        /**
         * Caso o elemento atual seja uma inst�ncia de ResultadoAvaliacao:
         * 1� Armazena o aproveitamento final, alterando o valor impresso quando forma de avalia��o for PARECER, aluno
         * amparado na disciplina por Conven��o( SUP ) ou aluno amparado na disciplina em todos os per�odos( Amp )
         * Para os casos de amparo, a frequ�ncia fica vazia
         */
        if( $oElementosAvaliacao->getElementoAvaliacao() instanceof ResultadoAvaliacao ) {

          $oElementoResultadoFinal                = $oDiarioAvaliacaoDisciplina->getResultadoFinal();
          $oDadosDisciplina->sAproveitamentoFinal = $oElementoResultadoFinal->getValorAprovacao();

          if( $oElementosAvaliacao->getElementoAvaliacao()->getFormaDeAvaliacao()->getTipo() == 'PARECER' ) {
            $oDadosDisciplina->sAproveitamentoFinal = 'Parec';
          }

          $oDadosDisciplina->sFrequencia = $oElementoResultadoFinal->getPercentualFrequencia();

          if(    $oDiarioAvaliacaoDisciplina->reclassificadoPorBaixaFrequencia()
              || ( $oDiario->reclassificadoPorBaixaFrequencia() && $oDiario->getProcedimentoDeAvaliacao()->getFormaCalculoFrequencia() == 2 ) ) {
            $oDadosDisciplina->sFrequencia = '--';
          }

          if( $oDiarioAvaliacaoDisciplina->getAmparo() instanceof AmparoDisciplina ) {

            if( $oDiarioAvaliacaoDisciplina->getAmparo()->getTipoAmparo() == AmparoDisciplina::AMPARO_CONVENCAO ) {

              $oDadosDisciplina->sAproveitamentoFinal = 'SUP';
              $oDadosDisciplina->sFrequencia          = '';
            }

            if(    $oDiarioAvaliacaoDisciplina->getAmparo()->getTipoAmparo() == AmparoDisciplina::AMPARO_JUSTIFICATIVA
              && $oDiarioAvaliacaoDisciplina->getAmparo()->isTotal()
            ) {

              $oDadosDisciplina->sAproveitamentoFinal = 'Amp';
              $oDadosDisciplina->sFrequencia          = '';
            }
          }

          /**
           * Guarda o resultado final do aluno, e em seguida busca o termo referente ao ensino e ano do calend�rio da
           * turma, caso o aluno j� tenha resultado na disciplina
           */
          $oDadosDisciplina->sResultadoFinal = $oElementoResultadoFinal->getResultadoFinal();

          if( $oDadosDisciplina->sResultadoFinal != '' ) {

            $aTermosEnsino = DBEducacaoTermo::getTermoEncerramento(
                                                                    $iEnsino,
                                                                    $oDadosDisciplina->sResultadoFinal,
                                                                    $iAnoCalendario
                                                                  );
            $oDadosDisciplina->sResultadoFinal = $aTermosEnsino[0]->sAbreviatura;
          }


          /**
           * Caso o resultado final do aluno tenha sido alterado, busca as informa��es referentes a esta altera��o,
           * armazenando em um array indexado pelo c�digo da Reg�ncia, com os alunos alterados na disciplina atual
           */
          if( $oElementoResultadoFinal->getFormaAprovacaoConselho() instanceof AprovacaoConselho ) {

            $oAprovacaoConselho = $oElementoResultadoFinal->getFormaAprovacaoConselho();
            $iRegencia          = $oDiarioAvaliacaoDisciplina->getRegencia()->getCodigo();

            switch ( $oAprovacaoConselho->getFormaAprovacao() ) {

              /**
               * Valida se a aprova��o foi por conselho
               */
              case 1:

                $oDocumento                = new libdocumento( 5013 );
                $oDocumento->disciplina    = $oDiarioAvaliacaoDisciplina->getDisciplina()->getNomeDisciplina();
                $oDocumento->etapa         = $oEtapa->getNome();
                $oDocumento->justificativa = $oAprovacaoConselho->getJustificativa();
                $oDocumento->nota          = $oAprovacaoConselho->getAvaliacaoConselho();
                $oDocumento->anomatricula  = $iAnoCalendario;

                $oDadosObservacao              = new stdClass();
                $oDadosObservacao->aParagrafos = $oDocumento->getDocParagrafos();

                if( trim( $oDadosObservacao->aParagrafos[1]->oParag->db02_texto ) != '' ) {

                  $sObservacao  = "- {$oMatricula->getAluno()->getNome()}: ";
                  $sObservacao .= $oDadosObservacao->aParagrafos[1]->oParag->db02_texto;

                  $oDadosRelatorio->aAprovadosConselho[$iRegencia][] = $sObservacao;
                }
                break;

              /**
               * Valida se a aprova��o n�o foi por baixa frequencia
               */
              case 2:

                $oDocumento             = new libdocumento( 5006 );
                $oDocumento->nome_aluno = $oMatricula->getAluno()->getNome();
                $oDocumento->ano        = $iAnoCalendario;
                $oDocumento->nome_etapa = $oEtapa->getNome();

                $aParagrafos                                       = $oDocumento->getDocParagrafos();
                $oDadosRelatorio->aAprovadosConselho[$iRegencia][] = "- {$aParagrafos[1]->oParag->db02_texto}";

                break;

              /**
               * Valida se a aprova��o foi por regimento escolar
               */
              case 3:

                $sObservacao  = "- {$oMatricula->getAluno()->getNome()}: ";
                $sObservacao .= "Disciplina {$oDiarioAvaliacaoDisciplina->getDisciplina()->getNomeDisciplina()} na etapa";
                $sObservacao .= " {$oEtapa->getNome()} foi aprovado pelo regimento escolar. ";
                $sObservacao .= "Justificativa: {$oAprovacaoConselho->getJustificativa()}";

                $oDadosRelatorio->aAprovadosConselho[$iRegencia][] = $sObservacao;

                break;
            }

            if( $oAprovacaoConselho->getFormaAprovacao() == 1 && $oAprovacaoConselho->getAlterarNotaFinal() == 2 ) {
              $oDadosDisciplina->sAproveitamentoFinal = $oAprovacaoConselho->getAvaliacaoConselho();
            }
          }

          continue;
        }

        /**
         * Sendo o elemento uma inst�ncia de AvaliacaoPeriodica, guarda o aproveitamento( j� convertido para regra de
         * arredondamento configurada para o ano ) e as faltas no per�odo.
         * Para os casos de amparo e parecer, o valor � alterado para:
         * - Amparo: SUP( Conven��o ); Amparado;
         * - PARECER: Parecer
         */
        $oDadosAvaliacao                  = new stdClass();
        $oDadosAvaliacao->iPeriodo        = $oElementosAvaliacao->getElementoAvaliacao()->getPeriodoAvaliacao()->getCodigo();
        $oDadosAvaliacao->mAproveitamento = $oElementosAvaliacao->getValorAproveitamento()->getAproveitamento();
        $oDadosAvaliacao->mAproveitamento = ArredondamentoNota::formatar( $oDadosAvaliacao->mAproveitamento, $iAnoCalendario );

        if( $oElementosAvaliacao->isAmparado() ) {

          $oDadosAvaliacao->mAproveitamento = 'Amparado';
          if( $oDiarioAvaliacaoDisciplina->getAmparo()->getTipoAmparo() == 1 ) {
            $oDadosAvaliacao->mAproveitamento = 'SUP';
          }
        }

        if(    $oElementosAvaliacao->getElementoAvaliacao()->getFormaDeAvaliacao()->getTipo() == 'PARECER'
            || $oMatricula->isAvaliadoPorParecer()
        ) {
          $oDadosAvaliacao->mAproveitamento = 'Parecer';
        }

        $oDadosAvaliacao->lAproveitamentoMinimo = $oElementosAvaliacao->temAproveitamentoMinimo();
        $oDadosAvaliacao->lAvaliacaoExterna     = $oElementosAvaliacao->isAvaliacaoExterna();
        $oDadosAvaliacao->iFaltas               = $oElementosAvaliacao->getNumeroFaltas();

        if( empty( $oDadosAvaliacao->iFaltas ) ) {
          $oDadosAvaliacao->iFaltas = '';
        }

        if( $oDiarioAvaliacaoDisciplina->getRegencia()->getFrequenciaGlobal() == 'A' ) {
          $oDadosAvaliacao->iFaltas = '-';
        }

        if( $oDadosRelatorio->lTemNotaParcial ) {
          $oDadosDisciplina->sNotaParcial = $oDiarioAvaliacaoDisciplina->getNotaParcial( $oElementosAvaliacao->getElementoAvaliacao() );
        }

        $oDadosDisciplina->aAvaliacoes[] = $oDadosAvaliacao;
        $oDadosAluno->aDisciplinas[$oDiarioAvaliacaoDisciplina->getRegencia()->getCodigo()] = $oDadosDisciplina;
      }
    }

    db_fim_transacao();

    /**
     * Controla a quebra de p�gina por aluno, caso atinja o limite permitido
     */
    if( $iContadorAlunos > $iMaximoAlunosPorPagina - 1 ) {

      $iContadorPagina++;
      $iContadorAlunos = 0;
    }

    $aEstruturaAlunos[ $iContadorPagina ][ $iContadorAlunos ] = $oDadosAluno;
    $iContadorAlunos++;
  }

  return $aEstruturaAlunos;
}

/**
 * Monta a estrutura final do relat�rio para impress�o dos dados
 * @param Turma $oTurma
 * @param Etapa $oEtapa
 * @param $oDadosRelatorio
 * @return array
 */
function montaEstruturaGeral( Turma $oTurma, Etapa $oEtapa, $oDadosRelatorio ) {

  $aEstruturaGeral    = array();
  $aDisciplinasTurma  = $oTurma->getDisciplinasPorEtapa( $oEtapa );
  $aEstruturaPeriodos = montaEstruturaImpressaoPeriodos( $oTurma, $oEtapa, $oDadosRelatorio );
  $aEstruturaAlunos   = montaEstruturaImpressaoAlunos( $oTurma, $oEtapa, $oDadosRelatorio );

  foreach( $aDisciplinasTurma as $oRegencia ) {

    if( !in_array( $oRegencia->getCodigo(), $oDadosRelatorio->aDisciplinasImpressao ) ) {
      continue;
    }

    $oDadosDisciplina              = new stdClass();
    $oDadosDisciplina->iDisciplina = $oRegencia->getCodigo();
    $oDadosDisciplina->sDisciplina = $oRegencia->getDisciplina()->getNomeDisciplina();
    $oDadosDisciplina->sRegente    = "";

    foreach( $oRegencia->getDocentes() as $oDocente ) {
      $oDadosDisciplina->sRegente = $oDocente->getNome();
    }

    $oDadosDisciplina->aPeriodos = $aEstruturaPeriodos;
    $oDadosDisciplina->aAlunos   = $aEstruturaAlunos;
    $aEstruturaGeral[]           = $oDadosDisciplina;
  }

  return $aEstruturaGeral;
}

/**
 * Imprime o quadro com as observa��es de alunos que tiveram resultado final alterado na disciplina impressa
 * @param FpdfMultiCellBorder $oPdf
 * @param $aAprovadosConselho
 */
function quadroObservacoes( FpdfMultiCellBorder $oPdf, $aAprovadosConselho ) {

  $sObservacoes = '';

  foreach( $aAprovadosConselho as $sAprovadoConselho ) {
    $sObservacoes .= $sAprovadoConselho . "\n";
  }

  $oPdf->SetAutoPageBreak( true );
  $oPdf->SetFont( 'arial', 'b', 7 );
  $oPdf->MultiCell( 280, 4, $sObservacoes, 1, 'L' );
}

$oPdf->Output();
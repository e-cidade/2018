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

require_once ("fpdf151/pdfwebseller.php");
require_once ("libs/db_sql.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/JSON.php");
require_once ("dbforms/db_funcoes.php");

$oGet                  = db_utils::postMemory( $_GET );
$oTurma                = TurmaRepository::getTurmaByCodigo( $oGet->iTurma );
$oPeriodoAvaliacao     = new PeriodoAvaliacao( $oGet->iPeriodo );
$oDaoCriterioAvaliacao = new cl_criterioavaliacao();

/**
 * Objeto com valores padrões
 */
$oFiltros                         = new stdClass();
$oFiltros->iLimiteCriteriosPagina = 10;
$oFiltros->aCriterios             = array();
$oFiltros->iAlturaPadrao          = 4;
$oFiltros->iAlunosPagina          = 39;
$oFiltros->iAlturaVertical        = 35;
$oFiltros->aDisciplinas           = explode( ",", $oGet->aDisciplinas );
$oFiltros->iPosicaoInicialX       = 10;
$oFiltros->iLinhasAlunoEmBranco   = 0;

/**
 * Variáveis para controle do array de limite de alunos por página
 */
$iPaginaAluno                 = 0;
$iContadorAuxAluno            = 0;
$oFiltros->aAlunosOrganizados = array();
$oFiltros->iTotalAlunos       = 0;

/**
 * Percorre os alunos matriculados na turma, e monta um array de stdClass com os dados necessários para impressão,
 * validando os alunos a serem impressos por página
 */
foreach( $oTurma->getAlunosMatriculados() as $oMatricula ) {

  $oDadosAlunos               = new stdClass();
  $oDadosAlunos->iNumeroAluno = $oMatricula->getNumeroOrdemAluno();
  $oDadosAlunos->sNome        = $oMatricula->getAluno()->getNome();
  $oDadosAlunos->iCodigoAluno = $oMatricula->getAluno()->getCodigoAluno();

  $oFiltros->aAlunosOrganizados[$iPaginaAluno][] = $oDadosAlunos;
  if ( $iContadorAuxAluno >= $oFiltros->iAlunosPagina - 1 ) {

    $iPaginaAluno++;
    $iContadorAuxAluno = 0;
  }

  $iContadorAuxAluno++;
  $oFiltros->iTotalAlunos++;
}

/**
 * Caso não existam alunos matriculados na turma, monta o array com dados em branco para impressão das linhas
 */
if ( count( $oTurma->getAlunosMatriculados() ) == 0 ) {

  for ( $iContador = 0; $iContador < $oFiltros->iAlunosPagina; $iContador++ ) {

    $oDadosAlunos               = new stdClass();
    $oDadosAlunos->iNumeroAluno = "";
    $oDadosAlunos->sNome        = "";
    $oDadosAlunos->iCodigoAluno = "";

    $oFiltros->aAlunosOrganizados[0][] = $oDadosAlunos;
  }
}

/**
 * Cabeçalho do relatório
 */
$head1 = "FICHA QUALITATIVA DE AVALIAÇÃO";
$head2 = "Calendário: {$oTurma->getCalendario()->getDescricao()}";
$head3 = "Turma: {$oTurma->getDescricao()}";

$head5      = "Período de Avaliação: {$oPeriodoAvaliacao->getDescricao()}";
$sProfessor = $oTurma->getProfessorConselheiro() != "" ? $oTurma->getProfessorConselheiro()->getNome() : "";
$head6      = "Professor: {$sProfessor}";

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak( true );
$oPdf->SetFillColor( 225 );

/**
 * Percorre o total de disciplinas selecionadas, sendo impressa ao menos uma página para cada
 */
for( $iContador = 0; $iContador < count( $oFiltros->aDisciplinas ); $iContador++ ) {

  $oDisciplina          = DisciplinaRepository::getDisciplinaByCodigo( $oFiltros->aDisciplinas[$iContador] );
  $head4                = "Disciplina: {$oDisciplina->getNomeDisciplina()}";
  $oFiltros->aCriterios = CriterioAvaliacaoRepository::getCriteriosPorVinculos( $oDisciplina, $oTurma, $oPeriodoAvaliacao );

  /**
   * Variaveis para controle do array do limite de critérios
   */
  $iPagina                         = 0;
  $iContadorAux                    = 0;
  $oFiltros->aCriteriosOrganizados = array();
  $oFiltros->iTotalCriterios       = 0;

  /**
   * Organiza o array com o limite de critérios por pa?ina
   */
  foreach( $oFiltros->aCriterios as $oCriterio ) {

    $oFiltros->aCriteriosOrganizados[$iPagina][] = $oCriterio;

    if ( $iContadorAux >= $oFiltros->iLimiteCriteriosPagina - 1 ) {

      $iPagina++;
      $iContadorAux = 0;
    }

    $iContadorAux++;
    $oFiltros->iTotalCriterios++;
  }

  /**
   * Percorre o array dos critérios para impressão por página de acordo com o organizado
   */
  foreach( $oFiltros->aCriteriosOrganizados as $iPagina => $aCriterio ) {

    $oFiltros->lUltimaPaginaCriterio = count($oFiltros->aCriteriosOrganizados) -1 == $iPagina;
    $oFiltros->aCriteriosAvaliacao   = $aCriterio;
    colunasCabecalho( $oPdf, $oFiltros, $oTurma);
  }
}

/**
 * Imprime as colunas do cabeçalho e realiza a chamada para o corpo do relatório
 * @param PDF      $oPdf
 * @param stdClass $oFiltros
 */
function colunasCabecalho( PDF $oPdf, $oFiltros, Turma $oTurma ) {

  $iNPaginasAluno = count($oFiltros->aAlunosOrganizados);

  foreach( $oFiltros->aAlunosOrganizados as $iPagina => $oFiltros->aAlunos ) {

    $oFiltros->lUltimaPaginaAluno = count($oFiltros->aAlunosOrganizados) -1 == $iPagina;

    $oPdf->AddPage();
    $oPdf->SetFont( 'arial', 'b', 7 );
    $oPdf->Rect( $oFiltros->iPosicaoInicialX, $oFiltros->iAlturaVertical, 192, 195 );

    $oPdf->Cell( 5,   $oFiltros->iAlturaVertical + 4, "Nº",                      1, 0, "C" );
    $oPdf->Cell( 97,  $oFiltros->iAlturaVertical + 4, "Nome do Aluno",           1, 0, "C" );
    $oPdf->Cell( 10,  $oFiltros->iAlturaVertical + 4, "Código",                  1, 0, "C" );
    $oPdf->Cell( 80,                               4, "Itens a serem avaliados", 1, 0, "C" );

    $oPdf->SetXY( 122, 39 );

    /**
     * Percorre o array dos critérios, imprimindo os mesmos na vertical utilizando o VCell
     */
    $oPdf->SetFont( 'arial', '', 6 );
    foreach( $oFiltros->aCriteriosAvaliacao as $oCriterio ) {
      $oPdf->VCell( 8, $oFiltros->iAlturaVertical, $oCriterio->getAbreviatura(), 1, 0, "C" );
    }

    /**
     * Caso o número de critérios para a página seja menor que o permitido, verifica a diferença e imprime as demais
     * colunas em branco
     */
    if ( count( $oFiltros->aCriteriosAvaliacao ) < $oFiltros->iLimiteCriteriosPagina ) {

      $iColunasImpressaoBranco = $oFiltros->iLimiteCriteriosPagina - count( $oFiltros->aCriteriosAvaliacao );

      for( $iContador = 0; $iContador < $iColunasImpressaoBranco; $iContador++ ) {
        $oPdf->VCell( 8, $oFiltros->iAlturaVertical, "", 1, 0, "C" );
      }
    }

    corpoRelatorio( $oPdf, $oFiltros, $oTurma );
  }
}

/**
 * Imprime os alunos da turma e realiza a chamada para o rodapé
 * @param PDF      $oPdf
 * @param stdClass $oFiltros
 */
function corpoRelatorio( PDF $oPdf, $oFiltros, Turma $oTurma ) {

  $oPdf->SetXY( $oFiltros->iPosicaoInicialX, $oFiltros->iAlturaVertical + 39 );
  $oPdf->SetFont( 'arial', '', 7 );

  if ( count( $oFiltros->aAlunos ) == 0 ) {

    $oFiltros->iLinhasAlunoEmBranco = $oFiltros->iAlunosPagina;
    imprimeLinhasEmBranco( $oPdf, $oFiltros );
  }

  $iControlePreenchimento = 1;

  foreach ( $oFiltros->aAlunos as $oAluno ) {

    $oFiltros->iPreenchimento = 1;
    if ( $iControlePreenchimento % 2 == 0 ) {
      $oFiltros->iPreenchimento = 0;
    }

    imprimeAlunos( $oPdf, $oFiltros, $oAluno );
    $iControlePreenchimento++;
  }

  /**
   * Caso todos os alunos tenham sido impressos, preenche as demais linhas em branco até o limite da página
   */
  if ( count( $oFiltros->aAlunos ) < $oFiltros->iAlunosPagina ) {

    $oFiltros->iLinhasAlunoEmBranco = $oFiltros->iAlunosPagina - count( $oFiltros->aAlunos );
    imprimeLinhasEmBranco( $oPdf, $oFiltros );
  }

  rodapeRelatorio( $oPdf, $oFiltros );
}

/**
 * Imprime a linha com as informações do aluno
 * @param PDF      $oPdf
 * @param stdClass $oFiltros
 * @param stdClass $oAluno
 */
function imprimeAlunos( PDF $oPdf, $oFiltros, $oAluno ) {

  $oPdf->Cell(  5, $oFiltros->iAlturaPadrao, $oAluno->iNumeroAluno, 1, 0, "C", $oFiltros->iPreenchimento );
  $oPdf->Cell( 97, $oFiltros->iAlturaPadrao, $oAluno->sNome,        1, 0, "L", $oFiltros->iPreenchimento );
  $oPdf->Cell( 10, $oFiltros->iAlturaPadrao, $oAluno->iCodigoAluno, 1, 0, "C", $oFiltros->iPreenchimento );

  for( $iContador = 0; $iContador < 10; $iContador++ ) {
    $oPdf->Cell( 8, $oFiltros->iAlturaPadrao, "", 1, 0, "C", $oFiltros->iPreenchimento );
  }

  $oPdf->Ln();
}

/**
 * Preenche linhas em branco quando não preenche todos os alunos
 * @param PDF      $oPdf
 * @param stdClass $oFiltros
 */
function imprimeLinhasEmBranco( PDF $oPdf, $oFiltros ) {

  for( $iContadorAlunos = 0; $iContadorAlunos < $oFiltros->iLinhasAlunoEmBranco; $iContadorAlunos++ ) {

    $oFiltros->iPreenchimento  = 0;
    $oDadosAluno               = new stdClass();
    $oDadosAluno->iNumeroAluno = "";
    $oDadosAluno->sNome        = "";
    $oDadosAluno->iCodigoAluno = "";
    imprimeAlunos( $oPdf, $oFiltros, $oDadosAluno );
  }
}

/**
 * Rodapé que contém os critérios de avaliação da turma
 * @param PDF      $oPdf
 * @param stdClass $oFiltros
 */
function rodapeRelatorio( PDF $oPdf, $oFiltros ) {

  $oPdf->SetXY( $oFiltros->iPosicaoInicialX, 230 );
  $oPdf->Rect( $oFiltros->iPosicaoInicialX, 234, 192, 50 );
  $oPdf->SetFont( 'arial', 'b', 7 );
  $oPdf->Cell( 192, $oFiltros->iAlturaPadrao, "Critérios de Avaliação", 1, 1, "C" );
  $oPdf->Ln( 1 );

  $iPosicaoX                = $oFiltros->iPosicaoInicialX;
  $iLarguraColuna           = 90;
  $iMaximoLinhas            = 32;
  $iContadorLinhasCriterios = 0;

  /**
   * Controla o total de linhas impressas por todos os critérios
   */
  for( $iContador = 0; $iContador < count( $oFiltros->aCriterios ); $iContador++ ) {

    $sCriterio  = $oFiltros->aCriterios[$iContador]->getAbreviatura() . " - ";
    $sCriterio .= $oFiltros->aCriterios[$iContador]->getDescricao();

    $iContadorLinhasCriterios += $oPdf->NbLines( $iLarguraColuna, $sCriterio);
  }

  /**
   * Caso o número de linhas seja maior que o permitido e esteja sendo impressa a última página de critérios e alunos,
   * a abreviatura e descrição dos critérios será impressa em uma página isolada
   */
  if ( $iContadorLinhasCriterios > $iMaximoLinhas && $oFiltros->lUltimaPaginaCriterio && $oFiltros->lUltimaPaginaAluno ) {

    $oPdf->AddPage();
    $oPdf->SetFont( 'arial', 'B', 8 );
    $oPdf->Cell(190, $oFiltros->iAlturaPadrao, "Critérios de Avaliação", 0, 1, "C"  );
    $oPdf->SetFont( 'arial', '', 8 );
    for( $iContador = 0; $iContador < count( $oFiltros->aCriterios ); $iContador++ ) {

      $sCriterio  = $oFiltros->aCriterios[$iContador]->getAbreviatura();
      $sCriterio .= " - "  . $oFiltros->aCriterios[$iContador]->getDescricao();
      $oPdf->MultiCell( 190, 4, $sCriterio, 0, 'L' );
      $oPdf->Ln(1);
    }

    /**
     * Sendo número de linhas menor que o máximo permitido, imprime os critérios normalmente no rodapé, controlando
     * a impressão por coluna
     */
  } else if ( $iContadorLinhasCriterios <= $iMaximoLinhas ) {

    $iContadorLinhasCriterios = 0;
    $lPassouNoLaco            = false;

    for( $iContador = 0; $iContador < count( $oFiltros->aCriterios ); $iContador++ ) {

      $sCriterio                 = $oFiltros->aCriterios[$iContador]->getAbreviatura() . " - " . $oFiltros->aCriterios[$iContador]->getDescricao();
      $iContadorLinhasCriterios += $oPdf->NbLines( $iLarguraColuna, $sCriterio );

      $iPosicaoY = $oPdf->getY();
      if ( $iContadorLinhasCriterios > 16 && !$lPassouNoLaco ) {

        $lPassouNoLaco = true;
        $iPosicaoX     = 106;
        $iPosicaoY     = 235;
      }

      $oPdf->SetXY( $iPosicaoX, $iPosicaoY );
      $oPdf->SetFont( 'arial', '', 6 );
      $oPdf->MultiCell( 90, 3, $sCriterio, 0 );
    }
  }
}

$oPdf->Output();
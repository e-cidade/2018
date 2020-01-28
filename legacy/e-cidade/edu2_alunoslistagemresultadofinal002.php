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

require_once ("fpdf151/pdf.php");
require_once ("libs/db_sql.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/JSON.php");
require_once ("std/DBDate.php");
require_once ("dbforms/db_funcoes.php");

$oParam = db_utils::postMemory($_GET);

/**
 * stdClass para utilizacao de propriedades no relatorio
 */
$oFiltros = new stdClass();

/**
 * Armazenamos os valores recebidos via GET
 */
$oFiltros->iEscola = $oParam->iEscola;
$oFiltros->iAno    = $oParam->iAno;
$oFiltros->iEnsino = $oParam->iEnsino;
$oFiltros->sEtapas = $oParam->sEtapas;

/**
 * Array para controle das quebras
 */
$oFiltros->aQuebras = array();

/**
 * Array com os cabecalhos selecionados para serem impressos
 */
$oFiltros->aCabecalho = array();

/**
 * stdClass referente ao CODIGO DO ALUNO
 * sDescricao: Descricao impressa no cabecalho
 * iTamanho: Tamanho da coluna
 * iTipo: Tipo para controle dos dados a serem impressos
 */
$oFiltros->oCodigoAluno             = new stdClass();
$oFiltros->oCodigoAluno->sDescricao = 'Código';
$oFiltros->oCodigoAluno->iTamanho   = 12;
$oFiltros->oCodigoAluno->iTipo      = 1;
$oFiltros->aCabecalho[]             = $oFiltros->oCodigoAluno;

/**
 * stdClass referente ao NOME DO ALUNO
 * sDescricao: Descricao impressa no cabecalho
 * iTamanho: Tamanho da coluna
 * iTipo: Tipo para controle dos dados a serem impressos
 */
$oFiltros->oNomeAluno             = new stdClass();
$oFiltros->oNomeAluno->sDescricao = 'Nome do Aluno';
$oFiltros->oNomeAluno->iTamanho   = 180;
$oFiltros->oNomeAluno->iTipo      = 2;
$oFiltros->aCabecalho[]           = $oFiltros->oNomeAluno;

/**
 * stdClass referente ao CURSO DO ALUNO
 * sDescricao: Descricao impressa no cabecalho
 * iTamanho: Tamanho da coluna
 * iTipo: Tipo para controle dos dados a serem impressos
 */
$oFiltros->oCurso                = new stdClass();
$oFiltros->oCurso->sDescricao    = 'Ensino';
$oFiltros->oCurso->iTamanho      = 40;
$oFiltros->oCurso->iTipo         = 3;
$oFiltros->oNomeAluno->iTamanho -= $oFiltros->oCurso->iTamanho;
$oFiltros->aCabecalho[]          = $oFiltros->oCurso;

/**
 * stdClass referente a ETAPA QUE SE ENCONTRA O ALUNO
 * sDescricao: Descricao impressa no cabecalho
 * iTamanho: Tamanho da coluna
 * iTipo: Tipo para controle dos dados a serem impressos
 */
$oFiltros->oEtapa                = new stdClass();
$oFiltros->oEtapa->sDescricao    = 'Etapa';
$oFiltros->oEtapa->iTamanho      = 25;
$oFiltros->oEtapa->iTipo         = 4;
$oFiltros->oNomeAluno->iTamanho -= $oFiltros->oEtapa->iTamanho;
$oFiltros->aCabecalho[]          = $oFiltros->oEtapa;

/**
 * stdClass referente ao RESULTADO FINAL DO ALUNO
 * sDescricao: Descricao impressa no cabecalho
 * iTamanho: Tamanho da coluna
 * iTipo: Tipo para controle dos dados a serem impressos
 */
$oFiltros->oResultadoFinal              = new stdClass();
$oFiltros->oResultadoFinal->sDescricao  = 'Resultado Final';
$oFiltros->oResultadoFinal->iTamanho    = 32;
$oFiltros->oResultadoFinal->iTipo       = 5;
$oFiltros->oNomeAluno->iTamanho        -= $oFiltros->oResultadoFinal->iTamanho;
$oFiltros->aCabecalho[]                 = $oFiltros->oResultadoFinal;

/**
 * Array com os resultados finais de todas as escolas
 */
$oFiltros->aSituacoesGeral = array();

/**
 * Propriedades para controle dos totalizadores dos resultados finais de todas as escolas
 */
$oFiltros->iAprovadosGeral          = 0;
$oFiltros->iReprovadosGeral         = 0;
$oFiltros->iParcialAprovadosGeral   = 0;
$oFiltros->iTotalAlunosGeral        = 0;

/**
 * Altura das colunas
 */
$oFiltros->iAlturaColuna = 4;

/**
 * Array com as condicoes para SQL
 */
$aWhereEscola = array();

/**
 * Array com as escolas para imprimir o nome no cabecalho
 */
$aEscolas = array();

/**
 * Condicoes do SQL
 */
$sWhere  = " where not exists (select 1 from diariofinal df1                          ";
$sWhere .= "                           where df1.ed74_c_resultadofinal = ''           ";
$sWhere .= "                             and df1.ed74_i_diario = diario.ed95_i_codigo)";
$sWhere .= " and ed60_c_situacao in ('MATRICULADO')";

if ($oFiltros->iAno != 0) {
  $aWhereEscola[] = "ed52_i_ano = {$oFiltros->iAno}";
}

if (isset($oParam->iEscola) && $oParam->iEscola != 0) {
  $aWhereEscola[] = "ed18_i_codigo = {$oParam->iEscola}";
}

if (isset($oParam->iEnsino) && $oParam->iEnsino != 0) {
  $aWhereEscola[] = "ed10_i_codigo = {$oParam->iEnsino}";
}

if (isset($oParam->sEtapas) && $oParam->sEtapas != '') {
  $aWhereEscola[] = "ed11_i_codigo in ({$oFiltros->sEtapas})";
}

if (count($aWhereEscola) > 0) {
  $sWhereEscola = " and ".implode(" and ", $aWhereEscola);
}

/**
 * SQL para buscar as escolas, ensinos, etapas, alunos, diarios e resultados finais. Monta um array da seguinte forma
 * $oFiltros->aQuebras[codigo da escola]
 *                    ..[ensinos vinculados a escola]
 *                    ....[codigo das turmas vinculadas ao ensino]
 *                    ......[codigo da etapa da turma]
 *                    ........[matricula_aluno]
 *                    ..........[diario] - resultado final
 * Monta o array das escolas
 */
$sSqlAlunos  = "select ed18_i_codigo         as codigo_escola,                                                                          ";
$sSqlAlunos .= "       ed18_c_nome           as nome_escola,                                                                            ";
$sSqlAlunos .= "       ed10_i_codigo         as ensino,                                                                                 ";
$sSqlAlunos .= "       ed11_i_codigo         as codigo_etapa,                                                                           ";
$sSqlAlunos .= "       ed11_c_descr          as descricao_etapa,                                                                        ";
$sSqlAlunos .= "       ed57_i_codigo         as codigo_turma,                                                                           ";
$sSqlAlunos .= "       ed60_i_codigo         as matricula_aluno,                                                                        ";
$sSqlAlunos .= "       ed95_i_codigo         as diario,                                                                                 ";
$sSqlAlunos .= "       ed74_c_resultadofinal as resultado_final                                                                         ";
$sSqlAlunos .= "  from turma                                                                                                            ";
$sSqlAlunos .= "       inner join escola              on escola.ed18_i_codigo              = turma.ed57_i_escola                        ";
$sSqlAlunos .= "       inner join calendario          on calendario.ed52_i_codigo          = turma.ed57_i_calendario                    ";
$sSqlAlunos .= "       inner join base                on base.ed31_i_codigo                = turma.ed57_i_base                          ";
$sSqlAlunos .= "       inner join cursoedu            on cursoedu.ed29_i_codigo            = base.ed31_i_curso                          ";
$sSqlAlunos .= "       inner join ensino              on ensino.ed10_i_codigo              = cursoedu.ed29_i_ensino                     ";
$sSqlAlunos .= "       inner join turmaserieregimemat on turmaserieregimemat.ed220_i_turma = turma.ed57_i_codigo                        ";
$sSqlAlunos .= "       inner join serieregimemat      on serieregimemat.ed223_i_codigo     = turmaserieregimemat.ed220_i_serieregimemat ";
$sSqlAlunos .= "       inner join serie               on serie.ed11_i_codigo               = serieregimemat.ed223_i_serie               ";
$sSqlAlunos .= "       inner join matricula           on matricula.ed60_i_turma            = turma.ed57_i_codigo                        ";
$sSqlAlunos .= "       inner join aluno               on aluno.ed47_i_codigo               = matricula.ed60_i_aluno                     ";
$sSqlAlunos .= "       inner join regencia            on  regencia.ed59_i_turma            = turma.ed57_i_codigo                        ";
$sSqlAlunos .= "       inner join diario              on  diario.ed95_i_escola             = escola.ed18_i_codigo                       ";
$sSqlAlunos .= "                                      and diario.ed95_i_calendario         = calendario.ed52_i_codigo                   ";
$sSqlAlunos .= "                                      and diario.ed95_i_aluno              = aluno.ed47_i_codigo                        ";
$sSqlAlunos .= "                                      and diario.ed95_i_serie              = serie.ed11_i_codigo                        ";
$sSqlAlunos .= "                                      and diario.ed95_i_regencia           = regencia.ed59_i_codigo                     ";
$sSqlAlunos .= "       inner join diariofinal         on  diariofinal.ed74_i_diario        = diario.ed95_i_codigo                       ";
$sSqlAlunos .= "       {$sWhere} {$sWhereEscola}                                                                                        ";
$sSqlAlunos .= " order by ed18_i_codigo;                                                                                                ";
$rsAlunos    = db_query($sSqlAlunos);
$iTotalTurma = pg_num_rows($rsAlunos);

if ($iTotalTurma == 0) {

  $sMsgErro  = "ERRO: Não foram encontrados registros para o filtro selecionado.<br>";
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsgErro}");
}

if ($iTotalTurma > 0) {

  for ($iContador = 0; $iContador < $iTotalTurma; $iContador++) {

    $oDadosSqlTurma = db_utils::fieldsMemory($rsAlunos, $iContador);
    $oTurma         = TurmaRepository::getTurmaByCodigo($oDadosSqlTurma->codigo_turma);
    $oEtapa         = EtapaRepository::getEtapaByCodigo($oDadosSqlTurma->codigo_etapa);
    $aAlunos        = $oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapa);

    if (count($aAlunos) == 0) {
      continue;
    }

    $oFiltros->aQuebras[$oDadosSqlTurma->codigo_escola]
                       [$oDadosSqlTurma->ensino]
                       [$oDadosSqlTurma->codigo_turma]
                       [$oDadosSqlTurma->codigo_etapa]
                       [$oDadosSqlTurma->matricula_aluno]
                       [$oDadosSqlTurma->diario] = $oDadosSqlTurma->resultado_final;

    $aEscolas[$oDadosSqlTurma->codigo_escola] = $oDadosSqlTurma->nome_escola;
  }

  EtapaRepository::removerEtapa($oEtapa);
  TurmaRepository::removerTurma($oTurma);
}

$sEnsino = "TODOS";
if ($oFiltros->iEnsino != 0) {

  $oEnsino = new Ensino($oFiltros->iEnsino);
  $sEnsino = $oEnsino->getNome();
}

/**
 * Iniciamos o PDF
 */
$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);
$oPdf->SetFillColor(225);

$head1 = "Listagem de Alunos Por Resultado Final";
$head3 = "Ano: {$oFiltros->iAno}";
$head4 = "Ensino: {$sEnsino}";

/**
 * Array para controle dos alunos, nao permitindo contabilizar alunos duplicados
 */
$oFiltros->aControleAlunos = array();

/**
 * Percorremos o array quebrado a pagina e totalizando os alunos por escola
 */
foreach ($oFiltros->aQuebras as $iEscola => $aEscola) {

  /**
   * Array com os resultados finais por escola
   */
  $oFiltros->aSituacoesPorEscola = array();

  /**
   * Propriedades para controle dos totalizadores dos resultados finais por escola
   */
  $oFiltros->iAprovadosPorEscola        = 0;
  $oFiltros->iReprovadosPorEscola       = 0;
  $oFiltros->iParcialAprovadosPorEscola = 0;

  if (array_key_exists($iEscola, $aEscolas)) {
    $head2 = "Escola: {$aEscolas[$iEscola]}";
  }

  $oPdf->AddPage();
  cabecalhoRelatorio($oPdf, $oFiltros);
  corpoRelatorio($oPdf, $oFiltros, $aEscola);
  imprimeTotalizador($oPdf, $oFiltros, 1);
}

/**
 * Imprime o cabecalho do relatorio de acordo com as colunas desejadas
 * @param PDF $oPdf
 * @param stdClass $oFiltros
 */
function cabecalhoRelatorio(PDF $oPdf, $oFiltros) {

  $oPdf->SetFont('arial', 'b', 7);
  foreach ($oFiltros->aCabecalho as $oCabecalho) {
    $oPdf->Cell($oCabecalho->iTamanho, $oFiltros->iAlturaColuna, $oCabecalho->sDescricao, 1, 0, 'C', 1);
  }
  $oPdf->Ln();
}

/**
 * Imprime o corpo do relatorio de acordo com as colunas desejadas. O corpo eh impresso seguindo a montagem do array.
 * Ou seja, sao impressos todos os alunos das etapas e ensinos existentes na escola, em sequencia
 * @param PDF $oPdf
 * @param stdClass $oFiltros
 * @param array $aEscola
 */
function corpoRelatorio(PDF $oPdf, $oFiltros, $aEscola) {

  $oFiltros->iTotalAlunos = 0;

  foreach ($aEscola as $iEnsino => $aEnsino) {

    $oEnsino = new Ensino($iEnsino);
    $sEnsino = $oEnsino->getNome();

    foreach ($aEnsino as $iTurma => $aTurma) {

      foreach ($aTurma as $iEtapa => $aEtapa) {

        $oEtapa  = EtapaRepository::getEtapaByCodigo($iEtapa);
        $sEtapa  = $oEtapa->getNome();

        /**
         * Percorremos os alunos matriculados na turma
         */
        foreach ($aEtapa as $iMatricula => $aDiarios ) {

          $oMatricula   = MatriculaRepository::getMatriculaByCodigo($iMatricula);
          $iCodigoAluno = $oMatricula->getAluno()->getCodigoAluno();
          $sNomeAluno   = $oMatricula->getAluno()->getNome();

          if (!in_array($iCodigoAluno, $oFiltros->aControleAlunos)) {
            $oFiltros->aControleAlunos[] = $iCodigoAluno;
          }

          if (($oPdf->GetY() > $oPdf->h - 15)) {

            $oPdf->AddPage();
            cabecalhoRelatorio($oPdf, $oFiltros);
          }

          $oPdf->SetFont('arial', '', 6);

          /**
           * Percorremos o array dos cabecalhos a serem impressos, e de acordo com o tipo selecionado, imprimimos os
           * devidos campos com tamanho correto
          */
          foreach ($oFiltros->aCabecalho as $oCabecalho) {

            switch ($oCabecalho->iTipo) {

              /**
               * Codigo do Aluno
               */
              case 1:

                $oPdf->Cell($oCabecalho->iTamanho, $oFiltros->iAlturaColuna, $iCodigoAluno, 1, 0, 'R');
                break;

              /**
               * Nome do aluno
               */
              case 2:

                $oPdf->Cell($oCabecalho->iTamanho, $oFiltros->iAlturaColuna, $sNomeAluno, 1, 0, 'L');
                break;

              /**
               * Nome do curso
               */
              case 3:

                $oPdf->Cell($oCabecalho->iTamanho, $oFiltros->iAlturaColuna, $sEnsino, 1, 0, 'L');
                break;

              /**
               * Descricao da etapa
               */
              case 4:

                $oPdf->Cell($oCabecalho->iTamanho, $oFiltros->iAlturaColuna, $sEtapa, 1, 0, 'L');
                break;

              /**
               * Resultado Final
               */
              case 5:

                /**
                 * Percorremos os diarios verificando os resultados finais. Caso encontre algum resultado como R,
                 * setamos a variavel do resultado para R
                 */
                $sResultadoFinal = 'A';
                foreach ($aDiarios as $iDiario => $sResultado) {

                  if ($sResultado == 'R') {
                    $sResultadoFinal = 'R';
                  }
                }
                $sResultadoFinal = totalizadorResultadoFinal($oPdf, $oFiltros, $iEnsino, $sResultadoFinal);
                $oPdf->Cell($oCabecalho->iTamanho, $oFiltros->iAlturaColuna, $sResultadoFinal, 1, 0, 'C');
                break;
            }
          }
          $oPdf->Ln();
          $oFiltros->iTotalAlunos++;
          $oFiltros->iTotalAlunosGeral++;
        }
      }
    }
  }
}

/**
 * Verica o Resultado Final, e incrementa os arrays e totalizadores de acordo com a referencia.
 * Retorna a descricao do Resultado Final de acordo com o termo de encerramento
 * @param PDF $oPdf
 * @param stdClass $oFiltros
 * @param integer $iEnsino
 * @param string $sResultado
 * @return string
 */
function totalizadorResultadoFinal(PDF $oPdf, $oFiltros, $iEnsino, $sResultado) {

  $sResultadoFinal = '';
  $aResultadoFinal = DBEducacaoTermo::getTermoEncerramento($iEnsino, $sResultado, $oFiltros->iAno);
  foreach ($aResultadoFinal as $oResultadoFinal) {

    switch ($oResultadoFinal->sReferencia) {

      case 'A':

        $oFiltros->iAprovadosPorEscola++;
        $oFiltros->iAprovadosGeral++;
        $oFiltros->aSituacoesPorEscola[$oResultadoFinal->sDescricao] = $oFiltros->iAprovadosPorEscola;
        $oFiltros->aSituacoesGeral[$oResultadoFinal->sDescricao]     = $oFiltros->iAprovadosGeral;
        break;

      case 'R':

        $oFiltros->iReprovadosPorEscola++;
        $oFiltros->iReprovadosGeral++;
        $oFiltros->aSituacoesPorEscola[$oResultadoFinal->sDescricao] = $oFiltros->iReprovadosPorEscola;
        $oFiltros->aSituacoesGeral[$oResultadoFinal->sDescricao]     = $oFiltros->iReprovadosGeral;
        break;

      case 'P':

        $oFiltros->iParcialAprovadosPorEscola++;
        $oFiltros->iParcialAprovadosGeral++;
        $oFiltros->aSituacoesPorEscola[$oResultadoFinal->sDescricao] = $oFiltros->iParcialAprovadosPorEscola;
        $oFiltros->aSituacoesGeral[$oResultadoFinal->sDescricao]     = $oFiltros->iParcialAprovadosGeral;
        break;
    }

    $sResultadoFinal = $oResultadoFinal->sDescricao;
  }

  return $sResultadoFinal;
}

/**
 * Imprime o totalizador. De acordo com o parametro $sTotal, imprime o totalizador por escola ou geral
 * 1 - Total de alunos por escola
 * 2 - Total de alunos no municipio
 * @param PDF $oPdf
 * @param stdClass $oFiltros
 */
function imprimeTotalizador(PDF $oPdf, $oFiltros, $iTipoTotalizador) {

  $oPdf->SetFont('arial', 'b', 7);

  if ($iTipoTotalizador == 1) {

    $sMensagem = "Total de Alunos na Escola: {$oFiltros->iTotalAlunos}";
    $oPdf->Cell(192, $oFiltros->iAlturaColuna, $sMensagem, 1, 1, 'R', 1);
    imprimeResultadosFinais($oPdf, $oFiltros, 1);
  } else {

    $sMensagem = "Total de Alunos no Munícipio: ".count($oFiltros->aControleAlunos);
    $oPdf->Cell(192, 4, $sMensagem, 1, 1, 'R', 1);
    imprimeResultadosFinais($oPdf, $oFiltros, 2);
  }
}

/**
 * Imprime ao final de cada e ao final do arquivo (caso tenha sido setada todas as escolas), os totalizadores de
 * resultados finais por escola e/ou por municipio
 * 1 - Por Escola
 * 2 - Geral
 * @param PDF $oPdf
 * @param stdClass $oFiltros
 * @param integer $iTipoResultadosFinais
 */
function imprimeResultadosFinais(PDF $oPdf, $oFiltros, $iTipoResultadosFinais) {

  $aSituacoes   = '';
  $iBaseCalculo = 0;

  if ($iTipoResultadosFinais == 1) {

    $aSituacoes   = $oFiltros->aSituacoesPorEscola;
    $iBaseCalculo = $oFiltros->iTotalAlunos;
  } else {

    $aSituacoes   = $oFiltros->aSituacoesGeral;
    $iBaseCalculo = $oFiltros->iTotalAlunosGeral;
  }

  if (($oPdf->GetY() > $oPdf->h - 15)) {

    $oPdf->AddPage();
    cabecalhoRelatorio($oPdf, $oFiltros);
  }

  /**
   * Buscamos a posicao X e Y ao final da impressao dos alunos, para montar o rodape
   */
  $oFiltros->iPosicaoX = $oPdf->GetX();
  $oFiltros->iPosicaoY = $oPdf->GetY();
  $iLimiteColuna       = 192;
  $iTotalSituacoes     = count($aSituacoes);

  $oPdf->SetFont('arial', 'b', 6);
  $oPdf->SetFont('arial', 'b', 6);
  $oPdf->SetXY(10, $oFiltros->iPosicaoY);
  $oPdf->Cell(192, $oFiltros->iAlturaColuna, "Totalizadores", 1, 1, "C", 1);

  /**
   * Aplicamos o tamanho de cada coluna a ser impressa, de acordo com o numero de situacoes retornadas
  */
  $iColunaTotalizador = $iLimiteColuna / count($aSituacoes);

  $iPosicaoXIndice = $oPdf->GetX();
  $iPosicaoY       = $oPdf->GetY();
  $iPosicaoXTotal  = $oPdf->GetX();

  /**
   * Percorremos e imprimimos as situacoes e seus valores
  */
  ksort($aSituacoes);

  foreach ($aSituacoes as $sIndice => $iTotal) {

    $oPdf->SetFont('arial', 'b', 6);
    $oPdf->SetXY($iPosicaoXIndice, $iPosicaoY);
    $oPdf->Cell($iColunaTotalizador, $oFiltros->iAlturaColuna, $sIndice, 1, 1, "C");

    $oPdf->SetXY($iPosicaoXIndice, $iPosicaoY + 4);
    $iPosicaoXIndice = $oPdf->GetX() + $iColunaTotalizador;

    $oPdf->Cell($iColunaTotalizador/2, $oFiltros->iAlturaColuna, "Total",       1, 0, "C");
    $oPdf->Cell($iColunaTotalizador/2, $oFiltros->iAlturaColuna, "Porcentagem", 1, 0, "C");

    $oPdf->SetFont('arial', '', 6);
    $oPdf->SetXY($iPosicaoXTotal, $iPosicaoY + 8);

    $oPdf->Cell($iColunaTotalizador/2, $oFiltros->iAlturaColuna, $iTotal, 1, 0, "C");

    /**
     * Pegamos o percentual referente a situacao
    */
    $nPercentual = $iTotal / $iBaseCalculo;
    $nPercentual = round($nPercentual, 2) * 100;

    $oPdf->Cell($iColunaTotalizador/2, $oFiltros->iAlturaColuna, $nPercentual."%", 1, 0, "C");
    $iPosicaoXTotal = $iPosicaoXTotal + $iColunaTotalizador;
  }

  $oPdf->Ln(4);
  $oPdf->SetFont('arial', 'b', 6);
}

/**
 * Imprimimos o totalizador de alunos e resultados finanis do municipio, caso tenham sido selecionadas todas as escolas
 */
if ($oFiltros->iEscola == 0) {

  $oPdf->Ln();
  imprimeTotalizador($oPdf, $oFiltros, 2);
}
$oPdf->Output();
?>
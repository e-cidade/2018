<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require_once ("libs/db_stdlib.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/JSON.php");
require_once ("std/DBDate.php");
require_once ("dbforms/db_funcoes.php");
require_once ("model/educacao/avaliacao/iFormaObtencao.interface.php");
require_once ("model/educacao/avaliacao/iElementoAvaliacao.interface.php");

db_app::import("exceptions.*");
db_app::import("educacao.*");
db_app::import("educacao.avaliacao.*");

$oDadosFormulario = db_utils::postMemory($_GET);
$oJson            = new Services_JSON();
$aTurmas          = $oJson->decode(str_replace("\\","",$oDadosFormulario->aTurmas));

/**
 * Instanciamos um objeto com os dados a serem utilizados no relatorio
 */
$oFiltros                = new stdClass();
$oFiltros->iEscola       = $oDadosFormulario->iEscola;
$oFiltros->iCalendario   = $oDadosFormulario->iCalendario;
$oFiltros->iTurmaDestino = $oDadosFormulario->iTurmaDestino;
$oFiltros->iOrdenacao    = $oDadosFormulario->iOrdenacao;
$oFiltros->iAlunosAtivos = $oDadosFormulario->iAlunosAtivos;
$oFiltros->iTrocaTurma   = $oDadosFormulario->iTrocaTurma;

/**
 * Setamos a altura padrao para as colunas do codigo, nome e resultado final do aluno, que sempre serao impressas
 */
$oFiltros->iColunaCodigoAluno    = 20;
$oFiltros->iColunaNome           = 132;
$oFiltros->iColunaResultadoFinal = 40;
$oFiltros->iAlturaColuna         = 4;
$oFiltros->iAlunosPorPagina      = 55;

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(true);
$oPdf->SetFillColor(225, 225, 225);

/**
 * Caso seja solicitada a coluna turma de destino, alteramos a coluna nome para acrescentar esta nova
 */
if ($oFiltros->iTurmaDestino == 1) {
  
  $oFiltros->iColunaNome         = 112;
  $oFiltros->iColunaTurmaDestino = 20;
}

/**
 * Percorremos as turmas a serem impressas
 */
foreach ($aTurmas as $oRetornoTurma) {
  
  $oTurma = TurmaRepository::getTurmaByCodigo($oRetornoTurma->ed57_i_codigo);
  $oEtapa = EtapaRepository::getEtapaByCodigo($oRetornoTurma->codigo_etapa);
  
  $oFiltros->aAlunosMatriculados      = array();
  $oFiltros->aDadosAlunos             = array();
  $oFiltros->aAlunosMatriculados      = $oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapa);
  $oFiltros->iTotalAlunosMatriculados = count($oFiltros->aAlunosMatriculados);
  $oFiltros->iEnsino                  = $oTurma->getBaseCurricular()->getCurso()->getEnsino()->getCodigo();
  $oFiltros->iAno                     = $oTurma->getCalendario()->getAnoExecucao();
  
  $oFiltros->aSituacoes         = array();
  $oFiltros->iTotalAprovados    = 0;
  $oFiltros->iTotalReprovados   = 0;
  $oFiltros->iTotalTransferidos = 0;
  $oFiltros->iTotalEvadidos     = 0;
  $oFiltros->iTotalParcialmente = 0;
  $oFiltros->iTotalAtivos       = 0;
  $oFiltros->iTotalInativos     = 0;
  
  $head1 = "Lista de Resultados Finais";
  $head2 = "Turma: {$oTurma->getDescricao()}";
  $head3 = "Etapa: {$oEtapa->getNome()}";
  $head4 = "Calendário: {$oTurma->getCalendario()->getDescricao()}";
  $head5 = "Curso: {$oTurma->getBaseCurricular()->getCurso()->getNome()}";
  
  /**
   * Montamos o array com os dados necessarios para cada aluno
   */
  foreach ($oFiltros->aAlunosMatriculados as $oMatricula) {
  
    if ($oFiltros->iTrocaTurma == 1 && $oMatricula->getSituacao() == 'TROCA DE TURMA') {
      continue;
    }
    
    if ($oFiltros->iAlunosAtivos == 2 && $oMatricula->getSituacao() != 'MATRICULADO') {
      continue;
    }
    
    $oDadosMatricula = new stdClass();
    $oDadosMatricula->iCodigoAluno = $oMatricula->getAluno()->getCodigoAluno();
    $oDadosMatricula->sNomeAluno   = $oMatricula->getAluno()->getNome();
  
    db_inicio_transacao();
    $oDadosMatricula->sResultadoFinal = $oMatricula->getDiarioDeClasse()->getResultadoFinal();
    db_fim_transacao();
  
    /**
     * Verificamos a situacao da matricula do aluno, para buscar o resultado final e incrementar os totalizadores
     */
    switch ($oMatricula->getSituacao()) {
  
      case 'TRANSFERIDO FORA':
  
        $oFiltros->iTotalInativos++;
        $oFiltros->iTotalTransferidos++;
        $oFiltros->aSituacoes['TRANSFERIDOS'] = $oFiltros->iTotalTransferidos;
        $oDadosMatricula->sResultadoFinal     = $oMatricula->getSituacao();
        break;
  
      case 'TRANSFERIDO REDE':
  
        $oFiltros->iTotalInativos++;
        $oFiltros->iTotalTransferidos++;
        $oFiltros->aSituacoes['TRANSFERIDOS'] = $oFiltros->iTotalTransferidos;
        $oDadosMatricula->sResultadoFinal     = $oMatricula->getSituacao();
        break;
  
      case 'EVADIDO':
  
        $oFiltros->iTotalInativos++;
        $oFiltros->iTotalEvadidos++;
        $oFiltros->aSituacoes['EVADIDOS'] = $oFiltros->iTotalEvadidos;
        $oDadosMatricula->sResultadoFinal = $oMatricula->getSituacao();
        break;
  
      case 'TROCA DE TURMA':
  
        $oFiltros->iTotalInativos++;
        $oDadosMatricula->sResultadoFinal = $oMatricula->getSituacao();
        break;
  
      case 'MATRICULADO':
  
        $oFiltros->iTotalAtivos++;
        $aResultadoFinal = DBEducacaoTermo::getTermoEncerramento($oFiltros->iEnsino, $oDadosMatricula->sResultadoFinal, $oFiltros->iAno);
        foreach ($aResultadoFinal as $oResultadoFinal) {
  
          switch ($oResultadoFinal->sReferencia) {
  
            case 'A':
  
              $oFiltros->iTotalAprovados++;
              $oFiltros->aSituacoes[$oResultadoFinal->sDescricao] = $oFiltros->iTotalAprovados;
              break;
  
            case 'R':
  
              $oFiltros->iTotalReprovados++;
              $oFiltros->aSituacoes[$oResultadoFinal->sDescricao] = $oFiltros->iTotalReprovados;
              break;
  
            case 'P':
  
              $oFiltros->iTotalParcialmente++;
              $oFiltros->aSituacoes[$oResultadoFinal->sDescricao] = $oFiltros->iTotalParcialmente;
              break;
          }
          $oDadosMatricula->sResultadoFinal = $oResultadoFinal->sDescricao;
        }
        break;
    }
  
    $oFiltros->aDadosAlunos[] = $oDadosMatricula;
    MatriculaRepository::removerMatricula($oMatricula);
    unset($oDadosMatricula);
  }
  
  /**
   * Verificamos a ordenacao selecionada
   */
  switch ($oFiltros->iOrdenacao) {
    
    case '1':
      
      uasort($oFiltros->aDadosAlunos, "ordernarMatriculaPorNome");
      break;
      
    case '2':
      break;
      
    case '3':
      
      uasort($oFiltros->aDadosAlunos, "ordernarMatriculaPorResultadoFinal");
      break;
  }

  /**
   * Criamos um array com o total de alunos a serem impressos por pagina
   */
  $aAlunosPorPagina = array();
  $iPagina          = 0;
  $iContador        = 1;
  
  foreach ($oFiltros->aDadosAlunos as $oDadosMatricula) {
    
    $aAlunosPorPagina[$iPagina][$iContador] = $oDadosMatricula;
    if ($iContador > $oFiltros->iAlunosPorPagina - 1) {
    
      $iPagina++;
      $iContador = 1;
    }
    $iContador++;
  }
  
  /**
   * Percorremos o array de alunos a serem impressos por pagina
   */
  foreach ($aAlunosPorPagina as $iIndice => $oFiltros->aAlunosPorPagina) {
    
    $oPdf->AddPage();
    cabecalhoColunas($oPdf, $oFiltros);
    corpoRelatorio($oPdf, $oFiltros);
    rodapeRelatorio($oPdf, $oFiltros);
  }
  TurmaRepository::removerTurma($oTurma);
}

/**
 * Cabecalho com as colunas a serem impressas
 * @param FPDF $oPdf
 * @param stdClass $oFiltros
 */
function cabecalhoColunas($oPdf, $oFiltros) {
  
  $oPdf->SetFont('arial', 'b', 6);
  $oPdf->Cell($oFiltros->iColunaCodigoAluno, $oFiltros->iAlturaColuna, "Código do Aluno", 1, 0, "C", 1);
  $oPdf->Cell($oFiltros->iColunaNome,        $oFiltros->iAlturaColuna, "Nome Completo",   1, 0, "C", 1);
  
  /**
   * Verificamos se deve apresentar a coluna Turma de Destino
   */
  if ($oFiltros->iTurmaDestino == 1) {
    
    $oPdf->Cell($oFiltros->iColunaResultadoFinal, $oFiltros->iAlturaColuna, "Resultado Final",  1, 0, "C", 1);
    $oPdf->Cell($oFiltros->iColunaTurmaDestino,   $oFiltros->iAlturaColuna, "Turma de Destino", 1, 1, "C", 1);
  } else {
    $oPdf->Cell($oFiltros->iColunaResultadoFinal, $oFiltros->iAlturaColuna, "Resultado Final", 1, 1, "C", 1);
  }
}

/**
 * Corpo do relatorio com os dados de cada aluno
 * @param FPDF $oPdf
 * @param stdClass $oFiltros
 */
function corpoRelatorio($oPdf, $oFiltros) {
  
  $oPdf->SetFont('arial', '', 5);
  foreach ($oFiltros->aAlunosPorPagina as $oDadosMatricula) {
    
    $oPdf->Cell($oFiltros->iColunaCodigoAluno, $oFiltros->iAlturaColuna, $oDadosMatricula->iCodigoAluno, 1, 0, "R");
    $oPdf->Cell($oFiltros->iColunaNome,        $oFiltros->iAlturaColuna, $oDadosMatricula->sNomeAluno,   1, 0, "L");
    
    /**
     * Verificamos se deve apresentar a coluna Turma de Destino
     */
    if ($oFiltros->iTurmaDestino == 1) {
      
      $oPdf->Cell($oFiltros->iColunaResultadoFinal, $oFiltros->iAlturaColuna, $oDadosMatricula->sResultadoFinal, 1, 0, "L");
      $oPdf->Cell($oFiltros->iColunaTurmaDestino,   $oFiltros->iAlturaColuna, "", 1, 1, "L");
    } else {
      $oPdf->Cell($oFiltros->iColunaResultadoFinal, $oFiltros->iAlturaColuna, $oDadosMatricula->sResultadoFinal, 1, 1, "L");
    }
  }
  
  /**
   * Buscamos a posicao X e Y ao final da impressao dos alunos, para montar o rodape
   */
  $oFiltros->iPosicaoX = $oPdf->GetX();
  $oFiltros->iPosicaoY = $oPdf->GetY();
}

/**
 * Rodape com os totalizadores por turma
 * @param FPDF $oPdf
 * @param stdClass $oFiltros
 */
function rodapeRelatorio($oPdf, $oFiltros) {
  
  $iLimiteColuna   = 192;
  $iTotalSituacoes = count($oFiltros->aSituacoes);
  $oPdf->SetFont('arial', 'b', 6);
  $oPdf->SetFont('arial', 'b', 6);
  $oPdf->SetXY(10, $oFiltros->iPosicaoY);
  $oPdf->Cell(192, $oFiltros->iAlturaColuna, "Totalizadores", 1, 1, "C", 1);
  
  /**
   * Aplicamos o tamanho de cada coluna a ser impressa, de acordo com o numero de situacoes retornadas
   */
  $iColunaTotalizador = $iLimiteColuna / count($oFiltros->aSituacoes);
  
  $iPosicaoXIndice = $oPdf->GetX();
  $iPosicaoY       = $oPdf->GetY();
  $iPosicaoXTotal  = $oPdf->GetX();
  
  /**
   * Percorremos e imprimimos as situacoes e seus valores
   */
  ksort($oFiltros->aSituacoes);
  foreach ($oFiltros->aSituacoes as $sIndice => $iTotal) {
    
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
    $nPercentual = $iTotal / $oFiltros->iTotalAlunosMatriculados;
    $nPercentual = round($nPercentual, 2) * 100;
    
    $oPdf->Cell($iColunaTotalizador/2, $oFiltros->iAlturaColuna, $nPercentual."%", 1, 0, "C");
    $iPosicaoXTotal = $iPosicaoXTotal + $iColunaTotalizador;
  }
  
  $oPdf->Ln(4);
  $oPdf->SetFont('arial', 'b', 6);
  
  /**
   * Caso tenha sido solicitado imprimir alunos ativos e inativos, imprimimos uma linha com estas informacoes
   */
  if ($oFiltros->iAlunosAtivos == 1) {
    
    $oPdf->Cell($iLimiteColuna/2, $oFiltros->iAlturaColuna, "Total de Alunos Ativos: {$oFiltros->iTotalAtivos}",     1, 0, "R");
    $oPdf->Cell($iLimiteColuna/2, $oFiltros->iAlturaColuna, "Total de Alunos Inativos: {$oFiltros->iTotalInativos}", 1, 1, "R");
  }
  
  $sTotalAlunosMatriculados = "Total de Alunos Matriculados: {$oFiltros->iTotalAlunosMatriculados}";
  $oPdf->Cell($iLimiteColuna, $oFiltros->iAlturaColuna, $sTotalAlunosMatriculados, 1, 1, "R", 1);
}

/**
 * Ordena os alunos por ordem alfabetica
 * @param array $aArrayAtual
 * @param array $aProximoArray
 */
function ordernarMatriculaPorNome($aArrayAtual, $aProximoArray) {
  return strcasecmp($aArrayAtual->sNomeAluno, $aProximoArray->sNomeAluno);
}

/**
 * Ordena os alunos pelo resultado final
 * @param array $aArrayAtual
 * @param array $aProximoArray
 */
function ordernarMatriculaPorResultadoFinal($aArrayAtual, $aProximoArray) {
  return strcasecmp($aArrayAtual->sResultadoFinal, $aProximoArray->sResultadoFinal);
}

$oPdf->Output();
?>
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

require_once ("fpdf151/pdfwebseller.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("std/DBDate.php");
require_once ("classes/db_edu_parametros_classe.php");
require_once ("model/educacao/avaliacao/iFormaObtencao.interface.php");
require_once ("model/educacao/avaliacao/iElementoAvaliacao.interface.php");

$oGet = db_utils::postMemory($_GET);
db_app::import("CgmFactory");
db_app::import("educacao.*");
db_app::import("exceptions.*");

/**
 * Buscamos informações da Turma e os alunos vinculados
 */
$oTurmaAC = new cl_turmaacmatricula();
$sCampos  = " ed47_i_codigo  as codigo_aluno,";
$sCampos .= " ed47_v_nome    as aluno, ";
$sCampos .= " (select  extract(year from age(ed47_d_nasc))) as idade, ";
$sCampos .= " ed268_i_codigo as codigo_turma, ";
$sCampos .= " ed268_c_descr  as nome_turma ";

$sWhere   = "     ed268_i_codigo     = {$oGet->iTurma}";
$sWhere  .= " and ed268_i_escola     = {$oGet->iEscola}";
$sWhere  .= " and ed268_i_calendario = {$oGet->iCalendario}";

$sSqlTurmaAC = $oTurmaAC->sql_query_turma(null, $sCampos, "ed47_v_nome", $sWhere);
$rsTurmaAC   = $oTurmaAC->sql_record($sSqlTurmaAC);
$iRegistros  = $oTurmaAC->numrows;

$oCalendario       = new Calendario($oGet->iCalendario);
$aAlunosVinculados = array();

if ($iRegistros == 0) {

  $sMsgErro  = "Nenhum aluno encontrado para turma selecionada.<br>";
  $sMsgErro .= "Turma : {$oGet->iTurma}";
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsgErro}");
}

for ($i = 0; $i < $iRegistros; $i++) {

  $aAlunosVinculados[] = db_utils::fieldsMemory($rsTurmaAC, $i);
}


$oDaoTurmaHorario = new cl_turmaachorarioprofissional();
$sSqlBuscaDocente = $oDaoTurmaHorario->sql_query_file(null, "ed346_rechumano", null, "ed346_turmaac = {$oGet->iTurma}");
$rsBuscaDocente   = $oDaoTurmaHorario->sql_record($sSqlBuscaDocente);

/**
 * Informacoes globais do cabecalho do relatorio
 */
$oDocente         = null;
if ($oDaoTurmaHorario->numrows > 0) {

  $iRecursoHumano = db_utils::fieldsMemory($rsBuscaDocente, 0)->ed346_rechumano;
  $oDocente       = DocenteRepository:: getDocenteByCodigoRecursosHumano($iRecursoHumano);
}

$sDiario      = "Diário de Classe - Turmas de AC e AEE";
$sCalendario  = "Calendário: " . $oCalendario->getDescricao();
$sTurma       = "Turma: " . $aAlunosVinculados[0]->nome_turma;
$sRegente    = "Regente: ";

if (!empty($oDocente)) {
  $sRegente .= $oDocente->getNome();
}

$head1 = $sDiario;
$head2 = $sCalendario;
$head3 = $sTurma;
$head4 = $sRegente;

/**
 * Configuracoes do relatorio
 */
$iNumeroDeAlunosPorPagina = 36;

/**
 * Calculo da grid de avaliacao
 */
$iTamanhoColuna = round(185 / $oGet->iNumeroColunas, 2);


$oPdf = new PDF("L");
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetFillColor(235);
$oPdf->SetAutoPageBreak(false);

$lPrimeiraVolta = true;
$iHeigth        = 4;

$iQuantosAlunosTemNaTurma = count($aAlunosVinculados);
$iLinhasImpressa          = 0;
$iLinhasImpressaNaPagina  = 0;

/**
 * Imprimindo relatório
 */
foreach ($aAlunosVinculados as $iIndice => $oAluno) {

  $iLinhasImpressa ++;
  $iLinhasImpressaNaPagina ++;

  if (($oPdf->GetY() > $oPdf->h -10) || $lPrimeiraVolta) {

    $lPrimeiraVolta = false;
    imprimeHeader($oPdf, $iTamanhoColuna,  $oGet->iNumeroColunas);
  }

  /**
   * Imprimindo a lista de Alunos
   */
  $oPdf->SetFont("arial", "", 7);
  $oPdf->Cell(5,  $iHeigth, $iIndice + 1,                      1, 0, "C");
  $oPdf->Cell(70, $iHeigth, strtoupper(substr($oAluno->aluno, 0, 43)), 1, 0, "L");
  $oPdf->Cell(5,  $iHeigth, $oAluno->idade,                            1, 0, "C");

  $iEixoX = $oPdf->getX();
  $iEixoY = $oPdf->getY();
  for($iColuna = 1; $iColuna <= $oGet->iNumeroColunas; $iColuna++) {

    $oPdf->setfont('arial','b',12);
    $oPdf->Cell($iTamanhoColuna,  $iHeigth, "", 1, 0, "C");
    $oPdf->Text($iEixoX + ($iTamanhoColuna * 37 / 100), $iEixoY + 2, ".");
    $iEixoX = $oPdf->getX();
  }
  $oPdf->SetFont("arial", "", 7);
  $oPdf->Cell(5,  $iHeigth, $iIndice + 1,    1, 0, "C");
  $oPdf->Cell(10, $iHeigth, "",                      1, 1, "C");



  /**
   * Nesse if validamos quando o numero de alunos vinculados a turma eh maior do que o numero de alunos
   * suportados em uma folha do relatorio.
   * Quando este caso for valido teremos no minimo duas paginas de relatorio
   */
  $iNumeroDeLinhasEmBranco = 0;
  $lQuebrouPaginaPorLimiteDeAlunosPorPagina = false ;
  if (($iLinhasImpressa >= $iNumeroDeAlunosPorPagina) && ($iLinhasImpressa == $iQuantosAlunosTemNaTurma)) {

    $lQuebrouPaginaPorLimiteDeAlunosPorPagina = true ;
    $iNumeroDeLinhasEmBranco = $iNumeroDeAlunosPorPagina - $iLinhasImpressaNaPagina;
  }

  /**
   * Nesse if validamos quando o numero de alunos vinculados a turma eh menor do que o numero de alunos
   * suportados em uma folha do relatorio.
   * Quando este caso for valido teremos uma folha com no minimo uma linha impressa sem dados (vazia)
   */
  $lMenosAlunosNaTurma = false;
  if (($iQuantosAlunosTemNaTurma == $iLinhasImpressaNaPagina) &&
      ($iLinhasImpressaNaPagina < $iNumeroDeAlunosPorPagina)) {

    $lMenosAlunosNaTurma = true;
    $iNumeroDeLinhasEmBranco = $iNumeroDeAlunosPorPagina - $iQuantosAlunosTemNaTurma;
  }
  /**
   * O Diario de classe necessita ser preenchido com linhas em branco, se não existir alunos suficientes
   * para preenche-lo
   * Se Este for o caso, $iLinhasImpressa sempre sera o maximo de linha comportado no documento (33)
   */
  if ($lQuebrouPaginaPorLimiteDeAlunosPorPagina || $lMenosAlunosNaTurma) {

    for ($iLinha = 0; $iLinha < $iNumeroDeLinhasEmBranco; $iLinha++) {

      $oPdf->Cell(5,  $iHeigth, "", 1, 0, "C");
      $oPdf->Cell(70, $iHeigth, "", 1, 0, "L");
      $oPdf->Cell(5,  $iHeigth, "", 1, 0, "L");

      $iEixoX = $oPdf->getX();
      $iEixoY = $oPdf->getY();
      for($iColuna = 1; $iColuna <= $oGet->iNumeroColunas; $iColuna++) {

        $oPdf->setfont('arial','b',12);
        $oPdf->Cell($iTamanhoColuna,  $iHeigth, "", 1, 0, "C");
        $oPdf->Text($iEixoX + ($iTamanhoColuna * 37 / 100), $iEixoY + 2, ".");
        $iEixoX = $oPdf->getX();
      }
      $oPdf->SetFont("arial", "", 7);
      $oPdf->Cell(5,  $iHeigth, "", 1, 0, "C");
      $oPdf->Cell(10, $iHeigth, "", 1, 1, "C");
    }
    $iLinhasImpressa         = $iNumeroDeAlunosPorPagina;
    $iLinhasImpressaNaPagina = $iNumeroDeAlunosPorPagina;
  }

  if ($iLinhasImpressa == $iNumeroDeAlunosPorPagina) {

    $lPrimeiraVolta  = true;
    $iLinhasImpressaNaPagina = 0;
    imprimeAssinatura($oPdf);
  }
}


$oPdf->Output();

/**
 * Imprime o cabecalho do relatorio
 * @param PDF $oPdf
 */
function imprimeHeader($oPdf, $iTamanhoColuna, $iNumeroColunas) {

  $oPdf->AddPage("L");
  $oPdf->SetFont("arial", "b", 7);
  $oPdf->Cell(70,  4, "",              1, 0);
  $oPdf->Cell(10,  4, "Mes >",         1, 0, "C");
  $oPdf->Cell(200, 4, "",              1, 1);
  $oPdf->Cell(5,   4, "Nº",            1, 0);
  $oPdf->Cell(65,  4, "Nome do Aluno", 1, 0, "C");
  $oPdf->Cell(10,  4, "Dia >",         1, 0, "C");
  for($i = 0; $i < $iNumeroColunas; $i++) {
    $oPdf->Cell($iTamanhoColuna, 4, "", 1, 0);
  }
  $oPdf->Cell(5,  4, "Nº", 1, 0, "C");
  $oPdf->Cell(10, 4, "Ft", 1, 1, "C");
}

/**
 * Imprime a Assinatura no fim da pagina
 * @param PDF $oPdf
 */
function imprimeAssinatura($oPdf) {

  $oPdf->SetFont("arial", "b", 7);
  $oPdf->Cell(140, 5.5, " Entregue em: ____/____/_____ POR ____________________________________", 1, 0);
  $oPdf->Cell(140, 5.5, " Revisado em: ____/____/_____ POR ____________________________________", 1, 1);

  $oPdf->Cell(140, 5.5, " Processado em: ____/____/_____ POR __________________________________", 1, 0);
  $oPdf->Cell(140, 5.5, " Assinatura do Professor: ____________________________________________", 1, 1);
}
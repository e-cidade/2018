<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once ("model/educacao/avaliacao/iFormaObtencao.interface.php");
require_once ("model/educacao/avaliacao/iElementoAvaliacao.interface.php");
require_once ("classes/db_edu_parametros_classe.php");

db_app::import("CgmFactory");
db_app::import("educacao.ArredondamentoNota");
db_app::import("educacao.DBEducacaoTermo");
db_app::import("educacao.progressaoparcial.*");
db_app::import("educacao.avaliacao.*");
db_app::import("educacao.*");
db_app::import("exceptions.*");

$oGet = db_utils::postMemory($_GET);

$aRegencias = explode(",", $oGet->sRegencias);

$oTurma            = TurmaRepository::getTurmaByCodigo($oGet->iTurma);
$aAlunosVinculados = array();

/**
 * Buscamos todos os alunos vinculados a turma
 */
foreach ($oTurma->getAlunosProgressaoParcial() as $oProgressaoAluno) {

  $oDadosAluno = new stdClass();
  $oDadosAluno->iCodigoProgressaoParcial = $oProgressaoAluno->getCodigoProgressaoParcial();
  $oDadosAluno->iCodigoAluno             = $oProgressaoAluno->getAluno()->getCodigoAluno();
  $oDadosAluno->sNomeAluno               = $oProgressaoAluno->getAluno()->getNome();
  $oDadosAluno->iDisciplina              = $oProgressaoAluno->getDisciplina()->getCodigoDisciplina();
  $oDadosAluno->dtVinculo                = $oProgressaoAluno->getVinculoRegencia()
                                                            ->getDataVinculo()
                                                            ->convertTo(DBDate::DATA_EN);
  $oDadosAluno->oDataNascimento          = new DBDate($oProgressaoAluno->getAluno()->getDataNascimento());
  $oDadosAluno->iIdade                   = $oProgressaoAluno->getAluno()->getIdade();
  $oDadosAluno->sSexo                    = $oProgressaoAluno->getAluno()->getSexo();
  $aAlunosVinculados[$oDadosAluno->iDisciplina][] = $oDadosAluno;
}

$lSemAlunosVinculados = true;
foreach ($aRegencias as $iCodigoRegencia) {

  $oRegencia   = RegenciaRepository::getRegenciaByCodigo($iCodigoRegencia);
  if (isset($aAlunosVinculados[$oRegencia->getDisciplina()->getCodigoDisciplina()])) {
    
    $lSemAlunosVinculados = false;
    break;
  }
}

if ($lSemAlunosVinculados) {

  $sMsgErro  = "Nenhum aluno vinculado para as disciplinas selecionadas.<br>";
  $sMsgErro .= "Turma : {$oGet->iTurma}";
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsgErro}");
}

/**
 * Informacoes globais do cabecalho do relatorio
 */
$sCurso       = "Curso: " . $oTurma->getBaseCurricular()->getCurso()->getCodigo() . " - ";
$sCurso      .= $oTurma->getBaseCurricular()->getCurso()->getNome();
$sCalendario  = "Calendário: " . $oTurma->getCalendario()->getDescricao();
$sTurma       = "Turma: " . $oTurma->getDescricao();
$sDiasLetivos = "Dias Letivos: " . $oTurma->getCalendario()->getDiasLetivos();
$sPeriodo     = "Período: ";
// Buscamos o periodo de avaliacao
foreach ($oTurma->getCalendario()->getPeriodos() as $oPeriodo) {
  
  if ($oPeriodo->getPeriodoAvaliacao()->getCodigo() == $oGet->iPeriodo){
    
    $sPeriodo .= $oPeriodo->getPeriodoAvaliacao()->getDescricao();
    break;
  }
}

/**
 * Configuracoes do relatorio 
 */
$iNumeroDeAlunosPorPagina = 40;

/**
 * Calculo da grid de avaliacao
 */
$iTamanhoColuna = round(200 / $oGet->iNumeroColunas, 2);

$oPdf = new PDF("L");
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetFillColor(235);
$oPdf->SetAutoPageBreak(false);

/**
 * Esse relatorio imprime duas paginas para cada disciplina selecionada
 * Na 1o pagina uma lista com no nome de todos os aluno vinculados a turma e uma grade para marcar presenca 
 * Na 2o pagina deve mostrar novamente os alunos e as proximas colunas devera obdecer os parametros configurados
 *    na tela de emissao. (CAMPO: Configurações do Relatório)
 *
 * Sendo assim comessaremos a logica do programa iterando sobre as disciplinas
 */
foreach ($aRegencias as $iCodigoRegencia) {
  
  $oRegencia   = RegenciaRepository::getRegenciaByCodigo($iCodigoRegencia);
  if (!isset($aAlunosVinculados[$oRegencia->getDisciplina()->getCodigoDisciplina()])) {
    continue;
  }
  $sEtapa      = "Etapa: " . $oRegencia->getEtapa()->getNome();  
  $sDisciplina = "Disciplina: " . $oRegencia->getDisciplina()->getNomeDisciplina();
  // Buscando o regente
  $aDocentes   = $oRegencia->getDocentes();
  $sRegente    = "Regente: ";
  
  if (count($aDocentes) > 0) {
    $sRegente .= current($aDocentes)->getNome();
  }

  $head1 = "Diário de Classe / Progressão Parcial";
  $head2 = $sCurso;
  $head3 = $sCalendario;
  $head4 = $sEtapa;
  $head5 = $sPeriodo;
  $head6 = $sTurma;
  $head7 = $sDisciplina;
  $head8 = $sRegente;
  $head9 = $sDiasLetivos;
  
  $lPrimeiraVolta                 = true;
  $iHeigth                        = 3.7;
  $iQtdAlunosVinculadosDisciplina = count($aAlunosVinculados[$oRegencia->getDisciplina()->getCodigoDisciplina()]);
  $aAlunosDisciplina              = $aAlunosVinculados[$oRegencia->getDisciplina()->getCodigoDisciplina()];
  $iLinhasImpressa                = 0;
  $iLinhasImpressaNaPagina        = 0;
  
  /** ************************************************************************************ 
   *                                     Processando a 1o Pagina
   */
  foreach ($aAlunosDisciplina as $iIndice => $oAluno) {
    
    $iLinhasImpressa ++; 
    $iLinhasImpressaNaPagina ++;
    
    if (($oPdf->GetY() > $oPdf->h - 10) || $lPrimeiraVolta) {
      
      $lPrimeiraVolta = false;
      imprimeHeader($oPdf, $iTamanhoColuna,  $oGet->iNumeroColunas);
    }
    
    /**
     * Imprimindo a lista de Alunos
     */
    $oPdf->SetFont("arial", "", 7);
    $oPdf->Cell(5,  $iHeigth, "{$iLinhasImpressa}",                   1, 0, "C");
    $oPdf->Cell(70, $iHeigth, strtoupper(substr($oAluno->sNomeAluno, 0, 43)), 1, 0, "L");
    $oPdf->Cell(5,  $iHeigth, $oAluno->iIdade           , 1, 0, "C");
    
    imprimeQuadroPresenca($oPdf, $oGet, $iTamanhoColuna, $iHeigth);
    
    $oPdf->SetFont("arial", "", 7);
    $oPdf->Cell(0, $iHeigth, "", 0, 1);
    
    
    $oParametroValidacao = validaNecessidadeDeImprimirLinhasEmBranco($iLinhasImpressa, 
                                                                     $iLinhasImpressaNaPagina, 
                                                                     $iNumeroDeAlunosPorPagina, 
                                                                     $iQtdAlunosVinculadosDisciplina);
    
    /**
     * O Diario de classe necessita ser preenchido com linhas em branco, se não existir alunos suficientes 
     * para preenche-lo
     * Se Este for o caso, $iLinhasImpressa sempre sera o maximo de linha comportado no documento (40)
     */
    if ($oParametroValidacao->lQuebrouPaginaPorLimiteDeAlunosPorPagina || $oParametroValidacao->lMenosAlunosNaTurma) {
      
      for ($iLinha = 0; $iLinha < $oParametroValidacao->iNumeroDeLinhasEmBranco; $iLinha++) {
        
        $oPdf->Cell(5,  $iHeigth, "", 1, 0, "C");
        $oPdf->Cell(70, $iHeigth, "", 1, 0, "L");
        $oPdf->Cell(5,  $iHeigth, "", 1, 0, "C");
        
        imprimeQuadroPresenca($oPdf, $oGet, $iTamanhoColuna, $iHeigth);
       
        $oPdf->SetFont("arial", "", 7);
        $oPdf->Cell(0, $iHeigth, "", 0, 1);
        $iLinhasImpressa ++;
      }
      $iLinhasImpressa         = $iNumeroDeAlunosPorPagina;
      $iLinhasImpressaNaPagina = $iNumeroDeAlunosPorPagina;
    }
    
    /**
     * Imprime a Assinatura do professor no fim da folha
     */
    if ($iLinhasImpressa == $iNumeroDeAlunosPorPagina) {
      
      $lPrimeiraVolta          = true;
      $iLinhasImpressaNaPagina = 0;
      imprimeAssinatura($oPdf);
    }
  }
  
  unset($oParametroValidacao);
  
  /** *****************************************************************************************
   *                                 Processando a 2o Pagina
   */
  $iLinhasImpressa         = 0; // Zera o contador de linhas impressas
  $iLinhasImpressaNaPagina = 0; // Zera o contador de linhas impressas na pagina atual
  $lPrimeiraVolta          = true;

  foreach ($aAlunosDisciplina as $iIndice => $oAluno) {
  
    $iLinhasImpressa ++; 
    $iLinhasImpressaNaPagina ++;
  
    if (($oPdf->GetY() > $oPdf->h -10) || $lPrimeiraVolta) {
      $lPrimeiraVolta  = false;
      imprimeHeaderSegundaPagina($oPdf, $oGet);
    }
  
    $sNome = "Andrio Araujo da Costa Marima masroiposa de bagre wihaeaw";
    /**
     * Imprimindo a lista de Alunos
     */
    $oPdf->SetFont("arial", "", 7);
    $oPdf->Cell(5,  $iHeigth, "{$iLinhasImpressa}",                   1, 0, "C");
    $oPdf->Cell(80, $iHeigth, strtoupper(substr($oAluno->sNomeAluno, 0, 48)), 1, 0, "L");
  
    $iLarguraPagina = 195;
    $sLegenda       = "Legendas: ";
    if ($oGet->sexo == "true") {
    
      $oPdf->Cell(5, $iHeigth, "{$oAluno->sSexo}", 1, 0, "C");
      $sLegenda       .= "S - Sexo  |  "; 
      $iLarguraPagina -= 5;
    }
  
    if ($oGet->idade == "true") {
  
      $oPdf->Cell(5, $iHeigth, "{$oAluno->iIdade}",  1, 0, "C");
      $sLegenda       .= "I - Idade  |  ";
      $iLarguraPagina -= 5;
    }
  
    if ($oGet->codigoAluno == "true") {
  
      $oPdf->Cell(15, $iHeigth, "{$oAluno->iCodigoAluno}",  1, 0, "R");
      $sLegenda       .= "Código - Código do Aluno  |  ";
      $iLarguraPagina -= 15;
    }
    if ($oGet->nascimento == "true") {
  
      $oPdf->Cell(20, $iHeigth, "{$oAluno->oDataNascimento->getDate(DBDate::DATA_PTBR)}", 1, 0, "C");
      $sLegenda       .= "Nascimento - Data de Nascimento ";
      $iLarguraPagina -= 20;
    }
  
    imprimeCelulasAvaliacao($oPdf, $iLarguraPagina, $iHeigth);
    $oPdf->Cell(0, $iHeigth, "", 0, 1);
  
    
    $oParametroValidacao = validaNecessidadeDeImprimirLinhasEmBranco($iLinhasImpressa,
                                                                     $iLinhasImpressaNaPagina,
                                                                     $iNumeroDeAlunosPorPagina,
                                                                     $iQtdAlunosVinculadosDisciplina);
    /**
     * Preenchendo as linhas em branco da segunda pagina
     */
    if ($oParametroValidacao->lQuebrouPaginaPorLimiteDeAlunosPorPagina || $oParametroValidacao->lMenosAlunosNaTurma) {
  
  
      for ($iLinha = 0; $iLinha < $oParametroValidacao->iNumeroDeLinhasEmBranco; $iLinha++) {
     
        $oPdf->SetFont("arial", "", 7);
        $oPdf->Cell(5,  $iHeigth, "", 1, 0, "C");
        $oPdf->Cell(80, $iHeigth, "", 1, 0, "L");
     
        $iLarguraPagina = 195;
        if ($oGet->sexo == "true") {
        
          $oPdf->Cell(5, $iHeigth, "", 1, 0, "C");
          $iLarguraPagina -= 5;
        }
     
        if ($oGet->idade == "true") {
     
          $oPdf->Cell(5, $iHeigth, "",  1, 0, "C");
          $iLarguraPagina -= 5;
        }
     
        if ($oGet->codigoAluno == "true") {
     
          $oPdf->Cell(15, $iHeigth, "",  1, 0, "R");
          $iLarguraPagina -= 15;
        }
        if ($oGet->nascimento == "true") {
     
          $oPdf->Cell(20, $iHeigth, "", 1, 0, "C");
          $iLarguraPagina -= 20;
        }
     
        imprimeCelulasAvaliacao($oPdf, $iLarguraPagina, $iHeigth);
        $oPdf->Cell(0, $iHeigth, "", 0, 1);
      }
      $iLinhasImpressa         = $iNumeroDeAlunosPorPagina;
      $iLinhasImpressaNaPagina = $iNumeroDeAlunosPorPagina;
    }
  
  
    /**
     * Imprime a Assinatura do professor no fim da folha
     */
    if ($iLinhasImpressa == $iNumeroDeAlunosPorPagina) {
  
      $lPrimeiraVolta          = true;
      $iLinhasImpressaNaPagina = 0;
      $oPdf->SetFont("arial", "b", 7);
      $oPdf->Cell(280, 4, $sLegenda, 1, 1, "L");
      imprimeAssinatura($oPdf);
    }
  }
}

$oPdf->Output();

/**
 * Imprime o cabecalho do relatorio
 * @param PDF $oPdf
 */
function imprimeHeader($oPdf, $iTamanhoColuna, $iNumeroColunas) {
  
  $oPdf->AddPage();
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
  $oPdf->Cell(0, 4, "", 0, 1);  
}

function imprimeHeaderSegundaPagina($oPdf, $oGet) {
  
  $oPdf->AddPage();
  $oPdf->SetFont("arial", "b", 7);
  $oPdf->Cell(5,   4, "Nº",            1, 0, "C");
  $oPdf->Cell(80,  4, "Nome do Aluno", 1, 0, "C");
  
  $iLarguraPagina = 195;
  
  if ($oGet->sexo == "true") {
     
    $oPdf->Cell(5,   4, "S",            1, 0, "C");
    $iLarguraPagina -= 5;
  }
  
  if ($oGet->idade == "true") {
    
    $oPdf->Cell(5,   4, "I",            1, 0, "C");
    $iLarguraPagina -= 5;
  }
  
  if ($oGet->codigoAluno == "true") {
    
    $oPdf->Cell(15,   4, "Código",            1, 0, "C");
    $iLarguraPagina -= 15;
  }
  if ($oGet->nascimento == "true") {
    
    $oPdf->Cell(20,   4, "Nascimento",            1, 0, "C");
    $iLarguraPagina -= 20;
  }
  
  imprimeCelulasAvaliacao($oPdf, $iLarguraPagina, 4);
  $oPdf->Cell(0, 4, "", 0, 1);
}

function imprimeCelulasAvaliacao($oPdf, $iLarguraPagina, $iAlturaLinha) {
  
  $iNumeroDeColunasAvaliacao = 16;
  $iTamanhoQuadroAvaliacao   = round($iLarguraPagina / $iNumeroDeColunasAvaliacao, 2);
  
  for ($i = 0; $i < $iNumeroDeColunasAvaliacao; $i++) {
  
    $oPdf->Cell($iTamanhoQuadroAvaliacao, $iAlturaLinha, "", 1, 0);
  }
}

/**
 * Imprime a Assinatura no fim da pagina
 * @param PDF $oPdf
 */
function imprimeAssinatura($oPdf) {
  
  $oPdf->SetFont("arial", "b", 7);
  $oPdf->Cell(280, 4, " Assinatura do Professor: _________________________________________", 1, 1);
}

/**
 * Imprime os quadros para marcar a presenca
 * @param FPDF $oPdf
 * @param stdClass $oGet
 * @param integer $iTamanhoColuna
 * @param integer $iAlturaLinha
 */
function imprimeQuadroPresenca($oPdf, $oGet, $iTamanhoColuna, $iAlturaLinha) {
  
  $iEixoX = $oPdf->getX();
  $iEixoY = $oPdf->getY();
  for($iColuna = 1; $iColuna <= $oGet->iNumeroColunas; $iColuna++) {
  
    $oPdf->setfont('arial','b',12);
    $oPdf->Cell($iTamanhoColuna,  $iAlturaLinha, "", 1, 0, "C");
    $oPdf->Text($iEixoX + ($iTamanhoColuna * 37 / 100), $iEixoY + 2, ".");
    $iEixoX = $oPdf->getX();
  }
}

/**
 * Calcula a necessidade de imprimir linhas em branco 
 * @param integer $iLinhasImpressa Numero de linhas impressa (contador de alunos impressos)
 * @param integer $iLinhasImpressaNaPagina  Numero de linhas impressa na pagina atual
 * @param integer $iNumeroDeAlunosPorPagina Numero de alunos que cabem em uma pagina do relatorio
 * @param integer $iQtdAlunosVinculadosDisciplina Quantos alunos temos vinculados a disciplina que esta sendo impressa
 * @return stdClass
 */
function validaNecessidadeDeImprimirLinhasEmBranco($iLinhasImpressa,
                                                   $iLinhasImpressaNaPagina,
                                                   $iNumeroDeAlunosPorPagina,
                                                   $iQtdAlunosVinculadosDisciplina) {
  
  $oParametroValidacao                                           = new stdClass();
  $oParametroValidacao->iNumeroDeLinhasEmBranco                  = 0;
  $oParametroValidacao->lQuebrouPaginaPorLimiteDeAlunosPorPagina = false;
  $oParametroValidacao->lMenosAlunosNaTurma                      = false;
  
  
  /**
   * Nesse if validamos quando o numero de alunos vinculados a turma eh maior do que o numero de alunos
   * suportados em uma folha do relatorio.
   * Quando este caso for valido teremos no minimo duas paginas de relatorio
   */
  if (($iLinhasImpressa >= $iNumeroDeAlunosPorPagina) && ($iLinhasImpressa == $iQtdAlunosVinculadosDisciplina)) {
  
    $oParametroValidacao->lQuebrouPaginaPorLimiteDeAlunosPorPagina = true ;
    $oParametroValidacao->iNumeroDeLinhasEmBranco = $iNumeroDeAlunosPorPagina - $iLinhasImpressaNaPagina;
  }
  
  /**
   * Nesse if validamos quando o numero de alunos vinculados a turma eh menor do que o numero de alunos
   * suportados em uma folha do relatorio.
   * Quando este caso for valido teremos uma folha com no minimo uma linha impressa sem dados (vazia)
   */
  $lMenosAlunosNaTurma = false;
  if (($iQtdAlunosVinculadosDisciplina == $iLinhasImpressaNaPagina) &&
      ($iLinhasImpressaNaPagina < $iNumeroDeAlunosPorPagina)) {
  
    $oParametroValidacao->lMenosAlunosNaTurma = true;
    $oParametroValidacao->iNumeroDeLinhasEmBranco = $iNumeroDeAlunosPorPagina - $iQtdAlunosVinculadosDisciplina;
  }
  
  return $oParametroValidacao;
}
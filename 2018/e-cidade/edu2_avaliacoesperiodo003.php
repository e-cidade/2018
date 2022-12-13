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

require_once ("fpdf151/scpdf.php");
require_once ("libs/db_sql.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once ("dbforms/db_funcoes.php");
require_once ("std/DBDate.php");
require_once ("model/educacao/avaliacao/iFormaObtencao.interface.php");
require_once ("model/educacao/avaliacao/iElementoAvaliacao.interface.php");
require_once ("model/CgmFactory.model.php");

db_app::import("educacao.*");
db_app::import("educacao.avaliacao.*");
db_app::import("exceptions.*");

$oGet  = db_utils::postMemory($_GET);

$oTurma            = TurmaRepository::getTurmaByCodigo($oGet->iTurma);
$aDisciplinas      = explode(",", $oGet->aDisciplinas);
$aEtapas           = $oTurma->getEtapas();
$oPeriodoAvaliacao = new PeriodoAvaliacao($oGet->iPeriodo);

/**
 * Verifica se a escola possui algum código referência e o adiciona na frente do nome
 */
$sNomeEscola       = $oTurma->getEscola()->getNome();
$iCodigoReferencia = $oTurma->getEscola()->getCodigoReferencia();

if ( $iCodigoReferencia != null ) {
	$sNomeEscola = "{$iCodigoReferencia} - {$sNomeEscola}";
}

/**
 * Variaveis de configuracao do relatorio e dados do cabecalho
 */
$oDadosRelatorio                     = new stdClass();
$oDadosRelatorio->sEscola            = $sNomeEscola;
$oDadosRelatorio->iAnoExecucao       = $oTurma->getCalendario()->getAnoExecucao();
$oDadosRelatorio->sEtapa             = $aEtapas[0]->getEtapa()->getNome();
$oDadosRelatorio->sTurma             = $oTurma->getDescricao();
$oDadosRelatorio->sTurno             = $oTurma->getTurno()->getDescricao();
$oDadosRelatorio->lTrocaTurma        = $oGet->trocaTurma         == 2 ? true : false;
$oDadosRelatorio->lAlunosAtivos      = $oGet->iAlunosAtivos      == 2 ? true : false;
$oDadosRelatorio->lExibirRecuperacao = $oGet->iExibirRecuperacao == 2 ? true : false;
$oDadosRelatorio->iAvaliacoes        = $oGet->iAvaliacoes;
$oDadosRelatorio->iPeriodo           = $oGet->iPeriodo;
$oDadosRelatorio->sPeriodo           = $oPeriodoAvaliacao->getDescricao();

/**
 * Variaveis de configuracao do tamanho das colunas
 */
$oDadosRelatorio->iTamanhoColunaAluno       = 79;
$oDadosRelatorio->iTamanhoColunaAvaliacao   = 11;
$oDadosRelatorio->iTamanhoColunaFinal       = 10;
$oDadosRelatorio->iTamanhoColunaRecuperacao = 60;

$iAreaMaximaAvaliacoes = 120;

if (!$oDadosRelatorio->lExibirRecuperacao) {
	$oDadosRelatorio->iTamanhoColunaAluno += $oDadosRelatorio->iTamanhoColunaRecuperacao;
}

$iRestoNaoUtilizadoPelasAvaliacoes = 120;

for ($i = 1; $i <= $oDadosRelatorio->iAvaliacoes; $i++) {
	
	$iRestoNaoUtilizadoPelasAvaliacoes -= ($oDadosRelatorio->iTamanhoColunaAvaliacao * 2);
}

$oDadosRelatorio->iTamanhoColunaAluno += $iRestoNaoUtilizadoPelasAvaliacoes;  

/**
 * Variaveis controladora da impressao dos alunos	
 */
$iMaximoAlunosPorPagina = 35;
$iNumeroAlunoNaTurma    = count($oTurma->getAlunosMatriculadosNaTurmaPorSerie($aEtapas[0]->getEtapa()));
$iTamanhoNomeAluno      = $oDadosRelatorio->iAvaliacoes == 6 ? 59 : 70;


$oPdf = new scpdf('L');
$oPdf->Open();
$oPdf->SetAutoPageBreak(false);
$oPdf->SetFillColor(230);
$oPdf->SetMargins(8, 10);


$lPrimeiroLaco = true;

/**
 * Filtra as disciplinas selecionadas na tela, criando uma estrutura somente com as regencias da turma 
 */
$aRegenciasSelecionadas = array();

foreach ( $aDisciplinas as $iCodigoDisciplina) {
	
	foreach ($oTurma->getDisciplinasPorEtapa($aEtapas[0]->getEtapa()) as $oRegencia) {
		
		if ($oRegencia->getDisciplina()->getCodigoDisciplinaGeral() == $iCodigoDisciplina) {
			$aRegenciasSelecionadas[] = $oRegencia;
		}
	}
}


foreach ($aRegenciasSelecionadas as $oRegencia) { 
	
	$oRegencia                        = RegenciaRepository::getRegenciaByCodigo($oRegencia->getCodigo());
	$oDadosRelatorio->sNomeDisciplina = $oRegencia->getDisciplina()->getNomeDisciplina();
	$oDadosRelatorio->sNomeProfessor  = '';
	
	if (count($oRegencia->getDocentes()) > 0) {
	
		foreach($oRegencia->getDocentes() as $oDocente) {
	
			$oDadosRelatorio->sNomeProfessor = $oDocente->getNome();
			break;
		}
	}
	
	$iContadorAluno          = 1;
	$iContadorAlunoPorPagina = 1;
	
	/**
	 * Iteramos sobre os alunos da Etapa
	 */
	foreach ($oTurma->getAlunosMatriculadosNaTurmaPorSerie($aEtapas[0]->getEtapa()) as $oMatricula) {
		
		if ($lPrimeiroLaco || $oPdf->gety() > $oPdf->h - 18) {
		
			imprimeCabecalho($oPdf, $oDadosRelatorio);
			$lPrimeiroLaco = false;
		}
		
		if (($oDadosRelatorio->lAlunosAtivos && 
				 ($oMatricula->getSituacao() != "MATRICULADO" && $oMatricula->getSituacao() != "TROCA DE TURMA")) 
				|| (!$oDadosRelatorio->lTrocaTurma && $oMatricula->getSituacao() == "TROCA DE TURMA")) {
			
			continue;
		}
		
		$oPdf->SetFont('arial', '', 7);
		$oPdf->Cell(5, 4, $iContadorAluno, 1, 0);
		$oPdf->SetFont('arial', '', 6);
		
		$oPdf->Cell($oDadosRelatorio->iTamanhoColunaAluno - 5, 4, 
				        substr($oMatricula->getAluno()->getNome(), 0, $iTamanhoNomeAluno), 1, 0);
		
		$oPdf->SetFont('arial', '', 7);
		
		if ( ($oDadosRelatorio->lTrocaTurma && $oMatricula->getSituacao() == "TROCA DE TURMA")
				|| (!$oDadosRelatorio->lAlunosAtivos && $oMatricula->getSituacao() != "MATRICULADO")) {
			
			$iTamanhoColuna = ($oDadosRelatorio->iTamanhoColunaAvaliacao * 2) * $oDadosRelatorio->iAvaliacoes;
			
			$iTamanhoFonte  = 7;
			if ($oDadosRelatorio->iAvaliacoes == 1) {
				$iTamanhoFonte  = 5;
			}
			
			$oPdf->SetFont('arial', 'b', $iTamanhoFonte);
			$oPdf->Cell($iTamanhoColuna,  4, $oMatricula->getSituacao(), 1, 0, "C");
			$oPdf->SetFont('arial', '', 7);
		} else {
			
			for ($i = 1; $i <= $oDadosRelatorio->iAvaliacoes; $i++) {
	
				if ($oDadosRelatorio->lExibirRecuperacao) {
				
					$oPdf->Cell($oDadosRelatorio->iTamanhoColunaAvaliacao,  4, "", 1, 0);
					$oPdf->Cell($oDadosRelatorio->iTamanhoColunaAvaliacao,  4, "", 1, 0);
				} else {
					$oPdf->Cell($oDadosRelatorio->iTamanhoColunaAvaliacao * 2,  4, "", 1, 0);
				}
			}
		}
		
		$oPdf->Cell($oDadosRelatorio->iTamanhoColunaFinal,  4, "", 1, 0);
		$oPdf->Cell($oDadosRelatorio->iTamanhoColunaFinal,  4, "", 1, 0);
		
		if ($oDadosRelatorio->lExibirRecuperacao) {
			$oPdf->Cell($oDadosRelatorio->iTamanhoColunaRecuperacao, 4, "", 1, 0);
		}
		
		$oPdf->Ln();
		
		if ($iMaximoAlunosPorPagina == $iContadorAlunoPorPagina) {
			
			imprimeRodape($oPdf, $oDadosRelatorio);
			$lPrimeiroLaco           = true;
			$iContadorAlunoPorPagina = 1;
		}
		$iContadorAluno ++;
		$iContadorAlunoPorPagina ++;
		
	}
	
	/**
	 * Imprime linhas ate o fim da pagina 
	 */
	for ($iLinha = $iContadorAlunoPorPagina; $iLinha < $iMaximoAlunosPorPagina; $iLinha ++) {
		
		$oPdf->Cell(5, 4, "", 1, 0);
		$oPdf->Cell($oDadosRelatorio->iTamanhoColunaAluno - 5, 4, "", 1, 0);
		
		for ($i = 1; $i <= $oDadosRelatorio->iAvaliacoes; $i++) {
		
			if ($oDadosRelatorio->lExibirRecuperacao) {
				
				$oPdf->Cell($oDadosRelatorio->iTamanhoColunaAvaliacao,  4, "", 1, 0);
				$oPdf->Cell($oDadosRelatorio->iTamanhoColunaAvaliacao,  4, "", 1, 0);
			} else {
				$oPdf->Cell($oDadosRelatorio->iTamanhoColunaAvaliacao * 2,  4, "", 1, 0);
			}
		}
		$oPdf->Cell($oDadosRelatorio->iTamanhoColunaFinal,  4, "", 1, 0);
		$oPdf->Cell($oDadosRelatorio->iTamanhoColunaFinal,  4, "", 1, 0);
		
		if ($oDadosRelatorio->lExibirRecuperacao) {
			$oPdf->Cell($oDadosRelatorio->iTamanhoColunaRecuperacao, 4, "", 1, 0);
		}
		$oPdf->Ln();
	}
	
	imprimeRodape($oPdf, $oDadosRelatorio);
	
	$lPrimeiroLaco  = true;
}




/**
 * Imprime cabecalho do relatório.
 * @param Fpdf $oPdf
 * @param stdClass $oDadosRelatorio dados do cabecalho
 */
function imprimeCabecalho($oPdf, $oDadosRelatorio) {

	$oPdf->AddPage();

	$oPdf->SetFont('arial', 'b', 10);
	
	$oPdf->Cell(290, 4, 'REGISTRO DE AVALIAÇÕES POR PERÍODO', 0, 1, "C");
	$oPdf->Cell(290, 4, $oDadosRelatorio->sEscola, 0, 1, "C");
	$oPdf->Ln();
	$oPdf->SetFont('arial', 'b', 9);
	$oPdf->Cell(20,  4, "Ano Letivo:", 0, 0, "L");
	$oPdf->Cell(40,  4, $oDadosRelatorio->iAnoExecucao, 0, 0, "L");
	$oPdf->Cell(20,  4, "Etapa:", 0, 0, "L");
	$oPdf->Cell(50,  4, $oDadosRelatorio->sEtapa, 0, 0, "L");
	$oPdf->Cell(20,  4, "Turma:", 0, 0, "L");
	$oPdf->Cell(50,  4, $oDadosRelatorio->sTurma, 0, 0, "L");
	$oPdf->Cell(20,  4, "Turno:", 0, 0, "L");
	$oPdf->Cell(30,  4, $oDadosRelatorio->sTurno, 0, 1, "L");
	$oPdf->Cell(20,  4, "Disciplina:", 0, 0, "L");
	$oPdf->Cell(165, 4, $oDadosRelatorio->sNomeDisciplina, 0, 0, "L");
	$oPdf->Cell(20,  4, "Professor:", 0, 0, "L");
	$oPdf->Cell(30,  4, $oDadosRelatorio->sNomeProfessor, 0, 0, "L");

	$oPdf->Ln();
	$oPdf->Ln();
	$oPdf->Cell(277, 4, $oDadosRelatorio->sPeriodo, 0, 1, "C");
	
	$iXinicial = $oPdf->GetX();
	$iYinicial = $oPdf->GetY();
	
	$oPdf->Rect($iXinicial, $iYinicial, $oDadosRelatorio->iTamanhoColunaAluno, 15);
	$oPdf->Cell($oDadosRelatorio->iTamanhoColunaAluno, 15, "Nome do Aluno", 1, 0, "C");
	
	$oPdf->SetFont('arial', 'b', 7);
	$iPosicaoX = $oPdf->GetX();
	
	for ($i = 1; $i <= $oDadosRelatorio->iAvaliacoes; $i++) {
		
		$oPdf->SetX($iPosicaoX);
		$oPdf->Cell($oDadosRelatorio->iTamanhoColunaAvaliacao * 2, 5, $i, 1,  1, "C");
		$oPdf->SetX($iPosicaoX);
		
		if ($oDadosRelatorio->lExibirRecuperacao) {
			
			$oPdf->Cell($oDadosRelatorio->iTamanhoColunaAvaliacao,   5, "Aval", 1, 0, "C");
			$oPdf->Cell($oDadosRelatorio->iTamanhoColunaAvaliacao,   5, "Rec",  1, 1, "C");
		} else {
			$oPdf->Cell($oDadosRelatorio->iTamanhoColunaAvaliacao * 2, 5, "Aval", 1, 1, "C");
		}
		
		$oPdf->SetX($iPosicaoX);
		$oPdf->Cell($oDadosRelatorio->iTamanhoColunaAvaliacao * 2, 5, "Peso:", 1, 0, "L");
		$iPosicaoX = $oPdf->GetX();
		$oPdf->SetY($iYinicial - 5);
		$oPdf->Ln();
	}
	$oPdf->SetX($iPosicaoX);
	$oPdf->VCell($oDadosRelatorio->iTamanhoColunaFinal, 15, "Nota Final", 1, 0, "C");
	$oPdf->VCell($oDadosRelatorio->iTamanhoColunaFinal, 15, "Faltas",     1, 0, "C");
	
	
	if ($oDadosRelatorio->lExibirRecuperacao) {
		$oPdf->Cell($oDadosRelatorio->iTamanhoColunaRecuperacao, 15, "Estudos de Recuperção", 1, 0, "C");
	}
	$oPdf->Ln();
}

/**
 * 
 * @param FPDF $oPdf
 * @param unknown $oDadosRelatorio
 */
function imprimeRodape($oPdf, $oDadosRelatorio) {
	
	$oPdf->SetFont('arial', 'b', 6);
	$oPdf->Cell(12,   3, "Legenda:", 0, 0);
	$oPdf->Cell(150,  3, "Aval = Avaliação;   Rec = Recuperação; ", 0, 1);
	$oPdf->SetFont('arial', '', 7);
	$oPdf->Ln();
	$oPdf->Cell(70,  3, "", 0,0);
	$oPdf->Cell(50,  3, "Data: ____/____/_______", 0, 0);
	$oPdf->Cell(100, 3, "_______________________________________________________", 0, 1);
	$oPdf->SetX(120);
	$oPdf->Cell(100, 4, "Assinatura do Professor", 0, 0, "C");
} 


$oPdf->Output();
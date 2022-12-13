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

require_once ("fpdf151/pdf.php");
require_once ("std/DBDate.php");
require_once ("libs/db_sql.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/JSON.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/exceptions/BusinessException.php");
require_once ("libs/exceptions/ParameterException.php");
require_once ("libs/exceptions/DBException.php");

/*
 relatorio dos cursos sociais
filtros
- iMinistrante
- iCurso
- iTotalAulas
- iModelo = 1 Por Mês
            2 Total
- sMeses  = [{iAno:2013, iMes:1}, {iAno:2013, iMes:1}, {iAno:2013, iMes:3} ] 
relatório
- quebra página por mês quando iModelo = 1 
- quando iModelo = 2, imprime todos os meses selecionados em uma página. Só que neste caso se curso ter muitos dias de
-   aula, não controlamos a página 
*/


$oGet  = db_utils::postMemory($_GET);
$oJson = new Services_JSON();

$oFiltros                     = new stdClass();
$oFiltros->iMinistrante       = $oGet->iMinistrante;
$oFiltros->iCurso             = $oGet->iCurso;
$oFiltros->iModelo            = $oGet->iModelo;
$oFiltros->iTotalAulas        = $oGet->iTotalAulas;
$oFiltros->aMeses             = $oJson->decode(str_replace("\\", "", $oGet->aMeses));
$oFiltros->lImprimeAssinatura = $oGet->lAssinatura == 'S' ? true : false;


$oConfigRelatorio = new stdClass();
$oConfigRelatorio->iAlturaLinha            = 4;
$oConfigRelatorio->iAlunosPorPagina        = 35;
$oConfigRelatorio->iColunaNome             = 80; // Largura da coluna
$oConfigRelatorio->iLarguraTotalPeriodo    = 280 - $oConfigRelatorio->iColunaNome;
$oConfigRelatorio->iLarguraMinimaCellFalta = 2.8;
$oConfigRelatorio->iNumeroMaximoCellFalta  = 60;

/**
 * fim dados mocados
 */


$aPaginas     = array();
$oCursosSocial = CursoSocialRepository::getCursoSocialByCodigo($oFiltros->iCurso);

/**
 * Monta o array de páginas, definindo quais meses sairá por página 
 */
foreach ($oCursosSocial->getMesesDeAbrangencia() as $iAno => $aMesCurso) {

  foreach ($oFiltros->aMeses as $oMesFiltro) {
    
    if ($iAno == $oMesFiltro->iAno && array_key_exists($oMesFiltro->iMes, $aMesCurso) ) {

      if ($oFiltros->iModelo == 1) {
        $aPaginas[][] = $oMesFiltro;
      } else {
        $aPaginas[1][] = $oMesFiltro;
      }
    }
  } 
}

/**
 * Se for modelo 2, devemos validar se os meses selecionados poderam serem impressos na página
 */
if ($oFiltros->iModelo == 2) {
  validaTamanhoColunas($oCursosSocial, $oConfigRelatorio, $aPaginas);
}

/**
 * Realiza o calculo do tamanho de cada celula para lançamento de presença.
 */
calculaTamanhoColunasFrequencia($oCursosSocial, $oConfigRelatorio, $aPaginas);


$oPdf = new PDF("L");
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(true);
$oPdf->SetFillColor(215);
$oPdf->SetMargins(10, 10);

$head1 = "Lista de Presença";
$head2 = "Curso: {$oCursosSocial->getNome()}";
$head3 = "Ministrante: {$oCursosSocial->getMinistrante()->getNome()}";
$head4 = "Horas Aula: {$oCursosSocial->getNumeroDeHorasAula()}h";;

/**
 * Começa impressão
 */
$lPrimeiraPagina = true;
foreach ($aPaginas as $iPagina => $aMesPagina) {

  $iAlunosImpressos = 0;
  foreach ($oCursosSocial->getCidadaosMatriculados() as $oMatricula) {

    $iAlunosImpressos ++;
    if ($lPrimeiraPagina || $oPdf->gety() > $oPdf->h - 20) {

      adicionaHeader($oPdf, $oCursosSocial, $oConfigRelatorio, $aPaginas[$iPagina], $iPagina);
      $lPrimeiraPagina = false;
    }

    $oPdf->SetFont("arial", '', 7);

    $sNomeAluno = $oMatricula->getCidadao()->getNome();
    $oPdf->Cell($oConfigRelatorio->iColunaNome, $oConfigRelatorio->iAlturaLinha, $sNomeAluno, 1, 0, "L");

    /**
     * Imprime os as celulas de presença para o mes e dia
     */
    foreach ($aMesPagina as $oMes) {

      $iColunaDia = $oConfigRelatorio->aTamanhoColunas[$iPagina][$oMes->iMes]->iColunaDia;

      foreach ($oCursosSocial->getDiasDeAulaPorMesAno($oMes->iMes, $oMes->iAno) as $oDiaAula) {

        $sPresenca = ".";

        if( array_key_exists( $oDiaAula->iCodigo, $oMatricula->getAusencias() ) ) {
          $sPresenca = "F";
        }

        imprimePontoPresenca($oPdf, $oConfigRelatorio, $iColunaDia, $sPresenca);
      }
    }
    $oPdf->ln();
    
    if ( $iAlunosImpressos >= $oConfigRelatorio->iAlunosPorPagina) {
      
      $iAlunosImpressos = 0;
      if ($oFiltros->lImprimeAssinatura) {
        imprimeAssinatura($oPdf, $oCursosSocial, $oConfigRelatorio);
      }
      adicionaHeader($oPdf, $oCursosSocial, $oConfigRelatorio, $aPaginas[$iPagina], $iPagina);
    }
  }
  
  /**
   * Verifica se necessita imprimir linhas em branco
   */
  if ($iAlunosImpressos < $oConfigRelatorio->iAlunosPorPagina) {
    
    $iLinhasEmBranco = $oConfigRelatorio->iAlunosPorPagina - $iAlunosImpressos;
    for ($i = 0; $i < $iLinhasEmBranco; $i++) {
      
      $oPdf->Cell($oConfigRelatorio->iColunaNome, $oConfigRelatorio->iAlturaLinha, "", 1, 0, "L");
      foreach ($aMesPagina as $oMes) {
        
        $iColunaDia = $oConfigRelatorio->aTamanhoColunas[$iPagina][$oMes->iMes]->iColunaDia;
        
        foreach ($oCursosSocial->getDiasDeAulaPorMesAno($oMes->iMes, $oMes->iAno) as $oDiaAula) {

          $sPresenca = ".";
          imprimePontoPresenca($oPdf, $oConfigRelatorio, $iColunaDia, $sPresenca);
        }
      }
      $oPdf->ln();
    }
    if ($oFiltros->lImprimeAssinatura) {
      imprimeAssinatura($oPdf, $oCursosSocial, $oConfigRelatorio);
    }  
  }
  
  $lPrimeiraPagina = true;
}



function imprimeAssinatura(FPDF $oPdf, CursoSocial $oCursosSocial, $oConfigRelatorio) {
  
  $oPdf->SetFont("arial", "", 8);
  $sMinistrante = $oCursosSocial->getMinistrante()->getNome();
  $oPdf->Ln(2);
  $oPdf->Cell(180, $oConfigRelatorio->iAlturaLinha, '', 0, 0);
  $oPdf->Cell(80, $oConfigRelatorio->iAlturaLinha, $sMinistrante, 0, 0, "C");
  $oPdf->Ln(6);
  $oPdf->Cell(180, $oConfigRelatorio->iAlturaLinha, '', 0, 0);
  $oPdf->Cell(80, $oConfigRelatorio->iAlturaLinha, '', "B", 0, "C");
}


/**
 * Calcula a posição para impressão do ponto de presença.
 * Posição calculada com base na posição do eixo X, Y e a altura e largura da celula 
 * @param FPDF     $oPdf
 * @param stdClass $oConfigRelatorio
 */
function imprimePontoPresenca (FPDF $oPdf, $oConfigRelatorio, $iColunaDia, $sPresenca) {

  $iYFinal  = $sPresenca == "." ? 0.5 : 1.2;
  $iXInicio = $oPdf->GetX();
  $iYInicio = $oPdf->GetY();

  $oPdf->Rect($oPdf->GetX(), $oPdf->GetY(), $iColunaDia, $oConfigRelatorio->iAlturaLinha);
  $oPdf->SetFont("arial", "b", 9);
  $oPdf->Text(($iXInicio + ($iColunaDia / 2) - 0.5 ), ($iYInicio + ($oConfigRelatorio->iAlturaLinha / 2) + $iYFinal), $sPresenca);
  $oPdf->SetXY(($iXInicio + $iColunaDia), $iYInicio);
}

/**
 * Adiciona o cabeçalho 
 * @param FPDF $oPdf
 * @param CursoSocial $oCursosSocial
 * @param stdClass    $oConfigRelatorio
 * @param array       $aMesesPagina
 * @param integer     $iPagina
 */
function adicionaHeader(FPDF $oPdf, CursoSocial $oCursosSocial, $oConfigRelatorio, $aMesesPagina, $iPagina) {

  $oPdf->AddPage();
  $oPdf->SetFont("arial", "b", 7);

  $oPdf->Cell($oConfigRelatorio->iColunaNome - 10, $oConfigRelatorio->iAlturaLinha, "", 1, 0, "C");
  $oPdf->Cell(10, $oConfigRelatorio->iAlturaLinha, "Mês", 1, 0, "C");

  foreach ($aMesesPagina as $oMes) {
    
    $iColunaMes = $oConfigRelatorio->aTamanhoColunas[$iPagina][$oMes->iMes]->iColunaMes; 
    $sMesAbrev  = db_mesAbreviado(str_pad($oMes->iMes, 2, "0", STR_PAD_LEFT));
    $oPdf->Cell($iColunaMes, $oConfigRelatorio->iAlturaLinha, "{$sMesAbrev}/{$oMes->iAno}", 1, 0, "C");
  }
  $oPdf->Ln();

  $oPdf->Cell($oConfigRelatorio->iColunaNome - 10, $oConfigRelatorio->iAlturaLinha, "Aluno", 1, 0, "C");
  $oPdf->Cell(10, $oConfigRelatorio->iAlturaLinha, "Dia", 1, 0, "C");

  foreach ($aMesesPagina as $oMes) {

    $iColunaDia      = $oConfigRelatorio->aTamanhoColunas[$iPagina][$oMes->iMes]->iColunaDia;

    $oPdf->SetFont("arial", "b", 6);
    foreach ($oCursosSocial->getDiasDeAulaPorMesAno($oMes->iMes, $oMes->iAno) as $oDiaAula) {
      $oPdf->Cell($iColunaDia, $oConfigRelatorio->iAlturaLinha, $oDiaAula->oDataAula->getDia(), 1, 0, "C");
    }
  }
  $oPdf->Ln();
}


/**
 * Calcula de acordo o tamanho das colunas dos meses e dias de aula de acordo com o número de meses impresso por página
 * Criei esta função para não ter que repetir calculo cada vez que que é necessário.
 * @param CursoSocial $oCursosSocial
 * @param unknown $oConfigRelatorio
 * @param unknown $aPaginas
 */
function calculaTamanhoColunasFrequencia (CursoSocial $oCursosSocial, $oConfigRelatorio, $aPaginas) {
  
  $aTamanhoColunas = array();

  foreach ($aPaginas as $iPagina => $aMes) {
    
    $iNumeroTotalAulasMesSelecionado = 0;
    
    $aMeses                  = array();
    foreach ($aMes as $oMes) {

      $iNumeroAulasMes                  = count($oCursosSocial->getDiasDeAulaPorMesAno($oMes->iMes, $oMes->iAno));
      $iNumeroTotalAulasMesSelecionado += $iNumeroAulasMes;
    }
    foreach ($aMes as $oMes) {

      $iNumeroAulasMes                  = count($oCursosSocial->getDiasDeAulaPorMesAno($oMes->iMes, $oMes->iAno));

      $oTamanhoMes                  = new stdClass();
      $oTamanhoMes->iColunaDia      = $oConfigRelatorio->iLarguraTotalPeriodo / $iNumeroTotalAulasMesSelecionado;
      $oTamanhoMes->iColunaMes      = $oTamanhoMes->iColunaDia * $iNumeroAulasMes;
      $oTamanhoMes->iNumeroAulasMes = $iNumeroAulasMes;
      $aMeses[$oMes->iMes]          = $oTamanhoMes;
       
    }
    $aTamanhoColunas[$iPagina] = $aMeses;
  }
  $oConfigRelatorio->aTamanhoColunas = $aTamanhoColunas;
  
}


/**
 * Somente quando modelo = 2, validamos se todos os meses selecionados podem serem impressos na página
 * @param CursoSocial $oCursosSocial
 * @param stdClass    $oConfigRelatorio
 * @param array       $aPaginas
 */
function validaTamanhoColunas(CursoSocial $oCursosSocial, $oConfigRelatorio, $aPaginas) {

  $iColunaMes = $oConfigRelatorio->iLarguraTotalPeriodo / count($aPaginas[1]);

  $iLarguraTotalDasCounasDias = 0;

  $lErro = false;

  $sMsgErro  = 'Este modelo não pode ser impresso para o número de meses selecionados.<br>';
  $sMsgErro .= 'Altere o filtro modelo para imprimir "Por mês" ou selecione menos meses e emita o relatório novamente.';

  foreach ($aPaginas[1] as $oMes) {

    $iNumeroAulasMes = count($oCursosSocial->getDiasDeAulaPorMesAno($oMes->iMes, $oMes->iAno));
    $iColunaDia      = $iColunaMes / $iNumeroAulasMes;

    if ($iColunaDia < $oConfigRelatorio->iLarguraMinimaCellFalta) {
      $lErro = true;
      break;
    }

    $iLarguraTotalDasCounasDias += $iColunaDia;
  }

  if ($lErro || $iLarguraTotalDasCounasDias > $oConfigRelatorio->iLarguraTotalPeriodo) {
    db_redireciona("db_erros.php?fechar=true&db_erro={$sMsgErro}.");
  }
}


$oPdf->Output();
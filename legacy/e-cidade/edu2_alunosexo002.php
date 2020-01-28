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

require_once("libs/db_utils.php");
require_once("fpdf151/pdfwebseller.php");

$oGet = db_utils::postMemory($_GET);

/**
 * Parametros para configuracao do relatório
 */
$oParametros                    = new stdClass();
$oParametros->iEscola           = $oGet->iEscola;
$oParametros->sListaCalendarios = $oGet->aCalendarios;
$oParametros->iEtapa            = $oGet->iEtapa;
$oParametros->iAno              = $oGet->iAno;

/**
 * Altura padrão das linhas
 */
$oParametros->iAlturaLinha = 4;

/**
 * Força escrever o cabeçalho
 */
$oParametros->lEscreverCabecalho = true;

/**
 * Fonte padrao
 */
$oParametros->sFonte  = 'Arial';

$iTotalMasculinoEscola = 0;
$iTotalFemininoEscola  = 0;

$aFiltros    = array();
$sNomeEscola = "TODAS";
$sNomeEtapa  = "TODAS";

if (!empty($oParametros->iEscola)) {

  $aFiltros[]  = " ed57_i_escola = {$oParametros->iEscola}";
  $oEscola     = EscolaRepository::getEscolaByCodigo($oParametros->iEscola);
  $sNomeEscola = $oEscola->getNome();
}

if (!empty($oParametros->sListaCalendarios)) {
  $aFiltros[] = " ed57_i_calendario in({$oParametros->sListaCalendarios})";
}

if (!empty($oParametros->iEtapa)) {

  $aFiltros[] = "ed11_i_codigo = {$oParametros->iEtapa}";
  $oEtapa     = EtapaRepository::getEtapaByCodigo($oParametros->iEtapa);
  $sNomeEtapa = $oEtapa->getNome()." - ". $oEtapa->getEnsino()->getNome();
}

$aFiltros[] = "ed60_c_situacao = 'MATRICULADO' ";
$aFiltros[] = "ed221_c_origem  = 'S'           ";
$sWhere     = implode(' and ', $aFiltros);

$sCampos    = " DISTINCT ed18_i_codigo, trim(escola.ed18_c_nome) as escola, trim(ed11_c_descr) as etapa,";
$sCampos   .= " ed11_i_ensino, ed11_i_sequencia, ed11_i_codigo, ed57_i_calendario, ed52_c_descr as calendario, ";
$sCampos   .= " ed57_i_codigo, trim(ed57_c_descr) as turma, ";
$sCampos   .= " (SELECT count(ed47_v_sexo)                                               ";
$sCampos   .= "    FROM aluno                                                            ";
$sCampos   .= "   inner join matricula m       on m.ed60_i_aluno = aluno.ed47_i_codigo   ";
$sCampos   .= "                               and m.ed60_i_turma = turma.ed57_i_codigo   ";
$sCampos   .= "   inner join matriculaserie ms on ms.ed221_i_matricula = m.ed60_i_codigo ";
$sCampos   .= "                               and ms.ed221_i_serie     = ed11_i_codigo   ";
$sCampos   .= "   WHERE ed47_v_sexo     = 'M'                                            ";
$sCampos   .= "     and ed221_c_origem  = 'S'                                            ";
$sCampos   .= "     and ed60_c_situacao = 'MATRICULADO'                                  ";
$sCampos   .= " ) as masculino,                                                          ";
$sCampos   .= " (SELECT count(ed47_v_sexo)                                               ";
$sCampos   .= "    FROM aluno                                                            ";
$sCampos   .= "   inner join matricula m       on m.ed60_i_aluno = aluno.ed47_i_codigo   ";
$sCampos   .= "                               and m.ed60_i_turma = turma.ed57_i_codigo   ";
$sCampos   .= "   inner join matriculaserie ms on ms.ed221_i_matricula = m.ed60_i_codigo ";
$sCampos   .= "                               and ms.ed221_i_serie     = ed11_i_codigo   ";
$sCampos   .= "   WHERE ed47_v_sexo     = 'F'                                            ";
$sCampos   .= "     and ed221_c_origem  = 'S'                                            ";
$sCampos   .= "     and ed60_c_situacao = 'MATRICULADO'                                  ";
$sCampos   .= " ) as feminino                                                            ";
$sOrdem     = "ed11_i_ensino, ed11_i_sequencia, ed57_i_codigo                            ";

$oDaoMatricula = new cl_matricula();
$sSqlMatricula = $oDaoMatricula->sql_query_bolsafamilia(null, $sCampos, $sOrdem, $sWhere);
$rsMatricula   = $oDaoMatricula->sql_record($sSqlMatricula);
$iLinhas       = $oDaoMatricula->numrows;

if ($iLinhas == 0) {
  db_redireciona("db_erros.php?fechar=true&db_erro=" . _M("educacao.escola.edu2_alunosexo.nenhum_registro_encontrado"));
}

/**
 * Realizamos o agrupamento dos dados
 */
$aAlunos = array();
for ($i = 0; $i < $iLinhas; $i++) {

  $oDados = db_utils::fieldsMemory($rsMatricula, $i);

  //IndexUnicoArray
  $iIndexEscola = $oDados->ed18_i_codigo;
  $iIndexEtapa  = "{$oDados->ed11_i_ensino}#{$oDados->ed11_i_sequencia}";

  if (!array_key_exists($iIndexEscola, $aAlunos)) {

    $oEscola             = new stdClass();
    $oEscola->sEscola    = $oDados->escola;
    $oEscola->iCodigo    = $oDados->ed18_i_codigo;
    $oEscola->iTotal     = 0;
    $oEscola->iMasculino = 0;
    $oEscola->iFeminino  = 0;
    $oEscola->aEtapas    = array();

    $aAlunos[$iIndexEscola] = $oEscola;
  }

  if (!array_key_exists($iIndexEtapa, $aAlunos[$iIndexEscola]->aEtapas)) {

    $oEtapa             = new stdClass();
    $oEtapa->sEtapa     = $oDados->etapa;
    $oEtapa->iTotal     = 0;
    $oEtapa->iMasculino = 0;
    $oEtapa->iFeminino  = 0;
    $oEtapa->aDados     = array();

    $aAlunos[$iIndexEscola]->aEtapas[$iIndexEtapa] = $oEtapa;
  }

  $oTurma             = new stdClass();
  $oTurma->sTurma     = $oDados->turma;
  $oTurma->iMasculino = $oDados->masculino;
  $oTurma->iFeminino  = $oDados->feminino;
  $oTurma->iTotal     = $oDados->masculino + $oDados->feminino;

  $aAlunos[$iIndexEscola]->aEtapas[$iIndexEtapa]->aDados[] = $oTurma;

  $aAlunos[$iIndexEscola]->aEtapas[$iIndexEtapa]->iTotal     += $oTurma->iTotal;
  $aAlunos[$iIndexEscola]->aEtapas[$iIndexEtapa]->iMasculino += $oTurma->iMasculino;
  $aAlunos[$iIndexEscola]->aEtapas[$iIndexEtapa]->iFeminino  += $oTurma->iFeminino;

  $aAlunos[$iIndexEscola]->iTotal     += $aAlunos[$iIndexEscola]->aEtapas[$iIndexEtapa]->iTotal;
  $aAlunos[$iIndexEscola]->iMasculino += $aAlunos[$iIndexEscola]->aEtapas[$iIndexEtapa]->iMasculino;
  $aAlunos[$iIndexEscola]->iFeminino  += $aAlunos[$iIndexEscola]->aEtapas[$iIndexEtapa]->iFeminino;
}


$oPdf = new PDF('P');
$oPdf->AliasNbPages();
$oPdf->Open();
$oPdf->SetAutoPageBreak(false, 20);

$head1 = "RELATÓRIO DE ALUNOS POR SEXO";
$head2 = "Escola: {$sNomeEscola}";
$head3 = "Etapa: {$sNomeEtapa}";
$head4 = "Ano: {$oParametros->iAno}";
$oPdf->SetFillColor(210);

$oTotalGeralEscolas = new stdClass();
$oTotalGeralEscolas->iMasculino = 0;
$oTotalGeralEscolas->iFeminino  = 0;
$oTotalGeralEscolas->aEtapas    = 0;

foreach ($aAlunos as $oEscola) {

  $iTotalMasculinoEscola = 0;
  $iTotalFemininoEscola  = 0;

  foreach ($oEscola->aEtapas as $oEtapa) {

    if (!$oParametros->lEscreverCabecalho && ($oPdf->GetY() + $oParametros->iAlturaLinha) <= ($oPdf->h -20)) {
      setHeader($oPdf, $oParametros, $oEscola->sEscola, $oEtapa->sEtapa, false);
    }

    foreach ($oEtapa->aDados as $oTurma) {

      if ($oParametros->lEscreverCabecalho || ($oPdf->GetY() + $oParametros->iAlturaLinha) >= ($oPdf->h - 20) ) {

        $lNovaPagina = $oParametros->lEscreverCabecalho || ($oPdf->GetY() + $oParametros->iAlturaLinha) >= $oPdf->h - 20;
        setHeader($oPdf, $oParametros, $oEscola->sEscola, $oEtapa->sEtapa, $lNovaPagina);
        $oParametros->lEscreverCabecalho = false;
      }

      $oPdf->SetFont($oParametros->sFonte, '', '8');
      $oPdf->Cell(60, $oParametros->iAlturaLinha, $oTurma->sTurma,     "TBR", 0, "L");
      $oPdf->Cell(40, $oParametros->iAlturaLinha, $oTurma->iMasculino,     1, 0, "R");
      $oPdf->Cell(40, $oParametros->iAlturaLinha, $oTurma->iFeminino,      1, 0, "R");
      $oPdf->Cell(52, $oParametros->iAlturaLinha, $oTurma->iTotal,     "TBL", 1, "R");

      $iTotalMasculinoEscola += $oTurma->iMasculino;
      $iTotalFemininoEscola  += $oTurma->iFeminino;
    }

    $oPdf->ln(0.3);

    if (($oPdf->GetY() + ($oParametros->iAlturaLinha * 2)) > ($oPdf->h - 25)) {
      setHeader($oPdf, $oParametros, $oEscola->sEscola, $oEtapa->sEtapa, true);
    }

    $oPdf->SetFont($oParametros->sFonte, 'B', '8');
    $oPdf->SetFillColor(240);
    $sMsg = "Total da Etapa {$oEtapa->sEtapa}";
    $oPdf->Cell(60, $oParametros->iAlturaLinha, $sMsg,               0, 0, "L", 1);
    $oPdf->Cell(40, $oParametros->iAlturaLinha, $oEtapa->iMasculino, 0, 0, "R", 1);
    $oPdf->Cell(40, $oParametros->iAlturaLinha, $oEtapa->iFeminino,  0, 0, "R", 1);
    $oPdf->Cell(52, $oParametros->iAlturaLinha, $oEtapa->iTotal,     0, 1, "R", 1);
    imprimePercentual($oPdf, $oParametros, $oEtapa);
    $oPdf->ln(0.3);
    $oPdf->Line(10, $oPdf->GetY(), 202, $oPdf->GetY());
  }

  $oParametros->lEscreverCabecalho = true;

  $oPdf->ln(0.3);

  if (($oPdf->GetY() + ($oParametros->iAlturaLinha * 2)) > ($oPdf->h - 15)) {
    setHeader($oPdf, $oParametros, $oEscola->sEscola, $oEtapa->sEtapa, true);
  }
  $oPdf->SetFont($oParametros->sFonte, 'B', '8');
  $oPdf->SetFillColor(240);
  $sMsg = "Total da Escola ";

  $oEscola->iMasculino = $iTotalMasculinoEscola;
  $oEscola->iFeminino  = $iTotalFemininoEscola;
  $oEscola->iTotal     = $iTotalMasculinoEscola + $iTotalFemininoEscola;

  $oPdf->Cell(60, $oParametros->iAlturaLinha, $sMsg,                0, 0, "L", 1);
  $oPdf->Cell(40, $oParametros->iAlturaLinha, $oEscola->iMasculino, 0, 0, "R", 1);
  $oPdf->Cell(40, $oParametros->iAlturaLinha, $oEscola->iFeminino,  0, 0, "R", 1);
  $oPdf->Cell(52, $oParametros->iAlturaLinha, $oEscola->iTotal,     0, 1, "R", 1);
  imprimePercentual($oPdf, $oParametros, $oEscola);

  $oTotalGeralEscolas->iMasculino += $oEscola->iMasculino;
  $oTotalGeralEscolas->iFeminino  += $oEscola->iFeminino;
  $oTotalGeralEscolas->iTotal     += $oEscola->iTotal;
}

/** *************************** *
 *      QUADRO DO TOTAL GERAL   *
 ** *************************** */
if (count($aAlunos) > 1) {

  $oPdf->ln(8);
  if ($oPdf->GetY() >= $oPdf->h -35) {
    $oPdf->AddPage();
  }

  $oPdf->Rect(9.8, $oPdf->GetY()-0.3, 192.5, $oParametros->iAlturaLinha * 3.1);

  $oPdf->SetFont($oParametros->sFonte, 'B', '8');
  $oPdf->Cell(60, $oParametros->iAlturaLinha, "Total Geral",     "BR", 0, "C", 1);
  $oPdf->Cell(40, $oParametros->iAlturaLinha, "Masculino",       "BRL", 0, "C", 1);
  $oPdf->Cell(40, $oParametros->iAlturaLinha, "Feminino",        "BRL", 0, "C", 1);
  $oPdf->Cell(52, $oParametros->iAlturaLinha, "Total de Alunos", "BL", 1, "C", 1);

  $oPdf->SetFillColor(240);
  $oPdf->Cell(60, $oParametros->iAlturaLinha, "Quantidade",                    0, 0, "L", 1);
  $oPdf->Cell(40, $oParametros->iAlturaLinha, $oTotalGeralEscolas->iMasculino, 0, 0, "R", 1);
  $oPdf->Cell(40, $oParametros->iAlturaLinha, $oTotalGeralEscolas->iFeminino,  0, 0, "R", 1);
  $oPdf->Cell(52, $oParametros->iAlturaLinha, $oTotalGeralEscolas->iTotal,     0, 1, "R", 1);

  imprimePercentual($oPdf, $oParametros, $oTotalGeralEscolas);
}

/**
 * Escreve o cabeçalho
 * @param FPDF     $oPdf           instancia de FPDF
 * @param stdClass $oParametros    Configurações do relatório
 * @param string   $sEscola        Nome da Escola
 * @param string   $sEtapa         Nome da Etapa
 * @param boolean  $lNovaPagina    Se devemos quebrar a página
 * @return void
 */
function setHeader(FPDF $oPdf, $oParametros, $sEscola, $sEtapa, $lNovaPagina) {

  $oPdf->SetFillColor(210);
  $oPdf->SetFont($oParametros->sFonte, 'B', '9');
  if ($lNovaPagina) {

    $oPdf->AddPage();
    $oPdf->Cell(192, $oParametros->iAlturaLinha, $sEscola, 1, 1, "L", 1);
  }

  $oPdf->Cell(60, $oParametros->iAlturaLinha, $sEtapa,           1, 0, "C", 1);
  $oPdf->Cell(40, $oParametros->iAlturaLinha, "Masculino",       1, 0, "C", 1);
  $oPdf->Cell(40, $oParametros->iAlturaLinha, "Feminino",        1, 0, "C", 1);
  $oPdf->Cell(52, $oParametros->iAlturaLinha, "Total de Alunos", 1, 1, "C", 1);

}

/**
 * Realiza o calculo percentual dos dados
 * @param FPDF     $oPdf        instancia de FPDF
 * @param stdClass $oParametros Configurações do relatório
 * @param stdClass $oDados      Objeto com os valores para calculo
 * @return void
 */
function imprimePercentual(FPDF $oPdf, $oParametros, $oDados) {

  $nMasculino = round(($oDados->iMasculino * 100) / $oDados->iTotal, 2);
  $nFeminino  = round(($oDados->iFeminino * 100) / $oDados->iTotal, 2);

  /**
   * Se houver diferença no calculo, jogamos a diferença no percentual masculino
   */
  if (($nMasculino + $nFeminino) != 100) {
    $nMasculino += ($nMasculino - $nFeminino);
  }
  $nMasculino = number_format($nMasculino, 2, ",", ".");
  $nFeminino  = number_format($nFeminino, 2, ",", ".");

  $oPdf->Cell(60, $oParametros->iAlturaLinha, "Percentuais",    0, 0, "L", 1);
  $oPdf->Cell(40, $oParametros->iAlturaLinha, "{$nMasculino}%", 0, 0, "R", 1);
  $oPdf->Cell(40, $oParametros->iAlturaLinha, "{$nFeminino}%",  0, 0, "R", 1);
  $oPdf->Cell(52, $oParametros->iAlturaLinha, "100%",           0, 1, "R", 1);
}

$oPdf->Output();
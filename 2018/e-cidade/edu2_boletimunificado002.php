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
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_stdlibwebseller.php");
require_once ("dbforms/db_funcoes.php");
require_once ("std/DBDate.php");

$oGet       = db_utils::postMemory($_GET);
$aRegencias = array();

if (isset($oGet->disciplinas)) {
  $aRegencias = explode(",", $oGet->disciplinas);
}
$aMatricula = explode(",", $oGet->alunos);

/**
 * Busca os dados informacoes da turma selecionada
 */
$oDaoTurma  = db_utils::getDao('turma');
$sWhere     = "ed220_i_codigo = {$oGet->turma}";
$sCampos    = " trim(ed18_c_nome) as escola, ed18_i_codigo, ed52_i_codigo, trim(ed52_c_descr) as calendario ";
$sCampos   .= ", ed52_i_ano as anocalendario, trim(ed57_c_descr) as turma, ed15_c_nome as turno, ed18_codigoreferencia ";
$sSqlTurma  = $oDaoTurma->sql_query_turmaserie(null, $sCampos, null, $sWhere);
$rsTurma    = $oDaoTurma->sql_record($sSqlTurma);

if ($oDaoTurma->numrows == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não foi encontrado nehum regitro da turma selecionada.');
}

$oDadosTurma    = db_utils::fieldsMemory($rsTurma, 0);

/**
 * Valida se escola possiu Código Referência e o adiciona antes do nome.
 */
$sNomeEscola       = $oDadosTurma->escola;
$iCodigoReferencia = $oDadosTurma->ed18_codigoreferencia;

if ( $iCodigoReferencia != null ) {
  $sNomeEscola = "{$iCodigoReferencia} - {$sNomeEscola}";
}

/**
 * Buscamos o logo da instituicao
 */
$oDaoDepart = db_utils::getDao('db_depart');
$sSqlDepart = $oDaoDepart->sql_query_dados_depart($oDadosTurma->ed18_i_codigo, "db_config.logo");
$rsDepart   = $oDaoDepart->sql_record($sSqlDepart);
$sLogo      = "";
if ($oDaoDepart->numrows > 0) {
  $sLogo = "imagens/files/".db_utils::fieldsMemory($rsDepart, 0)->logo;
}

$iHeigth          = 4;
$iContador        = 1;
$iTotalMatriculas = count($aMatricula);

$oPdf = new scpdf();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);
$oPdf->SetMargins(7, 10);
$oPdf->AddPage();

/**
 * Iteramos pelos alunos selecionados
 */
foreach ($aMatricula as $iMatricula) {

  $oMatricula             = new Matricula($iMatricula);
  $oGradeAproveitamento   = new GradeAproveitamentoAluno($oMatricula);
  $oFormaAvaliacao        = $oGradeAproveitamento->getProcedimentoAvaliacao()->getFormaAvaliacao();
  $aGradeAluno            = $oGradeAproveitamento->getGradeAproveitamento();
  $iAlturaInicial         = 10;
  $sFormaAvaliacao        = "";
  $sLegendaFormaAvaliacao = "";

  /**
   * Controla altura do quadro da 1a e 2a via do boletim
   */
  if ($iContador % 2 != 0) {

    $iAlturaFinal   = $iAlturaInicial + 135;
    $oPdf->Rect(7, $iAlturaInicial, 195, 135);
    $oPdf->Rect(7, $iAlturaInicial, 195, 17);

    if (!empty($sLogo)) {
      $oPdf->Image($sLogo, ($oPdf->w - 20), $iAlturaInicial +2, 9);
    }
  } else {

    $iAlturaInicial = $iAlturaInicial + 140;
    $iAlturaFinal   = $iAlturaInicial + 135;
    $oPdf->Rect(7, $iAlturaInicial, 195, 135);
    $oPdf->Rect(7, $iAlturaInicial, 195, 17);
  }

  $oPdf->SetY($iAlturaInicial);

  /**
   * Verificamos a forma de avaliacao da turma
   */
  switch ($oFormaAvaliacao->getTipo()) {

    case 'NOTA':

      $sFormaAvaliacao        = "NF";
      $sLegendaFormaAvaliacao = "Nota Final";
      break;

    case "PARECER":

      $sFormaAvaliacao        = "PF";
      $sLegendaFormaAvaliacao = "Parecer Final";
      break;

    case "CONCEITO":
    case "NIVEL":

      $sFormaAvaliacao        = "CF";
      $sLegendaFormaAvaliacao = "Conceito Final";
      break;
  }

  // Definimos um tamanho padrao para a coluna dos periodos
  $iTamanhoColunaPeriodo      = 10;
  // Numeros de periodos que serao apresentados no relatorio
  $iNumeroDePeriodosAvaliacao = count($oGradeAproveitamento->getPeriodos());
  $iPeriodosParecer           = count( $oGradeAproveitamento->getPeriodos() );

  /**
   * Este array sera preenchido com os pareceres descritivos de cada periodo, obdecendo o parametro "Disciplinas"
   * configurado na tela
   * @var $aDisciplinaParecer
   */
  $aDisciplinaParecer  = array();

  $oPdf->SetFont('arial', '', 8);

  /**
   * Cabecalho do relatorio
   */
  $oPdf->Cell(7,   $iHeigth, "Acompanhamento do Rendimento do Aluno /".$oDadosTurma->anocalendario, 0, 1);
  $oPdf->Cell(7,   $iHeigth, "{$sNomeEscola}" , 0, 1);
  $oPdf->Cell(130, $iHeigth, "Aluno(a): " . $oMatricula->getAluno()->getNome(), 0, 0);
  $oPdf->Cell(20,  $iHeigth, "Nº " . $oMatricula->getAluno()->getCodigoAluno(), 0, 0);
  $oPdf->Cell(20,  $iHeigth, "Ano " . $oDadosTurma->anocalendario, 0, 1);
  $oPdf->Cell(70,  $iHeigth, "Turma: " . $oDadosTurma->turma, 0, 0);
  $oPdf->Cell(50,  $iHeigth, "Turno: " . $oDadosTurma->turno, 0, 1);

  /**
   * Inicio da impressao da grade de avaliacao
   */
  $oPdf->SetFont('arial', 'B', 7);
  $oPdf->SetY($oPdf->GetY() + 1);

  $iYInicio = $oPdf->GetY();

  $oPdf->MultiCell(45, 4, "COMPROVANTE\nCURRICULAR", 1, "C");
  $oPdf->SetY($iYInicio);
  $oPdf->SetX(52);

  /**
   * Iteramos sobre os periodos para criar o cabecalho dos periodos
   */
  foreach ($oGradeAproveitamento->getPeriodos() as $oPeriodo) {

    if ($oPeriodo instanceof AvaliacaoPeriodica) {
      $oPdf->Cell($iTamanhoColunaPeriodo,  8, $oPeriodo->getPeriodoAvaliacao()->getDescricaoAbreviada(), 1, 0, "C");
    } else if ($oPeriodo instanceof ResultadoAvaliacao) {
      $oPdf->Cell($iTamanhoColunaPeriodo,  8, $oPeriodo->getTipoResultado()->getDescricaoAbreviada(), 1, 0, "C");
    }
  }

  $iTamanhoDoQuadroDeParecer   = ($oPdf->w - 15) - (45 + ($iTamanhoColunaPeriodo * ($iNumeroDePeriodosAvaliacao)));
  $oPdf->Cell($iTamanhoDoQuadroDeParecer, 8, "PARECER DESCRITIVO - ANOS INICIAIS", 1, 1, "C");

  $aTotalFaltas = array();
  $oPdf->SetFont('arial', '', 7);
  $iYAntesImprimirDisciplina = $oPdf->GetY();

  /**
   * Imprimindo as disciplinas e avaliacoes (cria a grade de avaliacao)
   */
  foreach ($aGradeAluno as $oGradeAluno) {

    $iYInicio     = $oPdf->GetY();

    $oPdf->MultiCell(45, 4, $oGradeAluno->sNome, 1, "L");
    $iAlturaLinha = $oPdf->GetY() - $iYInicio;

    $oPdf->SetY($iYInicio);
    $oPdf->SetX(52);

    /**
     * Iteramos sobre os aproveitamento validadando a forma de avaliacao.
     * Se Forma avaliacao = "PARECER", populamos o $aDisciplinaParecer com os pareceres agrupando-os pelo Periodo
     */
    foreach ($oGradeAluno->aAproveitamento as $oAproveitamento) {

      $mAproveitamento  = $oAproveitamento->oAproveitamento->nAproveitamento;

      if (empty($oAproveitamento->sDescricao)) {
        continue;
      }

      if ($oAproveitamento->oAproveitamento->sFormaAvaliacao == "PARECER") {

        if ($oGet->punico = "yes" && (isset($aDisciplinaParecer[$oAproveitamento->sDescricao]) &&
            count($aDisciplinaParecer[$oAproveitamento->sDescricao]) > 0)) {
          break;
        }

        if (in_array($oGradeAluno->iCodigoRegencia, $aRegencias)) {
          $aDisciplinaParecer[$oAproveitamento->sDescricao][] = $oAproveitamento->oAproveitamento->sParecer;
        }

        $mAproveitamento      = "";
      } else {
        $aDisciplinaParecer[$oAproveitamento->sDescricao] = null;
      }
    }

    /**
     * Imprimimos a grade de resultado
     */
    foreach ($oGradeAluno->aAproveitamento as $iIndex => $oAproveitamento) {

      $mAproveitamento  = $oAproveitamento->oAproveitamento->nAproveitamento;

      if ($oAproveitamento->oAproveitamento->sFormaAvaliacao == "PARECER") {
        $mAproveitamento = "PD";
      }
      // Quando aluno possui uma NEE ele pode ser avaliado por parecer, mesmo a forma de avaliação da turma sendo outra
      if ( $oMatricula->isAvaliadoPorParecer() && !empty($mAproveitamento) ) {
        $mAproveitamento = "PD";
      }

      $oPdf->Cell($iTamanhoColunaPeriodo, $iAlturaLinha, $mAproveitamento, 1, 0, "C");
      $aTotalFaltas[$iIndex][] = $oAproveitamento->oAproveitamento->iFaltas;
    }

    /**
     * Se o Aproveitamento for "Parecer" não deve aparecer no boletim

    $mAproveitamentoFinal = $oGradeAluno->oResultadoFinal->nAproveitamentoFinal;
    if ($sFormaAvaliacao == "PF") {
      $mAproveitamentoFinal = "";
    }
    $oPdf->Cell($iTamanhoColunaPeriodo, $iAlturaLinha, $mAproveitamentoFinal, 1, 1, "C");
    */
    $oPdf->ln();
  }

  $oPdf->SetFont('arial', 'B', 7);
  $oPdf->Cell(45, 4, "TOTAL DE FALTAS", 1, 0, "C");
  $oPdf->SetFont('arial', '', 7);

  $iSomatorioFaltaPeriodos = 0;

  /**
   * Imprimimos as faltas do aluno no periodo e calculamos o total
   */
  foreach ($aTotalFaltas as $aFaltas) {

    $iTotalFaltaPeriodo = 0;
    foreach ($aFaltas as $iFalta) {
      $iTotalFaltaPeriodo += $iFalta;
    }
    $iSomatorioFaltaPeriodos += $iTotalFaltaPeriodo;
    $oPdf->Cell($iTamanhoColunaPeriodo, 4, $iTotalFaltaPeriodo, 1, 0, "C");
  }

  /**
   * Imprimimos os pareceres descritivo
   */
  $oPdf->SetFont('arial', '', 6);

  // Calculo para descobrir a altura dos quadros de pareceres descritivos
  $iAlturaQuadroLegendas          = 25;
  $iAlturaQuadroParecerDescritivo = (($iAlturaFinal - $iAlturaQuadroLegendas) - $iYAntesImprimirDisciplina) / $iPeriodosParecer;
  $oPdf->SetY($iYAntesImprimirDisciplina);
  $oPdf->SetX(52 + ($iNumeroDePeriodosAvaliacao * 10));

  $iPosicaoDoEixoX             = 52 + (($iNumeroDePeriodosAvaliacao * 10 ));
  $iAlturaInicialQuadroParecer = $iYAntesImprimirDisciplina; // A Altura inicial do quadro é a mesma da 1a disciplina
  $iLarguraDoQuadro            = ($oPdf->w - 8) - $iPosicaoDoEixoX;

  foreach ($aDisciplinaParecer as $sPeriodo => $aParecer) {

    $iYAntesImprimirParecer = $oPdf->GetY();
    // Desenha o retangulo do Parecer
    $oPdf->Rect($iPosicaoDoEixoX, $iAlturaInicialQuadroParecer, $iLarguraDoQuadro, $iAlturaQuadroParecerDescritivo);

    $sTextoParecer = "{$sPeriodo}: " ;

    if (is_array($aParecer)) {
      // Concatenamos todos os pareceres do periodo em uma so string
      foreach ($aParecer as $sParecer) {
        $sTextoParecer .= " {$sParecer}";
      }
    }

    $oPdf->SetY($oPdf->GetY() +1);
    $oPdf->SetX($iPosicaoDoEixoX);

    $iTamanhoStringValida = retornaQuantidadeDeCaracteresParaUmQuadro($iLarguraDoQuadro, $iAlturaQuadroParecerDescritivo);
    $oPdf->MultiCell($iTamanhoDoQuadroDeParecer, 2, substr($sTextoParecer, 0, $iTamanhoStringValida));

    $iYDepoisImprimirParecer = $oPdf->GetY();
    $iNovaAltura             = $oPdf->GetY();

    if (($iYDepoisImprimirParecer - $iYAntesImprimirParecer) <= $iAlturaQuadroParecerDescritivo) {
      $iNovaAltura             = $oPdf->getY() + abs(($iYDepoisImprimirParecer - $iYAntesImprimirParecer) - $iAlturaQuadroParecerDescritivo);
    }

    $oPdf->SetY($iNovaAltura);
    $iAlturaInicialQuadroParecer += $iAlturaQuadroParecerDescritivo;
  }

  /**
   * Resultado final do aluno
   */
  $sResultadoFinal = ResultadoFinal($oMatricula->getCodigo(),
                                    $oMatricula->getAluno()->getCodigoAluno(),
                                    $oMatricula->getTurma()->getCodigo(),
                                    $oMatricula->getSituacao(),
                                    $oMatricula->isConcluida()?'S':'N',
                                    $oMatricula->getTurma()->getBaseCurricular()->getCurso()->getEnsino()->getCodigo()
                                    );

  /**
   * Retangulo das Legendas
   */
  $iAlturaInicialQuadroLegenda = $iAlturaFinal - $iAlturaQuadroLegendas;
  $oPdf->Rect(7, $iAlturaInicialQuadroLegenda, 195, $iAlturaQuadroLegendas);
  $oPdf->SetY($iAlturaInicialQuadroLegenda);
  $oPdf->SetFont('arial', 'B', 7);
  $oPdf->Cell(35, $iHeigth, "Minimo para Aprovação:", 0, 0);
  $oPdf->SetFont('arial', '', 7);
  $oPdf->Cell(50, $iHeigth, $oGradeAproveitamento->getMinimoParaAprovacao(), 0, 0);
  $oPdf->SetFont('arial', 'B', 7);
  $oPdf->Cell(35, $iHeigth, "Resultado Final:", 0, 0);
  $oPdf->SetFont('arial', '', 7);
  $oPdf->Cell(50, $iHeigth, $sResultadoFinal, 0, 1);

  $aConceitos = null;
  if ($oFormaAvaliacao->getTipo() == "CONCEITO" || $oFormaAvaliacao->getTipo() == "NIVEL"){
    $aConceitos = $oFormaAvaliacao->getConceitos();
  }

  if (!empty($aConceitos)) {

    foreach ($aConceitos as $iIndice => $oConceito) {

      $iQuebraLinha = 1;
      if (($iIndice % 2) == 0) {

        $iQuebraLinha = 0;
      }
      $oPdf->SetFont('arial', 'B', 7);
      $oPdf->Cell(35, $iHeigth, "{$oConceito->sConceito} : ", 0, 0);
      $oPdf->SetFont('arial', '', 7);
      $oPdf->Cell(50, $iHeigth, $oConceito->sNome, 0, $iQuebraLinha);
    }
  }

  $oPdf->SetFont('arial', 'B', 7);
  $oPdf->Cell(35, $iHeigth, "{$sFormaAvaliacao} : ", 0, 0);
  $oPdf->SetFont('arial', '', 7);
  $oPdf->Cell(50, $iHeigth, $sLegendaFormaAvaliacao, 0, 1);

  if ($iContador % 2 == 0 && $iTotalMatriculas > 2 && ($iContador != $iTotalMatriculas)) {
    $oPdf->AddPage();
  }
  $iContador++;
}

/**
 * Retorna quantos caracteres cabe em um quadro
 * OBS.: Essa funcao esta configurada para retornar a quantidade de caracteres que cabem em mm2 quado:
 *       Fonte: do tipo Arial
 *       Tamanho da Fonte: 6
 *       Altura da linha: 2
 * @param float $iAltura
 * @param float $iLargura
 * @return integer
 */
function retornaQuantidadeDeCaracteresParaUmQuadro($iAltura, $iLargura) {

  $m2              = $iAltura * $iLargura;
  $m2Padrao        = 100; // mm2
  $iCaracterPadrao = 22;  // quantidade de caracteres por 100mm2

  return floor(($m2 /$m2Padrao) *  $iCaracterPadrao);
}

$oPdf->Output();
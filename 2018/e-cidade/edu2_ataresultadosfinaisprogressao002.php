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


require_once(modification("libs/db_utils.php"));
require_once(modification("fpdf151/FpdfMultiCellBorder.php"));
/*PLUGIN DIARIO PROGRESSAO PARCIAL - Require db_funcoes*/

define ('MSG_ATARESULTADOSFINAISPROGRESSAO002', "educacao.escola.edu2_ataresultadosfinaisprogressao002.");

$oGet                         = db_utils::postmemory($_GET);
$oGet->aTurmas                = str_replace('\\', "", $oGet->aTurmas);
$oGet->aTurmas                = JSON::create()->parse($oGet->aTurmas);
$oGet->sDiretor               = base64_decode($oGet->sDiretor);
$oGet->sSecretario            = base64_decode($oGet->sSecretario);
$oGet->sAssinaturaAdicicional = base64_decode($oGet->sAssinaturaAdicicional);

$oConfig                         = new stdClass();
$oConfig->sDiretor               = $oGet->sDiretor;
$oConfig->sSecretario            = $oGet->sSecretario;
$oConfig->sAssinaturaAdicicional = $oGet->sAssinaturaAdicicional;
$oConfig->sCargoAdicional        = $oGet->sCargoAdicional;
$oConfig->sTipoFrequencia        = $oGet->sTipoFrequencia;
$oConfig->iLimiteAlunosPagina    = 45;     // limita quebra de pagina
$oConfig->iLimiteRegenciaPagina  = 6;      // limita quebra de pagina
$oConfig->sColunaAvaliacao       = "AVAL"; // descrição da coluna de avaliação
$oConfig->sColunaFaltas          = "FT";   // descrição da coluna de frequencia quando para imprimir número de faltas
if ($oGet->sTipoFrequencia == 'P') {
  $oConfig->sColunaFaltas        = "% F"; // descrição da coluna de frequencia quando para imprimir percentual de frequência
}
$oConfig->iAlturaInicioAlunos    = 43;  // eixo y onde os alunos começaram a serem impressos
$oConfig->iAlturaLimiteAlunos    = 258; // altura limite do eixo y que devemos quebrar a pagina dos alunos
$oConfig->iAlturaRetanguloAlunos = 216; // Height para desenho dos retangulos
$oConfig->iLarguraRetangulo      = 194; // Width para desenho dos retangulos
$oConfig->iAlturaRodape          = 261; // eixo y que devemos escrever rodapé

$oConfig->iLarguraColunaAluno    = 60; // largura coluna aluno
$oConfig->iColunaDisciplina      = 20; // largura coluna disciplina
$oConfig->iColunaAvaliacao       = 12; // largura coluna avaliação
$oConfig->iColunaFrequencia      = 8;  // largura coluna frequencia
$oConfig->iColunaRF              = 9;  // largura coluna resultado final


$aDadosRelatorio = array();

try {

  $oMsgErro = new stdClass();
  if ( empty($oGet->iEscola) ) {
    throw new Exception( _M(MSG_ATARESULTADOSFINAISPROGRESSAO002 . "informe_escola") );
  }

  if ( empty($oGet->iCalendario) ) {
    throw new Exception( _M(MSG_ATARESULTADOSFINAISPROGRESSAO002 . "informe_calendario") );
  }

  if ( empty($oGet->aTurmas) ) {
    throw new Exception( _M(MSG_ATARESULTADOSFINAISPROGRESSAO002 . "informe_turmas") );
  }

  $oEscola     = EscolaRepository::getEscolaByCodigo($oGet->iEscola);
  $oCalendario = CalendarioRepository::getCalendarioByCodigo($oGet->iCalendario);
  $oData       = new DBDate( date("Y-m-d") );
  $iAno        = $oCalendario->getAnoExecucao();

  $oConfig->sMunicipio = $oEscola->getDepartamento()->getInstituicao()->getMunicipio();
  $oConfig->sData = $oData->dataPorExtenso();
  $oConfig->oData = $oData;

  foreach ($oGet->aTurmas as $oStdTurmaEtapa) {

    $oTurma  = TurmaRepository::getTurmaByCodigo($oStdTurmaEtapa->iTurma);
    $oEtapa  = EtapaRepository::getEtapaByCodigo($oStdTurmaEtapa->iEtapa);
    $oEnsino = $oEtapa->getEnsino();
    $iEnsino = $oEnsino->getCodigo(); // código do ensino - usado para buscar Termo de Encerramento

    $aTermosAprovado  = DBEducacaoTermo::getTermoEncerramento($iEnsino, 'A', $iAno);
    $aTermosReprovado = DBEducacaoTermo::getTermoEncerramento($iEnsino, 'R', $iAno);
    $aTermos          =  array_merge($aTermosAprovado, $aTermosReprovado);

    $oDadosTurma                     = new stdClass();
    $oDadosTurma->sTurma             = $oTurma->getDescricao();
    $oDadosTurma->sEtapa             = $oEtapa->getNome();
    $oDadosTurma->sTipoEnsino        = $oEnsino->getNome();
    $oDadosTurma->iAno               = $iAno;
    $oDadosTurma->aRegenciasPagina   = array(); // controle das regencias que serão impressas por pagina
    $oDadosTurma->aRegencias         = array();
    $oDadosTurma->aTermoEncerramento = $aTermos;

    $aAlunosTurma = array();
    $aRegencias   = $oTurma->getDisciplinasPorEtapa($oEtapa);

    $iPagina   = 1;
    $iContador = 0;
    foreach ($aRegencias as $oRegencia) {

      $aProgressoes = ProgressaoParcialAlunoRepository::getProgressoesVinculadasRegencia($oRegencia, 1);

      if ( count($aProgressoes) == 0 ) {
        continue;
      }

      $oDadosRegencia               = new stdClass();
      $oDadosRegencia->iRegencia    = $oRegencia->getCodigo();
      $oDadosRegencia->sDescricao   = $oRegencia->getDisciplina()->getNomeDisciplina();
      $oDadosRegencia->sAbreviatura = $oRegencia->getDisciplina()->getAbreviatura();
      $oDadosTurma->aRegencias[]    = $oDadosRegencia;

      if ( $iContador == $oConfig->iLimiteRegenciaPagina ) {

        $iPagina  ++;
        $iContador = 1;
      }
      $iContador ++;
      $oDadosTurma->aRegenciasPagina[$iPagina][] = $oRegencia->getCodigo();
    }

    foreach ($oDadosTurma->aRegencias as $oDadosRegenciaComAlunos) {

      $oRegencia    = RegenciaRepository::getRegenciaByCodigo( $oDadosRegenciaComAlunos->iRegencia );
      $aProgressoes = ProgressaoParcialAlunoRepository::getProgressoesVinculadasRegencia($oRegencia, 1);

      foreach ($aProgressoes as $oProgressaoParcial) {

        $sResultadoFinal = "";
        $iTotalFalta     = "";
        $mAvaliacao      = "";

        $oVinculoRegencia = $oProgressaoParcial->getVinculoPorRegencia($oRegencia);
        $oResultadoProgressao = $oVinculoRegencia->getResultadoFinal();
        if ( $oResultadoProgressao instanceof ProgressaoParcialAlunoResultadoFinal ) {

          $sResultadoFinal = retornarTermoResultadoFinal($aTermos, $oResultadoProgressao->getResultado() );
          $iTotalFalta     = $oResultadoProgressao->getTotalFalta();
          $mAvaliacao      = $oResultadoProgressao->getNota();
        }

        /*PLUGIN DIARIO PROGRESSAO PARCIAL - Validação alunos evadidos*/

        $iCodigoAluno = $oProgressaoParcial->getAluno()->getCodigoAluno();

        $oDadosAluno                   = new stdClass();
        $oDadosAluno->iCodigo          = $iCodigoAluno;
        $oDadosAluno->sNome            = $oProgressaoParcial->getAluno()->getNome();
        $oDadosAluno->iRegencia        = $oRegencia->getCodigo();
        $oDadosAluno->sResultadoFinal  = $sResultadoFinal;
        $oDadosAluno->iTotalFalta      = $iTotalFalta;
        $oDadosAluno->mAvaliacao       = $mAvaliacao;

        $aAlunosTurma[$iCodigoAluno][] = $oDadosAluno;
      }

    }

    uasort($aAlunosTurma, 'ordenaAlunoNome');
    $oDadosTurma->aAlunosTurma = $aAlunosTurma;
    $aDadosRelatorio[]         = $oDadosTurma;
  }

} catch ( Exception $e) {

  $sMsg = urlencode($e->getMessage());
  db_redireciona('db_erros.php?fechar=true&db_erro=' . $sMsg);
}

function retornarTermoResultadoFinal($aTermos, $sValorAproveitamento ) {

  foreach ($aTermos as $oTermo) {

    if ($sValorAproveitamento == $oTermo->sReferencia) {
      return $oTermo->sAbreviatura;
    }
  }
}

TurmaRepository::removeAll();
EtapaRepository::removeAll();
ProgressaoParcialAlunoRepository::removeAll();



/** ******************************************************************************************************* *
 ** ************************************** INICIO ESCRITA DO PDF ****************************************** *
 ********************************************************************************************************** */

$oPdf = new FpdfMultiCellBorder('P');
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->setExibeBrasao(true);
$oPdf->exibeHeader(true);
$oPdf->SetAutoPageBreak(false, 10);
$oPdf->SetFillColor(225);
$oPdf->SetMargins(8, 10);
$oPdf->mostrarRodape(true);
$oPdf->mostrarTotalDePaginas(true);

foreach ($aDadosRelatorio as $oDadosRelatorio) {

  $sMsg  = "Aos {$oConfig->oData->getDia()} dias do mês de {$oConfig->oData->getMesExtenso($oConfig->oData->getMes())}";
  $sMsg .= " de {$oConfig->oData->getAno()}, encerrou-se a apuração de resultados dos alunos de progressão parcial da turma";
  $sMsg .= " {$oDadosRelatorio->sTurma} do {$oDadosRelatorio->sTipoEnsino} deste estabelecimento, com os seguintes resultados:";

  $head1 = 'Ata de Resultados Finais de Progressão Parcial';
  $head3 = $sMsg;

  foreach ($oDadosRelatorio->aRegenciasPagina as $aListaRegencias) {

    imprimirCabecalho($oPdf, $oConfig, $oDadosRelatorio, $aListaRegencias);
    imprimirAluno($oPdf, $oConfig, $oDadosRelatorio, $aListaRegencias);
    imprimirRodape($oPdf, $oConfig, $oDadosRelatorio->aTermoEncerramento);
  }

}

/**
 * Imprime colunas em branco
 * @param  FPDF     $oPdf              instância de fpdf
 * @param  stdClass $oConfig           dados padrão do relatorio
 * @param  integer  $iImprimirEmBranco numero de colunas
 * @param  boolean  $lPinta            se deve pintar alinha
 * @param  integer  $iAlturaLinha      altura que a linha deve ter
 */
function imprimirColunaDisciplinaEmBranco($oPdf, $oConfig, $iImprimirEmBranco, $lPinta = false, $iAlturaLinha = 4, $lDividir = false) {

  for ($i = 1; $i <= $iImprimirEmBranco; $i++) {

    if ( $lDividir ) {

      $oPdf->Cell($oConfig->iColunaAvaliacao,  $iAlturaLinha, "", 1 ,0, 0 , $lPinta);
      $oPdf->Cell($oConfig->iColunaFrequencia, $iAlturaLinha, "", 1 ,0, 0 , $lPinta);
    } else {
      $oPdf->Cell($oConfig->iColunaDisciplina, $iAlturaLinha, "", 1 ,0, 0 , $lPinta);
    }
  }
}

/**
 * Imprime o cabeçalho do relatorio
 * @param  FPDF     $oPdf              instância de fpdf
 * @param  stdClass $oConfig           dados padrão do relatorio
 * @param  stdClass $oDadosRelatorio   objeto com os dados da turma sendo impressa
 * @param  array    $aListaRegencias   lista das disciplinas da turma na pagina impressa
 */
function imprimirCabecalho($oPdf, $oConfig, $oDadosRelatorio, $aListaRegencias) {

  $iColunaDisciplina = $oConfig->iColunaDisciplina;
  $iColunaAvaliacao  = $oConfig->iColunaAvaliacao;
  $iColunaFrequencia = $oConfig->iColunaFrequencia;
  $iColunaRF         = $oConfig->iColunaRF;

  // Calcula se precisará colocar colunas em branco
  $iImprimirEmBranco = $oConfig->iLimiteRegenciaPagina - count($aListaRegencias);

  $oPdf->SetFont('Arial', 'B', 7);
  $oPdf->addPage();

  // Primeira linha do cabeçalho
  $oPdf->Cell($oConfig->iLarguraColunaAluno + 5, 4, "", 1, 0, 'R');
  foreach ($oDadosRelatorio->aRegencias as $oDadosRegencia) {

    if (in_array($oDadosRegencia->iRegencia, $aListaRegencias) ) {
      $oPdf->Cell($iColunaDisciplina, 4, "$oDadosRegencia->sAbreviatura", 1 ,0, 'C' );
    }
  }

  if ( $iImprimirEmBranco > 0 ) {
    imprimirColunaDisciplinaEmBranco($oPdf, $oConfig, $iImprimirEmBranco, false, 4);
  }
  $oPdf->Cell($iColunaRF, 4, "", "TRL" ,1, 'C' );

  // Segunda Linha do cabeçalho
  $oPdf->Cell(5, 4, "Nº", 1, 0);
  $oPdf->Cell($oConfig->iLarguraColunaAluno, 4, "Nome do Aluno ", 1, 0, 'C');
  foreach ($oDadosRelatorio->aRegencias as $oDadosRegencia) {

    if (in_array($oDadosRegencia->iRegencia, $aListaRegencias) ) {

      $oPdf->Cell($iColunaAvaliacao,  4, $oConfig->sColunaAvaliacao, 1 ,0, 'C' );
      $oPdf->Cell($iColunaFrequencia, 4, $oConfig->sColunaFaltas,    1 ,0, 'C' );
    }
  }

  if ( $iImprimirEmBranco > 0 ) {
    imprimirColunaDisciplinaEmBranco($oPdf, $oConfig, $iImprimirEmBranco, false, 4, true);
  }

  $oPdf->Cell($iColunaRF, 4, "RF", "RLB" ,1, 'C' );

  /*
   * Imprime o retangulo envolta dos alunos
   */
  $oPdf->Rect($oPdf->GetX(), $oPdf->GetY(), $oConfig->iLarguraRetangulo, $oConfig->iAlturaRetanguloAlunos);
  $oPdf->SetFont('Arial', '', 7);

}

/**
 * Imprime os alunos da turma
 * @param  FPDF     $oPdf              instância de fpdf
 * @param  stdClass $oConfig           dados padrão do relatorio
 * @param  stdClass $oDadosRelatorio   objeto com os dados da turma sendo impressa
 * @param  array    $aListaRegencias   lista das disciplinas da turma na pagina impressa
 */
function imprimirAluno($oPdf, $oConfig, $oDadosRelatorio, $aListaRegencias) {

  $iImprimirEmBranco = $oConfig->iLimiteRegenciaPagina - count($aListaRegencias);
  $iNumero           = 1;
  $aNumerosImpressos = array();

  $oPdf->SetFont('Arial', '', 7);
  foreach ($oDadosRelatorio->aAlunosTurma as $aAlunoDisciplina) {

    $lPinta = (($iNumero % 2) == 0);

    $iNumeroAuxiliar = $iNumero;
    foreach ( $aAlunoDisciplina as $oDadosAluno ) {

      $iLinhasNomeAluno = $oPdf->NbLines($oConfig->iLarguraColunaAluno, $oDadosAluno->sNome);
      $iAlturaLinha     = 4;

      $iYInicio = $oPdf->GetY();

      if ($iLinhasNomeAluno > 1) {
        $iAlturaLinha = 4 * $iLinhasNomeAluno;
      }

      if (in_array($iNumeroAuxiliar, $aNumerosImpressos) ) {
        $iNumeroAuxiliar = '';
      }

      $oPdf->Cell(5, $iAlturaLinha, "{$iNumeroAuxiliar}", 1, 0, 0, $lPinta);
      $oPdf->MultiCell($oConfig->iLarguraColunaAluno, 4, $oDadosAluno->sNome, 1, 'L', $lPinta);
      $oPdf->SetXY($oConfig->iLarguraColunaAluno + 13, $iYInicio);

      $lImprimeResultadoFinal = false;
      foreach ($aListaRegencias as $iRegencia) {

        if ( $oDadosAluno->iRegencia == $iRegencia) {

          $lImprimeResultadoFinal = true;
          $oPdf->Cell( $oConfig->iColunaAvaliacao,  $iAlturaLinha,$oDadosAluno->mAvaliacao,  1, 0, 'C', $lPinta);
          $oPdf->Cell( $oConfig->iColunaFrequencia, $iAlturaLinha, "{$oDadosAluno->iTotalFalta}", 1, 0, 'C', $lPinta);
        } else {
          imprimirColunaDisciplinaEmBranco($oPdf, $oConfig, 1, $lPinta, $iAlturaLinha, true);
        }
      }

      imprimirColunaDisciplinaEmBranco($oPdf, $oConfig, $iImprimirEmBranco, $lPinta, $iAlturaLinha, true);

      $sResultadoFinal = $oDadosAluno->sResultadoFinal;
      if (!$lImprimeResultadoFinal) {
        $sResultadoFinal = "";
      }

      $oPdf->Cell( $oConfig->iColunaRF, $iAlturaLinha, $sResultadoFinal, 1, 1, 'C', $lPinta);

      if ( $oPdf->getY() >= $oConfig->iAlturaLimiteAlunos) {

        imprimirRodape($oPdf, $oConfig, $oDadosRelatorio->aTermoEncerramento);
        imprimirCabecalho($oPdf, $oConfig, $oDadosRelatorio, $aListaRegencias);
      }
      $aNumerosImpressos[] = $iNumero;
    }
    $iNumero ++;
  }

  /*
   * Imprime linhas em branco
   */
  while ($oPdf->getY() <= $oConfig->iAlturaLimiteAlunos) {
    imprimirLinhasAlunosEmBranco($oPdf, $oConfig);
  }

}

/**
 * Imprime linhas em branco
 * @param  FPDF     $oPdf              instância de fpdf
 * @param  stdClass $oConfig           dados padrão do relatorio
 */
function imprimirLinhasAlunosEmBranco($oPdf, $oConfig) {

  $oPdf->Cell(5, 4, "", 1, 0, 0);
  $oPdf->Cell($oConfig->iLarguraColunaAluno, 4, "", 1, 0);
  imprimirColunaDisciplinaEmBranco($oPdf, $oConfig, $oConfig->iLimiteRegenciaPagina, false, 4, true);
  $oPdf->Cell( $oConfig->iColunaRF, 4, "", 1, 1);
}

/**
 * Imprime o rodapé da pagina
 * @param  FPDF     $oPdf                 instância de fpdf
 * @param  stdClass $oConfig              dados padrão do relatorio
 * @param  array    $aTermoEncerramento   Termos de encerramento
 */
function imprimirRodape($oPdf, $oConfig, $aTermoEncerramento) {

  $iLargura       = $oConfig->iLarguraRetangulo; //
  $iAltura        = 25;  // altura do quadro das assinaturas
  $iMeiaPagina    = 105; // metade da pagina contando as bordas
  $iTercoPagina   = 73;  // um terço da pagina

  $iLarguraQuadro1 = 65;  // quadro das legendas
  $iLarguraQuadro2 = 125; // quadro das assinaturas

  // Desenha os quadros
  $oPdf->SetY($oConfig->iAlturaRodape);
  $oPdf->Rect($oPdf->GetX(), $oPdf->GetY(), $iLargura, $iAltura);
  $oPdf->Rect($oPdf->GetX(), $oPdf->GetY(), $iLargura, $iAltura);
  $oPdf->Line($iTercoPagina, $oPdf->GetY(), $iTercoPagina, $oPdf->GetY() + 25 );

  /* ************************************************************************************** *
   * ******************************** QUADRO DAS LEGENDAS ********************************* *
   **************************************************************************************** */
  $oPdf->SetFont('Arial', 'B', 7);
  $oPdf->Cell($iLarguraQuadro1, 4, 'Legendas:', 0, 1);
  $oPdf->SetFont('Arial', '', 7);
  foreach ($aTermoEncerramento as $oTermo) {
    $oPdf->Cell($iLarguraQuadro1, 4, "{$oTermo->sAbreviatura} - {$oTermo->sDescricao}", 0, 1);
  }

  /*PLUGIN DIARIO PROGRESSAO PARCIAL - Legenda Eva - EVADIDO*/

  $sLegendaFaltas = $oConfig->sTipoFrequencia == 'T' ? 'F - Faltas' : 'F - Frequência (%)';
  $oPdf->Cell($iLarguraQuadro1, 4, "AVAL - Avaliação | {$sLegendaFaltas}", 0, 1);
  $oPdf->Cell($iLarguraQuadro1, 4, "RF - Resultado Final",   0, 1);


  /* ************************************************************************************** *
   * ******************************* QUADRO DAS ASSINATURAS ******************************* *
   **************************************************************************************** */
  $oPdf->SetXY(75, $oConfig->iAlturaRodape);
  $oPdf->SetFont('Arial', '', 6);
  $sTermoFinal = "E, para constar, foi lavrada esta ata.    {$oConfig->sMunicipio}, {$oConfig->sData}.";
  $oPdf->Cell($iLarguraQuadro2, 4, $sTermoFinal, 0, 0, 'C');

  $oPdf->ln(16);

  $aNomeDiretor = array();
  if ( !empty($oConfig->sDiretor) ) {
    $aNomeDiretor[] = $oConfig->sDiretor;
  }
  $aNomeDiretor[]  = "Diretor";
  $sNomeDiretor    = implode("\n", $aNomeDiretor);

  $aNomeSecretario = array();
  if ( !empty( $oConfig->sSecretario ) ) {
    $aNomeSecretario[] = $oConfig->sSecretario;
  }
  $aNomeSecretario[] = "Secretário";
  $sNomeSecretario   = implode("\n", $aNomeSecretario);
  $oPdf->SetFont('Arial', '', 5.5);
  if ( empty($oConfig->sAssinaturaAdicicional) ) {

    $oPdf->Line(75, $oPdf->GetY(), 136, $oPdf->GetY() );
    $oPdf->Line(138, $oPdf->GetY(), 200, $oPdf->GetY() );
    $oPdf->ln(0.5);

    $iAlturaAssinatura = $oPdf->getY();
    $oPdf->SetXY(75, $iAlturaAssinatura);
    $oPdf->MultiCell(60, 3, "{$sNomeDiretor}", 0, 'C');
    $oPdf->SetXY(138, $iAlturaAssinatura);
    $oPdf->MultiCell(60, 3, "{$sNomeSecretario}", 0, 'C');

  } else {

    $oPdf->Line(75,  $oPdf->GetY(), 115, $oPdf->GetY() );
    $oPdf->Line(117, $oPdf->GetY(), 157, $oPdf->GetY() );
    $oPdf->Line(159, $oPdf->GetY(), 200, $oPdf->GetY() );
    $oPdf->ln(0.5);

    $iAlturaAssinatura = $oPdf->getY();

    $oPdf->SetXY(75, $iAlturaAssinatura);
    $oPdf->MultiCell(40, 3, "{$sNomeDiretor}", 0, 'C');
    $oPdf->SetXY(117, $iAlturaAssinatura);
    $oPdf->MultiCell(40, 3, "{$sNomeSecretario}", 0, 'C');
    $oPdf->SetXY(159, $iAlturaAssinatura);

    $sAssinaturaAdicional = "{$oConfig->sAssinaturaAdicicional}\n{$oConfig->sCargoAdicional}";
    $oPdf->MultiCell(40, 3, "{$sAssinaturaAdicional}", 0, 'C');
  }

}

$oPdf->output();

function ordenaAlunoNome($aArrayAtual, $aProximoArray) {
  return strcasecmp($aArrayAtual[0]->sNome, $aProximoArray[0]->sNome);
}
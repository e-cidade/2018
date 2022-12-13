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

/**
 * @author Andrio Costa  andrio.costa@dbseller.com.br
 *         Fabio Esteves fabio.esteves@dbseller.com.br
 * @version $Revision: 1.13 $
 */
require_once ("fpdf151/FpdfMultiCellBorder.php");
require_once ("libs/db_sql.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once ("dbforms/db_funcoes.php");
require_once ("model/educacao/avaliacao/iFormaObtencao.interface.php");
require_once ("model/educacao/avaliacao/iElementoAvaliacao.interface.php");
require_once ("std/DBDate.php");

db_app::import("educacao.*");
db_app::import("educacao.avaliacao.*");

$oGet             = db_utils::postMemory($_GET);
$aPeriodosFiltros = explode(",", $oGet->aPeriodo);


$aAlunos = array();


/**
 * Variaveis de configuracao do relatorio
 */
$sSecretaria    = "SECRETARIA MUNICIPAL DE EDUCAÇÃO";
$sNomeRelatorio = "Ficha de Acompanhamento de Desempenho Escolar";
$sNecessidades  = "Possui Necessidades Educativas Especiais com Laudo? (  ) Sim    (  ) Não";

$iHeigth       = 4;
$lPrimeiroLaco = true;

/**
 * Buscando os dados do relatorio
 */
$oEscola           = new Escola($oGet->iEscola);
$oTurma            = TurmaRepository::getTurmaByCodigo($oGet->iTurma);
$iAnoLetivo        = $oTurma->getCalendario()->getAnoExecucao();
$aPeriodos         = $oTurma->getCalendario()->getPeriodos();
$sTurno            = $oTurma->getTurno()->getDescricao();
$sTurma            = $oTurma->getDescricao();
$aCodigoAlunos     = explode(",", $oGet->aAlunos);
$iContadorAlunos   = count($aCodigoAlunos);
$iNumeroDePeriodos = count($aPeriodos);

for ($iAluno = 0; $iAluno < $iContadorAlunos; $iAluno++) {
  $aAlunos[] = AlunoRepository::getAlunoByCodigo($aCodigoAlunos[$iAluno]);
}

$sLegendas = "";
$aLegendas = $oEscola->getLegendas();
foreach ($aLegendas as $oLegenda) {
  $sLegendas .= "{$oLegenda->ed91_sigla}  - ({$oLegenda->ed91_c_descr})    ";
}

$oDaoParecer      = db_utils::getDao('parecer');
$aDisciplinaTurma = $oTurma->getDisciplinas();

/**
 * Buscamos as disciplinas que possuem parecer na turma. e as que nao possuem vinculo
 */
foreach ($aDisciplinaTurma as $oRegencia) {

  $iCodigoDisciplina     = $oRegencia->getDisciplina()->getCodigoDisciplina();
  $sCamposDisciplina     = "DISTINCT ed232_c_descr, ed92_i_codigo, ed92_c_descr, parecerdisciplina.*";
  $sWhereDisciplina      = "     ed92_i_escola = {$oGet->iEscola} ";
  $sWhereDisciplina     .= " and ed105_i_turma = {$oTurma->getCodigo()}";
  $sWhereDisciplina     .= " and (ed106_disciplina  = {$iCodigoDisciplina}";
  $sWhereDisciplina     .= "       or ed106_disciplina is null)";
  $sSqlParecerDisciplina = $oDaoParecer->sql_query_turma_disciplina_periodo(null,
                                                                            $sCamposDisciplina,
                                                                            null,
                                                                            $sWhereDisciplina
                                                                           );
  $rsParecerDisciplina   = $oDaoParecer->sql_record($sSqlParecerDisciplina);
  $iNumeroLinhas         = $oDaoParecer->numrows;
  $aDisciplinaParecer    = array();

  for ($i = 0; $i < $iNumeroLinhas; $i++) {

    $oDadosRetornados                      = db_utils::fieldsMemory($rsParecerDisciplina, $i);
    $oDisciplinaParecer                    = new stdClass();
    $oDisciplinaParecer->iCodigoDisciplina = $iCodigoDisciplina;
    $oDisciplinaParecer->iCodigoParecer    = $oDadosRetornados->ed92_i_codigo;
    $oDisciplinaParecer->sDisciplina       = $oRegencia->getDisciplina()->getNomeDisciplina();
    $oDisciplinaParecer->sParecer          = $oDadosRetornados->ed92_c_descr;

    $aDisciplinaParecer[$iCodigoDisciplina][] = $oDisciplinaParecer;
  }
}
/**
 * Imprimindo relatorio
 */
$oPdf = new FpdfMultiCellBorder();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetMargins(10, 10, 10);
$oPdf->SetAutoPageBreak(true, 10);
$oPdf->mostrarRodape(true);
$oPdf->mostrarTotalDePaginas(true);

for ($iContador = 0; $iContador < $iContadorAlunos; $iContador++) {

  /**
   * Verifica se a turma é do tipo Integral e Infantil, alterando a forma como é apresentada a descrição do
   * turno.
   * Por padrão, mostra somente a descrição do Turno (Ex.: MANHÃ)
   * No caso de turno Integral e Infantil, mostra também o turno referente o qual a matrícula está vinculada
   * Ex.: INTEGRAL - MANHÃ / TARDE
   */
  $oMatricula = $aAlunos[$iContador]->getMatriculaByTurma($oTurma);
  if (    $oMatricula->getTurma()->getTurno()->isIntegral()
    && $oMatricula->getTurma()->getBaseCurricular()->getCurso()->getEnsino()->isInfantil()
  ) {

    $aDescricaoTurno = array();
    $aTurnoReferente = array( 1 => 'MANHÃ', 2 => 'TARDE', 3 => 'NOITE' );

    foreach ( $oMatricula->getTurnosVinculados() as $oTurnoReferente ) {
      $aDescricaoTurno[] = $aTurnoReferente[ $oTurnoReferente->ed336_turnoreferente ];
    }

    $sTurno                  = "INTEGRAL - " . implode( " / ", $aDescricaoTurno );
    $lDescricaoTurnoValidada = true;
  }

  db_inicio_transacao();
  $oDiarioClasse              = $aAlunos[$iContador]->getMatriculaByTurma($oTurma)->getDiarioDeClasse();
  $aDiarioAvaliacaoDisciplina = $oDiarioClasse->getDisciplinas();
  db_fim_transacao();
  $aPareceresAluno = Array();

  $aParecerDescritivo = array();

  /**
   * Buscamos as avaliacoes dos pareceres
   */
  foreach ($aDiarioAvaliacaoDisciplina as $oDisciplina) {

    foreach ($oDisciplina->getAvaliacoes() as $oAvaliacao) {

      $iCodigoDisciplina = $oDisciplina->getDisciplina()->getCodigoDisciplina();

      /**
       * Verificar se o elemento de avaliacao é um avaliacao.
       */
      $oElementoAvaliacao = $oAvaliacao->getElementoAvaliacao();
      if (!$oElementoAvaliacao->isResultado()) {

        $sParecerDescritivo = "";
        $iCodigoPeriodo     = $oElementoAvaliacao->getPeriodoAvaliacao()->getCodigo();
        if (in_array($iCodigoPeriodo, $aPeriodosFiltros)) {

          if ($oElementoAvaliacao->getFormaDeAvaliacao()->getTipo() == "PARECER"
              && $oAvaliacao->getValorAproveitamento()->getAproveitamento() != "") {

            $sParecerDescritivo          .= $oAvaliacao->getValorAproveitamento()->getAproveitamento();
            $oParecerDescritivo           = new stdClass();
            $oParecerDescritivo->iPeriodo = $iCodigoPeriodo;
            $oParecerDescritivo->sPeriodo = $oAvaliacao->getElementoAvaliacao()->getDescricao();
            $oParecerDescritivo->sParecer = $sParecerDescritivo;
            $aParecerDescritivo[]         = $oParecerDescritivo;
            unset($oParecerDescritivo);
          }
          $sParecer       = $oAvaliacao->getParecerPadronizado();
          $aPartesParecer = explode("**", $sParecer);
          foreach ($aPartesParecer as $sParecerParte) {

            $aParecerUnico  = explode("-", $sParecerParte);
            $iCodigoParecer = trim($aParecerUnico[0]);


            $aLegenda = explode("=>", $sParecerParte);
            $sLegenda = "";
            if (count($aLegenda) > 1) {

              $sLegenda                                                              = trim($aLegenda[1]);
              $aPareceresAluno[$iCodigoDisciplina][$iCodigoParecer][$iCodigoPeriodo] = $sLegenda;
            }
          }
        }
      }
    }
  }
  $oPdf->AddPage();
  $oPdf->SetFont('arial', '', 7);

  /** *****************************************************************************************************************
   **************************************** IMPRIMIMOS O CABEÇALHO DO RELATORIO ***************************************
   *******************************************************************************************************************/
  $oPdf->Image("imagens/files/".$oEscola->getLogo(), $oPdf->GetX(), $oPdf->GetY(), 15);

  $sNomeEscola       = $oEscola->getDepartamento()->getNomeDepartamento();
  $iCodigoReferencia = $oEscola->getCodigoReferencia();

  if ( $iCodigoReferencia != null ) {
    $sNomeEscola = "{$iCodigoReferencia} - {$sNomeEscola}";
  }

  $oPdf->setfont('arial', 'b', 10);
  $oPdf->SetX(30);
  $oPdf->Cell(150, $iHeigth, $oEscola->getNomeEstadoExtenso(),    0, 1, "L");
  $oPdf->SetX(30);
  $oPdf->Cell(150, $iHeigth, $sNomeEscola, 0, 1, "L");
  $oPdf->SetX(30);
  $oPdf->Cell(150, $iHeigth, $sSecretaria, 0, 1, "L");

  $oPdf->ln(6);
  $oPdf->SetFont('arial', 'b', 11);
  $oPdf->Cell(190, $iHeigth, $sNomeRelatorio, 0, 1, "C", 0);

  $oPdf->SetFont('arial', '', 8);
  foreach ($oTurma->getEtapas() as $oEtapa) {

    $sEtapaEnsino  = ucwords(mb_strtolower($oEtapa->getEtapa()->getNome())) . " do ";
    $sEtapaEnsino .= ucwords(mb_strtolower($oEtapa->getEtapa()->getEnsino()->getNome()));
    $oPdf->Cell(190, 4, $sEtapaEnsino, 0, 1, "C", 0);
  }

  $oPdf->ln(3);
  $oPdf->SetFont('arial', 'B', 8);
  $oPdf->Cell(150, $iHeigth, "Professor(a): ", 0, 0, "L", 0);
  $oPdf->Cell(20,  $iHeigth, "Ano Letivo: ",   0, 0, "L", 0);
  $oPdf->SetFont('arial', '', 7);
  $oPdf->Cell(20,  $iHeigth, $iAnoLetivo,       0, 1, "L", 0);
  $oPdf->SetFont('arial', 'B', 8);
  $oPdf->Cell(20,  $iHeigth, "Aluno(a): ",     0, 0, "L", 0);
  $oPdf->SetFont('arial', '', 7);
  $oPdf->Cell(170, $iHeigth, $aAlunos[$iContador]->getNome(), 0, 1, "L", 0);
  $oPdf->SetFont('arial', 'B', 8);
  $oPdf->Cell(20, $iHeigth, "Turno: ",         0, 0, "L", 0);
  $oPdf->SetFont('arial', '', 7);
  $oPdf->Cell(45, $iHeigth, $sTurno,           0, 0, "L", 0);
  $oPdf->SetFont('arial', 'B', 8);
  $oPdf->Cell(12, $iHeigth, "Turma: ",         0, 0, "L", 0);
  $oPdf->SetFont('arial', '', 7);
  $oPdf->Cell(50, $iHeigth, $sTurma,           0, 1, "L", 0);


  /*
   *  impressao das legendas de cada parecer
   */

  /**
   * Verificamos se o aluno possui necessidades especiais para preenchimento de Sim ou Nao na ficha
   */
  $oDaoAlunoNecessidades   = db_utils::getDao("alunonecessidade");
  $sWhereAlunoNecessidades = "ed214_i_aluno = {$aAlunos[$iContador]->getCodigoAluno()}";
  $sSqlAlunoNecessidades   = $oDaoAlunoNecessidades->sql_query_file(null, "*", null, $sWhereAlunoNecessidades);
  $sPossuiNecessidades     = "(  ) Sim    ( x ) Não";

  if ($oDaoAlunoNecessidades->numrows > 0) {
    $sPossuiNecessidades = "( x ) Sim    (  ) Não";
  }
  $sNecessidades = "Possui Necessidades Educativas Especiais com Laudo? {$sPossuiNecessidades}";
  unset($oDaoAlunoNecessidades);

  $oPdf->Ln(3);
  $oPdf->SetFont('arial', 'B', 8);
  $oPdf->Cell(190, $iHeigth, $sNecessidades,         0, 1, "L", 0);
  $oPdf->Cell(18,  $iHeigth, "Legendas: ",           0, 0, "L", 0);
  $oPdf->SetFont('arial', '', 7);
  $oPdf->MultiCell(172, $iHeigth, $sLegendas, 0, "L");
  $oPdf->Ln(2);

  /**
   * Inicio da impressao dos pareceres padronizados
   */

  /**
   * Tamanho da coluna:  "Aspecto desenvolvido ao longo do ano letivo" - Área
   */
  $iTamanhoCelulaDescricaoParecerPadrao    = 175;
  $iTamanhoCelulaPeriodoPadrao             = 10;
  $iTamanhoCelulaDescricaoParecerCalculado = $iTamanhoCelulaDescricaoParecerPadrao -
                                             ($iNumeroDePeriodos * $iTamanhoCelulaPeriodoPadrao);

  foreach ($aDisciplinaParecer as $aParecer) {

    $sNomeDisciplina  = "";
    $iYInicial        = $oPdf->GetY() + 4;
    $iXInicial        = $oPdf->GetX();
    $iAlturaRetangulo = "";

    foreach ($aParecer as $oParecer) {

      /**
       * Variável para verificar se houve quebra de página para os pareceres de uma mesma disciplina
       */
      $lQuebrouPagina     = false;
      $iNumeroDePareceres = count($aParecer);
      $sNomeDisciplina    = $oParecer->sDisciplina;


      if ($oPdf->gety() > $oPdf->h - 15 ) {

        imprimeDisciplina($oPdf, $iYInicial, $iXInicial, $iAlturaRetangulo, $sNomeDisciplina,
                          $iTamanhoCelulaDescricaoParecerCalculado
                         );
        $oPdf->AddPage();
        setHeader($oPdf, $iHeigth, $aPeriodos, $iTamanhoCelulaDescricaoParecerCalculado, $iTamanhoCelulaPeriodoPadrao);
        $lQuebrouPagina = true;
        $iYInicial      = $oPdf->GetY();
      }

      /**
       * Caso tenha quebrado a página entre os pareceres de uma mesma disciplina, zeramos o valor para cálculo
       * da altura do retângulo
       */
      if ($lQuebrouPagina) {
        $iAlturaRetangulo = 0;
      }

      if ($lPrimeiroLaco) {

        setHeader($oPdf, $iHeigth, $aPeriodos, $iTamanhoCelulaDescricaoParecerCalculado, $iTamanhoCelulaPeriodoPadrao);
        $lPrimeiroLaco = false;
      }

      $oPdf->SetX(25);
      $oPdf->SetFont('arial', '', 7);

      $iAlturaCelulaParecer = $oPdf->getY();


      $oPdf->multiCell($iTamanhoCelulaDescricaoParecerCalculado, $iHeigth, $oParecer->sParecer, 1);
      $iHeigthPeriodo = $oPdf->getY() - $iAlturaCelulaParecer;
      $oPdf->SetXY($iTamanhoCelulaDescricaoParecerCalculado + 25, $iAlturaCelulaParecer);

      /**
       * Iteramos sobre os periodos validando se existe legenda para o parecer em um determinado periodo.
       */
      foreach ($aPeriodos as $oPeriodo) {

        $iCodigoPeriodo = $oPeriodo->getPeriodoAvaliacao()->getCodigo();
        if (isset($aPareceresAluno[$oParecer->iCodigoDisciplina])
            && isset($aPareceresAluno[$oParecer->iCodigoDisciplina][$oParecer->iCodigoParecer])) {

          $aParecerDisciplina = $aPareceresAluno[$oParecer->iCodigoDisciplina][$oParecer->iCodigoParecer];
          $sLegendaAvaliada   = "";
          if (array_key_exists($oPeriodo->getPeriodoAvaliacao()->getCodigo(), $aParecerDisciplina)){
            $sLegendaAvaliada = $aParecerDisciplina[$oPeriodo->getPeriodoAvaliacao()->getCodigo()];
          }
          $oPdf->Cell($iTamanhoCelulaPeriodoPadrao,  $iHeigthPeriodo, "{$sLegendaAvaliada}", 1, 0, "C");
        } else {
          $oPdf->Cell($iTamanhoCelulaPeriodoPadrao,  $iHeigthPeriodo, "", 1, 0, "L");
        }
      }
      $oPdf->Cell(0,  $iHeigthPeriodo, "", 0, 1, "L");

      $iAlturaRetangulo += $iHeigthPeriodo;
      $lQuebrouPagina    = false;
    }

    $lPrimeiroLaco = true;
    imprimeDisciplina($oPdf, $iYInicial, $iXInicial, $iAlturaRetangulo,
                      $sNomeDisciplina, $iTamanhoCelulaDescricaoParecerCalculado
                     );
    $oPdf->Ln();
  }

  /**
   * Quadro de apontamentos
   */
  $iEixoYInicialApontamentos = $oPdf->getY();
  $iAlturaDoQuadro           = 104;

  /**
   * Aqui será impresso o parecer descritivo
   * Documento de parecer descritivo tem 26 linhas contando as 2 linhas do header.
   * Neste documento estamos utilizando a altura das linhas com o valor 4 mm portanto o quadro terá uma altura de 104 mm
   * 96 mm
   */
  $oPdf->SetFont('arial', 'b', 7);
  $oPdf->Cell(190, $iHeigth, "APONTAMENTOS", "TRL", 1, "C");
  $oPdf->SetFont('arial', 'i', 6);
  $sApontamento = "(Avanços, dificuldades, intervenções, outros elementos mediadores adotados)";
  $oPdf->Cell(190, $iHeigth, $sApontamento, "RLB", 1, "C");

  $iAlturaCelulaApontamento = $oPdf->getY();

  /**
   * Imprimimos o valor contido no sistema.
   */
  $oPdf->SetFont('arial', '', 7);

  $sParecerDescritivo    = "";
  $aParecerDescritivoAux = array();

  foreach ($aParecerDescritivo as $oParecerDescritivo) {

    if (array_key_exists($oParecerDescritivo->sPeriodo, $aParecerDescritivoAux)) {
      $aParecerDescritivoAux[$oParecerDescritivo->sPeriodo] .= " {$oParecerDescritivo->sParecer}";
    } else {

      $sParecerDescritivo                                   = str_replace("", " ", $oParecerDescritivo->sParecer);
      $aParecerDescritivoAux[$oParecerDescritivo->sPeriodo] = "{$oParecerDescritivo->sPeriodo}:\n{$sParecerDescritivo}";
    }
  }

  $sParecerDescritivo = "";
  foreach ($aParecerDescritivoAux as $sParecer) {

    $sParecerDescritivo .= $sParecer;
    $sParecerDescritivo .= "\n\n";
  }

  if (!empty($sParecerDescritivo)) {

    $iTamanhoString = $oPdf->GetStringWidth($sParecerDescritivo);
    $iXInicial      = $oPdf->GetX();
    $iYInicial      = $oPdf->GetY();
    $iYFinalPagina  = $oPdf->h - 100;
    $oPdf->multiCell(190, 3, $sParecerDescritivo, 1, "J", 0);
  } else {

    $iLinhasApontamento = ($iAlturaDoQuadro - ($oPdf->getY() - $iEixoYInicialApontamentos)) / 4;

    /**
     * Imprimimos as linhas
     */
    for ($iApontamento = 0; $iApontamento < $iLinhasApontamento; $iApontamento++) {
      $oPdf->Cell(190, $iHeigth, "", 1, 1, "C");
    }

  }
  if ($oPdf->GetY() == ($oPdf->h - 55)) {

    $oPdf->AddPage();
  }

  $oPdf->Ln(2);

  $sAcompanhamentoObservacao  = "Esta ficha de avaliação é apenas um dentre vários instrumentos a serem utilizados ";
  $sAcompanhamentoObservacao .= "para verificar o processo de ensino e aprendizagem. Tendo em vista este instrumento ";
  $sAcompanhamentoObservacao .= "como referência, o professor deverá adequar o tratamento dado aos conteúdos ";
  $sAcompanhamentoObservacao .= "escolares, atendendo as necessidades de diferenciação da metodologia e pedagogia ";
  $sAcompanhamentoObservacao .= "aplicada para o Ensino Fundamental e Educação de Jovens e Adultos.";
  $iTotalLinhasObservacao     = $oPdf->NbLines(190, $sAcompanhamentoObservacao) + 4;
  if ($oPdf->GetY() > ($oPdf->h - ($iTotalLinhasObservacao + 15))) {
    $oPdf->AddPage();
  }
  $oPdf->SetFont('arial', 'b', 8);
  $oPdf->Cell(190, 5, "OBSERVAÇÕES", "LRT", 1, "C");
  $oPdf->SetFont('arial', '', 6);
  $oPdf->MultiCell(190, 3, $sAcompanhamentoObservacao, "LRB", 1, "C");
  $oPdf->Ln(2);
  if ($oPdf->GetY() > ($oPdf->h - 39)) {
    $oPdf->AddPage();
  }
  /**
   * Imprimimos o quadro final do relatório
   */
  $oPdf->SetFont('arial', '', 7);
  $oPdf->Cell(35, $iHeigth, "Responsável pelo aluno(a): ",       0, 0, "L", 0);
  $oPdf->Cell(155, $iHeigth, $aAlunos[$iContador]->getNomeMae(), 0, 1, "L", 0);
  $oPdf->Line(43,  $oPdf->GetY()-1, 200, $oPdf->GetY()-1);

  $lPrimeiroPeriodo = true;
  $iTotalFaltasAno  = 0;


  foreach ($aPeriodos as $oPeriodos) {

    $iFaltasDoPeriodo = 0;

    foreach ($aDiarioAvaliacaoDisciplina as $oDiarioAvaliacaoDisciplina) {

      $iFaltasDoPeriodo += $oDiarioAvaliacaoDisciplina->getTotalFaltasPorPeriodo($oPeriodos->getPeriodoAvaliacao());
    }
    $iTotalFaltasAno += $iFaltasDoPeriodo;

    $iFaltasDoPeriodo   = $iFaltasDoPeriodo == 0 ? "" :  $iFaltasDoPeriodo;
    $sDescricaoPeriodo  = $oPeriodos->getPeriodoAvaliacao()->getDescricao();
    $sDescricaoPeriodo .= "  ( {$iFaltasDoPeriodo} ) faltas:";

    if ($lPrimeiroPeriodo) {

      $oPdf->Cell(35,  $iHeigth, $sDescricaoPeriodo, 0, 0, "L");
      $oPdf->Cell(60,  $iHeigth, "",               "B", 0, "L");
      $lPrimeiroPeriodo = false;
    } else {

      $oPdf->Cell(35,  $iHeigth, $sDescricaoPeriodo, 0, 0, "L");
      $oPdf->Cell(60,  $iHeigth, "",               "B", 1, "L");
      $lPrimeiroPeriodo = true;
    }
  }
  $oPdf->Ln(4);

  $iTotalFaltasAno  = $iTotalFaltasAno == 0 ? "" : $iTotalFaltasAno;
  $sTotalFaltasAno  = "O total de faltas no decorrer do ano letivo foi de:  ";
  $sTotalFrequencia = " e o percentual de frequência: ";
  $oPdf->Cell(55,  $iHeigth, $sTotalFaltasAno,  0, 0, "L", 0);
  $oPdf->Cell(10,  $iHeigth, $iTotalFaltasAno , 0, 0, "C", 0);
  $oPdf->Cell(95,  $iHeigth, $sTotalFrequencia, 0, 1, "L", 0);
  $oPdf->Line(65,  $oPdf->GetY()-1,  75, $oPdf->GetY()-1);
  $oPdf->Line(110, $oPdf->GetY()-1, 200, $oPdf->GetY()-1);

  $oPdf->Cell(190, $iHeigth, "O(A) aluno(a) foi considerado(a):", 0, 1);
  $oPdf->Line(50,  $oPdf->GetY()-1, 200, $oPdf->GetY()-1);
  $oPdf->ln(2);

  $oPdf->Cell(85,  $iHeigth, "Professor(a):",              0, 0);
  $oPdf->Cell(85,  $iHeigth, "Orientador(a) Pedagógico:",  0, 1);
  $oPdf->Line(28,  $oPdf->GetY()-1,  93, $oPdf->GetY()-1);
  $oPdf->Line(130, $oPdf->GetY()-1, 200, $oPdf->GetY()-1);

  $oPdf->Cell(85,  $iHeigth, "Orientador(a) Educacional:", 0, 0);
  $oPdf->Cell(85,  $iHeigth, "Direção:",                   0, 1);
  $oPdf->Line(42,  $oPdf->GetY()-1,  93, $oPdf->GetY()-1);
  $oPdf->Line(110, $oPdf->GetY()-1, 200, $oPdf->GetY()-1);


}

$oPdf->Output();

/**
 * Para poder-mos imprimir o nome da disciplina temos que setar a altura inicial do texto.
 * Após a impressão temos que setar novamete a altura atual do texto. Para isso utilizamos as variáveis:
 * $iYInicial e $iYFinalParecer
 *
 * Para imprimir o nome da disciplina no centro, realizamos o calculo: (($iYInicial +  $iYFinalParecer)/2 )- 3
 * Onde -3 é o valor de correcao para centralizar o texto
 */
function imprimeDisciplina(FPDF $oPdf, $iYInicial, $iXInicial, $iAlturaRetangulo, $sNomeDisciplina,
                          $iTamanhoCelulaDescricaoParecerCalculado) {

  $iYFinalParecer                      = $oPdf->GetY();
  $iCalculoDoTextoDaAlturaDaDisciplina = (($iYInicial +  $iYFinalParecer)/2 )-2;
  $iAlturaTextoDisciplina              = $iCalculoDoTextoDaAlturaDaDisciplina < $iYInicial ? $iYInicial
                                                                             : $iCalculoDoTextoDaAlturaDaDisciplina ;

  $oPdf->SetFont('arial', '', 5);

  /**
   * Verificamos o tamanho da string para podermos calcular a altura que o retangulo tera.
   * Apos isso setamos novamente a Altura Y do cursor.
   */
  $iTamanhoDisciplina = $oPdf->getStringWidth($sNomeDisciplina);
  $oPdf->SetY($iAlturaTextoDisciplina);
  $oPdf->MultiCell(15, 2.5, $sNomeDisciplina, 0, "C");

  /**
   * Calculo da altura do quadro da disciplina
   */
  $iEixoYMaisTexto          = $oPdf->GetY() - $iAlturaTextoDisciplina;
  $iAlturaDoQuadroCalculada = $iAlturaRetangulo >= $iEixoYMaisTexto ? $iAlturaRetangulo : $iEixoYMaisTexto;

  $iEixoY = $oPdf->GetY() >= $iYFinalParecer ? $oPdf->GetY() : $iYFinalParecer;
  $oPdf->SetY($iEixoY);

  /**
   * Verificamos se a altura do quadro da discplina ficou maior que o quadro dos pareceres comparando a diferenca
   * entre a altura final de ambos.
   * Se disciplinas for maior preenchemos o quadro dos pareceres com linhas em branco
   */
  if ($oPdf->GetY() > $iYFinalParecer) {

    $iCorrigir = ceil(($oPdf->GetY() - $iYFinalParecer) / 4);
    $oPdf->SetY($iYFinalParecer);

    for ($i = 1; $i <= $iCorrigir; $i++) {

      $oPdf->SetY($oPdf->GetY());
      $oPdf->SetX(25);

      $oPdf->Cell($iTamanhoCelulaDescricaoParecerCalculado,  4, "", 1, 0, "L");
      $oPdf->Cell(10,  4, "", 1, 0, "L");
      $oPdf->Cell(10,  4, "", 1, 0, "L");
      $oPdf->Cell(10,  4, "", 1, 1, "L");
    }

    $iAlturaDoQuadroCalculada = $iAlturaDoQuadroCalculada + ($oPdf->GetY() - $iYFinalParecer - $iCorrigir);
  }

  $oPdf->Rect($iXInicial, $iYInicial, 15, $iAlturaDoQuadroCalculada);
}


function setHeader($oPdf, $iHeigth, $aPeriodos, $iTamanhoCelulaDescricaoParecerCalculado,
                   $iTamanhoCelulaPeriodoPadrao) {

  $sLabelDescricaoParecer = "Aspecto desenvolvido ao longo do ano letivo";

  $oPdf->setfont('arial', 'b', 9);
  $oPdf->setfillcolor(235);
  $oPdf->Cell(15,  $iHeigth, "Área", 1, 0, "C", 1);
  $oPdf->Cell($iTamanhoCelulaDescricaoParecerCalculado, $iHeigth, $sLabelDescricaoParecer, 1, 0, "C", 1);

  $oPdf->setfont('arial', 'b', 6);
  foreach ($aPeriodos as $oPeriodos) {
    $oPdf->Cell($iTamanhoCelulaPeriodoPadrao,  $iHeigth, $oPeriodos->getPeriodoAvaliacao()->getDescricaoAbreviada(),
                1, 0, "C", 1
               );
  }
  $oPdf->ln();
}
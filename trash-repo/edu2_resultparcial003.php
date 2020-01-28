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

require_once ("fpdf151/pdf.php");
require_once ("std/DBDate.php");
require_once ("std/db_stdClass.php");
require_once ("dbforms/db_funcoes.php");
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

/**
 * Relat�rio de Conselho de Classe
 * filtros passados na url
 *  - periodo                
 *  - trocaTurma             
 *  - classificacaoAlunoTurma
 *  - turmas = pode ser informado mais de um c�digo que ser� separado por virgula.
 *             OBS.: n�o � o c�digo da turma e sim o c�digo da: turmaserieregimemat
 * relat�rio
 * - quebra p�gina por turma 
 * - possui as seguintes colunas:
 * -- N� = classifica��o do aluno (opcional, ver filtro $oConfigRelatorio->lClassificacaoAlunoTurma)   
 * -- Aluno = nome do aluno
 * -- S = situacao do aluno
 * -- Parecere 
 * -- [Disciplinas] = todas disciplinas da turma (Config Padr�o: $oConfigRelatorio->iMaximoDisciplinaPagina = 10)
 * -- TF            = Total de faltas 
 */

$oGet  = db_utils::postMemory($_GET);
$oJson = new Services_JSON();

$aTurmasSelecionadas = $oJson->decode(str_replace("\\", "", $oGet->oTurmas));

$aFiltroParametro = array();
$aFiltroParametro[] = null;
$aFiltroParametro[] = "ed233_c_notabranca";
$aFiltroParametro[] = null;
$aFiltroParametro[] = " ed233_i_escola = " . db_getsession("DB_coddepto");
$aParametroGlobal   = db_stdClass::getParametro("edu_parametros", $aFiltroParametro, "ed233_c_notabranca");

/** 
 * Objeto com a configura��o do relat�rio
 */
$oConfigRelatorio = new stdClass();
$oConfigRelatorio->lTrocaTurma              = $oGet->trocaTurma == 'Sim' ? true : false;
$oConfigRelatorio->lClassificacaoAlunoTurma = $oGet->classificacaoAlunoTurma == 'Sim' ? true : false;
$oConfigRelatorio->comLegenda               = $oGet->comLegenda == 'Sim' ? true : false;
$oConfigRelatorio->iFonteAvaliacao          = $oGet->tamanhoFonte;
$oConfigRelatorio->iMaximoDisciplinaPagina  = 10; // N� disciplina por p�gina  (M�ximo 10)
$oConfigRelatorio->iAlunosPorPagina         = 35; // N� de alunos por p�gina
$oConfigRelatorio->iAlturaLinha             = 4;  // Altura da linha
$oConfigRelatorio->iColunaNome              = 34; // Largura da coluna Aluno
$oConfigRelatorio->iColunaNumero            = 5;  // Largura da coluna N�, S (Situa��o) e TF (Total de Faltas)
$oConfigRelatorio->iColunaCodigo            = 9;  // C�digoAluno
$oConfigRelatorio->iColunaPareceres         = 18; // Coluna Pareceres
$oConfigRelatorio->iAlturaLine              = 183; //


$iSomaColunasDadosAluno  = ($oConfigRelatorio->iColunaNome + $oConfigRelatorio->iColunaCodigo);
$iSomaColunasDadosAluno += $oConfigRelatorio->iColunaPareceres;

/**
 * Por que o calculo abaixo?
 * Como visto no coment�rio da vari�vel ($oConfigRelatorio->iColunaNumero) ela � utilizada para definir o tamanho 
 * de tr�s colunas.   
 * -- N�, S (Situa��o) e TF (Total de Faltas)
 * Sendo assim calculamos quantas vezes ela ser� descontada da $oConfigRelatorio->iLarguraTotalDisciplinas
 */ 
if ($oConfigRelatorio->lTrocaTurma) {
  $iSomaColunasDadosAluno += ($oConfigRelatorio->iColunaNumero * 3);  
} else {
  $iSomaColunasDadosAluno += ($oConfigRelatorio->iColunaNumero * 2);
}


$oConfigRelatorio->iLarguraTotalDisciplinas  = 282 - $iSomaColunasDadosAluno;
$oConfigRelatorio->iLarguraTotalDisciplinas -= (0.3 * $oConfigRelatorio->iMaximoDisciplinaPagina);    
$oConfigRelatorio->lCalculaMediaParcial      = $aParametroGlobal[0]->ed233_c_notabranca == 'S' ? true : false;

$aTurmas = array();
/**
 * Cria a instancia de todas turmas selecionas no filtro 
 * Organizamos os dados a serem impressos no relat�rio 
 */
foreach ($aTurmasSelecionadas as $oTurmaSelecionada) {
  
  $oTurma = TurmaRepository::getTurmaByCodigo($oTurmaSelecionada->iTurma);
  $oEtapa = EtapaRepository::getEtapaByCodigo($oTurmaSelecionada->iEtapa);
  
  if (empty($oTurma)) {
    continue;
  }
  
  $oTurmaEtapa           = new stdClass();
  $oTurmaEtapa->aAlunos  = array();
  $oTurmaEtapa->aPaginas = array();
  $iContDisciplinas      = 0;
  
  /**
   * Verificamos quantas p�ginas ter� o relat�rio
   */ 
  foreach ($oTurma->getDisciplinasPorEtapa($oEtapa) as $oRegencia) {
    
    $iContDisciplinas ++;
    
    if ($iContDisciplinas <= $oConfigRelatorio->iMaximoDisciplinaPagina) {
      $oTurmaEtapa->aPaginas[0][$oRegencia->getCodigo()] = $oRegencia;
    } else if ($iContDisciplinas > $oConfigRelatorio->iMaximoDisciplinaPagina &&
               $iContDisciplinas <= ($oConfigRelatorio->iMaximoDisciplinaPagina * 2)) {
      $oTurmaEtapa->aPaginas[1][$oRegencia->getCodigo()] = $oRegencia;
    } else {
      $oTurmaEtapa->aPaginas[2][$oRegencia->getCodigo()] = $oRegencia;
    }
  }
  
   
  /**
   * Informa��es referente a turma
   */
  $oTurmaEtapa->sTurma          = $oTurma->getDescricao();
  $oTurmaEtapa->sEtapa          = $oEtapa->getNome();
  $oTurmaEtapa->sTurno          = $oTurma->getTurno()->getDescricao();
  $oTurmaEtapa->sCalendario     = $oTurma->getCalendario()->getDescricao();
  $oTurmaEtapa->iAnoCalendario  = $oTurma->getCalendario()->getAnoExecucao();
  $oTurmaEtapa->sCurso          = $oTurma->getBaseCurricular()->getCurso()->getNome();
  $oTurmaEtapa->sFormaAvaliacao = null;
  $oTurmaEtapa->sPeriodo        = null;
  $oTurmaEtapa->lUltimoPeriodo  = false;
  $oTurmaEtapa->lJaCalculado    = false; // Controle utilizado quando $oTurmaEtapa->lUltimoPeriodo = true 
  $oTurmaEtapa->iColunaNome     = $oConfigRelatorio->iColunaNome;
  
  /**
   * Localizamos qual forma de avaliacao para o per�odo selecionado 
   */ 
  $oAvaliacaoPeriodica = null;
  
  $iOrdemPeriodoSelecionado = 0;
  $iOrdemUltimoPeriodoTurma = 0;
  foreach ($oTurma->getProcedimentoDeAvaliacaoDaEtapa($oEtapa)->getElementos() as $oAvaliacaoPeriodicaTurma) {
    
    
    if ($oAvaliacaoPeriodicaTurma->isResultado()) {
      continue;
    }
    $iOrdemUltimoPeriodoTurma = $oAvaliacaoPeriodicaTurma->getPeriodoAvaliacao()->getOrdemPeriodo();
    
    if ($oAvaliacaoPeriodicaTurma->getPeriodoAvaliacao()->getCodigo() == $oGet->periodo) {
      
      $iOrdemPeriodoSelecionado         = $oAvaliacaoPeriodicaTurma->getPeriodoAvaliacao()->getOrdemPeriodo();
      $oTurmaEtapa->sNomeFormaAvaliacao = $oAvaliacaoPeriodicaTurma->getFormaDeAvaliacao()->getDescricao();
      $oTurmaEtapa->sFormaAvaliacao     = $oAvaliacaoPeriodicaTurma->getFormaDeAvaliacao()->getTipo();
      $oTurmaEtapa->sPeriodo            = $oAvaliacaoPeriodicaTurma->getPeriodoAvaliacao()->getDescricao();
      $oAvaliacaoPeriodica              = $oAvaliacaoPeriodicaTurma;
    }
  }
  
  if ($iOrdemUltimoPeriodoTurma == $iOrdemPeriodoSelecionado) {
    $oTurmaEtapa->lUltimoPeriodo = true;
  }
  
  /**
   * Buscamos os dados do aluno e suas avali��es para o periodo selecionado.
   * Organizamos a estrutura das avalia��es dos alunos pelo c�digo da regencia
   */
  foreach ($oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapa) as $oMatricula) {
    
    
    $oDadosAluno                 = new stdClass();
    $oDadosAluno->iMatricula     = $oMatricula->getCodigo();
    $oDadosAluno->sNome          = abreviar($oMatricula->getAluno()->getNome(), 22, true);
    $oDadosAluno->iCodigoAluno   = $oMatricula->getAluno()->getCodigoAluno();
    $oDadosAluno->sSituacao      = $oMatricula->getSituacao();
    $oDadosAluno->oDtMatricula   = $oMatricula->getDataMatricula();
    $oDadosAluno->iClassificacao = $oMatricula->getNumeroOrdemAluno();
    $oDadosAluno->aAvaliacao     = array();
    $oDadosAluno->iTotalFaltas   = 0; 

    db_inicio_transacao();
    
    $oDiarioDeClasse = $oMatricula->getDiarioDeClasse();
    
    $iContDisciplinas = 0;
    
    foreach ($oTurma->getDisciplinasPorEtapa($oEtapa) as $oRegencia) {

      $iContDisciplinas ++;
      $oDisciplinaDiario        = $oDiarioDeClasse->getDisciplinasPorRegencia($oRegencia, $oAvaliacaoPeriodica);
      $oAvaliacaoAproveitamento = $oDisciplinaDiario->getAvaliacoesPorOrdem($oAvaliacaoPeriodica->getOrdemSequencia());
      
      $oAvaliacao = new stdClass();
      $oAvaliacao->iRegencia       = $oRegencia->getCodigo();
      $oAvaliacao->sRegencia       = $oRegencia->getDisciplina()->getNomeDisciplina();
      $oAvaliacao->sRegenciaAbrev  = $oRegencia->getDisciplina()->getAbreviatura();
      $oAvaliacao->iFaltas         = $oAvaliacaoAproveitamento->getTotalFaltas() + $oAvaliacaoAproveitamento->getFaltasAbonadas();
      $oAvaliacao->mAproveitamento = $oAvaliacaoAproveitamento->getValorAproveitamento();
      $oAvaliacao->lAtingiuMinimo  = $oAvaliacaoAproveitamento->temAproveitamentoMinimo();
      $oAvaliacao->lNotaExterna    = $oAvaliacaoAproveitamento->isAvaliacaoExterna();
      $oAvaliacao->lAmparado       = $oAvaliacaoAproveitamento->isAmparado();
      $oAvaliacao->sTipoAmparo     = 'AMP';

      if ($oAvaliacao->lAmparado && $oDisciplinaDiario->getAmparo()->getCodigoConvencaoAmparo() != '') {
        $oAvaliacao->sTipoAmparo = $oDisciplinaDiario->getAmparo()->getConvencao()->getAbreviatura();
      }
      
      
      $oAvaliacao->mNotaParcial    = $oDisciplinaDiario->getNotaParcial($oAvaliacaoPeriodica);
      $oAvaliacao->sTipoAvaliacao  = $oAvaliacaoAproveitamento->getElementoAvaliacao()->getFormaDeAvaliacao()->getTipo();

      unset($oAvaliacaoAproveitamento);
      /**
       * Como uma turma pode ter mais de 10 disciplinas, devemos quebrar p�gina e continuar imprimindo as disciplinas 
       * restantes. Neste bloco definimos at� tr�s p�ginas de para uma turma com at� 30 disciplinas (N�o sendo alterada
       * a configura��o padr�o de 10 disciplinas por p�gina)
       * - 1� p�gina : de 1  � 10 disciplinas
       * - 2� p�gina : de 11 � 20 disciplinas
       * - 3� p�gina : de 21 � 30 disciplinas
       */
      if ($iContDisciplinas <= $oConfigRelatorio->iMaximoDisciplinaPagina) {
        $oDadosAluno->aAvaliacao[0][$oRegencia->getCodigo()] = $oAvaliacao;
      } else if ($iContDisciplinas > $oConfigRelatorio->iMaximoDisciplinaPagina && 
                 $iContDisciplinas <= ($oConfigRelatorio->iMaximoDisciplinaPagina * 2)) {
        $oDadosAluno->aAvaliacao[1][$oRegencia->getCodigo()] = $oAvaliacao;
      } else {
        $oDadosAluno->aAvaliacao[2][$oRegencia->getCodigo()] = $oAvaliacao;
      }
      
      $oDadosAluno->iTotalFaltas  += $oAvaliacao->iFaltas;
    }
    
    $oTurmaEtapa->aAlunos[] = $oDadosAluno;
    MatriculaRepository::removerMatricula($oMatricula);
    db_fim_transacao();
    
  }
  
  $aTurmas[] = $oTurmaEtapa;
  TurmaRepository::removerTurma($oTurma);
  EtapaRepository::removerEtapa($oEtapa);
}

$oPdf = new PDF("L");
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(true);
$oPdf->SetFillColor(215);
$oPdf->SetMargins(8, 10);
$oPdf->SetLineWidth(0);

foreach ($aTurmas as $oTurmaEtapa) {
  
  $head1 = "Conselho de Classe";
  $head2 = "Curso: {$oTurmaEtapa->sCurso}";
  $head3 = "Turma: {$oTurmaEtapa->sTurma}";
  $head4 = "Calend�rio: {$oTurmaEtapa->sCalendario}";
  $head5 = "Etapa: {$oTurmaEtapa->sEtapa}";
  $head6 = "Turno: {$oTurmaEtapa->sTurno}";
  $head7 = "Per�odo: {$oTurmaEtapa->sPeriodo}";
  $head8 = "Forma de Avalia��o: {$oTurmaEtapa->sNomeFormaAvaliacao}";
  
  $lPrimeiraPagina = true;
  $iPaginas        = count($oTurmaEtapa->aPaginas);
  
  for ($iPagina = 0; $iPagina < $iPaginas; $iPagina ++) {
    
    /**
     * A cada p�gina, calcula em tempo de excucao o tamanho de vari�veis necess�rias para o calculo 
     * de algumas colunas do relat�rio. Essas vari�veis ser�o recalcul�das a cada turma e pagina emitida 
     */
    calculaTamanhoDeCelulasDinamicas($oPdf, $oTurmaEtapa, $oConfigRelatorio, $iPagina);    
    
    $iAlunosImpressoPagina = 0;
    $iAlunosTurma          = count($oTurmaEtapa->aAlunos);
    
    $iTotalDisciplina      = $oTurmaEtapa->iTotalDisciplina;     
    $iLarguraCelulaParecer = $oTurmaEtapa->iLarguraCelulaParecer;
    $iLarguraDisciplina    = $oTurmaEtapa->iLarguraDisciplina;   
    $iLarguraAvaliacao     = $iLarguraDisciplina - 5;
    
    foreach ($oTurmaEtapa->aAlunos as $oAluno) {

      if ($lPrimeiraPagina ) {
        
        $lPrimeiraPagina = false;
        adicionaHeader($oPdf, $oTurmaEtapa, $oConfigRelatorio, $iPagina);
      }
      
      $iAlunosImpressoPagina ++;
      if ($iAlunosImpressoPagina > $oConfigRelatorio->iAlunosPorPagina) {
      
        $iAlunosImpressoPagina = 1;
        montaQuadroLegendaAssinatura($oPdf, $oConfigRelatorio, $oTurmaEtapa->lUltimoPeriodo, $oTurmaEtapa);
        if ($iAlunosImpressoPagina < $iAlunosTurma) {
          adicionaHeader($oPdf, $oTurmaEtapa, $oConfigRelatorio, $iPagina);
        }
      }
      
      if (!$oConfigRelatorio->lTrocaTurma && $oAluno->sSituacao == 'TROCA DE TURMA') {
        continue;
      }
      
      $oPdf->SetFont("arial", '', 7);
      $iLarguraCelulaNome = $oConfigRelatorio->iColunaNumero + $oTurmaEtapa->iColunaNome;
      if ($oConfigRelatorio->lClassificacaoAlunoTurma) {
        
        $iLarguraCelulaNome = $oTurmaEtapa->iColunaNome;
        $oPdf->Cell($oConfigRelatorio->iColunaNumero, $oConfigRelatorio->iAlturaLinha, $oAluno->iClassificacao, 1, 0, 'R');
      }
      $oPdf->SetFont("arial", '', 7);
      $oPdf->Cell($iLarguraCelulaNome, $oConfigRelatorio->iAlturaLinha, $oAluno->sNome, 1);
      
      $sSituacaoAbreviada = buscaAbreviaturaSituacao($oAluno->sSituacao);
      $oPdf->Cell($oConfigRelatorio->iColunaNumero, $oConfigRelatorio->iAlturaLinha, $sSituacaoAbreviada,   1, 0, 'C');
      $oPdf->Cell($oConfigRelatorio->iColunaCodigo, $oConfigRelatorio->iAlturaLinha, $oAluno->iCodigoAluno, 1, 0, 'C');
     
      if (!$oTurmaEtapa->lUltimoPeriodo) {
        imprimeCelulasParecer($oPdf, $iLarguraCelulaParecer, $oConfigRelatorio);
      }
      foreach ($oAluno->aAvaliacao[$iPagina] as $oAvaliacao) {
        
        $mAvaliacao = $oAvaliacao->mAproveitamento->getAproveitamento();
        $oPdf->SetFont("arial", '', $oConfigRelatorio->iFonteAvaliacao);
        
        if ($oAvaliacao->sTipoAvaliacao == 'PARECER') {
          
          $mAvaliacao = 'PARECER';
          $oAvaliacao->lAtingiuMinimo = true;
        } 
        
        $mAvaliacao = ArredondamentoNota::formatar($mAvaliacao, $oTurmaEtapa->iAnoCalendario);  
        if ($oAvaliacao->lNotaExterna) {
          $mAvaliacao .= "*";
        }
        
        if ($oAvaliacao->lAmparado) {
          
          $mAvaliacao = $oAvaliacao->sTipoAmparo;
          $oAvaliacao->lAtingiuMinimo = true;
        }
        
        if (!$oAvaliacao->lAtingiuMinimo) {
          $oPdf->SetFont("arial", 'b', $oConfigRelatorio->iFonteAvaliacao);
        }
        

        if ($oAvaliacao->sTipoAvaliacao == 'NOTA' && $oConfigRelatorio->lCalculaMediaParcial) {
          
          $oPdf->Cell($iLarguraAvaliacao/2, $oConfigRelatorio->iAlturaLinha, $mAvaliacao, 1, 0, "C");
          $oPdf->SetFont("arial", '', $oConfigRelatorio->iFonteAvaliacao); //Nota parcial n�o fica em negrito
          $oPdf->Cell($iLarguraAvaliacao/2, $oConfigRelatorio->iAlturaLinha, $oAvaliacao->mNotaParcial, 1, 0, "C");
        } else {
          $oPdf->Cell($iLarguraAvaliacao, $oConfigRelatorio->iAlturaLinha, $mAvaliacao, 1, 0, "C");
        }
        $oPdf->SetFont("arial", '', $oConfigRelatorio->iFonteAvaliacao);
        
        $oPdf->Cell(5, $oConfigRelatorio->iAlturaLinha, $oAvaliacao->iFaltas, 1, 0, "C");
      }
      
      /**
       * Imprime colunas de avalia��o vazia
       */
      if ($iTotalDisciplina < $oConfigRelatorio->iMaximoDisciplinaPagina) {
        imprimeQuadroAvaliacaoVazio ($oPdf, $oTurmaEtapa, $oTurmaEtapa->iTotalDisciplina, $oConfigRelatorio, false);
      }
      $oPdf->Cell($oConfigRelatorio->iColunaNumero, $oConfigRelatorio->iAlturaLinha, $oAluno->iTotalFaltas, 1, 1, 'C');
      
    }
    
    /**
     * Imprime linhas em branco para fechar o total de aluno por p�gina 
     */
    if ($iAlunosImpressoPagina < $oConfigRelatorio->iAlunosPorPagina) {
      
      for ($i = $iAlunosImpressoPagina; $i < $oConfigRelatorio->iAlunosPorPagina; $i++) {
        imprimeLinhaEmBranco($oPdf, $oConfigRelatorio, $oTurmaEtapa);
      }
    }
    montaQuadroLegendaAssinatura($oPdf, $oConfigRelatorio, $oTurmaEtapa->lUltimoPeriodo, $oTurmaEtapa);
    $lPrimeiraPagina = true;
  }
  unset($oTurmaEtapa);
}


/**
 * Renderiza quadro das legendas
 * @param FPDF $oPdf
 * @param stdClass $oConfigRelatorio
 */
function montaQuadroLegendaAssinatura(FPDF $oPdf, $oConfigRelatorio, $lUltimoPeriodo, $oTurmaEtapa) {

  /**
   * Soma com base na configura��o do layout a largura do quadro 
   */
  $iLarguraQuadro  = $oConfigRelatorio->iLarguraTotalDisciplinas;
  $iLarguraQuadro += $oConfigRelatorio->iColunaCodigo ;
  $iLarguraQuadro += $oTurmaEtapa->iColunaNome;
  $iLarguraQuadro += ($oConfigRelatorio->iColunaNumero*3);
  if (!$lUltimoPeriodo) {
    $iLarguraQuadro += $oConfigRelatorio->iColunaPareceres;
  }
  
  $iXInicial = $oPdf->GetX();
  $iYInicial = $oPdf->GetY();
  $oPdf->Rect($oPdf->GetX(), $oPdf->GetY(), $iLarguraQuadro, 13);
  
  if ($oConfigRelatorio->comLegenda) {
  
    $sLegendasCabe�alho  = "N�: N�mero da classifica��o do aluno;  S: Sa�da;  Ft.: N� de faltas na disciplina;";
    $sLegendasCabe�alho .= "  NT: Nota;  NP: Nota parcial;  TF: Total de faltas no per�odo.";
    
    $sLegendasSituacao  = montaLegendaSituacoes();
    
    $oPdf->SetY($iYInicial+1);
    $oPdf->SetFont("arial", 'b', 5);
    
    $iAlturaLinhaLegenda = $oConfigRelatorio->iAlturaLinha - 1.5;
    
    $oPdf->Cell(20, $iAlturaLinhaLegenda, "Legendas Cabe�alho: ");
    $oPdf->SetFont("arial", '', 5);
    $oPdf->MultiCell(175, $iAlturaLinhaLegenda, $sLegendasCabe�alho);
    
    $oPdf->SetFont("arial", 'b', 5);
    $oPdf->Cell(20, $iAlturaLinhaLegenda, "Legendas Situa��o: ");
    $oPdf->SetFont("arial", '', 5);
    $oPdf->MultiCell(170, $iAlturaLinhaLegenda, $sLegendasSituacao);
  }
  
  /**
   * Assinatura
  */
  $oPdf->SetFont("arial", 'b', 5);
  $oPdf->SetXY($iXInicial+200, $iYInicial+9);
  $oPdf->Cell(81, $oConfigRelatorio->iAlturaLinha, "Regente Conselheiro", 0, 0, 'C');
  $oPdf->Line($iXInicial+200, $iYInicial+9, $iXInicial+$iLarguraQuadro, $iYInicial+9);
}


/**
 * Realiza o calculo, em tempo de execu��o, de vari�veis de controle  
 * @param FPDF     $oPdf
 * @param stdClass $oTurmaEtapa
 * @param stdClass $oConfigRelatorio
 * @param integer  $iPagina
 */
function calculaTamanhoDeCelulasDinamicas(FPDF $oPdf, $oTurmaEtapa, $oConfigRelatorio, $iPagina) {
  
  $oTurmaEtapa->iTotalDisciplina   = count($oTurmaEtapa->aPaginas[$iPagina]); 
  $oTurmaEtapa->iLarguraDisciplina = $oConfigRelatorio->iLarguraTotalDisciplinas / $oConfigRelatorio->iMaximoDisciplinaPagina;
  
  $oTurmaEtapa->iLarguraCelulaParecer = $oConfigRelatorio->iColunaPareceres / 3;
  
  if ($oTurmaEtapa->lUltimoPeriodo && !$oTurmaEtapa->lJaCalculado) {
    
    $oTurmaEtapa->lJaCalculado          = true;
    $oTurmaEtapa->iColunaNome     += $oConfigRelatorio->iColunaPareceres;
    $oTurmaEtapa->iLarguraCelulaParecer = 0;
  }
  
}

/**
 * Adiciona o cabe�alho
 * @param FPDF     $oPdf
 * @param stdClass $oTurmaEtapa   
 * @param stdClass $oConfigRelatorio
 * @param integer  $iPagina
 */
function adicionaHeader(FPDF $oPdf, $oTurmaEtapa, $oConfigRelatorio, $iPagina) {
  
  $oPdf->AddPage();
  $oPdf->SetFont("arial", 'b', 7);
  /**
   * Primeira linha do cabe�alho
   */
  $iLargura = ($oConfigRelatorio->iColunaNumero * 2) + $oTurmaEtapa->iColunaNome + $oConfigRelatorio->iColunaCodigo;
  if ($oConfigRelatorio->lClassificacaoAlunoTurma) {
    
    $iLargura = $oConfigRelatorio->iColunaNumero + $oTurmaEtapa->iColunaNome + $oConfigRelatorio->iColunaCodigo;
    $oPdf->Cell($oConfigRelatorio->iColunaNumero, $oConfigRelatorio->iAlturaLinha, '', "TLR");
  } 
  $oPdf->Cell($iLargura, $oConfigRelatorio->iAlturaLinha, '', 1);
  
  if (!$oTurmaEtapa->lUltimoPeriodo) {
    $oPdf->Cell($oConfigRelatorio->iColunaPareceres, $oConfigRelatorio->iAlturaLinha, 'Pareceres', 1, 0, "C");
  }
  
  $iTotalDisciplina   = $oTurmaEtapa->iTotalDisciplina;
  $iLarguraDisciplina = $oTurmaEtapa->iLarguraDisciplina;
  
  $iEixoY = $oPdf->GetY();
  imprimeLinhaSeparadora ($oPdf, $oConfigRelatorio);
  foreach ($oTurmaEtapa->aPaginas[$iPagina] as $oRegencia) {
    
    $sAbreviatura = $oRegencia->getDisciplina()->getAbreviatura();
    $oPdf->Cell($iLarguraDisciplina, $oConfigRelatorio->iAlturaLinha, $sAbreviatura, 1, 0, "C");
    imprimeLinhaSeparadora ($oPdf, $oConfigRelatorio);
  }
  
  /**
   * Imprime colunas de avalia��o vazia
   */
  if ($iTotalDisciplina < $oConfigRelatorio->iMaximoDisciplinaPagina) {
    imprimeQuadroAvaliacaoVazio ($oPdf, $oTurmaEtapa, $oTurmaEtapa->iTotalDisciplina, $oConfigRelatorio, true);
  }

  /**
   * Coluna total de faltas.
   */
  $oPdf->Cell($oConfigRelatorio->iColunaNumero, $oConfigRelatorio->iAlturaLinha, '', "TLR");
  $oPdf->ln();
  
  /**
   * Segunda linha do cabe�alho
   */
  $iLargura = $oConfigRelatorio->iColunaNumero  + $oTurmaEtapa->iColunaNome;
  if ($oConfigRelatorio->lClassificacaoAlunoTurma) {
    
    $iLargura = $oTurmaEtapa->iColunaNome;
    $oPdf->Cell($oConfigRelatorio->iColunaNumero, $oConfigRelatorio->iAlturaLinha, 'N�', "BLR", 0, 'C');
  } 
  $oPdf->Cell($iLargura, $oConfigRelatorio->iAlturaLinha, 'Nome do Aluno', 1);
  $oPdf->Cell($oConfigRelatorio->iColunaNumero, $oConfigRelatorio->iAlturaLinha, 'S', 1, 0, 'C');
  $oPdf->Cell($oConfigRelatorio->iColunaCodigo, $oConfigRelatorio->iAlturaLinha, 'C�digo', 1, 0, 'C');
  
  if (!$oTurmaEtapa->lUltimoPeriodo) {
    imprimeCelulasParecer($oPdf, $oTurmaEtapa->iLarguraCelulaParecer, $oConfigRelatorio);
  }  
  
  
  $sTipo = $oTurmaEtapa->sFormaAvaliacao;
  if ($oTurmaEtapa->sFormaAvaliacao == 'NOTA') {
    $sTipo = 'NT';
  }
  
  foreach ($oTurmaEtapa->aPaginas[$iPagina] as $oRegencia) {
  
    $iLarguraAvaliacao = $iLarguraDisciplina - 5;
    if ($oTurmaEtapa->sFormaAvaliacao == 'NOTA' && $oConfigRelatorio->lCalculaMediaParcial) {
      
      $oPdf->Cell($iLarguraAvaliacao/2, $oConfigRelatorio->iAlturaLinha, 'NT', 1, 0, "C");
      $oPdf->Cell($iLarguraAvaliacao/2, $oConfigRelatorio->iAlturaLinha, 'NP', 1, 0, "C");
    } else {
      $oPdf->Cell($iLarguraAvaliacao, $oConfigRelatorio->iAlturaLinha, $sTipo, 1, 0, "C");
    }    
    $oPdf->Cell(5, $oConfigRelatorio->iAlturaLinha, 'Ft.', 1, 0, "C");
  
  }
  
  /**
   * Imprime colunas de avalia��o vazia 2� linha do cabe�alho
   */
  if ($iTotalDisciplina < $oConfigRelatorio->iMaximoDisciplinaPagina) {
    imprimeQuadroAvaliacaoVazio ($oPdf, $oTurmaEtapa, $oTurmaEtapa->iTotalDisciplina, $oConfigRelatorio, false);
  }
  $oPdf->Cell($oConfigRelatorio->iColunaNumero, $oConfigRelatorio->iAlturaLinha, 'TF', "BLR", "C");
  $oPdf->ln();
}


/**
 * Imprime vazio os quadros das avalia��es
 * @param FPDF     $oPdf
 * @param stdClass $oTurmaEtapa      -> Dados da turma
 * @param integer  $iTotalDisciplina -> N�mero de disciplina da p�gina atual
 * @param stdClass $oConfigRelatorio -> Objeto de configura��o
 * @param boolean  $lPrimeiraLinha   -> Se � a primeira linha do cabe�alho
 */
function imprimeQuadroAvaliacaoVazio (FPDF $oPdf, $oTurmaEtapa, $iTotalDisciplina, $oConfigRelatorio, 
                                      $lPrimeiraLinha = true) {

  for ($i = $iTotalDisciplina; $i < $oConfigRelatorio->iMaximoDisciplinaPagina; $i++) {

    if ($lPrimeiraLinha) {

      $oPdf->Cell($oTurmaEtapa->iLarguraDisciplina, $oConfigRelatorio->iAlturaLinha, '', 1, 0, "C");
      imprimeLinhaSeparadora ($oPdf, $oConfigRelatorio);
    } else {

      $iLarguraAvaliacao = $oTurmaEtapa->iLarguraDisciplina - 5;
      
      if ($oTurmaEtapa->sFormaAvaliacao == 'NOTA' && $oConfigRelatorio->lCalculaMediaParcial) {
      
        $oPdf->Cell($iLarguraAvaliacao/2, $oConfigRelatorio->iAlturaLinha, '', 1);
        $oPdf->Cell($iLarguraAvaliacao/2, $oConfigRelatorio->iAlturaLinha, '', 1);
      } else {
        $oPdf->Cell($iLarguraAvaliacao, $oConfigRelatorio->iAlturaLinha, '', 1);
      }
      $oPdf->Cell(5,  $oConfigRelatorio->iAlturaLinha, '', 1);
    }
  }
}

/**
 * Imprime a linha vertical que separa as disciplinas
 * @param FPDF     $oPdf
 * @param stdClass $oConfigRelatorio
 */
function imprimeLinhaSeparadora (FPDF $oPdf, $oConfigRelatorio) {
  
  $oPdf->SetLineWidth(0.3);
  $oPdf->Line($oPdf->GetX(), $oPdf->GetY(), $oPdf->GetX(), $oConfigRelatorio->iAlturaLine);
  $oPdf->SetLineWidth(0);
} 

/**
 * Imprime celulas para lan�ar o parecer
 * @param FPDF     $oPdf
 * @param integer  $iLarguraCelulaParecer
 * @param stdClass $oConfigRelatorio
 */
function imprimeCelulasParecer(FPDF $oPdf, $iLarguraCelulaParecer, $oConfigRelatorio) {
  
  $oPdf->Cell($iLarguraCelulaParecer, $oConfigRelatorio->iAlturaLinha, '', 1);
  $oPdf->Cell($iLarguraCelulaParecer, $oConfigRelatorio->iAlturaLinha, '', 1);
  $oPdf->Cell($iLarguraCelulaParecer, $oConfigRelatorio->iAlturaLinha, '', 1);
}

/**
 * Imprime linhas em branco para fechar o numero maximo de alunos
 * @param FPDF     $oPdf
 * @param stdClass $oConfigRelatorio
 * @param stdClass  $oTurmaEtapa
 */
function imprimeLinhaEmBranco(FPDF $oPdf, $oConfigRelatorio, $oTurmaEtapa) {
  
  $iLarguraCelulaNome = $oConfigRelatorio->iColunaNumero + $oTurmaEtapa->iColunaNome;
  if ($oConfigRelatorio->lClassificacaoAlunoTurma) {
    
    $iLarguraCelulaNome = $oTurmaEtapa->iColunaNome;
    $oPdf->Cell($oConfigRelatorio->iColunaNumero, $oConfigRelatorio->iAlturaLinha, '', 1);
  }
  $oPdf->Cell($iLarguraCelulaNome, $oConfigRelatorio->iAlturaLinha, '', 1);
  $oPdf->Cell($oConfigRelatorio->iColunaNumero, $oConfigRelatorio->iAlturaLinha, '',   1);
  $oPdf->Cell($oConfigRelatorio->iColunaCodigo, $oConfigRelatorio->iAlturaLinha, '', 1);
 
  if (!$oTurmaEtapa->lUltimoPeriodo) {
    imprimeCelulasParecer($oPdf, $oTurmaEtapa->iLarguraCelulaParecer, $oConfigRelatorio);
  }
  imprimeQuadroAvaliacaoVazio ($oPdf, $oTurmaEtapa, 0, $oConfigRelatorio, false);
  $oPdf->Cell($oConfigRelatorio->iColunaNumero, $oConfigRelatorio->iAlturaLinha, '', 1, 1);
  
}


/**
 * Retorna uma abreviatura para a situacao do aluno
 * @param string $sSituacaoBusca
 * @return string $sSituacaoRetorno
 */
function buscaAbreviaturaSituacao($sSituacaoBusca) {
  
  $sSituacaoRetorno = '';
  
  foreach (getSituacoes() as $sAbrev => $sSituacao) {
    
    if ($sSituacaoBusca == $sSituacao) {
      
      $sSituacaoRetorno = $sAbrev;
      break;
    }
  }
  return $sSituacaoRetorno;
}

/**
 * Retorna a legenda das Situa��es tratadas no relat�rio 
 * @return string
 */
function montaLegendaSituacoes() {
  
  $sLegenda = '';
  foreach (getSituacoes() as $sAbrev => $sSituacao) {
    
    $sLegenda .= "{$sAbrev}: $sSituacao;  ";
  }
  return $sLegenda;
}

/**
 * Array com as situa��es tratadas no relat�rio
 * @return multitype:string
 */
function getSituacoes() {
  
  /**
   * Array com as situa��e da matricula do aluno indexado pela abreviatura
   */
  $aSituacoes       = array();
  $aSituacoes['MT'] = 'MATRICULA TRANCADA';
  $aSituacoes['IN'] = 'MATRICULA INDEFERIDA';
  $aSituacoes['MI'] = 'MATRICULA INDEVIDA';
  $aSituacoes['TR'] = 'TRANSFERIDO REDE';
  $aSituacoes['TF'] = 'TRANSFERIDO FORA';
  $aSituacoes['TT'] = 'TROCA DE TURMA';
  $aSituacoes['TM'] = 'TROCA DE MODALIDADE';
  $aSituacoes['C']  = 'CANCELADO';
  $aSituacoes['E']  = 'EVADIDO';
  $aSituacoes['F']  = 'FALECIDO';

  return $aSituacoes;
}

/**
 * Abrevia o nome do aluno
 * @todo Mover para aluno
 * @param string $nome
 * @param integer $max
 * @param string $substr
 */
function abreviar($nome, $max, $substr=false) {

  if(strlen(trim($nome))>$max){
     
    $strinv = strrev(trim($nome));
    $ultnome = substr($strinv,0,strpos($strinv," "));
    $ultnome = strrev($ultnome);
    $nome = strrev($strinv);
    $prinome = substr($nome,0,strpos($nome," "));
    $nomes = strtok($nome, " ");
    $iniciais = "";
     
    while($nomes):
    if(($nomes == 'E') || ($nomes == 'DE') || ($nomes == 'DOS') ||
    ($nomes == 'DAS') || ($nomes == 'DA') || ($nomes == 'DO')){
      $iniciais .= " ".$nomes;
      $nomes = strtok(" ");
    }elseif (($nomes == $ultnome) || ($nomes == $prinome)){
      $nome = "";
      $nomes = strtok(" ");
    }else{
      $iniciais .= " ".$nomes[0].".";
      $nomes = strtok(" ");
    }
    endwhile;
     
    $nome =  $prinome;
    $nome .= $iniciais;
    $nome .= " ".$ultnome;
  }

  if (!$substr){
    return trim($nome);
  }else{
    return substr(trim($nome),0,20);
  }
}
$oPdf->Output();
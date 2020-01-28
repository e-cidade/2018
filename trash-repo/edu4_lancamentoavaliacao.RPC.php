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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/JSON.php");
require_once ("std/db_stdClass.php");
require_once ("std/DBDate.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/exceptions/BusinessException.php");
require_once ("libs/exceptions/DBException.php");
require_once ("libs/exceptions/FileException.php");
require_once ("libs/exceptions/ParameterException.php");

$iEscola           = db_getsession("DB_coddepto");
$oJson             = new Services_JSON();
$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->dados   = array();
$oRetorno->status  = 1;
$oRetorno->message = '';

$lProfessorLogado            = false;
$lBloqueiaAlteracaoAvaliacao = false;

/**
 * Buscamos o parametro ed233_bloqueioalteracaoavaliacao verificando se a alteracao de notas das avaliacoes deve ser
 * permitida ou nao
 */
$oDaoEduParametros   = new cl_edu_parametros();
$sWhereEduParametros = "ed233_i_escola = {$iEscola}";
$sSqlEduParametros   = $oDaoEduParametros->sql_query_file(null, "ed233_bloqueioalteracaoavaliacao", null, $sWhereEduParametros);
$rsEduParametros     = $oDaoEduParametros->sql_record($sSqlEduParametros);

if ($oDaoEduParametros->numrows > 0) {
  
  $sBloqueio                   = db_utils::fieldsMemory($rsEduParametros, 0)->ed233_bloqueioalteracaoavaliacao;
  $lBloqueiaAlteracaoAvaliacao = $sBloqueio == 't' ? true : false;
}

$iCodigoUsuario = db_getsession("DB_id_usuario");
$oDocente       = DocenteRepository::getDocenteLogado($iCodigoUsuario);
$oEscola = EscolaRepository::getEscolaByCodigo($iEscola);
if (!empty($oDocente) && count($oDocente->getTurmas()) > 0) {
  $lProfessorLogado = true;
}

$oRetorno->lProfessorLogado = $lProfessorLogado;
switch($oParam->exec) {

  case 'getDadosDiario':

     $aCalendarios = array();
     if ($lProfessorLogado) {
       $aTurmas = $oDocente->getTurmasPorEscola($oEscola);
     } else {
       $aTurmas = TurmaRepository::getTurmaByEscola($iEscola);
     }

     if (empty($aTurmas)) {
       $aTurmas = array();
     }

     foreach ($aTurmas as $oTurma) {

       $oCalendario      = null;
       $oCalendarioTurma = $oTurma->getCalendario();
       if ($oCalendarioTurma->isPassivo()) {
         continue;
       }
       if ($oParam->lProgressaoParcial && count($oTurma->getAlunosProgressaoParcial()) == 0) {
         continue;
       }
       if (!$oParam->lProgressaoParcial) {
         if ($oTurma->getTipoDaTurma() == 6 || $oTurma->getVagasOcupadas() == 0) {
           continue;
         }
       }
       /**
        * Pesquisamos se j� nao existe o calendario no array.
        */
       $lTemCalendario = false;
       foreach ($aCalendarios as $oCalendario) {

         if ($oCalendario->iCalendario === $oCalendarioTurma->getCodigo()) {

           $lTemCalendario = true;
           break;
         }
       }


       if (!$lTemCalendario) {

          $oCalendario                       =  new stdClass();
          $oCalendario->iCalendario          = $oCalendarioTurma->getCodigo();
          $oCalendario->sDescricaoCalendario = urlencode($oCalendarioTurma->getDescricao());
          $oCalendario->iAnoCalendario       = $oCalendarioTurma->getAnoExecucao();
          $oCalendario->sIdCalendario        = md5(uniqid(rand()));
          $oCalendario->aEnsinos             = array();
          $aCalendarios[]                    = $oCalendario;
        }
        /**
         * Verificamos o nivel de ensino
         */
        $oBaseTurma   = $oTurma->getBaseCurricular();
        $oEnsinoTurma = $oBaseTurma->getCurso()->getEnsino();
        $oEnsino      = null;
        $lTemEnsino   = false;
        foreach ($oCalendario->aEnsinos as $oEnsino) {

          if ($oCalendario->iCalendario == $oCalendarioTurma->getCodigo() &&
              $oEnsino->iEnsino == $oEnsinoTurma->getCodigo()) {
            $lTemEnsino = true;
            break;
          }
        }

        if (!$lTemEnsino) {

          $oEnsino                   = new stdClass();
          $oEnsino->iEnsino          = $oEnsinoTurma->getCodigo();
          $oEnsino->sDescricaoEnsino = urlencode($oEnsinoTurma->getNome());
          $oEnsino->sIdEnsino        = md5(uniqid(rand()));
          $oEnsino->aBases           = array();
          $oCalendario->aEnsinos[]   = $oEnsino;
        }

        /*
         * Verificamos as bases do nivel
         */
        $oBaseCurricular = null;
        $lTemBase        = false;
        foreach ($oEnsino->aBases as $oBaseCurricular) {

          if ($oCalendario->iCalendario == $oCalendarioTurma->getCodigo() &&
              $oBaseCurricular->iBase == $oBaseTurma->getCodigoSequencial() &&
              $oEnsino->iEnsino == $oEnsinoTurma->getCodigo()) {
            $lTemBase = true;
            break;
          }
        }

        if (!$lTemBase) {

          $oBaseCurricular                 = new stdClass();
          $oBaseCurricular->iBase          = $oBaseTurma->getCodigoSequencial();
          $oBaseCurricular->sDescricaoBase = urlencode($oBaseTurma->getDescricao());
          $oBaseCurricular->aEtapas        = array();
          $oEnsino->aBases[]               = $oBaseCurricular;
          $oBaseCurricular->sIdBase        = md5(uniqid(rand()));
        }

        /*
         * Verificamos as etapas da base
         */
        $oEtapa    = null;
        $lTemEtapa = false;
        foreach ($oTurma->getEtapas() as $oEtapaTurma) {

          $oEtapaTurma = $oEtapaTurma->getEtapa();
          foreach ($oBaseCurricular->aEtapas as $oEtapa) {

            if ($oEtapa->iEtapa == $oEtapaTurma->getCodigo() &&
                $oCalendario->iCalendario == $oCalendarioTurma->getCodigo() &&
                $oBaseCurricular->iBase == $oBaseTurma->getCodigoSequencial() &&
                $oEnsino->iEnsino == $oEnsinoTurma->getCodigo()) {

              $lTemEtapa = true;
              break;
            }
          }

        if (!$lTemEtapa) {

          $oEtapa                     = new stdClass();
          $oEtapa->iEtapa             = $oEtapaTurma->getCodigo();
          $oEtapa->sDescricaoEtapa    = urlencode($oEtapaTurma->getNome());
          $oEtapa->aTurmas            = array();
          $oEtapa->sIdEtapa           = md5(uniqid(rand()));
          $oBaseCurricular->aEtapas[] = $oEtapa;
        }

        $lTemTurma = false;
        foreach ($oEtapa->aTurmas as $oTurmaEtapa) {

          if ($oTurmaEtapa->iTurma == $oTurma->getCodigo()) {

            $lTemTurma = true;
            break;
          }
        }
        if (!$lTemTurma) {

          $oTurmaAdicionar                    = new stdClass();
          $oTurmaAdicionar->iTurma            = $oTurma->getCodigo();
          $oTurmaAdicionar->sDescricaoTurma   = urlEncode($oTurma->getDescricao());
          $oTurmaAdicionar->sIdTurma          = md5(uniqid(rand()));
          $oTurmaAdicionar->lEncerrada        = $oTurma->encerradaNaEtapa($oEtapaTurma);
          $oTurmaAdicionar->lEncerradaParcial = $oTurma->encerradaParcial($oEtapaTurma);
          $oTurmaAdicionar->lClassificada     = $oTurma->isClassificada();
          $oEtapa->aTurmas[]                  = $oTurmaAdicionar;
        }
      }
    }
    /**
     * Busca os calend�rios
     */
    $oRetorno->dados = $aCalendarios;

    break;

  case 'getAlunosMatriculados':

    unset($_SESSION["oMatricula"]);
    $oAlunosMatriculados = new Turma($oParam->iCodigoTurma);
    $aAlunosMatriculados = $oAlunosMatriculados->getAlunosMatriculadosNaTurmaPorSerie(new Etapa($oParam->iEtapa));
    $aDadosMatricula     = array();
    foreach ($aAlunosMatriculados as $oMatricula) {

      if (isset($oParam->iMostrarTrocaTurma) && $oParam->iMostrarTrocaTurma == 1) {

        if ($oMatricula->getSituacao() == "TROCA DE TURMA") {
          continue;
        }
      }
      $oDadosMatricula                   = new stdClass();
      $oDadosMatricula->iCodigo          = $oMatricula->getCodigo();
      $oDadosMatricula->iEtapa           = $oMatricula->getEtapaDeOrigem()->getCodigo();
      $oDadosMatricula->iMatricula       = $oMatricula->getMatricula();
      $oDadosMatricula->iOrdem           = $oMatricula->getNumeroOrdemAluno();
      $oDadosMatricula->sNome            = urlencode($oMatricula->getAluno()->getNome());
      $oDadosMatricula->dtDataMatricula  = $oMatricula->getDataMatricula()->convertTo(DBDate::DATA_PTBR);
      $oDadosMatricula->sSituacao        = urlencode($oMatricula->getSituacao());
      $oDadosMatricula->lAvaliadoParecer = $oMatricula->isAvaliadoPorParecer();
      $aDadosMatricula[]                 = $oDadosMatricula;
    }
    $oRetorno->dados = $aDadosMatricula;
    break;

    case 'periodosAvaliacao':

    $aPeriodosAvaliacaoRetorno = array();

    try {

      db_inicio_transacao();
      unset($_SESSION["oMatricula"]);
      $oDaoParametro   = db_utils::getDao('edu_parametros');
      $sWhereParametro = "ed233_i_escola = " .db_getsession('DB_coddepto') ;
      $sSqlParametro   = $oDaoParametro->sql_query_file(null, ' ed233_deslocamentocursor ', null, $sWhereParametro);
      $rsParametro     = $oDaoParametro->sql_record($sSqlParametro);
      $oMatricula      = new Matricula($oParam->iMatricula);
      $oMatricula->getDiarioDeClasse();
      
      $iAno                   = $oMatricula->getTurma()->getCalendario()->getAnoExecucao();
      $_SESSION["oMatricula"] = $oMatricula;
      $oEtapaAluno            = $oMatricula->getEtapaDeOrigem();
      $sFormaAvaliacaoTurma   = $oMatricula->getTurma()->getProcedimentoDeAvaliacaoDaEtapa($oEtapaAluno)
                                                       ->getFormaAvaliacao()->getTipo();
      /**
       * Buscamos os dados da Matricula do aluno
       */
      $oRetorno->sNomeAluno       = urlencode($oMatricula->getAluno()->getNome());
      $oRetorno->iCodigoAluno     = $oMatricula->getAluno()->getCodigoAluno();
      $oRetorno->sSituacaoAluno   = $oMatricula->getSituacao();
      $oRetorno->iCodigoTurma     = $oMatricula->getTurma()->getCodigo();
      $oRetorno->lAvaliadoParecer = $oMatricula->isAvaliadoPorParecer();
      $oRetorno->dtMatricula      = $oMatricula->getDataMatricula()->convertTo(DBDate::DATA_PTBR);
      $oRetorno->iEtapa           = $oEtapaAluno->getCodigo();
      $oRetorno->dtSaida          = str_repeat("&nbsp;", 10);
      if ($oMatricula->getDataEncerramento() != "") {
        $oRetorno->dtSaida = $oMatricula->getDataEncerramento()->convertTo(DBDate::DATA_PTBR);
      }
      $oRetorno->sCalendario          = urlencode($oMatricula->getTurma()->getCalendario()->getDescricao());
      $oRetorno->sTurma               = urlencode($oMatricula->getTurma()->getDescricao());
      $oRetorno->iTabIndex            = db_utils::fieldsMemory($rsParametro, 0)->ed233_deslocamentocursor;
      $oRetorno->sMascaraFormatacao   = ArredondamentoNota::getMascara($iAno);
      $oRetorno->sFormaAvaliacaoTurma = urlencode($sFormaAvaliacaoTurma);

      /**
       * Buscamos as etapas da turma
       */
      $aEtapaTurma = $oMatricula->getTurma()->getEtapas();

      /**
       * Iteramos sobre as etapas(serie) de uma turma.
       * Uma Turma pode ter duas etapas(serie) cursando em paralelo
       */
      foreach ($aEtapaTurma as $oEtapaTurma) {

        if ($oEtapaTurma->getEtapa()->getCodigo() != $oMatricula->getEtapaDeOrigem()->getCodigo()) {
          continue;
        }

        /**
         * Iteramos sobre o procedimento de avaliacao da etapa(serie) buscando os elementos de avaliacao
         */
        foreach ($oEtapaTurma->getProcedimentoAvaliacao()->getElementos() as $oAvaliacao) {

          $oPeriodosAvaliacao                              = new stdClass();
          $oPeriodosAvaliacao->iCodigoAvaliacao            = $oAvaliacao->getCodigo();
          $oPeriodosAvaliacao->geraResultadoFinal          = false;
          $oPeriodosAvaliacao->sFormaAvaliacao             = $oAvaliacao->getFormaDeAvaliacao()->getTipo();
          $oPeriodosAvaliacao->iPeriodoDependenteAprovacao = '';
          $oPeriodosAvaliacao->mMinimoAprovacao            = $oAvaliacao->getAproveitamentoMinimo();

          switch ($oPeriodosAvaliacao->sFormaAvaliacao) {

            case 'NOTA':

              $oPeriodosAvaliacao->iMenorValor = $oAvaliacao->getFormaDeAvaliacao()->getMenorValor();
              $oPeriodosAvaliacao->iMaiorValor = $oAvaliacao->getFormaDeAvaliacao()->getMaiorValor();
              $oPeriodosAvaliacao->nVariacao   = $oAvaliacao->getFormaDeAvaliacao()->getVariacao();
              break;
            case 'NIVEL':

              $oPeriodosAvaliacao->aConceitos = array();
              foreach ($oAvaliacao->getFormaDeAvaliacao()->getConceitos() as $oConceito) {

                $oDadoConceito                     = new stdClass();
                $oDadoConceito->iCodigoConceito    = $oConceito->iCodigo;
                $oDadoConceito->sDescricaoConceito = $oConceito->sConceito;
                $oDadoConceito->iOrdem             = $oConceito->iOrdem;
                $oPeriodosAvaliacao->aConceitos[]  = $oDadoConceito;
              }

              break;

          }
          $oPeriodosAvaliacao->iCodigoAvaliacao = $oAvaliacao->getOrdemSequencia();
          $oPeriodosAvaliacao->iOrdem           = $oAvaliacao->getOrdemSequencia();

          if ($oAvaliacao instanceof ResultadoAvaliacao) {

            $oTipoResultado                                 =  $oAvaliacao->getTipoResultado();
            $oPeriodosAvaliacao->sDescricaoPeriodo          = urlencode($oTipoResultado->getDescricao());
            $oPeriodosAvaliacao->sDescricaoPeriodoAbreviado = urlencode($oTipoResultado->getDescricaoAbreviada());
            $oPeriodosAvaliacao->sTipoAvaliacao             = "R";
            $oPeriodosAvaliacao->geraResultadoFinal         = $oAvaliacao->geraResultadoFinal();
            $oPeriodosAvaliacao->sFormaObtencao             = $oAvaliacao->getFormaDeObtencao();
          }

          if ($oAvaliacao instanceof AvaliacaoPeriodica) {

            $oPeriodo                                       = $oAvaliacao->getPeriodoAvaliacao();
            $oPeriodosAvaliacao->sDescricaoPeriodo          = urlencode($oPeriodo->getDescricao());
            $oPeriodosAvaliacao->sDescricaoPeriodoAbreviado = urlencode($oPeriodo->getDescricaoAbreviada());
            $oPeriodosAvaliacao->sTipoAvaliacao             = 'A';
            $oPeriodosAvaliacao->iLimiteReprovacao          = $oAvaliacao->quantidadeMaximaDisciplinasParaRecuperacao();
            if ($oAvaliacao->getElementoAvaliacaoVinculado() != "") {

              $oPeriodosAvaliacao->iPeriodoDependenteAprovacao = $oAvaliacao->getElementoAvaliacaoVinculado()
                                                                            ->getOrdemSequencia();
            }
          }

          $aPeriodosAvaliacaoRetorno[] = $oPeriodosAvaliacao;
        }
      }

      $oRetorno->dados = $aPeriodosAvaliacaoRetorno;

      /**
       * Buscamos os termos do ensino
       */
      $oDaoTurma    = db_utils::getDao('turma');
      $sCamposTurma = "ed10_i_codigo";
      $sWhereTurma  = "ed57_i_codigo = {$oMatricula->getTurma()->getCodigo()}";
      $sSqlTUrma    = $oDaoTurma->sql_query(null, $sCamposTurma, null, $sWhereTurma);
      $rsTurma      = $oDaoTurma->sql_record($sSqlTUrma);

      if ($oDaoTurma->numrows > 0) {

        $oRetorno->aTermos = array();
        $iContadorTermos   = 1;
        $iCodigoEnsino     = db_utils::fieldsMemory($rsTurma, 0)->ed10_i_codigo;
        if (!empty($iCodigoEnsino)) {

          $aTermos = DBEducacaoTermo::getTermoEncerramentoDoEnsino($iCodigoEnsino);
          if (count($aTermos) > 0) {

            $oRetorno->aTermos[0]->sReferencia  = '';
            $oRetorno->aTermos[0]->sDescricao   = '';
            $oRetorno->aTermos[0]->sAbreviatura = '';
            foreach ($aTermos as $oTermo) {

              $oRetorno->aTermos[$iContadorTermos]->sReferencia  = urlencode($oTermo->sReferencia);
              $oRetorno->aTermos[$iContadorTermos]->sDescricao   = urlencode($oTermo->sDescricao);
              $oRetorno->aTermos[$iContadorTermos]->sAbreviatura = urlencode($oTermo->sAbreviatura);
              $iContadorTermos++;
            }
          } else {

            $oRetorno->aTermos[0]->sReferencia  = '';
            $oRetorno->aTermos[0]->sDescricao   =  '';
            $oRetorno->aTermos[1]->sReferencia  = urlencode('A');
            $oRetorno->aTermos[1]->sDescricao   = urlencode('Aprovado');
            $oRetorno->aTermos[1]->sAbreviatura = urlencode('Apr');
            $oRetorno->aTermos[2]->sReferencia  = urlencode('R');
            $oRetorno->aTermos[2]->sDescricao   = urlencode('Reprovado');
            $oRetorno->aTermos[2]->sAbreviatura = urlencode('Rep');
          }
        }
      }

      if (count($oRetorno->dados) == 0) {
        throw new BusinessException("N�o h� periodos de avaliacao");
      }

      db_fim_transacao(false);
    } catch (ParameterException $oErro) {

      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($oErro->getMessage());
    } catch (BusinessException $oErro) {

      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($oErro->getMessage());
    }

    break;
  case 'buscaDisciplinasTurma':

    db_inicio_transacao();

    /**
     * @todo testar se existe a matricula na sessao
     * caso nao existir, lan�ar exception
     */
    $oMatricula      = $_SESSION["oMatricula"];
    $aRegenciasTurma = $oMatricula->getTurma()->getDisciplinasPorEtapa($oMatricula->getEtapaDeOrigem());
    $iAno            = $oMatricula->getTurma()->getCalendario()->getAnoExecucao();
    foreach ($aRegenciasTurma as $oRegencia) {

      if ($lProfessorLogado) {
        if (!$oDocente->lecionaRegencia($oRegencia)) {
          continue;
        }
      }

      $oDiario                            = $oMatricula->getDiarioDeClasse();
      $oDadosAproveitamento               = $oDiario->getDisciplinasPorRegencia($oRegencia);
      $oDadosRegencias                    = new stdClass();
      $oDadosRegencias->iCodigoRegencia   = $oRegencia->getCodigo();
      $oDadosRegencias->sDescricao        = urlencode($oRegencia->getDisciplina()->getNomeDisciplina());
      $oDadosRegencias->aAproveitamentos  = array();
      $oDadosRegencias->sFrequenciaGlobal = $oRegencia->getFrequenciaGlobal();
      $oDadosRegencias->lEncerrada        = $oDadosAproveitamento->isEncerrado();
      $oDadosRegencias->iTotalFaltas      = $oDadosAproveitamento->getTotalFaltas() -
                                            $oDadosAproveitamento->getTotalFaltasAbonadas();
      $aAvaliacoes = $oDadosAproveitamento->getAvaliacoes();
      foreach ($aAvaliacoes as $oAvaliacao) {

        $nNota  = $oAvaliacao->getValorAproveitamento()->getAproveitamento();
        $iOrdem = '';
        if ($oAvaliacao->getValorAproveitamento()->hasOrdem()) {
          $iOrdem = $oAvaliacao->getValorAproveitamento()->getOrdem();
        }
        if ($oAvaliacao->getElementoAvaliacao()->isResultado()) {
          $nNota = ArredondamentoNota::formatar($nNota, $iAno);
        }

        $oDadosAvaliacao                              = new stdClass();
        $oDadosAvaliacao->iPeriodo                    = $oAvaliacao->getElementoAvaliacao()->getOrdemSequencia();
        $oDadosAvaliacao->iTotalDisciplinasReprovadas = 0;
        $oDadosAvaliacao->nNota                       = urlencode("{$nNota}");
        $oDadosAvaliacao->iOrdem                      = $iOrdem;
        $oDadosAvaliacao->lEncerrado                  = $oDadosAproveitamento->isEncerrado();
        $oDadosAvaliacao->lMinima                     = $oAvaliacao->temAproveitamentoMinimo();
        $oDadosAvaliacao->iFalta                      = $oAvaliacao->getNumeroFaltas();
        $oDadosAvaliacao->lFaltaBloqueada             = false;
        $oDadosAvaliacao->lAmparada                   = $oAvaliacao->isAmparado();
        $oDadosAvaliacao->lAvaliacaoExterna           = $oAvaliacao->isAvaliacaoExterna();
        $oDadosAvaliacao->lRecuperacao                = $oAvaliacao->emRecuperacao();
        if (!$oAvaliacao->getElementoAvaliacao()->isResultado()) {

          $oDadosAvaliacao->lFaltaBloqueada = $oDadosAproveitamento->hasFaltasPorPeriodoDeAula(
                                               $oAvaliacao->getElementoAvaliacao()->getPeriodoAvaliacao()
                                              );
        }
        $oDadosAvaliacao->lNotaBloqueada             = (trim($nNota) != "" && $lProfessorLogado && $lBloqueiaAlteracaoAvaliacao);
        if ($oAvaliacao->isAmparado()) {
          
          $oDadosAvaliacao->lNotaBloqueada  = true;
          $oDadosAvaliacao->lFaltaBloqueada = true;
          $oDadosAvaliacao->nNota           = 'AMP';
          $oDadosAvaliacao->iFalta          = '';
        }
        $oDadosAvaliacao->sResultadoFinal            = $oDadosAproveitamento->getResultadoFinal()->getResultadoFinal();
        if (!$oRegencia->isObrigatoria()) {
          $oDadosAvaliacao->sResultadoFinal = 'A';
        }
        
        $oDadosAvaliacao->lAprovadoProgressaoParcial = $oDadosAproveitamento->aprovadoComProgressaoParcial();
        /**
         * Somamos todas as disciplinas com o elemento de avaliacoa reprovado no periodo
         */
        if (!$oAvaliacao->getElementoAvaliacao()->isResultado() &&
          $oAvaliacao->getElementoAvaliacao()->getElementoAvaliacaoVinculado() != '') {

          $oElementoReprovacao = $oAvaliacao->getElementoAvaliacao()->getElementoAvaliacaoVinculado();
          $iTotalReprovacoes   = count($oDiario->getDisciplinasReprovadasNoPeriodo($oElementoReprovacao));

          $oDadosAvaliacao->iTotalDisciplinasReprovadas = $iTotalReprovacoes;
        }
        $oDadosRegencias->aAproveitamentos[]= $oDadosAvaliacao;

      }
      db_fim_transacao(false);
      $_SESSION["oMatricula"] = $oMatricula;
      $oRetorno->dados[]      = $oDadosRegencias;
    }

    break;

  case 'salvaAvaliacaoAluno':

    try {

      db_inicio_transacao();

      LancamentoAvaliacaoAluno::salvaAvaliacaoAluno($_SESSION["oMatricula"]);
      $oRetorno->status  = 1;
      $oRetorno->message = urlencode("Dados de avalia��o salvos com sucesso.");
      db_fim_transacao(false);
      $_SESSION["oMatricula"] = $oMatricula;
    } catch (BusinessException $eBusinnesException) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eBusinnesException->getMessage());
      db_fim_transacao(true);
    } catch (DBException $eDBException) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eDBException->getMessage());
      db_fim_transacao(true);
    } catch (ParameterException $eParameterException) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eParameterException->getMessage());
      db_fim_transacao(true);
    }

    break;

  case 'calcularResultado':

    $_SESSION["oMatricula"] = LancamentoAvaliacaoAluno::calcularResultado($_SESSION["oMatricula"],
                                                                          $oParam->oAvaliacao,
                                                                          $oRetorno);

    break;

  case 'getLegendasParecer':

    $oDaoParecerLegenda = db_utils::getDao("parecerlegenda");
    $sSqlLegendas       = $oDaoParecerLegenda->sql_query_file(null,
                                                               "ed91_i_codigo as codigo,
                                                                trim(ed91_c_descr) as descricao",
                                                               "ed91_i_codigo",
                                                               "ed91_i_escola=".db_getsession("DB_coddepto")
                                                              );

    $rsLegendas          = $oDaoParecerLegenda->sql_record($sSqlLegendas);
    $oRetorno->aLegendas = db_utils::getCollectionByRecord($rsLegendas, false, false, true);
    break;

  case 'salvarParecer':

    $sParecer            = db_stdClass::normalizeStringJsonEscapeString($oParam->sParecer);
    $sParecerPadronizado = db_stdClass::normalizeStringJsonEscapeString($oParam->sParecerPadronizado);

    $_SESSION["oMatricula"] = LancamentoAvaliacaoAluno::salvarParecer($_SESSION["oMatricula"],
                                                                      RegenciaRepository::getRegenciaByCodigo($oParam->iRegencia),
                                                                      $oParam->iPeriodo,
                                                                      $sParecer,
                                                                      $sParecerPadronizado
                                                                     );

    break;

  case 'getParecer':

    db_inicio_transacao();
    $oParecer = LancamentoAvaliacaoAluno::getParecer($_SESSION["oMatricula"],
                                                     RegenciaRepository::getRegenciaByCodigo($oParam->iRegencia),
                                                     $oParam->iPeriodo);
    $oRetorno->sParecerPadronizado = urlencode($oParecer->sParecerPadronizado);
    $oRetorno->sParecer            = urlencode(str_replace('\n',"\n", $oParecer->sParecer));
    db_fim_transacao();

    break;

  case 'getParecerComplementar':

    db_inicio_transacao();

    if (isset($_SESSION["oTurma"]) && $_SESSION["oTurma"] instanceof Turma) {

      $oTurma = $_SESSION["oTurma"];
      $oEtapa = $_SESSION["oEtapa"];

      $aMatriculas = $oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapa);
      foreach ($aMatriculas as $oMatriculaTurma) {

        if ($oMatriculaTurma->getCodigo() == $oParam->iMatricula) {

          $oMatricula = $oMatriculaTurma;
          break;
        }
      }
    } else {
      $oMatricula  = MatriculaRepository::getMatriculaByCodigo($oParam->iMatricula);
    }

    $oParecer = LancamentoAvaliacaoAluno::getParecerComplementar($oMatricula,
                                                                RegenciaRepository::getRegenciaByCodigo($oParam->iRegencia),
                                                                $oParam->iPeriodo);
    $oRetorno->sParecerPadronizado = urlencode($oParecer->sParecerPadronizado);
    $oRetorno->sParecer            = urlencode(str_replace('\n',"\n", $oParecer->sParecer));

    if (isset($_SESSION["oTurma"]) && $_SESSION["oTurma"] instanceof Turma) {
      $_SESSION["oTurma"] = $oTurma;
    }

    db_fim_transacao();

    break;

  case 'salvarResultadoParecer':

    print_r($oParam);
    $_SESSION["oMatricula"] = LancamentoAvaliacaoAluno::salvarResultadoParecer($_SESSION["oMatricula"],
                                                                               RegenciaRepository::getRegenciaByCodigo($oParam->iRegencia),
                                                                               $oParam->iPeriodo,
                                                                               $oParam->lAproveitamentoMinimo,
                                                                               $oParam->lRecuperacao
                                                                              );

    break;

  case 'setFalta':

    $_SESSION["oMatricula"] = LancamentoAvaliacaoAluno::setFalta($_SESSION["oMatricula"],
                                                                 RegenciaRepository::getRegenciaByCodigo($oParam->iRegencia),
                                                                 $oParam->iPeriodo,
                                                                 $oParam->iFalta);
    break;

  case 'getParametroTelaParecer':

    $oDaoParametros   = db_utils::getDao("edu_parametros");
    $sWhereParametros = " ed233_i_escola = {$iEscola}";
    $sSqlParametros   = $oDaoParametros->sql_query(null, "ed233_formalancamentoparecer", null, $sWhereParametros);
    $rsParametros     = $oDaoParametros->sql_record($sSqlParametros);

    if ($oDaoParametros->numrows > 0) {

      $oRetorno->iParametro = db_utils::fieldsMemory($rsParametros, 0)->ed233_formalancamentoparecer;
    }
    break;

  case 'getParecerDisciplina':

    db_inicio_transacao();

      $oRetornoDados       = LancamentoAvaliacaoAluno::getParecerDisciplina(RegenciaRepository::getRegenciaByCodigo($oParam->iRegencia),
                                                                            $oParam->iPeriodoAvaliacao);

      $oRetorno->dados     = $oRetornoDados->aDados;
      $oRetorno->aLegendas = $oRetornoDados->aLegendas;

    db_fim_transacao();

    break;

  case 'getAlunosVinculados':

    unset($_SESSION["oMatricula"]);
    $oTurma            = TurmaRepository::getTurmaByCodigo($oParam->iCodigoTurma);

    $oEtapa            = EtapaRepository::getEtapaByCodigo($oParam->iEtapa);
    $aAlunosVinculados = $oTurma->getAlunosProgressaoParcial($oEtapa);
    $aDadosMatricula   = array();
    foreach ($aAlunosVinculados as $oVinculo) {

      $aVinculosNaTurma =  $oVinculo->getVinculosNaTurma($oTurma, $oEtapa);
      $lEncontrado      = false;

      foreach ($aDadosMatricula as $oMatricula) {

        if ($oMatricula->iMatricula == $oVinculo->getAluno()->getCodigoAluno()) {
          $lEncontrado = true;
          break;
        }
      }

      if (!$lEncontrado) {

        $oDadosMatricula                  = new stdClass();
        $oDadosMatricula->iCodigo         = array();
        $oDadosMatricula->iMatricula      = $oVinculo->getAluno()->getCodigoAluno();
        $oDadosMatricula->iOrdem          = '';
        $oDadosMatricula->aDisciplina     = array();
        $oDadosMatricula->sNome           = urlencode($oVinculo->getAluno()->getNome());
        $oDadosMatricula->dtDataMatricula = $aVinculosNaTurma[0]->getDataVinculo()->convertTo(DBDate::DATA_PTBR);
        $oDadosMatricula->sSituacao       = urlencode("DEPEND�NCIA EM: ");
        $aDadosMatricula[]                = $oDadosMatricula;
      }
      foreach ($aVinculosNaTurma as $oVinculoTurma) {

        $oDadosMatricula->iCodigo[]     = $oVinculoTurma->getCodigoProgressao();
        $oDadosMatricula->aDisciplina[] = urlencode($oVinculo->getDisciplina()->getAbreviatura());
      }
    }
    $oRetorno->dados = $aDadosMatricula;
    break;

  case 'getDadosVinculoAluno':

      $aPeriodosAvaliacaoRetorno = array();

      try {

        db_inicio_transacao();
        $iCodigoVinculo     = $oParam->aVinculos[0];
        $oTurma             = TurmaRepository::getTurmaByCodigo($oParam->iTurma);
        $oEtapa             = EtapaRepository::getEtapaByCodigo($oParam->iEtapa);
        $oProgressaoParcial = ProgressaoParcialAlunoRepository::getProgressaoParcialAlunoByCodigo($iCodigoVinculo);
        $aVinculos          = $oProgressaoParcial->getVinculosNaTurma($oTurma, $oEtapa);
        /**
         * Buscamos os dados da Matricula do aluno
         */
        $oRetorno->sNomeAluno     = urlencode($oProgressaoParcial->getAluno()->getNome());
        $oRetorno->iCodigoAluno   = $oProgressaoParcial->getAluno()->getCodigoAluno();
        $oRetorno->sSituacaoAluno = "";
        $oRetorno->iCodigoTurma   = $oTurma->getCodigo();
        $oRetorno->dtMatricula    = $aVinculos[0]->getDataVinculo() ->convertTo(DBDate::DATA_PTBR);
        $oRetorno->sCalendario    = urlencode($oTurma->getCalendario()->getDescricao());
        $oRetorno->sTurma         = urlencode($oTurma->getDescricao());
        $aDisciplinas             = array();

        foreach ($oParam->aVinculos as $iCodigoVinculo) {

          $oProgressaoParcial = ProgressaoParcialAlunoRepository::getProgressaoParcialAlunoByCodigo($iCodigoVinculo);
          $aVinculosTurma     = $oProgressaoParcial->getVinculosNaTurma($oTurma, $oEtapa);
          $oVinculo           = $aVinculosTurma[0];

          $oDisciplina              = new stdClass();
          $oDisciplina->lEncerrada  = $oVinculo->isEncerrado();
          $oDisciplina->sDisciplina = urlEncode($oVinculo->getRegencia()
                                                         ->getDisciplina()
                                                         ->getNomeDisciplina()
                                               );

          $oResultadoFinal                = $oVinculo->getResultadoFinal();
          $oDisciplina->iCodigoProgressao = $oVinculo->getCodigoProgressao();
          $oDisciplina->iNota             = urlencode($oResultadoFinal->getNota());
          $oDisciplina->iFalta            = $oResultadoFinal->getTotalFalta();
          $oDisciplina->sResultadoFinal   = $oResultadoFinal->getResultado();
          $aDisciplinas[]                 = $oDisciplina;

        }

        usort($aDisciplinas, "ordernarDisciplinas");
        $oRetorno->aDisciplinas = $aDisciplinas;

        /**
         * Buscamos os termos do ensino
         */
        $oDaoTurma    = db_utils::getDao('turma');
        $sCamposTurma = "ed10_i_codigo";
        $sWhereTurma  = "ed57_i_codigo = {$oParam->iTurma}";
        $sSqlTUrma    = $oDaoTurma->sql_query(null, $sCamposTurma, null, $sWhereTurma);
        $rsTurma      = $oDaoTurma->sql_record($sSqlTUrma);

        if ($oDaoTurma->numrows > 0) {

          $oRetorno->aTermos = array();
          $iContadorTermos   = 1;
          $iCodigoEnsino     = db_utils::fieldsMemory($rsTurma, 0)->ed10_i_codigo;
          if (!empty($iCodigoEnsino)) {

            $aTermos = DBEducacaoTermo::getTermoEncerramentoDoEnsino($iCodigoEnsino);
            if (count($aTermos) > 0) {

              $oRetorno->aTermos[0]->sReferencia = '';
              $oRetorno->aTermos[0]->sDescricao  = '';
              foreach ($aTermos as $oTermo) {

                $oRetorno->aTermos[$iContadorTermos]->sReferencia = urlencode($oTermo->sReferencia);
                $oRetorno->aTermos[$iContadorTermos]->sDescricao  = urlencode($oTermo->sDescricao);
                $iContadorTermos++;
              }
            } else {

              $oRetorno->aTermos[0]->sReferencia = '';
              $oRetorno->aTermos[0]->sDescricao  = '';
              $oRetorno->aTermos[1]->sReferencia = urlencode('A');
              $oRetorno->aTermos[1]->sDescricao  = urlencode('Aprovado');
              $oRetorno->aTermos[2]->sReferencia = urlencode('R');
              $oRetorno->aTermos[2]->sDescricao  = urlencode('Reprovado');
            }
          }
        }

      } catch (BusinessException $eBusinnesException) {

        $oRetorno->status  = 2;
        $oRetorno->message = urlencode($eBusinnesException->getMessage());
        db_fim_transacao(true);
      } catch (DBException $eDBException) {

        $oRetorno->status  = 2;
        $oRetorno->message = urlencode($eDBException->getMessage());
        db_fim_transacao(true);
      } catch (ParameterException $eParameterException) {

        $oRetorno->status  = 2;
        $oRetorno->message = urlencode($eParameterException->getMessage());
        db_fim_transacao(true);
      }
    break;

  case 'salvarProgressaoAluno':

    try {

      if (!isset($oParam->aProgressoes)) {
        throw new  ParameterException('Progress�es n�o informadas.');
      }

      db_inicio_transacao();

      foreach ($oParam->aProgressoes as $oProgressao) {

        $oProgressaoAluno = ProgressaoParcialAlunoRepository::getProgressaoParcialAlunoByCodigo($oProgressao->iCodigoProgressao);
        $oProgressaoAluno->setResultadoFinal(db_stdClass::normalizeStringJson($oProgressao->sNota),
                                             $oProgressao->iFaltas,
                                             $oProgressao->sResultadoFinal
                                            );
        $oProgressaoAluno->salvar();
      }
      $oRetorno->message = urlencode('Resultado final salva com sucesso.');
      db_fim_transacao(false);

    } catch (BusinessException $eBusinnesException) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eBusinnesException->getMessage());
      db_fim_transacao(true);
    } catch (DBException $eDBException) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eDBException->getMessage());
      db_fim_transacao(true);
    } catch (ParameterException $eParameterException) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eParameterException->getMessage());
      db_fim_transacao(true);
    }
    break;

  case 'salvarParecerComplementar':

    $sParecer            = db_stdClass::normalizeStringJsonEscapeString($oParam->sParecer);
    $sParecerPadronizado = db_stdClass::normalizeStringJsonEscapeString($oParam->sParecerPadronizado);

    db_inicio_transacao();
    if (isset($_SESSION["oTurma"]) && $_SESSION["oTurma"] instanceof Turma) {

      $oTurma = $_SESSION["oTurma"];
      $oEtapa = $_SESSION["oEtapa"];

      $aMatriculas = $oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapa);
      foreach ($aMatriculas as $oMatriculaTurma) {

        if ($oMatriculaTurma->getCodigo() == $oParam->iMatricula) {

          $oMatricula = $oMatriculaTurma;
          break;
        }
      }
    } else {
      $oMatricula  = MatriculaRepository::getMatriculaByCodigo($oParam->iMatricula);
    }
    foreach ($oParam->aRegencia as $iRegencia) {

      LancamentoAvaliacaoAluno::salvarParecerComplementar($oMatricula,
                                                          RegenciaRepository::getRegenciaByCodigo($iRegencia),
                                                          $oParam->iPeriodo,
                                                          $sParecer,
                                                          $sParecerPadronizado
      );
    }

    if (isset($_SESSION["oTurma"]) && $_SESSION["oTurma"] instanceof Turma) {
      $_SESSION["oTurma"] = $oTurma;
    }

    db_fim_transacao(false);
    break;
}

function ordernarDisciplinas($aArrayAtual, $aProximoArray) {
  return strcasecmp(urldecode($aArrayAtual->sDisciplina), urldecode($aProximoArray->sDisciplina));
}

echo $oJson->encode($oRetorno);
?>
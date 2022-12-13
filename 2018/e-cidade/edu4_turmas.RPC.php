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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("std/DBDate.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("model/educacao/avaliacao/iFormaObtencao.interface.php"));
require_once(modification("model/educacao/avaliacao/iElementoAvaliacao.interface.php"));

define("URL_MENSAGEM_TURMA_RPC", "educacao.escola.edu4_turmas_RPC.");

$iEscola           = db_getsession("DB_coddepto");
$iModuloEscola     = 1100747;
$oJson             = new Services_JSON();
$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';
$oRetorno->erro    = false;

define( "AVALIACAO_RESULTADO", 1 );      // Todos Períodos de avaliação + resultados
define( "AVALIACAO", 2 );                // Somente Períodos de avaliação
define( "RESULTADO", 3 );                // Somente Resultados
define( "AVALIACAO_RESULTADO_FINAL", 4); // Todos Períodos de avaliação + resultado final

try {

  switch($oParam->exec) {

    /**
     * Pesquisa os dados dos alunos para cancelamento da troca de turma
     */
    case 'pesquisaDadosAluno':

      $oMatricula = MatriculaRepository::getMatriculaByCodigo($oParam->iCodigoMatricula);
      $oTurma     = TurmaRepository::getTurmaByCodigo($oParam->iTurma);
      $oEtapa     = EtapaRepository::getEtapaByCodigo($oParam->iEtapa);

      /**
       * Preenchemos os dados da turma atual, com status de 'MATRICULADO'
       */
      $oRetorno->iCodigoAtual    = $oMatricula->getCodigo();
      $oRetorno->iMatriculaAtual = $oMatricula->getMatricula();
      $oRetorno->sTurmaAtual     = urlencode($oTurma->getDescricao());
      $oRetorno->sEtapaAtual     = urlencode($oEtapa->getNome());
      $oRetorno->sSituacaoAtual  = 'MATRICULADO';
      $oRetorno->sTurnoAtual     = urlencode($oTurma->getTurno()->getDescricao());

      /**
       * Buscamos as informacoes referentes a turma de origem do aluno
       */
      $oDaoMatricula    = db_utils::getDao("matricula");
      $sCamposMatricula = "ed60_i_codigo, turma.ed57_i_codigo as ed57_i_codigo, ed221_i_serie";
      $sWhereMatricula  = "ed60_matricula = {$oMatricula->getMatricula()} AND ed60_c_situacao = 'TROCA DE TURMA'";
      $sSqlMatricula    = $oDaoMatricula->sql_query(null, $sCamposMatricula, "ed60_i_codigo desc", $sWhereMatricula);
      $rsMatricula      = db_query( $sSqlMatricula );

      if ( !$rsMatricula ) {
        throw new DBException('Falha ao buscar os dados da matrícula do aluno.');
      }

      $oRetorno->iMatriculaOrigem = '';
      $oRetorno->sTurmaOrigem     = '';
      $oRetorno->sEtapaOrigem     = '';
      $oRetorno->sSituacaoOrigem  = '';
      $oRetorno->sTurnoOrigem     = '';

      if (pg_num_rows($rsMatricula) > 0) {

        $oDadosMatricula  = db_utils::fieldsMemory($rsMatricula, 0);
        $oMatriculaOrigem = MatriculaRepository::getMatriculaByCodigo($oDadosMatricula->ed60_i_codigo);

        $oTurmaOrigem     = TurmaRepository::getTurmaByCodigo($oDadosMatricula->ed57_i_codigo);
        $oEtapaOrigem     = EtapaRepository::getEtapaByCodigo($oDadosMatricula->ed221_i_serie);

        $oRetorno->iCodigoOrigem    = $oMatriculaOrigem->getCodigo();
        $oRetorno->iMatriculaOrigem = $oMatriculaOrigem->getMatricula();
        $oRetorno->sTurmaOrigem     = urlencode($oTurmaOrigem->getDescricao());
        $oRetorno->sEtapaOrigem     = urlencode($oEtapaOrigem->getNome());
        $oRetorno->sSituacaoOrigem  = 'TROCA DE TURMA';
        $oRetorno->sTurnoOrigem     = urlencode($oTurmaOrigem->getTurno()->getDescricao());
        unset($oDadosMatricula);
      }

      MatriculaRepository::removerMatricula($oMatricula);
      MatriculaRepository::removerMatricula($oMatriculaOrigem);
      TurmaRepository::removerTurma($oTurma);
      TurmaRepository::removerTurma($oTurmaOrigem);
      EtapaRepository::removerEtapa($oEtapa);
      EtapaRepository::removerEtapa($oEtapaOrigem);

      break;

    /**
     * Realiza o cancelamento da troca de turma de um aluno
     */
    case 'cancelarTrocaDeTurma':

      db_inicio_transacao();

      $oMatricula  = MatriculaRepository::getMatriculaByCodigo($oParam->iCodigoMatricula);
      $oTurma      = TurmaRepository::getTurmaByCodigo($oParam->iTurma);
      $oTrocaTurma = new TrocaTurma($oMatricula, $oTurma);
      $oTrocaTurma->cancelar();

      db_fim_transacao(false);
      break;

    case 'pesquisaTurno':

      $oRetorno->aTurnos = array();
      $oTurma            = TurmaRepository::getTurmaByCodigo($oParam->iTurma);

      $oDadosTurno          = new stdClass();
      $oDadosTurno->iCodigo = $oTurma->getTurno()->getCodigoTurno();
      $oDadosTurno->sTurno  = urlencode($oTurma->getTurno()->getDescricao());

      $oRetorno->aTurnos[]  = $oDadosTurno;

      if ($oTurma->temTurnoAdicional()) {

        $oDadosTurnoAdicional          = new stdClass();
        $oDadosTurnoAdicional->iCodigo = $oTurma->getTurnoAdicional()->getCodigoTurno();
        $oDadosTurnoAdicional->sTurno  = urlencode($oTurma->getTurnoAdicional()->getDescricao());
        $oRetorno->aTurnos[]           = $oDadosTurnoAdicional;
        unset($oDadosTurnoAdicional);
      }

      unset($oDadosTurno);
      break;

    /**
     * Retorna as regencias inconsistentes na troca de turma sem registro de movimentação, e se o procedimento de
     * avaliação das turmas é equivalente
     */
    case 'getRegenciasTurmaInconsistente':

      $oRetorno->aRegenciasInconsistentes = array();
      $oRetorno->aRegenciasDestino        = array();
      $oRetorno->lPeriodosInconsistentes  = false;

      if (isset($oParam->iMatricula) && isset($oParam->iTurmaDestino)) {

        $oMatricula    = new Matricula($oParam->iMatricula);
        $oEtapaAluno   = $oMatricula->getEtapaDeOrigem();
        $oTurmaDestino = new Turma($oParam->iTurmaDestino);
        $oTrocaTurma   = new TrocaTurma($oMatricula, $oTurmaDestino);

        foreach ($oTrocaTurma->getRegenciasTrocaTurmaInconsistentes() as $oRegenciaInconsistente) {

          $oRegencia                            = new stdClass();
          $oRegencia->iCodigo                   = $oRegenciaInconsistente->getCodigo();
          $oRegencia->sDisciplina               = urlencode($oRegenciaInconsistente->getDisciplina()->getNomeDisciplina());
          $oRetorno->aRegenciasInconsistentes[] = $oRegencia;
        }

        foreach ($oTrocaTurma->getDisciplinasTurmaDestinoSemVinculo() as $oRegenciaDestino) {

          $oRegencia                     = new stdClass();
          $oRegencia->iCodigo            = $oRegenciaDestino->getCodigo();
          $oRegencia->sDisciplina        = urlencode($oRegenciaDestino->getDisciplina()->getNomeDisciplina());
          $oRetorno->aRegenciasDestino[] = $oRegencia;
        }
        $oProcedimentoOrigem  = $oMatricula->getTurma()->getProcedimentoDeAvaliacaoDaEtapa($oEtapaAluno);
        $oProcedimentoDestino = $oTurmaDestino->getProcedimentoDeAvaliacaoDaEtapa($oEtapaAluno);

        $oRetorno->lPeriodosInconsistentes = !$oProcedimentoOrigem->temEquivalencia($oProcedimentoDestino);
      }
      break;

    /**
     * Salvamos as alterações da troca de turma sem registro de movimentação
     */
    case 'salvarTrocaTurmaSemRegistro':

      if( empty( $oParam->iTurmaDestino ) ) {
        throw new ParameterException( _M( URL_MENSAGEM_TURMA_RPC + 'turma_destino_nao_informada' ) );
      }

      if( empty( $oParam->sTurno ) ) {
        throw new ParameterException( _M( URL_MENSAGEM_TURMA_RPC + 'turno_nao_informado' ) );
      }

      $aTurnosSelecionados = explode(',', $oParam->sTurno);
      $oTurmaDestino       = new Turma($oParam->iTurmaDestino);

      foreach( $aTurnosSelecionados as $iTurnoReferenteSelecionado ) {

        if( !$oTurmaDestino->temVagaDisponivel( $iTurnoReferenteSelecionado ) ) {
          throw new BusinessException( _M( URL_MENSAGEM_TURMA_RPC . 'nao_ha_vaga' ) );
        }
      }

      db_inicio_transacao();

      $oRetorno->lProcedimentosInconsistentes      = false;
      $oRetorno->aDisciplinasProcedimentoDiferente = array();

      foreach( $oParam->aDisciplinasVinculadas as $oDisciplinasVincular ) {

        if( empty( $oDisciplinasVincular->iCodigoOrigem ) || empty( $oDisciplinasVincular->iCodigoDestino ) ) {
          continue;
        }

        $oRegenciaOrigem  = RegenciaRepository::getRegenciaByCodigo( $oDisciplinasVincular->iCodigoOrigem );
        $oRegenciaDestino = RegenciaRepository::getRegenciaByCodigo( $oDisciplinasVincular->iCodigoDestino );
        if( !$oRegenciaOrigem->possuiMesmoProcedimentoAvaliacao( $oRegenciaDestino ) ) {

          $oRetorno->lProcedimentosInconsistentes        = true;
          $oRetorno->aDisciplinasProcedimentoDiferente[] = urlencode( $oRegenciaOrigem->getDisciplina()->getNomeDisciplina() );
        }
      }

      if ( isset($oParam->iMatricula) && !$oRetorno->lProcedimentosInconsistentes ) {

        $aRegenciasVinculadas = array();
        $oMatricula           = new Matricula($oParam->iMatricula);
        $oTrocaTurma          = new TrocaTurma($oMatricula, $oTurmaDestino, $oParam->sTurno);

        if (count($oParam->aDisciplinasVinculadas) > 0) {

          foreach ($oParam->aDisciplinasVinculadas as $oRegenciaVinculada) {

            $oRegencia              = new stdClass();
            $oRegencia->origem      = new Regencia($oRegenciaVinculada->iCodigoOrigem);
            $oRegencia->destino     = new Regencia($oRegenciaVinculada->iCodigoDestino);
            $aRegenciasVinculadas[] = $oRegencia;
          }
        }
        $oTrocaTurma->trocarTurmaSemRegistro($aRegenciasVinculadas);
      }

      db_fim_transacao();
      break;

    case 'getRegencias':

      $oTurma = EducacaoSessionManager::carregarTurma($oParam->iTurma);
      $oEtapa = EducacaoSessionManager::carregarEtapa($oParam->iEtapa);

      $oDocente   = null;
      $lProfessor = false;
      if ( isset($oParam->lDisciplinasProfessor) && $oParam->lDisciplinasProfessor) {

        $oDocente = DocenteRepository::getDocenteLogado( db_getsession("DB_id_usuario"), $iEscola );

        /**
         * Como a classe Docente retorna uma instancia de qualquer cgm da base, devemos verificar se
         * o "Docente" retornado é mesmo um docente. Uma das formas de tazer isto, é validando em
         * quantas turmas o "Docente" esta lecionando
         */
        if (!empty($oDocente) && count($oDocente->getTurmas()) > 0) {
          $lProfessor = true;
        }
      }

      $oRetorno->aRegencias = array();
      foreach ($oTurma->getDisciplinasPorEtapa($oEtapa) as $oRegencia)  {

        if (    isset( $oParam->lSomenteDisciplinasGlobais )
             && $oParam->lSomenteDisciplinasGlobais
             && ( $oRegencia->getFrequenciaGlobal() != 'FA' && $oRegencia->getFrequenciaGlobal() != 'F' ) ) {
          continue;
        }

        if( isset( $oParam->lSomenteDisciplinasParecer ) ) {

          if( $oParam->lSomenteDisciplinasParecer && $oRegencia->getProcedimentoAvaliacao()->getFormaAvaliacao()->getTipo() != 'PARECER' ) {
            continue;
          }

          if( !$oParam->lSomenteDisciplinasParecer && $oRegencia->getProcedimentoAvaliacao()->getFormaAvaliacao()->getTipo() == 'PARECER' ) {
            continue;
          }
        }

        $oDisciplina                         = new stdClass();
        $oDisciplina->iRegencia              = $oRegencia->getCodigo();
        $oDisciplina->iDisciplina            = $oRegencia->getDisciplina()->getCodigoDisciplina();
        $oDisciplina->sDisciplina            = urlencode($oRegencia->getDisciplina()->getNomeDisciplina());
        $oDisciplina->lTemGradeHorario       = $oRegencia->temGradeHorario();
        $oDisciplina->iProcedimentoAvaliacao = $oRegencia->getProcedimentoAvaliacao()->getCodigo();
        $oDisciplina->sProcedimentoAvaliacao = urlencode($oRegencia->getProcedimentoAvaliacao()->getDescricao());
        /**
         * Se o usuário logado for um Docente, devemos retornar as diciplinas este leciona,
         * se não for um docente, retornamos todas as disciplinas
         */
        if ($lProfessor && $oDocente->lecionaRegencia($oRegencia)) {
          $oRetorno->aRegencias[]   = $oDisciplina;
        } else if(!$lProfessor) {
          $oRetorno->aRegencias[]   = $oDisciplina;
        }
      }

      break;

    case 'pesquisaAlunosTurma':

      $oRetorno->aAlunos = array();

      if (!empty($oParam->iTurma)) {

        $oTurma             = EducacaoSessionManager::carregarTurma($oParam->iTurma);
        $aAlunosMatriculado = array();
        if (!empty($oParam->iEtapa)) {

          $oEtapa             = EducacaoSessionManager::carregarEtapa($oParam->iEtapa);
          $aAlunosMatriculado = $oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapa);
        } else {
          $aAlunosMatriculado = $oTurma->getAlunosMatriculados(false);
        }

        foreach ($aAlunosMatriculado as $oMatricula) {

          if ($oMatricula->getSituacao() != 'MATRICULADO') {
            continue;
          }

          if( isset( $oParam->lSomenteAlunosOrigemForaRede )
              && $oParam->lSomenteAlunosOrigemForaRede
              && !$oMatricula->matriculaMaiorDataInicioPrimeiroPeriodoCalendario() ) {
            continue;
          }

          if( isset( $oParam->lMatriculaMaiorPrimeiroPeriodoCalendario )
              && $oParam->lMatriculaMaiorPrimeiroPeriodoCalendario
              && !$oMatricula->matriculaMaiorDataInicioPrimeiroPeriodoCalendario() ) {
            continue;
          }

          if (isset($oParam->lTrazerAlunosEncerrados) && !$oParam->lTrazerAlunosEncerrados) {
            db_inicio_transacao();
            $aDiarioAvaliacaoDisciplina = $oMatricula->getDiarioDeClasse()->getDisciplinas();
            if ($aDiarioAvaliacaoDisciplina[0]->isEncerrado()) {
              continue;
            }
            db_fim_transacao();
          }

          $oDadosAluno              = new stdClass();
          $oDadosAluno->iMatricula  = $oMatricula->getCodigo();
          $oDadosAluno->sNome       = urlencode($oMatricula->getAluno()->getNome());
          $oDadosAluno->dtMatricula = urlencode( $oMatricula->getDataMatricula()->getDate( DBDate::DATA_PTBR ) );
          $oRetorno->aAlunos[]      = $oDadosAluno;
        }
      }

      break;

    case 'pesquisaPeriodosTurma':

      $oRetorno->aPeriodos = array();
      if (!empty($oParam->iTurma) && !empty($oParam->iEtapa)) {

        $oTurma     = EducacaoSessionManager::carregarTurma($oParam->iTurma);
        $oEtapa     = EducacaoSessionManager::carregarEtapa($oParam->iEtapa);
        $aElementos = $oTurma->getProcedimentoDeAvaliacaoDaEtapa($oEtapa)->getElementos();

        foreach ($aElementos as $oElemento) {

          if ( $oParam->iTipoBusca == AVALIACAO && $oElemento instanceof ResultadoAvaliacao ) {
            continue;
          }

          if ( $oParam->iTipoBusca == RESULTADO && $oElemento instanceof AvaliacaoPeriodica ) {
            continue;
          }

          /**
           * Valida se o elemento de avaliação é um resultado que gera resultado final
           */
          $lResultadoFinal = false; // Se o Resultado é um resultado final
          if ( $oParam->iTipoBusca == AVALIACAO_RESULTADO_FINAL ) {

            if ($oElemento instanceof ResultadoAvaliacao && !$oElemento->geraResultadoFinal()) {
              continue;
            } else if ($oElemento instanceof ResultadoAvaliacao && $oElemento->geraResultadoFinal()) {
              $lResultadoFinal = true;
            }
          }

          if (    isset( $oParam->lSomenteCargaHoraria )
               && $oParam->lSomenteCargaHoraria
               && $oElemento instanceof AvaliacaoPeriodica
               && !$oElemento->getPeriodoAvaliacao()->hasSomaCargaHoraria() ) {
            continue;
          }

          $oDadosPeriodo                    = new stdClass();
          $oDadosPeriodo->iPeriodo          = $oElemento->getCodigo();
          $oDadosPeriodo->iPeriodoAvaliacao = null;

          if( $oElemento instanceof AvaliacaoPeriodica ) {
            $oDadosPeriodo->iPeriodoAvaliacao = $oElemento->getPeriodoAvaliacao()->getCodigo();
          }

          $oDadosPeriodo->sAbreviatura    = urlencode($oElemento->getDescricaoAbreviada());
          $oDadosPeriodo->sDescricao      = urlencode($oElemento->getDescricao());
          $oDadosPeriodo->iOrdem          = $oElemento->getOrdemSequencia();
          $oDadosPeriodo->lResultado      = $oElemento->isResultado();
          $oDadosPeriodo->lResultadoFinal = $lResultadoFinal;
          $oRetorno->aPeriodos[]          = $oDadosPeriodo;
        }
      }
      break;

    case 'dadosProcedimentoAvaliacao':

      if (empty($oParam->iTurma)) {
        throw new ParameterException("Nenhuma turma informada");
      }

      $oTurma = TurmaRepository::getTurmaByCodigo($oParam->iTurma);
      $oEtapa = null;

      if (!empty($oParam->iEtapa)) {
        $oEtapa = EtapaRepository::getEtapaByCodigo($oParam->iEtapa);
      } else {

        $aEtapas = $oTurma->getEtapas();
        $oEtapa  = $aEtapas[0]->getEtapa();
      }

      $oProcedimentoAvaliacao = $oTurma->getProcedimentoDeAvaliacaoDaEtapa($oEtapa);

      $oRetorno->oProcedimento                        = new stdClass();
      $oRetorno->oProcedimento->iCodigo               = $oProcedimentoAvaliacao->getCodigo();
      $oRetorno->oProcedimento->sTipoFormaAvaliacao   = $oProcedimentoAvaliacao->getFormaAvaliacao()->getTipo();
      $oRetorno->oProcedimento->iMaiorValor           = $oProcedimentoAvaliacao->getFormaAvaliacao()->getMaiorValor();
      $oRetorno->oProcedimento->iMenorValor           = $oProcedimentoAvaliacao->getFormaAvaliacao()->getMenorValor();
      $oRetorno->oProcedimento->mAproveitamentoMinimo = $oProcedimentoAvaliacao->getFormaAvaliacao()->getAproveitamentoMinino();
      $oRetorno->oProcedimento->nVariacao             = $oProcedimentoAvaliacao->getFormaAvaliacao()->getVariacao();


      break;

    /**
     * Gera classificação numerica da turma em ordem alfabética
     */
    case 'gerarNumeracao':

      foreach ($oParam->aTurmas as $oTurmaEtapa) {

        db_inicio_transacao();
        $oTurma = TurmaRepository::getTurmaByCodigo( $oTurmaEtapa->iTurma );

        if ( $oTurma->isClassificada() ) {
          continue;
        }

        $lTrocaTurma = $oParam->iTrocaTurma == "2";

        $oTurma->reclassificarNumeracaoDaTurma(false, $lTrocaTurma);
        db_fim_transacao();
      }

      $oRetorno->message = urlencode( _M(URL_MENSAGEM_TURMA_RPC."gerar_numeracao") );

      break;

    /**
     * Remove a classificação numérica da turma
     */
    case 'cancelarNumeracao':

      foreach ($oParam->aTurmas as $oTurmaEtapa) {

        db_inicio_transacao();
        $oTurma = TurmaRepository::getTurmaByCodigo( $oTurmaEtapa->iTurma );
        $oTurma->zerarNumeracaoDaTurma();
        db_fim_transacao();
      }

      $oRetorno->message = urlencode( _M(URL_MENSAGEM_TURMA_RPC."cancelar_numeracao") );


      break;

    /**
     * Retonar um array com os dados das vagas da turma por turno
     * Ex: array[iTurnoReferencia]->iVaga
     *     array[iTurnoReferencia]->iVagasDisponiveis
     *     array[iTurnoReferencia]->iVagasOcupadas
     *     array[iTurnoReferencia]->iTurnoReferente
     */
    case 'buscaVagasPorTurno':

      $aTurnoReferente   = array( 1 => 'manha', 2 => 'tarde', 3 => 'noite' );
      $oTurma            = TurmaRepository::getTurmaByCodigo($oParam->iTurma);
      $aVagas            = $oTurma->getVagas();
      $aVagasDisponiveis = $oTurma->getVagasDisponiveis();
      $aVagasOcupadas    = $oTurma->getVagasOcupadas();

      $aVagasTurma = array();

      foreach ($aVagas as $iTurnoReferente => $iVagas) {

        $oVagas                  = new stdClass();
        $oVagas->iVagas          = $iVagas;
        $oVagas->iTurnoReferente = $iTurnoReferente;

        if ( array_key_exists( $iTurnoReferente, $aTurnoReferente ) ) {
          $oVagas->sTurno = urlencode( $aTurnoReferente[ $iTurnoReferente ] );
        }

        $aVagasTurma[$iTurnoReferente] = $oVagas;
      }

      foreach ($aVagasDisponiveis as $iTurnoReferente => $iVagas) {
        $aVagasTurma[$iTurnoReferente]->iVagasDisponiveis = $iVagas;
      }

      foreach ($aVagasOcupadas as $iTurnoReferente => $iVagas) {
        $aVagasTurma[$iTurnoReferente]->iVagasOcupadas = $iVagas;
      }

      $oRetorno->aVagasTurma = $aVagasTurma;
      break;

    case 'getDadosEnsinoTurno':

      $oTurma                    = TurmaRepository::getTurmaByCodigo($oParam->iTurma);
      $oRetorno->lEnsinoInfantil = $oTurma->getBaseCurricular()->getCurso()->getEnsino()->isInfantil();
      $oRetorno->lTurnoIntegral  = $oTurma->getTurno()->isIntegral();

      break;

    case 'getInformacoesTurma':

      if ( !isset($oParam->iTurma) || $oParam->iTurma == "" ) {
        throw new Exception( _M(URL_MENSAGEM_TURMA_RPC."informe_turma") );

      }

      if ( !isset($oParam->iEtapa) || $oParam->iEtapa == "" ) {
        throw new Exception( _M(URL_MENSAGEM_TURMA_RPC."informe_etapa") );

      }

      $oTurma     = TurmaRepository::getTurmaByCodigo( $oParam->iTurma );
      $oEtapa     = EtapaRepository::getEtapaByCodigo( $oParam->iEtapa );

      $oRetorno->lTipoEja          = $oTurma->getTipoDaTurma() == 2 ? true : false;
      $oRetorno->lFrequenciaGlobal = false;

      foreach( $oTurma->getDisciplinasPorEtapa( $oEtapa ) as $oRegencia ) {

        if ( $oRegencia->getFrequenciaGlobal() == 'FA' || $oRegencia->getFrequenciaGlobal() == 'F' ) {
          $oRetorno->lFrequenciaGlobal = true;
        }
      }

      $oRetorno->iTurma = $oParam->iTurma;
      $oRetorno->iEtapa = $oParam->iEtapa;
      break;

    case 'getDependenciasComMaisDeUmaTurmaVinculada' :

      $iEscola = db_getsession('DB_coddepto');
      if ( isset($oParam->iEscola) && !empty($oParam->iEscola) ) {
        $iEscola = $oParam->iEscola;
      }

      $oEscola     = EscolaRepository::getEscolaByCodigo($iEscola);
      $oCalendario = CalendarioRepository::getCalendarioByCodigo($oParam->iCalendario);
      $oParam->iTurnoReferente;

      $aSalas = SalaRepository::getDependenciasComMaisDeUmaTurmaVinculada($oEscola, $oCalendario, $oParam->iTurnoReferente);

      $oRetorno->aDependencia = array();
      if ( count($aSalas) == 0 ) {
        throw new Exception( _M(URL_MENSAGEM_TURMA_RPC."nenhuma_dependencia_com_mais_de_uma_turma") );
      }

      foreach ( $aSalas as $oSala ) {

        $oDependencia             = new stdClass();
        $oDependencia->iCodigo    = $oSala->getCodigoSala();
        $oDependencia->sDescricao = urlencode($oSala->getDescricao());
        $oRetorno->aDependencia[] = $oDependencia;
      }
      break;

    /**
     * Salva o vinculo entre o profissional e sua atividade com a turma
     */
    case 'salvarVinculoProfissional':

      if ( !isset($oParam->iTurma) || $oParam->iTurma == "" ) {
        throw new ParameterException( _M(URL_MENSAGEM_TURMA_RPC."informe_turma") );
      }

      if ( !isset($oParam->iProfissional) || $oParam->iProfissional == "" ) {
        throw new ParameterException( _M(URL_MENSAGEM_TURMA_RPC."informe_profissional") );
      }

      if ( !isset($oParam->iAtividade) || $oParam->iAtividade == "" ) {
        throw new ParameterException( _M(URL_MENSAGEM_TURMA_RPC."informe_atividade") );
      }

      $oDaoOutrosProfissionais = new cl_turmaoutrosprofissionais();
      $sWhere  = "     ed347_rechumano       = {$oParam->iProfissional} ";
      $sWhere .= " and ed347_funcaoatividade = {$oParam->iAtividade}    ";
      $sWhere .= " and ed347_turma           = {$oParam->iTurma}        ";

      $sSqlOutrosProfissionais = $oDaoOutrosProfissionais->sql_query_file(null, "1", null, $sWhere);
      $rsOutrosProfissionais   = db_query( $sSqlOutrosProfissionais );

      if ( !$rsOutrosProfissionais ) {

        $oErro        = new stdClass();
        $oErro->sErro = pg_last_error();
        throw new DBException( _M(URL_MENSAGEM_TURMA_RPC."erro_buscar_outros_profissionais", $oErro) );
      }

      $oRetorno->message = urlencode( _M(URL_MENSAGEM_TURMA_RPC."profissional_ja_vinculado") );

      if ( pg_num_rows( $rsOutrosProfissionais ) == 0 ) {

        $oTurma = TurmaRepository::getTurmaByCodigo( $oParam->iTurma );
        $oTurma->salvarVinculoOutrosProfissionais( $oParam->iProfissional, $oParam->iAtividade );

        $oRetorno->message = urlencode( _M(URL_MENSAGEM_TURMA_RPC."salvo_sucesso") );
      }

      break;

    /**
     * Busca os profissionais que possuam atividade e estão vinculados com a turma
     */
    case 'buscarProfissionaisVinculados':

      if ( !isset($oParam->iTurma) || $oParam->iTurma == "" ) {
        throw new ParameterException( _M(URL_MENSAGEM_TURMA_RPC."informe_turma") );
      }

      $oTurma         = TurmaRepository::getTurmaByCodigo( $oParam->iTurma );
      $oRetorno->aProfissionais  = $oTurma->getProfissionaisVinculados();

      foreach ( $oRetorno->aProfissionais as $oProfissional ) {

        $oProfissional->nome      = urlencode($oProfissional->nome);
        $oProfissional->atividade = urlencode($oProfissional->atividade);
      }

      break;

    case 'excluirProfissionalVinculado':

      if ( !isset($oParam->iTurma) || $oParam->iTurma == "" ) {
        throw new ParameterException( _M(URL_MENSAGEM_TURMA_RPC."informe_turma") );
      }

      $oTurma = TurmaRepository::getTurmaByCodigo( $oParam->iTurma );
      $oTurma->excluirProfissionalVinculado( $oParam->iCodigoVinculo );

      $oRetorno->message = urlencode( _M(URL_MENSAGEM_TURMA_RPC."vinculo_excluido") );
      break;

    case 'getTermosResultadoFinal' :

      $oTurma  = EducacaoSessionManager::carregarTurma($oParam->iTurma);
      $oEnsino = $oTurma->getBaseCurricular()->getCurso()->getEnsino();

      $oRetorno->aTermos = array();
      foreach ($oEnsino->getTermosResultadoFinal($oTurma->getCalendario()->getAnoExecucao()) as $oTermo) {

        $oTermoRF = new stdClass();
        $oTermoRF->sDescricao   = urlencode($oTermo->ed110_descricao) ;
        $oTermoRF->sAbreviatura = urlencode($oTermo->ed110_abreviatura) ;
        $oTermoRF->sReferencia  = urlencode($oTermo->ed110_referencia);

        $oRetorno->aTermos[] = $oTermoRF;
      }

      break;

    /**
     * Verifica se o Procedimento de Avaliação informado para Regencia é diferente, se for, exclui o Diário do mesmo e
     * define o novo Procedimento de Avaliação
     */
    case 'salvarProcedimentoAvaliacao' :

      db_inicio_transacao();

      $sMensagem        = "";

      foreach ($oParam->aTurmas as $oDadosTurma) {

        $oTurma                      = TurmaRepository::getTurmaByCodigo( $oDadosTurma->iTurma );
        $oEtapa                      = EtapaRepository::getEtapaByCodigo( $oDadosTurma->iEtapa );
        $oProcedimentoAvaliacaoTurma = $oTurma->getProcedimentoDeAvaliacaoDaEtapa( $oEtapa );
        $sTipoFormaAvaliacaoTurma    = $oProcedimentoAvaliacaoTurma->getFormaAvaliacao()->getTipo();
        $lPossuiProcedimentoDaTurma  = false;

        foreach ($oParam->aDisciplinas as $oDadosDisciplina ) {

          $oProcedimentoAvaliacaoNovo = ProcedimentoAvaliacaoRepository::getProcedimentoByCodigo($oDadosDisciplina->iProcedimentoAvaliacao);
          $sTipoAvaliacaoNovo         = $oProcedimentoAvaliacaoNovo->getFormaAvaliacao()->getTipo();

          if ( $sTipoAvaliacaoNovo == $sTipoFormaAvaliacaoTurma ) {
            $lPossuiProcedimentoDaTurma = true;
          }
        }

        if ( !$lPossuiProcedimentoDaTurma ) {

          $sMensagem .= " - {$oTurma->getDescricao()}\n";
          continue;
        }

        foreach ($oParam->aDisciplinas as $oDadosDisciplina ) {

          $oDisciplina = DisciplinaRepository::getDisciplinaByCodigo( $oDadosDisciplina->iDisciplina );
          $oRegencia   = RegenciaRepository::getRegenciaByTurmaEtapaDisciplina( $oTurma, $oEtapa, $oDisciplina );

          if ( $oRegencia->getProcedimentoAvaliacao()->getCodigo() != $oDadosDisciplina->iProcedimentoAvaliacao ) {

            $oRegencia->excluirVinculoDiario();
            $oProcedimentoAvaliacao = ProcedimentoAvaliacaoRepository::getProcedimentoByCodigo($oDadosDisciplina->iProcedimentoAvaliacao);
            $oRegencia->setProcedimentoAvaliacao( $oProcedimentoAvaliacao );
            $oRegencia->salvar();
          }
        }
      }

      if( !empty($sMensagem) ) {

        $oMensagem            = new stdClass();
        $oMensagem->sMensagem = $sMensagem;
        $oRetorno->message    = urlencode( _M( URL_MENSAGEM_TURMA_RPC . 'turmas_nao_alteradas', $oMensagem ) );
        $oRetorno->erro       = true;
        db_fim_transacao();
        break;
      }

      $oRetorno->message = urlencode( _M( URL_MENSAGEM_TURMA_RPC . 'procedimentos_salvo_sucesso' ) );
      db_fim_transacao();
      break;

    /**
     * Busca todas as turmas que estejam no calendário e escola informados
     * Parâmetros( opcionais ):
     * - lSomenteComAlunosMatriculados: retorna somente turmas com alunos matriculados
     * - lSomenteComCriterioAvaliacao:  retorna somente turmas com critério de avaliação configurado
     * - lSomenteAtivas:                retorna somente turmas ainda não encerradas
     * - lSomenteProgressaoEncerrada:   retorna somente turmas que tenham alunos em progressão para alguma disciplina,
     *                                  e que estejam todas as progressões encerradas
     */
    case 'buscaTurmasPorCalendarioEscola' :

      if ( db_getsession("DB_modulo") != $iModuloEscola && ( !isset($oParam->iEscola) || empty($oParam->iEscola) ) ) {
        throw new ParameterException( _M( URL_MENSAGEM_TURMA_RPC . 'informe_escola' ) );
      }

      if ( !isset($oParam->iCalendario) || empty($oParam->iCalendario) ) {
        throw new ParameterException( _M( URL_MENSAGEM_TURMA_RPC . 'informe_calendario' ) );
      }

      $oRetorno->aTurmas = array();
      $iCodigoEscola     = !empty( $oParam->iEscola ) ? $oParam->iEscola : $iEscola;
      $oEscola           = EscolaRepository::getEscolaByCodigo( $iCodigoEscola );
      $oCalendario       = CalendarioRepository::getCalendarioByCodigo( $oParam->iCalendario );
      $oEtapa            = null;
      $aTipoTurmaFora    = isset( $oParam->aTipoTurmaFora ) ? $oParam->aTipoTurmaFora : array();

      if ( isset( $oParam->iEtapa ) && !empty($oParam->iEtapa) ) {
        $oEtapa = EtapaRepository::getEtapaByCodigo( $oParam->iEtapa );
      }

      $aTurmas = TurmaRepository::getTurmaPorCalendarioEscola( $oEscola, $oCalendario );

      foreach( $aTurmas as $oTurma ) {

        if(    isset( $oParam->lSomenteComAlunosMatriculados )
            && $oParam->lSomenteComAlunosMatriculados
            && !empty($oEtapa)
            && count( $oTurma->getAlunosMatriculadosNaTurmaPorSerie( $oEtapa ) ) == 0
          ) {
          continue;
        }

        if(    isset( $oParam->lSomenteComCriterioAvaliacao )
            && $oParam->lSomenteComCriterioAvaliacao
            && !$oTurma->possuiCriterioAvaliacao()
          ) {
          continue;
        }

        if( isset( $oParam->lSomenteProgressaoEncerrada ) && $oParam->lSomenteProgressaoEncerrada ) {

          $lTemProgressaoEncerrada = false;

          foreach( $oTurma->getDisciplinas() as $oRegencia ) {

            if( count( ProgressaoParcialAlunoRepository::getProgressoesVinculadasRegencia( $oRegencia, 1 ) ) > 0 ) {
              $lTemProgressaoEncerrada = true;
            }
          }

          if( !$lTemProgressaoEncerrada ) {
            continue;
          }
        }

        foreach( $oTurma->getEtapas() as $oEtapaTurma ) {

          if( !empty( $oEtapa ) && $oEtapaTurma->getEtapa()->getCodigo() != $oEtapa->getCodigo() ) {
            continue;
          }

          if(    isset( $oParam->lSomenteAtivas )
              && $oParam->lSomenteAtivas
              && ( $oTurma->encerradaNaEtapa( $oEtapaTurma->getEtapa() ) || $oTurma->encerradaParcial( $oEtapaTurma->getEtapa() ) )
            ) {
            continue;
          }

          if ( in_array($oTurma->getTipoDaTurma(), $aTipoTurmaFora) ) {
            continue;
          }

          $oDadosTurma         = new stdClass();
          $oDadosTurma->iTurma = $oTurma->getCodigo();
          $oDadosTurma->sTurma = urlencode( $oTurma->getDescricao() );
          $oDadosTurma->iEtapa = $oEtapaTurma->getEtapa()->getCodigo();
          $oDadosTurma->sEtapa = urlencode( $oEtapaTurma->getEtapa()->getNome() );

          $oRetorno->aTurmas[] = $oDadosTurma;
        }
      }

      break;

    /**
     * Compara entre duas Turmas se suas Disciplinas possuem o mesmo Procedimento de Avaliação vinculados há Regência
     */
    case 'comparaRegenciasEntreTurmas' :

      if ( !isset($oParam->iTurmaAtual) || empty($oParam->iTurmaAtual) ) {
        throw new ParameterException( _M( URL_MENSAGEM_TURMA_RPC . 'turma_atual_nao_informada' ) );
      }

      if ( !isset($oParam->iTurmaDestino) || empty($oParam->iTurmaDestino) ) {
        throw new ParameterException( _M( URL_MENSAGEM_TURMA_RPC . 'turma_destino_nao_informada' ) );
      }

      $oEtapaOrigem   = EtapaRepository::getEtapaByCodigo($oParam->iEtapaOrigem);
      $aEtapasDestino = explode(',', $oParam->sEtapasDestino);
      $oEtapaDestino  = null;

      /**
       * Como turma de destino pode ser uma turma multietapa verifica-se a etapa da turma de
       * origem encontra na(s) etapa(s) de destino. Se não, valida se há etapa de destino são equivalente
       */
      if ( in_array($oEtapaOrigem->getCodigo(), $aEtapasDestino) ) {
        $oEtapaDestino = $oEtapaOrigem;
      } else {

        foreach ( $oEtapaOrigem->buscaEtapaEquivalente() as $oEtapaEquivalente) {

          if ( in_array($oEtapaEquivalente->getCodigo(), $aEtapasDestino) ) {

            $oEtapaDestino = $oEtapaEquivalente;
            break;
          }
        }
      }

      if ( is_null($oEtapaDestino) ) {
        $oRetorno->lPossuiMesmoProcedimentos = false;
      }

      $oTurmaAtual                         = TurmaRepository::getTurmaByCodigo($oParam->iTurmaAtual);
      $oTurmaDestino                       = TurmaRepository::getTurmaByCodigo($oParam->iTurmaDestino);
      $oRetorno->lPossuiMesmoProcedimentos = $oTurmaAtual->possuiMesmoProcedimentosAvaliacao($oTurmaDestino,
                                                                                             $oEtapaOrigem,
                                                                                             $oEtapaDestino);
      break;

    case 'buscaTurmasComMaisUmProcedimentoAvaliacao' :
      /**
       * Assim que a rotina:
       * Procedimento > Diário de Classe > Lancamento por período
       * Sair do ar em 2015, podemos remover esse case
       */

      $sCampos  = " ed52_i_codigo, trim(ed52_c_descr) as calendario, ed57_i_codigo, trim(ed57_c_descr) as turma, ";
      $sCampos .= " trim(ed11_c_descr) as serie, ed223_i_serie, ed220_i_procedimento ";

      $sWhere    = " ed57_i_escola =  " . $iEscola;
      $sGroupBy  = " group by ed52_i_codigo, ed52_c_descr, ed57_i_codigo, ed57_c_descr, ed11_c_descr, ";
      $sGroupBy .= " ed223_i_serie, ed220_i_procedimento,ed11_i_sequencia ";

      $sHaving  = "  having  (select count(*) ";
      $sHaving .= "            from regencia ";
      $sHaving .= "           where ed59_i_turma = ed57_i_codigo ";
      $sHaving .= "             and ed59_i_serie = ed223_i_serie ";
      $sHaving .= "             and ed59_procedimento <> ed220_i_procedimento) > 0 ";

      $sOrdem = " ed52_i_ano, ed11_i_sequencia ";

      $oDaoTurma  = new cl_turma();
      $sSqlTurmas = $oDaoTurma->sql_query_turma(null, $sCampos, $sOrdem , $sWhere . $sGroupBy . $sHaving);
      $rsTurmas   = db_query($sSqlTurmas);

      $aTurmas = array();
      if ($rsTurmas && pg_num_rows($rsTurmas) > 0) {

        $iLinhas = pg_num_rows($rsTurmas);

        for ($i = 0; $i < $iLinhas; $i ++) {

          $oDados      = db_utils::fieldsMemory($rsTurmas, $i);
          $sTurmaEtapa = "{$oDados->turma} - {$oDados->serie}";

          if (!array_key_exists($oDados->ed52_i_codigo, $aTurmas)) {

            $oCalendario          = new stdClass();
            $oCalendario->sNome   = urlencode($oDados->calendario);
            $oCalendario->aTurmas = array();
            $aTurmas[$oDados->ed52_i_codigo] = $oCalendario;
          }

          $aTurmas[$oDados->ed52_i_codigo]->aTurmas[] = urlencode($sTurmaEtapa);
        }
      }
      $oRetorno->lPossue = count($aTurmas) > 0;
      $oRetorno->aTurmas = $aTurmas;

      break;

    case 'periodosCompoemResultado':

      if ( !isset($oParam->iTurma) || empty($oParam->iTurma) ) {
        throw new ParameterException( _M( URL_MENSAGEM_TURMA_RPC . 'informe_turma' ) );
      }

      if ( !isset($oParam->iEtapa) || empty($oParam->iEtapa) ) {
        throw new ParameterException( _M( URL_MENSAGEM_TURMA_RPC . 'informe_etapa' ) );
      }

      $oRetorno->aPeriodos    = array();
      $oTurma                 = EducacaoSessionManager::carregarTurma($oParam->iTurma);
      $oEtapa                 = EducacaoSessionManager::carregarEtapa($oParam->iEtapa);
      $oProcedimentoAvaliacao = $oTurma->getProcedimentoDeAvaliacaoDaEtapa( $oEtapa );
      $aResultadoFinal        = array();

      /**
       * Pega o primeiro resultado que gera resultado final
       */
      foreach ($oProcedimentoAvaliacao->getResultados() as $oElementoResultado) {

        if( $oElementoResultado->utilizaProporcionalidade() ) {
          $aResultadoFinal[] = $oElementoResultado;
        }
      }

      if ( count($aResultadoFinal) == 0) {
        throw new Exception( _M( URL_MENSAGEM_TURMA_RPC . 'turma_sem_procedimento_resultado_final' ) );
      }

      foreach( $aResultadoFinal as $oResultadoFinal ) {

        $aElementosResultado   = $oResultadoFinal->getElementosComposicaoResultado();
        $iElementosPercorridos = 0;
        $iTotalElementos       = count($aElementosResultado);
        foreach ($aElementosResultado as $oElemento) {

          $iElementosPercorridos++;
          $oDadosElemento         = new stdClass();

          if ( $oElemento instanceof ResultadoAvaliacao) {

            $oDadosElemento->iOrdem     = $oElemento->getOrdemSequencia();
            $oDadosElemento->sDescricao = urlencode( $oElemento->getDescricao() );
          } else if ($oElemento instanceof ResultadoAvaliacaoComposicao) {

            $oDadosElemento->iOrdem     = $oElemento->getOrdem();
            $oDadosElemento->sDescricao = urlencode( $oElemento->getElementoAvaliacao()->getDescricao() );
          } else {

            /**
             * Quando elemento for AvaliacaoPeriodica
             */
            $oDadosElemento->iOrdem     = $oElemento->getOrdemSequencia();
            $oDadosElemento->sDescricao = urlencode( $oElemento->getDescricao() );
          }
          $oDadosElemento->lBloqueia = $iElementosPercorridos == $iTotalElementos;
          $oRetorno->aPeriodos[]     = $oDadosElemento;
        }
      }


      break;
  }
} catch (Exception $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
  $oRetorno->erro    = true;
}

echo $oJson->encode($oRetorno);
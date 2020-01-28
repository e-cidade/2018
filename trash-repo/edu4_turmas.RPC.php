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
require_once ("model/educacao/avaliacao/iFormaObtencao.interface.php");
require_once ("model/educacao/avaliacao/iElementoAvaliacao.interface.php");

define("URL_MENSAGEM_TURMA_RPC", "educacao.escola.edu4_turmas_RPC.");

$iEscola           = db_getsession("DB_coddepto");
$oJson             = new Services_JSON();
$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

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
      $rsMatricula      = $oDaoMatricula->sql_record($sSqlMatricula);

      $oRetorno->iMatriculaOrigem = '';
      $oRetorno->sTurmaOrigem     = '';
      $oRetorno->sEtapaOrigem     = '';
      $oRetorno->sSituacaoOrigem  = '';
      $oRetorno->sTurnoOrigem     = '';

      if ($oDaoMatricula->numrows > 0) {

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
     * Retorna as regencias inconsistentes na troca de turma sem registro de movimentaчуo, e se o procedimento de
     * avaliaчуo das turmas щ equivalente
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
     * Salvamos as alteraчѕes da troca de turma sem registro de movimentaчуo
     */
    case 'salvarTrocaTurmaSemRegistro':

      db_inicio_transacao();

      if (isset($oParam->iMatricula) && isset($oParam->iTurmaDestino)) {

        $aRegenciasVinculadas = array();
        $oMatricula           = new Matricula($oParam->iMatricula);
        $oTurmaDestino        = new Turma($oParam->iTurmaDestino);
        $oTrocaTurma          = new TrocaTurma($oMatricula, $oTurmaDestino);

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

      db_fim_transacao(false);
      break;

    case 'getRegencias':

      $oTurma = TurmaRepository::getTurmaByCodigo($oParam->iTurma);
      $oEtapa = EtapaRepository::getEtapaByCodigo($oParam->iEtapa);

      $oDocente   = null;
      $lProfessor = false;
      if ($oParam->lDisciplinasProfessor) {

        $oDocente = DocenteRepository::getDocenteLogado(db_getsession("DB_id_usuario"));

        /**
         * Como a classe Docente retorna uma instancia de qualquer cgm da base, devemos verificar se
         * o "Docente" retornado щ mesmo um docente. Uma das formas de tazer isto, щ validando em
         * quantas turmas o "Docente" esta lecionando
         */
        if (!empty($oDocente) && count($oDocente->getTurmas()) > 0) {
          $lProfessor = true;
        }
      }

      $oRetorno->aRegencias = array();
      foreach ($oTurma->getDisciplinasPorEtapa($oEtapa) as $oRegencia)  {

        $oDisciplina              = new stdClass();
        $oDisciplina->iRegencia   = $oRegencia->getCodigo();
        $oDisciplina->sDisciplina = urlencode($oRegencia->getDisciplina()->getNomeDisciplina());

        /**
         * Se o usuсrio logado for um Docente, devemos retornar as diciplinas este leciona,
         * se nуo for um docente, retornamos todas as disciplinas
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

        $oTurma = TurmaRepository::getTurmaByCodigo($oParam->iTurma);

        $aAlunosMatriculado = array();
        if (!empty($oParam->iEtapa)) {

          $oEtapa             = EtapaRepository::getEtapaByCodigo($oParam->iEtapa);
          $aAlunosMatriculado = $oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapa);
        } else {
          $aAlunosMatriculado = $oTurma->getAlunosMatriculados(false);
        }

        foreach ($aAlunosMatriculado as $oMatricula) {

          if ($oMatricula->getSituacao() != 'MATRICULADO') {
            continue;
          }

          $oDadosAluno             = new stdClass();
          $oDadosAluno->iMatricula = $oMatricula->getCodigo();
          $oDadosAluno->sNome      = urlencode($oMatricula->getAluno()->getNome());
          $oRetorno->aAlunos[]     = $oDadosAluno;
        }
      }
      TurmaRepository::removerTurma($oTurma);
      if (isset($oEtapa)) {
        EtapaRepository::removerEtapa($oEtapa); 
      }
      break;

    case 'pesquisaPeriodosTurma':

      $oRetorno->aPeriodos = array();
      if (!empty($oParam->iTurma) && !empty($oParam->iEtapa)) {

        $oTurma     = TurmaRepository::getTurmaByCodigo($oParam->iTurma);
        $oEtapa     = EtapaRepository::getEtapaByCodigo($oParam->iEtapa);
        $aElementos = $oTurma->getProcedimentoDeAvaliacaoDaEtapa($oEtapa)->getElementos();

        foreach ($aElementos as $oElemento) {

          if ($oParam->lSomentePeriodosAvaliacao && $oElemento instanceof ResultadoAvaliacao) {
            continue;
          }
          $oDadosPeriodo             = new stdClass();
          $oDadosPeriodo->iPeriodo   = $oElemento->getCodigo();
          $oDadosPeriodo->sDescricao = urlencode($oElemento->getDescricao());
          $oDadosPeriodo->iOrdem     = $oElemento->getOrdemSequencia();
          $oRetorno->aPeriodos[]     = $oDadosPeriodo;
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
     * Gera classificaчуo numerica da turma em ordem alfabщtica
     */
    case 'gerarNumeracao':
      
      foreach ($oParam->aTurmas as $oTurmaEtapa) {
      	
        db_inicio_transacao();
        $oTurma = TurmaRepository::getTurmaByCodigo( $oTurmaEtapa->iTurma );
        
        if ( $oTurma->isClassificada() ) {
        	continue;
        }
        $oTurma->reclassificarNumeracaoDaTurma(false);
        db_fim_transacao();
      }
      
      $oRetorno->message = urlencode( _M(URL_MENSAGEM_TURMA_RPC."gerar_numeracao") );
      
      break;
      
    /**
     * Remove a classificaчуo numщrica da turma
     */
    case 'cancelarNumeracao':
      
      foreach ($oParam->aTurmas as $oTurmaEtapa) {
         
        db_inicio_transacao();
        $oTurma = TurmaRepository::getTurmaByCodigo( $oTurmaEtapa->iTurma );
        $oTurma->zerarNumeracaoDaTurma(false);
        db_fim_transacao();
      }
      
      $oRetorno->message = urlencode( _M(URL_MENSAGEM_TURMA_RPC."cancelar_numeracao") );
      
    
      break;
      
  }
} catch (ParameterException $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
} catch (BusinessException $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
} catch (DBException $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
}

echo $oJson->encode($oRetorno);
?>
<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("std/DBDate.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("model/educacao/avaliacao/iFormaObtencao.interface.php"));
require_once(modification("model/educacao/avaliacao/iElementoAvaliacao.interface.php"));
/* ATENCAO: PLUGIN ParametroProgressaoParcial - Requires - INSTALADO AQUI - NAO REMOVER */

$iEscola           = db_getsession("DB_coddepto");

$oJson             = new Services_JSON();
$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

switch($oParam->exec) {

  /**
   * Retorna um array com os alunos matriculados em uma turma, com base no codigo desta e da etapa
   */
  case 'getAlunosMatriculados':

    $oTurma                   = new Turma($oParam->iCodigoTurma);
    $oEtapa                   = EtapaRepository::getEtapaByCodigo($oParam->iEtapa);
    $aAlunosMatriculadosTurma = $oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapa);
    $iCodigoEnsino            = $oTurma->getBaseCurricular()->getCurso()->getEnsino()->getCodigo();
    $iAnoCalendario           = $oTurma->getCalendario()->getAnoExecucao();
    $oRetorno->aDadosAlunos   = array();
    $aTermosAprovado          = DBEducacaoTermo::getTermoEncerramento($iCodigoEnsino, 'A', $iAnoCalendario);

    if (count($aTermosAprovado) > 0) {
      $sLabelAprovado = $aTermosAprovado[0]->sDescricao;
    }

    $lPermiteAprovacaoParcial = EncerramentoAvaliacao::permiteAprovacaoParcial($oTurma, $oEtapa);
    /**
     * Percorremos as matriculas retornadas, para preencher com as informacoes de cada aluno
     */
    foreach ($aAlunosMatriculadosTurma as $oMatricula) {

      if ( isset($oParam->lTrocaTurma) && !$oParam->lTrocaTurma &&
           ($oMatricula->getSituacao() == 'TROCA DE TURMA') ) {
        continue;
      }

      $oDadosAluno                         = new stdClass();
      $oDadosAluno->iCodigoAluno           = $oMatricula->getAluno()->getCodigoAluno();
      $oDadosAluno->iMatricula             = $oMatricula->getCodigo();
      $oDadosAluno->sNomeAluno             = urlencode($oMatricula->getAluno()->getNome());
      $oDadosAluno->iOrdemAluno            = $oMatricula->getNumeroOrdemAluno();
      $oDadosAluno->sSituacao              = urlencode($oMatricula->getSituacao());
      $oDadosAluno->lConcluido             = $oMatricula->isConcluida();
      $oDadosAluno->lEncerrado             = false;  // Adicionado para compor as variaveis padrao de retorno
      $oDadosAluno->sDisciplina            = "";
      $oDadosAluno->sEncerrado             = "";
      $oDadosAluno->aPendencias            = array();
      $oDadosAluno->lTemMatriculaPosterior = false;
      $oDadosAluno->lSemPendencia          = false;

      if( isset( $oParam->lCancelamento ) || in_array($oMatricula->getSituacao(), array("TRANSFERIDO REDE", "TRANSFERIDO FORA") ) ) {
        $oDadosAluno->lTemMatriculaPosterior = MatriculaPosterior( $oMatricula->getCodigo() );
      }

      /**
       * Buscamos o resultado final do diario da matricula, e apresentamos o termo correto de acordo com o cadastrado
       * para o ensino, caso exista um resultado final
       */
      db_inicio_transacao();

      /**
       * Caso a matricula esteja com situacao diferente de matriculado (Ex.: Transferido Rede), atribuimos o valor da
       * situacao para ser apresentado no resultado final, para que nao fique em branco
       */
      if ($oMatricula->getSituacao() != 'MATRICULADO') {

        $oDadosAluno->lSemPendencia   = true;
        $oDadosAluno->lSemPendencia   = $oMatricula->getSituacao() == 'TROCA DE TURMA' ? false : $oDadosAluno->lSemPendencia;
        $oDadosAluno->sResultadoFinal = $oDadosAluno->sSituacao;
      } else {

        $oDadosAluno->sResultadoFinal = $oMatricula->getDiarioDeClasse()->getResultadoFinal();

        /**
         * Buscamos o termo correto para o resultado final
         */
        if (!empty($oDadosAluno->sResultadoFinal)) {

          if ($lPermiteAprovacaoParcial && $oDadosAluno->sResultadoFinal == 'A' &&
              EncerramentoAvaliacao::validaDiarioAlunoEja($oMatricula, $oEtapa) == "P") {
            $oDadosAluno->sResultadoFinal = 'P';
          }
          $aTermosEncerramento = DBEducacaoTermo::getTermoEncerramento($iCodigoEnsino,
                                                                       $oDadosAluno->sResultadoFinal,
                                                                       $oMatricula->getTurma()->getCalendario()
                                                                                              ->getAnoExecucao()
                                                                      );
          if (count($aTermosEncerramento) > 0) {
            $oDadosAluno->sResultadoFinal = urlencode($aTermosEncerramento[0]->sDescricao);
          }

          if ($oMatricula->getDiarioDeClasse()->aprovadoComProgressaoParcial()) {
            $oDadosAluno->sResultadoFinal = urlencode(" {$sLabelAprovado} (Progressão Parcial / Dependência)");
          }

          /**
           * 1ª Modificacao - Plugin Progressao parcial
           */
          if ($oMatricula->getDiarioDeClasse()->temRecuperacao() ) {
            $oDadosAluno->sResultadoFinal = urlencode("EM RECUPERAÇÃO");
          }
        }


        /**
         * Verifica se esta encerrando ou cancelando o encerramento para validar de formas dfirerentes cada caso
         */
        $sMetodo                    = !isset( $oParam->lCancelamento ) ? "getPendenciasEncerramento" : "getPendenciasCancelamentoEncerramento";
        $oDadosAluno->aPendencias   = $oMatricula->getDiarioDeClasse()->{$sMetodo}();
        $oDadosAluno->lSemPendencia = $oMatricula->getDiarioDeClasse()->aptoParaEncerramento();

        if( isset( $oParam->lCancelamento ) ) {
          $oDadosAluno->lSemPendencia = count( $oDadosAluno->aPendencias ) == 0;
        }

        //corrige a codificação das pendências
        $aPendenciasTratadas = array();
        foreach($oDadosAluno->aPendencias as $sPendencia){
          $aPendenciasTratadas[] = urlencode($sPendencia);
        }
        $oDadosAluno->aPendencias = $aPendenciasTratadas;
      }


      if ( isset( $oParam->lCancelamento ) && $oMatricula->hasTransferenciaEncerrada() ) {

        $oDadosAluno->lTemMatriculaPosterior = true;
        $oDadosAluno->lSemPendencia          = false;
        $oDadosAluno->aPendencias[]          = urlencode("Aluno transferido após o encerramento.");
      }

      db_fim_transacao(false);
      $oRetorno->aDadosAlunos[] = $oDadosAluno;
    }

    break;

  /**
   * Retorna um array de alunos de progressao e suas informacoes
   */
  case 'getAlunosDeProgressao':

      $oTurma                 = new Turma($oParam->iCodigoTurma);
      $iCodigoEnsino          = $oTurma->getBaseCurricular()->getCurso()->getEnsino()->getCodigo();
      $aAlunosDeProgressao    = $oTurma->getAlunosProgressaoParcial(EtapaRepository::getEtapaByCodigo($oParam->iEtapa));
      $oRetorno->aDadosAlunos = array();
      $iOrdemAluno            = 1;
      $iAnoCalendario         = $oTurma->getCalendario()->getAnoExecucao();

      /**
       * Percorremos cada aluno de progressao
       */
      foreach ($aAlunosDeProgressao as $oAlunoProgressao) {

        $oVinculoRegencia = $oAlunoProgressao->getVinculoRegenciaNaTurma($oTurma);
        if (empty($oVinculoRegencia)) {
          continue;
        }
        $sEncerrado = $oVinculoRegencia->isEncerrado() ? "Sim" : "Não" ;

        $oDadosAlunoProgressao                  = new stdClass();
        $oDadosAlunoProgressao->iCodigoAluno    = $oAlunoProgressao->getAluno()->getCodigoAluno();
        $oDadosAlunoProgressao->sNomeAluno      = urlencode($oAlunoProgressao->getAluno()->getNome());
        $oDadosAlunoProgressao->iOrdemAluno     = $iOrdemAluno;
        $oDadosAlunoProgressao->sDisciplina     = urlencode($oAlunoProgressao->getDisciplina()->getAbreviatura());
        $oDadosAlunoProgressao->sEncerrado      = urlencode($sEncerrado);
        $oDadosAlunoProgressao->lEncerrado      = $oVinculoRegencia->isEncerrado();
        $oDadosAlunoProgressao->sResultadoFinal = urlencode($oAlunoProgressao->getResultadoFinal()->getResultado());
        $oDadosAlunoProgressao->iProgressao     = $oAlunoProgressao->getCodigoProgressaoParcial();

        /**
         * Buscamos o termo correto para o resultado final
         */
        if (!empty($oDadosAlunoProgressao->sResultadoFinal)) {

          $aTermosEncerramento = DBEducacaoTermo::getTermoEncerramento($iCodigoEnsino, $oDadosAlunoProgressao->sResultadoFinal, $iAnoCalendario);

          if (count($aTermosEncerramento) > 0) {
            $oDadosAlunoProgressao->sResultadoFinal = urlencode($aTermosEncerramento[0]->sDescricao);
          }
        }

        if (empty($oDadosAlunoProgressao->sResultadoFinal)) {
          $oDadosAlunoProgressao->sResultadoFinal = urlencode("EM ANDAMENTO");
        }

        /* PLUGIN DIARIO PROGRESSAO - isEvadido - NÃO APAGAR */

        $iOrdemAluno++;
        $oRetorno->aDadosAlunos[] = $oDadosAlunoProgressao;
      }

    break;

  /**
   * Retorna os dados referentes a uma matricula
   */
  case 'buscaDadosMatricula':

    $oMatricula = new Matricula($oParam->iMatricula);

    /**
     * Buscamos os dados da Matricula do aluno
     */
    $oRetorno->sNomeAluno         = urlencode($oMatricula->getAluno()->getNome());
    $oRetorno->iCodigoAluno       = $oMatricula->getAluno()->getCodigoAluno();
    $oRetorno->sSituacaoAluno     = urlencode($oMatricula->getSituacao());
    $oRetorno->iCodigoTurma       = $oMatricula->getTurma()->getCodigo();
    $oRetorno->dtMatricula        = $oMatricula->getDataMatricula()->convertTo(DBDate::DATA_PTBR);
    $oRetorno->dtSaida            = str_repeat("&nbsp;", 10);
    if ($oMatricula->getDataEncerramento() != "") {
      $oRetorno->dtSaida  = $oMatricula->getDataEncerramento()->convertTo(DBDate::DATA_PTBR);
    }
    $oRetorno->sCalendario        = urlencode($oMatricula->getTurma()->getCalendario()->getDescricao());
    $oRetorno->sTurma             = urlencode($oMatricula->getTurma()->getDescricao());

    $oGradeAproveitamento         = new GradeAproveitamentoAluno($oMatricula);
    $oRetorno->mMinimoAprovacao   = urlencode($oGradeAproveitamento->getMinimoParaAprovacao());
    break;

  /**
   * Encerra as avaliacoes de Progressao Parcial
   */
  case 'encerrarProgressaoParcial':

    $aAlunosNaoProcessados = array();
    $oRetorno->lImprimeRel = true;

    if (count($oParam->aAlunos) > 0) {

      $oEncerramento    = new EncerramentoAvaliacao(new DBLogJSON("tmp/encerramento.json"));
      foreach ($oParam->aAlunos as $oAluno) {

        db_inicio_transacao();

        $oAlunoProgressao = new ProgressaoParcialAluno($oAluno->iProgressao);
        if ($oEncerramento->encerrarProgressaoParcial($oAlunoProgressao)) {

          $oRetorno->status  = 1;
          $oRetorno->message = "Encerramento processado com sucesso!";
        } else {

          $oAlunoNaoProcessado              = new stdClass();
          $oAlunoNaoProcessado->iProgressao = $oAlunoProgressao->getCodigoProgressaoParcial();
          $oAlunoNaoProcessado->sNome       = $oAlunoProgressao->getAluno()->getNome();
          $aAlunosNaoProcessados[]          = $oAlunoNaoProcessado;
          db_fim_transacao(true);
        }

        db_fim_transacao();
      }
    }

    if (count($aAlunosNaoProcessados) > 0) {

      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = "Não foi possível processar o encerramento das avaliações.";
    }

    $oRetorno->message = urlencode($oRetorno->message);
    break;

  /**
   * Cancela o encerramento de uma ou mais Progressoes Parciais
   */
  case 'cancelarEncerramentoProgressaoParcial':

    $aAlunosNaoProcessados = array();
    $oRetorno->lImprimeRel = true;

    if (count($oParam->aAlunos) > 0) {

      $oEncerramento    = new EncerramentoAvaliacao(new DBLogJSON("tmp/encerramento.json"));
      foreach ($oParam->aAlunos as $oAluno) {

        db_inicio_transacao();

        $oAlunoProgressao = new ProgressaoParcialAluno($oAluno->iProgressao);
        $oAlunoProgressao->setSituacaoProgressao(SituacaoEducacaoRepository::getSituacaoEducacaoByCodigo(ProgressaoParcialAluno::ATIVA));

        if ($oEncerramento->cancelarEncerramentoProgressaoParcial($oAlunoProgressao)) {

          $oRetorno->status  = 1;
          $oRetorno->message = "Cancelamento do Encerramento processado com sucesso!";
        } else {


          $oAlunoNaoProcessado              = new stdClass();
          $oAlunoNaoProcessado->iProgressao = $oAlunoProgressao->getCodigoProgressaoParcial();
          $oAlunoNaoProcessado->sNome       = $oAlunoProgressao->getAluno()->getNome();
          $aAlunosNaoProcessados[]          = $oAlunoNaoProcessado;
          db_fim_transacao(true);
        }
        db_fim_transacao();
      }
    }

    if (count($aAlunosNaoProcessados) > 0) {

      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = "Não foi possível processar o cancelamento do encerramento das avaliações.";
    }

    $oRetorno->message = urlencode($oRetorno->message);
    break;

  case 'encerrarAvaliacoes':

    try {

      $oEncerramento                = new EncerramentoAvaliacao(new DBLogJSON("tmp/encerramento.json"));
      $oRetorno->lImprimeRel        = false;
      $oRetorno->aTurmasProcessadas = array();
      $oRetorno->message            = "Encerramento processado com sucesso!";
      $oRetorno->status             = 1;
      foreach ($oParam->aTurmas as $oTurmaEtapa) {

        $oTurma = new Turma($oTurmaEtapa->iTurma);
        $oEtapa = EtapaRepository::getEtapaByCodigo($oTurmaEtapa->iEtapa);

        db_inicio_transacao();
        if ($oEncerramento->encerrarAvaliacaoTurma($oTurma, $oEtapa)) {
          $oRetorno->aTurmasProcessadas[] = $oTurmaEtapa;
        }
        db_fim_transacao(false);
      }

      if (count($oRetorno->aTurmasProcessadas) != count($oParam->aTurmas)) {

        $oRetorno->lImprimeRel = true;
        $oRetorno->status      = 2;
        $oRetorno->message     = "Não foi possível encerrar as avaliações.";
      }
    } catch (Exception $oErro) {

      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = $oErro->getMessage();
    }
    $oRetorno->message = urlencode($oRetorno->message);

    break;

  case 'encerrarAvaliacoesPorAluno' :

    try {

      $oEncerramento                = new EncerramentoAvaliacao(new DBLogJSON("tmp/encerramento.json"));
      $oRetorno->aTurmasProcessadas = array();
      $oRetorno->lImprimeRel        = false; // Para manter compatibilidade
      $oRetorno->status             = 1;
      $oRetorno->message            = "Encerramento processado com sucesso!";

      foreach ($oParam->aTurmas as $oTurmaEtapa) {

        $oDadosMatriculaAluno          = new stdClass();
        $oDadosMatriculaAluno->aAlunos = $oParam->aAlunos;
        $oDadosMatriculaAluno->iEtapa  = $oTurmaEtapa->iEtapa;

        $oTurma = new Turma($oTurmaEtapa->iTurma);
        db_inicio_transacao();

        $aMatriculas = getMatriculasAlunoTurma($oDadosMatriculaAluno, $oTurma);
        $iQuantidadeMatriculasEncerradas = $oEncerramento->encerrarAvaliacaoTurmaPorAluno($aMatriculas);
        $oRetorno->aTurmasProcessadas[]  = $oTurmaEtapa;

        db_fim_transacao(false);
      }

      if ( $iQuantidadeMatriculasEncerradas != count($oParam->aAlunos) ) {

        $oRetorno->lImprimeRel = true;
        $oRetorno->status      = 2;
        $oRetorno->message     = "Não foi possível encerrar as avaliações.";
      }

    } catch (Exception $oErro) {

      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = $oErro->getMessage();
    }
    $oRetorno->message = urlencode($oRetorno->message);
    break;

  case 'cancelarEncerramento':

    try {

      $oEncerramento                = new EncerramentoAvaliacao(new DBLogJSON("tmp/encerramento.json"));
      $oRetorno->lImprimeRel        = false;
      $oRetorno->aTurmasProcessadas = array();

      foreach ($oParam->aTurmas as $oTurmaEtapa) {

        $oTurma = new Turma($oTurmaEtapa->iTurma);
        $oEtapa = EtapaRepository::getEtapaByCodigo($oTurmaEtapa->iEtapa);
        db_inicio_transacao();

        if ($oEncerramento->cancelarEncerramentoTurma($oTurma, $oEtapa)) {

          $oRetorno->aTurmasProcessadas[] = $oTurmaEtapa;
          $oRetorno->status               = 1;
          $oRetorno->message              = "Cancelamento do encerramento processado com sucesso!";
        } else {

          $oRetorno->aTurmasProcessadas[] = $oTurmaEtapa;
          $oRetorno->lImprimeRel          = true;
          $oRetorno->status               = 2;
          $oRetorno->message              = "Não foi possível cancelar o encerramento das avaliações.";
        }

        db_fim_transacao(false);
      }
    } catch (Exception $oErro) {

      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = $oErro->getMessage();
    }
    $oRetorno->message = urlencode($oRetorno->message);

    break;

  case 'cancelarEncerramentoPorAluno':

    try {

      $oEncerramento                = new EncerramentoAvaliacao(new DBLogJSON("tmp/encerramento.json"));
      $oRetorno->aTurmasProcessadas = array();
      $oRetorno->lImprimeRel        = false; // Para manter compatibilidade

      foreach ($oParam->aTurmas as $oTurmaEtapa) {

        $oTurma = new Turma($oTurmaEtapa->iTurma);

        $oDadosMatriculaAluno          = new stdClass();
        $oDadosMatriculaAluno->aAlunos = $oParam->aAlunos;
        $oDadosMatriculaAluno->iEtapa  = $oTurmaEtapa->iEtapa;

        db_inicio_transacao();

        $aMatriculas = getMatriculasAlunoTurma($oDadosMatriculaAluno, $oTurma);

        if( $oEncerramento->cancelarEncerramentoTurmaPorAluno($aMatriculas) ) {

          $oRetorno->status  = 1;
          $oRetorno->message = "Cancelamento do encerramento processado com sucesso!";
        } else {

          $oRetorno->lImprimeRel = true;
          $oRetorno->status      = 2;
          $oRetorno->message     = "Não foi possível cancelar o encerramento das avaliações.";
        }

        $oRetorno->aTurmasProcessadas[] = $oTurmaEtapa;

        db_fim_transacao(false);
      }
    } catch (Exception $oErro) {

      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = $oErro->getMessage();
    }

    $oRetorno->message = urlencode($oRetorno->message);
    break;

  case 'verificaTurmaSemAulasDadas':

    try {

      $oTurma        = new Turma($oParam->iCodigoTurma);
      $oEtapa        = EtapaRepository::getEtapaByCodigo($oParam->iEtapa);
      $oEncerramento = new EncerramentoAvaliacao();

      if ($oEncerramento->semAulasDadas($oTurma, $oEtapa, false)) {

        $oRetorno->lTurmaEncerrada = true;
        $oRetorno->status          = 1;
        $oRetorno->message         = 'Turma Encerrada.';
      } else {

        $oRetorno->lTurmaEncerrada = false;
        $oRetorno->status          = 1;
        $oRetorno->message         = 'Turma Não Encerrada.';
      }

    } catch (Exception $oErro) {

      $oRetorno->lTurmaEncerrada = false;
      $oRetorno->status          = 2;
      $oRetorno->message         = $oErro->getMessage();
    }

    break;
}

echo $oJson->encode($oRetorno);

/**
 * Retorna as matriculas dos alunos na turma informada
 * @param array $aCodigoAluno
 * @param Turma $oTurma
 * @return Matricula[]
 * @throws Exception
 */
function getMatriculasAlunoTurma( $oDadosMatriculaAluno, $oTurma ) {

  $aMatriculas        = array();
  $aAlunosPercorridos = array();

  if (count($oDadosMatriculaAluno->aAlunos) == 0) {
    throw new Exception("Nenhum aluno selecionado.");
  }

  foreach ($oDadosMatriculaAluno->aAlunos as $iCodigoAluno) {

    if( in_array( $iCodigoAluno, $aAlunosPercorridos ) ) {
      continue;
    }

    $aAlunosPercorridos[] = $iCodigoAluno;
    $oAluno               = AlunoRepository::getAlunoByCodigo($iCodigoAluno);
    $aMatriculasAluno     = MatriculaRepository::getTodasMatriculasAluno( $oAluno, false, $oTurma );

    foreach( $aMatriculasAluno as $oMatricula ) {

      if( MatriculaPosterior( $oMatricula->getCodigo() )
          && in_array($oMatricula->getSituacao(), array("TRANSFERIDO REDE", "TRANSFERIDO FORA") ) ) {
        continue;
      }

      if( $oDadosMatriculaAluno->iEtapa != $oMatricula->getEtapaDeOrigem()->getCodigo() ) {
        continue;
      }

      $aMatriculas[] = $oMatricula;
    }
  }

  return $aMatriculas;
}
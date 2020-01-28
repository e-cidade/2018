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
require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_utils.php"));
require_once (modification("libs/db_app.utils.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("dbforms/db_funcoes.php"));
require_once (modification("libs/JSON.php"));

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

define('MSG_EDU4_AVALIACAOALTERNATIVARPC', 'educacao.escola.edu4_avaliacaoalternativaRPC.');

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    /**
     * Busca os períodos de avaliação que compõe o resultado final
     * - Somente quando resultado possui forma de obtenção = SOMA
     * - Somente quando este possui avaliação alternativa configurado
     * - Procedimento base é o vinculado a turma
     */
    case 'buscarPeriodosAvaliacao':

      if ( empty($oParam->iTurma) ) {
        throw new ParameterException( _M(MSG_EDU4_AVALIACAOALTERNATIVARPC."turma_nao_informada") );
      }
      if ( empty($oParam->iEtapa) ) {
        throw new ParameterException( _M(MSG_EDU4_AVALIACAOALTERNATIVARPC."etapa_nao_informada") );
      }

      $oTurma = EducacaoSessionManager::carregarTurma($oParam->iTurma);
      $oEtapa = EducacaoSessionManager::carregarEtapa($oParam->iEtapa);

      $oProcedimentoAvaliacao = $oTurma->getProcedimentoDeAvaliacaoDaEtapa( $oEtapa );

      $aPeriodosAvaliacao = array();

      foreach ( $oProcedimentoAvaliacao->getResultados() as $oResultado ) {


        if ($oResultado->getFormaDeObtencao() != 'SO' ) {
          continue;
        }

        if (count( $oResultado->getAvaliacoesAlternativas() ) == 0 )  {
          continue;
        }
        // pega as avaliações que compõe o resultado
        $aAvaliacoes = $oResultado->getElementosComposicaoResultado();

        foreach ($aAvaliacoes as $oAvaliacaoComposicao) {

          if ( $oAvaliacaoComposicao instanceof ResultadoAvaliacao) {
            continue;
          }

          $oElemento                               = $oAvaliacaoComposicao->getElementoAvaliacao();
          $oStdPeriodo                             = new stdClass();
          $oStdPeriodo->sDescricaoPeriodoAbreviado = urlencode( $oElemento->getPeriodoAvaliacao()->getDescricaoAbreviada() );
          $oStdPeriodo->iOrdemPeriodo              = $oAvaliacaoComposicao->getOrdem();
          $oStdPeriodo->iCodigo                    = $oElemento->getCodigo();

          $aPeriodosAvaliacao[] = $oStdPeriodo;
        }
        if ( count($aPeriodosAvaliacao) == 0 ) {
          throw new Exception( _M(MSG_EDU4_AVALIACAOALTERNATIVARPC."sem_periodos_avaliacao") );
        }
        $oRetorno->aPeriodosAvaliacao = $aPeriodosAvaliacao;
      }

      break;

    case "buscarAvaliacoesAlternativas":

      if ( empty($oParam->iTurma) ) {
        throw new ParameterException( _M(MSG_EDU4_AVALIACAOALTERNATIVARPC."turma_nao_informada") );
      }
      if ( empty($oParam->iEtapa) ) {
        throw new ParameterException( _M(MSG_EDU4_AVALIACAOALTERNATIVARPC."etapa_nao_informada") );
      }

      $oTurma = EducacaoSessionManager::carregarTurma($oParam->iTurma);
      $oEtapa = EducacaoSessionManager::carregarEtapa($oParam->iEtapa);

      $oProcedimentoAvaliacao = $oTurma->getProcedimentoDeAvaliacaoDaEtapa($oEtapa);
      $aAvaliacoes            = array();
      foreach ( $oProcedimentoAvaliacao->getResultados() as $oResultado ) {

        foreach ( $oResultado->getAvaliacoesAlternativas() as $oAvaliacaoAlternativa) {

          $oDadosAvaliacao                   = new stdClass();
          $oDadosAvaliacao->iCodigo          = $oAvaliacaoAlternativa->getCodigo();
          $oDadosAvaliacao->iAlternativa     = $oAvaliacaoAlternativa->getAlternativa();
          $oDadosAvaliacao->iCodigoResultado = $oResultado->getCodigo();
          $oDadosAvaliacao->aConfiguracao    = array();
          foreach ($oAvaliacaoAlternativa->getConfiguracao() as $oConfiguracao) {

            $oConfiguracao->sPeriodo          = urlencode($oConfiguracao->sPeriodo);
            $oConfiguracao->sFormaAvaliacao   = urlencode($oConfiguracao->sFormaAvaliacao);
            $oDadosAvaliacao->aConfiguracao[] = $oConfiguracao;
          }
          $aAvaliacoes[] = $oDadosAvaliacao;
        }
      }

      $oRetorno->aAvaliacoesAlternativas = $aAvaliacoes;

      break;

    case 'verificarAvaliacaoAluno':

      if ( empty($oParam->iMatriculaAluno) ) {
        throw new Exception( _M(MSG_EDU4_AVALIACAOALTERNATIVARPC."matricula_nao_informada") );
      }

      $oMatricula    = EducacaoSessionManager::carregarMatricula($oParam->iMatriculaAluno);
      $oDiarioClasse = $oMatricula->getDiarioDeClasse();

      $iCodigoAvaliacaoAlternativa = null;

      /**
       * Verifica se aluno tem avaliação alternativa configurada
       */
      foreach ($oDiarioClasse->getDisciplinas() as $oDiarioDisciplina) {

        $oAvaliacaoAlternativa = $oDiarioDisciplina->getAvaliacaoAlternativa();
        if (is_null($oAvaliacaoAlternativa)) {
          continue;
        }
        $iCodigoAvaliacaoAlternativa = $oAvaliacaoAlternativa->getCodigo();
      }

      $aAproveitamentos = array();
      /**
       * Verifica períodos e disciplina que o aluno possui avaliações lançadas
       */
      foreach ( $oDiarioClasse->getDisciplinas() as $oDiarioDisciplina ) {

        foreach ($oDiarioDisciplina->getResultados() as $oResultado) {

          $oElemento = $oResultado->getElementoAvaliacao();
          if ($oElemento->getFormaDeObtencao() != 'SO' ) {
            continue;
          }

          if (count( $oElemento->getAvaliacoesAlternativas() ) == 0 )  {
            continue;
          }

          // pega as avaliações que compõe o resultado
          $aAvaliacoes = $oElemento->getElementosComposicaoResultado();

          foreach ($aAvaliacoes as $oAvaliacaoComposicao) {

            //busca a avaliacao do aluno no período
            $oAvaliacaoPeriodo    = $oDiarioDisciplina->getAvaliacoesPorOrdem($oAvaliacaoComposicao->getOrdem());
            $oValorAproveitamento = $oAvaliacaoPeriodo->getValorAproveitamento();
            if ( is_null($oValorAproveitamento) || $oValorAproveitamento instanceof ValorAproveitamentoNivel ) {
              continue;
            }

            if ($oValorAproveitamento->getAproveitamento() == '' ) {
              continue;
            }

            $iDiario = $oDiarioDisciplina->getCodigoDiario();
            if ( !array_key_exists($iDiario, $aAproveitamentos) ) {

              $oStdDiario                 = new stdClass();
              $oStdDiario->sDisciplina    = urlencode($oDiarioDisciplina->getDisciplina()->getNomeDisciplina());
              $oStdDiario->aPeriodos      = array();
              $aAproveitamentos[$iDiario] = $oStdDiario;
            }

            $oAvaliacaoPeriodica = $oAvaliacaoPeriodo->getElementoAvaliacao();

            $oStdAproveitamento                  = new stdClass();
            $oStdAproveitamento->nAproveitamento = $oValorAproveitamento->getAproveitamento();
            $oStdAproveitamento->iOrdemPeriodo   = $oAvaliacaoPeriodica->getOrdemSequencia();
            $oStdAproveitamento->sPeriodo        = $oAvaliacaoPeriodica->getPeriodoAvaliacao()->getDescricao();;

            $aAproveitamentos[$iDiario]->aPeriodos[] = $oStdAproveitamento;
          }
        }
      }

      $oRetorno->iAvaliacaoAlternativa    = $iCodigoAvaliacaoAlternativa;
      $oRetorno->aAproveitamentosLancados = $aAproveitamentos;

      break;

    /**
     * Salva os vínculos das disciplinas com a avaliação alternativa informada
     * Validações:
     *   - O procedimento de avaliação da disciplina deve ser do tipo SOMA
     *   - Caso já tenha sido configurada uma avaliação alternativa anteriormente, valida se a informada não é a mesma,
     *     não salvando
     */
    case 'salvar':

      if( !isset( $oParam->iMatricula ) || empty( $oParam->iMatricula ) ) {
        throw new ParameterException( _M( MSG_EDU4_AVALIACAOALTERNATIVARPC . 'matricula_nao_informada' ) );
      }

      if( !isset( $oParam->iAvaliacaoAlternativa ) || empty( $oParam->iAvaliacaoAlternativa ) ) {
        throw new ParameterException( _M( MSG_EDU4_AVALIACAOALTERNATIVARPC . 'avaliacao_nao_informada' ) );
      }

      $oTurma = EducacaoSessionManager::carregarTurma();
      $oEtapa = EducacaoSessionManager::carregarEtapa();
      foreach( $oTurma->getAlunosMatriculadosNaTurmaPorSerie( $oEtapa ) as $oMatricula ) {

        if( $oMatricula->getCodigo() == $oParam->iMatricula ) {

          $oDiario               = $oMatricula->getDiarioDeClasse();
          $oAvaliacaoAlternativa = AvaliacaoAlternativaRepository::getByCodigo( $oParam->iAvaliacaoAlternativa );

          foreach( $oDiario->getDisciplinas() as $oDiarioAvaliacaoDisciplina ) {

            $oDiarioAvaliacaoDisciplina->excluirAvaliacaoAlternativa();
            $oProcedimentoAvaliacao   = $oDiarioAvaliacaoDisciplina->getRegencia()->getProcedimentoAvaliacao();
            $lTemAvaliacaoAlternativa = false;

            /**
             * Percorre o resultado do procedimento de avaliação, verificando se o mesmo trata-se de SOMA, com avaliações
             * alternativas configuradas
             */
            foreach( $oProcedimentoAvaliacao->getResultados() as $oResultadoAvaliacao ) {

              if( count( $oResultadoAvaliacao->getAvaliacoesAlternativas() ) > 0 ) {
                $lTemAvaliacaoAlternativa = true;
              }
              break;
            }

            if( !$lTemAvaliacaoAlternativa ) {
              continue;
            }

            $oDiarioAvaliacaoDisciplina->salvarAvaliacaoAlternativa($oAvaliacaoAlternativa);
          }

          $oRetorno->sMessage = urlencode( _M( MSG_EDU4_AVALIACAOALTERNATIVARPC . 'vinculos_salvos' ) );
        }
      }

      EducacaoSessionManager::registrarTurma($oTurma);
      break;

    case "excluir":

      if( !isset( $oParam->iMatricula ) || empty( $oParam->iMatricula ) ) {
        throw new ParameterException( _M( MSG_EDU4_AVALIACAOALTERNATIVARPC . 'matricula_nao_informada' ) );
      }

      $oTurma = EducacaoSessionManager::carregarTurma();
      $oEtapa = EducacaoSessionManager::carregarEtapa();

      foreach( $oTurma->getAlunosMatriculadosNaTurmaPorSerie( $oEtapa ) as $oMatricula ) {

        if( $oMatricula->getCodigo() == $oParam->iMatricula ) {

          $oDiarioClasse = $oMatricula->getDiarioDeClasse();

          foreach ( $oDiarioClasse->getDisciplinas() as $oDiarioAvaliacaoDisciplina ) {
            $oDiarioAvaliacaoDisciplina->excluirAvaliacaoAlternativa();
          }
        }
      }

      $oRetorno->sMessage = urlencode( _M( MSG_EDU4_AVALIACAOALTERNATIVARPC . 'vinculos_excluidos' ) );
      EducacaoSessionManager::registrarTurma($oTurma);
      break;
  }

  db_fim_transacao(false);
} catch (Exception $eErro) {

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}

$oRetorno->erro = $oRetorno->iStatus == 2;
echo $oJson->encode($oRetorno);
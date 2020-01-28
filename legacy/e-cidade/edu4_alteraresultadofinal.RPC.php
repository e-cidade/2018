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
require_once(modification("libs/exceptions/BusinessException.php"));
require_once(modification("libs/exceptions/DBException.php"));
require_once(modification("libs/exceptions/FileException.php"));
require_once(modification("libs/exceptions/ParameterException.php"));


$iEscola           = db_getsession("DB_coddepto");
$oJson             = new Services_JSON();
$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->dados   = array();
$oRetorno->status  = 1;
$oRetorno->message = '';

try {

  switch($oParam->exec) {

    /**
     * Filtro: Turma, Etapa e Regencia
     * Retorno: Array com os dados da Aprovação pelo conselho (aprovconselho)
     * {aResultados:[{iCodigo:'', sAluno:'', sProfessor:'', sJustificativa:'', sDataHora:'', sFormaAprovacao:''}]}
     */
    case 'getAlunosAprovadosPeloConselhoPorRegencia':

      $oRetorno->aResultados = array();

      $oTurma    = EducacaoSessionManager::carregarTurma( $oParam->iTurma );
      $oEtapa    = EducacaoSessionManager::carregarEtapa( $oParam->iEtapa );
      $oRegencia = RegenciaRepository::getRegenciaByCodigo($oParam->iRegencia);

      db_inicio_transacao();
      foreach ($oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapa) as $oMatricula) {

        $oDiarioAvaliacaoDisciplina = $oMatricula->getDiarioDeClasse()->getDisciplinasPorRegencia($oRegencia);
        $oResultadoFinal            = $oDiarioAvaliacaoDisciplina->getResultadoFinal();
        $oAprovadoPeloConselho      = $oResultadoFinal->getFormaAprovacaoConselho();

        if (empty($oAprovadoPeloConselho)) {
          continue;
        }

        /**
         * A figura do Docente não esta correta.
         * O código do rechumano salvo na tabela aprovconselho, pode ser qualquer rechumano do sistema.
         * @todo refatorar as rotinas que incluem Aprovação pelo conselho para vincular somente docentes
         */
        $oRecHumano = null;
        if ($oAprovadoPeloConselho->getRecursoHumano() != "") {
          $oRecHumano = DocenteRepository::getDocenteByCodigoRecursosHumano($oAprovadoPeloConselho->getRecursoHumano());
        }

        $sNomeProfessor = '';
        if (!empty($oRecHumano)) {
          $sNomeProfessor = $oRecHumano->getNome();
        }

        $sFormaAprovacao         = AprovacaoConselho::getDescricaoTipoAprovacao($oAprovadoPeloConselho->getFormaAprovacao());

        $oAluno                  = new stdClass();
        $oAluno->iAprovConselho  = $oAprovadoPeloConselho->getCodigo();
        $oAluno->iMatricula      = $oMatricula->getCodigo();
        $oAluno->sAluno          = urlencode($oMatricula->getAluno()->getNome());
        $oAluno->sJustificativa  = urlencode($oAprovadoPeloConselho->getJustificativa());
        $oAluno->dtData          = $oAprovadoPeloConselho->getData()->getDate(DBDate::DATA_PTBR);
        $oAluno->sHora           = $oAprovadoPeloConselho->getHora();
        $oAluno->sProfessor      = urlencode($sNomeProfessor);
        $oAluno->sFormaAprovacao = urlencode($sFormaAprovacao);

        $oRetorno->aResultados[] = $oAluno;

      }
      db_fim_transacao();

      break;

    /**
     * Filtros: Turma, Etapa e Regencia
     * Retorno: Array de alunos que já possuem resultado final
     * (aAlunos[{iMatricula:'', sAluno:'', lReprovadoNota:'', lReprovadoFrequencia:'' }])
     */
    case 'getAlunosAlteraResultadoFinal':

      $oRetorno->aAlunos = array() ;

      $oTurma    = EducacaoSessionManager::carregarTurma( $oParam->iTurma );
      $oEtapa    = EducacaoSessionManager::carregarEtapa( $oParam->iEtapa );
      $oRegencia = RegenciaRepository::getRegenciaByCodigo($oParam->iRegencia);

      db_inicio_transacao();
      foreach ($oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapa) as $oMatricula) {

        $oDiarioAvaliacaoDisciplina   = $oMatricula->getDiarioDeClasse()->getDisciplinasPorRegencia($oRegencia);

        if ($oDiarioAvaliacaoDisciplina->getResultadoFinal()->getResultadoFinal() == "A"
            || $oDiarioAvaliacaoDisciplina->emRecuperacao() ) {
          continue;
        }

        if ($oMatricula->getSituacao() != 'MATRICULADO' || $oMatricula->isConcluida() || !$oMatricula->isAtiva()) {
        	continue;
        }

        $oAluno                       = new stdClass();
        $oAluno->iMatricula           = $oMatricula->getCodigo();
        $oAluno->sAluno               = urlencode($oMatricula->getAluno()->getNome());
        $oAluno->lReprovadoNota       = false;
        $oAluno->lReprovadoFrequencia = false;

        /**
         * Verifica se reprovou por nota
         */
        if ($oDiarioAvaliacaoDisciplina->getResultadoFinal()->getResultadoAprovacao() == 'R') {
          $oAluno->lReprovadoNota = true;
        }

        /**
         * Verifica se reprovou por frequência
         */
        if ($oDiarioAvaliacaoDisciplina->getResultadoFinal()->getResultadoFrequencia() == 'R') {
          $oAluno->lReprovadoFrequencia = true;
        }

        if ($oAluno->lReprovadoNota || $oAluno->lReprovadoFrequencia) {
          $oRetorno->aAlunos[] = $oAluno;
        }

      }
      db_fim_transacao();

      break;

    case 'excluirAlteracaoResultadoFinal':

      $oMatricula = EducacaoSessionManager::carregarMatricula($oParam->iMatricula);
      $oRegencia  = RegenciaRepository::getRegenciaByCodigo($oParam->iRegencia);
      $sNomeAluno = $oMatricula->getAluno()->getNome();

      db_inicio_transacao();

      $oDiarioAvaliacaoDisciplina = $oMatricula->getDiarioDeClasse()->getDisciplinasPorRegencia($oRegencia);
      $oAvaliacaoResultadoFinal   = $oDiarioAvaliacaoDisciplina->getResultadoFinal();
      $oAvaliacaoResultadoFinal->removerAprovacaoConselho();
      $oDiarioAvaliacaoDisciplina->salvar();

      db_fim_transacao();

      $oRetorno->message = urlencode('Removida a alteração de resultado final do aluno: '.$sNomeAluno);

      break;

    case 'salvarAlteracaoResultadoFinal':

      $oMatricula = EducacaoSessionManager::carregarMatricula( $oParam->iMatricula );
      $oRegencia  = RegenciaRepository::getRegenciaByCodigo($oParam->iRegencia);
      $sNomeAluno = $oMatricula->getAluno()->getNome();

      db_inicio_transacao();

      $oDiarioAvaliacaoDisciplina = $oMatricula->getDiarioDeClasse()->getDisciplinasPorRegencia($oRegencia);
      $oAvaliacaoResultadoFinal   = $oDiarioAvaliacaoDisciplina->getResultadoFinal();
      $oAprovacaoConselho         = new AprovacaoConselho($oAvaliacaoResultadoFinal);
      $oAprovacaoConselho->setData(new DBDate(date("Y-m-d", time())));
      $oAprovacaoConselho->setHora(date("H:i", time()));

      if (!empty($oParam->iProfessor)) {
        $oAprovacaoConselho->setRecursoHumano($oParam->iProfessor);
      }
      $oAprovacaoConselho->setFormaAprovacao($oParam->iFormaAprovacao);
      $oAprovacaoConselho->setJustificativa(db_stdClass::normalizeStringJsonEscapeString($oParam->sJustificativa));
      $oAprovacaoConselho->setUsuario(new UsuarioSistema(db_getsession('DB_id_usuario')));

      if ($oParam->iFormaAprovacao == 1) {

        $oAprovacaoConselho->setAlterarNotaFinal($oParam->iAlterarNotaFinal);

        if ( $oParam->iAlterarNotaFinal == '' || $oParam->iAlterarNotaFinal == 1 ) {
          $oAprovacaoConselho->setAvaliacaoConselho('');
        } else {
          $oAprovacaoConselho->setAvaliacaoConselho($oParam->sAvaliacaoConselho);
        }
      }

      $oAvaliacaoResultadoFinal->adicionarAprovacaoConselho($oAprovacaoConselho);
      $oDiarioAvaliacaoDisciplina->salvar();

      db_fim_transacao();
      $oRetorno->message = urlencode('Resultado final do aluno '.$sNomeAluno.' alterado com sucesso.');

      break;

    /**
     * Deve retornar:
     * - nota minima para aprovação na turma;
     * - forma de avaliação (Nota, Conceito);
     *   -- Se for conceito, deve trazer um array com os conceitos (do mínimo para cima)
     *   -- Se nota, deve devolver a mascara configurada para o ano;
     * - Variação da nota;
     */
    case 'getParametros':

      $oTurma    = EducacaoSessionManager::carregarTurma($oParam->iTurma);
      $oEtapa    = EducacaoSessionManager::carregarEtapa($oParam->iEtapa);
      $oRegencia = RegenciaRepository::getRegenciaByCodigo( $oParam->iRegencia );

      $oProcedimentoAvaliacao = $oRegencia->getProcedimentoAvaliacao();

      $oElementoResultado = null;
      foreach ( $oProcedimentoAvaliacao->getElementos() as $oElementoAvaliacao) {

        if ($oElementoAvaliacao->isResultado() &&  $oElementoAvaliacao->geraResultadoFinal() ) {
          $oElementoResultado = $oElementoAvaliacao;
        }
      }

      $oDados                   = new stdClass();
      $oDados->mAvaliacaoMinima = $oElementoResultado->getAproveitamentoMinimo();
      $oDados->nMaiorValorNota  = $oElementoResultado->getFormaDeAvaliacao()->getMaiorValor();
      $oDados->sFormaAvaliacao  = $oElementoResultado->getFormaDeAvaliacao()->getTipo();
      $oDados->mVariacao        = $oElementoResultado->getFormaDeAvaliacao()->getVariacao();
      $oDados->sMascara         = ArredondamentoNota::getMascara($oTurma->getCalendario()->getAnoExecucao());
      $oDados->aConceitos       = array();

      if ($oDados->sFormaAvaliacao == 'NIVEL') {

        $oConceitoBase = null;
        foreach ($oElementoResultado->getFormaDeAvaliacao()->getConceitos() as $oConceito) {

          if ($oConceito->sConceito == $oDados->mAvaliacaoMinima) {
            $oConceitoBase = $oConceito;
            break;
          }
        }

        foreach ($oElementoResultado->getFormaDeAvaliacao()->getConceitos() as $oConceito) {

          if ($oConceito->iOrdem >= $oConceitoBase->iOrdem) {
            $oDados->aConceitos[] = $oConceito;
          }
        }
      }
      $oRetorno->oParametros = $oDados;

      break;
  }
} catch (Exception $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
}

unset($_SESSION["DB_desativar_account"]);
echo $oJson->encode($oRetorno);
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
$oRetorno->status  = 1;
$oRetorno->message = '';

try {

  switch($oParam->exec) {

    case 'getAlunos':

      db_inicio_transacao();

      $oRetorno->aAlunos = array();

      $oTurma  = EducacaoSessionManager::carregarTurma( $oParam->iTurma );
      $oEtapa  = EducacaoSessionManager::carregarEtapa( $oParam->iEtapa );
      $aAlunos = $oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapa);

      foreach ($aAlunos as $oMatricula) {

        $oDiario              = $oMatricula->getDiarioDeClasse();
        $oDisciplinaAvaliacao = $oDiario->getDisciplinasPorRegencia(RegenciaRepository::getRegenciaByCodigo($oParam->iRegencia));

        $lTemAmparo = false;
        if ($oDisciplinaAvaliacao->getAmparo()->getCodigo() != null || $oMatricula->getSituacao() != 'MATRICULADO' ||
            !$oMatricula->isAtiva() || $oMatricula->isConcluida()
           ) {
          continue;
        }

        $oDadosMatricula                  = new stdClass();
        $oDadosMatricula->iCodigo         = $oMatricula->getCodigo();
        $oDadosMatricula->iMatricula      = $oMatricula->getMatricula();
        $oDadosMatricula->iOrdem          = $oMatricula->getNumeroOrdemAluno();
        $oDadosMatricula->sNome           = urlencode($oMatricula->getAluno()->getNome());
        $oDadosMatricula->dtDataMatricula = $oMatricula->getDataMatricula()->convertTo(DBDate::DATA_PTBR);
        $oDadosMatricula->sSituacao       = urlencode($oMatricula->getSituacao());
        $oRetorno->aAlunos[]              = $oDadosMatricula;
      }

      db_fim_transacao(false);

      break;

    /**
     * Busca os alunos amparados da turma na regencia selecionada
     * @param $oParam->iTurma    - codigo da turma
     * @param $oParam->iEtapa    - codigo da etapa
     * @param $oParam->iRegencia - codigo da regencia
     */
    case 'getAlunosAmparados':

      db_inicio_transacao();

      $oRetorno->aAlunos = array();
      $oTurma            =  EducacaoSessionManager::carregarTurma( $oParam->iTurma );
      $oEtapa            =  EducacaoSessionManager::carregarEtapa( $oParam->iEtapa );
      $aAlunos           = $oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapa);

      foreach ($aAlunos as $oMatricula) {

        $oDadosAluno          = new stdClass();
        $oDiario              = $oMatricula->getDiarioDeClasse();
        $oRegencia            = RegenciaRepository::getRegenciaByCodigo($oParam->iRegencia);
        $oDisciplinaAvaliacao = $oDiario->getDisciplinasPorRegencia($oRegencia);

        if ($oDisciplinaAvaliacao->getAmparo()->getCodigo() == null || $oMatricula->getSituacao() != 'MATRICULADO' ||
            !$oMatricula->isAtiva() || $oMatricula->isConcluida()) {
          continue;
        }

        $oAmparo = $oDisciplinaAvaliacao->getAmparo();

        if (!empty($oAmparo)) {

          $oDadosAluno->iMatricula        = $oMatricula->getCodigo();
          $oDadosAluno->sNome             = urlencode($oMatricula->getAluno()->getNome());
          $oDadosAluno->lGeraCargaHoraria = $oAmparo->isAdicionadoNaCargaHoraria();
          $oDadosAluno->aPeriodos         = array();

          foreach ($oAmparo->getPeriodosAmparados() as $oPeriodo) {

            $oDadosPeriodo               = new stdClass();
            $oDadosPeriodo->iCodigo      = $oPeriodo->getElementoAvaliacao()->getCodigo();

            if ( $oPeriodo->getElementoAvaliacao() instanceof AvaliacaoPeriodica ) {

              $oDadosPeriodo->sDescricao   = urlencode($oPeriodo->getElementoAvaliacao()->getPeriodoAvaliacao()->getDescricao());
              $oDadosPeriodo->sAbreviatura = urlencode($oPeriodo->getElementoAvaliacao()->getPeriodoAvaliacao()->getDescricaoAbreviada());
            }

            if ( $oPeriodo->getElementoAvaliacao() instanceof ResultadoAvaliacao ) {
              continue;
            }
            $oDadosAluno->aPeriodos[]    = $oDadosPeriodo;
          }

          if ($oAmparo->getCodigoJustificativa() != '') {

            $oDadosAluno->sTipo      = urlencode("J");
            $oDadosAluno->iCodigo    = $oAmparo->getJustificativa()->getCodigo();
            $oDadosAluno->sDescricao = urlencode($oAmparo->getJustificativa()->getDescricao());
          } else {

            $oDadosAluno->sTipo      = urlencode("C");
            $oDadosAluno->iCodigo    = $oAmparo->getConvencao()->getCodigo();
            $oDadosAluno->sDescricao = urlencode($oAmparo->getConvencao()->getDescricao());
          }
        }
        $oRetorno->aAlunos[] = $oDadosAluno;
      }

      db_fim_transacao(false);

      break;

    /**
     * Salva o amparo para um aluno em determinada regencia
     * @param integer $oParam->aAlunos        - array com os dados do aluno, inclusive a matricula
     * @param integer $oParam->iRegencia      - codigo da regencia selecionada
     * @param array   $oParam->aPeriodos      - colecao com os periodos selecionados que devem fazer parte do amparo
     * @param string  $oParam->sTipoAmparo    - tipo de amparo a ser salva ('J' - Justificativa, 'C' - Convencao)
     * @param integer $oParam->iJustificativa - codigo da justificativaselecionada
     * @param integer $oParam->iConvencao     - codigo da convencao selecionada
     * @param boolean $oParam->lCargaHoraria  - informa se o amparo gera carga horaria no historico
     */
    case 'salvarAmparo':

      $oRegencia = RegenciaRepository::getRegenciaByCodigo($oParam->iRegencia);

      db_inicio_transacao();
      foreach ($oParam->aAlunos as $oAluno) {

        /**
         * $oAluno->iCodigo     é o código da matricula de verdade ed60_i_codigo
         * $oAluno->iMatricula  representa a matrícula no ano.. ed60_matricula (matricula fake... não usar)
         */
        $oMatricula       = EducacaoSessionManager::carregarMatricula( $oAluno->iCodigo );
        $oDiarioAvaliacao = $oMatricula->getDiarioDeClasse()->getDisciplinasPorRegencia($oRegencia);

        $oTipoAmparo        = null;
        $aPeriodosAvaliacao = array();

        if ( !$oDiarioAvaliacao instanceof DiarioAvaliacaoDisciplina ) {

          $sMsgErro  = "Não foi possível carregar vínculo do aluno {$oMatricula->getAluno()->getNome()} ";
          $sMsgErro .= "com a disciplina {$oRegencia->getDisciplina()->getNomeDisciplina()}. ";
          throw new Exception(($sMsgErro));
        }

        foreach ($oDiarioAvaliacao->getAvaliacoes() as $oAvaliacaoAproveitamento) {

          foreach ($oParam->aPeriodos as $oPeriodoAvaliacao) {

            if ($oAvaliacaoAproveitamento->getElementoAvaliacao()->getCodigo() == $oPeriodoAvaliacao->iCodigo) {
              $aPeriodosAvaliacao[] = $oAvaliacaoAproveitamento;
            }
          }
        }

        if ($oParam->sTipoAmparo == 'J') {
          $oTipoAmparo = new Justificativa($oParam->iJustificativa);
        } else {
          $oTipoAmparo = new Convencao($oParam->iConvencao);
        }

        $oDiarioAvaliacao->salvarAmparo($aPeriodosAvaliacao, $oTipoAmparo, $oParam->lCargaHoraria);
      }

      EducacaoSessionManager::limpar();

      db_fim_transacao();
      $oRetorno->message = urlencode("Amparo salvo com sucesso.");

      break;

    /**
     * Exclui o amparo de um aluno para uma regencia
     * $oParam->iTurma     código da turma
     * $oParam->iEtapa     código da etapa
     * $oParam->iRegencia  código da regencia
     * $oParam->aAlunos    array de matriculas ( ed60_i_codigo )
     */
    case 'excluirAmparo':

      $oRegencia = RegenciaRepository::getRegenciaByCodigo($oParam->iRegencia);
      db_inicio_transacao();
      foreach ($oParam->aAlunos as $iMatricula) {

        $oMatricula       = EducacaoSessionManager::carregarMatricula( $iMatricula );
        $oDiarioAvaliacao = $oMatricula->getDiarioDeClasse()->getDisciplinasPorRegencia($oRegencia);
        $oDiarioAvaliacao->removerAmparo();
      }
      db_fim_transacao();

      EducacaoSessionManager::limpar();
      $oRetorno->message = urlencode("Amparo(s) excluído(s) com sucesso.");

      break;
  }

} catch (Exception $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
}

echo $oJson->encode($oRetorno);

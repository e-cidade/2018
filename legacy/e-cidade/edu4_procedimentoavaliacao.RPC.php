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

$sFonteMsg = "educacao.escola.edu4_procedimentoavaliacaoRPC.";

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case "atualizarDiario":

      if ( empty($oParam->iProcedimento) ) {
        throw new Exception( _M( $sFonteMsg . "informe_procedimento") );
      }

      $oProcedimento = ProcedimentoAvaliacaoRepository::getProcedimentoByCodigo($oParam->iProcedimento);
      $aRegencias    = RegenciaRepository::getRegenciaByProcedimento($oProcedimento);

      $aTurmas = array();
      foreach ($aRegencias as $oRegencia) {

        $sHash = "{$oRegencia->getTurma()->getCodigo()}#{$oRegencia->getEtapa()->getCodigo()}";
        if ( !array_key_exists($sHash, $aTurmas) ) {

          $oTurmaEtapa             = new stdClass();
          $oTurmaEtapa->oTurma     = $oRegencia->getTurma();
          $oTurmaEtapa->oEtapa     = $oRegencia->getEtapa();
          $oTurmaEtapa->aRegencias = array();
          $aTurmas[$sHash]         = $oTurmaEtapa;
        }

        $aTurmas[$sHash]->aRegencias[] = $oRegencia;
      }

      $sNomeArquivo = "tmp/{$oProcedimento->getCodigo()}_turmas_atualizadas_".date('Ymd').".json";
      $oLog         = new DBLogJSON($sNomeArquivo, false);

      $lUtilizaProporcionalidade = false;
      foreach ($aTurmas as $oTurmaEtapa) {

        $oDadosLog              = new stdClass();
        $oDadosLog->iTurma      = $oTurmaEtapa->oTurma->getCodigo();
        $oDadosLog->sTurma      = urlencode( $oTurmaEtapa->oTurma->getDescricao() );
        $oDadosLog->iEtapa      = $oTurmaEtapa->oEtapa->getCodigo();
        $oDadosLog->sEtapa      = urlencode( $oTurmaEtapa->oEtapa->getNome() );
        $oDadosLog->sCalendario = urlencode( $oTurmaEtapa->oTurma->getCalendario()->getDescricao() );

        $oDadosLog->aAlunos = array();
        foreach ( $oTurmaEtapa->oTurma->getAlunosMatriculadosNaTurmaPorSerie($oTurmaEtapa->oEtapa) as $oMatricula) {

          /**
           * Não cria o diário, só atualiza o diario existente do aluno
           */
          $oDiarioClasse = new DiarioClasse( $oMatricula, false );
          $oDiarioClasse->atualizarDiario($oTurmaEtapa->aRegencias);

          /**
           * Identifica os aluno com proporcionalidade configurada para adicionar no log
           */
          $lAlunoTemProporcionalidade = false;
          foreach ($oDiarioClasse->getDisciplinas() as $oDiarioDisciplina) {

            if ( is_array($oDiarioDisciplina->getPeriodosAvaliacaoProporcionalidade()) &&
                 count($oDiarioDisciplina->getPeriodosAvaliacaoProporcionalidade()) > 0 ) {

              $lUtilizaProporcionalidade  = true;
              $lAlunoTemProporcionalidade = true;
              break;
            }
          }

          if ($lAlunoTemProporcionalidade) {
            $oDadosLog->aAlunos[] = urlencode( $oMatricula->getAluno()->getCodigoAluno() . ' - ' . $oMatricula->getAluno()->getNome() );
          }
        }
        $oLog->log( $oDadosLog, DBLog::LOG_INFO );
      }

      $oRetorno->sMessage                  = urlencode("Diários atualizados com sucesso.");
      $oRetorno->lUtilizaProporcionalidade = $lUtilizaProporcionalidade;
      $oRetorno->sNomeArquivoLog           = urlencode($sNomeArquivo);

      if ( count($aRegencias) == 0 ) {
        $oRetorno->sMessage = urlencode("Procedimento não possui turmas vínculadas.");
      }
      break;

    case 'alterarSituacaoProcedimento':

      if ( empty($oParam->iProcedimento) ) {
        throw new Exception( _M( $sFonteMsg . "informe_procedimento") );
      }

      if ( $oParam->lDesativar === '' ) {
        throw new Exception( _M( $sFonteMsg . "informe_situacao") );
      }

      $oDaoProcedimento = new cl_procedimento;
      $oDaoProcedimento->ed40_i_codigo   = $oParam->iProcedimento;
      $oDaoProcedimento->ed40_desativado = $oParam->lDesativar ? 'true' : 'false';
      $oDaoProcedimento->alterar($oParam->iProcedimento);

      if ($oDaoProcedimento->erro_status == 0 ) {
        throw new Exception( _M( $sFonteMsg . "erro_salvar") );
      }

      $oRetorno->sMessage = urlencode( _M( $sFonteMsg . "procedimento_atualizado") );

      break;

    case 'importarProcedimento' :

      if ( empty($oParam->iProcedimento) ) {
        throw new Exception( _M( $sFonteMsg . "informe_procedimento") );
      }

      $oEscola       = EscolaRepository::getEscolaByCodigo(db_getsession('DB_coddepto'));
      $oProcedimento = new ProcedimentoAvaliacao($oParam->iProcedimento);

      $oRetorno->iNovoProcedimento = ProcedimentoAvaliacao::importar($oEscola, $oProcedimento, db_getsession('DB_anousu'));

      $oRetorno->sMessage = urlencode( _M( $sFonteMsg . "procedimento_importado") );
      break;

  }

  db_fim_transacao(false);


} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}
$oRetorno->erro = $oRetorno->iStatus == 2;
echo $oJson->encode($oRetorno);
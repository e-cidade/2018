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


db_app::import("educacao.*");
db_app::import("educacao.avaliacao.*");
db_app::import("exceptions.*");

$iEscola           = db_getsession("DB_coddepto");
$oJson             = new Services_JSON();
$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

try {

  switch($oParam->exec) {

    case 'getObservacaoAluno':

      $oMatricula = EducacaoSessionManager::carregarMatricula($oParam->iMatricula);
      $oRegencia  = RegenciaRepository::getRegenciaByCodigo($oParam->iRegencia);

      db_inicio_transacao();
      $oDiarioClasse = $oMatricula->getDiarioDeClasse();
      db_fim_transacao();

      $sObservacao = "";
      switch ($oParam->sTipoAvaliacao) {

        case 'A':

          $oAvaliacaoPeriodica = AvaliacaoPeriodicaRepository::getAvaliacaoPeriodicaByCodigo($oParam->iPeriodo);
          $oAvaliacao          = $oDiarioClasse->getDisciplinasPorRegenciaPeriodo($oRegencia, $oAvaliacaoPeriodica);
          if ( $oAvaliacao instanceof AvaliacaoAproveitamento) {
            $sObservacao = $oAvaliacao->getObservacao();
          }

          break;

        case 'RF':

          $oDiarioDisciplina = $oDiarioClasse->getDisciplinasPorRegencia($oRegencia);
          $oResultadoFinal   = $oDiarioDisciplina->getResultadoFinal();
          if ( $oResultadoFinal instanceof AvaliacaoResultadoFinal ) {
            $sObservacao = $oResultadoFinal->getObservacao();
          }
          break;

      }
      $oRetorno->sObservacao = urlencode($sObservacao);
      break;

    case 'salvarObservacao':

      $oMatricula  = EducacaoSessionManager::carregarMatricula($oParam->iMatricula);
      $sObservacao = db_stdClass::normalizeStringJsonEscapeString($oParam->sObservacao);

      db_inicio_transacao();

      foreach ($oParam->aRegencias as $iCodigoRegencia) {

        $oRegencia     = RegenciaRepository::getRegenciaByCodigo($iCodigoRegencia);
        $oDiarioClasse = $oMatricula->getDiarioDeClasse();

        /**
         * Os tipos de avaliação que podem
         */
        switch ($oParam->sTipoAvaliacao) {

          case 'A':

            $oAvaliacaoPeriodica = AvaliacaoPeriodicaRepository::getAvaliacaoPeriodicaByCodigo($oParam->iPeriodo);
            $oAvaliacao          = $oDiarioClasse->getDisciplinasPorRegenciaPeriodo($oRegencia, $oAvaliacaoPeriodica);
            $oAvaliacao->setObservacao($sObservacao);
            $oDiarioClasse->salvar();
            break;

          case 'RF':

            $oDiarioDisciplina = $oDiarioClasse->getDisciplinasPorRegencia($oRegencia);
            $oResultadoFinal   = $oDiarioDisciplina->getResultadoFinal();
            $oResultadoFinal->setObservacao($sObservacao);
            $oResultadoFinal->salvar();
            break;
        }
      }

      db_fim_transacao();

      $oRetorno->message = "Observação salva.";

      break;
  }
} catch (Exception $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = $oErro->getMessage();
}

$oRetorno->message = urlencode($oRetorno->message);
echo $oJson->encode($oRetorno);
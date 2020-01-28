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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("libs/JSON.php"));

require_once(modification("dbforms/db_funcoes.php"));

require_once(modification("std/db_stdClass.php"));
require_once(modification("std/DBNumber.php"));

db_app::import("exceptions.*");
db_app::import("configuracao.*");
db_app::import("CgmFactory");
db_app::import("contabilidade.lancamento.*");
db_app::import("contabilidade.*");
db_app::import("empenho.EmpenhoFinanceiro");
db_app::import("empenho.RestosAPagar");
db_app::import("contabilidade.contacorrente.*");
$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new db_stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

$iInstituicao = db_getsession("DB_instit");
$iAnoSessao   = db_getsession("DB_anousu");

try {

  switch ($oParam->exec) {

    case 'getDadosRestosAPagar' :

      $oRetorno->lBloquearTela = false;

      $lProcessados = !empty($oParam->lProcessados) ? $oParam->lProcessados : null;
      $iTipo        = !empty($oParam->iTipo) ? (int) $oParam->iTipo : null;

			if ( EscrituracaoRestosAPagar::existeLancamentoPeriodo($iAnoSessao,
																														 $iInstituicao,
																														 $lProcessados,
																														 $iTipo) ) {
				$oRetorno->lBloquearTela = true;
			}

			$nValorExercicioAtual = EscrituracaoRestosAPagar::getValorLancamento($iAnoSessao,
						                                                               $iInstituicao,
						                                                               $iTipo);

			$nValorExerciciosAnteriores = EscrituracaoRestosAPagar::getValorLancamentoExerciciosAnteriores($iAnoSessao,
						                                                                                         $iInstituicao,
						                                                                                         $iTipo);
			if ($nValorExercicioAtual == 0) {
				$nValorExercicioAtual = RestosAPagar::getValor($iTipo, $iAnoSessao, $iInstituicao, false);
			}
			if ($nValorExerciciosAnteriores == 0) {
				$nValorExerciciosAnteriores = RestosAPagar::getValor($iTipo, $iAnoSessao, $iInstituicao, true);
			}

			$oRetorno->nValorExercicioAtual       = $nValorExercicioAtual;
			$oRetorno->nValorExerciciosAnteriores = $nValorExerciciosAnteriores;

			break;

		case 'processar'    :
		case 'desprocessar' :

			db_inicio_transacao();

			$sObservacao         = db_stdClass::normalizeStringJsonEscapeString($oParam->sObservacao);
			$iCodigoEscrituracao = null;
			$iCodigoDocumento    = null;
			$oEscrituracao       = new EscrituracaoRestosAPagar($iAnoSessao, $iInstituicao);
			$oEscrituracao->setTipoRestoAPagar($oParam->iTipo);

			if ($oParam->exec == 'processar') {

				$iCodigoEscrituracao = $oEscrituracao->escriturar();
				$lEstorno = false;
			} else {

				$iCodigoEscrituracao = $oEscrituracao->cancelarEscrituracao();
				$lEstorno = true;
			}

      $iCodigoDocumentoExercicioAtual    = RestosAPagar::getDocumento($oParam->iTipo, false, $lEstorno);
      $iCodigoDocumentoExercicioAnterior = RestosAPagar::getDocumento($oParam->iTipo, true,  $lEstorno);

      $nValorExercicioAtual    = RestosAPagar::getValor($oParam->iTipo, $iAnoSessao, $iInstituicao, false);
      $nValorExercicioAnterior = RestosAPagar::getValor($oParam->iTipo, $iAnoSessao, $iInstituicao, true);

      if ($nValorExercicioAtual > 0) {

        $oLancamentoAuxiliar = new LancamentoAuxiliarInscricaoRestosAPagar();
        $oLancamentoAuxiliar->setValorTotal($nValorExercicioAtual);
        $oLancamentoAuxiliar->setObservacaoHistorico($sObservacao);
        $oLancamentoAuxiliar->setInscricaoRestosAPagar($iCodigoEscrituracao);
        $oEscrituracao->processarLancamentosContabeis($oLancamentoAuxiliar, $iCodigoDocumentoExercicioAtual);
      }

      if ($nValorExercicioAnterior > 0) {

        $oLancamentoAuxiliar = new LancamentoAuxiliarInscricaoRestosAPagar();
        $oLancamentoAuxiliar->setValorTotal($nValorExercicioAnterior);
        $oLancamentoAuxiliar->setObservacaoHistorico($sObservacao);
        $oLancamentoAuxiliar->setInscricaoRestosAPagar($iCodigoEscrituracao);
        $oEscrituracao->processarLancamentosContabeis($oLancamentoAuxiliar, $iCodigoDocumentoExercicioAnterior);
      }

      db_fim_transacao(false);

      break;

  }

} catch (Exception $oErro) {

  $oRetorno->status  = 2;
  $oRetorno->message = $oErro->getMessage();

  db_fim_transacao(true);
}

$oRetorno->message = urlEncode($oRetorno->message);

echo $oJson->encode($oRetorno);

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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_libcontabilidade.php");
require_once("libs/JSON.php");

require_once("dbforms/db_funcoes.php");

require_once("std/db_stdClass.php");
require_once("std/DBNumber.php");

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

			if ( EscrituracaoRestosAPagarNaoProcessados::existeLancamentoPeriodo($iAnoSessao, $iInstituicao, $oParam->lProcessados) ) {
				$oRetorno->lBloquearTela = true;
			}

			$nValor  = EscrituracaoRestosAPagarNaoProcessados::getValorLancamento($iAnoSessao,
						                                                                $iInstituicao,
						                                                                $oParam->lProcessados);
			if ($nValor == 0) {
				$nValor = RestosAPagar::getValorNaoProcessadoAno($iAnoSessao, $iInstituicao);
			}

			$oRetorno->nValor = $nValor;

		break;

	  case 'processar'    :
		case 'desprocessar' :

			db_inicio_transacao();

	  	$oEscrituracao       = new EscrituracaoRestosAPagarNaoProcessados($iAnoSessao, $iInstituicao);

	  	$iCodigoEscrituracao = null;
	  	$iCodigoDocumento    = null;
			$sObservacao         = db_stdClass::normalizeStringJson($oParam->sObservacao);

	  	if ($oParam->exec == 'processar') {

		  	// Documento 2005: INSCRIÇÃO DE RESTOS A PAGAR NÃO PROCESSADOS
	  		$iCodigoDocumento    = 2005;
		  	$iCodigoEscrituracao = $oEscrituracao->escriturar();

	  	} else {

				// Documento 2006:	ESTORNO DE INSCR. DE RP NÃO PROCESSADOS
	  		$iCodigoDocumento    = 2006;
				$iCodigoEscrituracao = $oEscrituracao->cancelarEscrituracao();
	  	}

	  	$oLancamentoAuxiliar = new LancamentoAuxiliarInscricaoRestosAPagarNaoProcessado();
	  	$oLancamentoAuxiliar->setValorTotal($oParam->nValor);
	  	$oLancamentoAuxiliar->setObservacaoHistorico($sObservacao);
	  	$oLancamentoAuxiliar->setInscricaoRestosAPagarNaoProcessados($iCodigoEscrituracao);

	  	$oEscrituracao->processarLancamentosContabeis($oLancamentoAuxiliar, $iCodigoDocumento);

	  	db_fim_transacao(false);

		break;

  }

} catch (BusinessException $oErro){

	$oRetorno->status  = 2;
	$oRetorno->message = $oErro->getMessage();

	db_fim_transacao(true);

} catch (ParameterException $oErro) {

	$oRetorno->status  = 2;
	$oRetorno->message = $oErro->getMessage();

} catch (DBException $oErro) {

	$oRetorno->status  = 2;
	$oRetorno->message = $oErro->getMessage();

	db_fim_transacao(true);

} catch (Exception $oErro) {

	$oRetorno->status  = 2;
	$oRetorno->message = $oErro->getMessage();

	db_fim_transacao(true);
}

$oRetorno->message = urlEncode($oRetorno->message);

echo $oJson->encode($oRetorno);
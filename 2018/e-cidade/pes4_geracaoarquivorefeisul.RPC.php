<?php
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_" . "conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("libs/JSON.php");
require_once('phar://ext/php/PHPExcel.phar/PHPExcel.php');

$oJson                  = new services_json();
$oParam                 = isset($_POST["json"]) ? $oJson->decode(str_replace("\\","",$_POST["json"])) : new stdClass();
$oRetorno               = new stdClass();
$oRetorno->erro         = false;
$oRetorno->iStatus      = true;
$oRetorno->sMessage     = '';
$sPedido                = isset($oParam->exec) ? $oParam->exec : '';

try {

	switch ($oParam->exec) {

		case 'gerarArquivo':

			$oArquivoRefeisul       = new EmissaoArquivoRefeisul(DBPessoal::getCompetenciaFolha(), InstituicaoRepository::getInstituicaoSessao());
			$oRetorno->sNomeArquivo = $oArquivoRefeisul->gerar();
			break;
	}
} catch (Exception $eErro) {

	db_fim_transacao(true);
	$oRetorno->erro     = true;
	$oRetorno->iStatus  = false;
	$oRetorno->sMessage = urlencode($eErro->getMessage());
}
echo $oJson->encode($oRetorno);
<?php

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_GET["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

try {

  db_inicio_transacao();

  if (empty($oParam->sTipo)) {
    $oParam->sTipo = DBReleaseNote::buscarTipo(db_getsession('DB_itemmenu_acessado'));
  }

  switch ($oParam->exec) {

    case "getContent":

      $sNomeArquivo = db_getsession('DB_itemmenu_acessado');
      $sArquivoAtual = $sNomeArquivo;
      
      if ( $sNomeArquivo == "0" ) {

        $sNomeArquivo = "nota_geral";
        $sArquivoAtual = "nota_geral_01";
      }

      if ( !empty($oParam->sNomeArquivo) ) {
        $sArquivoAtual = $oParam->sNomeArquivo;
      }

      $oParam->sNomeArquivo = $sNomeArquivo;
      $oParam->sArquivoAtual = $sArquivoAtual;
      $oParam->idUsuario = db_getsession("DB_id_usuario");

      $oDBReleaseNote = DBReleaseNote::buildFromParams($oParam);
      $oRetorno = $oDBReleaseNote->buildData();

    break;

    case "marcarComoLido":

      $oParam->sNomeArquivo = db_getsession('DB_itemmenu_acessado'); 
      $oParam->idUsuario = db_getsession("DB_id_usuario"); 

      $oDBReleaseNote = DBReleaseNote::buildFromParams($oParam);
      $oDBReleaseNote->marcarComoLido($oParam->aArquivosLidos);

    break;
  }

  db_fim_transacao(false);

} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}

echo $oJson->encode($oRetorno);

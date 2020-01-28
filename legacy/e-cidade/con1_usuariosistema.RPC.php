<?php

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case "getDadosUsuario":

      $oUsuario = new UsuarioSistema(db_getsession("DB_id_usuario"));
      $oRetorno->sNomeUsuario = urlencode($oUsuario->getNome());

    break;

    case "coletaInfoCliente":

      require_once("std/DBCache.php");

      $sStringMensagem = "COLETADADOS\n";
      $sStringMensagem .= $_POST["json"];

      if ( !DBCache::check('coleta_dado/' . db_getsession('DB_id_usuario')) ) {
        db_logsmanual($sStringMensagem);
        DBCache::write('coleta_dado/' . db_getsession('DB_id_usuario'), $sStringMensagem);
      }

    break;

    case 'logHelp':

      $sStringMensagem = "HELP\n";
      $sStringMensagem .= "Acesso ao help do sistema";
      db_logsmanual($sStringMensagem);

    break;
  }

  db_fim_transacao(false);


} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}
echo $oJson->encode($oRetorno);
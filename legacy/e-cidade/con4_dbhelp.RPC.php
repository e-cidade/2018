<?php

use \ECidade\V3\Extension\Registry;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));

/**
 * Alterado o application name para não impactar na validação de bloqueio de rotinas
 */
db_query("SET application_name=ecidade_DBHelp_0;");

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_GET["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

try {

    switch ($oParam->exec) {

    case "getHelpData":

      $iIdItemMenu = db_getsession('DB_itemmenu_acessado');
      session_write_close();

      $oDBHelp = DBHelpSistema::create(Registry::get('app.config'), $iIdItemMenu);
      $oDBHelp->load();

      $oRetorno->oHelp = $oDBHelp->getData();
      $oRetorno->sVersao = $oDBHelp->getVersao();

    break;

    case "getHelpFields":

      $iIdItemMenu = db_getsession('DB_itemmenu_acessado');
      session_write_close();

      $oDBHelpInline = DBHelpInline::create(Registry::get('app.config'), $iIdItemMenu);
      $oDBHelpInline->load();

      $oRetorno->aFields = $oDBHelpInline->getData();

    break;

  }

} catch (Exception $eErro){

  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}
echo $oJson->encode($oRetorno);

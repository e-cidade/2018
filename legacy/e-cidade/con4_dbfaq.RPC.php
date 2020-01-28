<?php

use \ECidade\V3\Extension\Registry;

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

    switch ($oParam->exec) {

    case "getFaqData":

      $iIdItemMenu = db_getsession('DB_itemmenu_acessado');

      $oDBFaq = new DBFaq(Registry::get('app.config'), $iIdItemMenu);
      
      $oRetorno->aFaqs = $oDBFaq->getData();
      $oRetorno->sVersao = $oDBFaq->getVersao();

    break;

  }

} catch (Exception $eErro){

  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}
echo $oJson->encode($oRetorno);
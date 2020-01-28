<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_utils.php");
require_once modification("dbforms/db_funcoes.php");

$oParametros       = JSON::create()->parse( str_replace("\\","",$_POST["json"]) );
$oRetorno          = new stdClass;
$oRetorno->message = '';
$oRetorno->erro    = false;

try {

  db_inicio_transacao();

  switch ($oParametros->exec) {

    case 'getTutoriaisDisponiveis':

      $idMenu = db_getsession('DB_itemmenu_acessado');
      $idModulo = db_getsession('DB_modulo');
      $tutorialRepository = new TutorialRepository();
      $oRetorno->tutoriais = $tutorialRepository->getTutoriaisDisponiveis($idMenu, $idModulo);

      break;

    case "getTutorial":
      $oRetorno->tutorial = TutorialRepository::getById($oParametros->tutorialId)->toObject();
      break;

    case 'iniciarTutorial': 

      $tutorialRepository = new TutorialRepository();
      $tutorialRepository->iniciarTutorial(TutorialRepository::getById($oParametros->tutorialId));

      break;

    case 'finalizarTutorial':

      $tutorialRepository = new TutorialRepository();
      $tutorialRepository->finalizarTutorial(TutorialRepository::getById($oParametros->tutorialId));

      break;

    case 'setPassoAtual':

      $tutorial = TutorialRepository::restoreFromSession();
      $tutorial->getEtapaAtual()->setPassoAtual(TutorialEtapaPassoRepository::getById($oParametros->passoAtual));
      TutorialRepository::storeOnSession($tutorial);

      break;

    case 'setEtapaAtual':
 
      $tutorial = TutorialRepository::restoreFromSession();
      $tutorial->setEtapaAtual(TutorialEtapaRepository::getById($oParametros->etapaAtual));
      TutorialRepository::storeOnSession($tutorial);

      break;

    case 'getTutorialCorrente':

      $tutorial = TutorialRepository::restoreFromSession(); 

      if ($tutorial){
        $oRetorno->tutorial = $tutorial->toObject();
      }
      
      break;
    
    case "setModulo":

      db_putsession('DB_modulo', $oParametros->moduloId);

      break;
    default:
      throw new ParameterException('Método inválido.');

  }
  db_fim_transacao(false);

} catch (Exception $oException) {

  db_fim_transacao(true);

  $oRetorno = new \stdClass();
  $oRetorno->message = urlencode($oException->getMessage());
  $oRetorno->erro = true;
}

header('Content-type: application/json');
echo JSON::create()->stringify($oRetorno);
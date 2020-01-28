<?php

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->erro = false;
$oRetorno->sMessage     = '';

try {

  switch ($oParam->exec) {

    case "getTarefas":

      $aTarefas = array();

      $oAgenda = new Agenda();
      $aJobs = $oAgenda->importarTarefas();

      foreach($aJobs as $oJob) {

        $sCaminhoArquivoLock = TaskManager::PATH_LOCKS . $oJob->getNome() . ".lock";
        $iTempoExecucao = file_exists($sCaminhoArquivoLock) ? time() - filemtime($sCaminhoArquivoLock) : 0;
        $sStatus = $iTempoExecucao > 0 ? 'Executando há ' . round($iTempoExecucao/60, 2) . ' minuto(s)' : 'Fila';

        $sTextoErro = '';

        $aArquivosLog = \scandir(Task::CAMINHO_LOG);
        $lLog = false;

        foreach ($aArquivosLog as $sArquivoLog) {
            $aNome = explode("-", $sArquivoLog);

            $sNomeClass = array_shift($aNome);
            $sNomeJob = array_shift($aNome);

            if ($oJob->getNomeClasse() === $sNomeClass && $oJob->getNome() === $sNomeJob) {

                $lLog = true;
                break;
            }
        }

        if ( file_exists($sCaminhoArquivoLock) ) {
          $lock = file($sCaminhoArquivoLock);
          $sTextoErro = !empty($lock[2]) ? str_replace('UltimoErro=', '', $lock[2]) : '';
        }

        $aTarefas[] = array(
          'sNome' => $oJob->getNome(),
          'sNomeClasse' => $oJob->getNomeClasse(),
          'sDescricao' => $oJob->getDescricao(),
          'sDataCriacao' =>  date('d/m/Y H:i', $oJob->getMomentoCricao()),
          'sTipoPeriodicidade' => urlencode($oAgenda->getDescricaoPeriodicidade($oJob->getTipoPeriodicidade())),
          'aPeriodicidades' => $oJob->getPeriodicidades(),
          'sStatus' => urlencode($sStatus),
          'sTextoErro' => $sTextoErro,
          'lLock' => file_exists($sCaminhoArquivoLock),
          'lLog' => $lLog
        );
      }

      $oRetorno->aTarefas = $aTarefas;

    break;

    case "apagarLock":

      $sCaminhoArquivoLock = TaskManager::PATH_LOCKS . $oParam->sNome . ".lock";

      if (!file_exists($sCaminhoArquivoLock)) {
        throw new Exception("Arquivo de lock não existe mais.");
      }

      if (!unlink($sCaminhoArquivoLock) ) {
        throw new Exception('Erro ao apagar o arquivo de lock, tente novamente.');
      }

      $oRetorno->sMessage = 'Arquivo de lock apagado com sucesso.';

    break;

    case "getLogs":

        $className = $oParam->className;
        $aArquivosLog = \scandir(Task::CAMINHO_LOG);
        $aLogs = array();

        foreach ($aArquivosLog as $sArquivoLog) {
            $aNome = explode("-", $sArquivoLog);

            if ($className === $aNome[0]) {
                $oSimpleXml = simplexml_load_file(Task::CAMINHO_LOG . $sArquivoLog);

                foreach ($oSimpleXml->Log as $oLog) {
                    $aArquivo = explode("-", $sArquivoLog);
                    $aLogs[$aArquivo[2] . "/" . $sArquivoLog][] = $oLog->attributes()["TextoLog"]->__toString();
                }
            }
        }

        ksort($aLogs);
        $oRetorno->aLogs = $aLogs;
        break;
  }


} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  $oRetorno->erro = true;
}

echo $oJson->encode($oRetorno);

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

db_app::import("configuracao.DBLog");
/**
 * Classe para Gerenciamento de Dados da Sessao
 */
class _TaskSession {


}

/**
 * Classe de definições para cada tarefa do Gerenciador de Tarefas.
 * @author Rafael Serpa Nery <rafael.nery@dbseller.com.br>
 * @revision $Author: dbjeferson.belmiro $
 * @version $Revision: 1.13 $
 */
abstract class Task {

  /**
   * Constante com o caminho do diretório de logs do gerenciador de tarefas
   */
  const CAMINHO_LOG = "jobs/configuracoes/taskManager/logs/";

  /**
   * Log que ser autilizado
   * @var DBLog
   */
  protected $oLog;

  /**
   * PID da Tarefa
   * @var int
   */
  protected $iPIDTask;

  /**
   * PID do Processo Pai
   * @var int
   */
  protected $iParentPID;

  /**
   * Caminho do Arquivo de Lock da tarefa
   * @var string
   */
  protected $sCaminhoArquivoLock;

  /**
   * Instancia da Tarefa a Ser executada
   * @var Job
   */
  protected $oTarefa;

  /**
   * Construtor da Classe
   */
  public function __construct(){
    register_shutdown_function(array($this, 'shutdown'));
  }

  /**
   * Define a tarefa qual a execucao pertence
   * @param Job $oTarefa
   */
  public function setTarefa( Job $oTarefa ){
    $this->oTarefa = $oTarefa;
  }

  /**
   * Retorna a Instancia da Tarefa
   * @return Job
   */
  public function getTarefa(){
    return $this->oTarefa;
  }

  /**
   * Inicia a execução da tarefa
   */
  public function iniciar(){

    $this->iPIDTask   = posix_getpid();
    $this->iParentPID = posix_getppid();
    $this->log("Inicio Processamento.");
    $this->gerarArquivoLock();
  }

  /**
   * Conclui a Execução da Tarefa
   */
  public function terminar(){

    $this->log("Fim Processamento.");

    /**
     * Cria/altera arquivo com os dados da execucao da tarefa
     */
    $sCaminhoArquivo    = TaskManager::PATH_TAREFAS_EXECUTADAS.$this->oTarefa->getNome().".task.xml";
    $oXML               = new DOMDocument('1.0', 'ISO-8859-1');

    if ( !file_exists($sCaminhoArquivo) ){

      $oExecucoes = $oXML->createElement('Execucoes'); //ROOT
      $oExecucoes = $oXML->appendChild($oExecucoes);
    } else {

      $oXML->load($sCaminhoArquivo);
      $oExecucoes = $oXML->getElementsByTagName("Execucoes")->item(0);
    }

    $oExecucao = $oXML->createElement('Execucao');
    $oExecucao->setAttribute( "Execucao", $this->oTarefa->getPeriodicidadeExecucao() );
    $oExecucao->setAttribute( "MomentoExecucao", time() );
    $oExecucoes->appendChild($oExecucao);
    $oXML->formatOutput = true;
    $oXML->save($sCaminhoArquivo);

    if ($this->oTarefa->getTipoPeriodicidade() == Agenda::PERIODICIDADE_UNICA) {
        $this->oTarefa->excluir();
    }

    $this->removerArquivoLock();
    exit (0);
  }

  /**
   * Aborta a Execução da Tarefa
   */
  public function abortar(){


  }

  /**
   * Retorna o PID da Tarefa
   * @return integer
   */
  public function getPID() {
    return $this->iPIDTask;
  }

  /**
   * Escreve Log
   * @param String  $sMensagem
   * @param Integer $iTipo     Tipo de Log de Mensagem
   */
  public function log($sMensagem, $iTipo = DBLog::LOG_INFO) {

    db_app::import('configuracao.DBLog');

    if ( !isset($this->oLog) ) {

        $path = sprintf(
            '%s%s-%s-%s-%s',
            self::CAMINHO_LOG,
            get_class($this),
            $this->oTarefa->getNome(),
            $this->iPIDTask,
            date("Ymd_His")
        );
        $this->oLog = new DBLog( "XML", $path);
    }

    $this->oLog->escreverLog($sMensagem, $iTipo);
  }

  /**
   * Cria arquvo de Lock para que a tarefa não seja executada mais de uma vez no mesmo minuto
   */
  public function gerarArquivoLock() {

    $this->sCaminhoArquivoLock = TaskManager::PATH_LOCKS . $this->oTarefa->getNome() . ".lock";
    $pArquivo = file_put_contents($this->sCaminhoArquivoLock, "TaskManager={$this->iParentPID}\nServico={$this->iPIDTask}\n");
  }

  /**
   * Remove arquivo de Lock.
   */
  public function removerArquivoLock() {
    unlink($this->sCaminhoArquivoLock);
  }

  /**
   * Valida se a tarefa esta liberada para execucao
   * @param Job $oJob
   */
  public function isLiberadaExecucao() {

    $this->sCaminhoArquivoLock = TaskManager::PATH_LOCKS . $this->oTarefa->getNome() . ".lock";

    if ( file_exists($this->sCaminhoArquivoLock) ) {
      return false;
    }
    return true;
  }

  /**
   * Define os Dados da Sessao
   * @param _TaskSession $oSession
   */
  public function setSession( _TaskSession $oSession ) {

    foreach  ($oSession as $sAtributo => $sValor) {
      db_putsession($sAtributo, $sValor);
    }
  }

  public function shutdown() {

    if (!file_exists($this->sCaminhoArquivoLock)) {
      return;
    }

    $error = error_get_last();
    $e_fatal = (E_ERROR | E_USER_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_RECOVERABLE_ERROR);

    if (empty($error) || !($error['type'] & $e_fatal)) {
      return;
    }

    $message = $error['type'] . ' - ' . $error['message'] . ' in ' . $error['file'] . ' on line ' . $error['line'];
    file_put_contents($this->sCaminhoArquivoLock, "UltimoErro=" . str_replace("\n", "<CRLF>", $message) . "\n", FILE_APPEND);
  }

}

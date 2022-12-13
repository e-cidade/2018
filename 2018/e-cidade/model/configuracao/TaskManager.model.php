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

require_once(modification("std/Thread.php"));

/**
 * Classe que define os comportamentos do Gerenciador de Tarefas
 * @author Rafael Serpa Nery <rafael.nery@dbseller.com.br>
 * @revision $Author: dbjeferson.belmiro $
 * @version $Revision: 1.9 $
 */
class TaskManager {

  /**
   * Instancia da classe
   * @var TaskManager
   */
  private static $oInstance;

  /**
   * PID do Processo Gerado
   * @var integer
   */
  private $iPIDProcesso;

  /**
   * Caminho do arquivo de lock criado pelo sistema
   * @var string
   */
  private $sArquivoLock;

  /**
   * Usuario do Sistema que Criou o Processo
   * @var {UsuarioSistema}
   */
  private $oUsuarioSistema;

  /**
   * Instante de inicio do Servico
   * @var integer(TimeStamp)
   */
  private $iMomentoInicio;

  /**
   * Instancia da Agenda de Tarefas
   * @var Agenda
   */
  private $oAgenda;

  private $dDataInicio;

  private $sHoraInicio;

  /**
   *
   * @var array
   */
  private static $aTarefasExecutando = array();

  /**
   * Diretorio da Fila das Tarefas
   */
  const PATH_FILA_TAREFAS        = "jobs/configuracoes/taskManager/fila/";

  /**
   * Pasta Padrao para os Locks gerados pelo gerenciador de tarefas
   * @var string
   */
  const PATH_LOCKS               = "jobs/configuracoes/taskManager/lock/";

  /**
   * Diretorio padrao aonde ficam as tarefas executas
   * @var string
   */
  const PATH_TAREFAS_EXECUTADAS  = "jobs/configuracoes/taskManager/executadas/";

  /**
   * Construtor da Classe
   */
  protected function __construct() {
    $this->sArquivoLock = '.GerenciadorTarefas.lock.xml';
  }

  /**
   * Retorna o Usuario que iniciou o serviço
   * @return UsuarioSistema
   */
  public function getUsuarioSistema() {


  }

  /**
   * Define o Usuario do Sistema que Iniciou o Serviço
   * @param UsuarioSistema $oUsuarioSistema
   */
  public function setUsuarioSistema( UsuarioSistema  $oUsuarioSistema ) {

  }

  /**
   * Retorana a data de inicio do servico
   * @return date
   */
  public function getDataInicio() {
    return $this->dDataInicio;
  }

  /**
   * Define a data de Inicio do serviço
   * @param unknown_type $dDataInicio
   */
  public function setDataInicio( $dDataInicio ) {
    $this->dDataInicio  = $dDataInicio;
  }

  /**
   * Retorna a hora de Inicio do Serviço
   * @return string
   */
  public function getHoraInicio() {
    return $this->sHoraInicio;
  }

  /**
   * Define a hora de Inicio do Serviço
   * @param string $sHoraInicio
   */
  public function setHoraInicio( $sHoraInicio ) {
    $this->sHoraInicio  = $sHoraInicio;

  }

  /**
   * Rertona Caminho do arquivo de Lock
   * @return string
   */
  public function getCaminhoArquivoLock() {
    return $this->sArquivoLock;
  }

  /**
   * Retorna PID Processo
   */
  public function getPIDProcesso() {
    return $this->iPIDProcesso;
  }

  /**
   * Inicia o serviço do Gerenciador de Serviços
   * @param $lTeste Executa Teste Para validar se o Serviço pode ser Inicializado
   */
  public function iniciarServico( $lTeste = false ) {

    $lCriaArquivoLock = true;
    $lArquivoExiste   = file_exists($this->getCaminhoArquivoLock());

    if ($lArquivoExiste) {

      $aArquivoLock = (array)simplexml_load_file($this->getCaminhoArquivoLock());
      $iPid         = posix_getsid( $aArquivoLock['iPIDProcesso'] );

      if ( $iPid ) {
        $lCriaArquivoLock = false;
      }

      $this->iPIDProcesso = $aArquivoLock['iPIDProcesso'];

      db_app::import("configuracao.UsuarioSistema");
      $this->setDataInicio( date("d/m/Y", $aArquivoLock['tInicioProcesso'] ) );
      $this->setHoraInicio( date("H:i"  , $aArquivoLock['tInicioProcesso'] ) );

      /**
       * Poder ser adicionada aqui uma mensagem informando que o processo foi abortado.
       */

    }
    if ( !$lCriaArquivoLock ) {
      return false;
    }

    if ( $lTeste ) {
      return true;
    }
    $this->criaArquivoLock(1);
    db_app::import("configuracao.Agenda");
    $oAgenda = new Agenda();

    while (true) {

      $iInstante = time();

      /**
       * Percorre os processos existentes
       */
      foreach (self::$aTarefasExecutando as $sIndiceThread => $oThreadExecucao ) {

        $lLiberadoExecucao = false;

        $oJob = $oThreadExecucao->getJob();

        if ($oJob) {
          $lLiberadoExecucao = $oJob->isLiberadaExecucao();
        }

        /**
         * Caso o processo tenha sido concluido,
         * Ele é morto
         */
        if ( !$oThreadExecucao->isAlive() || $lLiberadoExecucao) {
          $oThreadExecucao->stop( SIGTERM, true, true);
          unset(self::$aTarefasExecutando[$sIndiceThread]);
        }

      }

      $aTarefas = $oAgenda->getTarefas( $iInstante );

      /**
       * Percorre as Tarefas Encontradas
       */
      foreach ($aTarefas as $oJob ) {

        require_once(modification($oJob->getCaminhoPrograma()));
        $sNomeTarefa = $oJob->getNomeClasse();

        $oTarefaExecucao = new $sNomeTarefa;
        $oTarefaExecucao->setTarefa($oJob);

        if ( $oTarefaExecucao->isLiberadaExecucao() ) {

          $oThread = new Thread(array($oTarefaExecucao, 'iniciar'));
          $oThread->start();

          self::$aTarefasExecutando[$oJob->getNomeClasse()] = $oThread;
        }
      }

      unset($aTarefas);
      sleep(60);
    }

    return true;
  }

  /**
   * Para o Serviço do Gerenciador de Tarefas
   */
  public function pararServico() {

    if ( !posix_kill($this->getPIDProcesso(), SIGTERM) ) {
      throw new Exception("Erro ao Terminar processo");
    }
    $this->criaArquivoLock(2, "Servico parado pelo Sistema");
  }

  /**
   * Cria/Altera arquivo de Lock do Gerenciador de Tarefas
   * @param $iTipoGravacao - tipo de Gravação 1 - Processo Iniciado
   *                                          2 - Processo Cancelado
   * @param $sObservacoes  - Observações sobre a manutenção do arquivo de lock.
   */
  private function criaArquivoLock($iTipoGravacao, $sObservacoes = "") {

    $this->iPIDProcesso = posix_getpid();

    $oXMLWriter = new XMLWriter;
    $oXMLWriter->openURI($this->getCaminhoArquivoLock());
    $oXMLWriter->setindent(true);
    $oXMLWriter->startDocument('1.0', 'UTF-8');

      $oXMLWriter->startElement('Documento');

        $oXMLWriter->startElement('iPIDProcesso');
        $oXMLWriter->text( $this->getPIDProcesso() );
        $oXMLWriter->endElement();

        $oXMLWriter->startElement('tInicioProcesso');
        $oXMLWriter->text( time() );
        $oXMLWriter->endElement();

        $oXMLWriter->startElement('iUsuario');
        $oXMLWriter->text( /* db_getsession('DB_usuario'); */ 1 );
        $oXMLWriter->endElement();

        $oXMLWriter->startElement('iInstituicao');
        $oXMLWriter->text( 1 );
        $oXMLWriter->endElement();

        $oXMLWriter->startElement('iSituacao');
        $oXMLWriter->text( $iTipoGravacao );
        $oXMLWriter->endElement();

        $oXMLWriter->startElement('sObservacoes');
        $oXMLWriter->text( $sObservacoes );
        $oXMLWriter->endElement();

      $oXMLWriter->endElement();
    $oXMLWriter->endDocument();


  }

  /**
   * Retorna apenas uma instancia da classe
   */
  public static function getInstance(){

    if ( is_null(self::$oInstance) ) {
      self::$oInstance = new TaskManager;
    }
    return self::$oInstance;
  }

  /**
   *
   * @throws BusinessException caso a instancia
   */
  public function __clone() {
    throw new BusinessException("Instancia não pode ser clonada");
  }

}

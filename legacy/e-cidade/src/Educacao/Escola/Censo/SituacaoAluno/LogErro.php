<?php

namespace ECidade\Educacao\Escola\Censo\SituacaoAluno;

use \DBLogJSON;

/**
 * Classe responsável para registrar os logs de erro
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.2 $
 */
class LogErro {

  private static $instance;
  private $arquivo;
  private $oLog;

  public static function getInstance() {


    if (null === self::$instance) {

      self::$instance = new static();

      $sArquivo   = "tmp/log_exportacao_situacao_aluno_" . time() . ".json";
      self::$instance->oLog = new \DBLogJSON($sArquivo);

      self::$instance->arquivo = $sArquivo;
    }

    return self::$instance;
  }

  public static function log($MensagemLog, $iIdentificador) {

    $oMensagem                 = new \stdClass();
    $oMensagem->iIdentificador = $iIdentificador;
    $oMensagem->sErro          = utf8_encode($MensagemLog);
    self::getInstance()->oLog->log($oMensagem, \DBLog::LOG_ERROR);
  }

  /**
   * Registro os logs de erro na importacao da situação do aluno
   * @param  string $MensagemLog
   */
  public static function logSituacao($MensagemLog, $iTipo = \DBLog::LOG_ERROR) {

    $oMensagem            = new \stdClass();
    $oMensagem->sMensagem = utf8_encode($MensagemLog);
    self::getInstance()->oLog->log($oMensagem, $iTipo);
  }


  public static function close() {
    self::getInstance()->oLog->finalizarLog();
  }

  public static function fileName() {
    return self::getInstance()->arquivo;
  }

  private function __construct(){ }
  private function __clone(){ }
  private function __wakeup(){ }
}
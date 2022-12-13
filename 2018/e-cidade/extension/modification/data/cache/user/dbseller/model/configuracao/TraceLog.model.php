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

/**
 * TraceLog
 * @author Rafael Serpa Nery  <rafael.nery@dbseller.com.br>
 */
class TraceLog {

  /**
   * Opções do tracelog
   */
  private $lActive          = false;
  private $lShowAccount     = false;
  private $lShowDefault     = false;
  private $lShowSourceInfo  = false;
  private $lShowFunctionName= false;
  private $lShowTime        = true;
  private $lShowBackTrace   = false;
  private $sDiretorio       = "./";

  /**
   * Caminho do Arquivo do Tracelog
   */
  private $sFilePath = null;
  private $aComandos = array();

  private static $oInstanciaTraceLog;

  /**
   * Construtor da Classe
   * @access private
   * @param string $sDiretorio
   */
  private function __construct($sDiretorio = "./") {

  	$this->sDiretorio = $sDiretorio;

  	$this->sFilePath = "tmp/TRACELOG_FILE_DBSeller_".date('Ymd').".log";
    $this->persistSession();
    return;
  }


  /**
   * Salva objeto na sessão
   * @access public
   */
  public function persistSession() {

    $_SESSION['TracelogObject'] = serialize($this);
    return;
  }

  /**
   * Valida se o tracelog esta ativo
   *
   * @access public
   * @return boolean
   */
  public function isActive() {
    return $this->lActive;
  }

  /**
   * Singleton para o tracelog, utiliza mesma instancia do objeto
   *
   * @static
   * @access public
   * @return TraceLog
   */
  public static function getInstance() {

    if ( isset($_SESSION['TracelogObject']) ) {
      TraceLog::$oInstanciaTraceLog = unserialize($_SESSION['TracelogObject']);
    }

    if ( empty(Tracelog::$oInstanciaTraceLog) ) {
      TraceLog::$oInstanciaTraceLog = new TraceLog();
    }
    return TraceLog::$oInstanciaTraceLog;
  }

  /**
   * Escreve no arquivo
   *
   * @param mixed $sMessage
   * @access public
   * @return void
   */
  public function write( $sMessage ) {

    $rsFile    = fopen($this->sDiretorio.$this->sFilePath, 'a');
    fputs($rsFile,$sMessage);
    fclose($rsFile);
    return;
  }

  /**
   * Retorna o caminho do arquvo
   * @access public
   * @return string
   */
  public function getFilePath() {
    return $this->sDiretorio.$this->sFilePath;
  }

  /**
   * Verifica se é mostrado
   *
   * @param mixed$sProperty
   * @access public
   * @return boolean
   * @throws ParameterException
   */
  public function isDisplayed( $sProperty ) {

    if ( !isset($this->{"lShow".$sProperty}) ) {
      throw new ParameterException("Propriedade {$sProperty} não encontrada.");
    }

    return $this->{"lShow".$sProperty};
  }

  /**
   * Define uma propriedade do tracelog
   * @param mixed $sProperty
   * @param mixed $sValue
   * @throws ParameterException
   */
  public function setProperty( $sProperty, $sValue ) {

    if ( !isset($this->{$sProperty}) ) {
      throw new ParameterException("Propriedade {$sProperty} não encontrada.");
    }
    $this->{$sProperty} = $sValue;
    $this->persistSession();
    return;
  }

  /**
   * @param $sDiretorio
   */
  public function setDiretorio($sDiretorio) {
  	$this->sDiretorio = $sDiretorio;
  	$this->persistSession();
  	return;
  }

  /**
   * Retorna o Backtrace Formatado
   *
   * @param mixed $aBackTraceData
   * @access public
   * @return void
   */
  public function getFormatedBacktrace( $aBackTraceData ) {

    $sBackTrace     = "";
    $aBackTraceData = array_reverse($aBackTraceData);
    unset($aBackTraceData[count( $aBackTraceData ) - 1]);
    unset($aBackTraceData[count( $aBackTraceData ) - 1]);

    $oTracelog      = TraceLog::getInstance();
    $aBackTrace     = array();

    foreach ( $aBackTraceData as $iIndice => $aRouteData ) { if (empty($aRouteData['file'])) {continue;} 

      $sFunction    = $aBackTraceData[$iIndice]['function'];
      $sFile        = explode("/",$aRouteData['file']);
      $sFile        = $sFile[count($sFile) - 1];
      $sBackTrace   = $sFile . ":" . $aRouteData['line'];
      if ( $this->lShowFunctionName ) {
        $sBackTrace .= "({$sFunction})";
      }

      $aBackTrace[] = $sBackTrace;
    }

    if ( $this->lShowBackTrace ) {
      return implode(" > ", $aBackTrace);
    }

    return isset($aBackTrace[count($aBackTrace) - 1]) ? $aBackTrace[count($aBackTrace) - 1] : '';
  }

  /**
   * Monta a mensagem para ser enviada ao Tracelog
   *
   * @param mixed $sSql
   * @param boolean $lErro
   * @access public
   * @return void
   */
  public function makeMessage($sSql, $lErro) {

    if ( !$this->lShowAccount ) {

      $aWordsBlock = array(
        "db_acount",
        "db_syscampo",
        "db_sysarquivo",
        "db_sysarqcamp",
        "db_usuariosonline",
        "db_itensmenu",
        "db_logsacessa"
      );

      foreach($aWordsBlock as $sWord) {

        $mAchouString = strpos($sSql, $sWord);

        if ( $mAchouString ) {
          return;
        }
      }
    }

    $this->aComandos[] = 'begin';
    $this->aComandos[] = 'commit';
    $this->aComandos[] = 'rollback';

    foreach( $this->aComandos as $sComando ) {

      if( !$this->lShowDefault ) {

        $aRegistrosPadrao = array( 'db_itensmenu', 'pg_backend_pid', 'ultimaversao', 'ultimarelease', 'pg_stat_activity', 'usapcasp' );
        foreach( $aRegistrosPadrao as $sRegistro ) {

          $mRegistro = strstr(strtolower($sSql), $sRegistro);
          if( $mRegistro ) {
            return;
          }
        }
      }

      $mComando = strstr(strtolower($sSql), $sComando);

      if (is_string($mComando)) {

        $sInfo      = TraceLog::getFormatedBacktrace(debug_backtrace());
        $sFlag      = $lErro ? "ERRO" : "INFO";
        $sData      = date("d/m/Y - H:i:s");
        $sSql       = str_replace("\n", "", $sSql);
        $sSql       = preg_replace("/\s+/", " ", $sSql);
        $sMensagem  = "[";
        $sMensagem .= "$sFlag";

        if ($this->lShowSourceInfo || $this->lShowBackTrace) {
          $sMensagem .= " - $sInfo";
        }

        if ($this->lShowTime) {
          $sMensagem .= " | $sData";
        }

        $sMensagem .= "] ";
        $sMensagem .= trim($sSql . ';');
        $sMensagem .= "\n";
        $this->write($sMensagem);
      }
    }
  }

  /**
   * Retorna os comandos exibidos no tracelog
   * @return array
   */
  public function getComandos() {
    return $this->aComandos;
  }
}

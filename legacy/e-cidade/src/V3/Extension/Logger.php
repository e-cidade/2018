<?php
namespace ECidade\V3\Extension;

use Exception;

/**
 * @package Extension
 * @todo user monolog
 */
class Logger {

  const QUIET = 0;
  const INFO = 1;
  const NOTICE = 2;
  const WARNING = 3;
  const ERROR = 4;
  const DEBUG = 5;

  /**
   * @var string
   */
  protected $path;

  /**
   * @var string
   */
  protected $file;

  /**
   * @var integer
   */
  protected $verbosity = 0;

  /**
   * @var callable[]
   */
  protected $handlers = array();

  /**
   * @var array
   */
  protected $levels = array(
    0 => 'QUIET',
    1 => 'INFO',
    2 => 'NOTICE',
    3 => 'WARNING',
    4 => 'ERROR',
    5 => 'DEBUG',
  );

  /**
   * @param string $path - caminho do arquivo para escrever log
   *  php://stdout : visivel somente no CLI
   *  php://output : visivel CLI e APACHE
   */
  public function __construct($path = 'php://stdout', $verbosity = self::QUIET) {

    $this->path = $path;
    $this->setFile($path);
    $this->setVerbosity($verbosity);
  }

  public function __destruct() {
    fclose($this->file);
  }

  /**
   * @param string $message
   */
  public function write($message) {
    return fwrite($this->file, $message);
  }

  /**
   * @param string $message
   */
  public function writeln($message) {
    return $this->write($message . PHP_EOL);
  }

  /**
   * @param string $message
   */
  public function info($message) {
    return $this->verbose($message, static::INFO);
  }

  /**
   * @param string $message
   */
  public function notice($message) {
    return $this->verbose($message, static::NOTICE);
  }

  /**
   * @param string $message
   */
  public function warning($message) {
    return $this->verbose($message, static::WARNING);
  }

  /**
   * @param string $message
   */
  public function error($message) {
    return $this->verbose($message, static::ERROR);
  }

  /**
   * @param string $message
   */
  public function debug($message) {
    return $this->verbose($message, static::DEBUG);
  }

  /**
   * @param string message
   * @param integer $verbosity
   */
  public function verbose($message, $verbosity) {

    if ($verbosity > $this->getVerbosity()) {
      return false;
    }

    $output = '['. date('Y-m-d H:i:s') .'] ' . $this->levels[$verbosity] . ': '. $message;

    // existe funcao para modificar conteudo do log
    if (isset($this->handlers[$verbosity])) {
      foreach ($this->handlers[$verbosity] as $handler) {
        $output = $handler($output, $verbosity);
      }
    }

    $this->writeln($output);

    $output = null;

    return true;
  }

  /**
   * @param string $path
   */
  public function setFile($path) {

    if (is_dir($path)) {
      throw new Exception('Caminho do log é um diretório: ' . $path);
    }

    // Somente para escrita
    // Coloca o ponteiro do arquivo no final do arquivo
    // Se o arquivo nao existir cria.
    $this->file = fopen($path, 'a');
    $this->filePath = $path;

    if (!is_resource($this->file)) {
      throw new Exception('Não foi possível abrir o arquivo para escrita: ' . $path);
    }
  }

  /**
   * @param integer $verbosity
   */
  public function setVerbosity($verbosity) {
    $this->verbosity = $verbosity;
  }

  /**
   * @return integer
   */
  public function getVerbosity() {
    return $this->verbosity;
  }

  /**
   * @param callable $handler
   * @param integer $level
   * @return \ECidade\Extension\Logger
   */
  public function addHandler($handler, $level = null) {

    if ($level === null) {
      foreach ($this->levels as $level => $name) {
        if ($level == 0) {
          continue;
        }
        $this->addHandler($handler, $level);
      }
    } else {
      $this->handlers[$level][] = $handler;
    }

    return $this;
  }

}

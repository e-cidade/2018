<?php
namespace ECidade\V3\Extension;

use \Exception;

/**
 * @package core
 */
class Storage {

  /**
   * @string
   */
  protected $path;

  /**
   * @var mixed
   */
  private $data;

  /**
   * @var boolean
   */
  private $serialize = true;

  /**
   * @param string $path
   */
  public function __construct($path) {
    $this->path = $path;
  }

  /**
   * @param boolean $serialize
   */
  public function setSerialize($serialize) {
    $this->serialize = $serialize;
  }

  /**
   * @param mixed $data
   */
  public function setData($data) {
    $this->data = $data;
  }

  /**
   * @return mixed
   */
  public function getData() {
    return $this->data;
  }

  /**
   * @param string $path
   */
  public function setPath($path) {
    $this->path = $path;
  }

  /**
   * @param string $path
   * @return string
   */
  public function getPath() {
    return $this->path;
  }

  /**
   * @return Storage
   */
  public function clear() {
    $this->data = null;
  }

  /**
   * @return boolean
   */
  public function exists() {
    return file_exists($this->path) && !is_dir($this->path);
  }

  /**
   * @return Storage
   */
  public function save() {

    $this->createDirectory();

    $data = $this->serialize ? serialize($this->data) : $this->data;

    if (empty($data)) {
      throw new Exception("Sem conteudo para salvar no arquivo: {$this->path}");
    }

    if (!file_put_contents($this->path, $data, LOCK_EX)) {
      throw new Exception("Nao foi possivel criar arquivo de cache: {$this->path}");
    }
  }

  /**
   * @return Storage
   */
  public function load() {

    $this->data = null;

    if (!$this->exists()) {
      return false;
    }

    $data = file_get_contents($this->path);

    // @FIXME - remover
    // como houve troca de namespaces na incorporacao do ecidade 3 ao ecidade,
    // os arquivos serializados ficaram corrompidos
    // este metodo serve para corrigi-los
    if ($this->serialize) {
      $data = $this->fixNamespaces($data);
    }

    $this->data = $this->serialize ? unserialize($data) : $data;

    return (boolean) $this->data;
  }

  /**
   * @return boolean
   */
  public function remove() {

    if ($this->exists() && !@unlink($this->path)) {
      throw new Exception("Não foi possivel remover cache: $this->path");
    }

    return true;
  }

  /**
   * @return boolean
   */
  public function touch() {

    $this->createDirectory();

    if (!@touch($this->path)) {
      throw new Exception("Não foi possivel alterar tempo de modificação: $this->path");
    }

    return true;
  }

  /**
   * @return boolean
   */
  private function createDirectory() {

    $dir = dirname($this->path);

    if (!is_dir($dir) && !mkdir($dir, 0775, true)) {
      throw new Exception("Não foi possivel criar diretório de cache: " . $dir);
    }

    return true;
  }

  /**
   * @FIXME - remover
   * Essa função deve ser removida com o tempo pois somente serve para migracao do namespace
   */
  public function fixNamespaces($data) {

    $pattern = '/(.):(\\d*):(".?ECidade\\\\(?:Config|Datasource|Error|Event|Extension|Modification|Window))/';

    return preg_replace_callback($pattern, function($matches) {

      $fixedData = array(
        $matches[1],
        (intval($matches[2]) + 3),
        str_replace("ECidade", "ECidade\V3", $matches[3])
      );

      return implode(":", $fixedData);
    }, $data);

  }

}

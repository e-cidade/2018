<?php
namespace ECidade\V3\Modification\Data;

use \ECidade\V3\Extension\PackageMetadata;

/**
 * @package modification
 */
class Modification extends PackageMetadata {

  /**
   * @var string
   */
  private $group;

  /**
   * @var array
   */
  private $operations = array();

  /**
   * @var array
   */
  private $filesOperations = array();

  /**
   * @var array
   */
  private $filesErrors = array();

  /**
   * @var string $path
   */
  public function __construct($id) {
    parent::__construct(ECIDADE_MODIFICATION_DATA_PATH . 'modification/' . $id);
  }

  public function setGroup($group) {
    $this->group = $group;
  }

  public function getGroup() {
    return $this->group;
  }

  public function hasGroup() {
    return !empty($this->group);
  }

  /**
   * Operacoes de um arquivo
   *
   * @param string $path
   * @return array
   */
  public function getOperationsFile($path) {

    $operations = array();
    if (isset($this->filesOperations[$path])) {
      foreach ($this->filesOperations[$path] as $operationPath) {
        $operations = array_merge($operations, $this->getOperation($operationPath));
      }
    }

    return $operations;
  }

  public function setOperations(Array $operations) {
    $this->operations = $operations;
  }

  public function getOperations() {
    return $this->operations;
  }

  public function getOperation($path) {
    return $this->operations[$path];
  }

  public function setFilesOperations(Array $filesOperations) {
    $this->filesOperations = $filesOperations;
  }

  public function addFileOperations($file, array $operationsPath) {
    $this->filesOperations[$file] = $operationsPath;
  }

  public function getFilesOperations($path = null) {

    $filesOperations = array();

    if ($path === null) {
      $filesOperations = $this->filesOperations;
    }

    if ($path !== null && isset($this->filesOperations[$path])) {
      $filesOperations = $this->filesOperations[$path];
    }

    return $filesOperations;
  }

  public function getFiles() {
    return array_keys($this->filesOperations);
  }

  public function addFileError($file, array $error) {

    if (!isset($this->filesErrors[$file])) {
      $this->filesErrors[$file] = array();
    }

    $this->filesErrors[$file][] = $error;
  }

  public function setFileError($file, array $errors) {
    $this->filesErrors[$file] = $errors;
  }

  /**
   * @param string $path
   * @return array
   */
  public function getFileErrors($path) {
    return isset($this->filesErrors[$path]) ? $this->filesErrors[$path] : array();
  }

  /**
   * @return array
   */
  public function getFilesErrors() {
    return $this->filesErrors;
  }

}

<?php

namespace ECidade\V3\Modification\Parse;

use DOMDocument, Exception;

use \ECidade\V3\Extension\Parse\XML as XMLParse;
use \ECidade\V3\Extension\Glob;

/**
 * Modificacoes
 */
class Modification extends XMLParse {

  /**
   * @var integer
   */
  private $id;

  /**
   * @var string
   */
  private $group;

  /**
   * @var string
   */
  private $label;

  /**
   * @var string
   */
  private $type;

  /**
   * @var array
   */
  private $operations = array();

  /**
   * @var array
   */
  private $filesOperations = array();

  /**
   * @return integer
   */
  public function getId() {
    return $this->id;
  }

  /**
   * @return string
   */
  public function getGroup() {
    return $this->group;
  }

  /**
   * @return string
   */
  public function getLabel() {
    return $this->label;
  }

  /**
   * @return string
   */
  public function getType() {
    return $this->type;
  }

  /**
   * @return array
   */
  public function getFilesOperations() {
    return $this->filesOperations;
  }

  /**
   * @return array
   */
  public function getOperations() {
    return $this->operations;
  }

  /**
   * @return \ECidade\Modification\Parse\Modification
   */
  public function parse() {

    $this->operations = array();
    $this->filesOperations = array();
    $this->type = 'global';
    $this->id = null;
    $this->label = null;
    $this->group = null;

    $nodeId = $this->dom->getElementsByTagName('id')->item(0);
    $nodeLabel = $this->dom->getElementsByTagName('label')->item(0);
    $nodeModification = $this->dom->getElementsByTagName('modification')->item(0);
    $nodeType = $nodeModification->getElementsByTagName('type')->item(0);
    $nodeGroup = $nodeModification->getElementsByTagName('group')->item(0);

    if (empty($nodeId) || empty($nodeId->textContent)) {
      throw new Exception("ID da modificação não informado");
    }

    $this->id = $nodeId->textContent;

    if (!empty($nodeGroup)) {
      $this->group = $nodeGroup->textContent;
    }

    if (!empty($nodeLabel)) {
      $this->label = mb_strtolower($nodeLabel->textContent);
    }

    if (!empty($nodeType)) {
      $this->type = mb_strtolower($nodeType->textContent);
    }

    $files = $nodeModification->getElementsByTagName('file');

    foreach ($files as $nodeFile) {

      // regex de caminhos de arquivos para ignorar
      $ignoreRegexPath = array();

      // busca atributo path da tag <ignore> e converte de glob pattern para regex
      foreach ($this->xpath->query('ignore', $nodeFile) as $nodeIgnore) {
        $ignoreRegexPath[] = Glob::toRegex($nodeIgnore->getAttribute('path'), true, false);
      }

      $basePath = $nodeFile->getAttribute('base');
      $path =  $nodeFile->getAttribute('path');
      $recursive = $nodeFile->getAttribute('recursive') == 'true';
      $nodeOperations = $nodeFile->getElementsByTagName('operation');

      $operationsPath = $basePath . $path;

      foreach ($nodeOperations as $index => $nodeOperation) {
        $this->operations[$operationsPath][] = new Operation($nodeOperation, $index);
      }

      $basePath = !empty($basePath) ? ECIDADE_PATH . ltrim($basePath, '/') : ECIDADE_PATH;
      $this->loadFiles($path, $basePath, $recursive, $ignoreRegexPath);
    }

    return $this;
  }

  /**
   * Busca arquivos pelo glob-path
   *
   * @param string $path
   * @param string $basePath
   * @param bool $recursive
   * @param array $ignoreRegexPath
   * @return \ECidade\Modification\Parse\Modification
   */
  private function loadFiles($path, $basePath = ECIDADE_PATH, $recursive = false, $ignoreRegexPath = array()) {

    $files = Glob::find($path, $basePath, $recursive);
    $operationsPath = str_replace(ECIDADE_PATH, null, $basePath) . $path;

    foreach ($files as $file) {

      // Remove base, caminho absoluto
      $file = str_replace(ECIDADE_PATH, null, realpath($file));

      foreach ($ignoreRegexPath as $regex) {
        if (preg_match($regex, $file)) {
          continue 2;
        }
      }

      if (!isset($this->filesOperations[$file])) {
        $this->filesOperations[$file] = array();
      }

      if (!in_array($operationsPath, $this->filesOperations[$file])) {
        $this->filesOperations[$file][] = $operationsPath;
      }
    }

    return $this;
  }

}

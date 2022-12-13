<?php

namespace ECidade\V3\Modification\Parse;

use Exception;

use \ECidade\V3\Modification\Exception\Abort as AbortException;
use \ECidade\V3\Modification\Parse\Operation;

class File {

  /**
   * @var string
   */
  private $path;

  /**
   * @var string
   */
  private $content;

  /**
   * @var Operation[]
   */
  private $operations = array();

  /**
   * @var Operation[]
   */
  private $failOperations = array();

  /**
   * @param string $path
   */
  public function __construct($path = null) {
    $this->path = $path;
  }

  /**
   * @return File
   */
  public function load() {

    if (!is_readable($this->path)) {
      throw new Exception("Sem permissão de leitura no arquivo: ". $this->path);
    }

    $this->content = file_get_contents($this->path);
    return $this;
  }

  /**
   * @return string
   */
  public function getPath() {
    return $this->path;
  } 

  public function setContent($content) {
    return $this->content = $content;
  }

  /**
   * @return string
   */
  public function getContent() {
    return $this->content;
  }

  /**
   * @param StdClass $operation
   * @return File
   */
  public function addOperation($operation) {

    $this->operations[] = $operation;
    return $this;
  }

  /**
   * @return File
   */
  public function setOperations(Array $operations) {

    $this->operations = $operations;
    return $this;
  }

  /**
   * @return stdClass[]
   */
  public function getOperations() {
    return $this->operations;
  }

  /**
   * @return Operation[]
   */
  public function getFailOperations() {
    return $this->failOperations;
  }

  /**
   * @return File
   */
  public function parse() {

    $parseContent = $this->content;

    foreach($this->operations as $operation) {

      $parseContent = $operation->execute($parseContent);

      // conteudo alterado pela operacao
      if ($parseContent != $this->content) {

        $this->content = $parseContent;
        continue;
      }

      // operacao nao alterou conteudo
      $this->failOperations[] = $operation;

      // abort 
      if ($operation->error() === Operation::ERROR_ABORT) {
        throw new AbortException($operation->label());
      }
    } 

    return $this;
  }

}

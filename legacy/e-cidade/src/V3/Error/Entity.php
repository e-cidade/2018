<?php

namespace ECidade\V3\Error;

class Entity {

  private $id;

  private $type;

  private $suppress;

  private $message;

  private $file;

  private $line;

  private $trace;

  private $time;

  private $code;

  const CODE_UNCAUGHT_EXCEPTION = 'E01';
  const CODE_EXCEPTION = 'E02';

  public function setId($id) {
    $this->id = $id;
  }

  public function getId() {
    return $this->id;
  }

  public function setType($type) {
    $this->type = $type;
  }

  public function getType() {
    return $this->type;
  }

  public function setSuppress($suppress) {
    $this->suppress = $suppress;
  }

  public function getSuppress() {
    return $this->suppress;
  }

  public function setMessage($message) {
    $this->message = $message;
  }

  public function getMessage() {
    return $this->message;
  }

  public function setFile($file) {
    $this->file = $file;
  }

  public function getFile() {
    return $this->file;
  }

  public function setLine($line) {
    $this->line = $line;
  }

  public function getLine() {
    return $this->line;
  }

  public function setTrace($trace) {
    $this->trace = $trace;
  }

  public function getTrace() {
    return $this->trace;
  }

  public function setTime($time) {
    $this->time = $time;
  }

  public function getTime() {
    return $this->time;
  }

  public function getCode() {
    return $this->code;
  }

  public function setCode($code) {
    $this->code = $code;
  }

  public function generateId() {

    $id = $this->type . $this->file . $this->line . $this->message;

    if ($this->trace) {

      $data = $this->trace->getSanitizedData();

      array_walk_recursive($data, function($value, $key) use (&$id) {
        $id .= $key.$value;
      });
    }

    return $this->id = md5($id);
  }

  public function getTypeAsString() {

    $errorType = array(
      E_ERROR             => 'E_ERROR',
      E_WARNING           => 'E_WARNING',
      E_PARSE             => 'E_PARSE',
      E_NOTICE            => 'E_NOTICE',
      E_CORE_ERROR        => 'E_CORE_ERROR',
      E_CORE_WARNING      => 'E_CORE_WARNING',
      E_COMPILE_ERROR     => 'E_COMPILE_ERROR',
      E_COMPILE_WARNING   => 'E_COMPILE_WARNING',
      E_USER_ERROR        => 'E_USER_ERROR',
      E_USER_WARNING      => 'E_USER_WARNING',
      E_USER_NOTICE       => 'E_USER_NOTICE',
      E_STRICT            => 'E_STRICT',
      E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
      E_DEPRECATED        => 'E_DEPRECATED',
      E_USER_DEPRECATED   => 'E_USER_DEPRECATED'
    );

    return isset($errorType[$this->type]) ? $errorType[$this->type] : 'Unknown PHP error';
  }

  public function toArray() {
    return array(
      'id' => $this->id,
      'type' => $this->type,
      'suppress' => $this->suppress,
      'message' => $this->message,
      'file' => $this->file,
      'line' => $this->line,
      'time' => $this->time,
      'code' => $this->code,
      'trace' => $this->trace ? $this->trace->getSanitizedData() : array()
    );
  }

}

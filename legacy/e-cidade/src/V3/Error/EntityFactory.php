<?php

namespace ECidade\V3\Error;

use \Exception;
use \ECidade\V3\Error\Trace;
use \ECidade\V3\Error\Entity;

class EntityFactory {

  public static function createFromException(Exception $exception) {

    $type = E_ERROR;
    $suppress = error_reporting() === 0;
    $message = $exception->getMessage();
    $line = $exception->getLine();
    $file = $exception->getFile();
    $time = time();
    $trace = new Trace($exception);

    $entity = static::create($type, $suppress, $message, $file, $line, $time, $trace);
    $entity->setCode(Entity::CODE_EXCEPTION);

    return $entity;
  }

  public static function create($type = null, $suppress = null, $message = null, $file = null, $line = null, $time = null, $trace = null) {

    $entity = new Entity();
    $entity->setType($type);
    $entity->setSuppress($suppress);
    $entity->setMessage($message);
    $entity->setFile(Sanitizer::clearPath($file));
    $entity->setLine($line);
    $entity->setTime($time);
    $entity->setTrace($trace);
    $entity->generateId();

    return $entity;
  }

}
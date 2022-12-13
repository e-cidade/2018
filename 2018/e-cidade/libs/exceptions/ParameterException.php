<?php

/**
 * Classe de Excessão para parametros.
 * @package Core
 * @author Iuri Guntchnigg
 *
 */
class ParameterException extends Exception {
  
  /**
   * Excessoes de Parametros
   *@param message[optional] 
   *@param code[optional] 
   */
  public function __construct($message = null , $code = null) {
    parent::__construct($message, $code);
  }
}
?>
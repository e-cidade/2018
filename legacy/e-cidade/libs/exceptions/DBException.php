<?php
/**
 * Classe de Excessoes para banco de dados
 * @package Core
 * @author Iuri Guntchnigg
 */
class DBException extends Exception {
  
  /**
   * Excessão para Erros de Banco de dados
   *@param message[optional] 
   *@param code[optional] 
   */
  public function __construct($message = null, $code = null) {
    parent::__construct($message, $code);
  }
}

?>
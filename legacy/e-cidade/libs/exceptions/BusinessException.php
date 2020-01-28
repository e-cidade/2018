<?php
/**
 * Classe de Excessoes de regras de Negocios
 * @package Core
 * @author Iuri Guntchnigg
 */
class BusinessException extends Exception {
  
  /**
   * Excess�o para Erros de regra de Negocios
   *@param message[optional] 
   *@param code[optional] 
   */
  public function __construct($message = null, $code = null) {
    parent::__construct($message, $code);
  }
}
?>
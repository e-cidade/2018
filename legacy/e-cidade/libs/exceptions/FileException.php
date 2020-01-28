<?php
/**
 * Classe de Excessoes para Arquivos
 * @package Core
 * @author Iuri Guntchnigg
 */
class FileException extends Exception {
  
  /**
   * Excessão para Erros de Arquivo
   *@param message[optional] 
   *@param code[optional] 
   */
  public function __construct($message = null, $code = null) {
    parent::__construct($message, $code);
  }
}

?>
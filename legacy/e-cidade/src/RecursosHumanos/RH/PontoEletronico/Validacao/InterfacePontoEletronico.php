<?php

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Validacao;

/**
 * Interface InterfacePontoEletronico
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Validacao
 */
interface InterfacePontoEletronico {

  /**
   * @return boolean
   */
  public function validar();

  /**
   * @return array
   */
  public function getErros();
}
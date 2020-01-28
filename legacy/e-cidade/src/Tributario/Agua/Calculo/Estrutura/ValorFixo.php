<?php

namespace ECidade\Tributario\Agua\Calculo\Estrutura;

class ValorFixo extends Estrutura {

  protected function calcularValor() {
    return $this->oEstrutura->getValor();
  }

}
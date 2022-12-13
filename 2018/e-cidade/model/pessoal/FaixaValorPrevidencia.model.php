<?php

/**
 * Classe para manipulação das Faixas de Previdencia.
 */
class FaixaValorPrevidencia extends FaixaValor {
  
  private $nPercentual;

  private $nTetoInativos;

  public function setPercentual($nPercentual) {
    $this->nPercentual = $nPercentual;
  }

  public function getPercentual() {
    return $this->nPercentual;
  }

  public function setTetoInativos($nTetoInativos) {
    $this->nTetoInativos = $nTetoInativos;
  }

  public function getTetoInativos() {
    return $this->nTetoInativos;
  }
}
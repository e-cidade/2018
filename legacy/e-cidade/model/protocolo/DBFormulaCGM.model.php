<?php 

class DBFormulaCGM extends DBFormula {

  public function __construct( CgmBase $oCGM ) {
    $this->adicionar("CODIGO_CGM", $oCGM->getCodigo());  
  }
}

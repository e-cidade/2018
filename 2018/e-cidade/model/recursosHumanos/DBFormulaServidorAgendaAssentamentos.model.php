<?php 

class DBFormulaServidorAgendaAssentamentos extends DBFormula {

  public function __construct( Servidor $oServidor ) {
    $this->adicionar("SERVIDOR", $oServidor->getMatricula());
  }
}
<?php 

class DBFormulaAssentamento extends DBFormula {

  public function __construct( Assentamento $oAssentamento ) {
    $this->adicionar("CODIGO_ASSENTAMENTO", $oAssentamento->getCodigo());  
    $this->adicionar("ANO_COMPETENCIA_ANTERIOR", DBPessoal::getCompetenciaFolha()->getCompetenciaAnterior()->getAno());
    $this->adicionar("MES_COMPETENCIA_ANTERIOR", DBPessoal::getCompetenciaFolha()->getCompetenciaAnterior()->getMes());
  }
}
